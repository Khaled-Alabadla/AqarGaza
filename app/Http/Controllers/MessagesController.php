<?php

namespace App\Http\Controllers;

use App\Events\ChatDeleted;
use App\Events\MessageCreated;
use App\Events\MessageDeleted;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MessagesController extends Controller
{
    /**
     * List messages in a chat
     */
    public function index($chatId)
    {

        $chat = Chat::with(['messages.sender', 'messages.receiver'])
            ->findOrFail($chatId);

        return response()->json([
            'chat' => $chat,
            'messages' => $chat->messages()->with('sender', 'receiver')
                // ->orderBy('created_at', 'desc')
                ->get()
        ]);
    }

    /**
     * Store a new message
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:5000',
            'chat_id' => 'nullable|exists:chats,id',
        ]);

        $user = Auth::user();

        $receiverId = $request->receiver_id;

        DB::beginTransaction();

        try {
            $chat = Chat::where(function ($q) use ($user, $receiverId) {
                $q->where('user_id', $user->id)
                    ->where('receiver_id', $receiverId);
            })->orWhere(function ($q) use ($user, $receiverId) {
                $q->where('user_id', $receiverId)
                    ->where('receiver_id', $user->id);
            })->first();

            if (!$chat) {
                $chat = Chat::create([
                    'user_id' => $user->id,
                    'receiver_id' => $receiverId,
                ]);
            }

            // 2. Check file type
            $type = 'text';
            $messageContent = $request->post('message');

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $type = 'attachment';
                $directory = 'uploads/messages';
                $file_name = rand() . time() . $request->file('attachment')->getClientOriginalName();
                $messageContent = json_encode([
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mimetype' => $file->getMimeType(),
                    'file_path' => $file_name,
                ]);
                $file_path = $request->file('attachment')->storeAs($directory, $file_name, 'public_uploads');
            }

            // 3. Create the message
            $message = $chat->messages()->create([
                'sender_id' => $user->id,
                'receiver_id' => $receiverId,
                'message' => $messageContent,
                'type' => $type,
            ]);


            $chat->update([
                'last_message_id' => $message->id
            ]);

            // 4. (Optional) broadcast the message
            broadcast(new MessageCreated($message));
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json([
            'chat_id' => $chat->id,
            'message' => $message,
            'message_content' => $messageContent,
        ], 201);
    }

    public function update(Request $request, Message $message)
    {
        // Ensure the authenticated user is the sender of the message
        if (Auth::id() !== $message->sender_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $type = $message->type;
            $messageContent = $request->post('message', $message->message);

            // Handle attachment update if provided
            if ($request->hasFile('attachment')) {
                // Delete old attachment if it exists
                if ($type === 'attachment' && $message->message) {
                    $oldAttachment = json_decode($message->message, true);
                    if (isset($oldAttachment['file_path'])) {
                        Storage::disk('public')->delete($oldAttachment['file_path']);
                    }
                }

                $file = $request->file('attachment');
                $messageContent = json_encode([
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mimetype' => $file->getMimeType(),
                    'file_path' => $file->store('attachments', ['disk' => 'public']),
                ]);
                $type = 'attachment';
            }

            // Update the message
            $message->update([
                'message' => $messageContent,
                'type' => $type,
                'updated_at' => now(),
            ]);

            // Update the chat's last_message_id if this is the latest message


            // (Optional) Broadcast the updated message
            // broadcast(new Message($message));

            DB::commit();

            return response()->json([
                // 'chat_id' => $chat->id,
                'message' => $message,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Mark all messages as read in a chat
     */
    public function markAsRead($chatId)
    {
        Message::where('chat_id', $chatId)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Marked as read']);
    }

    /**
     * Delete a specific message (for current user only)
     */


    public function destroy(Message $message)
    {
        // Ensure the authenticated user is the sender
        if (Auth::id() !== $message->sender_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Prevent deleting already deleted messages
        if ($message->message === 'تم حذف الرسالة') {
            return response()->json(['error' => 'Cannot delete a deleted message'], 403);
        }

        DB::beginTransaction();

        try {
            if ($message->type == 'attachment') {
                // Delete the attachment file if it exists
                $attachment = json_decode($message->message, true);
                if (isset($attachment['file_path'])) {
                    Storage::disk('public_uploads')->delete('messages/' . $attachment['file_path']);
                }
            }
            // Mark the message as deleted
            $message->update([
                'message' => 'تم حذف الرسالة',
                'type' => 'text',
            ]);

            // Check if all messages in the chat are deleted
            $chat = $message->chat;
            $remainingMessages = $chat->messages()->where('message', '!=', 'تم حذف الرسالة')->count();

            $isChatDeleted = false;
            if ($remainingMessages === 0) {
                // Delete the chat
                $chatId = $chat->id;
                $userId = $chat->user_id;
                $receiverId = $chat->receiver_id;
                $chat->delete();
                broadcast(new ChatDeleted($chatId, $userId, $receiverId));
                $isChatDeleted = true;
            } else {
                // Update last_message_id if the deleted message was the last one
                if ($chat->last_message_id === $message->id) {
                    $lastMessage = $chat->messages()->latest()->first();
                    $chat->update([
                        'last_message_id' => $lastMessage ? $lastMessage->id : null,
                    ]);
                }
            }

            // Broadcast the message deletion
            broadcast(new MessageDeleted($message));

            DB::commit();


            return response()->json([
                'message' => 'Message deleted successfully',
                'chat_id' => $chat->id,
                'is_chat_deleted' => $isChatDeleted,
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete message'], 500);
        }
    }
}

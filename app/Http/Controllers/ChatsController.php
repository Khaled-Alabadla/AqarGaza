<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $chats = Chat::where('user_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['creator', 'receiver', 'lastMessage', 'messages'])
            ->latest()
            ->get()
            ->map(function ($chat) use ($user) {
                $unreadCount = Message::where('chat_id', $chat->id)
                    ->where('receiver_id', $user->id)
                    ->whereNull('read_at')
                    ->count();
                return [
                    'id' => $chat->id,
                    'user_id' => $chat->user_id,
                    'receiver_id' => $chat->receiver_id,
                    'creator' => $chat->creator,
                    'receiver' => $chat->receiver,
                    'last_message' => $chat->lastMessage,
                    'unread_count' => $unreadCount,
                    'messages' => $chat->messages,
                ];
            });

        return response()->json($chats);
    }

    public function initiateChat(Request $request, $propertyId)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $property = Property::findOrFail($propertyId);
        $owner = $property->user;

        // Prevent users from initiating a chat with themselves
        if ($user->id === $owner->id) {
            return response()->json(['error' => 'لا يمكنك التواصل مع نفسك'], 403);
        }

        // Check for an existing chat
        $chat = Chat::where(function ($query) use ($user, $owner) {
            $query->where('user_id', $user->id)->where('receiver_id', $owner->id);
        })->orWhere(function ($query) use ($user, $owner) {
            $query->where('user_id', $owner->id)->where('receiver_id', $user->id);
        })->first();

        // Create a new chat if none exists
        if (!$chat) {
            $chat = Chat::create([
                'user_id' => $user->id,
                'receiver_id' => $owner->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['chat_id' => $chat->id], 200);
    }

    public function show($id)
    {
        $chat = Chat::with(['messages.sender', 'messages.receiver'])
            ->findOrFail($id);

        return $chat;
    }

    public function storeMessage(Request $request, $chatId)
    {
        $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $chat = Chat::findOrFail($chatId);

        if (!$chat) {
            $chat = Chat::create([]);
        }

        $message = $chat->messages()->create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json($message, 201);
    }

    public function markMessagesAsRead($chatId)
    {
        Message::where('chat_id', $chatId)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Messages marked as read']);
    }
}

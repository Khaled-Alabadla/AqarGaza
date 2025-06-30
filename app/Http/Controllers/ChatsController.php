<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    public function index()
    {
        // $user = Auth::user();
        $user = User::find(1);

        return $user->participatedChats()
            ->with(['creator', 'receiver', 'lastMessage', 'messages'])
            ->latest()
            ->get();
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

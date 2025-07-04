<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessengerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // $user = User::find(28);

        $chats = $user->participatedChats()
            ->with(['messages.sender', 'messages.receiver', 'lastMessage'])
            ->latest()
            ->get();

        return view('chat', compact('chats'));
    }
}

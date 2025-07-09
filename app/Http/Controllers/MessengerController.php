<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class MessengerController extends Controller
{
    public function index()
    {
        /** @var App/Model/User */

        $user = Auth::user();

        $chats = $user->participatedChats()
            ->with(['messages.sender', 'messages.receiver', 'lastMessage'])
            ->latest()
            ->get();

        return view('front.chat', compact('chats'));
    }
}

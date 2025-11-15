<?php

namespace App\Http\Controllers\Pages;

use App\Events\ChatSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChattingController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user_id ?? null;

        $users = User::where('id', '!=', auth()->id())
            ->get()
            ->map(function ($usr) {
                $lastChat = Chat::where(function ($query) use ($usr) {
                    $query->where('sender_id', auth()->id())
                        ->where('receiver_id', $usr->id);
                })
                    ->orWhere(function ($query) use ($usr) {
                        $query->where('sender_id', $usr->id)
                            ->where('receiver_id', auth()->id());
                    })
                    ->latest()
                    ->first();

                $usr->last_message = $lastChat ? $lastChat->message : null;
                $usr->last_message_time = $lastChat ? $lastChat->created_at->diffForHumans() : null;

                return $usr;
            })
            ->sortByDesc('last_message_time')
            ->values();

        $chats = collect();
        if ($userId) {
            $chats = Chat::with(['sender', 'receiver'])
                ->where(function ($query) use ($userId) {
                    $query->where('sender_id', auth()->id())
                        ->where('receiver_id', $userId);
                })
                ->orWhere(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->where('receiver_id', auth()->id());
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('pages.chatting.index', compact('users', 'chats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message'     => 'required|string|max:5000',
        ]);

        $chat = Chat::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message'     => $request->message,
        ]);

        // dd($chat);

        broadcast(new ChatSent($chat))->toOthers();

        return back();
    }
}

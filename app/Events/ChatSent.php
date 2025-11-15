<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // WAJIB
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;

    public function __construct(Chat $chat)
    {
        // ambil data sender untuk ditampilkan
        $this->chat = $chat->load(['sender']);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->chat->receiver_id);
    }

    public function broadcastAs()
    {
        return 'chat-sent';
    }

    public function broadcastWith()
    {
        return [
            'chat' => [
                'id'           => $this->chat->id,
                'sender_id'    => $this->chat->sender_id,
                'receiver_id'  => $this->chat->receiver_id,
                'sender_name'  => $this->chat->sender->name,
                'message'      => $this->chat->message,
                'created_at'   => $this->chat->created_at->format('H:i'),
            ]
        ];
    }
}

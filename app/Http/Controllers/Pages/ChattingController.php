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

        $auth   = auth()->user();
        $authId = $auth->id;
        $orgId  = $auth->organization_id;

        // Basis query: hanya user di organisasi yang sama, kecuali diri sendiri
        $baseQuery = User::where('id', '!=', $authId)
            ->where('organization_id', $orgId);

        if ($auth->can('view all chatting')) {
            // Lihat semua user dalam organisasi yang sama
            $userQuery = $baseQuery;
        } elseif ($auth->can('view inbox chatting')) {
            // Hanya tampilkan user yang pernah mengirim pesan ke kita (dalam org yang sama)
            $userQuery = $baseQuery->whereIn('id', function ($q) use ($authId) {
                $q->select('sender_id')
                    ->from('chat')
                    ->where('receiver_id', $authId);
            });
        } else {
            $userQuery = $baseQuery;
        }

        $users = $userQuery->get()
            ->map(function ($usr) use ($authId) {
                $lastChat = Chat::where(function ($query) use ($usr, $authId) {
                    $query->where('sender_id', $authId)
                        ->where('receiver_id', $usr->id);
                })
                    ->orWhere(function ($query) use ($usr, $authId) {
                        $query->where('sender_id', $usr->id)
                            ->where('receiver_id', $authId);
                    })
                    ->latest()
                    ->first();

                $usr->last_message = $lastChat?->message;
                $usr->last_message_time = $lastChat?->created_at?->diffForHumans();

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
                ->get()
                ->groupBy(function ($chat) {
                    return $chat->created_at->format('Y-m-d');
                });
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

    public function destroy($id)
    {
        $chat = Chat::find($id);

        if (!$chat) {
            return response()->json(['message' => 'Chat not found.'], 404);
        }

        $chat->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus pesan.']);;
    }
}

@extends('layouts.app')

@section('title', 'Chatting')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/modern-layout.css') }}">
    <style>
        /* ── Chat root ── */
        .chat-root {
            display: flex;
            height: calc(100vh - 120px);
            min-height: 560px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .07), 0 8px 24px rgba(0, 0, 0, .06);
        }

        /* ── Sidebar ── */
        .chat-sidebar {
            width: 300px;
            min-width: 260px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e5e7eb;
            background: #fafafa;
        }

        .chat-sidebar-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .chat-sidebar-header h5 {
            font-size: .95rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 .75rem;
        }

        .chat-search {
            position: relative;
        }

        .chat-search svg {
            position: absolute;
            left: .75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        .chat-search input {
            width: 100%;
            padding: .45rem .75rem .45rem 2.25rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: .88rem;
            background: #fff;
            transition: border-color .2s;
        }

        .chat-search input:focus {
            outline: none;
            border-color: #4f46e5;
        }

        .chat-contacts {
            flex: 1;
            overflow-y: auto;
        }

        .chat-contacts::-webkit-scrollbar {
            width: 4px;
        }

        .chat-contacts::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 4px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: .85rem;
            padding: .85rem 1.25rem;
            cursor: pointer;
            text-decoration: none;
            border-bottom: 1px solid #f3f4f6;
            transition: background .15s;
        }

        .contact-item:hover {
            background: #f3f4f6;
        }

        .contact-item.active {
            background: #eef2ff;
            border-left: 3px solid #4f46e5;
        }

        .contact-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .contact-info {
            flex: 1;
            min-width: 0;
        }

        .contact-name {
            font-size: .9rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: .1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .contact-item.active .contact-name {
            color: #4f46e5;
        }

        .contact-preview {
            font-size: .8rem;
            color: #9ca3af;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .contact-empty {
            padding: 2rem;
            text-align: center;
            font-size: .88rem;
            color: #9ca3af;
        }

        /* ── Chat panel ── */
        .chat-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .chat-panel-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: .85rem;
            background: #fff;
        }

        .chat-panel-header .contact-name {
            font-size: 1rem;
            margin: 0;
        }

        .chat-panel-header .contact-preview {
            font-size: .8rem;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: .1rem;
            background: #f8fafc;
        }

        .chat-messages::-webkit-scrollbar {
            width: 5px;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 4px;
        }

        /* Date divider */
        .chat-date-divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin: 1rem 0;
            color: #9ca3af;
            font-size: .78rem;
        }

        .chat-date-divider::before,
        .chat-date-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .chat-date-badge {
            background: #e0e7ff;
            color: #4f46e5;
            font-size: .75rem;
            font-weight: 600;
            padding: .2rem .75rem;
            border-radius: 20px;
            white-space: nowrap;
        }

        /* Message row */
        .msg-row {
            display: flex;
            align-items: flex-end;
            gap: .6rem;
            margin-bottom: .5rem;
        }

        .msg-row.me {
            flex-direction: row-reverse;
        }

        .msg-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            align-self: flex-end;
        }

        .msg-row.me .msg-avatar {
            display: none;
        }

        .msg-bubble-wrap {
            max-width: 65%;
            position: relative;
        }

        .msg-bubble {
            padding: .6rem 1rem;
            border-radius: 16px;
            font-size: .9rem;
            line-height: 1.5;
            word-break: break-word;
            position: relative;
        }

        /* Them */
        .msg-row:not(.me) .msg-bubble {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-bottom-left-radius: 4px;
            color: #111827;
        }

        /* Me */
        .msg-row.me .msg-bubble {
            background: #4f46e5;
            border-bottom-right-radius: 4px;
            color: #fff;
        }

        .msg-meta {
            display: flex;
            align-items: center;
            gap: .4rem;
            margin-top: .2rem;
            font-size: .73rem;
            color: #9ca3af;
        }

        .msg-row.me .msg-meta {
            justify-content: flex-end;
        }

        .btn-delete-msg {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #fca5a5;
            opacity: 0;
            transition: opacity .15s;
            display: flex;
            align-items: center;
        }

        .msg-row:hover .btn-delete-msg {
            opacity: 1;
        }

        .msg-row.me .msg-meta .btn-delete-msg {
            color: #fca5a5;
        }

        /* Empty chat */
        .chat-empty {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
            color: #9ca3af;
        }

        .chat-empty-icon {
            width: 72px;
            height: 72px;
            background: #eef2ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: #4f46e5;
        }

        .chat-empty h5 {
            color: #374151;
            font-weight: 600;
        }

        /* Input bar */
        .chat-input-bar {
            padding: 1rem 1.25rem;
            border-top: 1px solid #e5e7eb;
            background: #fff;
        }

        .chat-input-bar form {
            display: flex;
            gap: .75rem;
            align-items: center;
        }

        .chat-input-bar input[type=text] {
            flex: 1;
            padding: .65rem 1rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: .9rem;
            transition: border-color .2s;
        }

        .chat-input-bar input[type=text]:focus {
            outline: none;
            border-color: #4f46e5;
        }

        .btn-send {
            background: #4f46e5;
            color: #fff;
            border: none;
            padding: .65rem 1.25rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: .9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: .4rem;
            transition: background .15s, transform .1s;
        }

        .btn-send:hover {
            background: #3730a3;
            transform: translateY(-1px);
        }

        /* Responsive – mobile */
        @media (max-width: 768px) {
            .chat-root {
                height: calc(100vh - 100px);
                border-radius: 10px;
            }

            .chat-sidebar {
                width: 72px;
                min-width: 72px;
            }

            .chat-sidebar-header h5,
            .chat-sidebar-header .chat-search,
            .contact-info {
                display: none;
            }

            .contact-item {
                justify-content: center;
                padding: .75rem .5rem;
            }

            .contact-avatar {
                width: 38px;
                height: 38px;
                font-size: .85rem;
            }
        }
    </style>
@endpush

@section('content')
    @include('components.alert.success')

    @php
        $avatarColors = ['#4f46e5', '#0ea5e9', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4'];
        function avatarColor($id)
        {
            global $avatarColors;
            return $avatarColors[$id % count($avatarColors)];
        }
    @endphp

    <div class="chat-root">

        {{-- ── Sidebar kontak ── --}}
        <div class="chat-sidebar">
            <div class="chat-sidebar-header">
                <h5>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
                        style="vertical-align:-2px; margin-right:4px; color:#4f46e5">
                        <path
                            d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-4.347-1.11A5.002 5.002 0 0 1 .5 15.25l1.32-1.32A3.5 3.5 0 0 0 3 10.06c0-.293.04-.578.115-.847C2.398 8.183 2 6.14 2 4c0-3.866 3.582-7 8-7s8 3.134 8 7z" />
                    </svg>
                    Percakapan
                </h5>
                <div class="chat-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="text" id="contact-search" placeholder="Cari kontak..." autocomplete="off">
                </div>
            </div>
            <div class="chat-contacts" id="contact-list">
                @forelse ($users as $usr)
                    <a href="{{ route('chatting.index', ['user_id' => $usr->id]) }}"
                        class="contact-item {{ request('user_id') == $usr->id ? 'active' : '' }}"
                        data-name="{{ strtolower($usr->name) }}">
                        <div class="contact-avatar"
                            style="background: {{ $avatarColors[$usr->id % count($avatarColors)] }}">
                            {{ strtoupper(substr($usr->name, 0, 1)) }}
                        </div>
                        <div class="contact-info">
                            <div class="contact-name">{{ $usr->name }}</div>
                            <div class="contact-preview">{{ $usr->last_message ?? 'Belum ada pesan' }}</div>
                        </div>
                    </a>
                @empty
                    <div class="contact-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5"
                            style="color:#d1d5db; margin-bottom:.5rem">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <line x1="23" y1="11" x2="17" y2="11" />
                        </svg>
                        <p class="mb-0">Tidak ada pengguna</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ── Chat panel ── --}}
        <div class="chat-panel">
            @if (request('user_id'))
                @php
                    $chatUser = $users->firstWhere('id', request('user_id'));
                    $chatColor = $chatUser ? $avatarColors[$chatUser->id % count($avatarColors)] : '#4f46e5';
                @endphp

                {{-- Header --}}
                <div class="chat-panel-header">
                    <div class="contact-avatar"
                        style="background: {{ $chatColor }}; width:40px; height:40px; font-size:.9rem; flex-shrink:0">
                        {{ $chatUser ? strtoupper(substr($chatUser->name, 0, 1)) : '?' }}
                    </div>
                    <div>
                        <div class="contact-name">{{ $chatUser->name ?? 'Pengguna' }}</div>
                        <div class="contact-preview">Online</div>
                    </div>
                </div>

                {{-- Messages --}}
                <div class="chat-messages" id="chat-messages">
                    @forelse ($chats as $date => $messages)
                        <div class="chat-date-divider">
                            <span
                                class="chat-date-badge">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</span>
                        </div>
                        @foreach ($messages as $msg)
                            @php $isMe = $msg->sender_id == auth()->id(); @endphp
                            <div class="msg-row {{ $isMe ? 'me' : '' }}">
                                @if (!$isMe)
                                    <div class="msg-avatar"
                                        style="background: {{ $avatarColors[$msg->sender_id % count($avatarColors)] }}">
                                        {{ strtoupper(substr($msg->sender->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="msg-bubble-wrap">
                                    <div class="msg-bubble">
                                        {!! nl2br(e($msg->message)) !!}
                                    </div>
                                    <div class="msg-meta">
                                        @if (!$isMe)
                                            <span>{{ $msg->sender->name }}</span>
                                            <span>·</span>
                                        @endif
                                        <span>{{ $msg->created_at->format('H:i') }}</span>
                                        <button class="btn-delete-msg" onclick="deleteMessage({{ $msg->id }})"
                                            title="Hapus pesan">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                                <path d="M10 11v6" />
                                                <path d="M14 11v6" />
                                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @empty
                        <div style="flex:1; display:flex; align-items:center; justify-content:center;">
                            <div class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                    fill="none" stroke="#d1d5db" stroke-width="1.5" style="margin-bottom:.75rem">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                </svg>
                                <p class="text-muted mb-0" style="font-size:.9rem">Belum ada pesan. Mulai percakapan!</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Input --}}
                <div class="chat-input-bar">
                    <form action="{{ route('chatting.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ request('user_id') }}">
                        <input type="text" name="message" autocomplete="off" placeholder="Ketik pesan..." required>
                        <button class="btn-send" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13" />
                                <polygon points="22 2 15 22 11 13 2 9 22 2" />
                            </svg>
                            Kirim
                        </button>
                    </form>
                </div>
            @else
                {{-- No conversation selected --}}
                <div class="chat-empty">
                    <div class="chat-empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        </svg>
                    </div>
                    <h5>Pilih percakapan</h5>
                    <p class="mb-0" style="font-size:.88rem">Pilih salah satu kontak di sebelah kiri untuk mulai
                        chatting.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('js')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>
    <script>
        // Auto-scroll ke bawah
        const msgBox = document.getElementById('chat-messages');
        if (msgBox) msgBox.scrollTop = msgBox.scrollHeight;

        // Filter kontak
        document.getElementById('contact-search')?.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            document.querySelectorAll('.contact-item').forEach(item => {
                item.style.display = item.dataset.name.includes(keyword) ? '' : 'none';
            });
        });

        Pusher.logToConsole = false;

        document.addEventListener("DOMContentLoaded", () => {
            if (Notification.permission !== "granted") {
                Notification.requestPermission();
            }
        });

        window.Echo = new Echo({
            broadcaster: "pusher",
            key: "{{ env('PUSHER_APP_KEY') }}",
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            forceTLS: true,
        });

        Echo.private("chat.{{ auth()->id() }}")
            .listen(".chat-sent", (data) => {
                if (Notification.permission === "granted") {
                    new Notification("Pesan Baru", {
                        body: "Dari: " + data.chat.sender_name + "\n" + data.chat.message,
                    });
                }
                if (window.location.href.includes("/chatting")) {
                    location.reload();
                }
            });
    </script>
    <script>
        const BASE = "{{ route('chatting.index') }}";

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        function deleteMessage(id) {
            Swal.fire({
                title: "Hapus Pesan?",
                text: "Pesan yang dihapus tidak dapat dikembalikan.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: BASE + '/' + id + '/destroy',
                        method: "DELETE",
                        dataType: "json",
                        success: function(response) {
                            Toast.fire({
                                icon: response.status,
                                title: response.message
                            });
                            setTimeout(() => window.location.reload(), 2000);
                        },
                        error: function() {
                            Toast.fire({
                                icon: "error",
                                title: "Server Error"
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush

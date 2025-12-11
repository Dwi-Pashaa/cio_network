@extends('layouts.app')

@section('title', 'Chatting')

@section('content')
@include('components.alert.success')
<div class="card flex-fill">
    <div class="row g-0 flex-fill">
        {{-- Sidebar daftar user --}}
        <div class="col-12 col-lg-5 col-xl-3 border-end d-flex flex-column">
            <div class="card-header d-none d-md-block">
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                            <path d="M21 21l-6 -6"></path>
                        </svg>
                    </span>
                    <input type="text" class="form-control" placeholder="Search…" aria-label="Search">
                </div>
            </div>

            <div class="card-body p-0 scrollable flex-fill">
                <div class="nav flex-column" role="tablist">
                    @forelse ($users as $usr)
                        <a href="{{ route('chatting.index', ['user_id' => $usr->id]) }}"
                        class="nav-link text-start mw-100 p-3 {{ request('user_id') == $usr->id ? 'active bg-azure text-white' : '' }}">
                            <div class="row align-items-center flex-fill">
                                <div class="col-auto">
                                    <span class="avatar avatar-1 {{ request('user_id') == $usr->id ? 'bg-dark text-white' : '' }} d-flex align-items-center justify-content-center fw-bold">
                                        {{ strtoupper(substr($usr->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="col text-body">
                                    <div class="{{ request('user_id') == $usr->id ? 'text-white' : '' }}">{{ $usr->name }}</div>
                                    <div class="text-truncate w-100 {{ request('user_id') == $usr->id ? 'text-white' : 'text-secondary' }}">
                                        {{ $usr->last_message ?? 'Belum ada pesan' }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-center py-3">No users found.</p>
                    @endforelse

                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7 col-xl-9 d-flex flex-column">
            @if(request('user_id'))
                <div class="card-body scrollable flex-fill">
                    <div class="chat">
                        <div class="chat-bubbles" style="height:800px">
                            @forelse ($chats as $date => $messages)
                                <div class="text-center text-muted my-3">
                                    <small class="badge bg-primary text-white">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</small>
                                </div>

                                @foreach ($messages as $msg)
                                    <div class="chat-item">
                                        <div class="row align-items-end {{ $msg->sender_id == auth()->id() ? 'justify-content-end' : '' }}">

                                            @if ($msg->sender_id != auth()->id())
                                                <div class="col-auto">
                                                    <span class="avatar avatar-1">
                                                        {{ strtoupper(substr($msg->sender->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif

                                            <div class="col col-lg-6">
                                                <div class="chat-bubble {{ $msg->sender_id == auth()->id() ? 'chat-bubble-me' : '' }}">
                                                    <div class="chat-bubble-title">
                                                        <div class="row">
                                                            <div class="col chat-bubble-author">{{ $msg->sender->name }}</div>
                                                            {{-- <div class="col-auto chat-bubble-date">{{ $msg->created_at->format('H:i') }}</div> --}}
                                                            <div class="col-auto chat-bubble-date">
                                                                <span class="text-muted">
                                                                    <a href="javascript:void(0)" onclick="return deleteMessage({{ $msg->id }})" class="text-danger">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7h16" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /><path d="M10 12l4 4m0 -4l-4 4" /></svg>
                                                                    </a>
                                                                </span>
                                                                @if ($msg->sender_id != auth()->id())
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="chat-bubble-body">
                                                        <p>{!! nl2br($msg->message) !!}</p>
                                                        <b>
                                                            <span style="
                                                                position: absolute;
                                                                right: 10px;
                                                                bottom: 6px;
                                                                font-size: 11px;
                                                                color: #0f0f0f;
                                                            ">
                                                                {{ $msg->created_at->format('H:i') }}
                                                            </span>
                                                        </b>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            @empty
                                <div class="d-flex align-items-center justify-content-center text-center" style="height: 100%;">
                                    <p class="text-muted mb-0">Belum ada pesan. Mulai percakapan sekarang!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="card-footer mt-3">
                    <form action="{{ route('chatting.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ request('user_id') }}">
                        <div class="input-group input-group-flat">
                            <input type="text" name="message" class="form-control" autocomplete="off" placeholder="Ketik pesan..." required>
                            <button class="btn btn-primary" type="submit">Kirim</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="d-flex flex-column align-items-center justify-content-center flex-fill text-center p-5">
                    <h4>Silakan pilih pengguna untuk mulai chatting</h4>
                    <p class="text-muted">Pilih salah satu user di sebelah kiri untuk memulai percakapan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>
    <script>
        Pusher.logToConsole = true;

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
                    console.log("Notifikasi diterima:", data);

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
                title: "Peringatan !",
                text: "Anda yakin ingin menghapus pesan ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
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

                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        },
                        error: function(err) {
                            Toast.fire({
                                icon: "error",
                                title: "Server Error"
                            });
                        }
                    })
                }
            });
        }
    </script>
@endpush
@extends('errors.layout')

@section('code', '419')
@section('title', 'Sesi Halaman Telah Berakhir')

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="12" cy="12" r="10"></circle>
    <polyline points="12 6 12 12 16 14"></polyline>
</svg>
@endsection

@section('message')
    Sesi keamanan formulir Anda telah kedaluwarsa karena tidak ada aktivitas dalam waktu tertentu. Silakan muat ulang halaman untuk memperbarui sesi Anda.
@endsection

@section('extra_action')
<button type="button" class="btn-error-secondary" onclick="window.location.reload();">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
    </svg>
    <span>Perbarui Sesi (Refresh)</span>
</button>
@endsection

@extends('errors.layout')

@section('code', '500')
@section('title', 'Terjadi Kesalahan Server')

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
    <line x1="12" y1="9" x2="12" y2="13"></line>
    <line x1="12" y1="17" x2="12.01" y2="17"></line>
</svg>
@endsection

@section('message')
    Terjadi kendala internal saat memproses permintaan Anda di server. Silakan coba muat ulang halaman atau hubungi tim teknis jika kendala terus berlanjut.
@endsection

@section('extra_action')
<button type="button" class="btn-error-secondary" onclick="window.location.reload();">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
    </svg>
    <span>Muat Ulang Halaman</span>
</button>
@endsection

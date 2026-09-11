@extends('errors.layout')

@section('code', '403')
@section('title', 'Akses Menu Dibatasi')

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
</svg>
@endsection

@section('message')
    {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses (permission) yang cukup untuk membuka menu atau halaman ini. Silakan hubungi Administrator jika Anda memerlukan akses ke modul ini.' }}
@endsection

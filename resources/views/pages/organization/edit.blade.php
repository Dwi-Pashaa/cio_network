@extends('layouts.app')

@section('title')
    Tambah Organisasi / Mitra
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap');

    :root {
        --brand        : #6366f1;
        --brand-dark   : #4f46e5;
        --brand-glow   : rgba(99,102,241,.15);
        --surface      : #ffffff;
        --surface-2    : #f8f8fc;
        --border       : #e4e4f0;
        --text-primary : #1a1a2e;
        --text-muted   : #6b7280;
        --success      : #22c55e;
        --danger       : #ef4444;
        --radius       : 14px;
        --shadow       : 0 4px 24px rgba(99,102,241,.10);
    }

    * { box-sizing: border-box; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f0f8; }

    .org-wrap { margin: 0 auto; padding-bottom: 3rem; }

    .org-back { display: inline-flex; align-items: center; gap: .4rem; color: var(--text-muted); font-size: .84rem; font-weight: 600; text-decoration: none; margin-bottom: 1.5rem; transition: color .2s; }
    .org-back:hover { color: var(--brand); }

    /* ── SECTION CARD ── */
    .org-section { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; margin-bottom: 1.25rem; animation: slideUp .35s ease both; }
    .org-section:nth-child(2) { animation-delay: .05s; }
    .org-section:nth-child(3) { animation-delay: .10s; }
    .org-section:nth-child(4) { animation-delay: .15s; }

    @keyframes slideUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

    /* ── SECTION HEADER ── */
    .org-section-header { display: flex; align-items: center; gap: .85rem; padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--border); background: var(--surface-2); }
    .org-section-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .icon-indigo  { background: #eef2ff; color: #6366f1; }
    .icon-emerald { background: #ecfdf5; color: #059669; }
    .icon-amber   { background: #fffbeb; color: #d97706; }
    .org-section-header h6    { margin: 0; font-size: .92rem; font-weight: 700; color: var(--text-primary); }
    .org-section-header small { color: var(--text-muted); font-size: .78rem; }

    .org-section-body { padding: 1.5rem; }

    /* ── LABELS & INPUTS ── */
    .org-label { display: block; font-size: .8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .45rem; }
    .org-label span { color: var(--danger); margin-left: .2rem; }

    .org-input { width: 100%; border: 1.5px solid var(--border); border-radius: 10px; font-size: .9rem; color: var(--text-primary); padding: .6rem .95rem; font-family: inherit; background: var(--surface); transition: border-color .2s, box-shadow .2s; outline: none; }
    .org-input:focus      { border-color: var(--brand); box-shadow: 0 0 0 3px var(--brand-glow); }
    .org-input.is-invalid { border-color: var(--danger); box-shadow: 0 0 0 3px rgba(239,68,68,.12); }
    .org-input::placeholder { color: #c0c0d0; }

    .org-error { font-size: .78rem; color: var(--danger); margin-top: .3rem; display: flex; align-items: center; gap: .35rem; }
    .org-hint  { font-size: .76rem; color: var(--text-muted); margin-top: .3rem; }

    /* ── TIPE SELECTOR ── */
    .tipe-group { display: flex; gap: .75rem; }
    .tipe-option { flex: 1; }
    .tipe-option input[type="radio"] { display: none; }
    .tipe-option label { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: .5rem; padding: 1.1rem; border: 2px solid var(--border); border-radius: 12px; cursor: pointer; transition: border-color .2s, background .2s, box-shadow .2s; text-align: center; background: var(--surface); }
    .tipe-option label:hover { border-color: var(--brand); background: #f5f5ff; }
    .tipe-option input:checked + label { border-color: var(--brand); background: #eef2ff; box-shadow: 0 0 0 3px var(--brand-glow); }
    .tipe-option label .tipe-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .tipe-internal .tipe-icon { background: #eef2ff; color: #6366f1; }
    .tipe-mitra    .tipe-icon { background: #ecfdf5; color: #059669; }
    .tipe-option label .tipe-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); }
    .tipe-option label .tipe-desc  { font-size: .75rem; color: var(--text-muted); }

    /* ── AKSES CHECKBOXES ── */
    .akses-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: .6rem; }
    .akses-item input[type="checkbox"] { display: none; }
    .akses-item label { display: flex; align-items: center; gap: .55rem; padding: .55rem .85rem; border: 1.5px solid var(--border); border-radius: 9px; cursor: pointer; font-size: .84rem; font-weight: 500; color: var(--text-muted); transition: all .18s; background: var(--surface); user-select: none; }
    .akses-item label:hover { border-color: var(--brand); color: var(--brand); background: #f5f5ff; }
    .akses-item input:checked + label { border-color: var(--brand); background: #eef2ff; color: var(--brand); font-weight: 700; }
    .akses-check { width: 18px; height: 18px; border-radius: 5px; border: 2px solid currentColor; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background .18s; }
    .akses-item input:checked + label .akses-check { background: var(--brand); border-color: var(--brand); }
    .akses-item input:checked + label .akses-check::after { content: ''; width: 10px; height: 6px; border-left: 2px solid #fff; border-bottom: 2px solid #fff; transform: rotate(-45deg) translateY(-1px); display: block; }

    /* Error state akses grid */
    .akses-grid-error { border: 1.5px solid #fca5a5; border-radius: 12px; padding: .75rem; background: #fff5f5; }
    .akses-grid-error .label-error { border-color: #fca5a5 !important; }
    .akses-grid-error .label-error:hover { border-color: var(--danger) !important; background: #fff1f2 !important; color: var(--danger) !important; }

    /* ── PASSWORD ── */
    .pwd-wrap { position: relative; }
    .pwd-toggle { position: absolute; right: .9rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted); padding: 0; transition: color .2s; }
    .pwd-toggle:hover { color: var(--brand); }

    .strength-wrap { margin-top: .5rem; }
    .strength-bars { display: flex; gap: .25rem; margin-bottom: .3rem; }
    .strength-bar  { flex: 1; height: 4px; border-radius: 99px; background: var(--border); transition: background .3s; }
    .strength-text { font-size: .75rem; font-weight: 600; }

    /* ── DIVIDER ── */
    .org-divider { height: 1px; background: var(--border); margin: 1.25rem 0; }

    /* ── FOOTER ── */
    .org-footer { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; background: var(--surface-2); border-top: 1px solid var(--border); gap: .75rem; }

    .btn-submit { background: var(--brand); color: #fff; border: none; border-radius: 10px; padding: .65rem 2rem; font-size: .9rem; font-weight: 700; cursor: pointer; font-family: inherit; display: inline-flex; align-items: center; gap: .5rem; transition: background .2s, box-shadow .2s, transform .1s; }
    .btn-submit:hover    { background: var(--brand-dark); box-shadow: 0 6px 20px rgba(99,102,241,.35); }
    .btn-submit:active   { transform: scale(.97); }
    .btn-submit:disabled { opacity: .65; cursor: not-allowed; }

    .btn-reset-form { background: transparent; color: var(--text-muted); border: 1.5px solid var(--border); border-radius: 10px; padding: .65rem 1.25rem; font-size: .88rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: border-color .2s, color .2s; display: inline-flex; align-items: center; gap: .4rem; }
    .btn-reset-form:hover { border-color: var(--brand); color: var(--brand); }

    /* ── TOM SELECT ── */
    .ts-wrapper .ts-control        { border: 1.5px solid var(--border) !important; border-radius: 10px !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: .9rem !important; padding: .45rem .75rem !important; box-shadow: none !important; }
    .ts-wrapper.focus .ts-control  { border-color: var(--brand) !important; box-shadow: 0 0 0 3px var(--brand-glow) !important; }
    .ts-wrapper .ts-dropdown       { border-radius: 10px !important; border: 1.5px solid var(--border) !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: .88rem !important; }
    .ts-wrapper .ts-dropdown .active { background: #eef2ff !important; color: var(--brand) !important; }
    .ts-wrapper .item              { background: #eef2ff !important; color: var(--brand) !important; border-radius: 6px !important; font-size: .8rem !important; font-weight: 600 !important; }

    @media(max-width: 600px) {
        .tipe-group { flex-direction: column; }
        .akses-grid { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')
<div class="org-wrap">

    {{-- BACK --}}
    <a href="{{ route('organization.index') }}" class="org-back">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Kembali ke Daftar Organisasi
    </a>

    <form action="{{ route('organization.update', $organization->id) }}" method="POST" id="org-form">
        @csrf
        @method('PUT')
        <div class="org-section">
            <div class="org-section-header">
                <div class="org-section-icon icon-indigo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <div>
                    <h6>Informasi Organisasi</h6>
                    <small>Nama dan tipe organisasi / mitra</small>
                </div>
            </div>

            <div class="org-section-body">

                {{-- NAMA --}}
                <div class="mb-4">
                    <label class="org-label">Nama Organisasi <span>*</span></label>
                    <input type="text" name="name"
                           class="org-input @error('name') is-invalid @enderror"
                           placeholder="Contoh: PT. Mitra Teknologi Indonesia"
                           value="{{ old('name', $organization->name) }}">
                    @error('name')
                        <div class="org-error">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- TIPE --}}
                <div class="mb-2">
                    <label class="org-label">Tipe Organisasi <span>*</span></label>
                    <div class="tipe-group">

                        <div class="tipe-option tipe-internal">
                            <input type="radio" name="type" id="tipe-internal" value="internal"
                                   {{ old('type', $organization->type) === 'internal' ? 'checked' : '' }}>
                            <label for="tipe-internal">
                                <div class="tipe-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                    </svg>
                                </div>
                                <span class="tipe-title">Internal</span>
                                <span class="tipe-desc">Divisi atau departemen<br>dalam organisasi sendiri</span>
                            </label>
                        </div>

                        <div class="tipe-option tipe-mitra">
                            <input type="radio" name="type" id="tipe-mitra" value="mitra"
                                   {{ old('type', $organization->type) === 'mitra' ? 'checked' : '' }}>
                            <label for="tipe-mitra">
                                <div class="tipe-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                </div>
                                <span class="tipe-title">Mitra</span>
                                <span class="tipe-desc">Organisasi eksternal atau<br>perusahaan rekanan</span>
                            </label>
                        </div>

                    </div>
                    @error('type')
                        <div class="org-error mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>
        </div>

        <div class="org-section">
            <div class="org-section-header">
                <div class="org-section-icon icon-emerald">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div>
                    <h6>Akun Admin Organisasi</h6>
                    <small>Akun yang akan digunakan untuk mengelola organisasi ini</small>
                </div>
            </div>

            <div class="org-section-body">

                {{-- REGENCIE --}}
                <div class="mb-3">
                    <label class="org-label">Penempatan Kabupaten/Kota <span>*</span></label>
                    <input type="text" name="regencie"
                           class="org-input @error('regencie') is-invalid @enderror"
                           placeholder="Contoh: Kota Bandung"
                           value="{{ old('regencie', $regencie->name ?? '') }}"
                           autocomplete="off">
                    <div class="org-hint">
                        Masukkan nama kabupaten/kota tempat organisasi ini beroperasi. Contoh: "Kota Bandung", "Kota Garut", untuk mendefinisikan si mitra berdiri di kabupaten/kota mana.
                    </div>
                    @error('regencie')
                        <div class="org-error">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- USERNAME --}}
                <div class="mb-3">
                    <label class="org-label">Username <span>*</span></label>
                    <input type="text" name="username"
                           class="org-input @error('username') is-invalid @enderror"
                           placeholder="Contoh: AdminMitra01"
                           value="{{ old('username', $admin->username ?? '') }}"
                           autocomplete="off">
                    <div class="org-hint">Gunakan huruf kecil, angka, dan underscore. Tanpa spasi.</div>
                    @error('username')
                        <div class="org-error">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- NAMA & EMAIL --}}
                <div class="row g-3 mb-3">
                    <div class="col-lg-6 col-12">
                        <label class="org-label">Nama Lengkap <span>*</span></label>
                        <input type="text" name="admin_name"
                               class="org-input @error('admin_name') is-invalid @enderror"
                               placeholder="Nama lengkap admin"
                               value="{{ old('admin_name', $admin->name ?? '') }}">
                        @error('admin_name')
                            <div class="org-error">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-12">
                        <label class="org-label">Email <span>*</span></label>
                        <input type="email" name="email"
                               class="org-input @error('email') is-invalid @enderror"
                               placeholder="admin@example.com"
                               value="{{ old('email', $admin->email ?? '') }}">
                        @error('email')
                            <div class="org-error">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="org-divider"></div>

                {{-- PASSWORD --}}
                <div class="row g-3">
                    <div class="col-lg-6 col-12">
                        <label class="org-label">Password <span>*</span></label>
                        <div class="pwd-wrap">
                            <input type="password" name="password" id="password"
                                   class="org-input @error('password') is-invalid @enderror"
                                   placeholder="Min. 8 karakter"
                                   autocomplete="new-password">
                            <button type="button" class="pwd-toggle" data-target="password">
                                <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        <div class="strength-wrap" id="strength-wrap" style="display:none;">
                            <div class="strength-bars">
                                <div class="strength-bar" id="sb1"></div>
                                <div class="strength-bar" id="sb2"></div>
                                <div class="strength-bar" id="sb3"></div>
                                <div class="strength-bar" id="sb4"></div>
                            </div>
                            <span class="strength-text" id="strength-text"></span>
                        </div>
                        @error('password')
                            <div class="org-error">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-12">
                        <label class="org-label">Konfirmasi Password <span>*</span></label>
                        <div class="pwd-wrap">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="org-input"
                                   placeholder="Ulangi password"
                                   autocomplete="new-password">
                            <button type="button" class="pwd-toggle" data-target="password_confirmation">
                                <svg id="eye-password_confirmation" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        <div class="org-hint" id="pwd-match-hint"></div>
                    </div>
                </div>

            </div>
        </div>

        <div class="org-section">
            <div class="org-section-header">
                <div class="org-section-icon icon-amber">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <div>
                    <h6>Hak Akses Akun Mitra</h6>
                    <small>Pilih fitur yang dapat diakses oleh akun mitra ini</small>
                </div>
            </div>

            <div class="org-section-body">

                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                    <span style="font-size:.82rem; font-weight:600; color:var(--text-muted);">
                        Centang akses yang diizinkan:
                    </span>
                    <button type="button" id="btn-check-all"
                            style="background:none; border:none; color:var(--brand); font-size:.8rem; font-weight:700; cursor:pointer; font-family:inherit;">
                        Pilih Semua
                    </button>
                </div>

                {{-- GRID AKSES — name="permissions[]", value=$page->id --}}
                <div class="akses-grid @error('permissions') akses-grid-error @enderror">
                    @foreach ($pages as $page)
                        <div class="akses-item">
                            <input type="checkbox"
                                   name="permissions[]"
                                   id="page-{{ $page->id }}"
                                   value="{{ $page->id }}"
                                   {{ in_array($page->id, old('permissions', $orgPermissions)) ? 'checked' : '' }}>
                            <label for="page-{{ $page->id }}" class="@error('permissions') label-error @enderror">
                                <span class="akses-check"></span>
                                {{ $page->name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                @error('permissions')
                    <div class="org-error mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- FOOTER --}}
            <div class="org-footer">
                <button type="button" class="btn-reset-form" id="btn-reset-form">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="1 4 1 10 7 10"/>
                        <path d="M3.51 15a9 9 0 1 0 .49-4.5"/>
                    </svg>
                    Reset Form
                </button>

                <button type="submit" class="btn-submit" id="btn-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                         id="btn-check-icon">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span id="btn-text">Simpan Organisasi</span>
                    <svg id="btn-spinner" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                         style="display:none; animation:spin 1s linear infinite;">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                </button>
            </div>

        </div>

    </form>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── PASSWORD TOGGLE ──
    document.querySelectorAll('.pwd-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const target = document.getElementById(this.dataset.target);
            const isText = target.type === 'text';
            target.type  = isText ? 'password' : 'text';

            const eye = document.getElementById('eye-' + this.dataset.target);
            eye.innerHTML = isText
                ? '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>'
                : '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
        });
    });

    // ── PASSWORD STRENGTH ──
    const pwdInput     = document.getElementById('password');
    const strengthWrap = document.getElementById('strength-wrap');
    const strengthText = document.getElementById('strength-text');
    const bars         = ['sb1','sb2','sb3','sb4'].map(id => document.getElementById(id));

    const levels = [
        { color: '#ef4444', label: 'Sangat Lemah', text: '#ef4444' },
        { color: '#f97316', label: 'Lemah',        text: '#f97316' },
        { color: '#eab308', label: 'Cukup',        text: '#eab308' },
        { color: '#22c55e', label: 'Kuat',         text: '#22c55e' },
    ];

    function getStrength(pwd) {
        let score = 0;
        if (pwd.length >= 8)           score++;
        if (/[A-Z]/.test(pwd))         score++;
        if (/[0-9]/.test(pwd))         score++;
        if (/[^A-Za-z0-9]/.test(pwd))  score++;
        return score;
    }

    pwdInput.addEventListener('input', function () {
        const val = this.value;
        if (!val) { strengthWrap.style.display = 'none'; return; }
        strengthWrap.style.display = 'block';
        const score = getStrength(val);
        bars.forEach(function (bar, i) {
            bar.style.background = i < score ? levels[score - 1].color : '#e4e4f0';
        });
        strengthText.textContent = levels[score - 1]?.label ?? '';
        strengthText.style.color = levels[score - 1]?.text  ?? '#6b7280';
    });

    // ── PASSWORD MATCH ──
    const pwdConfirm = document.getElementById('password_confirmation');
    const matchHint  = document.getElementById('pwd-match-hint');

    function checkMatch() {
        if (!pwdConfirm.value) { matchHint.textContent = ''; return; }
        if (pwdInput.value === pwdConfirm.value) {
            matchHint.textContent = '✓ Password cocok';
            matchHint.style.color = '#22c55e';
        } else {
            matchHint.textContent = '✗ Password tidak cocok';
            matchHint.style.color = '#ef4444';
        }
    }

    pwdInput.addEventListener('input', checkMatch);
    pwdConfirm.addEventListener('input', checkMatch);

    // ── PILIH SEMUA AKSES ──
    const btnCheckAll = document.getElementById('btn-check-all');
    let   allChecked  = false;

    btnCheckAll.addEventListener('click', function () {
        allChecked = !allChecked;
        document.querySelectorAll('.akses-item input[type="checkbox"]').forEach(function (cb) {
            cb.checked = allChecked;
        });
        this.textContent = allChecked ? 'Batal Semua' : 'Pilih Semua';
    });

    // ── RESET FORM ──
    document.getElementById('btn-reset-form').addEventListener('click', function () {
        if (confirm('Reset semua isian form?')) {
            document.getElementById('org-form').reset();
            strengthWrap.style.display = 'none';
            matchHint.textContent      = '';
            btnCheckAll.textContent    = 'Pilih Semua';
            allChecked                 = false;
        }
    });

    // ── SUBMIT LOADING ──
    document.getElementById('org-form').addEventListener('submit', function () {
        const btn       = document.getElementById('btn-submit');
        const text      = document.getElementById('btn-text');
        const spinner   = document.getElementById('btn-spinner');
        const checkIcon = document.getElementById('btn-check-icon');

        btn.disabled            = true;
        text.textContent        = 'Menyimpan…';
        spinner.style.display   = 'inline-block';
        checkIcon.style.display = 'none';
    });

});

const s = document.createElement('style');
s.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
document.head.appendChild(s);
</script>
@endpush
@extends('layouts.app')

@section('title')
    Data Spam & Validasi Prosedur
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/modern-layout.css') }}">
    <style>
        /* ─── MASTER TABS ─────────────────────────────────────── */
        .spam-tabs-nav {
            display: flex;
            gap: 0;
            border-bottom: 2px solid #f1f5f9;
            padding: 0 1.5rem;
            overflow-x: auto;
        }
        .spam-tab-btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 0.9rem 1.3rem;
            font-size: 0.84rem; font-weight: 700; color: #94a3b8;
            background: transparent; border: none;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px; cursor: pointer; white-space: nowrap;
            transition: all 0.2s;
        }
        .spam-tab-btn:hover { color: #475569; }
        .spam-tab-btn.active { color: #2563eb; border-bottom-color: #2563eb; }
        .spam-tab-btn .tab-pill {
            font-size: 0.68rem; font-weight: 800;
            padding: 2px 8px; border-radius: 20px;
            background: #f1f5f9; color: #64748b;
            min-width: 22px; text-align: center;
            transition: all 0.2s;
        }
        .spam-tab-btn.active .tab-pill { background: rgba(37,99,235,0.12); color: #2563eb; }

        /* ─── TAB PANELS ──────────────────────────────────────── */
        .spam-panel { display: none; }
        .spam-panel.active { display: block; }

        /* ─── REQUEST CARD ────────────────────────────────────── */
        .req-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #cbd5e1;
            border-radius: 12px;
            margin-bottom: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            position: relative;
        }
        .req-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
            border-color: #cbd5e1;
        }
        .req-card.type-pemutusan         { border-left-color: #ef4444; }
        .req-card.type-pergantian-layanan { border-left-color: #2563eb; }
        .req-card.type-onu-router        { border-left-color: #7c3aed; }
        .req-card.type-pergantian-password { border-left-color: #0ea5e9; }

        /* Card Header */
        .req-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            background: #fafbfc;
            border-bottom: 1px solid #f1f5f9;
        }
        .req-card-avatar {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            font-weight: 800;
            flex-shrink: 0;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }
        .req-card-info {
            flex: 1;
            min-width: 0;
        }
        .req-card-name {
            font-size: 1rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
            line-height: 1.2;
        }
        .req-card-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 6px;
            font-size: 0.76rem;
            color: #64748b;
        }
        .req-card-meta .sep {
            color: #cbd5e1;
            font-weight: 300;
        }
        .req-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.01em;
        }
        .badge-pemutusan          { background: rgba(239,68,68,0.08);    color: #dc2626; }
        .badge-pergantian-layanan { background: rgba(37,99,235,0.08);    color: #2563eb; }
        .badge-onu-router         { background: rgba(124,58,237,0.08);   color: #7c3aed; }
        .badge-pergantian-password { background: rgba(14,165,233,0.08);   color: #0ea5e9; }

        /* ─── APPROVAL PROGRESS STEPPER ──────────────────────── */
        .approval-progress {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem 2rem 1.75rem;
            border-bottom: 1px solid #f1f5f9;
            position: relative;
            background: #fff;
        }
        .ap-step-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
            z-index: 2;
        }

        .ap-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 800;
            border: 2px solid #cbd5e1;
            background: #fff;
            color: #64748b;
            position: relative;
            z-index: 3;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .ap-dot.approved {
            background: #16a34a;
            border-color: #16a34a;
            color: #fff;
            box-shadow: 0 0 10px rgba(22,163,74,0.25);
        }
        .ap-dot.rejected {
            background: #ef4444;
            border-color: #ef4444;
            color: #fff;
            box-shadow: 0 0 10px rgba(239,68,68,0.25);
        }
        .ap-dot.active {
            border-color: #2563eb;
            color: #2563eb;
            background: #eff6ff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
            animation: activeDotPulse 2s infinite;
        }
        @keyframes activeDotPulse {
            0% { box-shadow: 0 0 0 0px rgba(37, 99, 235, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(37, 99, 235, 0); }
            100% { box-shadow: 0 0 0 0px rgba(37, 99, 235, 0); }
        }
        
        .ap-step-wrap::after {
            content: '';
            position: absolute;
            top: 15px; /* Half of dot height */
            left: 50%;
            width: 100%;
            height: 3px;
            background: #e2e8f0;
            z-index: 1;
            transition: background 0.3s ease;
        }
        .ap-step-wrap:last-child::after {
            display: none;
        }
        .ap-step-wrap.line-approved::after {
            background: #16a34a;
        }
        .ap-step-wrap.line-rejected::after {
            background: #ef4444;
        }
        
        .ap-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #64748b;
            text-align: center;
            margin-top: 8px;
            white-space: nowrap;
        }

        /* ─── CARD FOOTER ─────────────────────────────────────── */
        .req-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #fafbfc;
            border-top: 1px solid #f1f5f9;
        }
        .btn-toggle-detail {
            border: 1.5px solid #e2e8f0;
            background: #fff;
            color: #475569;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .btn-toggle-detail:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }
        .btn-toggle-detail.active {
            background: rgba(37,99,235,0.06);
            border-color: #93c5fd;
            color: #2563eb;
        }

        /* ─── ACTION BUTTONS ──────────────────────────────────── */
        .req-actions {
            display: flex;
            gap: 0.6rem;
            align-items: center;
            flex-shrink: 0;
        }
        .btn-approve {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.5rem 1.15rem;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            background: #16a34a;
            color: #fff;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(22,163,74,0.15);
            transition: all 0.2s ease;
        }
        .btn-approve:hover {
            background: #15803d;
            box-shadow: 0 4px 12px rgba(22,163,74,0.25);
            transform: translateY(-1px);
        }
        .btn-val-reject {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.5rem 1.1rem;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            background: #fff;
            color: #64748b;
            border: 1.5px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-val-reject:hover {
            background: #fef2f2;
            color: #ef4444;
            border-color: rgba(239,68,68,0.25);
        }

        /* ─── EMPTY STATES ────────────────────────────────────── */
        .empty-val { text-align: center; padding: 4rem 1rem; color: #94a3b8; }
        .empty-val svg { opacity: 0.25; margin-bottom: 1rem; }
        .empty-val h5 { font-size: 0.95rem; font-weight: 700; color: #64748b; }
        .empty-val p  { font-size: 0.82rem; }

        .loading-spin { text-align: center; padding: 2.5rem; color: #94a3b8; }

        /* ─── LEVEL LEGEND ────────────────────────────────────── */
        .level-legend { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; padding: 0.75rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
        .lvl-chip { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 8px; font-size: 0.72rem; font-weight: 700; }
        .lvl-blue   { background: rgba(37,99,235,0.08);  color: #2563eb; }
        .lvl-green  { background: rgba(22,163,74,0.08);  color: #16a34a; }
        .lvl-orange { background: rgba(234,88,12,0.08);  color: #ea580c; }
        .lvl-purple { background: rgba(124,58,237,0.08); color: #7c3aed; }

        /* ─── PAYLOAD DIFF BLOCK ──────────────────────────────── */
        .diff-block {
            margin: 0.75rem 1.25rem;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            font-size: 0.78rem;
        }
        .diff-title {
            display: flex; align-items: center; gap: 6px;
            padding: 0.5rem 0.9rem;
            background: linear-gradient(135deg, #f1f5f9, #f8fafc);
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.68rem; font-weight: 800;
            color: #64748b; text-transform: uppercase; letter-spacing: 0.07em;
        }
        .diff-rows { display: flex; flex-direction: column; }
        .diff-row {
            display: grid;
            grid-template-columns: 130px 1fr 18px 1fr;
            gap: 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .diff-row:last-child { border-bottom: none; }
        .diff-label {
            padding: 0.45rem 0.9rem;
            font-weight: 700; color: #94a3b8;
            border-right: 1px solid #f1f5f9;
            font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.04em;
            display: flex; align-items: center;
        }
        .diff-old {
            padding: 0.4rem 0.75rem;
            color: #dc2626; background: rgba(239,68,68,0.03);
            font-family: monospace; font-size: 0.8rem; word-break: break-all;
            display: flex; align-items: center;
        }
        .diff-arrow {
            display: flex; align-items: center; justify-content: center;
            color: #cbd5e1; font-size: 0.7rem;
        }
        .diff-new {
            padding: 0.4rem 0.75rem;
            color: #16a34a; background: rgba(22,163,74,0.03);
            font-family: monospace; font-size: 0.8rem; word-break: break-all;
            display: flex; align-items: center;
            border-left: 1px solid #f1f5f9;
        }
        .diff-info-row { grid-template-columns: 130px 1fr; }
        .diff-info-val {
            padding: 0.4rem 0.75rem;
            color: #475569; font-size: 0.8rem;
            display: flex; align-items: center; gap: 6px;
        }
        .btn-val-steps {
            border: 1.5px solid #ea580c;
            background: #fff;
            color: #ea580c;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .btn-val-steps:hover {
            background: rgba(234, 88, 12, 0.06);
            border-color: #c2410c;
            color: #c2410c;
            transform: translateY(-1px);
        }
        .btn-copy-msg {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 8px;
            border: 1.5px solid #ea580c;
            background: #fff;
            color: #ea580c;
            font-size: 0.74rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-copy-msg:hover {
            background: rgba(234, 88, 12, 0.06);
            border-color: #c2410c;
            color: #c2410c;
            transform: translateY(-1px);
        }
        .mix-message-container:hover pre {
            border-color: #cbd5e1;
            background: #f1f5f9 !important;
        }
        .mix-message-container:hover .mix-message-overlay {
            opacity: 1 !important;
            color: #ea580c;
            border-color: #ffd8a8;
        }
        pre a {
            color: #2563eb !important;
            text-decoration: underline !important;
            cursor: pointer !important;
        }
    </style>
@endpush

@section('content')
    @include('components.alert.success')

    <div class="org-card">
        {{-- ── CARD HEADER ── --}}
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M18 8a3 3 0 0 1 0 6"/>
                        <path d="M10 8v11a1 1 0 0 1 -1 1h-1a1 1 0 0 1 -1 -1v-5"/>
                        <path d="M12 8h0l4.524 -3.77a0.9 .9 0 0 1 1.476 .692v12.156a0.9 .9 0 0 1 -1.476 .692l-4.524 -3.77h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1h8"/>
                    </svg>
                </div>
                <div>
                    <h3 class="org-title">Data Spam & Validasi</h3>
                    <p class="org-subtitle mb-0">Kelola antrean data masuk dan validasi prosedur multi-level</p>
                </div>
            </div>
        </div>

        {{-- ── MASTER TAB NAV ── --}}
        <div class="spam-tabs-nav">
            @can('lihat halaman')
            <button class="spam-tab-btn active" id="nav-pemasangan" onclick="switchSpamTab('pemasangan')">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                Pemasangan Baru
                <span class="tab-pill" id="pill-pemasangan">0</span>
            </button>
            @endcan

            <button class="spam-tab-btn {{ !auth()->user()->can('lihat halaman') ? 'active' : '' }}" id="nav-pemutusan" onclick="switchSpamTab('pemutusan')">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/>
                    <line x1="18" y1="8" x2="23" y2="13"/><line x1="23" y1="8" x2="18" y2="13"/>
                </svg>
                Pemutusan Pelanggan
                <span class="tab-pill" id="pill-pemutusan">0</span>
            </button>

            <button class="spam-tab-btn" id="nav-pergantian-layanan" onclick="switchSpamTab('pergantian-layanan')">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/>
                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
                </svg>
                Pergantian Layanan
                <span class="tab-pill" id="pill-pergantian-layanan">0</span>
            </button>

            <button class="spam-tab-btn" id="nav-onu-router" onclick="switchSpamTab('onu-router')">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="2" width="20" height="8" rx="2" ry="2"/>
                    <rect x="2" y="14" width="20" height="8" rx="2" ry="2"/>
                    <line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/>
                </svg>
                Pergantian Perangkat
                <span class="tab-pill" id="pill-onu-router">0</span>
            </button>

            <button class="spam-tab-btn" id="nav-pergantian-password" onclick="switchSpamTab('pergantian-password')">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                </svg>
                Pergantian Password
                <span class="tab-pill" id="pill-pergantian-password">0</span>
            </button>

            @can('lihat rekap prosedur')
            <button class="spam-tab-btn" id="nav-rekap" onclick="switchSpamTab('rekap')">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                </svg>
                Rekap Historis
                <span class="tab-pill" id="pill-rekap">0</span>
            </button>
            @endcan
        </div>

        {{-- ══════════════════════════════════════════════════════
             TAB 1: PEMASANGAN BARU (existing spam table)
        ══════════════════════════════════════════════════════ --}}
        @can('lihat halaman')
            <div class="spam-panel active" id="panel-pemasangan">
                <div class="org-toolbar">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted" style="font-size: 0.88rem;">Tampilkan</span>
                        <select name="sort" id="sort" class="org-input" style="width: 80px; padding: 0.35rem 0.8rem;">
                            @php $opts = [10, 25, 50, 100]; @endphp
                            @foreach ($opts as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                        <span class="text-muted" style="font-size: 0.88rem;">entri</span>
                    </div>
                    @if (auth()->user()->hasPermissionTo('filter organization'))
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <span class="text-muted small fw-bold">Organisasi</span>
                            <select id="filter-organization" class="org-input" style="width:auto;padding:0.35rem 0.8rem;">
                                <option value="">Semua</option>
                                @foreach ($organizations as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="search-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" class="org-input" id="search-input" placeholder="Cari pemasangan baru..." autocomplete="off">
                    </div>
                </div>

                <div id="spam-table-wrapper" class="table-responsive">
                    <table class="table org-table table-vcenter text-nowrap" id="spam-table">
                        <thead>
                            <tr>
                                <th class="w-1">No</th>
                                <th>ID Pelanggan</th>
                                <th>Tipe Pelanggan</th>
                                <th>Tipe Layanan</th>
                                <th>NIK</th>
                                <th>Nama Pelanggan</th>
                                <th>Email</th>
                                <th>No Telephone</th>
                                <th>Mac Address</th>
                                <th>Jenis Router</th>
                                <th>Kampung</th>
                                <th>Desa</th>
                                <th>RT</th>
                                <th>RW</th>
                                <th>Kecamatan</th>
                                <th>Kabupaten/Kota</th>
                                <th>Vlan</th>
                                <th>Alamat ODC</th>
                                <th>Alamat ODP</th>
                                <th>Alamat OLT</th>
                                <th>Nama Wifi</th>
                                <th>Password Wifi</th>
                                <th>PPOE Username</th>
                                <th>PPOE Password</th>
                                <th>Tipe Paket</th>
                                <th>Mix Radius</th>
                                <th>Tipe Pembayaran</th>
                                <th>Lokasi</th>
                                <th>Foto KTP</th>
                                @if (auth()->user()->hasPermissionTo('filter organization'))
                                    <th>Organisasi/Mitra</th>
                                @endif
                                <th>Di Input Oleh</th>
                                <th>Created</th>
                                @if (auth()->user()->can('ubah pelanggan') || auth()->user()->can('hapus pelanggan'))
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="org-footer border-top py-3 px-4 d-flex align-items-center justify-content-between">
                    <p class="m-0 text-muted" style="font-size: 0.88rem;">
                        Showing <span id="start-entry" class="fw-medium">0</span>
                        to <span id="end-entry" class="fw-medium">0</span> of
                        <span id="total-entries" class="fw-medium">0</span> entries
                    </p>
                    <ul class="pagination m-0" id="custom-pagination"></ul>
                </div>
            </div>
        @endcan

        {{-- ══════════════════════════════════════════════════════
             TAB 2: PEMUTUSAN PELANGGAN
        ══════════════════════════════════════════════════════ --}}
        <div class="spam-panel {{ !auth()->user()->can('lihat halaman') ? 'active' : '' }}" id="panel-pemutusan">
            @include('pages.validasi.partials.validasi-panel', [
                'prosedur_type'  => 'pemutusan',
                'panel_title'    => 'Pemutusan Pelanggan',
                'panel_color'    => '#ef4444',
            ])
        </div>

        {{-- ══════════════════════════════════════════════════════
             TAB 3: PERGANTIAN LAYANAN
        ══════════════════════════════════════════════════════ --}}
        <div class="spam-panel" id="panel-pergantian-layanan">
            @include('pages.validasi.partials.validasi-panel', [
                'prosedur_type'  => 'pergantian-layanan',
                'panel_title'    => 'Pergantian Layanan',
                'panel_color'    => '#2563eb',
            ])
        </div>

        {{-- ══════════════════════════════════════════════════════
             TAB 4: PERGANTIAN PERANGKAT
        ══════════════════════════════════════════════════════ --}}
        <div class="spam-panel" id="panel-onu-router">
            @include('pages.validasi.partials.validasi-panel', [
                'prosedur_type'  => 'onu-router',
                'panel_title'    => 'Pergantian Perangkat',
                'panel_color'    => '#7c3aed',
            ])
        </div>

        {{-- ══════════════════════════════════════════════════════
             TAB: PERGANTIAN PASSWORD
        ══════════════════════════════════════════════════════ --}}
        <div class="spam-panel" id="panel-pergantian-password">
            @include('pages.validasi.partials.validasi-panel', [
                'prosedur_type'  => 'pergantian-password',
                'panel_title'    => 'Pergantian Password',
                'panel_color'    => '#0ea5e9',
            ])
        </div>

        {{-- ══════════════════════════════════════════════════════
             TAB 5: REKAP HISTORIS
        ══════════════════════════════════════════════════════ --}}
        @can('lihat rekap prosedur')
            <div class="spam-panel" id="panel-rekap">
                <div class="level-legend" style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size: 0.72rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.07em;">Rekap Hasil Validasi Prosedur</span>
                    @if (auth()->user()->hasPermissionTo('filter organization'))
                        <div class="d-flex align-items-center gap-2">
                            <span style="font-size:0.82rem;font-weight:700;color:#94a3b8;">Organisasi</span>
                            <select id="filter-org-rekap" class="org-input" style="width:auto;padding:0.35rem 0.8rem;font-size:0.82rem;">
                                <option value="">Semua</option>
                                @foreach ($organizations as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div style="padding: 1.25rem 1.5rem;">
                    <div class="loading-spin" id="val-loading-rekap">
                        <div class="spinner-border" style="width: 2rem; height: 2rem; color: #64748b;" role="status"></div>
                        <p style="margin-top: 0.6rem; font-size: 0.82rem;">Memuat rekap historis...</p>
                    </div>
                    <div id="val-content-rekap"></div>
                </div>
                <div class="org-footer border-top py-3 px-4 d-flex align-items-center justify-content-between" id="rekap-pagination-footer" style="display: none !important;">
                    <p class="m-0 text-muted" style="font-size: 0.88rem;">
                        Showing <span id="rekap-start-entry" class="fw-medium">0</span>
                        to <span id="rekap-end-entry" class="fw-medium">0</span> of
                        <span id="rekap-total-entries" class="fw-medium">0</span> entries
                    </p>
                    <ul class="pagination m-0" id="rekap-pagination"></ul>
                </div>
            </div>
        @endcan
    </div>

    {{-- ══ APPROVE MODAL ══════════════════════════════════════ --}}
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 25px 50px rgba(15,23,42,0.15);">
                <div class="modal-header" style="border: none; padding: 1.75rem 1.75rem 0.5rem;">
                    <div style="width: 48px; height: 48px; border-radius: 13px; background: rgba(22,163,74,0.1); color: #16a34a; display: flex; align-items: center; justify-content: center; margin-right: 1rem; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div>
                        <h5 class="modal-title" style="font-weight: 800; color: #0f172a; font-size: 1.05rem; margin: 0;">Konfirmasi Persetujuan</h5>
                        <p style="margin: 0; font-size: 0.82rem; color: #64748b;" id="approve-subtitle">Tindakan ini menandai checkpoint Anda sebagai disetujui.</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 1rem 1.75rem 0.5rem;">
                    <label style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; display: block; margin-bottom: 0.4rem;">Catatan (opsional)</label>
                    <textarea id="approve-notes" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu..." style="border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 0.88rem; resize: none;"></textarea>
                </div>
                <div class="modal-footer" style="border: none; padding: 1rem 1.75rem 1.75rem; gap: 0.75rem;">
                    <button type="button" class="btn-val-reject" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-approve" id="btn-do-approve">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Ya, Setujui
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ REJECT MODAL ════════════════════════════════════════ --}}
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 25px 50px rgba(15,23,42,0.15);">
                <div class="modal-header" style="border: none; padding: 1.75rem 1.75rem 0.5rem;">
                    <div style="width: 48px; height: 48px; border-radius: 13px; background: rgba(239,68,68,0.1); color: #ef4444; display: flex; align-items: center; justify-content: center; margin-right: 1rem; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </div>
                    <div>
                        <h5 class="modal-title" style="font-weight: 800; color: #0f172a; font-size: 1.05rem; margin: 0;">Tolak Request</h5>
                        <p style="margin: 0; font-size: 0.82rem; color: #64748b;">Request dibatalkan dan tidak ada perubahan data pelanggan.</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 1rem 1.75rem 0.5rem;">
                    <label style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; display: block; margin-bottom: 0.4rem;">Alasan Penolakan <span style="color: #ef4444;">*</span></label>
                    <textarea id="reject-reason" class="form-control" rows="3" placeholder="Tuliskan alasan penolakan dengan jelas..." style="border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 0.88rem; resize: none;"></textarea>
                    <div id="reject-reason-error" style="color: #ef4444; font-size: 0.78rem; margin-top: 0.4rem; display: none;">Alasan penolakan wajib diisi.</div>
                </div>
                <div class="modal-footer" style="border: none; padding: 1rem 1.75rem 1.75rem; gap: 0.75rem;">
                    <button type="button" style="padding: 0.45rem 1rem; border-radius: 9px; border: 1.5px solid #e2e8f0; background: #fff; color: #475569; font-weight: 700; font-size: 0.82rem; cursor: pointer;" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-val-reject" id="btn-do-reject" style="background: #ef4444; color: #fff; border-color: #ef4444;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Tolak Request
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- ══ ADMIN STEPS MODAL ════════════════════════════════════ --}}
    <div class="modal fade" id="adminStepsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 25px 50px rgba(15,23,42,0.15);">
                <div class="modal-header" style="border: none; padding: 1.75rem 1.75rem 0.5rem;">
                    <div style="width: 48px; height: 48px; border-radius: 13px; background: rgba(59,130,246,0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center; margin-right: 1rem; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M12 8a4 4 0 0 1 4 4v1a4 4 0 0 1 -8 0v-1a4 4 0 0 1 4 -4z"/></svg>
                    </div>
                    <div>
                        <h5 class="modal-title" style="font-weight: 800; color: #0f172a; font-size: 1.05rem; margin: 0;">Langkah-langkah Validasi Admin</h5>
                        <p style="margin: 0; font-size: 0.82rem; color: #64748b;">Ikuti panduan berikut sebelum melakukan validasi Admin</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem 1.75rem 1.75rem; font-size: 0.9rem; line-height: 1.8; color: #334155;">
                    <ol style="margin: 0; padding-left: 20px;">
                        <li class="mb-2">
                            Periksa kesesuaian data pengajuan perangkat baru yang diinput oleh teknisi.
                        </li>
                        <li class="mb-2">
                            Pastikan data pelanggan (ID Pelanggan, Nama, Alamat) sudah benar dan terdaftar di sistem.
                        </li>
                        <li class="mb-2">
                            Pastikan bukti foto perangkat/pembayaran (jika dilampirkan) sudah valid dan sesuai.
                        </li>
                        <li class="mb-2">
                            Setelah semua data dipastikan benar, lakukan konfirmasi dengan mengklik tombol <strong>Setujui</strong> di bawah.
                        </li>
                        <li class="mb-2">
                            Sistem akan otomatis mengirimkan notifikasi WA ke validator tingkat berikutnya (OLT, Mix Radius, dan ONC) untuk melanjutkan proses validasi.
                        </li>
                    </ol>
                </div>
                <div class="modal-footer" style="border: none; padding: 1rem 1.75rem 1.75rem;">
                    <button type="button" class="btn-val-reject" data-bs-dismiss="modal" style="margin: 0;">Tutup Panduan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ OLT STEPS MODAL ══════════════════════════════════════ --}}
    <div class="modal fade" id="oltStepsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 25px 50px rgba(15,23,42,0.15);">
                <div class="modal-header" style="border: none; padding: 1.75rem 1.75rem 0.5rem;">
                    <div style="width: 48px; height: 48px; border-radius: 13px; background: rgba(234,88,12,0.1); color: #ea580c; display: flex; align-items: center; justify-content: center; margin-right: 1rem; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="13" width="6" height="8" rx="2"/><rect x="15" y="13" width="6" height="8" rx="2"/><rect x="9" y="3" width="6" height="8" rx="2"/><path d="M12 11v2"/><path d="M6 13v-2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"/></svg>
                    </div>
                    <div>
                        <h5 class="modal-title" style="font-weight: 800; color: #0f172a; font-size: 1.05rem; margin: 0;">Langkah-langkah Validasi OLT</h5>
                        <p style="margin: 0; font-size: 0.82rem; color: #64748b;">Ikuti panduan berikut sebelum melakukan validasi perangkat</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body olt-steps-body" style="padding: 1.5rem 1.75rem 1.75rem; font-size: 0.9rem; line-height: 1.8; color: #334155;">
                    <ol style="margin: 0; padding-left: 20px;">
                        <li class="mb-2">
                            Masuk ke OLT <strong class="step-olt-name text-dark"></strong>, berikut linknya: <span id="step-olt-link"></span>
                        </li>
                        <li class="mb-2">
                            Masukan username & password OLT Anda.
                        </li>
                        <li class="mb-2">
                            Cari MAC Address lama (<strong id="step-mac-old" class="text-danger" style="font-family: monospace;"></strong>) di data OLT <strong class="step-olt-name text-dark"></strong>.
                        </li>
                        <li class="mb-2">
                            Hapus data MAC Address lama (<strong id="step-mac-old-2" class="text-danger" style="font-family: monospace;"></strong>).
                        </li>
                        <li class="mb-2">
                            Cek & cari di data OLT MAC Address baru (<strong id="step-mac-new" class="text-success" style="font-family: monospace;"></strong>), ada atau tidak ada?
                        </li>
                        <li class="mb-2">
                            Kalo tidak ada, lakukan input MAC Address secara manual.
                        </li>
                        <li class="mb-2">
                            Setelah melakukan langkah 1-6, segera lakukan validasi data pengajuan di bawah ini.
                        </li>
                    </ol>
                </div>
                <div class="modal-footer" style="border: none; padding: 1rem 1.75rem 1.75rem;">
                    <button type="button" class="btn-val-reject" data-bs-dismiss="modal" style="margin: 0;">Tutup Panduan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ MIX RADIUS STEPS MODAL ════════════════════════════════ --}}
    <div class="modal fade" id="mixStepsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 25px 50px rgba(15,23,42,0.15);">
                <div class="modal-header" style="border: none; padding: 1.75rem 1.75rem 0.5rem;">
                    <div style="width: 48px; height: 48px; border-radius: 13px; background: rgba(234,88,12,0.1); color: #ea580c; display: flex; align-items: center; justify-content: center; margin-right: 1rem; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="13" width="6" height="8" rx="2"/><rect x="15" y="13" width="6" height="8" rx="2"/><rect x="9" y="3" width="6" height="8" rx="2"/><path d="M12 11v2"/><path d="M6 13v-2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"/></svg>
                    </div>
                    <div>
                        <h5 class="modal-title" style="font-weight: 800; color: #0f172a; font-size: 1.05rem; margin: 0;">Langkah-langkah Validasi Mix Radius</h5>
                        <p style="margin: 0; font-size: 0.82rem; color: #64748b;">Ikuti panduan berikut sebelum melakukan validasi Mix Radius</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem 1.75rem 1.75rem; font-size: 0.9rem; line-height: 1.8; color: #334155;">
                    <ol style="margin: 0; padding-left: 20px;">
                        <li class="mb-2">
                            Masuk ke web Mix Radius <strong class="step-mix-name text-dark"></strong> berikut linknya: <a href="https://mixcio.topsetting.com:973/" target="_blank" class="text-primary fw-bold" style="text-decoration: underline;">https://mixcio.topsetting.com:973/</a>
                        </li>
                        <li class="mb-2">
                            Masukan username & password Mix Radius Anda.
                        </li>
                        <li class="mb-2">
                            Setelah login cari ke menu <strong>Pelanggan</strong> kemudian klik menu <strong>User PPP</strong>.
                        </li>
                        <li class="mb-2">
                            Cari ID Pelanggan (<strong class="step-mix-cust-id text-danger" style="font-family: monospace;"></strong>) di kolom pencarian User PPP.
                        </li>
                        <li class="mb-2">
                            Setelah ketemu kemudian edit data (<strong class="step-mix-cust-id-2 text-danger" style="font-family: monospace;"></strong>) di pojok kanan logo pensil.
                        </li>
                        <li class="mb-2">
                            Setelah muncul data kemudian klik <strong>Paket Langganan</strong>.
                        </li>
                        <li class="mb-2">
                            Kemudian ke kolom <strong>Caller-id</strong> kemudian hapus data MAC Address-nya (kosongkan saja).
                        </li>
                        <li class="mb-2">
                            Kemudian <strong>Bind on Login</strong> ubah jadi <strong>TIDAK</strong>. Setelah diubah jadi tidak, ubah lagi jadi <strong>YA</strong>.
                        </li>
                        <li class="mb-2">
                            Pastikan kolom MAC Address benar-benar kosong, kemudian klik <strong>Simpan</strong>.
                        </li>
                        <li class="mb-2">
                            Setelah melakukan langkah 1-9, segera lakukan validasi data pengajuan di bawah ini.
                        </li>
                    </ol>
                </div>
                <div class="modal-footer" style="border: none; padding: 1rem 1.75rem 1.75rem;">
                    <button type="button" class="btn-val-reject" data-bs-dismiss="modal" style="margin: 0;">Tutup Panduan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mixLayananStepsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 25px 50px rgba(15,23,42,0.15);">
                <div class="modal-header" style="border: none; padding: 1.75rem 1.75rem 0.5rem;">
                    <div style="width: 48px; height: 48px; border-radius: 13px; background: rgba(234,88,12,0.1); color: #ea580c; display: flex; align-items: center; justify-content: center; margin-right: 1rem; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="13" width="6" height="8" rx="2"/><rect x="15" y="13" width="6" height="8" rx="2"/><rect x="9" y="3" width="6" height="8" rx="2"/><path d="M12 11v2"/><path d="M6 13v-2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"/></svg>
                    </div>
                    <div>
                        <h5 class="modal-title step-mix-layanan-title" style="font-weight: 800; color: #0f172a; font-size: 1.05rem; margin: 0;">Langkah-langkah Validasi Mix Radius</h5>
                        <p style="margin: 0; font-size: 0.82rem; color: #64748b;">Ikuti panduan berikut sebelum melakukan validasi Mix Radius</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body mix-layanan-steps-body" style="padding: 1.5rem 1.75rem 1.75rem; font-size: 0.9rem; line-height: 1.8; color: #334155;">
                    <!-- Content will be injected dynamically -->
                </div>
                <div class="modal-footer" style="border: none; padding: 1rem 1.75rem 1.75rem;">
                    <button type="button" class="btn-val-reject" data-bs-dismiss="modal" style="margin: 0;">Tutup Panduan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ ONC STEPS MODAL ══════════════════════════════════════ --}}
    <div class="modal fade" id="oncStepsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 25px 50px rgba(15,23,42,0.15);">
                <div class="modal-header" style="border: none; padding: 1.75rem 1.75rem 0.5rem;">
                    <div style="width: 48px; height: 48px; border-radius: 13px; background: rgba(234,88,12,0.1); color: #ea580c; display: flex; align-items: center; justify-content: center; margin-right: 1rem; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="13" width="6" height="8" rx="2"/><rect x="15" y="13" width="6" height="8" rx="2"/><rect x="9" y="3" width="6" height="8" rx="2"/><path d="M12 11v2"/><path d="M6 13v-2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"/></svg>
                    </div>
                    <div>
                        <h5 class="modal-title step-onc-title" style="font-weight: 800; color: #0f172a; font-size: 1.05rem; margin: 0;">Langkah-langkah Validasi ONC</h5>
                        <p style="margin: 0; font-size: 0.82rem; color: #64748b;">Ikuti panduan berikut sebelum melakukan validasi ONC</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body onc-steps-body" style="padding: 1.5rem 1.75rem 1.75rem; font-size: 0.9rem; line-height: 1.8; color: #334155;">
                    <p style="margin-bottom: 1rem;">
                        PERUBAHAN DIATAS SUDAH DI VALIDASI OLEH SEMUA BAGIANNYA MASING MASING.
                    </p>
                    <p style="margin-bottom: 1rem;">
                        KAMI TINGGAL MENUNGGU KONFIRMASI TAHAP AKHIR DARI ANDA (<strong class="step-onc-auth-user text-dark"></strong>) UNTUK MELAKUKAN PERUBAHAN DATA DIBAGIAN DATA PELANGGAN SECARA OTOMATIS DIBARENGI DENGAN PENGIRIMKAN NOTIFIKASI SUKSES SECARA OTOMATIS KE TEKNISI (<strong class="step-onc-tech text-dark"></strong>).
                    </p>
                    <p style="margin-bottom: 1rem;">
                        TETAPI SEBELUM MELAKUKAN KLIK KONFIRMASI, SEBELUMNYA ANDA HARUS MEMASTIKAN BAHWA ANDA SUDAH MENGECEK MENYETING KEMBALI ROUTER TYPE (<strong class="step-onc-router-new text-success"></strong>), DENGAN MAC ADDRESS BARU (<strong class="step-onc-mac-new text-success" style="font-family: monospace;"></strong>) DAN SUDAH DALAM KEADAAN TERKONEKSI.
                    </p>
                    <p style="margin-bottom: 1rem;">
                        UNTUK MEMASTIKAN SEBAIKNYA ANDA BEKERJA SAMA DENGAN MENELPON TEKNISI PENGAJUAN PERUBAHAN DATA TERSEBUT:<br>
                        NAMA TEKNISI: <strong class="step-onc-tech-2 text-dark"></strong><br>
                        NO WA: <strong class="step-onc-tech-wa text-primary"></strong>
                    </p>
                    <p style="margin: 0;">
                        SETELAH SEMUANYA BERJALAN SUKSES TERKONEKSI, KEMUDIAN ANDA BISA MENYELESAIKANNYA DENGAN MENGKLIK KONFIRMASI (SUPAYA ID PELANGGAN <strong class="step-onc-cust-id text-danger" style="font-family: monospace;"></strong> BERUBAH SECARA OTOMATIS DI BAGIAN DATA PELANGGAN).
                    </p>
                </div>
                <div class="modal-footer" style="border: none; padding: 1rem 1.75rem 1.75rem;">
                    <button type="button" class="btn-val-reject" data-bs-dismiss="modal" style="margin: 0;">Tutup Panduan</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
<script>
// ── CONSTANTS ─────────────────────────────────────────────────────────────────
const SPAM_BASE = "{{ route('spam.index') }}";
const VAL_BASE  = "{{ route('validasi.prosedur.index') }}";
const CSRF      = $('meta[name="csrf-token"]').attr('content');
const LEVELS    = @json(config('prosedur_levels.levels', []));
const authUserName = @json(Auth::user()->name);
const userLevels = [];
@if(Auth::user()->hasPermissionTo('validasi prosedur level 1')) userLevels.push(1); @endif
@if(Auth::user()->hasPermissionTo('validasi prosedur level 2')) userLevels.push(2); @endif
@if(Auth::user()->hasPermissionTo('validasi prosedur level 3')) userLevels.push(3); @endif
@if(Auth::user()->hasPermissionTo('validasi prosedur level 4')) userLevels.push(4); @endif

let activeSpamTab = document.getElementById('nav-pemasangan') ? 'pemasangan' : 'pemutusan';
let pendingId     = null;
let currentItems  = [];

// ── TOAST ─────────────────────────────────────────────────────────────────────
const Toast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false,
    timer: 3000, timerProgressBar: true,
});

// ── TAB SWITCH ────────────────────────────────────────────────────────────────
const PROSEDUR_TABS = ['pemasangan', 'pemutusan', 'pergantian-layanan', 'onu-router', 'pergantian-password', 'rekap'];

function switchSpamTab(tab) {
    activeSpamTab = tab;

    PROSEDUR_TABS.forEach(t => {
        const nav = document.getElementById('nav-' + t);
        const panel = document.getElementById('panel-' + t);
        if (nav) nav.classList.toggle('active', t === tab);
        if (panel) panel.classList.toggle('active', t === tab);
    });

    if (tab === 'pemasangan') {
        if (typeof table !== 'undefined') table.ajax.reload();
    } else if (tab === 'rekap') {
        loadRekapPanel();
    } else {
        loadValidationPanel(tab);
    }
}

// ── VALIDATION PANEL LOADER ───────────────────────────────────────────────────
function loadValidationPanel(type) {
    const loadingEl = document.getElementById('val-loading-' + type);
    const contentEl = document.getElementById('val-content-' + type);

    if (loadingEl) loadingEl.style.display = 'block';
    if (contentEl) contentEl.innerHTML = '';

    const orgId = $('#filter-org-' + type).val() || '';
    $.get(VAL_BASE, { tab: 'queue', type: type, organization_id: orgId }, function(res) {
        if (loadingEl) loadingEl.style.display = 'none';
        const items = res.data || [];
        currentItems = items;

        // Update pill count
        const pill = document.getElementById('pill-' + type);
        if (pill) pill.textContent = items.length;

        if (!items.length) {
            contentEl.innerHTML = `
                <div class="empty-val">
                    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12l2 2 4-4M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z"/>
                    </svg>
                    <h5>Tidak ada antrean aktif</h5>
                    <p>Antrean akan muncul di sini saat teknisi mengajukan prosedur.</p>
                </div>`;
            return;
        }

        contentEl.innerHTML = items.map(item => renderValCard(item, true)).join('');
    }).fail(function() {
        if (loadingEl) loadingEl.style.display = 'none';
        if (contentEl) contentEl.innerHTML = `<div class="empty-val"><h5 class="text-danger">Gagal memuat data.</h5></div>`;
    });
}

$(document).on('change', '[id^="filter-org-"]', function() {
    const type = this.id.replace('filter-org-', '');
    if (type === 'rekap') {
        loadRekapPanel();
    } else {
        loadValidationPanel(type);
    }
});

// ── REKAP HISTORIS LOADER ─────────────────────────────────────────────────────
let currentRekapPage = 1;

function loadRekapPanel(page = 1) {
    currentRekapPage = page;
    const loadingEl = document.getElementById('val-loading-rekap');
    const contentEl = document.getElementById('val-content-rekap');
    const footerEl  = document.getElementById('rekap-pagination-footer');

    if (loadingEl) loadingEl.style.display = 'block';
    if (contentEl) contentEl.innerHTML = '';
    if (footerEl) footerEl.style.setProperty('display', 'none', 'important');

    const orgId = $('#filter-org-rekap').val() || '';
    $.get(VAL_BASE, { tab: 'rekap', page: page, organization_id: orgId }, function(res) {
        if (loadingEl) loadingEl.style.display = 'none';
        const items = res.data || [];
        currentItems = items;
        const total = res.total || 0;
        const lastPage = res.last_page || 1;
        const from = res.from || 0;
        const to = res.to || 0;

        // Update pill count
        const pill = document.getElementById('pill-rekap');
        if (pill) pill.textContent = total;

        if (!items.length) {
            contentEl.innerHTML = `
                <div class="empty-val">
                    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                    </svg>
                    <h5>Tidak ada rekap historis</h5>
                    <p>Log rekap historis yang sudah disetujui atau ditolak akan ditampilkan di sini.</p>
                </div>`;
            return;
        }

        contentEl.innerHTML = items.map(item => renderValCard(item, false)).join('');

        if (footerEl) {
            $('#rekap-start-entry').text(from);
            $('#rekap-end-entry').text(to);
            $('#rekap-total-entries').text(total);

            if (lastPage > 1) {
                footerEl.style.setProperty('display', 'flex', 'important');
                buildRekapPagination(page, lastPage);
            } else {
                footerEl.style.setProperty('display', 'none', 'important');
            }
        }
    }).fail(function() {
        if (loadingEl) loadingEl.style.display = 'none';
        if (contentEl) contentEl.innerHTML = `<div class="empty-val"><h5 class="text-danger">Gagal memuat data rekap.</h5></div>`;
    });
}

function buildRekapPagination(currentPage, lastPage) {
    const pagination = $('#rekap-pagination');
    pagination.empty();

    // Previous Button
    pagination.append(`<li class="page-item ${currentPage === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${currentPage - 1}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a></li>`);

    let startPage = Math.max(1, currentPage - 2);
    let endPage   = Math.min(lastPage, currentPage + 2);

    if (startPage > 1) {
        pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`);
        if (startPage > 2) pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
    }

    for (let i = startPage; i <= endPage; i++) {
        pagination.append(`<li class="page-item ${i === currentPage ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`);
    }

    if (endPage < lastPage) {
        if (endPage < lastPage - 1) pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="${lastPage}">${lastPage}</a></li>`);
    }

    // Next Button
    pagination.append(`<li class="page-item ${currentPage === lastPage ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${currentPage + 1}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a></li>`);

    pagination.find('a').on('click', function(e) {
        e.preventDefault();
        const targetPage = parseInt($(this).data('page'));
        if (!$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
            loadRekapPanel(targetPage);
        }
    });
}

// ── PAYLOAD DIFF RENDERER ─────────────────────────────────────────────────────
function renderPayloadDiff(item) {
    const p    = item.payload || {};
    const type = item.prosedur_type;
    const cust = item.customer || {};

    const activeValidation = (item.validations || []).find(v => v.status === 'pending');
    let targetLevel = null;
    if (activeValidation) {
        if (item.user_can_validate) {
            targetLevel = activeValidation.level;
        } else {
            const intersection = userLevels.filter(lvl => (item.validations || []).some(v => v.level == lvl));
            if (intersection.length > 0) {
                targetLevel = Math.max(...intersection);
            } else {
                targetLevel = activeValidation.level;
            }
        }
    }

    if (type === 'onu-router') {
        if (targetLevel) {
            let greeting = '';
            let headerTitle = '';

            if (targetLevel == 1) {
                greeting = `Hallo Admin : ${authUserName}`;
                headerTitle = `Pesan Validasi Admin`;
            } else if (targetLevel == 2) {
                greeting = `Hallo ${authUserName}`;
                headerTitle = `Pesan Validasi OLT`;
            } else if (targetLevel == 3) {
                greeting = `Hallo Mixradius : ${authUserName}`;
                headerTitle = `Pesan Validasi Mix Radius`;
            } else if (targetLevel == 4) {
                greeting = `Hallo ONC : ${authUserName}`;
                headerTitle = `Pesan Validasi ONC`;
            }

            if (greeting && headerTitle) {
                const technician = item.submitted_by?.name || '-';
                const tanggal = moment(item.created_at).format('DD MMMM YYYY');
                
                const addressParts = [];
                if (cust.hometown?.name) addressParts.push("Kampung " + cust.hometown.name);
                if (cust.rt?.name) addressParts.push("RT " + cust.rt.name);
                if (cust.rw?.name) addressParts.push("RW " + cust.rw.name);
                if (cust.village?.name) addressParts.push("Desa " + cust.village.name);
                if (cust.district?.name) addressParts.push("Kec. " + cust.district.name);
                if (cust.regencie?.name) addressParts.push("Kab. " + cust.regencie.name);
                const alamatStr = addressParts.join(', ') || '-';
                
                const serviceType = (cust.type?.name || '-').toLowerCase();
                const oltName = cust.olt?.name || '-';
                const macOld = (p.mac_address_old || '-').toUpperCase();
                const macNew = (p.mac_address_new || '-').toUpperCase();
                
                const routerLama = p.router_lama_name
                    ? `${p.router_lama_name}${p.router_lama_code ? ' (' + p.router_lama_code + ')' : ''}`
                    : (p.router_lama_id ? `ID: ${p.router_lama_id}` : '-');

                const routerBaru = p.router_new_name
                    ? `${p.router_new_name}${p.router_new_code ? ' (' + p.router_new_code + ')' : ''}`
                    : (p.router_new_id ? `ID: ${p.router_new_id}` : '-');

                const msgText = `${greeting}
Tolong di Baca Data Di bawah ini dengan teliti dan segera Lakukan perubahan data sesuai aturan
pesan di bawah ini.
Pengajuan : Pergantian Perangkat
Di input Oleh teknisi : ${technician}
Pada Tanggal : ${tanggal}
Data Pelanggan
.Id Pelanggan : ${cust.uuid || cust.id || '-'}
.type layanan : ${serviceType}
.nama Pelanggan : ${cust.name || '-'}
.alamat pelanggan : ${alamatStr}
Tujuan Pengajuan
untuk merubah data mac addres di olt (${oltName})
berikut data di bawah ini.
.mac addres lama : ${macOld}
.nama router lama : ${routerLama}
.mac addres baru : ${macNew}
.nama router baru : ${routerBaru}`;

                return `
                <div class="diff-block" id="diff-${item.id}" style="display: none; padding: 1.25rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <div style="font-size: 0.68rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.07em; display: flex; align-items: center; gap: 6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="8" width="20" height="8" rx="2"/><line x1="6" y1="12" x2="6.01" y2="12"/></svg>
                            ${headerTitle}
                        </div>
                        <button type="button" class="btn-copy-msg" onclick="copyMixMessage(${item.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            Salin Pesan
                        </button>
                    </div>
                    <div class="mix-message-container" onclick="copyMixMessage(${item.id})" title="Klik untuk menyalin pesan" style="cursor: pointer; position: relative;">
                        <pre id="mix-msg-${item.id}" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; font-family: 'Consolas', 'Fira Code', monospace; font-size: 0.82rem; line-height: 1.6; color: #334155; padding: 1.25rem; white-space: pre-wrap; word-break: break-all; margin: 0; transition: all 0.2s ease-in-out; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">${msgText}</pre>
                        <div class="mix-message-overlay" style="position: absolute; right: 12px; bottom: 12px; font-size: 0.65rem; color: #94a3b8; font-weight: 700; background: rgba(255,255,255,0.85); padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0; pointer-events: none; opacity: 0.8; transition: opacity 0.2s;">
                            Klik untuk menyalin
                        </div>
                    </div>
                </div>`;
            }
        }

        const macOld    = (p.mac_address_old || '-').toUpperCase();
        const macNew    = (p.mac_address_new || '-').toUpperCase();

        const routerLamaLabel = p.router_lama_name
            ? `${p.router_lama_name}${p.router_lama_code ? ' (' + p.router_lama_code + ')' : ''}`
            : (p.router_lama_id ? `ID: ${p.router_lama_id}` : null);

        const routerNewLabel  = p.router_new_name
            ? `${p.router_new_name}${p.router_new_code ? ' (' + p.router_new_code + ')' : ''}`
            : (p.router_new_id ? `ID: ${p.router_new_id}` : null);

        return `
        <div class="diff-block" id="diff-${item.id}" style="display: none;">
            <div class="diff-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="8" width="20" height="8" rx="2"/><line x1="6" y1="12" x2="6.01" y2="12"/></svg>
                Detail Pergantian Perangkat ONU/Router
            </div>
            <div class="diff-rows">
                <div class="diff-row">
                    <div class="diff-label">MAC Address</div>
                    <div class="diff-old">&#8722; ${macOld}</div>
                    <div class="diff-arrow" style="display:flex;align-items:center;justify-content:center;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></div>
                    <div class="diff-new">&#43; ${macNew}</div>
                </div>
                ${routerLamaLabel ? `<div class="diff-row diff-info-row"><div class="diff-label">Router Lama</div><div class="diff-info-val" style="color:#dc2626;font-weight:700;font-family:monospace;">${routerLamaLabel}</div></div>` : ''}
                ${routerNewLabel  ? `<div class="diff-row diff-info-row"><div class="diff-label">Router Baru</div><div class="diff-info-val" style="color:#16a34a;font-weight:700;font-family:monospace;">${routerNewLabel}</div></div>` : ''}
            </div>
        </div>`;
    }

    if (type === 'pergantian-layanan') {
        if (p.service_type === 'voucher-ke-pppoe' && targetLevel == 3) {
            const greeting = `Hallo Mixradius : ${authUserName}`;
            const headerTitle = `Pesan Validasi Mix Radius`;
            const technician = item.submitted_by?.name || '-';
            const tanggal = moment(item.created_at).format('DD MMMM YYYY');
            
            const addressParts = [];
            if (cust.hometown?.name) addressParts.push("Kampung " + cust.hometown.name);
            if (cust.rt?.name) addressParts.push("RT " + cust.rt.name);
            if (cust.rw?.name) addressParts.push("RW " + cust.rw.name);
            if (cust.village?.name) addressParts.push("Desa " + cust.village.name);
            if (cust.district?.name) addressParts.push("Kec. " + cust.district.name);
            if (cust.regencie?.name) addressParts.push("Kab. " + cust.regencie.name);
            const alamatStr = addressParts.join(', ') || '-';

            const mixName = p.mic_radius_name || '-';

            const msgText = `${greeting}
Tolong di Baca Data Di bawah ini dengan teliti dan segera Lakukan perubahan data sesuai aturan
pesan di bawah ini.
Pengajuan : Pergantian Layanan Voucher ke PPPOE
Di input Oleh teknisi : ${technician}
Pada Tanggal : ${tanggal}
Data Pelanggan Sebelumnya
.Id Pelanggan : ${cust.uuid || cust.id || '-'}
.type layanan sebelumya : VOUCHER
.nama Pelanggan : ${cust.name || '-'}
.alamat pelanggan : ${alamatStr}
Tujuan Pengajuan
Untuk merubah data pelanggan dari voucher ke pppoe
MASUKAN DATA PPPOE dibawah ini
.Tipe Paket : ${p.paket_name || '-'}
.Mix Radius : ${mixName}
.Nama WiFi : ${p.name_wifi || '-'}
.Password WiFi : ${p.password_wifi || '-'}
.Username PPPoE : ${p.pppoe_username || '-'}
.Password PPPoE : ${p.pppoe_password || '-'}`;

            return `
            <div class="diff-block" id="diff-${item.id}" style="display: none; padding: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <div style="font-size: 0.68rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.07em; display: flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="8" width="20" height="8" rx="2"/><line x1="6" y1="12" x2="6.01" y2="12"/></svg>
                        ${headerTitle}
                    </div>
                    <button type="button" class="btn-copy-msg" onclick="copyMixMessage(${item.id})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Salin Pesan
                    </button>
                </div>
                <div class="mix-message-container" onclick="copyMixMessage(${item.id})" title="Klik untuk menyalin pesan" style="cursor: pointer; position: relative;">
                    <pre id="mix-msg-${item.id}" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; font-family: 'Consolas', 'Fira Code', monospace; font-size: 0.82rem; line-height: 1.6; color: #334155; padding: 1.25rem; white-space: pre-wrap; word-break: break-all; margin: 0; transition: all 0.2s ease-in-out; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">${msgText}</pre>
                    <div class="mix-message-overlay" style="position: absolute; right: 12px; bottom: 12px; font-size: 0.65rem; color: #94a3b8; font-weight: 700; background: rgba(255,255,255,0.85); padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0; pointer-events: none; opacity: 0.8; transition: opacity 0.2s;">
                        Klik untuk menyalin
                    </div>
                </div>
            </div>`;
        } else if (p.service_type === 'pppoe-ke-voucher' && targetLevel == 3) {
            const greeting = `Hallo Mixradius : ${authUserName}`;
            const headerTitle = `Pesan Validasi Mix Radius`;
            const technician = item.submitted_by?.name || '-';
            const tanggal = moment(item.created_at).format('DD MMMM YYYY');
            const custId = cust.uuid || cust.id || '-';
            
            const addressParts = [];
            if (cust.hometown?.name) addressParts.push("Kampung " + cust.hometown.name);
            if (cust.rt?.name) addressParts.push("RT " + cust.rt.name);
            if (cust.rw?.name) addressParts.push("RW " + cust.rw.name);
            if (cust.village?.name) addressParts.push("Desa " + cust.village.name);
            if (cust.district?.name) addressParts.push("Kec. " + cust.district.name);
            if (cust.regencie?.name) addressParts.push("Kab. " + cust.regencie.name);
            const alamatStr = addressParts.join(', ') || '-';

            const mixName = p.mic_radius_name || cust.mic_radius?.name || '-';

            const msgText = `${greeting}
Tolong di Baca Data Di bawah ini dengan teliti dan segera Lakukan perubahan data sesuai aturan
pesan di bawah ini.
Pengajuan : Pergantian Layanan PPPOE ke VOUCHER
Di input Oleh teknisi : ${technician}
Pada Tanggal : ${tanggal}
Data Pelanggan Sebelumnya
.Id Pelanggan : ${custId}
.type layanan sebelumya : PPPOE
.nama Pelanggan : ${cust.name || '-'}
.alamat pelanggan : ${alamatStr}
Tujuan Pengajuan
Untuk merubah data pelanggan dari PPPOE KE VOUCHER
-anda haru melakukan penghapusan data pppoe dengan id ${custId} di web mixradius
Dengan akun mixradius ${mixName}
Langkah langkah
1.Masuk ke web akun mixradius ${mixName} berikut linknya
https://mixcio.topsetting.com:973/
2.masukan username & password mixradius anda
3.setelah login cari kemenu pelanggan kemudian klik menu user pppoe
4.Cari Id Pelanggan ${custId} di kolom pencarian user ppp
5.setelah ketemu kemudian klik tombol hapus
6.setelah melakukan langkah 1-5 segera melakuan validasi data pengajuan di bawah ini`;

            return `
            <div class="diff-block" id="diff-${item.id}" style="display: none; padding: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <div style="font-size: 0.68rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.07em; display: flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="8" width="20" height="8" rx="2"/><line x1="6" y1="12" x2="6.01" y2="12"/></svg>
                        ${headerTitle}
                    </div>
                    <button type="button" class="btn-copy-msg" onclick="copyMixMessage(${item.id})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Salin Pesan
                    </button>
                </div>
                <div class="mix-message-container" onclick="copyMixMessage(${item.id})" title="Klik untuk menyalin pesan" style="cursor: pointer; position: relative;">
                    <pre id="mix-msg-${item.id}" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; font-family: 'Consolas', 'Fira Code', monospace; font-size: 0.82rem; line-height: 1.6; color: #334155; padding: 1.25rem; white-space: pre-wrap; word-break: break-all; margin: 0; transition: all 0.2s ease-in-out; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">${msgText}</pre>
                    <div class="mix-message-overlay" style="position: absolute; right: 12px; bottom: 12px; font-size: 0.65rem; color: #94a3b8; font-weight: 700; background: rgba(255,255,255,0.85); padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0; pointer-events: none; opacity: 0.8; transition: opacity 0.2s;">
                        Klik untuk menyalin
                    </div>
                </div>
            </div>`;
        } else if (p.service_type === 'voucher-ke-pppoe' && targetLevel == 4) {
            const greeting = `Hallo ONC : ${authUserName}`;
            const headerTitle = `Pesan Validasi ONC`;
            const technician = item.submitted_by?.name || '-';
            const techName = (item.submitted_by || item.submittedBy)?.name || '-';
            const techTelp = (item.submitted_by || item.submittedBy)?.telp || '-';
            const tanggal = moment(item.created_at).format('DD MMMM YYYY');
            const custId = cust.uuid || cust.id || '-';
            
            const addressParts = [];
            if (cust.hometown?.name) addressParts.push("Kampung " + cust.hometown.name);
            if (cust.rt?.name) addressParts.push("RT " + cust.rt.name);
            if (cust.rw?.name) addressParts.push("RW " + cust.rw.name);
            if (cust.village?.name) addressParts.push("Desa " + cust.village.name);
            if (cust.district?.name) addressParts.push("Kec. " + cust.district.name);
            if (cust.regencie?.name) addressParts.push("Kab. " + cust.regencie.name);
            const alamatStr = addressParts.join(', ') || '-';

            const macNew = (p.mac_address_new || cust.mac_address || '-').toUpperCase();
            const routerNew = p.router_new_name
                ? `${p.router_new_name}${p.router_new_code ? ' (' + p.router_new_code + ')' : ''}`
                : (cust.router ? cust.router.name : '-');

            const msgText = `${greeting}
Tolong di Baca Data Di bawah ini dengan teliti dan segera Lakukan perubahan data sesuai aturan
pesan di bawah ini.
Pengajuan : Pergantian Layanan Voucher ke PPPOE
Di input Oleh teknisi : ${technician}
Pada Tanggal : ${tanggal}
Hallo Onc
BERIKUT DATA PELANGGAN SEBELUM DI EDIT DAN SESUDAH DI EDIT
Data idpelanggan ${custId} sebelum di edit
.Id Pelanggan : ${custId}
.nama Pelanggan : ${cust.name || '-'}
.alamat pelanggan : ${alamatStr}
.type layanan sebelumya : VOUCHER
Data idpelanggan ${custId} sesudah di edit
.Id Pelanggan : ${custId}
.nama Pelanggan : ${cust.name || '-'}
.alamat pelanggan : ${alamatStr}
.type layanan : PPPOE
.Tipe Paket : ${p.paket_name || '-'}
.Mix Radius : ${p.mic_radius_name || '-'}
.Nama WiFi : ${p.name_wifi || '-'}
.Password WiFi : ${p.password_wifi || '-'}
.Username PPPoE : ${p.pppoe_username || '-'}
.Password PPPoE : ${p.pppoe_password || '-'}
PERUBAHAN DIATAS SUDAH DI VALIDASI OLEH SEMUA BAGIANNYA MASING MASING.
KAMI TINGGAL MENUNGGU KONFIRMASI TAHAP AKHIR DARI ANDA ( ${authUserName} ) UNTUK
MELAKUKAN PERUBAHAN DATA DIBAGIAN DATA PELANGGAN SECARA OTOMATIS DIBARENGI
DENGAN PENGIRIMKAN NOTIFIKASI SUKSES SECARA OTOMATIS KE TEKNISI ( ${technician} )
TETAPI SEBELUM MELAKUKAN KLIK KONFIRMASI
SEBELUMNYA ANDA HARUS MEMASTIKAN BAHWA ANDA SUDAH MENGECEK MENYETING KEMBALI
ROUTER TYPE ( ${routerNew} ),DENGAN MAC ADDRES BARU ( ${macNew} ) DAN
SUDAH DALAM KE ADAAN TERKONEKSI,UNTUK MEMASTIKAN SEBAIKNYA ANDA BEKERJA SAMA
DENGAN MENELPON TEKNISI PENGAJUAN PERUBAHAN DATA TERSEBUT
NAMA TEKNISI ( ${techName} ) NO WA ( ${techTelp} )
SETELAH SEMUANYA BERJALAN SUKSES TERKONEKSI ,KEMUDIAN ANDA BISA MENYELESAIKANYA
DENGAN MENGKLIK KONFIRMASI (SUPAYA ID PELANGGAN ( ${custId} ) BERUBAH SECARA
OTOMATIS DI BAGIAN DATA PELANGGAN.`;

            return `
            <div class="diff-block" id="diff-${item.id}" style="display: none; padding: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <div style="font-size: 0.68rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.07em; display: flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="8" width="20" height="8" rx="2"/><line x1="6" y1="12" x2="6.01" y2="12"/></svg>
                        ${headerTitle}
                    </div>
                    <button type="button" class="btn-copy-msg" onclick="copyMixMessage(${item.id})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Salin Pesan
                    </button>
                </div>
                <div class="mix-message-container" onclick="copyMixMessage(${item.id})" title="Klik untuk menyalin pesan" style="cursor: pointer; position: relative;">
                    <pre id="mix-msg-${item.id}" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; font-family: 'Consolas', 'Fira Code', monospace; font-size: 0.82rem; line-height: 1.6; color: #334155; padding: 1.25rem; white-space: pre-wrap; word-break: break-all; margin: 0; transition: all 0.2s ease-in-out; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">${msgText}</pre>
                    <div class="mix-message-overlay" style="position: absolute; right: 12px; bottom: 12px; font-size: 0.65rem; color: #94a3b8; font-weight: 700; background: rgba(255,255,255,0.85); padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0; pointer-events: none; opacity: 0.8; transition: opacity 0.2s;">
                        Klik untuk menyalin
                    </div>
                </div>
            </div>`;
        } else if (p.service_type === 'pppoe-ke-voucher' && targetLevel == 4) {
            const greeting = `Hallo ONC : ${authUserName}`;
            const headerTitle = `Pesan Validasi ONC`;
            const technician = item.submitted_by?.name || '-';
            const techName = (item.submitted_by || item.submittedBy)?.name || '-';
            const techTelp = (item.submitted_by || item.submittedBy)?.telp || '-';
            const tanggal = moment(item.created_at).format('DD MMMM YYYY');
            const custId = cust.uuid || cust.id || '-';
            
            const addressParts = [];
            if (cust.hometown?.name) addressParts.push("Kampung " + cust.hometown.name);
            if (cust.rt?.name) addressParts.push("RT " + cust.rt.name);
            if (cust.rw?.name) addressParts.push("RW " + cust.rw.name);
            if (cust.village?.name) addressParts.push("Desa " + cust.village.name);
            if (cust.district?.name) addressParts.push("Kec. " + cust.district.name);
            if (cust.regencie?.name) addressParts.push("Kab. " + cust.regencie.name);
            const alamatStr = addressParts.join(', ') || '-';

            const macNew = (p.mac_address_new || cust.mac_address || '-').toUpperCase();
            const routerNew = p.router_new_name
                ? `${p.router_new_name}${p.router_new_code ? ' (' + p.router_new_code + ')' : ''}`
                : (cust.router ? cust.router.name : '-');

            const msgText = `${greeting}
Tolong di Baca Data Di bawah ini dengan teliti dan segera Lakukan perubahan data sesuai aturan
pesan di bawah ini.
Pengajuan : Pergantian Layanan PPPOE ke VOUCHER
Di input Oleh teknisi : ${technician}
Pada Tanggal : ${tanggal}
Hallo Onc
BERIKUT DATA PELANGGAN SEBELUM DI EDIT DAN SESUDAH DI EDIT
Data idpelanggan ${custId} sebelum di edit
.Id Pelanggan : ${custId}
.nama Pelanggan : ${cust.name || '-'}
.alamat pelanggan : ${alamatStr}
.type layanan sebelumya : PPPOE
.Tipe Paket sebelumnya : ${cust.paket?.name || '-'}
.Mix Radius sebelumnya : ${cust.mic_radius?.name || '-'}
.Username PPPoE sebelumnya : ${cust.pppoe_username || '-'}
.Password PPPoE sebelumnya : ${cust.pppoe_password || '-'}
Data idpelanggan ${custId} sesudah di edit
.Id Pelanggan : ${custId}
.nama Pelanggan : ${cust.name || '-'}
.alamat pelanggan : ${alamatStr}
.type layanan : VOUCHER
${p.name_wifi ? `.Nama WiFi : ${p.name_wifi}\n` : ''}${p.password_wifi ? `.Password WiFi : ${p.password_wifi}\n` : ''}PERUBAHAN DIATAS SUDAH DI VALIDASI OLEH SEMUA BAGIANNYA MASING MASING.
KAMI TINGGAL MENUNGGU KONFIRMASI TAHAP AKHIR DARI ANDA ( ${authUserName} ) UNTUK
MELAKUKAN PERUBAHAN DATA DIBAGIAN DATA PELANGGAN SECARA OTOMATIS DIBARENGI
DENGAN PENGIRIMKAN NOTIFIKASI SUKSES SECARA OTOMATIS KE TEKNISI ( ${technician} )
TETAPI SEBELUM MELAKUKAN KLIK KONFIRMASI
SEBELUMNYA ANDA HARUS MEMASTIKAN BAHWA ANDA SUDAH MENGECEK MENYETING KEMBALI
ROUTER TYPE ( ${routerNew} ),DENGAN MAC ADDRES BARU ( ${macNew} ) DAN
SUDAH DALAM KE ADAAN TERKONEKSI,UNTUK MEMASTIKAN SEBAIKNYA ANDA BEKERJA SAMA
DENGAN MENELPON TEKNISI PENGAJUAN PERUBAHAN DATA TERSEBUT
NAMA TEKNISI ( ${techName} ) NO WA ( ${techTelp} )
SETELAH SEMUANYA BERJALAN SUKSES TERKONEKSI ,KEMUDIAN ANDA BISA MENYELESAIKANYA
DENGAN MENGKLIK KONFIRMASI (SUPAYA ID PELANGGAN ( ${custId} ) BERUBAH SECARA
OTOMATIS DI BAGIAN DATA PELANGGAN.`;

            return `
            <div class="diff-block" id="diff-${item.id}" style="display: none; padding: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <div style="font-size: 0.68rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.07em; display: flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="8" width="20" height="8" rx="2"/><line x1="6" y1="12" x2="6.01" y2="12"/></svg>
                        ${headerTitle}
                    </div>
                    <button type="button" class="btn-copy-msg" onclick="copyMixMessage(${item.id})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Salin Pesan
                    </button>
                </div>
                <div class="mix-message-container" onclick="copyMixMessage(${item.id})" title="Klik untuk menyalin pesan" style="cursor: pointer; position: relative;">
                    <pre id="mix-msg-${item.id}" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; font-family: 'Consolas', 'Fira Code', monospace; font-size: 0.82rem; line-height: 1.6; color: #334155; padding: 1.25rem; white-space: pre-wrap; word-break: break-all; margin: 0; transition: all 0.2s ease-in-out; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">${msgText}</pre>
                    <div class="mix-message-overlay" style="position: absolute; right: 12px; bottom: 12px; font-size: 0.65rem; color: #94a3b8; font-weight: 700; background: rgba(255,255,255,0.85); padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0; pointer-events: none; opacity: 0.8; transition: opacity 0.2s;">
                        Klik untuk menyalin
                    </div>
                </div>
            </div>`;
        }

        const serviceMap = { 'pppoe-ke-voucher': 'PPPoE → Voucher', 'voucher-ke-pppoe': 'Voucher → PPPoE' };
        const svcLabel   = serviceMap[p.service_type] || p.service_type || '-';
        const parts      = svcLabel.split('→');
        const svcOld     = (parts[0] || '').trim();
        const svcNew     = (parts[1] || '').trim();

        const maskPwd = (v) => v ? '•'.repeat(Math.min(v.length, 8)) : '-';
        
        const displayWifiPwd = p.service_type === 'voucher-ke-pppoe' ? (p.password_wifi || '-') : maskPwd(p.password_wifi);
        const displayPppoePwd = p.service_type === 'voucher-ke-pppoe' ? (p.pppoe_password || '-') : maskPwd(p.pppoe_password);
        const wifiPwdStyle = p.service_type === 'voucher-ke-pppoe' ? '' : 'letter-spacing:2px;';
        const pppoePwdStyle = p.service_type === 'voucher-ke-pppoe' ? '' : 'letter-spacing:2px;';

        return `
        <div class="diff-block" id="diff-${item.id}" style="display: none;">
            <div class="diff-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                Detail Pergantian Layanan
            </div>
            <div class="diff-rows">
                <div class="diff-row">
                    <div class="diff-label">Tipe Pergantian</div>
                    <div class="diff-old">&#8722; ${svcOld || '-'}</div>
                    <div class="diff-arrow" style="display:flex;align-items:center;justify-content:center;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></div>
                    <div class="diff-new">&#43; ${svcNew || '-'}</div>
                </div>
                ${p.name_wifi     ? `<div class="diff-row diff-info-row"><div class="diff-label">Nama WiFi (SSID)</div><div class="diff-info-val" style="font-family:monospace;font-weight:700;">${p.name_wifi}</div></div>` : ''}
                ${p.password_wifi ? `<div class="diff-row diff-info-row"><div class="diff-label">Password WiFi</div><div class="diff-info-val" style="font-family:monospace;${wifiPwdStyle}">${displayWifiPwd}</div></div>` : ''}
                ${p.pppoe_username ? `<div class="diff-row diff-info-row"><div class="diff-label">PPPoE Username</div><div class="diff-info-val" style="font-family:monospace;font-weight:700;">${p.pppoe_username}</div></div>` : ''}
                ${p.pppoe_password ? `<div class="diff-row diff-info-row"><div class="diff-label">PPPoE Password</div><div class="diff-info-val" style="font-family:monospace;${pppoePwdStyle}">${displayPppoePwd}</div></div>` : ''}
                ${p.service_type !== 'pppoe-ke-voucher' && p.paket_name    ? `<div class="diff-row diff-info-row"><div class="diff-label">Tipe Paket</div><div class="diff-info-val" style="color:#2563eb;font-weight:700;">${p.paket_name}</div></div>` : (p.service_type !== 'pppoe-ke-voucher' && p.paket_id ? `<div class="diff-row diff-info-row"><div class="diff-label">Tipe Paket</div><div class="diff-info-val">ID: ${p.paket_id}</div></div>` : '')}
                ${p.service_type !== 'pppoe-ke-voucher' && p.price_name    ? `<div class="diff-row diff-info-row"><div class="diff-label">Tipe Pembayaran</div><div class="diff-info-val" style="color:#2563eb;font-weight:700;">${p.price_name}</div></div>` : (p.service_type !== 'pppoe-ke-voucher' && p.price_id ? `<div class="diff-row diff-info-row"><div class="diff-label">Tipe Pembayaran</div><div class="diff-info-val">ID: ${p.price_id}</div></div>` : '')}
                ${p.service_type !== 'pppoe-ke-voucher' && p.mic_radius_name ? `<div class="diff-row diff-info-row"><div class="diff-label">Mix Radius</div><div class="diff-info-val" style="font-weight:700;">${p.mic_radius_name}</div></div>` : (p.service_type !== 'pppoe-ke-voucher' && p.mic_radius_id ? `<div class="diff-row diff-info-row"><div class="diff-label">Mix Radius</div><div class="diff-info-val">ID: ${p.mic_radius_id}</div></div>` : '')}
            </div>
        </div>`;
    }

    if (type === 'pemutusan') {
        const fotoPerangkatUrl = p.foto_perangkat_path ? (p.foto_perangkat_url || (window.location.origin + '/storage/' + p.foto_perangkat_path)) : '-';
        const fotoPembayaranUrl = p.foto_pembayaran_path ? (p.foto_pembayaran_url || (window.location.origin + '/storage/' + p.foto_pembayaran_path)) : '-';
        const fotoPerangkatHtml = p.foto_perangkat_path ? `<a href="${fotoPerangkatUrl}" target="_blank" onclick="event.stopPropagation()" style="color: #2563eb; text-decoration: underline;">${fotoPerangkatUrl}</a>` : '-';
        const fotoPembayaranHtml = p.foto_pembayaran_path ? `<a href="${fotoPembayaranUrl}" target="_blank" onclick="event.stopPropagation()" style="color: #2563eb; text-decoration: underline;">${fotoPembayaranUrl}</a>` : '-';

        if (targetLevel == 2) {
            const greeting = `Hallo ${authUserName}`;
            const headerTitle = `Pesan Validasi OLT`;
            const technician = item.submitted_by?.name || '-';
            const tanggal = moment(item.created_at).format('DD MMMM YYYY');
            const custId = cust.uuid || cust.id || '-';
            const typeLayanan = (cust.type?.name || '-').toUpperCase();
            const oltName = cust.olt?.name || '-';
            const oltLink = cust.olt?.link || '#';
            const macLama = (cust.mac_address || '-').toUpperCase();

            const addressParts = [];
            if (cust.hometown?.name) addressParts.push("Kampung " + cust.hometown.name);
            if (cust.rt?.name) addressParts.push("RT " + cust.rt.name);
            if (cust.rw?.name) addressParts.push("RW " + cust.rw.name);
            if (cust.village?.name) addressParts.push("Desa " + cust.village.name);
            if (cust.district?.name) addressParts.push("Kec. " + cust.district.name);
            if (cust.regencie?.name) addressParts.push("Kab. " + cust.regencie.name);
            const alamatStr = addressParts.join(', ') || '-';

            const paymentType = (cust.price?.name || 'UNKNOWN').toUpperCase();
            const isPostpaid = paymentType.includes('POSTPAID') || paymentType.includes('PAKE DULU');
            const isVoucher = typeLayanan.includes('VOUCHER') || typeLayanan.includes('HOTSPOT');

            let buktiBayarLine = '';
            if (!isVoucher) {
                if (isPostpaid) {
                    buktiBayarLine = `\n.Bukti Pembayaran : ${fotoPembayaranHtml}`;
                }
            }

            const msgText = `${greeting}
Tolong di Baca Data Di bawah ini dengan teliti dan segera Lakukan perubahan data sesuai aturan
pesan di bawah ini.
Pengajuan : Pemutusan Layanan
Di input Oleh teknisi : ${technician}
Pada Tanggal : ${tanggal}
Data Pelanggan
.Id Pelanggan : ${custId}
.type layanan : ${typeLayanan}
.nama Pelanggan : ${cust.name || '-'}
.alamat pelanggan : ${alamatStr}
${isVoucher ? '' : `.Bukti Foto Perangkat : ${fotoPerangkatHtml}`}${buktiBayarLine}
Tujuan Pengajuan
Untuk Melakukan Penghapusan Data pelanggan Di Data Olt (${oltName})
Berikut Langkah langkah nya bawah ini.
Langkah langkah
1.Masuk ke olt (${oltName}) berikut linknya ${oltLink}
2.masukan username & password olt anda
3.cari mac addres lama (${macLama}) di data olt (${oltName})
4.hapus data mac addres lama (${macLama})
5.setelah melakukan langkah 1-4 segera melakuan validasi data pengajuan di bawah ini.`;

            return `
            <div class="diff-block" id="diff-${item.id}" style="display: none; padding: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <div style="font-size: 0.68rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.07em; display: flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="8" width="20" height="8" rx="2"/><line x1="6" y1="12" x2="6.01" y2="12"/></svg>
                        ${headerTitle}
                    </div>
                    <button type="button" class="btn-copy-msg" onclick="copyMixMessage(${item.id})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Salin Pesan
                    </button>
                </div>
                <div class="mix-message-container" onclick="copyMixMessage(${item.id})" title="Klik untuk menyalin pesan" style="cursor: pointer; position: relative;">
                    <pre id="mix-msg-${item.id}" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; font-family: 'Consolas', 'Fira Code', monospace; font-size: 0.82rem; line-height: 1.6; color: #334155; padding: 1.25rem; white-space: pre-wrap; word-break: break-all; margin: 0; transition: all 0.2s ease-in-out; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">${msgText}</pre>
                    <div class="mix-message-overlay" style="position: absolute; right: 12px; bottom: 12px; font-size: 0.65rem; color: #94a3b8; font-weight: 700; background: rgba(255,255,255,0.85); padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0; pointer-events: none; opacity: 0.8; transition: opacity 0.2s;">
                        Klik untuk menyalin
                    </div>
                </div>
            </div>`;
        } else if (targetLevel == 3) {
            const typeLayanan = (cust.type?.name || '-').toUpperCase();
            if (typeLayanan === 'PPPOE') {
                const greeting = `Hallo Mixradius : ${authUserName}`;
                const headerTitle = `Pesan Validasi Mix Radius`;
                const technician = item.submitted_by?.name || '-';
                const tanggal = moment(item.created_at).format('DD MMMM YYYY');
                const custId = cust.uuid || cust.id || '-';
                const mixName = cust.mic_radius?.name || '-';

                const addressParts = [];
                if (cust.hometown?.name) addressParts.push("Kampung " + cust.hometown.name);
                if (cust.rt?.name) addressParts.push("RT " + cust.rt.name);
                if (cust.rw?.name) addressParts.push("RW " + cust.rw.name);
                if (cust.village?.name) addressParts.push("Desa " + cust.village.name);
                if (cust.district?.name) addressParts.push("Kec. " + cust.district.name);
                if (cust.regencie?.name) addressParts.push("Kab. " + cust.regencie.name);
                const alamatStr = addressParts.join(', ') || '-';

                const paymentType = (cust.price?.name || 'UNKNOWN').toUpperCase();
                const isPostpaid = paymentType.includes('POSTPAID') || paymentType.includes('PAKE DULU');
                const isVoucher = typeLayanan.includes('VOUCHER') || typeLayanan.includes('HOTSPOT');

                let buktiBayarLine = '';
                if (!isVoucher) {
                    if (isPostpaid) {
                        buktiBayarLine = `\n.Bukti Pembayaran : ${fotoPembayaranHtml}`;
                    }
                }

                const msgText = `${greeting}
Tolong di Baca Data Di bawah ini dengan teliti dan segera Lakukan perubahan data sesuai aturan
pesan di bawah ini.
Pengajuan : Pemutusan Layanan
Di input Oleh teknisi : ${technician}
Pada Tanggal : ${tanggal}
Data Pelanggan
.Id Pelanggan : ${custId}
.type layanan : PPPOE
.nama Pelanggan : ${cust.name || '-'}
.alamat pelanggan : ${alamatStr}
${isVoucher ? '' : `.Bukti Foto Perangkat : ${fotoPerangkatHtml}`}${buktiBayarLine}
Tujuan Pengajuan
Untuk Menghapus Data Pelanggan yang sudah berhenti berlangganan
Dengan alasan (${p.alasan || '-'})
Langkah langkah
1.Masuk ke web akun mixradius (${mixName}) berikut linknya
https://mixcio.topsetting.com:973/
2.masukan username & password mixradius anda
3.setelah login cari kemenu pelanggan kemudian klik menu user pppoe
4.Cari Id Pelanggan (${custId}) di kolom pencarian user ppp
5.setelah ketemu kemudian klik tombol hapus
6.setelah melakukan langkah 1-5 segera melakuan validasi data pengajuan di bawah ini`;

                return `
                <div class="diff-block" id="diff-${item.id}" style="display: none; padding: 1.25rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <div style="font-size: 0.68rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.07em; display: flex; align-items: center; gap: 6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="8" width="20" height="8" rx="2"/><line x1="6" y1="12" x2="6.01" y2="12"/></svg>
                            ${headerTitle}
                        </div>
                        <button type="button" class="btn-copy-msg" onclick="copyMixMessage(${item.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            Salin Pesan
                        </button>
                    </div>
                    <div class="mix-message-container" onclick="copyMixMessage(${item.id})" title="Klik untuk menyalin pesan" style="cursor: pointer; position: relative;">
                        <pre id="mix-msg-${item.id}" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; font-family: 'Consolas', 'Fira Code', monospace; font-size: 0.82rem; line-height: 1.6; color: #334155; padding: 1.25rem; white-space: pre-wrap; word-break: break-all; margin: 0; transition: all 0.2s ease-in-out; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">${msgText}</pre>
                        <div class="mix-message-overlay" style="position: absolute; right: 12px; bottom: 12px; font-size: 0.65rem; color: #94a3b8; font-weight: 700; background: rgba(255,255,255,0.85); padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0; pointer-events: none; opacity: 0.8; transition: opacity 0.2s;">
                            Klik untuk menyalin
                        </div>
                    </div>
                </div>`;
            }
        } else if (targetLevel == 4) {
            const greeting = `Hallo ONC : ${authUserName}`;
            const headerTitle = `Pesan Validasi ONC`;
            const technician = item.submitted_by?.name || '-';
            const techName = (item.submitted_by || item.submittedBy)?.name || '-';
            const techTelp = (item.submitted_by || item.submittedBy)?.telp || '-';
            const tanggal = moment(item.created_at).format('DD MMMM YYYY');
            const custId = cust.uuid || cust.id || '-';
            const typeLayanan = (cust.type?.name || '-').toUpperCase();

            const addressParts = [];
            if (cust.hometown?.name) addressParts.push("Kampung " + cust.hometown.name);
            if (cust.rt?.name) addressParts.push("RT " + cust.rt.name);
            if (cust.rw?.name) addressParts.push("RW " + cust.rw.name);
            if (cust.village?.name) addressParts.push("Desa " + cust.village.name);
            if (cust.district?.name) addressParts.push("Kec. " + cust.district.name);
            if (cust.regencie?.name) addressParts.push("Kab. " + cust.regencie.name);
            const alamatStr = addressParts.join(', ') || '-';

            const msgText = `${greeting}
Tolong di Baca Data Di bawah ini dengan teliti dan segera Lakukan perubahan data sesuai aturan
pesan di bawah ini.
Pengajuan : Pemutusan Layanan
Di input Oleh teknisi : ${technician}
Pada Tanggal : ${tanggal}
Hallo Onc
BERIKUT DATA PENGAJUAN PEMUTUSAN LANGGAN
.Id Pelanggan : ${custId}
.nama Pelanggan : ${cust.name || '-'}
.alamat pelanggan : ${alamatStr}
.type layanan : ${typeLayanan}
${(() => {
    const pt = (cust.price?.name || 'UNKNOWN').toUpperCase();
    const isPost = pt.includes('POSTPAID') || pt.includes('PAKE DULU');
    const isVch = typeLayanan.includes('VOUCHER') || typeLayanan.includes('HOTSPOT');
    let lines = '';
    if (!isVch) lines += `.Bukti Foto Perangkat : ${fotoPerangkatHtml}`;
    if (!isVch && isPost) lines += `\n.Bukti Pembayaran : ${fotoPembayaranHtml}`;
    return lines;
})()}
PENGHAPUSAN DATA PELANGGAN DIATAS SUDAH DI VALIDASI OLEH SEMUA BAGIANNYA MASING MASING.
KAMI TINGGAL MENUNGGU KONFIRMASI TAHAP AKHIR DARI ANDA ( ${authUserName} ) UNTUK
MELAKUKAN PENGHAPUSAN DATA DIBAGIAN DATA PELANGGAN SECARA OTOMATIS DIBARENGI
DENGAN PENGIRIMKAN NOTIFIKASI BERHENTI BERLANGGANAN SUKSES SECARA OTOMATIS KE
TEKNISI ( ${technician} )
TETAPI SEBELUM MELAKUKAN KLIK KONFIRMASI PENGHAPUSAN DATA PERMANENT
SEBELUMNYA ANDA HARUS MEMASTIKAN BAHWA ANDA SUDAH MENGECEK MENYETING ROUTER
TERSEBUT MENJADI SETELAN AWAL KEMBALI (SETELAN KANTOR)
DISKUSIKAN DENGAN TEKNISI PENGAJUAN
NAMA TEKNISI ( ${techName} ) NO WA ( ${techTelp} )
SETELAH PENYETINGAN SELESAI,ANDA BISA MENYELESAIKANYA
DENGAN MENGKLIK KONFIRMASI PENGHAPUSAN DATA (SUPAYA ID PELANGGAN (${custId}) DI HAPUS PERMANEN SECARA OTOMATIS DI BAGIAN DATA PELANGGAN)`;

            return `
            <div class="diff-block" id="diff-${item.id}" style="display: none; padding: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <div style="font-size: 0.68rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.07em; display: flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="8" width="20" height="8" rx="2"/><line x1="6" y1="12" x2="6.01" y2="12"/></svg>
                        ${headerTitle}
                    </div>
                    <button type="button" class="btn-copy-msg" onclick="copyMixMessage(${item.id})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Salin Pesan
                    </button>
                </div>
                <div class="mix-message-container" onclick="copyMixMessage(${item.id})" title="Klik untuk menyalin pesan" style="cursor: pointer; position: relative;">
                    <pre id="mix-msg-${item.id}" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; font-family: 'Consolas', 'Fira Code', monospace; font-size: 0.82rem; line-height: 1.6; color: #334155; padding: 1.25rem; white-space: pre-wrap; word-break: break-all; margin: 0; transition: all 0.2s ease-in-out; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">${msgText}</pre>
                    <div class="mix-message-overlay" style="position: absolute; right: 12px; bottom: 12px; font-size: 0.65rem; color: #94a3b8; font-weight: 700; background: rgba(255,255,255,0.85); padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0; pointer-events: none; opacity: 0.8; transition: opacity 0.2s;">
                        Klik untuk menyalin
                    </div>
                </div>
            </div>`;
        }

        return `
        <div class="diff-block" id="diff-${item.id}" style="display: none;">
            <div class="diff-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="18" y1="8" x2="23" y2="13"/><line x1="23" y1="8" x2="18" y2="13"/></svg>
                Detail Request Pemutusan
            </div>
            <div class="diff-rows">
                <div class="diff-row diff-info-row">
                    <div class="diff-label">Status</div>
                    <div class="diff-info-val">
                        <span style="background:#dcfce7;color:#16a34a;padding:2px 8px;border-radius:6px;font-size:0.72rem;font-weight:700">${cust.status || 'Aktif'}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        <span style="background:#fee2e2;color:#dc2626;padding:2px 8px;border-radius:6px;font-size:0.72rem;font-weight:700">Diputus (Soft Delete)</span>
                    </div>
                </div>
                ${p.alasan ? `<div class="diff-row diff-info-row"><div class="diff-label">Alasan</div><div class="diff-info-val">${p.alasan}</div></div>` : ''}
                ${p.foto_perangkat_path  ? `<div class="diff-row diff-info-row"><div class="diff-label">Foto Perangkat</div><div class="diff-info-val"><a href="${p.foto_perangkat_url || '/storage/' + p.foto_perangkat_path}" target="_blank" style="color:#2563eb;font-weight:600">Lihat Foto</a></div></div>` : ''}
                ${p.foto_pembayaran_path ? `<div class="diff-row diff-info-row"><div class="diff-label">Bukti Bayar</div><div class="diff-info-val"><a href="${p.foto_pembayaran_url || '/storage/' + p.foto_pembayaran_path}" target="_blank" style="color:#2563eb;font-weight:600">Lihat Bukti</a></div></div>` : ''}
            </div>
        </div>`;
    }

    if (type === 'pergantian-password') {
        if (targetLevel == 4) {
            const greeting = `Hallo ONC : ${authUserName}`;
            const headerTitle = `Pesan Validasi ONC`;
            const technician = item.submitted_by?.name || 'Customer (Self-service)';
            const tanggal = moment(item.created_at).format('DD MMMM YYYY');
            const custId = cust.uuid || cust.id || '-';
            const typeLayanan = (cust.type?.name || '-').toUpperCase();

            const addressParts = [];
            if (cust.hometown?.name) addressParts.push("Kampung " + cust.hometown.name);
            if (cust.rt?.name) addressParts.push("RT " + cust.rt.name);
            if (cust.rw?.name) addressParts.push("RW " + cust.rw.name);
            if (cust.village?.name) addressParts.push("Desa " + cust.village.name);
            if (cust.district?.name) addressParts.push("Kec. " + cust.district.name);
            if (cust.regencie?.name) addressParts.push("Kab. " + cust.regencie.name);
            const alamatStr = addressParts.join(', ') || '-';

            const wifiNameOld = cust.name_wifi || '-';
            const wifiNameNew = p.name_wifi || '(Tidak diubah / Tetap)';
            const wifiPassOld = cust.password_wifi || '-';
            const wifiPassNew = p.password_wifi || '-';

            const msgText = `${greeting}
Tolong di Baca Data Di bawah ini dengan teliti dan segera Lakukan perubahan data sesuai aturan
pesan di bawah ini.
Pengajuan : Pergantian Password WiFi
Pada Tanggal : ${tanggal}
Hallo Onc
BERIKUT DATA PERUBAHAN PASSWORD PELANGGAN
Data pelanggan sebelum di edit:
.Id Pelanggan : ${custId}
.nama Pelanggan : ${cust.name || '-'}
.alamat pelanggan : ${alamatStr}
.type layanan : ${typeLayanan}
.Nama WiFi Lama : ${wifiNameOld}
.Password WiFi Lama : ${wifiPassOld}

Data pelanggan sesudah di edit:
.Nama WiFi Baru : ${wifiNameNew}
.Password WiFi Baru : ${wifiPassNew}

PERUBAHAN DIATAS DI SUBMIT OLEH CUSTOMER / DILUAR SISTEM.
KAMI TINGGAL MENUNGGU KONFIRMASI TAHAP AKHIR DARI ANDA ( ${authUserName} ) UNTUK
MELAKUKAN PERUBAHAN DATA DIBAGIAN DATA PELANGGAN SECARA OTOMATIS DIBARENGI
DENGAN PENGIRIMKAN NOTIFIKASI SUKSES SECARA OTOMATIS KE WHATSAPP PELANGGAN.
TETAPI SEBELUM MELAKUKAN KLIK KONFIRMASI, PASTIKAN ANDA SUDAH MENYETING PASSWORD TERSEBUT PADA ROUTER ATAU MIKROTIK RADIUS SEHINGGA PELANGGAN DAPAT TERKONEKSI KEMBALI.`;

            return `
            <div class="diff-block" id="diff-${item.id}" style="display: none; padding: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <div style="font-size: 0.68rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.07em; display: flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="8" width="20" height="8" rx="2"/><line x1="6" y1="12" x2="6.01" y2="12"/></svg>
                        ${headerTitle}
                    </div>
                    <button type="button" class="btn-copy-msg" onclick="copyMixMessage(${item.id})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Salin Pesan
                    </button>
                </div>
                <div class="mix-message-container" onclick="copyMixMessage(${item.id})" title="Klik untuk menyalin pesan" style="cursor: pointer; position: relative;">
                    <pre id="mix-msg-${item.id}" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; font-family: 'Consolas', 'Fira Code', monospace; font-size: 0.82rem; line-height: 1.6; color: #334155; padding: 1.25rem; white-space: pre-wrap; word-break: break-all; margin: 0; transition: all 0.2s ease-in-out; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">${msgText}</pre>
                    <div class="mix-message-overlay" style="position: absolute; right: 12px; bottom: 12px; font-size: 0.65rem; color: #94a3b8; font-weight: 700; background: rgba(255,255,255,0.85); padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0; pointer-events: none; opacity: 0.8; transition: opacity 0.2s;">
                        Klik untuk menyalin
                    </div>
                </div>
            </div>`;
        }

        const wifiNameOld = cust.name_wifi || '-';
        const wifiNameNew = p.name_wifi || '(Tidak diubah)';
        const wifiPassOld = cust.password_wifi || '-';
        const wifiPassNew = p.password_wifi || '-';

        return `
        <div class="diff-block" id="diff-${item.id}" style="display: none;">
            <div class="diff-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                Detail Pergantian Password WiFi
            </div>
            <div class="diff-rows">
                <div class="diff-row">
                    <div class="diff-label">Nama WiFi (SSID)</div>
                    <div class="diff-old">&#8722; ${wifiNameOld}</div>
                    <div class="diff-arrow" style="display:flex;align-items:center;justify-content:center;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></div>
                    <div class="diff-new">&#43; ${wifiNameNew}</div>
                </div>
                <div class="diff-row">
                    <div class="diff-label">Password WiFi</div>
                    <div class="diff-old">&#8722; ${wifiPassOld}</div>
                    <div class="diff-arrow" style="display:flex;align-items:center;justify-content:center;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></div>
                    <div class="diff-new" style="font-family:monospace;font-weight:700;">&#43; ${wifiPassNew}</div>
                </div>
            </div>
        </div>`;
    }

    return '';
}

function renderValCard(item, isPending = true) {
    const p             = item.payload || {};
    const cust          = item.customer || {};
    const validations   = item.validations || [];
    const approvedCount = validations.filter(v => v.status === 'approved').length;
    const allApproved   = approvedCount === validations.length && validations.length > 0;
    const hasRejected   = validations.some(v => v.status === 'rejected');
    
    let progressColor = allApproved ? '#16a34a' : hasRejected ? '#ef4444' : '#ea580c';
    let progressText  = allApproved ? 'Semua disetujui' : hasRejected ? 'Ditolak' : `${approvedCount}/${validations.length} disetujui`;

    if (!isPending) {
        if (item.status === 'approved') {
            progressColor = '#16a34a';
            progressText = 'Disetujui & Selesai';
        } else if (item.status === 'rejected') {
            progressColor = '#ef4444';
            progressText = 'Ditolak';
        }
    }

    // Find the active pending step index
    const activeIndex = validations.findIndex(v => v.status === 'pending');

    // Build visual stepper
    const stepperHtml = validations.map((v, i) => {
        const lvl    = LEVELS[v.level] || {};
        const label  = lvl.label || v.level_label || ('L' + v.level);
        const isLast = i === validations.length - 1;
        const byText = v.validated_by_user ? v.validated_by_user.name : null;
        const notes  = v.notes ? `\nCatatan: "${v.notes}"` : '';
        const tip    = `${label}${byText ? ' · ' + byText : ''}${notes}`;
        
        let statusClass = v.status; // 'approved', 'rejected', 'pending'
        if (v.status === 'pending' && i === activeIndex) {
            statusClass = 'active'; // The current active step
        }
        
        const dotIcon = v.status === 'approved'
            ? `<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>`
            : v.status === 'rejected'
            ? `<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`
            : `<span style="font-size:0.68rem;font-weight:800;">${i+1}</span>`;

        let labelHtml = `<div class="ap-label">${label}</div>`;
        if (v.status === 'approved' && byText) {
            labelHtml = `<div class="ap-label" style="line-height: 1.25;">${label}<div style="font-size: 0.65rem; color: #16a34a; font-weight: 700; margin-top: 2px;">${byText}</div></div>`;
        } else if (v.status === 'rejected' && byText) {
            labelHtml = `<div class="ap-label" style="line-height: 1.25;">${label}<div style="font-size: 0.65rem; color: #ef4444; font-weight: 700; margin-top: 2px;">${byText}</div></div>`;
        }

        return `
        <div class="ap-step-wrap line-${v.status}" title="${tip}">
            <div class="ap-dot ${statusClass}">${dotIcon}</div>
            ${labelHtml}
        </div>`;
    }).join('');

    const typeInfo = {
        'pemutusan':          { label: '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="18" y1="8" x2="23" y2="13"/><line x1="23" y1="8" x2="18" y2="13"/></svg> Pemutusan',           cls: 'type-pemutusan'          },
        'pergantian-layanan': { label: '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg> Pergantian Layanan',  cls: 'type-pergantian-layanan' },
        'onu-router':         { label: '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg> Pergantian Perangkat', cls: 'type-onu-router'         },
        'pergantian-password': { label: '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg> Ganti Password WiFi', cls: 'type-pergantian-password' },
    }[item.prosedur_type] || { label: item.prosedur_type, cls: '' };

    const typeBadge = `<span class="req-type-badge badge-${item.prosedur_type}">${typeInfo.label}</span>`;

    const custName = cust.name || '-';
    const initials = custName.split(' ').slice(0,2).map(w => w[0] || '').join('').toUpperCase() || '??';

    const diffHtml  = renderPayloadDiff(item);
    const hasDiff   = diffHtml.trim() !== '';
    const toggleBtn = hasDiff ? `
        <button class="btn-toggle-detail" onclick="toggleDetail(${item.id}, this)">
            <span>Lihat Detail</span>
            <svg class="chevron-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="transition: transform 0.25s;"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
    ` : '';

    let footerContent = '';
    if (isPending) {
        let actionButtons = '';
        let stepButtonHtml = '';
        if (item.user_can_validate) {
            const activeValidation = validations.find(v => v.status === 'pending');
            const isOltActive = activeValidation && activeValidation.level == 2;
            const isMixActive = activeValidation && activeValidation.level == 3;
            const isOncActive = activeValidation && activeValidation.level == 4;
            const isOnuRouter = item.prosedur_type === 'onu-router';

            if (isOnuRouter) {
                if (userLevels.includes(1)) {
                    stepButtonHtml = `
                        <button class="btn-val-steps" onclick="showAdminSteps(${item.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:2px; vertical-align:-1px;"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><line x1="5" y1="6" x2="5.01" y2="6"/><line x1="5" y1="12" x2="5.01" y2="12"/><line x1="5" y1="18" x2="5.01" y2="18"/></svg>
                            Langkah-langkah
                        </button>
                    `;
                } else if (userLevels.includes(2)) {
                    stepButtonHtml = `
                        <button class="btn-val-steps" onclick="showOltSteps(${item.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:2px; vertical-align:-1px;"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><line x1="5" y1="6" x2="5.01" y2="6"/><line x1="5" y1="12" x2="5.01" y2="12"/><line x1="5" y1="18" x2="5.01" y2="18"/></svg>
                            Langkah-langkah
                        </button>
                    `;
                } else if (userLevels.includes(3)) {
                    stepButtonHtml = `
                        <button class="btn-val-steps" onclick="showMixSteps(${item.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:2px; vertical-align:-1px;"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><line x1="5" y1="6" x2="5.01" y2="6"/><line x1="5" y1="12" x2="5.01" y2="12"/><line x1="5" y1="18" x2="5.01" y2="18"/></svg>
                            Langkah-langkah
                        </button>
                    `;
                } else if (userLevels.includes(4)) {
                    stepButtonHtml = `
                        <button class="btn-val-steps" onclick="showOncSteps(${item.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:2px; vertical-align:-1px;"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><line x1="5" y1="6" x2="5.01" y2="6"/><line x1="5" y1="12" x2="5.01" y2="12"/><line x1="5" y1="18" x2="5.01" y2="18"/></svg>
                            Langkah-langkah
                        </button>
                    `;
                }
            } else if (item.prosedur_type === 'pergantian-layanan' && (p.service_type === 'voucher-ke-pppoe' || p.service_type === 'pppoe-ke-voucher')) {
                if (userLevels.includes(3)) {
                    stepButtonHtml = `
                        <button class="btn-val-steps" onclick="showMixLayananSteps(${item.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:2px; vertical-align:-1px;"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><line x1="5" y1="6" x2="5.01" y2="6"/><line x1="5" y1="12" x2="5.01" y2="12"/><line x1="5" y1="18" x2="5.01" y2="18"/></svg>
                            Langkah-langkah
                        </button>
                    `;
                } else if (userLevels.includes(4)) {
                    stepButtonHtml = `
                        <button class="btn-val-steps" onclick="showOncSteps(${item.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:2px; vertical-align:-1px;"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><line x1="5" y1="6" x2="5.01" y2="6"/><line x1="5" y1="12" x2="5.01" y2="12"/><line x1="5" y1="18" x2="5.01" y2="18"/></svg>
                            Langkah-langkah
                        </button>
                    `;
                }
            } else if (item.prosedur_type === 'pemutusan') {
                if (userLevels.includes(2) && isOltActive) {
                    stepButtonHtml = `
                        <button class="btn-val-steps" onclick="showOltSteps(${item.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:2px; vertical-align:-1px;"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><line x1="5" y1="6" x2="5.01" y2="6"/><line x1="5" y1="12" x2="5.01" y2="12"/><line x1="5" y1="18" x2="5.01" y2="18"/></svg>
                            Langkah-langkah
                        </button>
                    `;
                } else if (userLevels.includes(3) && isMixActive) {
                    const typeLayanan = (cust.type?.name || '-').toUpperCase();
                    if (typeLayanan === 'PPPOE') {
                        stepButtonHtml = `
                            <button class="btn-val-steps" onclick="showMixLayananSteps(${item.id})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:2px; vertical-align:-1px;"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><line x1="5" y1="6" x2="5.01" y2="6"/><line x1="5" y1="12" x2="5.01" y2="12"/><line x1="5" y1="18" x2="5.01" y2="18"/></svg>
                                Langkah-langkah
                            </button>
                        `;
                    }
                } else if (userLevels.includes(4) && isOncActive) {
                    stepButtonHtml = `
                        <button class="btn-val-steps" onclick="showOncSteps(${item.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:2px; vertical-align:-1px;"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><line x1="5" y1="6" x2="5.01" y2="6"/><line x1="5" y1="12" x2="5.01" y2="12"/><line x1="5" y1="18" x2="5.01" y2="18"/></svg>
                            Langkah-langkah
                        </button>
                    `;
                }
            }

            actionButtons = `
                <div class="req-actions">
                    ${item.user_can_reject ? `
                    <button class="btn-val-reject" onclick="openValReject(${item.id})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Tolak
                    </button>
                    ` : ''}
                    <button class="btn-approve" onclick="openValApprove(${item.id}, '${(item.customer?.name || '').replace(/'/g, "\\'")}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Setujui
                    </button>
                </div>
            `;
        }

        footerContent = `
        <div class="req-card-footer">
            <div style="display: flex; gap: 8px; align-items: center;">
                ${toggleBtn}
                ${stepButtonHtml}
            </div>
            ${actionButtons}
        </div>`;
    } else {
        // Rekap log info
        let executionInfo = '';
        if (item.status === 'approved') {
            executionInfo = `<span style="font-size:0.76rem;color:#16a34a;font-weight:700;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:3px;vertical-align:-1px;"><polyline points="20 6 9 17 4 12"/></svg> Dieksekusi oleh: ${item.executed_by?.name || '-'} (${moment(item.executed_at).format('DD MMM YYYY, HH:mm')})</span>`;
        } else if (item.status === 'rejected') {
            executionInfo = `<span style="font-size:0.76rem;color:#ef4444;font-weight:700;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:3px;vertical-align:-1px;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Ditolak oleh: ${item.rejected_by?.name || '-'} (${moment(item.rejected_at).format('DD MMM YYYY, HH:mm')})<br><small style="color:#64748b;font-weight:normal;margin-left:15px;">Alasan: "${item.reject_reason || '-'}"</small></span>`;
        }
        footerContent = `
        <div class="req-card-footer">
            <div>${toggleBtn}</div>
            <div style="flex:1;text-align:right;">
                ${executionInfo}
            </div>
        </div>`;
    }

    return `
    <div class="req-card ${typeInfo.cls}" id="req-${item.id}">
        <div class="req-card-header">
            <div class="req-card-avatar" style="background:${progressColor}18;color:${progressColor};">${initials}</div>
            <div class="req-card-info">
                <div class="req-card-name">${custName}</div>
                <div class="req-card-meta">
                    <span style="font-family:monospace;font-weight:700;color:#475569;">${item.customer?.uuid || '-'}</span>
                    <span class="sep">|</span>
                    ${typeBadge}
                    <span class="sep">|</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    ${moment(item.created_at).format('DD MMM YYYY, HH:mm')}
                    <span class="sep">|</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    ${item.submitted_by?.name || '-'}
                    ${item.organization ? `<span class="sep">|</span><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> ${item.organization.name}` : ''}
                </div>
            </div>
            <span style="font-size:0.72rem;font-weight:700;color:${progressColor};background:${progressColor}15;padding:4px 12px;border-radius:20px;flex-shrink:0;letter-spacing:0.02em;">${progressText}</span>
        </div>
        ${diffHtml}
        <div class="approval-progress">${stepperHtml}</div>
        ${footerContent}
    </div>`;
}

function toggleDetail(id, btn) {
    const $diff    = $('#diff-' + id);
    const $btnText = $(btn).find('span');
    const $chevron = $(btn).find('.chevron-icon');
    const $btn     = $(btn);

    if ($diff.is(':visible')) {
        $diff.slideUp(200);
        $btnText.text('Lihat Detail');
        $chevron.css('transform', 'rotate(0deg)');
        $btn.removeClass('active');
    } else {
        $diff.slideDown(200);
        $btnText.text('Sembunyikan Detail');
        $chevron.css('transform', 'rotate(180deg)');
        $btn.addClass('active');
    }
}

// ── APPROVE MODAL ─────────────────────────────────────────────────────────────
function openValApprove(id, name) {
    pendingId = id;
    $('#approve-subtitle').text('Menyetujui request untuk pelanggan: ' + name);
    $('#approve-notes').val('');
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}

$('#btn-do-approve').on('click', function() {
    const btn = $(this);
    btn.prop('disabled', true).html('Memproses...');

    $.ajax({
        url: VAL_BASE + '/' + pendingId + '/approve',
        method: 'PUT',
        data: { _token: CSRF, notes: $('#approve-notes').val() },
        success(res) {
            bootstrap.Modal.getInstance(document.getElementById('approveModal')).hide();
            Toast.fire({ icon: 'success', title: res.message });
            if (activeSpamTab === 'rekap') {
                loadRekapPanel(currentRekapPage);
            } else {
                loadValidationPanel(activeSpamTab);
            }
            loadPillCounts();
        },
        error(xhr) {
            Toast.fire({ icon: 'error', title: xhr.responseJSON?.message || 'Gagal memproses.' });
        },
        complete() {
            btn.prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Ya, Setujui');
        }
    });
});

// ── REJECT MODAL ──────────────────────────────────────────────────────────────
function openValReject(id) {
    pendingId = id;
    $('#reject-reason').val('');
    $('#reject-reason-error').hide();
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

$('#btn-do-reject').on('click', function() {
    const reason = $('#reject-reason').val().trim();
    if (!reason) { $('#reject-reason-error').show(); return; }

    const btn = $(this);
    btn.prop('disabled', true).html('Memproses...');

    $.ajax({
        url: VAL_BASE + '/' + pendingId + '/reject',
        method: 'PUT',
        data: { _token: CSRF, reject_reason: reason },
        success(res) {
            bootstrap.Modal.getInstance(document.getElementById('rejectModal')).hide();
            Toast.fire({ icon: 'success', title: res.message });
            if (activeSpamTab === 'rekap') {
                loadRekapPanel(currentRekapPage);
            } else {
                loadValidationPanel(activeSpamTab);
            }
            loadPillCounts();
        },
        error(xhr) {
            Toast.fire({ icon: 'error', title: xhr.responseJSON?.message || 'Gagal memproses.' });
        },
        complete() {
            btn.prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Tolak Request');
        }
    });
});

// ── DATATABLES (Tab Pemasangan Baru) ──────────────────────────────────────────
let table;

$(function() {
    if (document.getElementById('spam-table')) {
        initializeDataTable();
        initializePaginationAndSearch();
    }
    loadPillCounts();
    if (activeSpamTab !== 'pemasangan') {
        switchSpamTab(activeSpamTab);
    }
});

function loadPillCounts() {
    // Count existing spam (pemasangan)
    $.get(SPAM_BASE, { ajax: 1 }, function() {
        // Will be updated after datatable loads
    });
    // Count pending prosedur for each type
    ['pemutusan', 'pergantian-layanan', 'onu-router', 'pergantian-password'].forEach(type => {
        $.get(VAL_BASE, { tab: 'queue', type: type }, function(res) {
            const count = (res.data || []).length;
            const pill = document.getElementById('pill-' + type);
            if (pill) pill.textContent = count;
        });
    });
    
    @can('lihat rekap prosedur')
        $.get(VAL_BASE, { tab: 'rekap' }, function(res) {
            const count = (res.data || []).length;
            const pill = document.getElementById('pill-rekap');
            if (pill) pill.textContent = count;
        });
    @endcan
}

function initializeDataTable() {
    table = $('#spam-table').DataTable({
        processing: true,
        serverSide: true,
                ajax: {
                    url: SPAM_BASE,
                    data: function(d) {
                        d._token = CSRF;
                        d.organization_id = $('#filter-organization').val();
                    }
                },
                order: [[{{ auth()->user()->hasPermissionTo('filter organization') ? 31 : 29 }}, 'desc']],
        pageLength: 10,
        dom: 'rt',
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'uuid', defaultContent: '-' },
            { data: 'tipe_pelanggan', defaultContent: '-' },
            { data: 'type_name', defaultContent: '-' },
            { data: 'nik', defaultContent: '-' },
            { data: 'name', defaultContent: '-' },
            { data: 'email', defaultContent: '-' },
            { data: 'telp', defaultContent: '-' },
            { data: 'mac_address', defaultContent: '-' },
            { data: 'router_name', defaultContent: '-' },
            { data: 'hometown_name', defaultContent: '-' },
            { data: 'village_name', defaultContent: '-' },
            { data: 'rt_name', defaultContent: '-' },
            { data: 'rw_name', defaultContent: '-' },
            { data: 'district_name', defaultContent: '-' },
            { data: 'regencie_name', defaultContent: '-' },
            { data: 'vlan_name', defaultContent: '-' },
            { data: 'odc_address', defaultContent: '-' },
            { data: 'odp_address', defaultContent: '-' },
            { data: 'olt_address', defaultContent: '-' },
            { data: 'name_wifi', defaultContent: '-' },
            { data: 'password_wifi', defaultContent: '-' },
            { data: 'pppoe_username', defaultContent: '-' },
            { data: 'pppoe_password', defaultContent: '-' },
            { data: 'paket_name', defaultContent: '-' },
            { data: 'mic_radius', defaultContent: '-' },
            { data: 'price_name', defaultContent: '-' },
            { data: 'location', orderable: false, searchable: false },
            { data: 'ktp_photo', orderable: false, searchable: false },
            @if (auth()->user()->hasPermissionTo('filter organization'))
                { data: 'organization_name', defaultContent: '-' },
            @endif
            { data: 'user_name', defaultContent: '-' },
            { data: 'created_at', render: data => moment(data).format('DD/MM/YYYY HH:mm:ss') },
            @if (auth()->user()->can('ubah pelanggan') || auth()->user()->can('hapus pelanggan'))
                { data: 'action', orderable: false, searchable: false }
            @endif
        ],
        drawCallback: function(settings) {
            updatePaginationInfo(settings);
            updateCustomPagination();
            handleEmptyState(settings);

            // Update pill for pemasangan tab
            const info = new $.fn.dataTable.Api(settings).page.info();
            const pill = document.getElementById('pill-pemasangan');
            if (pill) pill.textContent = info.recordsDisplay;
        },
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            emptyTable: '', zeroRecords: ''
        }
    });
}

function initializePaginationAndSearch() {
    $("#sort").on('change', function() { table.page.len($(this).val()).draw(); });
    $("#search-input").on('keypress', function(e) {
        if (e.which === 13) { e.preventDefault(); table.search(this.value).draw(); }
    });
    $("#filter-organization").on('change', function() {
        table.ajax.reload();
    });
}

function updatePaginationInfo(settings) {
    const api = new $.fn.dataTable.Api(settings);
    const info = api.page.info();
    $('#start-entry').text(info.recordsDisplay > 0 ? info.start + 1 : 0);
    $('#end-entry').text(info.end);
    $('#total-entries').text(info.recordsDisplay);
}

function updateCustomPagination() {
    const info = table.page.info();
    const pagination = $('#custom-pagination');
    pagination.empty();
    if (info.pages <= 1) return;

    pagination.append(`<li class="page-item ${info.page === 0 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${info.page - 1}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a></li>`);

    let startPage = Math.max(0, info.page - 2);
    let endPage   = Math.min(info.pages - 1, info.page + 2);

    if (startPage > 0) {
        pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
        if (startPage > 1) pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
    }

    for (let i = startPage; i <= endPage; i++) {
        pagination.append(`<li class="page-item ${i === info.page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i + 1}</a></li>`);
    }

    if (endPage < info.pages - 1) {
        if (endPage < info.pages - 2) pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a></li>`);
    }

    pagination.append(`<li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${info.page + 1}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a></li>`);

    pagination.find('a').on('click', function(e) {
        e.preventDefault();
        if (!$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
            const page = parseInt($(this).data('page'));
            if (!isNaN(page) && page >= 0 && page < info.pages) table.page(page).draw('page');
        }
    });
}

function handleEmptyState(settings) {
    const api  = new $.fn.dataTable.Api(settings);
    const info = api.page.info();

    if (info.recordsDisplay === 0) {
        $('#spam-table thead').hide();
        const isFiltered = $('#search-input').val();
        $('#spam-table tbody .empty-state-row').remove();
        $('#spam-table tbody').html(`
            <tr class="empty-state-row"><td colspan="32" class="text-center py-5">
                <div class="empty-state">
                    <div class="empty-state-icon mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-muted">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"/>
                            <path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2"/><path d="M12 12l0 .01"/>
                            <path d="M3 13a20 20 0 0 0 18 0"/>
                        </svg>
                    </div>
                    <h3 class="empty-state-title text-muted">Tidak Ada Data Pemasangan</h3>
                    <p class="empty-state-subtitle text-muted mb-3">
                        ${isFiltered ? 'Tidak ada hasil pencarian. Coba kata kunci lain.' : 'Tidak ada data pelanggan pemasangan baru saat ini.'}
                    </p>
                    ${isFiltered ? '<button type="button" class="btn btn-primary" id="resetSearchBtn">Hapus Pencarian</button>' : ''}
                </div>
            </td></tr>`);

        $('#resetSearchBtn').on('click', function() {
            $('#search-input').val(''); table.search('').draw();
        });
    } else {
        $('#spam-table thead').show();
        $('#spam-table tbody .empty-state-row').remove();
    }
}

// ── EXISTING SPAM ACTIONS ─────────────────────────────────────────────────────
const Toast2 = Swal.mixin({
    toast: true, position: "top-end", showConfirmButton: false,
    timer: 3000, timerProgressBar: true,
});

function outSpam(id) {
    Swal.fire({
        title: "Info !", text: "Anda yakin ingin memindahkan data ini dari spam?", icon: "warning",
        showCancelButton: true, confirmButtonColor: "#3085d6", cancelButtonColor: "#d33",
        confirmButtonText: "Keluarkan", cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: SPAM_BASE + '/' + id + '/outSpam', method: "PUT",
                data: { _token: CSRF }, dataType: "json",
                success() { Toast2.fire({ icon: 'success', title: 'Berhasil mengeluarkan pelanggan dari spam.' }); table.ajax.reload(); },
                error() { Toast2.fire({ icon: "error", title: "Server Error" }); }
            });
        }
    });
}

function reject(id) {
    Swal.fire({
        title: "Info !", text: "Anda yakin ingin membatalkan data pelanggan ini?", icon: "warning",
        showCancelButton: true, confirmButtonColor: "#3085d6", cancelButtonColor: "#d33",
        confirmButtonText: "Iya", cancelButtonText: "Tidak"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: SPAM_BASE + '/' + id + '/reject', method: "DELETE",
                data: { _token: CSRF }, dataType: "json",
                success() { Toast2.fire({ icon: 'success', title: 'Berhasil membatalkan data customer.' }); table.ajax.reload(); },
                error() { Toast2.fire({ icon: "error", title: "Server Error" }); }
            });
        }
    });
}

// ── ADMIN STEPS MODAL TRIGGER ─────────────────────────────────────────────────
function showAdminSteps(id) {
    new bootstrap.Modal(document.getElementById('adminStepsModal')).show();
}

// ── OLT STEPS MODAL TRIGGER ───────────────────────────────────────────────────
function showOltSteps(id) {
    const item = currentItems.find(x => x.id === id);
    if (!item) return;

    const oltName = item.customer?.olt?.name || 'Tidak diketahui';
    const oltLink = item.customer?.olt?.link;

    if (item.prosedur_type === 'pemutusan') {
        const macOld = (item.customer?.mac_address || '-').toUpperCase();
        let formattedLink = '(Link tidak tersedia)';
        if (oltLink) {
            let linkUrl = oltLink;
            if (!/^https?:\/\//i.test(oltLink)) {
                linkUrl = 'http://' + oltLink;
            }
            formattedLink = `<a href="${linkUrl}" target="_blank" class="text-primary fw-bold" style="text-decoration: underline;">${oltLink}</a>`;
        }
        $('.olt-steps-body').html(`
            <ol style="margin: 0; padding-left: 20px;">
                <li class="mb-2">
                    Masuk ke OLT <strong class="text-dark">${oltName}</strong>, berikut linknya: ${formattedLink}
                </li>
                <li class="mb-2">
                    Masukan username & password OLT Anda.
                </li>
                <li class="mb-2">
                    Cari MAC Address lama (<strong class="text-danger" style="font-family: monospace;">${macOld}</strong>) di data OLT <strong class="text-dark">${oltName}</strong>.
                </li>
                <li class="mb-2">
                    Hapus data MAC Address lama (<strong class="text-danger" style="font-family: monospace;">${macOld}</strong>).
                </li>
                <li class="mb-2">
                    Setelah melakukan langkah 1-4, segera lakukan validasi data pengajuan di bawah ini.
                </li>
            </ol>
        `);
    } else {
        const macOld = (item.payload?.mac_address_old || 'Tidak diketahui').toUpperCase();
        const macNew = (item.payload?.mac_address_new || 'Tidak diketahui').toUpperCase();
        let formattedLink = '(Link tidak tersedia)';
        if (oltLink) {
            let linkUrl = oltLink;
            if (!/^https?:\/\//i.test(oltLink)) {
                linkUrl = 'http://' + oltLink;
            }
            formattedLink = `<a href="${linkUrl}" target="_blank" class="text-primary fw-bold" style="text-decoration: underline;">${oltLink}</a>`;
        }
        $('.olt-steps-body').html(`
            <ol style="margin: 0; padding-left: 20px;">
                <li class="mb-2">
                    Masuk ke OLT <strong class="text-dark">${oltName}</strong>, berikut linknya: ${formattedLink}
                </li>
                <li class="mb-2">
                    Masukan username & password OLT Anda.
                </li>
                <li class="mb-2">
                    Cari MAC Address lama (<strong class="text-danger" style="font-family: monospace;">${macOld}</strong>) di data OLT <strong class="text-dark">${oltName}</strong>.
                </li>
                <li class="mb-2">
                    Hapus data MAC Address lama (<strong class="text-danger" style="font-family: monospace;">${macOld}</strong>).
                </li>
                <li class="mb-2">
                    Cek & cari di data OLT MAC Address baru (<strong class="text-success" style="font-family: monospace;">${macNew}</strong>), ada atau tidak ada?
                </li>
                <li class="mb-2">
                    Kalo tidak ada, lakukan input MAC Address secara manual.
                </li>
                <li class="mb-2">
                    Setelah melakukan langkah 1-6, segera lakukan validasi data pengajuan di bawah ini.
                </li>
            </ol>
        `);
    }

    new bootstrap.Modal(document.getElementById('oltStepsModal')).show();
}

// ── MIX RADIUS STEPS MODAL TRIGGER ────────────────────────────────────────────
function showMixSteps(id) {
    const item = currentItems.find(x => x.id === id);
    if (!item) return;

    const mixName = item.customer?.mic_radius?.name || 'Tidak diketahui';
    const custId = item.customer?.uuid || item.customer?.id || 'Tidak diketahui';

    $('.step-mix-name').text(mixName);
    $('.step-mix-cust-id').text(custId);
    $('.step-mix-cust-id-2').text(custId);

    new bootstrap.Modal(document.getElementById('mixStepsModal')).show();
}

// ── MIX RADIUS LAYANAN STEPS MODAL TRIGGER ─────────────────────────────────────
function showMixLayananSteps(id) {
    const item = currentItems.find(x => x.id === id);
    if (!item) return;

    const p = item.payload || {};
    const cust = item.customer || {};
    const custId = cust.uuid || cust.id || '-';
    const mixName = p.mic_radius_name || cust.mic_radius?.name || 'Tidak diketahui';

    if (item.prosedur_type === 'pemutusan') {
        $('.step-mix-layanan-title').text('Langkah-langkah Validasi Mix Radius (Pemutusan)');
        $('.mix-layanan-steps-body').html(`
            <ol style="margin: 0; padding-left: 20px;">
                <li class="mb-2">
                    Masuk ke web akun mixradius (<strong class="text-dark">${mixName}</strong>) berikut linknya: <a href="https://mixcio.topsetting.com:973/" target="_blank" class="text-primary fw-bold" style="text-decoration: underline;">https://mixcio.topsetting.com:973/</a>
                </li>
                <li class="mb-2">
                    Masukan username & password mixradius anda.
                </li>
                <li class="mb-2">
                    Setelah login cari ke menu <strong>Pelanggan</strong> kemudian klik menu <strong>user pppoe</strong>.
                </li>
                <li class="mb-2">
                    Cari Id Pelanggan (<strong class="text-dark">${custId}</strong>) di kolom pencarian user ppp.
                </li>
                <li class="mb-2">
                    Setelah ketemu kemudian klik tombol <strong>hapus</strong>.
                </li>
                <li class="mb-2">
                    Setelah melakukan langkah 1-5 segera lakukan validasi data pengajuan di bawah ini.
                </li>
            </ol>
        `);
    } else if (p.service_type === 'voucher-ke-pppoe') {
        $('.step-mix-layanan-title').text('Langkah-langkah Validasi Mix Radius (Voucher ke PPPoE)');
        $('.mix-layanan-steps-body').html(`
            <ol style="margin: 0; padding-left: 20px;">
                <li class="mb-2">
                    Masuk ke web akun mixradius (<strong class="text-dark">${mixName}</strong>) berikut linknya: <a href="https://mixcio.topsetting.com:973/" target="_blank" class="text-primary fw-bold" style="text-decoration: underline;">https://mixcio.topsetting.com:973/</a>
                </li>
                <li class="mb-2">
                    Masukan username & password mixradius anda.
                </li>
                <li class="mb-2">
                    Setelah login cari ke menu <strong>Pelanggan</strong> kemudian klik menu <strong>user pppoe</strong>.
                </li>
                <li class="mb-2">
                    Tambah Pelanggan PPPOE.
                </li>
                <li class="mb-2">
                    Kemudian isi data PPPOE sesuai Pesan Di atas di bagian tujuan pengajuan isi dengan teliti.
                </li>
                <li class="mb-2">
                    Setelah data sama kemudian klik <strong>Tambah Pelanggan</strong>, cara tersebut sama halnya dengan pemasangan baru pelanggan pppoe.
                </li>
                <li class="mb-2">
                    Setelah melakukan langkah 1-6 segera lakukan validasi data pengajuan di bawah ini.
                </li>
            </ol>
        `);
    } else if (p.service_type === 'pppoe-ke-voucher') {
        $('.step-mix-layanan-title').text('Langkah-langkah Validasi Mix Radius (PPPoE ke Voucher)');
        $('.mix-layanan-steps-body').html(`
            <ol style="margin: 0; padding-left: 20px;">
                <li class="mb-2">
                    Masuk ke web akun mixradius (<strong class="text-dark">${mixName}</strong>) berikut linknya: <a href="https://mixcio.topsetting.com:973/" target="_blank" class="text-primary fw-bold" style="text-decoration: underline;">https://mixcio.topsetting.com:973/</a>
                </li>
                <li class="mb-2">
                    Masukan username & password mixradius anda.
                </li>
                <li class="mb-2">
                    Setelah login cari ke menu <strong>Pelanggan</strong> kemudian klik menu <strong>user pppoe</strong>.
                </li>
                <li class="mb-2">
                    Cari Id Pelanggan (<strong class="text-dark">${custId}</strong>) di kolom pencarian user ppp.
                </li>
                <li class="mb-2">
                    Setelah ketemu kemudian klik tombol <strong>hapus</strong>.
                </li>
                <li class="mb-2">
                    Setelah melakukan langkah 1-5 segera lakukan validasi data pengajuan di bawah ini.
                </li>
            </ol>
        `);
    }

    new bootstrap.Modal(document.getElementById('mixLayananStepsModal')).show();
}

// ── ONC STEPS MODAL TRIGGER ───────────────────────────────────────────────────
function showOncSteps(id) {
    const item = currentItems.find(x => x.id === id);
    if (!item) return;

    const p = item.payload || {};
    const cust = item.customer || {};
    const techName = (item.submitted_by || item.submittedBy)?.name || '-';
    const techTelp = (item.submitted_by || item.submittedBy)?.telp || '-';
    const custId = cust.uuid || cust.id || '-';

    if (item.prosedur_type === 'pemutusan') {
        const typeLayanan = (cust.type?.name || '-').toUpperCase();
        const addressParts = [];
        if (cust.hometown?.name) addressParts.push("Kampung " + cust.hometown.name);
        if (cust.rt?.name) addressParts.push("RT " + cust.rt.name);
        if (cust.rw?.name) addressParts.push("RW " + cust.rw.name);
        if (cust.village?.name) addressParts.push("Desa " + cust.village.name);
        if (cust.district?.name) addressParts.push("Kec. " + cust.district.name);
        if (cust.regencie?.name) addressParts.push("Kab. " + cust.regencie.name);
        const alamatStr = addressParts.join(', ') || '-';

        $('.step-onc-title').text('Langkah-langkah Validasi ONC (Pemutusan)');
        $('.onc-steps-body').html(`
            <p class="mb-2"><strong>BERIKUT DATA PENGAJUAN PEMUTUSAN LANGGANAN:</strong></p>
            <table class="table table-sm table-bordered mb-3" style="font-size: 0.85rem;">
                <tr><td style="width: 30%; font-weight: 600;">ID Pelanggan</td><td>${custId}</td></tr>
                <tr><td style="font-weight: 600;">Nama Pelanggan</td><td>${cust.name || '-'}</td></tr>
                <tr><td style="font-weight: 600;">Alamat</td><td>${alamatStr}</td></tr>
                <tr><td style="font-weight: 600;">Type Layanan</td><td>${typeLayanan}</td></tr>
            </table>
            <p class="mb-2">
                PENGHAPUSAN DATA PELANGGAN DIATAS SUDAH DI VALIDASI OLEH SEMUA BAGIANNYA MASING MASING.
            </p>
            <p class="mb-2">
                KAMI TINGGAL MENUNGGU KONFIRMASI TAHAP AKHIR DARI ANDA (<strong class="text-dark">${authUserName}</strong>) UNTUK MELAKUKAN PENGHAPUSAN DATA DIBAGIAN DATA PELANGGAN SECARA OTOMATIS DIBARENGI DENGAN PENGIRIMKAN NOTIFIKASI BERHENTI BERLANGGANAN SUKSES SECARA OTOMATIS KE TEKNISI (<strong class="text-dark">${techName}</strong>).
            </p>
            <p class="mb-2 text-danger" style="font-weight: 700;">
                TETAPI SEBELUM MELAKUKAN KLIK KONFIRMASI PENGHAPUSAN DATA PERMANENT, SEBELUMNYA ANDA HARUS MEMASTIKAN BAHWA ANDA SUDAH MENGECEK MENYETING ROUTER TERSEBUT MENJADI SETELAN AWAL KEMBALI (SETELAN KANTOR).
            </p>
            <p class="mb-2">
                DISKUSIKAN DENGAN TEKNISI PENGAJUAN:<br>
                NAMA TEKNISI: <strong class="text-dark">${techName}</strong><br>
                NO WA: <strong class="text-primary">${techTelp}</strong>
            </p>
            <p class="mb-0">
                SETELAH PENYETINGAN SELESAI, ANDA BISA MENYELESAIKANYA DENGAN MENGKLIK KONFIRMASI PENGHAPUSAN DATA (SUPAYA ID PELANGGAN <strong class="text-danger" style="font-family: monospace;">${custId}</strong> DI HAPUS PERMANEN SECARA OTOMATIS DI BAGIAN DATA PELANGGAN).
            </p>
        `);
    } else {
        const macNew = (p.mac_address_new || cust.mac_address || '-').toUpperCase();
        const routerNew = p.router_new_name
            ? `${p.router_new_name}${p.router_new_code ? ' (' + p.router_new_code + ')' : ''}`
            : (p.router_new_id ? `ID: ${p.router_new_id}` : (cust.router ? cust.router.name : '-'));

        $('.step-onc-title').text('Langkah-langkah Validasi ONC');
        $('.onc-steps-body').html(`
            <p style="margin-bottom: 1rem;">
                PERUBAHAN DIATAS SUDAH DI VALIDASI OLEH SEMUA BAGIANNYA MASING MASING.
            </p>
            <p style="margin-bottom: 1rem;">
                KAMI TINGGAL MENUNGGU KONFIRSIMASI TAHAP AKHIR DARI ANDA (<strong class="text-dark">${authUserName}</strong>) UNTUK MELAKUKAN PERUBAHAN DATA DIBAGIAN DATA PELANGGAN SECARA OTOMATIS DIBARENGI DENGAN PENGIRIMKAN NOTIFIKASI SUKSES SECARA OTOMATIS KE TEKNISI (<strong class="text-dark">${techName}</strong>).
            </p>
            <p style="margin-bottom: 1rem;">
                TETAPI SEBELUM MELAKUKAN KLIK KONFIRMASI, SEBELUMNYA ANDA HARUS MEMASTIKAN BAHWA ANDA SUDAH MENGECEK MENYETING KEMBALI ROUTER TYPE (<strong class="text-success">${routerNew}</strong>), DENGAN MAC ADDRESS BARU (<strong class="text-success" style="font-family: monospace;">${macNew}</strong>) DAN SUDAH DALAM KEADAAN TERKONEKSI.
            </p>
            <p style="margin-bottom: 1rem;">
                UNTUK MEMASTIKAN SEBAIKNYA ANDA BEKERJA SAMA DENGAN MENELPON TEKNISI PENGAJUAN PERUBAHAN DATA TERSEBUT:<br>
                NAMA TEKNISI: <strong class="text-dark">${techName}</strong><br>
                NO WA: <strong class="text-primary">${techTelp}</strong>
            </p>
            <p style="margin: 0;">
                SETELAH SEMUANYA BERJALAN SUKSES TERKONEKSI, KEMUDIAN ANDA BISA MENYELESAIKANNYA DENGAN MENGKLIK KONFIRMASI (SUPAYA ID PELANGGAN <strong class="text-danger" style="font-family: monospace;">${custId}</strong> BERUBAH SECARA OTOMATIS DI BAGIAN DATA PELANGGAN).
            </p>
        `);
    }

    new bootstrap.Modal(document.getElementById('oncStepsModal')).show();
}

// ── COPY MIX RADIUS MESSAGE ───────────────────────────────────────────────────
function copyMixMessage(id) {
    const evt = window.event;
    if (evt && (evt.target.tagName === 'A' || evt.target.closest('a'))) {
        return;
    }
    const preElement = document.getElementById('mix-msg-' + id);
    if (!preElement) return;

    const textToCopy = preElement.textContent || preElement.innerText;

    try {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(textToCopy).then(() => {
                Toast.fire({ icon: 'success', title: 'Pesan berhasil disalin!' });
            }).catch(err => {
                fallbackCopyText(textToCopy);
            });
        } else {
            fallbackCopyText(textToCopy);
        }
    } catch (err) {
        Toast.fire({ icon: 'error', title: 'Gagal menyalin pesan.' });
    }
}

function fallbackCopyText(text) {
    const tempTextArea = document.createElement('textarea');
    tempTextArea.value = text;
    tempTextArea.style.position = 'fixed';
    document.body.appendChild(tempTextArea);
    tempTextArea.select();
    try {
        document.execCommand('copy');
        Toast.fire({ icon: 'success', title: 'Pesan berhasil disalin!' });
    } catch (err) {
        Toast.fire({ icon: 'error', title: 'Gagal menyalin pesan.' });
    }
    document.body.removeChild(tempTextArea);
}
</script>
@endpush

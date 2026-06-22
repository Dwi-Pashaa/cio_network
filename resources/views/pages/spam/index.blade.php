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
                    <div class="search-wrapper ms-auto">
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
             TAB 5: REKAP HISTORIS
        ══════════════════════════════════════════════════════ --}}
        @can('lihat rekap prosedur')
            <div class="spam-panel" id="panel-rekap">
                <div class="level-legend">
                    <span style="font-size: 0.72rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.07em;">Rekap Hasil Validasi Prosedur</span>
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
@endsection

@push('js')
<script>
// ── CONSTANTS ─────────────────────────────────────────────────────────────────
const SPAM_BASE = "{{ route('spam.index') }}";
const VAL_BASE  = "{{ route('validasi.prosedur.index') }}";
const CSRF      = $('meta[name="csrf-token"]').attr('content');
const LEVELS    = @json(config('prosedur_levels.levels', []));
let activeSpamTab = document.getElementById('nav-pemasangan') ? 'pemasangan' : 'pemutusan';
let pendingId     = null;

// ── TOAST ─────────────────────────────────────────────────────────────────────
const Toast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false,
    timer: 3000, timerProgressBar: true,
});

// ── TAB SWITCH ────────────────────────────────────────────────────────────────
const PROSEDUR_TABS = ['pemasangan', 'pemutusan', 'pergantian-layanan', 'onu-router', 'rekap'];

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

    $.get(VAL_BASE, { tab: 'queue', type: type }, function(res) {
        if (loadingEl) loadingEl.style.display = 'none';
        const items = res.data || [];

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

    $.get(VAL_BASE, { tab: 'rekap', page: page }, function(res) {
        if (loadingEl) loadingEl.style.display = 'none';
        const items = res.data || [];
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

    if (type === 'onu-router') {
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

    return '';
}

function renderValCard(item, isPending = true) {
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
    }[item.prosedur_type] || { label: item.prosedur_type, cls: '' };

    const typeBadge = `<span class="req-type-badge badge-${item.prosedur_type}">${typeInfo.label}</span>`;

    const custName = item.customer?.name || '-';
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
        if (item.user_can_validate) {
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
            <div>${toggleBtn}</div>
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
    ['pemutusan', 'pergantian-layanan', 'onu-router'].forEach(type => {
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
            }
        },
        order: [[29, 'desc']],
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
</script>
@endpush

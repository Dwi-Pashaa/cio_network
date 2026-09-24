@extends('layouts.app')

@section('title')
    Data Pelanggan
@endsection

@push('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <!-- Select2 CSS & Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .btn-action.btn-chat:hover { color: #0ea5e9; border-color: #0ea5e9; background: #f0f9ff; }
        .btn-action.btn-open-ticket:hover { color: #b45309 !important; border-color: #f59e0b !important; background: #fef3c7 !important; transform: scale(1.08); box-shadow: 0 2px 8px rgba(245, 158, 11, 0.25); }

        /* Filter Panel Styles */
        .filter-panel-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        .filter-group-header {
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            margin-bottom: 0.55rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .filter-group-header svg {
            stroke: #3b82f6;
        }
        .filter-label {
            font-size: 0.77rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 0.25rem;
            display: block;
        }
        .select2-container--bootstrap-5 .select2-selection {
            border-color: #cbd5e1;
            border-radius: 8px;
            font-size: 0.82rem;
            min-height: 38px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .select2-container--bootstrap-5 .select2-selection--single {
            padding-top: 5px;
            padding-bottom: 5px;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: #1e293b;
            font-weight: 500;
            padding-left: 0.5rem;
            padding-right: 1.5rem;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8;
        }
        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .select2-dropdown {
            border: 1px solid #cbd5e1;
            border-radius: 8px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            font-size: 0.83rem;
            z-index: 1055;
        }
        .select2-container--bootstrap-5 .select2-dropdown .select2-search .select2-search__field {
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            padding: 6px 10px;
            font-size: 0.82rem;
        }
        .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear {
            right: 2rem;
            color: #94a3b8;
            font-size: 1.1rem;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear:hover {
            color: #ef4444;
        }
        .btn-reset-filters {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.45rem 0.85rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
            height: 38px;
        }
        .btn-reset-filters:hover {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fca5a5;
        }

        @media (min-width: 992px) {
            .col-lg-5th {
                flex: 0 0 auto;
                width: 20%;
            }
        }

        /* ==================== Wizard Modal Styles ==================== */
        .wizard-steps-container {
            position: relative;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 1.25rem 1.5rem;
            border-radius: 12px 12px 0 0;
            color: #fff;
        }
        .wizard-steps-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            margin-top: 0.75rem;
        }
        .wizard-steps-line {
            position: absolute;
            top: 20px;
            left: 12%;
            right: 12%;
            height: 3px;
            background: rgba(255,255,255,0.15);
            z-index: 1;
        }
        .wizard-steps-line-fill {
            position: absolute;
            top: 20px;
            left: 12%;
            height: 3px;
            background: linear-gradient(90deg, #10b981, #3b82f6);
            transition: width 0.4s ease;
            z-index: 2;
        }
        .wizard-step-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 3;
            cursor: pointer;
            flex: 1;
        }
        .wizard-step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #334155;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            border: 2px solid rgba(255,255,255,0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .wizard-step-node.active .wizard-step-circle {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            border-color: #60a5fa;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.6);
            transform: scale(1.1);
        }
        .wizard-step-node.completed .wizard-step-circle {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border-color: #34d399;
        }
        .wizard-step-title {
            margin-top: 0.4rem;
            font-size: 0.78rem;
            font-weight: 600;
            color: #94a3b8;
            transition: color 0.3s;
            text-align: center;
        }
        .wizard-step-node.active .wizard-step-title {
            color: #ffffff;
            font-weight: 700;
        }
        .wizard-step-node.completed .wizard-step-title {
            color: #34d399;
        }

        /* Tech Select Card */
        .tech-card-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.9rem 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.9rem;
            margin-bottom: 0.65rem;
        }
        .tech-card-select:hover {
            border-color: #93c5fd;
            background: #f8fafc;
            transform: translateY(-1px);
        }
        .tech-card-select.selected {
            border-color: #3b82f6;
            background: #eff6ff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
        .tech-card-avatar {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .tech-card-select.selected .tech-card-avatar {
            background: linear-gradient(135deg, #2563eb, #1e40af);
        }

        /* Info Item */
        .wizard-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 0.85rem;
        }
        .wizard-info-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 0.9rem;
        }
        .wizard-info-item .info-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }
        .wizard-info-item .info-value {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1e293b;
        }

        /* Refresh Live Data Button */
        .btn-refresh-live {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 0.95rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #ffffff !important;
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg, #0284c7, #0369a1);
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(2, 132, 199, 0.2);
        }
        .btn-refresh-live:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(2, 132, 199, 0.35);
            background: linear-gradient(135deg, #0369a1, #075985);
        }
        .btn-refresh-live.loading .refresh-icon {
            animation: spinCustomerRefresh 0.85s linear infinite;
        }
        @keyframes spinCustomerRefresh {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Styling Baris Berdasarkan Status MikroTik */
        table.org-table tbody tr.row-mikrotik-waiting > td {
            background-color: #fefce8 !important;
        }
        table.org-table tbody tr.row-mikrotik-waiting:hover > td {
            background-color: #fef08a !important;
        }
        table.org-table tbody tr.row-mikrotik-waiting > td:first-child {
            border-left: 4px solid #eab308 !important;
        }

        table.org-table tbody tr.row-mikrotik-offline > td {
            background-color: #fef2f2 !important;
            color: #334155;
        }
        table.org-table tbody tr.row-mikrotik-offline:hover > td {
            background-color: #fee2e2 !important;
        }
        table.org-table tbody tr.row-mikrotik-offline > td:first-child {
            border-left: 4px solid #f87171 !important;
        }

        table.org-table tbody tr.row-mikrotik-offered > td {
            background-color: #fff7ed !important;
        }
        table.org-table tbody tr.row-mikrotik-offered:hover > td {
            background-color: #ffedd5 !important;
        }
        table.org-table tbody tr.row-mikrotik-offered > td:first-child {
            border-left: 4px solid #f97316 !important;
        }

        table.org-table tbody tr.row-mikrotik-bound > td {
            background-color: #ffffff;
        }
        table.org-table tbody tr.row-mikrotik-bound:hover > td {
            background-color: #f0fdf4 !important;
        }
        table.org-table tbody tr.row-mikrotik-bound > td:first-child {
            border-left: 4px solid #22c55e !important;
        }

        /* ==================== Live Stream Loading Bar & Pulse Animation ==================== */
        .org-card {
            position: relative;
        }

        .table-live-stream-bar {
            position: relative;
            width: 100%;
            height: 4px;
            background: rgba(226, 232, 240, 0.7);
            overflow: hidden;
            margin-top: 4px;
            margin-bottom: 2px;
            border-radius: 4px;
            z-index: 5;
        }

        .table-live-stream-bar .stream-bar-progress {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #0284c7 0%, #10b981 50%, #0054a6 100%);
            border-radius: 4px;
            opacity: 0;
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.7);
        }

        .table-live-stream-bar.animating .stream-bar-progress {
            opacity: 1;
            animation: streamBarSwipe 1.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        @keyframes streamBarSwipe {
            0% {
                width: 0%;
                left: 0%;
                opacity: 0.9;
            }
            50% {
                width: 70%;
                left: 20%;
                opacity: 1;
            }
            100% {
                width: 100%;
                left: 0%;
                opacity: 0;
            }
        }

        @keyframes rowPulse {
            0% {
                background-color: rgba(16, 185, 129, 0.35) !important;
            }
            40% {
                background-color: rgba(16, 185, 129, 0.18) !important;
            }
            100% {
                background-color: transparent;
            }
        }

        .row-updated-flash > td {
            animation: rowPulse 2.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .live-sync-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.2rem 0.65rem;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 9999px;
            font-size: 0.76rem;
            font-weight: 600;
            color: #059669;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            position: relative;
            display: inline-block;
        }

        .pulse-dot::after {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            width: 14px;
            height: 14px;
            background: rgba(16, 185, 129, 0.4);
            border-radius: 50%;
            animation: radar-pulse 1.8s infinite ease-out;
        }

        @keyframes radar-pulse {
            0% {
                transform: scale(0.6);
                opacity: 1;
            }
            100% {
                transform: scale(2.2);
                opacity: 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="org-container mt-4">
        <div class="org-card">
            <div class="org-header">
                <div class="org-title-wrap">
                    <div class="org-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                        </svg>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h2 class="org-title mb-0">Data Pelanggan</h2>
                            <span class="live-sync-badge" id="cust-live-sync-status-badge" title="Pembaruan status perangkat MikroTik otomatis setiap 5 menit">
                                <span class="pulse-dot"></span>
                                <span>Auto Sync (5 Menit)</span>
                                <span class="live-sync-timer" id="cust-live-sync-timer" style="font-size: 0.74rem; font-weight: 500; color: #059669; opacity: 0.88; margin-left: 2px;">(Baru saja)</span>
                            </span>
                        </div>
                        <p class="org-subtitle mb-0">Kelola dan pantau seluruh data pelanggan aktif.</p>
                    </div>
                </div>

                <div class="org-header-action d-flex gap-2 flex-wrap align-items-center">
                    <button type="button" class="btn-refresh-live" id="btn-refresh-customer" title="Tarik pembaruan data MikroTik seketika">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"
                            stroke-linejoin="round" class="refresh-icon">
                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                        </svg>
                        <span id="text-refresh-customer">Refresh Live Data</span>
                    </button>
                    @can('buat pelanggan')
                        <a href="{{ route('customer.create') }}" class="btn-add">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="14.5" y2="12"></line>
                            </svg>
                            Tambah Pelanggan
                        </a>
                        @can('download excel')
                            <a href="{{ route('customer.export') }}" class="btn-add"
                                style="background: linear-gradient(135deg,#16a34a,#15803d);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    <path d="M8 11h8v7h-8z" />
                                    <path d="M8 15h8" />
                                    <path d="M11 11v7" />
                                </svg>
                                Excel
                            </a>
                        @endcan
                        <a href="javascript:void(0)" class="btn-add"
                            style="background: linear-gradient(135deg,#0ea5e9,#0284c7);" onclick="return openSwitch()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M20 10h-16l5.5 -6" />
                                <path d="M4 14h16l-5.5 6" />
                            </svg>
                            Pindah OLT
                        </a>
                    @endcan
                </div>
            </div>

            {{-- Filter Panel --}}
            <div class="filter-panel-card">
                {{-- Group 1: Filter Wilayah & Status MikroTik (Terbuka untuk Semua) --}}
                <div class="mb-3">
                    <div class="filter-group-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Filter Wilayah Administratif & Status MikroTik
                    </div>
                    <div class="row g-2">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                            <label class="filter-label" for="regencie">1. Kabupaten / Kota</label>
                            <select name="regencie" id="regencie" class="filter-select form-select" data-placeholder="Semua Kabupaten/Kota">
                                <option value="">Semua Kabupaten/Kota</option>
                                @foreach ($regencies as $rgc)
                                    <option value="{{ $rgc->id }}">{{ $rgc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                            <label class="filter-label" for="district">2. Kecamatan</label>
                            <select name="district" id="district" class="filter-select form-select" data-placeholder="Semua Kecamatan">
                                <option value="">Semua Kecamatan</option>
                                @foreach ($districts as $dsc)
                                    <option value="{{ $dsc->id }}" data-regency="{{ $dsc->regencie_id }}">{{ $dsc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                            <label class="filter-label" for="hometown">3. Kampung</label>
                            <select name="hometown" id="hometown" class="filter-select form-select" data-placeholder="Semua Kampung">
                                <option value="">Semua Kampung</option>
                                @foreach ($hometown as $hmt)
                                    <option value="{{ $hmt->id }}" data-regency="{{ $hmt->regencie_id }}" data-district="{{ $hmt->district_id }}">{{ $hmt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                            <label class="filter-label" for="village">4. Desa</label>
                            <select name="village" id="village" class="filter-select form-select" data-placeholder="Semua Desa">
                                <option value="">Semua Desa</option>
                                @foreach ($vilage as $vlg)
                                    <option value="{{ $vlg->id }}" data-regency="{{ $vlg->regencie_id }}" data-district="{{ $vlg->district_id }}">{{ $vlg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                            <label class="filter-label" for="mikrotik_status">5. Status MikroTik</label>
                            <select name="mikrotik_status" id="mikrotik_status" class="filter-select form-select" data-placeholder="Semua Status MikroTik">
                                <option value="">Semua Status MikroTik</option>
                                <option value="bound">🟢 Bound (Aktif)</option>
                                <option value="waiting">🟡 Waiting (Menunggu)</option>
                                <option value="offered">🟠 Offered</option>
                                <option value="offline">🔴 Offline / Belum Terdeteksi</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Group 2: Filter Layanan & Jaringan (Khusus Permission 'filter pelanggan') --}}
                @can('filter pelanggan')
                <div class="mb-3 pt-2 border-top">
                    <div class="filter-group-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
                        Filter Tambahan (Layanan & Jaringan)
                    </div>
                    <div class="row g-2">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                            <label class="filter-label" for="type_id">Tipe Layanan</label>
                            <select name="type_id" id="type_id" class="filter-select form-select" data-placeholder="Semua Tipe Layanan">
                                <option value="">Semua Tipe Layanan</option>
                                @foreach ($serviceTypes as $tp)
                                    <option value="{{ $tp->id }}">{{ $tp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                            <label class="filter-label" for="tipe_pelanggan_id">Tipe Pelanggan</label>
                            <select name="tipe_pelanggan_id" id="tipe_pelanggan_id" class="filter-select form-select" data-placeholder="Semua Tipe Pelanggan">
                                <option value="">Semua Tipe Pelanggan</option>
                                @foreach ($customerTypes as $tp)
                                    <option value="{{ $tp->id }}">{{ $tp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                            <label class="filter-label" for="vlan">Vlan</label>
                            <select name="vlan" id="vlan" class="filter-select form-select" data-placeholder="Semua Vlan">
                                <option value="">Semua Vlan</option>
                                @foreach ($vlan as $vln)
                                    <option value="{{ $vln->id }}">{{ $vln->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                            <label class="filter-label" for="olt">OLT</label>
                            <select name="olt" id="olt" class="filter-select form-select" data-placeholder="Semua OLT">
                                <option value="">Semua OLT</option>
                                @foreach ($olts as $ol)
                                    <option value="{{ $ol->id }}">{{ $ol->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-5th">
                            <label class="filter-label" for="micradius">Mic Radius</label>
                            <select name="micradius" id="micradius" class="filter-select form-select" data-placeholder="Semua Mic Radius">
                                <option value="">Semua Mic Radius</option>
                                @foreach ($micRadius as $mc)
                                    <option value="{{ $mc->id }}">{{ $mc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endcan

                {{-- Toolbar Bawah: Extra Filters (Verif/Org) + Reset Button + Sort + Search --}}
                <div class="pt-2 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex flex-wrap align-items-center gap-2 flex-grow-1">
                        @can('filter pelanggan')
                            @can('verifikasi email')
                                <div style="min-width: 150px;">
                                    <select name="email_verify" id="email_verify" class="filter-select form-select" data-placeholder="Semua Verif Email">
                                        <option value="">Semua Verif Email</option>
                                        <option value="register">Terdaftar</option>
                                        <option value="not_register">Tidak Terdaftar</option>
                                        <option value="belum_dicek">Belum Dicek</option>
                                    </select>
                                </div>
                            @endcan

                            @can('verifikasi whatsapp')
                                <div style="min-width: 150px;">
                                    <select name="wa_verify" id="wa_verify" class="filter-select form-select" data-placeholder="Semua Verif WA">
                                        <option value="">Semua Verif WA</option>
                                        <option value="registered">Terdaftar</option>
                                        <option value="not_registered">Tidak Terdaftar</option>
                                        <option value="belum_dicek">Belum Dicek</option>
                                    </select>
                                </div>
                            @endcan

                            @if (optional(auth()->user()->organization)->type !== 'mitra')
                                <div style="min-width: 180px;">
                                    <select name="organization_id" id="organization_id" class="filter-select form-select" data-placeholder="Semua Organisasi/Mitra">
                                        <option value="">Semua Organisasi/Mitra</option>
                                        @foreach ($organizations as $org)
                                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        @endcan

                        <button type="button" class="btn-reset-filters" id="btn-reset-filters" title="Reset Semua Filter ke Awal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                            Reset Filter
                        </button>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center gap-1">
                            <select name="sort" id="sort" class="org-input" style="width: 75px; height: 38px; border-radius: 8px;">
                                @php $opts = [10, 25, 50, 100]; @endphp
                                @foreach ($opts as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                            <span class="text-muted small fw-bold d-none d-sm-inline">BARIS</span>
                        </div>

                        <div class="search-wrapper" style="min-width: 220px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" class="org-input w-100" id="search-input" placeholder="Cari pelanggan..." style="height: 38px; border-radius: 8px;">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Auto Sync 5 Menit Loading Bar --}}
            <div class="table-live-stream-bar" id="table-live-stream-bar" title="Sinkronisasi Otomatis MikroTik Aktif (Setiap 5 Menit)">
                <div class="stream-bar-progress"></div>
            </div>

            <div class="table-responsive">
                <table class="org-table" id="customer-table">
                    <thead>
                        <tr>
                            <th style="width:80px;">NO</th>
                            @canany(['lihat troubleshoot', 'kelola troubleshoot'])
                            <th class="text-center" style="width:60px;">OPEN TICKET</th>
                            @endcanany
                            <th>ID PELANGGAN</th>
                            <th>MAC ADDRESS</th>
                            <th>NAMA</th>
                            <th>NIK</th>
                            <th>EMAIL</th>
                            <th>NO TELP</th>
                            <th>TIPE PELANGGAN</th>
                            <th>TIPE LAYANAN</th>
                            <th>ROUTER</th>
                            <th>VLAN</th>
                            <th>PAKET</th>
                            <th>MIKROTIK RADIUS</th>
                            <th>TIPE PEMBAYARAN</th>
                            <th>NAMA WiFi</th>
                            <th>PASSWORD WiFi</th>
                            <th>PPPoE USERNAME</th>
                            <th>PPPoE PASSWORD</th>
                            <th>KAMPUNG</th>
                            <th>DESA</th>
                            <th>RT</th>
                            <th>RW</th>
                            <th>KECAMATAN</th>
                            <th>KABUPATEN/KOTA</th>
                            <th>ALAMAT ODC</th>
                            <th>ALAMAT ODP</th>
                            <th>ALAMAT OLT</th>
                            <th>LOKASI</th>
                            <th>FOTO KTP</th>
                            <th>ORGANISASI/MITRA</th>
                            <th>DIINPUT OLEH</th>
                            <th>DIUBAH OLEH</th>
                            <th>CREATED</th>
                            <th>UPDATED</th>
                            <th>CATATAN TEKNISI</th>
                            <th class="text-center" style="width:120px;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="org-footer flex-column flex-sm-row">
                <div class="org-info mb-3 mb-sm-0 text-center text-sm-start">
                    Menampilkan <span id="start-entry">0</span> - <span id="end-entry">0</span> dari
                    <span id="total-entries">0</span> data
                </div>
                <ul class="pagination mb-0" id="custom-pagination"></ul>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kirim Pemberitahuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="customer_id" id="customer_id" hidden>
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Tipe Pemberitahuan</label>
                        <select name="notif" id="notif" class="form-control">
                            <option value="">Pilih</option>
                            @php
                                $listNotif = [
                                    'pendaftaran baru',
                                    'riset mac address',
                                    'pindah dari pppoe ke voucher',
                                    'pindah dari voucher ke pppoe',
                                    'ganti perangkat',
                                    'berhenti langganan',
                                ];
                            @endphp
                            @foreach ($listNotif as $ln)
                                <option value="{{ $ln }}">{{ ucfirst($ln) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="send-notif" class="btn btn-primary">
                        <span class="btn-text">Kirim</span>
                        <span class="btn-loading spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal-switch-olt" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pindah OLT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="customer_switch_id" id="customer_switch_id" hidden>
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Pilih OLT</label>
                        <select name="olt_id" id="olt_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($olts as $olt)
                                <option value="{{ $olt->id }}">{{ $olt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btn-switch" class="btn btn-primary">
                        <span class="btn-text">Kirim</span>
                        <span class="btn-loading spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pilihan Copy Data Pelanggan -->
    <div class="modal modal-blur fade" id="modal-copy-customer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header border-bottom bg-light">
                    <div>
                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2 mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            Pilih Format Salin Data
                        </h5>
                        <div class="text-muted small" id="copy-customer-subtitle">Salin data pelanggan dengan pilihan format yang tersedia</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Opsi 1: Format Lengkap -->
                        <div class="col-md-6">
                            <div class="card h-100 border rounded-3 p-3 d-flex flex-column" style="background:#f8fafc;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-blue-lt text-primary fw-bold px-2 py-1">Opsi 1</span>
                                    <span class="text-muted small fw-semibold">Format Lengkap (Full)</span>
                                </div>
                                <h4 class="card-title text-dark fw-bold mb-1">Data Lengkap (Key-Value)</h4>
                                <p class="text-muted small mb-2">Format standar berlabel untuk arsip atau pembacaan lengkap.</p>
                                <div class="flex-grow-1 mb-3">
                                    <textarea id="copy-preview-full" class="form-control font-monospace text-muted" rows="11" readonly style="font-size: 11px; resize: none; background: #ffffff;"></textarea>
                                </div>
                                <button type="button" id="btn-do-copy-full" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    Salin Format Full
                                </button>
                            </div>
                        </div>

                        <!-- Opsi 2: Data Mikrotik -->
                        <div class="col-md-6">
                            <div class="card h-100 border border-primary-subtle rounded-3 p-3 d-flex flex-column" style="background:#f0f9ff;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-success text-white fw-bold px-2 py-1">Opsi 2</span>
                                    <span class="text-primary small fw-semibold">Mikrotik Template</span>
                                </div>
                                <h4 class="card-title text-dark fw-bold mb-1">Data Mikrotik</h4>
                                <p class="text-muted small mb-2">Format praktis ringkas berpagar -- untuk data mikrotik.</p>
                                <div class="flex-grow-1 mb-3">
                                    <textarea id="copy-preview-custom" class="form-control font-monospace text-dark" rows="11" readonly style="font-size: 11px; resize: none; background: #ffffff;"></textarea>
                                </div>
                                <button type="button" id="btn-do-copy-custom" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    Salin Data Mikrotik
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Open Ticket Wizard (3 Step) -->
    <div class="modal modal-blur fade" id="modal-open-ticket-wizard" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0 overflow-hidden" style="border-radius: 14px;">
                <!-- Wizard Header with Stepper Navigation -->
                <div class="wizard-steps-container">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l0 2"/><path d="M15 11l0 2"/><path d="M15 17l0 2"/><path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2"/><path d="M9 12l2 2l4 -4"/></svg>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-white mb-0" style="font-size:1.05rem;">Open Ticket Troubleshooting</h5>
                                <div class="text-white-50 small" style="font-size:0.75rem;">Buat tiket penanganan gangguan dan kirim notifikasi ke teknisi</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Steps Indicator -->
                    <div class="wizard-steps-nav">
                        <div class="wizard-steps-line"></div>
                        <div class="wizard-steps-line-fill" id="wizard-progress-fill" style="width: 0%;"></div>

                        <div class="wizard-step-node active" id="step-node-1" onclick="goToWizardStep(1)">
                            <div class="wizard-step-circle">1</div>
                            <div class="wizard-step-title">Detail Pelanggan</div>
                        </div>
                        <div class="wizard-step-node" id="step-node-2" onclick="goToWizardStep(2)">
                            <div class="wizard-step-circle">2</div>
                            <div class="wizard-step-title">Pilih Teknisi</div>
                        </div>
                        <div class="wizard-step-node" id="step-node-3" onclick="goToWizardStep(3)">
                            <div class="wizard-step-circle">3</div>
                            <div class="wizard-step-title">Keterangan & Submit</div>
                        </div>
                    </div>
                </div>

                <!-- Modal Body with Step Contents -->
                <div class="modal-body p-4" style="background:#f8fafc;min-height:360px;">
                    <!-- Loading Skeleton -->
                    <div id="wizard-loading-state" class="text-center py-5">
                        <div class="spinner-border text-primary mb-3" style="width: 2.5rem; height: 2.5rem;" role="status"></div>
                        <div class="fw-bold text-dark">Memuat Data Pelanggan & Alokasi Teknisi...</div>
                        <div class="text-muted small">Mencocokkan wilayah kampung dan desa pelanggan</div>
                    </div>

                    <!-- STEP 1: Detail Pelanggan -->
                    <div id="wizard-step-1" class="wizard-step-content" style="display:none;">
                        <!-- Alert Status MikroTik Waiting -->
                        <div class="alert alert-warning d-flex align-items-center gap-3 mb-3 border-0 shadow-sm" style="background:#fef3c7;border-radius:10px;">
                            <div style="font-size:1.4rem;">⚠️</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark" style="font-size:0.88rem;">Status MikroTik: <span class="badge bg-warning text-dark px-2 py-1 fw-bold">WAITING</span></div>
                                <div class="text-muted small" style="font-size:0.78rem;">Pelanggan ini sedang mengalami status waiting pada router MikroTik dan memerlukan pengecekan teknisi.</div>
                            </div>
                        </div>

                        <!-- Active Ticket Warning if any -->
                        <div id="wizard-active-ticket-alert" class="alert alert-danger d-none align-items-center gap-2 mb-3 border-0 shadow-sm" style="border-radius:10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <div id="wizard-active-ticket-msg" class="small fw-semibold"></div>
                        </div>

                        <!-- Info Grid -->
                        <div class="wizard-info-grid mb-3">
                            <div class="wizard-info-item">
                                <div class="info-label">Nama Pelanggan</div>
                                <div class="info-value" id="wiz-cust-name">-</div>
                            </div>
                            <div class="wizard-info-item">
                                <div class="info-label">MAC Address</div>
                                <div class="info-value font-monospace text-primary" id="wiz-cust-mac">-</div>
                            </div>
                            <div class="wizard-info-item">
                                <div class="info-label">No. Telepon / WA</div>
                                <div class="info-value" id="wiz-cust-telp">-</div>
                            </div>
                            <div class="wizard-info-item">
                                <div class="info-label">Tipe Layanan / Paket</div>
                                <div class="info-value" id="wiz-cust-paket">-</div>
                            </div>
                            <div class="wizard-info-item">
                                <div class="info-label">Router</div>
                                <div class="info-value" id="wiz-cust-router">-</div>
                            </div>
                            <div class="wizard-info-item">
                                <div class="info-label">Alokasi Area (Kampung & Desa)</div>
                                <div class="info-value">
                                    <span class="badge bg-blue-lt text-primary fw-bold me-1" id="wiz-cust-kampung">-</span>
                                    <span class="badge bg-green-lt text-success fw-bold" id="wiz-cust-desa">-</span>
                                </div>
                            </div>
                        </div>

                        <div class="wizard-info-item mb-3">
                            <div class="info-label">Alamat Lengkap</div>
                            <div class="info-value" id="wiz-cust-alamat" style="font-size:0.85rem;font-weight:500;">-</div>
                        </div>

                        <!-- Previous Tech Note Preview if exists -->
                        <div id="wiz-prev-note-box" class="card border border-purple-subtle p-3 mb-0" style="background:#faf5ff;border-radius:10px;display:none;">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="badge bg-purple text-white fw-bold" style="font-size:10px;">Catatan Troubleshoot Terakhir</span>
                                <span class="text-muted small" id="wiz-prev-note-date" style="font-size:11px;">-</span>
                            </div>
                            <div class="small fw-semibold text-dark" id="wiz-prev-note-tech">-</div>
                            <div class="small text-muted fst-italic" id="wiz-prev-note-text">-</div>
                        </div>
                    </div>

                    <!-- STEP 2: Pilih Teknisi -->
                    <div id="wizard-step-2" class="wizard-step-content" style="display:none;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size:0.92rem;">Pilih Teknisi Penanggung Jawab</h6>
                                <div class="text-muted small" style="font-size:0.76rem;" id="wiz-tech-area-hint">
                                    Menampilkan teknisi berdasarkan alokasi kampung / desa pelanggan
                                </div>
                            </div>
                            <div id="wiz-tech-area-badge">
                                <span class="badge bg-teal text-white fw-bold px-2 py-1" style="font-size:11px;">Alokasi Khusus Area</span>
                            </div>
                        </div>

                        <input type="hidden" id="wiz-selected-tech-id" value="">

                        <!-- Fallback Notice if no area tech -->
                        <div id="wiz-tech-fallback-alert" class="alert alert-info py-2 px-3 mb-3 border-0 small d-none" style="background:#e0f2fe;color:#0369a1;border-radius:8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            Tidak ada teknisi dengan alokasi khusus kampung/desa ini. Menampilkan semua teknisi yang tersedia di organisasi.
                        </div>

                        <!-- Technicians List Container -->
                        <div id="wiz-tech-list-container" style="max-height: 280px; overflow-y: auto; padding-right: 4px;">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- STEP 3: Keterangan Tiket & Submit -->
                    <div id="wizard-step-3" class="wizard-step-content" style="display:none;">
                        <!-- Summary Banner -->
                        <div class="card p-3 mb-3 border shadow-sm" style="background:#ffffff;border-radius:10px;">
                            <div class="row g-2 align-items-center">
                                <div class="col-sm-6">
                                    <div class="text-muted small" style="font-size:11px;">Pelanggan:</div>
                                    <div class="fw-bold text-dark" id="wiz-review-cust-name">-</div>
                                    <div class="text-muted small" id="wiz-review-cust-address">-</div>
                                </div>
                                <div class="col-sm-6 border-start ps-sm-3">
                                    <div class="text-muted small" style="font-size:11px;">Teknisi Terpilih:</div>
                                    <div class="fw-bold text-primary" id="wiz-review-tech-name">-</div>
                                    <div class="text-muted small" id="wiz-review-tech-telp">-</div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Input Keterangan -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark d-flex align-items-center justify-content-between" style="font-size:0.85rem;">
                                <span>Keterangan / Keluhan Gangguan <span class="text-danger">*</span></span>
                                <span class="text-muted fw-normal small" style="font-size:0.75rem;">Wajib diisi</span>
                            </label>
                            <textarea id="wiz-ticket-description" class="form-control" rows="4" placeholder="Contoh: Status MikroTik waiting, sinyal wifi tidak terdeteksi / lampu LOS berkedip merah..." style="border-radius:8px;font-size:0.85rem;"></textarea>
                        </div>

                        <!-- WhatsApp Notification Notice -->
                        <div class="card border border-success-subtle p-3 mb-0" style="background:#f0fdf4;border-radius:10px;">
                            <div class="d-flex align-items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-1"><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9"/><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1"/></svg>
                                <div>
                                    <div class="fw-bold text-success" style="font-size:0.85rem;">Notifikasi WhatsApp Otomatis</div>
                                    <div class="text-muted small" style="font-size:0.78rem;">
                                        Saat tiket disubmit, sistem akan otomatis mengirim pesan rincian tiket dan tautan live tracking GPS langsung ke nomor WhatsApp teknisi.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wizard Footer -->
                <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
                    <div>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" id="wiz-btn-cancel">Batal</button>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary" id="wiz-btn-prev" onclick="prevWizardStep()" style="display:none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="15 18 9 12 15 6"/></svg>
                            Sebelumnya
                        </button>
                        <button type="button" class="btn btn-primary" id="wiz-btn-next" onclick="nextWizardStep()">
                            Selanjutnya
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-1"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                        <button type="button" class="btn btn-success" id="wiz-btn-submit" onclick="submitWizardTicket()" style="display:none;">
                            <span class="btn-text d-flex align-items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10"/></svg>
                                Submit Tiket & Kirim WA
                            </span>
                            <span class="btn-loading spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Histori Catatan Teknisi -->
    <div class="modal modal-blur fade" id="modal-customer-notes" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0" style="border-radius:12px;">
                <div class="modal-header border-bottom bg-light">
                    <div>
                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            Histori Catatan Troubleshooting
                        </h5>
                        <div class="text-muted small" id="customer-notes-subtitle">Catatan penanganan teknisi</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3" id="customer-notes-body" style="max-height:400px;overflow-y:auto;">
                    <!-- Dynamically populated -->
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const BASE = "{{ route('customer.index') }}";
        const EDIT_BASE = "{{ route('customer.edit', '__ID__') }}";
        const MIC_RADIUS_BASE = "{{ route('mic.radius.index') }}";
        let table;

        $(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeModalHandlers();
            initializeFilterHandlers();
            initCustomerSseStream();
        });

        // ===========================
        // DataTable Initialization
        // ===========================
        function initializeDataTable() {
            table = $('#customer-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.regencie = $('#regencie').val();
                        d.district = $('#district').val();
                        d.hometown = $('#hometown').val();
                        d.village = $('#village').val();
                        d.vlan = $('#vlan').val();
                        d.olt = $('#olt').val();
                        d.micradius = $('#micradius').val();
                        d.email_verify = $('#email_verify').val();
                        d.wa_verify = $('#wa_verify').val();
                        d.type_id = $('#type_id').val();
                        d.tipe_pelanggan_id = $('#tipe_pelanggan_id').val();
                        d.organization_id = $('#organization_id').val();
                        d.mikrotik_status = $('#mikrotik_status').val();
                        d.search = $('#search-input').val();
                    }
                },
                order: [
                    [@canany(['lihat troubleshoot', 'kelola troubleshoot']) 33 @else 32 @endcanany, 'desc']
                ],
                pageLength: 10,
                dom: 'rt',
                columns: [{
                        data: 'DT_RowIndex',
                        width: '40px',
                        render: function(data, type, row) {
                            let checkbox = '<input class="form-check-input row-check m-0" type="checkbox" name="selected[]" value="' + row.id + '"> ';
                            let copyBtn = '';
                            @can('copy pelanggan')
                            copyBtn = '<button class="btn-action p-1 copy-btn ms-1" data-id="' + row.id + '" title="Copy Data"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button>';
                            @endcan
                            return checkbox + data + copyBtn;
                        }
                    },
                    @canany(['lihat troubleshoot', 'kelola troubleshoot'])
                    {
                        data: 'btn_open_ticket',
                        name: 'btn_open_ticket',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    @endcanany
                    {
                        data: 'uuid',
                        defaultContent: '-'
                    },
                    {
                        data: 'mac_address'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'nik',
                        defaultContent: '-'
                    },
                    {
                        data: 'email',
                        defaultContent: '-'
                    },
                    {
                        data: 'telp',
                        defaultContent: '-'
                    },
                    {
                        data: 'tipe_pelanggan',
                        defaultContent: '-'
                    },
                    {
                        data: 'type_name'
                    },
                    {
                        data: 'router_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'vlan_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'paket_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'mic_radius_info',
                        defaultContent: '-'
                    },
                    {
                        data: 'price_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'name_wifi',
                        defaultContent: '-'
                    },
                    {
                        data: 'password_wifi',
                        defaultContent: '-'
                    },
                    {
                        data: 'pppoe_username',
                        defaultContent: '-'
                    },
                    {
                        data: 'pppoe_password',
                        defaultContent: '-'
                    },
                    {
                        data: 'hometown_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'village_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'rt_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'rw_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'district_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'regencie_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'odc_info',
                        defaultContent: '-'
                    },
                    {
                        data: 'odp_info',
                        defaultContent: '-'
                    },
                    {
                        data: 'olt_info',
                        defaultContent: '-'
                    },
                    {
                        data: 'lokasi',
                        defaultContent: '-'
                    },
                    {
                        data: 'ktp',
                        defaultContent: '-'
                    },
                    {
                        data: 'organization',
                        defaultContent: '-'
                    },
                    {
                        data: 'input_by',
                        defaultContent: '-'
                    },
                    {
                        data: 'edited_by',
                        defaultContent: '-'
                    },
                    {
                        data: 'created_at',
                        render: function(data) {
                            return moment(data).format('DD/MM/YYYY HH:mm:ss');
                        }
                    },
                    {
                        data: 'updated_at_formatted',
                        defaultContent: '-'
                    },
                    {
                        data: 'technician_notes',
                        name: 'technician_notes',
                        defaultContent: '-',
                        searchable: false
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                drawCallback: function(settings) {
                    updatePaginationInfo(settings);
                    updateCustomPagination();
                }
            });
        }

        // ===========================
        // Pagination & Search
        // ===========================
        function initializePaginationAndSearch() {
            // Entries per page
            $("#sort").on('change', function() {
                table.page.len($(this).val()).draw();
            });

            // Search on Enter key
            $("#search-input").on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    table.ajax.reload();
                }
            });

            // Search on button click
            $("#search-btn").on('click', function(e) {
                e.preventDefault();
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

            // Previous button
            pagination.append(`
            <li class="page-item ${info.page === 0 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${info.page - 1}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </a>
            </li>
        `);

            let startPage = Math.max(0, info.page - 2);
            let endPage = Math.min(info.pages - 1, info.page + 2);

            // First page
            if (startPage > 0) {
                pagination.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="0">1</a>
                </li>
            `);
                if (startPage > 1) {
                    pagination.append(`
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                `);
                }
            }

            // Page numbers
            for (let i = startPage; i <= endPage; i++) {
                pagination.append(`
                <li class="page-item ${i === info.page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i + 1}</a>
                </li>
            `);
            }

            // Last page
            if (endPage < info.pages - 1) {
                if (endPage < info.pages - 2) {
                    pagination.append(`
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                `);
                }
                pagination.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a>
                </li>
            `);
            }

            // Next button
            pagination.append(`
            <li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${info.page + 1}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
            </li>
        `);

            // Event handler for pagination links
            pagination.find('a').on('click', function(e) {
                e.preventDefault();
                if (!$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
                    const page = parseInt($(this).data('page'));
                    if (!isNaN(page) && page >= 0 && page < info.pages) {
                        table.page(page).draw('page');
                    }
                }
            });
        }

        // ===========================
        // Filter Handlers (Select2 & Cascading Location)
        // ===========================
        let rawDistricts = [];
        let rawHometowns = [];
        let rawVillages = [];
        let isCascading = false;

        function initializeFilterHandlers() {
            if ($('.filter-select').length === 0) return;

            // Cache original raw options
            $('#district option').each(function() {
                const val = $(this).val();
                if (val) {
                    rawDistricts.push({
                        val: val,
                        text: $(this).text(),
                        regency: $(this).data('regency')
                    });
                }
            });

            $('#hometown option').each(function() {
                const val = $(this).val();
                if (val) {
                    rawHometowns.push({
                        val: val,
                        text: $(this).text(),
                        regency: $(this).data('regency'),
                        district: $(this).data('district')
                    });
                }
            });

            $('#village option').each(function() {
                const val = $(this).val();
                if (val) {
                    rawVillages.push({
                        val: val,
                        text: $(this).text(),
                        regency: $(this).data('regency'),
                        district: $(this).data('district')
                    });
                }
            });

            // Initialize Select2 on all filter-select elements
            $('.filter-select').each(function() {
                const placeholder = $(this).data('placeholder') || 'Pilih';
                $(this).select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: placeholder,
                    allowClear: true
                });
            });

            // Hierarchical filter: Kabupaten/Kota changed
            $('#regencie').on('change', function() {
                if (isCascading) return;
                isCascading = true;

                const regId = $(this).val();

                // 1. Update District
                const distSelect = $('#district');
                distSelect.empty().append('<option value="">Semua Kecamatan</option>');
                const filteredDistricts = regId ? rawDistricts.filter(d => String(d.regency) === String(regId)) : rawDistricts;
                filteredDistricts.forEach(d => {
                    distSelect.append(`<option value="${d.val}" data-regency="${d.regency}">${d.text}</option>`);
                });
                distSelect.val('').trigger('change.select2');

                // 2. Update Hometown
                const homeSelect = $('#hometown');
                homeSelect.empty().append('<option value="">Semua Kampung</option>');
                const filteredHometowns = regId ? rawHometowns.filter(h => String(h.regency) === String(regId)) : rawHometowns;
                filteredHometowns.forEach(h => {
                    homeSelect.append(`<option value="${h.val}" data-regency="${h.regency}" data-district="${h.district}">${h.text}</option>`);
                });
                homeSelect.val('').trigger('change.select2');

                // 3. Update Village
                const vilSelect = $('#village');
                vilSelect.empty().append('<option value="">Semua Desa</option>');
                const filteredVillages = regId ? rawVillages.filter(v => String(v.regency) === String(regId)) : rawVillages;
                filteredVillages.forEach(v => {
                    vilSelect.append(`<option value="${v.val}" data-regency="${v.regency}" data-district="${v.district}">${v.text}</option>`);
                });
                vilSelect.val('').trigger('change.select2');

                isCascading = false;
                table.ajax.reload();
            });

            // Hierarchical filter: Kecamatan changed
            $('#district').on('change', function() {
                if (isCascading) return;
                isCascading = true;

                const distId = $(this).val();
                const regId = $('#regencie').val();

                // 1. Update Hometown
                const homeSelect = $('#hometown');
                homeSelect.empty().append('<option value="">Semua Kampung</option>');
                let filteredHometowns = rawHometowns;
                if (distId) {
                    filteredHometowns = rawHometowns.filter(h => String(h.district) === String(distId));
                } else if (regId) {
                    filteredHometowns = rawHometowns.filter(h => String(h.regency) === String(regId));
                }
                filteredHometowns.forEach(h => {
                    homeSelect.append(`<option value="${h.val}" data-regency="${h.regency}" data-district="${h.district}">${h.text}</option>`);
                });
                homeSelect.val('').trigger('change.select2');

                // 2. Update Village
                const vilSelect = $('#village');
                vilSelect.empty().append('<option value="">Semua Desa</option>');
                let filteredVillages = rawVillages;
                if (distId) {
                    filteredVillages = rawVillages.filter(v => String(v.district) === String(distId));
                } else if (regId) {
                    filteredVillages = rawVillages.filter(v => String(v.regency) === String(regId));
                }
                filteredVillages.forEach(v => {
                    vilSelect.append(`<option value="${v.val}" data-regency="${v.regency}" data-district="${v.district}">${v.text}</option>`);
                });
                vilSelect.val('').trigger('change.select2');

                isCascading = false;
                table.ajax.reload();
            });

            // Other filters changed
            $('.filter-select').not('#regencie, #district').on('change', function() {
                if (!isCascading) {
                    table.ajax.reload();
                }
            });

            // Reset all filters
            $('#btn-reset-filters').on('click', function() {
                isCascading = true;

                // Reset Kabupaten
                $('#regencie').val('').trigger('change.select2');

                // Restore District
                const distSelect = $('#district');
                distSelect.empty().append('<option value="">Semua Kecamatan</option>');
                rawDistricts.forEach(d => distSelect.append(`<option value="${d.val}" data-regency="${d.regency}">${d.text}</option>`));
                distSelect.val('').trigger('change.select2');

                // Restore Hometown
                const homeSelect = $('#hometown');
                homeSelect.empty().append('<option value="">Semua Kampung</option>');
                rawHometowns.forEach(h => homeSelect.append(`<option value="${h.val}" data-regency="${h.regency}" data-district="${h.district}">${h.text}</option>`));
                homeSelect.val('').trigger('change.select2');

                // Restore Village
                const vilSelect = $('#village');
                vilSelect.empty().append('<option value="">Semua Desa</option>');
                rawVillages.forEach(v => vilSelect.append(`<option value="${v.val}" data-regency="${v.regency}" data-district="${v.district}">${v.text}</option>`));
                vilSelect.val('').trigger('change.select2');

                // Reset other selects
                $('.filter-select').not('#regencie, #district, #hometown, #village').val('').trigger('change.select2');

                // Reset search input
                $('#search-input').val('');

                isCascading = false;
                table.ajax.reload();
            });
        }

        // ===========================
        // Modal Handlers
        // ===========================
        function initializeModalHandlers() {
            // Chat notification modal
            $("#send-notif").on('click', function() {
                handleSendNotification();
            });

            // Switch OLT modal
            $("#btn-switch").on('click', function() {
                handleSwitchOlt();
            });
        }

        // ===========================
        // CRUD Operations
        // ===========================

        // Delete Customer
        function deleteCustomer(id) {
            Swal.fire({
                title: "Hapus Pelanggan?",
                text: "Data pelanggan ini akan dihapus permanen.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: 'btn btn-danger px-4 mx-2',
                    cancelButton: 'btn btn-link link-secondary px-4'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                            url: BASE + '/' + id + '/destroy',
                            method: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            }
                        })
                        .done(function(response) {
                            showSuccessMessage(response.message);
                            table.ajax.reload();
                        })
                        .fail(function() {
                            showErrorMessage("Server Error");
                        });
                }
            });
        }

        // Open Chat Modal
        function openChat(id) {
            $("#modal-simple").modal("show");
            $("#customer_id").val(id);
        }

        // Send Notification
        function handleSendNotification() {
            const btn = $("#send-notif");

            if (btn.prop("disabled")) return;

            const btnText = btn.find(".btn-text");
            const btnLoading = btn.find(".btn-loading");

            btn.prop("disabled", true);
            btnText.text("Mengirim...");
            btnLoading.removeClass("d-none");

            let customer_id = $("#customer_id").val();
            let notif = $("#notif").val();

            $.ajax({
                    url: "{{ route('customer.notif') }}",
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        customer_id: customer_id,
                        notif: notif,
                    },
                    dataType: "JSON",
                })
                .done(function(response) {
                    showSuccessMessage(response.message);
                    $("#modal-simple").modal("hide");
                    table.ajax.reload();
                    resetButton(btn, "Kirim");
                })
                .fail(function() {
                    showErrorMessage("Server Error");
                    resetButton(btn, "Kirim");
                });
        }

        // Open Switch OLT Modal
        function openSwitch() {
            let checks = document.querySelectorAll('.row-check:checked');

            if (checks.length === 0) {
                showInfoMessage("Pilih satu atau lebih pelanggan untuk dipindah OLT");
                return false;
            }

            let customerIDs = Array.from(checks).map(c => c.value);
            document.getElementById('customer_switch_id').value = JSON.stringify(customerIDs);

            $("#modal-switch-olt").modal("show");
            return true;
        }

        // Handle Switch OLT
        function handleSwitchOlt() {
            const btn = $("#btn-switch");

            btn.prop("disabled", true);
            btn.find(".btn-text").text("Memproses...");
            btn.find(".btn-loading").removeClass("d-none");

            let customer_switch_id = $("#customer_switch_id").val();
            let olt_id = $("#olt_id").val();

            $.ajax({
                    url: "{{ route('customer.switchOlt') }}",
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        customer_switch_id: customer_switch_id,
                        olt_id: olt_id
                    },
                    dataType: "JSON"
                })
                .done(function(response) {
                    if (response.code == 200) {
                        showSuccessMessage(response.message);
                        $("#modal-switch-olt").modal("hide");
                        table.ajax.reload();
                        resetButton(btn, "Kirim");
                    } else {
                        showErrorMessage(response.message);
                        resetButton(btn, "Kirim");
                    }
                })
                .fail(function() {
                    showErrorMessage("Server Error");
                    resetButton(btn, "Kirim");
                });
        }

        // Get Select Options for Hometown
        $("#home_town_id").change(function() {
            let hometown_id = $(this).val();

            if (hometown_id) {
                $.ajax({
                        url: "{{ route('customer.getSelect') }}",
                        method: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            home_town_id: hometown_id
                        },
                        dataType: "JSON"
                    })
                    .done(function(response) {
                        let data = response.data;

                        // Populate OLT dropdown
                        let oltOptions = '<option value="">Pilih</option>';
                        if (data.olts && data.olts.length > 0) {
                            data.olts.forEach(item => {
                                oltOptions += `<option value="${item.id}">${item.name}</option>`;
                            });
                        }
                        $("#olt_id").html(oltOptions);

                        // Populate Mic Radius dropdown
                        let micOptions = '<option value="">Pilih</option>';
                        if (data.micRadius && data.micRadius.length > 0) {
                            data.micRadius.forEach(item => {
                                micOptions +=
                                    `<option value="${item.id}">${item.code} - ${item.name}</option>`;
                            });
                        }
                        $("#mic_radius_id").html(micOptions);
                    })
                    .fail(function() {
                        showErrorMessage("Server Error");
                    });
            }
        });

        // ===========================
        // Helper Functions
        // ===========================
        function showSuccessMessage(message) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: "success",
                title: message
            });
        }

        function showErrorMessage(message) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: "error",
                title: message
            });
        }

        function showInfoMessage(message) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: "info",
                title: message
            });
        }

        function mixLogin(id) {
            const url = MIC_RADIUS_BASE + '/' + id + '/mix-login';
            window.open(url, '_blank');
        }

        function resetButton(btn, text) {
            btn.prop('disabled', false);
            btn.find(".btn-text").text(text);
            btn.find(".btn-loading").addClass('d-none');
        }

        function verifyEmailOnDemand(id, element) {
            const $btn = $(element);
            if ($btn.hasClass('pe-none')) return;
            
            const originalHtml = $btn.html();
            $btn.addClass('pe-none').html('<div class="spinner-border text-primary" role="status" style="width: 12px; height: 12px; border-width: 2px;"></div>');
            
            $.ajax({
                url: "{{ route('customer.verify-email-on-demand') }}",
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id
                },
                dataType: "JSON"
            })
            .done(function(response) {
                if (response.status === 'register') {
                    showSuccessMessage('✅ Email terdaftar: ' + response.message);
                } else {
                    showErrorMessage('❌ Email tidak valid: ' + response.message);
                }
                table.ajax.reload(null, false);
            })
            .fail(function(xhr) {
                const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal mengecek email.";
                showErrorMessage(errorMsg);
                $btn.removeClass('pe-none').html(originalHtml);
            });
        }

        function verifyWaOnDemand(id, element) {
            const $btn = $(element);
            if ($btn.hasClass('pe-none')) return;
            
            const originalHtml = $btn.html();
            $btn.addClass('pe-none').html('<div class="spinner-border text-primary" role="status" style="width: 12px; height: 12px; border-width: 2px;"></div>');
            
            $.ajax({
                url: "{{ route('customer.verify-wa-on-demand') }}",
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id
                },
                dataType: "JSON"
            })
            .done(function(response) {
                if (response.status === 'registered') {
                    showSuccessMessage('✅ WA terdaftar: ' + response.message);
                } else {
                    showErrorMessage('❌ WA tidak terdaftar: ' + response.message);
                }
                table.ajax.reload(null, false);
            })
            .fail(function(xhr) {
                const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal mengecek status WA.";
                showErrorMessage(errorMsg);
                $btn.removeClass('pe-none').html(originalHtml);
            });
        }

        let activeCopyFullText = '';
        let activeCopyCustomText = '';

        function cleanText(htmlOrStr) {
            if (!htmlOrStr) return '';
            return $('<div>').html(htmlOrStr).text().trim();
        }

        function cleanPrefix(val, prefix) {
            if (!val || val === '-') return '';
            const regex = new RegExp('^' + prefix + '\\.?\\s*', 'i');
            return val.replace(regex, '').trim();
        }

        function generateCustomFormat(rowData) {
            const uuid = (rowData.uuid && rowData.uuid !== '-') ? rowData.uuid : '';
            const typeName = (rowData.type_name && rowData.type_name !== '-') ? rowData.type_name.toUpperCase() : '';
            const name = (rowData.name && rowData.name !== '-') ? rowData.name : '';
            const telp = cleanText(rowData.telp);
            const mac = (rowData.mac_address && rowData.mac_address !== '-') ? rowData.mac_address : '';
            const router = (rowData.router_name && rowData.router_name !== '-') ? rowData.router_name : '';

            const hometown = cleanPrefix(rowData.hometown_name, 'Kp');
            const village = cleanPrefix(rowData.village_name, 'Ds');
            const rt = cleanPrefix(rowData.rt_name, 'Rt');
            const rw = cleanPrefix(rowData.rw_name, 'Rw');
            const district = cleanPrefix(rowData.district_name, 'Kec');
            const regency = cleanPrefix(rowData.regencie_name, 'Kab');
            const vlan = cleanPrefix(rowData.vlan_name, 'Vlan');
            
            let kordinat = '';
            if (rowData.latitude && rowData.longitude) {
                kordinat = `${rowData.latitude},${rowData.longitude}`;
            } else if (rowData.lokasi && rowData.lokasi !== '-') {
                const match = rowData.lokasi.match(/q=([-\d.]+,[-\d.]+)/);
                kordinat = match ? match[1] : '';
            }

            const lines = [
                `--${uuid}--`,
                `--${typeName}--`,
                `--${name}--`,
                `--${telp}--`,
                `--${mac}--`,
                `--${router}--`,
                `--Kp.${hometown}--`,
                `--Ds.${village}--`,
                `--Rt.${rt}--`,
                `--Rw.${rw}--`,
                `--Kec.${district}--`,
                `--Kab.${regency}--`,
                `--Vlan.${vlan}--`,
                `--${kordinat}--`
            ];

            return lines.join('\n');
        }

        function generateFullFormat(rowData) {
            const fields = {
                'UUID': rowData.uuid,
                'Tipe Pelanggan': rowData.tipe_pelanggan,
                'Tipe Layanan': rowData.type_name,
                'NIK': rowData.nik,
                'NAMA': rowData.name,
                'EMAIL': cleanText(rowData.email),
                'TELP': cleanText(rowData.telp),
                'MAC ADDRESS': rowData.mac_address,
                'ROUTER': rowData.router_name,
                'KAMPUNG': rowData.hometown_name,
                'DESA': rowData.village_name,
                'RT': rowData.rt_name,
                'RW': rowData.rw_name,
                'KECAMATAN': rowData.district_name,
                'KAB/KOTA': rowData.regencie_name,
                'VLAN': rowData.vlan_name,
                'OLT': rowData.olt_info,
                'ODP': rowData.odp_info,
                'WIFI': rowData.name_wifi,
                'PASSWORD WIFI': rowData.password_wifi,
                'PPPOE USER': rowData.pppoe_username,
                'PPPOE PASS': rowData.pppoe_password,
                'PAKET': rowData.paket_name,
                'PEMBAYARAN': rowData.price_name,
                'ORGANISASI/MITRA': rowData.organization,
                'INPUT OLEH': rowData.input_by,
                'LOKASI': rowData.maps_url || (rowData.latitude && rowData.longitude ? `https://www.google.com/maps?q=${rowData.latitude},${rowData.longitude}` : null)
            };

            let text = '';
            Object.entries(fields).forEach(([k, v]) => {
                if (v && v !== '-') text += `${k} : ${v}\n`;
            });
            return text;
        }

        function copyToClipboard(text, successMsg = 'Data berhasil disalin ke clipboard') {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => {
                    showSuccessMessage(successMsg);
                }).catch(() => {
                    fallbackClipboardCopy(text, successMsg);
                });
            } else {
                fallbackClipboardCopy(text, successMsg);
            }
        }

        function fallbackClipboardCopy(text, successMsg) {
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = text;
            tempTextArea.style.position = 'fixed';
            tempTextArea.style.left = '-9999px';
            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            try {
                document.execCommand('copy');
                showSuccessMessage(successMsg);
            } catch (err) {
                showErrorMessage('Gagal menyalin ke clipboard.');
            }
            document.body.removeChild(tempTextArea);
        }

        // Open modal when copy button is clicked
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.copy-btn');
            if (btn) {
                const tr = btn.closest('tr');
                const rowData = table.row(tr).data();
                if (!rowData) return;

                activeCopyFullText = generateFullFormat(rowData);
                activeCopyCustomText = generateCustomFormat(rowData);

                $('#copy-customer-subtitle').text(`Pelanggan: ${rowData.uuid || '-'} - ${rowData.name || '-'}`);
                $('#copy-preview-full').val(activeCopyFullText);
                $('#copy-preview-custom').val(activeCopyCustomText);

                const modalEl = document.getElementById('modal-copy-customer');
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        });

        // Copy button handlers inside modal
        $('#btn-do-copy-full').on('click', function() {
            if (!activeCopyFullText) return;
            copyToClipboard(activeCopyFullText, 'Format Full berhasil disalin!');
            bootstrap.Modal.getInstance(document.getElementById('modal-copy-customer'))?.hide();
        });

        $('#btn-do-copy-custom').on('click', function() {
            if (!activeCopyCustomText) return;
            copyToClipboard(activeCopyCustomText, 'Data Mikrotik berhasil disalin!');
            bootstrap.Modal.getInstance(document.getElementById('modal-copy-customer'))?.hide();
        });

        // ==========================================
        // Automatic 5-Minute Data Synchronization
        // ==========================================
        const CUST_SYNC_INTERVAL_MS = 5 * 60 * 1000; // 5 Menit = 300.000 ms
        let custAutoSyncTimer = null;
        let custLastTickTimestamp = Date.now();
        const custLiveSyncTimerEl = document.getElementById('cust-live-sync-timer');
        const btnRefreshCustomer = document.getElementById('btn-refresh-customer');

        // Ticker 1 Detik untuk memperbarui indikator waktu: (Baru saja) -> (15s lalu) -> (1 mnt lalu) -> (4 mnt lalu)
        setInterval(() => {
            if (!custLiveSyncTimerEl) return;
            const elapsedSec = Math.floor((Date.now() - custLastTickTimestamp) / 1000);
            if (elapsedSec < 10) {
                custLiveSyncTimerEl.textContent = '(Baru saja)';
            } else if (elapsedSec < 60) {
                custLiveSyncTimerEl.textContent = `(${elapsedSec}s lalu)`;
            } else {
                const elapsedMin = Math.floor(elapsedSec / 60);
                custLiveSyncTimerEl.textContent = `(${elapsedMin} mnt lalu)`;
            }
        }, 1000);

        function triggerTableSyncAnimation() {
            custLastTickTimestamp = Date.now();
            const bar = document.getElementById('table-live-stream-bar');
            if (bar) {
                bar.classList.remove('animating');
                void bar.offsetWidth; // Force DOM reflow to restart animation
                bar.classList.add('animating');
            }
            const badge = document.getElementById('cust-live-sync-status-badge') || document.querySelector('.live-sync-badge');
            if (badge) {
                badge.style.transform = 'scale(1.06)';
                setTimeout(() => { badge.style.transform = 'scale(1)'; }, 350);
            }
        }

        async function syncCustomerLiveData(force = true) {
            if (btnRefreshCustomer) {
                btnRefreshCustomer.classList.add('loading');
                btnRefreshCustomer.disabled = true;
            }

            try {
                // Tarik pembaruan live data langsung dari router MikroTik ke database
                const syncUrl = `{{ route('mikrotik.dhcp.data') }}?force=${force ? 1 : 0}&t=${Date.now()}`;
                const response = await fetch(syncUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const resData = await response.json();
                    if (resData.success) {
                        triggerTableSyncAnimation();
                        if (typeof table !== 'undefined' && table) {
                            table.ajax.reload(null, false);
                        }
                    }
                }
            } catch (err) {
                console.warn('Gagal sync live data customer:', err);
                if (typeof table !== 'undefined' && table) {
                    table.ajax.reload(null, false);
                }
            } finally {
                if (btnRefreshCustomer) {
                    btnRefreshCustomer.classList.remove('loading');
                    btnRefreshCustomer.disabled = false;
                }
                custLastTickTimestamp = Date.now();
            }
        }

        function startCustomerAutoSync() {
            stopCustomerAutoSync();
            custAutoSyncTimer = setInterval(syncCustomerLiveData, CUST_SYNC_INTERVAL_MS);
        }

        function stopCustomerAutoSync() {
            if (custAutoSyncTimer) {
                clearInterval(custAutoSyncTimer);
                custAutoSyncTimer = null;
            }
        }

        if (btnRefreshCustomer) {
            btnRefreshCustomer.addEventListener('click', function () {
                syncCustomerLiveData(true);
                startCustomerAutoSync();
            });
        }

        // Start auto-sync on page load
        startCustomerAutoSync();

        // Auto-pause saat tab tidak aktif (Page Visibility API)
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                stopCustomerAutoSync();
            } else {
                const elapsed = Date.now() - custLastTickTimestamp;
                if (elapsed >= CUST_SYNC_INTERVAL_MS) {
                    syncCustomerLiveData(true);
                }
                startCustomerAutoSync();
            }
        });

        // ==========================================
        // OPEN TICKET WIZARD LOGIC FOR WAITING USERS
        // ==========================================
        let currentWizardStep = 1;
        let wizardCustomerData = null;
        let wizardTechnicians = [];
        let selectedTechnician = null;

        const GET_TECH_BY_CUSTOMER_URL = "{{ route('troubleshoot.technicians-by-customer') }}";
        const STORE_TICKET_FROM_CUSTOMER_URL = "{{ route('troubleshoot.store-from-customer') }}";
        const CUSTOMER_NOTES_BASE_URL = "{{ url('/ticket/customer') }}";

        function openTicketFromCustomer(customerId) {
            currentWizardStep = 1;
            wizardCustomerData = null;
            wizardTechnicians = [];
            selectedTechnician = null;
            $('#wiz-selected-tech-id').val('');
            $('#wiz-ticket-description').val('');

            // Reset UI
            $('#wizard-loading-state').show();
            $('.wizard-step-content').hide();
            $('#wiz-btn-prev').hide();
            $('#wiz-btn-next').hide();
            $('#wiz-btn-submit').hide();
            updateWizardNav(1);

            $('#modal-open-ticket-wizard').modal('show');

            $.ajax({
                url: GET_TECH_BY_CUSTOMER_URL,
                type: 'GET',
                data: { customer_id: customerId },
                headers: { 'Accept': 'application/json' },
                success: function(res) {
                    if (res.status === 'success') {
                        wizardCustomerData = res.customer;
                        wizardTechnicians = res.technicians || [];
                        
                        // Populate Step 1: Customer Data
                        $('#wiz-cust-name').text(res.customer.name || '-');
                        $('#wiz-cust-mac').text(res.customer.mac_address || '-');
                        $('#wiz-cust-telp').text(res.customer.telp || '-');
                        $('#wiz-cust-paket').text((res.customer.tipe_layanan || '-') + ' / ' + (res.customer.paket || '-'));
                        $('#wiz-cust-router').text(res.customer.router || '-');
                        $('#wiz-cust-kampung').text('Kampung: ' + (res.customer.kampung || '-'));
                        $('#wiz-cust-desa').text('Desa: ' + (res.customer.desa || '-'));
                        $('#wiz-cust-alamat').text(res.customer.alamat || '-');

                        // Active ticket notice
                        if (res.active_ticket) {
                            $('#wizard-active-ticket-alert').removeClass('d-none').addClass('d-flex');
                            $('#wizard-active-ticket-msg').html(
                                'Perhatian: Pelanggan ini telah memiliki tiket aktif (#' + res.active_ticket.id + ') dengan status <span class="badge bg-danger">' + res.active_ticket.status.toUpperCase() + '</span> sejak ' + res.active_ticket.created_at + '.'
                            );
                        } else {
                            $('#wizard-active-ticket-alert').addClass('d-none').removeClass('d-flex');
                        }

                        // Previous note preview
                        if (res.latest_ticket && res.latest_ticket.technician_notes && res.latest_ticket.technician_notes !== '-') {
                            $('#wiz-prev-note-box').show();
                            $('#wiz-prev-note-tech').text('Teknisi: ' + res.latest_ticket.technician_name);
                            $('#wiz-prev-note-date').text(res.latest_ticket.updated_at);
                            $('#wiz-prev-note-text').text('"' + res.latest_ticket.technician_notes + '"');
                        } else {
                            $('#wiz-prev-note-box').hide();
                        }

                        // Populate Step 2: Technicians
                        renderTechnicianCards(wizardTechnicians, res.is_fallback, res.customer.kampung, res.customer.desa);

                        // Populate Step 3 Review defaults
                        $('#wiz-review-cust-name').text(res.customer.name || '-');
                        $('#wiz-review-cust-address').text((res.customer.kampung || '') + ', ' + (res.customer.desa || ''));

                        // Default pre-fill description with informative template
                        let defaultDesc = 'Status MikroTik terdeteksi WAITING. Mohon dilakukan pengecekan kabel FO / redaman sinyal dan konfigurasi router di lokasi pelanggan (' + (res.customer.kampung || '') + ', ' + (res.customer.desa || '') + ').';
                        $('#wiz-ticket-description').val(defaultDesc);

                        $('#wizard-loading-state').hide();
                        goToWizardStep(1);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memuat Data',
                            text: res.message || 'Terjadi kesalahan saat memuat data pelanggan.',
                        });
                        $('#modal-open-ticket-wizard').modal('hide');
                    }
                },
                error: function(xhr) {
                    let msg = 'Terjadi kesalahan pada server.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg,
                    });
                    $('#modal-open-ticket-wizard').modal('hide');
                }
            });
        }

        function renderTechnicianCards(technicians, isFallback, kampung, desa) {
            const container = $('#wiz-tech-list-container');
            container.empty();

            if (isFallback) {
                $('#wiz-tech-fallback-alert').removeClass('d-none');
                $('#wiz-tech-area-badge').html('<span class="badge bg-secondary text-white fw-bold px-2 py-1" style="font-size:11px;">Semua Teknisi</span>');
                $('#wiz-tech-area-hint').text('Menampilkan seluruh teknisi organisasi');
            } else {
                $('#wiz-tech-fallback-alert').addClass('d-none');
                $('#wiz-tech-area-badge').html('<span class="badge bg-teal text-white fw-bold px-2 py-1" style="font-size:11px;">Alokasi Khusus Area</span>');
                $('#wiz-tech-area-hint').text('Menampilkan teknisi untuk area ' + (kampung || '') + ' / ' + (desa || ''));
            }

            if (!technicians || technicians.length === 0) {
                container.html('<div class="text-center text-muted py-4"><div style="font-size:1.5rem;">👨‍🔧</div><div>Tidak ada teknisi yang terdaftar di organisasi ini.</div></div>');
                return;
            }

            technicians.forEach(function(tech, index) {
                const initials = tech.name ? tech.name.substring(0, 2).toUpperCase() : 'TK';
                const telpText = tech.telp ? ('WA: ' + tech.telp) : 'Belum ada No. WA';
                const hasWaBadge = tech.telp
                    ? '<span class="badge bg-success-lt text-success ms-auto" style="font-size:10px;">WhatsApp Aktif</span>'
                    : '<span class="badge bg-secondary-lt text-secondary ms-auto" style="font-size:10px;">Tanpa WA</span>';

                const cardHtml = `
                    <div class="tech-card-select" id="tech-card-${tech.id}" onclick="selectTechnicianCard(${tech.id})">
                        <div class="tech-card-avatar">${initials}</div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark" style="font-size:0.9rem;">${tech.name}</div>
                            <div class="text-muted small" style="font-size:0.75rem;">${tech.email || '-'} &middot; <span class="fw-medium">${telpText}</span></div>
                        </div>
                        ${hasWaBadge}
                    </div>
                `;
                container.append(cardHtml);
            });

            // Auto-select first technician if available
            if (technicians.length > 0) {
                selectTechnicianCard(technicians[0].id);
            }
        }

        function selectTechnicianCard(techId) {
            $('.tech-card-select').removeClass('selected');
            $('#tech-card-' + techId).addClass('selected');
            $('#wiz-selected-tech-id').val(techId);

            selectedTechnician = wizardTechnicians.find(t => t.id == techId) || null;
            if (selectedTechnician) {
                $('#wiz-review-tech-name').text(selectedTechnician.name);
                $('#wiz-review-tech-telp').text(selectedTechnician.telp ? ('WA: ' + selectedTechnician.telp) : 'Tanpa No. WA');
            }
        }

        function goToWizardStep(step) {
            if (!wizardCustomerData) return;

            // Validasi Step 2 sebelum lanjut ke Step 3
            if (step === 3) {
                const techId = $('#wiz-selected-tech-id').val();
                if (!techId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Teknisi',
                        text: 'Silakan pilih teknisi penanggung jawab terlebih dahulu sebelum melanjutkan.',
                    });
                    return;
                }
            }

            currentWizardStep = step;
            $('.wizard-step-content').hide();
            $('#wizard-step-' + step).show();
            updateWizardNav(step);

            // Button controls
            if (step === 1) {
                $('#wiz-btn-prev').hide();
                $('#wiz-btn-next').show();
                $('#wiz-btn-submit').hide();
            } else if (step === 2) {
                $('#wiz-btn-prev').show();
                $('#wiz-btn-next').show();
                $('#wiz-btn-submit').hide();
            } else if (step === 3) {
                $('#wiz-btn-prev').show();
                $('#wiz-btn-next').hide();
                $('#wiz-btn-submit').show();
            }
        }

        function nextWizardStep() {
            if (currentWizardStep < 3) {
                goToWizardStep(currentWizardStep + 1);
            }
        }

        function prevWizardStep() {
            if (currentWizardStep > 1) {
                goToWizardStep(currentWizardStep - 1);
            }
        }

        function updateWizardNav(step) {
            $('.wizard-step-node').removeClass('active completed');
            for (let i = 1; i <= 3; i++) {
                if (i < step) {
                    $('#step-node-' + i).addClass('completed');
                    $('#step-node-' + i + ' .wizard-step-circle').html('&#10003;');
                } else if (i === step) {
                    $('#step-node-' + i).addClass('active');
                    $('#step-node-' + i + ' .wizard-step-circle').text(i);
                } else {
                    $('#step-node-' + i + ' .wizard-step-circle').text(i);
                }
            }

            let fillPercent = (step - 1) * 38;
            $('#wizard-progress-fill').css('width', fillPercent + '%');
        }

        function submitWizardTicket() {
            if (!wizardCustomerData) return;

            const techId = $('#wiz-selected-tech-id').val();
            const description = $('#wiz-ticket-description').val().trim();

            if (!techId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Teknisi Belum Dipilih',
                    text: 'Silakan pilih teknisi terlebih dahulu pada langkah 2.',
                });
                goToWizardStep(2);
                return;
            }

            if (!description) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Keterangan Wajib Diisi',
                    text: 'Mohon isi rincian keterangan / keluhan gangguan.',
                });
                $('#wiz-ticket-description').focus();
                return;
            }

            const btnSubmit = $('#wiz-btn-submit');
            btnSubmit.prop('disabled', true);
            btnSubmit.find('.btn-text').addClass('d-none');
            btnSubmit.find('.btn-loading').removeClass('d-none');

            $.ajax({
                url: STORE_TICKET_FROM_CUSTOMER_URL,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    customer_id: wizardCustomerData.id,
                    technician_id: techId,
                    description: description
                },
                success: function(res) {
                    btnSubmit.prop('disabled', false);
                    btnSubmit.find('.btn-text').removeClass('d-none');
                    btnSubmit.find('.btn-loading').addClass('d-none');

                    if (res.status === 'success') {
                        $('#modal-open-ticket-wizard').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Tiket Berhasil Dibuat!',
                            html: `<div class="text-start">
                                    <p class="mb-2">${res.message}</p>
                                    <div class="p-2 bg-light rounded small mb-2">
                                        <b>ID Tiket:</b> #${res.data.ticket_id}<br>
                                        <b>Teknisi:</b> ${selectedTechnician ? selectedTechnician.name : '-'}<br>
                                        <b>Pelanggan:</b> ${wizardCustomerData.name}
                                    </div>
                                    <p class="mb-0 text-muted small">Notifikasi WhatsApp telah dikirimkan ke teknisi untuk segera ditindaklanjuti.</p>
                                   </div>`,
                            showCancelButton: true,
                            confirmButtonText: 'Buka Live Tracking GPS',
                            cancelButtonText: 'Tutup',
                            confirmButtonColor: '#3b82f6',
                            cancelButtonColor: '#64748b'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.open(res.data.tracking_url, '_blank');
                            }
                        });

                        // Reload table to reflect status
                        if (typeof table !== 'undefined' && table) {
                            table.ajax.reload(null, false);
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Membuat Tiket',
                            text: res.message || 'Terjadi kesalahan saat membuat tiket.',
                        });
                    }
                },
                error: function(xhr) {
                    btnSubmit.prop('disabled', false);
                    btnSubmit.find('.btn-text').removeClass('d-none');
                    btnSubmit.find('.btn-loading').addClass('d-none');

                    let msg = 'Gagal menyimpan tiket.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Perhatian',
                        text: msg,
                    });
                }
            });
        }

        // ==========================================
        // VIEW CUSTOMER TROUBLESHOOT NOTES HISTORY
        // ==========================================
        function viewCustomerNotes(customerId) {
            $('#customer-notes-body').html('<div class="text-center py-4"><div class="spinner-border text-primary spinner-border-sm mb-2" role="status"></div><div class="small text-muted">Memuat riwayat catatan...</div></div>');
            $('#modal-customer-notes').modal('show');

            $.ajax({
                url: `${CUSTOMER_NOTES_BASE_URL}/${customerId}/notes`,
                type: 'GET',
                headers: { 'Accept': 'application/json' },
                success: function(res) {
                    if (res.status === 'success') {
                        $('#customer-notes-subtitle').text('Pelanggan: ' + res.customer_name);
                        const body = $('#customer-notes-body');
                        body.empty();

                        if (!res.tickets || res.tickets.length === 0) {
                            body.html('<div class="text-center py-4 text-muted small"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2 opacity-50"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg><br>Belum ada riwayat catatan troubleshooting untuk pelanggan ini.</div>');
                            return;
                        }

                        let html = '<div class="d-flex flex-column gap-2">';
                        res.tickets.forEach(function(t) {
                            const dateFormatted = t.updated_at ? moment(t.updated_at).format('DD/MM/YYYY HH:mm') : '-';
                            const techName = t.technician ? t.technician.name : 'Teknisi';
                            const statusBadge = t.status === 'done'
                                ? '<span class="badge bg-success-lt text-success fw-bold" style="font-size:10px;">SELESAI</span>'
                                : '<span class="badge bg-warning-lt text-warning fw-bold" style="font-size:10px;">' + t.status.toUpperCase() + '</span>';

                            const techNotes = t.technician_notes
                                ? `<div class="p-2 bg-white rounded border border-purple-subtle mt-1 text-dark small" style="font-size:11.5px;line-height:1.4;">
                                    <div class="fw-semibold text-purple" style="font-size:10px;">Catatan Teknisi:</div>
                                    ${t.technician_notes}
                                   </div>`
                                : '<div class="text-muted small fst-italic mt-1" style="font-size:11px;">Belum ada catatan dari teknisi</div>';

                            html += `
                                <div class="card border p-2" style="background:#f8fafc;border-radius:8px;">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="fw-bold text-dark small" style="font-size:12px;">Tiket #${t.id} &middot; ${techName}</span>
                                        <div class="d-flex align-items-center gap-1">
                                            ${statusBadge}
                                            <span class="text-muted" style="font-size:10.5px;">${dateFormatted}</span>
                                        </div>
                                    </div>
                                    <div class="text-muted small" style="font-size:11px;"><b>Kendala:</b> ${t.description || '-'}</div>
                                    ${techNotes}
                                </div>
                            `;
                        });
                        html += '</div>';
                        body.html(html);
                    }
                },
                error: function() {
                    $('#customer-notes-body').html('<div class="text-danger text-center py-3 small">Gagal memuat riwayat catatan.</div>');
                }
            });
        }
    </script>
@endpush

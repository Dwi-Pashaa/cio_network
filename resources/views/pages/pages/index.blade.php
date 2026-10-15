@extends('layouts.app')

@section('title')
    Manajemen Halaman
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <style>
        /* Quick Access High Contrast Dark Command-Bar Styling */
        .quick-access-card {
            border-radius: 14px !important;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
            border: 1px solid #334155 !important;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.12) !important;
        }
        .quick-access-subtitle {
            color: #cbd5e1 !important;
            font-size: 0.82rem !important;
            font-weight: 500 !important;
        }
        .quick-access-btn {
            border-radius: 8px !important;
            font-size: 0.81rem !important;
            padding: 0.42rem 0.8rem !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.22) !important;
            background: rgba(255, 255, 255, 0.08) !important;
            transition: all 0.15s ease-in-out !important;
            text-decoration: none !important;
            backdrop-filter: blur(4px);
        }
        .quick-access-btn:hover {
            background: #4f46e5 !important;
            border-color: #6366f1 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.45) !important;
        }

        /* Professional Filter Panel Styling */
        .filter-panel-section {
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .filter-panel-section .form-label-filter {
            font-size: 0.76rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .filter-panel-section .select2-container--bootstrap-5 .select2-selection {
            font-size: 0.82rem !important;
            min-height: 36px !important;
            padding: 0.28rem 0.6rem !important;
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            background-color: #ffffff !important;
            transition: all 0.15s ease;
        }
        .filter-panel-section .select2-container--bootstrap-5 .select2-selection:focus,
        .filter-panel-section .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
        }
        .filter-panel-section .select2-container--bootstrap-5 .select2-selection__rendered {
            color: #1e293b !important;
            line-height: 1.5 !important;
            padding-left: 0.15rem !important;
        }

        /* Modal Custom Premium Styling */
        .modal-content-premium {
            border-radius: 18px !important;
            overflow: hidden;
            border: none !important;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
        }
        .modal-header-premium {
            background: linear-gradient(135deg, #1e1b4b, #4c1d95) !important;
            border: none !important;
            padding: 1.25rem 1.5rem !important;
        }
        .modal-header-premium .modal-title {
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.95rem;
        }
        .modal-header-premium .btn-close-white {
            filter: invert(1) grayscale(1) brightness(2);
        }

        .premium-card {
            border-radius: 14px !important;
            background: #ffffff;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04) !important;
        }

        /* Form styling */
        .form-label-premium {
            font-size: 0.82rem !important;
            font-weight: 600 !important;
            color: #334155 !important;
            margin-bottom: 0.35rem !important;
        }
        .form-control-premium {
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 0.88rem !important;
            padding: 0.45rem 0.75rem !important;
            color: #1e293b !important;
        }
        .form-control-premium:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15) !important;
        }

        /* Custom Checklist Box Component */
        .checklist-group-card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #ffffff;
            padding: 1rem 1.15rem;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
            transition: all 0.2s ease;
        }
        .checklist-group-card:focus-within {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }
        .checklist-group-card.border-danger {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12) !important;
        }
        .checklist-scrollbox {
            max-height: 220px;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.6rem;
            background: #f8fafc;
        }
        .checklist-scrollbox::-webkit-scrollbar { width: 6px; }
        .checklist-scrollbox::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .checklist-scrollbox::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }
        .checklist-scrollbox::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }
        .checklist-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            cursor: pointer;
            margin-bottom: 4px;
            transition: all 0.15s ease;
            user-select: none;
            background: #ffffff;
            border: 1px solid #edf2f7;
        }
        .checklist-item:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
        .checklist-item.item-checked {
            background: #eef2ff !important;
            border-color: #a5b4fc !important;
            color: #3730a3 !important;
            font-weight: 500;
        }
        .checklist-item .form-check-input {
            cursor: pointer;
            width: 1.15rem;
            height: 1.15rem;
            margin: 0;
            flex-shrink: 0;
            border-radius: 4px;
        }
        .checklist-search-input {
            font-size: 0.82rem !important;
            padding: 0.45rem 0.85rem !important;
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            background: #ffffff !important;
        }
        .checklist-search-input:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
        }
        .counter-badge {
            font-size: 0.73rem !important;
            font-weight: 700;
            padding: 0.25rem 0.65rem;
            border-radius: 20px;
            background: #e0e7ff;
            color: #4338ca;
            white-space: nowrap;
            border: 1px solid #c7d2fe;
        }
        .btn-checklist-action {
            font-size: 0.76rem;
            padding: 0.25rem 0.65rem;
            border-radius: 6px;
            color: #4f46e5;
            font-weight: 600;
            cursor: pointer;
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            transition: all 0.15s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        .btn-checklist-action:hover {
            background: #4f46e5;
            color: #ffffff;
            text-decoration: none;
        }
        .btn-checklist-clear {
            color: #dc2626 !important;
            background: #fef2f2 !important;
            border-color: #fecaca !important;
        }
        .btn-checklist-clear:hover {
            background: #dc2626 !important;
            color: #ffffff !important;
        }

        /* Dedicated VLAN Card Styling */
        .vlan-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 0.65rem;
            padding: 0.2rem;
        }
        .vlan-card-item {
            display: flex;
            align-items: flex-start;
            padding: 0.8rem 0.95rem !important;
            border-radius: 10px !important;
            background: #ffffff !important;
            border: 1.5px solid #e2e8f0 !important;
            margin-bottom: 0 !important;
            min-height: 74px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            position: relative;
        }
        .vlan-card-item:hover {
            background: #f8fafc !important;
            border-color: #a5b4fc !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1) !important;
        }
        .vlan-card-item.item-checked {
            background: linear-gradient(135deg, #f8faff 0%, #eef2ff 100%) !important;
            border-color: #6366f1 !important;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.18) !important;
        }
        .vlan-card-item .form-check-input {
            width: 1.25rem !important;
            height: 1.25rem !important;
            border-radius: 5px !important;
            flex-shrink: 0;
            margin-top: 0.25rem;
            margin-right: 0.75rem;
            accent-color: #6366f1;
        }
        .vlan-tag-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.55rem;
            border-radius: 6px;
            font-size: 0.76rem;
            font-weight: 700;
            background: #eef2ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
            letter-spacing: 0.2px;
            white-space: nowrap;
        }
        .vlan-card-item.item-checked .vlan-tag-pill {
            background: #4338ca;
            color: #ffffff;
            border-color: #4338ca;
        }
        .feature-pill {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 0.15rem 0.45rem;
            border-radius: 4px;
            letter-spacing: 0.2px;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .pill-pppoe {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .pill-voucher {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }
        .vlan-meta-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.73rem;
            color: #64748b;
            background: #f1f5f9;
            padding: 0.18rem 0.48rem;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .is-invalid ~ .invalid-feedback,
        select.is-invalid ~ .invalid-feedback {
            display: block !important;
            color: #ef4444;
            font-size: 0.78rem !important;
            font-weight: 500;
            margin-top: 0.25rem;
        }

        /* Stepper Navigation */
        .wizard-stepper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            max-width: 820px;
            margin: 0 auto;
            padding: 0.5rem 0.5rem 0.25rem;
        }
        .wizard-stepper-line {
            position: absolute;
            top: 22px;
            left: 8%;
            right: 8%;
            height: 3px;
            background: #e2e8f0;
            z-index: 1;
            border-radius: 2px;
        }
        .wizard-step-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            cursor: pointer;
            background: transparent;
            user-select: none;
        }
        .wizard-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #ffffff;
            color: #64748b;
            border: 2px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.4rem;
            transition: all 0.25s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }
        .wizard-circle svg {
            width: 18px;
            height: 18px;
            stroke-width: 2;
            stroke: currentColor;
            display: block;
            transition: all 0.25s ease;
        }
        .wizard-step-node.active .wizard-circle {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25);
        }
        .wizard-step-node.active .wizard-circle svg {
            stroke: #ffffff;
            color: #ffffff;
        }
        .wizard-step-node.completed .wizard-circle {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: #ffffff;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.18);
        }
        .wizard-step-node.completed .wizard-circle svg {
            stroke: #ffffff;
            color: #ffffff;
        }
        .wizard-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            transition: color 0.2s ease;
        }
        .wizard-step-node.active .wizard-label { color: #4f46e5; font-weight: 700; }
        .wizard-step-node.completed .wizard-label { color: #059669; }

        /* Choice Cards */
        .choice-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem 1.15rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            background: #ffffff;
            position: relative;
            user-select: none;
        }
        .choice-card:hover {
            border-color: #a5b4fc;
            background: #fafbff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.08);
        }
        .choice-card.selected,
        .choice-card:has(input:checked) {
            border-color: #6366f1;
            background: linear-gradient(135deg, #f8faff 0%, #eef2ff 100%);
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.15);
        }
        .choice-card * {
            pointer-events: none;
        }
        .choice-card input[type="radio"],
        .choice-card input[type="checkbox"] {
            pointer-events: auto;
            cursor: pointer;
        }
        .choice-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Summary Preview Box */
        .preview-summary-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem 1.25rem;
        }
        .preview-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 0.86rem;
        }
        .preview-item:last-child { border-bottom: none; }
        .preview-label { color: #64748b; font-weight: 500; min-width: 140px; }
        .preview-val { color: #1e293b; font-weight: 600; text-align: right; }

        /* Nav tab styling */
        .halaman-nav-tabs .nav-link {
            font-size: 0.9rem;
            font-weight: 600;
            color: #64748b;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 0.75rem 1.25rem;
            background: transparent;
            cursor: pointer;
        }
        .halaman-nav-tabs .nav-link:hover {
            color: #4f46e5;
        }
        .halaman-nav-tabs .nav-link.active {
            color: #4f46e5 !important;
            border-bottom: 3px solid #4f46e5 !important;
            font-weight: 700;
        }
    </style>
@endpush

@section('content')
    {{-- ======================================================== --}}
    {{-- BARIS TOMBOL AKSES CEPAT (QUICK ACCESS BAR)             --}}
    {{-- ======================================================== --}}
    @can('lihat akses cepat halaman')
        @if (!empty($quickAccessItems))
            <div class="card mb-3 quick-access-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary text-white px-2 py-1 fw-bold" style="font-size: 0.8rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" /></svg>
                                Pintas Akses Cepat
                            </span>
                            <span class="quick-access-subtitle">Shortcut menu perangkat, jaringan, stock & layanan</span>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($quickAccessItems as $qa)
                            @can($qa['permission'])
                                @if (Route::has($qa['route']))
                                    <a href="{{ route($qa['route']) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm quick-access-btn d-inline-flex align-items-center gap-1 shadow-none" title="{{ $qa['title'] }} (Buka di tab baru)">
                                        {!! $qa['icon'] !!}
                                        <span class="fw-medium">{{ $qa['title'] }}</span>
                                    </a>
                                @endif
                            @endcan
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endcan

    <div class="org-card">
        <div class="org-header pb-2">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                        <path d="M9 17h6" />
                        <path d="M9 13h6" />
                    </svg>
                </div>
                <div>
                    <h3 class="org-title">Manajemen Halaman</h3>
                    <p class="org-subtitle mb-0">Kelola konfigurasi halaman layanan pelanggan</p>
                </div>
            </div>

            <div class="org-actions d-flex align-items-center gap-2">
                @can('buat halaman lengkap')
                    <a href="javascript:void(0)" id="btnAddWizard" class="btn-add">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14m-7-7h14" />
                        </svg>
                        Tambah Halaman Baru
                    </a>
                @endcan
            </div>
        </div>

        {{-- Professional Filter Panel --}}
        <div class="filter-panel-section px-4 pt-3 pb-3">
            <div class="d-flex align-items-center justify-content-between mb-2.5 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 28px; height: 28px; border-radius: 7px; background: rgba(99, 102, 241, 0.12); color: #4f46e5; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.828 4.828a2 2 0 0 0 -.586 1.414v4.172a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-4.172a2 2 0 0 0 -.586 -1.414l-4.828 -4.828a2 2 0 0 1 -.586 -1.414v-2.172z" /></svg>
                    </div>
                    <div>
                        <span class="fw-bold text-dark" style="font-size: 0.88rem;">Filter Data Halaman</span>
                        <span class="text-muted ms-1 small" style="font-size: 0.76rem;">(Saring berdasarkan VLAN & Wilayah)</span>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-2" id="badge-active-filters" style="font-size: 0.72rem; display: none;">0 Filter Aktif</span>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1.5 px-2.5 py-1" id="btn-reset-filters" style="font-size: 0.78rem; border-radius: 6px; font-weight: 500;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -5v5h5" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 5v-5h-5" /></svg>
                        Reset Filter
                    </button>
                </div>
            </div>

            <div class="row g-2">
                {{-- 1. VLAN --}}
                <div class="col-lg col-md-4 col-sm-6">
                    <label class="form-label-filter" for="filter_vlan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polygon points="12 2 2 7 12 12 22 7 12 2" /><polyline points="2 17 12 22 22 17" /><polyline points="2 12 12 17 22 12" /></svg>
                        VLAN
                    </label>
                    <select name="filter_vlan" id="filter_vlan" class="form-select filter-select" data-placeholder="Semua VLAN">
                        <option value="">Semua VLAN</option>
                        @foreach ($vlans as $vln)
                            <option value="{{ $vln->id }}">{{ $vln->name }}{{ $vln->server_name ? ' (' . $vln->server_name . ')' : '' }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 2. Kabupaten / Kota --}}
                <div class="col-lg col-md-4 col-sm-6">
                    <label class="form-label-filter" for="filter_regency">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-info"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                        Kab / Kota
                    </label>
                    <select name="filter_regency" id="filter_regency" class="form-select filter-select" data-placeholder="Semua Kab/Kota">
                        <option value="">Semua Kab/Kota</option>
                        @foreach ($regencies as $reg)
                            <option value="{{ $reg->id }}">{{ $reg->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 3. Kecamatan --}}
                <div class="col-lg col-md-4 col-sm-6">
                    <label class="form-label-filter" for="filter_district">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-success"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /></svg>
                        Kecamatan
                    </label>
                    <select name="filter_district" id="filter_district" class="form-select filter-select" data-placeholder="Semua Kecamatan">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($districts as $dst)
                            <option value="{{ $dst->id }}" data-regency="{{ $dst->regencie_id }}">{{ $dst->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 4. Desa --}}
                <div class="col-lg col-md-4 col-sm-6">
                    <label class="form-label-filter" for="filter_village">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-warning"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="21" x2="21" y2="21" /><line x1="9" y1="8" x2="10" y2="8" /><line x1="9" y1="12" x2="10" y2="12" /><line x1="9" y1="16" x2="10" y2="16" /><line x1="14" y1="8" x2="15" y2="8" /><line x1="14" y1="12" x2="15" y2="12" /><line x1="14" y1="16" x2="15" y2="16" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg>
                        Desa
                    </label>
                    <select name="filter_village" id="filter_village" class="form-select filter-select" data-placeholder="Semua Desa">
                        <option value="">Semua Desa</option>
                        @foreach ($villages as $vlg)
                            <option value="{{ $vlg->id }}" data-regency="{{ $vlg->regencie_id }}" data-district="{{ $vlg->district_id }}">{{ $vlg->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 5. Kampung --}}
                <div class="col-lg col-md-4 col-sm-6">
                    <label class="form-label-filter" for="filter_hometown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                        Kampung
                    </label>
                    <select name="filter_hometown" id="filter_hometown" class="form-select filter-select" data-placeholder="Semua Kampung">
                        <option value="">Semua Kampung</option>
                        @foreach ($hometown as $ht)
                            <option value="{{ $ht->id }}" data-regency="{{ $ht->regencie_id }}" data-district="{{ $ht->district_id }}">{{ $ht->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 6. Organisasi (Jika punya hak akses) --}}
                @if (auth()->user()->hasPermissionTo('filter organization'))
                    <div class="col-lg col-md-4 col-sm-6">
                        <label class="form-label-filter" for="filter-organization">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="7" r="4" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            Organisasi
                        </label>
                        <select id="filter-organization" name="filter_organization" class="form-select filter-select" data-placeholder="Semua Organisasi">
                            <option value="">Semua Organisasi</option>
                            @foreach ($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sub-Toolbar: Entries & Search --}}
        <div class="org-toolbar d-flex align-items-center justify-content-between flex-wrap gap-2 py-2.5 px-4">
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted" style="font-size: 0.85rem;">Tampilkan</span>
                <select name="sort" id="sort" class="org-input" style="width: 75px; padding: 0.35rem 0.7rem; font-size: 0.85rem;">
                    @php $opts = [10, 25, 50, 100]; @endphp
                    @foreach ($opts as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
                <span class="text-muted" style="font-size: 0.85rem;">entri per halaman</span>
            </div>
            <div class="search-wrapper ms-auto" style="max-width: 320px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="text" class="org-input" id="search-input" placeholder="Cari halaman, vlan, telp..." autocomplete="off">
            </div>
        </div>

        <div id="halaman-table-wrapper" class="table-responsive">
            <table class="table org-table table-vcenter text-nowrap" id="halaman-table">
                <thead>
                    <tr id="table-header-row">
                        <th class="w-1">No</th>
                        @can('lihat kolom halaman nama')
                            <th>Nama Halaman</th>
                        @endcan
                        @can('lihat kolom halaman vlan')
                            <th>Nama VLAN</th>
                        @endcan
                        @can('lihat kolom halaman server')
                            <th>Nama Server</th>
                        @endcan
                        @can('lihat kolom halaman ip address')
                            <th>IP Address</th>
                        @endcan
                        @can('lihat kolom halaman support')
                            <th>Support</th>
                        @endcan
                        @can('lihat kolom halaman persetujuan')
                            <th>Persetujuan</th>
                        @endcan
                        @can('lihat kolom halaman foto ktp')
                            <th>Foto KTP</th>
                        @endcan
                        @can('lihat kolom halaman cek kontak')
                            <th>Cek Kontak</th>
                        @endcan
                        @can('lihat kolom halaman wilayah')
                            <th>Wilayah / Lokasi</th>
                        @endcan
                        @can('lihat kolom halaman olt')
                            <th>OLT</th>
                        @endcan
                        @can('lihat kolom halaman mix radius')
                            <th>Mix Radius</th>
                        @endcan
                        @can('lihat kolom halaman tipe paket')
                            <th>Tipe Paket</th>
                        @endcan
                        @can('lihat kolom halaman tipe pembayaran')
                            <th>Tipe Pembayaran</th>
                        @endcan
                        @can('lihat kolom halaman user')
                            <th>User Ditugaskan</th>
                        @endcan
                        <th>Action</th>
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
@endsection

@push('modal')
    {{-- ======================================================== --}}
    {{-- 1. MODAL STEP WIZARD 4-STEP: TAMBAH HALAMAN LENGKAP      --}}
    {{-- ======================================================== --}}
    <div class="modal modal-blur fade" id="modal-wizard" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content modal-content-premium">
                <div class="modal-header modal-header-premium">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="6" height="6" rx="1" /><rect x="14" y="4" width="6" height="6" rx="1" /><rect x="4" y="14" width="6" height="6" rx="1" /><rect x="14" y="14" width="6" height="6" rx="1" /></svg>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0">Tambah Halaman Baru (Step Wizard)</h5>
                            <span class="text-white-50 small" id="wizard-step-subtitle">Step 1 dari 4: Konfigurasi VLAN</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Stepper Indicators --}}
                <div class="px-4 py-3 bg-white border-bottom">
                    <div class="wizard-stepper">
                        <div class="wizard-stepper-line"></div>
                        <div class="wizard-step-node active" id="wz-nav-1" data-step="1">
                            <div class="wizard-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="15" width="6" height="6" rx="2" /><rect x="15" y="15" width="6" height="6" rx="2" /><rect x="9" y="3" width="6" height="6" rx="2" /><path d="M6 15v-1a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v1" /><line x1="12" y1="9" x2="12" y2="12" /></svg>
                            </div>
                            <span class="wizard-label">1. VLAN</span>
                        </div>
                        <div class="wizard-step-node" id="wz-nav-2" data-step="2">
                            <div class="wizard-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                            </div>
                            <span class="wizard-label">2. Info & Layanan</span>
                        </div>
                        <div class="wizard-step-node" id="wz-nav-3" data-step="3">
                            <div class="wizard-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="18" height="8" rx="3" /><rect x="3" y="12" width="18" height="8" rx="3" /><line x1="7" y1="8" x2="7" y2="8.01" /><line x1="7" y1="16" x2="7" y2="16.01" /><path d="M11 12h6" /></svg>
                            </div>
                            <span class="wizard-label">3. Jaringan & Paket</span>
                        </div>
                        <div class="wizard-step-node" id="wz-nav-4" data-step="4">
                            <div class="wizard-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                            </div>
                            <span class="wizard-label">4. Preview</span>
                        </div>
                    </div>
                </div>

                <div class="modal-body p-4" style="background-color: #f8fafc; max-height: calc(85vh - 180px); overflow-y: auto;">
                    <form id="form-wizard" autocomplete="off">
                        @csrf
                        <input type="hidden" name="vlan_mode" id="wz_vlan_mode" value="existing">

                        {{-- STEP 1: VLAN (Pilih VLAN yang Ada / Buat VLAN Baru) --}}
                        <div class="wizard-step-content" id="wz-step-1">
                            @can('input halaman vlan')
                            {{-- Checklist VLAN yang Ada --}}
                            <div id="section-vlan-existing">
                                <div class="checklist-group-card" id="card_wz_vlans_id" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 1.15rem;">
                                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom flex-wrap gap-2">
                                        <div>
                                            <label class="form-label-premium mb-0 fw-bold d-flex align-items-center gap-2 text-dark">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="15" width="6" height="6" rx="2" /><rect x="15" y="15" width="6" height="6" rx="2" /><rect x="9" y="3" width="6" height="6" rx="2" /><path d="M6 15v-1a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v1" /><line x1="12" y1="9" x2="12" y2="12" /></svg>
                                                Pilih Data Segmen VLAN <span class="text-danger">*</span>
                                            </label>
                                            <small class="text-muted" style="font-size: 0.75rem;">Pilih segmen VLAN untuk melihat detail konfigurasi dan sinkronisasi otomatis ke langkah berikutnya</small>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="counter-badge" id="badge-wz_vlans_id">0 dipilih</span>
                                            <button type="button" class="btn-checklist-action btn-checklist-all" data-target="wz_vlans_id">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 12l5 5l10 -10" /><path d="M2 12l5 5m5 -5l5 -5" /></svg> Semua
                                            </button>
                                            <span class="text-muted" style="font-size: 0.72rem;">|</span>
                                            <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="wz_vlans_id">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg> Batal
                                            </button>
                                        </div>
                                    </div>

                                    <div class="input-group input-group-flat mb-3">
                                        <span class="input-group-text bg-light border-end-0 text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" /></svg>
                                        </span>
                                        <input type="text" class="form-control checklist-search-input bg-light border-start-0 ps-0" placeholder="Cari nomor VLAN, nama VLAN, server, IP address, atau wilayah..." data-target="wz_vlans_id">
                                    </div>

                                    <div class="checklist-scrollbox" id="checklist-box-wz_vlans_id" style="max-height: 290px; overflow-y: auto;">
                                        <div class="vlan-card-grid">
                                            @foreach ($vlans as $vln)
                                                <label class="vlan-card-item checklist-item" id="wz-vlan-card-{{ $vln->id }}" data-search="{{ strtolower($vln->name . ' ' . ($vln->vlan_name ?? '') . ' ' . ($vln->server_name ?? '') . ' ' . ($vln->ip_address ?? '') . ' ' . ($vln->district->name ?? '') . ' ' . ($vln->regencie->name ?? '')) }}">
                                                    <div class="d-flex align-items-start gap-2 w-100">
                                                        <input type="checkbox" name="vlans_id[]" class="form-check-input wz-vlan-cb mt-1" value="{{ $vln->id }}" data-group="wz_vlans_id">
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <div class="d-flex align-items-center justify-content-between gap-1 mb-1">
                                                                <div class="d-flex align-items-center gap-1.5 overflow-hidden flex-wrap">
                                                                    <span class="vlan-tag-pill">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="15" width="6" height="6" rx="2" /><rect x="15" y="15" width="6" height="6" rx="2" /><rect x="9" y="3" width="6" height="6" rx="2" /><path d="M6 15v-1a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v1" /><line x1="12" y1="9" x2="12" y2="12" /></svg>
                                                                        <span class="vlan-card-num-text">VLAN {{ $vln->name }}</span>
                                                                    </span>
                                                                    <span class="checklist-text d-none">{{ $vln->name }}</span>
                                                                    @if($vln->vlan_name)
                                                                        <span class="badge bg-purple-subtle text-purple border border-purple-subtle vlan-card-vname-text" style="font-size: 0.72rem; padding: 2px 6px;">{{ $vln->vlan_name }}</span>
                                                                    @endif
                                                                    <span class="fw-bold text-dark text-truncate d-flex align-items-center gap-1 vlan-card-server-text" style="font-size: 0.83rem;" title="{{ $vln->server_name ?? ('#' . $vln->name) }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="18" height="8" rx="2" /><rect x="3" y="12" width="18" height="8" rx="2" /><line x1="7" y1="8" x2="7" y2="8.01" /><line x1="7" y1="16" x2="7" y2="16.01" /></svg>
                                                                        {{ $vln->server_name ?? ('#' . $vln->name) }}
                                                                    </span>
                                                                </div>
                                                                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                                                    @if($vln->support_pppoe)
                                                                        <span class="feature-pill pill-pppoe" title="Mendukung Layanan PPPoE">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-0.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="13 3 13 10 19 10 11 21 11 14 5 14 13 3" /></svg>PPPoE
                                                                        </span>
                                                                    @endif
                                                                    @if($vln->support_voucher)
                                                                        <span class="feature-pill pill-voucher" title="Mendukung Layanan Hotspot / Voucher">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-0.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="15" y1="5" x2="15" y2="7" /><line x1="15" y1="11" x2="15" y2="13" /><line x1="15" y1="17" x2="15" y2="19" /><path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" /></svg>Voucher
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-1.5 flex-wrap pt-1 border-top" style="border-color: #f1f5f9 !important;">
                                                                @if($vln->ip_address)
                                                                    <span class="vlan-meta-tag" title="IP Address">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="3.6" y1="9" x2="20.4" y2="9" /><line x1="3.6" y1="15" x2="20.4" y2="15" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg>
                                                                        {{ $vln->ip_address }}
                                                                    </span>
                                                                @endif
                                                                @if($vln->district || $vln->regencie)
                                                                    <span class="vlan-meta-tag" title="Wilayah Regional">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="11" r="3" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
                                                                        {{ $vln->district->name ?? $vln->regencie->name }}
                                                                    </span>
                                                                @endif
                                                                @if($vln->pakets && $vln->pakets->count() > 0)
                                                                    <span class="vlan-meta-tag" title="Paket Terhubung">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-success"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /></svg>
                                                                        {{ $vln->pakets->count() }} Paket
                                                                    </span>
                                                                @endif
                                                                @if($vln->olts && $vln->olts->count() > 0)
                                                                    <span class="vlan-meta-tag" title="OLT Terhubung">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-info"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="13" width="18" height="8" rx="2" /><line x1="17" y1="17" x2="17" y2="17.01" /><line x1="13" y1="17" x2="13" y2="17.01" /><line x1="15" y1="13" x2="15" y2="5" /></svg>
                                                                        {{ $vln->olts->count() }} OLT
                                                                    </span>
                                                                @endif
                                                                @if(!$vln->ip_address && !$vln->district && !$vln->regencie && (!$vln->pakets || $vln->pakets->count() == 0))
                                                                    <span class="vlan-meta-tag text-muted" style="font-size:0.71rem;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                                                                        Profil VLAN Siap Pakai
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback error_vlans_id"></span>

                                {{-- Container Detail VLAN Terpilih --}}
                                <div id="wz-vlan-details-container" class="mt-3 d-none"></div>
                            </div>
                            @else
                            <div class="alert alert-warning d-flex align-items-center gap-2 mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                                <span>Anda tidak memiliki izin untuk mengonfigurasi VLAN. Silakan lanjutkan ke langkah berikutnya.</span>
                            </div>
                            @endcan
                        </div>

                        {{-- STEP 2: Info & Dukungan Layanan --}}
                        <div class="wizard-step-content d-none" id="wz-step-2">
                            <div class="row g-3">
                                @can('input halaman judul')
                                <div class="col-md-6">
                                    <label class="form-label-premium">Judul Halaman <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="wz_name" class="form-control form-control-premium" placeholder="Masukkan judul halaman...">
                                    <span class="invalid-feedback error_name"></span>
                                </div>
                                @endcan
                                @can('input halaman deskripsi')
                                <div class="col-md-6">
                                    <label class="form-label-premium">Sub Judul / Deskripsi <span class="text-danger">*</span></label>
                                    <input type="text" name="desc" id="wz_desc" class="form-control form-control-premium" placeholder="Masukkan deskripsi singkat...">
                                    <span class="invalid-feedback error_desc"></span>
                                </div>
                                @endcan

                                {{-- Row 2: Dukungan Layanan & Tipe Pelanggan --}}
                                @can('input halaman support')
                                <div class="col-md-6">
                                    <div class="premium-card p-3 h-100 d-flex flex-column justify-content-between" id="card_support_layanan">
                                        <label class="form-label-premium fw-bold mb-2 d-flex align-items-center text-dark">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-primary me-2 flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polygon points="12 2 2 7 12 12 22 7 12 2" /><polyline points="2 17 12 22 22 17" /><polyline points="2 12 12 17 22 12" /></svg>
                                            <span>Dukungan Layanan Halaman <span class="text-danger">*</span></span>
                                        </label>
                                        <div class="row g-2 pt-1">
                                            <div class="col-6">
                                                <label class="checklist-item d-flex align-items-center gap-2 p-2 px-3 bg-white border rounded cursor-pointer w-100 mb-0" id="card_chk_pppoe">
                                                    <input type="checkbox" name="support_pppoe" id="wz_support_pppoe" value="1" class="form-check-input m-0 flex-shrink-0" checked>
                                                    <span class="fw-bold d-inline-flex align-items-center gap-1.5 text-dark" style="font-size:0.86rem;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-primary flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="13 3 13 10 19 10 11 21 11 14 5 14 13 3" /></svg>
                                                        Support PPPoE
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-6">
                                                <label class="checklist-item d-flex align-items-center gap-2 p-2 px-3 bg-white border rounded cursor-pointer w-100 mb-0" id="card_chk_voucher">
                                                    <input type="checkbox" name="support_voucher" id="wz_support_voucher" value="1" class="form-check-input m-0 flex-shrink-0" checked>
                                                    <span class="fw-bold d-inline-flex align-items-center gap-1.5 text-dark" style="font-size:0.86rem;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-warning flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="15" y1="5" x2="15" y2="7" /><line x1="15" y1="11" x2="15" y2="13" /><line x1="15" y1="17" x2="15" y2="19" /><path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" /></svg>
                                                        Support Voucher
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <span class="invalid-feedback error_support_layanan"></span>
                                    </div>
                                </div>
                                @endcan

                                @can('input halaman tipe pelanggan')
                                <div class="col-md-6">
                                    <div class="checklist-group-card p-3 bg-light border rounded h-100 d-flex flex-column justify-content-between" id="card_wz_tipe_pelanggan_id">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <label class="form-label-premium mb-0 d-flex align-items-center text-dark fw-bold">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-info me-2 flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                                                <span>Tipe Pelanggan <span class="text-danger">*</span></span>
                                            </label>
                                            <span class="counter-badge" id="badge-wz_tipe_pelanggan_id">0 dipilih</span>
                                        </div>
                                        <div class="row g-2 pt-1" id="checklist-box-wz_tipe_pelanggan_id">
                                            @foreach ($tipePelanggan as $tp)
                                                <div class="col-6">
                                                    <label class="checklist-item d-flex align-items-center gap-2 p-2 px-3 bg-white border rounded cursor-pointer w-100 mb-0" data-search="{{ strtolower($tp->name) }}">
                                                        <input type="checkbox" name="tipe_pelanggan_id[]" class="form-check-input m-0 flex-shrink-0" value="{{ $tp->id }}" data-group="wz_tipe_pelanggan_id">
                                                        <span class="checklist-text fw-bold text-dark" style="font-size: 0.86rem;">{{ $tp->name }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <span class="invalid-feedback error_tipe_pelanggan_id"></span>
                                </div>
                                @endcan

                                {{-- Area Regional --}}
                                @can('input halaman wilayah')
                                <div class="col-md-3">
                                    <label class="form-label-premium">Kabupaten/Kota <span class="text-danger">*</span></label>
                                    <select name="regencies_id" id="wz_regencies_id" class="form-select form-control-premium wz-regency-select" data-prefix="wz">
                                        <option value="">-- Pilih Kabupaten --</option>
                                        @foreach ($regencies as $reg)
                                            <option value="{{ $reg->id }}">{{ $reg->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback error_regencies_id"></span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-premium">Kecamatan <span class="text-danger">*</span></label>
                                    <select name="districts_id" id="wz_districts_id" class="form-select form-control-premium wz-district-select" data-prefix="wz">
                                        <option value="">-- Pilih Kecamatan --</option>
                                    </select>
                                    <span class="invalid-feedback error_districts_id"></span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-premium">Desa <span class="text-danger">*</span></label>
                                    <select name="villages_id" id="wz_villages_id" class="form-select form-control-premium">
                                        <option value="">-- Pilih Desa --</option>
                                    </select>
                                    <span class="invalid-feedback error_villages_id"></span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-premium">Kampung <span class="text-danger">*</span></label>
                                    <select name="hometowns_id" id="wz_hometowns_id" class="form-select form-control-premium">
                                        <option value="">-- Pilih Kampung --</option>
                                    </select>
                                    <span class="invalid-feedback error_hometowns_id"></span>
                                </div>
                                @endcan

                                {{-- Opsi Validasi & Kebijakan Halaman --}}
                                @can('input halaman opsi ktp')
                                <div class="col-md-4">
                                    <label class="form-label-premium">Opsi Upload KTP <span class="text-danger">*</span></label>
                                    <select name="is_ktp" id="wz_is_ktp" class="form-select form-control-premium">
                                        <option value="aktif">Aktif</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                    <span class="invalid-feedback error_is_ktp"></span>
                                </div>
                                @endcan
                                @can('input halaman opsi persetujuan')
                                <div class="col-md-4">
                                    <label class="form-label-premium">Opsi Persetujuan & TTD <span class="text-danger">*</span></label>
                                    <select name="is_persetujuan" id="wz_is_persetujuan" class="form-select form-control-premium">
                                        <option value="aktif">Aktif</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                    <span class="invalid-feedback error_is_persetujuan"></span>
                                </div>
                                @endcan
                                @can('input halaman opsi cek kontak')
                                <div class="col-md-4">
                                    <label class="form-label-premium">Pengecekan Email & No HP <span class="text-danger">*</span></label>
                                    <select name="is_verification" id="wz_is_verification" class="form-select form-control-premium">
                                        <option value="aktif">Aktif (Wajib Cek)</option>
                                        <option value="tidak">Tidak Aktif (Tanpa Cek)</option>
                                    </select>
                                    <span class="invalid-feedback error_is_verification"></span>
                                </div>
                                @endcan
                            </div>
                        </div>

                        {{-- STEP 3: Jaringan & Layanan + User Ditugaskan --}}
                        <div class="wizard-step-content d-none" id="wz-step-3">
                            <div class="row g-3">
                                {{-- ODC --}}
                                @can('input halaman odc')
                                <div class="col-md-6">
                                    <div class="checklist-group-card" id="card_wz_odcs_id">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <label class="form-label-premium mb-0">ODC Network <span class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="counter-badge" id="badge-wz_odcs_id">0 dipilih</span>
                                                <button type="button" class="btn-checklist-action btn-checklist-all" data-target="wz_odcs_id">Semua</button>
                                                <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="wz_odcs_id">Batal</button>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari ODC..." data-target="wz_odcs_id">
                                        <div class="checklist-scrollbox" id="checklist-box-wz_odcs_id">
                                            @foreach ($odcs as $odc)
                                                <label class="checklist-item" data-search="{{ strtolower($odc->home_odc . ' ' . $odc->code) }}">
                                                    <input type="checkbox" name="odcs_id[]" class="form-check-input" value="{{ $odc->id }}" data-group="wz_odcs_id">
                                                    <span class="checklist-text">{{ $odc->home_odc }} ({{ $odc->code }})</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <span class="invalid-feedback error_odcs_id"></span>
                                </div>
                                @endcan

                                {{-- ODP --}}
                                @can('input halaman odp')
                                <div class="col-md-6">
                                    <div class="checklist-group-card" id="card_wz_odps_id">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <label class="form-label-premium mb-0">ODP Network <span class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="counter-badge" id="badge-wz_odps_id">0 dipilih</span>
                                                <button type="button" class="btn-checklist-action btn-checklist-all" data-target="wz_odps_id">Semua</button>
                                                <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="wz_odps_id">Batal</button>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari ODP..." data-target="wz_odps_id">
                                        <div class="checklist-scrollbox" id="checklist-box-wz_odps_id">
                                            @foreach ($odps as $odp)
                                                <label class="checklist-item" data-search="{{ strtolower($odp->home_odc . ' ' . $odp->code) }}">
                                                    <input type="checkbox" name="odps_id[]" class="form-check-input" value="{{ $odp->id }}" data-group="wz_odps_id">
                                                    <span class="checklist-text">{{ $odp->home_odc }} ({{ $odp->code }})</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <span class="invalid-feedback error_odps_id"></span>
                                </div>
                                @endcan

                                {{-- OLT --}}
                                @can('input halaman olt')
                                <div class="col-md-6">
                                    <div class="checklist-group-card" id="card_wz_olts_id">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <label class="form-label-premium mb-0">OLT Network <span class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="counter-badge" id="badge-wz_olts_id">0 dipilih</span>
                                                <button type="button" class="btn-checklist-action btn-checklist-all" data-target="wz_olts_id">Semua</button>
                                                <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="wz_olts_id">Batal</button>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari OLT..." data-target="wz_olts_id">
                                        <div class="checklist-scrollbox" id="checklist-box-wz_olts_id">
                                            @foreach ($olts as $ol)
                                                <label class="checklist-item" data-search="{{ strtolower($ol->name . ' ' . $ol->code) }}">
                                                    <input type="checkbox" name="olts_id[]" class="form-check-input" value="{{ $ol->id }}" data-group="wz_olts_id">
                                                    <span class="checklist-text">{{ $ol->name }} ({{ $ol->code }})</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <span class="invalid-feedback error_olts_id"></span>
                                </div>
                                @endcan

                                {{-- Mic Radius --}}
                                @can('input halaman mic radius')
                                <div class="col-md-6">
                                    <div class="checklist-group-card" id="card_wz_mic_radius_id">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <label class="form-label-premium mb-0">Mic Radius <span class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="counter-badge" id="badge-wz_mic_radius_id">0 dipilih</span>
                                                <button type="button" class="btn-checklist-action btn-checklist-all" data-target="wz_mic_radius_id">Semua</button>
                                                <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="wz_mic_radius_id">Batal</button>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari Radius..." data-target="wz_mic_radius_id">
                                        <div class="checklist-scrollbox" id="checklist-box-wz_mic_radius_id">
                                            @foreach ($micRadius as $mc)
                                                <label class="checklist-item" data-search="{{ strtolower($mc->name . ' ' . $mc->code) }}">
                                                    <input type="checkbox" name="mic_radius_id[]" class="form-check-input" value="{{ $mc->id }}" data-group="wz_mic_radius_id">
                                                    <span class="checklist-text">{{ $mc->name }} ({{ $mc->code }})</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <span class="invalid-feedback error_mic_radius_id"></span>
                                </div>
                                @endcan

                                {{-- Paket --}}
                                @can('input halaman paket')
                                <div class="col-md-6">
                                    <div class="checklist-group-card" id="card_wz_paket_id">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <label class="form-label-premium mb-0">Tipe Paket <span class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="counter-badge" id="badge-wz_paket_id">0 dipilih</span>
                                                <button type="button" class="btn-checklist-action btn-checklist-all" data-target="wz_paket_id">Semua</button>
                                                <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="wz_paket_id">Batal</button>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari paket..." data-target="wz_paket_id">
                                        <div class="checklist-scrollbox" id="checklist-box-wz_paket_id">
                                            @foreach ($paket as $pkt)
                                                <label class="checklist-item" data-search="{{ strtolower($pkt->name) }}">
                                                    <input type="checkbox" name="paket_id[]" class="form-check-input" value="{{ $pkt->id }}" data-group="wz_paket_id">
                                                    <span class="checklist-text">{{ $pkt->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <span class="invalid-feedback error_paket_id"></span>
                                </div>
                                @endcan

                                {{-- Price --}}
                                @can('input halaman pembayaran')
                                <div class="col-md-6">
                                    <div class="checklist-group-card" id="card_wz_price">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <label class="form-label-premium mb-0">Tipe Pembayaran / Harga <span class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="counter-badge" id="badge-wz_price">0 dipilih</span>
                                                <button type="button" class="btn-checklist-action btn-checklist-all" data-target="wz_price">Semua</button>
                                                <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="wz_price">Batal</button>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari harga/pembayaran..." data-target="wz_price">
                                        <div class="checklist-scrollbox" id="checklist-box-wz_price">
                                            @foreach ($price as $prc)
                                                <label class="checklist-item" data-search="{{ strtolower($prc->name) }}">
                                                    <input type="checkbox" name="price[]" class="form-check-input" value="{{ $prc->id }}" data-group="wz_price">
                                                    <span class="checklist-text">{{ $prc->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <span class="invalid-feedback error_price"></span>
                                </div>
                                @endcan

                                {{-- Append Users (Penugasan User Awal) --}}
                                @can('input halaman user ditugaskan')
                                <div class="col-12">
                                    <div class="checklist-group-card" id="card_wz_user_ids">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                                <label class="form-label-premium mb-0">Tugaskan User / Teknisi Awal</label>
                                                <small class="text-muted d-block" style="font-size:0.75rem;">User yang dipilih akan langsung memiliki akses ke halaman ini (bisa diubah nanti di tombol Kelola User).</small>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="counter-badge" id="badge-wz_user_ids">0 dipilih</span>
                                                <button type="button" class="btn-checklist-action btn-checklist-all" data-target="wz_user_ids">Semua</button>
                                                <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="wz_user_ids">Batal</button>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari nama user atau email..." data-target="wz_user_ids">
                                        <div class="checklist-scrollbox" id="checklist-box-wz_user_ids" style="max-height: 140px;">
                                            @foreach ($allUsers as $u)
                                                <label class="checklist-item" data-search="{{ strtolower($u->name . ' ' . $u->email) }}">
                                                    <input type="checkbox" name="user_ids[]" class="form-check-input" value="{{ $u->id }}" data-group="wz_user_ids">
                                                    <span class="checklist-text">{{ $u->name }} <span class="text-muted small">({{ $u->email }})</span></span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endcan
                            </div>
                        </div>

                        {{-- STEP 4: PREVIEW RINGKASAN KONFIGURASI --}}
                        <div class="wizard-step-content d-none" id="wz-step-4">
                            <div class="alert alert-info d-flex align-items-center gap-2 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                                <div>
                                    <strong>Preview Ringkasan Konfigurasi:</strong> Silakan periksa kembali detail di bawah ini sebelum menyimpan halaman ke sistem.
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="preview-summary-box">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                                            Informasi Dasar Halaman
                                        </h6>
                                        <div class="preview-item">
                                            <span class="preview-label">Judul Halaman:</span>
                                            <span class="preview-val" id="prev_name">-</span>
                                        </div>
                                        <div class="preview-item">
                                            <span class="preview-label">Deskripsi:</span>
                                            <span class="preview-val" id="prev_desc">-</span>
                                        </div>
                                        <div class="preview-item">
                                            <span class="preview-label">Wilayah Area:</span>
                                            <span class="preview-val" id="prev_wilayah">-</span>
                                        </div>
                                        <div class="preview-item">
                                            <span class="preview-label">Fitur KTP:</span>
                                            <span class="preview-val" id="prev_ktp">-</span>
                                        </div>
                                        <div class="preview-item">
                                            <span class="preview-label">Fitur Persetujuan:</span>
                                            <span class="preview-val" id="prev_persetujuan">-</span>
                                        </div>
                                        <div class="preview-item">
                                            <span class="preview-label">Cek Email & HP:</span>
                                            <span class="preview-val" id="prev_verification">-</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="preview-summary-box">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="15" width="6" height="6" rx="2" /><rect x="15" y="15" width="6" height="6" rx="2" /><rect x="9" y="3" width="6" height="6" rx="2" /><path d="M6 15v-1a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v1" /><line x1="12" y1="9" x2="12" y2="12" /></svg>
                                            Konfigurasi VLAN & Layanan
                                        </h6>
                                        <div class="preview-item">
                                            <span class="preview-label">Metode VLAN:</span>
                                            <span class="preview-val" id="prev_vlan_mode">-</span>
                                        </div>
                                        <div class="preview-item">
                                            <span class="preview-label">VLAN Terpilih / Baru:</span>
                                            <span class="preview-val" id="prev_vlan_name">-</span>
                                        </div>
                                        <div class="preview-item">
                                            <span class="preview-label">Dukungan Layanan:</span>
                                            <span class="preview-val" id="prev_support">-</span>
                                        </div>
                                        <div class="preview-item">
                                            <span class="preview-label">Tipe Pelanggan:</span>
                                            <span class="preview-val" id="prev_tipe_pelanggan">-</span>
                                        </div>
                                        <div class="preview-item">
                                            <span class="preview-label">User Ditugaskan:</span>
                                            <span class="preview-val" id="prev_users">-</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="preview-summary-box">
                                        <h6 class="fw-bold text-primary mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="18" height="8" rx="3" /><rect x="3" y="12" width="18" height="8" rx="3" /><line x1="7" y1="8" x2="7" y2="8.01" /><line x1="7" y1="16" x2="7" y2="16.01" /></svg>
                                            Rincian Jaringan & Paket Layanan
                                        </h6>
                                        <div class="row g-2 text-muted" style="font-size:0.85rem;">
                                            <div class="col-md-3"><strong>ODC Network:</strong> <span id="prev_odc" class="text-dark">0 dipilih</span></div>
                                            <div class="col-md-3"><strong>ODP Network:</strong> <span id="prev_odp" class="text-dark">0 dipilih</span></div>
                                            <div class="col-md-3"><strong>OLT Network:</strong> <span id="prev_olt" class="text-dark">0 dipilih</span></div>
                                            <div class="col-md-3"><strong>Mic Radius:</strong> <span id="prev_radius" class="text-dark">0 dipilih</span></div>
                                            <div class="col-md-6 mt-2"><strong>Paket Layanan:</strong> <span id="prev_paket" class="text-dark">0 dipilih</span></div>
                                            <div class="col-md-6 mt-2"><strong>Tipe Pembayaran:</strong> <span id="prev_price" class="text-dark">0 dipilih</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer d-flex justify-content-between p-3 bg-white border-top">
                    <button type="button" class="btn btn-secondary" id="wz-btn-prev" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="5" y1="12" x2="19" y2="12" /><line x1="5" y1="12" x2="11" y2="18" /><line x1="5" y1="12" x2="11" y2="6" /></svg>
                        Sebelumnya
                    </button>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="wz-btn-next">
                            Selanjutnya
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="ms-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="5" y1="12" x2="19" y2="12" /><line x1="13" y1="18" x2="19" y2="12" /><line x1="13" y1="6" x2="19" y2="12" /></svg>
                        </button>
                        <button type="button" class="btn btn-success d-none" id="wz-btn-submit">
                            <span id="wzBtnSubmitText">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                Simpan Halaman Baru
                            </span>
                            <span id="wzBtnSubmitLoading" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL UBAH VLAN LANGSUNG DARI WIZARD                      --}}
    {{-- ======================================================== --}}
    <div class="modal modal-blur fade" id="modal-wizard-edit-vlan" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" style="z-index: 1070;">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content shadow-lg border-0" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:36px; height:36px; border-radius:10px; background:rgba(99, 102, 241, 0.2); display:flex; align-items:center; justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#818cf8" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -2 -2l-10.5 10.5v4" /><line x1="13.5" y1="6.5" x2="17.5" y2="10.5" /></svg>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0 text-white">Ubah Konfigurasi Data VLAN</h5>
                            <small class="text-white-50" style="font-size: 0.78rem;">Perbarui data segmen VLAN dan perangkat yang terhubung secara realtime</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="background-color: #f8fafc; max-height: calc(85vh - 160px); overflow-y: auto;">
                    <form id="form-wizard-edit-vlan" autocomplete="off">
                        @csrf
                        <input type="hidden" id="edit_wz_vlan_id" name="id">

                        <div class="card p-3 mb-3 border-0 shadow-sm" style="border-radius: 10px;">
                            <div class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><rect x="3" y="15" width="6" height="6" rx="2" /><rect x="15" y="15" width="6" height="6" rx="2" /><rect x="9" y="3" width="6" height="6" rx="2" /><path d="M6 15v-1a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v1" /><line x1="12" y1="9" x2="12" y2="12" /></svg>
                                Identitas & Konfigurasi Server
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label-premium">Nomor VLAN <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="edit_wz_vlan_number" class="form-control form-control-premium" placeholder="Contoh: 100" required>
                                    <span class="invalid-feedback error_edit_vlan_name"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-premium">Nama VLAN</label>
                                    <input type="text" name="vlan_name" id="edit_wz_vlan_name" class="form-control form-control-premium" placeholder="Contoh: VLAN-UTAMA-01">
                                    <span class="invalid-feedback error_edit_vlan_vlan_name"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-premium">Nama Server</label>
                                    <input type="text" name="server_name" id="edit_wz_vlan_server" class="form-control form-control-premium" placeholder="Contoh: MIKROTIK-CORE-01">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-premium">IP Address</label>
                                    <input type="text" name="ip_address" id="edit_wz_vlan_ip" class="form-control form-control-premium" placeholder="Contoh: 192.168.10.1/24">
                                </div>
                                <div class="col-12">
                                    <label class="form-label-premium mb-2">Dukungan Layanan</label>
                                    <div class="d-flex align-items-center gap-4 p-2.5 rounded bg-light border">
                                        <label class="form-check form-check-inline mb-0 cursor-pointer">
                                            <input class="form-check-input" type="checkbox" name="support_pppoe" id="edit_wz_vlan_support_pppoe" value="1">
                                            <span class="form-check-label fw-semibold text-dark">Support PPPoE</span>
                                        </label>
                                        <label class="form-check form-check-inline mb-0 cursor-pointer">
                                            <input class="form-check-input" type="checkbox" name="support_voucher" id="edit_wz_vlan_support_voucher" value="1">
                                            <span class="form-check-label fw-semibold text-dark">Support Voucher</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card p-3 mb-3 border-0 shadow-sm" style="border-radius: 10px;">
                            <div class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><circle cx="12" cy="11" r="3" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
                                Lokasi Wilayah Regional
                            </div>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label-premium">Kabupaten/Kota</label>
                                    <select name="regencie_id" id="edit_wz_vlan_regencie_id" class="form-select form-control-premium">
                                        <option value="">-- Pilih Kabupaten --</option>
                                        @foreach ($regencies as $reg)
                                            <option value="{{ $reg->id }}">{{ $reg->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-premium">Kecamatan</label>
                                    <select name="district_id" id="edit_wz_vlan_district_id" class="form-select form-control-premium">
                                        <option value="">-- Pilih Kecamatan --</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-premium">Desa</label>
                                    <select name="village_id" id="edit_wz_vlan_village_id" class="form-select form-control-premium">
                                        <option value="">-- Pilih Desa --</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-premium">Kampung</label>
                                    <select name="hometown_id" id="edit_wz_vlan_hometown_id" class="form-select form-control-premium">
                                        <option value="">-- Pilih Kampung --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card p-3 border-0 shadow-sm" style="border-radius: 10px;">
                            <div class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-success"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /></svg>
                                Perangkat Jaringan & Profil Paket Terhubung
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label class="form-label-premium mb-0">OLT Network</label>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none btn-edit-vlan-check-all" data-target="olts">Pilih Semua</button>
                                    </div>
                                    <div class="bg-white p-2 rounded border" style="max-height: 120px; overflow-y: auto;">
                                        @foreach ($olts as $ol)
                                            <label class="form-check mb-1 cursor-pointer">
                                                <input type="checkbox" name="olts[]" class="form-check-input edit-wz-olt-cb" value="{{ $ol->id }}">
                                                <span class="form-check-label text-dark small">{{ $ol->name }} ({{ $ol->code ?? '-' }})</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label class="form-label-premium mb-0">Mic Radius</label>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none btn-edit-vlan-check-all" data-target="radiuses">Pilih Semua</button>
                                    </div>
                                    <div class="bg-white p-2 rounded border" style="max-height: 120px; overflow-y: auto;">
                                        @foreach ($micRadius as $mr)
                                            <label class="form-check mb-1 cursor-pointer">
                                                <input type="checkbox" name="mix_radiuses[]" class="form-check-input edit-wz-radius-cb" value="{{ $mr->id }}">
                                                <span class="form-check-label text-dark small">{{ $mr->name }} ({{ $mr->code ?? '-' }})</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label class="form-label-premium mb-0">Tipe Paket</label>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none btn-edit-vlan-check-all" data-target="pakets">Pilih Semua</button>
                                    </div>
                                    <div class="bg-white p-2 rounded border" style="max-height: 120px; overflow-y: auto;">
                                        @foreach ($paket as $pk)
                                            <label class="form-check mb-1 cursor-pointer">
                                                <input type="checkbox" name="pakets[]" class="form-check-input edit-wz-paket-cb" value="{{ $pk->id }}">
                                                <span class="form-check-label text-dark small">{{ $pk->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label class="form-label-premium mb-0">Tipe Pembayaran</label>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none btn-edit-vlan-check-all" data-target="prices">Pilih Semua</button>
                                    </div>
                                    <div class="bg-white p-2 rounded border" style="max-height: 120px; overflow-y: auto;">
                                        @foreach ($price as $pr)
                                            <label class="form-check mb-1 cursor-pointer">
                                                <input type="checkbox" name="prices[]" class="form-check-input edit-wz-price-cb" value="{{ $pr->id }}">
                                                <span class="form-check-label text-dark small">{{ $pr->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-white d-flex justify-content-between p-3 border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2" id="btn-save-wizard-edit-vlan">
                        <span id="btnSaveWzEditVlanText" class="d-flex align-items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                            Simpan Perubahan VLAN
                        </span>
                        <span id="btnSaveWzEditVlanLoading" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- ======================================================== --}}
    {{-- 2. OFFCANVAS KELOLA HALAMAN (TAB USER & TAB EDIT)         --}}
    {{-- ======================================================== --}}
    <div class="offcanvas offcanvas-end shadow-lg" tabindex="-1" id="offcanvas-kelola" aria-labelledby="offcanvasKelolaLabel" style="width: 780px; max-width: 95vw; z-index: 1060;">
        <div class="offcanvas-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff;">
            <div class="d-flex align-items-center gap-3">
                <div style="width:38px; height:38px; border-radius:10px; background:rgba(255,255,255,0.12); display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" stroke-width="2" stroke="white" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </div>
                <div>
                    <h5 class="offcanvas-title fw-bold mb-0 text-white" id="offcanvasKelolaLabel">Kelola Halaman</h5>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <span class="badge bg-primary text-white" id="kelola-offcanvas-page-badge" style="font-weight: 500;">Halaman: -</span>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <!-- Nav tabs -->
        <div class="px-4 pt-3 bg-white border-bottom">
            <ul class="nav nav-tabs nav-fill border-bottom-0" id="kelolaTabs" role="tablist">
                @can('input halaman user ditugaskan')
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold py-2 px-3 d-flex align-items-center justify-content-center gap-2" id="tab-user-btn" data-bs-toggle="tab" data-bs-target="#tab-pane-user" type="button" role="tab" aria-controls="tab-pane-user" aria-selected="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <circle cx="9" cy="7" r="4" />
                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                        </svg>
                        <span>Kelola User</span>
                    </button>
                </li>
                @endcan
                <li class="nav-item" role="presentation">
                    <button class="nav-link @cannot('input halaman user ditugaskan') active @endcannot fw-bold py-2 px-3 d-flex align-items-center justify-content-center gap-2" id="tab-edit-btn" data-bs-toggle="tab" data-bs-target="#tab-pane-edit" type="button" role="tab" aria-controls="tab-pane-edit" aria-selected="{{ Auth::user()->can('input halaman user ditugaskan') ? 'false' : 'true' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -2.828 -2.828l-10.5 10.5v4" />
                            <path d="M13.5 6.5l4 4" />
                        </svg>
                        <span>Edit Halaman</span>
                    </button>
                </li>
            </ul>
        </div>

        <!-- Offcanvas Body -->
        <div class="offcanvas-body p-0" style="background-color: #f8fafc; overflow-y: auto;">
            <div class="tab-content" id="kelolaTabContent">
                @can('input halaman user ditugaskan')
                <!-- TAB 1: USER -->
                <div class="tab-pane fade show active p-4" id="tab-pane-user" role="tabpanel" aria-labelledby="tab-user-btn">
                    <input type="hidden" id="kelola_user_page_id">
                    <div class="card border-0 shadow-sm p-3 mb-3 bg-white rounded-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="counter-badge" id="kelola_user_counter">0 user dipilih</span>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn-checklist-action" id="btn-kelola-user-all">Pilih Semua</button>
                                <span class="text-muted" style="font-size: 0.72rem;">|</span>
                                <button type="button" class="btn-checklist-action btn-checklist-clear" id="btn-kelola-user-clear">Batal</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control checklist-search-input" id="kelola-user-search" placeholder="Cari nama atau email user...">
                        </div>
                        <div class="checklist-scrollbox" id="kelola-user-list" style="max-height: 480px; min-height: 250px;">
                            <div class="text-center py-4 text-muted" id="kelola-user-loading">
                                <span class="spinner-border spinner-border-sm me-2"></span>Memuat daftar user...
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-white border rounded shadow-sm d-flex justify-content-between align-items-center mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Tutup</button>
                        <button type="button" class="btn btn-primary" id="btnSaveKelolaUser">
                            <span id="btnSaveUserText">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                Simpan Penugasan
                            </span>
                            <span id="btnSaveUserLoading" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </div>
                @endcan

                <!-- TAB 2: EDIT -->
                <div class="tab-pane fade @cannot('input halaman user ditugaskan') show active @endcannot p-4" id="tab-pane-edit" role="tabpanel" aria-labelledby="tab-edit-btn">
                    <input type="hidden" name="type" id="type" value="update">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="page_version" id="page_version" value="complete">
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="premium-card p-3">
                                <h6 class="fw-bold text-dark mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                                    Informasi Halaman
                                </h6>
                                <div class="row g-3">
                                    @can('input halaman judul')
                                    <div class="col-md-6">
                                        <label for="name" class="form-label-premium">Judul Halaman <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control form-control-premium" placeholder="Masukkan judul halaman...">
                                        <span class="invalid-feedback error_name"></span>
                                    </div>
                                    @endcan
                                    @can('input halaman deskripsi')
                                    <div class="col-md-6">
                                        <label for="desc" class="form-label-premium">Sub Judul Halaman <span class="text-danger">*</span></label>
                                        <input type="text" name="desc" id="desc" class="form-control form-control-premium" placeholder="Masukkan sub judul...">
                                        <span class="invalid-feedback error_desc"></span>
                                    </div>
                                    @endcan

                                    {{-- Row 2: Dukungan Layanan & Tipe Pelanggan --}}
                                    @can('input halaman support')
                                    <div class="col-md-6" id="edit-support-section">
                                        <div class="p-3 bg-light border rounded h-100 d-flex flex-column justify-content-between">
                                            <label class="form-label-premium fw-bold mb-2 d-flex align-items-center text-dark">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-primary me-2 flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polygon points="12 2 2 7 12 12 22 7 12 2" /><polyline points="2 17 12 22 22 17" /><polyline points="2 12 12 17 22 12" /></svg>
                                                <span>Dukungan Layanan Halaman <span class="text-danger">*</span></span>
                                            </label>
                                            <div class="row g-2 pt-1">
                                                <div class="col-6">
                                                    <label class="checklist-item d-flex align-items-center gap-2 p-2 px-3 bg-white border rounded cursor-pointer w-100 mb-0">
                                                        <input type="checkbox" name="support_pppoe" id="edit_support_pppoe" value="1" class="form-check-input m-0 flex-shrink-0">
                                                        <span class="fw-bold d-inline-flex align-items-center gap-1.5 text-dark" style="font-size:0.86rem;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-primary flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="13 3 13 10 19 10 11 21 11 14 5 14 13 3" /></svg>
                                                            Support PPPoE
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="col-6">
                                                    <label class="checklist-item d-flex align-items-center gap-2 p-2 px-3 bg-white border rounded cursor-pointer w-100 mb-0">
                                                        <input type="checkbox" name="support_voucher" id="edit_support_voucher" value="1" class="form-check-input m-0 flex-shrink-0">
                                                        <span class="fw-bold d-inline-flex align-items-center gap-1.5 text-dark" style="font-size:0.86rem;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-warning flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="15" y1="5" x2="15" y2="7" /><line x1="15" y1="11" x2="15" y2="13" /><line x1="15" y1="17" x2="15" y2="19" /><path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" /></svg>
                                                            Support Voucher
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endcan

                                    @can('input halaman tipe pelanggan')
                                    <div class="col-md-6">
                                        <div class="checklist-group-card p-3 bg-light border rounded h-100 d-flex flex-column justify-content-between" id="card_tipe_pelanggan_id">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label class="form-label-premium mb-0 d-flex align-items-center text-dark fw-bold">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-info me-2 flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                                                    <span>Tipe Pelanggan <span class="text-danger">*</span></span>
                                                </label>
                                                <span class="counter-badge" id="badge-tipe_pelanggan_id">0 dipilih</span>
                                            </div>
                                            <div class="row g-2 pt-1" id="checklist-box-tipe_pelanggan_id">
                                                @foreach ($tipePelanggan as $tp)
                                                    <div class="col-6">
                                                        <label class="checklist-item d-flex align-items-center gap-2 p-2 px-3 bg-white border rounded cursor-pointer w-100 mb-0" data-search="{{ strtolower($tp->name) }}">
                                                            <input type="checkbox" name="tipe_pelanggan_id[]" class="form-check-input m-0 flex-shrink-0" value="{{ $tp->id }}" data-group="tipe_pelanggan_id">
                                                            <span class="checklist-text fw-bold text-dark" style="font-size: 0.86rem;">{{ $tp->name }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <span class="invalid-feedback error_tipe_pelanggan_id"></span>
                                    </div>
                                    @endcan

                                    {{-- Row 3: Opsi Validasi & Kebijakan Halaman --}}
                                    @can('input halaman opsi ktp')
                                    <div class="col-md-4">
                                        <label for="is_ktp" class="form-label-premium">Apakah Menggunakan KTP <span class="text-danger">*</span></label>
                                        <select name="is_ktp" id="is_ktp" class="form-select form-control-premium">
                                            <option value="aktif">Aktif</option>
                                            <option value="tidak">Tidak</option>
                                        </select>
                                    </div>
                                    @endcan
                                    @can('input halaman opsi persetujuan')
                                    <div class="col-md-4">
                                        <label for="is_persetujuan" class="form-label-premium">Apakah Menggunakan Persetujuan <span class="text-danger">*</span></label>
                                        <select name="is_persetujuan" id="is_persetujuan" class="form-select form-control-premium">
                                            <option value="aktif">Aktif</option>
                                            <option value="tidak">Tidak</option>
                                        </select>
                                    </div>
                                    @endcan
                                    @can('input halaman opsi cek kontak')
                                    <div class="col-md-4">
                                        <label for="is_verification" class="form-label-premium">Cek Email & No HP <span class="text-danger">*</span></label>
                                        <select name="is_verification" id="is_verification" class="form-select form-control-premium">
                                            <option value="aktif">Aktif (Wajib Cek)</option>
                                            <option value="tidak">Tidak Aktif (Tanpa Cek)</option>
                                        </select>
                                    </div>
                                    @endcan
                                </div>
                            </div>
                        </div>

                        @can('input halaman wilayah')
                        {{-- Area Regional --}}
                        <div class="col-12">
                            <div class="premium-card p-3">
                                <h6 class="fw-bold text-dark mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="11" r="3" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
                                    Area Regional
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="regencies_id" class="form-label-premium">Kabupaten/Kota <span class="text-danger">*</span></label>
                                        <select name="regencies_id" id="regencies_id" class="form-select form-control-premium wz-regency-select" data-prefix="edit">
                                            <option value="">-- Pilih Kabupaten --</option>
                                            @foreach ($regencies as $reg)
                                                <option value="{{ $reg->id }}">{{ $reg->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback error_regencies_id"></span>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="districts_id" class="form-label-premium">Kecamatan <span class="text-danger">*</span></label>
                                        <select name="districts_id" id="districts_id" class="form-select form-control-premium wz-district-select" data-prefix="edit">
                                            <option value="">-- Pilih Kecamatan --</option>
                                            @foreach ($districts as $dis)
                                                <option value="{{ $dis->id }}">{{ $dis->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback error_districts_id"></span>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="villages_id" class="form-label-premium">Desa <span class="text-danger">*</span></label>
                                        <select name="villages_id" id="villages_id" class="form-select form-control-premium">
                                            <option value="">-- Pilih Desa --</option>
                                            @foreach ($villages as $vlg)
                                                <option value="{{ $vlg->id }}">{{ $vlg->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback error_villages_id"></span>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="hometowns_id" class="form-label-premium">Kampung <span class="text-danger">*</span></label>
                                        <select name="hometowns_id" id="hometowns_id" class="form-select form-control-premium">
                                            <option value="">-- Pilih Kampung --</option>
                                            @foreach ($hometown as $ht)
                                                <option value="{{ $ht->id }}">{{ $ht->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback error_hometowns_id"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan

                        {{-- Perangkat Jaringan & Paket --}}
                        <div class="col-12">
                            <div class="premium-card p-3">
                                <h6 class="fw-bold text-dark mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="18" height="8" rx="3" /><rect x="3" y="12" width="18" height="8" rx="3" /><line x1="7" y1="8" x2="7" y2="8.01" /><line x1="7" y1="16" x2="7" y2="16.01" /></svg>
                                    Perangkat Jaringan & Layanan
                                </h6>
                                <div class="row g-3">
                                    {{-- VLAN --}}
                                    @can('input halaman vlan')
                                    <div class="col-md-6">
                                        <div class="checklist-group-card" id="card_vlans_id">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label class="form-label-premium mb-0 d-flex align-items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="15" width="6" height="6" rx="2" /><rect x="15" y="15" width="6" height="6" rx="2" /><rect x="9" y="3" width="6" height="6" rx="2" /><path d="M6 15v-1a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v1" /><line x1="12" y1="9" x2="12" y2="12" /></svg>
                                                    Data VLAN <span class="text-danger">*</span>
                                                </label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="counter-badge" id="badge-vlans_id">0 dipilih</span>
                                                    <button type="button" class="btn-checklist-action btn-checklist-all" data-target="vlans_id">Semua</button>
                                                    <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="vlans_id">Batal</button>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari VLAN..." data-target="vlans_id">
                                            <div class="checklist-scrollbox" id="checklist-box-vlans_id">
                                                @foreach ($vlans as $vln)
                                                    <label class="checklist-item" data-search="{{ strtolower($vln->name . ' ' . ($vln->server_name ?? '')) }}">
                                                        <input type="checkbox" name="vlans_id[]" class="form-check-input" value="{{ $vln->id }}" data-group="vlans_id">
                                                        <div class="d-flex align-items-center justify-content-between w-100 ms-1">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span class="vlan-tag-pill" style="font-size:0.72rem; padding: 0.15rem 0.45rem;">VLAN {{ $vln->name }}</span>
                                                                <span class="checklist-text fw-bold text-dark" style="font-size:0.83rem;">{{ $vln->server_name ?? ('Segment #' . $vln->name) }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-1">
                                                                @if($vln->support_pppoe)
                                                                    <span class="feature-pill pill-pppoe" style="font-size: 0.65rem;">PPPoE</span>
                                                                @endif
                                                                @if($vln->support_voucher)
                                                                    <span class="feature-pill pill-voucher" style="font-size: 0.65rem;">Voucher</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <span class="invalid-feedback error_vlans_id"></span>
                                    </div>
                                    @endcan

                                    {{-- ODC --}}
                                    @can('input halaman odc')
                                    <div class="col-md-6">
                                        <div class="checklist-group-card" id="card_odcs_id">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label class="form-label-premium mb-0">ODC Network <span class="text-danger">*</span></label>
                                                <span class="counter-badge" id="badge-odcs_id">0 dipilih</span>
                                            </div>
                                            <div class="checklist-scrollbox" id="checklist-box-odcs_id">
                                                @foreach ($odcs as $odc)
                                                    <label class="checklist-item" data-search="{{ strtolower($odc->home_odc) }}">
                                                        <input type="checkbox" name="odcs_id[]" class="form-check-input" value="{{ $odc->id }}" data-group="odcs_id">
                                                        <span class="checklist-text">{{ $odc->home_odc }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <span class="invalid-feedback error_odcs_id"></span>
                                    </div>
                                    @endcan

                                    {{-- ODP --}}
                                    @can('input halaman odp')
                                    <div class="col-md-6">
                                        <div class="checklist-group-card" id="card_odps_id">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label class="form-label-premium mb-0">ODP Network <span class="text-danger">*</span></label>
                                                <span class="counter-badge" id="badge-odps_id">0 dipilih</span>
                                            </div>
                                            <div class="checklist-scrollbox" id="checklist-box-odps_id">
                                                @foreach ($odps as $odp)
                                                    <label class="checklist-item" data-search="{{ strtolower($odp->home_odc) }}">
                                                        <input type="checkbox" name="odps_id[]" class="form-check-input" value="{{ $odp->id }}" data-group="odps_id">
                                                        <span class="checklist-text">{{ $odp->home_odc }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <span class="invalid-feedback error_odps_id"></span>
                                    </div>
                                    @endcan

                                    {{-- OLT --}}
                                    @can('input halaman olt')
                                    <div class="col-md-6">
                                        <div class="checklist-group-card" id="card_olts_id">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label class="form-label-premium mb-0">OLT Network <span class="text-danger">*</span></label>
                                                <span class="counter-badge" id="badge-olts_id">0 dipilih</span>
                                            </div>
                                            <div class="checklist-scrollbox" id="checklist-box-olts_id">
                                                @foreach ($olts as $ol)
                                                    <label class="checklist-item" data-search="{{ strtolower($ol->name) }}">
                                                        <input type="checkbox" name="olts_id[]" class="form-check-input" value="{{ $ol->id }}" data-group="olts_id">
                                                        <span class="checklist-text">{{ $ol->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <span class="invalid-feedback error_olts_id"></span>
                                    </div>
                                    @endcan

                                    {{-- Paket --}}
                                    @can('input halaman paket')
                                    <div class="col-md-6">
                                        <div class="checklist-group-card" id="card_paket_id">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label class="form-label-premium mb-0">Tipe Paket <span class="text-danger">*</span></label>
                                                <span class="counter-badge" id="badge-paket_id">0 dipilih</span>
                                            </div>
                                            <div class="checklist-scrollbox" id="checklist-box-paket_id">
                                                @foreach ($paket as $pkt)
                                                    <label class="checklist-item" data-search="{{ strtolower($pkt->name) }}">
                                                        <input type="checkbox" name="paket_id[]" class="form-check-input" value="{{ $pkt->id }}" data-group="paket_id">
                                                        <span class="checklist-text">{{ $pkt->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <span class="invalid-feedback error_paket_id"></span>
                                    </div>
                                    @endcan

                                    {{-- Radius --}}
                                    @can('input halaman mic radius')
                                    <div class="col-md-6">
                                        <div class="checklist-group-card" id="card_mic_radius_id">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label class="form-label-premium mb-0">Mic Radius <span class="text-danger">*</span></label>
                                                <span class="counter-badge" id="badge-mic_radius_id">0 dipilih</span>
                                            </div>
                                            <div class="checklist-scrollbox" id="checklist-box-mic_radius_id">
                                                @foreach ($micRadius as $mc)
                                                    <label class="checklist-item" data-search="{{ strtolower($mc->name) }}">
                                                        <input type="checkbox" name="mic_radius_id[]" class="form-check-input" value="{{ $mc->id }}" data-group="mic_radius_id">
                                                        <span class="checklist-text">{{ $mc->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <span class="invalid-feedback error_mic_radius_id"></span>
                                    </div>
                                    @endcan

                                    {{-- Price --}}
                                    @can('input halaman pembayaran')
                                    <div class="col-md-6">
                                        <div class="checklist-group-card" id="card_price">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label class="form-label-premium mb-0">Tipe Pembayaran / Harga <span class="text-danger">*</span></label>
                                                <span class="counter-badge" id="badge-price">0 dipilih</span>
                                            </div>
                                            <div class="checklist-scrollbox" id="checklist-box-price">
                                                @foreach ($price as $prc)
                                                    <label class="checklist-item" data-search="{{ strtolower($prc->name) }}">
                                                        <input type="checkbox" name="price[]" class="form-check-input" value="{{ $prc->id }}" data-group="price">
                                                        <span class="checklist-text">{{ $prc->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <span class="invalid-feedback error_price"></span>
                                    </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-white border rounded shadow-sm d-flex justify-content-between align-items-center mt-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Batal</button>
                        <button type="button" class="btn btn-primary" id="storeBtn">
                            <span id="btnText">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                Simpan Perubahan
                            </span>
                            <span id="btnLoading" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        const BASE = "{{ route('halaman.index') }}";
        let VLAN_DATA_MAP = @json($vlansJson ?? []);
        let table;
        let isEditMode = false;

        const multiFields = [
            'vlans_id', 'odcs_id', 'odps_id', 'olts_id',
            'paket_id', 'mic_radius_id', 'price', 'tipe_pelanggan_id'
        ];

        const wzMultiFields = [
            'wz_vlans_id', 'wz_odcs_id', 'wz_odps_id', 'wz_olts_id',
            'wz_paket_id', 'wz_mic_radius_id', 'wz_price', 'wz_tipe_pelanggan_id', 'wz_user_ids'
        ];

        $(document).ready(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeFilters();
            initializeChecklistInteractions();
            initializeWizard();
            initializeKelolaUserModal();
            initializeCascadingDropdowns();
            initializeEditModalHandlers();
        });

        // ============================================================
        // 1. DATA TABLE SETUP
        // ============================================================
        function initializeDataTable() {
            table = $('#halaman-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                lengthChange: false,
                pageLength: 10,
                dom: 'rt',
                ajax: {
                    url: BASE,
                    data: function(d) {
                        d.filter_vlan = $('#filter_vlan').val();
                        d.filter_regency = $('#filter_regency').val();
                        d.filter_district = $('#filter_district').val();
                        d.filter_village = $('#filter_village').val();
                        d.filter_hometown = $('#filter_hometown').val();
                        if ($('#filter-organization').length) {
                            d.organization_id = $('#filter-organization').val();
                        }
                    }
                },
                order: [[0, 'desc']],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: true, searchable: false },
                    @can('lihat kolom halaman nama')
                    { 
                        data: 'name', 
                        name: 'name',
                        orderable: true,
                        render: function(data, type, row) {
                            return '<div><strong class="text-dark">' + e(data) + '</strong><small class="text-muted d-block">' + (row.organization_name || '') + '</small></div>';
                        }
                    },
                    @endcan
                    @can('lihat kolom halaman vlan')
                    { data: 'vlan_name', name: 'vlan_name', orderable: true, searchable: false },
                    @endcan
                    @can('lihat kolom halaman server')
                    { data: 'server_name', name: 'server_name', orderable: true, searchable: false },
                    @endcan
                    @can('lihat kolom halaman ip address')
                    { data: 'ip_address', name: 'ip_address', orderable: true, searchable: false },
                    @endcan
                    @can('lihat kolom halaman support')
                    { data: 'support', name: 'support', orderable: true, searchable: false },
                    @endcan
                    @can('lihat kolom halaman persetujuan')
                    { data: 'is_persetujuan', name: 'is_persetujuan', orderable: true, searchable: false },
                    @endcan
                    @can('lihat kolom halaman foto ktp')
                    { data: 'is_ktp', name: 'is_ktp', orderable: true, searchable: false },
                    @endcan
                    @can('lihat kolom halaman cek kontak')
                    { data: 'is_verification', name: 'is_verification', orderable: true, searchable: false },
                    @endcan
                    @can('lihat kolom halaman wilayah')
                    { data: 'wilayah', name: 'wilayah', orderable: true, searchable: false },
                    @endcan
                    @can('lihat kolom halaman olt')
                    { data: 'olt', name: 'olt', orderable: true, searchable: false },
                    @endcan
                    @can('lihat kolom halaman mix radius')
                    { data: 'mix_radius', name: 'mix_radius', orderable: true, searchable: false },
                    @endcan
                    @can('lihat kolom halaman tipe paket')
                    { data: 'tipe_paket', name: 'tipe_paket', orderable: true, searchable: false },
                    @endcan
                    @can('lihat kolom halaman tipe pembayaran')
                    { data: 'tipe_pembayaran', name: 'tipe_pembayaran', orderable: true, searchable: false },
                    @endcan
                    @can('lihat kolom halaman user')
                    { data: 'users_count', name: 'users_count', orderable: true, searchable: false },
                    @endcan
                    { data: 'action', orderable: false, searchable: false }
                ],
                drawCallback: function(settings) {
                    updatePaginationInfo(settings);
                    updateCustomPagination();

                    const api = new $.fn.dataTable.Api(settings);
                    if (api.rows().count() === 0) {
                        const emptyContainer = $('#halaman-table tbody td.dataTables_empty, #halaman-table tbody td:only-child');
                        if (emptyContainer.length) {
                            emptyContainer.html(`
                                <div class="py-4 text-center text-muted">
                                    <div class="mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>
                                    </div>
                                    <h4 class="fw-bold text-dark mb-1">Tidak Ada Data Halaman</h4>
                                    <p class="text-secondary small mb-0">Pilih filter VLAN, Kabupaten, Kecamatan, Desa, atau Kampung di atas untuk menampilkan data halaman.</p>
                                </div>
                            `);
                        }
                    }
                },
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                    emptyTable: '<div class="py-4 text-center text-muted">Memuat data...</div>',
                    zeroRecords: '<div class="py-4 text-center text-muted"><div class="fw-bold">Tidak ada data yang cocok dengan filter atau pencarian.</div></div>'
                }
            });
        }

        // ============================================================
        // 2. STEP WIZARD 4-STEP LOGIC (TAMBAH HALAMAN LENGKAP)
        // ============================================================
        let currentStep = 1;

        function initializeWizard() {
            $('#btnAddWizard').on('click', function() {
                openWizardModal();
            });

            // Auto sync & detail saat VLAN di step 1 dipilih/berubah
            $(document).on('change', '.wz-vlan-cb', function() {
                syncWizardWithSelectedVlans();
                renderSelectedVlanDetails();
            });

            // Wizard Navigation Buttons
            $('#wz-btn-next').on('click', function() {
                if (validateStep(currentStep)) {
                    goToStep(currentStep + 1);
                }
            });

            $('#wz-btn-prev').on('click', function() {
                if (currentStep > 1) {
                    goToStep(currentStep - 1);
                }
            });

            // Click node directly if valid
            $('.wizard-step-node').on('click', function() {
                const targetStep = parseInt($(this).data('step'));
                if (targetStep < currentStep) {
                    goToStep(targetStep);
                } else if (targetStep > currentStep) {
                    if (validateStep(currentStep)) {
                        goToStep(targetStep);
                    }
                }
            });

            // Submit Wizard Form
            $('#wz-btn-submit').on('click', function() {
                submitWizardForm();
            });

            // Cascading dropdowns for Inline VLAN Edit Modal
            $(document).on('change', '#edit_wz_vlan_regencie_id', function() {
                loadCascadingWilayah('edit_wz_vlan', $(this).val());
            });

            $(document).on('change', '#edit_wz_vlan_district_id', function() {
                const districtId = $(this).val();
                const villageSelect = $('#edit_wz_vlan_village_id');
                const hometownSelect = $('#edit_wz_vlan_hometown_id');

                villageSelect.empty().append('<option value="">-- Pilih Desa --</option>');
                hometownSelect.empty().append('<option value="">-- Pilih Kampung --</option>');

                if (!districtId) return;

                $.get("{{ url('master-network/vlan/villages') }}/" + districtId, function(res) {
                    if (res.data) {
                        (res.data.villages || []).forEach(v => {
                            villageSelect.append(`<option value="${v.id}">${v.name}</option>`);
                        });
                        (res.data.hometowns || []).forEach(h => {
                            hometownSelect.append(`<option value="${h.id}">${h.name}</option>`);
                        });
                    }
                });
            });

            // Buka Modal Edit VLAN dari Wizard
            $(document).on('click', '.btn-edit-wizard-vlan', function(e) {
                e.preventDefault();
                const vlanId = parseInt($(this).data('id'));
                const data = (typeof VLAN_DATA_MAP !== 'undefined') ? VLAN_DATA_MAP[vlanId] : null;

                $('#form-wizard-edit-vlan')[0].reset();
                $('#edit_wz_vlan_id').val(vlanId);
                $('#form-wizard-edit-vlan .form-control, #form-wizard-edit-vlan .form-select').removeClass('is-invalid');
                $('#form-wizard-edit-vlan .invalid-feedback').text('').hide();

                if (data) {
                    $('#edit_wz_vlan_number').val(data.name || '');
                    $('#edit_wz_vlan_name').val(data.vlan_name || '');
                    $('#edit_wz_vlan_server').val(data.server_name || '');
                    $('#edit_wz_vlan_ip').val(data.ip_address || '');
                    $('#edit_wz_vlan_support_pppoe').prop('checked', !!data.support_pppoe);
                    $('#edit_wz_vlan_support_voucher').prop('checked', !!data.support_voucher);

                    if (data.regencie_id) {
                        $('#edit_wz_vlan_regencie_id').val(data.regencie_id);
                        loadCascadingWilayah('edit_wz_vlan', data.regencie_id, data.district_id, data.village_id, data.hometown_id);
                    } else {
                        $('#edit_wz_vlan_regencie_id').val('');
                        $('#edit_wz_vlan_district_id').empty().append('<option value="">-- Pilih Kecamatan --</option>');
                        $('#edit_wz_vlan_village_id').empty().append('<option value="">-- Pilih Desa --</option>');
                        $('#edit_wz_vlan_hometown_id').empty().append('<option value="">-- Pilih Kampung --</option>');
                    }

                    const checkArr = (cls, arr) => {
                        const ids = (arr || []).map(Number);
                        $(`.${cls}`).each(function() {
                            $(this).prop('checked', ids.includes(parseInt(this.value)));
                        });
                    };

                    checkArr('edit-wz-olt-cb', data.olts_id);
                    checkArr('edit-wz-radius-cb', data.mic_radius_id);
                    checkArr('edit-wz-paket-cb', data.paket_id);
                    checkArr('edit-wz-price-cb', data.price);
                }

                // AJAX fetch to guarantee latest server state
                $.get("{{ url('master-network/vlan') }}/" + vlanId + "/show", function(res) {
                    if (res && res.data) {
                        const d = res.data;
                        $('#edit_wz_vlan_number').val(d.name || '');
                        $('#edit_wz_vlan_name').val(d.vlan_name || '');
                        $('#edit_wz_vlan_server').val(d.server_name || '');
                        $('#edit_wz_vlan_ip').val(d.ip_address || '');
                        $('#edit_wz_vlan_support_pppoe').prop('checked', !!d.support_pppoe);
                        $('#edit_wz_vlan_support_voucher').prop('checked', !!d.support_voucher);

                        if (d.regencie_id) {
                            $('#edit_wz_vlan_regencie_id').val(d.regencie_id);
                            loadCascadingWilayah('edit_wz_vlan', d.regencie_id, d.district_id, d.village_id, d.hometown_id);
                        }

                        const checkArr = (cls, arr) => {
                            const ids = (arr || []).map(Number);
                            $(`.${cls}`).each(function() {
                                $(this).prop('checked', ids.includes(parseInt(this.value)));
                            });
                        };

                        checkArr('edit-wz-olt-cb', d.olt_ids);
                        checkArr('edit-wz-radius-cb', d.mix_radius_ids);
                        checkArr('edit-wz-paket-cb', d.paket_ids);
                        checkArr('edit-wz-price-cb', d.price_ids);
                    }
                });

                $('#modal-wizard-edit-vlan').modal('show');
            });

            // Quick check all toggle in edit VLAN modal
            $(document).on('click', '.btn-edit-vlan-check-all', function(e) {
                e.preventDefault();
                const target = $(this).data('target');
                let cls = '';
                if (target === 'olts') cls = 'edit-wz-olt-cb';
                else if (target === 'radiuses') cls = 'edit-wz-radius-cb';
                else if (target === 'pakets') cls = 'edit-wz-paket-cb';
                else if (target === 'prices') cls = 'edit-wz-price-cb';

                const checkboxes = $(`.${cls}`);
                const allChecked = checkboxes.length === checkboxes.filter(':checked').length;
                checkboxes.prop('checked', !allChecked);
                $(this).text(!allChecked ? 'Batal Semua' : 'Pilih Semua');
            });

            // Simpan Perubahan VLAN dari Modal Edit Wizard
            $('#btn-save-wizard-edit-vlan').on('click', function(e) {
                e.preventDefault();
                const vlanId = $('#edit_wz_vlan_id').val();
                if (!vlanId) return;

                const btn = $(this);
                btn.prop('disabled', true);
                $('#btnSaveWzEditVlanText').addClass('d-none');
                $('#btnSaveWzEditVlanLoading').removeClass('d-none');

                const formData = new FormData($('#form-wizard-edit-vlan')[0]);
                formData.append('_method', 'PUT');
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: "{{ url('master-network/vlan') }}/" + vlanId + "/update",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false
                })
                .done(function(response) {
                    btn.prop('disabled', false);
                    $('#btnSaveWzEditVlanText').removeClass('d-none');
                    $('#btnSaveWzEditVlanLoading').addClass('d-none');

                    if (response.errors) {
                        showValidationErrors(response.errors);
                        return;
                    }

                    // Update local VLAN_DATA_MAP
                    const num = $('#edit_wz_vlan_number').val();
                    const vname = $('#edit_wz_vlan_name').val();
                    const sname = $('#edit_wz_vlan_server').val();
                    const ip = $('#edit_wz_vlan_ip').val();
                    const pppoe = $('#edit_wz_vlan_support_pppoe').is(':checked');
                    const voucher = $('#edit_wz_vlan_support_voucher').is(':checked');
                    const regId = $('#edit_wz_vlan_regencie_id').val();
                    const distId = $('#edit_wz_vlan_district_id').val();
                    const vilId = $('#edit_wz_vlan_village_id').val();
                    const homeId = $('#edit_wz_vlan_hometown_id').val();
                    const regName = $('#edit_wz_vlan_regencie_id option:selected').val() ? $('#edit_wz_vlan_regencie_id option:selected').text() : '';
                    const distName = $('#edit_wz_vlan_district_id option:selected').val() ? $('#edit_wz_vlan_district_id option:selected').text() : '';
                    const vilName = $('#edit_wz_vlan_village_id option:selected').val() ? $('#edit_wz_vlan_village_id option:selected').text() : '';
                    const homeName = $('#edit_wz_vlan_hometown_id option:selected').val() ? $('#edit_wz_vlan_hometown_id option:selected').text() : '';

                    const getCheckedVals = cls => $(`.${cls}:checked`).map(function() { return parseInt(this.value); }).get();
                    const getCheckedLabels = cls => $(`.${cls}:checked`).map(function() { return $(this).closest('label').text().trim(); }).get();

                    VLAN_DATA_MAP[vlanId] = {
                        ...(VLAN_DATA_MAP[vlanId] || {}),
                        id: parseInt(vlanId),
                        name: num,
                        vlan_name: vname,
                        server_name: sname,
                        ip_address: ip,
                        support_pppoe: pppoe,
                        support_voucher: voucher,
                        regencie_id: regId ? parseInt(regId) : null,
                        regencie_name: regName,
                        district_id: distId ? parseInt(distId) : null,
                        district_name: distName,
                        village_id: vilId ? parseInt(vilId) : null,
                        village_name: vilName,
                        hometown_id: homeId ? parseInt(homeId) : null,
                        hometown_name: homeName,
                        olts_id: getCheckedVals('edit-wz-olt-cb'),
                        olts_names: getCheckedLabels('edit-wz-olt-cb'),
                        mic_radius_id: getCheckedVals('edit-wz-radius-cb'),
                        mic_radius_names: getCheckedLabels('edit-wz-radius-cb'),
                        paket_id: getCheckedVals('edit-wz-paket-cb'),
                        paket_names: getCheckedLabels('edit-wz-paket-cb'),
                        price: getCheckedVals('edit-wz-price-cb'),
                        price_names: getCheckedLabels('edit-wz-price-cb'),
                    };

                    // Update card element in Step 1 checklist
                    const card = $(`#wz-vlan-card-${vlanId}`);
                    if (card.length) {
                        card.find('.vlan-card-num-text').text('VLAN ' + num);
                        card.find('.checklist-text').text(num);
                        card.find('.vlan-card-server-text').html(`
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="18" height="8" rx="2" /><rect x="3" y="12" width="18" height="8" rx="2" /><line x1="7" y1="8" x2="7" y2="8.01" /><line x1="7" y1="16" x2="7" y2="16.01" /></svg>
                            ${sname || ('#' + num)}
                        `);
                        let vnameEl = card.find('.vlan-card-vname-text');
                        if (vname) {
                            if (vnameEl.length) {
                                vnameEl.text(vname);
                            } else {
                                card.find('.vlan-tag-pill').after(`<span class="badge bg-purple-subtle text-purple border border-purple-subtle vlan-card-vname-text" style="font-size: 0.72rem; padding: 2px 6px;">${vname}</span>`);
                            }
                        } else if (vnameEl.length) {
                            vnameEl.remove();
                        }
                        card.attr('data-search', `${num} ${vname} ${sname} ${ip} ${distName} ${regName}`.toLowerCase());
                    }

                    $('#modal-wizard-edit-vlan').modal('hide');
                    showSuccessMessage('Data VLAN berhasil diperbarui.');

                    // Refresh detail preview and sync with wizard steps
                    renderSelectedVlanDetails();
                    syncWizardWithSelectedVlans();
                })
                .fail(function(xhr) {
                    btn.prop('disabled', false);
                    $('#btnSaveWzEditVlanText').removeClass('d-none');
                    $('#btnSaveWzEditVlanLoading').addClass('d-none');
                    const res = xhr.responseJSON;
                    if (res && res.errors) {
                        showValidationErrors(res.errors);
                    } else {
                        showErrorMessage(res?.message || 'Gagal menyimpan perubahan VLAN.');
                    }
                });
            });
        }

        /**
         * Render Card Detail VLAN Terpilih di Step 1
         */
        function renderSelectedVlanDetails() {
            const container = $('#wz-vlan-details-container');
            const checkedBoxes = $('#checklist-box-wz_vlans_id input[type="checkbox"]:checked');

            if (checkedBoxes.length === 0) {
                container.empty().addClass('d-none');
                return;
            }

            container.removeClass('d-none');

            let html = `
                <div class="card border border-primary-subtle shadow-sm mb-0" style="border-radius: 12px; overflow: hidden; background: #ffffff;">
                    <div class="card-header py-2.5 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.08) 0%, rgba(241, 245, 249, 0.6) 100%);">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary text-white rounded-pill px-2.5 py-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                                Detail Konfigurasi VLAN Terpilih
                            </span>
                            <span class="text-secondary small fw-medium">(${checkedBoxes.length} VLAN aktif dipilih)</span>
                        </div>
                        <small class="text-muted" style="font-size: 0.74rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-info me-0.5"><polyline points="20 6 9 17 4 12" /></svg>
                            Otomatis sinkron ke langkah wilayah & perangkat
                        </small>
                    </div>
                    <div class="card-body p-3">
            `;

            checkedBoxes.each(function(idx) {
                const vlanId = parseInt(this.value);
                const data = (typeof VLAN_DATA_MAP !== 'undefined') ? VLAN_DATA_MAP[vlanId] : null;
                if (!data) return;

                const pppoeBadge = data.support_pppoe 
                    ? '<span class="badge bg-blue-subtle text-primary border border-blue-subtle d-inline-flex align-items-center gap-1" style="font-size: 0.73rem;"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="13 3 13 10 19 10 11 21 11 14 5 14 13 3" /></svg>PPPoE</span>' 
                    : '<span class="badge bg-light text-muted border" style="font-size: 0.73rem;">Tanpa PPPoE</span>';

                const voucherBadge = data.support_voucher 
                    ? '<span class="badge bg-amber-subtle text-warning border border-amber-subtle d-inline-flex align-items-center gap-1" style="font-size: 0.73rem;"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><line x1="15" y1="5" x2="15" y2="7" /><line x1="15" y1="11" x2="15" y2="13" /><line x1="15" y1="17" x2="15" y2="19" /><path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" /></svg>Voucher</span>' 
                    : '<span class="badge bg-light text-muted border" style="font-size: 0.73rem;">Tanpa Voucher</span>';

                const wilayahParts = [data.regencie_name, data.district_name, data.village_name, data.hometown_name].filter(Boolean);
                const wilayahText = wilayahParts.length ? wilayahParts.join(' / ') : '<span class="text-muted fst-italic">Belum ditentukan</span>';

                const oltsList = (Array.isArray(data.olts_names) && data.olts_names.length) 
                    ? data.olts_names.map(n => `<span class="badge bg-azure-lt border me-1 mb-1">${n}</span>`).join('') 
                    : '<span class="text-muted small">-</span>';

                const radiusList = (Array.isArray(data.mic_radius_names) && data.mic_radius_names.length) 
                    ? data.mic_radius_names.map(n => `<span class="badge bg-indigo-lt border me-1 mb-1">${n}</span>`).join('') 
                    : '<span class="text-muted small">-</span>';

                const paketList = (Array.isArray(data.paket_names) && data.paket_names.length) 
                    ? data.paket_names.map(n => `<span class="badge bg-green-lt border me-1 mb-1">${n}</span>`).join('') 
                    : '<span class="text-muted small">-</span>';

                const priceList = (Array.isArray(data.price_names) && data.price_names.length) 
                    ? data.price_names.map(n => `<span class="badge bg-teal-lt border me-1 mb-1">${n}</span>`).join('') 
                    : '<span class="text-muted small">-</span>';

                html += `
                    <div class="vlan-detail-block p-3 ${idx > 0 ? 'mt-3 pt-3 border-top' : ''}" style="background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge bg-primary text-white px-2.5 py-1 fw-bold" style="font-size: 0.85rem;">VLAN ${data.name}</span>
                                ${data.vlan_name ? `<span class="badge bg-purple-subtle text-purple border border-purple-subtle px-2 py-1 fw-semibold" style="font-size: 0.8rem;">${data.vlan_name}</span>` : ''}
                                <span class="text-dark fw-bold" style="font-size: 0.88rem;">${data.server_name || '-'}</span>
                                ${pppoeBadge}
                                ${voucherBadge}
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-wizard-vlan d-inline-flex align-items-center gap-1.5 px-3 py-1 shadow-sm" data-id="${data.id}" style="border-radius: 8px; font-size: 0.8rem; font-weight: 600;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -2 -2l-10.5 10.5v4" /><line x1="13.5" y1="6.5" x2="17.5" y2="10.5" /></svg>
                                Ubah VLAN Ini
                            </button>
                        </div>

                        <div class="row g-2 mt-1">
                            <div class="col-md-4">
                                <div class="p-2 bg-white rounded border h-100">
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">IP Address / Gateway</small>
                                    <div class="fw-semibold text-dark text-truncate" style="font-size: 0.82rem;">${data.ip_address || '<span class="text-muted fst-italic">Belum diisi</span>'}</div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="p-2 bg-white rounded border h-100">
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">Area Wilayah</small>
                                    <div class="fw-semibold text-dark text-truncate" style="font-size: 0.82rem;">${wilayahText}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-2 bg-white rounded border h-100">
                                    <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">OLT Terhubung</small>
                                    <div>${oltsList}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-2 bg-white rounded border h-100">
                                    <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Mic Radius Terhubung</small>
                                    <div>${radiusList}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-2 bg-white rounded border h-100">
                                    <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Tipe Paket Terhubung</small>
                                    <div>${paketList}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-2 bg-white rounded border h-100">
                                    <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Tipe Pembayaran Terhubung</small>
                                    <div>${priceList}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += `</div></div>`;
            container.html(html);
        }

        /**
         * Auto-fill / Append data dari VLAN terpilih ke Step 2 (Wilayah, Dukungan)
         * dan Step 3 (Jaringan OLT, Mic Radius, Paket, Tipe Pembayaran)
         */
        function syncWizardWithSelectedVlans() {
            const checkedBoxes = $('#checklist-box-wz_vlans_id input[type="checkbox"]:checked');
            if (checkedBoxes.length === 0) return;

            let oltIds = [];
            let micRadiusIds = [];
            let paketIds = [];
            let priceIds = [];
            let hasPppoe = false;
            let hasVoucher = false;
            let anySupportFound = false;
            let targetWilayah = null;

            checkedBoxes.each(function() {
                const vlanId = parseInt(this.value);
                const data = (typeof VLAN_DATA_MAP !== 'undefined') ? VLAN_DATA_MAP[vlanId] : null;
                if (!data) return;

                if (Array.isArray(data.olts_id)) oltIds.push(...data.olts_id);
                if (Array.isArray(data.mic_radius_id)) micRadiusIds.push(...data.mic_radius_id);
                if (Array.isArray(data.paket_id)) paketIds.push(...data.paket_id);
                if (Array.isArray(data.price)) priceIds.push(...data.price);

                if (data.support_pppoe) { hasPppoe = true; anySupportFound = true; }
                if (data.support_voucher) { hasVoucher = true; anySupportFound = true; }

                if (!targetWilayah && (data.regencie_id || data.district_id || data.village_id || data.hometown_id)) {
                    targetWilayah = data;
                }
            });

            // 1. Dukungan Layanan (PPPoE / Voucher)
            if (anySupportFound) {
                $('#wz_support_pppoe').prop('checked', hasPppoe);
                $('#wz_support_voucher').prop('checked', hasVoucher);
            }

            // 2. Checklist Jaringan & Layanan (Step 3) - OLT, Mic Radius, Paket, Price
            const checkBoxesInGroup = (groupId, ids) => {
                const uniqueIds = [...new Set(ids.map(Number))];
                if (uniqueIds.length === 0) return;

                $(`#checklist-box-${groupId} input[type="checkbox"]`).each(function() {
                    if (uniqueIds.includes(parseInt(this.value))) {
                        $(this).prop('checked', true);
                        $(this).closest('.checklist-item').addClass('item-checked');
                    }
                });
                updateChecklistBadge(groupId);
            };

            checkBoxesInGroup('wz_olts_id', oltIds);
            checkBoxesInGroup('wz_mic_radius_id', micRadiusIds);
            checkBoxesInGroup('wz_paket_id', paketIds);
            checkBoxesInGroup('wz_price', priceIds);

            // 3. Wilayah (Step 2) - Kabupaten, Kecamatan, Desa, Kampung
            if (targetWilayah && targetWilayah.regencie_id) {
                const regId = targetWilayah.regencie_id;
                const distId = targetWilayah.district_id;
                const vilId = targetWilayah.village_id;
                const homeId = targetWilayah.hometown_id;

                $('#wz_regencies_id').val(regId);
                loadCascadingWilayah('wz', regId, distId, vilId, homeId);
            }
        }

        function loadCascadingWilayah(prefix, regencyId, selectedDistrictId, selectedVillageId, selectedHometownId) {
            let districtSelect, villageSelect, hometownSelect;
            if (prefix === 'edit_wz_vlan') {
                districtSelect = $('#edit_wz_vlan_district_id');
                villageSelect = $('#edit_wz_vlan_village_id');
                hometownSelect = $('#edit_wz_vlan_hometown_id');
            } else if (prefix === 'new_vlan') {
                districtSelect = $('#new_vlan_district_id');
                villageSelect = $('#new_vlan_village_id');
                hometownSelect = $('#new_vlan_hometown_id');
            } else {
                districtSelect = $('#wz_districts_id');
                villageSelect = $('#wz_villages_id');
                hometownSelect = $('#wz_hometowns_id');
            }

            districtSelect.empty().append('<option value="">-- Pilih Kecamatan --</option>');
            villageSelect.empty().append('<option value="">-- Pilih Desa --</option>');
            hometownSelect.empty().append('<option value="">-- Pilih Kampung --</option>');

            if (!regencyId) return;

            $.get("{{ url('master-network/vlan/districts') }}/" + regencyId, function(res) {
                if (res.data) {
                    res.data.forEach(d => {
                        districtSelect.append(`<option value="${d.id}">${d.name}</option>`);
                    });
                }
                if (selectedDistrictId) {
                    districtSelect.val(selectedDistrictId);
                    $.get("{{ url('master-network/vlan/villages') }}/" + selectedDistrictId, function(resVil) {
                        if (resVil.data) {
                            (resVil.data.villages || []).forEach(v => {
                                villageSelect.append(`<option value="${v.id}">${v.name}</option>`);
                            });
                            (resVil.data.hometowns || []).forEach(h => {
                                hometownSelect.append(`<option value="${h.id}">${h.name}</option>`);
                            });
                        }
                        if (selectedVillageId) {
                            villageSelect.val(selectedVillageId);
                        }
                        if (selectedHometownId) {
                            hometownSelect.val(selectedHometownId);
                        }
                    });
                }
            });
        }

        function openWizardModal() {
            resetWizardForm();
            goToStep(1);
            $('#modal-wizard').modal('show');
        }

        function resetWizardForm() {
            $('#form-wizard')[0].reset();
            $('#wz_vlan_mode').val('existing');
            $('#section-vlan-existing').removeClass('d-none');
            $('#wz-vlan-details-container').empty().addClass('d-none');

            // Reset custom checklist items
            $('.checklist-scrollbox input[type="checkbox"]').prop('checked', false);
            $('.checklist-item').removeClass('item-checked');
            $('.checklist-search-input').val('');
            wzMultiFields.forEach(f => updateChecklistBadge(f));

            // Default support options
            $('#wz_support_pppoe').prop('checked', true);
            $('#wz_support_voucher').prop('checked', true);

            clearValidationErrors();
        }

        function goToStep(step) {
            currentStep = step;
            $('.wizard-step-content').addClass('d-none');
            $('#wz-step-' + step).removeClass('d-none');

            // Update Stepper indicators
            for (let i = 1; i <= 4; i++) {
                const node = $('#wz-nav-' + i);
                node.removeClass('active completed');
                if (i === step) {
                    node.addClass('active');
                } else if (i < step) {
                    node.addClass('completed');
                }
            }

            // Subtitle
            const subtitles = [
                'Step 1 dari 4: Konfigurasi VLAN',
                'Step 2 dari 4: Informasi Halaman & Dukungan Layanan',
                'Step 3 dari 4: Perangkat Jaringan & Penugasan User',
                'Step 4 dari 4: Preview Ringkasan Konfigurasi'
            ];
            $('#wizard-step-subtitle').text(subtitles[step - 1] || '');

            // Button visibility
            $('#wz-btn-prev').prop('disabled', step === 1);

            if (step === 4) {
                $('#wz-btn-next').addClass('d-none');
                $('#wz-btn-submit').removeClass('d-none');
                buildPreview();
            } else {
                $('#wz-btn-next').removeClass('d-none');
                $('#wz-btn-submit').addClass('d-none');
            }
        }

        function validateStep(step) {
            clearValidationErrors();
            let isValid = true;
            let firstErrorMessage = null;

            if (step === 1) {
                const checkedVlan = $('input[name="vlans_id[]"]:checked').length;
                if (checkedVlan === 0) {
                    $('#card_wz_vlans_id').addClass('border-danger');
                    $('.error_vlans_id').text('Silakan pilih minimal satu VLAN yang ada.').show();
                    if (!firstErrorMessage) firstErrorMessage = 'Silakan pilih minimal satu VLAN.';
                    isValid = false;
                }

                if (isValid) {
                    syncWizardWithSelectedVlans();
                }
            } else if (step === 2) {
                // Judul
                if ($('#wz_name').length && !($('#wz_name').val() || '').trim()) {
                    $('#wz_name').addClass('is-invalid');
                    $('.error_name').text('Judul halaman wajib diisi.').show();
                    if (!firstErrorMessage) firstErrorMessage = 'Judul halaman wajib diisi.';
                    isValid = false;
                }

                // Deskripsi
                if ($('#wz_desc').length && !($('#wz_desc').val() || '').trim()) {
                    $('#wz_desc').addClass('is-invalid');
                    $('.error_desc').text('Deskripsi halaman wajib diisi.').show();
                    if (!firstErrorMessage) firstErrorMessage = 'Deskripsi halaman wajib diisi.';
                    isValid = false;
                }

                // Dukungan Layanan
                if ($('#wz_support_pppoe').length) {
                    const supportPppoe = $('#wz_support_pppoe').is(':checked');
                    const supportVoucher = $('#wz_support_voucher').is(':checked');
                    if (!supportPppoe && !supportVoucher) {
                        $('#card_support_layanan').addClass('border-danger');
                        $('.error_support_layanan').text('Pilih setidaknya salah satu dukungan layanan (PPPoE atau Voucher).').show();
                        if (!firstErrorMessage) firstErrorMessage = 'Pilih setidaknya salah satu dukungan layanan (PPPoE atau Voucher).';
                        isValid = false;
                    }
                }

                // Wilayah Area
                if ($('#wz_regencies_id').length && !$('#wz_regencies_id').val()) {
                    $('#wz_regencies_id').addClass('is-invalid');
                    $('.error_regencies_id').text('Kabupaten wajib dipilih.').show();
                    if (!firstErrorMessage) firstErrorMessage = 'Kabupaten wajib dipilih.';
                    isValid = false;
                }
                if ($('#wz_districts_id').length && !$('#wz_districts_id').val()) {
                    $('#wz_districts_id').addClass('is-invalid');
                    $('.error_districts_id').text('Kecamatan wajib dipilih.').show();
                    if (!firstErrorMessage) firstErrorMessage = 'Kecamatan wajib dipilih.';
                    isValid = false;
                }
                if ($('#wz_villages_id').length && !$('#wz_villages_id').val()) {
                    $('#wz_villages_id').addClass('is-invalid');
                    $('.error_villages_id').text('Desa wajib dipilih.').show();
                    if (!firstErrorMessage) firstErrorMessage = 'Desa wajib dipilih.';
                    isValid = false;
                }
                if ($('#wz_hometowns_id').length && $('#wz_hometowns_id option').length > 1 && !$('#wz_hometowns_id').val()) {
                    $('#wz_hometowns_id').addClass('is-invalid');
                    $('.error_hometowns_id').text('Kampung wajib dipilih.').show();
                    if (!firstErrorMessage) firstErrorMessage = 'Kampung wajib dipilih.';
                    isValid = false;
                }

                // Tipe Pelanggan
                if ($('input[name="tipe_pelanggan_id[]"]').length > 0 && $('input[name="tipe_pelanggan_id[]"]:checked').length === 0) {
                    $('#card_wz_tipe_pelanggan_id').addClass('border-danger');
                    $('.error_tipe_pelanggan_id').text('Pilih minimal satu tipe pelanggan.').show();
                    if (!firstErrorMessage) firstErrorMessage = 'Pilih minimal satu tipe pelanggan.';
                    isValid = false;
                }
            } else if (step === 3) {
                const checks = [
                    { name: 'odcs_id', label: 'ODC Network' },
                    { name: 'odps_id', label: 'ODP Network' },
                    { name: 'olts_id', label: 'OLT Network' },
                    { name: 'mic_radius_id', label: 'Mic Radius' },
                    { name: 'paket_id', label: 'Tipe Paket' },
                    { name: 'price', label: 'Tipe Pembayaran' },
                ];
                checks.forEach(c => {
                    if ($(`input[name="${c.name}[]"]`).length > 0 && $(`input[name="${c.name}[]"]:checked`).length === 0) {
                        $(`#card_wz_${c.name}`).addClass('border-danger');
                        $(`.error_${c.name}`).text(`Silakan pilih minimal satu ${c.label}.`).show();
                        if (!firstErrorMessage) firstErrorMessage = `Silakan pilih minimal satu ${c.label}.`;
                        isValid = false;
                    }
                });
            }

            if (!isValid) {
                if (firstErrorMessage) {
                    showErrorMessage(firstErrorMessage);
                }
                const $firstInvalid = $('#wz-step-' + step).find('.is-invalid, .border-danger').first();
                if ($firstInvalid.length) {
                    $firstInvalid[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                    if ($firstInvalid.is('input, select, textarea')) {
                        $firstInvalid.focus();
                    }
                }
            }

            return isValid;
        }

        function buildPreview() {
            $('#prev_name').text($('#wz_name').val() || '-');
            $('#prev_desc').text($('#wz_desc').val() || '-');
            const regText = $('#wz_regencies_id option:selected').text();
            const disText = $('#wz_districts_id option:selected').text();
            const vlgText = $('#wz_villages_id option:selected').text();
            const htText = $('#wz_hometowns_id option:selected').text();
            $('#prev_wilayah').text(`${regText} / ${disText} / ${vlgText} / ${htText}`);
            $('#prev_ktp').html($('#wz_is_ktp').val() === 'aktif' ? '<span class="badge bg-success text-white">Aktif</span>' : '<span class="badge bg-warning text-white">Tidak</span>');
            $('#prev_persetujuan').html($('#wz_is_persetujuan').val() === 'aktif' ? '<span class="badge bg-success text-white">Aktif</span>' : '<span class="badge bg-warning text-white">Tidak</span>');
            $('#prev_verification').html($('#wz_is_verification').val() === 'aktif' ? '<span class="badge bg-success text-white">Aktif</span>' : '<span class="badge bg-warning text-white">Tidak</span>');

            $('#prev_vlan_mode').text('Pilih VLAN yang Ada');

            const vlanNames = [];
            $('input[name="vlans_id[]"]:checked').each(function() {
                const vid = $(this).val();
                const d = (typeof VLAN_DATA_MAP !== 'undefined') ? VLAN_DATA_MAP[vid] : null;
                if (d) {
                    vlanNames.push('VLAN ' + d.name + (d.vlan_name ? ' (' + d.vlan_name + ')' : ''));
                } else {
                    vlanNames.push($(this).closest('.checklist-item').find('.checklist-text').text());
                }
            });
            $('#prev_vlan_name').text(vlanNames.join(', ') || '-');

            const badges = [];
            if ($('#wz_support_pppoe').is(':checked')) badges.push('<span class="badge bg-blue-lt d-inline-flex align-items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="13 3 13 10 19 10 11 21 11 14 5 14 13 3" /></svg>PPPoE</span>');
            if ($('#wz_support_voucher').is(':checked')) badges.push('<span class="badge bg-amber-lt d-inline-flex align-items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><line x1="15" y1="5" x2="15" y2="7" /><line x1="15" y1="11" x2="15" y2="13" /><line x1="15" y1="17" x2="15" y2="19" /><path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" /></svg>Voucher</span>');
            $('#prev_support').html(badges.join(' ') || '-');

            const tipeNames = [];
            $('input[name="tipe_pelanggan_id[]"]:checked').each(function() {
                tipeNames.push($(this).closest('.checklist-item').find('.checklist-text').text());
            });
            $('#prev_tipe_pelanggan').text(tipeNames.join(', ') || '-');

            const userCount = $('input[name="user_ids[]"]:checked').length;
            $('#prev_users').text(userCount > 0 ? userCount + ' User Dipilih' : 'Hanya Pembuat Halaman (Default)');

            $('#prev_odc').text($('input[name="odcs_id[]"]:checked').length + ' dipilih');
            $('#prev_odp').text($('input[name="odps_id[]"]:checked').length + ' dipilih');
            $('#prev_olt').text($('input[name="olts_id[]"]:checked').length + ' dipilih');
            $('#prev_radius').text($('input[name="mic_radius_id[]"]:checked').length + ' dipilih');
            $('#prev_paket').text($('input[name="paket_id[]"]:checked').length + ' dipilih');
            $('#prev_price').text($('input[name="price[]"]:checked').length + ' dipilih');
        }

        function submitWizardForm() {
            const btn = $('#wz-btn-submit');
            btn.prop('disabled', true);
            $('#wzBtnSubmitText').addClass('d-none');
            $('#wzBtnSubmitLoading').removeClass('d-none');

            const formData = new FormData($('#form-wizard')[0]);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url: "{{ route('halaman.storeComplete') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false
            })
            .done(function(response) {
                if (response.errors) {
                    showValidationErrors(response.errors);
                    btn.prop('disabled', false);
                    $('#wzBtnSubmitText').removeClass('d-none');
                    $('#wzBtnSubmitLoading').addClass('d-none');
                    goToStep(1);
                } else {
                    $('#modal-wizard').modal('hide');
                    showSuccessMessage(response.message);
                    table.ajax.reload();
                    btn.prop('disabled', false);
                    $('#wzBtnSubmitText').removeClass('d-none');
                    $('#wzBtnSubmitLoading').addClass('d-none');
                }
            })
            .fail(function(jqXHR) {
                btn.prop('disabled', false);
                $('#wzBtnSubmitText').removeClass('d-none');
                $('#wzBtnSubmitLoading').addClass('d-none');
                if (jqXHR.status === 422 && jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                    showValidationErrors(jqXHR.responseJSON.errors);
                    goToStep(1);
                } else {
                    showErrorMessage(jqXHR.responseJSON?.message || "Terjadi kesalahan saat menyimpan data");
                }
            });
        }

        // ============================================================
        // 3. MODAL KELOLA USER (APPEND USER)
        // ============================================================
        function initializeKelolaUserModal() {
            $('#kelola-user-search').on('input', function() {
                const query = $(this).val().toLowerCase().trim();
                $('#kelola-user-list .checklist-item').each(function() {
                    const text = ($(this).data('search') || $(this).text()).toLowerCase();
                    if (text.indexOf(query) !== -1) {
                        $(this).removeClass('d-none');
                    } else {
                        $(this).addClass('d-none');
                    }
                });
            });

            $('#btn-kelola-user-all').on('click', function(e) {
                e.preventDefault();
                $('#kelola-user-list .checklist-item:not(.d-none) input[type="checkbox"]').prop('checked', true).closest('.checklist-item').addClass('item-checked');
                updateKelolaUserCounter();
            });

            $('#btn-kelola-user-clear').on('click', function(e) {
                e.preventDefault();
                $('#kelola-user-list input[type="checkbox"]').prop('checked', false).closest('.checklist-item').removeClass('item-checked');
                updateKelolaUserCounter();
            });

            $(document).on('change', '#kelola-user-list input[type="checkbox"]', function() {
                if (this.checked) {
                    $(this).closest('.checklist-item').addClass('item-checked');
                } else {
                    $(this).closest('.checklist-item').removeClass('item-checked');
                }
                updateKelolaUserCounter();
            });

            $('#btnSaveKelolaUser').on('click', function() {
                saveKelolaUser();
            });
        }

        function openKelolaOffcanvas(pageId, pageName, activeTab = 'user') {
            $('#kelola_user_page_id').val(pageId);
            $('#kelola-offcanvas-page-badge').text('Halaman: ' + (pageName || 'Memuat...'));

            // Activate Tab
            if (activeTab === 'edit') {
                const editTabEl = document.querySelector('#tab-edit-btn');
                if (editTabEl) {
                    bootstrap.Tab.getOrCreateInstance(editTabEl).show();
                }
            } else {
                const userTabEl = document.querySelector('#tab-user-btn');
                if (userTabEl) {
                    bootstrap.Tab.getOrCreateInstance(userTabEl).show();
                }
            }

            // Show Offcanvas
            const offcanvasEl = document.getElementById('offcanvas-kelola');
            if (offcanvasEl) {
                bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl).show();
            }

            // Load data for both tabs
            loadKelolaUserData(pageId);
            loadKelolaEditData(pageId, pageName);
        }

        function openUserModal(pageId, pageName) {
            openKelolaOffcanvas(pageId, pageName, 'user');
        }

        function loadKelolaUserData(pageId) {
            $('#kelola-user-loading').removeClass('d-none');
            $('#kelola-user-search').val('');

            $.get(BASE + '/' + pageId + '/users')
                .done(function(res) {
                    $('#kelola-user-loading').addClass('d-none');
                    const users = res.users || [];
                    const assigned = new Set((res.assigned || []).map(String));

                    let html = '';
                    users.forEach(u => {
                        const isChecked = assigned.has(String(u.id));
                        html += `
                            <label class="checklist-item ${isChecked ? 'item-checked' : ''}" data-search="${(u.name + ' ' + u.email).toLowerCase()}">
                                <input type="checkbox" name="user_sync_ids[]" value="${u.id}" class="form-check-input" ${isChecked ? 'checked' : ''}>
                                <div class="d-flex align-items-center justify-content-between w-100 ms-1">
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size:0.85rem;">${e(u.name)}</div>
                                        <small class="text-muted" style="font-size:0.75rem;">${e(u.email)}</small>
                                    </div>
                                    <span class="badge bg-purple-lt small">${e(u.role)}</span>
                                </div>
                            </label>
                        `;
                    });

                    if (users.length === 0) {
                        html = '<div class="text-center py-4 text-muted small">Tidak ada user lain di organisasi ini.</div>';
                    }

                    $('#kelola-user-list').html(html);
                    updateKelolaUserCounter();
                })
                .fail(function() {
                    $('#kelola-user-list').html('<div class="text-center py-4 text-danger small">Gagal memuat data user.</div>');
                });
        }

        function updateKelolaUserCounter() {
            const count = $('#kelola-user-list input[type="checkbox"]:checked').length;
            $('#kelola_user_counter').text(count + ' user dipilih');
        }

        function saveKelolaUser() {
            const pageId = $('#kelola_user_page_id').val();
            const btn = $('#btnSaveKelolaUser');
            btn.prop('disabled', true);
            $('#btnSaveUserText').addClass('d-none');
            $('#btnSaveUserLoading').removeClass('d-none');

            const userIds = [];
            $('#kelola-user-list input[type="checkbox"]:checked').each(function() {
                userIds.push(this.value);
            });

            $.ajax({
                url: BASE + '/' + pageId + '/sync-users',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    user_ids: userIds
                }
            })
            .done(function(res) {
                const offcanvasEl = document.getElementById('offcanvas-kelola');
                if (offcanvasEl) {
                    bootstrap.Offcanvas.getInstance(offcanvasEl)?.hide();
                }
                showSuccessMessage(res.message);
                table.ajax.reload();
            })
            .fail(function() {
                showErrorMessage('Gagal memperbarui penugasan user.');
            })
            .always(function() {
                btn.prop('disabled', false);
                $('#btnSaveUserText').removeClass('d-none');
                $('#btnSaveUserLoading').addClass('d-none');
            });
        }

        // ============================================================
        // 4. CASCADING REGIONAL DROPDOWNS
        // ============================================================
        let editDistrictOptions = null;
        let editVillageOptions = null;
        let editHometownOptions = null;

        function initializeCascadingDropdowns() {
            editDistrictOptions = $('#districts_id option').clone();
            editVillageOptions = $('#villages_id option').clone();
            editHometownOptions = $('#hometowns_id option').clone();

            $(document).on('change', '.wz-regency-select', function() {
                const regencyId = $(this).val();
                const prefix = $(this).data('prefix');

                const districtSelect = prefix === 'new_vlan' ? $('#new_vlan_district_id') : (prefix === 'wz' ? $('#wz_districts_id') : $('#districts_id'));
                const villageSelect = prefix === 'new_vlan' ? $('#new_vlan_village_id') : (prefix === 'wz' ? $('#wz_villages_id') : $('#villages_id'));
                const hometownSelect = prefix === 'new_vlan' ? $('#new_vlan_hometown_id') : (prefix === 'wz' ? $('#wz_hometowns_id') : $('#hometowns_id'));

                districtSelect.empty().append('<option value="">-- Pilih Kecamatan --</option>');
                villageSelect.empty().append('<option value="">-- Pilih Desa --</option>');
                hometownSelect.empty().append('<option value="">-- Pilih Kampung --</option>');

                if (!regencyId) return;

                $.get("{{ url('master-network/vlan/districts') }}/" + regencyId, function(res) {
                    if (res.data) {
                        res.data.forEach(d => {
                            districtSelect.append(`<option value="${d.id}">${d.name}</option>`);
                        });
                    }
                });
            });

            $(document).on('change', '.wz-district-select', function() {
                const districtId = $(this).val();
                const prefix = $(this).data('prefix');

                const villageSelect = prefix === 'new_vlan' ? $('#new_vlan_village_id') : (prefix === 'wz' ? $('#wz_villages_id') : $('#villages_id'));
                const hometownSelect = prefix === 'new_vlan' ? $('#new_vlan_hometown_id') : (prefix === 'wz' ? $('#wz_hometowns_id') : $('#hometowns_id'));

                villageSelect.empty().append('<option value="">-- Pilih Desa --</option>');
                hometownSelect.empty().append('<option value="">-- Pilih Kampung --</option>');

                if (!districtId) return;

                $.get("{{ url('master-network/vlan/villages') }}/" + districtId, function(res) {
                    if (res.data) {
                        (res.data.villages || []).forEach(v => {
                            villageSelect.append(`<option value="${v.id}">${v.name}</option>`);
                        });
                        (res.data.hometowns || []).forEach(h => {
                            hometownSelect.append(`<option value="${h.id}">${h.name}</option>`);
                        });
                    }
                });
            });
        }

        // ============================================================
        // 5. EDIT MODAL LOGIC
        // ============================================================
        function initializeEditModalHandlers() {
            $('#storeBtn').on('click', function() {
                handleEditSave();
            });
        }

        function editModal(id) {
            openKelolaOffcanvas(id, '', 'edit');
        }

        function loadKelolaEditData(id, pageName) {
            isEditMode = true;
            $.get(BASE + '/' + id + '/show')
                .done(function(response) {
                    const data = response.data;
                    if (!data) {
                        showErrorMessage('Data halaman tidak ditemukan');
                        return;
                    }
                    resetModal();
                    if (data.name) {
                        $('#kelola-offcanvas-page-badge').text('Halaman: ' + data.name);
                    }

                    $('#id').val(data.id);
                    $('#page_version').val(data.version || 'complete');
                    $('#name').val(data.name || '');
                    $('#desc').val(data.desc || '');
                    $('#is_ktp').val(data.is_ktp || 'aktif');
                    $('#is_persetujuan').val(data.is_persetujuan || 'tidak');
                    $('#is_verification').val(data.is_verification || 'aktif');

                    $('#edit_support_pppoe').prop('checked', !!data.support_pppoe);
                    $('#edit_support_voucher').prop('checked', !!data.support_voucher);

                    $('#regencies_id').val(data.regencies_id || '');
                    $('#districts_id').val(data.districts_id || '');
                    $('#villages_id').val(data.villages_id || '');
                    $('#hometowns_id').val(data.hometowns_id || '');

                    const checkItems = (fieldName, items, key) => {
                        if (items && items.length > 0) {
                            const ids = items.map(item => String(item[key]));
                            $('#tab-pane-edit input[name="' + fieldName + '[]"]').each(function() {
                                if (ids.includes(String(this.value))) {
                                    $(this).prop('checked', true).closest('.checklist-item').addClass('item-checked');
                                }
                            });
                            updateChecklistBadge(fieldName);
                        }
                    };

                    checkItems('vlans_id', data.vlan, 'vlans_id');
                    checkItems('odcs_id', data.odc, 'odcs_id');
                    checkItems('odps_id', data.odp, 'odps_id');
                    checkItems('olts_id', data.olt, 'olts_id');
                    checkItems('paket_id', data.paket, 'paket_id');
                    checkItems('mic_radius_id', data.mic_radius, 'mic_radius_id');
                    checkItems('price', data.price, 'price_id');

                    const tipeItems = data.tipe_pelanggan || data.tipe_pelanggan_pages || data.tipePelanggan;
                    checkItems('tipe_pelanggan_id', tipeItems, 'tipe_pelanggan_id');

                    setTimeout(function() { isEditMode = false; }, 300);
                })
                .fail(function() {
                    isEditMode = false;
                    showErrorMessage('Terjadi kesalahan saat mengambil data');
                });
        }

        function handleEditSave() {
            const id = $('#id').val();
            if (!id) {
                showErrorMessage('ID halaman tidak ditemukan.');
                return;
            }
            const url = BASE + '/' + id + '/update-complete';

            const btn = $('#storeBtn');
            btn.prop('disabled', true);
            $('#btnText').addClass('d-none');
            $('#btnLoading').removeClass('d-none');

            let formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('_method', 'PUT');
            formData.append('name', $('#name').val() || '');
            formData.append('desc', $('#desc').val() || '');
            formData.append('regencies_id', $('#regencies_id').val() || '');
            formData.append('districts_id', $('#districts_id').val() || '');
            formData.append('hometowns_id', $('#hometowns_id').val() || '');
            formData.append('villages_id', $('#villages_id').val() || '');
            formData.append('is_ktp', $('#is_ktp').val() || '');
            formData.append('is_persetujuan', $('#is_persetujuan').val() || '');
            formData.append('is_verification', $('#is_verification').val() || 'aktif');
            formData.append('support_pppoe', $('#edit_support_pppoe').is(':checked') ? 1 : 0);
            formData.append('support_voucher', $('#edit_support_voucher').is(':checked') ? 1 : 0);

            multiFields.forEach(field => {
                $('#tab-pane-edit input[name="' + field + '[]"]:checked').each(function() {
                    formData.append(`${field}[]`, this.value);
                });
            });

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false
            })
            .done(function(response) {
                if (response.code === 400 || response.errors || response.status === 'errors') {
                    if (response.errors) {
                        showValidationErrors(response.errors);
                    } else {
                        showErrorMessage(response.message || 'Gagal menyimpan perubahan.');
                    }
                } else {
                    const offcanvasEl = document.getElementById('offcanvas-kelola');
                    if (offcanvasEl) {
                        bootstrap.Offcanvas.getInstance(offcanvasEl)?.hide();
                    }
                    showSuccessMessage(response.message || 'Data halaman berhasil diperbarui.');
                    if (table) {
                        table.ajax.reload(null, false);
                    }
                }
            })
            .fail(function(jqXHR) {
                if (jqXHR.status === 422 && jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                    showValidationErrors(jqXHR.responseJSON.errors);
                } else {
                    const msg = jqXHR.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data';
                    showErrorMessage(msg);
                }
            })
            .always(function() {
                resetButton(btn);
            });
        }

        function resetModal() {
            $('#id').val('');
            $('#name').val('');
            $('#desc').val('');
            $('#is_ktp').val('aktif');
            $('#is_persetujuan').val('aktif');
            $('#is_verification').val('aktif');
            $('#regencies_id').val('');

            if (editDistrictOptions && editDistrictOptions.length > 0) {
                $('#districts_id').empty().append(editDistrictOptions.clone());
            } else {
                $('#districts_id').val('');
            }
            if (editVillageOptions && editVillageOptions.length > 0) {
                $('#villages_id').empty().append(editVillageOptions.clone());
            } else {
                $('#villages_id').val('');
            }
            if (editHometownOptions && editHometownOptions.length > 0) {
                $('#hometowns_id').empty().append(editHometownOptions.clone());
            } else {
                $('#hometowns_id').val('');
            }

            $('#edit_support_pppoe').prop('checked', true);
            $('#edit_support_voucher').prop('checked', true);

            $('#tab-pane-edit .checklist-scrollbox input[type="checkbox"]').prop('checked', false);
            $('#tab-pane-edit .checklist-item').removeClass('item-checked d-none');
            $('#tab-pane-edit .checklist-search-input').val('');
            multiFields.forEach(f => updateChecklistBadge(f));
            clearValidationErrors();
        }

        function deleteType(id) {
            Swal.fire({
                title: "Peringatan !",
                text: "Anda yakin ingin menghapus data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal"
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: BASE + '/' + id + '/destroy',
                        method: 'DELETE',
                        data: { _token: $('meta[name="csrf-token"]').attr('content') }
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

        // ============================================================
        // 6. CHECKLIST INTERACTION HELPERS
        // ============================================================
        function initializeChecklistInteractions() {
            $(document).on('change', '.checklist-scrollbox input[type="checkbox"]', function() {
                const item = $(this).closest('.checklist-item');
                if (this.checked) {
                    item.addClass('item-checked');
                } else {
                    item.removeClass('item-checked');
                }
                const group = $(this).data('group');
                if (group) {
                    updateChecklistBadge(group);
                    if (group === 'wz_vlans_id') {
                        syncWizardWithSelectedVlans();
                    }
                }
            });

            $(document).on('input', '.checklist-search-input', function() {
                const group = $(this).data('target');
                const q = $(this).val().toLowerCase().trim();
                $(`#checklist-box-${group} .checklist-item`).each(function() {
                    const text = ($(this).data('search') || $(this).text()).toLowerCase();
                    if (text.indexOf(q) !== -1) {
                        $(this).removeClass('d-none');
                    } else {
                        $(this).addClass('d-none');
                    }
                });
            });

            $(document).on('click', '.btn-checklist-all', function(e) {
                e.preventDefault();
                const group = $(this).data('target');
                $(`#checklist-box-${group} .checklist-item:not(.d-none)`).find('input[type="checkbox"]').prop('checked', true).each(function() {
                    $(this).closest('.checklist-item').addClass('item-checked');
                });
                updateChecklistBadge(group);
                if (group === 'wz_vlans_id') {
                    syncWizardWithSelectedVlans();
                    renderSelectedVlanDetails();
                }
            });

            $(document).on('click', '.btn-checklist-clear', function(e) {
                e.preventDefault();
                const group = $(this).data('target');
                $(`#checklist-box-${group} .checklist-item input[type="checkbox"]`).prop('checked', false).each(function() {
                    $(this).closest('.checklist-item').removeClass('item-checked');
                });
                updateChecklistBadge(group);
                if (group === 'wz_vlans_id') {
                    syncWizardWithSelectedVlans();
                    renderSelectedVlanDetails();
                }
            });
        }

        function updateChecklistBadge(groupName) {
            let count = 0;
            if (groupName.startsWith('wz_')) {
                count = $(`#modal-wizard input[name="${groupName.replace('wz_', '')}[]"]:checked, #modal-wizard input[name="${groupName}[]"]:checked`).length;
            } else {
                count = $(`#tab-pane-edit input[name="${groupName}[]"]:checked`).length;
            }
            $(`#badge-${groupName}`).text(`${count} dipilih`);
            if (count > 0) {
                $(`.error_${groupName.replace('wz_', '')}`).text('').hide();
                $(`#card_${groupName}`).removeClass('border-danger');
            }
        }

        function clearValidationErrors() {
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.checklist-group-card, .choice-card, .premium-card').removeClass('border-danger');
            $('.invalid-feedback').text('').hide();
        }

        function showValidationErrors(errors) {
            clearValidationErrors();
            let firstErrorMsg = null;
            Object.keys(errors).forEach(function(field) {
                const clean = field.split('.')[0];
                const text = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                if (!firstErrorMsg) {
                    firstErrorMsg = text;
                }
                $('#' + clean + ', #wz_' + clean).addClass('is-invalid');
                $('#card_' + clean + ', #card_wz_' + clean).addClass('border-danger');
                $('.error_' + clean).text(text).show();
            });
            if (firstErrorMsg) {
                showErrorMessage(firstErrorMsg);
            }
        }

        function showSuccessMessage(msg) {
            Swal.fire({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: "success",
                title: msg
            });
        }

        function showErrorMessage(msg) {
            Swal.fire({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: "error",
                title: msg
            });
        }

        function resetButton(btn) {
            btn.prop('disabled', false);
            $('#btnText').removeClass('d-none');
            $('#btnLoading').addClass('d-none');
        }

        function initializePaginationAndSearch() {
            $("#sort").on('change', function() { table.page.len($(this).val()).draw(); });
            $("#search-input").on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    table.search(this.value).draw();
                }
            });
        }

        function initializeFilters() {
            // Backup initial options for cascading
            const districtOptions = $('#filter_district option').clone();
            const villageOptions = $('#filter_village option').clone();
            const hometownOptions = $('#filter_hometown option').clone();

            $('#filter_vlan, #filter_regency, #filter_district, #filter_village, #filter_hometown, #filter-organization').select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true
            });

            // Cascading Kab/Kota -> Kecamatan, Desa, Kampung
            $('#filter_regency').on('change', function() {
                const regId = $(this).val();

                // Districts
                const currentDist = $('#filter_district').val();
                $('#filter_district').empty();
                districtOptions.each(function() {
                    const r = $(this).data('regency');
                    if (!regId || !r || String(r) === String(regId) || !$(this).val()) {
                        $('#filter_district').append($(this).clone());
                    }
                });
                $('#filter_district').val($('#filter_district option[value="' + currentDist + '"]').length ? currentDist : '').trigger('change.select2');

                // Villages
                const currentVil = $('#filter_village').val();
                $('#filter_village').empty();
                villageOptions.each(function() {
                    const r = $(this).data('regency');
                    if (!regId || !r || String(r) === String(regId) || !$(this).val()) {
                        $('#filter_village').append($(this).clone());
                    }
                });
                $('#filter_village').val($('#filter_village option[value="' + currentVil + '"]').length ? currentVil : '').trigger('change.select2');

                // Hometowns
                const currentHome = $('#filter_hometown').val();
                $('#filter_hometown').empty();
                hometownOptions.each(function() {
                    const r = $(this).data('regency');
                    if (!regId || !r || String(r) === String(regId) || !$(this).val()) {
                        $('#filter_hometown').append($(this).clone());
                    }
                });
                $('#filter_hometown').val($('#filter_hometown option[value="' + currentHome + '"]').length ? currentHome : '').trigger('change.select2');

                updateActiveFilterBadge();
                table.ajax.reload();
            });

            // Cascading Kecamatan -> Desa, Kampung
            $('#filter_district').on('change', function() {
                const distId = $(this).val();

                if (distId) {
                    const currentVil = $('#filter_village').val();
                    $('#filter_village').empty();
                    villageOptions.each(function() {
                        const d = $(this).data('district');
                        if (!d || String(d) === String(distId) || !$(this).val()) {
                            $('#filter_village').append($(this).clone());
                        }
                    });
                    $('#filter_village').val($('#filter_village option[value="' + currentVil + '"]').length ? currentVil : '').trigger('change.select2');

                    const currentHome = $('#filter_hometown').val();
                    $('#filter_hometown').empty();
                    hometownOptions.each(function() {
                        const d = $(this).data('district');
                        if (!d || String(d) === String(distId) || !$(this).val()) {
                            $('#filter_hometown').append($(this).clone());
                        }
                    });
                    $('#filter_hometown').val($('#filter_hometown option[value="' + currentHome + '"]').length ? currentHome : '').trigger('change.select2');
                }

                updateActiveFilterBadge();
                table.ajax.reload();
            });

            // Other filters
            $('#filter_vlan, #filter_village, #filter_hometown, #filter-organization').on('change', function() {
                updateActiveFilterBadge();
                table.ajax.reload();
            });

            // Reset Button
            $('#btn-reset-filters').on('click', function() {
                $('#filter_vlan, #filter_regency, #filter_district, #filter_village, #filter_hometown, #filter-organization').val('').trigger('change.select2');
                $('#filter_district').empty().append(districtOptions.clone());
                $('#filter_village').empty().append(villageOptions.clone());
                $('#filter_hometown').empty().append(hometownOptions.clone());
                $('#search-input').val('');
                table.search('');
                updateActiveFilterBadge();
                table.ajax.reload();
            });
        }

        function updateActiveFilterBadge() {
            let count = 0;
            if ($('#filter_vlan').val()) count++;
            if ($('#filter_regency').val()) count++;
            if ($('#filter_district').val()) count++;
            if ($('#filter_village').val()) count++;
            if ($('#filter_hometown').val()) count++;
            if ($('#filter-organization').length && $('#filter-organization').val()) count++;

            const badge = $('#badge-active-filters');
            if (count > 0) {
                badge.text(count + ' Filter Aktif').show();
            } else {
                badge.hide();
            }
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
            const p = $('#custom-pagination');
            p.empty();
            if (info.pages <= 1) return;

            p.append(`<li class="page-item ${info.page === 0 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${info.page - 1}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></a></li>`);

            let s = Math.max(0, info.page - 2);
            let e = Math.min(info.pages - 1, info.page + 2);
            for (let i = s; i <= e; i++) {
                p.append(`<li class="page-item ${i === info.page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i + 1}</a></li>`);
            }

            p.append(`<li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${info.page + 1}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></a></li>`);

            p.find('a').on('click', function(evt) {
                evt.preventDefault();
                const page = parseInt($(this).data('page'));
                if (!isNaN(page) && page >= 0 && page < info.pages) table.page(page).draw('page');
            });
        }

        function e(str) {
            if (!str) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
    </script>
@endpush

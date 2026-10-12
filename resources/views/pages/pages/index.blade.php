@extends('layouts.app')

@section('title')
    Data Halaman
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <style>
        .empty-state {
            padding: 3rem 1rem;
        }

        .empty-state-icon {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .empty-state-icon svg {
            opacity: 0.3;
        }

        .empty-state-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .empty-state-subtitle {
            font-size: 0.95rem;
            line-height: 1.6;
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
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05) !important;
            transition: all 0.2s ease-in-out;
        }

        .premium-card:hover {
            border-color: #cbd5e1 !important;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.05), 0 4px 6px -4px rgb(0 0 0 / 0.05) !important;
        }

        .premium-card-header {
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0 !important;
            border-radius: 14px 14px 0 0 !important;
            padding: 0.75rem 1rem !important;
        }

        .premium-card-header .icon-wrapper {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Form styling */
        .form-label-premium {
            font-size: 0.82rem !important;
            font-weight: 600 !important;
            color: #475569 !important;
            margin-bottom: 0.35rem !important;
        }

        .form-control-premium {
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 0.88rem !important;
            padding: 0.45rem 0.75rem !important;
            transition: all 0.15s ease-in-out !important;
        }

        .form-control-premium:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15) !important;
        }

        /* Custom Checklist Box Component */
        .checklist-group-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #ffffff;
            padding: 0.65rem 0.75rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .checklist-group-card:focus-within {
            border-color: #4f46e5;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
        }
        .checklist-group-card.border-danger {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.12) !important;
        }
        .checklist-scrollbox {
            max-height: 155px;
            overflow-y: auto;
            border: 1px solid #f1f5f9;
            border-radius: 8px;
            padding: 0.35rem;
            background: #f8fafc;
        }
        .checklist-scrollbox::-webkit-scrollbar {
            width: 5px;
        }
        .checklist-scrollbox::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }
        .checklist-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.6rem;
            border-radius: 6px;
            font-size: 0.83rem;
            cursor: pointer;
            margin-bottom: 2px;
            transition: all 0.12s ease;
            user-select: none;
            background: #ffffff;
            border: 1px solid transparent;
        }
        .checklist-item:hover {
            background: #f1f5f9;
        }
        .checklist-item.item-checked {
            background: #eef2ff;
            border-color: #c7d2fe;
            color: #3730a3;
            font-weight: 500;
        }
        .checklist-item .form-check-input {
            cursor: pointer;
            width: 1.05rem;
            height: 1.05rem;
            margin: 0;
            flex-shrink: 0;
        }
        .checklist-search-input {
            font-size: 0.78rem !important;
            padding: 0.25rem 0.5rem !important;
            border-radius: 6px !important;
            border: 1px solid #e2e8f0 !important;
        }
        .counter-badge {
            font-size: 0.7rem !important;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            background: #e0e7ff;
            color: #4338ca;
            white-space: nowrap;
        }
        .btn-checklist-action {
            font-size: 0.74rem;
            padding: 0;
            text-decoration: none;
            color: #6366f1;
            font-weight: 500;
            cursor: pointer;
            background: none;
            border: none;
        }
        .btn-checklist-action:hover {
            color: #4338ca;
            text-decoration: underline;
        }
        .btn-checklist-clear {
            color: #ef4444 !important;
        }
        .btn-checklist-clear:hover {
            color: #dc2626 !important;
        }

        .is-invalid ~ .invalid-feedback,
        select.is-invalid ~ .invalid-feedback {
            display: block !important;
            color: #ef4444;
            font-size: 0.78rem !important;
            font-weight: 500;
            margin-top: 0.25rem;
        }

        /* Chevron rotation styling for collapsible cards */
        .premium-card-header[aria-expanded="false"] .chevron-arrow {
            transform: rotate(-90deg);
        }
        .chevron-arrow {
            transition: transform 0.2s ease-in-out;
        }
    </style>
@endpush

@section('content')
    <div class="org-card">
        <div class="org-header">
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
                    <h3 class="org-title">Data Halaman</h3>
                    <p class="org-subtitle mb-0">Kelola konfigurasi halaman layanan pelanggan</p>
                </div>
            </div>
            @can('buat halaman')
                <div class="org-actions">
                    <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple"
                        class="btn-add">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14m-7-7h14" />
                        </svg>
                        Tambah
                    </a>
                </div>
            @endcan
        </div>

        <div class="org-toolbar flex-wrap gap-2">
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
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <select name="filter_hometown" id="filter_hometown" class="org-input"
                    style="min-width: 180px; padding: 0.35rem 0.8rem;">
                    <option value="">Semua Kampung</option>
                    @foreach ($hometown as $ht)
                        <option value="{{ $ht->id }}">{{ $ht->name }}</option>
                    @endforeach
                </select>
                <select name="filter_village" id="filter_village" class="org-input"
                    style="min-width: 180px; padding: 0.35rem 0.8rem;">
                    <option value="">Semua Desa</option>
                    @foreach ($villages as $vlg)
                        <option value="{{ $vlg->id }}">{{ $vlg->name }}</option>
                    @endforeach
                </select>
            </div>
            @if (auth()->user()->hasPermissionTo('filter organization'))
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small fw-bold">Organisasi</span>
                    <select id="filter-organization" class="org-input" style="width:auto;padding:0.35rem 0.8rem;">
                        <option value="">Semua</option>
                        @foreach ($organizations as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="search-wrapper ms-auto">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="text" class="org-input" id="search-input" placeholder="Cari halaman..." autocomplete="off">
            </div>
        </div>

        <div id="halaman-table-wrapper" class="table-responsive">
            <table class="table org-table table-vcenter text-nowrap" id="halaman-table">
                <thead>
                    <tr>
                        <th class="w-1">No</th>
                        <th>Nama</th>
                        <th>VLAN</th>
                        <th>Fitur KTP</th>
                        <th>Fitur Persetujuan</th>
                        <th>Created</th>
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
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content modal-content-premium">
                <div class="modal-header modal-header-premium">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <line x1="9" y1="9" x2="15" y2="9"/>
                                <line x1="9" y1="13" x2="15" y2="13"/>
                                <line x1="9" y1="17" x2="15" y2="17"/>
                            </svg>
                        </div>
                        <h5 class="modal-title mb-0" style="color:white;font-weight:700;font-size:.95rem;">Tambah Halaman</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:1.5rem; background-color: #f8fafc;">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">
                    
                    <div class="row g-4">
                        <!-- Card 1: Informasi Halaman -->
                        <div class="col-12">
                            <div class="premium-card">
                                <div class="premium-card-header d-flex justify-content-between align-items-center" style="cursor: pointer;"
                                    data-bs-toggle="collapse" data-bs-target="#collapseInfoHalaman" aria-expanded="true" aria-controls="collapseInfoHalaman">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-wrapper">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                <path d="M9 3v18M15 3v18"/>
                                            </svg>
                                        </div>
                                        <span class="fw-bold text-dark" style="font-size: 0.88rem;">Informasi Halaman</span>
                                    </div>
                                    <div class="chevron-icon text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="chevron-arrow">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="collapse show" id="collapseInfoHalaman">
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label-premium">Judul Halaman <span style="color:#ef4444;">*</span></label>
                                                <input type="text" name="name" id="name" class="form-control form-control-premium"
                                                    placeholder="Masukkan judul halaman...">
                                                <span class="invalid-feedback error_name"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="desc" class="form-label-premium">Sub Judul Halaman <span style="color:#ef4444;">*</span></label>
                                                <input type="text" name="desc" id="desc" class="form-control form-control-premium"
                                                    placeholder="Masukkan sub judul...">
                                                <span class="invalid-feedback error_desc"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="checklist-group-card" id="card_tipe_pelanggan_id">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <label class="form-label-premium mb-0">Tipe Pelanggan <span style="color:#ef4444;">*</span></label>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="counter-badge" id="badge-tipe_pelanggan_id">0 dipilih</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-all" data-target="tipe_pelanggan_id">Semua</button>
                                                            <span class="text-muted" style="font-size: 0.72rem;">|</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="tipe_pelanggan_id">Batal</button>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari tipe pelanggan..." data-target="tipe_pelanggan_id">
                                                    <div class="checklist-scrollbox" id="checklist-box-tipe_pelanggan_id">
                                                        @foreach ($tipePelanggan as $tp)
                                                            <label class="checklist-item" data-search="{{ strtolower($tp->name) }}">
                                                                <input type="checkbox" name="tipe_pelanggan_id[]" class="form-check-input tipe_pelanggan_id-cb" value="{{ $tp->id }}" data-group="tipe_pelanggan_id">
                                                                <span class="checklist-text">{{ $tp->name }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <span class="invalid-feedback error_tipe_pelanggan_id"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="is_ktp" class="form-label-premium">Apakah Halaman Menggunakan KTP <span style="color:#ef4444;">*</span></label>
                                                <select name="is_ktp" id="is_ktp" class="form-select form-control-premium">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="aktif">Aktif</option>
                                                    <option value="tidak">Tidak</option>
                                                </select>
                                                <span class="invalid-feedback error_is_ktp"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="is_persetujuan" class="form-label-premium">Apakah Halaman Menggunakan Persetujuan <span style="color:#ef4444;">*</span></label>
                                                <select name="is_persetujuan" id="is_persetujuan" class="form-select form-control-premium">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="aktif">Aktif</option>
                                                    <option value="tidak">Tidak</option>
                                                </select>
                                                <span class="invalid-feedback error_is_persetujuan"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Area Regional -->
                        <div class="col-12">
                            <div class="premium-card">
                                <div class="premium-card-header d-flex justify-content-between align-items-center" style="cursor: pointer;"
                                    data-bs-toggle="collapse" data-bs-target="#collapseAreaRegional" aria-expanded="true" aria-controls="collapseAreaRegional">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"/>
                                                <circle cx="12" cy="10" r="3"/>
                                            </svg>
                                        </div>
                                        <span class="fw-bold text-dark" style="font-size: 0.88rem;">Area Regional</span>
                                    </div>
                                    <div class="chevron-icon text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="chevron-arrow">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="collapse show" id="collapseAreaRegional">
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="regencies_id" class="form-label-premium">Kabupaten/Kota <span style="color:#ef4444;">*</span></label>
                                                <select name="regencies_id" id="regencies_id" class="form-select form-control-premium">
                                                    <option value="">-- Pilih Kabupaten/Kota --</option>
                                                    @foreach ($regencies as $rgs)
                                                        <option value="{{ $rgs->id }}">{{ $rgs->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="invalid-feedback error_regencies_id"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="districts_id" class="form-label-premium">Kecamatan <span style="color:#ef4444;">*</span></label>
                                                <select name="districts_id" id="districts_id" class="form-select form-control-premium">
                                                    <option value="">-- Pilih Kecamatan --</option>
                                                    @foreach ($districts as $dst)
                                                        <option value="{{ $dst->id }}">{{ $dst->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="invalid-feedback error_districts_id"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="hometowns_id" class="form-label-premium">Kampung <span style="color:#ef4444;">*</span></label>
                                                <select name="hometowns_id" id="hometowns_id" class="form-select form-control-premium">
                                                    <option value="">-- Pilih Kampung --</option>
                                                    @foreach ($hometown as $ht)
                                                        <option value="{{ $ht->id }}">{{ $ht->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="invalid-feedback error_hometowns_id"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="villages_id" class="form-label-premium">Desa <span style="color:#ef4444;">*</span></label>
                                                <select name="villages_id" id="villages_id" class="form-select form-control-premium">
                                                    <option value="">-- Pilih Desa --</option>
                                                    @foreach ($villages as $vlg)
                                                        <option value="{{ $vlg->id }}">{{ $vlg->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="invalid-feedback error_villages_id"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Konfigurasi Jaringan & Layanan -->
                        <div class="col-12">
                            <div class="premium-card">
                                <div class="premium-card-header d-flex justify-content-between align-items-center" style="cursor: pointer;"
                                    data-bs-toggle="collapse" data-bs-target="#collapseKonfigurasi" aria-expanded="true" aria-controls="collapseKonfigurasi">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-wrapper" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"/>
                                                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"/>
                                                <line x1="6" y1="6" x2="6.01" y2="6"/>
                                                <line x1="6" y1="18" x2="6.01" y2="18"/>
                                            </svg>
                                        </div>
                                        <span class="fw-bold text-dark" style="font-size: 0.88rem;">Konfigurasi Jaringan &amp; Layanan</span>
                                    </div>
                                    <div class="chevron-icon text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="chevron-arrow">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="collapse show" id="collapseKonfigurasi">
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            <!-- VLAN Checklist -->
                                            <div class="col-md-6">
                                                <div class="checklist-group-card" id="card_vlans_id">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <label class="form-label-premium mb-0">Vlan <span style="color:#ef4444;">*</span></label>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="counter-badge" id="badge-vlans_id">0 dipilih</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-all" data-target="vlans_id">Semua</button>
                                                            <span class="text-muted" style="font-size: 0.72rem;">|</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="vlans_id">Batal</button>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari VLAN..." data-target="vlans_id">
                                                    <div class="checklist-scrollbox" id="checklist-box-vlans_id">
                                                        @foreach ($vlans as $vln)
                                                            <label class="checklist-item" data-search="{{ strtolower($vln->name) }}">
                                                                <input type="checkbox" name="vlans_id[]" class="form-check-input vlans_id-cb" value="{{ $vln->id }}" data-group="vlans_id">
                                                                <span class="checklist-text">{{ $vln->name }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <span class="invalid-feedback error_vlans_id"></span>
                                            </div>

                                            <!-- ODC Checklist -->
                                            <div class="col-md-6">
                                                <div class="checklist-group-card" id="card_odcs_id">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <label class="form-label-premium mb-0">Alamat ODC <span style="color:#ef4444;">*</span></label>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="counter-badge" id="badge-odcs_id">0 dipilih</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-all" data-target="odcs_id">Semua</button>
                                                            <span class="text-muted" style="font-size: 0.72rem;">|</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="odcs_id">Batal</button>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari ODC..." data-target="odcs_id">
                                                    <div class="checklist-scrollbox" id="checklist-box-odcs_id">
                                                        @foreach ($odcs as $odc)
                                                            <label class="checklist-item" data-search="{{ strtolower($odc->code . ' ' . ($odc->hometown->name ?? '') . ' ' . $odc->home_odc) }}">
                                                                <input type="checkbox" name="odcs_id[]" class="form-check-input odcs_id-cb" value="{{ $odc->id }}" data-group="odcs_id">
                                                                <span class="checklist-text">{{ $odc->code }} | {{ $odc->hometown->name ?? '-' }} | {{ $odc->rt->name ?? '-' }} | {{ $odc->rw->name ?? '-' }} | {{ $odc->home_odc }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <span class="invalid-feedback error_odcs_id"></span>
                                            </div>

                                            <!-- ODP Checklist -->
                                            <div class="col-md-6">
                                                <div class="checklist-group-card" id="card_odps_id">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <label class="form-label-premium mb-0">Alamat ODP <span style="color:#ef4444;">*</span></label>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="counter-badge" id="badge-odps_id">0 dipilih</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-all" data-target="odps_id">Semua</button>
                                                            <span class="text-muted" style="font-size: 0.72rem;">|</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="odps_id">Batal</button>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari ODP..." data-target="odps_id">
                                                    <div class="checklist-scrollbox" id="checklist-box-odps_id">
                                                        @foreach ($odps as $odp)
                                                            <label class="checklist-item" data-search="{{ strtolower($odp->code . ' ' . ($odp->hometown->name ?? '') . ' ' . $odp->home_odc) }}">
                                                                <input type="checkbox" name="odps_id[]" class="form-check-input odps_id-cb" value="{{ $odp->id }}" data-group="odps_id">
                                                                <span class="checklist-text">{{ $odp->code }} | {{ $odp->hometown->name ?? '-' }} | {{ $odp->rt->name ?? '-' }} | {{ $odp->rw->name ?? '-' }} | {{ $odp->home_odc }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <span class="invalid-feedback error_odps_id"></span>
                                            </div>

                                            <!-- OLT Checklist -->
                                            <div class="col-md-6">
                                                <div class="checklist-group-card" id="card_olts_id">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <label class="form-label-premium mb-0">Alamat OLT <span style="color:#ef4444;">*</span></label>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="counter-badge" id="badge-olts_id">0 dipilih</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-all" data-target="olts_id">Semua</button>
                                                            <span class="text-muted" style="font-size: 0.72rem;">|</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="olts_id">Batal</button>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari OLT..." data-target="olts_id">
                                                    <div class="checklist-scrollbox" id="checklist-box-olts_id">
                                                        @foreach ($olts as $olt)
                                                            <label class="checklist-item" data-search="{{ strtolower(($olt->hometown->name ?? '') . ' ' . $olt->name . ' ' . ($olt->code ?? '')) }}">
                                                                <input type="checkbox" name="olts_id[]" class="form-check-input olts_id-cb" value="{{ $olt->id }}" data-group="olts_id">
                                                                <span class="checklist-text">{{ $olt->hometown->name ?? '-' }} | {{ $olt->name }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <span class="invalid-feedback error_olts_id"></span>
                                            </div>

                                            <!-- Paket Checklist -->
                                            <div class="col-lg-12">
                                                <div class="checklist-group-card" id="card_paket_id">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <label class="form-label-premium mb-0">Tipe Paket <span style="color:#ef4444;">*</span></label>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="counter-badge" id="badge-paket_id">0 dipilih</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-all" data-target="paket_id">Semua</button>
                                                            <span class="text-muted" style="font-size: 0.72rem;">|</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="paket_id">Batal</button>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari tipe paket..." data-target="paket_id">
                                                    <div class="checklist-scrollbox" id="checklist-box-paket_id">
                                                        @foreach ($paket as $okt)
                                                            <label class="checklist-item" data-search="{{ strtolower($okt->name) }}">
                                                                <input type="checkbox" name="paket_id[]" class="form-check-input paket_id-cb" value="{{ $okt->id }}" data-group="paket_id">
                                                                <span class="checklist-text">{{ $okt->name }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <span class="invalid-feedback error_paket_id"></span>
                                            </div>

                                            <!-- Mic Radius Checklist -->
                                            <div class="col-lg-12">
                                                <div class="checklist-group-card" id="card_mic_radius_id">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <label class="form-label-premium mb-0">Mic Radius <span style="color:#ef4444;">*</span></label>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="counter-badge" id="badge-mic_radius_id">0 dipilih</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-all" data-target="mic_radius_id">Semua</button>
                                                            <span class="text-muted" style="font-size: 0.72rem;">|</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="mic_radius_id">Batal</button>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari mic radius..." data-target="mic_radius_id">
                                                    <div class="checklist-scrollbox" id="checklist-box-mic_radius_id">
                                                        @foreach ($micRadius as $mc)
                                                            <label class="checklist-item" data-search="{{ strtolower($mc->code . ' ' . $mc->name) }}">
                                                                <input type="checkbox" name="mic_radius_id[]" class="form-check-input mic_radius_id-cb" value="{{ $mc->id }}" data-group="mic_radius_id">
                                                                <span class="checklist-text">{{ $mc->code }} - {{ $mc->name }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <span class="invalid-feedback error_mic_radius_id"></span>
                                            </div>

                                            <!-- Price Checklist -->
                                            <div class="col-lg-12">
                                                <div class="checklist-group-card" id="card_price">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <label class="form-label-premium mb-0">Tipe Pembayaran <span style="color:#ef4444;">*</span></label>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="counter-badge" id="badge-price">0 dipilih</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-all" data-target="price">Semua</button>
                                                            <span class="text-muted" style="font-size: 0.72rem;">|</span>
                                                            <button type="button" class="btn-checklist-action btn-checklist-clear" data-target="price">Batal</button>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control checklist-search-input mb-2" placeholder="Cari tipe pembayaran..." data-target="price">
                                                    <div class="checklist-scrollbox" id="checklist-box-price">
                                                        @foreach ($price as $prc)
                                                            <label class="checklist-item" data-search="{{ strtolower($prc->name) }}">
                                                                <input type="checkbox" name="price[]" class="form-check-input price-cb" value="{{ $prc->id }}" data-group="price">
                                                                <span class="checklist-text">{{ $prc->name }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <span class="invalid-feedback error_price"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="telp" id="telp" value="123">
                    <input type="hidden" name="password" id="password" value="123">
                </div>
                <div class="modal-footer px-4 py-3 bg-light" style="border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-outline-secondary me-auto"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary px-4">
                        <span id="btnText">Simpan</span>
                        <span id="btnLoading" class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const BASE = "{{ route('halaman.index') }}";
        const vlanDataMap = @json($vlans->keyBy('id'));
        let isEditMode = false;
        let table;

        const multiFields = [
            'vlans_id', 'odcs_id', 'odps_id', 'olts_id',
            'paket_id', 'mic_radius_id', 'price', 'tipe_pelanggan_id'
        ];

        $(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeModalHandlers();
            initializeFilters();
            initializeChecklistInteractions();
        });

        function initializeDataTable() {
            table = $('#halaman-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE,
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.filter_hometown = $('#filter_hometown').val();
                        d.filter_village = $('#filter_village').val();
                        d.organization_id = $('#filter-organization').val();
                    }
                },
                order: [
                    [5, 'desc']
                ],
                pageLength: 10,
                dom: 'rt',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        defaultContent: '-'
                    },
                    {
                        data: 'vlan',
                        orderable: false,
                        defaultContent: '-'
                    },
                    {
                        data: 'is_ktp',
                        render: function(data) {
                            if (data === 'aktif') {
                                return '<span class="badge bg-success text-white">Aktif</span>';
                            } else {
                                return '<span class="badge bg-warning text-white">Tidak Aktif</span>';
                            }
                        }
                    },
                    {
                        data: 'is_persetujuan',
                        render: function(data) {
                            if (data === 'aktif') {
                                return '<span class="badge bg-success text-white">Aktif</span>';
                            } else {
                                return '<span class="badge bg-warning text-white">Tidak Aktif</span>';
                            }
                        }
                    },
                    {
                        data: 'created_at',
                        render: function(data) {
                            return moment(data).format('DD/MM/YYYY HH:mm:ss');
                        }
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                drawCallback: function(settings) {
                    updatePaginationInfo(settings);
                    updateCustomPagination();
                    handleEmptyState(settings);
                },
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                    emptyTable: "", // Kosongkan, kita akan custom
                    zeroRecords: "" // Kosongkan, kita akan custom
                }
            });
        }

        function handleEmptyState(settings) {
            const api = new $.fn.dataTable.Api(settings);
            const info = api.page.info();

            if (info.recordsDisplay === 0) {
                // Sembunyikan table header dan tampilkan empty state
                $('#halaman-table thead').hide();

                const isFiltered = $('#filter_hometown').val() || $('#filter_village').val() || $('#search-input').val();

                const emptyStateHTML = `
                    <tr class="empty-state-row">
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                        <path d="M9 14l6 0" />
                                        <path d="M12 11l0 6" />
                                    </svg>
                                </div>
                                <h3 class="empty-state-title text-muted">Tidak Ada Data</h3>
                                <p class="empty-state-subtitle text-muted mb-3">
                                    ${isFiltered ? 
                                        'Tidak ada data yang cocok dengan filter yang dipilih.<br>Silakan ubah filter atau hapus filter untuk melihat semua data.' : 
                                        'Belum ada data halaman yang tersedia.<br>Klik tombol "Tambah" untuk membuat data baru.'}
                                </p>
                                ${isFiltered ? `
                                        <button type="button" class="btn btn-primary" id="resetFiltersBtn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-filter-off">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M3 3l18 18" />
                                                <path d="M9 5h9.5a1 1 0 0 1 .5 1.5l-4.049 4.454m-.951 3.046v5l-4 -3v-4l-5 -5.5a1 1 0 0 1 .18 -1.316" />
                                            </svg>
                                            Reset Filter
                                        </button>
                                    ` : ''}
                            </div>
                        </td>
                    </tr>
                `;

                // Hapus row empty state yang lama jika ada
                $('#halaman-table tbody .empty-state-row').remove();

                // Tambahkan empty state
                $('#halaman-table tbody').html(emptyStateHTML);

                // Event handler untuk reset filter
                $('#resetFiltersBtn').on('click', function() {
                    $('#filter_hometown').val(null).trigger('change');
                    $('#filter_village').val(null).trigger('change');
                    $('#search-input').val('');
                    table.search('').draw();
                });
            } else {
                // Tampilkan kembali table header jika ada data
                $('#halaman-table thead').show();
                $('#halaman-table tbody .empty-state-row').remove();
            }
        }

        function initializePaginationAndSearch() {
            $("#sort").on('change', function() {
                table.page.len($(this).val()).draw();
            });

            $("#search-input").on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    table.search(this.value).draw();
                }
            });

            $("#search-btn").on('click', function(e) {
                e.preventDefault();
                table.search($("#search-input").val()).draw();
            });
        }

        function initializeFilters() {
            $('#filter_hometown').select2({
                theme: 'bootstrap-5',
                placeholder: 'Semua Kampung',
                allowClear: true
            });

            $('#filter_village').select2({
                theme: 'bootstrap-5',
                placeholder: 'Semua Desa',
                allowClear: true
            });

            $('#filter_hometown, #filter_village').on('change', function() {
                table.ajax.reload();
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

            // Previous button
            pagination.append(`
                <li class="page-item ${info.page === 0 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${info.page - 1}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </a>
                </li>
            `);

            let startPage = Math.max(0, info.page - 2);
            let endPage = Math.min(info.pages - 1, info.page + 2);

            // First page
            if (startPage > 0) {
                pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
                if (startPage > 1) {
                    pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
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
                    pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                }
                pagination.append(
                    `<li class="page-item"><a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a></li>`
                    );
            }

            // Next button
            pagination.append(`
                <li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${info.page + 1}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                </li>
            `);

            // Pagination click handler
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

        function initializeChecklistInteractions() {
            // Checkbox change listener
            $(document).on('change', '.checklist-scrollbox input[type="checkbox"]', function() {
                const $item = $(this).closest('.checklist-item');
                if (this.checked) {
                    $item.addClass('item-checked');
                } else {
                    $item.removeClass('item-checked');
                }
                const targetGroup = $(this).data('group');
                if (targetGroup) {
                    updateChecklistBadge(targetGroup);
                }
            });

            // Instant realtime search within each checklist box
            $(document).on('input', '.checklist-search-input', function() {
                const targetGroup = $(this).data('target');
                const query = $(this).val().toLowerCase().trim();
                $(`#checklist-box-${targetGroup} .checklist-item`).each(function() {
                    const text = ($(this).data('search') || $(this).text()).toLowerCase();
                    if (text.indexOf(query) !== -1) {
                        $(this).removeClass('d-none');
                    } else {
                        $(this).addClass('d-none');
                    }
                });
            });

            // "Semua" button: check all visible items in the group
            $(document).on('click', '.btn-checklist-all', function(e) {
                e.preventDefault();
                const targetGroup = $(this).data('target');
                const $visibleItems = $(`#checklist-box-${targetGroup} .checklist-item:not(.d-none)`);
                $visibleItems.find('input[type="checkbox"]').prop('checked', true).each(function() {
                    $(this).closest('.checklist-item').addClass('item-checked');
                });
                updateChecklistBadge(targetGroup);
                if (targetGroup === 'vlans_id') {
                    handleVlanChecklistChange();
                }
            });

            // "Batal" button: uncheck all items in the group
            $(document).on('click', '.btn-checklist-clear', function(e) {
                e.preventDefault();
                const targetGroup = $(this).data('target');
                $(`#checklist-box-${targetGroup} .checklist-item input[type="checkbox"]`).prop('checked', false).each(function() {
                    $(this).closest('.checklist-item').removeClass('item-checked');
                });
                updateChecklistBadge(targetGroup);
            });

            // VLAN selection triggers auto-check for regional fields, OLT, Paket, Mic Radius, Price
            $(document).on('change', 'input[name="vlans_id[]"]', function() {
                handleVlanChecklistChange();
            });
        }

        function updateChecklistBadge(groupName) {
            const totalChecked = $(`input[name="${groupName}[]"]:checked`).length;
            $(`#badge-${groupName}`).text(`${totalChecked} dipilih`);
            if (totalChecked > 0) {
                $(`.error_${groupName}`).text('');
                $(`#card_${groupName}`).removeClass('border-danger');
            }
        }

        function handleVlanChecklistChange() {
            if (isEditMode) return;

            const checkedVlanIds = [];
            $('input[name="vlans_id[]"]:checked').each(function() {
                checkedVlanIds.push(this.value);
            });

            if (checkedVlanIds.length > 0) {
                // 1. Auto-fill Area Regional dari VLAN terakhir yang dicentang
                const lastVlanId = checkedVlanIds[checkedVlanIds.length - 1];
                const targetVlan = vlanDataMap[lastVlanId];

                if (targetVlan) {
                    if (targetVlan.regencie_id) $('#regencies_id').val(targetVlan.regencie_id);
                    if (targetVlan.district_id) $('#districts_id').val(targetVlan.district_id);
                    if (targetVlan.village_id) $('#villages_id').val(targetVlan.village_id);
                    if (targetVlan.hometown_id) $('#hometowns_id').val(targetVlan.hometown_id);
                }

                // 2. Kumpulkan ID item berelasi dari semua VLAN yang dicentang
                const allowedOlts = new Set();
                const allowedPakets = new Set();
                const allowedRadiuses = new Set();
                const allowedPrices = new Set();

                checkedVlanIds.forEach(vId => {
                    const v = vlanDataMap[vId];
                    if (v) {
                        (v.olts || []).forEach(o => allowedOlts.add(String(o.id)));
                        (v.pakets || []).forEach(p => allowedPakets.add(String(p.id)));
                        (v.mix_radiuses || v.mixRadiuses || []).forEach(m => allowedRadiuses.add(String(m.id)));
                        (v.prices || []).forEach(pr => allowedPrices.add(String(pr.id)));
                    }
                });

                // 3. AUTO-CHECK item yang relevan TANPA MENGHAPUS / HIDE OPSI LAIN!
                if (allowedOlts.size > 0) {
                    $('input[name="olts_id[]"]').each(function() {
                        if (allowedOlts.has(String(this.value))) {
                            $(this).prop('checked', true).closest('.checklist-item').addClass('item-checked');
                        }
                    });
                    updateChecklistBadge('olts_id');
                }

                if (allowedPakets.size > 0) {
                    $('input[name="paket_id[]"]').each(function() {
                        if (allowedPakets.has(String(this.value))) {
                            $(this).prop('checked', true).closest('.checklist-item').addClass('item-checked');
                        }
                    });
                    updateChecklistBadge('paket_id');
                }

                if (allowedRadiuses.size > 0) {
                    $('input[name="mic_radius_id[]"]').each(function() {
                        if (allowedRadiuses.has(String(this.value))) {
                            $(this).prop('checked', true).closest('.checklist-item').addClass('item-checked');
                        }
                    });
                    updateChecklistBadge('mic_radius_id');
                }

                if (allowedPrices.size > 0) {
                    $('input[name="price[]"]').each(function() {
                        if (allowedPrices.has(String(this.value))) {
                            $(this).prop('checked', true).closest('.checklist-item').addClass('item-checked');
                        }
                    });
                    updateChecklistBadge('price');
                }
            }
        }

        function initializeModalHandlers() {
            $("#addBtn").on('click', function() {
                isEditMode = false;
                resetModal();
                $(".modal-title").text("Tambah Halaman");
                $("#type").val('create');
            });

            $("#storeBtn").on('click', function() {
                handleSave();
            });
        }

        function resetModal() {
            $("#id").val('');
            $("#name").val('');
            $("#desc").val('');
            $("#telp").val('123');
            $("#password").val('123');
            $("#is_ktp").val('');
            $("#is_persetujuan").val('');
            $('#regencies_id').val('');
            $('#districts_id').val('');
            $('#hometowns_id').val('');
            $('#villages_id').val('');

            // Uncheck all checklists and restore visibility
            $('.checklist-scrollbox input[type="checkbox"]').prop('checked', false);
            $('.checklist-item').removeClass('item-checked d-none');
            $('.checklist-search-input').val('');

            // Reset all counter badges
            multiFields.forEach(field => {
                updateChecklistBadge(field);
            });

            clearValidationErrors();
        }

        function clearValidationErrors() {
            $(".form-control, .form-select").removeClass('is-invalid');
            $(".checklist-group-card").removeClass('border-danger');
            $(".invalid-feedback").text('');
        }

        function handleSave() {
            const type = $("#type").val();
            const id = $("#id").val();
            const url = type === 'create' ? "{{ route('halaman.store') }}" : BASE + '/' + id + '/update';

            const btn = $("#storeBtn");
            btn.prop('disabled', true);
            $("#btnText").addClass('d-none');
            $("#btnLoading").removeClass('d-none');

            let formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('name', $('#name').val() || '');
            formData.append('desc', $('#desc').val() || '');
            formData.append('regencies_id', $('#regencies_id').val() || '');
            formData.append('districts_id', $('#districts_id').val() || '');
            formData.append('hometowns_id', $('#hometowns_id').val() || '');
            formData.append('villages_id', $('#villages_id').val() || '');
            formData.append('telp', $('#telp').val() || '123');
            formData.append('password', $('#password').val() || '123');
            formData.append('is_ktp', $('#is_ktp').val() || '');
            formData.append('is_persetujuan', $('#is_persetujuan').val() || '');

            // Append multiple checklist values as arrays
            multiFields.forEach(field => {
                $(`input[name="${field}[]"]:checked`).each(function() {
                    formData.append(`${field}[]`, this.value);
                });
            });

            if (type === 'update') {
                formData.append('_method', 'PUT');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false
            })
            .done(function(response) {
                if (response.errors) {
                    showValidationErrors(response.errors);
                    resetButton(btn);
                } else {
                    $("#modal-simple").modal('hide');
                    showSuccessMessage(response.message);
                    table.ajax.reload();
                    resetButton(btn);
                }
            })
            .fail(function(jqXHR) {
                if (jqXHR.status === 422 && jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                    showValidationErrors(jqXHR.responseJSON.errors);
                } else {
                    showErrorMessage("Terjadi kesalahan saat menyimpan data");
                }
                resetButton(btn);
            });
        }

        function editModal(id) {
            isEditMode = true;
            $.get(BASE + '/' + id + '/show')
                .done(function(response) {
                    const data = response.data;

                    resetModal();
                    $(".modal-title").text("Edit Halaman");
                    $("#modal-simple").modal('show');

                    $("#id").val(data.id);
                    $("#name").val(data.name);
                    $("#telp").val(data.telp);
                    $("#desc").val(data.desc);
                    $("#is_ktp").val(data.is_ktp);
                    $("#is_persetujuan").val(data.is_persetujuan || 'tidak');
                    $("#type").val('update');

                    // Set single selects
                    $('#regencies_id').val(data.regencies_id);
                    $('#districts_id').val(data.districts_id);
                    $('#hometowns_id').val(data.hometowns_id);
                    $('#villages_id').val(data.villages_id);

                    // Helper to check items
                    const checkItems = (fieldName, items, key) => {
                        if (items && items.length > 0) {
                            const ids = items.map(item => String(item[key]));
                            $(`input[name="${fieldName}[]"]`).each(function() {
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

                    setTimeout(function() {
                        isEditMode = false;
                    }, 300);
                })
                .fail(function() {
                    isEditMode = false;
                    showErrorMessage("Terjadi kesalahan saat mengambil data");
                });
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

        function showValidationErrors(errors) {
            clearValidationErrors();

            Object.keys(errors).forEach(function(field) {
                const cleanField = field.split('.')[0];
                const errorText = Array.isArray(errors[field]) ? errors[field][0] : errors[field];

                const element = $("#" + cleanField);
                if (element.length) {
                    element.addClass('is-invalid');
                }

                const cardElement = $("#card_" + cleanField);
                if (cardElement.length) {
                    cardElement.addClass('border-danger');
                }

                $(".error_" + cleanField).text(errorText);
            });

            setTimeout(function() {
                clearValidationErrors();
            }, 6000);
        }

        function showSuccessMessage(message) {
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
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            Toast.fire({
                icon: "error",
                title: message
            });
        }

        function resetButton(btn) {
            btn.prop('disabled', false);
            $("#btnText").removeClass('d-none');
            $("#btnLoading").addClass('d-none');
        }
    </script>
@endpush

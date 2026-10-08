@extends('layouts.app')

@section('title')
    Barang Operasional
@endsection

@push('css')
<style>
    .nav-tabs-custom {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 1.25rem;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        padding: 0.75rem 1.25rem;
        border-radius: 8px 8px 0 0;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .nav-tabs-custom .nav-link.active {
        color: #2563eb;
        background: transparent;
        border-bottom: 3px solid #2563eb;
    }
    .field-detection-box {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="org-container">
    @include('components.alert.success')

    <div class="org-card">
        {{-- HEADER --}}
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon" style="background:#eff6ff; color:#2563eb;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                        <path d="M12 12l8 -4.5" />
                        <path d="M12 12l0 9" />
                        <path d="M12 12l-8 -4.5" />
                    </svg>
                </div>
                <div>
                    <h5 class="org-title">Data Barang Operasional</h5>
                    <div class="org-subtitle">Katalog perangkat, stok operasional, distribusi user & pengiriman ke teknisi</div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('barang-operasional.riwayat') }}" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4l2 2"/><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5"/></svg>
                    Riwayat Transfer
                </a>

                @can('tambah barang operasional')
                <button id="addBtn" class="btn-add" data-bs-toggle="modal" data-bs-target="#modal-barang">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah Barang
                </button>
                @endcan
            </div>
        </div>

        {{-- TABS --}}
        <div class="px-4 pt-3">
            <ul class="nav nav-tabs nav-tabs-custom" id="barangTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-master-btn" data-bs-toggle="tab" data-bs-target="#tab-master" type="button" role="tab" aria-selected="true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 1a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1z"/><path d="M7 20h10"/><path d="M9 16v4"/><path d="M15 16v4"/></svg>
                        Katalog & Stok Master
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-mystock-btn" data-bs-toggle="tab" data-bs-target="#tab-mystock" type="button" role="tab" aria-selected="false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/></svg>
                        Stok di Akun Saya
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="barangTabContent">
            {{-- TAB 1: MASTER DATA BARANG --}}
            <div class="tab-pane fade show active" id="tab-master" role="tabpanel">
                {{-- TOOLBAR MASTER --}}
                <div class="org-toolbar">
                    <div style="font-size:.85rem; font-weight:600; color:var(--text-muted); display:flex; align-items:center; gap:.5rem;">
                        Tampilkan
                        <select id="sort-master" class="org-input" style="padding: .35rem .6rem;">
                            @foreach([10,25,50,100] as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                        data
                    </div>

                    <div style="display:flex; align-items:center; gap:.5rem; font-size:.85rem; font-weight:600; color:var(--text-muted);">
                        Filter Tipe
                        <select id="filter-tipe" class="org-input" style="padding: .35rem .6rem;">
                            <option value="">Semua Tipe</option>
                            @foreach ($tipeList as $t)
                                <option value="{{ $t->id }}">{{ $t->nama_tipe }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if (auth()->user()->hasPermissionTo('filter organization'))
                        <div style="display:flex; align-items:center; gap:.5rem; font-size:.85rem; font-weight:600; color:var(--text-muted);">
                            Organisasi
                            <select id="filter-org-master" class="org-input" style="padding: .35rem .6rem;">
                                <option value="">Semua Organisasi</option>
                                @foreach ($organizations as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="search-wrapper ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" id="search-master" class="org-input" placeholder="Cari barang, kode, SN, MAC…">
                    </div>
                </div>

                {{-- TABLE MASTER --}}
                <div class="table-responsive">
                    <table id="master-table" class="org-table">
                        <thead>
                            <tr>
                                <th style="width:40px; text-align:center;">No</th>
                                <th>Kode</th>
                                <th>Nama Barang & Identitas</th>
                                <th>Tipe</th>
                                <th style="text-align:center;">Stok Master</th>
                                @if (auth()->user()->hasPermissionTo('filter organization'))
                                    <th style="text-align:center;">Organisasi</th>
                                @endif
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                {{-- FOOTER MASTER --}}
                <div class="org-footer">
                    <div class="org-info" id="master-info">
                        Menampilkan <span id="master-start">0</span> sampai <span id="master-end">0</span> dari <span id="master-total">0</span> data
                    </div>
                    <ul class="pagination" id="master-pagination"></ul>
                </div>
            </div>

            {{-- TAB 2: STOK SAYA (USER LOGIN) --}}
            <div class="tab-pane fade" id="tab-mystock" role="tabpanel">
                <div class="org-toolbar">
                    <div style="font-size:.85rem; font-weight:600; color:var(--text-muted); display:flex; align-items:center; gap:.5rem;">
                        Tampilkan
                        <select id="sort-mystock" class="org-input" style="padding: .35rem .6rem;">
                            @foreach([10,25,50,100] as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                        data
                    </div>

                    <div class="search-wrapper ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" id="search-mystock" class="org-input" placeholder="Cari stok barang saya…">
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="mystock-table" class="org-table">
                        <thead>
                            <tr>
                                <th style="width:40px; text-align:center;">No</th>
                                <th>Kode</th>
                                <th>Nama Barang & Identitas</th>
                                <th>Tipe</th>
                                <th style="text-align:center;">Stok di Akun Anda</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="org-footer">
                    <div class="org-info" id="mystock-info">
                        Menampilkan <span id="mystock-start">0</span> sampai <span id="mystock-end">0</span> dari <span id="mystock-total">0</span> data
                    </div>
                    <ul class="pagination" id="mystock-pagination"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH / EDIT BARANG --}}
<div class="modal modal-blur fade" id="modal-barang" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="barangForm">
                @csrf
                <input type="hidden" id="barang_id" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBarangTitle">Tambah Barang Operasional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required font-weight-bold">Tipe Barang</label>
                            <select class="form-select" name="tipe_barang_id" id="tipe_barang_id" required>
                                <option value="">-- Pilih Tipe Barang --</option>
                                @foreach ($tipeList as $t)
                                    <option value="{{ $t->id }}" 
                                            data-mac="{{ $t->has_mac_address ? '1' : '0' }}" 
                                            data-sn="{{ $t->has_serial_number ? '1' : '0' }}">
                                        {{ $t->nama_tipe }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="err_tipe_barang_id"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required font-weight-bold">Nama Barang</label>
                            <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Contoh: ZTE F609 V3 / Fiber Cleaver FC-6S" required>
                            <div class="invalid-feedback" id="err_nama_barang"></div>
                        </div>
                    </div>

                    {{-- DETECTION NOTICE --}}
                    <div class="field-detection-box d-flex align-items-center justify-content-between" id="detectionNotice" style="display: none !important;">
                        <div class="d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9h.01"/><path d="M11 12h1v4h1"/><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"/></svg>
                            <span class="small" id="detectionText">Aturan deteksi tipe barang aktif.</span>
                        </div>
                        <div id="detectionBadges" class="d-flex gap-1"></div>
                    </div>

                    <div class="row">
                        {{-- MAC ADDRESS (DYNAMICALLY SHOWN) --}}
                        <div class="col-md-6 mb-3" id="wrapper_mac" style="display: none;">
                            <label class="form-label required font-weight-bold text-primary">MAC Address</label>
                            <input type="text" class="form-control" name="mac_address" id="mac_address" placeholder="Contoh: AA:BB:CC:11:22:33">
                            <small class="text-muted">Wajib diisi sesuai konfigurasi tipe barang ini</small>
                            <div class="invalid-feedback" id="err_mac_address"></div>
                        </div>

                        {{-- SERIAL NUMBER (DYNAMICALLY SHOWN) --}}
                        <div class="col-md-6 mb-3" id="wrapper_sn" style="display: none;">
                            <label class="form-label required font-weight-bold text-azure">Serial Number (SN)</label>
                            <input type="text" class="form-control" name="serial_number" id="serial_number" placeholder="Contoh: ZTEGC1234567">
                            <small class="text-muted">Wajib diisi sesuai konfigurasi tipe barang ini</small>
                            <div class="invalid-feedback" id="err_serial_number"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Merk / Brand</label>
                            <input type="text" class="form-control" name="merk" id="merk" placeholder="Contoh: ZTE, Huawei, Ilsintech">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label required font-weight-bold">Satuan</label>
                            <input type="text" class="form-control" name="satuan" id="satuan" placeholder="Unit, Pcs, Roll, Meter" value="Unit" required>
                            <div class="invalid-feedback" id="err_satuan"></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label required font-weight-bold">Stok Awal Master</label>
                            <input type="number" class="form-control" name="total_stok" id="total_stok" min="0" value="1" required>
                            <div class="invalid-feedback" id="err_total_stok"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Spesifikasi / Catatan Tambahan</label>
                        <textarea class="form-control" name="spesifikasi" id="spesifikasi" rows="2" placeholder="Keterangan spesifikasi atau catatan kondisi fisik barang (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto" id="btnBarangSubmit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
                        Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DISTRIBUSI (ADMIN MEMBERI KE USER) --}}
<div class="modal modal-blur fade" id="modal-distribusi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="distribusiForm">
                @csrf
                <input type="hidden" id="dist_barang_id" name="barang_id">
                <div class="modal-header">
                    <h5 class="modal-title">Beri Barang ke User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 small mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/><path d="M12 9h.01"/><path d="M11 12h1v4h1"/></svg>
                        Sebagai <strong>Admin</strong>, stok akun Anda tidak akan dipotong saat membagikan barang ini.
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Barang Terpilih</label>
                        <div class="fw-bold fs-3 text-dark" id="dist_nama_barang">-</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required font-weight-bold">Pilih User Penerima</label>
                        <select class="form-select" name="user_id" id="dist_user_id" required>
                            <option value="">-- Pilih User --</option>
                            @foreach ($usersList as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->username }})</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="err_dist_user_id"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required font-weight-bold">Jumlah Barang</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="jumlah" id="dist_jumlah" min="1" value="1" required>
                            <span class="input-group-text" id="dist_satuan_label">Unit</span>
                        </div>
                        <div class="invalid-feedback" id="err_dist_jumlah"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan / Keterangan</label>
                        <textarea class="form-control" name="catatan" id="dist_catatan" rows="2" placeholder="Catatan distribusi (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto" id="btnDistSubmit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l7 7-7 7"/><path d="M5 12h14"/></svg>
                        Kirim Barang ke User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL TRANSFER KE TEKNISI (DARI AKUN USER LOGIN) --}}
<div class="modal modal-blur fade" id="modal-transfer-teknisi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="transferTeknisiForm">
                @csrf
                <input type="hidden" id="trf_barang_id" name="barang_id">
                <div class="modal-header">
                    <h5 class="modal-title">Kirim Barang ke Teknisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning py-2 small mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01"/><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"/></svg>
                        Pengiriman ini akan <strong>memotong saldo/stok barang di akun Anda</strong>.
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Barang Terpilih</label>
                        <div class="fw-bold fs-3 text-dark" id="trf_nama_barang">-</div>
                        <div class="mt-1 small text-muted">Sisa Stok Anda: <span class="badge bg-green-lt fw-bold" id="trf_stok_saya">-</span></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required font-weight-bold">Pilih Teknisi Penerima</label>
                        <select class="form-select" name="teknisi_id" id="trf_teknisi_id" required>
                            <option value="">-- Pilih Teknisi --</option>
                            @foreach ($teknisiList as $tek)
                                <option value="{{ $tek->id }}">{{ $tek->name }} ({{ $tek->username }})</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="err_trf_teknisi_id"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required font-weight-bold">Jumlah yang Dikirim</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="jumlah" id="trf_jumlah" min="1" value="1" required>
                            <span class="input-group-text" id="trf_satuan_label">Unit</span>
                        </div>
                        <small class="text-muted">Maksimal pengiriman sesuai stok yang Anda pegang.</small>
                        <div class="invalid-feedback" id="err_trf_jumlah"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan / Keterangan</label>
                        <textarea class="form-control" name="catatan" id="trf_catatan" rows="2" placeholder="Catatan untuk teknisi (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-teal ms-auto" id="btnTrfSubmit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5"/></svg>
                        Kirim ke Teknisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        let isEdit = false;
        let myStockMax = 0;

        // 1. MASTER TABLE DATATABLE
        const masterTable = $('#master-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('barang-operasional.index') }}",
                data: function(d) {
                    d.scope = 'master';
                    d.tipe_barang_id = $('#filter-tipe').val();
                    d.organization_id = $('#filter-org-master').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'kode_barang', name: 'barang_operasionals.kode_barang' },
                { data: 'item_info', name: 'barang_operasionals.nama_barang' },
                { data: 'tipe_info', name: 'tipe_barang_operasionals.nama_tipe' },
                { data: 'stok_badge', name: 'barang_operasionals.total_stok', className: 'text-center' },
                @if (auth()->user()->hasPermissionTo('filter organization'))
                { data: 'organization_name', name: 'organization.name', className: 'text-center' },
                @endif
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
            ],
            dom: 't',
            pageLength: 10,
            drawCallback: function(settings) {
                const info = this.api().page.info();
                $('#master-start').text(info.recordsTotal > 0 ? info.start + 1 : 0);
                $('#master-end').text(info.end);
                $('#master-total').text(info.recordsTotal);
                buildPagination('#master-pagination', masterTable, info);
            }
        });

        // 2. MY STOCK DATATABLE
        const myStockTable = $('#mystock-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('barang-operasional.index') }}",
                data: function(d) {
                    d.scope = 'my_stock';
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'kode_barang', name: 'barang_operasionals.kode_barang' },
                { data: 'item_info', name: 'barang_operasionals.nama_barang' },
                { data: 'nama_tipe', name: 'tipe_barang_operasionals.nama_tipe' },
                { data: 'stok_badge', name: 'user_barang_operasionals.stok', className: 'text-center' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
            ],
            dom: 't',
            pageLength: 10,
            drawCallback: function(settings) {
                const info = this.api().page.info();
                $('#mystock-start').text(info.recordsTotal > 0 ? info.start + 1 : 0);
                $('#mystock-end').text(info.end);
                $('#mystock-total').text(info.recordsTotal);
                buildPagination('#mystock-pagination', myStockTable, info);
            }
        });

        // Pagination builder helper
        function buildPagination(containerId, dataTableInstance, info) {
            const $ul = $(containerId).empty();
            if (info.pages <= 1) return;

            $ul.append(`
                <li class="page-item ${info.page === 0 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${info.page - 1}">Sebelumnya</a>
                </li>
            `);

            for (let i = 0; i < info.pages; i++) {
                if (i === 0 || i === info.pages - 1 || (i >= info.page - 1 && i <= info.page + 1)) {
                    $ul.append(`
                        <li class="page-item ${i === info.page ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i + 1}</a>
                        </li>
                    `);
                } else if (i === info.page - 2 || i === info.page + 2) {
                    $ul.append('<li class="page-item disabled"><span class="page-link">…</span></li>');
                }
            }

            $ul.append(`
                <li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${info.page + 1}">Selanjutnya</a>
                </li>
            `);

            $ul.find('a').off('click').on('click', function(e) {
                e.preventDefault();
                const p = $(this).data('page');
                if (p !== undefined && p >= 0) dataTableInstance.page(p).draw('page');
            });
        }

        // Toolbar Events Master
        $('#sort-master').on('change', function() { masterTable.page.len($(this).val()).draw(); });
        $('#search-master').on('keyup', function() { masterTable.search(this.value).draw(); });
        $('#filter-tipe, #filter-org-master').on('change', function() { masterTable.draw(); });

        // Toolbar Events MyStock
        $('#sort-mystock').on('change', function() { myStockTable.page.len($(this).val()).draw(); });
        $('#search-mystock').on('keyup', function() { myStockTable.search(this.value).draw(); });

        // Tab switches reload tables
        $('#tab-mystock-btn').on('shown.bs.tab', function() { myStockTable.columns.adjust().draw(); });
        $('#tab-master-btn').on('shown.bs.tab', function() { masterTable.columns.adjust().draw(); });

        // ==========================================
        // DYNAMIC DETECTION OF MAC / SERIAL NUMBER
        // ==========================================
        $('#tipe_barang_id').on('change', function() {
            const selectedOpt = $(this).find('option:selected');
            const tipeId = $(this).val();

            if (!tipeId) {
                $('#detectionNotice').attr('style', 'display: none !important');
                $('#wrapper_mac').hide();
                $('#wrapper_sn').hide();
                $('#mac_address').prop('required', false).val('');
                $('#serial_number').prop('required', false).val('');
                return;
            }

            const hasMac = selectedOpt.data('mac') == '1';
            const hasSn = selectedOpt.data('sn') == '1';

            $('#detectionNotice').removeAttr('style');
            let badgesHtml = '';

            if (hasMac) {
                badgesHtml += '<span class="badge bg-purple text-white">Wajib MAC Address</span> ';
                $('#wrapper_mac').slideDown(200);
                $('#mac_address').prop('required', true);
            } else {
                $('#wrapper_mac').slideUp(200);
                $('#mac_address').prop('required', false).val('');
            }

            if (hasSn) {
                badgesHtml += '<span class="badge bg-azure text-white">Wajib Serial Number</span>';
                $('#wrapper_sn').slideDown(200);
                $('#serial_number').prop('required', true);
            } else {
                $('#wrapper_sn').slideUp(200);
                $('#serial_number').prop('required', false).val('');
            }

            if (!hasMac && !hasSn) {
                badgesHtml = '<span class="badge bg-secondary-lt">Tanpa MAC & SN (Bulk Item)</span>';
                $('#detectionText').text('Tipe ini tidak memerlukan MAC Address maupun Serial Number.');
            } else {
                $('#detectionText').text('Tipe ini memerlukan identitas fisik khusus:');
            }

            $('#detectionBadges').html(badgesHtml);
        });

        // Add Modal Reset
        $('#addBtn').on('click', function() {
            isEdit = false;
            $('#barangForm')[0].reset();
            $('#barang_id').val('');
            $('#modalBarangTitle').text('Tambah Barang Operasional');
            $('.form-control, .form-select').removeClass('is-invalid');
            $('#tipe_barang_id').trigger('change');
        });

        // Submit Barang Form
        $('#barangForm').on('submit', function(e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('#btnBarangSubmit').prop('disabled', true).text('Menyimpan...');

            const id = $('#barang_id').val();
            const url = isEdit
                ? `{{ url('barang-operasional') }}/${id}/update`
                : `{{ route('barang-operasional.store') }}`;

            const formData = $(this).serializeArray();
            if (isEdit) {
                formData.push({ name: '_method', value: 'PUT' });
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: $.param(formData),
                success: function(res) {
                    $('#btnBarangSubmit').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg> Simpan Barang');
                    if (res.code === 200) {
                        $('#modal-barang').modal('hide');
                        masterTable.ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else if (res.errors) {
                        $.each(res.errors, function(k, v) {
                            $(`#${k}`).addClass('is-invalid');
                            $(`#err_${k}`).text(v[0]);
                        });
                    }
                },
                error: function(xhr) {
                    $('#btnBarangSubmit').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg> Simpan Barang');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan sistem.'
                    });
                }
            });
        });

        // Edit Modal Populate
        window.editModal = function(id) {
            isEdit = true;
            $('#barangForm')[0].reset();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('#modalBarangTitle').text('Edit Data Barang Operasional');

            $.get(`{{ url('barang-operasional') }}/${id}/show`, function(res) {
                if (res.code === 200) {
                    const data = res.data;
                    $('#barang_id').val(data.id);
                    $('#tipe_barang_id').val(data.tipe_barang_id).trigger('change');
                    $('#nama_barang').val(data.nama_barang);
                    $('#merk').val(data.merk);
                    $('#satuan').val(data.satuan);
                    $('#total_stok').val(data.total_stok);
                    $('#spesifikasi').val(data.spesifikasi);
                    $('#mac_address').val(data.mac_address);
                    $('#serial_number').val(data.serial_number);
                    $('#modal-barang').modal('show');
                }
            });
        };

        // Delete Barang
        window.deleteBarang = function(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Barang ini akan dihapus dari katalog!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('barang-operasional') }}/${id}/destroy`,
                        type: 'DELETE',
                        success: function(res) {
                            if (res.code === 200) {
                                masterTable.ajax.reload(null, false);
                                myStockTable.ajax.reload(null, false);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: res.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: res.message
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan sistem.'
                            });
                        }
                    });
                }
            });
        };

        // ==========================================
        // ADMIN DISTRIBUSI MODAL (ADMIN -> USER)
        // ==========================================
        window.distribusiModal = function(barangId, namaBarang, satuan) {
            $('#distribusiForm')[0].reset();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('#dist_barang_id').val(barangId);
            $('#dist_nama_barang').text(namaBarang);
            $('#dist_satuan_label').text(satuan || 'Unit');
            $('#dist_jumlah').val(1);
            $('#modal-distribusi').modal('show');
        };

        $('#distribusiForm').on('submit', function(e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('#btnDistSubmit').prop('disabled', true).text('Mengirim...');

            $.ajax({
                url: "{{ route('barang-operasional.distribusi') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    $('#btnDistSubmit').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l7 7-7 7"/><path d="M5 12h14"/></svg> Kirim Barang ke User');
                    if (res.code === 200) {
                        $('#modal-distribusi').modal('hide');
                        myStockTable.ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Distribusi Berhasil',
                            text: res.message,
                            timer: 2500,
                            showConfirmButton: false
                        });
                    } else if (res.errors) {
                        $.each(res.errors, function(k, v) {
                            $(`#dist_${k}`).addClass('is-invalid');
                            $(`#err_dist_${k}`).text(v[0]);
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: res.message });
                    }
                },
                error: function(xhr) {
                    $('#btnDistSubmit').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l7 7-7 7"/><path d="M5 12h14"/></svg> Kirim Barang ke User');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Gagal mendistribusikan barang.'
                    });
                }
            });
        });

        // ==========================================
        // USER -> TEKNISI TRANSFER MODAL
        // ==========================================
        window.transferTeknisiModal = function(barangId, namaBarang, userStok, satuan) {
            myStockMax = parseInt(userStok) || 0;
            $('#transferTeknisiForm')[0].reset();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('#trf_barang_id').val(barangId);
            $('#trf_nama_barang').text(namaBarang);
            $('#trf_stok_saya').text(`${myStockMax} ${satuan}`);
            $('#trf_satuan_label').text(satuan || 'Unit');
            $('#trf_jumlah').val(1).attr('max', myStockMax);
            $('#modal-transfer-teknisi').modal('show');
        };

        $('#transferTeknisiForm').on('submit', function(e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');

            const jumlah = parseInt($('#trf_jumlah').val()) || 0;
            if (jumlah > myStockMax) {
                $('#trf_jumlah').addClass('is-invalid');
                $('#err_trf_jumlah').text(`Jumlah melebihi sisa stok Anda (${myStockMax}).`);
                return;
            }

            $('#btnTrfSubmit').prop('disabled', true).text('Mentransfer...');

            $.ajax({
                url: "{{ route('barang-operasional.transfer-teknisi') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    $('#btnTrfSubmit').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5"/></svg> Kirim ke Teknisi');
                    if (res.code === 200) {
                        $('#modal-transfer-teknisi').modal('hide');
                        myStockTable.ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Transfer Berhasil',
                            text: res.message,
                            timer: 2500,
                            showConfirmButton: false
                        });
                    } else if (res.errors) {
                        $.each(res.errors, function(k, v) {
                            $(`#trf_${k}`).addClass('is-invalid');
                            $(`#err_trf_${k}`).text(v[0]);
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: res.message });
                    }
                },
                error: function(xhr) {
                    $('#btnTrfSubmit').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5"/></svg> Kirim ke Teknisi');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Gagal mentransfer barang ke teknisi.'
                    });
                }
            });
        });
    });
</script>
@endpush

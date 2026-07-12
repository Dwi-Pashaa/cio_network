@extends('layouts.app')

@section('title', 'Open Ticket')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .modal-content-premium {
        border-radius: 18px;
        overflow: hidden;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,.1), 0 8px 10px -6px rgba(0,0,0,.1);
    }
    .modal-header-premium {
        background: linear-gradient(135deg, #1e1b4b, #4c1d95);
        border: none;
        padding: 1.25rem 1.5rem;
    }
    .modal-header-premium .modal-title {
        color: #fff;
        font-weight: 700;
        font-size: .95rem;
    }
    .premium-card {
        border-radius: 14px;
        background: #fff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,.05), 0 2px 4px -2px rgba(0,0,0,.05);
        transition: all .2s ease-in-out;
    }
    .premium-card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        border-radius: 14px 14px 0 0;
        padding: .75rem 1rem;
    }
    .icon-wrapper {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: rgba(79,70,229,.1);
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .form-label-premium {
        font-size: .82rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: .35rem;
    }
    .form-control-premium {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: .88rem;
        padding: .45rem .75rem;
        transition: all .15s ease-in-out;
    }
    .form-control-premium:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79,70,229,.15);
    }
</style>
@endpush

@section('content')
<div class="org-container">
    @include('components.alert.success')
    <div class="org-card">
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83a2 2 0 0 1 -2.83 0l-.06 -.06a1.65 1.65 0 0 0 -1.82 -.33a1.65 1.65 0 0 0 -1 1.51v.11a2 2 0 0 1 -2 2a2 2 0 0 1 -2 -2v-.06a1.65 1.65 0 0 0 -1.02 -1.51a1.65 1.65 0 0 0 -1.82 .33l-.06 .06a2 2 0 0 1 -2.83 0a2 2 0 0 1 0 -2.83l.06 -.06a1.65 1.65 0 0 0 .33 -1.82a1.65 1.65 0 0 0 -1.51 -1H3a2 2 0 0 1 -2 -2a2 2 0 0 1 2 -2h.06a1.65 1.65 0 0 0 1.51 -1.02a1.65 1.65 0 0 0 -.33 -1.82l-.06 -.06a2 2 0 0 1 0 -2.83a2 2 0 0 1 2.83 0l.06 .06a1.65 1.65 0 0 0 1.82 .33h.09a1.65 1.65 0 0 0 1.51 -1.02v-.12a2 2 0 0 1 2 -2a2 2 0 0 1 2 2v.06a1.65 1.65 0 0 0 1.02 1.51a1.65 1.65 0 0 0 1.82 -.33l.06 -.06a2 2 0 0 1 2.83 0a2 2 0 0 1 0 2.83l-.06 .06a1.65 1.65 0 0 0 -.33 1.82v.09a1.65 1.65 0 0 0 1.51 1.51h.11a2 2 0 0 1 2 2a2 2 0 0 1 -2 2h-.06a1.65 1.65 0 0 0 -1.51 1.02" />
                    </svg>
                </div>
                <div>
                    <h5 class="org-title">Open Ticket</h5>
                    <div class="org-subtitle">Daftar ticket troubleshoot pelanggan</div>
                </div>
            </div>

            @can('kelola troubleshoot')
                <a href="{{ route('troubleshoot.create') }}" class="btn-add">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Buat Ticket
                </a>
            @endcan
        </div>

        <div class="org-toolbar flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted" style="font-size: 0.88rem;">Tampilkan</span>
                <select id="sort" class="org-input" style="width: 80px; padding: 0.35rem 0.8rem;">
                    @foreach ([10, 25, 50, 100] as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
                <span class="text-muted" style="font-size: 0.88rem;">data</span>
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
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="text" class="org-input" id="search-input" placeholder="Cari..." autocomplete="off">
            </div>
        </div>

        <div class="table-responsive">
            <table class="org-table" id="troubleshoot-table">
                <thead>
                    <tr>
                        <th style="width:50px; text-align:center;">No</th>
                        <th>Pelanggan</th>
                        @if (auth()->user()->hasPermissionTo('filter organization'))
                            <th>Organisasi/Mitra</th>
                        @endif
                        <th>Teknisi</th>
                        <th>Status</th>
                        <th>Dibuat Oleh</th>
                        <th>Tanggal</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="org-footer border-top py-3 px-4 d-flex align-items-center justify-content-between">
            <p class="m-0 text-muted" style="font-size: 0.88rem;">
                Menampilkan <span id="start-entry" class="fw-medium">0</span>
                sampai <span id="end-entry" class="fw-medium">0</span> dari
                <span id="total-entries" class="fw-medium">0</span> data
            </p>
            <ul class="pagination m-0" id="custom-pagination"></ul>
        </div>
    </div>
</div>
@endsection

@push('modal')
{{-- Edit Modal --}}
<div class="modal modal-blur fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-content-premium">
            <div class="modal-header modal-header-premium">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83a2 2 0 0 1 -2.83 0l-.06 -.06a1.65 1.65 0 0 0 -1.82 -.33a1.65 1.65 0 0 0 -1 1.51v.11a2 2 0 0 1 -2 2a2 2 0 0 1 -2 -2v-.06a1.65 1.65 0 0 0 -1.02 -1.51a1.65 1.65 0 0 0 -1.82 .33l-.06 .06a2 2 0 0 1 -2.83 0a2 2 0 0 1 0 -2.83l.06 -.06a1.65 1.65 0 0 0 .33 -1.82a1.65 1.65 0 0 0 -1.51 -1H3a2 2 0 0 1 -2 -2a2 2 0 0 1 2 -2h.06a1.65 1.65 0 0 0 1.51 -1.02a1.65 1.65 0 0 0 -.33 -1.82l-.06 -.06a2 2 0 0 1 0 -2.83a2 2 0 0 1 2.83 0l.06 .06a1.65 1.65 0 0 0 1.82 .33h.09a1.65 1.65 0 0 0 1.51 -1.02v-.12a2 2 0 0 1 2 -2a2 2 0 0 1 2 2v.06a1.65 1.65 0 0 0 1.02 1.51a1.65 1.65 0 0 0 1.82 -.33l.06 -.06a2 2 0 0 1 2.83 0a2 2 0 0 1 0 2.83l-.06 .06a1.65 1.65 0 0 0 -.33 1.82v.09a1.65 1.65 0 0 0 1.51 1.51h.11a2 2 0 0 1 2 2a2 2 0 0 1 -2 2h-.06a1.65 1.65 0 0 0 -1.51 1.02" />
                        </svg>
                    </div>
                    <h5 class="modal-title mb-0">Edit Ticket</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem; background:#f8fafc;">
                <input type="hidden" id="edit_id">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="premium-card">
                            <div class="premium-card-header d-flex align-items-center gap-2">
                                <div class="icon-wrapper" style="background:rgba(14,165,233,.1);color:#0ea5e9;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                    </svg>
                                </div>
                                <span class="fw-bold text-dark" style="font-size:.88rem;" id="editCustomerName">Informasi Ticket</span>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label-premium">Pelanggan</label>
                                        <p class="fw-semibold mb-0" id="editCustomerInfo" style="padding:.45rem 0;">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-premium">Status</label>
                                        <select id="edit_status" class="form-control form-control-premium">
                                            <option value="open">Open</option>
                                            <option value="menuju_lokasi">Menuju Lokasi</option>
                                            <option value="tiba_lokasi">Tiba di Lokasi</option>
                                            <option value="perbaikan">Perbaikan</option>
                                            <option value="done">Done</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit_technician_id" class="form-label-premium">Teknisi <span style="color:#ef4444;">*</span></label>
                                        <select id="edit_technician_id" class="form-select">
                                            <option value="">-- Pilih Teknisi --</option>
                                        </select>
                                        <span class="invalid-feedback error_edit_technician_id"></span>
                                    </div>
                                    <div class="col-12">
                                        <label for="edit_description" class="form-label-premium">Deskripsi <span style="color:#ef4444;">*</span></label>
                                        <textarea id="edit_description" class="form-control form-control-premium" rows="3"></textarea>
                                        <span class="invalid-feedback error_edit_description"></span>
                                    </div>
                                    <div class="col-12">
                                        <label for="edit_notes" class="form-label-premium">Catatan</label>
                                        <textarea id="edit_notes" class="form-control form-control-premium" rows="2" placeholder="Catatan (opsional)"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer px-4 py-3 bg-light" style="border-top:1px solid #e2e8f0;">
                <button type="button" class="btn btn-outline-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="editSaveBtn" class="btn btn-primary px-4">
                    <span id="editBtnText">Simpan Perubahan</span>
                    <span id="editBtnLoading" class="spinner-border spinner-border-sm d-none"></span>
                </button>
            </div>
        </div>
    </div>
</div>
{{-- Detail Modal --}}
<div class="modal modal-blur fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-content-premium">
            <div class="modal-header modal-header-premium">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83a2 2 0 0 1 -2.83 0l-.06 -.06a1.65 1.65 0 0 0 -1.82 -.33a1.65 1.65 0 0 0 -1 1.51v.11a2 2 0 0 1 -2 2a2 2 0 0 1 -2 -2v-.06a1.65 1.65 0 0 0 -1.02 -1.51a1.65 1.65 0 0 0 -1.82 .33l-.06 .06a2 2 0 0 1 -2.83 0a2 2 0 0 1 0 -2.83l.06 -.06a1.65 1.65 0 0 0 .33 -1.82a1.65 1.65 0 0 0 -1.51 -1H3a2 2 0 0 1 -2 -2a2 2 0 0 1 2 -2h.06a1.65 1.65 0 0 0 1.51 -1.02a1.65 1.65 0 0 0 -.33 -1.82l-.06 -.06a2 2 0 0 1 0 -2.83a2 2 0 0 1 2.83 0l.06 .06a1.65 1.65 0 0 0 1.82 .33h.09a1.65 1.65 0 0 0 1.51 -1.02v-.12a2 2 0 0 1 2 -2a2 2 0 0 1 2 2v.06a1.65 1.65 0 0 0 1.02 1.51a1.65 1.65 0 0 0 1.82 -.33l.06 -.06a2 2 0 0 1 2.83 0a2 2 0 0 1 0 2.83l-.06 .06a1.65 1.65 0 0 0 -.33 1.82v.09a1.65 1.65 0 0 0 1.51 1.51h.11a2 2 0 0 1 2 2a2 2 0 0 1 -2 2h-.06a1.65 1.65 0 0 0 -1.51 1.02" />
                        </svg>
                    </div>
                    <h5 class="modal-title mb-0">Detail Progress Ticket</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem; background:#f8fafc;">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="premium-card">
                            <div class="premium-card-header d-flex align-items-center gap-2">
                                <div class="icon-wrapper" style="background:rgba(14,165,233,.1);color:#0ea5e9;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                    </svg>
                                </div>
                                <span class="fw-bold text-dark" style="font-size:.88rem;" id="detailTicketTitle">Informasi Ticket</span>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label-premium">Pelanggan</label>
                                        <p class="fw-semibold mb-0" id="detailCustomer" style="padding:.45rem 0;">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-premium">Teknisi</label>
                                        <p class="fw-semibold mb-0" id="detailTechnician" style="padding:.45rem 0;">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-premium">Status</label>
                                        <p class="mb-0" id="detailStatus" style="padding:.45rem 0;">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-premium">Tanggal Dibuat</label>
                                        <p class="fw-semibold mb-0" id="detailDate" style="padding:.45rem 0;">-</p>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label-premium">Deskripsi</label>
                                        <p class="fw-semibold mb-0" id="detailDescription" style="padding:.45rem 0;">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="premium-card">
                            <div class="premium-card-header d-flex align-items-center gap-2">
                                <div class="icon-wrapper" style="background:rgba(34,197,94,.1);color:#22c55e;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h11"/>
                                    </svg>
                                </div>
                                <span class="fw-bold text-dark" style="font-size:.88rem;">Progress Teknisi</span>
                            </div>
                            <div class="card-body p-3" id="detailProgressList">
                                <p class="text-muted text-center mb-0 py-3">Memuat data progress...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer px-4 py-3 bg-light" style="border-top:1px solid #e2e8f0;">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Image Preview Modal --}}
<div class="modal modal-blur fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content modal-content-premium" style="background:#000;">
            <div class="modal-header" style="border:none; padding:0.75rem 1rem; position:absolute; top:0; right:0; z-index:10;">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center" style="min-height:60vh;">
                <img id="previewImage" src="" alt="Preview" style="max-width:100%; max-height:80vh; object-fit:contain;">
            </div>
        </div>
    </div>
</div>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const BASE = "{{ route('troubleshoot.index') }}";
    let table;
    let editSelect2;

    $(function() {
        const hasOrgFilter = {{ auth()->user()->hasPermissionTo('filter organization') ? 'true' : 'false' }};

        table = $('#troubleshoot-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: BASE,
                data: function(d) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    if (hasOrgFilter) {
                        d.organization_id = $('#filter-organization').val();
                    }
                }
            },
            order: [[hasOrgFilter ? 6 : 5, 'desc']],
            pageLength: 10,
            dom: 'rt',
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'customer_name', defaultContent: '-' },
                @if (auth()->user()->hasPermissionTo('filter organization'))
                { data: 'organization_name', defaultContent: '-' },
                @endif
                { data: 'technician_name', defaultContent: '-' },
                { data: 'status', defaultContent: '-' },
                { data: 'creator_name', defaultContent: '-' },
                { data: 'created_at', render: function(data) {
                    return data ? moment(data).format('DD/MM/YYYY HH:mm:ss') : '-';
                }},
                { data: 'action', orderable: false, searchable: false }
            ],
            drawCallback: function(settings) {
                updatePaginationInfo(settings);
                updateCustomPagination();
            },
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: 'Tidak ada data',
                zeroRecords: 'Tidak ada data yang cocok',
                info: '',
                infoEmpty: '',
                infoFiltered: ''
            }
        });

        $('#sort').on('change', function() {
            table.page.len($(this).val()).draw();
        });

        $('#search-input').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                table.search(this.value).draw();
            }
        });

        @if (auth()->user()->hasPermissionTo('filter organization'))
        $('#filter-organization').on('change', function() {
            table.ajax.reload();
        });
        @endif
    });

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

        pagination.append(`<li class="page-item ${info.page === 0 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${info.page - 1}">Prev</a></li>`);

        let startPage = Math.max(0, info.page - 2);
        let endPage = Math.min(info.pages - 1, info.page + 2);

        if (startPage > 0) {
            pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
            if (startPage > 1) {
                pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            pagination.append(`<li class="page-item ${i === info.page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i + 1}</a></li>`);
        }

        if (endPage < info.pages - 1) {
            if (endPage < info.pages - 2) {
                pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
            }
            pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a></li>`);
        }

        pagination.append(`<li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${info.page + 1}">Next</a></li>`);

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

    function editModal(id) {
        $('#edit_id').val(id);
        $('#editCustomerName').text('Memuat data...');
        $('#editCustomerInfo').text('-');
        $('#edit_description').val('');
        $('#edit_notes').val('');

        if (editSelect2) {
            editSelect2.select2('destroy');
        }

        $.get(BASE + '/' + id + '/edit')
            .done(function(res) {
                const t = res.troubleshoot;
                $('#editCustomerName').text('Ticket #' + t.id + ' — ' + (t.customer?.name || '-'));
                $('#editCustomerInfo').html(
                    '<span class="text-muted">' + (t.customer?.name || '-') + ' &middot; ' + (t.customer?.mac_address || '-') + '</span>'
                );
                $('#edit_status').val(t.status);
                $('#edit_description').val(t.description);
                $('#edit_notes').val(t.notes || '');

                const techSelect = $('#edit_technician_id');
                techSelect.empty().append('<option value="">-- Pilih Teknisi --</option>');
                $.each(res.technicians, function(i, tech) {
                    techSelect.append('<option value="' + tech.id + '">' + tech.name + '</option>');
                });
                techSelect.val(t.technician_id);

                editSelect2 = techSelect.select2({
                    theme: 'bootstrap-5',
                    placeholder: '-- Pilih Teknisi --',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#editModal')
                });

                $('#editModal').modal('show');
            })
            .fail(function() {
                Swal.fire('Error', 'Gagal memuat data ticket.', 'error');
            });
    }

    $('#editSaveBtn').on('click', function() {
        const id = $('#edit_id').val();
        const btn = $(this);
        btn.prop('disabled', true);
        $('#editBtnText').addClass('d-none');
        $('#editBtnLoading').removeClass('d-none');

        $('.invalid-feedback').text('');
        $('.form-control-premium, .form-select').removeClass('is-invalid');

        $.ajax({
            url: BASE + '/' + id,
            method: 'PUT',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                technician_id: $('#edit_technician_id').val(),
                description: $('#edit_description').val(),
                status: $('#edit_status').val(),
                notes: $('#edit_notes').val(),
            },
            success: function(res) {
                $('#editModal').modal('hide');
                Swal.fire('Berhasil', res.message || 'Ticket berhasil diupdate.', 'success');
                table.ajax.reload();
            },
            error: function(xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(field) {
                        const el = $('#edit_' + field);
                        el.addClass('is-invalid');
                        $('.error_edit_' + field).text(errors[field][0]);
                    });
                } else {
                    Swal.fire('Error', 'Gagal mengupdate ticket.', 'error');
                }
            },
            complete: function() {
                btn.prop('disabled', false);
                $('#editBtnText').removeClass('d-none');
                $('#editBtnLoading').addClass('d-none');
            }
        });
    });

    $('#editModal').on('hidden.bs.modal', function() {
        if (editSelect2) {
            editSelect2.select2('destroy');
            editSelect2 = null;
        }
    });

    function detailModal(id) {
        $('#detailTicketTitle').text('Memuat data...');
        $('#detailCustomer').text('-');
        $('#detailTechnician').text('-');
        $('#detailStatus').text('-');
        $('#detailDate').text('-');
        $('#detailDescription').text('-');
        $('#detailProgressList').html('<p class="text-muted text-center mb-0 py-3">Memuat data progress...</p>');

        $.get(BASE + '/' + id + '/detail')
            .done(function(res) {
                const t = res.troubleshoot;

                let statusBadge = '';
                const statusMap = {
                    'open': 'badge bg-warning text-white',
                    'menuju_lokasi': 'badge bg-info text-white',
                    'tiba_lokasi': 'badge bg-primary text-white',
                    'perbaikan': 'badge bg-indigo text-white',
                    'done': 'badge bg-success text-white',
                    'cancelled': 'badge bg-danger text-white',
                };
                const badgeClass = statusMap[t.status] || 'badge bg-secondary text-white';
                const statusLabel = t.status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                statusBadge = '<span class="' + badgeClass + '">' + statusLabel + '</span>';

                $('#detailTicketTitle').text('Ticket #' + t.id + ' — ' + (t.customer?.name || '-'));
                $('#detailCustomer').html('<span class="text-muted">' + (t.customer?.name || '-') + ' &middot; ' + (t.customer?.mac_address || '-') + '</span>');
                $('#detailTechnician').text(t.technician?.name || '-');
                $('#detailStatus').html(statusBadge);
                $('#detailDate').text(t.created_at ? moment(t.created_at).format('DD/MM/YYYY HH:mm:ss') : '-');
                $('#detailDescription').text(t.description || '-');

                let progressHtml = '';
                $.each(res.progress, function(i, p) {
                    const isCompleted = p.status === 'completed';
                    const iconColor = isCompleted ? '#22c55e' : '#94a3b8';
                    const bgColor = isCompleted ? 'rgba(34,197,94,.1)' : 'rgba(148,163,184,.1)';
                    const textColor = isCompleted ? 'text-success' : 'text-muted';

                    progressHtml += `
                        <div class="d-flex align-items-start gap-3 mb-3 p-3 rounded" style="background:${bgColor}; border: 1px solid ${isCompleted ? 'rgba(34,197,94,.2)' : 'rgba(148,163,184,.2)'};">
                            <div class="flex-shrink-0" style="width:36px;height:36px;border-radius:50%;background:${iconColor};display:flex;align-items:center;justify-content:center;">
                                ${isCompleted
                                    ? '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>'
                                    : '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/></svg>'
                                }
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold ${textColor}" style="font-size:.9rem;">Langkah ${p.step}: ${p.label}</h6>
                                    <span class="badge ${isCompleted ? 'bg-success' : 'bg-secondary'}">${isCompleted ? 'Selesai' : 'Belum'}</span>
                                </div>
                                ${isCompleted && p.photo
                                    ? `<div class="mt-2">
                                        <img src="${p.photo}" alt="Foto step ${p.step}" class="rounded cursor-pointer" style="max-width:180px;max-height:120px;object-fit:cover;border:1px solid #e2e8f0;cursor:pointer;" onclick="previewImage('${p.photo}')">
                                        ${p.address ? `<p class="mt-1 mb-0 small ${textColor}"><a href="${p.latitude && p.longitude ? `https://www.google.com/maps?q=${p.latitude},${p.longitude}` : `https://www.google.com/maps/search/${encodeURIComponent(p.address)}`}" target="_blank" rel="noopener noreferrer" class="${textColor} text-decoration-none"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> ${p.address}</a></p>` : ''}
                                        ${p.updated_at ? `<p class="mb-0 small ${textColor}"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> ${p.updated_at}</p>` : ''}
                                    </div>`
                                    : (isCompleted && !p.photo
                                        ? `<p class="mt-1 mb-0 small ${textColor}">Selesai (tanpa foto)</p>`
                                        : `<p class="mt-1 mb-0 small text-muted">Belum dikerjakan</p>`)
                                }
                            </div>
                        </div>
                    `;
                });

                $('#detailProgressList').html(progressHtml);
                $('#detailModal').modal('show');
            })
            .fail(function() {
                Swal.fire('Error', 'Gagal memuat detail ticket.', 'error');
            });
    }

    function previewImage(src) {
        $('#previewImage').attr('src', src);
        $('#imagePreviewModal').modal('show');
    }

    function deleteTicket(id) {
        Swal.fire({
            title: 'Hapus Ticket?',
            text: 'Data ticket dan semua progress akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: BASE + '/' + id,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(res) {
                        Swal.fire('Berhasil', res.message || 'Ticket berhasil dihapus.', 'success');
                        table.ajax.reload();
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghapus ticket.', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush

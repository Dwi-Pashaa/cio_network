@extends('layouts.app')

@section('title')
    Tipe Barang Operasional
@endsection

@push('css')
<style>
    .bo-badge-active {
        background: #ecfdf5;
        color: #059669;
        font-weight: 600;
        border: 1px solid #a7f3d0;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .bo-badge-inactive {
        background: #f1f5f9;
        color: #64748b;
        font-weight: 500;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.78rem;
    }
    .form-switch .form-check-input {
        width: 2.5rem;
        height: 1.3rem;
        cursor: pointer;
    }
    .toggle-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.85rem 1rem;
        transition: border-color 0.2s;
    }
    .toggle-card:hover {
        border-color: #cbd5e1;
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
                        <path d="M4 4h6v6h-6z" />
                        <path d="M14 4h6v6h-6z" />
                        <path d="M4 14h6v6h-6z" />
                        <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                    </svg>
                </div>
                <div>
                    <h5 class="org-title">Tipe Barang Operasional</h5>
                    <div class="org-subtitle">Konfigurasi jenis perangkat beserta kebutuhan MAC Address & Serial Number</div>
                </div>
            </div>

            @can('tambah tipe barang operasional')
            <button id="addBtn" class="btn-add" data-bs-toggle="modal" data-bs-target="#modal-tipe">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Tambah Tipe Barang
            </button>
            @endcan
        </div>

        {{-- TOOLBAR --}}
        <div class="org-toolbar">
            <div style="font-size:.85rem; font-weight:600; color:var(--text-muted); display:flex; align-items:center; gap:.5rem;">
                Tampilkan
                <select id="sort" class="org-input" style="padding: .35rem .6rem;">
                    @foreach([10,25,50,100] as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
                data
            </div>

            @if (auth()->user()->hasPermissionTo('filter organization'))
                <div style="display:flex; align-items:center; gap:.5rem; font-size:.85rem; font-weight:600; color:var(--text-muted);">
                    Organisasi
                    <select id="filter-organization" class="org-input" style="padding: .35rem .6rem;">
                        <option value="">Semua Organisasi</option>
                        @foreach ($organizations as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="search-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="search-input" class="org-input" placeholder="Cari tipe barang…">
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table id="tipe-table" class="org-table">
                <thead>
                    <tr>
                        <th style="width:50px; text-align:center;">No</th>
                        <th>Nama Tipe</th>
                        <th style="text-align:center;">MAC Address</th>
                        <th style="text-align:center;">Serial Number</th>
                        <th style="text-align:center;">Jumlah Item</th>
                        @if (auth()->user()->hasPermissionTo('filter organization'))
                            <th style="text-align:center;">Organisasi</th>
                        @endif
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>

        {{-- FOOTER --}}
        <div class="org-footer">
            <div class="org-info" id="table-info">
                Menampilkan <span id="start-entry">0</span> sampai <span id="end-entry">0</span> dari <span id="total-entries">0</span> data
            </div>
            <ul class="pagination" id="custom-pagination"></ul>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH / EDIT TIPE --}}
<div class="modal modal-blur fade" id="modal-tipe" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="tipeForm">
                @csrf
                <input type="hidden" id="tipe_id" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Tipe Barang Operasional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required font-weight-bold">Nama Tipe Barang</label>
                        <input type="text" class="form-control" name="nama_tipe" id="nama_tipe" placeholder="Contoh: Router / ONT / Tang Crimping / Kabel Dropcore" required>
                        <div class="invalid-feedback" id="err_nama_tipe"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Aturan Deteksi Identitas Fisik</label>
                        <p class="text-muted small mb-2">Tentukan apakah barang dalam tipe ini wajib menginput MAC Address atau Serial Number.</p>
                        
                        <div class="d-flex flex-column gap-2">
                            <div class="toggle-card d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-bold text-dark">Gunakan MAC Address</div>
                                    <div class="text-muted small">Aktifkan jika perangkat memiliki MAC Address yang wajib dicatat</div>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="has_mac_address" id="has_mac_address" value="1">
                                </div>
                            </div>

                            <div class="toggle-card d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-bold text-dark">Gunakan Serial Number (SN)</div>
                                    <div class="text-muted small">Aktifkan jika perangkat memiliki nomor seri (SN) yang wajib dicatat</div>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="has_serial_number" id="has_serial_number" value="1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan / Deskripsi</label>
                        <textarea class="form-control" name="keterangan" id="keterangan" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto" id="btnSubmit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
                        Simpan
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

        const table = $('#tipe-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('tipe-barang-operasional.index') }}",
                data: function(d) {
                    d.organization_id = $('#filter-organization').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'nama_tipe', name: 'tipe_barang_operasionals.nama_tipe' },
                { data: 'mac_badge', name: 'has_mac_address', className: 'text-center' },
                { data: 'sn_badge', name: 'has_serial_number', className: 'text-center' },
                { data: 'total_barang', name: 'barang_count', orderable: false, searchable: false, className: 'text-center' },
                @if (auth()->user()->hasPermissionTo('filter organization'))
                { data: 'organization_name', name: 'organization.name', className: 'text-center' },
                @endif
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
            ],
            dom: 't',
            pageLength: 10,
            drawCallback: function(settings) {
                const info = this.api().page.info();
                $('#start-entry').text(info.recordsTotal > 0 ? info.start + 1 : 0);
                $('#end-entry').text(info.end);
                $('#total-entries').text(info.recordsTotal);
                buildPagination(info);
            }
        });

        $('#sort').on('change', function() {
            table.page.len($(this).val()).draw();
        });

        $('#search-input').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('#filter-organization').on('change', function() {
            table.draw();
        });

        function buildPagination(info) {
            const $ul = $('#custom-pagination').empty();
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
        }

        $(document).on('click', '#custom-pagination a', function(e) {
            e.preventDefault();
            const p = $(this).data('page');
            if (p !== undefined && p >= 0) table.page(p).draw('page');
        });

        // Reset modal on add
        $('#addBtn').on('click', function() {
            isEdit = false;
            $('#tipeForm')[0].reset();
            $('#tipe_id').val('');
            $('#modalTitle').text('Tambah Tipe Barang Operasional');
            $('#has_mac_address').prop('checked', false);
            $('#has_serial_number').prop('checked', false);
            $('.form-control').removeClass('is-invalid');
        });

        // Submit form
        $('#tipeForm').on('submit', function(e) {
            e.preventDefault();
            $('.form-control').removeClass('is-invalid');
            $('#btnSubmit').prop('disabled', true).text('Menyimpan...');

            const id = $('#tipe_id').val();
            const url = isEdit
                ? `{{ url('barang-operasional/tipe') }}/${id}/update`
                : `{{ route('tipe-barang-operasional.store') }}`;

            const formData = $(this).serializeArray();
            if (isEdit) {
                formData.push({ name: '_method', value: 'PUT' });
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: $.param(formData),
                success: function(res) {
                    $('#btnSubmit').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg> Simpan');
                    if (res.code === 200) {
                        $('#modal-tipe').modal('hide');
                        table.ajax.reload(null, false);
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
                    $('#btnSubmit').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg> Simpan');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan sistem.'
                    });
                }
            });
        });

        // Edit modal
        window.editModal = function(id) {
            isEdit = true;
            $('#tipeForm')[0].reset();
            $('.form-control').removeClass('is-invalid');
            $('#modalTitle').text('Edit Tipe Barang Operasional');

            $.get(`{{ url('barang-operasional/tipe') }}/${id}/show`, function(res) {
                if (res.code === 200) {
                    const data = res.data;
                    $('#tipe_id').val(data.id);
                    $('#nama_tipe').val(data.nama_tipe);
                    $('#keterangan').val(data.keterangan);
                    $('#has_mac_address').prop('checked', !!data.has_mac_address);
                    $('#has_serial_number').prop('checked', !!data.has_serial_number);
                    $('#modal-tipe').modal('show');
                }
            });
        };

        // Delete
        window.deleteTipe = function(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Tipe barang ini akan dihapus dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('barang-operasional/tipe') }}/${id}/destroy`,
                        type: 'DELETE',
                        success: function(res) {
                            if (res.code === 200) {
                                table.ajax.reload(null, false);
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
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus data.'
                            });
                        }
                    });
                }
            });
        };
    });
</script>
@endpush

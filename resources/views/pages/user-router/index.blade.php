@extends('layouts.app')

@section('title') Data Stock Router @endsection

@section('content')
<div class="org-container">
    @include('components.alert.success')

    <div class="org-card">
        {{-- HEADER --}}
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <rect x="3" y="4" width="18" height="8" rx="2" />
                        <rect x="3" y="12" width="18" height="8" rx="2" />
                        <line x1="7" y1="8" x2="7" y2="8.01" />
                        <line x1="7" y1="16" x2="7" y2="16.01" />
                    </svg>
                </div>
                <div>
                    <h5 class="org-title">Daftar Stock Router User</h5>
                    <div class="org-subtitle">Manajemen alokasi stock router milik user/cabang</div>
                </div>
            </div>

            @can('buat barang')
            <button id="addBtn" class="btn-add" data-bs-toggle="modal" data-bs-target="#modal-simple">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Alokasi Router Baru
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
                        <option value="">Semua</option>
                        @foreach ($organizations as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="search-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="search-input" class="org-input" placeholder="Cari nama user atau router…">
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table id="stock-table" class="org-table">
                <thead>
                    <tr>
                        <th style="width:50px; text-align:center;">No</th>
                        <th>Nama User</th>
                        <th>Nama Router</th>
                        <th>Jumlah Alokasi</th>
                        <th>Created At</th>
                        @if (Auth::user()->organization->type === 'internal')
                            <th style="text-align:center;">Organisasi/Mitra</th>
                        @endif
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>

        {{-- FOOTER --}}
        <div class="org-footer">
            <div class="org-info" id="table-info">
                Menampilkan <span id="start-entry">0</span>
                sampai <span id="end-entry">0</span> dari
                <span id="total-entries">0</span> data
            </div>
            <ul class="pagination" id="custom-pagination"></ul>
        </div>
    </div>
</div>
@endsection

@push('modal')
<!-- Modal Tambah / Edit Stock Router -->
<div class="modal fade" id="modal-simple" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alokasi Stock Router</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id">
                <input type="hidden" id="type">
                
                <div class="form-group mb-3">
                    <label class="form-label">Tujuan User <span class="text-danger">*</span></label>
                    <select id="user_id" class="form-control">
                        <option value="">-- Pilih User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback error_user_id" style="font-size: .8rem; font-weight: 500;"></span>
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Router <span class="text-danger">*</span></label>
                    <select id="router_id" class="form-control">
                        <option value="">-- Pilih Router --</option>
                        @foreach($router as $router)
                            <option value="{{ $router->id }}">{{ $router->code }} - {{ $router->name }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback error_router_id" style="font-size: .8rem; font-weight: 500;"></span>
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label">Jumlah Alokasi <span class="text-danger">*</span></label>
                    <input type="number" id="total" class="form-control" placeholder="Masukkan jumlah unit (pcs)">
                    <span class="invalid-feedback error_total" style="font-size: .8rem; font-weight: 500;"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" style="border-radius: 8px; font-weight: 600;" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" style="background: #6366f1; border: none; border-radius: 8px; font-weight: 600;" id="saveBtn">
                    <span class="btn-text" id="btn-text">Simpan</span>
                    <span class="spinner-border spinner-border-sm d-none" id="btnLoading"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Penambahan Stock Lanjutan -->
<div class="modal fade" id="modal-add-stock" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Stock Router (Restock)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="user_router_id">
                <div class="form-group mb-3">
                    <label class="form-label">User Tujuan</label>
                    <input type="text" id="user" class="form-control" disabled style="background:#f1f5f9; cursor:not-allowed;">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Tambahan Stock Baru <span class="text-danger">*</span></label>
                    <input type="number" id="total_stock" class="form-control" placeholder="Berapa pcs yang ingin ditambahkan?">
                    <span class="invalid-feedback error_total_stock" style="font-size: .8rem; font-weight: 500;"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" style="border-radius: 8px; font-weight: 600;" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" style="background: #10b981; border: none; border-radius: 8px; font-weight: 600;" id="storeAddStock">
                    <span class="btn-text" id="btn-text-add-stock">Tambahkan Stock</span>
                    <span class="spinner-border spinner-border-sm d-none" id="btnLoadingAddStock"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('js')
<script>
    const BASE = "{{ route('user.router.index') }}";
    let table;

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

    $(function() {
        initializeDataTable();
        initializePaginationAndSearch();
        initializeModalHandlers();
    });

    // ===========================
    // DataTable Initialization
    // ===========================
    function initializeDataTable() {
        table = $('#stock-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: BASE,
            order: [[{{ Auth::user()->organization->type === 'internal' ? 5 : 4 }}, 'desc']],
            pageLength: 10,
            dom: 'rt', // Menghilangkan default filter dan info
            language: {
                emptyTable: "Belum ada alokasi stock router ke user.",
                zeroRecords: "Pencarian tidak ditemukan."
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'user.name', className: 'fw-bold text-dark' },
                { data: 'router.name', render: data => `<div style="font-family: inherit; font-size: 0.82rem; font-weight: bold; background: #eef2ff; color:#6366f1; padding: 4px 10px; border-radius: 6px; display: inline-block;">${data}</div>` },
                { data: 'total', render: data => `<div style="font-family: monospace; font-size: 0.9rem; font-weight: bold; background: #fffbeb; color:#d97706; padding: 5px 12px; border-radius: 8px; display: inline-block;">${data} Unit</div>` },
                { data: 'created_at', render: data => moment(data).format('DD/MM/YYYY - HH:mm') },
                @if (Auth::user()->organization->type === 'internal')
                { data: 'organization_name', orderable: false, searchable: false, className: 'text-center' },
                @endif
                { data: 'action', orderable: false, searchable: false, className: 'text-end' }
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

        // Search trigger
        $("#search-input").on('keyup', function() {
            table.search(this.value).draw();
        });

        // Filter organisasi (internal only)
        $("#filter-organization").on('change', function() {
            table.ajax.url(BASE + '?organization_id=' + this.value).load();
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
                <a class="page-link" href="#" data-page="${info.page - 1}">&laquo;</a>
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
                <a class="page-link" href="#" data-page="${info.page + 1}">&raquo;</a>
            </li>
        `);

        // Event handler for pagination links
        pagination.find('a').on('click', function(e) {
            e.preventDefault();
            const page = parseInt($(this).data('page'));
            if (!isNaN(page) && page >= 0 && page < info.pages) {
                table.page(page).draw('page');
            }
        });
    }

    // ===========================
    // Modal Handlers
    // ===========================
    function initializeModalHandlers() {
        // Add button - open modal for create
        $("#addBtn").on('click', function() {
            resetModal();
            $(".modal-title").text("Alokasi Stock Router");
            $("#type").val('create');
        });

        // Save button - handle create/update
        $("#saveBtn").on('click', function() {
            handleSaveStock();
        });

        // Add stock button
        $("#storeAddStock").on('click', function() {
            handleAddStock();
        });
    }

    function resetModal() {
        $("#user_id").val('');
        $("#router_id").val('');
        $("#total").val('');
        $("#id").val('');
        clearValidationErrors();
    }

    function clearValidationErrors() {
        $(".form-control").removeClass('is-invalid');
        $(".invalid-feedback").text('');
    }

    // ===========================
    // CRUD Operations
    // ===========================
    
    // Create / Update Stock
    function handleSaveStock() {
        const type = $("#type").val();
        const id = $("#id").val();
        const data = {
            user_id: $("#user_id").val(),
            router_id: $("#router_id").val(),
            total: $("#total").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        const url = type === 'create' ? BASE + '/store' : BASE + '/' + id + '/update';
        const method = type === 'create' ? 'POST' : 'PUT';

        // Show loading
        $("#saveBtn").prop('disabled', true);
        $("#btnLoading").removeClass('d-none');
        $("#btn-text").addClass('d-none');
        
        clearValidationErrors();

        $.ajax({
            url: url,
            method: method,
            data: data
        })
        .done(function(response) {
            if (response.errors) {
                showValidationErrors(response.errors);
            } else {
                $("#modal-simple").modal('hide');
                showSuccessMessage("Alokasi router berhasil disimpan.");
                table.ajax.reload();
            }
        })
        .fail(function() {
            showErrorMessage("Terjadi kesalahan sistem.");
        })
        .always(function() {
            $("#saveBtn").prop('disabled', false);
            $("#btnLoading").addClass('d-none');
            $("#btn-text").removeClass('d-none');
        });
    }

    // Edit Stock - Open modal with data
    function editModal(id) {
        $.get(BASE + '/' + id + '/show')
            .done(function(response) {
                const data = response.data;
                
                resetModal();
                $(".modal-title").text("Edit Stock Router");
                $("#modal-simple").modal('show');
                $("#user_id").val(data.user_id);
                $("#router_id").val(data.router_id);
                $("#total").val(data.total);
                $("#id").val(data.id);
                $("#type").val('update');
            })
            .fail(function() {
                showErrorMessage("Data tidak ditemukan.");
            });
    }

    // Add Stock - Open modal
    function addStock(id) {
        $.get(BASE + '/' + id + '/show')
            .done(function(response) {
                const data = response.data;
                
                $("#modal-add-stock").modal('show');
                $("#user_router_id").val(data.id);
                $("#user").val(data.user.name);
                $("#total_stock").val('');
                clearValidationErrors();
            })
            .fail(function() {
                showErrorMessage("Data tidak ditemukan.");
            });
    }

    // Handle Add Stock
    function handleAddStock() {
        const btn = $("#storeAddStock");
        
        if (btn.prop('disabled')) return;

        // Show loading
        btn.prop('disabled', true);
        $("#btn-text-add-stock").addClass('d-none');
        $("#btnLoadingAddStock").removeClass('d-none');

        clearValidationErrors();

        const data = {
            user_router_id: $("#user_router_id").val(),
            total_stock: $("#total_stock").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.post("{{ route('user.router.addStore') }}", data)
            .done(function(response) {
                if (response.errors) {
                    showValidationErrors(response.errors);
                    resetAddStockButton();
                } else {
                    $("#modal-add-stock").modal('hide');
                    showSuccessMessage("Penambahan stock berhasil.");
                    resetAddStockButton();
                    table.ajax.reload();
                }
            })
            .fail(function() {
                showErrorMessage("Terjadi kesalahan sistem.");
                resetAddStockButton();
            });

        function resetAddStockButton() {
            btn.prop('disabled', false);
            $("#btn-text-add-stock").removeClass('d-none');
            $("#btnLoadingAddStock").addClass('d-none');
        }
    }

    function deleteStock(id) {
        Swal.fire({
            title: "Tarik / Hapus Alokasi?",
            text: "Apakah anda yakin ingin menghapus data alokasi router user ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ef4444",
            cancelButtonColor: "#c0c0d0",
            confirmButtonText: "Ya, Hapus!",
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
                    showSuccessMessage("Berhasil menghapus alokasi.");
                    table.ajax.reload();
                })
                .fail(function() {
                    showErrorMessage("Server Kesalahan atau data tidak bisa dihapus.");
                });
            }
        });
    }

    function showValidationErrors(errors) {
        clearValidationErrors();
        
        Object.keys(errors).forEach(function(field) {
            $("#" + field).addClass('is-invalid');
            $(".error_" + field).text(errors[field]);
        });

        setTimeout(function() {
            clearValidationErrors();
        }, 3000);
    }

    function showSuccessMessage(message) {
        Toast.fire({
            icon: "success",
            title: message
        });
    }

    function showErrorMessage(message) {
        Toast.fire({
            icon: "error",
            title: message
        });
    }
</script>
@endpush
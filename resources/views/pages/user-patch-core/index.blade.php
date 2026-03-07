@extends('layouts.app')

@section('title')
    Data Stock Patch Core
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/modern-layout.css') }}">
@endpush

@section('content')
    <div class="org-container mt-4">
        <div class="org-card">
            <div class="org-header">
                <div class="org-title-wrap">
                    <div class="org-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-package">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                            <path d="M12 12l8 -4.5" />
                            <path d="M12 12l0 9" />
                            <path d="M12 12l-8 -4.5" />
                            <path d="M16 5.25l-8 4.5" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="org-title">Data Stock Patch Core</h2>
                        <p class="org-subtitle mb-0">Kelola distribusi dan alokasi stock Patch Core ke user.</p>
                    </div>
                </div>

                @can('buat barang')
                    <div class="org-header-action">
                        <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple"
                            class="btn-add">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="14.5" y2="12"></line>
                            </svg>
                            Alokasi Stock
                        </a>
                    </div>
                @endcan
            </div>

            <div class="org-toolbar">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted" style="font-size:.88rem">Tampilkan</span>
                    <select name="sort" id="sort" class="org-input" style="width: 80px; padding: 0.35rem 0.8rem;">
                        @php $opts = [10, 25, 50, 100]; @endphp
                        @foreach ($opts as $opt)
                            <option value="{{ $opt }}">{{ $opt }}</option>
                        @endforeach
                    </select>
                    <span class="text-muted" style="font-size:.88rem">entri</span>
                </div>

                <div class="search-wrapper ms-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" class="org-input" id="search-input"
                        placeholder="Cari data user atau patch core..." autocomplete="off">
                </div>
            </div>

            <div class="table-responsive">
                <table id="patch-core-table" class="org-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">NO</th>
                            <th>NAMA USER</th>
                            <th>NAMA PATCH CORE</th>
                            <th class="text-center">JUMLAH</th>
                            <th>TANGGAL DISTRIBUSI</th>
                            <th class="text-center">ACTION</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="org-footer flex-column flex-sm-row">
                <div class="org-info mb-3 mb-sm-0 text-center text-sm-start">
                    Menampilkan <span id="start-entry">0</span> - <span id="end-entry">0</span> dari <span
                        id="total-entries">0</span> data
                </div>
                <ul class="pagination mb-0" id="custom-pagination"></ul>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal Alokasi Stock Patch Core -->
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alokasi Stock Patch Core</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">

                    <div class="mb-3" id="role_id_show">
                        <label class="form-label">Pilih Level Akses</label>
                        <select name="role" id="role" class="form-control">
                            <option value="">-- Pilih Level --</option>
                            @foreach ($role as $rl)
                                <option value="{{ $rl->name }}">{{ strtoupper($rl->name) }}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback error_role"></span>
                    </div>

                    <div class="mb-3" id="user_id_show" style="display: none;">
                        <label class="form-label">Pilih Nama User</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">-- Pilih User --</option>
                        </select>
                        <span class="invalid-feedback error_user_id"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Item Patch Core</label>
                        <select name="patch_core_id" id="patch_core_id" class="form-control">
                            <option value="">-- Pilih Patch Core --</option>
                            @foreach ($patchCore as $pc)
                                <option value="{{ $pc->id }}">{{ $pc->name }}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback error_patch_core_id"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Unit (Alokasi)</label>
                        <div class="input-group">
                            <input type="number" name="total" id="total" class="form-control" min="1"
                                placeholder="0">
                            <span class="input-group-text">PCS</span>
                        </div>
                        <span class="invalid-feedback error_total"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary d-flex align-items-center gap-2">
                        <span class="btn-text">Simpan Alokasi</span>
                        <div class="btn-loading spinner-border spinner-border-sm d-none" role="status"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Stock (Restock) -->
    <div class="modal modal-blur fade" id="modal-add-stock" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">📈 Tambah Stock (Restock)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_patch_core_id" id="user_patch_core_id">

                    <div class="mb-3">
                        <label class="form-label">Nama Penerima</label>
                        <input type="text" name="user" id="user" class="form-control bg-light" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Tambahan Stock</label>
                        <div class="input-group">
                            <input type="number" name="total_stock" id="total_stock" class="form-control"
                                min="1" placeholder="Masukkan jumlah unit">
                            <span class="input-group-text">PCS</span>
                        </div>
                        <span class="invalid-feedback error_total_stock"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeAddStock" class="btn btn-primary d-flex align-items-center gap-2">
                        <span class="btn-text">Tambahkan Stock</span>
                        <div class="btn-loading spinner-border spinner-border-sm d-none" role="status"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        const BASE = "{{ route('user.patch.core.index') }}";
        let table;

        $(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeModalHandlers();
            initializeRoleHandler();
        });

        // ===========================
        // DataTable Initialization
        // ===========================
        function initializeDataTable() {
            table = $('#patch-core-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: BASE,
                order: [
                    [4, 'desc']
                ],
                pageLength: 10,
                dom: 'rt',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_name',
                        name: 'users.name',
                        orderable: true,
                        searchable: true,
                        render: function(data) {
                            return `<div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-xs rounded bg-primary-lt text-primary fw-bold">${data.charAt(0)}</div>
                            <span class="fw-bold">${data}</span>
                        </div>`;
                        }
                    },
                    {
                        data: 'patch_core_name',
                        name: 'patch_core.name',
                        orderable: true,
                        searchable: true,
                        render: function(data) {
                            return `<span class="badge-tipe">${data}</span>`;
                        }
                    },
                    {
                        data: 'total',
                        name: 'user_patch_core.total',
                        className: 'text-center',
                        render: function(data) {
                            return `<code class="fw-bold text-orange" style="font-size: 0.95rem;">${data} Unit</code>`;
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'user_patch_core.created_at',
                        render: function(data) {
                            return `<div class="text-muted small fw-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 0 0 1 -2 2h-12a2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                            ${moment(data).format('DD MMM YYYY')}
                        </div>`;
                        }
                    },
                    {
                        data: 'action',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
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

            // Live search
            $("#search-input").on('input', function() {
                table.search(this.value).draw();
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </a>
                </li>
            `);

            let startPage = Math.max(0, info.page - 2);
            let endPage = Math.min(info.pages - 1, info.page + 2);

            if (startPage > 0) {
                pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
                if (startPage > 1) pagination.append(
                    `<li class="page-item disabled"><span class="page-link">…</span></li>`);
            }

            for (let i = startPage; i <= endPage; i++) {
                pagination.append(
                    `<li class="page-item ${i === info.page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i + 1}</a></li>`
                );
            }

            if (endPage < info.pages - 1) {
                if (endPage < info.pages - 2) pagination.append(
                    `<li class="page-item disabled"><span class="page-link">…</span></li>`);
                pagination.append(
                    `<li class="page-item"><a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a></li>`
                );
            }

            // Next button
            pagination.append(`
                <li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${info.page + 1}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
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
        // Modal Handlers
        // ===========================
        function initializeModalHandlers() {
            // Add button - open modal for create
            $("#addBtn").on('click', function() {
                resetModal();
                $(".modal-title").text("📦 Alokasi Stock Patch Core");
                $("#type").val('create');
                $("#role_id_show").show();
                $("#user_id_show").hide();
            });

            // Save button - handle create/update
            $("#storeBtn").on('click', function() {
                handleSaveStock();
            });

            // Add stock button
            $("#storeAddStock").on('click', function() {
                handleAddStock();
            });
        }

        function resetModal() {
            $("#role").val('');
            $("#user_id").val('').html('<option value="">-- Pilih User --</option>');
            $("#patch_core_id").val('');
            $("#total").val('');
            $("#id").val('');
            clearValidationErrors();
        }

        function clearValidationErrors() {
            $(".form-control").removeClass('is-invalid');
            $(".invalid-feedback").text('');
        }

        // ===========================
        // Role Handler
        // ===========================
        function initializeRoleHandler() {
            $("#role").on('change', function() {
                const role = $(this).val();

                if (!role) {
                    $("#user_id_show").hide();
                    return;
                }

                $.ajax({
                        url: BASE + '/get-role',
                        method: "POST",
                        data: {
                            role: role,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                    .done(function(response) {
                        let html = '<option value="">-- Pilih User --</option>';

                        if (response.code === 200 && response.data) {
                            $("#user_id_show").show();

                            $.each(response.data, function(index, value) {
                                html += `<option value="${value.id}">${value.name}</option>`;
                            });
                        } else {
                            $("#user_id_show").hide();
                        }

                        $("#user_id").html(html);
                    })
                    .fail(function() {
                        showErrorMessage('Terjadi kesalahan saat mengambil data user');
                    });
            });
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
                patch_core_id: $("#patch_core_id").val(),
                total: $("#total").val(),
                role: $("#role").val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            const url = type === 'create' ? BASE + '/store' : BASE + '/' + id + '/update';
            const method = type === 'create' ? 'POST' : 'PUT';

            // Show loading
            const btn = $("#storeBtn");
            btn.prop('disabled', true);
            btn.find(".btn-text").text("Menyimpan...");
            btn.find(".btn-loading").removeClass('d-none');

            $.ajax({
                    url: url,
                    method: method,
                    data: data
                })
                .done(function(response) {
                    if (response.errors) {
                        showValidationErrors(response.errors);
                        resetButton(btn, "Simpan Alokasi");
                    } else {
                        $("#modal-simple").modal('hide');
                        showSuccessMessage(response.message);
                        resetButton(btn, "Simpan Alokasi");
                        table.ajax.reload();
                    }
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan");
                    resetButton(btn, "Simpan Alokasi");
                });
        }

        // Edit Stock - Open modal with data
        function editModal(id) {
            $.get(BASE + '/' + id + '/show')
                .done(function(response) {
                    if (response.code === 200) {
                        const data = response.data;

                        $(".modal-title").text("📦 Edit Alokasi Patch Core");
                        $("#modal-simple").modal('show');

                        $("#id").val(data.id);
                        $("#patch_core_id").val(data.patch_core_id);
                        $("#total").val(data.total);
                        $("#type").val('update');

                        // Trigger role change to load users
                        if (data.user && data.user.roles && data.user.roles[0]) {
                            $("#role").val(data.user.roles[0].name).trigger('change');

                            // Set user_id after a short delay to ensure users are loaded
                            setTimeout(function() {
                                $("#user_id").val(data.user_id);
                            }, 500);
                        }
                    } else {
                        showErrorMessage(response.message || 'Terjadi kesalahan');
                    }
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan saat mengambil data");
                });
        }

        // Add Stock - Open modal
        function addStock(id) {
            $.get(BASE + '/' + id + '/show')
                .done(function(response) {
                    if (response.code === 200) {
                        const data = response.data;

                        $("#modal-add-stock").modal('show');
                        $("#user_patch_core_id").val(data.id);
                        $("#user").val(data.user.name);
                        $("#total_stock").val('');
                        clearValidationErrors();
                    } else {
                        showErrorMessage(response.message || 'Terjadi kesalahan');
                    }
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan saat mengambil data");
                });
        }

        // Handle Add Stock
        function handleAddStock() {
            const btn = $("#storeAddStock");

            if (btn.prop('disabled')) return;

            // Show loading
            btn.prop('disabled', true);
            btn.find(".btn-text").text("Menyimpan...");
            btn.find(".btn-loading").removeClass('d-none');

            const data = {
                user_patch_core_id: $("#user_patch_core_id").val(),
                total_stock: $("#total_stock").val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.post("{{ route('user.patch.core.addStore') }}", data)
                .done(function(response) {
                    if (response.errors) {
                        showValidationErrors(response.errors);
                        resetButton(btn, "Tambahkan Stock");
                    } else {
                        $("#modal-add-stock").modal('hide');
                        showSuccessMessage(response.message);
                        resetButton(btn, "Tambahkan Stock");
                        table.ajax.reload();
                    }
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan");
                    resetButton(btn, "Tambahkan Stock");
                });
        }

        // Delete Stock
        function deleteStock(id) {
            Swal.fire({
                title: "Hapus Data?",
                text: "Data alokasi stock ini akan dihapus permanen dari sistem.",
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
                            showErrorMessage("Terjadi kesalahan pada server");
                        });
                }
            });
        }

        // ===========================
        // Helper Functions
        // ===========================
        function showValidationErrors(errors) {
            clearValidationErrors();

            Object.keys(errors).forEach(function(field) {
                $("#" + field).addClass('is-invalid');
                $(".error_" + field).text(errors[field]);
            });

            // Auto clear errors after 3 seconds
            setTimeout(function() {
                clearValidationErrors();
            }, 3000);
        }

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

        function resetButton(btn, text) {
            btn.prop('disabled', false);
            btn.find(".btn-text").text(text);
            btn.find(".btn-loading").addClass('d-none');
        }
    </script>
@endpush

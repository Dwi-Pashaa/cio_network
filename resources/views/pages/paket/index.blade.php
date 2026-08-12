@extends('layouts.app')

@section('title')
    Data Tipe Paket
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="org-container mt-4">
        <div class="org-card">
            <div class="org-header">
                <div class="org-title-wrap">
                    <div class="org-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-box">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                            <path d="M12 12l8 -4.5" />
                            <path d="M12 12l0 9" />
                            <path d="M12 12l-8 -4.5" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="org-title">Data Tipe Paket</h2>
                        <p class="org-subtitle mb-0">Kelola dan atur tipe paket beserta peruntukannya.</p>
                    </div>
                </div>

                <div class="org-header-action">
                    <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple"
                        class="btn-add">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="14.5" y2="12"></line>
                        </svg>
                        Tambah Data
                    </a>
                </div>
            </div>

            <div class="org-toolbar">
                <div class="d-flex align-items-center gap-2">
                    <select name="sort" id="sort" class="org-input" style="width: 80px;">
                        @php $opts = [10, 25, 50, 100]; @endphp
                        @foreach ($opts as $opt)
                            <option value="{{ $opt }}">{{ $opt }}</option>
                        @endforeach
                    </select>
                    <span class="text-muted small fw-bold d-none d-sm-inline">ENTRIES</span>
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

                <div class="search-wrapper w-100 w-sm-auto mt-3 mt-sm-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" class="org-input w-100" id="search-input" placeholder="Cari tipe paket...">
                </div>
            </div>

            <div class="table-responsive">
                <table id="paket-table" class="org-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">NO</th>
                            <th>TIPE PAKET</th>
                            <th>USER</th>
                            <th>TANGGAL DIBUAT</th>
                            @if (auth()->user()->hasPermissionTo('filter organization'))
                                <th class="text-center">Organisasi/Mitra</th>
                            @endif
                            <th class="text-center" style="width: 100px;">ACTION</th>
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
    <div class="modal modal-blur fade" id="modal-users" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">👥 Daftar User Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3" id="modal-users-paket-name"></p>
                    <div id="modal-users-list" class="d-flex flex-column gap-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">📦 Tambah Tipe Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">

                    <div class="mb-3">
                        <label class="form-label" for="user_id">Pilih User</label>
                        <select name="user_id[]" id="user_id" class="form-select" multiple>
                            <option value="">Pilih</option>
                            @foreach ($user as $usr)
                                <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback error_user_id"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="name">Tipe Paket</label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Masukkan tipe paket">
                        <span class="invalid-feedback error_name"></span>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_public" value="0">
                            <input type="checkbox" name="is_public" id="is_public" class="form-check-input" value="1" checked>
                            <label class="form-check-label" for="is_public">Tampilkan di Publik</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary d-flex align-items-center gap-2">
                        <span class="btn-text">Simpan Data</span>
                        <div class="btn-loading spinner-border spinner-border-sm d-none" role="status"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const BASE = "{{ route('paket.index') }}";
        let table;

        $(function() {
            initializeSelect2();
            initializeDataTable();
            initializePaginationAndSearch();
            initializeModalHandlers();
        });

        // ===========================
        // Select2 Initialization
        // ===========================
        function initializeSelect2() {
            $('#user_id').select2({
                width: '100%',
                dropdownParent: $('#modal-simple'),
                placeholder: 'Pilih User',
                allowClear: true
            });
        }

        // ===========================
        // DataTable Initialization
        // ===========================
        function initializeDataTable() {
            table = $('#paket-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: BASE,
                order: [[{{ auth()->user()->hasPermissionTo('filter organization') ? 4 : 3 }}, 'desc']],
                pageLength: 10,
                dom: 'rt',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        orderable: true,
                        searchable: true,
                        render: function(data) {
                            return `<span class="badge-tipe align-middle fw-bold">${data}</span>`;
                        }
                    },
                    {
                        data: 'user',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            if (!data || data.length === 0) {
                                return `<span class="text-muted fst-italic">-</span>`;
                            }
                            let count = data.length;
                            return `<button onclick="showUsers(${row.id})" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                ${count} User
                            </button>`;
                        }
                    },
                    {
                        data: 'created_at',
                        render: function(data) {
                            return `<div class="text-muted small fw-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 0 0 1 -2 2h-12a2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                            ${moment(data).format('DD MMM YYYY')}
                        </div>`;
                        }
                    },
                    @if (auth()->user()->hasPermissionTo('filter organization'))
                    {
                        data: 'organization_name',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    @endif
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
                    table.search(this.value).draw();
                }
            });

            // Search on button click
            $("#search-btn").on('click', function() {
                table.search($("#search-input").val()).draw();
            });

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
                <a class="page-link" href="#" data-page="${info.page - 1}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                    prev
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
                    next
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
        // Modal Handlers
        // ===========================
        function initializeModalHandlers() {
            // Add button - open modal for create
            $("#addBtn").on('click', function() {
                resetModal();
                $(".modal-title").text("📦 Tambah Tipe Paket");
                $("#type").val('create');
            });

            // Save button - handle create/update
            $("#storeBtn").on('click', function() {
                handleSave();
            });
        }

        function resetModal() {
            $("#name").val('');
            $("#is_public").prop('checked', true);
            $("#id").val('');
            $("#user_id").val(null).trigger('change');
            clearValidationErrors();
        }

        function clearValidationErrors() {
            $(".form-control").removeClass('is-invalid');
            $(".invalid-feedback").text('');
        }

        // ===========================
        // CRUD Operations
        // ===========================

        // Create / Update
        function handleSave() {
            const type = $("#type").val();
            const id = $("#id").val();

            const url = type === 'create' ? BASE + '/store' : BASE + '/' + id + '/update';
            const method = 'POST';

            // Prepare FormData
            let formData = new FormData();
            formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
            formData.append("_method", type === 'create' ? "POST" : "PUT");
            formData.append("name", $("#name").val());
            formData.append("is_public", $("#is_public").is(':checked') ? 1 : 0);

            // Append multiple user_id
            let users = $("#user_id").val() || [];
            users.forEach(userId => formData.append("user_id[]", userId));

            // Show loading
            const btn = $("#storeBtn");
            btn.prop('disabled', true);
            btn.find(".btn-text").text("Menyimpan...");
            btn.find(".btn-loading").removeClass('d-none');

            $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    processData: false,
                    contentType: false
                })
                .done(function(response) {
                    if (response.errors) {
                        showValidationErrors(response.errors);
                        resetButton(btn, "Simpan Data");
                    } else {
                        $("#modal-simple").modal('hide');
                        showSuccessMessage(response.message);
                        table.ajax.reload();
                        resetButton(btn, "Simpan Data");
                    }
                })
                .fail(function(jqXHR) {
                    console.error("Error:", jqXHR.responseText);
                    showErrorMessage("Terjadi kesalahan");
                    resetButton(btn, "Simpan Data");
                });
        }

        // Edit - Open modal with data
        function editModal(id) {
            $.get(BASE + '/' + id + '/show')
                .done(function(response) {
                    const data = response.data;

                    $(".modal-title").text("📦 Edit Tipe Paket");
                    $("#modal-simple").modal('show');

                    $("#id").val(data.id);
                    $("#name").val(data.name);
                    $("#is_public").prop('checked', data.is_public == 1 || data.is_public === true);
                    $("#type").val('update');

                    // Set selected users
                    if (data.user && data.user.length > 0) {
                        let selectedUsers = data.user.map(u => u.id);
                        $("#user_id").val(selectedUsers).trigger('change');
                    } else {
                        $("#user_id").val(null).trigger('change');
                    }
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan saat mengambil data");
                });
        }

        // Delete
        function deletePaket(id) {
            Swal.fire({
                title: "Hapus Data?",
                text: "Tipe paket ini akan dihapus permanen.",
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

        // ===========================
        // User List Modal
        // ===========================
        function showUsers(id) {
            $.get(BASE + '/' + id + '/show')
                .done(function(response) {
                    const data = response.data;
                    const users = data.user || [];

                    $('#modal-users-paket-name').text('Paket: ' + data.name);
                    const list = $('#modal-users-list');
                    list.empty();

                    if (users.length === 0) {
                        list.html('<span class="text-muted fst-italic">Tidak ada user</span>');
                    } else {
                        users.forEach(function(usr) {
                            let initial = usr.name.charAt(0).toUpperCase();
                            list.append(`
                                <div class="d-flex align-items-center gap-3 p-2 rounded border">
                                    <span class="avatar-initial rounded bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 14px; font-weight: 600;">${initial}</span>
                                    <div>
                                        <div class="fw-semibold">${usr.name}</div>
                                        <div class="text-muted small">${usr.email || ''}</div>
                                    </div>
                                </div>
                            `);
                        });
                    }

                    $('#modal-users').modal('show');
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan saat mengambil data");
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

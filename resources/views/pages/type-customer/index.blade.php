@extends('layouts.app')

@section('title')
    Data Tipe Pelanggan
@endsection

@section('content')
    <div class="org-container mt-4">
        <div class="org-card">
            <div class="org-header">
                <div class="org-title-wrap">
                    <div class="org-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-tags">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M3 8v4.172a2 2 0 0 0 .586 1.414l5.71 5.71a2.41 2.41 0 0 0 3.408 0l3.592 -3.592a2.41 2.41 0 0 0 0 -3.408l-5.71 -5.71a2 2 0 0 0 -1.414 -.586h-4.172a2 2 0 0 0 -2 2z" />
                            <path d="M18 9l.01 0" />
                            <path d="M10.5 15.5l6.98 6.56a2.38 2.38 0 0 0 3.27 0a2.03 2.03 0 0 0 0 -2.98l-6.75 -6.33" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="org-title">Data Tipe Pelanggan</h2>
                        <p class="org-subtitle mb-0">Kelola dan atur tipe layanan untuk pelanggan/customer.</p>
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
                    <input type="text" class="org-input w-100" id="search-input" placeholder="Cari tipe pelanggan...">
                </div>
            </div>

            <div class="table-responsive">
                <table id="type-table" class="org-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">NO</th>
                            <th>TIPE PELANGGAN</th>
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
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">📦 Tambah Tipe Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">

                    <div class="mb-3">
                        <label class="form-label" for="name">Tipe Pelanggan</label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Masukkan tipe pelanggan">
                        <span class="invalid-feedback error_name"></span>
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
    <script>
        const BASE = "{{ route('type.customer.index') }}";
        let table;

        $(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeModalHandlers();
        });

        function initializeDataTable() {
            table = $('#type-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE,
                    data: function(d) {
                        d.search_custom = $('#search-input').val();
                        d.organization_id = $('#filter-organization').val();
                    }
                },
                order: [[{{ auth()->user()->hasPermissionTo('filter organization') ? 3 : 2 }}, 'desc']],
                pageLength: 10,
                dom: 'rt',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        render: function(data) {
                            return `<span class="badge-tipe align-middle fw-bold">${data}</span>`;
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

        function initializePaginationAndSearch() {
            $("#sort").on('change', function() {
                table.page.len($(this).val()).draw();
            });

            // Search on Enter key
            $("#search-input").on('keypress', function(e) {
                if (e.which === 13) {
                    table.ajax.reload();
                }
            });

            // Search real-time dengan debounce
            let searchTimer;
            $("#search-input").on('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    table.ajax.reload();
                }, 400);
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

            for (let i = startPage; i <= endPage; i++) {
                pagination.append(`
                <li class="page-item ${i === info.page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i + 1}</a>
                </li>
            `);
            }

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

        function initializeModalHandlers() {
            $("#addBtn").on('click', function() {
                resetModal();
                $(".modal-title").text("📦 Tambah Tipe Pelanggan");
                $("#type").val('create');
            });

            $("#storeBtn").on('click', function() {
                handleSave();
            });
        }

        function resetModal() {
            $("#name").val('');
            $("#id").val('');
            clearValidationErrors();
        }

        function clearValidationErrors() {
            $(".form-control").removeClass('is-invalid');
            $(".invalid-feedback").text('');
        }

        function handleSave() {
            const type = $("#type").val();
            const id = $("#id").val();
            const data = {
                name: $("#name").val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            const url = type === 'create' ? BASE + '/store' : BASE + '/' + id + '/update';
            const method = type === 'create' ? 'POST' : 'PUT';

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
                        resetButton(btn, "Simpan Data");
                    } else {
                        $("#modal-simple").modal('hide');
                        showSuccessMessage(response.message);
                        table.ajax.reload();
                        resetButton(btn, "Simpan Data");
                    }
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan");
                    resetButton(btn, "Simpan Data");
                });
        }

        function editModal(id) {
            $.get(BASE + '/' + id + '/show')
                .done(function(response) {
                    const data = response.data;

                    $(".modal-title").text("📦 Edit Tipe Pelanggan");
                    $("#modal-simple").modal('show');

                    $("#id").val(data.id);
                    $("#name").val(data.name);
                    $("#type").val('update');
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan saat mengambil data");
                });
        }

        function deleteType(id) {
            Swal.fire({
                title: "Hapus Data?",
                text: "Tipe pelanggan ini akan dihapus permanen.",
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

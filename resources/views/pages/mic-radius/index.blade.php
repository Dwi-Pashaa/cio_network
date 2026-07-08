@extends('layouts.app')

@section('title', 'Data Mic Radius')

@push('css')
    <link href="{{ asset('css/modern-layout.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        .select2-container--bootstrap-5 .select2-selection {
            border-color: #e2e8f0;
            border-radius: 10px;
            padding: 0.25rem 0.5rem;
            min-height: 42px;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="org-container mt-4">
        <div class="org-card">

            {{-- Header --}}
            <div class="org-header">
                <div class="org-title-wrap">
                    <div class="org-header-icon" style="background:linear-gradient(135deg,#cffafe,#a5f3fc);color:#0891b2;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="12" cy="12" r="9" />
                            <circle cx="12" cy="12" r="1" />
                            <path d="M12 4v2" />
                            <path d="M12 18v2" />
                            <path d="M4 12h2" />
                            <path d="M18 12h2" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="org-title">Data Mic Radius</h2>
                        <p class="org-subtitle mb-0">Kelola master data perangkat MikroTik Radius Anda.</p>
                    </div>
                </div>

                @can('buat mic radius')
                    <div class="org-header-action">
                        <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple"
                            class="btn-add">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Tambah Radius
                        </a>
                    </div>
                @endcan
            </div>

            {{-- Toolbar --}}
            <div class="org-toolbar">
                <div class="d-flex align-items-center gap-2">
                    <select name="sort" id="sort" class="org-input" style="width:80px;">
                        @foreach ([10, 25, 50, 100] as $opt)
                            <option value="{{ $opt }}" {{ request('sort') == $opt ? 'selected' : '' }}>
                                {{ $opt }}</option>
                        @endforeach
                    </select>
                    <span class="text-muted small fw-bold d-none d-sm-inline">ENTRIES</span>
                </div>
                <div class="search-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="text" class="org-input w-100" id="search-input" placeholder="Cari Radius...">
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="org-table" id="micradius-table">
                    <thead>
                        <tr>
                            <th style="width:56px;">NO</th>
                            <th>KODE</th>
                            <th>NAMA RADIUS</th>
                            <th>KAMPUNG</th>
                            <th>USER</th>

                            <th>CREATED</th>
                            @if (auth()->user()->can('ubah mic radius') || auth()->user()->can('hapus mic radius'))
                                <th class="text-center">ACTION</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            {{-- Footer --}}
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
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="border-radius:18px;overflow:hidden;border:none;">

                {{-- Modal Header --}}
                <div class="modal-header"
                    style="background:linear-gradient(135deg,#1e1b4b,#4c1d95);border:none;padding:1.25rem 1.5rem;">
                    <div class="d-flex align-items-center gap-2">
                        <div
                            style="width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <circle cx="12" cy="12" r="9" />
                                <circle cx="12" cy="12" r="1" />
                                <path d="M12 4v2" />
                                <path d="M12 18v2" />
                                <path d="M4 12h2" />
                                <path d="M18 12h2" />
                            </svg>
                        </div>
                        <h5 class="modal-title mb-0" style="color:white;font-weight:700;font-size:.95rem;">Tambah Mic
                            Radius</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body" style="padding:1.5rem;">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">


                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label mb-2" for="user_id">Pilih User <span
                                    class="text-danger">*</span></label>
                            <select name="user_id[]" id="user_id" class="form-select" multiple>
                                @foreach ($user as $usr)
                                    <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_user_id"></span>
                            <div class="text-muted small mt-1"><i class="ti ti-info-circle"></i> Anda bisa memilih lebih
                                dari satu user.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="code">Kode Radius <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" class="form-control"
                                placeholder="Contoh: MR-01">
                            <span class="invalid-feedback error_code"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="name">Nama Radius <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="Nama Perangkat">
                            <span class="invalid-feedback error_name"></span>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="hometowns_id">Kampung <span
                                    class="text-danger">*</span></label>
                            <select name="hometowns_id" id="hometowns_id" class="form-select">
                                <option value="">-- Pilih Kampung --</option>
                                @foreach ($hometown as $hmt)
                                    <option value="{{ $hmt->id }}">{{ $hmt->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_hometowns_id"></span>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="mix_password">Password Mix Radius</label>
                            <div class="input-group">
                                <input type="password" name="mix_password" id="mix_password" class="form-control" placeholder="Password login Mix Radius" autocomplete="off">
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword" onclick="togglePasswordVisibility()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="eyeIcon">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="invalid-feedback error_mix_password"></span>
                        </div>
                    </div>


                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:1rem 1.5rem;gap:.75rem;">
                    <button type="button" class="btn btn-link link-secondary px-4"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary px-4">
                        <span id="btnText">Simpan</span>
                        <span id="btnLoading" class="spinner-border spinner-border-sm d-none ms-1" role="status"></span>
                    </button>
                </div>

            </div>
        </div>
    </div>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const BASE = "{{ route('mic.radius.index') }}";
        let table;
        let select2User;

        $(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeModalHandlers();
            initializeSelect2();
        });

        function initializeDataTable() {
            table = $('#micradius-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE,
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
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
                        data: 'code',
                        render: function(data) {
                            return `<span class="fw-bold" style="color:var(--brand);">${data || '-'}</span>`;
                        }
                    },
                    {
                        data: 'name',
                        defaultContent: '<span class="text-muted">-</span>'
                    },
                    {
                        data: 'hometown',
                        render: function(data) {
                            return data ? data.name : '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'user',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            if (data && data.length > 0) {
                                return `<div class="d-flex flex-wrap gap-1">` +
                                    data.map(u =>
                                        `<span class="badge" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;">${u.name}</span>`
                                        ).join('') +
                                    `</div>`;
                            }
                            return '<span class="text-muted">-</span>';
                        }
                    },

                    {
                        data: 'created_at',
                        render: function(data) {
                            if (!data) return '-';
                            const d = moment(data);
                            return `<span style="color:#64748b;font-size:.82rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                style="margin-right:3px;vertical-align:middle;">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            ${d.format('DD MMM YYYY')}
                            <span style="color:#94a3b8;margin-left:4px;">${d.format('HH:mm')}</span>
                        </span>`;
                        }
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        visible: {{ auth()->user()->can('ubah mic radius') || auth()->user()->can('hapus mic radius') ? 'true' : 'false' }}
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

            const prevDisabled = info.page === 0 ? 'disabled' : '';
            pagination.append(`<li class="page-item ${prevDisabled}">
            <a class="page-link" href="#" data-page="${info.page - 1}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </a></li>`);

            let startPage = Math.max(0, info.page - 2);
            let endPage = Math.min(info.pages - 1, info.page + 2);
            if (startPage > 0) {
                pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
                if (startPage > 1) pagination.append(
                `<li class="page-item disabled"><span class="page-link">…</span></li>`);
            }
            for (let i = startPage; i <= endPage; i++) {
                pagination.append(`<li class="page-item ${i === info.page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i + 1}</a></li>`);
            }
            if (endPage < info.pages - 1) {
                if (endPage < info.pages - 2) pagination.append(
                    `<li class="page-item disabled"><span class="page-link">…</span></li>`);
                pagination.append(
                    `<li class="page-item"><a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a></li>`
                    );
            }

            const nextDisabled = info.page === info.pages - 1 ? 'disabled' : '';
            pagination.append(`<li class="page-item ${nextDisabled}">
            <a class="page-link" href="#" data-page="${info.page + 1}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a></li>`);

            pagination.find('a').on('click', function(e) {
                e.preventDefault();
                if (!$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
                    table.page(parseInt($(this).data('page'))).draw('page');
                }
            });
        }

        function initializeModalHandlers() {
            $("#addBtn").on('click', function() {
                resetModal();
                $(".modal-title").text("Tambah Mic Radius");
                $("#type").val('create');

            });

            $("#storeBtn").on('click', handleSave);
        }

        function initializeSelect2() {
            select2User = $('#user_id').select2({
                width: '100%',
                placeholder: '  -- Pilih User --',
                allowClear: true,
                dropdownParent: $('#modal-simple'),
                theme: 'bootstrap-5',
                language: {
                    noResults: () => "Tidak ada user ditemukan",
                    searching: () => "Mencari..."
                }
            });
        }

        function resetModal() {
            $("#id").val('');
            if (select2User) select2User.val(null).trigger('change');
            $("#code").val('');
            $("#name").val('');
            $("#hometowns_id").val('');
            $("#mix_password").val('');
            clearValidationErrors();
        }

        function clearValidationErrors() {
            $(".is-invalid").removeClass('is-invalid');
            $(".invalid-feedback").text('');
            $("#user_id").next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }

        function handleSave() {
            const type = $("#type").val();
            const id = $("#id").val();
            const url = type === 'create' ? BASE + '/store' : BASE + '/' + id + '/update';
            const method = type === 'create' ? 'POST' : 'PUT';

            const btn = $("#storeBtn");
            btn.prop('disabled', true);
            $("#btnText").addClass('d-none');
            $("#btnLoading").removeClass('d-none');

            let formData = new FormData();
            formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
            formData.append("name", $("#name").val());
            formData.append("code", $("#code").val());
            formData.append("hometowns_id", $("#hometowns_id").val());
            formData.append("mix_password", $("#mix_password").val());

            let users = $("#user_id").val() || [];
            users.forEach(u => formData.append("user_id[]", u));

            $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-HTTP-Method-Override': method
                    }
                })
                .done(function(response) {
                    if (response.errors) {
                        showValidationErrors(response.errors);
                    } else {
                        $("#modal-simple").modal('hide');
                        showSuccessMessage(response.message);
                        table.ajax.reload();
                    }
                    resetButton(btn);
                })
                .fail(function(jqXHR) {
                    if (jqXHR.status === 422) {
                        showValidationErrors(jqXHR.responseJSON.errors);
                    } else {
                        showErrorMessage("Terjadi kesalahan");
                    }
                    resetButton(btn);
                });
        }

        function editModal(id) {
            $.get(BASE + '/' + id + '/show')
                .done(function(response) {
                    const data = response.data;
                    $(".modal-title").text("Edit Mic Radius");
                    $("#modal-simple").modal('show');

                    $("#id").val(data.id);
                    $("#code").val(data.code);
                    $("#name").val(data.name);
                    $("#hometowns_id").val(data.hometowns_id);
                    $("#mix_password").val(data.mix_password);
                    $("#type").val('update');

                    let selectedUsers = data.user.map(u => u.id);
                    if (select2User) select2User.val(selectedUsers).trigger("change");


                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan saat mengambil data");
                });
        }

        function mixLogin(id) {
            const url = BASE + '/' + id + '/mix-login';
            window.open(url, '_blank');
        }

        function togglePasswordVisibility() {
            const input = $("#mix_password");
            const icon = $("#eyeIcon");
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.html(`
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                    <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/>
                `);
            } else {
                input.attr('type', 'password');
                icon.html(`
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                `);
            }
        }

        function deleteMicRadius(id) {
            Swal.fire({
                title: "Hapus Radius?",
                text: "Data akan dihapus permanen.",
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
                if (field === 'user_id') {
                    $("#" + field).next('.select2-container').find('.select2-selection').addClass('is-invalid');
                    $(".error_" + field).text(errors[field]).show();
                } else {
                    $("#" + field).addClass('is-invalid');
                    $(".error_" + field).text(errors[field]);
                }
            });
            setTimeout(clearValidationErrors, 3000);
        }

        function showSuccessMessage(message) {
            Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                })
                .fire({
                    icon: "success",
                    title: message
                });
        }

        function showErrorMessage(message) {
            Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                })
                .fire({
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

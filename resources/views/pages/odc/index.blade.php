@extends('layouts.app')

@section('title', 'Data ODC')

@push('css')
    <link href="{{ asset('css/modern-layout.css') }}" rel="stylesheet">
    <style>
        .map-container {
            position: relative;
            width: 100%;
            height: 250px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            margin-top: 1rem;
            display: none;
        }

        .map-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>
@endpush

@section('content')
    <div class="org-container mt-4">
        <div class="org-card">

            {{-- Header --}}
            <div class="org-header">
                <div class="org-title-wrap">
                    <div class="org-header-icon" style="background:linear-gradient(135deg,#e0f2fe,#bae6fd);color:#0284c7;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            <path d="M9 14h6" />
                            <path d="M9 17h6" />
                            <path d="M12 11v-4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="org-title">Data ODC</h2>
                        <p class="org-subtitle mb-0">Kelola master data Optical Distribution Cabinet (ODC).</p>
                    </div>
                </div>

                @can('buat odc')
                    <div class="org-header-action">
                        <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple"
                            class="btn-add">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Tambah ODC
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
                    <input type="text" class="org-input w-100" id="search-input" placeholder="Cari ODC...">
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="org-table" id="odc-table">
                    <thead>
                        <tr>
                            <th style="width:56px;">NO</th>
                            <th>KODE</th>
                            <th>NAMA PEMILIK</th>
                            <th>PLC</th>
                            <th>PATCH CORE</th>
                            <th>KAMPUNG</th>
                            <th>RT</th>
                            <th>RW</th>
                            <th class="text-center">LOKASI</th>
                            <th>CREATED</th>
                            @if (auth()->user()->can('ubah odc') || auth()->user()->can('hapus odc'))
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
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                <path d="M9 14h6" />
                                <path d="M9 17h6" />
                                <path d="M12 11v-4" />
                            </svg>
                        </div>
                        <h5 class="modal-title mb-0" style="color:white;font-weight:700;font-size:.95rem;">Tambah ODC</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body" style="padding:1.5rem;">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="plc_id">Pilih PLC</label>
                            <select name="plc_id" id="plc_id" class="form-select">
                                <option value="">-- Pilih PLC --</option>
                                @foreach ($plcs as $plc)
                                    <option value="{{ $plc->id }}">{{ $plc->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_plc_id"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="patch_core_id">Pilih Patch Core</label>
                            <select name="patch_core_id" id="patch_core_id" class="form-select">
                                <option value="">-- Pilih Patch Core --</option>
                                @foreach ($patchCores as $pc)
                                    <option value="{{ $pc->id }}">{{ $pc->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_patch_core_id"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="code">Kode ODC</label>
                            <input type="text" name="code" id="code" class="form-control"
                                placeholder="Contoh: ODC-01">
                            <span class="invalid-feedback error_code"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="home_odc">Nama Pemilik</label>
                            <input type="text" name="home_odc" id="home_odc" class="form-control"
                                placeholder="Nama Pemilik / Alias">
                            <span class="invalid-feedback error_home_odc"></span>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="hometowns_id">Kampung</label>
                            <select name="hometowns_id" id="hometowns_id" class="form-select">
                                <option value="">-- Pilih Kampung --</option>
                                @foreach ($hometown as $hmt)
                                    <option value="{{ $hmt->id }}">{{ $hmt->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_hometowns_id"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="rts_id">RT</label>
                            <select name="rts_id" id="rts_id" class="form-select">
                                <option value="">-- Pilih RT --</option>
                                @foreach ($rts as $rt)
                                    <option value="{{ $rt->id }}">{{ $rt->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_rts_id"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="rws_id">RW</label>
                            <select name="rws_id" id="rws_id" class="form-select">
                                <option value="">-- Pilih RW --</option>
                                @foreach ($rws as $rw)
                                    <option value="{{ $rw->id }}">{{ $rw->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_rws_id"></span>
                        </div>
                    </div>

                    {{-- Map Area --}}
                    <div class="map-container" id="map-container">
                        <iframe id="map-frame" loading="lazy" allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <div class="mt-2 text-muted small px-1 d-flex gap-1 align-items-center" id="loc-status"
                        style="display:none !important">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="text-success">
                            <path d="M12 21a9 9 0 0 0 9 -9H3a9 9 0 0 0 9 9z" />
                            <path d="M12 3a9 9 0 0 1 9 9H3a9 9 0 0 1 9 -9z" />
                            <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                        </svg>
                        <span>Lokasi berhasil dideteksi otomatis.</span>
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
    <script>
        const BASE = "{{ route('odc.index') }}";
        let table;

        $(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeModalHandlers();
            initializeGeolocation();
        });

        function initializeDataTable() {
            table = $('#odc-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE,
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                order: [
                    [9, 'desc']
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
                        data: 'home_odc',
                        defaultContent: '<span class="text-muted">-</span>'
                    },
                    {
                        data: 'plc',
                        render: function(data) {
                            return data ? `<span class="badge bg-purple-lt">${data.name}</span>` :
                                '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'patch_core',
                        render: function(data) {
                            return data ? `<span class="badge bg-blue-lt">${data.name}</span>` :
                                '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'hometown',
                        render: function(data) {
                            return data ? data.name : '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'rt',
                        render: function(data) {
                            return data ? data.name : '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'rw',
                        render: function(data) {
                            return data ? data.name : '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'location',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (row.latitude && row.longitude) {
                                return `<a href="https://www.google.com/maps?q=${row.latitude},${row.longitude}" target="_blank" class="btn-action d-inline-flex" style="background:#ecfdf5;color:#10b981;border:none;" title="Lihat Peta">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5" />
                                    <path d="M9 4v13" /><path d="M15 7v5" />
                                    <path d="M21 15v4.5a1.5 1.5 0 0 1 -3 0v-4.5a1.5 1.5 0 0 1 3 0" />
                                </svg>
                            </a>`;
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
                        visible: {{ auth()->user()->can('ubah odc') || auth()->user()->can('hapus odc') ? 'true' : 'false' }}
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
                $(".modal-title").text("Tambah ODC");
                $("#type").val('create');

                // Re-trigger loc if needed
                if (!$("#latitude").val() && navigator.geolocation) {
                    initializeGeolocation();
                }
            });
            $("#storeBtn").on('click', handleSave);
        }

        function initializeGeolocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        let latitude = position.coords.latitude;
                        let longitude = position.coords.longitude;
                        $("#latitude").val(latitude);
                        $("#longitude").val(longitude);
                        $("#map-container").css("display", "block");
                        $("#loc-status").attr("style", "display: flex !important;");
                        $("#map-frame").attr("src",
                            `https://www.google.com/maps?q=${latitude},${longitude}&hl=id&z=15&output=embed`);
                    },
                    function(error) {
                        console.error("Error mendapatkan lokasi:", error.message);
                    }
                );
            }
        }

        function resetModal() {
            $("#id").val('');
            $("#plc_id").val('');
            $("#patch_core_id").val('');
            $("#code").val('');
            $("#home_odc").val('');
            $("#hometowns_id").val('');
            $("#rts_id").val('');
            $("#rws_id").val('');
            // Map will not be cleared so user stays on their location for easy adding
            clearValidationErrors();
        }

        function clearValidationErrors() {
            $(".is-invalid").removeClass('is-invalid');
            $(".invalid-feedback").text('');
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

            $.ajax({
                    url: url,
                    method: method,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        code: $("#code").val(),
                        home_odc: $("#home_odc").val(),
                        plc_id: $("#plc_id").val(),
                        patch_core_id: $("#patch_core_id").val(),
                        hometowns_id: $("#hometowns_id").val(),
                        rts_id: $("#rts_id").val(),
                        rws_id: $("#rws_id").val(),
                        latitude: $("#latitude").val(),
                        longitude: $("#longitude").val()
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
                    $(".modal-title").text("Edit ODC");
                    $("#modal-simple").modal('show');

                    $("#id").val(data.id);
                    $("#plc_id").val(data.plc_id);
                    $("#patch_core_id").val(data.patch_core_id);
                    $("#code").val(data.code);
                    $("#home_odc").val(data.home_odc);
                    $("#hometowns_id").val(data.hometowns_id);
                    $("#rts_id").val(data.rts_id);
                    $("#rws_id").val(data.rws_id);
                    $("#latitude").val(data.latitude);
                    $("#longitude").val(data.longitude);
                    $("#type").val('update');

                    if (data.latitude && data.longitude) {
                        $("#map-container").css("display", "block");
                        $("#loc-status").attr("style", "display: none !important;");
                        $("#map-frame").attr("src",
                            `https://www.google.com/maps?q=${data.latitude},${data.longitude}&hl=id&z=15&output=embed`
                            );
                    }
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan saat mengambil data");
                });
        }

        function deleteODC(id) {
            Swal.fire({
                title: "Hapus ODC?",
                text: "Data ODC ini akan dihapus permanen.",
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

@extends('layouts.app')

@section('title')
    Data Mac Address
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('content')
    @include('components.alert.success')
    <div class="card">
        <div class="card-header">
            <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple" class="btn btn-primary me-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" />
                </svg>
                Tambah
            </a>
            <a href="{{ route('mac.address.cetakLabel') }}" class="btn btn-danger me-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                </svg>
                Cetak Label
            </a>
            @if ($isMacValidationActive)
                <form action="{{ route('mac.address.toggleMacValidation') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="value" value="inactive">
                    <button type="submit" class="btn btn-warning me-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><path d="M9 12l2 2l4 -4" />
                        </svg>
                        Fitur Off
                    </button>
                </form>
            @else
                <form action="{{ route('mac.address.toggleMacValidation') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="value" value="active">
                    <button type="submit" class="btn btn-info me-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><path d="M9 12l2 2l4 -4" />
                        </svg>
                        Fitur On
                    </button>
                </form>
            @endif
            @role('Admin')
                <a href="javascript:void(0)" class="btn btn-success" onclick="return switchUsedMac()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l3 3l8 -8" /><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                    </svg>
                    Ubah ke Used
                </a>
            @endrole
        </div>
        
        <div class="card-body border-bottom py-3">
            <div class="d-flex">
                <div class="text-secondary">
                    <div class="mx-2 d-inline-block">
                        <select name="sort" id="sort" class="form-control">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    @role('Admin')
                        <div class="text-secondary">
                            <select name="filter_user" id="filter_user" class="form-control">
                                <option value="">Tampilkan Semua User</option>
                                @foreach ($user as $usr)
                                    <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endrole

                    <div class="text-secondary">
                        <input type="date" class="form-control" name="filter_date" id="filter_date">
                    </div>

                    <div>
                        <button type="button" id="filter-btn" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5.5 5h13a1 1 0 0 1 .5 1.5l-5 5.5l0 7l-4 -3l0 -4l-5 -5.5a1 1 0 0 1 .5 -1.5" />
                            </svg>
                            Filter
                        </button>
                        <button type="button" id="reset-filter-btn" class="btn btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>

                <div class="ms-auto text-secondary">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="search-input" placeholder="Search for…">
                        <button class="btn" type="button" id="search-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body border-bottom py-3">
            <div class="alert alert-primary mb-0">
                <div class="row">
                    <div class="col-md-4">
                        <h4 class="mb-1"><b>Total Mac Address yang sudah di input</b>: <span id="total-count">0</span></h4>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-0"><b>Jumlah Mac Address Available:</b> <strong id="available-count">0</strong></p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-0"><b>Jumlah Mac Address Used:</b> <strong id="used-count">0</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div id="macaddress-table-wrapper" class="table-responsive">
            <table id="macaddress-table" class="table card-table table-vcenter text-nowrap datatable">
                <thead class="bg-secondary">
                    <tr>
                        <th class="w-1 text-white">
                            @role('Admin')
                                <input class="form-check-input" type="checkbox" id="select-all">
                            @endrole
                            No
                        </th>
                        <th class="text-white">Mac Address</th>
                        <th class="text-white">Router</th>
                        <th class="text-white">Status</th>
                        <th class="text-white">Status Device</th>
                        <th class="text-white">Customer</th>
                        <th class="text-white">Di Input</th>
                        <th class="text-white">Created At</th>
                        @canany(['edit mac address','hapus mac address'])
                            <th class="text-white">Action</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="card-footer d-flex align-items-center">
            <p class="m-0 text-secondary">
                Showing <span id="start-entry">0</span> 
                to <span id="end-entry">0</span> of
                <span id="total-entries">0</span> entries
            </p>
            <ul class="pagination m-0 ms-auto" id="custom-pagination"></ul>
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mac Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">
                    
                    <div class="form-group mb-3">
                        <label for="mac_address" class="mb-2">Mac Address <span class="text-danger">*</span></label>
                        <input type="text" id="mac_address" class="form-control" name="mac_address" placeholder="00:00:00:00:00:00">
                        <span class="invalid-feedback error_mac_address"></span>
                        <small class="form-hint">Format: XX:XX:XX:XX:XX:XX</small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="router_id" class="mb-2">Router <span class="text-danger">*</span></label>
                        <select name="router_id" id="router_id" class="form-control">
                            <option value="">-- Pilih Router --</option>
                            @foreach ($router as $rtr)
                                <option value="{{ $rtr->id }}">{{ $rtr->name }}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback error_router_id"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="status_device" class="mb-2">Status Device <span class="text-danger">*</span></label>
                        <select name="status_device" id="status_device" class="form-control">
                            <option value="">-- Pilih Status Device --</option>
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                        </select>
                        <span class="invalid-feedback error_status_device"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary">
                        <span id="btnText">Simpan</span>
                        <span id="btnLoading" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal-customer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="mac" class="mb-2">Mac Address</label>
                        <input type="text" id="mac" class="form-control" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="id_customer" class="mb-2">ID Pelanggan</label>
                        <input type="text" id="id_customer" class="form-control" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="tipe" class="mb-2">Tipe Pelanggan</label>
                        <input type="text" id="tipe" class="form-control" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label class="mb-2">OLT</label>
                        <div class="input-group">
                            <input type="text" id="olt" class="form-control" disabled>
                            <a href="#" id="olt_link" class="btn btn-primary" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                                    <path d="M11 13l9 -9" /><path d="M15 4h5v5" />
                                </svg>
                                Buka Link
                            </a>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="user_show" class="mb-2">Di Input Oleh</label>
                        <input type="text" id="user_show" class="form-control" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="created_user" class="mb-2">Tanggal Terdaftar</label>
                        <input type="text" id="created_user" class="form-control" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const BASE = "{{ route('mac.address.index') }}";
    let table;
    let select2Router;

    $(function() {
        initializeDataTable();
        initializePaginationAndSearch();
        initializeModalHandlers();
        initializeSelect2();
        initializeSelectAll();
        initializeFilters();
        loadStatistics();
    });

    function initializeDataTable() {
        table = $('#macaddress-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: BASE,
                data: function(d) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.user = $('#filter_user').val();
                    d.date = $('#filter_date').val();
                }
            },
            order: [[7, 'desc']], // Sort by created_at column
            pageLength: 10,
            dom: 'rt',
            columns: [
                { 
                    data: 'DT_RowIndex',
                    orderable: false, 
                    searchable: false,
                    render: function(data, type, row) {
                        @role('Admin')
                            return `<input class="form-check-input row-check me-2" type="checkbox" name="selected[]" value="${row.id}"> ${data}`;
                        @else
                            return data;
                        @endrole
                    }
                },
                { 
                    data: 'mac_address',
                    defaultContent: '-'
                },
                { 
                    data: 'router',
                    render: function(data) {
                        return data ? data.name : '-';
                    },
                    defaultContent: '-'
                },
                { 
                    data: 'status',
                    render: function(data) {
                        let badgeClass = data === 'used' ? 'primary' : (data === 'available' ? 'success' : 'danger');
                        return `<span class="badge bg-${badgeClass} text-white">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                    }
                },
                { 
                    data: 'status_device',
                    render: function(data) {
                        let badgeClass = data === 'rusak' ? 'danger' : 'primary';
                        return `<span class="badge bg-${badgeClass} text-white">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                    }
                },
                { 
                    data: 'customer',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (data) {
                            return `<a href="javascript:void(0)" onclick="showCustomer(${row.id})" class="btn btn-sm btn-info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                </svg>
                                Lihat Customer
                            </a>`;
                        }
                        return '<i class="text-muted">Belum Digunakan</i>';
                    }
                },
                { 
                    data: 'user',
                    render: function(data) {
                        return data ? data.name : '-';
                    },
                    defaultContent: '-'
                },
                { 
                    data: 'created_at',
                    render: function(data) {
                        return moment(data).format('DD/MM/YYYY - HH:mm:ss');
                    }
                },
                { 
                    data: 'action', 
                    orderable: false, 
                    searchable: false,
                    visible: {{ auth()->user()->can('edit mac address') || auth()->user()->can('hapus mac address') ? 'true' : 'false' }}
                }
            ],
            drawCallback: function(settings) {
                updatePaginationInfo(settings);
                updateCustomPagination();
            },
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: "Tidak Ada Data",
                zeroRecords: "Tidak Ada Data yang Cocok"
            }
        });
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
        $("#filter-btn").on('click', function() {
            table.ajax.reload();
            loadStatistics();
        });

        $("#reset-filter-btn").on('click', function() {
            $("#filter_user").val('');
            $("#filter_date").val('');
            table.ajax.reload();
            loadStatistics();
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
                </a>
            </li>
        `);

        let startPage = Math.max(0, info.page - 2);
        let endPage = Math.min(info.pages - 1, info.page + 2);

        if (startPage > 0) {
            pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
            if (startPage > 1) {
                pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
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
                pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
            }
            pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a></li>`);
        }

        pagination.append(`
            <li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${info.page + 1}">
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
            $(".modal-title").text("Tambah Mac Address");
            $("#type").val('create');
        });

        $("#storeBtn").on('click', function() {
            handleSave();
        });
    }

    function initializeSelect2() {
        select2Router = $('#router_id').select2({
            width: '100%',
            placeholder: '-- Pilih Router --',
            allowClear: true,
            dropdownParent: $('#modal-simple'),
            theme: 'bootstrap-5'
        });
    }

    function initializeSelectAll() {
        $('#select-all').on('click', function() {
            $('.row-check').prop('checked', this.checked);
        });

        $(document).on('change', '.row-check', function() {
            if ($('.row-check:checked').length === $('.row-check').length) {
                $('#select-all').prop('checked', true);
            } else {
                $('#select-all').prop('checked', false);
            }
        });
    }

    function loadStatistics() {
        $.get(BASE + '/statistics', {
            _token: $('meta[name="csrf-token"]').attr('content'),
            filter_user: $('#filter_user').val(),
            filter_date: $('#filter_date').val()
        })
        .done(function(response) {
            $('#total-count').text(response.total);
            $('#available-count').text(response.available);
            $('#used-count').text(response.used);
        });
    }

    function resetModal() {
        $("#id").val('');
        $("#mac_address").val('');
        if (select2Router) {
            select2Router.val(null).trigger('change');
        }
        $("#status_device").val('');
        clearValidationErrors();
    }

    function clearValidationErrors() {
        $(".form-control").removeClass('is-invalid');
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
                mac_address: $("#mac_address").val(),
                router_id: $("#router_id").val(),
                status_device: $("#status_device").val()
            }
        })
        .done(function(response) {
            if (response.errors) {
                showValidationErrors(response.errors);
                resetButton(btn);
            } else {
                $("#modal-simple").modal('hide');
                showSuccessMessage(response.message);
                table.ajax.reload();
                loadStatistics();
                resetButton(btn);
            }
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
                
                $(".modal-title").text("Edit Mac Address");
                $("#modal-simple").modal('show');
                
                $("#id").val(data.id);
                $("#mac_address").val(data.mac_address);
                if (select2Router) {
                    select2Router.val(data.router_id).trigger('change');
                }
                $("#status_device").val(data.status_device);
                $("#type").val('update');
            })
            .fail(function() {
                showErrorMessage("Terjadi kesalahan saat mengambil data");
            });
    }

    function showCustomer(id) {
        $.get(BASE + '/' + id + '/get-customer')
            .done(function(response) {
                const data = response.data;
                
                $("#modal-customer").modal('show');
                
                $("#mac").val(data.mac_address);
                $("#id_customer").val(data.uuid);
                $("#tipe").val(data.type.name);
                $("#olt").val(data.olt.name);
                $("#olt_link").attr("href", data.olt.link);
                $("#user_show").val(data.user.name);
                
                let date = new Date(data.created_at);
                let formatted = String(date.getDate()).padStart(2, '0') + '/' +
                    String(date.getMonth() + 1).padStart(2, '0') + '/' +
                    date.getFullYear() + ' - ' +
                    String(date.getHours()).padStart(2, '0') + ':' +
                    String(date.getMinutes()).padStart(2, '0') + ':' +
                    String(date.getSeconds()).padStart(2, '0');
                
                $("#created_user").val(formatted);
            })
            .fail(function() {
                showErrorMessage("Terjadi kesalahan saat mengambil data");
            });
    }

    function deleteMicRadius(id) {
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
                    loadStatistics();
                })
                .fail(function() {
                    showErrorMessage("Server Error");
                });
            }
        });
    }

    function switchUsedMac() {
        let checks = document.querySelectorAll('.row-check:checked');

        if (checks.length === 0) {
            showErrorMessage("Pilih satu atau lebih mac address untuk ubah status ke used");
            return false;
        }

        let macIds = Array.from(checks).map(c => c.value);

        Swal.fire({
            title: "Konfirmasi",
            text: `Ubah ${macIds.length} Mac Address ke status Used?`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Ubah",
            cancelButtonText: "Batal"
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: BASE + '/switch-used',
                    method: "POST",
                    data: {
                        ids: macIds,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }
                })
                .done(function(response) {
                    showSuccessMessage(response.message);
                    table.ajax.reload();
                    loadStatistics();
                    $('#select-all').prop('checked', false);
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

    function resetButton(btn) {
        btn.prop('disabled', false);
        $("#btnText").removeClass('d-none');
        $("#btnLoading").addClass('d-none');
    }
</script>
@endpush
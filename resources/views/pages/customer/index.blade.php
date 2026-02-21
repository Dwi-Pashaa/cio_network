@extends('layouts.app')

@section('title')
    Data Pelanggan
@endsection

@push('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    @include('components.alert.success')
    <div class="card">
        @can('buat pelanggan')
            <div class="card-header">
                <a href="{{ route('customer.create') }}" class="btn btn-primary m-2">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Tambah
                </a>
                @can('download excel')
                    <a href="{{ route('customer.export') }}" class="btn btn-success btn-md m-2">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-spreadsheet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M8 11h8v7h-8z" /><path d="M8 15h8" /><path d="M11 11v7" /></svg>
                        Download Excel
                    </a>
                @endcan
                <a href="javascript:void(0)" class="btn btn-info" onclick="return openSwitch()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-transfer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 10h-16l5.5 -6" /><path d="M4 14h16l-5.5 6" /></svg>
                    Pindah OLT
                </a>
            </div>
        @endcan
        <div class="card-body border-bottom py-3">
            <div class="d-flex">
                <div class="text-secondary">
                    <div class="mx-2 d-inline-block">
                        <select name="sort" id="sort" class="form-control">
                            @php
                                $opts = [10, 25, 50, 100];
                            @endphp 
                            @foreach ($opts as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-secondary">
                    <div class="mx-2 d-inline-block">
                        <select name="village" id="village" class="form-control filter-select">
                            <option value="">Pilih Desa</option>
                            @foreach ($vilage as $vlg)
                                <option value="{{ $vlg->id }}">{{ $vlg->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-secondary">
                    <div class="mx-2 d-inline-block">
                        <select name="hometown" id="hometown" class="form-control filter-select">
                            <option value="">Pilih Kampung</option>
                            @foreach ($hometown as $hmt)
                                <option value="{{ $hmt->id }}">{{ $hmt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-secondary">
                    <div class="mx-2 d-inline-block">
                        <select name="vlan" id="vlan" class="form-control filter-select">
                            <option value="">Pilih Vlan</option>
                            @foreach ($vlan as $vln)
                                <option value="{{ $vln->id }}">{{ $vln->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-secondary">
                    <div class="mx-2 d-inline-block">
                        <select name="olt" id="olt" class="form-control filter-select">
                            <option value="">Pilih OLT</option>
                            @foreach ($olts as $ol)
                                <option value="{{ $ol->id }}">{{ $ol->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-secondary">
                    <div class="mx-2 d-inline-block">
                        <select name="micradius" id="micradius" class="form-control filter-select">
                            <option value="">Pilih Mic Radius</option>
                            @foreach ($micRadius as $mc)
                                <option value="{{ $mc->id }}">{{ $mc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="ms-auto text-secondary">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="search-input" placeholder="Search for…">
                        <button class="btn" type="button" id="search-btn">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap" id="customer-table">
                <thead class="bg-secondary">
                    <tr>
                        <th class="text-white w-1">No</th>
                        <th class="text-white">ID Pelanggan</th>
                        <th class="text-white">Tipe Pelanggan</th>
                        <th class="text-white">Tipe Layanan</th>
                        <th class="text-white">NIK</th>
                        <th class="text-white">Nama Pelanggan</th>
                        <th class="text-white">Email</th>
                        <th class="text-white">No Telephone</th>
                        <th class="text-white">Mac Address</th>
                        <th class="text-white">Jenis Router</th>
                        <th class="text-white">Kampung</th>
                        <th class="text-white">Desa</th>
                        <th class="text-white">RT</th>
                        <th class="text-white">RW</th>
                        <th class="text-white">Kecamatan</th>
                        <th class="text-white">Kabupaten/Kota</th>
                        <th class="text-white">Vlan</th>
                        <th class="text-white">Alamat ODC</th>
                        <th class="text-white">Alamat ODP</th>
                        <th class="text-white">Alamat OLT</th>
                        <th class="text-white">Nama Wifi</th>
                        <th class="text-white">Password Wifi</th>
                        <th class="text-white">PPOE Username</th>
                        <th class="text-white">PPOE Password</th>
                        <th class="text-white">Tipe Paket</th>
                        <th class="text-white">Mix Radius</th>
                        <th class="text-white">Tipe Pembayaran</th>
                        <th class="text-white">Lokasi</th>
                        <th class="text-white">Foto KTP</th>
                        <th class="text-white">Di Input Oleh</th>
                        <th class="text-white">Created</th>
                        @if(auth()->user()->can('ubah pelanggan') || auth()->user()->can('hapus pelanggan'))
                            <th class="text-white">Action</th>
                        @endif
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
        <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kirim Pemberitahuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="customer_id" id="customer_id" hidden>
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Tipe Pemberitahuan</label>
                        <select name="notif" id="notif" class="form-control">
                            <option value="">Pilih</option>
                            @php
                                $listNotif = [
                                    'pendaftaran baru',
                                    'riset mac address',
                                    'pindah dari pppoe ke voucher',
                                    'pindah dari voucher ke pppoe',
                                    'ganti perangkat',
                                    'berhenti langganan'
                                ]
                            @endphp
                            @foreach ($listNotif as $ln)
                                <option value="{{ $ln }}">{{ ucfirst($ln) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="send-notif" class="btn btn-primary">
                        <span class="btn-text">Kirim</span>
                        <span class="btn-loading spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal modal-blur fade" id="modal-switch-olt" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pindah OLT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="customer_switch_id" id="customer_switch_id" hidden>
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Pilih OLT</label>
                        <select name="olt_id" id="olt_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($olts as $olt)
                                <option value="{{$olt->id}}">{{$olt->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btn-switch" class="btn btn-primary">
                        <span class="btn-text">Kirim</span>
                        <span class="btn-loading spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
<script>
    const BASE = "{{ route('customer.index') }}";
    let table;

    $(function() {
        initializeDataTable();
        initializePaginationAndSearch();
        initializeModalHandlers();
        initializeFilterHandlers();
    });

    // ===========================
    // DataTable Initialization
    // ===========================
    function initializeDataTable() {
        table = $('#customer-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: BASE,
                data: function(d) {
                    d.village = $('#village').val();
                    d.hometown = $('#hometown').val();
                    d.vlan = $('#vlan').val();
                    d.olt = $('#olt').val();
                    d.micradius = $('#micradius').val();
                    d.search = $('#search-input').val();
                }
            },
            order: [[29, 'desc']],
            pageLength: 10,
            dom: 'rt',
            columns: [
                { 
                    data: 'checkbox',
                    orderable: false, 
                    searchable: false,
                },
                { data: 'uuid', defaultContent: '-' },
                { data: 'tipe_pelanggan', defaultContent: '-' },
                { data: 'type_name' },
                { data: 'nik', defaultContent: '-' },
                { data: 'name' },
                { data: 'email' },
                { data: 'telp' },
                { data: 'mac_address' },
                { data: 'router_name' },
                { data: 'hometown_name' },
                { data: 'village_name' },
                { data: 'rt_name' },
                { data: 'rw_name' },
                { data: 'district_name' },
                { data: 'regencie_name' },
                { data: 'vlan_name' },
                { 
                    data: 'odc_info',
                },
                { 
                    data: 'odp_info',
                },
                { 
                    data: 'olt_info',
                },
                { data: 'name_wifi', defaultContent: '-' },
                { data: 'password_wifi', defaultContent: '-' },
                { data: 'pppoe_username', defaultContent: '-' },
                { data: 'pppoe_password', defaultContent: '-' },
                { data: 'paket_name', defaultContent: '-' },
                { 
                    data: 'mic_radius_info',
                },
                { data: 'price_name', defaultContent: '-' },
                { 
                    data: 'lokasi',
                    orderable: false,
                },
                { 
                    data: 'ktp',
                    orderable: false,
                },
                { data: 'input_by', defaultContent: '-' },
                { 
                    data: 'created_at',
                    render: function(data) {
                        return moment(data).format('DD/MM/YYYY HH:mm:ss');
                    }
                },
                { 
                    data: 'action', 
                    orderable: false, 
                    searchable: false,
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
                e.preventDefault();
                table.ajax.reload();
            }
        });

        // Search on button click
        $("#search-btn").on('click', function(e) {
            e.preventDefault();
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

        // Previous button
        pagination.append(`
            <li class="page-item ${info.page === 0 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${info.page - 1}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
    // Filter Handlers
    // ===========================
    function initializeFilterHandlers() {
        // Filter select changes
        $('.filter-select').on('change', function() {
            table.ajax.reload();
        });
    }

    // ===========================
    // Modal Handlers
    // ===========================
    function initializeModalHandlers() {
        // Chat notification modal
        $("#send-notif").on('click', function() {
            handleSendNotification();
        });

        // Switch OLT modal
        $("#btn-switch").on('click', function() {
            handleSwitchOlt();
        });
    }

    // ===========================
    // CRUD Operations
    // ===========================
    
    // Delete Customer
    function deleteCustomer(id) {
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
                })
                .fail(function() {
                    showErrorMessage("Server Error");
                });
            }
        });
    }

    // Open Chat Modal
    function openChat(id) {
        $("#modal-simple").modal("show");
        $("#customer_id").val(id);
    }

    // Send Notification
    function handleSendNotification() {
        const btn = $("#send-notif");

        if (btn.prop("disabled")) return;

        const btnText = btn.find(".btn-text");
        const btnLoading = btn.find(".btn-loading");

        btn.prop("disabled", true);
        btnText.text("Mengirim...");
        btnLoading.removeClass("d-none");

        let customer_id = $("#customer_id").val();
        let notif = $("#notif").val();

        $.ajax({
            url: "{{ route('customer.notif') }}",
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                customer_id: customer_id,
                notif: notif,
            },
            dataType: "JSON",
        })
        .done(function(response) {
            showSuccessMessage(response.message);
            $("#modal-simple").modal("hide");
            table.ajax.reload();
            resetButton(btn, "Kirim");
        })
        .fail(function() {
            showErrorMessage("Server Error");
            resetButton(btn, "Kirim");
        });
    }

    // Open Switch OLT Modal
    function openSwitch() {
        let checks = document.querySelectorAll('.row-check:checked');

        if (checks.length === 0) {
            showInfoMessage("Pilih satu atau lebih pelanggan untuk dipindah OLT");
            return false;
        }

        let customerIDs = Array.from(checks).map(c => c.value);
        document.getElementById('customer_switch_id').value = JSON.stringify(customerIDs);

        $("#modal-switch-olt").modal("show");
        return true;
    }

    // Handle Switch OLT
    function handleSwitchOlt() {
        const btn = $("#btn-switch");

        btn.prop("disabled", true);
        btn.find(".btn-text").text("Memproses...");
        btn.find(".btn-loading").removeClass("d-none");

        let customer_switch_id = $("#customer_switch_id").val();
        let olt_id = $("#olt_id").val();

        $.ajax({
            url: "{{ route('customer.switchOlt') }}",
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                customer_switch_id: customer_switch_id,
                olt_id: olt_id
            },
            dataType: "JSON"
        })
        .done(function(response) {
            if (response.code == 200) {
                showSuccessMessage(response.message);
                $("#modal-switch-olt").modal("hide");
                table.ajax.reload();
                resetButton(btn, "Kirim");
            } else {
                showErrorMessage(response.message);
                resetButton(btn, "Kirim");
            }
        })
        .fail(function() {
            showErrorMessage("Server Error");
            resetButton(btn, "Kirim");
        });
    }

    // Get Select Options for Hometown
    $("#home_town_id").change(function () {
        let hometown_id = $(this).val();

        if (hometown_id) {
            $.ajax({
                url: "{{ route('customer.getSelect') }}",
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    home_town_id: hometown_id
                },
                dataType: "JSON"
            })
            .done(function(response) {
                let data = response.data;

                // Populate OLT dropdown
                let oltOptions = '<option value="">Pilih</option>';
                if (data.olts && data.olts.length > 0) {
                    data.olts.forEach(item => {
                        oltOptions += `<option value="${item.id}">${item.name}</option>`;
                    });
                }
                $("#olt_id").html(oltOptions);

                // Populate Mic Radius dropdown
                let micOptions = '<option value="">Pilih</option>';
                if (data.micRadius && data.micRadius.length > 0) {
                    data.micRadius.forEach(item => {
                        micOptions += `<option value="${item.id}">${item.code} - ${item.name}</option>`;
                    });
                }
                $("#mic_radius_id").html(micOptions);
            })
            .fail(function() {
                showErrorMessage("Server Error");
            });
        }
    });

    // ===========================
    // Helper Functions
    // ===========================
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

    function showInfoMessage(message) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        Toast.fire({
            icon: "info",
            title: message
        });
    }

    function resetButton(btn, text) {
        btn.prop('disabled', false);
        btn.find(".btn-text").text(text);
        btn.find(".btn-loading").addClass('d-none');
    }

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('copy-btn')) {

            const raw = e.target.dataset.copy;
            if (!raw) return;

            const data = JSON.parse(raw);
            let text = '';

            Object.entries(data).forEach(([k, v]) => {
                if (v) text += `${k} : ${v}\n`;
            });

            navigator.clipboard.writeText(text).then(() => {
                showSuccessMessage('Data copied to clipboard');
            });
        }
    });
</script>
@endpush
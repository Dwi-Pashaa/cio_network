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
    <div class="org-container mt-4">
        <div class="org-card">
            <div class="org-header">
                <div class="org-title-wrap">
                    <div class="org-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="org-title">Data Pelanggan</h2>
                        <p class="org-subtitle mb-0">Kelola dan pantau seluruh data pelanggan aktif.</p>
                    </div>
                </div>

                <div class="org-header-action d-flex gap-2 flex-wrap">
                    @can('buat pelanggan')
                        <a href="{{ route('customer.create') }}" class="btn-add">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="14.5" y2="12"></line>
                            </svg>
                            Tambah Pelanggan
                        </a>
                        @can('download excel')
                            <a href="{{ route('customer.export') }}" class="btn-add"
                                style="background: linear-gradient(135deg,#16a34a,#15803d);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    <path d="M8 11h8v7h-8z" />
                                    <path d="M8 15h8" />
                                    <path d="M11 11v7" />
                                </svg>
                                Excel
                            </a>
                        @endcan
                        <a href="javascript:void(0)" class="btn-add"
                            style="background: linear-gradient(135deg,#0ea5e9,#0284c7);" onclick="return openSwitch()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M20 10h-16l5.5 -6" />
                                <path d="M4 14h16l-5.5 6" />
                            </svg>
                            Pindah OLT
                        </a>
                    @endcan
                </div>
            </div>

            {{-- Filter Toolbar --}}
            <div class="org-toolbar flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <select name="sort" id="sort" class="org-input" style="width: 80px;">
                        @php $opts = [10, 25, 50, 100]; @endphp
                        @foreach ($opts as $opt)
                            <option value="{{ $opt }}">{{ $opt }}</option>
                        @endforeach
                    </select>
                    <span class="text-muted small fw-bold d-none d-sm-inline">ENTRIES</span>
                </div>

                <div class="d-flex flex-wrap gap-2 align-items-center flex-grow-1">
                    <select name="village" id="village" class="org-input filter-select" style="min-width:130px;">
                        <option value="">Semua Desa</option>
                        @foreach ($vilage as $vlg)
                            <option value="{{ $vlg->id }}">{{ $vlg->name }}</option>
                        @endforeach
                    </select>

                    <select name="hometown" id="hometown" class="org-input filter-select" style="min-width:140px;">
                        <option value="">Semua Kampung</option>
                        @foreach ($hometown as $hmt)
                            <option value="{{ $hmt->id }}">{{ $hmt->name }}</option>
                        @endforeach
                    </select>

                    <select name="vlan" id="vlan" class="org-input filter-select" style="min-width:120px;">
                        <option value="">Semua Vlan</option>
                        @foreach ($vlan as $vln)
                            <option value="{{ $vln->id }}">{{ $vln->name }}</option>
                        @endforeach
                    </select>

                    <select name="olt" id="olt" class="org-input filter-select" style="min-width:120px;">
                        <option value="">Semua OLT</option>
                        @foreach ($olts as $ol)
                            <option value="{{ $ol->id }}">{{ $ol->name }}</option>
                        @endforeach
                    </select>

                    <select name="micradius" id="micradius" class="org-input filter-select" style="min-width:140px;">
                        <option value="">Semua Mic Radius</option>
                        @foreach ($micRadius as $mc)
                            <option value="{{ $mc->id }}">{{ $mc->name }}</option>
                        @endforeach
                    </select>

                    @can('verifikasi email')
                        <select name="email_verify" id="email_verify" class="org-input filter-select" style="min-width:150px;">
                            <option value="">Semua Verif Email</option>
                            <option value="register">Terdaftar</option>
                            <option value="not_register">Tidak Terdaftar</option>
                            <option value="belum_dicek">Belum Dicek</option>
                        </select>
                    @endcan

                    @can('verifikasi whatsapp')
                        <select name="wa_verify" id="wa_verify" class="org-input filter-select" style="min-width:150px;">
                            <option value="">Semua Verif WA</option>
                            <option value="registered">Terdaftar</option>
                            <option value="not_registered">Tidak Terdaftar</option>
                            <option value="belum_dicek">Belum Dicek</option>
                        </select>
                    @endcan

                    <select name="type_id" id="type_id" class="org-input filter-select" style="min-width:145px;">
                        <option value="">Semua Tipe Layanan</option>
                        @foreach ($serviceTypes as $tp)
                            <option value="{{ $tp->id }}">{{ $tp->name }}</option>
                        @endforeach
                    </select>

                    <select name="tipe_pelanggan_id" id="tipe_pelanggan_id" class="org-input filter-select" style="min-width:155px;">
                        <option value="">Semua Tipe Pelanggan</option>
                        @foreach ($customerTypes as $tp)
                            <option value="{{ $tp->id }}">{{ $tp->name }}</option>
                        @endforeach
                    </select>

                    @if (optional(auth()->user()->organization)->type !== 'mitra')
                        <select name="organization_id" id="organization_id" class="org-input filter-select" style="min-width:170px;">
                            <option value="">Semua Organisasi/Mitra</option>
                            @foreach ($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="search-wrapper" style="min-width: 200px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" class="org-input w-100" id="search-input" placeholder="Cari pelanggan...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="org-table" id="customer-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">NO</th>
                            <th>ID PELANGGAN</th>
                            <th>TIPE PELANGGAN</th>
                            <th>TIPE LAYANAN</th>
                            <th>NIK</th>
                            <th>NAMA PELANGGAN</th>
                            <th>EMAIL</th>
                            <th>NO TELEPHONE</th>
                            <th>MAC ADDRESS</th>
                            <th>JENIS ROUTER</th>
                            <th>KAMPUNG</th>
                            <th>DESA</th>
                            <th>RT</th>
                            <th>RW</th>
                            <th>KECAMATAN</th>
                            <th>KABUPATEN/KOTA</th>
                            <th>VLAN</th>
                            <th>ALAMAT ODC</th>
                            <th>ALAMAT ODP</th>
                            <th>ALAMAT OLT</th>
                            <th>NAMA WIFI</th>
                            <th>PASSWORD WIFI</th>
                            <th>PPOE USERNAME</th>
                            <th>PPOE PASSWORD</th>
                            <th>TIPE PAKET</th>
                            <th>MIX RADIUS</th>
                            <th>TIPE PEMBAYARAN</th>
                            <th>LOKASI</th>
                            <th>FOTO KTP</th>
                            <th>ORGANISASI/MITRA</th>
                            <th>DI INPUT OLEH</th>
                            <th>CREATED</th>
                            <th>DI UBAH OLEH</th>
                            <th>UPDATED</th>
                            @if (auth()->user()->can('ubah pelanggan') || auth()->user()->can('hapus pelanggan'))
                                <th class="text-center">ACTION</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

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
                                    'berhenti langganan',
                                ];
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
                                <option value="{{ $olt->id }}">{{ $olt->name }}</option>
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
                        d.email_verify = $('#email_verify').val();
                        d.wa_verify = $('#wa_verify').val();
                        d.type_id = $('#type_id').val();
                        d.tipe_pelanggan_id = $('#tipe_pelanggan_id').val();
                        d.organization_id = $('#organization_id').val();
                        d.search = $('#search-input').val();
                    }
                },
                order: [
                    [29, 'desc']
                ],
                pageLength: 10,
                dom: 'rt',
                columns: [{
                        data: 'checkbox',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'uuid',
                        defaultContent: '-'
                    },
                    {
                        data: 'tipe_pelanggan',
                        defaultContent: '-'
                    },
                    {
                        data: 'type_name'
                    },
                    {
                        data: 'nik',
                        defaultContent: '-'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'telp'
                    },
                    {
                        data: 'mac_address'
                    },
                    {
                        data: 'router_name'
                    },
                    {
                        data: 'hometown_name'
                    },
                    {
                        data: 'village_name'
                    },
                    {
                        data: 'rt_name'
                    },
                    {
                        data: 'rw_name'
                    },
                    {
                        data: 'district_name'
                    },
                    {
                        data: 'regencie_name'
                    },
                    {
                        data: 'vlan_name'
                    },
                    {
                        data: 'odc_info',
                    },
                    {
                        data: 'odp_info',
                    },
                    {
                        data: 'olt_info',
                    },
                    {
                        data: 'name_wifi',
                        defaultContent: '-'
                    },
                    {
                        data: 'password_wifi',
                        defaultContent: '-'
                    },
                    {
                        data: 'pppoe_username',
                        defaultContent: '-'
                    },
                    {
                        data: 'pppoe_password',
                        defaultContent: '-'
                    },
                    {
                        data: 'paket_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'mic_radius_info',
                    },
                    {
                        data: 'price_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'lokasi',
                        orderable: false,
                    },
                    {
                        data: 'ktp',
                        orderable: false,
                    },
                    {
                        data: 'organization',
                        defaultContent: '-'
                    },
                    {
                        data: 'input_by',
                        defaultContent: '-'
                    },
                    {
                        data: 'created_at',
                        render: function(data) {
                            return moment(data).format('DD/MM/YYYY HH:mm:ss');
                        }
                    },
                    {
                        data: 'edited_by',
                        defaultContent: '-'
                    },
                    {
                        data: 'updated_at_formatted',
                        defaultContent: '-'
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
                title: "Hapus Pelanggan?",
                text: "Data pelanggan ini akan dihapus permanen.",
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
        $("#home_town_id").change(function() {
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
                                micOptions +=
                                    `<option value="${item.id}">${item.code} - ${item.name}</option>`;
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

        function verifyEmailOnDemand(id, element) {
            const $btn = $(element);
            if ($btn.hasClass('pe-none')) return;
            
            const originalHtml = $btn.html();
            $btn.addClass('pe-none').html('<div class="spinner-border text-primary" role="status" style="width: 12px; height: 12px; border-width: 2px;"></div>');
            
            $.ajax({
                url: "{{ route('customer.verify-email-on-demand') }}",
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id
                },
                dataType: "JSON"
            })
            .done(function(response) {
                if (response.status === 'register') {
                    showSuccessMessage('✅ Email terdaftar: ' + response.message);
                } else {
                    showErrorMessage('❌ Email tidak valid: ' + response.message);
                }
                table.ajax.reload(null, false);
            })
            .fail(function(xhr) {
                const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal mengecek email.";
                showErrorMessage(errorMsg);
                $btn.removeClass('pe-none').html(originalHtml);
            });
        }

        function verifyWaOnDemand(id, element) {
            const $btn = $(element);
            if ($btn.hasClass('pe-none')) return;
            
            const originalHtml = $btn.html();
            $btn.addClass('pe-none').html('<div class="spinner-border text-primary" role="status" style="width: 12px; height: 12px; border-width: 2px;"></div>');
            
            $.ajax({
                url: "{{ route('customer.verify-wa-on-demand') }}",
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id
                },
                dataType: "JSON"
            })
            .done(function(response) {
                if (response.status === 'registered') {
                    showSuccessMessage('✅ WA terdaftar: ' + response.message);
                } else {
                    showErrorMessage('❌ WA tidak terdaftar: ' + response.message);
                }
                table.ajax.reload(null, false);
            })
            .fail(function(xhr) {
                const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal mengecek status WA.";
                showErrorMessage(errorMsg);
                $btn.removeClass('pe-none').html(originalHtml);
            });
        }

        document.addEventListener('click', function(e) {
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

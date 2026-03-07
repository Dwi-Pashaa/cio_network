@extends('layouts.app')

@section('title')
    Data Spam
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/modern-layout.css') }}">
@endpush

@section('content')
    @include('components.alert.success')
    <div class="org-card">
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M18 8a3 3 0 0 1 0 6" />
                        <path d="M10 8v11a1 1 0 0 1 -1 1h-1a1 1 0 0 1 -1 -1v-5" />
                        <path
                            d="M12 8h0l4.524 -3.77a0.9 .9 0 0 1 1.476 .692v12.156a0.9 .9 0 0 1 -1.476 .692l-4.524 -3.77h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1h8" />
                    </svg>
                </div>
                <div>
                    <h3 class="org-title">Data Spam</h3>
                    <p class="org-subtitle mb-0">Kelola data pelanggan yang masuk antrian spam</p>
                </div>
            </div>
            @can('buat desa')
                <div class="org-actions">
                    <a href="{{ route('customer.create') }}" class="btn-add">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14m-7-7h14" />
                        </svg>
                        Tambah
                    </a>
                </div>
            @endcan
        </div>

        <div class="org-toolbar">
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted" style="font-size: 0.88rem;">Tampilkan</span>
                <select name="sort" id="sort" class="org-input" style="width: 80px; padding: 0.35rem 0.8rem;">
                    @php $opts = [10, 25, 50, 100]; @endphp
                    @foreach ($opts as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
                <span class="text-muted" style="font-size: 0.88rem;">entri</span>
            </div>
            <div class="search-wrapper ms-auto">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="text" class="org-input" id="search-input" placeholder="Cari data spam..."
                    autocomplete="off">
            </div>
        </div>

        <div id="spam-table-wrapper" class="table-responsive">
            <table class="table org-table table-vcenter text-nowrap" id="spam-table">
                <thead>
                    <tr>
                        <th class="w-1">No</th>
                        <th>ID Pelanggan</th>
                        <th>Tipe Pelanggan</th>
                        <th>Tipe Layanan</th>
                        <th>NIK</th>
                        <th>Nama Pelanggan</th>
                        <th>Email</th>
                        <th>No Telephone</th>
                        <th>Mac Address</th>
                        <th>Jenis Router</th>
                        <th>Kampung</th>
                        <th>Desa</th>
                        <th>RT</th>
                        <th>RW</th>
                        <th>Kecamatan</th>
                        <th>Kabupaten/Kota</th>
                        <th>Vlan</th>
                        <th>Alamat ODC</th>
                        <th>Alamat ODP</th>
                        <th>Alamat OLT</th>
                        <th>Nama Wifi</th>
                        <th>Password Wifi</th>
                        <th>PPOE Username</th>
                        <th>PPOE Password</th>
                        <th>Tipe Paket</th>
                        <th>Mix Radius</th>
                        <th>Tipe Pembayaran</th>
                        <th>Lokasi</th>
                        <th>Foto KTP</th>
                        <th>Di Input Oleh</th>
                        <th>Created</th>
                        @if (auth()->user()->can('ubah pelanggan') || auth()->user()->can('hapus pelanggan'))
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="org-footer border-top py-3 px-4 d-flex align-items-center justify-content-between">
            <p class="m-0 text-muted" style="font-size: 0.88rem;">
                Showing <span id="start-entry" class="fw-medium">0</span>
                to <span id="end-entry" class="fw-medium">0</span> of
                <span id="total-entries" class="fw-medium">0</span> entries
            </p>
            <ul class="pagination m-0" id="custom-pagination"></ul>
        </div>
    </div>
@endsection

@push('js')
    <script>
        const BASE = "{{ route('spam.index') }}";
        let table;

        $(function() {
            initializeDataTable();
            initializePaginationAndSearch();
        });

        function initializeDataTable() {
            table = $('#spam-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE,
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                order: [
                    [29, 'desc']
                ], // Sort by created_at column
                pageLength: 10,
                dom: 'rt',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
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
                        data: 'type_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'nik',
                        defaultContent: '-'
                    },
                    {
                        data: 'name',
                        defaultContent: '-'
                    },
                    {
                        data: 'email',
                        defaultContent: '-'
                    },
                    {
                        data: 'telp',
                        defaultContent: '-'
                    },
                    {
                        data: 'mac_address',
                        defaultContent: '-'
                    },
                    {
                        data: 'router_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'hometown_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'village_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'rt_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'rw_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'district_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'regencie_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'vlan_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'odc_address',
                        defaultContent: '-'
                    },
                    {
                        data: 'odp_address',
                        defaultContent: '-'
                    },
                    {
                        data: 'olt_address',
                        defaultContent: '-'
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
                        data: 'mic_radius',
                        defaultContent: '-'
                    },
                    {
                        data: 'price_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'location',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'ktp_photo',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'created_at',
                        render: function(data) {
                            return moment(data).format('DD/MM/YYYY HH:mm:ss');
                        }
                    },
                    @if (auth()->user()->can('ubah pelanggan') || auth()->user()->can('hapus pelanggan'))
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false
                        }
                    @endif
                ],
                drawCallback: function(settings) {
                    updatePaginationInfo(settings);
                    updateCustomPagination();
                    handleEmptyState(settings);
                },
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                    emptyTable: "",
                    zeroRecords: ""
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
                    </a>
                </li>
            `);

            let startPage = Math.max(0, info.page - 2);
            let endPage = Math.min(info.pages - 1, info.page + 2);

            // First page
            if (startPage > 0) {
                pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
                if (startPage > 1) {
                    pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
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
                    pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                }
                pagination.append(
                    `<li class="page-item"><a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a></li>`
                    );
            }

            // Next button
            pagination.append(`
                <li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${info.page + 1}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                </li>
            `);

            // Pagination click handler
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

        function handleEmptyState(settings) {
            const api = new $.fn.dataTable.Api(settings);
            const info = api.page.info();

            if (info.recordsDisplay === 0) {
                // Sembunyikan table header dan tampilkan empty state
                $('#spam-table thead').hide();

                const isFiltered = $('#search-input').val();

                const emptyStateHTML = `
                    <tr class="empty-state-row">
                        <td colspan="31" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                        <path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" />
                                        <path d="M12 12l0 .01" />
                                        <path d="M3 13a20 20 0 0 0 18 0" />
                                    </svg>
                                </div>
                                <h3 class="empty-state-title text-muted">Tidak Ada Data Spam</h3>
                                <p class="empty-state-subtitle text-muted mb-3">
                                    ${isFiltered ? 
                                        'Tidak ada data yang cocok dengan pencarian Anda.<br>Silakan coba kata kunci lain atau hapus filter pencarian.' : 
                                        'Tidak ada data pelanggan spam saat ini.<br>Data akan muncul di sini ketika ada pelanggan yang masuk ke spam.'}
                                </p>
                                ${isFiltered ? `
                                        <button type="button" class="btn btn-primary" id="resetSearchBtn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M18 6l-12 12" />
                                                <path d="M6 6l12 12" />
                                            </svg>
                                            Hapus Pencarian
                                        </button>
                                    ` : ''}
                            </div>
                        </td>
                    </tr>
                `;

                // Hapus row empty state yang lama jika ada
                $('#spam-table tbody .empty-state-row').remove();

                // Tambahkan empty state
                $('#spam-table tbody').html(emptyStateHTML);

                // Event handler untuk reset search
                $('#resetSearchBtn').on('click', function() {
                    $('#search-input').val('');
                    table.search('').draw();
                });
            } else {
                // Tampilkan kembali table header jika ada data
                $('#spam-table thead').show();
                $('#spam-table tbody .empty-state-row').remove();
            }
        }

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

        function outSpam(id) {
            Swal.fire({
                title: "Info !",
                text: "Anda yakin ingin memindahkan data ini dari spam?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Keluarkan",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: BASE + '/' + id + '/outSpam',
                        method: "PUT",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json",
                        success: function(response) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Berhasil mengeluarkan pelanggan dari spam.'
                            });
                            table.ajax.reload();
                        },
                        error: function(err) {
                            Toast.fire({
                                icon: "error",
                                title: "Server Error"
                            });
                        }
                    });
                }
            });
        }

        function reject(id) {
            Swal.fire({
                title: "Info !",
                text: "Anda yakin ingin membatalkan data pelanggan ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Iya",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: BASE + '/' + id + '/reject',
                        method: "DELETE",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json",
                        success: function(response) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Berhasil membatalkan data customer.'
                            });
                            table.ajax.reload();
                        },
                        error: function(err) {
                            Toast.fire({
                                icon: "error",
                                title: "Server Error"
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush

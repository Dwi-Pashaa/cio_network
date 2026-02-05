@extends('layouts.app')

@section('title')
    History Pemasangan
@endsection

@push('css')
    <style>
        .empty-state {
            padding: 3rem 1rem;
        }
        
        .empty-state-icon {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .empty-state-icon svg {
            opacity: 0.3;
        }
        
        .empty-state-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .empty-state-subtitle {
            font-size: 0.95rem;
            line-height: 1.6;
        }
    </style>
@endpush

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                @role("Admin")
                    <div class="col-lg-3">
                        <div class="form-group mb-3">
                            <label for="filter_user" class="mb-2">Pilih User</label>
                            <select name="filter_user" id="filter_user" class="form-control">
                                <option value="">Semua User</option>
                                @foreach ($user as $usr)
                                    <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endrole
                <div class="col-lg-3">
                    <div class="form-group mb-3">
                        <label for="filter_start" class="mb-2">Tanggal Mulai</label>
                        <input type="date" name="filter_start" id="filter_start" class="form-control" placeholder="Pilih Tanggal Mulai">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group mb-3">
                        <label for="filter_end" class="mb-2">Tanggal Selesai</label>
                        <input type="date" name="filter_end" id="filter_end" class="form-control" placeholder="Pilih Tanggal Selesai">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group mb-3">
                        <button type="button" id="resetFilterBtn" class="btn btn-secondary w-100 mt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-refresh">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                            </svg>
                            Reset Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <b>Jumlah Pemasangan: <span id="total-pemasangan">0</span></b>
        </div>
        <div class="card-body border-bottom py-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <div class="text-secondary">
                    <select name="sort" id="sort" class="form-control">
                        @php
                            $opts = [10, 25, 50, 100];
                        @endphp 
                        @foreach ($opts as $opt)
                            <option value="{{ $opt }}">{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ms-auto">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search-input" placeholder="Search for…">
                        <button class="btn" type="button" id="search-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                <path d="M21 21l-6 -6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="history-table-wrapper" class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap" id="history-table">
                <thead class="bg-secondary">
                    <tr>
                        <th class="w-1 text-white">No</th>
                        <th class="text-white">Di Input Oleh</th>
                        <th class="text-white">ID Pelanggan</th>
                        <th class="text-white">Nama Pelanggan</th>
                        <th class="text-white">Kampung</th>
                        <th class="text-white">Desa</th>
                        <th class="text-white">Created</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            <p class="m-0 text-secondary">
                Showing <span id="start-entry">0</span> 
                to <span id="end-entry">0</span> of
                <span id="total-entries">0</span> entries
            </p>
            <ul class="pagination m-0 ms-auto" id="custom-pagination">
            </ul>
        </div>
    </div>
@endsection

@push('js')
    <script>
        const BASE = "{{ route('history.index') }}";
        let table;

        $(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeFilters();
        });

        function initializeDataTable() {
            table = $('#history-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE,
                    type: 'GET',
                    data: function(d) {
                        d.filter_user = $('#filter_user').val();
                        d.filter_start = $('#filter_start').val();
                        d.filter_end = $('#filter_end').val();
                    }
                },
                order: [[6, 'desc']], // Sort by created_at column
                pageLength: 10,
                dom: 'rt',
                searching: true,
                columns: [
                    { 
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false, 
                        searchable: false
                    },
                    { 
                        data: 'user_name',
                        name: 'user.name',
                        defaultContent: '-'
                    },
                    { 
                        data: 'uuid',
                        name: 'uuid',
                        defaultContent: '-'
                    },
                    { 
                        data: 'name',
                        name: 'name',
                        defaultContent: '-'
                    },
                    { 
                        data: 'hometown_name',
                        name: 'hometown.name',
                        defaultContent: '-'
                    },
                    { 
                        data: 'village_name',
                        name: 'village.name',
                        defaultContent: '-'
                    },
                    { 
                        data: 'created_at',
                        name: 'created_at'
                    }
                ],
                drawCallback: function(settings) {
                    updatePaginationInfo(settings);
                    updateCustomPagination();
                    handleEmptyState(settings);
                    updateTotalPemasangan(settings);
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

        function initializeFilters() {
            $('#filter_user, #filter_start, #filter_end').on('change', function() {
                table.ajax.reload();
            });

            $('#resetFilterBtn').on('click', function() {
                $('#filter_user').val('');
                $('#filter_start').val('');
                $('#filter_end').val('');
                $('#search-input').val('');
                table.search('').ajax.reload();
            });
        }

        function updatePaginationInfo(settings) {
            const api = new $.fn.dataTable.Api(settings);
            const info = api.page.info();
            
            $('#start-entry').text(info.recordsDisplay > 0 ? info.start + 1 : 0);
            $('#end-entry').text(info.end);
            $('#total-entries').text(info.recordsDisplay);
        }

        function updateTotalPemasangan(settings) {
            const api = new $.fn.dataTable.Api(settings);
            const info = api.page.info();
            $('#total-pemasangan').text(info.recordsTotal);
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
                pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a></li>`);
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
                $('#history-table thead').hide();
                
                const isFiltered = $('#filter_user').val() || $('#filter_start').val() || $('#filter_end').val() || $('#search-input').val();
                
                const emptyStateHTML = `
                    <tr class="empty-state-row">
                        <td colspan="7" class="text-center py-5">
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
                                <h3 class="empty-state-title text-muted">Tidak Ada Data History Pemasangan</h3>
                                <p class="empty-state-subtitle text-muted mb-3">
                                    ${isFiltered ? 
                                        'Tidak ada data yang cocok dengan filter yang dipilih.<br>Silakan ubah filter atau reset filter untuk melihat semua data.' : 
                                        'Belum ada data history pemasangan yang tersedia.<br>Data akan muncul di sini setelah ada pemasangan baru.'}
                                </p>
                                ${isFiltered ? `
                                    <button type="button" class="btn btn-primary" id="resetFiltersBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-filter-off">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M3 3l18 18" />
                                            <path d="M9 5h9.5a1 1 0 0 1 .5 1.5l-4.049 4.454m-.951 3.046v5l-4 -3v-4l-5 -5.5a1 1 0 0 1 .18 -1.316" />
                                        </svg>
                                        Reset Filter
                                    </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
                
                // Hapus row empty state yang lama jika ada
                $('#history-table tbody .empty-state-row').remove();
                
                // Tambahkan empty state
                $('#history-table tbody').html(emptyStateHTML);
                
                // Event handler untuk reset filter
                $('#resetFiltersBtn').on('click', function() {
                    $('#filter_user').val('');
                    $('#filter_start').val('');
                    $('#filter_end').val('');
                    $('#search-input').val('');
                    table.search('').ajax.reload();
                });
            } else {
                // Tampilkan kembali table header jika ada data
                $('#history-table thead').show();
                $('#history-table tbody .empty-state-row').remove();
            }
        }
    </script>
@endpush
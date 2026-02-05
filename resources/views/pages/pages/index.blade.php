@extends('layouts.app')

@section('title')
    Data Halaman
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
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
    <div class="card">
        @can('buat halaman')
            <div class="card-header">
                <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Tambah
                </a>
            </div>
        @endcan
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
                <div>
                    <select name="filter_hometown" id="filter_hometown" class="form-control" style="min-width: 200px;">
                        <option value="">Semua Kampung</option>
                        @foreach ($hometown as $ht)
                            <option value="{{ $ht->id }}">{{ $ht->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="filter_village" id="filter_village" class="form-control" style="min-width: 200px;">
                        <option value="">Semua Desa</option>
                        @foreach ($villages as $vlg)
                            <option value="{{ $vlg->id }}">{{ $vlg->name }}</option>
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
        <div id="halaman-table-wrapper" class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap" id="halaman-table">
                <thead class="bg-secondary">
                    <tr>
                        <th class="w-1 text-white">No</th>
                        <th class="text-white">Nama</th>
                        <th class="text-white">No Telephone</th>
                        <th class="text-white">Fitur KTP</th>
                        <th class="text-white">Created</th>
                        <th class="text-white">Action</th>
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

@push('modal')
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Halaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="name" class="mb-2">Judul Halaman</label>
                                <input type="text" name="name" id="name" class="form-control">
                                <span class="invalid-feedback error_name"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="desc" class="mb-2">Sub Judul Halaman</label>
                                <input type="text" name="desc" id="desc" class="form-control">
                                <span class="invalid-feedback error_desc"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="regencies_id" class="mb-2">Kabupaten/Kota</label>
                                <select name="regencies_id" id="regencies_id" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($regencies as $rgs)
                                        <option value="{{ $rgs->id }}">{{ $rgs->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback error_regencies_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="districts_id" class="mb-2">Kecamatan</label>
                                <select name="districts_id" id="districts_id" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($districts as $dst)
                                        <option value="{{ $dst->id }}">{{ $dst->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback error_districts_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="hometowns_id" class="mb-2">Kampung</label>
                                <select name="hometowns_id" id="hometowns_id" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($hometown as $ht)
                                        <option value="{{ $ht->id }}">{{ $ht->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback error_hometowns_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="villages_id" class="mb-2">Desa</label>
                                <select name="villages_id" id="villages_id" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($villages as $vlg)
                                        <option value="{{ $vlg->id }}">{{ $vlg->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback error_villages_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="routers_id" class="mb-2">Jenis Router</label>
                                <select name="routers_id[]" id="routers_id" class="form-select" multiple>
                                    @foreach ($routers as $rtr)
                                        <option value="{{ $rtr->id }}">{{ $rtr->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback error_routers_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="vlans_id" class="mb-2">Vlan</label>
                                <select name="vlans_id[]" id="vlans_id" class="form-select" multiple>
                                    @foreach ($vlans as $vln)
                                        <option value="{{ $vln->id }}">{{ $vln->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback error_vlans_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="odcs_id" class="mb-2">Alamat ODC</label>
                                <select name="odcs_id[]" id="odcs_id" class="form-select" multiple>
                                    @foreach ($odcs as $odc)
                                        <option value="{{ $odc->id }}">
                                            {{ $odc->code }} | {{ $odc->hometown->name }} 
                                            | {{ $odc->rt->name }} | {{ $odc->rw->name }} |
                                            {{ $odc->home_odc }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback error_odcs_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="odps_id" class="mb-2">Alamat ODP</label>
                                <select name="odps_id[]" id="odps_id" class="form-select" multiple>
                                    @foreach ($odps as $odp)
                                        <option value="{{ $odp->id }}">
                                            {{ $odp->code }} | {{ $odp->hometown->name }} 
                                            | {{ $odp->rt->name }} | {{ $odp->rw->name }} |
                                            {{ $odp->home_odc }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback error_odps_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="olts_id" class="mb-2">Alamat OLT</label>
                                <select name="olts_id[]" id="olts_id" class="form-select" multiple>
                                    @foreach ($olts as $olt)
                                        <option value="{{ $olt->id }}">{{ $olt->hometown->name }} | {{ $olt->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback error_olts_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="paket_id" class="mb-2">Tipe Paket</label>
                                <select name="paket_id[]" id="paket_id" class="form-select" multiple>
                                    @foreach ($paket as $okt)
                                        <option value="{{ $okt->id }}">{{ $okt->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback error_paket_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="mic_radius_id" class="mb-2">Mic Radius</label>
                                <select name="mic_radius_id[]" id="mic_radius_id" class="form-select" multiple>
                                    @foreach ($micRadius as $mc)
                                        <option value="{{ $mc->id }}">{{ $mc->code }} - {{ $mc->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback error_mic_radius_id"></span>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="price" class="mb-2">Tipe Pembayaran</label>
                                <select name="price[]" id="price" class="form-select" multiple>
                                    @foreach ($price as $prc)
                                        <option value="{{ $prc->id }}">{{ $prc->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback error_price"></span>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="is_ktp" class="mb-2">Apakah Halaman Menggunakan KTP</label>
                                <select name="is_ktp" id="is_ktp" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="tidak">Tidak</option>
                                </select>
                                <span class="invalid-feedback error_is_ktp"></span>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="telp" id="telp" class="form-control" value="123">
                    <input type="hidden" name="password" id="password" class="form-control" value="123">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary">
                        <span id="btnText">Simpan</span>
                        <span id="btnLoading" class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const BASE = "{{ route('halaman.index') }}";
        let table;
        let select2Instances = {};

        $(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeModalHandlers();
            initializeFilters();
        });

        function initializeDataTable() {
            table = $('#halaman-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE,
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.filter_hometown = $('#filter_hometown').val();
                        d.filter_village = $('#filter_village').val();
                    }
                },
                order: [[4, 'desc']],
                pageLength: 10,
                dom: 'rt',
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', defaultContent: '-' },
                    { data: 'telp', defaultContent: '-' },
                    { 
                        data: 'is_ktp',
                        render: function(data) {
                            if (data === 'aktif') {
                                return '<span class="badge bg-success text-white">Aktif</span>';
                            } else {
                                return '<span class="badge bg-warning text-white">Tidak Aktif</span>';
                            }
                        }
                    },
                    { 
                        data: 'created_at',
                        render: function(data) {
                            return moment(data).format('DD/MM/YYYY HH:mm:ss');
                        }
                    },
                    { data: 'action', orderable: false, searchable: false }
                ],
                drawCallback: function(settings) {
                    updatePaginationInfo(settings);
                    updateCustomPagination();
                    handleEmptyState(settings);
                },
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                    emptyTable: "", // Kosongkan, kita akan custom
                    zeroRecords: "" // Kosongkan, kita akan custom
                }
            });
        }

        function handleEmptyState(settings) {
            const api = new $.fn.dataTable.Api(settings);
            const info = api.page.info();
            
            if (info.recordsDisplay === 0) {
                // Sembunyikan table header dan tampilkan empty state
                $('#halaman-table thead').hide();
                
                const isFiltered = $('#filter_hometown').val() || $('#filter_village').val() || $('#search-input').val();
                
                const emptyStateHTML = `
                    <tr class="empty-state-row">
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                        <path d="M9 14l6 0" />
                                        <path d="M12 11l0 6" />
                                    </svg>
                                </div>
                                <h3 class="empty-state-title text-muted">Tidak Ada Data</h3>
                                <p class="empty-state-subtitle text-muted mb-3">
                                    ${isFiltered ? 
                                        'Tidak ada data yang cocok dengan filter yang dipilih.<br>Silakan ubah filter atau hapus filter untuk melihat semua data.' : 
                                        'Belum ada data halaman yang tersedia.<br>Klik tombol "Tambah" untuk membuat data baru.'}
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
                $('#halaman-table tbody .empty-state-row').remove();
                
                // Tambahkan empty state
                $('#halaman-table tbody').html(emptyStateHTML);
                
                // Event handler untuk reset filter
                $('#resetFiltersBtn').on('click', function() {
                    $('#filter_hometown').val('');
                    $('#filter_village').val('');
                    $('#search-input').val('');
                    table.search('').draw();
                });
            } else {
                // Tampilkan kembali table header jika ada data
                $('#halaman-table thead').show();
                $('#halaman-table tbody .empty-state-row').remove();
            }
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
            $('#filter_hometown, #filter_village').on('change', function() {
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

        function initializeModalHandlers() {
            $("#addBtn").on('click', function() {
                resetModal();
                $(".modal-title").text("Tambah Halaman");
                $("#type").val('create');
                initializeSelect2();
            });

            $("#storeBtn").on('click', function() {
                handleSave();
            });
        }

        function initializeSelect2() {
            // Destroy existing instances
            destroySelect2();

            // Initialize all select2
            const selectIds = [
                'regencies_id', 'districts_id', 'hometowns_id', 'villages_id',
                'routers_id', 'vlans_id', 'odcs_id', 'odps_id', 'olts_id',
                'paket_id', 'mic_radius_id', 'price'
            ];

            selectIds.forEach(id => {
                select2Instances[id] = $('#' + id).select2({
                    width: '100%',
                    dropdownParent: $('#modal-simple'),
                    theme: 'bootstrap-5',
                    placeholder: '-- Pilih --',
                    allowClear: true
                });
            });
        }

        function destroySelect2() {
            Object.keys(select2Instances).forEach(key => {
                if (select2Instances[key]) {
                    select2Instances[key].select2('destroy');
                }
            });
            select2Instances = {};
        }

        function resetModal() {
            $("#id").val('');
            $("#name").val('');
            $("#desc").val('');
            $("#telp").val('123');
            $("#password").val('123');
            $("#is_ktp").val('');

            // Reset all selects
            const selectIds = [
                'regencies_id', 'districts_id', 'hometowns_id', 'villages_id',
                'routers_id', 'vlans_id', 'odcs_id', 'odps_id', 'olts_id',
                'paket_id', 'mic_radius_id', 'price'
            ];

            selectIds.forEach(id => {
                if (select2Instances[id]) {
                    select2Instances[id].val(null).trigger('change');
                }
            });

            clearValidationErrors();
        }

        function clearValidationErrors() {
            $(".form-control, .form-select").removeClass('is-invalid');
            $(".invalid-feedback").text('');
        }

        function handleSave() {
            const type = $("#type").val();
            const id = $("#id").val();
            
            const url = type === 'create' ? "{{ route('halaman.store') }}" : BASE + '/' + id + '/update';
            
            const btn = $("#storeBtn");
            btn.prop('disabled', true);
            $("#btnText").addClass('d-none');
            $("#btnLoading").removeClass('d-none');

            let formData = new FormData();
            
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('name', $('#name').val());
            formData.append('desc', $('#desc').val());
            formData.append('regencies_id', $('#regencies_id').val());
            formData.append('districts_id', $('#districts_id').val());
            formData.append('hometowns_id', $('#hometowns_id').val());
            formData.append('villages_id', $('#villages_id').val());
            formData.append('routers_id', $('#routers_id').val());
            formData.append('vlans_id', $('#vlans_id').val());
            formData.append('odcs_id', $('#odcs_id').val());
            formData.append('odps_id', $('#odps_id').val());
            formData.append('olts_id', $('#olts_id').val());
            formData.append('paket_id', $('#paket_id').val());
            formData.append('mic_radius_id', $('#mic_radius_id').val());
            formData.append('price', $('#price').val());
            formData.append('telp', $('#telp').val());
            formData.append('password', $('#password').val());
            formData.append('is_ktp', $('#is_ktp').val());

            if (type === 'update') {
                formData.append('_method', 'PUT');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false
            })
            .done(function(response) {
                if (response.errors) {
                    showValidationErrors(response.errors);
                    resetButton(btn);
                } else {
                    $("#modal-simple").modal('hide');
                    showSuccessMessage(response.message);
                    table.ajax.reload();
                    resetButton(btn);
                }
            })
            .fail(function(jqXHR) {
                if (jqXHR.status === 422 && jqXHR.responseJSON && jqXHR.responseJSON.errors) {
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
                    
                    $(".modal-title").text("Edit Halaman");
                    $("#modal-simple").modal('show');
                    
                    // Initialize Select2 first
                    initializeSelect2();
                    
                    $("#id").val(data.id);
                    $("#name").val(data.name);
                    $("#telp").val(data.telp);
                    $("#desc").val(data.desc);
                    $("#is_ktp").val(data.is_ktp);
                    $("#type").val('update');

                    // Set single selects
                    $('#regencies_id').val(data.regencies_id).trigger('change');
                    $('#districts_id').val(data.districts_id).trigger('change');
                    $('#hometowns_id').val(data.hometowns_id).trigger('change');
                    $('#villages_id').val(data.villages_id).trigger('change');

                    // Set multiple selects
                    if (data.router && data.router.length > 0) {
                        const routerIds = data.router.map(item => item.routers_id);
                        $('#routers_id').val(routerIds).trigger('change');
                    }

                    if (data.vlan && data.vlan.length > 0) {
                        const vlanIds = data.vlan.map(item => item.vlans_id);
                        $('#vlans_id').val(vlanIds).trigger('change');
                    }

                    if (data.odc && data.odc.length > 0) {
                        const odcIds = data.odc.map(item => item.odcs_id);
                        $('#odcs_id').val(odcIds).trigger('change');
                    }

                    if (data.odp && data.odp.length > 0) {
                        const odpIds = data.odp.map(item => item.odps_id);
                        $('#odps_id').val(odpIds).trigger('change');
                    }

                    if (data.olt && data.olt.length > 0) {
                        const oltIds = data.olt.map(item => item.olts_id);
                        $('#olts_id').val(oltIds).trigger('change');
                    }

                    if (data.paket && data.paket.length > 0) {
                        const paketIds = data.paket.map(item => item.paket_id);
                        $('#paket_id').val(paketIds).trigger('change');
                    }

                    if (data.mic_radius && data.mic_radius.length > 0) {
                        const micRadiusIds = data.mic_radius.map(item => item.mic_radius_id);
                        $('#mic_radius_id').val(micRadiusIds).trigger('change');
                    }

                    if (data.price && data.price.length > 0) {
                        const priceIds = data.price.map(item => item.price_id);
                        $('#price').val(priceIds).trigger('change');
                    }
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan saat mengambil data");
                });
        }

        function deleteType(id) {
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

        function showValidationErrors(errors) {
            clearValidationErrors();
            
            Object.keys(errors).forEach(function(field) {
                const element = $("#" + field);
                element.addClass('is-invalid');
                
                const errorText = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                $(".error_" + field).text(errorText);
            });

            setTimeout(function() {
                clearValidationErrors();
            }, 5000);
        }

        function showSuccessMessage(message) {
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
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
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

        // Cleanup on modal hide
        $('#modal-simple').on('hidden.bs.modal', function () {
            destroySelect2();
        });
    </script>
@endpush
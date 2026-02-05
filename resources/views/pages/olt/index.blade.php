@extends('layouts.app')

@section('title')
    Data OLT
@endsection

@push('css')
@endpush

@section('content')
<div class="card">
    @can('buat olt')
        <div class="card-header">
            <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple" class="btn btn-primary">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                Tambah
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
                            <option value="{{ $opt }}" {{ request('sort') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
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
        <table class="table card-table table-vcenter text-nowrap datatable" id="olt-table">
            <thead class="bg-secondary">
                <tr>
                    <th class="w-1 text-white">No</th>
                    <th class="text-white">Code</th>
                    <th class="text-white">Nama OLT</th>
                    <th class="text-white">Kampung</th>
                    <th class="text-white">Link OLT</th>
                    <th class="text-white">Created</th>
                    <th class="text-white">Lokasi</th>
                    @if(auth()->user()->can('ubah olt') || auth()->user()->can('hapus olt'))
                        <th class="text-white">Action</th>
                    @endif
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
    <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah OLT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="type" id="type">
                <input type="hidden" name="id" id="id">
                <div class="form-group mb-3">
                    <label for="code" class="mb-2">Kode OLT</label>
                    <input type="text" name="code" id="code" class="form-control">
                    <span class="invalid-feedback error_code"></span>
                </div>
                <div class="form-group mb-3">
                    <label for="name" class="mb-2">Nama OLT</label>
                    <input type="text" name="name" id="name" class="form-control">
                    <span class="invalid-feedback error_name"></span>
                </div>
                <div class="form-group mb-3">
                    <label for="hometowns_id" class="mb-2">Kampung</label>
                    <select name="hometowns_id" id="hometowns_id" class="form-control">
                        <option value="">Pilih</option>
                        @foreach ($hometown as $hmt)
                            <option value="{{ $hmt->id }}">{{ $hmt->name }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback error_hometowns_id"></span>
                </div>
                <div class="form-group mb-3">
                    <label for="link" class="mb-2">Link OLT</label>
                    <input type="text" name="link" id="link" class="form-control">
                    <span class="invalid-feedback error_link"></span>
                </div>
                <div class="mt-3" id="map-container" style="display:none;">
                    <iframe id="map-frame"
                        width="100%" 
                        height="300" 
                        style="border:0; border-radius: 10px;"
                        loading="lazy" 
                        allowfullscreen 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <input type="hidden" name="latitude" id="latitude" class="form-control">
                <input type="hidden" name="longitude" id="longitude" class="form-control">
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
@endpush

@push('js')
    <script>
        const BASE = "{{ route('olt.index') }}";
        let table;

        $(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeModalHandlers();
            initializeGeolocation();
        });

        function initializeDataTable() {
            table = $('#olt-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE,
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                order: [[5, 'desc']], // Sort by Created column
                pageLength: 10,
                dom: 'rt', // Remove default search and pagination
                columns: [
                    { 
                        data: 'DT_RowIndex',
                        orderable: false, 
                        searchable: false,
                        className: 'text-secondary'
                    },
                    { 
                        data: 'code',
                        defaultContent: '-'
                    },
                    { 
                        data: 'name',
                        defaultContent: '-'
                    },
                    { 
                        data: 'hometown',
                        render: function(data) {
                            return data ? data.name : '-';
                        },
                        defaultContent: '-'
                    },
                    { 
                        data: 'link',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            if (data) {
                                return `<a href="${data}" class="btn btn-primary btn-sm" target="_blank">Buka Web OLT</a>`;
                            }
                            return '<i>Belum Ada Link Untuk Web OLT</i>';
                        }
                    },
                    { 
                        data: 'created_at',
                        render: function(data) {
                            return moment(data).format('DD/MM/YYYY HH:mm');
                        }
                    },
                    { 
                        data: 'location',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (row.latitude && row.longitude) {
                                return `<a href="https://www.google.com/maps?q=${row.latitude},${row.longitude}" target="_blank" class="btn btn-primary btn-sm">Lihat Lokasi</a>`;
                            }
                            return '-';
                        }
                    },
                    { 
                        data: 'action', 
                        orderable: false, 
                        searchable: false,
                        visible: {{ auth()->user()->can('ubah olt') || auth()->user()->can('hapus olt') ? 'true' : 'false' }}
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
            // Entries per page
            $("#sort").on('change', function() {
                table.page.len($(this).val()).draw();
            });

            // Search on Enter key
            $("#search-input").on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    table.search(this.value).draw();
                }
            });

            // Search on button click
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

        function initializeModalHandlers() {
            // Add button - open modal for create
            $("#addBtn").on('click', function() {
                resetModal();
                $(".modal-title").text("Tambah OLT");
                $("#type").val('create');
            });

            // Save button - handle create/update
            $("#storeBtn").on('click', function() {
                handleSave();
            });
        }

        function initializeGeolocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        let latitude = position.coords.latitude;
                        let longitude = position.coords.longitude;

                        document.getElementById("latitude").value = latitude;
                        document.getElementById("longitude").value = longitude;

                        document.getElementById("map-container").style.display = "block";
                        document.getElementById("map-frame").src =
                            `https://www.google.com/maps?q=${latitude},${longitude}&hl=id&z=15&output=embed`;
                    },
                    function(error) {
                        console.error("Error mendapatkan lokasi:", error.message);
                    }
                );
            } else {
                console.error("Browser tidak mendukung geolocation.");
            }
        }

        function resetModal() {
            $("#id").val('');
            $("#code").val('');
            $("#name").val('');
            $("#hometowns_id").val('');
            $("#link").val('');
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

            // Show loading
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
                    name: $("#name").val(),
                    hometowns_id: $("#hometowns_id").val(),
                    link: $("#link").val(),
                    latitude: $("#latitude").val(),
                    longitude: $("#longitude").val()
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
                    
                    $(".modal-title").text("Edit OLT");
                    $("#modal-simple").modal('show');
                    
                    $("#id").val(data.id);
                    $("#code").val(data.code);
                    $("#name").val(data.name);
                    $("#hometowns_id").val(data.hometowns_id);
                    $("#link").val(data.link);
                    $("#latitude").val(data.latitude);
                    $("#longitude").val(data.longitude);
                    $("#type").val('update');

                    // Update map if coordinates exist
                    if (data.latitude && data.longitude) {
                        document.getElementById("map-container").style.display = "block";
                        document.getElementById("map-frame").src =
                            `https://www.google.com/maps?q=${data.latitude},${data.longitude}&hl=id&z=15&output=embed`;
                    }
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan saat mengambil data");
                });
        }

        // Delete
        function deleteOLT(id) {
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

        function resetButton(btn) {
            btn.prop('disabled', false);
            $("#btnText").removeClass('d-none');
            $("#btnLoading").addClass('d-none');
        }
    </script>
@endpush
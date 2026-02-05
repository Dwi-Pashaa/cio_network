@extends('layouts.app')

@section('title')
    Data Mic Radius
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="card">
    @can('buat mic radius')
        <div class="card-header">
            <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" />
                </svg>
                Tambah
            </a>
        </div>
    @endcan
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
            <div class="ms-auto text-secondary">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="search-input" placeholder="Search for…">
                    <button class="btn" type="button" id="search-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="micradius-table-wrapper" class="table-responsive">
        <table id="micradius-table" class="table card-table table-vcenter text-nowrap datatable">
            <thead class="bg-secondary">
                <tr>
                    <th class="w-1 text-white">No</th>
                    <th class="text-white">Code</th>
                    <th class="text-white">Nama Mic Radius</th>
                    <th class="text-white">Kampung</th>
                    <th class="text-white">User</th>
                    <th class="text-white">Lokasi</th>
                    <th class="text-white">Created</th>
                    @if(auth()->user()->can('ubah mic radius') || auth()->user()->can('hapus mic radius'))
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
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mic Radius</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="type" id="type">
                <input type="hidden" name="id" id="id">
                
                <div class="form-group mb-3">
                    <label for="user_id" class="mb-2">Pilih User <span class="text-danger">*</span></label>
                    <select name="user_id[]" id="user_id" class="form-control" multiple>
                        @foreach ($user as $usr)
                            <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback error_user_id"></span>
                    <small class="form-hint">Pilih satu atau lebih user</small>
                </div>

                <div class="form-group mb-3">
                    <label for="code" class="mb-2">Kode Mic Radius <span class="text-danger">*</span></label>
                    <input type="text" name="code" id="code" class="form-control" placeholder="Masukkan kode">
                    <span class="invalid-feedback error_code"></span>
                </div>

                <div class="form-group mb-3">
                    <label for="name" class="mb-2">Nama Mic Radius <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama">
                    <span class="invalid-feedback error_name"></span>
                </div>

                <div class="form-group mb-3">
                    <label for="hometowns_id" class="mb-2">Kampung <span class="text-danger">*</span></label>
                    <select name="hometowns_id" id="hometowns_id" class="form-control">
                        <option value="">Pilih Kampung</option>
                        @foreach ($hometown as $hmt)
                            <option value="{{ $hmt->id }}">{{ $hmt->name }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback error_hometowns_id"></span>
                </div>

                <div class="form-group mb-3">
                    <label class="mb-2">Lokasi GPS</label>
                    <div class="mt-2" id="map-container" style="display:none;">
                        <iframe id="map-frame"
                            width="100%" 
                            height="300" 
                            style="border:0; border-radius: 10px;"
                            loading="lazy" 
                            allowfullscreen 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
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
        initializeGeolocation();
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
            order: [[6, 'desc']], // Sort by created column
            pageLength: 10,
            dom: 'rt', // Remove default search and pagination
            columns: [
                { 
                    data: 'DT_RowIndex',
                    orderable: false, 
                    searchable: false,
                },
                { 
                    data: 'code',
                    defaultContent: '-',
                    render: function(data) {
                        return `<a href="#" class="text-reset" tabindex="-1">${data || '-'}</a>`;
                    }
                },
                { 
                    data: 'name',
                    defaultContent: '-',
                    render: function(data) {
                        return `<a href="#" class="text-reset" tabindex="-1">${data || '-'}</a>`;
                    }
                },
                { 
                    data: 'hometown',
                    render: function(data) {
                        return `<a href="#" class="text-reset" tabindex="-1">${data ? data.name : '-'}</a>`;
                    },
                    defaultContent: '-'
                },
                { 
                    data: 'user',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        if (data && data.length > 0) {
                            return data.map(u => `<span class="badge bg-primary text-white p-1">${u.name}</span>`).join(' ');
                        }
                        return '-';
                    },
                    defaultContent: '-'
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
                    data: 'created_at',
                    render: function(data) {
                        return moment(data).format('DD/MM/YYYY HH:mm:ss');
                    }
                },
                { 
                    data: 'action', 
                    orderable: false, 
                    searchable: false,
                    visible: {{ auth()->user()->can('ubah mic radius') || auth()->user()->can('hapus mic radius') ? 'true' : 'false' }}
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
            $(".modal-title").text("Tambah Mic Radius");
            $("#type").val('create');
        });

        // Save button - handle create/update
        $("#storeBtn").on('click', function() {
            handleSave();
        });
    }

    function initializeSelect2() {
        // Initialize Select2 for user selection
        select2User = $('#user_id').select2({
            width: '100%',
            placeholder: 'Pilih User',
            allowClear: true,
            dropdownParent: $('#modal-simple'),
            theme: 'bootstrap-5',
            language: {
                noResults: function() {
                    return "Tidak ada hasil ditemukan";
                },
                searching: function() {
                    return "Mencari...";
                }
            }
        });

        // Event handler when selection changes
        select2User.on('select2:select', function(e) {
            console.log('User selected:', e.params.data);
        });

        select2User.on('select2:unselect', function(e) {
            console.log('User unselected:', e.params.data);
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
        
        // Reset Select2
        if (select2User) {
            select2User.val(null).trigger('change');
        }
        
        $("#code").val('');
        $("#name").val('');
        $("#hometowns_id").val('');
        $("#latitude").val('');
        $("#longitude").val('');
        
        clearValidationErrors();
    }

    function clearValidationErrors() {
        $(".form-control").removeClass('is-invalid');
        $(".invalid-feedback").text('');
        
        // Clear Select2 validation
        $("#user_id").next('.select2-container').find('.select2-selection').removeClass('is-invalid');
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

        let formData = new FormData();
        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
        formData.append("name", $("#name").val());
        formData.append("code", $("#code").val());
        formData.append("hometowns_id", $("#hometowns_id").val());
        formData.append("latitude", $("#latitude").val());
        formData.append("longitude", $("#longitude").val());

        // Get selected users from Select2
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
                
                $(".modal-title").text("Edit Mic Radius");
                $("#modal-simple").modal('show');
                
                $("#id").val(data.id);
                $("#code").val(data.code);
                $("#name").val(data.name);
                $("#hometowns_id").val(data.hometowns_id);
                $("#latitude").val(data.latitude);
                $("#longitude").val(data.longitude);

                // Set selected users in Select2
                let selectedUsers = data.user.map(u => u.id);
                if (select2User) {
                    select2User.val(selectedUsers).trigger("change");
                }
                
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
            if (field === 'user_id') {
                // Special handling for Select2
                $("#" + field).next('.select2-container').find('.select2-selection').addClass('is-invalid');
                $(".error_" + field).text(errors[field]).show();
            } else {
                $("#" + field).addClass('is-invalid');
                $(".error_" + field).text(errors[field]);
            }
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
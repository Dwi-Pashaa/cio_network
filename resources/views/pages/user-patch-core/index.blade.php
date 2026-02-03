@extends('layouts.app')

@section('title')
    Data Stock Patch Core
@endsection

@section('content')
<div class="card">
    @can('buat barang')
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
                    <label class="me-2">Show</label>
                    <select name="sort" id="sort" class="form-control d-inline-block" style="width: auto;">
                        @php
                            $opts = [10, 25, 50, 100];
                        @endphp 
                        @foreach ($opts as $opt)
                            <option value="{{ $opt }}">{{ $opt }}</option>
                        @endforeach
                    </select>
                    <label class="ms-2">entries</label>
                </div>
            </div>
            <div class="ms-auto text-secondary">
                <div class="input-group mb-2" style="width: 300px;">
                    <input type="text" class="form-control" id="search-input" placeholder="Search for…">
                    <button class="btn" type="button" id="search-btn">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="patch-core-table" class="table card-table table-vcenter text-nowrap">
            <thead class="bg-secondary">
                <tr>
                    <th class="text-white w-1">No</th>
                    <th class="text-white">Nama User</th>
                    <th class="text-white">Nama Patch Core</th>
                    <th class="text-white">Jumlah</th>
                    <th class="text-white">Created At</th>
                    <th class="text-white">Action</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary" id="table-info">
            Showing <span id="start-entry">0</span> 
            to <span id="end-entry">0</span> of
            <span id="total-entries">0</span> entries
        </p>
        <ul class="pagination m-0 ms-auto" id="custom-pagination"></ul>
    </div>
</div>
@endsection

@push('modal')
    <!-- Modal Tambah / Edit Stock Patch Core -->
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Stock Patch Core</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">

                    <div class="form-group mb-3" id="role_id_show">
                        <label for="role" class="mb-2">Pilih Level</label>
                        <select name="role" id="role" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($role as $rl)
                                <option value="{{ $rl->name }}">{{ $rl->name }}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback error_role"></span>
                    </div>

                    <div class="form-group mb-3" id="user_id_show" style="display: none;">
                        <label for="user_id" class="mb-2">Pilih User</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">Pilih</option>
                        </select>
                        <span class="invalid-feedback error_user_id"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="patch_core_id" class="mb-2">Pilih Patch Core</label>
                        <select name="patch_core_id" id="patch_core_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($patchCore as $pc)
                                <option value="{{ $pc->id }}">{{ $pc->name }}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback error_patch_core_id"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="total" class="mb-2">Jumlah Patch Core</label>
                        <input type="number" name="total" id="total" class="form-control" min="1">
                        <span class="invalid-feedback error_total"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary">
                        <span class="btn-text">Simpan</span>
                        <span class="btn-loading spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Stock -->
    <div class="modal modal-blur fade" id="modal-add-stock" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Stock Patch Core</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_patch_core_id" id="user_patch_core_id">
                    
                    <div class="form-group mb-3">
                        <label for="user" class="mb-2">User</label>
                        <input type="text" name="user" id="user" class="form-control" disabled>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="total_stock" class="mb-2">Jumlah Patch Core</label>
                        <input type="number" name="total_stock" id="total_stock" class="form-control" min="1">
                        <span class="invalid-feedback error_total_stock"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeAddStock" class="btn btn-primary">
                        <span class="btn-text">Simpan</span>
                        <span class="btn-loading spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
<script>
    const BASE = "{{ route('user.patch.core.index') }}";
    let table;

    $(function() {
        initializeDataTable();
        initializePaginationAndSearch();
        initializeModalHandlers();
        initializeRoleHandler();
    });

    // ===========================
    // DataTable Initialization
    // ===========================
    function initializeDataTable() {
        table = $('#patch-core-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: BASE,
            order: [[4, 'desc']],
            pageLength: 10,
            dom: 'rt',
           columns: [
                {
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'user_name',
                    name: 'users.name',        // penting untuk ORDER BY
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'patch_core_name',
                    name: 'patch_core.name',  // penting untuk ORDER BY
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'total',
                    name: 'user_patch_core.total'
                },
                {
                    data: 'created_at',
                    name: 'user_patch_core.created_at',
                    render: function (data) {
                        return moment(data).format('DD/MM/YYYY HH:mm:ss');
                    }
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
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
                table.search(this.value).draw();
            }
        });

        // Search on button click
        $("#search-btn").on('click', function() {
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
                    prev
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
                    next
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
    // Modal Handlers
    // ===========================
    function initializeModalHandlers() {
        // Add button - open modal for create
        $("#addBtn").on('click', function() {
            resetModal();
            $(".modal-title").text("Tambah Stock Patch Core");
            $("#type").val('create');
            $("#role_id_show").show();
            $("#user_id_show").hide();
        });

        // Save button - handle create/update
        $("#storeBtn").on('click', function() {
            handleSaveStock();
        });

        // Add stock button
        $("#storeAddStock").on('click', function() {
            handleAddStock();
        });
    }

    function resetModal() {
        $("#role").val('');
        $("#user_id").val('').html('<option value="">Pilih</option>');
        $("#patch_core_id").val('');
        $("#total").val('');
        $("#id").val('');
        clearValidationErrors();
    }

    function clearValidationErrors() {
        $(".form-control").removeClass('is-invalid');
        $(".invalid-feedback").text('');
    }

    // ===========================
    // Role Handler
    // ===========================
    function initializeRoleHandler() {
        $("#role").on('change', function() {
            const role = $(this).val();
            
            if (!role) {
                $("#user_id_show").hide();
                return;
            }

            $.ajax({
                url: BASE + '/get-role',
                method: "POST",
                data: {
                    role: role,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            })
            .done(function(response) {
                let html = '<option value="">Pilih</option>';

                if (response.code === 200 && response.data) {
                    $("#user_id_show").show();
                    
                    $.each(response.data, function(index, value) {
                        html += `<option value="${value.id}">${value.name}</option>`;
                    });
                } else {
                    $("#user_id_show").hide();
                }

                $("#user_id").html(html);
            })
            .fail(function() {
                showErrorMessage('Terjadi kesalahan saat mengambil data user');
            });
        });
    }

    // ===========================
    // CRUD Operations
    // ===========================
    
    // Create / Update Stock
    function handleSaveStock() {
        const type = $("#type").val();
        const id = $("#id").val();
        const data = {
            user_id: $("#user_id").val(),
            patch_core_id: $("#patch_core_id").val(),
            total: $("#total").val(),
            role: $("#role").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        const url = type === 'create' ? BASE + '/store' : BASE + '/' + id + '/update';
        const method = type === 'create' ? 'POST' : 'PUT';

        // Show loading
        const btn = $("#storeBtn");
        btn.prop('disabled', true);
        btn.find(".btn-text").text("Menyimpan...");
        btn.find(".btn-loading").removeClass('d-none');

        $.ajax({
            url: url,
            method: method,
            data: data
        })
        .done(function(response) {
            if (response.errors) {
                showValidationErrors(response.errors);
                resetButton(btn, "Simpan");
            } else {
                $("#modal-simple").modal('hide');
                showSuccessMessage(response.message);
                resetButton(btn, "Simpan");
                table.ajax.reload();
            }
        })
        .fail(function() {
            showErrorMessage("Terjadi kesalahan");
            resetButton(btn, "Simpan");
        });
    }

    // Edit Stock - Open modal with data
    function editModal(id) {
        $.get(BASE + '/' + id + '/show')
            .done(function(response) {
                if (response.code === 200) {
                    const data = response.data;
                    
                    $(".modal-title").text("Edit Stock Patch Core");
                    $("#modal-simple").modal('show');
                    
                    $("#id").val(data.id);
                    $("#patch_core_id").val(data.patch_core_id);
                    $("#total").val(data.total);
                    $("#type").val('update');

                    // Trigger role change to load users
                    if (data.user && data.user.roles && data.user.roles[0]) {
                        $("#role").val(data.user.roles[0].name).trigger('change');
                        
                        // Set user_id after a short delay to ensure users are loaded
                        setTimeout(function() {
                            $("#user_id").val(data.user_id);
                        }, 500);
                    }
                } else {
                    showErrorMessage(response.message || 'Terjadi kesalahan');
                }
            })
            .fail(function() {
                showErrorMessage("Terjadi kesalahan saat mengambil data");
            });
    }

    // Add Stock - Open modal
    function addStock(id) {
        $.get(BASE + '/' + id + '/show')
            .done(function(response) {
                if (response.code === 200) {
                    const data = response.data;
                    
                    $("#modal-add-stock").modal('show');
                    $("#user_patch_core_id").val(data.id);
                    $("#user").val(data.user.name);
                    $("#total_stock").val('');
                    clearValidationErrors();
                } else {
                    showErrorMessage(response.message || 'Terjadi kesalahan');
                }
            })
            .fail(function() {
                showErrorMessage("Terjadi kesalahan saat mengambil data");
            });
    }

    // Handle Add Stock
    function handleAddStock() {
        const btn = $("#storeAddStock");
        
        if (btn.prop('disabled')) return;

        // Show loading
        btn.prop('disabled', true);
        btn.find(".btn-text").text("Menyimpan...");
        btn.find(".btn-loading").removeClass('d-none');

        const data = {
            user_patch_core_id: $("#user_patch_core_id").val(),
            total_stock: $("#total_stock").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.post("{{ route('user.patch.core.addStore') }}", data)
            .done(function(response) {
                if (response.errors) {
                    showValidationErrors(response.errors);
                    resetButton(btn, "Simpan");
                } else {
                    $("#modal-add-stock").modal('hide');
                    showSuccessMessage(response.message);
                    resetButton(btn, "Simpan");
                    table.ajax.reload();
                }
            })
            .fail(function() {
                showErrorMessage("Terjadi kesalahan");
                resetButton(btn, "Simpan");
            });
    }

    // Delete Stock
    function deleteStock(id) {
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

    function resetButton(btn, text) {
        btn.prop('disabled', false);
        btn.find(".btn-text").text(text);
        btn.find(".btn-loading").addClass('d-none');
    }
</script>
@endpush
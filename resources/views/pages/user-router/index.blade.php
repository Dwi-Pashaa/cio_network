@extends('layouts.app')

@section('title')
    Data Stock Router
@endsection

@section('content')
<div class="card">
    @can('buat barang')
        <div class="card-header">
            <a href="javascript:void(0)" id="addBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-simple">
                Tambah
            </a>
        </div>
    @endcan

    <div class="card-body border-bottom py-3 d-flex justify-content-between">
        <div>
            <label>Show</label>
            <select id="sort" class="form-control d-inline-block" style="width:auto;">
                @foreach([10,25,50,100] as $opt)
                    <option value="{{ $opt }}">{{ $opt }}</option>
                @endforeach
            </select>
            <label>entries</label>
        </div>
        <div>
            <div class="input-group" style="width:300px;">
                <input type="text" id="search-input" class="form-control" placeholder="Search…">
                <button class="btn" id="search-btn" type="button">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="stock-table" class="table table-vcenter text-nowrap">
            <thead class="bg-secondary">
                <tr>
                    <th class="text-white w-1">No</th>
                    <th class="text-white">Nama User</th>
                    <th class="text-white">Nama Router</th>
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
<!-- Modal Tambah / Edit Stock Router -->
<div class="modal fade" id="modal-simple" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah / Edit Stock Router</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id">
                <input type="hidden" id="type">
                
                <div class="form-group mb-3">
                    <label>User</label>
                    <select id="user_id" class="form-control">
                        <option value="">Pilih User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback error_user_id"></span>
                </div>
                
                <div class="form-group mb-3">
                    <label>Router</label>
                    <select id="router_id" class="form-control">
                        <option value="">Pilih Router</option>
                        @foreach($router as $router)
                            <option value="{{ $router->id }}">{{ $router->code }} - {{ $router->name }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback error_router_id"></span>
                </div>
                
                <div class="form-group mb-3">
                    <label>Jumlah</label>
                    <input type="number" id="total" class="form-control">
                    <span class="invalid-feedback error_total"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="saveBtn">
                    <span class="btn-text">Simpan</span>
                    <span class="spinner-border spinner-border-sm d-none" id="btnLoading"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Stock -->
<div class="modal fade" id="modal-add-stock" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Stock Router</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="user_router_id">
                <div class="form-group mb-3">
                    <label>User</label>
                    <input type="text" id="user" class="form-control" disabled>
                </div>
                <div class="form-group mb-3">
                    <label>Jumlah Stock</label>
                    <input type="number" id="total_stock" class="form-control">
                    <span class="invalid-feedback error_total_stock"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="storeAddStock">
                    <span class="btn-text">Simpan</span>
                    <span class="spinner-border spinner-border-sm d-none" id="btnLoadingAddStock"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('js')
<script>
    const BASE = "{{ route('user.router.index') }}";
    let table;

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

    $(function() {
        initializeDataTable();
        initializePaginationAndSearch();
        initializeModalHandlers();
    });

    // ===========================
    // DataTable Initialization
    // ===========================
    function initializeDataTable() {
        table = $('#stock-table').DataTable({
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
                    data: 'user.name' 
                },
                { 
                    data: 'router.name' 
                },
                { 
                    data: 'total' 
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
                <a class="page-link" href="#" data-page="${info.page - 1}">&laquo;</a>
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
                <a class="page-link" href="#" data-page="${info.page + 1}">&raquo;</a>
            </li>
        `);

        // Event handler for pagination links
        pagination.find('a').on('click', function(e) {
            e.preventDefault();
            const page = parseInt($(this).data('page'));
            if (!isNaN(page) && page >= 0 && page < info.pages) {
                table.page(page).draw('page');
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
            $(".modal-title").text("Tambah Stock Router");
            $("#type").val('create');
        });

        // Save button - handle create/update
        $("#saveBtn").on('click', function() {
            handleSaveStock();
        });

        // Add stock button
        $("#storeAddStock").on('click', function() {
            handleAddStock();
        });
    }

    function resetModal() {
        $("#user_id").val('');
        $("#router_id").val('');
        $("#total").val('');
        $("#id").val('');
        clearValidationErrors();
    }

    function clearValidationErrors() {
        $(".form-control").removeClass('is-invalid');
        $(".invalid-feedback").text('');
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
            router_id: $("#router_id").val(),
            total: $("#total").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        const url = type === 'create' ? BASE + '/store' : BASE + '/' + id + '/update';
        const method = type === 'create' ? 'POST' : 'PUT';

        // Show loading
        $("#saveBtn").prop('disabled', true);
        $("#btnLoading").removeClass('d-none');

        $.ajax({
            url: url,
            method: method,
            data: data
        })
        .done(function(response) {
            if (response.errors) {
                showValidationErrors(response.errors);
            } else {
                $("#modal-simple").modal('hide');
                showSuccessMessage(response.message);
                table.ajax.reload();
            }
        })
        .fail(function() {
            showErrorMessage("Terjadi kesalahan");
        })
        .always(function() {
            $("#saveBtn").prop('disabled', false);
            $("#btnLoading").addClass('d-none');
        });
    }

    // Edit Stock - Open modal with data
    function editModal(id) {
        $.get(BASE + '/' + id + '/show')
            .done(function(response) {
                const data = response.data;
                
                $(".modal-title").text("Edit Stock Router");
                $("#modal-simple").modal('show');
                $("#user_id").val(data.user_id);
                $("#router_id").val(data.router_id);
                $("#total").val(data.total);
                $("#id").val(data.id);
                $("#type").val('update');
            })
            .fail(function() {
                showErrorMessage("Terjadi kesalahan");
            });
    }

    // Add Stock - Open modal
    function addStock(id) {
        $.get(BASE + '/' + id + '/show')
            .done(function(response) {
                const data = response.data;
                
                $("#modal-add-stock").modal('show');
                $("#user_router_id").val(data.id);
                $("#user").val(data.user.name);
                $("#total_stock").val('');
                clearValidationErrors();
            })
            .fail(function() {
                showErrorMessage("Terjadi kesalahan");
            });
    }

    // Handle Add Stock
    function handleAddStock() {
        const btn = $("#storeAddStock");
        
        if (btn.prop('disabled')) return;

        // Show loading
        btn.prop('disabled', true);
        btn.find(".btn-text").text("Menyimpan...");
        btn.find("#btnLoadingAddStock").removeClass('d-none');

        const data = {
            user_router_id: $("#user_router_id").val(),
            total_stock: $("#total_stock").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.post("{{ route('user.router.addStore') }}", data)
            .done(function(response) {
                if (response.errors) {
                    showValidationErrors(response.errors);
                    resetAddStockButton();
                } else {
                    $("#modal-add-stock").modal('hide');
                    showSuccessMessage(response.message);
                    resetAddStockButton();
                    table.ajax.reload();
                }
            })
            .fail(function() {
                showErrorMessage("Terjadi kesalahan");
                resetAddStockButton();
            });

        function resetAddStockButton() {
            btn.prop('disabled', false);
            btn.find(".btn-text").text("Simpan");
            btn.find("#btnLoadingAddStock").addClass('d-none');
        }
    }

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
        Toast.fire({
            icon: "success",
            title: message
        });
    }

    function showErrorMessage(message) {
        Toast.fire({
            icon: "error",
            title: message
        });
    }
</script>
@endpush
@extends('layouts.app')

@section('title')
    Data Stock PLC
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
        <table id="plc-table" class="table card-table table-vcenter text-nowrap">
            <thead class="bg-secondary">
                <tr>
                    <th class="text-white w-1">No</th>
                    <th class="text-white">Nama User</th>
                    <th class="text-white">Nama PLC</th>
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
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Stock PLC</h5>
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
                        <label for="plc_id" class="mb-2">Pilih PLC</label>
                        <select name="plc_id" id="plc_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($plc as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback error_plc_id"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="total" class="mb-2">Jumlah PLC</label>
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

    <div class="modal modal-blur fade" id="modal-add-stock" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Stock PLC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_plc_id" id="user_plc_id">
                    
                    <div class="form-group mb-3">
                        <label for="user" class="mb-2">User</label>
                        <input type="text" name="user" id="user" class="form-control" disabled>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="total_stock" class="mb-2">Jumlah PLC</label>
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
    const BASE = "{{ route('user.plc.index') }}";
    let table;

    $(function() {
        initializeDataTable();
        initializePaginationAndSearch();
        initializeModalHandlers();
        initializeRoleHandler();
    });

    function initializeDataTable() {
        table = $('#plc-table').DataTable({
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
                    data: 'user.name',
                    defaultContent: '-'
                },
                { 
                    data: 'plc.name',
                    defaultContent: '-'
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

    function initializePaginationAndSearch() {
        $("#sort").on('change', function() {
            table.page.len($(this).val()).draw();
        });

        $("#search-input").on('keypress', function(e) {
            if (e.which === 13) {
                table.search(this.value).draw();
            }
        });

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

        for (let i = startPage; i <= endPage; i++) {
            pagination.append(`
                <li class="page-item ${i === info.page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i + 1}</a>
                </li>
            `);
        }

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
            $(".modal-title").text("Tambah Stock PLC");
            $("#type").val('create');
            $("#role_id_show").show();
            $("#user_id_show").hide();
        });

        $("#storeBtn").on('click', function() {
            handleSaveStock();
        });

        $("#storeAddStock").on('click', function() {
            handleAddStock();
        });
    }

    function resetModal() {
        $("#role").val('');
        $("#user_id").val('').html('<option value="">Pilih</option>');
        $("#plc_id").val('');
        $("#total").val('');
        $("#id").val('');
        clearValidationErrors();
    }

    function clearValidationErrors() {
        $(".form-control").removeClass('is-invalid');
        $(".invalid-feedback").text('');
    }

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
    
    function handleSaveStock() {
        const type = $("#type").val();
        const id = $("#id").val();
        const data = {
            user_id: $("#user_id").val(),
            plc_id: $("#plc_id").val(),
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

    function editModal(id) {
        $.get(BASE + '/' + id + '/show')
            .done(function(response) {
                if (response.code === 200) {
                    const data = response.data;
                    
                    $(".modal-title").text("Edit Stock PLC");
                    $("#modal-simple").modal('show');
                    
                    $("#id").val(data.id);
                    $("#plc_id").val(data.plc_id);
                    $("#total").val(data.total);
                    $("#type").val('update');

                    if (data.user && data.user.roles && data.user.roles[0]) {
                        $("#role").val(data.user.roles[0].name).trigger('change');
                        
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

    function addStock(id) {
        $.get(BASE + '/' + id + '/show')
            .done(function(response) {
                if (response.code === 200) {
                    const data = response.data;
                    
                    $("#modal-add-stock").modal('show');
                    $("#user_plc_id").val(data.id);
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

    function handleAddStock() {
        const btn = $("#storeAddStock");
        
        if (btn.prop('disabled')) return;

        btn.prop('disabled', true);
        btn.find(".btn-text").text("Menyimpan...");
        btn.find(".btn-loading").removeClass('d-none');

        const data = {
            user_plc_id: $("#user_plc_id").val(),
            total_stock: $("#total_stock").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.post("{{ route('user.plc.addStore') }}", data)
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
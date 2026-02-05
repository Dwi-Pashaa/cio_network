@extends('layouts.app')

@section('title')
    Data Kampung
@endsection

@push('css')
    
@endpush

@section('content')
<div class="card">
    @can('buat kampung')
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
            <div class="ms-auto text-secondary">
                <div class="input-group mb-2">
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
    <div id="kampung-table-wrapper" class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap" id="kampung-table">
            <thead class="bg-secondary">
                <tr>
                    <th class="w-1 text-white">No</th>
                    <th class="text-white">Kode</th>
                    <th class="text-white">Kabupaten/Kota</th>
                    <th class="text-white">Kecamatan</th>
                    <th class="text-white">Nama Kampung</th>
                    <th class="text-white">Created</th>
                    @if(auth()->user()->can('ubah kampung') || auth()->user()->can('hapus kampung'))
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
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kampung</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="type" id="type">
                <input type="hidden" name="id" id="id">
                <div class="form-group mb-3">
                    <label for="regencie_id" class="mb-2">Pilih Kabupaten/Kota</label>
                    <select name="regencie_id" id="regencie_id" class="form-control">
                        <option value="">-- Pilih Kabupaten/Kota --</option>
                        @foreach($regencie as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback error_regencie_id"></span>
                </div>
                <div class="form-group mb-3">
                    <label for="district_id" class="mb-2">Pilih Kecamatan</label>
                    <select name="district_id" id="district_id" class="form-control">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach ($district as $dd)
                            <option value="{{ $dd->id }}">{{ $dd->name }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback error_district_id"></span>
                </div>
                <div class="form-group mb-3">
                    <label for="name" class="mb-2">Nama Kampung</label>
                    <input type="text" name="name" id="name" class="form-control">
                    <span class="invalid-feedback error_name"></span>
                </div>
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
<script>
    const BASE = "{{ route('kampung.index') }}";
    let table;

    $(function() {
        initializeDataTable();
        initializePaginationAndSearch();
        initializeModalHandlers();
        initializeDependentDropdown();
    });

    function initializeDataTable() {
        table = $('#kampung-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: BASE,
                data: function(d) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            order: [[5, 'desc']], // Sort by created_at column
            pageLength: 10,
            dom: 'rt',
            columns: [
                { 
                    data: 'DT_RowIndex',
                    orderable: false, 
                    searchable: false
                },
                { 
                    data: 'code',
                    defaultContent: '-'
                },
                { 
                    data: 'regencie',
                    render: function(data) {
                        return data ? data.name : '-';
                    },
                    defaultContent: '-'
                },
                { 
                    data: 'district',
                    render: function(data) {
                        return data ? data.name : '-';
                    },
                    defaultContent: '-'
                },
                { 
                    data: 'name',
                    defaultContent: '-'
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
                    visible: {{ auth()->user()->can('ubah kampung') || auth()->user()->can('hapus kampung') ? 'true' : 'false' }}
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
            $(".modal-title").text("Tambah Kampung");
            $("#type").val('create');
        });

        $("#storeBtn").on('click', function() {
            handleSave();
        });
    }

    function resetModal() {
        $("#id").val('');
        $("#name").val('');
        $("#regencie_id").val('');
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

        const btn = $("#storeBtn");
        btn.prop('disabled', true);
        $("#btnText").addClass('d-none');
        $("#btnLoading").removeClass('d-none');

        $.ajax({
            url: url,
            method: method,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: $("#name").val(),
                regencie_id: $("#regencie_id").val(),
                district_id: $("#district_id").val()
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
                
                $(".modal-title").text("Edit Kampung");
                $("#modal-simple").modal('show');
                
                $("#id").val(data.id);
                $("#name").val(data.name);
                $("#regencie_id").val(data.regencie_id);
                
                // Trigger change untuk load districts
                $("#regencie_id").trigger('change');
                
                // Set district setelah districts di-load
                setTimeout(function() {
                    $("#district_id").val(data.district_id);
                }, 500);
                
                $("#type").val('update');
            })
            .fail(function() {
                showErrorMessage("Terjadi kesalahan saat mengambil data");
            });
    }

    function deleteKampung(id) {
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
            $(".error_" + field).text(errors[field][0]);
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
</script>
@endpush
@extends('layouts.app')

@section('title')
    Data RW
@endsection

@push('css')
@endpush

@section('content')
    <div class="org-card">
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                        <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                        <path d="M9 12h6" />
                        <path d="M9 16h6" />
                    </svg>
                </div>
                <div>
                    <h3 class="org-title">Data RW</h3>
                    <p class="org-subtitle mb-0">Kelola master data Rukun Warga</p>
                </div>
            </div>
            @can('buat rw')
                <div class="org-actions">
                    <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple"
                        class="btn-add">
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
                <select id="sort" class="org-input" style="width: 80px; padding: 0.35rem 0.8rem;">
                    @foreach ([10, 25, 50, 100] as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
                <span class="text-muted" style="font-size: 0.88rem;">entri</span>
            </div>
            <div class="search-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="text" class="org-input" id="search-input" placeholder="Cari RW..." autocomplete="off">
            </div>
        </div>

        <div id="advanced-table" class="table-responsive">
            <table class="table org-table table-vcenter text-nowrap datatable" id="rw-table">
                <thead>
                    <tr>
                        <th class="w-1">No</th>
                        <th>Nama RW</th>
                        <th>Created</th>
                        @if (auth()->user()->can('ubah rw') || auth()->user()->can('hapus rw'))
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="table-tbody"></tbody>
            </table>
        </div>

        <div class="org-footer border-top py-3 px-4 d-flex align-items-center justify-content-between">
            <p class="m-0 text-muted" style="font-size: 0.88rem;" id="table-info">
                Showing <span id="start-entry" class="fw-medium">0</span>
                to <span id="end-entry" class="fw-medium">0</span> of
                <span id="total-entries" class="fw-medium">0</span> entries
            </p>
            <ul class="pagination m-0" id="custom-pagination"></ul>
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah RW</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group mb-4">
                        <label for="name" class="form-label fw-medium text-muted">Nama RW</label>
                        <input type="text" name="name" id="name" class="org-input w-100"
                            placeholder="Masukkan nama RW...">
                        <span class="invalid-feedback error_name mt-1" style="font-size:0.85rem;"></span>
                    </div>
                </div>
                <div class="modal-footer px-4 py-3 bg-light">
                    <button type="button" class="btn btn-outline-secondary me-auto"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary px-4">
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
        const BASE = "{{ route('rw.index') }}";

        let table;

        $(function() {
            table = $('#rw-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: BASE,
                order: [
                    [4, 'desc']
                ],
                pageLength: 10,
                dom: 'rt',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'created_at',
                        render: data => moment(data).format('DD/MM/YYYY - HH:mm:ss')
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                drawCallback: function(settings) {
                    updatePaginationInfo(settings);
                    updateCustomPagination();
                }
            });

            $("#sort").change(function() {
                table.page.len($(this).val()).draw();
            });

            $("#search-input").on('keyup', function(e) {
                if (e.which === 13) table.search(this.value).draw();
            });
            $("#search-btn").click(function() {
                table.search($("#search-input").val()).draw();
            });
        });

        function updatePaginationInfo(settings) {
            const info = new $.fn.dataTable.Api(settings).page.info();
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
            <li class="page-item ${info.page===0?'disabled':''}">
                <a class="page-link" href="#" data-page="${info.page-1}">&laquo;</a>
            </li>
        `);

            let startPage = Math.max(0, info.page - 2);
            let endPage = Math.min(info.pages - 1, info.page + 2);

            if (startPage > 0) {
                pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
                if (startPage > 1) pagination.append(
                    `<li class="page-item disabled"><span class="page-link">...</span></li>`);
            }

            for (let i = startPage; i <= endPage; i++) {
                pagination.append(`
                <li class="page-item ${i===info.page?'active':''}">
                    <a class="page-link" href="#" data-page="${i}">${i+1}</a>
                </li>
            `);
            }

            if (endPage < info.pages - 1) {
                if (endPage < info.pages - 2) pagination.append(
                    `<li class="page-item disabled"><span class="page-link">...</span></li>`);
                pagination.append(
                    `<li class="page-item"><a class="page-link" href="#" data-page="${info.pages-1}">${info.pages}</a></li>`
                );
            }

            pagination.append(`
            <li class="page-item ${info.page===info.pages-1?'disabled':''}">
                <a class="page-link" href="#" data-page="${info.page+1}">&raquo;</a>
            </li>
        `);

            pagination.find('a').click(function(e) {
                e.preventDefault();
                const page = parseInt($(this).data('page'));
                if (!isNaN(page) && page >= 0 && page < info.pages) table.page(page).draw('page');
            });
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

        $("#addBtn").click(function() {
            $(".modal-title").text("Tambah RW");
            $("#name").val('');
            $("#type").val('create');
            $("#id").val('');
        });

        $("#storeBtn").click(function() {
            let type = $("#type").val();
            let id = $("#id").val();
            let name = $("#name").val();

            let url = type === 'create' ?
                BASE + '/store' :
                BASE + '/' + id + '/update';

            let method = type === 'create' ? 'POST' : 'PUT';

            $("#storeBtn").prop('disabled', true);
            $("#btnLoading").removeClass('d-none');

            $.ajax({
                url: url,
                method: method,
                data: {
                    name: name
                },
                success: function(res) {
                    if (res.errors) {
                        $(".error_name").text(res.errors.name ?? '');
                        $("#name").addClass('is-invalid');
                    } else {
                        $("#modal-simple").modal('hide');
                        Toast.fire({
                            icon: "success",
                            title: "Berhasil Menyimpan Data"
                        });
                        table.ajax.reload();
                    }
                },
                complete: function() {
                    $("#storeBtn").prop('disabled', false);
                    $("#btnLoading").addClass('d-none');
                    $("#name").removeClass('is-invalid');
                    $(".error_name").text('');
                }
            });
        });

        function editModal(id) {
            $.get(BASE + '/' + id + '/show', function(res) {
                let data = res.data;
                $(".modal-title").text("Edit RW");
                $("#modal-simple").modal('show');
                $("#name").val(data.name);
                $("#id").val(data.id);
                $("#type").val('update');
            });
        }

        function deleteRW(id) {
            Swal.fire({
                title: "Peringatan!",
                text: "Yakin ingin menghapus data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: BASE + '/' + id + '/destroy',
                        method: 'DELETE',
                        success: function() {
                            Toast.fire({
                                icon: "success",
                                title: "Berhasil Menghapus Data"
                            });
                            table.ajax.reload();
                        }
                    });
                }
            });
        }
    </script>
@endpush

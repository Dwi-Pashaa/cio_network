@extends('layouts.app')

@section('title')
    Data User
@endsection

@push('css')
    <style>
        #custom-pagination {
            display: flex;
            list-style: none;
            padding-left: 0;
            gap: 0.25rem;
        }

        #custom-pagination .page-item .page-link {
            min-width: 36px;
            text-align: center;
            padding: 0.375rem 0.5rem;
        }

        #custom-pagination .page-item.active .page-link {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }

        #custom-pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
        }

    </style>
@endpush

@section('content')
@include('components.alert.success')
<div class="card">
    <div class="card-header">
        <a href="{{ route('user.create') }}" class="btn btn-primary">
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
            Tambah
        </a>
    </div>
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
        <table id="users-table"
            class="table card-table table-vcenter text-nowrap datatable">
            <thead class="bg-secondary">
                <tr>
                    <th class="text-white">No</th>
                    <th class="text-white">Username</th>
                    <th class="text-white">Nama Lengkap</th>
                    <th class="text-white">Email</th>
                    <th class="text-white">Telp</th>
                    <th class="text-white">Level</th>
                    <th class="text-white">Penempatan</th>
                    <th class="text-white">Akses Halaman</th>
                    <th class="text-white">OLT</th>
                    <th class="text-white">Mic Radius</th>
                    <th class="text-white">Created</th>
                    <th class="text-white">Updated</th>
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
        <ul class="pagination m-0 ms-auto" id="custom-pagination">
            
        </ul>
    </div>
</div>
@endsection

@push('js')
    <script>
        const BASE = "{{ route('user.index') }}";
        let table;

        $(function () {
            table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.index') }}",
                order: [[10, 'desc']],
                pageLength: 10,
                dom: 'rt', 
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'username' },
                    { data: 'name', orderable: true, searchable: true },
                    { data: 'email', orderable: true, searchable: true },
                    { data: 'telp', defaultContent: '-' },
                    { data: 'role', orderable: false },
                    { data: 'regencie', orderable: false },
                    { data: 'pages', orderable: false },
                    { data: 'olt', orderable: false },
                    { data: 'mix_radius', orderable: false },
                    {data:'created_at', render: data => moment(data).format('DD/MM/YYYY - HH:mm:ss')},
                    {data:'updated_at', render: data => moment(data).format('DD/MM/YYYY - HH:mm:ss')},
                    { data: 'action', orderable: false, searchable: false },
                ],
                drawCallback: function(settings) {
                    updatePaginationInfo(settings);
                    updateCustomPagination();
                }
            });

            $("#sort").change(function() {
                table.page.len($(this).val()).draw();
            });

            $("#search-input").on('keyup', function() {
                table.search(this.value).draw();
            });

            $("#search-btn").on('click', function() {
                table.search($("#search-input").val()).draw();
            });

            $("#search-input").on('keypress', function(e) {
                if (e.which === 13) {
                    table.search(this.value).draw();
                }
            });
        });

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
                    <a class="page-link" href="#" data-page="${info.page - 1}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            `);

            let startPage = Math.max(0, info.page - 2);
            let endPage = Math.min(info.pages - 1, info.page + 2);

            if (startPage > 0) {
                pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
                if (startPage > 1) {
                    pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
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
                    pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                }
                pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a></li>`);
            }

            pagination.append(`
                <li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${info.page + 1}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `);

            pagination.find('a').on('click', function(e) {
                e.preventDefault();
                const page = parseInt($(this).data('page'));
                if (!isNaN(page) && page >= 0 && page < info.pages) {
                    table.page(page).draw('page');
                }
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

        function deleteUsers(id) {
            Swal.fire({
                title: "Peringatan !",
                text: "Anda yakin ingin menghapus data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: BASE + '/' + id + '/destroy',
                        method: "DELETE",
                        dataType: "json",
                        success: function(response) {
                            Toast.fire({
                                icon: response.status,
                                title: response.message
                            });

                            setTimeout(() => {
                                table.ajax.reload();
                            }, 3000);
                        },
                        error: function(err) {
                            Toast.fire({
                                icon: "error",
                                title: "Server Error"
                            });
                        }
                    })
                }
            });
        }
    </script>
@endpush
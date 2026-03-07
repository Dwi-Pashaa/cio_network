@extends('layouts.app')

@section('title')
    Data Organisasi / Mitra
@endsection


@section('content')
<div class="org-container">

    @include('components.alert.success')

    <div class="org-card">
        {{-- HEADER --}}
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div>
                    <h5 class="org-title">Daftar Organisasi/Mitra</h5>
                    <div class="org-subtitle">Manajemen data organisasi dan mitra di dalam sistem</div>
                </div>
            </div>

            @can('tambah organisasi')
            <a href="{{ route('organization.create') }}" class="btn-add">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Tambah Mitra
            </a>
            @endcan
        </div>

        {{-- TOOLBAR --}}
        <div class="org-toolbar">
            <div style="font-size:.85rem; font-weight:600; color:var(--text-muted); display:flex; align-items:center; gap:.5rem;">
                Tampilkan
                <select id="sort" class="org-input" style="padding: .35rem .6rem;">
                    @foreach([10,25,50,100] as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
                data
            </div>

            <div class="search-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="search-input" class="org-input" placeholder="Cari nama mitra…">
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table id="organisasi-table" class="org-table">
                <thead>
                    <tr>
                        <th style="width:50px; text-align:center;">No</th>
                        <th>Nama Organisasi/Mitra</th>
                        <th style="text-align:center;">Tipe Organisasi</th>
                        <th>Created At</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>

        {{-- FOOTER --}}
        <div class="org-footer">
            <div class="org-info" id="table-info">
                Menampilkan <span id="start-entry">0</span>
                sampai <span id="end-entry">0</span> dari
                <span id="total-entries">0</span> data
            </div>
            <ul class="pagination" id="custom-pagination"></ul>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    const BASE = "{{ route('organization.index') }}";

    let table;

    $(function () {
        table = $('#organisasi-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: BASE,
            order: [[3, 'desc']], // Created At default ordering
            pageLength: 10,
            dom: 'rt', // Menghilangkan default filter dan info
            language: {
                emptyTable: "Belum ada data organisasi yang ditambahkan.",
                zeroRecords: "Data organisasi tidak ditemukan."
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'name', className: 'fw-bold text-dark' },
                { 
                    data: 'type', 
                    className: 'text-center',
                    render: function(data) {
                        let tipeClass = data.toLowerCase() === 'mitra' ? 'mitra' : '';
                        return `<span class="badge-tipe ${tipeClass}">${data}</span>`;
                    }
                },
                { data: 'created_at', render: data => moment(data).format('DD/MM/YYYY - HH:mm') },
                { data: 'action', orderable: false, searchable: false, className: 'text-end' },
            ],
            drawCallback: function(settings) {
                updatePaginationInfo(settings);
                updateCustomPagination();
            }
        });

        $("#sort").change(function() {
            table.page.len($(this).val()).draw();
        });

        // Search trigger
        $("#search-input").on('keyup', function(){
            table.search(this.value).draw();
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

        if(info.pages <= 1) return;

        // Custom Pagination Loop (Prev, Pages, Next)
        pagination.append(`
            <li class="page-item ${info.page===0?'disabled':''}">
                <a class="page-link" href="#" data-page="${info.page-1}">&laquo;</a>
            </li>
        `);

        let startPage = Math.max(0, info.page - 2);
        let endPage = Math.min(info.pages - 1, info.page + 2);

        if(startPage > 0){
            pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
            if(startPage > 1) pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        }

        for(let i=startPage; i<=endPage; i++){
            pagination.append(`
                <li class="page-item ${i===info.page?'active':''}">
                    <a class="page-link" href="#" data-page="${i}">${i+1}</a>
                </li>
            `);
        }

        if(endPage < info.pages-1){
            if(endPage < info.pages-2) pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
            pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="${info.pages-1}">${info.pages}</a></li>`);
        }

        pagination.append(`
            <li class="page-item ${info.page===info.pages-1?'disabled':''}">
                <a class="page-link" href="#" data-page="${info.page+1}">&raquo;</a>
            </li>
        `);

        // Pagination Click Event
        pagination.find('a').click(function(e){
            e.preventDefault();
            const page = parseInt($(this).data('page'));
            if(!isNaN(page) && page>=0 && page<info.pages) table.page(page).draw('page');
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

    function deleteOrganisasi(id){
        Swal.fire({
            title: "Hapus Organisasi/Mitra?",
            text: "Selain organisasi, akun admin, role, regency dan hak akses terkait juga akan dihapus secara permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ef4444",
            cancelButtonColor: "#c0c0d0",
            confirmButtonText: "Ya, Hapus Data!",
            cancelButtonText: "Batal",
            customClass: {
                confirmButton: 'btn-add',
            }
        }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url: BASE+'/'+id+'/destroy',
                    method:'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success:function(res){
                        Toast.fire({
                            icon: "success",
                            title: res.message || "Berhasil menghapus organisasi."
                        });
                        table.ajax.reload();
                    },
                    error:function(){
                        Swal.fire("Error","Terjadi kesalahan pada server.","error");
                    }
                });
            }
        });
    }
</script>
@endpush

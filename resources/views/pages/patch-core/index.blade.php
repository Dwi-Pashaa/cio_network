@extends('layouts.app')

@section('title') Data Patch Core @endsection

@section('content')
<div class="org-container">
    @include('components.alert.success')

    <div class="org-card">
        {{-- HEADER --}}
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2.5"  stroke-linecap="round"  stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" />
                    </svg>
                </div>
                <div>
                    <h5 class="org-title">Daftar Patch Core</h5>
                    <div class="org-subtitle">Manajemen master data perangkat Patch Core</div>
                </div>
            </div>

            @can('tambah patch core')
            <button id="addBtn" class="btn-add" data-bs-toggle="modal" data-bs-target="#modal-patch">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Tambah Patch Core
            </button>
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
                <input type="text" id="search-input" class="org-input" placeholder="Cari nama patch core…">
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table id="patch-core-table" class="org-table">
                <thead>
                    <tr>
                        <th style="width:50px; text-align:center;">No</th>
                        <th>Nama Patch Core</th>
                        <th>Created At</th>
                        <th>Updated At</th>
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

@push('modal')
    <!-- Modal Tambah / Edit -->
    <div class="modal fade" id="modal-patch" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Patch Core</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="patch-id">
                    <input type="hidden" id="modal-type">
                    <div class="form-group mb-0">
                        <label class="form-label">Nama Patch Core <span class="text-danger">*</span></label>
                        <input type="text" id="patch-name" class="form-control" placeholder="Contoh: ODC-01">
                        <span class="invalid-feedback error_name" style="font-size: .8rem; font-weight: 500;"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" style="border-radius: 8px; font-weight: 600;" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" style="background: #6366f1; border: none; border-radius: 8px; font-weight: 600;" id="saveBtn">
                        <span class="btn-text" id="btn-text">Simpan</span>
                        <span class="spinner-border spinner-border-sm d-none" id="btnLoading"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
<script>
    const BASE = "{{ route('patch.core.index') }}";

    let table;

    $(function () {
        table = $('#patch-core-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: BASE,
            order: [[2, 'desc']], // Kolom 'Created At' default ordering
            pageLength: 10,
            dom: 'rt', // Menghilangkan default filter dan info
            language: {
                emptyTable: "Belum ada master data patch core.",
                zeroRecords: "Pencarian tidak ditemukan."
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'name', className: 'fw-bold text-dark' },
                { data: 'created_at', render: data => moment(data).format('DD/MM/YYYY - HH:mm') },
                { data: 'updated_at', render: data => moment(data).format('DD/MM/YYYY - HH:mm') },
                { data: 'action', orderable: false, searchable: false, className: 'text-end' },
            ],
            drawCallback: function(settings) {
                updatePaginationInfo(settings);
                updateCustomPagination();
            }
        });

        // Pagination length
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

    $("#addBtn").click(function(){
        $(".modal-title").text("Tambah Patch Core");
        $("#patch-name").val('');
        $("#patch-name").removeClass('is-invalid');
        $(".error_name").text('');
        $("#modal-type").val('create');
        $("#patch-id").val('');
    });

    $("#saveBtn").click(function(){
        let type = $("#modal-type").val();
        let id = $("#patch-id").val();
        let name = $("#patch-name").val();

        let url = type=='create' ? BASE+'/store' : BASE+'/'+id+'/update';
        let method = type=='create' ? 'POST' : 'PUT';

        $("#saveBtn").prop('disabled',true);
        $("#btnLoading").removeClass('d-none');
        $("#btn-text").addClass('d-none');

        $.ajax({
            url: url,
            method: method,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {name:name},
            success: function(res){
                if(res.errors){
                    $.each(res.errors,function(i,v){
                        $("#patch-name").addClass('is-invalid');
                        $(".error_"+i).text(v);
                    });
                    setTimeout(()=>{ $("#patch-name").removeClass('is-invalid'); $(".error_name").text(''); },3000);
                } else {
                    $("#modal-patch").modal('hide');
                    Toast.fire({
                        icon: "success",
                        title: "Berhasil menyimpan patch core."
                    });
                    table.ajax.reload();
                }
            },
            complete: function(){ 
                $("#saveBtn").prop('disabled',false); 
                $("#btnLoading").addClass('d-none'); 
                $("#btn-text").removeClass('d-none');
            }
        });
    });

    function editModal(id){
        $.get(BASE+'/'+id+'/show', function(res){
            let data = res.data;
            $(".modal-title").text("Edit Patch Core");
            $("#patch-name").removeClass('is-invalid');
            $(".error_name").text('');
            $("#modal-patch").modal('show');
            $("#patch-name").val(data.name);
            $("#patch-id").val(data.id);
            $("#modal-type").val('update');
        });
    }

    function deletePatchCore(id){
        Swal.fire({
            title: "Hapus Patch Core?",
            text: "Apakah anda yakin ingin menghapus data patch core ini secara permanen?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ef4444",
            cancelButtonColor: "#c0c0d0",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url: BASE+'/'+id+'/destroy',
                    method:'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success:function(res){
                        Toast.fire({
                            icon: "success",
                            title: "Berhasil menghapus patch core."
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

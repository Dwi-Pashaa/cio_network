@extends('layouts.app')

@section('title')
    Data RW
@endsection

@push('css')
    
@endpush

@section('content')
<div class="card">
    @can('buat rw')
        <div class="card-header">
            <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple" class="btn btn-primary">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
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
     <div id="advanced-table" class="table-responsive-lg">
        <table class="table card-table table-vcenter text-nowrap datatable" id="rw-table">
            <thead class="bg-secondary">
                <tr>
                    <th class="w-1 text-white">No</th>
                    <th class="text-white">
                        <button class="table-sort" data-sort="sort-name">Nama RW</button>
                    </th>
                    <th class="text-white">
                        <button class="table-sort" data-sort="sort-created">Created</button>
                    </th>
                    @if(auth()->user()->can('ubah rw') || auth()->user()->can('hapus rw'))
                        <th class="text-white">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody class="table-tbody">
               
            </tbody>
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
    <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah RW</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="type" id="type">
                <input type="hidden" name="id" id="id">
                <div class="form-group mb-3">
                    <label for="name" class="mb-2">Nama RW</label>
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
    const BASE = "{{ route('rw.index') }}";

    let table;

    $(function () {
        table = $('#rw-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: BASE,
            order: [[4, 'desc']],
            pageLength: 10,
            dom: 'rt',
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name' },
                { data: 'created_at', render: data => moment(data).format('DD/MM/YYYY - HH:mm:ss') },
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

        $("#search-input").on('keyup', function(e){
            if(e.which === 13) table.search(this.value).draw();
        });
        $("#search-btn").click(function(){
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
        $(".modal-title").text("Tambah RT");
        $("#name").val('');
        $("#type").val('create');
        $("#id").val('');
    });

    $("#storeBtn").click(function(){
        let type = $("#type").val();
        let id = $("#id").val();
        let name = $("#name").val();

        let url = type === 'create'
            ? BASE + '/store'
            : BASE + '/' + id + '/update';

        let method = type === 'create' ? 'POST' : 'PUT';

        $("#storeBtn").prop('disabled', true);
        $("#btnLoading").removeClass('d-none');

        $.ajax({
            url: url,
            method: method,
            data: { name: name },
            success: function(res){
                if(res.errors){
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
            complete: function(){
                $("#storeBtn").prop('disabled', false);
                $("#btnLoading").addClass('d-none');
                $("#name").removeClass('is-invalid');
                $(".error_name").text('');
            }
        });
    });

    function editModal(id){
        $.get(BASE + '/' + id + '/show', function(res){
            let data = res.data;
            $(".modal-title").text("Edit RT");
            $("#modal-simple").modal('show');
            $("#name").val(data.name);
            $("#id").val(data.id);
            $("#type").val('update');
        });
    }

    function deleteRW(id){
        Swal.fire({
            title: "Peringatan!",
            text: "Yakin ingin menghapus data ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal"
        }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url: BASE + '/' + id + '/destroy',
                    method:'DELETE',
                    success:function(){
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
@extends('layouts.app')

@section('title')
    Data PLC Box
@endsection

@section('content')
<div class="card">
    @can('tambah plc')
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
        <table id="plc-table" class="table table-vcenter text-nowrap">
            <thead class="bg-secondary">
                <tr>
                    <th class="text-white w-1">No</th>
                    <th class="text-white text-start">Serial Number</th>
                    <th class="text-white">Nama PLC Box</th>
                    <th class="text-white">Tipe PLC Box</th>
                    <th class="text-white">Created</th>
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
<!-- Modal Tambah / Edit -->
<div class="modal fade" id="modal-simple" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah PLC Box</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="plc-id">
                <input type="hidden" id="modal-type">
                <div class="form-group mb-3">
                    <label>Serial Number</label>
                    <input type="text" id="serial_number" class="form-control">
                    <span class="invalid-feedback error_serial_number"></span>
                </div>
                <div class="form-group mb-3">
                    <label>Nama PLC Box</label>
                    <input type="text" id="name" class="form-control">
                    <span class="invalid-feedback error_name"></span>
                </div>
                <div class="form-group mb-3">
                    <label>Tipe PLC Box</label>
                    <input type="text" id="type_plc" class="form-control">
                    <span class="invalid-feedback error_type_plc"></span>
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
@endpush

@push('js')
<script>
    const BASE = "{{ route('plc.index') }}";

    let table;

    $(function () {
        table = $('#plc-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: BASE,
            order: [[4, 'desc']], // Kolom 'Created'
            pageLength: 10,
            dom: 'rt',
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'serial_number' },
                { data: 'name' },
                { data: 'type' },
                { data: 'created_at', render: data => moment(data).format('DD/MM/YYYY - HH:mm:ss') },
                { data: 'action', orderable: false, searchable: false },
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

        // Search
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
        $(".modal-title").text("Tambah PLC Box");
        $("#serial_number, #name, #type_plc, #plc-id").val('');
        $("#modal-type").val('create');
    });

    $("#saveBtn").click(function(){
        let type = $("#modal-type").val();
        let id = $("#plc-id").val();
        let data = {
            serial_number: $("#serial_number").val(),
            name: $("#name").val(),
            type_plc: $("#type_plc").val()
        };

        let url = type==='create' ? BASE+'/store' : BASE+'/'+id+'/update';
        let method = type==='create' ? 'POST' : 'PUT';

        $("#saveBtn").prop('disabled', true);
        $("#btnLoading").removeClass('d-none');

        $.ajax({
            url: url,
            method: method,
            data: data,
            success: function(res){
                if(res.errors){
                    $.each(res.errors,function(i,v){
                        $("#" + i).addClass('is-invalid');
                        $(".error_" + i).text(v);
                        setTimeout(()=>{ $("#" + i).removeClass('is-invalid'); $(".error_" + i).text(''); },3000);
                    });
                } else {
                    $("#modal-simple").modal('hide');
                    Toast.fire({icon: "success", title: res.message});
                    table.ajax.reload();
                }
            },
            complete: function(){
                $("#saveBtn").prop('disabled', false);
                $("#btnLoading").addClass('d-none');
            }
        });
    });

    function editModal(id){
        $.get(BASE+'/'+id+'/show', function(res){
            let data = res.data;
            $(".modal-title").text("Edit PLC Box");
            $("#modal-simple").modal('show');
            $("#serial_number").val(data.serial_number);
            $("#name").val(data.name);
            $("#type_plc").val(data.type);
            $("#plc-id").val(data.id);
            $("#modal-type").val('update');
        });
    }

    function deletePLC(id){
        Swal.fire({
            title: "Peringatan!",
            text: "Apakah anda yakin ingin menghapus PLC ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal"
        }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url: BASE+'/'+id+'/destroy',
                    method:'DELETE',
                    success:function(res){
                        Toast.fire({icon:"success", title:"Berhasil menghapus PLC."});
                        table.ajax.reload();
                    },
                    error:function(){
                        Swal.fire("Error","Server Error","error");
                    }
                });
            }
        });
    }
</script>
@endpush

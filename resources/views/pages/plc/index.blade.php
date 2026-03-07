@extends('layouts.app')

@section('title') Data PLC Box @endsection

@section('content')
<div class="org-container">
    @include('components.alert.success')

    <div class="org-card">
        {{-- HEADER --}}
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M5 8m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                        <path d="M9 13h.01" />
                        <path d="M13 13h.01" />
                        <path d="M17 13h.01" />
                    </svg>
                </div>
                <div>
                    <h5 class="org-title">Daftar PLC Box</h5>
                    <div class="org-subtitle">Manajemen master data perangkat PLC Box</div>
                </div>
            </div>

            @can('tambah plc')
            <button id="addBtn" class="btn-add" data-bs-toggle="modal" data-bs-target="#modal-simple">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Tambah PLC Box
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
                <input type="text" id="search-input" class="org-input" placeholder="Cari nama atau SN…">
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table id="plc-table" class="org-table">
                <thead>
                    <tr>
                        <th style="width:50px; text-align:center;">No</th>
                        <th>Nomor Seri (SN)</th>
                        <th>Nama PLC Box</th>
                        <th>Tipe PLC Box</th>
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
                    <label class="form-label">Serial Number (SN) <span class="text-danger">*</span></label>
                    <input type="text" id="serial_number" class="form-control" placeholder="Masukkan SN PLC">
                    <span class="invalid-feedback error_serial_number" style="font-size: .8rem; font-weight: 500;"></span>
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Nama PLC Box <span class="text-danger">*</span></label>
                    <input type="text" id="name" class="form-control" placeholder="Contoh: PLC-A11">
                    <span class="invalid-feedback error_name" style="font-size: .8rem; font-weight: 500;"></span>
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label">Tipe PLC Box <span class="text-danger">*</span></label>
                    <input type="text" id="type_plc" class="form-control" placeholder="Contoh: 1:8">
                    <span class="invalid-feedback error_type_plc" style="font-size: .8rem; font-weight: 500;"></span>
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
    const BASE = "{{ route('plc.index') }}";

    let table;

    $(function () {
        table = $('#plc-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: BASE,
            order: [[4, 'desc']], // Kolom 'Created At' default ordering
            pageLength: 10,
            dom: 'rt', // Menghilangkan default filter dan info
            language: {
                emptyTable: "Belum ada master data PLC Box.",
                zeroRecords: "Pencarian tidak ditemukan."
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'serial_number', render: data => `<div style="font-family: monospace; font-weight: bold; padding: 4px 8px; background: #f1f5f9; border-radius: 6px; display: inline-block;">${data}</div>` },
                { data: 'name', className: 'fw-bold text-dark' },
                { data: 'type', render: data => `<span class="badge" style="background: #eef2ff; color: #6366f1; padding: .35rem .6rem; border-radius: 6px; font-size: .75rem; font-weight: 700;">${data}</span>` },
                { data: 'created_at', render: data => moment(data).format('DD/MM/YYYY - HH:mm') },
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

    function resetValidation() {
        $(".is-invalid").removeClass("is-invalid");
        $(".invalid-feedback").text("");
    }

    $("#addBtn").click(function(){
        $(".modal-title").text("Tambah PLC Box");
        $("#serial_number, #name, #type_plc, #plc-id").val('');
        resetValidation();
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
        $("#btn-text").addClass('d-none');

        resetValidation();

        $.ajax({
            url: url,
            method: method,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
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
                    Toast.fire({icon: "success", title: "Berhasil menyimpan PLC Box."});
                    table.ajax.reload();
                }
            },
            complete: function(){
                $("#saveBtn").prop('disabled', false);
                $("#btnLoading").addClass('d-none');
                $("#btn-text").removeClass('d-none');
            }
        });
    });

    function editModal(id){
        $.get(BASE+'/'+id+'/show', function(res){
            let data = res.data;
            $(".modal-title").text("Edit PLC Box");
            resetValidation();
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
            title: "Hapus PLC Box?",
            text: "Apakah anda yakin ingin menghapus data PLC Box ini secara permanen?",
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
                        Toast.fire({icon:"success", title:"Berhasil menghapus PLC Box."});
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

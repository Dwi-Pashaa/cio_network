@extends('layouts.app')

@section('title')
    Data Halaman Perbaikan/Pergantian Perangkat
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="card">
    @can('buat halaman')
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
                    <select name="sort" id="sort" class="form-control">
                        @php
                            $opts = [
                                10,25,50,100
                            ];
                        @endphp 
                        @foreach ($opts as $opt)
                            <option value="{{ $opt }}" {{ request('sort') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="ms-auto text-secondary">
                <form>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="search" placeholder="Search for…">
                        <button class="btn" type="submit">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="advanced-table" class="table-responsive-lg">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th class="w-1">No</th>
                    <th><button class="table-sort" data-sort="sort-name">Nama</button></th>
                    <th><button class="table-sort" data-sort="sort-telp">No Telephone</button></th>
                    <th><button class="table-sort" data-sort="sort-pass">Kunci</button></th>
                    <th><button class="table-sort" data-sort="sort-created">Created</button></th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="table-tbody list">
                @forelse ($pages as $item)
                    <tr>
                        <td><span class="text-secondary">{{ $loop->iteration }}</span></td>
                        <td class="sort-name">{{ $item->name }}</td>
                        <td class="sort-telp">{{ $item->telp }}</td>
                        <td class="sort-pass">{{ $item->password_show }}</td>
                        <td class="sort-created">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}</td>
                        <td>
                            @can('lihat halaman')
                                <a href="{{ route('switch.data.index', ['slug' => $item->slug]) }}" target="_blank" class="btn btn-outline-info btn-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                    </svg>
                                    Lihat Halaman
                                </a>
                            @endcan
                            @can('edit halaman')
                                <a href="javascript:void(0)" onclick="return editModal('{{ $item->id }}')" class="btn btn-outline-warning btn-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                        <path d="M16 5l3 3"/>
                                    </svg>
                                    Edit
                                </a>
                            @endcan
                            @can('hapus halaman')
                                <a href="javascript:void(0)" onclick="return deleteType('{{ $item->id }}')" class="btn btn-outline-danger btn-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/>
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                    </svg>
                                    Hapus
                                </a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $pages->firstItem() }}</span> 
            to <span>{{ $pages->lastItem() }}</span> of
            <span>{{ $pages->total() }}</span> entries
        </p>
        <ul class="pagination m-0 ms-auto">
            {{ $pages->links() }}
        </ul>
    </div>
</div>
@endsection

@push('modal')
<div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-1 modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Halaman Pergantian Perangkat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="type" id="type">
                <input type="hidden" name="id" id="id">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="name" class="mb-2">Judul Halaman</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="invalid-feedback error_name"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="desc" class="mb-2">Sub Judul Halaman</label>
                            <input type="text" name="desc" id="desc" class="form-control">
                            <span class="invalid-feedback error_desc"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="regencies_id" class="mb-2">Kabupaten/Kota</label>
                            <select name="regencies_id" id="regencies_id" class="form-control">
                                <option value="">Pilih</option>
                                @foreach ($regencies as $rgs)
                                    <option value="{{ $rgs->id }}">{{ $rgs->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_regencies_id"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="districts_id" class="mb-2">Kecamatan</label>
                            <select name="districts_id" id="districts_id" class="form-control">
                                <option value="">Pilih</option>
                                @foreach ($districts as $dst)
                                    <option value="{{ $dst->id }}">{{ $dst->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_districts_id"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="hometowns_id" class="mb-2">Kampung</label>
                            <select name="hometowns_id" id="hometowns_id" class="form-control">
                                <option value="">Pilih</option>
                                @foreach ($hometown as $ht)
                                    <option value="{{ $ht->id }}">{{ $ht->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_hometowns_id"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="villages_id" class="mb-2">Desa</label>
                            <select name="villages_id" id="villages_id" class="form-control">
                                <option value="">Pilih</option>
                                @foreach ($villages as $vlg)
                                    <option value="{{ $vlg->id }}">{{ $vlg->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_villages_id"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="telp" class="mb-2">No Telephone</label>
                    <input type="text" name="telp" id="telp" class="form-control">
                    <span class="invalid-feedback error_telp"></span>
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="mb-2">Kunci Halaman</label>
                    <input type="text" name="password" id="password" class="form-control">
                    <span class="invalid-feedback error_password"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="storeBtn" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const BASE = "{{ route('switch.index') }}";

        let params = new URLSearchParams(window.location.search);
        $("#sort").change(function() {
            params.set('sort', $(this).val());
            window.location.href = BASE + '?' + params.toString();
        });

        document.addEventListener("DOMContentLoaded", function () {
            const advancedTable = {
                headers: [
                    { "data-sort": "sort-name", name: "Nama" },
                    { "data-sort": "sort-telp", name: "No Telephone" },
                    { "data-sort": "sort-pass", name: "Kunci" },
                    { "data-sort": "sort-created", name: "Created" },
                ],
            };

            window.tabler_list = window.tabler_list || {};
            const list = (window.tabler_list["advanced-table"] = new List("advanced-table", {
                sortClass: "table-sort",
                listClass: "table-tbody",
                searchClass: "search",
                page: 10,
                pagination: true,
                valueNames: advancedTable.headers.map(h => h["data-sort"]),
            }));
        });

        $('#regencies_id').select2({
            width: '100%',
            dropdownParent: $('#modal-simple')
        });

        $('#districts_id').select2({
            width: '100%',
            dropdownParent: $('#modal-simple')
        });

        $('#hometowns_id').select2({
            width: '100%',
            dropdownParent: $('#modal-simple')
        });

        $('#villages_id').select2({
            width: '100%',
            dropdownParent: $('#modal-simple')
        });

        $('#routers_id').select2({
            width: '100%',
            dropdownParent: $('#modal-simple')
        });

        $('#vlans_id').select2({
            width: '100%',
            dropdownParent: $('#modal-simple')
        });

        $('#odcs_id').select2({
            width: '100%',
            dropdownParent: $('#modal-simple')
        });

        $('#odps_id').select2({
            width: '100%',
            dropdownParent: $('#modal-simple')
        });

        $('#olts_id').select2({
            width: '100%',
            dropdownParent: $('#modal-simple')
        });


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
            $(".modal-title").html("Tambah Halaman Perbaikan/Pergantian Perangkat");
            $("#name").val("");
            $("#type").val("create");
            $("#id").val("");
        });

        $('#storeBtn').click(function(e) {
            e.preventDefault();
            
            let formData = new FormData();
            let id = $('#id').val();

            let method = id ? 'PUT' : 'POST';

            formData.append('name', $('#name').val());
            formData.append('desc', $('#desc').val());
            formData.append('regencies_id', $('#regencies_id').val());
            formData.append('districts_id', $('#districts_id').val());
            formData.append('hometowns_id', $('#hometowns_id').val());
            formData.append('villages_id', $('#villages_id').val());
            formData.append('telp', $('#telp').val());
            formData.append('password', $('#password').val());

            if (id) {
                formData.append('_method', 'PUT');
            }

            let url = id ? `${BASE}/${id}/update` : "{{ route('switch.store') }}";

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#storeBtn').attr('disabled', true).text('Menyimpan...');
                },
                success: function(response) {
                    if (response.errors) {
                        $.each(response.errors, function(index, value) {
                            $("#" + index).addClass('is-invalid');
                            $(".error_" + index).html(value);

                            setTimeout(() => {
                                $("#" + index).removeClass('is-invalid');
                                $(".error_" + index).html('');
                            }, 3000);
                        })                
                    } else {
                        $("#modal-simple").modal('hide')
                        Toast.fire({
                            icon: response.status,
                            title: response.message
                        });

                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(xhr) {
                    let res = xhr.responseJSON;
                    if (res.errors) {
                        $.each(res.errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid');
                            $(`.error_${key}`).text(value[0]).show();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan, coba lagi!'
                        });
                    }
                },
                complete: function() {
                    $('#storeBtn').attr('disabled', false).text('Simpan');
                }
            });
        });

        function editModal(id) {
            let url = BASE + `/${id}/show`
            $.ajax({
                url: url,
                method: "GET",
                dataType: "json"
            }).done(function(response){
                $(".modal-title").html("Edit Halaman Perbaikan/Pergantian Perangkat");
                let data = response.data;
                $("#modal-simple").modal('show')

                $("#id").val(data.id);
                $("#name").val(data.name);
                $("#telp").val(data.telp);
                $("#desc").val(data.desc);
                $("#password").val(data.password_show);
                $("#type").val("update");

                $("#regencies_id").val(data.regencies_id).trigger('change');
                $("#districts_id").val(data.districts_id).trigger('change');
                $("#hometowns_id").val(data.hometowns_id).trigger('change');
                $("#villages_id").val(data.villages_id).trigger('change');

                $.each(response.data.router, function(index, value) {
                    let selectedRouters = $("#routers_id").val() || []; 
                    selectedRouters.push(value.routers_id); 
                    $("#routers_id").val(selectedRouters).trigger('change'); 
                });

                $.each(response.data.vlan, function(index, value) {
                    let selectedVlans = $("#vlans_id").val() || [];
                    selectedVlans.push(value.vlans_id);
                    $("#vlans_id").val(selectedVlans).trigger('change');
                });

                $.each(response.data.odc, function(index, value) {
                    let selectedOdcs = $("#odcs_id").val() || [];
                    selectedOdcs.push(value.odcs_id);
                    $("#odcs_id").val(selectedOdcs).trigger('change');
                });

                $.each(response.data.odp, function(index, value) {
                    let selectedOdps = $("#odps_id").val() || [];
                    selectedOdps.push(value.odps_id);
                    $("#odps_id").val(selectedOdps).trigger('change');
                });

                $.each(response.data.olt, function(index, value) {
                    let selectedolts = $("#olts_id").val() || [];
                    selectedolts.push(value.olts_id);
                    $("#olts_id").val(selectedolts).trigger('change');
                });
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
            });
        }

        function deleteType(id) {
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
                                window.location.reload();
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
@extends('layouts.app')

@section('title')
    Data Mic Radius
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="card">
    @can('buat mic radius')
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
    <div id="advanced-table" class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th class="w-1">No</th>
                    <th>
                        <button class="table-sort" data-sort="sort-code">Code</button>
                    </th>
                    <th>
                        <button class="table-sort" data-sort="sort-name">Nama Mic Radius</button>
                    </th>
                    <th>
                        <button class="table-sort" data-sort="sort-home">Kampung</button>
                    </th>
                    <th>
                        <button class="table-sort" data-sort="sort-home">User</button>
                    </th>
                    <th>Lokasi</th>
                    <th>
                        <button class="table-sort" data-sort="sort-created">Created</button>
                    </th>
                    @if(auth()->user()->can('ubah mic radius') || auth()->user()->can('hapus mic radius'))
                        <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody class="table-tbody">
                @forelse ($micRadius as $item)
                    <tr>
                         <td>{{ $loop->iteration }}</td>
                        <td class="sort-code">{{ $item->code }}</td>
                        <td class="sort-name">{{ $item->name }}</td>
                        <td class="sort-home">{{ $item->hometown->name }}</td>
                        <td class="sort-home">
                            @forelse ($item->user as $usr)
                                <span class="badge bg-primary text-white p-1">
                                    {{ $usr->name }}
                                </span>
                            @empty
                                -
                            @endforelse
                        </td>
                        <td>
                            <a href="https://www.google.com/maps?q={{ $item->latitude }},{{ $item->longitude }}" 
                            target="_blank" class="btn btn-primary btn-sm">Lihat Lokasi</a>
                        </td>
                        <td class="sort-created">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}</td>
                        @if(auth()->user()->can('edit mic radius') || auth()->user()->can('hapus mic radius'))
                            <td>
                                @can('edit mic radius')
                                    <a href="javascript:void(0)" onclick="return editModal('{{ $item->id }}')" class="btn btn-outline-warning btn-md">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                        Edit
                                    </a>
                                @endcan
                                @can('hapus mic radius')
                                    <a href="javascript:void(0)" onclick="return deleteType('{{ $item->id }}')" class="btn btn-outline-danger btn-md">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                        Hapus
                                    </a>
                                @endcan
                            </td> 
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $micRadius->firstItem() }}</span> 
            to <span>{{ $micRadius->lastItem() }}</span> of
            <span>{{ $micRadius->total() }}</span> entries
        </p>
        <ul class="pagination m-0 ms-auto">
            {{ $micRadius->links() }}
        </ul>
    </div>
</div>
@endsection

@push('modal')
<div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mic Radius</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="type" id="type">
                <input type="hidden" name="id" id="id">
                <div class="form-group mb-3">
                    <label for="name" class="mb-2">Pilih User</label>
                    <select name="user_id[]" id="user_id" class="form-control" multiple>
                        <option value="">Pilih</option>
                        @foreach ($user as $usr)
                            <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback error_name"></span>
                </div>
                <div class="form-group mb-3">
                    <label for="code" class="mb-2">Kode Mic Radius</label>
                    <input type="text" name="code" id="code" class="form-control">
                    <span class="invalid-feedback error_code"></span>
                </div>
                <div class="form-group mb-3">
                    <label for="name" class="mb-2">Nama Mic Radius</label>
                    <input type="text" name="name" id="name" class="form-control">
                    <span class="invalid-feedback error_name"></span>
                </div>
                <div class="form-group mb-3">
                    <label for="hometowns_id" class="mb-2">Kampung</label>
                    <select name="hometowns_id" id="hometowns_id" class="form-control">
                        <option value="">Pilih</option>
                        @foreach ($hometown as $hmt)
                            <option value="{{ $hmt->id }}">{{ $hmt->name }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback error_hometowns_id"></span>
                </div>
                <div class="mt-3" id="map-container" style="display:none;">
                    <iframe id="map-frame"
                        width="100%" 
                        height="300" 
                        style="border:0; border-radius: 10px;"
                        loading="lazy" 
                        allowfullscreen 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <input type="hidden" name="latitude" id="latitude" class="form-control">
                <input type="hidden" name="longitude" id="longitude" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="storeBtn" class="btn btn-primary">
                    <span id="btnText">Simpan</span>
                    <span id="btnLoading" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // ========= SORT TABLE (List.js + Tabler) =========
    const advancedTable = {
        headers: [
            { "data-sort": "sort-code", name: "Code" },
            { "data-sort": "sort-name", name: "Nama Mic Radius" },
            { "data-sort": "sort-home", name: "Kampung" },
            { "data-sort": "sort-created", name: "Created" },
        ],
    };

    window.tabler_list = window.tabler_list || {};

    document.addEventListener("DOMContentLoaded", function () {
        const list = (window.tabler_list["advanced-table"] = new List("advanced-table", {
            sortClass: "table-sort",
            listClass: "table-tbody",
            page: parseInt("{{ request('sort', 10) }}"),
            pagination: true,
            valueNames: advancedTable.headers.map(header => header["data-sort"]),
        }));
    });
</script>
<script>
    const BASE = "{{ route('mic.radius.index') }}";

    let params = new URLSearchParams(window.location.search);
    $("#sort").change(function() {
        params.set('sort', $(this).val());
        window.location.href = BASE + '?' + params.toString();
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

    $('#user_id').select2({
        width: '100%',
        dropdownParent: $('#modal-simple')
    });

    $("#addBtn").click(function() {
        $(".modal-title").html("Tambah Mic Radius");
        $("#user_id").val("");
        $("#code").val("");
        $("#name").val("");
        $("#type").val("create");
        $("#id").val("");
    });

    $("#storeBtn").click(function () {
        $("#storeBtn").prop("disabled", true);
        $("#btnText").addClass("d-none");
        $("#btnLoading").removeClass("d-none");

        let id = $("#id").val();
        let type = $("#type").val();

        let url;
        let method = "POST";

        if (type === "create") {
            url = BASE + "/store";
        } else {
            url = BASE + `/${id}/update`;
        }

        let formData = new FormData();

        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
        formData.append("_method", type === "create" ? "POST" : "PUT");

        formData.append("name", $("#name").val());
        formData.append("code", $("#code").val());
        formData.append("hometowns_id", $("#hometowns_id").val());
        formData.append("latitude", $("#latitude").val());
        formData.append("longitude", $("#longitude").val());

        let users = $("#user_id").val() || [];
        users.forEach(u => formData.append("user_id[]", u));

        $.ajax({
            url: url,
            method: method,
            data: formData,
            processData: false,
            contentType: false,
        })
        .done(function (response) {

            if (response.errors) {

                resetBtn();

                $.each(response.errors, function (index, value) {
                    $("#" + index).addClass("is-invalid");
                    $(".error_" + index).html(value);

                    setTimeout(() => {
                        $("#" + index).removeClass("is-invalid");
                        $(".error_" + index).html("");
                    }, 3000);
                });

            } else {
                $("#modal-simple").modal("hide");

                Toast.fire({
                    icon: response.status,
                    title: response.message,
                });

                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            resetBtn();
            console.log("Error:", textStatus, errorThrown);
        });

        function resetBtn() {
            $("#storeBtn").prop("disabled", false);
            $("#btnText").removeClass("d-none");
            $("#btnLoading").addClass("d-none");
        }
    });

    function editModal(id) {
        let url = BASE + `/${id}/show`
        $.ajax({
            url: url,
            method: "GET",
            dataType: "json"
        }).done(function(response){
            $(".modal-title").html("Edit Mic Radius");
            let data = response.data;
            
            $("#modal-simple").modal('show')

            $("#id").val(data.id);
            $("#code").val(data.code);
            $("#name").val(data.name);
            $("#hometowns_id").val(data.hometowns_id);

            let selectedUsers = data.user.map(u => u.id);   // ambil id user yang sudah terhubung
            $("#user_id").val(selectedUsers).trigger("change");
            $("#type").val("update");
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    let latitude = position.coords.latitude;
                    let longitude = position.coords.longitude;

                    document.getElementById("latitude").value = latitude;
                    document.getElementById("longitude").value = longitude;

                    document.getElementById("map-container").style.display = "block";
                    document.getElementById("map-frame").src =
                        `https://www.google.com/maps?q=${latitude},${longitude}&hl=id&z=15&output=embed`;

                },
                function(error) {
                    alert("Error mendapatkan lokasi");
                    console.error("Error mendapatkan lokasi:", error.message);
                }
            );
        } else {
            console.error("Browser tidak mendukung geolocation.");
            alert("Error mendapatkan lokasi");
        }
    });
</script>
@endpush
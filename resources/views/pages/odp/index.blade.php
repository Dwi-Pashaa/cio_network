@extends('layouts.app')

@section('title')
    Data ODP
@endsection

@push('css')
    
@endpush

@section('content')
<div class="card">
    @can('buat odp')
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
    <div id="advanced-table">
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th class="w-1">No</th>
                        <th>
                            <button class="table-sort desc" data-sort="sort-code">Code</button>
                        </th>
                        <th>
                            <button class="table-sort desc" data-sort="sort-name">Nama Pemilik</button>
                        </th>
                        <th>
                            <button class="table-sort desc" data-sort="sort-plc">PLC</button>
                        </th>
                        <th>
                            <button class="table-sort desc" data-sort="sort-patch-core">Patch Core</button>
                        </th>
                        <th>
                            <button class="table-sort desc" data-sort="sort-home">Kampung</button>
                        </th>
                        <th>
                            <button class="table-sort desc" data-sort="sort-rt">RT</button>
                        </th>
                        <th>
                            <button class="table-sort desc" data-sort="sort-rw">RW</button>
                        </th>
                        <th>Lokasi</th>
                        <th>
                            <button class="table-sort desc" data-sort="sort-created">Created</button>
                        </th>
                        @if(auth()->user()->can('ubah odp') || auth()->user()->can('hapus odp'))
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="table-tbody">
                    @forelse ($odps as $item)
                        <tr>
                            <td><span class="text-secondary">{{ $loop->iteration }}</span></td>
                            <td class="sort-code">{{ $item->code }}</td>
                            <td class="sort-name">{{ $item->home_odc }}</td>
                            <td class="sort-plc">{{ optional($item)->plc->name ?? '-' }}</td>
                            <td class="sort-patch-core">{{ optional($item)->patchCore->name ?? '-' }}</td>
                            <td class="sort-home">{{ $item->hometown->name }}</td>
                            <td class="sort-rt">{{ $item->rt->name }}</td>
                            <td class="sort-rw">{{ $item->rw->name }}</td>
                            <td>
                                <a href="https://www.google.com/maps?q={{ $item->latitude }},{{ $item->longitude }}" 
                                   target="_blank" class="btn btn-primary btn-sm">Lihat Lokasi</a>
                            </td>
                            <td class="sort-created">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}
                            </td>
                            @if(auth()->user()->can('ubah odp') || auth()->user()->can('hapus odp'))
                                <td>
                                    @can('ubah odp')
                                        <a href="javascript:void(0)" onclick="return editModal('{{ $item->id }}')" class="btn btn-outline-warning btn-md">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>
                                    @endcan
                                    @can('hapus odp')
                                        <a href="javascript:void(0)" onclick="return deleteType('{{ $item->id }}')" class="btn btn-outline-danger btn-md">
                                            <i class="ti ti-trash"></i> Hapus
                                        </a>
                                    @endcan
                                </td> 
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">Tidak Ada Data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $odps->firstItem() }}</span> 
            to <span>{{ $odps->lastItem() }}</span> of
            <span>{{ $odps->total() }}</span> entries
        </p>
        <ul class="pagination m-0 ms-auto">
            {{ $odps->links() }}
        </ul>
    </div>
</div>
@endsection

@push('modal')
<div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-1 modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah ODP</h5>
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
                            <label for="plc" class="mb-2">Pilih PLC</label>
                            <select name="plc_id" id="plc_id" class="form-control">
                                <option value="">Pilih</option>
                                @foreach ($plcs as $plc)
                                    <option value="{{ $plc->id }}">{{ $plc->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_plc_id"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="patch_core_id" class="mb-2">Pilih Patch Core</label>
                            <select name="patch_core_id" id="patch_core_id" class="form-control">
                                <option value="">Pilih</option>
                                @foreach ($patchCores as $pc)
                                    <option value="{{ $pc->id }}">{{ $pc->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_patch_core_id"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="code" class="mb-2">Kode ODP</label>
                            <input type="text" name="code" id="code" class="form-control">
                            <span class="invalid-feedback error_code"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="home_odc" class="mb-2">Nama Pemilik</label>
                            <input type="text" name="home_odc" id="home_odc" class="form-control">
                            <span class="invalid-feedback error_home_odc"></span>
                        </div>
                    </div>
                    <div class="col-lg-12">
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
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="rts_id" class="mb-2">RT</label>
                            <select name="rts_id" id="rts_id" class="form-control">
                                <option value="">Pilih</option>
                                @foreach ($rts as $rt)
                                    <option value="{{ $rt->id }}">{{ $rt->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_rts_id"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="rws_id" class="mb-2">RW</label>
                            <select name="rws_id" id="rws_id" class="form-control">
                                <option value="">Pilih</option>
                                @foreach ($rws as $rw)
                                    <option value="{{ $rw->id }}">{{ $rw->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_rws_id"></span>
                        </div>
                    </div>
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
<script>
    // ======== SORT TABLE ========
    const advancedTable = {
        headers: [
            { "data-sort": "sort-code", name: "Code" },
            { "data-sort": "sort-name", name: "Nama Pemilik" },
            { "data-sort": "sort-plc", name: "PLC" },
            { "data-sort": "sort-patch-core", name: "Patch Core" },
            { "data-sort": "sort-home", name: "Kampung" },
            { "data-sort": "sort-rt", name: "RT" },
            { "data-sort": "sort-rw", name: "RW" },
            { "data-sort": "sort-created", name: "Created" },
        ],
    };
    const setPageListItems = (e) => {
        window.tabler_list["advanced-table"].page = parseInt(e.target.dataset.value);
        window.tabler_list["advanced-table"].update();
        document.querySelector("#page-count").innerHTML = e.target.dataset.value;
    };
    window.tabler_list = window.tabler_list || {};
    document.addEventListener("DOMContentLoaded", function () {
        const list = (window.tabler_list["advanced-table"] = new List("advanced-table", {
            sortClass: "table-sort",
            listClass: "table-tbody",
            page: parseInt("20"),
            pagination: {
                item: (value) => `<li class='page-item'><a class='page-link cursor-pointer'>${value.page}</a></li>`,
                innerWindow: 1,
                outerWindow: 1,
            },
            valueNames: advancedTable.headers.map((header) => header["data-sort"]),
        }));
    });
</script>
<script>
    const BASE = "{{ route('odp.index') }}";

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

    $("#addBtn").click(function() {
        $(".modal-title").html("Tambah ODP");
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
        let code = $("#code").val();
        let home_odc = $("#home_odc").val();
        let plc_id = $("#plc_id").val();
        let patch_core_id = $("#patch_core_id").val();
        let hometowns_id = $("#hometowns_id").val();
        let rts_id = $("#rts_id").val();
        let rws_id = $("#rws_id").val();
        let latitude = $("#latitude").val();
        let longitude = $("#longitude").val();

        let url;
        let method;

        if (type === 'create') {
            url = BASE + '/store';
            method = "POST";
        } else {
            url = BASE + `/${id}/update`;
            method = "PUT";
        }

        $.ajax({
            url: url,
            method: method,
            data: {
                code: code,
                home_odc: home_odc,
                plc_id: plc_id,
                patch_core_id: patch_core_id,
                hometowns_id: hometowns_id,
                rts_id: rts_id,
                rws_id: rws_id,
                latitude: latitude,
                longitude: longitude,
            },
        })
        .done(function (response) {
            if (response.errors) {
                resetBtn();

                $.each(response.errors, function (index, value) {
                    $("#" + index).addClass('is-invalid');
                    $(".error_" + index).html(value);

                    setTimeout(() => {
                        $("#" + index).removeClass('is-invalid');
                        $(".error_" + index).html('');
                    }, 3000);
                });
            } else {
                $("#modal-simple").modal('hide');

                Toast.fire({
                    icon: response.status,
                    title: response.message
                });

                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }
        })
        .fail(function () {
            resetBtn();
            console.log("Server Error");
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
            $(".modal-title").html("Edit ODP");
            let data = response.data;
            $("#modal-simple").modal('show')

            $("#id").val(data.id);
            $("#code").val(data.code);
            $("#home_odc").val(data.home_odc);
            $("#plc_id").val(data.plc_id);
            $("#patch_core_id").val(data.patch_core_id);
            $("#hometowns_id").val(data.hometowns_id);
            $("#rts_id").val(data.rts_id);
            $("#rws_id").val(data.rws_id);
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
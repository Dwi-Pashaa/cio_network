@extends('layouts.app')

@section('title')
    Data Tipe Paket
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple" class="btn btn-primary">
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
            Tambah
        </a>
    </div>
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
                            <button class="table-sort d-flex justify-content-between desc" data-sort="sort-name">
                                Tipe Paket
                            </button>
                        </th>
                        <th>
                            <button class="table-sort d-flex justify-content-between desc" data-sort="sort-name">
                                User
                            </button>
                        </th>
                        <th>
                            <button class="table-sort d-flex justify-content-between desc" data-sort="sort-created">
                                Created
                            </button>
                        </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-tbody">
                    @forelse ($type as $item)
                        <tr>
                            <td>
                                <span class="text-secondary">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td class="sort-name">
                                <a href="#" class="text-reset" tabindex="-1">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td class="sort-name">
                                @forelse ($item->user as $usr)
                                    <span class="badge bg-primary text-white p-1">
                                        {{ $usr->name }}
                                    </span>
                                @empty
                                    -
                                @endforelse
                            </td>
                            <td class="sort-created">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i:s') }}
                            </td>
                            <td>
                                <a href="javascript:void(0)" onclick="return editModal('{{ $item->id }}')" class="btn btn-outline-warning btn-md">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                    Edit
                                </a>
                                <a href="javascript:void(0)" onclick="return deleteType('{{ $item->id }}')" class="btn btn-outline-danger btn-md">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                    Hapus
                                </a>
                            </td> 
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak Ada Data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $type->firstItem() }}</span> 
            to <span>{{ $type->lastItem() }}</span> of
            <span>{{ $type->total() }}</span> entries
        </p>
        <ul class="pagination m-0 ms-auto">
            {{ $type->links() }}
        </ul>
    </div>
</div>
@endsection

@push('modal')
<div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tipe Paket</h5>
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
                    <label for="name" class="mb-2">Tipe Paket</label>
                    <input type="text" name="name" id="name" class="form-control">
                    <span class="invalid-feedback error_name"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="storeBtn" class="btn btn-primary">
                    <span class="btn-text">Simpan</span>
                    <span class="spinner-border spinner-border-sm d-none ms-2" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const advancedTable = {
        headers: [
            { "data-sort": "sort-name", name: "Tipe Paket" },
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
                item: (value) => {
                    return `<li class="page-item"><a class="page-link cursor-pointer">${value.page}</a></li>`;
                },
                innerWindow: 1,
                outerWindow: 1,
                left: 0,
                right: 0,
            },
            valueNames: advancedTable.headers.map((header) => header["data-sort"]),
        }));
    });
</script>
<script>
    const BASE = "{{ route('paket.index') }}";

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
        $(".modal-title").html("Tambah Tipe Paket");
        $("#user").val("");
        $("#name").val("");
        $("#type").val("create");
        $("#id").val("");
    });

    $('#user_id').select2({
        width: '100%',
        dropdownParent: $('#modal-simple')
    });

    $("#storeBtn").click(function () {
        const btn     = $("#storeBtn");
        const btnText = btn.find(".btn-text");
        const spinner = btn.find(".spinner-border");

        btn.prop("disabled", true);
        btnText.text("Menyimpan...");
        spinner.removeClass("d-none");

        let id   = $("#id").val();
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
                    title: response.message
                });

                setTimeout(() => window.location.reload(), 1500);
            }
        })
        .fail(function (jqXHR) {
            console.log("Error:", jqXHR.responseText);
            resetBtn();
        });

        function resetBtn() {
            btn.prop("disabled", false);
            btnText.text("Simpan");
            spinner.addClass("d-none");
        }
    });

    function editModal(id) {
        let url = BASE + `/${id}/show`;

        $.ajax({
            url: url,
            method: "GET",
            dataType: "json"
        }).done(function(response){

            $(".modal-title").html("Edit Tipe Paket");

            let data = response.data;

            $("#modal-simple").modal('show');

            $("#id").val(data.id);
            $("#name").val(data.name);
            $("#type").val("update");

            // --- tampilkan relasi users ---
            let selectedUsers = data.user.map(u => u.id);   // ambil id user yang sudah terhubung
            $("#user_id").val(selectedUsers).trigger("change");
            // (trigger change perlu jika pakai select2)

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
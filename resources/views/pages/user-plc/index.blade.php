@extends('layouts.app')

@section('title')
    Data Stock PLC
@endsection

@push('css')
    
@endpush

@section('content')
<div class="card">
    @can('buat barang')
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
                            <button class="table-sort d-flex justify-content-between desc" data-sort="sort-name">Nama User</button>
                        </th>
                        <th>
                            <button class="table-sort d-flex justify-content-between desc" data-sort="sort-plc">Nama PLC</button>
                        </th>
                        <th>
                            <button class="table-sort d-flex justify-content-between desc" data-sort="sort-total">Jumlah</button>
                        </th>
                        <th>
                            <button class="table-sort d-flex justify-content-between desc" data-sort="sort-created">Created At</button>
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="table-tbody">
                    @forelse ($userPLC as $item)
                        <tr>
                            <td>
                                <span class="text-secondary">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td class="sort-name">
                                <a href="#" class="text-reset" tabindex="-1">
                                    {{ $item->user->name ?? '-' }}
                                </a>
                            </td>
                            <td class="sort-plc">
                                <a href="#" class="text-reset" tabindex="-1">
                                    {{ $item->plc->name ?? '-' }}
                                </a>
                            </td>
                            <td class="sort-total">
                                <a href="#" class="text-reset" tabindex="-1">
                                    {{ $item->total }}
                                </a>
                            </td>
                            <td class="sort-created">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}
                            </td>
                            <td>
                                @can('tambah stock')
                                    <a href="javascript:void(0)" onclick="return addStock('{{ $item->id }}')" class="btn btn-outline-primary btn-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-unsplash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 11h5v4h6v-4h5v9h-16zm5 -7h6v4h-6z" /></svg>
                                        Tambah Stock
                                    </a>
                                @endcan
                                @can('edit barang')
                                    <a href="javascript:void(0)" onclick="return editModal('{{ $item->id }}')" class="btn btn-outline-warning btn-md">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                        Edit
                                    </a>
                                @endcan
                                @can('hapus barang')
                                    <a href="javascript:void(0)" onclick="return deleteType('{{ $item->id }}')" class="btn btn-outline-danger btn-md">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
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
    </div>
    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $userPLC->firstItem() ?? 0 }}</span> 
            to <span>{{ $userPLC->lastItem() ?? 0 }}</span> of
            <span>{{ $userPLC->total() }}</span> entries
        </p>
        <ul class="pagination m-0 ms-auto">
            {{ $userPLC->links() }}
        </ul>
    </div>
</div>
@endsection

@push('modal')
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Stock PLC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group mb-3" id="role_id_show">
                        <label for="role" class="mb-2">Pilih Level</label>
                        <select name="role" id="role" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($role as $rl)
                                <option value="{{ $rl->name }}">{{ $rl->name }}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback error_role"></span>
                    </div>
                    <div class="form-group mb-3" id="user_id_show" style="display: none;">
                        <label for="user_id" class="mb-2">Pilih User</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">Pilih</option>
                        </select>
                        <span class="invalid-feedback error_user_id"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="plc_id" class="mb-2">Pilih PLC</label>
                        <select name="plc_id" id="plc_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($plc as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback error_plc_id"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="total" class="mb-2">Jumlah PLC</label>
                        <input type="number" name="total" id="total" class="form-control" min="1">
                        <span class="invalid-feedback error_total"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary">
                        <span class="btn-text">Simpan</span>
                        <span class="btn-loading spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal-add-stock" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Stock PLC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_plc_id" id="user_plc_id">
                    <div class="form-group mb-3">
                        <label for="user" class="mb-2">User</label>
                        <input type="text" name="user" id="user" class="form-control" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="total_stock" class="mb-2">Jumlah PLC</label>
                        <input type="number" name="total_stock" id="total_stock" class="form-control" min="1">
                        <span class="invalid-feedback error_total_stock"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeAddStock" class="btn btn-primary">
                        <span id="btn-text">Simpan</span>
                        <span id="btn-loading" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
<script>
    const advancedTable = {
        headers: [
            { "data-sort": "sort-name", name: "Nama User" },
            { "data-sort": "sort-plc", name: "Nama PLC" },
            { "data-sort": "sort-total", name: "Jumlah" },
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
    const BASE = "{{ route('user.plc.index') }}";

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

    $("#role").change(function() {
        let role = $(this).val();

        $.ajax({
            url: BASE + '/get-role', 
            method: "POST",
            data: {
                role: role,
                _token: $('meta[name="csrf-token"]').attr('content') 
            },
            success: function(response) {
                console.log(response); 
                let html = '<option value="">Pilih</option>';

                if (response.code == 200) {
                    $("#user_id_show").removeClass('d-none').addClass('d-block');
                    $.each(response.data, function(index, value) {
                        html += `<option value="${value.id}">${value.name}</option>`;
                    })
                } else {
                    $("#user_id_show").removeClass('d-block').addClass('d-none');             
                }

                $("#user_id").html(html)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                Toast.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan saat mengambil data user'
                });
            }
        });
    });

    $("#addBtn").click(function() {
        $(".modal-title").html("Tambah Stock PLC");
        $("#role").val("");
        $("#user_id").val("");
        $("#plc_id").val("");
        $("#total").val("");
        $("#type").val("create");
        $("#id").val("");
        $("#user_id_show").removeClass('d-block').addClass('d-none');
        $("#role_id_show").removeClass('d-none').addClass('d-block');
    });

    $("#storeBtn").on("click", function () {
        const btn = $(this);

        if (btn.prop("disabled")) return;

        const btnText = btn.find(".btn-text");
        const btnLoading = btn.find(".btn-loading");

        btn.prop("disabled", true);
        btnText.text("Menyimpan...");
        btnLoading.removeClass("d-none");

        let id = $("#id").val();
        let type = $("#type").val();
        let user_id = $("#user_id").val();
        let plc_id = $("#plc_id").val();
        let total = $("#total").val();
        let role = $("#role").val();

        let url, method;

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
                user_id: user_id,
                plc_id: plc_id,
                total: total,
                role: role,
            },
        })
        .done(function (response) {
            if (response.errors) {
                $.each(response.errors, function (index, value) {
                    $("#" + index).addClass('is-invalid');
                    $(".error_" + index).html(value);

                    setTimeout(() => {
                        $("#" + index).removeClass('is-invalid');
                        $(".error_" + index).html('');
                    }, 3000);
                });

                resetBtn();
            } else {
                $("#modal-simple").modal('hide');
                Toast.fire({
                    icon: response.status,
                    title: response.message
                });

                setTimeout(() => window.location.reload(), 3000);
            }
        })
        .fail(function () {
            console.log("Error request");
            resetBtn();
        });

        function resetBtn() {
            btn.prop("disabled", false);
            btnText.text("Simpan");
            btnLoading.addClass("d-none");
        }
    });

    function editModal(id) {
        let url = BASE + `/${id}/show`;

        $.ajax({
            url: url,
            method: "GET",
            dataType: "json"
        })
        .done(function(response) {
            if (response.code === 200) {
                $(".modal-title").html("Edit Stock PLC");

                let data = response.data;

                $("#modal-simple").modal('show');

                // $("#role_id_show").addClass('d-none').removeClass('d-block');
                // $("#user_id_show").removeClass('d-none').addClass('d-block');

                $("#id").val(data.id);
                $("#user_id").val(data.user_id);
                $("#plc_id").val(data.plc_id);
                $("#total").val(data.total);

                $("#role").val(data.role).trigger('change');

                setTimeout(() => {
                    $("#role").val(data.user.roles[0].name);
                    $("#user_id").val(data.user_id);
                }, 500);

                $("#type").val("update");
            } else {
                Toast.fire({
                    icon: 'error',
                    title: response.message
                });
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error:", textStatus, errorThrown);
            Toast.fire({
                icon: 'error',
                title: 'Terjadi kesalahan saat mengambil data'
            });
        });
    }

    function addStock(id) {
        let url = BASE + `/${id}/show`;

        $.ajax({
            url: url,
            method: "GET",
            dataType: "json"
        })
        .done(function(response) {
            if (response.code === 200) {
                $(".modal-title").html("Tambah Stock PLC");

                let data = response.data;

                $("#modal-add-stock").modal('show');
                
                $("#user_plc_id").val(data.id);
                $("#user").val(data.user.name);
                $("#total_stock").val("");
            } else {
                Toast.fire({
                    icon: 'error',
                    title: response.message
                });
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error:", textStatus, errorThrown);
            Toast.fire({
                icon: 'error',
                title: 'Terjadi kesalahan saat mengambil data'
            });
        });
    }

    $("#storeAddStock").click(function() {
        const btn = $(this);
        const btnText = $("#btn-text");
        const btnLoading = $("#btn-loading");

        // 🔒 cegah double click
        if (btn.prop("disabled")) return;

        // 🔄 loading state
        btn.prop("disabled", true);
        btnText.text("Menyimpan...");
        btnLoading.removeClass("d-none");

        let user_plc_id = $("#user_plc_id").val();
        let total_stock = $("#total_stock").val();

        let url = "{{ route('user.plc.addStore') }}";
        
        $.ajax({
            url: url,
            method: "POST",
            data: {
                user_plc_id: user_plc_id,
                total_stock: total_stock,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
        })
        .done(function(response) {
            if (response.errors) {
                $.each(response.errors, function(index, value) {
                    $("#" + index).addClass('is-invalid');
                    $(".error_" + index).html(value);

                    setTimeout(() => {
                        $("#" + index).removeClass('is-invalid');
                        $(".error_" + index).html('');
                    }, 3000);
                });

                // ❗ jika validasi error → aktifkan lagi tombol
                resetButton();
            } else {
                $("#modal-add-stock").modal('hide');
                Toast.fire({
                    icon: response.status,
                    title: response.message
                });

                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        })
        .fail(function() {
            Toast.fire({
                icon: 'error',
                title: 'Terjadi kesalahan'
            });

            // ❗ jika error server
            resetButton();
        });

        function resetButton() {
            btn.prop("disabled", false);
            btnText.text("Simpan");
            btnLoading.addClass("d-none");
        }
    });

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
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: response.status,
                            title: response.message
                        });

                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
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
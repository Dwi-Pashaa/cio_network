@extends('layouts.app')

@section('title')
    Data Mac Address
@endsection

@push('css')
    
@endpush

@section('content')
    @include('components.alert.success')
    <div class="card">
        <div class="card-header">
            <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple" class="btn btn-primary" class="btn btn-primary">
                Tambah
            </a>
            <a href="{{ route('mac.address.cetakLabel') }}" class="btn btn-danger m-2">
                Cetak Label
            </a>
            @if ($isMacValidationActive)
                <form action="{{ route('mac.address.toggleMacValidation') }}" method="POST">
                    @csrf
                    <input type="hidden" name="value" value="inactive">

                    <button type="submit" class="btn btn-warning">
                        Fitur Off
                    </button>
                </form>
            @else
                <form action="{{ route('mac.address.toggleMacValidation') }}" method="POST">
                    @csrf
                    <input type="hidden" name="value" value="active">

                    <button type="submit" class="btn btn-info">
                        Fitur On
                    </button>
                </form>
            @endif
            @role('Admin')
                <a href="javascript:void(0)" class="btn btn-info m-2" onclick="return switchUsedMac()">
                    Ubah ke Used
                </a>
            @endrole
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
                <form method="GET" action="{{ url()->current() }}" class="d-flex gap-2">

                    @role('Admin')
                        <div class="text-secondary">
                            <select name="user" id="user" class="form-control">
                                <option value="">Tampilkan Semua</option>
                                @foreach ($user as $usr)
                                    <option value="{{ $usr->id }}"
                                        {{ request('user') == $usr->id ? 'selected' : '' }}>
                                        {{ $usr->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endrole

                    <div class="text-secondary">
                        <input
                            type="date"
                            class="form-control"
                            name="date"
                            id="date"
                            value="{{ request('date') }}"
                        >
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary">
                            Cari
                        </button>
                    </div>
                </form>

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
        @if (count($macAdress) != 0)
            <div class="card-body border-bottom py-3">
                <div class="alert alert-primary">
                    <h4>Total Mac Address yang sudah di input : {{ count($macAdress) }}</h4>
                    <p class="mb-0">
                        Jumlah Mac Address Available : <strong>    {{ $macAdress->where('status', 'available')->count() }}</strong>
                    </p>
                    <p>
                        Jumlah Mac Address Used : <strong>    {{ $macAdress->where('status', 'used')->count() }}</strong>
                    </p>
                </div>
            </div>
        @endif
        <div id="advanced-table" class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th class="w-1">No</th>
                        <th>
                            <button class="table-sort" data-sort="sort-mac-address">Mac Address</button>
                        </th>
                        <th>
                            <button class="table-sort" data-sort="sort-router">Router</button>
                        </th>
                        <th>
                            <button class="table-sort" data-sort="sort-status">Status</button>
                        </th>
                        <th>
                            <button class="table-sort" data-sort="sort-status-device">Status Device</button>
                        </th>
                        <th>
                            <button class="table-sort" data-sort="sort-customer">Customer</button>
                        </th>
                        <th>
                            <button class="table-sort" data-sort="sort-user">Di Input</button>
                        </th>
                        <th>
                            <button class="table-sort" data-sort="sort-created">Created At</button>
                        </th>
                        @canany(['edit mac address','hapus mac address'])
                            <th>Action</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody class="table-tbody list">
                    @forelse ($macAdress as $item)
                        <tr>
                            <td>
                                @role("Admin")
                                    <input class="form-check-input row-check" style="margin-right: 20px" type="checkbox" id="checkbox-mac" name="selected[]" value="{{ $item->id }}">
                                @endrole
                                {{ $loop->iteration + $macAdress->firstItem() - 1  }}
                            </td>
                            <td class="sort-mac-address">
                                {{ $item->mac_address }}
                            </td>
                            <td class="sort-router">
                                {{ $item->router->name ?? '-' }}
                            </td>
                            <td class="sort-sort-status">
                                <span class="badge 
                                    bg-{{ 
                                        $item->status === 'used' 
                                            ? 'primary' 
                                            : ($item->status === 'available' 
                                                ? 'success' 
                                                : 'danger') 
                                    }} text-white">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="sort-status-device">
                                <span class="badge bg-{{ $item->status_device === "rusak" ? "danger" : "primary" }} text-white">
                                    {{ ucfirst($item->status_device) }}
                                </span>
                            </td>
                            <td class="sort-customer">
                                @if ($item->customer)
                                    <a href="javascript:void(0)" onclick="return showCustomer({{ $item->id }})" 
                                    class="btn btn-sm btn-info">
                                        Lihat Customer
                                    </a>
                                @else
                                    <i>Mac Address Belum Digunakan</i>
                                @endif
                            </td>
                            <td class="sort-user">
                                {{ $item->user->name ?? '-' }}
                            </td>
                            <td class="sort-created">
                                {{ $item->created_at->format('d/M/Y - H:i:s') }}
                            </td>
                            @canany(['edit mac address','hapus mac address'])
                                <td>
                                    @can('edit mac address')
                                        <a href="javascript:void(0)" onclick="return editModal('{{ $item->id }}')" class="btn btn-outline-warning btn-md">
                                            Edit
                                        </a>
                                    @endcan
                                    @can('hapus mac address')
                                        <a href="javascript:void(0)" onclick="return deleteItem('{{ $item->id }}')" class="btn btn-outline-danger btn-md">
                                            Hapus
                                        </a>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak Ada Data</td>
                        </tr>
                    @endforelse 
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            <p class="m-0 text-secondary">
                Showing <span>{{ $macAdress->firstItem() }}</span> 
                to <span>{{ $macAdress->lastItem() }}</span> of
                <span>{{ $macAdress->total() }}</span> entries
            </p>
            <ul class="pagination m-0 ms-auto">
                {{ $macAdress->links() }}
            </ul>
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mac Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group mb-3">
                        <label for="mac_address" class="mb-2">Mac Address</label>
                        <input type="text" id="mac_address" class="form-control" name="mac_address">
                        <div class="invalid-feedback error_mac_address"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="router_id" class="mb-2">Router</label>
                        <select name="router_id" id="router_id" class="form-control">
                            <option value="">-- Pilih Router --</option>
                            @foreach ($router as $rtr)
                                <option value="{{ $rtr->id }}">{{ $rtr->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback error_status_device"></div>
                    </div>
                    <div class="form-group">
                        <label for="status_device" class="mb-2">Status Device</label>
                        <select name="status_device" id="status_device" class="form-control">
                            <option value="">-- Pilih Status Device --</option>
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                        </select>
                        <div class="invalid-feedback error_status_device"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal-customer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Customer/h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group mb-3">
                        <label for="mac_address" class="mb-2">Mac Address</label>
                        <input type="text" id="mac" class="form-control" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="mac_address" class="mb-2">ID Pelanggan</label>
                        <input type="text" id="id_customer" class="form-control" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="mac_address" class="mb-2">Tipe Pelanggan</label>
                        <input type="text" id="tipe" class="form-control" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label class="mb-2">OLT</label>
                        <div class="input-group">
                            <input type="text" id="olt" class="form-control" disabled>
                            <a href="#" id="olt_link" class="btn btn-outline-primary" target="_blank">
                                🔗
                            </a>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="mac_address" class="mb-2">Di Input Oleh</label>
                        <input type="text" id="user_show" class="form-control" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="mac_address" class="mb-2">Tanggal Terdaftar</label>
                        <input type="text" id="created_user" class="form-control" disabled>
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
    <script>
        const BASE = "{{ route('mac.address.index') }}";

        document.addEventListener("DOMContentLoaded", function () {
            const advancedTable = {
                headers: [
                    { "data-sort": "sort-mac-address", name: "Mac Address" },
                    { "data-sort": "sort-status", name: "Status" },
                    { "data-sort": "sort-status-device", name: "Status Device" },
                    { "data-sort": "sort-customer", name: "Customer" },
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
            $(".modal-title").html("Tambah Mac Address");
            $("#mac_address").val("");
            $("#status_device").val("");
            $("#type").val("create");
            $("#id").val("");
        });

        $("#storeBtn").click(function() {
            let id = $("#id").val();
            let type = $("#type").val();
            let mac_address = $("#mac_address").val();
            let router_id = $("#router_id").val();
            let status_device = $("#status_device").val();

            let url;
            let method;

            if (type === 'create') {
                url = BASE + '/store';
                method = "POST";
            } else {
                url = BASE + `/${id}/update`
                method = "PUT";
            }
            
            $.ajax({
                url: url,
                method: method,
                data: {
                    mac_address: mac_address,
                    router_id: router_id,
                    status_device: status_device,
                },
            }).done(function(response) {
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
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
            });
        });

        function editModal(id) {
            let url = BASE + `/${id}/show`
            $.ajax({
                url: url,
                method: "GET",
                dataType: "json"
            }).done(function(response){
                $(".modal-title").html("Edit Mac Address");
                let data = response.data;
                
                $("#modal-simple").modal('show')

                $("#id").val(data.id);
                $("#mac_address").val(data.mac_address);
                $("#router_id").val(data.router_id);
                $("#status_device").val(data.status_device);
                $("#type").val("update");
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
            });
        }

        function showCustomer(id) {
            let url = BASE + `/${id}/get-customer`
            $.ajax({
                url: url,
                method: "GET",
                dataType: "json"
            }).done(function(response){
                $(".modal-title").html("Detail Customer");
                let data = response.data;
                
                $("#modal-customer").modal('show')
                
                $("#mac").val(data.mac_address);
                $("#id_customer").val(data.uuid);
                $("#tipe").val(data.type.name);
                
                $("#olt").val(data.olt.name);
                $("#olt_link").attr("href", data.olt.link);

                $("#user_show").val(data.user.name);

                let isoDate = data.created_at;
                let date = new Date(isoDate);
                
                let formatted =
                    String(date.getDate()).padStart(2, '0') + '/' +
                    String(date.getMonth() + 1).padStart(2, '0') + '/' +
                    date.getFullYear() + ' - ' +
                    String(date.getHours()).padStart(2, '0') + ':' +
                    String(date.getMinutes()).padStart(2, '0') + ':' +
                    String(date.getSeconds()).padStart(2, '0');

                $("#created_user").val(formatted);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
            });
        }

        function deleteItem(id) {
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

        function switchUsedMac() {
            let checks = document.querySelectorAll('.row-check:checked');

            if (checks.length === 0) {
                Toast.fire({
                    icon: "info",
                    title: "Pilih satu atau lebih mac address untuk ubah status ke used"
                });
                return false;
            }

            let macIds = Array.from(checks).map(c => c.value);

            console.log(macIds);

            $.ajax({
                url: BASE + '/switch-used',
                method: "POST",
                data: {
                    ids: macIds,
                    _token: "{{ csrf_token() }}"
                },
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
            });

            // return true;
        }
    </script>
@endpush

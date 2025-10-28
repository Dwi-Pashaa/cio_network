@extends('layouts.app')

@section('title')
    Data Pelanggan
@endsection

@push('css')
    
@endpush

@section('content')
    @include('components.alert.success')
    <div class="card">
        @can('buat desa')
            <div class="card-header">
                <a href="{{ route('customer.create') }}" class="btn btn-primary m-2">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Tambah
                </a>
                @can('download excel')
                    <a href="{{ route('customer.export') }}" class="btn btn-success btn-md">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-spreadsheet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M8 11h8v7h-8z" /><path d="M8 15h8" /><path d="M11 11v7" /></svg>
                        Download Excel
                    </a>
                @endcan
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
                            <th><button class="table-sort d-flex justify-content-between desc">No</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-id">ID Pelanggan</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-type">Type Pelanggan</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-nama">Nama Pelanggan</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-email">Email</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-telp">No Telephone</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-mac">Mac Address</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-router">Jenis Router</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-kampung">Kampung</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-desa">Desa</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-rt">RT</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-rw">RW</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-kecamatan">Kecamatan</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-kabupaten">Kabupaten/Kota</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-vlan">Vlan</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-odc">Alamat ODC</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-odp">Alamat ODP</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-olt">Alamat OLT</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-wifi">Nama Wifi</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-passwifi">Password Wifi</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-ppoeuser">PPOE Username</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-ppoepsw">PPOE Password</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-paket">Tipe Paket</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-mixradius">Mix Radius</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-pembayaran">Tipe Pembayaran</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-lokasi">Lokasi</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-input">Di Input Oleh</button></th>
                            <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-created">Created</button></th>
                            @if(auth()->user()->can('ubah pelanggan') || auth()->user()->can('hapus pelanggan'))
                                <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-action">Action</button></th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="table-tbody">
                        @forelse ($customers as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="sort-id">{{ $item->uuid ?? '-' }}</td>
                                <td class="sort-type">{{ $item->type->name }}</td>
                                <td class="sort-nama">{{ $item->name }}</td>
                                <td class="sort-email">{{ $item->email }}</td>
                                <td class="sort-telp">{{ $item->telp }}</td>
                                <td class="sort-mac">{{ $item->mac_address }}</td>
                                <td class="sort-router">{{ $item->router->name }}</td>
                                <td class="sort-kampung">{{ $item->hometown->name }}</td>
                                <td class="sort-desa">{{ $item->village->name }}</td>
                                <td class="sort-rt">{{ $item->rt->name }}</td>
                                <td class="sort-rw">{{ $item->rw->name }}</td>
                                <td class="sort-kecamatan">{{ $item->district->name }}</td>
                                <td class="sort-kabupaten">{{ $item->regencie->name }}</td>
                                <td class="sort-vlan">{{ $item->vlan->name }}</td>
                                <td class="sort-odc">
                                    {{ $item->odc->code }} | {{ $item->odc->hometown->name }} |
                                    {{ $item->odc->rt->name }} | {{ $item->odc->rw->name }} |
                                    {{ $item->odc->home_odc }}
                                </td>
                                <td class="sort-odp">
                                    {{ $item->odp->code }} | {{ $item->odp->hometown->name }} |
                                    {{ $item->odp->rt->name }} | {{ $item->odp->rw->name }} |
                                    {{ $item->odp->home_odc }}
                                </td>
                                <td class="sort-olt">{{ $item->olt->hometown->name }} | {{ $item->olt->name }}</td>
                                <td class="sort-wifi">{{ $item->name_wifi ?? '-' }}</td>
                                <td class="sort-passwifi">{{ $item->password_wifi ?? '-' }}</td>
                                <td class="sort-ppoeuser">{{ $item->pppoe_username ?? '-' }}</td>
                                <td class="sort-ppoepsw">{{ $item->pppoe_password ?? '-' }}</td>
                                <td class="sort-paket">{{ optional($item)->paket->name ?? '-' }}</td>
                                <td class="sort-mixradius">{{ optional($item)->mic_radius->code ?? '-' }} - {{ optional($item)->mic_radius->name ?? '-' }}</td>
                                <td class="sort-pembayaran">{{ optional($item)->price->name ?? '-' }}</td>
                                <td class="sort-lokasi">
                                    <a href="https://www.google.com/maps?q={{ $item->latitude }},{{ $item->longitude }}" target="_blank" class="btn btn-primary btn-sm">Lihat Lokasi</a>
                                </td>
                                <td class="sort-input">{{ optional($item)->user->name ?? '-' }}</td>
                                <td class="sort-created">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}</td>
                                
                                @if(auth()->user()->can('ubah pelanggan') || auth()->user()->can('hapus pelanggan'))
                                    <td class="sort-action">
                                        @can('ubah pelanggan')
                                            <a href="{{ route('customer.edit', ['id' => $item->id]) }}" class="btn btn-outline-warning btn-md">Edit</a>
                                        @endcan
                                        @can('hapus pelanggan')
                                            <a href="javascript:void(0)" onclick="return deleteType('{{ $item->id }}')" class="btn btn-outline-danger btn-md">Hapus</a>
                                        @endcan
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="19" class="text-center">Tidak Ada Data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
        <div class="card-footer d-flex align-items-center">
            <p class="m-0 text-secondary">
                Showing <span>{{ $customers->firstItem() }}</span> 
                to <span>{{ $customers->lastItem() }}</span> of
                <span>{{ $customers->total() }}</span> entries
            </p>
            <ul class="pagination m-0 ms-auto">
                {{ $customers->links() }}
            </ul>
        </div>
    </div>
@endsection

@push('js')
    <script>
        const advancedTable = {
            headers: [
                { "data-sort": "sort-no", name: "No" },
                { "data-sort": "sort-id", name: "ID Pelanggan" },
                { "data-sort": "sort-type", name: "Type Pelanggan" },
                { "data-sort": "sort-nama", name: "Nama Pelanggan" },
                { "data-sort": "sort-email", name: "Email" },
                { "data-sort": "sort-telp", name: "No Telephone" },
                { "data-sort": "sort-mac", name: "Mac Address" },
                { "data-sort": "sort-router", name: "Jenis Router" },
                { "data-sort": "sort-kampung", name: "Kampung" },
                { "data-sort": "sort-desa", name: "Desa" },
                { "data-sort": "sort-rt", name: "RT" },
                { "data-sort": "sort-rw", name: "RW" },
                { "data-sort": "sort-kecamatan", name: "Kecamatan" },
                { "data-sort": "sort-kabupaten", name: "Kabupaten/Kota" },
                { "data-sort": "sort-vlan", name: "Vlan" },
                { "data-sort": "sort-odc", name: "Alamat ODC" },
                { "data-sort": "sort-odp", name: "Alamat ODP" },
                { "data-sort": "sort-olt", name: "Alamat OLT" },
                { "data-sort": "sort-wifi", name: "Nama Wifi" },
                { "data-sort": "sort-passwifi", name: "Password Wifi" },
                { "data-sort": "sort-ppoeuser", name: "PPOE Username" },
                { "data-sort": "sort-ppoepsw", name: "PPOE Password" },
                { "data-sort": "sort-paket", name: "Tipe Paket" },
                { "data-sort": "sort-mixradius", name: "Mix Radius" },
                { "data-sort": "sort-pembayaran", name: "Tipe Pembayaran" },
                { "data-sort": "sort-lokasi", name: "Lokasi" },
                { "data-sort": "sort-input", name: "Di Input Oleh" },
                { "data-sort": "sort-created", name: "Created" },
                { "data-sort": "sort-action", name: "Action" },
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
        const BASE = "{{ route('customer.index') }}";

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
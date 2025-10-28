@extends('layouts.app')

@section('title')
    Data Spam
@endsection

@push('css')
    
@endpush

@section('content')
    @include('components.alert.success')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#tabs-home-1" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <b>Data Pelanggan</b>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#tabs-profile-1" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                        <b>Data Perbaikan/Pergantian Perangkat</b>
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane active show" id="tabs-home-1" role="tabpanel">
                <div class="card">
                    @can('buat desa')
                        <div class="card-header">
                            <a href="{{ route('customer.create') }}" class="btn btn-primary m-2">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                Tambah
                            </a>
                            {{-- @can('download excel')
                                <a href="{{ route('customer.export') }}" class="btn btn-success btn-md">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-spreadsheet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M8 11h8v7h-8z" /><path d="M8 15h8" /><path d="M11 11v7" /></svg>
                                    Download Excel
                                </a>
                            @endcan --}}
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
                    <div id="table-customers" class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th><button class="table-sort" data-sort="sort-no">No</button></th>
                                    <th><button class="table-sort" data-sort="sort-type">Type Pelanggan</button></th>
                                    <th><button class="table-sort" data-sort="sort-name">Nama Pelanggan</button></th>
                                    <th><button class="table-sort" data-sort="sort-email">Email</button></th>
                                    <th><button class="table-sort" data-sort="sort-telp">No Telephone</button></th>
                                    <th><button class="table-sort" data-sort="sort-mac">Mac Address</button></th>
                                    <th><button class="table-sort" data-sort="sort-router">Jenis Router</button></th>
                                    <th><button class="table-sort" data-sort="sort-hometown">Kampung</button></th>
                                    <th><button class="table-sort" data-sort="sort-village">Desa</button></th>
                                    <th>RT</th>
                                    <th>RW</th>
                                    <th><button class="table-sort" data-sort="sort-district">Kecamatan</button></th>
                                    <th><button class="table-sort" data-sort="sort-regencie">Kabupaten/Kota</button></th>
                                    <th><button class="table-sort" data-sort="sort-vlan">Vlan</button></th>
                                    <th>Alamat ODC</th>
                                    <th>Alamat ODP</th>
                                    <th>Alamat OLT</th>
                                    <th><button class="table-sort" data-sort="sort-created">Created</button></th>
                                    @if(auth()->user()->can('ubah pelanggan') || auth()->user()->can('hapus pelanggan'))
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="list table-tbody">
                                @forelse ($customers as $item)
                                    <tr>
                                        <td class="sort-no">{{ $loop->iteration }}</td>
                                        <td class="sort-type">{{ $item->type->name }}</td>
                                        <td class="sort-name">{{ $item->name }}</td>
                                        <td class="sort-email">{{ $item->email }}</td>
                                        <td class="sort-telp">{{ $item->telp }}</td>
                                        <td class="sort-mac">{{ $item->mac_address }}</td>
                                        <td class="sort-router">{{ $item->router->name }}</td>
                                        <td class="sort-hometown">{{ $item->hometown->name }}</td>
                                        <td class="sort-village">{{ $item->village->name }}</td>
                                        <td>{{ $item->rt->name }}</td>
                                        <td>{{ $item->rw->name }}</td>
                                        <td class="sort-district">{{ $item->district->name }}</td>
                                        <td class="sort-regencie">{{ $item->regencie->name }}</td>
                                        <td class="sort-vlan">{{ $item->vlan->name }}</td>
                                        <td>
                                            {{ $item->odc->code }} | {{ $item->odc->hometown->name }} | {{ $item->odc->rt->name }} | {{ $item->odc->rw->name }} | {{ $item->odc->home_odc }}
                                        </td>
                                        <td>
                                            {{ $item->odp->code }} | {{ $item->odp->hometown->name }} | {{ $item->odp->rt->name }} | {{ $item->odp->rw->name }} | {{ $item->odp->home_odc }}
                                        </td>
                                        <td>{{ $item->olt->hometown->name }} | {{ $item->olt->name }}</td>
                                        <td class="sort-created">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}</td>
                                        @if(auth()->user()->can('ubah pelanggan') || auth()->user()->can('hapus pelanggan'))
                                            <td>
                                                @can('ubah pelanggan')
                                                    <a href="javascript:void(0)" onclick="return outSpam('{{ $item->id }}')" class="btn btn-outline-warning btn-md">Active</a>
                                                @endcan
                                                @can('hapus pelanggan')
                                                    <a href="javascript:void(0)" onclick="return reject('{{ $item->id }}')" class="btn btn-outline-danger btn-md">Reject</a>
                                                @endcan
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr><td colspan="18" class="text-center">Tidak Ada Data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
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
            </div>
            <div class="tab-pane" id="tabs-profile-1" role="tabpanel">
                <div class="card">
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
                    <div id="table-switch" class="table-responsive mt-5">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th><button class="table-sort" data-sort="sort-no">No</button></th>
                                    <th><button class="table-sort" data-sort="sort-uuid">ID Pelanggan</button></th>
                                    <th><button class="table-sort" data-sort="sort-name">Nama Pelanggan</button></th>
                                    <th><button class="table-sort" data-sort="sort-type-old">Tipe Voucher Lama</button></th>
                                    <th><button class="table-sort" data-sort="sort-router-old">Tipe Router Lama</button></th>
                                    <th><button class="table-sort" data-sort="sort-mac-old">Mac Address Lama</button></th>
                                    <th><button class="table-sort" data-sort="sort-type-new">Tipe Voucher Baru</button></th>
                                    <th><button class="table-sort" data-sort="sort-router-new">Tipe Router Baru</button></th>
                                    <th><button class="table-sort" data-sort="sort-mac-new">Mac Address Baru</button></th>
                                    <th><button class="table-sort" data-sort="sort-created">Created</button></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="list table-tbody">
                                @forelse ($switchs as $swtch)
                                    <tr>
                                        <td class="sort-no">{{ $loop->iteration }}</td>
                                        <td class="sort-uuid">{{ $swtch->customer->uuid }}</td>
                                        <td class="sort-name">{{ $swtch->customer->name }}</td>
                                        <td class="sort-type-old">{{ $swtch->typeOld->name }}</td>
                                        <td class="sort-router-old">{{ $swtch->routerOld->name }}</td>
                                        <td class="sort-mac-old">{{ $swtch->mac_address_old }}</td>
                                        <td class="sort-type-new">{{ $swtch->typeNew->name }}</td>
                                        <td class="sort-router-new">{{ $swtch->routerNew->name }}</td>
                                        <td class="sort-mac-new">{{ $swtch->mac_address_new }}</td>
                                        <td class="sort-created">{{ \Carbon\Carbon::parse($swtch->created_at)->translatedFormat('d F Y - H:i:s') }}</td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="return outSwitch('{{ $swtch->id }}')" class="btn btn-outline-warning btn-md">Active</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="11" class="text-center">Tidak Ada Data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        <p class="m-0 text-secondary">
                            Showing <span>{{ $switchs->firstItem() }}</span> 
                            to <span>{{ $switchs->lastItem() }}</span> of
                            <span>{{ $switchs->total() }}</span> entries
                        </p>
                        <ul class="pagination m-0 ms-auto">
                            {{ $switchs->links() }}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        const BASE = "{{ route('spam.index') }}";

        let params = new URLSearchParams(window.location.search);
        $("#sort").change(function() {
            params.set('sort', $(this).val());
            window.location.href = BASE + '?' + params.toString();
        });

        document.addEventListener("DOMContentLoaded", function () {
            window.tabler_list = window.tabler_list || {};

            const initList = (id, headers) => {
                window.tabler_list[id] = new List(id, {
                    sortClass: "table-sort",
                    listClass: "table-tbody",
                    searchClass: "search",
                    page: 10,
                    pagination: true,
                    valueNames: headers.map(h => h["data-sort"]),
                });
            };

            initList("table-customers", [
                { "data-sort": "sort-type" },
                { "data-sort": "sort-name" },
                { "data-sort": "sort-email" },
                { "data-sort": "sort-telp" },
                { "data-sort": "sort-router" },
                { "data-sort": "sort-hometown" },
                { "data-sort": "sort-district" },
                { "data-sort": "sort-vlan" },
                { "data-sort": "sort-created" },
            ]);

            initList("table-switch", [
                { "data-sort": "sort-uuid" },
                { "data-sort": "sort-name" },
                { "data-sort": "sort-type-old" },
                { "data-sort": "sort-type-new" },
                { "data-sort": "sort-router-old" },
                { "data-sort": "sort-router-new" },
                { "data-sort": "sort-created" },
            ]);
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

        function outSpam(id) {
            Swal.fire({
                title: "Info !",
                text: "Anda yakin ingin memindahkan data ini dari spam?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Keluarkan",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: BASE + '/' + id + '/outSpam',
                        method: "PUT",
                        dataType: "json",
                        success: function(response) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Berhasil mengeluarkan pelanggan dari spam.'
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

        function reject(id) {
            Swal.fire({
                title: "Info !",
                text: "Anda yakin ingin membatalkan data pelanggan ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Iya",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: BASE + '/' + id + '/reject',
                        method: "DELETE",
                        dataType: "json",
                        success: function(response) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Berhasil membatalkan data customer.'
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

        function outSwitch(id) {
            Swal.fire({
                title: "Info !",
                text: "Anda yakin ingin memindahkan data ini dari spam?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Keluarkan",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: BASE + '/' + id + '/outSwitch',
                        method: "PUT",
                        dataType: "json",
                        success: function(response) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Berhasil mengeluarkan pelanggan dari spam.'
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
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
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Type Pelanggan</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Email</th>
                                    <th>No Telephone</th>
                                    <th>Mac Addres</th>
                                    <th>Jenis Router</th>
                                    <th>Kampung</th>
                                    <th>Desa</th>
                                    <th>RT</th>
                                    <th>RW</th>
                                    <th>Kecamatan</th>
                                    <th>Kabupaten/Kota</th>
                                    <th>Vlan</th>
                                    <th>Alamat ODC</th>
                                    <th>Alamat ODP</th>
                                    <th>Alamat OLT</th>
                                    <th>Created</th>
                                    @if(auth()->user()->can('ubah pelanggan') || auth()->user()->can('hapus pelanggan'))
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customers as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->type->name }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->telp }}</td>
                                        <td>{{ $item->mac_address }}</td>
                                        <td>{{ $item->router->name }}</td>
                                        <td>{{ $item->hometown->name }}</td>
                                        <td>{{ $item->village->name }}</td>
                                        <td>{{ $item->rt->name }}</td>
                                        <td>{{ $item->rw->name }}</td>
                                        <td>{{ $item->district->name }}</td>
                                        <td>{{ $item->regencie->name }}</td>
                                        <td>{{ $item->vlan->name }}</td>
                                        <td>
                                            {{ $item->odc->code }} | {{ $item->odc->hometown->name }} 
                                            | {{ $item->odc->rt->name }} | {{ $item->odc->rw->name }} |
                                            {{ $item->odc->home_odc }}
                                        </td>
                                        <td>
                                            {{ $item->odp->code }} | {{ $item->odp->hometown->name }} 
                                            | {{ $item->odp->rt->name }} | {{ $item->odp->rw->name }} |
                                            {{ $item->odp->home_odc }}
                                        </td>
                                        <td>{{ $item->olt->hometown->name }} | {{ $item->olt->name }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}
                                        </td>
                                        @if(auth()->user()->can('ubah pelanggan') || auth()->user()->can('hapus pelanggan'))
                                            <td>
                                                @can('ubah pelanggan')
                                                    <a href="javascript:void(0)" onclick="return outSpam('{{ $item->id }}')" class="btn btn-outline-warning btn-md">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-checkup-list"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 14h.01" /><path d="M9 17h.01" /><path d="M12 16l1 1l3 -3" /></svg>
                                                        Active
                                                    </a>
                                                @endcan
                                            </td> 
                                            <td>
                                                @can('hapus pelanggan')
                                                    <a href="javascript:void(0)" onclick="return reject('{{ $item->id }}')" class="btn btn-outline-danger btn-md">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                        Reject
                                                    </a>
                                                @endcan
                                            </td> 
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="18" class="text-center">Tidak Ada Data</td>
                                    </tr>
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
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Pelanggan</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Tipe Voucher Lama</th>
                                    <th>Tipe Router Lama</th>
                                    <th>Mac Addres Lama</th>
                                    <th>Tipe Voucher Baru</th>
                                    <th>Tipe Router Baru</th>
                                    <th>Mac Addres Baru</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($switchs as $swtch)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $swtch->customer->uuid }}</td>
                                        <td>{{ $swtch->customer->name }}</td>
                                        <td>{{ $swtch->typeOld->name }}</td>
                                        <td>{{ $swtch->routerOld->name }}</td>
                                        <td>{{ $swtch->mac_address_old }}</td>
                                        <td>{{ $swtch->typeNew->name }}</td>
                                        <td>{{ $swtch->routerNew->name }}</td>
                                        <td>{{ $swtch->mac_address_new }}</td>
                                        <td>{{ \Carbon\Carbon::parse($swtch->created_at)->translatedFormat('d F Y - H:i:s') }}</td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="return outSwitch('{{ $swtch->id }}')" class="btn btn-outline-warning btn-md">
                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-checkup-list"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 14h.01" /><path d="M9 17h.01" /><path d="M12 16l1 1l3 -3" /></svg>
                                                Active
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">Tidak Ada Data</td>
                                    </tr>
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
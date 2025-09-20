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
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pelanggan</th>
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
                        <th>Nama Wifi</th>
                        <th>Password Wifi</th>
                        <th>PPOE Username</th>
                        <th>PPOE Password</th>
                        <th>Tipe Paket</th>
                        <th>Tipe Pembayaran</th>
                        <th>Lokasi</th>
                        <th>Di Input Oleh</th>
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
                            <td>{{ $item->uuid ?? '-' }}</td>
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
                                {{ $item->name_wifi ?? '-' }}
                            </td>
                            <td>
                                {{ $item->password_wifi ?? '-' }}
                            </td>
                            <td>
                                {{ $item->pppoe_username ?? '-' }}
                            </td>
                            <td>
                                {{ $item->pppoe_password ?? '-' }}
                            </td>
                            <td>
                                {{ optional($item)->paket->name ?? '-' }}
                            </td>
                            <td>
                                {{ optional($item)->price->name ?? '-' }}
                            </td>
                            <td>
                                <a href="https://www.google.com/maps?q={{ $item->latitude }},{{ $item->longitude }}" target="_blank" class="btn btn-primary btn-sm">Lihat Lokasi</a>
                            </td>
                            <td>
                                {{ optional($item)->user->name ?? '-' }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}
                            </td>
                            @if(auth()->user()->can('ubah pelanggan') || auth()->user()->can('hapus pelanggan'))
                                <td>
                                    @can('ubah pelanggan')
                                        <a href="{{ route('customer.edit', ['id' => $item->id]) }}" class="btn btn-outline-warning btn-md">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                            Edit
                                        </a>
                                    @endcan
                                    @can('hapus pelanggan')
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
                            <td colspan="19" class="text-center">Tidak Ada Data</td>
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
@endsection

@push('js')
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
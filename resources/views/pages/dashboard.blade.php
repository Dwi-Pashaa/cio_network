@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@push('css')
    
@endpush

@section('content')
    <div class="alert alert-primary">
        <b>Selamat Datang Di {{ config('app.name') }}  {{ Auth::user()->name }}</b>
    </div>

    @php
        $colors = ['bg-primary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-info', 'bg-secondary'];
    @endphp

    <div class="row row-cards mb-3">
        @foreach ($userRouter as $rtr)
            @php
                $bgColor = $colors[$loop->index % count($colors)];
            @endphp

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="{{ $bgColor }} text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-router">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 13m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                        <path d="M17 17l0 .01" />
                                        <path d="M13 17l0 .01" />
                                        <path d="M15 13l0 -2" />
                                        <path d="M11.75 8.75a4 4 0 0 1 6.5 0" />
                                        <path d="M8.5 6.5a8 8 0 0 1 13 0" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    Router {{ $rtr->name }}
                                </div>
                                <div class="text-secondary">
                                    Total Stock {{ $rtr->pivot->total }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-body">
            <form action="">
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Pilih</label>
                    <select name="filter" id="filter" class="form-control">
                        <option value="">Pilih</option>
                        @php
                            $filter = ["kabupaten", "kecamatan", "desa", "kampung", "vlan", "olt", "voucher & ppoe"];
                        @endphp
                        @foreach ($filter as $item)
                            @php
                                $value = $item;
                                $label = $item === 'kabupaten' ? 'Kabupaten / Kota' : ucfirst($item);
                            @endphp

                            <option value="{{ $value }}" {{ request('filter') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="row row-cards mt-1">
        @php
            $colors = ['bg-primary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-info', 'bg-secondary', 'bg-dark'];
        @endphp

        @forelse ($data as $dt)
            <div class="col-sm-6 col-lg-4">
                <a href="javascript:void(0)" onclick="return detailCount('{{ $dt->id }}', '{{ $text }}')">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="{{ $colors[$loop->index % count($colors)] }} text-white avatar">
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                            width="24" height="24" viewBox="0 0 24 24" 
                                            fill="none" stroke="currentColor" stroke-width="2" 
                                            stroke-linecap="round" stroke-linejoin="round"  
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">
                                        <b>
                                            {{ request('filter') === "vlan" ? 'VLAN ' . $dt->name : $dt->name }}
                                        </b>
                                    </div>
                                    <div class="text-secondary">
                                        {{ $dt->customer_count }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-sm-12 col-lg-12">
                <div class="alert alert-secondary">
                    <b>Silahkan melakukan filter terlebih dahulu</b>
                </div>
            </div>
        @endforelse
    </div>
@endsection

@push('modal')
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-1 modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Type Pelanggan</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Email</th>
                                    <th>No Telephone</th>
                                    <th>Mac Address</th>
                                    <th>Nama Router</th>
                                    <th>Di Input Oleh</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-show">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            $("#filter").on("change", function() {
                $(this).closest("form").submit();
            });
        });

        const BASE = "{{ route('dashboard') }}"

        function detailCount(id, text) {
            if (text == "Kabupaten / Kota") {
                text = "kabupaten";
            }
            $.ajax({
                url: `/get-detail-count/` + id + '/' + text,
                method: "GET",
                dataType: "json",
                success: function(data) {
                    $(".modal-title").html('Detail customer ' + text + '  ' + data.data.name);
                    $("#tbody-show").html('');

                    let html = '';
                    let no = 1;
                    $.each(data.data.customer, function(index, value) {
                        console.log(value);
                        
                        html += `<tr>
                                    <td>${no++}</td>
                                    <td>${value.type?.name ?? '-'}</td>
                                    <td>${value.name}</td>
                                    <td>${value.email}</td>
                                    <td>${value.telp}</td>
                                    <td>${value.mac_address}</td>
                                    <td>${value.router.name}</td>
                                    <td>${value.user?.name ?? '-'}</td>
                                    <td>${new Date(value.created_at).toLocaleDateString('id-ID', {
                                        day: '2-digit',
                                        month: 'long',
                                        year: 'numeric'
                                        })}</td>
                                </tr>`;
                    });
                    $("#tbody-show").html(html);

                    var myModal = new bootstrap.Modal(document.getElementById('modal-simple'));
                    myModal.show();
                },
                error: function(err) {
                    console.log(err);
                }
            })
        }
    </script>
@endpush

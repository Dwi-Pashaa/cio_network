@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@push('css')
    <style>
        .hover-shadow-lg {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .hover-shadow-lg:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .form-select:focus,
        .btn:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        .card {
            border: 1px solid rgba(0, 0, 0, 0.08);
        }

        .avatar-lg {
            width: 3.5rem;
            height: 3.5rem;
            font-size: 1.5rem;
        }

        .bg-primary-lt {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }

        .bg-success-lt {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }

        .bg-warning-lt {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }

        .bg-danger-lt {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }

        .bg-info-lt {
            background-color: rgba(13, 202, 240, 0.1) !important;
        }

        .bg-secondary-lt {
            background-color: rgba(108, 117, 125, 0.1) !important;
        }

        .bg-teal-lt {
            background-color: rgba(32, 201, 151, 0.1) !important;
        }

        .bg-purple-lt {
            background-color: rgba(109, 58, 219, 0.1) !important;
        }

        .text-teal {
            color: #20c997 !important;
        }

        .text-purple {
            color: #6d3adb !important;
        }
    </style>
    <style>
        .hover-shadow-sm {
            transition: all 0.3s ease;
        }

        .hover-shadow-sm:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
            transform: translateY(-2px);
        }

        .card-sm {
            min-height: 80px;
        }

        .card-sm .card-body {
            overflow: hidden;
        }

        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
        }

        .overflow-hidden {
            overflow: hidden;
            min-width: 0;
        }
    </style>
@endpush

@section('content')
    <div class="alert alert-primary">
        <b>Selamat Datang Di {{ config('app.name') }}  {{ Auth::user()->name }}</b>
    </div>

    @php
        $colors = ['bg-primary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-info', 'bg-secondary'];
    @endphp

    <div class="row">
        <div class="col-lg-6">
            {{-- Router Section --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                            stroke-linejoin="round" class="icon me-2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 13m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                            <path d="M17 17l0 .01" />
                            <path d="M13 17l0 .01" />
                            <path d="M15 13l0 -2" />
                            <path d="M11.75 8.75a4 4 0 0 1 6.5 0" />
                            <path d="M8.5 6.5a8 8 0 0 1 13 0" />
                        </svg>
                        Jumlah Router Tersedia
                    </h3>
                </div>
                @if($userRouter->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-vcenter table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Router</th>
                                    <th>Total Stock</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userRouter as $rtr)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $bgColor = $colors[$loop->index % count($colors)];
                                                @endphp
                                                <span class="{{ $bgColor }} text-white avatar avatar-sm me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" 
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M3 13m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                                        <path d="M17 17l0 .01" />
                                                        <path d="M13 17l0 .01" />
                                                        <path d="M15 13l0 -2" />
                                                    </svg>
                                                </span>
                                                <strong>{{ $rtr->name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-blue-lt">{{ $rtr->pivot->total }} Unit</span>
                                        </td>
                                        <td>
                                            @if($rtr->pivot->total > 10)
                                                <span class="badge bg-success">Stock Tersedia</span>
                                            @elseif($rtr->pivot->total > 0)
                                                <span class="badge bg-warning text-white">Stock Terbatas</span>
                                            @else
                                                <span class="badge bg-danger text-white">Stock Habis</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="card-body">
                        <div class="empty">
                            <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                                    stroke-linejoin="round" class="icon">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M3 13m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                </svg>
                            </div>
                            <p class="empty-title">Tidak ada stock router</p>
                            <p class="empty-subtitle text-muted">Silahkan minta admin untuk menambahkan stock router</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="col-lg-6">
            {{-- Patch Core Section --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                            stroke-linejoin="round" class="icon me-2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                            <path d="M9 12h6" />
                            <path d="M12 9v6" />
                        </svg>
                        Jumlah Patch Core Tersedia
                    </h3>
                </div>
                @if($userPatchCore->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-vcenter table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Patch Core</th>
                                    <th>Total Stock</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userPatchCore as $ptc)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $bgColor = $colors[$loop->index % count($colors)];
                                                @endphp
                                                <span class="{{ $bgColor }} text-white avatar avatar-sm me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" 
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                                        <path d="M9 12h6" />
                                                        <path d="M12 9v6" />
                                                    </svg>
                                                </span>
                                                <strong>{{ $ptc->name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-cyan-lt">{{ $ptc->pivot->total }} Unit</span>
                                        </td>
                                        <td>
                                            @if($ptc->pivot->total > 10)
                                                <span class="badge bg-success">Stock Tersedia</span>
                                            @elseif($ptc->pivot->total > 0)
                                                <span class="badge bg-warning text-white">Stock Terbatas</span>
                                            @else
                                                <span class="badge bg-danger text-white">Stock Habis</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="card-body">
                        <div class="empty">
                            <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                                    stroke-linejoin="round" class="icon">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                </svg>
                            </div>
                            <p class="empty-title">Tidak ada stock patch core</p>
                            <p class="empty-subtitle text-muted">Silahkan minta admin untuk menambahkan stock patch core</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="col-12">
            {{-- Pages Access Section - Modern Grid Layout --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                            stroke-linejoin="round" class="icon me-2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            <path d="M9 17h6" />
                            <path d="M9 13h6" />
                        </svg>
                        Data Halaman Yang Dapat Di Akses
                    </h3>
                    <div class="card-actions">
                        <span class="badge bg-primary text text-white">{{ $userPages->count() }} Halaman</span>
                    </div>
                </div>
                @if($userPages->count() > 0)
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($userPages as $upg)
                                @php
                                    $bgColor = $colors[$loop->index % count($colors)];
                                @endphp
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a href="{{ route('input.data.index', ['slug' => $upg->slug]) }}" style="text-decoration: none">
                                        <div class="card card-sm border hover-shadow-sm h-100">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="{{ $bgColor }} text-white avatar avatar-sm me-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" 
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                        </svg>
                                                    </span>
                                                    <div class="flex-fill overflow-hidden">
                                                        <div class="fw-bold text-truncate" title="{{ $upg->name }}" style="max-width: 100%;">
                                                            {{ $upg->name }}
                                                        </div>
                                                        <div class="text-muted small text-nowrap">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" 
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M5 12l5 5l10 -10" />
                                                            </svg>
                                                            Aktif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="card-body">
                        <div class="empty">
                            <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                                    stroke-linejoin="round" class="icon">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                </svg>
                            </div>
                            <p class="empty-title">Tidak ada halaman yang dapat diakses</p>
                            <p class="empty-subtitle text-muted">Silahkan minta admin untuk memberikan akses halaman</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler me-2 text-primary">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                </svg>
                <h3 class="card-title mb-0 fw-bold">Filter Data Customer</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-12">
                        <label for="filter" class="form-label fw-semibold mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            Pilih Kategori Filter
                        </label>
                        <select name="filter" id="filter" class="form-select form-select-md" onchange="this.form.submit()">
                            <option value="">-- Pilih Filter --</option>
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
                </div>
            </form>
        </div>
    </div>

    <!-- Results Section -->
    @if(request('filter'))
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <span class="badge bg-primary-lt text-primary px-3 py-2">
                        Hasil Filter: {{ ucfirst(str_replace('_', ' ', request('filter'))) }}
                    </span>
                </h5>
                <span class="text-muted">Total: <strong>{{ $data->count() }}</strong> item</span>
            </div>
        </div>
    @endif

    <!-- Data Cards -->
    <div class="row row-cards g-3">
        @php
            $colors = ['primary', 'success', 'warning', 'danger', 'info', 'secondary', 'teal', 'purple'];
        @endphp

        @forelse ($data as $dt)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <a href="javascript:void(0)" 
                   onclick="return detailCount('{{ $dt->id }}', '{{ $text }}')" 
                   class="text-decoration-none">
                    <div class="card card-sm hover-shadow-lg transition-all h-100">
                        <div class="card-body">
                            <div class="row align-items-center g-2">
                                <div class="col-auto">
                                    <span class="avatar avatar-lg bg-{{ $colors[$loop->index % count($colors)] }}-lt text-{{ $colors[$loop->index % count($colors)] }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                            width="28" height="28" viewBox="0 0 24 24" 
                                            fill="none" stroke="currentColor" stroke-width="2" 
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium text-dark mb-1">
                                        <strong>
                                            {{ request('filter') === "vlan" ? 'VLAN ' . $dt->name : $dt->name }}
                                        </strong>
                                    </div>
                                    <div class="text-secondary small d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                        <span class="fw-semibold">{{ number_format($dt->customer_count) }} Customer</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted mb-3">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <h4 class="text-muted mb-2">Belum Ada Filter Dipilih</h4>
                        <p class="text-secondary mb-0">Silakan pilih kategori filter di atas untuk melihat data customer</p>
                    </div>
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

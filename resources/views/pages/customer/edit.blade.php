@extends('layouts.app')

@section('title', 'Edit Customer: ' . $customer->name)

@push('css')
    <style>
        .form-label {
            font-weight: 500;
            font-size: 0.875rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 0.6rem 0.8rem;
            font-size: 0.875rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-control[readonly] {
            background-color: #f8fafc;
            color: #64748b;
        }

        .org-card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }
    </style>
@endpush

@section('content')
    <div class="org-card">
        <div class="org-header border-bottom pb-3 mb-4 d-flex justify-content-between align-items-center">
            <h2 class="org-card-title">Edit Customer: {{ $customer->name }}</h2>
            <a href="{{ route('customer.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                    <path d="M19 12H5" />
                    <path d="M12 19l-7-7 7-7" />
                </svg> Kembali
            </a>
        </div>
        <div class="org-body">
            <form action="{{ route('customer.update', ['id' => $customer->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" id="status" value="active">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">ID Pelanggan</label>
                            <input type="text" name="" id=""
                                value="{{ $customer->uuid != null ? $customer->uuid : $newCode }}" class="form-control"
                                readonly>
                            <input type="hidden" name="uuid" id="uuid"
                                value="{{ $customer->uuid != null ? $customer->uuid : $newCode }}">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label for="types_id" class="mb-2">Type Pelanggan</label>
                            <select name="types_id" id="types_id"
                                class="form-control @error('types_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($type as $tp)
                                    <option value="{{ $tp->id }}" data-label="{{ $tp->name }}"
                                        {{ $customer->types_id == $tp->id ? 'selected' : '' }}>{{ $tp->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="type_name" value="{{ ucfirst($customer->type->name) }}">
                            @error('types_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="name" class="mb-2">Nama Pelanggan</label>
                            <input value="{{ $customer->name }}" type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="email" class="mb-2">Email Pelanggan</label>
                            <input value="{{ $customer->email }}" type="text" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="telp" class="mb-2">No Telephone</label>
                            <input value="{{ $customer->telp }}" type="text" name="telp" id="telp"
                                class="form-control @error('telp') is-invalid @enderror">
                            @error('telp')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row" id="pppoe-show">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="" class="mb-2">Nama Wifi</label>
                                <input type="text" name="name_wifi" value="{{ $customer->name_wifi }}" id="name_wifi"
                                    class="form-control @error('name_wifi') is-invalid @enderror">
                                @error('name_wifi')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="" class="mb-2">Password Wifi</label>
                                <input type="text" name="password_wifi" value="{{ $customer->password_wifi }}"
                                    id="password_wifi" class="form-control @error('password_wifi') is-invalid @enderror">
                                @error('password_wifi')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="" class="mb-2">Tipe Paket</label>
                                <select name="paket_id" id="paket_id"
                                    class="form-control @error('paket_id') is-invalid @enderror">
                                    <option value="">Pilih</option>
                                    @foreach ($paket as $pkt)
                                        <option value="{{ $pkt->id }}"
                                            {{ $customer->paket_id == $pkt->id ? 'selected' : '' }}>{{ $pkt->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('paket_id')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="" class="mb-2">Tipe Pembayaran</label>
                                <select name="price_id" id="price_id"
                                    class="form-control @error('price_id') is-invalid @enderror">
                                    <option value="">Pilih</option>
                                    @foreach ($price as $prc)
                                        <option value="{{ $prc->id }}"
                                            {{ $customer->price_id == $prc->id ? 'selected' : '' }}>{{ $prc->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('price_id')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="mac_address" class="mb-2">Mac Address</label>
                            <input value="{{ $customer->mac_address }}" type="text" name="mac_address"
                                id="mac_address" class="form-control @error('mac_address') is-invalid @enderror">
                            @error('mac_address')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Mix Radius</label>
                            <select name="mic_radius_id" id="mic_radius_id"
                                class="form-control @error('mic_radius_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($micRadius as $mc)
                                    <option value="{{ $mc->id }}"
                                        {{ $customer->mic_radius_id == $mc->id ? 'selected' : '' }}>{{ $mc->code }} -
                                        {{ $mc->name }}</option>
                                @endforeach
                            </select>
                            @error('mic_radius_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label for="routers_id" class="mb-2">Jenis Router</label>
                            <select name="routers_id" id="routers_id"
                                class="form-control @error('routers_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($router as $rtr)
                                    <option value="{{ $rtr->id }}"
                                        {{ $customer->routers_id == $rtr->id ? 'selected' : '' }}>{{ $rtr->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('routers_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="hometowns_id" class="mb-2">Kampung</label>
                            <select name="hometowns_id" id="hometowns_id"
                                class="form-control @error('hometowns_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($hometown as $hmt)
                                    <option value="{{ $hmt->id }}"
                                        {{ $customer->hometowns_id == $hmt->id ? 'selected' : '' }}>{{ $hmt->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hometowns_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="villages_id" class="mb-2">Desa</label>
                            <select name="villages_id" id="villages_id"
                                class="form-control @error('villages_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($village as $vlg)
                                    <option value="{{ $vlg->id }}"
                                        {{ $customer->villages_id == $vlg->id ? 'selected' : '' }}>{{ $vlg->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('villages_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="rts_id" class="mb-2">RT</label>
                            <select name="rts_id" id="rts_id"
                                class="form-control @error('rts_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($rt as $rts)
                                    <option value="{{ $rts->id }}"
                                        {{ $customer->rts_id == $rts->id ? 'selected' : '' }}>{{ $rts->name }}</option>
                                @endforeach
                            </select>
                            @error('rts_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="rws_id" class="mb-2">RW</label>
                            <select name="rws_id" id="rws_id"
                                class="form-control @error('rws_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($rw as $rws)
                                    <option value="{{ $rws->id }}"
                                        {{ $customer->rws_id == $rws->id ? 'selected' : '' }}>{{ $rws->name }}</option>
                                @endforeach
                            </select>
                            @error('rws_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="districts_id" class="mb-2">Kecamatan</label>
                            <select name="districts_id" id="districts_id"
                                class="form-control @error('districts_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($district as $dsc)
                                    <option value="{{ $dsc->id }}"
                                        {{ $customer->districts_id == $dsc->id ? 'selected' : '' }}>{{ $dsc->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('districts_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="regencies_id" class="mb-2">Kabupaten/Kota</label>
                            <select name="regencies_id" id="regencies_id"
                                class="form-control @error('regencies_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($regencie as $rgc)
                                    <option value="{{ $rgc->id }}"
                                        {{ $customer->regencies_id == $rgc->id ? 'selected' : '' }}>{{ $rgc->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('regencies_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="vlans_id" class="mb-2">Vlan</label>
                            <select name="vlans_id" id="vlans_id"
                                class="form-control @error('vlans_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($vlan as $vln)
                                    <option value="{{ $vln->id }}"
                                        {{ $customer->vlans_id == $vln->id ? 'selected' : '' }}>{{ $vln->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vlans_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="odcs_id" class="mb-2">Alamat ODC</label>
                            <select name="odcs_id" id="odcs_id"
                                class="form-control @error('odcs_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($odc as $odcs)
                                    <option value="{{ $odcs->id }}"
                                        {{ $customer->odcs_id == $odcs->id ? 'selected' : '' }}>
                                        {{ $odcs->code }} | {{ $odcs->hometown->name }}
                                        | {{ $odcs->rt->name }} | {{ $odcs->rw->name }} |
                                        {{ $odcs->home_odc }}
                                    </option>
                                @endforeach
                            </select>
                            @error('odcs_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="odps_id" class="mb-2">Alamat ODP</label>
                            <select name="odps_id" id="odps_id"
                                class="form-control @error('odps_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($odp as $odps)
                                    <option value="{{ $odps->id }}"
                                        {{ $customer->odps_id == $odps->id ? 'selected' : '' }}>
                                        {{ $odps->code }} | {{ $odps->hometown->name }}
                                        | {{ $odps->rt->name }} | {{ $odps->rw->name }} |
                                        {{ $odps->home_odc }}
                                    </option>
                                @endforeach
                            </select>
                            @error('odps_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="olts_id" class="mb-2">Alamat OLT</label>
                            <select name="olts_id" id="olts_id"
                                class="form-control @error('olts_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($olt as $olts)
                                    <option value="{{ $olts->id }}"
                                        {{ $customer->olts_id == $olts->id ? 'selected' : '' }}>
                                        {{ $olts->hometown->name }} | {{ $olts->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('olts_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <div class="mt-3" id="map-container">
                            <div id="map" style="height: 400px;"></div>
                            <input type="hidden" name="latitude" id="latitude"
                                class="form-control @error('latitude') is-invalid @enderror">
                            <input type="hidden" name="longitude" id="longitude"
                                class="form-control @error('longitude') is-invalid @enderror">
                        </div>
                    </div>
                </div>

                <div class="mt-4 border-top pt-4">
                    <button type="submit" id="btn"
                        class="btn btn-primary d-flex align-items-center float-end px-4">
                        <span id="btn-text">Update Customer</span>
                        <span id="btn-loading" class="spinner-border spinner-border-sm d-none ms-2"
                            role="status"></span>
                    </button>
                    <button type="reset" class="btn btn-outline-secondary float-start">Reset Isi Form</button>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let dbLat = "{{ $customer->latitude ?? '' }}";
            let dbLng = "{{ $customer->longitude ?? '' }}";

            if (dbLat && dbLng) {
                initMap(parseFloat(dbLat), parseFloat(dbLng));
            } else if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    initMap(position.coords.latitude, position.coords.longitude);
                }, function(error) {
                    alert("Gagal mendapatkan lokasi: " + error.message);
                    initMap(-6.200000, 106.816666);
                });
            } else {
                alert("Browser tidak mendukung geolocation");
                initMap(-6.200000, 106.816666);
            }

            function initMap(latitude, longitude) {
                document.getElementById("latitude").value = latitude;
                document.getElementById("longitude").value = longitude;

                let map = L.map('map').setView([latitude, longitude], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                let marker = L.marker([latitude, longitude], {
                    draggable: true
                }).addTo(map);

                marker.on('dragend', function() {
                    let latLng = marker.getLatLng();
                    document.getElementById("latitude").value = latLng.lat.toFixed(6);
                    document.getElementById("longitude").value = latLng.lng.toFixed(6);
                });
            }

            let typeName = "{{ $customer->type->name }}";

            if (typeName === "PPPOE") {
                document.getElementById('pppoe-show').style.display = 'block';
            } else {
                document.getElementById('pppoe-show').style.display = 'none';
            }

            $("#types_id").change(function() {
                let value = $(this).find("option:selected").data("label");
                if (value === "PPPOE") {
                    document.getElementById('pppoe-show').style.display = 'block';
                    $("#type_name").val(value)
                } else {
                    document.getElementById('pppoe-show').style.display = 'none';
                    $("#type_name").val('')
                }
            });
        });
    </script>
    <script>
        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.getElementById('btn');
            const text = document.getElementById('btn-text');
            const loading = document.getElementById('btn-loading');

            btn.disabled = true; // disable button
            text.textContent = 'Loading...';
            loading.classList.remove('d-none');
        });
    </script>
@endpush

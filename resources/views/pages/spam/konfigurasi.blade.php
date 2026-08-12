@extends('layouts.app-pages')

@section('title')
    Konfigurasi Pendaftaran — {{ $pendaftaran->nama }}
@endsection

@push('css')
    <link href="{{asset('')}}css/tabler.min.css?1738096682" rel="stylesheet" />
    <link href="{{asset('')}}css/tabler-flags.min.css?1738096682" rel="stylesheet" />
    <link href="{{asset('')}}css/tabler-socials.min.css?1738096682" rel="stylesheet" />
    <link href="{{asset('')}}css/tabler-payments.min.css?1738096682" rel="stylesheet" />
    <link href="{{asset('')}}css/tabler-vendors.min.css?1738096682" rel="stylesheet" />
    <link href="{{asset('')}}css/tabler-marketing.min.css?1738096682" rel="stylesheet" />
    <link href="{{asset('')}}css/demo.min.css?1738096682" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/modern-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-gradient: linear-gradient(135deg, #2563eb, #1d4ed8);
            --surface-card: #ffffff;
            --border-card: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        body {
            background-color: #f8fafc;
            color: var(--text-dark);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        /* Hero Banner Premium */
        .hero-banner {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 20px;
            padding: 2rem 2.25rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
            border: 1px solid #334155;
            margin-bottom: 2rem;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(37,99,235,0.25) 0%, rgba(37,99,235,0) 70%);
            pointer-events: none;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: rgba(37,99,235,0.2);
            color: #60a5fa;
            border: 1px solid rgba(96,165,250,0.3);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* Multi-Step Wizard Progress Bar */
        .wizard-steps-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            margin-bottom: 2.25rem;
            padding: 0 0.5rem;
        }

        .wizard-steps-container::before {
            content: '';
            position: absolute;
            top: 24px;
            left: 10%;
            right: 10%;
            height: 4px;
            background: #e2e8f0;
            z-index: 1;
            border-radius: 2px;
        }

        .wizard-progress-bar {
            position: absolute;
            top: 24px;
            left: 10%;
            height: 4px;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            z-index: 2;
            border-radius: 2px;
            transition: width 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0%;
        }

        .wizard-step-item {
            position: relative;
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            user-select: none;
        }

        .wizard-step-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #ffffff;
            border: 3px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.05rem;
            color: #64748b;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .wizard-step-item.active .wizard-step-circle {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-color: #93c5fd;
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
            transform: scale(1.1);
        }

        .wizard-step-item.completed .wizard-step-circle {
            background: #10b981;
            border-color: #a7f3d0;
            color: #ffffff;
        }

        .wizard-step-label {
            margin-top: 0.6rem;
            font-size: 0.82rem;
            font-weight: 700;
            color: #64748b;
            text-align: center;
            transition: color 0.3s ease;
        }

        .wizard-step-item.active .wizard-step-label {
            color: #2563eb;
        }

        .wizard-step-item.completed .wizard-step-label {
            color: #059669;
        }

        /* Step Card Section */
        .step-panel {
            display: none;
            animation: fadeIn 0.35s ease;
        }

        .step-panel.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .config-card {
            background: var(--surface-card);
            border: 1px solid var(--border-card);
            border-radius: 18px;
            box-shadow: 0 6px 25px rgba(15, 23, 42, 0.04);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .config-card-header {
            padding: 1.35rem 1.75rem;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .config-card-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .config-card-title .icon-box {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .config-card-body {
            padding: 2rem 1.75rem;
        }

        /* Form Controls UI Upgrade */
        .form-label-custom {
            font-size: 0.78rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.45rem;
            display: block;
        }

        .form-control-custom, .ts-wrapper .ts-control {
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 10px !important;
            padding: 0.65rem 0.95rem !important;
            font-size: 0.92rem !important;
            font-weight: 500;
            color: #0f172a !important;
            background-color: #ffffff !important;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12) !important;
            outline: none !important;
        }

        .form-control-readonly {
            background-color: #f8fafc !important;
            color: #64748b !important;
            font-family: monospace;
            font-weight: 700;
        }

        .sub-section-divider {
            border: 0;
            height: 1px;
            background: linear-gradient(90deg, #e2e8f0, #cbd5e1, #e2e8f0);
            margin: 1.5rem 0;
        }

        /* Wizard Footer Controls */
        .wizard-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1.5rem;
            padding-top: 1rem;
        }

        .btn-wizard-nav {
            font-weight: 700;
            font-size: 0.92rem;
            padding: 0.75rem 1.75rem;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-wizard-prev {
            background: #ffffff;
            color: #475569;
            border: 1.5px solid #cbd5e1;
        }

        .btn-wizard-prev:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
        }

        .btn-wizard-next {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            border: none;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }

        .btn-wizard-next:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.45);
            color: #ffffff;
        }

        .btn-wizard-submit {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            border: none;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        }

        .btn-wizard-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
            color: #ffffff;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">

                <!-- Hero Header Banner -->
                <div class="hero-banner">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 rounded-3" style="background: rgba(37, 99, 235, 0.25); border: 1px solid rgba(96,165,250,0.3);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="hero-badge">Wizard Konfigurasi</span>
                                    <span class="badge bg-blue-subtle text-blue border border-blue-subtle fw-mono">{{ $pendaftaran->kode }}</span>
                                </div>
                                <h3 class="fw-extrabold text-white mb-0" style="letter-spacing: -0.01em;">Konfigurasi Pendaftaran Online</h3>
                                <p class="text-slate-300 small mb-0 mt-1" style="color: #94a3b8;">
                                    Pemohon: <strong class="text-white">{{ $pendaftaran->nama }}</strong> &bull; Halaman: <span class="text-info">{{ $pages->name }}</span>
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('spam.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 py-2 fw-bold" style="border-color: rgba(255,255,255,0.2);">
                            &larr; Kembali ke List
                        </a>
                    </div>
                </div>

                <!-- Wizard Progress Navigation -->
                <div class="wizard-steps-container">
                    <div class="wizard-progress-bar" id="wizard-progress"></div>

                    <div class="wizard-step-item active" onclick="goToStep(1)">
                        <div class="wizard-step-circle" id="circle-1">1</div>
                        <div class="wizard-step-label">Data Pelanggan</div>
                    </div>

                    <div class="wizard-step-item" onclick="goToStep(2)">
                        <div class="wizard-step-circle" id="circle-2">2</div>
                        <div class="wizard-step-label">Paket & Layanan</div>
                    </div>

                    <div class="wizard-step-item" onclick="goToStep(3)">
                        <div class="wizard-step-circle" id="circle-3">3</div>
                        <div class="wizard-step-label">Alamat & Lokasi</div>
                    </div>

                    <div class="wizard-step-item" onclick="goToStep(4)">
                        <div class="wizard-step-circle" id="circle-4">4</div>
                        <div class="wizard-step-label">Jaringan & Device</div>
                    </div>
                </div>

                <form action="{{ route('input.data.saveCustomerToSpan') }}" method="POST" id="config-form">
                    @csrf
                    <input type="hidden" name="status" id="status" value="spam">
                    <input type="hidden" name="pendaftaran_id" id="pendaftaran_id" value="{{ $pendaftaran->id }}">
                    <input type="hidden" name="wa_phone" id="wa_phone" value="{{ $pages->telp }}">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="is_ktp" id="is_ktp" value="{{ $pages->is_ktp }}">

                    @include('components.alert.success')

                    @if (session()->has('error'))
                        <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4">
                            <strong>Gagal:</strong> {{ session()->get('error') }}
                        </div>
                    @endif

                    <!-- STEP 1: DATA PELANGGAN -->
                    <div class="step-panel active" id="step-panel-1">
                        <div class="config-card">
                            <div class="config-card-header">
                                <div class="config-card-title">
                                    <div class="icon-box" style="background: #eff6ff; color: #2563eb;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                    <span>Langkah 1: Informasi Data Pelanggan</span>
                                </div>
                                <span class="badge bg-blue-subtle text-blue px-3 py-1 rounded-pill">Langkah 1 / 4</span>
                            </div>
                            <div class="config-card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label-custom">ID Pelanggan (Otomatis Generator)</label>
                                        <input type="text" value="{{ $newCode }}" class="form-control form-control-custom form-control-readonly" readonly>
                                        <input type="hidden" name="uuid" id="uuid" value="{{ $newCode }}">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label-custom">Tipe Pelanggan <span class="text-danger">*</span></label>
                                        @if ($tipePelanggan->count() === 1)
                                            @php $singleTpl = $tipePelanggan->first(); @endphp
                                            <select name="tipe_pelanggan_id" id="tipe_pelanggan_id" class="form-control form-control-custom">
                                                <option value="{{ $singleTpl->id }}" selected>{{ $singleTpl->name }}</option>
                                            </select>
                                        @else
                                            <select name="tipe_pelanggan_id" id="tipe_pelanggan_id" class="form-control form-control-custom select-tom">
                                                <option value="">-- Pilih Tipe Pelanggan --</option>
                                                @foreach ($tipePelanggan as $tpl)
                                                    <option data-label="{{ $tpl->name }}" value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Nama Lengkap Pelanggan <span class="text-danger">*</span></label>
                                        <input value="{{ old('name', $pendaftaran->nama) }}" type="text" name="name" id="name" class="form-control form-control-custom" placeholder="Nama pemohon pendaftaran">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Email Pelanggan</label>
                                        <input value="{{ old('email', $pendaftaran->email) }}" type="text" name="email" id="email" class="form-control form-control-custom" placeholder="contoh@email.com">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Nomor Telepon / WA <span class="text-danger">*</span></label>
                                        <input value="{{ old('telp', $pendaftaran->no_telepon) }}" type="text" name="telp" id="telp" class="form-control form-control-custom" placeholder="08xxxxxxxxxx">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">MAC Address Modem / Router</label>
                                        <input value="{{ old('mac_address') }}" type="text" name="mac_address" id="mac_address" class="form-control form-control-custom" placeholder="XX:XX:XX:XX:XX:XX" autocomplete="off">
                                        <small id="macFeedback" class="d-block mt-1" style="font-size: 0.8rem; font-weight: 600;"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: KONFIGURASI LAYANAN -->
                    <div class="step-panel" id="step-panel-2">
                        <div class="config-card">
                            <div class="config-card-header">
                                <div class="config-card-title">
                                    <div class="icon-box" style="background: #f3e8ff; color: #7c3aed;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
                                    </div>
                                    <span>Langkah 2: Konfigurasi Paket & Layanan</span>
                                </div>
                                <span class="badge bg-purple-subtle text-purple px-3 py-1 rounded-pill">Langkah 2 / 4</span>
                            </div>
                            <div class="config-card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Tipe Layanan Internet <span class="text-danger">*</span></label>
                                        <select name="types_id" id="types_id" class="form-control form-control-custom">
                                            <option value="">-- Pilih Tipe Layanan --</option>
                                            @foreach ($types as $tp)
                                                @php $pTipeId = optional($pendaftaran->tipeLayanan ?? $pendaftaran->tipe_layanan)->id; @endphp
                                                <option data-label="{{ $tp->name }}" value="{{ $tp->id }}" {{ $pTipeId == $tp->id ? 'selected' : '' }}>
                                                    {{ $tp->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="type_name" id="type_name">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Jenis Router Terpasang</label>
                                        <input type="text" name="router_name" id="router_name" class="form-control form-control-custom form-control-readonly" disabled placeholder="Terisi otomatis berdasarkan tipe">
                                        <input type="hidden" name="routers_id" id="routers_id">
                                    </div>
                                </div>

                                <div class="sub-section-divider"></div>

                                <div id="pppoe-show">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div style="width: 8px; height: 18px; background: #2563eb; border-radius: 4px;"></div>
                                        <h6 class="fw-bold text-dark mb-0">Parameter Konfigurasi PPPOE</h6>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Nama WiFi (SSID)</label>
                                            <input type="text" name="name_wifi" id="name_wifi" value="{{ old('name_wifi', $pendaftaran->name_wifi) }}" class="form-control form-control-custom" placeholder="Nama SSID WiFi Pelanggan">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label-custom">Password WiFi</label>
                                            <input type="text" name="password_wifi" id="password_wifi" value="{{ old('password_wifi', $pendaftaran->password_wifi) }}" class="form-control form-control-custom" placeholder="Password WPA WiFi">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label-custom">Pilihan Tipe Paket</label>
                                            <select name="paket_id" id="paket_id" class="form-control form-control-custom">
                                                <option value="">-- Pilih Paket --</option>
                                                @foreach ($paket as $pkt)
                                                    <option value="{{ $pkt->id }}" {{ $pendaftaran->paket_id == $pkt->id ? 'selected' : '' }}>{{ $pkt->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label-custom">Profile Mix Radius</label>
                                            <select name="mic_radius_id" id="mic_radius_id" class="form-control form-control-custom">
                                                <option value="">-- Pilih Mix Radius --</option>
                                                @foreach ($micRadius as $mc)
                                                    <option value="{{ $mc->id }}">{{ $mc->code }} &ndash; {{ $mc->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label-custom">Skema Pembayaran</label>
                                            <select name="price_id" id="price_id" class="form-control form-control-custom">
                                                <option value="">-- Pilih Tipe Pembayaran --</option>
                                                @foreach ($price as $prc)
                                                    <option value="{{ $prc->id }}" {{ $pendaftaran->price_id == $prc->id ? 'selected' : '' }}>{{ $prc->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: ALAMAT & LOKASI -->
                    <div class="step-panel" id="step-panel-3">
                        <div class="config-card">
                            <div class="config-card-header">
                                <div class="config-card-title">
                                    <div class="icon-box" style="background: #f0fdf4; color: #16a34a;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    </div>
                                    <span>Langkah 3: Alamat Wilayah & Pemasangan</span>
                                </div>
                                <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill">Langkah 3 / 4</span>
                            </div>
                            <div class="config-card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label-custom">Kampung / Dusun</label>
                                        <select name="hometowns_id" id="hometowns_id" class="form-control form-control-custom">
                                            <option value="{{ $pages->hometowns_id }}">{{ $pendaftaran->hometown ? $pendaftaran->hometown->name : $pages->hometown->name }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">RT</label>
                                        <select name="rts_id" id="rts_id" class="form-control form-control-custom">
                                            <option value="">-- Pilih RT --</option>
                                            @foreach ($rts as $rt)
                                                <option value="{{ $rt->id }}">{{ $rt->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">RW</label>
                                        <select name="rws_id" id="rws_id" class="form-control form-control-custom">
                                            <option value="">-- Pilih RW --</option>
                                            @foreach ($rws as $rw)
                                                <option value="{{ $rw->id }}">{{ $rw->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label-custom">Desa / Kelurahan</label>
                                        <select name="villages_id" id="villages_id" class="form-control form-control-custom">
                                            <option value="{{ $pages->villages_id }}">{{ $pendaftaran->village ? $pendaftaran->village->name : $pages->village->name }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Kabupaten / Kota</label>
                                        <select name="regencies_id" id="regencies_id" class="form-control form-control-custom">
                                            <option value="{{ $pages->regencies_id }}">{{ $pages->regencie->name }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Kecamatan</label>
                                        <select name="districts_id" id="districts_id" class="form-control form-control-custom">
                                            <option value="{{ $pages->districts_id }}">{{ $pages->district->name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 4: INFRASTRUKTUR JARINGAN -->
                    <div class="step-panel" id="step-panel-4">
                        <div class="config-card">
                            <div class="config-card-header">
                                <div class="config-card-title">
                                    <div class="icon-box" style="background: #fff7ed; color: #ea580c;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
                                    </div>
                                    <span>Langkah 4: Alokasi Perangkat & Jaringan</span>
                                </div>
                                <span class="badge bg-warning-subtle text-warning px-3 py-1 rounded-pill">Langkah 4 / 4</span>
                            </div>
                            <div class="config-card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Pilihan VLAN Network</label>
                                        <select name="vlans_id" id="vlans_id" class="form-control form-control-custom">
                                            <option value="">-- Pilih VLAN --</option>
                                            @foreach ($vlans as $vln)
                                                <option value="{{ $vln->id }}" data-label="{{ $vln->name }}">{{ $vln->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Alokasi ODC (Optical Distribution Center)</label>
                                        <select name="odcs_id" id="odcs_id" class="form-control form-control-custom">
                                            <option value="">-- Pilih ODC --</option>
                                            @foreach ($odcs as $odc)
                                                <option value="{{ $odc->id }}">{{ $odc->code }} &bull; {{ $odc->odc_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Alokasi ODP (Optical Distribution Point)</label>
                                        <select name="odps_id" id="odps_id" class="form-control form-control-custom">
                                            <option value="">-- Pilih ODP --</option>
                                            @foreach ($odps as $odp)
                                                <option value="{{ $odp->id }}">{{ $odp->code }} &bull; {{ $odp->odp_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Alokasi OLT (Optical Line Terminal)</label>
                                        <select name="olts_id" id="olts_id" class="form-control form-control-custom">
                                            <option value="">-- Pilih OLT --</option>
                                            @foreach ($olts as $olt)
                                                <option value="{{ $olt->id }}">{{ $olt->code }} &bull; {{ $olt->olt_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Ukuran Patch Core <span class="text-danger">*</span></label>
                                        @if ($pathCore->count() === 1)
                                            @php $singlePc = $pathCore->first(); @endphp
                                            <select name="patch_core_id" id="patch_core_id" class="form-control form-control-custom">
                                                <option value="{{ $singlePc->id }}" selected>{{ $singlePc->name }}</option>
                                            </select>
                                        @else
                                            <select name="patch_core_id" id="patch_core_id" class="form-control form-control-custom">
                                                <option value="">-- Pilih Ukuran Patch Core --</option>
                                                @foreach ($pathCore as $pc)
                                                    <option value="{{ $pc->id }}">{{ $pc->name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>

                                <hr class="mt-4">
                                <div class="d-flex align-items-center gap-2 mb-3 mt-2">
                                    <div style="width:8px;height:18px;background:#16a34a;border-radius:4px;"></div>
                                    <h6 class="fw-bold text-dark mb-0">Lokasi Pemasangan (GPS)</h6>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Latitude</label>
                                        <input type="text" id="latitude_display" class="form-control form-control-custom form-control-readonly" placeholder="Mendeteksi lokasi..." readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Longitude</label>
                                        <input type="text" id="longitude_display" class="form-control form-control-custom form-control-readonly" placeholder="Mendeteksi lokasi..." readonly>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-4" onclick="detectLocation()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="me-1"><circle cx="12" cy="12" r="3"/><path d="M12 2v2m0 16v2M2 12h2m16 0h2"/></svg>
                                            Deteksi Lokasi Saat Ini
                                        </button>
                                        <small class="text-muted ms-2 d-block mt-1" style="font-size:0.78rem;">Izinkan akses lokasi jika diminta browser</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER WIZARD CONTROLS -->
                    <div class="wizard-footer">
                        <button type="button" class="btn-wizard-nav btn-wizard-prev" id="btn-prev" onclick="changeStep(-1)" style="visibility: hidden;">
                            &larr; Sebelumnya
                        </button>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn-wizard-nav btn-wizard-next" id="btn-next" onclick="changeStep(1)">
                                Selanjutnya &rarr;
                            </button>
                            <button type="submit" class="btn-wizard-nav btn-wizard-submit" id="btn-submit" style="display: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><polyline points="20 6 9 17 4 12"/></svg>
                                Simpan Konfigurasi
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let currentWizardStep = 1;
        const totalWizardSteps = 4;

        function updateWizardUI() {
            // Update panels
            for (let i = 1; i <= totalWizardSteps; i++) {
                const panel = document.getElementById(`step-panel-${i}`);
                const circle = document.getElementById(`circle-${i}`);
                const stepItem = circle.closest('.wizard-step-item');

                if (i === currentWizardStep) {
                    panel.classList.add('active');
                    stepItem.classList.add('active');
                    stepItem.classList.remove('completed');
                } else if (i < currentWizardStep) {
                    panel.classList.remove('active');
                    stepItem.classList.remove('active');
                    stepItem.classList.add('completed');
                    circle.innerHTML = '&#10003;';
                } else {
                    panel.classList.remove('active');
                    stepItem.classList.remove('active');
                    stepItem.classList.remove('completed');
                    circle.innerHTML = i;
                }
            }

            // Update Progress Bar Width
            const progressPercentage = ((currentWizardStep - 1) / (totalWizardSteps - 1)) * 80;
            document.getElementById('wizard-progress').style.width = `${progressPercentage}%`;

            // Update Nav Buttons
            const btnPrev = document.getElementById('btn-prev');
            const btnNext = document.getElementById('btn-next');
            const btnSubmit = document.getElementById('btn-submit');

            btnPrev.style.visibility = currentWizardStep === 1 ? 'hidden' : 'visible';

            if (currentWizardStep === totalWizardSteps) {
                btnNext.style.display = 'none';
                btnSubmit.style.display = 'inline-flex';
            } else {
                btnNext.style.display = 'inline-flex';
                btnSubmit.style.display = 'none';
            }

            window.scrollTo({ top: 150, behavior: 'smooth' });
        }

        function changeStep(delta) {
            const nextStep = currentWizardStep + delta;
            if (nextStep >= 1 && nextStep <= totalWizardSteps) {
                currentWizardStep = nextStep;
                updateWizardUI();
                // Auto-deteksi lokasi saat masuk step 4
                if (nextStep === 4 && navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(pos) {
                        document.getElementById('latitude').value = pos.coords.latitude;
                        document.getElementById('longitude').value = pos.coords.longitude;
                        const latDisplay = document.getElementById('latitude_display');
                        const lngDisplay = document.getElementById('longitude_display');
                        if (latDisplay) latDisplay.value = pos.coords.latitude;
                        if (lngDisplay) lngDisplay.value = pos.coords.longitude;
                    });
                }
            }
        }

        function goToStep(step) {
            if (step >= 1 && step <= totalWizardSteps) {
                currentWizardStep = step;
                updateWizardUI();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateWizardUI();

            const macInput = document.getElementById('mac_address');
            if (macInput) {
                macInput.addEventListener('blur', function() {
                    const macVal = this.value.trim();
                    if (!macVal) return;

                    fetch("{{ route('input.data.checkMacAddress') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ mac_address: macVal })
                    })
                    .then(res => res.json())
                    .then(data => {
                        const feedback = document.getElementById('macFeedback');
                        if (feedback) {
                            feedback.textContent = data.message || '';
                            feedback.style.color = data.valid ? '#16a34a' : '#dc2626';
                        }

                        let routerName = document.getElementById('router_name');
                        let routersId = document.getElementById('routers_id');

                        if (data.valid && data.router) {
                            if (routerName) routerName.value = data.router.name || '';
                            if (routersId) routersId.value = data.router.id || '';
                        } else {
                            if (routerName) routerName.value = '';
                            if (routersId) routersId.value = '';
                        }
                    })
                    .catch(err => {
                        console.error("Error checking MAC address:", err);
                    });
                });
            }
        });

        // Deteksi lokasi GPS
        function detectLocation() {
            if (!navigator.geolocation) {
                alert('Browser tidak mendukung geolocation.');
                return;
            }
            navigator.geolocation.getCurrentPosition(function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                const latDisplay = document.getElementById('latitude_display');
                const lngDisplay = document.getElementById('longitude_display');
                if (latDisplay) latDisplay.value = lat;
                if (lngDisplay) lngDisplay.value = lng;
            }, function(err) {
                alert('Gagal mendeteksi lokasi: ' + err.message);
            });
        }

        // Auto-detect GPS saat user masuk ke step 4
        const originalUpdateWizardUI = window.updateWizardUICallback || null;
        document.addEventListener('DOMContentLoaded', function() {
            const origChange = window.changeStep;
        });

        // Override goToStep to auto-trigger GPS on step 4
        const _origGoToStep = window.goToStep;
        window.goToStepWithGPS = function(step) {
            goToStep(step);
            if (step === 4 && navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(pos) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    const latDisplay = document.getElementById('latitude_display');
                    const lngDisplay = document.getElementById('longitude_display');
                    if (latDisplay) latDisplay.value = lat;
                    if (lngDisplay) lngDisplay.value = lng;
                });
            }
        };
    </script>
@endpush

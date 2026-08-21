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

        .form-control-custom.is-invalid, .ts-wrapper.is-invalid .ts-control {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
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

        /* ── KTP Camera Section ─────────────────────────────── */
        .ktp-section-card {
            background: #ffffff;
            border: 2px dashed #2563eb;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .ktp-section-header {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #bfdbfe;
        }

        .ktp-section-header .ktp-icon {
            width: 36px;
            height: 36px;
            background: #2563eb;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .ktp-section-header .ktp-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #1e40af;
            margin: 0;
        }

        .ktp-section-header .ktp-badge-required {
            margin-left: auto;
            background: #fee2e2;
            color: #dc2626;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .ktp-section-body {
            padding: 1.5rem;
        }

        .ktp-camera-container {
            position: relative;
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            background: #0f172a;
            border-radius: 14px;
            overflow: hidden;
            aspect-ratio: 16/9;
        }

        .ktp-camera-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .ktp-guide-frame {
            position: absolute;
            top: 10%;
            left: 5%;
            right: 5%;
            bottom: 10%;
            border: 2.5px solid rgba(255, 255, 255, 0.7);
            border-radius: 8px;
            pointer-events: none;
        }

        .ktp-guide-frame::before,
        .ktp-guide-frame::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border-color: #60a5fa;
            border-style: solid;
        }

        .ktp-guide-frame::before {
            top: -2px;
            left: -2px;
            border-width: 3px 0 0 3px;
            border-radius: 4px 0 0 0;
        }

        .ktp-guide-frame::after {
            bottom: -2px;
            right: -2px;
            border-width: 0 3px 3px 0;
            border-radius: 0 0 4px 0;
        }

        .ktp-canvas { display: none; }

        .ktp-photo-preview {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            border-radius: 14px;
            overflow: hidden;
            display: none;
        }

        .ktp-photo-preview img {
            width: 100%;
            display: block;
            border-radius: 14px;
        }

        .ktp-camera-loading {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(15,23,42,0.85);
            color: #fff;
            font-size: 0.88rem;
            gap: 10px;
            z-index: 5;
        }

        .ktp-permission-alert {
            background: #1e293b;
            color: #e2e8f0;
            padding: 2rem;
            text-align: center;
            border-radius: 14px;
        }

        .ktp-permission-alert .pa-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
        .ktp-permission-alert h5 { color: #f1f5f9; font-weight: 700; margin-bottom: 0.5rem; }
        .ktp-permission-alert p { font-size: 0.85rem; color: #94a3b8; margin-bottom: 1rem; }

        .ktp-btn-capture,
        .ktp-btn-retake {
            font-weight: 700;
            font-size: 0.88rem;
            padding: 0.65rem 1.5rem;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .ktp-btn-capture {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            box-shadow: 0 4px 12px rgba(37,99,235,0.35);
        }

        .ktp-btn-capture:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37,99,235,0.45);
        }

        .ktp-btn-retake {
            background: #f1f5f9;
            color: #475569;
            border: 1.5px solid #cbd5e1;
        }

        .ktp-btn-retake:hover {
            background: #e2e8f0;
        }

        .ktp-ocr-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            margin-top: 0.5rem;
        }

        .ktp-ocr-status.processing {
            background: #fef3c7;
            color: #92400e;
        }

        .ktp-ocr-status.success {
            background: #d1fae5;
            color: #065f46;
        }

        .ktp-ocr-status.failed {
            background: #fee2e2;
            color: #991b1b;
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

                    @if (session()->has('warning'))
                        <div class="alert alert-warning shadow-sm border-0 rounded-3 mb-4">
                            <strong>Peringatan:</strong> {{ session()->get('warning') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <strong>Terdapat Kesalahan Validasi:</strong>
                            </div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
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
                                                    <option data-label="{{ $tpl->name }}" value="{{ $tpl->id }}" {{ old('tipe_pelanggan_id') == $tpl->id ? 'selected' : '' }}>{{ $tpl->name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Nama Lengkap Pelanggan <span class="text-danger">*</span></label>
                                        <input value="{{ old('name', $pendaftaran->nama) }}" type="text" name="name" id="name" class="form-control form-control-custom" placeholder="Nama pemohon pendaftaran">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Email Pelanggan <span class="text-danger">*</span></label>
                                        <input value="{{ old('email', $pendaftaran->email) }}" type="email" name="email" id="email" class="form-control form-control-custom" placeholder="contoh@email.com">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Nomor Telepon / WA <span class="text-danger">*</span></label>
                                        <input value="{{ old('telp', $pendaftaran->no_telepon) }}" type="text" name="telp" id="telp" class="form-control form-control-custom" placeholder="08xxxxxxxxxx">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">MAC Address Modem / Router <span class="text-danger">*</span></label>
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
                                                @php $pTipeId = old('types_id', optional($pendaftaran->tipeLayanan ?? $pendaftaran->tipe_layanan)->id); @endphp
                                                <option data-label="{{ $tp->name }}" value="{{ $tp->id }}" {{ $pTipeId == $tp->id ? 'selected' : '' }}>
                                                    {{ $tp->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="type_name" id="type_name">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Jenis Router Terpasang <span class="text-danger">*</span></label>
                                        <div id="router_autodetect_wrap">
                                            <input type="text" name="router_name" id="router_name" class="form-control form-control-custom form-control-readonly" readonly placeholder="Terisi otomatis berdasarkan MAC Address">
                                            <input type="hidden" name="routers_id" id="routers_id" value="{{ old('routers_id') }}">
                                        </div>
                                        <div id="router_manual_wrap" class="mt-2" style="{{ old('routers_id') ? 'display: block;' : 'display: none;' }}">
                                            <small class="text-muted d-block mb-1" style="font-size: 0.78rem; font-weight: 600;">Atau pilih router manual jika tidak terdeteksi otomatis:</small>
                                            <select id="manual_router_select" class="form-control form-control-custom">
                                                <option value="">-- Pilih Router Tersedia --</option>
                                                @foreach ($routers as $rtr)
                                                    @php $rtrId = $rtr->routers_id ?? $rtr->id; @endphp
                                                    <option value="{{ $rtrId }}" data-name="{{ $rtr->name ?? $rtr->code }}" {{ old('routers_id') == $rtrId ? 'selected' : '' }}>
                                                        {{ $rtr->name ?? $rtr->code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
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
                                            <label class="form-label-custom">Nama WiFi (SSID) <span class="text-danger">*</span></label>
                                            <input type="text" name="name_wifi" id="name_wifi" value="{{ old('name_wifi', $pendaftaran->name_wifi) }}" class="form-control form-control-custom" placeholder="Nama SSID WiFi Pelanggan">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label-custom">Password WiFi <span class="text-danger">*</span></label>
                                            <input type="text" name="password_wifi" id="password_wifi" value="{{ old('password_wifi', $pendaftaran->password_wifi) }}" class="form-control form-control-custom" placeholder="Password WPA WiFi (Min. 8 karakter)">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label-custom">Pilihan Tipe Paket <span class="text-danger">*</span></label>
                                            <select name="paket_id" id="paket_id" class="form-control form-control-custom">
                                                <option value="">-- Pilih Paket --</option>
                                                @foreach ($paket as $pkt)
                                                    <option value="{{ $pkt->id }}" {{ $pendaftaran->paket_id == $pkt->id ? 'selected' : '' }}>{{ $pkt->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label-custom">Profile Mix Radius <span class="text-danger">*</span></label>
                                            <select name="mic_radius_id" id="mic_radius_id" class="form-control form-control-custom">
                                                <option value="">-- Pilih Mix Radius --</option>
                                                @foreach ($micRadius as $mc)
                                                    <option value="{{ $mc->id }}">{{ $mc->code }} &ndash; {{ $mc->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label-custom">Skema Pembayaran <span class="text-danger">*</span></label>
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

                        @if ($pages->is_ktp === 'aktif')
                        {{-- ── KTP Camera Section ── --}}
                        <div class="ktp-section-card">
                            <div class="ktp-section-header">
                                <div class="ktp-icon">🪪</div>
                                <p class="ktp-title">Foto KTP Pelanggan</p>
                                <span class="ktp-badge-required">Wajib</span>
                            </div>
                            <div class="ktp-section-body">
                                <p class="text-muted small mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    Posisikan KTP di dalam bingkai panduan, lalu tekan <strong>Ambil Foto</strong>. Sistem akan otomatis membaca NIK dari foto.
                                </p>

                                {{-- Camera View --}}
                                <div id="ktp-camera-wrap" class="ktp-camera-container mb-3">
                                    <video id="ktp-video" autoplay playsinline style="width:100%;height:100%;object-fit:cover;"></video>
                                    <div class="ktp-guide-frame"></div>
                                </div>

                                <canvas id="ktp-canvas" class="ktp-canvas"></canvas>
                                <input type="hidden" name="ktp_photo" id="ktp_photo">

                                {{-- Photo Preview --}}
                                <div class="ktp-photo-preview" id="ktp-photo-preview">
                                    <img id="ktp-captured-img" src="" alt="Foto KTP">
                                </div>

                                {{-- OCR Status Badge --}}
                                <div id="ktp-ocr-status" style="display:none;" class="ktp-ocr-status processing">
                                    <span class="spinner-border spinner-border-sm" style="width:.75rem;height:.75rem;border-width:2px;"></span>
                                    Membaca NIK dari foto...
                                </div>

                                {{-- Buttons --}}
                                <div class="text-center mt-3 d-flex justify-content-center gap-2">
                                    <button type="button" id="ktp-btn-capture" class="ktp-btn-capture" onclick="ktpCapturePhoto()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 7.828 3h.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 11.828 5H14a1 1 0 0 1 1 1zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4z"/><path d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5m0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/></svg>
                                        Ambil Foto KTP
                                    </button>
                                    <button type="button" id="ktp-btn-retake" class="ktp-btn-retake" onclick="ktpRetakePhoto()" style="display:none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/><path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/></svg>
                                        Ulangi Foto
                                    </button>
                                </div>

                                {{-- NIK Input (auto-filled) --}}
                                <div class="row mt-3">
                                    <div class="col-md-8 offset-md-2">
                                        <label class="form-label-custom">NIK <span class="text-danger">*</span>
                                            <span class="ms-1 text-info" style="font-size:0.7rem;font-weight:600;text-transform:none;">(Terisi otomatis dari OCR)</span>
                                        </label>
                                        <input type="text" name="nik" id="nik"
                                            class="form-control form-control-custom @error('nik') is-invalid @enderror"
                                            placeholder="NIK akan terisi otomatis setelah foto diambil"
                                            value="{{ old('nik') }}"
                                            maxlength="16">
                                        @error('nik')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        {{-- Jika KTP tidak aktif, tetap kirim nik dan ktp_photo sebagai nullable --}}
                        <input type="hidden" name="nik" id="nik" value="">
                        <input type="hidden" name="ktp_photo" id="ktp_photo" value="">
                        @endif

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
                                        <label class="form-label-custom">Kampung / Dusun <span class="text-danger">*</span></label>
                                        <select name="hometowns_id" id="hometowns_id" class="form-control form-control-custom">
                                            <option value="{{ $pages->hometowns_id }}">{{ $pendaftaran->hometown ? $pendaftaran->hometown->name : $pages->hometown->name }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">RT <span class="text-danger">*</span></label>
                                        <select name="rts_id" id="rts_id" class="form-control form-control-custom">
                                            <option value="">-- Pilih RT --</option>
                                            @foreach ($rts as $rt)
                                                <option value="{{ $rt->id }}">{{ $rt->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">RW <span class="text-danger">*</span></label>
                                        <select name="rws_id" id="rws_id" class="form-control form-control-custom">
                                            <option value="">-- Pilih RW --</option>
                                            @foreach ($rws as $rw)
                                                <option value="{{ $rw->id }}">{{ $rw->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label-custom">Desa / Kelurahan <span class="text-danger">*</span></label>
                                        <select name="villages_id" id="villages_id" class="form-control form-control-custom">
                                            <option value="{{ $pages->villages_id }}">{{ $pendaftaran->village ? $pendaftaran->village->name : $pages->village->name }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Kabupaten / Kota <span class="text-danger">*</span></label>
                                        <select name="regencies_id" id="regencies_id" class="form-control form-control-custom">
                                            <option value="{{ $pages->regencies_id }}">{{ $pages->regencie->name }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Kecamatan <span class="text-danger">*</span></label>
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
                                        <label class="form-label-custom">Pilihan VLAN Network <span class="text-danger">*</span></label>
                                        <select name="vlans_id" id="vlans_id" class="form-control form-control-custom">
                                            <option value="">-- Pilih VLAN --</option>
                                            @foreach ($vlans as $vln)
                                                <option value="{{ $vln->id }}" data-label="{{ $vln->name }}">{{ $vln->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Alokasi ODC (Optical Distribution Center) <span class="text-danger">*</span></label>
                                        <select name="odcs_id" id="odcs_id" class="form-control form-control-custom">
                                            <option value="">-- Pilih ODC --</option>
                                            @foreach ($odcs as $odc)
                                                <option value="{{ $odc->id }}">{{ $odc->code }} &bull; {{ $odc->odc_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Alokasi ODP (Optical Distribution Point) <span class="text-danger">*</span></label>
                                        <select name="odps_id" id="odps_id" class="form-control form-control-custom">
                                            <option value="">-- Pilih ODP --</option>
                                            @foreach ($odps as $odp)
                                                <option value="{{ $odp->id }}">{{ $odp->code }} &bull; {{ $odp->odp_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Alokasi OLT (Optical Line Terminal) <span class="text-danger">*</span></label>
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
                                <span id="btn-submit-text">Simpan Konfigurasi</span>
                                <span id="btn-submit-loading" class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        let currentWizardStep = 1;
        const totalWizardSteps = 4;
        const isKtpAktif = "{{ $pages->is_ktp }}" === 'aktif';

        // ── KTP Camera & OCR State ──────────────────────────────
        let ktpStream = null;
        let ktpVideo = null;
        let ktpCanvas = null;
        let ktpCapturedImg = null;
        let ktpPhotoPreview = null;
        let ktpCameraWrap = null;
        let ktpBtnCapture = null;
        let ktpBtnRetake = null;
        let ktpPhotoInput = null;
        let ktpNikInput = null;
        let ktpOcrStatus = null;

        function initKtpElements() {
            ktpVideo        = document.getElementById('ktp-video');
            ktpCanvas       = document.getElementById('ktp-canvas');
            ktpCapturedImg  = document.getElementById('ktp-captured-img');
            ktpPhotoPreview = document.getElementById('ktp-photo-preview');
            ktpCameraWrap   = document.getElementById('ktp-camera-wrap');
            ktpBtnCapture   = document.getElementById('ktp-btn-capture');
            ktpBtnRetake    = document.getElementById('ktp-btn-retake');
            ktpPhotoInput   = document.getElementById('ktp_photo');
            ktpNikInput     = document.getElementById('nik');
            ktpOcrStatus    = document.getElementById('ktp-ocr-status');
        }

        async function startKtpCamera() {
            if (!isKtpAktif) return;
            initKtpElements();
            if (!ktpVideo || !ktpCameraWrap) return;
            if (ktpPhotoInput && ktpPhotoInput.value) return; // Sudah ada foto

            // Hentikan stream sebelumnya jika ada
            stopKtpCamera();

            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'ktp-camera-loading';
            loadingDiv.id = 'ktp-cam-loader';
            loadingDiv.innerHTML = '<div class="spinner-border text-light mb-2"></div><div>Memuat kamera...</div>';
            ktpCameraWrap.appendChild(loadingDiv);

            try {
                const constraints = {
                    video: {
                        facingMode: 'environment',
                        width: { ideal: 1920, min: 1280 },
                        height: { ideal: 1080, min: 720 },
                        aspectRatio: { ideal: 16 / 9 }
                    },
                    audio: false
                };

                ktpStream = await navigator.mediaDevices.getUserMedia(constraints);
                ktpVideo.srcObject = ktpStream;

                ktpVideo.addEventListener('loadedmetadata', function() {
                    const loader = document.getElementById('ktp-cam-loader');
                    if (loader) loader.remove();
                }, { once: true });
            } catch (err) {
                console.error("Gagal membuka kamera:", err);
                const loader = document.getElementById('ktp-cam-loader');
                if (loader) {
                    loader.innerHTML = `
                        <div class="p-3 text-center">
                            <div class="text-warning mb-2" style="font-size:1.5rem;">⚠️</div>
                            <div class="fw-bold mb-1">Akses Kamera Gagal</div>
                            <small class="text-slate-300 d-block mb-2">${err.message || 'Periksa izin kamera pada browser.'}</small>
                            <button type="button" class="btn btn-sm btn-light" onclick="startKtpCamera()">Coba Lagi</button>
                        </div>
                    `;
                }
            }
        }

        function stopKtpCamera() {
            if (ktpStream) {
                ktpStream.getTracks().forEach(track => track.stop());
                ktpStream = null;
            }
        }

        function ktpCapturePhoto() {
            initKtpElements();
            if (!ktpVideo || !ktpCanvas) return;

            if (ktpVideo.videoWidth === 0 || ktpVideo.videoHeight === 0) {
                Toast.fire({ icon: 'warning', title: 'Kamera belum siap, tunggu sebentar.' });
                return;
            }

            ktpCanvas.width = ktpVideo.videoWidth;
            ktpCanvas.height = ktpVideo.videoHeight;
            const ctx = ktpCanvas.getContext('2d');
            ctx.drawImage(ktpVideo, 0, 0, ktpCanvas.width, ktpCanvas.height);

            const imageData = ktpCanvas.toDataURL('image/jpeg', 0.92);
            if (ktpPhotoInput) ktpPhotoInput.value = imageData;
            if (ktpCapturedImg) ktpCapturedImg.src = imageData;

            if (ktpPhotoPreview) ktpPhotoPreview.style.display = 'block';
            if (ktpCameraWrap) ktpCameraWrap.style.display = 'none';
            if (ktpBtnCapture) ktpBtnCapture.style.display = 'none';
            if (ktpBtnRetake) ktpBtnRetake.style.display = 'inline-flex';

            stopKtpCamera();
            extractNikFromKtp(imageData);
        }

        function ktpRetakePhoto() {
            initKtpElements();
            if (ktpPhotoInput) ktpPhotoInput.value = '';
            if (ktpCapturedImg) ktpCapturedImg.src = '';
            if (ktpPhotoPreview) ktpPhotoPreview.style.display = 'none';
            if (ktpCameraWrap) ktpCameraWrap.style.display = 'block';
            if (ktpBtnCapture) ktpBtnCapture.style.display = 'inline-flex';
            if (ktpBtnRetake) ktpBtnRetake.style.display = 'none';
            if (ktpOcrStatus) ktpOcrStatus.style.display = 'none';

            startKtpCamera();
        }

        function extractNikFromKtp(imageData) {
            initKtpElements();
            if (!ktpOcrStatus) return;

            ktpOcrStatus.style.display = 'inline-flex';
            ktpOcrStatus.className = 'ktp-ocr-status processing';
            ktpOcrStatus.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:.75rem;height:.75rem;border-width:2px;"></span> Membaca NIK dari foto...';

            if (typeof Tesseract === 'undefined') {
                ktpOcrStatus.className = 'ktp-ocr-status failed';
                ktpOcrStatus.innerHTML = '⚠️ Library OCR tidak termuat. Silakan isi NIK secara manual.';
                return;
            }

            Tesseract.recognize(imageData, 'ind', {
                logger: m => {
                    if (m.status === 'recognizing text') {
                        const pct = Math.round((m.progress || 0) * 100);
                        ktpOcrStatus.innerHTML = `<span class="spinner-border spinner-border-sm" style="width:.75rem;height:.75rem;border-width:2px;"></span> Memproses OCR (${pct}%)...`;
                    }
                }
            })
            .then(({ data: { text } }) => {
                console.log("OCR Result Text:", text);

                // Pola 1: 16 digit angka berurutan
                let nikMatch = text.match(/\b\d{16}\b/);

                // Pola 2: coba bersihkan karakter non-digit di baris NIK jika pola 1 tidak dapat
                if (!nikMatch) {
                    const lines = text.split('\n');
                    for (const line of lines) {
                        if (/nik|nomor|induk/i.test(line)) {
                            const digitsOnly = line.replace(/\D/g, '');
                            if (digitsOnly.length === 16) {
                                nikMatch = [digitsOnly];
                                break;
                            }
                        }
                    }
                }

                // Pola 3: gabungan digit jika terpisah spasi (misal 32 01 23 ...)
                if (!nikMatch) {
                    const cleanText = text.replace(/[\s\-_]/g, '');
                    const fallbackMatch = cleanText.match(/\d{16}/);
                    if (fallbackMatch) {
                        nikMatch = [fallbackMatch[0]];
                    }
                }

                if (nikMatch && nikMatch[0]) {
                    const foundNik = nikMatch[0];
                    if (ktpNikInput) {
                        ktpNikInput.value = foundNik;
                        ktpNikInput.classList.remove('is-invalid');
                    }
                    ktpOcrStatus.className = 'ktp-ocr-status success';
                    ktpOcrStatus.innerHTML = `✓ NIK Terdeteksi: <strong>${foundNik}</strong>`;
                    Toast.fire({ icon: 'success', title: `NIK (${foundNik}) berhasil terdeteksi!` });
                } else {
                    ktpOcrStatus.className = 'ktp-ocr-status failed';
                    ktpOcrStatus.innerHTML = '⚠️ NIK tak terbaca jelas. Silakan periksa atau isi manual.';
                    Toast.fire({ icon: 'warning', title: 'NIK tidak terbaca otomatis dari foto, silakan isi manual.' });
                    if (ktpNikInput) ktpNikInput.focus();
                }
            })
            .catch(err => {
                console.error("OCR Error:", err);
                ktpOcrStatus.className = 'ktp-ocr-status failed';
                ktpOcrStatus.innerHTML = '⚠️ Gagal membaca NIK. Silakan isi NIK manual.';
                Toast.fire({ icon: 'warning', title: 'Gagal membaca NIK, silakan ketik manual.' });
            });
        }

        // Inisialisasi Toast SweetAlert2
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        function showStepWarning(message, element) {
            Toast.fire({
                icon: 'warning',
                title: message
            });

            if (element) {
                element.classList.add('is-invalid');
                const tsControl = element.closest('.ts-wrapper');
                if (tsControl) {
                    tsControl.classList.add('is-invalid');
                }
                setTimeout(() => {
                    element.focus();
                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
            }
        }

        // Hapus penanda invalid saat input diubah
        document.addEventListener('input', function(e) {
            if (e.target && e.target.classList.contains('is-invalid')) {
                e.target.classList.remove('is-invalid');
                const tsWrapper = e.target.closest('.ts-wrapper');
                if (tsWrapper) tsWrapper.classList.remove('is-invalid');
            }
        });
        document.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('is-invalid')) {
                e.target.classList.remove('is-invalid');
                const tsWrapper = e.target.closest('.ts-wrapper');
                if (tsWrapper) tsWrapper.classList.remove('is-invalid');
            }
        });

        /**
         * Validasi input untuk setiap step
         */
        function validateStep(stepNumber, showNotification = true) {
            // Bersihkan error sebelum validasi
            const currentPanel = document.getElementById(`step-panel-${stepNumber}`);
            if (currentPanel) {
                currentPanel.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            }

            if (stepNumber === 1) {
                const tipePelanggan = document.getElementById('tipe_pelanggan_id');
                if (tipePelanggan && !tipePelanggan.value.trim()) {
                    if (showNotification) showStepWarning('Tipe Pelanggan wajib dipilih.', tipePelanggan);
                    return false;
                }

                const nameInput = document.getElementById('name');
                if (!nameInput || !nameInput.value.trim()) {
                    if (showNotification) showStepWarning('Nama Lengkap Pelanggan wajib diisi.', nameInput);
                    return false;
                }

                const emailInput = document.getElementById('email');
                const emailVal = emailInput ? emailInput.value.trim() : '';
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailVal) {
                    if (showNotification) showStepWarning('Email Pelanggan wajib diisi.', emailInput);
                    return false;
                } else if (!emailRegex.test(emailVal)) {
                    if (showNotification) showStepWarning('Format Email Pelanggan tidak valid (contoh@email.com).', emailInput);
                    return false;
                }

                const telpInput = document.getElementById('telp');
                const telpVal = telpInput ? telpInput.value.trim() : '';
                const telpRegex = /^[0-9]{10,15}$/;
                if (!telpVal) {
                    if (showNotification) showStepWarning('Nomor Telepon / WA wajib diisi.', telpInput);
                    return false;
                } else if (!telpRegex.test(telpVal.replace(/[-\s]/g, ''))) {
                    if (showNotification) showStepWarning('Nomor Telepon / WA harus berupa 10 - 15 digit angka.', telpInput);
                    return false;
                }

                const macInput = document.getElementById('mac_address');
                const macVal = macInput ? macInput.value.trim() : '';
                if (!macVal) {
                    if (showNotification) showStepWarning('MAC Address Modem / Router wajib diisi.', macInput);
                    return false;
                }

                return true;
            }

            if (stepNumber === 2) {
                const typesSelect = document.getElementById('types_id');
                if (!typesSelect || !typesSelect.value.trim()) {
                    if (showNotification) showStepWarning('Tipe Layanan Internet wajib dipilih.', typesSelect);
                    return false;
                }

                const routersId = document.getElementById('routers_id');
                if (!routersId || !routersId.value.trim()) {
                    const routerManualWrap = document.getElementById('router_manual_wrap');
                    if (routerManualWrap) routerManualWrap.style.display = 'block';
                    const manualSelect = document.getElementById('manual_router_select');
                    if (showNotification) {
                        showStepWarning('Jenis Router Terpasang belum dipilih atau belum terdeteksi. Silakan periksa MAC Address atau pilih router secara manual.', manualSelect || document.getElementById('router_name'));
                    }
                    return false;
                }

                const selectedTypeOption = typesSelect.options[typesSelect.selectedIndex];
                const typeLabel = selectedTypeOption ? (selectedTypeOption.getAttribute('data-label') || selectedTypeOption.text || '') : '';
                const isPPPOE = typeLabel.toUpperCase().includes('PPPOE');

                if (isPPPOE) {
                    const wifiName = document.getElementById('name_wifi');
                    if (!wifiName || !wifiName.value.trim()) {
                        if (showNotification) showStepWarning('Nama WiFi (SSID) wajib diisi untuk layanan PPPOE.', wifiName);
                        return false;
                    }

                    const wifiPassword = document.getElementById('password_wifi');
                    const passVal = wifiPassword ? wifiPassword.value.trim() : '';
                    if (!passVal) {
                        if (showNotification) showStepWarning('Password WiFi wajib diisi untuk layanan PPPOE.', wifiPassword);
                        return false;
                    } else if (passVal.length < 8) {
                        if (showNotification) showStepWarning('Password WiFi minimal 8 karakter.', wifiPassword);
                        return false;
                    }

                    const paketSelect = document.getElementById('paket_id');
                    if (!paketSelect || !paketSelect.value.trim()) {
                        if (showNotification) showStepWarning('Pilihan Tipe Paket wajib dipilih.', paketSelect);
                        return false;
                    }

                    const micRadiusSelect = document.getElementById('mic_radius_id');
                    if (!micRadiusSelect || !micRadiusSelect.value.trim()) {
                        if (showNotification) showStepWarning('Profile Mix Radius wajib dipilih.', micRadiusSelect);
                        return false;
                    }

                    const priceSelect = document.getElementById('price_id');
                    if (!priceSelect || !priceSelect.value.trim()) {
                        if (showNotification) showStepWarning('Skema Pembayaran wajib dipilih.', priceSelect);
                        return false;
                    }
                }

                return true;
            }

            if (stepNumber === 3) {
                if (isKtpAktif) {
                    const photoEl = document.getElementById('ktp_photo');
                    if (!photoEl || !photoEl.value) {
                        if (showNotification) {
                            showStepWarning('Foto KTP wajib diambil sebelum melanjutkan.', document.getElementById('ktp-camera-wrap') || document.getElementById('nik'));
                        }
                        return false;
                    }

                    const nikInput = document.getElementById('nik');
                    const nikVal = nikInput ? nikInput.value.trim() : '';
                    if (!nikVal) {
                        if (showNotification) showStepWarning('NIK wajib diisi.', nikInput);
                        return false;
                    } else if (nikVal.length !== 16 || !/^\d{16}$/.test(nikVal)) {
                        if (showNotification) showStepWarning('NIK harus terdiri dari 16 digit angka.', nikInput);
                        return false;
                    }
                }

                const hometownSelect = document.getElementById('hometowns_id');
                if (!hometownSelect || !hometownSelect.value.trim()) {
                    if (showNotification) showStepWarning('Kampung / Dusun wajib dipilih.', hometownSelect);
                    return false;
                }

                const rtSelect = document.getElementById('rts_id');
                if (!rtSelect || !rtSelect.value.trim()) {
                    if (showNotification) showStepWarning('RT wajib dipilih.', rtSelect);
                    return false;
                }

                const rwSelect = document.getElementById('rws_id');
                if (!rwSelect || !rwSelect.value.trim()) {
                    if (showNotification) showStepWarning('RW wajib dipilih.', rwSelect);
                    return false;
                }

                const villageSelect = document.getElementById('villages_id');
                if (!villageSelect || !villageSelect.value.trim()) {
                    if (showNotification) showStepWarning('Desa / Kelurahan wajib dipilih.', villageSelect);
                    return false;
                }

                const regencySelect = document.getElementById('regencies_id');
                if (!regencySelect || !regencySelect.value.trim()) {
                    if (showNotification) showStepWarning('Kabupaten / Kota wajib dipilih.', regencySelect);
                    return false;
                }

                const districtSelect = document.getElementById('districts_id');
                if (!districtSelect || !districtSelect.value.trim()) {
                    if (showNotification) showStepWarning('Kecamatan wajib dipilih.', districtSelect);
                    return false;
                }

                return true;
            }

            if (stepNumber === 4) {
                const vlanSelect = document.getElementById('vlans_id');
                if (!vlanSelect || !vlanSelect.value.trim()) {
                    if (showNotification) showStepWarning('Pilihan VLAN Network wajib dipilih.', vlanSelect);
                    return false;
                }

                const odcSelect = document.getElementById('odcs_id');
                if (!odcSelect || !odcSelect.value.trim()) {
                    if (showNotification) showStepWarning('Alokasi ODC wajib dipilih.', odcSelect);
                    return false;
                }

                const odpSelect = document.getElementById('odps_id');
                if (!odpSelect || !odpSelect.value.trim()) {
                    if (showNotification) showStepWarning('Alokasi ODP wajib dipilih.', odpSelect);
                    return false;
                }

                const oltSelect = document.getElementById('olts_id');
                if (!oltSelect || !oltSelect.value.trim()) {
                    if (showNotification) showStepWarning('Alokasi OLT wajib dipilih.', oltSelect);
                    return false;
                }

                const patchCoreSelect = document.getElementById('patch_core_id');
                if (!patchCoreSelect || !patchCoreSelect.value.trim()) {
                    if (showNotification) showStepWarning('Ukuran Patch Core wajib dipilih.', patchCoreSelect);
                    return false;
                }

                return true;
            }

            return true;
        }

        function updateWizardUI() {
            // Update panels
            for (let i = 1; i <= totalWizardSteps; i++) {
                const panel = document.getElementById(`step-panel-${i}`);
                const circle = document.getElementById(`circle-${i}`);
                const stepItem = circle ? circle.closest('.wizard-step-item') : null;

                if (!panel || !circle || !stepItem) continue;

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
            const progressBar = document.getElementById('wizard-progress');
            if (progressBar) progressBar.style.width = `${progressPercentage}%`;

            // Update Nav Buttons
            const btnPrev = document.getElementById('btn-prev');
            const btnNext = document.getElementById('btn-next');
            const btnSubmit = document.getElementById('btn-submit');

            if (btnPrev) btnPrev.style.visibility = currentWizardStep === 1 ? 'hidden' : 'visible';

            if (currentWizardStep === totalWizardSteps) {
                if (btnNext) btnNext.style.display = 'none';
                if (btnSubmit) btnSubmit.style.display = 'inline-flex';
            } else {
                if (btnNext) btnNext.style.display = 'inline-flex';
                if (btnSubmit) btnSubmit.style.display = 'none';
            }

            // KTP Camera handling saat aktif di Step 3
            if (isKtpAktif) {
                if (currentWizardStep === 3) {
                    const photoInput = document.getElementById('ktp_photo');
                    if (!photoInput || !photoInput.value) {
                        setTimeout(() => startKtpCamera(), 150);
                    }
                } else {
                    stopKtpCamera();
                }
            }

            window.scrollTo({ top: 150, behavior: 'smooth' });
        }

        function changeStep(delta) {
            if (delta > 0) {
                // Periksa validasi step saat ini sebelum maju
                if (!validateStep(currentWizardStep)) {
                    return;
                }
            }

            const nextStep = currentWizardStep + delta;
            if (nextStep >= 1 && nextStep <= totalWizardSteps) {
                currentWizardStep = nextStep;
                updateWizardUI();
                if (nextStep === 4) triggerGpsDetection();
            }
        }

        function goToStep(step) {
            if (step > currentWizardStep) {
                // Periksa validasi semua step perantara
                for (let s = currentWizardStep; s < step; s++) {
                    if (!validateStep(s)) {
                        currentWizardStep = s;
                        updateWizardUI();
                        return;
                    }
                }
            }

            if (step >= 1 && step <= totalWizardSteps) {
                currentWizardStep = step;
                updateWizardUI();
                if (step === 4) triggerGpsDetection();
            }
        }

        function triggerGpsDetection() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(pos) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    const latHidden = document.getElementById('latitude');
                    const lngHidden = document.getElementById('longitude');
                    const latDisplay = document.getElementById('latitude_display');
                    const lngDisplay = document.getElementById('longitude_display');

                    if (latHidden) latHidden.value = lat;
                    if (lngHidden) lngHidden.value = lng;
                    if (latDisplay) latDisplay.value = lat;
                    if (lngDisplay) lngDisplay.value = lng;
                });
            }
        }

        // Toggle PPPoE View
        function syncPppoeView() {
            const typesSelect = document.getElementById('types_id');
            const pppoeContainer = document.getElementById('pppoe-show');
            const typeNameInput = document.getElementById('type_name');

            if (!typesSelect || !pppoeContainer) return;

            const selectedOption = typesSelect.options[typesSelect.selectedIndex];
            const typeLabel = selectedOption ? (selectedOption.getAttribute('data-label') || selectedOption.text || '') : '';
            const isPPPOE = typeLabel.toUpperCase().includes('PPPOE');

            if (isPPPOE) {
                pppoeContainer.style.display = 'block';
                if (typeNameInput) typeNameInput.value = 'PPPOE';
            } else {
                pppoeContainer.style.display = 'none';
                if (typeNameInput) typeNameInput.value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateWizardUI();
            syncPppoeView();

            const typesSelect = document.getElementById('types_id');
            if (typesSelect) {
                typesSelect.addEventListener('change', syncPppoeView);
            }

            // Inisialisasi TomSelect untuk elemen select bertanda .select-tom
            document.querySelectorAll('select.select-tom').forEach(function(el) {
                const placeholder = el.querySelector('option[value=""]')?.textContent?.trim() ?? '-- Pilih --';
                const ts = new TomSelect(el, {
                    allowEmptyOption: true,
                    placeholder: placeholder,
                    create: false
                });

                ts.on('change', function() {
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                    el.classList.remove('is-invalid');
                    const tsWrap = el.closest('.ts-wrapper');
                    if (tsWrap) tsWrap.classList.remove('is-invalid');
                });
            });

            // Handler Router Manual Select
            const manualRouterSelect = document.getElementById('manual_router_select');
            if (manualRouterSelect) {
                manualRouterSelect.addEventListener('change', function() {
                    const selectedOpt = this.options[this.selectedIndex];
                    const routerName = document.getElementById('router_name');
                    const routersId = document.getElementById('routers_id');
                    if (this.value) {
                        if (routersId) routersId.value = this.value;
                        if (routerName) routerName.value = selectedOpt.getAttribute('data-name') || selectedOpt.text;
                    } else {
                        if (routersId) routersId.value = '';
                        if (routerName) routerName.value = '';
                    }
                });
            }

            // MAC Address validation & Router auto-detection
            function runCheckMac(macVal) {
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
                    let manualWrap = document.getElementById('router_manual_wrap');
                    let manualSelect = document.getElementById('manual_router_select');

                    if (data.valid && data.router) {
                        if (routerName) routerName.value = data.router.name || '';
                        if (routersId) routersId.value = data.router.id || '';
                        if (manualWrap) manualWrap.style.display = 'none';
                    } else {
                        if (manualWrap) manualWrap.style.display = 'block';
                        if (manualSelect && manualSelect.value) {
                            if (routersId) routersId.value = manualSelect.value;
                            if (routerName) routerName.value = manualSelect.options[manualSelect.selectedIndex]?.getAttribute('data-name') || '';
                        }
                    }
                })
                .catch(err => {
                    console.error("Error checking MAC address:", err);
                    const manualWrap = document.getElementById('router_manual_wrap');
                    if (manualWrap) manualWrap.style.display = 'block';
                });
            }

            const macInput = document.getElementById('mac_address');
            if (macInput) {
                macInput.addEventListener('blur', function() {
                    runCheckMac(this.value.trim());
                });
                macInput.addEventListener('change', function() {
                    runCheckMac(this.value.trim());
                });
                if (macInput.value.trim()) {
                    runCheckMac(macInput.value.trim());
                }
            }

            // Validasi submit keseluruhan formulir
            const configForm = document.getElementById('config-form');
            if (configForm) {
                configForm.addEventListener('submit', function(e) {
                    for (let s = 1; s <= totalWizardSteps; s++) {
                        if (!validateStep(s)) {
                            e.preventDefault();
                            currentWizardStep = s;
                            updateWizardUI();
                            return false;
                        }
                    }

                    // Tampilkan indikator loading saat submit berhasil lolos validasi
                    const btnSubmit = document.getElementById('btn-submit');
                    const submitText = document.getElementById('btn-submit-text');
                    const submitLoading = document.getElementById('btn-submit-loading');

                    if (btnSubmit) {
                        btnSubmit.disabled = true;
                        if (submitText) submitText.textContent = 'Menyimpan...';
                        if (submitLoading) submitLoading.classList.remove('d-none');
                    }
                });
            }
        });

        // Deteksi lokasi GPS manual
        function detectLocation() {
            if (!navigator.geolocation) {
                Toast.fire({ icon: 'error', title: 'Browser tidak mendukung Geolocation.' });
                return;
            }
            navigator.geolocation.getCurrentPosition(function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;

                const latHidden = document.getElementById('latitude');
                const lngHidden = document.getElementById('longitude');
                const latDisplay = document.getElementById('latitude_display');
                const lngDisplay = document.getElementById('longitude_display');

                if (latHidden) latHidden.value = lat;
                if (lngHidden) lngHidden.value = lng;
                if (latDisplay) latDisplay.value = lat;
                if (lngDisplay) lngDisplay.value = lng;

                Toast.fire({ icon: 'success', title: 'Lokasi GPS berhasil dideteksi.' });
            }, function(err) {
                Toast.fire({ icon: 'error', title: 'Gagal mendeteksi lokasi: ' + err.message });
            });
        }

        // Camera lifecycle listeners
        window.addEventListener('beforeunload', function() {
            stopKtpCamera();
        });

        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopKtpCamera();
            } else if (currentWizardStep === 3 && isKtpAktif) {
                const photoInput = document.getElementById('ktp_photo');
                if (!photoInput || !photoInput.value) {
                    startKtpCamera();
                }
            }
        });
    </script>
@endpush


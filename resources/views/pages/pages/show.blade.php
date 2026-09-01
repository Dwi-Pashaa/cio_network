@extends('layouts.app-pages')

@section('title')
    {{ $pages->name }}
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
            --page-primary: #2563eb;
            --page-primary-hover: #1d4ed8;
            --page-primary-light: #eff6ff;
            --page-primary-dark: #1e3a8a;
            --page-primary-glow: rgba(37, 99, 235, 0.18);
            --page-surface: #ffffff;
            --page-border: #e5e7eb;
            --page-text: #111827;
            --page-text-muted: #6b7280;
            --page-radius: 14px;
            --page-shadow: 0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.06);
        }

        body { background: #f0f5ff; }

        /* ── Page Header ── */
        .page-header-wizard {
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 55%, #2563eb 100%);
            color: #fff;
            border-radius: var(--page-radius);
            padding: 2.25rem 2rem;
            margin-bottom: 1.75rem;
            text-align: center;
            box-shadow: 0 10px 30px -6px rgba(37,99,235,0.35);
            position: relative;
            overflow: hidden;
        }

        .page-header-wizard::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }

        .page-header-wizard::after {
            content: '';
            position: absolute;
            bottom: -40px; left: -40px;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }

        .header-icon-badge {
            width: 60px; height: 60px;
            background: rgba(255,255,255,0.15);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.85rem;
            backdrop-filter: blur(8px);
            border: 1.5px solid rgba(255,255,255,0.25);
        }

        /* ── Stepper Navigation ── */
        .wizard-stepper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            margin-bottom: 2rem;
            background: #fff;
            padding: 1.25rem 1.75rem;
            border-radius: var(--page-radius);
            border: 1px solid #bfdbfe;
            box-shadow: var(--page-shadow);
        }

        .step-item {
            display: flex; flex-direction: column;
            align-items: center;
            position: relative; z-index: 2;
            flex: 1; text-align: center;
        }

        .step-circle {
            width: 42px; height: 42px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #64748b;
            border: 2px solid #cbd5e1;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.95rem;
            margin-bottom: 0.4rem;
            transition: all 0.3s ease;
        }

        .step-item.active .step-circle {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37,99,235,0.2);
        }

        .step-item.completed .step-circle {
            background: #16a34a;
            color: #fff;
            border-color: #16a34a;
        }

        .step-title {
            font-size: 0.78rem; font-weight: 600;
            color: #64748b;
            transition: color 0.3s ease;
        }

        .step-item.active .step-title { color: #2563eb; font-weight: 700; }
        .step-item.completed .step-title { color: #16a34a; }

        .step-line {
            position: absolute;
            top: 34px; left: 10%; right: 10%;
            height: 3px;
            background: linear-gradient(90deg, #bfdbfe, #e2e8f0);
            z-index: 1;
        }

        /* ── Section Card ── */
        .section-card {
            background: var(--page-surface);
            border: 1px solid #bfdbfe;
            border-radius: var(--page-radius);
            box-shadow: var(--page-shadow);
            margin-bottom: 1.25rem;
            overflow: hidden;
        }

        .section-card .section-header {
            display: flex; align-items: center; gap: .65rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #bfdbfe;
            background: linear-gradient(90deg, #eff6ff 0%, #dbeafe 100%);
        }

        .section-card .section-header h5,
        .section-card .section-header .section-title {
            color: #1e40af !important;
            font-weight: 700;
        }

        .section-card .section-header .icon-wrapper {
            background: rgba(37,99,235,0.12) !important;
            color: #2563eb !important;
        }

        .section-card .section-body { padding: 1.5rem; }

        /* ── Card (inner) headers ── */
        .card .card-header {
            display: flex; align-items: center; gap: .5rem;
            background: linear-gradient(90deg, #eff6ff 0%, #dbeafe 100%);
            border-bottom: 1px solid #bfdbfe;
            color: #1e40af;
            font-weight: 700;
        }

        .section-icon { font-size: 1.1rem; }

        /* ── Service Type Cards ── */
        .service-type-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.25rem; margin-bottom: 1.5rem;
        }

        .service-type-card {
            border: 2px solid #bfdbfe;
            border-radius: 14px; padding: 1.5rem;
            background: #ffffff; cursor: pointer;
            transition: all 0.25s ease;
            position: relative;
            display: flex; flex-direction: column;
            align-items: center; text-align: center;
        }

        .service-type-card:hover {
            border-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(37,99,235,0.15);
        }

        .service-type-card.selected {
            border-color: #2563eb;
            background: #eff6ff;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.2);
        }

        .service-icon-wrapper {
            width: 60px; height: 60px; border-radius: 50%;
            background: rgba(37,99,235,0.1);
            color: #2563eb;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1rem;
            transition: all 0.25s ease;
        }

        .service-type-card.selected .service-icon-wrapper {
            background: #2563eb; color: #ffffff;
        }

        /* ── Buttons override to blue ── */
        .btn-primary {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
            color: #fff !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
        }

        .btn-outline-secondary {
            border-color: #93c5fd !important;
            color: #2563eb !important;
        }
        .btn-outline-secondary:hover {
            background-color: #eff6ff !important;
        }

        /* ── Agreement Content Box ── */
        .agreement-box {
            background: #fafeff;
            border: 1.5px solid #bfdbfe;
            border-radius: 12px; padding: 1.5rem;
            max-height: 420px; overflow-y: auto;
            font-size: 0.92rem; line-height: 1.7;
            color: #1e3a5f; margin-bottom: 1.5rem;
            box-shadow: inset 0 2px 4px rgba(37,99,235,0.04);
        }

        .agreement-box h1, .agreement-box h2,
        .agreement-box h3, .agreement-box h4 {
            color: #1e3a8a; font-weight: 700;
            margin-top: 1rem; margin-bottom: 0.5rem;
        }

        /* ── Digital Signature Canvas ── */
        .signature-wrapper {
            background: #ffffff;
            border: 2px dashed #93c5fd;
            border-radius: 14px; padding: 1rem;
            text-align: center; position: relative;
            transition: border-color 0.2s;
        }

        .signature-wrapper:hover { border-color: #2563eb; }

        #signature-pad {
            width: 100%; height: 200px;
            background: #f8fbff;
            border-radius: 10px; cursor: crosshair;
            touch-action: none;
        }

        /* ── Summary Details Table ── */
        .summary-card {
            background: #f0f7ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px; padding: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .summary-item {
            display: flex; justify-content: space-between;
            padding: 0.45rem 0;
            border-bottom: 1px dashed #bfdbfe;
            font-size: 0.88rem;
        }

        .summary-item:last-child { border-bottom: none; }
        .summary-label { color: #3b6fb6; font-weight: 500; }
        .summary-value { color: #0f172a; font-weight: 600; text-align: right; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .wizard-stepper { padding: 1rem 0.5rem; }
            .step-title { font-size: 0.68rem; }
            .step-circle { width: 34px; height: 34px; font-size: 0.82rem; }
        }

        /* ── PDF Skeleton shimmer ── */
        @keyframes pdfShimmer {
            0%   { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-10 col-md-12 col-sm-12">
                <!-- Header Banner -->
                <div class="page-header-wizard">
                    <div class="header-icon-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                            stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <h1 class="h2 text-white fw-bold mb-1">{{ $pages->name }}</h1>
                    <p class="text-white-50 mb-0">{{ $pages->desc }}</p>
                </div>

                @include('components.alert.success')
                @if (session()->has('error'))
                    <div class="alert alert-danger mb-3">
                        {{ session()->get('error') }}
                    </div>
                @endif

                @if ($pages->is_persetujuan === 'aktif')
                    <!-- ========================================== -->
                    <!-- WIZARD STEPPER (5 STEPS) -->
                    <!-- ========================================== -->
                    <div class="wizard-stepper">
                        <div class="step-line"></div>
                        <div class="step-item active" id="step-nav-1">
                            <div class="step-circle">1</div>
                            <span class="step-title">Layanan</span>
                        </div>
                        <div class="step-item" id="step-nav-2">
                            <div class="step-circle">2</div>
                            <span class="step-title">Persetujuan</span>
                        </div>
                        <div class="step-item" id="step-nav-3">
                            <div class="step-circle">3</div>
                            <span class="step-title">Input Data</span>
                        </div>
                        <div class="step-item" id="step-nav-4">
                            <div class="step-circle">4</div>
                            <span class="step-title">Preview &amp; TTD</span>
                        </div>
                        <div class="step-item" id="step-nav-5">
                            <div class="step-circle">5</div>
                            <span class="step-title">Selesai</span>
                        </div>
                    </div>
                @endif

                <!-- Form Container -->
                <form id="customerForm" action="{{ route('input.data.saveCustomerToSpan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" id="status" value="spam">
                    <input type="hidden" name="wa_phone" id="wa_phone" value="{{ $pages->telp }}">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="is_ktp" id="is_ktp" value="{{ $pages->is_ktp }}">
                    <input type="hidden" name="tanda_tangan_customer" id="tanda_tangan_customer">

                    <!-- ============================================================ -->
                    <!-- STEP 1: PILIH TIPE LAYANAN -->
                    <!-- ============================================================ -->
                    <div class="wizard-step-pane {{ $pages->is_persetujuan === 'aktif' ? '' : 'd-none' }}" id="wizard-step-1">
                        <div class="section-card">
                            <div class="section-header">
                                <div class="icon-wrapper" style="width:32px;height:32px;border-radius:8px;background:rgba(37,99,235,0.12);color:#2563eb;display:flex;align-items:center;justify-content:center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                        <line x1="8" y1="21" x2="16" y2="21"/>
                                        <line x1="12" y1="17" x2="12" y2="21"/>
                                    </svg>
                                </div>
                                <h5 class="mb-0 fw-bold">Pilih Tipe Layanan Pemasangan</h5>
                            </div>
                            <div class="section-body">
                                <p class="text-muted mb-4">Silakan pilih kategori paket layanan yang diinginkan oleh pelanggan:</p>
                                
                                <div class="service-type-grid">
                                    @foreach ($types as $tp)
                                        @php
                                            $isPppoeType = stripos($tp->name, 'pppoe') !== false;
                                        @endphp
                                        <div class="service-type-card" data-type-id="{{ $tp->id }}" data-type-name="{{ $tp->name }}" data-is-pppoe="{{ $isPppoeType ? '1' : '0' }}">
                                            <div class="service-icon-wrapper">
                                                @if ($isPppoeType)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M5 12.55a11 11 0 0 1 14.08 0"/>
                                                        <path d="M1.42 9a16 16 0 0 1 21.16 0"/>
                                                        <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
                                                        <line x1="12" y1="20" x2="12.01" y2="20"/>
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                                                        <path d="M7 15h0M2 9.5h20"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <h4 class="fw-bold mb-1">{{ $tp->name }}</h4>
                                            <p class="text-muted small mb-0">
                                                @if ($isPppoeType)
                                                    Layanan internet dedicated/berlangganan bulanan dengan surat perjanjian.
                                                @else
                                                    Layanan internet sistem voucher / non-perjanjian.
                                                @endif
                                            </p>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn btn-primary px-4 py-2" id="btn-next-step-1" disabled>
                                        Selanjutnya
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-1">
                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                            <polyline points="12 5 19 12 12 19"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- STEP 2: PREVIEW DOKUMEN PERSETUJUAN (KHUSUS PPPOE) -->
                    <!-- ============================================================ -->
                    <div class="wizard-step-pane d-none" id="wizard-step-2">
                        <div class="section-card">
                            <div class="section-header">
                                <div class="icon-wrapper" style="width:32px;height:32px;border-radius:8px;background:rgba(37,99,235,0.12);color:#2563eb;display:flex;align-items:center;justify-content:center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                    </svg>
                                </div>
                                <h5 class="mb-0 fw-bold">Syarat &amp; Ketentuan Persetujuan Berlangganan</h5>
                            </div>
                            <div class="section-body">
                                <div class="agreement-box" id="agreementContent">
                                    @if(isset($persetujuan) && $persetujuan->konten)
                                        <h4 class="text-center fw-bold mb-3">{{ $persetujuan->judul ?? 'SURAT PERNYATAAN PERSETUJUAN BERLANGGANAN' }}</h4>
                                        {!! $persetujuan->konten !!}
                                    @else
                                        <h4 class="text-center fw-bold mb-3">SURAT PERNYATAAN PERSETUJUAN BERLANGGANAN LAYANAN INTERNET</h4>
                                        <p>Pelanggan dengan ini menyatakan telah memahami dan menyetujui seluruh ketentuan berlangganan jasa telekomunikasi dan internet yang disediakan oleh perusahaan:</p>
                                        <ol>
                                            <li>Pelanggan bersedia mematuhi jadwal pembayaran tagihan tepat waktu setiap bulannya.</li>
                                            <li>Perangkat router dan modem yang dipinjamkan wajib dirawat dan dijaga dengan baik oleh pelanggan.</li>
                                            <li>Pelanggan dilarang keras menyalahgunakan koneksi internet untuk tindakan melanggar hukum atau merugikan pihak lain.</li>
                                            <li>Segala bentuk gangguan koneksi jaringan akan ditangani secara profesional oleh tim teknisi resmi.</li>
                                        </ol>
                                    @endif
                                </div>

                                <div class="form-check p-3 bg-light rounded-3 border mb-4">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" id="agreeCheckbox" style="cursor: pointer;">
                                    <label class="form-check-label fw-bold text-dark" for="agreeCheckbox" style="cursor: pointer;">
                                        Saya telah membaca, memahami, dan menyetujui seluruh isi Surat Persetujuan Berlangganan di atas. <span class="text-danger">*</span>
                                    </label>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary px-4 py-2" id="btn-back-step-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                            <line x1="19" y1="12" x2="5" y2="12"/>
                                            <polyline points="12 19 5 12 12 5"/>
                                        </svg>
                                        Kembali
                                    </button>
                                    <button type="button" class="btn btn-primary px-4 py-2" id="btn-next-step-2" disabled>
                                        Lanjut Isi Formulir
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-1">
                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                            <polyline points="12 5 19 12 12 19"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- STEP 3: FORM INPUT DATA (LENGKAP) -->
                    <!-- ============================================================ -->
                    <div class="wizard-step-pane {{ $pages->is_persetujuan === 'aktif' ? 'd-none' : '' }}" id="wizard-step-3">
                        
                        @if ($pages->is_ktp === 'aktif')
                            <!-- Upload KTP Section -->
                            <div class="card section-card">
                                <div class="card-header">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg> Foto KTP Pelanggan
                                </div>
                                <div class="card-body">
                                    <div id="camera-view" class="camera-container">
                                        <video id="video" autoplay playsinline></video>
                                        <div class="ktp-frame"></div>
                                    </div>

                                    <canvas id="canvas"></canvas>
                                    <input type="hidden" name="ktp_photo" id="ktp_photo">

                                    <div class="photo-preview" id="photo-preview" style="display:none;">
                                        <img id="captured-photo">
                                    </div>

                                    <div class="form-group mb-3 mt-3">
                                        <label class="form-label">NIK <span class="text-danger">*</span></label>
                                        <input type="text" name="nik" id="nik"
                                            class="form-control @error('nik') is-invalid @enderror"
                                            placeholder="NIK akan terisi otomatis atau masukkan manual">
                                        @error('nik')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="text-center mt-3">
                                        <button type="button" id="btn-capture" class="btn btn-capture" onclick="capturePhoto()">
                                            Ambil Foto
                                        </button>
                                        <button type="button" id="btn-retake" class="btn btn-retake" onclick="retakePhoto()" style="display:none;">
                                            Ulangi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Data Pelanggan Section -->
                        <div class="card section-card">
                            <div class="card-header">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Data Pelanggan
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <label class="form-label">ID Pelanggan</label>
                                        <input type="text" value="{{ $newCode }}" class="form-control" readonly>
                                        <input type="hidden" name="uuid" id="uuid" value="{{ $newCode }}">
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                        <label class="form-label">Tipe Pelanggan <span class="text-danger">*</span></label>
                                        @if ($tipePelanggan->count() === 1)
                                            @php $singleTpl = $tipePelanggan->first(); @endphp
                                            <select name="tipe_pelanggan_id" id="tipe_pelanggan_id"
                                                class="form-control @error('tipe_pelanggan_id') is-invalid @enderror">
                                                <option value="{{ $singleTpl->id }}" selected>{{ $singleTpl->name }}</option>
                                            </select>
                                        @else
                                            <select name="tipe_pelanggan_id" id="tipe_pelanggan_id"
                                                class="form-control @error('tipe_pelanggan_id') is-invalid @enderror">
                                                <option value="">Pilih Tipe Pelanggan</option>
                                                @foreach ($tipePelanggan as $tpl)
                                                    <option data-label="{{ $tpl->name }}" value="{{ $tpl->id }}"
                                                        {{ old('tipe_pelanggan_id') == $tpl->id ? 'selected' : '' }}>
                                                        {{ $tpl->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                        @error('tipe_pelanggan_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                                        <input value="{{ old('name') }}" type="text" name="name" id="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Masukkan nama lengkap">
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Email Pelanggan <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input value="{{ old('email') }}" type="email" name="email" id="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="contoh@email.com" autocomplete="off">
                                            <button type="button" id="btn-check-email" class="btn btn-outline-secondary"
                                                style="border-radius: 0 8px 8px 0; border-left: 0; white-space:nowrap;">
                                                <span id="check-email-text">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                                                    </svg>
                                                    Cek Email
                                                </span>
                                                <span id="check-email-loading" class="spinner-border spinner-border-sm d-none" style="width:.8rem;height:.8rem;border-width:2px;"></span>
                                            </button>
                                        </div>
                                        @error('email')
                                            <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                                        @enderror
                                        <small id="emailFeedback" class="mt-1" style="display:none; font-size:.82rem;"></small>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">No Telephone <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input value="{{ old('telp') }}" type="text" name="telp" id="telp"
                                                class="form-control @error('telp') is-invalid @enderror"
                                                placeholder="08xxxxxxxxxx" autocomplete="off">
                                            <button type="button" id="btn-check-phone" class="btn btn-outline-secondary"
                                                style="border-radius: 0 8px 8px 0; border-left: 0; white-space:nowrap;">
                                                <span id="check-phone-text">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                                        <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58z"/>
                                                    </svg>
                                                    Cek No HP
                                                </span>
                                                <span id="check-phone-loading" class="spinner-border spinner-border-sm d-none" style="width:.8rem;height:.8rem;border-width:2px;"></span>
                                            </button>
                                        </div>
                                        @error('telp')
                                            <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                                        @enderror
                                        <small id="phoneFeedback" class="mt-1" style="display:none; font-size:.82rem;"></small>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Mac Address <span class="text-danger">*</span></label>
                                        <input value="{{ old('mac_address') }}" type="text" name="mac_address"
                                            id="mac_address" class="form-control @error('mac_address') is-invalid @enderror"
                                            placeholder="XX:XX:XX:XX:XX:XX">
                                        @error('mac_address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small id="macFeedback"></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Konfigurasi Layanan Section -->
                        <div class="card section-card">
                            <div class="card-header">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg> Konfigurasi Layanan &amp; Router
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                        <label class="form-label">Tipe Layanan <span class="text-danger">*</span></label>
                                        <select name="types_id" id="types_id" class="form-control @error('types_id') is-invalid @enderror">
                                            <option value="">Pilih Tipe Layanan</option>
                                            @foreach ($types as $tp)
                                                <option data-label="{{ $tp->name }}" value="{{ $tp->id }}"
                                                    {{ old('types_id') == $tp->id ? 'selected' : '' }}>
                                                    {{ $tp->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="type_name" id="type_name">
                                        @error('types_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                        <label class="form-label">Jenis Router</label>
                                        <input type="text" name="router_name" id="router_name" class="form-control"
                                            disabled placeholder="Terisi otomatis setelah cek MAC">
                                        <input type="hidden" name="routers_id" id="routers_id">
                                    </div>
                                </div>

                                <!-- PPPOE Configuration -->
                                <div id="pppoe-show" style="display:none;">
                                    <hr class="my-4">
                                    <h6 class="mb-3 text-muted fw-bold">Konfigurasi Tambahan PPPOE</h6>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                            <label class="form-label">Nama WiFi (SSID)</label>
                                            <input type="text" name="name_wifi" id="name_wifi"
                                                value="{{ old('name_wifi') }}"
                                                class="form-control @error('name_wifi') is-invalid @enderror"
                                                placeholder="Nama SSID WiFi">
                                            @error('name_wifi')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                            <label class="form-label">Password WiFi</label>
                                            <input type="text" name="password_wifi" id="password_wifi"
                                                value="{{ old('password_wifi') }}"
                                                class="form-control @error('password_wifi') is-invalid @enderror"
                                                placeholder="Password WiFi">
                                            @error('password_wifi')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                            <label class="form-label">Tipe Paket</label>
                                            @if ($paket->count() === 1)
                                                @php $singlePaket = $paket->first(); @endphp
                                                <select name="paket_id" id="paket_id" class="form-control @error('paket_id') is-invalid @enderror">
                                                    <option value="{{ $singlePaket->id }}" selected>{{ $singlePaket->name }}</option>
                                                </select>
                                            @else
                                                <select name="paket_id" id="paket_id" class="form-control select-tom @error('paket_id') is-invalid @enderror">
                                                    <option value="">Pilih Paket</option>
                                                    @foreach ($paket as $pkt)
                                                        <option value="{{ $pkt->id }}" {{ old('paket_id') == $pkt->id ? 'selected' : '' }}>
                                                            {{ $pkt->name }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                            @error('paket_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                            <label class="form-label">Mix Radius</label>
                                            @if ($micRadius->count() === 1)
                                                @php $singleMr = $micRadius->first(); @endphp
                                                <select name="mic_radius_id" id="mic_radius_id" class="form-control @error('mic_radius_id') is-invalid @enderror">
                                                    <option value="{{ $singleMr->id }}" selected>{{ $singleMr->code }} - {{ $singleMr->name }}</option>
                                                </select>
                                            @else
                                                <select name="mic_radius_id" id="mic_radius_id" class="form-control select-tom @error('mic_radius_id') is-invalid @enderror">
                                                    <option value="">Pilih Mix Radius</option>
                                                    @foreach ($micRadius as $mc)
                                                        <option value="{{ $mc->id }}" {{ old('mic_radius_id') == $mc->id ? 'selected' : '' }}>
                                                            {{ $mc->code }} - {{ $mc->name }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                            @error('mic_radius_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 mb-3">
                                            <label class="form-label">Tipe Pembayaran</label>
                                            @if ($price->count() === 1)
                                                @php $singlePrice = $price->first(); @endphp
                                                <select name="price_id" id="price_id" class="form-control @error('price_id') is-invalid @enderror">
                                                    <option value="{{ $singlePrice->id }}" selected>{{ $singlePrice->name }}</option>
                                                </select>
                                            @else
                                                <select name="price_id" id="price_id" class="form-control select-tom @error('price_id') is-invalid @enderror">
                                                    <option value="">Pilih Tipe Pembayaran</option>
                                                    @foreach ($price as $prc)
                                                        <option value="{{ $prc->id }}" {{ old('price_id') == $prc->id ? 'selected' : '' }}>
                                                            {{ $prc->name }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                            @error('price_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Wilayah & Lokasi -->
                        <div class="card section-card">
                            <div class="card-header">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"/><circle cx="12" cy="10" r="3"/></svg> Area &amp; Wilayah Pemasangan
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Kabupaten/Kota</label>
                                        <select name="regencies_id" id="regencies_id" class="form-control select-tom">
                                            <option value="{{ $pages->regencie->id }}">{{ $pages->regencie->name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Kecamatan</label>
                                        <select name="districts_id" id="districts_id" class="form-control select-tom">
                                            <option value="{{ $pages->district->id }}">{{ $pages->district->name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Desa</label>
                                        <select name="villages_id" id="villages_id" class="form-control select-tom">
                                            <option value="{{ $pages->village->id }}">{{ $pages->village->name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Kampung</label>
                                        <select name="hometowns_id" id="hometowns_id" class="form-control select-tom">
                                            <option value="{{ $pages->hometown->id }}">{{ $pages->hometown->name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">RT <span class="text-danger">*</span></label>
                                        <select name="rts_id" id="rts_id" class="form-control select-tom @error('rts_id') is-invalid @enderror">
                                            <option value="">Pilih RT</option>
                                            @foreach ($rts as $rt)
                                                <option value="{{ $rt->id }}" {{ old('rts_id') == $rt->id ? 'selected' : '' }}>{{ $rt->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('rts_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">RW <span class="text-danger">*</span></label>
                                        <select name="rws_id" id="rws_id" class="form-control select-tom @error('rws_id') is-invalid @enderror">
                                            <option value="">Pilih RW</option>
                                            @foreach ($rws as $rw)
                                                <option value="{{ $rw->id }}" {{ old('rws_id') == $rw->id ? 'selected' : '' }}>{{ $rw->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('rws_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-3" id="map-container" style="display:none;">
                                        <label class="form-label">Titik Koordinat Lokasi</label>
                                        <iframe id="map-frame" width="100%" height="220" style="border:0; border-radius:10px;" allowfullscreen="" loading="lazy"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Konfigurasi Jaringan -->
                        <div class="card section-card">
                            <div class="card-header">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg> Perangkat Jaringan (ODC, ODP, OLT, VLAN)
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">VLAN <span class="text-danger">*</span></label>
                                        @if ($vlans->count() === 1)
                                            @php $singleVlan = $vlans->first(); @endphp
                                            <select name="vlans_id" id="vlans_id" class="form-control @error('vlans_id') is-invalid @enderror">
                                                <option value="{{ $singleVlan->id }}" selected>{{ $singleVlan->name }}</option>
                                            </select>
                                        @else
                                            <select name="vlans_id" id="vlans_id" class="form-control select-tom @error('vlans_id') is-invalid @enderror">
                                                <option value="">Pilih VLAN</option>
                                                @foreach ($vlans as $vln)
                                                    <option value="{{ $vln->id }}">{{ $vln->name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Alamat ODC <span class="text-danger">*</span></label>
                                        @if ($odcs->count() === 1)
                                            @php $singleOdc = $odcs->first(); @endphp
                                            <select name="odcs_id" id="odcs_id" class="form-control @error('odcs_id') is-invalid @enderror">
                                                <option value="{{ $singleOdc->id }}" selected>{{ $singleOdc->code }} | {{ $singleOdc->hometown_name }} | {{ $singleOdc->rt_number }} | {{ $singleOdc->rw_number }} | {{ $singleOdc->odc_name }}</option>
                                            </select>
                                        @else
                                            <select name="odcs_id" id="odcs_id" class="form-control select-tom @error('odcs_id') is-invalid @enderror">
                                                <option value="">Pilih ODC</option>
                                                @foreach ($odcs as $odc)
                                                    <option value="{{ $odc->id }}">{{ $odc->code }} | {{ $odc->hometown_name }} | {{ $odc->rt_number }} | {{ $odc->rw_number }} | {{ $odc->odc_name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Alamat ODP <span class="text-danger">*</span></label>
                                        @if ($odps->count() === 1)
                                            @php $singleOdp = $odps->first(); @endphp
                                            <select name="odps_id" id="odps_id" class="form-control @error('odps_id') is-invalid @enderror">
                                                <option value="{{ $singleOdp->id }}" selected>{{ $singleOdp->code }} | {{ $singleOdp->hometown_name }} | {{ $singleOdp->rt_number }} | {{ $singleOdp->rw_number }} | {{ $singleOdp->odp_name }}</option>
                                            </select>
                                        @else
                                            <select name="odps_id" id="odps_id" class="form-control select-tom @error('odps_id') is-invalid @enderror">
                                                <option value="">Pilih ODP</option>
                                                @foreach ($odps as $odp)
                                                    <option value="{{ $odp->id }}">{{ $odp->code }} | {{ $odp->hometown_name }} | {{ $odp->rt_number }} | {{ $odp->rw_number }} | {{ $odp->odp_name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Alamat OLT <span class="text-danger">*</span></label>
                                        @if ($olts->count() === 1)
                                            @php $singleOlt = $olts->first(); @endphp
                                            <select name="olts_id" id="olts_id" class="form-control @error('olts_id') is-invalid @enderror">
                                                <option value="{{ $singleOlt->id }}" selected>{{ $singleOlt->code }} | {{ $singleOlt->hometown_name }} {{ $singleOlt->olt_name }}</option>
                                            </select>
                                        @else
                                            <select name="olts_id" id="olts_id" class="form-control select-tom @error('olts_id') is-invalid @enderror">
                                                <option value="">Pilih OLT</option>
                                                @foreach ($olts as $olt)
                                                    <option value="{{ $olt->id }}">{{ $olt->code }} | {{ $olt->hometown_name }} {{ $olt->olt_name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label class="form-label">Ukuran Patch Core <span class="text-danger">*</span></label>
                                        @if ($pathCore->count() === 1)
                                            @php $singlePc = $pathCore->first(); @endphp
                                            <select name="patch_core_id" id="patch_core_id" class="form-control @error('patch_core_id') is-invalid @enderror">
                                                <option value="{{ $singlePc->id }}" selected>{{ $singlePc->name }}</option>
                                            </select>
                                        @else
                                            <select name="patch_core_id" id="patch_core_id" class="form-control select-tom @error('patch_core_id') is-invalid @enderror">
                                                <option value="">Pilih Ukuran Patch Core</option>
                                                @foreach ($pathCore as $pc)
                                                    <option value="{{ $pc->id }}">{{ $pc->name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons Step 3 -->
                        <div class="card section-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    @if ($pages->is_persetujuan === 'aktif')
                                        <button type="button" class="btn btn-outline-secondary px-4" id="btn-back-step-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                                <line x1="19" y1="12" x2="5" y2="12"/>
                                                <polyline points="12 19 5 12 12 5"/>
                                            </svg>
                                            Kembali
                                        </button>
                                        <button type="button" class="btn btn-primary px-4" id="btn-next-step-3">
                                            Lanjut ke Tinjau &amp; TTD
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-1">
                                                <line x1="5" y1="12" x2="19" y2="12"/>
                                                <polyline points="12 5 19 12 12 19"/>
                                            </svg>
                                        </button>
                                    @else
                                        <button type="reset" class="btn btn-secondary">Reset</button>
                                        <button type="submit" id="btn" class="btn btn-primary">
                                            <span id="btn-text">Kirim Data</span>
                                            <span id="btn-loading" class="spinner-border spinner-border-sm d-none"></span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- STEP 4: PREVIEW DETAIL DATA & TTD DIGITAL + SUBMIT -->
                    <!-- ============================================================ -->
                    <div class="wizard-step-pane d-none" id="wizard-step-4">
                        <div class="section-card">
                            <div class="section-header">
                                <div class="icon-wrapper" style="width:32px;height:32px;border-radius:8px;background:rgba(37,99,235,0.12);color:#2563eb;display:flex;align-items:center;justify-content:center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </div>
                                <h5 class="mb-0 fw-bold">Tinjau Detail Data &amp; Tanda Tangan Pelanggan</h5>
                            </div>
                            <div class="section-body">
                                <!-- Ringkasan Data -->
                                <div class="summary-card">
                                    <h6 class="fw-bold text-primary mb-3">Ringkasan Data Pendaftaran</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="summary-item">
                                                <span class="summary-label">ID Pelanggan:</span>
                                                <span class="summary-value" id="preview-uuid">{{ $newCode }}</span>
                                            </div>
                                            <div class="summary-item">
                                                <span class="summary-label">Nama Pelanggan:</span>
                                                <span class="summary-value" id="preview-name">-</span>
                                            </div>
                                            <div class="summary-item">
                                                <span class="summary-label">NIK:</span>
                                                <span class="summary-value" id="preview-nik">-</span>
                                            </div>
                                            <div class="summary-item">
                                                <span class="summary-label">No. Telepon / WA:</span>
                                                <span class="summary-value" id="preview-telp">-</span>
                                            </div>
                                            <div class="summary-item">
                                                <span class="summary-label">Email:</span>
                                                <span class="summary-value" id="preview-email">-</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="summary-item">
                                                <span class="summary-label">Tipe Layanan:</span>
                                                <span class="summary-value text-primary fw-bold" id="preview-service-type">-</span>
                                            </div>
                                            <div class="summary-item" id="preview-row-paket">
                                                <span class="summary-label">Paket / Pembayaran:</span>
                                                <span class="summary-value" id="preview-paket">-</span>
                                            </div>
                                            <div class="summary-item" id="preview-row-wifi">
                                                <span class="summary-label">Nama WiFi (SSID):</span>
                                                <span class="summary-value" id="preview-wifi">-</span>
                                            </div>
                                            <div class="summary-item">
                                                <span class="summary-label">Wilayah:</span>
                                                <span class="summary-value" id="preview-location">-</span>
                                            </div>
                                            <div class="summary-item">
                                                <span class="summary-label">MAC Address:</span>
                                                <span class="summary-value" id="preview-mac">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Signature Pad Card -->
                                <div class="mb-4">
                                    <label class="form-label-premium fw-bold mb-2">
                                        Tanda Tangan Digital Calon Pelanggan <span class="text-danger">*</span>
                                    </label>
                                    <div class="signature-wrapper">
                                        <canvas id="signature-pad"></canvas>
                                        <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                                            <small class="text-muted">Goreskan tanda tangan Anda di area kotak atas (layar sentuh / mouse)</small>
                                            <button type="button" class="btn btn-sm btn-outline-danger" id="btn-clear-signature">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                                    <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                </svg>
                                                Hapus Tanda Tangan
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-check p-3 bg-light rounded-3 border mb-4">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" id="confirmDataCheckbox" style="cursor: pointer;">
                                    <label class="form-check-label fw-bold text-dark" for="confirmDataCheckbox" style="cursor: pointer;">
                                        Saya menyatakan data di atas telah sesuai dan tanda tangan digital adalah sah milik saya. <span class="text-danger">*</span>
                                    </label>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary px-4 py-2" id="btn-back-step-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                            <line x1="19" y1="12" x2="5" y2="12"/>
                                            <polyline points="12 19 5 12 12 5"/>
                                        </svg>
                                        Kembali
                                    </button>
                                    <button type="button" class="btn btn-success px-5 py-2 fw-bold" id="btn-submit-wizard" disabled>
                                        <span id="btn-submit-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                                <polyline points="22 4 12 14.01 9 11.01"/>
                                            </svg>
                                            Simpan &amp; Daftarkan
                                        </span>
                                        <span id="btn-submit-loading" class="spinner-border spinner-border-sm d-none"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- STEP 5: DETAIL SUKSES & PREVIEW + UNDUH DOKUMEN PDF -->
                    <!-- ============================================================ -->
                    <div class="wizard-step-pane d-none" id="wizard-step-5">
                        <div class="section-card p-4">
                            <!-- Success Banner -->
                            <div class="text-center mb-4">
                                <div style="width:72px;height:72px;border-radius:50%;background:rgba(22,163,74,0.12);color:#16a34a;display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                    </svg>
                                </div>
                                <h3 class="fw-bold text-dark mb-1">Pendaftaran Berhasil Disimpan!</h3>
                                <p class="text-muted mb-0">Data pendaftaran pelanggan telah berhasil diverifikasi dan disimpan ke sistem.</p>
                            </div>

                            <div class="row g-4 align-items-start">
                                <!-- Kiri: Info Pelanggan + Tombol -->
                                <div class="col-lg-4 col-md-12">
                                    <div class="summary-card mb-3">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                            Info Pelanggan
                                        </h6>
                                        <div class="summary-item">
                                            <span class="summary-label">ID Pelanggan:</span>
                                            <span class="summary-value text-primary fw-bold" id="final-uuid">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="summary-label">Nama Pelanggan:</span>
                                            <span class="summary-value" id="final-name">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="summary-label">No. Telepon:</span>
                                            <span class="summary-value" id="final-telp">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="summary-label">Status:</span>
                                            <span class="summary-value"><span class="badge bg-success text-white">Sukses Terdaftar</span></span>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <a href="javascript:void(0)" id="btn-download-pdf" target="_blank" class="btn btn-primary py-2 fw-bold">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                                <polyline points="7 10 12 15 17 10"/>
                                                <line x1="12" y1="15" x2="12" y2="3"/>
                                            </svg>
                                            Unduh Dokumen (PDF)
                                        </a>
                                        <button type="button" class="btn btn-outline-secondary py-2" onclick="location.reload()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                            Daftarkan Pelanggan Baru
                                        </button>
                                    </div>
                                </div>

                                <!-- Kanan: Preview PDF Iframe -->
                                <div class="col-lg-8 col-md-12">
                                    <div style="border:1.5px solid #bfdbfe;border-radius:12px;overflow:hidden;background:#f0f7ff;">
                                        <!-- Header bar -->
                                        <div style="padding:.75rem 1rem;background:linear-gradient(90deg,#eff6ff,#dbeafe);border-bottom:1px solid #bfdbfe;display:flex;align-items:center;gap:.5rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                            <span style="font-size:.85rem;font-weight:700;color:#1e40af;">Preview Dokumen Persetujuan</span>
                                            <span style="margin-left:auto;">
                                                <span id="pdf-loading-badge" class="badge" style="background:#dbeafe;color:#1e40af;font-size:.75rem;">
                                                    <span class="spinner-border spinner-border-sm me-1" style="width:.65rem;height:.65rem;border-width:2px;vertical-align:middle;"></span>
                                                    Memuat PDF...
                                                </span>
                                                <span id="pdf-ready-badge" class="badge d-none" style="background:#dcfce7;color:#15803d;font-size:.75rem;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="20 6 9 17 4 12"/></svg>
                                                    Siap
                                                </span>
                                            </span>
                                        </div>
                                        <!-- Loading skeleton shimmer -->
                                        <div id="pdf-skeleton" style="padding:1.5rem;display:flex;flex-direction:column;gap:.75rem;">
                                            <div style="height:18px;border-radius:6px;background:linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%);background-size:200% 100%;animation:pdfShimmer 1.4s infinite;"></div>
                                            <div style="height:18px;border-radius:6px;width:80%;background:linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%);background-size:200% 100%;animation:pdfShimmer 1.4s infinite .1s;"></div>
                                            <div style="height:18px;border-radius:6px;width:65%;background:linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%);background-size:200% 100%;animation:pdfShimmer 1.4s infinite .2s;"></div>
                                            <div style="height:120px;border-radius:8px;margin-top:.5rem;background:linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%);background-size:200% 100%;animation:pdfShimmer 1.4s infinite .3s;"></div>
                                            <div style="height:18px;border-radius:6px;width:90%;background:linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%);background-size:200% 100%;animation:pdfShimmer 1.4s infinite .4s;"></div>
                                            <div style="height:18px;border-radius:6px;background:linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%);background-size:200% 100%;animation:pdfShimmer 1.4s infinite .5s;"></div>
                                            <div style="height:18px;border-radius:6px;width:75%;background:linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%);background-size:200% 100%;animation:pdfShimmer 1.4s infinite .6s;"></div>
                                        </div>
                                        <!-- PDF iframe -->
                                        <iframe id="pdf-preview-frame"
                                            src="about:blank"
                                            style="display:none;width:100%;height:700px;border:none;"
                                            title="Preview Dokumen Persetujuan">
                                        </iframe>
                                    </div>
                                </div>
                            </div>
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

        // =========================================================================
        // WIZARD STATE & NAVIGATION CONTROLLER (JIKA is_persetujuan === 'aktif')
        // =========================================================================
        const isPersetujuanAktif = "{{ $pages->is_persetujuan }}" === 'aktif';
        let currentStep = 1;
        let selectedTypeId = null;
        let selectedTypeName = '';
        let isSelectedTypePppoe = false;

        function setWizardStep(step) {
            currentStep = step;
            $('.wizard-step-pane').addClass('d-none');
            $('#wizard-step-' + step).removeClass('d-none');

            // Update stepper visual
            $('.step-item').removeClass('active completed');
            for (let i = 1; i <= 5; i++) {
                if (i < step) {
                    $('#step-nav-' + i).addClass('completed');
                } else if (i === step) {
                    $('#step-nav-' + i).addClass('active');
                }
            }

            window.scrollTo({ top: 120, behavior: 'smooth' });

            // Initialize signature pad size on step 4
            if (step === 4) {
                setTimeout(resizeCanvas, 100);
            }
        }

        $(document).ready(function() {
            if (isPersetujuanAktif) {
                // Step 1: Selection Cards
                $('.service-type-card').on('click', function() {
                    $('.service-type-card').removeClass('selected');
                    $(this).addClass('selected');

                    selectedTypeId = $(this).data('type-id');
                    selectedTypeName = $(this).data('type-name');
                    isSelectedTypePppoe = $(this).data('is-pppoe') == '1';

                    $('#types_id').val(selectedTypeId).trigger('change');
                    $('#type_name').val(isSelectedTypePppoe ? 'PPPOE' : '');
                    $('#btn-next-step-1').prop('disabled', false);
                });

                // Next Step 1
                $('#btn-next-step-1').on('click', function() {
                    if (isSelectedTypePppoe) {
                        setWizardStep(2); // Preview Dokumen Persetujuan
                    } else {
                        setWizardStep(3); // Skip langsung ke Input Data
                    }
                });

                // Back & Next Step 2
                $('#btn-back-step-2').on('click', function() {
                    setWizardStep(1);
                });

                $('#agreeCheckbox').on('change', function() {
                    $('#btn-next-step-2').prop('disabled', !this.checked);
                });

                $('#btn-next-step-2').on('click', function() {
                    setWizardStep(3);
                });

                // Back & Next Step 3
                $('#btn-back-step-3').on('click', function() {
                    if (isSelectedTypePppoe) {
                        setWizardStep(2);
                    } else {
                        setWizardStep(1);
                    }
                });

                $('#btn-next-step-3').on('click', function() {
                    // Validasi Step 3
                    const name = $('#name').val().trim();
                    const email = $('#email').val().trim();
                    const telp = $('#telp').val().trim();
                    const emailStatus = window.__emailChecked ?? null;
                    const phoneStatus = window.__phoneChecked ?? null;
                    const rts = $('#rts_id').val();
                    const rws = $('#rws_id').val();
                    const vlans = $('#vlans_id').val();
                    const odcs = $('#odcs_id').val();
                    const odps = $('#odps_id').val();
                    const olts = $('#olts_id').val();
                    const patchCore = $('#patch_core_id').val();
                    const ktpPhoto = $('#ktp_photo').val();
                    const isKtpAktif = "{{ $pages->is_ktp }}" === 'aktif';

                    if (!name) {
                        Toast.fire({ icon: 'warning', title: 'Nama Pelanggan wajib diisi!' });
                        $('#name').focus();
                        return;
                    }

                    // ── Validasi Email ──
                    if (!email) {
                        Toast.fire({ icon: 'warning', title: 'Email Pelanggan wajib diisi!' });
                        $('#email').focus();
                        return;
                    }

                    if (emailStatus === null) {
                        Toast.fire({ icon: 'warning', title: 'Silakan klik tombol "Cek Email" terlebih dahulu untuk memverifikasi email!' });
                        $('#email').focus();
                        return;
                    }

                    if (emailStatus === false) {
                        Toast.fire({ icon: 'error', title: 'Email tidak valid atau sudah terdaftar. Perbaiki dan cek ulang!' });
                        $('#email').focus();
                        return;
                    }

                    // ── Validasi No WhatsApp ──
                    if (!telp) {
                        Toast.fire({ icon: 'warning', title: 'Nomor Telepon wajib diisi!' });
                        $('#telp').focus();
                        return;
                    }

                    if (phoneStatus === null) {
                        Toast.fire({ icon: 'warning', title: 'Silakan klik tombol "Cek No HP" terlebih dahulu untuk memverifikasi WhatsApp!' });
                        $('#telp').focus();
                        return;
                    }

                    if (phoneStatus === false) {
                        Toast.fire({ icon: 'error', title: 'Nomor tidak terdaftar di WhatsApp atau tidak valid. Perbaiki dan cek ulang!' });
                        $('#telp').focus();
                        return;
                    }

                    if (isKtpAktif && !ktpPhoto) {
                        Toast.fire({ icon: 'warning', title: 'Foto KTP wajib diambil terlebih dahulu!' });
                        return;
                    }

                    if (!rts || !rws) {
                        Toast.fire({ icon: 'warning', title: 'RT dan RW wajib dipilih!' });
                        return;
                    }

                    if (!vlans || !odcs || !odps || !olts || !patchCore) {
                        Toast.fire({ icon: 'warning', title: 'Konfigurasi perangkat jaringan (VLAN/ODC/ODP/OLT/Patch Core) wajib lengkap!' });
                        return;
                    }

                    // Populate Summary for Step 4
                    $('#preview-name').text(name);
                    $('#preview-nik').text($('#nik').val() || '-');
                    $('#preview-telp').text(telp);
                    $('#preview-email').text($('#email').val() || '-');
                    $('#preview-service-type').text(selectedTypeName || $('#types_id option:selected').text());
                    $('#preview-mac').text($('#mac_address').val() || '-');
                    
                    const villageText = $('#villages_id option:selected').text() || '';
                    const hometownText = $('#hometowns_id option:selected').text() || '';
                    const rtText = $('#rts_id option:selected').text() || '';
                    const rwText = $('#rws_id option:selected').text() || '';
                    $('#preview-location').text(`${villageText} / ${hometownText} (RT ${rtText} / RW ${rwText})`);

                    if (isSelectedTypePppoe) {
                        $('#preview-row-paket').show();
                        $('#preview-row-wifi').show();
                        $('#preview-paket').text($('#paket_id option:selected').text() || '-');
                        $('#preview-wifi').text($('#name_wifi').val() || '-');
                    } else {
                        $('#preview-row-paket').hide();
                        $('#preview-row-wifi').hide();
                    }

                    setWizardStep(4);
                });

                // Back Step 4
                $('#btn-back-step-4').on('click', function() {
                    setWizardStep(3);
                });

                // Checkbox confirmation Step 4
                $('#confirmDataCheckbox').on('change', function() {
                    validateStep4();
                });

                // Submit Form via AJAX di Step 4
                $('#btn-submit-wizard').on('click', function() {
                    if (isCanvasBlank(canvasPad)) {
                        Toast.fire({ icon: 'warning', title: 'Silakan bubuhkan tanda tangan terlebih dahulu!' });
                        return;
                    }

                    if (!$('#confirmDataCheckbox').is(':checked')) {
                        Toast.fire({ icon: 'warning', title: 'Centang kotak konfirmasi keabsahan data & tanda tangan!' });
                        return;
                    }

                    const ttdDataUrl = canvasPad.toDataURL('image/png');
                    $('#tanda_tangan_customer').val(ttdDataUrl);

                    const btn = $('#btn-submit-wizard');
                    const text = $('#btn-submit-text');
                    const loading = $('#btn-submit-loading');

                    btn.prop('disabled', true);
                    text.addClass('d-none');
                    loading.removeClass('d-none');

                    const form = document.getElementById('customerForm');
                    const formData = new FormData(form);

                    $.ajax({
                        url: form.action,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .done(function(res) {
                        btn.prop('disabled', false);
                        text.removeClass('d-none');
                        loading.addClass('d-none');

                        if (res.status === 'success') {
                            // Populate Step 5 info
                            $('#final-uuid').text(res.data.uuid);
                            $('#final-name').text(res.data.name);
                            $('#final-telp').text(res.data.telp);
                            $('#btn-download-pdf').attr('href', res.data.download_url);

                            // Load PDF preview in iframe
                            const iframe = document.getElementById('pdf-preview-frame');
                            iframe.onload = function() {
                                // Only trigger when a real URL is loaded (not about:blank)
                                if (iframe.src !== 'about:blank' && iframe.src !== '') {
                                    document.getElementById('pdf-skeleton').style.display = 'none';
                                    iframe.style.display = 'block';
                                    document.getElementById('pdf-loading-badge').classList.add('d-none');
                                    document.getElementById('pdf-ready-badge').classList.remove('d-none');
                                }
                            };
                            iframe.src = res.data.download_url;

                            // Pindah ke Step 5
                            setWizardStep(5);
                        } else {
                            Toast.fire({ icon: 'error', title: res.message || 'Terjadi kesalahan saat menyimpan data.' });
                        }
                    })
                    .fail(function(xhr) {
                        btn.prop('disabled', false);
                        text.removeClass('d-none');
                        loading.addClass('d-none');

                        let errMsg = 'Gagal menyimpan pendaftaran.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errMsg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: errMsg
                        });
                    });
                });
            }
        });

        // =========================================================================
        // DIGITAL SIGNATURE PAD CANVAS
        // =========================================================================
        const canvasPad = document.getElementById('signature-pad');
        let ctxPad = canvasPad ? canvasPad.getContext('2d') : null;
        let isDrawing = false;
        let hasSignature = false;

        function resizeCanvas() {
            if (!canvasPad) return;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = canvasPad.getBoundingClientRect();
            canvasPad.width = rect.width * ratio;
            canvasPad.height = rect.height * ratio;
            ctxPad.scale(ratio, ratio);
            ctxPad.lineWidth = 2.5;
            ctxPad.lineCap = 'round';
            ctxPad.lineJoin = 'round';
            ctxPad.strokeStyle = '#0f172a';
        }

        function isCanvasBlank(canvas) {
            if (!canvas || !hasSignature) return true;
            return false;
        }

        function validateStep4() {
            const isConfirmed = $('#confirmDataCheckbox').is(':checked');
            $('#btn-submit-wizard').prop('disabled', !(hasSignature && isConfirmed));
        }

        if (canvasPad) {
            function getPos(e) {
                const rect = canvasPad.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                return {
                    x: clientX - rect.left,
                    y: clientY - rect.top
                };
            }

            function startDraw(e) {
                e.preventDefault();
                isDrawing = true;
                hasSignature = true;
                const pos = getPos(e);
                ctxPad.beginPath();
                ctxPad.moveTo(pos.x, pos.y);
            }

            function draw(e) {
                if (!isDrawing) return;
                e.preventDefault();
                const pos = getPos(e);
                ctxPad.lineTo(pos.x, pos.y);
                ctxPad.stroke();
            }

            function stopDraw() {
                if (isDrawing) {
                    isDrawing = false;
                    validateStep4();
                }
            }

            canvasPad.addEventListener('mousedown', startDraw);
            canvasPad.addEventListener('mousemove', draw);
            window.addEventListener('mouseup', stopDraw);

            canvasPad.addEventListener('touchstart', startDraw, { passive: false });
            canvasPad.addEventListener('touchmove', draw, { passive: false });
            window.addEventListener('touchend', stopDraw);

            $('#btn-clear-signature').on('click', function() {
                ctxPad.clearRect(0, 0, canvasPad.width, canvasPad.height);
                hasSignature = false;
                validateStep4();
            });

            window.addEventListener('resize', resizeCanvas);
        }

        // =========================================================================
        // REGULAR SINGLE FORM HANDLER (JIKA is_persetujuan === 'tidak')
        // =========================================================================
        if (!isPersetujuanAktif) {
            document.querySelector('#customerForm').addEventListener('submit', function(e) {
                const btn           = document.getElementById('btn');
                const text          = document.getElementById('btn-text');
                const loading       = document.getElementById('btn-loading');
                const ktpPhoto      = document.getElementById('ktp_photo');
                const emailInput    = document.getElementById('email');
                const phoneInput    = document.getElementById('telp');
                const emailFeedback = document.getElementById('emailFeedback');
                const phoneFeedback = document.getElementById('phoneFeedback');

                const isKtpAktif  = "{{ $pages->is_ktp }}" === 'aktif';
                const emailValue  = emailInput  ? emailInput.value.trim()  : '';
                const phoneValue  = phoneInput  ? phoneInput.value.trim()  : '';
                const emailStatus = window.__emailChecked ?? null;
                const phoneStatus = window.__phoneChecked ?? null;

                if (!emailValue) {
                    e.preventDefault();
                    Toast.fire({ icon: 'warning', title: 'Email Pelanggan wajib diisi!' });
                    emailInput && emailInput.focus();
                    return false;
                }

                if (emailStatus === null) {
                    e.preventDefault();
                    Toast.fire({ icon: 'warning', title: 'Klik tombol "Cek Email" terlebih dahulu sebelum mengirim data.' });
                    emailInput && emailInput.focus();
                    return false;
                }

                if (emailStatus === false) {
                    e.preventDefault();
                    Toast.fire({ icon: 'error', title: 'Email tidak valid atau sudah terdaftar.' });
                    emailInput && emailInput.focus();
                    return false;
                }

                if (!phoneValue) {
                    e.preventDefault();
                    Toast.fire({ icon: 'warning', title: 'Nomor Telepon wajib diisi!' });
                    phoneInput && phoneInput.focus();
                    return false;
                }

                if (phoneStatus === null) {
                    e.preventDefault();
                    Toast.fire({ icon: 'warning', title: 'Klik tombol "Cek No HP" terlebih dahulu sebelum mengirim data.' });
                    phoneInput && phoneInput.focus();
                    return false;
                }

                if (phoneStatus === false) {
                    e.preventDefault();
                    Toast.fire({ icon: 'error', title: 'Nomor tidak terdaftar di WhatsApp.' });
                    phoneInput && phoneInput.focus();
                    return false;
                }

                if (isKtpAktif && (!ktpPhoto || ktpPhoto.value === '')) {
                    e.preventDefault();
                    Toast.fire({
                        icon: "warning",
                        title: "Silahkan Ambil Foto KTP terlebih dahulu sebelum mengirim data."
                    });
                    return false;
                }

                btn.disabled = true;
                text.classList.add('d-none');
                loading.classList.remove('d-none');
            });
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        let latitude = position.coords.latitude;
                        let longitude = position.coords.longitude;

                        document.getElementById("latitude").value = latitude;
                        document.getElementById("longitude").value = longitude;

                        document.getElementById("map-container").style.display = "block";
                        document.getElementById("map-frame").src =
                            `https://www.google.com/maps?q=${latitude},${longitude}&hl=id&z=15&output=embed`;
                    },
                    function(error) {
                        console.error("Error mendapatkan lokasi:", error.message);
                    }
                );
            }
        });

        $("#types_id").change(function() {
            let value = $(this).find("option:selected").data("label");
            if (value === "PPPOE") {
                document.getElementById('pppoe-show').style.display = 'block';
                $("#type_name").val(value);
            } else {
                document.getElementById('pppoe-show').style.display = 'none';
                $("#type_name").val('');
                $("#paket_id").val('');
                $("#price_id").val('');
            }
        });
    </script>

    {{-- Inisialisasi Tom Select --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const noSearchIds = ['rts_id', 'rws_id', 'hometowns_id', 'villages_id', 'regencies_id', 'districts_id'];
            const noSortIds = ['odcs_id', 'odps_id', 'olts_id'];

            document.querySelectorAll('select.select-tom').forEach(function (el) {
                const useSearch   = !noSearchIds.includes(el.id);
                const useSort     = !noSortIds.includes(el.id);
                const placeholder = el.querySelector('option[value=""]')?.textContent?.trim() ?? 'Pilih...';

                const ts = new TomSelect(el, {
                    allowEmptyOption : true,
                    placeholder      : placeholder,
                    create           : false,
                    sortField        : useSort ? { field: 'text', direction: 'asc' } : false,
                    controlInput     : useSearch ? undefined : null,
                });

                ts.on('change', function () {
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });
        });
    </script>

    {{-- Fitur Cek Email --}}
    <script>
        (function () {
            const emailInput  = document.getElementById('email');
            const feedback    = document.getElementById('emailFeedback');
            const checkBtn    = document.getElementById('btn-check-email');
            const checkText   = document.getElementById('check-email-text');
            const checkLoader = document.getElementById('check-email-loading');

            window.__emailChecked = @error('email') false @else null @enderror;

            function isValidEmailFormat(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            function setFeedback(message, type) {
                if (!feedback) return;
                feedback.style.display = 'block';
                if (type === 'success') {
                    feedback.style.color = '#16a34a';
                    feedback.innerHTML   = '✓ ' + message;
                } else if (type === 'error') {
                    feedback.style.color = '#dc2626';
                    feedback.innerHTML   = '✕ ' + message;
                } else {
                    feedback.style.color = '#6b7280';
                    feedback.innerHTML   = message;
                }
            }

            function clearFeedback() {
                if (!feedback) return;
                feedback.style.display = 'none';
                feedback.innerHTML     = '';
            }

            function setLoading(loading) {
                if (!checkBtn) return;
                checkBtn.disabled       = loading;
                checkText.classList.toggle('d-none', loading);
                checkLoader.classList.toggle('d-none', !loading);
            }

            function resetOnChange() {
                window.__emailChecked = null;
                clearFeedback();
                checkBtn && checkBtn.classList.remove('btn-success', 'btn-danger');
                checkBtn && checkBtn.classList.add('btn-outline-secondary');
            }

            if (!emailInput || !checkBtn) return;

            emailInput.addEventListener('input', resetOnChange);

            checkBtn.addEventListener('click', function () {
                const email = emailInput.value.trim();

                if (!email) {
                    setFeedback('Masukkan email terlebih dahulu.', 'error');
                    return;
                }

                if (!isValidEmailFormat(email)) {
                    setFeedback('Format email tidak valid.', 'error');
                    window.__emailChecked = false;
                    checkBtn.classList.remove('btn-outline-secondary', 'btn-success');
                    checkBtn.classList.add('btn-danger');
                    return;
                }

                setLoading(true);
                clearFeedback();

                fetch("{{ route('input.data.checkEmail') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email: email }),
                })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    setLoading(false);
                    if (data.is_valid) {
                        setFeedback(data.message, 'success');
                        window.__emailChecked = true;
                        checkBtn.classList.remove('btn-outline-secondary', 'btn-danger');
                        checkBtn.classList.add('btn-success');
                    } else {
                        setFeedback(data.message, 'error');
                        window.__emailChecked = false;
                        checkBtn.classList.remove('btn-outline-secondary', 'btn-success');
                        checkBtn.classList.add('btn-danger');
                    }
                })
                .catch(function () {
                    setLoading(false);
                    setFeedback('Gagal mengecek email. Silakan coba lagi.', 'error');
                    window.__emailChecked = false;
                    checkBtn.classList.remove('btn-success');
                    checkBtn.classList.add('btn-danger');
                });
            });
        })();
    </script>

    {{-- Fitur Cek Phone / WhatsApp --}}
    <script>
        (function () {
            const phoneInput  = document.getElementById('telp');
            const feedback    = document.getElementById('phoneFeedback');
            const checkBtn    = document.getElementById('btn-check-phone');
            const checkText   = document.getElementById('check-phone-text');
            const checkLoader = document.getElementById('check-phone-loading');

            window.__phoneChecked = @error('telp') false @else null @enderror;

            function isValidPhoneFormat(phone) {
                return /^[+]?[\d]{9,15}$/.test(phone.replace(/\s/g, ''));
            }

            function setFeedback(message, type) {
                if (!feedback) return;
                feedback.style.display = 'block';
                if (type === 'success') {
                    feedback.style.color = '#16a34a';
                    feedback.innerHTML   = '✓ ' + message;
                } else if (type === 'error') {
                    feedback.style.color = '#dc2626';
                    feedback.innerHTML   = '✕ ' + message;
                } else {
                    feedback.style.color = '#6b7280';
                    feedback.innerHTML   = message;
                }
            }

            function clearFeedback() {
                if (!feedback) return;
                feedback.style.display = 'none';
                feedback.innerHTML     = '';
            }

            function setLoading(loading) {
                if (!checkBtn) return;
                checkBtn.disabled       = loading;
                checkText.classList.toggle('d-none', loading);
                checkLoader.classList.toggle('d-none', !loading);
            }

            function resetOnChange() {
                window.__phoneChecked = null;
                clearFeedback();
                checkBtn && checkBtn.classList.remove('btn-success', 'btn-danger');
                checkBtn && checkBtn.classList.add('btn-outline-secondary');
            }

            if (!phoneInput || !checkBtn) return;

            phoneInput.addEventListener('input', resetOnChange);

            checkBtn.addEventListener('click', function () {
                const phone = phoneInput.value.trim();

                if (!phone) {
                    setFeedback('Masukkan nomor telepon terlebih dahulu.', 'error');
                    return;
                }

                if (!isValidPhoneFormat(phone)) {
                    setFeedback('Format nomor telepon tidak valid.', 'error');
                    window.__phoneChecked = false;
                    checkBtn.classList.remove('btn-outline-secondary', 'btn-success');
                    checkBtn.classList.add('btn-danger');
                    return;
                }

                setLoading(true);
                clearFeedback();

                fetch("{{ route('input.data.checkPhone') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ telp: phone }),
                })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    setLoading(false);
                    if (data.is_valid) {
                        setFeedback(data.message, 'success');
                        window.__phoneChecked = true;
                        checkBtn.classList.remove('btn-outline-secondary', 'btn-danger');
                        checkBtn.classList.add('btn-success');
                    } else {
                        setFeedback(data.message, 'error');
                        window.__phoneChecked = false;
                        checkBtn.classList.remove('btn-outline-secondary', 'btn-success');
                        checkBtn.classList.add('btn-danger');
                    }
                })
                .catch(function () {
                    setLoading(false);
                    setFeedback('Gagal mengecek nomor. Silakan coba lagi.', 'error');
                    window.__phoneChecked = false;
                    checkBtn.classList.remove('btn-success');
                    checkBtn.classList.add('btn-danger');
                });
            });
        })();
    </script>

    @if ($isMacValidationActive == true)
        <script>
            document.getElementById('mac_address').addEventListener('blur', function() {
                fetch("{{ route('input.data.checkMacAddress') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            mac_address: this.value
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        const feedback = document.getElementById('macFeedback');
                        feedback.textContent = data.message;
                        feedback.style.color = data.valid ? 'green' : 'red';

                        let routerName = document.getElementById('router_name');
                        let routersId = document.getElementById('routers_id');

                        if (data.valid == true) {
                            routerName.value = data.router.name || '';
                            routersId.value = data.router.id || '';
                        } else {
                            routerName.value = '';
                            routersId.value = '';
                        }
                    });
            });
        </script>
    @endif

    @if ($pages->is_ktp === 'aktif')
        <script>
            let stream = null;
            let video, canvas, capturedPhoto, photoPreview, cameraView, btnCapture, btnRetake, ktpPhotoInput;

            function capturePhoto() {
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                const imageData = canvas.toDataURL('image/jpeg', 0.92);
                ktpPhotoInput.value = imageData;

                capturedPhoto.src = imageData;
                photoPreview.style.display = 'block';
                cameraView.style.display = 'none';
                btnCapture.style.display = 'none';
                btnRetake.style.display = 'inline-block';

                stopCamera();
                extractNikFromImage(imageData);
            }

            function retakePhoto() {
                photoPreview.style.display = 'none';
                cameraView.style.display = 'block';
                btnCapture.style.display = 'inline-block';
                btnRetake.style.display = 'none';
                ktpPhotoInput.value = '';
                startCamera();
            }

            function stopCamera() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
            }

            async function startCamera() {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                    video.srcObject = stream;
                } catch (err) {
                    console.error("Camera access error:", err);
                }
            }

            function extractNikFromImage(imageData) {
                const nikInput = document.getElementById('nik');
                nikInput.value = "Memproses OCR...";

                Tesseract.recognize(imageData, 'ind')
                    .then(({ data: { text } }) => {
                        const nikMatch = text.match(/\b\d{16}\b/);
                        if (nikMatch) {
                            nikInput.value = nikMatch[0];
                        } else {
                            nikInput.value = "";
                            Toast.fire({ icon: "warning", title: "NIK tak terbaca, silakan isi manual." });
                        }
                    })
                    .catch(() => {
                        nikInput.value = "";
                    });
            }

            document.addEventListener('DOMContentLoaded', function() {
                video = document.getElementById('video');
                canvas = document.getElementById('canvas');
                capturedPhoto = document.getElementById('captured-photo');
                photoPreview = document.getElementById('photo-preview');
                cameraView = document.getElementById('camera-view');
                btnCapture = document.getElementById('btn-capture');
                btnRetake = document.getElementById('btn-retake');
                ktpPhotoInput = document.getElementById('ktp_photo');

                if (video) {
                    startCamera();
                }
            });
        </script>
    @endif
@endpush

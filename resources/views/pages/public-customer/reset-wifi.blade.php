@extends('layouts.app-pages')

@section('title', 'Reset Password WiFi — CIO Network')

@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: radial-gradient(circle at 10% 20%, rgba(30, 41, 59, 0.05) 0%, rgba(37, 99, 235, 0.05) 90%), #f8fafc;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            display: block !important;
            padding: 0;
        }

        .page {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: flex-start !important;
            min-height: 100vh !important;
            padding: 3.5rem 1.5rem 5rem !important;
            background: transparent !important;
            width: 100% !important;
            box-sizing: border-box;
        }

        .ambient-orb-1 {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.08) 0%, transparent 70%);
            top: -100px;
            left: -100px;
            z-index: -1;
        }

        .ambient-orb-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, transparent 70%);
            bottom: -150px;
            right: -100px;
            z-index: -1;
        }

        .wizard-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.06), 0 0 0 1px rgba(15, 23, 42, 0.03);
            border-radius: 24px;
            width: 100%;
            max-width: 460px;
            overflow: hidden;
            transition: all 0.3s;
            margin: auto 0;
            flex-shrink: 0 !important;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #2563eb 100%);
            padding: 3.5rem 2.25rem 3rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .brand-logo-container {
            display: inline-flex;
            gap: 16px;
            padding: 10px 18px;
            background: #ffffff;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .brand-logo-container img {
            height: 30px;
            width: auto;
            object-fit: contain;
        }

        .card-header-gradient h1 {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
            margin-top: 0;
        }

        .card-header-gradient p {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.85rem;
            margin: 0;
            font-weight: 500;
            line-height: 1.5;
            max-width: 320px;
        }

        .card-body-content {
            padding: 2rem;
        }

        .form-label-custom {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            margin-bottom: 0.6rem;
            display: block;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-icon-wrapper {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
            z-index: 5;
        }

        .form-control-custom {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.75rem;
            font-size: 0.925rem;
            font-weight: 500;
            background: rgba(248, 250, 252, 0.8);
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            color: #0f172a;
            transition: all 0.2s ease-in-out;
            outline: none;
        }

        .form-control-custom:focus {
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .form-control-custom:focus + .input-icon-wrapper {
            color: #3b82f6;
        }

        .btn-toggle-password {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .btn-toggle-password:hover {
            color: #475569;
        }

        .form-helper-text {
            font-size: 0.78rem;
            color: #475569;
            margin-top: 0.65rem;
            line-height: 1.45;
            display: flex;
            gap: 0.5rem;
            align-items: flex-start;
            background: #f8fafc;
            padding: 0.85rem;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
        }

        .form-helper-text svg {
            color: #2563eb;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .btn-primary-custom {
            width: 100%;
            padding: 0.9rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            border-radius: 14px;
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3);
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 15px 25px -5px rgba(37, 99, 235, 0.4);
            transform: translateY(-1px);
        }

        .btn-secondary-custom {
            width: 100%;
            padding: 0.8rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            background: transparent;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-secondary-custom:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .btn-success-custom {
            width: 100%;
            padding: 0.9rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 14px;
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.3);
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-success-custom:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 15px 25px -5px rgba(16, 185, 129, 0.4);
            transform: translateY(-1px);
        }

        .alert-custom-error {
            background: rgba(254, 226, 226, 0.7);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #b91c1c;
            padding: 0.85rem 1rem;
            border-radius: 14px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: none;
            align-items: center;
            gap: 8px;
            animation: slideIn 0.3s ease-out;
        }

        .alert-custom-success {
            background: rgba(209, 250, 229, 0.7);
            border: 1px solid rgba(52, 211, 153, 0.2);
            color: #065f46;
            padding: 0.85rem 1rem;
            border-radius: 14px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: none;
            align-items: center;
            gap: 8px;
            animation: slideIn 0.3s ease-out;
        }

        .wizard-step {
            display: none;
        }

        .wizard-step.active {
            display: block;
            animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .result-box {
            background: rgba(241, 245, 249, 0.5);
            border-radius: 18px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .result-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .result-item:last-of-type {
            border-bottom: none;
            padding-bottom: 0;
        }

        .result-lbl {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
        }

        .result-val {
            font-size: 0.925rem;
            font-weight: 600;
            color: #0f172a;
            max-width: 65%;
            text-align: right;
            word-break: break-all;
        }

        .result-val.highlight {
            font-family: monospace;
            background: #dbeafe;
            color: #1e40af;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.825rem;
        }

        .badge-step {
            font-size: 0.75rem;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.97); }
            to { opacity: 1; transform: scale(1); }
        }

        /* ── RESPONSIVE MEDIA QUERIES ── */
        @media (max-width: 480px) {
            .page {
                padding: 2.5rem 1rem 6rem !important;
            }
            .wizard-card {
                border-radius: 20px;
            }
            .card-header-gradient {
                padding: 2.75rem 1.5rem 2.5rem;
            }
            .card-header-gradient h1 {
                font-size: 1.3rem;
            }
            .card-header-gradient p {
                font-size: 0.8rem;
            }
            .card-body-content {
                padding: 1.5rem;
            }
            .form-control-custom {
                padding: 0.75rem 1rem 0.75rem 2.5rem;
                font-size: 0.9rem;
            }
            .input-icon-wrapper {
                left: 0.85rem;
            }
        }

        @media (max-width: 400px) {
            .result-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
                padding: 0.75rem 0;
            }
            .result-val {
                max-width: 100%;
                text-align: left;
            }
        }
    </style>
@endpush

@section('content')
    <div class="ambient-orb-1"></div>
    <div class="ambient-orb-2"></div>

    <div class="wizard-card">
        <!-- Card Header -->
        <div class="card-header-gradient">
            <div class="brand-logo-container">
                <img src="{{ asset('img/logo_2.jpeg') }}" alt="Logo 2">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo 1">
            </div>
            <div class="badge-step" id="badge-step-text">Langkah 1 dari 3</div>
            <h1 id="header-title">Cek Data Pelanggan</h1>
            <p id="header-desc">Khusus pelanggan paket HOME / PPPoE, masukkan MAC Address untuk mencari profil pelanggan dan detail router Anda</p>
        </div>

        <div class="card-body-content">
            <!-- Alert Error -->
            <div class="alert-custom-error" id="wizard-alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <span id="alert-error-text">Terjadi kesalahan.</span>
            </div>

            <!-- Alert Success -->
            <div class="alert-custom-success" id="wizard-alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                <span id="alert-success-text">Proses sukses.</span>
            </div>

            <!-- STEP 1: SEARCH CUSTOMER -->
            <div class="wizard-step active" id="step-search">
                <form id="search-form" autocomplete="off">
                    <div class="input-group-custom">
                        <label class="form-label-custom" for="mac_address">MAC Address Router</label>
                        <div style="position: relative;">
                            <input type="text" id="mac_address" class="form-control-custom" placeholder="Contoh: AA:BB:CC:DD:EE:FF" required>
                            <div class="input-icon-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                                    <line x1="8" y1="21" x2="16" y2="21" />
                                    <line x1="12" y1="17" x2="12" y2="21" />
                                </svg>
                            </div>
                        </div>
                        <div class="form-helper-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <span>MAC Address tertera pada stiker fisik di bagian bawah/belakang router WiFi Anda. Digunakan sebagai ID unik untuk mencocokkan data pelanggan Anda.</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary-custom" id="btn-search-submit">
                        <span>Cari Pelanggan</span>
                        <div class="spinner-border spinner-border-sm text-light" id="spinner-search" role="status" style="display: none; width: 1.1rem; height: 1.1rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                </form>
            </div>

            <!-- STEP 2: VIEW CUSTOMER & WIFI DETAILS -->
            <div class="wizard-step" id="step-details">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    <h3 style="color: #0f172a; font-weight: 800; font-size: 1.1rem; margin-bottom: 0.15rem;">Pelanggan Teridentifikasi</h3>
                    <p style="color: #64748b; font-size: 0.8rem; margin: 0;">Berikut detail keanggotaan dan router Anda</p>
                </div>

                <!-- Customer Info -->
                <div class="result-box">
                    <div class="result-header" style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: #3b82f6; letter-spacing: 0.05em; cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 2px 0;">
                        <span>Profil Pelanggan</span>
                        <svg class="chevron-icon" style="transition: transform 0.2s; transform: rotate(180deg); color: #3b82f6;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                    <div class="result-body" style="margin-top: 0.5rem;">
                        <div class="result-item">
                            <span class="result-lbl">ID Pelanggan</span>
                            <span class="result-val highlight" id="detail-id">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-lbl">Nama Pelanggan</span>
                            <span class="result-val" id="detail-name">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-lbl">No. HP / WA</span>
                            <span class="result-val" id="detail-telp">-</span>
                        </div>
                    </div>
                </div>

                <!-- WiFi details -->
                <div class="result-box">
                    <div class="result-header" style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: #3b82f6; letter-spacing: 0.05em; cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 2px 0;">
                        <span>Detail Kredensial WiFi & PPPoE</span>
                        <svg class="chevron-icon" style="transition: transform 0.2s; transform: rotate(0deg); color: #3b82f6;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                    <div class="result-body" style="display: none; margin-top: 0.5rem;">
                        <div class="result-item" id="row-pppoe-username">
                            <span class="result-lbl">Username PPPoE</span>
                            <span class="result-val" style="font-weight: 700;" id="detail-pppoe-username">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-lbl">Nama WiFi (SSID)</span>
                            <span class="result-val" style="font-weight: 700;" id="detail-wifi-name">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-lbl">Password WiFi</span>
                            <span class="result-val" id="detail-wifi-password">-</span>
                        </div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <button type="button" class="btn-primary-custom" id="btn-goto-reset">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 2v6h-6" />
                            <path d="M21 13a9 9 0 1 1-3-7.7L21 8" />
                        </svg>
                        Ganti Password WiFi
                    </button>
                    <button type="button" class="btn-secondary-custom" id="btn-reset-to-search">
                        Cari Pelanggan Lain
                    </button>
                </div>
            </div>

            <!-- STEP 3: RESET PASSWORD FORM (MOCK SIMULATION) -->
            <div class="wizard-step" id="step-reset-form">
                <form id="reset-password-form" autocomplete="off">
                    <div style="background: #eff6ff; border-radius: 12px; padding: 0.85rem; border: 1.5px solid #bfdbfe; font-size: 0.78rem; color: #1e3a8a; line-height: 1.45; margin-bottom: 1.5rem; display: flex; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb; flex-shrink: 0; margin-top: 1px;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <span><strong>Informasi:</strong> Permintaan perubahan Anda akan dikirimkan ke tim ONC untuk divalidasi terlebih dahulu. Perubahan akan aktif di router/sistem setelah tim ONC memberikan persetujuan. Pastikan nomor WhatsApp yang terdaftar pada sistem kami adalah nomor WhatsApp yang aktif.</span>
                    </div>

                    <!-- New Wifi Name -->
                    <div class="input-group-custom">
                        <label class="form-label-custom" for="new_wifi_name">Nama WiFi (SSID) Baru (Opsional)</label>
                        <div style="position: relative;">
                            <input type="text" id="new_wifi_name" class="form-control-custom" placeholder="Nama WiFi Anda (Biarkan kosong jika tidak diubah)">
                            <div class="input-icon-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12.5a5 5 0 0 1 7-7 5 5 0 0 1 7 7" />
                                    <path d="M12 17.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Z" />
                                    <path d="M9 14.5a3 3 0 0 1 6 0" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- New Wifi Password -->
                    <div class="input-group-custom">
                        <label class="form-label-custom" for="new_wifi_password">Password WiFi Baru (Min. 8 Karakter)</label>
                        <div style="position: relative;">
                            <input type="password" id="new_wifi_password" class="form-control-custom" placeholder="Masukkan password baru WiFi" required minlength="8">
                            <div class="input-icon-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </div>
                            <button type="button" class="btn-toggle-password" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                    </div>


                    <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 2rem;">
                        <button type="submit" class="btn-success-custom" id="btn-save-submit">
                            <span>Simpan Perubahan</span>
                            <div class="spinner-border spinner-border-sm text-light" id="spinner-save" role="status" style="display: none; width: 1.1rem; height: 1.1rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </button>
                        <button type="button" class="btn-secondary-custom" id="btn-cancel-reset">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // State
            let currentCustomer = null;

            // DOM Elements
            const $wizardAlertError = $('#wizard-alert-error');
            const $alertErrorText = $('#alert-error-text');
            const $wizardAlertSuccess = $('#wizard-alert-success');
            const $alertSuccessText = $('#alert-success-text');

            const $stepSearch = $('#step-search');
            const $stepDetails = $('#step-details');
            const $stepResetForm = $('#step-reset-form');

            const $badgeStepText = $('#badge-step-text');
            const $headerTitle = $('#header-title');
            const $headerDesc = $('#header-desc');

            // ── UTILITIES ──
            function showStep(stepId) {
                $('.wizard-step').removeClass('active');
                $wizardAlertError.hide();
                $wizardAlertSuccess.hide();

                if (stepId === 'search') {
                    $badgeStepText.text('Langkah 1 dari 3');
                    $headerTitle.text('Cek Data Pelanggan');
                    $headerDesc.text('Khusus pelanggan paket HOME / PPPoE, masukkan MAC Address untuk mencari profil pelanggan dan detail router Anda');
                    $stepSearch.addClass('active');
                } else if (stepId === 'details') {
                    $badgeStepText.text('Langkah 2 dari 3');
                    $headerTitle.text('Verifikasi Informasi');
                    $headerDesc.text('Periksa detail akun dan kredensial WiFi terdaftar di sistem kami');
                    $stepDetails.addClass('active');
                } else if (stepId === 'reset') {
                    $badgeStepText.text('Langkah 3 dari 3');
                    $headerTitle.text('Reset Password');
                    $headerDesc.text('Isi formulir berikut dengan konfigurasi kredensial baru Anda');
                    $stepResetForm.addClass('active');
                }
            }

            function showError(msg) {
                $alertErrorText.text(msg);
                $wizardAlertError.css('display', 'flex').hide().slideDown(200);
                $wizardAlertSuccess.hide();
            }

            function showSuccess(msg) {
                $alertSuccessText.text(msg);
                $wizardAlertSuccess.css('display', 'flex').hide().slideDown(200);
                $wizardAlertError.hide();
            }

            // ── PASSWORD VISIBILITY TOGGLE ──
            $('.btn-toggle-password').on('click', function(e) {
                e.preventDefault();
                const $btn = $(this);
                const $input = $btn.siblings('input');
                const isPassword = $input.attr('type') === 'password';

                $input.attr('type', isPassword ? 'text' : 'password');

                const eyeIcon = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                `;
                const eyeOffIcon = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                        <line x1="1" y1="1" x2="23" y2="23" />
                    </svg>
                `;

                $btn.html(isPassword ? eyeOffIcon : eyeIcon);
            });

            // ── STEP 1: SUBMIT SEARCH ──
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                const macAddress = $('#mac_address').val().trim();

                if (!macAddress) {
                    showError('Masukkan MAC Address terlebih dahulu.');
                    return;
                }

                $wizardAlertError.hide();
                $('#btn-search-submit').prop('disabled', true);
                $('#spinner-search').show();

                $.ajax({
                    url: "{{ route('public.customer.reset_wifi.search') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        mac_address: macAddress
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success' && response.data) {
                            currentCustomer = response.data;

                            // Populate details
                            $('#detail-id').text(currentCustomer.id);
                            $('#detail-name').text(currentCustomer.name);
                            $('#detail-telp').text(currentCustomer.telp || '-');

                            if (currentCustomer.pppoe_username) {
                                $('#detail-pppoe-username').text(currentCustomer.pppoe_username);
                                $('#row-pppoe-username').show();
                            } else {
                                $('#row-pppoe-username').hide();
                            }

                            $('#detail-wifi-name').text(currentCustomer.name_wifi || '-');
                            
                            // Mask WiFi password (hide some of the last characters with '*')
                            const rawPassword = currentCustomer.password_wifi || '';
                            let maskedPassword = '-';
                            if (rawPassword) {
                                const len = rawPassword.length;
                                const visibleLen = Math.min(4, Math.max(1, Math.floor(len / 2)));
                                maskedPassword = rawPassword.slice(0, visibleLen) + '*'.repeat(len - visibleLen);
                            }
                            $('#detail-wifi-password').text(maskedPassword);

                            // Set initial values on reset form step 3
                            $('#new_wifi_name').val(currentCustomer.name_wifi || '');
                            $('#new_wifi_password').val('');

                            // Transition to details
                            showStep('details');
                        } else {
                            showError('Terjadi kesalahan memproses respons server.');
                        }
                    },
                    error: function(xhr) {
                        let errMsg = 'Gagal melakukan pencarian.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        } else if (xhr.status === 404) {
                            errMsg = 'Data Pelanggan tidak ditemukan.';
                        }
                        showError(errMsg);
                    },
                    complete: function() {
                        $('#btn-search-submit').prop('disabled', false);
                        $('#spinner-search').hide();
                    }
                });
            });

            // ── STEP 2: INTERACTIONS ──
            $('#btn-reset-to-search').on('click', function() {
                $('#mac_address').val('');
                currentCustomer = null;
                showStep('search');
            });

            $('#btn-goto-reset').on('click', function() {
                showStep('reset');
            });

            // ── STEP 3: MOCK SUBMIT ──
            $('#btn-cancel-reset').on('click', function() {
                showStep('details');
            });

            $('#reset-password-form').on('submit', function(e) {
                e.preventDefault();

                const wifiPass = $('#new_wifi_password').val();
                if (wifiPass.length < 8) {
                    showError('Password WiFi minimal harus 8 karakter.');
                    return;
                }

                $wizardAlertError.hide();
                $wizardAlertSuccess.hide();

                const $btnSave = $('#btn-save-submit');
                const $spinnerSave = $('#spinner-save');

                $btnSave.prop('disabled', true);
                $spinnerSave.show();

                $.ajax({
                    url: "{{ route('public.customer.reset_wifi.submit') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        mac_address: $('#mac_address').val().trim(),
                        name_wifi: $('#new_wifi_name').val().trim(),
                        password_wifi: wifiPass
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success') {
                            showSuccess(response.message || 'Permintaan perubahan password berhasil diajukan.');
                            $('#reset-password-form').find('input, button').prop('disabled', true);
                            
                            // Scroll to top of card to make alert visible
                            $('.page, html, body').animate({ scrollTop: 0 }, 'slow');

                            setTimeout(function() {
                                // Reset to step 1
                                $('#mac_address').val('');
                                $('#new_wifi_name').val('');
                                $('#new_wifi_password').val('');
                                $('#reset-password-form').find('input, button').prop('disabled', false);
                                currentCustomer = null;
                                showStep('search');
                            }, 5000);
                        } else {
                            showError(response.message || 'Gagal mengajukan permintaan.');
                            $('.page, html, body').animate({ scrollTop: 0 }, 'slow');
                        }
                    },
                    error: function(xhr) {
                        let errMsg = 'Gagal mengajukan permintaan perubahan password.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        }
                        showError(errMsg);
                        $('.page, html, body').animate({ scrollTop: 0 }, 'slow');
                    },
                    complete: function() {
                        $btnSave.prop('disabled', false);
                        $spinnerSave.hide();
                    }
                });
            });

            // ── COLLAPSIBLE CARDS TOGGLE ──
            $('.result-header').on('click', function() {
                const $body = $(this).siblings('.result-body');
                const $icon = $(this).find('.chevron-icon');

                $body.slideToggle(200);

                // Toggle rotation by checking inline style attribute
                const styleAttr = $icon.attr('style') || '';
                if (styleAttr.includes('rotate(180deg)')) {
                    $icon.attr('style', 'transition: transform 0.2s; transform: rotate(0deg); color: #3b82f6;');
                } else {
                    $icon.attr('style', 'transition: transform 0.2s; transform: rotate(180deg); color: #3b82f6;');
                }
            });
        });
    </script>
@endpush

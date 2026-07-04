@extends('layouts.app-pages')

@section('title', 'Pencarian Pelanggan — CIO Network')

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

        .search-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.06), 0 0 0 1px rgba(15, 23, 42, 0.03);
            border-radius: 24px;
            width: 100%;
            max-width: 440px;
            overflow: hidden;
            transition: all 0.3s;
            margin: auto 0;
            flex-shrink: 0 !important;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #2563eb 100%);
            padding: 4.5rem 2.25rem 4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .brand-badge {
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #60a5fa;
            margin-bottom: 0.75rem;
        }

        .brand-logo-container {
            display: inline-flex;
            gap: 16px;
            padding: 10px 18px;
            background: #ffffff;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
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
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.75rem;
            margin-top: 0;
        }

        .card-header-gradient p {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.9rem;
            margin: 0;
            font-weight: 500;
            line-height: 1.5;
            max-width: 290px;
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
            margin-bottom: 1.75rem;
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

        .btn-search {
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

        .btn-search:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 15px 25px -5px rgba(37, 99, 235, 0.4);
            transform: translateY(-1px);
        }

        .alert-error {
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

        .results-panel {
            display: none;
            animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .result-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.85rem 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .result-item:last-of-type {
            border-bottom: none;
            padding-bottom: 1.5rem;
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

        .btn-login-redirect {
            width: 100%;
            padding: 0.9rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            background: #0f172a;
            border: none;
            border-radius: 14px;
            box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.3);
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            margin-bottom: 1rem;
        }

        .btn-login-redirect:hover {
            background: #1e293b;
            color: #ffffff;
        }

        .btn-reset-search {
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

        .btn-reset-search:hover {
            background: #f1f5f9;
            color: #0f172a;
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
            .search-card {
                border-radius: 20px;
            }
            .card-header-gradient {
                padding: 3.75rem 1.5rem 3.5rem;
            }
            .brand-badge {
                font-size: 0.8rem;
                margin-bottom: 0.5rem;
            }
            .brand-logo-container {
                margin-bottom: 1rem;
                padding: 8px 14px;
            }
            .brand-logo-container img {
                height: 24px;
            }
            .card-header-gradient h1 {
                font-size: 1.45rem;
                margin-bottom: 0.5rem;
            }
            .card-header-gradient p {
                font-size: 0.85rem;
                max-width: 250px;
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
            .btn-search, .btn-login-redirect, .btn-reset-search {
                padding: 0.8rem;
                font-size: 0.9rem;
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

    <div class="search-card">
        <!-- Card Header -->
        <div class="card-header-gradient">
            <div class="brand-logo-container">
                <img src="{{ asset('img/logo_2.jpeg') }}" alt="Logo 2">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo 1">
            </div>
            <h1>Pencarian Pelanggan</h1>
            <p>Khusus pelanggan paket HOME / PPPoE, masukkan MAC Address untuk mencari data pelanggan dan melakukan pembayaran</p>
        </div>

        <div class="card-body-content">
            <!-- Alert Error -->
            <div class="alert-error" id="search-alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <span id="alert-text">Data Pelanggan tidak ditemukan.</span>
            </div>

            <!-- Form Search Panel -->
            <form id="search-form" autocomplete="off">
                <!-- MAC Address -->
                <div class="input-group-custom">
                    <label class="form-label-custom" for="mac_address">MAC Address</label>
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
                        <span><strong>Petunjuk:</strong> MAC Address adalah identitas fisik unik perangkat router Anda. Biasanya tertera pada stiker di bagian bawah/belakang router. Ini digunakan untuk mencocokkan data ID Pelanggan dan mempermudah proses pembayaran tagihan Anda secara otomatis.</span>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-search" id="btn-submit">
                    <span id="btn-text">Cari Pelanggan</span>
                    <div class="spinner-border spinner-border-sm text-light" id="btn-spinner" role="status" style="display: none; width: 1.1rem; height: 1.1rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
            </form>

            <!-- Results Display Panel -->
            <div class="results-panel" id="results-panel">
                <div style="text-align: center; margin-bottom: 1.75rem;">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    <h3 style="color: #0f172a; font-weight: 800; font-size: 1.15rem; margin-bottom: 0.25rem;">Data Ditemukan</h3>
                    <p style="color: #64748b; font-size: 0.8rem; margin: 0;">Berikut detail data keanggotaan Anda</p>
                </div>

                <div style="background: rgba(241, 245, 249, 0.5); border-radius: 18px; padding: 1.25rem; margin-bottom: 1.75rem; border: 1px solid #e2e8f0;">
                    <div class="result-item">
                        <span class="result-lbl">ID Pelanggan</span>
                        <span class="result-val highlight" id="res-id">-</span>
                    </div>
                    <div class="result-item">
                        <span class="result-lbl">Nama</span>
                        <span class="result-val" id="res-name">-</span>
                    </div>
                    <div class="result-item">
                        <span class="result-lbl">Email</span>
                        <span class="result-val" id="res-email">-</span>
                    </div>
                    <div class="result-item">
                        <span class="result-lbl">No. Telephone</span>
                        <span class="result-val" id="res-telp">-</span>
                    </div>
                </div>

                <!-- Client Area Direct Redirect with Local Proxy Autofill -->
                <a href="#" target="_blank" class="btn-login-redirect" id="link-client-login">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-login" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                        <path d="M20 12l-7 0" />
                        <path d="M20 12l-3 3" />
                        <path d="M20 12l-3 -3" />
                    </svg>
                    Masuk ke Client Area
                </a>

                <button type="button" class="btn-reset-search" id="btn-reset">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67" />
                    </svg>
                    Cari Pelanggan Lain
                </button>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            const $searchForm = $('#search-form');
            const $resultsPanel = $('#results-panel');
            const $searchAlert = $('#search-alert');
            const $alertText = $('#alert-text');
            const $btnSubmit = $('#btn-submit');
            const $btnText = $('#btn-text');
            const $btnSpinner = $('#btn-spinner');

            $searchForm.on('submit', function(e) {
                e.preventDefault();

                const macAddress = $('#mac_address').val().trim();

                if (!macAddress) {
                    showAlert('Harap isi MAC Address.');
                    return;
                }

                // Show loading state
                $searchAlert.hide();
                $btnSubmit.prop('disabled', true);
                $btnText.hide();
                $btnSpinner.show();

                $.ajax({
                    url: "{{ route('public.customer.search.post') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        mac_address: macAddress
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success' && response.data) {
                            // Populate values
                            $('#res-id').text(response.data.id);
                            $('#res-name').text(response.data.name);
                            $('#res-email').text(response.data.email || '-');
                            $('#res-telp').text(response.data.telp || '-');

                            // Set up the clientarea direct redirect link via local proxy
                            const clientProxyUrl = `{{ route('public.customer.clientarea_login') }}?username=${encodeURIComponent(response.data.id)}&password=${encodeURIComponent(response.data.id)}`;
                            $('#link-client-login').attr('href', clientProxyUrl);

                            // Toggle displays with transition
                            $searchForm.slideUp(250, function() {
                                $resultsPanel.fadeIn(250);
                            });
                        } else {
                            showAlert('Format respons server tidak cocok.');
                        }
                    },
                    error: function(xhr) {
                        let errMsg = 'Terjadi kesalahan sistem.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        } else if (xhr.status === 404) {
                            errMsg = 'Data Pelanggan tidak ditemukan.';
                        }
                        showAlert(errMsg);
                    },
                    complete: function() {
                        // Reset submit button state
                        $btnSubmit.prop('disabled', false);
                        $btnSpinner.hide();
                        $btnText.show();
                    }
                });
            });

            $('#btn-reset').on('click', function() {
                // Reset inputs and values
                $('#mac_address').val('');
                $searchAlert.hide();

                // Swap views back
                $resultsPanel.fadeOut(200, function() {
                    $searchForm.slideDown(200);
                });
            });

            function showAlert(message) {
                $alertText.text(message);
                $searchAlert.css('display', 'flex').hide().slideDown(200);
            }
        });
    </script>
@endpush

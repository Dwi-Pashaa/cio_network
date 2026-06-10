@extends('layouts.app')

@section('title', 'Edit Customer: ' . $customer->name)

@push('css')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #ffa400 0%, #ff6b6b 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        * {
            transition: all 0.3s ease;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .org-card {
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            background: white;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .org-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem !important;
            border-radius: 16px 16px 0 0;
        }

        .org-header .org-card-title {
            color: white;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .org-header .btn-outline-secondary {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: white !important;
            font-weight: 600;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .org-header .btn-outline-secondary:hover {
            background: rgba(255, 255, 255, 0.25) !important;
            border-color: white !important;
            transform: translateX(-4px);
        }

        .org-body {
            padding: 2.5rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: #2d3748;
            margin-bottom: 0.75rem;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            background: #f8fafc;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control:hover,
        .form-select:hover {
            border-color: #cbd5e1;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-control[readonly] {
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            color: #64748b;
            border: 2px solid #e2e8f0;
            cursor: not-allowed;
        }

        .form-group {
            margin-bottom: 1.75rem;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 0.75rem !important;
            line-height: 1.2;
            min-height: 20px;
        }

        .form-group .input-group {
            order: 2;
        }

        .form-group .verify-result-row {
            order: 3;
        }

        .form-group .invalid-feedback {
            order: 4;
        }

        .row > [class*="col-"] {
            display: flex;
            flex-direction: column;
        }

        .row > [class*="col-"] .form-group {
            height: 100%;
        }

        .input-group {
            gap: 0.5rem;
        }

        .input-group .form-control {
            flex: 1;
        }

        .input-group .btn {
            font-weight: 600;
            padding: 0.875rem 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .input-group .btn-outline-primary {
            color: #667eea;
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }

        .input-group .btn-outline-primary:hover {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .input-group .btn-outline-success {
            color: #11998e;
            border-color: #11998e;
            background: rgba(17, 153, 142, 0.05);
        }

        .input-group .btn-outline-success:hover {
            background: var(--success-gradient);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
        }

        /* Verification badges */
        .verify-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 25px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            backdrop-filter: blur(10px);
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .verify-badge.belum {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            color: #475569;
            border: 1px solid #94a3b8;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .verify-badge.terdaftar {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #15803d;
            border: 1px solid #86efac;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
        }

        .verify-badge.tidak {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 1px solid #fca5a5;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
        }

        .verify-result-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            min-height: 32px;
        }

        /* Section Divider */
        #pppoe-show {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(240, 147, 251, 0.05) 100%);
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        #pppoe-show::before {
            content: "PPPOE Configuration";
            display: block;
            font-weight: 700;
            color: #667eea;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .border-bottom {
            border-bottom: 2px solid #e2e8f0 !important;
        }

        .mt-4 {
            margin-top: 2rem !important;
        }

        .pt-4 {
            padding-top: 2rem !important;
        }

        #map {
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            border: 2px solid #e2e8f0;
        }

        #map-container {
            padding: 1rem;
            background: linear-gradient(135deg, #f0f4f8 0%, #f8fafc 100%);
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        /* Submit Button */
        .btn-primary {
            background: var(--primary-gradient) !important;
            border: none !important;
            font-weight: 700;
            padding: 0.875rem 2rem !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4) !important;
            transform: translateY(-2px);
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-outline-secondary {
            font-weight: 700;
            border: 2px solid #cbd5e1;
            color: #64748b;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
            transform: translateY(-2px);
        }

        /* Spinner styling */
        .spinner-border {
            color: white;
            width: 1rem;
            height: 1rem;
        }

        .spinner-border-sm {
            width: 0.875rem;
            height: 0.875rem;
            border-width: 0.2em;
        }

        /* Row spacing */
        .row {
            row-gap: 1.5rem;
        }

        .col-lg-6,
        .col-lg-12,
        .col-md-12,
        .col-sm-12 {
            transition: transform 0.3s ease;
        }

        .form-group:hover {
            transform: translateY(-2px);
        }

        /* Invalid feedback */
        .invalid-feedback {
            display: block !important;
            color: #dc2626 !important;
            font-size: 0.8rem !important;
            margin-top: 0.5rem !important;
            font-weight: 500 !important;
        }

        .is-invalid {
            border-color: #fca5a5 !important;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%) !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
        }

        /* Clearfix */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .org-header {
                padding: 1.5rem !important;
            }

            .org-body {
                padding: 1.5rem;
            }

            .org-header .org-card-title {
                font-size: 1.5rem;
            }

            .btn-primary, .btn-outline-secondary {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .float-end {
                float: none !important;
                margin-bottom: 0.5rem;
            }

            .float-start {
                float: none !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="org-card">
        <div class="org-header pb-0 mb-0 d-flex justify-content-between align-items-center">
            <div>
                <div style="opacity: 0.9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">
                    📝 Edit Profil Customer
                </div>
                <h2 class="org-card-title">{{ $customer->name }}</h2>
            </div>
            <a href="{{ route('customer.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-2">
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
                
                <!-- Section: Informasi Dasar -->
                <div style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%); border-left: 4px solid #667eea; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem;">
                    <div style="font-weight: 700; color: #667eea; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><path d="M12 1v6m0 6v6"/><path d="M4.22 4.22l4.24 4.24m6.08 0l4.24-4.24"/><path d="M1 12h6m6 0h6"/><path d="M4.22 19.78l4.24-4.24m6.08 0l4.24 4.24"/></svg>
                        Informasi Dasar
                    </div>
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
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Nama lengkap pelanggan">
                                @error('name')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Informasi Kontak -->
                <div style="background: linear-gradient(135deg, rgba(240, 147, 251, 0.05) 0%, rgba(245, 87, 108, 0.05) 100%); border-left: 4px solid #f093fb; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem;">
                    <div style="font-weight: 700; color: #f093fb; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        Informasi Kontak
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="email" class="mb-2">Email Pelanggan</label>
                                <div class="input-group">
                                    <input value="{{ $customer->email }}" type="text" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror" placeholder="email@example.com">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="btn-check-email">
                                        <span id="email-check-text">Cek</span>
                                        <span id="email-check-spinner" class="spinner-border spinner-border-sm d-none ms-1" role="status"></span>
                                    </button>
                                </div>
                                <div class="verify-result-row">
                                    @php
                                        $emailVerifyBadge = match ($customer->email_verify_at) {
                                            'register' => ['terdaftar', 'Sudah Terverifikasi'],
                                            'not_register' => ['tidak', 'Tidak Terdaftar'],
                                            default => ['belum', 'Belum Dicek'],
                                        };
                                    @endphp
                                    <span id="email-status-badge" class="verify-badge {{ $emailVerifyBadge[0] }}">
                                        @if($customer->email_verify_at === 'register')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                            {{ $emailVerifyBadge[1] }}
                                        @elseif($customer->email_verify_at === 'not_register')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            {{ $emailVerifyBadge[1] }}
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                            {{ $emailVerifyBadge[1] }}
                                        @endif
                                    </span>
                                </div>
                                @error('email')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="telp" class="mb-2">No Telephone / WhatsApp</label>
                                <div class="input-group">
                                    <input value="{{ $customer->telp }}" type="text" name="telp" id="telp"
                                        class="form-control @error('telp') is-invalid @enderror" placeholder="62812xxxx">
                                    <button type="button" class="btn btn-outline-success btn-sm" id="btn-check-wa">
                                        <span id="wa-check-text">Cek WA</span>
                                        <span id="wa-check-spinner" class="spinner-border spinner-border-sm d-none ms-1" role="status"></span>
                                    </button>
                                </div>
                                <div class="verify-result-row">
                                    <span id="wa-status-badge" class="verify-badge {{ $customer->wa_verifiy_at ? 'terdaftar' : 'belum' }}">
                                        @if($customer->wa_verifiy_at)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                            Sudah Terverifikasi
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                            Belum Dicek
                                        @endif
                                    </span>
                                </div>
                                @error('telp')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Konfigurasi PPPOE -->
                <div id="pppoe-show" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(240, 147, 251, 0.05) 100%); border-left: 4px solid #667eea; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; display: none;">
                    <div style="font-weight: 700; color: #667eea; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 2.2"/></svg>
                        Konfigurasi PPPOE
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="name_wifi" class="mb-2">Nama Wifi (SSID)</label>
                                <input type="text" name="name_wifi" value="{{ $customer->name_wifi }}" id="name_wifi"
                                    class="form-control @error('name_wifi') is-invalid @enderror" placeholder="Nama SSID">
                                @error('name_wifi')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="password_wifi" class="mb-2">Password Wifi</label>
                                <input type="text" name="password_wifi" value="{{ $customer->password_wifi }}"
                                    id="password_wifi" class="form-control @error('password_wifi') is-invalid @enderror" placeholder="Password SSID">
                                @error('password_wifi')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="paket_id" class="mb-2">Tipe Paket</label>
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
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="price_id" class="mb-2">Tipe Pembayaran</label>
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
                </div>

                <!-- Section: Infrastruktur Jaringan -->
                <div style="background: linear-gradient(135deg, rgba(17, 153, 142, 0.05) 0%, rgba(56, 239, 125, 0.05) 100%); border-left: 4px solid #11998e; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem;">
                    <div style="font-weight: 700; color: #11998e; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v6m0 6v6"/><circle cx="12" cy="3" r="1"/><circle cx="12" cy="9" r="1"/><circle cx="12" cy="15" r="1"/><circle cx="12" cy="21" r="1"/><path d="M6 7h12M6 13h12M6 19h12"/></svg>
                        Infrastruktur Jaringan
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="mac_address" class="mb-2">Mac Address</label>
                                <input value="{{ $customer->mac_address }}" type="text" name="mac_address"
                                    id="mac_address" class="form-control @error('mac_address') is-invalid @enderror" placeholder="00:00:00:00:00:00">
                                @error('mac_address')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
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
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="" class="mb-2">Mix Radius</label>
                                <select name="mic_radius_id" id="mic_radius_id"
                                    class="form-control @error('mic_radius_id') is-invalid @enderror">
                                    <option value="">Pilih</option>
                                    @foreach ($micRadius as $mc)
                                        <option value="{{ $mc->id }}"
                                            {{ $customer->mic_radius_id == $mc->id ? 'selected' : '' }}>{{ $mc->code }} - {{ $mc->name }}</option>
                                    @endforeach
                                </select>
                                @error('mic_radius_id')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="vlans_id" class="mb-2">VLAN</label>
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
                                            {{ $odcs->code }} | {{ $odcs->hometown->name }} | {{ $odcs->rt->name }} | {{ $odcs->rw->name }} | {{ $odcs->home_odc }}
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
                                            {{ $odps->code }} | {{ $odps->hometown->name }} | {{ $odps->rt->name }} | {{ $odps->rw->name }} | {{ $odps->home_odc }}
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
                    </div>
                </div>

                <!-- Section: Lokasi Geografis -->
                <div style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.05) 0%, rgba(0, 242, 254, 0.05) 100%); border-left: 4px solid #4facfe; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem;">
                    <div style="font-weight: 700; color: #4facfe; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Lokasi Geografis
                    </div>
                    <div class="row">
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
                    </div>
                </div>

                <!-- Section: Peta Lokasi -->
                <div style="background: linear-gradient(135deg, rgba(255, 164, 0, 0.05) 0%, rgba(255, 107, 107, 0.05) 100%); border-left: 4px solid #ffa400; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem;">
                    <div style="font-weight: 700; color: #ffa400; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M7 7h10v10H7z"/></svg>
                        Peta Lokasi Pelanggan
                    </div>
                    <div id="map-container">
                        <div id="map" style="height: 400px;"></div>
                        <input type="hidden" name="latitude" id="latitude"
                            class="form-control @error('latitude') is-invalid @enderror">
                        <input type="hidden" name="longitude" id="longitude"
                            class="form-control @error('longitude') is-invalid @enderror">
                    </div>
                </div>



                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #e2e8f0; display: flex; gap: 1rem; justify-content: flex-end; flex-wrap: wrap;">
                    <button type="reset" class="btn btn-outline-secondary" style="min-width: 150px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 0.5rem;"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v6h-6"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M3 21v-6h6"/></svg>
                        Reset Isi Form
                    </button>
                    <button type="submit" id="btn" class="btn btn-primary" style="min-width: 180px; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        <span id="btn-text">Update Customer</span>
                        <span id="btn-loading" class="spinner-border spinner-border-sm d-none ms-1" role="status"></span>
                    </button>
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
        // ── Verification state ────────────────────────────────────────────────
        // Pre-mark as checked if already verified in DB; reset if user edits
        const _origEmail = document.getElementById('email').value;
        const _origTelp  = document.getElementById('telp').value;
        window.__emailChecked = @json($customer->email_verify_at === null ? null : $customer->email_verify_at === 'register');
        window.__waChecked    = {{ $customer->wa_verifiy_at   ? 'true' : 'false' }};

        // ── Helpers ───────────────────────────────────────────────────────────
        function showToast(message, type) {
            const bg = type === 'success' ? '#15803d' : (type === 'warning' ? '#b45309' : '#dc2626');
            const container = document.getElementById('toast-container') || (() => {
                const el = document.createElement('div');
                el.id = 'toast-container';
                el.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;';
                document.body.appendChild(el);
                return el;
            })();
            const toast = document.createElement('div');
            toast.style.cssText = `background:${bg};color:#fff;padding:12px 18px;border-radius:10px;font-size:0.875rem;font-weight:500;box-shadow:0 4px 15px rgba(0,0,0,.2);opacity:0;transform:translateX(30px);transition:all .3s ease;max-width:320px;`;
            toast.textContent = message;
            container.appendChild(toast);
            requestAnimationFrame(() => { toast.style.opacity = '1'; toast.style.transform = 'translateX(0)'; });
            setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(30px)'; setTimeout(() => toast.remove(), 350); }, 4000);
        }

        function setEmailBadge(status, label) {
            const badge = document.getElementById('email-status-badge');
            const icons = {
                belum:     '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
                terdaftar: '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>',
                tidak:     '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
            };
            badge.className = 'verify-badge ' + status;
            badge.innerHTML = (icons[status] || icons.belum) + ' ' + label;
        }

        function setWaBadge(status, label) {
            const badge = document.getElementById('wa-status-badge');
            const icons = {
                belum:     '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
                terdaftar: '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>',
                tidak:     '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
            };
            badge.className = 'verify-badge ' + status;
            badge.innerHTML = (icons[status] || icons.belum) + ' ' + label;
        }

        // ── Reset badge when admin changes the value ──────────────────────────
        document.getElementById('email').addEventListener('input', function () {
            if (this.value.trim() !== _origEmail) {
                window.__emailChecked = null;
                setEmailBadge('belum', 'Belum Dicek (Nilai Berubah)');
            } else {
                window.__emailChecked = @json($customer->email_verify_at === null ? null : $customer->email_verify_at === 'register');
                setEmailBadge('{{ $emailVerifyBadge[0] }}', '{{ $emailVerifyBadge[1] }}');
            }
        });
        document.getElementById('telp').addEventListener('input', function () {
            if (this.value.trim() !== _origTelp) {
                window.__waChecked = false;
                setWaBadge('belum', 'Belum Dicek (Nilai Berubah)');
            } else {
                window.__waChecked = {{ $customer->wa_verifiy_at ? 'true' : 'false' }};
                setWaBadge('{{ $customer->wa_verifiy_at ? "terdaftar" : "belum" }}', '{{ $customer->wa_verifiy_at ? "Sudah Terverifikasi" : "Belum Dicek" }}');
            }
        });

        // ── Check Email ───────────────────────────────────────────────────────
        document.getElementById('btn-check-email').addEventListener('click', function () {
            const email = document.getElementById('email').value.trim();
            if (!email) { showToast('Masukkan alamat email terlebih dahulu.', 'error'); return; }
            const btn = this, btnText = document.getElementById('email-check-text'), spinner = document.getElementById('email-check-spinner');
            btn.disabled = true; btnText.textContent = 'Mengecek...'; spinner.classList.remove('d-none');
            setEmailBadge('belum', 'Sedang Dicek...');
            $.ajax({
                url: '{{ route("customer.verify-email-on-demand") }}',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', email: email },
                success: function (res) {
                    if (res.status === 'register') {
                        window.__emailChecked = true;
                        setEmailBadge('terdaftar', 'Email Terdaftar');
                        showToast('✅ Email valid dan terdaftar!', 'success');
                    } else {
                        window.__emailChecked = false;
                        setEmailBadge('tidak', 'Email Tidak Terdaftar');
                        showToast('❌ Email tidak valid / tidak terdaftar.', 'error');
                    }
                },
                error: function () {
                    window.__emailChecked = null;
                    setEmailBadge('belum', 'Gagal Cek');
                    showToast('Gagal menghubungi server verifikasi email.', 'error');
                },
                complete: function () { btn.disabled = false; btnText.textContent = 'Cek Email'; spinner.classList.add('d-none'); }
            });
        });

        // ── Check WA ──────────────────────────────────────────────────────────
        document.getElementById('btn-check-wa').addEventListener('click', function () {
            const telp = document.getElementById('telp').value.trim();
            if (!telp) { showToast('Masukkan nomor telepon terlebih dahulu.', 'error'); return; }
            const btn = this, btnText = document.getElementById('wa-check-text'), spinner = document.getElementById('wa-check-spinner');
            btn.disabled = true; btnText.textContent = 'Mengecek...'; spinner.classList.remove('d-none');
            setWaBadge('belum', 'Sedang Dicek...');
            $.ajax({
                url: '{{ route("customer.verify-wa-on-demand") }}',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', telp: telp },
                success: function (res) {
                    if (res.status === 'registered') {
                        window.__waChecked = true;
                        setWaBadge('terdaftar', 'WA Terdaftar');
                        showToast('✅ Nomor WhatsApp terdaftar!', 'success');
                    } else {
                        window.__waChecked = false;
                        setWaBadge('tidak', 'WA Tidak Terdaftar');
                        showToast('❌ Nomor tidak terdaftar di WhatsApp.', 'error');
                    }
                },
                error: function () {
                    window.__waChecked = false;
                    setWaBadge('belum', 'Gagal Cek');
                    showToast('Gagal menghubungi server verifikasi WA.', 'error');
                },
                complete: function () { btn.disabled = false; btnText.textContent = 'Cek WA'; spinner.classList.add('d-none'); }
            });
        });

        // ── Form submit – gate on verification ────────────────────────────────
        document.querySelector('form').addEventListener('submit', function (e) {
            const email = document.getElementById('email').value.trim();
            const telp  = document.getElementById('telp').value.trim();

            if (email && window.__emailChecked === null) {
                e.preventDefault();
                showToast('⚠️ Harap klik "Cek Email" untuk memverifikasi email yang baru diubah.', 'warning');
                document.getElementById('btn-check-email').focus();
                return;
            }
            if (telp && !window.__waChecked) {
                e.preventDefault();
                showToast('⚠️ Harap klik "Cek WA" untuk memverifikasi nomor yang baru diubah.', 'warning');
                document.getElementById('btn-check-wa').focus();
                return;
            }

            const btn = document.getElementById('btn');
            const text = document.getElementById('btn-text');
            const loading = document.getElementById('btn-loading');
            btn.disabled = true;
            text.textContent = 'Menyimpan...';
            loading.classList.remove('d-none');
        });
    </script>
@endpush

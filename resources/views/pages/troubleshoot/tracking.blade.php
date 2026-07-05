@extends('layouts.app')

@section('title', 'Tracking - Troubleshoot #' . $troubleshoot->id)

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<style>
    .premium-card {
        border-radius: 14px; background: #fff; border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,.05), 0 2px 4px -2px rgba(0,0,0,.05);
    }
    .premium-card-header {
        background: #f8fafc; border-bottom: 1px solid #e2e8f0;
        border-radius: 14px 14px 0 0; padding: .75rem 1rem;
    }
    .icon-wrapper {
        width: 28px; height: 28px; border-radius: 8px;
        background: rgba(79,70,229,.1); color: #4f46e5;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    #tracking-map {
        height: 500px; border-radius: 0 0 14px 14px; z-index: 1;
    }
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
    }
    .stat-card {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;
        padding: 1rem 1.25rem; transition: all .2s ease;
    }
    .stat-card .stat-icon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: .5rem;
    }
    .stat-card .stat-label {
        font-size: .75rem; text-transform: uppercase; letter-spacing: .5px;
        color: #64748b; margin-bottom: .25rem;
    }
    .stat-card .stat-value {
        font-size: 1.05rem; font-weight: 700; color: #1e293b;
    }
    .tech-marker {
        background: none !important;
        border: none !important;
    }
    .tech-marker-inner {
        width: 44px; height: 44px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(59,130,246,0.5), 0 2px 8px rgba(0,0,0,0.12);
        display: flex; align-items: center; justify-content: center;
        border: 3px solid #fff;
        position: relative;
        transition: transform 0.25s ease;
    }
    .tech-marker-inner::after {
        content: '';
        position: absolute; bottom: -6px; left: 50%;
        transform: translateX(-50%);
        width: 0; height: 0;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-top: 8px solid #1d4ed8;
    }
    .customer-info-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: .6rem .75rem; border-bottom: 1px solid #f1f5f9; font-size: .88rem;
    }
    .customer-info-row:last-child { border-bottom: none; }
    .customer-info-row .label { color: #64748b; font-weight: 500; }
    .customer-info-row .value { color: #1e293b; font-weight: 600; text-align: right; }

    .stepper-icon {
        transition: all .35s cubic-bezier(.4,0,.2,1);
        will-change: transform, box-shadow, background;
        position: relative;
    }
    .stepper-item:hover .stepper-icon:not(.completed-icon) {
        transform: scale(1.08);
    }
    .stepper-icon.active-icon::after {
        content: '';
        position: absolute; inset: -6px;
        border-radius: 50%;
        border: 3px solid #3b82f6;
        animation: pulse-ring 1.8s cubic-bezier(.4,0,.6,1) infinite;
    }
    @keyframes pulse-ring {
        0% { opacity: 1; transform: scale(1); }
        100% { opacity: 0; transform: scale(1.35); }
    }
    .step-card-modern {
        animation: card-fade-in .4s cubic-bezier(.4,0,.2,1);
    }
    @keyframes card-fade-in {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .upload-zone-modern {
        transition: all .3s ease;
        cursor: pointer;
    }
    .upload-zone-modern:hover {
        border-color: #3b82f6;
        background: #f0f7ff;
    }
    .upload-zone-modern.has-photo {
        border-style: solid;
        border-color: #10b981;
        background: #f0fdf4;
        padding: .5rem;
    }
    .step-time {
        font-size: .62rem;
        color: #94a3b8;
        margin-top: 2px;
        transition: color .3s;
    }
    .completed-photo-thumb {
        width: 64px; height: 64px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #d1fae5;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="org-container">
    <div class="org-card">
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83a2 2 0 0 1 -2.83 0l-.06 -.06a1.65 1.65 0 0 0 -1.82 -.33a1.65 1.65 0 0 0 -1 1.51v.11a2 2 0 0 1 -2 2a2 2 0 0 1 -2 -2v-.06a1.65 1.65 0 0 0 -1.02 -1.51a1.65 1.65 0 0 0 -1.82 .33l-.06 .06a2 2 0 0 1 -2.83 0a2 2 0 0 1 0 -2.83l.06 -.06a1.65 1.65 0 0 0 .33 -1.82a1.65 1.65 0 0 0 -1.51 -1H3a2 2 0 0 1 -2 -2a2 2 0 0 1 2 -2h.06a1.65 1.65 0 0 0 1.51 -1.02a1.65 1.65 0 0 0 -.33 -1.82l-.06 -.06a2 2 0 0 1 0 -2.83a2 2 0 0 1 2.83 0l.06 .06a1.65 1.65 0 0 0 1.82 .33h.09a1.65 1.65 0 0 0 1.51 -1.02v-.12a2 2 0 0 1 2 -2a2 2 0 0 1 2 2v.06a1.65 1.65 0 0 0 1.02 1.51a1.65 1.65 0 0 0 1.82 -.33l.06 -.06a2 2 0 0 1 2.83 0a2 2 0 0 1 0 2.83l-.06 .06a1.65 1.65 0 0 0 -.33 1.82v.09a1.65 1.65 0 0 0 1.51 1.51h.11a2 2 0 0 1 2 2a2 2 0 0 1 -2 2h-.06a1.65 1.65 0 0 0 -1.51 1.02" />
                    </svg>
                </div>
                <div>
                    <h5 class="org-title">Live Tracking</h5>
                    <div class="org-subtitle">{{ $troubleshoot->customer->name ?? 'N/A' }} &middot; Teknisi: {{ $troubleshoot->technician->name ?? 'N/A' }}</div>
                </div>
            </div>
            <div>
                <a href="{{ route('troubleshoot.show', $troubleshoot->id) }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="px-3 pt-3">
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(245,158,11,.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div class="stat-label">Status</div>
                    <div class="stat-value">
                        @php
                            $badgeClass = match($troubleshoot->status) {
                                'open' => 'badge bg-warning',
                                'menuju_lokasi' => 'badge bg-info',
                                'tiba_lokasi' => 'badge bg-primary',
                                'perbaikan' => 'badge bg-indigo',
                                'done' => 'badge bg-success',
                                'cancelled' => 'badge bg-danger',
                                default => 'badge bg-secondary',
                            };
                        @endphp
                        <span class="{{ $badgeClass }}" style="font-size:.75rem;">{{ str_replace('_', ' ', ucfirst($troubleshoot->status)) }}</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(16,185,129,.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1 -4 10 15.3 15.3 0 0 1 -4 -10 15.3 15.3 0 0 1 4 -10z"/></svg>
                    </div>
                    <div class="stat-label">Jarak</div>
                    <div class="stat-value" id="distanceText">Menghitung...</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(59,130,246,.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/><path d="M12 2v4"/><path d="M12 18v4"/><path d="M2 12h4"/><path d="M18 12h4"/></svg>
                    </div>
                    <div class="stat-label">Posisi Teknisi</div>
                    <div class="stat-value" id="techPosition" style="font-size:.82rem;word-break:break-all;">Menunggu sinyal GPS...</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(168,85,247,.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div class="stat-label">Estimasi Sampai</div>
                    <div class="stat-value" id="etaText">Menghitung...</div>
                </div>
            </div>
        </div>

        {{-- Map + Customer Info --}}
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-lg-8">
                    <div class="premium-card">
                        <div class="premium-card-header d-flex align-items-center gap-2">
                            <div class="icon-wrapper" style="background:rgba(239,68,68,.1);color:#ef4444;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/><path d="M12 2a8 8 0 0 0 -8 8c0 5.25 8 12 8 12s8 -6.75 8 -12a8 8 0 0 0 -8 -8z"/></svg>
                            </div>
                            <span class="fw-bold text-dark" style="font-size:.88rem;">Peta Tracking</span>
                        </div>
                        <div id="tracking-map"></div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 d-flex flex-column gap-3">
                    <div class="premium-card">
                        <div class="premium-card-header d-flex align-items-center gap-2">
                            <div class="icon-wrapper" style="background:rgba(14,165,233,.1);color:#0ea5e9;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/></svg>
                            </div>
                            <span class="fw-bold text-dark" style="font-size:.88rem;">Info Pelanggan</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="customer-info-row">
                                <span class="label">Nama</span>
                                <span class="value">{{ $troubleshoot->customer->name ?? '-' }}</span>
                            </div>
                            <div class="customer-info-row">
                                <span class="label">MAC Address</span>
                                <span class="value">{{ $troubleshoot->customer->mac_address ?? '-' }}</span>
                            </div>
                            <div class="customer-info-row">
                                <span class="label">Alamat</span>
                                <span class="value" id="customerAddress" style="max-width:220px;font-weight:400;font-size:.78rem;text-align:right;">Memuat alamat...</span>
                            </div>
                            <div class="customer-info-row">
                                <span class="label">Deskripsi</span>
                                <span class="value" style="max-width:180px;font-weight:400;font-size:.82rem;">{{ $troubleshoot->description ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="premium-card">
                        <div class="premium-card-header d-flex align-items-center gap-2">
                            <div class="icon-wrapper" style="background:rgba(245,158,11,.1);color:#f59e0b;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/></svg>
                            </div>
                            <span class="fw-bold text-dark" style="font-size:.88rem;">Info Teknisi</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="customer-info-row">
                                <span class="label">Nama</span>
                                <span class="value">{{ $troubleshoot->technician->name ?? '-' }}</span>
                            </div>
                            <div class="customer-info-row">
                                <span class="label">Email</span>
                                <span class="value">{{ $troubleshoot->technician->email ?? '-' }}</span>
                            </div>
                            <div class="customer-info-row">
                                <span class="label">Telp</span>
                                <span class="value">{{ $troubleshoot->technician->telp ?? '-' }}</span>
                            </div>
                            <div class="customer-info-row">
                                <span class="label">Posisi Terkini</span>
                                <span class="value" id="techInfoPosition" style="font-size:.78rem;">Menunggu sinyal GPS...</span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top p-3">
                            @if(auth()->user()->can('kelola troubleshoot') || auth()->id() === $troubleshoot->technician_id)
                            <div style="font-size:.78rem;color:#64748b;text-align:center;">
                                Selesaikan ticket melalui 4 step proses di bawah
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wizard Card --}}
        @if(auth()->user()->can('kelola troubleshoot') || auth()->id() === $troubleshoot->technician_id)
        @php
            $stepNames = ['Menuju Lokasi', 'Tiba di Lokasi', 'Proses Perbaikan', 'Selesai'];
            $stepDescriptions = [
                1 => 'Konfirmasi keberangkatan Anda menuju lokasi pelanggan. Pastikan foto bukti perjalanan diambil sebagai verifikasi.',
                2 => 'Konfirmasi kedatangan Anda di lokasi pelanggan. Ambil foto lokasi/Customer sebagai bukti verifikasi.',
                3 => 'Lakukan diagnosa dan perbaikan sesuai prosedur. Catat tindakan yang dilakukan dan unggah foto hasil pekerjaan.',
                4 => 'Pekerjaan telah selesai. Mohon konfirmasi status akhir dan unggah dokumentasi final untuk menutup tiket.',
            ];
            $stepIcons = [
                1 => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="5" cy="17" r="3"/><circle cx="19" cy="17" r="3"/><path d="M5 17v-4h6l3 -6h3l1 3h4.5a2 2 0 0 1 1.5 .75l-1.5 4.25h-5.5l-1 3h-5.5"/><path d="M10 12l-1.5 -3h-4.5"/><path d="M16 7h-3"/></svg>',
                2 => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s-8 -5.5 -8 -11.5a8 8 0 0 1 16 0c0 6 -8 11.5 -8 11.5z"/><circle cx="12" cy="9" r="2.5"/></svg>',
                3 => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77 -3.77a6 6 0 0 1 -7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1 -3 -3l6.91 -6.91a6 6 0 0 1 7.94 -7.94l-3.76 3.76z"/></svg>',
                4 => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="16 10 11 15 8 12"/></svg>',
            ];
            $completedCount = $troubleshoot->progress->where('status', 'completed')->count();
            $completedPercent = min(($completedCount / 4) * 100, 100);
        @endphp
        <div class="px-3 pb-3">
            <div class="premium-card overflow-hidden">
                <div class="premium-card-header d-flex align-items-center gap-2 border-0" style="background:linear-gradient(135deg,#667eea,#764ba2);">
                    <div class="icon-wrapper" style="background:rgba(255,255,255,.2);color:#fff;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <span class="fw-bold text-white" style="font-size:.88rem;">Proses Troubleshoot</span>
                </div>
                <div class="card-body p-4" id="stepWizard">
                    {{-- Horizontal Stepper with Icons --}}
                    <div style="position:relative;padding:0 0 2rem 0;">
                        {{-- Background connector line --}}
                        <div style="position:absolute;top:28px;left:3%;right:3%;height:4px;background:#e2e8f0;border-radius:2px;z-index:0;"></div>
                        {{-- Dynamic gradient fill --}}
                        <div id="connector-gradient" style="position:absolute;top:28px;left:3%;height:4px;border-radius:2px;z-index:1;background:linear-gradient(90deg,#10b981 {{ $completedPercent }}%,#e2e8f0 {{ $completedPercent }}%);width:94%;transition:all .6s ease;"></div>

                        <div style="display:flex;align-items:flex-start;justify-content:space-between;position:relative;">
                            @foreach($stepNames as $i => $name)
                            @php
                                $s = $i + 1;
                                $p = $troubleshoot->progress->firstWhere('step', $s);
                                $st = $p ? $p->status : 'pending';
                                $time = $p && $p->created_at ? $p->created_at->format('H:i') : null;
                            @endphp
                            <div class="stepper-item" onclick="toggleStep({{ $s }})" style="display:flex;flex-direction:column;align-items:center;gap:.5rem;position:relative;z-index:2;flex:1;cursor:pointer;">
                                <div class="stepper-icon rounded-circle d-flex align-items-center justify-content-center"
                                     id="icon-{{ $s }}"
                                     style="width:56px;height:56px;background:#f1f5f9;border:3px solid #e2e8f0;color:#94a3b8;">
                                    <span id="icon-content-{{ $s }}">{!! $stepIcons[$s] !!}</span>
                                </div>
                                <div style="text-align:center;">
                                    <div class="step-name" id="slabel-{{ $s }}" style="font-size:.72rem;font-weight:600;color:#64748b;transition:color .3s;white-space:nowrap;">{{ $name }}</div>
                                    <div class="step-time" id="stime-{{ $s }}">{{ $st === 'completed' && $time ? 'Selesai '.$time : '' }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Step Cards --}}
                    @foreach($stepNames as $i => $name)
                    @php
                        $s = $i + 1;
                        $p = $troubleshoot->progress->firstWhere('step', $s);
                        $st = $p ? $p->status : 'pending';
                        $photo = $p && $p->photo ? asset($p->photo) : null;
                        $time = $p && $p->created_at ? $p->created_at->format('H:i') : null;
                    @endphp
                    <div class="step-card-modern" id="card-{{ $s }}" data-status="{{ $st }}"
                         style="display:none;background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:1.5rem;margin-bottom:1rem;box-shadow:0 4px 20px rgba(0,0,0,.06);">

                        {{-- Card Header --}}
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:48px;height:48px;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;">
                                {!! $stepIcons[$s] !!}
                            </div>
                            <div>
                                <div style="font-size:1.05rem;font-weight:700;color:#1e293b;">{{ $name }}</div>
                                <div style="font-size:.75rem;color:{{ $st === 'completed' ? '#10b981' : '#94a3b8' }};display:flex;align-items:center;gap:4px;">
                                    @if($st === 'completed')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    Selesai {{ $time }}
                                    @elseif($s === 1)
                                    Belum berangkat
                                    @elseif($s === 2)
                                    Belum tiba
                                    @elseif($s === 3)
                                    Belum dikerjakan
                                    @else
                                    Belum selesai
                                    @endif
                                </div>
                            </div>
                            @if($st === 'completed')
                            <div class="ms-auto">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#10b981;color:#fff;box-shadow:0 2px 10px rgba(16,185,129,.3);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Description / Microcopy --}}
                        <div style="font-size:.85rem;color:#475569;line-height:1.6;margin-bottom:1.25rem;padding:.75rem 1rem;background:#f8fafc;border-radius:10px;border-left:3px solid #667eea;">
                            {{ $stepDescriptions[$s] }}
                        </div>

                        {{-- Photo Upload Area --}}
                        <div class="upload-zone-modern {{ $st === 'completed' ? 'has-photo' : '' }}" id="upload-zone-{{ $s }}"
                             style="border:2px dashed {{ $st === 'completed' ? '#10b981' : '#cbd5e1' }};border-radius:12px;padding:{{ $st === 'completed' ? '.5rem' : '1.5rem' }};text-align:center;background:{{ $st === 'completed' ? '#f0fdf4' : '#fafbfc' }};position:relative;">

                            <input type="file" accept="image/*" capture="environment"
                                   class="camera-input-modern d-none" id="camera-{{ $s }}"
                                   onchange="handlePhoto({{ $s }}, this)">

                            @if($st === 'completed' && $photo)
                            <div class="d-flex align-items-center gap-3 justify-content-center">
                                <img src="{{ $photo }}" class="completed-photo-thumb" alt="Foto step {{ $s }}">
                                <div style="text-align:left;font-size:.82rem;color:#475569;">
                                    <div style="font-weight:600;color:#10b981;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="20 6 9 17 4 12"/></svg>
                                        Foto terverifikasi
                                    </div>
                                    <div style="color:#94a3b8;">{{ $time ? 'Pukul '.$time : '' }}</div>
                                </div>
                            </div>
                            @else
                            <div id="upload-default-{{ $s }}">
                                <div style="color:#94a3b8;margin-bottom:.75rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                </div>
                                <div style="font-size:.85rem;font-weight:600;color:#475569;margin-bottom:.35rem;">Ambil Foto Bukti</div>
                                <div style="font-size:.72rem;color:#94a3b8;margin-bottom:1rem;">Ketuk untuk mengambil foto atau pilih dari galeri</div>
                                <button type="button" class="btn btn-primary px-4 py-2" id="btn-photo-{{ $s }}"
                                        onclick="document.getElementById('camera-{{ $s }}').click()"
                                        style="border-radius:10px;font-size:.85rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                    Ambil Foto
                                </button>
                            </div>

                            <div id="upload-preview-{{ $s }}" class="d-none" style="position:relative;">
                                <img id="preview-{{ $s }}" class="img-fluid rounded" style="max-height:260px;object-fit:contain;border-radius:10px;border:1px solid #e2e8f0;">
                                <button type="button" class="btn btn-sm btn-outline-danger position-absolute"
                                        id="btn-retake-{{ $s }}"
                                        style="top:8px;right:8px;border-radius:50%;width:32px;height:32px;padding:0;display:flex;align-items:center;justify-content:center;"
                                        onclick="retakePhoto({{ $s }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                            @endif
                        </div>

                        {{-- Action Bar --}}
                        @if($st !== 'completed')
                        <div class="d-flex align-items-center justify-content-end gap-2 mt-3">
                            <div id="loading-{{ $s }}" class="d-none align-items-center gap-2">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                <span style="font-size:.78rem;color:#64748b;">Mengirim...</span>
                            </div>
                            <button type="button" class="btn btn-success px-4 py-2 d-none" id="btn-confirm-{{ $s }}"
                                    onclick="confirmStep({{ $s }})"
                                    style="border-radius:10px;font-size:.85rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="20 6 9 17 4 12"/></svg>
                                Konfirmasi & Kirim
                            </button>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const customerLat = {{ $troubleshoot->customer->latitude ?? 'null' }};
        const customerLng = {{ $troubleshoot->customer->longitude ?? 'null' }};
        const troubleshootId = {{ $troubleshoot->id }};

        if (!customerLat || !customerLng) {
            document.getElementById('tracking-map').innerHTML = '<div class="alert alert-warning m-3">Pelanggan belum memiliki data lokasi (latitude/longitude).</div>';
            return;
        }

        fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + customerLat + '&lon=' + customerLng + '&accept-language=id')
            .then(function(r) { return r.json(); })
            .then(function(d) {
                var addr = d.display_name || customerLat + ', ' + customerLng;
                document.getElementById('customerAddress').textContent = addr;
            })
            .catch(function() {
                document.getElementById('customerAddress').textContent = customerLat + ', ' + customerLng;
            });

        const map = L.map('tracking-map').setView([customerLat, customerLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const customerMarker = L.marker([customerLat, customerLng], {
            icon: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            })
        }).addTo(map).bindPopup('<b>Pelanggan:</b> {{ $troubleshoot->customer->name ?? 'N/A' }}');

        let techMarker = null;
        let routingControl = null;

        function updateRoute(techLat, techLng) {
            if (routingControl) {
                routingControl.setWaypoints([
                    L.latLng(techLat, techLng),
                    L.latLng(customerLat, customerLng)
                ]);
                return;
            }

            routingControl = L.Routing.control({
                waypoints: [
                    L.latLng(techLat, techLng),
                    L.latLng(customerLat, customerLng)
                ],
                routeWhileDragging: false,
                addWaypoints: false,
                draggableWaypoints: false,
                showAlternatives: false,
                fitSelectedRoutes: false,
                show: false,
                lineOptions: {
                    styles: [{ color: '#2563eb', weight: 4, opacity: 0.8 }]
                },
                createMarker: function() { return null; },
                router: L.Routing.osrmv1({
                    serviceUrl: 'https://router.project-osrm.org/route/v1'
                })
            }).addTo(map);

            routingControl.on('routesfound', function(e) {
                var routes = e.routes;
                var summary = routes[0].summary;
                var distance = summary.totalDistance;
                var distanceText;
                if (distance >= 1000) {
                    distanceText = (distance / 1000).toFixed(2) + ' km';
                } else {
                    distanceText = Math.round(distance) + ' m';
                }
                document.getElementById('distanceText').textContent = distanceText;

                var speedKmh = 30;
                var hours = distance / 1000 / speedKmh;
                var etaText;
                if (hours >= 1) {
                    etaText = Math.round(hours) + ' jam ' + Math.round((hours % 1) * 60) + ' menit';
                } else {
                    var minutes = hours * 60;
                    if (minutes >= 1) {
                        etaText = Math.round(minutes) + ' menit';
                    } else {
                        etaText = Math.round(minutes * 60) + ' detik';
                    }
                }
                document.getElementById('etaText').textContent = etaText;
            });
        }

        function updateTechAddress(lat, lng) {
            currentTechLat = lat;
            currentTechLng = lng;
            document.getElementById('techPosition').textContent = 'Mendapatkan alamat...';
            fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&accept-language=id')
                .then(function(r) { return r.json(); })
                .then(function(d) {
                    var addr = d.display_name || lat.toFixed(6) + ', ' + lng.toFixed(6);
                    currentTechAddr = addr;
                    document.getElementById('techPosition').textContent = addr;
                    document.getElementById('techInfoPosition').textContent = addr;
                })
                .catch(function() {
                    var fallback = lat.toFixed(6) + ', ' + lng.toFixed(6);
                    currentTechAddr = fallback;
                    document.getElementById('techPosition').textContent = fallback;
                    document.getElementById('techInfoPosition').textContent = fallback;
                });
        }

        let lastSentLat = null;
        let lastSentLng = null;
        let lastFetchLat = null;
        let lastFetchLng = null;
        let locationRetryTimer = null;

        function sendLocationToServer(lat, lng) {
            if (lastSentLat === lat && lastSentLng === lng) return;
            lastSentLat = lat;
            lastSentLng = lng;

            fetch('{{ route("troubleshoot.update-location") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ latitude: lat, longitude: lng })
            }).then(function(res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                if (locationRetryTimer) {
                    clearTimeout(locationRetryTimer);
                    locationRetryTimer = null;
                }
            }).catch(function(err) {
                console.error('Gagal update lokasi:', err);
                lastSentLat = null;
                lastSentLng = null;
                if (!locationRetryTimer) {
                    locationRetryTimer = setTimeout(function() {
                        locationRetryTimer = null;
                        sendLocationToServer(lat, lng);
                    }, 3000);
                }
            });
        }

        function applyTrackingData(tLat, tLng) {
            if (lastFetchLat === tLat && lastFetchLng === tLng) return;
            lastFetchLat = tLat;
            lastFetchLng = tLng;

            if (!techMarker) {
                techMarker = L.marker([tLat, tLng], {
                    icon: L.divIcon({
                        className: 'tech-marker',
                        html: '<div class="tech-marker-inner">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' +
                            '<circle cx="5" cy="17" r="3"/><circle cx="19" cy="17" r="3"/>' +
                            '<path d="M5 17v-4h6l3 -6h3l1 3h4.5a2 2 0 0 1 1.5 .75l-1.5 4.25h-5.5l-1 3h-5.5"/>' +
                            '<path d="M10 12l-1.5 -3h-4.5"/><path d="M16 7h-3"/>' +
                            '</svg></div>',
                        iconSize: [44, 52],
                        iconAnchor: [22, 52],
                        popupAnchor: [1, -44]
                    })
                }).addTo(map).bindPopup('<b>Posisi Teknisi</b>');
            } else {
                techMarker.setLatLng([tLat, tLng]);
            }
            updateRoute(tLat, tLng);
            updateTechAddress(tLat, tLng);
        }

        function onPositionSuccess(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            applyTrackingData(lat, lng);
            map.panTo([lat, lng]);
            sendLocationToServer(lat, lng);
        }

        var gpsDenied = false;

        function startGeolocation() {
            function onError(error) {
                console.warn('Geolocation error:', error.message);
                document.getElementById('techPosition').textContent = 'Gagal: ' + error.message;

                if (error.code === error.PERMISSION_DENIED) {
                    if (!gpsDenied) {
                        gpsDenied = true;
                        Swal.fire({
                            title: 'Akses Lokasi Diblokir',
                            text: 'Izinkan akses lokasi di pengaturan browser Anda, lalu coba lagi.',
                            icon: 'error',
                            confirmButtonText: 'Coba Lagi',
                            confirmButtonColor: '#4f46e5',
                            showCancelButton: true,
                            cancelButtonText: 'Tutup'
                        }).then(function(res) {
                            if (res.isConfirmed) {
                                gpsDenied = false;
                                startGeolocation();
                            }
                        });
                    }
                    return;
                }

                Swal.fire({
                    title: 'Gagal Mendapatkan Lokasi',
                    text: 'Pastikan GPS perangkat Anda aktif. ' + error.message,
                    icon: 'error',
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#4f46e5',
                    showCancelButton: true,
                    cancelButtonText: 'Tutup'
                }).then(function(res) {
                    if (res.isConfirmed) {
                        startGeolocation();
                    }
                });
            }

            navigator.geolocation.getCurrentPosition(onPositionSuccess, onError, {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            });

            navigator.geolocation.watchPosition(onPositionSuccess, function(error) {
                console.warn('WatchPosition error:', error.message);
                document.getElementById('techPosition').textContent = 'GPS terputus: ' + error.message;
            }, {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 5000
            });
        }

        function requestGps() {
            if (!navigator.geolocation) {
                Swal.fire({
                    title: 'GPS Tidak Didukung',
                    text: 'Browser Anda tidak mendukung geolocation.',
                    icon: 'error',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#6b7280'
                });
                document.getElementById('techPosition').textContent = 'Geolocation tidak didukung browser.';
                return;
            }

            Swal.fire({
                title: 'Aktifkan GPS',
                html: 'Aplikasi membutuhkan akses lokasi untuk tracking.<br><br>' +
                      '<span style="font-size:.85rem;color:#64748b;">' +
                      'Izinkan akses lokasi saat diminta browser.</span>',
                icon: 'info',
                confirmButtonText: 'Aktifkan GPS',
                confirmButtonColor: '#4f46e5',
                showCancelButton: false,
                allowOutsideClick: false
            }).then(function() {
                startGeolocation();
            });
        }

        requestGps();

        @php
            $progressJson = $troubleshoot->progress->map(function($p) {
                return [
                    'step' => $p->step,
                    'status' => $p->status,
                    'photo' => $p->photo ? asset($p->photo) : null,
                    'created_at' => $p->created_at ? $p->created_at->format('H:i') : null,
                ];
            })->values();
        @endphp
        const progressData = @json($progressJson);
        let currentTechLat = null;
        let currentTechLng = null;
        let currentTechAddr = '';

        function getStepStatus(step) {
            const found = progressData.find(function(p) { return p.step === step; });
            return found ? found.status : 'pending';
        }

        function getStepPhoto(step) {
            const found = progressData.find(function(p) { return p.step === step; });
            return found ? found.photo : null;
        }

        function getStepTime(step) {
            const found = progressData.find(function(p) { return p.step === step; });
            return found ? found.created_at : null;
        }

        window.findCurrentStep = function() {
            for (var s = 1; s <= 4; s++) {
                if (getStepStatus(s) !== 'completed') return s;
            }
            return 4;
        }

        window.toggleStep = function(step) {
            var card = document.getElementById('card-' + step);
            if (!card) return;
            var isVisible = card.style.display !== 'none';
            for (var s = 1; s <= 4; s++) {
                var c = document.getElementById('card-' + s);
                if (c) c.style.display = 'none';
            }
            if (!isVisible) {
                card.style.display = 'block';
                card.style.animation = 'none';
                setTimeout(function() { card.style.animation = ''; }, 10);
            } else if (getStepStatus(step) !== 'completed') {
                card.style.display = 'block';
            }
        }

        function updateConnector() {
            var completed = 0;
            for (var s = 1; s <= 4; s++) {
                if (getStepStatus(s) === 'completed') completed = s;
            }
            var pct = (completed / 4) * 100;
            var conn = document.getElementById('connector-gradient');
            if (conn) {
                conn.style.background = 'linear-gradient(90deg, #10b981 ' + pct + '%, #e2e8f0 ' + pct + '%)';
            }
        }

        function refreshStepUI() {
            // Hide all cards & reset loading states
            for (var s = 1; s <= 4; s++) {
                var c = document.getElementById('card-' + s);
                if (c) c.style.display = 'none';
                var ld = document.getElementById('loading-' + s);
                if (ld) { ld.classList.add('d-none'); ld.classList.remove('d-flex'); }
                var btn = document.getElementById('btn-confirm-' + s);
                if (btn) btn.disabled = false;
            }

            for (var s = 1; s <= 4; s++) {
                var status = getStepStatus(s);
                var icon = document.getElementById('icon-' + s);
                var iconContent = document.getElementById('icon-content-' + s);
                var label = document.getElementById('slabel-' + s);
                var time = document.getElementById('stime-' + s);

                if (status === 'completed') {
                    icon.style.background = '#10b981';
                    icon.style.borderColor = '#10b981';
                    icon.style.color = '#fff';
                    icon.style.boxShadow = '0 4px 12px rgba(16,185,129,.35)';
                    icon.classList.remove('active-icon');
                    icon.classList.add('completed-icon');
                    iconContent.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
                    label.style.color = '#10b981';
                    var st = getStepTime(s);
                    time.textContent = st ? 'Selesai ' + st : '';
                    time.style.color = '#10b981';
                } else {
                    icon.style.background = '#f1f5f9';
                    icon.style.borderColor = '#e2e8f0';
                    icon.style.color = '#94a3b8';
                    icon.style.boxShadow = 'none';
                    icon.classList.remove('active-icon', 'completed-icon');
                    label.style.color = '#64748b';
                    time.textContent = '';
                }
            }

            var current = findCurrentStep();
            if (current <= 4 && getStepStatus(current) !== 'completed') {
                var icon = document.getElementById('icon-' + current);
                if (icon) {
                    icon.style.background = 'linear-gradient(135deg, #3b82f6, #1d4ed8)';
                    icon.style.borderColor = '#93c5fd';
                    icon.style.color = '#fff';
                    icon.style.boxShadow = '0 4px 16px rgba(59,130,246,.4)';
                    icon.classList.add('active-icon');
                    label.style.color = '#1d4ed8';
                }
                var card = document.getElementById('card-' + current);
                if (card) card.style.display = 'block';
            }

            updateConnector();
        }

        window.retakePhoto = function(step) {
            var preview = document.getElementById('preview-' + step);
            preview.src = '';
            preview.classList.add('d-none');
            preview._watermarkBlob = null;
            document.getElementById('upload-preview-' + step).classList.add('d-none');
            document.getElementById('upload-default-' + step).classList.remove('d-none');
            document.getElementById('btn-confirm-' + step).classList.add('d-none');
            var ld = document.getElementById('loading-' + step);
            if (ld) { ld.classList.add('d-none'); ld.classList.remove('d-flex'); }
            document.getElementById('camera-' + step).value = '';
        }

        window.handlePhoto = function(step, input) {
            var file = input.files[0];
            if (!file) return;
            var lat = currentTechLat;
            var lng = currentTechLng;
            if (!lat || !lng) {
                Swal.fire('GPS Tidak Aktif', 'Tunggu hingga posisi GPS diperoleh.', 'warning');
                return;
            }
            addWatermark(file, lat, lng, function(blob) {
                var url = URL.createObjectURL(blob);
                var preview = document.getElementById('preview-' + step);
                preview.src = url;
                preview.classList.remove('d-none');
                preview._watermarkBlob = blob;
                document.getElementById('upload-default-' + step).classList.add('d-none');
                document.getElementById('upload-preview-' + step).classList.remove('d-none');
                document.getElementById('btn-confirm-' + step).classList.remove('d-none');
            });
        }

        function addWatermark(imgFile, lat, lng, callback) {
            var img = new Image();
            var canvas = document.createElement('canvas');
            var ctx = canvas.getContext('2d');

            img.onload = function () {
                var w = img.width, h = img.height;
                var max = 1920;
                if (w > max || h > max) {
                    var ratio = Math.min(max / w, max / h);
                    w = Math.round(w * ratio);
                    h = Math.round(h * ratio);
                }
                canvas.width = w;
                canvas.height = h;
                ctx.drawImage(img, 0, 0, w, h);

                var now = new Date();
                var dateStr = now.toLocaleDateString('id-ID', {
                    day: '2-digit', month: 'long', year: 'numeric'
                });
                var timeStr = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit', minute: '2-digit'
                });
                var addr = currentTechAddr || lat.toFixed(6) + ', ' + lng.toFixed(6);
                var mapsLink = 'https://www.google.com/maps?q=' + lat + ',' + lng;

                var barH = 90;
                ctx.fillStyle = 'rgba(0, 0, 0, 0.6)';
                ctx.fillRect(0, canvas.height - barH, canvas.width, barH);

                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 15px Arial, sans-serif';
                ctx.textBaseline = 'top';
                var padX = 14, padY = canvas.height - barH + 10;
                ctx.fillText('Lokasi: ' + addr.substring(0, 60), padX, padY);
                ctx.fillText('Link: ' + mapsLink, padX, padY + 26);
                ctx.fillText(dateStr + ' ' + timeStr, padX, padY + 52);

                canvas.toBlob(callback, 'image/jpeg', 0.9);
            };
            img.src = URL.createObjectURL(imgFile);
        }

        window.confirmStep = function(step) {
            var preview = document.getElementById('preview-' + step);
            var blob = preview._watermarkBlob;
            if (!blob) return;

            var lat = currentTechLat;
            var lng = currentTechLng;
            if (!lat || !lng) {
                Swal.fire('GPS Tidak Aktif', 'Tunggu hingga posisi GPS diperoleh.', 'warning');
                return;
            }

            document.getElementById('loading-' + step).classList.remove('d-none');
            document.getElementById('loading-' + step).classList.add('d-flex');
            document.getElementById('btn-confirm-' + step).disabled = true;

            var formData = new FormData();
            formData.append('photo', blob, 'step_' + step + '.jpg');
            formData.append('latitude', lat);
            formData.append('longitude', lng);
            formData.append('address', currentTechAddr);

            fetch('{{ route("troubleshoot.progress.upload", ["id" => $troubleshoot->id, "step" => "__STEP__"]) }}'.replace('__STEP__', step), {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.status === 'success') {
                    var now = new Date();
                    var timeStr = String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
                    progressData.push({ step: step, status: 'completed', photo: data.photo_url, created_at: timeStr });
                    refreshStepUI();
                    if (step === 4) {
                        Swal.fire({
                            title: 'Ticket Selesai!',
                            text: 'Semua step telah diselesaikan. Ticket akan ditutup.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.href = '{{ route("troubleshoot.index") }}';
                        });
                    } else {
                        Swal.fire({
                            title: 'Step ' + step + ' Selesai!',
                            text: 'Lanjutkan ke step berikutnya.',
                            icon: 'success',
                            confirmButtonText: 'Lanjutkan',
                            confirmButtonColor: '#4f46e5'
                        });
                    }
                } else {
                    throw new Error(data.message || 'Gagal mengirim foto');
                }
            })
            .catch(function(err) {
                console.error('Upload error:', err);
                var msg = err.message || 'Gagal mengirim foto. Coba lagi.';
                Swal.fire('Gagal', msg, 'error');
                var ld = document.getElementById('loading-' + step);
                if (ld) { ld.classList.add('d-none'); ld.classList.remove('d-flex'); }
                document.getElementById('btn-confirm-' + step).disabled = false;
            });
        }

        refreshStepUI();

        setInterval(function () {
            fetch('{{ route("troubleshoot.tracking-data", $troubleshoot->id) }}')
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.technician && data.technician.latitude && data.technician.longitude) {
                        const tLat = parseFloat(data.technician.latitude);
                        const tLng = parseFloat(data.technician.longitude);
                        if (!isNaN(tLat) && !isNaN(tLng)) {
                            applyTrackingData(tLat, tLng);
                        }
                    }
                })
                .catch(function (err) {
                    console.error('Gagal fetch tracking data:', err);
                });
        }, 5000);
    });
</script>
@endpush

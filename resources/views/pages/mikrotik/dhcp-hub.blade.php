@extends('layouts.app')

@section('title', 'MikroTik DHCP Hub')

@push('css')
    <link href="{{ asset('css/mikrotik-dhcp-hub.css') }}?v={{ time() }}" rel="stylesheet" />
@endpush

@section('content')
<div class="mikrotik-hub-wrapper py-3">

    {{-- Error Alert if Router is Unreachable --}}
    @if(!$is_connected && !empty($error_message))
        <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
            <div class="d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 9v2m0 4v.01" />
                    <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                </svg>
                <div>
                    <strong>Peringatan Koneksi Router:</strong> {{ $error_message }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ==================== Header Card ==================== --}}
    <div class="hub-header-card">
        <div class="row align-items-center g-3">
            <div class="col-lg-7 col-md-7">
                <div class="hub-title-group">
                    @include('pages.mikrotik.partials.icons', ['name' => 'router-hub', 'size' => 44])
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h1 class="hub-title">MikroTik DHCP Hub</h1>
                            <span class="live-sync-badge" id="live-sync-status-badge" title="Menerima pembaruan data secara langsung setiap 3 detik via SSE">
                                <span class="pulse-dot"></span>
                                <span>Live Sync</span>
                                <span class="live-sync-timer" id="live-sync-timer-text">(Baru saja)</span>
                            </span>
                        </div>
                        <div class="hub-meta-info">
                            <span class="hub-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                    <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                </svg>
                                Tarik Terakhir: <strong id="val-last-sync">{{ $last_sync }}</strong>
                            </span>
                            <span class="hub-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="8" rx="2" ry="2"/>
                                    <rect x="2" y="14" width="20" height="8" rx="2" ry="2"/>
                                    <line x1="6" y1="6" x2="6.01" y2="6"/>
                                    <line x1="6" y1="18" x2="6.01" y2="18"/>
                                </svg>
                                Router Host: <strong id="val-host">{{ $host }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 text-md-end text-start">
                <div class="d-inline-flex align-items-center gap-2 flex-wrap justify-content-md-end">
                    <button type="button" class="btn-hub-refresh" id="btn-refresh-hub" title="Tarik pembaruan data live seketika dari Router MikroTik">
                        @include('pages.mikrotik.partials.icons', ['name' => 'refresh', 'size' => 17])
                        <span>Refresh Live Data</span>
                    </button>
                    @can('reboot mikrotik')
                    <button type="button" class="btn-hub-reboot" id="btn-reboot-hub" title="Reboot Router MikroTik">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path>
                            <line x1="12" y1="2" x2="12" y2="12"></line>
                        </svg>
                        <span>Reboot Router</span>
                    </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== Router Hardware & System Resource Card ==================== --}}
    @php
        $res = $system_resource ?? [];
        $cpuLoad = $res['cpu_load'] ?? 0;
        $cpuClass = $cpuLoad > 80 ? 'danger' : ($cpuLoad > 50 ? 'warn' : '');
        $memPct = $res['memory_percent'] ?? 0;
        $hddPct = $res['hdd_percent'] ?? 0;
    @endphp
    <div class="system-resource-card">
        <div class="resource-header">
            <div class="resource-title-group">
                <div class="resource-title-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="4" width="16" height="16" rx="2" />
                        <rect x="9" y="9" width="6" height="6" />
                        <line x1="9" y1="1" x2="9" y2="4" />
                        <line x1="15" y1="1" x2="15" y2="4" />
                        <line x1="9" y1="20" x2="9" y2="23" />
                        <line x1="15" y1="20" x2="15" y2="23" />
                        <line x1="20" y1="9" x2="23" y2="9" />
                        <line x1="20" y1="14" x2="23" y2="14" />
                        <line x1="1" y1="9" x2="4" y2="9" />
                        <line x1="1" y1="14" x2="4" y2="14" />
                    </svg>
                </div>
                <div>
                    <h3 class="resource-title" id="res-router-name">{{ $res['router_name'] ?? 'MikroTik Router' }}</h3>
                    <div class="text-muted" style="font-size: 0.76rem;">Hardware & System Resource Status</div>
                </div>
            </div>

            <div class="resource-chips-wrap">
                <span class="resource-chip model-chip" title="Model Routerboard">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"/>
                        <rect x="2" y="14" width="20" height="8" rx="2" ry="2"/>
                    </svg>
                    Model: <strong id="res-board-name">{{ $res['board_name'] ?? 'MikroTik' }}</strong>
                </span>
                <span class="resource-chip" title="Arsitektur CPU">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="16 18 22 12 16 6"/>
                        <polyline points="8 6 2 12 8 18"/>
                    </svg>
                    Arch: <strong id="res-arch">{{ $res['architecture_name'] ?? '-' }}</strong>
                </span>
                <span class="resource-chip" title="Versi RouterOS">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    OS: <strong id="res-version">{{ $res['version'] ?? '-' }}</strong>
                </span>
                <span class="resource-chip uptime-chip" title="Waktu Aktif Router">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/>
                        <polyline points="12 7 12 12 15 15"/>
                    </svg>
                    Uptime: <strong id="res-uptime">{{ $res['uptime'] ?? '-' }}</strong>
                </span>
            </div>
        </div>

        {{-- Resource Bars Grid --}}
        <div class="resource-bars-grid">
            {{-- 1. CPU Load --}}
            <div class="resource-bar-box">
                <div class="resource-bar-header">
                    <span class="resource-bar-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="4" width="16" height="16" rx="2"/>
                            <rect x="9" y="9" width="6" height="6"/>
                        </svg>
                        CPU Load
                    </span>
                    <span class="resource-bar-pct" id="res-cpu-pct">{{ $cpuLoad }}%</span>
                </div>
                <div class="resource-progress-track">
                    <div class="resource-progress-fill fill-cpu {{ $cpuClass }}" id="res-cpu-bar" style="width: {{ $cpuLoad }}%;"></div>
                </div>
                <div class="resource-bar-footer">
                    <span>CPU: <strong id="res-cpu-name">{{ $res['cpu'] ?? 'CPU' }}</strong></span>
                    <span><strong id="res-cpu-count">{{ $res['cpu_count'] ?? 1 }} Core</strong> @ <span id="res-cpu-freq">{{ $res['cpu_frequency'] ?? '-' }}</span> MHz</span>
                </div>
            </div>

            {{-- 2. RAM Memory --}}
            <div class="resource-bar-box">
                <div class="resource-bar-header">
                    <span class="resource-bar-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 19v-3"/>
                            <path d="M10 19v-3"/>
                            <path d="M14 19v-3"/>
                            <path d="M18 19v-3"/>
                            <rect x="3" y="5" width="18" height="10" rx="2"/>
                        </svg>
                        Memory / RAM
                    </span>
                    <span class="resource-bar-pct" id="res-mem-pct">{{ $memPct }}%</span>
                </div>
                <div class="resource-progress-track">
                    <div class="resource-progress-fill fill-ram" id="res-mem-bar" style="width: {{ $memPct }}%;"></div>
                </div>
                <div class="resource-bar-footer">
                    <span>Used / Total</span>
                    <strong id="res-mem-formatted">{{ $res['memory_formatted'] ?? '-' }}</strong>
                </div>
            </div>

            {{-- 3. Storage / HDD --}}
            <div class="resource-bar-box">
                <div class="resource-bar-header">
                    <span class="resource-bar-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="12" x2="2" y2="12"/>
                            <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                            <line x1="6" y1="16" x2="6.01" y2="16"/>
                            <line x1="10" y1="16" x2="10.01" y2="16"/>
                        </svg>
                        Storage / Disk
                    </span>
                    <span class="resource-bar-pct" id="res-hdd-pct">{{ $hddPct }}%</span>
                </div>
                <div class="resource-progress-track">
                    <div class="resource-progress-fill fill-hdd" id="res-hdd-bar" style="width: {{ $hddPct }}%;"></div>
                </div>
                <div class="resource-bar-footer">
                    <span>Used / Total</span>
                    <strong id="res-hdd-formatted">{{ $res['hdd_formatted'] ?? '-' }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== 5 Stat Cards ==================== --}}
    <div class="row g-3 mb-4">
        {{-- Card 1: TOTAL LEASES --}}
        <div class="col-6 col-lg-auto flex-lg-fill">
            <div class="stat-card-clean" id="card-stat-total">
                <div class="stat-icon-wrapper stat-icon-total">
                    @include('pages.mikrotik.partials.icons', ['name' => 'total-leases', 'size' => 32])
                </div>
                <div class="stat-content">
                    <div class="stat-label">TOTAL LEASES</div>
                    <div class="stat-value-wrap">
                        <div class="stat-value" id="stat-total">{{ $stats['total'] ?? 0 }}</div>
                        <span class="stat-diff-badge" id="badge-diff-total" style="display: none;"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: BOUND / AKTIF --}}
        <div class="col-6 col-lg-auto flex-lg-fill">
            <div class="stat-card-clean" id="card-stat-bound">
                <div class="stat-icon-wrapper stat-icon-bound">
                    @include('pages.mikrotik.partials.icons', ['name' => 'bound-active', 'size' => 32])
                </div>
                <div class="stat-content">
                    <div class="stat-label">BOUND / AKTIF</div>
                    <div class="stat-value-wrap">
                        <div class="stat-value text-success" id="stat-bound">{{ $stats['bound'] ?? 0 }}</div>
                        <span class="stat-diff-badge" id="badge-diff-bound" style="display: none;"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: DYNAMIC LEASE --}}
        <div class="col-6 col-lg-auto flex-lg-fill">
            <div class="stat-card-clean" id="card-stat-dynamic">
                <div class="stat-icon-wrapper stat-icon-dynamic">
                    @include('pages.mikrotik.partials.icons', ['name' => 'dynamic-lease', 'size' => 32])
                </div>
                <div class="stat-content">
                    <div class="stat-label">DYNAMIC LEASE</div>
                    <div class="stat-value-wrap">
                        <div class="stat-value" id="stat-dynamic">{{ $stats['dynamic'] ?? 0 }}</div>
                        <span class="stat-diff-badge" id="badge-diff-dynamic" style="display: none;"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: WAITING --}}
        <div class="col-6 col-lg-auto flex-lg-fill">
            <div class="stat-card-clean" id="card-stat-waiting">
                <div class="stat-icon-wrapper stat-icon-waiting">
                    @include('pages.mikrotik.partials.icons', ['name' => 'waiting', 'size' => 32])
                </div>
                <div class="stat-content">
                    <div class="stat-label">WAITING</div>
                    <div class="stat-value-wrap">
                        <div class="stat-value" id="stat-waiting">{{ $stats['waiting'] ?? 0 }}</div>
                        <span class="stat-diff-badge" id="badge-diff-waiting" style="display: none;"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 5: STATIC LEASE --}}
        <div class="col-6 col-lg-auto flex-lg-fill">
            <div class="stat-card-clean" id="card-stat-static">
                <div class="stat-icon-wrapper stat-icon-static">
                    @include('pages.mikrotik.partials.icons', ['name' => 'static-lease', 'size' => 32])
                </div>
                <div class="stat-content">
                    <div class="stat-label">STATIC LEASE</div>
                    <div class="stat-value-wrap">
                        <div class="stat-value" id="stat-static">{{ $stats['static'] ?? 0 }}</div>
                        <span class="stat-diff-badge" id="badge-diff-static" style="display: none;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== Real-Time Bandwidth & Speed Traffic Monitor ==================== --}}
    @can('monitoring traffic mikrotik')
    <div class="traffic-monitor-card">
        {{-- Header Section --}}
        <div class="traffic-header">
            <div class="traffic-title-wrap">
                <div class="traffic-title-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                </div>
                <div>
                    <h3 class="traffic-title">Live Bandwidth & Traffic Monitor</h3>
                    <div class="traffic-subtitle">Grafik kecepatan Rx (Download) & Tx (Upload) real-time dari Router MikroTik</div>
                </div>
            </div>

            <div class="traffic-controls-wrap">
                {{-- Status Badge Live --}}
                <span class="traffic-status-badge live" id="traffic-status-badge">
                    <span class="pulse-dot"></span>
                    <span id="traffic-status-text">LIVE STREAMING</span>
                </span>

                {{-- Interface Selector --}}
                <div class="d-inline-flex align-items-center gap-2">
                    <label for="select-traffic-interface" class="form-label mb-0 text-muted fw-bold" style="font-size: 0.82rem;">Interface:</label>
                    <select id="select-traffic-interface" class="form-select filter-select-clean d-inline-block w-auto" style="min-width: 200px;">
                        @if(!empty($interfaces))
                            @foreach($interfaces as $iface)
                                <option value="{{ $iface['name'] }}" {{ ($loop->first || str_contains(strtolower($iface['name']), 'isp') || str_contains(strtolower($iface['name']), 'ether1')) ? 'selected' : '' }}>
                                    {{ $iface['name'] }} {{ $iface['comment'] ? "({$iface['comment']})" : '' }}
                                </option>
                            @endforeach
                        @else
                            <option value="ether1" selected>ether1</option>
                        @endif
                    </select>
                </div>

                {{-- Pause / Play Button --}}
                <button type="button" class="btn-traffic-control" id="btn-traffic-toggle" title="Jeda atau Lanjutkan live stream grafik">
                    <svg id="icon-traffic-pause" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="6" y="4" width="4" height="16"></rect>
                        <rect x="14" y="4" width="4" height="16"></rect>
                    </svg>
                    <svg id="icon-traffic-play" class="d-none" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                    <span id="text-traffic-toggle">Pause</span>
                </button>
            </div>
        </div>

        {{-- Metrics Row --}}
        <div class="traffic-metrics-grid">
            {{-- Download Metric --}}
            <div class="traffic-metric-box download">
                <div class="traffic-metric-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <polyline points="19 12 12 19 5 12"></polyline>
                    </svg>
                </div>
                <div class="traffic-metric-data">
                    <div class="traffic-metric-label">Download Speed (Rx)</div>
                    <div class="traffic-metric-value" id="val-rx-speed">0.00 Mbps</div>
                    <div class="traffic-metric-sub">
                        Peak: <strong id="val-rx-peak">0.00 Mbps</strong> &bull; <span id="val-rx-pps">0</span> pps
                    </div>
                </div>
            </div>

            {{-- Upload Metric --}}
            <div class="traffic-metric-box upload">
                <div class="traffic-metric-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="19" x2="12" y2="5"></line>
                        <polyline points="5 12 12 5 19 12"></polyline>
                    </svg>
                </div>
                <div class="traffic-metric-data">
                    <div class="traffic-metric-label">Upload Speed (Tx)</div>
                    <div class="traffic-metric-value" id="val-tx-speed">0.00 Mbps</div>
                    <div class="traffic-metric-sub">
                        Peak: <strong id="val-tx-peak">0.00 Mbps</strong> &bull; <span id="val-tx-pps">0</span> pps
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart Container --}}
        <div class="traffic-chart-container">
            <div id="mikrotik-traffic-chart"></div>
        </div>
    </div>
    @endcan

    {{-- ==================== Professional Blue Info Notice ==================== --}}
    <div class="hub-info-banner mb-3">
        <div class="d-flex align-items-center gap-3">
            <div class="info-banner-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
            </div>
            <div class="info-banner-text">
                <span class="fw-bold text-primary">Streaming Real-Time Otomatis (SSE):</span>
                <span class="text-secondary">Sistem menerima pembaruan status data perangkat secara langsung (Live Delta Streaming) tanpa membebani browser atau router. Anda juga dapat menekan tombol <strong>Refresh Live Data</strong> untuk sinkronisasi menyeluruh seketika.</span>
            </div>
        </div>
    </div>

    {{-- ==================== Controls Bar (Search & Filter) ==================== --}}
    <div class="hub-controls-bar">
        <div class="row align-items-center g-3">
            <div class="col-lg-4 col-md-5 col-12">
                <div class="search-input-group position-relative">
                    <span class="search-icon">
                        @include('pages.mikrotik.partials.icons', ['name' => 'search', 'size' => 16])
                    </span>
                    <input type="text" id="input-search" class="search-input-clean" placeholder="Cari IP, MAC, Hostname, atau Komentar..." autocomplete="off">
                    <button type="button" id="btn-clear-search" class="btn-clear-search" title="Hapus pencarian" style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="col-lg-8 col-md-7 col-12 text-md-end text-start">
                <div class="d-inline-flex align-items-center gap-3 flex-wrap justify-content-md-end">
                    {{-- Tipe Filter --}}
                    <div class="d-inline-flex align-items-center gap-2">
                        <label for="select-type" class="form-label mb-0 text-muted fw-bold" style="font-size: 0.85rem;">Tipe:</label>
                        <select id="select-type" class="form-select filter-select-clean d-inline-block w-auto">
                            <option value="all">[ Semua Tipe ]</option>
                            <option value="dynamic">DYNAMIC</option>
                            <option value="static">STATIC</option>
                        </select>
                    </div>

                    {{-- Status Filter --}}
                    <div class="d-inline-flex align-items-center gap-2">
                        <label for="select-status" class="form-label mb-0 text-muted fw-bold" style="font-size: 0.85rem;">Status:</label>
                        <select id="select-status" class="form-select filter-select-clean d-inline-block w-auto">
                            <option value="all">[ Semua Status ]</option>
                            <option value="bound">bound</option>
                            <option value="waiting">waiting</option>
                            <option value="offered">offered</option>
                            <option value="disabled">disabled</option>
                        </select>
                    </div>

                    {{-- Page Size --}}
                    <div class="d-inline-flex align-items-center gap-2">
                        <label for="select-page-size" class="form-label mb-0 text-muted fw-bold" style="font-size: 0.85rem;">Tampilkan:</label>
                        <select id="select-page-size" class="form-select page-size-select d-inline-block w-auto">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                            <option value="-1">Semua</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== Data Table Card ==================== --}}
    <div class="hub-table-card" id="hub-table-card">
        {{-- Real-Time 3s Stream Loading Bar --}}
        <div class="table-live-stream-bar" id="table-live-stream-bar" title="Live Sync Active (3s)">
            <div class="stream-bar-progress"></div>
        </div>

        <div class="table-responsive">
            <table class="hub-table" id="table-dhcp-leases">
                <thead>
                    <tr>
                        <th class="sortable-th" data-sort="ip_address" title="Klik untuk mengurutkan berdasarkan IP Address">
                            <div class="th-content">
                                <span>IP ADDRESS</span>
                                <span class="sort-icon" id="sort-icon-ip_address">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg>
                                </span>
                            </div>
                        </th>
                        <th class="sortable-th" data-sort="mac_address" title="Klik untuk mengurutkan berdasarkan MAC Address">
                            <div class="th-content">
                                <span>MAC ADDRESS</span>
                                <span class="sort-icon" id="sort-icon-mac_address">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg>
                                </span>
                            </div>
                        </th>
                        <th class="sortable-th" data-sort="host_name" title="Klik untuk mengurutkan berdasarkan Device / Hostname">
                            <div class="th-content">
                                <span>DEVICE / HOSTNAME</span>
                                <span class="sort-icon" id="sort-icon-host_name">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg>
                                </span>
                            </div>
                        </th>
                        <th class="sortable-th text-center" data-sort="device_type" title="Klik untuk mengurutkan berdasarkan Tipe">
                            <div class="th-content justify-content-center">
                                <span>TIPE</span>
                                <span class="sort-icon" id="sort-icon-device_type">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg>
                                </span>
                            </div>
                        </th>
                        <th class="sortable-th text-center" data-sort="status" title="Klik untuk mengurutkan berdasarkan Status">
                            <div class="th-content justify-content-center">
                                <span>STATUS</span>
                                <span class="sort-icon" id="sort-icon-status">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg>
                                </span>
                            </div>
                        </th>
                        <th class="sortable-th text-center" data-sort="expires_after" title="Klik untuk mengurutkan berdasarkan Expires">
                            <div class="th-content justify-content-center">
                                <span>EXPIRES</span>
                                <span class="sort-icon" id="sort-icon-expires_after">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg>
                                </span>
                            </div>
                        </th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody id="dhcp-table-body">
                    {{-- Dynamically populated via JavaScript for instant pagination and live search --}}
                </tbody>
            </table>
        </div>

        {{-- ==================== Pagination & Showing Footer ==================== --}}
        <div class="hub-pagination-footer">
            <div class="showing-entries-text">
                Menampilkan <strong id="showing-start">0</strong> sampai <strong id="showing-end">0</strong> dari <strong id="showing-total">0</strong> data
                <span id="showing-filtered-text" style="display: none;"> (difilter dari <span id="showing-grand-total">0</span> total)</span>
            </div>
            <div class="hub-pagination-nav" id="pagination-controls">
                {{-- Dynamically populated buttons --}}
            </div>
        </div>
    </div>

</div>

{{-- ==================== Modal Detail Pelanggan ==================== --}}
<div class="modal fade modal-customer-detail" id="modal-customer-detail" tabindex="-1" aria-labelledby="modalCustomerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded bg-primary-lt text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="22" height="22" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <circle cx="12" cy="7" r="4" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        </svg>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modal-cust-name">Detail Pelanggan</h5>
                        <div class="text-muted" style="font-size: 0.8rem;" id="modal-cust-mac">MAC: -</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-cust-body">
                {{-- Loading Spinner --}}
                <div id="modal-cust-loader" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Memuat data...</span>
                    </div>
                    <div class="text-muted mt-2 fw-semibold" style="font-size: 0.875rem;">Mencari data pelanggan dari database...</div>
                </div>

                {{-- Customer Found Content --}}
                <div id="modal-cust-content" style="display: none;">
                    <div class="row g-3">
                        {{-- Left Column: Data Pelanggan & Layanan --}}
                        <div class="col-md-6 col-12">
                            <div class="detail-card-box">
                                <div class="detail-section-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                    </svg>
                                    Informasi Pelanggan
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Nama Pelanggan</span>
                                    <span class="detail-value" id="c-name">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Status Pelanggan</span>
                                    <span class="detail-value" id="c-status">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">No. WhatsApp</span>
                                    <span class="detail-value d-inline-flex align-items-center">
                                        <span id="c-telp">-</span>
                                        <a href="#" id="c-wa-btn" target="_blank" class="btn-wa-chat" style="display: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                            </svg>
                                            WA
                                        </a>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Email</span>
                                    <span class="detail-value" id="c-email">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">NIK</span>
                                    <span class="detail-value" id="c-nik">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Paket Internet</span>
                                    <span class="detail-value text-primary" id="c-paket">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Tarif / Harga</span>
                                    <span class="detail-value" id="c-price">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Tipe Pelanggan</span>
                                    <span class="detail-value" id="c-type">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Organisasi / Mitra</span>
                                    <span class="detail-value" id="c-org">-</span>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Data Jaringan & Perangkat --}}
                        <div class="col-md-6 col-12">
                            <div class="detail-card-box">
                                <div class="detail-section-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="3" y="13" width="18" height="8" rx="2" />
                                        <line x1="17" y1="17" x2="17" y2="17.01" />
                                        <line x1="13" y1="17" x2="13" y2="17.01" />
                                        <line x1="15" y1="13" x2="15" y2="11" />
                                        <path d="M11.5 6.5a4.5 4.5 0 0 1 7 0" />
                                    </svg>
                                    Informasi Jaringan & Wi-Fi
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">MAC Address</span>
                                    <span class="detail-value font-mono-clean" id="c-mac">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">SSID Wi-Fi</span>
                                    <span class="detail-value text-azure fw-bold" id="c-wifi-name">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Password Wi-Fi</span>
                                    <span class="detail-value d-inline-flex align-items-center gap-1">
                                        <span id="c-wifi-pass" class="font-mono-clean">-</span>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">PPPoE Username</span>
                                    <span class="detail-value font-mono-clean text-muted" id="c-pppoe">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Router</span>
                                    <span class="detail-value" id="c-router">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">OLT</span>
                                    <span class="detail-value" id="c-olt">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">ODC / ODP</span>
                                    <span class="detail-value" id="c-odc-odp">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">VLAN</span>
                                    <span class="detail-value" id="c-vlan">-</span>
                                </div>
                            </div>
                        </div>

                        {{-- Full-Width Bottom: Alamat Lengkap --}}
                        <div class="col-12">
                            <div class="detail-card-box">
                                <div class="detail-section-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                                    </svg>
                                    Alamat & Wilayah
                                </div>
                                <div class="text-dark fw-semibold" id="c-address" style="font-size: 0.875rem;">-</div>
                            </div>
                        </div>

                        {{-- Lokasi Koordinat & Peta Leaflet --}}
                        <div class="col-12">
                            <div class="detail-card-box">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2 pb-2 border-bottom">
                                    <div class="detail-section-title mb-0 border-0 pb-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="12" cy="11" r="3" />
                                            <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                                        </svg>
                                        Peta Lokasi & Koordinat
                                    </div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap" id="c-map-action-btns" style="display: none !important;">
                                        <a href="#" id="c-gmaps-btn" target="_blank" class="btn-map-action btn-map-gmaps" title="Buka titik koordinat di Google Maps">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                                <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"></polygon>
                                                <line x1="9" y1="3" x2="9" y2="18"></line>
                                                <line x1="15" y1="6" x2="15" y2="21"></line>
                                            </svg>
                                            <span>Buka Google Maps</span>
                                        </a>
                                        <a href="#" id="c-gmaps-dir-btn" target="_blank" class="btn-map-action btn-map-direction" title="Petunjuk Arah rute navigasi ke lokasi pelanggan">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                                <polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
                                            </svg>
                                            <span>Petunjuk Arah</span>
                                        </a>
                                    </div>
                                </div>

                                {{-- Coordinates Info Bar --}}
                                <div id="c-coords-wrapper" class="mb-2" style="display: none;">
                                    <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 0.82rem;">
                                        <span>Koordinat:</span>
                                        <span id="c-coords" class="font-mono-clean fw-bold text-dark bg-white px-2 py-1 rounded border">-</span>
                                    </div>
                                </div>

                                {{-- Map Canvas --}}
                                <div id="c-map-wrapper" style="display: none;">
                                    <div id="customer-modal-map" class="customer-map-container"></div>
                                </div>

                                {{-- Notice if no coordinates --}}
                                <div id="c-no-coords-alert" class="alert alert-warning align-items-center gap-2 py-2 px-3 mb-0" style="display: none !important; font-size: 0.84rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                    <span>Pelanggan ini belum memiliki data koordinat (Latitude/Longitude).</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Customer Not Found Content --}}
                <div id="modal-cust-not-found" style="display: none;">
                    <div class="not-registered-box">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2 text-warning" width="36" height="36" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 9v2m0 4v.01" />
                            <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                        </svg>
                        <h4 class="fw-bold text-dark mb-1">Perangkat Belum Terdaftar</h4>
                        <p class="text-muted mb-3" style="font-size: 0.875rem;">
                            MAC Address <strong id="not-found-mac" class="font-mono-clean text-dark">-</strong> belum terhubung ke master data pelanggan di database.
                        </p>
                        @can('buat pelanggan')
                            <a href="{{ route('customer.create') }}" class="btn btn-primary px-4 py-2 fw-semibold">
                                + Daftarkan Sebagai Pelanggan Baru
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    @if(auth()->user()->can('ubah pelanggan') || auth()->user()->can('edit pelanggan'))
                    <a href="#" id="modal-btn-edit-cust" class="btn btn-outline-primary px-3 py-2 fw-semibold" target="_blank" style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />
                            <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />
                        </svg>
                        Buka Halaman Edit Pelanggan
                    </a>
                    @endif
                </div>
                <button type="button" class="btn btn-secondary px-4 py-2 fw-semibold" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@can('reboot mikrotik')
{{-- ==================== Modal Konfirmasi Reboot Router ==================== --}}
<div class="modal fade" id="modal-confirm-reboot" tabindex="-1" aria-labelledby="modalConfirmRebootLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header bg-danger text-white py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    <h5 class="modal-title fw-bold mb-0" id="modalConfirmRebootLabel">Konfirmasi Reboot Router</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning d-flex align-items-start gap-2 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger flex-shrink-0 mt-1">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <div style="font-size: 0.88rem; line-height: 1.45;">
                        <strong>Peringatan Penting:</strong> Tindakan ini akan mematikan dan me-restart router MikroTik (<strong>{{ $host }}</strong>). Seluruh koneksi internet dan sesi pelanggan yang terhubung akan <strong>terputus sementara</strong> selama proses reboot berlangsung (sekitar 1–2 menit).
                    </div>
                </div>
                <p class="text-secondary mb-0" style="font-size: 0.9rem;">
                    Apakah Anda benar-benar yakin ingin melanjutkan proses restart router sekarang?
                </p>
            </div>
            <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary px-4 fw-semibold" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger px-4 fw-bold" id="btn-execute-reboot">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                        <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path>
                        <line x1="12" y1="2" x2="12" y2="12"></line>
                    </svg>
                    <span>Ya, Reboot Router Sekarang</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ==================== Modal Overlay Reboot In-Progress ==================== --}}
<div class="modal fade" id="modal-reboot-countdown" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg text-center p-4" style="border-radius: 16px;">
            <div class="modal-body py-4">
                <div class="reboot-spinner-wrap mb-3">
                    <div class="spinner-border text-danger" style="width: 3.5rem; height: 3.5rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-2">Router Sedang Memulai Ulang...</h4>
                <p class="text-muted mb-4" style="font-size: 0.88rem;">
                    Perintah reboot telah dikirim. Routerboard sedang melakukan booting ulang sistem.
                </p>
                <div class="reboot-countdown-box mb-3">
                    <span class="countdown-label">Estimasi Waktu Tunggu:</span>
                    <div class="countdown-number text-danger fw-bold" id="reboot-countdown-timer">60s</div>
                </div>
                <div class="reboot-status-text text-secondary" id="reboot-reconnect-status" style="font-size: 0.82rem;">
                    Menunggu router aktif kembali...
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('input-search');
    const clearSearchBtn = document.getElementById('btn-clear-search');
    const typeSelect = document.getElementById('select-type');
    const statusSelect = document.getElementById('select-status');
    const pageSizeSelect = document.getElementById('select-page-size');
    const refreshBtn = document.getElementById('btn-refresh-hub');
    const tableBody = document.getElementById('dhcp-table-body');
    const lastSyncEl = document.getElementById('val-last-sync');
    const statTotal = document.getElementById('stat-total');
    const statBound = document.getElementById('stat-bound');
    const statDynamic = document.getElementById('stat-dynamic');
    const statWaiting = document.getElementById('stat-waiting');
    const statStatic = document.getElementById('stat-static');

    const showingStartEl = document.getElementById('showing-start');
    const showingEndEl = document.getElementById('showing-end');
    const showingTotalEl = document.getElementById('showing-total');
    const showingFilteredTextEl = document.getElementById('showing-filtered-text');
    const showingGrandTotalEl = document.getElementById('showing-grand-total');
    const paginationControlsEl = document.getElementById('pagination-controls');

    // Modal elements
    const customerModalEl = document.getElementById('modal-customer-detail');
    let customerModalInstance = null;
    let customerDetailMap = null;
    let customerDetailMarker = null;

    if (customerModalEl && typeof bootstrap !== 'undefined') {
        customerModalInstance = new bootstrap.Modal(customerModalEl);
        customerModalEl.addEventListener('shown.bs.modal', function () {
            if (customerDetailMap) {
                setTimeout(() => {
                    customerDetailMap.invalidateSize();
                }, 100);
            }
        });
    }

    const modalLoader = document.getElementById('modal-cust-loader');
    const modalContent = document.getElementById('modal-cust-content');
    const modalNotFound = document.getElementById('modal-cust-not-found');
    const modalCustName = document.getElementById('modal-cust-name');
    const modalCustMac = document.getElementById('modal-cust-mac');
    const modalBtnEditCust = document.getElementById('modal-btn-edit-cust');
    const notFoundMac = document.getElementById('not-found-mac');

    let allLeases = @json($leases);
    if (!Array.isArray(allLeases)) {
        allLeases = Object.values(allLeases || {});
    }
    let filteredLeases = [];
    let currentPage = 1;
    let pageSize = parseInt(pageSizeSelect ? pageSizeSelect.value : 10) || 10;
    let sortColumn = 'ip_address';
    let sortDirection = 'asc';

    const iconNeutral = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg>';
    const iconAsc = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>';
    const iconDesc = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>';

    function updateSortIcons() {
        document.querySelectorAll('.sortable-th').forEach(th => {
            const col = th.getAttribute('data-sort');
            const iconEl = th.querySelector('.sort-icon');
            th.classList.remove('sorted-asc', 'sorted-desc');
            if (col === sortColumn) {
                if (sortDirection === 'asc') {
                    th.classList.add('sorted-asc');
                    if (iconEl) iconEl.innerHTML = iconAsc;
                } else {
                    th.classList.add('sorted-desc');
                    if (iconEl) iconEl.innerHTML = iconDesc;
                }
            } else {
                if (iconEl) iconEl.innerHTML = iconNeutral;
            }
        });
    }

    function compareIp(ipA, ipB) {
        const aParts = (ipA || '').split('.').map(x => parseInt(x, 10) || 0);
        const bParts = (ipB || '').split('.').map(x => parseInt(x, 10) || 0);
        for (let i = 0; i < 4; i++) {
            const diff = (aParts[i] || 0) - (bParts[i] || 0);
            if (diff !== 0) return diff;
        }
        return 0;
    }

    function sortLeases(list) {
        if (!sortColumn) return list;
        return [...list].sort((a, b) => {
            let result = 0;
            if (sortColumn === 'ip_address') {
                result = compareIp(a.ip_address, b.ip_address);
            } else {
                const valA = (a[sortColumn] || '').toString().toLowerCase();
                const valB = (b[sortColumn] || '').toString().toLowerCase();
                result = valA.localeCompare(valB, undefined, { numeric: true, sensitivity: 'base' });
            }
            return sortDirection === 'asc' ? result : -result;
        });
    }

    // ==========================================
    // Filter & Search Logic (Dual Filter: Tipe & Status + Realtime Search)
    // ==========================================
    function applyFilters(highlightMacs = null, resetPage = false) {
        if (resetPage) {
            currentPage = 1;
        }

        const validHighlight = (highlightMacs instanceof Set) ? highlightMacs : new Set();
        const query = (searchInput ? searchInput.value : '').trim().toLowerCase();
        const selectedType = (typeSelect ? typeSelect.value : 'all').toLowerCase();
        const selectedStatus = (statusSelect ? statusSelect.value : 'all').toLowerCase();

        filteredLeases = allLeases.filter(lease => {
            const ip = (lease.ip_address || '').toLowerCase();
            const mac = (lease.mac_address || '').toLowerCase();
            const host = (lease.host_name || '').toLowerCase();
            const comment = (lease.comment || '').toLowerCase();
            const server = (lease.server || '').toLowerCase();
            const status = (lease.status || '').toLowerCase();
            const type = (lease.device_type || '').toLowerCase();

            // Multi-term query matching
            let matchesQuery = true;
            if (query) {
                const terms = query.split(/\s+/).filter(Boolean);
                const combined = `${ip} ${mac} ${host} ${comment} ${server} ${type} ${status}`;
                matchesQuery = terms.every(t => combined.includes(t));
            }

            const matchesType = (selectedType === 'all') || (type === selectedType);
            const matchesStatus = (selectedStatus === 'all') || (status === selectedStatus);

            return matchesQuery && matchesType && matchesStatus;
        });

        filteredLeases = sortLeases(filteredLeases);
        renderCurrentPage(validHighlight);
    }

    // ==========================================
    // Render Current Page
    // ==========================================
    function renderCurrentPage(highlightMacs = null) {
        const validHighlight = (highlightMacs instanceof Set) ? highlightMacs : new Set();
        const totalItems = filteredLeases.length;
        const grandTotal = allLeases.length;

        // Calculate pages
        let effectivePageSize = pageSize === -1 ? totalItems : pageSize;
        if (effectivePageSize <= 0) effectivePageSize = 1;
        const totalPages = Math.ceil(totalItems / effectivePageSize) || 1;

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIndex = totalItems === 0 ? 0 : (currentPage - 1) * effectivePageSize;
        const endIndex = pageSize === -1 ? totalItems : Math.min(startIndex + effectivePageSize, totalItems);
        const pageItems = filteredLeases.slice(startIndex, endIndex);

        // Update Table Rows
        if (pageItems.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="empty-table-state py-4 text-center text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2 text-secondary" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="10" cy="10" r="7"/>
                            <line x1="21" y1="21" x2="15" y2="15"/>
                        </svg>
                        <div class="fw-semibold">Tidak ada data DHCP Leases yang cocok dengan kriteria pencarian/filter.</div>
                    </td>
                </tr>
            `;
        } else {
            let html = '';
            pageItems.forEach(lease => {
                const ip = lease.ip_address || '-';
                const mac = (lease.mac_address || '').toUpperCase();
                const host = lease.host_name || 'Unknown Device';
                const type = (lease.device_type || 'DYNAMIC').toUpperCase();
                const status = (lease.status || 'waiting').toLowerCase();
                const expires = lease.expires_after || (type === 'STATIC' ? 'Static' : '-');

                const typeBadge = (type === 'DYNAMIC')
                    ? '<span class="badge-type-dynamic">[DYNAMIC]</span>'
                    : '<span class="badge-type-static">[STATIC]</span>';

                let statusBadge = '';
                if (status === 'bound') {
                    statusBadge = '<span class="badge-status-bound">[ bound ]</span>';
                } else if (status === 'waiting') {
                    statusBadge = '<span class="badge-status-waiting">[ waiting ]</span>';
                } else if (status === 'offered') {
                    statusBadge = '<span class="badge-status-offered">[ offered ]</span>';
                } else {
                    statusBadge = `<span class="badge-status-disabled">[ ${status} ]</span>`;
                }

                const isFlash = validHighlight.has(mac);
                const flashClass = isFlash ? 'row-updated-flash' : '';

                html += `
                    <tr class="lease-row ${flashClass}" id="row-${mac.replace(/[^a-zA-Z0-9]/g, '')}">
                        <td class="font-mono-clean">
                            ${ip && ip !== '-' ? `
                                <button type="button" class="btn-copy-nat-ip" data-ip="${ip}" title="Klik untuk salin script NAT: /ip firewall nat set [find comment=&quot;REMOTONU&quot;] to-addresses=${ip}">
                                    <span>${ip}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="copy-nat-icon"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                </button>
                            ` : '-'}
                        </td>
                        <td class="font-mono-clean text-muted">${mac}</td>
                        <td class="fw-semibold text-dark">${host}</td>
                        <td class="text-center">${typeBadge}</td>
                        <td class="text-center">${statusBadge}</td>
                        <td class="text-center font-mono-clean text-muted">${expires}</td>
                        <td class="text-center">
                            <button type="button" class="btn-action-customer btn-view-customer" data-mac="${mac}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="7" r="4" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                </svg>
                                <span>Detail Pelanggan</span>
                            </button>
                        </td>
                    </tr>
                `;
            });
            tableBody.innerHTML = html;
        }

        // Update Showing Info
        if (showingStartEl) showingStartEl.textContent = totalItems === 0 ? 0 : (startIndex + 1).toLocaleString('id-ID');
        if (showingEndEl) showingEndEl.textContent = endIndex.toLocaleString('id-ID');
        if (showingTotalEl) showingTotalEl.textContent = totalItems.toLocaleString('id-ID');

        if (showingFilteredTextEl && showingGrandTotalEl) {
            if (totalItems !== grandTotal) {
                showingGrandTotalEl.textContent = grandTotal.toLocaleString('id-ID');
                showingFilteredTextEl.style.display = 'inline';
            } else {
                showingFilteredTextEl.style.display = 'none';
            }
        }

        // Render Pagination Controls
        renderPaginationButtons(totalPages);
    }

    // ==========================================
    // Render Pagination Buttons
    // ==========================================
    function renderPaginationButtons(totalPages) {
        if (!paginationControlsEl) return;
        if (pageSize === -1 || totalPages <= 1) {
            paginationControlsEl.innerHTML = '';
            return;
        }

        let html = '';

        // First & Prev buttons
        html += `<button type="button" class="btn-page-item" data-page="1" ${currentPage === 1 ? 'disabled' : ''} title="Halaman Pertama">«</button>`;
        html += `<button type="button" class="btn-page-item" data-page="${currentPage - 1}" ${currentPage === 1 ? 'disabled' : ''} title="Halaman Sebelumnya">‹</button>`;

        // Smart sliding window page numbers
        const delta = 2;
        const left = Math.max(1, currentPage - delta);
        const right = Math.min(totalPages, currentPage + delta);

        if (left > 1) {
            html += `<button type="button" class="btn-page-item ${currentPage === 1 ? 'active' : ''}" data-page="1">1</button>`;
            if (left > 2) {
                html += `<span class="page-ellipsis">...</span>`;
            }
        }

        for (let i = left; i <= right; i++) {
            html += `<button type="button" class="btn-page-item ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
        }

        if (right < totalPages) {
            if (right < totalPages - 1) {
                html += `<span class="page-ellipsis">...</span>`;
            }
            html += `<button type="button" class="btn-page-item ${currentPage === totalPages ? 'active' : ''}" data-page="${totalPages}">${totalPages}</button>`;
        }

        // Next & Last buttons
        html += `<button type="button" class="btn-page-item" data-page="${currentPage + 1}" ${currentPage === totalPages ? 'disabled' : ''} title="Halaman Berikutnya">›</button>`;
        html += `<button type="button" class="btn-page-item" data-page="${totalPages}" ${currentPage === totalPages ? 'disabled' : ''} title="Halaman Terakhir">»</button>`;

        paginationControlsEl.innerHTML = html;

        // Attach click listeners to page buttons
        paginationControlsEl.querySelectorAll('.btn-page-item').forEach(btn => {
            btn.addEventListener('click', function () {
                if (this.disabled || this.classList.contains('active')) return;
                const targetPage = parseInt(this.getAttribute('data-page'));
                if (targetPage && targetPage >= 1 && targetPage <= totalPages) {
                    currentPage = targetPage;
                    renderCurrentPage();
                }
            });
        });
    }

    // ==========================================
    // Table Click Delegations (Copy NAT Command & Customer Detail Modal)
    // ==========================================
    if (tableBody) {
        tableBody.addEventListener('click', function (e) {
            // 1. Copy NAT Script saat IP Address diklik
            const copyIpBtn = e.target.closest('.btn-copy-nat-ip');
            if (copyIpBtn) {
                const ip = copyIpBtn.getAttribute('data-ip');
                if (ip && ip !== '-') {
                    const natCmd = `/ip firewall nat set [find comment="REMOTONU"] to-addresses=${ip}`;
                    copyNatCommandToClipboard(natCmd, copyIpBtn);
                }
                return;
            }

            // 2. Customer Detail Modal
            const btn = e.target.closest('.btn-view-customer');
            if (!btn) return;

            const mac = btn.getAttribute('data-mac');
            if (!mac) return;

            openCustomerDetailModal(mac);
        });
    }

    function copyNatCommandToClipboard(cmd, btnEl) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(cmd).then(() => {
                showNatCopiedFeedback(cmd, btnEl);
            }).catch(() => {
                fallbackNatCopy(cmd, btnEl);
            });
        } else {
            fallbackNatCopy(cmd, btnEl);
        }
    }

    function fallbackNatCopy(text, btnEl) {
        const temp = document.createElement('textarea');
        temp.value = text;
        temp.style.position = 'fixed';
        temp.style.left = '-9999px';
        document.body.appendChild(temp);
        temp.select();
        try {
            document.execCommand('copy');
            showNatCopiedFeedback(text, btnEl);
        } catch (err) {
            console.error('Gagal menyalin perintah NAT:', err);
        }
        document.body.removeChild(temp);
    }

    function showNatCopiedFeedback(cmd, btnEl) {
        if (btnEl) {
            btnEl.classList.add('copied');
            setTimeout(() => {
                btnEl.classList.remove('copied');
            }, 2000);
        }

        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });

            Toast.fire({
                icon: 'success',
                title: `<div style="font-size:12px;font-weight:700;color:#059669;margin-bottom:3px;">Perintah NAT MikroTik Disalin!</div><code style="font-size:11px;color:#0284c7;word-break:break-all;display:block;background:#f8fafc;padding:5px 8px;border-radius:5px;border:1px solid #cbd5e1;font-family:monospace;">${cmd}</code>`
            });
        }
    }

    async function openCustomerDetailModal(mac) {
        if (!customerModalInstance) {
            const modalEl = document.getElementById('modal-customer-detail');
            if (modalEl && typeof bootstrap !== 'undefined') {
                customerModalInstance = new bootstrap.Modal(modalEl);
            }
        }

        if (modalCustName) modalCustName.textContent = 'Memuat Data Pelanggan...';
        if (modalCustMac) modalCustMac.textContent = `MAC Address: ${mac}`;
        if (modalLoader) modalLoader.style.display = 'block';
        if (modalContent) modalContent.style.display = 'none';
        if (modalNotFound) modalNotFound.style.display = 'none';
        if (modalBtnEditCust) modalBtnEditCust.style.display = 'none';

        const initialNoCoordsAlert = document.getElementById('c-no-coords-alert');
        if (initialNoCoordsAlert) initialNoCoordsAlert.style.setProperty('display', 'none', 'important');
        const initialCoordsWrapper = document.getElementById('c-coords-wrapper');
        if (initialCoordsWrapper) initialCoordsWrapper.style.setProperty('display', 'none', 'important');
        const initialMapWrapper = document.getElementById('c-map-wrapper');
        if (initialMapWrapper) initialMapWrapper.style.setProperty('display', 'none', 'important');
        const initialMapActionBtns = document.getElementById('c-map-action-btns');
        if (initialMapActionBtns) initialMapActionBtns.style.setProperty('display', 'none', 'important');

        if (customerModalInstance) customerModalInstance.show();

        try {
            const url = `{{ route('mikrotik.dhcp.customer_by_mac') }}?mac=${encodeURIComponent(mac)}&t=${Date.now()}`;
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();
            if (modalLoader) modalLoader.style.display = 'none';

            if (data.success && data.found && data.customer) {
                const c = data.customer;
                if (modalCustName) modalCustName.textContent = c.name || 'Detail Pelanggan';
                if (modalCustMac) modalCustMac.textContent = `MAC Address: ${c.mac_address}`;

                const cNameEl = document.getElementById('c-name');
                if (cNameEl) cNameEl.textContent = c.name || '-';
                
                const statusBadge = (c.status === 'active')
                    ? '<span class="badge bg-success-lt text-success fw-bold">Aktif</span>'
                    : `<span class="badge bg-danger-lt text-danger fw-bold">${c.status || '-'}</span>`;
                const cStatusEl = document.getElementById('c-status');
                if (cStatusEl) cStatusEl.innerHTML = statusBadge;

                const cTelpEl = document.getElementById('c-telp');
                if (cTelpEl) cTelpEl.textContent = c.telp || '-';
                const waBtn = document.getElementById('c-wa-btn');
                if (waBtn) {
                    if (c.telp && c.telp.length > 5) {
                        let cleanPhone = c.telp.replace(/[^0-9]/g, '');
                        if (cleanPhone.startsWith('0')) cleanPhone = '62' + cleanPhone.substring(1);
                        waBtn.href = `https://wa.me/${cleanPhone}`;
                        waBtn.style.display = 'inline-flex';
                    } else {
                        waBtn.style.display = 'none';
                    }
                }

                const cEmailEl = document.getElementById('c-email');
                if (cEmailEl) cEmailEl.textContent = c.email || '-';
                const cNikEl = document.getElementById('c-nik');
                if (cNikEl) cNikEl.textContent = c.nik || '-';
                const cPaketEl = document.getElementById('c-paket');
                if (cPaketEl) cPaketEl.textContent = c.paket_name || '-';
                const cPriceEl = document.getElementById('c-price');
                if (cPriceEl) cPriceEl.textContent = c.price_amount || '-';
                const cTypeEl = document.getElementById('c-type');
                if (cTypeEl) cTypeEl.textContent = c.type_name || '-';
                const cOrgEl = document.getElementById('c-org');
                if (cOrgEl) cOrgEl.textContent = c.organization || '-';

                const cMacEl = document.getElementById('c-mac');
                if (cMacEl) cMacEl.textContent = c.mac_address || '-';
                const cWifiNameEl = document.getElementById('c-wifi-name');
                if (cWifiNameEl) cWifiNameEl.textContent = c.name_wifi || '-';
                const cWifiPassEl = document.getElementById('c-wifi-pass');
                if (cWifiPassEl) cWifiPassEl.textContent = c.password_wifi || '-';
                const cPppoeEl = document.getElementById('c-pppoe');
                if (cPppoeEl) cPppoeEl.textContent = c.pppoe_username || '-';

                const routerInfo = (c.router_name !== '-' && c.router_ip !== '-')
                    ? `${c.router_name} (${c.router_ip})`
                    : c.router_name;
                const cRouterEl = document.getElementById('c-router');
                if (cRouterEl) cRouterEl.textContent = routerInfo;
                const cOltEl = document.getElementById('c-olt');
                if (cOltEl) cOltEl.textContent = c.olt_name || '-';

                const odcInfo = [c.odc_name, c.odp_name].filter(x => x && x !== '-').join(' / ') || '-';
                const cOdcOdpEl = document.getElementById('c-odc-odp');
                if (cOdcOdpEl) cOdcOdpEl.textContent = odcInfo;
                const cVlanEl = document.getElementById('c-vlan');
                if (cVlanEl) cVlanEl.textContent = c.vlan_name || '-';

                const cAddrEl = document.getElementById('c-address');
                if (cAddrEl) cAddrEl.textContent = c.address || 'Alamat belum diatur';

                // ==========================================
                // Koordinat & Peta Leaflet Pelanggan
                // ==========================================
                const latNum = parseFloat(c.latitude);
                const lngNum = parseFloat(c.longitude);
                const hasValidCoords = !isNaN(latNum) && !isNaN(lngNum) && (latNum !== 0 || lngNum !== 0);

                const coordsWrapper = document.getElementById('c-coords-wrapper');
                const coordsEl = document.getElementById('c-coords');
                const mapWrapper = document.getElementById('c-map-wrapper');
                const mapActionBtns = document.getElementById('c-map-action-btns');
                const noCoordsAlert = document.getElementById('c-no-coords-alert');
                const gmapsBtn = document.getElementById('c-gmaps-btn');
                const gmapsDirBtn = document.getElementById('c-gmaps-dir-btn');

                if (hasValidCoords) {
                    if (coordsEl) coordsEl.textContent = `${latNum}, ${lngNum}`;
                    if (coordsWrapper) coordsWrapper.style.setProperty('display', 'block', 'important');
                    if (mapWrapper) mapWrapper.style.setProperty('display', 'block', 'important');
                    if (mapActionBtns) mapActionBtns.style.setProperty('display', 'flex', 'important');
                    if (noCoordsAlert) noCoordsAlert.style.setProperty('display', 'none', 'important');

                    // Google Maps link buttons
                    if (gmapsBtn) {
                        gmapsBtn.href = `https://www.google.com/maps?q=${latNum},${lngNum}`;
                    }
                    if (gmapsDirBtn) {
                        gmapsDirBtn.href = `https://www.google.com/maps/dir/?api=1&destination=${latNum},${lngNum}`;
                    }

                    // Render / Update Leaflet Map
                    setTimeout(() => {
                        if (typeof L !== 'undefined') {
                            const mapContainer = document.getElementById('customer-modal-map');
                            if (mapContainer) {
                                if (!customerDetailMap) {
                                    customerDetailMap = L.map('customer-modal-map').setView([latNum, lngNum], 16);
                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        attribution: '&copy; OpenStreetMap contributors',
                                        maxZoom: 19
                                    }).addTo(customerDetailMap);
                                } else {
                                    customerDetailMap.setView([latNum, lngNum], 16);
                                }

                                if (customerDetailMarker) {
                                    customerDetailMarker.setLatLng([latNum, lngNum]);
                                } else {
                                    customerDetailMarker = L.marker([latNum, lngNum]).addTo(customerDetailMap);
                                }

                                const safeName = (c.name || 'Pelanggan').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                                const safeAddr = (c.address || '-').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                                const popupContent = `
                                    <div style="font-size: 12px; line-height: 1.4; padding: 2px;">
                                        <strong style="color: #0f172a; display: block; font-size: 13px; margin-bottom: 3px;">${safeName}</strong>
                                        <div style="color: #64748b; margin-bottom: 6px;">${safeAddr}</div>
                                        <div style="font-family: monospace; font-size: 11px; color: #475569; margin-bottom: 6px;">${latNum}, ${lngNum}</div>
                                        <a href="https://www.google.com/maps?q=${latNum},${lngNum}" target="_blank" style="color: #0284c7; font-weight: 600; text-decoration: underline;">
                                            Buka di Google Maps &rarr;
                                        </a>
                                    </div>
                                `;
                                customerDetailMarker.bindPopup(popupContent).openPopup();
                                customerDetailMap.invalidateSize();
                            }
                        }
                    }, 250);
                } else {
                    if (coordsWrapper) coordsWrapper.style.setProperty('display', 'none', 'important');
                    if (mapWrapper) mapWrapper.style.setProperty('display', 'none', 'important');
                    if (mapActionBtns) mapActionBtns.style.setProperty('display', 'none', 'important');
                    if (noCoordsAlert) noCoordsAlert.style.setProperty('display', 'flex', 'important');
                }

                if (modalBtnEditCust && c.edit_url) {
                    modalBtnEditCust.href = c.edit_url;
                    modalBtnEditCust.style.display = 'inline-flex';
                }

                if (modalContent) modalContent.style.display = 'block';
            } else {
                if (modalCustName) modalCustName.textContent = 'Perangkat Belum Terdaftar';
                if (notFoundMac) notFoundMac.textContent = mac;
                if (modalNotFound) modalNotFound.style.display = 'block';
            }
        } catch (err) {
            console.error('Gagal mengambil data pelanggan:', err);
            if (modalLoader) modalLoader.style.display = 'none';
            if (modalCustName) modalCustName.textContent = 'Terjadi Kesalahan';
            if (notFoundMac) notFoundMac.textContent = mac;
            if (modalNotFound) modalNotFound.style.display = 'block';
        }
    }

    // ==========================================
    // Event Listeners (Automatic Instant Search & Filter)
    // ==========================================
    function updateClearSearchVisibility() {
        if (!clearSearchBtn) return;
        if (searchInput && searchInput.value.trim().length > 0) {
            clearSearchBtn.style.display = 'inline-flex';
        } else {
            clearSearchBtn.style.display = 'none';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            updateClearSearchVisibility();
            applyFilters(null, true);
        });
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                this.value = '';
                updateClearSearchVisibility();
                applyFilters(null, true);
            }
        });
    }

    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function () {
            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
            }
            updateClearSearchVisibility();
            applyFilters(null, true);
        });
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', function () {
            applyFilters(null, true);
        });
    }

    if (statusSelect) {
        statusSelect.addEventListener('change', function () {
            applyFilters(null, true);
        });
    }

    if (pageSizeSelect) {
        pageSizeSelect.addEventListener('change', function () {
            pageSize = parseInt(this.value) || 10;
            currentPage = 1;
            renderCurrentPage();
        });
    }

    // Table Header Sorting Click Listener
    document.querySelectorAll('.sortable-th').forEach(th => {
        th.addEventListener('click', function () {
            const col = this.getAttribute('data-sort');
            if (!col) return;
            if (sortColumn === col) {
                sortDirection = (sortDirection === 'asc') ? 'desc' : 'asc';
            } else {
                sortColumn = col;
                sortDirection = 'asc';
            }
            updateSortIcons();
            applyFilters();
        });
    });

    // ==========================================
    // AJAX Live Sync / Refresh Action
    // ==========================================
    async function fetchLiveData(force = false) {
        if (refreshBtn) {
            refreshBtn.classList.add('loading');
            refreshBtn.disabled = true;
        }

        try {
            const url = `{{ route('mikrotik.dhcp.data') }}?force=${force ? 1 : 0}&t=${Date.now()}`;
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Network error');

            const data = await response.json();
            if (data.success) {
                if (lastSyncEl) lastSyncEl.textContent = data.last_sync;
                if (statTotal) statTotal.textContent = data.stats.total || 0;
                if (statBound) statBound.textContent = data.stats.bound || 0;
                if (statDynamic) statDynamic.textContent = data.stats.dynamic || 0;
                if (statWaiting) statWaiting.textContent = data.stats.waiting || 0;
                if (statStatic) statStatic.textContent = data.stats.static || 0;

                allLeases = data.leases || [];
                if (!Array.isArray(allLeases)) {
                    allLeases = Object.values(allLeases || {});
                }
                applyFilters();
            }
        } catch (err) {
            console.error('Gagal mengambil live sync data:', err);
        } finally {
            if (refreshBtn) {
                refreshBtn.classList.remove('loading');
                refreshBtn.disabled = false;
            }
        }
    }

    if (refreshBtn) {
        refreshBtn.addEventListener('click', function () {
            fetchLiveData(true);
            fetchSystemResource();
        });
    }

    // Initial render
    updateSortIcons();
    applyFilters();

    @can('monitoring traffic mikrotik')
    // ==========================================
    // Real-Time Bandwidth & Speed Traffic Monitor
    // ==========================================
    const selectInterface   = document.getElementById('select-traffic-interface');
    const btnTrafficToggle  = document.getElementById('btn-traffic-toggle');
    const trafficBadge      = document.getElementById('traffic-status-badge');
    const trafficBadgeText  = document.getElementById('traffic-status-text');
    const iconPause         = document.getElementById('icon-traffic-pause');
    const iconPlay          = document.getElementById('icon-traffic-play');
    const textTrafficToggle = document.getElementById('text-traffic-toggle');

    const valRxSpeed = document.getElementById('val-rx-speed');
    const valTxSpeed = document.getElementById('val-tx-speed');
    const valRxPeak  = document.getElementById('val-rx-peak');
    const valTxPeak  = document.getElementById('val-tx-peak');
    const valRxPps   = document.getElementById('val-rx-pps');
    const valTxPps   = document.getElementById('val-tx-pps');

    const maxDataPoints = 25;
    let rxData = [];
    let txData = [];
    let timeCategories = [];

    // Pre-fill initial timestamps
    const now = new Date();
    for (let i = maxDataPoints - 1; i >= 0; i--) {
        const d = new Date(now.getTime() - i * 2500);
        timeCategories.push(d.toTimeString().split(' ')[0]);
        rxData.push(0);
        txData.push(0);
    }

    let maxRxMbps = 0;
    let maxTxMbps = 0;
    let isTrafficStreaming = true;
    let trafficPollingTimer = null;
    let trafficChart = null;

    async function initTrafficChart() {
        if (typeof ApexCharts === 'undefined') {
            setTimeout(initTrafficChart, 300);
            return;
        }

        const chartEl = document.querySelector('#mikrotik-traffic-chart');
        if (!chartEl) return;

        const options = {
            series: [
                {
                    name: 'Download (Rx)',
                    data: rxData.slice()
                },
                {
                    name: 'Upload (Tx)',
                    data: txData.slice()
                }
            ],
            chart: {
                type: 'area',
                height: 280,
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 1000
                    }
                },
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                parentHeightOffset: 0
            },
            colors: ['#0284c7', '#8b5cf6'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                },
                padding: {
                    top: 10,
                    right: 15,
                    bottom: 0,
                    left: 10
                }
            },
            xaxis: {
                categories: timeCategories.slice(),
                range: maxDataPoints,
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '11px',
                        fontFamily: 'inherit'
                    },
                    rotate: 0,
                    showDuplicates: false
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return val.toFixed(2) + ' Mbps';
                    },
                    style: {
                        colors: '#64748b',
                        fontSize: '11px',
                        fontFamily: 'inherit'
                    }
                },
                min: 0,
                forceNiceScale: true
            },
            tooltip: {
                theme: 'light',
                x: {
                    show: true
                },
                y: {
                    formatter: function (val) {
                        return val.toFixed(2) + ' Mbps';
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontWeight: 600,
                fontSize: '12px',
                labels: {
                    colors: '#1e293b'
                },
                markers: {
                    width: 10,
                    height: 10,
                    radius: 12
                }
            }
        };

        trafficChart = new ApexCharts(chartEl, options);
        trafficChart.render();

        try {
            await loadInterfaces();
        } catch (err) {
            console.warn('Load interfaces error:', err);
        }
        startTrafficStream();
    }

    async function loadInterfaces() {
        if (!selectInterface) return;
        try {
            const url = `{{ route('mikrotik.dhcp.interfaces') }}?t=${Date.now()}`;
            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!res.ok) return;
            const data = await res.json();
            if (data.success && Array.isArray(data.interfaces) && data.interfaces.length > 0) {
                const currentVal = selectInterface.value;
                selectInterface.innerHTML = '';
                let hasSelected = false;

                data.interfaces.forEach((iface) => {
                    const opt = document.createElement('option');
                    opt.value = iface.name;
                    opt.textContent = iface.comment ? `${iface.name} (${iface.comment})` : iface.name;
                    
                    const lowerName = iface.name.toLowerCase();
                    const isPreferred = lowerName.includes('isp') || lowerName.includes('ether1') || lowerName.includes('inet');
                    
                    if (currentVal === iface.name || (!hasSelected && isPreferred)) {
                        opt.selected = true;
                        hasSelected = true;
                    }
                    selectInterface.appendChild(opt);
                });

                if (!hasSelected && selectInterface.options.length > 0) {
                    selectInterface.options[0].selected = true;
                }
            }
        } catch (e) {
            console.warn('Gagal memuat list interface:', e);
        }
    }

    async function pollTrafficData() {
        if (!isTrafficStreaming) return;

        const iface = selectInterface ? selectInterface.value : '';
        if (!iface) return;

        try {
            const url = `{{ route('mikrotik.dhcp.traffic') }}?interface=${encodeURIComponent(iface)}&t=${Date.now()}`;
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) return;

            const data = await response.json();
            if (!data.success) return;

            // Update Metric Cards
            if (valRxSpeed) valRxSpeed.textContent = data.rx_formatted;
            if (valTxSpeed) valTxSpeed.textContent = data.tx_formatted;
            if (valRxPps) valRxPps.textContent = Number(data.rx_pps || 0).toLocaleString('id-ID');
            if (valTxPps) valTxPps.textContent = Number(data.tx_pps || 0).toLocaleString('id-ID');

            if (data.rx_mbps > maxRxMbps) {
                maxRxMbps = data.rx_mbps;
                if (valRxPeak) valRxPeak.textContent = data.rx_formatted;
            }
            if (data.tx_mbps > maxTxMbps) {
                maxTxMbps = data.tx_mbps;
                if (valTxPeak) valTxPeak.textContent = data.tx_formatted;
            }

            // Push to rolling arrays
            const timeLabel = data.timestamp || new Date().toTimeString().split(' ')[0];
            timeCategories.shift();
            timeCategories.push(timeLabel);

            rxData.shift();
            rxData.push(data.rx_mbps);

            txData.shift();
            txData.push(data.tx_mbps);

            if (trafficChart) {
                trafficChart.updateSeries([
                    {
                        name: 'Download (Rx)',
                        data: rxData
                    },
                    {
                        name: 'Upload (Tx)',
                        data: txData
                    }
                ]);
                trafficChart.updateOptions({
                    xaxis: {
                        categories: timeCategories
                    }
                });
            }
        } catch (err) {
            console.warn('Traffic poll issue:', err);
        }
    }

    function startTrafficStream() {
        if (trafficPollingTimer) clearInterval(trafficPollingTimer);
        pollTrafficData();
        trafficPollingTimer = setInterval(pollTrafficData, 5000);
    }

    function stopTrafficStream() {
        if (trafficPollingTimer) {
            clearInterval(trafficPollingTimer);
            trafficPollingTimer = null;
        }
    }

    if (btnTrafficToggle) {
        btnTrafficToggle.addEventListener('click', function () {
            isTrafficStreaming = !isTrafficStreaming;

            if (isTrafficStreaming) {
                startTrafficStream();
                trafficBadge.className = 'traffic-status-badge live';
                trafficBadgeText.textContent = 'LIVE STREAMING';
                iconPause.classList.remove('d-none');
                iconPlay.classList.add('d-none');
                textTrafficToggle.textContent = 'Pause';
            } else {
                stopTrafficStream();
                trafficBadge.className = 'traffic-status-badge paused';
                trafficBadgeText.textContent = 'PAUSED';
                iconPause.classList.add('d-none');
                iconPlay.classList.remove('d-none');
                textTrafficToggle.textContent = 'Resume';
            }
        });
    }

    if (selectInterface) {
        selectInterface.addEventListener('change', function () {
            maxRxMbps = 0;
            maxTxMbps = 0;
            if (valRxPeak) valRxPeak.textContent = '0.00 Mbps';
            if (valTxPeak) valTxPeak.textContent = '0.00 Mbps';

            rxData = new Array(maxDataPoints).fill(0);
            txData = new Array(maxDataPoints).fill(0);

            if (trafficChart) {
                trafficChart.updateSeries([
                    { name: 'Download (Rx)', data: rxData },
                    { name: 'Upload (Tx)', data: txData }
                ]);
            }

            if (isTrafficStreaming) {
                pollTrafficData();
            }
        });
    }

    // Initialize traffic chart
    initTrafficChart();
    @endcan

    // ==========================================
    // AJAX System Resource Live Updater
    // ==========================================
    const resRouterName   = document.getElementById('res-router-name');
    const resBoardName    = document.getElementById('res-board-name');
    const resArch         = document.getElementById('res-arch');
    const resVersion      = document.getElementById('res-version');
    const resUptime       = document.getElementById('res-uptime');
    const resCpuPct       = document.getElementById('res-cpu-pct');
    const resCpuBar       = document.getElementById('res-cpu-bar');
    const resCpuName      = document.getElementById('res-cpu-name');
    const resCpuCount     = document.getElementById('res-cpu-count');
    const resCpuFreq      = document.getElementById('res-cpu-freq');
    const resMemPct       = document.getElementById('res-mem-pct');
    const resMemBar       = document.getElementById('res-mem-bar');
    const resMemFormatted = document.getElementById('res-mem-formatted');
    const resHddPct       = document.getElementById('res-hdd-pct');
    const resHddBar       = document.getElementById('res-hdd-bar');
    const resHddFormatted = document.getElementById('res-hdd-formatted');

    async function fetchSystemResource() {
        try {
            const url = `{{ route('mikrotik.dhcp.resource') }}?t=${Date.now()}`;
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) return;
            const data = await response.json();
            if (!data.success) return;

            if (resRouterName) resRouterName.textContent = data.router_name || 'MikroTik Router';
            if (resBoardName) resBoardName.textContent = data.board_name || 'MikroTik';
            if (resArch) resArch.textContent = data.architecture_name || '-';
            if (resVersion) resVersion.textContent = data.version || '-';
            if (resUptime) resUptime.textContent = data.uptime || '-';

            // CPU
            if (resCpuPct) resCpuPct.textContent = (data.cpu_load || 0) + '%';
            if (resCpuBar) {
                const load = data.cpu_load || 0;
                resCpuBar.style.width = load + '%';
                resCpuBar.className = 'resource-progress-fill fill-cpu ' + (load > 80 ? 'danger' : (load > 50 ? 'warn' : ''));
            }
            if (resCpuName) resCpuName.textContent = data.cpu || 'CPU';
            if (resCpuCount) resCpuCount.textContent = (data.cpu_count || 1) + ' Core';
            if (resCpuFreq) resCpuFreq.textContent = data.cpu_frequency || '-';

            // RAM
            if (resMemPct) resMemPct.textContent = (data.memory_percent || 0) + '%';
            if (resMemBar) resMemBar.style.width = (data.memory_percent || 0) + '%';
            if (resMemFormatted) resMemFormatted.textContent = data.memory_formatted || '-';

            // HDD
            if (resHddPct) resHddPct.textContent = (data.hdd_percent || 0) + '%';
            if (resHddBar) resHddBar.style.width = (data.hdd_percent || 0) + '%';
            if (resHddFormatted) resHddFormatted.textContent = data.hdd_formatted || '-';
        } catch (err) {
            console.warn('System resource fetch error:', err);
        }
    }

    let systemResourceTimer = null;
    function startSystemResourceTimer() {
        if (systemResourceTimer) clearInterval(systemResourceTimer);
        systemResourceTimer = setInterval(fetchSystemResource, 20000);
    }
    function stopSystemResourceTimer() {
        if (systemResourceTimer) {
            clearInterval(systemResourceTimer);
            systemResourceTimer = null;
        }
    }

    // Refresh System Resource periodically every 20s
    startSystemResourceTimer();

    @can('reboot mikrotik')
    // ==========================================
    // Reboot Router Modal & Action Handler
    // ==========================================
    const btnRebootHub           = document.getElementById('btn-reboot-hub');
    const modalConfirmRebootEl   = document.getElementById('modal-confirm-reboot');
    const modalRebootCountdownEl = document.getElementById('modal-reboot-countdown');
    const btnExecuteReboot       = document.getElementById('btn-execute-reboot');
    const countdownTimerEl       = document.getElementById('reboot-countdown-timer');
    const reconnectStatusEl      = document.getElementById('reboot-reconnect-status');

    let confirmRebootModal   = null;
    let countdownRebootModal = null;

    if (modalConfirmRebootEl && typeof bootstrap !== 'undefined') {
        confirmRebootModal = new bootstrap.Modal(modalConfirmRebootEl);
    }
    if (modalRebootCountdownEl && typeof bootstrap !== 'undefined') {
        countdownRebootModal = new bootstrap.Modal(modalRebootCountdownEl);
    }

    if (btnRebootHub) {
        btnRebootHub.addEventListener('click', function () {
            if (confirmRebootModal) {
                confirmRebootModal.show();
            }
        });
    }

    if (btnExecuteReboot) {
        btnExecuteReboot.addEventListener('click', async function () {
            if (confirmRebootModal) {
                confirmRebootModal.hide();
            }
            if (countdownRebootModal) {
                countdownRebootModal.show();
            }

            // Stop traffic streaming during reboot
            if (typeof stopTrafficStream === 'function') {
                stopTrafficStream();
            }

            // Send POST request to trigger reboot
            try {
                await fetch(`{{ route('mikrotik.dhcp.reboot') }}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
            } catch (e) {
                // Connection drops as router starts reboot
                console.log('Reboot initiated:', e);
            }

            // Countdown 60s with auto-reconnection check
            let remaining = 60;
            if (countdownTimerEl) countdownTimerEl.textContent = remaining + 's';

            const countdownInterval = setInterval(async function () {
                remaining--;
                if (countdownTimerEl) countdownTimerEl.textContent = remaining + 's';

                // After 25s, start checking if router is back online
                if (remaining <= 35 && remaining % 4 === 0) {
                    if (reconnectStatusEl) reconnectStatusEl.textContent = 'Memeriksa status koneksi router...';
                    try {
                        const check = await fetch(`{{ route('mikrotik.dhcp.resource') }}?t=${Date.now()}`);
                        if (check.ok) {
                            const resData = await check.json();
                            if (resData.success) {
                                clearInterval(countdownInterval);
                                if (reconnectStatusEl) reconnectStatusEl.innerHTML = '<span class="text-success fw-bold">Router telah online kembali! Memuat halaman...</span>';
                                setTimeout(() => window.location.reload(), 1500);
                                return;
                            }
                        }
                    } catch (err) {
                        // Still rebooting
                    }
                }

                if (remaining <= 0) {
                    clearInterval(countdownInterval);
                    if (reconnectStatusEl) reconnectStatusEl.innerHTML = '<span class="text-success fw-bold">Waktu tunggu selesai. Memuat ulang halaman...</span>';
                    setTimeout(() => window.location.reload(), 1500);
                }
            }, 1000);
        });
    }
    @endcan

    // ==========================================
    // Real-Time Server-Sent Events (SSE) Delta Stream (3s Interval)
    // ==========================================
    let sseSource = null;
    let sseReconnectTimer = null;
    let lastTickTimestamp = Date.now();
    const tableStreamBar = document.getElementById('table-live-stream-bar');
    const tableCard = document.getElementById('hub-table-card');
    const liveSyncTimerEl = document.getElementById('live-sync-timer-text');

    // Simpan nilai statistik saat ini untuk mendeteksi kenaikan / penurunan angka
    let currentStats = {
        total: parseInt((statTotal ? statTotal.textContent : 0).toString().replace(/[^0-9]/g, '')) || 0,
        bound: parseInt((statBound ? statBound.textContent : 0).toString().replace(/[^0-9]/g, '')) || 0,
        dynamic: parseInt((statDynamic ? statDynamic.textContent : 0).toString().replace(/[^0-9]/g, '')) || 0,
        waiting: parseInt((statWaiting ? statWaiting.textContent : 0).toString().replace(/[^0-9]/g, '')) || 0,
        static: parseInt((statStatic ? statStatic.textContent : 0).toString().replace(/[^0-9]/g, '')) || 0,
    };

    // Ticker 1 Detik untuk memperbarui indikator waktu: (Baru saja) -> (1s lalu) -> (3s lalu)
    setInterval(() => {
        if (!liveSyncTimerEl) return;
        const elapsedSec = Math.floor((Date.now() - lastTickTimestamp) / 1000);
        if (elapsedSec <= 1) {
            liveSyncTimerEl.textContent = '(Baru saja)';
        } else {
            liveSyncTimerEl.textContent = `(${elapsedSec}s lalu)`;
        }
    }, 1000);

    // Animasi Pergeseran Angka Halus (Number Roll / Count-Up Transition)
    function animateNumber(element, startVal, endVal, duration = 600) {
        if (!element) return;
        if (startVal === endVal) {
            element.textContent = endVal.toLocaleString('id-ID');
            return;
        }

        const startTime = performance.now();
        function update(now) {
            const progress = Math.min((now - startTime) / duration, 1);
            // Easing quadratic out
            const easeProgress = 1 - (1 - progress) * (1 - progress);
            const current = Math.round(startVal + (endVal - startVal) * easeProgress);
            element.textContent = current.toLocaleString('id-ID');

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                element.textContent = endVal.toLocaleString('id-ID');
            }
        }
        requestAnimationFrame(update);
    }

    // Tampilkan Animasi Flash Kartu & Badge Selisih Mengambang (+N / -N)
    function triggerCardStatFeedback(cardId, badgeId, valueEl, oldVal, newVal) {
        if (oldVal === newVal) return;

        const diff = newVal - oldVal;
        const cardEl = document.getElementById(cardId);
        const badgeEl = document.getElementById(badgeId);

        // 1. Jalankan animasi kilatan warna kartu
        if (cardEl) {
            cardEl.classList.remove('card-flash-plus', 'card-flash-minus');
            void cardEl.offsetWidth; // Force reflow
            if (diff > 0) {
                cardEl.classList.add('card-flash-plus');
            } else {
                cardEl.classList.add('card-flash-minus');
            }
            setTimeout(() => {
                if (cardEl) cardEl.classList.remove('card-flash-plus', 'card-flash-minus');
            }, 1800);
        }

        // 2. Tampilkan badge mengambang (+N atau -N)
        if (badgeEl) {
            badgeEl.className = 'stat-diff-badge ' + (diff > 0 ? 'diff-plus' : 'diff-minus');
            badgeEl.textContent = diff > 0 ? `+${diff}` : `${diff}`;
            badgeEl.style.display = 'inline-flex';

            // Sembunyikan badge setelah animasinya selesai
            setTimeout(() => {
                if (badgeEl) badgeEl.style.display = 'none';
            }, 2350);
        }

        // 3. Animasi pergeseran angka dari oldVal ke newVal
        animateNumber(valueEl, oldVal, newVal, 650);
    }

    function updateStatCards(stats) {
        if (!stats) return;

        const newTotal = parseInt(stats.total !== undefined ? stats.total : currentStats.total) || 0;
        const newBound = parseInt(stats.bound !== undefined ? stats.bound : currentStats.bound) || 0;
        const newDynamic = parseInt(stats.dynamic !== undefined ? stats.dynamic : currentStats.dynamic) || 0;
        const newWaiting = parseInt(stats.waiting !== undefined ? stats.waiting : currentStats.waiting) || 0;
        const newStatic = parseInt(stats.static !== undefined ? stats.static : currentStats.static) || 0;

        triggerCardStatFeedback('card-stat-total', 'badge-diff-total', statTotal, currentStats.total, newTotal);
        triggerCardStatFeedback('card-stat-bound', 'badge-diff-bound', statBound, currentStats.bound, newBound);
        triggerCardStatFeedback('card-stat-dynamic', 'badge-diff-dynamic', statDynamic, currentStats.dynamic, newDynamic);
        triggerCardStatFeedback('card-stat-waiting', 'badge-diff-waiting', statWaiting, currentStats.waiting, newWaiting);
        triggerCardStatFeedback('card-stat-static', 'badge-diff-static', statStatic, currentStats.static, newStatic);

        currentStats = {
            total: newTotal,
            bound: newBound,
            dynamic: newDynamic,
            waiting: newWaiting,
            static: newStatic,
        };
    }

    function triggerTableSyncAnimation(hasUpdates = false) {
        if (tableStreamBar) {
            tableStreamBar.classList.remove('animating');
            void tableStreamBar.offsetWidth; // Trigger DOM reflow to restart animation
            tableStreamBar.classList.add('animating');
        }

        if (tableCard && hasUpdates) {
            tableCard.classList.add('stream-syncing');
            setTimeout(() => {
                if (tableCard) tableCard.classList.remove('stream-syncing');
            }, 1200);
        }
    }

    function initSseStream() {
        if (typeof EventSource === 'undefined') {
            console.warn('Browser tidak mendukung Server-Sent Events (SSE).');
            return;
        }

        if (sseSource) {
            sseSource.close();
        }

        const streamUrl = `{{ route('mikrotik.dhcp.stream') }}`;
        sseSource = new EventSource(streamUrl);

        sseSource.addEventListener('connected', function (e) {
            console.log('SSE Stream Connected (3s Live Stream):', e.data);
            lastTickTimestamp = Date.now();
            const liveBadge = document.getElementById('live-sync-status-badge');
            if (liveBadge) {
                liveBadge.title = 'Real-Time Streaming SSE 3 Detik Aktif';
            }
        });

        // Event Tick: Diterima setiap 3 detik tanda stream aktif & router tersambung
        sseSource.addEventListener('tick', function (e) {
            try {
                lastTickTimestamp = Date.now();
                const tickData = JSON.parse(e.data);
                triggerTableSyncAnimation(tickData.has_updates || false);
                if (lastSyncEl && tickData.time) {
                    lastSyncEl.textContent = tickData.time;
                }
                if (tickData.stats) {
                    updateStatCards(tickData.stats);
                }
            } catch (err) {
                triggerTableSyncAnimation(false);
            }
        });

        // Event Delta: Diterima jika ada perangkat baru atau status/IP/expires berubah
        sseSource.addEventListener('delta', function (e) {
            try {
                lastTickTimestamp = Date.now();
                const data = JSON.parse(e.data);
                if (!data || !data.updated || data.updated.length === 0) return;

                if (lastSyncEl && data.last_check) {
                    lastSyncEl.textContent = data.last_check;
                }

                // Update Stat Cards jika tersedia
                if (data.stats) {
                    updateStatCards(data.stats);
                }

                // Update data lokal allLeases (in-memory patching tanpa reload berat)
                const updatedMacs = new Set();
                data.updated.forEach(item => {
                    const mac = (item.mac_address || '').toUpperCase();
                    if (!mac) return;
                    updatedMacs.add(mac);

                    const idx = allLeases.findIndex(l => (l.mac_address || '').toUpperCase() === mac);
                    if (idx !== -1) {
                        allLeases[idx] = Object.assign({}, allLeases[idx], item);
                    } else {
                        allLeases.push(item);
                    }
                });

                // Terapkan filter dan beri animasi highlight hijau pada baris yang berubah
                applyFilters(updatedMacs);
            } catch (err) {
                console.error('Gagal memproses SSE delta:', err);
            }
        });

        sseSource.onerror = function (err) {
            if (sseSource) {
                sseSource.close();
                sseSource = null;
            }
            if (!sseReconnectTimer && !document.hidden) {
                sseReconnectTimer = setTimeout(() => {
                    sseReconnectTimer = null;
                    if (!document.hidden) {
                        initSseStream();
                    }
                }, 4000);
            }
        };
    }

    function stopSseStream() {
        if (sseSource) {
            sseSource.close();
            sseSource = null;
        }
        if (sseReconnectTimer) {
            clearTimeout(sseReconnectTimer);
            sseReconnectTimer = null;
        }
    }

    // Inisialisasi SSE Stream
    initSseStream();

    // ==========================================
    // Auto-Pause saat Tab Browser Tidak Aktif (Page Visibility API)
    // Menghemat hingga 90% resource hosting jika user berganti tab / minimize browser
    // ==========================================
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) {
            @can('monitoring traffic mikrotik')
            stopTrafficStream();
            @endcan
            stopSystemResourceTimer();
            stopSseStream();
        } else {
            @can('monitoring traffic mikrotik')
            if (isTrafficStreaming) {
                startTrafficStream();
            }
            @endcan
            fetchSystemResource();
            startSystemResourceTimer();
            initSseStream();
        }
    });
});
</script>
@endpush

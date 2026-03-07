@extends('layouts.app')

@section('title', 'Dashboard')

@push('css')
    <link href="{{ asset('css/modern-layout.css') }}" rel="stylesheet">
    <style>
        /* ── WELCOME BANNER ── */
        .dash-welcome {
            background: linear-gradient(135deg, #1e1b4b 0%, #4c1d95 50%, #6d28d9 100%);
            border-radius: 20px;
            padding: 2rem 2.5rem;
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .dash-welcome::before {
            content: '';
            position: absolute;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.3) 0%, transparent 70%);
            top: -100px;
            right: -60px;
        }

        .dash-welcome::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, transparent 70%);
            bottom: -60px;
            left: 30%;
        }

        .dash-welcome-inner {
            position: relative;
            z-index: 2;
        }

        .dash-welcome h2 {
            font-size: 1.6rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.3rem;
        }

        .dash-welcome p {
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
            font-size: 0.9rem;
        }

        .dash-welcome-time {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 0.5rem 1rem;
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 1rem;
        }

        /* ── METRIC CARDS ── */
        .metric-card {
            background: white;
            border-radius: 16px;
            padding: 1.4rem 1.5rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            gap: 1.1rem;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: default;
            height: 100%;
        }

        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .metric-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .metric-icon.purple {
            background: linear-gradient(135deg, #ede9fe, #ddd6fe);
            color: #7c3aed;
        }

        .metric-icon.blue {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #2563eb;
        }

        .metric-icon.green {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #16a34a;
        }

        .metric-icon.amber {
            background: linear-gradient(135deg, #fef9c3, #fde68a);
            color: #d97706;
        }

        .metric-val {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .metric-lbl {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 3px;
            font-weight: 500;
        }

        .metric-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            margin-top: 5px;
            display: inline-block;
        }

        .metric-badge.success {
            background: #dcfce7;
            color: #16a34a;
        }

        .metric-badge.warn {
            background: #fef9c3;
            color: #b45309;
        }

        .metric-badge.danger {
            background: #fee2e2;
            color: #dc2626;
        }

        /* ── SECTION CARDS ── */
        .dash-card {
            background: white;
            border-radius: 18px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .dash-card-header {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dash-card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }

        .dash-card-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dash-card-body {
            padding: 1.2rem 1.5rem;
        }

        /* ── STOCK TABLE ── */
        .stock-table {
            width: 100%;
            border-collapse: collapse;
        }

        .stock-table th {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
            padding: 0.6rem 0.8rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .stock-table td {
            padding: 0.7rem 0.8rem;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .stock-table tr:not(:last-child) td {
            border-bottom: 1px solid #f8fafc;
        }

        .stock-table tr:hover td {
            background: #f8fafc;
        }

        .stock-avatar {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            flex-shrink: 0;
        }

        /* ── FILTER SECTION ── */
        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.5rem 1.1rem;
            border-radius: 30px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 0.82rem;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .filter-pill:hover,
        .filter-pill.active {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            border-color: #7c3aed;
            color: white;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        /* ── DATA CARDS (customer count) ── */
        .customer-count-card {
            background: white;
            border-radius: 14px;
            border: 1.5px solid #f1f5f9;
            padding: 1rem 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .customer-count-card:hover {
            transform: translateY(-3px);
            border-color: #c4b5fd;
            box-shadow: 0 8px 24px rgba(124, 58, 237, 0.12);
        }

        .ccc-left {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .ccc-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ccc-name {
            font-size: 0.88rem;
            font-weight: 700;
            color: #0f172a;
        }

        .ccc-count {
            font-size: 0.78rem;
            color: #64748b;
            margin-top: 2px;
        }

        .ccc-arrow {
            color: #cbd5e1;
        }

        /* ── EMPTY STATE ── */
        .dash-empty {
            padding: 2.5rem;
            text-align: center;
        }

        .dash-empty-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: #94a3b8;
        }

        .dash-empty h6 {
            font-weight: 700;
            color: #374151;
            margin-bottom: 0.3rem;
        }

        .dash-empty p {
            font-size: 0.82rem;
            color: #94a3b8;
            margin: 0;
        }

        /* ── PAGE LINKS ── */
        .page-link-card {
            background: white;
            border-radius: 14px;
            border: 1.5px solid #f1f5f9;
            padding: 1rem 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            text-decoration: none;
            height: 100%;
            transition: all 0.2s;
        }

        .page-link-card:hover {
            border-color: #a78bfa;
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.1);
            transform: translateY(-2px);
        }

        .plc-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .plc-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0f172a;
        }

        .plc-info {
            font-size: 0.72rem;
            color: #94a3b8;
            margin-top: 2px;
            line-height: 1.4;
        }

        /* color cycles */
        .c0 {
            background: linear-gradient(135deg, #ede9fe, #ddd6fe);
            color: #7c3aed;
        }

        .c1 {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #2563eb;
        }

        .c2 {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #16a34a;
        }

        .c3 {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
        }

        .c4 {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
        }

        .c5 {
            background: linear-gradient(135deg, #f0f9ff, #bae6fd);
            color: #0284c7;
        }

        .c6 {
            background: linear-gradient(135deg, #f0fdf4, #bbf7d0);
            color: #15803d;
        }

        .c7 {
            background: linear-gradient(135deg, #fdf4ff, #e9d5ff);
            color: #9333ea;
        }

        /* ─ result badge ─ */
        .result-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #ede9fe, #ddd6fe);
            color: #7c3aed;
            border-radius: 20px;
            padding: 0.4rem 1rem;
            font-size: 0.82rem;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    @php
        $cycleClasses = ['c0', 'c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7'];
        $currentHour = now()->setTimezone('Asia/Jakarta')->hour;
        $greeting =
            $currentHour < 11
                ? 'Selamat Pagi'
                : ($currentHour < 15
                    ? 'Selamat Siang'
                    : ($currentHour < 18
                        ? 'Selamat Sore'
                        : 'Selamat Malam'));
    @endphp

    {{-- ── WELCOME BANNER ── --}}
    <div class="dash-welcome">
        <div class="dash-welcome-inner d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <p
                    style="color:rgba(255,255,255,0.6);font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px;">
                    {{ $greeting }}, 👋
                </p>
                <h2>{{ Auth::user()->name }}</h2>
                <div
                    style="margin-bottom: 1rem; margin-top: 0.2rem; display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">
                    <span
                        style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; backdrop-filter: blur(4px);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        </svg>
                        {{ Auth::user()->getRoleNames()->first() ?? 'Tidak Ada Role' }}
                    </span>
                    <span
                        style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; backdrop-filter: blur(4px);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 21h18" />
                            <path d="M5 21v-14l8-4v18" />
                            <path d="M19 21v-10l-6-4" />
                            <path d="M9 9h.01" />
                            <path d="M9 12h.01" />
                            <path d="M9 15h.01" />
                            <path d="M9 18h.01" />
                        </svg>
                        {{ Auth::user()->organization->name ?? 'Admin / Tanpa Organisasi' }}
                    </span>
                </div>
                <p>Selamat datang kembali di <strong style="color:white;">{{ config('app.name') }}</strong>. Pantau data
                    jaringan Anda dari sini.</p>
                <div class="dash-welcome-time">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    {{ now()->setTimezone('Asia/Jakarta')->isoFormat('dddd, D MMMM YYYY') }}
                </div>
            </div>
            <div class="d-flex gap-3 flex-wrap">
                <div
                    style="background:rgba(255,255,255,0.1);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.15);border-radius:14px;padding:1rem 1.5rem;text-align:center;min-width:90px;">
                    <div style="font-size:1.5rem;font-weight:800;color:white;">{{ $userRouter->count() }}</div>
                    <div style="font-size:.7rem;color:rgba(255,255,255,0.6);text-transform:uppercase;letter-spacing:.05em;">
                        Router</div>
                </div>
                <div
                    style="background:rgba(255,255,255,0.1);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.15);border-radius:14px;padding:1rem 1.5rem;text-align:center;min-width:90px;">
                    <div style="font-size:1.5rem;font-weight:800;color:white;">{{ $userPatchCore->count() }}</div>
                    <div style="font-size:.7rem;color:rgba(255,255,255,0.6);text-transform:uppercase;letter-spacing:.05em;">
                        Patch Core</div>
                </div>
                <div
                    style="background:rgba(255,255,255,0.1);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.15);border-radius:14px;padding:1rem 1.5rem;text-align:center;min-width:90px;">
                    <div style="font-size:1.5rem;font-weight:800;color:white;">{{ $userPages->count() }}</div>
                    <div style="font-size:.7rem;color:rgba(255,255,255,0.6);text-transform:uppercase;letter-spacing:.05em;">
                        Halaman</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── ROUTER STOCK ── --}}
        <div class="col-lg-6">
            <div class="dash-card">
                <div class="dash-card-header">
                    <div class="dash-card-title">
                        <div class="dash-card-icon" style="background:linear-gradient(135deg,#ede9fe,#ddd6fe);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 13m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                <path d="M17 17l0 .01" />
                                <path d="M13 17l0 .01" />
                                <path d="M15 13l0 -2" />
                                <path d="M11.75 8.75a4 4 0 0 1 6.5 0" />
                                <path d="M8.5 6.5a8 8 0 0 1 13 0" />
                            </svg>
                        </div>
                        Stok Router Tersedia
                    </div>
                    <span
                        style="background:#ede9fe;color:#7c3aed;font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:20px;">
                        {{ $userRouter->count() }} Item
                    </span>
                </div>

                @if ($userRouter->count() > 0)
                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Router</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userRouter as $rtr)
                                @php $cls = $cycleClasses[$loop->index % count($cycleClasses)]; @endphp
                                <tr>
                                    <td style="color:#94a3b8;font-size:.8rem;">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="stock-avatar {{ $cls }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M3 13m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                                    <path d="M17 17l0 .01" />
                                                    <path d="M13 17l0 .01" />
                                                    <path d="M15 13l0 -2" />
                                                </svg>
                                            </span>
                                            <span class="fw-600"
                                                style="font-size:.875rem;color:#0f172a;">{{ $rtr->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            style="background:#f1f5f9;color:#475569;font-size:.78rem;font-weight:600;padding:2px 10px;border-radius:20px;">
                                            {{ $rtr->pivot->total }} Unit
                                        </span>
                                    </td>
                                    <td>
                                        @if ($rtr->pivot->total > 10)
                                            <span class="metric-badge success">Tersedia</span>
                                        @elseif($rtr->pivot->total > 0)
                                            <span class="metric-badge warn">Terbatas</span>
                                        @else
                                            <span class="metric-badge danger">Habis</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="dash-empty">
                        <div class="dash-empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 13m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                            </svg>
                        </div>
                        <h6>Tidak ada stok router</h6>
                        <p>Silakan minta admin untuk menambahkan stok router</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── PATCH CORE STOCK ── --}}
        <div class="col-lg-6">
            <div class="dash-card">
                <div class="dash-card-header">
                    <div class="dash-card-title">
                        <div class="dash-card-icon" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                <path d="M9 12h6" />
                                <path d="M12 9v6" />
                            </svg>
                        </div>
                        Stok Patch Core Tersedia
                    </div>
                    <span
                        style="background:#dbeafe;color:#2563eb;font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:20px;">
                        {{ $userPatchCore->count() }} Item
                    </span>
                </div>

                @if ($userPatchCore->count() > 0)
                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Patch Core</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userPatchCore as $ptc)
                                @php $cls = $cycleClasses[$loop->index % count($cycleClasses)]; @endphp
                                <tr>
                                    <td style="color:#94a3b8;font-size:.8rem;">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="stock-avatar {{ $cls }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                                    <path d="M9 12h6" />
                                                    <path d="M12 9v6" />
                                                </svg>
                                            </span>
                                            <span
                                                style="font-size:.875rem;color:#0f172a;font-weight:500;">{{ $ptc->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            style="background:#f1f5f9;color:#475569;font-size:.78rem;font-weight:600;padding:2px 10px;border-radius:20px;">
                                            {{ $ptc->pivot->total }} Unit
                                        </span>
                                    </td>
                                    <td>
                                        @if ($ptc->pivot->total > 10)
                                            <span class="metric-badge success">Tersedia</span>
                                        @elseif($ptc->pivot->total > 0)
                                            <span class="metric-badge warn">Terbatas</span>
                                        @else
                                            <span class="metric-badge danger">Habis</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="dash-empty">
                        <div class="dash-empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                            </svg>
                        </div>
                        <h6>Tidak ada stok patch core</h6>
                        <p>Silakan minta admin untuk menambahkan stok patch core</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── PAGE ACCESS ── --}}
        <div class="col-12">
            <div class="dash-card">
                <div class="dash-card-header">
                    <div class="dash-card-title">
                        <div class="dash-card-icon" style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                <path d="M9 17h6" />
                                <path d="M9 13h6" />
                            </svg>
                        </div>
                        Halaman Dapat Diakses — <span style="color:#16a34a;">{{ Auth::user()->name }}</span>
                    </div>
                    <span
                        style="background:#dcfce7;color:#16a34a;font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:20px;">
                        {{ $userPages->count() }} Halaman
                    </span>
                </div>

                @if ($userPages->count() > 0)
                    <div class="dash-card-body">
                        <div class="row g-3">
                            @foreach ($userPages as $upg)
                                @php $cls = $cycleClasses[$loop->index % count($cycleClasses)]; @endphp
                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <a href="{{ route('input.data.index', ['slug' => $upg->slug]) }}" target="_blank"
                                        class="page-link-card">
                                        <div class="plc-icon {{ $cls }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                <path
                                                    d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                            </svg>
                                        </div>
                                        <div style="overflow:hidden;flex:1;">
                                            <div class="plc-name text-truncate">{{ $upg->name }}</div>
                                            <div class="plc-info">
                                                @if ($upg->regencie)
                                                    <span>📍 {{ $upg->regencie->name }}</span><br>
                                                @endif
                                                @if ($upg->district)
                                                    <span>🏛️ {{ $upg->district->name }}</span><br>
                                                @endif
                                                @if ($upg->vlan->count() > 0)
                                                    <span>📡
                                                        {{ $upg->vlan->pluck('vlan.name')->filter()->join(', ') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="9 18 15 12 9 6" />
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="dash-empty">
                        <div class="dash-empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            </svg>
                        </div>
                        <h6>Tidak ada halaman yang dapat diakses</h6>
                        <p>Silakan minta admin untuk memberikan akses halaman</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── FILTER CUSTOMER ── --}}
        <div class="col-12">
            <div class="dash-card">
                <div class="dash-card-header">
                    <div class="dash-card-title">
                        <div class="dash-card-icon" style="background:linear-gradient(135deg,#fef3c7,#fde68a);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                            </svg>
                        </div>
                        Data Customer per Kategori
                    </div>
                </div>
                <div class="dash-card-body">
                    <p class="text-muted mb-3" style="font-size:.85rem;">Pilih kategori di bawah untuk melihat jumlah
                        pelanggan berdasarkan wilayah / jaringan.</p>
                    <form action="" method="GET">
                        <div class="d-flex flex-wrap gap-2">
                            @php
                                $filterOptions = [
                                    'kabupaten' => ['label' => 'Kab / Kota', 'emoji' => '🏙️'],
                                    'kecamatan' => ['label' => 'Kecamatan', 'emoji' => '🏛️'],
                                    'desa' => ['label' => 'Desa', 'emoji' => '🏘️'],
                                    'kampung' => ['label' => 'Kampung', 'emoji' => '🏡'],
                                    'vlan' => ['label' => 'VLAN', 'emoji' => '📡'],
                                    'olt' => ['label' => 'OLT', 'emoji' => '🔌'],
                                    'voucher & ppoe' => ['label' => 'Voucher & PPOE', 'emoji' => '📶'],
                                ];
                            @endphp
                            @foreach ($filterOptions as $val => $opt)
                                <a href="?filter={{ urlencode($val) }}"
                                    class="filter-pill {{ request('filter') === $val ? 'active' : '' }}">
                                    {{ $opt['emoji'] }} {{ $opt['label'] }}
                                </a>
                            @endforeach
                            @if (request('filter'))
                                <a href="{{ route('dashboard') }}" class="filter-pill"
                                    style="border-color:#fecaca;color:#dc2626;">
                                    ✕ Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── RESULT ── --}}
        @if (request('filter'))
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="result-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                        </svg>
                        Hasil: {{ ucfirst(str_replace('_', ' ', request('filter'))) }}
                    </span>
                    <span style="font-size:.82rem;color:#64748b;">
                        Total: <strong style="color:#0f172a;">{{ $data->count() }}</strong> data
                    </span>
                </div>

                <div class="row g-3">
                    @forelse ($data as $dt)
                        @php $cls = $cycleClasses[$loop->index % count($cycleClasses)]; @endphp
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <a href="javascript:void(0)"
                                onclick="detailCount('{{ $dt->id }}', '{{ $text }}')"
                                class="customer-count-card">
                                <div class="ccc-left">
                                    <div class="ccc-avatar {{ $cls }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="ccc-name">
                                            {{ request('filter') === 'vlan' ? 'VLAN ' . $dt->name : $dt->name }}</div>
                                        <div class="ccc-count">
                                            <strong
                                                style="color:#7c3aed;">{{ number_format($dt->customer_count) }}</strong>
                                            Pelanggan
                                        </div>
                                    </div>
                                </div>
                                <svg class="ccc-arrow" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6" />
                                </svg>
                            </a>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="dash-empty" style="background:white;border-radius:18px;">
                                <div class="dash-empty-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8" />
                                        <path d="m21 21-4.35-4.35" />
                                    </svg>
                                </div>
                                <h6>Tidak ada data</h6>
                                <p>Tidak ditemukan data untuk kategori yang dipilih</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

    </div>
@endsection

@push('modal')
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content" style="border-radius:18px;overflow:hidden;">
                <div class="modal-header"
                    style="background:linear-gradient(135deg,#1e1b4b,#4c1d95);border:none;padding:1.2rem 1.5rem;">
                    <h5 class="modal-title" style="color:white;font-weight:700;font-size:.95rem;"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="org-table" style="border-radius:0;">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>TIPE PELANGGAN</th>
                                    <th>NAMA PELANGGAN</th>
                                    <th>EMAIL</th>
                                    <th>NO TELP</th>
                                    <th>MAC ADDRESS</th>
                                    <th>ROUTER</th>
                                    <th>DI INPUT OLEH</th>
                                    <th>TANGGAL MASUK</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-show"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        const BASE = "{{ route('dashboard') }}";

        function detailCount(id, text) {
            if (text === "Kabupaten / Kota") text = "kabupaten";

            $.ajax({
                url: `/get-detail-count/${id}/${text}`,
                method: "GET",
                dataType: "json",
                beforeSend: function() {
                    $(".modal-title").html('Memuat data...');
                    $("#tbody-show").html(
                        '<tr><td colspan="9" class="text-center py-4 text-muted">Memuat...</td></tr>');
                    new bootstrap.Modal(document.getElementById('modal-simple')).show();
                },
                success: function(data) {
                    $(".modal-title").html(`
                    <div style="display:flex;align-items:center;gap:8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/></svg>
                        Detail Pelanggan — ${data.data.name}
                    </div>
                `);

                    let html = '';
                    let no = 1;
                    if (data.data.customer && data.data.customer.length > 0) {
                        $.each(data.data.customer, function(index, value) {
                            html += `<tr>
                            <td style="color:#94a3b8;font-size:.8rem;">${no++}</td>
                            <td><span class="badge-tipe">${value.type?.name ?? '-'}</span></td>
                            <td><strong>${value.name}</strong></td>
                            <td>${value.email ?? '-'}</td>
                            <td>${value.telp ?? '-'}</td>
                            <td style="font-family:monospace;font-size:.8rem;">${value.mac_address ?? '-'}</td>
                            <td>${value.router?.name ?? '-'}</td>
                            <td>${value.user?.name ?? '-'}</td>
                            <td>${new Date(value.created_at).toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' })}</td>
                        </tr>`;
                        });
                    } else {
                        html =
                            '<tr><td colspan="9" class="text-center py-4 text-muted">Tidak ada pelanggan</td></tr>';
                    }
                    $("#tbody-show").html(html);
                },
                error: function() {
                    $("#tbody-show").html(
                        '<tr><td colspan="9" class="text-center py-4 text-danger">Gagal memuat data</td></tr>'
                    );
                }
            });
        }
    </script>
@endpush

@push('css')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #2563eb;
        --primary-dark: #1d4ed8;
        --primary-darker: #1e40af;
        --dark: #0f172a;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --radius: 12px;
        --radius-lg: 20px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.07);
        --shadow-lg: 0 12px 40px rgba(0,0,0,0.1);
        --transition: 0.25s cubic-bezier(0.4,0,0.2,1);
    }

    *, *::before, *::after { box-sizing: border-box; }

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: linear-gradient(135deg, var(--gray-50) 0%, #eef2ff 50%, var(--gray-50) 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1.5rem 4rem;
        margin: 0;
    }

    .ambient-orb {
        position: fixed;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .ambient-orb--1 {
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(37,99,235,0.08) 0%, transparent 70%);
        top: -200px; left: -150px;
    }
    .ambient-orb--2 {
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(16,185,129,0.07) 0%, transparent 70%);
        bottom: -250px; right: -200px;
    }

    .search-card {
        position: relative;
        z-index: 1;
        background: #fff;
        border: 1px solid rgba(15,23,42,0.08);
        box-shadow: var(--shadow-lg);
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 580px;
        overflow: hidden;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%);
        padding: 2.25rem 2rem 1.75rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .card-header-gradient::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 30% 50%, rgba(255,255,255,0.08) 0%, transparent 60%);
    }
    .brand-logo-container {
        display: inline-flex;
        gap: 10px;
        padding: 8px 18px;
        background: rgba(255,255,255,0.95);
        border-radius: 12px;
        align-items: center;
        margin-bottom: 0.85rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        position: relative;
    }
    .brand-logo-container svg { display: block; }
    .brand-logo-container img { height: 28px; width: auto; }
    .card-header-gradient h1 {
        color: #fff;
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin: 0 0 0.35rem;
        position: relative;
    }
    .card-header-gradient p {
        color: rgba(255,255,255,0.8);
        font-size: 0.85rem;
        margin: 0;
        font-weight: 500;
        position: relative;
    }

    .card-body-content { padding: 2rem 2.25rem 2.5rem; }

    .status-section {
        text-align: center;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        margin-bottom: 1.5rem;
    }
    .status-icon-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        margin: 0 auto 12px;
        border-radius: 50%;
    }
    .status-icon-wrap svg { width: 32px; height: 32px; }

    .status-label {
        font-size: 12px;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .status-badge {
        font-size: 13px;
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 600;
        display: inline-block;
    }
    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }
    .badge-assigned {
        background: #dbeafe;
        color: #1e40af;
    }
    .badge-completed {
        background: #d1fae5;
        color: #065f46;
    }
    .badge-notfound {
        background: #fee2e2;
        color: #991b1b;
    }
    .badge-empty {
        background: var(--gray-100);
        color: var(--gray-500);
    }

    .detail-section { padding: 0; }

    .detail-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 0;
        border-bottom: 1px solid var(--gray-100);
    }
    .detail-item:last-child { border-bottom: none; }

    .detail-icon {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        margin-top: 1px;
        color: var(--gray-400);
    }
    .detail-icon svg { width: 20px; height: 20px; display: block; }

    .detail-content { flex: 1; min-width: 0; }

    .detail-label {
        font-size: 11px;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 2px;
    }
    .detail-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--dark);
    }

    .info-box {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        margin-top: 1.5rem;
        padding: 1rem 1.25rem;
        border-radius: var(--radius);
        background: #eaf1ff;
        font-size: 13px;
        color: #1e3a8a;
        line-height: 1.6;
    }
    .info-box svg {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        margin-top: 1px;
        color: #1e3a8a;
    }

    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
    }
    .empty-state svg {
        width: 64px;
        height: 64px;
        color: var(--gray-300);
        margin-bottom: 16px;
    }
    .empty-state h3 {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--dark);
        margin: 0 0 6px;
    }
    .empty-state p {
        font-size: 14px;
        color: var(--gray-500);
        margin: 0;
    }

    .loading-state {
        text-align: center;
        padding: 3rem 1rem;
    }
    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid var(--gray-200);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin: 0 auto 16px;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-state p {
        font-size: 14px;
        color: var(--gray-500);
        margin: 0;
    }

    @media (max-width: 576px) {
        body { padding: 1rem 0.75rem 2rem; }
        .card-header-gradient { padding: 1.75rem 1.25rem 1.5rem; }
        .card-header-gradient h1 { font-size: 1.2rem; }
        .card-body-content { padding: 1.5rem 1.25rem 2rem; }
        .detail-item { padding: 12px 0; }
    }
</style>
@endpush

@extends('layouts.app-pages')

@section('title', 'Cek Status Pendaftaran — CIO Network')

@section('content')
<div class="ambient-orb ambient-orb--1"></div>
<div class="ambient-orb ambient-orb--2"></div>

<div class="search-card card">
    <div class="card-header-gradient">
        <div class="brand-logo-container">
            <img src="{{ asset('img/logo_2.jpeg') }}" alt="Logo Andira">
            <img src="{{ asset('img/logo.jpg') }}" alt="Logo CN">
        </div>
        <h1>Cek Status Pendaftaran</h1>
        <p>Informasi status pendaftaran layanan internet Anda</p>
    </div>

    <div class="card-body-content" id="app-content">
        <div class="loading-state" id="loading-state">
            <div class="spinner"></div>
            <p>Memuat data pendaftaran...</p>
        </div>

        <div class="empty-state" id="empty-state" style="display:none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
                <path d="M8 11h6"/>
            </svg>
            <h3>Kode Tidak Ditemukan</h3>
            <p id="empty-message">Kode pendaftaran tidak tersedia. Periksa kembali kode pada bukti pendaftaran Anda.</p>
        </div>

        <div id="result-content" style="display:none;">
            <div class="status-section" id="status-section"></div>
            <div class="detail-section" id="detail-section"></div>
            <div class="info-box" id="info-box"></div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const kode = params.get('cstmrid');

    const loadingEl = document.getElementById('loading-state');
    const emptyEl = document.getElementById('empty-state');
    const emptyMsg = document.getElementById('empty-message');
    const resultEl = document.getElementById('result-content');
    const statusSection = document.getElementById('status-section');
    const detailSection = document.getElementById('detail-section');
    const infoBox = document.getElementById('info-box');

    if (!kode) {
        loadingEl.style.display = 'none';
        emptyEl.style.display = 'block';
        emptyMsg.textContent = 'Tidak ada kode pendaftaran yang ditemukan. Silakan gunakan tautan yang disertakan pada bukti pendaftaran Anda.';
        return;
    }

    fetch('/search-pendaftaran?cstmrid=' + encodeURIComponent(kode) + '&format=json')
        .then(function (res) { return res.json(); })
        .then(function (json) {
            loadingEl.style.display = 'none';

            if (json.status === 'error') {
                emptyEl.style.display = 'block';
                var esc = document.createElement('span'); esc.textContent = kode;
                emptyMsg.innerHTML = 'Kode pendaftaran <strong>' + esc.innerHTML + '</strong> tidak ditemukan. Periksa kembali kode yang tertera pada bukti pendaftaran Anda.';
                return;
            }

            resultEl.style.display = 'block';

            if (json.type === 'pendaftaran') {
                renderPendaftaran(json.data);
            } else if (json.type === 'customer') {
                renderCustomer(json.data);
            }
        })
        .catch(function () {
            loadingEl.style.display = 'none';
            emptyEl.style.display = 'block';
            emptyMsg.textContent = 'Terjadi kesalahan saat memuat data. Silakan coba lagi.';
        });

    function renderPendaftaran(d) {
        var statusMap = {
            pending:  { label: 'Menunggu Diproses',  cls: 'badge-pending' },
            assigned: { label: 'Sedang Dikerjakan Teknisi', cls: 'badge-assigned' },
        };
        var info = statusMap[d.status] || { label: d.status, cls: 'badge-notfound' };

        var svgIcon = getStatusSvg(d.status);

        statusSection.innerHTML =
            '<div class="status-icon-wrap" style="background:' + getStatusBg(d.status) + '">' +
                svgIcon +
            '</div>' +
            '<div class="status-label">Status Pendaftaran</div>' +
            '<span class="status-badge ' + info.cls + '">' + info.label + '</span>';

        detailSection.innerHTML =
            detailRow('tag', 'Kode Pendaftaran', d.kode) +
            detailRow('user', 'Nama Pelanggan', d.nama) +
            detailRow('phone', 'Nomor Telepon', d.no_telepon) +
            detailRow('layanan', 'Layanan', d.layanan) +
            detailRow('map-pin', 'Lokasi', d.lokasi) +
            detailRow('calendar', 'Tanggal Daftar', d.tanggal_daftar);

        var infoMsg = '';
        if (d.status === 'assigned') {
            var escName = document.createElement('span'); escName.textContent = d.assigned_to || 'teknis kami';
            infoMsg = 'Pendaftaran Anda sedang ditangani oleh <strong>' + escName.innerHTML + '</strong>' +
                      (d.assigned_at ? ' sejak ' + d.assigned_at + ' WIB.' : '.');
        } else if (d.status === 'pending') {
            infoMsg = 'Pendaftaran Anda masih dalam antrian dan akan segera diproses oleh tim kami.';
        }
        infoBox.innerHTML =
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                '<circle cx="12" cy="12" r="10"/>' +
                '<path d="M12 16v-4"/>' +
                '<path d="M12 8h.01"/>' +
            '</svg>' +
            '<span>' + infoMsg + '</span>';
    }

    function renderCustomer(d) {
        statusSection.innerHTML =
            '<div class="status-icon-wrap" style="background:#d1fae5">' +
                '<svg viewBox="0 0 24 24" fill="none" stroke="#065f46" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:32px;height:32px;">' +
                    '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>' +
                    '<polyline points="22 4 12 14.01 9 11.01"/>' +
                '</svg>' +
            '</div>' +
            '<div class="status-label">Status Pendaftaran</div>' +
            '<span class="status-badge badge-completed">Telah Diproses</span>';

        detailSection.innerHTML =
            detailRow('tag', 'Kode Pelanggan', d.uuid) +
            detailRow('user', 'Nama Pelanggan', d.name) +
            detailRow('phone', 'Nomor Telepon', d.telp);

        infoBox.innerHTML =
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                '<circle cx="12" cy="12" r="10"/>' +
                '<path d="M12 16v-4"/>' +
                '<path d="M12 8h.01"/>' +
            '</svg>' +
            '<span>Pendaftaran Anda telah selesai diproses. Selamat menikmati layanan internet dari CIO Network Solution!</span>';
    }

    function detailRow(icon, label, value) {
        var icons = {
            tag:      '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>',
            user:     '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            phone:    '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
            layanan:  '<rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',
            'map-pin':'<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
            calendar: '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
        };
        var pathData = icons[icon] || icons.tag;
        return '<div class="detail-item">' +
            '<div class="detail-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' + pathData + '</svg></div>' +
            '<div class="detail-content"><div class="detail-label">' + label + '</div><div class="detail-value">' + value + '</div></div>' +
        '</div>';
    }

    function getStatusSvg(status) {
        var color = status === 'pending' ? '#92400e' : status === 'assigned' ? '#1e40af' : '#065f46';
        if (status === 'pending') {
            return '<svg viewBox="0 0 24 24" fill="none" stroke="' + color + '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:32px;height:32px;">' +
                '<path d="M12 2v20"/><path d="M12 2A10 10 0 0 0 2 12h10V2z"/><path d="M12 22A10 10 0 0 0 22 12H12v10z"/>' +
            '</svg>';
        } else if (status === 'assigned') {
            return '<svg viewBox="0 0 24 24" fill="none" stroke="' + color + '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:32px;height:32px;">' +
                '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>' +
            '</svg>';
        }
        return '';
    }

    function getStatusBg(status) {
        if (status === 'pending') return '#fef3c7';
        if (status === 'assigned') return '#dbeafe';
        return '#d1fae5';
    }
});
</script>
@endpush

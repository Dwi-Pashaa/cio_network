{{--
    Partial: Validation Panel
    Variabel yang diterima:
    - $prosedur_type  : 'pemutusan' | 'pergantian-layanan' | 'onu-router'
    - $panel_title    : string, label judul panel
    - $panel_color    : hex warna aksen
--}}
@php
    $levels = config('prosedur_levels.levels', []);
    $colorMap = ['blue' => '#2563eb', 'green' => '#16a34a', 'orange' => '#ea580c', 'purple' => '#7c3aed'];
@endphp

{{-- Level Legend ──────────────────────────────────────────── --}}
<div class="level-legend">
    <span style="font-size: 0.72rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.07em;">Level validator:</span>
    @foreach($levels as $num => $cfg)
        <span class="lvl-chip lvl-{{ $cfg['color'] }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="12"/></svg>
            L{{ $num }}: {{ $cfg['label'] }}
        </span>
    @endforeach
</div>

{{-- Content Area ──────────────────────────────────────────── --}}
<div style="padding: 1.25rem 1.5rem;">
    {{-- Loading spinner --}}
    <div class="loading-spin" id="val-loading-{{ $prosedur_type }}">
        <div class="spinner-border" style="width: 2rem; height: 2rem; color: {{ $panel_color }};" role="status"></div>
        <p style="margin-top: 0.6rem; font-size: 0.82rem;">Memuat antrean {{ $panel_title }}...</p>
    </div>

    {{-- Card container (diisi oleh JS) --}}
    <div id="val-content-{{ $prosedur_type }}"></div>
</div>

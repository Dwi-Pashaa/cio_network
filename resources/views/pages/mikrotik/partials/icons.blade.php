{{-- Router Hub Logo Icon --}}
@if(($name ?? '') === 'router-hub')
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="{{ $size ?? 38 }}" height="{{ $size ?? 38 }}" fill="none">
    <rect x="6" y="24" width="36" height="16" rx="4" fill="#0054a6" />
    <circle cx="12" cy="32" r="2" fill="#38bdf8" />
    <circle cx="18" cy="32" r="2" fill="#38bdf8" />
    <circle cx="24" cy="32" r="2" fill="#38bdf8" />
    <rect x="30" y="30" width="8" height="4" rx="2" fill="#ffffff" opacity="0.8" />
    <path d="M12 24V17" stroke="#0054a6" stroke-width="2.5" stroke-linecap="round" />
    <path d="M36 24V17" stroke="#0054a6" stroke-width="2.5" stroke-linecap="round" />
    <path d="M19 16C20.5 14.5 22.2 13.7 24 13.7C25.8 13.7 27.5 14.5 29 16" stroke="#0284c7" stroke-width="2" stroke-linecap="round" />
    <path d="M15 12C17.5 9.5 20.6 8.2 24 8.2C27.4 8.2 30.5 9.5 33 12" stroke="#0284c7" stroke-width="2" stroke-linecap="round" />
    <path d="M11 8C14.5 4.8 19 3 24 3C29 3 33.5 4.8 37 8" stroke="#0284c7" stroke-width="2" stroke-linecap="round" />
</svg>
@endif

{{-- Card 1: TOTAL LEASES (Database Stack + Coin Badge) --}}
@if(($name ?? '') === 'total-leases')
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="{{ $size ?? 36 }}" height="{{ $size ?? 36 }}" fill="none">
    <ellipse cx="24" cy="11" rx="16" ry="6" fill="#38bdf8" />
    <path d="M8 11V21C8 24.3 15.2 27 24 27C32.8 27 40 24.3 40 21V11" fill="#0284c7" opacity="0.85" />
    <ellipse cx="24" cy="21" rx="16" ry="6" fill="#38bdf8" opacity="0.6" />
    <path d="M8 21V31C8 34.3 15.2 37 24 37C32.8 37 40 34.3 40 31V21" fill="#0054a6" />
    <ellipse cx="24" cy="31" rx="16" ry="6" fill="#38bdf8" opacity="0.6" />
    {{-- Badge Coin --}}
    <circle cx="15" cy="33" r="10" fill="#0284c7" stroke="#ffffff" stroke-width="2.5" />
    <text x="15" y="37" font-family="Arial, sans-serif" font-size="12" font-weight="bold" fill="#ffffff" text-anchor="middle">$</text>
</svg>
@endif

{{-- Card 2: BOUND / AKTIF (Shield with Link) --}}
@if(($name ?? '') === 'bound-active')
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="{{ $size ?? 36 }}" height="{{ $size ?? 36 }}" fill="none">
    <path d="M24 4L40 10V22C40 33 33 41 24 44C15 41 8 33 8 22V10L24 4Z" fill="#10b981" />
    <path d="M24 7L37 12V22C37 31.5 31 38.5 24 41.2C17 38.5 11 31.5 11 22V12L24 7Z" fill="#059669" />
    {{-- Chain Link in center --}}
    <path d="M22 26L26 22M20 20L17.5 22.5C15.5 24.5 15.5 27.5 17.5 29.5C19.5 31.5 22.5 31.5 24.5 29.5L27 27M21 21L23.5 18.5C25.5 16.5 28.5 16.5 30.5 18.5C32.5 20.5 32.5 23.5 30.5 25.5L28 28" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
@endif

{{-- Card 3: DYNAMIC LEASE (Cloud with Transfer Arrows) --}}
@if(($name ?? '') === 'dynamic-lease')
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="{{ $size ?? 36 }}" height="{{ $size ?? 36 }}" fill="none">
    <path d="M38 34H11C6.58 34 3 30.42 3 26C3 21.8 6.2 18.4 10.3 18.05C11.5 11.8 17 7 23.5 7C30.5 7 36.3 12.3 36.9 19.3C41 20 44 23.6 44 28C44 31.3 41.3 34 38 34Z" fill="#d97706" />
    <path d="M36 32H12C8.7 32 6 29.3 6 26C6 22.9 8.3 20.3 11.4 20C12.4 14.8 17 11 22.5 11C28.3 11 33.1 15.4 33.7 21.2C37.2 21.8 40 24.8 40 28.5C40 30.4 38.4 32 36 32Z" fill="#f59e0b" />
    {{-- Dual transfer arrows --}}
    <path d="M19 28V19M19 19L16 22M19 19L22 22" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
    <path d="M27 20V29M27 29L24 26M27 29L30 26" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
</svg>
@endif

{{-- Card 4: WAITING (Hourglass / Sand Timer) --}}
@if(($name ?? '') === 'waiting')
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="{{ $size ?? 36 }}" height="{{ $size ?? 36 }}" fill="none">
    <rect x="10" y="5" width="28" height="5" rx="2.5" fill="#92400e" />
    <rect x="10" y="38" width="28" height="5" rx="2.5" fill="#92400e" />
    <path d="M14 10C14 18 21 21.5 21 24C21 26.5 14 30 14 38H34C34 30 27 26.5 27 24C27 21.5 34 18 34 10H14Z" fill="#d97706" opacity="0.3" stroke="#b45309" stroke-width="2.5" stroke-linejoin="round" />
    {{-- Upper and Lower sand --}}
    <path d="M17 14H31C31 18 26 21 24 22C22 21 17 18 17 14Z" fill="#b45309" />
    <path d="M16 38H32C32 34 28 30 24 30C20 30 16 34 16 38Z" fill="#f59e0b" />
    <circle cx="24" cy="26" r="1.5" fill="#b45309" />
</svg>
@endif

{{-- Card 5: STATIC LEASE (Padlock & Key) --}}
@if(($name ?? '') === 'static-lease')
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="{{ $size ?? 36 }}" height="{{ $size ?? 36 }}" fill="none">
    <path d="M16 19V14C16 9.6 19.6 6 24 6C28.4 6 32 9.6 32 14V19" stroke="#b45309" stroke-width="3.5" stroke-linecap="round" />
    <rect x="12" y="19" width="24" height="23" rx="4" fill="#ca8a04" />
    <rect x="14" y="21" width="20" height="19" rx="2.5" fill="#eab308" />
    <circle cx="24" cy="28" r="3" fill="#78350f" />
    <path d="M24 31V36" stroke="#78350f" stroke-width="2.5" stroke-linecap="round" />
    {{-- Key in front --}}
    <circle cx="34" cy="34" r="5" fill="#ca8a04" stroke="#ffffff" stroke-width="1.5" />
    <circle cx="34" cy="34" r="2" fill="#ffffff" />
    <path d="M34 39V45M34 42H37M34 44H36" stroke="#ca8a04" stroke-width="2.5" stroke-linecap="round" />
</svg>
@endif

{{-- Search Magnifier --}}
@if(($name ?? '') === 'search')
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{{ $size ?? 18 }}" height="{{ $size ?? 18 }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="11" cy="11" r="8"></circle>
    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
</svg>
@endif

{{-- Refresh Dual Arrows --}}
@if(($name ?? '') === 'refresh')
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{{ $size ?? 18 }}" height="{{ $size ?? 18 }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.19" />
</svg>
@endif

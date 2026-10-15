<?php

return [
    'categories' => [
        'jaringan' => [
            'name' => 'Jaringan & Infrastruktur',
            'color' => 'primary',
        ],
        'barang' => [
            'name' => 'Perangkat & Stock',
            'color' => 'azure',
        ],
        'operasional' => [
            'name' => 'Barang Operasional',
            'color' => 'teal',
        ],
        'layanan' => [
            'name' => 'Paket & Pembayaran',
            'color' => 'indigo',
        ],
    ],

    'items' => [
        // Jaringan
        [
            'title' => 'Data Router',
            'category' => 'jaringan',
            'route' => 'router.index',
            'permission' => 'akses cepat router',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="13" width="18" height="8" rx="2" /><line x1="17" y1="17" x2="17" y2="17.01" /><line x1="13" y1="17" x2="13" y2="17.01" /><line x1="15" y1="13" x2="15" y2="11" /><path d="M11.5 6.5a4.5 4.5 0 0 1 7 0" /><path d="M9 4a8 8 0 0 1 12 0" /></svg>',
            'badge' => 'Router',
        ],
        [
            'title' => 'Data VLAN',
            'category' => 'jaringan',
            'route' => 'vlan.index',
            'permission' => 'akses cepat vlan',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="6" cy="6" r="3" /><circle cx="18" cy="18" r="3" /><line x1="8.6" y1="8.6" x2="15.4" y2="15.4" /></svg>',
            'badge' => 'VLAN',
        ],
        [
            'title' => 'Data OLT',
            'category' => 'jaringan',
            'route' => 'olt.index',
            'permission' => 'akses cepat olt',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="18" height="8" rx="2" /><rect x="3" y="12" width="18" height="8" rx="2" /><line x1="7" y1="8" x2="7" y2="8.01" /><line x1="7" y1="16" x2="7" y2="16.01" /></svg>',
            'badge' => 'OLT',
        ],
        [
            'title' => 'Data ODC',
            'category' => 'jaringan',
            'route' => 'odc.index',
            'permission' => 'akses cepat odc',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="16" height="16" rx="2" /><line x1="9" y1="4" x2="9" y2="20" /><line x1="15" y1="4" x2="15" y2="20" /></svg>',
            'badge' => 'ODC',
        ],
        [
            'title' => 'Data ODP',
            'category' => 'jaringan',
            'route' => 'odp.index',
            'permission' => 'akses cepat odp',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="8" /><circle cx="12" cy="12" r="3" /><line x1="12" y1="4" x2="12" y2="9" /><line x1="12" y1="15" x2="12" y2="20" /></svg>',
            'badge' => 'ODP',
        ],
        [
            'title' => 'Data Server',
            'category' => 'jaringan',
            'route' => 'server.index',
            'permission' => 'akses cepat server',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="18" height="6" rx="2" /><rect x="3" y="14" width="18" height="6" rx="2" /><line x1="7" y1="7" x2="7" y2="7.01" /><line x1="7" y1="17" x2="7" y2="17.01" /></svg>',
            'badge' => 'Server',
        ],
        [
            'title' => 'Mic Radius',
            'category' => 'jaringan',
            'route' => 'mic.radius.index',
            'permission' => 'akses cepat mic radius',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 3" /></svg>',
            'badge' => 'Radius',
        ],
        [
            'title' => 'MAC Address',
            'category' => 'jaringan',
            'route' => 'mac.address.index',
            'permission' => 'akses cepat mac address',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="7" width="18" height="10" rx="2" /><line x1="7" y1="12" x2="7" y2="12.01" /><line x1="11" y1="12" x2="11" y2="12.01" /><line x1="15" y1="12" x2="15" y2="12.01" /></svg>',
            'badge' => 'MAC',
        ],

        // Perangkat & Stock
        [
            'title' => 'Patch Core',
            'category' => 'barang',
            'route' => 'patch.core.index',
            'permission' => 'akses cepat patch core',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12h7m4 0h7" /><circle cx="12" cy="12" r="2" /></svg>',
            'badge' => 'Patch Core',
        ],
        [
            'title' => 'PLC Splitter',
            'category' => 'barang',
            'route' => 'plc.index',
            'permission' => 'akses cepat plc',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="6" height="6" rx="1" /><rect x="15" y="4" width="6" height="6" rx="1" /><rect x="15" y="14" width="6" height="6" rx="1" /><line x1="9" y1="7" x2="15" y2="7" /><path d="M12 7v10h3" /></svg>',
            'badge' => 'PLC',
        ],
        [
            'title' => 'Stock Router',
            'category' => 'barang',
            'route' => 'user.router.index',
            'permission' => 'akses cepat stock router',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5" /></svg>',
            'badge' => 'Stock Router',
        ],
        [
            'title' => 'Stock Patch Core',
            'category' => 'barang',
            'route' => 'user.patch.core.index',
            'permission' => 'akses cepat stock patch core',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5" /></svg>',
            'badge' => 'Stock Patch',
        ],
        [
            'title' => 'Stock PLC',
            'category' => 'barang',
            'route' => 'user.plc.index',
            'permission' => 'akses cepat stock plc',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5" /></svg>',
            'badge' => 'Stock PLC',
        ],

        // Barang Operasional
        [
            'title' => 'Tipe Barang Ops',
            'category' => 'operasional',
            'route' => 'tipe-barang-operasional.index',
            'permission' => 'akses cepat tipe barang operasional',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="6" height="6" rx="1" /><rect x="14" y="4" width="6" height="6" rx="1" /><rect x="4" y="14" width="6" height="6" rx="1" /><rect x="14" y="14" width="6" height="6" rx="1" /></svg>',
            'badge' => 'Tipe Ops',
        ],
        [
            'title' => 'Barang Operasional',
            'category' => 'operasional',
            'route' => 'barang-operasional.index',
            'permission' => 'akses cepat barang operasional',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12v9" /><path d="M12 12l-8 -4.5" /></svg>',
            'badge' => 'Barang Ops',
        ],
        [
            'title' => 'Riwayat Transfer',
            'category' => 'operasional',
            'route' => 'barang-operasional.riwayat',
            'permission' => 'akses cepat riwayat transfer barang',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 8 12 12 14 14" /><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" /></svg>',
            'badge' => 'Riwayat',
        ],

        // Layanan & Pembayaran
        [
            'title' => 'Data Tipe Paket',
            'category' => 'layanan',
            'route' => 'paket.index',
            'permission' => 'akses cepat tipe paket',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="16" height="16" rx="2" /><line x1="4" y1="10" x2="20" y2="10" /><line x1="10" y1="4" x2="10" y2="20" /></svg>',
            'badge' => 'Paket',
        ],
        [
            'title' => 'Data Tipe Pembayaran',
            'category' => 'layanan',
            'route' => 'price.index',
            'permission' => 'akses cepat tipe pembayaran',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1" /><path d="M12 7v10" /></svg>',
            'badge' => 'Harga / Bayar',
        ],
    ],
];

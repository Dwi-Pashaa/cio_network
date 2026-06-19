<?php

/**
 * Konfigurasi Level Validator Prosedur
 *
 * Setiap level merepresentasikan satu checkpoint validasi.
 * Label dan permission bisa disesuaikan di sini tanpa mengubah kode atau database.
 * Untuk menambah/mengurangi level, cukup tambah/hapus entri di array ini.
 *
 * Permission yang tertera HARUS terdaftar di PermissionSeeder.php.
 */

return [
    'levels' => [
        1 => [
            'label'      => 'Admin',
            'permission' => 'validasi prosedur level 1',
            'color'      => 'blue',   // Untuk badge warna di UI
        ],
        2 => [
            'label'      => 'OLT',
            'permission' => 'validasi prosedur level 2',
            'color'      => 'green',
        ],
        3 => [
            'label'      => 'ONC',
            'permission' => 'validasi prosedur level 3',
            'color'      => 'orange',
        ],
        4 => [
            'label'      => 'Mix Radius',
            'permission' => 'validasi prosedur level 4',
            'color'      => 'purple',
        ],
    ],
];

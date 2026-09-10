# ADR-0002: MikroTik RouterOS Real-time Synchronization & SSE Monitoring

## Status
**Accepted**

## Tanggal
2026-09-08

## Konteks
Aplikasi membutuhkan pemantauan perangkat router MikroTik untuk:
- Memantau DHCP Leases, status perangkat (bound, waiting, offered, disabled, offline), ARP table, dan Hotspot hosts.
- Menampilkan grafik traffic throughput interface secara real-time.
- Melakukan korelasi data MAC address perangkat fisik MikroTik dengan data pelanggan yang terdaftar di database.

Kendala:
- Memanggil API MikroTik langsung pada setiap request halaman web menyebabkan latensi tinggi, timeout jika router sibuk, dan beban CPU tinggi pada router fisik.

## Keputusan
1. **Hybrid Synchronization Model**:
   - **Tabel Lokal (`mikrotik_devices`)**: Data lease/ARP dari MikroTik disinkronisasi ke tabel lokal melalui command Artisan `mikrotik:sync` yang dijadwalkan berkala (hourly) dan saat manual refresh.
   - Halaman monitoring membaca tabel lokal secara instan (<50ms) dengan filter dan pagination.
2. **Server-Sent Events (SSE) untuk Traffic Live**:
   - Pemantauan throughput antarmuka menggunakan SSE (`/monitoring/mikrotik/stream`) yang mengirimkan byte rate per interval detik tanpa perlu polling AJAX HTTP berulang-ulang yang berat.
3. **Koneksi RouterOS Terisolasi**:
   - Komunikasi socket API RouterOS dibungkus dalam `MikrotikService` dengan timeout terkonfigurasi (default 15 detik) dan penanganan graceful error fallback.

## Alternatif yang Dipertimbangkan
- **SNMP Polling**: Membutuhkan setup daemon SNMP dan MIB parser yang lebih kompleks. API RouterOS lebih langsung dan mendukung manipulasi data (reboot/static lease).
- **Direct Live Query pada Setiap Request**: Ditolak karena router menjadi bottleneck dan memperlambat response time aplikasi hingga >5-10 detik.

## Konsekuensi
- Tampilan monitoring tabel selalu cepat dan tahan terhadap fluktuasi jaringan router.
- Real-time traffic bekerja mulus melalui koneksi stream SSE.
- Memerlukan scheduler cron job Laravel yang aktif untuk menjaga data perangkat tetap mutakhir.

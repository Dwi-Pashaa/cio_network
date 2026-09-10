# 🌐 CIO Network Solution - ISP & Network Management System

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)](https://mysql.com)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker)](https://docker.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.x-7952B3?style=for-the-badge&logo=bootstrap)](https://getbootstrap.com)

**CIO Network** adalah platform manajemen operasional dan infrastruktur jaringan internet (ISP & RT/RW Net) berbasis web yang dirancang untuk mengelola pelanggan, topologi jaringan fiber optic, pemantauan perangkat MikroTik secara real-time, manajemen tiket troubleshoot, serta pendaftaran mandiri pelanggan.

---

## 🚀 Fitur Utama

### 1. 👥 Manajemen Pelanggan (Customer Management)
- **Multi-Service Support**: Mendukung pengelolaan pelanggan tipe PPPoE, Hotspot Voucher, dan Dedicated/Static IP.
- **Verifikasi Kontak Otomatis**: Integrasi API WhatsApp (Wablas/Fonte) dan verifikasi email aktif.
- **Geolokasi & Data Fisik**: Penyimpanan koordinat GPS lokasi rumah, integrasi peta interaktif (Leaflet / Google Maps), upload foto KTP, dan tanda tangan digital.
- **Server-Side DataTables**: Penelusuran data cepat, filter wilayah (Kabupaten, Kecamatan, Desa, RT/RW), filter status perangkat MikroTik (Bound, Waiting, Offered, Offline), dan ekspor data (Excel / PDF).

### 2. ⚡ Monitoring MikroTik RouterOS & Jaringan
- **Live DHCP Hub Monitoring**: Sinkronisasi data DHCP Leases, ARP Table, dan Hotspot Host langsung dari MikroTik RouterOS via API.
- **Real-time Traffic Streaming**: Pemantauan traffic bandwidth interface secara real-time via Server-Sent Events (SSE).
- **Manajemen Perangkat Fiber Optik**: Pencatatan dan mapping perangkat Router, OLT, ODC, ODP, VLAN, Switch, PLC, dan Patch Core.

### 3. 📝 Pendaftaran Publik & Self-Service Portal
- **Wizard Pendaftaran Baru**: Formulir registrasi pelanggan mandiri dengan pemilihan paket internet, metode pembayaran, tanda tangan digital, dan cetak bukti pendaftaran.
- **Portal Reset Mandiri**: Fitur scan QR Code untuk reset password WiFi dan akun PPPoE pelanggan secara mandiri.

### 4. 🛠️ Sistem Tiket Troubleshoot & Teknisi
- **Tiket Gangguan Terintegrasi**: Pencatatan keluhan pelanggan dari pelaporan hingga penyelesaian.
- **Live Tracking Teknisi**: Pelacakan posisi koordinat teknisi saat menuju lokasi gangguan.
- **Upload Progress Lapangan**: Dokumentasi foto tahapan perbaikan secara bertahap (step-by-step).

### 5. 🏢 Multi-Organisasi & Keamanan
- **Multi-Tenant (Mitra vs Internal)**: Pembagian akses data jaringan dan pelanggan antar mitra/cabang.
- **Role-Based Access Control (RBAC)**: Pengaturan hak akses granular menggunakan Spatie Permission.
- **Activity Log**: Audit trail riwayat perubahan data oleh pengguna sistem.

### 6. 💬 Komunikasi & Notifikasi Real-time
- **Live Chatting**: Komunikasi internal antar staf berbasis WebSocket (Pusher & Laravel Echo).
- **WhatsApp Notification Engine**: Template pesan notifikasi otomatis untuk pendaftaran dan keluhan.

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|---|---|
| **Framework Backend** | [Laravel 10.x](https://laravel.com/) (PHP 8.1+) |
| **Database** | MySQL 8.0 |
| **UI & Layout** | [Tabler Admin](https://tabler.io/), Bootstrap 5, DataTables 2.3+ |
| **Mapping & Visual** | Leaflet.js, ApexCharts, JsVectorMap |
| **Realtime Engine** | Pusher, Laravel Echo, SSE (Server-Sent Events) |
| **Integrasi Hardware** | MikroTik RouterOS API (`RouterosAPI`) |
| **Containerization** | Docker & Docker Compose |

---

## 📂 Struktur Direktori Utama

```
cio_network/
├── app/
│   ├── Console/Commands/        # Custom Artisan Commands (Mikrotik Sync, Backup, Wablas)
│   ├── DataTables/              # Yajra DataTables Server-Side Handlers
│   ├── Http/Controllers/Pages/  # Controllers (Customer, Dashboard, Jaringan, Troubleshoot)
│   ├── Models/                  # Eloquent Models (Customer, OLT, ODC, ODP, Router, dll)
│   └── Services/                # Service Layer (MikrotikService, Verification, WhatsApp)
├── database/
│   ├── migrations/              # Database Schema & Performance Indexes
│   └── seeders/                 # Database Default Seeders
├── docker/                      # Dockerfile & Container Config
├── resources/views/             # Blade Templates (Layouts, Dashboard, Customers, Forms)
├── routes/                      # Route Definitions (web, api, channels, master-*)
└── docker-compose.yml           # Multi-container orchestration (App, MySQL, PhpMyAdmin)
```

---

## 🚀 Panduan Instalasi & Menjalankan

### Menggunakan Docker (Direkomendasikan)

1. **Clone repositori**:
   ```bash
   git clone https://github.com/Dwi-Pashaa/cio_network.git
   cd cio_network
   ```

2. **Salin environment file**:
   ```bash
   cp .env.example .env
   ```

3. **Jalankan container dengan Docker Compose**:
   ```bash
   docker compose up -d
   ```

4. **Setup Database & Aplikasi di dalam container**:
   ```bash
   docker compose exec app composer install
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate --seed
   docker compose exec app php artisan storage:link
   ```

5. **Akses Aplikasi**:
   - **Web App**: `http://localhost:8004`
   - **PhpMyAdmin**: `http://localhost:8005`

---

## ⏱️ Scheduler & Background Jobs

Aplikasi menggunakan Laravel Task Scheduling untuk tugas otomatis:
- **Sinkronisasi MikroTik Devices**: Berjalan setiap 1 jam (`php artisan mikrotik:sync`).
- **Fetch Report Wablas**: Berjalan setiap 10 menit (`php artisan wablas:fetch`).
- **Backup Database**: Berjalan setiap tanggal 1 setiap bulan (`php artisan backup:monthly`).

Untuk menjalankan scheduler secara lokal:
```bash
docker compose exec app php artisan schedule:work
```

---

## 📄 Lisensi

Proyek ini dikembangkan untuk kebutuhan internal **CIO Network Solution**.
Hak Cipta &copy; 2026 CIO Network. Seluruh hak cipta dilindungi undang-undang.

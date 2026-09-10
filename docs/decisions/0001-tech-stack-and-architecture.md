# ADR-0001: Tech Stack & Architecture for ISP Management Platform

## Status
**Accepted**

## Tanggal
2026-03-01

## Konteks
Sistem CIO Network Solution dibangun untuk mengelola operasional penyedia layanan internet (ISP & RT/RW Net) multi-mitra dengan skala ribuan pelanggan dan perangkat jaringan fiber optik (OLT, ODC, ODP, VLAN, Router). Kebutuhan sistem meliputi:
- Pemisahan data multi-organisasi/mitra (*multi-tenant segregation*).
- Manajemen data relasional yang kompleks (pelanggan, geolokasi RT/RW, perangkat, invoice/pembayaran, tiket).
- Antarmuka web responsif dan cepat untuk staf lapangan dan admin.
- Integrasi perangkat jaringan (Router MikroTik) dan layanan pihak ketiga (WhatsApp API, verifikasi email).

## Keputusan
1. **Framework Backend**: Menggunakan **Laravel 10 (PHP 8.1+)** karena ekosistem yang matang, ORM Eloquent yang ekspresif, dukungan task scheduling terintegrasi, dan keamanan bawaan (CSRF, XSS, Authentication, RBAC).
2. **Database Primary**: Menggunakan **MySQL 8.0** untuk integritas data relasional ACID, dukungan composite indexing, foreign key cascade, dan kompatibilitas hosting luas.
3. **Frontend & UI**: Menggunakan **Tabler Admin Dashboard (Bootstrap 5)** dan **Yajra DataTables Server-Side** untuk rendering data ribuan pelanggan dengan efisien tanpa membebani browser.
4. **Containerization**: Menggunakan **Docker & Docker Compose** untuk standarisasi lingkungan pengembangan dan produksi yang konsisten.

## Alternatif yang Dipertimbangkan
- **Node.js / Express**: Perlu membangun sistem ORM, migrasi, dan scaffolding auth dari awal. Ditolak demi kecepatan rilis dan keandalan sistem administrasi terpadu.
- **Microservices**: Kompleksitas overhead deployment dan networking terlalu tinggi untuk fase saat ini. Monolith modular Laravel lebih efisien untuk dikelola tim.

## Konsekuensi
- Pengembangan fitur cepat dengan konvensi Laravel.
- Memerlukan konfigurasi caching dan indexing database yang baik seiring pertumbuhan data pelanggan.
- Deployment mudah direplikasi menggunakan container Docker.

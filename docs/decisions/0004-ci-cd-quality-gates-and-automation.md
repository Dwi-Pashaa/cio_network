# ADR-0004: GitHub Actions CI/CD Pipeline & Automated Quality Gates

## Status
**Accepted**

## Tanggal
2026-09-10

## Konteks
Dengan bertambahnya kontributor dan frekuensi deployment, integrasi perubahan kode ke branch utama (`Prod`, `main`) membutuhkan jaminan bahwa kode baru:
- Tidak merusak migrasi database atau skema yang ada.
- Lolos seluruh unit test dan feature test (seperti flow reset password WiFi, CRUD data, dan autentikasi).
- Lolos audit kerentanan keamanan paket dependency (Composer).
- Memastikan build aset frontend (Vite / Node.js) berhasil tanpa error kompilasi.

## Keputusan
1. **GitHub Actions CI Pipeline (`.github/workflows/ci.yml`)**:
   - Menjalankan pipeline otomatis pada setiap `push` dan `pull_request` ke branch `Prod`, `main`, dan `master`.
   - Menggunakan container service **MySQL 8.0** terisolasi di dalam runner untuk menjalankan migrasi dan test database nyata (*integration test*).
   - Menjalankan testing PHP (`php artisan test`) dan audit dependency (`composer audit`).
   - Menjalankan build aset frontend (`npm ci && npm run build`).
2. **Dependabot Configuration (`.github/dependabot.yml`)**:
   - Menjadwalkan pengecekan versi dependency mingguan untuk Composer dan NPM guna menjaga keamanan paket dari kerentanan CVE terbaru.

## Alternatif yang Dipertimbangkan
- **Manual Pre-deploy Testing**: Rawan kelalaian manusia (*human error*) dan tidak terstandar.
- **SQLite In-Memory Testing**: Walaupun lebih cepat, perilaku SQLite berbeda dengan MySQL pada enum, JSON, dan composite index tertentu. Menggunakan service container MySQL 8.0 menjamin akurasi lingkungan produksi.

## Konsekuensi
- Mencegah *breaking changes* dan bug masuk ke branch produksi.
- Seluruh PR diverifikasi secara otomatis sebelum di-merge.
- Waktu eksekusi pipeline tetap di bawah 2-3 menit berkat caching dependency Composer dan NPM.

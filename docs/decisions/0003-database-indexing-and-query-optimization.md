# ADR-0003: Database Indexing & Query Optimization for High-Scale DataTables

## Status
**Accepted**

## Tanggal
2026-09-10

## Konteks
Tabel `customers` adalah inti dari aplikasi CIO Network, dengan jumlah baris yang terus meningkat dan sering diakses melalui antarmuka Yajra DataTables dengan filter dinamis (status, wilayah, mitra, status verifikasi, dan status mikrotik).

Masalah yang Ditemukan:
1. Pengecekan AJAX dilakukan di akhir method controller setelah mengeksekusi belasan query master yang tidak digunakan pada request AJAX.
2. Global search mengeksekusi 11 nested `orWhereHas` subquery (`EXISTS (SELECT 1 ... WHERE name LIKE %...%)`) pada setiap pencarian teks, menyebabkan lonjakan CPU MySQL dan full table scan.
3. Kurangnya composite index pada kolom-kolom klausa `WHERE` dan `ORDER BY` (`status`, `organization_id`, `created_at`, `mac_address`).

## Keputusan
1. **Composite & Targeted Database Indexing**:
   - Menambahkan index `['status', 'organization_id', 'created_at']` untuk mendukung query isolasi tenant mitra dan sorting data terbaru.
   - Menambahkan index `mac_address` untuk mempercepat join relasi `Customer` ➔ `MikrotikDevice`.
   - Menambahkan composite index `['email_verify_at', 'wa_verifiy_at']` untuk filter status verifikasi kontak.
2. **Early Return pada Request AJAX**:
   - Memindahkan `if ($request->ajax()) return ...` ke baris paling awal method controller (`PagesController`, `CustomerController`).
3. **Streamlined Global Search**:
   - Membatasi pencarian teks bebas pada kolom-kolom utama tabel `customers` dan relasi kunci (`hometown`, `village`, `router`), sementara relasi lainnya diakses melalui filter dropdown spesifik.

## Alternatif yang Dipertimbangkan
- **Full-Text Search Engine (Meilisearch / Elasticsearch)**: Membutuhkan infrastruktur tambahan dan sinkronisasi queue. Optimasi MySQL index & query pruning saat ini sudah memberikan latensi <200ms tanpa menambah kompleksitas arsitektur.

## Konsekuensi
- Latensi respon DataTables turun drastis.
- Query plan MySQL beralih dari Full Table Scan ke Index Range Scan.
- Penggunaan RAM PHP berkurang karena query yang tidak diperlukan tidak lagi dieksekusi saat AJAX request.

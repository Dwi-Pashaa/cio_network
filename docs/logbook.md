# Logbook

## 2026-07-11 — Bug Fix: Stok Patch Core & Router Tidak Kembali ke User

### Masalah
Saat prosedur **Pergantian Perangkat (ONU/Router)** dan **Pergantian Layanan** disetujui, stok `patch_core` dan/atau `router` di tabel stok milik teknisi lapangan tidak dikembalikan/diupdate dengan benar.

### Perbaikan

#### 1. `ValidationController.php` — `executeOnuRouter()` (Bug #1)
- Tambah logika stok patch core: jika `patch_core_new_id` ada di payload, kembalikan stok patch core lama ke user dan kurangi stok patch core baru (defensive, tidak aktif sampai form dikirim dengan field tsb).

#### 2. `ValidationController.php` — `executePergantianLayanan()` (Bug #2)
- Tambah logika opsional untuk `router_new_id` dan `patch_core_new_id`: jika payload mengandung field tsb, stok lama dikembalikan dan stok baru dikurangi (defensive).

#### 3. `SpamController.php` — `outSwitch()` (Bug #3)
- Tambah return stok router lama (`+1`) dan kurangi stok router baru (`-1`) saat `outSwitch` dipanggil.

### File yang Diubah
| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/Pages/ValidationController.php` | +54 baris (logika stok di executeOnuRouter & executePergantianLayanan) |
| `app/Http/Controllers/Pages/SpamController.php` | +15 baris (logika stok di outSwitch) |
| `app/Http/Controllers/Pages/ProsedurController.php` | Revert (patch core form tidak jadi ditambah) |
| `resources/views/pages/prosedur/onu-router.blade.php` | Revert (patch core field tidak jadi ditambah) |

### Catatan
- Pergantian perangkat (ONU/Router) **tidak** mengganti patch core fisik → field `patch_core_new_id` tidak ditambahkan ke form.
- Logika di backend bersifat **defensive**: hanya aktif jika payload mengandung field yang sesuai.
- File lain (CustomerController, MacAddressController, PagesController) berisi perbaikan MAC Address normalization dari sesi sebelumnya.

## 2026-07-16 — Redesign Tabel Customer & Relokasi Tombol Copy

### Masalah
- Data pelanggan ditampilkan dalam format **expand/collapse detail** (accordion) yang mengharuskan user mengklik baris untuk melihat info lengkap.
- Tombol **Copy Data** berada di kolom ACTION, membuat kolom action terlalu penuh.

### Perbaikan

#### 1. `resources/views/pages/customer/index.blade.php` — Tabel Full Column
- Hapus semua CSS dan JS terkait expand/collapse detail (`formatDetail`, `toggleSection`, row click handler, detail styles).
- Ubah table headers dari 8 kolom menjadi **35 kolom** yang mencakup seluruh field dari detail expand:
  - Informasi Pelanggan (ID, Nama, NIK, Email, No Telp, Tipe Pelanggan)
  - Informasi Layanan (Tipe Layanan, Router, VLAN, Paket, Mikrotik Radius, Tipe Pembayaran)
  - Informasi Jaringan (MAC Address, Nama WiFi, Password WiFi, PPPoE Username, PPPoE Password)
  - Alamat Instalasi (Kampung, Desa, RT, RW, Kecamatan, Kabupaten/Kota, ODC, ODP, OLT, Lokasi)
  - Dokumen & Admin (Foto KTP, Organisasi, Diinput Oleh, Diubah Oleh, Created, Updated, Action)
- Update DataTable `columns` config dan `order` index sesuai kolom baru.
- Perbaiki `order` index dari `[10, 'desc']` menjadi `[32, 'desc']`.

#### 2. `app/DataTables/Customer/CustomerDataTable.php` — Relokasi Copy Button
- Hapus blok `copy-btn` dari kolom `action` (server-side).
- Tambahkan `copy-btn` di render function kolom `DT_RowIndex` (frontend blade), bersanding dengan checkbox di kolom NO.

### File yang Diubah
| File | Perubahan |
|------|-----------|
| `resources/views/pages/customer/index.blade.php` | -730 baris (hapus detail expand), +35 kolom table, relokasi copy button ke NO column |
| `app/DataTables/Customer/CustomerDataTable.php` | -8 baris (hapus copy button dari action column) |

### Catatan
- Table menggunakan `table-responsive` (horizontal scroll) untuk menampung 35 kolom, sesuai pattern menu lain (OLT, ODP, dll).
- Tombol Copy Data sekarang berada di kolom NO paling kiri, bersebelahan dengan checkbox seleksi.

## 2026-07-16 — Fix Auto-Detect MAC Address di Wizard Prosedur

### Masalah
Saat input MAC Address dengan karakter heksadesimal tidak valid (contoh: `12:AB:83:00:11:HB`), sistem gagal mengenali sebagai MAC dan salah menganggap sebagai **ID Pelanggan**, menyebabkan pencarian gagal dengan pesan "Pelanggan tidak ditemukan".

### Perbaikan
Tambahkan regex `MAC_LIKE` di 3 file prosedur untuk deteksi format MAC tanpa validasi heksadesimal:

#### 1. `pemutusan.blade.php`, `onu-router.blade.php`, `pergantian-layanan.blade.php`
- Tambah konstanta `MAC_LIKE = /^([0-9a-zA-Z]{2}[:\-]){5}[0-9a-zA-Z]{2}$/` untuk deteksi format MAC secara luas (termasuk karakter non-heksadesimal).
- Ubah prioritas deteksi mode di `input` handler:
  1. `MAC_FULL` valid → "Mode: MAC Address ✓"
  2. `MAC_LIKE` tapi hex tidak valid → "Mode: MAC Address (format tidak valid)" (warna merah)
  3. `MAC_REGEX` (partial) → "Mode: MAC Address (lanjutkan mengetik...)"
  4. Selainnya → "Mode: ID Pelanggan"
- Tambah validasi di `search` handler: jika format seperti MAC tapi hex tidak valid, tampilkan error spesifik dan batalkan pencarian.

### File yang Diubah
| File | Perubahan |
|------|-----------|
| `resources/views/pages/prosedur/pemutusan.blade.php` | +MAC_LIKE regex, update input & search handler |
| `resources/views/pages/prosedur/onu-router.blade.php` | +MAC_LIKE regex, update input & search handler |
| `resources/views/pages/prosedur/pergantian-layanan.blade.php` | +MAC_LIKE regex, update input & search handler |

### Catatan
- Perbaikan diterapkan seragam di ketiga wizard prosedur (pemutusan, onu-router, pergantian-layanan).

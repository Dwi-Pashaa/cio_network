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

## 2026-07-17 — Filter Opsi Pergantian Layanan Berdasarkan Tipe Layanan Saat Ini

### Masalah
Di wizard **Pergantian Layanan** step 2, kedua pilihan "Voucher ke PPPoE" dan "PPPoE ke Voucher" selalu ditampilkan, meskipun tipe layanan pelanggan saat ini sudah diketahui. Hal ini memungkinkan user memilih opsi yang tidak relevan (contoh: memilih "Voucher ke PPPoE" padahal pelanggan sudah PPPoE).

### Perbaikan

#### `resources/views/pages/prosedur/pergantian-layanan.blade.php`
- Tambah fungsi JavaScript `filterServiceOptionsByType()` yang membaca `loadedCustomerData.tipe_layanan` dan menyembunyikan card yang tidak sesuai:
  - Jika tipe = **PPPoE** → sembunyikan card **"Voucher ke PPPoE"**, auto-pilih **"PPPoE ke Voucher"**
  - Jika tipe = **Voucher** → sembunyikan card **"PPPoE ke Voucher"**, auto-pilih **"Voucher ke PPPoE"**
- Panggil `filterServiceOptionsByType()` di handler `$btnWizardNext1.on('click')` setelah switch ke pane 2.
- Tambah `$('.service-card').show()` di tombol restart wizard untuk mereset tampilan kedua card.

### File yang Diubah
| File | Perubahan |
|------|-----------|
| `resources/views/pages/prosedur/pergantian-layanan.blade.php` | +17 baris (fungsi filter + pemanggilan + reset) |

## 2026-07-17 — Implementasi Akses MIC Radius (user_mic_radius_access)

### Masalah
- User index page menampilkan kolom "Mic Radius" (penugasan Operator Mic Radius), tapi belum ada kolom **Akses Mic Rad.** yang independen dari role.
- Tombol login Mix Radius di halaman customer & mic-radius hanya mengecek permission `lihat mic radius`, tidak mengecek apakah user login memiliki akses spesifik ke device tersebut.

### Perbaikan

#### 1. Migration — Tabel `user_mic_radius_access`
Buat tabel pivot baru mengikuti pola `user_router_access` / `user_patch_core_access`:
- `id`, `user_id` (FK → users), `mic_radius_id` (FK → mic_radius), `timestamps`
- Unique constraint `[user_id, mic_radius_id]`

#### 2. `app/Models/User.php` — Relasi Baru
Tambah `micRadiusAccess()` → BelongsToMany via `user_mic_radius_access`

#### 3. `app/Http/Controllers/Pages/UserController.php`
- **store()**: tambah rule `mic_radius_access_id` + sync
- **update()**: tambah rule `mic_radius_access_id` + sync
- **destroy()**: tambah `micRadiusAccess()->detach()`

#### 4. Views User Create/Edit
Tambah select "Akses Data Mic Radius" (multiple, TomSelect) di section Level & Akses.

#### 5. `app/DataTables/UserDataTable.php`
- Tambah eager loading `micRadiusAccess`
- Tambah column `mic_radius_access` (render badges)

#### 6. `resources/views/pages/user/index.blade.php`
- Tambah header kolom "Akses Mic Rad."
- Tambah column DataTable `mic_radius_access`

#### 7. `app/DataTables/Customer/CustomerDataTable.php`
- `mic_radius_info` → nama MIC Radius clickable **hanya jika** user login memiliki akses ke device tsb (`$authUser->micRadiusAccess->contains('id', ...)`)
- Load `micRadiusAccess` di awal method `get()`

#### 8. `app/DataTables/Network/MicRadiusDataTable.php`
- Tombol login **hanya tampil** jika user login memiliki akses ke device tsb
- Load `micRadiusAccess` dan pass via `use ($auth)` ke closure action

### File yang Diubah
| File | Perubahan |
|------|-----------|
| `database/migrations/2026_07_17_000000_create_user_mic_radius_access_table.php` | +35 baris (migration baru) |
| `app/Models/User.php` | +9 baris (relasi micRadiusAccess) |
| `app/Http/Controllers/Pages/UserController.php` | +4 baris (store rule + sync, update rule + sync, destroy detach) |
| `app/DataTables/UserDataTable.php` | +13 baris (eager loading, column, rawColumns) |
| `app/DataTables/Customer/CustomerDataTable.php` | +5 baris (loadMissing, contains check) |
| `app/DataTables/Network/MicRadiusDataTable.php` | +3 baris (loadMissing, use closure, contains check) |
| `resources/views/pages/user/create.blade.php` | +15 baris (select field + TomSelect init) |
| `resources/views/pages/user/edit.blade.php` | +19 baris (selected ids, select field + TomSelect init) |
| `resources/views/pages/user/index.blade.php` | +2 baris (header kolom + column JS, adjust order index) |

### Catatan
- Semua user (termasuk internal) wajib didaftarkan ke `user_mic_radius_access` untuk bisa mengklik nama MIC Radius di customer page atau melihat tombol login di mic-radius page.
- Penugasan Operator Mic Radius (`mix_radius_users`) tetap terpisah dan tidak terpengaruh — hanya mengontrol apakah user bisa login ke Mix Radius. Akses view-only via `micRadiusAccess`.|

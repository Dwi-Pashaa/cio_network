# Rencana Implementasi: Globalisasi Tipe Pembayaran & Filter Visibilitas Publik (Paket & Pembayaran)

Dokumen ini mendeskripsikan rencana perubahan untuk mengubah alur pemilihan Tipe Pembayaran (diambil secara global dan dikonfigurasi melalui Admin) serta menyaring Tipe Paket & Tipe Pembayaran agar pilihan tertentu yang bersifat internal (non-publik seperti postpaid, prepaid, atau paket promo internal) tidak muncul pada formulir pendaftaran pelanggan (publik).

---

## Ringkasan Fitur

1. **Penyaringan & Globalisasi Tipe Pembayaran (`price`):**
   - Menghilangkan ketergantungan tipe pembayaran pada data halaman wilayah (`pages_id`). Pilihan tipe pembayaran akan ditampilkan secara global (seperti e-wallet "DANA", "Bayar di Teknisi").
   - Menambahkan kolom `description` (Textarea) pada master data tipe pembayaran di Admin. Field ini digunakan untuk menyimpan petunjuk/detail pembayaran (contoh: nomor e-wallet DANA, nomor rekening, atas nama, dll).
   - Menambahkan status `is_public` (boolean, default: `true`) di master data tipe pembayaran. Pembayaran bertipe internal (seperti "Postpaid" atau "Prepaid") dapat dinonaktifkan dari publik agar hanya bisa digunakan oleh Admin/Teknisi.
   - Pada form pendaftaran pendaftar (publik), jika user memilih opsi pembayaran yang memiliki `description` (seperti DANA), akan muncul sebuah box/card interaktif berisi petunjuk detail pembayaran tersebut secara otomatis. Opsi pembayaran yang tidak bertanda `is_public` tidak akan dimunculkan di publik.

2. **Pembatasan Tipe Paket Publik (`paket`):**
   - Menambahkan status `is_public` (boolean, default: `true`) di master data tipe paket.
   - Admin dapat menonaktifkan toggle "Tampilkan di Publik" (`is_public = false`) pada paket-paket tertentu (misal: paket promo internal teknisi).
   - Pada form pendaftaran publik, pilihan paket yang muncul hanya paket yang ditautkan ke halaman wilayah terpilih **DAN** memiliki status `is_public` aktif (`true`).
   - Teknisi di panel Admin/Spam tetap dapat memilih seluruh jenis paket & tipe pembayaran (termasuk tipe internal) saat melakukan konfigurasi pendaftaran.

---

## Proposed Changes

### 1. Database Migrations

#### [NEW] `2026_07_22_000003_add_description_and_is_public_to_price_table.php`
Menambahkan kolom `description` dan `is_public` pada tabel `price`:
```php
Schema::table('price', function (Blueprint $table) {
    $table->text('description')->nullable()->after('name');
    $table->boolean('is_public')->default(true)->after('description');
});
```

#### [NEW] `2026_07_22_000004_add_is_public_to_paket_table.php`
Menambahkan kolom `is_public` pada tabel `paket`:
```php
Schema::table('paket', function (Blueprint $table) {
    $table->boolean('is_public')->default(true)->after('name');
});
```

---

### 2. Models

#### [MODIFY] [Price.php](file:///d:/cionetworksolution/cio_network/app/Models/Price.php)
- Menambahkan `description` dan `is_public` ke dalam array `$fillable`.
- Menambahkan cast `'is_public' => 'boolean'`.

#### [MODIFY] [Paket.php](file:///d:/cionetworksolution/cio_network/app/Models/Paket.php)
- Menambahkan `is_public` ke dalam array `$fillable`.
- Menambahkan cast `'is_public' => 'boolean'`.

---

### 3. Controllers

#### [MODIFY] [PriceController.php](file:///d:/cionetworksolution/cio_network/app/Http/Controllers/Pages/PriceController.php)
- Update validasi pada method `store()` dan `update()` untuk menerima kolom `description` (opsional) dan `is_public` (boolean).
- Menambahkan `description` dan `is_public` saat melakukan create/update model `Price`.

#### [MODIFY] [PaketController.php](file:///d:/cionetworksolution/cio_network/app/Http/Controllers/Pages/PaketController.php)
- Update validasi pada method `store()` dan `update()` untuk menerima kolom `is_public` (boolean).
- Menambahkan `is_public` saat melakukan create/update model `Paket`.

#### [MODIFY] [PublicPendaftaranController.php](file:///d:/cionetworksolution/cio_network/app/Http/Controllers/Pages/PublicPendaftaranController.php)
- Pada method `index()`: Ambil data tipe pembayaran yang aktif untuk publik dari database:
  ```php
  $tipePembayaran = Price::where('is_public', true)
      ->select(['id', 'name', 'description'])
      ->get();
  ```
  Kirim data `$tipePembayaran` ini ke view pendaftaran.
- Pada method `getPages()`: Saring relasi `pakets` agar hanya memuat paket yang beratribut publik:
  ```php
  'pakets' => function($q) {
      $q->where('is_public', true);
  }
  ```
  Hapus pemuatan relasi `prices` dari data halaman wilayah karena tipe pembayaran sekarang di-load secara global.

---

### 4. Views

#### [MODIFY] [price/index.blade.php](file:///d:/cionetworksolution/cio_network/resources/views/pages/price/index.blade.php)
- Menambahkan textarea "Keterangan / Detail Pembayaran" dan toggle / checkbox "Tampilkan di Publik" ke dalam form modal tambah dan edit data.
- Memperbarui fungsi Javascript `editModal()` dan `handleSave()` untuk mengisi dan mengirim nilai `description` dan `is_public`.
- (Opsional) Menampilkan status Publik/Internal pada tabel master data tipe pembayaran.

#### [MODIFY] [paket/index.blade.php](file:///d:/cionetworksolution/cio_network/resources/views/pages/paket/index.blade.php)
- Menambahkan checkbox / switch toggle "Tampilkan di Publik" ke dalam form modal tambah dan edit data.
- Memperbarui fungsi Javascript `editModal()` dan `handleSave()` untuk mengisi dan mengirim nilai `is_public`.
- (Opsional) Menampilkan status Publik/Internal pada tabel master data tipe paket.

#### [MODIFY] [public-pendaftaran/wizard.blade.php](file:///d:/cionetworksolution/cio_network/resources/views/pages/public-pendaftaran/wizard.blade.php)
- **Tipe Pembayaran:**
  - Dropdown `#f_price` tidak lagi dikosongkan/diisi via JavaScript halaman wilayah. Dropdown ini dirender secara statis di blade menggunakan `@foreach($tipePembayaran as $tp)`.
  - Simpan nilai `description` ke dalam atribut data opsi: `<option value="{{ $tp->id }}" data-description="{{ $tp->description }}">{{ $tp->name }}</option>`.
  - Tambahkan card detail pembayaran di bawah dropdown `#f_price`:
    ```html
    <div id="payment_info_card" class="alert alert-info mt-2" style="display:none; border-left: 4px solid var(--primary);">
        <h5 class="fw-bold mb-1">Informasi Pembayaran</h5>
        <div id="payment_info_text"></div>
    </div>
    ```
  - Tambahkan event listener di JS pada `#f_price` agar ketika dipilih: jika opsi tersebut memiliki `data-description`, tampilkan card `#payment_info_card` dan isi teksnya; jika tidak, sembunyikan card tersebut.
- **Tipe Paket:**
  - Dropdown `#f_paket` tetap di-populate secara dinamis saat Halaman Wilayah diklik, namun data paket yang diterima dari server otomatis sudah terfilter hanya paket publik.

---

## Verification Plan

### Automated / Database Verification
1. Jalankan perintah migration:
   ```bash
   php artisan migrate
   ```
2. Pastikan kolom baru berhasil ditambahkan pada database.

### Manual Verification
1. **Admin - Master Tipe Pembayaran:**
   - Tambah/edit data Tipe Pembayaran (misal "DANA"), isi kolom detail pembayaran, dan centang "Tampilkan di Publik".
   - Edit data pembayaran lain (misal "Postpaid" atau "Prepaid"), dan hilangkan centang "Tampilkan di Publik".
2. **Admin - Master Tipe Paket:**
   - Buat satu tipe paket baru dan hilangkan centang "Tampilkan di Publik".
   - Hubungkan paket tersebut ke salah satu Halaman Wilayah.
3. **Pendaftaran Publik (Wizard):**
   - Buka halaman pendaftaran.
   - Pastikan opsi pembayaran "Postpaid" dan "Prepaid" **TIDAK muncul** di dropdown **Tipe Pembayaran**.
   - Pilih opsi pembayaran **DANA**, pastikan card petunjuk pembayaran langsung muncul secara interaktif beserta keterangan yang diisi di admin.
   - Pilihlah Halaman Wilayah yang ditautkan ke paket publik & internal di atas.
   - Pastikan pada dropdown **Pilih Paket**, paket berstatus internal **TIDAK muncul** (hanya paket publik yang muncul).

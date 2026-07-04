---

title: "[BUG/FEAT] Fix Wizard Pemutusan: Address ID Duplication and Add Conditional Logic"
labels: bug, enhancement, frontend, backend, wizard
assignees: ''
---

## 📋 Ringkasan

Wizard pemutusan layanan (`pemutusan.blade.php`) memiliki beberapa bug struktural dan kekurangan fitur yang membuat alur kerja tidak berjalan sesuai SOP. Ada **2 bug** dan **3 fitur** yang perlu diselesaikan dalam satu PR.

---

## 🐛 Bug yang Ditemukan

### Bug #1 — Duplikasi `id="w-pane-2"` (Kritis)

**File:** `resources/views/pages/prosedur/pemutusan.blade.php`
**Lokasi:** Baris 132 dan baris 167

```html
<!-- Baris 132: Pane Cari Pelanggan -->
<div class="wizard-pane" id="w-pane-2">

<!-- Baris 167: Pane Detail Profil (seharusnya w-pane-3, tapi ID sama) -->
<div class="wizard-pane" id="w-pane-2">
```

**Dampak:**  
- JavaScript `$('#w-pane-2').addClass('active')` selalu menarget elemen pertama yang ditemukan browser.
- Pane **Detail Profil** tidak pernah tampil — seolah step 3 "hilang".
- Navigasi stepper header (`hs-3`) aktif tapi konten yang ditampilkan salah.

---

### Bug #2 — Detail Profil Tidak Tampil Setelah Pencarian

**Akibat dari Bug #1.** Setelah search customer sukses, seharusnya pindah ke pane `w-pane-3` (Detail Profil), tetapi karena duplikasi ID, pane yang muncul adalah pane search (`w-pane-2`) itu sendiri.

---

## ✨ Fitur yang Dibutuhkan

### Feature #1 — Badge Tipe Layanan di Step 2

Setelah pengguna memilih tipe layanan (Voucher/PPPoE) di Step 1 dan klik **Lanjutkan**, Step 2 (Cari Pelanggan) harus menampilkan badge/reminder tipe yang dipilih agar operator tidak salah mencari.

**Expected UI:**
```
Langkah 2: Verifikasi & Cari Pelanggan
┌─────────────────────────────────┐
│ 🔵 Mencari customer tipe: PPPoE │
└─────────────────────────────────┘
```

---

### Feature #2 — Validasi Kategori Layanan di Step 3

Saat customer ditemukan, sistem harus memvalidasi apakah tipe layanan customer **cocok** dengan pilihan Step 1.

| Pilihan Step 1 | Tipe Layanan Customer | Status |
|---|---|---|
| Voucher | Voucher / Hotspot | ✅ Lanjut |
| Voucher | PPPoE / HOME | ❌ Mismatch — tampilkan peringatan |
| PPPoE | PPPoE / HOME + ada MAC | ✅ Lanjut |
| PPPoE | PPPoE / HOME + **tanpa MAC** | ❌ Peringatan: MAC tidak terdaftar |
| PPPoE | Voucher / Hotspot | ❌ Mismatch — tampilkan peringatan |

Validasi dilakukan dengan membandingkan `selectedPemutusanType` (dari Step 1) terhadap `tipe_layanan_raw` dari response API.

Jika mismatch → tombol **Lanjutkan Prosedur** di Step 3 di-`disabled`, dan muncul alert merah dengan tombol **Kembali & Ubah Tipe**.

---

### Feature #3 — Form Step 4 Kondisional (Berdasarkan Tipe Layanan & Pembayaran)

Step 4 (Bukti Pemutusan) harus menampilkan field yang berbeda tergantung kombinasi tipe layanan (Step 1) dan tipe pembayaran customer (`price.name` dari database, relasi `customer → price`).

| Tipe Layanan (Step 1) | Tipe Pembayaran | Field yang Tampil |
|---|---|---|
| Voucher | *(apapun)* | ✅ Alasan Pemutusan |
| PPPoE | PREPAID | ✅ Alasan + ✅ Foto Bukti Perangkat |
| PPPoE | POSTPAID | ✅ Alasan + ✅ Foto Bukti Perangkat + ✅ Foto Bukti Transfer |

> **Catatan:** Tipe pembayaran diambil dari relasi `customer → price → price.name`. Nilai yang diharapkan: `POSTPAID` atau `PREPAID` (case-insensitive).

---

## 🔧 Root Cause & Solusi Teknis

### Backend — `app/Http/Controllers/Pages/ProsedurController.php`

**Masalah:** Method `searchCustomer()` tidak mengembalikan `tipe_pembayaran` dan `tipe_layanan_raw`.

**Solusi:**

```php
// Tambah 'price' ke eager load (baris 69):
$query = Customer::with([
    'paket', 'type', 'tipePelanggan', 'price',   // <-- tambah 'price'
    'hometown', 'rt', 'rw', 'village', 'district', 'regencie'
]);

// Tambah ke response JSON (setelah baris 128):
'tipe_pembayaran'  => strtoupper($customer->price->name ?? 'UNKNOWN'),
'tipe_layanan_raw' => strtolower($customer->type->name ?? $customer->tipePelanggan->name ?? ''),
```

---

### Frontend — `resources/views/pages/prosedur/pemutusan.blade.php`

**Perubahan HTML yang dibutuhkan:**

1. **Rename ID:** Baris 167 → `id="w-pane-2"` diubah ke `id="w-pane-3"`.
2. **Tambah badge:** Div `#selected-service-badge` di bawah judul pane-2.
3. **Tambah alert mismatch:** Div `#mismatch-alert` di atas card detail di pane-3.
4. **Wrap upload fields:** Bungkus dropzone dalam `#section-bukti-perangkat` dan `#section-bukti-transfer`.
5. **Tambah summary badge:** Div `#step4-summary-badge` di atas form Step 4.

**Perubahan JavaScript yang dibutuhkan:**

1. Fix navigasi: perbaiki semua referensi `w-pane-2`/`w-pane-3` di event handler.
2. Update badge tipe layanan saat pindah ke Step 2.
3. Tambah fungsi `checkServiceCategory(customer)` → dipanggil setelah AJAX search sukses.
4. Tambah fungsi `updateStep4Layout()` → dipanggil saat masuk Step 4.
5. Set/hapus atribut `required` secara dinamis pada input file tersembunyi.
6. Fix restart wizard untuk reset state kondisional baru.

---

## ✅ Acceptance Criteria

- [ ] Navigasi antar step berjalan benar (pane 1 → 2 → 3 → 4).
- [ ] Step 2 menampilkan badge tipe layanan yang dipilih di Step 1.
- [ ] Jika customer tidak cocok dengan tipe layanan Step 1, muncul alert dan tombol Lanjut disabled.
- [ ] Jika customer PPPoE tidak punya MAC address, muncul peringatan.
- [ ] Step 4 hanya tampil field **Alasan** jika tipe = Voucher.
- [ ] Step 4 tampil Alasan + Bukti Perangkat jika tipe = PPPoE + PREPAID.
- [ ] Step 4 tampil Alasan + Bukti Perangkat + Bukti Transfer jika tipe = PPPoE + POSTPAID.
- [ ] Atribut `required` tidak memblokir submit untuk field yang disembunyikan.
- [ ] Restart wizard mereset semua state dengan benar.

---

## 📁 File yang Berubah

| File | Jenis Perubahan |
|------|----------------|
| `app/Http/Controllers/Pages/ProsedurController.php` | Modify — tambah eager load `price` & 2 field response baru |
| `resources/views/pages/prosedur/pemutusan.blade.php` | Modify — fix HTML duplikasi ID + tambah fitur kondisional |

---

## 🧪 Test Cases

| # | Skenario | Expected Result |
|---|----------|-----------------|
| 1 | Pilih Voucher → Cari customer Voucher | Step 3 tanpa warning, Step 4 hanya Alasan |
| 2 | Pilih Voucher → Cari customer PPPoE | Step 3 tampil alert mismatch, tombol Lanjut disabled |
| 3 | Pilih PPPoE → Cari customer PPPoE + POSTPAID | Step 4 tampil Alasan + Perangkat + Transfer |
| 4 | Pilih PPPoE → Cari customer PPPoE + PREPAID | Step 4 tampil Alasan + Perangkat (tanpa Transfer) |
| 5 | Pilih PPPoE → Cari customer tanpa MAC | Step 3 alert: MAC tidak terdaftar |
| 6 | Submit tanpa isi field yang tampil | Validasi HTML5 mencegah submit |
| 7 | Klik "Prosedur Baru" (restart) | Semua input & state bersih |
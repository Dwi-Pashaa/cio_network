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

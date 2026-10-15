# Rencana: Pecah "Data Halaman" Menjadi 2 Menu

## Usulan Nama Menu

| Data | Nama Menu (usulan) | Subjudul | Akses |
|---|---|---|---|
| Lama (`version = legacy`) | **Arsip Halaman** | Daftar halaman layanan versi sebelumnya (hanya lihat) | Hanya tombol **Lihat** |
| Baru (`version = complete`) | **Manajemen Halaman** | Kelola konfigurasi halaman layanan pelanggan | Lihat, Tambah (wizard), Edit, Hapus, Kelola User |

Alternatif nama: *Halaman Legacy / Halaman Aktif*, atau *Halaman Versi Lama / Halaman Layanan*.

---

## Kondisi Saat Ini (Temuan)

- Satu halaman [index.blade.php](file:///d:/cionetworksolution/cio_network/resources/views/pages/pages/index.blade.php) (±2475 baris) dengan 2 tab: `Halaman Lengkap` dan `Halaman Lama (Teknisi)`.
- Tab dikirim lewat `tab_version`, tapi [PagesDataTable::baseQuery](file:///d:/cionetworksolution/cio_network/app/DataTables/Pages/PagesDataTable.php#L115-L177) **tidak memfilter `pages.version`**. Akibatnya kedua tab menampilkan data yang sama, hanya tombol aksinya yang beda.
- Route di [master-pages.php](file:///d:/cionetworksolution/cio_network/routes/master-pages.php#L16-L26) (`update`, `destroy`, `syncUsers`, dll) **tidak punya middleware permission**. Jadi pembatasan "hanya lihat" sekarang cuma terjadi di UI, belum di server.
- Halaman tab lama ikut memuat semua data yang berat (VLAN, ODP, ODC, OLT, paket, user, modal wizard), padahal tidak dipakai di sana.

> [!WARNING]
> Kalau hanya tab yang dipecah tanpa filter `version` dan middleware, user yang cuma boleh melihat Arsip tetap bisa memanggil endpoint edit/hapus secara langsung.

---

## Perubahan yang Diusulkan

### 1. Routes — [master-pages.php](file:///d:/cionetworksolution/cio_network/routes/master-pages.php)

```php
// Arsip Halaman (read-only)
Route::get('/arsip', [PagesArchiveController::class, 'index'])
    ->name('halaman.arsip.index')
    ->middleware('permission:lihat tab halaman lama');

// Manajemen Halaman (full)
Route::prefix('listing')->middleware('permission:lihat tab halaman lengkap')->group(function () {
    Route::get('/', 'index')->name('halaman.index');
    Route::post('/store-complete', 'storeComplete')->middleware('permission:buat halaman lengkap');
    Route::put('/{id}/update-complete', 'updateComplete')->middleware('permission:edit halaman lengkap');
    Route::delete('/{id}/destroy', 'destroy')->middleware('permission:hapus halaman lengkap');
    Route::get('/{id}/users', 'getUsers')->middleware('permission:kelola user halaman');
    Route::post('/{id}/sync-users', 'syncUsers')->middleware('permission:kelola user halaman');
    Route::get('/{id}/show', 'show');
});
```

- `store` / `update` versi lama (non-wizard): **dihapus atau dinonaktifkan**, karena data lama jadi read-only.
- `destroy` / `updateComplete` diberi pengecekan `version === 'complete'`, supaya data arsip tidak bisa diubah lewat ID.

### 2. Controller

- **[NEW]** `app/Http/Controllers/Pages/PagesArchiveController.php`
  - `index()`: kalau request AJAX, panggil DataTable dengan version `legacy`. Kalau bukan, cukup kirim `hometown`, `villages`, `organizations` (ringan, tanpa VLAN/ODP/dll).
- **[MODIFY]** [PagesController::index](file:///d:/cionetworksolution/cio_network/app/Http/Controllers/Pages/PagesController.php#L53-L135)
  - DataTable dipanggil dengan version `complete`.
  - `$totalPages` hanya menghitung `version = complete`.

### 3. DataTable — [PagesDataTable.php](file:///d:/cionetworksolution/cio_network/app/DataTables/Pages/PagesDataTable.php)

- Constructor menerima `string $version` (`legacy` | `complete`), tidak lagi membaca `request('tab_version')`.
- `baseQuery()` ditambah `->where('pages.version', $this->version)`.
- Kolom `action`:
  - `legacy`: hanya tombol **Lihat**.
  - `complete`: Lihat, User, Edit, Hapus (sesuai permission).
- Kolom tampil per versi:
  - Legacy: VLAN, Fitur KTP, Fitur Persetujuan.
  - Complete: Asal VLAN, Support Layanan, User Ditugaskan.

### 4. Views

- **[NEW]** `resources/views/pages/pages/archive.blade.php`
  - Header "Arsip Halaman" dengan badge *Read-only*.
  - Filter Kampung/Desa (dan organisasi jika punya izin), search, tabel, pagination.
  - Tanpa wizard, modal edit, modal user, maupun quick access.
  - Ada info banner: *"Halaman versi lama hanya dapat dilihat. Buat halaman baru di Manajemen Halaman."*
- **[MODIFY]** [index.blade.php](file:///d:/cionetworksolution/cio_network/resources/views/pages/pages/index.blade.php)
  - Judul diganti jadi "Manajemen Halaman".
  - Hapus blok tab (L371–393), variabel `currentTabVersion`, `initializeTabSwitching()`, dan percabangan render kolom legacy.
  - Header kolom ditetapkan permanen: Asal VLAN / Support Layanan / User Ditugaskan.
- *(Opsional)* Filter + pagination yang dipakai bersama dipindah ke partial `pages/pages/partials/_filter-toolbar.blade.php` supaya tidak terduplikasi.

### 5. Navbar — [navbar.blade.php](file:///d:/cionetworksolution/cio_network/resources/views/components/navbar.blade.php#L420-L425)

```blade
@can('lihat tab halaman lengkap')
    <a class="dropdown-item {{ Route::is('halaman.index') ? 'active' : '' }}" href="{{ route('halaman.index') }}">
        Manajemen Halaman
    </a>
@endcan
@can('lihat tab halaman lama')
    <a class="dropdown-item {{ Route::is('halaman.arsip*') ? 'active' : '' }}" href="{{ route('halaman.arsip.index') }}">
        Arsip Halaman
    </a>
@endcan
```

`@canany` induk menu "Prosedur" ditambah 2 permission tersebut.

### 6. Permission

- **Opsi A (disarankan, tanpa migrasi):** pakai permission yang sudah ada.
  - `lihat tab halaman lama` → Arsip Halaman (Teknisi sudah punya).
  - `lihat tab halaman lengkap` → Manajemen Halaman.
- **Opsi B:** migrasi baru untuk mengganti nama menjadi `lihat arsip halaman` dan `lihat manajemen halaman`, supaya label di pengaturan role lebih jelas.

---

## Urutan Pengerjaan

1. DataTable: tambah parameter version + filter query.
2. Buat `PagesArchiveController` + `archive.blade.php`.
3. Rapikan `PagesController::index` + `index.blade.php` (hapus tab).
4. Routes + middleware permission + guard `version` di update/destroy.
5. Navbar.
6. Verifikasi.

## Verifikasi

- Login **Admin**: kedua menu tampil. Arsip hanya berisi data `legacy` dengan tombol Lihat saja. Manajemen hanya berisi data `complete` dan semua aksi berjalan.
- Login **Teknisi**: hanya menu Arsip yang tampil. Akses langsung `/master-pages/listing` harus menghasilkan **403**.
- `DELETE /master-pages/listing/{id_legacy}/destroy` harus ditolak.
- Halaman baru dari wizard harus muncul di Manajemen, bukan di Arsip.
- `php artisan route:list --path=master-pages` untuk mengecek middleware.

---

## Pertanyaan Terbuka

> [!IMPORTANT]
> 1. **Nama menu**: setuju *Arsip Halaman* & *Manajemen Halaman*, atau pilih alternatif lain?
> 2. **Permission**: Opsi A (pakai yang ada) atau Opsi B (rename via migrasi)?
> 3. **Route lama `store`/`update`**: dihapus total, atau disimpan dulu (dinonaktifkan) untuk jaga-jaga?
> 4. Perlu tombol **"Migrasikan ke Versi Baru"** di Arsip (mengubah `legacy` → `complete` lewat wizard)? Atau murni read-only?

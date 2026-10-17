@extends('layouts.app')

@section('title')
    Atur Dokumen Persetujuan
@endsection

@push('css')
<style>
    .persetujuan-card {
        border-radius: 16px;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .persetujuan-card:hover {
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    }
    .badge-status-active {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        font-weight: 700;
        font-size: 0.78rem;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .badge-status-inactive {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
        font-weight: 600;
        font-size: 0.78rem;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-action-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .btn-action-icon:hover {
        transform: translateY(-1px);
    }
    .modal-persetujuan-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        color: #ffffff;
        padding: 1.25rem 1.5rem;
        border: none;
    }
</style>
@endpush

@section('content')
<div class="container-xl" style="padding-top: 1.25rem; padding-bottom: 2.5rem;">

    <!-- Page Header & Action -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1" style="font-size: 1.35rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary me-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Atur Dokumen Persetujuan
            </h2>
            <p class="text-secondary small mb-0">Kelola master dokumen pernyataan persetujuan berlangganan untuk pendaftaran online dan data halaman.</p>
        </div>
        <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm" id="btnTambahDokumen" style="border-radius: 10px; font-weight: 700; padding: 0.55rem 1.25rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Dokumen Baru
        </button>
    </div>

    <!-- Info Banner -->
    <div style="background: rgba(37,99,235,0.04); border: 1.5px dashed rgba(37,99,235,0.25); border-radius: 16px; padding: 1.15rem 1.35rem; margin-bottom: 1.5rem; display: flex; gap: 14px;">
        <div style="width: 38px; height: 38px; border-radius: 50%; background: rgba(37,99,235,0.12); color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>
        </div>
        <div>
            <h4 style="margin: 0 0 4px 0; font-weight: 750; color: #1e3a8a; font-size: 0.92rem;">Aturan Dokumen Persetujuan Organisasi</h4>
            <p style="margin: 0; color: #475569; font-size: 0.83rem; line-height: 1.45;">
                Anda dapat memiliki <strong>banyak dokumen persetujuan</strong>. Namun, <strong>hanya 1 dokumen</strong> yang berstatus <em>Publikasi Aktif</em> untuk pendaftaran online default. Di <strong>Manajemen Halaman</strong>, jika status persetujuan diaktifkan, Anda dapat memilih dokumen tertentu yang akan digunakan saat proses input data pelanggan di halaman tersebut.
            </p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="persetujuan-card">
        <div class="table-responsive">
            <table class="table table-vcenter table-hover mb-0" id="table-persetujuan">
                <thead style="background: #f8fafc; border-bottom: 1.5px solid #e2e8f0;">
                    <tr>
                        <th style="width: 50px; font-weight: 750; color: #475569; font-size: 0.8rem; text-transform: uppercase;">No</th>
                        <th style="font-weight: 750; color: #475569; font-size: 0.8rem; text-transform: uppercase;">Judul Dokumen</th>
                        <th style="font-weight: 750; color: #475569; font-size: 0.8rem; text-transform: uppercase; width: 230px;">Status Publikasi Online</th>
                        <th style="font-weight: 750; color: #475569; font-size: 0.8rem; text-transform: uppercase; width: 160px;">Dipakai di Halaman</th>
                        <th style="font-weight: 750; color: #475569; font-size: 0.8rem; text-transform: uppercase; width: 170px;">Terakhir Diperbarui</th>
                        <th style="width: 150px; font-weight: 750; color: #475569; font-size: 0.8rem; text-transform: uppercase; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="persetujuan-tbody">
                    @forelse ($persetujuans as $index => $doc)
                        <tr id="row-doc-{{ $doc->id }}">
                            <td class="text-muted fw-bold">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $doc->judul }}</div>
                                <small class="text-muted text-truncate d-inline-block" style="max-width: 480px;">
                                    {{ Str::limit(strip_tags($doc->konten), 90) }}
                                </small>
                            </td>
                            <td>
                                @if($doc->is_active)
                                    <span class="badge-status-active">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        Aktif (Pendaftaran Online)
                                    </span>
                                @else
                                    <span class="badge-status-inactive">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                        Draft / Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-blue-lt fw-bold px-2 py-1">
                                    {{ $doc->pages_count ?? 0 }} Halaman
                                </span>
                            </td>
                            <td>
                                <span class="text-secondary small">{{ $doc->updated_at ? $doc->updated_at->format('d M Y, H:i') : '-' }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-1.5">
                                    @if(!$doc->is_active)
                                        <button type="button" class="btn btn-sm btn-outline-success btn-action-icon btn-make-active" data-id="{{ $doc->id }}" data-judul="{{ $doc->judul }}" title="Aktifkan Dokumen Ini untuk Pendaftaran Online">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-action-icon btn-edit-doc" data-id="{{ $doc->id }}" title="Edit Dokumen">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-action-icon btn-delete-doc" data-id="{{ $doc->id }}" data-judul="{{ $doc->judul }}" data-active="{{ $doc->is_active ? 1 : 0 }}" title="Hapus Dokumen">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-secondary opacity-50"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                </div>
                                <h4 class="fw-bold text-dark mb-1">Belum Ada Dokumen Persetujuan</h4>
                                <p class="text-secondary small mb-3">Klik tombol di bawah ini untuk menambahkan dokumen persetujuan pertama Anda.</p>
                                <button type="button" class="btn btn-primary btn-sm px-3" onclick="$('#btnTambahDokumen').click()">
                                    Tambah Dokumen Sekarang
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL FORM TAMBAH / EDIT DOKUMEN PERSETUJUAN -->
<!-- ============================================================ -->
<div class="modal fade" id="modal-persetujuan" tabindex="-1" aria-labelledby="modalPersetujuanTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 18px; overflow: hidden; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <div class="modal-header modal-persetujuan-header">
                <div class="d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    <h5 class="modal-title fw-bold mb-0" id="modalPersetujuanTitle">Tambah Dokumen Persetujuan</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background: #ffffff;">
                <form id="form-persetujuan">
                    <input type="hidden" name="id" id="doc_id">

                    <!-- Judul Dokumen -->
                    <div class="mb-3">
                        <label for="doc_judul" class="form-label fw-bold text-dark small text-uppercase mb-1" style="letter-spacing: 0.05em;">
                            Judul Dokumen <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="judul" id="doc_judul" class="form-control" placeholder="Contoh: Surat Pernyataan Persetujuan Berlangganan Paket Home" required style="border-radius: 8px; border: 1.5px solid #cbd5e1; padding: 0.6rem 0.85rem; font-weight: 500;">
                        <div class="invalid-feedback error_judul"></div>
                    </div>

                    <!-- Status Publikasi -->
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <label class="fw-bold text-dark mb-0 d-block" for="doc_is_active" style="cursor: pointer;">
                                    Aktifkan sebagai Dokumen Publikasi Pendaftaran Online
                                </label>
                                <small class="text-muted d-block mt-0.5">
                                    Jika diaktifkan, dokumen ini menjadi dokumen yang tampil di formulir pendaftaran online secara default. Dokumen lain milik organisasi Anda akan otomatis dinonaktifkan.
                                </small>
                            </div>
                            <div class="form-check form-switch m-0 ms-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="doc_is_active" value="1" style="cursor: pointer; width: 2.7em; height: 1.35em;">
                            </div>
                        </div>
                    </div>

                    <!-- Konten Editor -->
                    <div class="mb-2">
                        <label for="doc_konten" class="form-label fw-bold text-dark small text-uppercase mb-1" style="letter-spacing: 0.05em;">
                            Konten / Isi Dokumen Persetujuan <span class="text-danger">*</span>
                        </label>
                        <textarea name="konten" id="doc_konten" rows="14" class="form-control" placeholder="Tulis isi persetujuan di sini..."></textarea>
                        <div class="invalid-feedback error_konten"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between align-items-center border-top">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Batal</button>
                <button type="button" class="btn btn-primary px-4 d-inline-flex align-items-center gap-2" id="btnSaveDoc" style="border-radius: 8px; font-weight: 700;">
                    <span id="btnSaveDocText">Simpan Dokumen</span>
                    <span id="btnSaveDocLoading" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<!-- TinyMCE Rich Text Editor & SweetAlert2 -->
<script src="{{ asset('libs/tinymce/tinymce.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let editorInstance = null;

    document.addEventListener("DOMContentLoaded", function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#doc_konten',
                height: 480,
                menubar: true,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | fullscreen preview code',
                skin: 'oxide',
                content_css: 'default',
                branding: false,
                promotion: false,
                setup: function (editor) {
                    editorInstance = editor;
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                }
            });
        }
    });

    $(document).ready(function() {
        const BASE_URL = "{{ route('persetujuan.index') }}";
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Buka Modal Tambah
        $('#btnTambahDokumen').on('click', function() {
            resetDocForm();
            $('#modalPersetujuanTitle').text('Tambah Dokumen Persetujuan Baru');
            $('#modal-persetujuan').modal('show');
        });

        // Buka Modal Edit
        $(document).on('click', '.btn-edit-doc', function() {
            const id = $(this).data('id');
            resetDocForm();
            $('#modalPersetujuanTitle').text('Edit Dokumen Persetujuan');

            $.get(`${BASE_URL}/${id}/edit`, function(res) {
                if (res.status === 'success' && res.data) {
                    const doc = res.data;
                    $('#doc_id').val(doc.id);
                    $('#doc_judul').val(doc.judul);
                    $('#doc_is_active').prop('checked', !!doc.is_active);

                    if (editorInstance) {
                        editorInstance.setContent(doc.konten || '');
                    } else {
                        $('#doc_konten').val(doc.konten || '');
                    }

                    $('#modal-persetujuan').modal('show');
                }
            }).fail(function() {
                Swal.fire('Error', 'Gagal memuat data dokumen.', 'error');
            });
        });

        // Simpan (Tambah / Update)
        $('#btnSaveDoc').on('click', function() {
            if (editorInstance) {
                tinymce.triggerSave();
            }

            const docId = $('#doc_id').val();
            const isEdit = !!docId;
            const targetUrl = isEdit ? `${BASE_URL}/${docId}/update` : `${BASE_URL}/store`;
            const method = isEdit ? 'PUT' : 'POST';

            let formData = {
                _token: csrfToken,
                _method: method,
                judul: $('#doc_judul').val(),
                is_active: $('#doc_is_active').is(':checked') ? 1 : 0,
                konten: $('#doc_konten').val()
            };

            // Loading state
            $('#btnSaveDoc').prop('disabled', true);
            $('#btnSaveDocText').addClass('d-none');
            $('#btnSaveDocLoading').removeClass('d-none');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('').hide();

            $.ajax({
                url: targetUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#modal-persetujuan').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Dokumen persetujuan berhasil disimpan.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(key => {
                            $(`#doc_${key}`).addClass('is-invalid');
                            $(`.error_${key}`).text(errors[key][0]).show();
                        });
                    } else {
                        Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan dokumen.', 'error');
                    }
                },
                complete: function() {
                    $('#btnSaveDoc').prop('disabled', false);
                    $('#btnSaveDocText').removeClass('d-none');
                    $('#btnSaveDocLoading').addClass('d-none');
                }
            });
        });

        // Jadikan Dokumen Aktif (Toggle Active)
        $(document).on('click', '.btn-make-active', function() {
            const id = $(this).data('id');
            const judul = $(this).data('judul');

            Swal.fire({
                title: 'Aktifkan Dokumen Ini?',
                text: `Dokumen "${judul}" akan dijadikan dokumen default untuk pendaftaran online. Dokumen aktif sebelumnya akan dinonaktifkan.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Aktifkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `${BASE_URL}/${id}/toggle-active`,
                        type: 'POST',
                        data: { _token: csrfToken },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Diaktifkan!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal', xhr.responseJSON?.message || 'Gagal mengaktifkan dokumen.', 'error');
                        }
                    });
                }
            });
        });

        // Hapus Dokumen
        $(document).on('click', '.btn-delete-doc', function() {
            const id = $(this).data('id');
            const judul = $(this).data('judul');
            const isActive = $(this).data('active') == 1;

            let warnText = `Apakah Anda yakin ingin menghapus dokumen "${judul}"?`;
            if (isActive) {
                warnText += ` Perhatian: Dokumen ini sedang aktif sebagai default online. Jika dihapus, dokumen lain akan otomatis diaktifkan.`;
            }

            Swal.fire({
                title: 'Hapus Dokumen?',
                text: warnText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `${BASE_URL}/${id}/destroy`,
                        type: 'POST',
                        data: {
                            _token: csrfToken,
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal', xhr.responseJSON?.message || 'Gagal menghapus dokumen.', 'error');
                        }
                    });
                }
            });
        });

        function resetDocForm() {
            $('#doc_id').val('');
            $('#doc_judul').val('');
            $('#doc_is_active').prop('checked', false);
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('').hide();
            if (editorInstance) {
                editorInstance.setContent('');
            } else {
                $('#doc_konten').val('');
            }
        }
    });
</script>
@endpush

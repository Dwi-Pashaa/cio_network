@extends('layouts.app')

@section('title')
    Atur Dokumen Persetujuan
@endsection

@section('content')
<div class="container-xl" style="padding-top: 1rem; padding-bottom: 2rem;">

    @if(session('success'))
        <div class="alert alert-success" style="border-radius: 12px; display: flex; align-items: center; gap: 10px; padding: 1rem; border-left: 5px solid #16a34a; background-color: #f0fdf4; color: #15803d; border-top: none; border-right: none; border-bottom: none; margin-bottom: 1.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><polyline points="12 8 12 12 14 14"/><path d="M9 12l2 2 4-4"/>
            </svg>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Info Banner -->
    <div style="background: rgba(37,99,235,0.04); border: 1.5px dashed rgba(37,99,235,0.2); border-radius: 16px; padding: 1.25rem; margin-bottom: 1.5rem; display: flex; gap: 12px;">
        <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(37,99,235,0.1); color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>
        </div>
        <div>
            <h4 style="margin: 0 0 4px 0; font-weight: 750; color: #1e3a8a; font-size: 0.9rem;">Informasi Dokumen Persetujuan</h4>
            <p style="margin: 0; color: #475569; font-size: 0.8rem; line-height: 1.4;">
                Dokumen ini merupakan Surat Pernyataan Persetujuan Berlangganan yang akan ditampilkan kepada pelanggan baru saat melakukan pendaftaran secara online. Anda hanya perlu memiliki 1 data dokumen aktif untuk organisasi Anda.
            </p>
        </div>
    </div>

    <div class="row">
        <!-- Editor Column -->
        <div class="col-lg-12" style="margin-bottom: 1.5rem;">
            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02); padding: 1.75rem;">
                <h3 style="color: #0f172a; font-weight: 750; font-size: 1.05rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    Atur Dokumen Persetujuan Aktif
                </h3>

                <form action="{{ route('persetujuan.store') }}" method="POST">
                    @csrf

                    <!-- Judul Persetujuan -->
                    <div style="margin-bottom: 1.25rem;">
                        <label for="input-judul" style="display: block; font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Judul Dokumen</label>
                        <input type="text" name="judul" id="input-judul" class="form-control" placeholder="Contoh: Surat Pernyataan Persetujuan Berlangganan" value="{{ old('judul', $persetujuan->judul ?? '') }}" required style="border-radius: 8px; border: 1.5px solid #cbd5e1; padding: 0.6rem 0.85rem; color: #0f172a;">
                        @error('judul')
                            <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; font-weight: 600;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Status Publikasi</label>
                        <label class="form-check form-switch" style="padding-left: 2.5rem; margin-top: 0.5rem;">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $persetujuan->is_active ?? false) ? 'checked' : '' }} style="cursor: pointer; width: 2.5em; height: 1.25em;">
                            <span class="form-check-label" style="font-size: 0.85rem; color: #475569; font-weight: 600; cursor: pointer; user-select: none;">
                                Aktifkan dokumen ini untuk pendaftaran online
                            </span>
                        </label>
                        @error('is_active')
                            <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; font-weight: 600;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Konten Editor -->
                    <div style="margin-bottom: 1.5rem;">
                        <label for="konten" style="display: block; font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Konten / Isi Persetujuan</label>
                        <textarea name="konten" id="konten" rows="15" style="width: 100%; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.95rem; line-height: 1.5; padding: 1rem; color: #0f172a; resize: vertical;" placeholder="Tulis isi persetujuan di sini...">{{ old('konten', $persetujuan->konten ?? '') }}</textarea>
                        @error('konten')
                            <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; font-weight: 600;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1.5px solid #f1f5f9; padding-top: 1.25rem; margin-top: 1.5rem;">
                        <button type="submit" class="btn btn-primary" style="border-radius: 10px; padding: 0.6rem 1.5rem; font-weight: 700; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.15); transition: all 0.2s;">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<!-- TinyMCE Rich Text Editor -->
<script src="{{ asset('libs/tinymce/tinymce.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#konten',
                height: 550,
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
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                }
            });
        }
    });
</script>
@endpush

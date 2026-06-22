@extends('layouts.app')

@section('title')
    Template Chat Prosedur
@endsection

@section('content')
<div class="container-xl" style="padding-top: 1rem; padding-bottom: 2rem;">

    <!-- Top Action Row -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 1.25rem;">
        <a href="{{ route('prosedur.templates.create') }}" class="btn-sop-submit" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; font-size: 0.85rem; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #fff; text-decoration: none; border-radius: 8px; box-shadow: 0 4px 10px -2px rgba(37,99,235,0.25);">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Template
        </a>
    </div>

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
            <h4 style="color: #0f172a; font-weight: 750; font-size: 0.9rem; margin-bottom: 0.25rem;">Petunjuk Sinkronisasi</h4>
            <p style="color: #475569; font-size: 0.825rem; margin-bottom: 0; line-height: 1.4;">
                Setiap perubahan template chat akan langsung berdampak pada pesan berikutnya yang dikirim oleh sistem. Silakan pastikan variabel placeholder (seperti <code style="font-family: monospace; font-size: 0.75rem; background: #e2e8f0; padding: 2px 4px; border-radius: 4px; color: #0f172a;">{customer_name}</code>) ditulis dengan format kurung kurawal yang tepat agar data pelanggan dapat terisi secara otomatis.
            </p>
        </div>
    </div>

    <!-- Template Cards -->
    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02); overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 1.5px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
            <h3 style="color: #0f172a; font-weight: 750; font-size: 1.05rem; margin-bottom: 0; display: flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                Daftar Template Chat
            </h3>
            <span style="font-size: 0.74rem; font-weight: 700; background: rgba(37,99,235,0.08); color: #2563eb; padding: 4px 10px; border-radius: 20px;">
                {{ $templates->count() }} Template Aktif
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table class="table" style="margin-bottom: 0; width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: #475569; text-transform: uppercase; border-bottom: 1.5px solid #f1f5f9;">Nama Template</th>
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: #475569; text-transform: uppercase; border-bottom: 1.5px solid #f1f5f9;">Kode / Identifier</th>
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: #475569; text-transform: uppercase; border-bottom: 1.5px solid #f1f5f9;">Deskripsi Penggunaan</th>
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: #475569; text-transform: uppercase; border-bottom: 1.5px solid #f1f5f9; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $tmpl)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                            <td style="padding: 1.25rem 1.5rem; border-bottom: none; vertical-align: middle;">
                                <div style="font-weight: 750; color: #0f172a; font-size: 0.925rem;">{{ $tmpl->name }}</div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: none; vertical-align: middle;">
                                <code style="font-family: monospace; font-size: 0.8rem; font-weight: 700; color: #0f172a; background: #f1f5f9; padding: 4px 8px; border-radius: 6px; border: 1px solid #e2e8f0;">{{ $tmpl->code }}</code>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: none; color: #64748b; font-size: 0.85rem; vertical-align: middle; max-width: 400px; line-height: 1.4;">
                                {{ Str::limit(strtok($tmpl->description, "\n"), 120) }}
                            </td>
                            <td style="padding: 1.25rem 1.5rem; border-bottom: none; text-align: right; vertical-align: middle;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                                    <a href="{{ route('prosedur.templates.edit', $tmpl->id) }}" class="btn-sop-submit" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; font-size: 0.8rem; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #fff; text-decoration: none; border-radius: 8px; box-shadow: 0 4px 10px -2px rgba(37,99,235,0.25);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                        </svg>
                                        Ubah
                                    </a>
                                    <form action="{{ route('prosedur.templates.destroy', $tmpl->id) }}" method="POST" style="display: inline-block; margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-delete-template" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; font-size: 0.8rem; background: #fee2e2; color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.15); border-radius: 8px; cursor: pointer; transition: all 0.2s;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2 2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 3rem 1.5rem; text-align: center; color: #94a3b8; border-bottom: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem; color: #cbd5e1;">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                                <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 0.25rem;">Tidak ada template ditemukan.</div>
                                <div style="font-size: 0.85rem;">Silakan jalankan seeder template terlebih dahulu.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('.btn-delete-template').on('click', function(e) {
            const form = $(this).closest('form');
            Swal.fire({
                title: "Hapus Template?",
                text: "Apakah Anda yakin ingin menghapus template chat ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: 'btn btn-danger px-4 mx-2',
                    cancelButton: 'btn btn-link link-secondary px-4'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

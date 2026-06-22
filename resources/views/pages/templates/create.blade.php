@extends('layouts.app')

@section('title')
    Tambah Template Chat
@endsection

@section('content')
<div class="container-xl" style="padding-top: 1rem; padding-bottom: 2rem;">

    <div class="row">
        <!-- Editor Column -->
        <div class="col-lg-8" style="margin-bottom: 1.5rem;">
            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02); padding: 1.75rem;">
                <h3 style="color: #0f172a; font-weight: 750; font-size: 1.05rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                    Buat Template Baru
                </h3>

                <form action="{{ route('prosedur.templates.store') }}" method="POST">
                    @csrf

                    <div style="margin-bottom: 1.25rem;">
                        <label for="input-name" style="display: block; font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Nama Template</label>
                        <input type="text" name="name" id="input-name" class="form-control" placeholder="Contoh: Notifikasi Pembatalan Prosedur" value="{{ old('name') }}" required style="border-radius: 8px; border: 1.5px solid #cbd5e1; padding: 0.6rem 0.85rem; color: #0f172a;">
                        @error('name')
                            <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; font-weight: 600;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label for="input-code" style="display: block; font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Kode / Identifier</label>
                        <select name="code" id="input-code" class="form-select" required style="border-radius: 8px; border: 1.5px solid #cbd5e1; padding: 0.6rem 0.85rem; color: #0f172a;">
                            @if(count($availableCodes) > 0)
                                <option value="" disabled selected>Pilih Kode Identifier...</option>
                                @foreach($availableCodes as $value => $label)
                                    <option value="{{ $value }}" {{ old('code') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            @else
                                <option value="" disabled selected>Semua kode identifier default sudah digunakan</option>
                            @endif
                        </select>
                        <small style="color: #64748b; font-size: 0.75rem; margin-top: 0.25rem; display: block;">Kode identifier digunakan untuk memetakan template ini ke pengiriman notifikasi yang sesuai di sistem.</small>
                        @error('code')
                            <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; font-weight: 600;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label for="input-description" style="display: block; font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Deskripsi Penggunaan</label>
                        <textarea name="description" id="input-description" rows="3" class="form-control" placeholder="Deskripsikan kapan template ini dikirim..." style="border-radius: 8px; border: 1.5px solid #cbd5e1; padding: 0.6rem 0.85rem; color: #0f172a; resize: vertical;">{{ old('description') }}</textarea>
                        @error('description')
                            <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; font-weight: 600;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="template-body" style="display: block; font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Isi Pesan Template</label>
                        <textarea name="template" id="template-body" rows="10" style="width: 100%; border-radius: 12px; border: 1.5px solid #cbd5e1; font-family: 'Courier New', Courier, monospace; font-size: 0.95rem; line-height: 1.5; padding: 1rem; color: #0f172a; resize: vertical;" placeholder="Tulis isi pesan template di sini..." required>{{ old('template') }}</textarea>
                        @error('template')
                            <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; font-weight: 600;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                        <a href="{{ route('prosedur.templates.index') }}" class="btn-sop-back" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; border-radius: 10px; text-decoration: none; background: #f1f5f9; color: #475569; font-weight: 700; font-size: 0.85rem; border: 1.5px solid #e2e8f0; transition: all 0.2s;">
                            Batal
                        </a>
                        <button type="submit" class="btn-sop-submit" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 20px; border-radius: 10px; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #fff; border: none; font-weight: 700; font-size: 0.85rem; cursor: pointer; box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3); transition: all 0.2s;">
                            Buat Template
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar / Variables Column -->
        <div class="col-lg-4">
            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02); padding: 1.5rem; position: sticky; top: 1.5rem;">
                <h3 style="color: #0f172a; font-weight: 750; font-size: 1.05rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    Variabel Dinamis
                </h3>
                <p style="color: #64748b; font-size: 0.8rem; margin-bottom: 1.25rem; line-height: 1.4;">Klik pada label variabel di bawah ini untuk memasukkannya ke dalam kursor teks pesan Anda secara otomatis.</p>

                <!-- List of variables (All variables compiled) -->
                <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 450px; overflow-y: auto; padding-right: 4px;">
                    @php
                        $vars = [
                            '{customer_id}' => 'ID Pelanggan (CSTMRxxxx)',
                            '{customer_name}' => 'Nama lengkap pelanggan',
                            '{customer_address}' => 'Alamat lengkap pelanggan',
                            '{customer_phone}' => 'No HP/WA pelanggan',
                            '{service_type}' => 'Tipe Layanan Pelanggan (PPPoE/Voucher)',
                            '{customer_service_type}' => 'Tipe layanan (Voucher/PPPoE)',
                            '{procedure_type}' => 'Nama Prosedur (Pergantian Perangkat, dll.)',
                            '{technician_name}' => 'Nama Teknisi pengaju',
                            '{organization_name}' => 'Organisasi pengaju',
                            '{submission_date}' => 'Tanggal/Waktu pengajuan',
                            '{details}' => 'Detail data perubahan spesifik',
                            '{validation_link}' => 'Link URL Halaman Validasi',
                            '{validator_name}' => 'Nama Validator aktif / yang menolak',
                            '{validation_level}' => 'Level Validator aktif / yang menolak',
                            '{validator_label}' => 'Label Validator aktif',
                            '{validation_notes}' => 'Catatan persetujuan / alasan penolakan',
                            '{execution_date}' => 'Tanggal/Waktu eksekusi final'
                        ];
                    @endphp

                    @foreach($vars as $placeholder => $desc)
                        <div class="var-badge" data-placeholder="{{ $placeholder }}" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 0.6rem 0.85rem; display: flex; flex-direction: column; cursor: pointer; transition: all 0.2s;">
                            <span style="font-family: monospace; font-size: 0.825rem; font-weight: 750; color: #2563eb; margin-bottom: 2px;">{{ $placeholder }}</span>
                            <span style="color: #64748b; font-size: 0.725rem;">{{ $desc }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    $(document).ready(function() {
        // Variable badge click event
        $('.var-badge').on('click', function() {
            const placeholder = $(this).data('placeholder');
            const $textarea = $('#template-body');
            const textarea = $textarea[0];
            
            // Insert placeholder at cursor position
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = $textarea.val();
            const before = text.substring(0, start);
            const after = text.substring(end, text.length);
            
            $textarea.val(before + placeholder + after);
            $textarea.focus();
            
            // Put cursor right after the inserted placeholder
            const newCursorPos = start + placeholder.length;
            textarea.setSelectionRange(newCursorPos, newCursorPos);
        });

        // Hover animation
        $('.var-badge').hover(
            function() {
                $(this).css({
                    'border-color': '#2563eb',
                    'background': 'rgba(37,99,235,0.02)',
                    'transform': 'translateY(-1px)',
                    'box-shadow': '0 2px 8px rgba(37,99,235,0.06)'
                });
            },
            function() {
                $(this).css({
                    'border-color': '#e2e8f0',
                    'background': '#f8fafc',
                    'transform': 'none',
                    'box-shadow': 'none'
                });
            }
        );
    });
</script>
@endpush
@endsection

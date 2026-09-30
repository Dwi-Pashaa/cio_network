@extends('layouts.app')

@section('title')
    Pengaturan Notifikasi
@endsection

@section('content')
<div class="container-xl" style="padding-top: 1.5rem; padding-bottom: 2.5rem;">

    @if(session('success'))
        <div class="alert alert-success" style="border-radius: 12px; display: flex; align-items: center; gap: 10px; padding: 1rem; border-left: 5px solid #16a34a; background-color: #f0fdf4; color: #15803d; border-top: none; border-right: none; border-bottom: none; margin-bottom: 1.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><polyline points="12 8 12 12 14 14"/><path d="M9 12l2 2 4-4"/>
            </svg>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Header Banner -->
    <div style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); border-radius: 20px; padding: 1.75rem; margin-bottom: 2rem; color: #ffffff; box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.25);">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.2); padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    Pusat Notifikasi & Integrasi
                </div>
                <h2 style="margin: 0 0 6px 0; font-weight: 800; font-size: 1.5rem; letter-spacing: -0.02em;">Pengaturan Saluran Notifikasi</h2>
                <p style="margin: 0; color: #dbeafe; font-size: 0.9rem; line-height: 1.4;">
                    Pilih bagaimana Anda ingin menerima pemberitahuan otomatis: <strong>WhatsApp Fonnte API</strong>, <strong>Email</strong>, atau <strong>Keduanya Secara Bersamaan</strong>.
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <button type="button" class="btn btn-light" id="btn-test-send" onclick="doTestSend()" style="border-radius: 12px; font-weight: 750; color: #1e40af; padding: 0.65rem 1.25rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: none; cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                    Kirim Notifikasi Tes
                </button>
            </div>
        </div>
    </div>

    <form action="{{ route('notification.setting.update') }}" method="POST" id="form-notification-setting">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Left Column: Channel Selection & Contact Data -->
            <div class="col-lg-8" style="margin-bottom: 1.5rem;">
                
                <!-- Card 1: Saluran Pengiriman Utama -->
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02); padding: 1.75rem; margin-bottom: 1.5rem;">
                    <h3 style="color: #0f172a; font-weight: 750; font-size: 1.1rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 10px;">
                        <span style="width: 32px; height: 32px; border-radius: 10px; background: rgba(37,99,235,0.1); color: #2563eb; display: inline-flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </span>
                        Pilih Saluran Pengiriman Notifikasi
                    </h3>
                    <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 1.5rem;">
                        Tentukan media tujuan setiap kali ada update pengajuan prosedur, tiket troubleshoot, atau pendaftaran.
                    </p>

                    <!-- Radio Option Cards -->
                    <div class="row g-3">
                        <!-- Option 1: WhatsApp Saja -->
                        <div class="col-md-6">
                            <label class="channel-card {{ old('channel', $setting->channel ?? 'both') === 'whatsapp' ? 'selected' : '' }}" style="display: block; border: 2px solid #e2e8f0; border-radius: 16px; padding: 1.25rem; cursor: pointer; transition: all 0.2s ease; position: relative;">
                                <input type="radio" name="channel" value="whatsapp" {{ old('channel', $setting->channel ?? 'both') === 'whatsapp' ? 'checked' : '' }} style="position: absolute; top: 1.25rem; right: 1.25rem; width: 1.2rem; height: 1.2rem; accent-color: #2563eb;">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                    <div style="width: 40px; height: 40px; border-radius: 12px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                        💬
                                    </div>
                                    <div>
                                        <div style="font-weight: 750; color: #0f172a; font-size: 0.95rem;">WhatsApp Saja</div>
                                        <div style="font-size: 0.75rem; color: #16a34a; font-weight: 600;">Fonnte API Gateway</div>
                                    </div>
                                </div>
                                <div style="color: #64748b; font-size: 0.8rem; line-height: 1.4;">
                                    Notifikasi hanya dikirimkan ke nomor WhatsApp terdaftar Anda.
                                </div>
                            </label>
                        </div>

                        <!-- Option 2: Email Saja -->
                        <div class="col-md-6">
                            <label class="channel-card {{ old('channel', $setting->channel ?? 'both') === 'email' ? 'selected' : '' }}" style="display: block; border: 2px solid #e2e8f0; border-radius: 16px; padding: 1.25rem; cursor: pointer; transition: all 0.2s ease; position: relative;">
                                <input type="radio" name="channel" value="email" {{ old('channel', $setting->channel ?? 'both') === 'email' ? 'checked' : '' }} style="position: absolute; top: 1.25rem; right: 1.25rem; width: 1.2rem; height: 1.2rem; accent-color: #2563eb;">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                    <div style="width: 40px; height: 40px; border-radius: 12px; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                        ✉️
                                    </div>
                                    <div>
                                        <div style="font-weight: 750; color: #0f172a; font-size: 0.95rem;">Email Saja</div>
                                        <div style="font-size: 0.75rem; color: #4338ca; font-weight: 600;">SMTP Mailer Server</div>
                                    </div>
                                </div>
                                <div style="color: #64748b; font-size: 0.8rem; line-height: 1.4;">
                                    Notifikasi hanya dikirimkan ke alamat email terdaftar Anda.
                                </div>
                            </label>
                        </div>

                        <!-- Option 3: WhatsApp & Email (Both) -->
                        <div class="col-md-12">
                            <label class="channel-card {{ old('channel', $setting->channel ?? 'both') === 'both' ? 'selected' : '' }}" style="display: block; border: 2px solid #2563eb; background: rgba(37,99,235,0.03); border-radius: 16px; padding: 1.25rem; cursor: pointer; transition: all 0.2s ease; position: relative;">
                                <div style="position: absolute; top: -10px; left: 20px; background: #2563eb; color: #ffffff; font-size: 0.65rem; font-weight: 750; text-transform: uppercase; padding: 2px 10px; border-radius: 10px; letter-spacing: 0.05em;">
                                    Rekomendasi Terbaik
                                </div>
                                <input type="radio" name="channel" value="both" {{ old('channel', $setting->channel ?? 'both') === 'both' ? 'checked' : '' }} style="position: absolute; top: 1.25rem; right: 1.25rem; width: 1.2rem; height: 1.2rem; accent-color: #2563eb;">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                    <div style="width: 40px; height: 40px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                        ⚡
                                    </div>
                                    <div>
                                        <div style="font-weight: 750; color: #0f172a; font-size: 0.95rem;">WhatsApp & Email (Kirim ke Kedua-Duanya)</div>
                                        <div style="font-size: 0.75rem; color: #2563eb; font-weight: 600;">Simultan Multi-Channel</div>
                                    </div>
                                </div>
                                <div style="color: #64748b; font-size: 0.8rem; line-height: 1.4;">
                                    Sistem akan mengirimkan pesan instan ke WhatsApp dan salinan rapi ke Email Anda secara bersamaan.
                                </div>
                            </label>
                        </div>

                        <!-- Option 4: Nonaktifkan (None) -->
                        <div class="col-md-12">
                            <label class="channel-card {{ old('channel', $setting->channel ?? 'both') === 'none' ? 'selected' : '' }}" style="display: block; border: 2px solid #e2e8f0; border-radius: 16px; padding: 1rem 1.25rem; cursor: pointer; transition: all 0.2s ease; position: relative;">
                                <input type="radio" name="channel" value="none" {{ old('channel', $setting->channel ?? 'both') === 'none' ? 'checked' : '' }} style="position: absolute; top: 1.1rem; right: 1.25rem; width: 1.2rem; height: 1.2rem; accent-color: #64748b;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="font-size: 1.1rem;">🔕</span>
                                    <div>
                                        <span style="font-weight: 700; color: #64748b; font-size: 0.9rem;">Nonaktifkan Notifikasi (None)</span>
                                        <span style="font-size: 0.75rem; color: #94a3b8; margin-left: 8px;">Tidak akan menerima pesan WhatsApp maupun Email</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Kontak Penerima -->
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02); padding: 1.75rem;">
                    <h3 style="color: #0f172a; font-weight: 750; font-size: 1.1rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 10px;">
                        <span style="width: 32px; height: 32px; border-radius: 10px; background: rgba(37,99,235,0.1); color: #2563eb; display: inline-flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        Data Kontak Penerima Notifikasi
                    </h3>
                    <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 1.25rem;">
                        Pastikan data nomor WhatsApp dan Email Anda valid agar notifikasi dapat terkirim tanpa kendala.
                    </p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="input-telp" style="display: block; font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                                No. WhatsApp (Fonnte)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-right: none; border-radius: 10px 0 0 10px; font-weight: 600; color: #64748b;">
                                    +62
                                </span>
                                <input type="text" name="telp" id="input-telp" class="form-control" placeholder="81234567890" value="{{ old('telp', $user->telp) }}" style="border-radius: 0 10px 10px 0; border: 1.5px solid #cbd5e1; padding: 0.65rem 0.85rem; color: #0f172a; font-weight: 600;">
                            </div>
                            @error('telp')
                                <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; font-weight: 600;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="input-email" style="display: block; font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                                Alamat Email
                            </label>
                            <input type="email" name="email" id="input-email" class="form-control" placeholder="nama@domain.com" value="{{ old('email', $user->email) }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 0.65rem 0.85rem; color: #0f172a; font-weight: 600;">
                            @error('email')
                                <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; font-weight: 600;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Category Preferences & Integration Status -->
            <div class="col-lg-4">
                <!-- Card 3: Kategori Notifikasi -->
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02); padding: 1.5rem; margin-bottom: 1.5rem;">
                    <h3 style="color: #0f172a; font-weight: 750; font-size: 1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        Filter Kategori Notifikasi
                    </h3>
                    <p style="color: #64748b; font-size: 0.8rem; line-height: 1.4; margin-bottom: 1.25rem;">
                        Pilih jenis event sistem yang ingin Anda terima:
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        <!-- Prosedur -->
                        <label class="form-check form-switch" style="padding-left: 2.75rem; margin: 0; cursor: pointer;">
                            <input class="form-check-input" type="checkbox" name="notify_prosedur" value="1" {{ old('notify_prosedur', $setting->notify_prosedur ?? true) ? 'checked' : '' }} style="cursor: pointer; width: 2.2em; height: 1.2em;">
                            <span style="font-weight: 650; color: #1e293b; font-size: 0.85rem; display: block;">Prosedur & Validasi</span>
                            <span style="font-size: 0.75rem; color: #64748b; display: block;">Pergantian alat, layanan, dan reset password</span>
                        </label>

                        <!-- Troubleshoot -->
                        <label class="form-check form-switch" style="padding-left: 2.75rem; margin: 0; cursor: pointer;">
                            <input class="form-check-input" type="checkbox" name="notify_troubleshoot" value="1" {{ old('notify_troubleshoot', $setting->notify_troubleshoot ?? true) ? 'checked' : '' }} style="cursor: pointer; width: 2.2em; height: 1.2em;">
                            <span style="font-weight: 650; color: #1e293b; font-size: 0.85rem; display: block;">Tiket Troubleshoot</span>
                            <span style="font-size: 0.75rem; color: #64748b; display: block;">Penugasan tiket & update progress teknisi</span>
                        </label>

                        <!-- Pendaftaran Baru -->
                        <label class="form-check form-switch" style="padding-left: 2.75rem; margin: 0; cursor: pointer;">
                            <input class="form-check-input" type="checkbox" name="notify_pendaftaran" value="1" {{ old('notify_pendaftaran', $setting->notify_pendaftaran ?? true) ? 'checked' : '' }} style="cursor: pointer; width: 2.2em; height: 1.2em;">
                            <span style="font-weight: 650; color: #1e293b; font-size: 0.85rem; display: block;">Pendaftaran Baru</span>
                            <span style="font-size: 0.75rem; color: #64748b; display: block;">Pelanggan baru daftar mandiri via online</span>
                        </label>

                        <!-- Komplain -->
                        <label class="form-check form-switch" style="padding-left: 2.75rem; margin: 0; cursor: pointer;">
                            <input class="form-check-input" type="checkbox" name="notify_complain" value="1" {{ old('notify_complain', $setting->notify_complain ?? true) ? 'checked' : '' }} style="cursor: pointer; width: 2.2em; height: 1.2em;">
                            <span style="font-weight: 650; color: #1e293b; font-size: 0.85rem; display: block;">Komplain Pelanggan</span>
                            <span style="font-size: 0.75rem; color: #64748b; display: block;">Laporan gangguan voucher / WiFi pelanggan</span>
                        </label>
                    </div>
                </div>

                <!-- Card 4: Status Integrasi Server -->
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02); padding: 1.5rem; margin-bottom: 1.5rem;">
                    <h3 style="color: #0f172a; font-weight: 750; font-size: 1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #10b981;">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        Status Layanan Gateway
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 12px; font-size: 0.82rem;">
                        <!-- Fonnte -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 8px; font-weight: 650; color: #1e293b;">
                                <span>📱 WhatsApp Fonnte</span>
                            </div>
                            @if($fonnteTokenConfigured)
                                <span class="badge bg-success-lt" style="font-weight: 700;">Aktif / Siap</span>
                            @else
                                <span class="badge bg-danger-lt" style="font-weight: 700;">Token Kosong</span>
                            @endif
                        </div>

                        <!-- Email SMTP -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 8px; font-weight: 650; color: #1e293b;">
                                <span>📧 Email Mailer</span>
                            </div>
                            @if($mailHostConfigured)
                                <span class="badge bg-success-lt" style="font-weight: 700;">Aktif (SMTP)</span>
                            @else
                                <span class="badge bg-warning-lt" style="font-weight: 700;">Default Mail</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100" style="border-radius: 14px; font-weight: 750; padding: 0.85rem; font-size: 1rem; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('js')
<script>
    function doTestSend() {
        const btnTest = document.getElementById('btn-test-send');
        if (!btnTest) return;

        btnTest.disabled = true;
        const originalHtml = btnTest.innerHTML;
        btnTest.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span> Mengirim Uji Coba...`;

        $.ajax({
            url: "{{ route('notification.setting.test_send') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function (response) {
                btnTest.disabled = false;
                btnTest.innerHTML = originalHtml;

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Uji Coba Berhasil!',
                        text: response.message,
                        confirmButtonColor: '#2563eb'
                    });
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr) {
                btnTest.disabled = false;
                btnTest.innerHTML = originalHtml;

                let errMsg = 'Terjadi kesalahan saat mengirim notifikasi uji coba.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengirim',
                        text: errMsg,
                        confirmButtonColor: '#ef4444'
                    });
                } else {
                    alert(errMsg);
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Highlight active radio card
        const radioCards = document.querySelectorAll('.channel-card');
        radioCards.forEach(card => {
            card.addEventListener('click', function () {
                radioCards.forEach(c => {
                    c.classList.remove('selected');
                    c.style.borderColor = '#e2e8f0';
                    c.style.backgroundColor = '#ffffff';
                });
                this.classList.add('selected');
                this.style.borderColor = '#2563eb';
                this.style.backgroundColor = 'rgba(37,99,235,0.03)';
            });
        });
    });
</script>
@endpush

<style>
    .detection-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        font-weight: 650;
        padding: 8px 12px;
        border-radius: 8px;
        margin-top: 0.65rem;
        width: 100%;
        box-sizing: border-box;
        transition: all 0.3s;
    }
    .detection-badge.badge-success {
        background: #dcfce7;
        color: #16a34a;
        border: 1px solid rgba(22, 163, 74, 0.12);
    }
    .detection-badge.badge-danger {
        background: #fee2e2;
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.12);
    }
    .detection-badge.badge-info {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid rgba(100, 116, 139, 0.12);
    }
</style>

<div class="sop-content-section">
    <div class="sop-header">
        <div class="sop-badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line>
            </svg>
            Instruksi Kerja Interaktif
        </div>
        <h1>Pergantian Perangkat ONU / Router</h1>
        <p>Alur kerja terstandardisasi untuk verifikasi pelanggan, pencatatan MAC address perangkat lama dan baru, serta alokasi perangkat pada router jaringan.</p>
    </div>
    
    <div class="sop-body">
        
        <!-- SUCCESS CARD SCREEN -->
        <div class="success-card" id="onu-success-screen">
            <div style="width: 72px; height: 72px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; box-shadow: 0 10px 25px rgba(22, 163, 74, 0.15);">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </div>
            <h2 style="color: #0f172a; font-weight: 800; font-size: 1.5rem; margin-bottom: 0.5rem;">Request Diajukan!</h2>
            <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 2rem; max-width: 500px; display: inline-block; line-height: 1.6;">
                Request pergantian perangkat ONU/Router untuk pelanggan <strong id="success-cust-id">-</strong> telah masuk ke antrean.
                Menunggu persetujuan dari <strong>4 level validator</strong> sebelum perubahan diterapkan ke sistem.
            </p>
            <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                <button type="button" class="btn-sop-submit" id="btn-restart-wizard" style="background: #0f172a;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67" />
                    </svg>
                    Prosedur Baru
                </button>
            </div>
        </div>

        <!-- STEPPER FORM WIZARD PANEL -->
        <div class="wizard-container" id="onu-wizard">
            
            <!-- Horizontal Stepper Header -->
            <div class="horizontal-stepper">
                <div class="h-step active" id="hs-1">
                    <div class="h-step-node">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <span class="h-step-label">Cari Pelanggan</span>
                </div>
                <div class="h-step-line"></div>
                <div class="h-step" id="hs-2">
                    <div class="h-step-node">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <span class="h-step-label">Detail Profil</span>
                </div>
                <div class="h-step-line"></div>
                <div class="h-step" id="hs-3">
                    <div class="h-step-node">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="8" width="20" height="8" rx="2" ry="2"></rect>
                            <line x1="6" y1="12" x2="6.01" y2="12"></line>
                            <line x1="10" y1="12" x2="10.01" y2="12"></line>
                        </svg>
                    </div>
                    <span class="h-step-label">Konfigurasi MAC</span>
                </div>
                <div class="h-step-line"></div>
                <div class="h-step" id="hs-4">
                    <div class="h-step-node">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <span class="h-step-label">Konfirmasi</span>
                </div>
            </div>

            <!-- Stepper Content Wrapper -->
            <div class="wizard-content-wrapper">
                
                <!-- STEP 1 CONTENT: SEARCH CUSTOMER -->
                <div class="wizard-pane active" id="w-pane-1">
                    <h3 class="pane-title">Langkah 1: Verifikasi &amp; Cari Pelanggan</h3>
                    <p class="pane-desc">Masukkan <strong>ID Pelanggan</strong> atau <strong>MAC Address</strong> — sistem akan mengenali format secara otomatis.</p>

                    <div class="input-group-custom" style="margin-bottom: 1rem;">
                        <label class="form-label-custom" id="search-input-label">ID Pelanggan / MAC Address</label>
                        <div class="search-bar-container">
                            <div style="position: relative; flex-grow: 1;">
                                <input type="text" id="wizard-search-query" class="form-control-custom"
                                    placeholder="Contoh: CSTMR0001 atau AA:BB:CC:DD:EE:FF"
                                    autocomplete="off" spellcheck="false">
                                <div class="input-icon-wrapper" id="search-input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                </div>
                            </div>
                            <button type="button" class="btn-sop-submit" id="btn-wizard-search" style="flex-shrink: 0;">
                                <span id="btn-text-search">Cari</span>
                                <div class="spinner-border spinner-border-sm text-light" id="spinner-wizard-search" role="status" style="display: none; width: 1.1rem; height: 1.1rem; border-width: 2px;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </button>
                        </div>
                        {{-- Mode indicator badge --}}
                        <div id="search-mode-badge" style="margin-top: 0.5rem; display: inline-flex; align-items: center; gap: 5px; font-size: 0.74rem; font-weight: 600; color: #94a3b8; transition: all 0.2s;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <span id="search-mode-text">Ketik untuk mulai pencarian</span>
                        </div>
                    </div>

                    <div class="alert-error" id="wizard-search-alert" style="margin-bottom: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <span id="wizard-search-alert-text">Input tidak boleh kosong!</span>
                    </div>
                </div>
                
                <!-- STEP 2 CONTENT: REVIEW CUSTOMER PROFILE -->
                <div class="wizard-pane" id="w-pane-2">
                    <h3 class="pane-title">Langkah 2: Tinjau Detail Pelanggan</h3>
                    <p class="pane-desc">Pastikan profil pelanggan yang akan melakukan pergantian perangkat sudah benar.</p>
                    
                    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; padding: 1.5rem; margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: 1rem; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02);">
                        <!-- Row 1: ID Pelanggan -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(37, 99, 235, 0.08); color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                </div>
                                <span style="font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">ID Pelanggan</span>
                            </div>
                            <span id="wizard-detail-id" style="font-size: 0.925rem; font-weight: 700; color: #0f172a; font-family: monospace;">-</span>
                        </div>

                        <!-- Row 2: Nama Lengkap -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(37, 99, 235, 0.08); color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                </div>
                                <span style="font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Nama Lengkap</span>
                            </div>
                            <span id="wizard-detail-name" style="font-size: 0.925rem; font-weight: 750; color: #0f172a;">-</span>
                        </div>

                        <!-- Row: Tipe Layanan -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(37, 99, 235, 0.08); color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                                </div>
                                <span style="font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Tipe Layanan</span>
                            </div>
                            <span id="wizard-detail-type" style="font-size: 0.925rem; font-weight: 750; color: #0f172a;">-</span>
                        </div>

                        <!-- Row 3: Layanan Paket -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(37, 99, 235, 0.08); color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="12" x2="2" y2="12"></line><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path><line x1="6" y1="16" x2="6.01" y2="16"></line><line x1="10" y1="16" x2="10.01" y2="16"></line></svg>
                                </div>
                                <span style="font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Layanan Paket</span>
                            </div>
                            <span id="wizard-detail-plan" style="font-size: 0.925rem; font-weight: 750; color: #2563eb;">-</span>
                        </div>

                        <!-- Row 4: Alamat Rumah -->
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-top: 2px;">
                                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(37, 99, 235, 0.08); color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                </div>
                                <span style="font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Alamat Rumah</span>
                            </div>
                            <span id="wizard-detail-address" style="font-size: 0.9rem; font-weight: 600; color: #0f172a; text-align: right; max-width: 60%; line-height: 1.5;">-</span>
                        </div>

                        <!-- Row 5: Status Layanan -->
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(37, 99, 235, 0.08); color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                </div>
                                <span style="font-size: 0.8rem; font-weight: 750; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Status Layanan</span>
                            </div>
                            <span id="wizard-detail-status" style="font-size: 0.8rem; font-weight: 750; background: #cbd5e1; color: #475569; padding: 4px 10px; border-radius: 8px;">-</span>
                        </div>
                    </div>
                    
                    <div class="wizard-actions">
                        <button type="button" class="btn-sop-back" id="btn-back-to-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            Kembali
                        </button>
                        <button type="button" class="btn-sop-submit" id="btn-wizard-next-2">
                            Lanjutkan Prosedur
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </div>
                </div>

                <!-- STEP 3 CONTENT: CONFIG MAC ADDRESS & DETECT ROUTER -->
                <div class="wizard-pane" id="w-pane-3">
                    <h3 class="pane-title">Langkah 3: Konfigurasi Perangkat (MAC Address)</h3>
                    <p class="pane-desc">Masukkan alamat MAC fisik lama dan MAC fisik baru untuk didaftarkan pada router jaringan.</p>
                    
                    <div class="form-grid" style="margin-bottom: 1.5rem;">
                        
                        <!-- MAC Address Lama -->
                        <div class="input-group-custom">
                            <label class="form-label-custom" for="mac-lama">MAC Address Lama</label>
                            <div style="position: relative;">
                                <input type="text" id="mac-lama" class="form-control-custom" placeholder="Contoh: AA:BB:CC:DD:EE:FF" style="padding-left: 2.75rem;" required>
                                <div class="input-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="8" width="20" height="8" rx="2" ry="2"></rect>
                                        <line x1="6" y1="12" x2="6.01" y2="12"></line>
                                        <line x1="10" y1="12" x2="10.01" y2="12"></line>
                                    </svg>
                                </div>
                            </div>
                            <input type="hidden" id="detected-router-lama-id">
                            <input type="hidden" id="detected-router-lama-name">
                            <input type="hidden" id="detected-router-lama-code">
                            <div class="detection-badge badge-info" id="badge-mac-lama">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                Ketik MAC Address untuk mendeteksi Router...
                            </div>
                        </div>

                        <!-- MAC Address Baru -->
                        <div class="input-group-custom">
                            <label class="form-label-custom" for="mac-baru">MAC Address Baru</label>
                            <div style="position: relative;">
                                <input type="text" id="mac-baru" class="form-control-custom" placeholder="Contoh: 11:22:33:44:55:66" style="padding-left: 2.75rem;" required>
                                <div class="input-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="8" width="20" height="8" rx="2" ry="2"></rect>
                                        <line x1="6" y1="12" x2="6.01" y2="12"></line>
                                        <line x1="10" y1="12" x2="10.01" y2="12"></line>
                                    </svg>
                                </div>
                            </div>
                            <input type="hidden" id="detected-router-baru-id">
                            <input type="hidden" id="detected-router-new-name">
                            <input type="hidden" id="detected-router-new-code">
                            <div class="detection-badge badge-info" id="badge-mac-baru">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                Ketik MAC Address untuk mendeteksi Router...
                            </div>
                        </div>

                    </div>
                    
                    <div class="wizard-actions">
                        <button type="button" class="btn-sop-back" id="btn-back-to-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            Kembali
                        </button>
                        <button type="button" class="btn-sop-submit" id="btn-wizard-next-3" disabled style="opacity: 0.6; cursor: not-allowed;">
                            Lanjutkan Prosedur
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </div>
                </div>

                <!-- STEP 4 CONTENT: CONFIRMATION SUMMARY -->
                <div class="wizard-pane" id="w-pane-4">
                    <h3 class="pane-title">Langkah 4: Konfirmasi Detail Pergantian</h3>
                    <p class="pane-desc">Periksa kembali data pergantian perangkat sebelum melakukan penyimpanan ke sistem.</p>
                    
                    <form id="wizard-onu-form">
                        <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; padding: 1.5rem; margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: 1rem; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02);">
                            <!-- Pelanggan -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                                <span style="font-size: 0.8rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Pelanggan</span>
                                <span id="summary-cust-info" style="font-size: 0.925rem; font-weight: 750; color: #0f172a;">-</span>
                            </div>

                            <!-- MAC Address Lama -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                                <span style="font-size: 0.8rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">MAC Address Lama</span>
                                <span id="summary-mac-lama" style="font-size: 0.925rem; font-weight: 700; color: #ef4444; font-family: monospace;">-</span>
                            </div>

                            <!-- Router Lama -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                                <span style="font-size: 0.8rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Router Lama</span>
                                <span id="summary-router-lama" style="font-size: 0.925rem; font-weight: 750; color: #ef4444;">-</span>
                            </div>

                            <!-- MAC Address Baru -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                                <span style="font-size: 0.8rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">MAC Address Baru</span>
                                <span id="summary-mac-baru" style="font-size: 0.925rem; font-weight: 700; color: #16a34a; font-family: monospace;">-</span>
                            </div>

                            <!-- Router Baru -->
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.8rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Router Baru</span>
                                <span id="summary-router-baru" style="font-size: 0.925rem; font-weight: 750; color: #16a34a;">-</span>
                            </div>
                        </div>

                        <div class="alert-error" id="wizard-submit-alert" style="margin-bottom: 1.5rem; display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            <span id="wizard-submit-alert-text">Mohon lengkapi seluruh isian MAC Address terlebih dahulu!</span>
                        </div>

                        <div class="wizard-actions">
                            <button type="button" class="btn-sop-back" id="btn-back-to-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                Kembali
                            </button>
                            <button type="submit" class="btn-sop-submit" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Ajukan Pergantian
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- Confirmation Modal -->
            <div class="sop-modal-overlay" id="confirm-modal">
                <div class="sop-modal-card">
                    <div class="sop-modal-icon" style="background: rgba(37, 99, 235, 0.1); color: #2563eb;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="8" width="20" height="8" rx="2" ry="2"></rect>
                            <line x1="6" y1="12" x2="6.01" y2="12"></line>
                            <line x1="10" y1="12" x2="10.01" y2="12"></line>
                        </svg>
                    </div>
                    <h3 class="sop-modal-title">Simpan Pergantian Perangkat?</h3>
                    <p class="sop-modal-desc">Apakah Anda yakin ingin memperbarui data fisik MAC Address perangkat pelanggan ini? Perubahan akan langsung tercatat di sistem.</p>
                    <div class="sop-modal-actions">
                        <button type="button" class="btn-modal-cancel" id="btn-modal-close">Batal</button>
                        <button type="button" class="btn-modal-confirm" id="btn-modal-confirm" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);">Ya, Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            // ─── INTERACTIVE ONU-ROUTER WIZARD LOGIC ───
            const $searchQuery    = $('#wizard-search-query');
            const $btnSearch      = $('#btn-wizard-search');
            const $spinner        = $('#spinner-wizard-search');
            const $btnText        = $('#btn-text-search');
            const $searchAlert    = $('#wizard-search-alert');
            const $modeBadge      = $('#search-mode-badge');
            const $modeText       = $('#search-mode-text');
            const $inputIcon      = $('#search-input-icon');

            const $wizardDetailId     = $('#wizard-detail-id');
            const $wizardDetailName   = $('#wizard-detail-name');
            const $wizardDetailType   = $('#wizard-detail-type');
            const $wizardDetailPlan   = $('#wizard-detail-plan');
            const $wizardDetailAddress= $('#wizard-detail-address');
            const $wizardDetailStatus = $('#wizard-detail-status');
            const $btnWizardNext2 = $('#btn-wizard-next-2');

            let loadedCustomerData = null;

            $searchAlert.hide();
            $('#wizard-submit-alert').hide();

            // ─── Auto-detect input type as user types ───
            const MAC_REGEX  = /^([0-9a-fA-F]{2}[:\-]){1,}[0-9a-fA-F]{0,2}$/;
            const MAC_FULL   = /^([0-9a-fA-F]{2}[:\-]){5}[0-9a-fA-F]{2}$/;
            // MAC_LIKE: 17-char format XX:XX:XX:XX:XX:XX with at least one non-hex char
            const MAC_LIKE   = /^([0-9a-fA-F]{0,2}[^0-9a-fA-F:\-][0-9a-zA-Z]{0,1}[:\-]|[0-9a-zA-Z]{0,1}[^0-9a-fA-F:\-][0-9a-fA-F]{0,2}[:\-]|[0-9a-fA-F]{2}[:\-]){5}([0-9a-fA-F]{0,2}[^0-9a-fA-F\s][0-9a-zA-Z]{0,1}|[0-9a-zA-Z]{0,1}[^0-9a-fA-F\s][0-9a-fA-F]{0,2}|[0-9a-fA-F]{2})$/;
            // MAC_HAS_SEP: detects any input with colon/dash separator pattern (looks like a MAC being typed)
            const MAC_HAS_SEP = /^[0-9a-zA-Z]{1,2}([:\-][0-9a-zA-Z]{0,2})+$/;

            const svgId  = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
            const svgMac = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="8" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="12" x2="6.01" y2="12"/><line x1="10" y1="12" x2="10.01" y2="12"/></svg>';
            const svgSrch= '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>';

            $searchQuery.on('input', function() {
                const val = $(this).val().trim();
                $searchAlert.hide();

                if (!val) {
                    $modeBadge.css('color', '#94a3b8');
                    $modeText.text('Ketik untuk mulai pencarian');
                    $modeBadge.find('svg').replaceWith($(svgSrch).css({width:'12px',height:'12px'}));
                    $inputIcon.html(svgSrch);
                    return;
                }

                if (MAC_FULL.test(val)) {
                    $modeBadge.css('color', '#7c3aed');
                    $modeText.text('Mode: MAC Address ✓');
                    $modeBadge.find('svg').replaceWith($(svgMac).css({width:'12px',height:'12px'}));
                    $inputIcon.html(svgMac.replace('width="12"','width="18"').replace('height="12"','height="18"'));
                } else if (MAC_HAS_SEP.test(val) && !MAC_REGEX.test(val)) {
                    // Has separator pattern but contains non-hex character
                    $modeBadge.css('color', '#ef4444');
                    $modeText.text('Mode: MAC Address (karakter tidak valid, gunakan 0-9 dan A-F)');
                    $modeBadge.find('svg').replaceWith($(svgMac).css({width:'12px',height:'12px'}));
                    $inputIcon.html(svgMac.replace('width="12"','width="18"').replace('height="12"','height="18"'));
                } else if (MAC_REGEX.test(val)) {
                    $modeBadge.css('color', '#7c3aed');
                    $modeText.text('Mode: MAC Address (lanjutkan mengetik...)');
                    $modeBadge.find('svg').replaceWith($(svgMac).css({width:'12px',height:'12px'}));
                    $inputIcon.html(svgMac.replace('width="12"','width="18"').replace('height="12"','height="18"'));
                } else {
                    $modeBadge.css('color', '#2563eb');
                    $modeText.text('Mode: ID Pelanggan');
                    $modeBadge.find('svg').replaceWith($(svgId).css({width:'12px',height:'12px'}));
                    $inputIcon.html(svgId.replace('width="12"','width="18"').replace('height="12"','height="18"'));
                }
            });

            // ─── SHARED: Load customer into step 2 ───
            function loadCustomerToStep2(data) {
                loadedCustomerData = data;
                $wizardDetailId.text((data.id || '').toUpperCase());
                $wizardDetailName.text(data.name);
                $wizardDetailType.text(data.tipe_layanan);
                $wizardDetailPlan.text(data.paket);
                $wizardDetailAddress.text(data.alamat);

                const statusLower = (data.status || '').toLowerCase();
                if (statusLower === 'active' || statusLower === 'aktif') {
                    $wizardDetailStatus.text('Aktif Berlangganan').css({ 'background': '#dcfce7', 'color': '#16a34a' });
                } else {
                    $wizardDetailStatus
                        .text(data.status.charAt(0).toUpperCase() + data.status.slice(1))
                        .css({ 'background': '#fee2e2', 'color': '#ef4444' });
                }

                $('#hs-1').removeClass('active').addClass('completed');
                $('#hs-2').addClass('active');
                $('.h-step-line').first().addClass('completed');
                $('#w-pane-1').removeClass('active');
                $('#w-pane-2').addClass('active');
            }

            // ─── Search Handler (single unified) ───
            function doSearch() {
                const val = $searchQuery.val().trim();

                if (!val) {
                    $searchAlert.find('span').text('Masukkan ID Pelanggan atau MAC Address!');
                    $searchAlert.css('display', 'flex').hide().slideDown(200);
                    return;
                }

                // Auto-detect search mode
                const isMacValid = MAC_FULL.test(val);
                const isMacHasSep = MAC_HAS_SEP.test(val);
                const isMac      = isMacValid || MAC_REGEX.test(val) || isMacHasSep;
                const searchBy = isMac ? 'mac' : 'id';

                $searchAlert.hide();
                $btnSearch.prop('disabled', true);
                $spinner.show();
                $btnText.hide();

                $.ajax({
                    url: "{{ route('public.prosedur.search_customer') }}",
                    method: 'GET',
                    data: { query: val, search_by: searchBy },
                    success: function(response) {
                        $btnSearch.prop('disabled', false);
                        $spinner.hide();
                        $btnText.show();

                        if (response.status === 'success') {
                            loadCustomerToStep2(response.data);
                        } else {
                            $searchAlert.find('span').text(response.message || 'Pelanggan tidak ditemukan.');
                            $searchAlert.css('display', 'flex').hide().slideDown(200);
                        }
                    },
                    error: function(xhr) {
                        $btnSearch.prop('disabled', false);
                        $spinner.hide();
                        $btnText.show();

                        let errMsg = 'Pelanggan tidak ditemukan.';
                        if (xhr.responseJSON && xhr.responseJSON.message) errMsg = xhr.responseJSON.message;
                        $searchAlert.find('span').text(errMsg);
                        $searchAlert.css('display', 'flex').hide().slideDown(200);
                    }
                });
            }

            $btnSearch.on('click', doSearch);
            $searchQuery.on('keypress', function(e) {
                if (e.which === 13) { e.preventDefault(); doSearch(); }
            });

            // Step 2: Back to Step 1
            $('#btn-back-to-1').on('click', function() {
                // Adjust stepper indicators
                $('#hs-1').addClass('active').removeClass('completed');
                $('#hs-2').removeClass('active');
                $('.h-step-line').first().removeClass('completed');

                // Switch panes
                $('#w-pane-2').removeClass('active');
                $('#w-pane-1').addClass('active');
            });

            // Step 2: Continue to Step 3
            $btnWizardNext2.on('click', function() {
                if (!loadedCustomerData) return;

                // Adjust stepper indicators
                $('#hs-2').removeClass('active').addClass('completed');
                $('#hs-3').addClass('active');
                $('.h-step-line').eq(1).addClass('completed');

                // Switch panes
                $('#w-pane-2').removeClass('active');
                $('#w-pane-3').addClass('active');
            });

            // Step 3: Back to Step 2
            $('#btn-back-to-2').on('click', function() {
                // Adjust stepper indicators
                $('#hs-2').addClass('active').removeClass('completed');
                $('#hs-3').removeClass('active');
                $('.h-step-line').eq(1).removeClass('completed');

                // Switch panes
                $('#w-pane-3').removeClass('active');
                $('#w-pane-2').addClass('active');
            });

            // Step 3: Validation Helper for MAC Addresses
            function isMacAddressSame() {
                const macLama = $('#mac-lama').val().trim().toLowerCase();
                const macBaru = $('#mac-baru').val().trim().toLowerCase();
                
                return (macLama && macBaru && macLama === macBaru);
            }

            // Step 3: Validate MAC address fields to enable/disable continue button
            function validateStep3() {
                const macLama = $('#mac-lama').val().trim();
                const macBaru = $('#mac-baru').val().trim();
                const routerLamaId = $('#detected-router-lama-id').val();
                const routerBaruId = $('#detected-router-baru-id').val();
                const $btnNext = $('#btn-wizard-next-3');

                if (!macLama || !macBaru) {
                    $btnNext.prop('disabled', true).css({ 'opacity': '0.6', 'cursor': 'not-allowed' });
                    return;
                }

                if (macLama.toLowerCase() === macBaru.toLowerCase()) {
                    $btnNext.prop('disabled', true).css({ 'opacity': '0.6', 'cursor': 'not-allowed' });
                    return;
                }

                if (!routerLamaId || !routerBaruId) {
                    $btnNext.prop('disabled', true).css({ 'opacity': '0.6', 'cursor': 'not-allowed' });
                    return;
                }

                $btnNext.prop('disabled', false).css({ 'opacity': '1', 'cursor': 'pointer' });
            }

            // Step 3: AJAX Router Detection for MAC Lama
            let debounceTimerLama;
            $('#mac-lama').on('input', function() {
                validateStep3();
                clearTimeout(debounceTimerLama);
                const mac = $(this).val().trim();
                const $badge = $('#badge-mac-lama');
                
                if (!mac) {
                    $badge.removeClass('badge-success badge-danger').addClass('badge-info')
                        .html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Ketik MAC Address untuk mendeteksi Router...');
                    $('#detected-router-lama-id').val('');
                    validateStep3();
                    return;
                }
                
                $badge.removeClass('badge-success badge-danger').addClass('badge-info')
                    .html('<div class="spinner-border spinner-border-sm text-secondary" role="status" style="width: 0.85rem; height: 0.85rem; border-width: 1.5px; margin-right: 4px; display: inline-block; vertical-align: middle;"></div> Mendeteksi router...');

                debounceTimerLama = setTimeout(function() {
                    if (loadedCustomerData && loadedCustomerData.mac_address) {
                        if (mac.toLowerCase() !== loadedCustomerData.mac_address.toLowerCase()) {
                            $badge.removeClass('badge-info badge-success badge-danger').addClass('badge-danger')
                                .html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> MAC Address Lama tidak cocok dengan MAC Address terdaftar pelanggan!');
                            $('#detected-router-lama-id').val('');
                            validateStep3();
                            return;
                        }
                    }

                    $.ajax({
                        url: "{{ route('public.prosedur.get_router_by_mac') }}",
                        method: 'GET',
                        data: { mac: mac },
                        success: function(response) {
                            if (response.status === 'success') {
                                $badge.removeClass('badge-info badge-danger').addClass('badge-success')
                                    .html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> <strong>Router Terdeteksi:</strong> ' + response.data.router_name + ' (' + response.data.router_code + ')');
                                $('#detected-router-lama-id').val(response.data.router_id);
                                $('#detected-router-lama-name').val(response.data.router_name);
                                $('#detected-router-lama-code').val(response.data.router_code);
                                validateStep3();
                            }
                        },
                        error: function(xhr) {
                            let errMsg = 'MAC Address tidak terdaftar / Router tidak ditemukan';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                  errMsg = xhr.responseJSON.message;
                            }
                            $badge.removeClass('badge-info badge-success').addClass('badge-danger')
                                .html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> ' + errMsg);
                            $('#detected-router-lama-id').val('');
                            $('#detected-router-lama-name').val('');
                            $('#detected-router-lama-code').val('');
                            validateStep3();
                        }
                    });
                }, 600);
            });

            // Step 3: AJAX Router Detection for MAC Baru (w/ validation check)
            let debounceTimerBaru;
            $('#mac-baru').on('input', function() {
                validateStep3();
                clearTimeout(debounceTimerBaru);
                const mac = $(this).val().trim();
                const $badge = $('#badge-mac-baru');
                
                if (!mac) {
                    $badge.removeClass('badge-success badge-danger').addClass('badge-info')
                        .html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Ketik MAC Address untuk mendeteksi Router...');
                    $('#detected-router-baru-id').val('');
                    validateStep3();
                    return;
                }

                if (isMacAddressSame()) {
                    $badge.removeClass('badge-info badge-success').addClass('badge-danger')
                        .html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> MAC Address Baru tidak boleh sama dengan MAC Address Lama!');
                    $('#detected-router-baru-id').val('');
                    validateStep3();
                    return;
                }
                
                $badge.removeClass('badge-success badge-danger').addClass('badge-info')
                    .html('<div class="spinner-border spinner-border-sm text-secondary" role="status" style="width: 0.85rem; height: 0.85rem; border-width: 1.5px; margin-right: 4px; display: inline-block; vertical-align: middle;"></div> Mendeteksi router...');

                debounceTimerBaru = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('public.prosedur.get_router_by_mac') }}",
                        method: 'GET',
                        data: { mac: mac },
                        success: function(response) {
                            if (response.status === 'success') {
                                $badge.removeClass('badge-info badge-danger').addClass('badge-success')
                                    .html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> <strong>Router Terdeteksi:</strong> ' + response.data.router_name + ' (' + response.data.router_code + ')');
                                $('#detected-router-baru-id').val(response.data.router_id);
                                $('#detected-router-new-name').val(response.data.router_name);
                                $('#detected-router-new-code').val(response.data.router_code);
                                validateStep3();
                            }
                        },
                        error: function(xhr) {
                            let errMsg = 'MAC Address tidak terdaftar / Router tidak ditemukan';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                  errMsg = xhr.responseJSON.message;
                            }
                            $badge.removeClass('badge-info badge-success').addClass('badge-danger')
                                .html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> ' + errMsg);
                            $('#detected-router-baru-id').val('');
                            $('#detected-router-new-name').val('');
                            $('#detected-router-new-code').val('');
                            validateStep3();
                        }
                    });
                }, 600);
            });

            // Step 3: Continue to Step 4 (Summary & Confirmation)
            $('#btn-wizard-next-3').on('click', function() {
                const macLama = $('#mac-lama').val().trim();
                const macBaru = $('#mac-baru').val().trim();

                if (!macLama || !macBaru) {
                    alert('Mohon masukkan kedua MAC Address terlebih dahulu!');
                    return;
                }

                if (isMacAddressSame()) {
                    alert('MAC Address Baru tidak boleh sama dengan MAC Address Lama!');
                    return;
                }

                // Gather details for summary step 4
                $('#summary-cust-info').text(loadedCustomerData.id.toUpperCase() + ' — ' + loadedCustomerData.name);
                $('#summary-mac-lama').text(macLama.toUpperCase());
                $('#summary-mac-baru').text(macBaru.toUpperCase());

                // Find router names to display
                const routerLamaName = $('#badge-mac-lama').text().replace('Router Terdeteksi: ', '').trim();
                const routerBaruName = $('#badge-mac-baru').text().replace('Router Terdeteksi: ', '').trim();
                
                let cleanRouterLama = "Tidak terdeteksi";
                if (routerLamaName && !routerLamaName.includes('Ketik MAC') && !routerLamaName.includes('tidak terdaftar')) {
                    cleanRouterLama = routerLamaName;
                }
                
                let cleanRouterBaru = "Tidak terdeteksi";
                if (routerBaruName && !routerBaruName.includes('Ketik MAC') && !routerBaruName.includes('tidak terdaftar')) {
                    cleanRouterBaru = routerBaruName;
                }

                $('#summary-router-lama').text(cleanRouterLama);
                $('#summary-router-baru').text(cleanRouterBaru);

                // Adjust stepper indicators
                $('#hs-3').removeClass('active').addClass('completed');
                $('#hs-4').addClass('active');
                $('.h-step-line').last().addClass('completed');

                // Switch panes
                $('#w-pane-3').removeClass('active');
                $('#w-pane-4').addClass('active');
            });

            // Step 4: Back to Step 3
            $('#btn-back-to-3').on('click', function() {
                // Adjust stepper indicators
                $('#hs-3').addClass('active').removeClass('completed');
                $('#hs-4').removeClass('active');
                $('.h-step-line').last().removeClass('completed');

                // Switch panes
                $('#w-pane-4').removeClass('active');
                $('#w-pane-3').addClass('active');
            });

            // Step 4: Save & Submit Clicked (shows modal confirmation)
            $('#wizard-onu-form').on('submit', function(e) {
                e.preventDefault();
                $('#confirm-modal').addClass('active');
            });

            // Modal: Close
            $('#btn-modal-close').on('click', function() {
                $('#confirm-modal').removeClass('active');
            });

            // Modal: Confirm Clicked -> Submit to queue
            $('#btn-modal-confirm').on('click', function() {
                const $btn = $(this);
                $btn.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('public.prosedur.store') }}",
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        prosedur_type:  'onu-router',
                        customer_id:    loadedCustomerData.db_id,
                        mac_address_old: $('#mac-lama').val().trim(),
                        mac_address_new: $('#mac-baru').val().trim(),
                        router_lama_id:   $('#detected-router-lama-id').val(),
                        router_lama_name: $('#detected-router-lama-name').val(),
                        router_lama_code: $('#detected-router-lama-code').val(),
                        router_new_id:    $('#detected-router-baru-id').val(),
                        router_new_name:  $('#detected-router-new-name').val(),
                        router_new_code:  $('#detected-router-new-code').val(),
                    },
                    success: function(response) {
                        $('#confirm-modal').removeClass('active');

                        // Show pending-queue success screen
                        $('#success-cust-id').text(loadedCustomerData.id.toUpperCase());

                        $('.horizontal-stepper, #onu-wizard').slideUp(300, function() {
                            $('#onu-success-screen').fadeIn(300);
                        });
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.message || 'Terjadi kesalahan, coba lagi.';
                        alert('Gagal mengajukan: ' + msg);
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text('Ya, Simpan');
                    }
                });
            });

            // Modal: Click outside to dismiss
            $('#confirm-modal').on('click', function(e) {
                if (e.target === this) {
                    $(this).removeClass('active');
                }
            });

            $('#btn-restart-wizard').on('click', function() {
                // Clear search input & reset mode badge
                $searchQuery.val('');
                $modeBadge.css('color', '#94a3b8');
                $modeText.text('Ketik untuk mulai pencarian');
                $inputIcon.html('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>');
                $searchAlert.hide();

                // Clear MAC fields
                $('#mac-lama, #mac-baru').val('');
                $('#detected-router-lama-id, #detected-router-baru-id').val('');

                // Reset detail fields
                $wizardDetailId.text('-');
                $wizardDetailName.text('-');
                $wizardDetailType.text('-');
                $wizardDetailPlan.text('-');
                $wizardDetailAddress.text('-');
                $wizardDetailStatus.text('-').css({ 'background': '#cbd5e1', 'color': '#475569' });

                // Clear badges
                $('#badge-mac-lama, #badge-mac-baru').removeClass('badge-success badge-danger').addClass('badge-info')
                    .html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Ketik MAC Address untuk mendeteksi Router...');

                loadedCustomerData = null;
                validateStep3();

                // Reset horizontal stepper indicators
                $('#hs-1').addClass('active').removeClass('completed');
                $('#hs-2, #hs-3, #hs-4').removeClass('active completed');
                $('.h-step-line').removeClass('completed');

                // Switch pane
                $('.wizard-pane').removeClass('active');
                $('#w-pane-1').addClass('active');

                // Toggle screens
                $('#onu-success-screen').hide();
                $('.horizontal-stepper, #onu-wizard').slideDown(300);
            });
        });
    </script>
@endpush

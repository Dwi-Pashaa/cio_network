<style>
    .service-type-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .service-card {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        background: #ffffff;
        position: relative;
    }
    .service-card:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -8px rgba(15, 23, 42, 0.08);
    }
    .service-card.selected {
        border-color: #2563eb;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.02) 0%, rgba(59, 130, 246, 0.01) 100%);
        box-shadow: 0 12px 25px -10px rgba(37, 99, 235, 0.12), 0 0 0 1px rgba(37, 99, 235, 0.05);
    }
    .service-card.selected .card-radio-indicator {
        border-color: #2563eb !important;
        background: #2563eb;
    }
    .service-card.selected .card-radio-indicator div {
        transform: scale(1) !important;
        background: #ffffff !important;
    }
    
    @media (max-width: 640px) {
        .service-type-grid {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }
    }

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
                <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
            </svg>
            Instruksi Kerja Interaktif
        </div>
        <h1>Pergantian Layanan Pelanggan</h1>
        <p>Alur kerja terstandardisasi untuk pergantian jenis layanan pelanggan (Voucher ke PPPoE / PPPoE ke Voucher) pada jaringan.</p>
    </div>
    
    <div class="sop-body">
        
        <!-- SUCCESS CARD SCREEN -->
        <div class="success-card" id="change-success-screen">
            <div style="width: 72px; height: 72px; border-radius: 50%; background: #fef9c3; color: #ca8a04; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; box-shadow: 0 10px 25px rgba(202, 138, 4, 0.15);">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <h2 style="color: #0f172a; font-weight: 800; font-size: 1.5rem; margin-bottom: 0.5rem;">Request Diajukan!</h2>
            <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 2rem; max-width: 500px; display: inline-block; line-height: 1.6;">
                Request pergantian layanan untuk pelanggan <strong id="success-cust-id">-</strong> telah masuk ke antrean.
                Perubahan dari <strong id="success-service-before">-</strong> ke <strong id="success-service-after">-</strong> akan diterapkan setelah <strong>4 level validator</strong> menyetujui.
            </p>
            <div>
                <button type="button" class="btn-sop-submit" id="btn-restart-wizard" style="background: #0f172a;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67" />
                    </svg>
                    Prosedur Baru
                </button>
            </div>
        </div>

        <!-- STEPPER FORM WIZARD PANEL -->
        <div class="wizard-container" id="change-wizard">
            
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
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <polyline points="1 20 1 14 7 14"></polyline>
                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                        </svg>
                    </div>
                    <span class="h-step-label">Pilih Layanan</span>
                </div>
                <div class="h-step-line"></div>
                <div class="h-step" id="hs-3">
                    <div class="h-step-node">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line>
                        </svg>
                    </div>
                    <span class="h-step-label">Config PPPoE</span>
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
                
                <!-- STEP 1 CONTENT: SEARCH & VERIFY CUSTOMER -->
                <div class="wizard-pane active" id="w-pane-1">
                    <h3 class="pane-title">Langkah 1: Verifikasi & Cari Pelanggan</h3>
                    <p class="pane-desc">Masukkan <strong>ID Pelanggan</strong> atau <strong>MAC Address</strong> — sistem akan mengenali format secara otomatis.</p>
                    
                    <div class="input-group-custom" style="margin-bottom: 1.25rem;">
                        <label class="form-label-custom" id="search-input-label">ID Pelanggan / MAC Address</label>
                        <div class="search-bar-container">
                            <div style="position: relative; flex-grow: 1;">
                                <input type="text" id="wizard-search-id" class="form-control-custom" placeholder="Contoh: CSTMR0001 atau AA:BB:CC:DD:EE:FF" autocomplete="off" spellcheck="false">
                                <div class="input-icon-wrapper" id="search-input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                </div>
                            </div>
                            <button type="button" class="btn-sop-submit" id="btn-wizard-search" style="flex-shrink: 0;">
                                <span class="btn-text-search">Cari</span>
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

                    <div class="alert-error" id="wizard-search-alert" style="margin-bottom: 1.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <span id="wizard-search-alert-text">Masukkan ID Pelanggan atau MAC Address!</span>
                    </div>

                    <!-- CUSTOMER DETAILS PANEL (HIDDEN INITIALLY) -->
                    <div id="customer-details-card" style="display: none; background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; padding: 1.5rem; margin-bottom: 1.5rem; flex-direction: column; gap: 1rem; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02);">
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
                        <button type="button" class="btn-sop-submit" id="btn-wizard-next-1" disabled style="opacity: 0.6; cursor: not-allowed;">
                            Lanjutkan Prosedur
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </div>
                </div>
                
                <!-- STEP 2 CONTENT: CHOOSE SERVICE DIRECTION -->
                <div class="wizard-pane" id="w-pane-2">
                    <h3 class="pane-title">Langkah 2: Pilih Jenis Pergantian Layanan</h3>
                    <p class="pane-desc">Pilih arah perpindahan paket dan teknologi konektivitas yang akan diterapkan pada pelanggan.</p>
                    
                    <div class="service-type-grid">
                        <!-- Card 1: Voucher ke PPPoE -->
                        <div class="service-card selected" data-value="voucher-ke-pppoe">
                            <input type="radio" name="tipe_pergantian" id="radio-v-to-p" value="voucher-ke-pppoe" style="display: none;" checked>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(37, 99, 235, 0.08); color: #2563eb; display: flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                        <polyline points="17 6 23 6 23 12"></polyline>
                                    </svg>
                                </div>
                                <div class="card-radio-indicator" style="width: 20px; height: 20px; border-radius: 50%; border: 2px solid #cbd5e1; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                    <div style="width: 10px; height: 10px; border-radius: 50%; background: #2563eb; transform: scale(0); transition: all 0.2s;"></div>
                                </div>
                            </div>
                            <h4 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem 0;">Voucher ke PPPoE</h4>
                            <p style="font-size: 0.85rem; color: #64748b; margin: 0; line-height: 1.5;">Mengubah pelanggan hotspot voucher menjadi akun koneksi PPPoE dengan alokasi bandwidth dedicated.</p>
                        </div>

                        <!-- Card 2: PPPoE ke Voucher -->
                        <div class="service-card" data-value="pppoe-ke-voucher">
                            <input type="radio" name="tipe_pergantian" id="radio-p-to-v" value="pppoe-ke-voucher" style="display: none;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(37, 99, 235, 0.08); color: #2563eb; display: flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                        <polyline points="17 18 23 18 23 12"></polyline>
                                    </svg>
                                </div>
                                <div class="card-radio-indicator" style="width: 20px; height: 20px; border-radius: 50%; border: 2px solid #cbd5e1; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                    <div style="width: 10px; height: 10px; border-radius: 50%; background: #2563eb; transform: scale(0); transition: all 0.2s;"></div>
                                </div>
                            </div>
                            <h4 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem 0;">PPPoE ke Voucher</h4>
                            <p style="font-size: 0.85rem; color: #64748b; margin: 0; line-height: 1.5;">Mengubah akun koneksi PPPoE perumahan menjadi akun hotspot berbasis Voucher prabayar/hotspot.</p>
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

                <!-- STEP 3 CONTENT: CONFIG PPPOE -->
                <div class="wizard-pane" id="w-pane-3">
                    <h3 class="pane-title">Langkah 3: Konfigurasi Akun PPPoE</h3>
                    <p class="pane-desc">Lengkapi data konfigurasi modem/router dan akun PPPoE baru untuk pelanggan.</p>
                    
                    <div class="form-grid" style="margin-bottom: 1.5rem;">
                        <!-- Nama Wifi -->
                        <div class="input-group-custom">
                            <label class="form-label-custom" for="name_wifi">Nama Wifi (SSID)</label>
                            <div style="position: relative;">
                                <input type="text" id="name_wifi" class="form-control-custom" placeholder="Contoh: Wifi_Home" style="padding-left: 2.75rem;" required>
                                <div class="input-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><circle cx="12" cy="20" r="1"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Password Wifi -->
                        <div class="input-group-custom">
                            <label class="form-label-custom" for="password_wifi">Password Wifi</label>
                            <div style="position: relative;">
                                <input type="text" id="password_wifi" class="form-control-custom" placeholder="Minimal 8 Karakter" style="padding-left: 2.75rem;" required>
                                <div class="input-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- PPPoE Username -->
                        <div class="input-group-custom">
                            <label class="form-label-custom" for="pppoe_username">PPPoE Username</label>
                            <div style="position: relative;">
                                <input type="text" id="pppoe_username" class="form-control-custom" placeholder="Contoh: cstmr_username" style="padding-left: 2.75rem;" required>
                                <div class="input-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- PPPoE Password -->
                        <div class="input-group-custom">
                            <label class="form-label-custom" for="pppoe_password">PPPoE Password</label>
                            <div style="position: relative;">
                                <input type="text" id="pppoe_password" class="form-control-custom" placeholder="Password Koneksi" style="padding-left: 2.75rem;" required>
                                <div class="input-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Tipe Paket -->
                        <div class="input-group-custom">
                            <label class="form-label-custom" for="paket_id">Tipe Paket</label>
                            <div style="position: relative;">
                                <select id="paket_id" class="form-control-custom" style="padding-left: 2.75rem; appearance: none; -webkit-appearance: none;" required>
                                    <option value="">Pilih Paket</option>
                                    @foreach ($pakets as $pkt)
                                        <option value="{{ $pkt->id }}">{{ $pkt->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                                    </svg>
                                </div>
                                <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: #94a3b8;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Tipe Pembayaran -->
                        <div class="input-group-custom">
                            <label class="form-label-custom" for="price_id">Tipe Pembayaran</label>
                            <div style="position: relative;">
                                <select id="price_id" class="form-control-custom" style="padding-left: 2.75rem; appearance: none; -webkit-appearance: none;" required>
                                    <option value="">Pilih Pembayaran</option>
                                    @foreach ($prices as $prc)
                                        <option value="{{ $prc->id }}">{{ $prc->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="4" width="20" height="16" rx="2" ry="2"/><line x1="12" y1="4" x2="12" y2="20"/><line x1="2" y1="12" x2="22" y2="12"/>
                                    </svg>
                                </div>
                                <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: #94a3b8;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Mix Radius -->
                        <div class="input-group-custom form-group-full">
                            <label class="form-label-custom" for="mic_radius_id">Mix Radius</label>
                            <div style="position: relative;">
                                <select id="mic_radius_id" class="form-control-custom" style="padding-left: 2.75rem; appearance: none; -webkit-appearance: none;" required>
                                    <option value="">Pilih Mix Radius</option>
                                    @foreach ($micRadiuses as $mc)
                                        <option value="{{ $mc->id }}">{{ $mc->code }} - {{ $mc->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M12 6v6l4 2"/>
                                    </svg>
                                </div>
                                <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: #94a3b8;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </div>
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
                    <p class="pane-desc">Periksa kembali data pergantian layanan sebelum melakukan penyimpanan ke sistem.</p>
                    
                    <form id="wizard-change-form">
                        <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; padding: 1.5rem; margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: 1rem; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02);">
                            <!-- Pelanggan -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                                <span style="font-size: 0.8rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Pelanggan</span>
                                <span id="summary-cust-info" style="font-size: 0.925rem; font-weight: 750; color: #0f172a;">-</span>
                            </div>

                            <!-- Tipe Pergantian -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                                <span style="font-size: 0.8rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Tipe Pergantian</span>
                                <span id="summary-tipe-pergantian" style="font-size: 0.925rem; font-weight: 750; color: #2563eb;">-</span>
                            </div>

                            <!-- PPPoE Additional Summary (Conditional) -->
                            <div id="pppoe-summary-details" style="display: none; flex-direction: column; gap: 1rem;">
                                <!-- Nama Wifi -->
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                                    <span style="font-size: 0.8rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Nama Wifi (SSID)</span>
                                    <span id="summary-wifi-name" style="font-size: 0.925rem; font-weight: 750; color: #0f172a;">-</span>
                                </div>
                                <!-- Password Wifi -->
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                                    <span style="font-size: 0.8rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Password Wifi</span>
                                    <span id="summary-wifi-password" style="font-size: 0.925rem; font-weight: 750; color: #0f172a;">-</span>
                                </div>
                                <!-- PPPoE Username -->
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                                    <span style="font-size: 0.8rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">PPPoE Username</span>
                                    <span id="summary-pppoe-username" style="font-size: 0.925rem; font-weight: 750; color: #0f172a;">-</span>
                                </div>
                                <!-- PPPoE Password -->
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                                    <span style="font-size: 0.8rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">PPPoE Password</span>
                                    <span id="summary-pppoe-password" style="font-size: 0.925rem; font-weight: 750; color: #0f172a;">-</span>
                                </div>
                                <!-- Tipe Paket -->
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                                    <span style="font-size: 0.8rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Tipe Paket</span>
                                    <span id="summary-paket" style="font-size: 0.925rem; font-weight: 750; color: #2563eb;">-</span>
                                </div>
                                <!-- Tipe Pembayaran -->
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0;">
                                    <span style="font-size: 0.8rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Tipe Pembayaran</span>
                                    <span id="summary-price" style="font-size: 0.925rem; font-weight: 750; color: #2563eb;">-</span>
                                </div>
                                <!-- Mix Radius -->
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 0.8rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Mix Radius</span>
                                    <span id="summary-mix-radius" style="font-size: 0.925rem; font-weight: 750; color: #0f172a;">-</span>
                                </div>
                            </div>
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
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <polyline points="1 20 1 14 7 14"></polyline>
                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                        </svg>
                    </div>
                    <h3 class="sop-modal-title">Konfirmasi Pergantian</h3>
                    <p class="sop-modal-desc">Apakah Anda yakin ingin memproses pergantian layanan pelanggan ini? Tindakan ini akan mengupdate tipe koneksi dan konfigurasi perangkat.</p>
                    <div class="sop-modal-actions">
                        <button type="button" class="btn-modal-cancel" id="btn-modal-close">Batal</button>
                        <button type="button" class="btn-modal-confirm" id="btn-modal-confirm" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);">Ya, Proses</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            // ─── INTERACTIVE SERVICE CHANGE WIZARD LOGIC ───
            const $wizardSearchId = $('#wizard-search-id');
            const $btnWizardSearch = $('#btn-wizard-search');
            const $spinnerWizardSearch = $('#spinner-wizard-search');
            const $wizardSearchAlert = $('#wizard-search-alert');
            const $customerDetailsCard = $('#customer-details-card');
            
            const $wizardDetailId = $('#wizard-detail-id');
            const $wizardDetailName = $('#wizard-detail-name');
            const $wizardDetailType = $('#wizard-detail-type');
            const $wizardDetailPlan = $('#wizard-detail-plan');
            const $wizardDetailAddress = $('#wizard-detail-address');
            const $wizardDetailStatus = $('#wizard-detail-status');
            const $btnWizardNext1 = $('#btn-wizard-next-1');

            const $modeBadge      = $('#search-mode-badge');
            const $modeText       = $('#search-mode-text');
            const $inputIcon      = $('#search-input-icon');

            let selectedServiceType = 'voucher-ke-pppoe';
            let loadedCustomerData = null;

            // Hide alert on load
            $wizardSearchAlert.hide();

            // ─── Auto-detect input type as user types ───
            const MAC_REGEX = /^([0-9a-fA-F]{2}[:\-]){1,}[0-9a-fA-F]{0,2}$/;
            const MAC_FULL  = /^([0-9a-fA-F]{2}[:\-]){5}[0-9a-fA-F]{2}$/;

            const svgId  = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
            const svgMac = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="8" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="12" x2="6.01" y2="12"/><line x1="10" y1="12" x2="10.01" y2="12"/></svg>';
            const svgSrch= '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>';

            $wizardSearchId.on('input', function() {
                const val = $(this).val().trim();
                $wizardSearchAlert.hide();

                if (!val) {
                    $modeBadge.css('color', '#94a3b8');
                    $modeText.text('Ketik untuk mulai pencarian');
                    $modeBadge.find('svg').replaceWith($(svgSrch).css({width:'12px',height:'12px'}));
                    $inputIcon.html(svgSrch);
                    return;
                }

                if (MAC_REGEX.test(val)) {
                    $modeBadge.css('color', '#7c3aed');
                    $modeText.text(MAC_FULL.test(val) ? 'Mode: MAC Address ✓' : 'Mode: MAC Address (lanjutkan mengetik...)');
                    $modeBadge.find('svg').replaceWith($(svgMac).css({width:'12px',height:'12px'}));
                    $inputIcon.html(svgMac.replace('width="12"','width="18"').replace('height="12"','height="18"'));
                } else {
                    $modeBadge.css('color', '#2563eb');
                    $modeText.text('Mode: ID Pelanggan');
                    $modeBadge.find('svg').replaceWith($(svgId).css({width:'12px',height:'12px'}));
                    $inputIcon.html(svgId.replace('width="12"','width="18"').replace('height="12"','height="18"'));
                }
            });

            // Step 2: Click selection card to toggle active state
            $('.service-card').on('click', function() {
                $('.service-card').removeClass('selected');
                $(this).addClass('selected');
                $(this).find('input[type="radio"]').prop('checked', true);
                selectedServiceType = $(this).data('value');
            });

            // Step 1: Search Button Clicked
            $btnWizardSearch.on('click', function() {
                const customerId = $wizardSearchId.val().trim();
                
                if (!customerId) {
                    $wizardSearchAlert.find('span').text('Masukkan ID Pelanggan atau MAC Address!');
                    $wizardSearchAlert.css('display', 'flex').hide().slideDown(200);
                    return;
                }

                // Auto-detect search mode
                const isMac = MAC_FULL.test(customerId) || MAC_REGEX.test(customerId);
                const searchBy = isMac ? 'mac' : 'id';

                $wizardSearchAlert.hide();
                $btnWizardSearch.prop('disabled', true);
                $spinnerWizardSearch.show();
                $('.btn-text-search').hide();

                // Make AJAX request to search customer details
                $.ajax({
                    url: "{{ route('public.prosedur.search_customer') }}",
                    method: 'GET',
                    data: { query: customerId, search_by: searchBy },
                    success: function(response) {
                        $btnWizardSearch.prop('disabled', false);
                        $spinnerWizardSearch.hide();
                        $('.btn-text-search').show();

                        if (response.status === 'success') {
                            loadedCustomerData = response.data;
                            
                            // Set step details
                            $wizardDetailId.text(loadedCustomerData.id.toUpperCase());
                            $wizardDetailName.text(loadedCustomerData.name);
                            $wizardDetailType.text(loadedCustomerData.tipe_layanan);
                            $wizardDetailPlan.text(loadedCustomerData.paket);
                            $wizardDetailAddress.text(loadedCustomerData.alamat);
                            
                            // Style status badge
                            if (loadedCustomerData.status.toLowerCase() === 'active' || loadedCustomerData.status.toLowerCase() === 'aktif') {
                                $wizardDetailStatus.text('Aktif Berlangganan')
                                    .css({ 'background': '#dcfce7', 'color': '#16a34a' });
                            } else {
                                $wizardDetailStatus.text(loadedCustomerData.status.charAt(0).toUpperCase() + loadedCustomerData.status.slice(1))
                                    .css({ 'background': '#fee2e2', 'color': '#ef4444' });
                            }

                            // Show customer details card
                            $customerDetailsCard.slideDown(250).css('display', 'flex');
                            
                            // Enable next step button
                            $btnWizardNext1.prop('disabled', false).css({ 'opacity': '1', 'cursor': 'pointer' });
                        } else {
                            $customerDetailsCard.slideUp(200);
                            $btnWizardNext1.prop('disabled', true).css({ 'opacity': '0.6', 'cursor': 'not-allowed' });
                            $wizardSearchAlert.find('span').text(response.message || 'Pelanggan tidak ditemukan.');
                            $wizardSearchAlert.css('display', 'flex').hide().slideDown(200);
                        }
                    },
                    error: function(xhr) {
                        $btnWizardSearch.prop('disabled', false);
                        $spinnerWizardSearch.hide();
                        $('.btn-text-search').show();
                        $customerDetailsCard.slideUp(200);
                        $btnWizardNext1.prop('disabled', true).css({ 'opacity': '0.6', 'cursor': 'not-allowed' });

                        let errMsg = 'Pelanggan tidak ditemukan.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        }
                        $wizardSearchAlert.find('span').text(errMsg);
                        $wizardSearchAlert.css('display', 'flex').hide().slideDown(200);
                    }
                });
            });

            // Enter key searches customer too
            $wizardSearchId.on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $btnWizardSearch.click();
                }
            });

            // Step 1: Continue to Step 2
            $btnWizardNext1.on('click', function() {
                if (!loadedCustomerData) return;

                // Adjust stepper indicators
                $('#hs-1').removeClass('active').addClass('completed');
                $('#hs-2').addClass('active');
                $('.h-step-line').eq(0).addClass('completed');

                // Switch panes
                $('#w-pane-1').removeClass('active');
                $('#w-pane-2').addClass('active');
            });

            // Step 2: Back to Step 1
            $('#btn-back-to-1').on('click', function() {
                // Adjust stepper indicators
                $('#hs-1').addClass('active').removeClass('completed');
                $('#hs-2').removeClass('active');
                $('.h-step-line').eq(0).removeClass('completed');

                // Switch panes
                $('#w-pane-2').removeClass('active');
                $('#w-pane-1').addClass('active');
            });

            // Step 2: Continue to Step 3 / Step 4
            $('#btn-wizard-next-2').on('click', function() {
                if (selectedServiceType === 'pppoe-ke-voucher') {
                    // Go directly to Step 4 (Confirmation)
                    $('#hs-2').removeClass('active').addClass('completed');
                    $('#hs-3').addClass('completed');
                    $('#hs-4').addClass('active');
                    $('.h-step-line').eq(1).addClass('completed');
                    $('.h-step-line').eq(2).addClass('completed');

                    // Gather details for summary step 4
                    let beforeText = "PPPoE";
                    let afterText = "Voucher";
                    $('#summary-cust-info').text(loadedCustomerData.id.toUpperCase() + ' — ' + loadedCustomerData.name);
                    $('#summary-tipe-pergantian').text(beforeText + ' ➔ ' + afterText);
                    $('#pppoe-summary-details').hide();

                    // Switch panes
                    $('#w-pane-2').removeClass('active');
                    $('#w-pane-4').addClass('active');
                } else {
                    // Go to Step 3 (PPPoE Config)
                    $('#hs-2').removeClass('active').addClass('completed');
                    $('#hs-3').addClass('active');
                    $('.h-step-line').eq(1).addClass('completed');

                    // Switch panes
                    $('#w-pane-2').removeClass('active');
                    $('#w-pane-3').addClass('active');
                }
            });

            // Step 3 validation function
            function validateStep3() {
                const nameWifi = $('#name_wifi').val().trim();
                const passwordWifi = $('#password_wifi').val().trim();
                const pppoeUsername = $('#pppoe_username').val().trim();
                const pppoePassword = $('#pppoe_password').val().trim();
                const paketId = $('#paket_id').val();
                const priceId = $('#price_id').val();
                const micRadiusId = $('#mic_radius_id').val();

                const isValid = nameWifi && passwordWifi && pppoeUsername && pppoePassword && paketId && priceId && micRadiusId;

                if (isValid) {
                    $('#btn-wizard-next-3').prop('disabled', false).css({ 'opacity': '1', 'cursor': 'pointer' });
                } else {
                    $('#btn-wizard-next-3').prop('disabled', true).css({ 'opacity': '0.6', 'cursor': 'not-allowed' });
                }
            }

            // Attach input event listeners
            $('#name_wifi, #password_wifi, #pppoe_username, #pppoe_password').on('input', validateStep3);
            $('#paket_id, #price_id, #mic_radius_id').on('change', validateStep3);

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

            // Step 3: Continue to Step 4 (Confirmation)
            $('#btn-wizard-next-3').on('click', function() {
                if (!loadedCustomerData) return;

                // Determine before/after label based on selected type
                let beforeText = "Voucher";
                let afterText = "PPPoE";

                // Gather details for summary step 4
                $('#summary-cust-info').text(loadedCustomerData.id.toUpperCase() + ' — ' + loadedCustomerData.name);
                $('#summary-tipe-pergantian').text(beforeText + ' ➔ ' + afterText);

                // Populate PPPoE config details into summary
                $('#summary-wifi-name').text($('#name_wifi').val().trim());
                $('#summary-wifi-password').text($('#password_wifi').val().trim());
                $('#summary-pppoe-username').text($('#pppoe_username').val().trim());
                $('#summary-pppoe-password').text($('#pppoe_password').val().trim());
                $('#summary-paket').text($('#paket_id option:selected').text());
                $('#summary-price').text($('#price_id option:selected').text());
                $('#summary-mix-radius').text($('#mic_radius_id option:selected').text());
                $('#pppoe-summary-details').css('display', 'flex');

                // Adjust stepper indicators
                $('#hs-3').removeClass('active').addClass('completed');
                $('#hs-4').addClass('active');
                $('.h-step-line').eq(2).addClass('completed');

                // Switch panes
                $('#w-pane-3').removeClass('active');
                $('#w-pane-4').addClass('active');
            });

            // Step 4: Back to Step 3 / Step 2
            $('#btn-back-to-3').on('click', function() {
                if (selectedServiceType === 'pppoe-ke-voucher') {
                    // Go back directly to Step 2
                    $('#hs-2').addClass('active').removeClass('completed');
                    $('#hs-3').removeClass('completed');
                    $('#hs-4').removeClass('active');
                    $('.h-step-line').eq(1).removeClass('completed');
                    $('.h-step-line').eq(2).removeClass('completed');

                    // Switch panes
                    $('#w-pane-4').removeClass('active');
                    $('#w-pane-2').addClass('active');
                } else {
                    // Go back to Step 3
                    $('#hs-3').addClass('active').removeClass('completed');
                    $('#hs-4').removeClass('active');
                    $('.h-step-line').eq(2).removeClass('completed');

                    // Switch panes
                    $('#w-pane-4').removeClass('active');
                    $('#w-pane-3').addClass('active');
                }
            });

            // Step 4: Form Submission Clicked (shows modal confirmation)
            $('#wizard-change-form').on('submit', function(e) {
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
                        _token:            $('meta[name="csrf-token"]').attr('content'),
                        prosedur_type:     'pergantian-layanan',
                        customer_id:       loadedCustomerData.db_id,
                        service_type:      selectedServiceType,
                        paket_id:          $('#paket_id').val() || null,
                        paket_name:        $('#paket_id option:selected').text() || null,
                        price_id:          $('#price_id').val() || null,
                        price_name:        $('#price_id option:selected').text() || null,
                        mic_radius_id:     $('#mic_radius_id').val() || null,
                        mic_radius_name:   $('#mic_radius_id option:selected').text() || null,
                        pppoe_username:    $('#pppoe_username').val() || null,
                        pppoe_password:    $('#pppoe_password').val() || null,
                        name_wifi:         $('#name_wifi').val() || null,
                        password_wifi:     $('#password_wifi').val() || null,
                    },
                    success: function(response) {
                        $('#confirm-modal').removeClass('active');

                        // Set success screen details
                        $('#success-cust-id').text(loadedCustomerData.id.toUpperCase());
                        $('#success-service-before').text(selectedServiceType === 'pppoe-ke-voucher' ? 'PPPoE' : 'Voucher');
                        $('#success-service-after').text(selectedServiceType === 'pppoe-ke-voucher' ? 'Voucher' : 'PPPoE');

                        // Slide up wizard container and slide down success screen
                        $('.horizontal-stepper, #change-wizard').slideUp(300, function() {
                            $('#change-success-screen').fadeIn(300);
                        });
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.message || 'Terjadi kesalahan, coba lagi.';
                        alert('Gagal mengajukan: ' + msg);
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text('Ya, Ganti Layanan');
                    }
                });
            });


            // Modal: Click outside to dismiss
            $('#confirm-modal').on('click', function(e) {
                if (e.target === this) {
                    $(this).removeClass('active');
                }
            });

            // Restart Wizard Button
            $('#btn-restart-wizard').on('click', function() {
                // Clear fields
                $wizardSearchId.val('');
                $wizardDetailType.text('-');
                
                $modeBadge.css('color', '#94a3b8');
                $modeText.text('Ketik untuk mulai pencarian');
                $modeBadge.find('svg').replaceWith($(svgSrch).css({width:'12px',height:'12px'}));
                $inputIcon.html(svgSrch);
                
                // Clear Step 3 inputs
                $('#name_wifi').val('');
                $('#password_wifi').val('');
                $('#pppoe_username').val('');
                $('#pppoe_password').val('');
                $('#paket_id').val('');
                $('#price_id').val('');
                $('#mic_radius_id').val('');
                
                // Reset step selection
                $('.service-card').removeClass('selected');
                $('.service-card[data-value="voucher-ke-pppoe"]').addClass('selected');
                $('#radio-v-to-p').prop('checked', true);
                selectedServiceType = 'voucher-ke-pppoe';

                loadedCustomerData = null;
                $customerDetailsCard.hide();
                $btnWizardNext1.prop('disabled', true).css({ 'opacity': '0.6', 'cursor': 'not-allowed' });
                $('#btn-wizard-next-3').prop('disabled', true).css({ 'opacity': '0.6', 'cursor': 'not-allowed' });

                // Reset horizontal stepper indicators
                $('#hs-1').addClass('active').removeClass('completed');
                $('#hs-2').removeClass('active completed');
                $('#hs-3').removeClass('active completed');
                $('#hs-4').removeClass('active completed');
                $('.h-step-line').removeClass('completed');

                // Switch pane
                $('.wizard-pane').removeClass('active');
                $('#w-pane-1').addClass('active');

                // Toggle screens
                $('#change-success-screen').hide();
                $('.horizontal-stepper, #change-wizard').slideDown(300);
            });
        });
    </script>
@endpush

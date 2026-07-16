<div class="sop-content-section">
    <div class="sop-header">
        <div class="sop-badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            Instruksi Kerja Interaktif
        </div>
        <h1>Pemutusan Layanan Pelanggan</h1>
        <p>Alur kerja terstandardisasi untuk verifikasi, pembayaran, dan pencatatan penarikan perangkat router saat penonaktifan pelanggan.</p>
    </div>
    
    <div class="sop-body">
        
        <!-- SUCCESS CARD SCREEN -->
        <div class="success-card" id="termination-success-screen">
            <div style="width: 72px; height: 72px; border-radius: 50%; background: #fef9c3; color: #ca8a04; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; box-shadow: 0 10px 25px rgba(202, 138, 4, 0.15);">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <h2 style="color: #0f172a; font-weight: 800; font-size: 1.5rem; margin-bottom: 0.5rem;">Request Pemutusan Diajukan!</h2>
            <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 2rem; max-width: 500px; display: inline-block; line-height: 1.6;">
                Request pemutusan layanan pelanggan <strong id="success-cust-id">-</strong> telah masuk ke antrean.
                Data pelanggan <strong>belum dihapus</strong> — menunggu persetujuan dari <strong>4 level validator</strong> sebelum pemutusan diterapkan ke sistem.
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
        <div class="wizard-container" id="termination-wizard">
            
            <!-- Horizontal Stepper Header -->
            <div class="horizontal-stepper">
                <!-- Step 1: Pilih Tipe Layanan -->
                <div class="h-step active" id="hs-1">
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
                <!-- Step 2: Cari Pelanggan -->
                <div class="h-step" id="hs-2">
                    <div class="h-step-node">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </div>
                    <span class="h-step-label">Cari Pelanggan</span>
                </div>
                <div class="h-step-line"></div>
                <!-- Step 3: Detail Profil -->
                <div class="h-step" id="hs-3">
                    <div class="h-step-node">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <span class="h-step-label">Detail Profil</span>
                </div>
                <div class="h-step-line"></div>
                <!-- Step 4: Bukti Pemutusan -->
                <div class="h-step" id="hs-4">
                    <div class="h-step-node">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <span class="h-step-label">Bukti Pemutusan</span>
                </div>
            </div>

            <!-- Stepper Content Wrapper -->
            <div class="wizard-content-wrapper">

                <!-- STEP 1: PILIH TIPE LAYANAN -->
                <div class="wizard-pane active" id="w-pane-1">
                    <h3 class="pane-title">Langkah 1: Pilih Tipe Layanan yang Akan Diputus</h3>
                    <p class="pane-desc">Pilih jenis layanan pelanggan yang saat ini aktif dan akan dilakukan pemutusan.</p>

                    <div class="service-type-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
                        <!-- Card 1: Voucher -->
                        <div class="service-card selected" data-value="voucher" id="card-voucher" style="border:2px solid #2563eb;border-radius:16px;padding:1.5rem;cursor:pointer;transition:all 0.25s;background:#fff;position:relative;">
                            <input type="radio" name="tipe_pemutusan_layanan" value="voucher" style="display:none;" checked>
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(37,99,235,0.08);color:#2563eb;display:flex;align-items:center;justify-content:center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                </div>
                                <div class="card-radio-indicator" style="width:20px;height:20px;border-radius:50%;border:2px solid #2563eb;background:#2563eb;display:flex;align-items:center;justify-content:center;transition:all 0.2s;">
                                    <div style="width:10px;height:10px;border-radius:50%;background:#fff;transform:scale(1);transition:all 0.2s;"></div>
                                </div>
                            </div>
                            <h4 style="font-size:1rem;font-weight:800;color:#0f172a;margin:0 0 0.5rem 0;">Voucher / Hotspot</h4>
                            <p style="font-size:0.85rem;color:#64748b;margin:0;line-height:1.5;">Pelanggan menggunakan layanan berbasis Voucher atau Hotspot prabayar.</p>
                        </div>

                        <!-- Card 2: PPPoE -->
                        <div class="service-card" data-value="pppoe" id="card-pppoe" style="border:2px solid #e2e8f0;border-radius:16px;padding:1.5rem;cursor:pointer;transition:all 0.25s;background:#fff;position:relative;">
                            <input type="radio" name="tipe_pemutusan_layanan" value="pppoe" style="display:none;">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(37,99,235,0.08);color:#2563eb;display:flex;align-items:center;justify-content:center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line>
                                    </svg>
                                </div>
                                <div class="card-radio-indicator" style="width:20px;height:20px;border-radius:50%;border:2px solid #cbd5e1;display:flex;align-items:center;justify-content:center;transition:all 0.2s;">
                                    <div style="width:10px;height:10px;border-radius:50%;background:#2563eb;transform:scale(0);transition:all 0.2s;"></div>
                                </div>
                            </div>
                            <h4 style="font-size:1rem;font-weight:800;color:#0f172a;margin:0 0 0.5rem 0;">PPPoE / HOME</h4>
                            <p style="font-size:0.85rem;color:#64748b;margin:0;line-height:1.5;">Pelanggan menggunakan layanan koneksi PPPoE dengan perangkat router dedicated.</p>
                        </div>
                    </div>

                    <div class="wizard-actions">
                        <button type="button" class="btn-sop-submit" id="btn-next-to-search">
                            Lanjutkan Prosedur
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: CARI PELANGGAN -->
                <div class="wizard-pane" id="w-pane-2">
                    <h3 class="pane-title">Langkah 2: Verifikasi & Cari Pelanggan</h3>
                    <p class="pane-desc">Masukkan <strong>ID Pelanggan</strong> atau <strong>MAC Address</strong> — sistem akan mengenali format secara otomatis.</p>
                    
                    <!-- Selected service badge -->
                    <div id="selected-service-badge" style="margin-bottom: 1.25rem; display: inline-flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 700; color: #2563eb; background: rgba(37, 99, 235, 0.08); padding: 8px 14px; border-radius: 8px; border: 1px solid rgba(37, 99, 235, 0.15);">
                        <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #2563eb; animation: pulse 1.5s infinite;"></span>
                        <span>Mencari Pelanggan Tipe: <strong id="selected-service-text" style="text-transform: uppercase;">Voucher</strong></span>
                    </div>

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
                    <div class="alert-error" id="wizard-search-alert" style="margin-bottom: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <span id="wizard-search-alert-text">Input tidak boleh kosong!</span>
                    </div>
                </div>

                <!-- STEP 3: DETAIL PROFIL (FIXED ID FROM w-pane-2) -->
                <div class="wizard-pane" id="w-pane-3">
                    <h3 class="pane-title">Langkah 3: Tinjau Detail Pelanggan</h3>
                    <p class="pane-desc">Pastikan profil dan paket layanan yang akan dihapus sudah benar.</p>
                    
                    <!-- Warning Alert for mismatch category/MAC -->
                    <div class="alert-error" id="wizard-mismatch-alert" style="margin-bottom: 1.25rem; display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <span id="wizard-mismatch-alert-text">Tipe layanan tidak sesuai!</span>
                    </div>

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
                        <button type="button" class="btn-sop-back" id="btn-back-to-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            Kembali
                        </button>
                        <button type="button" class="btn-sop-submit" id="btn-wizard-next-3">
                            Lanjutkan Prosedur
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </div>
                </div>

                <!-- STEP 4: BUKTI PEMUTUSAN -->
                <div class="wizard-pane" id="w-pane-4">
                    <h3 class="pane-title">Langkah 4: Data & Bukti Pemutusan</h3>
                    <p class="pane-desc">Unggah bukti penarikan router, selesaikan tagihan akhir, serta alasan penonaktifan.</p>
                    
                    <form id="wizard-termination-form">
                        <div class="form-grid">
                            
                            <div class="input-group-custom form-group-full">
                                <label class="form-label-custom">Alasan Utama Pemutusan</label>
                                <div style="position: relative;">
                                    <input type="text" id="w-input-reason" class="form-control-custom" placeholder="Masukkan alasan pemutusan (misal: Pindah rumah, kendala sinyal, dll.)" required>
                                    <div class="input-icon-wrapper">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Wrapper Bukti Perangkat (Conditional) -->
                            <div class="input-group-custom" id="section-bukti-perangkat">
                                <label class="form-label-custom">Foto Bukti Perangkat Diambil</label>
                                <input type="file" id="wizard-router-input" style="display: none;" accept="image/*" required>
                                
                                <div class="upload-dropzone" id="wizard-router-dropzone">
                                    <div class="upload-icon" id="wizard-router-icon-element">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </div>
                                    <p class="upload-text" id="wizard-router-text-element">Klik/seret foto perangkat di sini</p>
                                    <p class="upload-subtext" id="wizard-router-subtext-element">Mendukung JPG, PNG maks 5MB</p>
                                    
                                    <div class="preview-image-container" id="wizard-router-preview-container">
                                        <img id="wizard-router-preview-img" src="" alt="Preview Bukti Perangkat">
                                    </div>
                                </div>
                            </div>

                            <!-- Wrapper Bukti Transfer (Conditional) -->
                            <div class="input-group-custom" id="section-bukti-transfer">
                                <label class="form-label-custom">Foto Bukti Pembayaran</label>
                                <input type="file" id="wizard-payment-input" style="display: none;" accept="image/*" required>
                                
                                <div class="upload-dropzone" id="wizard-payment-dropzone">
                                    <div class="upload-icon" id="wizard-payment-icon-element">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                                            <line x1="12" y1="18" x2="12.01" y2="18"></line>
                                            <line x1="8" y1="6" x2="16" y2="6"></line>
                                            <line x1="8" y1="10" x2="16" y2="10"></line>
                                            <line x1="8" y1="14" x2="13" y2="14"></line>
                                        </svg>
                                    </div>
                                    <p class="upload-text" id="wizard-payment-text-element">Klik/seret bukti bayar di sini</p>
                                    <p class="upload-subtext" id="wizard-payment-subtext-element">Mendukung JPG, PNG maks 5MB</p>
                                    
                                    <div class="preview-image-container" id="wizard-payment-preview-container">
                                        <img id="wizard-payment-preview-img" src="" alt="Preview Bukti Pembayaran">
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        <div class="wizard-actions">
                            <button type="button" class="btn-sop-back" id="btn-back-to-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                Kembali
                            </button>
                            <button type="submit" class="btn-sop-submit" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.3);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Konfirmasi & Putuskan Layanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Confirmation Modal -->
            <div class="sop-modal-overlay" id="confirm-modal">
                <div class="sop-modal-card">
                    <div class="sop-modal-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <h3 class="sop-modal-title">Konfirmasi Pemutusan</h3>
                    <p class="sop-modal-desc">Apakah Anda yakin ingin menonaktifkan dan memutuskan layanan pelanggan ini secara permanen? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="sop-modal-actions">
                        <button type="button" class="btn-modal-cancel" id="btn-modal-close">Batal</button>
                        <button type="button" class="btn-modal-confirm" id="btn-modal-confirm">Ya, Putuskan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            // ─── HORIZONTAL TERMINATION WIZARD LOGIC ───
            let selectedPemutusanType = 'voucher';

            const $wizardSearchId = $('#wizard-search-id');
            const $btnWizardSearch = $('#btn-wizard-search');
            const $spinnerWizardSearch = $('#spinner-wizard-search');
            const $wizardSearchAlert = $('#wizard-search-alert');
            const $wizardDetailId = $('#wizard-detail-id');
            const $wizardDetailName = $('#wizard-detail-name');
            const $wizardDetailType = $('#wizard-detail-type');
            const $wizardDetailPlan = $('#wizard-detail-plan');
            const $wizardDetailAddress = $('#wizard-detail-address');
            const $wizardDetailStatus = $('#wizard-detail-status');

            // Hide alert on load
            $wizardSearchAlert.hide();

            // ─── Step 1: Service card selection ───
            $('.service-card').on('click', function() {
                $('.service-card').each(function() {
                    $(this).css('border-color', '#e2e8f0');
                    $(this).find('.card-radio-indicator').css({'border-color':'#cbd5e1','background':''});
                    $(this).find('.card-radio-indicator div').css('transform','scale(0)');
                });
                $(this).css('border-color', '#2563eb');
                $(this).find('.card-radio-indicator').css({'border-color':'#2563eb','background':'#2563eb'});
                $(this).find('.card-radio-indicator div').css('transform','scale(1)');
                $(this).find('input[type="radio"]').prop('checked', true);
                selectedPemutusanType = $(this).data('value');
            });

            // Step 1 → Step 2
            $('#btn-next-to-search').on('click', function() {
                $('#selected-service-text').text(selectedPemutusanType);
                $('#hs-1').removeClass('active').addClass('completed');
                $('#hs-2').addClass('active');
                $('.h-step-line').eq(0).addClass('completed');
                $('#w-pane-1').removeClass('active');
                $('#w-pane-2').addClass('active');
            });

            // ─── Auto-detect input type as user types ───
            const MAC_REGEX  = /^([0-9a-fA-F]{2}[:\-]){1,}[0-9a-fA-F]{0,2}$/;
            const MAC_FULL   = /^([0-9a-fA-F]{2}[:\-]){5}[0-9a-fA-F]{2}$/;
            // MAC_HAS_SEP: detects any input with colon/dash separator pattern (looks like a MAC being typed)
            const MAC_HAS_SEP = /^[0-9a-zA-Z]{1,2}([:\-][0-9a-zA-Z]{0,2})+$/;;

            const svgId  = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
            const svgMac = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="8" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="12" x2="6.01" y2="12"/><line x1="10" y1="12" x2="10.01" y2="12"/></svg>';
            const svgSrch= '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>';

            $wizardSearchId.on('input', function() {
                const val = $(this).val().trim();
                $wizardSearchAlert.hide();

                if (!val) {
                    $('#search-mode-badge').css('color', '#94a3b8');
                    $('#search-mode-text').text('Ketik untuk mulai pencarian');
                    $('#search-mode-badge').find('svg').replaceWith($(svgSrch).css({width:'12px',height:'12px'}));
                    $('#search-input-icon').html(svgSrch);
                    return;
                }

                if (MAC_FULL.test(val)) {
                    $('#search-mode-badge').css('color', '#7c3aed');
                    $('#search-mode-text').text('Mode: MAC Address ✓');
                    $('#search-mode-badge').find('svg').replaceWith($(svgMac).css({width:'12px',height:'12px'}));
                    $('#search-input-icon').html(svgMac.replace('width="12"','width="18"').replace('height="12"','height="18"'));
                } else if (MAC_REGEX.test(val)) {
                    $('#search-mode-badge').css('color', '#7c3aed');
                    $('#search-mode-text').text('Mode: MAC Address (lanjutkan mengetik...)');
                    $('#search-mode-badge').find('svg').replaceWith($(svgMac).css({width:'12px',height:'12px'}));
                    $('#search-input-icon').html(svgMac.replace('width="12"','width="18"').replace('height="12"','height="18"'));
                } else {
                    $('#search-mode-badge').css('color', '#2563eb');
                    $('#search-mode-text').text('Mode: ID Pelanggan');
                    $('#search-mode-badge').find('svg').replaceWith($(svgId).css({width:'12px',height:'12px'}));
                    $('#search-input-icon').html(svgId.replace('width="12"','width="18"').replace('height="12"','height="18"'));
                }
            });

            // Allow Enter key to trigger search
            $wizardSearchId.on('keypress', function(e) {
                if (e.which === 13) $btnWizardSearch.trigger('click');
            });

            // Step 1: Search Button Clicked
            $btnWizardSearch.on('click', function() {
                const query = $wizardSearchId.val().trim();
                
                if (!query) {
                    $wizardSearchAlert.find('span').text('Masukkan ID Pelanggan atau MAC Address!');
                    $wizardSearchAlert.css('display', 'flex').hide().slideDown(200);
                    return;
                }

                // Auto-detect search mode
                const isMacValid = MAC_FULL.test(query);
                const isMacHasSep = MAC_HAS_SEP.test(query);
                const isMac      = isMacValid || MAC_REGEX.test(query) || isMacHasSep;
                const searchBy = isMac ? 'mac' : 'id';

                $wizardSearchAlert.hide();
                $btnWizardSearch.prop('disabled', true);
                $spinnerWizardSearch.show();
                $('.btn-text-search').hide();

                // Make AJAX request to get real customer details
                $.ajax({
                    url: "{{ route('public.prosedur.search_customer') }}",
                    method: 'GET',
                    data: { query: query, search_by: searchBy },
                    success: function(response) {
                        // Reset buttons
                        $btnWizardSearch.prop('disabled', false);
                        $spinnerWizardSearch.hide();
                        $('.btn-text-search').show();

                        if (response.status === 'success') {
                            const customer = response.data;
                            window._loadedPemutusan = customer; // simpan termasuk db_id
                            
                            // Set step details
                            $wizardDetailId.text(customer.id.toUpperCase());
                            $wizardDetailName.text(customer.name);
                            $wizardDetailType.text(customer.tipe_layanan);
                            $wizardDetailPlan.text(customer.paket);
                            $wizardDetailAddress.text(customer.alamat);
                            
                            // Style status badge
                            if (customer.status.toLowerCase() === 'active' || customer.status.toLowerCase() === 'aktif') {
                                $wizardDetailStatus.text('Aktif Berlangganan')
                                    .css({ 'background': '#dcfce7', 'color': '#16a34a' });
                            } else {
                                $wizardDetailStatus.text(customer.status.charAt(0).toUpperCase() + customer.status.slice(1))
                                    .css({ 'background': '#fee2e2', 'color': '#ef4444' });
                            }

                            $('#success-cust-id').text(customer.id.toUpperCase());

                            // Validate customer service category against selection
                            let isValid = true;
                            let errMsg = '';
                            const custTypeRaw = customer.tipe_layanan_raw || '';
                            const isVoucher = custTypeRaw.includes('voucher') || custTypeRaw.includes('hotspot');
                            const isPppoe   = custTypeRaw.includes('pppoe')   || custTypeRaw.includes('home');

                            if (selectedPemutusanType === 'voucher') {
                                if (!isVoucher) {
                                    isValid = false;
                                    errMsg = 'Mismatch Kategori: Anda memilih Voucher di Step 1, namun tipe layanan customer adalah ' + customer.tipe_layanan + '.';
                                }
                            } else if (selectedPemutusanType === 'pppoe') {
                                if (!isPppoe) {
                                    isValid = false;
                                    errMsg = 'Mismatch Kategori: Anda memilih PPPoE di Step 1, namun tipe layanan customer adalah ' + customer.tipe_layanan + '.';
                                } else if (!customer.mac_address) {
                                    isValid = false;
                                    errMsg = 'MAC Address Mismatch: Pelanggan PPPoE ini tidak memiliki MAC Address terdaftar.';
                                }
                            }

                            if (!isValid) {
                                $('#wizard-mismatch-alert-text').text(errMsg);
                                $('#wizard-mismatch-alert').show();
                                $('#btn-wizard-next-3').prop('disabled', true).css('opacity', 0.5);
                            } else {
                                $('#wizard-mismatch-alert').hide();
                                $('#btn-wizard-next-3').prop('disabled', false).css('opacity', 1);
                            }

                            // Update Stepper circles (now step 2→3)
                            $('#hs-2').removeClass('active').addClass('completed');
                            $('#hs-3').addClass('active');
                            $('.h-step-line').eq(1).addClass('completed');

                            // Switch panes
                            $('#w-pane-2').removeClass('active');
                            $('#w-pane-3').addClass('active');
                        } else {
                            $wizardSearchAlert.find('span').text(response.message || 'Pelanggan tidak ditemukan.');
                            $wizardSearchAlert.css('display', 'flex').hide().slideDown(200);
                        }
                    },
                    error: function(xhr) {
                        // Reset buttons
                        $btnWizardSearch.prop('disabled', false);
                        $spinnerWizardSearch.hide();
                        $('.btn-text-search').show();

                        let errMsg = 'Pelanggan tidak ditemukan.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        }
                        $wizardSearchAlert.find('span').text(errMsg);
                        $wizardSearchAlert.css('display', 'flex').hide().slideDown(200);
                    }
                });
            });

            // Step 2 (Cari): Back to Step 1 (Pilih Layanan)
            $('#btn-back-to-1').on('click', function() {
                $('#hs-1').addClass('active').removeClass('completed');
                $('#hs-2').removeClass('active');
                $('.h-step-line').eq(0).removeClass('completed');

                $('#w-pane-2').removeClass('active');
                $('#w-pane-1').addClass('active');
            });

            // Step 3 (Detail Profil): Back to Step 2 (Cari Pelanggan)
            $('#btn-back-to-2').on('click', function() {
                $('#hs-2').addClass('active').removeClass('completed');
                $('#hs-3').removeClass('active');
                $('.h-step-line').eq(1).removeClass('completed');

                $('#w-pane-3').removeClass('active');
                $('#w-pane-2').addClass('active');
            });

            // Function to configure step 4 dynamic layouts
            function updateStep4Layout() {
                const customer = window._loadedPemutusan || {};
                const paymentType = (customer.tipe_pembayaran || 'UNKNOWN').toUpperCase();

                if (selectedPemutusanType === 'voucher') {
                    // Voucher: Only reason (no router proof, no payment proof)
                    $('#section-bukti-perangkat').hide();
                    $('#wizard-router-input').prop('required', false);

                    $('#section-bukti-transfer').hide();
                    $('#wizard-payment-input').prop('required', false);
                } else {
                    // PPPoE
                    $('#section-bukti-perangkat').show();
                    $('#wizard-router-input').prop('required', true);

                    if (paymentType.includes('POSTPAID') || paymentType.includes('PAKE DULU')) {
                        // PPPoE Postpaid: Alasan + Perangkat + Transfer
                        $('#section-bukti-transfer').show();
                        $('#wizard-payment-input').prop('required', true);
                    } else {
                        // PPPoE Prepaid: Alasan + Perangkat (No Transfer)
                        $('#section-bukti-transfer').hide();
                        $('#wizard-payment-input').prop('required', false);
                    }
                }
            }

            // Step 3 (Detail Profil): Continue to Step 4 (Bukti Pemutusan)
            $('#btn-wizard-next-3').on('click', function() {
                updateStep4Layout();

                $('#hs-3').removeClass('active').addClass('completed');
                $('#hs-4').addClass('active');
                $('.h-step-line').eq(2).addClass('completed');

                $('#w-pane-3').removeClass('active');
                $('#w-pane-4').addClass('active');
            });

            // Step 4 (Bukti Pemutusan): Back to Step 3 (Detail Profil)
            $('#btn-back-to-3').on('click', function() {
                $('#hs-3').addClass('active').removeClass('completed');
                $('#hs-4').removeClass('active');
                $('.h-step-line').eq(2).removeClass('completed');

                $('#w-pane-4').removeClass('active');
                $('#w-pane-3').addClass('active');
            });

            // Step 3: Custom File Dropzone Click (Router/Perangkat)
            $('#wizard-router-dropzone').on('click', function(e) {
                if (!$(e.target).closest('#wizard-router-preview-container').length) {
                    $('#wizard-router-input').click();
                }
            });

            // Handle file input change & image preview (Router/Perangkat)
            $('#wizard-router-input').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $('#wizard-router-preview-img').attr('src', event.target.result);
                        $('#wizard-router-preview-container').fadeIn(200);
                        $('#wizard-router-icon-element, #wizard-router-text-element, #wizard-router-subtext-element').hide();
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Step 3: Custom File Dropzone Click (Pembayaran)
            $('#wizard-payment-dropzone').on('click', function(e) {
                if (!$(e.target).closest('#wizard-payment-preview-container').length) {
                    $('#wizard-payment-input').click();
                }
            });

            // Handle file input change & image preview (Pembayaran)
            $('#wizard-payment-input').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $('#wizard-payment-preview-img').attr('src', event.target.result);
                        $('#wizard-payment-preview-container').fadeIn(200);
                        $('#wizard-payment-icon-element, #wizard-payment-text-element, #wizard-payment-subtext-element').hide();
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Step 4: Final Submit Clicked
            $('#wizard-termination-form').on('submit', function(e) {
                e.preventDefault();
                // Show modal overlay
                $('#confirm-modal').addClass('active');
            });

            // Modal: Cancel Clicked
            $('#btn-modal-close').on('click', function() {
                $('#confirm-modal').removeClass('active');
            });

            // Modal: Confirm Clicked
            $('#btn-modal-confirm').on('click', function() {
                const customer = window._loadedPemutusan || {};
                const $btn = $(this);
                $btn.prop('disabled', true).text('Menyimpan...');

                const formData = new FormData();
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                formData.append('prosedur_type', 'pemutusan');
                formData.append('customer_id', customer.db_id);
                formData.append('alasan', $('#w-input-reason').val());

                const routerFile  = $('#wizard-router-input')[0].files[0];
                const paymentFile = $('#wizard-payment-input')[0].files[0];
                if (routerFile)  formData.append('foto_perangkat',  routerFile);
                if (paymentFile) formData.append('foto_pembayaran', paymentFile);

                $.ajax({
                    url: "{{ route('public.prosedur.store') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#confirm-modal').removeClass('active');
                        // Hide stepper header & wizard wrapper
                        formData.append('tipe_layanan_pemutusan', selectedPemutusanType);
                        $('.horizontal-stepper, #termination-wizard').slideUp(300, function() {
                            // Show success screen
                            $('#termination-success-screen').fadeIn(300);
                        });
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.message || 'Terjadi kesalahan, coba lagi.';
                        alert('Gagal mengajukan: ' + msg);
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text('Ya, Putuskan Layanan');
                    }
                });
            });


            // Modal: Click outside to close
            $('#confirm-modal').on('click', function(e) {
                if (e.target === this) {
                    $(this).removeClass('active');
                }
            });

            // Restart Wizard Button Clicked
            $('#btn-restart-wizard').on('click', function() {
                // Reset inputs and fields
                $wizardSearchId.val('');
                // Reset mode badge
                $('#search-mode-badge').css('color', '#94a3b8');
                $('#search-mode-text').text('Ketik untuk mulai pencarian');
                $('#search-input-icon').html('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>');
                $wizardDetailType.text('-');
                $('#w-input-reason').val('');
                
                // Reset Router Upload
                $('#wizard-router-input').val('');
                $('#wizard-router-preview-img').attr('src', '');
                $('#wizard-router-preview-container').hide();
                $('#wizard-router-icon-element, #wizard-router-text-element, #wizard-router-subtext-element').show();

                // Reset Payment Upload
                $('#wizard-payment-input').val('');
                $('#wizard-payment-preview-img').attr('src', '');
                $('#wizard-payment-preview-container').hide();
                $('#wizard-payment-icon-element, #wizard-payment-text-element, #wizard-payment-subtext-element').show();

                // Reset service card selection
                selectedPemutusanType = 'voucher';
                $('#card-voucher').css('border-color','#2563eb').find('.card-radio-indicator').css({'border-color':'#2563eb','background':'#2563eb'});
                $('#card-voucher .card-radio-indicator div').css('transform','scale(1)');
                $('#card-pppoe').css('border-color','#e2e8f0').find('.card-radio-indicator').css({'border-color':'#cbd5e1','background':''});
                $('#card-pppoe .card-radio-indicator div').css('transform','scale(0)');
                $('input[name="tipe_pemutusan_layanan"][value="voucher"]').prop('checked', true);

                // Reset horizontal header classes
                $('#hs-1').addClass('active').removeClass('completed');
                $('#hs-2').removeClass('active completed');
                $('#hs-3').removeClass('active completed');
                $('#hs-4').removeClass('active completed');
                $('.h-step-line').removeClass('completed');

                // Reset alerts & warnings
                $('#wizard-mismatch-alert').hide();
                $('#btn-wizard-next-3').prop('disabled', false).css('opacity', 1);

                // Switch back to pane 1
                $('.wizard-pane').removeClass('active');
                $('#w-pane-1').addClass('active');

                // Fade back wizard & header
                $('#termination-success-screen').hide();
                $('.horizontal-stepper, #termination-wizard').slideDown(300);
            });
        });
    </script>
@endpush

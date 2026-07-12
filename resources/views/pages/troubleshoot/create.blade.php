@extends('layouts.app')

@section('title', 'Buat Open Ticket')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .premium-card {
        border-radius: 14px; background: #fff; border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,.05), 0 2px 4px -2px rgba(0,0,0,.05);
    }
    .premium-card-header {
        background: #f8fafc; border-bottom: 1px solid #e2e8f0;
        border-radius: 14px 14px 0 0; padding: .75rem 1rem;
    }
    .icon-wrapper {
        width: 28px; height: 28px; border-radius: 8px;
        background: rgba(79,70,229,.1); color: #4f46e5;
        display: flex; align-items: center; justify-content: center;
    }
    .form-label-premium {
        font-size: .82rem; font-weight: 600; color: #475569; margin-bottom: .35rem;
    }
    .form-control-premium {
        border-radius: 8px; border: 1px solid #cbd5e1;
        font-size: .88rem; padding: .45rem .75rem;
        transition: all .15s ease-in-out;
    }
    .form-control-premium:focus {
        border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.15);
    }
    .detail-row {
        display: flex; justify-content: space-between;
        padding: .5rem .75rem; border-bottom: 1px solid #f1f5f9; font-size: .88rem;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { color: #64748b; font-weight: 500; }
    .detail-value { color: #1e293b; font-weight: 600; text-align: right; }

    /* Wizard */
    .wizard-steps {
        display: flex; justify-content: center; align-items: center;
        gap: 0; margin-bottom: 2rem; padding: 1.5rem 1rem 0;
    }
    .wizard-step {
        display: flex; align-items: center; gap: .5rem;
    }
    .wizard-step .step-circle {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .85rem;
        border: 2px solid #cbd5e1; color: #94a3b8;
        background: #fff; transition: all .25s ease;
    }
    .wizard-step .step-label {
        font-size: .82rem; font-weight: 500; color: #94a3b8;
        transition: all .25s ease;
    }
    .wizard-step.active .step-circle {
        border-color: #4f46e5; color: #fff; background: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79,70,229,.2);
    }
    .wizard-step.active .step-label { color: #1e293b; font-weight: 600; }
    .wizard-step.done .step-circle {
        border-color: #10b981; color: #fff; background: #10b981;
    }
    .wizard-step.done .step-label { color: #10b981; }
    .wizard-connector {
        width: 60px; height: 2px; background: #e2e8f0; margin: 0 1rem;
        transition: background .25s ease;
    }
    .wizard-connector.done { background: #10b981; }

    .step-panel { display: none; }
    .step-panel.active { display: block; }
</style>
@endpush

@section('content')
<div class="org-container">
    <div class="org-card">
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                </div>
                <div>
                    <h5 class="org-title">Buat Open Ticket Baru</h5>
                    <div class="org-subtitle">Wizard pembuatan ticket troubleshoot</div>
                </div>
            </div>
            <a href="{{ route('troubleshoot.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        {{-- Wizard Steps Indicator --}}
        <div class="wizard-steps">
            <div class="wizard-step active" data-step="1">
                <div class="step-circle">1</div>
                <span class="step-label">Cari Pelanggan</span>
            </div>
            <div class="wizard-connector" data-connector="1"></div>
            <div class="wizard-step" data-step="2">
                <div class="step-circle">2</div>
                <span class="step-label">Detail Pelanggan</span>
            </div>
            <div class="wizard-connector" data-connector="2"></div>
            <div class="wizard-step" data-step="3">
                <div class="step-circle">3</div>
                <span class="step-label">Konfigurasi Ticket</span>
            </div>
        </div>

        <div class="p-3">
            <form action="{{ route('troubleshoot.store') }}" method="POST" id="formTicket">
                @csrf
                <input type="hidden" name="customer_id" id="customer_id">

                {{-- STEP 1: Cari Pelanggan --}}
                <div class="step-panel active" data-panel="1">
                    <div class="premium-card">
                        <div class="premium-card-header d-flex align-items-center gap-2">
                            <div class="icon-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                            </div>
                            <span class="fw-bold text-dark" style="font-size:.88rem;">Cari Pelanggan</span>
                        </div>
                        <div class="card-body p-4 text-center">
                            <div style="max-width:600px;margin:0 auto;">
                                <div class="mb-3" style="font-size:3rem;color:#cbd5e1;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                        <path d="M8 11h6"/><path d="M11 8v6"/>
                                    </svg>
                                </div>
                                <h5 style="font-weight:600;color:#1e293b;margin-bottom:.5rem;">Masukkan MAC Address Pelanggan</h5>
                                <p style="color:#64748b;font-size:.88rem;margin-bottom:1.5rem;">Ketik MAC Address perangkat pelanggan untuk memulai</p>
                                <div class="input-group" style="max-width:500px;margin:0 auto;">
                                    <input type="text" id="search_mac" class="form-control form-control-premium"
                                        placeholder="Contoh: AA:BB:CC:DD:EE:FF" autocomplete="off" style="padding:.6rem .75rem;font-size:.95rem;">
                                    <button type="button" id="btnSearch" class="btn btn-primary px-4" style="font-weight:600;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                        </svg>
                                        Cari
                                    </button>
                                </div>
                                <div id="searchError" class="text-danger mt-2" style="font-size:.85rem;display:none;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 2: Detail Pelanggan --}}
                <div class="step-panel" data-panel="2">
                    <div class="premium-card">
                        <div class="premium-card-header d-flex align-items-center gap-2" style="background:#f0f9ff;border-color:#bae6fd;">
                            <div class="icon-wrapper" style="background:rgba(14,165,233,.1);color:#0ea5e9;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                </svg>
                            </div>
                            <span class="fw-bold text-dark" style="font-size:.88rem;" id="customerNameTitle">Detail Pelanggan</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="detail-row"><span class="detail-label">Nama Pelanggan</span><span class="detail-value" id="detailName">-</span></div>
                            <div class="detail-row"><span class="detail-label">ID Pelanggan</span><span class="detail-value" id="detailUuid">-</span></div>
                            <div class="detail-row"><span class="detail-label">MAC Address</span><span class="detail-value" id="detailMac">-</span></div>
                            <div class="detail-row"><span class="detail-label">Tipe Layanan</span><span class="detail-value" id="detailTipeLayanan">-</span></div>
                            <div class="detail-row"><span class="detail-label">Tipe Pembayaran</span><span class="detail-value" id="detailTipePembayaran">-</span></div>
                            <div class="detail-row"><span class="detail-label">Paket</span><span class="detail-value" id="detailPaket">-</span></div>
                            <div class="detail-row"><span class="detail-label">Alamat</span><span class="detail-value" id="detailAlamat" style="max-width:400px;">-</span></div>
                            <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value" id="detailStatus">-</span></div>
                            <div class="detail-row"><span class="detail-label">Koordinat</span><span class="detail-value" id="detailKoordinat">-</span></div>
                        </div>
                        <div id="customerMap" style="height:280px;border-radius:0 0 14px 14px;display:none;"></div>
                    </div>
                </div>

                {{-- STEP 3: Konfigurasi Ticket --}}
                <div class="step-panel" data-panel="3">
                    <div class="premium-card">
                        <div class="premium-card-header d-flex align-items-center gap-2">
                            <div class="icon-wrapper" style="background:rgba(245,158,11,.1);color:#f59e0b;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/>
                                </svg>
                            </div>
                            <span class="fw-bold text-dark" style="font-size:.88rem;">Konfigurasi Ticket</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label for="technician_id" class="form-label-premium">Pilih Teknisi <span style="color:#ef4444;">*</span></label>
                                    <select name="technician_id" id="technician_id" class="form-select">
                                        <option value="">-- Pilih Teknisi --</option>
                                    </select>
                                    <span class="invalid-feedback error_technician_id"></span>
                                </div>
                                <div class="col-12">
                                    <label for="description" class="form-label-premium">Deskripsi Trouble <span style="color:#ef4444;">*</span></label>
                                    <textarea name="description" id="description" class="form-control form-control-premium" rows="4" placeholder="Jelaskan trouble yang dialami pelanggan..."></textarea>
                                    <span class="invalid-feedback error_description"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Navigation --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="button" id="btnBack" class="btn btn-outline-secondary px-4" style="display:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                        Kembali
                    </button>
                    <div style="flex:1;"></div>
                    <button type="button" id="btnNext" class="btn btn-primary px-4">
                        Selanjutnya
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                    <button type="submit" id="submitBtn" class="btn btn-success px-4" style="display:none;">
                        <span id="btnText">Simpan Ticket</span>
                        <span id="btnLoading" class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function() {
        let currentStep = 1;
        let customerFound = false;
        let customerMap = null;

        function updateSteps() {
            $('.wizard-step').each(function() {
                const s = parseInt($(this).data('step'));
                $(this).removeClass('active done');
                if (s === currentStep) $(this).addClass('active');
                else if (s < currentStep) $(this).addClass('done');
            });
            $('.wizard-connector').each(function() {
                const c = parseInt($(this).data('connector'));
                $(this).toggleClass('done', c < currentStep);
            });
            $('.step-panel').removeClass('active').filter('[data-panel="' + currentStep + '"]').addClass('active');

            $('#btnBack').toggle(currentStep > 1);
            $('#btnNext').toggle(currentStep < 3);
            $('#submitBtn').toggle(currentStep === 3);
        }

        function goToStep(step) {
            if (step === 2 && !customerFound) {
                $('#searchError').text('Silakan cari pelanggan terlebih dahulu.').show();
                return;
            }
            if (step === 3 && !customerFound) return;
            currentStep = step;
            updateSteps();
            if (step === 2 && customerMap) {
                setTimeout(function() { customerMap.invalidateSize(); }, 200);
            }
        }

        $('#btnNext').on('click', function() {
            goToStep(currentStep + 1);
        });

        $('#btnBack').on('click', function() {
            goToStep(currentStep - 1);
        });

        function populateTechSelect(orgId) {
            const techSelect = $('#technician_id');
            if (techSelect.data('select2')) {
                techSelect.select2('destroy');
            }
            techSelect.empty().append('<option value="">-- Pilih Teknisi --</option>');

            $.get('{{ route("troubleshoot.technicians-by-organization") }}', { organization_id: orgId })
                .done(function(technicians) {
                    if (technicians.length === 0) {
                        techSelect.append('<option value="" disabled>Tidak ada teknisi</option>');
                    } else {
                        $.each(technicians, function(i, tech) {
                            techSelect.append('<option value="' + tech.id + '">' + tech.name + '</option>');
                        });
                    }
                    techSelect.select2({
                        theme: 'bootstrap-5',
                        placeholder: '-- Pilih Teknisi --',
                        allowClear: true,
                        width: '100%'
                    });
                })
                .fail(function() {
                    techSelect.append('<option value="" disabled>Gagal memuat teknisi</option>');
                    techSelect.select2({
                        theme: 'bootstrap-5',
                        placeholder: '-- Pilih Teknisi --',
                        allowClear: true,
                        width: '100%'
                    });
                });
        }

        // Initial load: teknisi berdasarkan organisasi user login
        populateTechSelect('');

        function initCustomerMap(lat, lng, name) {
            if (customerMap) { customerMap.remove(); customerMap = null; }
            const mapEl = document.getElementById('customerMap');
            mapEl.style.display = 'block';
            customerMap = L.map(mapEl, { zoomControl: true, scrollWheelZoom: false }).setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }).addTo(customerMap);
            L.marker([lat, lng], {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
                })
            }).addTo(customerMap).bindPopup('<b>' + name + '</b><br>Lokasi Pelanggan');
            setTimeout(function() { customerMap.invalidateSize(); }, 300);
        }

        function doSearch() {
            const mac = $('#search_mac').val().trim();
            if (!mac) {
                $('#searchError').text('Masukkan MAC Address terlebih dahulu.').show();
                return;
            }
            $('#btnSearch').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Mencari...');
            $('#searchError').hide();

            $.get('{{ route("troubleshoot.search-customer") }}', { mac: mac })
                .done(function(res) {
                    if (res.status === 'success') {
                        const d = res.data;
                        $('#customer_id').val(d.id);
                        $('#customerNameTitle').text(d.name);
                        $('#detailName').text(d.name);
                        $('#detailUuid').text(d.uuid);
                        $('#detailMac').text(d.mac_address);
                        $('#detailTipeLayanan').text(d.tipe_layanan);
                        $('#detailTipePembayaran').text(d.tipe_pembayaran);
                        $('#detailPaket').text(d.paket);
                        $('#detailAlamat').text(d.alamat);
                        const statusBadge = d.status === 'aktif' || d.status === 'active'
                            ? '<span class="badge bg-success">Aktif</span>'
                            : '<span class="badge bg-danger">' + d.status + '</span>';
                        $('#detailStatus').html(statusBadge);

                        const hasCoord = d.latitude && d.longitude;
                        $('#detailKoordinat').text(hasCoord ? d.latitude + ', ' + d.longitude : 'Tidak tersedia');

                        customerFound = true;
                        if (hasCoord) initCustomerMap(parseFloat(d.latitude), parseFloat(d.longitude), d.name);
                        else document.getElementById('customerMap').style.display = 'none';

                        // Load teknisi berdasarkan organisasi pelanggan
                        populateTechSelect(d.organization_id);

                        goToStep(2);
                    }
                })
                .fail(function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Terjadi kesalahan saat mencari pelanggan.';
                    $('#searchError').text(msg).show();
                    document.getElementById('customerMap').style.display = 'none';
                    if (customerMap) { customerMap.remove(); customerMap = null; }
                    customerFound = false;
                })
                .always(function() {
                    $('#btnSearch').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg> Cari');
                });
        }

        $('#btnSearch').on('click', doSearch);
        $('#search_mac').on('keypress', function(e) {
            if (e.which === 13) { e.preventDefault(); doSearch(); }
        });

        $('#formTicket').on('submit', function(e) {
            if (!customerFound) {
                e.preventDefault();
                $('#searchError').text('Silakan cari pelanggan terlebih dahulu.').show();
                goToStep(1);
                return;
            }
            const btn = $('#submitBtn');
            btn.prop('disabled', true);
            $('#btnText').addClass('d-none');
            $('#btnLoading').removeClass('d-none');
        });
    });
</script>
@endpush

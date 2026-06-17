@extends('layouts.app')

@section('title', 'Data Server')

@push('css')
    <link href="{{ asset('css/modern-layout.css') }}" rel="stylesheet">
    <style>
        #map-server {
            width: 100%;
            height: 320px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            z-index: 1;
        }
    </style>
@endpush

@section('content')
    <div class="org-container mt-4">
        <div class="org-card">

            {{-- Header --}}
            <div class="org-header">
                <div class="org-title-wrap">
                    <div class="org-header-icon" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#2563eb;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                            <path d="M3 12m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                            <path d="M7 8l0 .01" />
                            <path d="M7 16l0 .01" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="org-title">Data Server</h2>
                        <p class="org-subtitle mb-0">Kelola master data Server jaringan.</p>
                    </div>
                </div>

                @can('tambah server')
                    <div class="org-header-action">
                        <a href="javascript:void(0)" id="addBtn" data-bs-toggle="modal" data-bs-target="#modal-simple"
                            class="btn-add">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Tambah Server
                        </a>
                    </div>
                @endcan
            </div>

            {{-- Toolbar --}}
            <div class="org-toolbar">
                <div class="d-flex align-items-center gap-2">
                    <select name="sort" id="sort" class="org-input" style="width:80px;">
                        @foreach ([10, 25, 50, 100] as $opt)
                            <option value="{{ $opt }}" {{ request('sort') == $opt ? 'selected' : '' }}>
                                {{ $opt }}</option>
                        @endforeach
                    </select>
                    <span class="text-muted small fw-bold d-none d-sm-inline">ENTRIES</span>
                </div>
                <div class="search-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="text" class="org-input w-100" id="search-input" placeholder="Cari Server...">
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="org-table" id="server-table">
                    <thead>
                        <tr>
                            <th style="width:56px;">NO</th>
                            <th>KODE</th>
                            <th>NAMA SERVER</th>
                            <th>KAMPUNG</th>
                            <th>ALAMAT</th>
                            <th class="text-center">FOTO</th>
                            <th class="text-center">LOKASI</th>
                            <th class="text-center">LINK SERVER</th>
                            <th>CREATED</th>
                            @if (auth()->user()->can('edit server') || auth()->user()->can('hapus server'))
                                <th class="text-center">ACTION</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="org-footer flex-column flex-sm-row">
                <div class="org-info mb-3 mb-sm-0 text-center text-sm-start">
                    Menampilkan <span id="start-entry">0</span> - <span id="end-entry">0</span> dari
                    <span id="total-entries">0</span> data
                </div>
                <ul class="pagination mb-0" id="custom-pagination"></ul>
            </div>

        </div>
    </div>
@endsection

@push('modal')
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius:18px;overflow:hidden;border:none;">

                {{-- Modal Header --}}
                <div class="modal-header"
                    style="background:linear-gradient(135deg,#1e1b4b,#4c1d95);border:none;padding:1.25rem 1.5rem;">
                    <div class="d-flex align-items-center gap-2">
                        <div
                            style="width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                <path d="M3 12m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                <path d="M7 8l0 .01" />
                                <path d="M7 16l0 .01" />
                            </svg>
                        </div>
                        <h5 class="modal-title mb-0" style="color:white;font-weight:700;font-size:.95rem;">Tambah Server</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body" style="padding:1.5rem;">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">

                    <div class="row g-3">
                        {{-- Row 1 --}}
                        <div class="col-md-6">
                            <label class="form-label" for="code">Kode Server</label>
                            <div class="input-group">
                                <input type="text" name="code" id="code" class="form-control"
                                    placeholder="Contoh: SRV-01">
                                <button type="button" id="generate-code-btn" class="btn btn-primary d-flex align-items-center gap-1" style="font-weight: 600;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
                                    </svg>
                                    Generate
                                </button>
                            </div>
                            <span class="invalid-feedback error_code"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="name">Nama Server</label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="Masukkan Nama Server">
                            <span class="invalid-feedback error_name"></span>
                        </div>

                        {{-- Row 2 --}}
                        <div class="col-md-6">
                            <label class="form-label" for="hometowns_id">Kampung</label>
                            <select name="hometowns_id" id="hometowns_id" class="form-select">
                                <option value="">-- Pilih Kampung --</option>
                                @foreach ($hometown as $hmt)
                                    <option value="{{ $hmt->id }}">{{ $hmt->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error_hometowns_id"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="link">Link Server</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5" />
                                        <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5" />
                                    </svg>
                                </span>
                                <input type="text" name="link" id="link"
                                    class="form-control border-start-0 ps-0" placeholder="https:// ...">
                            </div>
                            <span class="invalid-feedback error_link"></span>
                        </div>

                        {{-- Row 3 --}}
                        <div class="col-md-6">
                            <label class="form-label" for="foto_lokasi">Foto Lokasi <span style="color:#ef4444;">*</span></label>
                            <input type="file" name="foto_lokasi" id="foto_lokasi" class="form-control" accept="image/*">
                            <span class="invalid-feedback error_foto_lokasi"></span>
                            <div id="preview_foto_lokasi" class="mt-2" style="display:none;">
                                <div class="d-flex flex-column border rounded bg-white shadow-sm p-1" style="border-radius: 12px; width: 100%; max-width: 280px;">
                                    <img src="" class="rounded" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                                    <div class="d-flex align-items-center justify-content-between px-2 py-2 mt-1 bg-light rounded-bottom" style="font-size: 0.75rem;">
                                        <span class="preview-status-text fw-bold text-secondary">Foto Lokasi Terunggah</span>
                                        <a href="" target="_blank" class="detail-link text-primary fw-bold" style="text-decoration: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                                <path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                            </svg>Lihat Full
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="foto_pemilik">Foto Pemilik Tempat <span style="color:#ef4444;">*</span></label>
                            <input type="file" name="foto_pemilik" id="foto_pemilik" class="form-control" accept="image/*">
                            <span class="invalid-feedback error_foto_pemilik"></span>
                            <div id="preview_foto_pemilik" class="mt-2" style="display:none;">
                                <div class="d-flex flex-column border rounded bg-white shadow-sm p-1" style="border-radius: 12px; width: 100%; max-width: 280px;">
                                    <img src="" class="rounded" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                                    <div class="d-flex align-items-center justify-content-between px-2 py-2 mt-1 bg-light rounded-bottom" style="font-size: 0.75rem;">
                                        <span class="preview-status-text fw-bold text-secondary">Foto Pemilik Terunggah</span>
                                        <a href="" target="_blank" class="detail-link text-primary fw-bold" style="text-decoration: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                                <path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                            </svg>Lihat Full
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Row 4 --}}
                        <div class="col-md-12">
                            <label class="form-label" for="foto_penanggung_jawab">Foto Penanggung Jawab <span style="color:#ef4444;">*</span></label>
                            <input type="file" name="foto_penanggung_jawab" id="foto_penanggung_jawab" class="form-control" accept="image/*">
                            <span class="invalid-feedback error_foto_penanggung_jawab"></span>
                            <div id="preview_foto_penanggung_jawab" class="mt-2" style="display:none;">
                                <div class="d-flex flex-column border rounded bg-white shadow-sm p-1" style="border-radius: 12px; width: 100%; max-width: 280px;">
                                    <img src="" class="rounded" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                                    <div class="d-flex align-items-center justify-content-between px-2 py-2 mt-1 bg-light rounded-bottom" style="font-size: 0.75rem;">
                                        <span class="preview-status-text fw-bold text-secondary">Foto PJ Terunggah</span>
                                        <a href="" target="_blank" class="detail-link text-primary fw-bold" style="text-decoration: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                                <path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                            </svg>Lihat Full
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="address">Alamat Server <span style="color:#ef4444;">*</span></label>
                            <textarea name="address" id="address" class="form-control" rows="2" placeholder="Masukkan alamat lengkap Server..."></textarea>
                            <span class="invalid-feedback error_address"></span>
                        </div>

                        {{-- Map Area (Full Width at Bottom) --}}
                        <div class="col-md-12 mt-3">
                            <hr class="my-2 text-muted" style="opacity: 0.15;">
                            <label class="form-label">Lokasi Geografis (Klik peta / seret penanda untuk memilih secara manual)</label>
                            <div class="position-relative mt-2">
                                <div id="map-server"></div>
                                <div class="map-search-container" style="position: absolute; top: 10px; left: 55px; z-index: 999; width: calc(100% - 70px); max-width: 320px;">
                                    <div class="input-group bg-white border shadow-sm" style="border-radius: 30px; padding: 3px; overflow: hidden;">
                                        <span class="input-group-text bg-transparent border-0 text-muted ps-3 pe-2" style="background: transparent !important; border: none !important;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                                            </svg>
                                        </span>
                                        <input type="text" id="map-search-input" class="form-control bg-transparent border-0 ps-0" placeholder="Cari lokasi / alamat..." style="box-shadow: none !important; border: none !important; font-size: 0.85rem; height: 32px; padding: 0;">
                                        <button type="button" id="map-search-btn" class="btn btn-primary d-flex align-items-center justify-content-center" style="border-radius: 20px !important; font-size: 0.8rem; font-weight: 700; height: 32px; padding: 0 16px; margin: 0;">
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Coordinates --}}
                        <div class="col-md-6">
                            <label class="form-label small text-muted" for="latitude">Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="form-control" readonly placeholder="-6.200000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted" for="longitude">Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="form-control" readonly placeholder="106.816666">
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:1rem 1.5rem;gap:.75rem;">
                    <button type="button" class="btn btn-link link-secondary px-4"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary px-4">
                        <span id="btnText">Simpan</span>
                        <span id="btnLoading" class="spinner-border spinner-border-sm d-none ms-1" role="status"></span>
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- Detail Photos Modal --}}
    <div class="modal modal-blur fade" id="modal-detail" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius:18px;overflow:hidden;border:none;">
                <div class="modal-header"
                    style="background:linear-gradient(135deg,#1e1b4b,#4c1d95);border:none;padding:1.25rem 1.5rem;">
                    <div class="d-flex align-items-center gap-2">
                        <div
                            style="width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                            </svg>
                        </div>
                        <h5 class="modal-title mb-0" id="modal-detail-title" style="color:white;font-weight:700;font-size:.95rem;">Detail Foto Server</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:1.5rem;">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; background: #f8fafc;">
                                <div class="card-header py-2 px-3 bg-light border-0 text-center">
                                    <span class="fw-bold small text-secondary">Foto Lokasi</span>
                                </div>
                                <div class="card-body p-2 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 150px;">
                                    <a href="" id="detail_foto_lokasi_link" target="_blank" class="w-100 mb-2">
                                        <img src="" id="detail_foto_lokasi" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                                    </a>
                                    <a href="" id="download_foto_lokasi" download class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 w-100 justify-content-center" style="font-size: 0.78rem; font-weight: 600; border-radius: 8px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg> Unduh Foto
                                    </a>
                                    <div id="detail_foto_lokasi_empty" class="text-muted small py-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2 text-muted">
                                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                        </svg>
                                        <div>Belum diunggah</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; background: #f8fafc;">
                                <div class="card-header py-2 px-3 bg-light border-0 text-center">
                                    <span class="fw-bold small text-secondary">Foto Pemilik Lokasi</span>
                                </div>
                                <div class="card-body p-2 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 150px;">
                                    <a href="" id="detail_foto_pemilik_link" target="_blank" class="w-100 mb-2">
                                        <img src="" id="detail_foto_pemilik" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                                    </a>
                                    <a href="" id="download_foto_pemilik" download class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 w-100 justify-content-center" style="font-size: 0.78rem; font-weight: 600; border-radius: 8px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg> Unduh Foto
                                    </a>
                                    <div id="detail_foto_pemilik_empty" class="text-muted small py-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2 text-muted">
                                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                        </svg>
                                        <div>Belum diunggah</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; background: #f8fafc;">
                                <div class="card-header py-2 px-3 bg-light border-0 text-center">
                                    <span class="fw-bold small text-secondary">Foto Penanggung Jawab</span>
                                </div>
                                <div class="card-body p-2 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 150px;">
                                    <a href="" id="detail_foto_penanggung_jawab_link" target="_blank" class="w-100 mb-2">
                                        <img src="" id="detail_foto_penanggung_jawab" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                                    </a>
                                    <a href="" id="download_foto_penanggung_jawab" download class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 w-100 justify-content-center" style="font-size: 0.78rem; font-weight: 600; border-radius: 8px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg> Unduh Foto
                                    </a>
                                    <div id="detail_foto_penanggung_jawab_empty" class="text-muted small py-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2 text-muted">
                                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                        </svg>
                                        <div>Belum diunggah</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:1rem 1.5rem;">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        const BASE = "{{ route('server.index') }}";
        let table;

        $(function() {
            initializeDataTable();
            initializePaginationAndSearch();
            initializeModalHandlers();
            setupFilePreview("#foto_lokasi", "#preview_foto_lokasi", "Foto Lokasi Terunggah");
            setupFilePreview("#foto_pemilik", "#preview_foto_pemilik", "Foto Pemilik Terunggah");
            setupFilePreview("#foto_penanggung_jawab", "#preview_foto_penanggung_jawab", "Foto PJ Terunggah");
        });

        function setupFilePreview(inputSelector, previewContainerSelector, currentStatusText) {
            $(inputSelector).on('change', function() {
                const file = this.files[0];
                const container = $(previewContainerSelector);
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        container.show();
                        container.find('img').attr('src', e.target.result);
                        container.find('.preview-status-text').text("Foto Baru Dipilih");
                        container.find('.detail-link').hide();
                    }
                    reader.readAsDataURL(file);
                } else {
                    const existingSrc = container.find('img').data('existing-src');
                    if (existingSrc) {
                        container.show();
                        container.find('img').attr('src', existingSrc);
                        container.find('.preview-status-text').text(currentStatusText);
                        container.find('.detail-link').attr('href', existingSrc).show();
                    } else {
                        container.hide().find('img').attr('src', '').data('existing-src', '');
                    }
                }
            });
        }

        function initializeDataTable() {
            table = $('#server-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: BASE,
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                order: [
                    [8, 'desc']
                ],
                pageLength: 10,
                dom: 'rt',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'code',
                        render: function(data) {
                            return `<span class="fw-bold" style="color:var(--brand);">${data || '-'}</span>`;
                        }
                    },
                    {
                        data: 'name',
                        defaultContent: '<span class="text-muted">-</span>'
                    },
                    {
                        data: 'hometown',
                        render: function(data) {
                            return data ? data.name : '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'address',
                        render: function(data) {
                            if (!data) return '<span class="text-muted">-</span>';
                            return `<span class="text-wrap" style="max-width: 250px; display: inline-block; font-size: 0.82rem; line-height: 1.4; white-space: normal;">${data}</span>`;
                        }
                    },
                    {
                        data: 'photos',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (row.foto_lokasi || row.foto_pemilik || row.foto_penanggung_jawab) {
                                return `<button onclick="detailModal(${row.id})" class="btn d-inline-flex align-items-center gap-1 btn-sm px-2 py-1" style="background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;border-radius:6px;font-size:0.8rem;font-weight:600;" title="Detail Foto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                    </svg>
                                    Lihat Foto
                                </button>`;
                            }
                            return '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'location',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (row.latitude && row.longitude) {
                                return `<a href="https://www.google.com/maps?q=${row.latitude},${row.longitude}" target="_blank" class="btn-action d-inline-flex" style="background:#ecfdf5;color:#10b981;border:none;" title="Lihat Peta">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5" />
                                    <path d="M9 4v13" /><path d="M15 7v5" />
                                    <path d="M21 15v4.5a1.5 1.5 0 0 1 -3 0v-4.5a1.5 1.5 0 0 1 3 0" />
                                </svg>
                            </a>`;
                            }
                            return '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'link',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data) {
                            if (data) {
                                return `<a href="${data}" target="_blank" class="btn-action d-inline-flex" style="background:#e0e7ff;color:#4f46e5;border:none;" title="Buka Web Server">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                                    <path d="M11 13l9 -9" />
                                    <path d="M15 4h5v5" />
                                </svg>
                            </a>`;
                            }
                            return '<span class="text-muted small"><i>Kosong</i></span>';
                        }
                    },
                    {
                        data: 'created_at',
                        render: function(data) {
                            if (!data) return '-';
                            const d = moment(data);
                            return `<span style="color:#64748b;font-size:.82rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                style="margin-right:3px;vertical-align:middle;">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            ${d.format('DD MMM YYYY')}
                            <span style="color:#94a3b8;margin-left:4px;">${d.format('HH:mm')}</span>
                        </span>`;
                        }
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        visible: {{ auth()->user()->can('edit server') || auth()->user()->can('hapus server') ? 'true' : 'false' }}
                    }
                ],
                drawCallback: function(settings) {
                    updatePaginationInfo(settings);
                    updateCustomPagination();
                }
            });
        }

        function initializePaginationAndSearch() {
            $("#sort").on('change', function() {
                table.page.len($(this).val()).draw();
            });
            $("#search-input").on('input', function() {
                table.search(this.value).draw();
            });
        }

        function updatePaginationInfo(settings) {
            const api = new $.fn.dataTable.Api(settings);
            const info = api.page.info();
            $('#start-entry').text(info.recordsDisplay > 0 ? info.start + 1 : 0);
            $('#end-entry').text(info.end);
            $('#total-entries').text(info.recordsDisplay);
        }

        function updateCustomPagination() {
            const info = table.page.info();
            const pagination = $('#custom-pagination');
            pagination.empty();
            if (info.pages <= 1) return;

            const prevDisabled = info.page === 0 ? 'disabled' : '';
            pagination.append(`<li class="page-item ${prevDisabled}">
            <a class="page-link" href="#" data-page="${info.page - 1}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </a></li>`);

            let startPage = Math.max(0, info.page - 2);
            let endPage = Math.min(info.pages - 1, info.page + 2);
            if (startPage > 0) {
                pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
                if (startPage > 1) pagination.append(
                `<li class="page-item disabled"><span class="page-link">…</span></li>`);
            }
            for (let i = startPage; i <= endPage; i++) {
                pagination.append(`<li class="page-item ${i === info.page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i + 1}</a></li>`);
            }
            if (endPage < info.pages - 1) {
                if (endPage < info.pages - 2) pagination.append(
                    `<li class="page-item disabled"><span class="page-link">…</span></li>`);
                pagination.append(
                    `<li class="page-item"><a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a></li>`
                    );
            }

            const nextDisabled = info.page === info.pages - 1 ? 'disabled' : '';
            pagination.append(`<li class="page-item ${nextDisabled}">
            <a class="page-link" href="#" data-page="${info.page + 1}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a></li>`);

            pagination.find('a').on('click', function(e) {
                e.preventDefault();
                if (!$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
                    table.page(parseInt($(this).data('page'))).draw('page');
                }
            });
        }

        function initializeModalHandlers() {
            $("#addBtn").on('click', function() {
                resetModal();
                $(".modal-title").text("Tambah Server");
                $("#type").val('create');

                // Auto generate code on modal open
                $.get(BASE + '/generate-code')
                    .done(function(response) {
                        if (response.code === 200) {
                            $("#code").val(response.data);
                        }
                    });
            });
            $("#storeBtn").on('click', handleSave);

            // Generate Code Click Listener
            $("#generate-code-btn").on('click', function() {
                const btn = $(this);
                const originalHtml = btn.html();
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span>');

                $.get(BASE + '/generate-code')
                    .done(function(response) {
                        if (response.code === 200) {
                            $("#code").val(response.data);
                        } else {
                            showErrorMessage("Gagal generate kode");
                        }
                        btn.prop('disabled', false).html(originalHtml);
                    })
                    .fail(function() {
                        showErrorMessage("Server Error");
                        btn.prop('disabled', false).html(originalHtml);
                    });
            });

            // Map Search Event Listeners
            $("#map-search-btn").on('click', function() {
                performMapSearch();
            });
            $("#map-search-input").on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    performMapSearch();
                }
            });
        }

        let serverMap, serverMarker;

        function initLeafletMap(lat, lng) {
            const defaultLat = parseFloat(lat) || -6.200000;
            const defaultLng = parseFloat(lng) || 106.816666;

            if (!serverMap) {
                serverMap = L.map('map-server').setView([defaultLat, defaultLng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(serverMap);

                serverMarker = L.marker([defaultLat, defaultLng], {
                    draggable: true
                }).addTo(serverMap);

                // Update input values on dragend
                serverMarker.on('dragend', function (e) {
                    const position = serverMarker.getLatLng();
                    $("#latitude").val(position.lat.toFixed(8));
                    $("#longitude").val(position.lng.toFixed(8));
                });

                // Update input values and marker on map click
                serverMap.on('click', function (e) {
                    serverMarker.setLatLng(e.latlng);
                    $("#latitude").val(e.latlng.lat.toFixed(8));
                    $("#longitude").val(e.latlng.lng.toFixed(8));
                });
            } else {
                serverMap.setView([defaultLat, defaultLng], 15);
                serverMarker.setLatLng([defaultLat, defaultLng]);
            }

            $("#latitude").val(defaultLat.toFixed(8));
            $("#longitude").val(defaultLng.toFixed(8));
        }

        function performMapSearch() {
            const query = $("#map-search-input").val().trim();
            if (!query) return;

            const btn = $("#map-search-btn");
            const originalHtml = btn.html();
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span>');

            $.getJSON(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`, function(data) {
                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);

                    if (serverMap && serverMarker) {
                        serverMap.setView([lat, lon], 15);
                        serverMarker.setLatLng([lat, lon]);
                        $("#latitude").val(lat.toFixed(8));
                        $("#longitude").val(lon.toFixed(8));
                    }
                } else {
                    Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    }).fire({
                        icon: "warning",
                        title: "Lokasi tidak ditemukan"
                    });
                }
                btn.prop('disabled', false).html(originalHtml);
            }).fail(function() {
                btn.prop('disabled', false).html(originalHtml);
                Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                }).fire({
                    icon: "error",
                    title: "Gagal menghubungi layanan pencarian"
                });
            });
        }

        // Initialize / Invalidate Leaflet Map when Bootstrap Modal becomes visible
        $('#modal-simple').on('shown.bs.modal', function () {
            let lat = parseFloat($("#latitude").val());
            let lng = parseFloat($("#longitude").val());

            if (!lat || !lng) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        initLeafletMap(position.coords.latitude, position.coords.longitude);
                        if (serverMap) serverMap.invalidateSize();
                    }, function () {
                        // Fallback to Jakarta coordinates
                        initLeafletMap(-6.200000, 106.816666);
                        if (serverMap) serverMap.invalidateSize();
                    });
                } else {
                    initLeafletMap(-6.200000, 106.816666);
                    if (serverMap) serverMap.invalidateSize();
                }
            } else {
                initLeafletMap(lat, lng);
                if (serverMap) serverMap.invalidateSize();
            }
        });

        function resetModal() {
            $("#id").val('');
            $("#code").val('');
            $("#name").val('');
            $("#hometowns_id").val('');
            $("#link").val('');
            $("#foto_lokasi").val('');
            $("#foto_pemilik").val('');
            $("#foto_penanggung_jawab").val('');
            $("#latitude").val('');
            $("#longitude").val('');
            $("#map-search-input").val('');
            $("#address").val('');
            $("#preview_foto_lokasi").hide().find('img').attr('src', '').data('existing-src', '');
            $("#preview_foto_pemilik").hide().find('img').attr('src', '').data('existing-src', '');
            $("#preview_foto_penanggung_jawab").hide().find('img').attr('src', '').data('existing-src', '');
            clearValidationErrors();
        }

        function clearValidationErrors() {
            $(".is-invalid").removeClass('is-invalid');
            $(".invalid-feedback").text('');
        }

        function handleSave() {
            const type = $("#type").val();
            const id = $("#id").val();
            const url = type === 'create' ? BASE + '/store' : BASE + '/' + id + '/update';
            const method = 'POST'; // Spoofed PUT using _method for multipart/form-data upload

            const btn = $("#storeBtn");
            btn.prop('disabled', true);
            $("#btnText").addClass('d-none');
            $("#btnLoading").removeClass('d-none');

            const formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('code', $("#code").val());
            formData.append('name', $("#name").val());
            formData.append('hometowns_id', $("#hometowns_id").val());
            formData.append('link', $("#link").val());
            formData.append('latitude', $("#latitude").val());
            formData.append('longitude', $("#longitude").val());
            formData.append('address', $("#address").val());

            if (type === 'update') {
                formData.append('_method', 'PUT');
            }

            if ($("#foto_lokasi")[0].files[0]) {
                formData.append('foto_lokasi', $("#foto_lokasi")[0].files[0]);
            }
            if ($("#foto_pemilik")[0].files[0]) {
                formData.append('foto_pemilik', $("#foto_pemilik")[0].files[0]);
            }
            if ($("#foto_penanggung_jawab")[0].files[0]) {
                formData.append('foto_penanggung_jawab', $("#foto_penanggung_jawab")[0].files[0]);
            }

            $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    processData: false,
                    contentType: false
                })
                .done(function(response) {
                    if (response.errors) {
                        showValidationErrors(response.errors);
                    } else {
                        $("#modal-simple").modal('hide');
                        showSuccessMessage(response.message);
                        table.ajax.reload();
                    }
                    resetButton(btn);
                })
                .fail(function(jqXHR) {
                    if (jqXHR.status === 422) {
                        showValidationErrors(jqXHR.responseJSON.errors);
                    } else {
                        showErrorMessage("Terjadi kesalahan");
                    }
                    resetButton(btn);
                });
        }

        function editModal(id) {
            $.get(BASE + '/' + id + '/show')
                .done(function(response) {
                    const data = response.data;
                    $(".modal-title").text("Edit Server");
                    $("#modal-simple").modal('show');

                    $("#id").val(data.id);
                    $("#code").val(data.code);
                    $("#name").val(data.name);
                    $("#hometowns_id").val(data.hometowns_id);
                    $("#link").val(data.link);
                    $("#latitude").val(data.latitude);
                    $("#longitude").val(data.longitude);
                    $("#address").val(data.address);
                    $("#type").val('update');

                    // Reset file inputs
                    $("#foto_lokasi").val('');
                    $("#foto_pemilik").val('');
                    $("#foto_penanggung_jawab").val('');
                    $("#map-search-input").val('');

                    // Render previews
                    if (data.foto_lokasi) {
                        $("#preview_foto_lokasi").show();
                        $("#preview_foto_lokasi").find('img').attr('src', `/${data.foto_lokasi}`).data('existing-src', `/${data.foto_lokasi}`);
                        $("#preview_foto_lokasi").find('.preview-status-text').text("Foto Lokasi Terunggah");
                        $("#preview_foto_lokasi").find('.detail-link').attr('href', `/${data.foto_lokasi}`).show();
                    } else {
                        $("#preview_foto_lokasi").hide().find('img').attr('src', '').data('existing-src', '');
                    }
                    if (data.foto_pemilik) {
                        $("#preview_foto_pemilik").show();
                        $("#preview_foto_pemilik").find('img').attr('src', `/${data.foto_pemilik}`).data('existing-src', `/${data.foto_pemilik}`);
                        $("#preview_foto_pemilik").find('.preview-status-text').text("Foto Pemilik Terunggah");
                        $("#preview_foto_pemilik").find('.detail-link').attr('href', `/${data.foto_pemilik}`).show();
                    } else {
                        $("#preview_foto_pemilik").hide().find('img').attr('src', '').data('existing-src', '');
                    }
                    if (data.foto_penanggung_jawab) {
                        $("#preview_foto_penanggung_jawab").show();
                        $("#preview_foto_penanggung_jawab").find('img').attr('src', `/${data.foto_penanggung_jawab}`).data('existing-src', `/${data.foto_penanggung_jawab}`);
                        $("#preview_foto_penanggung_jawab").find('.preview-status-text').text("Foto PJ Terunggah");
                        $("#preview_foto_penanggung_jawab").find('.detail-link').attr('href', `/${data.foto_penanggung_jawab}`).show();
                    } else {
                        $("#preview_foto_penanggung_jawab").hide().find('img').attr('src', '').data('existing-src', '');
                    }
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan saat mengambil data");
                });
        }

        function detailModal(id) {
            $.get(BASE + '/' + id + '/show')
                .done(function(response) {
                    const data = response.data;
                    $("#modal-detail-title").text(`Detail Foto Server - ${data.name}`);

                    // Foto Lokasi
                    if (data.foto_lokasi) {
                        $("#detail_foto_lokasi").attr('src', `/${data.foto_lokasi}`).show();
                        $("#detail_foto_lokasi_link").attr('href', `/${data.foto_lokasi}`).show();
                        $("#download_foto_lokasi").attr('href', `/${data.foto_lokasi}`).show();
                        $("#detail_foto_lokasi_empty").hide();
                    } else {
                        $("#detail_foto_lokasi").attr('src', '').hide();
                        $("#detail_foto_lokasi_link").attr('href', '').hide();
                        $("#download_foto_lokasi").attr('href', '').hide();
                        $("#detail_foto_lokasi_empty").show();
                    }

                    // Foto Pemilik Tempat
                    if (data.foto_pemilik) {
                        $("#detail_foto_pemilik").attr('src', `/${data.foto_pemilik}`).show();
                        $("#detail_foto_pemilik_link").attr('href', `/${data.foto_pemilik}`).show();
                        $("#download_foto_pemilik").attr('href', `/${data.foto_pemilik}`).show();
                        $("#detail_foto_pemilik_empty").hide();
                    } else {
                        $("#detail_foto_pemilik").attr('src', '').hide();
                        $("#detail_foto_pemilik_link").attr('href', '').hide();
                        $("#download_foto_pemilik").attr('href', '').hide();
                        $("#detail_foto_pemilik_empty").show();
                    }

                    // Foto Penanggung Jawab
                    if (data.foto_penanggung_jawab) {
                        $("#detail_foto_penanggung_jawab").attr('src', `/${data.foto_penanggung_jawab}`).show();
                        $("#detail_foto_penanggung_jawab_link").attr('href', `/${data.foto_penanggung_jawab}`).show();
                        $("#download_foto_penanggung_jawab").attr('href', `/${data.foto_penanggung_jawab}`).show();
                        $("#detail_foto_penanggung_jawab_empty").hide();
                    } else {
                        $("#detail_foto_penanggung_jawab").attr('src', '').hide();
                        $("#detail_foto_penanggung_jawab_link").attr('href', '').hide();
                        $("#download_foto_penanggung_jawab").attr('href', '').hide();
                        $("#detail_foto_penanggung_jawab_empty").show();
                    }

                    $("#modal-detail").modal('show');
                })
                .fail(function() {
                    showErrorMessage("Terjadi kesalahan saat mengambil data");
                });
        }

        function deleteServer(id) {
            Swal.fire({
                title: "Hapus Server?",
                text: "Data Server ini akan dihapus permanen.",
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
                    $.ajax({
                            url: BASE + '/' + id + '/destroy',
                            method: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            }
                        })
                        .done(function(response) {
                            showSuccessMessage(response.message);
                            table.ajax.reload();
                        })
                        .fail(function() {
                            showErrorMessage("Server Error");
                        });
                }
            });
        }

        function showValidationErrors(errors) {
            clearValidationErrors();
            Object.keys(errors).forEach(function(field) {
                const input = $("#" + field);
                input.addClass('is-invalid');
                if (input.parent().hasClass('input-group')) {
                    input.parent().addClass('is-invalid');
                }
                $(".error_" + field).text(errors[field]);
            });
            setTimeout(clearValidationErrors, 3000);
        }

        function showSuccessMessage(message) {
            Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                })
                .fire({
                    icon: "success",
                    title: message
                });
        }

        function showErrorMessage(message) {
            Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                })
                .fire({
                    icon: "error",
                    title: message
                });
        }

        function resetButton(btn) {
            btn.prop('disabled', false);
            $("#btnText").removeClass('d-none');
            $("#btnLoading").addClass('d-none');
        }
    </script>
@endpush

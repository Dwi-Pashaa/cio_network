@extends('layouts.app')

@section('title')
    Riwayat Transfer Barang Operasional
@endsection

@section('content')
<div class="org-container">
    <div class="org-card">
        {{-- HEADER --}}
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon" style="background:#eff6ff; color:#2563eb;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 8l0 4l2 2" />
                        <path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" />
                    </svg>
                </div>
                <div>
                    <h5 class="org-title">Riwayat Mutasi & Transfer Barang Operasional</h5>
                    <div class="org-subtitle">Log lengkap distribusi barang dan persetujuan (ACC) transfer dari pengirim ke penerima</div>
                </div>
            </div>

            <a href="{{ route('barang-operasional.index') }}" class="btn btn-outline-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1"/></svg>
                Kembali ke Data Barang
            </a>
        </div>

        {{-- TOOLBAR --}}
        <div class="org-toolbar flex-wrap gap-2">
            <div style="font-size:.85rem; font-weight:600; color:var(--text-muted); display:flex; align-items:center; gap:.5rem;">
                Tampilkan
                <select id="sort" class="org-input" style="padding: .35rem .6rem;">
                    @foreach([10,25,50,100] as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
                data
            </div>

            <div style="font-size:.85rem; font-weight:600; color:var(--text-muted); display:flex; align-items:center; gap:.5rem;" class="ms-sm-3">
                Status
                <select id="filter-status" class="org-input" style="padding: .35rem .6rem; min-width: 170px;">
                    <option value="">Semua Status</option>
                    <option value="menunggu_konfirmasi">Menunggu Konfirmasi</option>
                    <option value="diterima">Diterima (ACC)</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>

            <div class="search-wrapper ms-auto">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="search-input" class="org-input" placeholder="Cari transaksi, barang, pengirim, penerima…">
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table id="riwayat-table" class="org-table">
                <thead>
                    <tr>
                        <th style="width:40px; text-align:center;">No</th>
                        <th>Kode Transaksi</th>
                        <th>Pengirim</th>
                        <th>Penerima</th>
                        <th>Barang & Identitas</th>
                        <th style="text-align:center;">Jumlah</th>
                        <th>Tanggal Kirim</th>
                        <th>Catatan</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>

        {{-- FOOTER --}}
        <div class="org-footer">
            <div class="org-info" id="table-info">
                Menampilkan <span id="start-entry">0</span> sampai <span id="end-entry">0</span> dari <span id="total-entries">0</span> data
            </div>
            <ul class="pagination" id="custom-pagination"></ul>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        const table = $('#riwayat-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('barang-operasional.riwayat') }}",
                data: function(d) {
                    d.status = $('#filter-status').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'kode_transaksi', name: 'transfer_barang_operasionals.kode_transaksi' },
                { data: 'pengirim_nama', name: 'pengirim.name' },
                { data: 'penerima_nama', name: 'penerima.name' },
                { data: 'barang_nama', name: 'barang_operasionals.nama_barang' },
                { data: 'jumlah_badge', name: 'transfer_barang_operasionals.jumlah', className: 'text-center' },
                { data: 'tanggal', name: 'transfer_barang_operasionals.tanggal_transfer' },
                { data: 'catatan', name: 'transfer_barang_operasionals.catatan', defaultContent: '-' },
                { data: 'status_badge', name: 'transfer_barang_operasionals.status', className: 'text-center' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
            ],
            dom: 't',
            order: [[6, 'desc']],
            pageLength: 10,
            drawCallback: function(settings) {
                const info = this.api().page.info();
                $('#start-entry').text(info.recordsTotal > 0 ? info.start + 1 : 0);
                $('#end-entry').text(info.end);
                $('#total-entries').text(info.recordsTotal);
                buildPagination(info);
            }
        });

        $('#sort').on('change', function() {
            table.page.len($(this).val()).draw();
        });

        $('#filter-status').on('change', function() {
            table.ajax.reload();
        });

        $('#search-input').on('keyup', function() {
            table.search(this.value).draw();
        });

        // ==========================================
        // Event Handler: ACC / Terima Barang
        // ==========================================
        $(document).on('click', '.btn-acc', function() {
            const id = $(this).data('id');
            const kode = $(this).data('kode');
            const barang = $(this).data('barang');
            const jumlah = $(this).data('jumlah');

            Swal.fire({
                title: 'ACC Penerimaan Barang?',
                html: `
                    <div class="text-muted mb-2">Apakah Anda sudah menerima fisik barang berikut?</div>
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; margin-bottom:12px;" class="text-start">
                        <div style="font-size:0.8rem; color:#64748b;">Kode Transaksi: <strong>${kode}</strong></div>
                        <div class="fw-bold text-dark mt-1" style="font-size:1rem;">${barang}</div>
                        <div class="text-primary fw-bold small mt-1">Jumlah: ${jumlah}</div>
                    </div>
                    <div class="text-start">
                        <label class="form-label small fw-bold text-secondary">Catatan Penerima (Opsional):</label>
                        <textarea id="acc-catatan" class="form-control" rows="2" placeholder="Contoh: Barang fisik diterima lengkap & kondisi baik"></textarea>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: '✓ Ya, Terima & ACC',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const catatan = $('#acc-catatan').val();
                    return $.ajax({
                        url: `{{ url('barang-operasional/transfer') }}/${id}/acc`,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            catatan: catatan
                        }
                    }).catch(err => {
                        Swal.showValidationMessage(err.responseJSON?.message || 'Terjadi kesalahan sistem.');
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value?.code === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil di-ACC!',
                        text: result.value.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                    table.ajax.reload();
                }
            });
        });

        // ==========================================
        // Event Handler: Tolak Penerimaan Barang
        // ==========================================
        $(document).on('click', '.btn-reject', function() {
            const id = $(this).data('id');
            const kode = $(this).data('kode');
            const barang = $(this).data('barang');

            Swal.fire({
                title: 'Tolak Penerimaan Barang?',
                html: `
                    <div class="text-muted mb-2">Anda akan menolak pengiriman barang:</div>
                    <div style="background:#fef2f2; border:1px solid #fee2e2; border-radius:8px; padding:12px; margin-bottom:12px;" class="text-start">
                        <div style="font-size:0.8rem; color:#991b1b;">Kode Transaksi: <strong>${kode}</strong></div>
                        <div class="fw-bold text-danger mt-1" style="font-size:1rem;">${barang}</div>
                    </div>
                    <div class="text-start">
                        <label class="form-label small fw-bold text-danger">Alasan Penolakan <span class="text-danger">*</span>:</label>
                        <textarea id="reject-alasan" class="form-control" rows="2" placeholder="Contoh: Fisik barang rusak / jumlah unit tidak sesuai"></textarea>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: '✕ Ya, Tolak!',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const alasan = $('#reject-alasan').val()?.trim();
                    if (!alasan) {
                        Swal.showValidationMessage('Alasan penolakan wajib diisi!');
                        return false;
                    }
                    return $.ajax({
                        url: `{{ url('barang-operasional/transfer') }}/${id}/reject`,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            alasan: alasan
                        }
                    }).catch(err => {
                        Swal.showValidationMessage(err.responseJSON?.message || 'Terjadi kesalahan sistem.');
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value?.code === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Ditolak',
                        text: result.value.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                    table.ajax.reload();
                }
            });
        });

        function buildPagination(info) {
            const $ul = $('#custom-pagination').empty();
            if (info.pages <= 1) return;

            $ul.append(`
                <li class="page-item ${info.page === 0 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${info.page - 1}">Sebelumnya</a>
                </li>
            `);

            for (let i = 0; i < info.pages; i++) {
                if (i === 0 || i === info.pages - 1 || (i >= info.page - 1 && i <= info.page + 1)) {
                    $ul.append(`
                        <li class="page-item ${i === info.page ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i + 1}</a>
                        </li>
                    `);
                } else if (i === info.page - 2 || i === info.page + 2) {
                    $ul.append('<li class="page-item disabled"><span class="page-link">…</span></li>');
                }
            }

            $ul.append(`
                <li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${info.page + 1}">Selanjutnya</a>
                </li>
            `);

            $ul.find('a').off('click').on('click', function(e) {
                e.preventDefault();
                const p = $(this).data('page');
                if (p !== undefined && p >= 0) table.page(p).draw('page');
            });
        }
    });
</script>
@endpush

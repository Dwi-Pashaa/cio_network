@extends('layouts.app')

@section('title')
    Log WA Blast
@endsection

@push('css')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

    :root {
        --brand       : #6366f1;
        --brand-light : #818cf8;
        --brand-glow  : rgba(99,102,241,.15);
        --surface     : #ffffff;
        --surface-2   : #f8f8fc;
        --border      : #e8e8f0;
        --text-primary: #1e1e2e;
        --text-muted  : #6b7280;
        --success     : #22c55e;
        --warning     : #f59e0b;
        --danger      : #ef4444;
        --info        : #3b82f6;
        --radius      : 14px;
        --shadow-md   : 0 4px 16px rgba(99,102,241,.10);
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4f4f9; }

    .rpt-card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow-md); overflow: hidden; }

    /* HEADER */
    .rpt-header { padding: 1.4rem 1.75rem; border-bottom: 1px solid var(--border); background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%); display: flex; align-items: center; gap: .75rem; }
    .rpt-header-icon { width: 40px; height: 40px; background: rgba(255,255,255,.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; }
    .rpt-header h5 { margin: 0; color: #fff; font-weight: 700; font-size: 1rem; }
    .rpt-header small { color: rgba(255,255,255,.75); font-size: .78rem; }

    /* TOOLBAR */
    .rpt-toolbar { padding: 1.1rem 1.75rem; border-bottom: 1px solid var(--border); background: var(--surface-2); display: flex; flex-wrap: wrap; gap: .75rem; align-items: flex-end; justify-content: space-between; }

    /* FILTER */
    .rpt-filter { padding: 1.1rem 1.75rem; border-bottom: 1px solid var(--border); display: flex; flex-wrap: wrap; gap: .75rem; align-items: flex-end; background: var(--surface); }
    .rpt-filter .form-label { font-size: .78rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .35rem; display: block; }
    .rpt-filter .form-control,
    .rpt-filter .form-select {
        border: 1.5px solid var(--border); border-radius: 9px; font-size: .875rem;
        color: var(--text-primary); padding: .48rem .85rem;
        transition: border-color .2s, box-shadow .2s; background: var(--surface);
        font-family: inherit;
    }
    .rpt-filter .form-control:focus,
    .rpt-filter .form-select:focus { border-color: var(--brand); box-shadow: 0 0 0 3px var(--brand-glow); outline: none; }

    /* SHOW */
    .show-label { font-size: .875rem; font-weight: 500; color: var(--text-muted); }
    #sort { border: 1.5px solid var(--border); border-radius: 9px; font-size: .875rem; padding: .42rem .75rem; color: var(--text-primary); font-family: inherit; cursor: pointer; }
    #sort:focus { border-color: var(--brand); outline: none; box-shadow: 0 0 0 3px var(--brand-glow); }

    /* SEARCH */
    .rpt-search { position: relative; }
    .rpt-search input { border: 1.5px solid var(--border); border-radius: 10px; font-size: .875rem; padding: .48rem 2.8rem .48rem .9rem; width: 260px; color: var(--text-primary); font-family: inherit; transition: border-color .2s, box-shadow .2s; }
    .rpt-search input:focus { border-color: var(--brand); box-shadow: 0 0 0 3px var(--brand-glow); outline: none; }
    .rpt-search input::placeholder { color: #b0b0c0; }
    .rpt-search .search-icon { position: absolute; right: .85rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); cursor: pointer; transition: color .2s; }
    .rpt-search .search-icon:hover { color: var(--brand); }

    /* BUTTONS */
    .btn-brand { background: var(--brand); color: #fff; border: none; border-radius: 9px; font-size: .84rem; font-weight: 600; padding: .48rem 1.1rem; cursor: pointer; display: inline-flex; align-items: center; gap: .35rem; transition: background .2s, box-shadow .2s, transform .1s; font-family: inherit; }
    .btn-brand:hover { background: #4f52e8; box-shadow: 0 4px 12px rgba(99,102,241,.35); }
    .btn-brand:active { transform: scale(.97); }
    .btn-ghost { background: transparent; color: var(--text-muted); border: 1.5px solid var(--border); border-radius: 9px; font-size: .84rem; font-weight: 600; padding: .48rem 1rem; cursor: pointer; display: inline-flex; align-items: center; gap: .35rem; transition: border-color .2s, color .2s, background .2s; font-family: inherit; }
    .btn-ghost:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-glow); }

    /* TABLE */
    .rpt-table-wrap { overflow-x: auto; }
    #report-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: .86rem; }
    #report-table thead th { background: var(--surface-2); color: var(--text-muted); font-weight: 700; font-size: .75rem; text-transform: uppercase; letter-spacing: .06em; padding: .9rem 1rem; border-bottom: 2px solid var(--border); white-space: nowrap; border-top: none; }
    #report-table tbody tr { transition: background .15s; border-bottom: 1px solid var(--border); }
    #report-table tbody tr:last-child { border-bottom: none; }
    #report-table tbody tr:hover { background: #f5f5ff; }
    #report-table tbody td { padding: .85rem 1rem; color: var(--text-primary); vertical-align: middle; white-space: nowrap; }
    #report-table tbody td:first-child { font-family: 'JetBrains Mono', monospace; font-size: .78rem; color: var(--text-muted); font-weight: 500; }

    .cell-date { font-family: 'JetBrains Mono', monospace; font-size: .8rem; color: var(--text-primary); }
    .cell-wa   { font-family: 'JetBrains Mono', monospace; font-size: .8rem; color: var(--text-muted); }
    .cell-id   { font-family: 'JetBrains Mono', monospace; font-size: .8rem; color: var(--brand); font-weight: 600; }

    /* STATUS BADGES */
    .rpt-badge { display: inline-flex; align-items: center; gap: .25rem; padding: .28rem .75rem; border-radius: 99px; font-size: .73rem; font-weight: 700; letter-spacing: .02em; }
    .badge-success   { background: #dcfce7; color: #15803d; }
    .badge-sent      { background: #dcfce7; color: #15803d; }
    .badge-pending   { background: #fef9c3; color: #a16207; }
    .badge-failed    { background: #fee2e2; color: #b91c1c; }
    .badge-delivered { background: #dbeafe; color: #1d4ed8; }
    .badge-default   { background: #f3f4f6; color: #374151; }

    /* PESAN */
    .cell-message { max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: .84rem; }

    /* ACTION */
    .btn-wa { display: inline-flex; align-items: center; gap: .35rem; background: #dcfce7; color: #15803d; border: none; border-radius: 8px; padding: .38rem .85rem; font-size: .8rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: background .2s, transform .1s, box-shadow .2s; font-family: inherit; white-space: nowrap; }
    .btn-wa:hover { background: #bbf7d0; color: #15803d; box-shadow: 0 3px 10px rgba(21,128,61,.2); }
    .btn-wa:active { transform: scale(.96); }

    /* FOOTER */
    .rpt-footer { padding: 1rem 1.75rem; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; background: var(--surface-2); }
    .rpt-info { font-size: .82rem; color: var(--text-muted); font-weight: 500; }
    .rpt-info b { color: var(--text-primary); }

    /* PAGINATION */
    .rpt-pagination { display: flex; gap: .3rem; list-style: none; margin: 0; padding: 0; }
    .rpt-pagination li a, .rpt-pagination li span { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 9px; font-size: .82rem; font-weight: 600; border: 1.5px solid var(--border); color: var(--text-primary); background: var(--surface); text-decoration: none; transition: all .18s; cursor: pointer; }
    .rpt-pagination li a:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-glow); }
    .rpt-pagination li.active a { background: var(--brand); border-color: var(--brand); color: #fff; }
    .rpt-pagination li.disabled span, .rpt-pagination li.disabled a { opacity: .4; cursor: default; pointer-events: none; }

    /* EMPTY */
    .rpt-empty { padding: 4rem 2rem; text-align: center; color: var(--text-muted); }
    .rpt-empty svg { opacity: .25; display: block; margin: 0 auto .75rem; }
    .rpt-empty p { margin: 0; font-size: .9rem; }

    @keyframes fadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
    @keyframes spin   { to { transform: rotate(360deg); } }
    #report-table tbody tr { animation: fadeIn .2s ease both; }
</style>
@endpush

@section('content')
<div class="rpt-card">

    {{-- HEADER --}}
    <div class="rpt-header">
        <div class="rpt-header-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
        </div>
        <div>
            <h5>Log WA Blast</h5>
            <small>Riwayat pengiriman pesan WhatsApp</small>
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="rpt-toolbar">
        <div class="d-flex align-items-center gap-2">
            <span class="show-label">Tampilkan</span>
            <select id="sort">
                @foreach([10,25,50,100] as $opt)
                    <option value="{{ $opt }}">{{ $opt }}</option>
                @endforeach
            </select>
            <span class="show-label">entri</span>
        </div>
        <div class="rpt-search">
            <input type="text" id="search-input" placeholder="Cari nomor, pesan, status…">
            <svg class="search-icon" id="search-btn" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="rpt-filter">

        <div>
            <label class="form-label">Dari Tanggal</label>
            <input type="date" id="date-from" class="form-control" style="width:170px;"
                   value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
        </div>

        <div>
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" id="date-to" class="form-control" style="width:170px;"
                   value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
        </div>

        <div>
            <label class="form-label">Status</label>
            <select id="filter-status" class="form-select" style="width:160px;">
                <option value="">Semua Status</option>
                <option value="sent">Sent</option>
                <option value="pending">Pending</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button class="btn-brand" id="btn-filter">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                </svg>
                Filter
            </button>
            <button class="btn-ghost" id="btn-reset">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4"/>
                </svg>
                Reset
            </button>
        </div>

    </div>

    {{-- ALERT AUTO REFRESH --}}
    <div id="alert-refresh" style="
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        padding: .85rem 1.75rem;
        background: #fffbeb;
        border-bottom: 1px solid #fde68a;
        font-size: .84rem;
        color: #92400e;">

        {{-- Icon --}}
        <div style="flex-shrink:0; margin-top:.1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>

        {{-- Teks --}}
        <div style="flex:1; line-height:1.6;">
            <span style="font-weight:700;">Auto Refresh Aktif</span> —
            Sistem akan mengambil data baru secara otomatis dari Wablas setiap
            <span style="font-weight:700;">10 menit</span> sekali.
            Pembaruan berikutnya dalam
            <span id="countdown" style="
                font-family:'JetBrains Mono',monospace;
                font-weight:700;
                color:#b45309;
                background:#fef3c7;
                padding:.1rem .45rem;
                border-radius:6px;">10:00</span>.
        </div>

    </div>

    {{-- TABLE --}}
    <div class="rpt-table-wrap">
        <table id="report-table">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Tanggal</th>
                    <th>WA Pelanggan</th>
                    <th>Pesan</th>
                    <th>Status</th>
                    <th style="width:150px;">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    {{-- FOOTER --}}
    <div class="rpt-footer">
        <p class="rpt-info">
            Menampilkan <b id="start-entry">0</b>–<b id="end-entry">0</b>
            dari <b id="total-entries">0</b> entri
        </p>
        <ul class="rpt-pagination" id="custom-pagination"></ul>
    </div>

</div>
@endsection

{{-- MODAL --}}
@push('modal')
<div id="modal-pesan" style="
    display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,.45); backdrop-filter:blur(4px);
    align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; width:90%; max-width:560px;
                max-height:80vh; display:flex; flex-direction:column;
                box-shadow:0 20px 60px rgba(0,0,0,.2); overflow:hidden;">

        <div style="padding:1.1rem 1.5rem; border-bottom:1px solid #e8e8f0;
                    display:flex; align-items:center; justify-content:space-between;
                    background:linear-gradient(135deg,#6366f1,#818cf8);">
            <div style="display:flex;align-items:center;gap:.6rem;color:#fff;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <span style="font-weight:700;font-size:.95rem;">Isi Pesan</span>
            </div>
            <button id="modal-close" style="background:rgba(255,255,255,.2); border:none; border-radius:8px;
                    color:#fff; width:30px; height:30px; cursor:pointer;
                    display:flex; align-items:center; justify-content:center; font-size:1.1rem;">✕</button>
        </div>

        <div id="modal-body" style="padding:1.4rem 1.5rem; overflow-y:auto; flex:1;
             font-size:.875rem; line-height:1.75; color:#1e1e2e;
             white-space:pre-wrap; word-break:break-word; font-family:'Plus Jakarta Sans',sans-serif;">
        </div>

        <div style="padding:.85rem 1.5rem; border-top:1px solid #e8e8f0;
                    display:flex; justify-content:flex-end; background:#f8f8fc;">
            <button id="modal-close-btn" style="background:#6366f1; color:#fff; border:none; border-radius:9px;
                    padding:.45rem 1.2rem; font-size:.84rem; font-weight:600; cursor:pointer; font-family:inherit;">
                Tutup
            </button>
        </div>

    </div>
</div>
@endpush

@push('js')
<script>

    const BASE = "{{ route('report.index') }}";
    let table;

    $(function () {

        table = $('#report-table').DataTable({
            processing : true,
            serverSide : true,
            ajax: {
                url : BASE,
                data: function (d) {
                    d.date_from = $('#date-from').val();
                    d.date_to   = $('#date-to').val();
                    d.status    = $('#filter-status').val();
                }
            },
            order     : [[1, 'desc']],
            pageLength: 10,
            dom       : 'rt',

            columns: [

                // NO
                {
                    data: null, orderable: false, searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },

                // TANGGAL
                {
                    data: 'date',
                    render: function (data) {
                        if (!data) return '-';
                        return `<span class="cell-date">${moment(data).format('DD/MM/YYYY HH:mm')}</span>`;
                    }
                },

                // WA PELANGGAN
                {
                    data: 'to',
                    render: function (data) {
                        return data ? `<span class="cell-wa">${data}</span>` : '-';
                    }
                },

                // PESAN
                {
                    data: 'message',
                    render: function (data) {
                        if (!data) return '-';
                        const max     = 60;
                        const escaped = $('<div>').text(data).html();
                        const preview = escaped.length > max ? escaped.substring(0, max) + '…' : escaped;
                        const encoded = encodeURIComponent(data);
                        return `
                            <span class="cell-message cell-message-click"
                                  data-message="${encoded}"
                                  title="Klik untuk lihat pesan lengkap"
                                  style="cursor:pointer; border-bottom:1px dashed #6366f1; color:#6366f1;">
                                ${preview}
                            </span>`;
                    }
                },
                // STATUS
                {
                    data: 'status',
                    render: function (data, type, row) {

                        // Jika sent_at null → pending, apapun nilai status-nya
                        const key = !row.sent_at ? 'pending' : (data?.toLowerCase() || 'pending');

                        const map = {
                            'success'  : ['badge-success',   'Sukses'],
                            'sent'     : ['badge-sent',       'Terkirim'],
                            'delivered': ['badge-delivered',  'Diterima'],
                            'pending'  : ['badge-pending',    'Pending'],
                            'failed'   : ['badge-failed',     'Gagal'],
                        };

                        const dots = {
                            'success'  : '#15803d',
                            'sent'     : '#15803d',
                            'delivered': '#1d4ed8',
                            'pending'  : '#a16207',
                            'failed'   : '#b91c1c',
                        };

                        const [cls, label] = map[key] ?? ['badge-default', data ?? '-'];
                        const dot = dots[key] ?? '#6b7280';

                        return `
                            <span class="rpt-badge ${cls}">
                                <svg width="7" height="7" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="4" fill="${dot}"/>
                                </svg>
                                ${label}
                            </span>`;
                    }
                },

                // ACTION
                {
                    data      : null,
                    orderable : false,
                    searchable: false,
                    render    : function (data, type, row) {

                        // Jika sent_at sudah terisi → sudah dikirim, sembunyikan tombol
                        if (row.sent_at) {
                            const waktu = moment(row.sent_at).format('DD/MM/YYYY HH:mm');
                            return `<span style="font-family:'JetBrains Mono',monospace; font-size:.75rem; color:#6b7280;">
                                        ✓ ${waktu}
                                    </span>`;
                        }

                        // sent_at null → belum dikirim, tampilkan tombol
                        let normalized = (row.phone ?? '').replace(/\D/g, '');
                        if (normalized.startsWith('0')) {
                            normalized = '62' + normalized.slice(1);
                        }

                        const message = encodeURIComponent(row.message ?? '');

                        return `
                            <button
                                class="btn-wa btn-send-wa"
                                data-id="${row.id}"
                                data-phone="${normalized}"
                                data-message="${message}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="22" y1="2" x2="11" y2="13"/>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                                </svg>
                                Kirim Pemberitahuan
                            </button>`;
                    }
                },
            ],

            drawCallback: function (settings) {
                updatePaginationInfo(settings);
                updateCustomPagination();
            },

            language: {
                processing : '<svg style="animation:spin 1s linear infinite" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5" stroke-linecap="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Memuat data…',
                emptyTable : '<div class="rpt-empty"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"/><path d="M3 9l2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><line x1="12" y1="3" x2="12" y2="9"/></svg><p>Tidak ada data ditemukan</p></div>',
                zeroRecords: '<div class="rpt-empty"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg><p>Tidak ada data yang cocok dengan pencarian</p></div>',
            }
        });

        // SHOW ENTRIES
        $('#sort').val(10);
        $('#sort').change(function () { table.page.len(parseInt($(this).val())).draw(); });

        // SEARCH
        $('#search-input').on('keyup', function (e) { if (e.which === 13) table.search(this.value).draw(); });
        $('#search-btn').click(function () { table.search($('#search-input').val()).draw(); });

        // FILTER
        $('#btn-filter').click(function () {
            const from = $('#date-from').val();
            const to   = $('#date-to').val();
            if (from && to && from > to) {
                alert('Tanggal "Dari" tidak boleh lebih besar dari "Sampai".');
                return;
            }
            table.draw();
        });

        // RESET
        $('#btn-reset').click(function () {
            $('#date-from').val('{{ \Carbon\Carbon::now()->format("Y-m-d") }}');
            $('#date-to').val('{{ \Carbon\Carbon::now()->format("Y-m-d") }}');
            $('#filter-status').val('');
            $('#search-input').val('');
            table.search('').draw();
        });

        // PAGINATION
        $(document).on('click', '#custom-pagination a', function (e) {
            e.preventDefault();
            const info = table.page.info();
            const page = parseInt($(this).data('page'));
            if (!isNaN(page) && page >= 0 && page < info.pages) table.page(page).draw('page');
        });

        // MODAL — buka
        $(document).on('click', '.cell-message-click', function () {
            const raw = decodeURIComponent($(this).data('message'));
            $('#modal-body').text(raw);
            $('#modal-pesan').css('display', 'flex');
        });

        // MODAL — tutup
        $(document).on('click', '#modal-close, #modal-close-btn', function () {
            $('#modal-pesan').hide();
            $('#modal-body').text('');
        });

        // MODAL — klik backdrop
        $(document).on('click', '#modal-pesan', function (e) {
            if ($(e.target).is('#modal-pesan')) { $(this).hide(); $('#modal-body').text(''); }
        });

        // MODAL — ESC
        $(document).on('keydown', function (e) {
            if (e.key === 'Escape') { $('#modal-pesan').hide(); $('#modal-body').text(''); }
        });

    });

    function updatePaginationInfo(settings) {
        const info = new $.fn.dataTable.Api(settings).page.info();
        $('#start-entry').text(info.recordsDisplay > 0 ? info.start + 1 : 0);
        $('#end-entry').text(info.end);
        $('#total-entries').text(info.recordsDisplay);
    }

    function updateCustomPagination() {
        const info       = table.page.info();
        const pagination = $('#custom-pagination');
        pagination.empty();

        if (info.pages <= 1) return;

        pagination.append(`<li class="${info.page === 0 ? 'disabled' : ''}"><a data-page="${info.page - 1}" href="#">‹</a></li>`);

        const winSize   = 2;
        const startPage = Math.max(0, info.page - winSize);
        const endPage   = Math.min(info.pages - 1, info.page + winSize);

        if (startPage > 0) {
            pagination.append(`<li><a data-page="0" href="#">1</a></li>`);
            if (startPage > 1) pagination.append(`<li class="disabled"><span>…</span></li>`);
        }

        for (let i = startPage; i <= endPage; i++) {
            pagination.append(`<li class="${i === info.page ? 'active' : ''}"><a data-page="${i}" href="#">${i + 1}</a></li>`);
        }

        if (endPage < info.pages - 1) {
            if (endPage < info.pages - 2) pagination.append(`<li class="disabled"><span>…</span></li>`);
            pagination.append(`<li><a data-page="${info.pages - 1}" href="#">${info.pages}</a></li>`);
        }

        pagination.append(`<li class="${info.page === info.pages - 1 ? 'disabled' : ''}"><a data-page="${info.page + 1}" href="#">›</a></li>`);
    }

    $(document).on('click', '.btn-send-wa', function () {
        let button  = $(this);
        let id      = button.data('id');
        let phone   = button.data('phone');
        let message = button.data('message');

        button.prop('disabled', true).text('Updating...');

        $.ajax({
            url: `${BASE}/${id}/update`,
            type: 'PUT',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {

                if (res.success) {
                    $('#report-table').DataTable().ajax.reload(null, false);

                    window.open(`https://wa.me/${phone}?text=${message}`, '_blank');

                } else {
                    alert('Gagal update status');
                    button.prop('disabled', false).text('Kirim Pemberitahuan');
                }
            },
            error: function () {
                alert('Terjadi kesalahan server');
                button.prop('disabled', false).text('Kirim Pemberitahuan');
            }
        });
    });
</script>
<script>
    // ── AUTO REFRESH & COUNTDOWN ──
    const INTERVAL_MS  = 10 * 60 * 1000; // 10 menit
    let   remainingSec = 10 * 60;
    let   countdownTimer;
    let   autoRefreshTimer;

    function formatTime(sec) {
        const m = String(Math.floor(sec / 60)).padStart(2, '0');
        const s = String(sec % 60).padStart(2, '0');
        return `${m}:${s}`;
    }

    function startCountdown() {
        clearInterval(countdownTimer);
        remainingSec = 10 * 60;
        $('#countdown').text(formatTime(remainingSec));

        countdownTimer = setInterval(function () {
            remainingSec--;
            $('#countdown').text(formatTime(remainingSec));

            if (remainingSec <= 60) {
                $('#countdown').css({ background: '#fee2e2', color: '#b91c1c' });
            } else {
                $('#countdown').css({ background: '#fef3c7', color: '#b45309' });
            }

            if (remainingSec <= 0) {
                clearInterval(countdownTimer);
            }
        }, 1000);
    }

    setInterval(function () {
        table.draw('page');
    }, 10 * 60 * 1000);

    startCountdown();
    autoRefreshTimer = setInterval(startCountdown, INTERVAL_MS);
</script>
@endpush
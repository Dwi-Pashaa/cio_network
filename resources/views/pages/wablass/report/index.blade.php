@extends('layouts.app')

@section('title')
    Log WA Blast
@endsection

@push('css')
<style>
    /* ── Google Font ── */
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
        --shadow-sm   : 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md   : 0 4px 16px rgba(99,102,241,.10);
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4f4f9; }

    /* ── CARD ── */
    .rpt-card {
        background   : var(--surface);
        border-radius: var(--radius);
        border       : 1px solid var(--border);
        box-shadow   : var(--shadow-md);
        overflow     : hidden;
    }

    /* ── CARD HEADER ── */
    .rpt-header {
        padding         : 1.4rem 1.75rem;
        border-bottom   : 1px solid var(--border);
        background      : linear-gradient(135deg, #6366f1 0%, #818cf8 100%);
        display         : flex;
        align-items     : center;
        gap             : .75rem;
    }
    .rpt-header-icon {
        width           : 40px;
        height          : 40px;
        background      : rgba(255,255,255,.2);
        border-radius   : 10px;
        display         : flex;
        align-items     : center;
        justify-content : center;
        color           : #fff;
        font-size       : 1.2rem;
    }
    .rpt-header h5 {
        margin     : 0;
        color      : #fff;
        font-weight: 700;
        font-size  : 1rem;
        letter-spacing: .01em;
    }
    .rpt-header small {
        color      : rgba(255,255,255,.75);
        font-size  : .78rem;
    }

    /* ── TOOLBAR ── */
    .rpt-toolbar {
        padding      : 1.1rem 1.75rem;
        border-bottom: 1px solid var(--border);
        background   : var(--surface-2);
        display      : flex;
        flex-wrap    : wrap;
        gap          : .75rem;
        align-items  : flex-end;
        justify-content: space-between;
    }

    /* ── FILTER ROW ── */
    .rpt-filter {
        padding      : 1.1rem 1.75rem;
        border-bottom: 1px solid var(--border);
        display      : flex;
        flex-wrap    : wrap;
        gap          : .75rem;
        align-items  : flex-end;
        background   : var(--surface);
    }
    .rpt-filter .form-label {
        font-size  : .78rem;
        font-weight: 600;
        color      : var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: .35rem;
    }
    .rpt-filter .form-control {
        border       : 1.5px solid var(--border);
        border-radius: 9px;
        font-size    : .875rem;
        color        : var(--text-primary);
        padding      : .48rem .85rem;
        transition   : border-color .2s, box-shadow .2s;
        background   : var(--surface);
    }
    .rpt-filter .form-control:focus {
        border-color: var(--brand);
        box-shadow  : 0 0 0 3px var(--brand-glow);
        outline     : none;
    }

    /* ── SHOW ENTRIES ── */
    .show-label { font-size: .875rem; font-weight: 500; color: var(--text-muted); }
    #sort {
        border       : 1.5px solid var(--border);
        border-radius: 9px;
        font-size    : .875rem;
        padding      : .42rem .75rem;
        color        : var(--text-primary);
        font-family  : inherit;
        transition   : border-color .2s;
        cursor       : pointer;
    }
    #sort:focus { border-color: var(--brand); outline: none; box-shadow: 0 0 0 3px var(--brand-glow); }

    /* ── SEARCH ── */
    .rpt-search { position: relative; }
    .rpt-search input {
        border       : 1.5px solid var(--border);
        border-radius: 10px;
        font-size    : .875rem;
        padding      : .48rem 2.8rem .48rem .9rem;
        width        : 260px;
        color        : var(--text-primary);
        font-family  : inherit;
        transition   : border-color .2s, box-shadow .2s;
    }
    .rpt-search input:focus { border-color: var(--brand); box-shadow: 0 0 0 3px var(--brand-glow); outline: none; }
    .rpt-search input::placeholder { color: #b0b0c0; }
    .rpt-search .search-icon {
        position   : absolute;
        right      : .85rem;
        top        : 50%;
        transform  : translateY(-50%);
        color      : var(--text-muted);
        cursor     : pointer;
        transition : color .2s;
        font-size  : 1rem;
    }
    .rpt-search .search-icon:hover { color: var(--brand); }

    /* ── BUTTONS ── */
    .btn-brand {
        background   : var(--brand);
        color        : #fff;
        border       : none;
        border-radius: 9px;
        font-size    : .84rem;
        font-weight  : 600;
        padding      : .48rem 1.1rem;
        cursor       : pointer;
        display      : inline-flex;
        align-items  : center;
        gap          : .35rem;
        transition   : background .2s, box-shadow .2s, transform .1s;
        font-family  : inherit;
    }
    .btn-brand:hover { background: #4f52e8; box-shadow: 0 4px 12px rgba(99,102,241,.35); }
    .btn-brand:active { transform: scale(.97); }

    .btn-ghost {
        background   : transparent;
        color        : var(--text-muted);
        border       : 1.5px solid var(--border);
        border-radius: 9px;
        font-size    : .84rem;
        font-weight  : 600;
        padding      : .48rem 1rem;
        cursor       : pointer;
        display      : inline-flex;
        align-items  : center;
        gap          : .35rem;
        transition   : border-color .2s, color .2s, background .2s;
        font-family  : inherit;
    }
    .btn-ghost:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-glow); }

    /* ── TABLE ── */
    .rpt-table-wrap { overflow-x: auto; }
    #report-table {
        width         : 100%;
        border-collapse: separate;
        border-spacing : 0;
        font-size     : .86rem;
    }
    #report-table thead th {
        background      : var(--surface-2);
        color           : var(--text-muted);
        font-weight     : 700;
        font-size       : .75rem;
        text-transform  : uppercase;
        letter-spacing  : .06em;
        padding         : .9rem 1rem;
        border-bottom   : 2px solid var(--border);
        white-space     : nowrap;
        border-top      : none;
    }
    #report-table tbody tr {
        transition: background .15s;
        border-bottom: 1px solid var(--border);
    }
    #report-table tbody tr:last-child { border-bottom: none; }
    #report-table tbody tr:hover { background: #f5f5ff; }
    #report-table tbody td {
        padding     : .85rem 1rem;
        color       : var(--text-primary);
        vertical-align: middle;
        white-space : nowrap;
    }

    /* Nomor kolom */
    #report-table tbody td:first-child {
        font-family: 'JetBrains Mono', monospace;
        font-size  : .78rem;
        color      : var(--text-muted);
        font-weight: 500;
    }

    /* Tanggal */
    .cell-date {
        font-family: 'JetBrains Mono', monospace;
        font-size  : .8rem;
        color      : var(--text-primary);
    }

    /* ── BADGES ── */
    .rpt-badge {
        display      : inline-flex;
        align-items  : center;
        gap          : .3rem;
        padding      : .28rem .75rem;
        border-radius: 99px;
        font-size    : .75rem;
        font-weight  : 700;
        letter-spacing: .02em;
    }
    .rpt-badge-invoice   { background: #eff6ff; color: #2563eb; }
    .rpt-badge-reminder  { background: #fffbeb; color: #d97706; }
    .rpt-badge-broadcast { background: #f5f3ff; color: #7c3aed; }
    .rpt-badge-isolir    { background: #fff1f2; color: #be123c; }
    .rpt-badge-default   { background: #f3f4f6; color: #374151; }

    /* ── ID PELANGGAN ── */
    .cell-id {
        font-family: 'JetBrains Mono', monospace;
        font-size  : .8rem;
        color      : var(--brand);
        font-weight: 600;
    }

    /* ── WA ── */
    .cell-wa {
        font-family: 'JetBrains Mono', monospace;
        font-size  : .8rem;
        color      : var(--text-muted);
    }

    /* ── PESAN ── */
    .cell-message {
        max-width   : 280px;
        overflow    : hidden;
        text-overflow: ellipsis;
        white-space : nowrap;
        color       : var(--text-muted);
        font-size   : .84rem;
    }

    /* ── ACTION BUTTON ── */
    .btn-wa {
        display     : inline-flex;
        align-items : center;
        gap         : .35rem;
        background  : #dcfce7;
        color       : #15803d;
        border      : none;
        border-radius: 8px;
        padding     : .38rem .85rem;
        font-size   : .8rem;
        font-weight : 600;
        cursor      : pointer;
        text-decoration: none;
        transition  : background .2s, transform .1s, box-shadow .2s;
        font-family : inherit;
        white-space : nowrap;
    }
    .btn-wa:hover { background: #bbf7d0; color: #15803d; box-shadow: 0 3px 10px rgba(21,128,61,.2); }
    .btn-wa:active { transform: scale(.96); }

    /* ── PROCESSING OVERLAY ── */
    #report-table_processing {
        background  : rgba(255,255,255,.85) !important;
        border      : none !important;
        box-shadow  : none !important;
        color       : var(--brand) !important;
        font-weight : 600;
        font-family : inherit;
    }

    /* ── FOOTER ── */
    .rpt-footer {
        padding      : 1rem 1.75rem;
        border-top   : 1px solid var(--border);
        display      : flex;
        align-items  : center;
        justify-content: space-between;
        flex-wrap    : wrap;
        gap          : .75rem;
        background   : var(--surface-2);
    }
    .rpt-info {
        font-size  : .82rem;
        color      : var(--text-muted);
        font-weight: 500;
    }
    .rpt-info b { color: var(--text-primary); }

    /* ── PAGINATION ── */
    .rpt-pagination { display: flex; gap: .3rem; list-style: none; margin: 0; padding: 0; }
    .rpt-pagination li a,
    .rpt-pagination li span {
        display      : inline-flex;
        align-items  : center;
        justify-content: center;
        width        : 34px;
        height       : 34px;
        border-radius: 9px;
        font-size    : .82rem;
        font-weight  : 600;
        border       : 1.5px solid var(--border);
        color        : var(--text-primary);
        background   : var(--surface);
        text-decoration: none;
        transition   : all .18s;
        cursor       : pointer;
    }
    .rpt-pagination li a:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-glow); }
    .rpt-pagination li.active a { background: var(--brand); border-color: var(--brand); color: #fff; }
    .rpt-pagination li.disabled span,
    .rpt-pagination li.disabled a { opacity: .4; cursor: default; pointer-events: none; }

    /* ── EMPTY STATE ── */
    .rpt-empty {
        padding   : 4rem 2rem;
        text-align: center;
        color     : var(--text-muted);
    }
    .rpt-empty i { font-size: 2.5rem; opacity: .3; display: block; margin-bottom: .75rem; }
    .rpt-empty p { margin: 0; font-size: .9rem; }

    /* ── FADE IN ── */
    @keyframes fadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
    #report-table tbody tr { animation: fadeIn .2s ease both; }
</style>
@endpush

@section('content')
<div class="rpt-card">
    <div class="rpt-header">
        <div class="rpt-header-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                <line x1="1" y1="10" x2="23" y2="10"/>
            </svg>
        </div>
        <div>
            <h5>Log WA Blast</h5>
            <small>Riwayat transaksi pembayaran pelanggan</small>
        </div>
    </div>

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
            <input type="text" id="search-input" placeholder="Cari nama, nomor, pesan…">
            <i class="ti ti-search search-icon" id="search-btn"></i>
        </div>

    </div>

    <div class="rpt-filter">
        <div>
            <label class="form-label">Dari Tanggal</label>
            <input type="date" id="date-from" class="form-control" style="width:180px;"
                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
        </div>
        <div>
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" id="date-to" class="form-control" style="width:180px;"
                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
        </div>
        <div class="d-flex gap-2">
            <button class="btn-brand" id="btn-filter">
                <i class="ti ti-filter"></i> Filter
            </button>
            <button class="btn-ghost" id="btn-reset">
                <i class="ti ti-rotate"></i> Reset
            </button>
        </div>
    </div>

    <div class="rpt-table-wrap">
        <table id="report-table">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Tanggal</th>
                    <th>WA Pelanggan</th>
                    <th>Pesan</th>
                    <th style="width:120px;">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="rpt-footer">
        <p class="rpt-info">
            Menampilkan <b id="start-entry">0</b>–<b id="end-entry">0</b>
            dari <b id="total-entries">0</b> entri
        </p>
        <ul class="rpt-pagination" id="custom-pagination"></ul>
    </div>

</div>
@endsection

@push('modal')
<div id="modal-pesan" style="
    display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,.45); backdrop-filter:blur(4px);
    align-items:center; justify-content:center;">
    <div style="
        background:#fff; border-radius:16px; width:90%; max-width:560px;
        max-height:80vh; display:flex; flex-direction:column;
        box-shadow:0 20px 60px rgba(0,0,0,.2); overflow:hidden;">

        <div style="
            padding:1.1rem 1.5rem; border-bottom:1px solid #e8e8f0;
            display:flex; align-items:center; justify-content:space-between;
            background:linear-gradient(135deg,#6366f1,#818cf8);">
            <div style="display:flex;align-items:center;gap:.6rem;color:#fff;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <span style="font-weight:700;font-size:.95rem;">Isi Pesan</span>
            </div>
            <button id="modal-close" style="
                background:rgba(255,255,255,.2); border:none; border-radius:8px;
                color:#fff; width:30px; height:30px; cursor:pointer;
                display:flex; align-items:center; justify-content:center;
                font-size:1.1rem; transition:background .2s;">✕</button>
        </div>

        {{-- Modal Body --}}
        <div id="modal-body" style="
            padding:1.4rem 1.5rem; overflow-y:auto; flex:1;
            font-size:.875rem; line-height:1.75; color:#1e1e2e;
            white-space:pre-wrap; word-break:break-word;
            font-family:'Plus Jakarta Sans',sans-serif;">
        </div>

        <div style="
            padding:.85rem 1.5rem; border-top:1px solid #e8e8f0;
            display:flex; justify-content:flex-end; background:#f8f8fc;">
            <button id="modal-close-btn" style="
                background:#6366f1; color:#fff; border:none; border-radius:9px;
                padding:.45rem 1.2rem; font-size:.84rem; font-weight:600;
                cursor:pointer; font-family:inherit; transition:background .2s;">
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
            processing  : true,
            serverSide  : true,
            ajax: {
                url : BASE,
                data: function (d) {
                    d.date_from = $('#date-from').val();
                    d.date_to   = $('#date-to').val();
                }
            },
            order     : [[1, 'desc']],
            pageLength: 10,
            dom       : 'rt',

            columns: [
                {
                    data      : null,
                    orderable : false,
                    searchable: false,
                    render    : function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data  : 'date',
                    render: function (data) {
                        if (!data) return '-';
                        return `<span class="cell-date">${moment(data).format('DD/MM/YYYY HH:mm')}</span>`;
                    }
                },
                {
                    data  : 'to',
                    render: function (data) {
                        return data ? `<span class="cell-wa">${data}</span>` : '-';
                    }
                },
                {
                    data  : 'message',
                    render: function (data) {
                        if (!data) return '-';

                        const max     = 60;
                        const escaped = $('<div>').text(data).html();
                        const preview = escaped.length > max
                            ? escaped.substring(0, max) + '…'
                            : escaped;

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
                {
                    data      : null,
                    orderable : false,
                    searchable: false,
                    render    : function (data, type, row) {
                        const phone = row.to;
                        if (!phone) return '-';

                        let normalized = phone.replace(/\D/g, '');
                        if (normalized.startsWith('0')) {
                            normalized = '62' + normalized.slice(1);
                        }

                        const message = encodeURIComponent(row.message ?? '');

                        return `
                            <a href="https://wa.me/${normalized}?text=${message}"
                               target="_blank"
                               class="btn-wa">
                               Kirim Pemberitahuan
                            </a>`;
                    }
                }

            ],

            drawCallback: function (settings) {
                updatePaginationInfo(settings);
                updateCustomPagination();
            },

            language: {
                processing: '<i class="ti ti-loader" style="animation:spin 1s linear infinite;display:inline-block;"></i> Memuat data…',
                emptyTable: '<div class="rpt-empty"><i class="ti ti-inbox"></i><p>Tidak ada data ditemukan</p></div>',
                zeroRecords: '<div class="rpt-empty"><i class="ti ti-search-off"></i><p>Tidak ada data yang cocok</p></div>',
            }
        });

        $('#sort').val(10);
        $('#sort').change(function () {
            table.page.len(parseInt($(this).val())).draw();
        });

        $('#search-input').on('keyup', function (e) {
            if (e.which === 13) table.search(this.value).draw();
        });
        $('#search-btn').click(function () {
            table.search($('#search-input').val()).draw();
        });

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
            $('#date-from, #date-to').val('');
            $('#search-input').val('');
            table.search('').draw();
        });

        // PAGINATION DELEGATION
        $(document).on('click', '#custom-pagination a', function (e) {
            e.preventDefault();
            const info = table.page.info();
            const page = parseInt($(this).data('page'));
            if (!isNaN(page) && page >= 0 && page < info.pages) {
                table.page(page).draw('page');
            }
        });

    });

    /* ── CSS spin keyframe untuk processing ── */
    const style = document.createElement('style');
    style.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
    document.head.appendChild(style);

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

        const prev = info.page === 0 ? 'disabled' : '';
        pagination.append(`<li class="${prev}"><a data-page="${info.page - 1}" href="#">‹</a></li>`);

        const windowSize = 2;
        const startPage  = Math.max(0, info.page - windowSize);
        const endPage    = Math.min(info.pages - 1, info.page + windowSize);

        if (startPage > 0) {
            pagination.append(`<li><a data-page="0" href="#">1</a></li>`);
            if (startPage > 1) pagination.append(`<li class="disabled"><span>…</span></li>`);
        }

        for (let i = startPage; i <= endPage; i++) {
            const active = i === info.page ? 'active' : '';
            pagination.append(`<li class="${active}"><a data-page="${i}" href="#">${i + 1}</a></li>`);
        }

        if (endPage < info.pages - 1) {
            if (endPage < info.pages - 2) pagination.append(`<li class="disabled"><span>…</span></li>`);
            pagination.append(`<li><a data-page="${info.pages - 1}" href="#">${info.pages}</a></li>`);
        }

        const next = info.page === info.pages - 1 ? 'disabled' : '';
        pagination.append(`<li class="${next}"><a data-page="${info.page + 1}" href="#">›</a></li>`);
    }

    // MODAL PESAN
    const modal     = $('#modal-pesan');
    const modalBody = $('#modal-body');

    // Buka modal saat klik pesan
    $(document).on('click', '.cell-message-click', function () {
        const raw = decodeURIComponent($(this).data('message'));
        modalBody.text(raw); // .text() agar aman dari XSS
        modal.css('display', 'flex');
    });

    // Tutup modal
    $(document).on('click', '#modal-close, #modal-close-btn', function () {
        modal.hide();
        modalBody.text('');
    });

    // Tutup saat klik backdrop
    $(document).on('click', '#modal-pesan', function (e) {
        if ($(e.target).is('#modal-pesan')) {
            modal.hide();
            modalBody.text('');
        }
    });

    // Tutup dengan tombol ESC
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            modal.hide();
            modalBody.text('');
        }
    });

</script>
@endpush
@extends('layouts.app')

@section('title')
    Data User
@endsection

@section('content')
<div class="org-container">
    @include('components.alert.success')
    <div class="org-card">
        {{-- HEADER --}}
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                    </svg>
                </div>
                <div>
                    <h5 class="org-title">Daftar Pengguna</h5>
                    <div class="org-subtitle">Manajemen daftar seluruh user dan operator </div>
                </div>
            </div>
            
            @can('tambah user')
                <a href="{{ route('user.create') }}" class="btn-add">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah User
                </a>
            @endcan
        </div>

        {{-- TOOLBAR --}}
        <div class="org-toolbar">
            <div style="font-size:.85rem; font-weight:600; color:var(--text-muted); display:flex; align-items:center; gap:.5rem;">
                Tampilkan
                <select id="sort" class="org-input" style="padding: .35rem .6rem;">
                    @foreach([10,25,50,100] as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
                data
            </div>

            @if (auth()->user()->hasPermissionTo('filter organization'))
                <div style="display:flex; align-items:center; gap:.5rem; font-size:.85rem; font-weight:600; color:var(--text-muted);">
                    Organisasi
                    <select id="filter-organization" class="org-input" style="padding: .35rem .6rem;">
                        <option value="">Semua</option>
                        @foreach ($organizations as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="search-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="search-input" class="org-input" placeholder="Cari nama atau email…">
            </div>
        </div>

        <div class="table-responsive">
            <table id="users-table" class="org-table">
                <thead>
                    <tr>
                        <th style="width:50px; text-align:center;">No</th>
                        <th>User Info</th>
                        <th>Telp</th>
                        <th>Level</th>
                        <th style="text-align:center;">Penempatan</th>
                        <th style="text-align:center;">Akses Hal.</th>
                        <th style="text-align:center;">Akses Router</th>
                        <th style="text-align:center;">Akses Patch Core</th>
                        <th style="text-align:center;">OLT</th>
                        <th style="text-align:center;">Mic Radius</th>
                                                        @if (Auth::user()->organization->type === 'internal')
                                                            <th style="text-align:center;">Organisasi/Mitra</th>
                                                        @endif
                        <th>Created At</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>

        {{-- FOOTER --}}
        <div class="org-footer">
            <div class="org-info" id="table-info">
                Menampilkan <span id="start-entry">0</span>
                sampai <span id="end-entry">0</span> dari
                <span id="total-entries">0</span> data
            </div>
            <ul class="pagination" id="custom-pagination"></ul>
        </div>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalTitle">Detail Akses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailModalBody" style="display:grid; grid-template-columns: repeat(3, 1fr); gap: .75rem; align-items: start;">
                <!-- Content goes here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" style="border-radius: 8px; font-weight: 600;" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    const BASE = "{{ route('user.index') }}";
    let table;

    // Fungsi Render Untuk Tombol Detail
    function renderDetailButton(data, type, row, meta, title) {
        if (!data || data === '-' || data === '') {
            return `<span style="color:#9ca3af; font-size:.8rem; font-weight:600;">-</span>`;
        }
        
        let safeData = encodeURIComponent(data);
        return `
            <button class="btn-look" onclick="showDetailModal('${title}', '${safeData}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                </svg>
                Lihat
            </button>
        `;
    }

    $(function () {
        table = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: BASE,
            order: [[{{ Auth::user()->organization->type === 'internal' ? 11 : 10 }}, 'desc']], // Created At Sorting Default
            pageLength: 10,
            dom: 'rt', 
            language: {
                emptyTable: "Belum ada data user yang ditambahkan.",
                zeroRecords: "Data user tidak ditemukan."
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { 
                    data: 'name', 
                    render: function(data, type, row) {
                        return `
                        <div style="display:flex; flex-direction:column;">
                            <span style="font-weight:700; color:var(--text-primary);">${data}</span>
                            <span style="font-size:.75rem; color:var(--text-muted);">${row.email} | @${row.username}</span>
                        </div>
                        `;
                    }
                },
                { data: 'telp', defaultContent: '-' },
                { 
                    data: 'role', 
                    render: function(data) {
                        return `<span style="font-weight:700; color:var(--brand);">${data}</span>`;
                    } 
                },
                { data: 'regencie', orderable: false, className: 'text-center', render: (data, t, r, m) => renderDetailButton(data, t, r, m, 'Penempatan Wilayah') },
                { data: 'pages', orderable: false, className: 'text-center', render: (data, t, r, m) => renderDetailButton(data, t, r, m, 'Akses Halaman') },
                { data: 'router_access', orderable: false, className: 'text-center', render: (data, t, r, m) => renderDetailButton(data, t, r, m, 'Akses Router') },
                { data: 'patch_core_access', orderable: false, className: 'text-center', render: (data, t, r, m) => renderDetailButton(data, t, r, m, 'Akses Patch Core') },
                { data: 'olt', orderable: false, className: 'text-center', render: (data, t, r, m) => renderDetailButton(data, t, r, m, 'Akses OLT') },
                { data: 'mix_radius', orderable: false, className: 'text-center', render: (data, t, r, m) => renderDetailButton(data, t, r, m, 'Akses Mikrotik / Radius') },
                @if (Auth::user()->organization->type === 'internal')
                { data: 'organization_name', orderable: false, className: 'text-center' },
                @endif
                { data: 'created_at', render: data => moment(data).format('DD/MM/YYYY - HH:mm') },
                { data: 'action', orderable: false, searchable: false, className: 'text-end' },
            ],
            drawCallback: function(settings) {
                updatePaginationInfo(settings);
                updateCustomPagination();
            }
        });

        $("#sort").change(function() {
            table.page.len($(this).val()).draw();
        });

        $("#search-input").on('keyup', function() {
            table.search(this.value).draw();
        });

        $("#filter-organization").change(function() {
            table.ajax.url(BASE + '?organization_id=' + this.value).load();
        });
    });

    // Menampilkan Modal Detail Akses
    function showDetailModal(title, encodedData) {
        const decodedData = decodeURIComponent(encodedData);
        $('#detailModalTitle').text(title);
        
        // Membersihkan class badge bg-primary default datatable dari server jika ada agar lebih estetik di modal
        let prettyData = decodedData.replace(/<br>/g, '');
        // Set style teks ke text-wrap normal agar teks panjang tidak terpotong (misal nama OLT / router panjang)
        prettyData = prettyData.replace(/class="badge bg-primary text-white mb-2"/g, 'class="badge" style="background:#eef2ff; color:var(--brand); font-size:.8rem; padding:.55rem .75rem; width:100%; text-align:left; border:1px solid var(--brand-glow); white-space: normal; line-height: 1.4;"');

        $('#detailModalBody').html(prettyData);
        $('#detailModal').modal('show');
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

        pagination.append(`
            <li class="page-item ${info.page === 0 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${info.page - 1}" aria-label="Previous">&laquo;</a>
            </li>
        `);

        let startPage = Math.max(0, info.page - 2);
        let endPage = Math.min(info.pages - 1, info.page + 2);

        if (startPage > 0) {
            pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
            if (startPage > 1) pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        }

        for (let i = startPage; i <= endPage; i++) {
            pagination.append(`
                <li class="page-item ${i === info.page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i + 1}</a>
                </li>
            `);
        }

        if (endPage < info.pages - 1) {
            if (endPage < info.pages - 2) pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
            pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="${info.pages - 1}">${info.pages}</a></li>`);
        }

        pagination.append(`
            <li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${info.page + 1}" aria-label="Next">&raquo;</a>
            </li>
        `);

        pagination.find('a').on('click', function(e) {
            e.preventDefault();
            const page = parseInt($(this).data('page'));
            if (!isNaN(page) && page >= 0 && page < info.pages) table.page(page).draw('page');
        });
    }

    const Toast = Swal.mixin({
        toast: true, position: "top-end", showConfirmButton: false, timer: 3000, timerProgressBar: true,
        didOpen: (toast) => { toast.onmouseenter = Swal.stopTimer; toast.onmouseleave = Swal.resumeTimer; }
    });

    function deleteUsers(id) {
        Swal.fire({
            title: "Hapus Pengguna?",
            text: "Pengguna ini beserta hak aksesnya akan terhapus mandiri.",
            icon: "warning", showCancelButton: true,
            confirmButtonColor: "#ef4444", cancelButtonColor: "#c0c0d0",
            confirmButtonText: "Ya, Hapus!", cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: BASE + '/' + id + '/destroy',
                    method: "DELETE", dataType: "json",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        Toast.fire({ icon: response.status, title: response.message });
                        setTimeout(() => { table.ajax.reload(); }, 1500);
                    },
                    error: function(err) {
                        Swal.fire("Error","Terjadi kesalahan pada server.","error");
                    }
                })
            }
        });
    }
</script>
@endpush
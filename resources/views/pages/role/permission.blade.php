@extends('layouts.app')

@section('title')
    Hak Akses Level: {{ $role->name }}
@endsection

@push('css')
<style>
    .perm-wrap { padding-bottom: 3rem; animation: slideUp 0.35s ease both; }
    .perm-back {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--text-muted); font-size: 0.84rem; font-weight: 600;
        text-decoration: none; margin-bottom: 1.5rem; transition: color 0.2s;
    }
    .perm-back:hover { color: var(--brand); }
    .perm-card {
        background: var(--surface); border-radius: var(--radius);
        border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden;
    }
    .perm-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border);
        background: var(--surface-2); flex-wrap: wrap; gap: 1rem;
    }
    .perm-title-wrap { display: flex; align-items: center; gap: 0.85rem; }
    .perm-icon {
        width: 42px; height: 42px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        background: #fffbeb; color: #d97706; flex-shrink: 0;
    }
    .perm-title { margin: 0; font-size: 1.1rem; font-weight: 700; color: var(--text-primary); }
    .perm-subtitle { color: var(--text-muted); font-size: 0.8rem; }
    .perm-header-action { display: flex; align-items: center; gap: 0.75rem; }
    .btn-check-all {
        background: #eff6ff; color: var(--brand);
        border: 1.5px solid var(--brand-glow); border-radius: 8px;
        padding: 0.45rem 0.95rem; font-size: 0.82rem; font-weight: 700;
        cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;
        transition: all 0.2s;
    }
    .btn-check-all:hover { background: #dbeafe; }
    .perm-body { padding: 0; }

    /* ── SEARCH ── */
    .perm-search-wrap {
        padding: 0.85rem 1.5rem;
        border-bottom: 1px solid var(--border);
        background: #fafbfc;
    }
    .perm-search {
        width: 100%;
        padding: 0.6rem 1rem 0.6rem 2.5rem;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 0.85rem;
        font-family: inherit;
        font-weight: 500;
        color: var(--text-primary);
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E") 0.8rem center no-repeat;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .perm-search:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px var(--brand-glow);
    }
    .perm-search-empty {
        display: none;
        text-align: center;
        padding: 2.5rem 1.5rem;
        color: #94a3b8;
        font-size: 0.88rem;
        font-weight: 600;
    }
    .perm-search-empty.visible {
        display: block;
    }
    .perm-collapse.hidden {
        display: none;
    }
    .akses-item.hidden {
        display: none;
    }

    /* ── COLLAPSE GROUP ── */
    .perm-collapse {
        border-bottom: 1px solid var(--border);
    }
    .perm-collapse:last-child {
        border-bottom: none;
    }
    .perm-collapse-trigger {
        display: flex; align-items: center; justify-content: space-between;
        width: 100%; padding: 0.85rem 1.5rem;
        background: transparent; border: none;
        cursor: pointer; font-family: inherit;
        transition: background 0.2s;
        gap: 1rem;
    }
    .perm-collapse-trigger:hover {
        background: #f8fafc;
    }
    .perm-collapse-trigger-left {
        display: flex; align-items: center; gap: 0.75rem; min-width: 0;
    }
    .perm-collapse-dot {
        width: 8px; height: 8px; border-radius: 50%;
        flex-shrink: 0;
    }
    .perm-collapse-label {
        font-size: 0.82rem; font-weight: 700; color: var(--text-primary);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .perm-collapse-count {
        font-size: 0.72rem; font-weight: 700;
        background: #f1f5f9; color: #64748b;
        padding: 2px 10px; border-radius: 20px;
        flex-shrink: 0;
    }
    .perm-collapse-chevron {
        width: 18px; height: 18px; flex-shrink: 0;
        color: #94a3b8; transition: transform 0.3s ease;
    }
    .perm-collapse-trigger[aria-expanded="true"] .perm-collapse-chevron {
        transform: rotate(180deg);
    }
    .perm-collapse-body {
        overflow: hidden; transition: max-height 0.35s ease, opacity 0.25s ease;
        max-height: 0; opacity: 0;
    }
    .perm-collapse-body.open {
        max-height: 2000px; opacity: 1;
    }
    .perm-collapse-inner {
        padding: 0 1.5rem 1.25rem 1.5rem;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 0.5rem;
    }

    /* ── CHECKBOX ── */
    .akses-item { position: relative; }
    .akses-item input[type="checkbox"] {
        position: absolute; opacity: 0; cursor: pointer; height: 0; width: 0;
    }
    .akses-label {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.55rem 0.9rem;
        border: 1.5px solid var(--border); border-radius: 8px;
        cursor: pointer; font-size: 0.82rem; font-weight: 600;
        color: var(--text-primary); transition: all 0.2s ease;
        background: var(--surface); user-select: none;
    }
    .akses-label:hover {
        border-color: var(--brand); background: #eff6ff;
    }
    .akses-item input:checked + .akses-label {
        border-color: var(--brand); background: #eff6ff;
        color: var(--brand-dark); box-shadow: 0 2px 8px var(--brand-glow);
    }
    .akses-box {
        width: 18px; height: 18px; border-radius: 5px;
        border: 2px solid #cbd5e1;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: all 0.2s; background: #fff;
    }
    .akses-item input:checked + .akses-label .akses-box {
        background: var(--brand); border-color: var(--brand);
    }
    .akses-item input:checked + .akses-label .akses-box::after {
        content: ""; width: 9px; height: 5px;
        border-left: 2px solid #fff; border-bottom: 2px solid #fff;
        transform: rotate(-45deg) translateY(-1px); display: block;
    }

    /* ── FOOTER ── */
    .perm-footer {
        padding: 1.25rem 1.5rem; background: var(--surface-2);
        border-top: 1px solid var(--border); text-align: right;
    }
    .btn-submit {
        background: var(--brand); color: #fff; border: none;
        border-radius: 10px; padding: 0.65rem 2rem;
        font-size: 0.9rem; font-weight: 700; cursor: pointer;
        font-family: inherit; display: inline-flex; align-items: center;
        gap: 0.5rem; transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
    }
    .btn-submit:hover {
        background: var(--brand-dark);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
    }
    .btn-submit:active { transform: scale(0.97); }
</style>
@endpush

@section('content')
<div class="perm-wrap">

    <a href="{{ route('role.index') }}" class="perm-back">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Kembali ke Daftar Level / Role
    </a>

    @include('components.alert.success')

    <div class="perm-card">
        <form action="{{ route('role.savePermission', ['id' => $role->id]) }}" method="POST" id="perm-form">
            @csrf
            @method("PUT")
            
            <div class="perm-header">
                <div class="perm-title-wrap">
                    <div class="perm-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <div>
                        <h5 class="perm-title">Pengaturan Hak Akses (Level: {{ $role->name }})</h5>
                        <div class="perm-subtitle">Centang fitur yang boleh diakses oleh level ini</div>
                    </div>
                </div>

                <div class="perm-header-action">
                    <button type="button" id="btn-toggle-all" class="btn-check-all">
                        <svg id="icon-toggle" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 11 12 14 22 4"></polyline>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                        <span id="text-toggle">Pilih Semua</span>
                    </button>
                </div>
            </div>

            <div class="perm-body" id="perm-body">
                <div class="perm-search-wrap">
                    <input type="text" class="perm-search" id="perm-search" placeholder="Cari permission..." autocomplete="off">
                </div>
                @php
                    $colors = ['#2563eb','#7c3aed','#059669','#d97706','#dc2626','#0891b2','#9333ea','#16a34a'];
                    $gi = 0;
                @endphp
                @foreach ($groupedPermissions as $group => $items)
                    @php
                        $groupId = Str::slug($group);
                        $color = $colors[$gi % count($colors)];
                        $isFirst = $loop->first;
                        $gi++;
                    @endphp
                    <div class="perm-collapse">
                        <button class="perm-collapse-trigger" 
                                type="button"
                                aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                                data-target="collapse-{{ $groupId }}">
                            <span class="perm-collapse-trigger-left">
                                <span class="perm-collapse-dot" style="background: {{ $color }};"></span>
                                <span class="perm-collapse-label">{{ $group }}</span>
                                <span class="perm-collapse-count">{{ count($items) }}</span>
                            </span>
                            <svg class="perm-collapse-chevron" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </button>
                        <div class="perm-collapse-body {{ $isFirst ? 'open' : '' }}" id="collapse-{{ $groupId }}">
                            <div class="perm-collapse-inner">
                                @foreach ($items as $item)
                                    <div class="akses-item">
                                        <input name="permissions[]" 
                                               id="perm-{{ Str::slug($item) }}" 
                                               value="{{ $item }}" 
                                               type="checkbox" 
                                               class="cb-permission" 
                                               {{ $role->hasPermissionTo($item) ? 'checked' : '' }}>
                                        <label for="perm-{{ Str::slug($item) }}" class="akses-label">
                                            <span class="akses-box"></span>
                                            <span style="text-transform: capitalize;">{{ $item }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="perm-search-empty" id="perm-search-empty">
                    Tidak ada permission yang cocok dengan pencarian Anda.
                </div>
            </div>

            <div class="perm-footer">
                <button type="submit" class="btn-submit" id="btn-submit">
                    <svg id="btn-check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span id="btn-text">Simpan Hak Akses</span>
                    <svg id="btn-spinner" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                         style="display:none; animation:spin 1s linear infinite;">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                </button>
            </div>
            
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnToggleAll = document.getElementById('btn-toggle-all');
        const textToggle   = document.getElementById('text-toggle');
        const iconToggle   = document.getElementById('icon-toggle');
        const checkboxes   = document.querySelectorAll('.cb-permission');

        const checkAllState = () => {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            if (allChecked) {
                textToggle.textContent = 'Batal Semua';
                iconToggle.innerHTML = '<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>';
                btnToggleAll.dataset.state = 'all';
            } else {
                textToggle.textContent = 'Pilih Semua';
                iconToggle.innerHTML = '<polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>';
                btnToggleAll.dataset.state = 'none';
            }
        };

        checkAllState();

        btnToggleAll.addEventListener('click', function () {
            const newState = this.dataset.state === 'none';
            checkboxes.forEach(cb => cb.checked = newState);
            checkAllState();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', checkAllState);
        });

        // ── COLLAPSE TOGGLE ──
        document.querySelectorAll('.perm-collapse-trigger').forEach(function (trigger) {
            trigger.addEventListener('click', function () {
                const targetId = this.dataset.target;
                const body = document.getElementById(targetId);
                if (!body) return;

                const isOpen = body.classList.contains('open');
                if (isOpen) {
                    body.classList.remove('open');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    body.classList.add('open');
                    this.setAttribute('aria-expanded', 'true');
                }
            });
        });

        // ── SEARCH ──
        const searchInput = document.getElementById('perm-search');
        const emptyMsg = document.getElementById('perm-search-empty');

        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            let anyVisible = false;

            document.querySelectorAll('.perm-collapse').forEach(function (group) {
                let groupHasVisible = false;
                const items = group.querySelectorAll('.akses-item');

                items.forEach(function (item) {
                    const label = item.querySelector('.akses-label span:last-child');
                    const text = label ? label.textContent.toLowerCase() : '';
                    const match = !q || text.includes(q);
                    item.classList.toggle('hidden', !match);
                    if (match) groupHasVisible = true;
                });

                group.classList.toggle('hidden', !groupHasVisible);
                if (groupHasVisible) anyVisible = true;

                // Update count badge
                const countBadge = group.querySelector('.perm-collapse-count');
                const visibleItems = group.querySelectorAll('.akses-item:not(.hidden)').length;
                const totalItems = items.length;
                if (countBadge) {
                    countBadge.textContent = q ? visibleItems + '/' + totalItems : totalItems;
                }
            });

            emptyMsg.classList.toggle('visible', !anyVisible && q !== '');
        });

        // ── SUBMIT LOADING ──
        document.getElementById('perm-form').addEventListener('submit', function () {
            const btn       = document.getElementById('btn-submit');
            const text      = document.getElementById('btn-text');
            const spinner   = document.getElementById('btn-spinner');
            const checkIcon = document.getElementById('btn-check-icon');

            btn.disabled            = true;
            text.textContent        = 'Menyimpan…';
            spinner.style.display   = 'inline-block';
            checkIcon.style.display = 'none';
        });
    });

    if (!document.querySelector('#spin-style')) {
        const s = document.createElement('style');
        s.id = 'spin-style';
        s.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
        document.head.appendChild(s);
    }
</script>
@endpush
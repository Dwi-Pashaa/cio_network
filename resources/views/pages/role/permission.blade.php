@extends('layouts.app')

@section('title')
    Hak Akses Level: {{ $role->name }}
@endsection


@section('content')
<div class="perm-wrap">

    {{-- BACK --}}
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

            <div class="perm-body">
                <div class="akses-grid">
                    @if (Auth::user()->organization->type === 'internal')
                        @foreach ($permissions as $item)
                            <div class="akses-item">
                                <input name="permissions[]" id="perm-{{ $loop->index }}" value="{{ $item->name }}" type="checkbox" 
                                       class="cb-permission" {{ $role->hasPermissionTo($item->name) ? 'checked' : '' }}>
                                <label for="perm-{{ $loop->index }}" class="akses-label">
                                    <span class="akses-box"></span>
                                    <span style="text-transform: capitalize;">{{ $item->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    @else
                        @foreach ($organizationPermissions as $item)
                            <div class="akses-item">
                                <input name="permissions[]" id="perm-{{ $loop->index }}" value="{{ $item }}" type="checkbox" 
                                       class="cb-permission" {{ $role->hasPermissionTo($item) ? 'checked' : '' }}>
                                <label for="perm-{{ $loop->index }}" class="akses-label">
                                    <span class="akses-box"></span>
                                    <span style="text-transform: capitalize;">{{ $item }}</span>
                                </label>
                            </div>
                        @endforeach 
                    @endif
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
        
        // Fungsi untuk mengecek jika smeua sudah terceklis
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

        checkAllState(); // inisialisasi awal

        // Event listener untuk tombol Pilih Semua/Batal Semua
        btnToggleAll.addEventListener('click', function () {
            const newState = this.dataset.state === 'none';
            checkboxes.forEach(cb => cb.checked = newState);
            checkAllState();
        });

        // Event listener saat checkbox satuan ditekan agar update UI status tombol "Pilih Semua"
        checkboxes.forEach(cb => {
            cb.addEventListener('change', checkAllState);
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

    // Menambah script keyframes dinamis untuk animasi spinner jika belum ada di root
    if (!document.querySelector('#spin-style')) {
        const s = document.createElement('style');
        s.id = 'spin-style';
        s.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
        document.head.appendChild(s);
    }
</script>
@endpush
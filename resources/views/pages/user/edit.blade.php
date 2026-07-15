@extends('layouts.app')

@section('title')
    Edit User
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/modern-layout.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* ── Form Section Card ── */
        .form-section {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .form-section-title {
            font-size: .88rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #4f46e5;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .form-section-title svg {
            flex-shrink: 0;
        }

        /* ── Labels ── */
        .modern-label {
            font-size: .82rem;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: .4rem;
            display: block;
        }

        /* ── Inputs ── */
        .modern-input {
            width: 100%;
            padding: .6rem 1rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: .9rem;
            color: #111827;
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
        }

        .modern-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .1);
        }

        .modern-input.is-invalid {
            border-color: #ef4444;
        }

        /* ── Buttons ── */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .55rem 1.25rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            background: #f9fafb;
            color: #6b7280;
            font-weight: 600;
            font-size: .88rem;
            text-decoration: none;
            transition: background .15s, border-color .15s;
        }

        .btn-back:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
            color: #374151;
        }

        .btn-form-submit {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .65rem 1.5rem;
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: .9rem;
            cursor: pointer;
            transition: transform .1s, box-shadow .15s;
        }

        .btn-form-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, .35);
        }

        .btn-form-submit:disabled {
            opacity: .7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-form-reset {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .55rem 1.25rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            color: #6b7280;
            font-weight: 600;
            font-size: .88rem;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-form-reset:hover {
            background: #f3f4f6;
        }

        /* ── Tom Select Override ── */
        .ts-wrapper .ts-control {
            border: 1.5px solid #e5e7eb !important;
            border-radius: 10px !important;
            padding: .5rem .75rem !important;
            min-height: 42px !important;
            font-size: .9rem !important;
        }

        .ts-wrapper.focus .ts-control {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .1) !important;
        }
    </style>
@endpush

@section('content')
    @php
        // Ambil selected Mic Radius dari pivot
        $selectedMicRadius = $user->mixRadius->pluck('id')->toArray();

        // Ambil selected OLT dari pivot
        $selectedOlts = $user->olts->pluck('id')->toArray();
        $selectedRegencie = $user->regencie->pluck('id')->toArray();
        $selectedPages = $user->pages->pluck('id')->toArray();
        $selectedRouters = $user->routerAccess->pluck('id')->toArray();
        $selectedPatchCores = $user->patchCoreAccess->pluck('id')->toArray();

        $currentRole = old('role', $user->roles->first()->name ?? '');
    @endphp

    <div class="org-card">
        <div class="org-header">
            <div class="org-title-wrap">
                <div class="org-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="org-title">Edit Data User</h3>
                    <p class="org-subtitle mb-0">Perbarui informasi, akses, atau password user</p>
                </div>
            </div>
            <div class="org-actions">
                <a href="{{ route('user.index') }}" class="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div style="padding: 1.5rem;">
            <form action="{{ route('user.update', $user->id) }}" method="POST" id="editForm">
                @csrf
                @method('PUT')

                {{-- ── Informasi Akun ── --}}
                <div class="form-section">
                    <div class="form-section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        Informasi Akun
                    </div>

                    <div class="mb-3">
                        <label for="username" class="modern-label">Username</label>
                        <input value="{{ old('username', $user->username) }}" type="text" name="username" id="username"
                            class="modern-input @error('username') is-invalid @enderror" placeholder="Masukkan username">
                        @error('username')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label for="name" class="modern-label">Nama Lengkap</label>
                            <input value="{{ old('name', $user->name) }}" type="text" name="name" id="name"
                                class="modern-input @error('name') is-invalid @enderror"
                                placeholder="Masukkan nama lengkap">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label for="email" class="modern-label">Email</label>
                            <input value="{{ old('email', $user->email) }}" type="email" name="email" id="email"
                                class="modern-input @error('email') is-invalid @enderror" placeholder="contoh@email.com">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="telp" class="modern-label">No Telephone</label>
                        <input value="{{ old('telp', $user->telp) }}" type="text" name="telp" id="telp"
                            class="modern-input @error('telp') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                        @error('telp')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- ── Level & Akses ── --}}
                <div class="form-section">
                    <div class="form-section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                        Level & Akses
                    </div>

                    <div class="mb-3">
                        <label for="role" class="modern-label">Level Akses</label>
                        <select name="role" id="role" class="modern-input @error('role') is-invalid @enderror">
                            <option value="">-- Pilih Level --</option>
                            @foreach ($role as $item)
                                <option value="{{ $item->name }}" {{ $currentRole == $item->name ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3" id="mic_radius_wrap"
                        style="{{ $currentRole == 'Operator Mic Radius' ? '' : 'display:none' }}">
                        <label for="select-mic-radius" class="modern-label">Mic Radius</label>
                        <select name="mic_radius_id[]" id="select-mic-radius"
                            class="form-select @error('mic_radius_id') is-invalid @enderror" multiple>
                            @foreach ($micRadius as $mc)
                                <option value="{{ $mc->id }}"
                                    {{ in_array($mc->id, old('mic_radius_id', $selectedMicRadius)) ? 'selected' : '' }}>
                                    {{ $mc->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('mic_radius_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3" id="olt_wrap"
                        style="{{ $currentRole == 'Operator OLT' ? '' : 'display:none' }}">
                        <label for="select-olt" class="modern-label">OLT</label>
                        <select name="olt_id[]" id="select-olt" class="form-select @error('olt_id') is-invalid @enderror"
                            multiple>
                            @foreach ($olts as $olt)
                                <option value="{{ $olt->id }}"
                                    {{ in_array($olt->id, old('olt_id', $selectedOlts)) ? 'selected' : '' }}>
                                    {{ $olt->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('olt_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="select-regencie" class="modern-label">Penempatan Kabupaten/Kota</label>
                        <select name="regencie_id[]" id="select-regencie"
                            class="form-select @error('regencie_id') is-invalid @enderror" multiple>
                            @foreach ($regencie as $regency)
                                <option value="{{ $regency->id }}"
                                    {{ in_array($regency->id, old('regencie_id', $selectedRegencie)) ? 'selected' : '' }}>
                                    {{ $regency->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('regencie_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="select-pages" class="modern-label">Akses Data Halaman</label>
                        <select name="pages_id[]" id="select-pages"
                            class="form-select @error('pages_id') is-invalid @enderror" multiple>
                            <option value="keseluruhan">Keseluruhan Aksess Halaman</option>
                            @foreach ($pages as $page)
                                <option value="{{ $page->id }}"
                                    {{ in_array($page->id, old('pages_id', $selectedPages)) ? 'selected' : '' }}>
                                    {{ $page->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('pages_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="select-router" class="modern-label">Akses Data Router</label>
                        <select name="router_id[]" id="select-router"
                            class="form-select @error('router_id') is-invalid @enderror" multiple>
                            @foreach ($routers as $router)
                                <option value="{{ $router->id }}"
                                    {{ in_array($router->id, old('router_id', $selectedRouters)) ? 'selected' : '' }}>
                                    {{ $router->name }} ({{ $router->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('router_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="select-patch-core" class="modern-label">Akses Data Patch Core</label>
                        <select name="patch_core_id[]" id="select-patch-core"
                            class="form-select @error('patch_core_id') is-invalid @enderror" multiple>
                            @foreach ($patchCores as $patchCore)
                                <option value="{{ $patchCore->id }}"
                                    {{ in_array($patchCore->id, old('patch_core_id', $selectedPatchCores)) ? 'selected' : '' }}>
                                    {{ $patchCore->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patch_core_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- ── Password ── --}}
                <div class="form-section">
                    <div class="form-section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4" />
                        </svg>
                        Keamanan
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label for="password" class="modern-label">Password Baru (Opsional)</label>
                            <input type="password" name="password" id="password"
                                class="modern-input @error('password') is-invalid @enderror"
                                placeholder="Kosongkan jika tidak ingin diubah">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label for="password_confirmation" class="modern-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="modern-input @error('password_confirmation') is-invalid @enderror"
                                placeholder="Ulangi password baru">
                            @error('password_confirmation')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ── Action Buttons ── --}}
                <div class="d-flex justify-content-end align-items-center mt-2">
                    <button type="submit" id="btn" class="btn-form-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        <span id="btn-text">Simpan Perubahan</span>
                        <span id="btn-loading" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tsConfig = {
                plugins: ['remove_button'],
                persist: false,
                maxItems: null,
                create: false
            };

            new TomSelect("#select-mic-radius", {
                ...tsConfig,
                placeholder: "Pilih Mic Radius"
            });
            new TomSelect("#select-olt", {
                ...tsConfig,
                placeholder: "Pilih OLT"
            });
            new TomSelect("#select-regencie", {
                ...tsConfig,
                placeholder: "Pilih Kabupaten/Kota"
            });
            new TomSelect("#select-pages", {
                ...tsConfig,
                placeholder: "Pilih Akses Halaman"
            });
            new TomSelect("#select-router", {
                ...tsConfig,
                placeholder: "Pilih Akses Router"
            });
            new TomSelect("#select-patch-core", {
                ...tsConfig,
                placeholder: "Pilih Patch Core"
            });

            // Role → show/hide conditional fields
            $("#role").on('change', function() {
                const role = $(this).val();
                if (role === "Operator OLT") {
                    $("#mic_radius_wrap").hide();
                    $("#olt_wrap").show();
                } else if (role === "Operator Mic Radius") {
                    $("#mic_radius_wrap").show();
                    $("#olt_wrap").hide();
                } else {
                    $("#mic_radius_wrap").hide();
                    $("#olt_wrap").hide();
                }
            });

            // Submit loading state
            document.getElementById('editForm').addEventListener('submit', function() {
                const btn = document.getElementById('btn');
                const text = document.getElementById('btn-text');
                const loading = document.getElementById('btn-loading');
                btn.disabled = true;
                text.textContent = 'Menyimpan...';
                loading.classList.remove('d-none');
            });
        });
    </script>
@endpush

@extends('layouts.app')

@section('title')
    Edit User
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
@endpush

@section('content')

@php
    // Ambil selected Mic Radius dari pivot
    $selectedMicRadius = $user->mixRadius->pluck('id')->toArray();

    // Ambil selected OLT dari pivot
    $selectedOlts = $user->olts->pluck('id')->toArray();

    $currentRole = old('role', $user->roles->first()->name ?? '');
@endphp

<div class="card">
    <div class="card-header">
        <a href="{{ route('user.index') }}" class="btn btn-primary">
            Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label class="mb-2">Username</label>
                <input value="{{ old('username', $user->username) }}" 
                       type="text" name="username" 
                       class="form-control @error('username') is-invalid @enderror">
                @error('username') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="mb-2">Nama Lengkap</label>
                        <input value="{{ old('name', $user->name) }}" 
                               type="text" name="name" 
                               class="form-control @error('name') is-invalid @enderror">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="mb-2">Email</label>
                        <input value="{{ old('email', $user->email) }}" 
                               type="email" name="email" 
                               class="form-control @error('email') is-invalid @enderror">
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="mb-2">No Telephone</label>
                <input value="{{ old('telp', $user->telp) }}" 
                       type="text" name="telp" 
                       class="form-control @error('telp') is-invalid @enderror">
                @error('telp') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group mb-3">
                <label class="mb-2">Level</label>
                <select name="role" id="role" 
                        class="form-control @error('role') is-invalid @enderror">
                    <option value="">Pilih</option>
                    @foreach ($role as $item)
                        <option value="{{ $item->name }}" 
                                {{ $currentRole == $item->name ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
                @error('role') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            {{-- Mic Radius --}}
            <div class="form-group mb-3" id="mic_radius_wrap"
                style="{{ $currentRole == 'Operator Mic Radius' ? '' : 'display:none' }}">
                
                <label class="mb-2">Mic Radius</label>

                <select name="mic_radius_id[]" id="select-mic-radius" 
                        class="form-select @error('mic_radius_id') is-invalid @enderror" 
                        multiple>

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

            {{-- OLT --}}
            <div class="form-group mb-3" id="olt_wrap"
                style="{{ $currentRole == 'Operator OLT' ? '' : 'display:none' }}">
                
                <label class="mb-2">OLT</label>

                <select name="olt_id[]" id="select-olt" 
                        class="form-control @error('olt_id') is-invalid @enderror" 
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

            {{-- Password --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="mb-2">Password Baru (Opsional)</label>
                        <input type="password" name="password" 
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" 
                               class="form-control @error('password_confirmation') is-invalid @enderror">
                        @error('password_confirmation') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="mt-3">
                <button type="submit" class="btn btn-primary float-end">Update</button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <script>
        // Mic Radius
        new TomSelect("#select-mic-radius", {
            plugins: ['remove_button'],
            placeholder: "Pilih Mic Radius",
            maxItems: null
        });

        // OLT
        new TomSelect("#select-olt", {
            plugins: ['remove_button'],
            placeholder: "Pilih OLT",
            maxItems: null
        });

        // Show/hide fields based on role
        $("#role").change(function() {
            var role = $(this).val();

            if (role === "Operator OLT") {
                $("#olt_wrap").show();
                $("#mic_radius_wrap").hide();

            } else if (role === "Operator Mic Radius") {
                $("#mic_radius_wrap").show();
                $("#olt_wrap").hide();

            } else {
                $("#mic_radius_wrap").hide();
                $("#olt_wrap").hide();
            }
        });
    </script>
@endpush

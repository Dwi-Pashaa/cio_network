@extends('layouts.app')

@section('title')
    Edit User
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('user.index') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="icon icon-tabler icon-tabler-chevrons-left">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M11 7l-5 5l5 5" />
                <path d="M17 7l-5 5l5 5" />
            </svg>
            Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="username" class="mb-2">Username</label>
                <input value="{{ old('username', $user->username) }}" type="text" name="username"
                    id="username" class="form-control @error('username') is-invalid @enderror">
                @error('username') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="name" class="mb-2">Nama Lengkap</label>
                        <input value="{{ old('name', $user->name) }}" type="text" name="name"
                            id="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="email" class="mb-2">Email</label>
                        <input value="{{ old('email', $user->email) }}" type="email" name="email"
                            id="email" class="form-control @error('email') is-invalid @enderror">
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="telp" class="mb-2">No Telephone</label>
                <input value="{{ old('telp', $user->telp) }}" type="text" name="telp" id="telp"
                    class="form-control @error('telp') is-invalid @enderror">
                @error('telp') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group mb-3">
                <label class="mb-2">Level</label>
                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                    <option value="">Pilih</option>
                    @foreach ($role as $item)
                        <option value="{{ $item->name }}"
                            {{ old('role', $user->roles->first()->name ?? '') == $item->name ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
                @error('role') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            {{-- Mic Radius --}}
            <div class="form-group mb-3" id="mic_radius_wrap"
                style="{{ old('role', $user->roles->first()->name ?? '') == 'Operator Mic Radius' ? '' : 'display:none' }}">
                <label class="mb-2">Mic Radius</label>
                <select name="mic_radius_id" id="mic_radius_id"
                    class="form-control @error('mic_radius_id') is-invalid @enderror">
                    <option value="">Pilih</option>
                    @foreach ($micRadius as $mc)
                        <option value="{{ $mc->id }}"
                            {{ old('mic_radius_id', $user->mic_radius_id) == $mc->id ? 'selected' : '' }}>
                            {{ $mc->name }}
                        </option>
                    @endforeach
                </select>
                @error('mic_radius_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            {{-- OLT --}}
            <div class="form-group mb-3" id="olt_wrap"
                style="{{ old('role', $user->roles->first()->name ?? '') == 'Operator OLT' ? '' : 'display:none' }}">
                <label class="mb-2">OLT</label>
                <select name="olt_id" id="olt_id"
                    class="form-control @error('olt_id') is-invalid @enderror">
                    <option value="">Pilih</option>
                    @foreach ($olts as $olt)
                        <option value="{{ $olt->id }}"
                            {{ old('olt_id', $user->olt_id) == $olt->id ? 'selected' : '' }}>
                            {{ $olt->name }}
                        </option>
                    @endforeach
                </select>
                @error('olt_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="password" class="mb-2">Password (Opsional)</label>
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror">
                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="password_confirmation" class="mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            id="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror">
                        @error('password_confirmation') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary float-end">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    $("#role").change(function () {
        var role = $(this).val();

        if (role == "Operator OLT") {
            $("#mic_radius_wrap").hide();
            $("#olt_wrap").show();
        } else if (role == "Operator Mic Radius") {
            $("#mic_radius_wrap").show();
            $("#olt_wrap").hide();
        } else {
            $("#mic_radius_wrap").hide();
            $("#olt_wrap").hide();
        }
    });
</script>
@endpush

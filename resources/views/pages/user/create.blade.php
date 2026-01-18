@extends('layouts.app')

@section('title')
    Tambah User
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('user.index') }}" class="btn btn-primary">
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11 7l-5 5l5 5" /><path d="M17 7l-5 5l5 5" /></svg>
            Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('user.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="username" class="mb-2">Username</label>
                <input value="{{ old('username') }}" type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror">
                @error('username')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <label for="name" class="mb-2">Nama Lengkap</label>
                        <input value="{{ old('name') }}" type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <label for="email" class="mb-2">Email</label>
                        <input value="{{ old('email') }}" type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="telp" class="mb-2">No Telephone</label>
                <input value="{{ old('telp') }}" type="telp" name="telp" id="telp" class="form-control @error('telp') is-invalid @enderror">
                @error('telp')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="username" class="mb-2">Level</label>
                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                    <option value="">Pilih</option>
                    @foreach ($role as $item)
                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('role')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="form-group mb-3" style="display: none">
                <label for="select-mic-radius" class="mb-2">Mic Radius</label>

                <select name="mic_radius_id[]" id="select-mic-radius" 
                    class="form-select @error('mic_radius_id') is-invalid @enderror" 
                    multiple>
                    @foreach ($micRadius as $mc)
                        <option value="{{ $mc->id }}">{{ $mc->name }}</option>
                    @endforeach
                </select>

                @error('mic_radius_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group mb-3" style="display: none">
                <label for="username" class="mb-2">OLT</label>
                <select name="olt_id[]" id="select-olt" class="form-control @error('olt_id') is-invalid @enderror" multiple>
                    <option value="">Pilih</option>
                    @foreach ($olts as $olt)
                        <option value="{{ $olt->id }}">{{ $olt->name }}</option>
                    @endforeach
                </select>
                @error('olt_id')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="username" class="mb-2">Penempatan Kabupaten/Kota</label>
                <select name="regencie_id[]" id="select-regencie" class="form-control @error('regencie_id') is-invalid @enderror" multiple>
                    <option value="">Pilih</option>
                    @foreach ($regencie as $regency)
                        <option value="{{ $regency->id }}">{{ $regency->name }}</option>
                    @endforeach
                </select>
                @error('regencie_id')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <label for="password" class="mb-2">Password</label>
                        <input value="{{ old('password') }}" type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <label for="password_confirmation" class="mb-2">Konfirmasi Password</label>
                        <input value="{{ old('password_confirmation') }}" type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
                        @error('password_confirmation')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="reset" class="btn btn-secondary float-start">Reset</button>
                <button type="submit" id="btn" class="btn btn-primary float-end">
                    <span id="btn-text">Tambah</span>
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
        document.addEventListener("DOMContentLoaded", function () {
            new TomSelect("#select-mic-radius", {
                plugins: ['remove_button'],
                placeholder: "Pilih Mic Radius",
                persist: false,
                maxItems: null, // unlimited
                create: false
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            new TomSelect("#select-olt", {
                plugins: ['remove_button'],
                placeholder: "Pilih OLT",
                persist: false,
                maxItems: null, // unlimited
                create: false
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            new TomSelect("#select-regencie", {
                plugins: ['remove_button'],
                placeholder: "Pilih Penempatan Kabupaten/Kota",
                persist: false,
                maxItems: null, // unlimited
                create: false
            });
        });
        $("#role").change(function() {
            var role = $(this).val();
            if (role == "Operator OLT") {
                $("#select-mic-radius").parent().hide();
                $("#select-olt").parent().show();
            } else if(role == "Operator Mic Radius") {
                $("#select-mic-radius").parent().show();
                $("#select-olt").parent().hide();
            } else {
                $("#select-mic-radius").parent().hide();
                $("#select-olt").parent().hide();
            }
        });
    </script>
    <script>
		document.querySelector('form').addEventListener('submit', function () {
			const btn = document.getElementById('btn');
			const text = document.getElementById('btn-text');
			const loading = document.getElementById('btn-loading');

			btn.disabled = true;            // disable button
			text.textContent = 'Loading...';
			loading.classList.remove('d-none');
		});
	</script>
@endpush
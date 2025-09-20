<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>{{ $pages->name }}</title>
	<!-- CSS files -->
	<link href="{{asset('')}}css/tabler.min.css?1738096682" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-flags.min.css?1738096682" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-socials.min.css?1738096682" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-payments.min.css?1738096682" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-vendors.min.css?1738096682" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-marketing.min.css?1738096682" rel="stylesheet" />
	<link href="{{asset('')}}css/demo.min.css?1738096682" rel="stylesheet" />
	<style>
		@import url('https://rsms.me/inter/inter.css');
	</style>
	<style>
		.line {
			margin: 30px;
		}
	</style>
</head>

<body class=" d-flex flex-column">
	<script src="{{asset('')}}js/demo-theme.min.js?1738096682"></script>
	<div class="page">
		<div class="container py-5">
			<div class="row  d-flex justify-content-center">
				<div class="col-lg-8 col-md-10 col-sm-12">
					<div class="mb-5">
						<h1 class="text-center mb-2 mt-3">{{ $pages->name }}</h1>
						<h4 class="text-center">{{ $pages->desc }}</h4>
					</div>
					<form action="{{ route('switch.data.save') }}" method="POST">
						@csrf
						@include('components.alert.success')
						<div class="card">
							<div class="card-header">
								<b>Data Perbaikan/Pergantian Baru</b>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Pilih ID Pelanggan</label>
											<select name="customer_id" id="customer_id" class="form-control  @error('customer_id') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($customer as $cus)
													<option value="{{ $cus->id }}" {{ old('customer_id') == $cus->id ? 'selected' : '' }}>({{ $cus->uuid }}) - {{ $cus->name }}</option>
												@endforeach
											</select>
											@error('customer_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Pilih Tipe Pelanggan Lama</label>
											<select name="type_old_id" id="type_old_id" class="form-control @error('type_old_id') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($type as $tpl)
													<option value="{{ $tpl->id }}" {{ old('type_old_id') == $tpl->id ? 'selected' : '' }}>{{ $tpl->name }}</option>
												@endforeach
											</select>
											@error('type_old_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Pilih Tipe Router Lama</label>
											<select name="router_old_id" id="router_old_id" class="form-control @error('router_old_id') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($router as $rtrl)
													<option value="{{ $rtrl->id }}" {{ old('router_old_id') == $rtrl->id ? 'selected' : '' }}>{{ $rtrl->name }}</option>
												@endforeach
											</select>
											@error('router_old_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Mac Address Lama</label>
											<input type="text" name="mac_address_old" id="mac_address_old" class="form-control @error('mac_address_old') is-invalid @enderror" value="{{ old('mac_address_old') }}">
											@error('mac_address_old')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card mt-2">
							<div class="card-header">
								<b>Data Perbaikan/Pergantian Baru</b>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Pilih Tipe Pelanggan Baru</label>
											<select name="type_new_id" id="type_new_id" class="form-control  @error('type_new_id') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($type as $tpl)
													<option value="{{ $tpl->id }}" {{ old('type_new_id') == $tpl->id ? 'selected' : '' }}>{{ $tpl->name }}</option>
												@endforeach
											</select>
											@error('type_new_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Pilih Tipe Router Baru</label>
											<select name="router_new_id" id="router_new_id" class="form-control  @error('router_new_id') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($router as $rtrl)
													<option value="{{ $rtrl->id }}" {{ old('router_new_id') == $rtrl->id ? 'selected' : '' }}>{{ $rtrl->name }}</option>
												@endforeach
											</select>
											@error('router_new_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Mac Address Baru</label>
											<input type="text" name="mac_address_new" id="mac_address_new" class="form-control @error('mac_address_new') is-invalid @enderror" value="{{ old('mac_address_new') }}">
											@error('mac_address_new')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">No Telephone</label>
											<input type="text" name="telp" id="telp" class="form-control @error('telp') is-invalid @enderror" value="{{ old('telp') }}">
											@error('telp')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer">
								<button type="reset" class="btn btn-secondary float-start">Reset</button>
								<button type="submit" class="btn btn-primary float-end">Kirim</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- Libs JS -->
	{{-- @if (session('password_required'))
		<div class="modal modal-blur fade" id="passwordModal" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-1 modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Masukan Password</h5>
					</div>
					<form action="{{ route('switch.data.confirm.password') }}" method="POST">
						@csrf
						<div class="modal-body">
							@include('components.alert.danger')
							<input type="hidden" name="slug" id="slug" value="{{ $pages->slug }}">
							<input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password">
							@error('password')
								<span class="invalid-feedback">
									{{ $message }}      
								</span>
							@enderror
						</div>
						<div class="modal-footer">
							<button type="reset" class="btn me-auto">Batal</button>
							<button type="submit" id="storeBtn" class="btn btn-primary">Masuk</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	@endif --}}
	<!-- Tabler Core -->
	<script src="{{asset('')}}js/tabler.min.js?1738096682" defer></script>
	<script src="{{asset('')}}js/demo.min.js?1738096682" defer></script>

	@if (session('password_required'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));
                passwordModal.show();
            });
        </script>
    @endif
</body>

</html>
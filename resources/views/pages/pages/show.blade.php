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
</head>

<body class=" d-flex flex-column">
	<script src="{{asset('')}}js/demo-theme.min.js?1738096682"></script>
	<div class="page">
		<div class="container py-5">
			<div class="row  d-flex justify-content-center">
				<div class="col-lg-8 col-md-10 col-sm-12">
					<div class="mb-5">
						<h1 class="text-center m-0">{{ $pages->name }}</h1>
						<h4 class="text-center">{{ $pages->desc }}</h4>
					</div>
					<form action="{{ route('input.data.saveCustomerToSpan') }}" method="POST">
						@csrf
						<input type="hidden" name="status" id="status" value="spam">
						<input type="hidden" name="wa_phone" id="wa_phone" value="{{ $pages->telp }}">
						
						@include('components.alert.success')

						<div class="card">
							<div class="card-header">
								<b>Form Input Data Pelanggan</b>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">ID Pelanggan</label>
											<input type="text" name="" id="" value="{{ $newCode }}" class="form-control" readonly>
											<input type="hidden" name="uuid" id="uuid" value="{{ $newCode }}">
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Nama Pelanggan</label>
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
											<label for="" class="mb-2">Email Pelanggan</label>
											<input value="{{ old('email') }}" type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror">
											@error('email')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">No Telephone</label>
											<input value="{{ old('telp') }}" type="text" name="telp" id="telp" class="form-control @error('telp') is-invalid @enderror">
											@error('telp')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Mac Address</label>
											<input value="{{ old('mac_address') }}" type="text" name="mac_address" id="mac_address" class="form-control @error('mac_address') is-invalid @enderror">
											@error('mac_address')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Type Pelanggan</label>
											<select name="types_id" id="types_id" class="form-control @error('types_id') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($types as $tp)
													<option data-label="{{ $tp->name }}" value="{{ $tp->id }}" {{ old('types_id') == $tp->id ? 'selected' : '' }}>
														{{ $tp->name }}
													</option>
												@endforeach
												<input type="hidden" name="type_name" id="type_name">
											</select>
											@error('types_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Jenis Router</label>
											<select name="routers_id" id="routers_id" class="form-control @error('routers_id') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($routers as $rtr)
													<option value="{{ $rtr->id }}" {{ old('routers_id') == $rtr->id ? 'selected' : '' }}>
														{{ $rtr->name }}
													</option>
												@endforeach
											</select>
											@error('routers_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="row" id="pppoe-show">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group mb-3">
												<label for="" class="mb-2">Nama Wifi</label>
												<input type="text" name="name_wifi" id="name_wifi" class="form-control @error('name_wifi') is-invalid @enderror">
												@error('name_wifi')
													<span class="invalid-feedback">
														{{ $message }}
													</span>
												@enderror
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group mb-3">
												<label for="" class="mb-2">Password Wifi</label>
												<input type="text" name="password_wifi" id="password_wifi" class="form-control @error('password_wifi') is-invalid @enderror">
												@error('password_wifi')
													<span class="invalid-feedback">
														{{ $message }}
													</span>
												@enderror
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group mb-3">
												<label for="" class="mb-2">Tipe Paket</label>
												<select name="paket_id" id="paket_id" class="form-control @error('paket_id') is-invalid @enderror">
													<option value="">Pilih</option>
													@foreach ($paket as $pkt)
														<option value="{{ $pkt->id }}" {{ old('paket_id') == $pkt->id ? 'selected' : '' }}>{{ $pkt->name }}</option>
													@endforeach
												</select>
												@error('paket_id')
													<span class="invalid-feedback">
														{{ $message }}
													</span>
												@enderror
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group mb-3">
												<label for="" class="mb-2">Tipe Pembayaran</label>
												<select name="price_id" id="price_id" class="form-control @error('price_id') is-invalid @enderror">
													<option value="">Pilih</option>
													@foreach ($price as $prc)
														<option value="{{ $prc->id }}" {{ old('price_id') == $prc->id ? 'selected' : '' }}>{{ $prc->name }}</option>
													@endforeach
												</select>
												@error('price_id')
													<span class="invalid-feedback">
														{{ $message }}
													</span>
												@enderror
											</div>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Kampung</label>
											<select name="hometowns_id" id="hometowns_id" class="form-control @error('hometowns_id') is-invalid @enderror">
												<option value="{{ $pages->hometowns_id }}">{{ $pages->hometown->name }}</option>
											</select>
											@error('hometowns_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">RT</label>
											<select name="rts_id" id="rts_id" class="form-control @error('rts_id') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($rts as $rt)
													<option value="{{ $rt->id }}" {{ old('rts_id') == $rt->id ? 'selected' : '' }}>
														{{ $rt->name }}
													</option>
												@endforeach
											</select>
											@error('rts_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">RW</label>
											<select name="rws_id" id="rws_id" class="form-control @error('rws_id') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($rws as $rw)
													<option value="{{ $rw->id }}" {{ old('rws_id') == $rw->id ? 'selected' : '' }}>
														{{ $rw->name }}
													</option>
												@endforeach
											</select>
											@error('rws_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Desa</label>
											<select name="villages_id" id="villages_id" class="form-control @error('villages_id') is-invalid @enderror">
												<option value="{{ $pages->villages_id }}">{{ $pages->village->name }}</option>
											</select>
											@error('villages_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Kabupaten/Kota</label>
											<select name="regencies_id" id="regencies_id" class="form-control @error('regencies_id') is-invalid @enderror">
												<option value="{{ $pages->regencies_id }}">{{ $pages->regencie->name }}</option>
											</select>
											@error('regencies_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Kecamatan</label>
											<select name="districts_id" id="districts_id" class="form-control @error('districts_id') is-invalid @enderror">
												<option value="{{ $pages->districts_id }}">{{ $pages->district->name }}</option>
											</select>
											@error('districts_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Vlans</label>
											<select name="vlans_id" id="vlans_id" class="form-control @error('vlans_id') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($vlans as $vln)
													<option value="{{ $vln->id }}" data-label="{{ $vln->name }}">{{ $vln->name }}</option>
												@endforeach
											</select>
											@error('vlans_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Alamat ODC</label>
											<select name="odcs_id" id="odcs_id" class="form-control @error('odcs_id') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($odcs as $odc)
													<option value="{{ $odc->id }}">
														{{ $odc->code }} | {{ $odc->hometown_name }} 
														| {{ $odc->rt_number }} | {{ $odc->rw_number }} |
														{{ $odc->odc_name }}
													</option>
												@endforeach
											</select>
											@error('odcs_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Alamat ODP</label>
											<select name="odps_id" id="odps_id" class="form-control @error('odps_id') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($odps as $odp)
													<option value="{{ $odp->id }}">
														{{ $odp->code }} | {{ $odp->hometown_name }} 
														| {{ $odp->rt_number }} | {{ $odp->rw_number }} |
														{{ $odp->odp_name }}
													</option>
												@endforeach
											</select>
											@error('odps_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Alamat OLT</label>
											<select name="olts_id" id="olts_id" class="form-control @error('olts_id') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($olts as $olt)
													<option value="{{ $olt->id }}">
														{{ $olt->code }} | {{ $olt->hometown_name }} 
														{{ $olt->olt_name }}
													</option>
												@endforeach
											</select>
											@error('olts_id')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-12 col-sm-12 col-md-12">
										<div class="mt-3" id="map-container" style="display:none;">
											<iframe id="map-frame"
												width="100%" 
												height="300" 
												style="border:0; border-radius: 10px;"
												loading="lazy" 
												allowfullscreen 
												referrerpolicy="no-referrer-when-downgrade">
											</iframe>
										</div>
									</div>
								</div>
							</div>
							<input type="hidden" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror">
							<input type="hidden" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror">
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

	@if (session('password_required'))
		<div class="modal modal-blur fade" id="passwordModal" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-1 modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Masukan Password</h5>
					</div>
					<form action="{{ route('input.data.confirm.password') }}" method="POST">
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
	@endif
	<!-- Libs JS -->
	<!-- Tabler Core -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="{{asset('')}}js/tabler.min.js?1738096682" defer></script>
	<script src="{{asset('')}}js/demo.min.js?1738096682" defer></script>
    {{-- @if (session('password_required'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));
                passwordModal.show();
            });
        </script>
    @endif --}}

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(
					function(position) {
						let latitude = position.coords.latitude;
						let longitude = position.coords.longitude;

						document.getElementById("latitude").value = latitude;
						document.getElementById("longitude").value = longitude;

						document.getElementById("map-container").style.display = "block";
						document.getElementById("map-frame").src =
							`https://www.google.com/maps?q=${latitude},${longitude}&hl=id&z=15&output=embed`;

					},
					function(error) {
						alert("Error mendapatkan lokasi");
						console.error("Error mendapatkan lokasi:", error.message);
					}
				);
			} else {
				console.error("Browser tidak mendukung geolocation.");
				alert("Error mendapatkan lokasi");
			}
		});

		document.getElementById('pppoe-show').style.display = 'none';

		$("#types_id").change(function() {
			let value = $(this).find("option:selected").data("label");
			if (value === "PPPOE") {
				document.getElementById('pppoe-show').style.display = 'block';
				$("#type_name").val(value)
			} else {
				document.getElementById('pppoe-show').style.display = 'none';
				$("#type_name").val('')
			}
		});
	</script>

</body>

</html>
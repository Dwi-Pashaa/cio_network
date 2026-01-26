@extends('layouts.app-pages')

@section('title')
	{{ $pages->name }}
@endsection

@push('css')
	<link rel="stylesheet" href="{{ asset('css/pages.css') }}">
	<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
@endpush
	
@section('content')
	<div class="container py-5">
		<div class="row d-flex justify-content-center">
			<div class="col-lg-10 col-md-12 col-sm-12">
				<!-- Header Section -->
				<div class="page-header">
					<div class="header-icon-wrapper">
						<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16" class="header-icon">
							<path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
							<path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5"/>
						</svg>
					</div>
					<h1>{{ $pages->name }}</h1>
					<div class="header-divider"></div>
					<h4>
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: middle; margin-right: 8px;">
							<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
						</svg>
						{{ $pages->desc }}
					</h4>
				</div>

				<form action="{{ route('input.data.saveCustomerToSpan') }}" method="POST">
					@csrf
					<input type="hidden" name="status" id="status" value="spam">
					<input type="hidden" name="wa_phone" id="wa_phone" value="{{ $pages->telp }}">
					<input type="hidden" name="latitude" id="latitude">
					<input type="hidden" name="longitude" id="longitude">
					
					@include('components.alert.success')

					@if (session()->has('error'))
						<div class="alert alert-danger">
							{{ session()->get('error') }}
						</div>
					@endif

					<input type="hidden" name="is_ktp" id="is_ktp" value="{{ $pages->is_ktp }}">
					
					@if ($pages->is_ktp === 'aktif')
						<!-- Upload KTP Section -->
						<div class="card section-card">
							<div class="card-header">
								<span class="section-icon">🪪</span> Foto KTP
							</div>
							<div class="card-body">

								<div id="camera-view" class="camera-container">
									<video id="video" autoplay playsinline></video>
									<div class="ktp-frame"></div>
								</div>

								<canvas id="canvas"></canvas>

								<input type="hidden" name="ktp_photo" id="ktp_photo">

								<div class="photo-preview" id="photo-preview" style="display:none;">
									<img id="captured-photo">
								</div>

								<div class="form-group mb-3 mt-3">
									<label class="form-label">NIK <span class="text-danger">*</span></label>
									<input type="text" name="nik" id="nik" class="form-control @error('nik') is-invalid @enderror" placeholder="NIK akan terisi otomatis">
									@error('nik')
										<span class="invalid-feedback">
											{{ $message }}
										</span>
									@enderror
								</div>


								<div class="text-center mt-3">
									<button type="button" id="btn-capture" class="btn btn-capture" onclick="capturePhoto()">
										Ambil Foto
									</button>
									<button type="button" id="btn-retake" class="btn btn-retake" onclick="retakePhoto()" style="display:none;">
										Ulangi
									</button>
								</div>

							</div>
						</div>
					@endif

					<!-- Data Pelanggan Section -->
					<div class="card section-card">
						<div class="card-header">
							<span class="section-icon">👤</span> Data Pelanggan
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 mb-3">
									<label class="form-label">ID Pelanggan</label>
									<input type="text" value="{{ $newCode }}" class="form-control" readonly>
									<input type="hidden" name="uuid" id="uuid" value="{{ $newCode }}">
								</div>
								<div class="col-lg-6 mb-3">
									<label class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
									<input value="{{ old('name') }}" type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap">
									@error('name')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="col-lg-6 mb-3">
									<label class="form-label">Email Pelanggan</label>
									<input value="{{ old('email') }}" type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="contoh@email.com">
									@error('email')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="col-lg-6 mb-3">
									<label class="form-label">No Telephone <span class="text-danger">*</span></label>
									<input value="{{ old('telp') }}" type="text" name="telp" id="telp" class="form-control @error('telp') is-invalid @enderror" placeholder="08xxxxxxxxxx">
									@error('telp')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="col-lg-6 mb-3">
									<label class="form-label">Mac Address</label>
									<input value="{{ old('mac_address') }}" type="text" name="mac_address" id="mac_address" class="form-control @error('mac_address') is-invalid @enderror" placeholder="XX:XX:XX:XX:XX:XX">
									@error('mac_address')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
									<small id="macFeedback"></small>
								</div>
							</div>
						</div>
					</div>

					<!-- Konfigurasi Layanan Section -->
					<div class="card section-card">
						<div class="card-header">
							<span class="section-icon">⚙️</span> Konfigurasi Layanan
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 mb-3">
									<label class="form-label">Type Pelanggan <span class="text-danger">*</span></label>
									<select name="types_id" id="types_id" class="form-control select-tom @error('types_id') is-invalid @enderror">
										<option value="">Pilih Type Pelanggan</option>
										@foreach ($types as $tp)
											<option data-label="{{ $tp->name }}" value="{{ $tp->id }}" {{ old('types_id') == $tp->id ? 'selected' : '' }}>
												{{ $tp->name }}
											</option>
										@endforeach
									</select>
									<input type="hidden" name="type_name" id="type_name">
									@error('types_id')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 mb-3">
									<label class="form-label">Jenis Router</label>
									<input type="text" name="router_name" id="router_name" class="form-control" disabled placeholder="Terisi otomatis">
									<input type="hidden" name="routers_id" id="routers_id">
								</div>
							</div>

							<!-- PPPOE Configuration -->
							<div id="pppoe-show" style="display:none;">
								<hr class="my-4">
								<h6 class="mb-3 text-muted">Konfigurasi PPPOE</h6>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12 mb-3">
										<label class="form-label">Nama WiFi</label>
										<input type="text" name="name_wifi" id="name_wifi" class="form-control @error('name_wifi') is-invalid @enderror" placeholder="Nama SSID WiFi">
										@error('name_wifi')
											<span class="invalid-feedback">{{ $message }}</span>
										@enderror
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 mb-3">
										<label class="form-label">Password WiFi</label>
										<input type="text" name="password_wifi" id="password_wifi" class="form-control @error('password_wifi') is-invalid @enderror" placeholder="Password WiFi">
										@error('password_wifi')
											<span class="invalid-feedback">{{ $message }}</span>
										@enderror
									</div>
									<div class="col-lg-4 col-md-6 col-sm-12 mb-3">
										<label class="form-label">Tipe Paket</label>
										<select name="paket_id" id="paket_id" class="form-control @error('paket_id') is-invalid @enderror">
											<option value="">Pilih Paket</option>
											@foreach ($paket as $pkt)
												<option value="{{ $pkt->id }}" {{ old('paket_id') == $pkt->id ? 'selected' : '' }}>{{ $pkt->name }}</option>
											@endforeach
										</select>
										@error('paket_id')
											<span class="invalid-feedback">{{ $message }}</span>
										@enderror
									</div>
									<div class="col-lg-4 col-md-6 col-sm-12 mb-3">
										<label class="form-label">Mix Radius</label>
										<select name="mic_radius_id" id="mic_radius_id" class="form-control @error('mic_radius_id') is-invalid @enderror">
											<option value="">Pilih Mix Radius</option>
											@foreach ($micRadius as $mc)
												<option value="{{ $mc->id }}" {{ old('mic_radius_id') == $mc->id ? 'selected' : '' }}>{{ $mc->code }} - {{ $mc->name }}</option>
											@endforeach
										</select>
										@error('mic_radius_id')
											<span class="invalid-feedback">{{ $message }}</span>
										@enderror
									</div>
									<div class="col-lg-4 col-md-12 col-sm-12 mb-3">
										<label class="form-label">Tipe Pembayaran</label>
										<select name="price_id" id="price_id" class="form-control @error('price_id') is-invalid @enderror">
											<option value="">Pilih Tipe Pembayaran</option>
											@foreach ($price as $prc)
												<option value="{{ $prc->id }}" {{ old('price_id') == $prc->id ? 'selected' : '' }}>{{ $prc->name }}</option>
											@endforeach
										</select>
										@error('price_id')
											<span class="invalid-feedback">{{ $message }}</span>
										@enderror
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Alamat Section -->
					<div class="card section-card">
						<div class="card-header">
							<span class="section-icon">📍</span> Alamat & Lokasi
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 mb-3">
									<label class="form-label">Kampung</label>
									<select name="hometowns_id" id="hometowns_id" class="form-control @error('hometowns_id') is-invalid @enderror">
										<option value="{{ $pages->hometowns_id }}">{{ $pages->hometown->name }}</option>
									</select>
									@error('hometowns_id')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="col-lg-6 mb-3">
									<label class="form-label">RT</label>
									<select name="rts_id" id="rts_id" class="form-control @error('rts_id') is-invalid @enderror">
										<option value="">Pilih RT</option>
										@foreach ($rts as $rt)
											<option value="{{ $rt->id }}" {{ old('rts_id') == $rt->id ? 'selected' : '' }}>{{ $rt->name }}</option>
										@endforeach
									</select>
									@error('rts_id')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="col-lg-6 mb-3">
									<label class="form-label">RW</label>
									<select name="rws_id" id="rws_id" class="form-control @error('rws_id') is-invalid @enderror">
										<option value="">Pilih RW</option>
										@foreach ($rws as $rw)
											<option value="{{ $rw->id }}" {{ old('rws_id') == $rw->id ? 'selected' : '' }}>{{ $rw->name }}</option>
										@endforeach
									</select>
									@error('rws_id')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="col-lg-12 mb-3">
									<label class="form-label">Desa</label>
									<select name="villages_id" id="villages_id" class="form-control @error('villages_id') is-invalid @enderror">
										<option value="{{ $pages->villages_id }}">{{ $pages->village->name }}</option>
									</select>
									@error('villages_id')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="col-lg-6 mb-3">
									<label class="form-label">Kabupaten/Kota</label>
									<select name="regencies_id" id="regencies_id" class="form-control @error('regencies_id') is-invalid @enderror">
										<option value="{{ $pages->regencies_id }}">{{ $pages->regencie->name }}</option>
									</select>
									@error('regencies_id')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="col-lg-6 mb-3">
									<label class="form-label">Kecamatan</label>
									<select name="districts_id" id="districts_id" class="form-control @error('districts_id') is-invalid @enderror">
										<option value="{{ $pages->districts_id }}">{{ $pages->district->name }}</option>
									</select>
									@error('districts_id')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
							</div>

							<!-- Map Location -->
							<div class="row">
								<div class="col-12">
									<div class="mt-3" id="map-container" style="display:none;">
										<iframe id="map-frame"
											width="100%" 
											height="350" 
											style="border:0; border-radius: 12px;"
											loading="lazy" 
											allowfullscreen 
											referrerpolicy="no-referrer-when-downgrade">
										</iframe>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Infrastruktur Jaringan Section -->
					<div class="card section-card">
						<div class="card-header">
							<span class="section-icon">🔌</span> Infrastruktur Jaringan
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-6 mb-3">
									<label class="form-label">VLAN</label>
									<select name="vlans_id" id="vlans_id" class="form-control @error('vlans_id') is-invalid @enderror">
										<option value="">Pilih VLAN</option>
										@foreach ($vlans as $vln)
											<option value="{{ $vln->id }}" data-label="{{ $vln->name }}">{{ $vln->name }}</option>
										@endforeach
									</select>
									@error('vlans_id')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="col-lg-6 mb-3">
									<label class="form-label">Alamat ODC</label>
									<select name="odcs_id" id="odcs_id" class="form-control @error('odcs_id') is-invalid @enderror">
										<option value="">Pilih ODC</option>
										@foreach ($odcs as $odc)
											<option value="{{ $odc->id }}">
												{{ $odc->code }} | {{ $odc->hometown_name }} | {{ $odc->rt_number }} | {{ $odc->rw_number }} | {{ $odc->odc_name }}
											</option>
										@endforeach
									</select>
									@error('odcs_id')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="col-lg-6 mb-3">
									<label class="form-label">Alamat ODP</label>
									<select name="odps_id" id="odps_id" class="form-control @error('odps_id') is-invalid @enderror">
										<option value="">Pilih ODP</option>
										@foreach ($odps as $odp)
											<option value="{{ $odp->id }}">
												{{ $odp->code }} | {{ $odp->hometown_name }} | {{ $odp->rt_number }} | {{ $odp->rw_number }} | {{ $odp->odp_name }}
											</option>
										@endforeach
									</select>
									@error('odps_id')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="col-lg-6 mb-3">
									<label class="form-label">Alamat OLT</label>
									<select name="olts_id" id="olts_id" class="form-control @error('olts_id') is-invalid @enderror">
										<option value="">Pilih OLT</option>
										@foreach ($olts as $olt)
											<option value="{{ $olt->id }}">
												{{ $olt->code }} | {{ $olt->hometown_name }} {{ $olt->olt_name }}
											</option>
										@endforeach
									</select>
									@error('olts_id')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="col-lg-12 mb-3">
									<label class="form-label">Ukuran Patch Core <span class="text-danger">*</span></label>
									<select name="patch_core_id" id="patch_core_id" class="form-control @error('patch_core_id') is-invalid @enderror">
										<option value="">Pilih Ukuran Patch Core</option>
										@foreach ($pathCore as $pc)
											<option value="{{ $pc->id }}">{{ $pc->name }}</option>
										@endforeach
									</select>
									@error('patch_core_id')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
							</div>
						</div>
					</div>

					<!-- Action Buttons -->
					<div class="card section-card">
						<div class="card-body">
							<div class="d-flex justify-content-between">
								<button type="reset" class="btn btn-secondary">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16" style="vertical-align: middle;">
										<path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
										<path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
									</svg>
									Reset
								</button>
								<button type="submit" id="btn" class="btn btn-primary">
									<span id="btn-text">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16" style="vertical-align: middle;">
											<path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471z"/>
										</svg>
										Kirim Data
									</span>
									<span id="btn-loading" class="spinner-border spinner-border-sm d-none" role="status"></span>
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
	
@push('js')
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
	<script>
		const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

		document.querySelector('form').addEventListener('submit', function (e) {
			const btn = document.getElementById('btn');
			const text = document.getElementById('btn-text');
			const loading = document.getElementById('btn-loading');
			const ktpPhoto = document.getElementById('ktp_photo');

			const isKtpAktif = "{{ $pages->is_ktp }}" === 'aktif';

			if (isKtpAktif) {
				if (!ktpPhoto || ktpPhoto.value === '') {
					e.preventDefault();
					Toast.fire({
						icon: "warning",
						title: "Silahkan Ambil Foto KTP terlebih dahulu sebelum mengirim data."
					});
					return false;
				}
			}

			btn.disabled = true;
			text.classList.add('d-none');
			loading.classList.remove('d-none');
		});
	</script>

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
						console.error("Error mendapatkan lokasi:", error.message);
					}
				);
			}
		});

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

	@if ($isMacValidationActive == true)
		<script>
			document.getElementById('mac_address').addEventListener('blur', function () {
				fetch("{{ route('input.data.checkMacAddress') }}", {
					method: "POST",
					headers: {
						"X-CSRF-TOKEN": "{{ csrf_token() }}",
						"Content-Type": "application/json"
					},
					body: JSON.stringify({
						mac_address: this.value
					})
				})
				.then(res => res.json())
				.then(data => {
					const feedback = document.getElementById('macFeedback');
					feedback.textContent = data.message;
					feedback.style.color = data.valid ? 'green' : 'red';
					
					let routerName = document.getElementById('router_name');
					let routersId = document.getElementById('routers_id');

					if (data.valid == true) {
						routerName.value = data.router.name || '';
						routersId.value = data.router.id || '';
					} else {
						routerName.value = '';
						routersId.value = '';
					}
				});
			});
		</script>
	@endif

	@if ($pages->is_ktp === 'aktif')
		<script>
			let stream = null;
			let video, canvas, capturedPhoto, photoPreview, cameraView, btnCapture, btnRetake, ktpPhotoInput;

			async function checkCameraPermission() {
				try {
					if (navigator.permissions && navigator.permissions.query) {
						const permissionStatus = await navigator.permissions.query({ name: 'camera' });
						
						if (permissionStatus.state === 'denied') {
							showPermissionDeniedAlert();
							return false;
						} else if (permissionStatus.state === 'prompt') {
							showPermissionPromptAlert();
							return false;
						}
					}
					
					return true;
				} catch (error) {
					console.log("Permission API tidak didukung, mencoba akses langsung");
					return true;
				}
			}

			function showPermissionPromptAlert() {
				const alertHTML = `
					<div class="permission-alert" id="permission-alert">
						<div class="icon">📷</div>
						<h4>Izin Kamera Diperlukan</h4>
						<p>Aplikasi memerlukan akses ke kamera untuk mengambil foto KTP Anda. Silakan klik tombol di bawah untuk mengaktifkan izin kamera.</p>
						<button class="btn-activate-camera" onclick="requestCameraPermission()">
							<i class="bi bi-camera-fill me-2"></i>Aktifkan Kamera
						</button>
					</div>
				`;
				
				cameraView.innerHTML = alertHTML;
			}

			function showPermissionDeniedAlert() {
				const alertHTML = `
					<div class="permission-alert" id="permission-alert">
						<div class="icon">🚫</div>
						<h4>Akses Kamera Diblokir</h4>
						<p>Izin akses kamera telah diblokir. Untuk menggunakan fitur ini, Anda perlu mengaktifkan izin kamera secara manual.</p>
						<div class="permission-blocked">
							<p><strong>Cara mengaktifkan:</strong></p>
							<p>1. Klik ikon gembok/info di address bar browser</p>
							<p>2. Cari pengaturan "Kamera"</p>
							<p>3. Ubah dari "Blokir" menjadi "Izinkan"</p>
							<p>4. Muat ulang halaman ini</p>
						</div>
						<button class="btn-activate-camera" onclick="location.reload()" style="margin-top: 16px;">
							<i class="bi bi-arrow-clockwise me-2"></i>Muat Ulang Halaman
						</button>
					</div>
				`;
				
				cameraView.innerHTML = alertHTML;
			}

			async function requestCameraPermission() {
				const button = document.querySelector('.btn-activate-camera');
				button.disabled = true;
				button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Meminta Izin...';
				
				try {
					const testStream = await navigator.mediaDevices.getUserMedia({ 
						video: getVideoConstraints(),
						audio: false 
					});
					
					testStream.getTracks().forEach(track => track.stop());
					
					const alertElement = document.getElementById('permission-alert');
					if (alertElement) {
						alertElement.remove();
					}
					
					cameraView.innerHTML = `
						<video id="video" autoplay playsinline></video>
						<div class="ktp-frame"></div>
					`;
					
					video = document.getElementById('video');
					
					startCamera();
					
				} catch (error) {
					console.error("Error requesting camera permission:", error);
					
					if (error.name === 'NotAllowedError') {
						showPermissionDeniedAlert();
					} else {
						button.disabled = false;
						button.innerHTML = '<i class="bi bi-camera-fill me-2"></i>Coba Lagi';
						
						const errorDiv = document.createElement('div');
						errorDiv.className = 'alert alert-danger mt-3';
						errorDiv.textContent = 'Gagal mengakses kamera: ' + error.message;
						document.getElementById('permission-alert').appendChild(errorDiv);
					}
				}
			}

			function addGuideCorners() {
				const frame = document.querySelector('.ktp-frame');
				if (frame && !frame.querySelector('.ktp-guide-corner')) {
					const corners = ['top-left', 'top-right', 'bottom-left', 'bottom-right'];
					corners.forEach(position => {
						const corner = document.createElement('div');
						corner.className = `ktp-guide-corner ${position}`;
						frame.appendChild(corner);
					});
				}
			}

			function getVideoConstraints() {
				return {
					facingMode: 'environment',
					width: { ideal: 1920, min: 1280 },
					height: { ideal: 1080, min: 720 },
					aspectRatio: { ideal: 16/9 }
				};
			}

			async function startCamera() {
				const hasPermission = await checkCameraPermission();
				if (!hasPermission) {
					return;
				}
				
				addGuideCorners();
				
				const loadingDiv = document.createElement('div');
				loadingDiv.className = 'camera-loading';
				loadingDiv.innerHTML = '<div class="spinner-border text-light mb-2"></div><div>Memuat kamera...</div>';
				cameraView.appendChild(loadingDiv);
				
				try {
					const constraints = {
						video: getVideoConstraints(),
						audio: false
					};
					
					stream = await navigator.mediaDevices.getUserMedia(constraints);
					video.srcObject = stream;
					
					video.addEventListener('loadedmetadata', function() {
						if (loadingDiv && loadingDiv.parentNode) {
							loadingDiv.remove();
						}
					}, { once: true });
					
				} catch (err) {
					console.error("Error accessing camera:", err);
					
					if (err.name === 'NotAllowedError') {
						if (loadingDiv && loadingDiv.parentNode) {
							loadingDiv.remove();
						}
						showPermissionDeniedAlert();
						return;
					}
					
					let errorMsg = "Tidak dapat mengakses kamera. ";
					
					if (err.name === 'NotFoundError') {
						errorMsg += "Kamera tidak ditemukan pada perangkat ini.";
					} else if (err.name === 'NotReadableError') {
						errorMsg += "Kamera sedang digunakan aplikasi lain.";
					} else {
						errorMsg += "Terjadi kesalahan: " + err.message;
					}
					
					if (loadingDiv && loadingDiv.parentNode) {
						loadingDiv.innerHTML = '<div class="alert alert-danger m-3">' + errorMsg + '</div>';
					}
				}
			}

			function extractNikFromImage(imageData) {
				const nikInput = document.getElementById('nik');
				nikInput.value = "Memproses OCR...";

				Tesseract.recognize(imageData, 'ind', {
					logger: m => console.log(m)
				})
				.then(({ data: { text } }) => {
					console.log("OCR Result:", text);

					const nikMatch = text.match(/\b\d{16}\b/);

					if (nikMatch) {
						nikInput.value = nikMatch[0];
					} else {
						nikInput.value = "";
						Toast.fire({
							icon: "warning",
							title: "Nik Tak Terbaca Dari Gambar Silahkan Isi Manual."
						});
					}
				})
				.catch(err => {
					console.error("OCR Error:", err);
					nikInput.value = "";
					Toast.fire({
						icon: "warning",
						title: "Nik Tak Terbaca Dari Gambar Silahkan Isi Manual."
					});
				});
			}

			function capturePhoto() {
				const context = canvas.getContext('2d');

				canvas.width = video.videoWidth;
				canvas.height = video.videoHeight;

				context.drawImage(video, 0, 0, canvas.width, canvas.height);

				const imageData = canvas.toDataURL('image/jpeg', 0.92);
				ktpPhotoInput.value = imageData;

				capturedPhoto.src = imageData;
				photoPreview.style.display = 'block';
				cameraView.style.display = 'none';
				btnCapture.style.display = 'none';
				btnRetake.style.display = 'inline-block';

				stopCamera();

				extractNikFromImage(imageData);
			}


			function retakePhoto() {
				photoPreview.style.display = 'none';
				cameraView.style.display = 'block';
				btnCapture.style.display = 'inline-block';
				btnRetake.style.display = 'none';
				ktpPhotoInput.value = '';
				
				startCamera();
			}

			function stopCamera() {
				if (stream) {
					stream.getTracks().forEach(track => track.stop());
					stream = null;
				}
			}

			document.addEventListener('DOMContentLoaded', function() {
				video = document.getElementById('video');
				canvas = document.getElementById('canvas');
				capturedPhoto = document.getElementById('captured-photo');
				photoPreview = document.getElementById('photo-preview');
				cameraView = document.getElementById('camera-view');
				btnCapture = document.getElementById('btn-capture');
				btnRetake = document.getElementById('btn-retake');
				ktpPhotoInput = document.getElementById('ktp_photo');
				
				startCamera();
			});

			let orientationTimeout;
			window.addEventListener('orientationchange', function() {
				clearTimeout(orientationTimeout);
				orientationTimeout = setTimeout(() => {
					if (stream && cameraView.style.display !== 'none') {
						stopCamera();
						setTimeout(() => startCamera(), 300);
					}
				}, 200);
			});

			window.addEventListener('beforeunload', function() {
				stopCamera();
			});

			document.addEventListener('visibilitychange', function() {
				if (document.hidden) {
					stopCamera();
				} else if (cameraView && cameraView.style.display !== 'none' && !stream) {
					startCamera();
				}
			});
		</script>
	@endif
@endpush
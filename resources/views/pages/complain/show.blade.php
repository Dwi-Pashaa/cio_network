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
					<form action="{{ route('compalin.sendToWa') }}" method="POST">
						@csrf
						@include('components.alert.success')
                        <input type="hidden" name="wa_phone" id="wa_phone" value="{{ $pages->telp }}">
						<div class="card">
							<div class="card-header">
								<b>Form Komplain Pelanggan</b>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-12 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Kode Voucher</label>
											<input required value="{{ old('voucher') }}" type="text" name="voucher" id="voucher" class="form-control @error('voucher') is-invalid @enderror">
											@error('voucher')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
                                    <div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Type Masalah</label>
											<select required name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                                                <option value="">Pilih</option>
                                                @php
                                                    $arr = explode(',', $pages->problem);
                                                @endphp
                                                @foreach ($arr as $type)
                                                    <option value="{{ $type }}">{{ $type }}</option>
                                                @endforeach
                                            </select>
											@error('type')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Tanggal Pertama Kali Dipakai</label>
											<input required value="{{ old('date') }}" type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror">
											@error('date')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Pukul Pertama Kali Dipakai</label>
											<input required value="{{ old('time') }}" type="time" name="time" id="time" class="form-control @error('time') is-invalid @enderror">
											@error('time')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
                                    <div class="col-lg-12 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">No Telephone (WA)</label>
											<input required value="{{ old('telp') }}" type="text" name="telp" id="telp" class="form-control @error('telp') is-invalid @enderror">
											@error('telp')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Kampung</label>
											<select required name="hometown" id="hometown" class="form-control @error('hometown') is-invalid @enderror">
                                                @foreach ($hometowns as $ht)
                                                    <option value="{{ $ht->name }}">{{ $ht->name }}</option>
                                                @endforeach
                                            </select>
											@error('hometown')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">RT</label>
											<select required name="rt" id="rt" class="form-control @error('rt') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($rts as $rt)
													<option value="{{ $rt->name }}">
														{{ $rt->name }}
													</option>
												@endforeach
											</select>
											@error('rt')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">RW</label>
											<select required name="rw" id="rw" class="form-control @error('rw') is-invalid @enderror">
												<option value="">Pilih</option>
												@foreach ($rws as $rw)
													<option value="{{ $rw->name }}">
														{{ $rw->name }}
													</option>
												@endforeach
											</select>
											@error('rw')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Desa</label>
											<select required name="village" id="village" class="form-control @error('village') is-invalid @enderror">
                                                <option value="">Pilih</option>
                                                @foreach ($villages as $vg)
                                                    <option value="{{ $vg->name }}">{{ $vg->name }}</option>
                                                @endforeach
                                            </select>
											@error('village')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Kabupaten/Kota</label>
											<select required name="regencie" id="regencie" class="form-control @error('regencie') is-invalid @enderror">
                                                <option value="">Pilih</option>
                                                @foreach ($regencies as $rg)
                                                    <option value="{{ $rg->name }}">{{ $rg->name }}</option>
                                                @endforeach
                                            </select>
											@error('regencie')
												<span class="invalid-feedback">
													{{ $message }}
												</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group mb-3">
											<label for="" class="mb-2">Kecamatan</label>
											<select required name="district" id="district" class="form-control @error('district') is-invalid @enderror">
                                                <option value="">Pilih</option>
                                                @foreach ($districts as $dt)
                                                    <option value="{{ $dt->name }}">{{ $dt->name }}</option>
                                                @endforeach
                                            </select>
											@error('district')
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
	<!-- Tabler Core -->
	<script src="{{asset('')}}js/tabler.min.js?1738096682" defer></script>
	<script src="{{asset('')}}js/demo.min.js?1738096682" defer></script>
</body>

</html>
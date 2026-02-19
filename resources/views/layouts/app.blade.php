<!doctype html>
<html lang="en">

<head>
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>@yield('title') &mdash; {{ config('app.name') }}</title>
	<!-- CSS files -->
	<link href="{{asset('')}}css/tabler.min.css?1738096685" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-flags.min.css?1738096685" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-socials.min.css?1738096685" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-payments.min.css?1738096685" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-vendors.min.css?1738096685" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-marketing.min.css?1738096685" rel="stylesheet" />
	<link href="{{asset('')}}css/demo.min.css?1738096685" rel="stylesheet" />
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.rtl.min.css"/>
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/bootstrap.rtl.min.css"/>
	<style>
		@import url('https://rsms.me/inter/inter.css');
	</style>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
	<link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.min.css">
	<link href="https://cdn.datatables.net/v/bs5/dt-2.3.7/datatables.min.css" rel="stylesheet" integrity="sha384-MnxpJFHU9dTMYXshJqBDA0d93sG4KKAFEEzynAaDqcPP7BYh11O5HxbJ6iLDWOXS" crossorigin="anonymous">
	@stack('css')
</head>

<body>
	<script src="{{asset('')}}js/demo-theme.min.js?1738096685"></script>
	<div class="page">
		<!-- Navbar -->
		<div class="sticky-top">
			@include('components.header')
            @include('components.navbar')
		</div>
		<div class="page-wrapper">
			<!-- Page header -->
			<div class="page-header d-print-none">
				<div class="container-xl">
					<div class="row g-2 align-items-center">
						<div class="col">
							<!-- Page pre-title -->
							<div class="page-pretitle">
								Pages
							</div>
							<h2 class="page-title">
								@yield('title')
							</h2>
						</div>
					</div>
				</div>
			</div>
			<!-- Page body -->
			<div class="page-body">
				<div class="container-xl">
                    @yield('content')
				</div>
			</div>
			<footer class="footer footer-transparent d-print-none">
				<div class="container-xl">
					<div class="row text-center align-items-center flex-row-reverse">
						<div class="col-12 col-lg-auto mt-3 mt-lg-0">
							<ul class="list-inline list-inline-dots mb-0">
								<li class="list-inline-item">
									Copyright &copy; {{ date('Y') }} {{ config('app.name') }} All rights reserved.
								</li>
							</ul>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>
	@stack('modal')
	<!-- Libs JS -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="{{asset('')}}libs/apexcharts/dist/apexcharts.min.js?1738096685" defer></script>
	<script src="{{asset('')}}libs/jsvectormap/dist/jsvectormap.min.js?1738096685" defer></script>
	<script src="{{asset('')}}libs/jsvectormap/dist/maps/world.js?1738096685" defer></script>
	<script src="{{asset('')}}libs/jsvectormap/dist/maps/world-merc.js?1738096685" defer></script>
	<!-- Tabler Core -->
	<script src="{{asset('')}}js/tabler.min.js?1738096685" defer></script>
	<script src="{{asset('')}}js/demo.min.js?1738096685" defer></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
	<script src="{{ asset('') }}libs/list.js/dist/list.min.js?1759774804" defer=""></script>
	<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>
	<script src="https://cdn.datatables.net/2.3.7/js/dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/v/dt/dt-2.3.7/datatables.min.js" integrity="sha384-aQ8I1X2x8U0AR8D7C4Ah0OvZlwMslQdN5YDAQBA56jXrrhcECijs/i7H+5DDrlV1" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
	<script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
	<script>
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		}); 
	</script>
	@stack('js')
	<script>
		window.Echo = new Echo({
			broadcaster: "pusher",
			key: "{{ env('PUSHER_APP_KEY') }}",
			cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
			forceTLS: true,
		});

		alertify.dialog('minimalDialog', function () {
			return {
				main: function (content) {
					this.setContent(content);
				},
				setup: function () {
					return {
						options: {
							title: "Pesan Baru",
							movable: false,
							resizable: false,
							closable: true,
							transition: "fade"
						}
					};
				}
			};
		});

		Echo.private("chat.{{ auth()->id() }}")
			.listen(".chat-sent", (data) => {

				let html = `
					<div style="font-size: 14px; padding: 5px;">
						<strong>Pesan Baru!</strong><br>
						Dari: ${data.chat.sender_name}<br>
						Pesan: ${data.chat.message}<br><br>

						<button id="goChat"
							class="btn btn-primary btn-sm">
							Pergi ke Chatting
						</button>
					</div>
				`;

			let dialog = alertify.minimalDialog(html);

			setTimeout(() => {
				let btn = document.getElementById("goChat");
				if (btn) {
					btn.onclick = () => {
						window.location.href = "/chatting?user_id=" + data.chat.sender_id;
						dialog.close();
					};
				}
			}, 100);
		});
    </script>
</body>

</html>
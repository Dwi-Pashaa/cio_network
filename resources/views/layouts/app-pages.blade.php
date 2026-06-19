<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>@yield('title')</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- CSS files -->
	<link href="{{asset('')}}css/tabler.min.css?1738096682" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-flags.min.css?1738096682" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-socials.min.css?1738096682" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-payments.min.css?1738096682" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-vendors.min.css?1738096682" rel="stylesheet" />
	<link href="{{asset('')}}css/tabler-marketing.min.css?1738096682" rel="stylesheet" />
	<link href="{{asset('')}}css/demo.min.css?1738096682" rel="stylesheet" />
    @stack('css')
	<style>
		@import url('https://rsms.me/inter/inter.css');
	</style>
</head>

<body class=" d-flex flex-column">
	<script src="{{asset('')}}js/demo-theme.min.js?1738096682"></script>
	<div class="page">
        @yield('content')
        </div>

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
		// Set CSRF token globally for all AJAX requests
		$.ajaxSetup({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
		});
	</script>
	@stack('js')
</body>

</html>
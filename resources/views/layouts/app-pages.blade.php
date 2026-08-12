<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>@yield('title')</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('css')
</head>

<body>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @yield('content')
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
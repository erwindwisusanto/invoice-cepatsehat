<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<title>Invoice Cepat Sehat</title>
		{{-- icons --}}
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.2.96/css/materialdesignicons.min.css"/>
		<link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.svg') }}" />

		{{-- fontello --}}
		<link rel="stylesheet" href="{{ asset('assets/fontello/css/azik.css') }}" />

		{{-- uicons --}}
		<link rel="stylesheet" href="{{ asset('assets/uicons/css/uicons-regular-rounded.css') }}" />

		{{-- bootstrap --}}
		<link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" />

		<link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" />

		{{-- custom --}}
		<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
	</head>
	<body>
		<div class="content">
			{{ $slot }}
		</div>

		<script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
		<script src="{{ asset('https://code.jquery.com/jquery-3.6.0.min.js') }}"></script>
		<script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
	</body>
</html>



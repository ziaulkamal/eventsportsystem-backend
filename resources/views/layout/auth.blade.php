<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="POS - Bootstrap Admin Template">
        <meta name="robots" content="noindex, nofollow">
        <title>{{ $title }}</title>
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
		<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    </head>
        <body class="account-page">

        {{-- <div id="global-loader" >
			<div class="whirly-loader"> </div>
		</div> --}}
        <div class="main-wrapper">
			<div class="account-content">
				<div class="login-wrapper">
                    <div class="login-content">
                        @yield('content')
                    </div>
                    <div class="login-img">
                        <img src="{{ asset('assets/img/authentication/BGSIDE_LOGIN.png') }}" alt="img">
                    </div>
                </div>
			</div>
        </div>
		<div class="customizer-links" id="setdata">
			<ul class="sticky-sidebar">
				<li class="sidebar-icons">
					<a href="#" class="navigation-add" data-bs-toggle="tooltip" data-bs-placement="left"
						data-bs-original-title="Theme">
						<i data-feather="settings" class="feather-five"></i>
					</a>
				</li>
			</ul>
		</div>
        <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
		<script src="{{ asset('assets/js/feather.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        {{-- <script src="{{ asset('assets/js/theme-script.js') }}"></script> --}}
		{{-- <script src="{{ asset('assets/js/script.js') }}"></script> --}}
        @yield('script')
    </body>
</html>

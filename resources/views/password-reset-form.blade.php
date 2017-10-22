<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Welcome to Concepcion Catholic School - Student Grading System</title>


	{{-- Bootstrap 3.3.7 --}}
	{{-- Builtin in Laravel 5.3 --}}
	<link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">

	{{-- Custom CSS --}}
	<link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}">

	{{-- FontAwesome 4.7.0.3--}}
	<link rel="stylesheet" href="{{ URL::asset('fontawesome/css/font-awesome.min.css') }}">

	{{-- Ionicons 2 --}}
	<link rel="stylesheet" href="{{ URL::asset('css/ionicons.css') }}">

	{{-- Admin LTE --}}
	<link rel="stylesheet" href="{{ URL::asset('dist/css/AdminLTE.min.css') }}">

	{{-- Admin LTE Skin --}}
	<link rel="stylesheet" href="{{ URL::asset('dist/css/skins/skin-blue.min.css') }}">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<header class="main-header">
		<!-- <h3 class="text-center">Welcome to Concepcion Catholic School - Student Grading System</h3> -->
		
	</header>
	<hr>
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<h1>Password Reset Form</h1>
			@include('includes.success')
			@include('includes.errors')
			@include('includes.error')
			@include('includes.notice')
			<form action="{{ route('password_reset_last') }}" method="POST" autocomplete="off">
				<div class="form-group">
					<input type="password" name="password" class="form-control" placeholder="Enter New Password" />
				</div>
				<div class="form-group">
					<input type="password" name="password_confirmation" class="form-control" placeholder="Re Enter New Password" />
				</div>
				<div class="form-group">
					<input type="hidden" name="code_id" value="{{ $rc }}" />
					<input type="hidden" name="id" value="{{ $id }}" />
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<button class="btn btn-primary">Continue...</button>
					<a href="{{ route('landing_page') }}" class="btn btn-danger">Cancel</a>
				</div>
			</form>
		</div>
	</div>
	{{-- jQuery2.2.3 --}}
	<script src="{{ URL::asset('js/jquery-2.2.3.min.js') }}"></script>

	{{-- Bootstrap JS --}}
	<script src="{{ URL::asset('js/app.js') }}"></script>

	{{-- AdminLTE JS --}}
	<script src="{{ URL::asset('dist/js/app.min.js') }}"></script>
</body>
</html>
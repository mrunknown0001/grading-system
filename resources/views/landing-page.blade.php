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

	<style type="text/css">
		.btn-huge{
		    padding-top:20px;
		    padding-bottom:20px;
		    width: 80%;
		}
		body {
			color: black;
			font-weight: bold;
		}
	</style>
</head>
<body class="full">
	<header class="main-header">
		<h3 class="text-center">Welcome to Concepcion Catholic School - Student Grading System</h3>
		
	</header>
	<hr>
	<section class="content-header">
		<div class="row">
			<div class="col-md-4">
				<p class="text-center">Vision</p>
				<p>Concepcion Catholic School is a Filipino Catholic institution dedicated to quiality education, animated by the Spirit of the Gospel and inspired by the examples of the Blessed Virgin Mary and St. Dominic.</p>
			</div>
			<div class="col-md-4">
				<!-- <p class="text-center">Login Options</p> -->
				<p class="text-center">
					<a href="{{ route('admin_login') }}" class="btn btn-primary btn-lg btn-huge">Admin Login</a>
				</p>
				<p class="text-center">
					<a href="{{ route('teacher_login') }}" class="btn btn-primary btn-lg btn-huge">Teacher Login</a>
				</p>
				<p class="text-center">
					<a href="{{ route('student_login') }}"  class="btn btn-primary btn-lg btn-huge">Student Login</a>
				</p>
			</div>
			<div class="col-md-4">
				

				<p class="text-center">Our Mission</p>
				<ul class="disk">
					<li>To develop Christian and moral values</li>
					<li>To provide quality education to our pupils and students</li>
					<li>To integrate the aspects of their human knowledge to their daily experiences</li>
				</ul>
				
			</div>
		</div>
	</section>
	{{-- jQuery2.2.3 --}}
	<script src="{{ URL::asset('js/jquery-2.2.3.min.js') }}"></script>

	{{-- Bootstrap JS --}}
	<script src="{{ URL::asset('js/app.js') }}"></script>

	{{-- AdminLTE JS --}}
	<script src="{{ URL::asset('dist/js/app.min.js') }}"></script>
</body>
</html>
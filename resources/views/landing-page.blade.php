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
		<h3 class="text-center">Welcome to Concepcion Catholic School - Student Grading System</h3>
		
	</header>
	<hr>
	<div class="row">
		<div class="col-md-4">
			<p class="text-center">Hisotry</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sint vel, possimus atque. Totam sed eligendi incidunt distinctio, eos, cumque soluta asperiores, est accusantium vitae dignissimos excepturi veniam quis, ducimus. Veniam?</p>
		</div>
		<div class="col-md-4">
			<p class="text-center">Login Options</p>
			<p class="text-center">
				<a href="{{ route('admin_login') }}">Admin Login</a>
			</p>
			<p class="text-center">
				<a href="{{ route('teacher_login') }}">Teacher Login</a>
			</p>
			<p class="text-center">
				<a href="{{ route('student_login') }}">Student Login</a>
			</p>
		</div>
		<div class="col-md-4">
			<p class="text-center">Mission/Vision/Objective</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis expedita, maiores repellat reprehenderit culpa corrupti ad omnis ut vel fugiat rem rerum voluptate itaque optio voluptatibus natus eius reiciendis consectetur.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex ipsam provident reprehenderit delectus nobis laboriosam, accusamus maxime voluptate at commodi, inventore iure impedit quos iste id voluptatum voluptatem. Aliquam, expedita.</p>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officiis sequi, hic enim. Debitis placeat, ducimus! Doloribus sed architecto itaque dolorem quidem libero. Magni ab neque, corporis quae, quaerat in omnis?
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
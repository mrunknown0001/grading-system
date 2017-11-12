<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Teacher Login</title>


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
<body class="full-login">
<header class="main-header">
	<h3 class="text-center" style="color: #eef;">Concepcion Catholic School - Student Grading System</h3>
</header>
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
	          
			<div class="login text-center">
				{{-- Includes errors and session flash message display container --}}
		          @include('includes.errors')
		          @include('includes.error')
		          @include('includes.success')
		          @include('includes.notice')
				<form action="{{ route('post_login') }}" role="form" method="POST" autocomplete="off">
					<h3>Teacher Login</h3>
					<div class="form-group">
						<input type="text" name="id" class="form-control" placeholder="ID Number" onkeypress="return isNumberKey(event);" />
					</div>
					<div class="form-group">
						<input type="password" name="password" class="form-control" placeholder="Password" />
					</div>
					<div class="form-group">
						<input type="hidden" name="code" value="2" />
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<button class="btn btn-primary">Login</button>
						<a href="{{ route('landing_page') }}" class="btn btn-danger">Cancel</a>
					</div>
				</form>
				<p><a href="{{ route('password_reset') }}">Forgot Password?</a></p>
			</div>
		</div>
	</div>
	{{-- jQuery2.2.3 --}}
	<script src="{{ URL::asset('js/jquery-2.2.3.min.js') }}"></script>

	{{-- Bootstrap JS --}}
	<script src="{{ URL::asset('js/app.js') }}"></script>

	{{-- AdminLTE JS --}}
	<script src="{{ URL::asset('dist/js/app.min.js') }}"></script>

	<script>
		function isNumberKey(evt)
		{
		  var charCode = (evt.which) ? evt.which : event.keyCode;
		 console.log(charCode);
		    if (charCode != 46 && charCode != 45 && charCode > 31
		    && (charCode < 48 || charCode > 57))
		     return false;

		  return true;
		}
	</script>
</body>
</html>
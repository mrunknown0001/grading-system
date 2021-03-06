    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top">
      <div class="container">
        <a class="navbar-brand" href="{{ route('landing_page') }}"><img src="{{ asset('uploads/logo/logo.png') }}" height="50px" width="50px" /> <span style="font-family: Old English;" >Concepcion Catholic School</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin_login') }}" >Admin</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('teacher_login') }}">Teacher</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('student_login') }}">Student</a>
            </li>
            <li class="nav-item">
              <!-- <a class="nav-link" href="#">About Us</a> -->
              <div class="dropdown">
                <a class="nav-link">About Us</a>
                <div class="dropdown-content">
                  <p><a href="{{ route('mission_vision') }}">Mission &amp; Vision</a></p>
                  <p><a href="{{ route('about_developers') }}">Developer</a></p>
                  <p><a href="{{ route('contact_us') }}">Contact Us</a></p>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    
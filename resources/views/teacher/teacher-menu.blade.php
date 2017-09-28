<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="{{ URL::asset('uploads/logo/logo.png') }}" alt="Concepcion Catholic School" class="img-circle" height="50px" width="50px"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-md"><img src="{{ URL::asset('uploads/logo/logo.png') }}" alt="Concepcion Catholic School" class="img-circle" height="50px" width="50px"> <b>CCS-SGS</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
    <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">


      <!-- User Account Menu -->
      <li class="dropdown user user-menu">
        <!-- Menu Toggle Button -->
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <!-- The user image in the navbar-->
          <i class="fa fa-cogs" aria-hidden="true"></i>
          <!-- hidden-xs hides the username on small devices so only the image appears. -->
          <span class="hidden-xs">Settings</span>
        </a>
        <ul class="dropdown-menu">
          <!-- Menu Body -->
          <li class="text-center">
            <div>
              <a href="#">Change Profile Picture</a>
            </div>
          </li>
          <li class="text-center">
            <div>
                <a href="#" class=""><span>View Profile</span></a>
            </div>
          </li>
          <li class="text-center">

            <div>
                <a href="#" class="">Change Password</a>
            </div>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <div class="text-center">
              <a href="{{ route('logout') }}" class="btn btn-danger btn-flat">Sign out</a>
            </div>
          </li>
        </ul>
      </li>
    </ul>
    </div>
    </nav>
</header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ URL::asset('uploads/profile/default.jpg') }}" class="img-circle" alt="Admin Image">
        </div>
        <div class="pull-left info">
          <p>{{ ucwords(Auth::user()->firstname) }} {{ ucwords(Auth::user()->lastname) }}</p>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">Menu</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="treeview">
          <a href="#">
            <i class="fa fa-book fa-fw"></i> <span>My Students</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <!-- <li><a href="#"> Menu 1</a></li>
            <li><a href="#"> Menu 2</a></li> -->
            @foreach($students as $std)
            <li><a href="{{ route('get_student_class_subject', $std->id) }}">{{ ucwords($std->section->grade_level->name) }} - {{ ucwords($std->section->name) }} - {{ ucwords($std->subject->title) }}</a></li>
            @endforeach
          </ul>
        </li>
        <!-- <li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li> -->
<!--         <li class="treeview">
          <a href="#"><i class="fa fa-graduation-cap"></i> <span>My Students</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"> Menu 1</a></li>
            <li><a href="#"> Menu 2</a></li>
          </ul>
        </li> -->

      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
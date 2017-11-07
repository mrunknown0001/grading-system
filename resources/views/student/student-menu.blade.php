{{-- subjects for stduent --}}
<?php
  $info = App\StudentInfo::where('user_id', Auth::user()->user_id)->first();
  $year = App\SchoolYear::whereStatus(1)->first();


  $unread = \App\Message::where('student_id', Auth::user()->id)
                ->whereSender(2)
                ->whereStatus(0)
                ->get();
?>


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
          <li class="dropdown messages-menu">
            <!-- Menu toggle button -->
            <a href="{{ route('student_messages') }}" class="dropdown-toggle" >
              <i class="fa fa-envelope-o"></i>
              <span class="label label-danger">@if($unread->count() != 0) {{ $unread->count() }} @endif</span>
            </a>
          </li>

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
              <a href="{{ route('student_profile_picture_change') }}">Change Profile Picture</a>
            </div>
          </li>
          <li class="text-center">
            <div>
                <a href="{{ route('student_view_profile') }}" class=""><span>View Profile</span></a>
            </div>
          </li>
          <li class="text-center">

            <div>
                <a href="{{ route('student_change_password') }}" class="">Change Password</a>
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
          @if(count(Auth::user()->avatar) == 0)
          <img src="{{ URL::asset('uploads/profile/default.jpg') }}" class="img-circle" alt="Teacher Image">
          @else
          <img src="{{ URL::asset('uploads/profile/' . Auth::user()->avatar->name) }}" class="img-circle" alt="Teacher Image">
          @endif
        </div>
        <div class="pull-left info">
          <p>{{ ucwords(Auth::user()->firstname) }} {{ ucwords(Auth::user()->lastname) }}</p>
          <p>{{ Auth::user()->user_id }}</p>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">Menu</li>
        <!-- Optionally, you can add icons to the links -->
<!--         <li class="treeview">
          <a href="#">
            <i class="fa fa-book fa-fw"></i> <span>My Subjects</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @foreach($info->section1->grade_level->subjects as $sub)
            <li><a href="{{ route('student_subject_view', $sub->id) }}"> {{ $sub->title }}</a></li>
            @endforeach
          </ul>
        </li> -->
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
        <li><a href="{{ route('student_view_grades') }}"><i class="fa fa-circle"></i> <span>My Grades</span></a></li>

        <!-- <li><a href="{{ route('student_view_previews_grades') }}"><i class="fa fa-files-o"></i> <span>View Old Grades</span></a></li> -->
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
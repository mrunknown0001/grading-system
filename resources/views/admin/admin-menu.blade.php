<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ route('admin_dashboard') }}" class="logo">
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
              <a href="{{ route('admin_profile_picture_change') }}">Change Profile Picture</a>
            </div>
          </li>
          <li class="text-center">
            <div>
                <a href="{{ route('admin_profile') }}" class=""><span>View Profile</span></a>
            </div>
          </li>
          <li class="text-center">

            <div>
                <a href="{{ route('admin_change_password') }}" class="">Change Password</a>
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
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">Menu</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="treeview">
          <a href="javascript:void();">
            <i class="fa fa-users fa-fw"></i> <span>Teachers</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('add_teacher') }}"> Add Teacher</a></li>
            <li><a href="{{ route('get_all_teachers') }}"> View Teachers</a></li>
            <li class="treeview">
              <a href="#"> <span>Assign Subjects</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('assign_subject_level', 1) }}">Grade 7</a></li>
                <li><a href="{{ route('assign_subject_level', 2) }}">Grade 8</a></li>
                <li><a href="{{ route('assign_subject_level', 3) }}">Grade 9</a></li>
                <li><a href="{{ route('assign_subject_level', 4) }}">Grade 10</a></li>
                <li><a href="{{ route('assign_subject_level', 5) }}">Grade 11</a></li>
                <li><a href="{{ route('assign_subject_level', 6) }}">Grade 12</a></li>
              </ul>
            </li>
            <li><a href="{{ route('view_subject_assignments') }}"> View Subject Assignments</a></li>
          </ul>
        </li>
        <!-- <li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li> -->
        <li class="treeview">
          <a href="javascript:void();"><i class="fa fa-graduation-cap"></i> <span>Students</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('import_students') }}"> Batch Import Students</a></li>
            <li><a href="{{ route('get_add_student') }}"> Add Student</a></li>
            <li><a href="{{ route('get_view_all_students') }}"> View All Students</a></li>
            <li class="treeview">
              <a href="#"> <span>View Students Per Section</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('view_sections_grade_level', ['id' => 1]) }}">Grade 7</a></li>
                <li><a href="{{ route('view_sections_grade_level', ['id' => 2]) }}">Grade 8</a></li>
                <li><a href="{{ route('view_sections_grade_level', ['id' => 3]) }}">Grade 9</a></li>
                <li><a href="{{ route('view_sections_grade_level', ['id' => 4]) }}">Grade 10</a></li>
                <li><a href="{{ route('view_sections_grade_level', ['id' => 5]) }}">Grade 11</a></li>
                <li><a href="{{ route('view_sections_grade_level', ['id' => 6]) }}">Grade 12</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="treeview">
          <a href="javascript:void();"><i class="fa fa-book"></i> <span>Subjects</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('get_add_subject') }}"> Add Subject</a></li>
            <li><a href="{{ route('get_view_all_subjects') }}"> View All Subjects</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="javascript:void();"><i class="fa fa-list"></i> <span>Sections</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('add_section') }}"> Add Section</a></li>
            <li><a href="{{ route('get_all_sections') }}"> View All Sections</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-calendar"></i> <span>School Year</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('add_school_year') }}"> Add &amp; Select School Year</a></li>
            <li><a href="{{ route('select_quarter') }}"> Select Quarter</a></li>
            <li><a href="{{ route('select_semester') }}"> Select Semester</a></li>
          </ul>
        </li>
        <li>
          <a href="{{ route('get_all_users_logs') }}"><i class="fa fa-history fa-fw"></i> <span>Activity Logs</span></a>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
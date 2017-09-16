<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ route('admin_dashboard') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>CCS</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-md"><b>CCS-SGS</b></span>
        <!-- <span class="logo-lg"><b>Concepcion Catholic School Student Grading System</b></span> -->
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

<!--       <li class="dropdown messages-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <i class="fa fa-envelope-o"></i>
          <span class="label label-success">4</span>
        </a>
        <ul class="dropdown-menu">
          <li class="header">You have 4 messages</li>
          <li>
            <ul class="menu">
              <li>
                <a href="#">
                  <div class="pull-left">
                    <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                  </div>
                  <h4>
                    Support Team
                    <small><i class="fa fa-clock-o"></i> 5 mins</small>
                  </h4>
                  <p>Why not buy a new awesome theme?</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="footer"><a href="#">See All Messages</a></li>
        </ul>
      </li> -->

<!-- 
      <li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <i class="fa fa-bell-o"></i>
          <span class="label label-warning">10</span>
        </a>
        <ul class="dropdown-menu">
          <li class="header">You have 10 notifications</li>
          <li>
            <ul class="menu">
              <li>
                <a href="#">
                  <i class="fa fa-users text-aqua"></i> 5 new members joined today
                </a>
              </li>
            </ul>
          </li>
          <li class="footer"><a href="#">View all</a></li>
        </ul>
      </li>

      <li class="dropdown tasks-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <i class="fa fa-flag-o"></i>
          <span class="label label-danger">9</span>
        </a>
        <ul class="dropdown-menu">
          <li class="header">You have 9 tasks</li>
          <li>
            <ul class="menu">
              <li>
                <a href="#">
                  <h3>
                    Design some buttons
                    <small class="pull-right">20%</small>
                  </h3>
                  <div class="progress xs">
                    <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                      <span class="sr-only">20% Complete</span>
                    </div>
                  </div>
                </a>
              </li>

            </ul>
          </li>
          <li class="footer">
            <a href="#">View all tasks</a>
          </li>
        </ul>
      </li> -->
      <!-- User Account Menu -->
      <li class="dropdown user user-menu">
        <!-- Menu Toggle Button -->
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <!-- The user image in the navbar-->
          <!-- <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image"> -->
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

            <!-- <div class="row">
              <div class="col-xs-4 text-center">
                <a href="#">Followers</a>
              </div>
              <div class="col-xs-4 text-center">
                <a href="#">Sales</a>
              </div>
              <div class="col-xs-4 text-center">
                <a href="#">Friends</a>
              </div>
            </div> -->
            <!-- /.row -->
            <div>
                <a href="#" class="">Change Password</a>
            </div>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer"><!-- 
            <div class="pull-left">
              <a href="#" class="btn btn-default btn-flat">Profile</a>
            </div> -->
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
              <span>
                <i class="fa fa-angle-left pull-right"></i>
              </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#">Grade 7</a></li>
                <li><a href="#">Grade 8</a></li>
                <li><a href="#">Grade 9</a></li>
                <li><a href="#">Grade 10</a></li>
                <li><a href="#">Grade 11</a></li>
                <li><a href="#">Grade 12</a></li>
              </ul>
            </li>
            <li><a href="#"> View Subject Assignments</a></li>
          </ul>
        </li>
        <!-- <li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li> -->
        <li class="treeview">
          <a href="#"><i class="fa fa-graduation-cap"></i> <span>Students</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"> Batch Import Students</a></li>
            <li><a href="{{ route('get_add_student') }}"> Add Student</a></li>
            <li><a href="{{ route('get_view_all_students') }}"> View All Students</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-book"></i> <span>Subjects</span>
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
          <a href="#"><i class="fa fa-list"></i> <span>Sections</span>
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
            <li><a href="#"> Select Quarter</a></li>
            <li><a href="#"> Select Semester</a></li>
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
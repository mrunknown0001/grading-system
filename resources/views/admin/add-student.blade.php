@extends('layouts.app')

@section('title') Add Student - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Student
      </h1>
<!--       <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section> -->

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
      <div class="row">
        <div class="col-md-6">
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')
          <form action="{{ route('post_add_student') }}" method="POST" autocomplete="off">
          <div class="form-group">
            <select name="section" id="" class="form-control">
              <option value="">Select Grade Level &amp; Section</option>
              @foreach($sections as $s)
              <option value="{{ $s->id }}">{{ ucwords($s->grade_level->name) }} - {{ ucwords($s->name) }}</option>
              @endforeach
              @if(count($sections) == 0)
              <option value="">No Section Added</option>
              @endif
            </select>
          </div>
          <div class="form-group">
            <input type="text" name="student_number" class="form-control" placeholder="Student Number" autofocus="" />
          </div>
          <div class="form-group">
            <input type="text" name="firstname" class="form-control text-capitalize" placeholder="First Name" />
          </div>
          <div class="form-group">
            <input type="text" name="lastname" class="form-control text-capitalize" placeholder="Last Name" />
          </div>
          <div class="form-group">
            <input type="text" name="birthday" class="form-control" placeholder="MM/DD/YYYY" />
          </div>
          <div class="form-group">
            <select name="gender" id="gender" class="form-control">
              <option value="">Select Gender...</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
          <div class="form-group">
            <input type="text" name="address" class="form-control text-capitalize" placeholder="Address" />
          </div>
          <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Email Address" />
          </div>
          <div class="form-group">
            <input type="text" name="mobile" class="form-control" placeholder="11 Digit Mobile Number" />
          </div>
          <div class="form-group">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <button class="btn btn-primary">Add Student</button>
            <a href="{{ route('get_view_all_students') }}" class="btn btn-danger">Cancel</a>
          </div>
          </form>
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @include('includes.footer')
</div>
<!-- ./wrapper -->

</div>
@endsection
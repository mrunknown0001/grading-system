@extends('layouts.app')

@section('title') Update Student Details - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Update Student Details
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
          <form action="{{ route('post_update_student_details') }}" method="POST" autocomplete="off">
          <div class="form-group">
            <select name="section" id="" class="form-control">
              <option value="">Select Grade Level &amp; Section</option>
              @foreach($sections as $s)
              <option @if($s->id == $student->info->section) selected @endif value="{{ $s->id }}">{{ ucwords($s->grade_level->name) }} - {{ ucwords($s->name) }}</option>
              @endforeach
              @if(count($sections) == 0)
              <option value="">No Section Added</option>
              @endif
            </select>
          </div>
          <div class="form-group">
            <input type="text" name="student_number" class="form-control" value="{{ $student->user_id }}" placeholder="Student Number" autofocus="" />
          </div>
          <div class="form-group">
            <input type="text" name="firstname" class="form-control text-capitalize" value="{{ $student->firstname }}" placeholder="First Name" />
          </div>
          <div class="form-group">
            <input type="text" name="lastname" class="form-control text-capitalize" value="{{ $student->lastname }}" placeholder="Last Name" />
          </div>
          <div class="form-group">
            <input type="text" name="birthday" class="form-control" value="{{ $student->birthday }}" placeholder="MM/DD/YYYY" />
          </div>
          <div class="form-group">
            <select name="gender" id="gender" class="form-control">
              <option value="">Select Gender...</option>
              <option @if($student->gender == 'Male') selected @endif value="Male">Male</option>
              <option @if($student->gender == 'Female') selected @endif value="Female">Female</option>
            </select>
          </div>
          <div class="form-group">
            <input type="text" name="address" class="form-control text-capitalize" value="{{ $student->address }}" placeholder="Address" />
          </div>
          <div class="form-group">
            <input type="email" name="email" class="form-control" value="{{ $student->email }}" placeholder="Email Address" />
          </div>
          <div class="form-group">
            <input type="text" name="mobile" class="form-control" value="{{ $student->mobile }}" placeholder="11 Digit Mobile Number" />
          </div>
          <div class="form-group">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="hidden" name="id" value="{{ $student->id }}" />
            <input type="hidden" name="original_user_id" value="{{ $student->user_id }}" />
            <button class="btn btn-primary">Update Student Details</button>
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
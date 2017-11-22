@extends('layouts.app')

@section('title') Update Teacher Detail - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Update Teacher Details
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
          <form action="{{ route('post_update_teacher_details') }}" method="POST" autocomplete="off">
          <div class="form-group">
          <input type="text" name="id_number" class="form-control" value="{{ $user->user_id }}" placeholder="ID Number (e.g.: 0000-0000)" autofocus="" />
          </div>
          <div class="form-group">
          <input type="text" name="firstname" class="form-control text-capitalize" value="{{ $user->firstname }}" placeholder="First Name" />
          </div>
          <div class="form-group">
          <input type="text" name="lastname" class="form-control text-capitalize" value="{{ $user->lastname }}" placeholder="Last Name" />
          </div><!-- 
          <div class="form-group">
          <input type="text" name="birthday" class="form-control" value="{{ $user->birthday }}" placeholder="MM/DD/YYYY" />
          </div> -->
          <div class="form-group">
          <select name="gender" id="gender" class="form-control">
          <option value="">Select Gender...</option>
          <option @if($user->gender == "Male") selected @endif value="Male">Male</option>
          <option @if($user->gender == "Female") selected @endif value="Female">Female</option>
          </select>
          </div>
          <div class="form-group">
          <input type="text" name="address" class="form-control text-capitalize" value="{{ $user->address }}" placeholder="Address" />
          </div>
          <div class="form-group">
          <input type="email" name="email" class="form-control" value="{{ $user->email }}" placeholder="Email Address" />
          </div>
          <div class="form-group">
          <input type="text" name="mobile" class="form-control" value="{{ $user->mobile }}" placeholder="11 Digit Mobile Number" />
          </div>
          <div class="form-group">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $user->id }}" />
          <button class="btn btn-primary">Update</button>
          <a href="{{ route('get_all_teachers') }}" class="btn btn-danger">Cancel</a>
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
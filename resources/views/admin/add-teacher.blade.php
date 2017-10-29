@extends('layouts.app')

@section('title') Add Teacher - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Teacher
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
          <form action="{{ route('post_add_teacher') }}" method="POST" autocomplete="off">
          <div class="form-group">
          <input type="text" name="id_number" class="form-control" placeholder="ID Number (e.g.: 0000-0000)" autofocus="" />
          </div>
          <div class="form-group">
          <input type="text" name="firstname" class="form-control text-capitalize" placeholder="First Name" />
          </div>
          <div class="form-group">
          <input type="text" name="lastname" class="form-control text-capitalize" placeholder="Last Name" />
          </div>
          <div class="form-group">
          <input type="date" name="birthday" class="form-control" placeholder="MM/DD/YYYY" />
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
          <input type="number" name="mobile" class="form-control" placeholder="Mobile or Contact Number" />
          </div>
          <div class="form-group">
          {{ csrf_field() }}
          <button class="btn btn-primary">Add Teacher</button>
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
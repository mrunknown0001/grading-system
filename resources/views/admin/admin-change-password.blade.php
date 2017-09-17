@extends('layouts.app')

@section('title') Change Password - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
        Change Passowrd
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
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')

          <form action="{{ route('post_admin_change_password') }}" method="POST">
              <div class="form-group">
                <input type="password" name="old_password" class="form-control" placeholder="Old Password" />
              </div>
              <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="New Password" />
              </div>
              <div class="form-group">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confrim Password" />
              </div>
              <div class="form-group">
                {{ csrf_field() }}
                <button class="btn btn-primary">Change Password</button>
              </div>
            </form>
            <p><i>Note: Use alpha-numeric password, minimum password: 8</i></p>
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
@extends('layouts.app')

@section('title') Update Email  - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Update Admin Email
      </h1>
      <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol> -->
    </section>
  <hr>
    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
    <div class="row">
          <div class="col-lg-4 col-md-6">
            {{-- Includes errors and session flash message display container --}}
            @include('includes.errors')
            @include('includes.error')
            @include('includes.success')
            @include('includes.notice')

            <form action="{{ route('post_admin_email') }}" method="POST" autocomplete="off">
              <div class="form-group">
                <input type="text" name="email" value="{{ Auth::user()->email }}" class="form-control" placeholder="Email" />
              </div>
              <div class="form-group">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button class="btn btn-primary">Update Email</button>
                <a href="{{ route('admin_dashboard') }}" class="btn btn-danger">Cancel</a>
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
@extends('layouts.app')

@section('title') Add School Year - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add School Year
      </h1>
<!--       <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section> -->

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
      <hr>
      <div class="row">
        <div class="col-md-4">
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')
          <form action="{{ route('post_add_school_year') }}" method="POST" class="">
            <div class="form-group">

              <select name="school_year" class="form-control">
              <option value="">Select School Year</option>
              <option value="{{ date('Y') }}">{{ date('Y') }} - {{ date('Y')+1 }}</option>
              </select>

            </div>
            <div class="form-group pull-right">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <button class="btn btn-primary">Add School Year</button>
            </div>
          </form>
          <hr>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-6">
          <button class="btn btn-warning" data-toggle="modal" data-target="#close-school-year">Close School Year</button>
        </div>
      </div>
      <p><i>Note: You can't Add a new School Year if there is an Active School Year.</i></p>
    
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @include('admin.includes.modal-close-school-year')
  @include('includes.footer')
</div>
<!-- ./wrapper -->

@endsection
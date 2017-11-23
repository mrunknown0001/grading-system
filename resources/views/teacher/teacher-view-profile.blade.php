@extends('layouts.app')

@section('title') Teacher Profile - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('teacher.teacher-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
            Teacher Profile 
       
      </h1>
<!--       <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>-->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
      <div class="row">
        <div class="col-md-8">
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')
          <table class="table">
            <tr>
              <td>Name:</td>
              <td>{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</td>
            </tr>
            <tr>
              <td>Gender:</td>
              <td>{{ Auth::user()->gender }}</td>
            </tr>
            <!-- <tr>
              <td>Birthday:</td>
              <td>{{ date('F d, Y', strtotime(Auth::user()->birthday)) }}</td>
            </tr> -->
            <tr>
              <td>Address:</td>
              <td>{{ Auth::user()->address }}</td>
            </tr>
            <tr>
              <td>Email:</td>
              <td>{{ Auth::user()->email }}</td>
            </tr>
            <tr>
              <td>Contact #:</td>
              <td>{{ Auth::user()->mobile }}</td>
            </tr>
          </table>
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
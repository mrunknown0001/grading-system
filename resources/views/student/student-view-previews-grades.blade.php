@extends('layouts.app')

@section('title') Student Previews Grades - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('student.student-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
            Grades
       
      </h1>
<!--       <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>-->
    </section>
    <hr>
    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
      <div class="row">
        <div class="col-md-4">
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')
          <form action="#" method="GET">
            <div class="form-group">
              <select name="school_year" class="form-control">
                <option>Select School Year</option>
                @foreach($sy as $y)
                <option value="{{ $y->id }}">{{ $y->from . ' - ' . $y->to }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <button class="btn btn-primary">View Grades</button>
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
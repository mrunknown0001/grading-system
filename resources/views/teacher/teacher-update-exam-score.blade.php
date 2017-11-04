@extends('layouts.app')

@section('title') Update Exam Score - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('teacher.teacher-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
            Update Exam Score 
       
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
        <div class="col-md-4">
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')
          <h4>{{ ucwords($student->firstname . ' ' . $student->lastname) }}</h4>
          <form action="{{ route('post_update_exam_score') }}" method="POST" autocomplete="off">
            <div class="form-group">
              <p><strong>Total: {{ $score->total }}</strong></p>
              <input type="hidden" name="total" value="{{ $score->total }}" />
            </div>

            <div class="form-group">
              <input type="text" name="score" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ $score->score }}" max="{{ $score->total }}" required="" />
            </div>
            <div class="form-group">
              <input type="hidden" name="assignid" value="{{ $assignid }}" />
              <input type="hidden" name="id" value="{{ $score->id }}" />
              <input type="hidden" name="user_id" value="{{ $student->user_id }}" />
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <button class="btn btn-primary">Update Score</button>
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
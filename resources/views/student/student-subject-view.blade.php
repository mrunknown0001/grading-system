@extends('layouts.app')

@section('title') Subject View - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('student.student-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
            Subject: {{ ucwords($subject->title) }}
       
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
          <hr>
          <a href="{{ route('student_view_written_works', ['year_id' => $year->id, 'section' => $info->section1->id, 'subject' => $subject->id, 'student_number' => Auth::user()->user_id]) }}" class="btn btn-primary btn-lg">Written Works</a>
          <a href="{{ route('student_view_performance_tasks', ['year_id' => $year->id, 'section' => $info->section1->id, 'subject' => $subject->id, 'student_number' => Auth::user()->user_id]) }}" class="btn btn-primary btn-lg">Performance Tasks</a>
          <a href="{{ route('student_view_exams', ['year_id' => $year->id, 'section' => $info->section1->id, 'subject' => $subject->id, 'student_number' => Auth::user()->user_id]) }}" class="btn btn-primary btn-lg">Exams</a>
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
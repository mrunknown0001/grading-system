@extends('layouts.app')

@section('title') No Exam Score - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('teacher.teacher-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
            No Exam Score in {{ ucwords($assign->subject->title) }} | <a href="{{ route('get_student_class_subject', $assign->id) }}">{{ ucwords($assign->section->grade_level->name) }} - {{ ucwords($assign->section->name) }}</a>
       
      </h1>
      
      <hr>
      <h1>Go to <a href="{{ route('get_student_class_subject', $assign->id) }}">{{ ucwords($assign->section->grade_level->name) }} - {{ ucwords($assign->section->name) }} - {{ ucwords($assign->subject->title) }}</a></h1>
       
      </h1>

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
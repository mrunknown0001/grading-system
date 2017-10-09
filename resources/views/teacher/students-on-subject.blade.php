@extends('layouts.app')

@section('title') Students on Subject - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('teacher.teacher-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
            Students on {{ $assign->section->grade_level->name }} - {{ ucwords($assign->section->name) }} - {{ ucwords($assign->subject->title) }}
       
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
        <div class="col-md-10">
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')
          <div>
            <!-- <button class="btn btn-primary btn-xs">Add Written Work</button> -->
            <a href="{{ url('teacher/add/written-work/section/' . $assign->section->id . '/subject/' . $assign->subject->id) . '/' . $assign->id . '/get' }}" class="btn btn-primary btn-xs">Add Written Work</a>
            <a href="{{ url('teacher/add/performance-task/section/' . $assign->section->id . '/subject/' . $assign->subject->id) . '/' . $assign->id . '/get' }}" class="btn btn-primary btn-xs">Add Performance Task</a>
            <a href="{{ url('teacher/add/exam/section/' . $assign->section->id . '/subject/' . $assign->subject->id) . '/' . $assign->id . '/get' }}" class="btn btn-primary btn-xs">Add Exam</a>
            |
            <a href="{{ route('view_written_work_score', ['section' => $assign->section->id, 'subject' => $assign->subject->id, 'assign' => $assign->id] )}}" class="btn btn-success btn-xs">View Written Works Scores</a>
            <a href="{{ route('view_performance_task_score', ['section' => $assign->section->id, 'subject' => $assign->subject->id, 'assign' => $assign->id] )}}" class="btn btn-success btn-xs">View Performance Task Scores</a>
            <a href="{{ route('view_exam_score', ['section' => $assign->section->id, 'subject' => $assign->subject->id, 'assign' => $assign->id] )}}" class="btn btn-success btn-xs">View Exam Scores</a>
          </div>
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Student Number</th>
                <th>First Name</th>
                <th>Last Name</th>
              </tr>
            </thead>
            <tbody>
              @foreach($all_students as $astd)
              <tr>
                <td>{{ $astd->user->user_id }}</td>
                <td>{{ ucwords($astd->user->firstname) }}</td>
                <td>{{ ucwords($astd->user->lastname) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <p class="text-center"><strong>{{ $all_students->count() }} students</strong></p>

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
@extends('layouts.app')

@section('title') Subject Grades - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('teacher.teacher-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
            {{ ucwords($section->grade_level->name . ' - ' . $section->name) }} - {{ ucwords($subject->title) }} Grades 
       
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
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Name</th>
                <th>First</th>
                <th>Second</th>
              </tr>
            </thead>
            <tbody>
              @foreach($section->students as $std)
                <tr>
                  <td>{{ ucwords($std->user->lastname . ', ' . $std->user->firstname) }}</td>
                  <td>
                    @foreach($fsg as $f)
                      @if($f['student_id'] == $std->user_id)
                      {{ \App\Http\Controllers\StudentController::getGrade($f['grade']) }}
                      @endif
                    @endforeach
                  </td>
                  <td>
                    @foreach($ssg as $s)
                      @if($s['student_id'] == $std->user_id)
                      {{ \App\Http\Controllers\StudentController::getGrade($s['grade']) }}
                      @endif
                    @endforeach
                  </td>
                </tr>
              @endforeach
            </tbody>
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
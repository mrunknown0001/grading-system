@extends('layouts.app')

@section('title') My Grades - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('student.student-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
            My Grades
       
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
        <div class="col-md-12">
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')

          @if(Auth::user()->info->section1->grade_level->id == 5 ||
          Auth::user()->info->section1->grade_level->id == 6)
          <table class="table">
            <thead>
              <tr>
                <th>Subject</th>
                <th>First Semester</th>
                <th>Second Semester</th>
                <th>Final</th>
              </tr>
            </thead>
            <tbody>
              @foreach($subjects as $sub)
              <?php
              $g1 = 0;
              $g2 = 0;
              $g1_total = 0;
              $g1_count = 0;
              $g2_total = 0;
              $g2_count = 0;
              ?>
              <tr>
                <td><strong>{{ ucwords($sub->title) }}</strong></td>
                <td>
                  @if(count($fsg) != 0)
                    @foreach($fsg as $f)
                      @if($f['subject_id'] == $sub->id)
                        @if($f['grade'] == 0)
                        N/A
                        @else
                        <strong>{{ $g1 = \App\Http\Controllers\StudentController::getGrade($f['grade']) }}</strong>
                        <!-- <div>{{ $g1_total = $g1_total + $g1 }}
                        {{ $g1_count = $g1_count + 1 }}</div> -->
                        @endif
                      @endif
                    @endforeach
                  @endif
                </td>
                <td>
                  @if(count($ssg) != 0)
                    @foreach($ssg as $s)
                      @if($s['subject_id'] == $sub->id)
                        @if($s['grade'] == 0)
                        N/A
                        @else
                        <strong>{{ $g2 = \App\Http\Controllers\StudentController::getGrade($s['grade']) }}</strong>
                        
                        @endif
                      @endif
                    @endforeach
                  @endif
                </td>
                <td>
                  {{ $final = ($g1 + $g2)/2 }}
                </td>
                <!-- <td></td> -->
              </tr>
              @endforeach
              <tr>
                <th>Average</th>
                <td>{{ $g1_total/$g1_count }}</td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
          
          @else 
<!--  -->
          @endif
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
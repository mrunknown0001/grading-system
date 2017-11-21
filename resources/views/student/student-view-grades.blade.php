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

      <button id="printbutton" onclick="window.print();" class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
      <!-- Your Page Content Here -->
      <div class="row">
        <div class="col-md-12" id="myGrades">
          <div id="info">
            <h4 class="text-center">Concepcion Catholic School</h4>
            <p>Student Name: {{ ucwords(Auth::user()->firstname) }} {{ ucwords(Auth::user()->lastname) }}</p>
            <p>ID Number: {{ Auth::user()->user_id }}</p>
            <h5>Grade &amp; Section: {{ ucwords($section->grade_level->name) }} - {{ ucwords($section->name) }}</h5>
            <h5>School Year: {{ $asy->from }} - {{ $asy->to }}</h5>
            <br>
            <p class="text-center"><strong>Unofficial</strong></p>
          </div>

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
              <?php $grade_total = 0; ?>
              @foreach($subjects as $sub)
              <?php
              $g1 = 0;
              $g2 = 0;
              $g1_total = 0;
              $g2_total = 0;
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
                        <!-- <div>{{ $g1_total = $g1_total + $g1 }}</div> -->
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
                        <!-- <div>{{ $g2_total = $g2_total + $g2 }}</div> -->
                        @endif
                      @endif
                    @endforeach
                  @endif
                </td>
                <td>
                  {{ $final = ($g1 + $g2)/2 }}
                  <!-- <div>{{ $grade_total = $grade_total + $final }}</div> -->
                </td>
                <!-- <td></td> -->
              </tr>
              @endforeach
              <tr>
                <th>Average</th>
                <th colspan="5">
                    <u>{{ $grade_total/count($subjects) }}</u>
                </th>
              </tr>
            </tbody>
          </table>
          @else 
          <table class="table table-hover">
            <thead>
              <tr>
                <th><strong>Subjects</strong></th>
                <th>First Quarter</th>
                <th>Second Quarter</th>
                <th>Third Quarter</th>
                <th>Fourth Quarter</th>
                <th>Final</th>
                <!-- <th>GWA</th> -->
              </tr>
            </thead>
            <tbody>
              <?php $grade_total = 0; ?>
              @foreach($subjects as $sub)
              <?php
              $g1 = 0;
              $g2 = 0;
              $g3 = 0;
              $g4 = 0;
              $g1_total = 0;
              $g2_total = 0;
              $g3_total = 0;
              $g4_total = 0;

              ?>
              <tr>
                <td><strong>{{ $sub->title }}</strong></td>
                <td>
                  @if(count($fqg) != 0)
                    @foreach($fqg as $f)
                      @if($f['subject_id'] == $sub->id)
                        @if($f['grade'] == 0)
                        N/A
                        @else
                        <strong>{{ $g1 = \App\Http\Controllers\StudentController::getGrade($f['grade']) }}</strong>
                        <!-- <div>{{ $g1_total = $g1_total + $g1 }}</div> -->
                        @endif
                      @endif
                    @endforeach
                  @endif
                </td>
                <td>
                  @if(count($sqg) != 0)
                    @foreach($sqg as $s)
                      @if($s['subject_id'] == $sub->id)
                        @if($s['grade'] == 0)
                        N/A
                        @else
                        <strong>{{ $g2 = \App\Http\Controllers\StudentController::getGrade($s['grade']) }}</strong>
                        <!-- <div>{{ $g2_total = $g2_total + $g2 }}</div> -->
                        @endif
                      @endif
                    @endforeach
                  @endif
                </td>
                <td>
                  @if(count($tqg) != 0)
                    @foreach($tqg as $t)
                      @if($t['subject_id'] == $sub->id)
                        @if($t['grade'] == 0)
                        N/A
                        @else
                        <strong>{{ $g3 = \App\Http\Controllers\StudentController::getGrade($t['grade']) }}</strong>
                        <!-- <div>{{ $g3_total = $g3_total + $g3 }}</div> -->
                        @endif
                      @endif
                    @endforeach
                  @endif
                  
                </td>
                <td>
                  
                  @if(count($foqg) != 0)
                    @foreach($foqg as $fo)
                      @if($fo['subject_id'] == $sub->id)
                        @if($fo['grade'] == 0)
                        N/A
                        @else
                        <strong>{{ $g4 = \App\Http\Controllers\StudentController::getGrade($fo['grade']) }}</strong>
                        <!-- <div>{{ $g4_total = $g4_total + $g4 }}</div> -->
                        @endif
                      @endif
                    @endforeach
                  @endif
                </td>
                <td>
                  {{ $final = ($g1 + $g2 + $g3 + $g4)/4 }}
                  <!-- <div>{{ $grade_total = $grade_total + $final }}</div> -->
                </td>
                <!-- <td></td> -->
              </tr>
              @endforeach
              <tr>
                <th>Average</th>
                <th colspan="4"><u>{{ $grade_total/count($subjects) }}</u></th>
              </tr>
            </tbody>
          </table>
          @endif
          <div id="info">
            <p>Date Printed: {{ date('F d, Y') }}</p>
          </div>
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
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
          <table>
            <thead>
              <tr>
                <th>First Semester</th>
                <th>Second Semester</th>
                <th>Final</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                
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
                <th>GWA</th>
              </tr>
            </thead>
            <tbody>
              @foreach($subjects as $sub)
              <tr>
                <td><strong>{{ $sub->title }}</strong></td>
                <td>
                  @if(count($fqg) != 0)
                    @foreach($fqg as $f)
                      @if($f['subject_id'] == $sub->id)
                        @if($f['grade'] == 0)
                        N/A
                        @else
                        <strong>{{ \App\Http\Controllers\StudentController::getGrade($f['grade']) }}</strong>
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
                        <strong>{{ \App\Http\Controllers\StudentController::getGrade($s['grade']) }}</strong>
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
                        <strong>{{ \App\Http\Controllers\StudentController::getGrade($t['grade']) }}</strong>
                        @endif
                      @endif
                    @endforeach
                  @endif
                  
                </td>
                <td>
                  
                  @if(count($foqg) != 0)
                    @foreach($foqg as $f)
                      @if($f['subject_id'] == $sub->id)
                        @if($f['grade'] == 0)
                        N/A
                        @else
                        <strong>{{ \App\Http\Controllers\StudentController::getGrade($f['grade']) }}</strong>
                        @endif
                      @endif
                    @endforeach
                  @endif
                </td>
                <td></td>
                <td></td>
              </tr>
              @endforeach
            </tbody>
          </table>
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
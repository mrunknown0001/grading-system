@extends('layouts.app')

@section('title') View Written Work Score - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('student.student-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
            My Written Work Scores on {{ ucwords($subject->grade_level->name) . ' - ' . ucwords($section->name) . ' - ' . ucwords($subject->title) }}
       
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

          
          <table class="table table-hover">
              <thead>
                  <tr>
                      <th>Written Work #</th>
                      <th>Score</th>
                  </tr>
              </thead>
              <tbody>
                @for($x = 1; $x <= $ww_number->number; $x++)
        
                  @foreach($scores as $score)
                  <tr>
                      @if($x == $score->written_work_number)
                      <td>{{ $x }}</td>
                      <td>{{ $score->score }}/{{ $score->total }}</td>

                      @endif
                  </tr>
                  @endforeach

                @endfor
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
@extends('layouts.app')

@section('title') Admin Dashboard - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
        @if(count($school_year) != 0)
        School Year: {{ $school_year->from }}-{{ $school_year->to }}

          @if(count($quarter) != 0)| {{ ucwords($quarter->name) }} Quarter
          @else
          <hr>
          No Selected Quarter. Click <a href="{{ route('select_quarter') }}">here</a>.
          @endif
          
          @if(count($semester) != 0)| {{ ucwords($semester->name) }} Semester
          @else
          <hr>
          No Selected Semester. Click <a href="{{ route('select_semester') }}">here</a>.
          @endif

        @else
        Please Add School Year. Click <a href="{{ route('add_school_year') }}">here</a>.
        @endif
        
       
      </h1>
<!--       <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section> -->

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
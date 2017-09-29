@extends('layouts.app')

@section('title') Add Performance Task - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('teacher.teacher-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Performance Task : {{ ucwords($assign->subject->grade_level->name) }} - {{ ucwords($assign->section->name) }} - {{ ucwords($assign->subject->title) }}
       
      </h1>
 <!--      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol> -->
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

          <form action="#" method="POST" autocomplete="off">
            <div class="form-group">
              <input type="number" name="total" placeholder="Total" />
            </div>
            <div class="form-group">
            <table class="table">
            @foreach($section->students as $std)
              <tr>
              <td>{{ ucwords($std->user->firstname) }} {{ ucwords($std->user->lastname) }}</td>
               <td><input type="text" name="std-score" placeholder="Score" /></td>
              </tr>
            @endforeach
            </table>
            </div>
            <div class="form-group">
              <button class="btn btn-primary">Save</button>
              <a href="{{ route('get_student_class_subject', $assign->id) }}" class="btn btn-danger">Cancel</a>
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
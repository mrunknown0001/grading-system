@extends('layouts.app')

@section('title') Update Subject Assignment - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
        Update Subject Assignment
       
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
        <div class="col-md-6">
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')

          <form action="{{ route('post_update_subject_assignment') }}" method="POST" autocomplete="off">
            <div class="form-group">
              <select name="teacher" id="" class="form-control">
                <option value="">Select Teacher</option>
                @foreach($teachers as $t)
                <option value="{{ $t->id }}">{{ ucwords($t->firstname) }} {{ ucwords($t->lastname) }} - {{ $t->user_id }}</option>
                @endforeach
                @if(count($teachers) == 0) 
                <option value="">No Teachers</option>
                @endif
              </select>
            </div>
            <div class="form-group">
              <input type="text" name="grade_level_subject" value="{{ ucwords($subjectassign->section->grade_level->name . ' - ' . $subjectassign->subject->title) }}" class="form-control" readonly="" />
            </div>
            <div class="form-group">
              <input type="text" name="grade_level_subject" value="{{ ucwords($subjectassign->section->name) }}" class="form-control" readonly="" />
            </div>
            <div class="form-group">
              <input type="hidden" name="id" value="{{ $subjectassign->id }}" />
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <button class="btn btn-primary">Update Subject Assignment</button>
              <a href="{{ route('admin_dashboard') }}" class="btn btn-danger">Cancel</a>
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
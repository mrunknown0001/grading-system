@extends('layouts.app')

@section('title') Update Subject Details - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Update Subject Details
      </h1>
<!--       <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section> -->

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
      <form action="{{ route('post_update_subject_details') }}" method="POST" autocomplete="off">
        <div class="row">
          <div class="col-md-6">
            
            @include('includes.errors')
            @include('includes.error')
            @include('includes.success')
            @include('includes.notice')
            <div class="form-group">
              <!-- <select name="grade_level" class="form-control">
              <option value="">Subject For...</option>
              @foreach($levels as $l)
              <option @if($subject->level == $l->id) selected @endif value="{{ $l->id }}">{{ $l->name }}</option>
              @endforeach
              </select> -->
              <input type="text" name="" class="form-control" value="{{ ucwords($subject->grade_level->name) }}" placeholder="" readonly="" />
              <input type="hidden" name="grade_level" value="{{ $subject->grade_level->id }}">
            </div>
            <div class="form-group">
              <input type="text" name="title" class="form-control text-capitalize" value="{{ $subject->title }}" placeholder="Subject Title" />
            </div>
            <div class="form-group">
              <textarea name="description" id="description" cols="30" rows="10" class="form-control text-capitalize" placeholder="Description of the Subject...">{{ $subject->description }}</textarea>
            </div>
            <div class="form-group">
              Written Work: <input type="number" name="written_work" class="form-control" value="{{ $subject->written_work }}" placeholder="%" />
              Performance Task: <input type="number" name="performance_task" class="form-control" value="{{ $subject->performance_task }}" placeholder="%" />
              Exam: <input type="number" name="exam" class="form-control" value="{{ $subject->exam }}" placeholder="%" />
            </div>
            <div class="form-group">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <input type="hidden" name="id" value="{{ $subject->id }}" />
              <button class="btn btn-primary">Update Subject</button>
              <a href="{{ route('get_view_all_subjects') }}" class="btn btn-danger">Cancel</a>
            </div>

          </div>

          </div>
        </div>
      </form>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @include('includes.footer')
</div>
<!-- ./wrapper -->

</div>
@endsection
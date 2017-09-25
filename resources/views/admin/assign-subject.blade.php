@extends('layouts.app')

@section('title') Assign Subject - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
        Assign Subject on {{ ucwords($level->name) }}
       
      </h1>
 <!--      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
    <hr>
      <!-- Your Page Content Here -->
      <div class="row">
        <div class="col-md-6">
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')

          <form action="{{ route('post_assign_subject_level') }}" method="POST" autocomplete="off">
            <div class="form-group">
              <select name="teacher" id="" class="form-control">
                <option value="">Select Teacher</option>
                @foreach($teachers as $t)
                <option value="{{ $t->id }}">{{ ucwords($t->firstname) }} {{ ucwords($t->lastname) }} - {{ $t->user_id }}</option>
                @endforeach
                @if(empty($teachers)) 
                <option value="">No Teachers</option>
                @endif
              </select>
            </div>
            <div class="form-group">
              <select name="subject" id="" class="form-control">
                <option value="">Select Subject</option>
                @foreach($subjects as $s)
                <option value="{{ $s->id }}">{{ ucwords($s->grade_level->name) }} - {{ ucwords($s->title) }}</option>
                @endforeach
                @if(empty($subjects))
                <option value="">No Subjects</option>
                @endif
              </select>
            </div>
            <div class="form-group">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <button class="btn btn-primary">Assign Subject</button>
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
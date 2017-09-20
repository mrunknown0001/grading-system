@extends('layouts.app')

@section('title') Import Student - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
          Import Student
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
          <div class="col-lg-6 col-md-8">
              {{-- Includes errors and session flash message display container --}}
              @include('includes.errors')
              @include('includes.error')
              @include('includes.success')
              @include('includes.notice')
              <hr>
              <form action="{{ route('post_import_students') }}" method="POST" enctype="multipart/form-data">
                
                <div class="form-group">
                    <select name="grade_section" class="form-control text-capitalize" title="Grade Level & Section">
                      <option value="">Select Grade Level &amp; Section</option>
                      @foreach($sections as $s)
                      <option value="{{ $s->id }}">{{ ucwords($s->grade_level->name) }} - {{ ucwords($s->name) }}</option>
                      @endforeach
                      @if(count($sections) == 0)
                      <option value="">No Section Added</option>
                      @endif
                  </select>
                </div>
                <div class="form-group">

                    <input type="file" name="students" id="students" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" title="Select Excel Files Only" data-toggle="tooltip" data-placement="bottom" />

                </div>
                <div class="form-group">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                  <button class="btn btn-primary">Import Students</button>
                  <a href="{{ route('get_view_all_students') }}" class="btn btn-danger">Cancel</a>
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
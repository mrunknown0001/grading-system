@extends('layouts.app')

@section('title') Add Section - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Section
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
          <div class="col-lg-8 col-md-12">
            {{-- Includes errors and session flash message display container --}}
            @include('includes.errors')
            @include('includes.error')
            @include('includes.success')
            @include('includes.notice')
            <form action="{{ route('post_add_section') }}" method="POST" autocomplete="off">
              <div class="form-group">
                <!-- <input type="text" name="title" class="form-control text-capitalize" placeholder="Grade Level Title" /> -->
                <select name="grade_level" class="form-control">
                  <option value="">Select Grade Level</option>
                  @foreach($levels as $l)
                  <option value="{{ $l->id }}">{{ ucwords($l->name) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <input type="text" name="name" class="form-control text-capitalize" placeholder="Section Name" />
              </div>
              <div class="form-group">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button class="btn btn-primary">Add Section</button>
                <a href="{{ route('get_all_sections') }}" class="btn btn-danger">Cancel</a>
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
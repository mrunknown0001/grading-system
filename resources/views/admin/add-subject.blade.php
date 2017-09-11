@extends('layouts.app')

@section('title') Add Subject - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Subject
      </h1>
<!--       <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section> -->

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
      <form action="#" method="POST" autocomplete="off">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <select name="level" class="form-control">
              <option value="">Subject For...</option>
              @foreach($levels as $l)
              <option value="{{ $l->id }}">{{ $l->name }}</option>
              @endforeach
              </select>
            </div>
            <div class="form-group">
              <input type="text" name="title" class="form-control text-capitalize" placeholder="Subject Title" />
            </div>
            <div class="form-group">
              <textarea name="description" id="description" cols="30" rows="10" class="form-control text-capitalize" placeholder="Description of the Subject..."></textarea>
            </div>
            <div class="form-group">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <button class="btn btn-primary">Add Subject</button>
              <a href="#" class="btn btn-danger">Cancel</a>
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
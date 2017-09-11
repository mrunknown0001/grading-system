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
<option value="Grade7">Grade 7</option>
<option value="Grade8">Grade 8</option>
<option value="Grade9">Grade 9</option>
<option value="Grade10">Grade 10</option>
<option value="Grade11">Grade 11</option>
<option value="Grade12">Grade 12</option>
</select>
</div>
<!-- <div class="form-group">
<input type="text" name="code" class="form-control text-uppercase" placeholder="Subject Code" />
</div> -->
<div class="form-group">
<input type="text" name="title" class="form-control text-capitalize" placeholder="Subject Title" />
</div>
<div class="form-group">
<textarea name="description" id="description" cols="30" rows="10" class="form-control text-capitalize" placeholder="Description of the Subject..."></textarea>
</div>
<div class="form-group">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
<button class="btn btn-primary">Add Subject</button>
<a href="{{ route('subjects_view') }}" class="btn btn-danger">Cancel</a>
</div>

</div>

</div>
</form>
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
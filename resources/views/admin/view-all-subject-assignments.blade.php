@extends('layouts.app')

@section('title') View All Subject Assignments - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
        View All Subject Assignments       
       
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
        <div class="col-md-12">
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')

          <table class="table table-hover">
            <thead>
              <tr>
                <th>Grade Level &amp; Section</th>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($assignments as $as)
              <tr>
                <td>{{ $as->subject->grade_level->name }} - {{ ucwords($as->section->name) }}</td>
                <td>{{ ucwords($as->subject->title) }}</td>
                <td>{{ ucwords($as->teacher->firstname) }} {{ ucwords($as->teacher->lastname) }}</td>
                <td><a href="{{ route('update_subject_assigment', ['id' => $as->id]) }}" class="btn btn-primary btn-xs">Change</a></td>
              </tr>
              @endforeach
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
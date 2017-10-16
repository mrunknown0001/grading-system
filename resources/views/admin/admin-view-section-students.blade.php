@extends('layouts.app')

@section('title') Students on {{ ucwords($section->grade_level->name . ' - ' . $section->name) }} - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
        {{ count($students) }} Students on <a href="{{ route('view_sections_grade_level', ['id' => $section->grade_level->id]) }}">{{ ucwords($section->grade_level->name) }}</a> - {{ ucwords($section->name) }}
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
        <div class="col-md-12">
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')
          
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Student Number</th>
                <th>Name</th>
                <td>Rank</td>
                <th>Average</th>
              </tr>
            </thead>
            <tbody>
              @foreach($students as $std)
              <tr>
                <td>{{ $std->user_id }}</td>
                <td>{{ ucwords($std->user->firstname . ' ' . $std->user->lastname) }}</td>
                <td></td>
                <td></td>
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
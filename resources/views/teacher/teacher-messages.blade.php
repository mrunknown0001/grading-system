@extends('layouts.app')

@section('title') Teacher Messages - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('teacher.teacher-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
            Teacher Messages 
       
      </h1>
<!--       <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>-->
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
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Name</th>
                <th>Student Number</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($student_messages as $msg)
              <tr>
                <td>
                  {{ ucwords($msg->user->firstname . ' ' . $msg->user->lastname) }}
                  <span class="label label-danger">@if($msg->where('status', 0)->count() != 0){{ $msg->where('status', 0)->count() }} @endif</span>
                </td>
                <td>{{ $msg->user->user_id }}</td>
                <td><a href="{{ route('teacher_student_message_thread', ['student_id' => $msg->user->user_id]) }}">Open</a></td>
              </tr>
              @endforeach       
            </tbody>
          </table>
          {{--
          <!-- Count and Total count() of total() -->
          <p class="text-center"><strong>{{ $student_messages->count() + $student_messages->perPage() * ($student_messages->currentPage() - 1) }} of {{ $student_messages->total() }}</strong></p>

          <!-- Page Number render() -->
          <div class="text-center"> {{ $student_messages->links() }}</div>
          --}}
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
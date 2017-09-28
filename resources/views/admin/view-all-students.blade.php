@extends('layouts.app')

@section('title') View All Students - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
        View All Students
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
                      <th>ID Number</th>
                      <th>Last Name</th>
                      <th>First Name</th>
                      <th>Actions</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($students as $s)
                      <tr>
                          <td>{{ $s->user_id }}</td>
                          <td class="text-capitalize">{{ $s->lastname }}</td>
                          <td class="text-capitalize">{{ $s->firstname }}</td>
                          <td>
                              <div class="btn-group btn-group-xs">
                              <button class="btn btn-info" data-toggle="modal" data-target="#{{ $s->id }}-view"><i class="fa fa-eye" aria-hidden="true"></i></button>
                              <a href="{{ route('get_update_student_details', $s->id) }}" class="btn btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                              </div>

                          </td>
                      </tr>
                      @include('admin.includes.modal-student-view-details')
                  @endforeach
              </tbody>
          </table>
          <!-- Count and Total count() of total() -->
          <p class="text-center"><strong>{{ $students->count() + $students->perPage() * ($students->currentPage() - 1) }} of {{ $students->total() }}</strong></p>

          <!-- Page Number render() -->
          <div class="text-center"> {{ $students->links() }}</div>
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
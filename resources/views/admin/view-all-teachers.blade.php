@extends('layouts.app')

@section('title') View All Teachers - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View All Teachers
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
        <div class="col-lg-12 col-md-12">
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          <table class="table table-hover">
            <thead>
              <tr>
                <th>ID Number</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Actions</th>
              </tr>
            </thead>
          <tbody>
            @foreach($teachers as $t)
              <tr>
                <td>{{ $t->user_id }}</td>
                <td class="text-capitalize">{{ $t->firstname }}  {{ $t->lastname }}</td>
                <td>{{ $t->email }}</td>
                <td>
                  <div class="btn-group btn-group-xs">
                  <button class="btn btn-success" data-toggle="modal" data-target="#"><i class="fa fa-eye" aria-hidden="true"></i></button>

                  <a href="#" class="btn btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                  <button class="btn btn-danger" data-toggle="modal" data-target="#"><i class="fa fa-times" aria-hidden="true"></i></button>
                  </div>
                </td>
            </tr>
            @endforeach
            </tbody>
          </table>
          <!-- Count and Total count() of total() -->
          <p class="text-center"><strong>{{ $teachers->count() + $teachers->perPage() * ($teachers->currentPage() - 1) }} of {{ $teachers->total() }}</strong></p>

          <!-- Page Number render() -->
          <div class="text-center"> {{ $teachers->links() }}</div>
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

    <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      <!-- Footer Message -->
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy;  {{ date('Y') }}.</strong>
  </footer>
</div>
<!-- ./wrapper -->

</div>
@endsection
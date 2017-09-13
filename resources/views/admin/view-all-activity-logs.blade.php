@extends('layouts.app')

@section('title') Activity Logs - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Activity Logs
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
            <table class="table table-hover">
                  <thead>
                      <tr>
                          <th>Username/ID Number</th>
                          <th>Date &amp; Time</th>
                          <th>Action Made</th>
                      </tr>
                  </thead>
                  <tbody>
                  @foreach($logs as $l)
                  <tr>
                    <td>{{ $l->user->user_id }}</td>
                    <td>{{ date('F j, Y - g:i:s A l', strtotime($l->created_at) + 28800) }}</td>
                    <td>{{ $l->action }}</td>
                  </tr>
                  @endforeach

                  </tbody>
              </table>
          <!-- Count and Total count() of total() -->
          <p class="text-center"><strong>{{ $logs->count() + $logs->perPage() * ($logs->currentPage() - 1) }} of {{ $logs->total() }}</strong></p>

          <!-- Page Number render() -->
          <div class="text-center"> {{ $logs->links() }}</div>
                
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
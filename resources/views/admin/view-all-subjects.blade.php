@extends('layouts.app')

@section('title') View All Subjects - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View All Subjects
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
                  <th>Grade Level</th>
                  <th>Subject Title</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
<!-- // starto of foreach -->
                <tr>
                            <td class="text-capitalize">Grade 7</td>
                  <td class="text-capitalize">Math</td>
                  <td>
                <div class="btn-group btn-group-xs">
                  <button class="btn btn-success" data-toggle="modal" data-target="#-view"><i class="fa fa-eye" aria-hidden="true"></i></button>
                  <a href="#" class="btn btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                  <button class="btn btn-danger" data-toggle="modal" data-target="#-remove"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                  </td>
                </tr>
<!-- end of foreach -->
              </tbody>
            </table>
            <!-- Count and Total count() of total() -->
                <p class="text-center"><strong></strong></p>

                <!-- Page Number render() -->
                <div class="text-center"></div>
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
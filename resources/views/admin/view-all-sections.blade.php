@extends('layouts.app')

@section('title') View All Sections - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View All Sections
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
                  <th>Section Name</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              @foreach($sections as $sec)
                <tr>
                  <td class="text-capitalize">{{ $sec->grade_level->name }}</td>
                  <td class="text-capitalize">{{ $sec->name }}</td>
                  <td>
                <div class="btn-group btn-group-xs">
                  <!-- <button class="btn btn-info" data-toggle="modal" data-target="#-view"><i class="fa fa-eye" aria-hidden="true"></i></button> -->
                  <a href="{{ route('get_update_section_details', $sec->id) }}" class="btn btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                  <button class="btn btn-danger" data-toggle="modal" data-target="#{{ $sec->id }}-remove"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                  </td>
                </tr>
                
                @include('admin.includes.modal-section-remove-confirm')
              @endforeach
              </tbody>
            </table>
          <!-- Count and Total count() of total() -->
          <p class="text-center"><strong>{{ $sections->count() + $sections->perPage() * ($sections->currentPage() - 1) }} of {{ $sections->total() }}</strong></p>

          <!-- Page Number render() -->
          <div class="text-center"> {{ $sections->links() }}</div>
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
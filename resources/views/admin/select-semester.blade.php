@extends('layouts.app')

@section('title') Select Semester - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('admin.admin-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
        Select Semester
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
          <div class="row">
              @foreach($semester as $sem)
                <div class="col-md-3">
                  @if($sem->status == 1)
                  <div class="panel panel-success">
                  @else
                    @if($sem->finish == 1)
                    <div class="panel panel-primary">
                    @else
                    <div class="panel panel-warning">
                    @endif
                  @endif
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-12">
                                <div>
                                    @if($sem->name == 'first')
                                        First Semester
                                    @elseif($sem->name == 'second')
                                        Second Semester
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                      @if($sem->status == 1)
                        Selected
                      @else
                        @if($sem->finish == 1)
                          Finished
                        @else
                          Unselected
                        @endif
                      @endif
                    </div>

                </div>
              </div>
              @endforeach
          </div>



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
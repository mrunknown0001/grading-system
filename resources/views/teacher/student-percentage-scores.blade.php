@extends('layouts.app')

@section('title') Student Percentage - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('teacher.teacher-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
            Student Percentage 
       
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
        <div class="col-md-10">
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')


          <table class="table tabl-hover">
            <thead>
              <tr>
                <th style="cursor: pointer;">Name</th>
                <th>Written Work Score Percentage</th>
                <th>Performance Task Score Percentage</th>
                <th>Exam Score Percentage</th>
                <th>Grade</th>
              </tr>
            </thead>
            <tbody>
              @foreach($section->students as $std)
                @foreach($pg as $p)
                @if($std->user_id == $p['student_id'])
                <tr>
                  <td>{{ ucwords($std->user->lastname . ', ' . $std->user->firstname) }}</td>
                  <td>{{ $p['ww_percentage'] }}</td>
                  <td>{{ $p['pt_percentage'] }}</td>
                  <td>{{ $p['exam_percentage'] }}</td>
                  <td>{{ \App\Http\Controllers\StudentController::getGrade($p['grade']) }}</td>
                </tr>
                @endif
                @endforeach
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
<script type="text/javascript">
  function sortTable(table, col, reverse) {
    var tb = table.tBodies[0], // use `<tbody>` to ignore `<thead>` and `<tfoot>` rows
        tr = Array.prototype.slice.call(tb.rows, 0), // put rows into array
        i;
    reverse = -((+reverse) || -1);
    
    tr = tr.sort(function (a, b) { // sort rows
        
        
        if(!isNaN(a.cells[col].textContent) && !isNaN(b.cells[col].textContent))
        return reverse * ((+a.cells[col].textContent) - (+b.cells[col].textContent))
       return reverse // `-1 *` if want opposite order
            * (a.cells[col].textContent.trim() // using `.textContent.trim()` for test
                .localeCompare(b.cells[col].textContent.trim())
               );
    });
    for(i = 0; i < tr.length; ++i) tb.appendChild(tr[i]); // append each row in order
}

function makeSortable(table) {
    var th = table.tHead, i;
    th && (th = th.rows[0]) && (th = th.cells);
    if (th) i = th.length;
    else return; // if no `<thead>` then do nothing
    while (--i >= 0) (function (i) {
        var dir = 1;
        th[i].addEventListener('click', function () {sortTable(table, i, (dir = 1 - dir))});
    }(i));
}

function makeAllSortable(parent) {
    parent = parent || document.body;
    var t = parent.getElementsByTagName('table'), i = t.length;
    while (--i >= 0) makeSortable(t[i]);
}

window.onload = function () {
  makeAllSortable();
  makeSortable();

};
</script>
@endsection
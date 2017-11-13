@extends('layouts.app')

@section('title') Add Exam - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('teacher.teacher-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Exam : {{ ucwords($assign->subject->grade_level->name) }} - {{ ucwords($assign->section->name) }} - {{ ucwords($assign->subject->title) }}
       
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
        <div class="col-md-8">
          {{-- Includes errors and session flash message display container --}}
          @include('includes.errors')
          @include('includes.error')
          @include('includes.success')
          @include('includes.notice')

          <form action="{{ route('post_add_exam_score') }}" method="POST" autocomplete="off">
            <div class="form-group">
              <input type="number" name="total" max=999 onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Total" />
            </div>
            <div class="form-group">
            <table class="table" id="studentsTable">
              <thead>
                <tr>
                  <th onclick="sortTable(0);">Name</th>
                  <th>Score</th>
                </tr>
              </thead>
              <tbody>
            @foreach($section->students as $std)
              <tr>
              <td>{{ ucwords($std->user->lastname) }} {{ ucwords($std->user->firstname) }}</td>
               <td><input type="number" name="{{ $std->user->user_id }}" value=0 onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Score" /></td>
              </tr>
            @endforeach
            </tbody>
            </table>
            </div>
            <div class="form-group">
              <input type="hidden" name="assign_id" value="{{ $assign->id }}" />
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <button class="btn btn-primary">Save</button>
              <a href="{{ route('get_student_class_subject', $assign->id) }}" class="btn btn-danger">Cancel</a>
            </div>
          </form>


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
<script>
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("studentsTable");
  switching = true;
  // Set the sorting direction to ascending:
  dir = "asc"; 
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("TR");
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /* Check if the two rows should switch place,
      based on the direction, asc or desc: */
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      // Each time a switch is done, increase this count by 1:
      switchcount ++; 
    } else {
      /* If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again. */
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}

window.onload = function() {
  sortTable(0);
};
</script>
@endsection
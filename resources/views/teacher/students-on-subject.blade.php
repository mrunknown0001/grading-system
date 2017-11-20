@extends('layouts.app')

@section('title') Students on Subject - Student Grading System @endsection

@section('content')
<div class="wrapper">
@include('teacher.teacher-menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -  -->
            Students on {{ $assign->section->grade_level->name }} - {{ ucwords($assign->section->name) }} - {{ ucwords($assign->subject->title) }}
       
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
          <div>
            <!-- <button class="btn btn-primary btn-xs">Add Written Work</button> -->
            <a href="{{ url('teacher/add/written-work/section/' . $assign->section->id . '/subject/' . $assign->subject->id) . '/' . $assign->id . '/get' }}" class="btn btn-primary btn-xs">Add Written Work</a>
            <a href="{{ url('teacher/add/performance-task/section/' . $assign->section->id . '/subject/' . $assign->subject->id) . '/' . $assign->id . '/get' }}" class="btn btn-primary btn-xs">Add Performance Task</a>
            <a href="{{ url('teacher/add/exam/section/' . $assign->section->id . '/subject/' . $assign->subject->id) . '/' . $assign->id . '/get' }}" class="btn btn-primary btn-xs">Add Exam</a>
            |
            <a href="{{ route('view_written_work_score', ['section' => $assign->section->id, 'subject' => $assign->subject->id, 'assign' => $assign->id] )}}" class="btn btn-success btn-xs">View Written Works Scores</a>
            <a href="{{ route('view_performance_task_score', ['section' => $assign->section->id, 'subject' => $assign->subject->id, 'assign' => $assign->id] )}}" class="btn btn-success btn-xs">View Performance Task Scores</a>
            <a href="{{ route('view_exam_score', ['section' => $assign->section->id, 'subject' => $assign->subject->id, 'assign' => $assign->id] )}}" class="btn btn-success btn-xs">View Exam Scores</a>
            
            <a href="{{ route('teacher_view_percentange_scores', ['section' => $assign->section->id, 'subject' => $assign->subject->id, 'assign' => $assign->id]) }}" class="btn btn-success btn-xs">Percentage Scores</a>

            <a href="{{ route('teacher_view_subject_grades', ['section' => $assign->section->id, 'subject' => $assign->subject->id, 'assign' => $assign->id]) }}" class="btn btn-success btn-xs">View Grades</a>
          </div>
          <table class="table table-hover" id="studentsTable">
            <thead>
              <tr>
                <th onclick="sortTable(4)" style="cursor: pointer;">Student Number</th>
                <th onclick="sortTable(0)" style="cursor: pointer;">Last Name</th>
                <th onclick="sortTable(1)" style="cursor: pointer;">First Name</th>
              </tr>
            </thead>
            <tbody>
              @foreach($all_students as $astd)
              <tr>
                <td>{{ $astd->user->user_id }}</td>
                <td>{{ ucwords($astd->user->lastname) }}</td>
                <td>{{ ucwords($astd->user->firstname) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <p class="text-center"><strong>{{ $all_students->count() }} students</strong></p>

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
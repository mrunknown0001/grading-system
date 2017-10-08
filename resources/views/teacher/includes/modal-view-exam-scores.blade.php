<div class="modal fade modal-default" id="score-{{ $x }}" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Exam # {{ $x }}</h4>
            </div>
            <div class="modal-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scores as $score)
                        <tr>
                            @if($x == $score->exam_number)
                            <td>{{ ucwords($score->student->user->firstname . ' ' . $score->student->user->lastname)  }}</td>
                            <td>{{ $score->score }}</td>

                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">

            </div>
        </div>

    </div>
</div>

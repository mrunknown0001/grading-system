<div class="modal fade modal-info" id="{{ $s->id }}-view" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-header-success">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Subject Details</h4>
            </div>
            <div class="modal-body">
                <p>Grade Level: <span class="text-capitalize">{{ $s->grade_level->name }}</span></p>
                <p>Subject Title: <span class="text-capitalize">{{ $s->title }}</span></p>
                <p class="text-capitalize">Description: <i>{{ $s->description }}</i></p>
            
                <p><strong>Percentage:</strong></p>
                <p>Writter Works: {{ $s->written_work }}%</p>
                <p>Performance Tasks: {{ $s->performance_task }}%</p>
                <p>Exams: {{ $s->exam }}%</p>
                <p>Others: {{ $s->others }}%</p>
                
            </div>
            <div class="modal-footer">
            
            </div>
        </div>

    </div>
</div>

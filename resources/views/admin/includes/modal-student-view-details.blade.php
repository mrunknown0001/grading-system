<div class="modal fade modal-info" id="{{ $s->id }}-view" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-header-success">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Student Details</h4>
            </div>
            <div class="modal-body">
                <strong>
                <p class="text-capitalize">Grade &amp; Section: 
                    @if($s->section != null)
                    {{-- $s->info->section1->grade_level->name }} - {{ $s->info->section1->name --}}
                    {{ \App\Http\Controllers\AdminController::getSectionInfo($s->section) }}
                    @else
                    Not Available
                    @endif
                </p>
                <p>Student Number: {{ $s->user_id }}</p>
                <p class="text-capitalize">Name: {{ $s->firstname }} {{ $s->lastname }}</p>
                <p>Birthday: {{ date('F j, Y', strtotime($s->birthday)) }}</p>
                <p>Gender: {{ $s->gender }}</p>
                <p class="text-capitalize">Address: {{ $s->address }}</p>
                <p>Email: {{ $s->email }}</p>
                <p>Mobile: {{ $s->mobile }}</p>
                </strong>
            </div>
            <div class="modal-footer">
                
            </div>
        </div>

    </div>
</div>

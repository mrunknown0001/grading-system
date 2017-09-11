<div class="modal modal-danger fade" id="{{ $t->user_id }}-delete" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Teacher Removal Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove <strong class="text-capitalize">{{ $t->firstname }} {{ $t->lastname }}</strong> with ID Number: <strong>{{ $t->user_id }}</strong>?</p>
               
            </div>
            <div class="modal-footer">
                 {{-- Form Deletion --}}
                <form action="{{ route('get_remove_teacher', $t->id) }}" method="GET">
                    <button type="submit" class="btn btn-danger">Remove</button>
                </form>
            </div>
        </div>

    </div>
</div>

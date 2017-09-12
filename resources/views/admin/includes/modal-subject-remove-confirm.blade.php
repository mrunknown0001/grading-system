<div class="modal fade modal-danger" id="{{ $s->id }}-remove" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Subject Removal Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove the subject with title: <strong class="text-uppercase">{{ $s->title }}</strong>?</p>
               
            </div>
            <div class="modal-footer">
                 {{-- Form Deletion --}}
                <form action="{{ route('get_remove_subject', $s->id ) }}" method="GET">
                    <button type="submit" class="btn btn-danger">Remove</button>
                </form>
            </div>
        </div>

    </div>
</div>

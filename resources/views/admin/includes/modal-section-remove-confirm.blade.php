<div class="modal fade modal-danger" id="{{ $sec->id }}-remove" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Section Confirmation Removal</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this section: <strong class="text-capitalize">{{ $sec->grade_level->name }} - {{ $sec->name }}</strong>?</p>
            </div>
            <div class="modal-footer">
                 {{-- Form Deletion --}}
                <form action="{{ route('get_remove_section', $sec->id ) }}" method="GET">
                    <button type="submit" class="btn btn-danger">Remove</button>
                </form>
            </div>
        </div>

    </div>
</div>

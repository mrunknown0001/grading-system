<div class="modal fade modal-danger" id="{{ $s->id }}-remove" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Drop Student From System</h4>
            </div>
            <div class="modal-body">
                <p>Drop Student: <strong class="text-uppercase">{{ ucwords($s->firstname . ' ' . $s->lastname) }} ({{$s->user_id}})</strong>?</p>
               
            </div>
            <div class="modal-footer">
                 {{-- Form Deletion --}}
                <form action="{{ route('post_admin_remove_student') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" value="{{ $s->id }}" />
                    <button type="submit" class="btn btn-danger">Drop Student</button>
                </form>
            </div>
        </div>

    </div>
</div>

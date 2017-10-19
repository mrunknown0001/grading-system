<div class="modal fade modal-default" id="start-school-year" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Stat Next School Year</h4>
            </div>
            <div class="modal-body">
                <p>Start Next School Year.</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('admin_start_school_year') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <button class="btn btn-success">Start Next School Year</button>
                </form>
            </div>
        </div>

    </div>
</div>

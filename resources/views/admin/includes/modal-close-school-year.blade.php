<div class="modal fade modal-default" id="close-school-year" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-header-success">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Close School Year</h4>
            </div>
            <div class="modal-body">
                <p>Close School Year.</p>
                <h2><i>Make you all records are finalized. It can't be undone.</i></h2>
            </div>
            <div class="modal-footer">
                <form action="{{ route('post_admin_close_school_year') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <button class="btn btn-warning">Close School Year</button>
                </form>
            </div>
        </div>

    </div>
</div>

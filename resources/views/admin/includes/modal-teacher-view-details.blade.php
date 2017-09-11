<div class="modal modal-info fade" id="{{ $t->user_id }}-view" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-header-green">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Teacher's Details</h4>
            </div>
            <div class="modal-body">
                <strong>
                <p>ID Number: {{ $t->user_id }}</p>
                <p class="text-capitalize">Name: {{ $t->firstname }} {{ $t->lastname }}</p>
                <p>Birthday: {{ date('F j, Y', strtotime($t->birthday)) }}</p>
                <p>Gender: {{ $t->gender }}</p>
                <p class="text-capitalize">Address: {{ $t->address }}</p>
                <p>Email: {{ $t->email }}</p>
                <p>Mobile: {{ $t->mobile }}</p>
                </strong>
            </div>
            <div class="modal-footer">
            
            </div>
        </div>

    </div>
</div>

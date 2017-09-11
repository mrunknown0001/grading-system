@if(session('notice'))
	<div class="alert alert-warning text-center fade in top-space">
		<a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<b>{{ session('notice') }}</b>
	</div>
@endif
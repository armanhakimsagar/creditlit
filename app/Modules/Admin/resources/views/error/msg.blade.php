@if(Session::has('message'))

<div id="" class="alert alert-success">
	<span class="close" data-dismiss="alert">×</span>
	{{Session::get('message')}} 
</div>
@endif


@if(Session::has('error'))

<div class="alert alert-warning">
	<span class="close" data-dismiss="alert">×</span>
	{{Session::get('error')}} 
</div>

@endif

@if(Session::has('info'))

<div class="alert alert-warning">
	<span class="close" data-dismiss="alert">×</span>
	{{Session::get('info')}} 
</div>
@endif

@if(Session::has('danger'))
<div class="alert alert-danger">
	<span class="close" data-dismiss="alert">×</span>
	{{Session::get('danger')}} 
</div>
@endif

@if (count($errors) > 0)

<div class="alert alert-danger">
	<span class="close" data-dismiss="alert">×</span>
	<ul>
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul> 
</div>

@endif
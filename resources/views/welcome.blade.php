@extends('layouts.app')

@section('content')
<div class="col-sm-12 row justify-content-center align-items-center my-1 mx-0 rounded border border-success">
	<div class="card">
		<div class="card-header d-flex justify-content-between">
			<h3 class="my-auto shadow-lg">Home </h3>
		</div>
		<div class="card-body">
			<h1 class="text-center shadow">{{ config('app.name', 'Laravel') }}</h1>
		</div>
	</div>

</div>
@endsection

@section('js')
@endsection

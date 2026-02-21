@extends('layouts.app')

@section('content')
<div class="col-sm-12 row justify-content-center align-items-center mx-auto">
	<div class="card">
		<div class="card-header d-flex justify-content-between">
			<h3 class="my-auto">File Entries</h3>
			<a href="{{ route('importcsvs.create', ['id' => session()->get('lastBatchId') ?? NULL]) }}" class="my-auto btn btn-sm btn-outline-primary">Import CSV</a>
		</div>
		<div class="card-body">
			<table class="table table-hover" id="at"></table>
		</div>
		<div class="card-footer"></div>
	</div>
</div>
@endsection

@section('js')
window.data = {
	route: {
		getFileEntries: '{{ route('getFileEntries') }}',
	},
	url: {
		importcsvs: '{{ url('importcsvs') }}',
		file_entries: '{{ url('file_entries') }}',
	},
	old: {
	},
	errors: @json($errors->toArray()),
};
@endsection

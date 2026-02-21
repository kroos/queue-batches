@extends('layouts.app')

@section('content')
<div class="col-sm-12">

	<div class="col-sm-12 row justify-content-center align-items-center my-2">
			<div class="card">
				<div class="card-header d-flex justify-content-between">
					<h3 class="my-auto">Processing Progress</h3>
					<a href="{{ url('/dashboard') }}" class="btn btn-sm btn-outline-primary my-auto">Dashboard</a>
				</div>
				<div class="card-body">
					<div id="processcsv" class="row col-sm-12">
						<div class="progress col-sm-12" role="progressbar" aria-label="CSV Processing" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
							<div class="rounded-5 progress-bar progress-bar-striped progress-bar-animated fw-bolder text-white csvprogress" style="width: 0%">0%</div>
						</div>
						<div id="processedJobs" class="col-sm-12 text-center">
						</div>
						<p>&nbsp;</p>
						<div class="table-responsive">
							<table id="jb" class="table table-hover table-sm"></table>
						</div>
					</div>

				</div>
				<div class="card-footer">
				</div>
			</div>
		</form>
	</div>
</div>
@endsection

@section('js')
window.data = {
	route: {
		getJobBatchTable: '{{ route('getJobBatchTable') }}',
		getProgress: '{{ route('getProgress') }}',
		downloadCSV: '{{ route('progress.downloadCSV') }}',
	},
	url: {
	},
	old: {
	},
	errors: @json($errors->toArray()),
	requestid: @json(request()->id),
	sessionlastBatchId: @json(session()->get('lastBatchId')),
};

@endsection

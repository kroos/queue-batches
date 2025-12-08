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
							<div class="progress-bar progress-bar-striped progress-bar-animated fw-bolder text-white csvprogress" style="width: 0%">0% CSV Processing</div>
						</div>
						<div id="csvuploadStatus" class="text-center"></div>
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
///////////////////////////////////////////////////////////////////////////////////////////
// Start polling
@if(isset(request()->id) || session()->exists('lastBatchId'))
	const progressInterval = setInterval(checkProgress, 50);
	function checkProgress() {
		$.ajax({
			url: '{{ route('getProgress') }}',
			data: {
				_token: '{{ csrf_token() }}',
				id: '{{ request()->id ?? session()->get('lastBatchId') }}'
			},
			type: "GET",
			dataType: 'json',
			success: function(response) {
				const percent = Number(response);
				// Safety check
				if (isNaN(percent)) return;
				// Update progress bar
				$('.progress').attr('aria-valuenow', percent).css('width', percent + '%');
				$(".csvprogress").css('width', percent + '%').text(percent + '%');
				$("#csvuploadStatus").html('<i class="fa-solid fa-spinner fa-spin-pulse fa-beat-fade"></i> Please wait..');
				console.log("Progress:", percent);
				// Done?
				if (percent >= 100) {
					clearInterval(progressInterval);
					// reload DataTable without resetting paging
					if (typeof table !== 'undefined') {
						table.ajax.reload(null, false);
					}
					// redirect to download file
					// clear session batch id (the backend should handle this)
					<?php //session()->forget(['lastBatchId']); ?>
					window.location.href = '{{ route('progress.downloadCSV') }}';
				}
			},
			error: function(jqXHR, textStatus) {
				console.warn("Progress error:", textStatus);
			}
		});
	}
@endif

///////////////////////////////////////////////////////////////////////////////////////////
var table = $('#jb').DataTable({
	// columnDefs: [
	// 	{ type: 'date', 'targets': [4,5,6] },
	// 	// { type: 'time', 'targets': [6] },
	// ],
	order: [],
	responsive: true,
	autoWidth: true,
	fixedHeader: true,
	dom: 'Bfrtip',
	ajax: {
		type: 'GET',
		url: '{{ route('getJobBatchTable') }}',
		dataSrc: '',
		data: function(da){
			da._token = '{!! csrf_token() !!}'
		},
	},
	columns: [
		{ data: 'name', title: 'Name', defaultContent: '-', orderable: false, searchable:false },
		{ data: 'pending', title: 'Pending', defaultContent: '-', orderable: false, searchable:false },
		{ data: 'success', title: 'Success', defaultContent: '-', orderable: false, searchable:false },
		{ data: 'failed', title: 'Failed', defaultContent: '-', orderable: false, searchable:false },
	],
	initComplete: function(settings, response) {
		console.log(response); // This runs after successful loading
	}
});

///////////////////////////////////////////////////////////////////////////////////////////
@endsection

@extends('layouts.app')

@section('content')
<div class="col-sm-12 row justify-content-center align-items-center mx-auto">
	<div class="card">
		<div class="card-header d-flex justify-content-between">
			<h3 class="my-auto">File Entries</h3>
			<a href="{{ route('exportcsvs.create', ['id' => session()->get('lastBatchId') ?? NULL]) }}" class="my-auto btn btn-sm btn-outline-primary">Export CSV</a>
		</div>
		<div class="card-body">
			<table class="table table-hover" id="at"></table>
		</div>
		<div class="card-footer"></div>
	</div>
</div>
@endsection

@section('js')
///////////////////////////////////////////////////////////////////////////////////////////
var table = $('#at').DataTable({
	// columnDefs: [
	// 	{ type: 'date', 'targets': [4,5,6] },
	// 	// { type: 'time', 'targets': [6] },
	// ],
	order: [[0, 'desc'], [1, 'asc']],
	responsive: true,
	autoWidth: true,
	fixedHeader: true,
	dom: 'Bfrtip',
	ajax: {
		type: 'GET',
		url: '{{ route('getFileEntries') }}',
		dataSrc: '',
		data: function(da){
			da._token = '{!! csrf_token() !!}'
		},
	},
	columns: [
		{ data: 'id', title: 'ID' },
		{ data: 'belongstofile.file_original', title: 'File' },
		{ data: 'Year', title: 'Year', defaultContent: '-' },
		{ data: 'Industry_aggregation_NZSIOC', title: 'Ind. Aggregation', defaultContent: '-' },
		{ data: 'Industry_code_NZSIOC', title: 'Ind. Code', defaultContent: '-' },
		{ data: 'Industry_name_NZSIOC', title: 'Ind. Name', defaultContent: '-' },
		{ data: 'Units', title: 'Units', defaultContent: '-' },
		{ data: 'Variable_code', title: 'Var Code', defaultContent: '-' },
		{ data: 'Variable_name', title: 'Var Name', defaultContent: '-' },
		{ data: 'Variable_category', title: 'Var Category', defaultContent: '-' },
		{ data: 'Value', title: 'Value', defaultContent: '-' },
		{ data: 'Industry_code_ANZSIC06', title: 'Ind Code', defaultContent: '-' },
		{
			data: 'id',
			title: '#',
			orderable: false,
			searchable:false,
			render: function(id){
				return `
					<div class="btn-group btn-group-sm" role="group">
						<a href="{{ url('exportcsvs') }}/${id}" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
						<a href="{{ url('exportcsvs') }}/${id}/edit" class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i></a>
						<button type="button" class="btn btn-sm btn-outline-danger remove" data-id="${id}">
							<i class="fa fa-trash"></i>
						</button>
					</div>
				`
			}
		}
	],
	initComplete: function(settings, response) {
		console.log(response); // This runs after successful loading
	}
});

///////////////////////////////////////////////////////////////////////////////////////////
$(document).on('click', '.remove', function(e){
	const id = $(this).data('id');
	swal.fire({
		title: 'Delete Record?',
		text: 'This will delete record.',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, delete it'
	}).then(res=>{
		if(res.isConfirmed){
			$.ajax({
				url: '{{ url("file_entries") }}/'+id,
				type: 'DELETE',
				data: {_token:'{{ csrf_token() }}'},
				success: (response)=> {
					table.ajax.reload(null, false);
					swal.fire('Success!', response.message, response.status);
					// false = keep current page, true = reset to first page
				}
			});
		}
	});
});

///////////////////////////////////////////////////////////////////////////////////////////
@endsection

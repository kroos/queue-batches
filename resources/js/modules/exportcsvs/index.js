const { route, url, old, errors } = window.data;
function getError(name) {
	return errors[name] ? errors[name][0] : null;
}

var table = $('#at').DataTable({
	lengthMenu: [ [500, 1000, -1], [500, 1000, 'All'] ],
	order: [[ 0, 'desc' ], [1, 'desc']],
	responsive: true,
	autoWidth: true,
	fixedHeader: true,
	processing: true,
	serverSide: true,
	// dom: 'Bfrtip',

	ajax: {
		type: 'GET',
		url: route.getFileEntries,
		// dataSrc: '',
		data: function(da){
		},
	},
	columns: [
		{ data: 'id', title: 'ID' },
		// { data: 'belongstofile.file_original', title: 'File' },
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
						<a href="{{ url('importcsvs') }}/${id}" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
						<a href="${url.importcsvs}/${id}/edit" class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i></a>
						<button type="button" class="btn btn-sm btn-outline-danger remove" data-id="${id}">
							<i class="fa fa-trash"></i>
						</button>
					</div>
				`
			}
		}
	],
	initComplete: function(settings, response) {
		console.log(settings, response); // This runs after successful loading
	}
});

$(document).on('click', '.remove', function(e){
	const id = $(this).data('id');
	swal.fire({
		...config.swal,
	}).then(res=>{
		if(res.isConfirmed){
			$.ajax({
				url: `${url.file_entries}/${id}`,
				type: 'DELETE',
				data: {_token:'{{ csrf_token() }}'},
				success: (response)=> {
					// false = keep current page, true = reset to first page
					table.ajax.reload(null, false);
					swal.fire('Success!', response.message, response.status);
				}
			});
		}
	});
});



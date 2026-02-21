const { route, url, requestid, sessionlastBatchId } = window.data;

var table = $('#jb').DataTable({
	...config.datatable,
	// dom: 'Bfrtip',
	ajax: {
		type: 'GET',
		url: route.getJobBatchTable,
		dataSrc: '',
		data: function(da){
		},
	},
	columns: [
		{ data: 'name', title: 'Name', defaultContent: '-', orderable: false, searchable:false },
		{ data: 'pending', title: 'Pending', defaultContent: '-', orderable: false, searchable:false },
		{ data: 'success', title: 'Success', defaultContent: '-', orderable: false, searchable:false },
		{ data: 'failed', title: 'Failed', defaultContent: '-', orderable: false, searchable:false },
		{ data: 'totalJobs', title: 'Total Jobs', defaultContent: '-', orderable: false, searchable:false },
		{ data: 'processedJobs', title: 'Processed Jobs', defaultContent: '-', orderable: false, searchable:false },
	],
	initComplete: function(settings, response) {
		// console.log(response); // This runs after successful loading
	}
});

// Start polling
if (requestid || sessionlastBatchId) {
	const progressInterval = setInterval(checkProgress, 50);
	function checkProgress() {
		$.ajax({
			url: route.getProgress,
			data: {
				id: requestid ?? sessionlastBatchId
			},
			type: "GET",
			dataType: 'json',
			success: function(response) {
				const percent = Number(response.percent);
				// Safety check
				if (isNaN(percent)) return;
				// Update progress bar
				$('.progress').attr('aria-valuenow', percent).css('width', percent + '%');
				$(".csvprogress").css('width', percent + '%').text(percent + '%');
				$("#csvuploadStatus").html('<i class="fa-solid fa-spinner fa-spin-pulse fa-beat-fade"></i> Please wait..');
				$('#processedJobs').html(`<span>${response.processedJobs}</span> completed out of <span>${response.totalJobs}</span> process`);
				// reload DataTable without resetting paging
				table.ajax.reload(null, false);
				console.log("Progress:", percent);
				// Done?
				if (percent >= 100) {
					clearInterval(progressInterval);
					// redirect to download file
					window.location.href = route.downloadCSV;
				}
			},
			error: function(jqXHR, textStatus) {
				console.log("Progress error:", textStatus);
				// console.log("Progress error:", jqXHR);
			}
		});
		// $('#jb').DataTable().ajax.reload(null, false);
		table.ajax.reload(null, false);
	}
}

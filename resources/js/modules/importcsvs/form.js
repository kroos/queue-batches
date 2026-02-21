const { route, url, old, errors } = window.data;
function getError(name) {
	return errors[name] ? errors[name][0] : null;
}

// File upload via Ajax
$("#form").on('submit', function(e){
	e.preventDefault();
	$.ajax({
		xhr: function() {
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener("progress", function(evt) {
				if (evt.lengthComputable) {
					// Declaring JavaScript global variable within function
					window.percentComplete = ((evt.loaded / evt.total) * 100);
					$('#progressBar').attr('aria-valuenow', percentComplete).css('width', percentComplete+'%');
					$(".percent_upload").width(percentComplete.toPrecision(4) + '%');
					$(".percent_upload").html(percentComplete.toPrecision(4) +'%');
				}
			}, false);
			// console.log(xhr);
			return xhr;
		},
		type: 'POST',
		url: route.importcsvsstore,
		data: new FormData(this),
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function(){
			$(".progress-bar").width('0%');
			$('#uploadStatus').html('<i class="fa-solid fa-spinner fa-spin-pulse fa-beat-fade"></i> Please wait..');
		},
		error:function(resp){
			const res = resp.responseJSON;
			swal.fire('Error!', res.message,'error')
			.then(function(){
				window.location.reload(true);
			});
		},
		success: function(jqXHR, resp, errorThrown){
			console.log([jqXHR, resp, errorThrown]);
			if (percentComplete == 100) {
				window.location.replace(jqXHR);					// redirect action : important!
			}
		}
	});
});

// File type validation
$("#scvu").change(function(){
	var allowedTypes = [
		'application/vnd.ms-excel',
		'text/csv'
	];

   // 🔥 Guard: check if file exists
	if (!this.files || !this.files.length) {
		return;
	}

	var file = this.files[0];
	var fileType = file.type;

	if (!allowedTypes.includes(fileType)) {
		swal.fire('Error!', 'Please select a valid CSV file','error')
		.then(function(){
			window.location.reload(true);
		});

		$("#scvu").val('');
		return false;
	}
});

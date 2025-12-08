@extends('layouts.app')

@section('content')
<div class="col-sm-12">

	<div class="col-sm-12 row justify-content-center align-items-center my-2">
		<form method="POST" action="{{ route('importcsvs.store') }}" accept-charset="UTF-8" id="form" autocomplete="off" class="" enctype="multipart/form-data">
			@csrf
			<div class="card">
				<div class="card-header">Import CSV</div>
				<div class="card-body">
					<div class="form-group row m-1 @error('csv') has-error @enderror">
						<label for="scvu" class="col-form-label col-sm-4">CSV Upload : </label>
						<div class="col-sm-auto my-auto">
							<input type="file" name="csv[]" value="{{ old('csv', @$file->csv) }}" id="scvu" class="form-control form-control-sm @error('csv') is-invalid @enderror" placeholder="CSV Upload" aria-describedby="progressbar1" multiple>
							<div id="progressbar1" class="form-text">Upload File Progress</div>

							<div id="progressBar" class="progress" role="progressbar" aria-label="Progress Bar with label" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
								<div id="percent" class="progress-bar progress-bar-striped progress-bar-animated fw-bolder text-white percent_upload" style="width: 0%;">0% Uploading file/s</div>
							</div>

							<div id="uploadStatus" class="text-center"></div>

							@error('csv')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
							@enderror
						</div>
					</div>

				</div>
				<div class="card-footer">
					<div class="text-end">
						<button type="submit" class="btn btn-sm btn-outline-primary mx-1">Submit</button>
						<a href="{{ route('importcsvs.index') }}" class="btn btn-sm btn-outline-secondary mx-1">Cancel</a>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection

@section('js')
///////////////////////////////////////////////////////////////////////////////////////////
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
		url: '{{ route('importcsvs.store') }}',
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
	// var allowedTypes = ['application/vnd.ms-excel', 'application/pdf', 'application/msword', 'application/vnd.ms-office', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
	var allowedTypes = ['application/vnd.ms-excel'];
	var file = this.files[0];
	var fileType = file.type;
	if(!allowedTypes.includes(fileType)){
		// alert('Please select a valid file (PDF/DOC/DOCX/JPEG/JPG/PNG/GIF).');
		swal.fire('Error!', 'Please select a valid file (CSV file/s only)','error')
		.then(function(){
			window.location.reload(true);
		});
		$("#scvu").val('');
		return false;
	}
});

///////////////////////////////////////////////////////////////////////////////////////////
@endsection

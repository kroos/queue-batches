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
window.data = {
	route: {
		importcsvsstore: '{{ route('importcsvs.store') }}',
	},
	url: {
	},
	old: {
	},
	errors: @json($errors->toArray()),
};
@endsection

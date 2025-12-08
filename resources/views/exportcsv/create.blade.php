@extends('layouts.app')

@section('content')
<div class="col-sm-12">

	<div class="col-sm-12 row justify-content-center align-items-center my-2">
		<form method="POST" action="{{ route('exportcsvs.store') }}" accept-charset="UTF-8" id="form" autocomplete="off" class="" enctype="multipart/form-data">
			@csrf
			<div class="card">
				<div class="card-header">Export CSV</div>
				<div class="card-body">

					<div class="form-group row m-1 @error('Industry_code_NZSIOC') has-error @enderror">
						<label for="code" class="col-form-label col-sm-4">Industry_code_NZSIOC : </label>
						<div class="col-sm-4 my-auto">
							<select name="Industry_code_NZSIOC" id="code" class="form-select form-select-sm col-sm-12 @error('Industry_code_NZSIOC') is-invalid @enderror"></select>
							@error('Industry_code_NZSIOC')
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
						<a href="{{ route('exportcsvs.index') }}" class="btn btn-sm btn-outline-secondary mx-1">Cancel</a>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection

@section('js')
///////////////////////////////////////////////////////////////////////////////////////////
$('#code').select2({
	theme: 'bootstrap-5',
	placeholder: 'Please choose',
	allowClear: true,
	closeOnSelect: true,
	width: '100%',
	ajax: {
		url: '{{ route('getSelect2FileEntries') }}',
		type: 'GET',
		dataType: 'json',
		delay: 250,											// Delay to reduce server requests
		data: function (params) {
			return {
				_token: '{!! csrf_token() !!}',
				search: params.term,				// Search query
			}
		},
		processResults: function (data) {
			return {
				results: data.map(function(item) {
					return {
						id: item.Industry_code_NZSIOC,
						text: item.Industry_code_NZSIOC,
						raw: item
					}
				})
			};
		}
	},
});

///////////////////////////////////////////////////////////////////////////////////////////
@endsection

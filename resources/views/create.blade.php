<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

	<script type="text/javascript" src="{{ asset('js/fullcalendar/index.global.js') }}"></script>

	<script type="text/javascript" src="{{ asset('js/moment/moment.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/moment-range/dist/moment-range.js') }}"></script>


	@vite(['resources/css/app.css', 'resources/js/app.js'])

	<script type="module" src="{{ asset('js/jquery-chained/jquery.chained.js') }}"></script>
	<script type="module" src="{{ asset('js/jquery-chained/jquery.chained.remote.js') }}"></script>

	<script type="module" src="{{ asset('js/jquery-ui/dist/jquery-ui.js') }}"></script>
	<link href="{{ asset('js/jquery-ui/dist/themes/base/jquery-ui.css') }}" rel="stylesheet">
	<link href="{{ asset('js/jquery-ui/dist/themes/base/theme.css') }}" rel="stylesheet">

	<script type="module" src="{{ asset('js/pc-bootstrap4-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
	<link href="{{ asset('js/pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">
</head>
<body>
	<div class="container-fluid justify-content-center align-items-start">
		<div class="row col-auto justify-content-center text-center p-3 py-5">
			<h4 class="text-center">Interview Task</h4>
			<div class="col-sm-8">
				<noscript>
					<style type="text/css">
						.pagecontainer {display:none;}
					</style>
					<div class="noscriptmsg text-danger">
						This page requires JavaScript. Please enable it or you can contact your IT administrator.
						<meta http-equiv="refresh" content="0; url={{ url('/') }}" />
					</div>
				</noscript>


				<!-- IF SUCCESS -->
				@if(Session::has('flash_message'))
				<h6 class="pb-2 mb-2 border-bottom text-center alert alert-success">
					{{ Session::get('flash_message') }}
				</h6>
				@endif

				<!-- IF ERROR -->
				@if(Session::has('flash_danger'))
				<h6 class="pb-2 mb-2 border-bottom text-center alert alert-danger">
					{{ Session::get('flash_danger') }}
				</h6>
				@endif

				@if(count($errors) > 0 )
				<div class="col-sm-12 mb-3">
					<ul class="list-group">
						@foreach($errors->all() as $err)
							<li class="list-group-item list-group-item-danger">
								{!! $err !!}
							</li>
						@endforeach
					</ul>
				</div>
				@endif
				<p>&nbsp;</p>
				<form name="frmupload" method="POST" action="{{ route('interview.store') }}" accept-charset="UTF-8" id="uploadForm" autocomplete="off" enctype="multipart/form-data">
					@csrf
					<div class="row justify-content-center">
						<div class="form-group row mb-3 {{ $errors->has('reason') ? 'has-error' : '' }}">
							<label for="fileInput" class="col-form-label form-label-sm col-auto">Upload :</label>
							<div class="col-auto">
								<input name="csv[]" class="form-control form-control-sm col-auto" id="fileInput" type="file" aria-describedby="progressbar1" multiple>
								<div id="progressbar1" class="form-text">Upload File Progress</div>
								<div id="progressBar" class="progress" role="progressbar" aria-label="Progress Bar with label" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
									<div class="progress-bar percent" id="percent" style="width: 0%">0% Uploading file/s</div>
								</div>
								<div id="uploadStatus"></div>
							</div>
						</div>
						<div class="form-group row mb-3">
							<div class="col-auto offset-sm-2">
								<button type="submit" class="btn btn-sm btn-outline-secondary" id="submitButton" >Submit</button>
							</div>
						</div>
					</div>
				</form>
				<p>&nbsp;</p>
<?php
use Illuminate\Http\Request;
?>
				<div id="processcsv" class="row col-sm-12">
@if(request()->id || request()->session()->exists('lastBatchId') )
					<div class="progress col-sm-12" role="progressbar" aria-label="CSV Processing" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
						<div class="progress-bar csvprogress" style="width: 0%">0% CSV Processing</div>
					</div>
					<p>&nbsp;</p>
					<div class="table-responsive">
						<table class="table table-hover table-sm">
							<thead>
								<tr>
									<th>File</th>
									<th>Pending</th>
									<th>Success</th>
									<th>Failed</th>
								</tr>
							</thead>
							<tbody class=" table-group-divider">
@foreach(\App\Models\JobBatch::all() as $v)
								<tr>
									<td>{{ $v->name }}</td>
									<td>{{ ($v->pending_jobs == 0)?'No Pending':'Pending' }}</td>
									<td>
										@if($v->pending_jobs == 0 && $v->failed_jobs == 0)
											Success
										@else
											@if($v->pending_jobs > 0 && $v->failed_jobs == 0)
												Not Yet Process
											@else
												@if($v->pending_jobs == 0 && $v->failed_jobs > 0)
													Process With Fail
												@endif
											@endif
										@endif
									</td>
									<td>{{ ($v->failed_jobs == 0)?'No Failed':'Failed' }}</td>
								</tr>
@endforeach
							</tbody>
						</table>
					</div>
@endif
				</div>
			</div>
		</div>
	</div>
</body>
<script type="module">
	jQuery.noConflict ();
	(function($){
		$(document).ready(function(){
			@section('jquery')
			@show
			// alert("Hello\nHow are you?");
			$('#table').DataTable();
			$('#select2').select2({
				theme: 'bootstrap-5',
			});
			$('#inline').minicolors({
				theme: 'default',
			});
			// somehow cant make it work
			$("#series").chainedTo("#mark");

			$("#accordion").accordion();

			// console.log(moment().format('LLLL'));

			$('#datepicker').datetimepicker();

			$('#sweetalert').click(function(){
				Swal.fire({
					title: 'Are you sure?',
					text: "You won't be able to revert this!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.isConfirmed) {
						Swal.fire(
								  'Deleted!',
								  'Your file has been deleted.',
								  'success'
								  )
					}
				});
			});



			@if( request()->id || session()->exists('lastBatchId') )
				<?php $batchId = $request->id ?? session()->get('lastBatchId'); ?>
				setInterval(percent, 500);
				function percent() {
					// console.log('test');
					// window.percentComplete = ((evt.loaded / evt.total) * 100);
					// $(".csvprogress").width(percentComplete.toPrecision(4) + '%');
					// $(".csvprogress").html(percentComplete.toPrecision(4) +'%');

					$.ajax({
						url: '{{ route('interview.progress', ['id' => $batchId]) }}',
						type: "GET",
						data: { _token: '{{ csrf_token() }}'},
						dataType: 'json',
						success: function (response) {
							// var resp = response.responseJSON;
							// return resp;
							console.log(response);
						},
						error: function(jqXHR, textStatus, errorThrown) {
							// console.log(textStatus, errorThrown);
						}
					})
				}
			@endif

			// File upload via Ajax
			$("#uploadForm").on('submit', function(e){
				e.preventDefault();
				$.ajax({
					xhr: function() {
						var xhr = new window.XMLHttpRequest();
						xhr.upload.addEventListener("progress", function(evt) {
							if (evt.lengthComputable) {
								// Declaring JavaScript global variable within function
								window.percentComplete = ((evt.loaded / evt.total) * 100);
								$(".progress-bar").width(percentComplete.toPrecision(4) + '%');
								$(".progress-bar").html(percentComplete.toPrecision(4) +'%');
							}
						}, false);
						// console.log(xhr);
						return xhr;
					},
					type: 'POST',
					url: '{{ route('interview.store') }}',
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					beforeSend: function(){
						$(".progress-bar").width('0%');
						$('#uploadStatus').html('<i class="fa-solid fa-spinner fa-spin-pulse fa-beat-fade"></i>');
					},
					error:function(resp){
						const res = resp.responseJSON;
						Swal.fire('Error!', res.message,'error')
						.then(function(){
							window.location.reload(true);
						});
					},
					success: function(jqXHR, resp, errorThrown){
						// console.log([jqXHR, resp, errorThrown]);
						if (percentComplete == 100) {
							// window.location.reload(true);
							window.location.replace(jqXHR);					// redirect action
						}
					}
				});
			});

			// File type validation
			$("#fileInput").change(function(){
				// var allowedTypes = ['application/vnd.ms-excel', 'application/pdf', 'application/msword', 'application/vnd.ms-office', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
				var allowedTypes = ['application/vnd.ms-excel'];
				var file = this.files[0];
				var fileType = file.type;
				if(!allowedTypes.includes(fileType)){
					// alert('Please select a valid file (PDF/DOC/DOCX/JPEG/JPG/PNG/GIF).');
					Swal.fire('Error!', 'Please select a valid file (CSV file/s only)','error')
					.then(function(){
						window.location.reload(true);
					});
					$("#fileInput").val('');
					return false;
				}
			});

		});
	})(jQuery);

	@section('nonjquery')
	@show

	// chartjs
	// const ctx = document.getElementById('myChart');
	// new Chart(ctx, {
	// 	type: 'bar',
	// 	data: {
	// 		labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
	// 		datasets: [{
	// 			label: '# of Votes',
	// 			data: [12, 19, 3, 5, 2, 3],
	// 			borderWidth: 1
	// 		}]
	// 	},
	// 	options: {
	// 		scales: {
	// 			y: {
	// 				beginAtZero: true
	// 			}
	// 		}
	// 	}
	// });

	// fullcalendar
	// document.addEventListener('DOMContentLoaded', function() {
	// 	const calendarEl = document.getElementById('calendar')
	// 	const calendar = new FullCalendar.Calendar(calendarEl, {
	// 		// plugins: [dayGridPlugin],
	// 		headerToolbar: {
	// 			left: 'prev,next today',
	// 			center: 'title',
	// 			right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
	// 		}
	// 	})
	// 	calendar.render()
	// });















</script>
</html>

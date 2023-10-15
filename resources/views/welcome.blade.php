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
	<div class="container mb-3">
		<div class="row col-auto">
			<table id="table" class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">First</th>
						<th scope="col">Last</th>
						<th scope="col">Handle</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row">1</th>
						<td>Mark</td>
						<td>Otto</td>
						<td>@mdo</td>
					</tr>
					<tr>
						<th scope="row">2</th>
						<td>Jacob</td>
						<td>Thornton</td>
						<td>@fat</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="row col-auto mb-3">
			<select name="test" id="select2" class="form-select form-select-sm col-auto">
				<option value="">Please choose</option>
				<option value="1">1</option>
				<option value="2">2</option>
			</select>
		</div>
		<div class="row col-auto mb-3">
			<input type="text" id="inline" class="form-control form-control-sm col-auto" data-inline="true" value="#4fc8db" >
		</div>
		<div class="row col-auto mb-3">
			<button class="btn btn-sm btn-block"><i class="fa-brands fa-firefox-browser fa-beat" style="color: #3763fb;"></i>&nbsp;FortAwesome Icon</button>
		</div>
		<div class="row col-auto mb-3">
			<span class="mdi mdi-loading mdi-spin">Processing</span>&nbsp;MDI Font
			<span class="mdi mdi-account-arrow-up-outline"></span>&nbsp;MDI Font
		</div>
		<div class="row col-auto mb-3">
			<h1 class="animate__animated animate__bounce">An animated element</h1>
		</div>
		<div class="row col-auto mb-3">
			<i class="bi-alarm" style="font-size: 2rem; color: cornflowerblue;"></i>&nbsp;Bootstrap Icon
		</div>
		<div class="row col-auto mb-3">
			<select id="mark" name="mark" class="form-select form-select-sm col-auto" placeholder="Please choose">
				<option value="">--</option>
				<option value="1">BMW</option>
				<option value="2">Audi</option>
			</select>
			&nbsp;
			<select id="series" name="series" class="form-select form-select-sm col-auto" placeholder="Please choose">
				<option value="">--</option>
				<option value="series-3" class="1">3 series</option>
				<option value="series-5" class="1">5 series</option>
				<option value="series-6" class="1">6 series</option>
				<option value="a3" class="2">A3</option>
				<option value="a4" class="2">A4</option>
				<option value="a5" class="2">A5</option>
			</select>
		</div>
		<div class="row col-auto mb-3" style="position: relative;">
			<input type="text" class="col-auto" id="datepicker">
		</div>
		<div class="row col-auto mb-3">
			<div class="pretty p-icon p-round p-tada">
				<input type="checkbox" />
				<div class="state p-primary-o">
					<i class="icon mdi mdi-heart"></i>
					<label>Good</label>
				</div>
			</div>
		</div>
		<div class="row col-auto mb-3">
			<button id="sweetalert" class="btn btn-sm btn-primary">Sweet Alert 2</button>
		</div>
		<div>
			<canvas id="myChart"></canvas>
		</div>
		<div>
			<div id='calendar'></div>
		</div>
		<div class="row col-auto mb-3">
			<div id="accordion">
				<h3>Section 1</h3>
				<div>
				  <p>
				  Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer
				  ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit
				  amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut
				  odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate.
				  </p>
				</div>
				<h3>Section 2</h3>
				<div>
				  <p>
				  Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet
				  purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor
				  velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In
				  suscipit faucibus urna.
				  </p>
				</div>
				<h3>Section 3</h3>
				<div>
				  <p>
				  Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis.
				  Phasellus pellentesque purus in massa. Aenean in pede. Phasellus ac libero
				  ac tellus pellentesque semper. Sed ac felis. Sed commodo, magna quis
				  lacinia ornare, quam ante aliquam nisi, eu iaculis leo purus venenatis dui.
				  </p>
				  <ul>
					<li>List item one</li>
					<li>List item two</li>
					<li>List item three</li>
				  </ul>
				</div>
				<h3>Section 4</h3>
				<div>
				  <p>
				  Cras dictum. Pellentesque habitant morbi tristique senectus et netus
				  et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in
				  faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia
				  mauris vel est.
				  </p>
				  <p>
				  Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
				  Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
				  inceptos himenaeos.
				  </p>
				</div>
			  </div>
		</div>
	</div>
</body>
<script type="module">
	// import moment from '../../public/js/moment/moment.js';
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

			moment().format();
			console.log(moment().format('LLLL'));

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



		});
	})(jQuery);

	@section('nonjquery')
	@show

	// chartjs
	const ctx = document.getElementById('myChart');
	new Chart(ctx, {
		type: 'bar',
		data: {
			labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
			datasets: [{
				label: '# of Votes',
				data: [12, 19, 3, 5, 2, 3],
				borderWidth: 1
			}]
		},
		options: {
			scales: {
				y: {
					beginAtZero: true
				}
			}
		}
	});

	// fullcalendar
	document.addEventListener('DOMContentLoaded', function() {
		const calendarEl = document.getElementById('calendar')
		const calendar = new FullCalendar.Calendar(calendarEl, {
			// plugins: [dayGridPlugin],
			headerToolbar: {
				left: 'prev,next today',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
			}
		})
		calendar.render()
	});















</script>

</html>

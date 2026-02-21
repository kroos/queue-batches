const { route, url, old, errors } = window.data;
function getError(name) {
	return errors[name] ? errors[name][0] : null;
}

$('#code').select2({
	...config.select2,
	ajax: {
		url: route.getSelect2FileEntries,
		type: 'GET',
		dataType: 'json',
		delay: 250,											// Delay to reduce server requests
		data: function (params) {
			return {
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


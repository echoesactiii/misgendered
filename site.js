$(document).ready(function(){
	$('#form-name').typeahead({
		name: 'organisations',
		remote: '/api.php?json={"action":"complete","term":"%QUERY"}',
		template: [
			'<p><b>{{value}}</b><br/>',
			'{{address}}, {{city}}, {{postcode}}'
		].join(''),
		engine: Hogan
	});

	$('#form-name').bind('typeahead:selected', function(obj, datum) {
		$('#form-address').val(datum.address);
		$('#form-city').val(datum.city);
		$('#form-postcode').val(datum.postcode);
	});
});
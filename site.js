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

	$('#btn-initial-next').click(function(e){
		e.preventDefault();
		$('#formset-initial').hide();
		$('#formset-misgendered').show();
	});

	$('#form-gender-id').change(function(e){
		if($('#form-gender-id').val() == "ftm"){
			var explanation = "I am a man who was born into a typically female body. Throughout my life, I have been aware of the fact I am male, and I present as male in my day-to-day existence.";
		}else if($('#form-gender-id').val() == "mtf"){
			var explanation = "I am a woman who was born into a typically male body. Throughout my life, I have been aware of the fact I am female, and I present as female in my day-to-day existence.";
		}else if($('#form-gender-id').val() == "genderqueer" || $('#form-gender-id').val() == "androgyne" || $('#form-gender-id').val() == "genderless" || $('#form-gender-id').val() == "genderless"){
			var explanation = "I am a person who does not fit into the narrow two gender system used by the majority of western societies. I am neither male nor female. Though this is a concept unknown to many non-transgender people, it is important to note that the NHS and many other world health services recognise gender as a spectrum.";
		}else if($('#form-gender-id').val() == "genderfluid"){
			var explanation = "I am a person who does not fit into the narrow two gender system used by the majority of western societies. Though this is a concept unknown to many non-transgender people, it is important to note that the NHS and many other world health services recognise gender as a spectrum. My gender identity moves around within this spectrum, and I sometimes change the way I present my gender to the world. This helps me express this, and also helps me feel more comfortable in my body.";
		}

		$('#form-gender-explanation').val(explanation);
	})
});
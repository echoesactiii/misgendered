$().ready(function(){

// Bi-directional letter/cost calculator including payment service commission

	$("#letters").bind("input", function(){ // When letters field is changed

		var letters = $("#letters").val();
		if (letters == "" || letters == 0){
			money = 0;
		}else{
			var money = (letters * letter_cost * comm_multi) + comm_add; // Calculate cost of X letters
		}

		$("#money").val(money.toFixed(2)); // Fill form with result
	});


	$("#money").bind("input", function(){ // When money field is changed
		var money = $("#money").val();
		if (money == "" || money == 0){
			letters = "0";
		}else{
			var letters = ((money - comm_add) / comm_multi) / letter_cost; // Calculate number of letters X cost would cover
		}

		$("#letters").val(Math.floor(letters)); // Fill form with result
	});

	$('#donate-btn').on('click', function(e) {
		// Open Checkout with further options
		handler.open({
			name: site_title,
			description: site_title + ' Funds Donation',
			currency: "gbp",
			amount: $('#money').val() * 100
		});
		e.preventDefault();
	});

	// Close Checkout on page navigation
	$(window).on('popstate', function() {
		handler.close();
	});

});

var handler = StripeCheckout.configure({
	key: stripe_key,
	locale: 'auto',
	token: function(token) {
	  $('#stripe_token').val(token.id);
	  $('#stripe_email').val(token.email);
	  $('#donate_form').submit();
	}
});
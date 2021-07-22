
		window.addEventListener('message', function(event) {
			var data = JSON.parse(event.data)

			if(data.valid)
			{
				var initEmbeddedPayment = $.get("?action=auth-payment&method=embedded")

				initEmbeddedPayment.done(function(data) {
					var response
					try
					{
						response = $.parseJSON(data)
					}
					catch(err)
					{
						alert('Unable to initialize embedded card payment. Please check that api key and private key are correct.')
						return
					}

					var payMessage = {
						action: 'pay',
						token: response.token
					}

					document.getElementById('pf-cc-iframe').contentWindow.postMessage(
						JSON.stringify(payMessage),
						'https://www.vismapay.com/'
					)
				})
			}
		});

		// Embedded iframe card form
		$("#inline-form").click(function(e) {
			e.preventDefault()

			var validateMessage = {
				action: "validate"
			}

			document.getElementById('pf-cc-iframe').contentWindow.postMessage(
				JSON.stringify(validateMessage), 
				'https://www.vismapay.com/'
			)
		})

		// Open minified card form in iframe
		var card_payment_result = $('.card-payment-result')
		$("#iframe").click(function(e)
		{
			e.preventDefault()
			var initPayment = $.get("?action=auth-payment&method=iframe")
			initPayment.done(function(data) {
				var response
				try
				{
					response = $.parseJSON(data)
				}
				catch(err)
				{
					card_payment_result.html('Unable to create card payment. Please check that api key and private key are correct.')
					alert('Unable to create card payment. Please check that api key and private key are correct.')
					return
				}
				var overlay = $('<div id="overlay"></div>').appendTo(document.body);
				$('<iframe>', {
					src: response.url+"?minified",
					id:  'payment_frame',
					frameborder: 0,
					scrolling: 'no'
				}).appendTo(document.body);
			})
		})

		//if in iframe, prevent inception
		if(window.self !== window.top)
			$("#mainpage").hide();

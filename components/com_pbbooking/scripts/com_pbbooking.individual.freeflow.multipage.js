window.addEvent('domready',function(){

	$('treatment_id').addEvent('change',function(){
		//loop through all the treatments and see if payment is required...
		var service_id = this.getProperty('value');
		require_payment = false;
		$treatments.forEach(function(obj,idx){
			//console.log('idx '+idx+' is object '+obj.name);
			if (service_id == obj.id) {
				if (obj.require_payment==1) {
					require_payment = true;
				}
			}
		});
		if (require_payment) {
			$('pbbooking-notifications').setProperty('html',Joomla.JText._('COM_PBBOOKING_SERVICE_REQUIRES_PAYMENT')).addClass('pbbooking-notifications-active');
			setup_paypal_form();
		} else {
			$('pbbooking-notifications').setProperty('html','').removeClass('pbbooking-notifications-active');
			document.getElementById('pbbooking-paypal-form').innerHTML = '';
			document.getElementById('pbbooking-submit').style.display = 'block';
			document.getElementById('pbbooking-submit').style.marginLeft = 'auto';
			document.getElementById('pbbooking-submit').style.marginRight = 'auto';
		}
	});


		//override the submit button
		$('pbbooking-submit').addEvent('click',function(e){	
			
			e.stop();

			var error = validate_input();
			if (!error)
				document.getElementById('pbbooking-reservation-form').submit();
		});

})


/* 
renders the paypal payment form and sets everything up
*/
function setup_paypal_form() {
	document.getElementById('pbbooking-submit').style.display = 'none';
	var request = new Request.HTML({
		url:load_paypal_form_url,
		onSuccess:function(responseTree, responseElements, responseHTML, responseJavaScript) {
			document.getElementById('pbbooking-paypal-form').innerHTML = responseHTML;
			$('pbbooking-paypal').addEvent('submit',function(e){
				process_paypal_deposit(e);
			});
			document.getElementById('pbbooking-paypal-form').style.textAlign = 'center';
		},
		onTimeout:function(){},
		onFailure:function(){}
	}).get({service:document.getElementById('pbbooking-reservation-form').treatment_id.value,'format':'raw'});

}



/*
process paypal payment
*/

function process_paypal_deposit(e)
{

	if (validate_input() == false) {
		var request = new Request.JSON({
			url:base_url+'index.php?option=com_pbbooking&task=save&format=raw&view=pbbooking',
			onSuccess: function(json,text) {
				//alert(text);
				document.getElementById('pbbooking-paypal').notify_url.value=window.location.protocol+'//'+window.location.hostname+json.notify_url;
				document.getElementById('pbbooking-paypal').custom.value=json.id;
				document.getElementById('pbbooking-paypal').submit();
			},
			onTimeout:function(){},
			onFailure:function(){},
			useSpinner:true
		}).send($('pbbooking-reservation-form'));
	}
}






/*
validate input - revised method for validating the input just to do validation and return true or false
*/

function validate_input()
{
	error = false;
	//alert('validate input');
	for(var i=0;i<$customfields.length;i++) {
		if ($customfields[i].fieldtype=="text") {
			val = $$('input[name='+$customfields[i].varname+']').getProperty('value');
			if (val == "" && $customfields[i].is_required == 1) {
				$$('input[name='+$customfields[i].varname+']').setProperty('class','error-field');
				error = true;
			}
		}
		
		if ($customfields[i].fieldtype=="textarea") {
			val  = $$('textarea[name='+$customfields[i].varname+']').getProperty('value');
			if (val == "" && $customfields[i].is_required == 1) {
				$$('textarea[name='+$customfields[i].varname+']').setProperty('class','error-field');
				error = true;
			}
		}
		
		if (MooTools.version != "1.12") {
			//MooTools 1.12 doesn't support pseudo selectors.
			if ($customfields[i].fieldtype=="radio" && $customfields[i].is_required == 1) {
				if ($$('input[name='+$customfields[i].varname+']:checked').length==0) {
					error = true;
					$$('.'+$customfields[i].varname+'-label').addClass('error-label');
				}
			}
			if ($customfields[i].fieldtype=="checkbox" && $customfields[i].is_required == 1) {
				if ($$('input[name='+$customfields[i].varname+'[]]:checked').length==0) {
					error = true;
					$$('.'+$customfields[i].varname+'-label').addClass('error-label');
				}
			}
		}
	}
	
	//do i have a service type?
	if ($$('select[name=treatment_id]').getLast().getProperty('value') == -1) {
		error = true;
		$('service-error-msg').innerHTML = $error_msg_treatment;
		$('service-error-msg').addClass('error-message');
	}
	
	//have I selected a time slot
	var treatment_time = $$('treatment-time').getProperty('value');
	if (treatment_time == -1) {
		//$('timeslot-error-msg').setProperty('text',$error_msg_timeslot);
		$('timeslot-error-msg').innerHTML = $error_msg_timeslot;
		$('timeslot-error-msg').addClass('error-message');
		error = true;
	}

	return error;

}


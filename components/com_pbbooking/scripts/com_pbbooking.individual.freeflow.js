var treatment_time = -1;

window.addEvent('domready',function(){

	//new for 2.3 reload any data that might have been saved from previous use
	load_previous_data();
		
	//new for 2.2 bind to the select box to load timeslots if it changes
	$('select-timegrouping').addEvent('change',function(e){
		load_slots($('text-date').getProperty('value'),this.getProperty('value'),$('treatment_id').getProperty('value'));
	})
	
	//override the submit button
	$('pbbooking-submit').addEvent('click',function(e){	
		
		if (MooTools.version == "1.12") {
			event = new Event(e);  //had to be done this way for back compatibility.
			event.stop();
		} else {
			e.stop();
		}
		validateInput();
		return false;
	})
	
	//keep tracking the selected treatment - modified in version 2.2 to reflect select box.
	$$('select[name=massage]').addEvent('change',function(){
		$$('input[name=treatment_id]').setProperty('value',this.getProperty('value'));
		service_id = this.getProperty('value');

		if ($('select-timegrouping').getProperty('value') != 0 || enable_shifts == 0) {
			load_slots($('text-date').getProperty('value'),$('select-timegrouping').getProperty('value'),$('treatment_id').getProperty('value'));
		}

		if (enable_shifts == 1) {
			$('pbbooking-shift-select').setStyle('display','block');
		}

		//loop through all the treatments and see if payment is required...
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


		}
		
	}) 
	
	//bind the blur on the custom fields if is_required == 1
	for(i=0;i<$customfields.length;i++) {
		if ($customfields[i].is_required == 1) {
			
			if ($customfields[i].fieldtype=='text') {		
				$$('input[name='+$customfields[i].varname+']').addEvent('blur',function(){
					
					if($(this).getProperty('value') == "") {
						$(this).addClass('error-field');
					} else {
						$(this).removeClass('error-field');
					}
					
				})
			}
			
			if ($customfields[i].fieldtype=='radio') {
				$$('input[name='+$customfields[i].varname+']').addEvent('click',function(){
					name = $(this).getProperty('name');
					$$('.'+name+'-label').removeClass('error-label');
				})
			}			
			
			if ($customfields[i].fieldtype=='checkbox') {
				$$('input[name='+$customfields[i].varname+'[]]').addEvent('click',function(){
					name = $(this).getProperty('name');
					$$('.'+name.replace(/(.*)\[\]/,'$1')+'-label').removeClass('error-label');
				})
			}			
		}
	}

	//listen for document unload to save user data and save the need for re-entry
	window.addEvent('unload',function(){
		save_entered_data();
	})
	

});


function validateInput()
{
	error = validate_input();

	if (!error) $('pbbooking-reservation-form').submit();		
}

/**
* load_slots(date,grouping,treatment) - called when the time grouping selector is changed to get timeslots back and draw 			
* build the calendar output in the pbbooking-timeslot-listing div
* @param string date - the currently selected date as string
* @param string grouping - the selected grouping as a string
*/	
function load_slots(date,grouping,treatment)
{

	var request = new Request.HTML({
		'url': base_url+'index.php',
		onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript){
		
			//push the html into the dom.
			$('pbbooking-timeslot-listing').setProperty('html',responseHTML);
			
			//bind to the newly insert treatment time radio boxes
			$$('input[name=treatment-time]').addEvent('click',function(){
				treatment_time = this.getProperty('value');
				$('text-cal-id').setProperty('value',this.getProperty('class').replace(/cal_id\-(\d+)/,'$1'));
			})
			
		}
	}).get({'date':date,'option':'com_pbbooking','task':'load_slots_for_day','format':'raw','grouping':grouping,'treatment':treatment});	
}

/*
loops through all the custom fields and user data to see if values have been entered and rights the whole lot to cookie
*/

function save_entered_data()
{
	var fielddata = '';
	$customfields.forEach(function(el,idx){
		var data = $(el.varname).getProperty('value');
		if (data>'') {
			if (fielddata != '')
				fielddata += '|';
			var d = el.varname+'='+data;
			fielddata+= d;
		}
	});
	Cookie.write('pbbooking',fielddata);
}


function load_previous_data()
{
	var fielddata = Cookie.read('pbbooking');
	if (fielddata) {
		//alert('have data to load');
		var fields = fielddata.split('|');
		fields.forEach(function(el,idx){
			var data = el.split('=');
			var domel = document.getElementById(data[0]);
			domel.value = data[1];
		});
	}

}

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
		},
		onTimeout:function(){},
		onFailure:function(){}
	}).get({service:document.getElementById('pbbooking-reservation-form').massage.value,'format':'raw'});

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
	if ($$('input[name=treatment_id]').getLast().getProperty('value') == -1) {
		error = true;
		$('service-error-msg').innerHTML = $error_msg_treatment;
		$('service-error-msg').addClass('error-message');
	}
	
	//have I selected a time slot
	if (treatment_time == -1) {
		//$('timeslot-error-msg').setProperty('text',$error_msg_timeslot);
		$('timeslot-error-msg').innerHTML = $error_msg_timeslot;
		$('timeslot-error-msg').addClass('error-message');
		error = true;
	}

	return error;

}
<?php 
	

	//push in mootools.
	Jhtml::_('behavior.framework');
	Jhtml::_('behavior.framework','more');

	$doc = &JFactory::getDocument();
	$doc->addStyleSheet(JURI::root(false)."components/com_pbbooking/user_view_multi.css");
	$doc->addScript(JURI::root(false).'components/com_pbbooking/scripts/com_pbbooking.individual.freeflow.multipage.js');
	$doc->addScriptDeclaration('var $treatments = '.json_encode($this->treatments));
	$doc->addScriptDeclaration('var load_paypal_form_url = "'.JRoute::_('index.php?option=com_pbbooking&task=load_paypal_form').'";');
	$doc->addScriptDeclaration('var $customfields = '.json_encode($this->customfields));
	$doc->addScriptDeclaration('var base_url = "'.JURI::root(false).'";');
	JText::script('COM_PBBOOKING_SERVICE_REQUIRES_PAYMENT');

?>

<div id="pbbooking-notifications"></div>

<form action="<?php echo JRoute::_('index.php?option=com_pbbooking&task=save');?>" method="POST" id = "pbbooking-reservation-form">

	<?php
		echo '<input type="hidden" name="date" id="text-date" value='.$this->dateparam->format('Ymd').'>';
	?>
	
	<h2><?php echo JText::_('COM_PBBOOKING_YOURDETAILS');?></h2>
	
	
	<!-- begin render custom fields -->
	<table style="width:80%;" class="pbbooking-data-table">
	<?php foreach($this->customfields as $customfield) : ?>
		<tr>
			<td><?php echo $customfield->fieldname;?></td>
			<td>
				<?php if ($customfield->fieldtype == 'text') :?>
					<input type="text" name="<?php echo $customfield->varname;?>" value="" id="<?php echo $customfield->varname;?>">
				<?php endif;?>
				<?php if ($customfield->fieldtype == 'radio') :?>
					<?php foreach (explode('|',$customfield->values) as $value) :?>
						<label class="<?php echo $customfield->varname;?>-label"><?php echo $value;?></label> <input type="radio" name="<?php echo $customfield->varname;?>" value="<?php echo $value;?>" id="<?php echo $customfield->varname;?>"/>
					<?php endforeach;?>
				<?php endif;?>
				<?php if ($customfield->fieldtype == 'select'): ?>
					<select name="<?php echo $customfield->varname;?>" id="<?php echo $customfield->varname;?>">
						<?php foreach (explode('|',$customfield->values) as $value) :?>
							<option value="<?php echo $value;?>"><?php echo $value;?></option>
						<?php endforeach;?>
					</select>
				<?php endif;?>
				<?php if ($customfield->fieldtype == 'checkbox'):?>
					<?php foreach (explode('|',$customfield->values) as $value) :?>
						<label class="<?php echo $customfield->varname;?>-label"><?php echo $value;?></label>
						<input type="checkbox" name="<?php echo $customfield->varname;?>[]" value="<?php echo $value;?>" id="<?php echo $customfield->varname;?>"/>
					<?php endforeach;?>
				<?php endif;?>
				<?php if ($customfield->fieldtype == 'textarea'):?>
					<textarea name="<?php echo $customfield->varname;?>" id="<?php echo $customfield->varname;?>"></textarea>
				<?php endif;?>
			</td>
		</tr>
	<?php endforeach;?>
	</table>
	<!-- end render custom fields -->
	
	<h2><?php echo JText::_('COM_PBBOOKING_BOOKINGDETAILS');?></h2>
	<table class="pbbooking-data-table">
		<tr>
			<td style="width:200px;"><?php echo JText::_('COM_PBBOOKING_SUCCESS_DATE');?></td>
			<td><?php echo JHtml::_('date',$this->dateparam->format(DATE_ATOM),JText::_('COM_PBBOOKING_SUCCESS_DATE_FORMAT'));?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_PBBOOKING_SUCCESS_TIME');?></td>
			<td><?php echo JHtml::_('date',$this->dateparam->format(DATE_ATOM),JText::_('COM_PBBOOKING_SUCCESS_TIME_FORMAT'));?></td>
		</tr>
		<!--<tr>
			<td><?php echo JText::_('COM_PBBOOKING_SUCCESS_CALENDAR');?></td>
			<td><?php echo $this->cal->name;?></td>
		</tr>-->
		<tr>
			<td><?php echo JText::_('COM_PBBOOKING_BOOKINGTYPE');?></td>
			<td>
				<select name="treatment_id" id="treatment_id">
					<option value="0"><?php echo JText::_('COM_PBBOOKING_SELECT_DEFAULT');?></option>
					<?php foreach($this->treatments as $treatment) : ?>
						<option value="<?php echo $treatment->id;?>" 
							<?php echo ($this->cal->can_book_treatment_at_time($treatment->id,$this->dateparam,$this->closing_time)) ? null : 'disabled';?>
							>
							<?php echo $treatment->name;?>
							<?php if ($this->config->show_prices) :?>
								<?php if ($this->config->currency_symbol_before) :?>
									<?php echo JText::_('COM_PBBOOKING_CURRENCYSYMBOL').$treatment->price;?>
								<?php else:?>
									<?php echo $treatment->price.JText::_('COM_PBBOOKING_CURRENCYSYMBOL');?>
								<?php endif;?>
							<?php endif;?>
						</option>
					<?php endforeach;?>
				</select>
			</td>
		</tr>
	</table>

	
	<!-- begin render service types -->
	<?php $i=0;?>
	<h3></h3>
	<div id="service-error-msg"></div>

	<input type="hidden" name="cal_id" id="text-cal-id" value="<?php echo $this->cal->cal_id;?>"/> 
	<input type="hidden" name="date" value="<?php echo $this->dateparam->format('Ymd');?>"/>
	<input type="hidden" name="treatment-time" value="<?php echo $this->dateparam->format('Hi');?>"/>
	
	<!-- end render service types -->
	<div style="text-align:center;">
		<p></p><input type="submit" value="<?php echo JText::_('COM_PBBOOKING_SUBMIT_BUTTON');?>" id="pbbooking-submit"></p>
	</div>
</form>


<div id="pbbooking-paypal-form"></div>
	



<?php 
	
	$doc = &JFactory::getDocument();
	$doc->addStyleSheet(JURI::root(false)."components/com_pbbooking/user_view_multi.css");
?>


<h1><?php echo JText::_('COM_PBBOOKING_DAY_VIEW_HEADING').' '.Jhtml::_('date',$this->dateparam->format(DATE_ATOM),JText::_('COM_PBBOOKING_DAY_VIEW_DATE_FORMAT'));?></h1>

<?php if (!$this->user->authorise('pbbooking.create','com_pbbooking')) :?>
	<div class="pbbooking-notifications-active">
		<p><?php echo JText::_('COM_PBBOOKING_LOGIN_MESSAGE_CREATE');?></p>
	</div>
<?php endif;?>


<table id="pbbooking">
<!-- Draw header row showing calendars across the top....-->
	<tr>
		<th></th> <!-- first column left blank to display time slots -->
		<?php foreach ($this->cals as $cal) :?>
			<th><?php echo $cal->name;?></th>
		<?php endforeach;?>
	</tr>


	<!-- draw table data rows -->

	<?php while ($this->day_dt_start <= $this->last_slot_for_day) :?>
		<?php $slot_end = date_create($this->day_dt_start->format(DATE_ATOM),new DateTimezone(PBBOOKING_TIMEZONE));?>
		<?php $slot_end->modify('+ '.$this->config->time_increment.' minutes');?>
		<tr>
			<th><?php echo $this->day_dt_start->format(JText::_('COM_PBBOOKING_SUCCESS_TIME_FORMAT'));?></th>
			<?php foreach ($this->cals as $cal) :?>
				<td class="pbbooking-<?php echo (!$cal->is_free_from_to($this->day_dt_start,$slot_end)) ? 'free' : 'busy';?>-cell">
					<?php if ($this->day_dt_start>date_create("now",new DateTimeZone(PBBOOKING_TIMEZONE)) && !$cal->is_free_from_to($this->day_dt_start,$slot_end)) :?>
						<?php if ($this->user->authorise('pbbooking.create','com_pbbooking')) :?>
							<a href="<?php echo JRoute::_('index.php?option=com_pbbooking&task=create&dtstart='.$this->day_dt_start->format('YmdHi').'&cal_id='.$cal->cal_id);?>">
						<?php endif;?>
							<?php echo (!$cal->is_free_from_to($this->day_dt_start,$slot_end)) ? JText::_('COM_PBBOOKING_FREE') : JText::_('COM_PBBOOKING_BUSY');?>
						<?php if ($this->user->authorise('pbbooking.create','com_pbbooking')) :?>
							</a>
						<?php endif;?>
					<?php else :?>
						<?php echo JText::_('COM_PBBOOKING_BUSY');?>
					<?php endif;?>
				</td>
			<?php endforeach;?>
		</tr>
		<?php $this->day_dt_start->modify('+ '.$this->config->time_increment.' minutes');?>
	<?php endwhile;?>

	<!-- end draw table data rows-->

</table>
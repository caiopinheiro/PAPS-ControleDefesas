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


	<!-- loop through the shifts drawing the blocks -->
	<?php foreach ($this->shifts as $shift=>$times) : ?>
		<tr><td colspan="<?php echo count($this->cals)+1;?>"><span id="pbbooking-dayview-shift-label"><?php echo $times['display_label'];?></span></td></tr>
		<?php 
			$shift_start = date_create($this->day_dt_start->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
			$shift_end = date_create($this->day_dt_start->format(DATE_ATOM), new DateTimeZone(PBBOOKING_TIMEZONE));
			$shift_start->setTime($times['start_time']['start_hour'],$times['start_time']['start_min'],0);
			$shift_end->setTime($times['end_time']['end_hour'],$times['end_time']['end_min'],59);
			$shift_last_slot = date_create($shift_end->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
			$shift_last_slot->modify('- '.$this->config->time_increment.' minutes');
		?>
		<?php while ($shift_start<$shift_last_slot) :?>
			<?php 
				$slot_end = date_create($shift_start->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
				$slot_end->modify('+ '.$this->config->time_increment.' minutes');
			?>
			<tr>
				<th><?php echo $shift_start->format(JText::_('COM_PBBOOKING_SUCCESS_TIME_FORMAT'));?></th>

				<!-- now render cal availability for each calendar -->
				<?php foreach ($this->cals as $cal) :?>
					<td class="pbbooking-<?php echo (!$cal->is_free_from_to($shift_start,$slot_end)) ? 'free' : 'busy';?>-cell">
						<?php if ($shift_start>date_create("now",new DateTimeZone(PBBOOKING_TIMEZONE)) && !$cal->is_free_from_to($shift_start,$slot_end)) :?>
							<?php if ($this->user->authorise('pbbooking.create','com_pbbooking')) :?>
								<a href="<?php echo JRoute::_('index.php?option=com_pbbooking&task=create&dtstart='.$shift_start->format('YmdHi').'&cal_id='.$cal->cal_id);?>">
							<?php endif;?>
								<?php echo (!$cal->is_free_from_to($shift_start,$slot_end)) ? JText::_('COM_PBBOOKING_FREE') : JText::_('COM_PBBOOKING_BUSY');?>
							<?php if ($this->user->authorise('pbbooking.create','com_pbbooking')) :?>
								</a>
							<?php endif;?>
						<?php else :?>
							<?php echo JText::_('COM_PBBOOKING_BUSY');?>
						<?php endif;?>
					</td>
				<?php endforeach;?>
				<!-- end rendering caledar evailability-->

			</tr>
			<?php $shift_start->modify('+ '.$this->config->time_increment.' minutes');?>
		<?php endwhile;?>
	<?php endforeach;?>


</table>
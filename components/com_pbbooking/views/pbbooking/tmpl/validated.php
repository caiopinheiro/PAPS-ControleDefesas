<?php

/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/
 
// No direct access
 
defined('_JEXEC') or die('Restricted access'); 
	
?>

<h1><?php echo JTEXT::_('COM_PBBOOKING_SUCCESS_HEADING');?></h1>
<p><?php echo JTEXT::_('COM_PBBOOKING_VALIDATED_MESSAGE');?></p>

<p><em><?php echo JTEXT::_('COM_PBBOOKING_SUCCESS_SUB_HEADING');?></em></p>

<table>
	<tr>
		<th>
			<?php echo JTEXT::_('COM_PBBOOKING_SUCCESS_DATE');?>
		</th>
		<td>
			<?php echo JHtml::_('date',date_create($this->pending->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM),$this->config->date_format_message);?>
		</td>
	<tr><th><?php echo JTEXT::_('COM_PBBOOKING_SUCCESS_TIME');?></th><td><?php echo JHtml::_('date',date_create($this->pending->dtstart,new DateTimezone(PBBOOKING_TIMEZONE))->format(DATE_ATOM),JText::_('COM_PBBOOKING_SUCCESS_TIME_FORMAT'));?></td></tr>
	<tr><th><?php echo JTEXT::_('COM_PBBOOKING_BOOKINGTYPE');?></th><td><?php echo $this->treatment->name;?></td></tr>
	<?php if (isset($this->calendar)) :?>
		<tr><th><?php echo JTEXT::_('COM_PBBOOKING_SUCCESS_CALENDAR');?></th><td><?php echo $this->calendar->name;?></td></tr>
	<?php endif;?>
</table>

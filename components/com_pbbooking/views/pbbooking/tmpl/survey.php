<?php

/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or la<ter; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

// No direct access
 
defined('_JEXEC') or die('Restricted access'); 

$doc = &JFactory::getDocument();
$doc->addStyleSheet(JURI::root(false)."components/com_pbbooking/user_view.css");
	


?>

<h1><?php echo JText::_('COM_PBBOOKING_SURVEY_HEADING');?></h1>

<p><?php echo JTexT::_('COM_PBBOOKING_SURVEY_INTRODUCTION');?></p>

<form method="POST" action="<?php echo JRoute::_('index.php?option=com_pbbooking&task=survey');?>">
	<table id="pbbooking-survey-table">
		<?php foreach ($this->questions as $question) :?>
			<tr>
				<td><label><?php echo $question['testimonial_field_label'];?></label></td>
				<td>
					<?php echo PbGeneral::create_html_form_element($question['testimonial_field_varname'],$question['testimonial_field_type'],$question['testimonial_field_values']);?>
				</td>
			</tr>
		<?php endforeach;?>
		<tr><td colspan="2" align="center" style="text-align:center;"><input type="submit" value="<?php echo JText::_('COM_PBBOOKING_SURVEY_SUBMIT');?>"/></td></tr>
	</table>
	<input type="hidden" name="id" value="<?php echo $this->event->id;?>"/>
	<input type="hidden" name="email" value="<?php echo $this->event->email;?>"/>
	
</form>
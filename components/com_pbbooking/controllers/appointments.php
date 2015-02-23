<?php

/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or la<ter; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

// No direct access
 
defined('_JEXEC') or die('Restricted access'); 



class PbbookingControllerappointments extends JControllerLegacy

{

	public function __construct()
	{
		parent::__construct();

		//check to see whether self service is allowed on this installation
		$db = &JFactory::getDbo();
		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();

		if ($config->enable_selfservice == 0) 
			$this->setRedirect(JRoute::_('index.php?option=com_pbbooking&view=pbbooking'),JText::_('COM_PBBOOKING_SELF_SERVICE_NOT_ENABLED'));

	}

	public function display()
	{
		$db = &JFactory::getDbo();
		$user = &JFactory::getUser();
		$view = $this->getView('appointments','html');

		//load the users events
		$query = $db->getQuery(true);
		$query->select('#__pbbooking_events.*,#__pbbooking_treatments.name as name,#__pbbooking_cals.name as cal_name');
		$query->from('#__pbbooking_events')->where('#__pbbooking_events.email = "'.$db->escape($user->email).'"');
		$query->join('left','#__pbbooking_treatments on #__pbbooking_treatments.id = #__pbbooking_events.service_id');
		$query->join('left','#__pbbooking_cals on #__pbbooking_events.cal_id = #__pbbooking_cals.id');
		$query->order('#__pbbooking_events.dtstart ASC');
		$view->events = $db->setQuery($query)->loadObjectList();

		//push in data...
		$view->user = $user;
		$view->config = $db->setQuery('select * from #__pbbooking_config')->loadObject();

		//load the layout and display
		$view->setLayout('default');
		$view->display();
	}

	/**
	* deletes an appointment
	* @access public
	* @since 2.4.4
	*/

	public function delete_appt()
	{
		$input = &JFactory::getApplication()->input;
		$db = &JFactory::getDbo();
		$user = &JFactory::getUser();

		//set the error flag.
		$error = false;

		//can the user actually delete their appointments?
		if (!$user->authorise('pbbooking.deleteown','com_pbbooking')) {
			$this->setRedirect(JRoute::_('index.php?option=com_pbbooking&view=appointments'),JText::_('COM_PBBOOKING_APPOINTMENT_DELETE_NOT_AUTHORISED'));
			return;
		}

		//get the appointment id from the URL
		$a_id = $input->get('id',null,'integer');
		if ($a_id) {
			$appt = $db->setQuery('select * from #__pbbooking_events where id = '.(int)$db->escape($a_id))->loadObject();
			if ($appt && $appt->email == $user->email) {
				//delete the appointment
				$db->setQuery('delete from #__pbbooking_events where id = '.(int)$db->escape($a_id))->query();
				$this->setRedirect(JRoute::_('index.php?option=com_pbbooking&view=appointments'),JText::_('COM_PBBOOKING_SELF_SERVICE_APPT_DELETED'));				
			} else 
				$this->setRedirect(JRoute::_('index.php?option=com_pbbooking&view=appointments'),JText::_('COM_PBBOOKING_SELF_SERVICE_NO_APPT'));

		} else 
			$this->setRedirect(JRoute::_('index.php?option=com_pbbooking&view=appointments'),JText::_('COM_PBBOOKING_SELF_SERVICE_NO_APPT'));


	}
}




?>
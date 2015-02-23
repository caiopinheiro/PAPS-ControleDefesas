<?php
/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
  

class Pbbookinghelper
{

		
	/**
	* valid_appointment - ensures that appointment details provided during input are actually valid. 
	* loads up appropriate calendars and tests.
	*
	* @param array appointment details - requires an assoc array with cal_id, treatment_id, treatment_time, and date (as string) defined
	* @return bool returns a true or false to indiciate whether the appointment is valid or not
	*/
	
	static function valid_appointment($data)
	{
		$db = &JFactory::getDbo();
		$db->setQuery('select * from #__pbbooking_config');
		$config = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($data['treatment_id']));
		$treatment = $db->loadObject();
		
		$joom_config =&JFactory::getConfig();
    	date_default_timezone_set($joom_config->get('offset'));	

		//load up the calendar for the nominated treatment
		$cal = new calendar();
		$cal->loadCalendarFromDbase(array($data['cal_id']));
		
		$treatment_start = date_create($data['date'],new DateTimeZone(PBBOOKING_TIMEZONE));
		$start_time = str_split($data['treatment_time'],2);
		$treatment_start->setTime((int)ltrim($start_time[0],'0'),(int)ltrim($start_time[1],'0'));
		$treatment_end = date_create($treatment_start->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		$treatment_end->modify('+'.$treatment->duration.' minutes');
		if (!$cal->is_free_from_to($treatment_start,$treatment_end)) {
			//error_log ('cal is free');
			return true;
		} else {
			//error_log('cal is busy');
			return false;
		}
	}
	
	static function save_pending_event($data) 
	{
		//error_log('save_pending_event');
		$db = &JFactory::getDbo();
		$config =&JFactory::getConfig();
    	date_default_timezone_set($config->get('offset'));
		
		$db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($data['treatment_id']));
		$treatment = $db->loadObject();

		$db->setQuery('select * from #__pbbooking_customfields');
		$customfields = $db->loadObjectList();
		$db->setQuery('select * from #__pbbooking_customfields where is_email = 1');
		$emailfield = $db->loadObject();
		
		$dtstart = date_create($data['date'],new DateTimeZone(PBBOOKING_TIMEZONE));
		$start_time = str_split($data['treatment_time'],2);
		$dtstart->setTime((int)ltrim($start_time[0],'0'),(int)ltrim($start_time[1],'0'));
		
		$sql = sprintf('insert into #__pbbooking_pending (date,dtstart,service,verified,cal_id,email) values ("%s","%s",%s,0,%s,"%s")',
						$db->escape(date_create($data['date'],new DateTimeZone(PBBOOKING_TIMEZONE))->format('Y-m-d')),$dtstart->format(DATE_ATOM),$db->escape($data['treatment_id']),
						$db->escape($data['cal_id']),$db->escape($data[$emailfield->varname]));
		//error_log($sql);
		$db->setQuery($sql);
		$result = $db->query();
		
		if ($result) {
			$pending_id = $db->insertid();
			foreach ($customfields as $field) {
				if (isset($data[$field->varname])) {
					$sql = sprintf('insert into #__pbbooking_customfields_data (customfield_id,pending_id,data) values (%s,%s,"%s")',
									$db->escape($field->id),$pending_id,$db->escape($data[$field->varname]));
					$db->setQuery($sql);
					$db->query();
				}
			}
			Pbdebug::log_msg('save_pending_event() successful for id '.$pending_id,'com_pbbooking');
			return $pending_id;
		} else {
			Pbdebug::log_msg('save_pending_event() failed with data '.json_encode($data),'com_pbbooking');
			return false;
		}
	}
	
	/**
	* email_user - sends the validation email to the user with format defined in configuration.
	* 			- New in 2.2 this method now supports all customfield tags based on |*varname*|
	*
	* @param array data the array of appt specific data
	*/	
	static function email_user($data)
	{
		Pbdebug::log_msg('email_user() sending email to user for pending event id'.$data['pending_id'],'com_pbbooking');
		$db = &JFactory::getDbo();
		$db->setQuery('select * from #__pbbooking_config');
		$config = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_customfields where is_email = 1');
		$emailfield = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($data['treatment_id']));
		$service = $db->loadObject();
		$db->setQuery('select cf.varname,cfd.data from #__pbbooking_customfields cf,#__pbbooking_customfields_data cfd where cf.id = cfd.customfield_id and cfd.pending_id = '.$db->escape($data['pending_id']));
		$customfields = $db->loadObjectList();
		$db->setQuery('select * from #__pbbooking_pending where id = '.$db->escape($data['pending_id']));
		$pending_appt = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_cals where id = '.$db->escape($data['cal_id']))->loadObject();
		$calendar = $db->loadObject();
		
		$mailer =& JFactory::getMailer();
		$mailer_config =& JFactory::getConfig();

		$recipient = $data[$emailfield->varname];
		$bcc = null;
		if ($config->bcc_admin == 1) {
			$bcc = (isset($config->notification_email)) ? array($config->notification_email) : array($mailer_config->get('mailfrom'));
			if (isset($cal->email)) $bcc[] = $cal->email;
		}
		//error_log('JURI base(true) ='.JURI::base(true));
		//error_log('JURI base() = '.JURI::base());
		if (JURI::base(true) != '')
			$url = str_replace(JURI::base(true).'/','',JURI::base()).JRoute::_('index.php?option=com_pbbooking&task=validate&id='.$data['pending_id'].'&email='.$data[$emailfield->varname]);
		else
			$url = preg_replace('/(.*)\/$/','$1',JURI::base()).JRoute::_('index.php?option=com_pbbooking&task=validate&id='.$data['pending_id'].'&email='.$data[$emailfield->varname]);	
		Pbdebug::log_msg('email_user() replacement url = '.$url,'com_pbbooking');
		//error_log('url = '.$url);
		$urlstring = '<a href="'.$url.'">'.JTEXT::_('COM_PBBOOKING_VALIDATE_ANCHOR_TEXT')."</a>";
		
		//send email to client to let them know what is going on
		$body = self::_prepare_email('email_body',array('service_id'=>$data['treatment_id'],'dtstart'=>$pending_appt->dtstart,'url'=>$urlstring),(array)$customfields);
		self::send_email($config->email_subject,$body,$recipient,$bcc);
	}
	
	/**
	* validate_pending - looks up a pending booking and if still valid writes to the dbase.
	*
	* @param int the id of the pending booking
	* @param string the users email address or possibly null if admin validated
	* @param string the token or possibly null if user validated
	* @returns mixed returns an int with the event_id or false for failure
	*/
	
	static function validate_pending($pending_id,$email=null,$token=null)
	{
		$db = &JFactory::getDbo();
		$db->setQuery('select * from #__pbbooking_config');
		$config = $db->loadObject();
		
		$joom_config =&JFactory::getConfig();
    	date_default_timezone_set($joom_config->get('offset'));	

		
		$db->setQuery('select * from #__pbbooking_pending where id = '.$db->escape($pending_id));
		$pending = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_customfields where is_email =1');
		$customfields['email'] = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_customfields where is_first_name =1');
		$customfields['first_name'] = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_customfields where is_last_name =1');
		$customfields['last_name'] = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_customfields_data where pending_id = '.$pending->id.' and customfield_id = '.$customfields['first_name']->id);
		$customdata['first_name'] = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_customfields_data where pending_id = '.$pending->id.' and customfield_id = '.$customfields['last_name']->id);
		$customdata['last_name'] = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_customfields cf,#__pbbooking_customfields_data cfd where cf.id = cfd.customfield_id and cfd.pending_id = '.$db->escape($pending->id));
		$all_data = $db->loadObjectList();
		$db->setQuery('select * from #__pbbooking_treatments where id = '.$pending->service);
		$treatment = $db->loadObject();
		$db->setQuery('select #__pbbooking_customfields_data.*,#__pbbooking_customfields.varname from #__pbbooking_customfields_data join #__pbbooking_customfields on #__pbbooking_customfields.id = #__pbbooking_customfields_data.customfield_id where pending_id = '.$db->escape($pending_id));
		$all_fields = $db->loadAssocList(); //done to support parsing to dbase later on.
		
		$dtstart = date_create($pending->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE));
		
		$validation_arr = array('date'=>$pending->date,'treatment_time'=>$dtstart->format('Hi'),'cal_id'=>$pending->cal_id,'treatment_id'=>$pending->service);
		if (self::valid_appointment($validation_arr)) {
			if ( ($config->validation != 'admin' && $email == $pending->email) || ($config->validation == 'admin' && $token == $pending->token) ) {
				$pending->verified = 1;
				$db->updateObject('#__pbbooking_pending',$pending,'id');
				
				//create the new appt
				$dtend = date_create($dtstart->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
				$dtend->modify('+ '.$treatment->duration.' minutes');
				$summary = $treatment->name.' for '.$customdata['first_name']->data.' '.$customdata['last_name']->data;
				$description  = "";
				foreach ($all_data as $data) {
					$description .= $data->fieldname.' = '.$data->data.'\n';
				}
				$appt = new JObject;
				$appt->setProperties(array('summary'=>$summary,'dtend'=>$dtend->format(DATE_ATOM),'dtstart'=>$dtstart->format(DATE_ATOM),'description'=>$description,'service_id'=>$pending->service,'email'=>$pending->email,
											'customfields_data'=>json_encode($all_fields),'deposit_paid'=>0,'amount_paid'=>0.00));
				if ($pending->cal_id == 0) {
					$db->setQuery('select * from #__pbbooking_cals where out_cal = 1');
					$out_cal = $db->loadObject();
					$appt->set('cal_id',$out_cal->id);
				} else {
					$appt->set('cal_id',$pending->cal_id);
				}
	
				//write to database
				if ($db->insertObject('#__pbbooking_events',$appt)) {
					$event_id = $db->insertid();
					self::email_admin($event_id,$pending->id);
					Pbdebug::log_msg('validate_pending() successful validation of pending_event id '.$db->escape($pending_id),'com_pbbooking');
					if ($config->validation == 'admin') {
						//the appt is admin validated so we need to let the client know that it has been validated as well....
						$body = self::_prepare_email('admin_validation_confirmed_email_body',array('service_id'=>$treatment->id,'dtstart'=>$dtstart->format(DATE_ATOM),'url'=>null),json_decode($appt->customfields_data));
						self::send_email($config->admin_validation_confirmed_email_subject,$body,$appt->email);
					}
					return $event_id;
				}
			} else {
				return false;
			}
			
		} else {
			return false;
		}
	
	}
	
	/**
	* email admin - sends email to the administrator notifying them of a new appt in the calendar.
	*
	* @param int the id of the event in the database
	* @param int the id of the pending event in the databse
	*/
	
	static function email_admin($event_id,$pending_id)
	{
		//load up data
		Pbdebug::log_msg('email_admin: starting email of admin','com_pbbooking');
		$db = &JFactory::getDbo();
		$db->setQuery('select * from #__pbbooking_config');
		$config = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_events where id = '.$db->escape($event_id));
		$event = $db->loadObject();
		$db->setQuery('select cd.data,c.fieldname from #__pbbooking_customfields c,#__pbbooking_customfields_data cd where c.id = cd.customfield_id and cd.pending_id = '.$db->escape($pending_id));
		$customdata = $db->loadObjectList();
		$db->setQuery('select * from #__pbbooking_pending where id = '.$db->escape($pending_id));
		$pending = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($pending->service));
		$treatment = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_cals where id = '.$db->escape($pending->cal_id));
		$calendar = $db->loadObject();

		//build email
		$body = JText::_('COM_PBBOOKING_ADMIN_EMAIL_BODY');
		$body .= '<br><b>'.JText::_('COM_PBBOOKING_SUCCESS_DATE').'</b> '.JHtml::_('date',date_create($event->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM),$config->date_format_message.' '.JText::_('COM_PBBOOKING_SUCCESS_TIME_FORMAT'));
		$body .= '<br><b>'.JText::_('COM_PBBOOKING_BOOKINGTYPE').'</b> '.$treatment->name;
		$body .= '<ul>';
		$body .= '<p>'.JText::_('COM_PBBOOKING_EDIT_LINK_MSG').' ';
		$body .= '<a href="'.JURI::root(false).'/administrator/index.php?option=com_pbbooking&controller=manage&task=edit&id='.$event_id.'">';
		$body .= JURI::root(false).'/administrator/index.php?option=com_pbbooking&controller=manage&task=edit&id='.$event_id.'</a></p>';
		foreach ($customdata as $data) {
			$body .= '<li>'.$data->fieldname.'  - '.$data->data.'</li>';
		}
		$body .- '</ul>';
		Pbdebug::log_msg('email_admin: body of email said....','com_pbbooking');
		Pbdebug::log_msg($db->escape($body),'com_pbbooking');
			
		//build recipient list
		$mailer_config =& JFactory::getConfig();
		$recipient = array();
		if ($calendar->email)
			$recipient[] = $calendar->email;
		$recipient[] = (isset($config->notification_email) && $config->notification_email != '') ? $config->notification_email : $mailer_config->get('mailfrom');

		Pbdebug::log_msg('recipent array is '.json_encode($recipient),'com_pbbooking');

		//send_email($subject,$body,$recipient,$bcc=null)
		self::send_email(JText::_('COM_PBBOOKING_ADMIN_EMAIL_SUBJECT'),$body,$recipient);
	}
	
	/**
	* Is date a blocked day? - ideally this would be in the calendar model but it doesn't really fit there at present
	*
	* @param date the date
	* @returns bool whether the day is blocked or not
	*/
	
	public static function is_blocked_date($date)
	{
		$db = &JFactory::getDbo();
		$config =&JFactory::getConfig();
    	date_default_timezone_set($config->get('offset'));	

		$db->setQuery('select * from #__pbbooking_block_days');
		$block_ranges = $db->loadObjectList();
		foreach ($block_ranges as $range) {
			$block_start = date_create($range->block_start_date,new DateTimeZone(PBBOOKING_TIMEZONE))->setTime(0,0,0);
			$block_end = date_create($range->block_end_date,new DateTimeZone(PBBOOKING_TIMEZONE))->setTime(23,59,59);
			if ($date >= $block_start && $date<= $block_end) {
				return true;
			}
		}
	}

	
	/**
	* booking_for_day - returns an int with the number of bookings for nominated day
	*
	* @param datetime check_date - a datetime object containing the day to be checked
	* @return int the number of bookings for nominated date
	*/
	
	function booking_for_day($check_date = null)
	{
		$db = &JFactory::getDbo();

		//load up all the calendars
		$db->setQuery("select id from #__pbbooking_cals");
		$cals = $db->loadColumn();
		$cal = new calendar();
		$cal->loadCalendarFromDbase($cals);	
		
		$events = $cal->number_of_bookings_for_date($check_date);
		
		return $events;		
	}
	
	/**
	* busy_times_for_day - returns an array of busy times for nominated day
	*
	* @param datetime check_date - a datetime object containing the day to be checked
	* @return array array of arrays containing busy times.  array('1000','1130') - starttime / endtime
	*/	
	
	static function busy_times_for_day($check_date = null)
	{		
		$db = &JFactory::getDbo();
		$config =&JFactory::getConfig();
    	date_default_timezone_set($config->get('offset'));	
		
		$bod = date_create($check_date->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		$eod = date_create($check_date->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		$bod->setTime(0,0,0);
		$eod->setTime(23,59,59);
		
		//setup result arr..
		$busy_times = array();
		
		//firstly get list of all appts
		$db->setQuery('select * from #__pbbooking_events where dtstart >= "'.$bod->format(DATE_ATOM).'" and dtend <= "'.$eod->format(DATE_ATOM).'"');
		$appts = $db->loadObjectList();
		
		//loop through appts and add to busy_times array
		foreach ($appts as $appt) {
			$dtend = date_create($appt->dtend);
			$dtstart = date_create($appt->dtstart);
			$busy_times[] = array('start_hour'=>$dtstart->format('H'),'start_min'=>$dtstart->format('i'),
									'end_hour'=>$dtend->format('H'),'end_min'=>$dtend->format('i'));
		}
		
		return $busy_times;
	}

	/**
	* get_shift_times() - returns an assoc array of shift times
	*
	* @return array an assoc array of shift times array('shift name'=>array('start_time'=>array(start_hour,start_min),'end_time'=>array(end_hour,end_min)))
	* @since 2.2
	*/	
	
	public static function get_shift_times()
	{
		$db = &JFactory::getDbo();
		$db->setQuery('select * from #__pbbooking_config');
		$config = $db->loadObject();
		
		//get start_hour start_min end_hour end_min for groupings		
		$groupings = array();
		if ($config->time_groupings) {
			foreach (json_decode($config->time_groupings,true) as $k=>$v) {
				$start_time = str_split($v['shift_start'],2);
				$end_time = str_split($v['shift_end'],2);
				$groupings[$k] = array('start_time'=>array('start_hour'=>(int)ltrim($start_time[0],'0'),
																	'start_min'=>(int)ltrim($start_time[1],'0')),
												'end_time'=>array('end_hour'=>(int)ltrim($end_time[0],'0'),
																'end_min'=>(int)ltrim($end_time[1],'0')),
												'display_label'=>(isset($v['display_label'])) ? $v['display_label'] : $k);
			}
		}
		return $groupings;
	}
	
	/**
	* get_shift_for_appointments
	*
	* @param int id - the id of the event
	* @return string the shift label
	*/	
	
	public static function get_shift_for_appointment($id)
	{
		$db = &JFactory::getDbo();
		$db->setQuery('select * from #__pbbooking_config');
		$config = $db->loadObject();

		date_default_timezone_get($config->timezone);
		
		$db->setQuery('select * from #__pbbooking_events where id = '.$db->escape($id));
		$event = $db->loadObject();
		
		$shift_times = self::get_shift_times();
		$dtstart = date_create($event->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE));
		
		foreach ($shift_times as $k=>$v) {
			$shift_start = date_create($dtstart->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
			$shift_end = date_create($dtstart->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
			
			$shift_start->setTime($v['start_time']['start_hour'],$v['start_time']['start_min']);
			$shift_end->setTime($v['end_time']['end_hour'],$v['end_time']['end_min']);
			
			if ($dtstart>= $shift_start && $dtstart <= $shift_end) {
				return $k;
			}
		}
		
	}
	
	/**
	 * 
	 * send_admin_validation - sends a validation request to the admin to validate the appointment
	 * 
	 * @param array $data the data array containing appointment details
	 * @return boolean success or failure
	 * @since 2.2.2
	 */
	
	public function send_admin_validation($data)
	{	
		Pbdebug::log_msg('send_admin_validation() for pending event '.$data['pending_id'],'com_pbbooking');
		$db = &JFactory::getDbo();
		$config= $db->setQuery('select * from #__pbbooking_config')->loadObject();
		$service = $db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($data['treatment_id']))->loadObject();
		$customfields = $db->setQuery('select cf.varname,cfd.data from #__pbbooking_customfields cf,#__pbbooking_customfields_data cfd where cf.id = cfd.customfield_id and cfd.pending_id = '.$db->escape($data['pending_id']))->loadObjectList();
		$pending_appt = $db->setQuery('select * from #__pbbooking_pending where id = '.$db->escape($data['pending_id']))->loadObject();
		
		//create the token and update the record with the token
		$token = md5($pending_appt->id.$pending_appt->email.$pending_appt->date);
		$db->setQuery('update #__pbbooking_pending set token = "'.$token.'" where id = '.$pending_appt->id);
		$db->query();
		
		$url = sprintf("%sindex.php?option=com_pbbooking&task=validate&id=%s&token=%s",
							JURI::root(false),$pending_appt->id,$token);
		$urlstring = '<a href="'.$url.'">'.JTEXT::_('COM_PBBOOKING_VALIDATE_ANCHOR_TEXT')."</a>";

		//build the email body
		$body = self::_prepare_email('email_body',array('service_id'=>$pending_appt->service,'dtstart'=>$pending_appt->dtstart,'url'=>$urlstring),(array)$customfields);

		//build the recipent list
		$mailer_config =& JFactory::getConfig();
		$calendar = $db->setQuery('select * from #__pbbooking_cals where id = '.$db->escape($pending_appt->cal_id))->loadObject();
		$recipient = (isset($config->notification_email) && $config->notification_email !='') ? array($config->notification_email) : $mailer_config->get('mailfrom');
		if ($calendar->email) $recipient[] = $calendar->email;
		

		//send_email to admin for validation....
		self::send_email($config->email_subject,$body,$recipient);

		//send email to client to let them know what is going on
		$body = self::_prepare_email('admin_validation_pending_email_body',array('service_id'=>$pending_appt->service,'dtstart'=>$pending_appt->dtstart,'url'=>$urlstring),(array)$customfields);
		self::send_email($config->admin_validation_pending_email_subject,$body,$pending_appt->email);		
	}

	/**
	* gets valid services where there are linked calendars.  Ths method is used to fix an error where services without calendars
	* were still being displayed in the front end.
	* 
	* @return array an array of service objects
	* @since 2.3
	*/

	public static function get_valid_services()
	{
		//first get list of all services
		$db = &JFactory::getDbo();
		$db->setQuery('select * from #__pbbooking_treatments order by ordering DESC');
		$services = $db->loadObjectList();

		//loop through the arr to see if we have valids
		$ret_services = array();
		foreach ($services as $service) {
			$add_service = false;
			foreach (explode(',',$service->calendar) as $cal_id) {
				//look up the caledar in the dbase and see if it exists
				$db->setQuery('select * from #__pbbooking_cals where id = '.$cal_id);
				$cal = $db->loadObject();
				if ($cal) {
					$add_service = true;
				}
			}
			if ($add_service) {
				$ret_services[] = $service;
			}
		}
		return $ret_services;	
	}

	/**
	* get valid calendars for a specified service.  Potentially some calendars may have been deleted from the system
	* but the service not updated.  We only want to see the valid ones in the display.
	* @param int the service id
	* @return array an array for use in rendering the load calendars display
	* @since 2.3
	*/

	public static function get_calendars_for_service($service_id)
	{
		$db = &JFactory::getDbo();
		$treatment = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_config');
		$config = $db->loadObject();
		$valid_cals = array(); //an array to hold the calendars to be rendered in the response.
		
		//render the cals to push into the view later...
		$cals = explode(',',$treatment->calendar);
		foreach ($cals as $cal) {
			$db->setQuery('select * from #__pbbooking_cals where id = '.$cal);
			$calendar = $db->loadObject();
			if ($calendar) {
				$valid_cals[(string)$cal] = new calendar;
				$valid_cals[(string)$cal]->loadCalendarFromDbase(array($cal));
			}
		}

		return $valid_cals;
	}

	/**
	* gets a calendar name for a specified calendar id
	* @param int the id of the calenar
	* @return string the calendar name
	* @since 2.3
	*/

	public static function get_calendar_name_for_id($cal_id)
	{
		$db = &JFactory::getDbo();
		$db->setQuery('select * from #__pbbooking_cals where id = '.$db->escape($cal_id));
		$calendar = $db->loadObject();

		return $calendar->name;
	}

	/**
	* marks an event as recurring in the database and sets up the recurring parameters for it
	* @param int the id of the event to make recurring
	* @param assoc an array containing the recurrance settings - most likely a POST array
	* @return bool true or updated successfuly or false for failure
	* @since 2.3
	*/

	public static function make_recurring($id,$details)
	{
		//load up the event to manipulate
		$db = &JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')->from('#__pbbooking_events')->where('id = '.$db->escape($id));
		$event = $db->setQuery($query)->loadObject();

		//got the event so let's set reccurance rules
		$event->r_int = $db->escape($details['interval']);
		$event->r_freq = $db->escape($details['frequency']);
		$event->r_end = $db->escape($details['recur_end']);
		$db->updateObject('#__pbbooking_events',$event,'id');
	}

	/**
	* sends an email to the user when an event is marked for auto validation.
	* @param int the id of the event to send the validation email to
	* @return bool
	* @since 2.3
	*/

	public static function send_auto_validate_email($event_id)
	{
		Pbdebug::log_msg('send_auto_validate_email() starting auto validation email','com_pbbooking');

		$db = &JFactory::getDbo();
		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();
		$event = $db->setQuery('select * from #__pbbooking_events where id = '.$db->escape($event_id))->loadObject();
		$service = $db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($event->service_id))->loadObject();

		//send email to client to let them know what is going on
		$body = self::_prepare_email('auto_validated_appt_body',array('service_id'=>$service->id,'dtstart'=>$event->dtstart,'url'=>null),json_decode($event->customfields_data));
		self::send_email($config->auto_validated_appt_email_subject,$body,$event->email);

	}

	/**
	* a function to handle sending of mail from pbbooking.  We send a few emails to let's centralise the code
	* @param string the message subject
	* @param string the message body
	* @param string the recipient
	* @param string the bcc
	* @return bool
	* @since 2.3
	*/

	public static function send_email($subject,$body,$recipient,$bcc=null)
	{
		Pbdebug::log_msg('send_email() send email to '.$recipient,'com_pbbooking');
		Pbdebug::log_msg('send_email() email body = '.$body,'com_pbbooking');

		$mailer =& JFactory::getMailer();
		$config =& JFactory::getConfig();
		$mailer->setSender(array($config->get('mailfrom'),$config->get('fromname')));
		
		$mailer->addRecipient($recipient);
		$mailer->addBCC($bcc);
		$mailer->setSubject($subject);
		$mailer->isHTML(true);
		
		$mailer->setBody($body);
		$mailer->Send();

		return true;

	}


	/**
	* prepares an email for sending loads the template from config, parses custom field tags
	* @param string the template to load and parse
	* @param assoc array containing appointment details
	* @param assoc array containing the custom fields and data
	* @return string email body
	* @since 2.3.1
	* @access private
	*/

	private static function _prepare_email($template,$details,$customfields)
	{
		Pbdebug::log_msg('_prepare_email() using template '.$template,'com_pbbooking');
		Pbdebug::log_msg(json_encode($details),'com_pbbooking');
		Pbdebug::log_msg(json_encode($customfields),'com_pbbooking');

		$db = &JFactory::getDbo();
		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();
		$service = $db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($details['service_id']))->loadObject();

		Pbdebug::log_msg('_prepare_email() template is '.$config->$template,'com_pbbooking');
		$body = $config->$template;

		//parse custom fields tags
		foreach ($customfields as $customfield) {
			$body = preg_replace('/\|\*'.$customfield->varname.'\*\|/',$customfield->data,$body);
		}
		
		//append service details if required
		$booking_details = '';
		$booking_details .= "<p><table>";
		$booking_details .= "<tr><th>".JTEXT::_('COM_PBBOOKING_SUCCESS_DATE')."</th><td>".JHtml::_('date',date_create($details['dtstart'],new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM),$config->date_format_message);
		$booking_details .= "<tr><th>".JTEXT::_('COM_PBBOOKING_SUCCESS_TIME')."</th><td>".JHtml::_('date',date_create($details['dtstart'],new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM),JText::_('COM_PBBOOKING_SUCCESS_TIME_FORMAT'))."</td></tr>";
		$booking_details .= "<tr><th>".JTEXT::_('COM_PBBOOKING_BOOKINGTYPE')."</th><td>".$service->name."</td></tr></table></p>";
		$body = preg_replace('/\|\*booking_details\*\|/',$booking_details,$body);

		//append url string if we have it.....
		$body = preg_replace('/\|\*URL\*\|/',$details['url'],$body);

		Pbdebug::log_msg('_prepare_email() template after all replacements '.$body,'com_pbbooking');

		//return completed string
		return $body;
	}

	/**
	* receives paypal payment details from notify and marks appt as validated and saves to the datase
	* @param int the id of the pending event
	* @param float the amount of the payment
	* @since 2.4
	* @access public
	*/

	public static function confirm_paypal_payment($pending_id,$payment_gross)
	{
		Pbdebug::log_msg('confirm_paypal_paymnt() for pending_id '.(int)$pending_id,'com_pbbooking');

		$db = &JFactory::getDbo();
		$jconfig = JFactory::getConfig();
		
		$pending = $db->setQuery('select * from #__pbbooking_pending where id = '.(int)$pending_id)->loadObject();
		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();
		$customfields = $db->setQuery('select cf.varname,cfd.data from #__pbbooking_customfields cf,#__pbbooking_customfields_data cfd where cf.id = cfd.customfield_id and cfd.pending_id = '.$db->escape($pending->id))->loadObjectList();

		if ($pending) {
			//got a pending event need to validate..... 
			self::validate_pending($pending->id,$pending->email);

			//send email to client
			$body = self::_prepare_email('client_paypal_confirm',array('dtstart'=>$pending->dtstart,'url'=>null,'service_id'=>$pending->service),$customfields);
			Pbdebug::log_msg('confirm_paypal_payment() about to send emails for pending_id '.(int)$pending_id,'com_pbbooking');
			self::send_email($config->client_paypal_confirm_subject,$body,$pending->email);

			//send email to admin
			self::send_email($config->admin_paypal_confirm_subject,$config->admin_paypal_confirm,$jconfig->get('from'));

			//log a msg to let us know waht the go is for debugging....
			Pbdebug::log_msg('confirm_paypal_payment() emails sent for pending_id '.(int)$pending_id,'com_pbbooking');

		} else {
			Pbdebug::log_msg('confirm_paypal_paymnt() pending does not exist for id'.(int)$pending_id,'com_pbbooking');
		}
	}

	/**
	* checks to see whether the day is a trading day and returns true or false. adapted from method by the same name in the free version but specifically for commercial
	* @param datetime the datetime to check
	* @return bool true or false
	* @since 2.4.1
	* @access public
	*/
	public static function free_appointments_for_day($curr_day)
	{
		$db = &JFactory::getDbo();
		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();

		$trading_hours = json_decode($config->trading_hours,true);
		if ($trading_hours[$curr_day->format('w')]['status'] == 'closed')
			return false;

		if (self::is_blocked_date($curr_day))
			return false;

		return true;

	}

	/**
	* send a reminder email for the specified event using
	* @param object the event object
	* @return book true or false
	* @since 2.4.2
	* @access public
	*/

	public static function send_reminder_email_for_event($event)
	{
		$db = &JFactory::getDbo();
		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();

		$body = self::_prepare_email('reminder_email_body',array('service_id'=>$event->service_id,'dtstart'=>$event->dtstart,'url'=>null),json_decode($event->customfields_data));
		self::send_email($config->reminder_email_subject,$body,$event->email,$bcc=null);

		return true;

	}

	/**
	* send a testimonal request email for the specified event
	* @param object the event object
	* @return bool true or false
	* @since 2.4.3
	* @access public
	*/

	public static function send_testimonial_email_for_event($event)
	{
		$db = &JFactory::getDbo();
		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();


		if (JURI::base(true) != '')
			$url = str_replace(JURI::base(true).'/','',JURI::base()).JRoute::_('index.php?option=com_pbbooking&task=survey&email='.$event->email.'&id='.$$event->id);
		else
			$url = preg_replace('/(.*)\/$/','$1',JURI::base()).JRoute::_('index.php?option=com_pbbooking&task=survey&email='.$event->email.'&id='.$event->id);	
		Pbdebug::log_msg('send_testimonial_email_for_event() replacement url = '.$url,'com_pbbooking');
		$urlstring = '<a href="'.$url.'">'.JText::_('COM_PBBOOKING_SURVEY_ANCHOR_TEXT')."</a>";

		$body = self::_prepare_email('testimonial_email_body',array('url'=>$urlstring,'service_id'=>$event->service_id,'dtstart'=>$event->dtstart),json_decode($event->customfields_data));
		self::send_email($config->testimonial_email_subject,$body,$event->email,$bcc=null);

		return true;
	}


	/**
	* checks the appointments for a specified calid to determine whether there are any free appointments left.... added for one specific use case.  not really used presently....
	* @param object the day of the appointment
	* @param int the calendar id
	* @return bool true if there are false if there arent
	* @since 2.4.4
	* @access public
	*/

	public static function free_appointments_for_day_cal($curr_day,$calid)
	{
		$db = &JFactory::getDbo();
		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();
		
		//parse and set the dates.
		$dtstart = date_create($curr_day->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		$dtend = date_create($curr_day->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		$dtstart->setTime(0,0,0);
		$dtend->setTime(23,59,59);

		//init a cal object with the current date settings
		$cal = new Calendar();
		$cal->loadCalendarFromDbase(array($calid),$dtstart,$dtend);

		//now loop through the cal using the time increment to see whether there is a free time.  if there is return true....		
		/*$groupings[$k] = array('start_time'=>array('start_hour'=>(int)ltrim($start_time[0],'0'),
																	'start_min'=>(int)ltrim($start_time[1],'0')),
												'end_time'=>array('end_hour'=>(int)ltrim($end_time[0],'0'),
																'end_min'=>(int)ltrim($end_time[1],'0')),
												'display_label'=>(isset($v['display_label'])) ? $v['display_label'] : $k);*/
		$groupings = self::get_shift_times();
		foreach ($groupings as $grouping)
		{
			$dtstart->setTime($grouping['start_time']['start_hour'],$grouping['start_time']['start_min'],0);
			$dtend->setTime($grouping['end_time']['end_hour'],$grouping['end_time']['end_min'],59);
			$last_slot = date_create($dtend->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
			$last_slot->modify('- '.$config->time_increment.' minutes');
			while ($dtstart <= $last_slot)
			{
				$slot_end = date_create($dtstart->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
				$slot_end->modify('+ '.$config->time_increment.' minutes');
				if (!$cal->is_free_from_to($dtstart,$slot_end))
					return true;
				$dtstart->modify('+ '.$config->time_increment.' minutes');
			}
		}
		return false;
	}

}
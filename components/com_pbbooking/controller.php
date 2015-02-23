<?php
/**
 * @package    PurpleBeanie.PBBooking
 * @link http://www.purplebeanie.com
 * @license    GNU/GPL
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
require_once((dirname(__FILE__)).DS.'helpers'.DS.'pbbookinghelper.php');
 
class PbbookingController extends JControllerLegacy
{
	
	function __construct()
	{
		parent::__construct();
		JRequest::setVar('view','pbbooking');
		
		$config =&JFactory::getConfig();
    	date_default_timezone_set($config->get('offset'));	

    	$db = &JFactory::getDbo();
    	$db->setQuery('select * from #__pbbooking_config');
    	$this->config = $db->loadObject();


		//set locale as well...
		$language = &JFactory::getLanguage();
		$locale = $language->getLocale();		
		$str_locale = preg_replace(array('/-/','/\.utf8/'),array('_',''),$locale[0]);
		setlocale(LC_ALL,$str_locale);	

		Pbdebug::log_msg('Joomla offset = '.$config->get('offset').' PBBooking timezone = '.$this->config->timezone,'com_pbbooking');	

	}
	
    /**
     * Method to display the view
     *
     * @access    public
     */
    function display()
    {	
    	Pbdebug::log_msg('Calling display method in front end','com_pbbooking');    	
    	//load up the view
    	$view = $this->getView('PBBooking','html');
    	
    	//populate needed data into the view.
    	$db = &JFactory::getDBO();
    	
    	$view->config = $db->setQuery("select * from #__pbbooking_config")->loadObject();
    	$view->customfields = $db->setQuery("select * from #__pbbooking_customfields order by ordering desc")->loadObjectList();
		$view->calendar_message = $view->config->calendar_message;
		$view->now = date_create("now",new DateTimeZone(PBBOOKING_TIMEZONE));
		$view->treatments = Pbbookinghelper::get_valid_services();
		$view->user = &JFactory::getUser();
		$view->shift_times = PbbookingHelper::get_shift_times();
		$view->master_trading_hours = json_decode($view->config->trading_hours,true);
	    	
    	//am I passing a selected date from the view, either in the heading or in the body?
		if (JRequest::getVar('dateparam')) {
			$view->dateparam = date_create(JRequest::getVar('dateparam'),new DateTimeZone(PBBOOKING_TIMEZONE));
		} else {
			$view->dateparam = date_create("now",new DateTimeZone(PBBOOKING_TIMEZONE));
		}

		$config =&JFactory::getConfig();
    	$view->dateparam->setTimezone(new DateTimezone($config->get('offset')));
		
		//parse a valid cal from the database		
		$cals = $db->setQuery("select * from #__pbbooking_cals")->loadObjectList();
		foreach ($cals as $cal) {
			$new_cal = new calendar();
			$new_cal->loadCalendarFromDbase(array($cal->id));
			$new_cal->cal_id = $cal->id;
			$new_cal->name = $cal->name;
			$view->cals[]=$new_cal;
		}
		
		//a hack for block same say...
		if ($view->config->block_same_day == 1 && date_create("now",new DateTimeZone(PBBOOKING_TIMEZONE))->format("z") == $view->dateparam->format("z") ) {
			$view->dateparam->modify("+1 day");
		}
		
    	//choose the view depending on which one the config is set to use and whether the user is authorised
    	if ($view->user->authorise('pbbooking.browse','com_pbbooking'))
			$view->setLayout(($view->config->multi_page_checkout == 0) ? 'calendar' : 'multipagecheckout');
		else
			$view->setLayout('notauthorised');

		//display the view....
    	$view->display();			
    }
    
    /**
     * 
     * saves the appointment to the pending_events table and routes validation emails
     * 
     */
    	
	function save()
	{
		$db =& JFactory::getDBO();
		$db->setQuery('select * from #__pbbooking_customfields');
		$customfields = $db->loadObjectList();		
		$db->setQuery('select * from #__pbbooking_config');
		$config = $db->loadObject();
		$user = &JFactory::getUser();

		//check if user can save an appointment and bail early if they can't.
		if (!$user->authorise('pbbooking.create','com_pbbooking')) {
			$this->setRedirect('index.php?option=com_pbbooking',JText::_('COM_PBBOOKING_LOGIN_MESSAGE_CREATE'));
			return;
		}

		//load up the appointment data in an array.
		$data = array();
		$error = false;
		foreach ($customfields as $field) {
			if (JRequest::getVar($field->varname)) {
				$data[$field->varname] = is_array(JRequest::getVar($field->varname)) ? implode('|',JRequest::getVar($field->varname)) : JRequest::getVar($field->varname);
			} else if ($field->is_required == 1) {
				$error = true;
			}
		}
		
		$data['treatment_id'] = JRequest::getInt('treatment_id',0);
		$data['date'] = JRequest::getVar('date',"");
		$data['treatment_time'] = JRequest::getVar('treatment-time');
		$data['cal_id'] = JRequest::getInt('cal_id');
		
		//redirect on error or missing data but only if it's not an ajax request
		if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			if ($error || $data['treatment_id'] == 0 || $data['date'] == "" || $data['treatment_time'] == "") {
				$this->setRedirect('index.php?option=com_pbbooking',JText::_('COM_PBBOOKING_MISSING_DATA'));
				return;
			}
		} else {
			$config->validation = 'paypal';
		}
		
		//verify the appointment is actually available 
		$valid = Pbbookinghelper::valid_appointment($data);

		if ($valid) {
			//create pending event and email user
			$pending_id = Pbbookinghelper::save_pending_event($data);
			if ($pending_id) {
				$data['pending_id'] = $pending_id;
				
				//switch statement to handle the different validation types.
				switch ($config->validation) {
					case 'client':
						Pbbookinghelper::email_user($data);
						
						//now redirect - load up the view
						$view = $this->getView('PBBooking','html');
						$view->setLayout('success');
						
						//populate needed data into the view.
						$db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($data['treatment_id']));
						$view->service = $db->loadObject();
						$view->config = $config;
						$db->setQuery('select * from #__pbbooking_pending where id = '.$db->escape($pending_id));
						$view->pending = $db->loadObject();
						break;
					case 'auto':
						$db->setQuery('select * from #__pbbooking_customfields where is_email = 1');
						$emailfield = $db->loadObject();
						$this->setRedirect(JRoute::_('index.php?option=com_pbbooking&task=validate&id='.$data['pending_id'].'&email='.$data[$emailfield->varname]));
						return;
						break;
					case 'admin':
						Pbbookinghelper::send_admin_validation($data);
						
						//now redirect - load up the view
						$view = $this->getView('PBBooking','html');
						$view->setLayout('success');
						
						//populate needed data into the view.
						$db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($data['treatment_id']));
						$view->service = $db->loadObject();
						$view->config = $config;
						$db->setQuery('select * from #__pbbooking_pending where id = '.$db->escape($pending_id));
						$view->pending = $db->loadObject();
						break;
					case 'paypal':
						break;
				}

				//display the view but only if it's not an XMLHTTPRequest
				if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
					$view->display();
				} else {
					echo json_encode(array('id'=>$pending_id,'status'=>'success','notify_url'=>JRoute::_('index.php?option=com_pbbooking&task=notify')));
				}
					    	
			} else {
				$this->setRedirect('index.php?option=com_pbbooking',JText::_('COM_PBBOOKING_BOOKING_PROBLEM'));				
			}
		} else {
			$this->setRedirect('index.php?option=com_pbbooking',JText::_('COM_PBBOOKING_BOOKING_PROBLEM'));
		}
	}
	
	function validate() {
		
		$pendingid = JRequest::getVar('id');
		$email = JRequest::getVar('email',null);
		$token = JRequest::getVar('token',null);
		
		$event_id = Pbbookinghelper::validate_pending($pendingid,$email,$token);
		if ($event_id) {
			
			//load up the view
			$view = $this->getView('PBBooking','html');
			$view->setLayout('validated');
			
			//populate the view with data
			$db=&JFactory::getDBO();
			$db->setQuery('select * from #__pbbooking_pending where id = '.$db->escape($pendingid));
			$view->pending = $db->loadObject();
			$db->setQuery("select * from #__pbbooking_treatments where id = ".$view->pending->service);
			$view->treatment = $db->loadObject();
			$db->setQuery("select * from #__pbbooking_cals where id = ".$view->pending->cal_id);
			$view->calendar = $db->loadObject();
			$db->setQuery('select * from #__pbbooking_config');
			$view->config = $db->loadObject();
			$db->setQuery('select * from #__pbbooking_events where id= '.$event_id);
			$view->event = $db->loadObject();

			//check if the appt is sent to auto validate as if it is we need to email the user
			if ($view->config->validation == 'auto') {
				Pbdebug::log_msg('validate() sending auto validation email for event id '.$event_id,'com_pbbooking');
				Pbbookinghelper::send_auto_validate_email($event_id);
			}
			
			//display the view
			$view->display();
		} else {
			$this->setRedirect('index.php?option=com_pbbooking',JText::_('COM_PBBOOKING_VALIDATION_PROBLEM'));
		}
	}
	
	function error() {
		//$this->setLayout('fail');
		JRequest::setVar('layout','fail');
		parent::display();
	}
	
		
	/**
	* Subscribe function
	*
	* @param int via JRequest picks up the cal_id to load the approriate calendar
	* @return string Outputs the ics formatted calendar from the database
	*/
	
	function subscribe()
	{
		$view = $this->getView("Pbbooking","raw");
		$db = &JFactory::getDbo();
		$db->setQuery('select * from #__pbbooking_config');
		$config = $db->loadObject();
		
		//pump vars into view....
		$cal_id = JRequest::getInt('cal_id',0);
		if ($cal_id !=0 && JRequest::getVar('subscribe_secret',"") == $config->subscribe_secret && $config->allow_subscribe == 1) {
			$view->setLayout('ics_view');
			$db->setQuery("select * from #__pbbooking_cals where id = ".$db->escape($cal_id));
			$view->cal = $db->loadObject();

			$db->setQuery('select * from #__pbbooking_events where cal_id = '.$db->escape($cal_id));
			$view->events = $db->loadObjectList();
			$view->display();
		} else {
			echo JText::_('COM_PBBOOKING_SUBSCRIBE_MSG');
		}
				

	}
	
	/**
	* load_slots_for_day($date,$grouping,$treatment) - passes back a JSON encoded data for the browser to view time slots
	*
	* @param string $date - the date to return data for
	* @param string $grouping - the grouping to return data for
	* @param int $treatment - the treatement_id to return data for
	* @return string an html string to inject into the dom at the view
	*/	
	
	function load_slots_for_day()


	{
		$config =&JFactory::getConfig();
		$db = &JFactory::getDbo();
		$pbbooking_config = $db->setQuery('select * from #__pbbooking_config')->loadObject();

    	date_default_timezone_set($config->get('offset'));	

		//{'date':date,'option':'com_pbbooking','task':'load_slots_for_day','format':'raw','grouping':grouping,'treatment':treatment}')
		$date = date_create(JRequest::getVar('date'),new DateTimeZone(PBBOOKING_TIMEZONE));
		$date->setTimezone(new DateTimeZone($config->get('offset')));
		
		Pbdebug::log_msg('load_slots_for_day() using date '.$date->format(DATE_ATOM),'com_pbbooking');
		Pbdebug::log_msg('load_slots_for_day() $config->enable_shifts = '.$pbbooking_config->enable_shifts,'com_pbbooking');

		$grouping = JRequest::getWord('grouping');
		$treatment_id = JRequest::getInt('treatment');
		$db->setQuery('select * from #__pbbooking_treatments where id ='.$db->escape((int)$treatment_id));
		$treatment=$db->loadObject();
		$valid_cals = Pbbookinghelper::get_calendars_for_service($treatment_id);

		//get start_hour start_min end_hour end_min for groupings		
		Pbdebug::log_msg('load_slots_for_day() $this->config->time_groupings = '.$this->config->time_groupings,'com_pbbooking');
		Pbdebug::log_msg('load_slots_for_day() $grouping = '.$grouping,'com_pbbooking');
		$groupings = Pbbookinghelper::get_shift_times();
		
		//push vars into view
		$view = $this->getView('Pbbooking','raw');
		$view->setLayout('individual_freeflow_view_calendar');
		$view->date_start = date_create($date->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		$view->date_start->setTimezone(new DateTimezone($config->get('offset')));
		$view->date_end = date_create($date->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		$view->date_end->setTimezone(new DateTimezone($config->get('offset')));
		$view->config = $this->config;

		if ($pbbooking_config->enable_shifts == 1) {
			$view->date_start->setTime($groupings[$grouping]['start_time']['start_hour'],$groupings[$grouping]['start_time']['start_min'],'0');
			$view->date_end->setTime($groupings[$grouping]['end_time']['end_hour'],$groupings[$grouping]['end_time']['end_min'],'0');
		} else {
			$view->date_start->setTime(0,0,0);
			$view->date_end->setTime(23,59,59);
		}
		
		Pbdebug::log_msg('load_slots_for_day() $view->date_start = '.$view->date_start->format(DATE_ATOM),'com_pbbooking');
		Pbdebug::log_msg('load_slots_for_day() $view->date_end = '.$view->date_end->format(DATE_ATOM),'com_pbbooking');	

		$view->time_increment = $this->config->time_increment;
		$view->treatment = $treatment;
		$view->cals = $valid_cals;	
		
		//render view
		$view->display();

	}

	/**
	* load_paypal_form renders the paypal form nased on the service and passes back to the browser
	* @since 2.4
	* @access public
	*/

	public function load_paypal_form()
	{
		$db = &JFactory::getDbo();
		$input = &JFactory::getApplication()->input;

		$service_id = $input->get('service',0,'integer');

		$service = $db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($service_id))->loadObject();

		$html = Pbbookingpaypalhelper::build_form_for_service($service);
		
		echo $html;
	}

	/**
	* paypalpending is the return page from a paypal transaction that just renders a thank you message and advises the user they will receive an email
	* @since 2.4
	* @access public
	*/

	public function paypalpending()
	{
		$view = $this->getView('Pbbooking','html');
		$view->setLayout('paypalpending');
		$view->display();
	}

	/**
	* receives the notificiations from paypal of a successful transaction (via) IPN and processes accordingly
	* @access public
	* @since 2.4
	*/

	public function notify()
	{
		Pbdebug::log_msg('notify() - received paypal notification for pending event id'.(int)$_POST['custom'],'com_pbbooking');
		$db = &JFactory::getDbo();
		$input = &JFactory::getApplication()->input;
		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();


		//got the ipn so send it back to paypal for validation
		$url = ($config->paypal_test && $config->paypal_test == 1) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_notify-validate' : 'https://www.paypal.com/cgi-bin/webscr?cmd=_notify-validate';
        foreach ($_POST as $key=>$data) {
            $url.='&'.$key.'='.urlencode($data);
        }
        Pbdebug::log_msg('notify() - doing curl to url '.$url,'com_pbbooking');

        //now do curl....
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        //error_log('got paypal ipn');
        if (preg_match('/VERIFIED/',$response)) {
        	Pbdebug::log_msg('notify() - transaction for '.(int)$_POST['custom'].' is verified.','com_pbbooking');
            //all is good with paypal transaction....
            $pending_id = $input->get('custom',null,'string');
            $payment_gross = $input->get('mc_gross',null,'string');

            //process booking payment mark as validated and email client / admin accordingly....
            Pbbookinghelper::confirm_paypal_payment($pending_id,$payment_gross);
            //error_log('verified order '.$order_id);
        } else {
            Pbdebug::log_msg('notify() - transaction for '.(int)$_POST['custom'].' failed','com_pbbooking');
        }

        //close the curl session....
        curl_close($ch);
	}

	/**
	* renders the day view based on the free version layout. based on the code from the feww version with modifications.
	* @access public
	* @since 2.4.1
	*/

	public function dayview()
	{
		$db = &JFactory::getDbo();
		$input = &JFactory::getApplication()->input;

		//load the view
		$view = $this->getView('pbbooking','html');

		//getparam
		$view->config = $db->setQuery('select * from #__pbbooking_config')->loadObject();
		$view->dateparam = date_create($input->get('dateparam','now','string'),new DateTimeZone(PBBOOKING_TIMEZONE));

		//load up the calendars....
		$cals = $db->setQuery('select * from #__pbbooking_cals')->loadObjectList();
		$view->cals = array();
		foreach ($cals as $i=>$cal) {
			$view->cals[$i] = new Calendar();
			$view->cals[$i]->loadCalendarFromDbase(array($cal->id)); 
			$view->cals[$i]->name = $cal->name;
		}

		//calc start and end day
		$opening_hours = json_decode($view->config->trading_hours,true);
		$view->opening_hours = $opening_hours[(int)$view->dateparam->format('w')];
		$start_time_arr = str_split($opening_hours[(int)$view->dateparam->format('w')]['open_time'],2);
		$end_time_arr = str_split($opening_hours[(int)$view->dateparam->format('w')]['close_time'],2);
		$view->day_dt_start = date_create($input->get('dateparam','now','string'),new DateTimezone(PBBOOKING_TIMEZONE));
		$view->day_dt_end = date_create($input->get('dateparam','now','string'),new DateTimezone(PBBOOKING_TIMEZONE));
		$view->day_dt_start->setTime($start_time_arr[0],$start_time_arr[1],0);
		$view->day_dt_end->setTime($end_time_arr[0],$end_time_arr[1],0);
		$view->user = &JFactory::getUser();

		if ($view->config->enable_shifts == 1) {
			//load the shifts in ... we may not use them depends on the config.....
			$view->shifts = Pbbookinghelper::get_shift_times();
			$view->setLayout('dayviewinshifts');
		} else {
			$view->setLayout('dayview');
			$view->last_slot_for_day = date_create($view->day_dt_end->format(DATE_ATOM),new DateTimezone(PBBOOKING_TIMEZONE));
			$view->last_slot_for_day->modify('- '.$view->config->time_increment.' minutes');
		}

		//change the layout if the user is not authorised.
    	if (!JFactory::getUser()->authorise('pbbooking.browse','com_pbbooking'))
			$view->setLayout('notauthorised');

		//render the display
		$view->display();
	}

	/**
	* responds to the create task
	* @param string the date and time from the command ?dtstart=201209041030
	* @param string the cal_id &cal=1
	* @since 2.4.1
	* @access public
	*/
	public function create()
	{
		$input = &JFactory::getApplication()->input;
		$db = &JFactory::getDbo();
		$view = $this->getView('pbbooking','html');
		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();

		//push the dateparam into the view now cause we need it so often...
		$view->dateparam = date_create($input->get('dtstart',null,'string'), new DateTimeZone(PBBOOKING_TIMEZONE));

		//check if I'm working with shifts and get the relevant shift
		if ($config->enable_shifts == 1) {
			$shifts = Pbbookinghelper::get_shift_times();
			foreach ($shifts as $label=>$shift) {
				$shift_start = date_create($view->dateparam->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
				$shift_end = date_create($view->dateparam->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
				$shift_start->setTime($shift['start_time']['start_hour'],$shift['start_time']['start_min'],0);
				$shift_end->setTime($shift['end_time']['end_hour'],$shift['end_time']['end_min'],0);
				if ( $view->dateparam >= $shift_start && $view->dateparam <= $shift_end )
					$view->closing_time = date_create($shift_end->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
			}
		} else {
			$opening_hours = json_decode($config->trading_hours,true);		
			$closing_time_arr = str_split( $opening_hours[date_create($view->dateparam->format(DATE_ATOM),new DateTimezone(PBBOOKING_TIMEZONE))->format('w')]['close_time'],2 );
			$view->closing_time = date_create($input->get('dtstart',null,'string'),new DateTimeZone(PBBOOKING_TIMEZONE));
			$view->closing_time->setTime($closing_time_arr[0],$closing_time_arr[1],0);
		}
		
		$dateparam = $input->get('dtstart',date_create('now',new DateTimeZone(PBBOOKING_TIMEZONE))->format('YmdHi'),'string');
		$cal_id = $input->get('cal_id',0,'integer');
		$opening_hours = json_decode($config->trading_hours,true);
		$closing_time_arr = str_split( $opening_hours[date_create($dateparam,new DateTimezone(PBBOOKING_TIMEZONE))->format('w')]['close_time'],2 );
		
		$view->dateparam = date_create($dateparam,new DateTimeZone(PBBOOKING_TIMEZONE));
		$view->customfields = $db->setQuery('select * from #__pbbooking_customfields order by ordering desc')->loadObjectList();
		$view->treatments = $db->setQuery('select * from #__pbbooking_treatments order by ordering desc')->loadObjectList();
		$view->cal = new Calendar();
		$view->cal->loadCalendarFromDbase(array((int)$cal_id));

		$view->config = $config;

		$view->setLayout('create');
		$view->display();
	}

	/**
	* runs any pending cron jobs such as reminders etc
	* @access public
	* @since 2.4.2
	*/

	public function cron()
	{
		$db = &JFactory::getDbo();
		$view = $this->getView('pbbooking','html');
		$view->setLayout('cron');

		$view->config = $db->setQuery('select * from #__pbbooking_config')->loadObject();
		if ($view->config->enable_cron) {

			//log for tracking / auditing purposes....
			Pbdebug::log_msg('cron(): running cron by web request at '.date_create("now",new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM).' from client '.$_SERVER['REMOTE_ADDR'],'com_pbbooking');

			//what cron tasks do i need to do?
			if ($view->config->enable_reminders == 1) {
				Pbdebug::log_msg('cron(): got task enable_reminders','com_pbbooking');
				$reminder_details = json_decode($view->config->reminder_settings,true);
				$date_from = date_create("today",new DateTimeZone(PBBOOKING_TIMEZONE));
				$date_from->modify('+ '.$reminder_details['reminder_days_in_advance'].' days');
				$date_to = date_create($date_from->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
				$date_to->setTime(23,59,59);
				Pbdebug::log_msg('cron(): going to run task enable_reminders for date range $date_from '.$date_from->format(DATE_ATOM).' $date_to '.$date_to->format(DATE_ATOM),'com_pbbooking');

				//get all the events I should send for...
				$events = $db->setQuery('select * from #__pbbooking_events where dtstart >= "'.$date_from->format(DATE_ATOM).'" and dtstart <= "'.$date_to->format(DATE_ATOM).'"')->loadObjectList();
				Pbdebug::log_msg('cron(): found '.count($events).' events to send reminders for','com_pbbooking');

				//loop through all the events and send the reminder...
				foreach ($events as $event) {
					if ($event->reminder_sent == 0) {
						Pbdebug::log_msg('cron(): sending reminder for event with id '.$event->id,'com_pbbooking');
						if (Pbbookinghelper::send_reminder_email_for_event($event))
							//update the event with the status....
							$db->updateObject('#__pbbooking_events',new JObject(array('id'=>$event->id,'reminder_sent'=>1)),'id');
					}
				}
			}

			if ($view->config->enable_testimonials) {
				Pbdebug::log_msg('cron(): got task enable_testimonials','com_pbbooking');
				$date_from = date_create("today",new DateTimeZone(PBBOOKING_TIMEZONE));
				$date_from->modify('- '.$view->config->testimonial_days_after.' days');
				$date_from->setTime(0,0,0);
				$date_to = date_create($date_from->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
				$date_to->setTime(23,59,59);

				//get all the events....
				$events = $db->setQuery('select * from #__pbbooking_events where dtstart >= "'.$date_from->format(DATE_ATOM).'" and dtstart <= "'.$date_to->format(DATE_ATOM).'"')->loadObjectList();
				Pbdebug::log_msg('cron(): going to run task enable_testimonials for date range $date_from '.$date_from->format(DATE_ATOM).' $date_to '.$date_to->format(DATE_ATOM),'com_pbbooking');

				foreach ($events as $event) {
					if ($event->testimonial_request_sent == 0) {
						Pbdebug::log_msg('cron(): sending testimonial request for event with id '.$event->id,'com_pbbooking');
						if (Pbbookinghelper::send_testimonial_email_for_event($event))
							$db->updateObject('#__pbbooking_events',new JObject(array('id'=>$event->id,'testimonial_request_sent'=>1)),'id');
					}
				}
			}

			$view->display();
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_pbbooking'),JText::_('COM_PBBBOOKING_CRON_NOT_ENABLED'));
		}
	}


	/**
	* loads and presents a survey for the user to complete. 
	* @access public
	* @since 2.4.3
	*/

	public function survey()
	{
		$db = &JFactory::getDbo();
		$input = &JFactory::getApplication()->input;
		$view = $this->getView('pbbooking','html');

		//define an error flag to prevent dodginess
		$error = false;

		//load the user survey object up and check if there is actually an event with the nominated id and whether the email matches
		$email = $input->get('email',null,'string');
		$id = $input->get('id',null,'integer');
		if (!$email && !$id)
			$error = true;
		else {
			//load up the config & the event
			$event = $db->setQuery('select * from #__pbbooking_events where id = '.(int)$id)->loadObject();
			$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();
			if ($event->email != $email)
				$error = true;
		}

		if ($_SERVER['REQUEST_METHOD'] == 'GET' && !$error) {
			$view->setLayout('survey');
			$view->event = $event;
			$view->config = $config;
			$view->questions = json_decode($config->testimonial_questions,true);
			$view->display();
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$error)
		{
			$survey = $this->getModel('Survey');
			$s_response = array();
			$s_response['event_id'] = $id;

			foreach (json_decode($config->testimonial_questions,true) as $question)
				$s_response['content'][$question['testimonial_field_varname']] = $input->get($question['testimonial_field_varname'],null,'string');
			$s_response['content'] = json_encode($s_response['content']);
			$s_response['submission_ip'] = $_SERVER['REMOTE_ADDR'];

			if ($survey->save_survey($s_response)) {
				$j_config = &JFactory::getConfig();
				PbbookingHelper::send_email(JText::_('COM_PBBOOKING_EMAIL_NEW_SURVEY_SUBJECT'),JText::_('COM_PBBOOKING_EMAIL_NEW_SURVEY_BODY'),$j_config->get('mailfrom'),$bcc=null);
				$this->setRedirect('index.php',JText::_('COM_PBBOOKING_SURVEY_SUCCESS'));
			}
			else
				$error = true;
		}

		if ($error)
			$this->setRedirect('index.php',JText::_('COM_PBBOOKING_SURVEY_ERROR'));

	}
	

}
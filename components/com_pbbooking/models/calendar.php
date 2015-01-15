<?php

/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/


//class definition for event
class calendar

{

public $events;
public $kTimeslots;
public $cal_id;
public $name;

function __construct() {

	$db=&JFactory::getDBO();
	
	//set the timezone for the calendars
	$config =&JFactory::getConfig();
    date_default_timezone_set($config->get('offset'));	
	
	//set a default cal_id;
	$this->cal_id = -1;
}


/**
* Loads the events from the database
*
* @param array An array of calendar ID's to be parsed
* @param datetime an optional starting date from
* @param datetime an optional starting date to
* @return nil loads $this->events with appointments
* @since 2.1
*/

function loadCalendarFromDbase($cals,$dtfrom=null,$dtto = null)
{
	//set the cal_id for future calendars
	$this->cal_id = (count($cals)>1) ? 0 : $cals[0];

	$db = &JFactory::getDBO();
	$events = array();
	
	foreach($cals as $cal) {
		if ($dtfrom && $dtto) {
			Pbdebug::log_msg('loadCalendarFromDbase() - $dtfrom and $dtto are set','com_pbbooking');
			$db->setQuery('select * from #__pbbooking_events where cal_id = '.$db->escape($cal).' and dtstart>= "'.$dtfrom->format(DATE_ATOM).'" and dtstart<= "'.$dtto->format(DATE_ATOM).'"');	
		} else {
			Pbdebug::log_msg('loadCalendarFromDbase() - no override dates set','com_pbbooking');
			$db->setQuery("select * from #__pbbooking_events where cal_id = ".$db->escape($cal));
		}
		$cal_events = $db->loadObjectList();
		if (count($cal_events) > 0 ) {
			foreach ($cal_events as $cal_event) {
				//$date_string = date(DATE_ATOM,$cal_event->dtend);
				$event = new event();
				$event->summary = $cal_event->summary;
				$event->dtend = date_create($cal_event->dtend,new DateTimeZone(PBBOOKING_TIMEZONE));
				$event->dtstart = date_create($cal_event->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE));
				$event->description = $cal_event->description;
				$event->id = $cal_event->id;
				$event->r_int = $cal_event->r_int;
				$event->r_end = $cal_event->r_end;
				$event->r_freq = $cal_event->r_freq;
				$events[] = $event;
			}
		}
	}
	
	$this->events = $events;
}

/**
* loads a calendar from a nominated ics file
* @param array the cal ids to parse
* @return calendar
* @deprecated 2.1 replaced by loadCalendarFromDbase
*/

function parseCalendar($cals) {


	$events = Array();
	
	foreach($cals as $cal)
	{
		$path_parts = pathinfo($cal);
		if ($path_parts['dirname'] == '.') {
			#the cal is in the component subdir so add relevant info to path
			$realpath = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_pbbooking'.DIRECTORY_SEPARATOR.$cal;
			$cal = $realpath;
		}
		
		$file = @fopen($cal,"r");
	
		$string = "";

		$inevent = false;
		$i = 0;


		$curr_summary = "";
		$curr_description = "";
		$curr_dtend = "";
		$curr_dtstart = "";
		$recurring = false;
		$recarr = Array();

		while(!feof($file)) {
	
			$line = fgets($file);
	
			if (preg_match("/^BEGIN:VEVENT/",$line)==1) {
				//we have started an event
				$inevent = true;
				$i = $i +1;
			}
	
			if ($inevent) {
				if (preg_match("/^SUMMARY:/",$line) == 1) {
					$curr_summary = str_replace('SUMMARY:',"",$line);
				}
				/*if (preg_match("/^DTEND;TZID=/",$line) == 1) {
					//this captures on cals where we have TZID in the code
					$temp = str_replace('DTEND;TZID='.$this->timezone.':',"",$line);
					$curr_dtend= date_create($temp);
				}*/
		
				/*if (preg_match("/^DTSTART;TZID=/",$line) == 1) {
					//echo this captues on cals where we have TZID in the code
					$temp = str_replace("DTSTART;TZID=".$this->timezone.":","",$line);
					$curr_dtstart= date_create($temp);
				}*/
				
				if (preg_match("/^DTEND;TZID=.*:(.*)\n/",$line,$matches)) {
					$curr_dtend = date_create($matches[1],new DateTimeZone(PBBOOKING_TIMEZONE));
				}

				if (preg_match("/^DTSTART;TZID=.*:(.*)\n/",$line,$matches)) {
					$curr_dtstart = date_create($matches[1],new DateTimeZone(PBBOOKING_TIMEZONE));
				}

				if (preg_match("/^DTEND;VALUE=DATE-TIME/",$line) == 1) {
					//this captures on cals where we have zulu time
					$temp = str_replace('DTEND;VALUE=DATE-TIME:',"",$line);
					//$temp1=str_replace('Z',"",$temp);
					$curr_dtend= date_create($temp,new DateTimeZone(PBBOOKING_TIMEZONE));
					
				}
				
				if (preg_match("/^DTSTART;VALUE=DATE-TIME/",$line) == 1) {
					//this captures on cals where we have zulu time
					$temp = str_replace('DTSTART;VALUE=DATE-TIME:',"",$line);
					$curr_dtstart= date_create($temp,new DateTimeZone(PBBOOKING_TIMEZONE));
				}
				
				if (preg_match("/^RRULE:/",$line)==1) {
					//we are in a recurring event
					$temp = str_replace('RRULE:',"",$line);
					$recarr = explode(";",$temp);
					$recurring = true;
				}
				
				if (preg_match("/^DESCRIPTION:/",$line)==1) {
					//get description of event
					$curr_description = $line;
				}

		
				if (preg_match("/^END:VEVENT/",$line) == 1) {
					//we have reached the close of the event
					$inevent = false;
					//$event = Array("summary" => $curr_summary, "dtend" => $curr_dtend, "dtstart"=>$curr_dtstart);
					$event = new event();
					$event->summary = $curr_summary;
					$event->dtend = $curr_dtend;
					$event->dtstart = $curr_dtstart;
					$event->description = $curr_description;
					if ($recurring) {
						$recurring = false;
						$interval = str_replace('INTERVAL=',"",$recarr[1]);
						
						//is until defined?
						if (isset($recarr[2])) {
							if (preg_match("/^UNTIL=/",$recarr[2])==1) {
								$temp=str_replace('Z',"",$recarr[2]);
								$untilstring = str_replace("UNTIL=","",$temp);
								$untildate= date_create($untilstring,new DateTimeZone(PBBOOKING_TIMEZONE));
								$event->recurring = Array ("frequency"=>"week","interval"=>$interval,"until"=>$untildate);
							}
						} else {
							$event->recurring = Array ("frequency"=>"week","interval"=>$interval,"until"=>0);
						}
						
						
					}
					$events[]=$event;
				}
			}	
		}
	}
	
	foreach($events as $row) {
		$dtstart[]=$row->dtstart;
	}

	//gives me an array of events going newest to oldest
	if (count($events) > 0) {
		array_multisort($dtstart,SORT_DESC,$events);
	}
	
	$this->events = $events;
}

/**
* returns any events booked on a given date in a given timeslot
* @param the date to check
* @param the slot to set
* @return the booked event or null
* @deprecated this is no longed used since moving to freeflow in 2.2.  Use is_free_from_to instead
*/

function isFree($date,$timeslot){
	
	$db=&JFactory::getDbo();
	$config =&JFactory::getConfig();
    date_default_timezone_set($config->get('offset'));	


	$eventstart = date_create($date->format('Y-m-d'),new DateTimeZone(PBBOOKING_TIMEZONE));
	$eventend = date_create($date->format('Y-m-d'),new DateTimeZone(PBBOOKING_TIMEZONE));
	
	$db = &JFactory::getDBO();
	$db->setQuery("select * from #__pbbooking_slots where id = ".$db->escape($timeslot));
	$slot = $db->loadObject();
	
	$eventstart->setTime($slot->start_hour,$slot->start_min);
	$eventend->setTime($slot->end_hour,$slot->end_min,1);
	
		
	$free = true;
	$bookedEvent = null;
	
	foreach($this->events as $event) {
		
		$event->dtend->modify("-1 second");
		
		if ($event->dtend >= $eventstart && $event->dtend <= $eventend) {
			$free = false;
			$bookedEvent = date_create($event->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		}
		
		//check for multi day events
		if ($event->dtstart <= $eventstart && $event->dtend >= $eventend) {
			$free = false;
			$bookedEvent = date_create($event->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		}
		
		//check for recurrance
		if (isset($event->r_int)) {
			//we have a recurring event...
			$dt_until = date_create($event->r_end,new DateTimeZone(PBBOOKING_TIMEZONE));
			$dt_until->setTimezone(new DateTimeZone($config->get('offset')));
			$dt_until->setTime(23,59,59);

			/*while ($event->dtstart < $dt_until) {

			}*/
			
			
		}
	}
	
	return $bookedEvent;
}

/**
* writes an event out to the ics file
* @param event the event to write
* @param the calfile to write to
* @deprecated 2.1
*/

function writeEvent($event,$calfile) {
	global $kOutcal;
	//BEGIN:VEVENT
	//DTEND;VALUE=DATE-TIME:20100426T172429Z
	//DTSTART;VALUE=DATE-TIME:20100426T162429
	//SUMMARY:New Event
	//END:VEVENT
	
	$path_parts = pathinfo($calfile);
	if ($path_parts['dirname'] == '.') {
		#the cal is in the component subdir so add relevant info to path
		$realpath = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_pbbooking'.DIRECTORY_SEPARATOR.$calfile;
		$calfile = $realpath;
	}
	
	$file = @fopen($calfile,"r");
	$lines = Array();
	$i = 0;
	$insert=0;
	while(!feof($file)) {
		//read entire file into memory and if line == BEGIN:VTIMEZONE then note the line in $i
		$line = fgets($file);
		if (preg_match("/^BEGIN:VTIMEZONE/",$line) == 1) {
			$insert=$i;
		}
		$i++;
		$lines[]=$line;
	}
	fclose($file);
	
	//slice array from insert to end
	//add elements recursively to end of array and merge again
	$arr1 = array_slice($lines,0,$insert);
	$arr2 = array_slice($lines,$insert,(count($lines)-$insert));
	//pop new elements to end of $arr1
	$arr1[] = "BEGIN:VEVENT\n";
	$arr1[] = sprintf("DTEND;VALUE=DATE-TIME:%sT%s\n",date_format($event->dtend,'Ymd'),date_format($event->dtend,'His'));
	$arr1[] = sprintf("DTSTART;VALUE=DATE-TIME:%sT%s\n",date_format($event->dtstart,'Ymd'),date_format($event->dtstart,'His'));
	$arr1[]=sprintf("SUMMARY:%s\n",$event->summary);
	$arr1[]="DESCRIPTION:$event->description\n";
	$arr1[]="END:VEVENT\n";
	
	$lines = array_merge($arr1,$arr2);
	
	$file = @fopen($calfile,"w");
	foreach($lines as $line) {
		fwrite($file,$line);
	}
	
	fclose($file);
}


/**
* Writes a validated event to the database
*
* @param event The event to be written to the calendar
* @return bool success or failure
* @since 2.1
*/


function writeEventToDatabase($event,$cal_id)
{
	
	$db = &JFactory::getDBO();
	if ($event->id) {
		$sql = sprintf("update #__pbbooking_events set cal_id = %s, summary = '%s', dtend = %s, dtstart = %s, description = '%s' where id=%s",
				$db->escape($cal_id),$db->escape($event->summary),
				$db->escape($event->dtend->format('U')),
				$db->escape($event->dtstart->format('U')),
				$event->description,
				$db->escape($event->id));	
		//error_log($sql);			
	} else {
		$sql = sprintf("insert into #__pbbooking_events (cal_id,summary,dtend,dtstart,description) values (%s,'%s',%s,%s,'%s')",
				$db->escape($cal_id),$db->escape($event->summary),
				$db->escape($event->dtend->format('U')),
				$db->escape($event->dtstart->format('U')),
				$db->escape($event->description));		
	}
	$db->setQuery($sql);
	$db->query();
	return $db->insertid();
}

/**
* used to calculate whether a date is a blocked date
*
* @param datetime the date to block
* @return bool true or false true = is open and false = is closed
*/

function isOpen($date)
{

	//is the date a block day?
	$db=&JFactory::getDBO();
	$db->setQuery("select * from #__pbbooking_block_days");
	$blocked_days = $db->loadObjectList();
	$config = &JFactory::getConfig();
	$offset = $config->get('offset');
	
	if (count($blocked_days)>0) {
		foreach ($blocked_days as $blocked_day) {
			$block_from = date_create($blocked_day->block_start_date,new DateTimeZone(PBBOOKING_TIMEZONE));
			$block_from->setTime(00,00,00);
			$block_from->setTimezone(new DateTimezone($offset));
			
			$block_to = date_create($blocked_day->block_end_date,new DateTimeZone(PBBOOKING_TIMEZONE));
			$block_to->setTime(23,59,59);
			$block_to->setTimezone(new DateTimezone($offset));
			
			if ($date>=$block_from && $date<=$block_to && in_array($this->cal_id,explode(',',$blocked_day->calendars))) {
				Pbdebug::log_msg('Calendar model found single block at '.$date->format(DATE_ATOM),'com_pbbooking');
				return false;
			}
			$blocked_day->r_dtend = date_create($blocked_day->r_end,new DateTimeZone(PBBOOKING_TIMEZONE));
			$blocked_day->r_dtend->setTimezone(new DateTimeZone($offset));
			if ((isset($blocked_day->r_int) && isset($blocked_day->r_freq)) && $date <= $blocked_day->r_dtend) {
				while (($block_from<=$date && $block_to <= $date) || ($block_from<=$blocked_day->r_dtend && $block_to <= $blocked_day->r_dtend)) {
					$block_from->modify('+ '.$blocked_day->r_int.' '.$blocked_day->r_freq);
					$block_to->modify('+ '.$blocked_day->r_int.' '.$blocked_day->r_freq);
					if ($date>=$block_from && $date<=$block_to && in_array($this->cal_id,explode(',',$blocked_day->calendars))) {
						Pbdebug::log_msg('Calendar model found recurrant block with id '.$blocked_day->id.' at '.$date->format(DATE_ATOM),'com_pbbooking');
						return false;
					}
				}
			}
		}
	}
	return true;		
}

/**
* is_free_from_to() - returns either an event or false (ie false = available) to indicate whether the nominated calendar is free from from date to to date used for newer views based on times
*
* @param datetime from_date - the datetime to check from
* @param datetime to_date - the datetime to check to
* @param bool is_admin - whether the method is being called from an admin view or not	
* @return event the event that is in the time slot if one is there.
* @since 2.2
*/	
public function is_free_from_to($from_date,$to_date,$is_admin=false) {

	Pbdebug::log_msg('is_free_from_to(): checking dates $from_date '.$from_date->format(DATE_ATOM).' and $to_date '.$to_date->format(DATE_ATOM),'com_pbbooking');

	$free = true;
	$bookedEvent = null;
	
	//can we bail early due to being outside trading hours?  There are a couple of test cases
	//		1. the time is outside the trading hours
	//		2. the day it not a trading day
	$db = &JFactory::getDbo();
	$db->setQuery('select * from #__pbbooking_cals where id = '.$db->escape((int)$this->cal_id));
	$cal = $db->loadObject();
	$pbb_config = $db->setQuery('select * from #__pbbooking_config')->loadObject();

	//get global config
	$config =&JFactory::getConfig();
    date_default_timezone_set($config->get('offset'));	
	
	$trading_hours = ($this->cal_id != 0 && $cal->hours > '') ? json_decode($cal->hours,true) : json_decode($pbb_config->trading_hours,true);
	
	if (!$is_admin) {
		if ($trading_hours[$from_date->format('w')]['status'] == 'open') {
			//catches for outside trading times.
			$str_opening_time = $trading_hours[$from_date->format('w')]['open_time'];
			$str_closing_time = $trading_hours[$from_date->format('w')]['close_time'];
			$opening_time_arr = str_split($str_opening_time,2);
			$closing_time_arr = str_split($str_closing_time,2);
			$opening_date = date_create($from_date->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
			$closing_date = date_create($from_date->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
			$opening_date->setTime((int)ltrim($opening_time_arr[0],'0'),(int)ltrim($opening_time_arr[1],'0'));
			$closing_date->setTime((int)ltrim($closing_time_arr[0],'0'),(int)ltrim($closing_time_arr[1],'0'));
			if ($from_date < $opening_date || $from_date > $closing_date) return true;
		} else {
			//catches for non-trading day
			return true;
		}
	}
	
	//check to see if it's in a block date range.
	if (!$this->isOpen($from_date)) {
		return true;
	}
	
	
	foreach($this->events as $event) {
		
		$event->dtend->modify("-1 second");
		
		if ($event->dtend >= $from_date && $event->dtend <= $to_date) {
			$free = false;
			$bookedEvent = clone $event;
		}
		
		//check for multi day events
		if ($event->dtstart <= $from_date && $event->dtend >= $to_date) {
			$free = false;
			$bookedEvent = clone $event;
		}
		
		//check for recurrance
		if (isset($event->r_int)) {
			//we have a recurring event
			$dt_until = date_create($event->r_end,new DateTimeZone(PBBOOKING_TIMEZONE));
			$dt_until->setTimezone(new DateTimeZone($config->get('offset')));
			$dt_until->setTime(23,59,59);

			$dt_recur_cur_dtstart = date_create($event->dtstart->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
			$dt_recur_cur_dtend = date_create($event->dtend->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
			$dt_recur_cur_dtstart->setTimezone(new DateTimeZone($config->get('offset')));
			$dt_recur_cur_dtend->setTimezone(new DateTimeZone($config->get('offset')));

			while ($dt_recur_cur_dtstart < $dt_until) {
				if ($dt_recur_cur_dtend >= $from_date && $dt_recur_cur_dtend <= $to_date) {
					$free = false;
					$bookedEvent = clone $event;
				}
				$dt_recur_cur_dtstart->modify('+'.$event->r_int.' '.$event->r_freq);
				$dt_recur_cur_dtend->modify('+'.$event->r_int.' '.$event->r_freq);
			}
		}
	}
	
	return $bookedEvent;
}

/**
* can_book_treatment_at_time() - returns bool to indicate whether a treatment can be booked at a specified time.
* 								there are a number of reasons why it might not be possible:
* 										- slot is busy? caught by is_free_from_to
*										- too close to end of shift
*										- not enough time before next treatment
*
* @param int treatment_id - the id of the treatement to be booked
* @param datetime treatment_date - the datetime to be booked
* @param datetime shift_end - the ending time of the shift
* @returns bool
* @since 2.2
*/	

public function can_book_treatment_at_time($treatment_id,$treatment_date,$shift_end)
{

	Pbdebug::log_msg('can_book_treatment_at_time: $treatment_id = '.(int)$treatment_id.' and $treatment_date = '.$treatment_date->format(DATE_ATOM).' and $shift_end = '.$shift_end->format(DATE_ATOM),'com_pbbooking');


	$check_date = date_create($treatment_date->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
	$db = &JFactory::getDbo();
	$db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape((int)$treatment_id));
	$treatment = $db->loadObject();
	
	$db->setQuery('select * from #__pbbooking_config');
	$config = $db->loadObject();

	//can the calendar actually accept the treatment???
	if (!in_array($this->cal_id,explode(',',$treatment->calendar)))
		return false;
	
	if ($treatment->duration<= $config->time_increment) {
		return true;
	}
	
	//all remaining are where treatment->duration > time_interval
	$poss_book = true;
	$treatment_end = date_create($check_date->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
	//$treatment_end->modify('+'.($treatment->duration-1).'minutes');
	$treatment_end->modify('+'.($treatment->duration).'minutes');	
	
	Pbdebug::log_msg('can_book_treatment_at_time: $treatment_end = '.$treatment_end->format(DATE_ATOM),'com_pbbooking');

	//we could also have a treatment that blows past the end..... need to catch this....
	if ($treatment_end > $shift_end) 
		return false;

	//now check all other conditions...
	while($check_date <= $treatment_end) {
		$slot_end = date_create($check_date->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		$slot_end->modify('+'.$config->time_increment.' minutes');
		if ($slot_end<= $treatment_end) {
			if ($this->is_free_from_to($check_date,$slot_end)) {
				Pbdebug::log_msg('can_book_treatment_at_time: returning false from $this->is_free_from_to','com_pbbooking');
				return false;
			}
		}
		$check_date->modify('+'.$config->time_increment.' minutes');
	}
	
	return $poss_book;
}


	/**
	* returns the number of bookings on a given date including checking for recurring bookings
	* @param the date to check
	* @return int the number of bookings
	* @since 2.3
	* @todo merge the booking checking code in number_of_bookings_for_date and is_free_from_to
	*/

	public function number_of_bookings_for_date($check_date)
	{
		$config =&JFactory::getConfig();
    	date_default_timezone_set($config->get('offset'));	
	
		$bod = date_create($check_date->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		$eod = date_create($check_date->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		$bod->setTimezone(new DateTimeZone($config->get('offset')));
		$eod->setTimezone(new DateTimeZone($config->get('offset')));
		$bod->setTime(0,0,0);
		$eod->setTime(23,59,59);

		$num_events = 0;
		foreach ($this->events as $event) {
			if ($event->dtend >= $bod && $event->dtend <= $eod && !isset($event->r_int)) {
				//error_log('match for event'.$event->id);
				$num_events++;
			}
			if (isset($event->r_int)) {
				//recurring event
				$dt_until = date_create($event->r_end,new DateTimeZone(PBBOOKING_TIMEZONE));
				$dt_until->setTimezone(new DateTimeZone($config->get('offset')));

				$dt_recur_cur_dtend = date_create($event->dtend->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
				$dt_recur_cur_dtend->setTimezone(new DateTimeZone($config->get('offset')));

				while ($dt_recur_cur_dtend <= $dt_until && $dt_recur_cur_dtend <= $eod) {
					if ($dt_recur_cur_dtend >= $bod && $dt_recur_cur_dtend <= $eod) {
						$num_events++;
					}
					$dt_recur_cur_dtend->modify('+'.$event->r_int.' '.$event->r_freq);
				}

			}
		}
		return $num_events;


	}
	
	/**
	* returns the calendar utilization expressed as number of booked hours / number of working hours * 100
	* @param datetime the date to calculate from
	* @param datetime the date to calculate to
	* @return float
	* @since 2.4
	* @access public
	*/

	public function get_calendar_utilization($_x_date_from,$date_to)
	{
		$date_from = date_create($_x_date_from->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		
		Pbdebug::log_msg('get_calendar_utilization with dates $date_from = '.$date_from->format(DATE_ATOM).' $date_to  = '.$date_to->format(DATE_ATOM),'com_pbbooking');
		$db = &JFactory::getDbo();

		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();
		$calendar = $db->setQuery('select * from #__pbbooking_cals where id = '.(int)$this->cal_id)->loadObject();

		//calc total "avail hours" for period
		$cal_hours = json_decode($calendar->hours,true);
		$date_from->setTime(0,0,0);
		$date_to->setTime(23,59,59);
		$total_working_minutes = 0;

		$events = $db->setQuery('select * from #__pbbooking_events where dtstart >= "'.$date_from->format(DATE_ATOM).'" AND dtstart <= "'.$date_to->format(DATE_ATOM).'" and cal_id = '.(int)$this->cal_id)->loadObjectList();

		while ($date_from <= $date_to) {
			if ($cal_hours[$date_from->format('w')]['status'] == 'open') {
				$start_arr = str_split($cal_hours[$date_from->format('w')]['open_time'],2);
				$close_arr = str_split($cal_hours[$date_from->format('w')]['close_time'],2);
				$dtstart = new DateTime($date_from->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
				$dtend = new DateTime($date_from->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
				$dtstart->setTime($start_arr[0],$start_arr[1],0);
				$dtend->setTime($close_arr[0],$close_arr[1],59);
				$diff = $dtend->diff($dtstart);
				$total_working_minutes += (($diff->format('%h')*60) + $diff->format('%i'));
			}
			
			$date_from->modify('+1 day');
		}
		Pbdebug::log_msg('get_calendar_utilization() - total working minutes for calendar_id = '.$this->cal_id.' = '.$total_working_minutes,'com_pbbooking');

		//now get appt's in date range....
		$total_booked_minutes = 0;
		foreach ($events as $event) {
			$dtstart = date_create($event->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE));
			$dtend = date_create($event->dtend,new DateTimeZone(PBBOOKING_TIMEZONE));
			$total_booked_minutes += (($dtstart->diff($dtend)->format('%h')*60)+$dtstart->diff($dtend)->format('%i'));
		}
		Pbdebug::log_msg('get_calendar_utilization() - total hours worked for calendar_id = '.$this->cal_id.' = '.$total_booked_minutes,'com_pbbooking');

		Pbdebug::log_msg('get_calendar_utilization() - calendar_utilization for calendar = '.$this->cal_id.' = '.($total_booked_minutes/$total_working_minutes)*100,'com_pbbooking');

		return ($total_booked_minutes/$total_working_minutes)*100;
		
	}
}

?>
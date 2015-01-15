<?php
/**
 * @version		$Id: index.php 20806 2011-02-21 19:44:59Z dextercowley $
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */


/**
* publish.php file used to publish events to the calendar tables
* 
* Loads the Joomla Framework to allow database connecticity.
*/


error_log('puhlish.php page');

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);


$dir_arr = explode("/", __FILE__);
$dir_arr = array_slice($dir_arr,0,(count($dir_arr)-4));
define('JPATH_BASE',implode('/',$dir_arr));


require_once JPATH_BASE.DS.'includes/defines.php';
require_once JPATH_BASE.DS.'includes/framework.php';

// Mark afterLoad in the profiler.
JDEBUG ? $_PROFILER->mark('afterLoad') : null;

// Instantiate the application.
$app = JFactory::getApplication('site');

// Initialise the application.
$app->initialise();


// --------- START PBBOOKING CODE HERE -------------//
$db = &JFactory::getDBO();
$db->setQuery('select * from #__pbbooking_config');
$config = $db->loadObject();
$joom_config =&JFactory::getConfig();
date_default_timezone_set($joom_config->get('offset'));	

define('PBBOOKING_TIMEZONE',$joom_config->get('offset'));


if (!isset($_SERVER['PHP_AUTH_USER'])) {
	if ($config->use_pb_pub_sec == 1) {
		header('WWW-Authenticate: Basic realm="My Realm"');
	    header('HTTP/1.0 401 Unauthorized');
	    exit;
	}
}  

//check pbbooking passwords if required....
if ($config->use_pb_pub_sec == 1) {
	if ($config->publish_username != $_SERVER['PHP_AUTH_USER'] || $config->publish_password != $_SERVER['PHP_AUTH_PW']) {
		error_log('invalid username and password for publish');
		die;
	}	
}

if ($config->allow_publish == 1) {
    error_log('received publish request.... ');
	$data = "";
	$events = array();
	if($datain = fopen('php://input','r')){
		
		$in_event = false;
		$curr_uid = "";
		$curr_summary = "";
		$curr_dstart_string = "";
		$curr_dtend_string = "";
		
		while(!@feof($datain)){
			$line = fgets($datain,4096);
			$data .= $line;
			
			//BEGIN PARSE INCOMING EVENTS
			
			if (preg_match('/BEGIN:VEVENT/',$line)) $in_event = true;
			if (preg_match('/UID:(.*)$/',$line,$matches)) $curr_uid = $matches[1];
			if (preg_match('/DTEND;.*:(.*)$/',$line,$matches)) $curr_dtend_string = $matches[1];
			if (preg_match('/DTSTART;.*:(.*)$/',$line,$matches)) $curr_dtstart_string = $matches[1];
			if (preg_match('/SUMMARY:(.*)$/',$line,$matches)) $curr_summary = $matches[1];
			if (preg_match('/END:VEVENT/',$line)) {
				$new_event = array('uid'=>$curr_uid,'dtend'=>$curr_dtend_string,'dtstart'=>$curr_dtstart_string,'summary'=>$curr_summary);
				$events[] = $new_event;
				//error_log(print_r($new_event));
				$in_event = false;
			}
			
			//END PARSE INCOMING EVENTS
			
		}
		@fclose($datain);
	}
	
	preg_match('/.*\/(\d+).ics/',$_SERVER['REQUEST_URI'],$matches);
	$calname = $matches[1];
	//error_log('calname = '.$matches[1]);
	//error_log(count($events).' events');
	
	//check if calendar is valid...
	$db->setQuery('select * from #__pbbooking_cals where id = '.$db->escape($calname));
	$cal = $db->loadObject();
	if ($cal) { 
		//error_log('cal is valid');
		foreach ($events as $event) {
			//build SQL  insert or update
			if ($event['uid']) {
				
				$sql = 'select * from #__pbbooking_events where uid = "'.$db->escape(trim($event['uid'])).'"';
				$db->setQuery($sql);
	
				if (count($db->loadObjectList())>0) {
					//existing event... update
					$sql = sprintf('update #__pbbooking_events set summary = "%s", dtend = "%s", dtstart = "%s", description = "%s" where uid = "%s"',
							$db->escape(trim($event['summary'])),
							$db->escape(date_create(trim($event['dtend']),new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM)),
							$db->escape(date_create(trim($event['dtstart']),new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM)),
							$db->escape('Imported Event'),
							$db->escape(trim($event['uid'])));
							//error_log($sql);
							$db->setQuery($sql);
							$db->query();
				} else {
					//new event ... create
					$sql = sprintf("insert into #__pbbooking_events (cal_id,summary,dtend,dtstart,description,uid) values (%s,'%s','%s','%s','%s','%s')",
					$db->escape($calname),
					$db->escape(trim($event['summary'])),
					$db->escape(date_create(trim($event['dtend']),new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM)),
					$db->escape(date_create(trim($event['dtstart']),new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM)),
					$db->escape('Imported Event'),
					$db->escape(trim($event['uid'])));
					//error_log($sql);
					$db->setQuery($sql);
					$db->query();
				}
				
			}
			
		}
	} else {
		//error_log('cal is not valid');
		die;
	} 


} else {
	error_log('unauthorised attempt to publish');
	die('You are not allowed here');
}









<?php
/**
 * @version		ics_view.php
 * @copyright		Purple Beanie
 * @license		GNU General Public License version 2 or later; 
 */
$cal = "BEGIN:VCALENDAR
VERSION:2.0
CALSCALE:GREGORIAN
X-WR-CALNAME:".$this->cal->name."
PRODID:-//PBBooking//PBBooking 2.2//EN";
foreach ($this->events as $event) {
	$cal.="
BEGIN:VEVENT
UID:".$event->id."@".JURI::root(false)."
DTEND;VALUE=DATE-TIME:".date_create($event->dtend,new DateTimeZone(PBBOOKING_TIMEZONE))->format('Ymd')."T".date_create($event->dtend,new DateTimeZone(PBBOOKING_TIMEZONE))->format('His')."
DTSTART;VALUE=DATE-TIME:".date_create($event->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE))->format('Ymd')."T".date_create($event->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE))->format('His')."
SUMMARY:".$event->summary."
DESCRIPTION:".str_replace("\n","\r\n",$event->description)."
END:VEVENT";
}
$cal.="
END:VCALENDAR";
str_replace("\n","\r\n",$cal);
header('HTTP/1.1 200 OK');
header('Accept-Ranges: bytes');
header('Content-Length: '.strlen($cal));
header('Content-Type: text/calendar');
header("Content-Disposition: attachment; filename=\"event.ics\""); 
?>
<?php echo $cal;?>
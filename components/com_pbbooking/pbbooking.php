<?php
/**
 * @package    PurpleBeanie.PBBooking
 * @subpackage Components
 * @link http://www.purplebeanie.com
 * @license    GNU/GPL
*/
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$config = &JFactory::getConfig();
$version = new JVersion();
$input = &JFactory::getApplication()->input;


//set some defines cause we're goign to use things a lot!!
define('PBBOOKING_TIMEZONE',$config->get('offset')); 
define('JOOMLA_VERSION',$version->RELEASE);
if ($version->RELEASE == '3.0')
	define('DS',DIRECTORY_SEPARATOR);

//some requires 
require_once( JPATH_COMPONENT.DS.'controller.php' );
require_once( JPATH_COMPONENT.DS.'models'.DS.'calendar.php' );
require_once( JPATH_COMPONENT.DS.'models'.DS.'event.php' );
require_once( JPATH_COMPONENT.DS.'views'.DS.'pbbooking'.DS.'view.html.php' );
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'pbbookingpaypalhelper.php');

//pull in my own framework files....
require_once(JPATH_COMPONENT.DS.'pbframe'.DS.'pbgeneral.php');
require_once(JPATH_COMPONENT.DS.'pbframe'.DS.'pbdebug.php');


 
// Require specific controller if requested
if($controller = JRequest::getWord('view')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
    	Pbdebug::log_msg('pbbooking.php - importing controller '.$controller,'com_pbbooking');
        require_once $path;
    } else {
        $controller = '';
    }
}
 
// Create the controller
$c_name = (JRequest::getWord('view') == 'pbbooking') ? 'PbbookingController' : 'PbbookingController'.JRequest::getWord('view');

//$controller   = new $c_name;
$controller = new $c_name;


$task = JRequest::getWord('task');

if(!$task || $task == 'view')
	$task == 'display';

$controller->execute($task);

 
// Redirect if set by the controller
$controller->redirect();
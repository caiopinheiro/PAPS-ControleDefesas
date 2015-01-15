<?php

/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or la<ter; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

// No direct access
 
defined('_JEXEC') or die('Restricted access'); 


class PbGeneral
{
	/**
	* returns an html form element with the formatted
	* @param string the name of the element
	* @param string type of element
	* @param string options / values as a string with each option separated by a | and values by =
	* @param array an array of html options
	*/

	public static function create_html_form_element($name,$type,$options=null,$h_options=null)
	{
		$el = '';
		switch ($type) {
			case 'text':
				$el = '<input type="text" name="'.$name.'" value=""/>'; 
				break;
			case 'radio':
				foreach (explode('|',$options) as $option) {
					$o_arr = explode('=',$option);
					$el.='<input type="radio" name="'.$name.'" value="'.$o_arr[0].'"/><label>'.$o_arr[1].'</label><br/>';
				}
				break;
			case 'checkbox':
				foreach (explode('|',$options) as $option) {
					$o_arr = explode('=',$option);
					$el.='<input type="checbox" name="'.$name.'[]" value="'.$o_arr[0].'"/><label>'.$o_arr[1].'</label><br/>';
				}
				break;
			case 'textarea':
				$el = '<textarea name="'.$name.'"></textarea>';
				break;
			case 'select':
				$el = '<select name="'.$name.'">';
				foreach (explode('|',$options) as $option) {
					$o_arr = explode('=',$option);
					$el.='<option value="'.$o_arr[0].'">'.$o_arr[1].'</option>';
				}
				break;

		}

		return $el;
	}
}

?>
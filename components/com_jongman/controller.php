<?php
/**
 * @version		$Id: controller.php 522 2013-01-06 20:48:00Z mrs.siam $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

require_once(JPATH_COMPONENT.'/helpers/jongman.php');
/**
 * JONGman default controller.
 * this controller used for URL in form of option=com_jongman&view=vname or option=com_jongman
 *
 * @package		Joomla.Administrator
 * @subpackage	com_jongman
 * @since		2.0
 */
class JongmanController extends JController
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{		
		//JFactory::getDocument()->addStyleSheet(JURI::base(true).'/media/com_jongman/css/jongman.css?'.rand(100,100000));
		$document = JFactory::getDocument();
		if (JRequest::getCmd('view')=='schedule') {
			if (JRequest::getCmd('layout')=='calendar') {
				$document->addStyleSheet(JURI::base(true).'/media/com_jongman/css/calendar.css?'.rand(100,100000));			
			}
		}
		if (JRequest::getCmd('view')=='reservation') {
			$document->addStyleSheet(JURI::base(true).'/media/com_jongman/css/jongman.css?'.rand(100,100000));
			$document->addStyleSheet(JURI::base(true).'/media/com_jongman/css/popup-reservation.css?'.rand(100,100000));
			$document->addScript(JURI::base(true).'/media/com_jongman/js/reservation.js');	
		}

		parent::display();
		return $this;
	}
}

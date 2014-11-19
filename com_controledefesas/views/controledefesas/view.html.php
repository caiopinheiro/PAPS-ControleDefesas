<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class ControleDefesasViewControleDefesas extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) 
        {
                // Assign data to the view
                $this->msg = 'eu to escrevendo direto na classe da view, o html que poderá ser inserido deve ser colocado na classe default';
				$this->msg2 = 'eu to escrevendo direto na classe da view, o html que poderá ser inserido deve ser colocado na classe default';
                // Display the view
                parent::display($tpl);
        }
}

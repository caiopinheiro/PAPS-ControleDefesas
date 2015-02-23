<?php

$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class DefesasCoordenadorViewDefesasCoordenador extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) 
        {
                // Assign data to the view
                $this->msg = 'Módulo de defesas do coordenador';
                
                // Display the view
                parent::display($tpl);
        }
        
		
		
}

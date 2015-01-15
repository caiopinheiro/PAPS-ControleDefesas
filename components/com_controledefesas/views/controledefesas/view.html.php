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
                $this->msg = 'Controle de Defesas';
                
                // Display the view
                parent::display($tpl);
        }
        
        function solicitarBanca($tpl = null){
			$this->msg = 'Solicitar Banca';
			parent::display($tpl);
		}
		
		
}

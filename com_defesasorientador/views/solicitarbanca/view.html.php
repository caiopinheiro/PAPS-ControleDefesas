<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class DefesasOrientadorViewSolicitarBanca extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) 
        {
                // Assign data to the view
                $this->msg = '';
                $this->aluno = $this->get('aluno');
                $this->membrosbanca = $this->get('membrosbanca');
                $this->faseDefesa = $this->get('fasedefesa');
                $this->mapaFases = $this->get('mapafases');
                
                // Display the view
                parent::display($tpl);
        }
        
		
		
}

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
class DefesasCoordenadorViewListaBancas extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) {              
			$nome_orientador = JRequest::getCmd('buscaNomeOrientador', false);
			$status_bancas = JRequest::getCmd('buscaStatusBanca', false);
			$this->nome_orientador = $nome_orientador;
			$this->status_bancas = $status_bancas;
			
			if(($this->nome_orientador == null) AND ($this->status_bancas == null)){
				$nome_orientador = '';
				$status_bancas = 3;
			}
						
			$model = $this->getModel();
			$this->bancas = $model->filtroBanca($nome_orientador,$status_bancas);
							
			// Display the view
			parent::display($tpl);
        }        
}

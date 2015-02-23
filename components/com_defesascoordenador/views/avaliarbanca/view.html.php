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
class DefesasCoordenadorViewAvaliarBanca extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null){	
			$this->msg = 'Avaliar Banca';
			$idBanca = JRequest::getVar('idBanca');		
			$status = JRequest::getVar('status');
			$status2 = JRequest::getVar('status2');
			$emailconv = JRequest::getVar('emailconv');
			$emailsoli = JRequest::getVar('emailsoli');

			$this->emailsoli = $emailsoli;
			$this->emailconv = $emailconv;
			$this->status = $status;
			$this->status2 = $status2;
			$this->idBanca = $idBanca;
			
			$model = $this->getModel();
			
			$this->banca = $model->visualizarBanca($idBanca);
			$this->aluno = $model->visualizarAluno($idBanca);
			$this->defesa = $model->visualizarDefesa($idBanca);
			$this->membrosBanca = $model->visualizarMembrosBanca($idBanca);
				
			parent::display($tpl);
        }
        
}

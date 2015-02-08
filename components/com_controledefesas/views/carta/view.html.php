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
class ControledefesasViewCarta extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null){	
			$this->msg = 'Aluno - Detalhes';
			$idDefesa = JRequest::getVar('idDefesa');		
			$status = JRequest::getVar('status');
			$status2 = JRequest::getVar('status2');
			$idAluno = JRequest::getvar('idAluno');
			$idMembro = JRequest::getVar('idMembro');
			
			$this->status = $status;
			$this->status2 = $status2;
			$this->idDefesa = $idDefesa;
			$this->idAluno = $idAluno;
			$this->idBanca = $idBanca;

			
			$model = $this->getModel();
			

			$this->alunos = $model->visualizarDefesa($idDefesa);
			$this->banca = $model->visualizarNomeMembro($idMembro);
				
			parent::display($tpl);
        }
        
}

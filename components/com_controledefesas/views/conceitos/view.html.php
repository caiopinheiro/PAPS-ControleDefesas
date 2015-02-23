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
class ControledefesasViewConceitos extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null){	
			$this->msg = 'Defesa - Detalhes';
			$idDefesa = JRequest::getVar('idDefesa');		
			$status = JRequest::getVar('status');
			$status2 = JRequest::getVar('status2');
			$checkNum = JRequest::getVar('checkNum');
			$idAluno = JRequest::getvar('idAluno');
			$idBanca = JRequest::getVar('idBanca');
			
			$this->status = $status;
			$this->status2 = $status2;
			$this->checkNum = $checkNum;
			$this->idDefesa = $idDefesa;
			$this->idAluno = $idAluno;
			$this->idBanca = $idBanca;

			
			$model = $this->getModel();
			
			//$this->conceito = $model->updateConceito($idAluno);
			$this->banca = $model->visualizarBanca($idBanca);
			$this->aluno = $model->visualizarAluno($idAluno);
			$this->defesa = $model->visualizarDefesa($idDefesa);
			$this->membrosBanca = $model->visualizarMembrosBanca($idDefesa);
				
			parent::display($tpl);
        }
        
}

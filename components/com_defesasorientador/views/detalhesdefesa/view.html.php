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
class DefesasOrientadorViewDetalhesDefesa extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null){	
			$this->msg = 'Detalhes da Defesa';

			$idDefesa = JRequest::getVar('idDefesa');
			$idAluno = JRequest::getVar('idAluno');
			$isUpdate = JRequest::getVar('isUpdate');
			$updated = JRequest::getVar('updated');
			
			$this->idDefesa = $idDefesa;
			$this->idAluno = $idAluno;
			$this->isUpdate = $isUpdate;
			$this->updated = $updated;

			$model = $this->getModel();
			$this->defesa = $model->visualizarDefesa($idDefesa, $idAluno);
			$this->aluno = $model->visualizarAluno($idAluno);
			$this->membrosBanca = $model->visualizarMembrosBanca($this->defesa[0]->banca_id);

			parent::display($tpl);
        }
        
}

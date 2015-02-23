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
class ControledefesasViewDetalhesAluno extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null){	
			$this->msg = 'Dados do Aluno';
			$idAluno = JRequest::getVar('idAluno');

			$this->idAluno = $idAluno;

			$model = $this->getModel();
			$this->aluno = $model->getAluno($idAluno);
			$this->defesas = $model->getDefesas($idAluno);
			$this->membrosBanca = $model->getMembrosBanca($idAluno);

			parent::display($tpl);
        }
        
}

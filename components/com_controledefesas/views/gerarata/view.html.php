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
class ControledefesasViewGerarata extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null){	
			$this->msg = 'Aluno - FOLHA APROVAÇÃO';
			$idDefesa = JRequest::getVar('idDefesa');
			$idAluno = JRequest::getvar('idAluno');
			
			
			$this->idDefesa = $idDefesa;
			$this->idAluno = $idAluno;

			
			$model = $this->getModel();			
			$this->defesa = $model->folhaaprovacao($idDefesa);
			$this->membrosBanca = $model->visualizarMembrosBanca($idDefesa);
			$this->aluno = $model->visualizarAlunoAta($idDefesa);
			$this->defesaAta = $model->visualizarDefesaAta($idDefesa);

				
			parent::display($tpl);
        }
        
}

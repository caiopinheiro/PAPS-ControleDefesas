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
class ControledefesasViewRelatorioDefesas extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) {
			
			
			$data_inicial = JRequest::getVar('dataInicial', false);
			$data_final = JRequest::getVar('dataFinal', false);
			$nome_professor = JRequest::getVar('nomeProfessor', false);
			$id_membro_banca = JRequest::getVar('idMembroBanca', false);
			$id_professor = JRequest::getVar('idProfessor', false);

			$this->data_inicial = $data_inicial;
			$this->data_final = $data_final;
			$this->nome_professor = $nome_professor;
			$this->id_membro_banca = $id_membro_banca;
			$this->id_professor = $id_professor;

			if(($this->data_inicial == NULL) AND 
			   ($this->data_final == NULL) AND 
			   ($this->nome_professor == NULL) AND
			   ($this->id_membro_banca == NULL) AND
			   ($this->id_professor == NULL)){
			    $data_inicial = '';
				$data_final = '';
				$nome_professor = '';
				$id_membro_banca = '';
				$id_professor = '';
			}
			$this->defesas = NULL;
			
			$model = $this->getModel();
			if($nome_professor == NULL || $nome_professor == false || $nome_professor == '')
				$this->defesas = $model->getDefesasPorPeriodo($data_inicial, $data_final);
			else
				$this->defesas = $model->getDefesasPorPeriodoProfessor($data_inicial, $data_final, $nome_professor, $id_membro_banca, $id_professor);
			
			$this->membrosBanca = $model->getMembrosBanca();
			$this->professores = $model->getProfessores();

			// Display the view
			parent::display($tpl);
        }
}

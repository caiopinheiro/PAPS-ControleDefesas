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
			$id_membro_banca = JRequest::getVar('idMembroBanca', false);

			$this->data_inicial = $data_inicial;
			$this->data_final = $data_final;
			$this->id_membro_banca = $id_membro_banca;

			if(($this->data_inicial == NULL) AND 
			   ($this->data_final == NULL) AND 
			   ($this->id_membro_banca == NULL)){
			    $data_inicial = '';
				$data_final = '';
				$id_membro_banca = '';
			}
			$this->defesas = NULL;
			
			$model = $this->getModel();
			if($id_membro_banca == NULL || $id_membro_banca == false || $id_membro_banca == '')
				$this->defesas = $model->getDefesasPorPeriodo($data_inicial, $data_final);
			else
				$this->defesas = $model->getDefesasPorPeriodoProfessor($data_inicial, $data_final, $id_membro_banca);
			
			$this->membrosBanca = $model->getMembrosBanca();
			//$this->professores = $model->getProfessores();
			// Display the view
			parent::display($tpl);
        }
}

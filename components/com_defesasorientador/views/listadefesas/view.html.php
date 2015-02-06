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
class DefesasOrientadorViewListaDefesas extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) {
			
			$nome_aluno = JRequest::getVar('buscaNomeAluno', false);
			//$titulo_defesa = JRequest::getVar('buscaTituloDefesa', false);
			$curso = JRequest::getCmd('buscaCurso', false);
			$tipo_defesa = JRequest::getCmd('buscaTipoDefesa', false);
			$data_defesa = JRequest::getVar('buscaDataDefesa', false);
			$local_defesa = JRequest::getVar('buscaLocalDefesa', false);

			$this->nome_aluno = $nome_aluno;
			//$this->titulo_defesa = $titulo_defesa;
			$this->curso = $curso;
			$this->tipo_defesa = $tipo_defesa;
			$this->data_defesa = $data_defesa;
			$this->local_defesa = $local_defesa;

			if(($this->nome_aluno == NULL) AND 
			   //($this->titulo_defesa == NULL) AND
			   ($this->curso == NULL) AND 
			   ($this->tipo_defesa == NULL) AND 
			   ($this->data_defesa == NULL) AND 
			   ($this->local_defesa == NULL)){

			    $nome_aluno = '';
			    //$titulo_defesa = '';
			    $curso = 0;
			    $tipo_defesa = '';
			    $data_defesa = '';
			    $local_defesa = '';
			}

			$user = JFactory::getUser();

			$model = $this->getModel();
			$this->defesas = $model->filtroDefesa($user->id, $nome_aluno, $curso, $tipo_defesa, $data_defesa, $local_defesa);
							
			// Display the view
			parent::display($tpl);
        }
}

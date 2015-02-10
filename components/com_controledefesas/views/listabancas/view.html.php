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
class ControledefesasViewListaBancas extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) {              
			
			$status_bancas = JRequest::getCmd('buscaStatusBanca', false);
			$nome_aluno = JRequest::getVar('buscaNomeAluno', false);
			$tipo_banca = JRequest::getCmd('tipoBanca', false);
			$nome_orientador = JRequest::getVar('buscaNomeOrientador', false);
			$linha_pesquisa = JRequest::getCmd('linhaPesquisa',false);
			$tipo_curso = JRequest::getCmd('tipo_curso',false); // o que é getCmd ?



			
			$this->status_bancas = $status_bancas;
			$this->nome_aluno = $nome_aluno;
			$this->tipo_banca = $tipo_banca;
			$this->nome_orientador  = $nome_orientador;;
			$this->linha_pesquisa = $linha_pesquisa;
			$this->tipo_curso = $tipo_curso;
			
			
			if(($this->status_bancas == NULL) AND 
			   ($this->nome_aluno == NULL) AND
			   ($this->tipo_banca == NULL) AND 
			   ($this->nome_orientador == NULL) AND
			   ($this->linha_pesquisa == NULL) AND
			   ($this->tipo_curso == NULL)){
				$status_bancas = 5;
				$nome_aluno = '';
				$tipo_banca = 4;
				$tipo_curso = 0;
				$nome_orientador = '';
				$linha_pesquisa = 0;
			}
			
			$model = $this->getModel();
			$this->defesas = $model->filtroBanca($status_bancas,$nome_aluno, $tipo_curso, $nome_orientador,$tipo_banca,$linha_pesquisa);
							
			// Display the view
			parent::display($tpl);
        }        
}




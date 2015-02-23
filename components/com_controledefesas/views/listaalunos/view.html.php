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
class ControledefesasViewListaAlunos extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) {              
			
			$nome_aluno = JRequest::getVar('buscaNomeAluno', false);
			$matricula = JRequest::getVar('buscaMatricula', false);
			$curso = JRequest::getCmd('buscaCurso', false);
			$linha_pesquisa = JRequest::getCmd('buscaLinhaPesquisa',false);
			$nome_orientador = JRequest::getVar('buscaNomeOrientaddor', false);

			$this->nome_aluno = $nome_aluno;
			$this->matricula = $matricula;
			$this->curso = $curso;
			$this->linha_pesquisa = $linha_pesquisa;
			$this->nome_orientador = $nome_orientador;
			
			if(($this->nome_aluno == NULL) AND 
			   ($this->matricula == NULL) AND
			   ($this->curso == NULL) AND
			   ($this->linha_pesquisa == NULL) AND
			   ($this->nome_orientador == NULL)){
				$nome_aluno = '';
				$matricula = '';
				$curso = 0;
				$linha_pesquisa = 0;
				$nome_orientador = '';
			}
			
			$model = $this->getModel();
			$this->alunos = $model->filtroAluno($nome_aluno, $matricula, $curso, $linha_pesquisa, $nome_orientador);
							
			// Display the view
			parent::display($tpl);
        }        
}

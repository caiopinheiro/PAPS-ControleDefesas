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
class DefesasCoordenadorViewListaBancas extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) {              
			
			$status_bancas = JRequest::getCmd('buscaStatusBanca', false);
			$nome_aluno = JRequest::getVar('buscaNomeAluno', false);
			$tipo_banca = JRequest::getCmd('tipoBanca', false);
			$nome_orientador = JRequest::getVar('buscaNomeOrientador', false);
			$linha_pesquisa = JRequest::getCmd('linhaPesquisa',false);
			
			$this->status_bancas = $status_bancas;
			$this->nome_aluno = $nome_aluno;
			$this->tipo_banca = $tipo_banca;
			$this->nome_orientador  = $nome_orientador;;
			$this->linha_pesquisa = $linha_pesquisa;
			
			
			if(($this->status_bancas == NULL) AND 
			   ($this->nome_aluno == NULL) AND
			   ($this->tipo_banca == NULL) AND 
			   ($this->nome_orientador == NULL) AND
			   ($this->linha_pesquisa == NULL)){
				$status_bancas = 2;
				$nome_aluno = '';
				$tipo_banca = 2;
				$nome_orientador = '';
				$linha_pesquisa = 0;
			}
			
			$model = $this->getModel();
			$this->bancas = $model->filtroBanca($status_bancas,$nome_aluno,$nome_orientador,$tipo_banca,$linha_pesquisa);
							
			// Display the view
			parent::display($tpl);
        }        
}
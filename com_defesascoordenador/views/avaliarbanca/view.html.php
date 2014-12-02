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
class DefesasCoordenadorViewAvaliarBanca extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null){	
			$this->msg = 'Avaliar Banca';
			$idBanca = JRequest::getVar('idBanca');		
			
			
			//echo '<p>'.$idBanca.'</p>';
			
			//$this->idBanca = $idBanca;
					
			$model = $this->getModel();
			
			$this->banca = $model->visualizarBanca($idBanca);
			//$this->banca_dAluno = $model->visualizarAluno($idBanca);
		//	echo '<p>'.var_dump($this->ViewBanca).' Estou na model</p>';
			//add aluno, tipo de defesa, linha de pesquisa na view listabancas	
			parent::display($tpl);
        }
        
}

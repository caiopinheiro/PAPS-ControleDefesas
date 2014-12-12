<?php

/**
 * @version     1.0.0
 * @package     com_defesascoordenador
 * @copyright   Copyright (C) 2014. Todos os direitos reservados.
 * @license     GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 * @author      Caio <pinheiro.caiof@gmail.com> - http://
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class DefesascoordenadorController extends JController {

    /**
     * Method to display a view.
     *
     * @param	boolean			$cachable	If true, the view output will be cached
     * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return	JController		This object to support chaining.
     * @since	1.5
     */
    public function display($cachable = false, $urlparams = false) {
        require_once JPATH_COMPONENT . '/helpers/defesascoordenador.php';
				
        parent::display($cachable, $urlparams);
			
        return $this;
    }
    
    public function avaliarBanca(){
//		$idBanca = JRequest::getCmd('idBancaSelec', false);
		$idBanca = JRequest::getVar('idBanca');
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');		
		//echo '<p>'.$idAluno.'</p>';
		header('Location: index.php?option=com_defesascoordenador&view=avaliarbanca&idBanca='.$idBanca.'&idDefesa='.$idDefesa.'&idAluno='.$idAluno);
	}
	
	public function deferirBanca(){
		$idBanca = JRequest::getVar('idBanca');
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		$avaliacao = JRequest::getVar('avaliacao');
		$model = $this->getModel('avaliarbanca');		
		$sucesso = $model->updateStatusBanca($idBanca,$avaliacao);	
		
		header('Location: index.php?option=com_defesascoordenador&view=avaliarbanca&idBanca='.$idBanca.'&idDefesa='.$idDefesa.'&idAluno='.$idAluno.'&idAvaliacao='.$sucesso);
	}
	
	public function indeferirBanca(){
		$idBanca = JRequest::getVar('idBanca');
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		$avaliacao = JRequest::getVar('avaliacao');
		//echo '<p>'.$avaliacao.'</p>';
		$model = $this->getModel('avaliarbanca');		
		$sucesso = $model->updateStatusBanca($idBanca,$avaliacao);	
		
		header('Location: index.php?option=com_defesascoordenador&view=avaliarbanca&idBanca='.$idBanca.'&idDefesa='.$idDefesa.'&idAluno='.$idAluno.'&idAvaliacao='.$sucesso);
			
	}

	
	
}

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

class ControledefesasController extends JController {

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
        require_once JPATH_COMPONENT . '/helpers/controledefesas.php';
				
        parent::display($cachable, $urlparams);
			
        return $this;
    }
    
    public function conceitos(){
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		header('Location: index.php?option=com_controledefesas&view=conceitos&idDefesa='.$idDefesa.'&idAluno='.$idAluno);

	}
	
	public function aprovar(){
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		$model = $this->getModel('conceitos');
		$aprovado = "Aprovado";
		$status = $model->updateConceito($idAluno,$idDefesa,$aprovado);	
		header('Location: index.php?option=com_controledefesas&view=conceitos&idAluno='.$idAluno.'&idDefesa='.$idDefesa.'&status='.$status);
		
	}

	public function reprovar(){
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		$model = $this->getModel('conceitos');
		$reprovado = "Reprovado";
		$status = $model->updateConceito($idAluno,$idDefesa,$reprovado);	
		header('Location: index.php?option=com_controledefesas&view=conceitos&idAluno='.$idAluno.'&idDefesa='.$idDefesa.'&status='.$status);
		
	}
	 

	public function folhaaprovacao(){
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		header('Location: index.php?option=com_controledefesas&view=folhaaprovacao&idDefesa='.$idDefesa.'&idAluno='.$idAluno);

	} 


	public function carta(){
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		header('Location: index.php?option=com_controledefesas&view=carta&idDefesa='.$idDefesa.'&idAluno='.$idAluno);
		
	}

	public function declaracao(){
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		header('Location: index.php?option=com_controledefesas&view=declaracao&idDefesa='.$idDefesa.'&idAluno='.$idAluno);
	}

}

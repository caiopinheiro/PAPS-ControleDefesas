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
		//header('Location: http://www.google.com.br/');
	}

	public function reprovar(){
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		$model = $this->getModel('conceitos');
		$reprovado = "Reprovado";
		$status = $model->updateConceito($idAluno,$idDefesa,$reprovado);	
		header('Location: index.php?option=com_controledefesas&view=conceitos&idAluno='.$idAluno.'&idDefesa='.$idDefesa.'&status='.$status);
		//header('Location: http://www.google.com.br/');
	}
	 

	public function enviarConvite($emails){
		//$caio = "thiagoleitexd@gmail.com";
		$caio2 = "pinheiro.caiof@gmail.com";
		//$caio3 = "gcarneirobr@gmail.com";
		
		var_dump($emails);
		
		// subject
		$subject  = "[IComp/UFAM] Convite de Participação de Banca";

		// message
		$message .= "Caro Professor: \r\n\n";
		$message .= "texto de convite...fjkasjhkfla \r\n\n";
		$message .= "Nome: fsfsa \r\n";
		$message .= "E-mail: fdsafa\r\n";
		$message .= "Local: fdsfa\r\n";
		$message .= "ISSO É APENAS UM TESTE!!!\r\n";
		
		//$email[] = $caio;
		$email[] = $caio2;
		//$email[] = $caio3;
		//var_dump($email);

		JUtility::sendMail($user->email, "Site do IComp: Controle de Defesas", $email, $subject, $message, false, NULL, NULL, NULL);
		
	}

}

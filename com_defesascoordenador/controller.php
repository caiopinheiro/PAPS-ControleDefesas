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
		$idBanca = JRequest::getVar('idBanca');
		
		header('Location: index.php?option=com_defesascoordenador&view=avaliarbanca&idBanca='.$idBanca);

	}
	
	public function deferirBanca(){
		$idBanca = JRequest::getVar('idBanca');
		$avaliacao = JRequest::getVar('avaliacao');
		$emails = JRequest::getVar('emails');
		$model = $this->getModel('avaliarbanca');				
		$status = $model->updateStatusBanca($idBanca,$avaliacao);	
		var_dump($emails);
		$this->enviarConvite($emails);
		//header('Location: index.php?option=com_defesascoordenador&view=avaliarbanca&idBanca='.$idBanca.'&status='.$status);
		
	}
	 
	
	public function indeferirBanca(){
		$idBanca = JRequest::getVar('idBanca');
		$avaliacao = JRequest::getVar('avaliacao');
		$justificativa = JRequest::getVar('justificativa');
		
		//echo '<p>'.$justificativa.'passei na controller</p>';
		$model = $this->getModel('avaliarbanca');		
		$status = $model->updateStatusBanca($idBanca,$avaliacao);	
		$status2 = $model->setJustificativa($idBanca,$justificativa);
		

		header('Location: index.php?option=com_defesascoordenador&view=avaliarbanca&idBanca='.$idBanca.'&status='.$status.'&status2='.$status2);
			
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

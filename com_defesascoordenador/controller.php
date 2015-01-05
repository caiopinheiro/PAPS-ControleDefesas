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
		$model = $this->getModel('avaliarbanca');	
			
		$status = $model->updateStatusBanca($idBanca,$avaliacao);	
		
		$caio = "cfp@icomp.ufam.edu.br";
		$caio2 = "pinheiro.caiof@gmail.com";

		// subject
		$subject  = "TESTE - [IComp/UFAM] Solicitacao de Afastamento do DCC";

		// message
		$message .= "O(A) professor(a) fkahfj enviou uma solicitacao de afastamento do IComp.\r\n\n";
		$message .= "Nome: fsfsa \r\n";
		$message .= "E-mail: fdsafa\r\n";
		$message .= "Local: fdsfa\r\n";
		$message .= "ISSO É APENAS UM TESTE!!!\r\n";
		
		$email[] = $caio;
		$email[] = $caio2;

		JUtility::sendMail($user->email, "Site do IComp: dfdsfas", $email, $subject, $message, false, 'gcarneirobr@gmail.com', NULL, NULL);
			
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

	
	
}

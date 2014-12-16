<?php

/**
 * @version     1.0.0
 * @package     com_defesasorientador
 * @copyright   Copyright (C) 2014. Todos os direitos reservados.
 * @license     GNU General Public License versÃ£o 2 ou posterior; consulte o arquivo License. txt
 * @author      Caio <pinheiro.caiof@gmail.com> - http://
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class DefesasorientadorController extends JController {

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
        require_once JPATH_COMPONENT . '/helpers/defesasorientador.php';

        parent::display($cachable, $urlparams);

        return $this;
    }

    public function solicitarbanca() {
    	
    	$this->default_view = 'solicitarbanca';
    	
    	$this->display();
    		
    }
    
    public function cadastrarbanca() {

    	
    	$idAluno = JRequest::getVar("idaluno");
    	$nomeAluno = JRequest::getVar('nomeAluno');
    	$defesa["titulo"] = JRequest::getVar("titulodefesa");
    	$defesa['data'] = JRequest::getVar('datadefesa');
    	$defesa['resumo'] = JRequest::getVar('resumodefesa');
    	$defesa['tipoDefesa'] = JRequest::getVar('tipoDefesa');
    	
    	$membrosBanca["id"] = JRequest::getVar('idMembroBanca');
    	$membrosBanca['tipoMembro'] = JRequest::getVar('tipoMembroBanca');
    	
    	$defesa['aluno'] = $idAluno;
    	$defesa['membrosBanca'] = $membrosBanca;
    	
		$model = $this->getModel();
		
		$resultado = $model->insertDefesa($defesa);
		
		if (is_array($resultado)) {
			
			$this->set('mensagens', $resultado); 
			
			$this->default_view('solicitarbanca');
			
		
		} else {
			
			// se não houver erros redireciona para view de confirmação			
			
			$this->default_view = 'confirmacao-banca';
			
			$this->set('idaluno', $idAluno);
			
			$this->set('aluno', $model->getAluno());
			$this->set('tipoDefesa', $defesa['tipoDefesa']);
			
			// evitar de cadastrar duas vezes por atualização de página
			unset($_POST);
		
		}
		$this->display();
		
    	
    }
    
}

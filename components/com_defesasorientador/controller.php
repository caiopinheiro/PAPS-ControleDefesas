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

    public function confirmarbanca() {
    	
    	$view = $this->getView('confirmacaobanca', 'html');
    	
    	$tipoLocal = $this->get('tipoLocal');
    	
    	$data = $this->get('data');
    	
    	$view->data = $data;
    	$data = explode('/', $data);
    	
    	$view->dia = $data[0];
    	$view->mes = $data[1];
		$view->ano = $data[2];

		$view->tipoLocal = $tipoLocal;
		
		$view->display();
    }
    
    public function solicitarbanca() {
    	
    	$view = $this->getView('solicitarbanca', 'html');
    	
    	$model = $this->getModel('solicitarbanca');
    	 
    	$view->aluno = $model->getAluno();
    	$view->membrosExternos = $model->getMembrosExternos();
    	$view->membrosInternos = $model->getMembrosInternos();
    	
    	// apresentacao
    	$view->faseDefesa = $model->getFaseDefesa();
    	$view->mapaFases = $model->getMapaFases();
    	$view->orientador = $model->getOrientador();
    	
    	// dados controle
    	$view->finalizouCurso = false;
    	$view->existeSemAprovacao = false;
    	$view->semProeficiencia = false;
    	
    	// dados form
    	$view->mensagens = $this->get('mensagens');
    	$view->titulo = $this->get('titulo');
    	$view->resumo = $this->get('resumo');
    	$view->datadefesa = $this->get('datadefesa');
    	$view->membrosBancaTabela = $this->get('membrosBancaTabela'); 

    	
    		
    	
    	//dados local
    	$view->tipoLocal = $this->get('tipoLocal');
    	$view->localDescricao = $this->get('localDescricao');
    	$view->localSala = $this->get('localSala');
    	$view->localHorario = $this->get('localHorario');
    	
    	$previa =  $this->get('previa');
    	
    	if (isset($previa)) {
    		$view->urlPrevia = 'tmp/' . basename($previa['name']);
    	}
    	
    	$arrayNome = array();
    	$arrayFiliacao = array();
    	
    	for ($i = 0; $i < count($view->membrosBancaTabela['id']); $i++) {
    		 
    		$membro = $model->getMembroBanca($view->membrosBancaTabela['id'][$i]);
    		
    		if (count($membro)) {
	    		$arrayNome[$i] = $membro->nome;
	    		$arrayFiliacao[$i] = $membro->filiacao;
    		} 
    	}
    	
    	$view->membrosBancaTabela['nome'] = $arrayNome;
    	$view->membrosBancaTabela['filiacao'] = $arrayFiliacao;
    	
    	$view->nomeFase = array("P" => "Proeficiência","Q1" => "Qualificação 1", 'Q2' => "Qualificação 2", 'D' => 'Dissertação', 'T' => 'Tese', 'Q' => 'Qualificação');
    	if (!strcmp($view->faseDefesa[0], $view->mapaFases[0]) && !$view->faseDefesa[1]) {
    		$view->semProeficiencia = true;
    	} else if (!$view->faseDefesa[1] && strcmp($view->faseDefesa[0], $view->mapaFases[0])) {
    		$view->existeSemAprovacao = true;
    	} else if ($view->faseDefesa[0] == $view->mapaFases[count($view->mapaFases) - 1]) {
    		$view->finalizouCurso = true;
    	} else {
    		$count = 0;
    		$achou = 0;
    		while ($count < count($view->mapaFases) && !$achou){
    			if ($view->faseDefesa[0] == $view->mapaFases[$count])
    				$achou = 1;
    			$count++;
    		}
    	
    		$view->faseDefesa[0] = $view->mapaFases[$count];
    	}
    	
		$view->tipoLocal = $this->get('tipoLocal');
		$view->localDescricao = $this->get('localDescricao');
		$view->localSala = $this->get('localSala');
		$view->localHorario = $this->get('localHorario');
		
    	$view->display();
    		
    }
    
    public function cadastrarbanca() {

    	
    	$idAluno = JRequest::getVar("idaluno");
    	$nomeAluno = JRequest::getVar('nomeAluno');
    	$defesa["titulo"] = JRequest::getVar("titulodefesa");
    	$defesa['data'] = JRequest::getVar('datadefesa');
    	$defesa['resumo'] = JRequest::getVar('resumodefesa');
    	$defesa['tipoDefesa'] = JRequest::getVar('tipoDefesa');
    	$defesa['previa'] = JRequest::getVar('previa', null, 'files', 'array');
    	$defesa['tipoLocal'] = JRequest::getVar('tipolocal');
    	$defesa['localDescricao'] = JRequest::getVar('localdescricao');
    	$defesa['localSala'] = JRequest::getVar('localsala');
    	$defesa['localHorario'] = JRequest::getVar('localhorario');
    	 
    	$membrosBanca['id'] = JRequest::getVar('idMembroBanca', array(), 'ARRAY');
    	$membrosBanca['tipoMembro'] = JRequest::getVar('tipoMembroBanca', array(), 'ARRAY');
    	$membrosBanca['passagem'] = JRequest::getVar('passagem', array(), 'ARRAY');
    	 
    	
    	$defesa['aluno'] = $idAluno;
    	$defesa['membrosBanca'] = $membrosBanca;
    	
    	$model = $this->getModel('cadastrarbanca');
    	
		$resultado = $model->insertDefesa($defesa);
		
		if (is_array($resultado)) {
			
			$this->set('mensagens', $resultado);
			$this->set('titulo', $defesa['titulo']);
			$this->set('resumo', $defesa['resumo']);
			$this->set('datadefesa', $defesa['data']);
			
			if (!$resultado['semArquivo'] && !$resultado['arquivoTamanho'] && !$resultado['arquivoFormato'])
				$this->set('previa', $defesa['previa']);
			
			$this->set('membrosBancaTabela', $defesa['membrosBanca']);
			$this->set('tipoLocal', $defesa['tipoLocal']);
			$this->set('localDescricao', $defesa['localDescricao']);
			$this->set('localSala', $defesa['localSala']);
			$this->set('localHorario', $defesa['localHorario']);
			$this->execute('solicitarbanca');	
			
		} else {
			
			// se não houver erros redireciona para view de confirmação			
			
			$this->set('idaluno', $idAluno);
			
			$this->set('aluno', $model->getAluno($idAluno));
			$this->set('tipoDefesa', $defesa['tipoDefesa']);
			$this->set('tipoLocal', $defesa['tipoLocal']);

			$this->set('data', $defesa['data']);
			
			$this->execute('confirmarbanca');
			
			// evitar de cadastrar duas vezes por atualização de página
			unset($_POST);
			
			$this->display();
		
		}
		
    	
    }
    
}

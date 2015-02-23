<?php

/**
 * @version     1.0.0
 * @package     com_defesasorientador
 * @copyright   Copyright (C) 2014. Todos os direitos reservados.
 * @license     GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
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
		
		unset($_POST);
    }
    
    public function solicitarbanca() {
    	
    	$view = $this->getView('solicitarbanca', 'html');
    	
    	$model = $this->getModel('solicitarbanca');
    	 
    	$view->aluno = $model->getAluno();
    	
    	//membros da banca
    	
    	$view->presidente = $model->getPresidente();
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
    	$view->titulo = $this->get('titulodefesa');
    	$view->resumo = $this->get('resumo');
    	$view->datadefesa = $this->get('datadefesa');
    	$view->membrosBancaTabela = $this->get('membrosBancaTabela'); 
    	
    	//dados local
    	$view->tipoLocal = $this->get('tipoLocal');
    	$view->localDescricao = $this->get('localDescricao');
    	$view->localSala = $this->get('localSala');
    	$view->localHorario = $this->get('localHorario');
    
    	
    	// examinador para qualifica��o 2 de doutorado
    	$view->emailexaminador = $this->get('emailexaminador');
    	$view->examinador = $this->get('examinador');
    	
    	
    	// dados para valida��o de tese e doutorado - cr�ditos cumpridos
    	$view->creditos = $model->getCreditos();

    	
    	$previa =  $this->get('previa');
    	
    	if (isset($previa)) {
    		$view->urlPrevia = 'tmp/' . urlencode(basename($previa['name']));
    	}
    	
    	$view->previa = $previa['name'];
    	
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
    	
    	$view->nomeFase = array("P" => "Proefici�ncia","Q1" => "Qualifica��o 1", 'Q2' => "Qualifica��o 2", 'D' => 'Disserta��o', 'T' => 'Tese', 'Q' => 'Qualifica��o');
    	
    	// verifica��o � poss�vel cadastrar defesa
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
    	$defesa['tipoLocal'] = JRequest::getVar('tipoLocal');
    	$defesa['localDescricao'] = JRequest::getVar('localDescricao');
    	$defesa['localSala'] = JRequest::getVar('localSala');
    	$defesa['localHorario'] = JRequest::getVar('localHorario');
    	$defesa['examinador'] = JRequest::getVar('examinador'); 
    	$defesa['emailexaminador'] = JRequest::getVar('emailexaminador');
    	$defesa['previaanterior'] = JRequest::getVar('previaanterior');
    	
    	// flag para indicar que a previa j� foi validada
    	$defesa['flagPrevia'] = false;
    	
    	if (!($defesa['previa']['size']) && strlen($defesa['previaanterior'])) {
    		$defesa['flagPrevia'] = true;
    		$defesa['previa']['name'] = $defesa['previaanterior'];
    	}
    	
    	
    	$membrosBanca['id'] = JRequest::getVar('idMembroBanca', array(), 'ARRAY');
    	$membrosBanca['tipoMembro'] = JRequest::getVar('tipoMembroBanca', array(), 'ARRAY');
    	$membrosBanca['passagem'] = JRequest::getVar('passagem', array(), 'ARRAY');
    	 
    	
    	$defesa['aluno'] = $idAluno;
    	$defesa['membrosBanca'] = $membrosBanca;
    	
    	$model = $this->getModel('cadastrarbanca');
    	
		$resultado = $model->insertDefesa($defesa);
		
		if (is_array($resultado)) {
			
			$this->set('mensagens', $resultado);
			$this->set('resumo', $defesa['resumo']);
			$this->set('datadefesa', $defesa['data']);
			
			if (!$resultado['semArquivo'] && !$resultado['arquivoTamanho'] && !$resultado['arquivoFormato'])
				$this->set('previa', $defesa['previa']);
			
			var_dump($resultado);
			
			$this->set('titulodefesa', $defesa['titulo']);
			$this->set('membrosBancaTabela', $defesa['membrosBanca']);
			$this->set('tipoLocal', $defesa['tipoLocal']);
			$this->set('localDescricao', $defesa['localDescricao']);
			$this->set('localSala', $defesa['localSala']);
			$this->set('localHorario', $defesa['localHorario']);
			$this->set('examinador', $defesa['examinador']);
			$this->set('emailexaminador', $defesa['emailexaminador']);
			$this->set('flagPrevia', $defesa['flagPrevia']);
			
			$this->execute('solicitarbanca');	
			
		} else {
			
			// se n�o houver erros redireciona para view de confirma��o			
			
			$this->set('iddefesa', $resultado);
			
			$this->set('idaluno', $idAluno);
			
			$aluno = $model->getAluno($idAluno);
			
			// enviar e-mail apenas se for qualifica��o 1 de doutorado
			if ($aluno->curso == 2 && $defesa['tipoDefesa'])
			$this->enviarEmailExaminador($defesa['examinador'], $defesa['emailexaminador'], $resultado);
			
			$this->set('aluno', $model->getAluno($idAluno));
			$this->set('tipoDefesa', $defesa['tipoDefesa']);
			$this->set('tipoLocal', $defesa['tipoLocal']);

			$this->set('data', $defesa['data']);
			
			$this->execute('confirmarbanca');
			
			// evitar de cadastrar duas vezes por atualiza��o de p�gina
			
		}
		
    }
    
    public function detalhesDefesa(){
    	$idDefesa = JRequest::getVar('idDefesa');
    	$idAluno = JRequest::getVar('idAluno');
    	$isUpdate = JRequest::getVar('isUpdate');
    
    	header('Location: index.php?option=com_defesasorientador&view=detalhesdefesa&idDefesa='.$idDefesa.'&idAluno='.$idAluno.'&isUpdate='.$isUpdate);
    }
    
    public function atualizarDefesa(){
    	$idDefesa = JRequest::getVar('idDefesa');
    	$idAluno = JRequest::getVar('idAluno');
    	$titulo = JRequest::getVar('titulo');
    	$dataDefesa = JRequest::getVar('dataDefesa');
    	$horarioDefesa = JRequest::getVar('horarioDefesa');
    	$localDefesa = JRequest::getVar('localDefesa');
    	$resumo = JRequest::getVar('resumo');
    
    	$model = $this->getModel('detalhesdefesa');
    	$resultado = $model->updateDefesa($idDefesa, $idAluno, $titulo, $dataDefesa, $horarioDefesa, $localDefesa, $resumo);
    
    	header('Location: index.php?option=com_defesasorientador&view=detalhesdefesa&idDefesa='.$idDefesa.'&idAluno='.$idAluno.'&isUpdate=1&updated='.$resultado);
    }
    
    public function deletarDefesa(){
    	$idDefesa = JRequest::getVar('idDefesa');
    	$idAluno = JRequest::getVar('idAluno');
    	$idBanca = JRequest::getVar('idBanca');
    
    	$model = $this->getModel('detalhesdefesa');
    	$resultado = $model->deleteDefesa($idDefesa, $idAluno, $idBanca);
    
    	if($resultado == 0){
    		echo '<script>';
    		echo 'alert("ERRO: O comando falhou. Tente Novamente.");';
    		echo 'location.href="index.php?option=com_defesasorientador&view=listadefesas"';
    		echo '</script>';
    	}
    	else
    	{
    		echo '<script>';
    		echo 'alert("Defesa deletada com sucesso!");';
    		echo 'location.href="index.php?option=com_defesasorientador&view=listadefesas"';
    		echo '</script>';
    	}
    	exit;
    }
    
    private function enviarEmailExaminador($nomeExaminador, $emailExaminador, $idDefesa){
    	
    	
    	$model = $this->getModel('cadastrarbanca');
    	
    	$defesa = $model->visualizarDefesa($idDefesa);// precisa pegar os dados da defesa (data, titulo e previa)
    	$aluno = $model->visualizarAluno($idDefesa);// precisa pega os dados do aluno (nome)
    
    	$titulo = $defesa[0]->titulo;	// e o titulo
    	$previaDefesa = $defesa[0]->previa; //pega a previa
    		
    
    	if($emailExaminador != null){
    			
    		// subject
    		$subject  = "[IComp/UFAM] Convite de Participação de Defesa";
    			
    		// message
    		$message = "A Coordenação do Programa de Pós-graduação em Informática PPGI/UFAM tem o prazer de convidá-lo para examinar a Qualificação de Doutorado:\r\n\n";
    		$message .= "$titulo\r\n\n";
    		$message .= "CANDIDATO: ".$aluno[0]->nome_aluno."\r\n\n";
    		$message .= "EXAMINADOR(A): \r\n";
    			
    		$message .= "$nomeExaminador\r\n";
    			
    			
    		$data = explode("-", $defesa[0]->data);
    		$data = $data[2] . "/" . $data[1] . "/" .$data[0] ;
    			
    		$message .= "\n";
    		$message .= "DATA: ".$data."\r\n\n";
    		$message .= "Reiteramos o nosso prazer em tê-lo como participante de um momento tão importantes.\r\n\n";
    		$message .= "Atenciosamente,\r\n\n";
    		$message .= "Profa. Eulanda M. dos Santos\r\n"  ;
    		$message .= "Coordenadora do PPGI\r\n";
    			
    		$path []= "components/com_defesasorientador/previas/".$previaDefesa;
    	
    		return JUtility::sendMail(null, "IComp: Controle de Defesas", $emailExaminador, utf8_decode($subject), utf8_decode($message), false, NULL, NULL, $path);
    	}
    }
    
    
}
	

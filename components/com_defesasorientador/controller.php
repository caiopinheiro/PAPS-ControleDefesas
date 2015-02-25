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
    
    	
    	// examinador para qualificação 2 de doutorado
    	$view->emailexaminador = $this->get('emailexaminador');
    	$view->examinador = $this->get('examinador');
    	
    	
    	// dados para validação de tese e doutorado - créditos cumpridos
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
    	
    	$view->nomeFase = array("P" => "Proeficiência","Q1" => "Qualificação 1", 'Q2' => "Qualificação 2", 'D' => 'Dissertação', 'T' => 'Tese', 'Q' => 'Qualificação');
    	
    	// verificação é possível cadastrar defesa
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
    	
    	// flag para indicar que a previa já foi validada
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
			
			// se não houver erros redireciona para view de confirmação			
			
			$this->set('iddefesa', $resultado);
			
			$this->set('idaluno', $idAluno);
			
			$aluno = $model->getAluno($idAluno);
			
			// enviar e-mail apenas se for qualificação 1 de doutorado
			if ($aluno->curso == 2 && $defesa['tipoDefesa'])
			$this->enviarEmailExaminador($defesa['examinador'], $defesa['emailexaminador'], $resultado);
			
			$this->set('aluno', $model->getAluno($idAluno));
			$this->set('tipoDefesa', $defesa['tipoDefesa']);
			$this->set('tipoLocal', $defesa['tipoLocal']);

			$this->set('data', $defesa['data']);
			
			$this->execute('confirmarbanca');
			
			// evitar de cadastrar duas vezes por atualização de página
			
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
    		$message = "A CoordenaÃ§Ã£o do Programa de PÃ³s-graduaÃ§Ã£o em InformÃ¡tica PPGI/UFAM tem o prazer de convidÃ¡-lo para examinar a QualificaÃ§Ã£o de Doutorado:\r\n\n";
    		$message .= "$titulo\r\n\n";
    		$message .= "CANDIDATO: ".$aluno[0]->nome_aluno."\r\n\n";
    		$message .= "EXAMINADOR(A): \r\n";
    			
    		$message .= "$nomeExaminador\r\n";
    			
    			
    		$data = explode("-", $defesa[0]->data);
    		$data = $data[2] . "/" . $data[1] . "/" .$data[0] ;
    			
    		$message .= "\n";
    		$message .= "DATA: ".$data."\r\n\n";
    		$message .= "Reiteramos o nosso prazer em tÃª-lo como participante de um momento tÃ£o importantes.\r\n\n";
    		$message .= "Atenciosamente,\r\n\n";
    		$message .= "Profa. Eulanda M. dos Santos\r\n"  ;
    		$message .= "Coordenadora do PPGI\r\n";
    			

    		$path []= "components/com_defesasorientador/previas/".$previaDefesa;
    	
    		return JUtility::sendMail(null, "IComp: Controle de Defesas", $emailExaminador, mb_convert_encoding($subject, 'UTF-8', 'ISO-8859-1'), utf8_decode($message), false, NULL, NULL, $path);
    	}
    }
    
    /**
     * função usada para migrar os dados
     * 
     * para migrar os dados colocar como public
     * para bloquear acessos externos colocar como private
     */
    public function migrardados() {
    	
    	$database =& JFactory::getDBO();

    	// migração defesas de qualificação 1 de mestrado
    	$sqlmestrado = "select * from #__aluno where curso = 1";
		$database->setQuery($sqlmestrado);
		
		$aluno = $database->loadObjectList();
		
    	foreach ($aluno as $alunomestrado) {
    		
    		if (strlen($alunomestrado->tituloQual2)) {
				
	    		$sqlbanca = "select * from #__banca where idAluno = $alunomestrado->id and tipoDefesa = 'Q'";
	    		$database->setQuery($sqlbanca);
	    		
	    		$bancas = $database->loadObjectList();
	    		
	    		$insertBanca = "insert into #__banca_controledefesas (status_banca, justificativa) VALUES (1, '')";
	    		
	    		$database->setQuery($insertBanca);
	    		$database->execute();
				$idBanca = $database->insertid();

				
				if (sizeof($bancas))
				foreach ($bancas as $banca) {
					
					if ($banca->idMembro != '0' && $banca->funcao != 'P') {
						$insertBanca = "insert into #__banca_hasmembrosbanca (banca_id, membrosbanca_id, funcao, passagem) VALUES ($idBanca, $banca->idMembro, '$banca->funcao', 'N')";
						$database->setQuery($insertBanca);
						$database->execute();
					}
				}
				
				$insertDefesa = "insert into #__defesa (aluno_id, banca_id, resumo, data, local, horario, tipoDefesa, titulo, conceito) values 
								($alunomestrado->id, $idBanca, '$alunomestrado->resumoQual2', str_to_date('$alunomestrado->dataQual2', '%d/%m/%Y'), '$alunomestrado->localQual2', '$alunomestrado->horarioQual2', 'Q1', '$alunomestrado->tituloQual2', '$alunomestrado->conceitoQual2')";
				
				$database->setQuery($insertDefesa);
				$database->execute();
				
    		}
    	}
    	
    	
    	// migração dos dados de dissertação
    	
    	foreach ($aluno as $alunomestrado) {
    		
    		if (strlen($alunomestrado->tituloTese)) {
    		
    			$sqlbanca = "select * from #__banca where idAluno = $alunomestrado->id and tipoDefesa = 'D'";
    			$database->setQuery($sqlbanca);
    			 
    			$bancas = $database->loadObjectList();
    			 
    			$insertBanca = "insert into #__banca_controledefesas (status_banca, justificativa) VALUES (1, '')";
    			 
    			$database->setQuery($insertBanca);
    			 
    			$database->execute();
    			
    			$idBanca = $database->insertid();
    			
    			if (sizeof($bancas))
    			foreach ($bancas as $banca) {
    				if ($banca->idMembro != '0' && $banca->funcao != 'P') {
    					$insertBanca = "insert into #__banca_hasmembrosbanca (banca_id, membrosbanca_id, funcao, passagem) VALUES ($idBanca, $banca->idMembro, '$banca->funcao', 'N')";
	    				$database->setQuery($insertBanca);
	    				
	    				$database->execute();
    				}
    			}
    			
    			$insertDefesa = "insert into #__defesa (numDefesa, aluno_id, banca_id, resumo, data, local, horario, tipoDefesa, titulo, conceito) values
    			(" . (!is_null($alunomestrado->numDefesa) ? $alunomestrado->numDefesa : 'NULL') . ", $alunomestrado->id, $idBanca, '$alunomestrado->resumoTese', 
    				 str_to_date('$alunomestrado->dataTese', '%d/%m/%Y'), '$alunomestrado->localTese', '$alunomestrado->horarioTese', 'D', '$alunomestrado->tituloTese',
    			 '$alunomestrado->conceitoTese')";	
    			$database->setQuery($insertDefesa);
    			$database->execute();
    		}
    	}

    	// migração defesas de doutorado
    	$sqldoutorado = "select * from #__aluno where curso = 2";

    	$database->setQuery($sqldoutorado);
    	
    	$aluno = $database->loadObjectList();
    	
    	
    	//migração de qualificação 2 de doutorado
    	foreach ($aluno as $alunomestrado) {
    	
    	
    		if (strlen($alunomestrado->tituloQual2)) {
    			 
    	
    			$sqlbanca = "select * from #__banca where idAluno = $alunomestrado->id and tipoDefesa = 'Q2";
    			$database->setQuery($sqlbanca);
    			 
    			$bancas = $database->loadObjectList();
    			 
    			$insertBanca = "insert into #__banca_controledefesas (status_banca, justificativa) VALUES (1, '')";
    			 
  	  			$database->setQuery($insertBanca);
				$database->execute();
  	  			
  	  			
    			$idBanca = $database->insertid();
    			if (sizeof($bancas))
    			foreach ($bancas as $banca) {
    				if ($banca->idMembro != '0' && $banca->funcao != 'P') {
    					$insertBanca = "insert into #__banca_hasmembrosbanca (banca_id, membrosbanca_id, funcao, passagem) VALUES ($idBanca, $banca->idMembro, '$banca->funcao', 'N')";
    					$database->setQuery($insertBanca);
    					$database->execute();
    				}
    			}
    			
    			$insertDefesa = "insert into #__defesa (aluno_id, banca_id, resumo, data, local, horario, tipoDefesa, titulo, conceito) values
    			($alunomestrado->id, $idBanca, '$alunomestrado->resumoQual2', str_to_date('$alunomestrado->dataQual2, 'd/m/Y')', '$alunomestrado->localQual2',
    			'$alunomestrado->horarioQual2', 'Q2', '$alunomestrado->tituloQual2', '$alunomestrado->conceitoQual2')";
    		
    			$database->setQuery($insertDefesa);
    			 
    			$database->execute();
    		}
    	}
    		 
    		// migração dos dados de tese
    		 
    		foreach ($aluno as $alunomestrado) {
    	
	    		if (strlen($alunomestrado->tituloTese)) {
	    	
		    		$sqlbanca = "select * from #__banca where idAluno = $alunomestrado->id and tipoDefesa = 'T'";
		    		$database->setQuery($sqlbanca);
		    	
		    				$bancas = $database->loadObjectList();
		    	
		    				$insertBanca = "insert into #__banca_controledefesas (status_banca, justificativa) VALUES (1, '')";
		    	
		    			$database->setQuery($insertBanca);
		    			
		    			$database->execute();
		    	
		    			$idBanca = $database->insertid();
		    	
		    		if (sizeof($bancas))
		    		foreach ($bancas as $banca) {
		    		if ($banca->idMembro != '0' && $banca->funcao != 'P')
		    			$insertBanca = "insert into #__banca_hasmembrosbanca (banca_id, membrosbanca_id, funcao, passagem) VALUES ($idBanca, $banca->idMembro, '$banca->funcao', 'N')";
		    		  	 $database->setQuery($insertBanca);
						$database->execute();
		    		}
		    		
		    		$insertDefesa = "insert into #__defesa (numDefesa, aluno_id, banca_id, resumo, data, local, horario, tipoDefesa, titulo, conceito) values
	    					(" . (!is_null($alunomestrado->numDefesa) ? $alunomestrado->numDefesa : 'NULL') . ", $alunomestrado->id, $idBanca, '$alunomestrado->resumoTese',
		    			    					str_to_date('$alunomestrado->dataTese', '%d/%m/%Y'), '$alunomestrado->localTese', '$alunomestrado->horarioTese', 'T', '$alunomestrado->tituloTese',
		    			    					'$alunomestrado->conceitoTese')";
		    		
		    		$database->setQuery($insertDefesa);
		    		
		    		$database->execute();
		    	}
    	
    		}
			
    		// migração dos dados de qualificação 1 de doutorado
    		 
    		foreach ($aluno as $alunomestrado) {
    			 
    			if (strlen(trim($alunomestrado->tituloQual1))) {
    				 
    				$insertBanca = "insert into #__banca_controledefesas (status_banca, justificativa) VALUES (1, '')";
    				 
    				$database->setQuery($insertBanca);
					$database->execute();    				 
    				
					$idBanca = $database->insertid();
    				 
    				$insertDefesa = "insert into #__defesa (aluno_id, banca_id, resumo, data, tipoDefesa, titulo, conceito, examinador) values
	    					(" . "$alunomestrado->id, $idBanca, '',
    			    					str_to_date('$alunomestrado->dataQual1', '%d/%m/%Y'), 'Q1', '$alunomestrado->tituloQual1',
    			    					'$alunomestrado->conceitoQual1', '$alunomestrado->examinadorQual1')";
    				
    				$database->setQuery($insertDefesa);
    				
    				$database->execute();
    			}	
    			 
    		}
    
    }
    
}
	

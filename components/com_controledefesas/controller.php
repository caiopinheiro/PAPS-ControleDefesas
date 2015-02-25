<?php
/**
 * @version     1.0.0
 * @package     com_defesascoordenador
 * @copyright   Copyright (C) 2014. Todos os direitos reservados.
 * @license     GNU General Public License versÃ£o 2 ou posterior; consulte o arquivo License. txt
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
	
	public function gerarAta(){	
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		header('Location: index.php?option=com_controledefesas&view=gerarata&idDefesa='.$idDefesa.'&idAluno='.$idAluno);
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
	
	public function setarNumDefesa(){
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		$numDefesa = JRequest::getVar('numDefesa');
		$tipoDefesa = JRequest::getVar('tipoDefesa');
		$model = $this->getModel('conceitos');
		
		$status = $model->checkNumDefesa($numDefesa, $tipoDefesa);	
		$status_update = NULL;
		
		if(!$status){
			$status_update = $model->updateNumDefesa($idDefesa,$numDefesa);
			header('Location: index.php?option=com_controledefesas&view=conceitos&idAluno='.$idAluno.'&idDefesa='.$idDefesa.'&status='.$status_update);
		}
		else{
			header('Location: index.php?option=com_controledefesas&view=conceitos&idAluno='.$idAluno.'&idDefesa='.$idDefesa.'&checkNum='.$status);
		}
	}
	
	public function gerarConviteDefesa(){
		//configuraÃ§Ãµes iniciais
		require('./components/com_controledefesas/pdf/pdf.php');
		//require('./components/com_defesascoordenador/emails/enviarConvite.php');
		
		//$view = $this->getView('listabancas', 'html');
		$model = $this->getModel('listabancas');	
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
			
		$membrosBanca = $model->visualizarMembrosBanca($idDefesa);
		$defesa = $model->visualizarDefesa($idDefesa);
		$aluno = $model->visualizarAluno($idDefesa);
		$banca = $model->visualizarBanca($idDefesa);
		
/*		$membrosBanca = $view->membrosBanca;	
		$aluno = $view->aluno;
		$defesa = $view->defesa;
		$banca = $view->banca;
	*/	
		//if($banca[0]->status_banca != NULL){
			$chave = $aluno[0]->nome_aluno;
			$data = explode("-", $defesa[0]->data);
			$data = $data[2] . "/" . $data[1] . "/" .$data[0] ;	 
			//$pdf = new FPDF('P','cm','A4');
			$pdf = new PDF();
			$pdf->Open();
			$pdf->AddPage();
			//titulos de configuraÃ§Ã£o do documento
			$pdf->SetTitle("Convite de Defesa");
			
			// OBTENDO OS DADOS A SEREM PREENCHIDOS
			
			$pdf->SetFont("Helvetica",'B', 14);
			$pdf->MultiCell(0,7,"",0, 'C');
			$pdf->MultiCell(0,5,utf8_decode('CONVITE À COMUNIDADE'),0, 'C');
			$pdf->MultiCell(0,5,"",0, 'C');
			
			$tag = " A Coordenação do Programa de Pós-Graduação em Informática PPGI/UFAM tem o prazer de convidar toda a comunidade para a sessão pública de apresentação de defesa de";
			if ($defesa[0]->tipoDefesa == 'Q1' OR $defesa[0]->tipoDefesa == 'Q2' ) {
				if ($aluno[0]->curso == 2){
					$tag = $tag . " exame de qualificação de doutorado:";
					$chave .= "_defesa_qualific_doutorado_convite";
				}
				else{
					$tag = $tag . " exame de qualificação de mestrado:";
					$chave .= "_defesa_qualific_mestrado_convite";
				}
			} else {
				if ($aluno[0]->curso == 2){
					$tag = $tag . " tese:";
					$chave .= "_defesa_tese_doutorado_convite";
				}
				else{
					$tag = $tag . " dissertação:";
					$chave .= "_defesa_dissertacao_mestrado_convite";
				}
			}
			$pdf->SetFont("Helvetica",'', 10);
			$pdf->MultiCell(0,6,utf8_decode($tag),0, 'J');
			$pdf->MultiCell(0,5,"",0, 'C');
			
			$pdf->SetFont("Helvetica",'B', 12);		
			$pdf->MultiCell(0,6,utf8_decode($defesa[0]->titulo),0, 'C');
			$pdf->MultiCell(0,5,"",0, 'C');
			$pdf->SetFont("Helvetica",'', 11);
			$pdf->MultiCell(0,6,"RESUMO: " . utf8_decode($defesa[0]->resumo),0, 'J');
			
			$pdf->MultiCell(0,5,"",0, 'C');
			$pdf->MultiCell(0,6,"CANDIDATO(A): " . utf8_decode($aluno[0]->nome_aluno),0, 'J');
			$pdf->MultiCell(0,5,"",0, 'C');
			$pdf->MultiCell(0,6,"BANCA EXAMINADORA: ",0, 'J');
			foreach ($membrosBanca as $membro) {
				$tag = "                        " . utf8_decode($membro->nome) . " - " . utf8_decode($membro->filiacao);
				if ($membro->funcao == "P")
					$tag = $tag . " (Presidente)";
				$pdf->MultiCell(0,6,$tag,0, 'J');
			}
			$pdf->MultiCell(0,5,"",0, 'C');
			
			$pdf->MultiCell(0,6,"LOCAL: " . utf8_decode($defesa[0]->local),0, 'J');
			$pdf->MultiCell(0,5,"",0, 'C');
			$pdf->MultiCell(0,6,"DATA: " . utf8_decode($data),0, 'J');
			$pdf->MultiCell(0,5,"",0, 'C');
			$pdf->MultiCell(0,6,utf8_decode("HORÁRIO: ") . utf8_decode($defesa[0]->horario),0, 'J');
			$pdf->MultiCell(0,5,"",0, 'C');
			$pdf->MultiCell(0,5,"",0, 'C');
			$pdf->SetFont("Helvetica",'', 10);
			$pdf->MultiCell(0,4,"Professora Dra. Eulanda Miranda dos Santos",0, 'C');
			$pdf->SetFont("Helvetica",'', 8);
			$pdf->MultiCell(0,4,utf8_decode("Coordenadora do Programa de Pós-Graduação em Informática PPGI/UFAM"),0, 'C');
			ob_clean(); // Limpa o buffer de saÃ­da
			
			//cria o arquivo pdf e exibe no navegador
			$pdf->Output('components/com_controledefesas/convites/'.$chave.'.pdf','I');
			
			exit;	
		}
	
	public function enviarSolicitacaoPassagem($idDefesa){
		$view = $this->getView('conceitos', 'html');
		$model = $this->getModel('conceitos');	
//		$idDefesa = JRequest::getVar('idDefesa');
		
		$view->membrosBanca = $model->visualizarMembrosBanca($idDefesa);
    	
		$membrosBanca = $view->membrosBanca;
		$sucesso = false;
		$emails = null;	
			
		$formSolicitacao = "Form_passagem_diarias.doc";
			
		foreach( $membrosBanca as $membro ){	
			if($membro->passagem == 'S')
				$emails[] = $membro->email;
		}
	/*	echo "<pre>";
		print_r($membrosBanca);
		print_r($emails);	
		print_r(isset($emails));
	*/	
		if($emails != null){
				
			// subject
			$subject  = "[IComp/UFAM] SolicitaÃƒÂ§ÃƒÂ£o de Passagem AÃƒÂ©rea";
					
			// message
			$message .= "A CoordenaÃƒÂ§ÃƒÂ£o do Programa de PÃƒÂ³s-graduaÃƒÂ§ÃƒÂ£o em InformÃƒÂ¡tica PPGI/UFAM tem o prazer de tÃƒÂª-lo para a sessÃƒÂ£o pÃƒÂºblica de apresentaÃƒÂ§ÃƒÂ£o da Defesa de DissertaÃƒÂ§ÃƒÂ£o/Tese de Mestrado/Doutorado.\r\n\n";
			
			$message .= "Considerando a sua participaÃƒÂ§ÃƒÂ£o, pedimos o preenchimento do FormulÃƒÂ¡rio de SolicitaÃƒÂ§ÃƒÂ£o de Passagens e DiÃƒÂ¡rias, que estÃƒÂ¡ em anexo ÃƒÂ  este email. Rogamos, ainda, o fornecimento dos seguintes dados, para fins de cadastro em nosso sistema de gerenciamento da PÃƒÂ³s-GraduaÃƒÂ§ÃƒÂ£o:\r\n\n";
			$message .= "a. Nome completo;\n";
			$message .=	"b. Data de nascimento;\n";
			$message .= "c. Data de sua diplomaÃƒÂ§ÃƒÂ£o em PÃƒÂ³s-GraduaÃƒÂ§ÃƒÂ£o, e nome da InstituiÃƒÂ§ÃƒÂ£o em que diplomou-se;\n";
			$message .=	"d. Data de inÃƒÂ­cio do vÃƒÂ­nculo com sua IES;\n";
			$message .= "e. SugestÃƒÂ£o de Voo.\r\n\n";
			$message .= "Solicitamos que, apÃƒÂ³s o preenchimento do FormulÃƒÂ¡rio de SolicitaÃƒÂ§ÃƒÂ£o de Passagens e DiÃƒÂ¡rias, bem como dos demais dados solicitados, os mesmos sejam encaminhados ao email de nossa Secretaria: secretariappgi@icomp.ufam.edu.br.\r\n\n";
				
			$message .= "Por fim, reiteramos o nosso prazer em tÃƒÂª-lo como participante de um momento tÃƒÂ£o importante, e esperamos, sinceramente, que outros mais venham.\r\n\n";
			
			$message .= "Atenciosamente,\r\n\n";
			
			$message .= "Profa. Eulanda M. dos Santos\r\n";
			$message .= "Coordenadora do PPGI\r\n";
			
			
			$path = "components/com_defesascoordenador/forms/".$formSolicitacao;
			
			$sucesso= JUtility::sendMail($user->email, "IComp: Controle de Defesas", $emails, utf8_decode($subject), utf8_decode($message), false, NULL, NULL, $path);
		}
		
		return $sucesso;		
	}
	
	public function enviarSolicitacao(){				
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		$status = $this->enviarSolicitacaoPassagem($idDefesa);				
	
		header('Location: index.php?option=com_controledefesas&view=conceitos&idAluno='.$idAluno.'&idDefesa='.$idDefesa.'&status='.$status);
	}
	
	public function emailExaminador(){				
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		$status = $this->enviarEmailExaminador($idDefesa,$idAluno);				
		
		header('Location: index.php?option=com_controledefesas&view=conceitos&idAluno='.$idAluno.'&idDefesa='.$idDefesa.'&status='.$status);
	}
	
	public function emitirRelatorioDefesas(){
	
		//configura??es iniciais
		require('./components/com_controledefesas/pdf/pdf.php');
	
		$data_inicial = JRequest::getVar('dataInicial', false);
		$data_final = JRequest::getVar('dataFinal', false);
		$nome_professor = JRequest::getVar('nomeProfessor', false);
		$id_membro_banca = JRequest::getVar('idMembroBanca', false);
		$id_professor = JRequest::getVar('idProfessor', false);
	
		$view = $this->getView('relatoriodefesas', 'html');
		$model = $this->getModel('relatoriodefesas');
	
		$view->defesas = NULL;
	
		if($nome_professor == NULL || $nome_professor == false || $nome_professor == '')
			$view->defesas = $model->getDefesasPorPeriodo($data_inicial, $data_final);
		else
			$view->defesas = $model->getDefesasPorPeriodoProfessor($data_inicial, $data_final, utf8_decode($nome_professor), $id_membro_banca, $id_professor);
	
		$defesas = $view->defesas;
	
		if($defesas != NULL){
	
			$pdf = new PDF();
			$pdf->Open();
			$pdf->AddPage();
	
			//titulos de configuração do documento
			$pdf->SetTitle("Relatorio de Defesas");
			$pdf->SetFillColor(105,105,105);
	
			$pdf->SetFont("Helvetica",'B', 14);
			$pdf->MultiCell(0,7,"",0, 'C');
			$pdf->MultiCell(0,5,utf8_decode('RELATÓRIO DE DEFESAS'),0, 'C');
			$pdf->MultiCell(0,5,"",0, 'C');
	
	
			if($nome_professor == NULL || $nome_professor == false || $nome_professor == ''){
					
				$pdf->SetFont("Helvetica",'B', 11);

				if (($data_inicial != NULL && $data_inicial != false && $data_inicial != '') && ($data_final != NULL && $data_final != false && $data_final != '')){

					$aux_data_inicial = $this->formatarData($data_inicial);
					$aux_data_final = $this->formatarData($data_final);

					$pdf->MultiCell(0,5,utf8_decode("Período: ").utf8_decode($aux_data_inicial)." a ".utf8_decode($aux_data_final),0, 'C');
				}
				else
				{
					if ($data_final == NULL || $data_final == false || $data_final == ''){
						$aux_data_inicial = $this->formatarData($data_inicial);
						$pdf->MultiCell(0,5,utf8_decode("Data Inicial: ").utf8_decode($aux_data_inicial),0, 'C');
					}
					else if ($data_inicial == NULL || $data_inicial == false || $data_inicial == ''){
						$aux_data_final = $this->formatarData($data_final);
						$pdf->MultiCell(0,5,utf8_decode("Data Final: ").utf8_decode($aux_data_final),0, 'C');
					}
				}
	
				$pdf->MultiCell(0,5,"",0, 'C');
	
				$pdf->Cell(75,5,utf8_decode('Nome do Aluno'), 1, 0, 'J', true);
				$pdf->Cell(30,5,utf8_decode('Curso'), 1, 0, 'J', true);
				$pdf->Cell(30,5,utf8_decode('Tipo Defesa'), 1, 0, 'J', true);
				$pdf->Cell(30,5,utf8_decode('Conceito'), 1, 0, 'J', true);
				$pdf->Cell(25,5,utf8_decode('Data'), 1, 1, 'J', true);
	
				$pdf->SetFont("Helvetica",'', 8);
	
				foreach ($defesas as $defesa) {
					$pdf->Cell(75,5,utf8_decode($defesa->nome_aluno), 0, 0, 'J');
					$pdf->Cell(30,5,utf8_decode($defesa->desc_curso), 0, 0, 'J');
					$pdf->Cell(30,5,utf8_decode($defesa->desc_tipo_defesa), 0, 0, 'J');
					$pdf->Cell(30,5,utf8_decode($defesa->conceito_defesa), 0, 0, 'J');
					$pdf->Cell(25,5,utf8_decode($defesa->data_defesa), 0, 1, 'J');
				}
				
				$pdf->SetFont("Helvetica",'B', 9);
				$pdf->MultiCell(0,5,"",0, 'C');
				$pdf->MultiCell(0,5,utf8_decode("Total de Defesas: ").utf8_decode(sizeof($defesas)),0, 'J');
			}
			else{
	
				$pdf->SetFont("Helvetica",'B', 11);

				$pdf->MultiCell(0,5,utf8_decode($nome_professor),0, 'C');
				$pdf->MultiCell(0,5,"",0, 'C');
					
				$pdf->Cell(70,5,utf8_decode('Nome do Aluno'), 1, 0, 'J', true);
				$pdf->Cell(20,5,utf8_decode('Curso'), 1, 0, 'J', true);
				$pdf->Cell(25,5,utf8_decode('Tipo Defesa'), 1, 0, 'J', true);
				$pdf->Cell(25,5,utf8_decode('Conceito'), 1, 0, 'J', true);
				$pdf->Cell(20,5,utf8_decode('Data'), 1, 0, 'J', true);
				$pdf->Cell(29,5,utf8_decode('Função'), 1, 1, 'J', true);
	
				$pdf->SetFont("Helvetica",'', 8);
					
				foreach ($defesas as $defesa) {
					$pdf->Cell(70,5,utf8_decode($defesa->nome_aluno), 0, 0, 'J');
					$pdf->Cell(20,5,utf8_decode($defesa->desc_curso), 0, 0, 'J');
					$pdf->Cell(25,5,utf8_decode($defesa->desc_tipo_defesa), 0, 0, 'J');
					$pdf->Cell(25,5,utf8_decode($defesa->conceito_defesa), 0, 0, 'J');
					$pdf->Cell(20,5,utf8_decode($defesa->data_defesa), 0, 0, 'J');
					$pdf->Cell(29,5,utf8_decode($defesa->funcao_membro), 0, 1, 'J');
				}
				
				$pdf->SetFont("Helvetica",'B', 9);
				$pdf->MultiCell(0,5,"",0, 'C');
				$pdf->MultiCell(0,5,utf8_decode("Total de Defesas: ").utf8_decode(sizeof($defesas)),0, 'J');
			}
	
			ob_clean(); // Limpa o buffer de sa?da
	
			//cria o arquivo pdf e exibe no navegador
			$pdf->Output('RelatorioDefesas.pdf','I');
			exit;
		}
		else{
			echo '<script>';
			echo 'alert("Nenhuma defesa foi encontrada!")';
			echo '</script>';
	
			header('Refresh: index.php?option=com_controledefesas&view=relatoriodefesas');
		}
	}

	function formatarData($data){
	    $arraydata = explode("-", $data);
	    $aux = $arraydata[0] . "/" . $arraydata[1] . "/" .$arraydata[2];
	    return $aux;
	}
	
	public function sendNotification(){
		$idAluno = JRequest::getVar('idAluno');
		$exame = JRequest::getVar('exame');
		$sucesso = $this->enviaremail($idAluno,$exame);
		
		header('Location: index.php?option=com_controledefesas&view=listapendente&status='.$sucesso);
		
	}
	
	public function enviaremail($idAluno,$exame){
		$view = $this->getView('listapendente', 'html');
		$model = $this->getModel('listapendente');	
//		$idAluno = JRequest::getVar('idAluno');
//		$exame = JRequest::getVar('exame');
		if ($exame == 'Q1'){
			$tipoexame = "Qualificação I";
		}
		else if ($exame == 'Q2'){
			$tipoexame = "Qualificação II";
		}
		else if ($exame == 'D'){
			$tipoexame = "Dissertação";
		}
		else {
			$tipoexame = "Tese";
		}
			
		$view->aluno = $model->visualizarAluno($idAluno);
		
		var_dump($idAluno);
		$aluno = $view->aluno;
		
			$nome_aluno = $aluno[0]->nome_aluno;
			$emailOrientador = $aluno[0]->profemail;	
			$emailAluno = $aluno[0]->alunoemail;	
			$nomeOrientador = $aluno[0]->nomeProfessor;	
			$emails[] = $emailOrientador;
			$emails[] = $emailAluno;
			$emails[] = "secretariappgi@icomp.ufam.edu.br";
			//$emails[] = "coordpesquisa@icomp.ufam.edu.br";
			
			
			// subject
			$subject  = "[IComp/UFAM] Pendência em relação à Defesa";
			
			// message
			$message .= "Informamos que há uma pendência de defesa do aluno abaixo relacionado: \r\n\n";
			$message .= "CANDIDATO: ".$nome_aluno."\r\n";
			$message .= "ORIENTADOR: ".$nomeOrientador."\r\n";
			$message .= "EXAME: ".$tipoexame."\r\n\n";
			$message .= "Atenciosamente,\r\n\n";
			$message .= "Secretaria - ICOMP\r\n"  ;
			
			
			return JUtility::sendMail($user->email, "IComp: Controle de Defesas", $emails, $subject, $message, false, NULL, NULL, NULL);
		
	}
	
	public function enviarEmailExaminador($idDefesa, $idAluno){
		
		$model = $this->getModel('conceitos');
		
		$defesa = $model->visualizarDefesa($idDefesa);// precisa pegar os dados da defesa (data, titulo e previa)
		$aluno = $model->visualizarAluno($idAluno);// precisa pega os dados do aluno (nome)
    	
		$titulo = $defesa[0]->titulo;	// pega o titulo	
		$previaDefesa = $defesa[0]->previa; //pega a previa
		$nomeExaminador = $defesa[0]->examinador;
		$emailExaminador = $defesa[0]->emailExaminador;
		$sucesso=NULL;	
		
		if($emailExaminador != null){				
			
			// subject
			$subject  = "[IComp/UFAM] Convite de ParticipaÃ§Ã£o de Defesa";
			
			// message
			$message = "A CoordenaÃ§Ã£o do Programa de PÃ³s-graduaÃ§Ã£o em InformÃ¡tica PPGI/UFAM tem o prazer de convidÃ¡-lo para examinar a Tese de Doutorado:\r\n\n";
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
				
			$sucesso = JUtility::sendMail($this->user->email, "IComp: Controle de Defesas", $emailExaminador, utf8_decode($subject), utf8_decode($message), false, NULL, NULL, $path);
		}
		return $sucesso;
	}
	
    public function detalhesAluno(){
    	$idAluno = JRequest::getVar('idAluno');
    	header('Location: index.php?option=com_controledefesas&view=detalhesaluno&idAluno='.$idAluno);
    }
}

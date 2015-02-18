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
	
	public function setarNumDefesa(){
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		$numDefesa = JRequest::getVar('numDefesa');
		
		
		$model = $this->getModel('listabancas');		
		$status = $model->updateNumDefesa($idDefesa,$numDefesa);	

		header('Location: index.php?option=com_controledefesas&view=conceitos&idAluno='.$idAluno.'&idDefesa='.$idDefesa.'&status='.$status);
	}
	
	public function gerarConviteDefesa(){
		//configurações iniciais
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

			//titulos de configuração do documento
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

			ob_clean(); // Limpa o buffer de saída
			
			//cria o arquivo pdf e exibe no navegador
			$pdf->Output('components/com_controledefesas/convites/'.$chave.'.pdf','I');
			
			exit;	

		}
	
	public function gerarAta(){	
		//configurações iniciais
		require('./components/com_controledefesas/pdf/pdf.php');
		
		$view = $this->getView('listabancas', 'html');
		$model = $this->getModel('listabancas');	
		$idDefesa = JRequest::getVar('idDefesa');
			
		$view->membrosBanca = $model->visualizarMembrosBanca($idDefesa);
		$view->defesa = $model->visualizarDefesa($idDefesa);
		$view->aluno = $model->visualizarAluno($idDefesa);
		
		
		$membrosBanca = $view->membrosBanca;	
		$aluno = $view->aluno;
		$defesa = $view->defesa;
		

		$chave = 'AtaDefesa_'.$aluno[0]->nome_aluno;
		$data = explode("-", $defesa[0]->data);
		//$data = $data[2] . "/" . $data[1] . "/" .$data[0] ;	 
		//$pdf = new FPDF('P','cm','A4');
		$pdf = new PDF();
		$pdf->Open();
		$pdf->AddPage();

		$mes = array (
			"01" => "Janeiro",
			"02" => "Fevereiro",
			"03" => "Março",
			"04" => "Abril",
			"05" => "Maio",
			"06" => "Junho",
			"07" => "Julho",
			"08" => "Agosto",
			"09" => "Setembro",
			"10" => "Outubro",
			"11" => "Novembro",
			"12" => "Dezembro"
		);


		//$pdf = new FPDF('P','cm','A4');
		$pdf = new PDF();
		$pdf->Open();
		$pdf->AddPage();

		//titulos de configuração do documento
		$pdf->SetTitle("Ata de Defesa");
		
		// OBTENDO OS DADOS A SEREM PREENCHIDOS
		if ($defesa[0]->tipoDefesa == 'D' OR $defesa[0]->tipoDefesa == 'T'){	
	//		$data = explode("/", $alunos[0]->dataTese);	
			$hora = $defesa[0]->horario;		
			$local = $defesa[0]->local;		
			$titulo = $defesa[0]->titulo;
			if ($aluno[0]->curso == 2){
				$complemento = "TESE DE DOUTORADO";
				$complemento3 = "tese de doutorado";
				$complemento2 = "doutor";
			}
			else{
				$complemento = "DISSERTAÇÃO DE MESTRADO";
				$complemento3 = "dissertação de mestrado";
				$complemento2 = "mestre";			
			}
		}
		$membrosBanca_text = "";
		foreach ($membrosBanca as $membro) {
			
			if ($membro->funcao == "P"){
				$presidente = $membro->nome. " (" . utf8_decode($membro->filiacao). ") ";
			}
			else{
				$membrosBanca_text = $membrosBanca_text. utf8_decode($membro->nome). " (" . utf8_decode($membro->filiacao). "), ";
			}
		}	
		// OBTENDO OS DADOS A SEREM PREENCHIDOS
		
		$pdf->SetFont("Helvetica",'B', 14);
		$pdf->MultiCell(0,7,"",0, 'C');
		$pdf->MultiCell(0,5,utf8_decode($defesa[0]->numDefesa.'ª ATA DE DEFESA PÚBLICA DE '.$complemento),0, 'C');
		$pdf->MultiCell(0,5,"",0, 'C');
		
		$tag = "Aos ".$data[2]." dias do mês de ".$mes[$data[1]]." do ano de ".$data[0].", às ".$hora.", na ".$local." da Universidade Federal do Amazonas, situada na Av. Rodrigo Otávio, 6.200, Campus Universitário, Setor Norte, Coroado, nesta Capital, ocorreu a sessão pública de defesa de ".$complemento3." intitulada  '".$titulo."' apresentada pelo aluno(a) ".$aluno[0]->nome_aluno." que concluiu todos os pré-requisitos exigidos para a obtenção do título de ".$complemento2." em informática, conforme estabelece o artigo 52 do regimento interno do curso. Os trabalhos foram instalados pelo(a)  ".$presidente.", orientador(a) e presidente da Banca Examinadora, que foi constituída, ainda, por ".$membrosBanca."membros convidados. A Banca Examinadora tendo decidido aceitar a dissertação, passou à arguição pública do candidato. 
		
	Encerrados os trabalhos, os examinadores expressaram o parecer abaixo. 

	A comissão considerou a ".$complemento3.":
	(   ) Aprovada
	(   ) Aprovada condicionalmente, sujeita a alterações, conforme folha de modificações, anexa,
	(   ) Reprovada, conforme folha de modificações, anexa

	Proclamados os resultados, foram encerrados os trabalhos e, para constar, eu, Elienai Nogueira, Secretária do Programa de Pós-Graduação em Informática, lavrei a presente ata, que assino juntamente com os Membros da Banca Examinadora.";

		$pdf->SetFont("Helvetica",'', 10);
		-$pdf->MultiCell(0,6,utf8_decode($tag),0, 'J');
		$pdf->MultiCell(0,5,"",0, 'C');
		$i = 0;
		foreach ($membrosBanca as $membro) {
			$pdf->MultiCell(0,7,"Assinatura: ___________________________________             ".utf8_decode($membro->nome),0, 'J');	
			$pdf->MultiCell(0,5,"",0, 'C');	
			$i++;
		}	
		$pdf->SetXY(10, 255);
		$pdf->MultiCell(0,5,"____________________________________
		Secretaria",0, 'C');	
		$pdf->MultiCell(0,5,"",0, 'C');			
		$pdf->MultiCell(0,5,"Manaus, ".$data[2]." de ". $mes[$data[1]]." de ".$data[0],0, 'C');	

		ob_clean(); // Limpa o buffer de saída
		//cria o arquivo pdf e exibe no navegador
		$pdf->Output('components/com_controledefesas/atas/$chave.pdf','I');
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
			$caio2 = "pinheiro.caiof@gmail.com";	
			$caio1 = "gcarneirobr@gmail.com";	
				
			// subject
			$subject  = "[IComp/UFAM] SolicitaÃ§Ã£o de Passagem AÃ©rea";
					
			// message
			$message .= "A CoordenaÃ§Ã£o do Programa de PÃ³s-graduaÃ§Ã£o em InformÃ¡tica PPGI/UFAM tem o prazer de tÃª-lo para a sessÃ£o pÃºblica de apresentaÃ§Ã£o da Defesa de DissertaÃ§Ã£o/Tese de Mestrado/Doutorado.\r\n\n";
			
			$message .= "Considerando a sua participaÃ§Ã£o, pedimos o preenchimento do FormulÃ¡rio de SolicitaÃ§Ã£o de Passagens e DiÃ¡rias, que estÃ¡ em anexo Ã  este email. Rogamos, ainda, o fornecimento dos seguintes dados, para fins de cadastro em nosso sistema de gerenciamento da PÃ³s-GraduaÃ§Ã£o:\r\n\n";

			$message .= "a. Nome completo;\n";
			$message .=	"b. Data de nascimento;\n";
			$message .= "c. Data de sua diplomaÃ§Ã£o em PÃ³s-GraduaÃ§Ã£o, e nome da InstituiÃ§Ã£o em que diplomou-se;\n";
			$message .=	"d. Data de inÃ­cio do vÃ­nculo com sua IES;\n";
			$message .= "e. SugestÃ£o de Voo.\r\n\n";

			$message .= "Solicitamos que, apÃ³s o preenchimento do FormulÃ¡rio de SolicitaÃ§Ã£o de Passagens e DiÃ¡rias, bem como dos demais dados solicitados, os mesmos sejam encaminhados ao email de nossa Secretaria: secretariappgi@icomp.ufam.edu.br.\r\n\n";
				
			$message .= "Por fim, reiteramos o nosso prazer em tÃª-lo como participante de um momento tÃ£o importante, e esperamos, sinceramente, que outros mais venham.\r\n\n";
			
			$message .= "Atenciosamente,\r\n\n";
			
			$message .= "Profa. Eulanda M. dos Santos\r\n";
			$message .= "Coordenadora do PPGI\r\n";
			
			
			$email[] = $caio2;
			$email[] = $caio1;
		
			
			$path = "components/com_defesascoordenador/forms/".$formSolicitacao;
			
			$sucesso= JUtility::sendMail($user->email, "IComp: Controle de Defesas", $email, utf8_decode($subject), utf8_decode($message), false, NULL, NULL, $path);
		}
		
		return $sucesso;		
	}
	
	public function enviarSolicitacao(){				
		$idDefesa = JRequest::getVar('idDefesa');
		$idAluno = JRequest::getVar('idAluno');
		$status = $this->enviarSolicitacaoPassagem($idDefesa);				
	
		header('Location: index.php?option=com_controledefesas&view=conceitos&idAluno='.$idAluno.'&idDefesa='.$idDefesa.'&status='.$status);
	}

	public function emitirRelatorioDefesas(){
	
		//configura??es iniciais
		require('./components/com_controledefesas/pdf/pdf.php');
	
		$data_inicial = JRequest::getVar('dataInicial', false);
		$data_final = JRequest::getVar('dataFinal', false);
		$nome_professor = JRequest::getVar('nomeProfessor', false);
	
		$view = $this->getView('relatoriodefesas', 'html');
		$model = $this->getModel('relatoriodefesas');
	
		$view->defesas = NULL;
	
		if($nome_professor == NULL || $nome_professor == false || $nome_professor == '')
			$view->defesas = $model->getDefesasPorPeriodo($data_inicial, $data_final);
		else
			$view->defesas = $model->getDefesasPorPeriodoProfessor($data_inicial, $data_final, $nome_professor);
	
		$defesas = $view->defesas;
	
		if($defesas != NULL){
	
			$chave = 'RelatorioDefesas';
	
			$pdf = new PDF();
			$pdf->Open();
			$pdf->AddPage();
	
			//titulos de configura??o do documento
			$pdf->SetTitle("Relatorio de Defesas");
			$pdf->SetFillColor(105,105,105);
	
			$pdf->SetFont("Helvetica",'B', 14);
			$pdf->MultiCell(0,7,"",0, 'C');
			$pdf->MultiCell(0,5,utf8_decode('RELAT?RIO DE DEFESAS'),0, 'C');
			$pdf->MultiCell(0,5,"",0, 'C');
	
	
			if($nome_professor == NULL || $nome_professor == false || $nome_professor == ''){
					
				$pdf->SetFont("Helvetica",'B', 11);
	
				if (($data_inicial != NULL && $data_inicial != false && $data_inicial != '') && ($data_final != NULL && $data_final != false && $data_final != '')){
					$pdf->MultiCell(0,5,utf8_decode("Per?odo: ").utf8_decode($data_inicial)." a ".utf8_decode($data_final),0, 'C');
				}
				else
				{
					if ($data_final == NULL || $data_final == false || $data_final == '')
						$pdf->MultiCell(0,5,utf8_decode("Data Inicial: ").utf8_decode($data_inicial),0, 'C');
					else if ($data_inicial == NULL || $data_inicial == false || $data_inicial == '')
						$pdf->MultiCell(0,5,utf8_decode("Data Final: ").utf8_decode($data_final),0, 'C');
				}
	
				$pdf->MultiCell(0,5,"",0, 'C');
	
				$pdf->Cell(70,5,utf8_decode('Nome Aluno'), 1, 0, 'J', true);
				$pdf->Cell(20,5,utf8_decode('Curso'), 1, 0, 'J', true);
				$pdf->Cell(25,5,utf8_decode('Tipo Defesa'), 1, 0, 'J', true);
				$pdf->Cell(25,5,utf8_decode('Conceito'), 1, 0, 'J', true);
				$pdf->Cell(20,5,utf8_decode('Data'), 1, 1, 'J', true);
	
				$pdf->SetFont("Helvetica",'', 8);
	
				foreach ($defesas as $defesa) {
					$pdf->Cell(70,5,utf8_decode($defesa->nome_aluno), 0, 0, 'J');
					$pdf->Cell(20,5,utf8_decode($defesa->desc_curso), 0, 0, 'J');
					$pdf->Cell(25,5,utf8_decode($defesa->desc_tipo_defesa), 0, 0, 'J');
					$pdf->Cell(25,5,utf8_decode($defesa->conceito_defesa), 0, 0, 'J');
					$pdf->Cell(20,5,utf8_decode($defesa->data_defesa), 0, 1, 'J');
				}
			}
			else{
	
				$pdf->SetFont("Helvetica",'B', 11);
	
				$pdf->MultiCell(0,5,utf8_decode("Filtro: ").utf8_decode($defesas[0]->nome_membro_banca),0, 'C');
				$pdf->MultiCell(0,5,"",0, 'C');
					
				$pdf->Cell(70,5,utf8_decode('Nome Aluno'), 1, 0, 'J', true);
				$pdf->Cell(20,5,utf8_decode('Curso'), 1, 0, 'J', true);
				$pdf->Cell(25,5,utf8_decode('Tipo Defesa'), 1, 0, 'J', true);
				$pdf->Cell(25,5,utf8_decode('Conceito'), 1, 0, 'J', true);
				$pdf->Cell(20,5,utf8_decode('Data'), 1, 0, 'J', true);
				$pdf->Cell(25,5,utf8_decode('Fun??o'), 1, 1, 'J', true);
	
				$pdf->SetFont("Helvetica",'', 8);
					
				foreach ($defesas as $defesa) {
					$pdf->Cell(70,5,utf8_decode($defesa->nome_aluno), 0, 0, 'J');
					$pdf->Cell(20,5,utf8_decode($defesa->desc_curso), 0, 0, 'J');
					$pdf->Cell(25,5,utf8_decode($defesa->desc_tipo_defesa), 0, 0, 'J');
					$pdf->Cell(25,5,utf8_decode($defesa->conceito_defesa), 0, 0, 'J');
					$pdf->Cell(20,5,utf8_decode($defesa->data_defesa), 0, 0, 'J');
					$pdf->Cell(25,5,utf8_decode($defesa->funcao_membro), 0, 1, 'J');
				}
			}
	
			ob_clean(); // Limpa o buffer de sa?da
	
			//cria o arquivo pdf e exibe no navegador
			$pdf->Output('components/com_controledefesas/relatorios/'.$chave.'.pdf','I');
			exit;
		}
		else{
			echo '<script>';
			echo 'alert("Nenhuma defesa foi encontrada!")';
			echo '</script>';
	
			header('Refresh: index.php?option=com_controledefesas&view=relatoriodefesas');
		}
	}
	
}

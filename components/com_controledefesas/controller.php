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
		if($banca[0]->status_banca != NULL){
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
		else {
			?>
				<script>
				alert('Não é possível Gerar Convite, pois a banca ainda não foi aprovada pelo Coordenador');
					location.href='index.php?option=com_controledefesas&view=conceitos&idDefesa='+<?php echo $idDefesa?>+'&idAluno='+<?php echo $idAluno?>;
				</script>

			<?php
			
		}
	}
	
	public function gerarAta(){	
		//configurações iniciais
		require('./components/com_controledefesas/pdf/pdf.php');
		
		$view = $this->getView('listabancas', 'html');
		$model = $this->getModel('listabancas');	
		$idDefesa = 125;//JRequest::getVar('idDefesa');
			
		$view->membrosBanca = $model->visualizarMembrosBanca($idDefesa);
		$view->defesa = $model->visualizarDefesa($idDefesa);
		$view->aluno = $model->visualizarAluno($idDefesa);
		
		
		$membrosBanca = $view->membrosBanca;	
		$aluno = $view->aluno;
		$defesa = $view->defesa;
		
		if($membrosBanca != NULL){
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
			$pdf->MultiCell(0,6,utf8_decode($tag),0, 'J');
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
		else{
			echo '<script>';
			echo 'alert("Defesa sem banca definida ainda.")';
			echo '</script>';
			
			header('Refresh: index.php?option=com_controledefesas&view=listabancas');
		}
	}

}

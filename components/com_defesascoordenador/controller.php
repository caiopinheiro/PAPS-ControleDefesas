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
		
		header('Location: index.php?option=com_defesascoordenador&view=avaliarbanca&idBanca='.$idBanca.'&status='.$status);

		$chave = $this->gerarConviteDefesa();
		//$succesEmail = False;
		//$succesEmail2 = False;
		$succesEmail = $this->enviarConvite($chave);
		$succesEmail2 = $this->enviarSolicitacaoPassagem();
	}
	 
	public function indeferirBanca(){
		$idBanca = JRequest::getVar('idBanca');
		$avaliacao = JRequest::getVar('avaliacao');
		$justificativa = JRequest::getVar('justificativa');
		
		
		$model = $this->getModel('avaliarbanca');		
		$status = $model->updateStatusBanca($idBanca,$avaliacao);	
		$status2 = $model->setJustificativa($idBanca,$justificativa);
		

		header('Location: index.php?option=com_defesascoordenador&view=avaliarbanca&idBanca='.$idBanca.'&status='.$status.'&status2='.$status2);
			
	}
	
	public function enviarConvite($pathConvite){
		$view = $this->getView('avaliarbanca', 'html');
		$model = $this->getModel('avaliarbanca');	
		$idBanca = JRequest::getVar('idBanca');
		
		$view->membrosBanca = $model->visualizarMembrosBanca($idBanca);
		$view->defesa = $model->visualizarDefesa($idBanca);
		$view->aluno = $model->visualizarAluno($idBanca);
    	
    	
		$membrosBanca = $view->membrosBanca;	
		$aluno = $view->aluno;
		$defesa = $view->defesa;
	
		$emails = null;
		$titulo = $defesa[0]->titulo;		
		$previaDefesa = $defesa[0]->previa;
			
		foreach( $membrosBanca as $membro ){	
			$emails[] = $membro->email;
		}
		
		if($emails != null){			
			$ascom = "assessoriadaufam@gmail.com";
			$caio2 = "pinheiro.caiof@gmail.com";	
			$caio1 = "gcarneirobr@gmail.com";	
			
			
			// subject
			$subject  = "[IComp/UFAM] Convite de Participação de Defesa";
			
			// message
			$message .= "A Coordenação do Programa de Pós-graduação em Informática PPGI/UFAM tem o prazer de convidá-lo para a sessão pública de apresentação da Defesa de Dissertação/Tese de Mestrado/Doutorado:\r\n\n";
			$message .= "$titulo\r\n\n";
			$message .= "CANDIDATO: ".$aluno[0]->nome_aluno."\r\n\n";
			$message .= "BANCA EXAMINADORA: \r\n";
			
			foreach( $view->membrosBanca as $membro ){
				if($membro->funcao === 'P'){
					$message .= "$membro->nome - $membro->filiacao (Presidente)\r\n";
				}
				else{
					$message .= "$membro->nome - $membro->filiacao\r\n";
				}
			}
			
			$data = explode("-", $defesa[0]->data);
			$data = $data[2] . "/" . $data[1] . "/" .$data[0] ;	
			
			$message .= "\n";
			$message .= "LOCAL: ".$defesa[0]->local."\r\n";
			$message .= "DATA: ".$data."\r\n";
			$message .= "HORÁRIO: ".$defesa[0]->horario."\r\n\n";
			$message .= "Reiteramos o nosso prazer em tê-lo como participante de um momento tão importantes.\r\n\n";
			$message .= "Atenciosamente,\r\n\n";
			$message .= "Profa. Eulanda M. dos Santos\r\n";
			$message .= "Coordenadora do PPGI\r\n";
			
			
			$email[] = $caio2;
			$email[] = $caio1;
			$emails[] = $ascom;
			
			$path []= "components/com_defesasorientador/previas/".$previaDefesa;
			$path []= "components/com_defesascoordenador/convites/".$pathConvite.".pdf";
				
			return JUtility::sendMail($user->email, "IComp: Controle de Defesas", $email, $subject, $message, false, NULL, NULL, $path);
		}
	}
	
	public function enviarSolicitacaoPassagem(){
		$view = $this->getView('avaliarbanca', 'html');
		$model = $this->getModel('avaliarbanca');	
		$idBanca = JRequest::getVar('idBanca');
		
		$view->membrosBanca = $model->passagemMembrosBanca($idBanca);
    	
		$membrosBanca = $view->membrosBanca;
		
		$emails = null;	
			
		$formSolicitacao = "Form_passagem_diarias.doc";
			
		foreach( $membrosBanca as $membro ){	
			if($membro->passagem === 'S')
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
			$subject  = "[IComp/UFAM] Solicitação de Passagem Aérea";
					
			// message
			$message .= "A Coordenação do Programa de Pós-graduação em Informática PPGI/UFAM tem o prazer de tê-lo para a sessão pública de apresentação da Defesa de Dissertação/Tese de Mestrado/Doutorado.\r\n\n";
			
			$message .= "Considerando a sua participação, pedimos o preenchimento do Formulário de Solicitação de Passagens e Diárias, que está em anexo à este email. Rogamos, ainda, o fornecimento dos seguintes dados, para fins de cadastro em nosso sistema de gerenciamento da Pós-Graduação:\r\n\n";

			$message .= "a. Nome completo;\n";
			$message .=	"b. Data de nascimento;\n";
			$message .= "c. Data de sua diplomação em Pós-Graduação, e nome da Instituição em que diplomou-se;\n";
			$message .=	"d. Data de início do vínculo com sua IES;\n";
			$message .= "e. Sugestão de Voo.\r\n\n";

			$message .= "Solicitamos que, após o preenchimento do Formulário de Solicitação de Passagens e Diárias, bem como dos demais dados solicitados, os mesmos sejam encaminhados ao email de nossa Secretaria: secretariappgi@icomp.ufam.edu.br.\r\n\n";
				
			$message .= "Por fim, reiteramos o nosso prazer em tê-lo como participante de um momento tão importante, e esperamos, sinceramente, que outros mais venham.\r\n\n";
			
			$message .= "Atenciosamente,\r\n\n";
			
			$message .= "Profa. Eulanda M. dos Santos\r\n";
			$message .= "Coordenadora do PPGI\r\n";
			
			
			$email[] = $caio2;
			$email[] = $caio1;
		
			
			$path = "components/com_defesascoordenador/forms/".$formSolicitacao;
			
			return JUtility::sendMail($user->email, "IComp: Controle de Defesas", $email, $subject, $message, false, NULL, NULL, $path);
		}

	}
	
	public function gerarConviteDefesa() {
		
		//configurações iniciais
		require('./components/com_defesascoordenador/pdf/pdf.php');
		//require('./components/com_defesascoordenador/emails/enviarConvite.php');
		
		$view = $this->getView('avaliarbanca', 'html');
		$model = $this->getModel('avaliarbanca');	
		$idBanca = JRequest::getVar('idBanca');
			
		$view->membrosBanca = $model->visualizarMembrosBanca($idBanca);
		$view->defesa = $model->visualizarDefesa($idBanca);
		$view->aluno = $model->visualizarAluno($idBanca);
		
		
		$membrosBanca = $view->membrosBanca;	
		$aluno = $view->aluno;
		$defesa = $view->defesa;
		
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
		$pdf->Output('components/com_defesascoordenador/convites/'.$chave.'.pdf','F');
		
		return $chave;
		exit;	
	}
	
}

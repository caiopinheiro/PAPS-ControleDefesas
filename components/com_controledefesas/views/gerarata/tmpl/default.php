<?php

JHTML::_('behavior.mootools');
JHTML::_('script','modal.js', 'media/system/js', true);
JHTML::_('stylesheet','modal.css');
JHTML::_('behavior.modal', 'a.modal');

// No direct access to this file


$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
//$document->addScript("includes/js/joomla.javascript.js");


$idAluno = JRequest::getvar('idAluno');
$idDefesa = JRequest::getvar('idDefesa');
$Defesa = $this->defesa;
$MembrosBanca = $this->membrosBanca;
$DefesaAta = $this->defesaAta;
$Aluno = $this->aluno;

////////////////////

function gerarAta($Defesa,$Membros,$Aluno){	
		//configurações iniciais
		//require('./components/com_controledefesas/pdf/pdf.php');
		
		require('fpdf/fpdf.php');
	//configurações iniciais

	class PDF extends FPDF
	{
		function Header()
		{
			$this->Image('components/com_portalsecretaria/images/logo-brasil.jpg', 10, 7, 32.32);
			$this->Image('components/com_portalsecretaria/images/ufam.jpg', 175, 7, 25.25);

			//exibe o cabecalho do documento
			$this->SetFont("Helvetica",'B', 12);
			$this->MultiCell(0,5,"PODER EXECUTIVO",0, 'C');
			$this->SetFont("Helvetica",'B', 10);
			$this->MultiCell(0,5,utf8_decode("MINISTÉRIO DA EDUCAÇÃO"),0, 'C');
			$this->MultiCell(0,5,utf8_decode("INSTITUTO DE COMPUTAÇÃO"),0, 'C');
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,5,utf8_decode("PROGRAMA DE PÓS-GRADUAÇÃO EM INFORMÁTICA"),0, 'C');
			$this->SetDrawColor(0,0,0);
			$this->Line(10,42,200,42);
			$this->ln( 7 );
		}

		function Footer()
		{
			$this->Line(10,285,200,285);
			$this->SetFont('Helvetica','I',8);
			$this->SetXY(10, 281);
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,4,utf8_decode("Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur Virgílio Filho - CEP 69077-000 - Manaus, AM, Brasil"),0, 'C');
			$this->MultiCell(0,4," Tel. (092) 3305 1193         E-mail: secretariappgi@icomp.ufam.edu.br          www.ppgi.ufam.edu.br",0, 'C');
			$this->Image('components/com_portalsecretaria/images/icon_telefone.jpg', '40', '290');
			$this->Image('components/com_portalsecretaria/images/icon_email.jpg', '73', '290');
			$this->Image('components/com_portalsecretaria/images/icon_casa.jpg', '134', '290');
		}
	}
		
		$membrosBanca = $Membros;	
		$aluno = $Aluno;
		$defesa = $Defesa;
		

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
		
		//$tag = "Aos ".$data[2]." dias do mês de ".$mes[$data[1]]." do ano de ".$data[0].", às ".$hora.", na ".$local." da Universidade Federal do Amazonas, situada na Av. Rodrigo Otávio, 6.200, Campus Universitário, Setor Norte, Coroado, nesta Capital, ocorreu a sessão pública de defesa de ".$complemento3." intitulada  '".$titulo."' apresentada pelo aluno(a) ".$aluno[0]->nome_aluno." que concluiu todos os pré-requisitos exigidos para a obtenção do título de ".$complemento2." em informática, conforme estabelece o artigo 52 do regimento interno do curso. Os trabalhos foram instalados pelo(a)  ".$presidente.", orientador(a) e presidente da Banca Examinadora, que foi constituída, ainda, por ".$membrosBanca."membros convidados. A Banca Examinadora tendo decidido aceitar a dissertação, passou à arguição pública do candidato. 
		$tag = "Aos ".$data[2]." dias do mês de ".$mes[$data[1]]." do ano de ".$data[0].", às ".$hora.", na ".$local." da Universidade Federal do Amazonas, situada na Av. Rodrigo Otávio, 6.200, Campus Universitário, Setor Norte, Coroado, nesta Capital, ocorreu a sessão pública de defesa de ".$complemento3." intitulada  '".$titulo."' apresentada pelo aluno(a) ".$aluno[0]->nome_aluno." que concluiu todos os pré-requisitos exigidos para a obtenção do título de ".$complemento2." em informática, conforme estabelece o artigo 52 do regimento interno do curso. Os trabalhos foram instalados pelo(a)  ".$presidente.", orientador(a) e presidente da Banca Examinadora, que foi constituída, ainda, por ";
		foreach ($membrosBanca as $membro) {
			if($membro->funcao != 'P'){	
				$tag = $tag. $membro->nome . ',';
			}
		}	 
		$tag = $tag." membros convidados. A Banca Examinadora tendo decidido aceitar a dissertação, passou à arguição pública do candidato. 
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
		$pdf->MultiCell(0,5,"Manaus, ".$data[2]." de ".utf8_decode($mes[$data[1]])." de ".$data[0],0, 'C');	

		ob_clean(); // Limpa o buffer de saída
		//cria o arquivo pdf e exibe no navegador
		$pdf->Output('components/com_controledefesas/atas/$chave.pdf','I');
		exit;
	}

function imprimirFolhaQualificacao($Defesa,$MembrosBanca) {

	$alunos = $Defesa;
	require('fpdf/fpdf.php');
	//configurações iniciais

	class PDF extends FPDF
	{
		function Header()
		{
			$this->Image('components/com_portalsecretaria/images/logo-brasil.jpg', 10, 7, 32.32);
			$this->Image('components/com_portalsecretaria/images/ufam.jpg', 175, 7, 25.25);

			//exibe o cabecalho do documento
			$this->SetFont("Helvetica",'B', 12);
			$this->MultiCell(0,5,"PODER EXECUTIVO",0, 'C');
			$this->SetFont("Helvetica",'B', 10);
			$this->MultiCell(0,5,utf8_decode("MINISTÉRIO DA EDUCAÇÃO"),0, 'C');
			$this->MultiCell(0,5,utf8_decode("INSTITUTO DE COMPUTAÇÃO"),0, 'C');
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,5,utf8_decode("PROGRAMA DE PÓS-GRADUAÇÃO EM INFORMÁTICA"),0, 'C');
			$this->SetDrawColor(0,0,0);
			$this->Line(10,42,200,42);
			$this->ln( 7 );
		}

		function Footer()
		{
			$this->Line(10,285,200,285);
			$this->SetFont('Helvetica','I',8);
			$this->SetXY(10, 281);
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,4,utf8_decode("Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur Virgílio Filho - CEP 69077-000 - Manaus, AM, Brasil"),0, 'C');
			$this->MultiCell(0,4," Tel. (092) 3305 1193         E-mail: secretariappgi@icomp.ufam.edu.br          www.ppgi.ufam.edu.br",0, 'C');
			$this->Image('components/com_portalsecretaria/images/icon_telefone.jpg', '40', '290');
			$this->Image('components/com_portalsecretaria/images/icon_email.jpg', '73', '290');
			$this->Image('components/com_portalsecretaria/images/icon_casa.jpg', '134', '290');
		}
	}

	$curso = array (
		'Q' => 'DISSERTA&Ccedil;&Atilde;O',
		'T' => 'TESE'
	);

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

$linha_pes = array(
	1 => "Banco de Dados e Recuperação da Informação", 
	2 => "Sistemas Embarcados & Engenharia de Software", 
	3 => "Inteligência Artificial", 
	4 => "Visão Computacional e Robótica", 
	5 => "Redes e Telecomunicações", 
	6 => "Otimização Algorítmica e Complexidade"
	);


	$chave = md5($alunos[0]->id . $alunos[0]->nome . date("l jS \of F Y h:i:s A"));

	//$pdf = new FPDF('P','cm','A4');
	$pdf = new PDF();
	$pdf->Open();
	$pdf->AddPage();

	//titulos de configuração do documento
	$pdf->SetTitle(utf8_decode("Folha de Qualificação"));

	// OBTENDO OS DADOS A SEREM PREENCHIDOS

	$data = explode("/",$alunos[0]->data);
	$hora = $alunos[0]->horario;
	$local = utf8_decode($alunos[0]->local);
	$titulo = utf8_decode($alunos[0]->titulo);
	if ($alunos[0]->curso == 2){
		$complemento = "TESE DE DOUTORADO";
	}
	else{
		$complemento = "DISSERTAÇÃO DE MESTRADO";
	}
	$membrosBanca = "";

	// OBTENDO OS DADOS A SEREM PREENCHIDOS

	$pdf->SetFont("Helvetica",'B', 14);
	$pdf->MultiCell(0,8,utf8_decode('AVALIAÇÃO DE PROPOSTA DE '.$complemento),0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->SetFont("Helvetica",'B', 12);

    // DADOS DO ALUNO
	$pdf->MultiCell(0,8,"DADOS DO(A) ALUNO(A)",0, 'J');
	$pdf->SetFont("Helvetica",'', 12);
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"Nome: ".utf8_decode($alunos[0]->nome),0, 'J');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"Linha de Pesquisa: ".utf8_decode($linha_pes[$alunos[0]->area]),0, 'J');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"Orientador: ".utf8_decode($alunos[0]->nomeProfessor),0, 'J');

	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->SetDrawColor(180,180,180);
	$pdf->Line(10,102,200,102);

    // DADOS DA DEFESA
	$pdf->SetFont("Helvetica",'B', 12);
	$pdf->MultiCell(0,8,"DADOS DA DEFESA",0, 'J');
	$pdf->SetFont("Helvetica",'', 12);
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,utf8_decode("Título: ".$alunos[0]->titulo),0, 'J');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"Data: ". date ('d/m/Y',(strtotime($alunos[0]->data))) ."     Hora: ".$hora."     Local: ".$local,0, 'J');
	//date ('d/m/Y',(strtotime($defesa->data)))
	$pdf->MultiCell(0,8,"",0, 'C');

    // DADOS DA DEFESA
	$pdf->SetFont("Helvetica",'B', 12);
	$pdf->MultiCell(0,8,utf8_decode("AVALIAÇÃO DA BANCA EXAMINADORA"),0, 'J');
	$pdf->MultiCell(0,6,"",0, 'C');
	$pdf->SetXY(140,$pdf->getY() - 14);
	$pdf->MultiCell(0,6,"CONCEITO: ___________",0, 'J');
   	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->SetFont("Helvetica",'', 12);
	foreach ($MembrosBanca as $membro) {
      	$pdf->MultiCell(0,5,"",0, 'C');
		if ($membro->funcao == "P")
        	$pdf->MultiCell(0,9,"_____________________________________________
            ".utf8_decode($membro->nome). " - PRESIDENTE",0, 'R');
		else
    		$pdf->MultiCell(0,9,"_____________________________________________
            ".utf8_decode($membro->nome). " - MEMBRO",0, 'R');
	}

	$pdf->AddPage();
	$pdf->SetFont("Helvetica",'B', 14);
	$pdf->MultiCell(0,8,utf8_decode('AVALIAÇÃO DE PROPOSTA DE '.$complemento),0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->SetFont("Helvetica",'B', 12);

    // DADOS DO ALUNO
	$pdf->MultiCell(0,10,"PARECER:",0, 'J');
	$pdf->Rect(10, 70, 190, 120, "D");
	$pdf->SetXY(10,190);
	$pdf->SetFont("Helvetica",'', 12);
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
   	$pdf->MultiCell(0,10,"___________________________________         ___________________________________",0, 'C');
   	$pdf->MultiCell(0,10,"Assinatura do(a) Orientador(a)                                                 Assinatura do(a) Discente",0, 'C');

	$pdf->SetFont("Helvetica",'B', 12);
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,10,"Obs.: Anexar PROPOSTA a ser apresentada",0, 'C');

	ob_clean(); // Limpa o buffer de saída
	//cria o arquivo pdf e exibe no navegador
	$pdf->Output('components/com_portalsecretaria/defesas/$chave.pdf','I');
	exit;

}


////////////////////
if ($Defesa[0]->tipoDefesa == 'D' || $Defesa[0]->tipoDefesa == 'T'){

gerarAta($DefesaAta,$MembrosBanca,$Aluno);
}
else if (($Defesa[0]->tipoDefesa == "Q1" AND $Defesa[0]->curso == 1) || ($Defesa[0]->tipoDefesa == "Q2" AND $Defesa[0]->curso == 2)){

imprimirFolhaQualificacao($Defesa,$MembrosBanca);
}
else {
?>
		<script language="JavaScript" type="text/javascript">
		alert ("Observação:\n\n  -Não há folha de aprovação para Qualificação I do curso de Doutorado.")
		</script>

<?php

		header('refresh:1; url =index.php?option=com_controledefesas&view=listabancas&lang=pt-br');
}



?>


<div id="box-toggle" class="box">
<div class="tgl"></div></div>

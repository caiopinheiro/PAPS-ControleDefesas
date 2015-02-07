

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

$Banca = $this->banca;
$Aluno = $this->aluno;
$Defesa = $this->defesa;
$MembrosBanca = $this->membrosBanca;

$idDefesa= JRequest::getVar('idDefesa'); 
$idMembro = JRequest::getVar('idMembro'); 
$funcao = JRequest::getVar('funcao'); 


$linha_pes = array(0 => "Todos", 1 => "Banco de Dados e Recuperação da Informação", 2 => "Sistemas Embarcados & Engenharia de Software", 3 => "Inteligência Artificial", 4 => "Visão Computacional e Robótica", 5 => "Redes e Telecomunicações", 6 => "Otimização Algorítmica e Complexidade");
			
$arrayTipoDefesa = array('1' => "Mestrado", '2' => "Doutorado");

//$status_banc = array (0 => "Banca Indeferida", 1 => "Banca Deferida", NULL => "Banca Não Avaliada");

$array_funcao = array ('P' => "Presidente",'E' => "Membro Externo", 'I' => "Membro Interno");


$justificativa="";
$nome_orientador = "";

foreach( $MembrosBanca as $membro ){
		if($membro->funcao == 'P')
			$nome_orientador = $membro->nome;
		$emails[] = $membro->email;	
}

$sucesso = $this->status;	
$sucesso2 = $this->status;
if($sucesso == true OR $sucesso2 == true ){
	JFactory :: getApplication()->enqueueMessage(JText :: _('Opera&#231;&#227;o realizada com sucesso.'));
}
else if(($sucesso == false AND $sucesso !=NULL) OR ($sucesso2 == false AND $sucesso2 !=NULL) ){
	JError :: raiseWarning(100, 'ERRO: Opera&#231;&#227;o Falhou.');
}


function imprimirAgradecimento($idMembro, $idDefesa, $funcao) {

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
	$database = & JFactory :: getDBO();

	$curso = array (
		'Q' => 'DISSERTAÇÃO',
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

	$sql = "SELECT nome FROM #__membrosbanca WHERE id = $idMembro";
	$database->setQuery($sql);
	$banca = $database->loadObjectList();

	$sql = "SELECT a.nome, titulo,tipoDefesa,data,horario,local,curso FROM #__defesa as d JOIN #__aluno as a on a.id = d.aluno_id WHERE idDefesa = ".$idDefesa;
	$database->setQuery($sql);
	$alunos = $database->loadObjectList();

	$chave = md5($alunos[0]->id . $alunos[0]->nome . date("l jS \of F Y h:i:s A"));

	//$pdf = new FPDF('P','cm','A4');
	$pdf = new PDF();
	$pdf->Open();
	$pdf->AddPage();

	//titulos de configuração do documento
	$pdf->SetTitle("Agradecimentos");
	
	// OBTENDO OS DADOS A SEREM PREENCHIDOS
	
	$nome = utf8_decode($alunos[0]->nome);	
	if ($alunos[0]->tipoDefesa == 'Q1' || $alunos[0]->tipoDefesa == 'Q2') {	
		$data = explode("/",$alunos[0]->data);
		$titulo = utf8_decode($alunos[0]->titulo);
		$hora = $alunos[0]->horario;
		if ($alunos[0]->curso == 2){
			$complemento = "Doutorado";
			$complemento2 = "Exame de Qualificação de Doutorado";
		}
		else{
			$complemento = "Mestrado";
			$complemento2 = "Exame de Qualificação de Mestrado";
		}
	}
	else{
		$data = explode("/",$alunos[0]->data);	
		$titulo = utf8_decode($alunos[0]->titulo);
		$hora = $alunos[0]->horario;
		if ($alunos[0]->curso == 2){
			$complemento2 = "Tese de Doutorado";
			$complemento = "Doutorado";
		}
		else if ($alunos[0]->curso == 1){
			$complemento2= "Dissertação de Mestrado";
			$complemento = "Mestrado";
		}
	}
	// OBTENDO OS DADOS A SEREM PREENCHIDOS
	
	$pdf->SetFont("Helvetica",'B', 14);
	$pdf->MultiCell(0,7,"",0, 'C');
	$pdf->MultiCell(0,5,'AGRADECIMENTO',0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');
	
	if ($funcao == "P")
        	$participacao = "presidente/orientador(a)";
	else if ($funcao == "I")
        	$participacao = "membro interno";
	else if ($funcao == "E")
        	$participacao = "membro externo";

	//$tag = "AGRADECEMOS a participação do(a) ".$banca[0]->nome." como ".$participacao." da banca examinadora referente à apresentação da Defesa de ".$complemento2." do(a) aluno(a), abaixo especificado(a), do curso de ".$complemento." em Informática do Programa de Pós-Graduação em Informática da Universidade Federal do Amazonas - realizada no dia ".$data[0]." de ".$mes[$data[1]]." de ".$data[2]." às ".$hora.".";
	$tag = "AGRADECEMOS a participação do(a) ".$banca[0]->nome." como ".$participacao." da banca examinadora referente à apresentação da Defesa de ".$complemento2." do(a) aluno(a), abaixo especificado(a), do curso de ".$complemento." em Informática do Programa de Pós-Graduação em Informática da Universidade Federal do Amazonas - realizada no dia ".date('d',(strtotime($data[0])))." de ". $mes[date('m',(strtotime($data[0])))]." de ".date('Y',(strtotime($data[0])))." às ".$hora.".";

	$pdf->SetFont("Helvetica",'', 12);
	$pdf->MultiCell(0,10,utf8_decode($tag),0, 'J');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->MultiCell(0,8,utf8_decode('Título: ').$titulo,0, 'J');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->MultiCell(0,8,'Aluno(a): '.$nome,0, 'J');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');	
	$pdf->SetFont("Helvetica",'', 10);
	$pdf->MultiCell(0,5,"Manaus, ".date('d',(strtotime($data[0])))." de ". $mes[date('m',(strtotime($data[0])))]." de ".date('Y',(strtotime($data[0]))),0, 'C');

	ob_clean(); // Limpa o buffer de saída
	//cria o arquivo pdf e exibe no navegador
	$pdf->Output('components/com_portalsecretaria/defesas/$chave.pdf','I');
	exit;
	
}

imprimirAgradecimento($idMembro,$idDefesa,$funcao);

?>


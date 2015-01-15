<?php
function identificarAlunoID($idAluno) {
	$database = & JFactory :: getDBO();

	$sql = "SELECT * FROM #__aluno WHERE id = $idAluno";
	$database->setQuery($sql);
	$alunos = $database->loadObjectList();

	return ($alunos[0]);
}

function identificarCadastro($cpf) {
	$database = & JFactory :: getDBO();

	$sql = "SELECT * FROM #__aluno WHERE cpf = $cpf";
	$database->setQuery($sql);
	$cadastro = $database->loadObjectList();
	if ($cadastro[0] == NULL) {
		return 0;
	}
	return 1;
}

/**
 * FunÃ§Ã£o que transforma a data do formulÃ¡rio (m/Y) em uma data para inserÃ§Ã£o/ediÃ§Ã£o no
 * banco da dados (Y-m-d).
 * 
 * @param string $data. A data a ser transformada.
 * @return date A data no novo formato. 
 */
function moldarData($data) {
	$data = explode("/", $data);
	$aux = $data[1] . "-" . $data[0] . "-" . "01";
	return date("Y-m-d", strtotime($aux));
}

// CADASTRAR ALUNO : SQL
function salvarCadastro() {
	$database = & JFactory :: getDBO();

	$nome = $_POST['nome'];
	$matricula = $_POST['matricula'];
	$email = $_POST['email'];
	$curso = $_POST['curso'];	
	
	$sql = "SELECT email, curso FROM #__aluno WHERE email = '$email'";
	$database->setQuery($sql);
	$repetido = $database->loadObjectList();
	
	foreach ( $repetido as $repetido ) {
      $emailExistente =  $repetido->email;
      $cursoExistente = $repetido->curso;
	}
	
	echo $emailExistente;
	echo $cursoExistente;	

	if (!$repetido) {		
		$endereco = $_POST['endereco'];
		$bairro = $_POST['bairro'];
		$cidade = $_POST['cidade'];
		$uf = $_POST['uf'];
		$cep = $_POST['cep'];
		$sexo = $_POST['sexo'];
		$estadocivil = $_POST['estadocivil'];
		$datanascimento = $_POST['datanascimento'];
		$nacionalidade = $_POST['nacionalidade'];
		if ($nacionalidade == 1)
			$pais = "Brasil";
		else
			$pais = $_POST['pais'];
		$cpf = $_POST['cpf'];
		$rg = $_POST['rg'];
		$orgaoexpedidor = $_POST['orgaoexpedidor'];
		$anoingresso = moldarData($_POST['anoingresso']);
		$dataexpedicao = $_POST['dataexpedicao'];
		$telresidencial = $_POST['telresidencial'];
		$telcomercial = $_POST['telcomercial'];
		$telcelular = $_POST['telcelular'];
		$nomepai = $_POST['nomepai'];
		$nomemae = $_POST['nomemae'];
		
		$regime = $_POST['regime'];
		$bolsista = $_POST['bolsista'];
		$agencia = $_POST['agencia'];
		$orientador = $_POST['orientador'];
		$area = $_POST['area'];
		$cursograd = $_POST['cursograd'];
		$instituicaograd = $_POST['instituicaograd'];
		$egressograd = $_POST['egressograd'];
		$crgrad = $_POST['crgrad'];

		$senha = md5($matricula);

		// Cria um novo usuario do Joomla para acesso do aluno
		$idUser = CreateNewUser($nome, $email, $email, $senha, $registerDate = NULL, $usertype = 'Registered', 0, 1, 10);

		//SQL para inserÃ§Ã£o do novo aluno no banco de dados
		$sql = "INSERT INTO #__aluno(`nome`, `email`, `senha`, `matricula`, `orientador`, `area`, `curso`, `endereco`, `bairro`, `cidade`, `uf`, `cep`, `datanascimento`, `sexo`, `nacionalidade`, `estadocivil`, `cpf`, `rg`, `orgaoexpeditor`, `dataexpedicao`, `telresidencial`, `telcomercial`, `telcelular`, `nomepai`, `nomemae`, `regime`, `bolsista`, `agencia`, `pais`, anoingresso, cursograd, instituicaograd, egressograd, crgrad, idUser) VALUES ('$nome ', '$email', '$senha', '$matricula', $orientador, $area, '$curso', '$endereco', '$bairro', '$cidade', '$uf', '$cep', '$datanascimento', '$sexo', '$nacionalidade', '$estadocivil', '$cpf', '$rg', '$orgaoexpedidor', '$dataexpedicao', '$telresidencial', '$telcomercial', '$telcelular', '$nomepai', '$nomemae', '$regime', '$bolsista', '$agencia', '$pais', '$anoingresso', '$cursograd', '$instituicaograd', $egressograd, '$crgrad', $idUser)";

		//execuÃ§Ã£o do SQL
		$database->setQuery($sql);

		$sucesso = $database->Query();

		if ($sucesso)
			JFactory :: getApplication()->enqueueMessage(JText :: _('Cadastro de Aluno realizado com sucesso.'));
		else
			JError :: raiseWarning(100, 'ERRO: Opera&#231;&#227;o de Cadastro de Novo Aluno Falhou.');
		return 1;

	} else if(($email == $emailExistente) && ($curso !=$cursoExistente)){		//caso o email seja o mesmo mas o curso diferente
		
		$endereco = $_POST['endereco'];
		$bairro = $_POST['bairro'];
		$cidade = $_POST['cidade'];
		$uf = $_POST['uf'];
		$cep = $_POST['cep'];
		$sexo = $_POST['sexo'];
		$estadocivil = $_POST['estadocivil'];
		$datanascimento = $_POST['datanascimento'];
		$nacionalidade = $_POST['nacionalidade'];
		if ($nacionalidade == 1)
			$pais = "Brasil";
		else
			$pais = $_POST['pais'];
		$cpf = $_POST['cpf'];
		$rg = $_POST['rg'];
		$orgaoexpedidor = $_POST['orgaoexpedidor'];
		$anoingresso = moldarData($_POST['anoingresso']);
		$dataexpedicao = $_POST['dataexpedicao'];
		$telresidencial = $_POST['telresidencial'];
		$telcomercial = $_POST['telcomercial'];
		$telcelular = $_POST['telcelular'];
		$nomepai = $_POST['nomepai'];
		$nomemae = $_POST['nomemae'];
		
		$regime = $_POST['regime'];
		$bolsista = $_POST['bolsista'];
		$agencia = $_POST['agencia'];
		$orientador = $_POST['orientador'];
		$area = $_POST['area'];
		$cursograd = $_POST['cursograd'];
		$instituicaograd = $_POST['instituicaograd'];
		$egressograd = $_POST['egressograd'];
		$crgrad = $_POST['crgrad'];

		$senha = md5($matricula);
		
	    $sql = "SELECT idUser FROM #__aluno WHERE email = '$email'";
		$database->setQuery($sql);
		$repetido = $database->loadObjectList();
	
		foreach ( $repetido as $repetido ) {
	      $idExistente = $repetido->idUser;
		}

		//SQL para inserÃ§Ã£o do novo aluno no banco de dados
		$sql = "INSERT INTO #__aluno(`nome`, `email`, `senha`, `matricula`, `orientador`, `area`, `curso`, `endereco`, `bairro`, `cidade`, `uf`, `cep`, `datanascimento`, `sexo`, `nacionalidade`, `estadocivil`, `cpf`, `rg`, `orgaoexpeditor`, `dataexpedicao`, `telresidencial`, `telcomercial`, `telcelular`, `nomepai`, `nomemae`, `regime`, `bolsista`, `agencia`, `pais`, anoingresso, cursograd, instituicaograd, egressograd, crgrad, idUser) VALUES ('$nome ', '$email', '$senha', '$matricula', $orientador, $area, '$curso', '$endereco', '$bairro', '$cidade', '$uf', '$cep', '$datanascimento', '$sexo', '$nacionalidade', '$estadocivil', '$cpf', '$rg', '$orgaoexpedidor', '$dataexpedicao', '$telresidencial', '$telcomercial', '$telcelular', '$nomepai', '$nomemae', '$regime', '$bolsista', '$agencia', '$pais', '$anoingresso', '$cursograd', '$instituicaograd', $egressograd, '$crgrad', $idExistente)";

		//execuÃ§Ã£o do SQL
		$database->setQuery($sql);

		$sucesso = $database->Query();

		if ($sucesso)
			JFactory :: getApplication()->enqueueMessage(JText :: _('Cadastro de Aluno realizado com sucesso.'));
		else
			JError :: raiseWarning(100, 'ERRO: Opera&#231;&#227;o de Cadastro de Novo Aluno Falhou.');
		return 1;
			
	}
	return 0;
}

// EDITAR ALUNO : SQL
function salvarEdicao($idAluno) {
	$database = & JFactory :: getDBO();

	$nome = $_POST['nome'];
	$status = $_POST['status'];
	$matricula = $_POST['matricula'];
	$email = $_POST['email'];
	$curso = $_POST['curso'];

	$sql = "SELECT email FROM #__aluno WHERE email = '$email' AND id <> $idAluno AND curso = $curso AND status = 0";
	$database->setQuery($sql);
	$repetido = NULL; //$database->loadObjectList();
	$repetido = $database->loadObjectList();

	if (!$repetido) {
		$endereco = $_POST['endereco'];
		$bairro = $_POST['bairro'];
		$cidade = $_POST['cidade'];
		$uf = $_POST['uf'];
		$cep = $_POST['cep'];
		$sexo = $_POST['sexo'];
		$estadocivil = $_POST['estadocivil'];
		$datanascimento = $_POST['datanascimento'];
		$nacionalidade = $_POST['nacionalidade'];
		if ($nacionalidade == 1)
			$pais = "Brasil";
		else
			$pais = $_POST['pais'];
		$cpf = $_POST['cpf'];
		$rg = $_POST['rg'];
		$orgaoexpedidor = $_POST['orgaoexpedidor'];
		//    $anoingresso = $_POST['anoingresso'];
		$anoingresso = moldarData($_POST['anoingresso']);
		$dataexpedicao = $_POST['dataexpedicao'];
		$telresidencial = $_POST['telresidencial'];
		$telcomercial = $_POST['telcomercial'];
		$telcelular = $_POST['telcelular'];
		$nomepai = $_POST['nomepai'];
		$nomemae = $_POST['nomemae'];
		$regime = $_POST['regime'];
		$bolsista = $_POST['bolsista'];
		$agencia = $_POST['agencia'];
		$orientador = $_POST['orientador'];
		$area = $_POST['area'];
		$cursograd = $_POST['cursograd'];
		$instituicaograd = $_POST['instituicaograd'];
		$egressograd = $_POST['egressograd'];
		$crgrad = $_POST['crgrad'];

		$senha = md5($matricula);

		//  $idUser = CreateNewUser($nome, $email, $email, $senha, $registerDate = NULL, $usertype = 'Registered', 0, 1, 10);

		$sql = "UPDATE #__aluno
		         SET
		           nome ='$nome',
		           email = '$email',
		           senha = '$senha',
		           status = $status,
		           matricula = '$matricula',
		           orientador = $orientador,
		           area = '$area',
		           curso = '$curso',
		           endereco = '$endereco',
		           bairro = '$bairro',
		           cidade = '$cidade',
		           uf = '$uf',
		           cep = '$cep',
		           datanascimento = '$datanascimento',
		           sexo = '$sexo',
		           nacionalidade = '$nacionalidade',
		           estadocivil = '$estadocivil',
		           cpf = '$cpf',
		           rg = '$rg',
		           orgaoexpeditor = '$orgaoexpedidor',
		           dataexpedicao = '$dataexpedicao',
		           telresidencial = '$telresidencial',
		           telcomercial = '$telcomercial',
		           telcelular = '$telcelular',
		           nomepai = '$nomepai',
		           nomemae = '$nomemae',
		           regime = '$regime',
		           anoingresso = '$anoingresso',
		           bolsista = '$bolsista',
		           agencia = '$agencia',
		           cursograd = '$cursograd',
		           instituicaograd = '$instituicaograd',
		           egressograd = '$egressograd',
		           crgrad = '$crgrad',
		           pais =  '$pais'
		         WHERE id = $idAluno";

		$database->setQuery($sql);
		$sucesso = $database->Query();
		if ($sucesso)
			JFactory :: getApplication()->enqueueMessage(JText :: _('Edi&#231;&#227;o de Aluno realizada com sucesso.'));
		else
			JError :: raiseWarning(100, 'ERRO: Opera&#231;&#227;o de Edi&#231;&#227;o de Aluno Falhou.');

		return 1;
	} else
		return (0);
}

// NOVO USUÁRIO
function CreateNewUser($name, $username, $email, $password, $registerDate = NULL, $usertype = 'Registered', $block = 0, $sendEmail = 1, $gid = 10) {

	global $db;

	$db = & JFactory :: getDBO();

	$sql = "INSERT INTO #__users(name, username, email, password, registerDate, usertype, block, sendEmail) VALUES ('$name ', '$username', '$email', '$password', '$registerDate', '$usertype', $block, $sendEmail)";
	$db->setQuery($sql);
	$db->Query();

	//Obter ID do aluno
	$query = "SELECT id FROM #__users ORDER BY id DESC LIMIT 1";
	$db->setQuery($query);
	$ultimoaluno = $db->loadResult();

	$sql = "INSERT INTO #__user_usergroup_map(user_id, group_id) VALUES ($ultimoaluno, $gid)";
	$db->setQuery($sql);
	$db->Query();

	return ($ultimoaluno->id);
}

function verProfessor($idProfessor) {
	$db = & JFactory :: getDBO();
	$db->setQuery("SELECT nomeProfessor FROM #__professores WHERE id = $idProfessor LIMIT 1");
	$professor = $db->loadResult();

	return ($professor);
}

function verLinhaPesquisa($idLinha, $inf) {
	$db = & JFactory :: getDBO();
	$db->setQuery("SELECT nome, sigla FROM #__linhaspesquisa WHERE id = $idLinha LIMIT 1");
	$linha = $db->loadObjectList();

	if ($inf == 2)
		return ($linha[0]->sigla);

	return ($linha[0]->nome);
}

function relatorioAluno($aluno) {
	global $mosConfig_lang;
	$database = & JFactory :: getDBO();

	$sexo = array (
		'M' => 'Masculino',
		'F' => 'Feminimo'
	);
	$curso = array (
		1 => 'Mestrado',
		2 => 'Doutorado',
		3 => 'Especial'
	);
	$pesquisa = array (
		1 => 'Disserta&#231;&#245;',
		2 => 'Tese',
		3 => 'Pesquisa'
	);
	$regime = array (
		1 => 'Integral',
		2 => 'Parcial'
	);
	$status = array (
		0 => 'Aluno Corrente',
		1 => 'Aluno Egresso',
		2 => 'Aluno Desistente',
		3 => 'Aluno Desligado',
		4 => 'Aluno Jubilado',
		5 => 'Aluno com Matr&#237;cula Trancada'
	);

	$chave = md5($aluno->id . $aluno->nome . date("l jS \of F Y h:i:s A"));
	$relatorio = "components/com_portalsecretaria/reports/$chave.pdf";

	$arq = fopen($relatorio, 'w') or die('CREATE ERROR');

	$pdf = new Cezpdf();
	$pdf->selectFont('pdf-php/fonts/Helvetica.afm');
	$optionsText = array (
		'justification' => 'center',
		'spacing' => 1.3
	);
	$dados = array (
		'justification' => 'justify',
		'spacing' => 1.0
	);
	$optionsTable = array (
		'showLines' => 1,
		'showHeadings' => 1,
		'shaded' => 2,
		'textCol' => array (
			0.1,
			0.1,
			1
		),
		'shadeCol' => array (
			1,
			0.1,
			0.1
		),
		'shadeCol2' => array (
			0.1,
			1,
			0.1
		),
		'fontSize' => 12,
		'titleFontSize' => 12,
		'rowGap' => 5,
		'colGap' => 5,
		'lineCol' => array (
			0,
			0,
			1
		),
		'xPos' => 'center',
		'width' => 400
	);

	$pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 490, 720, 75);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 720, 100);
	$pdf->ezText('<b>PODER EXECUTIVO</b>', 12, $optionsText);
	$pdf->ezText('<b>MINISTÉRIO DA EDUCA��O</b>', 10, $optionsText);
	$pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>', 10, $optionsText);
	$pdf->ezText('<b>INSTITUTO DE COMPUTA��O</b>', 10, $optionsText);
	$pdf->ezText('', 11, $optionsText);
	$pdf->ezText('<b>PROGRAMA DE P�S-GRADUA��O EM INFORM�TICA</b>', 10, $optionsText);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText(''); //Para quebra de linha
	$pdf->setLineStyle(2);
	$pdf->line(20, 700, 580, 700);
	$pdf->ezText('<b>RELAT�RIO DE ALUNO</b>', 12, $optionsText);
	$anoIngresso = date("m/Y", strtotime($aluno->anoingresso));
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Aluno:</b> " . utf8_decode($aluno->nome), 10, $dados);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Matr��cula:</b> $aluno->matricula", 10, $dados);
	$pdf->addText(280, 615, 10, "<b>Status:</b> " . $status[$aluno->status], 0, 0);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Email:</b> $aluno->email", 10, $dados);
	$pdf->addText(280, 582, 10, "<b>Ano de Ingresso:</b> $anoIngresso", 0, 0);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Curso:</b>  " . $curso[$aluno->curso], 10, $dados);
	$pdf->addText(280, 546, 10, "<b>Regime de Dedica��o:</b> " . $regime[$aluno->regime], 0, 0);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Bolsista:</b> $aluno->bolsista", 10, $dados);
	$pdf->addText(280, 511, 10, "<b>Ag�ncia de Fomento:</b> $aluno->agencia", 0, 0);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Orientador:</b> " . utf8_decode(verProfessor($aluno->orientador)), 10, $dados);
	$pdf->addText(280, 477, 10, "<b>Linha de Pesquisa:</b> " . utf8_decode(verLinhaPesquisa($aluno->area, 1)), 0, 0);
	$pdf->setLineStyle(1);
	$pdf->line(20, 465, 580, 465);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Endere�o:</b> " . utf8_decode($aluno->endereco), 10, $dados);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Bairro:</b> " . utf8_decode($aluno->bairro), 10, $dados);
	$pdf->addText(280, 408, 10, "<b>CEP:</b>  $aluno->cep", 0, 0);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Cidade:</b> " . utf8_decode($aluno->cidade), 10, $dados);
	$pdf->addText(280, 373, 10, "<b>UF:</b> $aluno->uf", 0, 0);
	$pdf->line(20, 360, 580, 360);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Data de Nascimento:</b> $aluno->datanascimento", 10, $dados);
	$pdf->addText(280, 339, 10, "<b>Sexo:</b> " . $sexo[$aluno->sexo], 0, 0);
	$pdf->addText(410, 339, 10, "<b>Estado Civil:</b> $aluno->estadocivil", 0, 0);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>CPF:</b> $aluno->cpf", 10, $dados);
	$pdf->addText(280, 304, 10, "<b>Nacionalidade:</b> " . utf8_decode($aluno->pais), 0, 0);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>RG:</b> $aluno->rg", 10, $dados);
	$pdf->addText(210, 269, 10, "<b>Org�o Expedidor:</b> $aluno->orgaoexpeditor", 0, 0);
	$pdf->addText(390, 269, 10, "<b>Data de expedi��o:</b> $aluno->dataexpedicao", 0, 0);
	$pdf->ezText('', 10, $dados);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Telefone 1: </b> $aluno->telresidencial", 10, $dados);
	$pdf->addText(210, 234, 10, "<b>Telefone 2:</b> $aluno->telcomercial", 0, 0);
	$pdf->addText(390, 234, 10, "<b>Telefone 3:</b> $aluno->telcelular", 0, 0);
	$pdf->ezText('', 10, $dados);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Nome do Pai:</b> " . utf8_decode($aluno->nomepai), 10, $dados);
	$pdf->addText(280, 199, 10, "<b>Nome da M�e:</b> " . utf8_decode($aluno->nomemae), 0, 0);
	$pdf->line(20, 50, 580, 50);
	$pdf->addText(80, 40, 8, 'Av. Rodrigo Ot�vio, 6.200 - Campus Universit�rio Senador Arthur Virg�lio Filho - CEP 69077-000 - Manaus, AM, Brasil', 0, 0);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 140, 30, 8, 8);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 229, 30, 8, 8);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 383, 30, 8, 8);
	$pdf->addText(150, 30, 8, 'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br', 0, 0);

	$pdfcode = $pdf->output();
	fwrite($arq, $pdfcode);
	fclose($arq);

	header("Location: " . $relatorio);

}

// REGISTRAR MATRICULA
function registrarMatricula($idAluno, $idPeriodo) {
	$database = & JFactory :: getDBO();

	$sql = "UPDATE #__matricula SET status = 1 WHERE idAluno = $idAluno AND idPeriodo = $idPeriodo";
	$database->setQuery($sql);
	$database->Query();
}

// IMPRIMIR MATRICULA
function imprimirMatricula($aluno, $disciplinas, $matricula) {

	global $mosConfig_lang;
	$database = & JFactory :: getDBO();

	$sexo = array (
		'M' => "Masculino",
		'F' => "Feminimo"
	);
	$curso = array (
		1 => "Mestrado",
		2 => "Doutorado",
		3 => "Especial"
	);
	$pesquisa = array (
		1 => "Disserta&#231;&#227;o",
		2 => "Tese",
		3 => "Pesquisa"
	);
	$regime = array (
		1 => "Integral",
		2 => "Parcial"
	);

	$fase = array (
		1 => 'Definição do Tema',
		2 => 'Preparação e Apresentação do Projeto',
		3 => 'Pesquisa Bibliográfica',
		4 => 'Desenvolvimento de Projeto',
		5 => 'Conclusão',
		6 => 'Redação Final',
		7 => "Apresentação - Data Prevista: " . $matricula[0]->dataPrevista . ""
	);

	$mes = date("m");
	switch ($mes) {

		case 1 :
			$mes = "Janeiro";
			break;
		case 2 :
			$mes = "Fevereiro";
			break;
		case 3 :
			$mes = "Marï¿½o";
			break;
		case 4 :
			$mes = "Abril";
			break;
		case 5 :
			$mes = "Maio";
			break;
		case 6 :
			$mes = "Junho";
			break;
		case 7 :
			$mes = "Julho";
			break;
		case 8 :
			$mes = "Agosto";
			break;
		case 9 :
			$mes = "Setembro";
			break;
		case 10 :
			$mes = "Outubro";
			break;
		case 11 :
			$mes = "Novembro";
			break;
		case 12 :
			$mes = "Dezembro";
			break;

	}

	$chave = md5($aluno->id . $aluno->nome . date("l jS \of F Y h:i:s A"));
	$comprovanteMatricula = "components/com_portalaluno/forms/$chave.pdf";
	$arq = fopen($comprovanteMatricula, 'w') or die("CREATE ERROR");

	$pdf = new Cezpdf();
	$pdf->selectFont('pdf-php/fonts/Helvetica.afm');
	$optionsText = array (
		'justification' => 'center',
		'spacing' => 1.5
	);
	$dados = array (
		'justification' => 'justify',
		'spacing' => 1.0
	);
	$optionsTable = array (
		'fontSize' => 8,
		'titleFontSize' => 8,
		'xPos' => 'center',
		'width' => 560,
		'cols' => array (
			'Código' => array (
				'width' => 50,
				'justification' => 'center'
			),
			'Disciplina' => array (
				'width' => 205
			),
			'Turma' => array (
				'width' => 35,
				'justification' => 'center'
			),
			'Professor' => array (
				'width' => 125
			),
			'CR' => array (
				'width' => 25,
				'justification' => 'center'
			),
			'CH' => array (
				'width' => 25,
				'justification' => 'center'
			),
			'Horário' => array (
				'width' => 95,
				'justification' => 'center'
			)
		)
	);

	$pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 490, 720, 75);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 720, 100);
	$pdf->ezText('<b>PODER EXECUTIVO</b>', 12, $optionsText);
	$pdf->ezText('<b>MINISTÉRIO DA EDUCAÇÃO</b>', 10, $optionsText);
	$pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>', 10, $optionsText);
	$pdf->ezText('<b>INSTITUTO DE COMPUTAÇÃO</b>', 10, $optionsText);
	$pdf->ezText('', 11, $optionsText);
	$pdf->ezText('<b>PROGRAMA DE PÓS-GRADUAÇÃO EM INFORMÁTICA</b>', 10, $optionsText);
	$pdf->addText(495, 665, 8, "<b>Data:</b> " . date("d/m/Y"), 0, 0);
	$pdf->addText(495, 675, 8, "<b>Hora:</b> " . date("H:i"), 0, 0);
	$pdf->setLineStyle(1);
	$pdf->line(20, 690, 580, 690);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText('<b>COMPROVANTE DE MATRÍCULA</b>', 12, $optionsText);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Aluno(a): </b>" . utf8_decode($aluno->nome), 10, $dados);
	$pdf->addText(430, 632, 10, "<b>Matrícula: </b> $aluno->matricula", 0, 0);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Linha de Pesquisa:</b> " . utf8_decode(verLinhaPesquisa($aluno->area, 1)), 10, $dados);
	$pdf->addText(430, 610, 10, "<b>Curso:</b> " . $curso[$aluno->curso], 0, 0);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Orientador:</b> " . utf8_decode(verProfessor($aluno->orientador)), 10, $dados);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Fase da Dissertação/Tese:</b> " . $fase[$matricula[0]->fasePesquisa], 10, $dados);
	$pdf->addText(430, 562, 10, "<b>Período: </b> " . $matricula[0]->semestre, 0, 0);
	$pdf->setLineStyle(1);
	$pdf->line(20, 550, 580, 550);
	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText("<b>Disciplinas Matriculadas</b>", 11, $optionsText);
	$pdf->ezText('');

	foreach ($disciplinas as $disciplina) {
		$disciplinasMatriculas[] = array (
			'Código' => $disciplina->codigo,
			'Disciplina' => utf8_decode($disciplina->nomeDisciplina),
			'Turma' => $disciplina->turma,
			'CR' => $disciplina->creditos,
			'CH' => $disciplina->carga,
			'Professor' => utf8_decode($disciplina->professor),
			'Horário' => utf8_decode($disciplina->horario)
		);
	}

	$pdf->ezTable($disciplinasMatriculas, $cols, '', $optionsTable);

	$pdf->ezText(''); //Para quebra de linha
	$pdf->ezText(''); //Para quebra de linha
	$pdf->addText(50, 200, 10, '<b>De acordo:</b>', 0, 0);
	$pdf->addText(85, 120, 8, 'Assinatura do Aluno', 0, 0);
	$pdf->addText(255, 120, 8, 'Assinatura do Orientador', 0, 0);
	$pdf->addText(415, 120, 8, 'Assinatura do Coordenador do PPGI', 0, 0);
	$pdf->line(40, 130, 210, 130);
	$pdf->line(220, 130, 380, 130);
	$pdf->line(390, 130, 560, 130);
	$pdf->addText(235, 90, 8, "<b>Manaus, " . date("d") . " de " . $mes . " de " . date("Y") . "</b>", 0, 0);
	$pdf->line(20, 55, 580, 55);
	$pdf->addText(80, 40, 8, 'Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur Virgílio Filho - CEP 69077-000 - Manaus, AM, Brasil', 0, 0);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 140, 30, 8, 8);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 229, 30, 8, 8);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 383, 30, 8, 8);
	$pdf->addText(150, 30, 8, 'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br', 0, 0);

	$pdfcode = $pdf->output();
	fwrite($arq, $pdfcode);
	fclose($arq);

	header("Location: " . $comprovanteMatricula);

}

// OPERACOES COM HISTORICO

function listarHistoricoAlunos($nome = "", $status, $anoingresso, $curso) {

	$database = & JFactory :: getDBO();

	if ($status == NULL)
		$status = 0;

	$sqlEstendido = "";
	$sqlEstendido2 = "";

	if ($anoingresso)
		$sqlEstendido = "AND anoingresso = '$anoingresso'";

	if ($curso > 0)
		$sqlEstendido2 = "AND curso = $curso";

	if ($status < 6)
		$sql = "SELECT * FROM #__aluno WHERE nome LIKE '%$nome%' AND status = $status " . $sqlEstendido . " " . $sqlEstendido2 . " ORDER BY nome ";
	else
		$sql = "SELECT * FROM #__aluno WHERE nome LIKE '%$nome%' " . $sqlEstendido . " " . $sqlEstendido2 . " ORDER BY nome ";

	$database->setQuery($sql);
	$alunos = $database->loadObjectList();

	listarHistoricoAlunosHTML($alunos, $nome, $status, $anoingresso, $curso);
}


///////////////////////////

function listarAlunosPPGI() {

	$database = & JFactory :: getDBO();

	$sql = "SELECT * FROM #__aluno ORDER BY nome ";

	$database->setQuery($sql);
	$alunos = $database->loadObjectList();

	listarAlunosPPGIHTML($alunos);
}

//////////////////////////////////////////////////////////////

function listarAlunosPPGIHTML($alunos) {

	$Itemid = JRequest :: getInt('Itemid', 0);
	$database = & JFactory :: getDBO();
?>

  <script src="components/com_portalsecretaria/jquery/javascripts/jquery-1.4.2.min.js" type="text/javascript"></script>            
  <script type="text/javascript" src="components/com_portalsecretaria/jquery/javascripts/picnet.table.filter.min.js"></script>    
<!--  <script src="components/com_portalsecretaria/jquery/javascripts/pagination.js" type="text/javascript"></script>-->
  
<script language="JavaScript">
<!--

function validarForm(form, idAluno)
{

   form.idAluno.value = idAluno;
   form.submit();
   return true;

}

function detalhar(form){

   var idSelecionado = 0;
   for(i = 0;i < form.idAlunoSelec.length;i++)
        if(form.idAlunoSelec[i].checked) idSelecionado = form.idAlunoSelec[i].value;

   if(idSelecionado > 0){
       form.task.value = 'detalharAluno';
       form.idAluno.value = idSelecionado;
       form.submit();
   }
   else{
       alert('Ao menos 1 item deve ser selecionado para edi\xE7\xE3o.')
   }
}

function historico(form){

   var idSelecionado = 0;
   for(i = 0;i < form.idAlunoSelec.length;i++)
        if(form.idAlunoSelec[i].checked) idSelecionado = form.idAlunoSelec[i].value;

   if(idSelecionado > 0){
       window.open("index.php?option=com_portalaluno&Itemid=190&task=historico&idAluno="+idSelecionado,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
   }
   else{
       alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o da declara\xE7\xE3o do historico.')
   }
}

function editar(form){

   var idSelecionado = 0;
   for(i = 0;i < form.idAlunoSelec.length;i++)
        if(form.idAlunoSelec[i].checked) idSelecionado = form.idAlunoSelec[i].value;

   if(idSelecionado > 0){
	   form.task.value = 'editarAluno';
       form.idAluno.value = idSelecionado;
       form.submit();
   }
   else{
       alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o da declara\xE7\xE3o.')
   }

}


function declaracao(form){

   var idSelecionado = 0;
   for(i = 0;i < form.idAlunoSelec.length;i++)
        if(form.idAlunoSelec[i].checked) idSelecionado = form.idAlunoSelec[i].value;

   if(idSelecionado > 0){
       window.open("index.php?option=com_portalaluno&Itemid=190&task=declaracao&idAluno="+idSelecionado,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
   }
   else{
       alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o da declara\xE7\xE3o.')
   }
}


function banca(form){

   var idSelecionado = 0;
   for(i = 0;i < form.idAlunoSelec.length;i++)
        if(form.idAlunoSelec[i].checked) idSelecionado = form.idAlunoSelec[i].value;

   if(idSelecionado > 0){
       form.task.value = 'banca';
       form.idAluno.value = idSelecionado;
       form.submit();
   }
   else{
       alert('Ao menos 1 item deve ser selecionado para gerenciar suas bancas de defesa.')
   }
}

//-->
</script>

  <script type="text/javascript">
        $(document).ready(function() {
          // Initialise Plugin
            var options1 = {
                
                //additionalFilterTriggers: [$('#Corrente'), $('#Egresso'), $('#Desistente'), $('#Desligado'), $('#Jubilado'), $('#Trancado')],
				matchingRow: function(state, tr, textTokens) {
                  if (!state || !state.id) {
                    return true;
                  }
                  var child = tr.children('td:eq(1)');
                  if (!child) return true;
                  var val = child.text();
                  switch (state.id) {
                  case 'Corrente':
                    return state.value !== true || val === 'Corrente';
                  case 'Egresso':
                    return state.value !== true || val === 'Egresso';
				  case 'Desistente':
                    return state.value !== true || val === 'Desistente';
				  case 'Desligado':
                    return state.value !== true || val === 'Desligado';					
				  case 'Jubilado':
                    return state.value !== true || val === 'Jubilado';					
				  case 'Trancado':
                    return state.value !== true || val === 'Trancado';
  				  default:
                    return true;
                  }
                }
            };

            $('#demotable1').tableFilter(options1);
           
        });
  </script>
<style type="text/css">

* {  
  font-family:arial;
  line-height:1.5em;
  }

#redes{
  background-color:#ACF3FD;
  border: solid 1px silver;  
  }
#engsw_sist{
  background-color:#B3FF99  ;
  border: solid 1px silver;
  }
#bd_ri{
  background-color:#E8C6FF;
  border: solid 1px silver;
  }
 #otimizacao{
  background-color:#FFECB0;
  border: solid 1px silver;
  }
 #ia{
  background-color:#E9D0B6;
  border: solid 1px silver;
  }
 #visao{
  background-color:#FFA4A4;
  border: solid 1px silver;
  }
  
#mestrado{
  background-color:#ACF3FD;
  border: solid 1px silver;
  }  
#doutorado{
  background-color:#B3FF99;
  border: solid 1px silver;
  }    
#especial{
  background-color:#FFECB0;
  border: solid 1px silver;
  }    
  
table,
th {
  border: solid 1px silver;
  color:#666;
  padding:5px;
  font-size:8pt;
  }
table {
  border-collapse:collapse;
  }
table tr {
  background-color:#eee;
  }

th {
  background-color:#333;
  color:#fff;
  font-size:0.85em
  }
  
.hover { background-color: #106166; color: #fff; cursor:pointer; }
.page{ margin:5px;}  

tbody tr:nth-of-type(even){
background:rgba(179,216,179,0.5);
}
 
tbody tr:nth-of-type(odd){
background:rgba(222,240,222,0.5);
}

thead th{
background:rgba(28,157,162,0.5);
}
</style>
</head>
<body>  

  <div class="content">
    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" method="post" >
      <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-new">
           		<a href="javascript:document.form.task.value='addAluno';validarForm(document.form, 0)">
           			<span class="icon-32-new"></span><?php echo JText::_( 'Novo' ); ?></a>
				</div>
				<div class="icon" id="toolbar-preview">
           		<a href="javascript:detalhar(document.form)">
           			<span class="icon-32-preview"></span><?php echo JText::_( 'Detalhar' ); ?></a>
				</div>
				<div class="icon" id="toolbar-edit">
           		<a href="javascript:editar(document.form)">
           			<span class="icon-32-edit"></span><?php echo JText::_( 'Editar' ); ?></a>
				</div>
				<div class="icon" id="toolbar-stats">
           		<a href="javascript:historico(document.form)">
           			<span class="icon-32-stats"></span><?php echo JText::_( 'Hist&#243;rico' ); ?></a>
				</div>
				<div class="icon" id="toolbar-copy">
           		<a href="javascript:declaracao(document.form)">
           			<span class="icon-32-copy"></span><?php echo JText::_( 'Declara&#231;&#227;o' ); ?></a>
				</div>
				<div class="icon" id="toolbar-apply">
           		<a href="javascript:banca(document.form)">
           			<span class="icon-32-apply"></span><?php echo JText::_( 'Defesas' ); ?></a>
				</div>
				<div class="icon" id="toolbar-back">
           		<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
		</div>
    <div class="clr"></div>
	</div>
    <div class="pagetitle icon-48-contact"><h2>Alunos</h2></div>
    </div></div>
	 <!-- <h3>Escolha o status do aluno:</h3>
      <input type="checkbox" id="Corrente" CHECKED> Correntes 
	  <input type="checkbox" id="Egresso"> Egressos
	  <input type="checkbox" id="Desistente"> Desistentes
	  <input type="checkbox" id="Desligado"> Desligados
	  <input type="checkbox" id="Jubilado"> Jubilados
	  <input type="checkbox" id="Trancado"> Com Matr&#237;cula Trancada
	  -->
      <table id="demotable1" width="100%">
        <thead>
          <tr>
			<th width="5%"></th>
			<th width="10%" filter-type="ddl">Status</th>
			<th width="15%">Matr&#237;cula</th>
			<th width="35%">Nome</th>
			<th width="15%" filter-type="ddl">Ingresso</th>
			<th width="15%" filter-type="ddl">Curso</th>
			<th width="5%" filter-type="ddl">Linha de Pesquisa</th>
			</tr>
          <tr class="filters">
			<td></td>
			<td></td>
			<td><input type="text" id="demotable1_filter_1" class="filter" title="Nome do aluno" style="width: 95%;"></td>
			<td><input type="text" id="demotable1_filter_4" class="filter" title="Mês/Ano de ingresso do aluno" style="width: 95%;"></td>
			<td><select id="demotable1_filter_2" class="filter" style="width: 95%;"><option>Todos...</option><option value="Mestrado">Mestrado</option><option value="Doutorado">Doutorado</option><option value="Especial">Especial</option></select></td>
			<td><select id="demotable1_filter_3" class="filter" style="width: 95%;"><option>Todos...</option><option value="Engenharia de Software">Engenharia de Software</option><option value="Banco de Dados">Banco de Dados</option><option value="Redes de Computadores">Redes de Computadores</option></select></td>
			</tr></thead>
        <tbody>
		<?php
			$i = 0;
			$table_bgcolor_even = "#e6e6e6";
			$table_bgcolor_odd = "#FFFFFF";
			$status = array (
				0 => "Corrente",
				1 => "Egresso",
				2 => "Desistente",
				3 => "Desligado",
				4 => "Jubilado",
				5 => "Matr&#237;cula Trancada"
			);
			$statusImg = array (
				0 => "ativo.png",
				1 => "egresso.png",
				2 => "desistente.png",
				3 => "desligado.gif",
				4 => "reprovar.gif",
				5 => "trancado.jpg"
			);
			$curso = array (
				1 => "mestrado",
				2 => "doutorado",
				3 => "especial"
			);
			$linhaPesquisa = array (
				1 => "bd_ri",
				2 => "engsw_sist",
				3 => "ia",
				4 => "visao",
				5 => "redes",
				6 => "otimizacao"
			);
			
			foreach ($alunos as $aluno) {
				$i = $i +1;
				if ($i % 2) {
					echo ("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'  filtermatch='false'>");
				} else {
					echo ("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
				}
		?>

		<td width='16'><input type="radio" name="idAlunoSelec" value="<?php echo $aluno->id;?>"></td>
		<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $statusImg[$aluno->status];?>' width='16' height='16' title='<?php echo $status[$aluno->status];?>'><?php echo $status[$aluno->status];?></td>
		<td><?php echo $aluno->matricula;?></td>
		<td><?php echo $aluno->nome;?></td>
		<td><?php $data = $aluno->anoingresso; echo date("Y/m", strtotime($data));?></td>
		<td id="<?php echo $curso[$aluno->curso];?>"><?php echo strtoupper($curso[$aluno->curso]);?></td>
		<td id="<?php echo $linhaPesquisa[$aluno->area];?>"><?php echo strtoupper($linhaPesquisa[$aluno->area]);?></td>
	</tr>

	<?php

	}		
	?>
      </table>
     <input name='task' type='hidden' value='alunos'>
     <input name='idAlunoSelec' type='hidden' value='0'>
     <input name='idAluno' type='hidden' value=''>
     </form>
  </div>
  
  <body>

 <?php

}


// CADASTRAR ALUNO : TELA
function cadastrarNovoAluno($aluno = NULL, $nome, $status, $anoingresso, $curso) {
	$database = & JFactory :: getDBO();
	$Itemid = JRequest :: getInt('Itemid', 0); ?>

    <script type="text/javascript" src="components/com_portalsecretaria/assets/js/jquery.js"></script>

	<script language="JavaScript">
		function IsEmpty(aTextField) {	
		   if ((aTextField.value.length==0) ||  (aTextField.value==null) ) {
		      return true;
		   } else { return false; }
		}

		function isValidEmail(str) {
		   return (str.indexOf(".") > 2) && (str.indexOf("@") > 0);
		}

		function radio_button_checker(elem) {
			var radio_choice = false;

			for (counter = 0; counter < elem.length; counter++) {
				if (elem[counter].checked)
					radio_choice = true;
			}

			return (radio_choice);
		}

		function isPdf(file) {
			extArray = new Array(".pdf");
			allowSubmit = false;
			
			if (!file) return;
			while (file.indexOf("\\") != -1)
			file = file.slice(file.indexOf("\\") + 1);
			ext = file.slice(file.indexOf(".")).toLowerCase();
			
			for (var i = 0; i < extArray.length; i++) {
				if (extArray[i] == ext) { allowSubmit = true; break; }
			}
			
			return(allowSubmit);
		}

		function IsNumeric(sText) {
			var ValidChars = "0123456789.";
			var IsNumber=true;
			var Char;

			if (sText.length <= 0){
			  IsNumber = false;
			}

			for (i = 0; i < sText.length && IsNumber == true; i++) {
				Char = sText.charAt(i);
	  
				if (ValidChars.indexOf(Char) == -1) {
					IsNumber = false;
				}
			}
			
			return IsNumber;
		}

		function vercpf (cpf) {
		   if (cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" || cpf == "99999999999")
			  return false;
		   add = 0;

		   for (i=0; i < 9; i ++)
			  add += parseInt(cpf.charAt(i)) * (10 - i);
		
		   rev = 11 - (add % 11);
		   if (rev == 10 || rev == 11)
			  rev = 0;
		
		   if (rev != parseInt(cpf.charAt(9)))
			  return false;
		
		   add = 0;
		   for (i = 0; i < 10; i ++)
			  add += parseInt(cpf.charAt(i)) * (11 - i);
		
		   rev = 11 - (add % 11);
		   if (rev == 10 || rev == 11)
			   rev = 0;
		
		   if (rev != parseInt(cpf.charAt(10)))
			   return false;
		
		   return true;
		}

		function VerificaData(digData) {
        	var bissexto = 0;
	        var data = digData;
	        var tam = data.length;
			
	        if (tam == 10) {
                var dia = data.substr(0,2)
                var mes = data.substr(3,2)
                var ano = data.substr(6,4)
				
                if ((ano > 1900)||(ano < 2100)) {
	                switch (mes) {
						case '01':
						case '03':
						case '05':
						case '07':
						case '08':
						case '10':
						case '12':
							if (dia <= 31) {
								return true;
							}
						break
						case '04':
						case '06':
						case '09':
						case '11':
							if (dia <= 30) {
								return true;
							}
						break
						case '02':
							/* Validando ano Bissexto / fevereiro / dia */
							if ((ano % 4 == 0) || (ano % 100 == 0) || (ano % 400 == 0)) {
								bissexto = 1;
							}
							if ((bissexto == 1) && (dia <= 29)) {
								return true;
							}
							if ((bissexto != 1) && (dia <= 28)) {
								return true;
							}
						break
					}
				}
        	}
			
			return false;
		}

		function validarIngresso(periodo) {
		   if(periodo.length != 7){
		      return false;
		   }

			var semestre = periodo.substr(0,2);
			var barra = periodo.substr(2,1);
			var ano = periodo.substr(3,4);

			if(barra != '/' || isNaN(semestre) || isNaN(ano))
				return false;
			
			return true;
		}

		function ValidateformCadastro(formCadastro) {
			if(IsEmpty(formCadastro.nome)) {
				alert(unescape('O campo Nome deve ser preenchido.'))
				formCadastro.nome.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.matricula)) {
				alert(unescape('O campo Matricula deve ser preenchido.'))
				formCadastro.matricula.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.email)) {
				alert(unescape('O campo Email deve ser preenchido.'))
				formCadastro.email.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.endereco)) {
				alert('O campo Endereco deve ser preenchido.')
				formCadastro.endereco.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.bairro)) {
				alert('O campo Bairro deve ser preenchido.')
				formCadastro.bairro.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.cidade)) {
				alert('O campo Cidade deve ser preenchido.')
				formCadastro.cidade.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.uf)) {
				alert('O campo UF deve ser preenchido.')
				formCadastro.uf.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.cep)) {
				alert('O campo CEP deve ser preenchido.')
				formCadastro.cep.focus();
				return false;
			}
			
			if(!VerificaData(formCadastro.datanascimento.value)) {
				alert('Campo Data de Nascimento invalido.')
				formCadastro.datanascimento.focus();
				return false;
			}
			
			if (IsEmpty(formCadastro.sexo)) {
				alert('O campo Sexo deve ser preenchido.')
				formCadastro.sexo.focus();
				return (false);
			}
			
			if (!radio_button_checker(formCadastro.nacionalidade)) {
				// If there were no selections made display an alert box
				alert('O campo Nacionalidade deve ser preenchido.')
				formCadastro.nacionalidade[0].focus();
				return (false);
			}
			
			if(formCadastro.nacionalidade[1].checked && IsEmpty(formCadastro.pais)) {
				alert('Quando o candidato possuir a nacionalidade Estrangeira, deve ser informado o seu pais de origem.')
				formCadastro.pais.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.estadocivil)) {
				alert('O campo Estado Civil deve ser preenchido.')
				formCadastro.estadocivil.focus();
				return false;
			}
			
			if(formCadastro.nacionalidade[0].checked && !vercpf(formCadastro.cpf.value)) {
				alert('Campo CPF invalido.')
				formCadastro.cpf.focus();
				return false;
			}
			
			if(formCadastro.nacionalidade[0].checked && IsEmpty(formCadastro.rg)) {
				alert('O campo RG deve ser preenchido.')
				formCadastro.rg.focus();
				return false;
			}
			
			if(formCadastro.nacionalidade[0].checked && IsEmpty(formCadastro.orgaoexpedidor)) {
				alert('O campo Orgao Expedidor deve ser preenchido.')
				formCadastro.orgaoexpedidor.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.telresidencial)) {
				alert('O campo Telefone de Contato deve ser preenchido.')
				formCadastro.telresidencial.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.cursograd)) {
				alert('O campo Curso de Graduacao deve ser preenchido.')
				formCadastro.cursograd.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.instituicaograd)) {
				alert('O campo Instituicao onde cursou a Graduacao deve ser preenchido.')
				formCadastro.instituicaograd.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.crgrad)) {
				alert('O campo Coeficiente de Rendimento deve ser preenchido.')
				formCadastro.crgrad.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.egressograd)) {
				alert('O campo Ano Egresso deve ser preenchido.')
				formCadastro.egressograd.focus();
				return false;
			}
			
			if (IsEmpty (formCadastro.curso)) {
				alert('O campo Tipo de Aluno deve ser preenchido.')
				formCadastro.curso.focus();
				return (false);
			}
			
			if (IsEmpty (formCadastro.anoingresso)  || !validarIngresso(formCadastro.anoingresso.value)) {
				alert('O campo Ano de Ingresso deve ser preenchido no formato Mes/Ano (XX/YYYY).')
				formCadastro.anoingresso.focus();
				return (false);
			}
			
			if (!radio_button_checker(formCadastro.regime)) {
				alert('O campo Regime de Dedicacao deve ser preenchido.')
				formCadastro.regime[0].focus();
				return (false);
			}
			
			if (!radio_button_checker(formCadastro.bolsista)) {
				alert('O campo Bolsa deve ser preenchido.')
				formCadastro.solicitabolsa[0].focus();
				return (false);
			}
			
			if(formCadastro.bolsista[0].checked && IsEmpty(formCadastro.agencia)) {
				alert('O campo Agencia deve ser preenchido quando o aluno recebe bolsa.')
				formCadastro.agencia.focus();
				return false;
			}
			
			
			if(IsEmpty(formCadastro.orientador)) {
				alert('O campo Orientador deve ser preenchido.')
				formCadastro.orientador.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.area)) {
				alert('O campo Linha de Pesquisa deve ser preenchido.')
				formCadastro.area.focus();
				return false;
			}
			
			return true;
		}

		function voltarForm(form) {
			form.task.value = 'alunos';
			form.submit();
		}
	</script>
    
  	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />

	<link type="text/css" href="components/com_portalaluno/jquery-ui-1.8.20.custom.css" rel="Stylesheet" />

    <script src="components/com_portalaluno/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="components/com_portalaluno/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>

    <script>
		$(function() {
			$( "#anoingresso" ).datepicker({dateFormat: 'mm/yy'});
		});
	</script>
    
    <!-- MÁSCARAS -->
    <script type="text/javascript" src="components/com_portalsecretaria/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
		$(function($){
			$("#cpf").mask("99999999999");
			$("#cep").mask("99999-999");
			$("#rg").mask("9999999-9");
			$(".data").mask("99/99/9999");
			$(".telefone").mask("(99) 9999-9999");
			$(".ano").mask("9999");
		});
    </script>
    
    <style>
		.widht-minus{ width:120px; }
		
		.sub-title{ background-color:#7196d8; color:#FFF; font-weight:bold; width:100%; height:30px; }
    </style>

   <form method="post" name="formCadastro" id="form" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" method="post" onsubmit="javascript:return ValidateformCadastro(this)">
   
	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
		<div class="cpanel2">
            <div class="icon" id="toolbar-save">
          		<a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
                	<span class="icon-32-save"></span><?php echo JText::_( 'Salvar' ); ?>
	            </a>
            </div>
            
            <div class="icon" id="toolbar-cancel">
            	<a href="javascript:voltarForm(document.formCadastro)">
                	<span class="icon-32-cancel"></span><?php echo JText::_( 'Cancelar' ); ?>
                </a>
            </div>
		</div>
    	<div class="clr"></div>
		</div>
        
	<div class="pagetitle icon-48-contact"><h2>Cadastro de Alunos do PPGI</h2></div>
    </div></div>

    <span class="label label-info">Como proceder</span>
    <ul>
        <li>Preencha todos os campos necessários</li>
        <li>Os campos com <span class="obrigatory">*</span> são de preenchimento obrigatório</li>
    </ul>
    <hr />
   
	<table width="100%">
		<tr class="sub-title">
        	<td colspan="4">Dados Pessoais</td>
        </tr>
        <tr>
            <td colspan="2">Nome <span class="obrigatory">*</span></td>
            <td>Email <span class="obrigatory">*</span></td>
            <td>Matrícula <span class="obrigatory">*</span></td>
        </tr>
        <tr>
            <td colspan="2"><input type="text" name="nome" class="obrigatorio" style="width:300px;"></td>
            <td><input type="text" name="email" class="obrigatorio"></td>            
            <td><input type="text" name="matricula" class="obrigatorio widht-minus"></td>
        </tr>
        
        <tr>
        	<td colspan="2">Endereço <span class="obrigatory">*</span></td>
            <td>Bairro <span class="obrigatory">*</span></td>
            <td>CEP <span class="obrigatory">*</span></td>
        </tr>
        <tr>
        	<td colspan="2"><input type="text" name="endereco" class="obrigatorio" style="width:300px;"></td>
            <td><input type="text" name="bairro" class="obrigatorio"></td>
        	<td><input type="text" name="cep" id="cep" class="obrigatorio widht-minus"></td>
        </tr>
        
        <tr>
            <td>Cidade <span class="obrigatory">*</span></td>        
            <td>UF <span class="obrigatory">*</span></td>
            <td>Nacionalidade <span class="obrigatory">*</span></td>
            <td>País</td>
        </tr>
        <tr>
	        <td><input type="text" name="cidade" class="obrigatorio widht-minus"></td>
        	<td>
            <select name="uf" class="obrigatorio widht-minus">
                <option value=""></option>
                <option value="Outro">Outro</option>
                <option value="AC">AC</option>
                <option value="AL">AL</option>
                <option value="AM">AM</option>
                <option value="AP">AP</option>
                <option value="BA">BA</option>
                <option value="CE">CE</option>
                <option value="DF">DF</option>
                <option value="ES">ES</option>
                <option value="GO">GO</option>
                <option value="MA">MA</option>
                <option value="MG">MG</option>
                <option value="MS">MS</option>
                <option value="MT">MT</option>
                <option value="PA">PA</option>
                <option value="PB">PB</option>
                <option value="PE">PE</option>
                <option value="PI">PI</option>
                <option value="PR">PR</option>
                <option value="RJ">RJ</option>
                <option value="RN">RN</option>
                <option value="RO">RO</option>
                <option value="RR">RR</option>
                <option value="RS">RS</option>
                <option value="SC">SC</option>
                <option value="SE">SE</option>
                <option value="SP">SP</option>
                <option value="TO">TO</option>
            </select>
			</td>            
            <td><input name="nacionalidade" value="1" type="radio">Brasileira
            	<input name="nacionalidade" value="2" type="radio">Estrangeira
            </td>
            <td><input type="text" name="pais" class="widht-minus"></td>
        </tr>
        
        <tr>
            <td>Data de Nascimento <span class="obrigatory">*</span></td>
            <td>Sexo <span class="obrigatory">*</span></td>
            <td>Estado Civil <span class="obrigatory">*</span>
        </tr>
        <tr>
        	<td><input type="text" name="datanascimento" class="obrigatorio widht-minus data"></td>
            <td>
                <select name="sexo" class="obrigatorio widht-minus">
                    <option value=""></option>
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                </select>
			</td>
            <td>
            	<select name="estadocivil" class="obrigatorio widht-minus">
                    <option value=""></option>
                    <option value="Casado">Casado</option>
                    <option value="Divorciado">Divorciado</option>
                    <option value="Separado">Separado</option>                    
                    <option value="Solteiro">Solteiro</option>
                    <option value="Viúvo">Viúvo</option>                    
                </select>	
            </td>
        </tr>
        
        <tr style="font-weight:bold;">
        	<td colspan="4">Os campos CPF, RG e Órgão Expedidor são obrigatórios para candidatos com nacionalidade Brasileira <span class="obrigatory">*</span></td>
        </tr>
        <tr>
            <td>CPF </td>
            <td>RG </td>
            <td>&#211;rg&#227;o Expedidor </td>
            <td>Data de expedi&#231;&#227;o </td>
        </tr>
        <tr>
            <td><input type="text" name="cpf" id="cpf" class="obrigatorio widht-minus" placeholder="Somente números..."></td>
            <td><input type="text" name="rg" id="rg" class="obrigatorio widht-minus"></td>
            <td><input type="text" name="orgaoexpedidor" class="obrigatorio widht-minus"></td>
            <td><input type="text" name="dataexpedicao" class="obrigatorio widht-minus data"></td>
        </tr>
        <tr class="sub-title">
        	<td colspan="4">Telefones</td>
        </tr>
        <tr>
            <td>Residencial <span class="obrigatory">*</span></td>
            <td>Celular </td>
            <td>Comercial </td>
        </tr>
        <tr>
            <td><input type="text" name="telresidencial" class="telefone obrigatorio telefone widht-minus"></td>
            <td><input type="text" name="telcelular" class="telefone widht-minus"></td>
            <td><input type="text" name="telcomercial" class="telefone widht-minus"></td>
        </tr>
        <tr class="sub-title">
        	<td colspan="4">Filiação</td>
        </tr>
        <tr>
            <td colspan="2">Nome do Pai </td>
            <td colspan="2">Nome da Mãe </td>
        </tr>
        <tr>
            <td colspan="2"><input type="text" name="nomepai"></td>
            <td colspan="2"><input type="text" name="nomemae"></td>
        </tr>
        <tr class="sub-title">
        	<td colspan="4">Curso de Graduação </td>
        </tr>
        <tr>
            <td colspan="2">Curso <span class="obrigatory">*</span></td>
            <td colspan="2">Instituição <span class="obrigatory">*</span></td>
        </tr>
        <tr>
            <td colspan="2"><input type="text" name="cursograd" class="obrigatorio"></td>
            <td colspan="2"><input type="text" name="instituicaograd" class="obrigatorio"></td>
        </tr>
        <tr>
            <td colspan="2">Coeficiente Rendimento </td>
            <td colspan="2">Ano Egresso </td>
        </tr>
        <tr>
            <td colspan="2"><input type="text" name="crgrad" class="widht-minus"></td>
            <td colspan="2"><input type="text" name="egressograd" class="ano widht-minus"></td>
        </tr>
        <tr class="sub-title">
        	<td colspan="4">Curso de Pós-Graduação</td>
        </tr>
        <tr>
            <td>Tipo de Aluno <span class="obrigatory">*</span></td>
            <td>Mês/Ano de Ingresso </td>
            <td colspan="2">Regime de Dedicação <span class="obrigatory">*</span></td>
        </tr>
        <tr>
            <td>
            <select name="curso" class="widht-minus">
                <option value=""></option>
                <option value="1">Mestrado</option>
                <option value="2">Doutorado</option>
                <option value="3">Especial</option>
            </select>
            </td>
            <td><input type="text" id="anoingresso" name="anoingresso" value="<?php echo date("m/Y");?>" class="widht-minus" /></td>
            <td colspan="2">
            	<input name="regime" value="1" type="radio">Integral
            	<input name="regime" value="2" type="radio" >Parcial
            </td>            
        </tr>
        <tr>
            <td colspan="2">Aluno Bolsista? <span class="obrigatory">*</span></td>
            <td colspan="2">Se sim, de qual agência?</td>
        </tr>
        <tr>
        	<td colspan="2">	
            	<input name="bolsista" value="SIM" type="radio">Sim
            	<input name="bolsista" value="NAO" type="radio">Não
			</td>
			<td colspan="2"><input type="text" name="agencia" /></td>
        </tr>
        <tr>
            <td colspan="2">Orientador <span class="obrigatory">*</span></td>
            <td colspan="2">Linha de Pesquisa <span class="obrigatory">*</span></td>
        </tr>
        <tr>
        	<td colspan="2">
				<select name="orientador" class="obrigatorio" style="width:350px;">
					<option value=""></option>
					<?php
					$database->setQuery("SELECT * from #__professores WHERE ppgi = 1 ORDER BY nomeProfessor");
					$professores = $database->loadObjectList();
					
					foreach ($professores as $professor) { ?>
						<option value="<?php echo $professor->id;?>"><?php echo $professor->nomeProfessor;?></option>
					<?php } ?>
				</select>
			</td>        
	        <td colspan="2">
    		    <select name="area" class="obrigatorio" style="width:350px;">        
			        <option value=""></option>
            		<?php
			        $database->setQuery("SELECT * from #__linhaspesquisa ORDER BY nome");
			        $linhas = $database->loadObjectList();
        
			        foreach ($linhas as $linha) { ?>
			            <option value="<?php echo $linha->id;?>"><?php echo $linha->nome;?></option>
            		<?php } ?>
		        </select>
			</td>        
        </tr>
		</tbody>
	</table>

    <input name='task' type='hidden' value='salvarCadastro'>
    <input name='buscaAnoIngresso' type='hidden' value='<?php echo $anoingresso;?>'>
    <input name='buscaCurso' type='hidden' value='<?php echo $curso;?>'>
    <input name='buscaNome' type='hidden' value='<?php echo $nome;?>'>
    <input name='buscaStatus' type='hidden' value='<?php echo $status;?>'>

</form>

<?php }

// EDITAR ALUNO : TELA
function editarAluno($aluno, $nome, $status, $anoingresso, $curso) {
	$Itemid = JRequest :: getInt('Itemid', 0);
	$database = & JFactory :: getDBO(); ?>

    <script type="text/javascript" src="components/com_portalsecretaria/assets/js/jquery.js"></script>

	<script language="JavaScript">
		function IsEmpty(aTextField) {
		   if ((aTextField.value.length==0) || (aTextField.value==null) ) {
			  return true;
		   }
		   else { return false; }
		}

		function isValidEmail(str) {
		   return (str.indexOf(".") > 2) && (str.indexOf("@") > 0);
		}

		function radio_button_checker(elem) {
		  var radio_choice = false;
		
		  for (counter = 0; counter < elem.length; counter++) {
			if (elem[counter].checked)
			radio_choice = true;
		   }
		
		  return (radio_choice);
		}

		function isPdf(file) {
		   extArray = new Array(".pdf");
		   allowSubmit = false;
		
		   if (!file) return;
		   while (file.indexOf("\\") != -1)
		   file = file.slice(file.indexOf("\\") + 1);
		   ext = file.slice(file.indexOf(".")).toLowerCase();
		
		   for (var i = 0; i < extArray.length; i++) {
				 if (extArray[i] == ext) { allowSubmit = true; break; }
		   }
		
		   return(allowSubmit);
		}

		function IsNumeric(sText) {
		   var ValidChars = "0123456789.";
		   var IsNumber=true;
		   var Char;
		
		   if (sText.length <= 0){
			  IsNumber = false;
		   }
		
		   for (i = 0; i < sText.length && IsNumber == true; i++) {
			  Char = sText.charAt(i);
			  if (ValidChars.indexOf(Char) == -1) {
				 IsNumber = false;
			  }
		   }
		   return IsNumber;
		}

		function vercpf (cpf ){
		   if (cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" || cpf == "99999999999")
			  return false;
		   add = 0;
		
		   for (i=0; i < 9; i ++)
			  add += parseInt(cpf.charAt(i)) * (10 - i);
		
		   rev = 11 - (add % 11);
		   if (rev == 10 || rev == 11)
			  rev = 0;
		
		   if (rev != parseInt(cpf.charAt(9)))
			  return false;
		
		   add = 0;
		   for (i = 0; i < 10; i ++)
			  add += parseInt(cpf.charAt(i)) * (11 - i);
		
		   rev = 11 - (add % 11);
		   if (rev == 10 || rev == 11)
			   rev = 0;
		
		   if (rev != parseInt(cpf.charAt(10)))
			   return false;
		
		   return true;
		}

		function VerificaData(digData) {
			var bissexto = 0;
			var data = digData;
			var tam = data.length;
			
			if (tam == 10) {
				var dia = data.substr(0,2)
				var mes = data.substr(3,2)
				var ano = data.substr(6,4)
				
				if ((ano > 1900)||(ano < 2100)) {
					switch (mes) {
						case '01':
						case '03':
						case '05':
						case '07':
						case '08':
						case '10':
						case '12':
							if (dia <= 31) {
								return true;
							}
						break
						case '04':
						case '06':
						case '09':
						case '11':
							if (dia <= 30) {
								return true;
							}
						break
						case '02':
							/* Validando ano Bissexto / fevereiro / dia */
							if ((ano % 4 == 0) || (ano % 100 == 0) || (ano % 400 == 0)) {
								bissexto = 1;
							}
							if ((bissexto == 1) && (dia <= 29)) {
								return true;
							}
							if ((bissexto != 1) && (dia <= 28)) { 
								return true;
							}
						break
					}
				}
			}
			return false;
		}

		function ValidateformCadastro(formCadastro) {	
			if(IsEmpty(formCadastro.nome)) {
			  alert(unescape('O campo Nome deve ser preenchido.'))
			  formCadastro.nome.focus();
			  return false;
			}

			if(IsEmpty(formCadastro.matricula)) {
			  alert(unescape('O campo Matricula deve ser preenchido.'))
			  formCadastro.matricula.focus();
			  return false;
			}

			if(IsEmpty(formCadastro.email)) {
			  alert(unescape('O campo Email deve ser preenchido.'))
			  formCadastro.matricula.focus();
			  return false;
			}

			if(IsEmpty(formCadastro.endereco)) {
			  alert('O campo Endereco deve ser preenchido.')
			  formCadastro.endereco.focus();
			  return false;
			}

			if(IsEmpty(formCadastro.bairro)) {
			  alert('O campo Bairro deve ser preenchido.')
			  formCadastro.bairro.focus();
			  return false;
			}

			if(IsEmpty(formCadastro.cidade)) {
			  alert('O campo Cidade deve ser preenchido.')
			  formCadastro.cidade.focus();
			  return false;
			}

			if(IsEmpty(formCadastro.uf)) {
			  alert('O campo UF deve ser preenchido.')
			  formCadastro.uf.focus();
			  return false;
			}

			if(IsEmpty(formCadastro.cep)) {
			  alert('O campo CEP deve ser preenchido.')
			  formCadastro.cep.focus();
			  return false;
			}

			if(!VerificaData(formCadastro.datanascimento.value)) {
			  alert('Campo Data de Nascimento invalido.')
			  formCadastro.datanascimento.focus();
			  return false;
			}
			
			if (IsEmpty(formCadastro.sexo)) {
			 alert('O campo Sexo deve ser preenchido.')
			 formCadastro.sexo.focus();
			 return (false);
			}
			
			if (!radio_button_checker(formCadastro.nacionalidade)) {
			 // If there were no selections made display an alert box
			 alert('O campo Nacionalidade deve ser preenchido.')
			 formCadastro.nacionalidade[0].focus();
			 return (false);
			}

			if(formCadastro.nacionalidade[1].checked && IsEmpty(formCadastro.pais)) {
			  alert('Quando o candidato possuir a nacionalidade Estrangeira, deve ser informado o seu pais de origem.')
			 formCadastro.pais.focus();
			  return false;
			}
			
			if(IsEmpty(formCadastro.estadocivil)) {
			  alert('O campo Estado Civil deve ser preenchido.')
			  formCadastro.estadocivil.focus();
			  return false;
			}
			
			if(formCadastro.nacionalidade[0].checked && !vercpf(formCadastro.cpf.value)) {
			  alert('Campo CPF invalido.')
			  formCadastro.cpf.focus();
			  return false;
			}
			
			if(formCadastro.nacionalidade[0].checked && IsEmpty(formCadastro.rg)) {
			  alert('O campo RG deve ser preenchido.')
			  formCadastro.rg.focus();
			  return false;
			}
			
			if(formCadastro.nacionalidade[0].checked && IsEmpty(formCadastro.orgaoexpedidor)) {
			  alert('O campo Orgao Expedidor deve ser preenchido.')
			  formCadastro.orgaoexpedidor.focus();
			  return false;
			}

			if(IsEmpty(formCadastro.telresidencial)) {
			  alert('O campo Telefone de Contato deve ser preenchido.')
			  formCadastro.telresidencial.focus();
			  return false;
			}
			
			if(IsEmpty(formCadastro.cursograd)) {
			  alert('O campo Curso de Graduacao deve ser preenchido.')
			  formCadastro.cursograd.focus();
			  return false;
			}
			
			if(IsEmpty(formCadastro.instituicaograd)) {
			  alert('O campo Instituicao onde cursou a Graduacao deve ser preenchido.')
			  formCadastro.instituicaograd.focus();
			  return false;
			}
			
			if(IsEmpty(formCadastro.crgrad)) {
			  alert('O campo Coeficiente de Rendimento deve ser preenchido.')
			  formCadastro.crgrad.focus();
			  return false;
			}
			
			if(IsEmpty(formCadastro.egressograd)) {
			  alert('O campo Ano Egresso deve ser preenchido.')
			  formCadastro.egressograd.focus();
			  return false;
			}
			
			if (IsEmpty (formCadastro.curso)) {
			 alert('O campo Curso deve ser preenchido.')
			 formCadastro.cursodesejado[0].focus();
			 return (false);
			}

			if (IsEmpty (formCadastro.anoingresso)) {
			 alert('O campo Ano de Ingresso deve ser preenchido.')
			 formCadastro.anoingresso.focus();
			 return (false);
			}
			
			if (!radio_button_checker(formCadastro.regime)) {
			 alert('O campo Regime de Dedicacao deve ser preenchido.')
			 formCadastro.regime[0].focus();
			 return (false);
			}
			
			if (!radio_button_checker(formCadastro.bolsista)) {
			 alert('O campo Bolsa deve ser preenchido.')
			 formCadastro.solicitabolsa[0].focus();
			 return (false);
			}

			return true;
		}

		function voltarForm(form) {
		   form.task.value = 'alunos';
		   form.submit();
		}
	</script>
    
    <!-- MÁSCARAS -->
    <script type="text/javascript" src="components/com_portalsecretaria/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
		$(function($){
			$("#cpf").mask("99999999999");
			$("#cep").mask("99999-999");
			$(".data").mask("99/99/9999");
			$(".telefone").mask("(99) 9999-9999");
			$(".ano").mask("9999");
		});
    </script>
    
    <style>
		.widht-minus{ width:120px; }
		
		.sub-title{ background-color:#7196d8; color:#FFF; font-weight:bold; width:100%; height:30px; }
    </style>
    
    <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />    

	<form method="post" name="formCadastro" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" method="post"  onsubmit="javascript:return ValidateformCadastro(this)">
   
		<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        	<div class="cpanel2">
				<div class="icon" id="toolbar-save">
           			<a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
           				<span class="icon-32-save"></span><?php echo JText::_( 'Salvar' ); ?>
                    </a>
				</div>
                
				<div class="icon" id="toolbar-cancel">
           			<a href="javascript:voltarForm(document.formCadastro)">
           				<span class="icon-32-cancel"></span><?php echo JText::_( 'Cancelar' ); ?>
                    </a>
				</div>
                
            </div>
            <div class="clr"></div>
            </div>
        	<div class="pagetitle icon-48-contact"><h2>Edi&#231;&#227;o de Alunos do PPGI</h2></div>
		</div></div>

        <span class="label label-info">Como proceder</span>
        <ul>
            <li>Preencha todos os campos necessários</li>
            <li>Os campos com <span class="obrigatory">*</span> são de preenchimento obrigatório</li>
        </ul>
        <hr />

		<table width="100%">
            <tr class="sub-title">
                <td colspan="4"> Dados Pessoais</td>
            </tr>
            <tr>
                <td colspan="2">Nome <span class="obrigatory">*</span></td>
                <td>Email <span class="obrigatory">*</span></td>
                <td>Matrícula <span class="obrigatory">*</span></td>
            </tr>
            <tr>
                <td colspan="2"><input type="text" name="nome" class="obrigatorio" value="<?php echo $aluno->nome; ?>" style="width:300px;"></td>
                <td><input type="text" name="email" value="<?php echo $aluno->email; ?>" class="obrigatorio"></td>            
                <td><input type="text" name="matricula" value="<?php echo $aluno->matricula; ?>" class="obrigatorio widht-minus"></td>
            </tr>
            
            <tr>
                <td colspan="2">Endereço <span class="obrigatory">*</span></td>
                <td>Bairro <span class="obrigatory">*</span></td>
                <td>CEP <span class="obrigatory">*</span></td>
            </tr>
            <tr>
                <td colspan="2"><input type="text" name="endereco" value="<?php echo $aluno->endereco; ?>" class="obrigatorio" style="width:300px;"></td>
                <td><input type="text" name="bairro" value="<?php echo $aluno->bairro; ?>" class="obrigatorio"></td>
                <td><input type="text" name="cep" id="cep" value="<?php echo $aluno->cep; ?>" class="obrigatorio widht-minus"></td>
            </tr>
            
            <tr>
                <td>Cidade <span class="obrigatory">*</span></td>        
                <td>UF <span class="obrigatory">*</span></td>
                <td>Nacionalidade <span class="obrigatory">*</span></td>
                <td>País</td>
            </tr>
            <tr>
                <td><input type="text" name="cidade" value="<?php echo $aluno->cidade; ?>" class="obrigatorio widht-minus"></td>
                <td>
                <select name="uf" class="obrigatorio widht-minus">
                    <option value="" <?php if ($aluno->uf == "") echo 'SELECTED';?>></option>
                    <option value="Outro" <?php if ($aluno->uf == "Outro") echo 'SELECTED';?>>Outro</option>
                    <option value="AC" <?php if ($aluno->uf == "AC") echo 'SELECTED';?>>AC</option>
                    <option value="AL" <?php if ($aluno->uf == "AL") echo 'SELECTED';?>>AL</option>
                    <option value="AM" <?php if ($aluno->uf == "AM") echo 'SELECTED';?>>AM</option>
                    <option value="AP" <?php if ($aluno->uf == "AP") echo 'SELECTED';?>>AP</option>
                    <option value="BA" <?php if ($aluno->uf == "BA") echo 'SELECTED';?>>BA</option>
                    <option value="CE" <?php if ($aluno->uf == "CE") echo 'SELECTED';?>>CE</option>
                    <option value="DF" <?php if ($aluno->uf == "DF") echo 'SELECTED';?>>DF</option>
                    <option value="ES" <?php if ($aluno->uf == "ES") echo 'SELECTED';?>>ES</option>
                    <option value="GO" <?php if ($aluno->uf == "GO") echo 'SELECTED';?>>GO</option>
                    <option value="MA" <?php if ($aluno->uf == "MA") echo 'SELECTED';?>>MA</option>
                    <option value="MG" <?php if ($aluno->uf == "MG") echo 'SELECTED';?>>MG</option>
                    <option value="MS" <?php if ($aluno->uf == "MS") echo 'SELECTED';?>>MS</option>
                    <option value="MT" <?php if ($aluno->uf == "MT") echo 'SELECTED';?>>MT</option>
                    <option value="PA" <?php if ($aluno->uf == "PA") echo 'SELECTED';?>>PA</option>
                    <option value="PB" <?php if ($aluno->uf == "PB") echo 'SELECTED';?>>PB</option>
                    <option value="PE" <?php if ($aluno->uf == "PE") echo 'SELECTED';?>>PE</option>
                    <option value="PI" <?php if ($aluno->uf == "PI") echo 'SELECTED';?>>PI</option>
                    <option value="PR" <?php if ($aluno->uf == "PR") echo 'SELECTED';?>>PR</option>
                    <option value="RJ" <?php if ($aluno->uf == "RJ") echo 'SELECTED';?>>RJ</option>
                    <option value="RN" <?php if ($aluno->uf == "RN") echo 'SELECTED';?>>RN</option>
                    <option value="RO" <?php if ($aluno->uf == "RO") echo 'SELECTED';?>>RO</option>
                    <option value="RR" <?php if ($aluno->uf == "RR") echo 'SELECTED';?>>RR</option>
                    <option value="RS" <?php if ($aluno->uf == "RS") echo 'SELECTED';?>>RS</option>
                    <option value="SC" <?php if ($aluno->uf == "SC") echo 'SELECTED';?>>SC</option>
                    <option value="SE" <?php if ($aluno->uf == "SE") echo 'SELECTED';?>>SE</option>
                    <option value="SP" <?php if ($aluno->uf == "SP") echo 'SELECTED';?>>SP</option>
                    <option value="TO" <?php if ($aluno->uf == "TO") echo 'SELECTED';?>>TO</option>
                </select>
                </td>            
                <td>
                    <input name="nacionalidade" value="1" type="radio" <?php if ($aluno->nacionalidade == 1) echo 'CHECKED';?>>Brasileira
                    <input name="nacionalidade" value="2" type="radio" <?php if ($aluno->nacionalidade == 2) echo 'CHECKED';?>>Estrangeira
                </td>
                <td><input type="text" name="pais" value="<?php echo $aluno->pais; ?>" class="widht-minus"></td>
            </tr>
            
            <tr>
                <td>Data de Nascimento <span class="obrigatory">*</span></td>
                <td>Sexo <span class="obrigatory">*</span></td>
                <td>Estado Civil <span class="obrigatory">*</span>
            </tr>
            <tr>
                <td><input type="text" name="datanascimento" value="<?php echo $aluno->datanascimento; ?>" class="obrigatorio widht-minus data"></td>
                <td>
                    <select name="sexo" class="obrigatorio widht-minus">
                        <option value="" <?php if ($aluno->sexo == "") echo 'SELECTED';?>></option>
                        <option value="M" <?php if ($aluno->sexo == "M") echo 'SELECTED';?>>Masculino</option>
                        <option value="F" <?php if ($aluno->sexo == "F") echo 'SELECTED';?>>Feminino</option>
                    </select>
                </td>
                <td>
                    <select name="estadocivil" class="obrigatorio widht-minus">
                        <option value=""></option>
                        <option value="Casado" <?php if ($aluno->estadocivil == 'Casado') echo 'SELECTED'; ?>>Casado</option>
                        <option value="Divorciado" <?php if ($aluno->estadocivil == 'Divorciado') echo 'SELECTED'; ?>>Divorciado</option>
                        <option value="Separado" <?php if ($aluno->estadocivil == 'Separado') echo 'SELECTED'; ?>>Separado</option>                    
                        <option value="Solteiro" <?php if ($aluno->estadocivil == 'Solteiro') echo 'SELECTED'; ?>>Solteiro</option>
                        <option value="Viúvo" <?php if ($aluno->estadocivil == 'Viúvo') echo 'SELECTED'; ?>>Viúvo</option>                    
                    </select>	
                </td>
            </tr>
            
            <tr style="font-weight:bold;">
                <td colspan="4">Os campos CPF, RG e Órgão Expedidor são obrigatórios para candidatos com nacionalidade Brasileira <span class="obrigatory">*</span></td>
            </tr>
            <tr>
                <td>CPF </td>
                <td>RG </td>
                <td>&#211;rg&#227;o Expedidor </td>
                <td>Data de expedi&#231;&#227;o </td>
            </tr>
            <tr>
                <td><input type="text" name="cpf" id="cpf" value="<?php echo $aluno->cpf; ?>" class="obrigatorio widht-minus" placeholder="Somente números..."></td>
                <td><input type="text" name="rg" id="rg" value="<?php echo $aluno->rg; ?>" class="obrigatorio widht-minus"></td>
                <td><input type="text" name="orgaoexpedidor" value="<?php echo $aluno->orgaoexpeditor; ?>" class="obrigatorio widht-minus"></td>
                <td><input type="text" name="dataexpedicao" value="<?php echo $aluno->dataexpedicao; ?>" class="obrigatorio widht-minus data"></td>
            </tr>
            
            <tr class="sub-title">
                <td colspan="4"> Telefones</td>
            </tr>
            <tr>
                <td>Residencial <span class="obrigatory">*</span></td>
                <td>Celular </td>
                <td>Comercial </td>
            </tr>
            <tr>
                <td><input type="text" name="telresidencial" value="<?php echo $aluno->telresidencial; ?>" class="telefone obrigatorio telefone widht-minus"></td>
                <td><input type="text" name="telcelular" value="<?php echo $aluno->telcelular; ?>" class="telefone widht-minus"></td>
                <td><input type="text" name="telcomercial" value="<?php echo $aluno->telcomercial; ?>" class="telefone widht-minus"></td>
            </tr>
            
            <tr class="sub-title">
                <td colspan="4"> Filiação</td>
            </tr>
            <tr>
                <td colspan="2">Nome do Pai </td>
                <td colspan="2">Nome da Mãe </td>
            </tr>
            <tr>
                <td colspan="2"><input type="text" name="nomepai" value="<?php echo $aluno->nomepai; ?>"></td>
                <td colspan="2"><input type="text" name="nomemae" value="<?php echo $aluno->nomemae;?>"></td>
            </tr>
            
            <tr class="sub-title">
                <td colspan="4"> Curso de Graduação </td>
            </tr>
            <tr>
                <td colspan="2">Curso <span class="obrigatory">*</span></td>
                <td colspan="2">Instituição <span class="obrigatory">*</span></td>
            </tr>
            <tr>
                <td colspan="2"><input type="text" name="cursograd" value="<?php echo $aluno->cursograd; ?>" class="obrigatorio" style="width:330px;"></td>
                <td colspan="2"><input type="text" name="instituicaograd" value="<?php echo $aluno->instituicaograd; ?>" class="obrigatorio"></td>
            </tr>
            <tr>
                <td colspan="2">Coeficiente Rendimento </td>
                <td colspan="2">Ano Egresso </td>
            </tr>
            <tr>
                <td colspan="2"><input type="text" name="crgrad" value="<?php echo $aluno->crgrad; ?>" class="widht-minus"></td>
                <td colspan="2"><input type="text" name="egressograd" value="<?php echo $aluno->egressograd; ?>" class="ano widht-minus"></td>
            </tr>
            
            <tr class="sub-title">
                <td colspan="4"> Curso de Pós-Graduação</td>
            </tr>
            <tr>
                <td>Tipo de Aluno <span class="obrigatory">*</span></td>
                <td>Mês/Ano de Ingresso </td>
                <td colspan="2">Regime de Dedicação <span class="obrigatory">*</span></td>
            </tr>
            <tr>
                <td>
                <select name="curso" class="widht-minus">
                    <option value="" <?php if ($aluno->curso == "") echo 'SELECTED';?>></option>
                    <option value="1" <?php if ($aluno->curso == "1") echo 'SELECTED';?>>Mestrado</option>
                    <option value="2" <?php if ($aluno->curso == "2") echo 'SELECTED';?>>Doutorado</option>
                    <option value="3" <?php if ($aluno->curso == "3") echo 'SELECTED';?>>Especial</option>
                </select>
                </td>
                <td><input type="text" id="anoingresso" name="anoingresso" value="<?php echo date("m/Y", strtotime($aluno->anoingresso)); ?>" class="widht-minus" /></td>
                <td colspan="2">
                    <input name="regime" value="1" type="radio" <?php if ($aluno->regime == 1) echo 'CHECKED';?>>Integral
                    <input name="regime" value="2" type="radio" <?php if ($aluno->regime == 2) echo 'CHECKED';?>>Parcial
                </td>            
            </tr>
            <tr>
                <td colspan="2">Aluno Bolsista? <span class="obrigatory">*</span></td>
                <td colspan="2">Se sim, de qual agência?</td>
            </tr>
            <tr>
                <td colspan="2">	
                    <input name="bolsista" value="SIM" type="radio" <?php if ($aluno->bolsista == "SIM") echo 'CHECKED'; ?>>Sim
                    <input name="bolsista" value="NAO" type="radio" <?php if ($aluno->bolsista == "NAO") echo 'CHECKED'; ?>>Não
                </td>
                <td colspan="2"><input type="text" name="agencia" /></td>
            </tr>
            
            <tr>
                <td colspan="2">Orientador <span class="obrigatory">*</span></td>
                <td colspan="2">Linha de Pesquisa <span class="obrigatory">*</span></td>
            </tr>
            <tr>
                <td colspan="2">
                    <select name="orientador" class="obrigatorio" style="width:350px;">
                        <option value="" <?php if ($aluno->orientador == 0) echo 'SELECTED';?>></option>
                        <?php
                            $database->setQuery("SELECT * from #__professores WHERE ppgi = 1 OR id = ".$aluno->orientador." ORDER BY nomeProfessor");
                            $professores = $database->loadObjectList();
    
                            foreach ($professores as $professor) { ?>
                                <option value="<?php echo $professor->id;?>" <?php if ($professor->id == $aluno->orientador) echo 'SELECTED';?>><?php echo $professor->nomeProfessor;?></option>
                        <?php } ?>
                    </select>
                </td>        
                <td colspan="2">
                    <select name="area" class="obrigatorio" style="width:350px;">        
                        <option value="" <?php if ($aluno->area == "") echo 'SELECTED';?>></option>
                        <?php
                            $database->setQuery("SELECT * from #__linhaspesquisa ORDER BY nome");
                            $linhas = $database->loadObjectList();
    
                            foreach ($linhas as $linha) { ?>
                                <option value="<?php echo $linha->id;?>" <?php if($linha->id == $aluno->area) echo 'SELECTED'?>><?php echo $linha->nome;?></option>
                        <?php } ?>
                    </select>
                </td>        
            </tr>
            </tbody>
        </table>
        
        <div class="alert alert-info">
            <b>Condição Atual do Aluno</b>
            <select name="status" class="obrigatorio">
                <option value="0" <?php if ($aluno->status == 0) echo 'SELECTED';?>>Aluno Corrente</option>
                <!--<option value="1" <?php if ($aluno->status == 1) echo 'SELECTED';?>>Aluno Egresso</option>-->
                <option value="2" <?php if ($aluno->status == 2) echo 'SELECTED';?>>Aluno Desistente</option>
                <option value="3" <?php if ($aluno->status == 3) echo 'SELECTED';?>>Aluno Desligado</option>
                <option value="4" <?php if ($aluno->status == 4) echo 'SELECTED';?>>Aluno Jubilado</option>
                <option value="5" <?php if ($aluno->status == 5) echo 'SELECTED';?>>Aluno com Matr&#237;cula Trancada</option>
            </select>
        </div>
        
		<input name='idAluno' type='hidden' value='<?php echo $aluno->id;?>'>
		<input name='task' type='hidden' value='salvarEditar'>
        <input name='buscaAnoIngresso' type='hidden' value='<?php echo $anoingresso;?>'>
        <input name='buscaCurso' type='hidden' value='<?php echo $curso;?>'>
        <input name='buscaNome' type='hidden' value='<?php echo $nome;?>'>
        <input name='buscaStatus' type='hidden' value='<?php echo $status;?>'>
	</form>

<?php }

function detalharAluno($aluno){
	$Itemid = JRequest :: getInt('Itemid', 0);
	$database = & JFactory :: getDBO();
	
	$curso = array (
		1 => "Mestrado",
		2 => "Doutorado",
		3 => "Especial"
	);
	$creditosMinimo = array (
		1 => 24,
		2 => 40,
		3 => 24
	);
	$disciplinasObrigatorias = array (
		1 => 3,
		2 => 6,
		3 => 3
	);
	$status = array (
		0 => 'Aluno Corrente',
		1 => 'Aluno Egresso',
		2 => 'Aluno Desistente',
		3 => 'Aluno Desligado',
		4 => 'Aluno Jubilado',
		5 => 'Aluno com Matr&#237;cula Trancada'
	);
	$totalDisciplinasCursadas = calculoDisciplinasCursadas($aluno->id);
	if(!$totalDisciplinasCursadas) $totalDisciplinasCursadas = array("totDisciplinas" => 0, "numCreditos" => 0);
	if(!$totalDisciplinasCursadas[0]->numCreditos) $totalDisciplinasCursadas[0]->numCreditos = 0;
	$disciplinasCursadas = disciplinasCursadas($aluno->id);
	$disciplinasSemNota = disciplinasSemNota($aluno->id);
	$CR = CalculoPontuacao($aluno->id);
	
	$textoDisciplinasObrigatorias = "";
	$totDisciplinasObrigatorias = 0;
	$textoDisciplinasOptativas = "";
	$totDisciplinasOptativas = 0;

	$tam = sizeof($disciplinasCursadas); 
	if($tam){
		foreach($disciplinasCursadas as $disciplinaCursada){
			if($disciplinaCursada->obrigatoria){
  			   if(!$disciplinaCursada->periodo)
					$textoDisciplinasObrigatorias = $textoDisciplinasObrigatorias. "APROVEITAMENTO: $disciplinaCursada->codigo-$disciplinaCursada->nomeDisciplina<br>";
				else 
					$textoDisciplinasObrigatorias = $textoDisciplinasObrigatorias. "$disciplinaCursada->periodo: $disciplinaCursada->codigo-$disciplinaCursada->nomeDisciplina<br>";
			   $totDisciplinasObrigatorias++;
			 }
			 else{
				 if(!$disciplinaCursada->periodo)
					$textoDisciplinasOptativas = $textoDisciplinasOptativas. "APROVEITAMENTO: $disciplinaCursada->codigo-$disciplinaCursada->nomeDisciplina<br>";
				 else
					$textoDisciplinasOptativas = $textoDisciplinasOptativas. "$disciplinaCursada->periodo: $disciplinaCursada->codigo-$disciplinaCursada->nomeDisciplina<br>";
			   $totDisciplinasOptativas++;
			 }
		}
	}
	if($totDisciplinasObrigatorias == 0) $textoDisciplinasObrigatorias = "N&#227;o foram cursadas disciplinas obrigat&#243;rias";
	if($totDisciplinasOptativas == 0) $textoDisciplinasOptativas = "N&#227;o foram cursadas disciplinas optativas";

	$erroDefesa = 0;
	$erroCreditos = 0; 
	$erroDisciplinasObrigatorias = 0;
	$erroDisciplinasOptativas = 0;
	
	if($totalDisciplinasCursadas[0]->numCreditos < $creditosMinimo[$aluno->curso]) $erroCreditos = 1; 
	if($totDisciplinasObrigatorias < $disciplinasObrigatorias[$aluno->curso]) $erroDisciplinasObrigatorias = 1;
	if($totDisciplinasOptativas < (($creditosMinimo[$aluno->curso]/4) - $disciplinasObrigatorias[$aluno->curso])) $erroDisciplinasOptativas = 1;
	
?>

<script language="JavaScript">

function visualizar(form){
   window.open("index.php?option=com_portalsecretaria&Itemid=190&task=verAluno&idAluno="+form.idAluno.value,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
}

function formar(form){
	
	var msg = "O aluno n\u00e3o pode formar. Itens est\u00e3o pendentes:";
	var erro = false;
	if(form.creditosAbaixo.value == 1){
		msg = msg + "\n- Ele n\u00e3o pagou todos os cr\u00e9ditos exigidos para o curso.";
		erro = true;
	}
	if(form.disciplinasSemNota.value > 0){
		msg = msg + "\n- H\u00e1 disciplinas em que ele est\u00e1 inscrito ainda sem nota lan\u00e7ada.";
		erro = true;
	}
	if(form.coeficienteAbaixo.value < 2){
		msg = msg + "\n- Seu coeficiente est\u00e1 abaixo do minimo exigido pelo PPGI.";
		erro = true;
	}
	if(form.erroDefesa.value == 1){
		msg = msg + "\n- Ele ainda n\u00e3o realizou todas as defesas exigidas pelo PPGI.";
		erro = true;
	}
	if(form.erroObrigatorias.value == 1){
		msg = msg + "\n- N\u00e3o cursou o m\u00ednimo exigido de disciplinas obrigat\u00f3rias.";
		erro = true;
	}

	if(erro){
		alert(msg);
	}
	else{
		var confirmar = confirm('Confirmar a formatura do aluno? Este passo \u00e9 irrevers\u00edvel.');
		if(confirmar){
			form.task.value = 'formarAluno';
			form.submit();
		}
	}
}

function voltarForm(form) {
   form.task.value = 'alunos';
   form.submit();
}

</script>

   <form method="post" name="form" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" method="post"  onsubmit="javascript:return ValidateformCadastro(this)">
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-print">
           		<a href="javascript:visualizar(document.form)">
           			<span class="icon-32-print"></span><?php echo JText::_( 'Relat&#243;rio' ); ?></a>
				</div>
				<?php if($aluno->status == 0) { ?>
				<div class="icon" id="toolbar-default">
           		<a href="javascript:formar(document.form)">
           			<span class="icon-32-default"></span><?php echo JText::_( 'Formar' ); ?></a>
				</div>
				<?php } ?>
				<div class="icon" id="toolbar-back">
           		<a href="javascript:voltarForm(document.form)">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
		</div>
    <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-contact"><h2>Dados do Aluno</h2></div>
    </div></div>
	<p><h2>Dados Pessoais do(a) Aluno(a)</h2></p>
    <table width="100%" border="1" cellspacing="1" cellpadding="3" class="tabela">
    <tr>
      <td width="20%" bgcolor="#CCCCCC"><strong>Nome:</strong></td>
      <td width="50%" ><?php echo $aluno->nome;?></td>
      <td width="15%" bgcolor="#CCCCCC"><strong>Curso:</strong></td>
      <td width="15%" ><?php echo $curso[$aluno->curso];?></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC"><strong>Linha de Pesquisa:</strong></td>
      <td><?php echo verLinhaPesquisa($aluno->area, 1);?></td>
      <td bgcolor="#CCCCCC"><strong>Ingresso:</strong></td>
      <td><?php echo date("m/Y", strtotime($aluno->anoingresso));?></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC"><strong>Orientador:</strong></td>
      <td><?php echo verProfessor($aluno->orientador);?></td>
      <td bgcolor="#CCCCCC"><strong>Status:</strong></td>
      <td><?php echo $status[$aluno->status];?></td>
    </tr>
	
 </table>
 <p><h2>Dados Acad&#234;micos</h2></p>
 <table width="100%" border="1" cellspacing="1" cellpadding="3" class="tabela">
    <tr>
      <td width="15%" bgcolor="#CCCCCC"><strong>Cr&#233;ditos Obtidos:</strong></td>
	  <td width="10%" align="center" <?php if($totalDisciplinasCursadas[0]->numCreditos >= $creditosMinimo[$aluno->curso]) echo "bgcolor='#66ff99'"; else echo "bgcolor='#ff6666'";?>><?php echo $totalDisciplinasCursadas[0]->numCreditos;?>	  
      <td width="15%" bgcolor="#CCCCCC"><strong>Disciplinas Obrigat&#243;rias:</strong></td>
	  <td width="60%" <?php if($totDisciplinasObrigatorias >= $disciplinasObrigatorias[$aluno->curso]) echo "bgcolor='#66ff99'"; else echo "bgcolor='#ff6666'";?>><?php echo $textoDisciplinasObrigatorias; ?></td>
    </tr>
    <tr>
	  <td bgcolor="#CCCCCC"><strong>Coeficiente de Rendimento:</strong></td>
      <td align="center" <?php if($CR >= 2) echo "bgcolor='#66ff99'"; else echo "bgcolor='#ff6666'";?>><?php echo number_format($CR, 1);?></td>
	  <td bgcolor="#CCCCCC"><strong>Disciplinas sem Nota:</strong></td>
      <td <?php $tam = sizeof($disciplinasSemNota);	if(!$tam) echo "bgcolor='#66ff99'"; else echo "bgcolor='#ff6666'";?>>
			<?php 
			 
			if($tam){ 
				foreach($disciplinasSemNota as $disciplinaSemNota){
					if(!$disciplinaSemNota->periodo) echo "Aproveitamento: ";
					else echo "$disciplinaSemNota->periodo: ";
					echo "disciplinaSemNota->codigo-$disciplinaSemNota->nomeDisciplina<br>";
				}
			}
			else
				echo "N&#227;o h&#225; disciplinas sem nota lan&#231;ada";
	  ?></td>	
	</tr>
 </table>
 <p><h2>Defesas Realizadas</h2></p>
 <table width="100%" border="1" cellspacing="1" cellpadding="3" class="tabela">
    <tr>
      <td width="20%" bgcolor="#CCCCCC"><strong>Exame de Profici&#234;ncia:</strong></td>
      <td width="43%" ><?php echo $aluno->idiomaExameProf;?></td>
      <td width="5%" bgcolor="#CCCCCC"><strong>Data:</strong></td>
      <td width="10%" ><?php echo $aluno->dataExameProf;?></td>
      <td width="10%" bgcolor="#CCCCCC"><strong>Conceito:</strong></td>
      <td width="12%" <?php if($aluno->conceitoExameProf == "Aprovado") echo "bgcolor='#66ff99'"; else echo "bgcolor='#ff6666'"; ?>><?php if($aluno->conceitoExameProf != "") echo $aluno->conceitoExameProf; else { echo "N&#227;o realizada"; $erroDefesa = 1;}?></td>
	  </tr>
    <?php if($aluno->curso == 2) { ?>
	<tr>
      <td bgcolor="#CCCCCC"><strong>Qualificaca&#231;&#227;o I:</strong></td>
      <td><?php echo $aluno->tituloQual1;?></td>
      <td bgcolor="#CCCCCC"><strong>Data:</strong></td>
      <td><?php echo $aluno->dataQual1;?></td>
      <td bgcolor="#CCCCCC"><strong>Conceito:</strong></td>
      <td <?php if($aluno->conceitoQual1 == "Aprovado") echo "bgcolor='#66ff99'"; else echo "bgcolor='#ff6666'"; ?>><?php if($aluno->conceitoQual1 != "") echo $aluno->conceitoQual1; else { echo "N&#227;o realizada"; $erroDefesa = 1;}?></td>
    </tr>
	<?php } ?>
	<tr>
      <td bgcolor="#CCCCCC"><strong>Qualificaca&#231;&#227teste;o<?php if($aluno->curso == 2) echo " II";?>:</strong></td>
      <td><?php echo $aluno->tituloQual2;?></td>
      <td bgcolor="#CCCCCC"><strong>Data:</strong></td>
      <td><?php echo $aluno->dataQual2;?></td>
      <td bgcolor="#CCCCCC"><strong>Conceito:</strong></td>
      <td <?php if($aluno->conceitoQual2 == "Aprovado") echo "bgcolor='#66ff99'"; else echo "bgcolor='#ff6666'"; ?>><?php if($aluno->conceitoQual2 != "") echo $aluno->conceitoQual2; else { echo "N&#227;o realizada"; $erroDefesa = 1;}?></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC"><strong>Defesa de <?php if($aluno->curso == 2) echo "Tese"; else echo "Disserta&#231;&#227;o";?>:</strong></td>
      <td><?php echo $aluno->tituloTese;?></td>
      <td bgcolor="#CCCCCC"><strong>Data:</strong></td>
      <td><?php echo $aluno->dataTese;?></td>
      <td bgcolor="#CCCCCC"><strong>Conceito:</strong></td>
      <td <?php if($aluno->conceitoTese == "Aprovado") echo "bgcolor='#66ff99'"; else echo "bgcolor='#ff6666'";?>><?php if($aluno->conceitoTese != "") echo $aluno->conceitoTese; else { echo "N&#227;o realizada"; $erroDefesa = 1;} ?></td>
    </tr>
	</table>  
  <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">

</font>
    <input name='idAluno' type='hidden' value='<?php echo $aluno->id;?>'>
    <input name='task' type='hidden' value=''>
    <input name='creditosAbaixo' type='hidden' value='<?php echo $erroCreditos;?>'>
    <input name='disciplinasSemNota' type='hidden' value='<?php echo sizeof($disciplinasSemNota);?>'>
    <input name='coeficienteAbaixo' type='hidden' value='<?php echo $CR;?>'>
	<input name='erroDefesa' type='hidden' value='<?php echo $erroDefesa;?>'>
	<input name='erroObrigatorias' type='hidden' value='<?php echo $erroDisciplinasObrigatorias;?>'>
</form>

    <?php

}

function calculoDisciplinasCursadas($idAluno){

         $db = & JFactory::getDBO();
         $db->setQuery("SELECT COUNT(idDisciplina) as totDisciplinas, SUM(creditos) as numCreditos FROM #__disc_matricula AS DM JOIN #__disciplina AS D ON D.id = DM.idDisciplina WHERE DM.idAluno = $idAluno AND conceito IN ('A','B','C', 'AP') AND frequencia >= 75");
         $disciplinas = $db->loadObjectList();

         return($disciplinas);
}

function disciplinasCursadas($idAluno){

         $db = & JFactory::getDBO();
         $db->setQuery("SELECT idDisciplina, codigo, creditos, nomeDisciplina, periodo, obrigatoria, conceito, frequencia FROM #__disc_matricula AS DM JOIN #__disciplina AS D ON D.id = DM.idDisciplina LEFT JOIN #__periodos  AS P ON P.id = DM.idPeriodo WHERE DM.idAluno = $idAluno AND conceito IN ('A','B','C', 'AP') AND frequencia >= 75 ORDER BY periodo");
         $disciplinas = $db->loadObjectList();

         return($disciplinas);
}

function disciplinasSemNota($idAluno){

         $db = & JFactory::getDBO();
		 $sql = "SELECT idDisciplina, codigo, nomeDisciplina, periodo FROM #__disc_matricula AS DM JOIN #__disciplina AS D ON D.id = DM.idDisciplina JOIN #__periodos  AS P ON P.id = DM.idPeriodo WHERE DM.idAluno = $idAluno AND conceito IS NULL";
         $db->setQuery($sql);
         $disciplinas = $db->loadObjectList();
	 
         return($disciplinas);
}

function formarAluno($idAluno){

         $db = & JFactory::getDBO();
		 $sucesso = 1;
		 
		 // Remover as disciplinas que reprovou
		 $sql = "DELETE FROM #__disc_matricula WHERE conceito NOT IN ('A', 'B', 'C', 'AP') AND idAluno = $idAluno";
         $db->setQuery($sql);
		 $sucesso1 = $db->query();
		 
		// Mudar o status pra EGRESSO
		$sql = "UPDATE #__aluno SET status = 1 WHERE id = $idAluno";
		$db->setQuery($sql);
		$sucesso2 = $db->query();
		 
		 if($sucesso1 && $sucesso2){
			JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
		 }
		 else{
			JError::raiseWarning( 100, 'ERRO: Houve erro ao registrar a formatura do aluno. Verifique os dados e tente novamente.' );		 
		 }

		 
}

function listarPendenciasAlunosPPGI(){

	$Itemid = JRequest :: getInt('Itemid', 0);
	$database = & JFactory :: getDBO();
	
	$sql = "SELECT * FROM #__aluno ORDER BY nome ";

	$database->setQuery($sql);
	$alunos = $database->loadObjectList();
	
	$table_bgcolor_even = "#e6e6e6";
	$table_bgcolor_odd = "#FFFFFF";
	$status = array (
		0 => "Corrente",
		1 => "Egresso",
		2 => "Desistente",
		3 => "Desligado",
		4 => "Jubilado",
		5 => "Matr&#237;cula Trancada"
	);
	$statusImg = array (
		0 => "ativo.png",
		1 => "egresso.png",
		2 => "desistente.png",
		3 => "desligado.gif",
		4 => "reprovar.gif",
		5 => "trancado.jpg"
	);
	$curso = array (
		1 => "mestrado",
		2 => "doutorado",
		3 => "especial"
	);
	$linhaPesquisa = array (
		1 => "bd_ri",
		2 => "engsw_sist",
		3 => "ia",
		4 => "visao",
		5 => "redes",
		6 => "otimizacao"
	);
	
?>


  <script src="components/com_portalsecretaria/jquery/javascripts/jquery-1.4.2.min.js" type="text/javascript"></script>            
  <script type="text/javascript" src="components/com_portalsecretaria/jquery/javascripts/picnet.table.filter.min.js"></script>    
<!--  <script src="components/com_portalsecretaria/jquery/javascripts/pagination.js" type="text/javascript"></script>-->
  
<script language="JavaScript">
<!--

function validarForm(form, idAluno)
{

   form.idAluno.value = idAluno;
   form.submit();
   return true;

}

function detalhar(form){

   var idSelecionado = 0;
   for(i = 0;i < form.idAlunoSelec.length;i++)
        if(form.idAlunoSelec[i].checked) idSelecionado = form.idAlunoSelec[i].value;

   if(idSelecionado > 0){
       form.task.value = 'detalharAluno';
       form.idAluno.value = idSelecionado;
       form.submit();
   }
   else{
       alert('Ao menos 1 item deve ser selecionado para edi\xE7\xE3o.')
   }
}


//-->
</script>


<style type="text/css">

* {  
  font-family:arial;
  line-height:1.5em;
  }

#redes{
  background-color:#ACF3FD;
  border: solid 1px silver;  
  }
#engsw_sist{
  background-color:#B3FF99  ;
  border: solid 1px silver;
  }
#bd_ri{
  background-color:#E8C6FF;
  border: solid 1px silver;
  }
 #otimizacao{
  background-color:#FFECB0;
  border: solid 1px silver;
  }
 #ia{
  background-color:#E9D0B6;
  border: solid 1px silver;
  }
 #visao{
  background-color:#FFA4A4;
  border: solid 1px silver;
  }
  
#mestrado{
  background-color:#ACF3FD;
  border: solid 1px silver;
  }  
#doutorado{
  background-color:#B3FF99;
  border: solid 1px silver;
  }    
#especial{
  background-color:#FFECB0;
  border: solid 1px silver;
  }    
  
table,
th {
  border: solid 1px silver;
  color:#666;
  padding:5px;
  font-size:8pt;
  }
table {
  border-collapse:collapse;
  }
table tr {
  background-color:#eee;
  }

th {
  background-color:#333;
  color:#fff;
  font-size:0.85em
  }
  
.hover { background-color: #106166; color: #fff; cursor:pointer; }
.page{ margin:5px;}  

tbody tr:nth-of-type(even){
background:rgba(179,216,179,0.5);
}
 
tbody tr:nth-of-type(odd){
background:rgba(222,240,222,0.5);
}

thead th{
background:rgba(28,157,162,0.5);
}
</style>
</head>
<body>  

  <div class="content">
    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" method="post" >
      <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-preview">
           		<a href="javascript:detalhar(document.form)">
           			<span class="icon-32-preview"></span><?php echo JText::_( 'Detalhar' ); ?></a>
				</div>
				<div class="icon" id="toolbar-back">
           		<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
		</div>
    <div class="clr"></div>
	</div>
    <div class="pagetitle icon-48-contact"><h2>Alunos com Pendencia</h2></div>
    </div></div>
      <b>Mestrandos que qualificaram, mas n&#227;o realizaram Estudo Dirigido.</b>
	  <table id="demotable1" width="100%">
        <thead>
          <tr>
			<th width="5%"></th>
			<th width="10%"">Status</th>
			<th width="15%">Matr&#237;cula</th>
			<th width="35%">Nome</th>
			<th width="15%">Ingresso</th>
			<th width="15%">Curso</th>
			<th width="5%">Linha de Pesquisa</th>
			</tr>
		</thead>
        <tbody>
		<?php
			$i = 0;
			$alunosRetornados = verMestrandosQualificadosSemED();
			
			foreach ($alunosRetornados as $aluno) {
				$i = $i +1;
				if ($i % 2) {
					echo ("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'  filtermatch='false'>");
				} else {
					echo ("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
				}
		?>

		<td width='16'><input type="radio" name="idAlunoSelec" value="<?php echo $aluno->id;?>"></td>
		<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $statusImg[$aluno->status];?>' width='16' height='16' title='<?php echo $status[$aluno->status];?>'><?php echo $status[$aluno->status];?></td>
		<td><?php echo $aluno->matricula;?></td>
		<td><?php echo $aluno->nome;?></td>
		<td><?php $data = $aluno->anoingresso; echo date("Y/m", strtotime($data));?></td>
		<td id="<?php echo $curso[$aluno->curso];?>"><?php echo strtoupper($curso[$aluno->curso]);?></td>
		<td id="<?php echo $linhaPesquisa[$aluno->area];?>"><?php echo strtoupper($linhaPesquisa[$aluno->area]);?></td>
	</tr>

	<?php

	}		
	?>
      </table>
	<b>Mestrandos que n&#227;o qualificaram, mas tem nota de Estudo Dirigido.</b>
	  <table id="demotable1" width="100%">
        <thead>
          <tr>
			<th width="5%"></th>
			<th width="10%"">Status</th>
			<th width="15%">Matr&#237;cula</th>
			<th width="35%">Nome</th>
			<th width="15%">Ingresso</th>
			<th width="15%">Curso</th>
			<th width="5%">Linha de Pesquisa</th>
			</tr>
		</thead>
        <tbody>
		<?php
			$i = 0;
			$alunosRetornados = verMestrandosNaoQualificadosComED();
			
			foreach ($alunosRetornados as $aluno) {
				$i = $i +1;
				if ($i % 2) {
					echo ("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'  filtermatch='false'>");
				} else {
					echo ("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
				}
		?>

		<td width='16'><input type="radio" name="idAlunoSelec" value="<?php echo $aluno->id;?>"></td>
		<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $statusImg[$aluno->status];?>' width='16' height='16' title='<?php echo $status[$aluno->status];?>'><?php echo $status[$aluno->status];?></td>
		<td><?php echo $aluno->matricula;?></td>
		<td><?php echo $aluno->nome;?></td>
		<td><?php $data = $aluno->anoingresso; echo date("Y/m", strtotime($data));?></td>
		<td id="<?php echo $curso[$aluno->curso];?>"><?php echo strtoupper($curso[$aluno->curso]);?></td>
		<td id="<?php echo $linhaPesquisa[$aluno->area];?>"><?php echo strtoupper($linhaPesquisa[$aluno->area]);?></td>
	</tr>

	<?php

	}		
	?>
      </table>	  
	  <b>Doutorandos que n&#227;o fizeram Qualifica&#231;&#227;o 1, mas j&#225; pagaram Estudo Dirigido 1.</b>
	  <table id="demotable1" width="100%">
        <thead>
          <tr>
			<th width="5%"></th>
			<th width="10%"">Status</th>
			<th width="15%">Matr&#237;cula</th>
			<th width="35%">Nome</th>
			<th width="15%">Ingresso</th>
			<th width="15%">Curso</th>
			<th width="5%">Linha de Pesquisa</th>
			</tr>
		</thead>
        <tbody>
		<?php
			$i = 0;
			$alunosRetornados = verDoutorandosNaoQualificadosComED();
			
			foreach ($alunosRetornados as $aluno) {
				$i = $i +1;
				if ($i % 2) {
					echo ("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'  filtermatch='false'>");
				} else {
					echo ("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
				}
		?>

		<td width='16'><input type="radio" name="idAlunoSelec" value="<?php echo $aluno->id;?>"></td>
		<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $statusImg[$aluno->status];?>' width='16' height='16' title='<?php echo $status[$aluno->status];?>'><?php echo $status[$aluno->status];?></td>
		<td><?php echo $aluno->matricula;?></td>
		<td><?php echo $aluno->nome;?></td>
		<td><?php $data = $aluno->anoingresso; echo date("Y/m", strtotime($data));?></td>
		<td id="<?php echo $curso[$aluno->curso];?>"><?php echo strtoupper($curso[$aluno->curso]);?></td>
		<td id="<?php echo $linhaPesquisa[$aluno->area];?>"><?php echo strtoupper($linhaPesquisa[$aluno->area]);?></td>
	</tr>

	<?php

	}		
	?>
      </table>
      </table>
	  <b>Doutorandos que n&#227;o fizeram Qualifica&#231;&#227;o 1, mas est&#227;o matriculados em Estudo Dirigido 2.</b>
	  <table id="demotable1" width="100%">
        <thead>
          <tr>
			<th width="5%"></th>
			<th width="10%"">Status</th>
			<th width="15%">Matr&#237;cula</th>
			<th width="35%">Nome</th>
			<th width="15%">Ingresso</th>
			<th width="15%">Curso</th>
			<th width="5%">Linha de Pesquisa</th>
			</tr>
		</thead>
        <tbody>
		<?php
			$i = 0;
			$alunosRetornados = verDoutorandosNaoQualificadosMatriculadoED2();
			
			foreach ($alunosRetornados as $aluno) {
				$i = $i +1;
				if ($i % 2) {
					echo ("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'  filtermatch='false'>");
				} else {
					echo ("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
				}
		?>

		<td width='16'><input type="radio" name="idAlunoSelec" value="<?php echo $aluno->id;?>"></td>
		<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $statusImg[$aluno->status];?>' width='16' height='16' title='<?php echo $status[$aluno->status];?>'><?php echo $status[$aluno->status];?></td>
		<td><?php echo $aluno->matricula;?></td>
		<td><?php echo $aluno->nome;?></td>
		<td><?php $data = $aluno->anoingresso; echo date("Y/m", strtotime($data));?></td>
		<td id="<?php echo $curso[$aluno->curso];?>"><?php echo strtoupper($curso[$aluno->curso]);?></td>
		<td id="<?php echo $linhaPesquisa[$aluno->area];?>"><?php echo strtoupper($linhaPesquisa[$aluno->area]);?></td>
	</tr>

	<?php

	}		
	?>
      </table>	
<b>Doutorandos que n&#227;o fizeram Qualifica&#231;&#227;o 2, mas tem nota em Estudo Dirigido 2.</b>
	  <table id="demotable1" width="100%">
        <thead>
          <tr>
			<th width="5%"></th>
			<th width="10%"">Status</th>
			<th width="15%">Matr&#237;cula</th>
			<th width="35%">Nome</th>
			<th width="15%">Ingresso</th>
			<th width="15%">Curso</th>
			<th width="5%">Linha de Pesquisa</th>
			</tr>
		</thead>
        <tbody>
		<?php
			$i = 0;
			$alunosRetornados = verDoutorandosNaoQualificados2ComED2();
			
			foreach ($alunosRetornados as $aluno) {
				$i = $i +1;
				if ($i % 2) {
					echo ("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'  filtermatch='false'>");
				} else {
					echo ("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
				}
		?>

		<td width='16'><input type="radio" name="idAlunoSelec" value="<?php echo $aluno->id;?>"></td>
		<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $statusImg[$aluno->status];?>' width='16' height='16' title='<?php echo $status[$aluno->status];?>'><?php echo $status[$aluno->status];?></td>
		<td><?php echo $aluno->matricula;?></td>
		<td><?php echo $aluno->nome;?></td>
		<td><?php $data = $aluno->anoingresso; echo date("Y/m", strtotime($data));?></td>
		<td id="<?php echo $curso[$aluno->curso];?>"><?php echo strtoupper($curso[$aluno->curso]);?></td>
		<td id="<?php echo $linhaPesquisa[$aluno->area];?>"><?php echo strtoupper($linhaPesquisa[$aluno->area]);?></td>
	</tr>

	<?php

	}		
	?>
      </table>		  
     <input name='task' type='hidden' value='alunos'>
     <input name='idAlunoSelec' type='hidden' value='0'>
     <input name='idAluno' type='hidden' value=''>
     </form>
  </div>
  
  <body>

 <?php

}

function verMestrandosQualificadosSemED(){

	$database = & JFactory :: getDBO();
	
	$sql = "SELECT * FROM #__aluno AS A
			WHERE curso = 1 AND status = 0 AND (conceitoQual2 <> '' OR conceitoQual2 IS NOT NULL)
			AND id NOT IN (SELECT idAluno FROM #__disc_matricula AS DM WHERE idDisciplina IN (61,66,19) AND DM.conceito IN ('A', 'B', 'C'))
			ORDER BY nome";

	$database->setQuery($sql);
	$alunos = $database->loadObjectList();
	
	return $alunos;

}

function verMestrandosNaoQualificadosComED(){

	$database = & JFactory :: getDBO();
	
	$sql = "SELECT * FROM #__aluno AS A
			WHERE curso = 1 AND status = 0 AND (conceitoQual2 = '' OR conceitoQual2 IS NULL)
			AND id IN (SELECT idAluno FROM #__disc_matricula AS DM WHERE idDisciplina IN (61,66,19) AND DM.conceito IN ('A', 'B', 'C'))
			ORDER BY nome";

	$database->setQuery($sql);
	$alunos = $database->loadObjectList();
	
	return $alunos;

}

function verDoutorandosNaoQualificadosComED(){

	$database = & JFactory :: getDBO();
	
	$sql = "SELECT * FROM #__aluno AS A
			WHERE curso = 2 AND status = 0 AND (conceitoQual1 = '' OR conceitoQual1 IS NULL)
			AND id IN (SELECT idAluno FROM #__disc_matricula AS DM WHERE DM.idDisciplina IN (61,66,19) AND DM.conceito IN ('A', 'B', 'C'))
			ORDER BY nome";

	$database->setQuery($sql);
	$alunos = $database->loadObjectList();
	
	return $alunos;

}

function verDoutorandosNaoQualificadosMatriculadoED2(){

	$database = & JFactory :: getDBO();
	
	$sql = "SELECT * FROM #__aluno AS A
			WHERE curso = 2 AND status = 0 AND (conceitoQual1 = '' OR conceitoQual1 IS NULL)
			AND id IN (SELECT idAluno FROM #__disc_matricula AS DM WHERE DM.idDisciplina IN (54,67))
			ORDER BY nome";

	$database->setQuery($sql);
	$alunos = $database->loadObjectList();
	
	return $alunos;

}

function verDoutorandosNaoQualificados2ComED2(){

	$database = & JFactory :: getDBO();
	
	$sql = "SELECT * FROM #__aluno AS A
			WHERE curso = 2 AND status = 0 AND (conceitoQual2 = '' OR conceitoQual2 IS NULL)
			AND id IN (SELECT idAluno FROM #__disc_matricula AS DM WHERE DM.idDisciplina IN (54,67) AND  DM.conceito IN ('A', 'B', 'C'))
			ORDER BY nome";

	$database->setQuery($sql);
	$alunos = $database->loadObjectList();
	
	return $alunos;

}
?>




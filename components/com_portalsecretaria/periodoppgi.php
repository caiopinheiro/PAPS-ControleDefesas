<?php // LISTAGEM DE PERIODOS
function listarPeriodos($ano = "", $resultado) {
	$database =& JFactory::getDBO();

	$sql = "SELECT * FROM #__periodos WHERE periodo LIKE '%$ano%' ORDER BY periodo DESC";
	$database->setQuery( $sql );
	$periodos = $database->loadObjectList();

	listarPeriodo($periodos, $ano, $resultado);
}

// CADASTRAR PERIODO
function criarPeriodo() {
	$database =& JFactory::getDBO();

	$periodo = $_POST['periodo'];
	$inicio = $_POST['inicio'];
	$fim = $_POST['fim'];

	$sql = "INSERT INTO #__periodos(`id`,`periodo`,`dataInicio`, `dataFim`, `status`) VALUES (NULL, '$periodo', '$inicio', '$fim', '0')";
	$database->setQuery($sql);
	$database->Query();

	JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
}

function tratamento($idDisciplina,$myDisciplinas) {
	foreach($myDisciplinas as $disciplina) {
		if ($disciplina->id == $idDisciplina) {
			return 1;
		}
	}
	return 0;
}

// SALVAR DISCIPLINAS DO PERIODO
function salvarDiscPeriodo($idPeriodo,$idDisciplina) {
	$database =& JFactory::getDBO();

	$professor = JRequest::getVar('professor');
	$dias = JRequest::getVar('dias');
	$hora = JRequest::getVar('hora');
	$turma = JRequest::getVar('turma');
	$turmaVelha = JRequest::getVar('turmaVelha');
	$sala = JRequest::getVar('sala');
	$curso = JRequest::getVar('curso');
	$editar = JRequest::getVar('editar');

	$tamanho = sizeof($dias);

	if($tamanho){
		$horario = $dias[0];

		for($i = 1; $i < $tamanho; $i++) {
			if($i == $tamanho-1) $horario =  $horario ." e ".$dias[$i];
			else $horario =  $horario .", ".$dias[$i];
		}

		$horas = explode(";", $hora);
		$horario = $horario . " - ".$horas[0]."hs as ".$horas[1]."hs";
	} else {
		$horario = "Sem Horario";
	}
	
	$horario = utf8_encode($horario);
	if($editar) {
		$sql = "UPDATE #__periodo_disc SET idProfessor = $professor, professor = '".verProfessor($professor)."', horario = '".utf8_decode($horario)."', turma = '$turma', curso = $curso, sala = '$sala' WHERE idPeriodo = $idPeriodo AND idDisciplina = $idDisciplina AND turma = '$turmaVelha'";
	} else
		$sql = "INSERT INTO #__periodo_disc(idPeriodo, idDisciplina, idProfessor, professor, horario, turma, curso, sala) VALUES ($idPeriodo,$idDisciplina, $professor, '".verProfessor($professor)."', '".utf8_decode($horario)."' , '$turma', $curso, '$sala')";

	$database->setQuery($sql);
	if($database->Query())
		JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
	else
		JError::raiseWarning( 100, 'ERRO: Opera&#231;&#227;o de Inser&#231;&atilde;o falhou.' );
}

// EXCLUIR DISCIPLINA DO PERÍODO
function removerDiscPeriodo($idPeriodo,$idDisciplina, $turma) {
	$database =& JFactory::getDBO();

	$sql = "DELETE FROM #__periodo_disc WHERE idPeriodo = $idPeriodo AND idDisciplina = $idDisciplina AND turma = '$turma' LIMIT 1";
	$database->setQuery( $sql );
	$sucesso = $database->Query();

	return ($sucesso);
}

////////////////////////////////////////

/*function salvarCadastroPD($idPeriodo,$myDisciplinas){

$database	=& JFactory::getDBO();

$sql = " SELECT * FROM `#__temp_pd` WHERE idPeriodo = $idPeriodo ";
$database->setQuery( $sql );
$temp = $database->loadObjectList();

for($i=0; $i < count($temp); $i++) {
$tempPeriodo = $temp[$i]->idPeriodo;
$tempDisc = $temp[$i]->idDisciplina;
$tempProf = $temp[$i]->professor;
$tempIdProf = $temp[$i]->idProfessor;
$tempHorario = $temp[$i]->horario;
$tempTurma = $temp[$i]->turma;
$tempCurso = $temp[$i]->curso;

$sql = "INSERT INTO #__periodo_disc (idPeriodo,idDisciplina, idProfessor, professor, horario, turma, curso) VALUES ($tempPeriodo,$tempDisc, $tempIdProf, '$tempProf', '$tempHorario', '$tempTurma', $tempCurso)";
$database->setQuery( $sql );
$database->Query();
}

$sql ="DELETE FROM #__temp_pd WHERE #__temp_pd.idPeriodo = $idPeriodo ";
$database->setQuery( $sql );
$database->Query();

$periodo = identificarPeriodoID($idPeriodo);
JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
}    */

////////////////////////////////////////

// IDENTIFICAR DISCIPLINA
function identificarDisciplinaID($idDisciplina) {
	$database =& JFactory::getDBO();

	$sql = "SELECT * FROM #__disciplina WHERE id = '$idDisciplina' ";
	$database->setQuery( $sql );
	$alunos = $database->loadObjectList();

	return ($disciplina[0]);
}

// IDENTIFICAR PERÍODO
function identificarPeriodoID($idPeriodo){
	$database =& JFactory::getDBO();

	$sql = "SELECT * FROM #__periodos WHERE id = '$idPeriodo' ";
	$database->setQuery( $sql );
	$periodo = $database->loadObjectList();

	return ($periodo[0]);
}

// DISCIPLINAS DO ALUNO
function getMyDisciplinas($idAluno) {
	$database =& JFactory::getDBO();

	$sql = "SELECT D.id, D.codigo, D.nomeDisciplina , D.creditos , D.carga FROM `#__disciplina` as D join #__temp as T on D.id = T.idDisciplina WHERE T.idAluno = '$idAluno'";
	$database->setQuery( $sql );
	$myDisciplinas = $database->loadObjectList();

	return ($myDisciplinas);
}

// DISCIPLINAS DO PERÍODO
function getMyDisciplinas_p($periodo){

	$database =& JFactory::getDBO();

	$sql = "SELECT D.id, D.codigo, D.nomeDisciplina , D.creditos , PD.professor, PD.horario, PD.turma, PD.curso, D.carga FROM `#__disciplina` as D join #__temp_pd as PD on D.id = PD.idDisciplina WHERE PD.idPeriodo = '$periodo'  ORDER BY D.codigo";
	$database->setQuery( $sql );
	$myDisciplinas = $database->loadObjectList();

	return ($myDisciplinas);
}

// DISCIPLINAS CADASTRADAS
function disciplinasCadastradas($periodo) {
	$database =& JFactory::getDBO();

	$sql = "SELECT D.id, D.codigo, D.nomeDisciplina , D.creditos , PD.professor, PD.horario, PD.turma, PD.curso, D.carga, PD.sala FROM `#__disciplina` as D join #__periodo_disc as PD on D.id = PD.idDisciplina WHERE PD.idPeriodo = '$periodo' ORDER BY D.codigo";
	$database->setQuery( $sql );
	$myDisciplinas = $database->loadObjectList();

	return ($myDisciplinas);
}

////////////////////////////////////////
function getTabelaDisciplinas($periodo) {
	$database =& JFactory::getDBO();

	$sql = "SELECT D.id, D.codigo, D.nomeDisciplina , D.creditos , PD.professor, PD.horario, PD.turma, PD.curso, D.carga FROM `#__disciplina` as D join #__periodo_disc as PD on D.id = PD.idDisciplina WHERE PD.idPeriodo = '$periodo'";
	$database->setQuery( $sql );
	$disciplinas= $database->loadObjectList();

	return ($disciplinas);
}

function getTabelaDisciplinas_p() {
	$database =& JFactory::getDBO();
	
	$sql = "SELECT * FROM #__disciplina ORDER by nomeDisciplina";
	$database->setQuery( $sql );
	$disciplinas= $database->loadObjectList();

	return ($disciplinas);
}

// IDENTIFICAR PERÍODO
function identificarPeriodo($periodo) {
	$database =& JFactory::getDBO();

	$sql = "SELECT * FROM #__periodos WHERE periodo LIKE '%$periodo%' ";
	$database->setQuery( $sql );
	$teste = $database->loadObjectList();

	if ($teste[0]==NULL) {
		return 0;
	}
	return 1;
}

// ATIVAR PERÍODO
function ativarPeriodo($id) {
	$database =& JFactory::getDBO();

	$sql = "SELECT periodo, status FROM #__periodos WHERE id= '$id'";
	$database->setQuery( $sql );
	$database->Query();
	$periodo = $database->loadObjectList();

	if ($periodo[0]->status == 0){
		$sql = "SELECT * FROM #__periodos WHERE status = 1";
		$database->setQuery( $sql );
		$database->Query();
		$periodoAtivo = $database->loadObjectList();

		if(!$periodoAtivo){
			$sql = "UPDATE #__periodos SET status = '1' WHERE id = $id";
			$database->setQuery( $sql );
			$database->Query();
		} else {
			return(2);
		}
	} else {
		return(1);
	}
	
	return(0);
}

// DESATIVAR PERÍODO
function desativarPeriodo($id){

	$database	=& JFactory::getDBO();

	$sql = "SELECT periodo, status FROM #__periodos WHERE id= $id";
	$database->setQuery( $sql );
	$database->Query();
	$periodo = $database->loadObjectList();

	if ($periodo[0]->status == 1){
		$sql = "UPDATE #__periodos SET status = '2' WHERE id = $id";
		$database->setQuery( $sql );
		$database->Query();
		JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
	}
	else{
		return(3);
	}
	return(0);
}

//////////////////////////////////////////
function mudarMatriculaPeriodo($id, $status){
	$database =& JFactory::getDBO();

	$sql = "SELECT periodo, status, matricula FROM #__periodos WHERE id= $id";
	$database->setQuery( $sql );
	$database->Query();
	$periodo = $database->loadObjectList();

	if ($periodo[0]->status == 2 && $status == 1) {
		return (5);
	} else {
		$sql = "UPDATE #__periodos SET matricula = '$status' WHERE id = $id";
		$database->setQuery( $sql );
		$database->Query();
		JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
		return (0);
	}
}

//////////////////////////////////////////
function mudarLancarNotasPeriodo($id, $status){
	$database =& JFactory::getDBO();

	$sql = "SELECT periodo, status, notas FROM #__periodos WHERE id= $id";
	$database->setQuery( $sql );
	$database->Query();
	$periodo = $database->loadObjectList();

	if (($periodo[0]->status == 2 || $periodo[0]->status == 0) && $status == 1)
		return (6);

	$sql = "UPDATE #__periodos SET notas = '$status' WHERE id = $id";
	$database->setQuery( $sql );
	$database->Query();
	JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
	return (0);
}

// EXCLUIR PERÍODO
function excluirPeriodo($id) {
	$database =& JFactory::getDBO();

	$sql = "SELECT periodo, status FROM #__periodos WHERE id= '$id'";
	$database->setQuery( $sql );
	$database->Query();
	$periodo = $database->loadObjectList();

	if ($periodo[0]->status == 0) {
		$sql ="DELETE FROM #__periodo_disc WHERE #__periodo_disc.idPeriodo = $id LIMIT 1";
		$database->setQuery( $sql );
		$database->Query();

		$sql ="DELETE FROM #__periodos WHERE #__periodos.id = $id LIMIT 1";
		$database->setQuery( $sql );
		$database->Query();
		JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
	} else {
		return(4);
	}

	return(0);
}

// SALVAR DISCIPLINA
function salvarDisciplina(){
	$database =& JFactory::getDBO();

	$idDisc = $_POST['idDisc'];
	$codigo = $_POST['codigo'];
	$nome = $_POST['nome'];
	$creditos = $_POST['creditos'];
	$carga = $_POST['carga'];

	if($idDisc){
		$sql = "UPDATE #__disciplina SET codigo='$codigo', nomeDisciplina='$nome', creditos='$creditos', carga=$carga WHERE id=$idDisc";
	} else {
		$sql = "INSERT INTO #__disciplina(codigo, nomeDisciplina, creditos, carga) VALUES ('$codigo', '$nome', '$creditos', $carga)";
	}

	$database->setQuery($sql);
	$database->Query();
	JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
}

// EXCLUIR DISCIPLINA
function excluirDisciplina($idDisc){
	$database =& JFactory::getDBO();

	$sql = "DELETE from #__disciplina WHERE id=$idDisc";
	$database->setQuery($sql);
	$database->Query();
	JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
}

// IMPRIMIR PAGELA
function imprimirPagela($idPeriodo, $idDisciplina, $turma, $tipo){
	$database	=& JFactory::getDBO();

	$sql = "SELECT idPeriodo, idDisciplina, professor, horario, turma, curso, codigo, nomeDisciplina FROM #__periodo_disc as P join #__disciplina as D ON P.idDisciplina = D.id WHERE P.idPeriodo = $idPeriodo AND P.idDisciplina = $idDisciplina AND turma = '$turma '";
	$database->setQuery( $sql );
	$turmas = $database->loadObjectList();

	$sql = "SELECT idAluno, nome, matricula, orientador, area, curso, bolsista, agencia, anoingresso, frequencia, conceito FROM #__disc_matricula as M join #__aluno as A ON M.idAluno = A.id WHERE M.idPeriodo = $idPeriodo AND M.idDisciplina = $idDisciplina AND turma = '$turma' AND curso = $tipo ORDER BY nome";
	$database->setQuery($sql);
	$alunos = $database->loadObjectList();

	$sql = "SELECT periodo FROM #__periodos WHERE id = $idPeriodo";
	$database->setQuery($sql);
	$periodos = $database->loadObjectList();

	$curso = array (1 => 'PPG-INF-M   Mestrado em Inform�tica',2 => 'PPG-INF-D   Doutorado em Inform�tica');

	$chave = md5($sql.date("l jS \of F Y h:i:s A"));
	$boletim = "components/com_portalsecretaria/reports/$chave.pdf";
	$arq = fopen($boletim, 'w') or die('CREATE ERROR');

	$pdf = new Cezpdf('a4','landscape');
	$pdf->selectFont('pdf-php/fonts/Helvetica.afm');
	$optionsText = array('justification'=>'center', 'spacing'=>1.5);
	$dados = array('justification'=>'justify', 'spacing'=>1.0);
	$optionsTable = array('fontSize'=>8, 'titleFontSize'=>8, 'xPos'=>'center', 'width'=>800, 'cols'=>array('Ordem'=>array('width'=>35, 'justification'=>'center'),'MatrÃ­cula'=>array('width'=>70, 'justification'=>'center'), 'Nome do Aluno'=>array('width'=>170), 'Total de Faltas'=>array('width'=>50, 'justification'=>'center')));

	$pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 740, 470, 80);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 470, 100);
	$pdf->ezText('<b>PODER EXECUTIVO</b>',12,$optionsText);
	$pdf->ezText('<b>MINIST�RIO DA EDUCA��O</b>',10,$optionsText);
	$pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$optionsText);
	$pdf->ezText('<b>INSTITUTO DE COMPUTA��O</b>',10,$optionsText);
	$pdf->ezText('',11,$optionsText);
	$pdf->ezText('<b>PROGRAMA DE P�S-GRADUA��O EM INFORM�TICA</b>',10,$optionsText);
	$pdf->addText(495,715,8,"<b>Data:</b> ".date("d/m/Y"),0,0);
	$pdf->addText(495,725,8,"<b>Hora:</b> ".date("H:i"),0,0);
	$pdf->setLineStyle(2);
	$pdf->line(20, 450, 820, 450);
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText('<b>BOLETIM DE FREQU�NCIA</b>',12,$optionsText);

	$pdf->ezText('');
	$pdf->ezText('');
	$pdf->ezText("<b>Curso:</b> ".$curso[$turmas[0]->curso]."                                           <b>Semestre:</b> ".$periodos[0]->periodo."                                           <b>Turma: </b> ".$turmas[0]->turma,10,$dados);
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText("<b>Disciplina:</b> ". utf8_decode($turmas[0]->codigo." - ". $turmas[0]->nomeDisciplina)."                                          <b>Professor:</b> ". utf8_decode($turmas[0]->professor),10,$dados);
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText('');

	$cont = 1;
	$cols = array('Ordem'=>'Ordem', 'Matr��cula'=>'Matr��cula', 'Nome do Aluno'=>'Nome do Aluno','Dia 1'=>'     ','Dia 2'=>'','Dia 3'=>'','Dia 4'=>'','Dia 5'=>'','Dia 6'=>'','Dia 7'=>'','Dia 8'=>'','Dia 9'=>'','Dia 10'=>'','Dia 11'=>'','Dia 12'=>'','Dia 13'=>'','Dia 14'=>'','Dia 15'=>'','Dia 16'=>'','Dia 17'=>'','Dia 18'=>'','Dia 19'=>'','Dia 20'=>'','Total de Faltas'=>'Total de Faltas' );
	foreach( $alunos as $aluno )
	{
		$listaAlunos[] = array('Ordem'=>$cont, 'Matré­cula'=>$aluno->matricula, 'Nome do Aluno'=>utf8_decode($aluno->nome),'Dia 1'=>'','Dia 2'=>'','Dia 3'=>'','Dia 4'=>'','Dia 5'=>'','Dia 6'=>'','Dia 7'=>'','Dia 8'=>'','Dia 9'=>'','Dia 10'=>'','Dia 11'=>'','Dia 12'=>'','Dia 13'=>'','Dia 14'=>'','Dia 15'=>'','Dia 16'=>'','Dia 17'=>'','Dia 18'=>'','Dia 19'=>'','Dia 20'=>'','Total de Faltas'=>'' );
		$cont++;
	}

	$pdf->ezTable($listaAlunos,$cols,'',$optionsTable);

	$pdf->ezText('');  //Para quebra de linha
	$pdf->line(20, 55, 820, 55);
	$pdf->addText(190,40,8,'Av. Rodrigo Ot�vio, 6.200 - Campus Universit�rio Senador Arthur Virg��lio Filho - CEP 69077-000 - Manaus, AM, Brasil',0,0);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 250, 30, 8, 8);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 339, 30, 8, 8);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 493, 30, 8, 8);
	$pdf->addText(260,30,8,'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br',0,0);

	$pdfcode = $pdf->output();
	fwrite($arq,$pdfcode);
	fclose($arq);

	header("Location: ".$boletim);
}

//  IMPRIMIR BOLETIM
function imprimirBoletim($idPeriodo, $idDisciplina, $turma, $tipo) {
	$database	=& JFactory::getDBO();

	$sql = "SELECT idPeriodo, idDisciplina, professor, horario, turma, curso, codigo, nomeDisciplina, creditos, carga FROM #__periodo_disc as P join #__disciplina as D ON P.idDisciplina = D.id WHERE P.idPeriodo = $idPeriodo AND P.idDisciplina = $idDisciplina AND turma = '$turma '";
	$database->setQuery( $sql );
	$turmas = $database->loadObjectList();

	$sql = "SELECT idAluno, nome, matricula, orientador, area, curso, bolsista, agencia, anoingresso, frequencia, conceito FROM #__disc_matricula as M join #__aluno as A ON M.idAluno = A.id WHERE M.idPeriodo = $idPeriodo AND M.idDisciplina = $idDisciplina AND turma = '$turma' AND curso = $tipo ORDER BY nome";
	$database->setQuery($sql);
	$alunos = $database->loadObjectList();

	$sql = "SELECT periodo FROM #__periodos WHERE id = $idPeriodo";
	$database->setQuery( $sql );
		$periodos = $database->loadObjectList();

	$mes = array ("01" => "Janeiro","02" => "Fevereiro","03" => "Mar&#231;o","04" => "Abril","05" => "Maio","06" => "Junho","07" => "Julho","08" => "Agosto","09" => "Setembro","10" => "Outubro","11" => "Novembro","12" => "Dezembro");
	$curso = array (1 => 'PPG-INF-M   Mestrado em Inform�tica',2 => 'PPG-INF-D   Doutorado em Inform�tica');
	$tipoCurso = array (1 => ' - ALUNOS REGULARES',2 => ' - ALUNOS REGULARES', 3 => ' - ALUNOS ESPECIAIS');

	$chave = md5($sql.date("l jS \of F Y h:i:s A"));
	$boletim = "components/com_portalsecretaria/reports/$chave.pdf";
	$arq = fopen($boletim, 'w') or die('CREATE ERROR');

	$pdf = new Cezpdf();
	$pdf->selectFont('pdf-php/fonts/Helvetica.afm');
	$optionsText = array('justification'=>'center', 'spacing'=>1.5);
	$dados = array('justification'=>'justify', 'spacing'=>1.0);
	$optionsTable = array('fontSize'=>8, 'titleFontSize'=>8, 'xPos'=>'center', 'width'=>500, 'cols'=>array('Ordem'=>array('width'=>35, 'justification'=>'center'),'Matr��cula'=>array('width'=>70, 'justification'=>'center'), 'Nome do Aluno'=>array('width'=>275), 'Conceito'=>array('width'=>50, 'justification'=>'center'), 'Frequ�ncia(%)'=>array('width'=>70, 'justification'=>'center')));

	$pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 490, 720, 75);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 720, 100);
	$pdf->ezText('<b>PODER EXECUTIVO</b>',12,$optionsText);
	$pdf->ezText('<b>MINIST�RIO DA EDUCA��O</b>',10,$optionsText);
	$pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$optionsText);
	$pdf->ezText('<b>INSTITUTO DE COMPUTA��O</b>',10,$optionsText);
	$pdf->ezText('',11,$optionsText);
	$pdf->ezText('<b>PROGRAMA DE P�S-GRADUA��O EM INFORM�TICA</b>',10,$optionsText);
	$pdf->setLineStyle(1);
	$pdf->line(20, 690, 580, 690);
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText('<b>BOLETIM DE NOTAS'.$tipoCurso[$tipo].'</b>',12,$optionsText);
	$pdf->ezText('');
	$pdf->ezText('');
	$pdf->ezText("<b>Curso:</b> ".$curso[$turmas[0]->curso],10,$dados);
	$pdf->addText(450,630,10,"<b>Semestre:</b> ".$periodos[0]->periodo,0,0);
	$pdf->ezText('');
	$pdf->ezText("<b>Disciplina:</b> ". utf8_decode($turmas[0]->codigo." - ". $turmas[0]->nomeDisciplina),10,$dados);
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText("<b>Cr�ditos:</b> ". $turmas[0]->creditos,10,$dados);
	$pdf->addText(450,586,10,"<b>Carga Hor�ria: </b> ".$turmas[0]->carga,0,0);
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText("<b>Professor:</b> ". utf8_decode($turmas[0]->professor),10,$dados);
	$pdf->addText(450,562,10,"<b>Turma: </b> ".$turmas[0]->turma,0,0);
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText('');

	$cont = 1;
	foreach( $alunos as $aluno ) {
		$listaAlunos[] = array('Ordem'=>$cont, 'Matr��cula'=>$aluno->matricula, 'Nome do Aluno'=>utf8_decode($aluno->nome),'Conceito'=>$aluno->conceito,'Frequ�ncia(%)'=>number_format($aluno->frequencia,2));
		$cont++;
	}

	$pdf->ezTable($listaAlunos,$cols,'',$optionsTable);

	$tag.$pdf->ezText('');  //Para quebra de linha
	$pdf->addText(150,90,8,"<b>Manaus, ".date("d")." de ".$mes[date("m")]." de ".date("Y")."</b>",0,0);
	$pdf->line(20, 55, 580, 55);

	$pdf->addText(415,90,8,'Assinatura do Professor',0,0);
	$pdf->line(350, 100, 560, 100);
	$pdf->line(20, 55, 580, 55);

	$pdf->addText(80,40,8,'Av. Rodrigo Ot�vio, 6.200 - Campus Universit�rio Senador Arthur Virg�lio Filho - CEP 69077-000� Manaus, AM, Brasil',0,0);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 140, 30, 8, 8);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 229, 30, 8, 8);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 383, 30, 8, 8);
	$pdf->addText(150,30,8,'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br',0,0);

	$pdfcode = $pdf->output();
	fwrite($arq,$pdfcode);
	fclose($arq);

	header("Location: ".$boletim);
}

// SALVAR NOTA
function salvarNota($idPeriodo, $idDisciplina, $turma, $alunos, $frequencias, $conceitos) {
	$database	=& JFactory::getDBO();

	$cont =0;
	foreach($alunos as $aluno) {
		$sql = "UPDATE #__disc_matricula SET frequencia = ".$frequencias[$cont].", conceito='".$conceitos[$cont]."' WHERE idAluno = $aluno AND idDisciplina = $idDisciplina AND turma = '$turma'";
		$database->setQuery( $sql );
		$database->Query();

		$cont++;
	}
	JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
}

// LISTAGEM DE MATRICULAS
function listarMatriculas($idPeriodo, $anoIngresso, $curso) {
	$Itemid = JRequest::getInt('Itemid', 0);
	$database =& JFactory::getDBO(); ?>

    <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
    
	<script language="JavaScript">
        function ConfirmarRegistro(formMatricula, idAluno) {
            var confirmar = confirm('Confirmar Registro?');
            if(confirmar == true) {
                formMatricula.task.value='registrarMatricula';
                formMatricula.idAluno.value=idAluno;
                formMatricula.idPeriodo.value=formMatricula.periodo.value;
                formMatricula.submit();
            }
        }
    </script>
    
    <!-- EFEITO TOGGLE [MATRÃCULAS PENDENTES] -->
    <link href="components/com_portalsecretaria/faq/simpleFAQ.css" rel="stylesheet" type="text/css" />
	<script src="components/com_portalsecretaria/faq/jquery-1.7.2.js" language="javascript"></script>
    <script src="components/com_portalsecretaria/faq/jquery.simpleFAQ-0.7.min.js" type="text/javascript"></script>
    <script>
        $(document).ready(function(){
             $('#faqList').simpleFAQ();
        });
    </script>
    
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
    <script type="text/javascript">
        $(function() {
            $("#tablesorter-imasters").tablesorter();
        });
    </script>
    
   	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />
    
	<form name="formMatricula" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" method="post">
    
    	<!-- BARRA DE OPÇÕES -->
        <div id="toolbar-box">
            <div class="m">
                <div class="toolbar-list" id="toolbar">
                    <div class="cpanel2">
                        <div class="icon" id="toolbar-back">
                            <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
                            	<span class="icon-32-back"></span> <?php echo JText::_( 'Voltar' ); ?>
                            </a>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
                
                <div class="pagetitle icon-48-contact">
                    <h2>Matr&#237;culas Realizadas</h2>
                </div>
            </div>
        </div>
    
    	<!-- FILTRO -->
		<legend>Filtros para consulta</legend>
        
        <p>            
		<table>
			<tr>
				<td>
				Per&#237;odo: <select name="periodo" style="width:100px;">
                
				<?php
				$database->setQuery("SELECT * from #__periodos ORDER BY periodo DESC");
			    $periodos = $database->loadObjectList();

				foreach($periodos as $periodo){ ?>
					<option value="<?php echo $periodo->id;?>"
					<?php
						if($idPeriodo == NULL){
    				        if($periodo->status == 1){
               					echo 'SELECTED';
               					$idPeriodo = $periodo->id;
				            }
         				} else {
				            if($idPeriodo == $periodo->id) echo 'SELECTED';
         				}
         			?>>
					<?php echo $periodo->periodo;?>
					</option>
                    
				<?php }
				
				 $sqlEstendido = "";
				 $sqlEstendido2 = "";
							
				 if($anoIngresso) $sqlEstendido = "AND anoingresso LIKE '%$anoIngresso%'";
				 if($curso > 0) $sqlEstendido2 = "AND curso = $curso";
		
				 $sql = "SELECT A.id, matricula, nome, anoingresso, semestre, curso, regime, area, idPeriodo, M.status FROM `#__matricula` as M join #__aluno as A ON M.idAluno = A.id WHERE M.idPeriodo = $idPeriodo ".$sqlEstendido." ".$sqlEstendido2." ORDER BY nome";
				 $database->setQuery( $sql );
				 $alunos = $database->loadObjectList();
		
				 ?>				
				 </select>
            </td>			
			<td>Curso:
            	<select name="curso" style="width:150px;">
					<option value="0" <?php if($curso == 0) echo 'SELECTED';?>>Todos</option>
					<option value="1" <?php if($curso == 1) echo 'SELECTED';?>>Mestrado</option>
					<option value="2" <?php if($curso == 2) echo 'SELECTED';?>>Doutorado</option>
					<option value="3" <?php if($curso == 3) echo 'SELECTED';?>>Especial</option>
				</select>
			</td>
			<td>Ano de ingresso: 
            	<input type="text" id="anoingresso" name="anoingresso" value="<?php echo $anoIngresso; ?>" style="width:100px;" />
			</td>
            <td width="13%"></td>
			<td><div align="right">
            	<button type="submit" value="Buscar" class="btn btn-primary" style="margin-top:-8px;">
            		<i class="icone-search icone-white"></i> Consultar
                </button></div>
            </td>
            </tr>
		</table> 
        </p>     
    
    <!-- LISTAGEM DOS NÃO MATRICULADOS -->
    <?php
	$sqlMatr = "SELECT a.matricula, a.nome, a.anoingresso, a.curso, a.area FROM j17_aluno a WHERE a.status = 0
				AND a.id NOT IN ( SELECT m.idAluno
								  FROM j17_matricula AS m
								  WHERE m.idPeriodo = $idPeriodo )
				AND ( YEAR( a.anoingresso ) <> 2014 )";
	$database->setQuery($sqlMatr);
	$resultadoMatr = $database->loadObjectList();
	
	$curso = array (1 => "mestrado",2 => "doutorado",3 => "especial");
	$linhaPesquisa = array (1 => "bd_ri",2 => "se_es",3 => "ia",4 => "visao",5 => "redes",6 => "otimizacao"); ?>

	<div id="faqList">
	<li>
		<div class='question' id="q1">
			Alunos com matricula pendente: <?php echo count($resultadoMatr); ?>
		</div>
		
		<?php if ($resultadoMatr) {
		foreach ($resultadoMatr as $fora) { ?>
			<div class='answer' id="a1">
				<table class="table table-striped">
					<tbody>
					<tr>
						<td></td>
						<td width="15%"><?php echo $fora->matricula; ?></td>
						<td width="44%"><?php echo $fora->nome; ?></td>
						<td><?php echo date("m/Y", strtotime($fora->anoingresso)); ?></td>
						<td><img border='0'
							src='components/com_portalsecretaria/images/<?php echo $curso[$fora->curso];?>.gif'
							title='<?php echo $curso[$fora->curso];?>'>
						</td>
						<td><img border='0'
							src='components/com_portalsecretaria/images/<?php echo $linhaPesquisa[$fora->area];?>.gif'
							title='<?php echo verLinhaPesquisa($fora->area, 2);?>'>
						</td>
					</tr>
					</tbody>
				</table>                    
			</div>                
		<?php } } ?>
	</li>
	</div>
    
	<table class="table table-striped" id="tablesorter-imasters">
		<thead class="head-one">
			<tr>
				<th></th>
				<th></th>
				<th>Matr&#237;cula</th>
				<th>Nome</th>
				<th>Ingresso</th>
				<th>Curso</th>
				<th>Linha</th>
			</tr>
		</thead>
        
		<tbody>
			<?php
			$curso = array (1 => "Mestrado",2 => "Doutorado",3 => "Especial");
			$curso = array (1 => "mestrado",2 => "doutorado",3 => "especial");
			$linhaPesquisa = array (1 => "bd_ri",2 => "se_es",3 => "ia",4 => "visao",5 => "redes",6 => "otimizacao");

			if($alunos){
				foreach($alunos as $aluno) { ?>
                
            	<tr>
					<td width='16'>
                    <div align="center">
					<a href="index.php?option=com_portalsecretaria&task=imprimirMatricula&idAluno=<?php echo $aluno->id;?>&idPeriodo=<?php echo $aluno->idPeriodo;?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=820,height=680');return false;"><img border='0' src='components/com_portalsecretaria/images/b_view.png' width='16' height='16' title='Comprovante de Matricula'> </a> </a>
					</div>
            		</td>
					<td><?php if($aluno->status == 0) { ?> 
                    	<a href="javascript:ConfirmarRegistro(document.formMatricula,<?php echo $aluno->id;?>)">
                        	<img border='0' src='components/com_portalsecretaria/images/corrente.png' width='12' height='12' title='Registrar Matricula'>
                        </a>
						<?php } else { ?>
                        	<img border='0' src='components/com_portalsecretaria/images/aprovar.png' width='12' height='12' title='Matricula Registrada'>
						<?php } ?>
					</td>
					<td><?php echo $aluno->matricula;?></td>
					<td><?php echo $aluno->nome;?></td>
					<td><?php echo date("m/Y", strtotime($aluno->anoingresso)); ?></td>
					<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $curso[$aluno->curso];?>.gif' title='<?php echo $curso[$aluno->curso];?>'>
					</td>
					<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $linhaPesquisa[$aluno->area];?>.gif' title='<?php echo verLinhaPesquisa($aluno->area, 2);?>'>
					</td>
				</tr>
			<?php }
			}
			?>
		</tbody>
	</table>
    
	<br />
    <span class="label label-inverse">Total de matrículas: <?php echo sizeof($alunos);?></span>
    
	<input name='task' type='hidden' value='listarmatriculas' />
    <input name='idAluno' type='hidden' value='' />
    <input name='idPeriodo' type='hidden' value='' />
</form>

<?php }


// VISUALIZAR DISCIPLINAS DO PERIODO
function formularioDiscPeriodo($periodo,$cadastradas = NULL) {
	$Itemid = JRequest::getInt('Itemid', 0);
	$database =& JFactory::getDBO();

	$status = array (0 => "Nao iniciado",1 => "Ativo",2 => "Finalizado"); ?>

	<script language="JavaScript">
        function adicionar(form){
            form.task.value = 'addDiscPeriodo';
            form.submit();
        }
        
        function editar(form) {
            var idSelecionado = 0;
            for(i = 0;i < form.idDiscSelec.length;i++)
                if(form.idDiscSelec[i].checked) idSelecionado = form.idDiscSelec[i].value;
            
            if(idSelecionado != 0){
            
               form.task.value = 'editarDiscPeriodo';
               form.idDisc.value = idSelecionado;
               form.submit();
            }
            else{
               alert('Ao menos 1 disciplina deve ser selecionada para edi\xE7\xE3o.')
            }
        }
    
        function excluir(form) {
           var idSelecionado = 0;
           for(i = 0;i < form.idDiscSelec.length;i++)
                if(form.idDiscSelec[i].checked) idSelecionado = form.idDiscSelec[i].value;
        
           if(idSelecionado != 0){
             var confirmar = confirm('Confirmar Exclus\xE3o?');
             if(confirmar == true){
        
               form.task.value = 'rmDiscPeriodo';
               form.idDisc.value = idSelecionado;
               form.submit();
             }
           }
           else{
               alert('Ao menos 1 disciplina deve ser selecionada para exclus\xE3o.')
           }
        }
    
        function imprimir(ItemId, periodo){
            window.open("index.php?option=com_portalsecretaria&Itemid="+ItemId+"&task=oferta&idPeriodo="+periodo,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
        }
    </script>

	<form method="post" name="formPeriodoDisc" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateFormPeriodoDisc(this)">
    	
        <!-- BARRA DE OPÇÕES -->
		<div id="toolbar-box"><div class="m">
			<div class="toolbar-list" id="toolbar">
				<div class="cpanel2">
					<?php if($periodo->status < 2) { ?>
					<div class="icon" id="toolbar-new">
						<a href="javascript:adicionar(document.formPeriodoDisc)"> 
                        <span class="icon-32-new"></span> <?php echo JText::_( 'Adicionar' ); ?></a>
					</div>
					<div class="icon" id="toolbar-edit">
						<a href="javascript:editar(document.formPeriodoDisc)">
                         <span class="icon-32-edit"></span> <?php echo JText::_( 'Editar' ); ?></a>
					</div>
					<div class="icon" id="toolbar-delete">
						<a href="javascript:excluir(document.formPeriodoDisc)"> 
                        <span class="icon-32-delete"></span> <?php echo JText::_( 'Excluir' ); ?></a>
					</div>
					<?php } ?>
                    
					<div class="icon" id="toolbar-print">
						<a href="javascript:imprimir(<?php echo $Itemid;?>, <?php echo $periodo->id;?>)">
						<span class="icon-32-print"></span> <?php echo JText::_( 'Imprimir<br>Oferta' ); ?></a>
					</div>
					<div class="icon" id="toolbar-cancel">
						<a href="index.php?option=com_portalsecretaria&task=periodo&Itemid<?php echo $Itemid; ?>">
                        <span class="icon-32-back"></span> <?php echo JText::_( 'Voltar' ); ?></a>
					</div>
                    </div>
                    <div class="clr"></div>
                </div>
                
                <div class="pagetitle icon-48-levels">
                    <h2>Disciplinas</h2>
                </div>
            </div>
        </div>

        <table width="100%" cellpadding="6">
            <tr style="background-color:#7196d8; color:#FFF;">
                <td colspan="4"><b>Dados do Per&#237;odo</b></td>
            </tr>
            <tr style="background-color:#eaeaea; border-bottom:1px solid #CCC; font-weight:bold;">
                <td width="30%">Per&#237;odo</td>
                <td width="25%">Data de In&#237;cio</td>
                <td width="25%">Data de T&#233;rmino</td>
                <td width="20%">Status</td>
            </tr>
            <tr>
                <td><?php echo $periodo->periodo;?></td>
                <td><?php echo $periodo->dataInicio;?></td>
                <td><?php echo $periodo->dataFim; ?></td>
                <td><?php echo $status[$periodo->status]; ?></td>
            </tr>
        </table>
    
		<br />
    
        <table width="100%" cellpadding="6" class="table table-striped">
            <tr style="background-color:#7196d8; color:#FFF;">
                <td colspan="9"><b>Disciplinas Cadastradas no Per&#237;odo</b></td>
            </tr>
            <tr style="background-color:#eaeaea; border-bottom:1px solid #CCC; font-weight:bold;">
                <td align="center" width="2%"></td>
                <td align="center" width="5%">C&#243;digo</td>
                <td align="center" width="25%">Disciplina</td>
                <td align="center" width="5%">Turma</td>
                <td align="center" width="7%">Curso</td>
                <td align="center" width="20%">Professor</td>
                <td align="center" width="23%">Hor&#225;rio</td>
                <td align="center" width="23%">Sala</td>
            </tr>
    
            <?php
            $curso = array (1 => "mestrado",2 => "doutorado");
    
            foreach($cadastradas as $cadastrada) {    
            	$matriculado++; ?>
            <tr>
                <td align="center">
                <?php if($periodo->status < 2) { ?>
	                <input type="radio" name="idDiscSelec" value="<?php echo $cadastrada->id.';'.$cadastrada->turma.';'.$periodo->id;?>">
                <?php } ?>
                </td>
                <td align="center"><?php echo $cadastrada->codigo;?></td>
                <td><?php echo $cadastrada->nomeDisciplina;?></td>
                <td align="center"><?php echo $cadastrada->turma;?></td>
                <td><img border='0' src='components/com_portalsecretaria/images/<?php echo $curso[$cadastrada->curso];?>.gif' title='<?php echo $curso[$cadastrada->curso];?>'></td>
                <td align="center"><?php echo $cadastrada->professor;?></td>
                <td align="center"><?php echo $cadastrada->horario;?></td>
                <td align="center"><?php echo $cadastrada->sala;?></td>
            </tr>
            <?php } ?>
        </table>

        <input name='matriculado' type='hidden' value='<?php echo $matriculado;?>'> 
        <input name='idPeriodo' type='hidden' value='<?php echo $periodo->id;?>'> 
        <input name='idDiscSelec' type='hidden' value='0'> 
        <input name='idDisc' type='hidden' value=''> 
        <input name='task' type='hidden' value='salvarCadastroPD'> 
        <input name='rmTurma' type='hidden' value='0'> <input name='rmDisc' type='hidden' value='200'>
    </form>

<?php }


// CADASTRAR DISCIPLINA : TELA
function addDiscPeriodo($periodo, $disciplinas, $idDisciplina, $turma, $editar) {
	$Itemid = JRequest::getInt('Itemid', 0);
	$database =& JFactory::getDBO();

	$database->setQuery("SELECT * from #__periodo_disc WHERE idPeriodo = $periodo->id AND idDisciplina = $idDisciplina AND turma = '$turma'");
	$disciplinasSelecionadas = $database->loadObjectList();
	$disciplinaSelecionada = $disciplinasSelecionadas[0];

	$status = array (0 => "Nao iniciado",1 => "Ativo",2 => "Finalizado"); ?>
    
	<link rel="stylesheet" href="components/com_portalsecretaria/jquery/stylesheets/jslider.css" type="text/css" />
	<link rel="stylesheet" href="components/com_portalsecretaria/jquery/stylesheets/jslider.blue.css" type="text/css" />

	<script type="text/javascript" src="components/com_portalsecretaria/jquery/javascripts/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="components/com_portalsecretaria/jquery/javascripts/jquery.dependClass.js"></script>
	<script type="text/javascript" src="components/com_portalsecretaria/jquery/javascripts/jquery.slider-min.js"></script>

	<script language="JavaScript">
		function radio_button_checker(elem) {
		  var radio_choice = false;
		
		  for (counter = 0; counter < elem.length; counter++) {
			if (elem[counter].checked)
			radio_choice = true;
		   }
		
		  return (radio_choice);
		}
		
		function ValidateFormPeriodoDisc(formPeriodoDisc) {
		   if(formPeriodoDisc.matriculado.value=='') {
			  alert('Ao menos uma disciplinas deve ser selecionada para o periodo.')
			  formPeriodoDisc.matriculado.focus();
			  return false;
		   }
		   return true;
		 }
		
		function VerCheckBox(formPeriodoDisc) {
			for (var i = 0 ; i < formPeriodoDisc['dias[]'].length ; i++){
				if (formPeriodoDisc['dias[]'][i].checked){
				   return 1;
				}
			}
			return 0;
		}
		
		function VerificaProfessor(formPeriodoDisc) {
		   if(formPeriodoDisc.novaDisciplina.value==0) {
			  alert('Deve ser escolhida uma disciplina.')
			  formPeriodoDisc.novaDisciplina.focus();
			  return false;
		   } else if(formPeriodoDisc.turma.value=='') {
			  alert('A turma deve ser preenchida.')
			  formPeriodoDisc.turma.focus();
			  return false;
		   } else if(formPeriodoDisc.professor.value=='') {
			  alert('O professor deve ser preenchido.')
			  formPeriodoDisc.professor.focus();
			  return false;
		   } else if(formPeriodoDisc.com_horario.checked && !VerCheckBox(formPeriodoDisc)) {
			  alert('Disciplina com Hor\xE1rio: Ao menos 1 dia deve ser escolhido!')
			  formPeriodoDisc.com_horario.focus();
			  return false;
		   } else if (!radio_button_checker(formPeriodoDisc.curso)) {
			 alert('O campo Curso deve ser preenchido.')
			 formPeriodoDisc.curso[0].focus();
			 return (false);
		   } else {
			  formPeriodoDisc.task.value='addDPeriodo';
			  formPeriodoDisc.submit();
		   }
		}
		 
		function voltar(form) {
			form.task.value = 'cadastroPD';
			form.submit();
		}
    </script>
    
    <script>
		$(document).ready(function(){
			$("#com_horario").click(function(evento){
				if ($("#com_horario").attr("checked")){
					$("#formulariomayores").css("display", "block");
				}else{
					$("#formulariomayores").css("display", "none");
				}
			});
		});
    </script>
    
    <style>
		/* CHECKBOX */
		label {
			display: inline-block;
			cursor: pointer;
			position: relative;
			padding-left: 20px;
			margin-right: 15px;
			margin-left: -10px;
			font-size: 12px;
		}
		input[type=checkbox] {  
			display: none;  
		}
		label:before {  
			content: "";  
			display: inline-block;  
			
			width: 16px;  
			height: 16px;  
			
			margin-right: 10px;  
			position: absolute;  
			left: 0;
			bottom: 1px;  
			background-color: #aaa;  
			box-shadow: inset 0px 2px 3px 0px rgba(0, 0, 0, .3), 0px 1px 0px 0px rgba(255, 255, 255, .8);  
		}
		.checkbox label:before {  
			border-radius: 3px;  
		}
		input[type=checkbox]:checked + label:before {  
			content: "\2713";  
			color: #f3f3f3;
			font-size: 15px;
			text-align: center;  
			line-height: 18px;  
		} 
		
		/* RADIO */
		input[type=radio] {  
			display: none;  
		}
		.radio label:before {  
			border-radius: 8px;  
		}
		input[type=radio]:checked + label:before {  
			content: "\2022";  
			color: #f3f3f3;
			font-size: 30px;
			text-align: center;  
			line-height: 18px;  
		}
	</style>

	<form method="post" name="formPeriodoDisc" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateFormPeriodoDisc(this)">
    
    	<!-- BARRA DE OPÇÕES -->
		<div id="toolbar-box"><div class="m">
			<div class="toolbar-list" id="toolbar">
				<div class="cpanel2">
					<div class="icon" id="toolbar-save">
						<a href="javascript:if(VerificaProfessor(document.formPeriodoDisc))document.formPeriodoDisc.submit()">
							<span class="icon-32-save"></span> <?php echo JText::_( 'Salvar' ); ?>
						</a>
					</div>
                    
					<div class="icon" id="toolbar-cancel">
						<a href="javascript:voltar(document.formPeriodoDisc)"> 
	                        <span class="icon-32-back"></span> <?php echo JText::_( 'Voltar' ); ?>
						</a>
					</div>
				</div>
				<div class="clr"></div>
			</div>
            
			<div class="pagetitle icon-48-levels">
				<h2>Adicionar Disciplina</h2>
			</div>
            </div>
        </div>


	<table width="100%" cellpadding="6">
		<tr style="background-color:#7196d8; color:#FFF; font-weight:bold;">
			<td colspan="4">Dados do Per&#237;odo</td>
		</tr>
		<tr style="background-color:#eaeaea; border-bottom:1px solid #CCC; font-weight:bold;">
			<td width="30%">Per&#237;odo </td>
			<td width="25%">Data de In&#237;cio	</td>
			<td width="25%">Data de T&#233;rmino </td>
			<td width="20%">Status </td>
		</tr>
		<tr>
			<td><?php echo $periodo->periodo;?></td>
			<td><?php echo $periodo->dataInicio;?></td>
			<td><?php echo $periodo->dataFim; ?></td>
			<td><?php echo $status[$periodo->status]; ?></td>
		</tr>
	</table>
	<br />

	<table width="100%" cellpadding="6">
		<tr style="background-color:#7196d8; color:#FFF; font-weight:bold;">
			<td colspan="4">Informações da Disciplina</td>
		</tr>
		<tr>
			<td bgcolor="#eaeaea" width="10%">Disciplina</td>
			<td width="90%">
            <select name="novaDisciplina" id="novaDisciplina">
				<option value="0">Escolher Disciplina</option>
				<?php
					for($i=0; $i < count($disciplinas); $i++) { ?>
						<option value="<?php echo $disciplinas[$i]->id;?>"
						<?php if($idDisciplina == $disciplinas[$i]->id) echo "SELECTED";?>>
							<?php echo $disciplinas[$i]->codigo." - ".$disciplinas[$i]->nomeDisciplina;?>
				</option>
				<?php } ?>
			</select>
            </td>
		</tr>
		<tr>
			<td bgcolor="#eaeaea">Turma</td>
			<td><input id="turma" name="turma" size="5" type="text" value="<?php if($turma) echo $turma;?>" /></td>
		</tr>
		<tr>
			<td bgcolor="#eaeaea">Professor</td>
			<td>
            	<select name="professor" class="inputbox">
					<option value="0">Professor Orientador</option>
					<?php
					$database->setQuery("SELECT * from #__professores WHERE ppgi = 1 ORDER BY nomeProfessor");
					$professores = $database->loadObjectList();

					foreach($professores as $professor) { ?>
						<option value="<?php echo $professor->id;?>"
							<?php if($professor->id == $disciplinaSelecionada->idProfessor) echo 'SELECTED';?>>
							<?php echo $professor->nomeProfessor;?>
						</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td bgcolor="#eaeaea">Hor&#225;rio</td>
			<td>
            	<div class="checkbox">
                    <input type="checkbox" name="com_horario" value="1" id="com_horario" checked />
                    <label for="com_horario">Disciplina com Hor&#225;rio <small>(para disciplinas sem hor&#225;rio, desmarque esta op&#231;&#227;o)</small></label>
                </div>
			</td>
            </tr>
        <tr>
			<td bgcolor="#eaeaea"></td>
            <td>

				<div id="formulariomayores" style="display:block;">
					<b>Dias</b>
                    <div class="checkbox">
                    <input name="dias[]" type="checkbox" value="Segunda" id="check1" /><label for="check1">Segunda</label>
					<input name="dias[]" type="checkbox" value="Ter&#231;a" id="check2" /><label for="check2">Ter&#231;a</label>
					<input name="dias[]" type="checkbox" value="Quarta" id="check3" /><label for="check3">Quarta</label>
                    <input name="dias[]" type="checkbox" value="Quinta" id="check4" /><label for="check4">Quinta</label>
                    <input name="dias[]" type="checkbox" value="Sexta" id="check5" /><label for="check5">Sexta</label>
                    <input name="dias[]" type="checkbox" value="S&#225;bado" id="check6" /><label for="check6">S&#225;bado</label>
                    </div> 
                    <br />
                    			
					<div class="layout" width="100%">
						<div class="layout-slider">
							<b>Horário</b>
                            <input id="Slider5" name="hora" value="14;16" type="slider" />
						</div>
                        
						<script type="text/javascript" charset="utf-8">
			            	jQuery("#Slider5").slider({ 
								from: 8, 
								to: 22, 
								step:1, 
								smooth: false, 
								skin: "blue", 
								dimension: '&nbsp;hs', 
								scale: ['8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00'], 
								limits: false, 
								calculate: function( value ){
									var hours = value;
									var mins = 0;
									return (hours < 10 ? "0"+hours : hours) + ":" + ( mins == 0 ? "00" : mins );
								}}
							);
          				</script>
					</div>
				</div>
                </td>
		</tr>
        <tr>
        	<td bgcolor="#eaeaea"></td>
            <td></td>
        </tr>
		<tr>
			<td bgcolor="#eaeaea">Curso</td>
			<td>
            	<div class="radio">
		            <input name="curso" value="1" type="radio" id="radio1" <?php if($disciplinaSelecionada->curso == 1) echo 'CHECKED';?>><label for="radio1">Mestrado</label>
					<input name="curso" value="2" type="radio" id="radio2" <?php if($disciplinaSelecionada->curso == 2) echo 'CHECKED';?>><label for="radio2">Doutorado</label>
                </div>
			</td>
		</tr>
		<tr>
			<td bgcolor="#eaeaea">Sala</td>
			<td><input id="sala" name="sala" size="30" type="text" value="<?php if($disciplinaSelecionada->sala) echo $disciplinaSelecionada->sala;?>" />
			</td>
		</tr>
	</table>

	<input name='idHidden' type='hidden' value='<?php echo $periodo->id;?>'>
	<input name='editar' type='hidden' value='<?php echo $editar;?>'> <input
		name='turmaVelha' type='hidden' value='<?php echo $turma;?>'> <input
		name='task' type='hidden' value='salvarCadastroPD'>
</form>

<?php }


// CADASTRAR PERIODO : TELA
function cadastrarNovoPeriodo() {
	$Itemid = JRequest::getInt('Itemid', 0);
	$database =& JFactory::getDBO(); ?>

	<!-- SCRIPTS -->
	<script language="JavaScript">
		function IsEmpty(aTextField) {
			if ((aTextField.value.length==0) || (aTextField.value==null) ) {
				return true;
			} else { 
				return false; 
			}
		}

		function validaDatas(dataInicio, dataFim) {
			var diaInicio = dataInicio.substr(0,2)
			var mesInicio = dataInicio.substr(3,2)
			var anoInicio = dataInicio.substr(6,4)
			var diaFim = dataFim.substr(0,2)
			var mesFim = dataFim.substr(3,2)
			var anoFim = dataFim.substr(6,4)
			
			var datInicio = new Date(anoInicio, mesInicio-1, diaInicio);
			var datFim = new Date(anoFim, mesFim-1, diaFim);
			
			if(datInicio >= datFim)
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
						if  (dia <= 31) {
							return true;
						}
						break
		
						case '04':
						case '06':
						case '09':
						case '11':
						if  (dia <= 30) {
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

		function validarPeriodo(periodo) {
			if(periodo.length != 7){
			  return false;
			}
			
			var ano = periodo.substr(0,4);
			var barra = periodo.substr(4,1);
			var semestre = periodo.substr(5,2);
			
			if(barra != '/' || isNaN(semestre) || isNaN(ano))
			  return false;
			
			return true;
		}

		function ValidateformPeriodo(formPeriodo) {
			if(IsEmpty(formPeriodo.periodo) || !validarPeriodo(formPeriodo.periodo.value)) {
				alert('O campo periodo deve ser preenchido seguindo o formato indicado.')
				formPeriodo.periodo.focus();
				return false;
			}


			if(IsEmpty(formPeriodo.inicio) || !VerificaData(formPeriodo.inicio.value)) {
				alert(unescape('O campo Data de In\xEDcio deve ser preenchida com um valor v\xE1lido.'))
				formPeriodo.inicio.focus();
				return false;
			}

			if(IsEmpty(formPeriodo.fim) || !VerificaData(formPeriodo.fim.value)) {
				alert(unescape('O campo Data de Fim deve ser preenchida com um valor v\xE1lido.'))
				formPeriodo.fim.focus();
				return false;
			}
			
			if(!validaDatas(formPeriodo.inicio.value, formPeriodo.fim.value)) {
				alert(unescape('A Data de In\xEDcio deve ser inferior \xE0 Data de Fim.'))
				formPeriodo.fim.focus();
				return false;
			}

			return true;
		}
	</script>
    
	<script type="text/javascript" src="components/com_portalsecretaria/assets/js/jquery.js"></script>
    
    <!-- MÁSCARAS -->
    <script type="text/javascript" src="components/com_portalsecretaria/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
		$(function($){
            $("#periodo").mask("9999/99");			
            $("#dataInicio").mask("99/99/9999");
            $("#dataTermino").mask("99/99/9999");
		});
    </script>
    
    <!-- CALENDÁRIO -->
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/default.css"/>
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/jquery.click-calendario-1.0.css"/>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/jquery.click-calendario-1.0-min.js"></script>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/script.js"></script>

    <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css"/>

	<form method="post" name="formPeriodo" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateformPeriodo(this)">
    
    	<!-- BARRA DE OPÇÕES -->
        <div id="toolbar-box"><div class="m">
            <div class="toolbar-list" id="toolbar">
                <div class="cpanel2">
                    <div class="icon" id="toolbar-save">
                        <a href="javascript:if(ValidateformPeriodo(document.formPeriodo))document.formPeriodo.submit()">
                        <span class="icon-32-save"></span> <?php echo JText::_( 'Salvar' ); ?></a>
                    </div>
                    
                    <div class="icon" id="toolbar-cancel">
                        <a href="index.php?option=com_portalsecretaria&task=periodo&Itemid=<?php echo $Itemid;?>">
                        <span class="icon-32-back"></span> <?php echo JText::_( 'Voltar' ); ?></a>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
            
            <div class="pagetitle icon-48-cpanel">
                <h2>Cadastro de Per&#237;odo do PPGI</h2>
            </div>
            </div>
        </div>

		<table class="table-form">
            <tr>
                <td width="37%">Per&#237;odo <span class="obrigatory">*</span></td>
                <td width="63%"><input type="text" id="periodo" name="periodo" placeholder="Ano/Semestre" />
                </td>
            </tr>
            <tr>
                <td>Data de In&#237;cio <span class="obrigatory">*</span></td>
                <td><input type="text" id="dataInicio" name="inicio" class="data imgcalendar" /></td>
            </tr>
            <tr>
                <td height="23">Data de Fim <span class="obrigatory">*</span> </td>
                <td><input type="text" id="dataTermino" name="fim" class="data imgcalendar"/></td>
            </tr>
        </table>

		<input name="task" type="hidden" value="criaPeriodo" />
	</form>

<?php }


// LISTAGEM DE PERÍODOS
function listarPeriodo($periodos, $ano, $resultado) {
	$Itemid = JRequest::getInt('Itemid', 0);

	$database	=& JFactory::getDBO();
	$sql = "SELECT DISTINCT periodo FROM #__periodos ORDER BY periodo DESC";
	$database->setQuery( $sql );
	$semestres = $database->loadObjectList(); ?>

	<!-- SCRIPTS -->
	<script language="JavaScript">
		function excluir(form) {
			var idSelecionado = 0;
			for(i = 0;i < form.idPeriodoSelec.length;i++)
			if(form.idPeriodoSelec[i].checked) idSelecionado = form.idPeriodoSelec[i].value;
		
			if(idSelecionado > 0) {
				var confirmar = confirm('Confirmar Exclusao?');
				
				if(confirmar == true) {
					form.task.value='excluirPeriodo';
					form.idHidden.value=idSelecionado;
					form.submit();
				}
			} else {
				alert('Ao menos 1 item deve ser selecionado para exclus\xE3o.');
			}		
		}
    
	    function visualizar(form) {    
		    var idSelecionado = 0;
		    for(i = 0;i < form.idPeriodoSelec.length;i++)
		    if(form.idPeriodoSelec[i].checked) idSelecionado = form.idPeriodoSelec[i].value;
    
		    if(idSelecionado > 0) {
			    form.task.value = 'cadastroPD';
			    form.idHidden.value=idSelecionado;
			    form.submit();
		    } else {
		 	   alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o das disciplinas.')
		    }
    	}
    
		function ConfirmarAtivacao(formPeriodo, idPeriodo) {
			var confirmar = confirm('Confirmar Ativacao?');
			
			if(confirmar == true) {
				formPeriodo.task.value='ativarPeriodo';
				formPeriodo.idHidden.value=idPeriodo;
				formPeriodo.submit();
			}
		}
    
		function ConfirmarDesativacao(formPeriodo, idPeriodo) {
			var confirmar = confirm('Confirmar Desativacao?');
			
			if(confirmar == true) {
				formPeriodo.task.value='desativarPeriodo';
				formPeriodo.idHidden.value=idPeriodo;
				formPeriodo.submit();
			}
		}
		
		function ConfirmarAbertura(formPeriodo, idPeriodo) {
			var confirmar = confirm('Confirmar Abertura de Matricula?');
			
			if(confirmar == true) {
				formPeriodo.task.value='abrirMatriculaPeriodo';
				formPeriodo.idHidden.value=idPeriodo;
				formPeriodo.submit();
			}
		}
    
		function ConfirmarFechamento(formPeriodo, idPeriodo) {
			var confirmar = confirm('Confirmar Fechamento de Matricula?');
			
			if(confirmar == true) {
				formPeriodo.task.value='fecharMatriculaPeriodo';
				formPeriodo.idHidden.value=idPeriodo;
				formPeriodo.submit();
			}
		}
		
		function ConfirmarAberturaNotas(formPeriodo, idPeriodo) {
			var confirmar = confirm('Confirmar Abertura de Periodo de Lancamento de Notas?');
			
			if(confirmar == true) {
				formPeriodo.task.value='abrirNotasPeriodo';
				formPeriodo.idHidden.value=idPeriodo;
				formPeriodo.submit();
			}
		}
    
		function ConfirmarFechamentoNotas(formPeriodo, idPeriodo) {
			var confirmar = confirm('Confirmar Fechamento de Periodo de Lancamento de Notas?');
			
			if(confirmar == true) {
				formPeriodo.task.value='fecharNotasPeriodo';
				formPeriodo.idHidden.value=idPeriodo;
				formPeriodo.submit();
			}
		}
		
		function AbrirInscricao(formPeriodo, idPeriodo) {			
			formPeriodo.task.value='abrirInscricao';
			formPeriodo.idHidden.value=idPeriodo;			
			formPeriodo.submit();
		}
		
		function EncerrarInscricao(formPeriodo, idPeriodo) {
			var confirmar = confirm('Deseja encerrar o perído de Inscrição?');
			
			if(confirmar == true) {
				formPeriodo.task.value='encerrarInscricao';
				formPeriodo.idHidden.value=idPeriodo;
				formPeriodo.submit();
			}
		}
    </script>

    <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
    <script ype="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>    
    <script type="text/javascript">
	    $(function() {
    	    $("#tablesorter-imasters").tablesorter();    
	    });
    </script>
    
    <script type="text/javascript" src="components/com_portalsecretaria/assets/js/jquery.js"></script>
    
    <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css"/>    
    <!--<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/estilo.css" />-->
    
	<form name="formPeriodo" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" method="post" class="form-horizontal">
    
        <div id="toolbar-box">
            <div class="m">
                <div class="toolbar-list" id="toolbar">
                    <div class="cpanel2">
                        <div class="icon" id="toolbar-new">
                            <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=addPeriodo">
                                <span class="icon-32-new"></span> <?php echo JText::_( 'Novo' ); ?>
                            </a>
                        </div>
                        
                        <div class="icon" id="toolbar-preview">
                            <a href="javascript:visualizar(document.formPeriodo)"> <span
                                class="icon-32-preview"></span> <?php echo JText::_( 'Ver<br>Disciplinas' ); ?>
                            </a>
                        </div>
                        
                        <div class="icon" id="toolbar-delete">
                            <a href="javascript:excluir(document.formPeriodo)"> <span
                                class="icon-32-delete"></span> <?php echo JText::_( 'Excluir' ); ?>
                            </a>
                        </div>
                        
                        <div class="icon" id="toolbar-back">
                            <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
                                <span class="icon-32-back"></span> <?php echo JText::_( 'Voltar' ); ?>
                            </a>
                        </div>
                        
                    </div>
                    <div class="clr"></div>
                </div>
                
                <div class="pagetitle icon-48-cpanel">
                    <h2>Per&#237;odos Cadastrados</h2>
                </div>
            </div>
        </div>

        <!-- FILTRO DA BUSCA -->
        <legend>Filtro para consulta</legend>
        <p>            
            Selecione o período           
            <select name="fecha">
                <option value="">Todos</option>
                <?php
                foreach($semestres as $semestre) { ?>
                <option value="<?php echo $semestre->periodo;?>"
                <?php
                if($semestre->periodo == $ano)
                    echo 'SELECTED';
                ?>>
                    <?php echo $semestre->periodo;?>
                </option>
                <?php } ?>
            </select>
        
            <button type="submit" name="buscar" class="btn btn-primary">
                <i class="icone-search icone-white"></i> Consultar
            </button>
		</p>                    
        
		<p>
			<?php
			switch($resultado){
				case 1:
					JError::raiseWarning( 100, 'ERRO: O per&#237;odo escolhido n&#227;o pode ser ativado, pois este j&#225; est&#225; ativado ou desativado!' );
					break;
				case 2:
					JError::raiseWarning( 100, 'ERRO: J&#225; existe um per&#237;odo ativado no momento. Primeiro encerre o per&#237;odo ativo para ent&#227;o ativar um novo per&#237;odo!' );
					break;
				case 3:
					JError::raiseWarning( 100, 'ERRO: O per&#237;odo escolhido n&#227;o pode ser desativado, pois este ainda n&#227;o foi ativado ou j&#225; est&#225; desativado!' );
					break;
				case 4:
					JError::raiseWarning( 100, 'ERRO: O per&#237;odo escolhido n&#227;o pode ser exclu&#237;do por j&#225; estar iniciado ou finalizado!' );
					break;
				case 5:
					JError::raiseWarning( 100, 'ERRO: O per&#237;odo escolhido n&#227;o pode ser ter seu per&#237;odo de matr&#237;cula aberto por j&#225; estar finalizado!' );
					break;
				case 6:
					JError::raiseWarning( 100, 'ERRO: O per&#237;odo escolhido n&#227;o pode ter o lan&#231;amento de notas aberto por j&#225; estar finalizado ou n&#227;o ter sido ainda iniciado!' );
					break;
          	} ?>
		</p>
        
		<table class="table table-striped" id="tabela">
			<thead>
				<tr>
					<th></th>
					<th>Ativar/Desativar</th>
                    <th>Inscrição</th>
					<th>Matr&#237;cula</th>
					<th>Lan&#231;ar Notas</th>
					<th>Status</th>
					<th>Per&#237;odo</th>
					<th>In&#237;cio</th>
					<th>Fim</th>
				</tr>
			</thead>
            
			<tbody>
				<?php
				$status = array (0 => "Nao iniciado", 1 => "Ativo", 2 => "Finalizado");
				
				foreach($periodos as $periodo) { ?>
                    
				<td><input type="radio" name="idPeriodoSelec" id="idPeriodoSelec" value="<?php echo $periodo->id;?>" /></td>
				<td>
					<?php if($periodo->status == 0) { ?>
                        <a href="javascript:ConfirmarAtivacao(document.formPeriodo, <?php echo $periodo->id;?>)">
                        	<img border='0' src='components/com_portalsecretaria/images/ativar.png' width='16' height='16' title='Iniciar Periodo'>
                        </a>
					<?php } else if($periodo->status == 1) { ?>
                        <a href="javascript:ConfirmarDesativacao(document.formPeriodo, <?php echo $periodo->id;?>)">
                        	<b><img border='0' src='components/com_portalsecretaria/images/desativar.png' width='16' height='16' title='Finalizar Periodo'>
                        </a>
					<?php } ?>
				</td>
                <td>
                	<?php if ($periodo->status == 0) { 
					
						$database =& JFactory::getDBO();
						$sql = "SELECT published FROM #__menu WHERE id = 175";
						$database->setQuery($sql);
						$publish = $database->loadObjectList(); 
						
						//if ($publish[0]->published == 1) { 
						if ($periodo->inscricao == 0) { ?> 
                            <!--<span class="option-on">Aberta
                            <a href="javascript:EncerrarInscricao(document.formPeriodo)">
                                <img src='components/com_portalsecretaria/assets/img/icon_option.fw.png' width='16' height='16' title='Clique para Encerrar'>
                            </a>
                            </span>-->
                            
                            <a href="javascript:AbrirInscricao(document.formPeriodo, <?php echo $periodo->id;?>)">
                            <img border='0' src='components/com_portalsecretaria/images/cadeado_fechado.gif' title='Abrir para  Inscrição'>
                        </a>
                        <?php } else { ?>
                            <!--<span class="option-off">Encerrada
                            <a href="javascript:AbrirInscricao(document.formPeriodo)">
                                <img src='components/com_portalsecretaria/assets/img/icon_option.fw.png' width='16' height='16' title='Clique para Ativar'>
                            </a>
                            </span>-->
                        
                        	<a href="javascript:EncerrarInscricao(document.formPeriodo, <?php echo $periodo->id;?>)">
                            <img border='0' src='components/com_portalsecretaria/images/cadeado_aberto.gif' title='Fechar para  Inscrição'> 
                        </a>
                    <?php } } ?>           	
                </td>
				<td>
					<?php if($periodo->matricula == 0) { ?>
                        <a href="javascript:ConfirmarAbertura(document.formPeriodo, <?php echo $periodo->id;?>)">
                            <img border='0' src='components/com_portalsecretaria/images/cadeado_fechado.gif' title='Abrir para Matricula'> 
                        </a> 
                    <?php } else { ?>
                        <a href="javascript:ConfirmarFechamento(document.formPeriodo, <?php echo $periodo->id;?>)">
                            <img border='0' src='components/com_portalsecretaria/images/cadeado_aberto.gif' title='Fechar para Matricula'>
                        </a>
                    <?php } ?>
                </td>
				<td>
					<?php if($periodo->notas == 0) { ?>
                        <a href="javascript:ConfirmarAberturaNotas(document.formPeriodo, <?php echo $periodo->id;?>)">
                            <img border='0' src='components/com_portalsecretaria/images/cadeado_fechado.gif' title='Abrir para Lan&#231;amento de Notas'>
                        </a>
                    <?php } else { ?>
                        <a href="javascript:ConfirmarFechamentoNotas(document.formPeriodo, <?php echo $periodo->id;?>)">
                            <img border='0' src='components/com_portalsecretaria/images/cadeado_aberto.gif' title='Fechar para Lan&#231;amento de Notas'>
                        </a>
                    <?php } ?>
				</td>

				<td><?php echo $status[$periodo->status];?></td>
				<td><?php echo $periodo->periodo;?></td>
				<td><?php echo $periodo->dataInicio;?></td>
				<td><?php echo $periodo->dataFim;?></td>
			</tr>

			<?php } ?>

			</tbody>

		</table>
        
		<input name="task" type="hidden" value="periodo" />
		<input name="idPeriodoSelec" type="hidden" value="0" />
		<input name="idHidden" type="hidden" value="" />
	</form>

<?php }


// LISTAGEM DE DISCIPLINAS
function listarDisciplinasPPGI($filtro) {	
	$Itemid = JRequest::getInt('Itemid', 0);
	$database	=& JFactory::getDBO();

	$sql = "SELECT * FROM #__disciplina WHERE nomeDisciplina LIKE '%$filtro%' ORDER BY nomeDisciplina";
	$database->setQuery( $sql );
	$disciplinas = $database->loadObjectList(); ?>

	<script language="JavaScript">
		function excluir(form) {
			var idSelecionado = 0;
		
			for(i = 0;i < form.idDiscSelec.length;i++)
				if(form.idDiscSelec[i].checked) idSelecionado = form.idDiscSelec[i].value;
		
			if(idSelecionado > 0) {
				var confirmar = confirm('Confirmar Exclusao?');
				
				if(confirmar == true) {
					form.task.value='excluirDisciplina';
					form.idDisc.value=idSelecionado;
					form.submit();
				}
			} else {
				alert('Ao menos 1 item deve ser selecionado para exclus\xE3o.')
			}
		
		}

		function editar(form){
		   var idSelecionado = 0;
		   for(i = 0;i < form.idDiscSelec.length;i++)
				if(form.idDiscSelec[i].checked) idSelecionado = form.idDiscSelec[i].value;
		
		   if(idSelecionado > 0) {
			   form.task.value = 'editarDisciplina';
			   form.idDisc.value=idSelecionado;
			   form.submit();
		   } else {
			   alert('Ao menos 1 item deve ser selecionado para edi\xE7\xE3o.')
		   }
		}
	</script>

	<script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
	<script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();	
		});
	</script>
    
	<link rel="stylesheet" href="components/com_portalsecretaria/assets/css/estilo.css" type="text/css" />
    
	<form method="post" name="formDisciplina" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
    
        <div id="toolbar-box">
            <div class="m">
                <div class="toolbar-list" id="toolbar">
                    <div class="cpanel2">
                        <div class="icon" id="toolbar-new">
                            <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=addDisciplina">
                            <span class="icon-32-new"></span> <?php echo JText::_( 'Nova' ); ?></a>
                        </div>
                        
                        <div class="icon" id="toolbar-edit">
                            <a href="javascript:editar(document.formDisciplina)">
                            <span class="icon-32-edit"></span> <?php echo JText::_( 'Editar' ); ?></a>
                        </div>
                        
                        <div class="icon" id="toolbar-delete">
                            <a href="javascript:excluir(document.formDisciplina)">
                            <span class="icon-32-delete"></span> <?php echo JText::_( 'Excluir' ); ?></a>
                        </div>
                        
                        <div class="icon" id="toolbar-back">
                            <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
                            <span class="icon-32-back"></span> <?php echo JText::_( 'Voltar' ); ?></a>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
                
                <div class="pagetitle icon-48-cpanel">
                    <h2>Disciplinas Cadastradas</h2>
                </div>
            </div>
        </div>

        <!-- FILTRO DA BUSCA -->
        <fieldset>
            <legend>Filtro para consulta</legend>
            
            <input id="nome" name="nome" size="40" type="text" value="<?php echo $filtro;?>" placeholder="Nome da disciplina..." /> 
            
            <button type="submit" name="buscar" class="btn btn-primary" title="Filtra busca">
                <i class="icone-search icone-white"></i> Consultar
            </button>
        </fieldset>				

        <table class="table table-striped" id="tablesorter-imasters">
            <thead>
                <tr>
                    <th></th>
                    <th>C&#243;digo</th>
                    <th>Nome</th>
                    <th>Cr&#233;ditos</th>
                    <th>Carga Hor&#225;ria</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $disciplinas as $disciplina ) { ?>

                <tr>
                    <td><input type="radio" name="idDiscSelec" value="<?php echo $disciplina->id;?>"></td>
                    <td><?php echo $disciplina->codigo;?></td>
                    <td><?php echo $disciplina->nomeDisciplina;?></td>
                    <td><div align="center"><?php echo  $disciplina->creditos;?></div></td>
                    <td><div align="center"><?php echo $disciplina->carga;?></div></td>
                </tr>

                <?php } ?>

            </tbody>
        </table>
            
		<input name="task" type="hidden" value="disciplinas" />
        <input name="idDiscSelec" type="hidden" value="0" />
        <input name="idDisc" type="hidden" value="" />
        
	</form>

<?php }


// CADASTRAR/EDITAR DISCIPLINA : TELA
function cadastrarDisciplina($idDisc) {
	$Itemid = JRequest::getInt('Itemid', 0);
	$database	=& JFactory::getDBO();

	if($idDisc) {
       $sql = "SELECT * from #__disciplina WHERE id=$idDisc";
       $database->setQuery($sql);
       $disciplinas = $database->loadObjectList();
       $disciplina = $disciplinas[0];
    } ?>

	<script language="JavaScript">
		function IsEmpty(aTextField) {
		   if ((aTextField.value.length==0) ||
		   (aTextField.value==null) ) {
			  return true;
		   }
		   else { return false; }
		}

		function ValidateformDisciplina(formDisciplina) {
		   if(IsEmpty(formDisciplina.codigo)) {
			  alert(unescape('O campo Codigo deve ser preenchido.'))
			  formDisciplina.codigo.focus();
			  return false;
		   }
		
		   if(IsEmpty(formDisciplina.nome)) {
			  alert(unescape('O campo Nome da Disciplina deve ser preenchido.'))
			  formDisciplina.nome.focus();
			  return false;
		   }
		
		   if(IsEmpty(formDisciplina.creditos)) {
			  alert(unescape('O campo Creditos deve ser preenchido.'))
			  formDisciplina.creditos.focus();
			  return false;
		   }
		
		   if(IsEmpty(formDisciplina.carga)) {
			  alert(unescape('O campo Carga Horaria deve ser preenchido.'))
			  formDisciplina.carga.focus();
			  return false;
		   }
		
			return true;
		}

		function handleEnterCredito (field, event) {
			var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
			if(keyCode == 8 || keyCode == 9 || keyCode <= 46 || (keyCode >= 48 && keyCode <= 57)) return true;
			if (keyCode == 13) {
			var i;
			for (i = 0; i < field.form.elements.length; i++)
			 if (field == field.form.elements[ i ])
			   break;
			   i = (i + 1) % field.form.elements.length;
			   field.form.elements[ i ].focus();
			   return false;
			} else
				return false;
		}

		function handleEnterCarga (field, event) {
			  var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
			  
			  if(keyCode == 8 || keyCode == 9 || (keyCode >= 48 && keyCode <= 57)) return true;
			  if (keyCode == 13) {
				var i;
				
			  	for (i = 0; i < field.form.elements.length; i++)
				 if (field == field.form.elements[ i ])
				   break;
				   i = (i + 1) % field.form.elements.length;
				   field.form.elements[ i ].focus();
				   return false;
			  } else
			return false;
		}
	</script>
    
  	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />

	<form method="post" name="formDisciplina" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateformDisciplina(this)">
    
		<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
			<div class="cpanel2">
            	<div class="icon" id="toolbar-save">
                	<a href="javascript:if(ValidateformDisciplina(document.formDisciplina))document.formDisciplina.submit()">
                    	<span class="icon-32-save"></span> <?php echo JText::_( 'Salvar' ); ?>
                    </a>
                </div>
                    
                <div class="icon" id="toolbar-cancel">
                	<a href="index.php?option=com_portalsecretaria&task=disciplinas&Itemid=<?php echo $Itemid;?>">
                        <span class="icon-32-cancel"></span> <?php echo JText::_( 'Cancelar' ); ?>
                    </a>
                </div>
                </div>
                <div class="clr"></div>
            </div>
            
            <div class="pagetitle icon-48-edit">
                <h2>Cadastro/Edi&#231;&#227;o de Disciplinas do PPGI</h2>
            </div>
            </div>
        </div>
        
        <span class="label label-info">Como Proceder</span>
        <ul>
        	<li>Preencha os campos abaixo para cadastrar ou editar um Disciplia</li>
        </ul>
        <hr />

        <table width="100%" class="table-form">
            <tr>
                <td>Código</td>
                <td><input type="text" name="codigo" value="<?php echo $disciplina->codigo;?>"></td>
            </tr>
            <tr>
                <td>Nome da Disciplina</td>
                <td><input type="text" name="nome" value="<?php echo $disciplina->nomeDisciplina;?>" style="width:350px;"></td>
            </tr>
            <tr>
                <td>Crédito</td>
                <td><input type="text" name="creditos" value="<?php echo $disciplina->creditos;?>" onkeypress="return handleEnterCredito(this, event)"></td>
            </tr>
            <tr>
                <td>Carga Horária</td>
                <td><input type="text" name="carga" value="<?php echo $disciplina->carga;?>" onkeypress="return handleEnterCarga(this, event)"></td>
            </tr>
        </table>
        
        <input name="task" type="hidden" value="salvarDisciplina" />
        <input name="idDisc" type="hidden" value="<?php echo $idDisc;?>" />

	</form>

<?php }


// LISTAR TURMAS
function listarTurmas($idPeriodo, $curso) {
	$Itemid = JRequest::getInt('Itemid', 0);
	$database =& JFactory::getDBO(); ?>

	<script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
	<script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();
		});
	</script>
    
  	<link rel="stylesheet" href="components/com_portalsecretaria/assets/css/estilo.css" type="text/css" />
    
	<form name="formTurma" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" method="post">
    
		<div id="toolbar-box"><div class="m">
			<div class="toolbar-list" id="toolbar">
				<div class="cpanel2">
					<div class="icon" id="toolbar-back">
						<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
                        	<span class="icon-32-back"></span> <?php echo JText::_( 'Voltar' ); ?>
                        </a>                        
                    </div>
                    
                </div>
                <div class="clr"></div>
                </div>
                
                <div class="pagetitle icon-48-cpanel">
                    <h2>Turmas Cadastradas</h2>
                </div>
            </div>
        </div>

		<legend>Filtros para consulta</legend>
		<table>
			<tr>
				<td width="40%">
				Per&#237;odo <select name="periodo" style="width:120px;">
				<?php
					$database->setQuery("SELECT * from #__periodos ORDER BY periodo DESC");
					$periodos = $database->loadObjectList();

					foreach ($periodos as $periodo) { ?>
						<option value="<?php echo $periodo->id;?>"
						<?php
							if($idPeriodo == NULL) {
					            if($periodo->status == 1) {
					            	echo 'SELECTED';								   
				    	            $idPeriodo = $periodo->id;
					            }
					         } else {
					            if($idPeriodo == $periodo->id) echo 'SELECTED';
					         }
				         ?>>
                         
						 <?php echo $periodo->periodo;?>
				 		 </option>
                         
					<?php }
					
			         if($idPeriodo == NULL) 
					 	$idPeriodo = $periodos[0]->id;
			         
					 echo "</select>";
			         $sqlEstendido = "";

			         if ($curso > 0) $sqlEstendido = "AND curso = $curso";
			
					 $sql = "SELECT idPeriodo, idDisciplina, professor, horario, turma, curso, codigo, P.status, nomeDisciplina FROM #__periodo_disc as P join #__disciplina as D ON P.idDisciplina = D.id WHERE P.idPeriodo = $idPeriodo ".$sqlEstendido." ORDER BY nomeDisciplina, curso";
					 $database->setQuery( $sql );
					 $turmas = $database->loadObjectList();

         			?>
                    </select>         
				</td>
				<td width="40%">
                	Curso <select name="curso" style="width:150px;">
							<option value="0" <?php if($curso == 0) echo 'SELECTED';?>>Todos</option>
							<option value="1" <?php if($curso == 1) echo 'SELECTED';?>>Mestrado</option>
							<option value="2" <?php if($curso == 2) echo 'SELECTED';?>>Doutorado</option>
						   </select>
				</td>
				<td>
                	<button type="submit" name="buscar" class="btn btn-primary" title="Filtra busca" style="margin-top:-8px;">
                        <i class="icone-search icone-white"></i> Consultar
                    </button>
                </td>
			</tr>
		</table>
        <br />        
		        
		<table class="table table-striped" id="tablesorter-imasters">
			<thead>
                <tr>
                    <th></th>
                    <th>Status</th>
                    <th>C&#243;digo</th>
                    <th>Disciplina</th>
                    <th>Professor</th>
                    <th>Turma</th>
                    <th>Curso</th>
                </tr>
            </thead>
            
			<tbody>
			<?php
                $curso = array (1 => "mestrado",2 => "doutorado",3 => "especial");
                $status = array('0' => "Aberta" , '1' => "Finalizada");
                $statusImg = array(0 => "cadeado_aberto.gif" , 1 => "cadeado_fechado.gif");
			
                if ($turmas) {
					foreach ($turmas as $turma) { ?>

				<tr>
				<td align="center" width='16'><a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=consultarTurma&idDisciplina=<?php echo $turma->idDisciplina;?>&periodo=<?php echo $turma->idPeriodo;?>&turma=<?php echo $turma->turma;?>"><img border='0' src='components/com_portalsecretaria/images/b_view.png' width='16' height='16' title='detalhar'></a>			</td>
				<td><img src="components/com_portalsecretaria/images/<?php echo $statusImg[$turma->status];?>" alt="oi" title="<?php echo $status[$turma->status];?>">
				</td>
                <td><?php echo $turma->codigo;?></td>
                <td><?php echo $turma->nomeDisciplina;?></td>
                <td><?php echo $turma->professor;?></td>
                <td><?php echo $turma->turma;?></td>
                <td><img border='0'
                    src='components/com_portalsecretaria/images/<?php echo $curso[$turma->curso];?>.gif'
                    title='<?php echo $curso[$turma->curso];?>'>
                </td>
			</tr>
            
			<?php
                }
            }
            ?>

            </tbody>
        </table>
        
        <br />
		Total de Turmas: <b><?php echo sizeof($turmas);?> </b>
        <input type="hidden" name="task" value="listarturmas" />
        <input type="hidden" name="idDisciplina" value="" />
        
	</form>

<?php }


// CONSULTAR TURMA : LISTAGEM DE ALUNOS
function consultarTurma($idPeriodo, $idDisciplina, $turma) {
	$Itemid = JRequest::getInt('Itemid', 0);
	$database =& JFactory::getDBO();

	$sql = "SELECT idPeriodo, idDisciplina, professor, horario, turma, curso, codigo, nomeDisciplina, status FROM #__periodo_disc as P join #__disciplina as D ON P.idDisciplina = D.id WHERE P.idPeriodo = $idPeriodo AND P.idDisciplina = $idDisciplina AND turma = '$turma'";
	$database->setQuery($sql);
	$turmas = $database->loadObjectList();

	$sql = "SELECT idAluno, nome, matricula, orientador, area, curso, bolsista, agencia, anoingresso, frequencia, conceito FROM #__disc_matricula as M join #__aluno as A ON M.idAluno = A.id WHERE M.idPeriodo = $idPeriodo AND M.idDisciplina = $idDisciplina AND turma = '$turma ' ORDER BY nome";
	$database->setQuery($sql);
	$alunos = $database->loadObjectList();
	
	$turma = $turmas[0];
	
	$sql = "SELECT status FROM #__periodos WHERE id = '$turma->idPeriodo'";
	$database->setQuery($sql);
	$dados = $database->loadObjectList();
	$status = $dados[0]->status;
	
	$linhaPesquisa = array (1 => "bd_ri",2 => "se_es",3 => "ia",4 => "visao",5 => "redes",6 => "otimizacao");
	$curso = array (1 => "Mestrado",2 => "Doutorado",3 => "Especial"); ?>

	<script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
	<script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();
		});
	</script>
    
  	<script type="text/javascript" rc="components/com_portalsecretaria/assets/js/jquery.js"></script>
    
  	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />
    
    <div id="toolbar-box"><div class="m">
        <div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-back">
                    <a href="index.php?option=com_portalsecretaria&task=listarturmas&Itemid=<?php echo $Itemid;?>">
                        <span class="icon-32-back"></span> <?php echo JText::_( 'Voltar' ); ?>
                    </a>
                </div>
            </div>
            <div class="clr"></div>
        </div>
        
        <div class="pagetitle icon-48-cpanel">
            <h2>Consulta de Turma</h2>
        </div>
        </div>
    </div>
		
	<table align="center" cellspacing='3' cellpadding="3" width="100%">
        <tr>
            <th id="cpanel" width="20%">
                <div class="icon" style='text-align: center;'>
                    <a
                        href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=imprimirPagela&idDisciplina=<?php echo $turma->idDisciplina;?>&periodo=<?php echo $turma->idPeriodo;?>&turma=<?php echo $turma->turma;?>&tipo=1"
                        onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;"><img
                        src="images/print_f2.png" border="0"><br>Pagela de Alunos
                        Regulares</a>
                </div>
            </th>
            
			<?php if($turma->curso == 1 && sizeof($alunos) > 0) { ?>
				<th id="cpanel" width="20%">
					<div class="icon" style='text-align: center;'>
						<a
							href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=imprimirPagela&idDisciplina=<?php echo $turma->idDisciplina;?>&periodo=<?php echo $turma->idPeriodo;?>&turma=<?php echo $turma->turma;?>&tipo=3"
							onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;"><img
							src="images/print_f2.png" border="0"><br>Pagela de Alunos
							Especiais</a>
					</div>
				</th>
			<?php } ?>
            
			<th id="cpanel" width="20%">
                <div class="icon" style='text-align: center;'>
                    <a
                        href="index.php?option=com_portalsecretaria&task=imprimirBoletim&idDisciplina=<?php echo $turma->idDisciplina;?>&periodo=<?php echo $turma->idPeriodo;?>&turma=<?php echo $turma->turma;?>&tipo=<?php echo $turma->curso;?>"
                        onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;"><img
                        src="images/print_f2.png" border="0"><br>Boletim de Notas Alunos
                        Regulares</a>
                </div>
            </th>
            
			<?php if($turma->curso == 1  && sizeof($alunos) > 0) { ?>
				<th id="cpanel" width="20%">
					<div class="icon" style='text-align: center;'>
						<a
							href="index.php?option=com_portalsecretaria&task=imprimirBoletim&idDisciplina=<?php echo $turma->idDisciplina;?>&periodo=<?php echo $turma->idPeriodo;?>&turma=<?php echo $turma->turma;?>&tipo=3"
							onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;"><img
							src="images/print_f2.png" border="0"><br>Boletim de Notas de
							Alunos Especiais</a>
					</div>
				</th>
			<?php } ?>            
        </tr>
    </table>
        
	<h3>Dados da Turma</h3>
    <hr />

    <table width='100%' class="table">
        <tr>
            <td bgcolor="#D0D0D0" width="15%"><b>C&#243;digo </b></td>
            <td width="15%"><?php echo $turma->codigo;?></td>
            <td bgcolor="#D0D0D0" width="20%"><b>Nome Disciplina </b></td>
            <td width="50%"><?php echo $turma->nomeDisciplina;?></td>
        </tr>
        <tr>
            <td bgcolor="#D0D0D0"><b>Turma </b></td>
            <td><?php echo $turma->turma;?></td>
            <td bgcolor="#D0D0D0"><b>Professor </b></td>
            <td><?php echo $turma->professor;?></td>
        </tr>
        <tr>
            <td bgcolor="#D0D0D0"><b>Curso </b></td>
            <td><?php echo $curso[$turma->curso];?></td>
            <td bgcolor="#D0D0D0"><b>Hor&#225;rio </b></td>
            <td><?php echo $turma->horario;?></td>
        </tr>		
    </table>
    <br />
        
    <h3>Alunos Matriculados</h3>
   	<hr />
    
    <table width="100%" id="tablesorter-imasters" class="table table-striped">
        <thead>
            <tr bgcolor="#002666">
                <th width="40%" align="center"><b>Nome</b></th>
                <th width="12%" align="center"><b>Matr&#237;cula</b></th>
                <th width="12%" align="center"><b>Linha</b></th>
                <th width="11%" align="center"><b>Ingresso</b></th>
                <th width="10%" align="center"><b>Freq (%)</b></th>
                <th width="12%" align="center"><b>Conceito</b></th>
                <?php if ($status == '2'  || $turma->status == 1) { ?>
                    <th width="12%" align="center"><b>Editar</b></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php
            $table_bgcolor_even="#e6e6e6";
            $table_bgcolor_odd="#FFFFFF";

            $i = 0;
            if($alunos){
                foreach ($alunos as $aluno) {
                    $i = $i + 1;
                    
                    if ($i % 2)
                        echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");			
                    else	
                        echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>"); ?>
                        
            <td><?php echo $aluno->nome;?></td>
            <td><?php echo $aluno->matricula;?></td>
            <td><img src="components/com_portalsecretaria/images/<?php echo $linhaPesquisa[$aluno->area];?>.gif" title="<?php echo verLinhaPesquisa($aluno->area, 1);?>">
            </td>
            <td><?php echo dataBr($aluno->anoingresso);?></td>
            <td><?php if($aluno->frequencia <> NULL) echo number_format($aluno->frequencia,2);?></td>
            <td><?php echo $aluno->conceito;?></td>
            <?php if ($status == '2' || $turma->status == 1) { ?>
                <td><a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=corrigirNota&idAluno=<?php echo $aluno->idAluno;?>&idDisciplina=<?php echo $turma->idDisciplina;?>&periodo=<?php echo $turma->idPeriodo;?>&idTurma=<?php echo $turma->turma;?>" title="Alterar Notas"><img src="components/com_portalsecretaria/images/editar.gif" /></a></td>
            <?php } ?>
        </tr>
        
        <?php
            }
        }
        ?>

        </tbody>
    </table>
    <br />

    <span class="label label-inverse">Total de Alunos: <?php echo sizeof($alunos); ?></span>
    
    <input name="task" type="hidden" value="listarturmas" />
    <input name="idDisciplina" type="hidden" value="<?php echo $turma->idDisciplina;?>" />
    <input name="periodo" type="hidden" value="<?php echo $turma->idPeriodo;?>" />
    <input name="turma" type="hidden" value="<?php echo $turma->turma;?>" />

<?php }


// CORREÇÃO DE NOTA : TELA
function corrigirNota($idAluno, $idPeriodo, $idDisciplina, $idTurma) { 
    $Itemid = JRequest::getInt('Itemid', 0);
    $database =& JFactory::getDBO(); 
	
	$sql = "SELECT idPeriodo, idDisciplina, professor, horario, turma, curso, codigo, nomeDisciplina 
			FROM #__periodo_disc as P join #__disciplina as D ON P.idDisciplina = D.id
			WHERE P.idPeriodo = $idPeriodo 
			AND P.idDisciplina = $idDisciplina 
			AND turma = '$idTurma'";
	$database->setQuery( $sql );
	$turmas = $database->loadObjectList(); 
	$turma = $turmas[0]; 
	
	$sql = "SELECT idAluno, nome, matricula, orientador, area, curso, bolsista, agencia, anoingresso, frequencia, conceito 
				FROM #__disc_matricula as M join #__aluno as A ON M.idAluno = A.id 
				WHERE M.idPeriodo = '$idPeriodo'
				AND M.idAluno = '$idAluno'
				AND M.idDisciplina = '$idDisciplina' 
				AND turma = '$idTurma' 
				ORDER BY nome";
    $database->setQuery($sql);
    $alunos = $database->loadObjectList();
	$aluno = $alunos[0];
	
	$curso = array (1 => "Mestrado",2 => "Doutorado",3 => "Especial"); ?> 
    
    <script language="javascript">
        function atualizar(form){
            form.task.value = 'atualizarNota';
            form.submit();
        }
	</script>
    
  	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />

	<form method="post" name="formEditNota" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
    
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-save">
                    <a href="javascript: atualizar(document.formEditNota)" class="toolbar">
                    <span class="icon-32-save"></span><?php echo JText::_( 'Atualizar' ); ?></a>
                </div>
                <div class="icon" id="toolbar-back">
                    <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid; ?>&task=consultarTurma&idDisciplina=<?php echo $idDisciplina; ?>&periodo=<?php echo $idPeriodo; ?>&turma=<?php echo $idTurma; ?>">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
           
            <div class="clr"></div>
            </div>
            <div class="pagetitle icon-48-search"><h2>Alterar Notas</h2></div>
        </div></div>
    
        <h3>Dados da Turma</h3>
		<hr />
       
        <table width='100%' class="table">
            <tr>
                <td bgcolor="#D0D0D0" width="15%"><b>C&#243;digo </b></td>
                <td width="15%"><?php echo $turma->codigo;?></td>
                <td bgcolor="#D0D0D0" width="20%"><b>Nome Disciplina </b></td>
                <td width="50%"><?php echo $turma->nomeDisciplina;?></td>
            </tr>
            <tr>
                <td bgcolor="#D0D0D0"><b>Turma </b></td>
                <td><?php echo $turma->turma;?></td>
                <td bgcolor="#D0D0D0"><b>Professor </b></td>
                <td><?php echo $turma->professor;?></td>
            </tr>
            <tr>
                <td bgcolor="#D0D0D0"><b>Curso </b></td>
                <td><?php echo $curso[$turma->curso];?></td>
                <td bgcolor="#D0D0D0"><b>Hor&#225;rio </b></td>
                <td><?php echo $turma->horario;?></td>
            </tr>
        </table>
        
    
        <h3>Dados do Aluno</h3>
        <hr />
        
        <table width='100%' class="table">
            <thead>
                <tr align="center" bgcolor="#e6e6e6">
                    <th width="50%"><b>Nome</b></th>
                    <th width="20%"><b>Frequência (%)</b></th>
                    <th width="30%"><b>Conceito</b></th>
                </tr>
            </thead>
            
            <tbody>
                <tr align="center">
                    <td><?php echo $aluno->nome; ?></td>
                    <td><input type="text" name="frequencia" value="<?php echo number_format($aluno->frequencia,2); ?>" /></td>
                    <td>
                        <select name="conceito" class="inputbox">
							<option value="<?php echo $aluno->conceito;?>"><?php echo $aluno->conceito;?></option>
						<?php if($turma->idDisciplina == 68 || $turma->idDisciplina == 69 || $turma->idDisciplina == 94) { ?>
							<option value="AP" <?php if ($aluno->conceito == "AP") echo 'SELECTED';?>>AP - Aprovado</option>
						<?php }
						else{         ?>
							<option value="A" <?php if ($aluno->conceito == "A") echo 'SELECTED';?>>A (&#8805; 9,0: Aprovado)</option>
							<option value="B" <?php if ($aluno->conceito == "B") echo 'SELECTED';?>>B (&#8805; 8,0 e < 9,0: Aprovado)</option>
							<option value="C" <?php if ($aluno->conceito == "C") echo 'SELECTED';?>>C (&#8805; 7,0 e < 8,0: Aprovado)</option>
							<option value="X" <?php if ($aluno->conceito == "X") echo 'SELECTED';?>>X (Trancamento)</option>
						<?php }  ?>
							<option value="R" <?php if ($aluno->conceito == "R") echo 'SELECTED';?>>R (Reprovado)</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        
		<input name="idAluno" type="hidden" value="<?php echo $idAluno; ?>" />        
		<input name="idDisciplina" type="hidden" value="<?php echo $turma->idDisciplina; ?>" />
		<input name="idPeriodo" type="hidden" value="<?php echo $turma->idPeriodo; ?>" />
		<input name="turma" type="hidden" value="<?php echo $turma->turma; ?>" />
		<input name='task' type='hidden' value='atualizarNota'>
    
    </form>	
	
<?php }


// CORREÇÃO DE NOTA : SQL
function atualizarNota($idAluno, $idPeriodo, $idDisciplina, $turma) {
	$database =& JFactory::getDBO();
	
	$frequencia = JRequest::getVar('frequencia');
	$conceito = JRequest::getVar('conceito');

	$sql = "UPDATE #__disc_matricula 
				SET frequencia = $frequencia, conceito = '$conceito' 
				WHERE idAluno = '$idAluno' AND idPeriodo = '$idPeriodo' AND idDisciplina = '$idDisciplina'";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	if ($funcionou) {
		JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
	}
	
	consultarTurma($idPeriodo, $idDisciplina, $turma);
}


// IMPRIMIR OFERTA
function imprimirOferta($disciplinas, $periodo) {
	$Itemid = JRequest::getInt('Itemid', 0);

	$ofertaDisciplina = "components/com_portalaluno/forms/oferta".$periodo->id.".pdf";
	$arq = fopen($ofertaDisciplina, 'w') or die("CREATE ERROR");

	$pdf = new Cezpdf('a4','landscape');
	$pdf->selectFont('pdf-php/fonts/Helvetica.afm');
	$optionsText = array('justification'=>'center', 'spacing'=>1.5);
	$dados = array('justification'=>'justify', 'spacing'=>1.0);
	$optionsTable = array('fontSize'=>8, 'titleFontSize'=>8, 'xPos'=>'center', 'width'=>800, 'cols'=>array('Código'=>array('width'=>50, 'justification'=>'center'),'Disciplina'=>array('width'=>235), 'Turma'=>array('width'=>35, 'justification'=>'center'), 'Professor'=>array('width'=>140), 'CR'=>array('width'=>25, 'justification'=>'center'), 'CH'=>array('width'=>25, 'justification'=>'center'), 'Horário'=>array('width'=>145, 'justification'=>'center'), 'Sala'=>array('width'=>145, 'justification'=>'center')));

	$pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 30, 470, 100);
	$pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',15,$optionsText);
	$pdf->ezText('<b>Instituto de Computação - IComp</b>',12,$optionsText);
	$pdf->ezText('<b>Programa de Pós-Graduação em Informática - PPGI</b>',12,$optionsText);
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText('');  //Para quebra de linha
	$pdf->setLineStyle(3);
	$pdf->line(20, 450, 820, 450);
	$pdf->ezText("<b>OFERTA DE DISCIPLINAS DO PERÍODO ".$periodo->periodo."</b>",14,$optionsText);
	$pdf->addText(480,780,8,"<b>Data:</b> ".date("d/m/Y"),0,0);
	$pdf->addText(480,790,8,"<b>Hora:</b> ".date("H:i"),0,0);
	$pdf->ezText('');
	$pdf->setLineStyle(3);
	$pdf->line(20, 700, 580, 700);

	foreach( $disciplinas as $disciplina)
	{
		$disciplinasMatriculas[] = array('Código'=>$disciplina->codigo, 'Disciplina'=>utf8_decode($disciplina->nomeDisciplina), 'Turma'=>$disciplina->turma,'CR'=>$disciplina->creditos, 'CH'=>$disciplina->carga, 'Professor'=>utf8_decode($disciplina->professor), 'Horário'=>utf8_decode($disciplina->horario), 'Sala'=>utf8_decode($disciplina->sala));
	}

	$pdf->ezTable($disciplinasMatriculas,$cols,'',$optionsTable);

	$pdf->line(20, 55, 820, 55);
	$pdf->addText(270,40,8,'Av. Rodrigo Otávio, 6.200 - Coroado - Campus Universitário - CEP 69077-000 - Manaus, AM, Brasil',0,0);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 290, 30, 8, 8);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 403, 30, 8, 8);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 527, 30, 8, 8);
	$pdf->addText(300,30,8,'Tel. (092) 3305 2808 / 2809       E-mail: secppgi@ufam.edu.br        www.ppgi.ufam.edu.br',0,0);

	$pdfcode = $pdf->output();
	fwrite($arq,$pdfcode);
	fclose($arq);

	header("Location: ".$ofertaDisciplina);

}


// LISTAGEM DE APROVEITAMENTOS
function listarAproveitamentos($buscaNome, $buscaAnoIngresso, $buscaCurso, $buscaStatus, $buscaPeriodo) {
	$Itemid = JRequest::getInt('Itemid', 0);
	$database	=& JFactory::getDBO();
	
	if(!$buscaStatus) $buscaStatus = 0; ?>

	<script language="JavaScript">
		function ConfirmarRegistro(formMatricula, idAluno) {
			var confirmar = confirm('Confirmar Registro?');
			
			if(confirmar == true){
				formMatricula.task.value='registrarMatricula';
				formMatricula.idAluno.value=idAluno;
				formMatricula.idPeriodo.value=formMatricula.periodo.value;
				formMatricula.submit();
			}
		}

		function julgarAproveitamento(form, idAluno) {
		   var idSelecionado = 0;
		   
		   for(i = 0;i < form.idAlunoSelec.length;i++)
				if(form.idAlunoSelec[i].checked) idSelecionado = form.idAlunoSelec[i].value;
		
		   if(idSelecionado > 0){
				form.task.value = 'julgarAproveitamento';
				form.idAluno.value = idSelecionado;
				form.submit();
				return true;
		   } else {
			   alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o.')
		   }
		}
	</script>

	<script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
	<script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();
		});
	</script>

	<link rel="stylesheet" href="components/com_portalsecretaria/assets/css/estilo.css" type="text/css" />
    
	<form name="formAproveitamento" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" method="post">
    
    	<!-- BARRA DE OPÇÕES -->
        <div id="toolbar-box">
            <div class="m">
                <div class="toolbar-list" id="toolbar">
                    <div class="cpanel2">
                        <div class="icon" id="toolbar-preview">
                            <a href="javascript:julgarAproveitamento(document.formAproveitamento)">
                             	<span class="icon-32-preview"></span> <?php echo JText::_( 'Visualizar' ); ?>
                             </a>
                        </div>
                        
                        <div class="icon" id="toolbar-back">
                            <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
                            	<span class="icon-32-back"></span> <?php echo JText::_( 'Voltar' ); ?>
                            </a>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
                
                <div class="pagetitle icon-48-cpanel">
                    <h2>Pedidos de Aproveitamento Solicitados</h2>
                </div>
            </div>
        </div>
        
        <!-- FILTRO DA BUSCA -->
        <fieldset>
        	<legend>Filtros para consulta</legend>
            
            <table width="100%">
            	<tr>
                	<td width="14%">Curso</td>
                    <td width="17%">Status</td>
                    <td width="12%">Período</td>
                    <td width="28%">Nome</td>
                    <td>Ano Ingresso</td>
                </tr>
            </table>
            
            <select name="buscaCurso">
                <option value="0" <?php if($buscaCurso == 0) echo 'SELECTED';?>>Todos</option>
                <option value="1" <?php if($buscaCurso == 1) echo 'SELECTED';?>>Mestrado</option>
                <option value="2" <?php if($buscaCurso == 2) echo 'SELECTED';?>>Doutorado</option>
                <option value="3" <?php if($buscaCurso == 3) echo 'SELECTED';?>>Especial</option>
            </select>
            
            <select name="buscaStatus">
                <option value="0" <?php if($buscaStatus == 0) echo 'SELECTED';?>>N&#227;o Julgados</option>
                <option value="1" <?php if($buscaStatus == 1) echo 'SELECTED';?>>Deferidos</option>
                <option value="2" <?php if($buscaStatus == 2) echo 'SELECTED';?>>Indeferidos</option>
            </select>
            
            <select name="buscaPeriodo">
				<?php
                    $database->setQuery("SELECT * from #__periodos ORDER BY periodo DESC");
                    $periodos = $database->loadObjectList();

                    foreach($periodos as $periodo) { ?>
                    
                    <option value="<?php echo $periodo->id;?>"
                    <?php
                    if($buscaPeriodo == NULL) {
                        if($periodo->status == 1) {
                           echo 'SELECTED';
                           $buscaPeriodo = $periodo->id;
                        }
                    } else {
                        if($buscaPeriodo == $periodo->id) echo 'SELECTED';
                    }
                    ?>>
                    
                    <?php echo $periodo->periodo;?>
                    </option>
                <?php } ?>
			</select>

	        <input id="buscanome" name="buscaNome" size="30" type="text" value="<?php echo $buscaNome;?>" />
                
            <input id="buscaAnoIngresso" name="buscaAnoIngresso" size="4" type="text" value="<?php echo $buscaAnoIngresso;?>" />
    
        	<button type="submit" name="buscar" class="btn btn-primary pull-right" title="Filtra busca">
                <i class="icone-search icone-white"></i> Consultar
            </button>
        </fieldset>
        
        <table class="table table-striped" id="tablesorter-imasters">
            <thead>
                <tr>
                    <th></th>
                    <th>Matr&#237;cula</th>
                    <th>Nome</th>
                    <th>Ingresso</th>
                    <th>Curso</th>
                    <th>Linha de Pesquisa</th>
                </tr>
            </thead>
            
            <tbody>
            <?php
                $curso = array (1 => "Mestrado",2 => "Doutorado",3 => "Especial");
                $curso = array (1 => "mestrado",2 => "doutorado",3 => "especial");
                $linhaPesquisa = array (1 => "bd_ri",2 => "se_es",3 => "ia",4 => "visao",5 => "redes",6 => "otimizacao");

                $extra = "";
                if($buscaNome) $extra = $extra . " AND AL.nome LIKE '%$buscaNome%'";
                if($buscaCurso > 0) $extra = $extra . " AND AL.curso = $buscaCurso";
                if($buscaAnoIngresso) $extra = $extra . " AND AL.anoingresso = '$buscaAnoIngresso'";

                $sql = "SELECT AL.id, AL.nome, AL.matricula, AL.anoingresso, AL.curso, AL.area FROM #__aluno AS AL WHERE id IN (SELECT idAluno FROM #__aproveitamentos WHERE resultado = $buscaStatus AND idPeriodo = $buscaPeriodo) $extra ORDER BY nome";
                $database->setQuery($sql);
                $alunos = $database->loadObjectList();

                if($alunos) {
					foreach($alunos as $aluno) { ?>
                    
                    <tr>
                    <td><input type="radio" name="idAlunoSelec" value="<?php echo $aluno->id;?>" /></td>
                    <td><?php echo $aluno->matricula;?></td>
                    <td><?php echo $aluno->nome;?></td>
                    <td><?php echo dataBr($aluno->anoingresso);?></td>
                    <td><img border='0' src='components/com_portalsecretaria/images/<?php echo $curso[$aluno->curso];?>.gif' title='<?php echo $curso[$aluno->curso];?>' /></td>
                    <td><img border='0' src='components/com_portalsecretaria/images/<?php echo $linhaPesquisa[$aluno->area];?>.gif' title='<?php echo verLinhaPesquisa($aluno->area, 2);?>' /></td>
                </tr>
                
                <?php }
					}
				?>
                
            </tbody>
        </table>
            
        <br />
        <span class="label label-inverse">Total de Pedidos: <b><?php echo sizeof($alunos);?></b></span>
        
        <input name="task" type="hidden" value="aproveitamentos" />
        <input name="idPeriodo" type="hidden" value="" /> 
        <input name="idAlunoSelec" type='hidden' value="0" />
        <input name="idAluno" type="hidden" value="" />
    </form>

<?php }


// JULGAR APROVEITAMENTO DE DISCIPLINA
function julgarAproveitamento($aluno, $nome, $anoingresso, $curso, $status, $periodo) {
	$Itemid = JRequest::getInt('Itemid', 0);
	$database =& JFactory::getDBO();

	$curso = array (1 => "Mestrado",2 => "Doutorado",3 => "Especial");
	$resultado = array (1 => "deferido",2 => "indeferido"); ?>
    
    <!-- SCRIPTS -->
	<script language="JavaScript">
		function Deferir(formAproveitamento, idAproveitamento) {
			var confirmar = confirm('Confirmar deferimento? Esta opera\xE7\xE3o \xE9 irrevers\xEDvel.');
			
			if(confirmar == true) {
				formAproveitamento.task.value='deferirAproveitamento';
				formAproveitamento.idAproveitamento.value=idAproveitamento;
				formAproveitamento.submit();
			}
		}

		function Indeferir(formAproveitamento, idAproveitamento) {
			var confirmar = confirm('Confirmar indeferimento? Esta opera\xE7\xE3o \xE9 irrevers\xEDvel.');
			
			if(confirmar == true) {
				var justificativa = prompt("Qual a justificativa?", "Digite a justificativa aqui")
				formAproveitamento.task.value='indeferirAproveitamento';
				formAproveitamento.idAproveitamento.value=idAproveitamento;
				formAproveitamento.justificativa.value=justificativa;
				formAproveitamento.submit();
			}
		}

		function imprimir(idAluno, periodo) {
			 window.open("index.php?option=com_portalsecretaria&Itemid=190&task=imprimirAproveitamento&idAluno="+idAluno+"&periodo="+periodo,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
		}

		function Limpar(valor, validos) {
			 var result = "";
			 var aux;
			 
			 for (var i=0; i < valor.length; i++) {
				 aux = validos.indexOf(valor.substring(i, i+1));
				 if (aux>=0)
				   result += aux;
			 }
			 return result;
		}

		function Formata(campo,tammax,teclapres,decimal) {
			 var tecla = teclapres.keyCode;
			 vr = Limpar(campo.value,"0123456789");
			 tam = vr.length;
			 dec=decimal;
	
			 if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }
	
			 if (tecla == 8 )
			 { tam = tam - 1 ; }
	
			 if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ) {
	
			 if ( tam <= dec )
			 { campo.value = vr ; }
	
			 if ( (tam > dec) && (tam <= 5) ){
				campo.value = vr.substr( 0, tam - 2 ) + "." + vr.substr( tam - dec, tam ) ; }	
			}		
		}

		function ValidarAdicao(formAproveitamento) {
			if(formAproveitamento.novaDisciplina.value==0) {
			  alert('Deve ser escolhida uma disciplina.')
			  formAproveitamento.novaDisciplina.focus();
			  return false;
			}
			
			if(formAproveitamento.conceito.value=='') {
			  alert('O campo conceito deve ser preenchido.')
			  formAproveitamento.conceito.focus();
			  return false;
			}
		
			return true;
		}

		function voltarForm(form) {
		   form.task.value = 'aproveitamentos';
		   form.submit();
		   return true;
		}
	</script>

	<link rel="stylesheet" href="components/com_portalsecretaria/estilo.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css" />

	<script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script> 
	<script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();	
		});
	</script>

	<form method="post" name="formAproveitamento" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
    
		<div id="toolbar-box"><div class="m">
            <div class="toolbar-list" id="toolbar">
                <div class="cpanel2">
                    <div class="icon" id="toolbar-print">
                        <a href="javascript:imprimir(<?php echo $aluno->id;?>, <?php echo $periodo;?>)">
                        <span class="icon-32-print"></span> <?php echo JText::_( 'Imprimir' ); ?></a>
                    </div>
                    
                    <div class="icon" id="toolbar-back">
                        <a href="javascript:voltarForm(document.formAproveitamento)">
                        <span class="icon-32-back"></span> <?php echo JText::_( 'Voltar' ); ?></a>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
            
            <div class="pagetitle icon-48-cpanel"><h2>Aproveitamento de Disciplina</h2></div>
        </div>
		</div>

        <table width="100%" cellpadding="6">
            <tr style="background-color:#7196d8; color:#FFF; font-weight:bold;">
                <td colspan="8">Dados do Aluno</td>
            </tr>
            <tr>
                <td width="20%" bgcolor="#CCCCCC"><b>Nome</b></td>
                <td width="50%"><?php echo $aluno->nome;?></td>
                <td width="15%" bgcolor="#CCCCCC"><b>Curso</b></td>
                <td width="15%"><?php echo $curso[$aluno->curso];?></td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC"><b>Linha de Pesquisa</b></td>
                <td><?php echo verLinhaPesquisa($aluno->area, 1);?></td>
                <td bgcolor="#CCCCCC"><b>Ano Ingresso</b></td>
                <td><?php $data = explode("-", $aluno->anoingresso); echo $data[1]."/".$data[0]; ?></td>
            </tr>
        </table>
        
		<?php
			$sql = "SELECT idAproveitamento, idDisciplina, idPeriodo, periodoCursado, frequencia, conceito, comprovante, resultado, DATE_FORMAT(dataPedido, '%d/%m/%Y') AS dataPedido,DATE_FORMAT(dataJulgamento, '%d/%m/%Y') AS dataJulgamento, justificativa, tipoAproveitamento, codigo, disciplina, carga_horaria, creditos, ementa FROM #__aproveitamentos WHERE idAluno = $aluno->id AND resultado = 0 AND idPeriodo = $periodo ORDER BY codigo";	
			$database->setQuery($sql);
		    $myDisciplinas = $database->loadObjectList();

			if ($myDisciplinas) { ?>
			
				<br />        
                <table width="100%" cellpadding="6">
                    <tr style="background-color:#7196d8; color:#FFF; font-weight:bold;">
                        <td colspan="10">Disciplinas Solicitadas e Não Julgadas</td>
                    </tr>
                    <tr bgcolor="#eaeaea">
                        <td align="center" width="6%">Julgar</td>
                        <td align="center" width="8%">Pedido</td>
                        <td align="center" width="8%">Código</td>
                        <td align="center" width="9%">Perí­odo</td>
                        <td align="center" width="39%">Disciplina</td>
                        <td align="center" width="8%">Créditos</td>
                        <td align="center" width="5%">C.H.</td>
                        <td align="center" width="8%">Freq</td>
                        <td align="center" width="10%">Conceito</td>
                        <td align="center" width="6%">Tipo</td>
                    </tr>

					<?php 							
					foreach($myDisciplinas as $disciplina) 

						if (($disciplina->tipoAproveitamento == '1') || ($disciplina->tipoAproveitamento == '3')) {			 
							$sql = "SELECT * FROM #__disciplina AS D WHERE id = $disciplina->idDisciplina";
							$database->setQuery($sql);
							$myDisciplinas1 = $database->loadObjectList();

							if($myDisciplinas1) {
							foreach ($myDisciplinas1 as $d1) { ?>
	
    						<tr>
                                <td align="center">
                                    <a href="javascript:Deferir(document.formAproveitamento, <?php echo $disciplina->idAproveitamento;?>)">
                                    <img src="components/com_portalaluno/images/deferido.gif" height="16" width="16" title="Deferir" /></a>
                                    
                                    <a href="javascript:Indeferir(document.formAproveitamento, <?php echo $disciplina->idAproveitamento;?>)">
                                    <img src="components/com_portalaluno/images/indeferido.gif" height="16" width="16" title="Indeferir" /></a>
                                </td>
                                <td align="center"><?php echo $disciplina->dataPedido;?></td>
                                <td align="center"><?php echo $d1->codigo;?></td>
                                <td align="center"><?php echo $disciplina->periodoCursado;?></td>
                                <td align="center"><?php echo $d1->nomeDisciplina;?> 
                                    <a href="<?php echo $disciplina->comprovante;?>" target="_blank">
                                    <img src="components/com_portalaluno/images/icon_pdf.gif" width="16" height="16" border="0"  title="comprovante"/> </a>							
                                </td>
                                <td align="center"><?php echo $d1->creditos;?></td>
                                <td align="center"><?php echo $d1->carga;?></td>
                                <td align="center"><?php echo number_format($d1->frequencia,2);?></td>
                                <td align="center"><?php echo $disciplina->conceito;?></td>
                                <td align="center"><?php echo $disciplina->tipoAproveitamento;?></td>
                            </tr>
    
							<?php }}
							
						} else {							
				 ?>                    
                            <tr>
                                <td align="center">
                                  <a href="javascript:Deferir(document.formAproveitamento, <?php echo $disciplina->idAproveitamento;?>)">
                                  <img src="components/com_portalaluno/images/deferido.gif" height="16" width="16" title="Deferir" /></a>
                                    
                                  <a href="javascript:Indeferir(document.formAproveitamento, <?php echo $disciplina->idAproveitamento;?>)">
                                  <img src="components/com_portalaluno/images/indeferido.gif" height="16" width="16" title="Indeferir" /></a>
                                </td>
                                <td align="center"><?php echo $disciplina->dataPedido;?></td>
                                <td align="center"><?php echo $disciplina->codigo;?></td>
                                <td align="center"><?php echo $disciplina->periodoCursado;?></td>
                                <td align="center"><?php echo $disciplina->disciplina;?> 
                                    <a href="<?php echo $disciplina->comprovante;?>" target="_blank">
                                    <img src="components/com_portalaluno/images/icon_pdf.gif" width="16" height="16" border="0" title="comprovante"/> </a>							
					 <?php if($disciplina->ementa) { ?> <a href="<?php echo $disciplina->ementa;?>" target="_blank">
                                    <img src="components/com_portalaluno/images/icon_pdf.gif" width="16" height="16" border="0"  title="ementa" /> </a>							<?php }?>


                                </td>
                                <td align="center"><?php echo $disciplina->creditos;?></td>
                                <td align="center"><?php echo $disciplina->carga_horaria;?></td>
                                <td align="center"><?php echo number_format($disciplina->frequencia,2);?></td>
                                <td align="center"><?php echo $disciplina->conceito;?></td>
                                <td align="center"><?php echo $disciplina->tipoAproveitamento;?></td>
                           </tr>

						<?php } ?>                    
                </table>                
			<?php } ?>              
        
		<br />        
		<table width="100%">
			<tr>
				<td width="80%">
                	<small><b>LEGENDA DE TIPO DE APROVEITAMENTO:</b><br />
                        1 - Cursada neste Curso de P&#243;s-Gradua&#231;&#227;o como 
                        aluno regular ou aluno especial<br />
                        2 - Cursada em outros Cursos de P&#243;s-Gradua&#231;&#227;o 
                        como aluno especial ou regular<br />
                        3 - Ministrada no IComp/UFAM por pelo menos um semestre para 
                        aproveitamento em Est&#225;gio em Doc&#234;ncia<br />
                        4 - Professores de outras IES reconhecidas que tenham exercido 
                        suas atividades na &#225;rea de Inform&#225;tica<br /> 
                        5 - Aluno que tenha ministrado aulas em cursos de Inform&#225;tica 
                        ou Computa&#231;&#227;o em outras IES reconhecidas<br /> 
                        6 - Cursada em curso de Gradua&#231;&#227;o com equival&#234;ncia 
                        &#224;s disciplinas do Mestrado<br />
					</small>
                </td>
            </tr>
        </table>

		<?php
			$sql = "SELECT A.idAproveitamento, A.resultado, A.justificativa,D.id, DATE_FORMAT(A.dataPedido, '%d/%m/%Y') AS dataPedido, DATE_FORMAT(A.dataJulgamento, '%d/%m/%Y')AS dataJulgamento, D.codigo, D.nomeDisciplina, D.creditos, D.carga, A.comprovante, A.periodoCursado, A.conceito, A.frequencia FROM #__disciplina AS D JOIN #__aproveitamentos AS A ON D.id = A.idDisciplina WHERE idAluno = $aluno->id AND resultado <> 0 ORDER BY codigo";
			$database->setQuery($sql);
			$myDisciplinas = $database->loadObjectList();

			if($myDisciplinas) { ?>

			<table width="100%" border="0" cellspacing="2" cellpadding="2">
				<tr style="background-color: #7196d8;">
					<td style="width: 100%;" colspan="8"><b><font color="#FFFFFF" size="2">Disciplinas Julgadas</font></b></td>
				</tr>
				<tr>
					<td align="center" width="8%" bgcolor="#CCCCCC"><strong>Pedido</strong></td>
					<td align="center" width="8%" bgcolor="#CCCCCC"><strong>Julgamento</strong></td>
					<td align="center" width="10%" bgcolor="#CCCCCC"><strong>Resultado</strong></td>
					<td align="center" width="7%" bgcolor="#CCCCCC"><strong>Justif.</strong></td>
					<td align="center" width="8%" bgcolor="#CCCCCC"><strong>CÃ³digo</strong></td>
					<td align="center" width="43%" bgcolor="#CCCCCC"><strong>Disciplina</strong></td>
					<td align="center" width="8%" bgcolor="#CCCCCC"><strong>Freq (%)</strong></td>
					<td align="center" width="10%" bgcolor="#CCCCCC"><strong>Conceito</strong></td>
				</tr>

				<?php

    		foreach($myDisciplinas as $disciplina) { ?>
				<tr>
					<td align="center"><?php echo $disciplina->dataPedido;?></td>
					<td align="center"><?php echo $disciplina->dataJulgamento;?></td>
					<td align="center"><img
						src="components/com_portalsecretaria/images/<?php echo $resultado[$disciplina->resultado];?>.gif">
					</td>
					<td align="center"><?php if($disciplina->resultado == 2){ ?><img
						src="components/com_portalsecretaria/images/justificativa.gif"
						title="<?php echo $disciplina->justificativa;?>"> <?php } ?></td>
					<td align="center"><?php echo $disciplina->codigo;?></td>
					<td align="center"><?php echo $disciplina->nomeDisciplina;?></td>
					<td align="center"><?php echo number_format($disciplina->frequencia,2);?>
					</td>
					<td align="center"><?php echo $disciplina->conceito;?></td>
				</tr>
				<?php } ?>
			</table>
            
			<?php } ?>
            
			<br />
            
            <input name='buscaNome' type='hidden' value='<?php echo $nome;?>' />
            <input name='buscaAnoIngresso' type='hidden' value='<?php echo $anoingresso;?>' />
            <input name='buscaCurso' type='hidden' value='<?php echo $curso;?>' />
            <input name='buscaStatus' type='hidden' value='<?php echo $status;?>' />
            <input name='buscaPeriodo' type='hidden' value='<?php echo $periodo;?>' />
            <input name='idAluno' type='hidden' value='<?php echo $aluno->id;?>' />
            <input name='task' type='hidden' value='aproveitamentos' />
            <input name='idAproveitamento' type='hidden' value='' />
            <input name='justificativa' type='hidden' value='' />
		</form>        

<?php }


// SALVAR JULGAMENTO, APROVEITAMENTO : SQL
function salvarJulgamentoAproveitamento($idAproveitamento, $justificativa, $valor) {
	$database =& JFactory::getDBO();

	$sql = "SELECT * FROM #__aproveitamentos WHERE idAproveitamento = $idAproveitamento LIMIT 1";
	$database->setQuery($sql);
	$aproveitamento = $database->loadObjectList();

	$sql = "UPDATE #__aproveitamentos SET resultado = $valor, justificativa = '$justificativa', dataJulgamento = '". date("Y/m/d H:m:s")."' WHERE idAproveitamento = $idAproveitamento";
	$database->setQuery($sql);
	$funcionou = $database->Query();

	if($funcionou){

         if($valor == 1) {
            	$sql = "INSERT INTO #__disc_matricula (idDisciplina, idAluno, idPeriodo, frequencia, conceito) VALUES (".$aproveitamento[0]->idDisciplina.", ".$aproveitamento[0]->idAluno.", 0, ".$aproveitamento[0]->frequencia.", '".$aproveitamento[0]->conceito."')";
	     	$database->setQuery($sql);
         	$funcionou = $database->Query();
         }
         if($funcionou) JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
	  else           JError::raiseWarning( 100, 'ERRO: Opera&#231;&#227;o falhou.' );
	}
	else
           JError::raiseWarning( 100, 'ERRO: Opera&#231;&#227;o falhou.' );
}


// IMPRIMIR APROVEITAMENTO
function imprimirAproveitamento($aluno, $periodo) {
	global $mosConfig_lang;
	$database =& JFactory::getDBO();

	$sql = "SELECT A.idAproveitamento, D.id, A.codigo AS codExterno, A.disciplina, A.carga_horaria  AS cargaExterno, A.creditos AS credExterno, D.codigo, A.tipoAproveitamento, A.resultado, D.nomeDisciplina, DATE_FORMAT(A.dataPedido, '%d/%m/%Y') AS dataPedido, D.creditos, D.carga, A.comprovante, A.periodoCursado, A.conceito, A.frequencia FROM #__disciplina AS D JOIN #__aproveitamentos AS A ON D.id = A.idDisciplina WHERE idAluno = $aluno->id AND idPeriodo = $periodo ORDER BY codigo";
	$database->setQuery($sql);
	$disciplinas = $database->loadObjectList();

	$sexo = array ('M' => "Masculino",'F' => "Feminimo");
	$curso = array (1 => "Mestrado",2 => "Doutorado",3 =>"Especial");
	$resultado = array (0 => "Não Julgado",1 => "Deferido",2 =>"Indeferido");

	$chave = md5($aluno->id.$aluno->nome.date("l jS \of F Y h:i:s A"));
	$comprovanteMatricula = "components/com_portalaluno/forms/$chave.pdf";
	$arq = fopen($comprovanteMatricula, 'w') or die("CREATE ERROR");

	$pdf = new Cezpdf();
	$pdf->selectFont('pdf-php/fonts/Helvetica.afm');
	$optionsText = array('justification'=>'center', 'spacing'=>1.5);
	$dados = array('justification'=>'justify', 'spacing'=>1.0);
	$optionsTable = array('fontSize'=>8, 'titleFontSize'=>8, 'xPos'=>'center', 'width'=>615, 'cols'=>array('Código'=>array('width'=>50, 'justification'=>'center'),'Disciplina'=>array('width'=>205), 'Semestre'=>array('width'=>50, 'justification'=>'center'), 'Conceito'=>array('width'=>50, 'justification'=>'center'), 'CR'=>array('width'=>25, 'justification'=>'center'), 'CH'=>array('width'=>25, 'justification'=>'center'), 'Freq(%)'=>array('width'=>50, 'justification'=>'center'), 'Tipo'=>array('width'=>30, 'justification'=>'center'), 'Resultado'=>array('width'=>60, 'justification'=>'center')));

	$idPeriodo = identificarPeriodoID($periodo);

	$pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 490, 720, 75);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 720, 100);
	$pdf->ezText('<b>PODER EXECUTIVO</b>',12,$optionsText);
	$pdf->ezText('<b>MINIST�rIO DA EDUCA��O</b>',10,$optionsText);
	$pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$optionsText);
	$pdf->ezText('<b>INSTITUTO DE COMPUTA��O</b>',10,$optionsText);
	$pdf->ezText('',11,$optionsText);
	$pdf->ezText('<b>PROGRAMA DE P�S-GRADUA��O EM INFORM�TICA</b>',10,$optionsText);
	$pdf->setLineStyle(1);
	$pdf->line(20, 690, 580, 690);
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText('<b>APROVEITAMENTO DE ESTUDO</b>',12,$optionsText);
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText("<b>Aluno(a): </b>". utf8_decode($aluno->nome),10,$dados);
	$pdf->addText(430,632,10,"<b>Matr��cula: </b> $aluno->matricula",0,0);
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText("<b>Linha de Pesquisa:</b> ". utf8_decode(verLinhaPesquisa($aluno->area, 1)),10,$dados);
	$pdf->addText(430,610,10,"<b>Curso:</b> ". $curso[$aluno->curso],0,0);
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText("<b>Orientador:</b> ". utf8_decode(verProfessor($aluno->orientador)),10,$dados);
	$pdf->setLineStyle(1);
	$pdf->line(20, 570, 580, 570);
	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText("<b>Disciplinas Solicitadas</b>",11,$optionsText);
	$pdf->addText(430,584,10,"<b>Per�odo:</b> ". $idPeriodo->periodo,0,0);
	$pdf->ezText('');

	foreach( $disciplinas as $disciplina) {
		if($disciplina->tipoAproveitamento == 1 || $disciplina->tipoAproveitamento == 3)
		   $disciplinasMatriculas[] = array('C�digo'=>$disciplina->codigo, 'Disciplina'=>utf8_decode($disciplina->nomeDisciplina), 'Semestre'=>$disciplina->periodoCursado,'CR'=>$disciplina->creditos, 'CH'=>$disciplina->carga, 'Conceito'=>utf8_decode($disciplina->conceito), 'Freq(%)'=>utf8_decode($disciplina->frequencia), 'Tipo'=>utf8_decode($disciplina->tipoAproveitamento), 'Resultado'=>$resultado[$disciplina->resultado]);
		else
		   $disciplinasMatriculas[] = array('C�digo'=>$disciplina->codExterno, 'Disciplina'=>utf8_decode($disciplina->disciplina), 'Semestre'=>$disciplina->periodoCursado,'CR'=>$disciplina->credExterno, 'CH'=>$disciplina->cargaExterno, 'Conceito'=>utf8_decode($disciplina->conceito), 'Freq(%)'=>utf8_decode($disciplina->frequencia), 'Tipo'=>utf8_decode($disciplina->tipoAproveitamento), 'Resultado'=>$resultado[$disciplina->resultado]);
	}

	$pdf->ezTable($disciplinasMatriculas,$cols,'',$optionsTable);

	$pdf->ezText('');  //Para quebra de linha
	$pdf->ezText('<b>LEGENDA DE TIPO DE APROVEITAMENTO:</b>');  //Para quebra de linha
	$pdf->ezText('1 - Cursada neste Curso de P�s-Gradua��o como aluno regular ou aluno especial');
	$pdf->ezText('2 - Cursada em outros Cursos de P�s-Gradua��o como aluno especial ou regular');
	$pdf->ezText('3 - Ministrada no IComp/UFAM por pelo menos um semestre para aproveitamento em Est�gio em Doc�ncia');
	$pdf->ezText('4 - Professores de outras IES reconhecidas que tenham exercido suas atividades na �rea de Inform�tica');
	$pdf->ezText('5 - Aluno que tenha ministrado aulas em cursos de Inform�tica ou Computa��o em outras IES reconhecidas');
	$pdf->ezText('6 - Cursada em curso de Gradua��o com equival�ncia �s disciplinas do Mestrado');
	$pdf->addText(50,80,10,'PARECER DO PPGI-IComp:______________________, em reuni�o realizada no dia_____/____/____.',0,0);
	$pdf->addText(85,120,8,'Assinatura do Aluno',0,0);
	$pdf->addText(255,120,8,'Assinatura do Orientador',0,0);
	$pdf->addText(415,120,8,'Assinatura do Coordenador do PPGI',0,0);
	$pdf->line(40, 130, 210, 130);
	$pdf->line(220, 130, 380, 130);
	$pdf->line(390, 130, 560, 130);
	$pdf->line(20, 55, 580, 55);
	$pdf->addText(80,40,8,'Av. Rodrigo Ot�vio, 6.200 - Campus Universit�rio Senador Arthur Virg��lio Filho - CEP 69077-000 - Manaus, AM, Brasil',0,0);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 140, 30, 8, 8);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 229, 30, 8, 8);
	$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 383, 30, 8, 8);
	$pdf->addText(150,30,8,'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br',0,0);

	$pdfcode = $pdf->output();
	fwrite($arq,$pdfcode);
	fclose($arq);

	header("Location: ".$comprovanteMatricula);
}
?>

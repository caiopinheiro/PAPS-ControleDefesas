<?php

function notifyMe($idAluno){

    $database	=& JFactory::getDBO();
    $cursos = array(1 => "Mestrado", 2 => "Doutorado" );
    $regimes = array(1 => "Tempo Integral", 2 => "Tempo Parcial" );
    
    $sql = "SELECT nome,email,cidade,uf,area,fim, telresidencial, cursodesejado,regime, tituloproposta FROM #__aluno WHERE id = $idAluno";
    $database->setQuery( $sql );
	$database->Query();
	$alunos = $database->loadObjectList();

    // subject
    $subject  = "Matricula no Mestrado/Doutorado no PPGI";

    $mime_boundary = "<<<--==-->>>";
    
    // message
    $message .= "Mais um candidato se cadastrou no site de matricula do mestrado/doutorado do PPGI.\r\n";
    $message .= "Nome: ".$alunos[0]->nome."\r\n";
    $message .= "E-mail: ".$alunos[0]->email."\r\n";
    $message .= "Telefone: ".$alunos[0]->telresidencial."\r\n";
    $message .= "Cidade: ".$alunos[0]->cidade." - UF: ".$alunos[0]->uf."\r\n";
    $message .= "Linha de Pesquisa: ".verLinhaPesquisa($alunos[0]->area,1)."\r\n";
    $message .= "Curso Desejado: ".$cursos[$alunos[0]->cursodesejado]."\r\n";
    $message .= "Regime de Dedicacao: ".$regimes[$alunos[0]->regime]."\r\n";
    $message .= "Titulo da proposta: ".$alunos[0]->tituloproposta."\r\n";
    $message .= "Data da Matricula: ".date(DATE_RFC822)."\r\n";
    $message .= $mime_boundary."\r\n";

    $email = "ariloclaudio@gmail.com";

	JUtility::sendMail($alunos[0]->email, $alunos[0]->nome, "arilo@dcc.ufam.edu.br", $subject, $message);
}

/////////////////////////////////////////

function imprimirDeclaracao($aluno) {

	global $mosConfig_lang;
    $database	=& JFactory::getDBO();

	$curso = array (1 => "MESTRADO EM INFORMÁTICA",2 => "DOUTORADO EM INFORMÁTICA",3 => "MESTRADO EM INFORMÁTICA");

    $mes = date("m");
    switch ($mes){

    case 1: $mes = "Janeiro"; break;
    case 2: $mes = "Fevereiro"; break;
    case 3: $mes = "Março"; break;
    case 4: $mes = "Abril"; break;
    case 5: $mes = "Maio"; break;
    case 6: $mes = "Junho"; break;
    case 7: $mes = "Julho"; break;
    case 8: $mes = "Agosto"; break;
    case 9: $mes = "Setembro"; break;
    case 10: $mes = "Outubro"; break;
    case 11: $mes = "Novembro"; break;
    case 12: $mes = "Dezembro"; break;

    }
    $chave = md5($aluno->id.$aluno->nome.date("l jS \of F Y h:i:s A"));

	$declaracaoMatricula = "components/com_portalaluno/forms/$chave.pdf";
	$arq = fopen($declaracaoMatricula, 'w') or die("CREATE ERROR");

    $pdf = new Cezpdf();
    $pdf->selectFont('pdf-php/fonts/Helvetica.afm');
    $optionsText = array('justification'=>'center', 'spacing'=>2.0);
    $header = array('justification'=>'center', 'spacing'=>1.3);
    $dados = array('justification'=>'justify', 'spacing'=>1.0);

    $pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 490, 740, 70, 83, 100);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 740);
    $pdf->ezText('<b>PODER EXECUTIVO</b>',12,$header);
    $pdf->ezText('<b>MINISTÉRIO DA EDUCAÇÃO</b>',10,$header);
    $pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$header);
    $pdf->ezText('<b>INSTITUTO DE COMPUTAÇÃO</b>',10,$header);
    $pdf->ezText('',11,$optionsText);
    $pdf->ezText('<b>PROGRAMA DE PÓS-GRADUAÇÃO EM INFORMÁTICA</b>',10,$header);
    $pdf->addText(495,715,8,"<b>Data:</b> ".date("d/m/Y"),0,0);
    $pdf->addText(495,725,8,"<b>Hora:</b> ".date("H:i"),0,0);
    $pdf->setLineStyle(1);
    $pdf->line(20, 690, 580, 690);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('<b>DECLARAÇÃO DE MATRÍCULA</b>',12,$optionsText);

    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha


    $tag = "<b>DECLARAMOS</b>, para os devidos fins, que <b>".utf8_decode(strtoupper($aluno->nome))."</b> é aluno(a)";

    if($aluno->curso == 3)
       $tag = $tag." especial";
    else
       $tag = $tag. " regularmente matriculado(a)";

    $tag = $tag. ", sob a matrícula n. $aluno->matricula, no Curso de <b>".$curso[$aluno->curso]."</b> na área de <b>CIÊNCIA DA COMPUTAÇÃO</b> do Programa de Pós-Graduação em Informática da Universidade Federal do Amazonas.";

    $pdf->ezText($tag,11,$optionsText);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("PROGRAMA DE PÓS-GRADUAÇÃO EM INFORMÁTICA DA UNIVERSIDADE FEDERAL DO AMAZONAS, em Manaus, ".date("d")." de ".$mes." de ".date("Y"),8,$dados);
    $pdf->line(20, 55, 580, 55);
    $pdf->addText(80,40,8,'Av. Rodrigo Otávio, 6.200 • Campus Universitário Senador Arthur Virgílio Filho • CEP 69077-000 •  Manaus, AM, Brasil',0,0);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 140, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 229, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 383, 30, 8, 8);
    $pdf->addText(150,30,8,'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br',0,0);

    $pdfcode = $pdf->output();
    fwrite($arq,$pdfcode);
	fclose($arq);

	header("Location: ".$declaracaoMatricula);

}

///////////////////////////

function historicoAluno($aluno) {

	global $mosConfig_lang;
    $database	=& JFactory::getDBO();

	$sexo = array (1 => 'Masculino',2 => 'Feminimo');
	$curso = array (1 => 'Mestrado',2 => 'Doutorado',3 =>'Especial');
	$fasesPesquisa = array (1 => '1. DefiniÃ§Ã£o do Tema', 2 => '2. PreparaÃ§Ã£o e ApresentaÃ§Ã£o do Projeto', 3 => '3. Pesquisa BibliogrÃ¡fica', 4 => '4. Desenvolvimento de Projeto', 5 => '5. ConclusÃ£o', 6 => '6. RedaÃ§Ã£o Final', 7 => '7. ApresentaÃ§Ã£o');

	$mes = array ("01" => "Janeiro","02" => "Fevereiro","03" => "Mar&#231;o","04" => "Abril","05" => "Maio","06" => "Junho","07" => "Julho","08" => "Agosto","09" => "Setembro","10" => "Outubro","11" => "Novembro","12" => "Dezembro");

    $sql = "SELECT M.idDisciplina, D.nomeDisciplina, periodo, codigo, conceito, frequencia, carga, creditos FROM #__disc_matricula AS M JOIN #__disciplina AS D JOIN #__periodos as P ON D.id = M.idDisciplina AND P.id = M.idPeriodo WHERE idAluno = $aluno->id AND M.idPeriodo <> 0 ORDER BY periodo, codigo";
    $database->setQuery( $sql );
    $disciplinasCursadas = $database->loadObjectList();

    $sql = "SELECT M.idDisciplina, D.nomeDisciplina, codigo, conceito, frequencia, carga, creditos FROM #__disc_matricula AS M JOIN #__disciplina AS D ON D.id = M.idDisciplina WHERE idAluno = $aluno->id AND M.idPeriodo = 0 ORDER BY codigo";
    $database->setQuery( $sql );
    $disciplinasAproveitadas = $database->loadObjectList();

    $sql = "SELECT MAX(semestre), fasePesquisa FROM #__matricula WHERE idAluno = $aluno->id";
    $database->setQuery( $sql );
    $fases = $database->loadObjectList();

    $sql = "SELECT * FROM #__banca WHERE idAluno = $aluno->id AND tipoDefesa = 'Q' ORDER BY presidente DESC, nomeMembro ASC";
    $database->setQuery( $sql );
    $bancaqualificacao = $database->loadObjectList();

    $sql = "SELECT * FROM #__banca WHERE idAluno = $aluno->id AND tipoDefesa = 'T' ORDER BY presidente DESC, nomeMembro ASC";
    $database->setQuery( $sql );
    $bancatese = $database->loadObjectList();

    $chave = md5($aluno->id.$aluno->nome.date("l jS \of F Y h:i:s A"));
   	$historico = "components/com_portalsecretaria/reports/".$chave.".pdf";
	$arq = fopen($historico, 'w') or die('CREATE ERROR');

    $pdf = new Cezpdf();
    $pdf->selectFont('pdf-php/fonts/Helvetica.afm');
    $optionsText = array('justification'=>'center', 'spacing'=>1.3);
    $dados = array('justification'=>'justify', 'spacing'=>1.0);
    $optionsTable = array('fontSize'=>10, 'titleFontSize'=>12, 'xPos'=>'center', 'width'=>500, 'cols'=>array('Código'=>array('width'=>60, 'justification'=>'center'),'Período'=>array('width'=>50, 'justification'=>'center'),'Disciplina'=>array('width'=>285), 'Conceito'=>array('width'=>50, 'justification'=>'center'), 'FR%'=>array('width'=>45, 'justification'=>'center'), 'CR'=>array('width'=>30, 'justification'=>'center'), 'CH'=>array('width'=>30, 'justification'=>'center')));

    $pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 490, 740, 70, 83, 100);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 740);
    $pdf->ezText('<b>PODER EXECUTIVO</b>',12,$optionsText);
    $pdf->ezText('<b>MINISTÉRIO DA EDUCAÇÃO</b>',10,$optionsText);
    $pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$optionsText);
    $pdf->ezText('<b>INSTITUTO DE COMPUTAÇÃO</b>',10,$optionsText);
    $pdf->ezText('',11,$optionsText);
    $pdf->ezText('<b>PROGRAMA DE PÓS-GRADUAÇÃO EM INFORMÁTICA</b>',10,$optionsText);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->setLineStyle(2);
    $pdf->line(20, 700, 580, 700);
    $pdf->ezText('<b>HISTÓRICO ESCOLAR</b>',12,$optionsText);

    if($aluno->curso == 2)
       $pdf->ezText("Curso de Doutorado em Informática, credenciado pela Portaria nº. 087/2008 e publicada no Diário Oficial da União de 18/01/2008.",7.5,$optionsText);
    else
       $pdf->ezText("Curso de Mestrado em Informática, credenciado pela Portaria nº. 1585 - CAPES/MEC, de 20/06/2003  e publicada no Diário Oficial da União de 23/06/2003.",7.5,$optionsText);

    $pdf->setLineStyle(1);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('<b>DADOS PESSOAIS</b>',11,$optionsText);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Nome: </b>". utf8_decode($aluno->nome),10,$dados);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Filiação:</b> ". utf8_decode($aluno->nomemae),0,0);
    $pdf->ezText("                ". utf8_decode($aluno->nomepai),0,0);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Nacionalidade:</b> ". utf8_decode($aluno->pais),10,$dados);
    $pdf->addText(400,548,10,"<b>Data de Nascimento:</b> $aluno->datanascimento",0,0);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Graduação:</b> ". utf8_decode($aluno->cursograd),10,$dados);
    $pdf->ezText("<b>Insituição:</b>  ". utf8_decode($aluno->instituicaograd),10,$dados);
    $pdf->addText(400,514,10,"<b>Ano de Conclusão:</b> ". utf8_decode($aluno->egressograd),0,0);
    $pdf->setLineStyle(1);
    $pdf->line(20, 505, 580, 505);

    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('<b>PÓS-GRADUAÇÃO</b>',11,$optionsText);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('<b>Área de Concentração:</b> Ciência da Computação',10,$dados);
    $pdf->addText(280,462,10,"<b>Linha de Pesquisa:</b> ". utf8_decode(verLinhaPesquisa($aluno->area, 1)),0,0);
    $pdf->ezText("<b>Nível:</b> ". $curso[$aluno->curso],10,$dados);
    $pdf->addText(280,450,10,"<b>Mês/Ano de Ingresso:</b> $aluno->anoingresso",0,0);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Orientador(a):</b> ". utf8_decode(verProfessor($aluno->orientador)),10,$dados);
    $pdf->line(20, 420, 580, 420);

    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('<b>PROFICIÊNCIA EM IDIOMA ESTRANGEIRO</b>',11,$optionsText);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Idioma:</b> ". utf8_decode($aluno->idiomaExameProf),10,$dados);
    $pdf->addText(210,375,10,"<b>Data do Exame:</b> $aluno->dataExameProf",0,0);
    $pdf->addText(390,375,10,"<b>Situação:</b> $aluno->conceitoExameProf",0,0);
    $pdf->line(20, 365, 580, 365);

    if($aluno->curso == 2){

       $pdf->ezText('');  //Para quebra de linha
       $pdf->ezText('<b>EXAME DE QUALIFICAÇÃO I</b>',11,$optionsText);
       $pdf->ezText('');  //Para quebra de linha
       $pdf->ezText("<b>Tema:</b> ". utf8_decode($aluno->tituloQual1),10,$dados);
       $pdf->ezText("<b>Data do Exame:</b> $aluno->dataQual1",10,$dados);
       $pdf->addText(190,310,10,"<b>Situação:</b> $aluno->conceitoQual1",0,0);
       $pdf->addText(320,310,10,"<b>Examinador(a):</b> ". utf8_decode($aluno->examinadorQual1),0,0);
       $pdf->ezText('');  //Para quebra de linha
       $pdf->addText(280,236,10,"<b>Situação:</b> $aluno->conceitoQual2",0,0);
    }
    else{
       $pdf->addText(280,311,10,"<b>Situação:</b> $aluno->conceitoQual2",0,0);
    }



    if($aluno->curso == 2)
        $pdf->ezText('<b>EXAME DE QUALIFICAÇÃO II</b>',11,$optionsText);
    else{
        $pdf->ezText('');  //Para quebra de linha
        $pdf->ezText('<b>EXAME DE QUALIFICAÇÃO</b>',11,$optionsText);
    }

    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Tema:</b> ". utf8_decode($aluno->tituloQual2),10,$dados);
    $pdf->ezText("<b>Data do Exame:</b> $aluno->dataQual2",10,$dados);
    $pdf->ezText("<b>Comissão Examinadora:</b> ",10,$dados);

    foreach( $bancaqualificacao as $banca )
	{
        $pdf->ezText("                                           ".utf8_decode($banca->nomeMembro),10,$dados);
    }

    $pdf->ezText('');  //Para quebra de linha
    if($aluno->curso == 2) $pdf->ezText('<b>DEFESA DE TESE</b>',11,$optionsText);
    else $pdf->ezText('<b>DEFESA DE DISSERTAÇÃO</b>',11,$optionsText);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Tema:</b> ". utf8_decode($aluno->tituloTese),10,$dados);
    $pdf->ezText("<b>Data do Exame:</b> $aluno->dataTese                                  <b>Situação:</b> $aluno->conceitoTese",10,$dados);
    $pdf->ezText('<b>Comissão Examinadora:</b> ',10,$dados);

    foreach( $bancatese as $banca )
	{
        $pdf->ezText("                                           ".utf8_decode($banca->nomeMembro),10,$dados);
    }

    $pdf->line(20, 38, 580, 38);
    $pdf->addText(80,30,8,'Av. Rodrigo Otávio, 6.200 • Campus Universitário Senador Arthur Virgílio Filho • CEP 69077-000 •  Manaus, AM, Brasil',0,0);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 140, 20, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 229, 20, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 383, 20, 8, 8);
    $pdf->addText(150,20,8,'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br',0,0);

    $pdf->ezNewPage();

    $pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 490, 740, 70, 83, 100);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 740);
    $pdf->ezText('<b>PODER EXECUTIVO</b>',12,$optionsText);
    $pdf->ezText('<b>MINISTÉRIO DA EDUCAÇÃO</b>',10,$optionsText);
    $pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$optionsText);
    $pdf->ezText('<b>INSTITUTO DE COMPUTAÇÃO</b>',10,$optionsText);
    $pdf->ezText('',11,$optionsText);
    $pdf->ezText('<b>PROGRAMA DE PÓS-GRADUAÇÃO EM INFORMÁTICA</b>',10,$optionsText);
    $pdf->addText(495,715,8,"<b>Data:</b> ".date("d/m/Y"),0,0);
    $pdf->addText(495,725,8,"<b>Hora:</b> ".date("H:i"),0,0);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->setLineStyle(2);
    $pdf->line(20, 700, 580, 700);
    $pdf->ezText('<b>HISTÓRICO ESCOLAR</b>',12,$optionsText);

    if($aluno->curso == 2)
       $pdf->ezText("Curso de Doutorado em Informática, credenciado pela Portaria nº. 087/2008 e publicada no Diário Oficial da União de 18/01/2008.",7.5,$optionsText);
    else
       $pdf->ezText("Curso de Mestrado em Informática, credenciado pela Portaria nº. 1585 - CAPES/MEC, de 20/06/2003  e publicada no Diário Oficial da União de 23/06/2003.",7.5,$optionsText);

    $pdf->line(20, 55, 580, 55);
    $pdf->addText(80,40,8,'Av. Rodrigo Otávio, 6.200 • Campus Universitário Senador Arthur Virgílio Filho • CEP 69077-000 •  Manaus, AM, Brasil',0,0);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 140, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 229, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 383, 30, 8, 8);
    $pdf->addText(150,30,8,'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br',0,0);

    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('<b>DISCIPLINAS CURSADAS</b>',11,$optionsText);

    $pdf->ezText('');

	foreach( $disciplinasCursadas as $disc )
	{
      $disciplinasC[] = array('Código'=>$disc->codigo,'Período'=>$disc->periodo, 'Disciplina'=>utf8_decode($disc->nomeDisciplina), 'Conceito'=>$disc->conceito,'FR%'=>number_format($disc->frequencia, 2),'CR'=>$disc->creditos, 'CH'=>$disc->carga);
    }

    $pdf->ezTable($disciplinasC,$cols,'',$optionsTable);

    if($disciplinasAproveitadas <> NULL){

	   foreach( $disciplinasAproveitadas as $disc )
	   {
           $disciplinasA[] = array('Código'=>$disc->codigo,'Disciplina'=>utf8_decode($disc->nomeDisciplina), 'Conceito'=>$disc->conceito,'FR%'=>number_format($disc->frequencia, 2),'CR'=>$disc->creditos, 'CH'=>$disc->carga);
       }

       $pdf->ezText('');  //Para quebra de linha
       $pdf->ezText('<b>DISCIPLINAS APROVEITADAS</b>',11,$optionsText);
       $pdf->ezText('');  //Para quebra de linha
       $pdf->ezTable($disciplinasA,$cols,'',$optionsTable);
    }

    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('<b>Legenda:</b> A = Excelente	| B = Ótimo	| C = Bom | I = Reprovado | X = Trancamento',8,$optionsText);
    $pdf->setLineStyle(1);
    $pdf->line(70, 110, 270, 110);
    $pdf->line(310, 110, 550, 110);
    $pdf->addText(110,100,8,'<b>Prof. Dr. Edleno Silva de Moura</b>',0,0);
    $pdf->addText(125,90,8,'Coordenador do Programa',0,0);
    $pdf->addText(360,100,8,'<b>Profª. Drª. Maria Fulgência C. L. Bandeira</b>',0,0);
    $pdf->addText(330,90,8,'Diretora do Departamento de Pós-Graduação - PROPESP',0,0);

    $pdfcode = $pdf->output();
    fwrite($arq,$pdfcode);
	fclose($arq);

	header("Location: ". $historico);

}

/////////////////////////////////////////

function imprimirMatricula($aluno,$disciplinas,$matricula) {

	global $mosConfig_lang;
    $database	=& JFactory::getDBO();

	$sexo = array ('M' => "Masculino",'F' => "Feminimo");
	$curso = array (1 => "Mestrado",2 => "Doutorado",3 =>"Especial");
	$pesquisa = array (1 => "Disserta&#231;&#227;o",2 => "Tese",3 =>"Pesquisa");
	$regime = array (1 => "Integral",2 => "Parcial");

    $fase = array (1 => 'Definição do Tema', 2 => 'Preparação e Apresentação do Projeto',
    3 => 'Pesquisa Bibliográfica',
    4 => 'Desenvolvimento de Projeto',
    5 =>  'Conclusão',
    6 => 'Redação Final',
    7 => "Apresentação - Data Prevista: ".$matricula[0]->dataPrevista."");

    $mes = date("m");
    switch ($mes){

    case 1: $mes = "Janeiro"; break;
    case 2: $mes = "Fevereiro"; break;
    case 3: $mes = "Março"; break;
    case 4: $mes = "Abril"; break;
    case 5: $mes = "Maio"; break;
    case 6: $mes = "Junho"; break;
    case 7: $mes = "Julho"; break;
    case 8: $mes = "Agosto"; break;
    case 9: $mes = "Setembro"; break;
    case 10: $mes = "Outubro"; break;
    case 11: $mes = "Novembro"; break;
    case 12: $mes = "Dezembro"; break;

    }

    $chave = md5($aluno->id.$aluno->nome.date("l jS \of F Y h:i:s A"));
	$comprovanteMatricula = "components/com_portalaluno/forms/$chave.pdf";
	$arq = fopen($comprovanteMatricula, 'w') or die("CREATE ERROR");

    $pdf = new Cezpdf();
    $pdf->selectFont('pdf-php/fonts/Helvetica.afm');
    $optionsText = array('justification'=>'center', 'spacing'=>1.5);
    $dados = array('justification'=>'justify', 'spacing'=>1.0);
    $optionsTable = array('fontSize'=>8, 'titleFontSize'=>8, 'xPos'=>'center', 'width'=>560, 'cols'=>array('Código'=>array('width'=>50, 'justification'=>'center'),'Disciplina'=>array('width'=>205), 'Turma'=>array('width'=>35, 'justification'=>'center'), 'Professor'=>array('width'=>125), 'CR'=>array('width'=>25, 'justification'=>'center'), 'CH'=>array('width'=>25, 'justification'=>'center'), 'Horário'=>array('width'=>95, 'justification'=>'center')));

    $pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 490, 740, 70, 83, 100);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 740);
    $pdf->ezText('<b>PODER EXECUTIVO</b>',12,$optionsText);
    $pdf->ezText('<b>MINISTÉRIO DA EDUCAÇÃO</b>',10,$optionsText);
    $pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$optionsText);
    $pdf->ezText('<b>INSTITUTO DE COMPUTAÇÃO</b>',10,$optionsText);
    $pdf->ezText('',11,$optionsText);
    $pdf->ezText('<b>PROGRAMA DE PÓS-GRADUAÇÃO EM INFORMÁTICA</b>',10,$optionsText);
    $pdf->addText(495,715,8,"<b>Data:</b> ".date("d/m/Y"),0,0);
    $pdf->addText(495,725,8,"<b>Hora:</b> ".date("H:i"),0,0);
    $pdf->setLineStyle(1);
    $pdf->line(20, 690, 580, 690);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('<b>COMPROVANTE DE MATRÍCULA</b>',12,$optionsText);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Aluno(a): </b>". utf8_decode($aluno->nome),10,$dados);
    $pdf->addText(430,632,10,"<b>Matrícula: </b> $aluno->matricula",0,0);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Linha de Pesquisa:</b> ". utf8_decode(verLinhaPesquisa($aluno->area, 1)),10,$dados);
    $pdf->addText(430,610,10,"<b>Curso:</b> ". $curso[$aluno->curso],0,0);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Orientador:</b> ". utf8_decode(verProfessor($aluno->orientador)),10,$dados);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Fase da Dissertação/Tese:</b> ".$fase[$matricula[0]->fasePesquisa],10,$dados);
    $pdf->addText(430,562,10,"<b>Período: </b> ".$matricula[0]->semestre,0,0);
    $pdf->setLineStyle(1);
    $pdf->line(20, 550, 580, 550);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Disciplinas Matriculadas</b>",11,$optionsText);
    $pdf->ezText('');


    foreach( $disciplinas as $disciplina)
    {
        $disciplinasMatriculas[] = array('Código'=>$disciplina->codigo, 'Disciplina'=>utf8_decode($disciplina->nomeDisciplina), 'Turma'=>$disciplina->turma,'CR'=>$disciplina->creditos, 'CH'=>$disciplina->carga, 'Professor'=>utf8_decode($disciplina->professor), 'Horário'=>utf8_decode($disciplina->horario));
    }

    $pdf->ezTable($disciplinasMatriculas,$cols,'',$optionsTable);

    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->addText(50,200,10,'<b>De acordo:</b>',0,0);
    $pdf->addText(85,120,8,'Assinatura do Aluno',0,0);
    $pdf->addText(255,120,8,'Assinatura do Orientador',0,0);
    $pdf->addText(415,120,8,'Assinatura do Coordenador do PPGI',0,0);
    $pdf->line(40, 130, 210, 130);
    $pdf->line(220, 130, 380, 130);
    $pdf->line(390, 130, 560, 130);
    $pdf->addText(235,90,8,"<b>Manaus, ".date("d")." de ".$mes." de ".date("Y")."</b>",0,0);
    $pdf->line(20, 55, 580, 55);
    $pdf->addText(80,40,8,'Av. Rodrigo Otávio, 6.200 • Campus Universitário Senador Arthur Virgílio Filho • CEP 69077-000 •  Manaus, AM, Brasil',0,0);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 140, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 229, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 383, 30, 8, 8);
    $pdf->addText(150,30,8,'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br',0,0);

    $pdfcode = $pdf->output();
    fwrite($arq,$pdfcode);
	fclose($arq);

	header("Location: ".$comprovanteMatricula);

}

//////////////////////////////////////////////////////////////

function imprimirOferta($disciplinas, $periodo)
    {
    $Itemid = JRequest::getInt('Itemid', 0);
    
	$ofertaDisciplina = "components/com_portalaluno/forms/oferta".$periodo->id.".pdf";
	$arq = fopen($ofertaDisciplina, 'w') or die("CREATE ERROR");

    $pdf = new Cezpdf('a4','landscape');
    $pdf->selectFont('pdf-php/fonts/Helvetica.afm');
    $optionsText = array('justification'=>'center', 'spacing'=>1.5);
    $dados = array('justification'=>'justify', 'spacing'=>1.0);
    $optionsTable = array('fontSize'=>8, 'titleFontSize'=>8, 'xPos'=>'center', 'width'=>800, 'cols'=>array('Código'=>array('width'=>50, 'justification'=>'center'),'Disciplina'=>array('width'=>235), 'Turma'=>array('width'=>35, 'justification'=>'center'), 'Professor'=>array('width'=>140), 'CR'=>array('width'=>25, 'justification'=>'center'), 'CH'=>array('width'=>25, 'justification'=>'center'), 'Horário'=>array('width'=>145, 'justification'=>'center'), 'Sala'=>array('width'=>145, 'justification'=>'center')));

    $pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 740, 470, 70, 83, 100);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 470);
    $pdf->ezText('<b>PODER EXECUTIVO</b>',12,$optionsText);
    $pdf->ezText('<b>MINISTÉRIO DA EDUCAÇÃO</b>',10,$optionsText);
    $pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$optionsText);
    $pdf->ezText('<b>INSTITUTO DE COMPUTAÇÃO</b>',10,$optionsText);
    $pdf->ezText('',11,$optionsText);
    $pdf->ezText('<b>PROGRAMA DE PÓS-GRADUAÇÃO EM INFORMÁTICA</b>',10,$optionsText);
    $pdf->addText(495,715,8,"<b>Data:</b> ".date("d/m/Y"),0,0);
    $pdf->addText(495,725,8,"<b>Hora:</b> ".date("H:i"),0,0);
    $pdf->setLineStyle(2);
    $pdf->line(20, 450, 820, 450);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>OFERTA DE DISCIPLINAS DO PERÍODO ".$periodo->periodo."</b>",14,$optionsText);
    $pdf->ezText('');

    foreach( $disciplinas as $disciplina)
    {
        $disciplinasMatriculas[] = array('Código'=>$disciplina->codigo, 'Disciplina'=>utf8_decode($disciplina->nomeDisciplina), 'Turma'=>$disciplina->turma,'CR'=>$disciplina->creditos, 'CH'=>$disciplina->carga, 'Professor'=>utf8_decode($disciplina->professor), 'Horário'=>utf8_decode($disciplina->horario), 'Sala'=>utf8_decode($disciplina->sala));
    }

    $pdf->ezTable($disciplinasMatriculas,$cols,'',$optionsTable);

    $pdf->line(20, 55, 820, 55);
    $pdf->addText(190,40,8,'Av. Rodrigo Otávio, 6.200 • Campus Universitário Senador Arthur Virgílio Filho • CEP 69077-000 •  Manaus, AM, Brasil',0,0);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 250, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 339, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 493, 30, 8, 8);
    $pdf->addText(260,30,8,'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br',0,0);

    $pdfcode = $pdf->output();
    fwrite($arq,$pdfcode);
	fclose($arq);

	header("Location: ".$ofertaDisciplina);

}


?>

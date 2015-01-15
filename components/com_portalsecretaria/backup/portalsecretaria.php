<?php

$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

include_once("components/com_portalsecretaria/portalsecretaria.html.php");
include_once("components/com_portalsecretaria/selecaoppgi.php");
include_once("components/com_portalsecretaria/periodoppgi.php");
include_once("components/com_portalsecretaria/alunoppgi.php");
include_once("components/com_portalsecretaria/professor.php");
include_once("components/com_portalsecretaria/bolsasppgi.php");
include_once("components/com_portalsecretaria/jubilamento.php");
include_once("components/com_portalsecretaria/prorrogacao.php");
include_once("components/com_portalsecretaria/trancamento.php");
//include_once("components/com_portalsecretaria/reserva.php");
include_once("components/com_portalsecretaria/banca.php");
$document->addScript("components/com_portalsecretaria/jquery/javascripts/jquery.ui.datepicker-pt-BR.js");
include_once('pdf-php/class.ezpdf.php');
//include_once("pdf-php/class.ezpdf.php");

$task = JRequest::getCmd('task', false);
$Itemid = JRequest::getInt('Itemid', 0);
$database	=& JFactory::getDBO();

switch ($task) {

  /*Caso seja escolhida a validação de login*/
  case "validalogin":
	$senha = $_POST['senha'];
	$email = $_POST['email'];
	if(secretaria($email, $senha)){
	  listarServicos();
	} else {
	  formLogin(true);
	}
  break;
  
  /*Lista os serviços*/
  case "listarservicos":
	listarServicos();
  break;
  
  /***--- Tasks referentes a Operações de Gerenciamento de Candidatos ---***/
  case "avaliarcandidatos":
	$periodo = JRequest::getVar('periodo');
	avaliarCandidatos($periodo);
  break;
  
  case "aprovar":
	$periodo = JRequest::getVar('periodo');
	$ano = JRequest::getVar('ano');
	$idCandidato = JRequest::getVar('idcandidato');
	aprovarCandidato($idCandidato, $ano);
  break;
  
  case "salvarAprovacao":
	$idCandidato = JRequest::getVar('idcandidato');
	$periodo = JRequest::getVar('periodo');
	$ano = JRequest::getVar('ano');
	salvarAprovacao($idCandidato);
	avaliarCandidatos($ano);
  break;
  
  case "reprovar":
	$periodo = JRequest::getVar('periodo');
	$ano = JRequest::getVar('ano');
	$idCandidato = JRequest::getVar('idcandidato');
	reprovarCandidato($idCandidato);
	avaliarCandidatos($ano);
  break;
  
  case "listarnotificacoes":
	$mes = JRequest::getVar('fecha');
	listarNotificacoes($mes);
  break;
  
  /***--- Tasks referentes a Operações de Gerenciamento de Alunos ---***/
  case "alunos":
	listarAlunosPPGI();
  break;
  
  case "addAluno":
	$nome = JRequest::getVar('buscaNome');
	$status = JRequest::getVar('buscaStatus');
	$anoingresso = JRequest::getVar('buscaAnoIngresso');
	$curso = JRequest::getVar('buscaCurso');
	cadastrarNovoAluno(NULL, $nome, $status, $anoingresso, $curso);
  break;
  
  case "editarAluno":
	$nome = JRequest::getVar('buscaNome');
	$status = JRequest::getVar('buscaStatus');
	$anoingresso = JRequest::getVar('buscaAnoIngresso');
	$curso = JRequest::getVar('buscaCurso');
	$idAluno = JRequest::getCmd('idAluno', false);
	$aluno = identificarAlunoID($idAluno);
	editarAluno($aluno, $nome, $status, $anoingresso, $curso);
  break;
  
  case "salvarCadastro":
  if(salvarCadastro())
	listarAlunosPPGI("", 0, "", 0);
  else{
	JError::raiseWarning( 100, 'ERRO: Cadastro n&#227;o realizado. Este e-mail j&#225; est&#225; cadastrado no sistema.' );
	$nome = JRequest::getVar('buscaNome');
	$status = JRequest::getVar('buscaStatus');
	$anoingresso = JRequest::getVar('buscaAnoIngresso');
	$curso = JRequest::getVar('buscaCurso');
	cadastrarNovoAluno(NULL, $nome, $status, $anoingresso, $curso);
  }
  break;
  
  case "salvarEditar":
	$idAluno = $_POST['idAluno'];
	if(salvarEdicao($idAluno))
	listarAlunosPPGI("", 0, "", 0);
	else{
	JError::raiseWarning( 100, 'ERRO: Edi&ccedil;&atilde;o n&atilde;o realizada. Este e-mail j&aacute; est&aacute; cadastrado no sistema como 	    aluno corrente. <br>
	Encerre a conta atual do aluno ou altere seu e-mail para confirmar a edi&ccedil;&atilde;o.' );
	$nome = JRequest::getVar('buscaNome');
	$status = JRequest::getVar('buscaStatus');
	$anoingresso = JRequest::getVar('buscaAnoIngresso');
	$curso = JRequest::getVar('buscaCurso');
	$idAluno = JRequest::getCmd('idAluno', false);
	$aluno = identificarAlunoID($idAluno);
	editarAluno($aluno, $nome, $status, $anoingresso, $curso);
  }
  break;
  
  case "verAluno":
	$idAluno = JRequest::getCmd('idAluno', false);
	$aluno = identificarAlunoID($idAluno);
	relatorioAluno($aluno);
  break;
  
  /***--- Tasks referentes a Operações de Gerenciamento de Matriculas ---***/
  case "listarmatriculas":
	$periodo = JRequest::getCmd('periodo', false);
	$anoIngresso = JRequest::getVar('anoingresso');
	$curso = JRequest::getCmd('curso', false);
	listarMatriculas($periodo, $anoIngresso, $curso);
  break;
  
  case "imprimirMatricula":
	$idAluno = JRequest::getCmd('idAluno', false);
	$idPeriodo = JRequest::getCmd('idPeriodo', false);
	$aluno = identificarAlunoID($idAluno);
	$sql = "SELECT D.id, D.codigo, D.nomeDisciplina , D.creditos , D.carga, PD.professor, PD.horario, T.turma, PD.curso FROM #__disciplina as D 	  join #__disc_matricula as T join #__periodo_disc as PD on (D.id = T.idDisciplina AND T.idDisciplina = PD.idDisciplina) WHERE T.idAluno =  $idAluno AND T.idPeriodo = $idPeriodo AND PD.idPeriodo = $idPeriodo AND T.turma = PD.turma ORDER BY D.codigo";
	$database->setQuery( $sql );
	$disciplinas = $database->loadObjectList();
	$sql = " SELECT `semestre`,`fasePesquisa`,`dataPrevista`,`dataMatricula` FROM `#__matricula` WHERE idAluno = $idAluno  AND idPeriodo =    																		  $idPeriodo";
	$database->setQuery( $sql );
	$matricula = $database->loadObjectList();
	imprimirMatricula($aluno,$disciplinas,$matricula);
	break;
	case "registrarMatricula":
	$idAluno = JRequest::getCmd('idAluno', false);
	$idPeriodo = JRequest::getCmd('idPeriodo', false);
	registrarMatricula($idAluno, $idPeriodo);
	
	$periodo = JRequest::getCmd('periodo', false);
	$anoIngresso = JRequest::getVar('anoingresso');
	$curso = JRequest::getCmd('curso', false);
	listarMatriculas($periodo, $anoIngresso, $curso);
  break;
  
  /***--- Tasks referentes a Operações de Gerenciamento de Períodos ---***/
  case "periodo":
	$ano = JRequest::getVar('fecha');
	listarPeriodos($ano, 0);
  break;
  
  case "addPeriodo":
	cadastrarNovoPeriodo();
  break;
  
  case "criaPeriodo":
	  $periodo = JRequest::getVar('periodo');
	  if(identificarPeriodo($periodo)==0 ){
		  criarPeriodo();
	  }
	  $ano = JRequest::getVar('fecha');
	  listarPeriodos($ano, 0);
  break;
  
  case "cadastroPD":
	  $idPeriodo = JRequest::getVar('idHidden');
	  $periodo = identificarPeriodoID($idPeriodo);
	  
	  $sql = "DELETE FROM #__temp_pd WHERE idPeriodo = $idPeriodo";
	  $database->setQuery( $sql );
	  $database->Query();
	  
	  //    $myDisciplinas = getMyDisciplinas_p($idPeriodo);
	  $cadastradas = disciplinasCadastradas($idPeriodo);	  
	  formularioDiscPeriodo($periodo, $cadastradas);
  break;
  
  case "addDiscPeriodo":  
	  $idPeriodo = JRequest::getVar('idPeriodo');
	  $periodo = identificarPeriodoID($idPeriodo);
	  $disciplinas = getTabelaDisciplinas_p();	  
	  addDiscPeriodo($periodo, $disciplinas, $idDisciplina, $turma, 0);  
  break;
  
  case "editarDiscPeriodo":
	  $idRemove = JRequest::getVar('idDisc');
	  $itens = explode(";", $idRemove);
	  $idDisciplina = $itens[0];
	  $turma = $itens[1];
	  $idPeriodo = $itens[2];
	  $periodo = identificarPeriodoID($idPeriodo);
	  $disciplinas = getTabelaDisciplinas_p();	  
	  addDiscPeriodo($periodo, $disciplinas, $idDisciplina, $turma, 1);
  break;
  
  case "ativarPeriodo":
	  $idPeriodo = JRequest::getVar('idHidden');	  
	  $resultado = ativarPeriodo($idPeriodo);
	  $ano = JRequest::getVar('fecha');
	  listarPeriodos($ano, $resultado);
  break;
  
  case "desativarPeriodo":
	  $idPeriodo = JRequest::getVar('idHidden');
	  $resultado = desativarPeriodo($idPeriodo);
	  $ano = JRequest::getVar('fecha');
	  listarPeriodos($ano, $resultado);
  break;
  
  case "abrirMatriculaPeriodo":
	  $idPeriodo = JRequest::getVar('idHidden');
	  $resultado = mudarMatriculaPeriodo($idPeriodo , 1);
	  $ano = JRequest::getVar('fecha');
	  listarPeriodos($ano, $resultado);
  break;
  
  case "fecharMatriculaPeriodo":
	  $idPeriodo = JRequest::getVar('idHidden');
	  $resultado = mudarMatriculaPeriodo($idPeriodo , 0);
	  $ano = JRequest::getVar('fecha');
	  listarPeriodos($ano, $resultado);
  break;
  
  case "abrirNotasPeriodo":
	  $idPeriodo = JRequest::getVar('idHidden');  
	  $resultado = mudarLancarNotasPeriodo($idPeriodo , 1);
	  $ano = JRequest::getVar('fecha');
	  listarPeriodos($ano, $resultado);
  break;
  
  case "fecharNotasPeriodo":
	  $idPeriodo = JRequest::getVar('idHidden');
	  $resultado = mudarLancarNotasPeriodo($idPeriodo , 0);
	  $ano = JRequest::getVar('fecha');
	  listarPeriodos($ano, $resultado);
  break;
  
  case "excluirPeriodo":
	  $idPeriodo = JRequest::getVar('idHidden');
	  $resultado = excluirPeriodo($idPeriodo);
	  $ano = JRequest::getVar('fecha');
	  listarPeriodos($ano, $resultado);
  break;
  
  case "rmDiscPeriodo":
	  $idRemove = JRequest::getVar('idDisc');
	  $itens = explode(";", $idRemove);
	  $idDisciplina = $itens[0];
	  $turma = $itens[1];
	  $idPeriodo = $itens[2];
	  $sucesso = removerDiscPeriodo($idPeriodo,$idDisciplina, $turma);
	  
	  if($sucesso) 
	  	JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
	  else
	  	JError::raiseWarning( 100, 'ERRO: Opera&#231;&#227;o de Exclus&atilde;o falhou.' );
		
	  $periodo = identificarPeriodoID($idPeriodo);
	  $cadastradas = disciplinasCadastradas($idPeriodo);
	  formularioDiscPeriodo($periodo,$cadastradas );
  break;
  
  case "addDPeriodo":
	  $idDisciplina = JRequest::getVar('novaDisciplina');
	  $idPeriodo = JRequest::getVar('idHidden');  
	  $periodo = identificarPeriodoID($idPeriodo);
	  salvarDiscPeriodo($idPeriodo,$idDisciplina);
		
	  $cadastradas = disciplinasCadastradas($idPeriodo);  
	  formularioDiscPeriodo($periodo,$cadastradas);
  break;
  
  /* case "salvarCadastroPD":
  $idPeriodo = JRequest::getCmd('idPeriodo', false);
  $periodo = identificarPeriodoID($idPeriodo);
  $myDisciplinas = getMyDisciplinas_p($periodo->id);
  
  salvarCadastroPD($idPeriodo,$myDisciplinas);
  listarPeriodos("", 0);
  break;*/
  
  case "oferta":
	  $idPeriodo = JRequest::getCmd('idPeriodo', false);
	  
	  $sql = "SELECT D.id, D.codigo, D.nomeDisciplina , D.creditos , D.carga, PD.professor, PD.horario, PD.turma, PD.curso, PD.sala FROM #__disciplina as D join #__periodo_disc as PD on (D.id = PD.idDisciplina) WHERE PD.idPeriodo = $idPeriodo ORDER BY D.nomeDisciplina";
	  $database->setQuery( $sql );
	  $disciplinas = $database->loadObjectList();
	  
	  $database->setQuery("SELECT periodo FROM #__periodos WHERE id = $idPeriodo");
	  $periodos = $database->loadObjectList();
	  imprimirOferta($disciplinas, $periodos[0]);
  break;
  
  /***--- Tasks referentes a Operações de Gerenciamento de Disciplinas ---***/  
  case "disciplinas":
	  $filtro = JRequest::getVar('nome');
	  listarDisciplinasPPGI($filtro);
  break;
  
  case "addDisciplina":
  	cadastrarDisciplina(NULL);
  break;
  
  case "editarDisciplina":
	  $idDisc = JRequest::getCmd('idDisc', false);
	  cadastrarDisciplina($idDisc);
  break;
  
  case "excluirDisciplina":
	  $idDisc = JRequest::getCmd('idDisc', false);
	  $filtro = JRequest::getVar('nome');
	  excluirDisciplina($idDisc);
	  listarDisciplinasPPGI($filtro);
  break;
  
  case "salvarDisciplina":
	  $filtro = JRequest::getVar('nome');
	  salvarDisciplina();
	  listarDisciplinasPPGI("");
  break;
  
  // -------------------- GERENCIAMENTO DE TURMA --------------------
  case "listarturmas":
	  $periodo = JRequest::getCmd('periodo', false);
	  $curso = JRequest::getVar('curso');
	  listarTurmas($periodo, $curso);
  break;
  
  case "consultarTurma":
	  $idPeriodo = JRequest::getCmd('periodo', false);
	  $idDisciplina = JRequest::getCmd('idDisciplina', false);
	  $turma = JRequest::getCmd('turma', false);	  
	  consultarTurma($idPeriodo, $idDisciplina, $turma);
  break;
  
  case "imprimirPagela":
	  $idPeriodo = JRequest::getCmd('periodo', false);
	  $idDisciplina = JRequest::getCmd('idDisciplina', false);
	  $turma = JRequest::getCmd('turma', false);
	  $tipo = JRequest::getCmd('tipo', false);	  
	  imprimirPagela($idPeriodo, $idDisciplina, $turma, $tipo);
  break;
  
  case "imprimirBoletim":
	  $idPeriodo = JRequest::getCmd('periodo', false);
	  $idDisciplina = JRequest::getCmd('idDisciplina', false);
	  $turma = JRequest::getCmd('turma', false);
	  $tipo = JRequest::getCmd('tipo', false);	  
	  imprimirBoletim($idPeriodo, $idDisciplina, $turma, $tipo);
  break;
  
  case "corrigirNota":
	  $idAluno = JRequest::getVar('idAluno');
	  $idPeriodo = JRequest::getCmd('periodo', false);
	  $idDisciplina = JRequest::getCmd('idDisciplina', false);
	  $idTurma = JRequest::getCmd('idTurma', false);
	  corrigirNota($idAluno, $idPeriodo, $idDisciplina, $idTurma);
  break;
  
  case "atualizarNota":
	  $idAluno = JRequest::getVar('idAluno');
	  $idPeriodo = JRequest::getVar('idPeriodo');
	  $idDisciplina = JRequest::getVar('idDisciplina');
	  $turma = JRequest::getVar('turma');	  
	  atualizarNota($idAluno, $idPeriodo, $idDisciplina, $turma);
  break;
  
  case "historico":
  	  listarHistoricoAlunos($nome, $status, $anoingresso, $curso);
  break;

  case "detalharAluno":
	  $idAluno = JRequest::getVar('idAluno');
	  $aluno = identificarAlunoID($idAluno);
	  detalharAluno($aluno);
  break;  

  case "formarAluno":
	  $idAluno = JRequest::getVar('idAluno');
	  formarAluno($idAluno);
	  $aluno = identificarAlunoID($idAluno);
	  detalharAluno($aluno);
  break;  
  
  case "banca":
	  $idAluno = JRequest::getCmd('idAluno', false);
	  $aluno = identificarAlunoID($idAluno);
	  registrarBanca($aluno);
  break;
  
  case "salvarbanca":
	  $idAluno = JRequest::getCmd('idAluno', false);
	  salvarBanca($idAluno);
	  $aluno = identificarAlunoID($idAluno);
	  registrarBanca($aluno);
  break;
  
  case "addBancaQual":
	  $idAluno = JRequest::getCmd('idAluno', false);
	  salvarBanca($idAluno);
	  adicionarMembroBanca($idAluno, 'Q');
	  $aluno = identificarAlunoID($idAluno);
	  registrarBanca($aluno);
  break;
  
  case "removeBancaQual":
	  $idAluno = JRequest::getCmd('idAluno', false);
	  salvarBanca($idAluno);
	  removerMembroBanca($idAluno);
	  $aluno = identificarAlunoID($idAluno);
	  registrarBanca($aluno);
  break;
  
  case "addBancaTese":
	  $idAluno = JRequest::getCmd('idAluno', false);
	  salvarBanca($idAluno);
	  adicionarMembroBanca($idAluno, 'T');
	  $aluno = identificarAlunoID($idAluno);
	  registrarBanca($aluno);
  break;
  
  case "removeBancaTese":
	  $idAluno = JRequest::getCmd('idAluno', false);
	  salvarBanca($idAluno);
	  removerMembroBanca($idAluno);
	  $aluno = identificarAlunoID($idAluno);
	  registrarBanca($aluno);
  break;
  
  case "chamadabanca":
	  $idAluno = JRequest::getCmd('idAluno', false);
	  $tipoDefesa = JRequest::getCmd('tipoDefesa', false);	  
	  imprimirChamadaDefesa($idAluno, $tipoDefesa);
  break;

  case "imprimirAta":
	  $idAluno = JRequest::getVar('idAluno');
	  $tipoDefesa = JRequest::getVar('tipoDefesa');
	  imprimirAtaDefesa($idAluno, $tipoDefesa);
  break;

  case "imprimirFolha":
	  $idAluno = JRequest::getVar('idAluno');
	  $tipoDefesa = JRequest::getVar('tipoDefesa');
	  imprimirFolhaAprovacao($idAluno, $tipoDefesa);
  break;

  case "imprimirAgradecimento":
	  $idMembro = JRequest::getVar('idMembro');
	  $tipoDefesa = JRequest::getVar('tipoDefesa');
	  imprimirAgradecimento($idMembro, $tipoDefesa);
  break;

  case "imprimirDeclaracaoBanca":
	  $idMembro = JRequest::getVar('idMembro');
	  $tipoDefesa = JRequest::getVar('tipoDefesa');
	  imprimirDeclaracaoBanca($idMembro, $tipoDefesa);
  break;

  /***--- Tasks referentes a Operações de Gerenciamento de Professores ---***/
  case "professores":
	$nome = JRequest::getVar('buscaNome');
	listarProfessores($nome);
  break;
  
  case "addProfessor":
	  $nome = JRequest::getVar('buscaNome');
	  editarProfessor(NULL, $nome);
  break;
  
  case "editarProfessor":
	  $nome = JRequest::getVar('buscaNome');
	  $idProf = JRequest::getCmd('idProf', false);
	  $professor = identificarProfessorID($idProf);
	  editarProfessor($professor, $nome);
  break;
  
  case "salvarProfessor":
	  $nome = JRequest::getVar('buscaNome');
	  $idProf = JRequest::getCmd('idProf', false);
	  
	  if(salvarProfessor($idProf))
	  	listarProfessores($nome);
	  else {
	  	$nome = mosGetParam( $_REQUEST, 'buscaNome', '');
	  	$professor = identificarProfessorID($idProf);
	  	editarProfessor($professor, $nome);
	  }
  break;
  
  case "excluirProfessor":
	  $nome = JRequest::getVar('buscaNome');
	  $idProf = JRequest::getCmd('idProf', false);
	  excluirProfessor($idProf);
	  listarProfessores($nome);
  break;
  
  case "verProfessor":
	  $idProf = JRequest::getCmd('idProf', false);
	  $professor = identificarProfessorID($idProf);
	  relatorioProfessor($professor);
  break;
  
  /***--- Tasks referentes a Operações de Aproveitamento ---***/
  case "aproveitamentos":
	  $nome = JRequest::getVar('buscaNome');
	  $anoingresso = JRequest::getVar('buscaAnoIngresso');
	  $curso = JRequest::getVar('buscaCurso');
	  $status = JRequest::getVar('buscaStatus');
	  $periodo = JRequest::getVar('buscaPeriodo');
	  listarAproveitamentos($nome, $anoingresso, $curso, $status, $periodo);
  break;
  
  case "julgarAproveitamento":
	  $idAluno = JRequest::getVar('idAluno');
	  $aluno = identificarAlunoID($idAluno);
	  $nome = JRequest::getVar('buscaNome');
	  $anoingresso = JRequest::getVar('buscaAnoIngresso');
	  $curso = JRequest::getVar('buscaCurso');
	  $status = JRequest::getVar('buscaStatus');
	  $periodo = JRequest::getVar('buscaPeriodo');
	  julgarAproveitamento($aluno, $nome, $anoingresso, $curso, $status, $periodo);
  break;
  
  case "deferirAproveitamento":
	  $idAluno = JRequest::getVar('idAluno');
	  $idAproveitamento = JRequest::getVar('idAproveitamento');
	  $aluno = identificarAlunoID($idAluno);
	  $nome = JRequest::getVar('buscaNome');
	  $anoingresso = JRequest::getVar('buscaAnoIngresso');
	  $curso = JRequest::getVar('buscaCurso');
	  $status = JRequest::getVar('buscaStatus');
	  $periodo = JRequest::getVar('buscaPeriodo');
	  $justificativa = JRequest::getVar('justificativa');
	  salvarJulgamentoAproveitamento($idAproveitamento, $justificativa, 1);
	  julgarAproveitamento($aluno, $nome, $anoingresso, $curso, $status, $periodo);
  break;
  
  case "indeferirAproveitamento":
	  $idAluno = JRequest::getVar('idAluno');
	  $idAproveitamento = JRequest::getVar('idAproveitamento');
	  $aluno = identificarAlunoID($idAluno);
	  $nome = JRequest::getVar('buscaNome');
	  $anoingresso = JRequest::getVar('buscaAnoIngresso');
	  $curso = JRequest::getVar('buscaCurso');
	  $status = JRequest::getVar('buscaStatus');
	  $periodo = JRequest::getVar('buscaPeriodo');
	  $justificativa = JRequest::getVar('justificativa');
	  salvarJulgamentoAproveitamento($idAproveitamento, $justificativa, 2);
	  julgarAproveitamento($aluno, $nome, $anoingresso, $curso, $status, $periodo);
  break;
  
  case "imprimirAproveitamento":
	  $idAluno = JRequest::getVar('idAluno');
	  $periodo = JRequest::getVar('periodo');
	  $aluno = identificarAlunoID($idAluno);
	  imprimirAproveitamento($aluno, $periodo);
  break;
  
  /***--- Tasks referentes a Operações com Bolsas de Estudo ---***/
  case "bolsas":
	  $aluno = JRequest::getVar('buscaAluno');
	  $agencia = JRequest::getVar('buscaAgencia');
	  $status = JRequest::getVar('buscaStatus');
	  listarBolsas($aluno,$agencia, $status);
  break;
  
  case "addBolsa":
	  $aluno = JRequest::getVar('buscaAluno');
	  $agencia = JRequest::getVar('buscaAgencia');
	  $status = JRequest::getVar('buscaStatus');
	  editarBolsa(NULL);
  break;
  
  case "salvarBolsa":
	  //   $idBolsa = JRequest::getVar('idBolsa');
	  $idBolsa = JRequest::getCmd('idBolsa', false);
	  salvarBolsa($idBolsa);
	  listarBolsas(NULL,NULL,NULL);
  break;
  
  case "excluirBolsa":
	  $idBolsa = JRequest::getVar('idBolsa');
	  excluirBolsa($idBolsa);
	  listarBolsas(NULL,NULL,NULL);
  break;
  
  case "verBolsa":
	  $idBolsa = JRequest::getVar('idBolsa');
	  $verbolsa = identificarBolsaID($idBolsa);
	  verBolsa($verbolsa);
  break;
  
  case "editarBolsa":
	  $idBolsa = JRequest::getVar('idBolsa');
	  $verbolsa = identificarBolsaID($idBolsa);
	  editarBolsa($verbolsa);
  break;
  
  /***--- Tasks referentes a Operações de Jubilamento ---***/
  case "jubilamento":
	  $nome = JRequest::getVar('buscaAluno');
	  $periodoPes = JRequest::getVar('periodo');
	  $curso = JRequest::getVar('buscaCurso');
	  $cr = JRequest::getVar('buscaCR');
	  listarJubilamento($nome,$periodoPes,$curso, $cr);
  break;
  
  case "imprimirJubilamento":
	  $nome = JRequest::getVar('buscaAluno');
	  $periodoPes = JRequest::getVar('periodo');
	  $curso = JRequest::getVar('buscaCurso');
	  $cr = JRequest::getVar('buscaCR');
	  imprimirJubilamento($nome,$periodoPes,$curso, $cr);
  break;
  
  // ------------------------ PRORROGAÇÃO ------------------------
  case "prorrogacao":
	$mes = JRequest::getVar('fecha');	
	$status = JRequest::getVar('buscaStatus');
   	mostrarTelaProrrogacao($mes, $status);
  break;
  
  case "mostrarDetalhesProrrogacao":
	$idProrrogacao = JRequest::getVar('idSolic');
	$prorrogacao = identificarProrrogacaoID($idProrrogacao);
	$mes = JRequest::getVar('fecha');	
	$status = JRequest::getVar('buscaStatus');
	$idAluno = $prorrogacao->idAluno;
	$aluno = identificarAlunoID($idAluno);
  	mostrarDetalhesProrrogacao($aluno, $prorrogacao, $mes, $status);
  break;
  
  case "aprovarProrrogacao":
	$mes = JRequest::getVar('fecha');
	$status = JRequest::getVar('buscaStatus');
	$idAluno = JRequest::getVar('idAluno');
	$aluno = identificarAlunoID($idAluno);
	$status = JRequest::getCmd('buscaStatus');		
	aprovarProrrogacao($mes,$status,$aluno);
  break;

  case "reprovarProrrogacao":
	$mes = JRequest::getVar('fecha');
	$status = JRequest::getVar('buscaStatus');
	$idAluno = JRequest::getVar('idAluno');
	$aluno = identificarAlunoID($idAluno);
	reprovarProrrocacao($mes,$status,$aluno);
  break;

  case "imprimirProrrogacao":
	$idAluno = JRequest::getVar('idAluno');
	$aluno = identificarAlunoID($idAluno);
	$idProrrogacao = JRequest::getVar('idProrrogacao');
	imprimirProrrogacao($aluno,$idProrrogacao);
  break;
  
  case "downloadDissertacao":
	$idProrrogacao = JRequest::getVar('idProrrogacao');
	echo("Passei pela task".$idProrrogacao); 
	$prorrogacao = identificarProrrogacaoID($idProrrogacao);
	downloadDissertacao($prorrogacao->previa);
  break;
  
  // ------------------------ TRANCAMENTO ------------------------
	case "trancamento":
		$mes = JRequest::getVar('fecha');	
		$status = JRequest::getVar('buscaStatus');
		telaTrancamento($mes, $status);
	break;
	
	case "mostrarDetalhesTrancamento":
		$idAluno = JRequest::getVar('idAluno');
		$aluno = identificarAlunoID($idAluno);
		mostrarDetalhesTrancamento($aluno);
	break;
	
	case "aprovarTrancamento":
		$mes = JRequest::getVar('fecha');
		$status = JRequest::getVar('buscaStatus');
		$idAluno = JRequest::getVar('idAluno');
		$aluno = identificarAlunoID($idAluno);
		$status = JRequest::getCmd('buscaStatus');		
		aprovarTrancamento($mes,$status,$aluno);
	break;
	
	case "reprovarTrancamento":
		$mes = JRequest::getVar('fecha');
		$status = JRequest::getVar('buscaStatus');
		$idAluno = JRequest::getVar('idAluno');
		$aluno = identificarAlunoID($idAluno);
		reprovarTrancamento($mes,$status,$aluno);
	break;
	
	case "imprimirTrancamento":
		$idAluno = JRequest::getVar('idAluno');
		$aluno = identificarAlunoID($idAluno);
		$idTrancamento = JRequest::getVar('idTrancamento');
		imprimirTrancamento($aluno,$idTrancamento);
	break;
  
    // ------------------------ RESERVA DE SALAS ------------------------
	case "reservas":
		$atividade = JRequest::getVar('atividade');
		$dataInicio = JRequest::getVar('dataInicio', false);
		$dataTermino = JRequest::getVar('dataTermino', false);
		listarReservas($atividade, $dataInicio, $dataTermino);
	break;
	
	case "addReserva":
		telaCadastrarReserva();
	break;
	
	case "cadReserva":
		salvarReserva();
	break;
		
	case "confirmarExclusao":	
		$idReserva = JRequest::getVar('idReserva');
		confirmarExclusao($idReserva);
	break;
	
	case "excluirReserva":	
		$idReserva = JRequest::getVar('idReserva');
		excluirReserva($idReserva);
	break;
	
  
	default:
	listarServicos();
	break;
}  
  
  // Menu Principal
  
  function formLogin($login){
  HTML_secretariaDCC::formLogin($login);
  }
  
  function mensagemDeErro($text){
  ?>
  
  <table style='width: 100%;' border='1' cellspacing='2'>
  <tbody>
  <tr align='center'>
  <td colspan='2'><span style='font-weight: bold;'>MENSAGEM DE ERRO</span></td>
  </tr>
  <tr>
  <td width='48px'><img style='width: 48px; height: 48px;' alt='erro' src='components/com_portalsecretaria/images/erro.gif'></td>
  <td><font color='#ff0000'><?php echo $text;?></font></td>
  </tr>
  </tbody>
  </table>
  <?php
  
  }
  
  function listarServicos(){
  
  HTML_secretariaDCC::listarServicos();
  }
  
  function listarNotificacoes($mes){
  
	$database	=& JFactory::getDBO();
  
	if($mes == ""){
		$mesAtual = (int)date("m");
		$anoAtual = (int)date("Y");
  
	}
	else{
		$mesAtual = (int)substr($mes,0,2);
		$anoAtual = (int)substr($mes,3,4);
	}
  
  
	$sql = "SELECT id,nomeusuario, DATE_FORMAT(dataenvio,'%d/%m/%Y %h:%i') as dataenvio, local, tipo, datasaida, dataretorno, justificativa, reposicao FROM #__notificacoes_saida WHERE MONTH(dataenvio) = $mesAtual AND YEAR(dataenvio) = $anoAtual ORDER BY dataenvio";
	$database->setQuery( $sql );
	$notificacoes = $database->loadObjectList();
  
	HTML_secretariaDCC::listarNotificacoes($notificacoes, $mes);
  }


// FUNÇÕES : CONVERSÃO DE DATAS E VALORES	

// DATA BR
function dataBr($dataSql) {
	if (!empty($dataSql)) {
		$p_dt = explode('-',$dataSql);
    	$data = $p_dt[2].'/'.$p_dt[1].'/'.$p_dt[0];
		return $data;
	}
}

// HORA BR
function horaBr($horaSql) {
	if (!empty($horaSql)) {
		$p_dt = explode(':',$horaSql);
    	$hora = $p_dt[0].':'.$p_dt[1];
		return $hora;
	}
}

?>

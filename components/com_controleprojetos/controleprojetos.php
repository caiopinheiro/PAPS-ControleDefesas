<?php
/***********************************************************************
*               UFAM - Universidade Ferderal do Amazonas
* 
*      Funcoes de controle do modulo Despesa,desenvolvido em 10/02/2013
* adaptado do modulo professor, para update do site icomp sob orientacao
* do Prof Arilo Dias. 
* 
* Sitemas de informacao, Equipe Augusto, Denise e Diana
* usando padrao de desenvolvimento ágil
* 
************************************************************************/


$user = JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );
include_once("components/com_controleprojetos/gerenciarfomento.php");
include_once("components/com_controleprojetos/gerenciarprojeto.php");
include_once("components/com_controleprojetos/gerenciarrubrica.php");
include_once("components/com_controleprojetos/rubricadeprojeto.php");
include_once("components/com_controleprojetos/relatoriosimplificadodespesa.php");
include_once("components/com_controleprojetos/relatoriodetalhadodespesa.php");
include_once("components/com_controleprojetos/despesasprojeto.php");
include_once("components/com_controleprojetos/receitasprojeto.php");
include_once("components/com_controleprojetos/despesasrubrica.php");
//include_once("components/com_controleprojetos/despesasconsultarubrica.php");
include_once("components/com_controleprojetos/consultasaldorubrica.php");
include_once("components/com_controleprojetos/registrodedatas.php");
include_once("components/com_controleprojetos/controleprojetos.html.php");
include_once("components/com_controleprojetos/transferenciadesaldo.php");
include_once("components/com_controleprojetos/prorrogarprojeto.php");
include_once('pdf-php/class.ezpdf.php');


$task = JRequest::getCmd('task', false);
$Itemid = JRequest::getInt('Itemid', 0);
$database = JFactory::getDBO();

switch ($task) {
	case "validalogin":
		$senha = $_POST['senha'];
		$email = $_POST['email'];
		if(secretaria($email, $senha)){
			listarServicos();
		} else {
			formLogin(true);
		}
   break;

// ------------------------------------------ GERENCIAR PROJETOS P&D 
	case "gerenciarProjetopds":
		$nome = JRequest::getVar('buscaNome');
		$coordenador = JRequest::getVar('buscaCoordenador');
		$financiador = JRequest::getVar('buscaFinanciador');
		$status = JRequest::getVar('buscaStatus');
		listarProjetopds($nome, $coordenador, $financiador, $status);
	break;

	case "addProjetopd":
		$nome = JRequest::getVar('buscaNome');
		addProjetopd(NULL, $nome);
	break;

	case "editarProjetopd":
		$nome = JRequest::getVar('buscaNome');
		$idProjetopd = JRequest::getVar('idProjetopd');
		$projetopd = identificarProjetopdID($idProjetopd);
		editarProjetopd($projetopd, $nome);
	break;

	case "salvarProjetopd":
		$nome = JRequest::getVar('buscaNome');
		$idProjetopd = JRequest::getCmd('idProjetopd', false);
		if(salvarProjetopd($idProjetopd))
			listarProjetopds($nome);
		else {
			$nome = JRequest::getVar('buscaNome');    
			$projetopd = identificarProjetopdID($idProjetopd);
			addProjetopd(NULL, $nome);
		}
	break;
   
	case "atualizarProjetopd":
		$nome = JRequest::getVar('buscaNome');
		$idProjetopd = JRequest::getVar('idProjetopd');
		$projetopd = identificarProjetopdID($idProjetopd);
		atualizarProjetopd($projetopd, $nome);
	break;
   
	case "excluirProjetopd":
		$nome = JRequest::getVar('buscaNome');
		$idProjetopd = JRequest::getCmd('idProjetopd', false);
		excluirProjetopd($idProjetopd);
		listarProjetopds($nome);
	break;

	case "verProjetopd":
		$idProjetopd = JRequest::getCmd('idProjetopd', false);
		$projetopd = identificarProjetopdID($idProjetopd);
		relatorioProjetopd($projetopd);
	break;
	
	case "prorrogacao":
		$idProjetopd = JRequest::getCmd('idProjeto', false);
		$projetopd = identificarProjetopdID($idProjetopd);
		mostrarTelaProrrogacao($projetopd);
	break;

	case "adicionarProrrogacao":
		$idProjeto = JRequest::getCmd('idProjeto', false);
		adicionarProrrogacao($idProjeto);
	break;


// ------------------------------------------ RUBRICAS DE PROJETOS
	case "gerenciarRubricadeprojetos":
		$descricao = JRequest::getVar('buscaDescricao');
		listarRubricadeprojetos($descricao);
	break;

	case "addRubricadeprojeto":
		$descricao = JRequest::getVar('buscaDescricao');
		$idProjeto = JRequest::getVar('idProjetopd');
		addRubricadeprojeto(NULL, $descricao, $idProjeto);
	break;

	case "editarRubricadeprojeto":
		$descricao = JRequest::getVar('buscaDescricao');
		$idRubricadeprojeto = JRequest::getVar('idRubricadeprojeto');
		$Rubricadeprojeto = identificarRubricadeprojetoID($idRubricadeprojeto);
		editarRubricadeprojeto($Rubricadeprojeto, $descricao);
	break;

	case "salvarRubricadeprojeto":
		$idProjeto = JRequest::getVar('idProjeto');
		$descricao = JRequest::getVar('buscaDescricao');
		$idRubricadeprojeto = JRequest::getVar('idRubricadeprojeto');
		salvarRubricadeprojeto($idRubricadeprojeto, $idProjeto);
	break;
	
	case "atualizarRubricadeprojeto":
		$idProjeto = JRequest::getVar('idProjeto');
		$descricao = JRequest::getVar('buscaDescricao');
		$idRubricadeprojeto = JRequest::getVar('idRubricadeprojeto');
		atualizarRubricadeprojeto($idRubricadeprojeto, $idProjeto);
	break;
   
	case "excluirRubricadeprojeto":
		$descricao = JRequest::getVar('buscaDescricao');
		$idRubricadeprojeto = JRequest::getVar('idRubricadeprojeto');
		excluirRubricadeprojeto($idRubricadeprojeto);
		listarRubricadeprojetos($descricao);
	break;
 
 
// ------------------------------------------ TRANSFERIR SALDO DE RUBRICAS
	case "gerenciarTranferenciadeRubricas":
		listarTranferenciadeRubricas();
	break;
	
	case "addTranferenciadeRubrica":
		$idProjeto = JRequest::getVar('idProjetopd');
		addTranferenciadeRubrica(NULL, $idProjeto);
	break;
	
	case "salvarTranferenciadeRubrica":
		$idProjeto = JRequest::getVar('idProjeto');
		$idTranferenciadeRubrica = JRequest::getVar('idTranferenciadeRubrica');
		salvarTranferenciadeRubrica($idTranferenciadeRubrica, $idProjeto);
	break;
   
	case "excluirTranferenciadeRubrica":
	   $idTranferenciadeRubrica = JRequest::getVar('idTranferenciadeRubrica');
	   excluirTranferenciadeRubrica($idTranferenciadeRubrica);
	   listarTranferenciadeRubricas();
	break;


// ------------------------------------------ DESPESAS DE PROJETO 
	case "gerenciarDespesasprojetos":
		listarDespesasprojetos();
	break;

	case "addDespesaprojeto":
		$idProjeto = JRequest::getVar('idProjetopd');
		addDespesaprojeto(NULL, $idProjeto);
	break;

	case "editarDespesaprojeto":
		$idProjeto = JRequest::getVar('idProjetopd');//Este  $idProjeto - está Exclusivo para identificar Lista rubrica por rubrica de projetos
		$idDespesaprojeto = JRequest::getVar('idDespesaprojeto');
		$despesaprojeto = identificarDespesaprojetoID($idDespesaprojeto);
		editarDespesaprojeto($despesaprojeto, $idProjeto);//Este  $idProjeto - está Exclusivo para identificar Lista rubrica por rubrica de projetos
	break;

	case "salvarDespesaprojeto":
		$idDespesaprojeto = JRequest::getVar('idDespesaprojeto');
		$idProjeto = JRequest::getVar('idProjeto');
		salvarDespesaprojeto($idProjeto);
	break;
	
	case "atualizarDespesaprojeto":
		$idProjeto = JRequest::getVar('idProjeto');
		$idRubricadeprojeto = JRequest::getVar('idRubricadeprojeto');
		atualizarDespesaprojeto($idRubricadeprojeto, $idProjeto);
	break;
   
	case "excluirDespesaprojeto":
		$idDespesaprojeto = JRequest::getVar('idDespesaprojeto');
		excluirDespesaprojeto($idDespesaprojeto);
		listarDespesasprojetos();
	break;

	case "verDespesaprojeto":
		$idProjeto = JRequest::getVar('idProjetopd');
		$idDespesaprojeto = JRequest::getVar('idDespesaprojeto');
		$despesaprojeto = identificarDespesaprojetoID($idDespesaprojeto);
		relatorioDespesaprojeto($despesaprojeto, $idProjeto);
	break;

// ------------------------------------------ RECEITAS DE PROJETO
	case "receitas":
		listarReceitas();
	break;

	case "addReceita":
		$idProjeto = JRequest::getVar('idProjetopd');
		addReceita(NULL, $idProjeto);
	break;

	case "salvarReceita":
		$idReceita = JRequest::getVar('idReceita');
		$idProjeto = JRequest::getVar('idProjeto');
		if(salvarReceita($idReceita))
 		   listarReceitas();
		else   
		   addReceita(NULL, $idProjeto);		
	break;
   
	case "excluirReceita":
		$idReceita = JRequest::getVar('idReceita');
		excluirReceita($idReceita);
		listarReceitas();
	break;

	case "verReceita":
		$idProjeto = JRequest::getVar('idProjetopd');
		$idReceita = JRequest::getVar('idReceita');
		$receita = identificarDespesaprojetoID($idReceita);
		relatorioDespesa($receita, $idProjeto);
	break;

// ------------------------------------------ GERENCIAR REGISTRO DE DATAS
	case "gerenciarRegistrodedatas":
		$evento = JRequest::getVar('buscaEvento');
		listarRegistrodedatas($evento);
	break;

	case "addRegistrodedata":
		$evento = JRequest::getVar('buscaEvento');
		$idProjeto = JRequest::getVar('idProjetopd');
		addRegistrodedata(NULL, $evento, $idProjeto);
	break;

	case "editarRegistrodedata":
		$evento = JRequest::getVar('buscaEvento');
		$idProjeto = JRequest::getVar('idProjetopd');//Este  $idProjeto - está Exclusivo para identificar  de projetos
		$idRegistrodedata = JRequest::getVar('idRegistrodedata');
		$registrodedata = identificarRegistrodedataID($idRegistrodedata);
		editarRegistrodedata($registrodedata, $evento, $idProjeto);
	break;

	case "salvarRegistrodedata":
		$evento = JRequest::getVar('buscaEvento');
		$idRegistrodedata = JRequest::getVar('idRegistrodedata');
		$idProjeto = JRequest::getVar('idProjeto');		
		if(salvarRegistrodedata($idRegistrodedata))
			listarRegistrodedatas($evento);
		else
			addRegistrodedata(NULL, $evento, $idProjeto);		
	break;
	
	case "atualizarRegistrodedata":
		$evento = JRequest::getVar('buscaEvento');
		$idProjeto = JRequest::getVar('idProjeto');
		$idRegistrodedata = JRequest::getVar('idRegistrodedata');
		$registrodedata = identificarRegistrodedataID($idRegistrodedata);
		if(atualizarRegistrodedata($idRegistrodedata))
		    listarRegistrodedatas($evento);
		else
			editarRegistrodedata($registrodedata, $evento, $idProjeto);		
	break;
   
	case "excluirRegistrodedata":
		$evento = JRequest::getVar('buscaEvento');
		$idRegistrodedata = JRequest::getVar('idRegistrodedata');
		excluirRegistrodedata($idRegistrodedata);
		listarRegistrodedatas($evento);
	break;


// ------------------------------------------ RELATORIO DE DESPESAS
	case "gerenciarRelatorioSimplificadoDespesa":
		$descricaoRelatorioSimplificadoDespesa = JRequest::getVar('buscaRelatorioDespesa');
		listarRelatorioSimplificadoDespesas($descricaoRelatorioSimplificadoDespesa);
	break;

case "relatorioSimplificadoDespesa":
  $idProjeto = JRequest::getVar('idProjetopd');
  $sql = " SELECT RP.id, RP.projeto_id, P.nomeprojeto, P.orcamento, P.saldo, P.data_inicio, P.data_fim,
                  P.coordenador_id,  PR.nomeProfessor, P.agencia_id, AG.sigla, P.edital, P.status,
                  RP.rubrica_id, R.nome,  RP.descricao, RP.valor_total, RP.valor_gasto, RP.valor_disponivel
           FROM #__contproj_rubricasdeprojetos AS RP 
           INNER JOIN #__contproj_rubricas AS R
                  ON RP.rubrica_id = R.id  
           INNER JOIN #__contproj_projetos AS P
                  ON RP.projeto_id = P.id 
           INNER JOIN #__professores AS PR
                  ON P.coordenador_id = PR.id
           INNER JOIN #__contproj_agencias AS AG
                  ON P.agencia_id = AG.id
           WHERE  projeto_id = $idProjeto ORDER BY descricao";
   $database->setQuery( $sql );
   $gerenciarRubricadeprojetos = $database->loadObjectList();
   $database->setQuery( $sql );
   $consultasaldorubrica = $database->loadObjectList();
   imprimirRelatorioSimplificado($gerenciarRubricadeprojetos, $consultasaldorubrica[0]);
   break;
   
   
	case "gerenciarRelatorioDetalhadoDespesa":
		$descricaoRelatorioDetalhadoDespesa = JRequest::getVar('buscaRelatorioDespesa');
		listarRelatorioDetalhadoDespesas($descricaoRelatorioDetalhadoDespesa);
	break;

case "relatorioDetalhadoDespesa":
  $idProjeto = JRequest::getVar('idProjetopd');
  $sql = " SELECT RP.id, RP.projeto_id, P.nomeprojeto, P.orcamento, P.saldo, P.data_inicio, P.data_fim,
                  P.coordenador_id,  PR.nomeProfessor, P.agencia_id, AG.sigla, P.edital, P.status,
                  RP.rubrica_id, R.nome,  RP.descricao, RP.valor_total, RP.valor_gasto, RP.valor_disponivel
           FROM #__contproj_rubricasdeprojetos AS RP 
           INNER JOIN #__contproj_rubricas AS R
                  ON RP.rubrica_id = R.id  
           INNER JOIN #__contproj_projetos AS P
                  ON RP.projeto_id = P.id 
           INNER JOIN #__professores AS PR
                  ON P.coordenador_id = PR.id
           INNER JOIN #__contproj_agencias AS AG
                  ON P.agencia_id = AG.id
           WHERE  projeto_id = $idProjeto ORDER BY descricao";

  
	$database->setQuery( $sql );
	$gerenciarRubricadeprojetos = $database->loadObjectList();   
	
	$database->setQuery( $sql );
	$consultasaldorubrica = $database->loadObjectList();
	
	$database->setQuery( $sql );
	$consultasaldorubrica = $database->loadObjectList();
   
	$database->setQuery( $sql );
   	$gerenciarDespesasRubricas = $database->loadObjectList();
   
   imprimirRelatorioDetalhado($gerenciarRubricadeprojetos, $gerenciarDespesasRubricas, $consultasaldorubrica[0]);
   break;
  
  
// ------------------------------------------ GERENCIAR RUBRICAS
	case "gerenciarRubricas":
		$nome = JRequest::getVar('buscaNome');
		listarRubricas($nome);
	break;

	case "addRubrica":
		$nome = JRequest::getVar('buscaNome');
		addRubrica(NULL, $nome);
	break;

	case "editarRubrica":
		$nome = JRequest::getVar('buscaNome');
		$idRubrica = JRequest::getCmd('idRubrica', false);
		$rubrica = identificarRubricaID($idRubrica);
		editarRubrica($rubrica, $nome);
	break;

   case "salvarRubrica":
		$nome = JRequest::getVar('buscaNome');
		$idRubrica = JRequest::getCmd('idRubrica', false);
		$rubrica = identificarRubricaID($idRubrica);
		salvarRubrica($rubrica);
   break;

   case "atualizarRubrica":
		$nome = JRequest::getVar('buscaNome');
		$idRubrica = JRequest::getCmd('idRubrica', false);
		$rubrica = identificarRubricaID($idRubrica);
		atualizarRubrica($rubrica);
   break;
   
	case "excluirRubrica":
		$nome = JRequest::getVar('buscaNome');
		$idRubrica = JRequest::getCmd('idRubrica', false);
		excluirRubrica($idRubrica);
		listarRubricas($nome);
	break;

	case "verRubrica":
		$idRubrica = JRequest::getCmd('idRubrica', false);
		$rubrica = identificarRubricaID($idRubrica);
		relatorioRubrica($rubrica);
	break;

// ------------------------------------------ GERENCIAR AGENCIAS DE FOMENTOS 
	case "gerenciarFomentos":
		$nome = JRequest::getVar('buscaNome');
		listarFomentos($nome);
	break;

	case "addFomento":
		$nome = JRequest::getVar('buscaNome');
		addfomento(NULL, $nome);
	break;

	case "editarFomento":
		$nome = JRequest::getVar('buscaNome');
		$idFomento = JRequest::getCmd('idFomento', false);
		$fomento = identificarFomentoID($idFomento);
		editarFomento($fomento, $nome);
	break;

	case "salvarFomento":
		$nome = JRequest::getVar('buscaNome');
		$idFomento = JRequest::getCmd('idFomento', false);
		if(salvarFomento($idFomento))
			listarFomentos($nome);
		else {
			$nome = JRequest::getVar('buscaNome');    
			$fomento = identificarFomentoID($idFomento);
			addfomento(NULL, $nome);
		}
	break;
   
	case "excluirFomento":
		$nome = JRequest::getVar('buscaNome');
		$idFomento = JRequest::getCmd('idFomento', false);
		excluirFomento($idFomento);
		listarFomentos($nome);
	break;

	case "verFomento":
		$idFomento = JRequest::getCmd('idFomento', false);
		$fomento = identificarFomentoID($idFomento);
		relatorioFomento($fomento);
	break;


// ------------------------------------------ GERENCIAR CONSULTA SALDO RUBRICAS
	case "consultaSaldoRubricas":
		$buscaData = JRequest::getVar('buscaData');
		$buscaValor = JRequest::getVar('buscaValor');
		$buscaNomeRubrica = JRequest::getVar('buscaNomeRubrica');
		consultaSaldoRubricas($buscaNomeRubrica, $buscaValor, $buscaData);
	break;    
    
	case "gerenciarConsultasaldorubricas":
		$buscaData = JRequest::getVar('buscaData');
		$buscaValor = JRequest::getVar('buscaValor');
		$buscaNomeRubrica = JRequest::getVar('buscaNomeRubrica');
		listarConsultasaldorubricas($buscaNomeRubrica, $buscaValor, $buscaData);
	break;

   
// ------------------------------------------ DESPESAS RUBRICA - CONSULTA SALDO RUBRICAS SETADO
	case "gerenciarDespesasrubricas":
		$descricao = JRequest::getVar('buscaDescricao');
		listarDespesasrubricas($descricao);
	break;

	case "addDespesarubrica":
		$descricao = JRequest::getVar('buscaDescricao');
		$idRubrica = JRequest::getVar('idRubrica');
		addDespesarubrica(NULL, $descricao, $idRubrica);
	break;

	case "editarDespesarubrica":
		$descricao = JRequest::getVar('buscaDescricao');
		$idDespesarubrica = JRequest::getCmd('idDespesarubrica', false);
		$despesarubrica = identificarDespesarubricaID($idDespesarubrica);
		editarDespesarubrica($despesarubrica, $descricao);
	break;

	case "salvarDespesarubrica":
	$descricao = JRequest::getVar('buscaDescricao');
	$idDespesarubrica = JRequest::getCmd('idDespesarubrica', false);
	if(salvarDespesarubrica($idDespesarubrica))
		listarDespesasrubricas($descricao);
	else {
		$descricao = JRequest::getVar('buscaDescricao');    
		$despesarubrica = identificarDespesarubricaID($idDespesarubrica);
		addDespesarubrica(NULL, $descricao);
	}
	break;
   
	case "excluirDespesarubrica":
		$descricao = JRequest::getVar('buscaDescricao');
		$idDespesarubrica = JRequest::getCmd('idDespesarubrica', false);
		excluirDespesarubrica($idDespesarubrica);
		listarDespesasrubricas($descricao);
	break;

	case "verDespesarubrica":
		$idDespesarubrica = JRequest::getCmd('idDespesarubrica', false);
		$despesarubrica = identificarDespesarubricaID($idDespesarubrica);
		relatorioDespesarubrica($despesarubrica);
	break;
	
	
	default:
    	listarServicosControleProjetos();
    break;
}

?>
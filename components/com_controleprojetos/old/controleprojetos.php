<?php
/***********************************************************************
*               UFAM - Universidade Ferderal do Amazonas
* 
*      Funcoes de controle do modulo Despesa,desenvolvido em 10/02/2013
* adaptado do modulo professor, para update do site icomp sob orientacao
* do Prof Arilo Dias. 
* 
* Sitemas de informacao, Equipe Ferpa Augusto, Denise e Diana
* usando padrao de desenvolvimento ágil
* 
************************************************************************/

$user = JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );
include_once("components/com_controleprojetos/gerenciarfomento.php");
include_once("components/com_controleprojetos/gerenciarprojeto.php");
include_once("components/com_controleprojetos/gerenciarrubrica.php");
include_once("components/com_controleprojetos/rubricadeprojeto.php");
include_once("components/com_controleprojetos/despesas.php");
include_once("components/com_controleprojetos/controleprojetos.html.php");

$task = JRequest::getCmd('task', false);
$Itemid = JRequest::getInt('Itemid', 0);
$database	= JFactory::getDBO();

switch ($task) {
	case "validalogin":
	   $senha = $_POST['senha'];
	   $email = $_POST['email'];
	   if(secretaria($email, $senha))
		  listarServicos();
	   else
		  formLogin(true);
	   break;

  
// ---------------------------------------------- GERENCIAR PROJETOS P&D ----------------------------------------------
	case "gerenciarProjetopds":
		$nome = JRequest::getVar('buscaNome');
		listarProjetopds($nome);
	break;

	case "addProjetopd":
		$nome = JRequest::getVar('buscaNome');
		addProjetopd(NULL, $nome);
	break;

	case "editarProjetopd":
		$nome = JRequest::getVar('buscaNome');
		$idProjetopd = JRequest::getCmd('idProjetopd', false);
		$projetopd = identificarProjetopdID($idProjetopd);
		editarProjetopd($projetopd, $nome);
	break;

	case "salvarProjetopd":
		$nome = JRequest::getVar('buscaNome');
		$idProjetopd = JRequest::getCmd('idProjetopd', false);
		if (salvarProjetopd($idProjetopd))
			listarProjetopds($nome);
		else {
			$nome = JRequest::getVar('buscaNome');    
			$projetopd = identificarProjetopdID($idProjetopd);
			addProjetopd(NULL, $nome);
		}
	break;
	
	case "atualizarProjetopd":
		$nome = JRequest::getVar('buscaNome');
		$idProjetopd = JRequest::getCmd('idProjetopd', false);
		$projetopd = identificarProjetopdID($idProjetopd);
		atualizarProjetopd($projetopd);
	break;
   
	case "excluirProjetopd":
		$nome = JRequest::getVar('buscaNome');
		$idProjetopd = JRequest::getCmd('idProjetopd', false);
		excluirProjetopd($idProjetopd);
		listarProjetopds($nome);
	break;
	
	case "verProjetopd":
		$idProjetopd = JRequest::getCmd('idProjetopd', 0);
		$projetopd = identificarProjetopdID($idProjetopd);
		relatorioProjetopd($projetopd);
	break;
   
// ---------------------------------------------- GERENCIAR RUBRICAS   ----------------------------------------------
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

// ---------------------------------------------- RUBRICA DE PROJETOS ---------------------------------------------- 
	case "gerenciarRubricadeprojetos":
		$idProjetopd = JRequest::getCmd('idProjetopd', 0);
		$projetopd = identificarProjetopdID($idProjetopd);
		listarRubricadeprojetos($projetopd);
	break;
	
	case "addRubricadeprojeto":
		$descricao = JRequest::getVar('buscaDescricao');
		addRubricadeprojeto(NULL, $descricao);
	break;

	case "editarRubricadeprojeto":
		$descricao = JRequest::getVar('buscaDescricao');
		$idRubricadeprojeto = JRequest::getCmd('idRubricadeprojeto', false);
		$rubricadeprojeto = identificarRubricadeprojetoID($idRubricadeprojeto);
		editarRubricadeprojeto($rubricadeprojeto, $descricao);
	break;
	
	case "salvarRubricadeprojeto":
		$idRubricadeprojeto = JRequest::getCmd('idRubricadeprojeto', false);
		$rubricadeprojeto = identificarRubricadeprojetoID($idRubricadeprojeto);		
		salvarRubricadeprojeto($rubricadeprojeto);
	break;
   
	case "excluirRubricadeprojeto":
		$descricao = JRequest::getVar('buscaDescricao');
		$idRubricadeprojeto = JRequest::getCmd('idRubricadeprojeto', false);
		excluirRubricadeprojeto($idRubricadeprojeto);
		listarRubricadeprojetos($descricao);
	break;
	
	case "verRubricadeprojeto":
		$idRubricadeprojeto = JRequest::getCmd('idRubricadeprojeto', false);
		$rubricadeprojeto = identificarRubricadeprojetoID($idRubricadeprojeto);
		relatorioRubricadeprojeto($rubricadeprojeto);
	break;

// ---------------------------------------------- DESPESAS ----------------------------------------------
	case "gerenciarDespesas":
		$descricao = JRequest::getVar('buscaDescricao');
		listarDespesas($descricao);
	break;

	case "addDespesa":
		$descricao = JRequest::getVar('buscaDescricao');
		addDespesa(NULL, $descricao);
	break;
	
	case "editarDespesa":
		$descricao = JRequest::getVar('buscaDescricao');
		$idDespesa = JRequest::getCmd('idDespesa', false);
		$Despesa = identificarDespesaID($idDespesa);
		editarDespesa($Despesa, $descricao);
	break;

	case "salvarDespesa":
		$descricao = JRequest::getVar('buscaDescricao');
		$idDespesa = JRequest::getCmd('idDespesa', false);
		if(salvarDespesa($idDespesa))
			listarDespesas($descricao);
		else {
			//$descricao = mosGetParam($_REQUEST, 'buscaDescricao', '');
			$descricao = JRequest::getVar('buscaDescricao');    
			$Despesa = identificarDespesaID($idDespesa);
			//editarDespesa($Despesa, $descricao);
			addDespesa(NULL, $descricao);
		}
	break;
   
	case "excluirDespesa":
		$descricao = JRequest::getVar('buscaDescricao');
		$idDespesa = JRequest::getCmd('idDespesa', false);
		excluirDespesa($idDespesa);
		listarDespesas($descricao);
	break;
	
	case "verDespesa":
		$idDespesa = JRequest::getCmd('idDespesa', false);
		$Despesa = identificarDespesaID($idDespesa);
		relatorioDespesa($Despesa);
	break;
 
// ---------------------------------------------- GERENCIAR AGENCIAS DE FOMENTOS ---------------------------------------------- 
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
			//$nome = mosGetParam($_REQUEST, 'buscaNome', '');
			$nome = JRequest::getVar('buscaNome');    
			$fomento = identificarFomentoID($idFomento);
			//editarFomento($fomento, $nome);
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

// ---------------------------------------------- GERENCIAR DESPESAS ----------------------------------------------

	case "gerenciarDespesa":
		$descricao = JRequest::getVar('buscaDescricao');
		listarDespesas($descricao);
	break;
	
	case "addDespesa":
		$descricao = JRequest::getVar('buscaDescricao');
		addDespesa(NULL, $descricao);
	break;

	case "editarDespesa":
		$descricao = JRequest::getVar('buscaDescricao');
		$idDespesa = JRequest::getCmd('idDespesa', false);
		$Despesa = identificarDespesaID($idDespesa);
		editarDespesa($Despesa, $descricao);
	break;
	
	case "salvarDespesa":
		$descricao = JRequest::getVar('buscaDescricao');
		$idDespesa = JRequest::getCmd('idDespesa', false);
		if(salvarDespesa($idDespesa))
			listarDespesas($descricao);
		else {
			//$descricao = mosGetParam($_REQUEST, 'buscaDescricao', '');
			$descricao = JRequest::getVar('buscaDescricao');    
			$Despesa = identificarDespesaID($idDespesa);
			//editarDespesa($Despesa, $descricao);
			addDespesa(NULL, $descricao);
		}
	break;
   
	case "excluirDespesa":
		$descricao = JRequest::getVar('buscaDescricao');
		$idDespesa = JRequest::getCmd('idDespesa', false);
		excluirDespesa($idDespesa);
		listarDespesas($descricao);
	break;
	
	case "verDespesa":
		$idDespesa = JRequest::getCmd('idDespesa', false);
		$Despesa = identificarDespesaID($idDespesa);
		relatorioDespesa($Despesa);
	break;

// ---------------------------------------------- LISTANDO OS SERVI�OS ----------------------------------------------
	default:
    	listarServicosControleProjetos();
    break;
}

?>
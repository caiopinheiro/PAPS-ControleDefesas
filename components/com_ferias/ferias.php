<?php

$user =& JFactory::getUser();
$ano = date("Y");
if(!$user->username) die( 'Acesso Restrito.' );

include_once("components/com_ferias/ferias.html.php");
include_once("components/com_ferias/ferias.secretaria.html.php");

$task = JRequest::getCmd('task', false);
$Itemid = JRequest::getInt('Itemid', 0);
$database	=& JFactory::getDBO();

switch ($task) {
    // ------------------------ RESERVA DE SALAS ------------------------
	case "addFerias":
		addFerias($user);
	break;
	
	case "saveFerias":	
		$sucesso = salvarFerias($user);
		if($sucesso)
			listFerias($user->id, $ano);
		else
			addFerias($user);		
	break;
	
	case "deleteFerias":	
		$idFerias = JRequest::getVar('idFerias');
		$ano = JRequest::getVar('fecha');
		confirmarExclusao($idFerias);
		listFerias($user->id, $ano);
	break;

	case "removeFerias":	
		$idFerias = JRequest::getVar('idFerias');
		$ano = JRequest::getVar('fecha');
		excluirFerias($idFerias);
		listFerias($user->id, $ano);
	break;
	
	case "listFeriasProfessores":
		$ano = JRequest::getVar('fecha');
		$professor = JRequest::getVar('professor');
		listFeriasProfessores($professor, $ano);
	break;		

	case "viewFeriasProfessor":
		$ano = JRequest::getVar('fecha');
		$idProfessor = JRequest::getVar('idProfessor');
		$userSelecionado = getUser($idProfessor);
		listFeriasProfessor($userSelecionado, $ano);
	break;		
	
	case "addFeriasSecretaria":
		$idProfessor = JRequest::getVar('idProfessor');
		$ano = JRequest::getVar('fecha');
		$userSelecionado = getUser($idProfessor);
		if($userSelecionado)
			addFeriasSecretaria($userSelecionado, $ano);	
		else
			echo "ERRO";
	break;		
	
	case "saveFeriasSecretaria":	
		$idProfessor = JRequest::getVar('idProfessor');
		$ano = JRequest::getVar('fecha');
		$userSelecionado = getUser($idProfessor);
		
		if($userSelecionado){
			$sucesso = salvarFerias($userSelecionado);
			if($sucesso)
				listFeriasProfessor($userSelecionado, $ano);
			else
				addFeriasSecretaria($userSelecionado, $ano);		
		}
		else
			echo "ERRO";
	break;		
	
	case "deleteFeriasSecretaria":	
		$idFerias = JRequest::getVar('idFerias');
		$idProfessor = JRequest::getVar('idProfessor');
		$ano = JRequest::getVar('fecha');
		$userSelecionado = getUser($idProfessor);
		excluirFerias($idFerias);
		listFeriasProfessor($userSelecionado, $ano);
	break;				

	default:
		// listarServicos(); PARA MAIS ITENS
		$ano = JRequest::getVar('fecha');
		listFerias($user->id, $ano);
	break;
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

// DATA SQL
function dataSql($dataBr) {
	if (!empty($dataBr)) {
		$p_dt = explode('/',$dataBr);
		$data = $p_dt[2].'-'.$p_dt[1].'-'.$p_dt[0];
		
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

function salvarFerias($user) {
	$database =& JFactory::getDBO();
	
	$tipo = JRequest::getVar('tipo');	
	$dataSaida = JRequest::getVar('datasaida');
	$dataSaida = dataSql($dataSaida);
	$dataRetorno = JRequest::getVar('dataretorno');
	$dataRetorno = dataSql($dataRetorno);
	
	$funcionou = true;
	
	$fim = explode("-", $dataRetorno);
	$ano = $fim[0];

	$inicio = explode("-", $dataSaida);

	$funcionou = checkdate($inicio[1],$inicio[2],$inicio[0]);
	
	if($funcionou)
		$funcionou = checkdate($fim[1],$fim[2],$fim[0]);
	
	if($funcionou && $dataSaida > $dataRetorno)
		$funcionou = false;
		
	if($funcionou){
				
		$sql = "SELECT tipo, YEAR(dataSaida) as ano, SUM(DATEDIFF(dataRetorno,dataSaida))+1 AS tot FROM #__ferias WHERE idusuario = $user->id AND tipo= $tipo AND YEAR(dataSaida) = $ano GROUP BY YEAR(dataSaida)";
		$database->setQuery($sql);
		$totais = $database->loadObjectList();
		
		$erro = false;
		$diasFerias = 0;
		if($totais){
			$novoTotal = $totais[0]->tot + (dateDiff($dataSaida, $dataRetorno));
		}
		else
			$novoTotal = (dateDiff($dataSaida, $dataRetorno));		
		
		if(ehProfessor($user->id)) $diasFerias = 45;
		else $diasFerias = 30;
		if($novoTotal > $diasFerias)
			$erro = true;
		
		if(!$erro){
			$sql = "INSERT INTO `#__ferias` (`idusuario`, `nomeusuario`, `emailusuario`, `tipo`, `dataSaida`, `dataRetorno`) VALUES ('$user->id', '$user->name', '$user->email', $tipo, '$dataSaida', '$dataRetorno');";
			$database->setQuery($sql);
			$funcionou = $database->Query();
	
			if($funcionou){
				echo '<div class="alert alert-success">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>CADASTRO:</b> Registro de F&#233;rias realizado com sucesso!
			  </div>';
			  return true;
			} else {
				echo '<div class="alert alert-error">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>CADASTRO:</b> N&#227;o foi poss&#237;vel registar o pedido de f&#233;rias, tente novamente!
			  </div>';
			  return(false);
			}
		}
		else{
				echo '<div class="alert alert-error">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>CADASTRO:</b> N&#227;o foi poss&#237;vel registar o pedido de f&#233;rias, pois ultrapassa o total de dias previstos para esta categoria de funcion&#225;rio. Tente novamente com outras datas!
			  </div>';
			  return(false);
		
		}
	}
	else{
		echo '<div class="alert alert-error">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>CADASTRO:</b> N&#227;o foi poss&#237;vel registrar este pedido de f&#233;rias, pois as datas s&#227;o inv&#225;lidas ou a data de t&#233;rmino &#233; anterior &#224; data de in&#237;cio. Tente novamente em outras datas!
			  </div>';
			  return(false);
			  
	}
}

function excluirFerias($idFerias) {
	$database = JFactory::getDBO();
	
	$sql = "DELETE FROM #__ferias WHERE id = '$idFerias'";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	if($funcionou) {
		echo '<div class="alert alert-success">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>EXCLUS&#195;O :</b> Registro de F&#233;rias de sala exclu&#237;do com sucesso!
			  </div>';
	} else {
		echo '<div class="alert alert-error">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>EXCLUS&#195;O :</b> Não foi poss&#237;vel desfazer o registro de f&#233;rias, tente novamente!
			  </div>';
	}
	
} 

function getUser($idUser) {
	$database = JFactory::getDBO();
	
	$sql = "SELECT * FROM #__users WHERE id = '$idUser'";
	$database->setQuery($sql);
	$users = $database->loadObjectList();
	
	if($users) return $users[0];	
	else return false;
} 

function dateDiff($start, $end) {

	$start_ts = strtotime($start);
	$end_ts = strtotime($end);
	$diff = $end_ts - $start_ts;

	return round($diff / 86400);

}

function ehProfessor($idUser) {
	$database = JFactory::getDBO();
	

	$sql = "SELECT * FROM #__user_usergroup_map WHERE user_id = '$idUser' AND group_id = '9'";
	$database->setQuery($sql);
	$users = $database->loadObjectList();
	
	if($users)
		return true;	
	else return false;
} 

?>

<?php

$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

include_once("components/com_reserva/reserva.html.php");
include_once("components/com_reserva/gerenciarreservas.php");

$task = JRequest::getCmd('task', false);
$Itemid = JRequest::getInt('Itemid', 0);
$database	=& JFactory::getDBO();

switch ($task) {
    // ------------------------ RESERVA DE SALAS ------------------------
	case "addReserva":
		telaCadastrarReserva();
	break;
	
	case "gerenciarReservas":
		$atividade = JRequest::getVar('atividade', false);
		$tipo = JRequest::getVar('tipo', false);		
		$dataInicio = JRequest::getVar('dataInicio', false);
		$dataTermino = JRequest::getVar('dataTermino', false);
		listarReservas($atividade, $tipo, $dataInicio, $dataTermino);
	break;
	
	case "cadReserva":	
		salvarReserva();
	break;
	
	case "editReserva":
		$idReserva = JRequest::getVar('idReserva');	
		telaEditarReserva($idReserva);
	break;
	
	case "atualizarReserva":
		$idReserva = JRequest::getVar('idReserva');	
		atualizarReserva($idReserva);
	break;
		
	case "confirmarExclusao":	
		$idReserva = JRequest::getVar('idReserva');
		confirmarExclusao($idReserva);
	break;
	
	case "excluirReserva":	
		$idReserva = JRequest::getVar('idReserva');
		excluirReserva($idReserva);
	break;	
	
	case "addSala":
		telaCadastrarSala();
	break;
	
	case "cadSala":	
		salvarSala();
	break;
	
	case "gerenciarSala":
		listarSalas();
	break;
	
	case "excluirSala":
		$idSala = JRequest::getVar('idSala');
		excluirSala($idSala);
	break;
	
	case "verTabelaHorario":
		$dia = JRequest::getVar('dia');
		$mes = JRequest::getVar('mes');
		$ano = JRequest::getVar('ano');		
		tabelaHorario($dia, $mes, $ano);
	break;
	
	case "periodo":
		telaCadastrarPeriodo();
	break;
	
	case "cadPeriodo":
		salvarPeriodo();
	break;
  
	default:
		// listarServicos(); PARA MAIS ITENS
		telaCadastrarReserva();
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

// VERIFICAR RESERVAS
function verificarReservas($dia, $mes, $ano) {
	$database = JFactory::getDBO();
	
    $sql = "SELECT COUNT(*) as total FROM #__reservas WHERE DAY(dataInicio) = '$dia' AND MONTH(dataInicio) = '$mes' AND YEAR(dataInicio) = '$ano'";
    $database->setQuery($sql);
    $dados = $database->loadObjectList();
	$total = $dados[0]->total;
	
	return $total;
}

// IDENTIFICAR RESERVA POR ID
function identificarReservaId($idReserva) {
	$database = JFactory::getDBO();
	
    $sql = "SELECT R.id, R.sala, R.idSolicitante, R.atividade, R.tipo, R.dataInicio, R.dataTermino, R.horaInicio, R.horaTermino, U.name, S.nome
			FROM #__reservas as R
			JOIN #__users as U ON U.id = R.idSolicitante
			JOIN #__reservas_salas as S ON S.id = R.sala
			WHERE R.id = '$idReserva'";
    $database->setQuery($sql);
    $dados = $database->loadObjectList();
	
	return $dados[0]; // QUANDO HOUVER RESULTADO UNICO
	//return $dados; // QUANDO HOUVER MAIS DE UM RESULTADO
}

// IDENTIFICAR RESERVAS POR DIA E MES
function identificarReservas($dia, $mes, $ano) {
	$database = JFactory::getDBO();
	
    $sql = "SELECT R.id, R.sala, R.tipo, R.idSolicitante, R.atividade, R.dataInicio, R.dataTermino, R.horaInicio, R.horaTermino, U.name, S.nome
			FROM #__reservas as R
			JOIN #__users as U ON U.id = R.idSolicitante
			JOIN #__reservas_salas as S ON S.id = R.sala			
			WHERE DAY(dataInicio) = '$dia' AND MONTH(dataInicio) = '$mes' AND YEAR(dataInicio) = '$ano'
			ORDER BY R.horaInicio ASC";
    $database->setQuery($sql);
    $dados = $database->loadObjectList();
	
	//return $dados[0]; // QUANDO HOUVER RESULTADO UNICO
	return $dados; // QUANDO HOUVER MAIS DE UM RESULTADO
}

// IDENTIFICAR RESERVAS POR HORA E DIA E SALA
function identificarReservasHora($hora, $dia, $mes, $sala) {
	$database = JFactory::getDBO();
	
    $sql = "SELECT R.id, R.sala, R.tipo, R.idSolicitante, R.atividade, R.dataInicio, R.dataTermino, R.horaInicio, R.horaTermino, U.name, S.nome
			FROM #__reservas as R
			JOIN #__users as U ON U.id = R.idSolicitante
			JOIN #__reservas_salas as S ON S.id = R.sala
			WHERE R.idSolicitante = U.id
			AND S.id = $sala
			AND DAY(dataInicio) = '$dia' AND MONTH(dataInicio) = '$mes' AND horaInicio = '$hora'
			ORDER BY R.horaInicio ASC";
    $database->setQuery($sql);
    $dados = $database->loadObjectList();
	
	//return $dados[0]; // QUANDO HOUVER RESULTADO UNICO
	return $dados; // QUANDO HOUVER MAIS DE UM RESULTADO
}

// VERIFICAR SALAS
function identificarSalas() {
	$database = JFactory::getDBO();
	
    $sql = "SELECT * FROM #__reservas_salas";
    $database->setQuery($sql);
    $dados = $database->loadObjectList();
	
	return $dados;
}

// VERIFICAR SE EH SECRETARIA
function ehSecretaria($idUser) {
	$database = JFactory::getDBO();
	
    $sql = "SELECT U.id FROM #__users AS U
			JOIN #__user_usergroup_map AS UM ON UM.user_id = U.id
			JOIN #__usergroups AS UG ON UM.group_id = UG.id
			WHERE UG.title = 'Secretaria' AND U.id = $idUser";
    $database->setQuery($sql);
    $dados = $database->loadObjectList();
	
	return $dados;
}

// VERIFICAR SE A RESERVA JÁ PASSOU
function reservaPassada($dataInicio, $horaInicio, $horaTermino){
	$dataAtual = date('Y-m-d');
	$horaAtual = date('h:i');
	if($dataInicio > $dataAtual)
		return true;
	else if($dataInicio == $dataAtual && $horaTermino > $hotaAtual)
		return true;
	return false;
}

// CALCULAR HORARIO
function dif_horario($horario1, $horario2) {
    $horario1 = strtotime("1/1/1980 $horario1");
    $horario2 = strtotime("1/1/1980 $horario2");
         
 if ($horario2 < $horario1) {
    $horario2 = $horario2 + 86400;
 }
 
 $hora = array('hora'=> (int) (($horario2 - $horario1) / 3600), 'minuto' => (($horario2 - $horario1) % 3600));
 return (int)2*($horario2 - $horario1) / 3600;      
}

// CALCULAR QUANTIDADE DE RESERVAS
function qtdeReservas($idUser){
	$database = JFactory::getDBO();

	$sql = "SELECT COUNT(*) as total FROM #__reservas WHERE idSolicitante = '$idUser' AND CONCAT(dataInicio,' ',horaInicio) > NOW()";
    $database->setQuery($sql);
    $dados = $database->loadObjectList();
	
	return $dados[0]->total;
}

?>

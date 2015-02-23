<?php 
	$user = JFactory::getUser();
	if(!$user->username) die( 'Acesso Restrito.' );
	
	include_once("components/com_bolsas/bolsas.html.php");
	include_once("components/com_bolsas/gerenciarbolsas.php");
	include_once("components/com_bolsas/gerenciaralocacao.php");
	include_once('pdf-php/class.ezpdf.php');
	
	$task = JRequest::getCmd('task', false);
	$Itemid = JRequest::getInt('Itemid', 0);
	$database =& JFactory::getDBO();
	
	switch ($task) {
		
		// -------------------- GERENCIAR BOLSAS --------------------
		case "gerenciarBolsas":
			$agencia = JRequest::getCmd('buscaAgencia', false);
			$categoria = JRequest::getCmd('buscaCategoria', false);
			$status = JRequest::getCmd('buscaStatus', false);
			listarBolsas($agencia, $categoria, $status);
		break;
		
		case "addBolsa":
			telaCadastrarBolsa();
		break;
		
		case "cadBolsa":			
			salvarBolsa();
		break;
		
		case "editarBolsa":	
			$idBolsa = JRequest::getVar('idBolsa');
			$bolsa = identificarBolsa($idBolsa);
			telaEditarBolsa($bolsa);
		break;
		
		case "atualizarBolsa":	
			$idBolsa = JRequest::getVar('idBolsa');
			atualizarBolsa($idBolsa);
		break;
		
		case "confirmarExclusao":	
			$idBolsa = JRequest::getVar('idBolsa');
			confirmarExclusao($idBolsa);
		break;
		
		case "excluirBolsa":	
			$idBolsa = JRequest::getVar('idBolsa');
			excluirBolsa($idBolsa);
		break;
		
		default:
			listarOpcoesBolsa();
		break;
		
		// -------------------- GERENCIAR ALOCAÇÃO ------------------
		case "gerenciarAlocacao":
			listarAlocacoes();
		break;
		
		case "addAlocacao":
			telaCadastrarAlocacao();
		break;
		
		case "alocarBolsista":
			$idBolsa = JRequest::getVar('idBolsa');
			$bolsa = identificarBolsa($idBolsa);
			telaCadastrarAlocacao($bolsa);
		break;
		
		case "cadAlocacao":
			$idBolsa = JRequest::getVar('idBolsa');
			salvarAlocacao($idBolsa);
		break;
		
		case "desalocarBolsista":
			$idBolsista = JRequest::getVar('idBolsista');
			$bolsista = identificarBolsista($idBolsista);
			telaDesalocarBolsista($bolsista);
		break;
		
		case "cadDesalocacao":
			$idBolsaAloc = JRequest::getVar('idBolsaAloc');			
			$idBolsa = JRequest::getVar('idBolsa');
			$idAluno = JRequest::getVar('idAluno');
			$dataInicio = JRequest::getVar('dataInicio');
			$dataTermino = JRequest::getVar('dataTermino');
			salvarDesalocacao($idBolsaAloc, $idBolsa, $idAluno, $dataInicio, $dataTermino);
		break;
		
		// -------------------- HISTÓRICO DE ALOCAÇÃO ------------------
		case "historico":
			$agencia = JRequest::getCmd('buscaAgencia', false);
			$categoria = JRequest::getCmd('buscaCategoria', false);
			$area = JRequest::getCmd('buscaArea', false);
			$mesInicio = JRequest::getCmd('buscaInicio', false);
			$mesFim = JRequest::getCmd('buscaFim', false);
			$ano = JRequest::getCmd('buscaAno', false);
			listarHistorico($agencia, $categoria, $area, $mesInicio, $mesFim, $ano);
		break;
		
		case "imprimirHistorico":
			$agencia = JRequest::getCmd('agencia', false);
			$categoria = JRequest::getCmd('categoria', false);
			$area = JRequest::getCmd('area', false);			
			$mes = JRequest::getCmd('mes', false);
			visualizarPDF($agencia, $categoria, $area, $mes);
		break;	
	} 


// FUNÇÕES : CONVERSÃO DE DATAS E VALORES	

// DATA BR
function dataBr($dataSql) {
	if (!empty($dataSql)) {
		$p_dt = explode('-',$dataSql);
    	$data_brProjetopd = $p_dt[2].'/'.$p_dt[1].'/'.$p_dt[0];
		return $data_brProjetopd;
	}
}
 
// DATA SQL
function dataSql($dataBr) {
	if (!empty($dataBr)) {
		$p_dt = explode('/',$dataBr);
		$data_sqlProjetopd = $p_dt[2].'-'.$p_dt[1].'-'.$p_dt[0];
		
		return  $data_sqlProjetopd;
	}
}

// MOEDA BR
function moedaBr($ValorBr) {
    $ValorBr = number_format($ValorBr, 2, ',','.');
	
    return $ValorBr;
}
	
// MOEDA SQL	
function moedaSql($ValorSql) {
    $origem = array('.', ','); 
    $destino = array('', '.');
    $ValorSql = str_replace($origem, $destino, $ValorSql); //remove os pontos e substitui a virgula pelo ponto
	
    return $ValorSql; //retorna o valor formatado para gravar no banco
}

// AGENCIAS
function listaAgencias(){
    $database = JFactory::getDBO();
	
    $sql = "SELECT * FROM #__contproj_agencias ORDER BY sigla";
    $database->setQuery( $sql );
    $agencias = $database->loadObjectList();
	
    return ($agencias);
}

// AGENCIAS
function listaLinhasPesquisa(){
    $database = JFactory::getDBO();
	
    $sql = "SELECT * FROM #__linhaspesquisa ORDER BY id";
    $database->setQuery( $sql );
    $linhas = $database->loadObjectList();
	
    return ($linhas);
}

// IDENTIFICAR MES
function identificarMes($mes) {
    switch ($mes) {
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
	return $mes;
}

// IDENTIFICAR BOLSA
function identificarBolsa($idBolsa){
    $database = JFactory::getDBO();
	
    $sql = "SELECT * FROM #__bolsas WHERE id = $idBolsa LIMIT 1";
    $database->setQuery( $sql );
    $bolsa = $database->loadObjectList();
	
    return ($bolsa[0]);
}

// IDENTIFICAR BOLSISTA
function identificarBolsista($idBolsista){
    $database = JFactory::getDBO();
	
    $sql = "SELECT BA.id, BA.idAluno, BA.idBolsa, BA.dataInicio, BA.dataTermino, B.id as idBolsa, B.agencia, B.categoria, A.id as idAluno, A.nome, A.area
			FROM #__bolsas_aloc as BA, #__bolsas as B, #__aluno as A
			WHERE ba.idBolsa = B.id
			AND BA.idAluno = A.id
			AND BA.idAluno = '$idBolsista'";
    $database->setQuery( $sql );
    $bolsista = $database->loadObjectList();
	
    return ($bolsista[0]);
}

// TOTAL DE BOLSAS DISPONIVEIS
function calcularBolsasDisp($idBolsa){
    $database = JFactory::getDBO();
	
    $sql1 = "SELECT quantidade FROM #__bolsas WHERE id = '$idBolsa'";
    $database->setQuery($sql1);
    $bolsa = $database->loadObjectList();
	
	$sql2 = "SELECT COUNT(*) as bolsistas FROM #__bolsas_aloc WHERE idBolsa = '$idBolsa' AND status='Ativa'";
    $database->setQuery($sql2);
    $bolsa_aloc = $database->loadObjectList();
	
	$total = $bolsa[0]->quantidade;
	$bolsitas = $bolsa_aloc[0]->bolsistas;
	
	$disponiveis = $total - $bolsitas;
	$alocadas = $bolsitas;
	
	return $alocadas;
}

// IDENTIFICAR LINHA DE PESQUISA DO ALUNO
function linhaPesquisa($idArea) {
	switch ($idArea) {
		case 1: $area = "Banco de Dados"; break;
		case 2: $area = "Engenharia de Software"; break;
		case 3: $area = "Inteligência Artificial"; break;
		case 4: $area = "Visão Computacional"; break;
		case 5: $area = "Redes de Computadores"; break;
		case 6: $area = "Otimização e Algoritmos"; break;
	}
	return($area);
}

// ALUNOS DISPONIVEIS PARA ALOCAÇÃO DE BOLSA : VERIFICAR CONDIÇÕES DO ALUNO
function alunosDisponiveis($categoria){
    $database = JFactory::getDBO();
	
	if ($categoria == 'Mestrado')
		$curso = '1';
	elseif ($categoria == 'Doutorado')
		$curso = '2';
	else
		$curso = '3';	// especial

	
    $sql = "SELECT id, nome, curso, status
			FROM #__aluno
			WHERE curso = '$curso'
			AND status = '0'
			AND id NOT IN (SELECT idAluno FROM #__bolsas_aloc)
			ORDER BY nome";
    $database->setQuery($sql);
    $alunosDisponiveis = $database->loadObjectList();
	
	return $alunosDisponiveis;
}

?>
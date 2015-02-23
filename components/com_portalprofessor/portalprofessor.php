<?php
$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

jimport('joomla.filesystem.path');
include_once("components/com_portalprofessor/alunoppgi.php");
include_once("components/com_portalprofessor/turmasppgi.php");
include_once("components/com_portalprofessor/pit&rit.php");
include_once("components/com_portalprofessor/prorrogacao.php");
include_once("components/com_portalprofessor/trancamento.php");
include_once("components/com_portalprofessor/projetos.php");
include_once('pdf-php/class.ezpdf.php');

$task = JRequest::getCmd('task', false);
$Itemid = JRequest::getInt('Itemid', 0);

$professor = identificarProfessor($user->id);

switch ($task) {
	case "enviar":
		$local = $_POST['local'];
		$tipo = $_POST['tipo'];
		$datasaida = $_POST['datasaida'];
		$dataretorno = $_POST['dataretorno'];
		$justificativa = $_POST['justificativa'];
		$reposicao = $_POST['reposicao'];

		salvarNotificacao($user, $local, $tipo, $datasaida, $dataretorno, $justificativa, $reposicao);
		$arquivoHTML = gerarHTML($user->name, $user->email, $local, $tipo, $datasaida, $dataretorno, $justificativa, $reposicao);
		enviarEmail($user, $local, $tipo, $datasaida, $dataretorno, $justificativa, $arquivoHTML);
			
		telafinal();
	break;
	
	case "alunos":
		$nome = JRequest::getCmd('buscaNome', false);
		$status = JRequest::getCmd('buscaStatus', false);
		$anoingresso = JRequest::getCmd('buscaAnoIngresso', false);
		$curso = JRequest::getCmd('buscaCurso', false);
		listarAlunosPPGI($nome, $status, $anoingresso, $curso, $professor);
	break;
		
	case "verAluno":
		$idAluno = JRequest::getCmd('idAluno', false);
		$aluno = identificarAlunoID($idAluno);
		historicoAluno($aluno);
	break;
	
	// -------------------- NOTIFICACOES --------------------
	case "enviarnotificacao":
		formularioNotificacaoSaida($user);
	break;
		
	case "listarnotificacoes":
		$mes = $_POST['fecha'];
		listarNotificacoes($user->id, $mes);
	break;
		
	case "vernotificacao":
		$idPedido = JRequest::getCmd('idPedido', false);
		$pedido = identificarNotificacao($idPedido);
		$arquivoHTML = gerarHTML($pedido->nomeusuario, $pedido->emailusuario, $pedido->local, $pedido->tipo, $pedido->datasaida, $pedido->dataretorno, $pedido->justificativa, $pedido->reposicao);
		header("Location: ".$arquivoHTML);			
	break;
			
	// -------------------- TURMAS --------------------
	case "listarturmas":
		$periodo = JRequest::getCmd('periodo', false);
		listarTurmas($periodo, $curso, $professor);
		break;
	case "consultarTurma":
		$idPeriodo = JRequest::getVar('idPeriodo');
		$idDisciplina = JRequest::getVar('idDisciplina');
		$turma = JRequest::getVar('turma');
		consultarTurma($idPeriodo, $idDisciplina, $turma);
	break;
	
	case "lancarnota":
		$idPeriodo = JRequest::getCmd('periodo', false);
		$idDisciplina = JRequest::getCmd('idDisciplina', false);
		$turma = JRequest::getCmd('turma', false);

		lancarNota($idPeriodo, $idDisciplina, $turma);
	break;
	
	case "salvarnota":
		$idPeriodo = JRequest::getCmd('idPeriodo', false);
		$idDisciplina = JRequest::getCmd('idDisciplina', false);
		$turma = JRequest::getCmd('turma', false);
		$alunos = $_POST['alunos'];
		$frequencias = $_POST['frequencias'];
		$conceitos = $_POST['conceitos'];

		salvarNota($idPeriodo, $idDisciplina, $turma, $alunos, $frequencias, $conceitos);
		consultarTurma($idPeriodo, $idDisciplina, $turma);
	break;
	
	case "finalizarturma":
		$idPeriodo = JRequest::getCmd('periodo', false);
		$idDisciplina = JRequest::getCmd('idDisciplina', false);
		$turma = JRequest::getCmd('turma', false);

		finalizarTurma($idPeriodo, $idDisciplina, $turma);
		consultarTurma($idPeriodo, $idDisciplina, $turma);
	break;
	
	// -------------------- PLANO DE TRABALHO --------------------
	case "pit":
		listarPITs($professor);
	break;
		
	case "addpit":
		novoPIT($professor);
	break;
	
	case "salvarpit":
		$falha = salvarPIT($professor);
		if($falha == 0){
			echo "ERRO de SQL";
		} else {
			listarPITs($professor);
		}
	break;
	
	case "removepit":
		$idPIT = JRequest::getVar('id');
		removerPIT($idPIT);
		listarPITs($professor);
	break;
	
	case "editpit1":
		$idPeriodo = JRequest::getVar('periodo');
		$PIT = identificarPIT($professor, $idPeriodo);
		editarPIT1($professor, $PIT);
	break;
	
	case "editpit2":
		$idPeriodo = JRequest::getVar('periodo');
		$PIT = identificarPIT($professor, $idPeriodo);
		editarPIT2($professor, $PIT);
	break;
	
	case "editpit3":
		$idPeriodo = JRequest::getVar('periodo');
		$PIT = identificarPIT($professor, $idPeriodo);
		editarPIT3($professor, $PIT);
	break;
	
	case "editpit4":
		$idPeriodo = JRequest::getVar('periodo');
		$PIT = identificarPIT($professor, $idPeriodo);
		editarPIT4($professor, $PIT);
	break;
	
	case "editpit5":
		$idPeriodo = JRequest::getVar('periodo');
		$PIT = identificarPIT($professor, $idPeriodo);
		editarPIT5($professor, $PIT);
	break;
	
	case "editpit6":
		$idPeriodo = JRequest::getVar('periodo');
		$PIT = identificarPIT($professor, $idPeriodo);
		editarPIT6($professor, $PIT);
	break;
	
	case "editpit7":
		$idPeriodo = JRequest::getVar('periodo');
		$PIT = identificarPIT($professor, $idPeriodo);
		editarPIT7($professor, $PIT);
	break;
	
	case "editpit8":
		$idPeriodo = JRequest::getVar('periodo');
		$PIT = identificarPIT($professor, $idPeriodo);
		editarPIT8($professor, $PIT);
	break;
	
	case "verpit":
		$idPeriodo = JRequest::getVar('idPit');
		$PIT = identificarPIT($professor, $idPeriodo);
		$arquivoPDF = verPIT($professor, $PIT);
		header("Location: ".$arquivoPDF);
	break;
	
	case "addAulaPit":
		$idPeriodo = JRequest::getVar('periodo');
		$idPIT = JRequest::getVar('idPIT');
		$falha = salvarAulaPIT($idPIT, $idPeriodo);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			$PIT = identificarPIT($professor, $idPeriodo);
			editarPIT2($professor, $PIT);
		}
	break;
	
	case "removeAulaPit":
		$idAulaPIT = JRequest::getVar('id');
		$idPeriodo = JRequest::getVar('periodo');
		$falha = removerAulaPIT($idAulaPIT);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			$PIT = identificarPIT($professor, $idPeriodo);
			editarPIT2($professor, $PIT);
		}
	break;
	
	case "addHorPit":
		$idPeriodo = JRequest::getVar('periodo');
		$idPIT = JRequest::getVar('idPIT');
		$falha = salvarHorPIT($idPIT, $idPeriodo);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			$PIT = identificarPIT($professor, $idPeriodo);
			editarPIT3($professor, $PIT);
		}
	break;
	
	case "removeHorPit":
		$idExtPIT = JRequest::getVar('id');
		$idPeriodo = JRequest::getVar('periodo');
		$falha = removerHorPIT($idExtPIT);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			$PIT = identificarPIT($professor, $idPeriodo);
			editarPIT3($professor, $PIT);
		}
	break;
	
	case "addPesqPit":
		$idPeriodo = JRequest::getVar('periodo');
		$idPIT = JRequest::getVar('idPIT');
		$falha = salvarPesqPIT($idPIT, $idPeriodo);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			$PIT = identificarPIT($professor, $idPeriodo);
			editarPIT4($professor, $PIT);
		}
	break;
	
	case "removePesqPit":
		$idPesqPIT = JRequest::getVar('id');
		$idPeriodo = JRequest::getVar('periodo');
		$falha = removerPesqPIT($idPesqPIT);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			$PIT = identificarPIT($professor, $idPeriodo);
			editarPIT4($professor, $PIT);
		}
	break;
	
	case "addExtPit":
		$idPeriodo = JRequest::getVar('periodo');
		$idPIT = JRequest::getVar('idPIT');
		$falha = salvarExtPIT($idPIT, $idPeriodo);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			$PIT = identificarPIT($professor, $idPeriodo);
			editarPIT5($professor, $PIT);
		}
	break;
	
	case "removeExtPit":
		$idExtPIT = JRequest::getVar('id');
		$idPeriodo = JRequest::getVar('periodo');
		$falha = removerExtPIT($idExtPIT);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			$PIT = identificarPIT($professor, $idPeriodo);
			editarPIT5($professor, $PIT);
		}
	break;
	
	case "addAdmPit":
		$idPeriodo = JRequest::getVar('periodo');
		$idPIT = JRequest::getVar('idPIT');
		$falha = salvarAdmPIT($idPIT, $idPeriodo);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			$PIT = identificarPIT($professor, $idPeriodo);
			editarPIT6($professor, $PIT);
		}
	break;
	
	case "removeAdmPit":
		$idExtPIT = JRequest::getVar('id');
		$idPeriodo = JRequest::getVar('periodo');
		$falha = removerAdmPIT($idExtPIT);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			$PIT = identificarPIT($professor, $idPeriodo);
			editarPIT6($professor, $PIT);
		}
	break;
	
	case "addForPit":
		$idPeriodo = JRequest::getVar('periodo');
		$idPIT = JRequest::getVar('idPIT');
		$falha = salvarForPIT($idPIT, $idPeriodo);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			$PIT = identificarPIT($professor, $idPeriodo);
			editarPIT7($professor, $PIT);
		}
	break;
	
	case "removeForPit":
		$idExtPIT = JRequest::getVar('id');
		$idPeriodo = JRequest::getVar('periodo');
		$falha = removerForPIT($idExtPIT);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			$PIT = identificarPIT($professor, $idPeriodo);
			editarPIT7($professor, $PIT);
		}
	break;
	
	case "addOutPit":
		$idPeriodo = JRequest::getVar('periodo');
		$idPIT = JRequest::getVar('idPIT');
		$falha = salvarOutPIT($idPIT, $idPeriodo);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			$PIT = identificarPIT($professor, $idPeriodo);
			editarPIT9($professor, $PIT);
		}
	break;
	
	case "removeOutPit":
		$idExtPIT = JRequest::getVar('id');
		$idPeriodo = JRequest::getVar('periodo');
		$falha = removerOutPIT($idExtPIT);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			$PIT = identificarPIT($professor, $idPeriodo);
			editarPIT9($professor, $PIT);
		}
	break;
	
	case "enviarpit":
		$idPIT = JRequest::getVar('id');
		$falha = registrarEnvioPIT($idPIT);
		if($falha == 0){
			echo "ERRO de SQL";
		}else{
			listarPITs($professor);
		}
	break;
	
	case "professores":
		$nome = JRequest::getVar('buscaNome');
		listarProfessores($nome);
	break;

	// ---------------------- PRORROGACAO ----------------------
	case "prorrogacao":
		$status = JRequest::getCmd('buscaStatus', false);		
		mostrarTelaProrrogacao($professor, $status);
	break;

	case "mostrarDetalhesProrrogacao":
		$idProrrogacao = JRequest::getVar('idSolic');
		$prorrogacao = identificarProrrogacaoID($idProrrogacao);
		$idAluno = $prorrogacao->idAluno;
		$aluno = identificarAlunoID($idAluno);		
		mostrarDetalhesProrrogacao($aluno, $prorrogacao);
	break;
		
	case "aprovarProrrogacao":
		$idAluno = JRequest::getCmd('idAluno', false);
		$aluno = identificarAlunoID($idAluno);
		$status = JRequest::getCmd('buscaStatus');		
		aprovarProrrogacao($aluno, $professor, $status);
	break;
	
	case "reprovarProrrogacao":
		$idAluno = JRequest::getCmd('idAluno', false);
		$aluno = identificarAlunoID($idAluno);
		$status = JRequest::getCmd('buscaStatus');			
		reprovarProrrocacao($aluno, $professor, $status);
	break;
	
	// ---------------------- TRANCAMENTO ----------------------
	case "trancamento":	
		$status = JRequest::getCmd('buscaStatus', false);			
		telaTrancamento($professor, $status);
	break;
	
	case "mostrarDetalhesTrancamento":
		$idAluno = JRequest::getCmd('idAluno', false);
		$aluno = identificarAlunoID($idAluno);		
		mostrarDetalhesTrancamento($aluno);
	break;
	
	case "aprovarTrancamento":
		$idAluno = JRequest::getCmd('idAluno', false);
		$aluno = identificarAlunoID($idAluno);
		$status = JRequest::getCmd('buscaStatus');		
		aprovarTrancamento($aluno, $professor, $status);
	break;
	
	case "reprovarTrancamento":
		$idAluno = JRequest::getCmd('idAluno', false);
		$aluno = identificarAlunoID($idAluno);
		$status = JRequest::getCmd('buscaStatus');			
		reprovarTrancamento($aluno, $professor, $status);
	break;
	
	case "oferta":

		$database	=& JFactory::getDBO();
		
		$idPeriodo = JRequest::getVar('idPeriodo');
		$sql = "SELECT id, periodo FROM #__periodos WHERE id = $idPeriodo";
			
		$database->setQuery($sql);
		$periodos = $database->loadObjectList();
		
		$sql = "SELECT D.id, D.codigo, D.nomeDisciplina , D.creditos , D.carga, PD.professor, PD.horario, PD.turma, PD.curso, PD.sala FROM #__disciplina as D join #__periodo_disc as PD on (D.id = PD.idDisciplina) WHERE PD.idPeriodo = $idPeriodo ORDER BY D.codigo";
		$database->setQuery( $sql );
		$disciplinas = $database->loadObjectList();
	
		imprimirOferta($disciplinas, $periodos[0]);	
	break;

	// -------------------- PROJETOS --------------------
	case "listarProjetos":
		listarProjetos($professor);
	break;
	
	case "verSimplificado":
		$idProjeto = JRequest::getVar('idProjeto');
		verProjeto($idProjeto);
	break;
	
	case "verDetalhes":
		$idProjeto = JRequest::getVar('idProjeto');
		verProjetoDetalhes($idProjeto);
	break;
			
	case 'solicitarbanca':
		header('location: index.php?option=com_defesasorientador&' . 'itemid='. $Itemid .'&view=solicitarbanca');
	
	// CASO NAO SEJA NENUMA DAS TAGS LISTADAS
	default:
		listarServicos($professor);
	break;
}

//////////////////////////////////////////////////////////////
function identificarProfessor($idUser) {
	$database	=& JFactory::getDBO();
	$database->setQuery("SELECT * from #__professores WHERE idUser = $idUser LIMIT 1");
	$professor = $database->loadObjectList();

	return ($professor[0]);
}

//////////////////////////////////////////////////////////////
function identificarNotificacao($idPedido) {
	$database	=& JFactory::getDBO();
	$database->setQuery("SELECT * from #__notificacoes_saida WHERE id = $idPedido LIMIT 1");
	$pedidos = $database->loadObjectList();

	return ($pedidos[0]);
}

//////////////////////////////////////////////////////////////
function listarServicos($professor) {
	$Itemid = JRequest::getInt('Itemid', 0); ?>
    
    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
    
    <script type="text/javascript">	
		alerta = function(){
			var $alerta = $("#alerta");
			$alerta.slideDown(500);
		
			window.setTimeout(function() {
				$alerta.slideUp(500);
			}, 10000);
		}
		
		$alerta.click(function(){
			$alerta.slideUp(500);
		});
	</script>
    
	<script language="JavaScript">        
        function abrirOferta(idPeriodo) {
           window.open("index.php?option=com_portalprofessor&Itemid=191&task=oferta&idPeriodo="+idPeriodo,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
        } 		
    </script>
    
    <h3>Servi&#231;os Oferecidos para o Professor do DCC/PPGI</h3>
    <hr />
    <br />
    
    <?php 	
		$database =& JFactory::getDBO();		
		$sqlPro = "SELECT * FROM #__prorrogacoes WHERE idOrientador = ".$professor->id." AND status = '0'";
		$database->setQuery($sqlPro);
		$prorrogacoes = $database->loadObjectList();

		$sqlTranc = "SELECT * FROM #__trancamentos WHERE idOrientador = ".$professor->id." AND status = '0'";
		$database->setQuery($sqlTranc);
		$trancamentos = $database->loadObjectList(); 
		?>
        
    <!-- DIV DE ALERTA -->
    <div id="alerta">
        <?php 
        if ($prorrogacoes) { 
            $qtde1 = count($prorrogacoes);
            echo 'ProrrogaÃƒÂ§ÃƒÂ£o de Prazo: '.$qtde1.'<br>';
        }
        
        if ($trancamentos) {
            $qtde2 = count($trancamentos);
            echo 'Trancamento de Prazo: '.$qtde2.'<br>';
        } ?>
    </div>

    <div class="cpanel">
        <div class="icon-wrapper">
            <div class="icon">
                <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=enviarnotificacao">
                <img width="32" height="32" border="0" src="components/com_portalsecretaria/images/notificacaosaida.gif" /><span><?php echo JText::_( 'Enviar Solicita&#231;&#227;o de Afastamento' ); ?></span></a>
            </div>
        </div>
    
        <div class="icon-wrapper">
            <div class="icon">
                <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=listarnotificacoes">
                <img width="32" height="32" border="0" src="components/com_portalprofessor/images/consultar.gif"><span><?php echo JText::_( 'Consultar Solicita&#231;&#245;es de Afastamento' ); ?></span></a>
            </div>
        </div>

        <div class="icon-wrapper">
            <div class="icon">
                <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=alunos">
                <img width="32" height="32" border="0" src="components/com_portalprofessor/images/alunos.png"><span><?php echo JText::_( 'Acompanhar Orientandos' ); ?></span></a>
            </div>
        </div>

        <div class="icon-wrapper">
            <div class="icon">
                <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=listarturmas">
                <img width="32" height="32" border="0" src="components/com_portalsecretaria/images/nota.gif"><span><?php echo JText::_( 'Acessar Turmas /<br > Lan&#231;ar Notas' ); ?></span></a>
            </div>
        </div>

        <div class="icon-wrapper">
            <div class="icon">
                <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=pit">
                <img width="32" height="32" border="0" src="components/com_portalsecretaria/images/periodo.png"><span><?php echo JText::_( 'Plano Individual de Trabalho(PIT)' ); ?></span></a>
            </div>
        </div>

        <div class="icon-wrapper">
            <div class="icon">
                <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=professores">
                <img width="32" height="32" border="0" src="components/com_portalsecretaria/images/professor.png"><span><?php echo JText::_( 'Professores IComp/PPGI' ); ?></span></a>
            </div>
        </div>

        <div class="icon-wrapper">
            <div class="icon">
                <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=prorrogacao">
                <img width="32" height="32" border="0" src="components/com_portalprofessor/images/prorrogacao.png"><span><?php echo JText::_( 'Prorroga&#231;&#227;o de Prazo' ); ?></span></a>
            </div>
        </div>
        
        <div class="icon-wrapper">
            <div class="icon">
                <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=trancamento">
                <img width="32" height="32" border="0" src="components/com_portalaluno/images/trancamento.png"><span><?php echo JText::_( 'Trancamento de Curso' ); ?></span></a>
            </div>
        </div>
        
		<?php
		$database->setQuery("SELECT * from #__periodos WHERE status < 2 ORDER BY periodo, status");
		$periodos = $database->loadObjectList();
		
		if($periodos) {
			$idPeriodo = $periodos[0]->id;
			$codPeriodo = $periodos[0]->periodo;
			?>
            
			<div class="icon-wrapper">
				<div class="icon">
                <a href="javascript:abrirOferta(<?php echo $idPeriodo;?>)">
           			<img width="32" height="32" border="0" src="components/com_portalaluno/images/oferta.png"><span><?php echo JText::_( 'Oferta e Ensalamento do Per&#237;odo '.$codPeriodo); ?></span></a>
				</div>
			</div>
		<?php } ?>
            
		<div class="icon-wrapper">
            <div class="icon">
            	<a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=listarProjetos">
                <img width="32" height="32" border="0" src="components/com_portalsecretaria/images/projetos.jpg"><span><?php echo JText::_('Projetos de Pesquisa'); ?></span></a>
            </div>
        </div>

	</div>

<?php }
// ---------- MENU PRINCIPAL ----------



function formularioNotificacaoSaida($user) {
	$Itemid = JRequest::getInt('Itemid', 0); ?>    
    
    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
    <link rel="stylesheet" type="text/css"href="components/com_portalprofessor/calendar-jos.css">
    <link type="text/css" rel="stylesheet" href="components/com_portalaluno/jquery-ui-1.8.20.custom.css" />
    
    <script src="components/com_portalaluno/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="components/com_portalaluno/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>    
    
    <script language="JavaScript" charset="ISO-8859-1">
		function IsEmpty(aTextField) {
        	if ((aTextField.value.length==0) || (aTextField.value==null)) {
              return true;
			} else { 
				return false; 
			}
        }
        
        function radio_button_checker(elem) {          
			var radio_choice = false;	// set var radio_choice to false
        
			// Loop from zero to the one minus the number of radio button selections
			for (counter = 0; counter < elem.length; counter++) {
				// If a radio button has been selected it will return true
				// (If not it will return false)
				if (elem[counter].checked)
					radio_choice = true;
			}
			
			return (radio_choice);
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
        
        function ValidateForm2(form) {        
			if(IsEmpty(form.local)) {
            	alert('O campo Local deve ser preenchido.')
              	form.local.focus();
	            return false;
    	    }
        
			if (!radio_button_checker(form.tipo)) {
				alert('O campo Tipo de Viagem deve ser preenchido.')
				form.tipo[0].focus();
				return (false);
			}
        
			if(IsEmpty(form.datasaida) || !VerificaData(form.datasaida.value)) {
				alert(unescape('O campo Data de Sa\xEDda deve ser preenchida com um valor v\xE1lido.'))
				form.datasaida.focus();
				return false;
			}
        
			if(IsEmpty(form.dataretorno) || !VerificaData(form.dataretorno.value)) {
				alert(unescape('O campo Data de Retorno deve ser preenchida com um valor v\xE1lido.'))
				form.dataretorno.focus();
				return false;
			}
			
			if(!validaDatas(form.datasaida.value, form.dataretorno.value)) {
				alert(unescape('A Data de Sa\xEDda deve ser inferior \xE0 Data de Retorno.'))
				form.dataretorno.focus();
				return false;
			}
        
			if(IsEmpty(form.justificativa)) {
				alert('O campo Justificativa deve ser preenchido.')
				form.justificativa.focus();
				return false;
			}
        
        	return true;
        
        }
		
		function ValidateForm(form) {        
           var erro = false;
		   form.submeter.click();
		   
		   //form.submit();
		   
		   /*
		   if(IsEmpty(form.local)) {
			
              erro = true;
           }
        
           if (!radio_button_checker(form.tipo)) {
				form.tipo.focus();
              erro = true;
           }
        
            if(IsEmpty(form.datasaida) || !VerificaData(form.datasaida.value)) {
			//	form.datasaida.focus();
              erro = true;
           }
        
            if(IsEmpty(form.dataretorno) || !VerificaData(form.dataretorno.value)) {
              erro = true;			
           }
           if(!validaDatas(form.datasaida.value, form.dataretorno.value)) {
              erro = true;		   
           }
        
           if(IsEmpty(form.justificativa)) {
              erro = true;		   		   
           }
		   if(erro){
              alert('Veja quais campos foram preenchidos incorretamente e corrija o formulario.')
              return false;
			}
		
			form.submit();*/
        
        }		
    </script>
    
    <!--<script>
        $(function() {
            $( "#datasaida" ).datepicker({dateFormat: 'dd/mm/yy'});
        });
        $(function() {
            $( "#dataretorno" ).datepicker({dateFormat: 'dd/mm/yy'});
        });
    </script>-->
    
	<style type="text/css">
	  input:required:invalid, input:focus:invalid {
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAeVJREFUeNqkU01oE1EQ/mazSTdRmqSxLVSJVKU9RYoHD8WfHr16kh5EFA8eSy6hXrwUPBSKZ6E9V1CU4tGf0DZWDEQrGkhprRDbCvlpavan3ezu+LLSUnADLZnHwHvzmJlvvpkhZkY7IqFNaTuAfPhhP/8Uo87SGSaDsP27hgYM/lUpy6lHdqsAtM+BPfvqKp3ufYKwcgmWCug6oKmrrG3PoaqngWjdd/922hOBs5C/jJA6x7AiUt8VYVUAVQXXShfIqCYRMZO8/N1N+B8H1sOUwivpSUSVCJ2MAjtVwBAIdv+AQkHQqbOgc+fBvorjyQENDcch16/BtkQdAlC4E6jrYHGgGU18Io3gmhzJuwub6/fQJYNi/YBpCifhbDaAPXFvCBVxXbvfbNGFeN8DkjogWAd8DljV3KRutcEAeHMN/HXZ4p9bhncJHCyhNx52R0Kv/XNuQvYBnM+CP7xddXL5KaJw0TMAF8qjnMvegeK/SLHubhpKDKIrJDlvXoMX3y9xcSMZyBQ+tpyk5hzsa2Ns7LGdfWdbL6fZvHn92d7dgROH/730YBLtiZmEdGPkFnhX4kxmjVe2xgPfCtrRd6GHRtEh9zsL8xVe+pwSzj+OtwvletZZ/wLeKD71L+ZeHHWZ/gowABkp7AwwnEjFAAAAAElFTkSuQmCC);
		background-position: right top;
		background-repeat: no-repeat;
		-moz-box-shadow: none;
	  }
	  
	  input:required:valid {
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAepJREFUeNrEk79PFEEUx9/uDDd7v/AAQQnEQokmJCRGwc7/QeM/YGVxsZJQYI/EhCChICYmUJigNBSGzobQaI5SaYRw6imne0d2D/bYmZ3dGd+YQKEHYiyc5GUyb3Y+77vfeWNpreFfhvXfAWAAJtbKi7dff1rWK9vPHx3mThP2Iaipk5EzTg8Qmru38H7izmkFHAF4WH1R52654PR0Oamzj2dKxYt/Bbg1OPZuY3d9aU82VGem/5LtnJscLxWzfzRxaWNqWJP0XUadIbSzu5DuvUJpzq7sfYBKsP1GJeLB+PWpt8cCXm4+2+zLXx4guKiLXWA2Nc5ChOuacMEPv20FkT+dIawyenVi5VcAbcigWzXLeNiDRCdwId0LFm5IUMBIBgrp8wOEsFlfeCGm23/zoBZWn9a4C314A1nCoM1OAVccuGyCkPs/P+pIdVIOkG9pIh6YlyqCrwhRKD3GygK9PUBImIQQxRi4b2O+JcCLg8+e8NZiLVEygwCrWpYF0jQJziYU/ho2TUuCPTn8hHcQNuZy1/94sAMOzQHDeqaij7Cd8Dt8CatGhX3iWxgtFW/m29pnUjR7TSQcRCIAVW1FSr6KAVYdi+5Pj8yunviYHq7f72po3Y9dbi7CxzDO1+duzCXH9cEPAQYAhJELY/AqBtwAAAAASUVORK5CYII=);
		background-position: right top;
		background-repeat: no-repeat;
	  }
	</style>
    
    <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css"/>

    <form method="post" name="formNotificacao" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>" autocomplete="on">
    
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
            	<div class="icon" id="toolbar-send">
                    <a href="javascript: ValidateForm(document.formNotificacao)" class="toolbar">
                    <span class="icon-32-send"></span>Enviar</a>
	            </div>
                
            	<div class="icon" id="toolbar-back">
		            <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
		            <span class="icon-32-back"></span>Voltar</a>
	            </div>
            
    	        </div>
        	    <div class="clr"></div>
            	</div>
           
          <div class="pagetitle icon-48-writemess">
    	    	    <h2>Formul&#225;rio de Solicita&#231;&#227;o de Afastamento</h2>
        	    </div>
            </div>
		</div>
    
        <span class="label label-info">Como Proceder</span>
        <p>Preencha os campos abaixo para enviar uma solicitaÃ§Ã£o de afastamento ao diretor e secretaria do IComp</p>
        <p><span class="aviso">Importante</span> Todos os campos devem ser preenchidos</p>
      <hr />
    
        <table class="table-form">
            <tbody>
                <tr>
                    <td>Usu&#225;rio Atual</td>
                    <td><b><?php echo $user->name;?></b></td>
                </tr>
                <tr>
                    <td>E-mail de Origem</td>
                    <td><b><?php echo $user->email;?></b></td>
                </tr>
                <tr>
                    <td>Local da Viagem</td>
                    <td><input maxlength="40" size="40" name="local" class="inputbox" required="required" autofocus="autofocus"><input type="submit" value="Go"></td>
                </tr>
                <tr>
                    <td>Tipo de Viagem</td>
                    <td><input name="tipo" value="1" type="radio" required>Nacional
                        <input name="tipo" value="2" type="radio" required>Internacional
                    </td>
                </tr>
                <tr>
                    <td>Data da Sa&#237;da</b></td>
                    <td><input id="datasaida" name="datasaida" size="10" type="date" required="required"/></td>
                </tr>
                <tr>
                    <td>Data do Retorno</td>
                    <td><input id="dataretorno" name="dataretorno" size="10" type="date" required="required"/></td>
                </tr>
                <tr>
                    <td>Justificativa</td>
                    <td><textarea class="inputbox" name="justificativa" rows="5" cols="50" class="mceEditor" required="required"></textarea>			
                    </td>
                </tr>
                <tr>
                    <td>Plano de ReposiÃ§Ã£o</td>
                    <td><textarea class="inputbox" name="reposicao" rows="5" cols="50" class="mceEditor"></textarea>		
                    </td>
                </tr>
            </tbody>
        </table>        
        <br />
        
        <input type="submit" value="Submeter" name="submeter" style="display:none">
        <input name='task' type='hidden' value='enviar' />    
    </form>
    
<?php }

// LISTAGEM DE NOTIFICAÃ‡Ã•ES
function listarNotificacoes($idProfessor, $mes) {

	$Itemid = JRequest::getInt('Itemid', 0);
	$database	=& JFactory::getDBO();

	if($mes == "") {
        $mesAtual = (int)date("m");
        $anoAtual = (int)date("Y");
        $sql = "SELECT id,nomeusuario, DATE_FORMAT(dataenvio,'%d/%m/%Y %h:%i') as dataenvio, local, tipo, datasaida, dataretorno, justificativa, reposicao FROM #__notificacoes_saida WHERE idusuario = $idProfessor ORDER BY dataenvio DESC";
    } else {
        $mesAtual = (int)substr($mes,0,2);
        $anoAtual = (int)substr($mes,3,4);
        $sql = "SELECT id,nomeusuario, DATE_FORMAT(dataenvio,'%d/%m/%Y %h:%i') as dataenvio, local, tipo, datasaida, dataretorno, justificativa, reposicao FROM #__notificacoes_saida WHERE MONTH(dataenvio) = $mesAtual AND YEAR(dataenvio) = $anoAtual AND idusuario = $idProfessor ORDER BY dataenvio DESC";
    }

    $database->setQuery( $sql );
    $notificacoes = $database->loadObjectList();

    $sql = "SELECT MONTH(dataenvio) as mes, YEAR(dataenvio) as ano FROM #__notificacoes_saida WHERE idusuario = $idProfessor group by (MONTH(dataenvio)+'/'+ YEAR(dataenvio)) ORDER BY ano DESC, mes DESC";
    $database->setQuery( $sql );
    $meses = $database->loadObjectList();
    ?>
    
	<script type="text/javascript">
		function visualizar(form) {
			var idSelecionado = 0;
		    
			for(i = 0;i < form.idPedidoSelec.length;i++)
				if(form.idPedidoSelec[i].checked) idSelecionado = form.idPedidoSelec[i].value;
        
			if(idSelecionado > 0) {
				window.open("index.php?option=com_portalprofessor&Itemid=316&task=vernotificacao&idPedido="+idSelecionado,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
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
    
    <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css"/>
    
	<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
    
		<div id="toolbar-box"><div class="m">
			<div class="toolbar-list" id="toolbar">
				<div class="cpanel2">
					
								
					<div class="icon" id="toolbar-preview">
						<a href="javascript:visualizar(document.form)"> 
                        <span class="icon-32-preview"></span>Visualizar</a>
					</div>
		
					<div class="icon" id="toolbar-back">
						<a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
                        <span class="icon-32-back"></span>Voltar</a>
					</div>
				</div>
				<div class="clr"></div>
			</div>
            
			<div class="pagetitle icon-48-inbox">
				<h2>SolicitaÃ§Ãµes de Afastamento Enviadas</h2>
			</div>
            </div>
        </div>

		<fieldset>        
        	<legend>Filtro para consulta</legend>
            
			<table>
			<tr>
				<td>Selecione o mÃªs</td>
                <td><select name="fecha" class="inputbox">
							<option value="">Todos</option>
							<?php
							foreach($meses as $mesCombo){
               $valor = $mesCombo->mes ."/".$mesCombo->ano;
               if($mesCombo->mes < 10)
               	$valor = "0". $valor;

               ?>
                    <option value="<?php echo $valor;?>"
                    <?php
                    if($valor == $mes)
                        echo 'SELECTED';
                    ?>>
                        <?php echo $valor;?>
                    </option>
                    <?php } ?>
                </select>
                </td>
                <td>
                <button type="submit" class="btn btn-primary" value="Buscar">
                	<i class="icone-search icone-white"></i> Buscar
                </button>
                </td>
            </tr>
        </table>
	</fieldset>        
    
		<table class="table table-striped" id="tablesorter-imasters">
            <thead>
                <tr>
                    <th></th>
                    <th>#Pedido</th>
                    <th>Data do Envio</th>
                    <th>Professor</th>
                    <th>SaÃ­da</th>
                    <th>Retorno</th>
                    <th>Local</th>
                    <th>Tipo da Viagem</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $table_bgcolor_even="#e6e6e6";
                $table_bgcolor_odd="#FFFFFF";
    
                $tipoViagem = array (1 => "Nacional",2 => "Internacional");
    
                $i = 0;
                foreach( $notificacoes as $notificacao ) {
                    $i = $i + 1;
                    if ($i % 2) {
                        echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
                    } else {
                        echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
                    }
                    $anoEnvio = substr($notificacao->dataenvio,2,2);
                    $mesEnvio = substr($notificacao->dataenvio,5,2);
                    $diaEnvio = substr($notificacao->dataenvio,8,2);
                    $horaEnvio = substr($notificacao->dataenvio,11,2);
                    $minutoEnvio = substr($notificacao->dataenvio,14,2);
                    ?>
    
                    <td width='16'><input type="radio" name="idPedidoSelec" value="<?php echo $notificacao->id;?>"></td>
                    <td><?php echo $notificacao->id;?></td>
                    <td><?php echo $notificacao->dataenvio;?></td>
                    <td><?php echo $notificacao->nomeusuario;?></td>
                    <td><?php echo $notificacao->datasaida;?></td>
                    <td><?php echo $notificacao->dataretorno;?></td>
                    <td><?php echo $notificacao->local;?></td>
                    <td><?php echo $tipoViagem[$notificacao->tipo];?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    
        <input name='task' type='hidden' value='listarnotificacoes' /> 
        <input name='idPedidoSelec' type='hidden' value='0'> <input name='idPedido' type='hidden' value='' />
    
    </form>

<?php }


//////////////////////////////////////////////////////////////

function telafinal() {
	$Itemid = JRequest::getInt('Itemid', 0);
	
	JFactory::getApplication()->enqueueMessage(JText::_('Solicita&#231;&#227;o enviada com sucesso!!!'));
	?>
    
    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
    <div id="toolbar-box">
        <div class="m">
            <div class="toolbar-list" id="toolbar">
                <div class="cpanel2">
                    <div class="icon" id="toolbar-back">
                        <a
                            href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
                            <span class="icon-32-back"></span> <?php echo JText::_( 'Voltar' ); ?>
                        </a>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="pagetitle icon-48-cpanel">
                <h2>Formul&#225;rio de Solicita&#231;&#227;o de Afastamento</h2>
            </div>
        </div>
    </div>
    <p>
        <font size="2" style="line-height: 150%"><br>Os dados de sua
            solicita&#231;&#227;o de afastamento foram enviados ao Diretor do
            IComp com sucesso. <br />Obrigado por usar os servi&#231;os do DCC.</font>
    </p>
    <br>
    <br>
    <p align="right">
        <br />Ass: Dire&#231;&#227;o do IComp.
    </p>

<?php
}

function salvarNotificacao($user, $local, $tipo, $datasaida, $dataretorno, $justificativa, $reposicao) {
	$database	=& JFactory::getDBO();

	$sql = "INSERT INTO #__notificacoes_saida (idusuario, nomeusuario, emailusuario, local, tipo, datasaida, dataretorno, justificativa, reposicao, dataenvio) VALUES ('$user->id','$user->name', '$user->email', '$local', '$tipo', '$datasaida', '$dataretorno', '$justificativa', '$reposicao','".date("Y-m-d H:i:s")."')";
	$database->setQuery( $sql );
	$database->Query();
}

function gerarHTML($nome, $email, $local, $tipo, $datasaida, $dataretorno, $justificativa, $reposicao) {
	$database	=& JFactory::getDBO();

	$tipoViagem = array (1 => "Nacional",2 => "Internacional");

	$hoje = date("d_m_y_H_i");
	$arquivoHTML = "components/com_portalprofessor/forms/notificacao_".$user->id."_".$hoje.".pdf";
	$arq = fopen($arquivoHTML, 'w') or die("CREATE ERROR");

	// leitura das datas automaticamente
	$dia = date('d');
	$mes = date('m');
	$ano = date('Y');

	// configuraÃ¯Â¿Â½Ã¯Â¿Â½o mes

	switch ($mes){
		case 1: $mes = "Janeiro"; break;
		case 2: $mes = "Fevereiro"; break;
		case 3: $mes = "Mar&#231;o"; break;
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
    //                       <!--<img src='data:image/jpeg;base64,".$imagemUFAM."' border='0' id='idImagem' alt='ufam' title='ufam' >-->
    //                    <!-- <img src='data:image/jpeg;base64,".$imagemDCC."' border='0' id='idImagem' alt='dcc' title='dcc' >-->

    $pdf = new Cezpdf();
    $pdf->selectFont('pdf-php/fonts/Helvetica.afm');
    $optionsText = array('justification'=>'center', 'spacing'=>2.0);
    $header = array('justification'=>'center', 'spacing'=>1.3);
    $dados = array('justification'=>'justify', 'spacing'=>2.0);
    $optionsTable = array('fontSize'=>10, 'titleFontSize'=>12, 'xPos'=>'center', 'width'=>500, 'cols'=>array('CÃ³digo'=>array('width'=>60, 'justification'=>'center'),'PerÃ­odo'=>array('width'=>50, 'justification'=>'center'),'Disciplina'=>array('width'=>285), 'Conceito'=>array('width'=>50, 'justification'=>'center'), 'FR%'=>array('width'=>45, 'justification'=>'center'), 'CR'=>array('width'=>30, 'justification'=>'center'), 'CH'=>array('width'=>30, 'justification'=>'center')));

    $pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 490, 730, 75);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 730, 100);
    $pdf->ezText('<b>PODER EXECUTIVO</b>',12,$header);
    $pdf->ezText('<b>MINISTÉRIO DA EDUCAÇÃO</b>',10,$header);
    $pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$header);
    $pdf->ezText('',11,$header);
    $pdf->ezText('<b>INSTITUTO DE COMPUTAÇÃO</b>',10,$header);
    $pdf->ezText('',11,$header);
    $pdf->setLineStyle(2);
    $pdf->line(20, 720, 580, 720);
    //    $pdf->addText(495,695,8,"<b>Data:</b> ".date("d/m/Y"),0,0);
    //    $pdf->addText(495,705,8,"<b>Hora:</b> ".date("H:i"),0,0);

    //    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('<b>SOLICITAÇÃO DE AFASTAMENTO TEMPORÁRIO</b>',12,$optionsText);
    $pdf->ezText('');
    $pdf->ezText("O(A) professor(a) <b>".utf8_decode($nome)."</b> estará afastado de suas atividades presenciais na UFAM no perí­odo de <b>$datasaida</b> a <b>$dataretorno</b>.",10,$dados);
    $pdf->ezText('');
    $pdf->ezText("O motivo deste afastamento é: <b>".utf8_decode($justificativa)."</b>.",10,$dados);
    $pdf->ezText('');
    $pdf->ezText('Para reposição de suas aulas, o professou fez o seguinte planejamento:',10,$dados);
    $pdf->ezText("<b>".utf8_decode($reposicao)."</b>.",10,$dados);
    $pdf->ezText('');
    $pdf->ezText('Os dados deste funcionário estão descritos a seguir:',10,$dados);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Funcionário: </b>".utf8_decode($nome),10,$dados);
    //    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>E-mail:</b> ".utf8_decode($email),10,$dados);
    //    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Local:</b> ".utf8_decode($local),10,$dados);
    //    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Tipo da Viagem:</b> ".$tipoViagem[$tipo],10,$dados);
    //    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Data de Saí­da:</b> $datasaida",10,$dados);
    //    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Data de Retorno:</b>  $dataretorno",10,$dados);

    $pdf->setLineStyle(1);
    $pdf->line(310, 110, 550, 110);
    $pdf->addText(360,100,8,'<b>Prof. Dr. Ruiter Braga Caldas</b>',0,0);
    $pdf->addText(330,90,8,'Diretor do Instituto de Computação - IComp',0,0);

    $pdf->line(20, 60, 580, 60);
    $pdf->addText(80,40,8,'Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur Virgílio Filho - CEP 69077-000 - Manaus, AM, Brasil',0,0);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 140, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 229, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 383, 30, 8, 8);
    $pdf->addText(150,30,8,'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br',0,0);


    $pdfcode = $pdf->output();
    fwrite($arq,$pdfcode);
    fclose($arq);

    return $arquivoHTML;

}

function enviarEmail($user, $local, $tipo, $datasaida, $dataretorno, $justificativa, $arquivoHTML){
	$tipoViagem = array (1 => "Nacional",2 => "Internacional");

	// subject
	$subject  = "[IComp/UFAM] Solicitacao de Afastamento do DCC";

	// message
	$message .= "O(A) professor(a) ".$user->name." enviou uma solicitacao de afastamento do IComp.\r\n\n";
	$message .= "Nome: ".$user->name."\r\n";
	$message .= "E-mail: ".$user->email."\r\n";
	$message .= "Local: ".utf8_decode($local)."\r\n";
	$message .= "Tipo de Viagem: ".$tipoViagem[$tipo]."\r\n";
	$message .= "Data de SaÃ¯Â¿Â½da: ".$datasaida."\r\n";
	$message .= "Data de Retorno: ".$dataretorno."\r\n";
	$message .= "Justificativa: ".utf8_decode($justificativa)."\r\n";
	$message .= "Data e Hora do envio: ".date("d/m/Y H:i:s")."\r\n";

	$chefe = "ruiter@icomp.ufam.edu.br";
	$secretaria = "secretaria@icomp.ufam.edu.br";

	$email[] = $chefe;
	$email[] = $secretaria;

	JUtility::sendMail($user->email, "Site do IComp: ".$user->name, $email, $subject, $message, false, $user->email, NULL, $arquivoHTML);
}


// LISTAGEM DE PROFESSORES
function listarProfessores($nome = "") {
    $database =& JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);

    $sql = "SELECT P.id, nomeProfessor, P.idLinhaPesquisa, email, sigla, telresidencial, telcelular  FROM #__professores AS P JOIN #__linhaspesquisa AS LP ON P.idLinhaPesquisa = LP.id WHERE nomeProfessor LIKE '%$nome%' ORDER BY nomeProfessor ";

    $database->setQuery( $sql );
    $professores = $database->loadObjectList();
	
    $statusImg = array (0 => "reprovar.gif",1 => "sim.gif"); ?>
    
	<script language="JavaScript">
        function validarForm(form, idProf) {
           form.idProf.value = idProf;
           form.submit();
           return true;
        }
        
        function confirmarExclusao() {
            var resposta = window.confirm("Confirmar exclusao do Professor?");
            if(resposta){
              return true;
            }
            return false;
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

	<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">

		<div id="toolbar-box"><div class="m">
			<div class="toolbar-list" id="toolbar">
				<div class="cpanel2">
					<div class="icon" id="toolbar-back">
						<a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
							<span class="icon-32-back"></span>Voltar
						</a>
					</div>
				</div>
				<div class="clr"></div>
                </div>
                
                <div class="pagetitle icon-48-contact"><h2>Professores do IComp/PPGI</h2>
			</div>
            </div>
        </div>

		<legend>Filtro para consulta</legend>
        <p>
		<span>Nome </span>
		<input id="buscaNome" name="buscaNome" size="30" type="text" value="<?php echo $nome;?>" />
		<button type="submit" value="Buscar" class="btn btn-primary">
            <i class="icone-search icone-white"></i> Buscar
        </button>		
        </p>

		<table class="table table-striped" id="tablesorter-imasters">
		<thead>
			<tr>
				<th>Nome</th>
				<th>Fones</th>
				<th>E-mail</th>
				<th>Linha</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$table_bgcolor_even="#e6e6e6";
			$table_bgcolor_odd="#FFFFFF";
			$linhaPesquisa = array (1 => "bd_ri",2 => "se_es",3 => "ia",4 => "visao",5 => "redes",6 => "otimizacao");

			$i = 0;
			foreach( $professores as $professor ) {
				$i = $i + 1;
				if ($i % 2) {
					echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
				} else {
					echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
				}
				?>

                <td><?php echo $professor->nomeProfessor;?></td>
                <td align="center"><?php if($professor->telresidencial) echo "<img src='components/com_portalsecretaria/images/telefone.png' title='".$professor->telresidencial."'>";?>
                    <?php if($professor->telcelular) echo "<img src='components/com_portalsecretaria/images/celular.png' title='".$professor->telcelular."'>";?>
                </td>
                <td><img border='0'
                    src='components/com_portalsecretaria/images/emailButton.png'
                    width='16' height='16' title='<?php echo $professor->email;?>'></td>
                <td><img border='0'
                    src='components/com_portalsecretaria/images/<?php echo $linhaPesquisa[$professor->idLinhaPesquisa];?>.gif'
                    title='<?php echo verLinhaPesquisa($aluno->area, 1);?>'>
                </td>
			</tr>

			<?php } ?>

		</tbody>
	</table>
    
	<br>Foi(foram) retornado(s) <b><?php echo sizeof($professores);?> </b>
	professor(es). <input name='task' type='hidden' value='professores' /> 
    <input name='idProf' type='hidden' value='' />
</form>

<?php } 

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
    $optionsTable = array('fontSize'=>8, 'titleFontSize'=>8, 'xPos'=>'center', 'width'=>800, 'cols'=>array('CÃ³digo'=>array('width'=>50, 'justification'=>'center'),'Disciplina'=>array('width'=>235), 'Turma'=>array('width'=>35, 'justification'=>'center'), 'Professor'=>array('width'=>140), 'CR'=>array('width'=>25, 'justification'=>'center'), 'CH'=>array('width'=>25, 'justification'=>'center'), 'HorÃ¡rio'=>array('width'=>145, 'justification'=>'center'), 'Sala'=>array('width'=>145, 'justification'=>'center')));

    $pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 490, 720, 75);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 720, 100);
    $pdf->ezText('<b>PODER EXECUTIVO</b>',12,$optionsText);
    $pdf->ezText('<b>MINISTÃ‰RIO DA EDUCAÃ‡ÃƒO</b>',10,$optionsText);
    $pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$optionsText);
    $pdf->ezText('<b>INSTITUTO DE COMPUTAÃ‡ÃƒO</b>',10,$optionsText);
    $pdf->ezText('',11,$optionsText);
    $pdf->ezText('<b>PROGRAMA DE PÃ“S-GRADUAÃ‡ÃƒO EM INFORMÃ�TICA</b>',10,$optionsText);
    $pdf->addText(495,715,8,"<b>Data:</b> ".date("d/m/Y"),0,0);
    $pdf->addText(495,725,8,"<b>Hora:</b> ".date("H:i"),0,0);
    $pdf->setLineStyle(2);
    $pdf->line(20, 450, 820, 450);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>OFERTA DE DISCIPLINAS DO PERÃ�ODO ".$periodo->periodo."</b>",14,$optionsText);
    $pdf->ezText('');

    foreach( $disciplinas as $disciplina)
    {
        $disciplinasMatriculas[] = array('CÃ³digo'=>$disciplina->codigo, 'Disciplina'=>utf8_decode($disciplina->nomeDisciplina), 'Turma'=>$disciplina->turma,'CR'=>$disciplina->creditos, 'CH'=>$disciplina->carga, 'Professor'=>utf8_decode($disciplina->professor), 'HorÃ¡rio'=>utf8_decode($disciplina->horario), 'Sala'=>utf8_decode($disciplina->sala));
    }

    $pdf->ezTable($disciplinasMatriculas,$cols,'',$optionsTable);

    $pdf->line(20, 55, 820, 55);
    $pdf->addText(190,40,8,'Av. Rodrigo OtÃ¡vio, 6.200 â€¢ Campus UniversitÃ¡rio Senador Arthur VirgÃ­lio Filho â€¢ CEP 69077-000 â€¢  Manaus, AM, Brasil',0,0);
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
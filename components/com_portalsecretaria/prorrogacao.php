<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
<link rel="stylesheet" href="components/com_inscricaoppgi/estilo.css" type="text/css" />

<script type="text/javascript">
	function julgar(form, fator) {
		var idSelecionado = 0;
		
		for(i = 0; i < form.idSolicSelec.length;i++)
			if(form.idSolicSelec[i].checked) idSelecionado = form.idSolicSelec[i].value;
		
		//verifica se algum item foi selecionado e o status da solicitacao		 
		if(idSelecionado > 0){ 
			if (idSelecionado.status != 0) {
				if (fator==0){
				  form.task.value = 'aprovarProrrogacao';
				  form.idAluno.value = idSelecionado;
				  form.submit();
				} else {
				  form.task.value = 'reprovarProrrogacao';
				  form.idAluno.value = idSelecionado;
				  form.submit();
				}
			} else {
				alert('A solicitação ainda está em aprovação pelo Orientador');
			}
		} else {
			alert('Ao menos 1 item deve ser Selecionado!');
		}
	}
	
	/*Função JavaScript que chama a função PHP responsável por visualizar os detalhes de uma prorrogação*/
	function visualizar(form) {
		var idSelecionado = 0;
		
		for(i = 0; i < form.idSolicSelec.length;i++)
			if(form.idSolicSelec[i].checked) idSelecionado = form.idSolicSelec[i].value;
		
 		if(idSelecionado > 0){
		  
		  form.task.value = 'mostrarDetalhesProrrogacao';
		  form.idSolic.value = idSelecionado;
		  form.submit();
		} else {
			alert('Ao menos 1 item deve ser Selecionado!');
		}
	}
	
	/*Função JavaScript que chama a função PHP responsável pela geração da Solicitação Formal*/
	function imprimir(idAluno) {
		/*window.open("index.php?option=com_portalsecretaria&Itemid=190&task=imprimirProrrogacao&idAluno="+idAluno,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");*/
		form.task.value = 'imprimirProrrogacao';
		form.submit();
	}
	
	function imprimirPrevia(idAluno) {		
		window.open("components/com_portalaluno/previas/PPGI-Previa-"+idAluno+".pdf","_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
	}	
	
	/*Função JavaScript que chama a função PHP responsável pelo download*/
	function download(form) {
		form.task.value = 'downloadDissertacao';
		form.submit();
	}
</script>
	
<?php // LISTAGEM DE PRORROGA��ES : TELA
function mostrarTelaProrrogacao($mes, $status) {
	if($status == NULL) $status = 1;
	$Itemid = JRequest::getInt('Itemid', 0);
	
	$database =& JFactory::getDBO();
	
	// LISTAGEM DOS STATUS PARA CONSULTA
	if($status == NULL) 
		$status = 0;
		
	$sqlEstendido = "";
	
	if ($mes) {
		$data = explode("/", $mes);
		$mesBusca = $data[0];
		$anoBusca = $data[1];
		$sqlEstendido = "AND MONTH(dataSolicitacao) = '$mesBusca' AND YEAR(dataSolicitacao) = '$anoBusca'";
	}
	
//	if ($status < 5) {
		$sql = "SELECT * FROM #__prorrogacoes WHERE status = $status ".$sqlEstendido." ORDER BY dataSolicitacao DESC";
//		} else {
	//	$sql = "SELECT * FROM #__prorrogacoes WHERE status = $status AND MONTH(dataSolicitacao) = $mesAtual AND YEAR(dataSolicitacao) = $anoAtual ".$sqlEstendido." ORDER BY dataSolicitacao DESC";
	//}	
		
	$database->setQuery($sql);
	$prorrogacoes = $database->loadObjectList();
?>

<script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
<script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
<script type="text/javascript">
	$(function() {
		$("#tablesorter-imasters").tablesorter();
	});
</script>

<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css">

<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">

	<!-- CABEÇALHO DA PÁGINA -->
	<div id="toolbar-box">
		<div class="m">
			<div class="toolbar-list" id="toolbar">
				<div class="cpanel2">
					<div class="icon" id="toolbar-preview">
						<a href="javascript:visualizar(document.form)">
						<span class="icon-32-preview"></span><?php echo JText::_( 'Visualizar' ); ?></a>
					</div>
					<div class="icon" id="toolbar-back">
						<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
						<span class="icon-32-back"></span> <?php echo JText::_( 'Voltar' ); ?></a>
					</div>
				</div>
				<div class="clr"></div>
			</div>

			<div class="pagetitle icon-48-inbox">
				<h2>Solicitações de Prorrogação de Prazo</h2>
			</div>
		</div>
	</div>

	<!-- FILTRO DA BUSCA -->
	<fieldset>
		<legend>Filtros para consulta</legend>
		
		<table>
		<tr>
			<td>Mês</td>
			<td>
			<select name="fecha">
				<option value="">Todos</option>
				<?php						
					$sql = "SELECT DISTINCT DATE_FORMAT(dataSolicitacao, '%m/%Y') as dataSolicitacao from #__prorrogacoes ORDER BY dataSolicitacao";
					$database->setQuery($sql);
					$meses = $database->loadObjectList();
					
					foreach($meses as $mesCombo) { ?>   
						<option value="<?php echo $mesCombo->dataSolicitacao; ?>" 
							<?php
							
							   if($mes == $mesCombo->dataSolicitacao)
								  echo 'SELECTED';
							?>
							> <?php 
								   echo $mesCombo->dataSolicitacao;
							?>
				</option>
				<?php } ?>
			</select>
			</td>
			<td>Status</td>
			<td>
				<select name="buscaStatus">
					<option value="0" <?php if($status == 0) echo 'SELECTED';?>>Em aprovação pelo Orientador</option>
					<option value="1" <?php if($status == 1) echo 'SELECTED';?>>Em aprovação pelo PPGI</option>
					<option value="2" <?php if($status == 2) echo 'SELECTED';?>>Indeferido pelo Professor</option>
					<option value="3" <?php if($status == 3) echo 'SELECTED';?>>Indeferido pelo PPGI</option>
					<option value="4" <?php if($status == 4) echo 'SELECTED';?>>Deferido</option>
				</select>
			</td>
			<td>            
				<button type="submit" name="buscar" class="btn btn-primary" title="Filtra busca">
					<i class="icone-search icone-white"></i> Consultar
				</button>
			</td>
		</tr>
		</table>
	</fieldset>
	
	<?php if (sizeof($prorrogacoes) == 0) { ?>
		<div id="noItens" style="background-color:#d1f0b6; padding:15px; font-size:13px; color:#29480e; border:1px solid #29480e; border-radius:5px;">Não há solicitações para este filtro!</div>
	<?php } else { ?>

	<!--Tabela que lista os pedidos-->
	<table class="table table-striped" id="tablesorter-imasters">
		<thead>
			<tr>
				<th></th>
				<th>Status</th>
				<th>Solicitado em</th>
				<th>Nome do Aluno</th>
				<th>Orientador</th>
				<th>Curso</th>
				<th>Ingresso</th>                  
			</tr>
		</thead>
		<tbody>
			<?php				
			$curso = array (1 => "mestrado",2 => "doutorado",3 => "especial");
			$status_pedido = array (0 => "Em aprovação pelo Professor",
									1 => "Em aprovação pelo PPGI",
									2 => "Indeferido pelo Professor",
									3 => "Indeferido pelo PPGI",
									4 => "Deferido"); 
			/*Pega cada item da lista e coloca em uma linha da tabela*/
			foreach ($prorrogacoes as $prorrogacao) {
				$sqlAluno = "SELECT aluno.nome, aluno.curso, aluno.anoingresso, orientador.nomeProfessor
							FROM j17_aluno aluno,j17_professores orientador
							WHERE aluno.id =".$prorrogacao->idAluno." AND orientador.id =".$prorrogacao->idOrientador;
							
				$database->setQuery($sqlAluno);
				$resultado = $database->loadObjectList();

				//atribui o primeiro objeto do vetor a uma variavel
				foreach ($resultado as $t){
					$aluno = $t;
				}
				?>
			<tr>
				<td align="center">
						<input type="radio" name="idSolicSelec" value="<?php echo $prorrogacao->id;?>">
				</td>
				<td align="center"><img src="components/com_portalaluno/images/icon_status<?php echo $prorrogacao->status; ?>.png" title="<?php echo $status_pedido[$prorrogacao->status]; ?>" /></td>
				<td align="center">
					<?php
						$data = $prorrogacao->dataSolicitacao; // Formato Mysql(YYYY/MM/DD)
						$data = explode("-", $data);
						echo $data[2]."/".$data[1]."/".$data[0];
					?>
				</td>
				<td align="center"><?php echo $aluno->nome;?></td>
				<td align="center"><?php echo $aluno->nomeProfessor;?></td>
				<td align="center"><img border='0' src='components/com_portalsecretaria/images/<?php echo $curso[$aluno->curso];?>.gif' title='<?php echo $curso[$aluno->curso];?>'></td>
				<td align="center">
					<?php 
						$data = $aluno->anoingresso;
						$data = explode("-", $data);
						echo $data[1]."/".$data[0];
					?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<br />
	
	<span class="label label-inverse">Total de Solicitações: <?php echo sizeof($prorrogacoes);?></span>
	
	<?php } ?>
	
	<input name='idAluno' type='hidden' value='<?php echo $aluno->id?>' />    
	<input name='idSolic' type='hidden' value='' />    
	<input name='idSolicSelec' type='hidden' value='0' />        
	<input name='task' type='hidden' value='prorrogacao' />

</form>

<?php } ?>

<?php // DETALHES DA PRORROGA��O
function mostrarDetalhesProrrogacao($aluno, $prorrogacao, $mes, $buscaStatus) {
	$Itemid = JRequest::getInt('Itemid', 0);
	
	$curso = array (1 => "Mestrado",2 => "Doutorado",3 => "Especial");
	$database =& JFactory::getDBO();
	
	$idAluno = $aluno->id;
	
	$status_pedido = array (0 => "Em aprovação pelo Professor",
									1 => "Em aprovação pelo PPGI",
									2 => "Indeferido pelo Professor",
									3 => "Indeferido pelo PPGI",
									4 => "Deferido"); ?>	
									
	<script type="text/javascript">
		function julgar(form, fator) {
			if (fator==0){
			  form.task.value = 'aprovarProrrogacao';
			  form.submit();
			} else {
			  form.task.value = 'reprovarProrrogacao';
			  form.submit();
			}
		}
	</script>
		
	<script type="text/javascript">
		function voltar(form) {
	
		  form.submit();
		}        
	</script>

<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">

	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
		<div class="cpanel2">
			<div class="icon" id="toolbar-print">
				<a href="javascript:imprimir(<?php echo $aluno->id;?>)">
					<span class="icon-32-copy"></span><?php echo JText::_( 'Imprimir' ); ?>
				</a>
			</div>
			
			<?php if ($prorrogacao->status == 1){ ?>
			<div class="icon" id="toolbar-aprovar">
				<a href="javascript:julgar(document.form,0)">
					<span class="icon-32-checkin"></span> <?php echo JText::_( 'Aprovar' ); ?>
				</a>
			</div>

			<div class="icon" id="toolbar-reprovar">
				<a href="javascript:julgar(document.form, 1)">
					<span class="icon-32-cancel"></span> <?php echo JText::_( 'Reprovar' ); ?>
				</a>
			</div>
			<?php } ?>
			
			<div class="icon" id="toolbar-back">
				<a href="javascript:voltar(document.form)">
					<span class="icon-32-back"></span>Voltar
				</a>
			</div>
			
		</div>
	  
		<div class="clr"></div>
		</div>
		  <div class="pagetitle icon-48-contact"><h2>Dados da Solicitação</h2></div>
		</div>
	</div>
	
	<table width="100%" cellpadding="6">
		<tr style="background-color:#7196d8;">
			<td colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
		</tr>        
		<tr>
			<td bgcolor="#CCCCCC"><b>Aluno </b></td>
			<td><?php echo $aluno->nome;?></td>
		</tr>
		<tr>
			<td bgcolor="#CCCCCC"><b>Curso </b></td>
			<td><?php echo $curso[$aluno->curso];?></td>
		</tr>
		<tr>
			<td bgcolor="#CCCCCC"><b>Ano de Ingresso </b></td>
			<td><?php
					 $data = explode("-", $aluno->anoingresso);
					 echo $data[2]."/".$data[1]."/".$data[0];
			?></td>
		</tr>
		<tr>
			<td bgcolor="#CCCCCC"><b>Previsão de Conclusão </b></td>
			<td><?php
					 $data = explode("-", $aluno->anoconclusao);
					 echo $data[2]."/".$data[1]."/".$data[0];
			?></td>
		</tr>
		<tr>
			<td bgcolor="#CCCCCC"><b>Justificativa: </b></td>
			<td><textarea name="justificativa" cols="70" rows="5" readonly="readonly"><?php echo $prorrogacao->justificativa;?></textarea></td>
		</tr>
		<tr>
			<td bgcolor="#CCCCCC"><b>Prévia da Dissertação:</b></td>
			<td><a href="javascript:imprimirPrevia(<?php echo $aluno->id;?>)"><img border='0' src='components/com_portalaluno/images/icon_pdf.gif' title='Prévia da Dissertação'> Download do Arquivo</a>
			</td>
		</tr>       
		<tr>
			<td bgcolor="#CCCCCC"><b>Status do Pedido:</b></td>
			<td><img src="components/com_portalaluno/images/icon_status<?php echo $prorrogacao->status; ?>.png" title="<?php echo $status_pedido[$prorrogacao->status]; ?>" /> <?php echo $status_pedido[$prorrogacao->status];?></td>
		</tr>

	</table>
	
	<input name='idProrrogacao' type='hidden' value='<?php echo $prorrogacao->id;?>'>
	<input name='idAluno' type='hidden' value='<?php echo $idAluno;?>'>
	<input name='fecha' type='hidden' value='<?php echo $mes;?>'>
	<input name='buscaStatus' type='hidden' value='<?php echo $buscaStatus;?>'>
	<input name='task' type='hidden' value='prorrogacao'>		
</form>
<?php }  ?>

<?php	
	function identificarProrrogacaoID($idProrrogacao) {
		$database	=& JFactory::getDBO();
		$sql = "SELECT * FROM #__prorrogacoes WHERE id = ".$idProrrogacao;
		$database->setQuery( $sql );
		$prorrogacoes = $database->loadObjectList();
		return ($prorrogacoes[0]);
	
	}
	

	function aprovarProrrogacao($mes,$status,$aluno) {
		$Itemid = JRequest::getInt('Itemid', 0);
				
		$database =& JFactory::getDBO();		
		$sqlProrrogacao = "UPDATE #__prorrogacoes SET status = 4, dataAprovColegiado = '".date("Y-m-d")."' WHERE idAluno = ".$aluno->id."";
		$database->setQuery($sqlProrrogacao);
		$funcionou = $database->Query();
		
 		$data = $aluno->anoconclusao;

		$novoPrazo =  date('Y-m-d', strtotime("+120 days",strtotime("$data")));			
		$database =& JFactory::getDBO();		
		$sqlData = "UPDATE #__aluno SET anoconclusao = '".$novoPrazo."' WHERE id = '".$aluno->id."'";
		$database->setQuery($sqlData);
		$funcionou2 = $database->Query();
				
		if ($funcionou2) {
			JFactory::getApplication()->enqueueMessage('Solicitação Deferida pelo PPGI!');
			mostrarTelaProrrogacao($mes,$status);	
		} else 
			echo JError::raiseWarning( 100, 'ERRO: Solicitação não enviada.' );
	} 


	function reprovarProrrocacao($mes,$status,$aluno) {
		$Itemid = JRequest::getInt('Itemid', 0);
		
		$database =& JFactory::getDBO();	
		$sqlProrrogacao = "UPDATE #__prorrogacoes SET status = 3, dataAprovColegiado = '".date("Y-m-d")."' WHERE idAluno = ".$aluno->id."";
		$database->setQuery($sqlProrrogacao);
		$funcionou = $database->Query();
		
		if ($funcionou) {
			JFactory::getApplication()->enqueueMessage('Solicitação Indeferida pelo PPGI!');
			mostrarTelaProrrogacao($mes,$status);	
		} else 
			echo JError::raiseWarning( 100, 'ERRO: Solicitação não enviada.' );
	} 
	
	
	function downloadDissertacao($path) {					
		// Verifica se o arquivo não existe
		if (isset($arquivo) && !file_exists($arquivo)) {
			echo JError::raiseWarning( 100, 'ERRO: Arquivo não encontrado.' );
			exit;
		}
		
		// Configuramos os headers que serão enviados para o browser
		header('Content-Disposition: attachment; filename="'.$path.'"');
		header('Content-Type: application/pdf');
		header('Content-Length: '. filesize($path));
		readfile($path);
	}
	
	
	function imprimirProrrogacao($aluno,$idProrrogacao) {
		global $mosConfig_lang;
		
		$Itemid = JRequest::getInt('Itemid', 0);
		
		$database =& JFactory::getDBO();
		$sql = "SELECT * FROM #__prorrogacoes WHERE id=".$idProrrogacao." ";
		$database->setQuery($sql);
		$prorrogacoes = $database->loadObjectList();
				
		foreach($prorrogacoes as $p){
			$prorrogacao=$p;
		}
	
		$curso = array (1 => "MESTRADO EM INFORM�TICA",2 => "DOUTORADO EM INFORM�TICA",3 => "MESTRADO EM INFORM�TICA");
	
		$mes = date("m");
		
		$dataIngresso = $aluno->anoingresso;
		$dataIngresso = explode("-", $dataIngresso);
		$novaDataIngresso = $dataIngresso[1]."/".$dataIngresso[0];
		
		$dataConclusao = $aluno->anoconclusao;
		$dataConclusao = explode("-", $dataConclusao);
		$novaDataConclusao = $dataConclusao[1]."/".$dataConclusao[0];
		
		$dataConclusao = $aluno->anoconclusao;
		$novoPrazo =  date('Y-m-d', strtotime("+120 days",strtotime("$dataConclusao")));
		$novoPrazo = explode("-",$novoPrazo);
		$prazoFormatado = $novoPrazo[1]."/".$novoPrazo[0];
		
		
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
		
		$chave = md5($aluno->id.$aluno->nome.date("l jS \of F Y h:i:s A"));
	
		$declaracaoMatricula = "components/com_portalaluno/forms/$chave.pdf";
		$arq = fopen($declaracaoMatricula, 'w') or die("CREATE ERROR");
	
		$pdf = new Cezpdf();
		$pdf->selectFont('pdf-php/fonts/Helvetica.afm');
		$optionsText = array('justification'=>'center', 'spacing'=>2.0);
		$header = array('justification'=>'center', 'spacing'=>1.3);
		$dados = array('justification'=>'justify', 'spacing'=>1.0);
	
		$pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 490, 720, 75);
		$pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 720, 100);
		$pdf->ezText('<b>PODER EXECUTIVO</b>',12,$header);
		$pdf->ezText('<b>MINIST�RIO DA EDUCACAO</b>',10,$header);
		$pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$header);
		$pdf->ezText('<b>INSTITUTO DE COMPUTA��O</b>',10,$header);
		$pdf->ezText('',11,$optionsText);
		$pdf->ezText('<b>PROGRAMA DE POS-GRADUA��O EM INFORM�TICA</b>',10,$header);
		$pdf->addText(495,665,8,"<b>Data:</b> ".date("d/m/Y"),0,0);
		$pdf->addText(495,675,8,"<b>Hora:</b> ".date("H:i"),0,0);
		$pdf->setLineStyle(1);
		$pdf->line(20, 690, 580, 690);
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText('<b>SOLICITACAO DE PRORROGA��O DE PRAZO</b>',12,$optionsText);
	
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText('');  //Para quebra de linha	
	
		$tag = "<b>EU</b>, <b>".utf8_decode(strtoupper($aluno->nome))."</b> aluno(a)";
	
		if($aluno->curso == 3)
		   $tag = $tag. " especial";
		else
		   $tag = $tag. " regularmente matriculado(a)";
	
		$tag = $tag. ", sob a matr�cula n. $aluno->matricula, no Curso de <b>".$curso[$aluno->curso]."</b> na �rea de <b>CI�NCIA DA COMPUTA��O</b> do Programa de Pos-Graduacao em Informatica da Universidade Federal do Amazonas, ingresso(a) em <b>$novaDataIngresso</b> e com previsão de defesa para <b>$novaDataConclusao</b>. Venho atrav�s desta, solicitar a prorroga��o da Data de Defesa, sob a seguinte justificativa: <b>$prorrogacao->justificativa</b>, transferindo a defesa para <b>$prazoFormatado</b>";
	
		$pdf->ezText($tag,11,$optionsText);
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText("PROGRAMA DE P�S-GRADUA��O EM INFORM�TICA DA UNIVERSIDADE FEDERAL DO AMAZONAS, em Manaus, ".date("d")." de ".$mes." de ".date("Y"),8,$dados);
		$pdf->line(20, 55, 580, 55);
		$pdf->addText(80,40,8,'Av. Rodrigo Ot�vio, 6.200 - Campus Universit�rio Senador Arthur Virg�lio Filho - CEP 69077-000 -  Manaus, AM, Brasil',0,0);
		$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 140, 30, 8, 8);
		$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 229, 30, 8, 8);
		$pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 383, 30, 8, 8);
		$pdf->addText(150,30,8,'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br',0,0);
	
		$pdfcode = $pdf->output();
		fwrite($arq,$pdfcode);
		fclose($arq);
	
		header("Location: ".$declaracaoMatricula);

	}
	
?>

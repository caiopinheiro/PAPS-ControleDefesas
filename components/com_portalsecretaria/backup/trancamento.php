<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
<link rel="stylesheet" href="components/com_inscricaoppgi/estilo.css" type="text/css" />

<script type="text/javascript">
	function julgar(form, fator) {
		var idSelecionado = 0;
		
		for(i = 0; i < form.idSolicSelec.length;i++)
			if(form.idSolicSelec[i].checked) idSelecionado = form.idSolicSelec[i].value;
		
		if(idSelecionado > 0){ 
			if (idSelecionado.status != 0) {
				if (fator==0){
				  form.task.value = 'aprovarTrancamento';
				  form.idAluno.value = idSelecionado;
				  form.submit();
				} else {
				  form.task.value = 'reprovarTrancamento';
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
		
	function visualizarTrancamento(form) {
		var idSelecionado = 0;
		
		for(i = 0; i < form.idSolicSelec.length;i++)
			if(form.idSolicSelec[i].checked) idSelecionado = form.idSolicSelec[i].value;
		
		if(idSelecionado > 0){	  
			form.task.value = 'mostrarDetalhesTrancamento';
			form.idAluno.value = idSelecionado;
			form.submit();
		} else {
			alert('Ao menos 1 item deve ser Selecionado!');
		}
	}
	
	function visualizarDocumento(idAluno) {		
		window.open("components/com_portalaluno/atestados/PPGI-Trancamento-"+idAluno+".pdf","_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
	}
	
	function imprimirTrancamento(idAluno) {		
		form.task.value = 'imprimirTrancamento';
		form.submit();
	}
	
	function desfazerTrancamento(idAluno) {
		form.task.value = 'desfazerTrancamento';
		form.submit();
	}	
</script>

	
<?php
	function telaTrancamento($mes, $status) {
        if($status == NULL) $status = 1;
		$Itemid = JRequest::getInt('Itemid', 0);
		
		$database =& JFactory::getDBO();
		
		// LISTAGEM DOS STATUS PARA CONSULTA
		if($status == NULL) 
			$status = 0;
			
		$sqlEstendido = "";
		
		if ($mes) {
			$data = explode("-", $mes);
			$mes = $data[1];
			$sqlEstendido = "AND MONTH(dataSolicitacao) = '$mes'";			
		}
		
		if ($status < 5) {
			$sql = "SELECT * FROM #__trancamentos WHERE status = $status ".$sqlEstendido." ORDER BY dataSolicitacao DESC";
		} else {
			$sql = "SELECT * FROM #__trancamentos WHERE status < 6 ".$sqlEstendido." ORDER BY dataSolicitacao DESC";
		}	
		$database->setQuery($sql);
	    $trancamentos = $database->loadObjectList();
	?>
    
	<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" method="post">

		<!-- CABEÇALHO DA PÁGINA -->
		<div id="toolbar-box">
			<div class="m">
				<div class="toolbar-list" id="toolbar">
					<div class="cpanel2">
	                    <div class="icon" id="toolbar-preview">
                            <a href="javascript:visualizarTrancamento(document.form)">
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
					<h2>Solicitações de T		rancamento de Curso</h2>
				</div>
			</div>
		</div>
    
    	<!-- BUSCA POR MES / STATUS -->
		<table>        
        	<div class="icon" style='text-align: center;'>            	
	        	<tr><th id="cpanel">            
        		<p>Selecione o mês:    
                <select name="fecha" class="inputbox">
                <option value="">Todos</option>
                <?php						
					$sql = "SELECT DISTINCT dataSolicitacao from #__trancamentos ORDER BY dataSolicitacao";
					$database->setQuery($sql);
					$meses = $database->loadObjectList();
					
                    foreach($meses as $mesCombo) { ?>   
                    	<option value="<?php echo $mesCombo->dataSolicitacao; ?>" 
							<?php
                               if($mes == $mesCombo->dataSolicitacao)
                                  echo 'SELECTED';
                            ?>
                            > <?php 
                              	$data = explode("-", $mesCombo->dataSolicitacao);
                                echo $data[1]."/".$data[0];
                            ?>
                        </option>
                	<?php } ?>
                </select></p>
                </th>

                <th id="cpanel">            
        		<p>Selecione o status:
                <select name="buscaStatus" class="inputbox">
                    <option value="0" <?php if($status == 0) echo 'SELECTED';?>>Em aprovação pelo Orientador</option>
                    <option value="1" <?php if($status == 1) echo 'SELECTED';?>>Em aprovação pelo PPGI</option>
                    <option value="2" <?php if($status == 2) echo 'SELECTED';?>>Indeferido pelo Professor</option>
                    <option value="3" <?php if($status == 3) echo 'SELECTED';?>>Indeferido pelo PPGI</option>
                    <option value="4" <?php if($status == 4) echo 'SELECTED';?>>Deferido</option>
                    <option value="5" <?php if($status == 5) echo 'SELECTED';?>>Existentes</option>                    
                </select></p>
                </th>
                <th><input type="submit" value="Buscar"></th>
            </div>
        </table>
        
        <?php if (sizeof($trancamentos) == 0) { ?>
			<div id="noItens" style="background-color:#d1f0b6; padding:15px; font-size:13px; color:#29480e; border:1px solid #29480e; border-radius:5px;">Não há solicitações para este filtro!</div>
		<?php } else { ?>

		<!--Tabela que lista os pedidos-->
		<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters" class="tabela">
			<thead>
				<tr bgcolor="#002666">
	                <th width="3%" align="center"></th>
					<th width="5%" align="center"><font color="#FFC000">Status</font></th>
					<th width="6%" align="center"><font color="#FFC000">Date de Solicitação</font></th>
					<th width="15%" align="center"><font color="#FFC000">Nome do Aluno</font></th>
					<th width="15%" align="center"><font color="#FFC000">Orientador</font></th>
					<th width="5%" align="center"><font color="#FFC000">Curso</font></th>
					<th width="5%" align="center"><font color="#FFC000">Ingresso</font></th>                  
				</tr>
			</thead>
			<tbody>
				<?php
				$table_bgcolor_even="#e6e6e6";
				$table_bgcolor_odd="#FFFFFF";
				
				$curso = array (1 => "mestrado",2 => "doutorado",3 => "especial");
				$status_pedido = array (0 => "Em aprovação pelo Professor",
										1 => "Em aprovação pelo PPGI",
										2 => "Indeferido pelo Professor",
										3 => "Indeferido pelo PPGI",
										4 => "Deferido",
										5 => "Existente"); 
				$i = 0;

				/*Pega cada item da lista e coloca em uma linha da tabela*/
				foreach ($trancamentos as $trancamento) {
					$sqlAluno = "SELECT aluno.nome, aluno.curso, aluno.anoingresso, orientador.nomeProfessor
								FROM j17_aluno aluno,j17_professores orientador
								WHERE aluno.id =".$trancamento->idAluno." AND orientador.id =".$trancamento->idOrientador;
								
					$database->setQuery($sqlAluno);
					$resultado = $database->loadObjectList();

					foreach ($resultado as $t){
						$aluno = $t;
					}

					//muda as cores das linhas da tabela
					$i = $i + 1;
					if ($i % 2){
						echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
							
					} else {
						echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
					}
					?>
				<tr>
					<td align="center">
                        	<input type="radio" name="idSolicSelec" value="<?php echo $trancamento->idAluno;?>">
					</td>
					<td align="center"><img src="components/com_portalaluno/images/icon_status<?php echo $trancamento->status; ?>.png" title="<?php echo $status_pedido[$trancamento->status]; ?>" /></td>
					<td align="center">
						<?php
							$data = explode("-", $trancamento->dataSolicitacao);
							echo $data[2]."/".$data[1]."/".$data[0];
                        ?>
					</td>
					<td align="center"><?php echo $aluno->nome;?></td>
					<td align="center"><?php echo $aluno->nomeProfessor;?></td>
					<td align="center"><img border='0' src='components/com_portalsecretaria/images/<?php echo $curso[$aluno->curso];?>.gif' title='<?php echo $curso[$aluno->curso];?>'></td>
					<td align="center">
						<?php 
							$data = explode("-", $aluno->anoingresso);
							echo $data[1]."/".$data[0];
						?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
        <br>Total de Solicitações: <b><?php echo sizeof($trancamentos);?></b>
        
        <?php } ?>
        
        <input name='idAluno' type='hidden' value='<?php echo $aluno->id?>' />    
	    <input name='idSolicSelec' type='hidden' value='0' />        
        <input name='task' type='hidden' value='trancamento' />

	</form>

<?php } ?>


<?php // ------------------------- FUNÇÕES -------------------------
	function mostrarDetalhesTrancamento($aluno) {
		$Itemid = JRequest::getInt('Itemid', 0);
		
		
	    $curso = array (1 => "Mestrado",2 => "Doutorado",3 => "Especial");
        $database =& JFactory::getDBO();
		$sql = "SELECT id, justificativa, status, documento FROM #__trancamentos WHERE idAluno=".$aluno->id." ORDER BY dataSolicitacao";
		$database->setQuery($sql);
		$trancamentos = $database->loadObjectList();
		
        $status_pedido = array (0 => "Em aprovação pelo Professor",
										1 => "Em aprovação pelo PPGI",
										2 => "Indeferido pelo Professor",
										3 => "Indeferido pelo PPGI",
										4 => "Deferido");

		foreach($trancamentos as $t)
			$trancamento = $t;				
		?>	
    
		<script type="text/javascript">
            function julgar(form, fator) {
                if (fator==0){
                  form.task.value = 'aprovarTrancamento';
                  form.submit();
                } else {
                  form.task.value = 'reprovarTrancamento';
                  form.submit();
                }
            }
        </script>
    
        <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
            <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
                <div class="cpanel2">   
                    <div class="icon" id="toolbar-print">
                        <a href="javascript:imprimirTrancamento(<?php echo $aluno->id;?>)">
                        <span class="icon-32-copy"></span>Imprimir</a>
                    </div>
                    
                    <?php if ($t->status <> 0){ ?>
                    <div class="icon" id="toolbar-aprovar">
                        <a href="javascript:julgar(document.form,0)">
                        <span class="icon-32-checkin"></span> <?php echo JText::_( 'Aprovar' ); ?></a>
                    </div>
    
                    <div class="icon" id="toolbar-reprovar">
                        <a href="javascript:julgar(document.form, 1)">
                        <span class="icon-32-cancel"></span> <?php echo JText::_( 'Reprovar' ); ?></a>
                    </div>
                    
                    <div class="icon" id="toolbar-desfazer">
                        <a href="javascript:desfazerTrancamento(document.form)">
                        <span class="icon-32-purge"></span> <?php echo JText::_( 'Destrancar' ); ?></a>
                    </div>
                    <?php } ?>
                    <div class="icon" id="toolbar-back">
                        <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=trancamento">
                        <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                    </div>				
                </div>
              
                <div class="clr"></div>
                </div>
                  <div class="pagetitle icon-48-contact"><h2>Dados da Solicitação</h2></div>
                </div>
            </div>
            
            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                <tr style="background-color: #7196d8;">
                    <td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
                </tr>        
                <tr>
                    <td bgcolor="#CCCCCC"><strong>Aluno: </strong></td>
                    <td><?php echo $aluno->nome;?></td>
                </tr>
                <tr>
                    <td bgcolor="#CCCCCC"><strong>Justificativa: </strong></td>
                    <td><textarea name="justificativa" cols="70" rows="5" readonly><?php echo $trancamento->justificativa;?></textarea></td>
               </tr>
                <tr>
                    <td bgcolor="#CCCCCC"><strong>Documento (s):</strong></td>
                    <td><a href="javascript:visualizarDocumento(<?php echo $aluno->id;?>)"> Visualizar Documentação do Aluno</a>
                    </td>
                </tr>       
                <tr>
                    <td bgcolor="#CCCCCC"><strong>Status do Pedido:</strong></td>
                    <td><img src="components/com_portalaluno/images/icon_status<?php echo $t->status; ?>.png" title="<?php echo $status_pedido[$t->status]; ?>" /> <?php echo $status_pedido[$t->status];?></td>
                </tr>
            </table>
            
            <input name='idTrancamento' type='hidden' value='<?php echo $trancamento->id;?>'>
            <input name='idAluno' type='hidden' value='<?php echo $aluno->id;?>'>
            <input name='task' type='hidden' value='mostrarDetalhesTrancamento'>		
        </form>
<?php } ?>

<?php 

	function identificarTrancamentoID($idTrancamento) {
		
		$database =& JFactory::getDBO();		
		$sqltrancamento = "SELECT * FROM #__trancamentos WHERE id =".$idTrancamento;
		$database->setQuery($sqltrancamento);
		$resultado = $database->loadObjectList();
		
		if ( $resultado ){
			return $resultado[0];	
		} else
			return null;
			
	}
?>
<?php
	function aprovarTrancamento($mes,$status,$aluno) {
		$Itemid = JRequest::getInt('Itemid', 0);

		$database =& JFactory::getDBO();		
		$sqltrancamento = "UPDATE #__trancamentos SET status = 4, dataInicio = '".date("Y-m-d")."' WHERE idAluno = ".$aluno->id."";
		$database->setQuery($sqltrancamento);
		$funcionou = $database->Query();
		
		/* ATUALIZAÇÃO O STATUS DO ALUNO PARA TRANCADO */
		$sqlData = "UPDATE #__aluno SET status = '5' WHERE id = '".$aluno->id."'";
		$database->setQuery($sqlData);
		$funcionou2 = $database->Query();
						
		if ($funcionou2) {
			JFactory::getApplication()->enqueueMessage('Solicitação Deferida pelo IComp!');
			telaTrancamento($mes,$status);	
		} else 
			echo JError::raiseWarning( 100, 'ERRO: Solicitação não enviada.' );
	} 
?>

<?php
	function reprovarTrancamento($mes,$status,$aluno) {
		$Itemid = JRequest::getInt('Itemid', 0);
		
		$database =& JFactory::getDBO();	
		$sqltrancamento = "UPDATE #__trancamentos SET status = 3 WHERE idAluno = ".$aluno->id."";
		$database->setQuery($sqltrancamento);
		$funcionou = $database->Query();
		
		if ($funcionou) {
			JFactory::getApplication()->enqueueMessage('Solicitação Indeferida pelo Icomp!');
			telaTrancamento($mes,$status);	
		} else 
			echo JError::raiseWarning( 100, 'ERRO: Solicitação não enviada.' );
	} 
?>

<?php
	function desfazerTrancamento($aluno, $idTrancamento,$mes,$status) {
		
		$Itemid = JRequest::getInt('Itemid', 0);
		
		$trancamento = identificarTrancamentoID($idTrancamento);
		
		$timeInicial = strtotime($trancamento->dataInicio);
		$timeAtual = strtotime(date("Y-m-d"));
		
		$diferenca = $timeAtual-$timeInicial;
		$dias = (int) floor($diferenca/(60*60*24));
		
		$novoPrazo =  date('Y-m-d', strtotime("+".$dias." days",strtotime("$aluno->anoconclusao")));		
		
		$database =& JFactory::getDBO();		
		$sqltrancamento = "UPDATE #__trancamentos SET status = 5, dataTermino ='".date("Y-m-d")."' WHERE idAluno = ".$aluno->id." AND id =".$idTrancamento."";
		$database->setQuery($sqltrancamento);
		$funcionou = $database->Query();
		
		$sqlData = "UPDATE #__aluno SET status = '1', anoconclusao = '".$novoPrazo."' WHERE id = '".$aluno->id."'";
		$database->setQuery($sqlData);
		$funcionou2 = $database->Query();
						
		if ($funcionou2) {
			JFactory::getApplication()->enqueueMessage('Operação Realizada com sucesso!');
			telaTrancamento($mes,$status);	
		} else 
			echo JError::raiseWarning( 100, 'ERRO: Solicitação não enviada.' );
	} 
?>

<?php
	function imprimirTrancamento($aluno,$idTrancamento) {
		global $mosConfig_lang;
		
		$Itemid = JRequest::getInt('Itemid', 0);
		
		$database =& JFactory::getDBO();
		$sql = "SELECT * FROM #__trancamentos WHERE id=".$idTrancamento." ";
		$database->setQuery($sql);
		$trancamentos = $database->loadObjectList();
				
		foreach($trancamentos as $t)
			$trancamento = $t;
	
		$curso = array (1 => "MESTRADO EM INFORM�TICA",2 => "DOUTORADO EM INFORM�TICA",3 => "MESTRADO EM INFORM�TICA");
	
		$mes = date("m");
		
		$dataIngresso = $aluno->anoingresso;
		$dataIngresso = explode("-", $dataIngresso);
		$novaDataIngresso = $dataIngresso[1]."/".$dataIngresso[0];
		
		$dataConclusao = $aluno->anoconclusao;
		$novoPrazo =  date('Y-m-d', strtotime("+120 days",strtotime("$dataConclusao")));
		$novoPrazo = explode("-",$novoPrazo);
		$prazoFormatado = $novoPrazo[1]."/".$novoPrazo[0];
		
		
		switch ($mes) {	
			case 1: $mes = "Janeiro"; break;
			case 2: $mes = "Fevereiro"; break;
			case 3: $mes = "Mar�o"; break;
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
		$pdf->ezText('<b>MINIST�RIO DA EDUCA��O</b>',10,$header);
		$pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$header);
		$pdf->ezText('<b>INSTITUTO DE COMPUTA��O</b>',10,$header);
		$pdf->ezText('',11,$optionsText);
		$pdf->ezText('<b>PROGRAMA DE P�S-GRADUA��O EM INFORM�TICA</b>',10,$header);
		$pdf->addText(495,665,8,"<b>Data:</b> ".date("d/m/Y"),0,0);
		$pdf->addText(495,675,8,"<b>Hora:</b> ".date("H:i"),0,0);
		$pdf->setLineStyle(1);
		$pdf->line(20, 690, 580, 690);
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText('<b>SOLICITA��O DE TRANCAMENTO DE CURSO</b>',12,$optionsText);
	
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
	
		$tag = $tag. ", sob a matr�cula n. $aluno->matricula, no Curso de <b>".$curso[$aluno->curso]."</b> na area de <b>CIENCIA DA COMPUTA��O</b> do Programa de P�s-Gradua��o em Inform�tica da Universidade Federal do Amazonas, " .
				"ingresso(a) em <b>$novaDataIngresso</b> e com previs�o de defesa para <b>$novaDataConclusao</b>. Venho por meio desta, solicitar o <b>Trancamento</b> da minha matr�cula, sob a seguinte justificativa: " .
				"<b>$trancamento->justificativa</b>";
			
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
		$pdf->addText(80,40,8,'Av. Rodrigo Ot�vio, 6.200 - Campus Universit�rio Senador Arthur Virg�lio Filho - CEP 69077-000 - Manaus, AM, Brasil',0,0);
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

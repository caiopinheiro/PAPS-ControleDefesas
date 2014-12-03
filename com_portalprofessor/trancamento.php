<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
<link rel="stylesheet" href="components/com_inscricaoppgi/estilo.css" type="text/css" />

<script type="text/javascript">
	function visualizarTrancamento(form) {	
		var idSelecionado = 0;
		
		for(i = 0; i < form.idAlunoSelec.length;i++)
			if(form.idAlunoSelec[i].checked) idSelecionado = form.idAlunoSelec[i].value;
	
		if(idSelecionado > 0){
		   form.task.value = 'mostrarDetalhesTrancamento';
		   form.idAluno.value = idSelecionado;
		   form.submit();
		} else {
			alert('Ao menos 1 item deve ser selecionado para visualização.');
		}
	}

	function imprimir(idAluno) {
		window.open("components/com_portalaluno/atestados/PPGI-Trancamento-"+idAluno+".pdf","_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
	}
</script>

<?php
function telaTrancamento($professor, $status) {
	$Itemid = JRequest::getInt('Itemid', 0);
	
	$database =& JFactory::getDBO();	
	
	if($status == NULL) 
		$status = 0;
		
	if($status < 4)
    	$sql = "SELECT * FROM #__trancamentos WHERE idOrientador = ".$professor->id." AND status = $status";
    else
    	$sql = "SELECT * FROM #__trancamentos WHERE idOrientador = ".$professor->id." AND status = 0";

	$database->setQuery($sql);
	$trancamentos = $database->loadObjectList(); ?>

	<link rel="stylesheet" href="components/com_portalsecretaria/assets/css/estilo.css" type="text/css" />

<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">

    <!--Barra de Ferramentas Superior-->
    <div id="toolbar-box">
        <div class="m">
            <div class="toolbar-list" id="toolbar">
                <div class="cpanel2">
                    <div class="icon" id="toolbar-visualizar">
                        <a href="javascript:visualizarTrancamento(document.form)">
                        <span class="icon-32-preview"></span> <?php echo JText::_( 'Visualizar' ); ?></a>
                    </div>
                    
                    <div class="icon" id="toolbar-back">
                        <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
                        <span class="icon-32-back"></span> <?php echo JText::_( 'Voltar' ); ?></a>
                    </div>
                </div>
                <div class="clr"></div>
            </div>

            <div class="pagetitle icon-48-inbox">
                <h2>Solicita&#231;&#245;es de Trancamento de Curso</h2>
            </div>
        </div>
    </div>
    
    <!-- FILTRO DA BUSCA -->
    <legend>Filtro para consulta</legend>
    <p>
	<span>Status</span>
    <select name="buscaStatus" style="width:280px;">
        <option value="0" <?php if($status == 0) echo 'SELECTED';?>>Em aprovação pelo Orientador</option>
        <option value="1" <?php if($status == 1) echo 'SELECTED';?>>Em aprovação pelo PPGI</option>
        <option value="2" <?php if($status == 2) echo 'SELECTED';?>>Indeferido pelo Professor</option>
        <option value="3" <?php if($status == 3) echo 'SELECTED';?>>Indeferido pelo PPGI</option>
        <option value="4" <?php if($status == 4) echo 'SELECTED';?>>Deferido</option>
    </select>
    <button type="submit" value="Buscar" class="btn btn-primary">
        <i class="icone-search icone-white"></i> Buscar
    </button>
    </p>

	<?php if (sizeof($trancamentos) == 0) { ?>
    	<div id="noItens" style="background-color:#d1f0b6; padding:15px; font-size:13px; color:#29480e; border:1px solid #29480e; border-radius:5px;">Não há solicitações para este filtro!</div>
	<?php } else { ?>
    
    <!--Tabela que lista os pedidos-->
    <table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters" class="tabela">
        <thead>
            <tr bgcolor="#002666">
                <th width="3%" align="center"></th>
                <th width="3%" align="center">Status</th>
                <th width="6%" align="center">Data do Envio</th>
                <th width="15%" align="center">Nome do Aluno</th>
                <th width="5%" align="center">Curso</th>
                <th width="5%" align="center">Ingresso</th>
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
									4 => "Deferido"); 
            $i = 0;

            foreach ($trancamentos as $trancamento) {
                $sqlAluno = "SELECT aluno.nome, aluno.curso, aluno.anoingresso 
                			FROM j17_aluno aluno,j17_professores orientador 
			                WHERE aluno.id =".$trancamento->idAluno;

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
                <?php
					if ($status == '0') { ?> 
                    	<input type="radio" name="idAlunoSelec" value="<?php echo $trancamento->idAluno;?>">
					<?php } else { } ?>
                </td>
                <td align="center"><img src="components/com_portalaluno/images/icon_status<?php echo $trancamento->status; ?>.png" title="<?php echo $status_pedido[$trancamento->status]; ?>" /></td>               
                <td align="center">
				  <?php
					  $data = explode("-", $trancamento->dataSolicitacao);
					  echo $data[2]."/".$data[1]."/".$data[0]; 
                  ?>
                </td>
                <td align="center"><?php echo $aluno->nome;?></td>
                <td align="center"> <img border='0' src='components/com_portalsecretaria/images/<?php echo $curso[$aluno->curso];?>.gif' title='<?php echo $curso[$aluno->curso];?>'></td>
                <td align="center">
					<?php 
					 	$data = explode("-", $aluno->anoingresso);
					  	echo $data[2]."/".$data[1]."/".$data[0];
					?>
                </td>
            </tr>           
		<?php } ?>
        </tbody>
    </table>
    <br>Total de Solicitações: <b><?php echo sizeof($trancamentos);?></b>
    
    <?php } ?>
   
    <input name='idAluno' type='hidden' value='<?php echo $aluno->id?>'>    
    <input name='idAlunoSelec' type='hidden' value='0'> 
    <input name='task' type='hidden' value='trancamento'>
</form>

<?php } ?>

<?php // ------------------------- FUNÇÕES -------------------------
	function mostrarDetalhesTrancamento($aluno) {
		$Itemid = JRequest::getInt('Itemid', 0);
		
	    $curso = array (1 => "Mestrado",2 => "Doutorado",3 => "Especial");
		$database =& JFactory::getDBO();
		$sql = "SELECT id, justificativa, documento FROM #__trancamentos WHERE idAluno=".$aluno->id." ORDER BY dataSolicitacao";
		$database->setQuery($sql);
		$trancamentos = $database->loadObjectList();
				
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
    
		<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
		<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
			<div class="cpanel2">
                <div class="icon" id="toolbar-aprovar">
                    <a href="javascript:julgar(document.form,0)">
                    <span class="icon-32-checkin"></span> <?php echo JText::_( 'Aprovar' ); ?></a>
                </div>

                <div class="icon" id="toolbar-reprovar">
                    <a href="javascript:julgar(document.form, 1)">
                    <span class="icon-32-cancel"></span> <?php echo JText::_( 'Reprovar' ); ?></a>
                </div>

				<div class="icon" id="toolbar-back">
					<a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=trancamento">
					<span class="icon-32-back"></span>Voltar</a>
				</div>				
			</div>
		  
			<div class="clr"></div>
			</div>
			  <div class="pagetitle icon-48-contact"><h2>Dados da Solicitação do Aluno</h2></div>
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
                <td bgcolor="#CCCCCC"><strong>Curso: </strong></td>
                <td><?php echo $curso[$aluno->curso];?></td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC"><strong>Ano de Ingresso: </strong></td>
                <td><?php
                         $data = explode("-", $aluno->anoingresso);
					     echo $data[2]."/".$data[1]."/".$data[0];
                ?></td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC"><strong>Previsão de Conclusão: </strong></td>
                <td><?php
                         $data = explode("-", $aluno->anoconclusao);
					     echo $data[2]."/".$data[1]."/".$data[0];
                ?></td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC"><strong>Justificativa: </strong></td>
                <td><textarea name="justificativa" cols="70" rows="5" readonly="readonly"><?php echo $trancamento->justificativa;?></textarea></td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC"><strong>Documento:</strong></td>
                <td><a href="javascript:imprimir(<?php echo $aluno->id;?>)"><img border='0' src='components/com_portalaluno/images/icon_pdf.gif' title='Documento'> Download do Arquivo</a>
            </tr>       
        </table>        
        
		<input name='idTtrancamento' type='hidden' value='<?php echo $trancamento->id;?>'>
		<input name='idAluno' type='hidden' value='<?php echo $aluno->id;?>'>
		<input name='task' type='hidden' value='mostrarDetalhesTrancamento'>		
	</form>
    
<?php } ?>

<?php
	function aprovarTrancamento($aluno,$professor,$status) {
		$Itemid = JRequest::getInt('Itemid', 0);
		
		$database =& JFactory::getDBO();		
		$sqlTrancamento = "UPDATE #__trancamentos SET status = 1, dataAprovOrientador = '".date("Y-m-d H:i:s")."' WHERE idAluno = ".$aluno->id."";
		$database->setQuery($sqlTrancamento);
		$funcionou = $database->Query();
		
		if ($funcionou) {
			JFactory::getApplication()->enqueueMessage('Solicitação enviada para Avaliação do Colegiado!');
			//header('Location: index.php?option=com_portalprofessor&Itemid='.$Itemid.'&task=prorrogacao&lang=pt-br');
			telaTrancamento($professor, $status);	
		} else 
			echo JError::raiseWarning( 100, 'ERRO: Solicitação não enviada.' );
	}
?>

<?php
	function reprovarTrancamento($aluno,$professor,$status) {
		$Itemid = JRequest::getInt('Itemid', 0);
		
		$database =& JFactory::getDBO();	
		$sqlTrancamento = "UPDATE #__trancamentos SET status = 2, dataAprovOrientador = '".date("Y-m-d H:i:s")."' WHERE idAluno = ".$aluno->id."";
		$database->setQuery($sqlTrancamento);
		$funcionou = $database->Query();
		
		if ($funcionou) {
			JFactory::getApplication()->enqueueMessage('Solicitação indeferida com sucesso!');
			//header('Location: index.php?option=com_portalprofessor&Itemid='.$Itemid.'&task=prorrogacao&lang=pt-br');
			telaTrancamento($professor,$status);
		} else 
			echo JError::raiseWarning( 100, 'ERRO: Solicitação não enviada.' );
	} 
?>
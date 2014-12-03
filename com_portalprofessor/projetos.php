<?php // LISTAGEM DOS PROJETOS 
function listarProjetos($professor) { 
	$Itemid = JRequest::getInt('Itemid', 0);
	
	$database =& JFactory::getDBO(); 
	
	$sql = "SELECT P.id, P.nomeprojeto, P.orcamento, P.saldo, P.data_inicio, P.data_fim,
            P.coordenador_id, P.agencia_id, P.edital, P.proposta, P.status, A.sigla 
            FROM j17_contproj_projetos AS P
            INNER JOIN j17_contproj_agencias AS A
                ON A.id = P.agencia_id
            WHERE coordenador_id = '$professor->id'";
    $database->setQuery($sql);
    $projetopds = $database->loadObjectList();
	?>
    
    <script language="JavaScript">
		function visualizar(form) {
			var idSelecionado = 0;
			
			for(i = 0;i < form.idProjetopdSelec.length;i++)
				if(form.idProjetopdSelec[i].checked) 
					idSelecionado = form.idProjetopdSelec[i].value;
					
			if(idSelecionado > 0) {
				form.task.value = 'verSimplificado';
				form.idProjeto.value = idSelecionado;
				form.submit();
			} else {
				alert('Selecione o projeto a ser visualizado')
			}
		}
		
		function visualizar2(form) {
			var idSelecionado = 0;
			
			for(i = 0;i < form.idProjetopdSelec.length;i++)
				if(form.idProjetopdSelec[i].checked) 
					idSelecionado = form.idProjetopdSelec[i].value;
					
			if(idSelecionado > 0) {
				form.task.value = 'verDetalhes';
				form.idProjeto.value = idSelecionado;
				form.submit();
			} else {
				alert('Selecione o projeto a ser visualizado')
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
    
	<link rel="stylesheet" href="components/com_portalsecretaria/assets/css/estilo.css" type="text/css" />
    
    <form method="post" name="form" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">    
    
	<!-- BARRA DE FERRAMENTAS -->
 	  <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        <div class="cpanel2">            
            <div class="icon" id="toolbar-preview">
                <a href="javascript:visualizar(document.form)">
                <span class="icon-32-preview"></span><?php echo JText::_( 'Visualizar' ); ?></a>
            </div>
            
            <div class="icon" id="toolbar-preview">
                <a href="javascript:visualizar2(document.form)">
                <span class="icon-32-preview"></span><?php echo JText::_( 'Detalhes' ); ?></a>
            </div>
            
            <div class="icon" id="toolbar-back">
                <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
                <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
            </div>
        </div>

        <div class="clr"></div>
        </div>
        <div class="pagetitle icon-48-contact"><h2>Meus Projetos</h2></div>
        </div>
    </div>
    
    <!-- LISTAGEM DOS PROJETOS -->
    <table class="table table-striped" id="tablesorter-imasters">
    	<thead>
            <tr>
                <th></th>
                <th>Projeto</th>
                <th>Financiador</th>
                <th>Período</th>
                <th>Orçamento</th>
                <th>Saldo</th>
                <th>Status</th>
            </tr>
     	</thead>
        
     	<tbody>
       	<?php foreach ($projetopds as $projeto)  
			$i = $i + 1;
			if ($i % 2) {
				echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
			} else {
				echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
			} ?>
            
			<td><input type="radio" name="idProjetopdSelec" value="<?php echo $projeto->id;?>" /></td>
            <td><?php echo $projeto->nomeprojeto; ?></td>
            <td><?php echo $projeto->sigla; ?></td>
            <td><?php echo dataBr($projeto->data_inicio).' a '.dataBr($projeto->data_fim); ?></td>
            <td><?php echo moedaBr($projeto->orcamento); ?></td>
            <td><?php echo moedaBr($projeto->saldo); ?></td>
            <td><?php echo $projeto->status; ?></td>
            </tr>
                  
        </tbody>
	</table>    
    <br />
    
	<span class="label label-inverse">Total de Projetos: <?php echo sizeof($projetopds); ?></span>
	
    <input name='task' type='hidden' value='verSimplificado' />
    <input name='idProjetopdSelec' type='hidden' value='<?php echo $projeto->id;?>' />
    <input name='idProjeto' type='hidden' value='' />
    
    </form>

<?php } ?>


<?php // RELATÓRIO SIMPLIFICADO DE DESPESAS
function verProjeto($idProjeto) {
	
	$Itemid = JRequest::getInt('Itemid', 0);	
	$database =& JFactory::getDBO(); 
	
	$sql = "SELECT P.id, P.nomeprojeto, P.orcamento, P.saldo, P.data_inicio, P.data_fim, P.data_fim_alterada
		P.coordenador_id, P.agencia_id, P.edital, P.proposta, P.status, P.conta, P.agencia, P.banco_id, A.sigla 
		FROM j17_contproj_projetos AS P
		INNER JOIN j17_contproj_agencias AS A
			ON A.id = P.agencia_id
		WHERE P.id = '$idProjeto'";
    $database->setQuery($sql);
    $projetopds = $database->loadObjectList(); 
	
	$projetopd = identificarProjetopdID($idProjeto);
 
	$sqlCapital = "SELECT R.nome, R.codigo, R.tipo,
          RP.id, RP.projeto_id, RP.rubrica_id, RP.descricao, RP.valor_total, RP.valor_gasto, RP.valor_disponivel
          FROM #__contproj_rubricasdeprojetos AS RP 
          INNER JOIN #__contproj_rubricas AS R
          ON RP.rubrica_id = R.id  
          WHERE  projeto_id = $idProjeto AND tipo = 'Capital' ORDER BY tipo, codigo";
		  
	$sqlCusteio = "SELECT R.nome, R.codigo, R.tipo,
          RP.id, RP.projeto_id, RP.rubrica_id, RP.descricao, RP.valor_total, RP.valor_gasto, RP.valor_disponivel
          FROM #__contproj_rubricasdeprojetos AS RP 
          INNER JOIN #__contproj_rubricas AS R
          ON RP.rubrica_id = R.id  
          WHERE  projeto_id = $idProjeto AND tipo = 'Custeio' ORDER BY tipo, codigo";

    $database->setQuery( $sqlCapital );
    $rubricadeprojetosCapital = $database->loadObjectList();
	$database->setQuery( $sqlCusteio );
	$rubricadeprojetosCusteio = $database->loadObjectList();
	
	$sql2 = "SELECT R.tipo, SUM(valor_total) AS concedido, SUM(valor_gasto) AS gasto, SUM(valor_disponivel) AS saldo 
			FROM j17_contproj_rubricasdeprojetos AS RP
			JOIN j17_contproj_rubricas AS R ON R.id = RP.rubrica_id
			WHERE projeto_id = $idProjeto
			GROUP BY tipo ORDER BY tipo";
	$database->setQuery($sql2);
    $resumo = $database->loadObjectList(); //Totais

    $sql = "SELECT TR.id, TR.projeto_id, rubrica_origem, rubrica_destino, O.descricao AS rubricaOrigem, D.descricao AS rubricaDestino, TR.valor, TR.data, TR.autorizacao
             FROM #__contproj_transferenciassaldorubricas AS TR 
             INNER JOIN #__contproj_rubricasdeprojetos AS O ON TR.rubrica_origem = O.id  
             INNER JOIN #__contproj_rubricasdeprojetos AS D ON TR.rubrica_destino = D.id    
             WHERE  TR.projeto_id = $idProjeto ORDER BY data"; //idprojeto->identifica o projeto                 
    
    $database->setQuery( $sql );
    $gerenciarTranferenciadeRubricas = $database->loadObjectList();  ?>
    
    <script type="text/javascript">
		function imprimir(ItemId, idProjetopd) {
			window.open("index.php?option=com_controleprojetos&Itemid="+ItemId+"&task=relatorioSimplificadoDespesa&idProjetopd="+idProjetopd,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
		}
	</script>
    
  	<link type="text/css" rel="stylesheet" href="components/com_portalsecretaria/assets/css/estilo.css" />
    
	<!-- BARRA DE FERRAMENTAS -->
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        <div class="cpanel2"> 
	        <div class="icon" id="toolbar-print">
    	        <a href="javascript:imprimir(<?php echo $Itemid;?>, <?php echo $projetopd->id;?>)">
                <span class="icon-32-print"></span><?php echo JText::_( 'Imprimir' ); ?></a>
            </div>           
            
	        <div class="icon" id="toolbar-back">
                <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=listarProjetos">
                <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
            </div>
        </div>

        </div>
        <div class="pagetitle icon-48-contact"><h2>Relatório Simplificado de Movimentações</h2></div>
        </div>
    </div>
    
    <!-- INFORMAÇÕES -->
	<table cellpadding="6" width="100%">
		<tr style="background-color: #7196d8;">
    	   <td colspan="4"><font color="#FFFFFF"><b>Informações</b></font></td>
       	</tr>
       	<tr>
			<td><b>Projeto:</b></td>
        	<td width="45%"><?php echo $projetopd->nomeprojeto;?></td>
   	    	<td><b>Proposta:</b></td>
        	<td><a href="<?php echo $projetopd->proposta;?>" target="_blank"><img border='0' src='components/com_controleprojetos/images/icon_pdf.gif' width='16' height='16'></a></td>
		</tr>  
      	<tr> 
        	<td><b>Coordenador:</b></td>
         	<td>
            <?php 
                 $database->setQuery("SELECT nomeProfessor from #__professores WHERE id = $projetopd->coordenador_id");
	             $coordenador = $database->loadObjectList();
	             echo $coordenador[0]->nomeProfessor;
            ?>
        	</td> 
	    	<td><b>Status:</b></td>
        	<td> <?php echo $projetopd->status;?></td>
      	</tr>
       	<tr> 
            <td><b>Data de Início:</b></td>
            <td> <?php echo dataBr($projetopd->data_inicio); ?></td>
            <td><b>Data de Término:</b></td>
            <td>
				<?php 
				if ($projetopd->data_fim_alterada == '0000-00-00') {
					echo dataBr($projetopd->data_fim);	
				} else {
					echo dataBr($projetopd->data_fim_alterada); 
				} ?>
            </td>
      	</tr>
      	<tr> 
            <td><b>Orçamento:</b></td>
            <td>R$ <?php echo moedaBr($projetopd->orcamento); ?></td>
            <td><b>Saldo:</b></td>
            <td>R$ <?php echo moedaBr($projetopd->saldo); ?></td>
      	</tr> 
      	<tr>  
			<td><b>Financiador/Edital:</b></td>
        	<td><a href="<?php echo $projetopd->edital;?>" target="_blank"><img src="components/com_controleprojetos/images/icon_pdf.gif"></a></td>
	  		<td><b>Conta:</b></td>
        	<td><?php echo $projetopd->conta;?></td>
      	</tr>
      	<tr>
        	<td><b>Banco:</b></td>
        	<td>
            <?php 
                 $database->setQuery("SELECT * FROM #__contproj_bancos WHERE id = $projetopd->banco_id");
	             $banco = $database->loadObjectList();
	             echo $banco[0]->nome;
            ?>
        	</td>
        	<td><b>Agência:</b></td>
        	<td><?php echo $projetopd->agencia;?></td>
      	</tr>
   	</table>
    
    <hr />

    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
		<div class="cpanel2">
            <div class="icon" id="toolbar-apply">
                <span class="icon-32-apply"></span>
                <?php echo JText::_( '<b>Concedido (R$)</b><br>R$ '.moedaBr($resumo[0]->concedido)); ?>
            </div>
            
            <div class="icon" id="toolbar-unblock">           			
                <span class="icon-32-unblock"></span>
                <?php echo JText::_( '<b>Liberado (R$)</b><br>R$ '.moedaBr($resumo[0]->saldo+$resumo[0]->gasto)); ?>
            </div>
            
            <div class="icon" id="toolbar-remove">
                <span class="icon-32-remove"></span>
                <?php echo JText::_( '<b>Gasto (R$)</b><br>R$ '.moedaBr($resumo[0]->gasto)); ?>
            </div>
            
            <div class="icon" id="toolbar-purge">
                <span class="icon-32-purge"></span>
                <?php echo JText::_( '<b>Saldo (R$)</b><br>R$ '.moedaBr($resumo[0]->saldo)); ?>
            </div>
		</div>
		<div class="clr"></div>
		</div>
    
		<div class="pagetitle icon-48-module"><h2>Itens de Capital</h2></div>
    </div></div>
    
	<table class="table table-striped">
		<thead class="head-one">
        <tr>
            <th width="10%">Tipo</th>
            <th width="20%">Item de Dispêndio</th>
            <th width="44%">Descrição</th>
            <th width="12%">Receitas</th>
            <th width="12%">Despesas</th>
            <th width="12%">Saldo</th>
		</tr>
     	</thead>
        
		<tbody>
		<?php
		foreach( $rubricadeprojetosCapital as $gerenciarItemRubricadeprojetos ) { ?>            
            <tr>
                <td align="left"><?php echo $gerenciarItemRubricadeprojetos->tipo; ?></td>                
                <td align="left"><?php echo $gerenciarItemRubricadeprojetos->nome; ?></td>                
                <td align="left"><?php echo $gerenciarItemRubricadeprojetos->descricao; ?></td>
                <td align="left"><?php echo moedaBr($gerenciarItemRubricadeprojetos->valor_total); ?></td>
                <td align="left"><?php echo moedaBr($gerenciarItemRubricadeprojetos->valor_gasto); ?></td>
                <td align="left"><?php echo moedaBr($gerenciarItemRubricadeprojetos->valor_disponivel); ?></td>
            </tr>    
        <?php } ?>        
        </tbody>
    </table>    
	<hr />
    
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
		<div class="cpanel2">
            <div class="icon" id="toolbar-apply">
                <span class="icon-32-apply"></span>
                <?php echo JText::_( '<b>Concedido (R$)</b><br>R$ '.moedaBr($resumo[1]->concedido)); ?>
            </div>
            
            <div class="icon" id="toolbar-unblock">           			
                <span class="icon-32-unblock"></span>
                <?php echo JText::_( '<b>Liberado (R$)</b><br>R$ '.moedaBr($resumo[1]->saldo+$resumo[1]->gasto)); ?>
            </div>
            
            <div class="icon" id="toolbar-remove">
                <span class="icon-32-remove"></span>
                <?php echo JText::_( '<b>Gasto (R$)</b><br>R$ '.moedaBr($resumo[1]->gasto)); ?>
            </div>
            
            <div class="icon" id="toolbar-purge">
                <span class="icon-32-purge"></span>
                <?php echo JText::_( '<b>Saldo (R$)</b><br>R$ '.moedaBr($resumo[1]->saldo)); ?>
            </div>
		</div>
	
        <div class="clr"></div>
		</div>        
    	<div class="pagetitle icon-48-massmail"><h2>Itens de Custeio</h2></div>
    </div></div>
    
	<table class="table table-striped">
        <thead class="head-one">
            <tr>
                <th width="10%">Tipo</th>
                <th width="20%">Item de Dispêndio</th>
                <th width="44%">Descrição</th>
                <th width="12%">Receitas</th>
                <th width="12%">Despesas</th>
                <th width="12%">Saldo</th>
            </tr>
        </thead>
        
		<tbody>
		<?php
        foreach($rubricadeprojetosCusteio as $gerenciarItemRubricadeprojetos) { ?>
            <tr>            
                <td align="left"><?php echo $gerenciarItemRubricadeprojetos->tipo; ?></td>                
                <td align="left"><?php echo $gerenciarItemRubricadeprojetos->nome; ?></td>                
                <td align="left"><?php echo $gerenciarItemRubricadeprojetos->descricao; ?></td>
                <td align="left"><?php echo moedaBr($gerenciarItemRubricadeprojetos->valor_total); ?></td>
                <td align="left"><?php echo moedaBr($gerenciarItemRubricadeprojetos->valor_gasto); ?></td>
                <td align="left"><?php echo moedaBr($gerenciarItemRubricadeprojetos->valor_disponivel); ?></td>
            </tr>
		<?php } ?>        
     	</tbody>        
     </table>     
	 <hr />
    
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        <div class="clr"></div>
        </div>
        <div class="pagetitle icon-48-clear"><h2>Transferências</h2></div>
    </div></div>
    
    <!-- Formulario -->
	<table class="table table-striped">
		<thead class="head-one">
      		<tr>
                <th>Data</th>
                <th>Rubrica Origem</th>
                <th>Rubrica Destino</th>
                <th>Valor</th>
                <th>Autorização</th>
			</tr>
		</thead>
        
        <tbody>
		<?php
		foreach($gerenciarTranferenciadeRubricas as $transferencia) { ?>
            <tr>                
                <td><?php echo dataBr($transferencia->data);?></td>
                <td><?php echo $transferencia->rubricaOrigem;?></td>
                <td><?php echo $transferencia->rubricaDestino;?></td>
                <td><?php echo moedaBr($transferencia->valor);?></td>
                <td><?php echo $transferencia->autorizacao;?></td>
            </tr>
        <?php } ?>           
		</tbody>
	</table>    
    <hr />
	
<?php } ?>


<?php // RELATÓRIO DETALHADO DE DESPESAS
function verProjetoDetalhes ($idProjeto) { 
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
	
    $projetopd = identificarProjetopdID($idProjeto);
	
    $sql = "SELECT RP.id, RP.projeto_id, P.nomeprojeto, RP.rubrica_id, R.nome AS nomerubrica, RP.descricao, RP.valor_total, RP.valor_gasto, RP.valor_disponivel
            FROM #__contproj_rubricasdeprojetos AS RP 
            INNER JOIN #__contproj_rubricas AS R
                ON RP.rubrica_id = R.id  
            INNER JOIN #__contproj_projetos AS P
                ON RP.projeto_id = P.id 
           WHERE  descricao LIKE '%$descricaoRelatorioDetalhadoDespesa%' AND projeto_id = $idProjeto ORDER BY descricao";
    $database->setQuery( $sql );
    $gerenciarRubricadeprojetos = $database->loadObjectList(); ?>

	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css" />

	<script type="text/javascript">
		function imprimir(ItemId, idProjetopd){
			window.open("index.php?option=com_controleprojetos&Itemid="+ItemId+"&task=relatorioDetalhadoDespesa&idProjetopd="+idProjetopd,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
		}
	</script>
    
	<link type="text/css" rel="stylesheet" href="components/com_portalsecretaria/assets/css/estilo.css" />    
    
	<!-- BARRA DE FERRAMENTAS -->
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        <div class="cpanel2"> 
	        <div class="icon" id="toolbar-print">
    	        <a href="javascript:imprimir(<?php echo $Itemid;?>, <?php echo $projetopd->id;?>)">
                <span class="icon-32-print"></span><?php echo JText::_( 'Imprimir' ); ?></a>
            </div>           
            
	        <div class="icon" id="toolbar-back">
                <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=listarProjetos">
                <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
            </div>
        </div>

        </div>
        <div class="pagetitle icon-48-contact"><h2>Relatório Detalhado de Despesas</h2></div>
        </div>
    </div>
    
    <!-- INFORMAÇÕES -->
	<table cellpadding="6" width="100%">
		<tr style="background-color:#7196d8; color:#FFF; font-weight:bold;">
    	   <td colspan="4">Informações</td>
       	</tr>
       	<tr>
			<td><b>Projeto:</b></td>
        	<td width="45%"><?php echo $projetopd->nomeprojeto;?></td>
   	    	<td><b>Proposta:</b></td>
        	<td><a href="<?php echo $projetopd->proposta;?>" target="_blank"><img border='0' src='components/com_controleprojetos/images/icon_pdf.gif' width='16' height='16'></a></td>
		</tr>  
      	<tr> 
        	<td><b>Coordenador:</b></td>
         	<td>
            <?php 
                 $database->setQuery("SELECT nomeProfessor from #__professores WHERE id = $projetopd->coordenador_id");
	             $coordenador = $database->loadObjectList();
	             echo $coordenador[0]->nomeProfessor;
            ?>
        	</td> 
	    	<td><b>Status:</b></td>
        	<td> <?php echo $projetopd->status;?></td>
      	</tr>
       	<tr> 
            <td><b>Data de Início:</b></td>
            <td> <?php echo dataBr($projetopd->data_inicio); ?></td>
            <td><b>Data de Término:</b></td>
            <td>
				<?php 
				if ($projetopd->data_fim_alterada == '0000-00-00') {
					echo dataBr($projetopd->data_fim);	
				} else {
					echo dataBr($projetopd->data_fim_alterada); 
				} ?>
            </td>
      	</tr>
      	<tr> 
            <td><b>Orçamento:</b></td>
            <td>R$ <?php echo moedaBr($projetopd->orcamento); ?></td>
            <td><b>Saldo:</b></td>
            <td>R$ <?php echo moedaBr($projetopd->saldo); ?></td>
      	</tr> 
      	<tr>  
			<td><b>Financiador/Edital:</b></td>
        	<td><a href="<?php echo $projetopd->edital;?>" target="_blank"><img src="components/com_controleprojetos/images/icon_pdf.gif"></a></td>
	  		<td><b>Conta:</b></td>
        	<td><?php echo $projetopd->conta;?></td>
      	</tr>
      	<tr>
        	<td><b>Banco:</b></td>
        	<td>
            <?php 
                 $database->setQuery("SELECT * FROM #__contproj_bancos WHERE id = $projetopd->banco_id");
	             $banco = $database->loadObjectList();
	             echo $banco[0]->nome;
            ?>
        	</td>
        	<td><b>Agência:</b></td>
        	<td><?php echo $projetopd->agencia;?></td>
      	</tr>
   	</table>
    
    <br />
    
	<h3>Dados das Despesas</h3>
    <hr />

	<?php foreach( $gerenciarRubricadeprojetos as $gerenciarItemRubricadeprojetos ) { ?> 
    
    <b>RUBRICA:</b> <span class="label label-info"><?php echo $gerenciarItemRubricadeprojetos->nomerubrica; ?></span>
    <br />
    
    <ul>
        <li><b>Descrição:</b> <?php echo $gerenciarItemRubricadeprojetos->descricao; ?></li>
        <li><b>Valor Total:</b> R$ <?php echo moedaBr($gerenciarItemRubricadeprojetos->valor_total); ?> | 
            <b>Valor Gasto:</b> R$ <?php echo moedaBr($gerenciarItemRubricadeprojetos->valor_gasto); ?> | 
            <b>Valor Disponível:</b> R$ <?php echo moedaBr($gerenciarItemRubricadeprojetos->valor_disponivel); ?>
        </li>
    </ul>
        
	<!-- SUB LISTA -->
	<table class="table table-striped">
		<thead class="head-one">
            <tr>
                <th width="15%">Data</th>                
                <th width="10%">Tipo</th>                				
                <th width="60%">Descrição</th>
                <th width="15%">Valor</th>
            </tr>
        </thead>
        
        <tbody>
			<?php // Acessa a tabela rubricas para exibir em list box
			$database->setQuery("SELECT D.id, D.rubricasdeprojetos_id , D.data_emissao, D.descricao, D.valor_despesa
							   FROM #__contproj_despesas AS D
							   INNER JOIN #__contproj_rubricasdeprojetos AS RP
									 ON D.rubricasdeprojetos_id = RP.id
							   WHERE  rubricasdeprojetos_id LIKE '$gerenciarItemRubricadeprojetos->id' ORDER BY data_emissao");							
			// Este $idProjeto - está Exclusivo para identificar Lista rubrica por rubrica de projetos
			$DespesaRubrica_listas = $database->loadObjectList();
				 
			$database->setQuery("SELECT R.id, R.rubricasdeprojetos_id , R.data, R.descricao, R.valor_receita
							   FROM #__contproj_receitas AS R
							   INNER JOIN #__contproj_rubricasdeprojetos AS RP
									 ON R.rubricasdeprojetos_id = RP.id
							   WHERE  rubricasdeprojetos_id LIKE '$gerenciarItemRubricadeprojetos->id' ORDER BY data");
			// Este  $idProjeto - está Exclusivo para identificar Lista rubrica por rubrica de projetos
			$ReceitaRubrica_listas = $database->loadObjectList();				 
				 
            $table_bgcolor_even="#e6e6e6";
            $table_bgcolor_odd="#FFFFFF";
			
            $i = 0;
			foreach($ReceitaRubrica_listas as $ReceitaRubrica_final_listas) {
                $i = $i + 1;
                if ($i % 2) {
					echo("<tr bgcolor='$table_bgcolor_even'>");
                } else {
					echo("<tr bgcolor='$table_bgcolor_odd'>");
				} ?>
                
                <td><?php echo dataBr($ReceitaRubrica_final_listas->data); ?></td>                
                <td align="left">Receita</td>                				
				<td align="left"><?php echo $ReceitaRubrica_final_listas->descricao; ?></td>
                <td align="right"><?php echo moedaBr($ReceitaRubrica_final_listas->valor_receita); ?></td>
            <?php }			
			
            foreach($DespesaRubrica_listas as $DespesaRubrica_final_listas){
                $i = $i + 1;
                if ($i % 2) {
					echo("<tr bgcolor='$table_bgcolor_even'>");
				} else {
                    echo("<tr bgcolor='$table_bgcolor_odd'>");
				} ?>
                
                <td align="left"><?php echo dataBr($DespesaRubrica_final_listas->data_emissao);?></td>                
                <td align="left">Despesa</td>                								
				<td align="left"><?php echo $DespesaRubrica_final_listas->descricao;?></td>
                <td align="right"><?php echo moedaBr($DespesaRubrica_final_listas->valor_despesa);?></td>
                
			<?php } ?>
        </tbody> <!-- SUB LISTA -->
    </table>
    
	<hr />    
	<!-- LISTAGEM PRINCIPAL -->
    
	<?php } ?>
    
<?php } ?>


<?php

// FUNÇÕES : CONVERSÃO DE DATAS E VALORES

// Formata data aaaa-mm-dd para dd/mm/aaaa
function dataBr($dataSql) {
	if (!empty($dataSql)) {
		$p_dt = explode('-',$dataSql);
    	$data_brProjetopd = $p_dt[2].'/'.$p_dt[1].'/'.$p_dt[0];
		return $data_brProjetopd;
	}
}

// Formata o valor escrito no padrao xx.xxx,xx
function moedaBr($ValorBr) {
    $ValorBr = number_format($ValorBr, 2, ',','.');
	
    return $ValorBr;
}

// Identifica o id do registro selecionado pelo radio button
function identificarProjetopdID($idProjetopd){
    $database = JFactory::getDBO();
    $sql = "SELECT * FROM #__contproj_projetos WHERE id = $idProjetopd LIMIT 1";
    $database->setQuery( $sql );
    $projetopd = $database->loadObjectList();
    return ($projetopd[0]);
}
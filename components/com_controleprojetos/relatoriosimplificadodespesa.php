<?php
/***********************************************************************
*               UFAM - Universidade Ferderal do Amazonas
* 
*      Funcoes de controle do modulo despesa,desenvolvido em 10/02/2013
* adaptado do modulo professor, para update do site icomp sob orientacao
* do Prof Arilo Dias. 
* 
* Sitemas de informacao, Equipe Augusto, Denise e Diana
* usando padrao de desenvolvimento ágil
* 
************************************************************************/




// Listar registros
function listarRelatorioSimplificadoDespesas($descricaoRelatorioSimplificadoDespesa = ""){
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
    
    $idProjeto = JRequest::getVar('idProjeto');
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
	
	$resumoCapitalConcedido = 0;
	$resumoCapitalSaldo = 0;
	$resumoCapitalGasto = 0;
	$resumoCusteioConcedido = 0;
	$resumoCusteioSaldo = 0;
	$resumoCusteioGasto = 0;

	if(sizeof($resumo) == 1){
		if($resumo[0]->tipo == "Capital"){
			$resumoCapitalConcedido = $resumo[0]->concedido;
			$resumoCapitalSaldo = $resumo[0]->saldo;
			$resumoCapitalGasto = $resumo[0]->gasto;
		}else
		if($resumo[0]->tipo == "Custeio"){
			$resumoCusteioConcedido = $resumo[0]->concedido;
			$resumoCusteioSaldo = $resumo[0]->saldo;
			$resumoCusteioGasto = $resumo[0]->gasto;
		}
	}
	if(sizeof($resumo) > 1){
			$resumoCapitalConcedido = $resumo[0]->concedido;
			$resumoCapitalSaldo = $resumo[0]->saldo;
			$resumoCapitalGasto = $resumo[0]->gasto;

			$resumoCusteioConcedido = $resumo[1]->concedido;
			$resumoCusteioSaldo = $resumo[1]->saldo;
			$resumoCusteioGasto = $resumo[1]->gasto;
	}
	

    $sql = "SELECT TR.id, TR.projeto_id, rubrica_origem, rubrica_destino, O.descricao AS rubricaOrigem, D.descricao AS rubricaDestino, TR.valor, TR.data, TR.autorizacao
             FROM #__contproj_transferenciassaldorubricas AS TR 
             INNER JOIN #__contproj_rubricasdeprojetos AS O ON TR.rubrica_origem = O.id  
             INNER JOIN #__contproj_rubricasdeprojetos AS D ON TR.rubrica_destino = D.id    
             WHERE  TR.projeto_id = $idProjeto ORDER BY data"; //idprojeto->identifica o projeto                 
    
    $database->setQuery( $sql );
    $gerenciarTranferenciadeRubricas = $database->loadObjectList(); 
	
  
	?>

<!--Funcionalidades dos formularios (Excluir, Editar e Visualizar) --> 
<script language="JavaScript">
function excluir(form){
   var idSelecionado = 0;
   for(i = 0;i < form.idRubricadeprojetoSelec.length;i++)
        if(form.idRubricadeprojetoSelec[i].checked) idSelecionado = form.idRubricadeprojetoSelec[i].value;
   if(idSelecionado > 0){
               var resposta = window.confirm("Confirme a exclusão do item.");
               if(resposta){
                  form.task.value = 'excluirRubricadeprojeto';
                  form.idRubricadeprojeto.value = idSelecionado;
                  form.submit();
               }
   }
   else{
        alert('Selecione o item a ser excluído!')
   }
}

function editar(form){
   var idSelecionado = 0;
   for(i = 0;i < form.idRubricadeprojetoSelec.length;i++)
        if(form.idRubricadeprojetoSelec[i].checked) idSelecionado = form.idRubricadeprojetoSelec[i].value;
   if(idSelecionado > 0){
           form.task.value = 'editarRubricadeprojeto';
           form.idRubricadeprojeto.value = idSelecionado;
           form.submit();
   }
   else{
          alert('Selecione o item a ser editado!')
   }
}

function visualizar(form){
   var idSelecionado = 0;
   for(i = 0;i < form.idRubricadeprojetoSelec.length;i++)
        if(form.idRubricadeprojetoSelec[i].checked) idSelecionado = form.idRubricadeprojetoSelec[i].value;
   if(idSelecionado > 0){
               form.task.value = 'verRubricadeprojeto';//volta para visualizar projeto
               form.idRubricadeprojeto.value = idSelecionado;
               form.submit();
   }
   else{
           alert('Selecione o item a ser  visualizado.')
   }
}


function imprimir(ItemId, idProjetopd){
    window.open("index.php?option=com_controleprojetos&Itemid="+ItemId+"&task=relatorioSimplificadoDespesa&idProjetopd="+idProjetopd,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
}



function voltarNivelForm(form){
   form.task.value = 'verProjetopd';
   form.submit();
   return true;
}

</script>
<!-- ---------------------------- -->

	<link rel="stylesheet" href="components/com_controleprojetos/estilo.css" type="text/css" />
        <script type="text/javascript" src="components/com_controleprojetos/jquery.js"></script>
        <script type="text/javascript" src="components/com_controleprojetos/jquery.tablesorter.js"></script>
	<script type="text/javascript">
	$(function() {
		$("#tablesorter-imasters").tablesorter();
	});
	</script>
        
<!-- Formulario da Funcao gerenciarProjetospd -->
<!--Cuidado! a linha abaixo refere-se a postagem do formulario no modulo controle projetos -->
<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post" >
    <!-- Barra ferramentas -->
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
                <div class="icon" id="toolbar-print">
           		<a href="javascript:imprimir(<?php echo $Itemid;?>, <?php echo $projetopd->id;?>)">
           			<span class="icon-32-print"></span><?php echo JText::_( 'Imprimir' ); ?></a>
				</div>
  				<div class="icon" id="toolbar-back">
                        <a href="javascript:voltarNivelForm(document.form)">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
          </div>
      <!-- ------------------ -->
        <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-contact"><h2>Relatório Simplificado de Movimentações</h2></div>
    </div></div>
    
	<!-- Informações -->
	<?php exibirProjeto($projetopd); ?>
	<hr>		

  <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
	</font>
    <!-- ------------ -->
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
      <!-- Formulario -->
	<hr>
          <!-- Barra de Ferramentas CAPITAL -->
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
			<div class="cpanel2">
				<div class="icon" id="toolbar-apply">
           			<span class="icon-32-apply"></span><?php echo JText::_( '<b>Concedido (R$)</b><br>R$ '.moedaBr($resumoCapitalConcedido)); ?>
				</div>
                
                <div class="icon" id="toolbar-unblock">
           			
           			<span class="icon-32-unblock"></span><?php echo JText::_( '<b>Liberado (R$)</b><br>R$ '.moedaBr($resumoCapitalSaldo+$resumoCapitalGasto)); ?>
				</div>
                
                <div class="icon" id="toolbar-remove">
           			<span class="icon-32-remove"></span><?php echo JText::_( '<b>Gasto (R$)</b><br>R$ '.moedaBr($resumoCapitalGasto)); ?>
				</div>
                
  				<div class="icon" id="toolbar-purge">
           			<span class="icon-32-purge"></span><?php echo JText::_( '<b>Saldo (R$)</b><br>R$ '.moedaBr($resumoCapitalSaldo)); ?>
				</div>
			</div>
      <!-- ------------------ -->
        <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-module"><h2>Itens de Capital</h2></div>
    </div></div>
	<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
	<thead>
        <tr bgcolor="#002666">
        <th width="10%" align="center"><font color="#FFC000">Tipo</font></th>
		<th width="20%" align="center"><font color="#FFC000">Item de Dispêndio</font></th>
        <th width="44%" align="center"><font color="#FFC000">Descrição</font></th>
        <th width="12%" align="center"><font color="#FFC000">Receitas</font></th>
        <th width="12%" align="center"><font color="#FFC000">Despesas</font></th>
        <th width="12%" align="center"><font color="#FFC000">Saldo</font></th>
      </tr>
     </thead>
     <tbody>
	<?php

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";

	$i = 0;
	foreach( $rubricadeprojetosCapital as $gerenciarItemRubricadeprojetos ){
		$i = $i + 1;
		if ($i % 2){
		    echo("<tr bgcolor='$table_bgcolor_even'>");
		 }
		else{
		    echo("<tr bgcolor='$table_bgcolor_odd'>");
	  	 }
	?>
                <td style='text-align: left;'><?php echo $gerenciarItemRubricadeprojetos->tipo;?></td>                
				<td style='text-align: left;'><?php echo $gerenciarItemRubricadeprojetos->nome;?></td>                
                <td style='text-align: left;'><?php echo $gerenciarItemRubricadeprojetos->descricao;?></td>
                <td style='text-align: right;'><?php echo number_format($gerenciarItemRubricadeprojetos->valor_total, 2, ',','.');?></td>
                <td style='text-align: right;'><?php echo number_format($gerenciarItemRubricadeprojetos->valor_gasto, 2, ',','.');?></td>
                <td style='text-align: right;'><?php echo number_format($gerenciarItemRubricadeprojetos->valor_disponivel, 2, ',','.');?></td>
	</tr>
	<?php
	}
        ?>
     </tbody>
     </table>
	<hr>
        <!-- Barra de Ferramentas CUSTEIO -->
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
			<div class="cpanel2">
				<div class="icon" id="toolbar-apply">
           			<span class="icon-32-apply"></span><?php echo JText::_( '<b>Concedido (R$)</b><br>R$ '.moedaBr($resumoCusteioConcedido)); ?>
				</div>
                
                <div class="icon" id="toolbar-unblock">
           			
           			<span class="icon-32-unblock"></span><?php echo JText::_( '<b>Liberado (R$)</b><br>R$ '.moedaBr($resumoCusteioSaldo+$resumoCusteioGasto)); ?>
				</div>
                
                <div class="icon" id="toolbar-remove">
           			<span class="icon-32-remove"></span><?php echo JText::_( '<b>Gasto (R$)</b><br>R$ '.moedaBr($resumoCusteioGasto)); ?>
				</div>
                
  				<div class="icon" id="toolbar-purge">
           			<span class="icon-32-purge"></span><?php echo JText::_( '<b>Saldo (R$)</b><br>R$ '.moedaBr($resumoCusteioSaldo)); ?>
				</div>
			</div>
	
      <!-- ------------------ -->
        <div class="clr"></div>
	</div>
    <div class="pagetitle icon-48-massmail"><h2>Itens de Custeio</h2></div>
    </div></div>
	<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
	<thead>
        <tr bgcolor="#002666">
        <th width="10%" align="center"><font color="#FFC000">Tipo</font></th>
		<th width="20%" align="center"><font color="#FFC000">Item de Dispêndio</font></th>
        <th width="44%" align="center"><font color="#FFC000">Descrição</font></th>
        <th width="12%" align="center"><font color="#FFC000">Receitas</font></th>
        <th width="12%" align="center"><font color="#FFC000">Despesas</font></th>
        <th width="12%" align="center"><font color="#FFC000">Saldo</font></th>
      </tr>
     </thead>
     <tbody>
	<?php

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";

	$i = 0;
	foreach( $rubricadeprojetosCusteio as $gerenciarItemRubricadeprojetos ){
		$i = $i + 1;
		if ($i % 2){
		    echo("<tr bgcolor='$table_bgcolor_even'>");
		 }
		else{
		    echo("<tr bgcolor='$table_bgcolor_odd'>");
	  	 }
	?>
                <td style='text-align: left;'><?php echo $gerenciarItemRubricadeprojetos->tipo;?></td>                
				<td style='text-align: left;'><?php echo $gerenciarItemRubricadeprojetos->nome;?></td>                
                <td style='text-align: left;'><?php echo $gerenciarItemRubricadeprojetos->descricao;?></td>
                <td style='text-align: right;'><?php echo number_format($gerenciarItemRubricadeprojetos->valor_total, 2, ',','.');?></td>
                <td style='text-align: right;'><?php echo number_format($gerenciarItemRubricadeprojetos->valor_gasto, 2, ',','.');?></td>
                <td style='text-align: right;'><?php echo number_format($gerenciarItemRubricadeprojetos->valor_disponivel, 2, ',','.');?></td>
	</tr>
	<?php
	}
        ?>
     </tbody>
     </table>	
	<hr>
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
      <!-- ------------------ -->
        <div class="clr"></div>
	</div>
    <div class="pagetitle icon-48-clear"><h2>Transferências</h2></div>
    </div></div>
    <!-- Formulario -->
	<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters" class="tabela">
		<thead style="color:#FFC000; text-align:center;">
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
            $table_bgcolor_even="#e6e6e6";
            $table_bgcolor_odd="#FFFFFF";
            $i = 0;
				
            foreach( $gerenciarTranferenciadeRubricas as $transferencia ){
                $i = $i + 1;
                if ($i % 2) {
                    echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
                } else {
                    echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
                } ?>
                
                <td><?php echo dataBr($transferencia->data);?></td>
                <td><?php echo $transferencia->rubricaOrigem;?></td>
                <td><?php echo $transferencia->rubricaDestino;?></td>
                <td><?php echo moedaBr($transferencia->valor);?></td>
                <td><?php echo $transferencia->autorizacao;?></td>
           <?php } ?>
		</tbody>
	</table>	 
     <input name='task' type='hidden' value='gerenciarRubricadeprojetos'>
     <!--Abaixo a referencia da tela anterior-->
     <input name='idProjetopd' type='hidden' value='<?php echo $idProjeto;?>'>
      <!------------------------->
     <input name='idRubricadeprojetoSelec' type='hidden' value='0'>
     <input name='idRubricadeprojeto' type='hidden' value=''>

     </form>
<!-- ---------------------------- -->
 <?php
}
//////////////////////////////////////////


// Formata data aaaa-mm-dd para dd/mm/aaaa
function databrRelatorioSimplesDespesa($datasqlRelatorioSimplesDespesa) {
	if (!empty($datasqlRelatorioSimplesDespesa)){
	$p_dt = explode('-',$datasqlRelatorioSimplesDespesa);
    	$data_brRelatorioSimplesDespesa = $p_dt[2].'/'.$p_dt[1].'/'.$p_dt[0];
	return print $data_brRelatorioSimplesDespesa;
	}
}
//////////////////////////////////////////



function imprimirRelatorioSimplificado($gerenciarRubricadeprojetos, $projetopd){
    
    $relatorioSimplificadoDespesadeprojetos = "components/com_controleprojetos/forms/relatoriosimplificadodespesa_id_".$projetopd->id.".pdf";
    $arq = fopen($relatorioSimplificadoDespesadeprojetos, 'w') or die("CREATE ERROR");
    $pdf = new Cezpdf('a4','portrait');
    $pdf->selectFont('pdf-php/fonts/Helvetica.afm');
    $optionsText = array('justification'=>'center', 'spacing'=>2.0);
    $header = array('justification'=>'center', 'spacing'=>1.3);
    $optionsTable = array('fontSize'=>10, 'titleFontSize'=>12 ,
                          'xPos'=>'center', 'width'=>560, 'cols'=>array(
						  utf8_decode('Rubrica')=>array('width'=>120, 'justification'=>'left'),
                          utf8_decode('Descrição')=>array('width'=>200, 'justification'=>'left'),
                          utf8_decode('Receita')=>array('width'=>80, 'justification'=>'right'),
                          utf8_decode('Despesa')=>array('width'=>80, 'justification'=>'right'),
                          utf8_decode('Saldo')=>array('width'=>80, 'justification'=>'right')));
    $pdf->addJpegFromFile('components/com_controleprojetos/images/ufam.jpg', 490, 730, 75);
    $pdf->addJpegFromFile('components/com_controleprojetos/images/logo-brasil.jpg', 30, 730, 100);
    $pdf->addText(500,700,8,"<b>Data:</b> ".date("d/m/y"),0,0);
    $pdf->ezText('<b>PODER EXECUTIVO</b>',12,$header);
    $pdf->ezText (utf8_decode('<b>MINISTÉRIO DA EDUCAÇÃO</b>'),10,$header);
    $pdf->ezText(utf8_decode('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>'),10,$header);
    $pdf->ezText(utf8_decode('<b>INSTITUTO DE COMPUTAÇÃO - ICOMP</b>'),10,$header);
    $pdf->ezText('',11,$header);
    $pdf->ezText('',11,$header);
    //    Padrao papel A4 800 X 600; setLineStyle(espessura); line(margem_esquerda,Mov_ponto_esquerdo,margem_direita,Mov_ponto_direito)
    $pdf->setLineStyle(2);
    $pdf->line(10, 720, 580, 720);//LINHA 1
    $pdf->ezText(utf8_decode("<b>Relatório Simplificado de Despesas </b>"),14,$optionsText);
    $pdf->setLineStyle(1);
    $pdf->line(10, 673, 580, 673);//LINHA 3    
    $pdf->ezText(utf8_decode("<b>Dados do projeto</b>"),10,$optionsText);
    $pdf->ezText(''); //Para quebra de linha
   
//  ....................................................................),Tamanho fonte,?)
    $pdf->ezText("<b>Projeto: </b> ". utf8_decode($projetopd->nomeprojeto),10,0);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("<b>Coordenador: </b>". utf8_decode($projetopd->nomeProfessor),10,0);
    $pdf->addText(400,612,10,"<b>Ag. Fomento: </b>". utf8_decode($projetopd->sigla),0,0);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText(utf8_decode("<b>Data de Início: </b>"). utf8_decode(dataBr($projetopd->data_inicio)),10,0);
    $pdf->addText(400,590,10,"<b>Data Final: </b>". utf8_decode(dataBr($projetopd->data_fim)),0,0);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText(utf8_decode("<b>Orçamento:</b> R$"). utf8_decode(number_format($projetopd->orcamento, 2, ',','.')),10,0);
    $pdf->addText(400,568,10,"<b>Saldo: </b> R$". utf8_decode(number_format($projetopd->saldo, 2, ',','.')),0,0);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText(''); //Para quebra de linha
    $pdf->setLineStyle(1);
    $pdf->line(10, 560, 580, 560);//LINHA 3
    $pdf->ezText(utf8_decode("<b>Dados das Despesas</b>"),10,$optionsText);
    $pdf->ezText(''); //Para quebra de linha 
    foreach( $gerenciarRubricadeprojetos as $gerenciarItemRubricadeprojetos){
        $listaSimplificadaRubricadeprojetos[]=array(
										utf8_decode('Rubrica')=>utf8_decode($gerenciarItemRubricadeprojetos->nome),
                                        utf8_decode('Descrição')=>utf8_decode($gerenciarItemRubricadeprojetos->descricao),
                                        utf8_decode('Receita')=>"R$".utf8_decode(number_format($gerenciarItemRubricadeprojetos->valor_total, 2, ',','.')),
                                        utf8_decode('Despesa')=>"R$".utf8_decode(number_format($gerenciarItemRubricadeprojetos->valor_gasto, 2, ',','.')),
                                        utf8_decode('Saldo')=>"R$".utf8_decode(number_format($gerenciarItemRubricadeprojetos->valor_disponivel, 2, ',','.')));
                                        }
    $pdf->ezTable($listaSimplificadaRubricadeprojetos,$cols,'',$optionsTable);
    $pdf->line(20, 60, 580, 60);//LINHA
    $pdf->addText(80,40,8,utf8_decode('Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur Virgílio Filho - CEP 69077-000 -  Manaus, AM, Brasil'),0,0);
    $pdf->addJpegFromFile('components/com_controleprojetos/images/icon_telefone.jpg', 140, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_controleprojetos/images/icon_email.jpg', 229, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_controleprojetos/images/icon_casa.jpg', 383, 30, 8, 8);
    $pdf->addText(150,30,8,utf8_decode('Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br'),0,0);
    $pdfcode = $pdf->output();
    fwrite($arq,$pdfcode);
    fclose($arq);
    header("Location: ".$relatorioSimplificadoDespesadeprojetos);
}

//////////////////////////////////////////////////////////////
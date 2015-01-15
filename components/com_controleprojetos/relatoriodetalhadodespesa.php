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
function listarRelatorioDetalhadoDespesas($descricaoRelatorioDetalhadoDespesa = ""){
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
    $idProjeto = JRequest::getVar('idProjeto');
    $projetopd = identificarProjetopdID($idProjeto);
    $sql = "SELECT RP.id, RP.projeto_id, P.nomeprojeto, RP.rubrica_id, R.nome AS nomerubrica, RP.descricao, RP.valor_total, RP.valor_gasto, RP.valor_disponivel
            FROM #__contproj_rubricasdeprojetos AS RP 
            INNER JOIN #__contproj_rubricas AS R
                ON RP.rubrica_id = R.id  
            INNER JOIN #__contproj_projetos AS P
                ON RP.projeto_id = P.id 
           WHERE  descricao LIKE '%$descricaoRelatorioDetalhadoDespesa%' AND projeto_id = $idProjeto ORDER BY descricao";
    $database->setQuery( $sql );
    $gerenciarRubricadeprojetos = $database->loadObjectList();
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
    window.open("index.php?option=com_controleprojetos&Itemid="+ItemId+"&task=relatorioDetalhadoDespesa&idProjetopd="+idProjetopd,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
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
<!--				<div class="icon" id="toolbar-new">
           		<a href="javascript:document.form.task.value='addRubricadeprojeto';document.form.submit()">
           			<span class="icon-32-new"></span><?php // echo JText::_( 'Novo' ); ?></a>
				</div>
				<div class="icon" id="toolbar-edit">
           		<a href="javascript:editar(document.form)">
           			<span class="icon-32-edit"></span><?php // echo JText::_( 'Editar' ); ?></a>
				</div>
				<div class="icon" id="toolbar-preview">
           		<a href="javascript:visualizar(document.form)">
           			<span class="icon-32-preview"></span><?php // echo JText::_( 'Visualizar' ); ?></a>
				</div>
				<div class="icon" id="toolbar-delete">
           		<a href="javascript:excluir(document.form)">
           			<span class="icon-32-delete"></span><?php // echo JText::_( 'Excluir' ); ?></a>
				</div>-->
                                <div class="icon" id="toolbar-print">
           		<a href="javascript:imprimir(<?php echo $Itemid;?>, <?php echo $projetopd->id;?>)">
           			<span class="icon-32-print"></span><?php echo JText::_( 'Imprimir<br>Relatório' ); ?></a>
				</div>
  				<div class="icon" id="toolbar-back">
           		<!--<a href="index.php?option=com_controleprojetos&Itemid=<?php // echo $Itemid;?>">-->
                        <a href="javascript:voltarNivelForm(document.form)">
                            	<!--<a href="javascript:visualizarProjeto(document.form)">-->
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
          </div>
      <!-- ------------------ -->
        <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-contact"><h2>Relatório Detalhado de Despesas</h2></div>
    </div></div>

	<!-- Informações -->
	<?php exibirProjeto($projetopd); ?>
	<hr>		

   <!-- ////// /////////////////////////// //////-->    
     
  <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
</font>
<h3>Dados das Despesas</h3><hr>
    
<!--//////////  Listagem Principal  ///////////-->
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
      <?php 
      foreach( $gerenciarRubricadeprojetos as $gerenciarItemRubricadeprojetos ){
      ?> 
    <br>
      <!--<b>ID:</b> <?php // echo $gerenciarItemRubricadeprojetos->id;?><br>-->
      <!--<b>RUBRICA ID:</b> <?php // echo $gerenciarItemRubricadeprojetos->rubrica_id;?><br>-->
      <b>RUBRICA:</b> <?php echo $gerenciarItemRubricadeprojetos->nomerubrica;?><br>
      <ul><li><b>Descrição:</b> <?php echo $gerenciarItemRubricadeprojetos->descricao;?></li>
      <li><b>Valor Total:</b> R$ <?php echo number_format($gerenciarItemRubricadeprojetos->valor_total, 2, ',','.');?> | 
      <b>Valor Gasto:</b> R$ <?php echo number_format($gerenciarItemRubricadeprojetos->valor_gasto, 2, ',','.');?> | 
      <b>Valor Disponível:</b> R$ <?php echo number_format($gerenciarItemRubricadeprojetos->valor_disponivel, 2, ',','.');?></li></ul>
      <!--//////////  Sub lista  ///////////-->
      <table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
            <thead>
            <tr bgcolor="#002666">
                <th width="15%" align="center"><font color="#FFC000">Data</font></th>                
                <th width="10%" align="center"><font color="#FFC000">Tipo</font></th>                				
				<th width="60%" align="center"><font color="#FFC000">Descrição</font></th>
                <th width="15%" align="center"><font color="#FFC000">Valor</font></th>
            </tr>
            </thead>
        <tbody>
                  <?php //Acessa a tabela rubricas para exibir em list box
                  $database->setQuery("SELECT D.id, D.rubricasdeprojetos_id , D.data_emissao, D.descricao, D.valor_despesa
                                       FROM #__contproj_despesas AS D
                                       INNER JOIN #__contproj_rubricasdeprojetos AS RP
                                             ON D.rubricasdeprojetos_id = RP.id
                                       WHERE  rubricasdeprojetos_id LIKE '$gerenciarItemRubricadeprojetos->id' ORDER BY data_emissao");//Este  $idProjeto - está Exclusivo para identificar Lista rubrica por rubrica de projetos
	             $DespesaRubrica_listas = $database->loadObjectList();
				 
                  $database->setQuery("SELECT R.id, R.rubricasdeprojetos_id , R.data, R.descricao, R.valor_receita
                                       FROM #__contproj_receitas AS R
                                       INNER JOIN #__contproj_rubricasdeprojetos AS RP
                                             ON R.rubricasdeprojetos_id = RP.id
                                       WHERE  rubricasdeprojetos_id LIKE '$gerenciarItemRubricadeprojetos->id' ORDER BY data");//Este  $idProjeto - está Exclusivo para identificar Lista rubrica por rubrica de projetos
	             $ReceitaRubrica_listas = $database->loadObjectList();
				 
				 
            $table_bgcolor_even="#e6e6e6";
            $table_bgcolor_odd="#FFFFFF";
            $i = 0;
			foreach($ReceitaRubrica_listas as $ReceitaRubrica_final_listas){
                $i = $i + 1;
                if ($i % 2){
                        echo("<tr bgcolor='$table_bgcolor_even'>");
                    }
                    else{
                    echo("<tr bgcolor='$table_bgcolor_odd'>");
                    }
            ?>
                <td style='text-align: left;'><?php echo dataBr($ReceitaRubrica_final_listas->data);?></td>                
                <td style='text-align: left;'>Receita</td>                				
				<td style='text-align: left;'><?php echo $ReceitaRubrica_final_listas->descricao;?></td>
                <td style='text-align: right;'><?php echo number_format($ReceitaRubrica_final_listas->valor_receita, 2, ',','.');?></td>
            <?php
              }			
            foreach($DespesaRubrica_listas as $DespesaRubrica_final_listas){
                $i = $i + 1;
                if ($i % 2){
                        echo("<tr bgcolor='$table_bgcolor_even'>");
                    }
                    else{
                    echo("<tr bgcolor='$table_bgcolor_odd'>");
                    }
            ?>
                <td style='text-align: left;'><?php echo dataBr($DespesaRubrica_final_listas->data_emissao);?></td>                
                <td style='text-align: left;'>Despesa</td>                								
				<td style='text-align: left;'><?php echo $DespesaRubrica_final_listas->descricao;?></td>
                <td style='text-align: right;'><?php echo number_format($DespesaRubrica_final_listas->valor_despesa, 2, ',','.');?></td>
            <?php
              }
            ?>
        </tbody>
        <!--//////////  Final Sub lista  ///////////-->
      </table>
      <hr>
 <!--//////////  Listagem Principal  ///////////-->
       <?php } ?>

     <input name='task' type='hidden' value='gerenciarRubricadeprojetos'>
     <input name='idProjetopd' type='hidden' value='<?php echo $idProjeto;?>'>
     <input name='idProjeto' type='hidden' value='<?php echo $idProjeto;?>'><!--referencia o ID do PROJETO para edicao--> 
     <input name='idRubricadeprojetoSelec' type='hidden' value='0'>
     <input name='idRubricadeprojeto' type='hidden' value=''>
 
 


     </form>
<!-- ---------------------------- -->
 <?php
}
//////////////////////////////////////////


// Formata data aaaa-mm-dd para dd/mm/aaaa
function databrRelatorioDetalhadoDespesa($datasqlRelatorioDetalhadoDespesa) {
	if (!empty($datasqlRelatorioDetalhadoDespesa)){
	$p_dt = explode('-',$datasqlRelatorioDetalhadoDespesa);
    	$data_brRelatorioDetalhadoDespesa = $p_dt[2].'/'.$p_dt[1].'/'.$p_dt[0];
	return print $data_brRelatorioDetalhadoDespesa;
	}
}
//////////////////////////////////////////




function imprimirRelatorioDetalhado($gerenciarRubricadeprojetos,$gerenciarDespesasRubricas, $projetopd){
    
    $database	= JFactory::getDBO();
	$relatorioDetalhadoDespesadeprojetos = "components/com_controleprojetos/forms/detalhadodespesa_id_".$projetopd->id.".pdf";
    $arq = fopen($relatorioDetalhadoDespesadeprojetos, 'w') or die("CREATE ERROR");
    $pdf = new Cezpdf('a4','portrait');
    $pdf->selectFont('pdf-php/fonts/Helvetica.afm');
    $optionsText = array('justification'=>'center', 'spacing'=>2.0);
    $header = array('justification'=>'center', 'spacing'=>1.3);
    $optionsTable = array('fontSize'=>10, 'titleFontSize'=>12 ,
                          'xPos'=>'center', 'width'=>560, 'cols'=>array(
                          utf8_decode('Data')=>array('width'=>70, 'justification'=>'center'),
                          utf8_decode('Tipo')=>array('width'=>50, 'justification'=>'center'),
                          utf8_decode('Descrição')=>array('width'=>300, 'justification'=>'center'),
                          utf8_decode('Valor')=>array('width'=>70, 'justification'=>'center')));
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
    $pdf->ezText(utf8_decode("<b>Relatório Detalhado de Despesas </b>"),14,$optionsText);
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
    $pdf->setLineStyle(1);
    $pdf->line(10, 560, 580, 560);//LINHA 3

    $pdf->ezText(utf8_decode("<b>Dados das Despesas</b>"),10,$optionsText);
    $pdf->ezText(''); //Para quebra de linha 
        
	
    foreach( $gerenciarRubricadeprojetos as $gerenciarItemRubricadeprojetos){
    
		$pdf->ezText("<b>- RUBRICA: </b> ". utf8_decode($gerenciarItemRubricadeprojetos->nome),10,0);
		$pdf->ezText('');  //Para quebra de linha
		$pdf->ezText(utf8_decode("<b>Descrição: </b>"). utf8_decode($gerenciarItemRubricadeprojetos->descricao),10,0);
		$pdf->ezText("<b>Valor Total: </b> R$ ". utf8_decode(number_format($gerenciarItemRubricadeprojetos->valor_total, 2, ',','.')),10,0);
		$pdf->ezText("<b>Valor Gasto: </b> R$ ". utf8_decode(number_format($gerenciarItemRubricadeprojetos->valor_gasto, 2, ',','.')),10,0);
		$pdf->ezText(utf8_decode("<b>Valor Disponível: </b> R$ "). utf8_decode(number_format($gerenciarItemRubricadeprojetos->valor_disponivel, 2, ',','.')),10,0);
		$pdf->ezText(''); //Para quebra de linha     
		$pdf->ezText(''); //Para quebra de linha 	
		
		$database->setQuery("SELECT D.id, D.rubricasdeprojetos_id , D.data_emissao, D.descricao, D.valor_despesa
            FROM #__contproj_despesas AS D
            INNER JOIN #__contproj_rubricasdeprojetos AS RP
            ON D.rubricasdeprojetos_id = RP.id
            WHERE  rubricasdeprojetos_id LIKE '$gerenciarItemRubricadeprojetos->id' ORDER BY data_emissao");//Este  $idProjeto - está Exclusivo para identificar Lista rubrica por rubrica de projetos
	    $DespesaRubrica_listas = $database->loadObjectList();
				 
        $database->setQuery("SELECT R.id, R.rubricasdeprojetos_id , R.data, R.descricao, R.valor_receita
            FROM #__contproj_receitas AS R
            INNER JOIN #__contproj_rubricasdeprojetos AS RP
            ON R.rubricasdeprojetos_id = RP.id
            WHERE  rubricasdeprojetos_id LIKE '$gerenciarItemRubricadeprojetos->id' ORDER BY data");//Este  $idProjeto - está Exclusivo para identificar Lista rubrica por rubrica de projetos
	    $ReceitaRubrica_listas = $database->loadObjectList();
		
		$listaDetalhadaRubricadeprojetos = null;

	    foreach($ReceitaRubrica_listas as $ReceitaRubrica_final_listas){
			$listaDetalhadaRubricadeprojetos[]=array(utf8_decode('Data')=>utf8_decode(dataBr($ReceitaRubrica_final_listas->data)),
                                        utf8_decode('Tipo')=>utf8_decode("Receita"),
                                        utf8_decode('Descrição')=>utf8_decode($ReceitaRubrica_final_listas->descricao),
                                        utf8_decode('Valor')=>utf8_decode(number_format($ReceitaRubrica_final_listas->valor_receita, 2, ',','.')));
        }
		foreach($DespesaRubrica_listas as $DespesaRubrica_final_listas){		
			$listaDetalhadaRubricadeprojetos[]=array(utf8_decode('Data')=>utf8_decode(dataBr($DespesaRubrica_final_listas->data_emissao)),
                                        utf8_decode('Tipo')=>utf8_decode("Despesa"),
                                        utf8_decode('Descrição')=>utf8_decode($DespesaRubrica_final_listas->descricao),
                                        utf8_decode('Valor')=>utf8_decode(number_format($DespesaRubrica_final_listas->valor_despesa, 2, ',','.')));
		
		}
		$pdf->ezTable($listaDetalhadaRubricadeprojetos,$cols,'',$optionsTable);
    
		$pdf->ezText(''); //Para quebra de linha
		$pdf->ezText('______________________________________________________________________________________________'); //Para quebra de linha
		$pdf->ezText(''); //Para quebra de linha		
    }
    
    
    $pdf->line(20, 60, 580, 60);//LINHA
    $pdf->addText(80,40,8,utf8_decode('Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur Virgílio Filho - CEP 69077-000 -  Manaus, AM, Brasil'),0,0);
    $pdf->addJpegFromFile('components/com_controleprojetos/images/icon_telefone.jpg', 140, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_controleprojetos/images/icon_email.jpg', 229, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_controleprojetos/images/icon_casa.jpg', 383, 30, 8, 8);
    $pdf->addText(150,30,8,utf8_decode('Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br'),0,0);
    $pdfcode = $pdf->output();
    fwrite($arq,$pdfcode);
    fclose($arq);
    header("Location: ".$relatorioDetalhadoDespesadeprojetos);
}

//////////////////////////////////////////////////////////////
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


// consultar registros por filtro
function consultaSaldoRubricas($buscaNomeRubrica, $buscaValor = ""){
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
	?>

	<link rel="stylesheet" href="components/com_controleprojetos/estilo.css" type="text/css" />
    <script type="text/javascript" src="components/com_controleprojetos/jquery.js"></script>
    <script type="text/javascript" src="components/com_controleprojetos/jquery.tablesorter.js"></script>
	<script type="text/javascript">
	$(function() {
		$("#tablesorter-imasters").tablesorter();

	});
	</script>
        
	<script src="components/com_controleprojetos/jquery.maskMoney.js" type="text/javascript"></script>
	<script type="text/javascript">
    $(function(){
        $("#buscaValor").maskMoney({thousands:'.', decimal:',', symbolStay: true});
    })
    </script>
	
<!-- Formulario da Funcao gerenciarProjetospd -->
<!--Cuidado! a linha abaixo refere-se a postagem do formulario no modulo controle projetos -->
<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post" >
    <!-- Barra ferramentas -->
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
  				<div class="icon" id="toolbar-back">
           		<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
          </div>
      <!-- ------------------ -->
        <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-contact"><h2>Consultar Saldo por Rubricas</h2></div>
    </div></div>
    <!-- Filtros de busca -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
            <tr>
                <!-----Filtro por Rubrica------------>
                <td>Rubrica: </td>
                <td colspan="3">  <select id="buscaNomeRubrica" name="buscaNomeRubrica" class="inputbox"> 	
                      <?php $database->setQuery("SELECT id, nome, codigo from #__contproj_rubricas ORDER BY nome");
                        $listaRubricas = $database->loadObjectList();
                        foreach($listaRubricas as $listaItemRubricas){?>
                             <option value="<?php echo $listaItemRubricas->id;?>"
                                            <?php if($buscaNomeRubrica == $listaItemRubricas->id) echo 'SELECTED';?>>
                                            <?php echo "[".$listaItemRubricas->codigo."] ".$listaItemRubricas->nome;?>
                             </option> <?php } ?>
                      </select>
                </td>
                <!-----Filtro por Valor------------>
                <td>Valor (R$): </td>            
                <td><input id="buscaValor" name="buscaValor" size="10" maxlength="10" type="text" value="" /></td>
                <td><input type="submit" value="Buscar" ></td>
            </tr>
    </tbody>
    </table>
    <!-- ------------ -->
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    <input name='task' type='hidden' value='gerenciarConsultasaldorubricas'>

    <hr>

     
<!-- ---------------------------- -->
 <?php
}
//////////////////////////////////////////


// Listar registros
function listarConsultasaldorubricas($buscaNomeRubrica, $buscaValor = ""){
    $database	= JFactory::getDBO();
	$valor = str_replace(".","",$buscaValor);
	$valor = str_replace(",",".",$valor);
	$valor = (real) $valor;
    $Itemid = JRequest::getInt('Itemid', 0);
	
	if($buscaNomeRubrica){
    $sql= "SELECT RP.id, P.id as idProjeto, P.status, A.sigla, P.nomeprojeto, P.data_fim, RP.valor_total, RP.valor_disponivel, PR.nomeProfessor
           FROM #__contproj_rubricasdeprojetos AS RP
           INNER JOIN #__contproj_projetos AS P
              ON RP.projeto_id = P.id
           INNER JOIN #__contproj_agencias AS A
              ON A.id = P.agencia_id 
           INNER JOIN #__professores AS PR
              ON PR.id = P.coordenador_id
           WHERE RP.rubrica_id = $buscaNomeRubrica AND valor_disponivel >= $valor ORDER BY data_fim";
    $database->setQuery( $sql );
	
    $gerenciarConsultasaldorubricas = $database->loadObjectList();
	}
	?>

<!--Funcionalidades dos formularios (Excluir, Editar e Visualizar) --> 
<script language="JavaScript">

function visualizar(form){
   var idSelecionado = 0;
   for(i = 0;i < form.idProjetopdSelec.length;i++)
        if(form.idProjetopdSelec[i].checked) idSelecionado = form.idProjetopdSelec[i].value;
   if(idSelecionado > 0){
               form.task.value = 'gerenciarRubricadeprojetos';
               form.idProjeto.value = idSelecionado;
               form.submit();
   }
   else{
           alert('Selecione o item a ser  visualizado.')
   }
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

	<script src="components/com_controleprojetos/jquery.maskMoney.js" type="text/javascript"></script>
	<script type="text/javascript">
    $(function(){
        $("#buscaValor").maskMoney({thousands:'.', decimal:',', symbolStay: true});
    })
    </script>
	
<!-- Formulario da Funcao gerenciarProjetospd -->
<!--Cuidado! a linha abaixo refere-se a postagem do formulario no modulo controle projetos -->
<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post" >
    <!-- Barra ferramentas -->
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<?php if($buscaNomeRubrica){ ?>
				<div class="icon" id="toolbar-preview">
           		<a href="javascript:visualizar(document.form)">
           			<span class="icon-32-preview"></span><?php echo JText::_( 'Ver Projeto' ); ?></a>
				</div>
				<?php } ?>
  				<div class="icon" id="toolbar-back">
           		<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
          </div>
      <!-- ------------------ -->
        <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-contact"><h2>Consultar Saldo por Rubricas</h2></div>
    </div></div>
    <!-- Filtros de busca -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
            <tr>
<!-----Filtro por Rubrica------------>
                <td>Rubrica: </td>
                <td colspan="3">  <select id="buscaNomeRubrica" name="buscaNomeRubrica" class="inputbox"> 
                      <?php $database->setQuery("SELECT id, nome, codigo from #__contproj_rubricas ORDER BY nome");
                        $listaRubricas = $database->loadObjectList();
                        foreach($listaRubricas as $listaItemRubricas){?>
                             <option value="<?php echo $listaItemRubricas->id;?>"
                                            <?php if($buscaNomeRubrica == $listaItemRubricas->id) echo 'SELECTED';?>>
                                            <?php echo "[".$listaItemRubricas->codigo."] ".$listaItemRubricas->nome;?>
                             </option> <?php } ?>
                      </select>
                </td>
                <!-----Filtro por Valor------------>
                <td>Valor (R$): </td>            
                <td><input id="buscaValor" name="buscaValor" size="10" maxlength="10" type="text" value="<?php echo $buscaValor;?>" /></td>
                <td><input type="submit" value="Buscar" ></td>
			</tr>
		</tbody>
    </table>
    <!-- ------------ -->
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
      <!-- Formulario -->
	<?php if($buscaNomeRubrica){ ?>    
	<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
	<thead>
      <tr bgcolor="#002666">
        <th width="2%" align="center"><font color="#FFC000"></font></th>
   		<th width="5%" align="center"><font color="#FFC000">Status</font></th>
		<th width="11%" align="center"><font color="#FFC000">Término</font></th>
        <th width="30%" align="center"><font color="#FFC000">Projeto</font></th>        
        <th width="10%" align="center"><font color="#FFC000">Agência</font></th>		
        <th width="25%" align="center"><font color="#FFC000">Coordenador</font></th>
        <th width="17%" align="center"><font color="#FFC000">Saldo Rubrica</font></th>
      </tr>
     </thead>
     <tbody>
	<?php

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";
    $imagemStatus = array ('Cadastrado'=>"projetocadastrado.png", 'Ativo'=>"projetoiniciado.png", 'Prorrogado'=>"projetoprorrogado.png", 'Encerrado'=>"projetoencerrado.png");
    $mouseoverStatus = array ('Cadastrado'=>"Projeto Cadastrado", 'Ativo'=>"Projeto Ativo", 'Prorrogado'=>"Projeto Prorrogado", 'Encerrado'=>"Projeto Encerrado");
	
	$total = 0;
        $i = 0;
	foreach( $gerenciarConsultasaldorubricas as $gerenciarItemConsultasaldorubricas ){
		$i = $i + 1;
		if ($i % 2){
		    echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
		 }
		else{
		    echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
	  	 }
		 $total += $gerenciarItemConsultasaldorubricas->valor_disponivel;
	?>
		<td width='16'><input type="radio" name="idProjetopdSelec" value="<?php echo $gerenciarItemConsultasaldorubricas->idProjeto;?>"></td>
        <td><img border='0' src='components/com_controleprojetos/images/<?php echo $imagemStatus[$gerenciarItemConsultasaldorubricas->status];?>' width='16' height='16' title='<?php echo $mouseoverStatus[$gerenciarItemConsultasaldorubricas->status];?>'></td>
        <td><?php databrConsultasaldorubrica($gerenciarItemConsultasaldorubricas->data_fim);?></td>
        <td><?php echo $gerenciarItemConsultasaldorubricas->nomeprojeto;?></td>
        <td><?php echo $gerenciarItemConsultasaldorubricas->sigla;?></td>
        <td><?php echo $gerenciarItemConsultasaldorubricas->nomeProfessor;?></td>
        <td>R$ <?php echo number_format($gerenciarItemConsultasaldorubricas->valor_disponivel, 2, ',','.');?></td>
	<?php
	}
        ?>
     </tbody>
     </table>
     <br>Foi(foram) encontrado(s) <b><?php echo sizeof($gerenciarConsultasaldorubricas);?></b> projetos(s).
	 <br>O saldo total disponível para esta rubrica é de <b>R$ <?php echo number_format($total,2, ',','.');?></b>.
	<?php } ?>	 
     <input name='task' type='hidden' value='gerenciarConsultasaldorubricas'>
     <input name='idProjetopdSelec' type='hidden' value='0'>
     <input name='idProjeto' type='hidden' value=''>
     

     </form>
<!-- ---------------------------- -->
 <?php
}

// Formata data aaaa-mm-dd para dd/mm/aaaa
function databrConsultasaldorubrica($datasqlConsultasaldorubrica) {
	if (!empty($datasqlConsultasaldorubrica)){
	$p_dt = explode('-',$datasqlConsultasaldorubrica);
    	$data_brConsultasaldorubrica = $p_dt[2].'/'.$p_dt[1].'/'.$p_dt[0];
	return print $data_brConsultasaldorubrica;
	}
}
//////////////////////////////////////////

 
// Formata data dd/mm/aaaa para aaaa-mm-dd
function datasqlConsultasaldorubrica($databrConsultasaldorubrica) {
	if (!empty($databrConsultasaldorubrica)){
	$p_dt = explode('/',$databrConsultasaldorubrica);
	$data_sqlConsultasaldorubrica = $p_dt[2].'-'.$p_dt[1].'-'.$p_dt[0];
	return  $data_sqlConsultasaldorubrica;
	}
}
//////////////////////////////////////////


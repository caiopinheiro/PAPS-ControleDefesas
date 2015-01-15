<?php
/***********************************************************************
*               UFAM - Universidade Ferderal do Amazonas
* 
*      Funcoes de controle do modulo Receita,desenvolvido em 10/02/2013
* adaptado do modulo professor, para update do site icomp sob orientacao
* do Prof Arilo Dias. 
* 
* Sitemas de informacao, Equipe Augusto, Denise e Diana
* usando padrao de desenvolvimento ágil
* 
************************************************************************/

// LISTAGEM DE RECEITAS
function listarReceitas() {
    $database = JFactory::getDBO();
	
    $Itemid = JRequest::getInt('Itemid', 0);
    $idProjeto = JRequest::getVar('idProjeto');
    $projetopd = identificarProjetopdID($idProjeto);
	
	$receitasCapital = getReceitaTipo($idProjeto, "Capital");
	$receitasCusteio = getReceitaTipo($idProjeto, "Custeio");
	
	$sql2 = "SELECT R.tipo, SUM(valor_total) AS concedido, SUM(valor_gasto) AS gasto, SUM(valor_disponivel) AS saldo 
			FROM #__contproj_rubricasdeprojetos AS RP
			JOIN #__contproj_rubricas AS R ON R.id = RP.rubrica_id
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
			
	?>

    <!--Funcionalidades dos formularios (Excluir, Editar e Visualizar) --> 
    <script language="JavaScript">
		function excluir(form) {
			var idSelecionado = 0;
			
	        for(i = 0;i < form.idReceitaSelec.length;i++)
            	if(form.idReceitaSelec[i].checked) 
					idSelecionado = form.idReceitaSelec[i].value;
			
       		if(idSelecionado > 0) {
	          	var resposta = window.confirm("Confirme a exclusão do item.");
                if(resposta) {
                	form.task.value = 'excluirReceita';
                    form.idReceita.value = idSelecionado;
                    form.submit();
                }				
			} else {
				alert('Selecione o item a ser excluído!')
			}
		}
    
		function editar(form) {
			var idSelecionado = 0;

			for(i = 0;i < form.idReceitaSelec.length;i++)
	
			if(form.idReceitaSelec[i].checked) 
				idSelecionado = form.idReceitaSelec[i].value;
				
			if(idSelecionado > 0) {
				form.task.value = 'editarReceita';
				form.idReceita.value = idSelecionado;
				form.submit();
			} else {
				alert('Selecione o item a ser editado!')
			}
		}
    
		function voltarNivelForm(form) {
			form.task.value = 'verProjetopd';
			form.submit();
			return true;
		}
    </script>
    
	<script type="text/javascript" src="components/com_controleprojetos/jquery.js"></script>
    <script type="text/javascript" src="components/com_controleprojetos/jquery.tablesorter.js"></script>
	<script type="text/javascript">
	$(function() {
		$("#tablesorter-imasters").tablesorter();
	});
	</script>

	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/estilo.css" />
	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css" />

    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>">
    
        <!-- Barra ferramentas -->
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
			<div class="cpanel2">
                <div class="icon" id="toolbar-new">
                	<a href="javascript:document.form.task.value='addReceita';document.form.submit()">
                    <span class="icon-32-new"></span><?php echo JText::_( 'Novo' ); ?></a>
                </div>
                
                <div class="icon" id="toolbar-delete">
                	<a href="javascript:excluir(document.form)">
                    <span class="icon-32-delete"></span><?php echo JText::_( 'Excluir' ); ?></a>
                </div>
                
                <div class="icon" id="toolbar-back">
                	<!--<a href="index.php?option=com_controleprojetos&Itemid=<?php // echo $Itemid;?>">-->
                    <a href="javascript:voltarNivelForm(document.form)">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
			</div>
            <div class="clr"></div>
        </div>
       	<div class="pagetitle icon-48-contact"><h2>Receitas</h2></div>
        </div></div>
        
    	<?php exibirProjeto($projetopd); ?> 

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
			</div>
            
            <div class="clr"></div>
            </div>
            <div class="pagetitle icon-48-module"><h2>Itens de Capital</h2></div>
	    </div></div>

      	<!-- Formulario -->
		<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
            <thead style="color:#FFC000; text-align:center;">
                <tr>
                    <th width="3%"></th>
                    <th width="10%">Tipo</th>
					<th width="30%">Item de Dispêndio</th>
                    <th width="30%">Descrição</th>
                    <th width="10%">Data</th>
                    <th width="6%">Valor Receita</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                        $table_bgcolor_even="#e6e6e6";
                        $table_bgcolor_odd="#FFFFFF";
                        $i = 0;
                        foreach ($receitasCapital as $receitas){
                            $i = $i + 1;
                            if ($i % 2) {
                                echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
                            } else {
                                echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
                            } ?>            
                            
                            <td width='16'>
                            	<?php if ($receitas->descricao == 'Transferência de Saldo') {} else { ?>
	                            	<input type="radio" name="idReceitaSelec" value="<?php echo $receitas->id; ?>" />
                                <?php } ?>
                            </td>
							<td><?php echo $receitas->tipo; ?></td>							
                            <td><?php echo $receitas->codigo.' : '.$receitas->nome; ?></td>
                            <td><?php echo $receitas->descricao; ?></td>
                            <td><?php echo dataBr($receitas->data); ?></td>
                            <td align="right"><?php echo moedaBr($receitas->valor_receita); ?></td>
                    <?php } ?>
                </tr>        
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
			</div>
            
            <div class="clr"></div>
            </div>
            <div class="pagetitle icon-48-massmail"><h2>Itens de Custeio</h2></div>
	    </div></div>

      	<!-- Formulario -->
		<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
            <thead style="color:#FFC000; text-align:center;">
                <tr>
                    <th width="3%"></th>
                    <th width="10%">Tipo</th>
					<th width="30%">Item de Dispêndio</th>
                    <th width="30%">Descrição</th>
                    <th width="10%">Data</th>
                    <th width="6%">Valor Receita</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                        $table_bgcolor_even="#e6e6e6";
                        $table_bgcolor_odd="#FFFFFF";
                        $i = 0;
                        foreach ($receitasCusteio as $receitas){
                            $i = $i + 1;
                            if ($i % 2) {
                                echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
                            } else {
                                echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
                            } ?>            
                            
                            <td width='16'>
                            	<?php if ($receitas->descricao == 'Transferência de Saldo') {} else { ?>
	                            	<input type="radio" name="idReceitaSelec" value="<?php echo $receitas->id; ?>" />
                                <?php } ?>
                            </td>
							<td><?php echo $receitas->tipo; ?></td>							
                            <td><?php echo $receitas->codigo.' : '.$receitas->nome; ?></td>
                            <td><?php echo $receitas->descricao; ?></td>
                            <td><?php echo dataBr($receitas->data); ?></td>
                            <td align="right"><?php echo moedaBr($receitas->valor_receita); ?></td>
                    <?php } ?>
                </tr>        
            </tbody>
		</table>        

		<br />
        <span>Total de Receitas: <b><?php echo sizeof($receitasCapital);?></b></span>
        <input name='task' type='hidden' value='gerenciarReceitas' />
        <input name='idProjetopd' type='hidden' value='<?php echo $idProjeto;?>' />
        <input name='idProjeto' type='hidden' value='<?php echo $idProjeto;?>' />
        <input name='idReceitaSelec' type='hidden' value='0' />
        <input name='idReceita' type='hidden' value='' />

     </form>
     
<?php }


// FORMULÁRIO DE CADASTRO DE RECEITA
function addReceita($Receita = NULL, $idProjeto){
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); ?>

    <!--Valida dados no formulario-->
    <script language="JavaScript">
		function IsEmpty(aTextField) {
		   if ((aTextField.value.length==0) || (aTextField.value==null) ) {
			  return true;
			} else { 
		   		return false; 
			}
		}
    
		function ValidateformCadastro(formCadastro){
			if(IsEmpty(formCadastro.descricao)) {
				alert('O campo descrição deve ser preenchido.')
				formCadastro.descricao.focus();
				return false;
			}
		   
			if(IsEmpty(formCadastro.valor_receita)) {
				alert('O campo valor receita deve ser preenchido.')
				formCadastro.valor_receita.focus();
				return false;
			}
		   
			if(IsEmpty(formCadastro.data)) {
				alert('O campo data deve ser preenchido.')
				formCadastro.data.focus();
				return false;
			}   
		   
			return true;
		}
    
		function voltarForm(form) {
			form.task.value = 'receitas';
			form.submit();
			return true;
		}
    </script>

    <!--Recurso de data interativa  (os componentes Jquery, a funcao e a linha de comando para chamada da funcao)-->    
    <link type="text/css" href="components/com_controleprojetos/jquery-ui-1.10.1.custom/css/ui-lightness/jquery-ui-1.10.1.custom.css" rel="Stylesheet" />
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>  
    <script>
        $(function() { //Mudanca para PT(BR)
            $.datepicker.regional['pt-BR'] = {
                    closeText: 'Fechar',
                    prevText: '&#x3c;Anterior',
                    nextText: 'Pr&oacute;ximo&#x3e;',
                    currentText: 'Hoje',
                    monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho',
                    'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
                    'Jul','Ago','Set','Out','Nov','Dez'],
                    dayNames: ['Domingo','Segunda-feira','Ter&ccedil;a-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
                    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
                    dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
                    weekHeader: 'Sm',
                    dateFormat: 'dd/mm/yy',
                    firstDay: 0,
                    isRTL: false,
                    showMonthAfterYear: false,
                    yearSuffix: ''};
            $.datepicker.setDefaults($.datepicker.regional['pt-BR']);
            $( "#data" ).datepicker({dateFormat: 'dd/mm/yy'});
    
        });
    </script>

    <script src="components/com_controleprojetos/jquery.maskMoney.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function(){
            $("#valor_receita").maskMoney({thousands:'.', decimal:',', symbolStay: true});
        })
    </script>

    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    
    <script type="text/javascript" src="components/com_controleprojetos/scripts.js"></script>
    
   <form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateformCadastro(this)">
   
    	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
			<div class="cpanel2">
				<div class="icon" id="toolbar-save">
                    <!-- A opcao abaixo usa o recurso de validacao do formulario-->
                    <a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
                    <!--A opcao abaixo acessa o formulario de forma direta-->
                    <!--<a href="javascript:document.formCadastro.task.value='salvarReceita';document.formCadastro.submit()">-->
           			<span class="icon-32-save"></span>Salvar</a>
				</div>
                
                <div class="icon" id="toolbar-cancel">
           			<a href="javascript:voltarForm(document.formCadastro)">
           			<span class="icon-32-cancel"></span>Cancelar</a>
		    	</div>
			</div>
            <div class="clr"></div>
		    </div>
            <div class="pagetitle icon-48-cpanel"><h2>Cadastro de Receitas</h2></div>
            </div>
        </div>
        
		<b>Como proceder: </b>
		<ul><li>Preencha os dados da receitas <font color="#FF0000">(* Campos Obrigatórios)</font>.</li></ul>
        
		<hr />
        
		<table width="100%">
        	<tr style="background-color: #7196d8;">
                <td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
            </tr>
            <tr>
                <input name='id' type='hidden' value="NULL"><!--referencia o ID visualizacao-->
                <input name='projeto_id' type='hidden' value='<?php echo $idProjeto;?>'><!--referencia o ID do PROJETO para novo-->
            </tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Rubrica:</td>
                <td colspan="2">
                  <select name="rubricasdeprojetos_id" class="inputbox">
                    <option value=""></option>
                    <?php 
						$database->setQuery("SELECT RP.id AS idRubricaProjeto, RP.projeto_id, P.nomeprojeto, RP.rubrica_id,
                                     RP.valor_total, RP.valor_gasto, RP.valor_disponivel, R.codigo, R.nome
                                     FROM j17_contproj_rubricasdeprojetos AS RP
                                     INNER JOIN j17_contproj_projetos AS P
                                        ON RP.projeto_id = P.id
                                     INNER JOIN j17_contproj_rubricas AS R
                                    ON RP.rubrica_id = R.id
                                     WHERE RP.projeto_id LIKE  '$idProjeto'");
                         $rubrica_listas = $database->loadObjectList();
						 
                         foreach($rubrica_listas as $listaRubricas) { ?> 
                         	<option value="<?php echo $listaRubricas->idRubricaProjeto;?>"><?php echo $listaRubricas->codigo;?> : <?php echo $listaRubricas->nome;?> Vl Previsto: <?php moedaBr($listaRubricas->valor_total);?></option>
					<?php } ?>
                  </select>
                 </td>
            </tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Descrição:</td>
                <td><input type="text" maxlength="80" size="80" name="descricao" class="inputbox" /></td>
            </tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Valor Receita:</td>
                <td><input type="text" maxlength="15" size="15" id="valor_receita" name="valor_receita" class="inputbox" onKeyPress="return(MascaraMoeda(this,'.',',',event))" /></td>
            </tr> 
            <tr>
                <td class="tbItemForm"><span>*</span> Data:</td>
                <td><input type="text" size="15" id="data" name="data" class="inputbox" /></td>
            </tr>
		</table>
       
		<input name='idReceita' type='hidden' value='<?php if($Receita) echo $Receita->id;?>'>
        <input name='idProjeto' type='hidden' value='<?php echo $idProjeto;?>'>    
        <input name='task' type='hidden' value='salvarReceita'>
        
    </form>
    
<?php }


// SALVAR RECEITA
function salvarReceita($idReceita = ""){
	$database = JFactory::getDBO();
	$rubricasdeprojetos_id = JRequest::getVar('rubricasdeprojetos_id');
	$descricao = JRequest::getVar('descricao');
	$valor_receita = moedaSql(JRequest::getVar('valor_receita'));
	$data = dataSql(JRequest::getVar('data'));
	$idProjeto = JRequest::getVar('idProjeto');
	
	//----- atributos para cadastro de rubrica (maior ou igual ao valor do projeto)            
	$sql2 = "SELECT SUM(valor_receita) AS VT FROM #__contproj_receitas WHERE  rubricasdeprojetos_id = $rubricasdeprojetos_id";
	$database->setQuery($sql2);
	$somaReceitas = $database->loadResult();//soma da coluna valor total
	
	$sql3 = "SELECT valor_total FROM #__contproj_rubricasdeprojetos WHERE id = $rubricasdeprojetos_id";
	$database->setQuery($sql3);
	$valor_previsto = $database->loadResult();
	
	// CAPTURAR O VALOR DISPONIVEL ATUAL PARA ACRESCENTAR COM A RECEITA CADASTRADA
	$sqlValor = "SELECT valor_disponivel FROM #__contproj_rubricasdeprojetos WHERE id = '".$rubricasdeprojetos_id."'";
	$database->setQuery($sqlValor);
	$resultado = $database->loadObjectList();
	foreach ($resultado as $valor)
		$valorDisp = $valor->valor_disponivel;
		
		$novoValorDisp = $valorDisp + $valor_receita;
	
	if (((double)$somaReceitas + (double)$valor_receita) <= (double)$valor_previsto) {//condicional para cadastro de receita	
		$sql = "INSERT INTO #__contproj_receitas (rubricasdeprojetos_id, descricao, valor_receita, data)
				VALUES ($rubricasdeprojetos_id, '$descricao', $valor_receita, '$data')";
		$database->setQuery($sql);
		$funcionou = $database->Query();
		
		// ATUALIZAR SALDO DA RUBRICA DO PROJETO
		$sqlUpdate = "UPDATE #__contproj_rubricasdeprojetos SET valor_disponivel = '$novoValorDisp' WHERE id = '".$rubricasdeprojetos_id."'";
		$database->setQuery($sqlUpdate);
		$foi = $database->Query();
	
		// ATUALIZAR SALDO DO PROJETO
		$saldoAtual = calcularSaldo($idProjeto);
		$saldoAtual = moedaSql($saldoAtual);
		$saldo = $saldoAtual + $valor_receita;	
	
		$sqlUpdate = "UPDATE #__contproj_projetos SET saldo = '$saldo' WHERE id = '$idProjeto'";
		$database->setQuery($sqlUpdate);
		$foi2 = $database->Query();
		
		if($funcionou){
			JFactory::getApplication()->enqueueMessage(JText::_('Receita cadastrada com sucesso!'));
			return 1;
		} else {
			JError::raiseWarning( 100, 'Cadastro não realizado, dados inválidos!' );
			return 0;
		}
	} else { 
		JError::raiseWarning( 100, 'Cadastro não realizado o valor da receita excede o valor previsto no projeto!' );
		return 0;
	}
}


// EXCLUIR RECEITA
function excluirReceita($idReceita){ //TÁ IMPRIMINDO O ID DO PROJETO (63), DEVE IMPRIMIR 24 QUE É O ID DA RECEITA
	$database = JFactory::getDBO();
	$idProjeto = JRequest::getVar('idProjeto');
		
	$sqlBusca = "SELECT COUNT(despesas.rubricasdeprojetos_id) as total, receitas.valor_receita, receitas.rubricasdeprojetos_id
				  FROM j17_contproj_receitas AS receitas, j17_contproj_despesas AS despesas
				  WHERE despesas.rubricasdeprojetos_id = receitas.rubricasdeprojetos_id
				  AND receitas.id = '".$idReceita."'";
	$database->setQuery($sqlBusca);
	$resultado = $database->loadObjectList();

	foreach ($resultado as $res) {
		$total = $res->total;
		$receita = $res->valor_receita;
		$rubrica_id = $res->rubricasdeprojetos_id;
		
		$sqlValor = "SELECT valor_total, valor_gasto, valor_disponivel FROM #__contproj_rubricasdeprojetos WHERE id = '".$rubrica_id."'";
	   	$database->setQuery($sqlValor);
   		$resultadoValor = $database->loadObjectList();
		
		foreach ($resultadoValor as $r)
			$valorTotal = $r->valor_total;
			$valorGasto = $r->valor_gasto;
			$valorDisponivel = $r->valor_disponivel;
		
			$valorDisponivelNovo = $valorTotal - $receita;

		if ($total == 0) {
			
			// ATUALIZAR SALDO DA RUBRICA DO PROJETO
			$sqlUpdate = "UPDATE #__contproj_rubricasdeprojetos SET valor_disponivel = '$valorDisponivelNovo' WHERE id = '".$rubrica_id."'";
			$database->setQuery($sqlUpdate);
			$foi = $database->Query();		
			
			// ATUALIZAR SALDO DO PROJETO
			$sqlSaldo = "SELECT saldo FROM #__contproj_projetos WHERE id = '$idProjeto'";
			$database->setQuery($sqlSaldo);
			$resultadoSaldo = $database->loadObjectList();
			
			foreach ($resultadoSaldo as $r) 
				$saldoAtual = $r->saldo;
				$saldo = $saldoAtual - $receita;
			
			$sqlUpdate = "UPDATE #__contproj_projetos SET saldo = '$saldo' WHERE id = '$idProjeto'";
			$database->setQuery($sqlUpdate);
			$funcionou3 = $database->Query();
			
			// EXCLUI A RECEITA DA TABELA DE RECEITAS
			$sql = "DELETE FROM #__contproj_receitas WHERE id = $idReceita";
			$database->setQuery($sql);
			$funcionou = $database->Query();
			
			if ($funcionou) {
				JFactory::getApplication()->enqueueMessage(JText::_('Exclusão da Receita realizada com sucesso!'));
				return 1;
			} else {
				JError::raiseWarning( 100, 'Erro ao tentar excluir receita!' );
				return 0;
			}
			
		} else { ?>
			
            <div class="warning">
            	Você não pode excluir esta receita, já existem despesas cadastradas para ela!
            </div>
			
		<?php }
	}	
}


// Identifica o id do registro selecionado pelo radio button
function identificarReceitaID($idReceita){
    $database = JFactory::getDBO();
    $sql = "SELECT * FROM #__contproj_receitas WHERE id = $idReceita LIMIT 1";
    $database->setQuery( $sql );
    $Receita = $database->loadObjectList();
	
    return ($Receita[0]);
}

function getReceitaTipo($idProjeto, $tipo){

    $database	= JFactory::getDBO();
	
	$sql = "SELECT R.id, RU.tipo, RU.codigo, RU.nome, R.descricao, R.data, R.valor_receita
			FROM  #__contproj_receitas AS R
			INNER JOIN #__contproj_rubricasdeprojetos AS RP  ON R.rubricasdeprojetos_id = RP.id			
			INNER JOIN #__contproj_rubricas AS RU  ON RU.id = RP.rubrica_id			
			WHERE projeto_id = $idProjeto AND RU.tipo = '$tipo' ORDER BY data DESC";
	
    $database->setQuery($sql);
    $receitas = $database->loadObjectList(); 
	return $receitas;
	
}
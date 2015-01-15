<?php
/***********************************************************************
*               UFAM - Universidade Ferderal do Amazonas
* 
*      Funcoes de controle do modulo Despesaprojeto,desenvolvido em 10/02/2013
* adaptado do modulo professor, para update do site icomp sob orientacao
* do Prof Arilo Dias. 
* 
* Sitemas de informacao, Equipe Augusto, Denise e Diana
* usando padrao de desenvolvimento ágil
* 
************************************************************************/

// LISTAGEM DAS DESPESAS
function listarDespesasprojetos(){
    $database	= JFactory::getDBO();
	
    $Itemid = JRequest::getInt('Itemid', 0);
    $idProjeto = JRequest::getVar('idProjeto');
    $projetopd = identificarProjetopdID($idProjeto);
	$despesasCapital = getDespesaTipo($idProjeto, "Capital");
	$despesasCusteio = getDespesaTipo($idProjeto, "Custeio");
	
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
		function excluir(form){
			var idSelecionado = 0;
			
			for(i = 0;i < form.idDespesaSelec.length;i++)
            	if(form.idDespesaSelec[i].checked) 
					idSelecionado = form.idDespesaSelec[i].value;
				
				if(idSelecionado > 0) {
					var resposta = window.confirm("Confirme a exclusão do item.");
					if(resposta) {
                    	form.task.value = 'excluirDespesaprojeto';
                    	form.idDespesaprojeto.value = idSelecionado;
                    	form.submit();
                   	}
				} else {
            		alert('Selecione o item a ser excluído!')
		   		}
		}
    
		function editar(form) {
			var idSelecionado = 0;
			
			for(i = 0;i < form.idDespesaSelec.length;i++)
				if(form.idDespesaSelec[i].checked) 
					idSelecionado = form.idDespesaSelec[i].value;
					
				if(idSelecionado > 0) {
					form.task.value = 'editarDespesaprojeto';
					form.idDespesaprojeto.value = idSelecionado;
					form.submit();
				} else {
					alert('Selecione o item a ser editado!')
				}
		}
		
		function visualizar(form) {
			var idSelecionado = 0;
		   
			for(i = 0;i < form.idDespesaSelec.length;i++)
				if(form.idDespesaSelec[i].checked) 
					idSelecionado = form.idDespesaSelec[i].value;
					
				if(idSelecionado > 0) {
					form.task.value = 'verDespesaprojeto';//volta para visualizar projeto
					form.idDespesaprojeto.value = idSelecionado;
					form.submit();
				} else {
					alert('Selecione o item a ser  visualizado.')
				}
		}
		
		function voltarNivelForm(form){
			form.task.value = 'verProjetopd';
			form.submit();
			return true;
		}	
    </script>

   
	<link rel="stylesheet" href="components/com_controleprojetos/estilo.css" type="text/css" />	
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">   
<style type="text/css">

* {  
  font-family:arial;
  line-height:1.5em;
  }

table,
th {
  border: solid 1px silver;
  color:#666;
  padding:5px;
  font-size:8pt;
  }
table {
  border-collapse:collapse;
  }
table tr {
  background-color:#eee;
  }

th {
  background-color:#333;
  color:#fff;
  font-size:0.85em
  }
  
.hover { background-color: #106166; color: #fff; cursor:pointer; }
.page{ margin:5px;}  

tbody tr:nth-of-type(even){
background:rgba(179,216,179,0.5);
}
 
tbody tr:nth-of-type(odd){
background:rgba(222,240,222,0.5);
}

thead th{
background:rgba(28,157,162,0.5);
}
</style>        
    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>">
    
        <!-- Barra de Ferramentas DESPESAS -->
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
			<div class="cpanel2">
				<div class="icon" id="toolbar-new">
           			<a href="javascript:document.form.task.value='addDespesaprojeto';document.form.submit()">
           			<span class="icon-32-new"></span><?php echo JText::_( 'Nova' ); ?></a>
				</div>
                
                <div class="icon" id="toolbar-preview">
           			<a href="javascript:visualizar(document.form)">
           			<span class="icon-32-preview"></span><?php echo JText::_( 'Visualizar' ); ?></a>
				</div>
                
                <div class="icon" id="toolbar-delete">
           			<a href="javascript:excluir(document.form)">
           			<span class="icon-32-delete"></span><?php echo JText::_( 'Excluir' ); ?></a>
				</div>
                
  				<div class="icon" id="toolbar-back">
                    <a href="javascript:voltarNivelForm(document.form)">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
			</div>
            
            <div class="clr"></div>
            </div>
            <div class="pagetitle"><h2>Movimentações Financeiras</h2></div>
	    </div></div>
		
		<!-- Informações -->
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
                
                <div class="icon" id="toolbar-remove">
           			<span class="icon-32-remove"></span><?php echo JText::_( '<b>Gasto (R$)</b><br>R$ '.moedaBr($resumoCapitalGasto)); ?>
				</div>
                
  				<div class="icon" id="toolbar-purge">
           			<span class="icon-32-purge"></span><?php echo JText::_( '<b>Saldo (R$)</b><br>R$ '.moedaBr($resumoCapitalSaldo)); ?>
				</div>
			</div>
            
            <div class="clr"></div>
            </div>
            <div class="pagetitle icon-48-module"><h2>Itens de Capital</h2></div>
	    </div></div>
        
		<!-- Formulario -->
		<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters" class="tabela">
			<thead style="color:#FFC000; text-align:center;">
				<tr bgcolor="#002666">
                    <th width="2%"></th>
                    <th width="15%">Tipo Doc. Fiscal</th>
                    <th width="15%">Num. Doc.</th>					
					<th width="20%">Nome do Favorecido</th>
                    <th width="20%">Item de Dispêndio</th>
                    <th width="15%">Data de Emissão</th>					
                    <th width="15%">Descrição do Item</th>
                    <th width="10%">Valor (R$)</th>
                    <th width="10%">Num. Cheque</th>					
                </tr>
			</thead>
			<tbody>
            	<tr>
				<?php
                $table_bgcolor_even="#e6e6e6";
                $table_bgcolor_odd="#FFFFFF";
                $i = 0;
				if($despesasCapital){
                foreach ($despesasCapital as $despesas) {
                    $i = $i + 1;
                    if ($i % 2) {
                        echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
                    } else {
                        echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
                    } ?>
        
					<td width='16'>
	                    <?php if ($despesas->descricao == 'Transferência de Saldo') {} else { ?>
                            <input type="radio" name="idDespesaSelec" value="<?php echo $despesas->id;?>">
                        <?php } ?>
                    </td>
 	                <td><?php echo $despesas->nf;?></td>					
			<td><?php echo $despesas->ident_nf;?></td>					
                	<td><?php echo $despesas->favorecido;?></td>
	                <td><?php echo $despesas->nome;?></td>
                	<td><?php echo $despesas->data_emissao;?></td>
                	<td><?php echo $despesas->descricao;?></td>
                	<td><?php echo moedaBr($despesas->valor_despesa);?></td>
                	<td><?php echo $despesas->ident_cheque;?></td>					
				</tr>
				<?php }} ?>
     		</tbody>
     	</table>
        
		<br />
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
            
            <div class="clr"></div>
            </div>
            <div class="pagetitle icon-48-massmail"><h2>Itens de Custeio</h2></div>
	    </div></div>
        
		<!-- Formulario -->
		<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters" class="tabela">
			<thead style="color:#FFC000; text-align:center;">
				<tr bgcolor="#002666">
                    <th width="2%"></th>
                    <th width="15%">Tipo Doc. Fiscal</th>
                    <th width="15%">Num. Doc.</th>					
					<th width="20%">Nome do Favorecido</th>
                    <th width="20%">Item de Dispêndio</th>
                    <th width="15%">Data de Emissão</th>					
                    <th width="15%">Descrição do Item</th>
                    <th width="10%">Valor (R$)</th>
                    <th width="10%">Num. Cheque</th>					
                </tr>
			</thead>
			<tbody>
            	<tr>
				<?php
                $table_bgcolor_even="#e6e6e6";
                $table_bgcolor_odd="#FFFFFF";
                $i = 0;
				if($despesasCusteio){
                foreach ($despesasCusteio as $despesas) {
                    $i = $i + 1;
                    if ($i % 2) {
                        echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
                    } else {
                        echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
                    } ?>
        
					<td width='16'>
	                    <?php if ($despesas->descricao == 'Transferência de Saldo') {} else { ?>
                            <input type="radio" name="idDespesaSelec" value="<?php echo $despesas->id;?>">
                        <?php } ?>
                    </td>
   	                <td><?php echo $despesas->ident_nf;?></td>					
					<td><?php echo $despesas->nf;?></td>					
                	<td><?php echo $despesas->favorecido;?></td>
	                <td><?php echo $despesas->nome;?></td>
                	<td><?php echo $despesas->data_emissao;?></td>
                	<td><?php echo $despesas->descricao;?></td>
                	<td><?php echo moedaBr($despesas->valor_despesa);?></td>
                	<td><?php echo $despesas->ident_cheque;?></td>					
				</tr>
				<?php }} ?>
     		</tbody>
     	</table>
        <input name='task' type='hidden' value='gerenciarDespesasprojetos' />
        <input name='idProjetopd' type='hidden' value='<?php echo $idProjeto;?>' />
        <input name='idProjeto' type='hidden' value='<?php echo $idProjeto;?>' />
        <input name='idDespesaSelec' type='hidden' value='0' />
        <input name='idDespesaprojeto' type='hidden' value='' />

     </form>     

<?php }      


// FORMULÁRIO PARA CADASTRO DE DESPESA
function addDespesaprojeto($Despesaprojeto = NULL, $idProjeto){
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
    <!--Valida dados no formulario-->
    <script language="JavaScript">
    function IsEmpty(aTextField){
       if ((aTextField.value.length==0) ||
       (aTextField.value==null) ) {
          return true;
       }
       else { return false; }
    }
    
    function radio_button_checker(elem){
      var radio_choice = false;
      for (counter = 0; counter < elem.length; counter++){
        if (elem[counter].checked)
        radio_choice = true;
       }
      return (radio_choice);
    }
    
    function IsNumeric(sText){
       var ValidChars = "0123456789.";
       var IsNumber=true;
       var Char;
       if (sText.length <= 0){
          IsNumber = false;
       }
       for (i = 0; i < sText.length && IsNumber == true; i++){
          Char = sText.charAt(i);
          if (ValidChars.indexOf(Char) == -1)
             {
             IsNumber = false;
             }
          }
      return IsNumber;
    }
    
    function ValidateformCadastro(formCadastro){
       if(IsEmpty(formCadastro.descricao)){
          alert('O campo descrição deve ser preenchido.')
          formCadastro.descricao.focus();
          return false;
       }
       if(IsEmpty(formCadastro.valor_despesa)){
          alert('O campo valor despesa deve ser preenchido.')
          formCadastro.valor_despesa.focus();
          return false;
       }
     if(IsEmpty(formCadastro.tipo_pessoa)){
          alert('O campo tipo de pessoa deve ser preenchido.')
          formCadastro.tipo_pessoa.focus();
          return false;
       }   
    return true;
    }
        
    function voltarForm(form){
       form.task.value = 'gerenciarDespesasprojetos';
       form.submit();
       return true;
    }
    </script>

	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    <link type="text/css" href="components/com_controleprojetos/jquery-ui-1.10.1.custom/css/ui-lightness/jquery-ui-1.10.1.custom.css" rel="Stylesheet" />
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>  
	<script>
		$(function() {//Mudanca para PT(BR)
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
		$( "#data_emissao" ).datepicker({dateFormat: 'dd/mm/yy'})
			$( "#data_emissao_cheque" ).datepicker({dateFormat: 'dd/mm/yy'});
	
		});
	</script>
	<script src="components/com_controleprojetos/jquery.maskMoney.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(function(){
			$("#valor_despesa").maskMoney({thousands:'.', decimal:',', symbolStay: true});
		})
    </script>	
	<script type="text/javascript">
		$(function(){
			$("#valor_cheque").maskMoney({thousands:'.', decimal:',', symbolStay: true});
		})
    </script>	
	
    <script type="text/javascript" src="components/com_controleprojetos/scripts.js"></script>
    
  	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/estilo.css">

   <form method="post" name="formCadastro" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateformCadastro(this)">
   
		<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
			<div class="cpanel2">
				<div class="icon" id="toolbar-save">
                    <!-- A opcao abaixo usa o recurso de validacao do formulario-->
                    <a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
                    <!--A opcao abaixo acessa o formulario de forma direta-->
                    <!--<a href="javascript:document.formCadastro.task.value='salvarDespesaprojeto';document.formCadastro.submit()">-->
           			<span class="icon-32-save"></span>Salvar</a>
				</div>
                
                <div class="icon" id="toolbar-cancel">
           			<a href="javascript:voltarForm(document.formCadastro)">
           			<span class="icon-32-cancel"></span>Cancelar</a>
		    	</div> 
                               
                </div>
                <div class="clr"></div>
                </div>
                <div class="pagetitle icon-48-cpanel"><h2>Cadastro de Despesas</h2></div>
            </div>
        </div>
    
		<b>Como proceder: </b>
		<ul><li>Preencha os dados da despesa <font color="#FF0000">(* Campos Obrigatórios)</font>.</li></ul>
        
		<hr />
        
		<table width="100%">
        	<tr style="background-color: #7196d8;">
               <td style="width: 100%;" colspan="4"><font size="2" color="#FFFFFF"><b>Informações</b></font></td>
            </tr>
            <tr>
                <input name='id' type='hidden' value="NULL"><!--referencia o ID visualizacao-->
                <input name='projeto_id' type='hidden' value='<?php echo $idProjeto;?>'><!--referencia o ID do PROJETO para novo-->
            </tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Item de Dispêndio:</td>
                <td colspan="2">
                  <select name="rubricasdeprojetos_id" class="inputbox">
                    <option value=""></option>
                    <?php 
						$database->setQuery("SELECT RP.id AS idRubricaProjeto, R.tipo, RP.projeto_id, P.nomeprojeto, RP.rubrica_id,
                                     RP.valor_total, RP.valor_gasto, RP.valor_disponivel, R.codigo, R.nome
                                     FROM j17_contproj_rubricasdeprojetos AS RP
                                     INNER JOIN j17_contproj_projetos AS P ON RP.projeto_id = P.id
                                     INNER JOIN j17_contproj_rubricas AS R ON RP.rubrica_id = R.id
                                     WHERE RP.projeto_id = $idProjeto");
						$rubrica_listas = $database->loadObjectList();
						
                        foreach($rubrica_listas as $rubricasLista) { ?> 
                        	<option value="<?php echo $rubricasLista->idRubricaProjeto;?>">
								<?php echo $rubricasLista->tipo;?> : <?php echo $rubricasLista->nome;?> - Val Disponível: R$ <?php echo moedaBr($rubricasLista->valor_disponivel);?>
							</option>
						<?php } ?>
                  </select>
                 </td>
            </tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Tipo de Documento Fiscal:</td>
                <td><select name="tipoDocumento" class="inputbox" style="width:210px;">
                        <option value=""></option>
                        <option value="Cupom Fiscal">Cupom Fiscal</option>
                        <option value="DARF">DARF</option>
                        <option value="Invoice">Invoice</option>
                        <option value="Nota Fiscal">Nota Fiscal</option>
                        <option value="Nota de Empenho">Nota de Empenho</option>
                        <option value="Recibo">Recibo</option>						
                        <option value="Tarifa Bancaria">Tarifa Bancaria</option>						

                    </select>
				</td>
            </tr> 
            <tr>
                <td class="tbItemForm">Número do Documento Fiscal:</td>
                <td><input type="text" maxlength="50" size="50" name="numDocumento" class="inputbox" /></td>
            </tr> 

            <tr>
                <td class="tbItemForm"><span>*</span> Descrição:</td>
                <td><input type="text" maxlength="200" size="80" name="descricao" class="inputbox" /></td>
            </tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Valor Despesa:</td>
                <td><input type="text" maxlength="15" size="15" id="valor_despesa" name="valor_despesa" class="inputbox" onKeyPress="return(MascaraMoeda(this,'.',',',event))" /></td>
            </tr> 
                <td class="tbItemForm"><span>*</span> Tipo Pessoa:</td>
            	<td>
                    <select name="tipo_pessoa" class="inputbox" style="width:110px;">
                        <option value=""></option>
                        <option value="Física">Física</option>
                        <option value="Jurídica">Jurídica</option>
                    </select>
                </td>
            <tr>
                <td class="tbItemForm"><span>*</span> Data Emissão:</td>
                <td><input type="text" maxlength="10" size="15" id="data_emissao" name="data_emissao" class="inputbox" /></td>
            </tr>
            <tr>
                <td class="tbItemForm">Identificação cheque:</td>
                <td><input type="text" maxlength="15" size="15" name="ident_cheque" class="inputbox" /></td>
            </tr> 
            <tr>
                <td class="tbItemForm">Data Emissão do Cheque:</td>
                <td><input type="text" maxlength="10" size="15" id="data_emissao_cheque" name="data_emissao_cheque" class="inputbox" /></td>
            </tr>            
            <tr>
                <td class="tbItemForm"><span>*</span> Valor Cheque:</td>
                <td><input type="text" maxlength="15" size="15" id="valor_cheque" name="valor_cheque" class="inputbox" onKeyPress="return(MascaraMoeda(this,'.',',',event))" /></td>
            </tr> 
            <tr>
                <td class="tbItemForm">Favorecido:</td>
                <td><input type="text" maxlength="60" size="60" name="favorecido" class="inputbox" /></td>
            </tr> 
            <tr>
                <td class="tbItemForm">CNPJ/CPF:</td>
                <td><input type="text" maxlength="15" size="15" name="cnpj_cpf" class="inputbox" /></td>
            </tr> 
            <tr>
                <td class="tbItemForm">Comprovante:</td>
                <td><input type="file" name="comprovante" /></td>
            </tr> 
		</table>
       
        <input name='idDespesaprojeto' type='hidden' value='<?php if($Despesaprojeto) echo $Despesaprojeto->id;?>' />
        <input name='idProjeto' type='hidden' value='<?php echo $idProjeto;?>' />
        <input name='task' type='hidden' value='salvarDespesaprojeto' />
        
    </form>
    
<?php }


// SALVAR DESPESA
function salvarDespesaprojeto($idProjeto){
	$database = JFactory::getDBO();
	
	$id = JRequest::getVar('id');
	$rubricasdeprojetos_id = JRequest::getVar('rubricasdeprojetos_id');
	$descricao = JRequest::getVar('descricao');
	$valor_despesa = moedaSql(JRequest::getVar('valor_despesa'));
	$tipo_pessoa = JRequest::getVar('tipo_pessoa');
	$data_emissao = dataSql(JRequest::getVar('data_emissao'));
	$tipoDocumento = JRequest::getVar('tipoDocumento');
	$numDocumento = JRequest::getVar('numDocumento');
	$ident_cheque = JRequest::getVar('ident_cheque');
	$data_emissao_cheque = dataSql(JRequest::getVar('data_emissao_cheque'));
	$valor_cheque = moedaSql(JRequest::getVar('valor_cheque'));
	$favorecido = JRequest::getVar('favorecido');
	$cnpj_cpf = JRequest::getVar('cnpj_cpf');  
	$comprovante = "";
	
	if($_FILES['comprovante']['tmp_name']){
		$codigo = md5($descricao.date("d/m/Y h:i"));
		$comprovante = "components/com_controleprojetos/docprojetos/comprovantes/PPGI-Comprovante-".$codigo.".pdf";
		$sucesso = move_uploaded_file($_FILES["comprovante"]["tmp_name"],$comprovante);	
		if(!$sucesso){
			JError::raiseWarning( 100, 'ERRO: Não foi possível realizar mover o arquivo escolhido!' );
			addDespesaprojeto(NULL, $idProjeto);
			return 0;
		}
	}

	// CAPTURA DOS VALORES PARA ATUALIZAÇÃO DE SALDO DA RUBRICA DO PROJETO
	$sqlValor = "SELECT projeto_id, valor_total, valor_gasto, valor_disponivel 
			 FROM #__contproj_rubricasdeprojetos 
			 WHERE id = $rubricasdeprojetos_id";
	$database->setQuery($sqlValor);
   	$resultado = $database->loadObjectList();
	
	foreach ($resultado as $res) {
		$valorTotal = $res->valor_total;
		$valorGasto = $res->valor_gasto;
		$valorDisponivel = $res->valor_disponivel;
	
		//// VALOR DA DESPESA NÃO PODE SER MAIOR QUE O VALOR DISPONÍVEL
		//if ($valor_despesa <= $valorDisponivel) {		

			$valorGastoNovo = $valorGasto + $valor_despesa;		
			$valorDisponivelNovo = $valorDisponivel - $valor_despesa;
		
			// ATUALIZAR SALDO DA RUBRICA DO PROJETO
			$sqlUpdate = "UPDATE #__contproj_rubricasdeprojetos SET valor_gasto = '$valorGastoNovo', valor_disponivel = '$valorDisponivelNovo' WHERE id = $rubricasdeprojetos_id";
			$database->setQuery($sqlUpdate);
			$funcionou = $database->Query();			
	
			// INSERÇÃO NA TABELA DESPESAS
			$sqll = "INSERT INTO #__contproj_despesas (rubricasdeprojetos_id, descricao, valor_despesa, tipo_pessoa, data_emissao, ident_nf, nf, ident_cheque, data_emissao_cheque, valor_cheque, favorecido, cnpj_cpf, comprovante)
			   VALUES ('$rubricasdeprojetos_id', '$descricao', '$valor_despesa', '$tipo_pessoa', '$data_emissao', '$tipoDocumento', '$numDocumento','$ident_cheque', '$data_emissao_cheque', '$valor_cheque', '$favorecido', '$cnpj_cpf', '$comprovante')";
			$database->setQuery($sqll);
			$funcionou2 = $database->Query();

			// ATUALIZAR SALDO DO PROJETO
			$sqlSaldo = "SELECT saldo FROM #__contproj_projetos WHERE id = '$idProjeto'";
			$database->setQuery($sqlSaldo);
			$resultadoSaldo = $database->loadObjectList();
			
			foreach ($resultadoSaldo as $r) 
				$saldoAtual = $r->saldo;
				$saldo = $saldoAtual - $valor_despesa;
			
			$sqlUpdate = "UPDATE #__contproj_projetos SET saldo = '$saldo' WHERE id = '$idProjeto'";
			$database->setQuery($sqlUpdate);
			$funcionou3 = $database->Query();
		
			if($funcionou && $funcionou2){
				JFactory::getApplication()->enqueueMessage(JText::_('Cadastro realizado com sucesso!'));
				listarDespesasprojetos();
				return 1;
			} else {
				JError::raiseWarning( 100, 'Cadastro não realizado!' );
				addDespesaprojeto(NULL, $res->projeto_id);
				return 0;
			}
	
	//	} else { ?>
		
			<!--<div class="warning">O valor da despesa não pode ser maior que o valor disponível!</div>-->
			<?php //addDespesaprojeto(NULL, $res->projeto_id);
		//}		
	}
}


// EXCLUIR DESPESA
function excluirDespesaprojeto($idDespesaprojeto){
	$database = JFactory::getDBO();
	$idProjeto = JRequest::getVar('idProjeto');
	
	$sqlRubrica = "SELECT rubricasdeprojetos_id, valor_despesa FROM #__contproj_despesas WHERE id = '".$idDespesaprojeto."'";
	$database->setQuery($sqlRubrica);
   	$despesa = $database->loadObjectList();
	
	foreach ($despesa as $d)
		$rubrica_id = $d->rubricasdeprojetos_id;
		$valorDespesa = $d->valor_despesa;
	
		$sqlValor = "SELECT valor_gasto, valor_disponivel FROM #__contproj_rubricasdeprojetos WHERE id = '".$rubrica_id."'";
		$database->setQuery($sqlValor);
		$resultado = $database->loadObjectList();
		
		foreach ($resultado as $res) {
			$valorGasto = $res->valor_gasto;
			$valorDisponivel = $res->valor_disponivel;
			
			$valorGastoNovo = $valorGasto - $valorDespesa;			
			$valorDisponivelNovo = $valorDisponivel + $valorGasto;	
		
			// ATUALIZAR SALDO DA RUBRICA DO PROJETO
			$sqlUpdate = "UPDATE #__contproj_rubricasdeprojetos SET valor_disponivel = '$valorDisponivelNovo', valor_gasto = '$valorGastoNovo' WHERE id = '".$rubrica_id."'";
			$database->setQuery($sqlUpdate);
			$foi = $database->Query();
			
			// ATUALIZAR SALDO DO PROJETO
			$sqlSaldo = "SELECT saldo FROM #__contproj_projetos WHERE id = '$idProjeto'";
			$database->setQuery($sqlSaldo);
			$resultadoSaldo = $database->loadObjectList();
			
			foreach ($resultadoSaldo as $r) 
				$saldoAtual = $r->saldo;
				$saldo = $saldoAtual + $valorDespesa;
			
			$sqlUpdate = "UPDATE #__contproj_projetos SET saldo = '$saldo' WHERE id = '$idProjeto'";
			$database->setQuery($sqlUpdate);
			$funcionou3 = $database->Query();
		
			// EXCLUIR A DESPESA
			$sql = "DELETE FROM #__contproj_despesas WHERE id = $idDespesaprojeto";
			$database->setQuery($sql);
			$funcionou = $database->Query();
			
			if ($funcionou) {
				JFactory::getApplication()->enqueueMessage(JText::_('Exclusão da Despesa realizada com sucesso!'));
				return 1;
			} else {
				JError::raiseWarning( 100, 'Erro ao tentar excluir receita!' );
				return 0;
			}
		
		}
}

// Identifica o id do registro selecionado pelo radio button
function identificarDespesaprojetoID($idDespesaprojeto){
    $database = JFactory::getDBO();
    $sql = "SELECT * FROM #__contproj_despesas WHERE id = $idDespesaprojeto LIMIT 1";
    $database->setQuery( $sql );
    $Despesaprojeto = $database->loadObjectList();
	
    return ($Despesaprojeto[0]);
}

// VISUALIZAR DESPESA DO PROJETO
function relatorioDespesaprojeto($despesa, $idProjeto){

    $database = JFactory::getDBO();
	$rubrica = getRubrica($despesa->rubricasdeprojetos_id);
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    <!--Funcionalidades dos formularios (Excluir, Editar e Visualizar) --> 
    <script language="JavaScript">
		
		function voltar(form){
			form.task.value = 'gerenciarDespesasprojetos';
			form.submit();
			return true;
		}	
    </script>
   <form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateformCadastro(this)">
   
		<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
			<div class="cpanel2">
                <div class="icon" id="toolbar-back">
           			<a href="javascript:voltar(document.formCadastro)">
           			<span class="icon-32-back"></span>Voltar</a>
		    	</div> 
                </div>
                <div class="clr"></div>
                </div>
                <div class="pagetitle icon-48-cpanel"><h2>Visualizar Despesa</h2></div>
            </div>
        </div>
    
		<hr />
		<!-- Informações -->
		<?php 
			$projetopd = identificarProjetopdID($idProjeto);
			exibirProjeto($projetopd ); 
		?>
		<hr>		
        
		<table width="100%">
        	<tr style="background-color: #7196d8;">
               <td style="width: 100%;" colspan="2"><font size="2" color="#FFFFFF"><b>Dados da Despesa</b></font></td>
            </tr>
            <tr>
                <td style="width: 30%;" class="tbItemForm">Item de Dispêndio:</td>
                <td><?php echo $rubrica->nome;?></td>				
                <td>
                </td>
            </tr>
            <tr>
                <td class="tbItemForm">Descrição:</td>
                <td><?php echo $despesa->descricao;?></td>
            </tr>
            <tr>
                <td class="tbItemForm">Valor da Despesa (R$):</td>
                <td><?php echo moedaBr($despesa->valor_despesa);?></td>
            </tr> 
                <td class="tbItemForm">Tipo Pessoa:</td>
				<td><?php echo $despesa->tipo_pessoa;?></td>				
            <tr>
                <td class="tbItemForm">Data Emissão:</td>
				<td><?php echo $despesa->data_emissao;?></td>
            </tr>
            <tr>
                <td class="tbItemForm">Tipo de Documento Fiscal:</td>
                <td><?php echo $despesa->nf;?></td>
            </tr> 
            <tr>
                <td class="tbItemForm">Número do Documento Fiscal:</td>
                <td><?php echo $despesa->ident_nf;?></td>
            </tr> 
            <tr>
                <td class="tbItemForm">Identificação cheque:</td>
                <td><?php echo $despesa->ident_cheque;?></td>
            </tr> 
            <tr>
                <td class="tbItemForm">Data Emissão do Cheque:</td>
                <td><?php echo $despesa->data_emissao_cheque;?></td>				
            </tr>            
            <tr>
                <td class="tbItemForm">Valor Cheque (R$):</td>
                <td><?php echo moedaBr($despesa->valor_cheque);?></td>				
            </tr> 
            <tr>
                <td class="tbItemForm">Favorecido:</td>
                <td><?php echo $despesa->favorecido;?></td>				
            </tr> 
            <tr>
                <td class="tbItemForm">CNPJ/CPF:</td>
                <td><?php echo $despesa->cnpj_cpf;?></td>				
            </tr> 
            <tr>
                <td class="tbItemForm">Comprovante:</td>
				<td><a href="<?php echo $despesa->comprovante;?>" target="_blank"><img src="components/com_controleprojetos/images/icon_pdf.gif"></a></td>
            </tr> 		
        </table>
       
		<input name='idDespesaprojeto' type='hidden' value='<?php if($Despesaprojeto) echo $Despesaprojeto->id;?>' />
        <input name='idProjeto' type='hidden' value='<?php echo $idProjeto;?>' />
        <input name='task' type='hidden' value='salvarDespesaprojeto' />
        <input name='buscaDescricao' type='hidden' value='<?php echo $descricao;?>' />
        
    </form>
    
<?php 

}

// EXCLUIR DESPESA
function getRubrica($rubricaDeProjeto){
	$database = JFactory::getDBO();
	
	$sqlRubrica = "SELECT codigo, nome FROM #__contproj_rubricas AS R JOIN #__contproj_rubricasdeprojetos AS RP ON RP.rubrica_id = R.id WHERE RP.id = $rubricaDeProjeto";
	$database->setQuery($sqlRubrica);
   	$rubrica = $database->loadObjectList();
	return($rubrica[0]);

}

function getDespesaTipo($idProjeto, $tipo){

    $database	= JFactory::getDBO();
	
	$sql = "SELECT D.id, R.codigo, R.nome, D.descricao,  D.valor_despesa, D.tipo_pessoa, DATE_FORMAT(D.data_emissao, '%d/%m/%Y') as data_emissao, D.ident_nf, D.nf, 
			D.ident_cheque, D.data_emissao_cheque, D.valor_cheque, D.favorecido, D.cnpj_cpf
			FROM #__contproj_despesas AS D
			INNER JOIN #__contproj_rubricasdeprojetos AS RP
				  ON D.rubricasdeprojetos_id = RP.id
			INNER JOIN #__contproj_rubricas AS R
				  ON R.id = RP.rubrica_id
			WHERE  projeto_id LIKE '$idProjeto' AND R.tipo = '$tipo' ORDER BY data_emissao DESC";   
                   
    $database->setQuery($sql);
    $despesas = $database->loadObjectList(); 
	return $despesas;
	
}



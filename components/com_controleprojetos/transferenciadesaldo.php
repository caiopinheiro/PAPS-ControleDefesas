<?php
/***********************************************************************
*               UFAM - Universidade Ferderal do Amazonas
* 
*      Funcoes de controle do modulo Despesa,desenvolvido em 10/02/2013
* adaptado do modulo professor, para update do site icomp sob orientacao
* do Prof Arilo Dias. 
* 
* Sitemas de informacao, Equipe Augusto, Denise e Diana
* usando padrao de desenvolvimento ágil
* 
************************************************************************/

// LISTAGEM DE TRANSFERENCIAS
function listarTranferenciadeRubricas(){
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
	
    $idProjeto = JRequest::getVar('idProjeto');
    $projetopd = identificarProjetopdID($idProjeto);
    $sql = "SELECT TR.id, TR.projeto_id, rubrica_origem, rubrica_destino, O.descricao AS rubricaOrigem, D.descricao AS rubricaDestino, TR.valor, TR.data, TR.autorizacao
             FROM #__contproj_transferenciassaldorubricas AS TR 
             INNER JOIN #__contproj_rubricasdeprojetos AS O ON TR.rubrica_origem = O.id  
             INNER JOIN #__contproj_rubricasdeprojetos AS D ON TR.rubrica_destino = D.id    
             WHERE  TR.projeto_id = $idProjeto ORDER BY data"; //idprojeto->identifica o projeto                 
    
    $database->setQuery( $sql );
    $gerenciarTranferenciadeRubricas = $database->loadObjectList(); ?>

    <!--Funcionalidades dos formularios (Excluir, Editar e Visualizar) --> 
    <script language="JavaScript">
    	function excluir(form) {
			var idSelecionado = 0;
			
	        for(i = 0;i < form.idTranferenciaSelec.length;i++)
            if(form.idTranferenciaSelec[i].checked) 
				idSelecionado = form.idTranferenciaSelec[i].value;

			if(idSelecionado > 0) {		   
	            var resposta = window.confirm("Deseja cancelar esta transferência?");
				
                if (resposta) {
	                form.task.value = 'excluirTranferenciadeRubrica';
    	            form.idTranferenciadeRubrica.value = idSelecionado;
                    form.submit();
                }
			} else {
		       alert('Selecione o item a ser excluído!')
			}
	    }

		function editar(form) {
			var idSelecionado = 0;
			
			for(i = 0;i < form.idTranferenciadeRubricaSelec.length;i++)
			if(form.idTranferenciadeRubricaSelec[i].checked) 
				idSelecionado = form.idTranferenciadeRubricaSelec[i].value;
				
			if(idSelecionado > 0) {
				form.task.value = 'editarTranferenciadeRubrica';
				form.idTranferenciaSelec.value = idSelecionado;
				form.submit();
			} else {
				alert('Selecione o item a ser editado!')
			}
		}

		function visualizar(form) {
			var idSelecionado = 0;
			
			for(i = 0;i < form.idTranferenciadeRubricaSelec.length;i++)
			if(form.idTranferenciadeRubricaSelec[i].checked) 
				idSelecionado = form.idTranferenciadeRubricaSelec[i].value;
				
			if(idSelecionado > 0) {
				form.task.value = 'verTranferenciadeRubrica';//volta para visualizar projeto
				form.idTranferenciadeRubrica.value = idSelecionado;
				form.submit();
			} else {
				alert('Selecione o item a ser  visualizado.')
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
    
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/estilo.css"/>
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
        
<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" >

    <!-- Barra ferramentas -->
	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
    	<div class="cpanel2">
			<div class="icon" id="toolbar-new">
           		<a href="javascript:document.form.task.value='addTranferenciadeRubrica';document.form.submit()">
           		<span class="icon-32-new"></span><?php echo JText::_( 'Novo' ); ?></a>
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
        <div class="pagetitle icon-48-contact"><h2>Transferência de Saldo de Rubricas</h2></div>
    </div></div>    
    
    <?php exibirProjeto($projetopd); ?> 

	<hr />
    
    <!-- Formulario -->
	<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters" class="tabela">
		<thead style="color:#FFC000; text-align:center;">
      		<tr>
            	<th></th>
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
                
                <td><input type="radio" name="idTranferenciaSelec" value="<?php echo $transferencia->id; ?>" /></td>
                <td><?php echo dataBr($transferencia->data);?></td>
                <td><?php echo $transferencia->rubricaOrigem;?></td>
                <td><?php echo $transferencia->rubricaDestino;?></td>
                <td><?php echo moedaBr($transferencia->valor);?></td>
                <td><?php echo $transferencia->autorizacao;?></td>
           <?php } ?>
		</tbody>
	</table>
 
    <br />
	Transferências efetuadas: <b><?php echo sizeof($gerenciarTranferenciadeRubricas);?></b>
    <input name='task' type='hidden' value='gerenciarTranferenciadeRubricas' />
    <input name='idProjetopd' type='hidden' value='<?php echo $idProjeto;?>' />
    <input name='idProjeto' type='hidden' value='<?php echo $idProjeto;?>' />
    <input name='idTranferenciaSelec' type='hidden' value='0' />
    <input name='idTranferenciadeRubrica' type='hidden' value='' />

</form>

<?php }


// FORMULÁRIO PARA CADASTRO DE TRANSFERENCIAS
function addTranferenciadeRubrica($TranferenciadeRubrica, $idProjeto) {
    $Itemid = JRequest::getInt('Itemid', 0);
	
    $database = JFactory::getDBO();
	
    $projetopd = identificarProjetopdID($idProjeto);
	
  	$sql = "SELECT rubprojeto.id, rubricas.nome, rubprojeto.descricao, rubprojeto.valor_total, rubprojeto.valor_disponivel
			FROM j17_contproj_rubricasdeprojetos AS rubprojeto, j17_contproj_rubricas AS rubricas
			WHERE rubprojeto.rubrica_id = rubricas.id
			AND rubprojeto.projeto_id = '".$idProjeto."'";			 
    $database->setQuery($sql);
    $gerenciarRubricadeprojetos = $database->loadObjectList(); ?>
    
	<!--Valida dados no formulario-->
	<script language="JavaScript">
		function IsEmpty(aTextField){
			if ((aTextField.value.length==0) || (aTextField.value==null) ) {
		    	return true;
			} else { 
				return false; 
			}
		}

		function radio_button_checker(elem){
			var radio_choice = false;
	
			for (counter = 0; counter < elem.length; counter++) {		
				if (elem[counter].checked)
					radio_choice = true;
			}
				
			return (radio_choice);
		}

		function IsNumeric(sText) {
			var ValidChars = "0123456789.";
			var IsNumber=true;
			var Char;
			
			if (sText.length <= 0) {
				IsNumber = false;
			}
			
			for (i = 0; i < sText.length && IsNumber == true; i++) {
				Char = sText.charAt(i);
				
				if (ValidChars.indexOf(Char) == -1) {
					IsNumber = false;
				}
			}
			
			return IsNumber;
		}

		function ValidateformCadastro(formCadastro){
		   if(IsEmpty(formCadastro.rubrica_origem)){
			  alert('O campo rubrica de origem deve ser preenchido.')
			  formCadastro.rubrica_origem.focus();
			  return false;
		   } 
		   if(IsEmpty(formCadastro.rubrica_destino)){
			  alert('O campo rubrica de destino deve ser preenchido.')
			  formCadastro.rubrica_destino.focus();
			  return false;
		   }
		   if(formCadastro.rubrica_origem.value == formCadastro.rubrica_destino.value){
			  alert('O campo rubrica de origem deve ser diferente da rubrica de destino.')
			  formCadastro.rubrica_destino.focus();
			  return false;
		   }
		   
		   if(IsEmpty(formCadastro.valor)){
			  alert('O campo valor da transferência deve ser preenchido.')
			  formCadastro.valor.focus();
			  return false;
		   }
		   if(IsEmpty(formCadastro.data)){
			  alert('O campo data deve ser preenchido.')
			  formCadastro.data.focus();
			  return false;
		   } 
		   if(IsEmpty(formCadastro.autorizacao)){
			  alert('O campo autorização deve ser preenchido.')
			  formCadastro.autorizacao.focus();
			  return false;
		   }
		  
		return true;
		}
		
		
		function voltarForm(form){
		   form.task.value = 'gerenciarTranferenciadeRubricas';
		   form.submit();
		   return true;
		}
	</script>

    <!--Recurso de data interativa  (os componentes Jquery, a funcao e a linha de comando para chamada da funcao)-->    
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>  

	<script type="text/javascript" src="components/com_controleprojetos/jquery.tablesorter.js"></script>
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
            $( "#data" ).datepicker({dateFormat: 'dd/mm/yy'});
    
        });
    </script>
    
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();
		});
	</script>

	<script src="components/com_controleprojetos/jquery.maskMoney.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(function(){
			$("#valor").maskMoney({thousands:'.', decimal:',', symbolStay: true});
		})
    </script>
    <script type="text/javascript" src="components/com_controleprojetos/scripts.js"></script>
    
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/estilo.css"/>
	<link type="text/css" href="components/com_controleprojetos/jquery-ui-1.10.1.custom/css/ui-lightness/jquery-ui-1.10.1.custom.css" rel="Stylesheet" />

	<form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateformCadastro(this)">
    
		<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
			<div class="cpanel2">
				<div class="icon" id="toolbar-save">
                	<a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
              		<span class="icon-32-save"></span>Salvar</a>
	            </div>
                
				<div class="icon" id="toolbar-cancel">
        	    	<a href="javascript:voltarForm(document.formCadastro)">
        	       <span class="icon-32-cancel"></span>Cancelar</a>
			    </div>
			</div>
            
	        <div class="clr"></div>
    	    </div>
        	<div class="pagetitle icon-48-cpanel"><h2>Transferências de Saldo de Rubricas</h2></div>
       		</div>
	    </div>
        
		<hr />       
         
		<h4>Rubricas Disponíveis</h4>

		<!-- Formulario -->
		<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
			<thead style="color:#FFC000; text-align:center;">
                <tr>
                    <th width="20%">Rubrica</th>
                    <th width="30%">Descrição</th>
                    <th width="6%">Valor Previsto</th>
                    <th width="6%">Valor Disponivel</th>
                </tr>
			</thead>
			<tbody>
			<?php
            $table_bgcolor_even="#e6e6e6";
            $table_bgcolor_odd="#FFFFFF";
            $i = 0;
			
            foreach($gerenciarRubricadeprojetos as $rubricas) {
				
				//$saldoRubrica = saldoRubrica($rubricas->id);
				
	            $i = $i + 1;
				if ($i % 2) {
            		echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
	            } else {
		            echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
        	    } ?>
                
                <td><?php echo $rubricas->nome;?></td>
                <td><?php echo $rubricas->descricao;?></td>
                <td><?php echo moedaBr($rubricas->valor_total);?></td>
                <td><?php echo moedaBr($rubricas->valor_disponivel);?></td>
                
            <?php } ?>
			</tbody>
		</table>	
        
		<hr />

		<b>Como proceder: </b>
		<ul><li>Preencha todos os campos com os dados da Rubrica <font color="#FF0000">(* Campos Obrigatórios)</font>.</li></ul>
        
		<table width="100%">
	        <tr style="background-color: #7196d8;">
               <td style="color:#FFF;" colspan="2"><b>Informações</b></td>
            </tr>
            <tr>
                <input name='id' type='hidden' value="NULL"><!--referencia o ID visualizacao-->
                <input name='projeto_id' type='hidden' value='<?php echo $idProjeto;?>'><!--referencia o ID do PROJETO para novo-->
                <input name='rubricasdeprojetos_id' type='hidden' value='<?php echo $idProjeto;?>'><!--referencia o ID do PROJETO para novo-->
            </tr>
            <tr>
                <td width="19%" class="tbItemForm"><span>*</span> Rubrica Origem:</td>
                <td width="81%" colspan="2">
                    <select name="rubrica_origem" class="inputbox">
                        <option value=""></option>
                       <?php 
							$sql = "SELECT rubProjetos.id, rubricas.codigo, rubricas.nome
									FROM j17_contproj_rubricasdeprojetos AS rubProjetos, j17_contproj_rubricas AS rubricas
									WHERE rubricas.id = rubProjetos.rubrica_id
									AND rubProjetos.projeto_id = '$idProjeto'
									AND valor_disponivel > 0"; // '> 0' SÓ LISTAS AS RUBRICAS DISPONÍVEIS
                        	$database->setQuery($sql);
                        	$resultado = $database->loadObjectList();
                        
                        	foreach($resultado as $rubricasOrigem) { ?> 
                            <option value="<?php echo $rubricasOrigem->id;?>"><?php echo $rubricasOrigem->codigo;?> : <?php echo $rubricasOrigem->nome;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr> 
            <tr>
                <td class="tbItemForm"><span>*</span> Rubrica Destino:</td>
                <td colspan="2">
                    <select name="rubrica_destino" class="inputbox">
                        <option value=""></option>
                        <?php 
                        $database->setQuery("SELECT RP.id , RP.projeto_id, P.nomeprojeto, RP.rubrica_id, R.codigo, R.nome, RP.descricao, RP.valor_total
                                          FROM j17_contproj_rubricasdeprojetos AS RP
                                          INNER JOIN j17_contproj_rubricas AS R
                                              ON RP.rubrica_id = R.id  
                                          INNER JOIN j17_contproj_projetos AS P
                                              ON RP.projeto_id = P.id     
                                          WHERE  projeto_id LIKE '$idProjeto'");
                         $rubrica_listas = $database->loadObjectList();
                         
                         foreach($rubrica_listas as $rubricasDestino) { ?> 
                            <option value="<?php echo $rubricasDestino->id;?>"><?php echo $rubricasDestino->codigo;?> : <?php echo $rubricasDestino->nome;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr> 
            <tr>
                <td class="tbItemForm"><span>*</span> Valor de Troca:</td>
                <td><input type="text" maxlength="15" size="15" id="valor" name="valor" class="inputbox" onKeyPress="return(MascaraMoeda(this,'.',',',event))" /></td>
            </tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Data:</td>
                <td><input type="text" size="15" id="data" name="data" class="inputbox" /></td>
            </tr>
            <tr>
				<td class="tbItemForm"><span>*</span> Autorização:</td>
            	<td><input type="text" maxlength="50" size="50" name="autorizacao" class="inputbox" /></td>
            </tr>     
        </table>    
    
        <input name='idTranferenciadeRubrica' type='hidden' value='<?php if($TranferenciadeRubrica) echo $TranferenciadeRubrica->id;?>' />
        <input name='idProjeto' type='hidden' value='<?php echo $idProjeto; ?>' />  
        <input name='task' type='hidden' value='salvarTranferenciadeRubrica' />
        
	</form>

<?php } 


// SALVAR TRANSFERENCIAS
function salvarTranferenciadeRubrica($idTranferenciadeRubrica = "", $idProjeto) {
	$database = JFactory::getDBO();
	
	$id = JRequest::getVar('id');
	$rubrica_origem = JRequest::getVar('rubrica_origem');
	$rubrica_destino = JRequest::getVar('rubrica_destino');
	$valor = moedaSql(JRequest::getVar('valor'));
	$data = dataSql(JRequest::getVar('data'));
	$autorizacao = JRequest::getVar('autorizacao');

	//$saldo = saldoRubrica($rubrica_origem);
	
	$sql = "SELECT valor_disponivel 
			FROM #__contproj_rubricasdeprojetos
			WHERE id = '".$rubrica_origem."'
			AND projeto_id = '".$idProjeto."'";
    $database->setQuery($sql);
    $res = $database->loadObjectList();
	foreach ($res as $resSaldo)
		$saldo = moedaSql($resSaldo->valor_disponivel);
	
	if ($valor <= $saldo) { 	
		$sql = "INSERT INTO #__contproj_transferenciassaldorubricas (projeto_id, rubrica_origem, rubrica_destino, valor, data, autorizacao) VALUES ($idProjeto, $rubrica_origem, $rubrica_destino, $valor, '$data', '$autorizacao')";
		$database->setQuery($sql);
		$funcionou = $database->Query(); 

		if ($funcionou) { 	
			// DESPESA NA RUBRICA DE ORIGEM
			//$sqlDesp = "INSERT INTO #__contproj_despesas (rubricasdeprojetos_id, descricao, tipo_pessoa, valor_despesa, data_emissao, favorecido) VALUES ($rubrica_origem, 'Transferência de Saldo', 'Jurídica', $valor, '$data', 'Transferência de Saldo')";
			//$database->setQuery($sqlDesp);	
			//$database->Query();
			
			// ATUALIZAR SALDO DA RUBRICA DO PROJETO NA RUBRICA DE ORIGEM
			$sqlOrigem = "SELECT valor_gasto, valor_disponivel FROM #__contproj_rubricasdeprojetos WHERE id = $rubrica_origem";
			$database->setQuery($sqlOrigem);
			$resultado = $database->loadObjectList();
			foreach ($resultado as $r)
				//$valorGasto = $r->valor_gasto;
				$valorDisponivel = $r->valor_disponivel;
				
				//$novoValorGasto = $valorGasto + $valor;			
				$novoValorDisp = $valorDisponivel - $valor;
				
				//$sqlUpdate = "UPDATE #__contproj_rubricasdeprojetos SET valor_gasto = '$novoValorGasto', valor_disponivel = '$novoValorDisp' WHERE id = '$rubrica_origem'";
				$sqlUpdate = "UPDATE #__contproj_rubricasdeprojetos SET valor_disponivel = '$novoValorDisp' WHERE id = '$rubrica_origem'";
				$database->setQuery($sqlUpdate);
				$foi = $database->Query();
				
			// RECEITA NA RUBRICA DE DESTINO
		//	$sqlRec = "INSERT INTO #__contproj_receitas (rubricasdeprojetos_id, descricao, valor_receita, data)   VALUES ($rubrica_destino, 'Transferência de Saldo', $valor, '$data')";
		//	$database->setQuery($sqlRec);	
		//	$database->Query(); 
			
			// ATUALIZAR SALDO DA RUBRICA DO PROJETO NA RUBRICA DE DESTINO
			$sqlDest = "SELECT valor_disponivel FROM #__contproj_rubricasdeprojetos WHERE id = $rubrica_destino";
			$database->setQuery($sqlDest);
			$resultado2 = $database->loadObjectList();
			
			foreach ($resultado2 as $r2)
				$valorDisponivel2 = $r2->valor_disponivel;
				$novoValorDisp2 = $valorDisponivel2 + $valor;
				
				$sqlUpdate = "UPDATE #__contproj_rubricasdeprojetos SET valor_disponivel = '$novoValorDisp2' WHERE id = '$rubrica_destino'";
				$database->setQuery($sqlUpdate);
				$foi2 = $database->Query();
			
			 if ($foi2) {
				 
				JFactory::getApplication()->enqueueMessage(JText::_('Transferência de saldo realizada com sucesso!'));
				listarTranferenciadeRubricas();
				return 1;    
            
			} else { 
			
				JError::raiseWarning( 100, 'Cadastro não realizado!' );
				addTranferenciadeRubrica(NULL, $idProjeto);
				return 0;
            
			}
        
		} else { 
		
			JError::raiseWarning( 100, 'ERRO: Você não pode realizar esta transferência. O valor de troca é maior que o valor disponível!' );		
			addTranferenciadeRubrica(NULL, $idProjeto);
		}
	
	} else { 
		JError::raiseWarning( 100, 'ERRO: O valor de troca não pode ser maior que o valor disponível!' );			
	    addTranferenciadeRubrica(NULL, $idProjeto);
	}
	
}


// EXCLUIR TRANSFERENCIAS
function excluirTranferenciadeRubrica ($idTranferenciadeRubrica) {
	$database = JFactory::getDBO();

	$sqlRubrica = "SELECT * FROM #__contproj_transferenciassaldorubricas WHERE id = '".$idTranferenciadeRubrica."'";
	$database->setQuery($sqlRubrica);
   	$transf = $database->loadObjectList();
	
	foreach ($transf as $t) {
		$rubrica_origem = $t->rubrica_origem;
		$rubrica_destino = $t->rubrica_destino;
		$valorTransf = $t->valor;
	
		$sqlValor = "SELECT valor_gasto, valor_disponivel FROM #__contproj_rubricasdeprojetos WHERE id = '".$rubrica_origem."'";
		$database->setQuery($sqlValor);
		$resultado = $database->loadObjectList();		
		foreach ($resultado as $res) 
			$valorGasto = $res->valor_gasto;
			$valorDisponivel = $res->valor_disponivel;
						
			$valorGastoNovo = $valorGasto - $valorTransf;
			$valorDisponivelNovo = $valorDisponivel + $valorTransf;
			
			// ACRESCENTAR SALDO NA RUBRICA DE ORIGEM
			$sqlUpdate = "UPDATE #__contproj_rubricasdeprojetos SET valor_gasto = '$valorGastoNovo', valor_disponivel = '$valorDisponivelNovo' WHERE id = '".$rubrica_origem."'";
			$database->setQuery($sqlUpdate);
			$foi = $database->Query();	

			// EXCLUSÃO DA DESPESA NA RUBRICA DE ORIGEM
			$sqlDespesa = "DELETE FROM #__contproj_despesas WHERE rubricasdeprojetos_id = '".$rubrica_origem."' AND descricao = 'Transferência de Saldo'";
			$database->setQuery($sqlDespesa);
			$funcionouD = $database->Query();

		$sqlValor2 = "SELECT valor_disponivel FROM #__contproj_rubricasdeprojetos WHERE id = '".$rubrica_destino."'";
		$database->setQuery($sqlValor2);
		$resultado2 = $database->loadObjectList();		
		foreach ($resultado2 as $res2) 
			$valorDisponivel2 = $res2->valor_disponivel;
			
			$valorDisponivelNovo2 = $valorDisponivel2 - $valorTransf;

			// DIMINUIR SALDO NA RUBRICA DE DESTINO
			$sqlUpdate2 = "UPDATE #__contproj_rubricasdeprojetos SET valor_disponivel = '$valorDisponivelNovo2' WHERE id = '".$rubrica_destino."'";
			$database->setQuery($sqlUpdate2);
			$foi2 = $database->Query();		
		
			// EXLUSÃO DA RECEITA NA RUBRICA DE DESTINO
			$sqlReceita = "DELETE FROM #__contproj_receitas WHERE rubricasdeprojetos_id = '".$rubrica_destino."' AND descricao = 'Transferência de Saldo'";
			$database->setQuery($sqlReceita);
			$funcionouR = $database->Query();
		
				
		// EXCLUSÃO DA TRANSFERENCIA DE SALDO
		$sql = "DELETE FROM #__contproj_transferenciassaldorubricas WHERE id = '".$idTranferenciadeRubrica."'";
		$database->setQuery($sql);
		$funcionou = $database->Query();
		
		if ($funcionou) {
			JFactory::getApplication()->enqueueMessage(JText::_('Transferência excluída com sucesso!'));
			return 1;
		} else {
			JError::raiseWarning( 100, 'Erro ao tentar excluir a transferência!' );
			return 0;
		}
	}
}


// CALCULAR SALDO DAS RUBRICAS PARA TRANSFERENCIAS
function saldoRubrica($rubrica_id) {
	$database = JFactory::getDBO();
	
	$sqlRubrica = "SELECT rubricas.projeto_id
				FROM j17_contproj_rubricasdeprojetos AS rubricas
				WHERE rubricas.id = '".$rubrica_id."'";
	$database->setQuery($sqlRubrica);
	$rubricas = $database->loadObjectList();
	
	foreach ($rubricas as $rubrica) { 	
		$sqlReceita = "SELECT receitas.id, receitas.rubricasdeprojetos_id, receitas.descricao, receitas.valor_receita
					FROM j17_contproj_receitas AS receitas, j17_contproj_rubricasdeprojetos AS rubricas
					WHERE receitas.rubricasdeprojetos_id = rubricas.id
					AND rubricas.id = '".$rubrica_id."'
					AND rubricas.projeto_id = '".$rubrica->projeto_id."'";
		$database->setQuery($sqlReceita);
		$resultado = $database->loadObjectList();
		
		$qtdReceitas = sizeof($resultado);
		
		$receitas = 0;
		$despesas = 0;
		$saldoRubrica = 0;
	
		foreach ($resultado as $r) {			
			$receitas = $r->valor_receita;
					
			$sqlDespesa = "SELECT receitas.rubricasdeprojetos_id, SUM(despesas.valor_despesa) as totalDespesas
						FROM j17_contproj_despesas AS despesas, j17_contproj_receitas AS receitas, j17_contproj_rubricasdeprojetos AS rubricas
						WHERE despesas.rubricasdeprojetos_id = receitas.rubricasdeprojetos_id
						AND receitas.rubricasdeprojetos_id = rubricas.id
						AND receitas.rubricasdeprojetos_id = '".$r->rubricasdeprojetos_id."'
						AND rubricas.projeto_id = '".$rubrica->projeto_id."'
						GROUP BY receitas.rubricasdeprojetos_id";
			$database->setQuery($sqlDespesa);
			$dados = $database->loadObjectList();
			
			$qtdDespesas = sizeof($dados);		
			
			if ($qtdDespesas == 1) {			
				foreach ($dados as $d) {
					$despesas = $d->totalDespesas;
					$saldoRubrica = $r->valor_receita - $d->totalDespesas;								
				}				
			} elseif ($qtdDespesas == 0) {				
				$despesas = $despesas;
				$saldoRubrica = $r->valor_receita;				
			}	
		}	
	}	
	
	return $saldoRubrica;	
}


// Identifica o id do registro selecionado pelo radio button
function identificarTranferenciadeRubricaID($idTranferenciadeRubrica){
    $database	= JFactory::getDBO();
    $sql = "SELECT * FROM #__contproj_rubricasdeprojetos WHERE id = $idTranferenciadeRubrica LIMIT 1";
    $database->setQuery( $sql );
    $TranferenciadeRubrica = $database->loadObjectList();
	
    return ($TranferenciadeRubrica[0]);
}
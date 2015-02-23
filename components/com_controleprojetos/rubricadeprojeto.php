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

// LISTAGEM DE RUBRICAS
function listarRubricadeprojetos($descricao = ""){
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
    $idProjeto = JRequest::getVar('idProjeto');
    $projetopd = identificarProjetopdID($idProjeto);
    $sql = " SELECT RP.id, P.nomeprojeto, R.nome,RP.descricao, RP.valor_total, RP.valor_gasto, RP.valor_disponivel
             FROM #__contproj_rubricasdeprojetos AS RP 
             INNER JOIN #__contproj_rubricas AS R
                 ON RP.rubrica_id = R.id  
             INNER JOIN #__contproj_projetos AS P
                 ON RP.projeto_id = P.id 
             WHERE  projeto_id LIKE '$idProjeto' AND descricao LIKE '%$descricao%' ORDER BY nome";//idprojeto->identifica o projeto
    $database->setQuery( $sql );
    $gerenciarRubricadeprojetos = $database->loadObjectList(); ?>

    <!--Funcionalidades dos formularios (Excluir, Editar e Visualizar) --> 
    <script language="JavaScript">
		function excluir(form) {
			var idSelecionado = 0;
			
	        for(i = 0;i < form.idRubricadeprojetoSelec.length;i++)
            if(form.idRubricadeprojetoSelec[i].checked) 
				idSelecionado = form.idRubricadeprojetoSelec[i].value;
				
			if(idSelecionado > 0) {		   
	            var resposta = window.confirm("Confirme a exclusão do item.");
                if(resposta) {
	                form.task.value = 'excluirRubricadeprojeto';
    	            form.idRubricadeprojeto.value = idSelecionado;
                    form.submit();
                }
			} else {
		       alert('Selecione o item a ser excluído!')
			}
	    }
    
		function editar(form) {
			var idSelecionado = 0;
			
			for(i = 0;i < form.idRubricadeprojetoSelec.length;i++)
			if(form.idRubricadeprojetoSelec[i].checked) 
				idSelecionado = form.idRubricadeprojetoSelec[i].value;
				
			if(idSelecionado > 0) {
				form.task.value = 'editarRubricadeprojeto';
				form.idRubricadeprojeto.value = idSelecionado;
				form.submit();
			} else {
				alert('Selecione o item a ser editado!')
			}
		}
    
    	function voltarNivelForm(form){
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
           		<a href="javascript:document.form.task.value='addRubricadeprojeto';document.form.submit()">
                <span class="icon-32-new"></span><?php echo JText::_( 'Novo' ); ?></a>
            </div>
            
            <div class="icon" id="toolbar-edit">
            	<a href="javascript:editar(document.form)">
                <span class="icon-32-edit"></span><?php echo JText::_( 'Editar' ); ?></a>
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
        
		<div class="pagetitle icon-48-contact"><h2>Rubricas</h2></div>
    </div></div>

    <?php exibirProjeto($projetopd);?> 

    <hr />

    <!-- Filtro de busca -->	
	<table border="0" cellpadding="0" cellspacing="0" width="50%">
    	<tr>
        	<td>Filtro por descrição:</td>
	        <td><input id="buscaDescricao" name="buscaDescricao" size="30" type="text" value="<?php echo $descricao;?>"/></td>
   	    	<td rowspan="2"><input type="submit" value="Buscar"></td>
		</tr>
    </table>
    
    <!-- Formulario -->    
    <table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
        <thead style="color:#FFC000; text-align:center;">
            <tr>
                <th width="3%"></th>
                <th width="20%">Rubrica</th>
                <th width="30%">Descrição</th>
                <th width="6%">Valor Previsto</th>
                <th width="6%">Valor Gasto</th>
                <th width="6%">Valor Disponível</th>
            </tr>
		</thead>
     	<tbody>
        	<tr>
			<?php    
            if (count($gerenciarRubricadeprojetos) == 0) {
                
            } else {
            
                $table_bgcolor_even="#e6e6e6";
                $table_bgcolor_odd="#FFFFFF";
                $i = 0; 
				
                foreach ($gerenciarRubricadeprojetos as $rubricas) {
                    $i = $i + 1;
                    if ($i % 2) {
                        echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
                    } else {
                        echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
                    } ?>
                    
                    <td width='16'><input type="radio" name="idRubricadeprojetoSelec" value="<?php echo $rubricas->id;?>" /></td>    
                    <td><?php echo $rubricas->nome;?></td>
                    <td><?php echo $rubricas->descricao;?></td>
                    <td align="right"><?php echo moedaBr($rubricas->valor_total);?></td>
                    <td align="right"><?php echo moedaBr($rubricas->valor_gasto);?></td>
                    <td align="right"><?php echo moedaBr($rubricas->valor_disponivel);?></td>
            <?php } ?>
            </tr>    
            <tr>
            <?php
                $sql2 = "SELECT SUM(valor_total) AS SomaVLtotal , SUM(valor_gasto) AS SomaVLgasto ,
                        SUM(valor_disponivel)AS SomaVLdisponivel  FROM #__contproj_rubricasdeprojetos
                        WHERE projeto_id LIKE '$idProjeto'";
                $database->setQuery($sql2);
                $sqlsomacoluna = $database->loadObjectList();
                
                foreach ($sqlsomacoluna as $sqlsomacolunaf) { ?>
                    <td width="53%" align="left" colspan="3"></td>  
                    <td width="6%" align="right"><b><i><?php echo moedaBr($sqlsomacolunaf->SomaVLtotal);?></i></b></td>
                    <td width="6%" align="right"><b><i><?php echo moedaBr($sqlsomacolunaf->SomaVLgasto);?></i></b></td>
                    <td width="6%" align="right"><b><i><?php echo moedaBr($sqlsomacolunaf->SomaVLdisponivel);?></i></b></td>
            <?php } } ?> 
            </tr>
     	</tbody>
     </table>
        
     <br />
     <span>Total de Rubricas: <b><?php echo sizeof($gerenciarRubricadeprojetos);?></b></span>
     <input name='task' type='hidden' value='gerenciarRubricadeprojetos' />
     <input name='idProjetopd' type='hidden' value='<?php echo $idProjeto;?>' />
     <input name='idProjeto' type='hidden' value='<?php echo $idProjeto;?>' />
     <input name='idRubricadeprojetoSelec' type='hidden' value='0' />
     <input name='idRubricadeprojeto' type='hidden' value='' />

</form>

<?php } 


// FORMULARIO PARA CADASTRO DE RUBRICA
function addRubricadeprojeto($Rubricadeprojeto = NULL, $descricao, $idProjeto){
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
    
		function ValidateformCadastro(formCadastro) {
		   if(IsEmpty(formCadastro.rubrica_id)){
			  alert('O campo rubrica deve ser preenchido.')
			  formCadastro.rubrica_id.focus();
			  return false;
		   } 
		   if(IsEmpty(formCadastro.descricao)) {
			  alert('O campo descrição deve ser preenchido.')
			  formCadastro.descricao.focus();
			  return false;
		   }
		   if(IsEmpty(formCadastro.valor_total)) {
			  alert('O campo valor da rubrica deve ser preenchido.')
			  formCadastro.valor_total.focus();
			  return false;
		   }
		   if(IsEmpty(formCadastro.valor_disponivel)) {
			  alert('O campo valor disponivel deve ser preenchido.')
			  formCadastro.valor_disponivel.focus();
			  return false;
		   }
		   
		   var disponivel = parseFloat(converteMoedaFloat(formCadastro.valor_disponivel.value));
		   var total = parseFloat(converteMoedaFloat(formCadastro.valor_total.value));
		   
		   if( disponivel > total ){
			  alert('O campo valor disponivel deve ser menor ou igual ao valor total.')
			  formCadastro.valor_disponivel.focus();
			  return false;
		   }
		   
			return true;
		}
    
		function converteMoedaFloat(valor) {		
			if(valor === "") {
				valor =  0;
			} else {
		 		valor = valor.replace(".","");
				valor = valor.replace(",",".");
		 		valor = parseFloat(valor);
			}
			
			return valor;		
		}
    
    
		function voltarForm(form) {
			form.task.value = 'gerenciarRubricadeprojetos';
			form.submit();
			return true;
		}
    </script>
    
    <link type="text/css" href="components/com_controleprojetos/jquery-ui-1.10.1.custom/css/ui-lightness/jquery-ui-1.10.1.custom.css" rel="Stylesheet" />
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>  
    <script src="components/com_controleprojetos/jquery.maskMoney.js" type="text/javascript"></script>
    
    <script type="text/javascript">
        $(function(){
            $("#valor_disponivel").maskMoney({thousands:'.', decimal:',', symbolStay: true});
        })
    </script>
    
    <script type="text/javascript">
        $(function(){
            $("#valor_total").maskMoney({thousands:'.', decimal:',', symbolStay: true});
        })
    </script>
    
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
    <script type="text/javascript" src="components/com_controleprojetos/scripts.js"></script>

    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    
	<form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateformCadastro(this)">
    
    	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
			<div class="cpanel2">
				<div class="icon" id="toolbar-save">
                    <!-- A opcao abaixo usa o recurso de validacao do formulario-->
                    <a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
                    <!--A opcao abaixo acessa o formulario de forma direta-->
                    <!--<a href="javascript:document.formCadastro.task.value='salvarRubricadeprojeto';document.formCadastro.submit()">-->
           			<span class="icon-32-save"></span>Salvar</a>
				</div>
                
                <div class="icon" id="toolbar-cancel">
           			<a href="javascript:voltarForm(document.formCadastro)"><span class="icon-32-cancel"></span>Cancelar</a>
		    	</div>
                </div>
                
                <div class="clr"></div>
                </div>
                <div class="pagetitle icon-48-cpanel"><h2>Cadastro de Rubricas</h2></div>
			</div>
		</div>
        
		<b>Como proceder: </b>
		<ul><li>Preencha todos os campos com os dados da Rubrica <font color="#FF0000">(* Campos Obrigatórios)</font>.</li></ul>
        
		<hr />
        
		<table width="100%">
            <tr style="background-color: #7196d8;">
               <td colspan="4"><font color="#FFFFFF"><b>Informações</b></font></td>
            </tr>
            <tr>
                <input name='id' type='hidden' value="NULL"><!--referencia o ID visualizacao-->
                <input name='projeto_id' type='hidden' value='<?php echo $idProjeto;?>'><!--referencia o ID do PROJETO para novo-->
            </tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Rubrica:</td>
                <td colspan="2">
                    <select name="rubrica_id" class="inputbox">
                        <option value=""></option>
                            <?php //Acessa a tabela rubricas para exibir em list box
                                 $database->setQuery("SELECT * from #__contproj_rubricas  ORDER  BY nome");
                                 $rubrica_listas = $database->loadObjectList();
                                 foreach($rubrica_listas as $rubrica_final_listas) { ?> 
                                 	<option value="<?php echo $rubrica_final_listas->id;?>"><?php echo $rubrica_final_listas->codigo;?> - <?php echo $rubrica_final_listas->nome;?>
							<?php } ?>
						</option>
                    </select>
                 </td>
            </tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Descrição:</td>
                <td><textarea rows="4" cols="60" name="descricao"/></textarea></td>
            </tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Valor Previsto:</td>
                <td><input type="text" id="valor_total" name="valor_total" size="15" class="inputbox" onKeyPress="return(MascaraMoeda(this,'.',',',event))" /></td>
            </tr> 
            <tr>
                <td class="tbItemForm"><span>*</span> Saldo Inicial:</td>
                <td><input type="text" id="valor_disponivel" name="valor_disponivel" size="15" class="inputbox" onKeyPress="return(MascaraMoeda(this,'.',',',event))" /></td>
            </tr>    
   		</table>      

        <input name='idProjeto' type='hidden' value='<?php echo $idProjeto;?>' />
        <input name='task' type='hidden' value='salvarRubricadeprojeto' />
        <input name='buscaDescricao' type='hidden' value='<?php echo $descricao;?>' />
        
    </form>
    
<?php }


// FORMULÁRIO PARA EDIÇÃO DE RUBRICAS
function editarRubricadeprojeto($Rubricadeprojeto = NULL, $descricao){
    $database	= JFactory::getDBO();
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
    
		function radio_button_checker(elem) {
			var radio_choice = false;

			for (counter = 0; counter < elem.length; counter++) {
				if (elem[counter].checked)
					radio_choice = true;
			}
			
			return (radio_choice);
		}
    
		function IsNumeric(sText){
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
    
		function ValidateformCadastro(formCadastro) {
			if(IsEmpty(formCadastro.descricao)){
				alert('O campo descrição deve ser preenchido.')
				formCadastro.descricao.focus();
				return false;
			}
		
			if(IsEmpty(formCadastro.valor_total)) {
				alert('O campo valor da rubrica deve ser preenchido.')
				formCadastro.valor_total.focus();
				return false;
			}
		
			return true;
		}
        
		function voltarForm(form) {
			form.task.value = 'gerenciarRubricadeprojetos';
			form.submit();
			return true;
		}
    </script>

    <!--Recurso de data interativa  (os componentes Jquery, a funcao e a linha de comando para chamada da funcao)-->    
    <link type="text/css" href="components/com_controleprojetos/jquery-ui-1.10.1.custom/css/ui-lightness/jquery-ui-1.10.1.custom.css" rel="Stylesheet" />
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>  
    <script src="components/com_controleprojetos/jquery.maskMoney.js" type="text/javascript"></script>
    
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
    
    <script type="text/javascript">
        $(function(){
            $("#valor_total").maskMoney({thousands:'.', decimal:',', symbolStay: true});
        })
    </script>

    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">

	<form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateformCadastro(this)">
    
    	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
			<div class="cpanel2">
				<div class="icon" id="toolbar-save">
                    <!-- A opcao abaixo usa o recurso de validacao do formulario-->
                    <a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
                    <!--A opcao abaixo acessa o formulario de forma direta-->
                    <!--<a href="javascript:document.formCadastro.task.value='salvarRubricadeprojeto';document.formCadastro.submit()">-->
            		<span class="icon-32-save"></span>Salvar</a>
				</div>
                
				<div class="icon" id="toolbar-cancel">
           			<a href="javascript:voltarForm(document.formCadastro)"><span class="icon-32-cancel"></span>Cancelar</a>
			    </div>
                
			</div>
            <div class="clr"></div>
		    </div>
            <div class="pagetitle icon-48-cpanel"><h2>Edicão de Rubricas</h2></div>
           </div>
        </div>
        
        <b>Como proceder: </b>
        <ul><li>Edite os dados do projeto <font color="#FF0000">(* Campos Obrigatórios)</font>.</li></ul>
        
        <hr />
    
   		<input name='id' type='hidden' value="<?php echo $Rubricadeprojeto->id;?>">
   
		<table width="100%">
	        <tr style="background-color: #7196d8;">
               <td style="color:#FFF;" colspan="2"><b>Informações</b></td>
            </tr>
            <tr>
                <td class="tbItemForm">Rubrica:</td>
                <td><input type="text" size="80" class="inputbox" value="<?php echo $Rubricadeprojeto->codigo;?> - <?php echo $Rubricadeprojeto->nome;?>" disabled="disabled" /></td>
            </tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Descrição:</td>
				<td><textarea rows="4" cols="60" name="descricao"/><?php echo $Rubricadeprojeto->descricao;?></textarea></td>				
            </tr> 
            <tr>
                <td class="tbItemForm"><span>*</span> Valor Previsto:</td>
                <td><input type="text" size="15" id="valor_total" name="valor_total" class="inputbox" value="<?php echo moedaBr($Rubricadeprojeto->valor_total);?>"></td>
            </tr>
	   </table>
       
        <label id="info"></label>
        <input name='idRubricadeprojeto' type='hidden' value='<?php if($Rubricadeprojeto) echo $Rubricadeprojeto->id;?>' />
        <input name='rubrica_id' type='hidden' value='<?php if($Rubricadeprojeto) echo $Rubricadeprojeto->rubrica_id;?>' />
        <input name='idProjeto' type='hidden' value='<?php if($Rubricadeprojeto) echo $Rubricadeprojeto->projeto_id;?>' />
        <input name='task' type='hidden' value='atualizarRubricadeprojeto' />
        <input name='buscaDescricao' type='hidden' value='<?php echo $descricao;?>' />
        
    </form>

<?php }


// SALVAR RUBRICA
function salvarRubricadeprojeto($idRubricadeprojeto = "", $idProjeto){
	$database = JFactory::getDBO();
	
	$projetopd = identificarProjetopdID($idProjeto);
	$id = JRequest::getVar('id');
	$rubrica_id = JRequest::getVar('rubrica_id');
	$descricao = JRequest::getVar('descricao');
	$valor_total = moedaSql(JRequest::getVar('valor_total'));
	$valor_disponivel = moedaSql(JRequest::getVar('valor_disponivel'));
	
	// DATA DO SISTEMA
	$sishora = mktime(date("H") - 1, date("i"), date("s"));
	$data = date("Y-m-d");
	$hora = date("H:i:s", $sishora);	
	$datahora = $data . " " . $hora;	
	
	// CALCULA O TOTAL DOS VALORES DE RUBRICAS
	$sql = "SELECT SUM(valor_total) as soma FROM #__contproj_rubricasdeprojetos WHERE projeto_id = $idProjeto";
	$database->setQuery($sql);
	$somas = $database->loadObjectList();
	$soma = $somas[0]->soma + $valor_total;;
	$orcamento = floatval($projetopd->orcamento);
	
	if(round($soma,2) > round($orcamento,2)){
		JError::raiseWarning( 100, 'ERRO: O valor informado para esta rubrica somada às demais ultrapassa o orçamento do projeto!');
		addRubricadeprojeto(NULL, "", $idProjeto);
		return 0;
	}

	// INSERÇÃO NA TABELA RUBRICA DE PROJETO
	$sql = "INSERT INTO #__contproj_rubricasdeprojetos (projeto_id, rubrica_id, descricao, valor_total, valor_gasto, valor_disponivel) VALUES ($idProjeto, $rubrica_id, '$descricao', $valor_total, 0, $valor_disponivel)";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	// ID DA RUBRICA CADASTRADA : A RECEITA PERTENCE A UMA RUBRICA
	$sql2 = "SELECT MAX(id) as idRubrica FROM #__contproj_rubricasdeprojetos";
    $database->setQuery($sql2);
    $resultado = $database->loadObjectList();
	
	foreach ($resultado as $r) 
		$idRubrica = $r->idRubrica;		
	
	// INSERÇÃO NA TABELA DE RECEITAS
	$sql = "INSERT INTO #__contproj_receitas (rubricasdeprojetos_id, descricao, valor_receita, data)
			VALUES ($idRubrica, '$descricao', $valor_disponivel, '$datahora')";
	$database->setQuery($sql);
	$funcionou2 = $database->Query();
	
	// CONSULTA DO SALDO ATUAL DO PROJETO
	$sqlCons = "SELECT saldo FROM #__contproj_projetos WHERE id = $idProjeto";
    $database->setQuery($sqlCons);
    $resultadoSaldo = $database->loadObjectList();
	
	foreach ($resultadoSaldo as $s) 
		$saldo = $s->saldo.'<br>';
		
		$novoSaldo = $saldo + $valor_disponivel;
	
	// ATUALIZAÇÃO DO SALDO NA TABELA DE PROJETOS
	$sqlUp = "UPDATE #__contproj_projetos SET saldo = '$novoSaldo' WHERE  id = $idProjeto";
	$database->setQuery($sqlUp);
	$funcionou3 = $database->Query();
	
	
	if ($funcionou && $funcionou2) {
		JFactory::getApplication()->enqueueMessage(JText::_('Cadastro realizado com sucesso!'));
		listarRubricadeprojetos();
    	return 1;
	} else{
        JError::raiseWarning(100, 'ERRO: Cadastro não realizado');
		addRubricadeprojeto(NULL, "", $idProjeto);
        return 0;
	}    
}


// ATUALIZAR RUBRICA
function atualizarRubricadeprojeto($idRubricadeprojeto, $idProjeto){
	$database = JFactory::getDBO();
	
	$projetopd = identificarProjetopdID($idProjeto);
	$id = JRequest::getVar('id');
	$rubrica_id = JRequest::getVar('rubrica_id');
	$descricao = JRequest::getVar('descricao');
	$valor_total = moedaSql(JRequest::getVar('valor_total'));
	
	// RECUPERA A RUBRICA ALTERADA
	$sql = "SELECT * FROM #__contproj_rubricasdeprojetos WHERE id = $id";
	$database->setQuery($sql);
	$rubricasAlteradas = $database->loadObjectList();

	// CALCULA O TOTAL DOS VALORES DE RUBRICAS
	$sql = "SELECT SUM(valor_total) as soma FROM #__contproj_rubricasdeprojetos WHERE projeto_id = $idProjeto";
	$database->setQuery($sql);
	$somas = $database->loadObjectList();
	$soma = $somas[0]->soma - $rubricasAlteradas[0]->valor_total + $valor_total;;
	
	//VERIFICA SE O NOVO VALOR É MAIOR QUE O DISPONÍVEL
	if($valor_total < $rubricasAlteradas[0]->valor_gasto){
		JError::raiseWarning( 100, 'ERRO: O valor previsto para esta rubrica não pode ser inferior ao valor já gasto no projeto!');
		$Rubricadeprojeto = identificarRubricadeprojetoID($idRubricadeprojeto);
		editarRubricadeprojeto($Rubricadeprojeto, "");		
		return 0;
	}
	else if($soma > $projetopd->orcamento){
		$Rubricadeprojeto = identificarRubricadeprojetoID($idRubricadeprojeto);
		JError::raiseWarning( 100, 'ERRO: O valor informado para esta rubrica somada às demais ultrapassa o orçamento do projeto!');
		editarRubricadeprojeto($Rubricadeprojeto, "");				
		return 0;
	}

	$sql = "UPDATE #__contproj_rubricasdeprojetos SET descricao ='$descricao', valor_total = $valor_total WHERE  id = $idRubricadeprojeto";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	if ($funcionou) {
		JFactory::getApplication()->enqueueMessage(JText::_('Atualização realizada com sucesso!'));
		listarRubricadeprojetos();
		return 1;
	 } else {
		JError::raiseWarning( 100, 'ERRO: Edição não realizada!');
		$Rubricadeprojeto = identificarRubricadeprojetoID($idRubricadeprojeto);
		editarRubricadeprojeto($Rubricadeprojeto, "");		
		return 0;
	 }  
}


// EXLUIR RUBRICAS
function excluirRubricadeprojeto($idRubricadeprojeto){
	$database = JFactory::getDBO();
	
	$sqlBusca = "SELECT * FROM #__contproj_despesas WHERE rubricasdeprojetos_id = $idRubricadeprojeto";
	$database->setQuery($sqlBusca);
	$resultado = $database->loadObjectList();

	//VERIFICAR SE JÁ TEM GASTOS
	if($resultado){
		JError::raiseWarning( 100, 'ERRO: Esta rubrica já possui gastos cadastrados e não pode ser excluída!');
		return 0;
	}

	$sqlBusca = "SELECT * FROM #__contproj_receitas WHERE rubricasdeprojetos_id = $idRubricadeprojeto";
	$database->setQuery($sqlBusca);
	$resultado = $database->loadObjectList();

	//VERIFICAR SE JÁ TEM RECEITAS
	if($resultado){
		JError::raiseWarning( 100, 'ERRO: Esta rubrica já possui receitas cadastradas e não pode ser excluída!');
		return 0;
	}
	
	// EXCLUSÃO DA RUBRICA DO PROJETO
	$sql = "DELETE FROM #__contproj_rubricasdeprojetos WHERE id = $idRubricadeprojeto";
	$database->setQuery($sql);
	$funcionou = 1;//$database->Query();

	if($funcionou) {
		JFactory::getApplication()->enqueueMessage(JText::_('Exclusão realizada com sucesso!'));
		return 1;
	} else {
		JError::raiseWarning( 100, 'Erro ao tentar excluir a Rubrica!' );
		return 0;
	}
			
}


// Identifica o id do registro selecionado pelo radio button
function identificarRubricadeprojetoID($idRubricadeprojeto){
    $database	= JFactory::getDBO();
    $sql = "SELECT RP.id, RP.rubrica_id, R.codigo, R.nome, R.tipo, RP.projeto_id, RP.descricao, RP.valor_total, RP.valor_disponivel, RP.valor_gasto FROM #__contproj_rubricasdeprojetos AS RP JOIN #__contproj_rubricas AS R ON R.id = RP.rubrica_id WHERE RP.id = $idRubricadeprojeto LIMIT 1";
    $database->setQuery( $sql );
    $Rubricadeprojeto = $database->loadObjectList();
	
    return ($Rubricadeprojeto[0]);
}
<?php
/***********************************************************************
*               UFAM - Universidade Ferderal do Amazonas
* 
*      Funcoes de controle do modulo despesa,desenvolvido em 10/02/2013
* adaptado do modulo professor, para update do site icomp sob orientacao
* do Prof Arilo Dias. 
* 
* Sitemas de informacao, Equipe Ferpa Augusto, Darlison, Denise e Diana
* usando padrao de desenvolvimento ágil
* 
************************************************************************/

// LISTAGEM DOS REGISTROS
function listarRegistrodedatas($evento = ""){
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
    $idProjeto = JRequest::getVar('idProjeto');
    $projetopd = identificarProjetopdID($idProjeto);
    $sql = "SELECT * FROM #__contproj_registradatas AS RD 
            WHERE projeto_id = $idProjeto AND evento LIKE '%$evento%' ORDER BY data";//idprojeto->identifica o projeto
    $database->setQuery( $sql );
    $gerenciarRegistrodedatas = $database->loadObjectList(); ?>

    <!--Funcionalidades dos formularios (Excluir, Editar e Visualizar) --> 
    <script language="JavaScript">
        function excluir(form) {
            var idSelecionado = 0;
            
            for(i = 0;i < form.idRubricaSelec.length;i++)
            if(form.idRubricaSelec[i].checked) 
                idSelecionado = form.idRubricaSelec[i].value;
                
            if(idSelecionado > 0) {
                var resposta = window.confirm("Confirmar exclusão do registro?");
                
                if(resposta) {
                    form.task.value = 'excluirRegistrodedata';
                    form.idRegistrodedata.value = idSelecionado;
                    form.submit();
                }	
            } else {
                alert('Selecione o item a ser excluído!')
            }
        }
        
		function editar(form) {
			var idSelecionado = 0;
			
			for(i = 0;i < form.idRubricaSelec.length;i++)
			if(form.idRubricaSelec[i].checked) 
				idSelecionado = form.idRubricaSelec[i].value;
				
			if(idSelecionado > 0) {
				form.task.value = 'editarRegistrodedata';
				form.idRegistrodedata.value = idSelecionado;
				form.submit();
			} else {
				alert('Selecione o item a ser editado!')
			}
		}
        
		function visualizar(form) {
			var idSelecionado = 0;
			
			for(i = 0;i < form.idRubricaSelec.length;i++)
			if(form.idRubricaSelec[i].checked) 
				idSelecionado = form.idRubricaSelec[i].value;
				
			if(idSelecionado > 0) {
				form.task.value = 'verRegistrodedata';
				form.idRegistrodedata.value = idSelecionado;
				form.submit();
			} else {
				alert('Selecione o item a ser  visualizado..')
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

<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" >

    <!-- Barra ferramentas -->
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-new">
           			<a href="javascript:document.form.task.value='addRegistrodedata';document.form.submit()">
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
        <div class="pagetitle icon-48-contact"><h2>Registrar Eventos</h2></div>
	</div></div>
    
	<?php exibirProjeto($projetopd); ?>

	<hr>

	<!-- Opção de Filtro -->
    <table width="50%">
		<tr>
        	<td>Filtro por evento:</td>
	        <td><input id="buscaEvento" name="buscaEvento" size="30" type="text" value="<?php echo $evento;?>"/></td>
	        <td rowspan="2"><input type="submit" value="Buscar"></td>
		</tr>
    </table>
     
    
    <!-- Formulario -->
    <table width='100%' id="tablesorter-imasters" class="tabela" cellpadding="0" cellspacing="1">
		<thead style="color:#FFC000; text-align:center;">
            <tr bgcolor="#002666">
                <th width="3%"></th>
                <th width="12%">Data</th>
                <th width="40%">Evento</th>
                <th width="35%">Observação</th>
                <th width="10%">Tipo</th>
			</tr>
		</thead>
		<tbody>
			<?php
            $table_bgcolor_even="#e6e6e6";
            $table_bgcolor_odd="#FFFFFF";
            $i = 0;
            
            foreach ($gerenciarRegistrodedatas as $registros) {
                $i = $i + 1;
                if ($i % 2) {
                    echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
                } else {
                    echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
                } ?>
                
                <td width='16'><input type="radio" name="idRubricaSelec" value="<?php echo $registros->id;?>"></td>
                <td><?php echo dataBr($registros->data);?></td>
                <td><?php echo $registros->evento;?></td>
                <td><?php echo $registros->observacao;?></td>
                <td><?php echo $registros->tipo;?></td>
			</tr> 
            
			<?php } ?>
            
		</tbody>
	</table>
    
    <br />
    Total de Registros: <b><?php echo sizeof($gerenciarRegistrodedatas);?></b>
    <input name='task' type='hidden' value='gerenciarRegistrodedatas' />
    <input name='idProjetopd' type='hidden' value='<?php echo $idProjeto;?>' />
    <input name='idProjeto' type='hidden' value='<?php echo $idProjeto;?>' />
    <input name='idRubricaSelec' type='hidden' value='0' />
    <input name='idRegistrodedata' type='hidden' value='' />
    
</form>

<?php } 


// FORMULÁRIO PARA CADASTRO DE REGISTRO
function addRegistrodedata($registrodedata = NULL, $evento, $idProjeto){
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
		
		function ValidateformCadastro(formCadastro) {
			if(IsEmpty(formCadastro.evento)) {
				alert('O campo Evento deve ser preenchido.')
				formCadastro.evento.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.data)) {
				alert('O campo Data deve ser preenchido.')
				formCadastro.data.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.tipo)) {
				alert('O campo Tipo deve ser preenchido.')
				formCadastro.tipo.focus();
				return false;
			}
		
			return true;
		}
		
		function voltarForm(form) {
			form.task.value = 'gerenciarRegistrodedatas';
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
    
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">

	<form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateformCadastro(this)">
    
    	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
		    <div class="cpanel2">
        	    <div class="icon" id="toolbar-save">
            	    <!-- A opcao abaixo usa o recurso de validacao do formulario-->
                    <a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
                    <!--A opcao abaixo acessa o formulario de forma direta-->
                    <!--<a href="javascript:document.formCadastro.task.value='salvarRegistrodedata';document.formCadastro.submit()">-->
           			<span class="icon-32-save"></span>Salvar</a>
				</div>
                
                <div class="icon" id="toolbar-cancel">
           			<a href="javascript:voltarForm(document.formCadastro)">
	           		<span class="icon-32-cancel"></span>Cancelar</a>
			    </div>
                
			</div>
            <div class="clr"></div>
    		</div>
            <div class="pagetitle icon-48-cpanel"><h2>Cadastro de evento</h2></div>
	        </div>
	    </div>
        
		<b>Como proceder: </b>
		<ul><li>Preencha todos os campos com os dados para registro de datas <font color="#FF0000">(* Campos Obrigatorios)</font>.</li></ul>
        
		<hr />
        
		<table width="100%">
    		<tr>
                 <input name='id' type='hidden' value="NULL">
                 <input name='projeto_id' type='hidden' value='<?php echo $idProjeto;?>'>
            </tr>
            <tr style="background-color: #7196d8;">
               <td colspan="4"><font color="#FFFFFF"><b>Informações</b></font></td>
            </tr>
		    <tr>
        		<td class="tbItemForm">Projeto:</td>
		        <td colspan="3">
       		    <?php 
                	$database->setQuery("SELECT * from #__contproj_projetos WHERE id = $idProjeto ORDER  BY nomeprojeto");
	            	$projeto_listas = $database->loadObjectList();
					$nomeProjeto = $projeto_listas[0]->nomeprojeto; 
                    ?>
					<?php echo $nomeProjeto; ?>
				</td>    
		    </tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Evento:</td>
                <td><input type="text" maxlength="70" size="70" name="evento" class="inputbox" /></td>
            </tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Data:</td>
                <td><input type="text" size="15" id="data" name="data" class="inputbox" /></td>
            </tr>
            <tr>
               <td class="tbItemForm">Observação:</td>
               <td><textarea rows="8" cols="50" name="observacao" class="inputbox" style="resize:none;"></textarea></td>
            </tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Tipo:</td>
                <td>
                    <select name="tipo" class="inputbox" style="width:120px;">
                        <option value=""></option>
                        <option value="E-mail">E-mail</option>
                        <option value="Recado">Recado</option>
                        <option value="Telefone">Telefone</option>
                    </select>
                </td>
            </tr>
        </table>
    
        <input name='idRegistrodedata' type='hidden' value='<?php if($registrodedata) echo $registrodedata->id;?>' />
        <input name='idProjeto' type='hidden' value='<?php echo $idProjeto;?>' />
        <input name='task' type='hidden' value='salvarRegistrodedata' />
        <input name='buscaEvento' type='hidden' value='<?php echo $evento;?>' />
    
	</form>
    
<?php }


// FORMULÁRIO PARA EDIÇÃO DE REGISTROS
function editarRegistrodedata($registrodedata = NULL, $evento, $idProjeto) {
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
		
		function ValidateformCadastro(formCadastro) {
			if(IsEmpty(formCadastro.evento))  {
				alert('O campo Evento deve ser preenchido.')
				formCadastro.evento.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.data)){
				alert('O campo Data deve ser preenchido.')
				formCadastro.data.focus();
				return false;
			}
		
			if(IsEmpty(formCadastro.tipo)) {
				alert('O campo Tipo deve ser preenchido.')
				formCadastro.tipo.focus();
				return false;
			}
		
			return true;
		}
		
		function voltarForm(form) {
			form.task.value = 'gerenciarRegistrodedatas';
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
    
	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    
	<form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateformCadastro(this)">
    
    	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        	<div class="cpanel2">
            	<div class="icon" id="toolbar-save">
                	<!-- A opcao abaixo usa o recurso de validacao do formulario-->
                    <a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
                    <!--A opcao abaixo acessa o formulario de forma direta-->
                    <!--<a href="javascript:document.formCadastro.task.value='salvarRegistrodedata';document.formCadastro.submit()">-->
           			<span class="icon-32-save"></span>Salvar</a>
				</div>
                
                <div class="icon" id="toolbar-cancel">
           			<a href="javascript:voltarForm(document.formCadastro)">
	           		<span class="icon-32-cancel"></span>Cancelar</a>
			    </div>
                
			</div>
            <div class="clr"></div>
    	    </div>
            <div class="pagetitle icon-48-cpanel"><h2>Edição de evento</h2></div>
       		</div>
	    </div>
        
		<b>Como proceder: </b>
		<ul><li>Edite os dados do registro <font color="#FF0000">(* Campos Obrigatorios)</font>.</li></ul>
        
	    <hr />
        
		<table width="100%">
			<tr>
                <input name='id' type='hidden' value="<?php echo $registrodedata->id;?>">
                <input name='projeto_id' type='hidden' value="<?php  echo $registrodedata->projeto_id;?>">
            </tr>
            <tr style="background-color: #7196d8;">
               <td colspan="4"><font color="#FFFFFF"><b>Informações</b></font></td>
            </tr>            
            <tr>
                <td class="tbItemForm">Projeto:</td>
                <td colspan="3">
                	<?php 
                    	$database->setQuery("SELECT * from #__contproj_projetos  ORDER  BY nomeprojeto");
                        $projeto_listas = $database->loadObjectList();
                        foreach($projeto_listas as $projeto_final_listas) { 
							if ($projeto_final_listas->id == $idProjeto) 
								echo $projeto_final_listas->nomeprojeto;
						} ?>
				</td>            
            </tr> 
            <tr>
                <td class="tbItemForm"><span>*</span> Evento:</td>
                <td><input type="text" maxlength="40" size="40" name="evento" class="inputbox" value="<?php echo $registrodedata->evento;?>"></td>
			</tr>
            <tr>
                <td class="tbItemForm"><span>*</span> Data:</td>
                <td><input type="text" maxlength="10" size="10" id="data" name="data" class="inputbox" value="<?php echo dataBr($registrodedata->data);?>" /></td>
            </tr>
            <tr>
               <td class="tbItemForm">Observação:</td>
               <td><textarea class="inputbox" size="15" name="observacao" rows="10" cols="60" style="width:60%;height:80%;"><?php echo $registrodedata->observacao;?></textarea></td>
            </tr>         
		    <tr>
        		<td class="tbItemForm"><span>*</span> Tipo:</td>
				<td>
			        <select name="tipo" class="inputbox">
				        <option value="E-mail" <?php if ($registrodedata->tipo == "E-mail") echo 'SELECTED';?>>E-mail</option>
				        <option value="Recado" <?php if ($registrodedata->tipo == "Recado") echo 'SELECTED';?>>Recado</option>
				        <option value="Telefone" <?php if ($registrodedata->tipo == "Telefone") echo 'SELECTED';?>>Telefone</option>
			        </select>
				</td>
			</tr>
		</table>

        <input name='idRegistrodedata' type='hidden' value='<?php if($registrodedata) echo $registrodedata->id;?>' />
        <input name='idProjeto' type='hidden' value='<?php if($registrodedata) echo $registrodedata->projeto_id;?>' />
        <input name='task' type='hidden' value='atualizarRegistrodedata' />
        <input name='buscaEvento' type='hidden' value='<?php echo $evento;?>' />
        
    </form>
    
<?php }

// SALVAR REGISTRO
function salvarRegistrodedata($idRegistrodedata = ""){
	$database = JFactory::getDBO();
	
	$id = JRequest::getVar('id');
	$evento = JRequest::getVar('evento');
	$data = dataSql(JRequest::getVar('data'));  
	$projeto_id = JRequest::getVar('projeto_id');
	$observacao = JRequest::getVar('observacao');
	$tipo= JRequest::getVar('tipo');
  
	$sql = "INSERT INTO #__contproj_registradatas (id, evento, data, projeto_id, observacao, tipo) 
			VALUES ('$id', '$evento', '$data', '$projeto_id', '$observacao', '$tipo')";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	if($funcionou) {
		JFactory::getApplication()->enqueueMessage(JText::_('Cadastro realizado com sucesso!'));
		return 1;
	} else {
		JError::raiseWarning(100, 'ERRO: Cadastro não realizado');
		return 0;
	}              
}


// ATUALIZA REGISTRO
function atualizarRegistrodedata($idRegistrodedata){
	$database = JFactory::getDBO();
	
	$id = JRequest::getVar('id');
	$evento = JRequest::getVar('evento');
	$data = dataSql(JRequest::getVar('data'));  
	$projeto_id = JRequest::getVar('projeto_id');
	$observacao = JRequest::getVar('observacao');
	$tipo = JRequest::getVar('tipo');
  
	$sql = "UPDATE #__contproj_registradatas SET evento = '$evento', data = '$data', observacao = '$observacao', tipo = '$tipo' WHERE id = '$idRegistrodedata'";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	if($funcionou) {
		JFactory::getApplication()->enqueueMessage(JText::_('Atualização realizada com sucesso!'));
		return 1;
	} else {
		JError::raiseWarning(100, 'ERRO: Edição não realizada!');
		return 0;
	}              
}


// EXCLUIR REGISTRO
function excluirRegistrodedata($idRegistrodedata) {
	$database = JFactory::getDBO();
	$sql = "DELETE FROM #__contproj_registradatas WHERE id = $idRegistrodedata";
	$database->setQuery($sql);
	$database->Query();
}


// Identifica o id do registro selecionado pelo radio button
function identificarRegistrodedataID($idRegistrodedata){
    $database	= JFactory::getDBO();
    $sql = "SELECT * FROM #__contproj_registradatas WHERE id = $idRegistrodedata LIMIT 1";
    $database->setQuery( $sql );
    $registrodedata = $database->loadObjectList();
    return ($registrodedata[0]);
}
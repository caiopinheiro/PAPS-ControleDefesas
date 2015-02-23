<?php
/***********************************************************************
*               UFAM - Universidade Ferderal do Amazonas
* 
*      Funcoes de controle do modulo rubrica,desenvolvido em 10/02/2013
* adaptado do modulo professor, para update do site icomp sob orientacao
* do Prof Arilo Dias. 
* 
* Sitemas de informacao, Equipe Ferpa Augusto, Denise e Diana
* usando padrao de desenvolvimento ágil
* 
************************************************************************/

// Listar registros
function listarRubricas($nome = ""){
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
    $sql = "SELECT * FROM #__contproj_rubricas WHERE nome LIKE '%$nome%' ORDER BY nome";
    $database->setQuery( $sql );
    $gerenciarRubricas = $database->loadObjectList();
	?>

    <!--Funcionalidades dos formularios (Excluir, Editar e Visualizar) --> 
    <script language="JavaScript">
        function excluir(form){
           var idSelecionado = 0;
           for(i = 0;i < form.idRubricaSelec.length;i++)
                if(form.idRubricaSelec[i].checked) idSelecionado = form.idRubricaSelec[i].value;
           if(idSelecionado > 0){
               var resposta = window.confirm("Confirmar exclus\xE3o da Rubrica?");
               if(resposta){
                  form.task.value = 'excluirRubrica';
                  form.idRubrica.value = idSelecionado;
                  form.submit();
               }
           }
           else{
                alert('Ao menos 1 item deve ser selecionado para exclus\xE3o.')
           }
        }
    
        function editar(form){
           var idSelecionado = 0;
           for(i = 0;i < form.idRubricaSelec.length;i++)
                if(form.idRubricaSelec[i].checked) idSelecionado = form.idRubricaSelec[i].value;
           if(idSelecionado > 0){
               form.task.value = 'editarRubrica';
               form.idRubrica.value = idSelecionado;
               form.submit();
           }
           else{
                alert('Ao menos 1 item deve ser selecionado para edi\xE7\xE3o.')
           }
        }
    
        function visualizar(form){
           var idSelecionado = 0;
           for(i = 0;i < form.idRubricaSelec.length;i++)
                if(form.idRubricaSelec[i].checked) idSelecionado = form.idRubricaSelec[i].value;
           if(idSelecionado > 0){
               form.task.value = 'verRubrica';
               form.idRubrica.value = idSelecionado;
               form.submit();
           }
           else{
                alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o.')
           }
        }
    </script>

    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
	<link rel="stylesheet" href="components/com_controleprojetos/estilo.css" type="text/css" />
    
    <script type="text/javascript" src="components/com_controleprojetos/jquery.js"></script>
    <script type="text/javascript" src="components/com_controleprojetos/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();	
		});
	</script>
        
<!-- Formulario da Funcao listarRubricas -->
<!--Cuidado! a linha abaixo refere-se a postagem do formulario no modulo controle projetos -->
<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post" >
    <!-- Barra ferramentas -->
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-new">
           			<a href="javascript:document.form.task.value='addRubrica';document.form.submit()">
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
           			<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
          </div>
          
          <div class="clr"></div>
          </div>
          <div class="pagetitle icon-48-contact"><h2>Gerenciar Rubricas</h2></div>
    </div></div>
    
    <!-- Filtro de busca -->
    <table border="0" cellpadding="0" cellspacing="0" width="50%">
    <tbody>
      <tr>
        <td>Filtro por Nome:</td>
        <td><input id="buscaNome" name="buscaNome" size="30" type="text" value="<?php echo $nome;?>"/></td>
        <td rowspan="2"><input type="submit" value="Buscar"></td>
      </tr>
    </tbody>
    </table>

      <!-- Formulario -->
    <table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
	<thead>
      <tr bgcolor="#002666">
        <th width="1%" align="center"><font color="#FFC000"></font></th>
        <th width="11%" align="center"><font color="#FFC000">Código</font></th>
        <th width="50%" align="center"><font color="#FFC000">Nome</font></th>
        <th width="12%" align="center"><font color="#FFC000">Tipo</font></th>        
      </tr>
     </thead>
     <tbody>
	<?php
	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";
	$i = 0;
	foreach( $gerenciarRubricas as $gerenciarItemRubricas ) {
		$i = $i + 1;
		if ($i % 2) {
		    echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
		} else {
		    echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
		} ?>
        
		<td width='16'><input type="radio" name="idRubricaSelec" value="<?php echo $gerenciarItemRubricas->id;?>"></td>
		<td><?php echo $gerenciarItemRubricas->codigo;?></td>
		<td><?php echo $gerenciarItemRubricas->nome;?></td>
		<td><?php echo $gerenciarItemRubricas->tipo;?></td>
	</tr>
    
	<?php } ?>
    </tbody>
    </table>
    
    <br>Total de Rubricas <b><?php echo sizeof($gerenciarRubricas);?></b>
    <input name='task' type='hidden' value='gerenciarRubricas'>
    <input name='idRubricaSelec' type='hidden' value='0'>
    <input name='idRubrica' type='hidden' value=''>
</form>
<?php  } ?>


<?php // Identifica o id do registro selecionado pelo radio button
function identificarRubricaID($idRubrica){
    $database	= JFactory::getDBO();
    $sql = "SELECT * FROM #__contproj_rubricas WHERE id = $idRubrica LIMIT 1";
    $database->setQuery( $sql );
    $rubrica = $database->loadObjectList();
	
    return ($rubrica[0]);
}


// Criar novo registro
function addRubrica($rubrica = NULL, $nomerubrica){
    $database	= JFactory::getDBO();
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
		  // set var radio_choice to false
		  var radio_choice = false;
		  // Loop from zero to the one minus the number of radio button selections
		  for (counter = 0; counter < elem.length; counter++){
			// If a radio button has been selected it will return true
			// (If not it will return false)
			if (elem[counter].checked)
			radio_choice = true;
		   }
		  return (radio_choice);
		}
    
		function ValidateformCadastro(formCadastro){
		   if(IsEmpty(formCadastro.codigo)){
			  alert('O campo Codigo deve ser preenchido.')
			  formCadastro.codigo.focus();
			  return false;
		   }
		if(IsEmpty(formCadastro.nomerubrica)){
			  alert('O campo nome deve ser preenchido.')
			  formCadastro.nomerubrica.focus();
			  return false;
		   }
		 if (!radio_button_checker(formCadastro.tipo)){
			  alert('O campo Tipo deve ser preenchido.')
			  formCadastro.tipo.focus();
			  return false;
		   }
		return true;
		}
		
		function voltarForm(form){
		   form.task.value = 'gerenciarRubricas';
		   form.submit();
		   return true;
		}
	</script>
    
	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">

   <form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post"  onsubmit="javascript:return ValidateformCadastro(this)">
	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
		<div class="cpanel2">
			<div class="icon" id="toolbar-save">
                <a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
                <span class="icon-32-save"></span>Salvar</a>
			</div>
			<div class="icon" id="toolbar-cancel">
                <a href="javascript:voltarForm(document.formCadastro)">
                <span class="icon-32-back"></span>Voltar</a>
		    </div>
			</div>
            <div class="clr"></div>
			</div>
            <div class="pagetitle icon-48-cpanel"><h2>Cadastro de Rubricas</h2></div>
       </div>
    </div>
    
	<b>Como proceder: </b>
	<ul><li>Preencha todos os campos com os dados da rubrica <font color="#FF0000">(* Campos Obrigatórios)</font>.</li></ul>
	<hr style="width: 100%; height: 2px;">
    
    <table border="0" cellpadding="1" cellspacing="2" width="100%">
        <tr style="background-color: #7196d8;">
        	<td colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
        </tr>   
    	<input name='id' type='hidden' value="NULL">
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Código:</b></font></td>
            <td><input maxlength="20" size="20" name="codigo" class="inputbox" value=""></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Nome:</b></font></td>
            <td><input maxlength="80" size="60" name="nomerubrica" class="inputbox" value=""></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Tipo:</b></font></td>
            <td><input type="radio" name="tipo" value="Capital">Capital
            	<input type="radio" name="tipo" value="Custeio">Custeio
            </td>
        </tr>
    </table>
   
    <input name='idRubrica' type='hidden' value='<?php if($rubrica) echo $rubrica->id;?>'>
    <input name='task' type='hidden' value='salvarRubrica'>
    <input name='buscaNome' type='hidden' value='<?php echo $nomerubrica;?>'>
</form>
    <?php
}
//////////////////////////////////////////

// Edita o registro selecionado pela variavel idRubrica
function editarRubrica($rubrica, $nome){
    $database	= JFactory::getDBO();
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
		  // set var radio_choice to false
		  var radio_choice = false;
		  // Loop from zero to the one minus the number of radio button selections
		  for (counter = 0; counter < elem.length; counter++){
			// If a radio button has been selected it will return true
			// (If not it will return false)
			if (elem[counter].checked)
			radio_choice = true;
		   }
		  return (radio_choice);
		}
		
		function ValidateformCadastro(formCadastro){
			if(IsEmpty(formCadastro.codigo)){
			  alert('O campo Codigo deve ser preenchido.')
			  formCadastro.codigo.focus();
			  return false;
		   }
		if(IsEmpty(formCadastro.nomerubrica)){
			  alert('O campo Nome deve ser preenchido.')
			  formCadastro.nomerubrica.focus();
			  return false;
		   }
		 if (!radio_button_checker(formCadastro.tipo)){
			  alert('O campo Tipo deve ser preenchido.')
			  formCadastro.tipo.focus();
			  return false;
		   }
		return true;
		}
		
		function voltarForm(form){
		   form.task.value = 'gerenciarRubricas';
		   form.submit();
		   return true;
		}
    </script>
    
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">

   <form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post"  onsubmit="javascript:return ValidateformCadastro(this)">
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
		<div class="cpanel2">
			<div class="icon" id="toolbar-save">
            	<a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
          		<span class="icon-32-save"></span>Salvar</a>
	        </div>
            <div class="icon" id="toolbar-cancel">
           		<a href="javascript:voltarForm(document.formCadastro)">
           		<span class="icon-32-back"></span>Voltar</a>
		    </div>
			</div>
            <div class="clr"></div>
    	    </div>
            <div class="pagetitle icon-48-cpanel"><h2>Edição de Rubricas</h2></div>
       </div>
    </div>
    
	<b>Como proceder: </b>
	<ul><li>Edite os dados da rubrica <font color="#FF0000">(* Campos Obrigatórios)</font>.</li></ul>
	<hr style="width: 100%; height: 2px;">
    
    <table border="0" cellpadding="1" cellspacing="2" width="100%">
        <tr style="background-color: #7196d8;">
        	<td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
        </tr>
        <tr>
        	<td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Código:</b></font></td>
        	<td><input size="20" name="codigo" class="inputbox" value="<?php echo $rubrica->codigo;?>"></td>
        </tr>
        <tr>
        	<td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Nome:</b></font></td>
       		<td><input size="60" name="nomerubrica" class="inputbox" value="<?php echo $rubrica->nome;?>"></td>
        </tr>
        <tr>
        	<td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Tipo:</b></font></td>
        	<td><input type="radio" name="tipo" value="Capital" <?php if($rubrica->tipo == "Capital") echo "CHECKED";?>> Capital 
        		<input type="radio" name="tipo" value="Custeio" <?php if($rubrica->tipo == "Custeio") echo "CHECKED";?>> Custeio
        	</td>
        </tr>
    </table>    

    <input name='id' type='hidden' value="<?php echo $rubrica->id;?>">
    <input name='idRubrica' type='hidden' value='<?php if($rubrica) echo $rubrica->id;?>'>
    <input name='task' type='hidden' value='atualizarRubrica'>
    <input name='buscaNome' type='hidden' value='<?php echo $nomerubrica;?>'>
</form>

<?php } ?>



<?php //Atualiza a rubrica
function salvarRubrica($rubrica){
	$database = JFactory::getDBO();

	$nomerubrica = JRequest::getVar('nomerubrica');
	$tipo = JRequest::getVar('tipo');
	$codigo = JRequest::getVar('codigo');
  
	$sql = "INSERT INTO #__contproj_rubricas (codigo, nome, tipo) VALUES ('$codigo', '$nomerubrica', '$tipo')";
    $database->setQuery($sql);

	if($database->Query()) {
		JFactory::getApplication()->enqueueMessage(JText::_('Cadastro realizado com sucesso!'));
	  	listarRubricas($nome);
	} else {
		JError::raiseWarning( 100, 'ERRO: Não foi possível realizar o cadastro.' );
		return 0;
	}
}
?>


<?php //Atualiza a rubrica
function atualizarRubrica($rubrica){
	$database = JFactory::getDBO();

	$nomerubrica = JRequest::getVar('nomerubrica');
	$tipo = JRequest::getVar('tipo');
	$codigo = JRequest::getVar('codigo');
  
	$sql = "UPDATE #__contproj_rubricas SET codigo = '$codigo', nome = '$nomerubrica', tipo = '$tipo' WHERE id = $rubrica->id";
    $database->setQuery($sql);

	if($database->Query()) {
		JFactory::getApplication()->enqueueMessage(JText::_('Edição da rubrica realizada com sucesso!'));
	  	listarRubricas($nome);
	} else {
		JError::raiseWarning( 100, 'ERRO: Alteração não realizada.' );
		return 0;
	}
}
?>



<?php //Exclui o registro
function excluirRubrica($idRubrica){
	$database	= JFactory::getDBO();
	$sql = "DELETE FROM #__contproj_rubricas WHERE id = $idRubrica";
	$database->setQuery($sql);
	$database->Query();
	
	if ($database)
		JFactory::getApplication()->enqueueMessage(JText::_('Rubrica excluída com sucesso!'));
}
?>
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

// Listar registros
function listarFomentos($nome = ""){
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
    $sql = "SELECT * FROM #__contproj_agencias WHERE nome LIKE '%$nome%' ORDER BY sigla";
    $database->setQuery( $sql );
    $gerenciarFomentos = $database->loadObjectList();
	?>

    <!--Funcionalidades dos formularios (Excluir, Editar e Visualizar) --> 
    <script language="JavaScript">
        function excluir(form) {
           var idSelecionado = 0;
           for(i = 0;i < form.idFomentoSelec.length;i++)
                if(form.idFomentoSelec[i].checked) idSelecionado = form.idFomentoSelec[i].value;
                
           if(idSelecionado > 0) {
               var resposta = window.confirm("Confirmar exclusao do Fomento?");
               if(resposta){
                  form.task.value = 'excluirFomento';
                  form.idFomento.value = idSelecionado;
                  form.submit();
               }
           } else {
                alert('Ao menos 1 item deve ser selecionado para edi\xE7\xE3o.')
           }
        }
    
        function editar(form) {
           var idSelecionado = 0;
           for(i = 0;i < form.idFomentoSelec.length;i++)
                if(form.idFomentoSelec[i].checked) idSelecionado = form.idFomentoSelec[i].value;
                
           if(idSelecionado > 0 ){
               form.task.value = 'editarFomento';
               form.idFomento.value = idSelecionado;
               form.submit();
           } else {
                alert('Ao menos 1 item deve ser selecionado para edi\xE7\xE3o.')
           }
        }
    
        function visualizar(form){
           var idSelecionado = 0;
           for(i = 0;i < form.idFomentoSelec.length;i++)
                if(form.idFomentoSelec[i].checked) idSelecionado = form.idFomentoSelec[i].value;
                
           if(idSelecionado > 0) {
               form.task.value = 'verFomento';
               form.idFomento.value = idSelecionado;
               form.submit();
           } else {
                alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o.')
           }
        }
    </script>

	<link rel="stylesheet" href="components/com_controleprojetos/estilo.css" type="text/css" />
    <script type="text/javascript" src="components/com_controleprojetos/jquery.js"></script>
    <script type="text/javascript" src="components/com_controleprojetos/jquery.tablesorter.js"></script>
	<script type="text/javascript">
	$(function() {
		$("#tablesorter-imasters").tablesorter();
	});
	</script>
    
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
        
    <!-- Formulario da Funcao listarFomentos -->
    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post" >
    <!-- Barra ferramentas -->
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-new">
           			<a href="javascript:document.form.task.value='addFomento';document.form.submit()">
           			<span class="icon-32-new"></span><?php echo JText::_( 'Novo' ); ?></a>
				</div>
                
				<div class="icon" id="toolbar-edit">
           			<a href="javascript:editar(document.form)">
           			<span class="icon-32-edit"></span><?php echo JText::_( 'Editar' ); ?></a>
				</div>
                
				<!--<div class="icon" id="toolbar-preview">
           			<a href="javascript:visualizar(document.form)">
           			<span class="icon-32-preview"></span><?php echo JText::_( 'Visualizar' ); ?></a>
				</div>-->
                
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
          <div class="pagetitle icon-48-contact"><h2>Agências de Fomentos</h2></div>
    </div></div>
    
    <!-- Filtro de busca -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td>Filtro por Agência de Fomento:</td>
        <td><input id="buscaNome" name="buscaNome" size="50" type="text" value="<?php echo $nome;?>"/></td>
        <td rowspan="2"><input type="submit" value="Buscar"></td>
      </tr>
    </table>


    <!-- Formulario -->
    <table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
	<thead>
      <tr bgcolor="#002666">
        <th width="5%" align="center"><font color="#FFC000"></font></th>
        <th width="15%" align="center"><font color="#FFC000">Sigla</font></th>
        <th width="80%" align="center"><font color="#FFC000">Nome</font></th>
      </tr>
     </thead>
     <tbody>
     
	<?php
	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";
	$i = 0;
	foreach( $gerenciarFomentos as $gerenciarItemFomentos ){
		$i = $i + 1;
		if ($i % 2){
		    echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
		 } else {
		    echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
	  	 }
	?>
		<td width='16'><input type="radio" name="idFomentoSelec" value="<?php echo $gerenciarItemFomentos->id;?>"></td>
		<td><?php echo $gerenciarItemFomentos->sigla;?></td>
		<td><?php echo $gerenciarItemFomentos->nome;?></td>
	</tr>
    
    <?php } ?>
	</tbody>
    </table>
    
    <br>Total de Agências de Fomento: <b><?php echo sizeof($gerenciarFomentos);?></b>
    <input name='task' type='hidden' value='gerenciarFomentos'>
    <input name='idFomentoSelec' type='hidden' value='0'>
    <input name='idFomento' type='hidden' value=''>
</form>
<?php } ?>


<?php // Identifica o id do registro selecionado pelo radio button
function identificarFomentoID($idFomento){
    $database	= JFactory::getDBO();
    $sql = "SELECT * FROM #__contproj_agencias WHERE id = $idFomento LIMIT 1";
    $database->setQuery( $sql );
    $fomento = $database->loadObjectList();
    return ($fomento[0]);
}
?>

<?php // Criar novo registro
function addfomento($fomento = NULL, $nomefomento){
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
           if(IsEmpty(formCadastro.nomefomento)){
              alert('O campo Nome deve ser preenchido.')
              formCadastro.nomefomento.focus();
              return false;
           }
           if(IsEmpty(formCadastro.sigla)){
              alert('O campo sigla deve ser preenchido.')
              formCadastro.sigla.focus();
              return false;
           }
           
        return true;
        }
        
        function voltarForm(form){
           form.task.value = 'gerenciarFomentos';
           form.submit();
           return true;
        }
    </script>

   <form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post"  onsubmit="javascript:return ValidateformCadastro(this)">
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
            <div class="pagetitle icon-48-cpanel"><h2>Cadastro da Agência de Fomento</h2></div>
       </div>
    </div>
    
	<b>Como proceder: </b>
	<ul><li>Preencha todos os campos com os dados da agencia de fomento <font color="#FF0000">(* Campos Obrigatorios)</font>.</li></ul>
   <hr style="width: 100%; height: 2px;">
   
	<table border="0" cellpadding="1" cellspacing="2" width="100%">
        <input name='id' type='hidden' value="NULL">
        <tr style="background-color: #7196d8;">
        	<td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
        </tr>
        <tr>
        	<td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Nome:</b></font></td>
        	<td><input maxlength="80" size="60" name="nomefomento" class="inputbox" value=""></td>
        </tr>
        <tr>
        	<td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Sigla:</b></font></td>
        	<td><input maxlength="15" size="15" name="sigla" class="inputbox" value=""></td>
        </tr>
	</table>
   
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    <input name='idFomento' type='hidden' value='<?php if($fomento) echo $fomento->id;?>'>
    <input name='task' type='hidden' value='salvarFomento'>
    <input name='buscaNome' type='hidden' value='<?php echo $nomefomento;?>'>
</form>
<?php } ?>


<?php // Edita o registro selecionado pela variavel idFomento
function editarFomento($fomento = NULL, $nomefomento){
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
		   if(IsEmpty(formCadastro.nomefomento)){
			  alert('O campo nome deve ser preenchido.')
			  formCadastro.nomefomento.focus();
			  return false;
		   }
		   if(IsEmpty(formCadastro.sigla)){
			  alert('O campo sigla deve ser preenchido.')
			  formCadastro.sigla.focus();
			  return false;
		   }
		   return true;
		}
		
		function voltarForm(form){
		   form.task.value = 'gerenciarFomentos';
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
           		<span class="icon-32-cancel"></span>Cancelar</a>
		    </div>
		</div>
		<div class="clr"></div>
		</div>
		<div class="pagetitle icon-48-cpanel"><h2>Edição da Agência de Fomento</h2></div>
       </div>
    </div>
    
	<b>Como proceder: </b>
	<ul><li>Altere os campos necessários <font color="#FF0000">(* Campos Obrigatorios)</font>.</li></ul>
	<hr style="width: 100%; height: 2px;">
    
	<table border="0" cellpadding="1" cellspacing="2" width="100%">
        <input name='id' type='hidden' value="<?php echo $fomento->id;?>">
        <tr style="background-color: #7196d8;">
        	<td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Nome:</b></font></td>
            <td><input maxlength="80" size="60" name="nomefomento" class="inputbox" value="<?php echo $fomento->nome;?>"></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Sigla:</b></font></td>
            <td><input maxlength="15" size="15" name="sigla" value="<?php echo $fomento->sigla;?>"></td>
        </tr>
	</table>    

    <input name='idFomento' type='hidden' value='<?php if($fomento) echo $fomento->id;?>'>
    <input name='task' type='hidden' value='salvarFomento'>
    <input name='buscaNome' type='hidden' value='<?php echo $nomefomento;?>'>
</form>

<?php } ?>


<?php //Salva o registro que foi criado ou editado (recebe salvarFomento)
function salvarFomento($idFomento = ""){
	$database = JFactory::getDBO();
	$id = $_POST['id'];
	$nomefomento = $_POST['nomefomento'];
	$sigla = $_POST['sigla'];
  
  if($idFomento == ""){//condicao se o cadastro for novo
      $sql = "SELECT * FROM #__contproj_agencias WHERE nome = '$nomefomento' OR sigla = '$sigla' ";
      $database->setQuery($sql);
      $repetido = $database->loadObjectList();
        if(!$repetido){
        $sql = "INSERT INTO #__contproj_agencias (id, nome, sigla) VALUES ('$id','$nomefomento','$sigla')";
        $database->setQuery($sql);
        $database->Query();
        JFactory::getApplication()->enqueueMessage(JText::_('Cadastro realizado com sucesso!'));
        return 1;
        }
        else{
            JFactory::getApplication()->enqueueMessage(JText::_('Cadastro não realizado, nome e/ou sigla já cadastrados.'));
            return (0);
        }
    }
    
    if($idFomento == $id){//condicao se o cadastro for editado
    $sql = "SELECT * FROM #__contproj_agencias WHERE nome = '$nomefomento' AND sigla = '$sigla' ";
    $database->setQuery($sql);
    $repetido = $database->loadObjectList();
        if(!$repetido){
        $sql = "UPDATE #__contproj_agencias SET id = '$id', nome = '$nomefomento' , sigla = '$sigla' WHERE  id = $idFomento";
        $database->setQuery($sql);
        $database->Query();
            if($idFomento == $id){
                $sql = "SELECT * FROM #__contproj_agencias WHERE nome = '$nomefomento' AND sigla = '$sigla' ";
                $database->setQuery($sql);
                $repetido_outros = $database->loadObjectList();
                if($repetido_outros){
                    JFactory::getApplication()->enqueueMessage(JText::_('Edição realizada com sucesso!'));
                    return (1);
                }
            }
        JFactory::getApplication()->enqueueMessage(JText::_('Edição não realizada, nome e/ou sigla já cadastrados!'));
        return (1);
        }
        else{
            JFactory::getApplication()->enqueueMessage(JText::_('Não houve alteração de cadastro!'));
            return (1);
        }
    }
}
?>



<?php //Exclui o registro
function excluirFomento($idFomento){
  $database	= JFactory::getDBO();
  $sql = "DELETE FROM #__contproj_agencias WHERE id = $idFomento";
  $database->setQuery($sql);
  $funcionou = $database->Query();
  
  if ($funcionou)
		JFactory::getApplication()->enqueueMessage(JText::_('Agência de fomento excluída com sucesso!'));
}
?>


<?php //Cria Relatorio
function relatorioFomento($fomento) {
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); ?>

    <div id="toolbar-box"><div class="m">
		<div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
			<div class="icon" id="toolbar-back">
           	    <a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarFomentos">
           	    <span class="icon-32-back"></span>Voltar</a>
			</div>
		</div>
        <div class="clr"></div>
		</div>
		<div class="pagetitle icon-48-contact"><h2>Dados da agencia de fomento</h2></div>
        </div>
    </div>
    
	<table border="0" cellpadding="3" cellspacing="2" width="100%">
		<tr>
            <td><font size="2"><b>Id:</b></font></td>
            <td colspan="3"><?php echo $fomento->id;?></td>
		</tr>
        <tr>
            <td><font size="2"><b>Nome:</b></font></td>
            <td colspan="3"><?php echo $fomento->nome;?></td>
        </tr> 
        <tr>
            <td><font size="2"><b>Sigla:</b></font></td>
            <td colspan="3"><?php echo $fomento->sigla;?> </td>
        </tr>
	</table>
   
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css" />
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css" />
    <link rel="stylesheet" href="components/com_controleprojetos/estilo.css" type="text/css" />
	
<?php } ?>
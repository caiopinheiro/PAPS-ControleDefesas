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

//include_once("components/com_controleprojetos/gerenciarfomento.php");
//include_once("components/com_controleprojetos/gerenciarprojeto.php");//te  que ver!!!!!!!
//include_once("components/com_controleprojetos/gerenciarrubrica.php");
//include_once("components/com_controleprojetos/rubricadeprojeto.php");
//include_once("components/com_controleprojetos/controleprojetos.html.php");


// Listar registros
function listarRubricadeprojetos($projetopd) {
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
//    $sql= "  SELECT a.*, b.nomeProfessor, c.sigla AS sigla 
//             FROM #__contproj_rubricasdeprojetos AS a 
//             INNER JOIN #__professores b
//             ON a.coordenador_id = b.id 
//             INNER JOIN #__contproj_agencias c
//             ON a.agencia_id = c.id
//             WHERE descricao  
//             LIKE '%$nome%' ORDER BY descricao";   

	$sql = "SELECT * FROM #__contproj_rubricasdeprojetos WHERE projeto_id = '$projetopd->id'";
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
            } else {
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
            } else {
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
            } else {
                alert('Selecione o item a ser  visualizado.')
            }
        }
        
        function voltarNivelForm(form){
			form.task.value = 'verProjetopd';
//			form.idProjetopd.value = <?php $projetopd->id; ?>;
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
    
   	<link rel="stylesheet" href="components/com_controleprojetos/estilo.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
        
    <!-- Formulario da Funcao gerenciarProjetospd -->
    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post" >
    
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
                
			<div class="icon" id="toolbar-preview">
                <a href="javascript:visualizar(document.form)">
                <span class="icon-32-preview"></span><?php echo JText::_( 'Visualizar' ); ?></a>
            </div>
                
			<div class="icon" id="toolbar-delete">
                <a href="javascript:excluir(document.form)">
                <span class="icon-32-delete"></span><?php echo JText::_( 'Excluir' ); ?></a>
            </div>
                
			<div class="icon" id="toolbar-back">
                <a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=verProjetopd&idProjetopd=<?php echo $projetopd->id;?>"><span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
            </div>
          </div>
        <div class="clr"></div>
		</div>
	<div class="pagetitle icon-48-contact"><h2>Rubricas do Projeto (Referência)</h2></div>
    </div></div>

    <!-- Filtro de busca -->
    <table border="0" cellpadding="0" cellspacing="0" width="50%">
    <tbody>
      <tr>
        <td>Filtro por descrição:</td>
        <td><input id="buscaDescricao" name="buscaDescricao" size="30" type="text" value="<?php echo $descricao;?>"/></td>
        <td rowspan="2"><input type="submit" value="Buscar"></td>
      </tr>
    </tbody>
    </table>

    <!-- Formulario -->
    <table border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters" class="tabela">
	<thead>
      <tr bgcolor="#002666">
        <th width="3%" align="center"><font color="#FFC000"></font></th>
        <th width="30%" align="center"><font color="#FFC000">Descrição</font></th>
        <th width="6%" align="center"><font color="#FFC000">Valor Total</font></th>
        <th width="6%" align="center"><font color="#FFC000">Valor Gasto</font></th>
        <th width="6%" align="center"><font color="#FFC000">Orçamento</font></th>
      </tr>
     </thead>
     <tbody>
     
	<?php
	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";
	$i = 0;
	foreach( $gerenciarRubricadeprojetos as $gerenciarItemRubricadeprojetos ){
		$i = $i + 1;
		if ($i % 2) {
		    echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
		} else {
		    echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
	  	}
	?>
		<td width='16'><input type="radio" name="idRubricadeprojetoSelec" value="<?php echo $gerenciarItemRubricadeprojetos->id;?>"></td>
        <td><?php echo $gerenciarItemRubricadeprojetos->descricao;?></td>
        <td align="right"><?php echo number_format($gerenciarItemRubricadeprojetos->valor_total, 2, ',','.');?></td>
        <td align="right"><?php echo number_format($gerenciarItemRubricadeprojetos->valor_gasto, 2, ',','.');?></td>
        <td align="right"><?php echo number_format($gerenciarItemRubricadeprojetos->valor_disponivel, 2, ',','.');?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
    
    <br>Total de Rubricas: <b><?php echo sizeof($gerenciarRubricadeprojetos);?></b>
    <input name='task' type='hidden' value='gerenciarRubricadeprojetos'>
    <input name='idRubricadeprojetoSelec' type='hidden' value='0'>
    <input name='idRubricadeprojeto' type='hidden' value=''>
    <input name='idprojeto' type='hidden' value='<?php echo $projetopd->id; ?>'>
</form>

<?php } ?>



<?php // Identifica o id do registro selecionado pelo radio button
function identificarRubricadeprojetoID($idRubricadeprojeto) {
    $database	= JFactory::getDBO();
    $sql = "SELECT * FROM #__contproj_rubricasdeprojetos WHERE id = $idRubricadeprojeto LIMIT 1";
    $database->setQuery( $sql );
    $rubricadeprojeto = $database->loadObjectList();
	
    return ($rubricadeprojeto[0]);
}



// Criar novo registro
function addRubricadeprojeto($rubricadeprojeto = NULL, $descricao){
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
	?>
    
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
		   if(IsEmpty(formCadastro.descricao)){
			  alert('O campo descrição deve ser preenchido.')
			  formCadastro.descricao.focus();
			  return false;
		   }
		   if(IsEmpty(formCadastro.valor_total)){
			  alert('O campo valor total deve ser preenchido.')
			  formCadastro.valor_total.focus();
			  return false;
		   }
		return true;
		}
		
		function voltarForm(form){
		   form.task.value = 'gerenciarRubricadeprojetos';
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
	  <div class="pagetitle icon-48-cpanel"><h2>Cadastro de Rubricas do Projeto</h2></div>
       </div>
    </div>
    
	<b>Como proceder: </b>
	<ul><li>Preencha todos os campos com os dados da despesa <font color="#FF0000">(* Campos Obrigatórios)</font>.</li></ul>
	<hr style="width: 100%; height: 2px;">
    
	<table border="0" cellpadding="1" cellspacing="2" width="100%">
		<tr style="background-color: #7196d8;">
			<td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
		</tr>
        <input name='id' type='hidden' value="NULL">
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Descrição:</b></font></td>
            <td><input maxlength="80" size="60" name="descricao" class="inputbox" value=""></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Valor Total:</b></font></td>
            <td><input maxlength="15" size="15" name="valor_total" class="inputbox" value=""></td>
        </tr> 
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000"></font> <b>Valor Gasto:</b></font></td>
            <td><input maxlength="15" size="15" name="valor_gasto" class="inputbox" value=""></td>
        </tr> 
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000"></font> <b>Valor Disponível:</b></font></td>
            <td><input maxlength="15" size="15" name="valor_disponivel" class="inputbox" value=""></td>
        </tr> 
	</table>  

    <input name='idRubricadeprojeto' type='hidden' value='<?php if($rubricadeprojeto) echo $rubricadeprojeto->id;?>'>
    <input name='task' type='hidden' value='salvarRubricadeprojeto'>
    <input name='buscaDescricao' type='hidden' value='<?php echo $descricao;?>'>
</form>
<?php }



// Editar o registro selecionado pela variavel idRubricadeprojeto
function editarRubricadeprojeto($rubricadeprojeto = NULL, $descricao){
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
	?>
    
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
        
        function ValidateformEdtRubrica(formEdtRubrica){
			if(IsEmpty(formEdtRubrica.descricao)){
				alert('O campo descrição deve ser preenchido.')
				formEdtRubrica.descricao.focus();
				return false;
			}
			if(IsEmpty(formEdtRubrica.valor_total)){
				alert('O campo valor total deve ser preenchido.')
				formEdtRubrica.valor_total.focus();
				return false;
			}
		   
        	return true;
        }
        
        function voltarForm(form){
           form.task.value = 'gerenciarRubricadeprojetos';
           form.submit();
           return true;
        }
    </script>

	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    
   <form method="post" name="formEdtRubrica" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post"  onsubmit="javascript:return ValidateformCadastro(this)">
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        <div class="cpanel2">
        
            <div class="icon" id="toolbar-save">
                <a href="javascript:if(ValidateformEdtRubrica(document.formEdtRubrica))document.formEdtRubrica.submit()">
                <span class="icon-32-save"></span>Salvar</a>
            </div>
            
            <div class="icon" id="toolbar-cancel">
                <a href="javascript:voltarForm(document.formEdtRubrica)">
                <span class="icon-32-back"></span>Voltar</a>
            </div>
            
        	</div>
        	<div class="clr"></div>
   		</div>
      	<div class="pagetitle icon-48-cpanel"><h2>Edição de Rubricas</h2></div>
       </div>
    </div>
    
	<b>Como proceder: </b>
	<ul><li>Altere os campos necessários da Rubrica <font color="#FF0000">(* Campos Obrigatórios)</font>.</li></ul>
	<hr style="width: 100%; height: 2px;">
    
	<table border="0" cellpadding="1" cellspacing="2" width="100%">
	    <tr style="background-color: #7196d8;">
        	<td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
        </tr>
	<tr>
	  <td width="194">
	    <tr>
	      <input name='id2' type='hidden' value="<?php echo $rubricadeprojeto->id;?>" />
        </tr>
	    <tr>
	      <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Descrição:</b></font></td>
	      <td width="885"><input size="50" name="descricao" class="inputbox" value="<?php echo $rubricadeprojeto->descricao;?>" /></td>
        </tr>
	    <tr>
	      <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Valor Total:</b></font></td>
	      <td><input size="15" name="valor_total" class="inputbox" onKeyPress="return(MascaraMoeda(this,'.',',',event))" value="<?php echo number_format($rubricadeprojeto->valor_total, 2, ',', '.'); ?>" /></td>
        </tr>
	    <tr>
	      <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Valor Gasto:</b></font></td>
	      <td><input size="15" name="valor_gasto" class="inputbox" onKeyPress="return(MascaraMoeda(this,'.',',',event))" value="<?php echo number_format($rubricadeprojeto->valor_gasto, 2, ',', '.'); ?>" /></td>
        </tr>
	    <tr>
	      <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Valor Disponivel:</b></font></td>
	      <td><input size="15" name="valor_disponivel" class="inputbox" onKeyPress="return(MascaraMoeda(this,'.',',',event))" value="<?php echo number_format($rubricadeprojeto->valor_disponivel, 2, ',', '.'); ?>" /></td>
        </tr>
      </td>
	</tr>
	</table>    

    <input name='idRubricadeprojeto' type='hidden' value='<?php if($rubricadeprojeto) echo $rubricadeprojeto->id; ?>'>
    <input name='task' type='hidden' value='salvarRubricadeprojeto'>
    <input name='buscaDescricao' type='hidden' value='<?php echo $descricao;?>'>
</form>

<?php } ?>



<?php //Salva o registro que foi criado ou editado (recebe salvarRubricadeprojeto)
function salvarRubricadeprojeto($rubricadeprojeto){
	$database = JFactory::getDBO();

	$descricao = JRequest::getVar('descricao');
	$valor_total = JRequest::getVar('valor_total');
	$valor_gasto = JRequest::getVar('valor_gasto');
	$valor_disponivel = JRequest::getVar('valor_disponivel'); 
	
	$valor_total = str_replace(',', '.', str_replace('.', '', $valor_total));
	$valor_gasto = str_replace(',', '.', str_replace('.', '', $valor_gasto));	
	$valor_disponivel = str_replace(',', '.', str_replace('.', '', $valor_disponivel));		
	
	$sql = "UPDATE #__contproj_rubricasdeprojetos SET descricao ='$descricao', valor_total ='$valor_total', valor_gasto ='$valor_gasto', valor_disponivel ='$valor_disponivel' WHERE id = $rubricadeprojeto->id";
	$database->setQuery($sql);

	if($database->Query()) {
		JFactory::getApplication()->enqueueMessage(JText::_('Alteração da rubrica realizada com sucesso!'));
		listarRubricadeprojetos($rubricadeprojeto->projeto_id);
	} else {
		JError::raiseWarning( 100, 'ERRO: Não foi possível alterar a rubrica.' );
		return 0;
	}
}
?>



<?php //Exclui o registro
function excluirRubricadeprojeto($idRubricadeprojeto){
  $database	= JFactory::getDBO();
  $sql = "DELETE FROM #__contproj_rubricasdeprojetos WHERE id = $idRubricadeprojeto";
  $database->setQuery($sql);
  $database->Query();
}
?>



<?php //Cria Relatorio
function relatorioRubricadeprojeto($rubricadeprojeto) {
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
    <div id="toolbar-box"><div class="m">
	  <div class="toolbar-list" id="toolbar">
        <div class="cpanel2">
          
            <div class="icon" id="toolbar-back">
              <a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarRubricadeprojetos&order=<?php echo $rubricadeprojeto->projeto_id; ?>"><span class="icon-32-back"></span>Voltar</a>
            </div>
        
		</div>
        <div class="clr"></div>
          </div>
          <div class="pagetitle icon-48-contact"><h2>Dados do Projeto</h2></div>
        </div>
    </div>
    
   <table border="0" cellpadding="3" cellspacing="2" width="100%">
    <tbody>
      <tr>
        <td><font size="2"><b>Projeto:</b></font></td>
        <td colspan="3"><?php echo $rubricadeprojeto->projeto_id;?></td>
        <td><font size="2"><b>Rubrica:</b></font></td>
        <td colspan="3"> <?php echo $rubricadeprojeto->rubrica_id;?> </td>
      </tr>  
      <tr> 
        <td><font size="2"><b>Descrição:</b></font></td>
        <td colspan="3"> <?php echo $rubricadeprojeto->descricao;?> </td>
        <td><font size="2"><b>valor total:</b></font></td>
        <td colspan="3"> <?php echo $rubricadeprojeto->valor_total;?> </td>
      </tr> 
      <tr> 
        <td><font size="2"><b>Valor gasto:</b></font></td>
        <td colspan="3"> <?php echo $rubricadeprojeto->valor_gasto;?> </td>
        <td><font size="2"><b>valor disponivel:</b></font></td>
        <td colspan="3"> <?php echo $rubricadeprojeto->valor_disponivel;?> </td>
      </tr>
     </tbody>
   </table>
   
	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
	</font>
	<hr>
    
	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
	<link rel="stylesheet" href="components/com_controleprojetos/estilo.css" type="text/css" />
		<div class="cpanel">			
		  <div class="icon-wrapper">
				<div class="icon">
		    	  <a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarDespesas">
           			<img width="32" height="32" border="0" src="components/com_controleprojetos/images/despesa.jpg" /><span><?php echo JText::_( 'Gerenciar Despesas' ); ?></span></a>
				</div>
			</div>
			<div class="icon-wrapper">
				<div class="icon">
		    	  <a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=registrardata">
           			<img width="32" height="32" border="0" src="components/com_controleprojetos/images/datas.png" /><span><?php echo JText::_( 'Registrar Datas' ); ?></span></a>
				</div>
			</div>
			<div class="icon-wrapper">
				<div class="icon">
               	  <a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerarrelatorio">
           			<img width="32" height="32" border="0" src="components/com_controleprojetos/images/relatorio.png" /><span><?php echo JText::_( 'Gerar Relatórios' ); ?></span></a>
				</div>
			</div>
		</div>
<?php } ?>
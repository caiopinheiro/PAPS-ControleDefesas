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


// Listar registros
function listarDespesas($descricao = ""){
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
    $idProjeto = JRequest::getVar('idProjeto');
    $projetopd = identificarProjetopdID($idProjeto);
    $sql = "SELECT * FROM #__contproj_despesas WHERE  projeto_id LIKE  '$idProjeto' AND descricao LIKE '%$descricao%' ORDER BY descricao";
    $database->setQuery( $sql );
    $gerenciarDespesas = $database->loadObjectList();
	?>

<!--Funcionalidades dos formularios (Excluir, Editar e Visualizar) --> 
<script language="JavaScript">
function excluir(form){
   var idSelecionado = 0;
   for(i = 0;i < form.idDespesaSelec.length;i++)
        if(form.idDespesaSelec[i].checked) idSelecionado = form.idDespesaSelec[i].value;
   if(idSelecionado > 0){
               var resposta = window.confirm("Confirme a exclusão do item.");
               if(resposta){
                  form.task.value = 'excluirDespesa';
                  form.idDespesa.value = idSelecionado;
                  form.submit();
               }
   }
   else{
        alert('Selecione o item a ser excluído!')
   }
}

function editar(form){
   var idSelecionado = 0;
   for(i = 0;i < form.idDespesaSelec.length;i++)
        if(form.idDespesaSelec[i].checked) idSelecionado = form.idDespesaSelec[i].value;
   if(idSelecionado > 0){
           form.task.value = 'editarDespesa';
           form.idDespesa.value = idSelecionado;
           form.submit();
   }
   else{
          alert('Selecione o item a ser editado!')
   }
}

function visualizar(form){
   var idSelecionado = 0;
   for(i = 0;i < form.idDespesaSelec.length;i++)
        if(form.idDespesaSelec[i].checked) idSelecionado = form.idDespesaSelec[i].value;
   if(idSelecionado > 0){
               form.task.value = 'verDespesa';//volta para visualizar projeto
               form.idDespesa.value = idSelecionado;
               form.submit();
   }
   else{
           alert('Selecione o item a ser  visualizado.')
   }
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
				<div class="icon" id="toolbar-new">
           		<a href="javascript:document.form.task.value='addDespesa';document.form.submit()">
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
           		<!--<a href="index.php?option=com_controleprojetos&Itemid=<?php // echo $Itemid;?>">-->
                        <a href="javascript:voltarNivelForm(document.form)">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
          </div>
      <!-- ------------------ -->
        <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-contact"><h2>Despesas</h2></div>
    </div></div>
    <!-- Filtro de busca -->
     <table border="0" cellpadding="3" cellspacing="2" width="100%">
    
    <!-- ////// Menu de referencia do PROJETO ////// -->
    <tbody>
      <tr>
        <td><font size="2"><b>Projeto:</b></font></td>
        <td colspan="3"><?php echo $projetopd->nomeprojeto;?></td>
        <td><font size="2"><b>Orçamento:</b></font></td>
        <td colspan="3"> <?php echo number_format($projetopd->orcamento, 2, ',','.');?> </td>
      </tr>  
      <tr> 
        <td><font size="2"><b>Data Inicio:</b></font></td>
        <td colspan="3"> <?php databrRubricadeprojeto($projetopd->data_inicio);?> </td>
        <td><font size="2"><b>Data Fim:</b></font></td>
        <td colspan="3"> <?php databrRubricadeprojeto($projetopd->data_fim);?> </td>
      </tr> 
      <tr>  
        <td><font size="2"><b>Coordenador:</b></font></td>
         <td colspan="3">
            <?php //Acessa a tabela coordenadores para exibir o nome do coordenador ja definido
                 $database->setQuery("SELECT * from #__professores  ORDER  BY nomeProfessor");
	             $coordenador_listas = $database->loadObjectList();
	             foreach($coordenador_listas as $coordenador_final_listas){
                         ?><?php if ($coordenador_final_listas->id == $projetopd->coordenador_id) echo $coordenador_final_listas->nomeProfessor;?></option> <?php
                         }
            ?></td> 
        <td><font size="2"><b>Ag. Fomento:</b></font></td>
        <td colspan="3">
            <?php //Acessa a tabela fomento para exibir o nome do fomento ja definido
                 $database->setQuery("SELECT * from #__contproj_agencias  ORDER  BY sigla");
	             $fomento_listas = $database->loadObjectList();
	             foreach($fomento_listas as $fomento_final_listas){
                         ?><?php if ($fomento_final_listas->id == $projetopd->agencia_id) echo $fomento_final_listas->sigla;?></option> <?php
                         }
            ?></td>
      </tr>
 
      <tr> 
        <td><font size="2"><b>Edital:</b></font></td>
        <td colspan="3"> <?php echo $projetopd->edital;?> </td>
        <td><font size="2"><b>Status:</b></font></td>
        <td colspan="3"> <?php echo $projetopd->status;?></td>
      </tr>
      </tbody>
    <!-- ////// /////////////////////////// //////-->    

    
   </table>
  <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
</font>
<hr>
    <table border="0" cellpadding="0" cellspacing="0" width="50%">
    <tbody>
      <tr>
        <td>
            Filtro por descrição:
        </td>
        <td><input id="buscaDescricao" name="buscaDescricao" size="30" type="text" value="<?php echo $descricao;?>"/>
        </td>
        <td rowspan="2">
            <input type="submit" value="Buscar">
        </td>
      </tr>
    </tbody>
    </table>
    <!-- ------------ -->
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
      <!-- Formulario -->
    <table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
	<thead>
      <tr bgcolor="#002666">
        <th width="3%" align="center"><font color="#FFC000"></font></th>
<!--        <th width="3%" align="center"><font color="#FFC000">ID</font></th>
    	<th width="25%" align="center"><font color="#FFC000">Rubrica</font></th>
        <th width="30%" align="center"><font color="#FFC000">Projeto</font></th>-->
        <th width="20%" align="center"><font color="#FFC000">Descrição</font></th>
<!--        <th width="6%" align="center"><font color="#FFC000">Valor Despesa</font></th>
        <th width="6%" align="center"><font color="#FFC000">Tipo Pessoa</font></th>
        <th width="6%" align="center"><font color="#FFC000">Data da emissão</font></th>
        <th width="6%" align="center"><font color="#FFC000">Identf NF</font></th>
        <th width="6%" align="center"><font color="#FFC000">Nota Fiscal</font></th>
        <th width="6%" align="center"><font color="#FFC000">Identificação Cheque</font></th>
        <th width="6%" align="center"><font color="#FFC000">Data da Emissão Cheque</font></th>
        <th width="6%" align="center"><font color="#FFC000">Valor Cheque</font></th>-->
        <th width="15%" align="center"><font color="#FFC000">Favorecido</font></th>
        <th width="6%" align="center"><font color="#FFC000">CNPJ/CPF</font></th>
      </tr>
     </thead>
     <tbody>
	<?php

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";
	$i = 0;
	foreach( $gerenciarDespesas as $gerenciarItemDespesas ){
		$i = $i + 1;
		if ($i % 2){
		    echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
		 }
		else{
		    echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
	  	 }
	?>
		<td width='16'><input type="radio" name="idDespesaSelec" value="<?php echo $gerenciarItemDespesas->id;?>"></td>
<!--		<td><?php // echo $gerenciarItemDespesas->id;?></td>
                <td><?php // echo $gerenciarItemDespesas->rubrica_id;?></td>
                <td><?php // echo $gerenciarItemDespesas->projeto_id;?></td>                -->
                <td><?php echo $gerenciarItemDespesas->descricao;?></td>
<!--                <td><?php // echo number_format($gerenciarItemDespesas->valor_despesa, 2, ',','.');?></td>
                <td><?php // if ($gerenciarItemDespesas->tipo_pessoa == "1") echo "Física";
//                          else if ($gerenciarItemDespesas->tipo_pessoa == "2") echo "Jurídica";?></td>
                <td><?php // databrDespesa($gerenciarItemDespesas->data_emissao);?></td>
                <td><?php // echo $gerenciarItemDespesas->ident_nf;?></td>
                <td><?php // echo $gerenciarItemDespesas->nf;?></td>
                <td><?php // echo $gerenciarItemDespesas->ident_cheque;?></td>
                <td><?php // databrDespesa($gerenciarItemDespesas->data_emissao_cheque);?></td>
                <td><?php // echo number_format($gerenciarItemDespesas->valor_cheque, 2, ',','.');?></td>-->
                <td><?php echo $gerenciarItemDespesas->favorecido;?></td>
                <td><?php echo number_format($gerenciarItemDespesas->cnpj_cpf, 2, '-','.');?></td>
	</tr>
	<?php
	}
        ?>
     </tbody>
     </table>
     <br>Foi(foram) encontrado(s) <b><?php echo sizeof($gerenciarDespesas);?></b> despesa(s).
     <input name='task' type='hidden' value='gerenciarDespesas'>
     <input name='idProjetopd' type='hidden' value='<?php echo $idProjeto;?>'>
     <input name='idProjeto' type='hidden' value='<?php echo $idProjeto;?>'><!--referencia o ID do PROJETO para edicao--> 
     <input name='idDespesaSelec' type='hidden' value='0'>
     <input name='idDespesa' type='hidden' value=''>

     </form>
<!-- ---------------------------- -->
 <?php
}
//////////////////////////////////////////


// Identifica o id do registro selecionado pelo radio button
function identificarDespesaID($idDespesa){
    $database	= JFactory::getDBO();
    $sql = "SELECT * FROM #__contproj_despesas WHERE id = $idDespesa LIMIT 1";
    $database->setQuery( $sql );
    $Despesa = $database->loadObjectList();
    return ($Despesa[0]);
}
//////////////////////////////////////////


// Criar novo registro
function addDespesa($Despesa = NULL, $descricao, $idProjeto){
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
   form.task.value = 'gerenciarDespesas';
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
	$( "#data_emissao" ).datepicker({dateFormat: 'dd/mm/yy'})
        $( "#data_emissao_cheque" ).datepicker({dateFormat: 'dd/mm/yy'});

	});
</script>
<!-- --------------------------------------------------------------- -->

   <form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post"  onsubmit="javascript:return ValidateformCadastro(this)">
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
                <div class="cpanel2">
                    <div class="icon" id="toolbar-save">
                        <!-- A opcao abaixo usa o recurso de validacao do formulario-->
                        <a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
                        <!--A opcao abaixo acessa o formulario de forma direta-->
                        <!--<a href="javascript:document.formCadastro.task.value='salvarDespesa';document.formCadastro.submit()">-->
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
  <ul>
   <li>Preencha todos os campos com os dados da Despesa <font color="#FF0000">(* Campos Obrigatórios)</font>.</li>
  </ul>
   <hr style="width: 100%; height: 2px;">
   <table border="0" cellpadding="1" cellspacing="2" width="100%">
    <tbody>
    <tr>
        <input name='id' type='hidden' value="NULL"><!--referencia o ID visualizacao-->
    </tr>
    <tr>
        <td><font size="2"><font color="#FF0000">*</font> <b>Rubrica:</b></font></td>
               <td colspan="2">
          <select name="rubrica_id" class="inputbox">
            <option value=""></option>
            <?php //Acessa a tabela rubricas para exibir em list box
                 $database->setQuery("SELECT * from #__contproj_rubricas  ORDER  BY nome");
	             $rubrica_listas = $database->loadObjectList();
	             foreach($rubrica_listas as $rubrica_final_listas){
                         ?> <option value="<?php echo $rubrica_final_listas->id;?>"><?php echo $rubrica_final_listas->codigo;?> - <?php echo $rubrica_final_listas->nome;?></option><?php
                         }
            ?>
          </select>
         </td>
    </tr>
   <input name='projeto_id' type='hidden' value='<?php echo $idProjeto;?>'><!--referencia o ID do PROJETO para novo-->
   <tr>
        <td><font size="2"><font color="#FF0000">*</font> <b>Descrição:</b></font></td>
        <td><input maxlength="80" size="80" name="descricao" class="inputbox" value=""></td>
    </tr>
    <tr>
        <td><font size="2"><font color="#FF0000">*</font> <b>Valor Despesa:</b></font></td>
        <td><input maxlength="15" size="15" name="valor_despesa" class="inputbox" value=""></td>
    </tr> 
        <td><font size="2"><font color="#FF0000">*</font> <b>Tipo Pessoa:</b></font></td>
    <td>
        <select name="tipo_pessoa" class="inputbox">
        <option value=""></option>
        <option value="1">Física</option>
        <option value="2">Jurídica</option>
        </select>
    </td>
    <tr>
        <td><font size="2"><font color="#FF0000">*</font> <b>Data Emissão:</b></font></td>
        <td><input maxlength="10" size="10" id="data_emissao" name="data_emissao" class="inputbox" value="" /></td>
    </tr>
    <tr>
        <td><font size="2"><font color="#FF0000">*</font> <b>identificação Nota Fiscal:</b></font></td>
        <td><input maxlength="15" size="15" name="ident_nf" class="inputbox" value=""></td>
    </tr> 
    <tr>
        <td><font size="2"><font color="#FF0000"></font> <b>Nota Fiscal:</b></font></td>
        <td><input maxlength="15" size="15" name="nf" class="inputbox" value=""></td>
    </tr> 
    <tr>
        <td><font size="2"><font color="#FF0000"></font> <b>Identificação cheque:</b></font></td>
        <td><input maxlength="15" size="15" name="ident_cheque" class="inputbox" value=""></td>
    </tr> 
    <tr>
        <td><font size="2"></font> <b>Data Emissão do Cheque:</b></font></td>
        <td><input maxlength="10" size="10" id="data_emissao_cheque" name="data_emissao_cheque" class="inputbox" value="" /></td>
    </tr>
    <tr>
    
    <tr>
        <td><font size="2"><font color="#FF0000"></font> <b>Valor Cheque:</b></font></td>
        <td><input maxlength="15" size="15" name="valor_cheque" class="inputbox" value=""></td>
    </tr> 
    <tr>
        <td><font size="2"><font color="#FF0000"></font> <b>Favorecido:</b></font></td>
        <td><input maxlength="60" size="60" name="favorecido" class="inputbox" value=""></td>
    </tr> 
    <tr>
        <td><font size="2"><font color="#FF0000"></font> <b>CNPJ/CPF:</b></font></td>
        <td><input maxlength="15" size="15" name="cnpj_cpf" class="inputbox" value=""></td>
    </tr> 
   </tbody>
   </table>
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    <input name='idDespesa' type='hidden' value='<?php if($Despesa) echo $Despesa->id;?>'>
    <input name='idProjeto' type='hidden' value='<?php echo $idProjeto;?>'>    
    <input name='task' type='hidden' value='salvarDespesa'>
    <input name='buscaDescricao' type='hidden' value='<?php echo $descricao;?>'>
</form>
    <?php
}
//////////////////////////////////////////



// Editar o registro selecionado pela variavel idDespesa
function editarDespesa($Despesa = NULL, $descricao){
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
   form.task.value = 'gerenciarDespesas';
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
	$( "#data_emissao" ).datepicker({dateFormat: 'dd/mm/yy'})
        $( "#data_emissao_cheque" ).datepicker({dateFormat: 'dd/mm/yy'});

	});
</script>
<!-- --------------------------------------------------------------- -->
   <form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post"  onsubmit="javascript:return ValidateformCadastro(this)">
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
                <div class="cpanel2">
                    <div class="icon" id="toolbar-save">
                        <!-- A opcao abaixo usa o recurso de validacao do formulario-->
                        <a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
                        <!--A opcao abaixo acessa o formulario de forma direta-->
                        <!--<a href="javascript:document.formCadastro.task.value='salvarDespesa';document.formCadastro.submit()">-->
           		<span class="icon-32-save"></span>Salvar</a>
                   </div>
                    <div class="icon" id="toolbar-cancel">
           		<a href="javascript:voltarForm(document.formCadastro)">
           		<span class="icon-32-cancel"></span>Cancelar</a>
		    </div>
		</div>
            <div class="clr"></div>
       </div>
            <div class="pagetitle icon-48-cpanel"><h2>Edicão de Rubricas</h2></div>
       </div>
    </div>
  <b>Como proceder: </b>
  <ul>
   <li>Edite os dados do projeto <font color="#FF0000">(* Campos Obrigatórios)</font>.</li>
  </ul>
   <hr style="width: 100%; height: 2px;">
   <table border="0" cellpadding="1" cellspacing="2" width="100%">
   <tbody>
    <tr>
        <input name='id' type='hidden' value="<?php echo $Despesa->id;?>"><!--referencia o ID da DESPESA para edicao--> 
    </tr>
    <tr>
        <td><font size="2"><font color="#FF0000">*</font> <b>Rubrica:</b></font></td>
          <td colspan="2">
          <select name="rubrica_id" class="inputbox">
            <option value=""></option>
            <?php //Acessa a tabela rubricas para exibir em list box
                 $database->setQuery("SELECT * from #__contproj_rubricas  ORDER  BY nome");
	             $rubrica_listas = $database->loadObjectList();
	             foreach($rubrica_listas as $rubrica_final_listas){
                         ?><option value="<?php echo $rubrica_final_listas->id;?>"<?php if ($rubrica_final_listas->id == $Despesa->rubrica_id) echo 'SELECTED';?>><?php echo $rubrica_final_listas->codigo;?> - <?php echo $rubrica_final_listas->nome;?></option><?php
                         }
            ?>
          </select>
         </td>
    </tr>
    <input name='projeto_id' type='hidden' value="<?php  echo $Despesa->projeto_id;?>"><!--referencia o ID do PROJETO para edicao--> 
    <tr>
        <td><font size="2"><font color="#FF0000">*</font> <b>Descrição:</b></font></td>
        <td><input maxlength="80" size="80" name="descricao" class="inputbox" value="<?php echo $Despesa->descricao;?>"></td>
    </tr> 
    <tr>
        <td><font size="2"><font color="#FF0000">*</font> <b>Valor Despesa:</b></font></td>
        <td><input maxlength="15" size="15" name="valor_despesa" class="inputbox" value="<?php echo $Despesa->valor_despesa;?>"></td>
    </tr> 
          <td><font size="2"><font color="#FF0000">*</font><b>Tipo Pessoa:</b></font></td>
    <td>
        <select name="tipo_pessoa" class="inputbox">
        <option value="1" <?php if ($Despesa->tipo_pessoa == "1") echo 'SELECTED';?>>Física</option>
        <option value="2" <?php if ($Despesa->tipo_pessoa == "2") echo 'SELECTED';?>>Jurídica</option>
        </select>
    </td>    
    <tr>
        <td><font size="2"></font> <b>Data Emissão:</b></font></td>
        <td><input maxlength="10" size="10" id="data_emissao" name="data_emissao" class="inputbox" value="<?php databrDespesa($Despesa->data_emissao);?>" /></td>
    </tr>
    <tr>
        <td><font size="2"><font color="#FF0000">*</font> <b>Identificação Nota Fiscal:</b></font></td>
        <td><input maxlength="15" size="15" name="ident_nf" class="inputbox" value="<?php echo $Despesa->ident_nf;?>"></td>
    </tr> 
    <tr>
        <td><font size="2"><font color="#FF0000">*</font> <b>Nota Fiscal:</b></font></td>
        <td><input maxlength="15" size="15" name="nf" class="inputbox" value="<?php echo $Despesa->nf;?>"></td>
    </tr> 
    <tr>
        <td><font size="2"><font color="#FF0000">*</font> <b>Identificação Cheque:</b></font></td>
        <td><input maxlength="15" size="15" name="ident_cheque" class="inputbox" value="<?php echo $Despesa->ident_cheque;?>"></td>
    </tr> 
    
    <tr>
        <td><font size="2"></font> <b>Data Emissão do Cheque:</b></font></td>
        <td><input maxlength="10" size="10" id="data_emissao_cheque" name="data_emissao_cheque" class="inputbox" value="<?php databr($Despesa->data_emissao_cheque);?>" /></td>
    </tr>
    <tr>
        <td><font size="2"><font color="#FF0000">*</font> <b>Valor Cheque:</b></font></td>
        <td><input maxlength="15" size="15" name="valor_cheque" class="inputbox" value="<?php echo $Despesa->valor_cheque;?>"></td>
    </tr> 
    <tr>
        <td><font size="2"><font color="#FF0000">*</font> <b>favorecido:</b></font></td>
        <td><input maxlength="60" size="60" name="favorecido" class="inputbox" value="<?php echo $Despesa->favorecido;?>"></td>
    </tr> 
    <tr>
        <td><font size="2"><font color="#FF0000">*</font> <b>CNPJ / CPF:</b></font></td>
        <td><input maxlength="15" size="15" name="cnpj_cpf" class="inputbox" value="<?php echo $Despesa->cnpj_cpf;?>"></td>
    </tr> 
    
   </tbody>
   </table>
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    <input name='idDespesa' type='hidden' value='<?php if($Despesa) echo $Despesa->id;?>'><!--referencia o ID da DESPESA para edicao--> 
    <input name='idProjeto' type='hidden' value='<?php if($Despesa) echo $Despesa->projeto_id;?>'><!--referencia o ID do PROJETO para edicao--> 
    <input name='task' type='hidden' value='salvarDespesa'>
    <input name='buscaDescricao' type='hidden' value='<?php echo $descricao;?>'>
</form>
    <?php
}
//////////////////////////////////////////



//Cria Relatorio
function relatorioDespesa($Despesa) {
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
?>
<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post" >    
<div id="toolbar-box"><div class="m">
                        <div class="toolbar-list" id="toolbar">
                            <div class="cpanel2">
                                <div class="icon" id="toolbar-back">
                                    <a href="javascript:document.form.submit()">
                                    <span class="icon-32-back"></span>Voltar</a>
                                </div>
                            </div>
                                <div class="clr"></div>
                        </div>
                            <div class="pagetitle icon-48-contact"><h2>Dados da Despesa</h2></div>
                      </div>
</div>
<table border="0" cellpadding="3" cellspacing="2" width="100%">
    <tbody>
       <tr>
            <td><font size="2"><b>ID:</b></font></td>
            <td colspan="3"><?php echo $Despesa->id;?></td>
       </tr>  
       <tr>
            <td><font size="2"><b>Rubrica:</b></font></td>
            <td colspan="3"><?php echo $Despesa->rubrica_id;?></td>
       </tr>  
       <tr>
            <td><font size="2"><b>Projeto:</b></font></td>
            <td colspan="3"><?php echo $Despesa->projeto_id;?></td>
       </tr>  
       <tr>     
            <td><font size="2"><b>Descrição:</b></font></td>
            <td colspan="3"> <?php echo $Despesa->descricao;?> </td>
       </tr>  
       <tr> 
            <td><font size="2"><b>Valor da Despesa:</b></font></td>
            <td colspan="3"> <?php echo $Despesa->valor_despesa;?> </td>
       </tr>  
       <tr> 
            <td><font size="2"><b>Tipo Pessoa:</b></font></td>
            <td colspan="3"> <?php echo $Despesa->tipo_pessoa;?> </td>
       </tr> 
       <tr> 
            <td><font size="2"><b>Data Emissão:</b></font></td>
            <td colspan="3"> <?php echo $Despesa->data_emissao;?> </td>
       </tr> 
       <tr> 
            <td><font size="2"><b>Identificacão Cheque:</b></font></td>
            <td colspan="3"> <?php echo $Despesa->ident_cheque;?> </td>
       </tr> 
       <tr> 
            <td><font size="2"><b>Data Emissão:</b></font></td>
            <td colspan="3"> <?php echo $Despesa->data_emissao_cheque;?> </td>
       </tr> 
       <tr> 
            <td><font size="2"><b>Valor Cheque:</b></font></td>
            <td colspan="3"> <?php echo $Despesa->valor_cheque;?> </td>
       </tr> 
       <tr> 
            <td><font size="2"><b>Favorecido:</b></font></td>
            <td colspan="3"> <?php echo $Despesa->favorecido;?> </td>
       </tr> 
       <tr> 
            <td><font size="2"><b>CNPJ/CPF:</b></font></td>
            <td colspan="3"> <?php echo $Despesa->cnpj_cpf;?> </td>
       </tr> 
    </tbody>
</table>
<hr><!--linha fina-->
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    <input name='idProjeto' type='hidden' value='<?php echo $Despesa->projeto_id;?>'>
    <input name='task' type='hidden' value='gerenciarDespesas'>
</form>
<?php
}



//Exclui o registro
function excluirDespesa($idDespesa){
  $database	= JFactory::getDBO();
  $sql = "DELETE FROM #__contproj_despesas WHERE id = $idDespesa";
  $database->setQuery($sql);
  $database->Query();
  

}
//////////////////////////////////////////



// Formata data aaaa-mm-dd para dd/mm/aaaa
function databrDespesa($datasqlDespesa) {
	if (!empty($datasqlDespesa)){
	$p_dt = explode('-',$datasqlDespesa);
    	$data_brDespesa = $p_dt[2].'/'.$p_dt[1].'/'.$p_dt[0];
	return print $data_brDespesa;
	}
}
//////////////////////////////////////////




// Formata data dd/mm/aaaa para aaaa-mm-dd
function datasqlDespesa($databrDespesa) {
	if (!empty($databrDespesa)){
	$p_dt = explode('/',$databrDespesa);
	$data_sqlDespesa = $p_dt[2].'-'.$p_dt[1].'-'.$p_dt[0];
	return  $data_sqlDespesa;
	}
}
//////////////////////////////////////////



//Salva o registro que foi criado ou editado (recebe salvarDespesa)
function salvarDespesa($idDespesa = ""){
  $database = JFactory::getDBO();
  $id = JRequest::getVar('id');
  $rubrica_id = JRequest::getVar('rubrica_id');
  $projeto_id = JRequest::getVar('projeto_id');
  $descricao = JRequest::getVar('descricao');
  $valor_despesa = JRequest::getVar('valor_despesa');
  $tipo_pessoa = JRequest::getVar('tipo_pessoa');
  $data_emissao = datasqlDespesa(JRequest::getVar('data_emissao'));
  $ident_nf = JRequest::getVar('ident_nf');
  $nf = JRequest::getVar('nf');
  $ident_cheque = JRequest::getVar('ident_cheque');
  $data_emissao_cheque = datasqlDespesa(JRequest::getVar('data_emissao_cheque'));
  $valor_cheque = JRequest::getVar('valor_cheque');
  $favorecido = JRequest::getVar('favorecido');
  $cnpj_cpf = JRequest::getVar('cnpj_cpf');
  
  if($idDespesa == ""){//condicao se o cadastro for novo
      $sql = "SELECT * FROM #__contproj_despesas WHERE descricao = '$descricao'";
      $database->setQuery($sql);
      $repetido = $database->loadObjectList();
        if(!$repetido){
           $sql = "INSERT INTO #__contproj_despesas
                              (id, rubrica_id, projeto_id, descricao, valor_despesa, tipo_pessoa, data_emissao,
                              ident_nf, nf, ident_cheque, data_emissao_cheque, valor_cheque, favorecido, cnpj_cpf)
                               VALUES
                              ('$id', '$rubrica_id', '$projeto_id', '$descricao', '$valor_despesa', '$tipo_pessoa',
                               '$data_emissao', '$ident_nf', '$nf','$ident_cheque', '$data_emissao_cheque', '$valor_cheque',
                               '$favorecido', '$cnpj_cpf')";
           $database->setQuery($sql);
           if($database->Query()){
             JFactory::getApplication()->enqueueMessage(JText::_('Cadastro realizado com sucesso!'));
             return 1;
             }
             else{
             JFactory::getApplication()->enqueueMessage(JText::_('Cadastro não realizado, despesa já cadastrada!'));
             return 1;
             }
        }
        else{
            JFactory::getApplication()->enqueueMessage(JText::_('Cadastro não realizado, despesa já cadastrada!'));
            return (0);
        }
    }
     else if($idDespesa == $id){//condicao se o cadastro for editado
         //colocar os itens depois do where que se quer alterar se nao estiver vai identificar como ja existente.
         //Mas nao esqueça deve-se ativar os recursos uniques na base de dados para os itens que nao podem ser repetidos
         $sql = "SELECT * FROM #__contproj_despesas WHERE id = '$id', rubrica_id ='$rubrica_id', projeto_id ='$projeto_id',
                    descricao ='$descricao', valor_despesa ='$valor_despesa', tipo_pessoa ='$tipo_pessoa',
                    data_emissao ='$data_emissao', ident_nf ='$ident_nf', nf ='$nf', ident_cheque ='$ident_cheque',
                    data_emissao_cheque ='$data_emissao_cheque', valor_cheque ='$valor_cheque', favorecido ='$favorecido',
                    cnpj_cpf ='$cnpj_cpf'";
     $database->setQuery($sql);
      $repetido = $database->loadObjectList();
        if(!$repetido){
             $sql = "UPDATE #__contproj_despesas SET id = '$id', rubrica_id ='$rubrica_id', projeto_id ='$projeto_id',
                    descricao ='$descricao', valor_despesa ='$valor_despesa', tipo_pessoa ='$tipo_pessoa',
                    data_emissao ='$data_emissao', ident_nf ='$ident_nf', nf ='$nf', ident_cheque ='$ident_cheque',
                    data_emissao_cheque ='$data_emissao_cheque', valor_cheque ='$valor_cheque', favorecido ='$favorecido',
                    cnpj_cpf ='$cnpj_cpf' WHERE  id = $idDespesa";                 
             $database->setQuery($sql);
             if($database->Query()){
               JFactory::getApplication()->enqueueMessage(JText::_('Edição realizada com sucesso!'));
               return 1;
             }
             else{
                JFactory::getApplication()->enqueueMessage(JText::_('Edição não realizada, despesa já cadastrada!'));
                return 1;
             }
        }
               else{
                   JFactory::getApplication()->enqueueMessage(JText::_('Edição não realizada, despesa já cadastrada!'));
                   return (1);
              }
        }     
}

// *********************************************************************/
// ********************    Manaus Fev 2013    **************************/
// *********************************************************************/

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

// Listar registros
function listarProjetopds($nome = ""){
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
	
    $sql= "  SELECT a.*, b.nomeProfessor, c.sigla AS sigla 
             FROM #__contproj_projetos AS a 
             INNER JOIN #__professores b
             ON a.coordenador_id = b.id 
             INNER JOIN #__contproj_agencias c
             ON a.agencia_id = c.id
             WHERE nomeprojeto  
             LIKE '%$nome%' ORDER BY nomeprojeto";
    $database->setQuery( $sql );
    $gerenciarProjetopds = $database->loadObjectList();
	?>

    <!--Funcionalidades dos formularios (Excluir, Editar e Visualizar) --> 
    <script language="JavaScript">
        function excluir(form) {
            var idSelecionado = 0;
            for(i = 0;i < form.idProjetopdSelec.length;i++)
                if(form.idProjetopdSelec[i].checked) idSelecionado = form.idProjetopdSelec[i].value;
            
            if(idSelecionado > 0){
                var resposta = window.confirm("Confirme a exclusão do item.");
                
                if(resposta){
                    form.task.value = 'excluirProjetopd';
                    form.idProjetopd.value = idSelecionado;
                    form.submit();
                }
            } else {
                alert('Selecione o item a ser excluído!')
            }
        }
        
        function editar(form) {
            var idSelecionado = 0;
            for(i = 0;i < form.idProjetopdSelec.length;i++)
                if(form.idProjetopdSelec[i].checked) idSelecionado = form.idProjetopdSelec[i].value;
                
            if(idSelecionado > 0){
                form.task.value = 'editarProjetopd';
                form.idProjetopd.value = idSelecionado;
                form.submit();
            } else {
                alert('Selecione um projeto para ser editado!')
            }
        }
        
        function visualizar(form) {
            var idSelecionado = 0;
            for(i = 0;i < form.idProjetopdSelec.length;i++)
                if(form.idProjetopdSelec[i].checked) idSelecionado = form.idProjetopdSelec[i].value;
                
            if(idSelecionado > 0) {
                form.task.value = 'verProjetopd';
                form.idProjetopd.value = idSelecionado;
                form.submit();
            } else {
                alert('Selecione o item a ser visualizado.')
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
        
<!-- Formulario da Funcao gerenciarProjetospd -->
<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post" >
    <!-- Barra ferramentas -->
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-new">
           			<a href="javascript:document.form.task.value='addProjetopd';document.form.submit()">
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
           			<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
          </div>

        <div class="clr"></div>
	</div>
	<div class="pagetitle icon-48-contact"><h2>Projetos</h2></div>
    </div></div>
    
    <!-- Filtro de busca -->
    <table border="0" cellpadding="0" cellspacing="0" width="50%">
      <tr>
        <td>Filtro por projeto:</td>
        <td><input id="buscaNome" name="buscaNome" size="30" type="text" value="<?php echo $nome;?>"/>
        </td>
        <td rowspan="2"><input type="submit" value="Buscar"></td>
      </tr>
    </table>

    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    <!-- Formulario -->
    <table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
    <thead>
        <tr bgcolor="#002666">
        <th width="3%" align="center"><font color="#FFC000"></font></th>
        <th width="25%" align="center"><font color="#FFC000">Projeto</font></th>
        <th width="10%" align="center"><font color="#FFC000">Data Início</font></th>
        <th width="10%" align="center"><font color="#FFC000">Data Fim</font></th>
        <th width="20%" align="center"><font color="#FFC000">Coordenador</font></th>
        <th width="6%" align="center"><font color="#FFC000">Orçamento</font></th>
        <th width="6%" align="center"><font color="#FFC000">Saldo</font></th>
        <th width="8%" align="center"><font color="#FFC000">Status</font></th>
        </tr>
    </thead>
    <tbody>
    
	<?php
	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";
	
	$status_projeto = array (1 => "Cadastrado",
							2 => "Iniciado",
							3 => "Finalizado",
							4 => "Cancelado",
							5 => "Prorrogado"); 
										
	$i = 0;
	foreach( $gerenciarProjetopds as $gerenciarItemProjetopds ) {
		$i = $i + 1;
		if ($i % 2) {
		    echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
		 } else {
		    echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
	  	 } ?>
         
		<td width='16'><input type="radio" name="idProjetopdSelec" value="<?php echo $gerenciarItemProjetopds->id;?>"></td>
		<td><?php echo $gerenciarItemProjetopds->nomeprojeto;?></td>
        <td><?php if ($gerenciarItemProjetopds->data_inicio == '0000-00-00') 
					echo '';
				  else 
					databr($gerenciarItemProjetopds->data_inicio);?>
		</td>
        <td><?php if ($gerenciarItemProjetopds->data_fim == '0000-00-00') 
					echo '';
				  else 
				    databr($gerenciarItemProjetopds->data_fim);?>
		</td>
        <td><?php echo $gerenciarItemProjetopds->nomeProfessor;?></td>
        <td><?php echo number_format($gerenciarItemProjetopds->orcamento, 2, ',','.');?></td>
        <td><?php echo number_format($gerenciarItemProjetopds->saldo, 2, ',','.');?></td>
        <td><img border='0' src='components/com_controleprojetos/images/proj-status<?php echo $gerenciarItemProjetopds->estado; ?>.fw.png' title='<?php echo $status_projeto[$gerenciarItemProjetopds->estado];?>' width="19" height="20">
		</td>
	</tr>
	<?php } ?>
	</tbody>
    </table>
    
    <br>Total de Projetos: <b><?php echo sizeof($gerenciarProjetopds);?></b>
    <input name='task' type='hidden' value='gerenciarProjetopds'>
    <input name='idProjetopdSelec' type='hidden' value='0'>
    <input name='idProjetopd' type='hidden' value=''>
</form>

<?php } ?>


<?php // Identifica o id do registro selecionado pelo radio button
function identificarProjetopdID($idProjetopd){
    $database	= JFactory::getDBO();
    $sql = "SELECT * FROM #__contproj_projetos WHERE id = $idProjetopd LIMIT 1";
    $database->setQuery( $sql );
    $projetopd = $database->loadObjectList();
	
    return ($projetopd[0]);
}
?>


<?php // Criar novo registro
function addProjetopd($projetopd = NULL, $nomeprojeto){
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
           if(IsEmpty(formCadastro.nomeprojeto)){
              alert('O campo Nome deve ser preenchido.')
              formCadastro.nomeprojeto.focus();
              return false;
           }
           if(IsEmpty(formCadastro.nomecoordenador_id)){
              alert('O campo coordenador deve ser preenchido.')
              formCadastro.nomecoordenador_id.focus();
              return false;
           }
           if(IsEmpty(formCadastro.siglagerfomento_id)){
              alert('O campo Fomento deve ser preenchido.')
              formCadastro.siglagerfomento_id.focus();
              return false;
           }
           if(IsEmpty(formCadastro.banco_id)){
              alert('O campo Banco deve ser preenchido.')
              formCadastro.banco_id.focus();
              return false;
           }
		   
           return true;
        }
        
        function voltarForm(form){
           form.task.value = 'gerenciarProjetopds';
           form.submit();
           return true;
        }
    </script>
    
    <!--Recurso de data interativa  (os componentes Jquery, a funcao e a linha de comando para chamada da funcao)-->    
     <link type="text/css" href="components/com_controleprojetos/jquery-ui-1.10.1.custom/css/ui-lightness/jquery-ui-1.10.1.custom.css" rel="Stylesheet" />
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>  

	<!-- CALENDARIO -->
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
		
		$( "#data_inicio" ).datepicker({dateFormat: 'dd/mm/yy'})
    	    $( "#data_fim" ).datepicker({dateFormat: 'dd/mm/yy'});
	});
    </script>
    
    <!-- MÁSCARA DE REAIS -->
	<script type="text/javascript" src="components/com_controleprojetos/jquery/javascripts/scripts.js"></script>
    
   	<form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post"  onsubmit="javascript:return ValidateformCadastro(this)" enctype="multipart/form-data">
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
        
        <div class="pagetitle icon-48-cpanel"><h2>Cadastro de Projetos</h2></div>
       </div>
    </div>
    
    <b>Como proceder: </b>
    <ul><li>Preencha todos os campos com os dados da despesa <font color="#FF0000">(* Campos Obrigatórios)</font>.</li></ul>
	<hr style="width: 100%; height: 2px;">
    
    <table width="100%" border="0" cellspacing="2" cellpadding="2">
    	<tr style="background-color: #7196d8;">
			<td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
		</tr>
        <tr><input name='id' type='hidden' value="NULL" /></tr>
        <tr>
            <td width="24%" bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Projeto:</b></font></td>
            <td width="76%"><input maxlength="80" size="60" name="nomeprojeto" class="inputbox" /></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Orcamento:</b></font></td>
            <td><input id="orcamento" name="orcamento" type="text" onKeyPress="return(MascaraMoeda(this,'.',',',event))" /></td>
        </tr> 
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"></font> <b>Data Inicio:</b></font></td>
            <td><input id="data_inicio" name="data_inicio" class="inputbox" /></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"></font> <b>Data Fim:</b></font></td>
            <td><input id="data_fim" name="data_fim" class="inputbox" /></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font><b>Coordenador:</b></font></td>
             <td colspan="2">
              <select name="nomecoordenador_id" class="inputbox">
                <option value=""></option>
                <?php //Acessa a tabela coordenadores para exibir em list box
                     $database->setQuery("SELECT * from #__professores  ORDER  BY nomeProfessor");
                     $coordenador_listas = $database->loadObjectList();
                     foreach($coordenador_listas as $coordenador_final_listas) { ?> 
                     	<option value="<?php echo $coordenador_final_listas->id;?>"><?php echo $coordenador_final_listas->nomeProfessor;?></option>
					<?php } ?>
              </select>
             </td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Agência de Fomento:</b></font></td>
            <td colspan="2">
              <select name="siglagerfomento_id" class="inputbox" style="width:150px">
                <option value=""></option>
                <?php //Acessa a tabela fomentos para exibir em list box
                     $database->setQuery("SELECT * from #__contproj_agencias  ORDER  BY sigla");
                     $fomento_listas = $database->loadObjectList();
                     foreach($fomento_listas as $fomento_final_listas) { ?> 
                        <option value="<?php echo $fomento_final_listas->id;?>"><?php echo $fomento_final_listas->sigla;?></option>				
                     <?php } ?>
              </select>
            </td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Banco:</b></font></td>
            <td colspan="2">
              <select name="banco_id" class="inputbox">
                <option value=""></option>
                <?php //Acessa a tabela bancos para exibir em list box
                     $database->setQuery("SELECT * from #__contproj_bancos  ORDER  BY nome");
                     $banco_listas = $database->loadObjectList();
                     foreach($banco_listas as $banco_final_listas) { ?> 
                        <option value="<?php echo $banco_final_listas->id;?>"><?php echo $banco_final_listas->nome;?></option>
                      <?php } ?>
              </select>
            </td>
        </tr>   
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"></font> <b>Agência:</b></font></td>
            <td><input size="10" name="agencia" class="inputbox" /></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"></font> <b>Conta:</b></font></td>
            <td><input size="10" name="conta" class="inputbox" /></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"></font> <b>Edital:</b></font></td>
            <td><input name="edital" class="inputbox" style="width:277px;" /> <i style="color:#F00;">Link do Edital</i></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"></font> <b>Proposta:</b></font></td>
            <td><input type="file" name="proposta" /></td>
        </tr>
    </table>
   
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    <input name='idProjetopd' type='hidden' value='<?php if($projetopd) echo $projetopd->id;?>'>
    <input name='task' type='hidden' value='salvarProjetopd'>
    <input name='buscaNome' type='hidden' value='<?php echo $nomeprojeto;?>'>        
</form>
<?php } ?>


<?php // Editar o registro selecionado pela variavel idProjetopd
function editarProjetopd($projetopd = NULL, $nomeprojeto) {
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
		   if(IsEmpty(formCadastro.nomeprojeto)){
			  alert('O campo projeto deve ser preenchido.')
			  formCadastro.nomeprojeto.focus();
			  return false;
		   }
		   if(IsEmpty(formCadastro.nomecoordenador_id)){
			  alert('O campo coordenador deve ser preenchido.')
			  formCadastro.nomecoordenador_id.focus();
			  return false;
		   }
		   if(IsEmpty(formCadastro.siglagerfomento_id)){
			  alert('O campo fomento deve ser preenchido.')
			  formCadastro.siglagerfomento_id.focus();
			  return false;
		   }
		   if(IsEmpty(formCadastro.banco_id)){
			  alert('O campo banco deve ser preenchido.')
			  formCadastro.banco_id.focus();
			  return false;
		   }
		   
		   return true;
		}
		
		function voltarForm(form){
		   form.task.value = 'gerenciarProjetopds';
		   form.submit();
		   return true;
		}
    </script>
    
    <!-- MÁSCARA DE REAIS -->
	<script type="text/javascript" src="components/com_controleprojetos/jquery/javascripts/scripts.js"></script>
    
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
        <div class="pagetitle icon-48-cpanel"><h2>Edição de Projetos</h2></div>
       </div>
    </div>
    
    <b>Como proceder: </b>
    <ul><li>Altere os campos necessários para a atualização do projeto.<font color="#FF0000">(* Campos Obrigatórios)</font>.</li></ul>
    
    <hr style="width: 100%; height: 2px;">
    
    <table width="100%" border="0" cellspacing="2" cellpadding="2">
    	<tr style="background-color: #7196d8;">
			<td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
		</tr>
    	<tr><input name='id' type='hidden' value="<?php echo $projetopd->id;?>"></tr>
        <tr>
            <td width="25%" bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Projeto:</b></font></td>
            <td width="75%"><input type="text" maxlength="80" size="60" name="nomeprojeto" class="inputbox" value="<?php echo $projetopd->nomeprojeto;?>"></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Orcamento:</b></font></td>
            <td><input type="text" id="orcamento" name="orcamento"  class="inputbox" onKeyPress="return(MascaraMoeda(this,'.',',',event))" value="<?php echo number_format($projetopd->orcamento, 2, ',', '.'); ?>"></td>
        </tr> 
    
    <!--Recurso de data interativa  (os componentes Jquery, a funcao e a linha de comando para chamada da funcao)-->    
    <link type="text/css" href="components/com_controleprojetos/jquery-ui-1.10.1.custom/css/ui-lightness/jquery-ui-1.10.1.custom.css" rel="Stylesheet" />
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>  
    
    <!-- CALENDARIO -->
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
			
			$( "#data_inicio" ).datepicker({dateFormat: 'dd/mm/yy'})
			$( "#data_fim" ).datepicker({dateFormat: 'dd/mm/yy'});
		});
    </script>
    
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"></font> <b>Data Inicio:</b></font></td>
            <td><?php if ($projetopd->data_inicio == '0000-00-00') { ?>
						<input id="data_inicio" name="data_inicio" class="inputbox" />
			    <?php } else { ?> 
			            <input id="data_inicio" name="data_inicio" class="inputbox" value="<?php databr($projetopd->data_inicio);?>" />		            	
				<?php } ?> 
			</td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"></font> <b>Data Fim:</b></font></td>
            <td><?php if ($projetopd->data_fim == '0000-00-00') { ?>
						<input id="data_fim" name="data_fim" class="inputbox" />
			    <?php } else { ?> 
			            <input id="data_fim" name="data_fim" class="inputbox" value="<?php databr($projetopd->data_fim);?>" />		            	
				<?php } ?> 
			</td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Coordenador:</b></font></td>
            <td colspan="2">
            <select name="nomecoordenador_id" class="inputbox">
            <option value=""></option>
            <?php //Acessa a tabela coordenadores para exibir em list box
                 $database->setQuery("SELECT * from #__professores  ORDER  BY nomeProfessor");
                 $coordenador_listas = $database->loadObjectList();
                 foreach($coordenador_listas as $coordenador_final_listas) { ?>
                 	<option value="<?php echo $coordenador_final_listas->id;?>"<?php if ($coordenador_final_listas->id == $projetopd->coordenador_id) echo 'SELECTED';?>><?php echo $coordenador_final_listas->nomeProfessor;?></option>
				<?php } ?>
            </select>
            </td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Agência de Fomento:</b></font></td>
            <td colspan="2">
            <select name="siglagerfomento_id" class="inputbox" style="width:150px;">
            <option value=""></option>
            <?php //Acessa a tabela fomentos para exibir em list box
                 $database->setQuery("SELECT * from #__contproj_agencias  ORDER  BY nome");
                 $fomento_listas = $database->loadObjectList();
                 foreach($fomento_listas as $fomento_final_listas) { ?>
                 	<option value="<?php echo $fomento_final_listas->id;?>"<?php if ($fomento_final_listas->id == $projetopd->agencia_id) echo 'SELECTED';?>><?php echo $fomento_final_listas->sigla;?> </option>
				<?php } ?>
            </select>
            </td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Banco:</b></font></td>
            <td colspan="2">
            <select name="banco_id" class="inputbox" style="width:150px;">
            <option value=""></option>
            <?php //Acessa a tabela bancos para exibir em list box
                 $database->setQuery("SELECT * from #__contproj_bancos  ORDER  BY nome");
                 $banco_listas = $database->loadObjectList();
                 foreach($banco_listas as $banco_final_listas) { ?>
                 	<option value="<?php echo $banco_final_listas->id;?>"<?php if ($banco_final_listas->id == $projetopd->banco_id) echo 'SELECTED';?>><?php echo $banco_final_listas->nome;?> </option>
				<?php } ?>
            </select>
            </td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"></font> <b>Agência:</b></font></td>
            <td><input maxlength="10" size="10" name="agencia" class="inputbox" value="<?php echo $projetopd->agencia;?>"></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"></font> <b>Conta:</b></font></td>
            <td><input maxlength="10" size="10" name="conta" class="inputbox" value="<?php echo $projetopd->conta;?>"></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"></font> <b>Edital:</b></font></td>
            <td><input maxlength="10" size="10" name="edital" class="inputbox" value="<?php echo $projetopd->edital;?>"></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"></font> <b>Proposta:</b></font></td>
            <td><input maxlength="10" size="10" name="proposta" class="inputbox" value="<?php echo $projetopd->proposta;?>"></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font><b>Estado:</b></font></td>
            <td>
                <select name="estado" class="inputbox" style="width:150px;">
                <option value="1" <?php if ($projetopd->estado == "1") echo 'SELECTED';?>>Cadastrado</option>
                <option value="2" <?php if ($projetopd->estado == "2") echo 'SELECTED';?>>Iniciado</option>
                <option value="3" <?php if ($projetopd->estado == "3") echo 'SELECTED';?>>Finalizado</option>
                <option value="4" <?php if ($projetopd->estado == "4") echo 'SELECTED';?>>Cancelado</option>
                <option value="5" <?php if ($projetopd->estado == "5") echo 'SELECTED';?>>Prorrogado</option>
                </select>
            </td>
        </tr>
    </table>

    <input name='idProjetopd' type='hidden' value='<?php if($projetopd) echo $projetopd->id;?>' />
    <input name='task' type='hidden' value='atualizarProjetopd' />
    <input name='buscaNome' type='hidden' value='<?php echo $nomeprojeto;?>' />
</form>
<?php } ?>


<?php //Salva o registro que foi criado ou editado (recebe salvarProjetopd)
function salvarProjetopd($idProjetopd = ""){
	$database = JFactory::getDBO();
	
	$id = JRequest::getVar('id');
	$nomeprojeto = JRequest::getVar('nomeprojeto');
	$orcamento = JRequest::getVar('orcamento');
	$data_inicio = datasql(JRequest::getVar('data_inicio'));
	$data_fim = datasql(JRequest::getVar('data_fim'));
	$coordenador_id = JRequest::getVar('nomecoordenador_id');
	$agencia_id = JRequest::getVar('siglagerfomento_id');
	$banco_id = JRequest::getVar('banco_id');
	$agencia = JRequest::getVar('agencia');
	$conta = JRequest::getVar('conta');
	$edital = JRequest::getVar('edital');
	$proposta = "";  
	$estado = 1;
	
	if($_FILES["proposta"]["tmp_name"]){
		$proposta = "components/com_controleprojetos/docprojetos/proposta/PPGI-Proposta-".$id.".pdf";
		move_uploaded_file($_FILES["proposta"]["tmp_name"],$proposta);
	}
	
	$orcamento = str_replace(',', '.', str_replace('.', '', $orcamento));
				
	$sql = "INSERT INTO #__contproj_projetos (nomeprojeto, orcamento, data_inicio, data_fim, coordenador_id, agencia_id, banco_id, agencia, conta, edital, proposta, estado) VALUES ('$nomeprojeto', '$orcamento', '$data_inicio', '$data_fim', '$coordenador_id', '$agencia_id', '$banco_id', '$agencia', '$conta', '$edital', '$proposta', '$estado')";
	$database->setQuery($sql);
		
	if($database->Query()) {
		JFactory::getApplication()->enqueueMessage(JText::_('Cadastro realizado com sucesso!'));
	  	return 1;
	} else {
		JError::raiseWarning( 100, 'ERRO: Não foi possível realizar a alteração.' );
		return 0;
	}		                 
}
?>


<?php // Salva a edição do projeto
function atualizarProjetopd ($projetopd) {
	$database = JFactory::getDBO();
	
	$id = JRequest::getVar('id');
	$nomeprojeto = JRequest::getVar('nomeprojeto');
	$orcamento = JRequest::getVar('orcamento');
	$data_inicio = datasql(JRequest::getVar('data_inicio'));
	$data_fim = datasql(JRequest::getVar('data_fim'));
	$coordenador_id = JRequest::getVar('nomecoordenador_id');
	$agencia_id = JRequest::getVar('siglagerfomento_id');
	$banco_id = JRequest::getVar('banco_id');
	$agencia = JRequest::getVar('agencia');
	$conta = JRequest::getVar('conta');
	$edital = JRequest::getVar('edital');
	$estado = JRequest::getVar('estado');
	
	$orcamento = str_replace(',', '.', str_replace('.', '', $orcamento));
	
	$sql = "UPDATE #__contproj_projetos SET nomeprojeto ='$nomeprojeto', orcamento ='$orcamento', data_inicio ='$data_inicio', data_fim ='$data_fim', coordenador_id ='$coordenador_id', agencia_id ='$agencia_id', banco_id ='$banco_id', agencia ='$agencia', conta ='$conta', edital ='$edital', proposta ='$proposta', estado ='$estado'  WHERE  id = $id";
	$database->setQuery($sql);

	if($database->Query()) {
		JFactory::getApplication()->enqueueMessage(JText::_('Edição do projeto realizada com sucesso!'));
		listarProjetopds($nome);
	} else {
		JError::raiseWarning( 100, 'ERRO: Alteração não realizada.' );
		return 0;
	}
}
?>


<?php //Exclui o registro
function excluirProjetopd($idProjetopd){
	$database	= JFactory::getDBO();
	$sql = "DELETE FROM #__contproj_projetos WHERE id = $idProjetopd";
	$database->setQuery($sql);
	$database->Query();
}


// Formata data aaaa-mm-dd para dd/mm/aaaa
function databr($datasql) {
	if (!empty($datasql)){
	$p_dt = explode('-',$datasql);
    	$data_br = $p_dt[2].'/'.$p_dt[1].'/'.$p_dt[0];
	return print $data_br;
	}
}
 
 
// Formata data dd/mm/aaaa para aaaa-mm-dd
function datasql($databr) {
	if (!empty($databr)){
	$p_dt = explode('/',$databr);
	$data_sql = $p_dt[2].'-'.$p_dt[1].'-'.$p_dt[0];
	return  $data_sql;
	}
}


//Visualiza Informações do Projeto
function relatorioProjetopd($projetopd) {
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
    <script type="text/javascript">
    
    	function gerenciarRubricasProjeto(form){
    	   form.task.value = 'gerenciarRubricadeprojetos';
           form.submit();
    	}
    
	</script>
        
    <form name="formInformacoesProjeto" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" method="post">
    
    	<div id="toolbar-box"><div class="m">
	    <div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-back">
                    <a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarProjetopds"><span class="icon-32-back"></span>Voltar</a>
                </div>
            </div>
	        <div class="clr"></div>
    	</div>
        <div class="pagetitle icon-48-contact"><h2>Informações do Projeto</h2></div>
        </div>
    </div>
    
    <table border="0" cellpadding="3" cellspacing="2" width="100%">
    	<tr style="background-color: #7196d8;">
			<td colspan="6"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
		</tr>
        <tr>
            <td width="18%" bgcolor="#CCCCCC"><font size="2"><b>Projeto:</b></font></td>
            <td colspan="3"><?php echo $projetopd->nomeprojeto;?></td>
            <td width="18%" bgcolor="#CCCCCC"><font size="2"><b>Orçamento:</b></font></td>
            <td width="25%" colspan="3"> <?php echo 'R$ '.number_format($projetopd->orcamento, 2, ',','.'); ?> </td>
        </tr>  
        <tr> 
            <td bgcolor="#CCCCCC"><font size="2"><b>Data Inicio:</b></font></td>
            <td colspan="3"> <?php $dataInicio = $projetopd->data_inicio; echo date("d/m/Y", strtotime($dataInicio)); ?> </td>
            <td bgcolor="#CCCCCC"><font size="2"><b>Data Fim:</b></font></td>
            <td colspan="3"> <?php $dataFim = $projetopd->data_fim; echo date("d/m/Y", strtotime($dataFim)); ?> </td>
        </tr> 
        <tr>  
        	<td bgcolor="#CCCCCC"><font size="2"><b>Coordenador:</b></font></td>
            <td colspan="3">
				<?php //Acessa a tabela coordenadores para exibir o nome do coordenador ja definido
                $database->setQuery("SELECT * from #__professores  ORDER  BY nomeProfessor");
                $coordenador_listas = $database->loadObjectList();
                foreach($coordenador_listas as $coordenador_final_listas) {
                ?><?php if ($coordenador_final_listas->id == $projetopd->coordenador_id) echo $coordenador_final_listas->nomeProfessor;?></option> <?php
                }
                ?>
			</td> 
        	<td bgcolor="#CCCCCC"><font size="2"><b>Ag. Fomento:</b></font></td>
        	<td colspan="3">
				<?php //Acessa a tabela fomento para exibir o nome do fomento ja definido
                $database->setQuery("SELECT * from #__contproj_agencias  ORDER  BY sigla");
                $fomento_listas = $database->loadObjectList();
                foreach($fomento_listas as $fomento_final_listas){
                ?><?php if ($fomento_final_listas->id == $projetopd->agencia_id) echo $fomento_final_listas->sigla;?></option> <?php
                }
                ?>
			</td>
        </tr>
        <tr>
        	<td bgcolor="#CCCCCC"><font size="2"><b>Banco:</b></font></td>
        	<td colspan="3"> 
				<?php if ($projetopd->banco_id == "1") echo "Banco do Brasil";
				if ($projetopd->banco_id == "2") echo "Bradesco";
				if ($projetopd->banco_id == "3") echo "Itaú";
				if ($projetopd->banco_id == "4") echo "Santander";?>
			</td>
			<td bgcolor="#CCCCCC"><font size="2"><b>Agência:</b></font></td>
        	<td colspan="3"> <?php echo $projetopd->agencia;?> </td>
        </tr>
        <tr> 
        	<td bgcolor="#CCCCCC"><font size="2"><b>Conta:</b></font></td>
        	<td colspan="3"> <?php echo $projetopd->conta;?> </td>
        	<td bgcolor="#CCCCCC"><font size="2"><b>Edital:</b></font></td>
        	<td colspan="3"> <?php echo $projetopd->edital;?> </td>
        </tr>
        <tr> 
        	<td bgcolor="#CCCCCC"><font size="2"><b>Proposta:</b></font></td>
        	<td colspan="3"> <?php echo $projetopd->proposta;?> </td>
        	<td bgcolor="#CCCCCC"><font size="2"><b>Estado:</b></font></td>
        	<td colspan="3"> 
				<?php if ($projetopd->estado == "1") echo "Cadastrado";
				if ($projetopd->estado == "2") echo "Iniciado";
				if ($projetopd->estado == "3") echo "Finalizado";
				if ($projetopd->estado == "4") echo "Cancelado";?>
			</td>
        </tr>
    </table>
    
	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css" />
	<hr>

    <!--Menu acesso a Rubrica de projeto  -->
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    <link rel="stylesheet" href="components/com_controleprojetos/estilo.css" type="text/css" />
    
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
	        	<a href="javascript:gerenciarRubricasProjeto(document.formInformacoesProjeto)"><img width="32" height="32" border="0" src="components/com_controleprojetos/images/rubrica.png" /><span><?php echo JText::_( 'Rubricas do Projeto'); ?></span></a>
			</div>
		</div>    
            
        <div class="icon-wrapper">
            <div class="icon">
                <a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciardespesa"><img width="32" height="32" border="0" src="components/com_controleprojetos/images/despesa.jpg" /><span><?php echo JText::_( 'Gerenciar Despesas' ); ?></span></a>
            </div>
        </div>
        
        <div class="icon-wrapper">
            <div class="icon">
            <a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=registrardata"><img width="32" height="32" border="0" src="components/com_controleprojetos/images/datas.png" /><span><?php echo JText::_( 'Registrar Datas' ); ?></span></a>
            </div>
        </div>
        
        <div class="icon-wrapper">
            <div class="icon">
            <a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerarrelatorio"><img width="32" height="32" border="0" src="components/com_controleprojetos/images/relatorio.png" /><span><?php echo JText::_( 'Gerar Relatórios' ); ?></span></a>
            </div>
        </div>
	</div>
	
	<input name='idProjetopd' type='hidden' value='<?php echo $projetopd->id;?>'>
	<input name='idProjetoSelec' type='hidden' value='0' />        
    <input name='task' type='hidden' value='prorrogacao' />		
	
	</form>

<?php } ?>
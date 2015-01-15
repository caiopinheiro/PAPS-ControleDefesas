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

// LISTAGEM DE PROJETOS
function listarProjetopds($nome = "", $coordenador = "", $financiador = "", $buscaStatus = "Ativo"){
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
	
	if(!$buscaStatus) $buscaStatus = "Ativo";
		
    if($financiador){
		$sql= " SELECT P.id, P.nomeprojeto, P.orcamento, P.saldo, P.data_inicio, P.data_fim,
            P.coordenador_id, PR.nomeProfessor AS coordenador, P.agencia_id, A.sigla AS agenciafomento, 
            P.banco_id, BC.nome AS nomebanco, P.agencia, P.conta, P.edital, P.proposta, P.status
            FROM j17_contproj_projetos AS P 
            INNER JOIN j17_professores AS PR
                ON PR.id = P.coordenador_id 
            INNER JOIN j17_contproj_agencias AS A
                ON A.id = P.agencia_id
            INNER JOIN j17_contproj_bancos AS BC
                ON BC.id = P.banco_id
            WHERE nomeprojeto  LIKE '%$nome%' AND PR.nomeProfessor  LIKE '%$coordenador%' AND P.agencia_id = $financiador AND status = '$buscaStatus' ORDER BY nomeprojeto";    
	} else {
		$sql= " SELECT P.id, P.nomeprojeto, P.orcamento, P.saldo, P.data_inicio, P.data_fim,
            P.coordenador_id, PR.nomeProfessor AS coordenador, P.agencia_id, A.sigla AS agenciafomento, 
            P.banco_id, BC.nome AS nomebanco, P.agencia, P.conta, P.edital, P.proposta, P.status
            FROM j17_contproj_projetos AS P 
            INNER JOIN j17_professores AS PR
                ON PR.id = P.coordenador_id 
            INNER JOIN j17_contproj_agencias AS A
                ON A.id = P.agencia_id
            INNER JOIN j17_contproj_bancos AS BC
                ON BC.id = P.banco_id
            WHERE nomeprojeto  LIKE '%$nome%' AND PR.nomeProfessor  LIKE '%$coordenador%' AND status = '$buscaStatus' ORDER BY nomeprojeto";    
	}
    $database->setQuery( $sql );
    $gerenciarProjetopds = $database->loadObjectList();
	?>

    <!--Funcionalidades dos formularios (Excluir, Editar e Visualizar) --> 
    <script language="JavaScript">
		function excluir(form) {
			var idSelecionado = 0;
			
	        for(i = 0;i < form.idProjetopdSelec.length;i++)
	            if(form.idProjetopdSelec[i].checked) 
					idSelecionado = form.idProjetopdSelec[i].value;
					
			if(idSelecionado > 0) {
				var resposta = window.confirm("Confirme a exclusão do item.");
				if(resposta) {
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
			if(form.idProjetopdSelec[i].checked) 
				idSelecionado = form.idProjetopdSelec[i].value;

			if(idSelecionado > 0) {
				form.task.value = 'editarProjetopd';
				form.idProjetopd.value = idSelecionado;
				form.submit();
			} else {
				alert('Selecione o item a ser editado!')
			}
		}
    
		function visualizar(form) {
			var idSelecionado = 0;
			
			for(i = 0;i < form.idProjetopdSelec.length;i++)
				if(form.idProjetopdSelec[i].checked) 
					idSelecionado = form.idProjetopdSelec[i].value;
					
			if(idSelecionado > 0) {
				form.task.value = 'verProjetopd';
				form.idProjetopd.value = idSelecionado;
				form.submit();
			} else {
				alert('Selecione o item a ser  visualizado.')
			}
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
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css" />
        
    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>">
    
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
                    <span class="icon-32-preview"></span><?php echo JText::_( 'Gerenciar' ); ?></a>
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
            </div>
        </div>
    
        <!-- Filtro de busca -->
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td>Filtro por título do projeto:</td>
                <td><input id="buscaNome" name="buscaNome" size="20" type="text" value="<?php echo $nome;?>"/></td>
                <td>Filtro por coordenador:</td>
                <td><input id="buscaCoordenador" name="buscaCoordenador" size="20" type="text" value="<?php echo $coordenador;?>"/></td>
                <td rowspan="2"><input type="submit" value="Buscar"></td>
            </tr>
            <tr>	  
                <td>Filtro por financiador:</td>
                <td><select id="buscaFinanciador" name="buscaFinanciador" class="inputbox">
                        <option value=""></option>
                        <?php $database->setQuery("SELECT id, nome, sigla from #__contproj_agencias ORDER BY sigla");
                            $listaAgencias = $database->loadObjectList();
                            foreach($listaAgencias as $listaItemAgencias) { ?>
                                <option value="<?php echo $listaItemAgencias->id;?>"
                                               <?php if($financiador == $listaItemAgencias->id) echo 'SELECTED';?>>
                                               <?php echo $listaItemAgencias->sigla;?>
                                </option> 
                        <?php } ?>
                    </select>
                </td>
                <td>Filtro por status:</td>
                <td><select id="buscaStatus" name="buscaStatus" class="inputbox"> 
                        <option value="Cadastrado"<?php if($buscaStatus == "Cadastrado") echo 'SELECTED';?>>Cadastrado</option>
                        <option value="Ativo"<?php if($buscaStatus == "Ativo") echo 'SELECTED';?>>Ativo</option>
                        <option value="Prorrogado"<?php if($buscaStatus == "Prorrogado") echo 'SELECTED';?>>Prorrogado</option>
                        <option value="Encerrado"<?php if($buscaStatus == "Encerrado") echo 'SELECTED';?>>Encerrado</option>					
                    </select>
                </td>
            </tr>
        </table>    
        
		<!-- Formulario -->
		<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
		<thead style="color:#FFC000; text-align:center;">
            <tr bgcolor="#002666">
                <th width="3%"></th>
                <th width="25%">Projeto</th>
                <th width="10%">Financiador</th>
                <th width="12%">Período</th>
                <th width="25%">Coordenador</th>
                <th width="10%">Orçamento</th>
                <th width="10%">Saldo</th>
                <th width="5%">Status</th>
            </tr>
     	</thead>
     	<tbody>
		<?php
			$table_bgcolor_even="#e6e6e6";
			$table_bgcolor_odd="#FFFFFF";
			$imagemStatus = array ('Cadastrado'=>"projetocadastrado.png", 'Ativo'=>"projetoiniciado.png", 'Prorrogado'=>"projetoprorrogado.png", 'Encerrado'=>"projetoencerrado.png");
			$mouseoverStatus = array ('Cadastrado'=>"Projeto Cadastrado", 'Ativo'=>"Projeto Ativo", 'Prorrogado'=>"Projeto Prorrogado", 'Encerrado'=>"Projeto Encerrado");
			$i = 0;
			
			foreach($gerenciarProjetopds as $projeto) {
			$i = $i + 1;
			if ($i % 2) {
				echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
			} else {
				echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
			} ?>
            
			<td width='16'><input type="radio" name="idProjetopdSelec" value="<?php echo $projeto->id;?>" /></td>
			<td><?php echo $projeto->nomeprojeto;?></td>
			<td><?php echo $projeto->agenciafomento;?></td>
            <td><?php echo dataBr($projeto->data_inicio); echo " a "; echo dataBr($projeto->data_fim);?></td>
            <td><?php echo $projeto->coordenador;?></td>
            <td><?php echo moedaBr($projeto->orcamento);?></td>
            <td><?php echo moedaBr($projeto->saldo);?></td>
            <td><img border='0' src='components/com_controleprojetos/images/<?php echo $imagemStatus[$projeto->status];?>' width='16' height='16' title='<?php echo $mouseoverStatus[$projeto->status];?>' /></td>
            
		<?php } ?>
        </tbody>
	</table>
     
	<br />
	Total de Projetos: <b><?php echo sizeof($gerenciarProjetopds);?></b>
    <input name='task' type='hidden' value='gerenciarProjetopds' />
    <input name='idProjetopdSelec' type='hidden' value='0' />
    <input name='idProjetopd' type='hidden' value='' />

</form>

<?php } 


// FORMULÁRIO PARA CADASTRO DE PROJETO
function addProjetopd($projetopd = NULL, $nomeprojeto){
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
			if(IsEmpty(formCadastro.nomeprojeto)){
				alert('O campo Nome deve ser preenchido.')
				formCadastro.nomeprojeto.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.coordenador_id)){
				alert('O campo coordenador deve ser preenchido.')
				formCadastro.coordenador_id.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.agencia_id)){
				alert('O campo Fomento deve ser preenchido.')
				formCadastro.agencia_id.focus();
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
    
	<script src="components/com_controleprojetos/jquery.maskMoney.js" type="text/javascript"></script>
	<script type="text/javascript">
    $(function(){
        $("#orcamento").maskMoney({thousands:'.', decimal:',', symbolStay: true});
    })
	</script>
    <script type="text/javascript" src="components/com_controleprojetos/scripts.js"></script>
    
	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
      
   <form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateformCadastro(this)" enctype="multipart/form-data">
   
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-save">
                    <!-- A opcao abaixo usa o recurso de validacao do formulario-->
                    <!--<a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">-->
                    <!--A opcao abaixo acessa o formulario de forma direta-->
                    <a href="javascript:document.formCadastro.task.value='salvarProjetopd';document.formCadastro.submit()">
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
        <ul><li>Preencha os dados do projeto <font color="#FF0000">(* Campos Obrigatórios)</font>.</li></ul>
        <hr />
        
        <table border="0" cellpadding="1" cellspacing="2" width="100%">
            <tr style="background-color: #7196d8;">
                <td colspan="2"><b><font color="#FFFFFF">Informações</font></b></td>
            </tr>
            <tr>
                 <!--referencia o ID visualizacao--> 
                <input name='id' type='hidden' value="NULL">
            </tr>
            <tr>
                <td width="21%" bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Projeto:</b></font></td>
                <td width="79%"><input maxlength="80" size="60" name="nomeprojeto" class="inputbox" /></td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Orcamento:</b></font></td>
                <td><input maxlength="15" size="15" id="orcamento" name="orcamento" class="inputbox"  onKeyPress="return(MascaraMoeda(this,'.',',',event))" /></td>
            </tr> 
            <tr>
                <td bgcolor="#CCCCCC"><font size="2"></font> <b>Data Inicio:</b></font></td>
                <td><input maxlength="10" size="10" id="data_inicio" name="data_inicio" class="inputbox" /></td>
            </tr>
             <tr>
                <td bgcolor="#CCCCCC"><font size="2"></font> <b>Data Fim:</b></font></td>
                <td><input maxlength="10" size="10" id="data_fim" name="data_fim" class="inputbox" /></td>
             </tr>   
            <tr>
                <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Coordenador:</b></font></td>
                <td colspan="2">
                  <select name="coordenador_id" class="inputbox">
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
                  <select name="agencia_id" class="inputbox">
                    <option value=""></option>
                    <?php //Acessa a tabela fomentos para exibir em list box
                         $database->setQuery("SELECT * from #__contproj_agencias  ORDER  BY sigla");
                         $fomento_listas = $database->loadObjectList();
                         foreach($fomento_listas as $fomento_final_listas) { ?> 
                         	<option value="<?php echo $fomento_final_listas->id;?>"><?php echo $fomento_final_listas->sigla;?> - <?php echo $fomento_final_listas->nome;?></option>
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
                         foreach($banco_listas as $banco_final_listas){ ?> 
                         	<option value="<?php echo $banco_final_listas->id;?>"><?php echo $banco_final_listas->nome;?></option>				
					<?php } ?>
                  </select>
                 </td>
            </tr>
             <tr>
                <td bgcolor="#CCCCCC"><font size="2"></font> <b>Agência:</b></font></td>
                <td><input maxlength="10" size="10" name="agencia" class="inputbox" /></td>
             </tr>
             <tr>
                <td bgcolor="#CCCCCC"><font size="2"></font> <b>Conta:</b></font></td>
                <td><input maxlength="10" size="10" name="conta" class="inputbox" /></td>
             </tr>
             <tr>
                <td bgcolor="#CCCCCC"><font size="2"></font> <b>Edital:</b></font></td>
                <td><input type="file" name="edital" /></td>
             </tr>
             <tr>
                <td bgcolor="#CCCCCC"><font size="2"></font> <b>Proposta:</b></font></td>
                <td><input type="file" name="proposta" /></td>
             </tr>
        </table>
   
        <input name='idProjetopd' type='hidden' value='<?php if($projetopd) echo $projetopd->id;?>' />
        <input name='task' type='hidden' value='salvarProjetopd' />
        <input name='buscaNome' type='hidden' value='<?php echo $nomeprojeto;?>' />
        
    </form>
    
<?php }


// FORMULÁRIO PARA EDIÇÃO DE PROJETOS
function editarProjetopd($projetopd = NULL, $nomeprojeto){
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
    //   if(IsEmpty(formCadastro.id)){
    //      alert('O campo id deve ser preenchido.')
    //      formCadastro.id.focus();
    //      return false;
    //   }
       if(IsEmpty(formCadastro.nomeprojeto)){
          alert('O campo projeto deve ser preenchido.')
          formCadastro.nomeprojeto.focus();
          return false;
       }
       if(IsEmpty(formCadastro.coordenador_id)){
          alert('O campo coordenador deve ser preenchido.')
          formCadastro.coordenador_id.focus();
          return false;
       }
       if(IsEmpty(formCadastro.agencia_id)){
          alert('O campo fomento deve ser preenchido.')
          formCadastro.agencia_id.focus();
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
	$( "#data_inicio" ).datepicker({dateFormat: 'dd/mm/yy'})
        $( "#data_fim" ).datepicker({dateFormat: 'dd/mm/yy'});
	});
    </script>
	<script src="components/com_controleprojetos/jquery.maskMoney.js" type="text/javascript"></script>

	<script type="text/javascript">
		$(function(){
			$("#orcamento").maskMoney({thousands:'.', decimal:',', symbolStay: true});
		})
	</script>    
    
	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
	
	<form method="post" name="formCadastro" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateformCadastro(this)">
    
		<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
			<div class="cpanel2">
				<div class="icon" id="toolbar-save">
                    <!-- A opcao abaixo usa o recurso de validacao do formulario-->
                    <a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
                    <!--A opcao abaixo acessa o formulario de forma direta
                    <a href="javascript:document.formCadastro.task.value='salvarProjetopd';document.formCadastro.submit()">-->
           			<span class="icon-32-save"></span>Salvar</a>
				</div>
                
                <div class="icon" id="toolbar-cancel">
           			<a href="javascript:voltarForm(document.formCadastro)">
           			<span class="icon-32-cancel"></span>Cancelar</a>
		    	</div>
                
                </div>
                <div class="clr"></div>
                </div>
                <div class="pagetitle icon-48-cpanel"><h2>Edicão de Projetos</h2></div>
			</div>
		</div>
        
		<b>Como proceder: </b>
		<ul><li>Edite os dados do projeto <font color="#FF0000">(* Campos Obrigatórios)</font>.</li></ul>
		<hr />
        
		<table border="0" cellpadding="1" cellspacing="2" width="100%">
        	<tr style="background-color: #7196d8;">
                <td colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
            </tr>
            <tr>
                <!--referencia o ID visualizacao--> 
                <input name='id' type='hidden' value="<?php echo $projetopd->id;?>">
            </tr>
            <tr>
                <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Título do Projeto:</b></font></td>
                <td><input maxlength="80" size="80" name="nomeprojeto" class="inputbox" value="<?php echo $projetopd->nomeprojeto;?>"></td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC"><font size="2"><b>Orcamento:</b></font></td>
                <td><input size="15" name="orcamento" class="inputbox" value="<?php echo moedaBr($projetopd->orcamento);?>"  /></td>
            </tr> 
			<tr>
                <td bgcolor="#CCCCCC"><font size="2"></font> <b>Data Inicio:</b></font></td>
                <td><input size="15" id="data_inicio" name="data_inicio" class="inputbox" value="<?php echo dataBr($projetopd->data_inicio);?>" /></td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC"><font size="2"></font> <b>Data Fim:</b></font></td>
                <td><input size="15" id="data_fim" name="data_fim" class="inputbox" value="<?php echo dataBr($projetopd->data_fim);?>"  /></td>
            </tr>
            <tr>
				<td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Coordenador:</b></font></td>
				<td colspan="2">
                  <select name="coordenador_id" class="inputbox">
                    <option value=""></option>
                    <?php //Acessa a tabela coordenadores para exibir em list box
                         $database->setQuery("SELECT * from #__professores  ORDER  BY nomeProfessor");
                         $coordenador_listas = $database->loadObjectList();
                         foreach($coordenador_listas as $coordenador_final_listas){
                                 ?><option value="<?php echo $coordenador_final_listas->id;?>"<?php if ($coordenador_final_listas->id == $projetopd->coordenador_id) echo 'SELECTED';?>><?php echo $coordenador_final_listas->nomeProfessor;?></option><?php
                                 }
                    ?>
                  </select>
                 </td>
            </tr>
			<tr>
                <td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font> <b>Agência de Fomento:</b></font></td>
                  <td colspan="2">
                  <select name="agencia_id" class="inputbox">
                    <option value=""></option>
                    <?php //Acessa a tabela fomentos para exibir em list box
                         $database->setQuery("SELECT * from #__contproj_agencias  ORDER  BY nome");
                         $fomento_listas = $database->loadObjectList();
                         foreach($fomento_listas as $fomento_final_listas) { ?>
                         	<option value="<?php echo $fomento_final_listas->id;?>"<?php if ($fomento_final_listas->id == $projetopd->agencia_id) echo 'SELECTED';?>><?php echo $fomento_final_listas->sigla;?> - <?php echo $fomento_final_listas->nome;?> </option>
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
                         foreach($banco_listas as $banco_final_listas){
                                 ?><option value="<?php echo $banco_final_listas->id;?>"<?php if ($banco_final_listas->id == $projetopd->banco_id) echo 'SELECTED';?>><?php echo $banco_final_listas->nome;?> </option><?php
                                 }
                    ?>
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
                <td><a href='<?php echo $projetopd->edital;?>' target="_blank"><img border="0" src="components/com_controleprojetos/images/icon_pdf.gif"></a></td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC"><font size="2"></font> <b>Proposta:</b></font></td>
                <td><a href='<?php echo $projetopd->proposta;?>' target="_blank"><img border="0" src="components/com_controleprojetos/images/icon_pdf.gif"></a></td>
            </tr>
            <tr>
            	<td bgcolor="#CCCCCC"><font size="2"><font color="#FF0000">*</font><b>Status:</b></font></td>
                <td>                
                    <select name="status" class="inputbox">
                    <option value="Cadastrado" <?php  if ($projetopd->status == "Cadastrado") echo 'SELECTED';?>>Cadastrado</option>
                    <option value="Ativo" <?php  if ($projetopd->status == "Ativo") echo 'SELECTED';?>>Ativo</option>
                    <option value="Prorrogado" <?php if ($projetopd->status == "Prorrogado") echo 'SELECTED';?>>Prorrogado</option>    
                    <option value="Encerrado" <?php if ($projetopd->status == "Encerrado") echo 'SELECTED';?>>Encerrado</option>
                    </select>
                </td>
            </tr>
        </table>

        <input name='idProjetopd' type='hidden' value='<?php if($projetopd) echo $projetopd->id;?>'>
        <input name='task' type='hidden' value='atualizarProjetopd'>
        <input name='buscaNome' type='hidden' value='<?php echo $nomeprojeto;?>'>

</form>

<?php }
 

// VISUALIZAR DADOS DO PROJETO
function relatorioProjetopd($projetopd) {
    $Itemid = JRequest::getInt('Itemid', 0); ?>

	<script type="text/javascript">
		function visualizar(form) {	
			form.task.value = 'prorrogacao';
			form.submit();
		}
    </script>

<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>">
    
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/estilo.css" />
    
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
		<div class="cpanel2">
			<div class="icon" id="toolbar-back">
           		<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarProjetopds">
           	    <span class="icon-32-back"></span>Voltar</a>
			</div>
		</div>
        
        <div class="clr"></div>
        </div>
        <div class="pagetitle icon-48-contact"><h2>Dados do Projeto</h2></div>
        </div>
    </div>
    
    <?php exibirProjeto($projetopd); ?>

	<hr>
 
    <div class="cpanel">
    	<div class="icon-wrapper">
			<div class="icon">
        		<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarRubricadeprojetos&idProjeto=<?php echo $projetopd->id;?>">
            	<img width="32" height="32" border="0" src="components/com_controleprojetos/images/rubrica.png"><span><?php echo JText::_( 'Rubricas do Projeto'); ?></span></a>
			</div>
        </div>
        
	    <?php if($projetopd->status=="Ativo" || $projetopd->status=="Prorrogado") { ?>
        <div class="icon-wrapper">
            <div class="icon">
            	<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=receitas&idProjeto=<?php echo $projetopd->id;?>">
                <img width="32" height="32" border="0" src="components/com_controleprojetos/images/receita.png"><span><?php echo JText::_( 'Receitas do Projeto' ); ?></span></a>
            </div>
        </div>
        
        <div class="icon-wrapper">
            <div class="icon">
            	<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarDespesasprojetos&idProjeto=<?php echo $projetopd->id;?>"> 				
                <img width="32" height="32" border="0" src="components/com_controleprojetos/images/despesa.png"><span><?php echo JText::_( 'Despesas do Projeto' ); ?></span></a>
            </div>
        </div>
        
        <div class="icon-wrapper">
            <div class="icon">
            	<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarTranferenciadeRubricas&idProjeto=<?php echo $projetopd->id;?>"> 				
                <img width="32" height="32" border="0" src="components/com_controleprojetos/images/transferencia.png"><span><?php echo JText::_( 'Transferência de Saldo de Rubrica' ); ?></span></a>
            </div>
        </div>
        
        <div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=prorrogacao&idProjeto=<?php echo $projetopd->id;?>"> ´
                <img width="32" height="32" border="0" src="components/com_controleprojetos/images/prorrogacao.gif"><span><?php echo JText::_( 'Prorrogar Projeto' ); ?></span></a>
			</div>
		</div>
        
        <div class="icon-wrapper">
            <div class="icon">
            	<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarRegistrodedatas&idProjeto=<?php echo $projetopd->id;?>">
                <img width="32" height="32" border="0" src="components/com_controleprojetos/images/datas.png"><span><?php echo JText::_( 'Registrar Datas' ); ?></span></a>
            </div>
        </div>
        
        <div class="icon-wrapper">
            <div class="icon">
				<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarRelatorioSimplificadoDespesa&idProjeto=<?php echo $projetopd->id;?>">
                <img width="32" height="32" border="0" src="components/com_controleprojetos/images/relatorio.png"><span><?php echo JText::_( 'Relatório Simplificado de Despesa'); ?></span></a>
            </div>
        </div>
        
        <div class="icon-wrapper">
            <div class="icon">
				<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarRelatorioDetalhadoDespesa&idProjeto=<?php echo $projetopd->id;?>">
                <img width="32" height="32" border="0" src="components/com_controleprojetos/images/detalhado.png"><span><?php echo JText::_( 'Relatório Detalhado de Despesa'); ?></span></a>
            </div>
        </div>
        
    <?php } ?>
    
    </div>
    
	<input name='idProjetopd' type='hidden' value='<?php if($projetopd) echo $projetopd->id;?>'> 
	<input name='task' type='hidden' value='atualizarProjetopd'> 
	
</form>

<?php }


// EXIBIR PROJETO
function exibirProjeto($projetopd) {
	$database = JFactory::getDBO(); ?>
    
	<table border="0" cellpadding="3" cellspacing="2" width="100%">
		<tr style="background-color: #7196d8;">
    	   <td colspan="4"><font color="#FFFFFF"><b>Informações</b></font></td>
       	</tr>
       	<tr>
			<td class="tbItem">Projeto:</td>
        	<td><?php echo $projetopd->nomeprojeto;?></td>
   	    	<td class="tbItem">Proposta:</td>
        	<td><a href="<?php echo $projetopd->proposta;?>" target="_blank"><img border='0' src='components/com_controleprojetos/images/icon_pdf.gif' width='16' height='16'></a></td>
		</tr>  
      	<tr> 
        	<td class="tbItem">Coordenador:</td>
         	<td>
            <?php 
                 $database->setQuery("SELECT nomeProfessor from #__professores WHERE id = $projetopd->coordenador_id");
	             $coordenador = $database->loadObjectList();
	             echo $coordenador[0]->nomeProfessor;
            ?>
        	</td> 
	    	<td class="tbItem">Status:</td>
        	<td> <?php echo $projetopd->status;?></td>
      	</tr>
       	<tr> 
            <td class="tbItem">Data de Início:</td>
            <td> <?php echo dataBr($projetopd->data_inicio); ?></td>
            <td class="tbItem">Data de Término:</td>
            <td>
				<?php 
				if ($projetopd->data_fim_alterada == '0000-00-00') {
					echo dataBr($projetopd->data_fim);	
				} else {
					echo dataBr($projetopd->data_fim_alterada); 
				} ?>
            </td>
      	</tr>
      	<tr> 
            <td class="tbItem">Orçamento:</td>
            <td>R$ <?php echo moedaBr($projetopd->orcamento); ?></td>
            <td class="tbItem">Saldo:</td>
            <td>R$ <?php echo moedaBr($projetopd->saldo); ?></td>
      	</tr> 
      	<tr>  
			<td class="tbItem">Financiador/Edital:</td>
        	<td><a href="<?php echo $projetopd->edital;?>" target="_blank"><img src="components/com_controleprojetos/images/icon_pdf.gif"></a></td>
	  		<td class="tbItem">Conta:</td>
        	<td> <?php echo $projetopd->conta;?> </td>
      	</tr>
      	<tr>
        	<td class="tbItem">Banco:</td>
        	<td>
            <?php 
                 $database->setQuery("SELECT * from #__contproj_bancos WHERE id = $projetopd->banco_id");
	             $banco = $database->loadObjectList();
	             echo $banco[0]->nome;
            ?>
        	</td>
        	<td class="tbItem">Agência:</td>
        	<td> <?php echo $projetopd->agencia;?> </td>
      	</tr>
   	</table>
    
<?php }


// CADASTRAR PROJETO
function salvarProjetopd($idProjetopd = ""){
	$database = JFactory::getDBO();
	
	$id = JRequest::getVar('id');
	$nomeprojeto = JRequest::getVar('nomeprojeto');
	$orcamento = moedaSql(JRequest::getVar('orcamento'));
	$saldo = moedaSql(JRequest::getVar('saldo'));
	$data_inicio = dataSql(JRequest::getVar('data_inicio'));
	$data_fim = dataSql(JRequest::getVar('data_fim'));
	$coordenador_id = JRequest::getVar('coordenador_id');
	$agencia_id = JRequest::getVar('agencia_id');
	$banco_id = JRequest::getVar('banco_id');
	$agencia = JRequest::getVar('agencia');
	$conta = JRequest::getVar('conta');
	$edital = "";
	$proposta = "";
  
	if($_FILES['edital']['tmp_name']){
		$edital = "components/com_controleprojetos/docprojetos/edital/PPGI-Edital-".$id.".pdf";
		move_uploaded_file($_FILES["edital"]["tmp_name"],$edital);	
	}
	
	if($_FILES['proposta']['tmp_name']){
		$proposta = "components/com_controleprojetos/docprojetos/proposta/PPGI-Proposta.pdf";
		move_uploaded_file($_FILES["proposta"]["tmp_name"],$proposta);
	}  

   	if($data_inicio <= date("Y-m-d"))
		$status = "Ativo";
	else
		$status = "Cadastrado"; //o orcamento esta setado no saldo na criacao de um novo projeto
				
	$sql = "INSERT INTO #__contproj_projetos (nomeprojeto, orcamento, saldo, data_inicio, data_fim, coordenador_id, agencia_id, banco_id, agencia, conta, edital, proposta, status) VALUES ('$nomeprojeto', $orcamento, 0, '$data_inicio', '$data_fim', $coordenador_id, $agencia_id, $banco_id, '$agencia', '$conta', '$edital', '$proposta', '$status')";
	$database->setQuery($sql);
			
    if($database->Query()){
    	JFactory::getApplication()->enqueueMessage(JText::_('Cadastro realizado com sucesso!'));
        return 1;
	} else {
		JError::raiseWarning( 100, 'ERRO: Não foi possível realizar o cadastro!' );
		return 0;
	}              
}


// ATUALIZAR PROJETO
function atualizarProjetopd ($projetopd, $nome) {
	$database = JFactory::getDBO();
	
	$nomeprojeto = JRequest::getVar('nomeprojeto');
	$orcamento = moedaSql(JRequest::getVar('orcamento'));
	$saldo = moedaSql(JRequest::getVar('saldo'));
	$data_inicio = dataSql(JRequest::getVar('data_inicio'));
	$data_fim = dataSql(JRequest::getVar('data_fim'));
	$coordenador_id = JRequest::getVar('coordenador_id');
	$agencia_id = JRequest::getVar('agencia_id');
	$banco_id = JRequest::getVar('banco_id');
	$agencia = JRequest::getVar('agencia');
	$conta = JRequest::getVar('conta');
	$status = JRequest::getVar('status');
	
	$sql = "UPDATE #__contproj_projetos SET nomeprojeto = '$nomeprojeto', orcamento = '$orcamento', data_inicio = '$data_inicio', data_fim = '$data_fim', coordenador_id = '$coordenador_id', agencia_id = '$agencia_id', banco_id = '$banco_id', agencia = '$agencia', conta = '$conta', status = '$status' WHERE id = '$projetopd->id' ";
	$database->setQuery($sql);
	
	$funcionou = $database->Query();

	if($funcionou) {
		JFactory::getApplication()->enqueueMessage(JText::_('Atualização do projeto realizada com sucesso!'));
		listarProjetopds($nome);
	} else {
		JError::raiseWarning( 100, 'ERRO: Alteração não realizada.' );
		atualizarProjetopd($projetopd);
		return 0;
	}
}


// EXCLUIR PROJETO
function excluirProjetopd($idProjetopd){
	$database = JFactory::getDBO();
	$sql = "DELETE FROM #__contproj_projetos WHERE id = $idProjetopd";
	$database->setQuery($sql);
	$database->Query();
	
	$sqlRubrica = "SELECT id FROM #__contproj_rubricasdeprojetos WHERE projeto_id = $idProjetopd";
	$database->setQuery($sqlRubrica);
	$resultado = $database->loadObjectList();	

	foreach ($resultado as $res) {
		$rubrica_id = $res->id;
	
		$sqlDelReceita = "DELETE FROM #__contproj_receitas WHERE rubricasdeprojetos_id = $rubrica_id";
		$database->setQuery($sqlDelReceita);
		$database->Query();
		
		$sqlDelDespesa = "DELETE FROM #__contproj_despesas WHERE rubricasdeprojetos_id = $rubrica_id";
		$database->setQuery($sqlDelDespesa);
		$database->Query();
		
		$sqlDelTrans = "DELETE FROM #__contproj_transferenciassaldorubricas WHERE projeto_id = $idProjetopd";
		$database->setQuery($sqlDelTrans);
		$database->Query();
	}
	
	$sqlDelDespesa = "DELETE FROM #__contproj_rubricasdeprojetos WHERE projeto_id = $idProjetopd";
	$database->setQuery($sqlDelDespesa);
	$database->Query();
}


// CALCULAR SALDO DO PROJETO
function calcularSaldo($projeto_id) {
	$database = JFactory::getDBO();
	
	$sqlReceita = "SELECT SUM(receitas.valor_receita) as totalReceitas
			FROM `j17_contproj_receitas` AS receitas, `j17_contproj_rubricasdeprojetos` AS rubricas, `j17_contproj_projetos` AS projetos
			WHERE projetos.id = rubricas.projeto_id
			AND rubricas.id = receitas.rubricasdeprojetos_id
			AND projetos.id = '".$projeto_id."'";
	$database->setQuery($sqlReceita);
	$resultado = $database->loadObjectList();

	foreach ($resultado as $res) 
		$valorReceita = $res->totalReceitas;
		
	$sqlDespesa = "SELECT SUM(despesas.valor_despesa) as totalDespesas
					FROM `j17_contproj_despesas` AS despesas, `j17_contproj_rubricasdeprojetos` AS rubricas, `j17_contproj_projetos` AS projetos
					WHERE projetos.id = rubricas.projeto_id
					AND rubricas.id = despesas.rubricasdeprojetos_id
					AND projetos.id = '".$projeto_id."'";
	$database->setQuery($sqlDespesa);
	$resultado2 = $database->loadObjectList();	

	foreach ($resultado2 as $res2) 
		$valorDespesa = $res2->totalDespesas;
	
	$saldo = $valorReceita - $valorDespesa;
	
	return $saldo;	
}


// FUNÇÕES : CONVERSÃO DE DATAS E VALORES

// Identifica o id do registro selecionado pelo radio button
function identificarProjetopdID($idProjetopd){
    $database = JFactory::getDBO();
    $sql = "SELECT * FROM #__contproj_projetos WHERE id = $idProjetopd LIMIT 1";
    $database->setQuery( $sql );
    $projetopd = $database->loadObjectList();
    return ($projetopd[0]);
}

// Formata data aaaa-mm-dd para dd/mm/aaaa
function dataBr($dataSql) {
	if (!empty($dataSql)) {
		$p_dt = explode('-',$dataSql);
    	$data_brProjetopd = $p_dt[2].'/'.$p_dt[1].'/'.$p_dt[0];
		return $data_brProjetopd;
	}
}
 
// Formata data dd/mm/aaaa para aaaa-mm-dd
function dataSql($dataBr) {
	if (!empty($dataBr)) {
		$p_dt = explode('/',$dataBr);
		$data_sqlProjetopd = $p_dt[2].'-'.$p_dt[1].'-'.$p_dt[0];
		
		return  $data_sqlProjetopd;
	}
}

// Formata o valor escrito no padrao xx.xxx,xx
function moedaBr($ValorBr) {
    $ValorBr = number_format($ValorBr, 2, ',','.');
	
    return $ValorBr;
}

// Formata o valor escrito no padrao xx,xxx.xx    
function moedaSql($ValorSql) {
    $origem = array('.', ','); 
    $destino = array('', '.');
    $ValorSql = str_replace($origem, $destino, $ValorSql); //remove os pontos e substitui a virgula pelo ponto
	
    return $ValorSql; //retorna o valor formatado para gravar no banco
}
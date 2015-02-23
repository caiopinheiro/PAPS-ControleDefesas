<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
<link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/estilo.css">

<?php // LISTAGEM DE BOLSAS
function listarBolsas($agencia = NULL, $categoria  = NULL, $status = NULL) {
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); 
	
	$sqlEstendido = $sqlEstendido2 = $sqlEstendido3 = "";

    if($agencia)
		$sqlEstendido = "AND agencia LIKE '$agencia'";

    if($categoria) 
		$sqlEstendido2 = "AND categoria = '$categoria'";

    if($status) 
		$sqlEstendido3 = "AND status = '$status'";
	
	$sql = "SELECT * FROM #__bolsas WHERE 1 ".$sqlEstendido." ".$sqlEstendido2." ".$sqlEstendido3." ORDER BY agencia ";
    $database->setQuery($sql);
    $gerenciarBolsas = $database->loadObjectList(); ?>
    
    <!-- SCRIPTS -->
    <script type="text/javascript">
		function excluir(id){
			if (confirm("Deseja realmente exluir o registro?")) {
				form.task.value = 'excluirBolsa';
				form.idBolsa.value = id;
				form.submit();
			} else
				alert("Exclusão cancelada");	
		}
		
		function visualizarPDF(edital) {
			window.open("components/com_bolsas/editais/"+edital+".pdf","_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
		}
	</script>
    
	<form method="post" name="form" action="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>">

        <!-- BARRA DE FERRAMENTAS -->
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-new">
                    <a href="javascript:document.form.task.value='addBolsa';document.form.submit()">
                    <span class="icon-32-new"></span><?php echo JText::_( 'Novo' ); ?></a>
                </div>
                
                <div class="icon" id="toolbar-back">
                    <a href="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
    
            <div class="clr"></div>
            </div>
            <div class="pagetitle icon-48-search"><h2>Bolsas de Pesquisa</h2></div>
            </div>
        </div>
        
        <!-- FILTRO DA BUSCA -->
        <fieldset>
            <legend>Filtros para consulta</legend>

			<select name="buscaAgencia">
	            <option value="">Selecione um Financiador</option>
                <?php 
					$agencias = listaAgencias(); 					
					foreach ($agencias as $itemAgencia) { ?>                    
	                	<option value="<?php echo $itemAgencia->sigla; ?>" <?php if($agencia == $itemAgencia->sigla) echo "SELECTED";?>><?php echo $itemAgencia->sigla?></option>                    
                <?php } ?>
            </select>
            
			<select name="buscaCategoria" >
	            <option value="">Selecione uma Categoria</option>
                <option value="Mestrado" <?php if($categoria == "Mestrado") echo "SELECTED";?>>Mestrado</option>
                <option value="Doutorado" <?php if($categoria == "Doutorado") echo "SELECTED";?>>Doutorado</option>
            </select>
            
            <select name="buscaStatus">
	            <option value="">Selecione um status</option>
	            <option value="Ativa" <?php if($status == "Ativa") echo "SELECTED";?>>Ativa</option>
	            <option value="Finalizada" <?php if($status == "Finalizada") echo "SELECTED";?>>Finalizada</option>
            </select>
            
            <button type="submit" name="buscar" class="btn btn-primary" style="float:right;" title="Filtra busca">
            	<i class="icone-search icone-white"></i> Consultar
            </button>
        </fieldset>
        
		<?php 
		if (!$gerenciarBolsas) 
			echo '<div class="alert alert-info">
			  		<b>Não há registros para esta consulta</b>
			  	  </div>'; 
			else { ?>
        
        <table class="table table-striped">
            <thead class="head-one">
            <tr>
                <td></td>
                <td>Financiador</td>
                <td>Edital</td>
                <td>Início</td>
                <td>Término</td>                
                <td>Valor Mensal</td>
                <td>Nível</td>
                <td>Bolsas</td>
                <!--<td>Disponíveis</td>-->
                <td>Status</td>
                <td>Opções</td>
            </tr>
            </thead>
            
            <tbody>
            
            <?php foreach ($gerenciarBolsas as $bolsa) { 
			
				// VALOR TOTAL DA BOLSA
				$valorUnitario = $bolsa->valorUnitario;
				$qtdeBolsas = $bolsa->quantidade;
				$valorTotal = $qtdeBolsas * $valorUnitario; ?>
            
            <tr>
                <td></td>
                <td><?php echo $bolsa->agencia; ?></td>
                <!--<td><a href="javascript:visualizarPDF('<?php echo $bolsa->edital;?>')"><img border='0' src='components/com_portalaluno/images/icon_pdf.gif' title='Visualizar Edital'></a></td>-->
                <td><?php echo $bolsa->edital; ?></td>
                <td><?php echo dataBr($bolsa->dataInicio); ?></td>
                <td><?php if ($bolsa->dataTermino == '0000-00-00') echo '';
                          else echo dataBr($bolsa->dataTermino); ?></td>
                <td><?php echo 'R$ '.moedaBr($bolsa->valorUnitario); ?></td>
                <td><?php echo ucfirst($bolsa->categoria); ?></td>
                <td><?php echo $bolsa->quantidade; ?></td>
                <!--<td><?php echo calcularBolsasDisp($bolsa->id); ?></td>-->
                <td><?php echo $bolsa->status; ?></td>
                <td><a href="javascript:document.form.task.value='editarBolsa';document.form.idBolsa.value='<?php echo $bolsa->id; ?>';document.form.submit()"><span class="label label-info" title="Editar"> <i class="icone icone-pencil icone-white"></i> </span></a>
                    <a href="javascript:document.form.task.value='confirmarExclusao';document.form.idBolsa.value='<?php echo $bolsa->id; ?>';document.form.submit()"><span class="label label-important" title="Excluir"> <i class="icone icone-minus icone-white"></i> </span></a>
                </td>
            </tr>            
                
            <?php } ?>            
                
            </tbody>
        </table>
        
        <?php } ?>
        
        <br />
        Total de Bolsas: <b><?php echo sizeof($gerenciarBolsas);?></b>
        
        <input name='task' type='hidden' value='gerenciarBolsas' />
        <input name='idBolsa' type='hidden' value='' />    
    </form>
        
<?php } ?>


<?php // CADASTRAR BOLSA : TELA
function telaCadastrarBolsa() { 
    $database	= JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
    <!-- SCRIPTS -->    
    <script type="text/javascript">
		function ValidateForm(form) {        
           var erro = false;
		   
		   form.submeter.click();
		}
	</script>
    
    <!-- MÁSCARAS PARA NÚMEROS -->
	<script type="text/javascript" src="components/com_bolsas/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
        $(function($){
            $("#dataInicio").mask("99/99/9999");
            $("#dataTermino").mask("99/99/9999");
            $("#quantidadeBolsas").mask("99");		
        });
    </script>
        
    <!-- CALENDÁRIO -->
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/default.css"/>
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/jquery.click-calendario-1.0.css"/>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/jquery.click-calendario-1.0-min.js"></script>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/script.js"></script>
    
	<form method="post" name="formCadBolsa" action="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>" class="form-horizontal">
                  
        <!-- BARRA DE FERRAMENTAS -->
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-save">
                    <a href="javascript: ValidateForm(document.formCadBolsa)" class="toolbar">
                    <span class="icon-32-save"></span><?php echo JText::_( 'Salvar' ); ?></a>
                </div>
                
                <div class="icon" id="toolbar-cancel">
                    <a href="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>&task=gerenciarBolsas">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
            
            <div class="clr"></div>            
            </div>
        
            <div class="pagetitle icon-48-category-add">
                <h2>Cadastro de Bolsa</h2>
            </div>
        </div></div>
    
        <span class="label label-info">Informações</span>
        <ul>
            <li>Os campos com <span class="obrigatory"> * </span> são de preenchimento obrigatório</li>
            <li></li>
        </ul>
        <hr />
        
        <table class="table-form">            
            <tbody>
            	<tr>
                	<td>Financiador <span class="obrigatory">*</span></td>
                    <td><select name="agencia" required="required">
                    	<option value="">Selecione...</option>
                        <?php 
							$agencias = listaAgencias(); 					
							foreach ($agencias as $itemAgencia) { ?>                    
	    		           	<option value="<?php echo $itemAgencia->sigla; ?>"><?php echo $itemAgencia->sigla?></option>                        <?php } ?>
                    </select></td>
                </tr>
            	<tr>
                	<td>Edital <span class="obrigatory">*</span></td>
                    <td><input type="text" name="edital" required="required" /></td>
                </tr>
		       	<tr>
                	<td>Período <span class="obrigatory">*</span></td>
                    <td><input type="text" name="dataInicio" id="dataInicio" required="required" class="imgcalendar" /> a 
                    	<input type="text" name="dataTermino" id="dataTermino" class="imgcalendar" />
                    	<span class="help-block"><span class="label">Lembrete</span> Uma bolsa pode não ter Data de Término</span>
                    </td>
                </tr>
                <tr>
                	<td>Valor Mensal <span class="obrigatory">*</span></td>
                    <td><input type="text" name="valor" onKeyPress="return(MascaraMoeda(this,'.',',',event))" required="required" /></td>
                </tr>
                <tr>
                	<td>Quantidade de bolsas <span class="obrigatory">*</span></td>
                    <td><input type="text" name="quantidade" id="quantidadeBolsas" required="required" /></td>
                </tr>
                <tr>
                	<td>Categoria <span class="obrigatory">*</span></td>
                    <td><select name="categoria" required="required">
                    	<option value="">Selecione...</option>
                        <option value="Mestrado">Mestrado</option>
                        <option value="Doutorado">Doutorado</option>
                    </select>                    
                    </td>
                </tr>
            </tbody>
        </table>        
        
		<input type="submit" value="Submeter" name="submeter" style="display:none">        
		<input name='task' type='hidden' value='cadBolsa'>
    </form>

<?php } ?>


<?php // CADASTRAR BOLSA : SQL
function salvarBolsa() {
	$database =& JFactory::getDBO();
	
	$edital = JRequest::getVar('edital');
	$agencia = JRequest::getVar('agencia');
	$dataInicio = JRequest::getVar('dataInicio');
	$dataTermino = JRequest::getVar('dataTermino');
	$valor = JRequest::getVar('valor');
	$quantidade = JRequest::getVar('quantidade');
	$categoria = JRequest::getVar('categoria');
	$status = 'Ativa';
	$valor = moedaSql($valor);
	
	$dataInicio = dataSql($dataInicio);
	$dataTermino = dataSql($dataTermino);

	$sql = "INSERT INTO #__bolsas (id, edital, agencia, dataInicio, dataTermino, valorUnitario, quantidade, categoria, status) 	
			VALUES ('','$edital', '$agencia', '$dataInicio', '$dataTermino', '$valor', '$quantidade', '$categoria', '$status')";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	if($funcionou){
		echo '<div class="alert alert-success">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>CADASTRO :</b> Registro cadastrado com sucesso!
			  </div>';
	} else {
		echo '<div class="alert alert-error">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>CADASTRO :</b> Não foi possível cadastrar o registro, tente novamente!
			  </div>';
	}
	
	listarBolsas();
}
?>


<?php // EDITAR BOLSA : TELA
function telaEditarBolsa($bolsa) { 
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);  ?>
    
    <!-- SCRIPTS -->    
    <script type="text/javascript">
		function ValidateForm(form) {        
           var erro = false;
		   
		   form.submeter.click();
		}
	</script>
    
	<!-- MÁSCARAS PARA NÚMEROS -->
	<script type="text/javascript" src="components/com_bolsas/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
        $(function($){
            $("#dataInicio").mask("99/99/9999");
            $("#dataTermino").mask("99/99/9999");	
        });
    </script>
    
	<!-- CALENDÁRIO -->
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/default.css"/>
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/jquery.click-calendario-1.0.css"/>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/jquery.click-calendario-1.0-min.js"></script>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/script.js"></script>
    
	<form method="post" name="formEditBolsa" action="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>" enctype="multipart/form-data">
                  
		<!-- BARRA DE FERRAMENTAS -->                  
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-save">
                    <a href="javascript: ValidateForm(document.formEditBolsa)" class="toolbar">
                    <span class="icon-32-save"></span><?php echo JText::_( 'Atualizar' ); ?></a>
                </div>
                
                <div class="icon" id="toolbar-cancel">
                    <a href="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>&task=gerenciarBolsas">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
            
            <div class="clr"></div>            
            </div>
        
            <div class="pagetitle icon-48-category-add">
                <h2>Edição de Bolsa</h2>
            </div>
        </div></div>
    
        <span class="label label-info">Informações</span>
        <ul>
            <li>Os campos com <span class="obrigatory"> * </span> são de preenchimento obrigatório</li>
            <li>Somente os campos <b>[campos]</b> podem ser alterados</li>
        </ul>
        <hr />
        
        <table class="table-form">            
            <tbody>
            	<tr>
                	<td>Financiador <span class="obrigatory">*</span></td>
                    <td><select name="agencia" required="required">
                    	<option value="<?php echo $bolsa->agencia; ?>"><?php echo $bolsa->agencia; ?></option>
                        <?php
                        	$database->setQuery("SELECT sigla FROM #__contproj_agencias ORDER BY sigla");
                        	$dados_agencia = $database->loadObjectList();
                        	foreach($dados_agencia as $agencia) { ?> 
                         		<option value="<?php echo $agencia->sigla; ?>"><?php echo $agencia->sigla; ?></option>				
						<?php } ?>
                    </select></td>
                </tr>
            	<tr>
                	<td>Edital <span class="obrigatory">*</span></td>
                    <td><input type="text" name="edital" required="required" value="<?php echo $bolsa->edital;?>"/></td>
                </tr>
		       	<tr>
                	<td>Período <span class="obrigatory">*</span></td>
                    <td><input type="text" name="dataInicio" id="dataInicio" required="required" value="<?php echo dataBr($bolsa->dataInicio); ?>" /> a <input type="text" name="dataTermino" id="dataTermino" value="<?php echo dataBr($bolsa->dataTermino); ?>" />
                    	<span class="help-block"><span class="label">Lembrete</span> Uma bolsa pode não ter Data de Término</span>
                    </td>
                </tr>
                <tr>
                	<td>Valor Mensal <span class="obrigatory">*</span></td>
                    <td><input type="text" name="valor" onKeyPress="return(MascaraMoeda(this,'.',',',event))" required="required" value="<?php echo moedaBr($bolsa->valorUnitario); ?>" /></td>
                </tr>
                <tr>
                	<td>Quantidade de bolsas <span class="obrigatory">*</span></td>
                    <td><input type="text" name="quantidade" required="required" value="<?php echo $bolsa->quantidade; ?>" /></td>
                </tr>
                <tr>
                	<td>Categoria <span class="obrigatory">*</span></td>
                    <td><select name="categoria" required="required">
                    	<option value="<?php echo $bolsa->categoria; ?>"><?php echo $bolsa->categoria; ?></option>
                        <option value="Mestrado">Mestrado</option>
                        <option value="Doutorado">Doutorado</option>
                    </select>
                    </td>
                </tr>
                <tr>
                	<td>Status <span class="obrigatory">*</span></td>
                    <td><select name="status" required="required">
                    	<option value="<?php echo $bolsa->status; ?>"><?php echo $bolsa->status; ?></option>
                        <option value="Ativa">Ativa</option>
                        <option value="Suspensa">Suspensa</option>
                        <option value="Finalizada">Finalizada</option>                        
                    </select></td>
                </tr>
            </tbody>
        </table>        
        
		<input type="submit" value="Submeter" name="submeter" style="display:none" />
		<input name="idBolsa" type="hidden" value="<?php echo $bolsa->id; ?>" />
		<input name="task" type="hidden" value="atualizarBolsa" />
    </form> 

<?php } ?>


<?php // EDITAR BOLSA : SQL
function atualizarBolsa ($idBolsa) {
	$database = JFactory::getDBO();
	
	$agencia = JRequest::getVar('agencia');
	$edital = JRequest::getVar('edital');
	$dataInicio = JRequest::getVar('dataInicio');
	$dataTermino = JRequest::getVar('dataTermino');
	$valor = JRequest::getVar('valor');
	$quantidade = JRequest::getVar('quantidade');
	$categoria = JRequest::getVar('categoria');
	$status = JRequest::getVar('status');
	$valor = moedaSql($valor);
	
	$dataInicio = dataSql($dataInicio);
	$dataTermino = dataSql($dataTermino);
	
	$sql = "UPDATE #__bolsas SET edital = '$edital', agencia = '$agencia', dataInicio = '$dataInicio', dataTermino = '$dataTermino', valorUnitario = '$valor', quantidade = '$quantidade', categoria = '$categoria', status = '$status' WHERE id = '$idBolsa' ";
	$database->setQuery($sql);	
	var_dump($sql);
	$funcionou = $database->Query();

	if($funcionou) {
		echo '<div class="alert alert-success">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>EDIÇÃO :</b> Registro atualizado com sucesso!
			  </div>';
	} else {
		echo '<div class="alert alert-error">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>EDIÇÃO :</b> Não foi possível atualizar o registro, tente novamente!
			  </div>';
	}
	
	listarBolsas();
} ?>


<?php // CONFIRMAR EXCLUSÃO DA BOLSA
function confirmarExclusao ($idBolsa) { 
    $Itemid = JRequest::getInt('Itemid', 0); ?>

	<form method="post" name="form" action="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>">
    
        <div class="alert alert-error">
            <h4>Advertência!</h4>
            Você realmente deseja excluir este registro?
    
            <button class="btn btn-small btn-danger" type="submit">Sim, quero excluir</button>        
            <a href="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>&task=gerenciarBolsas"><button class="btn btn-small" type="button">Cancelar Exclusão</button></a>
        </div>
    
        <input name='task' type='hidden' value='excluirBolsa' />    
	    <input name='idBolsa' type='hidden' value='<?php echo $idBolsa; ?>' />
    
    </form>

<?php listarBolsas(); } ?>


<?php // EXCLUIR BOLSA
function excluirBolsa($idBolsa) {
	$database = JFactory::getDBO();
	
	$sql = "DELETE FROM #__bolsas WHERE id = '$idBolsa'";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	if($funcionou) {
		echo '<div class="alert alert-success">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>EXCLUSÃO :</b> Registro excluído com sucesso!
			  </div>';
	} else {
		echo '<div class="alert alert-error">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>EXCLUSÃO :</b> Não foi possível excluir o registro, tente novamente!
			  </div>';
	}
	
	listarBolsas();
}
?>
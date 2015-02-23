<?php // LISTAGEM DE PROFESSORES
function listarFuncionarios($nome = "") {  
	$database =& JFactory::getDBO();
	$Itemid = JRequest::getInt('Itemid', 0);
  	$sql = "SELECT * FROM #__funcionarios WHERE nome LIKE '%$nome%' ORDER BY nome";  
  	$database->setQuery($sql);
    $funcionarios = $database->loadObjectList(); ?>
    
	<script type="text/javascript" src="components/com_portalsecretaria/assets/js/jquery.js"></script>
    
    <!-- SCRIPTS -->
	<script language="JavaScript">	  
		function editar(form, id) {
			form.task.value = 'editarFuncionario';
			form.idFunc.value = id;
			form.submit();
		}		
  
		function visualizar(form, id) {
			form.task.value = 'verFuncionario';
			form.idFunc.value = id;
			form.submit();
		}
		
		function excluir(form, id) {
			var resposta = window.confirm("Confirmar exclusao do Funcionário?");
			
			if(resposta) {
				form.task.value = 'excluirFuncionario';
				form.idFunc.value = id;
				form.submit();
		    }
		}
	</script>
    
    <!-- FADE -->
    <script type="text/javascript">
		$(function(){
			$(".close").click(function(){           
				$("#alert").fadeOut();
			});
		});
    </script>
    
    <!-- TABLEMASTER -->
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>    
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();	
		});
	</script>
    
   	<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css" />
   	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />

    <form method="post" name="form" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" >
    	
        <!-- BARRA DE OPÇÕES -->
    	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        	<div class="cpanel2">
				<div class="icon" id="toolbar-new">
           			<a href="javascript:document.form.task.value='addFuncionario';document.form.submit()">
           				<span class="icon-32-user-add"></span><?php echo JText::_( 'Novo' ); ?>
                    </a>
				</div>
                
				<div class="icon" id="toolbar-back">
           			<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
           				<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?>
                    </a>
				</div>
			</div>
            
            <div class="clr"></div>
            </div>
            <div class="pagetitle icon-48-groups"><h2>Funcionários</h2></div>
        </div></div>

		<!-- FILTRO -->
		<fieldset>
            <legend>Filtro para consulta</legend>
            
            <input type="text" name="buscaNome" id="buscaNome" placeholder="Busque por nome..." style="width:200px;"/>
            
		    <button type="submit" name="buscar" class="btn btn-primary" title="Filtra busca">
            	<i class="icone-search icone-white"></i> Consultar
            </button>            
        </fieldset>        
		
		<table class="table table-striped" id="tablesorter-imasters">
            <thead class="head-one">
                <tr>
					<th>Nome</th>
					<th>CPF</th>
                    <th>Fones</th>
                    <th>E-mail</th>
                    <th>Cargo</th>
                    <th colspan="3">Opções</th>
                </tr>
            </thead>
            
            <tbody>
                <?php    
					foreach($funcionarios as $funcionario) { 
						$idFunc = $funcionario->id; ?>
					
					<tr>
						<td><?php echo $funcionario->nome; ?></td>
						<td><?php echo $funcionario->cpf; ?></td>
						<td>
							<?php if($funcionario->tel_residencial) echo "<img src='components/com_portalsecretaria/images/telefone.png' title='".$funcionario->tel_residencial."'>";?>
							<?php if($funcionario->tel_celular) echo "<img src='components/com_portalsecretaria/images/celular.png' title='".$funcionario->tel_celular."'>";?>
						</td>
						<td><img border='0' src='components/com_portalsecretaria/images/emailButton.png' width='16' height='16' title='<?php echo $funcionario->email; ?>'></td>
                        <td><?php echo $funcionario->cargo; ?></td>
                        <td width="1%"><a href="javascript:visualizar(document.form, <?php echo $idFunc; ?>)" title="Visualizar"><span class="label label-view"><i class="icone-user icone-white"></i></span></a></td>
						<td width="1%"><a href="javascript:editar(document.form, <?php echo $idFunc; ?>)" title="Editar"><span class="label label-info"><i class="icone-edit icone-white"></i></span></a></td>
                        <td width="1%"><a href="javascript:excluir(document.form, <?php echo $idFunc; ?>)" title="Excluir"><span class="label label-important"><i class="icone-remove icone-white"></i></span></a></td>
					</tr>  
                <?php } ?>
    
            </tbody>
		</table>
        
		<br />
        <span class="label label-inverse">Total de Funcionários: <?php echo sizeof($funcionarios);?></span>
        
		<input name='task' type='hidden' value='funcionarios' />
     	<input name='idFunc' type='hidden' value='' />
	</form>

<?php } 


// CADASTRAR FUNCIONARIO : TELA 
function telaCadFuncionario() { 
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
	<script type="text/javascript" src="components/com_portalsecretaria/assets/js/jquery.js"></script>
    
    <!-- MÁSCARAS -->
	<script type="text/javascript" src="components/com_portalsecretaria/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
        $(function($){
            $("#cpf").mask("999.999.999-99");			
            $(".telefone").mask("(99) 9999-9999");
            $("#cep").mask("99999-999");
            $("#dataInicio").mask("99/99/9999");			
        });
    </script>
        
    <!-- CALENDÁRIO -->
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/default.css"/>
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/jquery.click-calendario-1.0.css"/>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/jquery.click-calendario-1.0-min.js"></script>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/script.js"></script>
    
    <!-- MENSAGEM DE ERRO : CAMPOS VAZIOS -->
    <script type="text/javascript">
		$(document).ready(function(){
			$("input").change(function(){
				$(this).css("border","1px solid #CCCCCC");
			});
			
			$("#botao").click(function(){
				var cont = 0;
				
				$("#form .obrigatorio").each(function() {
					if($(this).val() == "") {
						cont++;
						
						$(this).css("border","1px solid #F00");						
					}
				});
				
				if(cont == 0) {
					$("#form").submit();
				}
			});
		});
	</script>
    
    <style>		
		.sub-title{ background-color:#7196d8; color:#FFF; width:100%; height:30px; line-height:30px; margin-bottom:10px; }
    </style>
    
	<form method="post" name="formCadFunc" id="form" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" class="form-horizontal">
                  
        <!-- BARRA DE FERRAMENTAS -->
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-save">
                    <a href="#" id="botao">
                    <span class="icon-32-save"></span><?php echo JText::_( 'Salvar' ); ?></a>
                </div>
                
                <div class="icon" id="toolbar-cancel">
                    <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=funcionarios">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
            
            <div class="clr"></div>            
            </div>
        
            <div class="pagetitle icon-48-groups-add">
                <h2>Cadastro de Funcionários</h2>
            </div>
        </div></div>
    
        <span class="label label-info">Informações</span>
        <ul>
            <li>Os campos com <span class="obrigatory"> * </span> são de preenchimento obrigatório</li>
            <li></li>
        </ul>
        <hr />
        
        <fieldset>
        	<legend class="sub-title">Dados Pessoais</legend>
            
            <table>
            	<tbody>
                	<tr>
                    	<td colspan="2">Nome <span class="obrigatory">*</span></td>
                        <td>CPF <span class="obrigatory">*</span></td>
                        <td colspan="2">Email <span class="obrigatory">*</span></td>
                    </tr>
                    <tr>
                    	<td colspan="2"><input type="text" name="nome" size="50" class="obrigatorio" autofocus="autofocus" /></td>
                        <td><input type="text" name="cpf" id="cpf" class="obrigatorio" /></td>
                        <td colspan="2"><input type="text" name="email" size="35" class="obrigatorio" /></td>
                    </tr>
                    <tr>
                    	<td colspan="2">Endereço <span class="obrigatory">*</span></td>
                        <td>Bairro <span class="obrigatory">*</span></td>
                        <td>Cidade <span class="obrigatory">*</span></td>
                        <td>UF <span class="obrigatory">*</span></td>
                    </tr>
                    <tr>
                    	<td colspan="2"><input type="text" name="endereco" size="50" class="obrigatorio" /></td>
                        <td><input type="text" name="bairro" class="obrigatorio" /></td>
                        <td><input type="text" name="cidade" class="obrigatorio" /></td>
                        <td>
                            <select name="uf" class="obrigatorio">
                                <option value=""></option>
                                <option value="Outro">AC</option>
                                <option value="AL">AL</option>
                                <option value="AM">AM</option>
                                <option value="AP">AP</option>
                                <option value="BA">BA</option>
                                <option value="CE">CE</option>
                                <option value="DF">DF</option>
                                <option value="ES">ES</option>
                                <option value="GO">GO</option>
                                <option value="MA">MA</option>
                                <option value="MG">MG</option>
                                <option value="MS">MS</option>
                                <option value="MT">MT</option>
                                <option value="PA">PA</option>
                                <option value="PB">PB</option>
                                <option value="PE">PE</option>
                                <option value="PI">PI</option>
                                <option value="PR">PR</option>
                                <option value="RJ">RJ</option>
                                <option value="RN">RN</option>
                                <option value="RO">RO</option>
                                <option value="RR">RR</option>
                                <option value="RS">RS</option>
                                <option value="SC">SC</option>
                                <option value="SE">SE</option>
                                <option value="SP">SP</option>
                                <option value="TO">TO</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    	<td>CEP</td>
                    	<td>Telefone Residencial <span class="obrigatory">*</span></td>
                        <td>Telefone Celular</td>
                    </tr>
                    <tr>
                    	<td><input type="text" name="cep" id="cep" /></td>
                    	<td><input type="text" name="tel_residencial" class="telefone obrigatorio" /></td>
                        <td><input type="text" name="tel_celular" class="telefone" /></td>
                    </tr>
                </tbody>
            </table>
            
		</fieldset>
        
        <fieldset>
        	<legend class="sub-title">Dados Profissionais</legend>
            
        	<table>
            	<tbody>
                	<tr>
                    	<td>Data de Ingresso</td>
                        <td>SIAPE</td>
                        <td colspan="2">Cargo/Função</td>
                    </tr>
                    <tr>
                    	<td><input type="text" name="dataingresso" id="dataInicio" /></td>
                        <td><input type="text" name="siape" /></td>
                        <td><input type="text" name="cargo" size="65" /></td>
					</tr>                    
                </tbody>
            </table>    
        </fieldset>
        
		<input type="submit" value="Submeter" name="submeter" style="display:none" />
		<input name="task" type="hidden" value="cadFuncionario" />
    </form>

<?php } 


// CADASTRAR FUNCIONÁRIO : SQL
function salvarFuncionario() {
	$database =& JFactory::getDBO();
	
	$nome = JRequest::getVar('nome');
	$cpf = JRequest::getVar('cpf');
	$email = JRequest::getVar('email');
	$endereco = JRequest::getVar('endereco');
	$bairro = JRequest::getVar('bairro');
	$cidade = JRequest::getVar('cidade');
	$uf = JRequest::getVar('uf');
	$cep = JRequest::getVar('cep');
	$tel_residencial = JRequest::getVar('tel_residencial');
	$tel_celular = JRequest::getVar('tel_celular');	
	$data = JRequest::getVar('data_ingresso');
	$data_ingresso = dataSql($data);
	$siape = JRequest::getVar('siape');
	$cargo = JRequest::getVar('cargo');

	$sql = "INSERT INTO #__funcionarios (id, nome, cpf, email, endereco, bairro, cidade, uf, cep, tel_residencial, tel_celular, data_ingresso, siape, cargo) 	
			VALUES ('', '$nome', '$cpf', '$email', '$endereco', '$bairro', '$cidade', '$uf', '$cep', '$tel_residencial', '$tel_celular', '$data_ingresso', '$siape', '$cargo')";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	$numeros = preg_replace("/\D/", "", $cpf);
	$senha = md5($numeros);
	$registerDate = date("Y/m/d h:i:s");

	newUser($nome, $email, $email, $senha, $registerDate, $usertype = 'Registered', $block = '0', $sendEmail = '1', $gid = '18');
	
	if($funcionou){
		echo '<div class="alert alert-success" id="alert">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>CADASTRO</b>
				<br />
				Funcionário cadastrado com sucesso!
			  </div>';
	} else {
		echo '<div class="alert alert-error" id="alert">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>CADASTRO</b>
				Não foi possível cadastrar o funcionário, tente novamente!
			  </div>';
	}
	
	listarFuncionarios();
}


// EDITAR FUNCIONARIO : TELA 
function telaEditarFuncionario($funcionario) { 
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
	<script type="text/javascript" src="components/com_portalsecretaria/assets/js/jquery.js"></script>
    
    <!-- MÁSCARAS -->
	<script type="text/javascript" src="components/com_portalsecretaria/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
        $(function($){
            $("#cpf").mask("999.999.999-99");			
            $(".telefone").mask("(99) 9999-9999");
            $("#cep").mask("99999-999");
            $("#dataInicio").mask("99/99/9999");			
        });
    </script>
        
    <!-- CALENDÁRIO -->
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/default.css"/>
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/jquery.click-calendario-1.0.css"/>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/jquery.click-calendario-1.0-min.js"></script>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/script.js"></script>
    
    <!-- MENSAGEM DE ERRO : CAMPOS VAZIOS -->
    <script type="text/javascript">
		$(document).ready(function(){
			$("input").change(function(){
				$(this).css("border","1px solid #CCCCCC");
			});
			
			$("#botao2").click(function(){
				var cont = 0;
				
				$("#form .obrigatorio").each(function() {
					if($(this).val() == "") {
						cont++;
						
						$(this).css("border","1px solid #F00");						
					}
				});
				
				if(cont == 0) {
					$("#form").submit();
				}
			});
		});
	</script>
    
    <style>		
		.sub-title{ background-color:#7196d8; color:#FFF; width:100%; height:30px; line-height:30px; margin-bottom:10px; }
    </style>
    
	<form method="post" name="formEditFunc" id="form" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" class="form-horizontal">
                  
        <!-- BARRA DE FERRAMENTAS -->
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-save">
                    <a href="#" id="botao2">
                    <span class="icon-32-save"></span><?php echo JText::_( 'Atualizar' ); ?></a>
                </div>
                
                <div class="icon" id="toolbar-cancel">
                    <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=funcionarios">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
            
            <div class="clr"></div>            
            </div>
        
            <div class="pagetitle icon-48-massmail">
                <h2>Edição de Funcionários</h2>
            </div>
        </div></div>
    
        <span class="label label-info">Informações</span>
        <ul>
            <li>Os campos com <span class="obrigatory"> * </span> são de preenchimento obrigatório</li>
            <li></li>
        </ul>
        <hr />
        
        <fieldset>
        	<legend class="sub-title">Dados Pessoais</legend>
            
            <table>
            	<tbody>
                	<tr>
                    	<td colspan="2">Nome <span class="obrigatory">*</span></td>
                        <td>CPF <span class="obrigatory">*</span></td>
                        <td colspan="2">Email <span class="obrigatory">*</span></td>
                    </tr>
                    <tr>
                    	<td colspan="2"><input type="text" name="nome" size="50" class="obrigatorio" value="<?php echo $funcionario->nome; ?>" /></td>
                        <td><input type="text" name="cpf" id="cpf" class="obrigatorio" value="<?php echo $funcionario->cpf; ?>" /></td>
                        <td colspan="2"><input type="text" name="email" size="35" class="obrigatorio" value="<?php echo $funcionario->email; ?>" /></td>
                    </tr>
                    <tr>
                    	<td colspan="2">Endereço <span class="obrigatory">*</span></td>
                        <td>Bairro <span class="obrigatory">*</span></td>
                        <td>Cidade <span class="obrigatory">*</span></td>
                        <td>UF <span class="obrigatory">*</span></td>
                    </tr>
                    <tr>
                    	<td colspan="2"><input type="text" name="endereco" size="50" class="obrigatorio" value="<?php echo $funcionario->endereco; ?>" /></td>
                        <td><input type="text" name="bairro" class="obrigatorio" value="<?php echo $funcionario->bairro; ?>" /></td>
                        <td><input type="text" name="cidade" class="obrigatorio" value="<?php echo $funcionario->cidade; ?>" /></td>
                        <td>
                            <select name="uf" class="obrigatorio">
                                <option value="Outro" <?php if ($funcionario->uf == "Outro") echo 'SELECTED';?>>Outro</option>
                                <option value="AC" <?php if ($funcionario->uf == "AC") echo 'SELECTED';?>>AC</option>
                                <option value="AL" <?php if ($funcionario->uf == "AL") echo 'SELECTED';?>>AL</option>
                                <option value="AM" <?php if ($funcionario->uf == "AM") echo 'SELECTED';?>>AM</option>
                                <option value="AP" <?php if ($funcionario->uf == "AP") echo 'SELECTED';?>>AP</option>
                                <option value="BA" <?php if ($funcionario->uf == "BA") echo 'SELECTED';?>>BA</option>
                                <option value="CE" <?php if ($funcionario->uf == "CE") echo 'SELECTED';?>>CE</option>
                                <option value="DF" <?php if ($funcionario->uf == "DF") echo 'SELECTED';?>>DF</option>
                                <option value="ES" <?php if ($funcionario->uf == "ES") echo 'SELECTED';?>>ES</option>
                                <option value="GO" <?php if ($funcionario->uf == "GO") echo 'SELECTED';?>>GO</option>
                                <option value="MA" <?php if ($funcionario->uf == "MA") echo 'SELECTED';?>>MA</option>
                                <option value="MG" <?php if ($funcionario->uf == "MG") echo 'SELECTED';?>>MG</option>
                                <option value="MS" <?php if ($funcionario->uf == "MS") echo 'SELECTED';?>>MS</option>
                                <option value="MT" <?php if ($funcionario->uf == "MT") echo 'SELECTED';?>>MT</option>
                                <option value="PA" <?php if ($funcionario->uf == "PA") echo 'SELECTED';?>>PA</option>
                                <option value="PB" <?php if ($funcionario->uf == "PB") echo 'SELECTED';?>>PB</option>
                                <option value="PE" <?php if ($funcionario->uf == "PE") echo 'SELECTED';?>>PE</option>
                                <option value="PI" <?php if ($funcionario->uf == "PI") echo 'SELECTED';?>>PI</option>
                                <option value="PR" <?php if ($funcionario->uf == "PR") echo 'SELECTED';?>>PR</option>
                                <option value="RJ" <?php if ($funcionario->uf == "RJ") echo 'SELECTED';?>>RJ</option>
                                <option value="RN" <?php if ($funcionario->uf == "RN") echo 'SELECTED';?>>RN</option>
                                <option value="RO" <?php if ($funcionario->uf == "RO") echo 'SELECTED';?>>RO</option>
                                <option value="RR" <?php if ($funcionario->uf == "RR") echo 'SELECTED';?>>RR</option>
                                <option value="RS" <?php if ($funcionario->uf == "RS") echo 'SELECTED';?>>RS</option>
                                <option value="SC" <?php if ($funcionario->uf == "SC") echo 'SELECTED';?>>SC</option>
                                <option value="SE" <?php if ($funcionario->uf == "SE") echo 'SELECTED';?>>SE</option>
                                <option value="SP" <?php if ($funcionario->uf == "SP") echo 'SELECTED';?>>SP</option>
                                <option value="TO" <?php if ($funcionario->uf == "TO") echo 'SELECTED';?>>TO</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    	<td>CEP</td>
                    	<td>Telefone Residencial <span class="obrigatory">*</span></td>
                        <td>Telefone Celular</td>
                    </tr>
                    <tr>
                    	<td><input type="text" name="cep" id="cep" value="<?php echo $funcionario->cep; ?>" /></td>
                    	<td><input type="text" name="tel_residencial" class="telefone obrigatorio" value="<?php echo $funcionario->tel_residencial; ?>" /></td>
                        <td><input type="text" name="tel_celular" class="telefone" value="<?php echo $funcionario->tel_celular; ?>" /></td>
                    </tr>
                </tbody>
            </table>
            
		</fieldset>
        
        <fieldset>
        	<legend class="sub-title">Dados Profissionais</legend>
            
        	<table>
            	<tbody>
                	<tr>
                    	<td>Data de Ingresso</td>
                        <td>SIAPE</td>
                        <td colspan="2">Cargo/Função</td>
                    </tr>
                    <tr>
                    	<td><input type="text" name="data_ingresso" id="dataInicio" value="<?php echo $funcionario->data_ingresso; ?>" /></td>
                        <td><input type="text" name="siape" value="<?php echo $funcionario->siape; ?>" /></td>
                        <td><input type="text" name="cargo" size="65" value="<?php echo $funcionario->cargo; ?>" /></td>
					</tr>                    
                </tbody>
            </table>    
        </fieldset>
        
		<input type="submit" value="Submeter" name="submeter" style="display:none" />
		<input name="idFunc" type="hidden" value="<?php echo $funcionario->id; ?>" />
		<input name="task" type="hidden" value="editFuncionario" />
    </form>

<?php } 


// EDITAR FUNCIONARIO : SQL
function atualizarFuncionario ($idFunc) {
	$database = JFactory::getDBO();
	
	$nome = JRequest::getVar('nome');
	$cpf = JRequest::getVar('cpf');
	$email = JRequest::getVar('email');
	$endereco = JRequest::getVar('endereco');
	$bairro = JRequest::getVar('bairro');
	$cidade = JRequest::getVar('cidade');
	$uf = JRequest::getVar('uf');
	$cep = JRequest::getVar('cep');
	$tel_residencial = JRequest::getVar('tel_residencial');
	$tel_celular = JRequest::getVar('tel_celular');	
	$data = JRequest::getVar('data_ingresso');
	$data_ingresso = dataSql($data);
	$siape = JRequest::getVar('siape');
	$cargo = JRequest::getVar('cargo');
	
	$sql = "UPDATE #__funcionarios SET nome = '$nome', cpf = '$cpf', email = '$email', endereco = '$endereco', bairro = '$bairro', cidade = '$cidade', uf = '$uf', cep = '$cep', tel_residencial = '$tel_residencial', tel_celular = '$tel_celular', data_ingresso = '$data_ingresso', siape = '$siape', cargo = '$cargo' WHERE id = '$idFunc' ";
	$database->setQuery($sql);
	$funcionou = $database->Query();

	if($funcionou) {
		echo '<div class="alert alert-success" id="alert">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>EDIÇÃO</b>
				<br />
				Dados do funcionário atualizado com sucesso!
			  </div>';
	} else {
		echo '<div class="alert alert-error" id="alert">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>EDIÇÃO</b>
				<br />
				Não foi possível atualizar o registro, tente novamente!
			  </div>';
	}
	
	listarFuncionarios();
} 


// EXCLUIR FUNCIONÁRIO
function excluirFuncionario($idFunc) {
	$database = JFactory::getDBO();
	
	$sql = "DELETE FROM #__funcionarios WHERE id = '$idFunc'";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	if($funcionou) {
		echo '<div class="alert alert-success">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>EXCLUSÃO</b>
				<br />
				Funcionário excluído com sucesso!
			  </div>';
	} else {
		echo '<div class="alert alert-error">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>EXCLUSÃO</b>
				<br />
				Não foi possível excluir o funcionário, tente novamente!
			  </div>';
	}
	
	listarFuncionarios();
}


// IDENTIFICAR FUNCIONÁRIO ID 
function identificarFuncionarioID($idFunc) {
    $database =& JFactory::getDBO();

    $sql = "SELECT * FROM #__funcionarios WHERE id = '$idFunc'";
    $database->setQuery( $sql );
    $funcionario = $database->loadObjectList();

    return ($funcionario[0]);
}


// VISUALIZAR DADOS DO FUNCIONÁRIO
function relatorioFuncionario($funcionario) {
    $database =& JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
   	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />

    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
		<div class="cpanel2">
            <div class="icon" id="toolbar-back">
                <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=funcionarios">
                    <span class="icon-32-back"></span>Voltar
                </a>
            </div>
		</div>
    	<div class="clr"></div>
		</div>
        
		<div class="pagetitle icon-48-contact"><h2>Informações do Funcionário</h2></div>
    </div></div>

	<fieldset>
    	<legend><span class="label label-info">Dados Pessoais</span></legend>
        
        <table class="table">
        	<tr>
            	<td><b>Nome:</b></td>
                <td><?php echo $funcionario->nome; ?></td>
                <td><b>CPF:</b></td>
                <td colspan="3"><?php echo $funcionario->cpf; ?></td>
            </tr>
            <tr>
            	<td><b>Email:</b></td>
                <td><?php echo $funcionario->email; ?></td>
                <td><b>Residencial:</b></td>
               	<td><?php echo $funcionario->tel_residencial; ?></td>
               	<td><b>Celular:</b></td>
               	<td><?php echo $funcionario->tel_celular; ?></td>
            </tr>
            <tr>
            	<td><b>Endereço:</b></td>
                <td><?php echo $funcionario->endereco; ?></td>
                <td><b>Bairro:</b></td>
                <td><?php echo $funcionario->bairro.' ('.$funcionario->cidade.'/'.$funcionario->uf.')'; ?></td>
                <td><b>CEP:</b></td>
                <td><?php echo $funcionario->cep; ?></td>
            </tr>
            <tr>
				<td colspan="6"><legend><span class="label label-info">Dados Profissionais</span></legend></td>
			</tr>
        	<tr>
            	<td><b>SIAPE:</b></td>
                <td><?php echo $funcionario->siape; ?></td>
                <td><b>Ingresso:</b></td>
                <td><?php echo dataBr($funcionario->data_ingresso); ?></td>
                <td><b>Cargo:</b></td>
                <td><?php echo $funcionario->cargo;?></td>
            </tr>
        </table>
    </fieldset>
  
<?php } ?>


<?php // CRIAR NOVO USUÁRIO
function newUser($name, $username, $email, $password, $registerDate = NULL, $usertype = 'Registered', $block = '0', $sendEmail = '1', $gid = '18') {

	 global $db;

	 $db = & JFactory::getDBO();
	 jimport('joomla.user.helper');

	 $user = new stdClass;
	 $user->id = NULL;
	 $user->name = $name;
	 $user->username = $username;
	 $user->email = $email;
	 $user->password = $password;
	 $user->registerDate = registerDate;
	 $user->usertype = $usertype;
	 $user->block = $block;
	 $user->sendEmail = $sendEmail;
	$user->registerDate = $registerDate;
	 //$user->gid = $gid;

	 if (!$db->insertObject('#__users', $user, 'id')) {
		echo $db->stderr();
		return false;
	 }
	 
     $sql = "INSERT INTO #__user_usergroup_map (user_id, group_id) VALUES (".$user->id.", 11)";
	 $db->setQuery($sql);
	 $db->Query();

	 return 1;
}

<?php // LISTAGEM DE PROFESSORES
function listarProfessores($nome = "") {  
	$database =& JFactory::getDBO();
	$Itemid = JRequest::getInt('Itemid', 0);
  
  	$sql = "SELECT P.id, nomeProfessor, P.idLinhaPesquisa, email, sigla, telresidencial, telcelular, CPF, icomp, ppgi FROM #__professores AS P 					    JOIN #__linhaspesquisa AS LP ON P.idLinhaPesquisa = LP.id WHERE nomeProfessor LIKE '%$nome%' ORDER BY nomeProfessor ";
  
  	$database->setQuery( $sql );
  	$professores = $database->loadObjectList();
  	$statusImg = array (0 => "reprovar.gif",1 => "sim.gif"); ?>
    
    <!-- SCRIPTS -->
	<script language="JavaScript">
		function excluir(form) {
			var idSelecionado = 0;
			
			for(i = 0;i < form.idProfSelec.length;i++)
			  if(form.idProfSelec[i].checked) idSelecionado = form.idProfSelec[i].value;
			
			if(idSelecionado > 0) {
					 var resposta = window.confirm("Confirmar exclusao do Professor?");
					 if(resposta){
						form.task.value = 'excluirProfessor';
						form.idProf.value = idSelecionado;
						form.submit();
					 }
			} else {
			  alert('Ao menos 1 item deve ser selecionado para edi\xE7\xE3o.')
			}		
		}
  
		function editar(form) {			
			var idSelecionado = 0;
			
			for(i = 0;i < form.idProfSelec.length;i++)
			if(form.idProfSelec[i].checked) idSelecionado = form.idProfSelec[i].value;
			
			if(idSelecionado > 0) {
			   form.task.value = 'editarProfessor';
			   form.idProf.value = idSelecionado;
			   form.submit();
			} else {
			  alert('Ao menos 1 item deve ser selecionado para edi\xE7\xE3o.')
			
			}
		}
  
		function visualizar(form) {
			var idSelecionado = 0;
			
			for(i = 0;i < form.idProfSelec.length;i++)
				if(form.idProfSelec[i].checked) idSelecionado = form.idProfSelec[i].value;
	  
			if(idSelecionado > 0){
				 form.task.value = 'verProfessor';
				 form.idProf.value = idSelecionado;
				 form.submit();
			} else {			 
				alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o.')
			}
		}
	</script>
    
    <!-- TABLEMASTER -->
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
    
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();	
		});
	</script>
    
   	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />

    <form method="post" name="form" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" >
    	
        <!-- BARRA DE OPÇÕES -->
    	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        	<div class="cpanel2">
				<div class="icon" id="toolbar-new">
           			<a href="javascript:document.form.task.value='addProfessor';document.form.submit()">
           				<span class="icon-32-new"></span><?php echo JText::_( 'Novo' ); ?>
                    </a>
				</div>
                
				<div class="icon" id="toolbar-edit">
           			<a href="javascript:editar(document.form)">
           				<span class="icon-32-edit"></span><?php echo JText::_( 'Editar' ); ?>
                    </a>
				</div>
                
				<div class="icon" id="toolbar-preview">
           			<a href="javascript:visualizar(document.form)">
           				<span class="icon-32-preview"></span><?php echo JText::_( 'Visualizar' ); ?>
                    </a>
				</div>
                
				<div class="icon" id="toolbar-delete">
           			<a href="javascript:excluir(document.form)">
           				<span class="icon-32-delete"></span><?php echo JText::_( 'Excluir' ); ?>
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
            <div class="pagetitle icon-48-contact"><h2>Professores (IComp)</h2></div>
        </div></div>

		<!-- FILTRO -->
		<fieldset>
            <legend>Filtro para consulta</legend>
            
            <input type="text" name="buscaNome" id="buscaNome" placeholder="Busque por nome..." value="<?php echo $nome;?>" style="width:200px;"/>
            
		    <button type="submit" name="buscar" class="btn btn-primary" title="Filtra busca">
            	<i class="icone-search icone-white"></i> Consultar
            </button>            
        </fieldset>        

		<table class="table table-striped" id="tablesorter-imasters">
            <thead class="head-one">
                <tr>
                    <th></th>
                    <th>CPF</th>
                    <th>Fones</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>IComp</th>
                    <th>PPGI</th>
                    <th>Linha</th>
                </tr>
            </thead>
            
            <tbody>
                <?php
                $linhaPesquisa = array (1 => "bd_ri",2 => "se_es",3 => "ia",4 => "visao",5 => "redes",6 => "otimizacao");
    
                foreach($professores as $professor) { ?>
                
                <tr>
	                <td><input type="radio" name="idProfSelec" value="<?php echo $professor->id;?>"></td>
    	            <td><?php echo $professor->CPF;?></td>
        	        <td>
						<?php if($professor->telresidencial) echo "<img src='components/com_portalsecretaria/images/telefone.png' title='".$professor->telresidencial."'>";?>
	                    <?php if($professor->telcelular) echo "<img src='components/com_portalsecretaria/images/celular.png' title='".$professor->telcelular."'>";?>
	                </td>
    	            <td><?php echo $professor->nomeProfessor;?></td>
        	        <td><img border='0' src='components/com_portalsecretaria/images/emailButton.png' width='16' height='16' title='<?php echo $professor->email;?>'>
                    </td>
                	<td><img src="components/com_portalsecretaria/images/<?php echo $statusImg[$professor->icomp];?>" border="0">
                    </td>
	                <td><img src="components/com_portalsecretaria/images/<?php echo $statusImg[$professor->ppgi];?>" border="0">
                    </td>
	                <td><img border='0' src='components/com_portalsecretaria/images/<?php echo $linhaPesquisa[$professor->idLinhaPesquisa];?>.gif' title='<?php echo verLinhaPesquisa($aluno->area, 1);?>'>
                    </td>
                </tr>
    
                <?php } ?>
    
            </tbody>
		</table>
        
		<br />
        <span class="label label-inverse">Total de Professores: <?php echo sizeof($professores);?></span>
        
		<input name='task' type='hidden' value='professores' />
     	<input name='idProfSelec' type='hidden' value='0' />
     	<input name='idProf' type='hidden' value='' />
	</form>

<?php }


// IDENTIFICAR PROFESSOR ID 
function identificarProfessorID($idProf) {
    $database =& JFactory::getDBO();

    $sql = "SELECT * FROM #__professores WHERE id = $idProf LIMIT 1";
    $database->setQuery( $sql );
    $professor = $database->loadObjectList();

    return ($professor[0]);
}


// LISTAR ORIENTANDOS DO PROFESSOR
function getOrientandos($idProf) {
    $database =& JFactory::getDBO();

    $sql = "SELECT * FROM #__aluno WHERE orientador = $idProf ORDER BY nome";
    $database->setQuery( $sql );
    $alunos = $database->loadObjectList();

    return ($alunos);
}


// CADASTRAR/EDITAR PROFESSORES : TELA
function editarProfessor($professor = NULL, $nome) {
    $database =& JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
    <!-- SCRIPTS -->
	<script type="text/javascript" src="components/com_portalsecretaria/assets/js/jquery.js"></script>

	<script language="JavaScript">
		function IsEmpty(aTextField) {
			if ((aTextField.value.length==0) || (aTextField.value==null) ) {
				return true;
			} else { return false; }
		}

		function isValidEmail(str) {
		   return (str.indexOf(".") > 2) && (str.indexOf("@") > 0);
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
		
		   if (sText.length <= 0){
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

		function vercpf (cpf){
		   if (cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" || cpf == "99999999999")
			  return false;
		   add = 0;
		
		   for (i=0; i < 9; i ++)
			  add += parseInt(cpf.charAt(i)) * (10 - i);
		
		   rev = 11 - (add % 11);
		   if (rev == 10 || rev == 11)
			  rev = 0;
		
		   if (rev != parseInt(cpf.charAt(9)))
			  return false;
		
		   add = 0;
		   for (i = 0; i < 10; i ++)
			  add += parseInt(cpf.charAt(i)) * (11 - i);
		
		   rev = 11 - (add % 11);
		   if (rev == 10 || rev == 11)
			   rev = 0;
		
		   if (rev != parseInt(cpf.charAt(10)))
			   return false;
		
		   return true;
		}

		function VerificaData(digData) {
			var bissexto = 0;
			var data = digData;
			var tam = data.length;
			
			if (tam == 10) {
				var dia = data.substr(0,2)
				var mes = data.substr(3,2)
				var ano = data.substr(6,4)
				
				if ((ano > 1900)||(ano < 2100)) {
					switch (mes) {
						case '01':
						case '03':
						case '05':
						case '07':
						case '08':
						case '10':
						case '12':
							if  (dia <= 31) {
								return true;
							}
						break
						case '04':
						case '06':
						case '09':
						case '11':
							if  (dia <= 30) {
								return true;
							}
						break
						case '02':
							/* Validando ano Bissexto / fevereiro / dia */
							if ((ano % 4 == 0) || (ano % 100 == 0) || (ano % 400 == 0)) {
									bissexto = 1;
							}
							if ((bissexto == 1) && (dia <= 29)) {
									return true;
							}
							if ((bissexto != 1) && (dia <= 28)) {
									return true;
							}
							break
						}
					}
        	}
			return false;
		}

		function ValidateformCadastro(formCadastro) {
			if(IsEmpty(formCadastro.nome)) {
				alert(unescape('O campo Nome deve ser preenchido.'))
				formCadastro.nome.focus();
				return false;
			}

			if(!vercpf(formCadastro.cpf.value)) {
				alert('Campo CPF invalido.')
				formCadastro.cpf.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.email)) {
				alert(unescape('O campo Email deve ser preenchido.'))
				formCadastro.email.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.endereco)) {
				alert('O campo Endereco deve ser preenchido.')
				formCadastro.endereco.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.bairro)) {
				alert('O campo Bairro deve ser preenchido.')
				formCadastro.bairro.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.cidade)) {
				alert('O campo Cidade deve ser preenchido.')
				formCadastro.cidade.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.uf)) {
				alert('O campo UF deve ser preenchido.')
				formCadastro.uf.focus();
				return false;
			}
			
			if(IsEmpty(formCadastro.cep)) {
				alert('O campo CEP deve ser preenchido.')
				formCadastro.cep.focus();
				return false;
			}
			if(IsEmpty(formCadastro.linhapesquisa)) {
				alert('O campo LINHA DE PESQUISA deve ser preenchido.')
				formCadastro.linhapesquisa.focus();
				return false;
			}
			return true;			
		}

		function voltarForm(form) {		
		   form.task.value = 'professores';
		   form.submit();
		   return true;		
		}
	</script>
    
    <!-- MÁSCARAS PARA DATAS -->
	<script type="text/javascript" src="components/com_portalsecretaria/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
        $(function($){
            $(".telefone").mask("(99)9999-9999");
            $("#cep").mask("99999-999");
        });
    </script>
    
   	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />
    
    <style>		
		.sub-title{ background-color:#7196d8; color:#FFF; font-weight:bold; width:100%; height:30px; line-height:30px; }
    </style>

   <form method="post" name="formCadastro" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateformCadastro(this)">
   
    	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        	<div class="cpanel2">
				<div class="icon" id="toolbar-save">
           			<a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
           				<span class="icon-32-save"></span>Salvar
                    </a>
				</div>

				<div class="icon" id="toolbar-cancel">
	           		<a href="javascript:voltarForm(document.formCadastro)">
           				<span class="icon-32-cancel"></span>Cancelar
                    </a>
				</div>
            </div>
            
            <div class="clr"></div>
            </div>
			<div class="pagetitle icon-48-edit"><h2>Cadastro/Edi&#231;&#227;o de Professores</h2></div>
        </div></div>

        <span class="label label-info">Como proceder </span>
        <ul>
        	<li>Preencha todos os campos com os dados necessários
            <li>Os campos com <span class="obrigatory">*</span> s&#227;o de preenchimento obrigatório</li>
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
                    	<td colspan="2"><input type="text" name="nome" value="<?php if($professor) echo $professor->nomeProfessor; ?>" size="50"></td>
                        <td><input type="text" name="cpf" value="<?php if($professor) echo $professor->CPF; ?>"></td>
                        <td colspan="2"><input type="text" name="email" value="<?php if($professor) echo $professor->email; ?>" size="30"></td>
                    </tr>
                    <tr>
                    	<td colspan="2">Endereço <span class="obrigatory">*</span></td>
                        <td>Bairro <span class="obrigatory">*</span></td>
                        <td>Cidade <span class="obrigatory">*</span></td>
                        <td>UF <span class="obrigatory">*</span></td>
                    </tr>
                    <tr>
                    	<td colspan="2"><input type="text" name="endereco" value="<?php if($professor) echo $professor->endereco; ?>" size="50"></td>
                        <td><input type="text" name="bairro" value="<?php if($professor) echo $professor->bairro; ?>"></td>
                        <td><input type="text" name="cidade" value="<?php if($professor) echo $professor->cidade; ?>"></td>
                        <td>
                            <select name="uf">
                                <option value="" <?php if ($professor && $professor->uf == "") echo 'SELECTED';?>></option>
                                <option value="Outro" <?php if ($professor && $professor->uf == "Outro") echo 'SELECTED';?>>Outro</option>
                                <option value="AC" <?php if ($professor && $professor->uf == "AC") echo 'SELECTED';?>>AC</option>
                                <option value="AL" <?php if ($professor && $professor->uf == "AL") echo 'SELECTED';?>>AL</option>
                                <option value="AM" <?php if ($professor && $professor->uf == "AM") echo 'SELECTED';?>>AM</option>
                                <option value="AP" <?php if ($professor && $professor->uf == "AP") echo 'SELECTED';?>>AP</option>
                                <option value="BA" <?php if ($professor && $professor->uf == "BA") echo 'SELECTED';?>>BA</option>
                                <option value="CE" <?php if ($professor && $professor->uf == "CE") echo 'SELECTED';?>>CE</option>
                                <option value="DF" <?php if ($professor && $professor->uf == "DF") echo 'SELECTED';?>>DF</option>
                                <option value="ES" <?php if ($professor && $professor->uf == "ES") echo 'SELECTED';?>>ES</option>
                                <option value="GO" <?php if ($professor && $professor->uf == "GO") echo 'SELECTED';?>>GO</option>
                                <option value="MA" <?php if ($professor && $professor->uf == "MA") echo 'SELECTED';?>>MA</option>
                                <option value="MG" <?php if ($professor && $professor->uf == "MG") echo 'SELECTED';?>>MG</option>
                                <option value="MS" <?php if ($professor && $professor->uf == "MS") echo 'SELECTED';?>>MS</option>
                                <option value="MT" <?php if ($professor && $professor->uf == "MT") echo 'SELECTED';?>>MT</option>
                                <option value="PA" <?php if ($professor && $professor->uf == "PA") echo 'SELECTED';?>>PA</option>
                                <option value="PB" <?php if ($professor && $professor->uf == "PB") echo 'SELECTED';?>>PB</option>
                                <option value="PE" <?php if ($professor && $professor->uf == "PE") echo 'SELECTED';?>>PE</option>
                                <option value="PI" <?php if ($professor && $professor->uf == "PI") echo 'SELECTED';?>>PI</option>
                                <option value="PR" <?php if ($professor && $professor->uf == "PR") echo 'SELECTED';?>>PR</option>
                                <option value="RJ" <?php if ($professor && $professor->uf == "RJ") echo 'SELECTED';?>>RJ</option>
                                <option value="RN" <?php if ($professor && $professor->uf == "RN") echo 'SELECTED';?>>RN</option>
                                <option value="RO" <?php if ($professor && $professor->uf == "RO") echo 'SELECTED';?>>RO</option>
                                <option value="RR" <?php if ($professor && $professor->uf == "RR") echo 'SELECTED';?>>RR</option>
                                <option value="RS" <?php if ($professor && $professor->uf == "RS") echo 'SELECTED';?>>RS</option>
                                <option value="SC" <?php if ($professor && $professor->uf == "SC") echo 'SELECTED';?>>SC</option>
                                <option value="SE" <?php if ($professor && $professor->uf == "SE") echo 'SELECTED';?>>SE</option>
                                <option value="SP" <?php if ($professor && $professor->uf == "SP") echo 'SELECTED';?>>SP</option>
                                <option value="TO" <?php if ($professor && $professor->uf == "TO") echo 'SELECTED';?>>TO</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    	<td>CEP</td>
                    	<td>Telefone Residencial</td>
                        <td>Telefone Celular</td>
                    </tr>
                    <tr>
                    	<td><input type="text" name="cep" id="cep" value="<?php if($professor) echo $professor->cep; ?>"></td>
                    	<td><input type="text" name="telresidencial" value="<?php if($professor) echo $professor->telresidencial; ?>" class="telefone"></td>
                        <td><input type="text" name="telcelular" value="<?php if($professor) echo $professor->telcelular; ?>" class="telefone"></td>
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
                        <td colspan="2">Linha de Pesquisa</td>
                    </tr>
                    <tr>
                    	<td><input type="text" name="dataingresso" id="dataIngresso" value="<?php if($professor) echo $professor->dataIngresso; ?>"></td>
                        <td><input type="text" name="siape" value="<?php if($professor) echo $professor->siape; ?>"></td>
                        <td colspan="2">
                        	<select name="linhapesquisa">                        
                                <option value="" <?php if ($professor && $professor->idLinhaPesquisa == "") echo 'SELECTED';?>></option>
								<?php                        
                                    $database->setQuery("SELECT * from #__linhaspesquisa ORDER BY nome");
                                    $linhas = $database->loadObjectList();
                    
                                     foreach($linhas as $linha) { ?>
                                        <option value="<?php echo $linha->id;?>" <?php if($professor && $linha->id == $professor->idLinhaPesquisa) echo 'SELECTED'?>><?php echo $linha->nome;?>
                                        </option>
                                <?php } ?>
                            </select>
                        </td>
					</tr>
			        <tr>
                        <td>Professor do IComp?</td>
                        <td>Professor do PPGI?</td>
                        <td>Unidade</td>  
                        <td>Titula&#231;&#227;o</td>             	
                    </tr>
                    <tr>
                        <td>
                            <select name="icomp" style="width:100px;">
                              <option value="0" <?php if ($professor && $professor->icomp == "0") echo 'SELECTED';?>>N&#227;o</option>
                              <option value="1" <?php if ($professor && $professor->icomp == "1") echo 'SELECTED';?>>Sim</option>
                            </select>
                        </td>
                        <td>
                            <select name="ppgi" style="width:100px;">
                              <option value="0" <?php if ($professor && $professor->ppgi == "0") echo 'SELECTED';?>>N&#227;o</option>
                              <option value="1" <?php if ($professor && $professor->ppgi == "1") echo 'SELECTED';?>>Sim</option>
                            </select>
                        </td>
                        <td><input type="text" name="unidade" value="<?php if($professor) echo $professor->unidade; ?>"></td>
                        <td>
                        	<select name="titulacao" style="width:175px;">
                              <option value="Graduado" <?php if ($professor && $professor->titulacao == "Graduado") echo 'SELECTED';?>>Graduado</option>
                              <option value="Especialista" <?php if ($professor && $professor->titulacao == "Especialista") echo 'SELECTED';?>>Especialista</option>
                              <option value="Mestre" <?php if ($professor && $professor->titulacao == "Mestre") echo 'SELECTED';?>>Mestre</option>
                              <option value="Doutor" <?php if ($professor && $professor->titulacao == "Doutor") echo 'SELECTED';?>>Doutor</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Classe</td>
                        <td>N&#237;vel</td>
                        <td>Regime</td>
                        <td>Turno</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="classe" value="<?php if($professor) echo $professor->classe; ?>"></td>
                        <td><input type="text" name="nivel" value="<?php if($professor) echo $professor->nivel; ?>"></td>
                        <td>
                        	<select name="regime" style="width:160px;">
                              <option value="20hs" <?php if ($professor && $professor->regime == "20hs") echo 'SELECTED';?>>20h</option>
                              <option value="40hs" <?php if ($professor && $professor->regime == "40hs") echo 'SELECTED';?>>40h</option>
                              <option value="DE" <?php if ($professor && $professor->regime == "DE") echo 'SELECTED';?>>DE</option>
                            </select>
                        </td>
                        <td>
                        	<select name="turno">
                              <option value="Matutino e Vespertino" <?php if ($professor && $professor->turno == "Matutino e Vespertino") echo 'SELECTED';?>>Matutino e Vespertino</option>
                              <option value="Matutino e Noturno" <?php if ($professor && $professor->turno == "Matutino e Noturno") echo 'SELECTED';?>>Matutino e Noturno</option>
                              <option value="Vespertino e Noturno" <?php if ($professor && $professor->turno == "Vespertino e Noturno") echo 'SELECTED';?>>Vespertino e Noturno</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>    
        </fieldset>

    <input name='idProf' type='hidden' value='<?php if($professor) echo $professor->id;?>'>
    <input name='task' type='hidden' value='salvarProfessor'>
    <input name='buscaNome' type='hidden' value='<?php echo $nome;?>'>
</form>

<?php }


// CADASTRAR PROFESSOR : SQL
function salvarProfessor($idProf = "") {
  $database =& JFactory::getDBO();

  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $siape = $_POST['siape'];
  $endereco = $_POST['endereco'];
  $bairro = $_POST['bairro'];
  $cidade = $_POST['cidade'];
  $uf = $_POST['uf'];
  $cep = $_POST['cep'];
  $cpf = $_POST['cpf'];
  $telresidencial = $_POST['telresidencial'];
  $telcelular = $_POST['telcelular'];
  $dataingresso = $_POST['dataingresso'];
  $icomp = $_POST['icomp'];
  $ppgi = $_POST['ppgi'];
  $linha = $_POST['linhapesquisa'];
  $unidade = $_POST['unidade'];
  $classe = $_POST['classe'];
  $titulacao = $_POST['titulacao'];
  $nivel = $_POST['nivel'];
  $regime = $_POST['regime'];
  $turno = $_POST['turno'];

  if($idProf == "") {
    $sql = "SELECT email FROM #__professores WHERE email = '$email'";
    $database->setQuery($sql);
    $repetido = $database->loadObjectList();
	
    if(!$repetido) {
		$sql = "INSERT INTO #__professores (nomeProfessor, email, siape, idLinhaPesquisa, endereco, bairro, cidade, uf, cep, CPF, dataIngresso, telresidencial, telcelular, icomp, ppgi, unidade, titulacao, classe, nivel, regime, turno)
             VALUES ('$nome', '$email', '$siape', $linha, '$endereco', '$bairro', '$cidade', '$uf', '$cep', '$cpf', '$dataingresso',  '$telresidencial', '$telcelular', $icomp, $ppgi, '$unidade', '$titulacao', '$classe', '$nivel', '$regime', '$turno')";
		$database->setQuery($sql);
		$funcionou = $database->Query();
      
		$senha = md5($cpf);

      // Provisorio: Cria um novo usuario do Joomla para acesso do professor
      //  CreateNewProfessor($nome, $email, $email, $senha, $registerDate = NULL, $usertype = 'Registered', $block = '0', $sendEmail = '1', $gid = '18');
		if($funcionou){
			JFactory::getApplication()->enqueueMessage(JText::_('Cadastro de Professor realizado com sucesso.'));
			return 1;
		}
		else{
			JFactory::getApplication()->enqueueMessage(JText::_('ERRO ao tentar salvar os dados do professor. Dados inv&#225;lidos.'));
			return (0);
		}
    } else {
        JFactory::getApplication()->enqueueMessage(JText::_('Cadastro n&#227;o realizado. Este e-mail j&#225; est&#225; cadastrado no sistema.'));
        return (0);
    }
  } else {
    $sql = "SELECT email FROM #__professores WHERE email = '$email' AND id <> $idProf";
    $database->setQuery($sql);
    $repetido = $database->loadObjectList();
	
    if(!$repetido) {
      $sql = "UPDATE #__professores SET
           nomeProfessor ='$nome',
           email = '$email',
           siape = '$siape',
           idLinhaPesquisa = $linha,
           endereco = '$endereco',
           bairro = '$bairro',
           cidade = '$cidade',
           uf = '$uf',
           cep = '$cep',
           dataingresso = '$dataingresso',
           cpf = '$cpf',
           telresidencial = '$telresidencial',
           telcelular = '$telcelular',
           icomp = $icomp,
           ppgi = $ppgi,
           unidade = '$unidade',
           titulacao = '$titulacao',
           classe = '$classe',
           nivel = '$nivel',
           regime = '$regime',
           turno = '$turno'
         WHERE id = $idProf";

		$database->setQuery($sql);
		$funcionou = $database->Query();
	  
		if($funcionou){
			JFactory::getApplication()->enqueueMessage(JText::_('Edi&#231;&#227;o realizada com sucesso.'));
			return 1;
		}
		else {
			JFactory::getApplication()->enqueueMessage(JText::_('ERRO ao tentar salvar os dados do professor. Dados inv&#225;lidos.'));
			return (0);
		}
	} else {
      JFactory::getApplication()->enqueueMessage(JText::_('Edi&#231;&#227;o n&#227;o realizada. Este e-mail j&#225; est&#225; cadastrado no sistema.'));
      return (0);
  	}

  }
}

// EXCLUIR PROFESSORE : SQL
function excluirProfessor($idProf) {
  $database =& JFactory::getDBO();

  $sql = "DELETE FROM #__professores WHERE id = $idProf";
  $database->setQuery($sql);
  $database->Query();
}


// CREATE NEW PROFESSOR
function CreateNewProfessor($name, $username, $email, $password, $registerDate = NULL, $usertype = 'Registered', $block = '0', $sendEmail = '1', $gid = '18') {

	 global $db;

	 $db = & JFactory::getDBO();
	 jimport('joomla.user.helper');

	 //Make the joomla password hash

	 //$salt = JUserHelper::genRandomPassword(32);
	 //$crypt = JUserHelper::getCryptedPassword($password, $salt);

	 //$joomlapassword = $crypt . ':' . $salt;

	 //Table #__users
	 //Informations about the user

	 $user = new stdClass;
	 $user->id = NULL;
	 $user->name = $name;
	 $user->username = $username;
	 $user->email = $email;
	 $user->password = $password;
	 $user->registerDate = $registerDate;
	 $user->usertype = $usertype;
	 $user->block = $block;
	 $user->sendEmail = $sendEmail;
	 $user->gid = $gid;

	 if (!$db->insertObject('#__users', $user, 'id')) {
		echo $db->stderr();
		return false;

	 }

	 //Table #__core_acl_aro
	 //Discover what is the last value of value in #__core_acl_aro
	 $query = "SELECT value FROM #__core_acl_aro ORDER BY id DESC LIMIT 1";
	 $db->setQuery($query);
	 $coreaclarolastvalue = $db->loadResult();
	 $coreaclaro = new stdClass;
	 $coreaclaro->id = NULL;
	 $coreaclaro->section_value = 'users';
	 $coreaclaro->value = $coreaclarolastvalue + 1;
	 $coreaclaro->order_value = NULL;
	 $coreaclaro->name = $name;
	 $coreaclaro->hidden = NULL;

	 if (!$db->insertObject('#__core_acl_aro', $coreaclaro, 'id')) {
		echo $db->stderr();
		return false;

	 }

	 //Table #__core_acl_groups_aro_map
	 $coreaclmap = new stdClass;
	 $coreaclmap->group_id = $gid;
	 $coreaclmap->section_value = NULL;
	 $coreaclmap->aro_id = $coreaclaro->id; // maybe just $user->id ?

	 if (!$db->insertObject('#__core_acl_groups_aro_map', $coreaclmap)) {

		echo $db->stderr();
		return false;

	 }

	 $sql = "INSERT INTO #__fua_userindex (user_id, group_id) VALUES (".$user->id.", 11)";
	 $db->setQuery($sql);
	 $db->Query();

	 return($user->id);
}


function relatorioProfessor($professor) {
    $database =& JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
   	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />

    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
		<div class="cpanel2">
            <div class="icon" id="toolbar-back">
                <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=professores">
                    <span class="icon-32-back"></span>Voltar
                </a>
            </div>
		</div>
    	<div class="clr"></div>
		</div>
        
		<div class="pagetitle icon-48-contact"><h2>Informações do Professor</h2></div>
    </div></div>

	<fieldset>
    	<legend><span class="label label-info">Dados Pessoais</span></legend>
        
        <table class="table">
        	<tr>
            	<td><b>Nome:</b></td>
                <td><?php echo $professor->nomeProfessor; ?></td>
                <td><b>CPF:</b></td>
                <td colspan="4"><?php echo $professor->CPF; ?></td>
            </tr>
            <tr>
            	<td><b>Email:</b></td>
                <td><?php echo $professor->email; ?></td>
                <td><b>Residencial:</b></td>
               	<td><?php echo $professor->telresidencial; ?></td>
               	<td><b>Celular:</b></td>
               	<td colspan="2"><?php echo $professor->telcelular; ?></td>
            </tr>
            <tr>
            	<td><b>Endereço:</b></td>
                <td><?php echo $professor->endereco; ?></td>
                <td><b>Bairro:</b></td>
                <td colspan="2"><?php echo $professor->bairro.' ('.$professor->cidade.'/'.$professor->uf.')'; ?></td>
                <td><b>CEP:</b></td>
                <td><?php echo $professor->cep; ?></td>
            </tr>
        </table>
    </fieldset>
    
    	<fieldset>
    	<legend><span class="label label-info">Dados Profissionais</span></legend>
        
        <table class="table">
        	<tr>
            	<td><b>SIAPE:</b></td>
                <td><?php echo $professor->SIAPE; ?></td>
                <td><b>Ingresso:</b></td>
                <td><?php echo $professor->dataIngresso; ?></td>               
                <td><b>Professor do IComp?</b></td>
                <td><?php if ($professor->icomp == "0") echo 'N&#227;o'; else echo "Sim";?></td>
                <td><b>Professor do PPGI?</b></td>
                <td><?php if ($professor->ppgi == "0") echo 'N&#227;o'; else echo "Sim";?></td>
            </tr>
            <tr>
                <td><b>Titula&#231;&#227;o:</b></td>
                <td><?php echo $professor->titulacao;?></td>
                <td><b>Classe:</b></td>
        		<td><?php echo $professor->classe;?></td>
                <td><b>N&#237;vel:</b></td>
       			<td><?php echo $professor->nivel;?></td>
                <td><b>Regime:</b></td>
        		<td><?php echo $professor->regime;?></td>
            </tr>
            <tr>
                <td><b>Unidade:</b></td>
                <td><?php echo $professor->unidade;?></td>
                <td><b>Turno:</b></td>
                <td><?php echo $professor->turno;?></td>
                <td><b>Linha de Pesquisa:</b></td>
                <td colspan="3"><span style="font-size:14px;"><?php echo verLinhaPesquisa($professor->idLinhaPesquisa, 1); ?></span></td> 
 			</tr>
        </table>
    </fieldset>
  
<!--<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">-->

<?php listarAlunosPorProfessor($professor->id); 

} ?>


<?php
function listarAlunosPorProfessor($idProf) {
    $Itemid = JRequest::getInt('Itemid', 0);
    $database =& JFactory::getDBO();
	
    $alunos = getOrientandos($idProf); ?>
    
   	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />

    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
    	<div class="clr"></div>
		</div>
        
		<div class="pagetitle icon-48-massmail"><h2>Dados de Seus Orientados</h2></div>
    </div></div>
    <!--<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">-->

    <table class="table table-striped" id="tablesorter-imasters">
        <thead>
            <tr>
                <th>Status</th>
                <th>Matricula</th>
                <th>Nome</th>
                <th>Ingresso</th>
                <th>Curso</th>
                <th>Linha de Pesquisa</th>
            </tr>
        </thead>
        
     	<tbody>
		<?php
		$status = array (0 => "Aluno Corrente",1 => "Aluno Egresso",2 => "Aluno Desistente",3 => "Aluno Desligado",4 => "Aluno Jubilado",5 => "Aluno com Matr&#237;cula Trancada");
		
		$statusImg = array (0 => "ativo.png",1 => "egresso.png",2 => "desistente.png",3 => "desligado.gif",4 => "reprovar.gif",5 => "trancado.jpg");
		
		$curso = array (1 => "mestrado",2 => "doutorado",3 => "especial");
		
		$linhaPesquisa = array (1 => "bd_ri",2 => "se_es",3 => "ia",4 => "visao",5 => "redes",6 => "otimizacao");

		foreach( $alunos as $aluno ) { ?>
	    <tr>
            <td><div align="center"><img border='0' src='components/com_portalsecretaria/images/<?php echo $statusImg[$aluno->status];?>' width='16' height='16' title='<?php echo $status[$aluno->status]; ?>'></div></td>
            <td><?php echo $aluno->matricula; ?></td>
            <td><?php echo $aluno->nome; ?></td>
            <td><?php echo dataBr($aluno->anoingresso); ?></td>
            <td><img border='0' src='components/com_portalsecretaria/images/<?php echo $curso[$aluno->curso];?>.gif' title='<?php echo $curso[$aluno->curso]; ?>'> </td>
            <td><img border='0' src='components/com_portalsecretaria/images/<?php echo $linhaPesquisa[$aluno->area];?>.gif' title='<?php echo verLinhaPesquisa($aluno->area, 1); ?>'> </td>
        </tr>

		<?php } ?>
     	</tbody>

     </table>
     
     <br />
     <span class="label label-inverse">Total de Alunos: <?php echo sizeof($alunos);?></span>

<?php } ?>

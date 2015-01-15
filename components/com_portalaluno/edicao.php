<?php

//////////////////////////////////////////////////////////////

function editarAluno($aluno)
    {
    $Itemid = JRequest::getInt('Itemid', 0);

	?>

<script language="JavaScript">
function IsEmpty(aTextField) {
	if ((aTextField.value.length==0) ||
	(aTextField.value==null) ) {
	  return true;
	}
	else { return false; }
	}

function isValidEmail(str) {
	return (str.indexOf(".") > 2) && (str.indexOf("@") > 0);
	
	}

function radio_button_checker(elem)
	{
	  // set var radio_choice to false
	  var radio_choice = false;
	
	  // Loop from zero to the one minus the number of radio button selections
	  for (counter = 0; counter < elem.length; counter++)
	  {
		// If a radio button has been selected it will return true
		// (If not it will return false)
		if (elem[counter].checked)
		radio_choice = true;
	   }
	
	  return (radio_choice);
	}

function isPdf(file){
	
	   extArray = new Array(".pdf");
	   allowSubmit = false;
	
	   if (!file) return;
	   while (file.indexOf("\\") != -1)
	   file = file.slice(file.indexOf("\\") + 1);
	   ext = file.slice(file.indexOf(".")).toLowerCase();
	
	   for (var i = 0; i < extArray.length; i++) {
			 if (extArray[i] == ext) { allowSubmit = true; break; }
	   }
	
	   return(allowSubmit);
	}

function IsNumeric(sText)
	
	{
	   var ValidChars = "0123456789.";
	   var IsNumber=true;
	   var Char;
	
	   if (sText.length <= 0){
		  IsNumber = false;
	   }
	
	   for (i = 0; i < sText.length && IsNumber == true; i++)
		  {
		  Char = sText.charAt(i);
		  if (ValidChars.indexOf(Char) == -1)
			 {
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

function VerificaData(digData)
	{
			var bissexto = 0;
			var data = digData;
			var tam = data.length;
			if (tam == 10)
			{
					var dia = data.substr(0,2)
					var mes = data.substr(3,2)
					var ano = data.substr(6,4)
					if ((ano > 1900)||(ano < 2100))
					{
							switch (mes)
							{
									case '01':
									case '03':
									case '05':
									case '07':
									case '08':
									case '10':
									case '12':
											if  (dia <= 31)
											{
													return true;
											}
											break
	
									case '04':
									case '06':
									case '09':
									case '11':
											if  (dia <= 30)
											{
													return true;
											}
											break
									case '02':
											/* Validando ano Bissexto / fevereiro / dia */
											if ((ano % 4 == 0) || (ano % 100 == 0) || (ano % 400 == 0))
											{
													bissexto = 1;
											}
											if ((bissexto == 1) && (dia <= 29))
											{
													return true;
											}
											if ((bissexto != 1) && (dia <= 28))
											{
													return true;
											}
											break
							}
					}
			}
			 return false;
	}

function ValidateformCadastro(formCadastro)
	{
	
	   if(IsEmpty(formCadastro.email))
	   {
		  alert(unescape('O campo Email deve ser preenchido.'))
		  formCadastro.matricula.focus();
		  return false;
	   }
	
	   if(IsEmpty(formCadastro.endereco))
	   {
		  alert('O campo Endereco deve ser preenchido.')
		  formCadastro.endereco.focus();
		  return false;
	   }
	
	   if(IsEmpty(formCadastro.bairro))
	   {
		  alert('O campo Bairro deve ser preenchido.')
		  formCadastro.bairro.focus();
		  return false;
	   }
	
	   if(IsEmpty(formCadastro.cidade))
	   {
		  alert('O campo Cidade deve ser preenchido.')
		  formCadastro.cidade.focus();
		  return false;
	   }
	
	   if(IsEmpty(formCadastro.uf))
	   {
		  alert('O campo UF deve ser preenchido.')
		  formCadastro.uf.focus();
		  return false;
	   }
	
	   if(IsEmpty(formCadastro.cep))
	   {
		  alert('O campo CEP deve ser preenchido.')
		  formCadastro.cep.focus();
		  return false;
	   }
	
	   if(!VerificaData(formCadastro.datanascimento.value))
	   {
		  alert('Campo Data de Nascimento invalido.')
		  formCadastro.datanascimento.focus();
		  return false;
	   }
	
	   if (IsEmpty(formCadastro.sexo))
	   {
		 alert('O campo Sexo deve ser preenchido.')
		 formCadastro.sexo.focus();
		 return (false);
	   }
	
	   if (!radio_button_checker(formCadastro.nacionalidade))
	   {
		 // If there were no selections made display an alert box
		 alert('O campo Nacionalidade deve ser preenchido.')
		 formCadastro.nacionalidade[0].focus();
		 return (false);
	   }
	
	   if(formCadastro.nacionalidade[1].checked && IsEmpty(formCadastro.pais))
	   {
		  alert('Quando o candidato possuir a nacionalidade Estrangeira, deve ser informado o seu pais de origem.')
		 formCadastro.pais.focus();
		  return false;
	   }
	
	   if(IsEmpty(formCadastro.estadocivil))
	   {
		  alert('O campo Estado Civil deve ser preenchido.')
		  formCadastro.estadocivil.focus();
		  return false;
	   }
	
	  if(formCadastro.nacionalidade[0].checked && !vercpf(formCadastro.cpf.value))
	   {
		  alert('Campo CPF invalido.')
		  formCadastro.cpf.focus();
		  return false;
	   }
	
	   if(formCadastro.nacionalidade[0].checked && IsEmpty(formCadastro.rg))
	   {
		  alert('O campo RG deve ser preenchido.')
		  formCadastro.rg.focus();
		  return false;
	   }
	
	   if(formCadastro.nacionalidade[0].checked && IsEmpty(formCadastro.orgaoexpedidor))
	   {
		  alert('O campo Orgao Expedidor deve ser preenchido.')
		  formCadastro.orgaoexpedidor.focus();
		  return false;
	   }
	
	   if(IsEmpty(formCadastro.telresidencial))
	   {
		  alert('O campo Telefone de Contato deve ser preenchido.')
		  formCadastro.telresidencial.focus();
		  return false;
	   }
	
		if(IsEmpty(formCadastro.cursograd))
	   {
		  alert('O campo Curso de Graduacao deve ser preenchido.')
		  formCadastro.cursograd.focus();
		  return false;
	   }
	
		if(IsEmpty(formCadastro.instituicaograd))
	   {
		  alert('O campo Instituicao onde cursou a Graduacao deve ser preenchido.')
		  formCadastro.instituicaograd.focus();
		  return false;
	   }
	
	   if(IsEmpty(formCadastro.crgrad))
	   {
		  alert('O campo Coeficiente de Rendimento deve ser preenchido.')
		  formCadastro.crgrad.focus();
		  return false;
	   }
	
	   if(IsEmpty(formCadastro.egressograd))
	   {
		  alert('O campo Ano Egresso deve ser preenchido.')
		  formCadastro.egressograd.focus();
		  return false;
	   }
	
	
	return true;
	
	}

function voltarForm(form)
	{
	
	   form.task.value = 'servicos';
	   form.submit();
	   return true;
	
	}
</script>

   <form name="formCadastro" action="index.php?option=com_portalaluno&Itemid=<?php echo $Itemid;?>" method="post"  onsubmit="javascript:return ValidateformCadastro(this)">
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-save">
           		<a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
           			<span class="icon-32-save"></span><?php echo JText::_( 'Salvar' ); ?></a>
				</div>
				<div class="icon" id="toolbar-cancel">
           		<a href="javascript:voltarForm(document.formCadastro)">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
		</div>
    <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-user-edit"><h2>Atualiza&#231;&#227;o dos Dados Pessoais</h2></div>
    </div></div>

  <b>Como proceder: </b>
  <ul>
   <li>Preencha todos os campos com seus dados pessoais <font color="#FF0000">(* Campos Obrigat&#243rios)</font>.</li>
   </ul>
   <hr style="width: 100%; height: 2px;">

   <table border="0" cellpadding="1" cellspacing="2" width="100%">
    <tbody>
    <tr style="background-color: #7196d8;">
        <td colspan="2"><font size="2"><b><font color="#FFFFFF">Nome:</font></b></font></td>
        <td colspan="2"><font size="2"><b><font color="#FFFFFF">Matr&#237;cula:</font></b></font></td>
      </tr>
      <tr>
        <td colspan="2"><?php echo $aluno->nome;?></td>
        <td colspan="2"><?php echo $aluno->matricula;?></td>
      </tr>

      <tr style="background-color: #7196d8;">
        <td style="width: 50%;" colspan="2"><font size="2"><font color="#FF0000">*</font> <b><font color="#FFFFFF">Email:</font></b></font></td>
        <td style="width: 50%;" colspan="2"><font size="2"><b><font color="#FFFFFF">Status:</font></b></font></td>
      </tr>
      <tr>
        <td style="width: 100%;" colspan="2"><input maxlength="60" size="60" name="email" class="inputbox" value="<?php echo $aluno->email;?>"></td>
        <td style="width: 100%;" colspan="2">
        <?php if ($aluno->status == 0) echo 'Aluno Corrente';
              else if ($aluno->status == 1) echo 'Aluno Egresso';
              else if ($aluno->status == 2) echo 'Aluno Desistente';?>
        </td>
      </tr>
      <tr style="background-color: #7196d8;">
        <td><font size="2"><b><font color="#FFFFFF">Tipo de Aluno:</font></b></font></td>
        <td><font size="2"><b><font color="#FFFFFF">Ano de Ingresso:</font></b></font></td>
        <td><font size="2"><b><font color="#FFFFFF">Regime de dedica&#231;&#227;o:</font></b></font></td>
        <td><font size="2"><b><font color="#FFFFFF">Bolsista:</font></b></font></td>
      </tr>
      <tr>
        <td>
        <?php if ($aluno->curso == "1") echo 'Mestrado';
              else if ($aluno->curso == "2") echo 'Doutorado';
              else if ($aluno->curso == "3") echo 'Especial';?>
        </td>
        <td><?php echo $aluno->anoingresso;?></td>
        <td><?php if ($aluno->regime == 1) echo 'Integral'; else if ($aluno->regime == 2) echo 'Parcial';?></td>
        <td><?php if ($aluno->bolsista == "SIM") echo $aluno->agencia; else echo 'NAO';?></td>
      </tr>
      <tr>
      <tr style="background-color: rgb(113, 150, 216);">
        <td colspan="2"><font size="2"><b><font color="#ffffff">Orientador:</font></b></font></td>
        <td colspan="2"><font size="2"><b><font color="#ffffff">Linha de Pesquisa:</font></b></font></td>
      </tr>
      <tr>
        <td colspan="2"><?php echo verProfessor($aluno->orientador);?><font size="2"></font></td>
        <td colspan="2"><?php echo verLinhaPesquisa($aluno->area, 1); ?></td>
      </tr>
      <tr>
        <td  colspan="4"><hr style="width: 100%; height: 2px;"></td>
      </tr>
      <tr style="background-color: #7196d8;">
        <td style="width: 100%;" colspan="4"><font size="2"><font color="#FF0000">*</font> <b><font color="#FFFFFF">Endere&#231;o:</font></b></font></td>
      </tr>
      <tr>
        <td style="width: 100%;" colspan="4"><input maxlength="140" size="60" name="endereco" class="inputbox" value="<?php echo $aluno->endereco;?>"></td>
      </tr>
      <tr style="background-color: #7196d8;">
        <td style="width: 30%;"><font size="2"><font color="#FF0000">*</font> <b><font color="#FFFFFF"> Bairro:</font></b></font></td>
        <td style="width: 22%;"><font size="2"><font color="#FF0000">*</font> <b><font color="#FFFFFF">Cidade:</font></b></font></td>
        <td style="width: 28%;"><font size="2"><font color="#FF0000">*</font> <b><font color="#FFFFFF">UF:</font></b></font></td>
        <td style="width: 20%;"><font size="2"><font color="#FF0000">*</font> <b><font color="#FFFFFF">CEP:</font></b></font></td>
      </tr>
      <tr>
        <td><input maxlength="40" size="22" name="bairro" class="inputbox" value="<?php echo $aluno->bairro;?>"></td>
        <td><input maxlength="30" size="20" name="cidade" class="inputbox" value="<?php echo $aluno->cidade;?>"></td>
        <td>
<select name="uf" class="inputbox">
<option value="" <?php if ($aluno->uf == "") echo 'SELECTED';?>></option>
<option value="Outro" <?php if ($aluno->uf == "Outro") echo 'SELECTED';?>>Outro</option>
<option value="AC" <?php if ($aluno->uf == "AC") echo 'SELECTED';?>>AC</option>
<option value="AL" <?php if ($aluno->uf == "AL") echo 'SELECTED';?>>AL</option>
<option value="AM" <?php if ($aluno->uf == "AM") echo 'SELECTED';?>>AM</option>
<option value="AP" <?php if ($aluno->uf == "AP") echo 'SELECTED';?>>AP</option>
<option value="BA" <?php if ($aluno->uf == "BA") echo 'SELECTED';?>>BA</option>
<option value="CE" <?php if ($aluno->uf == "CE") echo 'SELECTED';?>>CE</option>
<option value="DF" <?php if ($aluno->uf == "DF") echo 'SELECTED';?>>DF</option>
<option value="ES" <?php if ($aluno->uf == "ES") echo 'SELECTED';?>>ES</option>
<option value="GO" <?php if ($aluno->uf == "GO") echo 'SELECTED';?>>GO</option>
<option value="MA" <?php if ($aluno->uf == "MA") echo 'SELECTED';?>>MA</option>
<option value="MG" <?php if ($aluno->uf == "MG") echo 'SELECTED';?>>MG</option>
<option value="MS" <?php if ($aluno->uf == "MS") echo 'SELECTED';?>>MS</option>
<option value="MT" <?php if ($aluno->uf == "MT") echo 'SELECTED';?>>MT</option>
<option value="PA" <?php if ($aluno->uf == "PA") echo 'SELECTED';?>>PA</option>
<option value="PB" <?php if ($aluno->uf == "PB") echo 'SELECTED';?>>PB</option>
<option value="PE" <?php if ($aluno->uf == "PE") echo 'SELECTED';?>>PE</option>
<option value="PI" <?php if ($aluno->uf == "PI") echo 'SELECTED';?>>PI</option>
<option value="PR" <?php if ($aluno->uf == "PR") echo 'SELECTED';?>>PR</option>
<option value="RJ" <?php if ($aluno->uf == "RJ") echo 'SELECTED';?>>RJ</option>
<option value="RN" <?php if ($aluno->uf == "RN") echo 'SELECTED';?>>RN</option>
<option value="RO" <?php if ($aluno->uf == "RO") echo 'SELECTED';?>>RO</option>
<option value="RR" <?php if ($aluno->uf == "RR") echo 'SELECTED';?>>RR</option>
<option value="RS" <?php if ($aluno->uf == "RS") echo 'SELECTED';?>>RS</option>
<option value="SC" <?php if ($aluno->uf == "SC") echo 'SELECTED';?>>SC</option>
<option value="SE" <?php if ($aluno->uf == "SE") echo 'SELECTED';?>>SE</option>
<option value="SP" <?php if ($aluno->uf == "SP") echo 'SELECTED';?>>SP</option>
<option value="TO" <?php if ($aluno->uf == "TO") echo 'SELECTED';?>>TO</option>
</select>
        <td><input maxlength="10" size="10" name="cep" class="inputbox" value="<?php echo $aluno->cep;?>"></td>
</td>
      </tr>
      <tr style="background-color: #7196d8;">
        <td><font size="2"><font color="#FF0000">*</font> <b><font color="#FFFFFF">Data de Nascimento:</font></b></font></td>
        <td><font size="2"><font color="#FF0000">*</font> <b><font color="#FFFFFF">Sexo:</font></b></font></td>
        <td><font size="2"><font color="#FF0000">*</font> <b><font color="#FFFFFF">Nacionalidade:</font></b></font></td>
        <td><font size="2"><font color="#FF0000">*</font> <b><font color="#FFFFFF">Estado Civil:</font></b></font></td>
      </tr>
      <tr>
        <td><input maxlength="10" size="10" name="datanascimento" class="inputbox" value="<?php echo $aluno->datanascimento;?>"></td>
        <td>
        <select name="sexo" class="inputbox">
        <option value="" <?php if ($aluno->sexo == "") echo 'SELECTED';?>></option>
        <option value="M" <?php if ($aluno->sexo == "M") echo 'SELECTED';?>>Masculino</option>
        <option value="F" <?php if ($aluno->sexo == "F") echo 'SELECTED';?>>Feminino</option>
        </select></td>
        <td><input name="nacionalidade" value="1" type="radio" <?php if ($aluno->nacionalidade == 1) echo 'CHECKED';?>><font size="2">Brasileira</font><input name="nacionalidade" value="2" type="radio" <?php if ($aluno->nacionalidade == 2) echo 'CHECKED';?>><font size="2">Estrangeira</font>
        <br /> Pa&#237;s: <input maxlength="20" size="20" name="pais" class="inputbox" value="<?php echo $aluno->pais;?>"></td>

        <td><input maxlength="12" size="10" name="estadocivil" class="inputbox" value="<?php echo $aluno->estadocivil;?>"></td>
      </tr>
      <tr>
        <td colspan="4"><font color="#FF0000">*</font> <font size="2"><b>Os campos CPF, RG, &#211;rg&#227;o Expedidor e Data de expedi&#231;&#227;o s&#227;o obrigat&#243;rios para candidatos com nacionalidade Brasileira:</b></font></td>
      </tr>

      <tr style="background-color: #7196d8;">
        <td><font size="2"><b><font color="#FFFFFF">CPF:</font></b></font></td>
        <td><font size="2"><b><font color="#FFFFFF">RG:</font></b></font></td>
        <td><font size="2"><b><font color="#FFFFFF">&#211;rg&#227;o Expedidor:</font></b></font></td>
        <td><font size="2"><b><font color="#FFFFFF">Data de expedi&#231;&#227;o:</font></b></font></td>
      </tr>
      <tr>
        <td><input maxlength="14" size="14" name="cpf" class="inputbox" value="<?php echo $aluno->cpf;?>"></td>
        <td><input maxlength="10" size="15" name="rg" class="inputbox" value="<?php echo $aluno->rg;?>"></td>
        <td><input maxlength="10" size="10" name="orgaoexpedidor" class="inputbox" value="<?php echo $aluno->orgaoexpeditor;?>"></td>
        <td><input maxlength="10" size="10" name="dataexpedicao" class="inputbox" value="<?php echo $aluno->dataexpedicao;?>"></td>
      </tr>
      <tr>
        <td style="width: 100%;" colspan="4"><font size="2"><font color="#FF0000"></font> <br><b>Telefones:</b></font></td>
      </tr>
      <tr style="background-color: #7196d8;">
        <td><font size="2"><font color="#FF0000">*</font> <b><font color="#FFFFFF">Telefone de Contato:</font></b></font></td>
        <td><font size="2"><font color="#FF0000"></font> <b><font color="#FFFFFF">Telefone Alternativo 1:</font></b></font></td>
        <td colspan="2"><font size="2"><b><font color="#FFFFFF">Telefone Alternativo 2:</font></b></font></td>
      </tr>
      <tr>
        <td><input maxlength="12" size="15" name="telresidencial" class="inputbox" value="<?php echo $aluno->telresidencial;?>"></td>
        <td><input maxlength="12" size="15" name="telcomercial" class="inputbox" value="<?php echo $aluno->telcomercial;?>"></td>
        <td colspan="2"><input maxlength="12" size="15" name="telcelular" class="inputbox" value="<?php echo $aluno->telcelular;?>"></td>
      </tr>
      <tr>
        <td style="width: 100%;" colspan="4"><font size="2"><font color="#FF0000"></font> <br><b>Filia&#231;&#227;o:</b></font></td>
      </tr>
      <tr style="background-color: #7196d8;">
        <td colspan="2"><font size="2"><b><font color="#FFFFFF">Nome do Pai:</font></b></font></td>
        <td colspan="2"><font size="2"><b><font color="#FFFFFF">Nome da M&#227;e:</font></b></font></td>
      </tr>
      <tr>
        <td colspan="2"><input maxlength="40" size="30" name="nomepai" class="inputbox" value="<?php echo $aluno->nomepai;?>"></td>
        <td colspan="2"><input maxlength="40" size="30" name="nomemae" class="inputbox" value="<?php echo $aluno->nomemae;?>"></td>
      </tr>
      <tr>
        <td style="width: 100%;" colspan="4"><font size="2"><br><font color="#FF0000">*</font> <b>Curso de Gradua&#231;&#227;o:</b></font></td>
      </tr>
       <tr  style="background-color: #7196d8;">
        <td colspan="2"><font size="2" color="#FFFFFF"><b>Curso:</b></font></td>
        <td colspan="2"><font size="2" color="#FFFFFF"><b>Institui&#231;&#227;o:</b></font></td>
       </tr>
       <tr>
        <td colspan="2"><input maxlength="100" size="50" name="cursograd" class="inputbox" value="<?php echo $aluno->cursograd;?>"></td>
        <td colspan="2"><input maxlength="100" size="50" name="instituicaograd" class="inputbox" value="<?php echo $aluno->instituicaograd;?>"></td>
       </tr>
       <tr style="background-color: #7196d8;">
        <td colspan="2"><font size="2" color="#FFFFFF"><b>Coeficiente Rendimento:</b></font></td>
        <td colspan="2"><font size="2" color="#FFFFFF"><b>Ano Egresso:</b></font></td>
       </tr>
       <tr>
        <td colspan="2"><input maxlength="5" size="5" name="crgrad" class="inputbox" value="<?php echo $aluno->crgrad;?>"></td>
        <td colspan="2"><input maxlength="4" size="4" name="egressograd" class="inputbox" value="<?php echo $aluno->egressograd;?>"></td>
       </tr>
    </tbody>
  </table>
       <input name='idAluno' type='hidden' value='<?php echo $aluno->id;?>'>
       <input name='task' type='hidden' value='salvarEditar'>

</form>
<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
    <?php
    }

 //////////////////////////////////////////////////////////////

/////////////////////////////////////

function salvarEdicao($idAluno){

    $database	=& JFactory::getDBO();

    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $uf = $_POST['uf'];
    $cep = $_POST['cep'];
    $sexo = $_POST['sexo'];
    $estadocivil = $_POST['estadocivil'];
    $datanascimento = $_POST['datanascimento'];
    $nacionalidade = $_POST['nacionalidade'];
    if($nacionalidade == 1)
       $pais = "Brasil";
    else
       $pais = $_POST['pais'];
    $cpf = $_POST['cpf'];
    $rg = $_POST['rg'];
    $orgaoexpedidor = $_POST['orgaoexpedidor'];
    $dataexpedicao = $_POST['dataexpedicao'];
    $telresidencial = $_POST['telresidencial'];
    $telcomercial = $_POST['telcomercial'];
    $telcelular = $_POST['telcelular'];
    $nomepai = $_POST['nomepai'];
    $nomemae = $_POST['nomemae'];
    $cursograd = $_POST['cursograd'];
    $instituicaograd = $_POST['instituicaograd'];
    $egressograd = $_POST['egressograd'];
    $crgrad = $_POST['crgrad'];

    $sql = "UPDATE #__aluno
         SET
           email = '$email',
           endereco = '$endereco',
           bairro = '$bairro',
           cidade = '$cidade',
           uf = '$uf',
           cep = '$cep',
           datanascimento = '$datanascimento',
           sexo = '$sexo',
           nacionalidade = '$nacionalidade',
           estadocivil = '$estadocivil',
           cpf = '$cpf',
           rg = '$rg',
           orgaoexpeditor = '$orgaoexpedidor',
           dataexpedicao = '$dataexpedicao',
           telresidencial = '$telresidencial',
           telcomercial = '$telcomercial',
           telcelular = '$telcelular',
           nomepai = '$nomepai',
           nomemae = '$nomemae',
           cursograd = '$cursograd',
           instituicaograd = '$instituicaograd',
           egressograd = '$egressograd',
           crgrad = '$crgrad',
           pais =  '$pais'
         WHERE id = $idAluno";

    $database->setQuery($sql);
    $database->Query();
    JFactory::getApplication()->enqueueMessage(JText::_('Dados salvos com sucesso!!!'));
}


?>


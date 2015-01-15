<?php

function reprovarCandidato($idCandidato){

    $database	=& JFactory::getDBO();

    $sql = "UPDATE #__candidatos SET resultado = 0 WHERE id = $idCandidato";
    $database->setQuery( $sql );
    $database->Query();
    JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));

}

function salvarAprovacao($idCandidato){

    $database	=& JFactory::getDBO();

    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
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
    $curso = $_POST['curso'];
    $regime = $_POST['regime'];
    $bolsista = $_POST['bolsista'];
    $agencia = $_POST['agencia'];
    $orientador = $_POST['orientador'];
    $area = $_POST['area'];
    $anoingresso = $_POST['anoingresso'];
    $cursograd = $_POST['cursograd'];
    $instituicaograd = $_POST['instituicaograd'];
    $egressograd = $_POST['egressograd'];
    $crgrad = $_POST['crgrad'];
    $senha = md5($matricula);
    
    $sql = "INSERT INTO #__aluno(`nome`, `email`, `senha`, `matricula`, `orientador`, `area`, `curso`, `endereco`, `bairro`, `cidade`, `uf`, `cep`, `datanascimento`, `sexo`, `nacionalidade`, `estadocivil`, `cpf`, `rg`, `orgaoexpeditor`, `dataexpedicao`, `telresidencial`, `telcomercial`, `telcelular`, `nomepai`, `nomemae`, `regime`, `bolsista`, `agencia`, `pais`, `anoingresso`, cursograd, instituicaograd, egressograd, crgrad) VALUES ('$nome ', '$email', '$senha', '$matricula', $orientador, $area, '$curso', '$endereco', '$bairro', '$cidade', '$uf', '$cep', '$datanascimento', '$sexo', '$nacionalidade', '$estadocivil', '$cpf', '$rg', '$orgaoexpedidor', '$dataexpedicao', '$telresidencial', '$telcomercial', '$telcelular', '$nomepai', '$nomemae', '$regime', '$bolsista', '$agencia', '$pais', $anoingresso, '$cursograd', '$instituicaograd', $egressograd, '$crgrad')";
    $database->setQuery($sql);
    $database->Query();
    
    $sql = "UPDATE #__candidatos SET resultado = 1 WHERE id = $idCandidato";
    $database->setQuery( $sql );
    $database->Query();
    
//    $sucesso = CreateNewUser($nome, $email, $email, $senha, $registerDate = NULL, $usertype = 'Registered', 0, 1, 10);
    $sucesso = criarUsuarioJoomla($nome, $email, $matricula);
    
    if($sucesso)
      JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
    else
      JError::raiseWarning( 100, 'ERRO: Opera&#231;&#227;o de Cadastro de Novo Aluno Falhou.' );
}

function criarUsuarioJoomla($nome, $email, $matricula){

    $db	=& JFactory::getDBO();
    
    $senha = md5($matricula);

    // Insert record into users
    $sql1 = "INSERT INTO ".$db->nameQuote('#__users')." SET
    ".$db->nameQuote('name')."            = ".$db->quote($nome).",
    ".$db->nameQuote('username')."        = ".$db->quote($email).",
    ".$db->nameQuote('email')."           = ".$db->quote($email).",
    ".$db->nameQuote('password')."        = ".$db->quote($senha).",
    ".$db->nameQuote('usertype')."        = 'Registered',
    ".$db->nameQuote('block')."           = 0,
    ".$db->nameQuote('sendEmail')."       = 1,
    ".$db->nameQuote('registerDate')."    = '".date('Y-m-d H:i:s')."',
    ".$db->nameQuote('lastvisitDate')."   = ".$db->quote('0000-00-00 00:00:00').",
    ".$db->nameQuote('activation')."      = '',
    ".$db->nameQuote('params')."          = ''
    ";

    $db->setQuery($sql1);
    $sucesso1 = $db->query();
    
    $user_id = $db->insertid();
    
    // Insert record into #__user_usergroup_map
    $sql2 = "INSERT INTO ".$db->nameQuote('#__user_usergroup_map')." SET
    ".$db->nameQuote('group_id')."        = 10,
    ".$db->nameQuote('user_id')."         = ".$db->quote($user_id)."
    ";
    $db->setQuery($sql2);
    $sucesso2 = $db->query();
    
    return ($sucesso1 || $sucesso2);

}

//////////////////////////////////////////////////////////////

function avaliarCandidatos($periodo)
    {

    $Itemid = JRequest::getInt('Itemid', 0);
    $database	=& JFactory::getDBO();
    
    $database->setQuery("SELECT DISTINCT periodo from #__candidatos ORDER BY periodo DESC");
    $periodosLista = $database->loadObjectList();

    if($periodo == "")
       $periodo = $periodosLista[0]->periodo;
       
    $sql = "SELECT * FROM #__candidatos WHERE fim is not NULL AND periodo = '$periodo' ORDER BY id";
    $database->setQuery( $sql );
    $candidatos = $database->loadObjectList();

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";

	$cursoDesejado = array (1 => "mestrado",2 => "doutorado");
	$resultado = array (NULL=> "components/com_portalsecretaria/images/desistente.png", 0 => "components/com_portalsecretaria/images/desligado.gif",1 => "components/com_portalsecretaria/images/ativo.png");
	$resultadoTitle = array (NULL=> "Nao avaliado", 0 => "Reprovado",1 => "Aprovado");
	$regimeDedicacao = array (1 => "Integral",2 => "Parcial");

    $database->setQuery("SELECT count(*) as tot from #__candidatos WHERE fim is NULL AND periodo = '$periodo'");
	$incompletos = $database->loadObjectList();


	?>
<script language="JavaScript">
<!--
function confirmarAprovacao(form, idCandidato)
{
    var confirmar = confirm('Confirmar a aprovacao do candidato?');
    if(confirmar == true){
        form.task.value='aprovar';
        form.idcandidato.value=idCandidato;
        form.submit();
    }
}

function confirmarReprovacao(form, idCandidato)
{
    var confirmar = confirm('Confirmar a reprovacao do candidato?');
    if(confirmar == true){
        form.task.value='reprovar';
        form.idcandidato.value=idCandidato;
        form.submit();
    }
}

//-->
</script>

	<link rel="stylesheet" href="components/com_inscricaoppgi/estilo.css" type="text/css" />

        <script type="text/javascript" src="components/com_inscricaoppgi/jquery.js"></script>
        <script type="text/javascript" src="components/com_inscricaoppgi/jquery.tablesorter.js"></script>

	<script type="text/javascript">
	$(function() {
		$("#tablesorter-imasters").tablesorter();

	});
	</script>
    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-back">
           		<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
		</div>
    <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-contact"><h2>Avalia&#231;&#227;o de candidatos do PPGI</h2></div>
    </div></div>
	<table><tr><td  width="50%"><p>Selecione o periodo:

    <select name="periodo" class="inputbox">

	        <?php


	             foreach($periodosLista as $periodoLista){

              ?>   <option value="<?php echo $periodoLista->periodo;?>"
              <?php
                   if($periodo == $periodoLista->periodo) echo 'SELECTED';

              ?>
              > <?php echo $periodoLista->periodo;?></option>
	          <?php
	               }

              ?>
    </select>
    <input type="submit" value="Buscar">
    </td>
    </tr></table>
    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
	<p>Existe(m) <b><?php echo sizeof($candidatos);?></b> candidato(s) com inscri&#231;&#227;o finalizada | Existe(m) <b><?php echo $incompletos[0]->tot;?></b> candidato(s) com inscri&#231;&#227;o ainda incompleta e n&#227;o finalizada.</p>
	<p>Legenda: <img border='0' src='components/com_portalsecretaria/images/desistente.png' width='16' height='16' title='Nao avaliado'> - N&#227;o Julgado | <img border='0' src='components/com_portalsecretaria/images/desligado.gif' width='16' height='16' title='Reprovado'> - Reprovado | <img border='0' src='components/com_portalsecretaria/images/ativo.png' width='16' height='16' title='Aprovado'> - Aprovado</p>
    <table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
	<thead>
      <tr bgcolor="#002666">
        <th width="3%" align="center"></th>
        <th width="3%" align="center"></th>
        <th width="3%" align="center"></th>
        <th width="3%" align="center"><font color="#FFC000"></font></th>
        <th width="8%" align="center"><font color="#FFC000">A&#231;&#227;o</font></th>
    	<th width="7%" align="center"><font color="#FFC000">#Insc</font></th>
        <th width="37%" align="center"><font color="#FFC000">Nome</font></th>
        <th width="9%" align="center"><font color="#FFC000">E-mail</font></th>
        <th width="9%" align="center"><font color="#FFC000">Curso</font></th>
        <th width="10%" align="center"><font color="#FFC000">Regime</font></th>
        <th width="12%" align="center"><font color="#FFC000">Linha</font></th>
      </tr>
     </thead>
     <tbody>
	<?php

	$linhaPesquisa = array (1 => "bd_ri",2 => "se_es",3 => "ia",4 => "visao",5 => "redes",6 => "otimizacao");

	$i = 0;
	foreach( $candidatos as $candidato )
	{
		$i = $i + 1;
		if ($i % 2)
		 {
		    echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
		 }
		else
		 {
		    echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
	  	 }
	?>

		<td width='16'><div align="center">
            <a href="index.php?option=com_inscricaoppgi&Itemid=176&task=imprimir&idCandidato=<?php echo $candidato->id;?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;"><img border='0' src='images/b_view.png' width='16' height='16' title='Visualizar Documentos de Inscri&#231;&#227;o'></a>
			</a>
		</div></td>
		<td width='16'><div align="center">
        <?php

             $idCarta = cartaRecomendacao(1, $candidato->id);
             if($idCarta){
        ?>
            <a href="index.php?option=com_inscricaoppgi&Itemid=176&task=imprimirCartas&idCandidato=<?php echo $candidato->id;?>&idCarta=<?php echo $idCarta;?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;"><img border='0' src='components/com_portalsecretaria/images/emailButton.png' width='16' height='16' title='Visualizar Carta de Recomenda&#231;&#227;o 1'></a>			</a>
        <?php } ?>
		</div></td>
		<td width='16'><div align="center">
        <?php

             $idCarta = cartaRecomendacao(2, $candidato->id);
             if($idCarta){
        ?>
            <a href="index.php?option=com_inscricaoppgi&Itemid=176&task=imprimirCartas&idCandidato=<?php echo $candidato->id;?>&idCarta=<?php echo $idCarta;?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;"><img border='0' src='components/com_portalsecretaria/images/emailButton.png' width='16' height='16' title='Visualizar Carta de Recomenda&#231;&#227;o 2'></a>			</a>
        <?php } ?>
		</div></td>
		<td width='16'><div align="center">
            <img border='0' src='<?php echo $resultado[$candidato->resultado];?>' width='16' height='16' title='<?php echo $resultadoTitle[$candidato->resultado];?>'></a>
			</a>
		</div></td>
		<td width='16'><div align="center">
            <?php if($candidato->resultado == NULL) {?>
               <a href="javascript:confirmarAprovacao(document.form, <?php echo $candidato->id;?>)"><img border='0' src='components/com_portalsecretaria/images/aprovar.png' width='12' height='12' title='aprovar'></a>
               <a href="javascript:confirmarReprovacao(document.form, <?php echo $candidato->id;?>)"><img border='0' src='components/com_portalsecretaria/images/reprovar.gif' width='16' height='16' title='reprovar'></a>
			<?php } ?>
		</div></td>

		<td><?php echo $candidato->id;?></td>
		<td><?php echo $candidato->nome;?></td>
		<td><img border='0' src='components/com_portalsecretaria/images/emailButton.png' width='16' height='16' title='<?php echo $candidato->email;?>'></td>
		<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $cursoDesejado[$candidato->cursodesejado];?>.gif' title='<?php echo $cursoDesejado[$candidato->cursodesejado];?>'> </td>
		<td><?php echo $regimeDedicacao[$candidato->regime];?></td>
		<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $linhaPesquisa[$candidato->linhapesquisa];?>.gif' title='<?php echo verLinhaPesquisa($candidato->linhapesquisa, 1);?>'> </td>
	</tr>
	<?php
	}
         ?>
     </tbody>
	</table>
    <input name='task' type='hidden' value='avaliarcandidatos'>
    <input name='ano' type='hidden' value='<?php echo substr($periodo,0,4);?>'>
    <input name='idcandidato' type='hidden' value=''>
    </form>

     <?php
 }


 ////////////////////////////////////////////////////////////////////
function aprovarCandidato($idCandidato, $ano){

    $Itemid = JRequest::getInt('Itemid', 0);
    $database	=& JFactory::getDBO();

    $sql = "SELECT * FROM #__candidatos WHERE id = $idCandidato";
    $database->setQuery( $sql );
    $candidatos = $database->loadObjectList();
    $aluno = $candidatos[0];

	$sexo = array ('M' => "Masculino", 'F' => "Feminino");
	$cursoDesejado = array (1 => "Mestrado",2 => "Doutorado",3 => "Especial");
	$regimeDedicacao = array (1 => "Integral",2 => "Parcial");

	?>

<script language="JavaScript">
<!--

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

function ValidateformCadastro(formCadastro)
{

      if(IsEmpty(formCadastro.matricula))
   {
      alert(unescape('O campo Matricula deve ser preenchido.'))
      formCadastro.matricula.focus();
      return false;
   }

   if (IsEmpty (formCadastro.curso))
   {
     alert('O campo Curso deve ser preenchido.')
     formCadastro.cursodesejado[0].focus();
     return (false);
   }

   if (IsEmpty (formCadastro.anoingresso))
   {
     alert('O campo Ano de Ingresso deve ser preenchido.')
     formCadastro.anoingresso.focus();
     return (false);
   }


   if (!radio_button_checker(formCadastro.regime))
   {
     alert('O campo Regime de Dedicacao deve ser preenchido.')
     formCadastro.regime[0].focus();
     return (false);
   }

      if (!radio_button_checker(formCadastro.bolsista))
   {
     alert('O campo Bolsa deve ser preenchido.')
     formCadastro.solicitabolsa[0].focus();
     return (false);
   }

   if(formCadastro.bolsista[0].checked && IsEmpty(formCadastro.agencia))
   {
      alert('O campo Agencia deve ser preenchido quando o aluno recebe bolsa.')
      formCadastro.agencia.focus();
      return false;
   }

    if(IsEmpty(formCadastro.orientador))
   {
      alert('O campo Orientador deve ser preenchido.')
      formCadastro.orientador.focus();
      return false;
   }

   if(IsEmpty(formCadastro.area))
   {
      alert('O campo Linha de Pesquisa deve ser preenchido.')
      formCadastro.area.focus();
      return false;
   }

return true;

}
//-->
</script>

<p align="center"><h3>Formul&#225;rio de Cadastro de Alunos no Mestrado/Doutorado no PPGI/UFAM</h3></p>

<hr style="width: 100%; height: 2px;">
  <b>Como proceder: </b>
  <ul>
   <li>Preencha todos os campos com seus dados pessoais <font color="#FF0000">(* Campos Obrigat&#243;rios)</font>.</li>
   </ul>
   <hr style="width: 100%; height: 2px;">
   <form method="post" name="formCadastro" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" method="post" onsubmit="javascript:return ValidateformCadastro(this)">
   <table border="0" cellpadding="1" cellspacing="2" width="100%">
   <tbody>
   <tr style="background-color: #7196d8;"><td colspan="2"><font size="2"><b><font color="#FFFFFF">Nome:</font></b></font></td>
        <td colspan="2"><font size="2"><font color="#FF0000">*</font> <b><font color="#FFFFFF">Matr&#237;cula:</font></b></font></td>
      </tr>
      <tr>
        <td colspan="2"><?php echo $aluno->nome;?></td>
        <td colspan="2"><input maxlength="15" size="15" name="matricula" class="inputbox" value=""></td>
      </tr>

      <tr style="background-color: #7196d8;">
        <td style="width: 100%;" colspan="4"><font size="2"><b><font color="#FFFFFF">Email:</font></b></font></td>
      </tr>
      <tr>
        <td style="width: 100%;" colspan="4"><?php echo $aluno->email;?></td>
      </tr>
      <tr style="background-color: #7196d8;">
        <td style="width: 100%;" colspan="4"><font size="2"><b><font color="#FFFFFF">Endere&#231;o:</font></b></font></td>
      </tr>
      <tr>
        <td style="width: 100%;" colspan="4"><?php echo $aluno->endereco;?></td>
      </tr>
      <tr style="background-color: #7196d8;">
        <td style="width: 30%;"><font size="2"><b><font color="#FFFFFF"> Bairro:</font></b></font></td>
        <td style="width: 22%;"><font size="2"><b><font color="#FFFFFF">Cidade:</font></b></font></td>
        <td style="width: 28%;"><font size="2"><b><font color="#FFFFFF">UF:</font></b></font></td>
        <td style="width: 20%;"><font size="2"><b><font color="#FFFFFF">CEP:</font></b></font></td>
      </tr>
      <tr>
        <td><?php echo $aluno->bairro;?></td>
        <td><?php echo $aluno->cidade;?></td>
        <td><?php echo $aluno->uf;;?></td>
        <td><?php echo $aluno->cep;?></td>
      </tr>
      <tr style="background-color: #7196d8;">
        <td><font size="2"><b><font color="#FFFFFF">Data de Nascimento:</font></b></font></td>
        <td><font size="2"><b><font color="#FFFFFF">Sexo:</font></b></font></td>
        <td><font size="2"><b><font color="#FFFFFF">Nacionalidade:</font></b></font></td>
        <td><font size="2"><b><font color="#FFFFFF">Estado Civil:</font></b></font></td>
      </tr>
      <tr>
        <td><?php echo $aluno->datanascimento;?></td>
        <td><?php echo $sexo[$aluno->sexo];?></td>
        <td><?php echo $aluno->pais;?></td>
        <td><?php echo $aluno->estadocivil;?></td>
      </tr>
      <tr style="background-color: #7196d8;">
        <td><font size="2"><b><font color="#FFFFFF">CPF:</font></b></font></td>
        <td><font size="2"><b><font color="#FFFFFF">RG:</font></b></font></td>
        <td><font size="2"><b><font color="#FFFFFF">&#211;rg&#227;o Expedidor:</font></b></font></td>
        <td><font size="2"><b><font color="#FFFFFF">Data de expedi&#231;&#227;o:</font></b></font></td>
      </tr>
      <tr>
        <td><?php echo $aluno->cpf;?></td>
        <td><?php echo $aluno->rg;?></td>
        <td><?php echo $aluno->orgaoexpedidor;?></td>
        <td><?php echo $aluno->dataexpedicao;?></td>
      </tr>
      <tr>
        <td style="width: 100%;" colspan="4"><font size="2"><br><b>Telefones:</b></font></td>
      </tr>
      <tr style="background-color: #7196d8;">
        <td><font size="2"><b><font color="#FFFFFF">Telefone de Contato:</font></b></font></td>
        <td><font size="2"><font color="#FF0000"></font> <b><font color="#FFFFFF">Telefone Alternativo 1:</font></b></font></td>
        <td colspan="2"><font size="2"><b><font color="#FFFFFF">Telefone Alternativo 2:</font></b></font></td>
      </tr>
      <tr>
        <td><?php echo $aluno->telresidencial;?></td>
        <td><?php echo $aluno->telcomercial;?></td>
        <td colspan="2"><?php echo $aluno->telcelular;?></td>
      </tr>
      <tr>
        <td style="width: 100%;" colspan="4"><font size="2"> <br><b>Filia&#231;&#227;o:</b></font></td>
      </tr>
      <tr style="background-color: #7196d8;">
        <td colspan="2"><font size="2"><b><font color="#FFFFFF">Nome do Pai:</font></b></font></td>
        <td colspan="2"><font size="2"><b><font color="#FFFFFF">Nome da M&#227;e:</font></b></font></td>
      </tr>
      <tr>
        <td colspan="2"><?php echo $aluno->nomepai;?></td>
        <td colspan="2"><?php echo $aluno->nomemae;?></td>
      </tr>
      <tr>
        <td style="width: 100%;" colspan="4"><font size="2"><br><b>Curso de Gradua&#231;&#227;o:</b></font></td>
      </tr>
       <tr  style="background-color: #7196d8;">
        <td colspan="2"><font size="2" color="#FFFFFF"><b>Curso:</b></font></td>
        <td colspan="2"><font size="2" color="#FFFFFF"><b>Institui&#231;&#227;o:</b></font></td>
       </tr>
       <tr>
        <td colspan="2"><?php echo $aluno->cursograd;?></td>
        <td colspan="2"><?php echo $aluno->instituicaograd;?></td>
       </tr>
       <tr style="background-color: #7196d8;">
        <td colspan="2"><font size="2" color="#FFFFFF"><b>Coeficiente Rendimento:</b></font></td>
        <td colspan="2"><font size="2" color="#FFFFFF"><b>Ano Egresso:</b></font></td>
       </tr>
       <tr>
        <td colspan="2"><?php echo $aluno->crgrad;?></td>
        <td colspan="2"><?php echo $aluno->egressograd;?></td>
       </tr>
      <tr>
        <td style="width: 100%;" colspan="4"><font size="2"><br><b>Curso de P&#243;s-Gradua&#231;&#227;o:</b></font></td>
      </tr>
      <tr style="background-color: #7196d8;">
        <td><font size="2"><b><font color="#FFFFFF">Tipo de Aluno:</font></b></font></td>
        <td><font size="2"><b><font color="#FFFFFF">Ano de Ingresso:</font></b></font></td>
        <td colspan="2"><font size="2"><b><font color="#FFFFFF">Regime de Dedica&#231;&#227;o:</font></b></font></td>
      </tr>
      <tr>
        <td>
        <select name="curso" class="inputbox">
        <option value="" <?php if ($aluno->cursodesejado == "") echo 'SELECTED';?>></option>
        <option value="1" <?php if ($aluno->cursodesejado == "1") echo 'SELECTED';?>>Mestrado</option>
        <option value="2" <?php if ($aluno->cursodesejado == "2") echo 'SELECTED';?>>Doutorado</option>
        <option value="3" <?php if ($aluno->cursodesejado == "3") echo 'SELECTED';?>>Especial</option>
        </select></td>
        <td><input maxlength="4" size="4" name="anoingresso" class="inputbox" value="<?php echo $ano;?>" /></td>
        <td colspan="2"><input name="regime" value="1" type="radio" <?php if ($aluno->regime == 1) echo 'CHECKED';?>><font size="2">Integral</font><input name="regime" value="2" type="radio" <?php if ($aluno->regime == 2) echo 'CHECKED';?>><font size="2">Parcial</font></td>

      </tr>

<tr style="background-color: rgb(113, 150, 216);">
        <td colspan="2"><font size="2"><font  color="#ff0000"></font><span  style="font-weight: bold;"></span><b><font
 color="#ffffff">&nbsp;&Eacute; Bolsista?</font></b></font></td>
        <td colspan="2"><font size="2"><font
 color="#ff0000">*</font>&nbsp;<b><font
 color="#ffffff">Se sim, de qual ag&ecirc;ncia?</font></b></font></td>
      </tr>
      <tr>
      <td colspan="2"><input name="bolsista" value="SIM" type="radio"><font size="2">Sim</font><input name="bolsista" value="NAO" type="radio" ><font size="2">N&#227;o</font></td>
         <td colspan="2"><input maxlength="30" size="30" name="agencia" class="inputbox" value="" /></td>
      </tr>

      <tr style="background-color: rgb(113, 150, 216);">
        <td colspan="2"><font size="2"><b><font color="#ffffff">Orientador:</font></b></font></td>
        <td colspan="2"><font size="2"><b><font color="#ffffff">Linha de Pesquisa:</font></b></font></td>
      </tr>
      <tr>
        <td colspan="2">
        <select name="orientador" class="inputbox">
            <option value=""></option>
            <?php

                 $database->setQuery("SELECT * from #__professores WHERE ppgi = 1 ORDER BY nomeProfessor");
	             $professores = $database->loadObjectList();

	             foreach($professores as $professor){

            ?>
                <option value="<?php echo $professor->id;?>" ><?php echo $professor->nomeProfessor;?></option>
            <?php
	               }
            ?>
          </select>
        </td>
        <td colspan="2">
        <select name="area" class="inputbox">

        <option value="" <?php if ($aluno->linhapesquisa == "") echo 'SELECTED';?>></option>
            <?php

                 $database->setQuery("SELECT * from #__linhaspesquisa ORDER BY nome");
	             $linhas = $database->loadObjectList();

	             foreach($linhas as $linha){

            ?>
                <option value="<?php echo $linha->id;?>" <?php if($linha->id == $aluno->linhapesquisa) echo 'SELECTED'?>><?php echo $linha->nome;?></option>
            <?php
	               }
            ?>
        </select></td>
      </tr>

    </tbody>
  </table>
  <br><br>
  <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/template_css.css">
  <table border="0" cellpadding="0" cellspacing="2" width="100%">
    <tbody>
    <tr>
		<th id="cpanel" width="50%"><div class="icon" style='text-align: center;'><a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()"><img src="components/com_portalsecretaria/images/salvar.png" border="0" height="32" width="32"><br><b>Aprovar Candidatos</b></a></td>
		<th id="cpanel" width="50%"><div class="icon" style='text-align: center;'><a href="index.php?option=com_portalsecretaria&task=avaliarcandidatos"><img src="components/com_portalsecretaria/images/cancelar.png" border="0" height="32" width="32"><br><b>Cancelar</b></a></td>
	</tr>

    </tbody>
    </table>

    <input name='task' type='hidden' value='salvarAprovacao'>
    <input name='idcandidato' type='hidden' value='<?php echo $aluno->id;?>'>
    <input name='nome' type='hidden' value='<?php echo $aluno->nome;?>'>
    <input name='email' type='hidden' value='<?php echo $aluno->email;?>'>
    <input name='endereco' type='hidden' value='<?php echo $aluno->endereco;?>'>
    <input name='bairro' type='hidden' value='<?php echo $aluno->bairro;?>'>
    <input name='cidade' type='hidden' value='<?php echo $aluno->cidade;?>'>
    <input name='uf' type='hidden' value='<?php echo $aluno->uf;?>'>
    <input name='cep' type='hidden' value='<?php echo $aluno->cep;?>'>
    <input name='datanascimento' type='hidden' value='<?php echo $aluno->datanascimento;?>'>
    <input name='sexo' type='hidden' value='<?php echo $aluno->sexo;?>'>
    <input name='nacionalidade' type='hidden' value='<?php echo $aluno->nacionalidade;?>'>
    <input name='pais' type='hidden' value='<?php echo $aluno->pais;?>'>
    <input name='estadocivil' type='hidden' value='<?php echo $aluno->estadocivil;?>'>
    <input name='cpf' type='hidden' value='<?php echo $aluno->cpf;?>'>
    <input name='rg' type='hidden' value='<?php echo $aluno->rg;?>'>
    <input name='orgaoexpedidor' type='hidden' value='<?php echo $aluno->orgaoexpedidor;?>'>
    <input name='dataexpedicao' type='hidden' value='<?php echo $aluno->dataexpedicao;?>'>
    <input name='telresidencial' type='hidden' value='<?php echo $aluno->telresidencial;?>'>
    <input name='telcomercial' type='hidden' value='<?php echo $aluno->telcomercial;?>'>
    <input name='telcelular' type='hidden' value='<?php echo $aluno->telcelular;?>'>
    <input name='nomepai' type='hidden' value='<?php echo $aluno->nomepai;?>'>
    <input name='nomemae' type='hidden' value='<?php echo $aluno->nomemae;?>'>
    <input name='ano' type='hidden' value='<?php echo $ano;?>'>
    <input name='cursograd' type='hidden' value='<?php echo $aluno->cursograd;?>'>
    <input name='instituicaograd' type='hidden' value='<?php echo $aluno->instituicaograd;?>'>
    <input name='egressograd' type='hidden' value='<?php echo $aluno->egressograd;?>'>
    <input name='crgrad' type='hidden' value='<?php echo $aluno->crgrad;?>'>
</form>

    <?php
    }
 ////////////////////////////////////////////////////////////////////
function cartaRecomendacao($ordem, $idCandidato){

	$database	=& JFactory::getDBO();

    $sql = "SELECT * FROM #__recomendacoes WHERE idCandidato = $idCandidato AND dataEnvio <> '0000-00-00 00:00:00'";
    $database->setQuery( $sql );
    $recomendacoes = $database->loadObjectList();
    return($recomendacoes[$ordem - 1]->id);
}

//////////////////////////////////////////////////////////////

?>

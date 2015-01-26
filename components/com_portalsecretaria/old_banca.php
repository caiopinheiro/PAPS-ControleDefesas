<?php

///////////////////////////////////////////////////////////////

function registrarBanca($aluno) {

	$Itemid = JRequest :: getInt('Itemid', 0);
	$database = & JFactory :: getDBO();

	$curso = array (
		1 => "Mestrado",
		2 => "Doutorado",
		3 => "Especial"
	);
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

function VerificaData(digData)
{
        var bissexto = 0;
        var data = digData;
        var tam = data.length;
        if (tam == 10)
        {
                var ano = data.substr(0,4)
                var mes = data.substr(5,2)
                var dia = data.substr(8,2)
                if ((ano > 1900)||(ano < 2500))
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

function ValidateformBanca(formBanca)
{

   if(!IsEmpty(formBanca.conceitoExameProf)){
		if(IsEmpty(formBanca.idiomaExameProf))
		{
			alert(unescape('O campo Idioma do Exame de Profici\u00eancia n\u00e3o foi preenchido.'))
			formBanca.idiomaExameProf.focus();
			return false;
		}
		if(IsEmpty(formBanca.dataExameProf))
		{
			alert(unescape('O campo Data do Exame de Profici\u00eancia n\u00e3o foi preenchido.'))
			formBanca.dataExameProf.focus();
			return false;
		}

	}
	if(!IsEmpty(formBanca.conceitoQual2)){
		if(IsEmpty(formBanca.tituloQual2))
		{
			alert(unescape('O campo T\u00edtulo do Exame de Qualifica\u00e7\u00e3o foi preenchido incorretamente.'))
			formBanca.tituloQual2.focus();
			return false;
		}
		if(IsEmpty(formBanca.dataQual2))
		{
			alert(unescape('O campo Data do Exame de Qualifica\u00e7\u00e3o foi preenchido incorretamente.'))
			formBanca.dataQual2.focus();
			return false;
		}
		if(IsEmpty(formBanca.horarioQual2))
		{
			alert(unescape('O campo Hor\u00e1rio do Exame de Qualifica\u00e7\u00e3o foi preenchido incorretamente.'))
			formBanca.horarioQual2.focus();
			return false;
		}
		if(IsEmpty(formBanca.localQual2))
		{
			alert(unescape('O campo Local do Exame de Qualifica\u00e7\u00e3o foi preenchido incorretamente.'))
			formBanca.localQual2.focus();
			return false;
		}
		
		
	}

	if(!IsEmpty(formBanca.conceitoTese)){
		if(IsEmpty(formBanca.numDefesa))
		{
			alert(unescape('O campo N\u00famero da Defesa deve ser preenchido.'))
			formBanca.numDefesa.focus();
			return false;
		}

		if(IsEmpty(formBanca.tituloTese))
		{
			alert(unescape('O campo T\u00edtulo da Disserta\u00e7\u00e3o/Tese foi preenchido incorretamente.'))
			formBanca.tituloTese.focus();
			return false;
		}
		if(IsEmpty(formBanca.dataTese))
		{
			alert(unescape('O campo Data da Disserta\u00e7\u00e3o/Tese foi preenchido incorretamente.'))
			formBanca.dataTese.focus();
			return false;
		}
		if(IsEmpty(formBanca.horarioTese))
		{
			alert(unescape('O campo Hor\u00e1rio da Disserta\u00e7\u00e3o/Tese foi preenchido incorretamente.'))
			formBanca.horarioTese.focus();
			return false;
		}
		if(IsEmpty(formBanca.localTese))
		{
			alert(unescape('O campo Local da Disserta\u00e7\u00e3o/Tese foi preenchido incorretamente.'))
			formBanca.localTese.focus();
			return false;
		}
	}

return true;

}

function VerificaBancaQual(formBanca)
{


   if(IsEmpty(formBanca.nomeQual))
   {
      alert(unescape('O campo Nome do Membro da Banca do Exame de Qualifica\u00e7\u00e3o deve ser preenchido.'))
      formBanca.nomeQual.focus();
      return false;
   }

   if(IsEmpty(formBanca.instituicaoQual))
   {
      alert(unescape('O campo Institui\u00e7\u00e3o do Membro da Banca do Exame de Qualifica\u00e7\u00e3o deve ser preenchido.'))
      formBanca.instituicaoQual.focus();
      return false;
   }

   if (!radio_button_checker(formBanca.funcaoQual))
   {
     alert('O campo Funcao deve ser preenchido.')
     formBanca.funcaoQual[0].focus();
     return (false);
   }

	return true;

}


function VerificaBancaTese(formBanca)
{


   if(IsEmpty(formBanca.nomeTese))
   {
      alert(unescape('O campo Nome do Membro da Banca deve ser preenchido.'))
      formBanca.nomeTese.focus();
      return false;
   }

   if(IsEmpty(formBanca.instituicaoTese))
   {
      alert(unescape('O campo Institui\u00e7\u00e3o do Membro da Banca deve ser preenchido.'))
      formBanca.instituicaoTese.focus();
      return false;
   }

   if (!radio_button_checker(formBanca.funcaoTese))
   {
     alert('O campo Funcao deve ser preenchido.')
     formBanca.funcaoTese[0].focus();
     return (false);
   }

	return true;

}
function confirmarExclusao()
{
    var confirmar = confirm('Confirmar a exclus\u00e3o?');

    return confirmar;

}

//-->
</script>


	<link rel="stylesheet" href="components/com_portalsecretaria/estilo.css" type="text/css" />
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="components/com_portalsecretaria/jquery/javascripts/jquery-1.9.1.js"></script>
	<script src="components/com_portalsecretaria/jquery/javascripts/jquery-ui.js"></script>
	<link rel="stylesheet" href="/resources/demos/style.css" />
	<script>
	$(function() {
		$( "#dataExameProf" ).datepicker({
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Ter�a','Quarta','Quinta','Sexta','S�bado','Domingo'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','S�b','Dom'],
        monthNames: ['Janeiro','Fevereiro','Mar�o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']});
		$( "#dataQual1" ).datepicker({
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Ter�a','Quarta','Quinta','Sexta','S�bado','Domingo'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','S�b','Dom'],
        monthNames: ['Janeiro','Fevereiro','Mar�o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']});
		$( "#dataQual2" ).datepicker({
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Ter�a','Quarta','Quinta','Sexta','S�bado','Domingo'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','S�b','Dom'],
        monthNames: ['Janeiro','Fevereiro','Mar�o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']});
		$( "#dataTese" ).datepicker({
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Ter�a','Quarta','Quinta','Sexta','S�bado','Domingo'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','S�b','Dom'],
        monthNames: ['Janeiro','Fevereiro','Mar�o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']});
	});
	</script>

 
    <form method="post" name="formBanca" enctype="multipart/form-data" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-save">
           		<a href="javascript:if(ValidateformBanca(document.formBanca))document.formBanca.submit()">
           			<span class="icon-32-save"></span><?php echo JText::_( 'Salvar' ); ?></a>
				</div>
				<div class="icon" id="toolbar-back">
           		<a href="index.php?option=com_portalsecretaria&task=alunos&Itemid=<?php echo $Itemid;?>">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
		</div>
    <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-contact"><h2>Registrar Bancas/Exame de Profici&#234;ncia</h2></div>
    </div></div>
	<p><h2>Dados do(a) Aluno(a)</h2></p>
    <table width="100%" border="1" cellspacing="1" cellpadding="3" class="tabela">
    <tr>
      <td width="20%" bgcolor="#CCCCCC"><strong>Nome:</strong></td>
      <td width="50%" ><?php echo $aluno->nome;?></td>
      <td width="15%" bgcolor="#CCCCCC"><strong>Curso:</strong></td>
      <td width="15%" ><?php echo $curso[$aluno->curso];?></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC"><strong>Linha de Pesquisa:</strong></td>
      <td><?php echo verLinhaPesquisa($aluno->area, 1);?></td>
      <td bgcolor="#CCCCCC"><strong>Ingresso:</strong></td>
      <td><?php echo $aluno->anoingresso;?></td>
    </tr>
 </table>

	<p><h2>Exame de Profici&#234;ncia</h2></p>
    <table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
      <tr>
    	<td bgcolor="#D0D0D0" width="20%"><b>Idioma: </b></td>
    	<td colspan="3"><input name="idiomaExameProf" type="text" maxlength="20" class="inputbox" size="20" value="<?php echo $aluno->idiomaExameProf;?>"/></td>
      </tr>
      <tr>
        <td bgcolor="#D0D0D0" width="15%"><b>Data do Exame: </b></td>
    	<td width="35%"><input name="dataExameProf" id="dataExameProf" type="text" maxlength="10" class="inputbox" size="10" value="<?php if($aluno->dataExameProf) echo $aluno->dataExameProf;?>"/></td>
        <td bgcolor="#D0D0D0" width="25%"><b>Conceito Obtido: </b></td>
    	<td width="25%">
            <select name="conceitoExameProf" class="inputbox">
            <option value="" <?php if ($aluno->conceitoExameProf == "") echo 'SELECTED';?>></option>
            <option value="Aprovado" <?php if ($aluno->conceitoExameProf == "Aprovado") echo 'SELECTED';?>>Aprovado</option>
            <option value="Reprovado" <?php if ($aluno->conceitoExameProf == "Reprovado") echo 'SELECTED';?>>Reprovado</option>
            </select>
        </td>
      </tr>
    </table>
    <?php

	if ($aluno->curso == 2) {
?>
	<p><h2>Exame de Qualifica&#231;&#227;o I</h2></p>
    <table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
      <tr>
    	<td bgcolor="#D0D0D0" width="20%"><b>T&#237;tulo: </b></td>
    	<td colspan="4"><input name="tituloQual1" type="text" maxlength="180" class="inputbox" size="80" value="<?php echo $aluno->tituloQual1;?>"/></td>
      </tr>
      <tr>
        <td bgcolor="#D0D0D0" width="15%"><b>Data da Defesa: </b></td>
    	<td width="25%"><input id="dataQual1" name="dataQual1" type="text" maxlength="10" class="inputbox" size="10" value="<?php if($aluno->dataQual1) echo $aluno->dataQual1;?>"/></td>
        <td bgcolor="#D0D0D0"><b>Conceito Obtido: </b></td>
    	<td>
            <select name="conceitoQual1" class="inputbox">
            <option value="" <?php if ($aluno->conceitoQual1 == "") echo 'SELECTED';?>></option>
            <option value="Aprovado" <?php if ($aluno->conceitoQual1 == "Aprovado") echo 'SELECTED';?>>Aprovado</option>
            <option value="Reprovado" <?php if ($aluno->conceitoQual1 == "Reprovado") echo 'SELECTED';?>>Reprovado</option>
            </select>
        </td>
      </tr>
      <tr>
        <td bgcolor="#D0D0D0"><b>Examinador: </b></td>
        <td colspan="3"><input name="examinadorQual1" type="text" maxlength="60" class="inputbox" size="60" value="<?php echo $aluno->examinadorQual1;?>"/></td>
      </tr>
    </table>


	<?php

	}
?>
	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-print">
           		<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=chamadabanca&idAluno=<?php echo $aluno->id;?>&tipoDefesa=Q" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;">
           			<span class="icon-32-print"></span><?php echo JText::_( 'Convite' ); ?></a>
				</div>
				<div class="icon" id="toolbar-print">
					<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=imprimirFolha&idAluno=<?php echo $aluno->id;?>&tipoDefesa=Q" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;">
           			<span class="icon-32-print"></span><?php echo JText::_( 'Folha de <br>Aprova&#231;&#227;o' ); ?></a>
				</div>
				
		</div>
    <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-contact"><h2>Exame de Qualifica&#231;&#227;o<?php if($aluno->curso == 2) echo " II";?></div>
    </div></div>
 
	<table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
      <tr>
    	<td bgcolor="#D0D0D0" width="20%"><b>T&#237;tulo: </b></td>
    	<td colspan="3"><input name="tituloQual2" type="text" maxlength="180" class="inputbox" size="80" value="<?php echo $aluno->tituloQual2;?>"/></td>
   	</tr>
      <tr>
        <td bgcolor="#D0D0D0" width="15%"><b>Data da Defesa: </b></td>
    	<td width="25%"><input id="dataQual2" name="dataQual2" type="text" maxlength="10" class="inputbox" size="10" value="<?php echo $aluno->dataQual2;?>"/></td>
        <td bgcolor="#D0D0D0" width="20%"><b>Hor&#225;rio: </b></td>
    	<td width="20%"><input name="horarioQual2" type="text" maxlength="10" class="inputbox" size="10" value="<?php echo $aluno->horarioQual2;?>"/></td>
      </tr>
      <tr>
        <td bgcolor="#D0D0D0"><b>Local: </b></td>
    	<td><input name="localQual2" type="text" maxlength="100" class="inputbox" size="40" value="<?php echo $aluno->localQual2;?>"/></td>
        <td bgcolor="#D0D0D0"><b>Conceito Obtido: </b></td>
    	<td>
            <select name="conceitoQual2" class="inputbox">
            <option value="" <?php if ($aluno->conceitoQual2 == "") echo 'SELECTED';?>></option>
            <option value="Aprovado" <?php if ($aluno->conceitoQual2 == "Aprovado") echo 'SELECTED';?>>Aprovado</option>
            <option value="Reprovado" <?php if ($aluno->conceitoQual2 == "Reprovado") echo 'SELECTED';?>>Reprovado</option>
            </select>
        </td>
      </tr>
      <tr>
        <td bgcolor="#D0D0D0"><b>Resumo: </b></td>
    	<td colspan="3"><TEXTAREA NAME="resumoQual2" COLS="60%" ROWS="10"><?php echo $aluno->resumoQual2;?></TEXTAREA></td>
      </tr>
    </table>
	<p><h4>Comiss&#227;o Examinadora:</h4></p>
    <table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
	<thead>
      <tr bgcolor="#002666">
    	<th></th>
		<th></th>
		<th></th>
    	<th width="45%" align="center"><b><font color="#FFFFFF">Nome do Membro</font></b></th>
    	<th width="30%" align="center"><b><font color="#FFFFFF">Institui&#231;&#227;o</font></b></th>
    	<th width="20%" align="center"><b><font color="#FFFFFF">Fun&#231;&#227;o</font></b></th>
      </tr>
     </thead>
     <tbody>
	<?php


	$table_bgcolor_even = "#e6e6e6";
	$table_bgcolor_odd = "#FFFFFF";
	$funcao = array (
		'P' => "Presidente",
		'I' => "Membro Interno",
		'E' => "Membro Externo"
	);

	$sql = "SELECT * FROM #__banca WHERE idAluno = $aluno->id AND tipoDefesa = 'Q' ORDER BY funcao DESC, nomeMembro ASC";
	$database->setQuery($sql);
	$membros = $database->loadObjectList();

	$i = 0;
	foreach ($membros as $banca) {
		$i = $i +1;
		if ($i % 2) {
			echo ("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
		} else {
			echo ("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
		}
?>

		<td width='16' align="center">
		<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=imprimirAgradecimento&idMembro=<?php echo $banca->id;?>&tipoDefesa=Q" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;">
           			<img src="components/com_portalsecretaria/images/b_print.gif" border="0" title='Carta de Agradecimento'></a>
		</td>
		<td width='16' align="center">
			<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=imprimirDeclaracaoBanca&idMembro=<?php echo $banca->id;?>&tipoDefesa=Q" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;">		
            <img src="components/com_portalsecretaria/images/b_print.gif" border="0" title='Declara&#231;&#227;o de Participa&#231;&#227;o'></a>
		</td>		
		<td width='16' align="center">
            <a href="javascript:if(confirmarExclusao()){
            document.formBanca.task.value='removeBancaQual';
            document.formBanca.id.value='<?php echo $banca->id;?>';
            document.formBanca.submit();}">
                <img src="components/com_portalsecretaria/images/excluir.png" border="0" title='excluir'></a>
			</a>
		</td>
		<td><?php echo $banca->nomeMembro;?></td>
		<td><?php echo $banca->instituicaoMembro;?></td>
		<td><?php echo $funcao[$banca->funcao];?></td>
	</tr>

	<?php

	}
?>
     <tr>
		<td width='16' align="center" colspan="3">
            <a href="javascript:if(VerificaBancaQual(document.formBanca)){document.formBanca.task.value='addBancaQual';document.formBanca.submit();}"> <img src="components/com_portalsecretaria/images/adicionar.jpg" border="0" title='adicionar'></a>
			</a>
		</td>
		<td><input name="nomeQual" type="text" maxlength="60" class="inputbox" size="50"></td>
		<td><input name="instituicaoQual" type="text" maxlength="60" class="inputbox" size="30"></td>
		<td><input name="funcaoQual" value="P" type="radio">Presidente<br /><input name="funcaoQual" value="I" type="radio" >Membro Interno<br /><input name="funcaoQual" value="E" type="radio" >Membro Externo</td>
	</tr>
    </tbody>
    </table>
	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-print">
           		<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=chamadabanca&idAluno=<?php echo $aluno->id;?>&tipoDefesa=T" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;">
           			<span class="icon-32-print"></span><?php echo JText::_( 'Convite' ); ?></a>
				</div>
				<div class="icon" id="toolbar-print">
           		<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=imprimirAta&idAluno=<?php echo $aluno->id;?>&tipoDefesa=T" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;">
           			<span class="icon-32-print"></span><?php echo JText::_( 'Ata de <br>Defesa' ); ?></a>
				</div>
				<div class="icon" id="toolbar-print">
           		<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=imprimirFolha&idAluno=<?php echo $aluno->id;?>&tipoDefesa=T" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;">
           			<span class="icon-32-print"></span><?php echo JText::_( 'Folha de <br>Aprova&#231;&#227;o' ); ?></a>
				</div>
				
		</div>
    <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-contact"><h2><?php

	if ($aluno->curso == 2)
		echo "Tese de Doutorado";
	else
		echo "Disserta&#231;&#227;o de Mestrado";
?></h2></div>
    </div></div>
    <table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
      <tr>
    	<td bgcolor="#D0D0D0" width="20%"><b>N&#186; Defesa: </b></td>
    	<td colspan="3"><input name="numDefesa" type="text" maxlength="10" class="inputbox" size="10" value="<?php if($aluno->numDefesa) echo $aluno->numDefesa;?>"/></td>
      </tr>

  	  <tr>
    	<td bgcolor="#D0D0D0"><b>T&#237;tulo: </b></td>
    	<td colspan="3"><input name="tituloTese" type="text" maxlength="180" class="inputbox" size="100" value="<?php echo $aluno->tituloTese;?>"/></td>
      </tr>
      <tr>
        <td bgcolor="#D0D0D0" width="20%"><b>Data da Defesa: </b></td>
    	<td width="20%"><input id="dataTese" name="dataTese" type="text" maxlength="10" class="inputbox" size="10" value="<?php if($aluno->dataTese) echo $aluno->dataTese;?>"/></td>
        <td bgcolor="#D0D0D0" width="20%"><b>Hor&#225;rio: </b></td>
    	<td width="20%"><input name="horarioTese" type="text" maxlength="10" class="inputbox" size="10" value="<?php echo $aluno->horarioTese;?>"/></td>
      </tr>
      <tr>
        <td bgcolor="#D0D0D0"><b>Local: </b></td>
    	<td><input name="localTese" type="text" maxlength="100" class="inputbox" size="40" value="<?php echo $aluno->localTese;?>"/></td>
        <td bgcolor="#D0D0D0"><b>Conceito Obtido: </b></td>
    	<td>
            <select name="conceitoTese" class="inputbox">
            <option value="" <?php if ($aluno->conceitoTese == "") echo 'SELECTED';?>></option>
            <option value="Aprovado" <?php if ($aluno->conceitoTese == "Aprovado") echo 'SELECTED';?>>Aprovado</option>
            <option value="Reprovado" <?php if ($aluno->conceitoTese == "Reprovado") echo 'SELECTED';?>>Reprovado</option>
            </select>
        </td>
      </tr>
      <tr>
        <td bgcolor="#D0D0D0"><b>Resumo: </b></td>
    	<td colspan="3"><TEXTAREA NAME="resumoTese" COLS="60%" ROWS="10"><?php echo $aluno->resumoTese;?></TEXTAREA></td>
      </tr>

    </table>
	<p><h4>Comiss&#227;o Examinadora:</h4></p>
    <table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
	<thead>
      <tr bgcolor="#002666">
    	<th></th>
		<th></th>
		<th></th>
    	<th width="45%" align="center"><b><font color="#FFFFFF">Nome do Membro</font></b></th>
    	<th width="30%" align="center"><b><font color="#FFFFFF">Institui&#231;&#227;o</font></b></th>
    	<th width="20%" align="center"><b><font color="#FFFFFF">Fun&#231;&#227;o</font></b></th>
      </tr>
     </thead>
     <tbody>
	<?php


	$table_bgcolor_even = "#e6e6e6";
	$table_bgcolor_odd = "#FFFFFF";
	$funcao = array (
		'P' => "Presidente",
		'I' => "Membro Interno",
		'E' => "Membro Externo"
	);

	$sql = "SELECT * FROM #__banca WHERE idAluno = $aluno->id AND tipoDefesa = 'T' ORDER BY funcao DESC, nomeMembro ASC";
	$database->setQuery($sql);
	$membros = $database->loadObjectList();

	$i = 0;
	foreach ($membros as $banca) {
		$i = $i +1;
		if ($i % 2) {
			echo ("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
		} else {
			echo ("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
		}
?>
		<td width='16' align="center">
		<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=imprimirAgradecimento&idMembro=<?php echo $banca->id;?>&tipoDefesa=T" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;">
           			<img src="components/com_portalsecretaria/images/b_print.gif" border="0" title='Carta de Agradecimento'></a>
		</td>
		<td width='16' align="center">
			<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=imprimirDeclaracaoBanca&idMembro=<?php echo $banca->id;?>&tipoDefesa=T" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;">		
            <img src="components/com_portalsecretaria/images/b_print.gif" border="0" title='Declara&#231;&#227;o de Participa&#231;&#227;o'></a>
		</td>
		<td width='16' align="center">	
			<a href="javascript:if(confirmarExclusao()){
   			   document.formBanca.task.value='removeBancaTese';
               document.formBanca.id.value='<?php echo $banca->id;?>';
               document.formBanca.submit();}">
               <img src="components/com_portalsecretaria/images/excluir.png" border="0" title='excluir'>
            </a>
		</td>
		<td><?php echo $banca->nomeMembro;?></td>
		<td><?php echo $banca->instituicaoMembro;?></td>
		<td><?php echo $funcao[$banca->funcao];?></td>
	</tr>

	<?php

	}
?>
     <tr>
		<td width='16' align="center" colspan="3">
            <a href="javascript:if(VerificaBancaTese(document.formBanca)){document.formBanca.task.value='addBancaTese';document.formBanca.submit();}"> <img src="components/com_portalsecretaria/images/adicionar.jpg" border="0" title='adicionar'></a>
			</a>
		</td>
		<td><input name="nomeTese" type="text" maxlength="150" class="inputbox" size="50"></td>
		<td><input name="instituicaoTese" type="text" maxlength="60" class="inputbox" size="30"></td>
		<td><input name="funcaoTese" value="P" type="radio">Presidente<br /><input name="funcaoTese" value="I" type="radio" >Membro Interno<br /><input name="funcaoTese" value="E" type="radio" >Membro Externo</td>
	</tr>
    </tbody>
    </table>
     <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
     <input name='task' type='hidden' value='salvarbanca'>
     <input name='idAluno' type='hidden' value='<?php echo $aluno->id;?>'>
     <input name='id' type='hidden' value=''>
     </form>

    <?php

}

function imprimirChamadaDefesa($idAluno, $tipoDefesa) {

	require('fpdf/fpdf.php'); 
	//configura��es iniciais
	
	class PDF extends FPDF
	{	
		function Header()
		{
			$this->Image('components/com_portalsecretaria/images/logo-brasil.jpg', 10, 7, 32.32);
			$this->Image('components/com_portalsecretaria/images/ufam.jpg', 175, 7, 25.25);
	
			//exibe o cabecalho do documento
			$this->SetFont("Helvetica",'B', 12);
			$this->MultiCell(0,5,"PODER EXECUTIVO",0, 'C');
			$this->SetFont("Helvetica",'B', 10);
			$this->MultiCell(0,5,"MINIST�RIO DA EDUCA��O",0, 'C');
			$this->MultiCell(0,5,"INSTITUTO DE COMPUTA��O",0, 'C');
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,5,"PROGRAMA DE P�S-GRADUA��O EM INFORM�TICA",0, 'C');
			$this->SetDrawColor(0,0,0);
			$this->Line(10,42,200,42);
			$this->ln( 7 ); 			
		}

		function Footer()
		{
			$this->Line(10,285,200,285);
			$this->SetFont('Helvetica','I',8);
			$this->SetXY(10, 281);
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,4,"Av. Rodrigo Ot�vio, 6.200 - Campus Universit�rio Senador Arthur Virg�lio Filho - CEP 69077-000 - Manaus, AM, Brasil",0, 'C');
			$this->MultiCell(0,4," Tel. (092) 3305 1193         E-mail: secretariappgi@icomp.ufam.edu.br          www.ppgi.ufam.edu.br",0, 'C');
			$this->Image('components/com_portalsecretaria/images/icon_telefone.jpg', '40', '290');
			$this->Image('components/com_portalsecretaria/images/icon_email.jpg', '73', '290');
			$this->Image('components/com_portalsecretaria/images/icon_casa.jpg', '134', '290');
		}
	}
	$database = & JFactory :: getDBO();

	$curso = array (
		'Q' => 'DISSERTA&Ccedil;&Atilde;O',
		'T' => 'TESE'
	);

	$mes = array (
		"01" => "Janeiro",
		"02" => "Fevereiro",
		"03" => "Mar&#231;o",
		"04" => "Abril",
		"05" => "Maio",
		"06" => "Junho",
		"07" => "Julho",
		"08" => "Agosto",
		"09" => "Setembro",
		"10" => "Outubro",
		"11" => "Novembro",
		"12" => "Dezembro"
	);

	$sql = "SELECT UPPER(nome) as nome, UPPER(tituloQual2) as tituloQual2, dataQual2, localQual2, resumoQual2, horarioQual2, UPPER(tituloTese) as tituloTese, dataTese, horarioTese, localTese, resumoTese, curso FROM #__aluno WHERE id = $idAluno";
	$database->setQuery($sql);
	$alunos = $database->loadObjectList();

	$sql = "SELECT idAluno, nomeMembro, instituicaoMembro, funcao FROM #__banca WHERE idAluno = $idAluno AND tipoDefesa = '$tipoDefesa' ORDER BY funcao DESC";
	$database->setQuery($sql);
	$banca = $database->loadObjectList();
	
	$chave = md5($alunos[0]->nome . date("l jS \of F Y h:i:s A"));

	//$pdf = new FPDF('P','cm','A4');
	$pdf = new PDF();
	$pdf->Open();
	$pdf->AddPage();

	//titulos de configura��o do documento
	$pdf->SetTitle("Ata de Defesa");
	
	// OBTENDO OS DADOS A SEREM PREENCHIDOS
	if ($tipoDefesa == 'Q') {	
		$data = explode("/",$alunos[0]->dataQual2);
		$hora = $alunos[0]->horarioQual2;
		$local = utf8_decode($alunos[0]->localQual2);		
		$titulo = utf8_decode($alunos[0]->tituloQual2);
		if ($alunos[0]->curso == 2){
			$complemento = "exame de qualifica��o de doutorado";
			$complemento2 = "qualificada para o doutorado";
			$complemento3 = "EXAME DE QUALIFICA��O DE DOUTORADO";
		}
		else{
			$complemento = "exame de qualifica��o de mestrado";
			$complemento2 = "qualificada para o mestrado";			
			$complemento3 = "EXAME DE QUALIFICA��O DE MESTRADO";
		}
	}
	else{
		$data = explode("/", $alunos[0]->dataTese);	
		$hora = $alunos[0]->horarioTese;		
		$local = utf8_decode($alunos[0]->localTese);		
		$titulo = utf8_decode($alunos[0]->tituloTese);
		if ($alunos[0]->curso == 2){
			$complemento = "TESE DE DOUTORADO";
			$complemento3 = "tese de doutorado";
			$complemento2 = "doutor";
		}
		else{
			$complemento = "DISSERTA��O DE MESTRADO";
			$complemento3 = "disserta��o de mestrado";
			$complemento2 = "mestre";			
		}
	}
	$membrosBanca = "";
	foreach ($banca as $membro) {
		
		if ($membro->funcao == "P"){
			$presidente = utf8_decode($membro->nomeMembro). " - " . utf8_decode($membro->instituicaoMembro);
		}
		else{
			$membrosBanca = $membrosBanca. utf8_decode($membro->nomeMembro). " (" . utf8_decode($membro->instituicaoMembro). "), ";
		}
	}	
	// OBTENDO OS DADOS A SEREM PREENCHIDOS
	
	$pdf->SetFont("Helvetica",'B', 14);
	$pdf->MultiCell(0,7,"",0, 'C');
	$pdf->MultiCell(0,5,'CONVITE � COMUNIDADE',0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	
	$tag = " A Coordena��o do Programa de P�s-Gradua��o em Inform�tica PPGI/UFAM tem o prazer de convidar toda a comunidade para a sess�o p�blica de apresenta��o de defesa de";

	if ($tipoDefesa == 'Q') {
		if ($alunos[0]->curso == 2)
			$tag = $tag . " exame de qualifica��o de doutorado:";
		else
			$tag = $tag . " exame de qualifica��o de mestrado:";
	} else {
		if ($alunos[0]->curso == 2)
			$tag = $tag . " tese:";
		else
			$tag = $tag . " disserta��o:";
	}

	$pdf->SetFont("Helvetica",'', 10);
	$pdf->MultiCell(0,6,$tag,0, 'J');
	$pdf->MultiCell(0,5,"",0, 'C');
	
	$pdf->SetFont("Helvetica",'B', 12);
	if ($tipoDefesa == 'Q'){
		$pdf->MultiCell(0,6,utf8_decode($alunos[0]->tituloQual2),0, 'C');
		$pdf->MultiCell(0,5,"",0, 'C');
		$pdf->SetFont("Helvetica",'', 11);
		$pdf->MultiCell(0,6,"RESUMO: " . utf8_decode($alunos[0]->resumoQual2),0, 'J');
	}
	else{
		$pdf->MultiCell(0,6,utf8_decode($alunos[0]->tituloTese),0, 'C');
		$pdf->MultiCell(0,5,"",0, 'C');
		$pdf->SetFont("Helvetica",'', 11);
		$pdf->MultiCell(0,6,"RESUMO: " . utf8_decode($alunos[0]->resumoTese),0, 'J');
	}
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,6,"CANDIDATO(A): " . utf8_decode($alunos[0]->nome),0, 'J');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,6,"BANCA EXAMINADORA: ",0, 'J');

	foreach ($banca as $membro) {
		$tag = "                        " . utf8_decode($membro->nomeMembro) . " - " . utf8_decode($membro->instituicaoMembro);
		if ($membro->funcao == "P")
			$tag = $tag . " (Presidente)";
		$pdf->MultiCell(0,6,$tag,0, 'J');
	}

	$pdf->MultiCell(0,5,"",0, 'C');
	
	if ($tipoDefesa == 'Q') {
		$pdf->MultiCell(0,6,"LOCAL: " . utf8_decode($alunos[0]->localQual2),0, 'J');
		$pdf->MultiCell(0,5,"",0, 'C');
		$pdf->MultiCell(0,6,"DATA: " . utf8_decode($alunos[0]->dataQual2),0, 'J');
		$pdf->MultiCell(0,5,"",0, 'C');
		$pdf->MultiCell(0,6,"HOR�RIO: " . utf8_decode($alunos[0]->horarioQual2),0, 'J');
		$pdf->MultiCell(0,5,"",0, 'C');
	} else {
		$pdf->MultiCell(0,6,"LOCAL: " . utf8_decode($alunos[0]->localTese),0, 'J');
		$pdf->MultiCell(0,5,"",0, 'C');
		$pdf->MultiCell(0,6,"DATA: " . utf8_decode($alunos[0]->dataTese),0, 'J');
		$pdf->MultiCell(0,5,"",0, 'C');
		$pdf->MultiCell(0,6,"HOR�RIO: " . utf8_decode($alunos[0]->horarioTese),0, 'J');
		$pdf->MultiCell(0,5,"",0, 'C');
	}

	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->SetFont("Helvetica",'', 10);
	$pdf->MultiCell(0,4,"Professora Dra. Eulanda Miranda dos Santos",0, 'C');
	$pdf->SetFont("Helvetica",'', 8);
	$pdf->MultiCell(0,4,"Coordenadora do Programa de P�s-Gradua��o em Inform�tica PPGI/UFAM",0, 'C');

	ob_clean(); // Limpa o buffer de sa�da
	//cria o arquivo pdf e exibe no navegador
	$pdf->Output('components/com_portalsecretaria/defesas/$chave.pdf','I');
	exit;
			
}

function imprimirAtaDefesa($idAluno, $tipoDefesa) {

	require('fpdf/fpdf.php'); 
	//configura��es iniciais
	
	class PDF extends FPDF
	{	
		function Header()
		{
			$this->Image('components/com_portalsecretaria/images/logo-brasil.jpg', 10, 7, 32.32);
			$this->Image('components/com_portalsecretaria/images/ufam.jpg', 175, 7, 25.25);
	
			//exibe o cabecalho do documento
			$this->SetFont("Helvetica",'B', 12);
			$this->MultiCell(0,5,"PODER EXECUTIVO",0, 'C');
			$this->SetFont("Helvetica",'B', 10);
			$this->MultiCell(0,5,"MINIST�RIO DA EDUCA��O",0, 'C');
			$this->MultiCell(0,5,"INSTITUTO DE COMPUTA��O",0, 'C');
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,5,"PROGRAMA DE P�S-GRADUA��O EM INFORM�TICA",0, 'C');
			$this->SetDrawColor(0,0,0);
			$this->Line(10,42,200,42);		
			$this->ln( 7 ); 			
		}

		function Footer()
		{
			$this->Line(10,285,200,285);
			$this->SetFont('Helvetica','I',8);
			$this->SetXY(10, 281);
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,4,"Av. Rodrigo Ot�vio, 6.200 - Campus Universit�rio Senador Arthur Virg�lio Filho - CEP 69077-000 - Manaus, AM, Brasil",0, 'C');
			$this->MultiCell(0,4," Tel. (092) 3305 1193         E-mail: secretariappgi@icomp.ufam.edu.br          www.ppgi.ufam.edu.br",0, 'C');
			$this->Image('components/com_portalsecretaria/images/icon_telefone.jpg', '40', '290');
			$this->Image('components/com_portalsecretaria/images/icon_email.jpg', '73', '290');
			$this->Image('components/com_portalsecretaria/images/icon_casa.jpg', '134', '290');
		}
	}
	$database = & JFactory :: getDBO();

	$curso = array (
		'Q' => 'DISSERTA&Ccedil;&Atilde;O',
		'T' => 'TESE'
	);

	$mes = array (
		"01" => "Janeiro",
		"02" => "Fevereiro",
		"03" => "Mar�o",
		"04" => "Abril",
		"05" => "Maio",
		"06" => "Junho",
		"07" => "Julho",
		"08" => "Agosto",
		"09" => "Setembro",
		"10" => "Outubro",
		"11" => "Novembro",
		"12" => "Dezembro"
	);

	$sql = "SELECT id, UPPER(nome) as nome, numDefesa, UPPER(tituloQual2) as tituloQual2, dataQual2, localQual2, horarioQual2, resumoQual2, UPPER(tituloTese) as tituloTese, dataTese, horarioTese, localTese, resumoTese, curso FROM #__aluno WHERE id = $idAluno";
	$database->setQuery($sql);
	$alunos = $database->loadObjectList();

	$sql = "SELECT idAluno, nomeMembro, instituicaoMembro, funcao FROM #__banca WHERE idAluno = $idAluno AND tipoDefesa = '$tipoDefesa' ORDER BY funcao DESC";
	$database->setQuery($sql);
	$banca = $database->loadObjectList();

	$chave = md5($alunos[0]->id . $alunos[0]->nome . date("l jS \of F Y h:i:s A"));

	//$pdf = new FPDF('P','cm','A4');
	$pdf = new PDF();
	$pdf->Open();
	$pdf->AddPage();

	//titulos de configura��o do documento
	$pdf->SetTitle("Ata de Defesa");
	
	// OBTENDO OS DADOS A SEREM PREENCHIDOS
	if ($tipoDefesa == 'Q') {	
		$data = explode("/",$alunos[0]->dataQual2);
		$hora = $alunos[0]->horarioQual2;
		$local = utf8_decode($alunos[0]->localQual2);		
		$titulo = utf8_decode($alunos[0]->tituloQual2);
		if ($alunos[0]->curso == 2){
			$complemento = "exame de qualifica��o de doutorado";
			$complemento2 = "qualificada para o doutorado";
			$complemento3 = "EXAME DE QUALIFICA��O DE DOUTORADO";
		}
		else{
			$complemento = "exame de qualifica��o de mestrado";
			$complemento2 = "qualificada para o mestrado";			
			$complemento3 = "EXAME DE QUALIFICA��O DE MESTRADO";
		}
	}
	else{
		$data = explode("/", $alunos[0]->dataTese);	
		$hora = $alunos[0]->horarioTese;		
		$local = utf8_decode($alunos[0]->localTese);		
		$titulo = utf8_decode($alunos[0]->tituloTese);
		if ($alunos[0]->curso == 2){
			$complemento = "TESE DE DOUTORADO";
			$complemento3 = "tese de doutorado";
			$complemento2 = "doutor";
		}
		else{
			$complemento = "DISSERTA��O DE MESTRADO";
			$complemento3 = "disserta��o de mestrado";
			$complemento2 = "mestre";			
		}
	}
	$membrosBanca = "";
	foreach ($banca as $membro) {
		
		if ($membro->funcao == "P"){
			$presidente = utf8_decode($membro->nomeMembro). " (" . utf8_decode($membro->instituicaoMembro). ") ";
		}
		else{
			$membrosBanca = $membrosBanca. utf8_decode($membro->nomeMembro). " (" . utf8_decode($membro->instituicaoMembro). "), ";
		}
	}	
	// OBTENDO OS DADOS A SEREM PREENCHIDOS
	
	$pdf->SetFont("Helvetica",'B', 14);
	$pdf->MultiCell(0,7,"",0, 'C');
	$pdf->MultiCell(0,5,$alunos[0]->numDefesa.'� ATA DE DEFESA P�BLICA DE '.$complemento,0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	
	$tag = "Aos ".$data[0]." dias do m�s de ".$mes[$data[1]]." do ano de ".$data[2].", �s ".$hora.", na ".$local." da Universidade Federal do Amazonas, situada na Av. Rodrigo Ot�vio, 6.200, Campus Universit�rio, Setor Norte, Coroado, nesta Capital, ocorreu a sess�o p�blica de defesa de ".$complemento3." intitulada  '".$titulo."' apresentada pelo aluno  ".utf8_decode($alunos[0]->nome)." que concluiu todos os pr�-requisitos exigidos para a obten��o do t�tulo de ".$complemento2." em inform�tica, conforme estabelece o artigo 52 do regimento interno do curso. Os trabalhos foram instalados pelo(a)  ".$presidente.", orientador(a) e presidente da Banca Examinadora, que foi constitu�da, ainda, por ".$membrosBanca."membros convidados. A Banca Examinadora tendo decidido aceitar a disserta��o, passou � argui��o p�blica do candidato. 
	
Encerrados os trabalhos, os examinadores expressaram o parecer abaixo. 

A comiss�o considerou a ".$complemento3.":
(   ) Aprovada
(   ) Aprovada condicionalmente, sujeita a altera��es, conforme folha de modifica��es, anexa,
(   ) Reprovada, conforme folha de modifica��es, anexa

Proclamados os resultados, foram encerrados os trabalhos e, para constar, eu, Elienai Nogueira, Secret�ria do Programa de P�s-Gradua��o em Inform�tica, lavrei a presente ata, que assino juntamente com os Membros da Banca Examinadora.";

	$pdf->SetFont("Helvetica",'', 10);
	$pdf->MultiCell(0,6,$tag,0, 'J');
	$pdf->MultiCell(0,5,"",0, 'C');
	$i = 0;
	foreach ($banca as $membro) {
		$pdf->MultiCell(0,7,"Assinatura: ___________________________________             ".utf8_decode($membro->nomeMembro),0, 'J');	
		$pdf->MultiCell(0,5,"",0, 'C');	
		$i++;
	}	
	$pdf->SetXY(10, 255);
	$pdf->MultiCell(0,5,"____________________________________
	Secretaria",0, 'C');	
	$pdf->MultiCell(0,5,"",0, 'C');			
	$pdf->MultiCell(0,5,"Manaus, ".$data[0]." de ". $mes[$data[1]]." de ".$data[2],0, 'C');	

	ob_clean(); // Limpa o buffer de sa�da
	//cria o arquivo pdf e exibe no navegador
	$pdf->Output('components/com_portalsecretaria/defesas/$chave.pdf','I');
	exit;

}

///////////////////////////////

function imprimirFolhaQualificacao($idAluno, $tipoDefesa) {


	require('fpdf/fpdf.php');
	//configura��es iniciais

	class PDF extends FPDF
	{
		function Header()
		{
			$this->Image('components/com_portalsecretaria/images/logo-brasil.jpg', 10, 7, 32.32);
			$this->Image('components/com_portalsecretaria/images/ufam.jpg', 175, 7, 25.25);

			//exibe o cabecalho do documento
			$this->SetFont("Helvetica",'B', 12);
			$this->MultiCell(0,5,"PODER EXECUTIVO",0, 'C');
			$this->SetFont("Helvetica",'B', 10);
			$this->MultiCell(0,5,"MINIST�RIO DA EDUCA��O",0, 'C');
			$this->MultiCell(0,5,"INSTITUTO DE COMPUTA��O",0, 'C');
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,5,"PROGRAMA DE P�S-GRADUA��O EM INFORM�TICA",0, 'C');
			$this->SetDrawColor(0,0,0);
			$this->Line(10,42,200,42);
			$this->ln( 7 );
		}

		function Footer()
		{
			$this->Line(10,285,200,285);
			$this->SetFont('Helvetica','I',8);
			$this->SetXY(10, 281);
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,4,"Av. Rodrigo Ot�vio, 6.200 - Campus Universit�rio Senador Arthur Virg�lio Filho - CEP 69077-000 - Manaus, AM, Brasil",0, 'C');
			$this->MultiCell(0,4," Tel. (092) 3305 1193         E-mail: secretariappgi@icomp.ufam.edu.br          www.ppgi.ufam.edu.br",0, 'C');
			$this->Image('components/com_portalsecretaria/images/icon_telefone.jpg', '40', '290');
			$this->Image('components/com_portalsecretaria/images/icon_email.jpg', '73', '290');
			$this->Image('components/com_portalsecretaria/images/icon_casa.jpg', '134', '290');
		}
	}
	$database = & JFactory :: getDBO();

	$curso = array (
		'Q' => 'DISSERTA&Ccedil;&Atilde;O',
		'T' => 'TESE'
	);

	$mes = array (
		"01" => "Janeiro",
		"02" => "Fevereiro",
		"03" => "Mar�o",
		"04" => "Abril",
		"05" => "Maio",
		"06" => "Junho",
		"07" => "Julho",
		"08" => "Agosto",
		"09" => "Setembro",
		"10" => "Outubro",
		"11" => "Novembro",
		"12" => "Dezembro"
	);

	$sql = "SELECT id, UPPER(nome) as nome, area, orientador, tituloQual2, dataQual2, localQual2, horarioQual2, resumoQual2, curso FROM #__aluno WHERE id = $idAluno";
	$database->setQuery($sql);
	$alunos = $database->loadObjectList();

	$sql = "SELECT idAluno, nomeMembro, instituicaoMembro, funcao FROM #__banca WHERE idAluno = $idAluno AND tipoDefesa = '$tipoDefesa' ORDER BY funcao DESC";
	$database->setQuery($sql);
	$banca = $database->loadObjectList();

	$chave = md5($alunos[0]->id . $alunos[0]->nome . date("l jS \of F Y h:i:s A"));

	//$pdf = new FPDF('P','cm','A4');
	$pdf = new PDF();
	$pdf->Open();
	$pdf->AddPage();

	//titulos de configura��o do documento
	$pdf->SetTitle("Folha de Aprova��o");

	// OBTENDO OS DADOS A SEREM PREENCHIDOS

	$data = explode("/",$alunos[0]->dataQual2);
	$hora = $alunos[0]->horarioQual2;
	$local = utf8_decode($alunos[0]->localQual2);
	$titulo = utf8_decode($alunos[0]->tituloQual2);
	if ($alunos[0]->curso == 2){
		$complemento = "TESE DE DOUTORADO";
	}
	else{
		$complemento = "DISSERTA��O DE MESTRADO";
	}
	$membrosBanca = "";

	// OBTENDO OS DADOS A SEREM PREENCHIDOS

	$pdf->SetFont("Helvetica",'B', 14);
	$pdf->MultiCell(0,8,'AVALIA��O DE PROPOSTA DE '.$complemento,0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->SetFont("Helvetica",'B', 12);
    // DADOS DO ALUNO
	$pdf->MultiCell(0,8,"DADOS DO(A) ALUNO(A)",0, 'J');
	$pdf->SetFont("Helvetica",'', 12);
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"Nome: ".utf8_decode($alunos[0]->nome),0, 'J');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"Linha de Pesquisa: ".utf8_decode(verLinhaPesquisa($alunos[0]->area,1)),0, 'J');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"Orientador: ".utf8_decode(verProfessor($alunos[0]->orientador)),0, 'J');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->SetDrawColor(180,180,180);
	$pdf->Line(10,102,200,102);
    // DADOS DA DEFESA
	$pdf->SetFont("Helvetica",'B', 12);
	$pdf->MultiCell(0,8,"DADOS DA DEFESA",0, 'J');
	$pdf->SetFont("Helvetica",'', 12);
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"T�tulo: ".$titulo,0, 'J');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"Data: ".$data[0]."/".$data[1]."/".$data[2]."     Hora: ".$hora."     Local: ".$local,0, 'J');
	$pdf->MultiCell(0,8,"",0, 'C');
    // DADOS DA DEFESA
	$pdf->SetFont("Helvetica",'B', 12);
	$pdf->MultiCell(0,8,"AVALIA��O DA BANCA EXAMINADORA",0, 'J');
	$pdf->MultiCell(0,6,"",0, 'C');
	$pdf->SetXY(140,$pdf->getY() - 14);
	$pdf->MultiCell(0,6,"CONCEITO: ___________",0, 'J');
   	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->SetFont("Helvetica",'', 12);
	foreach ($banca as $membro) {
      	$pdf->MultiCell(0,5,"",0, 'C');
		if ($membro->funcao == "P")
        	$pdf->MultiCell(0,9,"_____________________________________________
            ".utf8_decode($membro->nomeMembro). " - PRESIDENTE",0, 'R');
		else
    		$pdf->MultiCell(0,9,"_____________________________________________
            ".utf8_decode($membro->nomeMembro). " - MEMBRO",0, 'R');
	}

	$pdf->AddPage();
	$pdf->SetFont("Helvetica",'B', 14);
	$pdf->MultiCell(0,8,'AVALIA��O DE PROPOSTA DE '.$complemento,0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->SetFont("Helvetica",'B', 12);
    // DADOS DO ALUNO
	$pdf->MultiCell(0,10,"PARECER:",0, 'J');
	$pdf->Rect(10, 70, 190, 120, "D");
	$pdf->SetXY(10,190);
	$pdf->SetFont("Helvetica",'', 12);
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
   	$pdf->MultiCell(0,10,"___________________________________         ___________________________________",0, 'C');
   	$pdf->MultiCell(0,10,"Assinatura do(a) Orientador(a)                                                 Assinatura do(a) Discente",0, 'C');

	$pdf->SetFont("Helvetica",'B', 12);
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,10,"Obs.: Anexar PROPOSTA a ser apresentada",0, 'C');

	ob_clean(); // Limpa o buffer de sa�da
	//cria o arquivo pdf e exibe no navegador
	$pdf->Output('components/com_portalsecretaria/defesas/$chave.pdf','I');
	exit;

}

///////////////////////////////

function imprimirFolhaAprovacao($idAluno, $tipoDefesa) {


	require('fpdf/fpdf.php');
	//configura��es iniciais

	class PDF extends FPDF
	{
		function Header()
		{
			$this->Image('components/com_portalsecretaria/images/logo-brasil.jpg', 10, 7, 32.32);
			$this->Image('components/com_portalsecretaria/images/ufam.jpg', 175, 7, 25.25);

			//exibe o cabecalho do documento
			$this->SetFont("Helvetica",'B', 12);
			$this->MultiCell(0,5,"PODER EXECUTIVO",0, 'C');
			$this->SetFont("Helvetica",'B', 10);
			$this->MultiCell(0,5,"MINIST�RIO DA EDUCA��O",0, 'C');
			$this->MultiCell(0,5,"INSTITUTO DE COMPUTA��O",0, 'C');
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,5,"PROGRAMA DE P�S-GRADUA��O EM INFORM�TICA",0, 'C');
			$this->SetDrawColor(0,0,0);
			$this->Line(10,42,200,42);
			$this->ln( 7 );
		}

		function Footer()
		{
			$this->Line(10,285,200,285);
			$this->SetFont('Helvetica','I',8);
			$this->SetXY(10, 281);
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,4,"Av. Rodrigo Ot�vio, 6.200 - Campus Universit�rio Senador Arthur Virg�lio Filho - CEP 69077-000 - Manaus, AM, Brasil",0, 'C');
			$this->MultiCell(0,4," Tel. (092) 3305 1193         E-mail: secretariappgi@icomp.ufam.edu.br          www.ppgi.ufam.edu.br",0, 'C');
			$this->Image('components/com_portalsecretaria/images/icon_telefone.jpg', '40', '290');
			$this->Image('components/com_portalsecretaria/images/icon_email.jpg', '73', '290');
			$this->Image('components/com_portalsecretaria/images/icon_casa.jpg', '134', '290');
		}
	}
	$database = & JFactory :: getDBO();

	$curso = array (
		'Q' => 'DISSERTA&Ccedil;&Atilde;O',
		'T' => 'TESE'
	);

	$mes = array (
		"01" => "Janeiro",
		"02" => "Fevereiro",
		"03" => "Mar�o",
		"04" => "Abril",
		"05" => "Maio",
		"06" => "Junho",
		"07" => "Julho",
		"08" => "Agosto",
		"09" => "Setembro",
		"10" => "Outubro",
		"11" => "Novembro",
		"12" => "Dezembro"
	);

	$sql = "SELECT id, UPPER(nome) as nome, numDefesa, UPPER(tituloQual2) as tituloQual2, dataQual2, localQual2, horarioQual2, resumoQual2, UPPER(tituloTese) as tituloTese, dataTese, horarioTese, localTese, resumoTese, curso FROM #__aluno WHERE id = $idAluno";
	$database->setQuery($sql);
	$alunos = $database->loadObjectList();

	$sql = "SELECT idAluno, nomeMembro, instituicaoMembro, funcao FROM #__banca WHERE idAluno = $idAluno AND tipoDefesa = '$tipoDefesa' ORDER BY funcao DESC";
	$database->setQuery($sql);
	$banca = $database->loadObjectList();

	$chave = md5($alunos[0]->id . $alunos[0]->nome . date("l jS \of F Y h:i:s A"));

	//$pdf = new FPDF('P','cm','A4');
	$pdf = new PDF();
	$pdf->Open();
	$pdf->AddPage();

	//titulos de configura��o do documento
	$pdf->SetTitle("Folha de Aprova��o");

	// OBTENDO OS DADOS A SEREM PREENCHIDOS

	if ($tipoDefesa == 'Q') {
		$data = explode("/",$alunos[0]->dataQual2);
		$hora = $alunos[0]->horarioQual2;
		$local = utf8_decode($alunos[0]->localQual2);
		$titulo = utf8_decode($alunos[0]->tituloQual2);
		if ($alunos[0]->curso == 2){
			$complemento = "Exame de Qualifica��o de Doutorado";
		}
		else{
			$complemento = "Exame de Qualifica��o de Mestrado";
		}
	}
	else{
		$data = explode("/",$alunos[0]->dataTese);
		$titulo = utf8_decode($alunos[0]->tituloTese);
		if ($alunos[0]->curso == 2){
			$complemento = "Tese de Doutorado";
		}
		else{
			$complemento = "Diesserta��o de Mestrado";
		}
	}
	$membrosBanca = "";
	

	// OBTENDO OS DADOS A SEREM PREENCHIDOS

	$pdf->SetFont("Helvetica",'B', 14);
	$pdf->MultiCell(0,8,'FOLHA DE APROVA��O',0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,10,"'".$titulo."'",0, 'C');
	$pdf->MultiCell(0,7,"",0, 'C');
	$pdf->MultiCell(0,8,utf8_decode($alunos[0]->nome),0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->MultiCell(0,5,"",0, 'C');

	$tag = $complemento." defendida e aprovada pela banca examinadora contitu�da pelos Professores:";

	$pdf->SetFont("Helvetica",'', 12);
	$pdf->MultiCell(0,8,$tag,0, 'J');
	$pdf->MultiCell(0,6,"",0, 'C');
	$pdf->MultiCell(0,6,"",0, 'C');
	foreach ($banca as $membro) {
		if ($membro->funcao == "P")
        	$pdf->MultiCell(0,10,utf8_decode($membro->nomeMembro). " - PRESIDENTE",0, 'J');
		else if ($membro->funcao == "I")
    		$pdf->MultiCell(0,10,utf8_decode($membro->nomeMembro). " - MEMBRO INTERNO",0, 'J');
		else if ($membro->funcao == "E")
    		$pdf->MultiCell(0,10,utf8_decode($membro->nomeMembro). " - MEMBRO EXTERNO",0, 'J');
      	$pdf->MultiCell(0,7,"",0, 'C');
      	$pdf->MultiCell(0,6,"",0, 'C');
	}
	$pdf->MultiCell(0,5,"",0, 'C');
	$pdf->SetFont("Helvetica",'', 10);
	$pdf->MultiCell(0,5,"Manaus, ".$data[0]." de ". $mes[$data[1]]." de ".$data[2],0, 'C');

	ob_clean(); // Limpa o buffer de sa�da
	//cria o arquivo pdf e exibe no navegador
	$pdf->Output('components/com_portalsecretaria/defesas/$chave.pdf','I');
	exit;

}

///////////////////////////////

function imprimirAgradecimento($idMembro, $tipoDefesa) {

	require('fpdf/fpdf.php'); 
	//configura��es iniciais
	
	class PDF extends FPDF
	{	
		function Header()
		{
			$this->Image('components/com_portalsecretaria/images/logo-brasil.jpg', 10, 7, 32.32);
			$this->Image('components/com_portalsecretaria/images/ufam.jpg', 175, 7, 25.25);
	
			//exibe o cabecalho do documento
			$this->SetFont("Helvetica",'B', 12);
			$this->MultiCell(0,5,"PODER EXECUTIVO",0, 'C');
			$this->SetFont("Helvetica",'B', 10);
			$this->MultiCell(0,5,"MINIST�RIO DA EDUCA��O",0, 'C');
			$this->MultiCell(0,5,"INSTITUTO DE COMPUTA��O",0, 'C');
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,5,"PROGRAMA DE P�S-GRADUA��O EM INFORM�TICA",0, 'C');
			$this->SetDrawColor(0,0,0);
			$this->Line(10,42,200,42);
			$this->ln( 7 ); 			
		}

		function Footer()
		{
			$this->Line(10,285,200,285);
			$this->SetFont('Helvetica','I',8);
			$this->SetXY(10, 281);
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,4,"Av. Rodrigo Ot�vio, 6.200 - Campus Universit�rio Senador Arthur Virg�lio Filho - CEP 69077-000 - Manaus, AM, Brasil",0, 'C');
			$this->MultiCell(0,4," Tel. (092) 3305 1193         E-mail: secretariappgi@icomp.ufam.edu.br          www.ppgi.ufam.edu.br",0, 'C');
			$this->Image('components/com_portalsecretaria/images/icon_telefone.jpg', '40', '290');
			$this->Image('components/com_portalsecretaria/images/icon_email.jpg', '73', '290');
			$this->Image('components/com_portalsecretaria/images/icon_casa.jpg', '134', '290');
		}
	}
	$database = & JFactory :: getDBO();

	$curso = array (
		'Q' => 'DISSERTA&Ccedil;&Atilde;O',
		'T' => 'TESE'
	);
	
	$mes = array (
		"01" => "Janeiro",
		"02" => "Fevereiro",
		"03" => "Mar�o",
		"04" => "Abril",
		"05" => "Maio",
		"06" => "Junho",
		"07" => "Julho",
		"08" => "Agosto",
		"09" => "Setembro",
		"10" => "Outubro",
		"11" => "Novembro",
		"12" => "Dezembro"
	);

	$sql = "SELECT idAluno, nomeMembro, instituicaoMembro, funcao FROM #__banca WHERE id = $idMembro";
	$database->setQuery($sql);
	$banca = $database->loadObjectList();

	$sql = "SELECT id, UPPER(nome) as nome, numDefesa, UPPER(tituloQual2) as tituloQual2, dataQual2, localQual2, horarioQual2, resumoQual2, UPPER(tituloTese) as tituloTese, dataTese, horarioTese, localTese, resumoTese, curso FROM #__aluno WHERE id = ".$banca[0]->idAluno;
	$database->setQuery($sql);
	$alunos = $database->loadObjectList();

	$chave = md5($alunos[0]->id . $alunos[0]->nome . date("l jS \of F Y h:i:s A"));

	//$pdf = new FPDF('P','cm','A4');
	$pdf = new PDF();
	$pdf->Open();
	$pdf->AddPage();

	//titulos de configura��o do documento
	$pdf->SetTitle("Agradecimentos");
	
	// OBTENDO OS DADOS A SEREM PREENCHIDOS
	
	$nome = utf8_decode($alunos[0]->nome);	
	if ($tipoDefesa == 'Q') {	
		$data = explode("/",$alunos[0]->dataQual2);
		$titulo = utf8_decode($alunos[0]->tituloQual2);
		$hora = $alunos[0]->horarioQual2;
		if ($alunos[0]->curso == 2){
			$complemento = "Doutorado";
			$complemento2 = "Exame de Qualifica��o de Doutorado";
		}
		else{
			$complemento = "Mestrado";
			$complemento2 = "Exame de Qualifica��o de Mestrado";
		}
	}
	else{
		$data = explode("/",$alunos[0]->dataTese);	
		$titulo = utf8_decode($alunos[0]->tituloTese);
		$hora = $alunos[0]->horarioTese;
		if ($alunos[0]->curso == 2){
			$complemento2 = "Tese de Doutorado";
			$complemento = "Doutorado";
		}
		else{
			$complemento2= "Diesserta��o de Mestrado";
			$complemento = "Mestrado";
		}
	}
	// OBTENDO OS DADOS A SEREM PREENCHIDOS
	
	$pdf->SetFont("Helvetica",'B', 14);
	$pdf->MultiCell(0,7,"",0, 'C');
	$pdf->MultiCell(0,5,'AGRADECIMENTO',0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');
	
	if ($banca[0]->funcao == "P")
        	$participacao = "presidente/orientador(a)";
	else if ($banca[0]->funcao == "I")
        	$participacao = "membro interno";
	else if ($banca[0]->funcao == "E")
        	$participacao = "membro externo";

	$tag = "AGRADECEMOS a participa��o do(a) ".utf8_decode($banca[0]->nomeMembro)." como ".$participacao." da banca examinadora referente � apresenta��o da Defesa de ".$complemento2." do(a) aluno(a), abaixo especificado(a), do curso de ".$complemento." em Inform�tica do Programa de P�s-Gradua��o em Inform�tica da Universidade Federal do Amazonas � realizada no dia ".$data[0]." de ".$mes[$data[1]]." de ".$data[2]." �s ".$hora.".";

	$pdf->SetFont("Helvetica",'', 12);
	$pdf->MultiCell(0,10,$tag,0, 'J');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->MultiCell(0,8,'T�tulo: '.$titulo,0, 'J');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->MultiCell(0,8,'Aluno(a): '.$nome,0, 'J');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');	
	$pdf->SetFont("Helvetica",'', 10);
	$pdf->MultiCell(0,8,"Manaus, ".$data[0]." de ". $mes[$data[1]]." de ".$data[2],0, 'C');	

	ob_clean(); // Limpa o buffer de sa�da
	//cria o arquivo pdf e exibe no navegador
	$pdf->Output('components/com_portalsecretaria/defesas/$chave.pdf','I');
	exit;
	
}

///////////////////////////////

function imprimirDeclaracaoBanca($idMembro, $tipoDefesa) {

	require('fpdf/fpdf.php'); 
	//configura��es iniciais
	
	class PDF extends FPDF
	{	
		function Header()
		{
			$this->Image('components/com_portalsecretaria/images/logo-brasil.jpg', 10, 7, 32.32);
			$this->Image('components/com_portalsecretaria/images/ufam.jpg', 175, 7, 25.25);
	
			//exibe o cabecalho do documento
			$this->SetFont("Helvetica",'B', 12);
			$this->MultiCell(0,5,"PODER EXECUTIVO",0, 'C');
			$this->SetFont("Helvetica",'B', 10);
			$this->MultiCell(0,5,"MINIST�RIO DA EDUCA��O",0, 'C');
			$this->MultiCell(0,5,"INSTITUTO DE COMPUTA��O",0, 'C');
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,5,"PROGRAMA DE P�S-GRADUA��O EM INFORM�TICA",0, 'C');
			$this->SetDrawColor(0,0,0);
			$this->Line(10,42,200,42);
			$this->ln( 7 ); 			
		}

		function Footer()
		{
			$this->Line(10,285,200,285);
			$this->SetFont('Helvetica','I',8);
			$this->SetXY(10, 281);
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,4,"Av. Rodrigo Ot�vio, 6.200 - Campus Universit�rio Senador Arthur Virg�lio Filho - CEP 69077-000 - Manaus, AM, Brasil",0, 'C');
			$this->MultiCell(0,4," Tel. (092) 3305 1193         E-mail: secretariappgi@icomp.ufam.edu.br          www.ppgi.ufam.edu.br",0, 'C');
			$this->Image('components/com_portalsecretaria/images/icon_telefone.jpg', '40', '290');
			$this->Image('components/com_portalsecretaria/images/icon_email.jpg', '73', '290');
			$this->Image('components/com_portalsecretaria/images/icon_casa.jpg', '134', '290');
		}
	}
	$database = & JFactory :: getDBO();

	$mes = array (
		"01" => "Janeiro",
		"02" => "Fevereiro",
		"03" => "Mar�o",
		"04" => "Abril",
		"05" => "Maio",
		"06" => "Junho",
		"07" => "Julho",
		"08" => "Agosto",
		"09" => "Setembro",
		"10" => "Outubro",
		"11" => "Novembro",
		"12" => "Dezembro"
	);

	$sql = "SELECT idAluno, nomeMembro, instituicaoMembro, funcao FROM #__banca WHERE id = $idMembro";
	$database->setQuery($sql);
	$banca = $database->loadObjectList();

	$sql = "SELECT id, UPPER(nome) as nome, numDefesa, tituloQual2, dataQual2, localQual2, horarioQual2, resumoQual2, tituloTese, dataTese, horarioTese, localTese, resumoTese, curso FROM #__aluno WHERE id = ".$banca[0]->idAluno;
	$database->setQuery($sql);
	$alunos = $database->loadObjectList();


	$chave = md5($alunos[0]->id . $alunos[0]->nome . date("l jS \of F Y h:i:s A"));

	//$pdf = new FPDF('P','cm','A4');
	$pdf = new PDF();
	$pdf->Open();
	$pdf->AddPage();

	//titulos de configura��o do documento
	$pdf->SetTitle("Declara��o");
	
	// OBTENDO OS DADOS A SEREM PREENCHIDOS
	
	$nome = utf8_decode($alunos[0]->nome);	
	if ($tipoDefesa == 'Q') {	
		$data = explode("/",$alunos[0]->dataQual2);
		$titulo = utf8_decode($alunos[0]->tituloQual2);
		$hora = $alunos[0]->horarioQual2;
		$complemento2 = "exame de qualifica��o";
		if ($alunos[0]->curso == 2){
			$complemento = "Doutorado";
		}
		else{
			$complemento = "Mestrado";
		}
	}
	else{
		$data = explode("/",$alunos[0]->dataTese);	
		$titulo = utf8_decode($alunos[0]->tituloTese);
		$hora = $alunos[0]->horarioTese;
		if ($alunos[0]->curso == 2){
			$complemento = "Doutorado";
			$complemento2 = "tese";			
		}
		else{
			$complemento = "Mestrado";
			$complemento2 = "disserta��o";			
		}
	}
	// OBTENDO OS DADOS A SEREM PREENCHIDOS
	
	$pdf->SetFont("Helvetica",'B', 16);
	$pdf->MultiCell(0,7,"",0, 'C');
	$pdf->MultiCell(0,5,'DECLARA��O',0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');
	$pdf->MultiCell(0,8,"",0, 'C');
	
	if ($banca[0]->funcao == "P")
        	$participacao = "presidente/orientador(a)";
	else if ($banca[0]->funcao == "I")
        	$participacao = "membro interno";
	else if ($banca[0]->funcao == "E")
        	$participacao = "membro externo";

	$tag = "DECLARAMOS para os devidos fins que o(a) ".utf8_decode($banca[0]->nomeMembro)." fez parte, na qualidade de ".$participacao.", da comiss�o julgadora da defesa de ".$complemento2." do(a) aluno(a) ".$nome.", intitulada �".$titulo."�, do curso de ".$complemento." em Inform�tica do Programa de P�s-Gradua��o em Inform�tica da Universidade Federal do Amazonas, realizada no dia ".$data[0]." de ".$mes[$data[1]]." de ".$data[2]." �s ".$hora.".";

	$pdf->SetFont("Helvetica",'', 12);
	$pdf->MultiCell(0,10,$tag,0, 'J');

	ob_clean(); // Limpa o buffer de sa�da
	//cria o arquivo pdf e exibe no navegador
	$pdf->Output('components/com_portalsecretaria/defesas/$chave.pdf','I');
	exit;

}
///////////////////////////////////////////////
function salvarBanca($idAluno) {

	$database = & JFactory :: getDBO();

	$idiomaProf = $_POST['idiomaExameProf'];
	$dataProf = $_POST['dataExameProf'];
	//$dataProf = dateToStr($dataProf);
	$conceitoProf = $_POST['conceitoExameProf'];
	$aluno = identificarAlunoID($idAluno);
	$tituloQual2 = $_POST['tituloQual2'];
	$dataQual2 = $_POST['dataQual2'];
	//$dataQual2 = dateToStr($dataQual2);
	$horarioQual2 = $_POST['horarioQual2'];
	$localQual2 = $_POST['localQual2'];
	$resumoQual2 = addslashes($_POST['resumoQual2']);
	$conceitoQual2 = $_POST['conceitoQual2'];
	$tituloTese = $_POST['tituloTese'];
	$dataTese = $_POST['dataTese'];
	//$dataTese = dateToStr($dataTese);
	$numDefesa = $_POST['numDefesa'];
	$horarioTese = $_POST['horarioTese'];
	$localTese = $_POST['localTese'];
	$resumoTese = addslashes($_POST['resumoTese']);
	$conceitoTese = $_POST['conceitoTese'];

	if($aluno->curso == 2){
		$tituloQual1 = $_POST['tituloQual1'];
		$dataQual1 = $_POST['dataQual1'];
		$conceitoQual1 = $_POST['conceitoQual1'];
		$examinadorQual1 = $_POST['examinadorQual1'];
		
		$sql = "UPDATE #__aluno SET idiomaExameProf='$idiomaProf', numDefesa = '$numDefesa', dataExameProf='$dataProf', conceitoExameProf='$conceitoProf', tituloQual1='$tituloQual1', dataQual1='$dataQual1', examinadorQual1='$examinadorQual1', conceitoQual1='$conceitoQual1', tituloQual2='$tituloQual2', dataQual2='$dataQual2', horarioQual2='$horarioQual2', localQual2='$localQual2', resumoQual2='$resumoQual2', conceitoQual2='$conceitoQual2', tituloTese='$tituloTese', dataTese='$dataTese', horarioTese='$horarioTese', localTese='$localTese', resumoTese='$resumoTese', conceitoTese='$conceitoTese' WHERE id = $idAluno";
	}
	else{
		$sql = "UPDATE #__aluno SET idiomaExameProf='$idiomaProf', numDefesa = '$numDefesa', dataExameProf='$dataProf', conceitoExameProf='$conceitoProf', tituloQual2='$tituloQual2', dataQual2='$dataQual2', horarioQual2='$horarioQual2', localQual2='$localQual2', resumoQual2='$resumoQual2', conceitoQual2='$conceitoQual2', tituloTese='$tituloTese', dataTese='$dataTese', horarioTese='$horarioTese', localTese='$localTese', resumoTese='$resumoTese', conceitoTese='$conceitoTese' WHERE id = $idAluno";
	}

	$database->setQuery($sql);
	$database->Query();
	JFactory :: getApplication()->enqueueMessage(JText :: _('Dados salvos com sucesso.'));
}

////////////////////////////////////////////////////////////////////////////////

function adicionarMembroBanca($idAluno, $tipo) {

	$database = & JFactory :: getDBO();

	if ($tipo == 'Q') {
		$nome = $_POST['nomeQual'];
		$instituicao = $_POST['instituicaoQual'];
		$funcao = $_POST['funcaoQual'];
	}
	if ($tipo == 'T') {
		$nome = $_POST['nomeTese'];
		$instituicao = $_POST['instituicaoTese'];
		$funcao = $_POST['funcaoTese'];
	}

	$sql = "INSERT INTO #__banca (idAluno, nomeMembro, instituicaoMembro, funcao, tipoDefesa) VALUES ($idAluno, '$nome', '$instituicao', '$funcao', '$tipo')";
	$database->setQuery($sql);
	$database->Query();
}

////////////////////////////////////////////////////////////////////////////////

function removerMembroBanca($idAluno) {

	$database = & JFactory :: getDBO();

	$id = JRequest :: getCmd('id', false);

	$sql = "DELETE FROM #__banca WHERE id = $id";
	$database->setQuery($sql);
	$database->Query();
}

function strToDate($data){
	if($data){
		$novadata = explode("/", $data);
		return($novadata[2]."-".$novadata[1]."-".$novadata[0]);
	}
	return($data);
}

function dateToStr($data){
	
	if($data){
		$novadata = explode("-", $data);
		return($novadata[2]."/".$novadata[1]."/".$novadata[0]);
	}
	return($data);
}

?>
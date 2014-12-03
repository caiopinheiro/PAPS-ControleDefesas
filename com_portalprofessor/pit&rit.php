<?php
function listarPITs($professor) {
    $Itemid = JRequest::getInt('Itemid', 0);
    $database	=& JFactory::getDBO();

    $sql = "SELECT id, idPeriodo, periodo, titulacao, classe, nivel, turno, aberto, DATE_FORMAT(dataEnvio,'%d/%m/%Y') as dataEnvio FROM #__pits WHERE idProf = ".$professor->id." ORDER BY periodo DESC";
    $database->setQuery( $sql );
    $pits = $database->loadObjectList(); ?>
    
	<script language="JavaScript">
		function confirmarExclusao() {
			var confirmar = confirm('Confirmar a exclusao?');
	
			return confirmar;
		}
	</script>
    
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();
		});
	</script>
    
	<link rel="stylesheet" href="components/com_portalsecretaria/assets/css/estilo.css" type="text/css" />
    
    <form method="post" name="form" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
    
	<h3>Plano Invididual de Trabalho - PIT</h3>
    <hr />
    
    <table width="100%">
        <tr>
            <th id="cpanel" width="30%">
                <div class="icon" style='text-align: center;'><a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=addpit">
                 <img src="components/com_portalsecretaria/images/listarinscritos.jpg" width="32" height="32" border="0"><br><b>Novo PIT</b>
                </a></div>
            </th>
            <th id="cpanel" width="30%">
                <div class="icon" style='text-align: center;'><a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
                 <img src="components/com_portalsecretaria/images/voltar.png" border="0"><br><b>Voltar</b>
                </a></div>
            </th>
    	</tr>
	</table>
    
    <table class="table table-striped" id="tablesorter-imasters">
        <thead>
          <tr>
            <th></th>
            <th></th>
            <th>Período</th>
            <th>Data de Criação/Envio</th>
            <th>Titulação</th>
            <th>Classe</th>
            <th>Nível</th>
            <th>Turno</th>
          </tr>
        </thead>
        
		<tbody>
		<?php
    	if($pits) {
			foreach($pits as $pit) { ?>

			<tr>
                <td width='16'><div align="center">
                    <?php if(!$pit->aberto){ ?> <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=verpit&periodo=<?php echo $pit->idPeriodo;?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;"><img border='0' src='images/b_view.png' width='16' height='16' title='visualizar'></a><?php } ?>
                    <?php if($pit->aberto){ ?>
                       <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=editpit1&periodo=<?php echo $pit->idPeriodo;?>"><img border='0' src='components/com_portalsecretaria/images/editar.gif' width='16' height='16' title='editar'></a>
                    <?php } ?>
                </div></td>
                <td width='16'><div align="center">
                    <?php if($pit->aberto){ ?>
                       <a href="javascript:if(confirmarExclusao()){document.form.task.value='removepit';javascript:document.form.id.value='<?php echo $pit->id;?>';document.form.submit()}"><img border='0' src='components/com_portalsecretaria/images/lixeira.gif' width='16' height='16' title='remover'></a>
                    <?php } ?>
                </div></td>
                <td><?php echo $pit->periodo;?></td>
                <td><?php echo $pit->dataEnvio;?></td>
                <td><?php echo $pit->titulacao;?></td>
                <td><?php echo $pit->classe;?></td>
                <td><?php echo $pit->nivel;?></td>
                <td><?php echo $pit->turno;?></td>
            </tr>
			<?php }
		}
        ?>
		</tbody>
	</table>
    
    <input name='task' type='hidden' value='' />
    <input name='id' type='hidden' value='' />
	</form>

<?php }
 
///////////////////////////////////////////////////////////////

function editarPIT1($professor, $pit = NULL)
    {

    $Itemid = JRequest::getInt('Itemid', 0);
    $database	=& JFactory::getDBO();

	?>

    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
    <?php cabecalho($pit);?>
    <p><h3>Dados do Professor</h3></p>
    <table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
      <tr>
    	<td bgcolor="#D0D0D0" width="15%"><b>Nome: </b></td>
    	<td width="40%"><?php echo $professor->nomeProfessor;?></td>
        <td bgcolor="#D0D0D0" width="15%"><b>Titula&#231;&#227;o: </b></td>
    	<td width="30%"><?php echo $pit->titulacao;?></td>
      </tr>
      <tr>
    	<td bgcolor="#D0D0D0"><b>Unidade: </b></td>
    	<td><?php echo $pit->unidade;?></td>
        <td bgcolor="#D0D0D0"><b>Classe/N&#237;vel: </b></td>
    	<td><?php echo $pit->classe." - ".$pit->nivel;?></td>
      </tr>
      <tr>
    	<td bgcolor="#D0D0D0"><b>Ano de Ingresso: </b></td>
    	<td><?php echo $professor->dataIngresso;?></td>
        <td bgcolor="#D0D0D0"><b>SIAPE: </b></td>
    	<td><?php echo $professor->SIAPE;?></td>
      </tr>
      <tr>
        <td bgcolor="#D0D0D0"><b>Regime/Turno de Trabalho: </b></td>
    	<td><?php echo $pit->regime." - ".$pit->turno;?></td>
    	<td bgcolor="#D0D0D0"><b>Per&#237;odo: </b></td>
    	<td><?php echo $pit->periodo;?>
        </td>
      </tr>
    </table>
    <?php rodape();?>
     <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/template_css.css">
     <input name='task' type='hidden' value='salvarbanca'>
     <input name='idPIT' type='hidden' value='<?php echo $pit->id;?>'>
     <input name='id' type='hidden' value=''>
     <input name='periodo' type='hidden' value='<?php echo $pit->idPeriodo;?>'>
     </form>

    <?php
    }

function editarPIT2($professor, $pit = NULL)
    {

    $Itemid = JRequest::getInt('Itemid', 0);
    $database	=& JFactory::getDBO();

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

function IsNumeric(sText)

{
   var ValidChars = "0123456789,";
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

function verificaAula(form)
{

   if(IsEmpty(form.codigo))
   {
      alert(unescape('O campo CODIGO deve ser preenchido.'))
      form.codigo.focus();
      return false;
   }

   if(IsEmpty(form.nomeDisciplina))
   {
      alert(unescape('O campo NOME DISCIPLINA deve ser preenchido.'))
      form.nomeDisciplina.focus();
      return false;
   }

   if(IsEmpty(form.creditos))
   {
      alert(unescape('O campo CREDITOS deve ser preenchido.'))
      form.creditos.focus();
      return false;
   }
   if(IsEmpty(form.turma))
   {
      alert(unescape('O campo TURMA deve ser preenchido.'))
      form.turma.focus();
      return false;
   }
   if(IsEmpty(form.horas) || (!IsEmpty(form.horas) && !IsNumeric(form.horas.value)))
   {
      alert(unescape('O campo HORAS SEMANAIS deve ser preenchido com valores inteiros.'))
      form.horas.focus();
      return false;
   }
   if(IsEmpty(form.prevAlunos) || (!IsEmpty(form.prevAlunos) && !IsNumeric(form.prevAlunos.value)))
   {
      alert(unescape('O campo PREVISAO DE ALUNOS deve ser preenchido com valores inteiros.'))
      form.prevAlunos.focus();
      return false;
   }
   if(IsEmpty(form.totAlunos) || (!IsEmpty(form.totAlunos) && !IsNumeric(form.totAlunos.value)))
   {
      alert(unescape('O campo TOTAL DE ALUNOS deve ser preenchido com valores inteiros.'))
      form.totAlunos.focus();
      return false;
   }

return true;

}

function confirmarExclusao()
{
    var confirmar = confirm('Confirmar a exclusao?');

    return confirmar;

}

//-->
</script>


	<link rel="stylesheet" href="components/com_portalsecretaria/estilo.css" type="text/css" />

        <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
        <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>

	<script type="text/javascript">
	$(function() {
		$("#tablesorter-imasters").tablesorter();

	});
	</script>

    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
    <?php cabecalho($pit);?>
	<h3>Ministra&#231;&#227;o de Aulas</h3>
    <table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
	<thead>
      <tr bgcolor="#002666">
    	<th></th>
    	<th width="15%" align="center"><b><font color="#FFFFFF">C&#243;digo</font></b></th>
    	<th width="35%" align="center"><b><font color="#FFFFFF">Disciplina</font></b></th>
    	<th width="5%" align="center"><b><font color="#FFFFFF">Cred.</font></b></th>
    	<th width="5%" align="center"><b><font color="#FFFFFF">Turma</font></b></th>
    	<th width="10%" align="center"><b><font color="#FFFFFF">H. Sem</font></b></th>
    	<th width="10%" align="center"><b><font color="#FFFFFF">Prev. Alunos</font></b></th>
    	<th width="10%" align="center"><b><font color="#FFFFFF">Num. Alunos</font></b></th>
      </tr>
     </thead>
     <tbody>
	<?php

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";
	$presidente = array (0 => "N&Atilde;O",1 => "SIM");

    $sql = "SELECT * FROM #__pits_aula WHERE idPit = $pit->id ORDER BY codigo";
    $database->setQuery( $sql );
    $aulas = $database->loadObjectList();

	$i = 0;
	foreach( $aulas as $aula )
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

		<td width='16' align="center">
            <a href="javascript:if(confirmarExclusao()){document.form.task.value='removeAulaPit';javascript:document.form.id.value='<?php echo $aula->id;?>';document.form.submit()}"> <img src="components/com_portalsecretaria/images/excluir.png" border="0" title='excluir'></a>
			</a>
		</td>
		<td><?php echo $aula->codigo;?></td>
		<td><?php echo $aula->nomeDisciplina;?></td>
		<td><?php echo $aula->creditos;?></td>
		<td><?php echo $aula->turma;?></td>
		<td><?php echo $aula->horas;?></td>
		<td><?php echo $aula->prevAlunos;?></td>
		<td><?php echo $aula->totAlunos;?></td>
	</tr>

	<?php
	}
     ?>
     <tr>
		<td width='16' align="center">
            <a href="javascript:if(verificaAula(document.form)){document.form.task.value='addAulaPit';document.form.submit()}"> <img src="components/com_portalsecretaria/images/adicionar.jpg" border="0" title='adicionar'></a>
			</a>
		</td>
		<td><input name="codigo" type="text" maxlength="10" class="inputbox" size="10"></td>
		<td><input name="nomeDisciplina" type="text" maxlength="60" class="inputbox" size="30"></td>
		<td><input name="creditos" type="text" maxlength="6" class="inputbox" size="6"></td>
		<td><input name="turma" type="text" maxlength="3" class="inputbox" size="3"></td>
		<td><input name="horas" type="text" maxlength="2" class="inputbox" size="2"></td>
		<td><input name="prevAlunos" type="text" maxlength="2" class="inputbox" size="2"></td>
		<td><input name="totAlunos" type="text" maxlength="2" class="inputbox" size="2"></td>
	</tr>
    </tbody>
    </table>
    <?php rodape();?>
    <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/template_css.css">
    <input name='task' type='hidden' value='salvarbanca'>
    <input name='idPIT' type='hidden' value='<?php echo $pit->id;?>'>
    <input name='id' type='hidden' value=''>
    <input name='periodo' type='hidden' value='<?php echo $pit->idPeriodo;?>'>
    </form>

    <?php
    }

function editarPIT3($professor, $pit = NULL)
    {

    $Itemid = JRequest::getInt('Itemid', 0);
    $database	=& JFactory::getDBO();

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

function verificaHorario(form)
{

   if(IsEmpty(form.disciplina))
   {
      alert(unescape('O campo DISCIPLINA deve ser preenchido.'))
      form.disciplina.focus();
      return false;
   }

   if(IsEmpty(form.segunda) && IsEmpty(form.sexta) && IsEmpty(form.terca) && IsEmpty(form.quarta) && IsEmpty(form.quinta) && IsEmpty(form.sabado))
   {
      alert(unescape('Ao menos um dia da semana deve ser preenchido.'))
      form.segunda.focus();
      return false;
   }

   if(IsEmpty(form.atividade))
   {
      alert(unescape('O campo ATIVIDADE deve ser preenchido.'))
      form.atividade.focus();
      return false;
   }

return true;

}

function confirmarExclusao()
{
    var confirmar = confirm('Confirmar a exclusao?');

    return confirmar;

}

//-->
</script>


	<link rel="stylesheet" href="components/com_portalsecretaria/estilo.css" type="text/css" />

        <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
        <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>

	<script type="text/javascript">
	$(function() {
		$("#tablesorter-imasters").tablesorter();

	});
	</script>

    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
    <?php cabecalho($pit);?>
	<h3>Hor&#225;rio das Disciplinas e de Atendimentos a Alunos</h3>
    <table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
	<thead>
      <tr bgcolor="#002666">
    	<th></th>
    	<th width="19%" align="center"><b><font color="#FFFFFF">Disciplina</font></b></th>
    	<th width="11%" align="center"><b><font color="#FFFFFF">Seg</font></b></th>
    	<th width="11%" align="center"><b><font color="#FFFFFF">Ter</font></b></th>
    	<th width="11%" align="center"><b><font color="#FFFFFF">Qua</font></b></th>
    	<th width="11%" align="center"><b><font color="#FFFFFF">Qui</font></b></th>
    	<th width="11%" align="center"><b><font color="#FFFFFF">Sex</font></b></th>
    	<th width="11%" align="center"><b><font color="#FFFFFF">S&#225;b</font></b></th>
    	<th width="15%" align="center"><b><font color="#FFFFFF">Atividade</font></b></th>
      </tr>
     </thead>
     <tbody>
	<?php

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";
	$presidente = array (0 => "N&Atilde;O",1 => "SIM");

    $sql = "SELECT * FROM #__pits_horarios WHERE idPit = $pit->id ORDER BY nomeDisciplina";
    $database->setQuery( $sql );
    $aulas = $database->loadObjectList();

	$i = 0;
	foreach( $aulas as $aula )
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

		<td width='16' align="center">
            <a href="javascript:if(confirmarExclusao()){document.form.task.value='removeHorPit';javascript:document.form.id.value='<?php echo $aula->id;?>';document.form.submit()}"> <img src="components/com_portalsecretaria/images/excluir.png" border="0" title='excluir'></a>
			</a>
		</td>
		<td><?php echo $aula->nomeDisciplina;?></td>
		<td><?php echo $aula->segunda;?></td>
		<td><?php echo $aula->terca;?></td>
		<td><?php echo $aula->quarta;?></td>
		<td><?php echo $aula->quinta;?></td>
		<td><?php echo $aula->sexta;?></td>
		<td><?php echo $aula->sabado;?></td>
		<td><?php echo $aula->atividade;?></td>
	</tr>

	<?php
	}
     ?>
     <tr>
		<td width='16' align="center">
            <a href="javascript:if(verificaHorario(document.form)){document.form.task.value='addHorPit';document.form.submit()}"> <img src="components/com_portalsecretaria/images/adicionar.jpg" border="0" title='adicionar'></a>
			</a>
		</td>
		<td><select class="inputbox" name="disciplina"><option value=''></option>
        <?php
          $sql = "SELECT DISTINCT(codigo) as codigo, nomeDisciplina FROM #__pits_aula WHERE idPit = $pit->id ORDER BY nomeDisciplina";
          $database->setQuery( $sql );
          $discs = $database->loadObjectList();

	      $i = 0;
	      foreach( $discs as $disc )
	      {
              echo "<option value='$disc->codigo: $disc->nomeDisciplina'>$disc->codigo: $disc->nomeDisciplina</option>";
          }
        ?>
        </select></td>
		<td><input name="segunda" type="text" maxlength="10" class="inputbox" size="5"></td>
		<td><input name="terca" type="text" maxlength="10" class="inputbox" size="5"></td>
		<td><input name="quarta" type="text" maxlength="10" class="inputbox" size="5"></td>
		<td><input name="quinta" type="text" maxlength="10" class="inputbox" size="5"></td>
		<td><input name="sexta" type="text" maxlength="10" class="inputbox" size="5"></td>
		<td><input name="sabado" type="text" maxlength="10" class="inputbox" size="5"></td>
		<td><select class="inputbox" name="atividade"><option value=''></option><option value="Aula">Aula</option><option value="Atendimento">Atendimento</option></select></td>
	</tr>
    </tbody>
    </table>
    <?php rodape();?>
    <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/template_css.css">
    <input name='task' type='hidden' value='salvarbanca'>
    <input name='idPIT' type='hidden' value='<?php echo $pit->id;?>'>
    <input name='id' type='hidden' value=''>
    <input name='periodo' type='hidden' value='<?php echo $pit->idPeriodo;?>'>
    </form>

    <?php
    }

function editarPIT4($professor, $pit = NULL)
    {

    $Itemid = JRequest::getInt('Itemid', 0);
    $database	=& JFactory::getDBO();

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

function IsNumeric(sText)

{
   var ValidChars = "0123456789,";
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


function validaDatas(dataInicio, dataFim)
{
       var diaInicio = dataInicio.substr(0,2)
       var mesInicio = dataInicio.substr(3,2)
       var anoInicio = dataInicio.substr(6,4)
       var diaFim = dataFim.substr(0,2)
       var mesFim = dataFim.substr(3,2)
       var anoFim = dataFim.substr(6,4)

       var datInicio = new Date(anoInicio, mesInicio-1, diaInicio);
       var datFim = new Date(anoFim, mesFim-1, diaFim);

       if(datInicio >= datFim)
          return false;

       return true;
}

function verificaPesquisa(form)
{

   if(IsEmpty(form.atividadeP))
   {
      alert(unescape('O campo ATIVIDADE deve ser preenchido.'))
      form.atividadeP.focus();
      return false;
   }

    if(IsEmpty(form.inicioP) || !VerificaData(form.inicioP.value))
   {
      alert(unescape('O campo Data de In\xEDcio deve ser preenchida com um valor v\xE1lido.'))
      form.inicioP.focus();
      return false;
   }

    if(IsEmpty(form.terminoP) || !VerificaData(form.terminoP.value))
   {
      alert(unescape('O campo Data de T\xE9rmino deve ser preenchido com um valor v\xE1lido.'))
      form.terminoP.focus();
      return false;
   }
   if(!validaDatas(form.inicioP.value, form.terminoP.value)){
      alert(unescape('A Data de In\xEDcio deve ser inferior \xE0 Data de T\xE9rmino.'))
      form.terminoP.focus();
      return false;
   }
   if(IsEmpty(form.horasP) || (!IsEmpty(form.horasP) && !IsNumeric(form.horasP.value)))
   {
      alert(unescape('O campo HORAS DISPONIVEIS deve ser preenchido com valores inteiros.'))
      form.horasP.focus();
      return false;
   }
return true;

}

function confirmarExclusao()
{
    var confirmar = confirm('Confirmar a exclusao?');

    return confirmar;

}

//-->
</script>


	<link rel="stylesheet" href="components/com_portalsecretaria/estilo.css" type="text/css" />

        <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
        <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>

	<script type="text/javascript">
	$(function() {
		$("#tablesorter-imasters").tablesorter();

	});
	</script>

    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
    <?php cabecalho($pit);?>
	<link type="text/css" href="components/com_portalaluno/jquery-ui-1.8.20.custom.css" rel="Stylesheet" />
    <script src="components/com_portalaluno/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="components/com_portalaluno/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>

    <script>
	$(function() {
		$( "#inicioP" ).datepicker({dateFormat: 'dd/mm/yy'});
	});
	$(function() {
		$( "#terminoP" ).datepicker({dateFormat: 'dd/mm/yy'});
	});
	</script>
	<h3>Atividades de Pesquisa</h3>

    <table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
	<thead>
      <tr bgcolor="#002666">
    	<th width="5%" align="center"></th>
    	<th width="50%" align="center"><b><font color="#FFFFFF">T&#237;tulo</font></b></th>
    	<th width="10%" align="center"><b><font color="#FFFFFF">In&#237;cio</font></b></th>
    	<th width="10%" align="center"><b><font color="#FFFFFF">T&#233;rmino</font></b></th>
    	<th width="15%" align="center"><b><font color="#FFFFFF">Horas Dispon.</font></b></th>
      </tr>
     </thead>
     <tbody>
	<?php

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";

    $sql = "SELECT id, idPIT, atividade, horas, DATE_FORMAT(inicio, '%d/%m/%Y') AS inicio, DATE_FORMAT(termino, '%d/%m/%Y') AS termino FROM #__pits_pesquisa WHERE idPit = $pit->id ORDER BY atividade";
    $database->setQuery( $sql );
    $pesquisas = $database->loadObjectList();

	$i = 0;
	foreach( $pesquisas as $pesquisa )
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

		<td width='16' align="center">
            <a href="javascript:if(confirmarExclusao()){document.form.task.value='removePesqPit';javascript:document.form.id.value='<?php echo $pesquisa->id;?>';document.form.submit()}"> <img src="components/com_portalsecretaria/images/excluir.png" border="0" title='excluir'></a>
			</a>
		</td>
		<td><?php echo $pesquisa->atividade;?></td>
		<td><?php echo $pesquisa->inicio;?></td>
		<td><?php echo $pesquisa->termino;?></td>
		<td><?php echo $pesquisa->horas;?></td>
	</tr>

	<?php
	}
     ?>
     <tr>
		<td width='16' align="center">
            <a href="javascript:if(verificaPesquisa(document.form)){document.form.task.value='addPesqPit';document.form.submit()}"> <img src="components/com_portalsecretaria/images/adicionar.jpg" border="0" title='adicionar'></a>
			</a>
		</td>
		<td><textarea name="atividadeP" rows="1" cols="70%"></textarea></td>
		<td><input id="inicioP" name="inicioP" type="text" maxlength="10" class="inputbox" size="10"></td>
		<td><input id="terminoP" name="terminoP" type="text" maxlength="10" class="inputbox" size="10"></td>
		<td><input name="horasP" type="text" maxlength="3" class="inputbox" size="3"></td>
	</tr>
    </tbody>
    </table>

    <?php rodape();?>
    <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/template_css.css">
    <input name='task' type='hidden' value='salvarbanca'>
    <input name='idPIT' type='hidden' value='<?php echo $pit->id;?>'>
    <input name='id' type='hidden' value=''>
    <input name='periodo' type='hidden' value='<?php echo $pit->idPeriodo;?>'>
    </form>

    <?php
    }

function editarPIT5($professor, $pit = NULL)
    {

    $Itemid = JRequest::getInt('Itemid', 0);
    $database	=& JFactory::getDBO();

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

function IsNumeric(sText)

{
   var ValidChars = "0123456789,";
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

function validaDatas(dataInicio, dataFim)
{
       var diaInicio = dataInicio.substr(0,2)
       var mesInicio = dataInicio.substr(3,2)
       var anoInicio = dataInicio.substr(6,4)
       var diaFim = dataFim.substr(0,2)
       var mesFim = dataFim.substr(3,2)
       var anoFim = dataFim.substr(6,4)

       var datInicio = new Date(anoInicio, mesInicio-1, diaInicio);
       var datFim = new Date(anoFim, mesFim-1, diaFim);

       if(datInicio >= datFim)
          return false;

       return true;
}

function verificaExtensao(form)
{

   if(IsEmpty(form.atividadeE))
   {
      alert(unescape('O campo ATIVIDADE deve ser preenchido.'))
      form.atividadeE.focus();
      return false;
   }

    if(IsEmpty(form.inicioE) || !VerificaData(form.inicioE.value))
   {
      alert(unescape('O campo Data de In\xEDcio deve ser preenchida com um valor v\xE1lido.'))
      form.inicioE.focus();
      return false;
   }

    if(IsEmpty(form.terminoE) || !VerificaData(form.terminoE.value))
   {
      alert(unescape('O campo Data de T\xE9rmino deve ser preenchido com um valor v\xE1lido.'))
      form.terminoE.focus();
      return false;
   }
   if(!validaDatas(form.inicioE.value, form.terminoE.value)){
      alert(unescape('A Data de In\xEDcio deve ser inferior \xE0 Data de T\xE9rmino.'))
      form.terminoE.focus();
      return false;
   }
   if(IsEmpty(form.horasE) || (!IsEmpty(form.horasE) && !IsNumeric(form.horasE.value)))
   {
      alert(unescape('O campo HORAS DISPONIVEIS deve ser preenchido com valores inteiros.'))
      form.horasE.focus();
      return false;
   }
return true;

}

function confirmarExclusao()
{
    var confirmar = confirm('Confirmar a exclusao?');

    return confirmar;

}

//-->
</script>


	<link rel="stylesheet" href="components/com_portalsecretaria/estilo.css" type="text/css" />

        <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
        <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>

	<script type="text/javascript">
	$(function() {
		$("#tablesorter-imasters").tablesorter();

	});
	</script>

    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
    <?php cabecalho($pit);?>

	<link type="text/css" href="components/com_portalaluno/jquery-ui-1.8.20.custom.css" rel="Stylesheet" />

    <script src="components/com_portalaluno/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="components/com_portalaluno/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>

    <script>
	$(function() {
		$( "#inicioE" ).datepicker({dateFormat: 'dd/mm/yy'});
	});
	$(function() {
		$( "#terminoE" ).datepicker({dateFormat: 'dd/mm/yy'});
	});
	</script>
	<p><h3>Atividades de Extens&#227;o</h3></p>
    <table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
	<thead>
      <tr bgcolor="#002666">
    	<th width="5%" align="center"></th>
    	<th width="50%" align="center"><b><font color="#FFFFFF">Especifica&#231;&#227;o da Atividade</font></b></th>
    	<th width="10%" align="center"><b><font color="#FFFFFF">In&#237;cio</font></b></th>
    	<th width="10%" align="center"><b><font color="#FFFFFF">T&#233;rmino</font></b></th>
    	<th width="15%" align="center"><b><font color="#FFFFFF">Horas Dispon.</font></b></th>
      </tr>
     </thead>
     <tbody>
	<?php

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";

    $sql = "SELECT id, idPIT, atividade, horas, DATE_FORMAT(inicio, '%d/%m/%Y') AS inicio, DATE_FORMAT(termino, '%d/%m/%Y') AS termino FROM #__pits_extensao WHERE idPit = $pit->id ORDER BY atividade";
    $database->setQuery( $sql );
    $extensoes = $database->loadObjectList();

	$i = 0;
	foreach( $extensoes as $extensao )
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

		<td width='16' align="center">
            <a href="javascript:if(confirmarExclusao()){document.form.task.value='removeExtPit';javascript:document.form.id.value='<?php echo $extensao->id;?>';document.form.submit()}"> <img src="components/com_portalsecretaria/images/excluir.png" border="0" title='excluir'></a>
			</a>
		</td>
		<td><?php echo $extensao->atividade;?></td>
		<td><?php echo $extensao->inicio;?></td>
		<td><?php echo $extensao->termino;?></td>
		<td><?php echo $extensao->horas;?></td>
	</tr>

	<?php
	}
     ?>
     <tr>
		<td width='16' align="center">
            <a href="javascript:if(verificaExtensao(document.form)){document.form.task.value='addExtPit';document.form.submit()}"> <img src="components/com_portalsecretaria/images/adicionar.jpg" border="0" title='adicionar'></a>
			</a>
		</td>
		<td><textarea name="atividadeE" rows="1" cols="70%"></textarea></td>
		<td><input id="inicioE" name="inicioE" type="text" maxlength="10" class="inputbox" size="10"></td>
		<td><input id="terminoE" name="terminoE" type="text" maxlength="10" class="inputbox" size="10"></td>
		<td><input name="horasE" type="text" maxlength="3" class="inputbox" size="3"></td>
	</tr>
    </tbody>
    </table>
    <?php rodape();?>

    <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/template_css.css">
    <input name='task' type='hidden' value='salvarbanca'>
    <input name='idPIT' type='hidden' value='<?php echo $pit->id;?>'>
    <input name='id' type='hidden' value=''>
    <input name='periodo' type='hidden' value='<?php echo $pit->idPeriodo;?>'>
    </form>

    <?php
    }

function editarPIT6($professor, $pit = NULL)
    {

    $Itemid = JRequest::getInt('Itemid', 0);
    $database	=& JFactory::getDBO();

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

function IsNumeric(sText)

{
   var ValidChars = "0123456789,";
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

function verificaAdministracao(form)
{

   if(IsEmpty(form.cargoA))
   {
      alert(unescape('O campo CARGO deve ser preenchido.'))
      form.cargoA.focus();
      return false;
   }

    if(IsEmpty(form.dataA) || !VerificaData(form.dataA.value))
   {
      alert(unescape('O campo Data de In\xEDcio deve ser preenchida com um valor v\xE1lido.'))
      form.dataA.focus();
      return false;
   }

   if(IsEmpty(form.horasA) || (!IsEmpty(form.horasA) && !IsNumeric(form.horasA.value)))
   {
      alert(unescape('O campo HORAS DISPONIVEIS deve ser preenchido com valores inteiros.'))
      form.horasA.focus();
      return false;
   }
return true;

}

function confirmarExclusao()
{
    var confirmar = confirm('Confirmar a exclusao?');

    return confirmar;

}

//-->
</script>


	<link rel="stylesheet" href="components/com_portalsecretaria/estilo.css" type="text/css" />

        <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
        <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>

	<script type="text/javascript">
	$(function() {
		$("#tablesorter-imasters").tablesorter();

	});
	</script>

    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
    <?php cabecalho($pit);?>

	<link type="text/css" href="components/com_portalaluno/jquery-ui-1.8.20.custom.css" rel="Stylesheet" />

    <script src="components/com_portalaluno/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="components/com_portalaluno/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>

    <script>
	$(function() {
		$( "#dataA" ).datepicker({dateFormat: 'dd/mm/yy'});
	});
	</script>
	<p><h3>Atividades de Administra&#231;&#227;o</h3></p>
    <table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
	<thead>
      <tr bgcolor="#002666">
    	<th width="3%" align="center"></th>
    	<th width="50%" align="center"><b><font color="#FFFFFF">Especifica&#231;&#227;o do Cargo/Fun&#231;&#227;o</font></b></th>
    	<th width="30%" align="center"><b><font color="#FFFFFF">Ato</font></b></th>
    	<th width="7%" align="center"><b><font color="#FFFFFF">Data</font></b></th>
    	<th width="10%" align="center"><b><font color="#FFFFFF">Horas Dispon.</font></b></th>
      </tr>
     </thead>
     <tbody>
	<?php

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";

    $sql = "SELECT id, idPIT, cargo, ato, horas, DATE_FORMAT(data, '%d/%m/%Y') AS data FROM #__pits_administracao WHERE idPit = $pit->id ORDER BY cargo";
    $database->setQuery( $sql );
    $adms = $database->loadObjectList();

	$i = 0;
	foreach( $adms as $adm )
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

		<td width='16' align="center">
            <a href="javascript:if(confirmarExclusao()){document.form.task.value='removeAdmPit';javascript:document.form.id.value='<?php echo $adm->id;?>';document.form.submit()}"> <img src="components/com_portalsecretaria/images/excluir.png" border="0" title='excluir'></a>
			</a>
		</td>
		<td><?php echo $adm->cargo;?></td>
		<td><?php echo $adm->ato;?></td>
		<td><?php echo $adm->data;?></td>
		<td><?php echo $adm->horas;?></td>
	</tr>

	<?php
	}
     ?>
     <tr>
		<td width='16' align="center">
            <a href="javascript:if(verificaAdministracao(document.form)){document.form.task.value='addAdmPit';document.form.submit()}"> <img src="components/com_portalsecretaria/images/adicionar.jpg" border="0" title='adicionar'></a>
			</a>
		</td>
		<td><textarea name="cargoA" rows="2" cols="70%"></textarea></td>
		<td><input id="atoA" name="atoA" type="text" maxlength="40" class="inputbox" size="30"></td>
		<td><input id="dataA" name="dataA" type="text" maxlength="10" class="inputbox" size="10"></td>
		<td><input name="horasA" type="text" maxlength="3" class="inputbox" size="3"></td>
	</tr>
    </tbody>
    </table>
    <?php rodape();?>

    <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/template_css.css">
    <input name='task' type='hidden' value='salvarbanca'>
    <input name='idPIT' type='hidden' value='<?php echo $pit->id;?>'>
    <input name='id' type='hidden' value=''>
    <input name='periodo' type='hidden' value='<?php echo $pit->idPeriodo;?>'>
    </form>

    <?php
    }

function editarPIT7($professor, $pit = NULL)
    {

    $Itemid = JRequest::getInt('Itemid', 0);
    $database	=& JFactory::getDBO();

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

function IsNumeric(sText)

{
   var ValidChars = "0123456789,";
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

function validaDatas(dataInicio, dataFim)
{
       var diaInicio = dataInicio.substr(0,2)
       var mesInicio = dataInicio.substr(3,2)
       var anoInicio = dataInicio.substr(6,4)
       var diaFim = dataFim.substr(0,2)
       var mesFim = dataFim.substr(3,2)
       var anoFim = dataFim.substr(6,4)

       var datInicio = new Date(anoInicio, mesInicio-1, diaInicio);
       var datFim = new Date(anoFim, mesFim-1, diaFim);

       if(datInicio >= datFim)
          return false;

       return true;
}

function verificaFormacao(form)
{

   if(IsEmpty(form.atividadeE))
   {
      alert(unescape('O campo ATIVIDADE deve ser preenchido.'))
      form.atividadeE.focus();
      return false;
   }

   if(IsEmpty(form.nivelE))
   {
      alert(unescape('O campo NIVEL deve ser preenchido.'))
      form.nivelE.focus();
      return false;
   }

    if(IsEmpty(form.inicioE) || !VerificaData(form.inicioE.value))
   {
      alert(unescape('O campo Data de In\xEDcio deve ser preenchida com um valor v\xE1lido.'))
      form.inicioE.focus();
      return false;
   }

    if(IsEmpty(form.terminoE) || !VerificaData(form.terminoE.value))
   {
      alert(unescape('O campo Data de T\xE9rmino deve ser preenchido com um valor v\xE1lido.'))
      form.terminoE.focus();
      return false;
   }
   if(!validaDatas(form.inicioE.value, form.terminoE.value)){
      alert(unescape('A Data de In\xEDcio deve ser inferior \xE0 Data de T\xE9rmino.'))
      form.terminoE.focus();
      return false;
   }
   if(IsEmpty(form.localE))
   {
      alert(unescape('O campo LOCAL deve ser preenchido.'))
      form.localE.focus();
      return false;
   }

   if(IsEmpty(form.horasE) || (!IsEmpty(form.horasE) && !IsNumeric(form.horasE.value)))
   {
      alert(unescape('O campo HORARIO SEMANAL deve ser preenchido com valores inteiros.'))
      form.horasE.focus();
      return false;
   }
return true;

}

function confirmarExclusao()
{
    var confirmar = confirm('Confirmar a exclusao?');

    return confirmar;

}

//-->
</script>


	<link rel="stylesheet" href="components/com_portalsecretaria/estilo.css" type="text/css" />

        <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
        <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>

	<script type="text/javascript">
	$(function() {
		$("#tablesorter-imasters").tablesorter();

	});
	</script>

    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
    <?php cabecalho($pit);?>

	<link type="text/css" href="components/com_portalaluno/jquery-ui-1.8.20.custom.css" rel="Stylesheet" />

    <script src="components/com_portalaluno/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="components/com_portalaluno/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>

    <script>
	$(function() {
		$( "#inicioE" ).datepicker({dateFormat: 'dd/mm/yy'});
	});
	$(function() {
		$( "#terminoE" ).datepicker({dateFormat: 'dd/mm/yy'});
	});
	</script>
	<p><h3>Atividades de Forma&#231;&#227;o</h3></p>
    <table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
	<thead>
      <tr bgcolor="#002666">
    	<th width="3%" align="center"></th>
    	<th width="35%" align="center"><b><font color="#FFFFFF">Especifica&#231;&#227;o da Atividade</font></b></th>
    	<th width="12%" align="center"><b><font color="#FFFFFF">N&#237;vel</font></b></th>
    	<th width="10%" align="center"><b><font color="#FFFFFF">In&#237;cio</font></b></th>
    	<th width="10%" align="center"><b><font color="#FFFFFF">T&#233;rmino</font></b></th>
    	<th width="20%" align="center"><b><font color="#FFFFFF">Local</font></b></th>
    	<th width="10%" align="center"><b><font color="#FFFFFF">Hor&#225;rio Semanal</font></b></th>
      </tr>
     </thead>
     <tbody>
	<?php

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";

    $sql = "SELECT id, idPIT, nivel, local, atividade, horas, DATE_FORMAT(inicio, '%d/%m/%Y') AS inicio, DATE_FORMAT(termino, '%d/%m/%Y') AS termino FROM #__pits_formacao WHERE idPit = $pit->id ORDER BY atividade";
    $database->setQuery( $sql );
    $extensoes = $database->loadObjectList();

	$i = 0;
	foreach( $extensoes as $extensao )
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

		<td width='16' align="center">
            <a href="javascript:if(confirmarExclusao()){document.form.task.value='removeForPit';javascript:document.form.id.value='<?php echo $extensao->id;?>';document.form.submit()}"> <img src="components/com_portalsecretaria/images/excluir.png" border="0" title='excluir'></a>
			</a>
		</td>
		<td><?php echo $extensao->atividade;?></td>
		<td><?php echo $extensao->nivel;?></td>
		<td><?php echo $extensao->inicio;?></td>
		<td><?php echo $extensao->termino;?></td>
		<td><?php echo $extensao->local;?></td>
		<td><?php echo $extensao->horas;?></td>
	</tr>

	<?php
	}
     ?>
     <tr>
		<td width='16' align="center">
            <a href="javascript:if(verificaFormacao(document.form)){document.form.task.value='addForPit';document.form.submit()}"> <img src="components/com_portalsecretaria/images/adicionar.jpg" border="0" title='adicionar'></a>
			</a>
		</td>
		<td><textarea name="atividadeE" rows="1" cols="40%"></textarea></td>
		<td><input name="nivelE" type="text" maxlength="30" class="inputbox" size="15"></td>
		<td><input id="inicioE" name="inicioE" type="text" maxlength="10" class="inputbox" size="10"></td>
		<td><input id="terminoE" name="terminoE" type="text" maxlength="10" class="inputbox" size="10"></td>
		<td><input name="localE" type="text" maxlength="40" class="inputbox" size="15"></td>
		<td><input name="horasE" type="text" maxlength="3" class="inputbox" size="3"></td>
	</tr>
    </tbody>
    </table>
    <?php rodape();?>

    <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/template_css.css">
    <input name='task' type='hidden' value='salvarbanca'>
    <input name='idPIT' type='hidden' value='<?php echo $pit->id;?>'>
    <input name='id' type='hidden' value=''>
    <input name='periodo' type='hidden' value='<?php echo $pit->idPeriodo;?>'>
    </form>

    <?php
    }

function editarPIT8($professor, $pit = NULL)
    {

    $Itemid = JRequest::getInt('Itemid', 0);
    $database	=& JFactory::getDBO();

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

function IsNumeric(sText)

{
   var ValidChars = "0123456789,";
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

function validaDatas(dataInicio, dataFim)
{
       var diaInicio = dataInicio.substr(0,2)
       var mesInicio = dataInicio.substr(3,2)
       var anoInicio = dataInicio.substr(6,4)
       var diaFim = dataFim.substr(0,2)
       var mesFim = dataFim.substr(3,2)
       var anoFim = dataFim.substr(6,4)

       var datInicio = new Date(anoInicio, mesInicio-1, diaInicio);
       var datFim = new Date(anoFim, mesFim-1, diaFim);

       if(datInicio >= datFim)
          return false;

       return true;
}

function verificaOutra(form)
{

   if(IsEmpty(form.atividadeE))
   {
      alert(unescape('O campo ATIVIDADE deve ser preenchido.'))
      form.atividadeE.focus();
      return false;
   }

    if(IsEmpty(form.inicioE) || !VerificaData(form.inicioE.value))
   {
      alert(unescape('O campo Data de In\xEDcio deve ser preenchida com um valor v\xE1lido.'))
      form.inicioE.focus();
      return false;
   }

    if(IsEmpty(form.terminoE) || !VerificaData(form.terminoE.value))
   {
      alert(unescape('O campo Data de T\xE9rmino deve ser preenchido com um valor v\xE1lido.'))
      form.terminoE.focus();
      return false;
   }
   if(!validaDatas(form.inicioE.value, form.terminoE.value)){
      alert(unescape('A Data de In\xEDcio deve ser inferior \xE0 Data de T\xE9rmino.'))
      form.terminoE.focus();
      return false;
   }
   if(IsEmpty(form.horasE) || (!IsEmpty(form.horasE) && !IsNumeric(form.horasE.value)))
   {
      alert(unescape('O campo HORAS DISPONIVEIS deve ser preenchido com valores inteiros.'))
      form.horasE.focus();
      return false;
   }
return true;

}

function confirmarExclusao()
{
    var confirmar = confirm('Confirmar a exclusao?');

    return confirmar;

}

//-->
</script>


	<link rel="stylesheet" href="components/com_portalsecretaria/estilo.css" type="text/css" />

        <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
        <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>

	<script type="text/javascript">
	$(function() {
		$("#tablesorter-imasters").tablesorter();

	});
	</script>

    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
    <?php cabecalho($pit);?>

	<link type="text/css" href="components/com_portalaluno/jquery-ui-1.8.20.custom.css" rel="Stylesheet" />

    <script src="components/com_portalaluno/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="components/com_portalaluno/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>

    <script>
	$(function() {
		$( "#inicioE" ).datepicker({dateFormat: 'dd/mm/yy'});
	});
	$(function() {
		$( "#terminoE" ).datepicker({dateFormat: 'dd/mm/yy'});
	});
	</script>
	<p><h3>Outras Atividades</h3></p>
    <table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
	<thead>
      <tr bgcolor="#002666">
    	<th width="5%" align="center"></th>
    	<th width="50%" align="center"><b><font color="#FFFFFF">Especifica&#231;&#227;o da Atividade</font></b></th>
    	<th width="10%" align="center"><b><font color="#FFFFFF">In&#237;cio</font></b></th>
    	<th width="10%" align="center"><b><font color="#FFFFFF">T&#233;rmino</font></b></th>
    	<th width="15%" align="center"><b><font color="#FFFFFF">Horas Dispon.</font></b></th>
      </tr>
     </thead>
     <tbody>
	<?php

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";

    $sql = "SELECT id, idPIT, atividade, horas, DATE_FORMAT(inicio, '%d/%m/%Y') AS inicio, DATE_FORMAT(termino, '%d/%m/%Y') AS termino FROM #__pits_outras WHERE idPit = $pit->id ORDER BY atividade";
    $database->setQuery( $sql );
    $extensoes = $database->loadObjectList();

	$i = 0;
	foreach( $extensoes as $extensao )
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

		<td width='16' align="center">
            <a href="javascript:if(confirmarExclusao()){document.form.task.value='removeOutPit';javascript:document.form.id.value='<?php echo $extensao->id;?>';document.form.submit()}"> <img src="components/com_portalsecretaria/images/excluir.png" border="0" title='excluir'></a>
			</a>
		</td>
		<td><?php echo $extensao->atividade;?></td>
		<td><?php echo $extensao->inicio;?></td>
		<td><?php echo $extensao->termino;?></td>
		<td><?php echo $extensao->horas;?></td>
	</tr>

	<?php
	}
     ?>
     <tr>
		<td width='16' align="center">
            <a href="javascript:if(verificaOutra(document.form)){document.form.task.value='addOutPit';document.form.submit()}"> <img src="components/com_portalsecretaria/images/adicionar.jpg" border="0" title='adicionar'></a>
			</a>
		</td>
		<td><textarea name="atividadeE" rows="1" cols="70%"></textarea></td>
		<td><input id="inicioE" name="inicioE" type="text" maxlength="10" class="inputbox" size="10"></td>
		<td><input id="terminoE" name="terminoE" type="text" maxlength="10" class="inputbox" size="10"></td>
		<td><input name="horasE" type="text" maxlength="3" class="inputbox" size="3"></td>
	</tr>
    </tbody>
    </table>
    <?php rodape();?>

    <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/template_css.css">
    <input name='task' type='hidden' value='salvarbanca'>
    <input name='idPIT' type='hidden' value='<?php echo $pit->id;?>'>
    <input name='id' type='hidden' value=''>
    <input name='periodo' type='hidden' value='<?php echo $pit->idPeriodo;?>'>
    </form>

    <?php
    }

//////////////////////////////////////////////////////////////
function identificarPIT($professor, $periodo)
{

    $database	=& JFactory::getDBO();
    $sql = "SELECT * from #__pits WHERE idProf = $professor->id AND idPeriodo = $periodo LIMIT 1";
    $database->setQuery($sql);
    $pit = $database->loadObjectList();
    return ($pit[0]);
 }
 
function salvarAulaPIT($idPIT, $idPeriodo){

	$database	=& JFactory::getDBO();
	
    $codigo = JRequest::getVar('codigo');
    $nomeDisciplina = JRequest::getVar('nomeDisciplina');
    $creditos = JRequest::getVar('creditos');
    $turma = JRequest::getVar('turma');
    $horas = JRequest::getVar('horas');
    $prevAlunos = JRequest::getVar('prevAlunos');
    $totAlunos = JRequest::getVar('totAlunos');

    $sql = "INSERT INTO #__pits_aula (idPIT, codigo, nomedisciplina, creditos, turma, horas, prevAlunos, totAlunos) VALUES ($idPIT, '$codigo','$nomeDisciplina', '$creditos', '$turma', $horas, $prevAlunos, $totAlunos)";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;
}

function removerAulaPIT($idAulaPIT){

	$database	=& JFactory::getDBO();

    $sql = "DELETE FROM #__pits_aula WHERE id = $idAulaPIT";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;
}
function salvarHorPIT($idPIT, $idPeriodo){

	$database	=& JFactory::getDBO();

    $disciplina = JRequest::getVar('disciplina');
    $atividade = JRequest::getVar('atividade');
    $segunda = JRequest::getVar('segunda');
    $terca = JRequest::getVar('terca');
    $quarta = JRequest::getVar('quarta');
    $quinta = JRequest::getVar('quinta');
    $sexta = JRequest::getVar('sexta');
    $sabado = JRequest::getVar('sabado');

    $sql = "INSERT INTO #__pits_horarios (idPIT, nomeDisciplina, segunda, terca, quarta, quinta, sexta, sabado, atividade) VALUES ($idPIT, '$disciplina','$segunda', '$terca', '$quarta', '$quinta', '$sexta', '$sabado', '$atividade')";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;
}

function removerHorPIT($id){

	$database	=& JFactory::getDBO();

    $sql = "DELETE FROM #__pits_horarios WHERE id = $id";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;
}

//////////////////////////////////////////////////////////////

function salvarPesqPIT($idPIT, $idPeriodo){

	$database	=& JFactory::getDBO();

    $atividade = JRequest::getVar('atividadeP');
    $inicioP = JRequest::getVar('inicioP');
    $inicio = substr($inicioP,6,4) . "-" . substr($inicioP,3,2) . "-" . substr($inicioP,0,2);
    $terminoP = JRequest::getVar('terminoP');
    $termino = substr($terminoP,6,4) . "-" . substr($terminoP,3,2) . "-" . substr($terminoP,0,2);
    $horas = JRequest::getVar('horasP');

    $sql = "INSERT INTO #__pits_pesquisa (idPIT, atividade, inicio, termino, horas) VALUES ($idPIT, '$atividade','$inicio', '$termino', $horas)";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;
}

function removerPesqPIT($idPesqPIT){

	$database	=& JFactory::getDBO();

    $sql = "DELETE FROM #__pits_pesquisa WHERE id = $idPesqPIT";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;
}
//////////////////////////////////////////////////////////////

function salvarExtPIT($idPIT, $idPeriodo){

	$database	=& JFactory::getDBO();

    $atividade = JRequest::getVar('atividadeE');
    $inicioE = JRequest::getVar('inicioE');
    $inicio = substr($inicioE,6,4) . "-" . substr($inicioE,3,2) . "-" . substr($inicioE,0,2);
    $terminoE = JRequest::getVar('terminoE');
    $termino = substr($terminoE,6,4) . "-" . substr($terminoE,3,2) . "-" . substr($terminoE,0,2);
    $horas = JRequest::getVar('horasE');

    $sql = "INSERT INTO #__pits_extensao (idPIT, atividade, inicio, termino, horas) VALUES ($idPIT, '$atividade','$inicio', '$termino', $horas)";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;
}

function removerExtPIT($idExtPIT){

	$database	=& JFactory::getDBO();

    $sql = "DELETE FROM #__pits_extensao WHERE id = $idExtPIT";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;
}

//////////////////////////////////////////////////////////////

function salvarAdmPIT($idPIT, $idPeriodo){

	$database	=& JFactory::getDBO();

    $cargo = JRequest::getVar('cargoA');
    $ato = JRequest::getVar('atoA');
    $dataA = JRequest::getVar('dataA');
    $data = substr($dataA,6,4) . "-" . substr($dataA,3,2) . "-" . substr($dataA,0,2);
    $horas = JRequest::getVar('horasA');

    $sql = "INSERT INTO #__pits_administracao (idPIT, cargo, ato, data, horas) VALUES ($idPIT, '$cargo','$ato', '$data', $horas)";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;
}

function removerAdmPIT($id){

	$database	=& JFactory::getDBO();

    $sql = "DELETE FROM #__pits_administracao WHERE id = $id";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;
}
//////////////////////////////////////////////////////////////

function salvarForPIT($idPIT, $idPeriodo){

	$database	=& JFactory::getDBO();

    $atividade = JRequest::getVar('atividadeE');
    $inicioE = JRequest::getVar('inicioE');
    $inicio = substr($inicioE,6,4) . "-" . substr($inicioE,3,2) . "-" . substr($inicioE,0,2);
    $terminoE = JRequest::getVar('terminoE');
    $termino = substr($terminoE,6,4) . "-" . substr($terminoE,3,2) . "-" . substr($terminoE,0,2);
    $nivel = JRequest::getVar('nivelE');
    $local = JRequest::getVar('localE');
    $horas = JRequest::getVar('horasE');

    $sql = "INSERT INTO #__pits_formacao (idPIT, atividade, nivel, inicio, termino, local, horas) VALUES ($idPIT, '$atividade','$nivel', '$inicio', '$termino', '$local', $horas)";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;

}

function removerForPIT($id){

	$database	=& JFactory::getDBO();

    $sql = "DELETE FROM #__pits_formacao WHERE id = $id";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;
}
function salvarOutPIT($idPIT, $idPeriodo){

	$database	=& JFactory::getDBO();

    $atividade = JRequest::getVar('atividadeE');
    $inicioE = JRequest::getVar('inicioE');
    $inicio = substr($inicioE,6,4) . "-" . substr($inicioE,3,2) . "-" . substr($inicioE,0,2);
    $terminoE = JRequest::getVar('terminoE');
    $termino = substr($terminoE,6,4) . "-" . substr($terminoE,3,2) . "-" . substr($terminoE,0,2);
    $horas = JRequest::getVar('horasE');

    $sql = "INSERT INTO #__pits_outras (idPIT, atividade, inicio, termino, horas) VALUES ($idPIT, '$atividade','$inicio', '$termino', $horas)";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;
}

function removerOutPIT($id){

	$database	=& JFactory::getDBO();

    $sql = "DELETE FROM #__pits_outras WHERE id = $id";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;
}

function cabecalho($pit){
?>
<script language="JavaScript">
<!--

function confirmarEnvio()
{
    var confirmar = confirm('Confirmar envio do PIT \xE0 secretaria do IComp? Ap\xF3s o envio este n\xE3o poder\xE1 ser alterado!');

    return confirmar;

}

//-->
</script>

	<p><h2>Registrar PIT</h2></p>
    <table align="center" border='0' cellspacing='1' cellpadding="3" class="tabela" width="100%">
      <tr>
        <th id="cpanel" width="20%">
          <div class="icon" style='text-align: center;'>
          <a href="javascript:document.form.task.value='editpit1';javascript:document.form.periodo.value='<?php echo $pit->idPeriodo;?>';document.form.submit()"><img src="components/com_portalprofessor/images/professor.png" border="0"><br>Dados do<br>Professor</a></div>
        </th>
        <th id="cpanel" width="20%">
          <div class="icon" style='text-align: center;'>
          <a href="javascript:document.form.task.value='editpit2';javascript:document.form.periodo.value='<?php echo $pit->idPeriodo;?>';document.form.submit()"><img src="components/com_portalprofessor/images/aula.png" border="0"><br>Ministra&#231;&#227;o<br>de Aulas</a></div>
        </th>
        <th id="cpanel" width="20%">
          <div class="icon" style='text-align: center;'>
          <a href="javascript:document.form.task.value='editpit3';javascript:document.form.periodo.value='<?php echo $pit->idPeriodo;?>';document.form.submit()"><img src="components/com_portalprofessor/images/horario.png" border="0"><br>Hor&#225;rios de Disciplinas</a></div>
        </th>
        <th id="cpanel" width="20%">
          <div class="icon" style='text-align: center;'>
          <a href="javascript:document.form.task.value='editpit4';javascript:document.form.periodo.value='<?php echo $pit->idPeriodo;?>';document.form.submit()"><img src="components/com_portalprofessor/images/pesquisa.gif" border="0"><br>Atividades de Pesquisa</a></div>
        </th>
        <th id="cpanel" width="20%">
          <div class="icon" style='text-align: center;'>
          <a href="javascript:document.form.task.value='editpit5';javascript:document.form.periodo.value='<?php echo $pit->idPeriodo;?>';document.form.submit()"><img src="components/com_portalprofessor/images/extensao.png" border="0"><br>Atividades de Extens&#227;o</a></div>
        </th>
      </tr>
      <tr>
        <th id="cpanel" width="20%">
          <div class="icon" style='text-align: center;'>
          <a href="javascript:document.form.task.value='editpit6';javascript:document.form.periodo.value='<?php echo $pit->idPeriodo;?>';document.form.submit()"><img src="components/com_portalprofessor/images/administracao.gif" border="0"><br>Atividades de Administra&#231;&#227;o</a></div>
        </th>
        <th id="cpanel" width="20%">
          <div class="icon" style='text-align: center;'>
          <a href="javascript:document.form.task.value='editpit7';javascript:document.form.periodo.value='<?php echo $pit->idPeriodo;?>';document.form.submit()"><img src="components/com_portalprofessor/images/formacao.png" border="0"><br>Atividades de Forma&#231;&#227;o</a></div>
        </th>
        <th id="cpanel" width="20%">
          <div class="icon" style='text-align: center;'>
          <a href="javascript:document.form.task.value='editpit8';javascript:document.form.periodo.value='<?php echo $pit->idPeriodo;?>';document.form.submit()"><img src="components/com_portalprofessor/images/outras.png" border="0"><br>Outras<br>Atividades</a></div>
        </th>
        <th id="cpanel" width="20%">
          <div class="icon" style='text-align: center;'>
          <a onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;" href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=verpit&periodo=<?php echo $pit->idPeriodo;?>"><img src="components/com_portalprofessor/images/visualizar.png" border="0"><br>Visualizar Documento</a></div>
        </th>
        <th id="cpanel" width="20%">
          <div class="icon" style='text-align: center;'>
          <a href="javascript:if(confirmarEnvio(document.form)){javascript:document.form.task.value='enviarpit';javascript:document.form.id.value='<?php echo $pit->id;?>';document.form.submit()}"><img src="components/com_portalprofessor/images/enviar.gif" border="0"><br>Enviar PIT para Secretaria</a></div>
        </th>
      </tr>
      </table>
      <hr align="tr" width="100%">
<?php
}

function rodape(){
    $Itemid = JRequest::getInt('Itemid', 0);
?>

    <table align="center" border='0' cellspacing='1' cellpadding="3" class="tabela" width="100%">
      <tr>
        <th id="cpanel" width="50%">
          <div class="icon" style='text-align: center;'>
          <a href="index.php?option=com_portalprofessor&task=pit&Itemid=<?php echo $Itemid;?>"><img src="components/com_portalsecretaria/images/voltar.png" border="0"><br>Voltar</a></div>
        </th>
      </tr>
      </table>
<?php
}

function verPIT($professor, $PIT){

	$database	=& JFactory::getDBO();

	$hoje = date("d_m_y_H_i");
	$arquivoHTML = "components/com_portalprofessor/forms/PIT_".$professor->id."_".$PIT->id.".pdf";
	$arq = fopen($arquivoHTML, 'w') or die("CREATE ERROR");

    // leitura das datas automaticamente
    $dia = date('d');
    $mes = date('m');
    $ano = date('Y');

    // configuração mes

    switch ($mes){

    case 1: $mes = "Janeiro"; break;
    case 2: $mes = "Fevereiro"; break;
    case 3: $mes = "Mar&#231;o"; break;
    case 4: $mes = "Abril"; break;
    case 5: $mes = "Maio"; break;
    case 6: $mes = "Junho"; break;
    case 7: $mes = "Julho"; break;
    case 8: $mes = "Agosto"; break;
    case 9: $mes = "Setembro"; break;
    case 10: $mes = "Outubro"; break;
    case 11: $mes = "Novembro"; break;
    case 12: $mes = "Dezembro"; break;

    }

    $pdf = new Cezpdf();
    $pdf->selectFont('pdf-php/fonts/Helvetica.afm');
    $optionsText = array('justification'=>'center', 'spacing'=>2.0);
    $header = array('justification'=>'center', 'spacing'=>1.3);
    $dados = array('justification'=>'justify', 'spacing'=>2.0);

    $pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 490, 720, 75);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 720, 100);
    $pdf->ezText('<b>PODER EXECUTIVO</b>',12,$header);
    $pdf->ezText('<b>MINISTÉRIO DA EDUCAÇÃO</b>',10,$header);
    $pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$header);
    $pdf->ezText('',11,$header);
    $pdf->ezText('<b>INSTITUTO DE COMPUTAÇÃO</b>',10,$header);
    $pdf->ezText('',11,$header);
    $pdf->setLineStyle(2);
    $pdf->line(20, 720, 580, 720);

//    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('<b>PLANO INDIVIDUAL DE TRABALHO - PIT</b>',12,$optionsText);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText("NOME DO PROFESSOR: ".utf8_decode($professor->nomeProfessor),10,$dados);
    $pdf->ezText("UNIDADE: ".utf8_decode($professor->unidade),10,$dados);
    $pdf->ezText("TITULAÇÃO: ".utf8_decode($professor->titulacao),10,$dados);
    $pdf->addText(230,605,10,"CLASSE: ".utf8_decode($professor->classe),0,0);
    $pdf->addText(430,605,10,"NÍVEL: ".utf8_decode($professor->nivel),0,0);
    $pdf->ezText("INGRESSO: ".$professor->dataIngresso,10,$dados);
    $pdf->addText(230,582,10,"INSCRIÇÃO NO SIAPE: ".utf8_decode($professor->SIAPE),0,0);
    $pdf->ezText("REGIME DE TRABALHO: ".$professor->regime,10,$dados);
    $pdf->addText(230,559,10,"TURNO DE TRABALHO: ".utf8_decode($professor->turno),0,0);
    $pdf->ezText("ANO:  ".substr($PIT->periodo,0,4),10,$dados);
    $pdf->addText(230,536,10,"SEMESTRE: ".substr($PIT->periodo,5,2),0,0);
    $pdf->ezText('');
    $pdf->ezText("<b>1. ATIVIDADES DE ENSINO</b>",10,$dados);
    $pdf->ezText("<b>1.1 Ministração de Aulas</b>",10,$dados);
    $pdf->ezText('');

    $tabelaAulas = array('fontSize'=>8, 'titleFontSize'=>8, 'xPos'=>'center', 'width'=>560, 'cols'=>array('Código'=>array('width'=>50, 'justification'=>'center'),'Disciplina'=>array('width'=>215), 'Nº de Créd.'=>array('width'=>40, 'justification'=>'center'), 'Turma'=>array('width'=>35, 'justification'=>'center'), 'Previsão de Alunos'=>array('width'=>50, 'justification'=>'center'), 'Nº de Aulas Semanais'=>array('width'=>60, 'justification'=>'center'), 'Nº de Alunos'=>array('width'=>50, 'justification'=>'center')));
    $sql = "SELECT * FROM #__pits_aula WHERE idPit = ".$PIT->id." ORDER BY codigo";
    $database->setQuery( $sql );
    $aulas = $database->loadObjectList();

	foreach( $aulas as $aula )
	{
        $listaAulas[] = array('Código'=>$aula->codigo, 'Disciplina'=>utf8_decode($aula->nomeDisciplina), 'Nº de Créd.'=>utf8_decode($aula->creditos),'Turma'=>$aula->turma,'Previsão de Alunos'=>$aula->prevAlunos, 'Nº de Aulas Semanais'=>utf8_decode($aula->horas), 'Nº de Alunos'=>utf8_decode($aula->totAlunos));
    }

    $pdf->ezTable($listaAulas,$cols,'',$tabelaAulas);

    $pdf->ezText("<b>1.2. Horário das Disciplinas e de Atendimentos a Alunos</b>",10,$dados);
    $pdf->ezText('');

    $tabelaHorarios = array(fontSize=>8, titleFontSize=>8, xPos=>'center', width=>500, cols=>array('Atividade'=>array('width'=>160, 'justification'=>'center'),'Seg'=>array('width'=>40, 'justification'=>'center'), 'Ter'=>array('width'=>40, 'justification'=>'center'), 'Qua'=>array('width'=>40, 'justification'=>'center'), 'Qui'=>array('width'=>40, 'justification'=>'center'), 'Sex'=>array('width'=>40, 'justification'=>'center'), 'Sáb'=>array('width'=>40, 'justification'=>'center'), 'Observação'=>array('width'=>100, 'justification'=>'center')));
    $sql = "SELECT * FROM #__pits_horarios WHERE idPit = ".$PIT->id." ORDER BY nomeDisciplina, atividade";
    $database->setQuery( $sql );
    $aulas = $database->loadObjectList();

    if($aulas){
       foreach( $aulas as $aula )
	   {
           $listaHorarios[] = array('Atividade'=>utf8_decode($aula->nomeDisciplina), 'Seg'=>utf8_decode($aula->segunda), 'Ter'=>utf8_decode($aula->terca),'Qua'=>$aula->quarta,'Qui'=>$aula->quinta, 'Sex'=>utf8_decode($aula->sexta), 'Sáb'=>utf8_decode($aula->sabado), 'Observação'=>utf8_decode($aula->atividade));
       }

       $pdf->ezTable($listaHorarios,$cols,'',$tabelaHorarios);
    }

    $pdf->ezText("<b>2. ATIVIDADES DE PESQUISA</b>",10,$dados);
    $pdf->ezText('');
    $tabelaPesquisas = array(fontSize=>8, titleFontSize=>8, xPos=>'center', width=>500, cols=>array('Ord'=>array('width'=>30, 'justification'=>'center'),'Título'=>array('width'=>320), 'Início'=>array('width'=>60, 'justification'=>'center'), 'Término'=>array('width'=>60, 'justification'=>'center'), 'THD'=>array('width'=>40, 'justification'=>'center')));

    $sql = "SELECT id, idPIT, atividade, horas, DATE_FORMAT(inicio, '%d/%m/%Y') AS inicio, DATE_FORMAT(termino, '%d/%m/%Y') AS termino FROM #__pits_pesquisa WHERE idPit = $PIT->id ORDER BY atividade";
    $database->setQuery( $sql );
    $pesquisas = $database->loadObjectList();

    if($pesquisas){
       $i = 0;
	   foreach( $pesquisas as $pesquisa )
	   {
           $i++;
           $listaPesquisas[] = array('Ord'=>$i, 'Título'=>utf8_decode($pesquisa->atividade), 'Início'=>utf8_decode($pesquisa->inicio),'Término'=>$pesquisa->termino,'THD'=>$pesquisa->horas);
       }

       $pdf->ezTable($listaPesquisas,$cols,'',$tabelaPesquisas);
       $pdf->ezText('    Legenda: Ord. = ordem numérica; THD = total de horas disponíveis na semana.');
    }

    $pdf->ezText("<b>3. ATIVIDADES DE EXTENSÃO</b>",10,$dados);
    $pdf->ezText('');
    $tabelaExtensao = array(fontSize=>8, titleFontSize=>8, xPos=>'center', width=>500, cols=>array('Ord'=>array('width'=>30, 'justification'=>'center'),'Especificação da Atividade'=>array('width'=>320), 'Início'=>array('width'=>60, 'justification'=>'center'), 'Término'=>array('width'=>60, 'justification'=>'center'), 'THD'=>array('width'=>40, 'justification'=>'center')));

    $sql = "SELECT id, idPIT, atividade, horas, DATE_FORMAT(inicio, '%d/%m/%Y') AS inicio, DATE_FORMAT(termino, '%d/%m/%Y') AS termino FROM #__pits_extensao WHERE idPit = $PIT->id ORDER BY atividade";
    $database->setQuery( $sql );
    $extensoes = $database->loadObjectList();

    if($extensoes){
       $i = 0;

   	   foreach( $extensoes as $extensao )
	   {
           $i++;
           $listaExtensao[] = array('Ord'=>$i, 'Especificação da Atividade'=>utf8_decode($extensao->atividade), 'Início'=>utf8_decode($extensao->inicio),'Término'=>$extensao->termino,'THD'=>$extensao->horas);
       }

       $pdf->ezTable($listaExtensao,$cols,'',$tabelaExtensao);
       $pdf->ezText('    Legenda: Ord. = ordem numérica; THD = total de horas disponíveis na semana.');
    }

    $pdf->ezText("<b>4. ATIVIDADES DE ADMINISTRAÇÃO</b>",10,$dados);
    $pdf->ezText('');
    $tabelaAdministracao = array(fontSize=>8, titleFontSize=>8, xPos=>'center', width=>510, cols=>array('Ord'=>array('width'=>30, 'justification'=>'center'),'Especificação do Cargo ou Função'=>array('width'=>290, 'justification'=>'justify'), 'Ato'=>array('width'=>90, 'justification'=>'center'), 'Data'=>array('width'=>60, 'justification'=>'center'), 'THD'=>array('width'=>40, 'justification'=>'center')));

    $sql = "SELECT id, idPIT, cargo, ato, horas, DATE_FORMAT(data, '%d/%m/%Y') AS data FROM #__pits_administracao WHERE idPit = $PIT->id ORDER BY cargo";
    $database->setQuery( $sql );
    $adms = $database->loadObjectList();

    if($adms){
       $i = 0;

	   foreach( $adms as $adm )
	   {
           $i++;
           $listaAdministracao[] = array('Ord'=>$i, 'Especificação do Cargo ou Função'=>utf8_decode($adm->cargo), 'Ato'=>utf8_decode($adm->ato),'Data'=>$adm->data,'THD'=>$adm->horas);
       }

       $pdf->ezTable($listaAdministracao,$cols,'',$tabelaAdministracao);
       $pdf->ezText('    Legenda: Ord. = ordem numérica; THD = total de horas disponíveis na semana.');
    }
    
    $pdf->ezText("<b>5. ATIVIDADES DE FORMAÇÃO</b>",10,$dados);
    $pdf->ezText('');
    $tabelaFormacao = array(fontSize=>8, titleFontSize=>8, xPos=>'center', width=>500, cols=>array('Ord'=>array('width'=>30, 'justification'=>'center'),'Especificação da Atividade'=>array('width'=>200), 'Nível'=>array('width'=>60, 'justification'=>'center'),'Início'=>array('width'=>60, 'justification'=>'center'), 'Término'=>array('width'=>60, 'justification'=>'center'), 'Local'=>array('width'=>60, 'justification'=>'center'), 'THD'=>array('width'=>40, 'justification'=>'center')));

    $sql = "SELECT id, idPIT, nivel, local, atividade, horas, DATE_FORMAT(inicio, '%d/%m/%Y') AS inicio, DATE_FORMAT(termino, '%d/%m/%Y') AS termino FROM #__pits_formacao WHERE idPit = $PIT->id ORDER BY atividade";
    $database->setQuery( $sql );
    $formacoes = $database->loadObjectList();

    $i = 0;
    if($formacoes){

       foreach( $formacoes as $formacao )
  	   {
           $i++;
           $listaFormacao[] = array('Ord'=>$i, 'Especificação da Atividade'=>utf8_decode($formacao->atividade), 'Nível'=>utf8_decode($formacao->nivel),'Início'=>utf8_decode($formacao->inicio),'Término'=>$formacao->termino, 'Local'=>utf8_decode($formacao->local),'THD'=>$formacao->horas);
       }

       $pdf->ezTable($listaFormacao,$cols,'',$tabelaFormacao);
       $pdf->ezText('    Legenda: Ord. = ordem numérica.');
    }
    $pdf->ezText("<b>6. OUTRAS ATIVIDADES</b>",10,$dados);
    $pdf->ezText('');
    $tabelaOutras = array(fontSize=>8, titleFontSize=>8, xPos=>'center', width=>500, cols=>array('Ord'=>array('width'=>30, 'justification'=>'center'),'Especificação da Atividade'=>array('width'=>320), 'Início'=>array('width'=>60, 'justification'=>'center'), 'Término'=>array('width'=>60, 'justification'=>'center'), 'THD'=>array('width'=>40, 'justification'=>'center')));

    $sql = "SELECT id, idPIT, atividade, horas, DATE_FORMAT(inicio, '%d/%m/%Y') AS inicio, DATE_FORMAT(termino, '%d/%m/%Y') AS termino FROM #__pits_outras WHERE idPit = $PIT->id ORDER BY atividade";
    $database->setQuery( $sql );
    $outras = $database->loadObjectList();

    if($outras){
       $i = 0;

	   foreach( $outras as $outra )
	   {
           $i++;
           $listaOutras[] = array('Ord'=>$i, 'Especificação da Atividade'=>utf8_decode($outra->atividade), 'Início'=>utf8_decode($outra->inicio),'Término'=>$outra->termino,'THD'=>$outra->horas);
       }

       $pdf->ezTable($listaOutras,$cols,'',$tabelaOutras);
       $pdf->ezText('    Legenda: Ord. = ordem numérica; THD = total de horas disponíveis na semana.');
    }
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('<b>DATA: _____/_____/__________                     APROVADO EM: _____/_____/__________</b>',10,$header);
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('<b>_____________________________________              _____________________________________',10,$header);
    $pdf->ezText('<b>                                 Assinatura do Professor                                  Diretor do Instituto de Computação - IComp',10,$dados);

    $pdf->line(20, 60, 580, 60);
    $pdf->addText(80,40,8,'Av. Rodrigo Otávio, 6.200 • Campus Universitário Senador Arthur Virgílio Filho • CEP 69077-000 •  Manaus, AM, Brasil',0,0);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 140, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 229, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 383, 30, 8, 8);
    $pdf->addText(150,30,8,'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br',0,0);


    $pdfcode = $pdf->output();
    fwrite($arq,$pdfcode);
	fclose($arq);

	return $arquivoHTML;

}

function registrarEnvioPIT($id){

	$database	=& JFactory::getDBO();

    $sql = "UPDATE #__pits SET aberto = 0 WHERE id = $id";
    $database->setQuery( $sql );
    $falha = $database->Query();
    return $falha;
}

///////////////////////////////////////////////////////////////

function novoPIT($professor)
    {

    $Itemid = JRequest::getInt('Itemid', 0);
    $database	=& JFactory::getDBO();

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

function confirmar(form)
{
   if(IsEmpty(form.periodo))
   {
      alert(unescape('O campo PERIODO deve ser preenchido.'))
      form.periodo.focus();
      return false;
   }
   var confirmar = confirm('Confirmar cria\xE7\xE3o do PIT?');

   return confirmar;

}

//-->
</script>

    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
    <h3>Cria&#231;&#227;o do PIT</h3>
    <table width='100%' border='1' cellspacing='1' cellpadding="3" class="tabela">
      <tr>
    	<td bgcolor="#D0D0D0" width="20%"><b>PIT para o Per&#237;odo: </b></td>
    	<td width="25%">
    <select name="periodo" class="inputbox"><option value=""></option>
	<?php

         $database->setQuery("SELECT id, periodo FROM #__periodos WHERE id NOT IN (SELECT DISTINCT idPeriodo FROM #__pits WHERE idProf = $professor->id) ORDER BY periodo");;
	     $periodos = $database->loadObjectList();
	     foreach($periodos as $periodo){
	     ?>   <option value="<?php echo $periodo->id;?>"><?php echo $periodo->periodo;?></option>
	     <?php

         }
	?>
	</select></td>
    	<td bgcolor="#D0D0D0" width="30%"><b>Importar dados do Per&#237;odo: </b></td>
    	<td width="25%">
    <select name="periodoCopiado" class="inputbox"><option value="Nenhum">Nenhum</option>
	<?php

         $database->setQuery("SELECT id, idPeriodo, periodo FROM #__pits WHERE idProf = $professor->id ORDER BY periodo");;
	     $periodos = $database->loadObjectList();
	     foreach($periodos as $periodo){
	     ?>   <option value="<?php echo $periodo->id;?>"><?php echo $periodo->periodo;?></option>
	     <?php

         }
	?>
	</select>
        </td>
      </tr>
    </table>
    <table align="center" border='0' cellspacing='1' cellpadding="3" class="tabela" width="100%">
      <tr>
        <th id="cpanel" width="50%">
          <div class="icon" style='text-align: center;'>
          <a href="javascript:if(confirmar(document.form)){document.form.task.value='salvarpit';document.form.submit()}"><img src="components/com_portalsecretaria/images/salvar.png" border="0"><br>Salvar</a></div>
        </th>
        <th id="cpanel" width="50%">
          <div class="icon" style='text-align: center;'>
          <a href="index.php?option=com_portalprofessor&task=pit&Itemid=<?php echo $Itemid;?>"><img src="components/com_portalsecretaria/images/voltar.png" border="0"><br>Voltar</a></div>
        </th>
      </tr>
      </table>

     <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/template_css.css">
     <input name='task' type='hidden' value=''>
     <input name='id' type='hidden' value=''>
     </form>

    <?php
    }

function salvarPIT($professor){

	$database	=& JFactory::getDBO();

    $idPeriodo = JRequest::getVar('periodo');
    $idPITCopiado = JRequest::getVar('periodoCopiado');
    $sql = "SELECT periodo FROM #__periodos WHERE id = $idPeriodo";
    $database->setQuery( $sql );
    $periodos = $database->loadObjectList();
    $periodo = $periodos[0]->periodo;
    
    $sql = "INSERT INTO #__pits (idProf, idPeriodo, periodo, unidade, titulacao, classe, nivel, regime, turno) VALUES ($professor->id, $idPeriodo, '$periodo','$professor->unidade','$professor->titulacao','$professor->classe','$professor->nivel','$professor->regime','$professor->turno')";
    $database->setQuery( $sql );
    $falha = $database->Query();

    if($idPITCopiado <> "Nenhum"){
       $database->setQuery("select LAST_INSERT_ID(id) AS id from j17_pits order by id desc limit 1");
       $ultimo = $database->loadObjectList();
       $falha2 = importarPIT($ultimo[0]->id, $idPITCopiado);
    }
    
    return ($falha || $falha2);
}

function removerPIT($idPIT){

	$database	=& JFactory::getDBO();

    $database->setQuery("DELETE FROM #__pits_aula WHERE idPIT = $idPIT");
    $database->Query();
    $database->setQuery("DELETE FROM #__pits_extensao WHERE idPIT = $idPIT");
    $database->Query();
    $database->setQuery("DELETE FROM #__pits_pesquisa WHERE idPIT = $idPIT");
    $database->Query();
    $database->setQuery("DELETE FROM #__pits_administracao WHERE idPIT = $idPIT");
    $database->Query();
    $database->setQuery("DELETE FROM #__pits_formacao WHERE idPIT = $idPIT");
    $database->Query();
    $database->setQuery("DELETE FROM #__pits_horarios WHERE idPIT = $idPIT");
    $database->Query();
    $database->setQuery("DELETE FROM #__pits_outras WHERE idPIT = $idPIT");
    $database->Query();
    $database->setQuery("DELETE FROM #__pits WHERE id = $idPIT");
    $database->Query();

}

function importarPIT($idNovoPIT, $idPITCopiado){

	$database	=& JFactory::getDBO();
    $sql = "INSERT INTO #__pits_aula (idPIT, codigo, nomeDisciplina, creditos, turma, horas, prevAlunos, totAlunos) SELECT $idNovoPIT, codigo, nomeDisciplina, creditos, turma, horas, prevAlunos, totAlunos FROM #__pits_aula WHERE idPIT = $idPITCopiado";
    $database->setQuery($sql);
    $falha1 = $database->Query();
    
    $database->setQuery("INSERT INTO #__pits_administracao (idPIT, cargo, ato, data, horas) SELECT $idNovoPIT, cargo, ato, data, horas FROM #__pits_administracao WHERE idPIT = $idPITCopiado");
    $falha2 = $database->Query();

    $database->setQuery("INSERT INTO #__pits_extensao (idPIT, atividade, inicio, termino, horas) SELECT $idNovoPIT, atividade, inicio, termino, horas FROM #__pits_extensao WHERE idPIT = $idPITCopiado");
    $falha3 = $database->Query();

    $database->setQuery("INSERT INTO #__pits_formacao (idPIT, atividade, inicio, termino, horas, nivel, local) SELECT $idNovoPIT, atividade, inicio, termino, horas, nivel, local FROM #__pits_formacao WHERE idPIT = $idPITCopiado");
    $falha4 = $database->Query();

    $database->setQuery("INSERT INTO #__pits_horarios (idPIT, nomeDisciplina, segunda, terca, quarta, quinta, sexta, sabado, atividade) SELECT $idNovoPIT, nomeDisciplina, segunda, terca, quarta, quinta, sexta, sabado, atividade FROM #__pits_horarios WHERE idPIT = $idPITCopiado");
    $falha5 = $database->Query();

    $database->setQuery("INSERT INTO #__pits_outras (idPIT, atividade, inicio, termino, horas) SELECT $idNovoPIT, atividade, inicio, termino, horas FROM #__pits_outras WHERE idPIT = $idPITCopiado");
    $falha6 = $database->Query();

    $database->setQuery("INSERT INTO #__pits_pesquisa (idPIT, atividade, inicio, termino, horas) SELECT $idNovoPIT, atividade, inicio, termino, horas FROM #__pits_pesquisa WHERE idPIT = $idPITCopiado");
    $falha7 = $database->Query();
    
    return ($falha1 || $falha2 || $falha3 || $falha4 || $falha5 || $falha6 || $falha7);

}


?>

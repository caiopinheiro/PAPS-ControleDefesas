<?php

function formata_data($data)
{
 //recebe o par�metro e armazena em um array separado por -
 $data = explode('-', $data);
 //armazena na variavel data os valores do vetor data e concatena /
 $data = $data[2].'/'.$data[1].'/'.$data[0];

 //retorna a string da ordem correta, formatada
 return $data;
}

function gravaData ($data) {
if ($data != '') {
   return (substr($data,6,4).'-'.substr($data,3,2).'-'.substr($data,0,2));
}
else { return ''; }
}

function listarBolsas($aluno = "",$agencia ,$status ){

    $database	=& JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
    
    $sqlEstendido = "";
    $sqlEstendido2 = "";
    
    if ($agencia)  $sqlEstendido = " AND B.agencia LIKE '%$agencia%'";
    if ($status){
      if ($status <> "Vencida")
          $sqlEstendido2 = " AND B.status = '$status'";
      else
          $sqlEstendido2 = " AND B.data_fim < CURDATE()";
    }

    $sql = "SELECT B.id, B.codigo, B.agencia, B.data_ini, B.data_fim, B.idAluno, A.nome, B.status, B.curso FROM #__bolsa AS B JOIN #__aluno AS A ON A.id = B.idAluno WHERE nome LIKE '%$aluno%' $sqlEstendido $sqlEstendido2 ORDER BY codigo ";
    $database->setQuery( $sql );
    $bolsas = $database->loadObjectList();
    
	$statusImg = array ("Ativa" => "ativo.png", "Inativa" => "ausente.png", "Suspensa" => "trancado.jpg", "Finalizada" => "desligado.gif");

	?>

<script language="JavaScript">
<!--

function ValidateformCadastro(form){

return true;

}


function validarForm(form, idBolsa)
{

   form.idBolsa.value = idBolsa;
   form.submit();
   return true;

}

function excluir(form){

   var idSelecionado = 0;
   for(i = 0;i < form.idBolsa.length;i++)
        if(form.idBolsa[i].checked) idSelecionado = form.idBolsa[i].value;

   if(idSelecionado > 0){
       var resposta = window.confirm("Confirmar exclusao da Bolsa?");
       if(resposta){
          form.task.value = 'excluirBolsa';
          form.idBolsa.value = idSelecionado;
          form.submit();
       }
   }
   else{
       alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o dos dados.')
   }
}

function visualizar(form){

   var idSelecionado = 0;
   for(i = 0;i < form.idBolsaSelec.length;i++)
        if(form.idBolsaSelec[i].checked) idSelecionado = form.idBolsaSelec[i].value;

   if(idSelecionado > 0){
       form.task.value = 'verBolsa';
       form.idBolsa.value = idSelecionado;
       form.submit();
   }
   else{
       alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o dos dados.')
   }
}

function editar(form){

   var idSelecionado = 0;
   for(i = 0;i < form.idBolsaSelec.length;i++)
        if(form.idBolsaSelec[i].checked) idSelecionado = form.idBolsaSelec[i].value;

   if(idSelecionado > 0){
       form.task.value = 'editarBolsa';
       form.idBolsa.value = idSelecionado;
       form.submit();
   }
   else{
       alert('Ao menos 1 item deve ser selecionado para edi\xE7\xE3o.')
   }
}

function voltarForm(form){

   form.task.value = 'bolsas';
   form.submit();
   return true;
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

<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" method="post" >
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-new">
           		<a href="javascript:document.form.task.value='addBolsa';validarForm(document.form, 0)">
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
           		<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
		</div>
    <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-categories"><h2>Bolsas do PPGI</h2></div>
    </div></div>

<!-- Campo de Filtro -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td>Filtro por Aluno: </td>
      <td> <input id="buscaAluno" name="buscaAluno" size="30" value="<?php echo $aluno;?>" type="text"></td>
      <td>Filtro por Ag&ecirc;ncia: </td>
      <td> <input id="buscaAgencia" name="buscaAgencia" size="30" value="<?php echo $agencia;?>" type="text"></td>
      <td rowspan="2"> <input value="Buscar" type="submit"></td>
    </tr>
    <tr>
      <td>Filtro por Status: </td>
        <td> <select name="buscaStatus" class="inputbox">
        <option value="" >                                                          Todos            </option>
        <option value="Ativa" <?php if ($status == "Ativa") echo 'SELECTED';?>>     Ativa            </option>
        <option value="Inativa"<?php if ($status == "Inativa") echo 'SELECTED';?>>    Inativa          </option>
        <option value="Suspensa"<?php if ($status == "Suspensa") echo 'SELECTED';?>>    Suspensa          </option>
        <option value="Finalizada"<?php if ($status == "Finalizada") echo 'SELECTED';?>> Finalizada       </option>
        <option value="Vencida"<?php if ($status == "Vencida") echo 'SELECTED';?>> Vencida       </option>
        </select> </td>
    </tr>
  </tbody>
</table>
<!-- Fim do Campo de Filtro e Inicio da Tabela -->
    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">

<!-- Header da tabela -->
<table id="tablesorter-imasters"
 class="tabela" border="0" cellpadding="0" cellspacing="1" width="100%">
  <thead> <tr bgcolor="#002666">
    <th align="center" width="3%"></th>
    <th align="center" width="10%"><font color="#ffc000">ID</font></th>
    <th align="center" width="12%"><font color="#ffc000">Ag&ecirc;ncia</font></th>
     <th align="center" width="10%"><font color="#ffc000">Curso</font></th>
    <th align="center" width="10%"><font color="#ffc000">In&iacute;cio</font></th>
    <th align="center" width="14%"><font color="#ffc000">Encerramento</font></th>
    <th align="center" width="32%"><font color="#ffc000">Aluno</font></th>
    <th align="center" width="10%"><font color="#ffc000">Status</font></th>
  </tr>
  </thead><!-- Fim do Header -->

  <!-- ------------- Inicio do Conte�do --------------- -->
  <tbody>
	<?php

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";
	$curso = array (1 => "mestrado",2 => "doutorado",3 => "especial");

	$i = 0;
	foreach( $bolsas as $bolsa )
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

  		<td width='16'><input type="radio" name="idBolsaSelec" value="<?php echo $bolsa->id;?>"></td>
		<td><?php echo $bolsa->codigo;?></td>
		<td><?php echo $bolsa->agencia;?></td>
		<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $curso[$bolsa->curso];?>.gif' title='<?php echo $curso[$bolsa->curso];?>'></td>
		<td><?php echo formata_data($bolsa->data_ini);?></td>
		<td><?php $data = formata_data($bolsa->data_fim);
                  if($data < date("d/m/Y"))
                     echo "<font color='#FF0000'>$data</font>";
                  else
                     echo $data;
        ?></td>
		<td><?php echo $bolsa->nome;?></td>
		<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $statusImg[$bolsa->status];?>' title='<?php echo $bolsa->status;?>'></td>
	</tr>

	<?php
	}
     ?>

     </tbody>
<!-- ------------- Fim do Conte�do --------------- -->
</table>

 <br>Foi(foram) retornada(s) <b><?php echo sizeof($bolsas);?></b> bolsa(s).
 <input name='task' type='hidden' value='bolsas'>
 <input name='idBolsaSelec' type='hidden' value='0'>
 <input name='idBolsa' type='hidden' value=''>
 </form>
    
<!-- ------------- Fim da Lista --------------- -->

 <?php
 }
 
function salvarBolsa($idBolsa = ""){

    $database	=& JFactory::getDBO();
    
    $codigo = $_POST['codigo'];
    $agencia = $_POST['agencia'];
    $curso = $_POST['curso'];
    $data_ini = gravaData($_POST['data_ini']);
    $data_fim = gravaData($_POST['data_fim']);
    $aluno = $_POST['aluno'];
    $status = $_POST['status'];

    if ($idBolsa == ""){
       $sql = "INSERT INTO #__bolsa (codigo,agencia,curso,data_ini,data_fim,idAluno,status) VALUES ('$codigo','$agencia','$curso','$data_ini','$data_fim',$aluno,'$status')";
       }
    else
        $sql = "UPDATE #__bolsa SET codigo = '$codigo', agencia = '$agencia', curso = '$curso', data_ini = '$data_ini', data_fim = '$data_fim', idAluno = $aluno, status = '$status' WHERE id = $idBolsa";

    $database->setQuery( $sql );
    $database->Query();
    JFactory::getApplication()->enqueueMessage(JText::_('Opera&#231;&#227;o realizada com sucesso.'));
    
}

function excluirBolsa($idBolsa){

  $database	=& JFactory::getDBO();

  $sql = "DELETE FROM #__bolsa WHERE id = $idBolsa";
  $database->setQuery($sql);
  $database->Query();
  JFactory::getApplication()->enqueueMessage(JText::_('Exclus&#227;o realizada com sucesso.'));
}

function identificarBolsaID($idBolsa){

    $database	=& JFactory::getDBO();

    $sql = "SELECT B.id, B.codigo, B.agencia, B.data_ini, B.data_fim, B.idAluno, A.nome, B.status, B.curso FROM #__bolsa AS B JOIN #__aluno AS A ON A.id = B.idAluno WHERE B.id = $idBolsa";

    $database->setQuery( $sql );
    $verbolsa = $database->loadObjectList();

    return ($verbolsa[0]);
}

?>

<!-- ------- Bloco de Codigos das Bolsas -------- -->

<?php  function editarBolsa($bolsa = NULL){  ?>


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


function ValidateformCadastro(form){

    if(IsEmpty(form.codigo))
   {
      alert(unescape('O campo C\xF3digo deve ser preenchido.'))
      form.codigo.focus();
      return false;
   }

    if(IsEmpty(form.data_ini) || !VerificaData(form.data_ini.value))
   {
      alert(unescape('O campo Data de In\xEDcio deve ser preenchida com um valor v\xE1lido.'))
      form.data_ini.focus();
      return false;
   }

    if(IsEmpty(form.data_fim) || !VerificaData(form.data_fim.value))
   {
      alert(unescape('O campo Data de Fim deve ser preenchida com um valor v\xE1lido.'))
      form.data_fim.focus();
      return false;
   }
   if(!validaDatas(form.data_ini.value, form.data_fim.value)){
      alert(unescape('A Data de In\xEDcio deve ser inferior \xE0 Data de Fim.'))
      form.data_fim.focus();
      return false;
   }

    if(IsEmpty(form.aluno))
   {
      alert(unescape('O campo Aluno deve ser selecionado.'))
      form.nome.focus();
      return false;
   }


   form.task.value = 'salvarBolsa';
   form.submit();
   return true;
}


function validarForm(form, idBolsa)
{

   form.idBolsa.value = idBolsa;
   form.submit();
   return true;

}

function voltarForm(form){

   form.task.value = 'bolsas';
   form.submit();
   return true;
}

//-->
</script>

	<link type="text/css" href="components/com_portalaluno/jquery-ui-1.8.20.custom.css" rel="Stylesheet" />

    <script src="components/com_portalaluno/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="components/com_portalaluno/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>

    <script>
	$(function() {
		$( "#data_ini" ).datepicker({dateFormat: 'dd/mm/yy'});
	});
	$(function() {
		$( "#data_fim" ).datepicker({dateFormat: 'dd/mm/yy'});
	});

	</script>


<form name="formCadastro" action="index.php?option=com_portalsecretaria&Itemid=190" method="post" onsubmit="">
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-save">
           		<a href="javascript:if(ValidateformCadastro(document.formCadastro))document.formCadastro.submit()">
           			<span class="icon-32-save"></span><?php echo JText::_( 'Salvar' ); ?></a>
				</div>
				<div class="icon" id="toolbar-cancel">
           		<a href="javascript:voltarForm(document.formCadastro)">
           			<span class="icon-32-cancel"></span><?php echo JText::_( 'Cancelar' ); ?></a>
				</div>
		</div>
    <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-categories"><h2>Cadastro/Edi&#231;&#227;o de Bolsa do PPGI</h2></div>
    </div></div>
<b>Como proceder: </b>
<hr style="width: 100%; height: 2px;">
<p> Preencha todos os campos com seus dados pessoais <font color="#ff0000">(*Campos Obrigat&oacute;rios)</font>.</p>
<hr style="width: 100%; height: 2px;">
<!-- Inicio do Formulario -->

  <table>
    <tbody>
      <tr>
        <th align="left">C&oacute;digo:</th>
        <td> <input name="codigo" size="15" maxlenght="15" type="text" value="<?php if ($bolsa)echo $bolsa->codigo;?>" > </td>
      </tr>
      <tr>
        <th align="left">Ag&ecirc;ncia: </th>
        <td>  <select name="agencia" size="1" listbox="">
        <option value="CAPES" <?php if ($bolsa)if ($bolsa->agencia == "CAPES") echo 'SELECTED';?> >CAPES</option>
        <option value="CNPq" <?php if ($bolsa)if ($bolsa->agencia == "CNPq") echo 'SELECTED';?> >CNPq</option>
        <option value="FAPEAM" <?php if ($bolsa)IF($bolsa->agencia == "FAPEAM") echo 'SELECTED';?> >FAPEAM</option>
        <option value="SUFRAMA" <?php if ($bolsa)IF($bolsa->agencia == "SUFRAMA") echo 'SELECTED';?> >SUFRAMA</option>
        <option value="UOL" <?php if ($bolsa)IF($bolsa->agencia == "UOL") echo 'SELECTED';?> >UOL</option>
        </select>   </td>
      </tr>
      <tr>
        <th align="left">Curso :</th>
        <td> <select name="curso" size="1" listbox="">
        <option value="1" <?php if ($bolsa)if ($bolsa->curso == 1) echo 'SELECTED';?> >Mestrado</option>
        <option value="2" <?php if ($bolsa)if ($bolsa->curso == 2) echo 'SELECTED';?> >Doutorado</option>
        </select> </td>
      </tr>
      <tr>
        <th align="left">Data de In&iacute;cio :</th>
        <td> <input id="data_ini" name="data_ini" size="10" maxlenght="10" type="text" value="<?php if ($bolsa)echo formata_data($bolsa->data_ini);?>"> </td>
      </tr>
      <tr>
        <th align="left">Data de Fim :</th>
        <td> <input id="data_fim" name="data_fim" size="10" maxlenght="10" type="text" value="<?php if ($bolsa)echo formata_data($bolsa->data_fim);?>"> </td>
      </tr>
      <tr>
        <th align="left">Nome do Aluno :</th>
        <td> <select name="aluno" size="1" listbox="">
        <option value="" <?php if ($bolsa)if ($bolsa->idAluno == NULL) echo 'SELECTED';?> >Nenhum Selecionado</option>
        <?php
            $database	=& JFactory::getDBO();
            $Itemid = JRequest::getInt('Itemid', 0);

            $sql = "SELECT * FROM #__aluno ORDER BY nome ";
            $database->setQuery( $sql );
            $alunos = $database->loadObjectList();

	        foreach( $alunos as $aluno ){
	        ?>
            <option value="<?php echo $aluno->id; ?>" <?php if ($bolsa)if ($aluno->id == $bolsa->idAluno) echo 'SELECTED';?> > <?php echo $aluno->nome ?> </option>
      <?php } ?>
      </select> </td>

      </tr>
      <tr>
        <th align="left">Status:</th>
        <td> <select name="status" size="1" listbox="">
        <option value="Ativa" <?php if ($bolsa)if ($bolsa->status == "Ativa") echo 'SELECTED';?> >Ativa</option>
        <option value="Inativa" <?php if ($bolsa)if ($bolsa->status == "Inativa") echo 'SELECTED';?> >Inativa</option>
        <option value="Suspensa" <?php if ($bolsa)if ($bolsa->status == "Suspensa") echo 'SELECTED';?> >Suspensa</option>
        <option value="Finalizada" <?php if ($bolsa)if ($bolsa->status == "Finalizada") echo 'SELECTED';?> >Finalizada</option>
        </select> </td>
      </tr>
    </tbody>
  </table>
<!-- Fim do Formulario -->

<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
    <input name='task' type='hidden' value='salvarBolsa'>
    <input name='idBolsa' type='hidden' value='<?php if($bolsa) echo $bolsa->id;?>'>
<!-- Fim Opcao Images -->
</form>



<?php }

 function verBolsa($verbolsa){

 ?>

<form name="formCadastro" action="index.php?option=com_portalsecretaria&Itemid=190" method="post" onsubmit="">
     <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-back">
           		<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=bolsas">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
		</div>
    <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-categories"><h2>Visualiza&#231;&#227;o de Bolsa do PPGI</h2></div>
    </div></div>

  <table width = "100%" cellpadding="2" cellspacing="3" >
    <tbody>
      <tr>
        <th width = "20%" align="left">C&oacute;digo:</th> <td> <?php echo $verbolsa->codigo;?> </td>
      </tr>
      <tr>
        <th align="left">Ag&ecirc;ncia de Formento: </th> <td> <?php echo $verbolsa->agencia;?> </td>
      </tr>
      <tr>
        <th align="left">Curso:</th> <td> <?php echo $verbolsa->curso;?> </td>
      </tr>
      <tr>
        <th align="left">Data de In&iacute;cio :</th> <td> <?php echo formata_data($verbolsa->data_ini);?> </td>
      </tr>
      <tr>
        <th align="left">Data de Fim :</th> <td> <?php echo formata_data($verbolsa->data_fim);?> </td>
      </tr>
      <tr>
        <th align="left">Nome do Aluno :</th> <td> <?php echo $verbolsa->nome;?> </td>
      </tr>
      <tr>
        <th align="left">Status da Bolsa:</th> <td> <?php echo $verbolsa->status;?> </td>
      </tr>
    </tbody>
  </table>
<!-- Fim do Formulario -->
<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
<!-- Fim Opcao Images -->
</form>

<?php } ?>


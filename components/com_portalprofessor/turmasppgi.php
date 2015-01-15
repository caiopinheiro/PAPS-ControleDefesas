<?php // LISTAGEM DE TURMAS
function listarTurmas($idPeriodo, $curso, $professor) {
	$Itemid = JRequest::getInt('Itemid', 0);
	$database =& JFactory::getDBO(); ?>

	<script language="javascript">    
        function visualizar(form) {
            var idSelecionado = 0;
            
            for(i = 0; i < form.idDisciplinaSelec.length;i++)
               if(form.idDisciplinaSelec[i].checked) idSelecionado = form.idDisciplinaSelec[i].value;
    
            if(idSelecionado != 0){
               form.task.value = 'consultarTurma';
               var quebra = idSelecionado.split("-");
               form.idDisciplina.value = quebra[0];
               form.idPeriodo.value = quebra[1];
               form.turma.value = quebra[2];
               form.submit();
            } else {
               alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o.')
            }
        }
	</script>

	<script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();
		});
	</script>

	<link type="text/css" rel="stylesheet" href="components/com_portalsecretaria/assets/css/estilo.css" />
    
    <form name="formTurma" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>" method="post">
    
   	  <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        	<div class="cpanel2">
				<div class="icon" id="toolbar-preview">
           		<a href="javascript:visualizar(document.formTurma)">
           			<span class="icon-32-preview"></span>Visualizar</a>
				</div>

				<div class="icon" id="toolbar-back">
           		<a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
           			<span class="icon-32-back"></span>Voltar</a>
				</div>
			</div>
            <div class="clr"></div>
            </div>
            
       	<div class="pagetitle icon-48-contact-categories"><h2>Turmas alocadas para o professor</h2></div>
		</div></div>

		<!-- FILTRO DA BUSCA -->
		<fieldset>        
			<legend>Filtro para consulta</legend>
            <table>
            	<tr>
                	<td>Período</td>
                    <td>
                        <select name="periodo">
                        <?php            
                         $database->setQuery("SELECT idPeriodo, periodo, P.status FROM #__periodo_disc AS PD JOIN #__periodos AS P ON PD.idPeriodo = P.id WHERE idProfessor = ".$professor->id." GROUP BY idPeriodo ORDER BY idPeriodo DESC");
                         $periodos = $database->loadObjectList();
                         $status = array('0' => "Aberta" , '1' => "Finalizada");
                         $statusImg = array('0' => "cadeado_aberto.gif" , '1' => "cadeado_fechado.gif");
                
                         foreach($periodos as $periodo) { ?>
                             <option value="<?php echo $periodo->idPeriodo;?>" <?php
                             if($idPeriodo == NULL){
                                if($periodo->status == 1){
                                   echo 'SELECTED';
                                   $idPeriodo = $periodo->idPeriodo;
                                }
                             } else {
                                if($idPeriodo == $periodo->idPeriodo) echo 'SELECTED';
                             }
                             ?>> <?php echo $periodo->periodo;?>
                             </option>
                        <?php }
                         if($idPeriodo == NULL) $idPeriodo = $periodos[0]->idPeriodo;
                             echo "</select>";
                
                          $sql = "SELECT idPeriodo, P.status, idDisciplina, professor, horario, turma, curso, codigo, nomeDisciplina FROM #__periodo_disc as P join #__disciplina as D ON P.idDisciplina = D.id WHERE P.idPeriodo = $idPeriodo AND idProfessor = ".$professor->id." ORDER BY nomeDisciplina, curso";
                          $database->setQuery( $sql );
                          $turmas = $database->loadObjectList();
                
                        ?>
                        </select>
                    </td>            
		            <td><button type="submit" value="Buscar" class="btn btn-primary">
                    		<i class="icone-search icone-white"></i> Buscar
                        </button>
					</td>
				</tr>
			</table>
		</fieldset>            
   
		<table class="table table-striped" id="tablesorter-imasters">
            <thead>
                <tr>
                    <th></th>
                    <th>Status</th>
                    <th>Código</th>
                    <th>Nome Disciplina</th>
                    <th>Turma</th>
                    <th>Curso</th>
                </tr>
            </thead>
            
     		<tbody>
				<?php
                $curso = array (1 => "Mestrado",2 => "Doutorado",3 => "Especial");
            
                if($turmas){
					foreach( $turmas as $turma ) { ?>
					<tr>
						<td>
							<input type="radio" name="idDisciplinaSelec" value="<?php echo $turma->idDisciplina."-".$turma->idPeriodo."-".$turma->turma;?>">
						</td>
						<td><img src="components/com_portalsecretaria/images/<?php echo $statusImg[$turma->status];?>" title="<?php echo $status[$turma->status];?>"></td>
						<td><?php echo $turma->codigo;?></td>
						<td><?php echo $turma->nomeDisciplina;?></td>
						<td><?php echo $turma->turma;?></td>
						<td><?php echo $curso[$turma->curso];?></td>
					</tr>
					<?php } 
				}
                ?>
			</tbody>
		</table>
        <br />
        
        <span class="label label-inverse">Total de Turmas: <?php echo sizeof($turmas);?></span>
        <input name='task' type='hidden' value='listarturmas' />
        <input name='turma' type='hidden' value='' />
        <input name='idPeriodo' type='hidden' value='' />
        <input name='idDisciplina' type='hidden' value='' />
        <input name='idDisciplinaSelec' type='hidden' value='0' />
    
    </form>

<?php }


// DETALHES DA TURMA
function consultarTurma($idPeriodo, $idDisciplina, $turma) {
    $Itemid = JRequest::getInt('Itemid', 0);
    $database =& JFactory::getDBO();

    $sql = "SELECT idPeriodo, idDisciplina, professor, horario, turma, curso, codigo, nomeDisciplina, status FROM #__periodo_disc as P join #__disciplina as D ON P.idDisciplina = D.id WHERE P.idPeriodo = $idPeriodo AND P.idDisciplina = $idDisciplina AND turma = '$turma'";
    $database->setQuery( $sql );
    $turmas = $database->loadObjectList();

    $sql = "SELECT idAluno, nome, matricula, orientador, area, curso, bolsista, agencia, anoingresso, frequencia, conceito FROM #__disc_matricula as M join #__aluno as A ON M.idAluno = A.id WHERE M.idPeriodo = $idPeriodo AND M.idDisciplina = $idDisciplina AND turma = '$turma ' ORDER BY nome";
    $database->setQuery($sql);
    $alunos = $database->loadObjectList();
    
    $turma = $turmas[0];

    $database->setQuery("SELECT id, notas FROM #__periodos WHERE id = $idPeriodo LIMIT 1");
    $periodos = $database->loadObjectList();
    $notas = $periodos[0]->notas;

	$curso = array (1 => "Mestrado",2 => "Doutorado",3 => "Especial"); ?>
    
    <script language="JavaScript">
	    function ConfirmarFinalizacao() {
			var confirmar = confirm('Confirmar Finalizacao? Esse passo eh irreversivel. Apos sua confirmacao, ele nao podera ser desfeito.');
			/*if(confirmar == true){
				formPeriodoDisc.task.value='rmDiscPeriodo';
				formPeriodoDisc.rmDisc.value=idDisc;
				formPeriodoDisc.submit();
			} */
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
        
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
		<div class="cpanel2">
			<?php if($turma->status == 0 && $notas) { ?>
                <div class="icon" id="toolbar-new-style">
           		<a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=lancarnota&idDisciplina=<?php echo $turma->idDisciplina;?>&periodo=<?php echo $turma->idPeriodo;?>&turma=<?php echo $turma->turma;?>">
           			<span class="icon-32-new-style"></span>Lan&#231;ar<br>Notas</a>
				</div>
				<div class="icon" id="toolbar-checkin">
           		<a href="javascript:if(ConfirmarFinalizacao()) location.href='index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=finalizarturma&idDisciplina=<?php echo $turma->idDisciplina;?>&periodo=<?php echo $turma->idPeriodo;?>&turma=<?php echo $turma->turma;?>'">
           			<span class="icon-32-checkin"></span>Finalizar<br>Turma</a>
				</div>
			<?php } ?>
                
            <div class="icon" id="toolbar-back">
            <a href="index.php?option=com_portalprofessor&task=listarturmas&Itemid=<?php echo $Itemid;?>">
                <span class="icon-32-back"></span>Voltar</a>
            </div>

            </div>
            <div class="clr"></div>
            </div>
		<div class="pagetitle icon-48-search"><h2>Consulta de Turma</h2></div>
    </div></div>

	<h3>Dados da Turma</h3>
    <hr />

    <table class="table table-bordered">
      <tr>
    	<td bgcolor="#D0D0D0" width="15%"><b>Código </b></td>
    	<td width="15%"><?php echo $turma->codigo;?></td>
        <td bgcolor="#D0D0D0" width="20%"><b>Nome Disciplina </b></td>
    	<td width="50%"><?php echo $turma->nomeDisciplina;?></td>
      </tr>
      <tr>
        <td bgcolor="#D0D0D0"><b>Turma </b></td>
    	<td><?php echo $turma->turma;?></td>
        <td bgcolor="#D0D0D0"><b>Professor </b></td>
    	<td><?php echo $turma->professor;?></td>
      </tr>
      <tr>
        <td bgcolor="#D0D0D0"><b>Curso </b></td>
    	<td><?php echo $curso[$turma->curso];?></td>
        <td bgcolor="#D0D0D0"><b>Horário </b></td>
    	<td><?php echo $turma->horario;?></td>
      </tr>
      <tr>
    </table>
    
	<h3>Alunos Matriculados</h3>
    <hr />

    <table class="table table-striped" id="tablesorter-imasters">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Matrícula</th>
            <th>Linha de Pesquisa</th>
            <th>Ingresso</th>
            <th>Freq (%)</th>
            <th>Conceito</th>
          </tr>
        </thead>
        
		<tbody>
		<?php
		if($alunos) {
			foreach($alunos as $aluno) { ?>
            
            <tr>
                <td><?php echo $aluno->nome;?></td>
                <td><?php echo $aluno->matricula;?></td>
                <td><?php echo verLinhaPesquisa($aluno->area, 2);?></td>
                <td><?php echo dataBr($aluno->anoingresso);?></td>
                <td><?php if($aluno->frequencia <> NULL) echo number_format($aluno->frequencia,2);?></td>
                <td><?php echo $aluno->conceito;?></td>
            </tr>
			<?php } 
		}
     	?>
		</tbody>
	</table>    
	<br />
    
    <span class="label label-inverse">Total de Alunos: <?php echo sizeof($alunos); ?></span>
    
    <input name='task' type='hidden' value='listarturmas' />
    <input name='idDisciplina' type='hidden' value='<?php echo $turma->idDisciplina;?>' />
    <input name='periodo' type='hidden' value='<?php echo $turma->idPeriodo;?>' />
    <input name='turma' type='hidden' value='<?php echo $turma->turma;?>' />

<?php }

////////////////////////////////////////////

 function lancarNota($idPeriodo, $idDisciplina, $nomeTurma)
 {

    $Itemid = JRequest::getInt('Itemid', 0);
    $database	=& JFactory::getDBO();

    $sql = "SELECT idPeriodo, idDisciplina, professor, horario, turma, curso, codigo, nomeDisciplina FROM #__periodo_disc as P join #__disciplina as D ON P.idDisciplina = D.id WHERE P.idPeriodo = $idPeriodo AND P.idDisciplina = $idDisciplina AND turma = '$nomeTurma'";
    $database->setQuery( $sql );
    $turmas = $database->loadObjectList();
    $turma = $turmas[0];

    $sql = "SELECT idAluno, nome, matricula, orientador, area, curso, bolsista, agencia, anoingresso, frequencia, conceito FROM #__disc_matricula as M join #__aluno as A ON M.idAluno = A.id WHERE M.idPeriodo = $idPeriodo AND M.idDisciplina = $idDisciplina AND turma = '$nomeTurma' ORDER BY nome";
    $database->setQuery($sql);
    $alunos = $database->loadObjectList();

	$curso = array (1 => "Mestrado",2 => "Doutorado",3 => "Especial");
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

function Limpar(valor, validos) {
         // retira caracteres invalidos da string
         var result = "";
         var aux;
         for (var i=0; i < valor.length; i++) {
             aux = validos.indexOf(valor.substring(i, i+1));
             if (aux>=0)
               result += aux;
         }
         return result;
}

//Formata número tipo moeda usando o evento onKeyDown

function Formata(campo,tammax,teclapres,decimal) {
         var tecla = teclapres.keyCode;
         vr = Limpar(campo.value,"0123456789");
         tam = vr.length;
         dec=decimal;

         if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }

         if (tecla == 8 )
         { tam = tam - 1 ; }

         if ( tecla == 8 || (tecla >= 48 && tecla <= 57) )
         {

         if ( tam == 0 )
         {         campo.value = '0.00' ;         }
         else if ( tam == 1 )
         {         campo.value = '0.0'+vr ;         }
         else if ( tam == 2 )
         {         campo.value = '0.'+vr ;         }
         else if ( (tam > dec) ){
            var parte1 = vr.substr( 0, tam - 2 );
            if(parte1 == '00') parte1 = '0';
            if(parte1 == '01') parte1 = '1';
            if(parte1 == '02') parte1 = '2';
            if(parte1 == '03') parte1 = '3';
            if(parte1 == '04') parte1 = '4';
            if(parte1 == '05') parte1 = '5';
            if(parte1 == '06') parte1 = '6';
            if(parte1 == '07') parte1 = '7';
            if(parte1 == '08') parte1 = '8';
            if(parte1 == '98') parte1 = '9';
            campo.value = parte1 + "." + vr.substr( tam - dec, tam ) ; }

         }

}

   function handleEnter (field, event) {
      var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
      if(keyCode == 8 || keyCode == 9 || (keyCode >= 48 && keyCode <= 57)) return true;
      if (keyCode == 13) {
        var i;
      for (i = 0; i < field.form.elements.length; i++)
         if (field == field.form.elements[ i ])
           break;
           i = (i + 1) % field.form.elements.length;
           field.form.elements[ i ].focus();
           return false;
        }
       else
    return false;
    }
    
function validarFreq(form) {
      for (i = 0; i < form.elements.length; i++)
         if (form.elements[i].type == "text")
         if (form.elements[i].value > 100){
           alert(unescape('O campo FREQUENCIA deve ser preenchido com valores menores ou iguais a 100.'));
           form.elements[i].focus();
           return false;

         }
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
    
    <form method="post" name="formNota" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
                <div class="icon" id="toolbar-save">
           		<a href="javascript:if(validarFreq(document.formNota)) document.formNota.submit()">
           			<span class="icon-32-save"></span>Salvar</a>
				</div>
				<div class="icon" id="toolbar-back">
           		<a href="javascript:document.formNota.task.value='consultarTurma';document.formNota.submit()">
           			<span class="icon-32-back"></span>Voltar</a>
				</div>

		</div>
    <div class="clr"></div>
	</div>
          <div class="pagetitle icon-48-search"><h2>Lan&#231;ar Notas</h2></div>
    </div></div>
	<p><h2>Dados da Turma</h2></p>
    <table width='100%' border='1' cellspacing='1' cellpadding="0" class="tabela">
      <tr>
    	<td bgcolor="#D0D0D0" width="15%"><b>C&#243;digo: </b></td>
    	<td width="15%"><?php echo $turma->codigo;?></td>
        <td bgcolor="#D0D0D0" width="20%"><b>Nome Disciplina: </b></td>
    	<td width="50%"><?php echo $turma->nomeDisciplina;?></td>
      </tr>
      <tr>
        <td bgcolor="#D0D0D0"><b>Turma: </b></td>
    	<td><?php echo $turma->turma;?></td>
        <td bgcolor="#D0D0D0"><b>Professor: </b></td>
    	<td><?php echo $turma->professor;?></td>
      </tr>
      <tr>
        <td bgcolor="#D0D0D0"><b>Curso: </b></td>
    	<td><?php echo $curso[$turma->curso];?></td>
        <td bgcolor="#D0D0D0"><b>Hor&#225;rio: </b></td>
    	<td><?php echo $turma->horario;?></td>
      </tr>
      <tr>
    </table>
    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
	<p><h2>Alunos Matriculados</h2></p>
    <table width='100%' border='0' cellspacing='1' cellpadding="0">
	<thead>
      <tr bgcolor="#002666">
    	<th width="50%" align="center"><font color="#FFC000"><b>Nome</b></font></th>
        <th width="20%" align="center"><font color="#FFC000"><b>Frequ&#234;ncia (%)</b></font></th>
        <th width="30%" align="center"><font color="#FFC000"><b>Conceito</b></font></th>
      </tr>
     </thead>
     <tbody>
	<?php

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";

	$i = 0;
	if($alunos){
	foreach( $alunos as $aluno )
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

		<td><?php echo $aluno->nome;?></td>
		<td>
    		<input type="hidden" id="alunos<?php echo $aluno->idAluno; ?>" name="alunos[]" value='<?php echo $aluno->idAluno; ?>'/>
            <input id="frequencia<?php echo $aluno->idAluno;?>"  name="frequencias[]" MAXLENGTH="6" size="6" type="text" value="<?php if($aluno->frequencia <> NULL) echo number_format($aluno->frequencia,2);?>" onKeydown="Formata(this,5,event,2)"  onkeypress="return handleEnter(this, event)"/></td>
		<td>
        <select name="conceitos[]" id="conceito<?php echo $aluno->idAluno;?>" class="inputbox">
        <option value="" <?php if ($aluno->conceito == "") echo 'SELECTED';?>></option>
        <?php if($turma->idDisciplina == 68 || $turma->idDisciplina == 69 || $turma->idDisciplina == 94) { ?>
        <option value="AP" <?php if ($aluno->conceito == "AP") echo 'SELECTED';?>>AP - Aprovado</option>
        <?php }
        else{         ?>
        <option value="A" <?php if ($aluno->conceito == "A") echo 'SELECTED';?>>A (&#8805; 9,0: Aprovado)</option>
        <option value="B" <?php if ($aluno->conceito == "B") echo 'SELECTED';?>>B (&#8805; 8,0 e < 9,0: Aprovado)</option>
        <option value="C" <?php if ($aluno->conceito == "C") echo 'SELECTED';?>>C (&#8805; 7,0 e < 8,0: Aprovado)</option>
        <option value="X" <?php if ($aluno->conceito == "X") echo 'SELECTED';?>>X (Trancamento)</option>
        <?php }  ?>
        <option value="R" <?php if ($aluno->conceito == "R") echo 'SELECTED';?>>R (Reprovado)</option>
        </select></td>
	</tr>
	<?php
	}}
     ?>

     </tbody>
	</table>
	<p><br /><b><?php echo sizeof($alunos);?></b> alunos est&#227;o cadastrados neste turma.</p>
    <input name='task' type='hidden' value='salvarnota'>
    <input name='idDisciplina' type='hidden' value='<?php echo $idDisciplina;?>'>
    <input name='idPeriodo' type='hidden' value='<?php echo $idPeriodo;?>'>
    <input name='turma' type='hidden' value='<?php echo $turma->turma;?>'>
    </form>

    <?php
}

////////////////////////////////////////////
 function salvarNota($idPeriodo, $idDisciplina, $turma, $alunos, $frequencias, $conceitos){

    $database	=& JFactory::getDBO();

    $cont =0;
    foreach($alunos as $aluno)
    {
       $sql = "UPDATE #__disc_matricula SET frequencia = ".$frequencias[$cont].", conceito='".$conceitos[$cont]."' WHERE idAluno = $aluno AND idDisciplina = $idDisciplina AND turma = '$turma' AND idPeriodo = $idPeriodo";
       $database->setQuery( $sql );
       $database->Query();

       $cont++;
    }
    JFactory::getApplication()->enqueueMessage(JText::_('As notas foram salvas com sucesso!!!'));
}

////////////////////////////////////////////
 function finalizarTurma($idPeriodo, $idDisciplina, $turma){

    $database	=& JFactory::getDBO();

    $sql = "UPDATE #__periodo_disc SET status = 1 WHERE idPeriodo = $idPeriodo AND idDisciplina = $idDisciplina AND turma = '$turma'";
    $database->setQuery( $sql );
    $database->Query();
    JFactory::getApplication()->enqueueMessage(JText::_('Turma finalizada com sucesso!!!'));
}

?>

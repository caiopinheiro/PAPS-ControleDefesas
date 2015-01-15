<?php
	function servicosPortalAluno($alunos) {
			$Itemid = JRequest::getInt('Itemid', 0);
		
			$database =& JFactory::getDBO();	
			$sqlMestrado = "SELECT * FROM #__aluno WHERE email = '".$aluno->email."' AND status = '0' AND curso = '1'";
			$database->setQuery($sqlMestrado);
			$userMestrado = $database->loadObjectList();
			foreach ($userMestrado as $userMestrado)
			
			$database =& JFactory::getDBO();	
			$sqlDoutorado = "SELECT * FROM #__aluno WHERE email = '".$aluno->email."' AND status = '0' AND curso = '2'";
			$database->setQuery($sqlDoutorado);
			$userDoutorado = $database->loadObjectList();
			foreach ($userDoutorado as $userDoutorado)
				
			$database =& JFactory::getDBO();	
			$sqlUserUpdate = "UPDATE #__aluno SET idUser = '$userMestrado->idUser' WHERE id = '$userDoutorado->id'";
			$database->setQuery($sqlUserUpdate);
			$funcionou = $database->Query();
			
			?>
	   
			<script language="JavaScript">
                function acessarConta(form, idAluno) {
                   form.task.value = 'servicos';
				   form.idAluno.value = idAluno;
                   form.submit();
                }
            </script>
        
            <p><h3>Selecione o tipo de conta que deseja acessar</h3></p> 
            <hr />
            
            <div class="cpanel" style="width:290px; height:100px; margin:auto;">            
                <form name="formAluno" action="index.php?option=com_portalaluno&Itemid=<?php echo $Itemid;?>" method="post">
                <?php
					$curso = array (1 => 'Mestrado',2 => 'Doutorado',3 =>'Especial');
				
					foreach($alunos as $aluno) { ?>
                    
                        <div class="icon-wrapper">
                            <div class="icon">
                            <a href="javascript:acessarConta(document.formAluno, <?php echo $aluno->id;?>)">
                                <img width="40" height="40" border="0" src="components/com_portalaluno/images/<?php echo $curso[$aluno->curso]; ?>.png"><span><b><?php echo $curso[$aluno->curso]; ?></b></span></a>
                            </div>
                        </div>                    
                <?php
					}
				?>
                    <input name='task' type='hidden' value='servicos'>     
                    <input name='idAluno' type='hidden' value=''>                         
                </form>
                
			</div>                 
		
		<?php 
	}
?>

<?php
	function servicosPortalAlunoItem($aluno) {
		$Itemid = JRequest::getInt('Itemid', 0);
		$database	=& JFactory::getDBO();
		$status = array (0 => 'Aluno Corrente',1 => 'Aluno Egresso', 2 => 'Aluno Desistente', 3 => 'Aluno Desligado', 4 => 'Aluno Jubilado', 5 => 'Aluno com Matr&#237;cula Trancada');
		$curso = array (1 => 'Mestrado',2 => 'Doutorado',3 =>'Especial');
		?>

	<script language="JavaScript">
        function abrirHistorico(idAluno) {
           window.open("index.php?option=com_portalaluno&Itemid=191&task=historico&idAluno="+idAluno,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
        }
        
        function abrirMatricula(form) {
           form.task.value = 'matricula';
           form.submit();
        }
        
        function abrirComprovante(idAluno) {
           window.open("index.php?option=com_portalaluno&Itemid=191&task=imprimirMatricula&idAluno="+idAluno,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
        }
        
        function abrirDeclaracao(idAluno) {
           window.open("index.php?option=com_portalaluno&Itemid=191&task=declaracao&idAluno="+idAluno,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
        }
        
        function abrirOferta(idAluno, idPeriodo) {
           window.open("index.php?option=com_portalaluno&Itemid=191&task=oferta&idAluno="+idAluno+"&idPeriodo="+idPeriodo,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
        }
        
        function editarAluno(form) {
           form.task.value = 'aluno';
           form.submit();
        }
        
        function aproveitamentoDisciplina(form) {
           form.task.value = 'aproveitamento';
           form.submit();
        }
        
        function prorrogacaoPrazo(form) {
           form.task.value = 'prorrogacao';
           form.submit();
        }
		
		function trancarPeriodo(form) {
           form.task.value = 'trancamento';
           form.submit();
        }		
    </script>
    
    <script src="components/com_portalaluno/jquery_countdown/script/jquery-1.6.1.js" type="text/javascript"></script>
	<script src="components/com_portalaluno/jquery_countdown/script/jquery.jcountdown1.3.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#time").countdown({
                date: "<?php echo $aluno->anoconclusao; ?>", 
                onChange: function( event, timer ){	
                },
                onComplete: function( event ){			
                    $(this).html("<b>Prazo Encerrado!</b>");
                },
                leadingZero: true,
                direction: "down"
            });	
        });
    </script>

    <link rel="stylesheet" type="text/css" href="components/com_portalaluno/jquery_countdown/css/style.css" />    
    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
    
    <p><h3>Servi&#231;os Oferecidos para os Alunos do PPGI</h3></p>    
    <hr align="tr">
    
    <?php
	    $database =& JFactory::getDBO();
		$sqlTrancamento = "SELECT * FROM #__trancamentos WHERE idAluno = ".$aluno->id."";
		$database->setQuery($sqlTrancamento);
		$dados = $database->loadObjectList();
		
		$solicTrancamento = sizeof($dados);
    ?>
    
    <table border="0" width="100%">
        <tr>
          <td width="37%" height="21"><b>Aluno(a):</b> <?php echo $aluno->nome;?></td>
          <td width="38%"><b>Matr&#237;cula:</b> <?php echo $aluno->matricula;?></td>
          <td colspan="2"><b>Status:</b> <?php echo $status[$aluno->status];?></td>
        </tr>
        <tr>
          <td height="21"><b>Orientador(a):</b> <?php echo verProfessor($aluno->orientador);?></td>
          <td><b>Linha de Pesquisa:</b> <?php echo verLinhaPesquisa($aluno->area, 2);?></td>
          <td colspan="2"><b>Curso:</b> <?php echo $curso[$aluno->curso];?></td>
        </tr>
        <tr>
          <td height="21"><b>Coeficiente de Rendimento:</b> <?php $CR = calculoPontuacao($aluno->id); if($CR >=0) echo number_format($CR,2); else echo "SEM CR";?></td>
          <td><b>Previsão de Conclusão:</b> <?php $data = $aluno->anoconclusao; echo date("m/Y", strtotime($data));?></td>          
          <td width="6%"><b>Restam:</b></td>
          <td width="19%"><p id="time" class="time"></p></td>
        </tr>
    </table>

	<hr align="tr">
    
	<form name="formAluno" action="index.php?option=com_portalaluno&Itemid=<?php echo $Itemid;?>" method="post">
		<div class="cpanel">
			<div class="icon-wrapper">
				<div class="icon">
           		<a href="javascript:editarAluno(document.formAluno)">
           			<img width="32" height="32" border="0" src="components/com_portalaluno/images/cadastraraluno.png"><span><?php echo JText::_( 'Dados Pessoais' ); ?></span></a>
				</div>
			</div>
            
			<div class="icon-wrapper">
				<div class="icon">
                <a href="javascript:abrirHistorico(<?php echo $aluno->id;?>)">
           			<img width="32" height="32" border="0" src="components/com_portalaluno/images/historico.gif"><span><?php echo JText::_( 'Hist&#243;rico Escolar' ); ?></span></a>
				</div>
			</div>
            
            <?php if ($aluno->status == 0) {
				if ($codPeriodoMatricula = matriculaAberta()) { ?>
				<div class="icon-wrapper">
					<div class="icon">
					<a href="javascript:abrirMatricula(document.formAluno)">
						<img width="32" height="32" border="0" src="components/com_portalaluno/images/matricula.gif"><span><?php echo JText::_( 'Realizar/Ajustar Matr&#237;cula para o Per&#237;odo '. $codPeriodoMatricula); ?></span></a>
					</div>
				</div>
				
				<div class="icon-wrapper">
					<div class="icon">
					<a href="javascript:aproveitamentoDisciplina(document.formAluno)">
						<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/disciplina.png"><span><?php echo JText::_( 'Aproveitamento de Disciplinas' ); ?></span></a>
					</div>
				</div>
                
			    <div class="icon-wrapper">
				<div class="icon">
                <a href="javascript:trancarPeriodo(document.formAluno)">
           			<img width="32" height="32" border="0" src="components/com_portalaluno/images/trancamento.png"><span><?php echo JText::_( 'Trancamento de Curso' ); ?></span></a>
				</div>
			</div>                  

            <?php }
            }
			
            if($aluno->status == 0){
               $database->setQuery("SELECT * from #__periodos WHERE status < 2 ORDER BY periodo, status");
               $periodos = $database->loadObjectList();

               if($periodos){
                  $idPeriodo = $periodos[0]->id;
                  $codPeriodo = $periodos[0]->periodo;
            ?>
            
			<div class="icon-wrapper">
				<div class="icon">
                <a href="javascript:abrirOferta(<?php echo $aluno->id;?>,<?php echo $idPeriodo;?>)">
           			<img width="32" height="32" border="0" src="components/com_portalaluno/images/oferta.png"><span><?php echo JText::_( 'Oferta e Ensalamento do Per&#237;odo '.$codPeriodo); ?></span></a>
				</div>
			</div>
            <?php } } ?>
            
			<div class="icon-wrapper">
				<div class="icon">
			    <a href="javascript:abrirComprovante(<?php echo $aluno->id;?>)">
           			<img width="32" height="32" border="0" src="components/com_portalaluno/images/comprovante.jpg"><span><?php echo JText::_( 'Comprovante de Matr&#237;cula' ); ?></span></a>
				</div>
			</div>
            
			<div class="icon-wrapper">
				<div class="icon">
                <a href="javascript:abrirDeclaracao(<?php echo $aluno->id;?>)">
           			<img width="32" height="32" border="0" src="components/com_portalaluno/images/notificacaosaida.gif"><span><?php echo JText::_( 'Declara&#231;&#227;o de Matr&#237;cula' ); ?></span></a>
				</div>
            </div>
		<?php
				if ($codPeriodoMatricula = matriculaAberta()) { ?>
                
			<div class="icon-wrapper">
				<div class="icon">
                <a href="javascript:prorrogacaoPrazo(document.formAluno)">
           			<img width="32" height="32" border="0" src="components/com_portalaluno/images/prorrogacao.png"><span><?php echo JText::_( 'Prorroga&#231;&#227;o de Prazo' ); ?></span></a>
				</div>
			</div>
            <?php } 	?>
			
		</div>

        <input name='idAluno' type='hidden' value='<?php echo $aluno->id;?>'>
        <input name='task' type='hidden' value='historico'>
        <input name='idPeriodo' type='hidden' value='<?php echo $idPeriodo;?>'>
	</form>

<?php } ?>
 
<?php function formularioMatricula($aluno,$myDisciplinas = NULL,$disciplinas = NULL,$periodo= NULL, $fasePesquisa) {
    	$Itemid = JRequest::getInt('Itemid', 0); ?>
    
		<link type="text/css" href="components/com_portalaluno/jquery-ui-1.8.20.custom.css" rel="Stylesheet" />

		<script src="components/com_portalaluno/jquery-1.7.2.min.js" type="text/javascript"></script>
        <script src="components/com_portalaluno/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>
        
        <script language="JavaScript">
            function IsEmpty(aTextField) {
           if ((aTextField.value.length==0) ||
           (aTextField.value==null)) {
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
                        var datInicio = new Date(ano, mes-1, dia);
                        var hoje = new Date();
        
                        if(datInicio <= hoje){
                              return false;}
        
                        if (ano > 1900)
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
                                                       if(datInicio <= hoje)
                                                           return false;
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
                                                       if(datInicio <= hoje)
                                                           return false;
                                                        return true;
                                                }
                                                if ((bissexto != 1) && (dia <= 28))
                                                {
                                                       if(datInicio <= hoje)
                                                           return false;
                                                        return true;
                                                }
                                                break
                                }
                        }
                }
                 return false;
        }
        
        function ValidateForm(formMatricula)
        {
           if(formMatricula.matriculado.value=='')
           {
              alert('Ao menos uma disciplina deve ser selecionada na matricula.')
              formMatricula.matriculado.focus();
              return false;
           }
        
    
           if(IsEmpty(formMatricula.fasePesquisa))
           {
              alert('O campo Fase da Pesquisa deve ser preenchido.')
              formMatricula.fasePesquisa.focus();
              return false;
           }
        
           if(formMatricula.fasePesquisa.value=='7' && !VerificaData(formMatricula.dataPrevista.value))
           {
              alert('O Campo Data Prevista deve ser preenchido quando a fase de Pesquisa for 7 - Apresentacao e este deve ser maior que a data atual.')
              formMatricula.dataPrevista.focus();
              return false;
           }
        
        return true;
        
        }
        
        function ConfirmarExclusao(formMatricula, idDisc)
        {
            var confirmar = confirm('Confirmar?');
            if(confirmar == true){
                formMatricula.task.value='rmDisciplina';
                formMatricula.rmDisc.value=idDisc;
                formMatricula.submit();
            }
        }
        
        function confirmarCancelamento(form)
        {
            var confirmar = confirm('Confirmar o cancelamento da atual matr\xEDcula? Qualquer mudan\xE7a realizada ser\xE1 perdida!');
            if(confirmar == true){
                form.task.value='cancelarMatricula';
                form.submit();
            }
        }
        </script>
    
        <script>
            $(function() {
                $( "#dataPrevista" ).datepicker({dateFormat: 'dd/mm/yy'});
            });
        </script>


		<form method="post" name="formMatricula" action="index.php?option=com_portalaluno&Itemid=<?php echo $Itemid;?>" method="post"  onsubmit="javascript:return ValidateForm(this)">
			<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
				<div class="cpanel2">
                    <div class="icon" id="toolbar-save">
                        <a href="javascript: if(ValidateForm(document.formMatricula)) document.formMatricula.submit()">
                        <span class="icon-32-save"></span><?php echo JText::_( 'Salvar' ); ?></a>
                    </div>
                    
                    <div class="icon" id="toolbar-cancel">
                        <a href="javascript: confirmarCancelamento(document.formMatricula) ">
                        <span class="icon-32-cancel"></span><?php echo JText::_( 'Cancelar' ); ?></a>
                    </div>
                </div>
                
            	<div class="clr"></div>    
			</div>

			<div class="pagetitle icon-48-cpanel"><h2>Formul&#225;rio de Matr&#237;cula no PPGI/UFAM</h2></div>
    		</div></div>
            
            <b>Como proceder: </b>
            <ul><li>Preenche o formul&#225;rio abaixo escolhendo as disciplinas a serem cursadas no pr&#243;ximo per&#237;odo.</li>						</ul>
            <hr style="width: 100%; height: 2px;">

            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                <tr style="background-color: #7196d8;">
                    <td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Dados do Aluno</font></b></font></td>
                </tr>            
                <tr>
                    <td bgcolor="#CCCCCC"><strong>Nome: </strong></td>
                    <td bgcolor="#CCCCCC"><strong>Matr&#237;cula: </strong></td>
                </tr>
                <tr>
                    <td style="width: 50%;"><?php echo $aluno->nome;?></td>
                    <td style="width: 50%;"><?php echo $aluno->matricula;?></td>
                </tr>
                <tr>
                    <td bgcolor="#CCCCCC"><strong>&#193;rea de Concentra&#231;&#227;o: </strong></td>
                    <td bgcolor="#CCCCCC"><strong>Orientador Acad&#234;mico: </strong></td>
                </tr>
                <tr>
                    <td><?php echo verLinhaPesquisa($aluno->area, 1);?></td>
                    <td><?php echo verProfessor($aluno->orientador); ?></td>
                </tr>            
                <tr>
                    <td bgcolor="#CCCCCC"><strong>Curso: </strong></td>
                    <td bgcolor="#CCCCCC"><strong>Semestre (Periodo/Ano):</strong></td>
                </tr>
                <tr>
                    <?php $cursoDesejado = array (1 => "Mestrado",2 => "Doutorado",3 => "Especial"); ?>
                    <td><?php echo $cursoDesejado[$aluno->curso];?></td>
                    <td><?php echo $periodo->periodo;?> </td>
                </tr>            
            </table>
  
			<br />

            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                <tr style="background-color: #7196d8;">
                    <td style="width: 100%;" colspan="8"><font size="2"> <b><font color="#FFFFFF">Inscri&#231;&#227;o em Disciplinas do Curso</font></b></font></td>
                </tr>            
                <tr>
                    <td align="center" width="2%" bgcolor="#CCCCCC"><strong></strong></td>
                    <td align="center" width="5%" bgcolor="#CCCCCC"><strong>C&#243digo</strong></td>
                    <td align="center" width="28%" bgcolor="#CCCCCC"><strong>Disciplina</strong></td>
                    <td align="center" width="5%" bgcolor="#CCCCCC"><strong>Turma</strong></td>
                    <td align="center" width="8%" bgcolor="#CCCCCC"><strong>Cr&#233;ditos</strong></td>
                    <td align="center" width="6" bgcolor="#CCCCCC"><strong>C.H.</strong></td>
                    <td align="center" width="22%" bgcolor="#CCCCCC"><strong>Professor</strong></td>
                    <td align="center" width="14%" bgcolor="#CCCCCC"><strong>Hor&#225;rio</strong></td>
                </tr>            
            	<?php            
            	if ($myDisciplinas) {
            		foreach ($myDisciplinas as $disciplina) { ?>
                        <tr>
                            <td align="center"><a href="javascript:ConfirmarExclusao(document.formMatricula, <?php echo $disciplina->id;?>)"> <img src="components/com_portalsecretaria/images/lixeira.gif" title="excluir" /> </a></td>
                            <td align="center"><?php echo $disciplina->codigo;?></td>
                            <td align="center"><?php echo $disciplina->nomeDisciplina;?></td>
                            <td align="center"><?php echo $disciplina->turma;?></td>
                            <td align="center"><?php echo $disciplina->creditos;?></td>
                            <td align="center"><?php echo $disciplina->carga;?></td>
                            <td align="center"><?php echo $disciplina->professor;?></td>
                            <td align="center"><?php echo $disciplina->horario;?></td>
                        </tr>
            		<?php } 
				} ?>
            </table>

		<?php 
        function matriculado($id,$myDisciplinas) {
           foreach($myDisciplinas as $disciplina) {
                    if ($disciplina->id == $id)  return 1;
           }
    
        }
      
        function aptoDisciplina($idDisciplina, $idAluno, $curso){
            $database	=& JFactory::getDBO();
            $cargaCurso = array (1 => 24,2 => 40 ,3 => 24);
            $totPeriodos  = array (61 => 1, 66 => 2, 67 => 1);
			$estudoDirigidoDSc = 0;
            
            $sql = "SELECT idDisciplina FROM #__disc_matricula WHERE idDisciplina = $idDisciplina AND idAluno = $idAluno AND conceito IN ('A', 'B', 'C', 'D', 'AP') AND frequencia >= 75";
            $database->setQuery($sql);
            $cursou = $database->loadObjectList();
        
            if($idDisciplina == 68 || $idDisciplina == 69) {
                $sql = "SELECT A.id, sum(creditos) AS tot FROM #__aluno AS A JOIN #__disc_matricula AS DM
                        JOIN #__disciplina AS D ON A.id = DM.idAluno AND D.id = DM.idDisciplina
                        WHERE idAluno = $idAluno AND conceito IN ('A', 'B', 'C', 'D') AND frequencia >= 75 GROUP BY (A.id)";
                $database->setQuery($sql);
    
                $aptidao = $database->loadObjectList();
                if ($aptidao[0]->tot >= $cargaCurso[$curso])  
                    return 1;
                    
                return 0;
            } else if($idDisciplina == 94) {
                $sql = "SELECT A.id, sum(creditos) AS tot FROM #__aluno AS A JOIN #__disc_matricula AS DM
                        JOIN #__disciplina AS D ON A.id = DM.idAluno AND D.id = DM.idDisciplina
                        WHERE idAluno = $idAluno AND conceito IN ('A', 'B', 'C', 'D') AND frequencia >= 75 GROUP BY (A.id)";
                $database->setQuery($sql);
    
                $aptidao = $database->loadObjectList();
                if ($aptidao[0]->tot >= $estudoDirigidoDSc)  
                    return 1;
                    
                return 0;
            }
	     else if (!$cursou) {
                
                if ($idDisciplina == 61 || $idDisciplina == 66) {
                    $sql = "SELECT idAluno, COUNT(DISTINCT idPeriodo) as tot FROM #__disc_matricula WHERE idAluno = $idAluno AND idPeriodo <> 0";
    
                    $database->setQuery($sql);
                    $aptidao = $database->loadObjectList();
                    if ($aptidao[0]->tot >= $totPeriodos[$idDisciplina])  
                        return 1;
                        
                    return 0;
                } else {
                    $sql = "SELECT id, nomeDisciplina, preRequisito FROM #__disciplina WHERE (preRequisito IS NULL OR preRequisito IN
                            (SELECT idDisciplina FROM #__disc_matricula WHERE idAluno = $idAluno))
                            AND id = $idDisciplina";
                    
                    $database->setQuery($sql);		
                    $aptidao = $database->loadObjectList();
                    if($aptidao)  
                        return 1;
                        
                    return 0;
                }
            } else
                return 0;
        } ?>

		<br />

        <label>Nova Disciplina:
            <select name="novaDisciplina" id="novaDisciplina">
            <option value="0">Escolher Disciplina</option>
            <?php
                 for($i=0; $i < count($disciplinas); $i++) {
                    if (matriculado($disciplinas[$i]->id,$myDisciplinas)!=1)
    
                    if (aptoDisciplina($disciplinas[$i]->id, $aluno->id, $aluno->curso)) { ?>
                        <option value="<?php echo $disciplinas[$i]->id."/".$disciplinas[$i]->turma;?>"><?php echo $disciplinas[$i]->codigo."-".$disciplinas[$i]->nomeDisciplina." (".$disciplinas[$i]->professor." : ".$disciplinas[$i]->horario.")";?></option>
                    <?php }
                 } ?>
            </select>
        </label>
  
		<a href="javascript:document.formMatricula.task.value='addDisciplina';javascript:document.formMatricula.submit()"> <img src="components/com_portalaluno/images/add.gif" border="0" height="20" width="20"></a>
		<br /><br />

        <table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr style="background-color: #7196d8;">
                <td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Fase da Pesquisa (Dissertacao/Tese)</font></b></font></td>
            </tr>    
            <tr>
                <td width="50%" bgcolor="#CCCCCC"><p><strong>Fase atual: </strong></td>
                <td width="50%" bgcolor="#CCCCCC"><strong>Data prevista de Apresentação (se fase for 7):</strong> </td>
            </tr>
            <tr>
                <td style="width: 50%;">    
                <select name="fasePesquisa" class="inputbox">
                    <option value=""></option>
                    <option value="1" <?php if ($fasePesquisa == 1) echo 'SELECTED';?>>1. Definição do Tema</option>
                    <option value="2" <?php if ($fasePesquisa == 2) echo 'SELECTED';?>>2. Preparação e Apresentação do Projeto</option>
                    <option value="3" <?php if ($fasePesquisa == 3) echo 'SELECTED';?>>3. Pesquisa Bibliográfica</option>
                    <option value="4" <?php if ($fasePesquisa == 4) echo 'SELECTED';?>>4. Desenvolvimento de Projeto</option>
                    <option value="5" <?php if ($fasePesquisa == 5) echo 'SELECTED';?>>5. Conclusão</option>
                    <option value="6" <?php if ($fasePesquisa == 6) echo 'SELECTED';?>>6. Redação Final </option>
                    <option value="7" <?php if ($fasePesquisa == 7) echo 'SELECTED';?>>7. Apresentação (informar data)</option>
                </select>    
                </td>
                <td style="width: 50%;"><input name="dataPrevista" type="text" id="dataPrevista" size="10" maxlength="10" /></td>
            </tr>    
        </table>
  
            <input name='matriculado' type='hidden' value='<?php echo $myDisciplinas[0]->codigo;?>'>
            <input name='idAluno' type='hidden' value='<?php echo $aluno->id;?>'>
            <input name='idPeriodo' type='hidden' value='<?php echo $periodo->id;?>'>
            <input name='periodo' type='hidden' value='<?php echo $periodo->periodo;?>'>
            
            <input name='task' type='hidden' value='salvarMatricula'>
            <input name='rmDisc' type='hidden' value='200'>
        </form>

    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
    <?php

    }
	//-----------------------------------------------------------------------------------------------------

	function telafinal($idAluno,$idPeriodo , $status) {
		$Itemid = JRequest::getInt('Itemid', 0);
		
		if ($status == 1) { ?>
	
			<script language="JavaScript">
				function imprimirComprovante(idAluno, idPeriodo) {
				   window.open("index.php?option=com_portalaluno&Itemid=191&task=imprimir&idAluno="+idAluno+"&idPeriodo="+idPeriodo,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
				}
			</script>
	
			<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
		
			<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
				<div class="cpanel2">
					<div class="icon" id="toolbar-print">
					<a href="javascript:imprimirComprovante(<?php echo $idAluno;?>,<?php echo $idPeriodo;?>)">
					<span class="icon-32-print"></span><?php echo JText::_( 'Imprimir' ); ?></a>
				</div>
				<div class="icon" id="toolbar-back">
					<a href="index.php?option=com_portalaluno&idAluno=<?php echo $idAluno;?>&Itemid=<?php echo $Itemid;?>">
					<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
				</div>
			
				<div class="clr"></div>        
				</div>
			
				<div class="pagetitle icon-48-cpanel"><h2>Formul&#225;rio de Matr&#237;cula no PPGI/UFAM</h2></div>        
			</div></div>
			
			<?php JFactory::getApplication()->enqueueMessage(JText::_('Matr&#237;cula salva com sucesso!!!')); ?>
			<p><font size="2" style="line-height: 150%">Os dados de sua matr&#237;cula foram cadastrados com sucesso. <br> Clique no link "Imprimir Comprovante de Matr&#237;cula" e imprima a p&#225;gina contendo os dados de sua Matr&#237;cula para seu controle. </font>
			<font size="2" style="line-height: 150%"><br>Voc&#234; poder&#225; atualizar sua matr&#237;cula enquanto o per&#237;odo de matr&#237;culas estiver ativo no PPGI.</font>
			<br><br><font size="2" style="line-height: 150%">Ass: Coordena&#231;&#227;o do PPGI/UFAM</font></p><br>
	
		<?php } else { ?>
	
			<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
				<div class="cpanel2">
					<div class="icon" id="toolbar-back">
					<a href="index.php?option=com_portalaluno&idAluno=<?php echo $idAluno;?>&task=servicos&Itemid=<?php echo $Itemid;?>">
					<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
				</div>
			
				<div class="clr"></div>
				</div>
				
				<div class="pagetitle icon-48-cpanel"><h2>Formul&#225;rio de Matr&#237;cula no PPGI/UFAM</h2></div>
			</div></div>
			
			<?php JFactory::getApplication()->enqueueMessage(JText::_('Matr&#237;cula cancelada com sucesso!!!')); ?>
			<p><font size="2" style="line-height: 150%"><br>Seus dados foram cancelados. Crie uma nova matr&#237;cula para estar regular no PPGI.</font></p>
			<br><br><p align="right"><font size="2" style="line-height: 150%">Ass: Coordena&#231;&#227;o do PPGI/UFAM</font></p>
	
		 <?php }
	}
	
	//-----------------------------------------------------------------------------------------------------

	function jaMatriculado($aluno,$semestre) {    
    	$Itemid = JRequest::getInt('Itemid', 0); ?>

		<form method="post" name="formMatricula" action="index.php?option=com_portalaluno&Itemid=<?php echo $Itemid;?>" method="post"  onsubmit="javascript:return ValidateForm(this)">
			<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
				<div class="cpanel2">
					<div class="icon" id="toolbar-back">
           				<a href="index.php?option=com_portalaluno&idAluno=<?php echo $idAluno;?>&task=servicos&Itemid=<?php echo $Itemid;?>">
	           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
					</div>
				</div>
                
			    <div class="clr"></div>
				</div>
                
				<div class="pagetitle icon-48-cpanel"><h2>Formul&#225;rio de Matr&#237;cula no PPGI/UFAM</h2></div>
    		</div></div>
            
			<?php JFactory::getApplication()->enqueueMessage(JText::_('Aluno(a) Matriculado(a)!')); ?>
    		<p align="center"><font size="2">O(A) aluno(a) <?php echo $aluno->nome;?> j&#225; enviou pedido de matr&#237;cula para o per&#237;odo <?php echo $semestre;?>, e este est&#225; sendo processado pelo PPGI.</font></p>
    		<br><br><p align="right"><font size="2" style="line-height: 150%">Ass: Coordena&#231;&#227;o do PPGI/UFAM</font></p><br />

    		<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
     <?php }
	 
	//-----------------------------------------------------------------------------------------------------

	function CalculoPontuacao($idAluno) {
         $db = & JFactory::getDBO();
         $db->setQuery("
		SELECT idDisciplina, conceito FROM #__disc_matricula
		WHERE idAluno = $idAluno
		AND (conceito NOT IN ('NULL', 'X', 'I') OR (conceito =  'I' AND idDisciplina NOT IN (
		SELECT idDisciplina FROM #__disc_matricula WHERE idAluno = $idAluno AND conceito IN ('A',  'B',  'C'))))");
         $materias = $db->loadObjectList();

         $count=0;
         $tam = 0;

         foreach( $materias as $materia ) {
             if ($materia->conceito == "A"){
                  $count+=4;
                  $tam++;
             } else if ($materia->conceito == "B") {
                  $count+=3;
                  $tam++;
             } else if ($materia->conceito == "C") {
                  $count+=1;
                  $tam++;
             } else if ($materia->conceito == "I") {
                  $count+=0;
                  $tam++;
             }
		}

        if ($tam)
		    $calculo = $count / $tam;
        else
		    $calculo = -1;

		return($calculo);
	}
?>

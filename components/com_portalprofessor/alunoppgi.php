<?php

function identificarAlunoID($idAluno){

    $database	=& JFactory::getDBO();

    $sql = "SELECT * FROM #__aluno WHERE id = $idAluno";
    $database->setQuery( $sql );
    $alunos = $database->loadObjectList();

    return ($alunos[0]);
}

function verProfessor($idProfessor) {

         $db = & JFactory::getDBO();
         $db->setQuery("SELECT nomeProfessor FROM #__professores WHERE id = $idProfessor LIMIT 1");
         $professor = $db->loadResult();

         return($professor);
}

function verLinhaPesquisa($idLinha, $inf) {

         $db = & JFactory::getDBO();
         $db->setQuery("SELECT nome, sigla FROM #__linhaspesquisa WHERE id = $idLinha LIMIT 1");
         $linha = $db->loadObjectList();

         if($inf == 2)
           return($linha[0]->sigla);

         return($linha[0]->nome);
}


/*------------------------------------------*/
function historicoAluno($aluno) {

	global $mosConfig_lang;
    $database	=& JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);

	$sexo = array (1 => 'Masculino',2 => 'Feminimo');
	$curso = array (1 => 'Mestrado',2 => 'Doutorado',3 =>'Especial');
	$pontuacao = array ('A' => 4, 'B' => 3, 'C' => 1, 'D' => 0, 'I' => 0);
	$fasesPesquisa = array (1 => '1. DefiniÃ§Ã£o do Tema', 2 => '2. PreparaÃ§Ã£o e ApresentaÃ§Ã£o do Projeto', 3 => '3. Pesquisa BibliogrÃ¡fica', 4 => '4. Desenvolvimento de Projeto', 5 => '5. ConclusÃ£o', 6 => '6. RedaÃ§Ã£o Final', 7 => '7. ApresentaÃ§Ã£o');

	$mes = array ("01" => "Janeiro","02" => "Fevereiro","03" => "Mar&#231;o","04" => "Abril","05" => "Maio","06" => "Junho","07" => "Julho","08" => "Agosto","09" => "Setembro","10" => "Outubro","11" => "Novembro","12" => "Dezembro");

    $sql = "SELECT M.idDisciplina, D.nomeDisciplina, periodo, codigo, conceito, frequencia, carga, creditos FROM #__disc_matricula AS M JOIN #__disciplina AS D JOIN #__periodos as P ON D.id = M.idDisciplina AND P.id = M.idPeriodo WHERE idAluno = $aluno->id AND M.idPeriodo <> 0 ORDER BY periodo, codigo";
    $database->setQuery( $sql );
    $disciplinasCursadas = $database->loadObjectList();

    $sql = "SELECT M.idDisciplina, D.nomeDisciplina, codigo, conceito, frequencia, carga, creditos FROM #__disc_matricula AS M JOIN #__disciplina AS D ON D.id = M.idDisciplina WHERE idAluno = $aluno->id AND M.idPeriodo = 0 ORDER BY codigo";
    $database->setQuery( $sql );
    $disciplinasAproveitadas = $database->loadObjectList();

    $sql = "SELECT MAX(semestre), fasePesquisa FROM #__matricula WHERE idAluno = $aluno->id";
    $database->setQuery( $sql );
    $fases = $database->loadObjectList();

    $sql = "SELECT * FROM #__banca WHERE idAluno = $aluno->id AND tipoDefesa = 'Q' ORDER BY presidente DESC, nomeMembro ASC";
    $database->setQuery( $sql );
    $bancaqualificacao = $database->loadObjectList();

    $sql = "SELECT * FROM #__banca WHERE idAluno = $aluno->id AND tipoDefesa = 'T' ORDER BY presidente DESC, nomeMembro ASC";
    $database->setQuery( $sql );
    $bancatese = $database->loadObjectList();

?>

	<script type="text/javascript">
        jQuery.fn.toggleText = function(a,b) {
				return   this.html(this.html().replace(new RegExp("("+a+"|"+b+")"),function(x){return(x==a)?b:a;}));
			}
	
			$(document).ready(function(){
				$('.tg2').before("<span><h2><img src='components/com_portalprofessor/images/show_hide.png' border='0'>Exames de Profici&#234;ncia, Qualifica&#231;&#227;o e Defesa</h2></span>");
				$('.tg2').css('display', 'none')
				$('.tgl').before("<span><h2><img src='components/com_portalprofessor/images/show_hide.png' align='Mostrar/Ocultar' border='0'>Dados sobre Disciplinas</h2></span>");
				$('.tgl').css('display', 'none')
	
				$('span', '#box-toggle').click(function() {
				$(this).next().slideToggle('slow')
				.siblings('.tgl:visible').slideToggle('fast');
	
				// aqui começa o funcionamento do plugin
				$(this).toggleText("Nada","Nada")
				.siblings('span').next('.tgl:visible').prev()
				.toggleText("Nada","Nada")
			});
		})

	</script>
    
   	<style type="text/css">
        div.box {
            border: 1px dotted #CCC;
            width: 100%;
            margin-bottom: 10px;
        }
        
        div.box img {
            float: right;
            margin: 5px;
            cursor: pointer;
        }
        
        p.conteudo {
            padding: 10px;
            text-align: justify;
        }
    </style>

    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
            <div class="icon" id="toolbar-back">
            <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=alunos">
                <span class="icon-32-back"></span>Voltar</a>
            </div>
		</div>
        <div class="clr"></div>
        </div>
    
          <div class="pagetitle icon-48-user"><h2>Consulta aos Dados de Orientando</h2></div>
    </div></div>
    
	<h2>Dados do Aluno</h2>
    <hr />

    <table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
      <tbody>
        <tr>
          <td style='font-weight: bold;'>NOME:</td>
          <td colspan='3' ><?php echo $aluno->nome;?></td>
        </tr>
        <tr>
          <td style='font-weight: bold;' width='20%'>NATURALIDADE:<br>NACIONALIDADE:</td>
          <td width='30%'><?php echo $aluno->cidade;?><br><?php echo $aluno->pais;?></td>
          <td style='font-weight: bold;' width='25%'>DATA DE NASCIMENTO:</td>
          <td width='25%'><?php echo  $aluno->datanascimento;?></td>
        </tr>
        <tr>
          <td style='font-weight: bold;'>LINHA DE PESQUISA:</td>
          <td colspan='3'><?php echo   verLinhaPesquisa($aluno->area, 1);?></td>
        </tr>
        <tr>
          <td style='font-weight: bold;'>N&Iacute;VEL:</td>
          <td><?php echo  $curso[$aluno->curso];?></td>
          <td style='font-weight: bold;'>ANO DE INGRESSO:</td>
          <td><?php echo  $aluno->anoingresso;?></td>
        </tr>
        <tr>
          <td style='font-weight: bold;'>E-MAIL:</td>
          <td><?php echo  $aluno->email;?></td>
          <td style='font-weight: bold;'>TELEFONE:</td>
          <td><?php echo  $aluno->telresidencial;?><br><?php echo  $aluno->telcelular;?></td>
        </tr>
      </tbody>
    </table>

    <div id="box-toggle" class="box">
    <div class="tgl">

	<h3>Disciplinas Cursadas</h3>
    <hr />
    
    <table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
      <tbody>
        <tr bgcolor='#B0B0B0'>
          <td style='text-align: center; font-weight: bold;' width='10%'>C&Oacute;DIGO</td>
          <td style='width: 10%; text-align: center; font-weight: bold;'>PER&Iacute;ODO</td>
          <td style='text-align: center; font-weight: bold;' width='40%'>DISCIPLINAS CURSADAS</td>
          <td style='text-align: center; font-weight: bold;' width='10%'>CONC.</td>
          <td style='text-align: center; font-weight: bold;' width='10%'>FR.%</td>
          <td style='text-align: center; font-weight: bold;' width='10%'>CR</td>
          <td style='text-align: center; font-weight: bold;' width='10%'>C/H</td>
        </tr>
    
        <?php
        $pontos = 0;
        $creditos = 0;
        $totDisciplinas = 0;
        foreach( $disciplinasCursadas as $disc )
        {
        ?>
        <tr>
          <td align='center'><?php echo $disc->codigo;?></td>
          <td align='center'><?php echo $disc->periodo;?></td>
          <td><?php echo $disc->nomeDisciplina;?></td>
          <td align='center'><?php echo $disc->conceito;?></td>
          <td align='center'><?php echo number_format($disc->frequencia, 2);?></td>
          <td align='center'><?php echo $disc->creditos;?></td>
          <td align='center'><?php echo $disc->carga;?></td>
        </tr>
        <?php
             $pontos += $pontuacao[$disc->conceito];
             $totDisciplinas++;
             if($disc->conceito == 'A' ||  $disc->conceito == 'B' || $disc->conceito == 'C' ) $creditos += $disc->creditos;
        } ?>
    
      </tbody>
    </table>
    
	<?php if($disciplinasAproveitadas <> NULL){ ?>

    <table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
      <tbody>
        <tr bgcolor='#B0B0B0'>
          <td style='text-align: center; font-weight: bold;' width='10%'>C&Oacute;DIGO</td>
          <td style='text-align: center; font-weight: bold;' width='50%'>DISCIPLINAS APROVEITADAS</td>
          <td style='text-align: center; font-weight: bold;' width='10%'>CONC.</td>
          <td style='text-align: center; font-weight: bold;' width='10%'>FR.%</td>
          <td style='text-align: center; font-weight: bold;' width='10%'>CR</td>
          <td style='text-align: center; font-weight: bold;' width='10%'>C/H</td>
        </tr>
    
        <?php
    
        foreach( $disciplinasAproveitadas as $disc )
        {
        ?>
        <tr>
          <td align='center'><?php echo $disc->codigo;?></td>
          <td><?php echo $disc->nomeDisciplina;?></td>
          <td align='center'><?php echo $disc->conceito;?></td>
          <td align='center'><?php echo number_format($disc->frequencia, 2);?></td>
          <td align='center'><?php echo $disc->creditos;?></td>
          <td align='center'><?php echo $disc->carga;?></td>
        </tr>
    
        <?php
             $pontos += $pontuacao[$disc->conceito];
             $totDisciplinas++;
             if($disc->conceito == 'A' ||  $disc->conceito == 'B' || $disc->conceito == 'C' ) $creditos += $disc->creditos;
    //$pontos/$totDisciplinas
        }
        ?>
    
      </tbody>
    </table>
	<?php } ?>
	<hr />
    
	<h3>Avalia&#231;&#227;o do Aluno</h3>
	<ul>
   <li>Coeficiente de Rendimento: <?php echo number_format(CalculoPontuacao($aluno->id), 2);?></li>
   <li>Cr&#233;ditos Cumpridos: <?php echo $creditos;?></li>
</ul>
</div>
<hr style='width: 100%; height: 2px; font-family: Verdana;'>
<div class="tg2">
<h3>Profici&#234;ncia em Idioma Estrangeiro</h3>
<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
  <tbody>
    <tr>
      <td width='15%' style='font-weight: bold;'>IDIOMA:</td>
      <td width='15%'><?php echo  $aluno->idiomaExameProf;?></td>
      <td width='10%'><span style='font-weight: bold;'>SITUA&Ccedil;&Atilde;O:</span></td>
      <td width='10%'><?php echo  $aluno->conceitoExameProf;?></td>
      <td width='25%'><span style='font-weight: bold;'>DATA DO EXAME:</span></td>
      <td width='25%'><?php echo  $aluno->dataExameProf;?></td>
    </tr>
  </tbody>
</table>
<br />
<?php if($aluno->curso == 2){ ?>
<hr style='width: 100%; height: 2px; font-family: Verdana;'>
<h3>Exame de Qualifica&#231;&#227;o I</h3>
<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
  <tbody>
    <tr>
      <td style='font-weight: bold;'>TEMA:</td>
      <td colspan='3' rowspan='1'><?php echo  $aluno->tituloQual1;?></td>
    </tr>
    <tr>
      <td width='30%' style='font-weight: bold;'>DATA:</td>
      <td width='20%'><?php echo  $aluno->dataQual1;?></td>
      <td width='25%' style='font-weight: bold;'>SITUA&Ccedil;&Atilde;O:</td>
      <td width='25%'><?php echo  $aluno->conceitoQual1;?></td>
    </tr>
    <tr>
      <td width='30%' style='font-weight: bold;'>EXAMINADOR(A):</td>
      <td colspan='3'><?php echo  $aluno->examinadorQual1;?></td>
    </tr>
  </tbody>
</table>
<?php
}
?>
<hr style='width: 100%; height: 2px; font-family: Verdana;'>
<h3>Exame de Qualifica&#231;&#227;o
<?php if($aluno->curso == 2) echo " II"; ?></h3>

<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
  <tbody>
    <tr>
      <td style='font-weight: bold;'>TEMA:</td>
      <td colspan='3' rowspan='1'><?php echo  $aluno->tituloQual2;?></td>
    </tr>
    <tr>
      <td width='30%' style='font-weight: bold;'>DATA:</td>
      <td width='20%'><?php echo  $aluno->dataQual2;?></td>
      <td width='25%' style='font-weight: bold;'>SITUA&Ccedil;&Atilde;O:</td>
      <td width='25%'><?php echo  $aluno->conceitoQual2;?></td>
    </tr>
    <tr>
      <td style='font-weight: bold;'>COMISS&Atilde;O EXAMINADORA:</td>
      <td colspan='3' rowspan='1'>

         <?php
            foreach( $bancaqualificacao as $banca )
            	{
                     echo $banca->nomeMembro."<br />";
                }
         ?>

        </td>
    </tr>
  </tbody>
</table>
<br />
<hr style='width: 100%; height: 2px; font-family: Verdana;'>
<h3>Defesa de <?php  if($aluno->curso == 2) echo "Tese"; else echo "Disserta&#231;&#227;o"; ?></h3>
<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
  <tbody>
    <tr>
      <td style='font-weight: bold;'>T&Iacute;TULO:</td>
      <td colspan='3' rowspan='1'><?php echo  $aluno->tituloTese;?></td>
    </tr>
    <tr>
      <td width='30%' style='font-weight: bold;'>DATA:</td>
      <td width='20%'><?php echo  $aluno->dataTese;?></td>
      <td width='25%' style='font-weight: bold;'>SITUA&Ccedil;&Atilde;O:</td>
      <td width='25%'><?php echo  $aluno->conceitoTese;?></td>
    </tr>
    <tr>
      <td style='font-weight: bold;'>COMISS&Atilde;O EXAMINADORA:</td>
      <td colspan='3' rowspan='1'>
      <?php
           foreach( $bancatese as $banca )
	       {
             echo $banca->nomeMembro;
             if($banca->presidente == 1) echo " (presidente)";
               echo " - ".$banca->instituicaoMembro."<br />";
           }
      ?>

      </td>
    </tr>
  </tbody>
</table>
</div>
</div>
    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">

<?php

}

////////////////////////////////////////////////////////////////////////////////
function CalculoPontuacao($idAluno){

         $db = & JFactory::getDBO();
         $db->setQuery("
		SELECT idDisciplina, conceito FROM #__disc_matricula
		WHERE idAluno = $idAluno
		AND (conceito NOT IN ('NULL', 'X', 'I') OR (conceito =  'I' AND idDisciplina NOT IN (
		SELECT idDisciplina FROM #__disc_matricula
		WHERE idAluno = $idAluno AND conceito IN ('A',  'B',  'C'))))");

         $materias = $db->loadObjectList();
         
         $count=0;
         $tam = 0;
         
         foreach( $materias as $materia ){
             if ($materia->conceito == "A"){
                  $count+=4;
                  $tam++;
             }
             else if ($materia->conceito == "B"){
                  $count+=3;
                  $tam++;
             }
             else if ($materia->conceito == "C"){
                  $count+=1;
                  $tam++;
             }
             else if ($materia->conceito == "I"){
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

function listarAlunosPPGI($nome = "", $status, $anoingresso, $curso, $professor){

    $database =& JFactory::getDBO();

    if($status == NULL) $status =0;

    $sqlEstendido = "";
    $sqlEstendido2 = "";

    if($anoingresso) $sqlEstendido = "AND anoingresso = $anoingresso";

    if($curso > 0) $sqlEstendido2 = "AND curso = $curso";

    if($status < 6)
      $sql = "SELECT * FROM #__aluno WHERE orientador = ".$professor->id." AND nome LIKE '%$nome%' AND status = $status ".$sqlEstendido." ".$sqlEstendido2." ORDER BY nome ";
    else
       $sql = "SELECT * FROM #__aluno WHERE orientador = ".$professor->id." AND nome LIKE '%$nome%' ".$sqlEstendido." ".$sqlEstendido2." ORDER BY nome ";

    $database->setQuery( $sql );
    $alunos = $database->loadObjectList();

    listarAlunosPPGIHTML($alunos, $nome, $status, $anoingresso, $curso, $professor);
}


// LISTAGEM DE ORIENTANDOS
function listarAlunosPPGIHTML($alunos, $nome, $status, $anoingresso, $curso, $professor) {
    $Itemid = JRequest::getInt('Itemid', 0);
    $database 	=& JFactory::getDBO(); ?>
    
	<script language="JavaScript">
        function visualizar(form){        
           var idSelecionado = 0;
		   
           for(i = 0;i < form.idAlunoSelec.length;i++)
                if(form.idAlunoSelec[i].checked) idSelecionado = form.idAlunoSelec[i].value;
        
           if(idSelecionado > 0){
				form.task.value = 'verAluno';
				form.idAluno.value = idSelecionado;
				form.submit();
           } else {
           	alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o.')
           }
        }


        function solicitabanca(form){        
            var idSelecionado = 0;
 		   
            for(i = 0;i < form.idAlunoSelec.length;i++)
                 if(form.idAlunoSelec[i].checked) idSelecionado = form.idAlunoSelec[i].value;

            form2 = document.getElementById("form2");
            
            if(idSelecionado > 0){
	    		idaluno = document.getElementById('idalunosolicitabanca');            
 				idaluno.value = idSelecionado;
 				form2.submit();
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
    
	<link rel="stylesheet" href="components/com_portalsecretaria/assets/css/estilo.css" type="text/css" />
	<form method="post" id="form2" name="form2" enctype="multipart/form-data" action="index.php?option=com_defesasorientador&task=solicitarbanca">
	
		<input type="hidden" name="idaluno" id="idalunosolicitabanca">
	</form>
    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
    
		<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
            
   					<div class="icon" id="toolbar-banca">
						<a href="javascript:solicitabanca(document.form)" class="toolbar">
                        <span class="icon-32-checkin"></span>Solicitar</br> Banca</a>
					</div>
                <div class="icon" id="toolbar-preview">
                    <a href="javascript:visualizar(document.form)" class="toolbar">
                    <span class="icon-32-preview"></span>Visualizar</a>
                </div>
                
                <div class="icon" id="toolbar-back">
                    <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
                    <span class="icon-32-back"></span>Voltar</a>
                </div>
            </div>
            <div class="clr"></div>
            </div>
            
            <div class="pagetitle icon-48-groups"><h2>Lista de Orientandos</h2></div>
        </div></div>

		<!-- FILTRO DA BUSCA -->
		<fieldset>
        	<legend>Filtros para consulta</legend>
            <table width="100%">
                <tr>
                    <td>Nome</td>
                    <td>Curso</td>
                    <td>Ano de Ingresso</td>
                    <td>Situação</td>
                    <td></td>
                </tr>
                <tr>
	                <td><input id="buscaNome" name="buscaNome" type="text" value="<?php echo $nome;?>"/></td>
                    <td>
                    <select name="buscaCurso">
                        <option value="0" <?php if($curso == 0) echo 'SELECTED';?>>Todos</option>
                        <option value="1" <?php if($curso == 1) echo 'SELECTED';?>>Mestrado</option>
                        <option value="2" <?php if($curso == 2) echo 'SELECTED';?>>Doutorado</option>
                        <option value="3" <?php if($curso == 3) echo 'SELECTED';?>>Especial</option>
                    </select>
                    </td>
                    <td>
                    	<select name="buscaAnoIngresso">
            				<option value="">Todos</option>
	        				<?php
			                 $database->setQuery("SELECT DISTINCT anoingresso from #__aluno WHERE orientador = ".$professor->id." ORDER BY anoingresso");
	        			     $anos = $database->loadObjectList();

				             foreach($anos as $ano) { ?>
                             	<option value="<?php echo $ano->anoingresso;?>" <?php if($anoingresso == $ano->anoingresso) echo 'SELECTED'; ?>> <?php echo $ano->anoingresso;?></option>
	          				<?php } ?>
			            </select>
                    </td>
                    <td><select name="buscaStatus">
                            <option value="0" <?php if($status == 0) echo 'SELECTED';?>>Alunos Correntes</option>
                            <option value="1" <?php if($status == 1) echo 'SELECTED';?>>Alunos Egressos</option>
                            <option value="2" <?php if($status == 2) echo 'SELECTED';?>>Alunos Desistentes</option>
                            <option value="3" <?php if($status == 3) echo 'SELECTED';?>>Alunos Desligados</option>
                            <option value="4" <?php if($status == 4) echo 'SELECTED';?>>Alunos Jubilados</option>
                            <option value="5" <?php if($status == 5) echo 'SELECTED';?>>Alunos com Matr&#237;cula Trancada</option>
                            <option value="6" <?php if($status == 6) echo 'SELECTED';?>>Todos</option>
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
                <th>#Matr</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Curso</th>
                <th>Regime</th>
              </tr>
            </thead>
             
     		<tbody>
			<?php
            $status = array (0 => "Aluno Corrente",1 => "Aluno Egresso",2 => "Aluno Desistente",3 => "Aluno Desligado",4 => "Aluno Jubilado",5 => "Aluno com Matr&#237;cula Trancada");
			
            $statusImg = array (0 => "ativo.png",1 => "egresso.png",2 => "desistente.png",3 => "desligado.gif",4 => "reprovar.gif",5 => "trancado.jpg");
			
            $curso = array (1 => "mestrado",2 => "doutorado",3 => "especial");
			
            $regime = array (1 => "Integral",2 => "Parcial");

			foreach( $alunos as $aluno ) { ?>

            <tr>
                <td width='16'><input type="radio" name="idAlunoSelec" value="<?php echo $aluno->id;?>"></td>
                <td><img border='0' src='components/com_portalsecretaria/images/<?php echo $statusImg[$aluno->status];?>' width='16' height='16' title='<?php echo $status[$aluno->status];?>'></td>
                <td><?php echo $aluno->matricula;?></td>
                <td><?php echo $aluno->nome;?></td>
                <td><img border='0' src='components/com_portalsecretaria/images/emailButton.png' width='16' height='16' title='<?php echo $aluno->email;?>'></td>
                <td align="center"><?php if($aluno->telresidencial) echo "<img src='components/com_portalsecretaria/images/telefone.png' title='".$aluno->telresidencial."'>";?><?php if($aluno->telcelular) echo "<img src='components/com_portalsecretaria/images/celular.png' title='".$aluno->telcelular."'>";?></td>
                <td><img border='0' src='components/com_portalsecretaria/images/<?php echo $curso[$aluno->curso];?>.gif' title='<?php echo $curso[$aluno->curso];?>'></td>
                <td><?php echo $regime[$aluno->regime];?></td>
            </tr>

			<?php } ?>
		</tbody>
     </table>     
     <br />
     
     <span class="label label-inverse">Total de Alunos: <?php echo sizeof($alunos);?></span>
     
     <input name='task' type='hidden' value='alunos' />
     <input name='idAlunoSelec' type='hidden' value='0' />
     <input name='idAluno' type='hidden' value='' />
</form>

<?php } ?>

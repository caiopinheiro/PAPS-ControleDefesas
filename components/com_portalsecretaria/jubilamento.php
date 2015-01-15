<?php // LISTAR JUBILAMENTO
function listarJubilamento($nome="",$periodoPes,$curso, $cr) {    
    $database =& JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);

    $sqlEstendido = "";
	
    if ($curso)  
		$sqlEstendido = " AND curso=$curso";
		
    if ($periodoPes)  
		$sqlEstendido = " AND anoingresso='$periodoPes'";
    
    $sql = "SELECT id,nome,matricula,anoingresso,curso,area FROM #__aluno WHERE nome LIKE '%$nome%' AND status=0 $sqlEstendido ORDER BY nome";

    $database->setQuery( $sql );
    $alunos = $database->loadObjectList();

	$linhaPesquisa = array (1 => "bd_ri",2 => "se_es",3 => "ia",4 => "visao",5 => "redes",6 => "otimizacao");
	$cursoImg = array (1 => "mestrado",2 => "doutorado",3 => "especial"); ?>
    
	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />

	<script language="JavaScript">
        function ValidateformCadastro(form) {
		    return true;
	    }
    
    	function validarForm(form, idJubilamento) {    
		   form.idJubilamento.value = idJubilamento;
		   form.submit();
		   return true;
	    }
    
    	function voltarForm(form) {
			form.task.value = 'jubilamento';
        	form.submit();
       		return true;
	    }
    
    	function imprimir(form) {
        	window.open("index.php?option=com_portalsecretaria&Itemid=190&task=imprimirJubilamento","_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
	    }
    </script>

    <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();
		});
	</script>

	<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>" >
    
    	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        	<div class="cpanel2">
				<div class="icon" id="toolbar-print">
           			<a href="javascript:imprimir()">
           				<span class="icon-32-print"></span><?php echo JText::_( 'Imprimir' ); ?>
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
          	<div class="pagetitle icon-48-cpanel"><h2>Consulta para Jubilamento</h2></div>
		</div></div>

        <!-- FILTRO DA BUSCA -->
		<fieldset>
        	<legend>Filtros para consulta</legend>
            
            <table width="100%">
            	<tr>
                	<td width="25%">Nome do Aluno</td>
                    <td width="15%">Curso</td>
                    <td width="23%">Justificativa</td>
                    <td width="28%">Ingresso</td>
                    <td></td>
                </tr>
            </table>
            

			<input id="buscaAluno" name="buscaAluno" size="25" value="<?php echo $nome;?>" type="text">

			<select name="buscaCurso">
                <option value="">                                                  Todos            </option>
                <option value="1" <?php if ($curso == "1")  echo 'SELECTED';?>>    Mestrado         </option>
                <option value="2" <?php if ($curso == "2")  echo 'SELECTED';?>>    Doutorado        </option>
                <option value="3" <?php if ($curso == "3")  echo 'SELECTED';?>>    Especial         </option>
            </select>
            
			<select name="buscaCR">
                <option value="0">CR Menor que 2.0</option>
                <option value="1" <?php if ($cr == 1)  echo 'SELECTED';?>>2 ou mais reprova&#231;&#245;es</option>
                <option value="2" <?php if ($cr == 2)  echo 'SELECTED';?>>Repetiu reprova&#231;&#227;o</option>
                <option value="3" <?php if ($cr == 3)  echo 'SELECTED';?>>Todos</option>
            </select> 
            
            <select name="periodo">
                <option value="">Todos</option>
                <?php
                     $database->setQuery("SELECT DISTINCT anoingresso from #__aluno WHERE status = 0 ORDER BY RIGHT(anoingresso, 4)");
                     $periodos = $database->loadObjectList();
        
                     foreach($periodos as $periodo) { ?>
                     	<option value="<?php echo $periodo->anoingresso;?>"
					  <?php
                          if($periodoPes == $periodo->anoingresso) echo 'SELECTED';
                      ?>
                      > <?php echo date("m/Y", strtotime($periodo->anoingresso));?></option>
                      <?php
                           }
                      ?>
			</select>
            
            <button type="submit" name="buscar" class="btn btn-primary pull-right" title="Filtra busca">
                <i class="icone-search icone-white"></i> Consultar
            </button>
		</fieldset>

        <table class="table table-striped" id="tablesorter-imasters">
	        <thead> 
            <tr>
		        <th>Matr&iacute;cula</th>
		        <th>Nome</th>
		        <th>Curso</th>
		        <th>Linha de Pesquisa</th>
		        <th>Ingresso</th>
		        <th>Reprovações</th>
		        <th>Repetência</th>
		        <th>CR</th>
        	</tr>
        </thead>

		<tbody>
		<?php   	
		   	$curso = array (1 => 'Mestrado',2 => 'Doutorado',3 =>'Especial');	
			$i = 0;
			
			foreach( $alunos as $aluno ) {
				$pontos = CalculoPontuacao($aluno->id);
				$reprovou = CalculoReprovacao($aluno->id);
				$reprovacaoRepetida = CalculoReprovacaoRepetida($aluno->id);
				$totReprovou = sizeof($reprovou);
				$totReprovacaoRepetida = sizeof($reprovacaoRepetida);
				
	    		if ($cr == 3 || ($cr == 2 && $totReprovacaoRepetida > 0)  || ($cr == 1 && $totReprovou >= 2) || ($cr == 0 && $pontos >= 0 && $pontos < 2)){

				$i = $i + 1; ?>
                
            <tr>        
            <td><?php echo $aluno->matricula?></td>
            <td><?php echo $aluno->nome?></td>
            <td><img border='0' src='components/com_portalsecretaria/images/<?php echo $cursoImg[$aluno->curso];?>.gif' title='<?php echo $curso[$aluno->curso];?>'></td>
            <td><div align="center"><img border='0' src='components/com_portalsecretaria/images/<?php echo $linhaPesquisa[$aluno->area];?>.gif' title='<?php echo verLinhaPesquisa($aluno->area, 1);?>'></div></td>
            <td><div align="center"><?php echo date("m/Y", strtotime($aluno->anoingresso));?></div></td>
            <td><div align="center"><?php echo $totReprovou;
                if($totReprovou){
                    echo "<img src='components/com_portalsecretaria/images/justificativa.gif' width='16' height='16' border='0' title='";
                    foreach($reprovou as $rep){
                       echo "[".$rep->periodo."] ".$rep->nomeDisciplina."\n";
                    }
                echo "'>";
                }
            ?></div></td>
    
            <td><div align="center"><?php echo $totReprovacaoRepetida;
                if($totReprovacaoRepetida){
                    echo "<img src='components/com_portalsecretaria/images/justificativa.gif' width='16' height='16' border='0' title='";
                    foreach($reprovacaoRepetida as $rep){
                       echo $rep->nomeDisciplina." [Reprova&#231;&#245;es = ".$rep->totReprovacao."]\n";
                    }
                echo "'>";
                }
            ?></div></td>
            <td><?php if($pontos >= 0)
                         if($pontos < 2){
                            echo "<font color='#FF0000'>";
                            echo number_format($pontos, 1);
                            echo "</font>";
                         }
                         else{
                            echo number_format($pontos, 1);
                         }
                      else echo "Sem CR";?>
            </td>
        </tr>
        <?php } } ?>
    
		</tbody>
	</table>

	<br />
	<span class="label label-inverse">Total de Alunos: <b><?php echo $i;?></b></span>
    
    <input name='task' type='hidden' value='jubilamento' />
    <input name='idJubilamento' type='hidden' value='' />

</form>


<?php } ?>



<?php // FUNÇÕES
function LinhaPesquisa($idLinha, $inf) {
	 $db = & JFactory::getDBO();
	 $db->setQuery("SELECT nome, sigla FROM #__linhaspesquisa WHERE id = $idLinha LIMIT 1");
	 $linha = $db->loadObjectList();

     if($inf == 2)
		return($linha[0]->sigla);

        return($linha[0]->nome);
} 

function CalculoPontuacao($idAluno){
	$db = & JFactory::getDBO();
	
    $db->setQuery("
		SELECT idDisciplina, conceito FROM #__disc_matricula
		WHERE idAluno = $idAluno
		AND (conceito NOT IN ('NULL', 'X', 'R') OR (conceito =  'R' AND idDisciplina NOT IN (
		SELECT idDisciplina FROM #__disc_matricula WHERE idAluno = $idAluno AND conceito IN ('A',  'B',  'C'))))");

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
		 else if ($materia->conceito == "R"){
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

function CalculoReprovacao($idAluno) {
	 $db = & JFactory::getDBO();
	 
	 $db->setQuery("SELECT idDisciplina, nomeDisciplina,periodo FROM #__disc_matricula AS DM JOIN #__disciplina AS D ON D.id = DM.idDisciplina JOIN #__periodos  AS P ON P.id = DM.idPeriodo WHERE idAluno = $idAluno AND conceito IN ('D','E','R') ORDER BY periodo");
	 $materias = $db->loadObjectList();

	 return($materias);
}

function CalculoReprovacaoRepetida($idAluno) {
	 $db = & JFactory::getDBO();
	 
	 $db->setQuery("SELECT nomeDisciplina, COUNT(idDisciplina) as totReprovacao
						   FROM j17_disc_matricula AS DM
						   JOIN j17_disciplina AS D ON D.id = DM.idDisciplina
						   JOIN j17_periodos  AS P ON P.id = DM.idPeriodo
						   WHERE idAluno = $idAluno AND conceito IN ('D','E','R')
						   GROUP BY idDisciplina
						   HAVING COUNT(idDisciplina) > 1
	 ");
	 $materias = $db->loadObjectList();

	 return($materias);
}

function imprimirJubilamento($nome,$periodoPes,$curso, $cr) {
//--------------- Mesma funcao do comeco do codigo -------------------//
    $database =& JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);

    $sqlEstendido = "";
    if ($curso)  $sqlEstendido = " AND curso=$curso";
    if ($periodoPes)  $sqlEstendido = " AND anoingresso='$periodoPes'";

    $sql = "SELECT id,nome,matricula,anoingresso,curso,area FROM #__aluno WHERE nome LIKE '%$nome%' AND status=0 $sqlEstendido ORDER BY nome";

    $database->setQuery( $sql );
    $alunos = $database->loadObjectList();

//--------------- Fim da Mesma funcao do comeco do codigo -------------------//

    $database =& JFactory::getDBO();

    $mes = date("m");
   	$curso = array (1 => 'Mestrado',2 => 'Doutorado',3 =>'Especial');
    switch ($mes){
		case 1: $mes = "Janeiro"; break;
		case 2: $mes = "Fevereiro"; break;
		case 3: $mes = "Março"; break;
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

    $chave = md5($aluno->id.$aluno->nome.date("l jS \of F Y h:i:s A"));
	$comprovanteMatricula = "components/com_portalaluno/forms/$chave.pdf"; //??? Verificar
	$arq = fopen($comprovanteMatricula, 'w') or die("CREATE ERROR");

    $pdf = new Cezpdf();
    $pdf->selectFont('pdf-php/fonts/Helvetica.afm');
    $optionsText = array('justification'=>'center', 'spacing'=>1.5);
    $dados = array('justification'=>'justify', 'spacing'=>1.0);
    $optionsTable = array('fontSize'=>8, 'titleFontSize'=>8, 'xPos'=>'center', 'width'=>560, 'cols'=>array('Matrícula'=>array('width'=>70, 'justification'=>'center'),'Nome'=>array('width'=>260), 'Ingresso'=>array('width'=>50, 'justification'=>'center'), 'Curso'=>array('width'=>50, 'justification'=>'center'), 'Linha de Pesquisa'=>array('width'=>100, 'justification'=>'center'), 'CR'=>array('width'=>30, 'justification'=>'center')));

    $pdf->addJpegFromFile('components/com_portalaluno/images/ufam.jpg', 490, 720, 75);
    $pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',15,$optionsText);
    $pdf->ezText('<b>Instituto de Computação - IComp</b>',12,$optionsText);
    $pdf->ezText('<b>Programa de Pós-Graduação em Informática - PPGI</b>',12,$optionsText);
    $pdf->addText(480,780,8,"<b>Data:</b> ".date("d/m/Y"),0,0);
    $pdf->addText(480,790,8,"<b>Hora:</b> ".date("H:i"),0,0);
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->setLineStyle(3);
    $pdf->line(20, 700, 580, 700);
    $pdf->ezText('<b>Lista de Jubilamento</b>',12,$optionsText);
    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    

    foreach( $alunos as $aluno) {
	    $pontos = CalculoPontuacao($aluno->id);
		
	    if ($pontos >= 0 && $pontos < 2){
        $TabelaAluno[] = array('Matrícula'=>$aluno->matricula, 'Nome'=>utf8_decode($aluno->nome), 'Ingresso'=>$aluno->anoingresso,'Curso'=>$curso[$aluno->curso], 'Linha de Pesquisa'=>utf8_decode(LinhaPesquisa($aluno->area, 2)), 'CR'=>utf8_decode(number_format($pontos,2)));
        }
    }

    if($TabelaAluno)
       $pdf->ezTable($TabelaAluno,$cols,'',$optionsTable);
    else
       $pdf->ezText('Não existem alunos candidatos a jubilamento.');  //Para quebra de linha

    $pdf->ezText('');  //Para quebra de linha
    $pdf->ezText('');  //Para quebra de linha
    $pdf->addText(235,90,8,"<b>Manaus, ".date("d")." de ".$mes." de ".date("Y")."</b>",0,0);
    $pdf->line(20, 55, 580, 55);
    $pdf->addText(120,40,8,'Av. Rodrigo Otávio, 6.200 • Coroado • Campus Universitário •  CEP 69077-000 •  Manaus, AM, Brasil',0,0);
    $pdf->addJpegFromFile('components/com_portalaluno/images/icon_telefone.jpg', 140, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalaluno/images/icon_email.jpg', 253, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalaluno/images/icon_casa.jpg', 377, 30, 8, 8);
    $pdf->addText(150,30,8,'Tel. (092) 3305 2808 / 2809       E-mail: secppgi@ufam.edu.br        www.ppgi.ufam.edu.br',0,0);

    $pdfcode = $pdf->output();
    fwrite($arq,$pdfcode);
	fclose($arq);

	header("Location: ".$comprovanteMatricula);
}
?>

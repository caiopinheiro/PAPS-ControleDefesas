<?php


//////////////////////////////////////////////////////////////

function avaliarCandidatosAcre($periodo)
    {

    $Itemid = JRequest::getInt('Itemid', 0);
    $database	=& JFactory::getDBO();
    
    $database->setQuery("SELECT DISTINCT periodo from #__candidatosnivelamento ORDER BY periodo DESC");
    $periodosLista = $database->loadObjectList();

    if($periodo == "")
       $periodo = $periodosLista[0]->periodo;
       
    $sql = "SELECT * FROM #__candidatosnivelamento WHERE fim is not NULL AND periodo = '$periodo' ORDER BY id";
    $database->setQuery( $sql );
    $candidatos = $database->loadObjectList();

	$table_bgcolor_even="#e6e6e6";
	$table_bgcolor_odd="#FFFFFF";

	$cursoDesejado = array (1 => "mestrado",2 => "doutorado");
	$resultado = array (NULL=> "components/com_portalsecretaria/images/desistente.png", 0 => "components/com_portalsecretaria/images/desligado.gif",1 => "components/com_portalsecretaria/images/ativo.png");
	$resultadoTitle = array (NULL=> "Nao avaliado", 0 => "Reprovado",1 => "Aprovado");
	$regimeDedicacao = array (1 => "Integral",2 => "Parcial");

    $database->setQuery("SELECT count(*) as tot from #__candidatosnivelamento WHERE fim is NULL AND periodo = '$periodo'");
	$incompletos = $database->loadObjectList();


	?>

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
          <div class="pagetitle icon-48-contact"><h2>Candidatos do PPGI no Acre</h2></div>
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
    <table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters"  class="tabela">
	<thead>
      <tr bgcolor="#002666">
        <th width="7%" align="center"><font color="#FFC000">Ver</font></th>
    	<th width="7%" align="center"><font color="#FFC000">ID</font></th>
        <th width="37%" align="center"><font color="#FFC000">Nome</font></th>
        <th width="9%" align="center"><font color="#FFC000">E-mail</font></th>
        <th width="9%" align="center"><font color="#FFC000">Curso</font></th>
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
            <a href="index.php?option=com_inscricaonivelamento&Itemid=176&task=imprimir&idCandidato=<?php echo $candidato->id;?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;"><img border='0' src='images/b_view.png' width='16' height='16' title='Visualizar Documentos de Inscri&#231;&#227;o'></a>
			</a>
		</div></td>
		
		<td><?php echo $candidato->id;?></td>
		<td align="left"> <?php echo $candidato->nome;?></td>
		<td><img border='0' src='components/com_portalsecretaria/images/emailButton.png' width='16' height='16' title='<?php echo $candidato->email;?>'></td>
		<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $cursoDesejado[$candidato->cursodesejado];?>.gif' title='<?php echo $cursoDesejado[$candidato->cursodesejado];?>'> </td>
		
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

?>

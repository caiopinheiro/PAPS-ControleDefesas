<?php // LISTAGEM DE PEDIDOS : TELA
function listarSolicitacoes($mes) { 
	$Itemid = JRequest::getInt('Itemid', 0); 
    $database = JFactory::getDBO();
	
	$sqlEstendido = "";	

	if ($mes) {
		$data = explode("/", $mes);
		$mesBusca = $data[0];
		$anoBusca = $data[1];
		$sqlEstendido = "AND MONTH(dataInicio) = '$mesBusca' AND YEAR(dataInicio) = '$anoBusca'";
	}
	
	$sql = "SELECT * FROM #__solic_entrada WHERE 1 ".$sqlEstendido." ORDER BY dataInicio ";
    $database->setQuery($sql);
    $solicitacoes = $database->loadObjectList(); ?>
    
   	<script type="text/javascript" src="components/com_portalaluno/jquery-1.7.2.min.js"></script>
    
    <!-- TABLESORTER -->
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
    <script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();
		});
	</script>
    
    <!-- CALENDÁRIO -->
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/default.css"/>
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/jquery.click-calendario-1.0.css"/>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/jquery.click-calendario-1.0-min.js"></script>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/script.js"></script>
    
    <script type="text/javascript">
		function abrirSolicitacao(idSolic,idAluno) {
			window.open("index.php?option=com_portalaluno&Itemid=191&task=imprimirSolicitacao&item="+idSolic+"&al="+idAluno,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
		}
	</script>
    
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css" /> <!-- BARRA DE OPÇÕES -->
    <link rel="stylesheet" type="text/css" href="components/com_reserva/assets/css/estilo.css" /> <!-- STRAP -->
    <link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/estilo.css" /> <!-- TABELA -->
    
    <style>
		.pdf{
			width:16px;
			height:16px;
			margin:auto;
			background-image:url(components/com_portalsecretaria/images/icon_pdf.png);
			background-repeat:no-repeat;
		}
		
		.pdf:hover{
			background-image:url(components/com_portalsecretaria/images/icon_pdf_hover.png);
			background-repeat:no-repeat;
		}
    </style>

	<form method="post" name="form" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=solicitacao" class="form-horizontal">

        <!-- BARRA DE FERRAMENTAS -->
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">                
                <div class="icon" id="toolbar-back">
	                <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
    
            <div class="clr"></div>
            </div>
          <div class="pagetitle icon-48-copy"><h2>Solicitações de Entrada</h2></div>
            </div>
        </div>
        
        <!-- FILTRO DA BUSCA -->
        <fieldset>
            <legend>Filtros para consulta</legend>
                        
            <select name="mes">
                <option value="">Todos os meses</option>
                <?php						
                    $sql = "SELECT DISTINCT DATE_FORMAT(dataInicio, '%m/%Y') as mesInicio FROM #__solic_entrada ORDER BY dataInicio";
                    $database->setQuery($sql);
                    $meses = $database->loadObjectList();
                    
                    foreach($meses as $mesCombo) { ?>   
                        <option value="<?php echo $mesCombo->mesInicio; ?>" 
                            <?php
                            
                               if($mes == $mesCombo->mesInicio)
                                  echo 'SELECTED';
                            ?>
                            > <?php 
                                   echo $mesCombo->mesInicio;
                            ?>
                </option>
                <?php } ?>
            </select>
            
            <button type="submit" name="buscar" class="btn btn-primary" style="float:right;" title="Filtra busca">
            	<i class="icone-search icone-white"></i> Consultar
            </button>
        </fieldset>
        
        <table class="table table-striped" id="tablesorter-imasters">
            <thead>
                <tr>
                	<th>Nº</th>
                    <th>Tipo</th>
                    <th>Aluno</th>                    
                    <th>Período</th>
                    <th>Motivo</th>
                    <th>PDF</th>                   
                </tr>
            </thead>
            
            <tbody>  
            <?php 
				foreach ($solicitacoes as $solic) { 
					$sql = "SELECT nome FROM #__aluno WHERE id = '$solic->idAluno' ";
					$database->setQuery($sql);
					$aluno = $database->loadObjectList(); ?>
				    
                <tr>
                	<td><?php echo $solic->id; ?></td>
                    <td><?php echo $solic->tipo; ?></td>
                    <td><?php echo $aluno[0]->nome; ?></td>
                    <td>
                    	<?php echo dataBr($solic->dataInicio);
							  if ($solic->dataTermino != $solic->dataInicio)
							  	echo ' a '.dataBr($solic->dataTermino); ?>
                    </td>
                    <td><div align="center"><img src="components/com_portalsecretaria/images/justificativa.gif" alt="justificativa" title="<?php echo $solic->motivo; ?>"></div></td>
                    <td><a href="javascript:abrirSolicitacao(<?php echo $solic->id; ?>,<?php echo $solic->idAluno; ?>)" title="Visualizar"><div class="pdf"></div></a></td>
                </tr>
 
			<?php } ?>
                
            </tbody>
        </table>
        
        <br />
        <span class="label label-inverse">Total de Solicitações: <?php echo sizeof($solicitacoes);?></span>
        
    </form>	
	
<?php } ?>
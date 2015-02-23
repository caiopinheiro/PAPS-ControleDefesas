<?php
/***********************************************************************
*               UFAM - Universidade Ferderal do Amazonas
* 
*      Funcoes de controle do modulo despesa,desenvolvido em 10/02/2013
* adaptado do modulo professor, para update do site icomp sob orientacao
* do Prof Arilo Dias. 
* 
* Sitemas de informacao, Equipe Ferpa Augusto, Denise e Diana
* usando padrao de desenvolvimento ágil
* 
************************************************************************/

$document = JFactory::getDocument();
$document->addScript("includes/js/joomla.javascript.js");

// ----------------------------------------------------- //
   

function listarServicosControleProjetos(){
    $Itemid = JRequest::getInt('Itemid', 0); ?>
	
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css" />
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/estilo.css" />
    <script type="text/javascript" src="components/com_controleprojetos/scripts.js"></script>
    
    <!-- LISTAGEM DOS EVENTOS -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready( function() {
			var nav = '<span style="display:block; text-align:right; padding:0 20px; margin-bottom:-10px; font-size:small;" ></span>';
			$( nav ).insertBefore( '#jfaq' );
			$( '#jfaq' ).addClass( 'simple_jfaq' );
			$( '#jfaq dd' ).css( 'display', 'none' );
			$( '#jfaq dt' ).css( 'cursor', 'pointer' )
				.click( function() { $(this).next().slideToggle( 'fast' ); })
				.hover( function () { $(this).addClass("hover"); }, function () { $(this).removeClass( 'hover' ); } );
			$( 'a[rel=jfaq_expand]' ).click( function() { $( '#jfaq dd:hidden' ).slideToggle( 'fast' ); });
			$( 'a[rel=jfaq_collapse]' ).click( function() { $( '#jfaq dd:visible' ).slideToggle( 'fast' ); });
		});
	</script>

    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        <div class="cpanel2">
        </div>
		<div class="clr"></div>
		</div>
        <div class="pagetitle icon-48-contact"><h2>Gerenciamento de Projetos P&D</h2></div>
    </div></div>
	
    <hr />
    
	<dl id="jfaq">
		<dt>Próximos Eventos</dt>
		<dd><?php imprimirNotificacoes(); ?></dd>
	</dl>
    
    <hr />
    
	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
            	<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarProjetopds">
           		<img width="32" height="32" border="0" src="components/com_controleprojetos/images/projeto.png"><span><?php echo JText::_( 'Projetos de Pesquisa e Desenvolvimento' ); ?></span></a>
			</div>
		</div>

		<div class="icon-wrapper">
    		<div class="icon">
        		<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarRubricas">
           		<img width="32" height="32" border="0" src="components/com_controleprojetos/images/rubrica.png"><span><?php echo JText::_( 'Gerenciar Rubricas'); ?></span></a>
			</div>
		</div>
        
        <div class="icon-wrapper">
			<div class="icon">
            	<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarFomentos">
           		<img width="32" height="32" border="0" src="components/com_controleprojetos/images/fomento.png"><span><?php echo JText::_( 'Gerenciar Agências de Fomento' ); ?></span></a>
			</div>
		</div>
        
        <div class="icon-wrapper">
			<div class="icon">
            	<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=consultaSaldoRubricas">
           		<img width="32" height="32" border="0" src="components/com_controleprojetos/images/visualizar.png"><span><?php echo JText::_( 'Consultar Saldo por Rubrica' ); ?></span></a>
			</div>
		</div>      
                    
	</div>
    
<?php } ?>


<?php 
function imprimirNotificacoes() { 
    $database = JFactory::getDBO();

	$hoje = date("d");

	$sql = "SELECT eventos.data, eventos.evento, eventos.observacao, projetos.nomeprojeto
			FROM #__contproj_registradatas as eventos, #__contproj_projetos as projetos
			WHERE eventos.projeto_id = projetos.id
			AND DAY(eventos.data) >= '$hoje'";
    $database->setQuery($sql);
    $resultado = $database->loadObjectList();
	
	$total = sizeof($resultado);
	
	if (empty($total)) {
		echo 'Nenhum evento cadastrado';
	} else { ?>
    
		<table>
            <tr>
                <td>Nome do Projeto</td>
                <td>Data do Evento</td>
                <td>Nome</td>
                <td>Observação</td>
            </tr>
            <?php foreach ($resultado as $registro) { 
				$data = $registro->data;
				$dataAtual = date("Y-m-d");
				
				$data = explode("-",$data);
				$data = $data[2];

				$dataAtual = explode("-",$dataAtual);
				$dataAtual = $dataAtual[2];
				
				if ($data >= $dataAtual) { ?>
					<tr>
						<td><?php echo $registro->nomeprojeto; ?></td>
						<td><?php echo dataBr($registro->data); ?></td>
						<td><?php echo $registro->evento; ?></td>
						<td><?php echo $registro->observacao; ?></td>
					</tr>		
					<?php } 
				} ?>
        </table>
		
	<?php }
    
} ?>
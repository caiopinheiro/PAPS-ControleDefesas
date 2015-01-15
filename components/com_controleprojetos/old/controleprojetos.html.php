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
   

function listarServicosControleProjetos() {
    $Itemid = JRequest::getInt('Itemid', 0);
    ?>
    
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css" />
    <link rel="stylesheet" href="components/com_controleprojetos/estilo.css" type="text/css" />
    
    <p><h3>Gerenciamento de Projetos P&D</h3></p>
    <br />
    
    <div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarProjetopds">
      			<img width="32" height="32" border="0" src="components/com_controleprojetos/images/projeto.png" /><span><?php echo JText::_( 'Gerenciar Projetos' ); ?></span></a>
			</div>
		</div>
		
        <div class="icon-wrapper">
			<div class="icon">
                <a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarRubricas">
                <img width="32" height="32" border="0" src="components/com_controleprojetos/images/rubrica.png" /><span><?php echo JText::_( 'Gerenciar Rubricas'); ?></span></a>
            </div>
        </div>
                    
        <div class="icon-wrapper">
			<div class="icon">
        	    <a href="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>&task=gerenciarFomentos">
           		<img width="32" height="32" border="0" src="components/com_controleprojetos/images/fomento.png" /><span><?php echo JText::_( 'Gerenciar Agencias de Fomento' ); ?></span></a>
            </div>
        </div>
	</div>
    
<?php } ?>
<?php
// No direct access to this file
$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
$document->addStyleSheet('components/com_defesasorientador/template.css','text/css');
$document->addStyleSheet('components/com_defesasorientador/assets/css/estilo.css','text/css');
//$document->addScript("includes/js/joomla.javascript.js");

?>
<script>



<?php

	
		JFactory :: getApplication()->enqueueMessage(JText :: _(mb_convert_encoding('Solicitação de banca realizada com sucesso. Não esqueça de reservar a sala, caso local seja interno.', 'UTF-8', 'ISO-8859-1')));
		?>

	<?php 
	
	?>


</script>

</form>


<div id="toolbar-box"><div class="m
"><div class="toolbar-list" id="toolbar">
<div class="cpanel2">


<div class="icon" id="toolbar-cancel">
<a href="index.php?option=com_portalprofessor&task=alunos&Itemid=317&lang=pt-br" class="toolbar">

<span class="icon-32-back"></span>Voltar</a>
</div>
</div>
<div class="clr"></div>
</div>


<div class="pagetitle icon-48-groups"><h2>Solicitar Banca</h2></div>
</div>
</div>
	

		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_reserva">
				<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/prorrogacao.png">
				<span><?php echo JText::_( 'Reservar sala' ); ?></span></a>
			</div>
		</div>
	
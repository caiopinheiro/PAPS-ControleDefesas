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
//echo 'aeaeaeaeaeae';
//var_dump($this->tipoLocal);
if ($this->tipoLocal == 'I') {

		
	?>
alert(mb_convert_encoding('Defesa cadastrada com sucesso. Agora você será redirecionado para a reserva da sala.', 'UTF-8', 'ISO-8859-1'));

<?php echo "location.href = 'index.php?option=com_reserva&dia=$this->dia" . "&mes=$this->mes" . "&ano=$this->ano" . "&data=$this->data" . "&task=addReserva';"?>

<?php } else {
	
		JFactory :: getApplication()->enqueueMessage(JText :: _(mb_convert_encoding('Solicitação de banca realizada com sucesso.', 'UTF-8', 'ISO-8859-1')));
		?>

	<?php 
		}		
	
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
</div></div>

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
var_dump($this->tipoLocal );
if ($this->tipoLocal == 'I') {

		
	?>
alert('Defesa cadastrada com sucesso. Agora você será redirecionado para a reserva da sala.');

<?php echo "location.href = 'index.php?option=com_reserva&dia=$this->dia" . "&mes=$this->mes" . "&ano=$this->ano" . "&data=$this->data" . "&task=addReserva"?>

<?php } else {
	
		JFactory :: getApplication()->enqueueMessage(JText :: _('Solicitação de banca realizada com sucesso.'));
		?>

	<?php 
		}		
	
	?>


</script>

</form>



<div id="toolbar-box"><div class="m
"><div class="toolbar-list" id="toolbar">
<div class="cpanel2">


</div>
<div class="clr"></div>
</div>

<div class="icon" id="toolbar-back">
<a href="index.php?option=com_portalprofessor&task=alunos&Itemid=317&lang=pt-br">
<span class="icon-32-save-new"></span>Voltar</a>
</div>

<div class="pagetitle icon-48-groups"><h2>Solicitar Banca - <?php echo mb_convert_encoding($this->nomeFase[$this->faseDefesa[0]], 'UTF-8', 'ISO-8859-1');	?></h2></div>
</div></div>


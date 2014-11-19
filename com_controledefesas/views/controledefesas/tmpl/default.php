<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

include_once("components/com_portalsecretaria/portalsecretaria.html.php");
?>
<h1><?php echo $this->msg; ?></h1>
<h2><?php echo $this->msg2;?></h2>

<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=alunos">
		<img width="50" height="50" border="0" src="components/com_portalsecretaria/images/cadastraraluno.png"><span><?php echo JText::_( 'Gerenciar Alunos do PPGI' ); ?></span></a>
	</div>
</div>

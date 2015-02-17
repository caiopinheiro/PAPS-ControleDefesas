<?php
$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
$document->addScript("includes/js/joomla.javascript.js");

//include_once("components/com_portalsecretaria/portalsecretaria.html.php");
?>
<h1><?php echo $this->msg; ?></h1>

<hr />
<br />

<div class="cpanel">
	
		<link rel="stylesheet" type="text/css" href="components/com_controledefesas/assets/css/template.css">
		
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_controledefesas&view=listabancas">
				<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/gerencia.png">
				<span><?php echo JText::_( 'Gerenciar Defesas' ); ?></span></a>
			</div>
		</div>
		
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_portalsecretaria&Itemid=190&task=membrosbanca">
				<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/funcionario.png">
				<span><?php echo JText::_( 'Membros de Banca' ); ?></span></a>
			</div>
		</div>
		
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_controledefesas&view=relatoriodefesas">
				<img width="32" height="32" border="0" src="components/com_controledefesas/assets/images/relatorio.png">
				<span><?php echo JText::_( 'Relatório de Defesas' ); ?></span></a>
			</div>
		</div>
		
</div>

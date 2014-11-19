<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//$document = &JFactory::getDocument();
//$document->addScript("includes/js/joomla.javascript.js");

//include_once("components/com_portalsecretaria/portalsecretaria.html.php");
?>
<h1><?php echo $this->msg; ?></h1>

<hr />
<br />

<div class="cpanel">
	
		<link rel="stylesheet" type="text/css" href="components/com_controledefesas/assets/css/template.css">
		
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_controledefesas&view=controledefesas&task=solicitarBanca">
				<img width="32" height="32" border="0" src="components/com_controledefesas/assets/images/professor.png"><span><?php echo JText::_( 'Solicitar Banca' ); ?></span></a>
			</div>
		</div>
		
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=funcionarios">
				<img width="32" height="32" border="0" src="components/com_controledefesas/assets/images/aprovarcandidatos.png"><span><?php echo JText::_( 'Aprovar Banca' ); ?></span></a>
			</div>
		</div>
		
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=funcionarios">
				<img width="32" height="32" border="0" src="components/com_controledefesas/assets/images/aprovarcandidatos.png"><span><?php echo JText::_( 'Confirmar Banca' ); ?></span></a>
			</div>
		</div>
		
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=funcionarios">
				<img width="32" height="32" border="0" src="components/com_controledefesas/assets/images/historico.gif"><span><?php echo JText::_( 'Lançar Conceito' ); ?></span></a>
			</div>
		</div>
		
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=funcionarios">
				<img width="32" height="32" border="0" src="components/com_controledefesas/assets/images/consultar.gif"><span><?php echo JText::_( 'Consultar Defesas' ); ?></span></a>
			</div>
		</div>
	
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=funcionarios">
				<img width="32" height="32" border="0" src="components/com_controledefesas/assets/images/alunos.png"><span><?php echo JText::_( 'Gerenciar Membros da Banca' ); ?></span></a>
			</div>
		</div>
		
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=funcionarios">
				<img width="32" height="32" border="0" src="components/com_controledefesas/assets/images/enviar.gif"><span><?php echo JText::_( 'Enviar Emails de aviso' ); ?></span></a>
			</div>
		</div>
		
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=avaliarcandidatos">
				<img width="32" height="32" border="0" src="components/com_controledefesas/assets/images/relatorio.png"><span><?php echo JText::_( 'Gerar Relatórios' ); ?></span></a>
			</div>
		</div>
</div>



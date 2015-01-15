<link rel="stylesheet" type="text/css" href="components/com_reserva/assets/css/estilo.css" />
<script type="text/javascript" src="components/com_reserva/assets/js/jquery.js"></script>

<?php
$document = &JFactory::getDocument();

function listarServicos() {    
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
   	<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
	
    <div class="cpanel">
    
    		<h3>Serviços oferecidos para Reserva de Sala</h3><br />
            
            <!-- DIV NOVA SALA 
			<div class="icon-wrapper">
				<div class="icon">
                	<a href="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>">
           			<img width="32" height="32" border="0" src= "components/com_portalaluno/images/trancamento.png"><span><?php echo JText::_( 'Nova Sala' ); ?></span></a>
				</div>
			</div> -->
            
            <!-- DIV RESERVA DE SALAS -->
			<div class="icon-wrapper">
				<div class="icon">
                	<a href="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>&task=addReserva">
           			<img width="32" height="32" border="0" src= "components/com_portalaluno/images/trancamento.png"><span><?php echo JText::_( 'Reserva de Salas' ); ?></span></a>
				</div>
			</div>          
		
        </div>

<?php }
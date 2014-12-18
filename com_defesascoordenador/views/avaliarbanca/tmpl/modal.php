<?php

JHTML::_('behavior.mootools');
JHTML::_('script','modal.js', 'media/system/js', true);
JHTML::_('stylesheet','modal.css');
JHTML::_('behavior.modal', 'a.modal');

// No direct access to this file


$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
//$document->addScript("includes/js/joomla.javascript.js");

$idBanca = $this->idBanca;

$Banca = $this->banca;
$Aluno = $this->aluno;
$Defesa = $this->defesa;
$MembrosBanca = $this->membrosBanca;

$linha_pes = array(0 => "Todos", 1 => "Banco de Dados e Recuperação da Informação", 2 => "Sistemas Embarcados & Engenharia de Software", 3 => "Inteligência Artificial", 4 => "Visão Computacional e Robótica", 5 => "Redes e Telecomunicações", 6 => "Otimização Algorítmica e Complexidade");
			
$arrayTipoDefesa = array('T' => "Mestrado", 'D' => "Doutorado");

$status_banc = array (0 => "Banca Indeferida", 1 => "Banca Deferida", NULL => "Banca Não Avaliada");

$array_funcao = array ('P' => "Presidente",'E' => "Membro Externo", 'I' => "Membro Interno");

$nome_orientador = "";
foreach( $MembrosBanca as $membro ){
		if($membro->funcao == 'P')
			$nome_orientador = $membro->nome;
}

$sucesso = $this->status;	

if($sucesso == true){
	JFactory :: getApplication()->enqueueMessage(JText :: _('Opera&#231;&#227;o realizada com sucesso.'));
}
else if($sucesso == false AND $sucesso !=NULL ){
	JError :: raiseWarning(100, 'ERRO: Opera&#231;&#227;o Falhou.');
}


?>

<!--script type="text/javascript" src="/icomp/components/com_defesascoordenador/assets/js/bootstrap.min.js"></script-->

<script type="text/javascript" src="/icomp/components/com_defesascoordenador/assets/jquery-ui-1.11.2.custom/jquery-ui.js"></script>



<script language="JavaScript">
       
        function deferirBanca(form){        
           var confirmar;
           var deferir = 1;
           
           confirmar = window.confirm('Você tem certeza que deseja DEFERIR essa banca?');
       
           if(confirmar == true){
			    console.log(form.avaliacao);
				form.task.value = 'deferirBanca';				
				form.avaliacao.value = deferir;
				form.submit();
           }
        }
        			
</script>

<!--script>
	jQuery(function() {
    var dialog, form;
	
	
    function indeferirBanca(form){        
           var confirmar;
           var indeferir = 0;
            
           confirmar = window.confirm('Você tem certeza que deseja INDEFERIR essa banca?');
			
           if(confirmar == true){		
				form.task.value = 'indeferirBanca';
				form.avaliacao.value = indeferir;
		//		form.justificativa.value = "";
				form.submit();
           }
           
        }
	 
    dialog = jQuery( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 300,
      width: 350,
      modal: true,
      buttons: {
        "Salvar": indeferirBanca,
        Cancel: function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });
 
    form = dialog.find( "form" ).on( "submit", function( event ) {
      event.preventDefault();
      
    });
 
    
  });

</script-->

<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css"> 	
<link rel="stylesheet" type="text/css" href="components/com_defesascoordenador/assets/jquery-ui-1.11.2.custom/jquery-ui.css"> 	

<div id="toolbar-box">
	<div class="m">
		<div class="toolbar-list" id="toolbar">
		  <div class="cpanel2">
				<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_defesascoordenador&view=avaliarBanca" >
					<div <?php if($Banca[0]->status_banca != NULL) { ?> style="display: none;" <?php } ?> class="icon" id="deferir">
						<a href ="javascript:deferirBanca(document.form)" class = 'toolbar'>
						<span class="icon-32-apply"></span>Deferir</a>
					</div>
				
					<div <?php if($Banca[0]->status_banca != NULL) { ?> style="display: none;"<?php } ?>  class="icon" id="indeferir">
						<a class ='toolbar'>
						<span class="icon-32-delete"></span>Indeferir</a>
				   </div>
				   
					
				   <div class="icon" id="toolbar-back">
						<a href="index.php?option=com_defesascoordenador&view=listabancas">
						<span class="icon-32-back"></span>Voltar</a>
				   </div>
				   
				   <input name='task' type='hidden' value='display'>
				   <input name='idBanca' type='hidden' value = <?php echo $idBanca;?>>
				   <input name='avaliacao' type='hidden' value = ''>
				   <input name='justificativa' type='hidden' value = ''>
				</form>   
		</div>
		<div class="clr"></div>
		</div>

	<div class="pagetitle icon-48-user"><h2><?php echo $this->msg; ?></h2></div>
	</div>
</div>

<h2>Dados do Aluno</h2>
<hr />

	<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
	  <tbody>
		
		<tr>
		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='20%'>ALUNO:</td>
		  <td colspan='3'><?php echo $Aluno[0]->nome_aluno;?></td>
		</tr>
				
		<tr>
		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='20%'>LINHA DE PESQUISA:</td>
		  <td colspan='3'><?php echo $linha_pes[$Aluno[0]->area];?></td>
		</tr>
		
		<tr>
		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='20%'>INGRESSO:</td>
		  <td colspan='3'><?php echo $Aluno[0]->anoingresso;?></td>
		</tr>
		
		<tr>
		  <td bgcolor="#B0B0B0" style='font-weight: bold;'>ORIENTADOR:</td>
		  <td colspan='3' ><?php echo $nome_orientador; ?></td>
		</tr>
		</tbody>
	</table>

<h2>Dados da Defesa</h2>
<hr />
	<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
	  <tbody>

		<tr>
		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='20%'>TITULO:</td>
		  <td colspan='3'><?php echo $Defesa[0]->titulo;?></td>
		</tr>
		
		<tr>
		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='20%'>RESUMO:</td>
		  <td colspan='3'><?php echo $Defesa[0]->resumo;?></td>
		</tr>
		
		<tr>
		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='20%'>TIPO BANCA:</td>
		  <td width='30%'><?php echo $arrayTipoDefesa[$Defesa[0]->tipoDefesa];?></td>
		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='25%'>STATUS BANCA:</td>
		  <td width='25%'><?php echo $status_banc[$Banca[0]->status_banca];?></td>
		</tr>
		
		<tr <?php if(($Banca[0]->status_banca == 0) AND ($Banca[0]->status_banca != NULL) ) {?>>
		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='20%'>JUSTIFICATIVA:</td>
		  <td colspan='3'><?php echo $Banca[0]->justificativa;?></td>
		</tr>
		<?php }?>
	  </tbody>
	</table>
	
<h2>Dados da Comissão Examinadora</h2>
<hr />
	
	<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
      <tbody>
        <tr bgcolor='#B0B0B0'>
          <td style='text-align: center; font-weight: bold;' width='70%'>MEMBROS DA BANCA</td>
          <td style='text-align: center; font-weight: bold;' width='20%'>FILIAÇÃO</td>
          <td style='text-align: center; font-weight: bold;' width='10%'>FUNÇÃO</td>
        </tr>
    
        <?php
        if(isset ($MembrosBanca)){
			foreach( $MembrosBanca as $membro )
			{
				if($membro->funcao == 'P')
					$nome_orientador = $membro->nome;
			?>
			<tr>
			  <td align='center'><?php echo $membro->nome;?></td>
			  <td align='center'><?php echo $membro->filiacao;?></td>
			  <td align='center'><?php echo $array_funcao[$membro->funcao];?></td>   
			</tr>
				
				
			<?php
			}
		} ?>
    
      </tbody>
    </table>


<div id="box-toggle" class="box">
<div class="tgl"></div></div>

<div id="dialog-form" title="Create new user" style='display:none'>
 
  <form>
    <fieldset>
      <label for="name">Name</label>
      <input type="text" name="name" id="name" value="Jane Smith" class="text ui-widget-content ui-corner-all">
      <label for="email">Email</label>
      <input type="text" name="email" id="email" value="jane@smith.com" class="text ui-widget-content ui-corner-all">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" value="xxxxxxx" class="text ui-widget-content ui-corner-all">
 
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
</div>

<div id="modaltoolbar-box" style= 'display:none'>
	<div class="m">
		<div class="toolbar-list" id="toolbar">
		  <div class="cpanel2">
				<div class="icon" id="toolbar-back">
					<a href ="javascript:indeferirBanca(document.form)" class = 'toolbar'>
					<span class="icon-32-save"></span>Salvar</a>
				</div>
		</div>
		<div class="clr"></div>
		</div>

	<div class="pagetitle icon-48-user"><h2><?php echo Justificativa; ?></h2></div>
	</div>
	
	 <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_defesascoordenador&view=avaliarBanca" >
	
	<table width="100%">
		<tr>
			<td>Digite a Justificativa de Deferimento da Banca:</td>
		</tr>
		<tr>
			<td ><input size= 100  id="justificativa" name="justificativa" type="text" value="<?php echo $justificativa;?>"/></td>
		</tr>
	</table>
</form>
	
</div>

<!--script>
jQuery("#indeferir").on("click", function() {
		jQuery( "#dialog-form" ).dialog();
    });
</script-->

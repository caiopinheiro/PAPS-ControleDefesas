

<?php

JHTML::_('behavior.mootools');
JHTML::_('script','modal.js', 'media/system/js', true);
JHTML::_('stylesheet','modal.css');
JHTML::_('behavior.modal', 'a.modal');

$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();

$Banca = $this->banca;
$Aluno = $this->aluno;
$Defesa = $this->defesa;
$MembrosBanca = $this->membrosBanca;

$idDefesa= JRequest::getVar('idDefesa'); 
$idAluno = JRequest::getVar('idAluno'); 

$botao = 0;
$linha_pes = array(0 => "Todos", 1 => "Banco de Dados e Recuperação da Informação", 2 => "Sistemas Embarcados & Engenharia de Software", 3 => "Inteligência Artificial", 4 => "Visão Computacional e Robótica", 5 => "Redes e Telecomunicações", 6 => "Otimização Algorítmica e Complexidade");
$arrayTipoDefesa = array('1' => "Mestrado", '2' => "Doutorado");
$array_funcao = array ('P' => "Presidente",'E' => "Membro Externo", 'I' => "Membro Interno");
$numDefesa=null;
$nome_orientador = "";

foreach( $MembrosBanca as $membro ){
		if($membro->funcao == 'P')
			$nome_orientador = $membro->nome;
		$emails[] = $membro->email;	
}

$sucesso = $this->status;	
$sucesso2 = $this->status;
if($sucesso == true OR $sucesso2 == true ){
	JFactory :: getApplication()->enqueueMessage(JText :: _('Opera&#231;&#227;o realizada com sucesso.'));
}
else if(($sucesso == false AND $sucesso !=NULL) OR ($sucesso2 == false AND $sucesso2 !=NULL) ){
	JError :: raiseWarning(100, 'ERRO: Opera&#231;&#227;o Falhou.');
}


$tipoDefesa = array('Q1' => "Exame de Qualificação I", 'Q2' => "Exame de Qualificação II", 'T' => "Tese", 'D' => "Dissertação");


	if ((date('Y/m/d'))  < ($Defesa[0]->data)){
		$botao = 1;
	}

	else if ($Defesa[0]->conceito != ''){ 
		$botao = 2;
	}

	else if (($Defesa[0]->banca_id == NULL || $Defesa[0]->banca_id == 0) && (($Aluno[0]->curso == 1 && $Defesa[0]->tipoDefesa == 'Q1') || ($Aluno[0]->curso == 2 && $Defesa[0]->tipoDefesa == 'Q2' )  || ($Aluno[0]->curso == 1 && $Defesa[0]->tipoDefesa == 'D')  || ($Aluno[0]->curso == 2 && $Defesa[0]->tipoDefesa == 'T' ))){ 
		$botao = 3;

	}
	else if(($Defesa[0]->tipoDefesa == 'T' || $Defesa[0]->tipoDefesa == 'D')  && ($Defesa[0]->status_banca == NULL && ($Defesa[0]->banca_id != 0 || $Defesa[0]->banca_id != NULL ))){
		$botao = 4;
	}
	else if($Defesa[0]->status_banca == 0){
		$botao = 5;
	}
?>


<script type="text/javascript" src="/icomp/components/com_defesascoordenador/assets/jquery-ui-1.11.2.custom/jquery-ui.js"></script>

<script language="JavaScript">
       

	function observacao(botao){

		if (botao == 1){
			alert ("Observação:\n\n  -Não é possivel lançar o conceito, pois a defesa ainda não foi realizada.");
		}
		else if (botao == 2){
			alert ("Observação:\n\n  -Conceito já foi devidamente Lançado.");
		}
		else if (botao == 3){
			alert ("Observação:\n\n  -Ainda não é possivel lançar o conceito, pois não consta no Banco de Dados a existência de uma Banca Avaliadora.");
		}
		else if (botao == 4){
			alert ("Observação:\n\n  -Ainda não é possivel lançar o conceito, pois a Banca Avaliadora ainda não foi aprovada pelo Coordenador.");
		}
		else if (botao == 5) {
			alert ("Observação:\n\n  -Não é possivel lançar o conceito, pois a Banca Avaliadora foi INDEFERIDA pelo Coordenador.");	
		}
		else if (botao == 6){
			alert ("Observação:\n\n  -Só é possivel imprimir a Carta de Agradecimento e Declaração de Participação após o DEFERIMENTO da Banca Avaliadora pelo Coordenador.");		
		}
	}

		function folhaaprovacao(form){
                window.open(URL='index.php?option=com_controledefesas&task=folhaaprovacao&idDefesa='+<?php echo $idDefesa ?>+'&idAluno='+<?php echo $idAluno?>+'&lang=pt-br');
        }


        function aprovar(form){        
           var confirmar;
           var deferir = 1;
           var botao = <?php echo $botao?>;

           	if (botao != 0){
           		observacao(botao);
           	}
           else{
	           confirmar = window.confirm('Você tem certeza que deseja APROVAR esse(a) aluno(a)?');
	       
	           if(confirmar == true){
					form.task.value = 'aprovar';
					form.submit(); // 'continuacao no arquivo de controller.php da raiz'

	           }
	       }
        }
						
		function reprovar(form){        
           var confirmar;
            var botao = <?php echo $botao?>;
            
            if (botao != 0){
           		observacao(botao);
           	}
           else{
	           confirmar = window.confirm('Você tem certeza que deseja REPROVAR esse(a) aluno(a)?');
				
	           if(confirmar == true){		
					form.task.value = 'reprovar';
					form.submit();
	           }
       		}
           
        }

		function gerarAtaDefesa(form,idDefesa, num_defesa){        
           if(num_defesa != null){
				form.task.value = 'gerarAta'; 
				form.idDefesa.value = idDefesa;
				window.open(URL='index.php?option=com_controledefesas&task=gerarAta&idDefesa='+<?php echo $idDefesa ?>+'&lang=pt-br');
           } 
           else {
			  alert('Defesa sem número! Por favor, coloque o número de defesa antes de gerar a ata.');
		   }
	    }
	    
	    function gerarConviteDefesa(form, idDefesa){        			
	    	form.task.value = 'gerarConviteDefesa'; 
			window.open(URL='index.php?option=com_controledefesas&task=gerarConviteDefesa&idDefesa='+<?php echo $idDefesa ?>+'&lang=pt-br');
	    }
	    
	    function setarNumDefesa(form){        
			jQuery( "#dialog-form" ).dialog();
        }

</script>

<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css"> 	
<link rel="stylesheet" type="text/css" href="components/com_defesascoordenador/assets/jquery-ui-1.11.2.custom/jquery-ui.css"> 	



<div id="toolbar-box">
	<div class="m">
		<div class="toolbar-list" id="toolbar">
		  <div class="cpanel2">
				<form method="post" id="formAvaliacao" name="form" enctype="multipart/form-data" action="index.php?option=com_controledefesas&view=conceitos">
					<!--<div <?php if(($Defesa[0]->conceito != '') || ($Defesa[0]->data > (date('Y/m/d'))) || ($Defesa[0]->banca_id == 0)  ||   ($Defesa[0]->tipoDefesa == 'T' OR $Defesa[0]->tipoDefesa == 'D')  && ($Defesa[0]->status_banca == NULL)  ) {  ?> style="display: none;" <?php } ?> class="icon" id="toolbar-back"> -->
					<div class="icon" id="toolbar-back">
						<a href ="javascript:aprovar(document.form)" class = 'toolbar'>
						<span class="icon-32-apply"></span>Conceito:<br>Aprovar</a>
					</div>
					<!-- <div <?php if(($Defesa[0]->conceito != '') || ($Defesa[0]->data > (date('Y/m/d')))  || ($Defesa[0]->banca_id == 0)  ||  ($Defesa[0]->tipoDefesa == 'T' OR $Defesa[0]->tipoDefesa == 'D')  && ($Defesa[0]->status_banca == NULL)    ) { ?> style="display: none;"<?php } ?>  class="icon" id="indeferir"> -->
					<div class="icon" id="indeferir">
						<a href ="javascript:reprovar(document.form)" class = 'toolbar'>
						<span class="icon-32-delete"></span>Conceito:<br>Reprovar</a>
				   </div>
					<div class="icon" id="toolbar-apply">
	                    <a href="javascript:folhaaprovacao(document.form)" class="toolbar" title = "Funcionalidade que permite: 
	                    -Imprimir folha de aprovação">
	                    <span class="icon-32-apply"></span>Folha<br>Aprovação<br></a>
	                </div>
				   <div class="icon" id="setarNumDefesa">
						<a href="javascript:setarNumDefesa(document.form)">
						<span class="icon-32-edit"></span>Por Num</br>de Defesa</a>
				   </div>

				   <div class="icon" id="gerarAta">
						<a href="javascript:gerarAtaDefesa(document.form, <?php echo $idDefesa;?> , <?php echo $Defesa[0]->numDefesa;?> )">
						<span class="icon-32-print"></span>Gerar</br>Ata</a>
				   </div>
				   
				   <div class="icon" id="gerarConvite">
						<a href="javascript:gerarConviteDefesa(document.form, <?php echo $idDefesa;?>)">
						<span class="icon-32-print"></span>Gerar</br>Convite</a>
				   </div>
				   
				   <div class="icon" id="toolbar-back">
						<a href="index.php?option=com_controledefesas&view=listabancas">
						<span class="icon-32-back"></span>Voltar</a>
				   </div>
				   
				   <input name='task' type='hidden' value='display'>
				   <input name='idDefesa' type='hidden' value = <?php echo $idDefesa;?>>
				   <input name='idAluno' type='hidden' value = <?php echo $idAluno;?>>
				   <input name='avaliacao' type='hidden' value = ''>
				   <input id="numDefesa" name='numDefesa' type='hidden' value = ''>
				   <input id="emails" name='emails' type='hidden' value = <?php echo $emails;?>>
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
		  <td colspan='3'><?php echo $Aluno[0]->nome_aluno; ?></td>
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
		  <td colspan='3' ><?php echo $Aluno[0]->nomeProfessor; ?></td>
		</tr>
		<tr>
			<td bgcolor="#B0B0B0" style='font-weight: bold;' width='20%'>CURSO:</td>
		 	<td colspan='3'><?php echo $arrayTipoDefesa[$Aluno[0]->curso];?></td>
		</tr>
		</tbody>
	</table>


	<br> 

	<br>

<h2>Dados da Defesa - <?php echo $tipoDefesa[$Defesa[0]->tipoDefesa];  ?> </h2>
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

		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='20%'>Data:</td>
		  <td ><?php echo $Defesa[0]->data ?> </td>
		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='20%'>Horário:</td>
		  <td colspan='1'><?php echo $Defesa[0]->horario ?> </td>
		</tr>
		<tr>
		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='20%'>Local: </td>
		  <td ><?php echo $Defesa[0]->local ?> </td>
		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='20%'>Conceito Obtido:</td>
		  <td colspan='3'> <?php if ($Defesa[0]->conceito == NULL) echo "<font color = red ><b> Conceito não Lançado </b> </font>"; 
		  	else echo $Defesa[0]->conceito; ?> 
		  </td>
		</tr>
		<tr>
		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='20%'> Prévia: </td>
		  <td colspan='3'> <a href = "" target = "_blank">  Download </a> </td>
		</tr>

		</tr>

	  </tbody>
	</table>
	
	<?php

	if ( $Defesa[0]->banca_id != 0 ){ 

			?>
		<h2>Dados da Comissão Examinadora</h2>
		<hr />
			
			<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
		      <tbody>
		        <tr bgcolor='#B0B0B0'>
		          <td style='text-align: center; font-weight: bold;' width='5%'></td>		
		          <td style='text-align: center; font-weight: bold;' width='5%'></td>	
		          <td style='text-align: center; font-weight: bold;' width='55%'>MEMBROS DA BANCA</td>
		          <td style='text-align: center; font-weight: bold;' width='20%'>FILIAÇÃO</td>
		          <td style='text-align: center; font-weight: bold;' width='20%'>FUNÇÃO</td>
		        </tr>

		        <?php
		        if(isset ($MembrosBanca)){


					foreach( $MembrosBanca as $membro )
					{
						if($membro->funcao == 'P')

							$nome_orientador = $membro->nome;
					?>
					<tr>

					  <td align='center'>  
					  	<?php if ($botao == 0 || $botao == 2) { ?>
					  	<a 	href ="index.php?option=com_controledefesas&view=carta&idMembro=<?php echo $membro->id?>&idDefesa=<?php echo $Defesa[0]->idDefesa?>&funcao=<?php echo $membro->funcao?>" TARGET="_blank">
					  	<?php }
					  	else { ?>
					  		
							<a href ="javascript:observacao(6)">
					  	<?php } 
					  	?>
					  		<img src="components/com_controledefesas/assets/images/carta.png" border="0" title='Carta de Agradecimento'>  
					  	</a>
					  </td>
					  <td align='center'>
					  	<?php if ($botao == 0 || $botao == 2) { ?>
					  	<a href ="index.php?option=com_controledefesas&view=declaracao&idMembro=<?php echo $membro->id?>&idDefesa=<?php echo $Defesa[0]->idDefesa?>&funcao=<?php echo $membro->funcao?>" TARGET="_blank"x>
					  	<?php }
					  	else { ?>
					  		
							<a href ="javascript:observacao(6)">
					  	<?php } 
					  	?>
					  		<img src="components/com_controledefesas/assets/images/declaracao.png" border="0" title='Declaração de Participação'> 
					  	</a>
					  </td>
					  <td align='center'><?php echo $membro->nome;?></td>
					  <td align='center'><?php echo $membro->filiacao;?></td>
					  <td align='center'><?php echo $array_funcao[$membro->funcao];?></td>   
					</tr>
						
					<?php
					}
				}
	} ?>
    
      </tbody>
    </table>


<div id="box-toggle" class="box">
<div class="tgl"></div></div>

<div id="dialog-form" title="Digitar o número de defesa" style='display:none'>
 
  <form>
    <fieldset>	
		<table width="100%">	
				<label>Digite o numero da defesa:</label>
				<!--input type="text" rows="5" cols="10" name="justicaDialog" id="justicaDialog" class="text ui-widget-content ui-corner-all" value= </?php echo $justificativa; ?> --> 
				<textarea name="numDefesa" id="numDefesa" value= '<?php echo $numDefesa; ?>' rows="50" cols="11" style="margin: 0px; width: 250px; height: 85px;" onkeypress='return SomenteNumero(event)'></textarea>
		</table>
    </fieldset>
  </form>
  <button id="buttonIndeferir" type="button" value="Salvar" class="btn btn-primary">
		<i class="icone-search icone-white"></i> Salvar
  </button>
</div>

<script>
    jQuery("#buttonIndeferir").click(function(){
        var form = $('formAvaliacao');
		
		
		if(jQuery('#numDefesa').value == ''){
			alert('Voce precisa digitar o numero de defesa.')
		}
		else{
			form.avaliacao.value = indeferir;
			form.task.value = 'setarNumDefesa';
			form.numDefesa.value = jQuery('#numDefesa').value;
			form.submit();		
		}
	});
	
	
	function SomenteNumero(e){
		var tecla=(window.event)?event.keyCode:e.which;
		if((tecla>47 && tecla<58)) 
			return true;
		else{
			if (tecla==8 || tecla==0) 
				return true;
			else 
				return false;
		}
	}	
		
</script>

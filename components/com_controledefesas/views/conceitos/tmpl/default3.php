

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

$Banca = $this->banca;
$Aluno = $this->aluno;
$Defesa = $this->defesa;
$MembrosBanca = $this->membrosBanca;

$idDefesa= JRequest::getVar('idDefesa'); 
$idAluno = JRequest::getVar('idAluno'); 


var_dump($Defesa[0]->conceito);
var_dump($Defesa[0]->banca_id);
var_dump($Defesa[0]->data);
echo(date('d/m/Y'));




$linha_pes = array(0 => "Todos", 1 => "Banco de Dados e Recuperação da Informação", 2 => "Sistemas Embarcados & Engenharia de Software", 3 => "Inteligência Artificial", 4 => "Visão Computacional e Robótica", 5 => "Redes e Telecomunicações", 6 => "Otimização Algorítmica e Complexidade");
			
$arrayTipoDefesa = array('1' => "Mestrado", '2' => "Doutorado");

//$status_banc = array (0 => "Banca Indeferida", 1 => "Banca Deferida", NULL => "Banca Não Avaliada");

$array_funcao = array ('P' => "Presidente",'E' => "Membro Externo", 'I' => "Membro Interno");


$justificativa="";
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


function tipoDefesa($tipoDefesa){

	switch ($tipoDefesa) {
		case 'Q1':
			return "Exame de Qualificação 1";
			break;
		case 'Q2':
			return "Qualificação 2";
			break;
		case 'T':

			return "Tese";
			break;

		case 'D':

			return "Dissertação";
			break;
		default:
			break;
	}

}


?>

<script type="text/javascript" src="/icomp/components/com_defesascoordenador/assets/jquery-ui-1.11.2.custom/jquery-ui.js"></script>

<script language="JavaScript">
       
        function aprovar(form){        
           var confirmar;
           var deferir = 1;
           
           confirmar = window.confirm('Você tem certeza que deseja APROVAR esse(a) aluno(a)?');
       
           if(confirmar == true){
				form.task.value = 'aprovar';
				
				form.submit(); // 'continuacao no arquivo de controller.php da raiz'

           }
        }
						
		function reprovar(form){        
           var confirmar;
           
            
           confirmar = window.confirm('Você tem certeza que deseja REPROVAR esse(a) aluno(a)?');
			
           if(confirmar == true){		
				form.task.value = 'reprovar';
				//form.avaliacao.value = deferir;
				form.submit();
           }
           
        }



</script>

<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css"> 	
<link rel="stylesheet" type="text/css" href="components/com_defesascoordenador/assets/jquery-ui-1.11.2.custom/jquery-ui.css"> 	



<div id="toolbar-box">
	<div class="m">
		<div class="toolbar-list" id="toolbar">
		  <div class="cpanel2">
				<form method="post" id="formAvaliacao" name="form" enctype="multipart/form-data" action="index.php?option=com_controledefesas&view=conceitos" >
					<div <?php if(($Defesa[0]->conceito != '') || ($Defesa[0]->data > (date('Y/m/d'))) || ($Defesa[0]->banca_id == 0)  ||   ($Defesa[0]->tipoDefesa = 'T' OR $Defesa[0]->tipoDefesa = 'D')  && ($Defesa[0]->status_banca == NULL)  ) {  ?> style="display: none;" <?php } ?> class="icon" id="toolbar-back">
						<a href ="javascript:aprovar(document.form)" class = 'toolbar'>
						<span class="icon-32-apply"></span>Aprovar</a>
					</div>
				
					<div <?php if(($Defesa[0]->conceito != '') || ($Defesa[0]->data > (date('Y/m/d')))  || ($Defesa[0]->banca_id == 0)  ||  ($Defesa[0]->tipoDefesa = 'T' OR $Defesa[0]->tipoDefesa = 'D')  && ($Defesa[0]->status_banca == NULL)    ) { ?> style="display: none;"<?php } ?>  class="icon" id="indeferir">
						<a href ="javascript:reprovar(document.form)" class = 'toolbar'>
						<span class="icon-32-delete"></span>Reprovar</a>
				   </div>

				   <div class="icon" id="toolbar-back">
						<a href="index.php?option=com_controledefesas&view=listabancas">
						<span class="icon-32-back"></span>Voltar</a>
				   </div>
				   
				   <input name='task' type='hidden' value='display'>
				   <input name='idDefesa' type='hidden' value = <?php echo $idDefesa;?>>
				   <input name='idAluno' type='hidden' value = <?php echo $idAluno;?>>
				   <input name='avaliacao' type='hidden' value = ''>
				   <input id="justificativa" name='justificativa' type='hidden' value = ''>
				   <input id="emails" name='emails' type='hidden' value = <?php echo $emails;?>>
				</form>   
				
		</div>
		<div class="clr"></div>
		</div>

	<div class="pagetitle icon-48-user"><h2><?php echo $this->msg; ?></h2></div>
	</div>
</div>

<?php //var_dump($emails); ?>

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
		</tbody>
	</table>


	<br> 

	<br>

<h2>Dados da Defesa - <?php echo (tipoDefesa($Defesa[0]->tipoDefesa));  ?> </h2>
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
		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='20%'>CURSO:</td>
		  <td width='30%'><?php echo $arrayTipoDefesa[$Aluno[0]->curso];?></td>
		  <td bgcolor="#B0B0B0" style='font-weight: bold;' width='25%'>Conceito Obtido:</td>
		  <td width='25%'><?php if ($Defesa[0]->conceito == NULL) echo "<b> Conceito  NÃO Lançado </b>"; 
		  	else echo $Defesa[0]->conceito;?> </td>
		</tr>
		

	  </tbody>
	</table>
	<?php


	if ($Defesa[0]->tipoDefesa != 'Q1' AND $Aluno[0]->curso != 2){ 
		// VERIFICAR ISSO COM URGENCIA !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1
		//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!11
	?>
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
}
    ?>
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

	<?php 


	if ((date('Y/m/d'))  < ($Defesa[0]->data)){
	?>
		<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
		alert ("Observações.:\n\n  -Não é possivel lançar o conceito, pois a 
			defesa ainda não foi realizada.")
		</SCRIPT>
	<?php 
	}

	else if ($Defesa[0]->conceito != ''){ 
	?>

		<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
		alert ("Observações.:\n\n  -Conceito já foi devidamente Lançado.")
		</SCRIPT>
	
	<?php 
	}

	else if ($Defesa[0]->banca_id == NULL || $Defesa[0]->banca_id == 0){ 
	?>
		<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
		alert ("Observações.:\n\n  -Não será possivel lançar o conceito, pois não 
			consta no Banco de Dados a existência de uma Banca Avaliadora.")
		</SCRIPT>
	<?php 
	}

		else if(($Defesa[0]->tipoDefesa = 'T' OR $Defesa[0]->tipoDefesa = 'D')  && ($Defesa[0]->status_banca == NULL)){
	?>
		<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
		alert ("Observações.:\n\n  -Não é possivel lançar o conceito, pois a Banca Avaliadora
			ainda não foi aprovada pelo Coordenador.")
		</SCRIPT>

	<?php 
	}
	?>

<div id="box-toggle" class="box">
<div class="tgl"></div></div>
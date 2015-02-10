<?php
// No direct access to this file
$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
//$document->addScript("includes/js/joomla.javascript.js");


function formatarData($data){
	$data = explode("-", $data);
	$aux = $data[2] . "/" . $data[1] . "/" .$data[0] ;
	return $aux;	
}
	


$idBanca = $this->idBanca;

$Banca = $this->banca;
$Aluno = $this->aluno;
$Defesa = $this->defesa;
$MembrosBanca = $this->membrosBanca;

$linha_pes = array(0 => "Todos", 1 => "Banco de Dados e Recuperação da Informação"	, 2 => "Sistemas Embarcados & Engenharia de Software", 3 => "Inteligência Artificial", 4 => "Visão Computacional e Robótica", 5 => "Redes e Telecomunicações", 6 => "Otimização Algorítmica e Complexidade");
			
$arrayTipoDefesa = array('D' => "Mestrado", 'T' => "Doutorado");

$status_banc = array (0 => "Banca Indeferida", 1 => "Banca Deferida", NULL => "Banca Não Avaliada");

$array_funcao = array ('P' => "Presidente",'E' => "Membro Externo", 'I' => "Membro Interno");

$emails;
$nomesMembros;
$filiacaoMembros;
$justificativa="";
$nome_orientador = "";

foreach( $MembrosBanca as $membro ){
		if($membro->funcao == 'P')
			$nome_orientador = $membro->nome;
}

$sucesso = $this->status;	
$sucesso2 = $this->status;
if($sucesso == true OR $sucesso2 == true ){
	JFactory :: getApplication()->enqueueMessage(JText :: _('Opera&#231;&#227;o realizada com sucesso.'));
}
else if(($sucesso == false AND $sucesso !=NULL) OR ($sucesso2 == false AND $sucesso2 !=NULL) ){
	JError :: raiseWarning(100, 'ERRO: Opera&#231;&#227;o Falhou.');
}


?>

<script type="text/javascript" src= "/icomp/components/com_defesascoordenador/assets/jquery-ui-1.11.2.custom/jquery-ui.js"></script>
<!--script type="text/javascript" src= "</?php echo JPATH_COMPONENT;?>/assets/jquery-ui-1.11.2.custom/jquery-ui.js"></script-->


<script language="JavaScript">
       
        function deferirBanca(form){        
           var confirmar;
           var deferir = 1;
           
           confirmar = window.confirm('Você tem certeza que deseja DEFERIR essa banca?');
       
           if(confirmar == true){
			   
				jQuery( "#process" ).dialog({
					autoOpen: false,
					width: 400,
					resizable: false,
					draggable: false,
					close: function(){
						// executa uma ação ao fechar
						//alert("você fechou a janela");
					}
				});
				jQuery( "#process" ).dialog( "open" ).html("<p>Aguarde, enviando os dados...</p>");
				
				
				form.task.value = 'deferirBanca';
				form.avaliacao.value = deferir;
				form.submit();
           }
        }
						
		function indeferirBanca(form){        
           var confirmar;
       
           confirmar = window.confirm('Você tem certeza que deseja INDEFERIR essa banca?');
			
           if(confirmar == true){		
				jQuery( "#dialog-form" ).dialog();
           }
           
        }
				
</script>

<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css"> 	
<link rel="stylesheet" type="text/css" href="components/com_defesascoordenador/assets/jquery-ui-1.11.2.custom/jquery-ui.css"> 	


<div id="toolbar-box">
	<div class="m">
		<div class="toolbar-list" id="toolbar">
		  <div class="cpanel2">
				<form method="post" id="formAvaliacao" name="form" enctype="multipart/form-data" action="index.php?option=com_defesascoordenador&view=avaliarBanca" >
					<div <?php if($Banca[0]->status_banca != NULL) { ?> style="display: none;" <?php } ?> class="icon" id="toolbar-back">
						<a href ="javascript:deferirBanca(document.form)" class = 'toolbar'>
						<span class="icon-32-apply"></span>Deferir</a>
					</div>
				
					<div <?php if($Banca[0]->status_banca != NULL) { ?> style="display: none;"<?php } ?>  class="icon" id="indeferir">
						<a href ="javascript:indeferirBanca(document.form)" class = 'toolbar'>
						<span class="icon-32-deny"></span>Indeferir</a>
				   </div>
				   
					
				   <div class="icon" id="toolbar-back">
						<a href="index.php?option=com_defesascoordenador&view=listabancas">
						<span class="icon-32-back"></span>Voltar</a>
				   </div>
				   
				   <input name='task' type='hidden' value='display'>
				   <input name='idBanca' type='hidden' value = <?php echo $idBanca;?>>
				   <input name='avaliacao' type='hidden' value = ''>
				   <input id="justificativa" name='justificativa' type='hidden' value = ''>
				   
				   
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
		  <td colspan='3'><?php echo formatarData($Aluno[0]->anoingresso);?></td>
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

<div id="dialog-form" title="Justificar Indeferimento" style='display:none'>
 
  <form>
    <fieldset>	
		<table width="100%">	
				<label>Digite a Justificativa de Indeferimento:</label>
				<!--input type="text" rows="5" cols="10" name="justicaDialog" id="justicaDialog" class="text ui-widget-content ui-corner-all" value= </?php echo $justificativa; ?> --> 
				<textarea name="justificaDialog" id="justificaDialog" value= <?php echo $justificativa; ?> rows="50" cols="11" style="margin: 0px; width: 250px; height: 85px;"></textarea>
		</table>
    </fieldset>
  </form>
  <button id="buttonIndeferir" type="button" value="Salvar" class="btn btn-primary">
		<i class="icone-search icone-white"></i> Salvar
  </button>
</div>


<script>
    jQuery("#buttonIndeferir").click(function(){
		var indeferir = 0;
        var form = $('formAvaliacao');


		if($('justificaDialog').value == ''){
			alert('Digite a Justificativa do Indeferimento.')
		}
		else{
			form.avaliacao.value = indeferir;
			form.task.value = 'indeferirBanca';
			form.justificativa.value = $('justificaDialog').value;
			form.submit();		
		}
	});
		
</script>

<div id="process" title="Mensagem">
    <p></p>
</div>

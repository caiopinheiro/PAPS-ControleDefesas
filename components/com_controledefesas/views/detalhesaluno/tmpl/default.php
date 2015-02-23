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

$idAluno = $this->idAluno;

$Aluno = $this->aluno;
$Defesas = $this->defesas;
$MembrosBanca = $this->membrosBanca;

$arrayCurso = array (1 => "Mestrado", 2 => "Doutorado", 3 => "Especial");
$arrayTipoDefesa = array('Q1' => "Qualificação 1", 'Q2' => "Qualificação 2", 'D' => "Dissertação", 'T' => "Tese");
$arrayFuncaoMembro = array ('P' => "Presidente",'E' => "Membro Externo", 'I' => "Membro Interno");

?>

<script type="text/javascript" src= "/icomp/components/com_controledefesas/assets/jquery-ui-1.11.2.custom/jquery-ui.js"></script>

<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css"> 	
<link rel="stylesheet" type="text/css" href="components/com_controledefesas/assets/jquery-ui-1.11.2.custom/jquery-ui.css">

<form method="post" id="formDadosAluno" name="form" enctype="multipart/form-data" action="index.php?option=com_controledefesas&view=detalhesaluno" >

<div id="toolbar-box">
	<div class="m">
		<div class="toolbar-list" id="toolbar">
		  <div class="cpanel2">

			<div class="icon" id="toolbar-back">
				<a href="index.php?option=com_controledefesas&view=listaalunos">
				<span class="icon-32-back"></span>Voltar</a>
			</div>
		   
			<input name='task' type='hidden' value='display'>
			<input name='idAluno' type='hidden' value = <?php echo $idAluno;?>>

		</div>
		<div class="clr"></div>
		</div>

		<div class="pagetitle icon-48-user"><h2><?php echo $this->msg; ?></h2></div>
	</div>
</div>

<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />

<fieldset>
    <h2>Dados do Aluno</h2>
    <hr />

    <table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
      <tbody>
        <tr>
          <td style='font-weight: bold;'>NOME:</td>
          <td colspan='3' ><?php echo $Aluno[0]->nome;?></td>
        </tr>
        <tr>
          <td style='font-weight: bold;' width='20%'>NATURALIDADE:<br>NACIONALIDADE:</td>
          <td width='30%'><?php echo $Aluno[0]->cidade;?><br><?php echo $Aluno[0]->pais;?></td>
          <td style='font-weight: bold;' width='25%'>DATA DE NASCIMENTO:</td>
          <td width='25%'><?php echo $Aluno[0]->datanascimento;?></td>
        </tr>
        <tr>
          <td style='font-weight: bold;'>LINHA DE PESQUISA:</td>
          <td colspan='3'><?php echo $Aluno[0]->nome_area;?></td>
        </tr>
        <tr>
          <td style='font-weight: bold;'>CURSO:</td>
          <td><?php echo  $arrayCurso[$Aluno[0]->curso];?></td>
          <td style='font-weight: bold;'>ANO DE INGRESSO:</td>
          <td><?php echo formatarData($Aluno[0]->anoingresso);?></td>
        </tr>
        <tr>
          <td style='font-weight: bold;'>E-MAIL:</td>
          <td><?php echo  $Aluno[0]->email;?></td>
          <td style='font-weight: bold;'>TELEFONE:</td>
          <td><?php echo  $Aluno[0]->telresidencial;?><br><?php echo $Aluno[0]->telcelular;?></td>
        </tr>
      </tbody>
    </table>
</fieldset>

<div id="box-toggle" class="box">

<h2>Defesas Realizadas</h2>
<hr />
<div class="tg2">
<h3>Profici&#234;ncia em Idioma Estrangeiro</h3>
<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
  <tbody>
    <tr>
      <td width='15%' style='font-weight: bold;'>IDIOMA:</td>
      <td width='15%'><?php echo  $Aluno[0]->idiomaExameProf;?></td>
      <td width='10%'><span style='font-weight: bold;'>SITUA&Ccedil;&Atilde;O:</span></td>
      <td width='10%'><?php echo  $Aluno[0]->conceitoExameProf;?></td>
      <td width='25%'><span style='font-weight: bold;'>DATA DO EXAME:</span></td>
      <td width='25%'><?php echo  $Aluno[0]->dataExameProf;?></td>
    </tr>
  </tbody>
</table>
<br />

<?php if($Aluno[0]->curso == 1 || $Aluno[0]->curso == 3){ ?>
<hr style='width: 100%; height: 2px; font-family: Verdana;'>
<h3>Exame de Qualifica&#231;&#227;o</h3>
<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
  <tbody>
    <?php
    if(isset ($Defesas)){
        foreach($Defesas as $defesa ){ if($defesa->tipoDefesa == 'Q1'){ ?>
            <tr>
              <td style='font-weight: bold;'>TEMA:</td>
              <td colspan='3' rowspan='1'><?php echo $defesa->titulo;?></td>
            </tr>
            <tr>
              <td width='30%' style='font-weight: bold;'>DATA:</td>
              <td width='20%'><?php echo formatarData($defesa->data);?></td>
              <td width='25%' style='font-weight: bold;'>CONCEITO:</td>
              <td width='25%'><?php echo $defesa->conceito;?></td>
            </tr>
            <tr>
              <td style='font-weight: bold;'>COMISS&Atilde;O EXAMINADORA:</td>
              <td colspan='3' rowspan='1'>
                 <?php
                    if(isset ($MembrosBanca)){
                        foreach($MembrosBanca as $membro ){ 
                            if($membro->tipoDefesa == 'D' || $membro->tipoDefesa == 'T' || $membro->funcao == 'P'){
                                echo $membro->nome;
                                echo " (".$arrayFuncaoMembro[$membro->funcao].")";
                                echo " - ".$membro->filiacao."<br />";
                            }
                        }
                    }
                 ?>
                </td>
            </tr>
        <?php
        }
        }
    } ?>
  </tbody>
</table>
<br />
<?php
}
?>

<?php if($Aluno[0]->curso == 2){ ?>
<hr style='width: 100%; height: 2px; font-family: Verdana;'>
<h3>Exame de Qualifica&#231;&#227;o I</h3>
<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
  <tbody>
    <?php
    if(isset ($Defesas)){
        foreach($Defesas as $defesa ){ if($defesa->tipoDefesa == 'Q1'){ ?>
            <tr>
              <td style='font-weight: bold;'>TEMA:</td>
              <td colspan='3' rowspan='1'><?php echo $defesa->titulo;?></td>
            </tr>
            <tr>
              <td width='30%' style='font-weight: bold;'>DATA:</td>
              <td width='20%'><?php echo formatarData($defesa->data);?></td>
              <td width='25%' style='font-weight: bold;'>CONCEITO:</td>
              <td width='25%'><?php echo $defesa->conceito;?></td>
            </tr>
            <tr>
              <td width='30%' style='font-weight: bold;'>EXAMINADOR(A):</td>
              <td colspan='3'><?php echo $defesa->examinador;?></td>
            </tr>
        <?php
        }
        }
    } ?>
  </tbody>
</table>
<br />
<?php
}
?>

<?php if($Aluno[0]->curso == 2){ ?>
<hr style='width: 100%; height: 2px; font-family: Verdana;'>
<h3>Exame de Qualifica&#231;&#227;o  II</h3>
<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
  <tbody>
    <?php
    if(isset ($Defesas)){
        foreach($Defesas as $defesa ){ if($defesa->tipoDefesa == 'Q2'){ ?>
            <tr>
              <td style='font-weight: bold;'>TEMA:</td>
              <td colspan='3' rowspan='1'><?php echo $defesa->titulo;?></td>
            </tr>
            <tr>
              <td width='30%' style='font-weight: bold;'>DATA:</td>
              <td width='20%'><?php echo formatarData($defesa->data);?></td>
              <td width='25%' style='font-weight: bold;'>CONCEITO:</td>
              <td width='25%'><?php echo $defesa->conceito;?></td>
            </tr>
            <tr>
              <td style='font-weight: bold;'>COMISS&Atilde;O EXAMINADORA:</td>
              <td colspan='3' rowspan='1'>
                 <?php
                    if(isset ($MembrosBanca)){
                        foreach($MembrosBanca as $membro ){ 
                            if($membro->tipoDefesa == 'D' || $membro->tipoDefesa == 'T' || $membro->funcao == 'P'){
                                echo $membro->nome;
                                echo " (".$arrayFuncaoMembro[$membro->funcao].")";
                                echo " - ".$membro->filiacao."<br />";
                            }
                        }
                    }
                 ?>
                </td>
            </tr>
        <?php
        }
        }
    } ?>
  </tbody>
</table>
<br />
<?php
}
?>

<hr style='width: 100%; height: 2px; font-family: Verdana;'>
<h3>Defesa de <?php  if($Aluno[0]->curso == 2) echo "Tese"; else echo "Disserta&#231;&#227;o"; ?></h3>
<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
  <tbody>
    <?php
    if(isset ($Defesas)){
        foreach($Defesas as $defesa ){ if($defesa->tipoDefesa == 'D' || $defesa->tipoDefesa == 'T'){ ?>
            <tr>
              <td style='font-weight: bold;'>T&Iacute;TULO:</td>
              <td colspan='3' rowspan='1'><?php echo $defesa->titulo;?></td>
            </tr>
            <tr>
              <td width='30%' style='font-weight: bold;'>DATA:</td>
              <td width='20%'><?php echo formatarData($defesa->data);?></td>
              <td width='25%' style='font-weight: bold;'>CONCEITO:</td>
              <td width='25%'><?php echo $defesa->conceito;?></td>
            </tr>
            <tr>
              <td style='font-weight: bold;'>COMISS&Atilde;O EXAMINADORA:</td>
              <td colspan='3' rowspan='1'>
                 <?php
                    if(isset ($MembrosBanca)){
                        foreach($MembrosBanca as $membro ){ 
                            if($membro->tipoDefesa == 'D' || $membro->tipoDefesa == 'T' || $membro->funcao == 'P'){
                                echo $membro->nome;
                                echo " (".$arrayFuncaoMembro[$membro->funcao].")";
                                echo " - ".$membro->filiacao."<br />";
                            }
                        }
                    }
                 ?>
                </td>
            </tr>
        <?php
        }
        }
    } ?>
  </tbody>
</table>
</div>
</div>

</form>

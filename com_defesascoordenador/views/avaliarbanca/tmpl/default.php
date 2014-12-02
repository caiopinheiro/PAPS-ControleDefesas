<?php
// No direct access to this file

$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
$document->addScript("includes/js/joomla.javascript.js");

$idBanca = $this->idBanca;
$Banca = $this->banca;
/*function verLinhaPesquisa($idLinha, $inf) {

         $db = & JFactory::getDBO();
         $db->setQuery("SELECT nome, sigla FROM #__linhaspesquisa WHERE id = $idLinha LIMIT 1");
         $linha = $db->loadObjectList();

         if($inf == 2)
           return($linha[0]->sigla);

         return($linha[0]->nome);
}*/
?>
<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css"> 	

<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
	  <div class="cpanel2">
		<div class="icon" id="toolbar-back">
			<a href="index.php?option=com_defesascoordenador&view=listabancas">	
			<span class="icon-32-edit"></span>Confirmar</a>
	   </div>
		
	   <div class="icon" id="toolbar-back">
			<a href="index.php?option=com_defesascoordenador&view=listabancas">
			<span class="icon-32-back"></span>Voltar</a>
	   </div>
	</div>
<div class="clr"></div>
</div>

<div class="pagetitle icon-48-user"><h2><?php echo $this->msg; ?></h2></div>
</div></div>

<h2>Dados da Banca</h2>
<hr />

	<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
	  <tbody>
		<tr>
		  <td style='font-weight: bold;'>ORIENTADOR:</td>
		  <td colspan='3' ><?php echo $Banca[0]->nome; ?></td>
		</tr>
		
		<tr>
		  <td style='font-weight: bold;' width='20%'>LINHA DE PESQUISA:</td>
		  <td colspan='3'><!--?php echo $aluno->cidade;?--><br><!--?php echo $aluno->pais;?--></td>
		</tr>
		
		<tr>
		  <td style='font-weight: bold;' width='20%'>ALUNO:</td>
		  <td colspan='3'><!--?php echo $aluno->cidade;?--><br><!--?php echo $aluno->pais;?--></td>
		</tr>
		
		<tr>
		  <td style='font-weight: bold;' width='20%'>TITULO:</td>
		  <td colspan='3'><!--?php echo $aluno->cidade;?--><br><!--?php echo $aluno->pais;?--></td>
		</tr>
		
		<tr>
		  <td style='font-weight: bold;' width='20%'>RESUMO:</td>
		  <td colspan='3'><!--?php echo $aluno->cidade;?--><br><!--?php echo $aluno->pais;?--></td>
		</tr>
		
		<tr>
		  <td style='font-weight: bold;' width='20%'>TIPO BANCA:</td>
		  <td width='30%'><!--?php echo $aluno->cidade;?--><br><!--?php echo $aluno->pais;?--></td>
		  <td style='font-weight: bold;' width='25%'>STATUS BANCA:</td>
		  <td width='25%'><?php if($Banca[0]->status_banca == 0) 
									echo 'Deferida'; 
								else if($Banca[0]->status_banca == 1) 
									echo 'Indeferida'; 
								else 
									echo 'Não Avaliada';?></td>
		</tr>
		
		
	  </tbody>
	</table>
	
	<table style='text-align: left; width: 100%;' border='1' cellpadding='3' cellspacing='0'>
      <tbody>
        <tr bgcolor='#B0B0B0'>
          <td style='text-align: center; font-weight: bold;' width='70%'>MEMBROS DA BANCA</td>
          <td style='text-align: center; font-weight: bold;' width='20%'>FILIAÇÃO</td>
          <td style='text-align: center; font-weight: bold;' width='10%'>FUNÇÃO</td>
        </tr>
    
        <?php
        $pontos = 0;
        $creditos = 0;
        $totDisciplinas = 0;
        foreach( $disciplinasCursadas as $disc )
        {
        ?>
        <tr>
          <td align='center'><?php echo $disc->codigo;?></td>
          <td align='center'><?php echo $disc->periodo;?></td>
          <td><?php echo $disc->nomeDisciplina;?></td>
          <td align='center'><?php echo $disc->conceito;?></td>
          <td align='center'><?php echo number_format($disc->frequencia, 2);?></td>
          <td align='center'><?php echo $disc->creditos;?></td>
          <td align='center'><?php echo $disc->carga;?></td>
        </tr>
        <?php
             $pontos += $pontuacao[$disc->conceito];
             $totDisciplinas++;
             if($disc->conceito == 'A' ||  $disc->conceito == 'B' || $disc->conceito == 'C' ) $creditos += $disc->creditos;
        } ?>
    
      </tbody>
    </table>


<div id="box-toggle" class="box">
<div class="tgl"></div></div>

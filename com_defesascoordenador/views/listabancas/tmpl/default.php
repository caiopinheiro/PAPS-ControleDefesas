<?php
// No direct access to this file

$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
$document->addScript("includes/js/joomla.javascript.js");



$bancas = $this->bancas;
$nome_orientador = $this->nome_orientador;
$status_bancas = $this->status_bancas;

if(($this->nome_orientador == null) AND ($this->status_bancas == null)){
	$nome_orientador = '';
	$status_bancas = 3;
}

?>

<script language="JavaScript">
        function avaliarBanca(form){        
           var idSelecionado = 0;
		   
           for(i = 0;i < form.idBancaSelec.length ;i++)
                if(form.idBancaSelec[i].checked) idSelecionado = form.idBancaSelec[i].value;
        
           if(idSelecionado > 0){
				form.task.value = 'avaliarBanca';
				form.idBanca.value = idSelecionado;
				form.submit();
           } else {
           	alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o.')
           }
        }
</script>
        
    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">

    
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();
		});
	</script>
    
	<link rel="stylesheet" href="components/com_portalsecretaria/assets/css/estilo.css" type="text/css" />

    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_defesascoordenador&view=listabancas&task=banca">
    
		<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-preview">
                    <a href="javascript:avaliarBanca(document.form)" class="toolbar">
                    <span class="icon-32-preview"></span>Avaliar</br>Banca</a>
                </div>
                
                
                <div class="icon" id="toolbar-back">
                    <a href="index.php?option=com_defesascoordenador">
                    <span class="icon-32-back"></span>Voltar</a>
                </div>
            </div>
            <div class="clr"></div>
            </div>
            
            <div class="pagetitle icon-48-groups"><h2>Lista de Bancas</h2></div>
        </div></div>

		<!-- FILTRO DA BUSCA -->
		<fieldset>
        	<legend>Filtros para consulta</legend>
            <table width="100%">
                <tr>
                    <td>Nome Orientador</td>
                    <td>Status Banca</td>
                </tr>
                <tr>
	                <td><input id="buscaNomeOrientador" name="buscaNomeOrientador" type="text" value="<?php echo $nome_orientador;?>"/></td>
                    <td>
                    <select name="buscaStatusBanca">	
						<option value="3" <?php if($status_bancas == 3) echo 'SELECTED';?>>Todos</option>
                        <option value="0" <?php if($status_bancas == 0) echo 'SELECTED';?>>Banca Deferidas</option>
                        <option value="1" <?php if($status_bancas == 1) echo 'SELECTED';?>>Bancas Indeferidas</option>
                        <option value="2" <?php if($status_bancas == 2) echo 'SELECTED';?>>Bancas Não Avaliadas</option>
                    </select>
                    </td>
                    <!--td>
                    	<select name="buscaAnoIngresso">
            				<option value="">Todos</option>
	        				</?php
			                 $database->setQuery("SELECT DISTINCT anoingresso from #__aluno WHERE orientador = ".$professor->id." ORDER BY anoingresso");
	        			     $anos = $database->loadObjectList();

				             foreach($anos as $ano) { ?>
                             	<option value="</?php echo $ano->anoingresso;?>" </?php if($anoingresso == $ano->anoingresso) echo 'SELECTED'; ?>> </?php echo $ano->anoingresso;?></option>
	          				</?php } ?>
			            </select>
                    </td-->
                    <!--td><select name="buscaStatus">
                            <option value="0" </?php if($status == 0) echo 'SELECTED';?>>Alunos Correntes</option>
                            <option value="1" </?php if($status == 1) echo 'SELECTED';?>>Alunos Egressos</option>
                            <option value="2" </?php if($status == 2) echo 'SELECTED';?>>Alunos Desistentes</option>
                            <option value="3" </?php if($status == 3) echo 'SELECTED';?>>Alunos Desligados</option>
                            <option value="4" </?php if($status == 4) echo 'SELECTED';?>>Alunos Jubilados</option>
                            <option value="5" </?php if($status == 5) echo 'SELECTED';?>>Alunos com Matr&#237;cula Trancada</option>
                            <option value="6" </?php if($status == 6) echo 'SELECTED';?>>Todos</option>
                        </select>
            		</td-->
                    <td><button type="submit" value="Buscar" class="btn btn-primary">
                    		<i class="icone-search icone-white"></i> Buscar
                        </button>
                    </td>
                </tr>
			</table>
		</fieldset>
    
        <table class="table table-striped" id="tablesorter-imasters">
            <thead>
              <tr>
                <th></th>
                <th>Status</th>
                <th>Nome Orientador</th>
              </tr>
            </thead>
             
     		<tbody>
			<?php
            $status = array (0 => "Banca Deferida",1 => "Banca Indeferida", NULL => "Banca Não Avaliada");
			
            $statusImg = array (0 => "sim.gif",1 => "excluir.png",NULL => "editar.gif");

			foreach($bancas as $banca ) { ?>

            <tr>
                <td width='16'><input type="radio" name="idBancaSelec" value="<?php echo $banca->id;?>"></td>
                <td><img border='0' src='components/com_defesascoordenador/assets/images/<?php echo $statusImg[$banca->status_banca];?>' width='16' height='16' title='<?php echo $status[$banca->status_banca];?>'></td>
                <td><?php echo $banca->nome;?></td>
            </tr>

			<?php } ?>
		</tbody>
     </table>     
     <br />
     
     <span class="label label-inverse">Total de Bancas: <?php echo sizeof($bancas);?></span>
     
     <input name='task' type='hidden' value='display' />
     <input name='idBancaSelec' type='hidden' value='0' />
     <input name='idBanca' type='hidden' value='' /-->
</form>

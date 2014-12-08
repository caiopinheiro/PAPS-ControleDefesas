<?php
// No direct access to this file

$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
$document->addScript("includes/js/joomla.javascript.js");



$bancas = $this->bancas;

$status_bancas = $this->status_bancas;
$nome_aluno = $this->nome_aluno;
$tipo_banca = $this->tipo_banca;
$nome_orientador = $this->nome_orientador;
$linha_pesquisa = $this->linha_pesquisa;

if(($this->status_bancas == null) AND 
   ($this->nome_aluno == null) AND
   ($this->tipo_banca == null) AND 
   ($this->nome_orientador == null) AND
   ($this->linha_pesquisa == null)){
	$status_bancas = 3;
	$nome_aluno = '';
	$tipo_banca = 3;
	$nome_orientador = '';
	$linha_pesquisa = 0;
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

    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_defesascoordenador&view=listabancas">
    
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
            </br>
            <table width="100%">
                <tr>
                    <td>Status Banca</td>
					<td>Nome Aluno</td>
					<td>Tipo de Banca</td>
					<td>Nome Orientador</td>
					<td>Linha de Pesquisa</td>
                </tr>
                <tr>
                    <td>
                    <select name="buscaStatusBanca">	
						<option value="3" <?php if($status_bancas == 3) echo 'SELECTED';?>>Todos</option>
                        <option value="0" <?php if($status_bancas == 0) echo 'SELECTED';?>>Deferidas</option>
                        <option value="1" <?php if($status_bancas == 1) echo 'SELECTED';?>>Indeferidas</option>
                        <option value="2" <?php if($status_bancas == 2) echo 'SELECTED';?>>Não Avaliadas</option>
                    </select>
                    </td>
                    
                    <td ><input id="buscaNomeAluno" name="buscaNomeAluno" type="text" value="<?php echo $nome_aluno;?>"/></td>
                    
                    <td>
                    <select name="tipoBanca">	
						<option value="3" <?php if($tipo_banca == 3) echo 'SELECTED';?>>Todos</option>
                        <option value="0" <?php if($tipo_banca == 0) echo 'SELECTED';?>>Mestrado</option>
                        <option value="1" <?php if($tipo_banca == 1) echo 'SELECTED';?>>Doutorado</option>
                    </select>
                    </td>
                    
                    
                    <td><input id="buscaNomeOrientador" name="buscaNomeOrientador" type="text" value="<?php echo $nome_orientador;?>"/></td>
					
					
					<td>
                    <select name="linhaPesquisa">	
						<option value="3" <?php if($linha_pesquisa == 0) echo 'SELECTED';?>>Todos</option>
                        <option value="0" <?php if($linha_pesquisa == 1) echo 'SELECTED';?>>BD e RI</option>
                        <option value="1" <?php if($linha_pesquisa == 2) echo 'SELECTED';?>>SistEmb & EngSW</option>
                        <option value="3" <?php if($linha_pesquisa == 3) echo 'SELECTED';?>>Int. Art.</option>
                        <option value="0" <?php if($linha_pesquisa == 4) echo 'SELECTED';?>>Visão Comp. e Rob.</option>
                        <option value="1" <?php if($linha_pesquisa == 5) echo 'SELECTED';?>>Redes e Telec.</option>
                        <option value="3" <?php if($linha_pesquisa == 6) echo 'SELECTED';?>>Ot., Alg. e Complex.</option>
                    </select>
                    </td>
                    
                    <td align="right">
						<button type="submit" value="Buscar" class="btn btn-primary">
							<i class="icone-search icone-white"></i> Buscar
						</button>
					</td>	
                </tr>
				
				<tr>
							
				</tr>
                
			</table>
		</fieldset>
    
        <table class="table table-striped" id="tablesorter-imasters">
            <thead>
              <tr>
                <th></th>
                <th>Status Banca</th>
                <th>Nome Aluno</th>
                <th>Tipo de Banca</th>
                <th>Nome Orientador</th>
                <th>Linha de Pesquisa</th>
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
                <td><?php echo $banca->nome;?></td>
                <td><?php echo $banca->nome;?></td>
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

<?php
// No direct access to this file

$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
$document->addScript("includes/js/joomla.javascript.js");


$defesas = $this->defesas;

$nome_aluno = $this->nome_aluno;
//$titulo_defesa = $this->titulo_defesa;
$curso = $this->curso;
$tipo_defesa = $this->tipo_defesa;
$data_defesa = $this->data_defesa;
$local_defesa = $this->local_defesa;

if(($this->nome_aluno == NULL) AND 
   //($this->titulo_defesa == NULL) AND
   ($this->curso == NULL) AND 
   ($this->tipo_defesa == NULL) AND
   ($this->data_defesa == NULL) AND
   ($this->local_defesa == NULL)){

    $nome_aluno = '';
    //$titulo_defesa = '';
    $curso = 0;
    $tipo_defesa = '';
    $data_defesa = '';
    $local_defesa = '';
}

?>

<script language="JavaScript"> 
    function visualizar(form, idDefesa, idAluno) {
        form.task.value = 'detalhesDefesa';
        form.idDefesa.value = idDefesa;
        form.idAluno.value = idAluno;
        form.isUpdate.value = '0';
        form.submit();
    }

    function editar(form, idDefesa, idAluno, conceitoDefesa) {

        if (conceitoDefesa != '')
            alert('Defesa não pode ser alterada, pois a mesma já foi conceituada.');
        else
        {
            form.task.value = 'detalhesDefesa';
            form.idDefesa.value = idDefesa;
            form.idAluno.value = idAluno;
            form.isUpdate.value = '1';
            form.submit();
        }
    }
    
    function excluir(form, idDefesa, idAluno, idBanca, statusBanca) {

        if (statusBanca == '0' || statusBanca == '1')
            alert('Defesa não pode ser excluída, pois a mesma já possui banca avaliada pelo coordenador.');
        else
        {
            var resposta = window.confirm("Confirmar exclusão da Defesa?");

            if(resposta) {
                form.task.value = 'deletarDefesa';
                form.idDefesa.value = idDefesa;
                form.idAluno.value = idAluno;
                form.idBanca.value = idBanca;
                form.submit();
            }
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

<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_defesasorientador&view=listadefesas">

	<div id="toolbar-box">
        <div class="m">
            <div class="toolbar-list" id="toolbar">
                <div class="cpanel2">
                    <div class="icon" id="toolbar-back">
                        <a href="index.php?option=com_defesasorientador">
                        <span class="icon-32-back"></span>Voltar</a>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="pagetitle icon-48-groups"><h2>Lista de Defesas</h2></div>
        </div>
    </div>

	<!-- FILTRO DA BUSCA -->
	<fieldset>
    	<legend>Filtros para consulta</legend>
        </br>
        <table width="100%">
            <tr>
                <td>Nome Aluno</td>
				<!--<td>Título Defesa</td> -->
                <td>Curso</td>
				<td>Tipo Defesa</td>
				<td>Data Defesa</td>
				<td>Local Defesa</td>
            </tr>
            <tr>                
                <td ><input id="buscaNomeAluno" name="buscaNomeAluno" type="text" size="25" value="<?php echo $nome_aluno;?>"/></td>
                <!--<td ><input id="buscaTituloDefesa" name="buscaTituloDefesa" type="text" value="<?php echo $titulo_defesa;?>"/></td> -->
                
                <td>
                <select name="buscaCurso">
                    <option value="0" <?php if($curso == 0) echo 'SELECTED';?>>Todos</option>
                    <option value="1" <?php if($curso == 1) echo 'SELECTED';?>>Mestrado</option>
                    <option value="2" <?php if($curso == 2) echo 'SELECTED';?>>Doutorado</option>
                    <option value="3" <?php if($curso == 3) echo 'SELECTED';?>>Especial</option>
                </select>
                </td>

                <td>
                <select name="buscaTipoDefesa">
                    <option value="" <?php if($tipo_defesa == '') echo 'SELECTED';?>>Todos</option>
					<option value="Q1" <?php if($tipo_defesa == 'Q1') echo 'SELECTED';?>>Qualificação 1</option>
                    <option value="Q2" <?php if($tipo_defesa == 'Q2') echo 'SELECTED';?>>Qualificação 2</option>
                    <option value="D" <?php if($tipo_defesa == 'D') echo 'SELECTED';?>>Dissertação</option>
                    <option value="T" <?php if($tipo_defesa == 'T') echo 'SELECTED';?>>Tese</option>
                </select>
                </td>
                
                
                <td><input id="buscaDataDefesa" name="buscaDataDefesa" type="text" size="10" maxlength="10" value="<?php echo $data_defesa;?>"/></td>
                <td><input id="buscaLocalDefesa" name="buscaLocalDefesa" type="text" size="20" value="<?php echo $local_defesa;?>"/></td>
            </tr>
			
			<tr>
				<td align="right">
					<button type="submit" value="Buscar" class="btn btn-primary">
						<i class="icone-search icone-white"></i> Buscar
					</button>
				</td>		
			</tr>
            
		</table>
	</fieldset>

    <table class="table table-striped" id="tablesorter-imasters">
        <thead>
          <tr>
            <!--<th></th> -->
            <th>Nome Aluno</th>
            <!--<th>Título Defesa</th> -->
            <th>Curso</th>
            <th>Tipo</th>
            <th>Data Defesa</th>
            <th>Conceito</th>
            <th>Local Defesa</th>
            <th colspan="3">Opções</th>
          </tr>
        </thead>
         
 		<tbody>
		<?php

        $arrayCurso = array (1 => "Mestrado", 2 => "Doutorado", 3 => "Especial");
		
		$arrayTipoDefesa = array('Q1' => "Qualificação 1", 'Q2' => "Qualificação 2", 'D' => "Dissertação", 'T' => "Tese");
		
		if(isset ($defesas)){
			foreach($defesas as $defesa) { ?>

			<tr>
				<td><?php echo $defesa->nome_aluno;?></td>
                <!--<td><?php echo $defesa->titulo_defesa;?></td> -->
                <td><img border='0' src='components/com_portalsecretaria/images/<?php echo $arrayCurso[$defesa->curso];?>.gif' title='<?php echo $arrayCurso[$defesa->curso];?>'></td>
                <td title='<?php echo $arrayTipoDefesa[$defesa->tipoDefesa];?>'><?php echo $defesa->tipoDefesa;?></td>
				<td><?php echo $defesa->data_defesa;?></td>
                <td><?php echo $defesa->conceito_defesa;?></td>
                <td width="20%"><?php echo $defesa->local_defesa;?></td>
                <td width="1%"><a href="javascript:visualizar(document.form, <?php echo $defesa->idDefesa;?>, <?php echo $defesa->idAluno;?>)" title="Visualizar"><span class="label label-view"><i class="icone-user icone-white"></i></span></a></td>
                <td width="1%"><a href="javascript:editar(document.form, <?php echo $defesa->idDefesa;?>, <?php echo $defesa->idAluno;?>, '<?php echo $defesa->conceito_defesa;?>')" title="Editar"><span class="label label-info"><i class="icone-edit icone-white"></i></span></a></td>
                <td width="1%"><a href="javascript:excluir(document.form, <?php echo $defesa->idDefesa;?>, <?php echo $defesa->idAluno;?>, <?php echo $defesa->banca_id;?>, '<?php echo $defesa->status_banca;?>')" title="Excluir"><span class="label label-important"><i class="icone-remove icone-white"></i></span></a></td>
			</tr>
		
		<?php } 
		}?>
	</tbody>
 </table>     
 <br />
 
 <span class="label label-inverse">Total de Defesas: <?php echo sizeof($defesas);?></span>
 
 <input name='task' type='hidden' value='display' />
 <input name='idDefesa' type='hidden' value='' />
 <input name='idAluno' type='hidden' value='' />
 <input name='idBanca' type='hidden' value='' />
 <input name='isUpdate' type='hidden' value='' />
     
</form>

<?php
// No direct access to this file

$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
$document->addScript("includes/js/joomla.javascript.js");

$alunos = $this->alunos;

$nome_aluno = $this->nome_aluno;
$matricula = $this->matricula;
$curso = $this->curso;
$linha_pesquisa = $this->linha_pesquisa;
$nome_orientador = $this->nome_orientador;

if(($this->nome_aluno == NULL) AND 
   ($this->matricula == NULL) AND
   ($this->curso == NULL) AND 
   ($this->linha_pesquisa == NULL) AND
   ($this->nome_orientador == NULL)){
        $nome_aluno = '';
        $matricula = '';
        $curso = 0;
        $linha_pesquisa = 0;
        $nome_orientador = '';
}

?>

<script language="JavaScript">
        function detalhes(form){        
           var idAlunoSelecionado = 0;
           
          for(i = 0;i < form.idAlunoSelec.length; i++)
            if(form.idAlunoSelec[i].checked){
              idAlunoSelecionado = form.idAlunoSelec[i].value;	
            }
				
           if(idAlunoSelecionado > 0){
              form.task.value = 'detalhesAluno';
              form.idAluno.value = idAlunoSelecionado;
              form.submit();
           } else {
           	  alert('Você deve selecionar um aluno para visualiza\xE7\xE3o.')
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

    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controledefesas&view=listaalunos">
    
		<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-preview">
                    <a href="javascript:detalhes(document.form)" class="toolbar">
                    <span class="icon-32-preview"></span>Detalhes</a>
                </div>                
                <div class="icon" id="toolbar-back">
                    <a href="index.php?option=com_controledefesas">
                    <span class="icon-32-back"></span>Voltar</a>
                </div>
            </div>
            <div class="clr"></div>
            </div>
            
            <div class="pagetitle icon-48-groups"><h2>Lista de Alunos</h2></div>
        </div></div>

		<!-- FILTRO DA BUSCA -->
		<fieldset>
        	<legend>Filtros para consulta</legend>
            </br>
            <table width="100%">
                <tr>
                    <td>Nome Aluno</td>
					<td>Matrícula</td>
                    <td>Nome Orientador</td>
					<td>Curso</td>
                    <td>Linha de Pesquisa</td>					
                </tr>
                <tr>                    
                    <td ><input id="buscaNomeAluno" name="buscaNomeAluno" type="text" size="20"value="<?php echo $nome_aluno;?>"/></td>
                    <td ><input id="buscaMatricula" name="buscaMatricula" type="text" size="10" value="<?php echo $matricula;?>"/></td>
                    <td ><input id="buscaNomeOrientaddor" name="buscaNomeOrientaddor" type="text" size="20"value="<?php echo $nome_orientador;?>"/></td>

                    <td>
                    <select name="buscaCurso">
                        <option value="0" <?php if($curso == 0) echo 'SELECTED';?>>Todos</option>
                        <option value="1" <?php if($curso == 1) echo 'SELECTED';?>>Mestrado</option>
                        <option value="2" <?php if($curso == 2) echo 'SELECTED';?>>Doutorado</option>
                        <option value="3" <?php if($curso == 3) echo 'SELECTED';?>>Especial</option>
                    </select>
                    </td>                    
		
					          <td>
                    <select name="buscaLinhaPesquisa">	
						        <option value="0" <?php if($linha_pesquisa == 0) echo 'SELECTED';?>>Todos</option>
                        <option value="1" <?php if($linha_pesquisa == 1) echo 'SELECTED';?>>BD e RI</option>
                        <option value="2" <?php if($linha_pesquisa == 2) echo 'SELECTED';?>>SistEmb & EngSW</option>
                        <option value="3" <?php if($linha_pesquisa == 3) echo 'SELECTED';?>>Int. Art.</option>
                        <option value="4" <?php if($linha_pesquisa == 4) echo 'SELECTED';?>>Visão Comp. e Rob.</option>
                        <option value="5" <?php if($linha_pesquisa == 5) echo 'SELECTED';?>>Redes e Telec.</option>
                        <option value="6" <?php if($linha_pesquisa == 6) echo 'SELECTED';?>>Ot., Alg. e Complex.</option>
                    </select>
                    </td>
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
                <th></th>
                <th>Nome Aluno</th>
                <th>Matrícula</th>
                <th>Orientador</th>
                <th>Curso</th>
                <th>Linha de Pesquisa</th>
              </tr>
            </thead>
             
     		<tbody>
			<?php
			$arrayImgCurso = array (1 => "mestrado", 2 => "doutorado", 3 => "especial");
            $arrayCurso = array (1 => "Mestrado", 2 => "Doutorado", 3 => "Especial");
			$arrayImgLinhaPesquisa = array (1 => "bd_ri",2 => "se_es",3 => "ia",4 => "visao",5 => "redes",6 => "otimizacao");
            $arrayLinhaPesquisa = array (1 => "BD e RI",2 => "SistEmb & EngSW",3 => "Int. Art.",4 => "Visão Comp. e Rob.",5 => "Redes e Telec.",6 => "Ot., Alg. e Complex.");
			
			if(isset ($alunos)){
				foreach($alunos as $aluno ) { ?>
	
				<tr>
					<td width='15'><input type="radio" name="idAlunoSelec" value="<?php echo $aluno->idAluno;?>"></td>
					<td><?php echo $aluno->nome_aluno;?></td>
                    <td><?php echo $aluno->matricula;?></td>
                    <td><?php echo $aluno->nome_orientador;?></td>
					<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $arrayImgCurso[$aluno->curso];?>.gif' title='<?php echo $arrayCurso[$aluno->curso];?>'></td>
					<td><img border='0' src='components/com_portalsecretaria/images/<?php echo $arrayImgLinhaPesquisa[$aluno->linha_pesquisa];?>.gif' title='<?php echo $arrayLinhaPesquisa[$aluno->linha_pesquisa];?>'></td>
				</tr>
			
			<?php } 
			}?>
		</tbody>
     </table>     
     <br />
     
     <span class="label label-inverse">Total de Alunos: <?php echo sizeof($alunos);?></span>
     
     <input name='task' type='hidden' value='display' />
     <input name='idAlunoSelec' type='hidden' value='0' />
     <input name='idAluno' type='hidden' value='' />

</form>

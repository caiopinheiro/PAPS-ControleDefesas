<?php
// No direct access to this file

$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
$document->addScript("includes/js/joomla.javascript.js");



$defesas = $this->defesas;
$alunos = $this->aluno;

$status_bancas = $this->status_bancas;
$nome_aluno = $this->nome_aluno;
$tipo_banca = $this->tipo_banca;
$nome_orientador = $this->nome_orientador;
$linha_pesquisa = $this->linha_pesquisa;
$tipo_curso = $this->tipo_curso;



if(($this->status_bancas == NULL) AND 
   ($this->nome_aluno == NULL) AND
   ($this->tipo_banca == NULL) AND 
   ($this->nome_orientador == NULL) AND
   ($this->linha_pesquisa == NULL)){
	$status_bancas = 1;
	$nome_aluno = '';
    $tipo_curso = 0;
	$tipo_banca = 4;
	$nome_orientador = '';
	$linha_pesquisa = 0;
}

$sucesso = $this->status;

if($sucesso){
	JFactory :: getApplication()->enqueueMessage(JText :: _('Opera&#231;&#227;o realizada com sucesso.'));
}
else if($sucesso != true AND $sucesso != NULL) {
	JError :: raiseWarning(100, 'ERRO: Opera&#231;&#227;o Falhou.');
}


function defesa($defesa){

    if ($defesa->curso == 1 && $defesa->QTD_DEFESA == 0){ 
        return 'Q1';
    }
    else if ($defesa->curso == 1 && $defesa->QTD_DEFESA == 1){
        return 'D';
    }
    else if ($defesa->curso == 2 && $defesa->QTD_DEFESA == 0){
        return 'Q1';
    }
    else if ($defesa->curso == 2 && $defesa->QTD_DEFESA == 1){
        return 'Q2';
    }
    else if ($defesa->curso == 2 && $defesa->QTD_DEFESA == 2){
        return 'T';
    }

}


function data_defesa($defesa){
    if ($defesa->curso == 1 && $defesa->QTD_DEFESA == 0){ 
        return date ('d/m/Y',(strtotime('+1 years',strtotime($defesa->anoingresso))));
    }
    else if ($defesa->curso == 1 && $defesa->QTD_DEFESA == 1){
        return date ('d/m/Y',(strtotime('+2 years',strtotime($defesa->anoingresso))));
    }
    else if ($defesa->curso == 2 && $defesa->QTD_DEFESA == 0){
        return date ('d/m/Y',(strtotime('+2 years',strtotime($defesa->anoingresso))));
    }
    else if ($defesa->curso == 2 && $defesa->QTD_DEFESA == 1){
        return date ('d/m/Y',(strtotime('+3 years',strtotime($defesa->anoingresso))));
    }
    else if ($defesa->curso == 2 && $defesa->QTD_DEFESA == 2){
        return date ('d/m/Y',(strtotime('+4 years',strtotime($defesa->anoingresso))));
    }



}


?>

<script language="JavaScript">
        function enviarlembrete(form){        
           var idAlunoSelec = 0;
           var exame = 0;

           
           
           for(i = 0;i < form.idAlunoSelec.length ;i++)
                if(form.idAlunoSelec[i].checked){
                    idAlunoSelec = form.idAlunoSelec[i].value;
                    exame = form.exame[i].value;
				}
				
           if(idAlunoSelec > 0){
				form.task.value = 'enviaremail'; 
                form.exame.value = exame;
                form.idAluno.value = idAlunoSelec;
				//form.submit();
                location.href='index.php?option=com_controledefesas&task=sendNotification&idAluno='+idAlunoSelec+'&exame='+exame+'&lang=pt-br';
           } else {
           	alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o.')
           }
        }


        function buscar(form){
            form.task.value = null; 
            form.submit();
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

    <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controledefesas&view=listapendente" >
		<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">

                <div class="icon" id="toolbar-preview">
                    <a href="javascript:enviarlembrete(document.form)" class="toolbar" title = 
                    "Funcionalidade que permite:
                    -Enviar Email de aviso informando que já passou o período ideal para realizar a Defesa">
                    <span class="icon-32-apply"></span>Enviar<br>Lembrete</a>
                </div>


                
                <div class="icon" id="toolbar-back">
                    <a href="index.php?option=com_controledefesas">
                    <span class="icon-32-back"></span>Voltar</a>
                </div>
            </div>
            <div class="clr"></div>
            </div>
               
            <div class="pagetitle icon-48-groups"><h2>Lista de Defesas Pendentes</h2></div>
        </div></div>
        
		<!-- FILTRO DA BUSCA -->

		<fieldset>
        	<legend>Filtros para consulta</legend>
            </br>
            <table width="100%">
               <tr>
                    <td> Status Exame </td>
					<td>Nome Aluno</td>
                    <td>Tipo Curso </td>
					
                </tr>

                <tr>

                    <td>
                    <select id="buscaStatusBanca" name="buscaStatusBanca">    
                        <option value="1" <?php if($status_bancas == 1) echo 'SELECTED';?>>Ultrapassou o Prazo</option>
                        <option value="2" <?php if($status_bancas == 2) echo 'SELECTED';?>>Dentro do Prazo</option>
                        <option value="3" <?php if($status_bancas == 3) echo 'SELECTED';?>> Mostrar Todos</option>
                        
                    </select>
                    </td>

                    <td ><input id="buscaNomeAluno" name="buscaNomeAluno" type="text" value="<?php echo $nome_aluno;?>"/></td>


                    <td>
                    <select id="tipo_curso" name="tipo_curso" >   
                        <option value="0" <?php  if($tipo_curso == 0) echo 'SELECTED';?>>Todos</option>
                        <option value="1" <?php if($tipo_curso == 1) echo 'SELECTED';?>>Mestrado</option>
                        <option value="2" <?php if($tipo_curso == 2) echo 'SELECTED';?>>Doutorado</option>
                     
                    </select>
                    </td>

                </tr>

                <tr>
                    <td>Exame Pendente</td>
                    <td>Nome Orientador</td>
                    <td>Linha de Pesquisa</td>
                </tr>


                <tr>

                    
                    <td>
                    <select id= "tipoBanca" name="tipoBanca">	
						<option value="4" <?php if($tipo_banca == 4) echo 'SELECTED';?>>Mostrar Todos</option>
                        <option value="1" <?php if($tipo_banca == 1) echo 'SELECTED';?>>Tese</option>
                        <option value="0" <?php if($tipo_banca == 0) echo 'SELECTED';?>>Dissertação</option>
                        <option value="2" <?php if($tipo_banca == 2) echo 'SELECTED';?>>Qualificação 1 </option>
                        <option value="3" <?php if($tipo_banca == 3) echo 'SELECTED';?>>Qualificação 2</option>

                    </select>
                    </td>
                    
                    
                    <td><input id="buscaNomeOrientador" name="buscaNomeOrientador" type="text" value="<?php echo $nome_orientador;?>"/></td>
		
					<td>
                    <select name="linhaPesquisa">	
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
						<button type="button"  value="Buscar" onClick = "buscar(form);" class="btn btn-primary">
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
                <th>Aluno</th>
                <th>Data Limite</th>
                <th>Fase </th>
                <th>Curso </th>    
                <th>Orientador</th>
                <th>Linha de Pesquisa</th>
              </tr>

            </thead>
             
     		<tbody>
			<?php
            $status = array (0 => "Sem Conceito", 1 => "Conceituada");
			
            $statusImg = array (0 => "desativar.png" ,1 => "sim.gif");
			
			$linhaPesquisa = array (1 => "bd_ri",2 => "se_es",3 => "ia",4 => "visao",5 => "redes",6 => "otimizacao");
			
			
			

			if(isset ($defesas)){


                
				foreach($defesas as $defesa ) { 

               $mostrar = 1;
               //$mostrar = verificardefesa($defesa);

                if ($mostrar == 1) {

                    ?>


    
    <tr>
        <td> <input type="radio" name="idAlunoSelec" value="<?php echo $defesa->aluno_id;?>"> </td>
        <td ><?php echo $defesa->nome; ?></td>
        <td >  <?php echo data_defesa($defesa);  ?> </td>
        <td ><?php $exame = defesa($defesa); echo $exame;  ?>
            <input type="hidden" name="exame" value="<?php echo $exame;?>">
        </td>
        <td><?php if ($defesa->curso == 1) echo 'Mestrado'; else if($defesa->curso == 2) echo 'Doutorado'; else echo 'Especial'?></td>
        <td><?php echo $defesa->nomeProfessor;?></td>
        <td><img border='0' src='components/com_portalsecretaria/images/<?php echo $linhaPesquisa[$defesa->area];?>.gif'></td>
    </tr>



			
			<?php  } 
            }
		}?>
		</tbody>
     </table>     
     <br />
     
     <span class="label label-inverse">Total de Bancas: <?php echo sizeof($defesas);?></span>
     
     <input name='task' type='hidden' value='display' />
     <input name='idDefesaSelec' type='hidden' value='0' />
     <input name='idAlunoSelec' type='hidden' value='0'/>
     <input name='idDefesa' type='hidden' value=''/>
     <input name= 'idAluno' type='hidden' value =''/>
     <input name= 'exame' type='hidden' value =''/>
   

     
</form>

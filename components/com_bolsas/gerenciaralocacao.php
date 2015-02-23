<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
<link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/estilo.css">

<?php // LISTAGEM DE ALOCAÇÕES
function listarAlocacoes() {
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); 
	
	$sql = "SELECT * FROM #__bolsas";
    $database->setQuery($sql);
    $gerenciarBolsas = $database->loadObjectList(); ?>  
    
    <script type="text/javascript" src="components/com_bolsas/assets/js/colapsavel/colapsavel.js"></script>   	
    
	<form method="post" name="form" action="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>">

        <!-- BARRA DE FERRAMENTAS -->
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <!--<div class="icon" id="toolbar-new">
                    <a href="javascript:document.form.task.value='addAlocacao';document.form.submit()">
                    <span class="icon-32-new"></span><?php echo JText::_( 'Novo' ); ?></a>
                </div>-->
                
                <div class="icon" id="toolbar-back">
                    <a href="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
    
            <div class="clr"></div>
            </div>
            <div class="pagetitle icon-48-search2"><h2>Bolsas de Pesquisa</h2></div>
            </div>
        </div>
        
        <span class="label label-success">M</span> Mestrado | <span class="label label-info">D</span> Doutorado</li>
        
        <dl id="jfaq">
        <?php 
		foreach ($gerenciarBolsas as $bolsa) { 
		
			$sql = "SELECT BA.idAluno, BA.idBolsa, BA.dataInicio, BA.dataTermino, A.nome, A.anoingresso, A.area, BA.status
					FROM #__bolsas_aloc as ba, #__aluno as A
					WHERE BA.idAluno = A.id
					AND BA.idBolsa = '$bolsa->id' AND BA.status = 'Ativa'";
			$database->setQuery($sql);
			$gerenciarAloc = $database->loadObjectList(); 
			
			// VALOR TOTAL DA BOLSA
			$valorUnitario = $bolsa->valorUnitario;
			$qtdeBolsas = $bolsa->quantidade;
			$valorTotal = $qtdeBolsas * $valorUnitario; ?>
            
			<dt>
            	<table class="table-collapse">
                	<thead class="head-two">
                    <tr>
                    	<td width="30%"><i class="icone icone-th"></i> Financiador: <?php echo $bolsa->agencia; ?></td>
                        <td width="20%">Total de Bolsas: <?php echo $bolsa->quantidade; ?></td>
                        <td width="15%">Alocadas: <?php echo calcularBolsasDisp($bolsa->id); ?></td>
                        <td width="50%">Valor da Bolsa: <?php echo 'R$ '.moedaBr($bolsa->valorUnitario); ?></td>
                        <td>
                        	<?php if ($bolsa->categoria == 'Mestrado')
	                        		echo '<span class="label label-success">M</span>';
								  elseif ($bolsa->categoria == 'Doutorado')
								  	echo '<span class="label label-info">D</span>';								  
							?>
                        </td>
                    </tr>
					</th>
                </table>
			</dt>
            
            <dd>
            <table class="table">
                <thead class="bold">
                    <tr>
                    	<td>Bolsista</td>
                        <td>Ingresso</td>
                        <td>Linha de Pesquisa</td>
                        <td>Início</td>
                        <td>Término</td>
						<td>Status</td>
                        <td><a href="javascript:document.form.task.value='alocarBolsista';document.form.idBolsa.value='<?php echo $bolsa->id; ?>';document.form.submit()" title="Alocar Bolsista">
                        	<span class="label label-info"><i class="icone icone-plus-sign icone-white"></i></span></a></td>
                    </tr>
				</thead>
                <tbody>
				<?php foreach ($gerenciarAloc as $alocacao) { ?>
                
                <tr>
                    <td><?php echo $alocacao->nome; ?></td>
                    <td><?php echo dataBr($alocacao->anoingresso); ?></td>
                    <td><?php echo linhaPesquisa($alocacao->area); ?></td>
                    <td><?php echo dataBr($alocacao->dataInicio); ?></td>
                    <td><?php echo dataBr($alocacao->dataTermino); ?></td>
					<td><?php echo $alocacao->status; ?></td>
                    <td><a href="javascript:document.form.task.value='desalocarBolsista';document.form.idBolsista.value='<?php echo $alocacao->idAluno; ?>';document.form.submit()" title="Desalocar Bolsista">
                    	<span class="label label-important"><i class="icone icone-minus-sign icone-white"></i></span></a></td>                    
                </tr>
                
                <?php } ?>
            	</tbody>
			</table>
			</dd>

        <?php } ?>
        </dl>
        
        <br />
        <input name='task' type='hidden' value='gerenciarAlocacao' />
        <input name='idBolsista' type='hidden' value='' />        
        <input name='idBolsa' type='hidden' value='' />
    </form>
        
<?php } ?>


<?php // CADASTRAR ALOCAÇÃO DE BOLSISTA : TELA
function telaCadastrarAlocacao($bolsa) { 
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
    <!-- SCRIPTS -->   
	<script type="text/javascript">
		function ValidateForm(form) {
           var erro = false;
		   
		   form.submeter.click();
		}
	</script>
        
 	<!-- AUTOCOMPLETE :: DEVE FICAR ANTES DO CALENDÁRIO -->
	<script src="components/com_bolsas/assets/js/autocomplete/jquery.js"></script>    
	<script src="components/com_bolsas/assets/js/autocomplete/jquery-ui-autocomplete.js"></script>
	<script src="components/com_bolsas/assets/js/autocomplete/jquery.select-to-autocomplete.min.js"></script>
	<script type="text/javascript" charset="utf-8">
	  (function($){
	    $(function(){
	      $('select').selectToAutocomplete();
	    });
	  })(jQuery);
	</script>    
        
    <!-- MÁSCARAS PARA DATAS -->
	<script type="text/javascript" src="components/com_bolsas/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
        $(function($){
            $("#dataInicio").mask("99/99/9999");
            $("#dataTermino").mask("99/99/9999");
        });
    </script>
    
    <!-- ALERTA DE DATAS -->            
	<script language="javascript">
        $(function(){
            $("#dataInicio").blur(function(){
            var data_min = $("#data_inicio").val();
            var data = $("#dataInicio").val();
            
            var compara1 = parseInt(data_min.split("/")[2].toString() + data_min.split("/")[1].toString() + data_min.split("/")[0].toString());
            
            var compara2 = parseInt(data.split("/")[2].toString() + data.split("/")[1].toString() + data.split("/")[0].toString());

            if (compara1 <= compara2) {
                $('#resultado1').html("");
				$('#resultado1').css("background","#fff");
            } else {
                $('#resultado1').html("Data de Início fora do período definido!");
				$('#resultado1').css("background","#f2dede");
				$('#resultado1').css("color","#b94a48");
				$('#resultado1').css("border-radius","5px");
            }
            
            return false;
            })
        })
    </script>    
    
    <script language="javascript">			
        $(function(){
            $("#dataTermino").blur(function(){
            var data_max = $("#data_termino").val();
            var data = $("#dataTermino").val();
            
            var compara1 = parseInt(data_max.split("/")[2].toString() + data_max.split("/")[1].toString() + data_max.split("/")[0].toString());
            
            var compara2 = parseInt(data.split("/")[2].toString() + data.split("/")[1].toString() + data.split("/")[0].toString());
            
            if (compara1 >= compara2) {
                $('#resultado2').html("");
				$('#resultado2').css("background","#fff");								
            } else {
                $('#resultado2').html("Previsão de Término fora do período definido!");
				$('#resultado2').css("background","#f2dede");
				$('#resultado2').css("color","#b94a48");
				$('#resultado2').css("border-radius","5px");
            }
            
            return false;
            })
        })
    </script>
        
	<style> 
	.resultado{ padding:5px 10px; font-size:13px; color:#F00; } 
    </style>
    
	<form method="post" name="formCadAlocacao" action="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>" class="form-horizontal">
         
        <!-- BARRA DE FERRAMENTAS -->          
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-save">
                    <a href="javascript: ValidateForm(document.formCadAlocacao)" class="toolbar">
                    <span class="icon-32-save"></span><?php echo JText::_( 'Salvar' ); ?></a>
                </div>
                
                <div class="icon" id="toolbar-cancel">
                    <a href="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>&task=gerenciarAlocacao">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
            
            <div class="clr"></div>            
            </div>
        
            <div class="pagetitle icon-48-contact-categories">
                <h2>Alocação de Bolsista</h2>
            </div>
        </div></div>
    
        <span class="label label-info">Informações</span>
        <ul>
            <li>Os campos com <span class="obrigatory"> * </span> são de preenchimento obrigatório</li>
            <li>Apenas alunos da <b>mesma categoria</b> da bolsa de pesquisa são listados</li>
            <li>A data de término para o Aluno deve ser <b>menor ou igual</b> a data de término da bolsa de pesquisa</li>
        </ul>
        <hr />
        
        <br />
        <h2>Dados da Bolsa de Pesquisa</h2>        
        <table class="table table-bordered">
            <tr class="info bold">
                <td>Financiador</td>
                <td>Edital</td>
                <td>Data de Início</td>
                <td>Data de Término</td>
                <td>Valor da Bolsa</td>
                <td>Categoria</td>
            </tr>
            <tr>
                <td><?php echo $bolsa->agencia; ?></td>
                <td><?php echo $bolsa->edital; ?></td>
                <td><?php echo dataBr($bolsa->dataInicio); ?></td>
                <td><?php if ($bolsa->dataTermino == '0000-00-00') echo 'Não há';
                          else echo dataBr($bolsa->dataTermino); ?></td>
                <td><?php echo 'R$ '.moedaBr($bolsa->valorUnitario); ?></td>
                <td class="upper">
	                <?php if ($bolsa->categoria == 'Mestrado')
							echo '<span class="label label-success">Mestrado</span>';
						  elseif ($bolsa->categoria == 'doutorado')
							echo '<span class="label label-info">Doutorado</span>';								  
					?></td>
            </tr>
        </table>
           
        <table class="table-form"> 
            <tbody>
            	<tr>
                	<td>Bolsista <span class="obrigatory">*</span></td>
                    <td colspan="2">
    					<select name="bolsista" class="selector" autocorrect="off" autocomplete="off" required="required">
                    	<option value=""></option>
                        <?php
                        	$alunosDisp = alunosDisponiveis($bolsa->categoria);
                        	foreach($alunosDisp as $alunosD) { ?> 
                         		<option value="<?php echo $alunosD->id; ?>"><?php echo $alunosD->nome; ?></option>				
						<?php } ?>
                    </select></td>
                </tr>
		       	<tr>
                	<td>Data Início <span class="obrigatory">*</span></td>
                    <td><input type="text" name="dataInicio" id="dataInicio" required="required" class="imgcalendar" /></td>
                    <td><div id="resultado1" class="resultado"></div></td>
                </tr>
                <tr>
                	<td>Previsão de Término <span class="obrigatory">*</span></td>
                    <td><input type="text" name="dataTermino" id="dataTermino" required="required" class="imgcalendar" /></td>
                    <td><div id="resultado2" class="resultado"></div></td>
                </tr>
            </tbody>
        </table>
        
        <input type="hidden" name="data_inicio" id="data_inicio" value="<?php echo dataBr($bolsa->dataInicio); ?>" />
        <input type="hidden" name="data_termino" id="data_termino" value="<?php echo dataBr($bolsa->dataTermino); ?>" />
        
		<input type="submit" name="submeter" value="Submeter" style="display:none" />
        <input type="hidden" name="idBolsa" value="<?php echo $bolsa->id; ?>" />
		<input type='hidden' name='task' value='cadAlocacao' />
    </form> 

<?php } ?>


<?php // CADASTRAR ALOCAÇÃO DE BOLSISTA : SQL
function salvarAlocacao($idBolsa) {
	$database =& JFactory::getDBO();

	$idAluno = JRequest::getVar('bolsista');
	$dataInicio = JRequest::getVar('dataInicio');
	$dataTermino = JRequest::getVar('dataTermino');

	$dataInicio = dataSql($dataInicio);
	$dataTermino = dataSql($dataTermino);

	$sql = "INSERT INTO #__bolsas_aloc (id, idAluno, idBolsa, dataInicio, dataTermino, status) 	
			VALUES ('','$idAluno', '$idBolsa', '$dataInicio', '$dataTermino', 'Ativa')";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	if($funcionou){
		echo '<div class="alert alert-success">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>CADASTRO :</b> Alocação de bolsista realizada com sucesso!
			  </div>';
	} else {
		echo '<div class="alert alert-error">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>CADASTRO :</b> Não foi possível alocar o bolsista, tente novamente!
			  </div>';
	}
	
	listarAlocacoes();
}
?>


<?php // DESALOCAR BOLSISTA : TELA
function telaDesalocarBolsista($bolsista) { 
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
    <!-- SCRIPTS -->    
    <script type="text/javascript">
		function ValidateForm(form) {        
           var erro = false;
		   
		   form.submeter.click();
		}
	</script>
    
	<form method="post" name="formDesalocacao" action="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>">
         
        <!-- BARRA DE FERRAMENTAS -->          
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-save">
                    <a href="javascript: ValidateForm(document.formDesalocacao)" class="toolbar">
                    <span class="icon-32-save"></span><?php echo JText::_( 'Salvar' ); ?></a>
                </div>
                
                <div class="icon" id="toolbar-cancel">
                    <a href="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>&task=gerenciarAlocacao">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
            
            <div class="clr"></div>            
            </div>
        
            <div class="pagetitle icon-48-desaloc">
                <h2>Desalocação de Bolsista</h2>
            </div>
        </div></div>
    
        <span class="label label-info">Informações</span>
        <ul>
            <li>Os campos com <span class="obrigatory"> * </span> são de preenchimento obrigatório</li>
            <li>Verifique se a <b>Data de Término</b> corresponde a <b>Previsão de Término</b></li>
        </ul>
        <hr />
        
        <br />
        
        <h2>Dados da Bolsista</h2>        
        <table class="table table-bordered">
            <tr class="info bold">
                <td>Financiador</td>
                <td>Bolsista</td>
                <td>Linha de Pesquisa</td>
                <td>Data de Início</td>
                <td>Previsão de Término</td>
                <td>Categoria</td>
            </tr>
            <tr>
                <td><?php echo $bolsista->agencia; ?></td>
                <td><?php echo $bolsista->nome; ?></td>
                <td><?php echo linhaPesquisa($bolsista->area); ?></td>
                <td><?php echo dataBr($bolsista->dataInicio); ?></td>
                <td><?php if ($bolsista->dataTermino == '0000-00-00') echo 'Não há';
                          else echo dataBr($bolsista->dataTermino); ?></td>
                <td class="upper"><span class="label label-success"><?php echo $bolsista->categoria; ?></span></td>
            </tr>
        </table>
        
        <table class="table-form"> 
            <tbody>
            	<tr>
                	<td>Data de Término <span class="obrigatory">*</span></td>
                	<td><input type="date" name="dataTermino" value="<?php echo date("Y-m-d"); ?>" required="required" autofocus="autofocus" /></td>
                </tr>
            	<tr>
                	<td>Motivo <span class="obrigatory">*</span></td>
                    <td><select name="motivo" required="required">
                    	<option value="">Selecione um motivo</option>
                        <option value="1">Prazo Encerrado</option>
                        <option value="2">Baixo Rendimento</option>
                        <option value="3">Outra Forma de Renda</option>
                    </select></td>
                </tr>
		       	<tr>
                	<td>Justificativa</td>
                    <td><textarea name="justificativa" rows="5" cols="50"></textarea></td>
                </tr>
            </tbody>
        </table>  
        
		<input type="submit" name="submeter" value="Submeter" style="display:none" />
        <input type="hidden" name="idBolsaAloc" value="<?php echo $bolsista->id; ?>" />
        <input type="hidden" name="idBolsa" value="<?php echo $bolsista->idBolsa; ?>" />
        <input type="hidden" name="idAluno" value="<?php echo $bolsista->idAluno; ?>" />
        <input type="hidden" name="dataInicio" value="<?php echo $bolsista->dataInicio; ?>" />
		<input type='hidden' name='task' value='cadDesalocacao' />
    </form> 

<?php } ?>


<?php // DESALOCAR BOLSISTA : SQL
function salvarDesalocacao($idBolsaAloc, $idBolsa, $idAluno, $dataInicio, $dataTermino) {
	$database =& JFactory::getDBO();
	
	$motivo = JRequest::getVar('motivo');
	$justificativa = JRequest::getVar('justificativa');
	$dataInicio = JRequest::getVar('dataInicio');
	$dataTermino = JRequest::getVar('dataTermino');
	
	$dataInicio = dataSql($dataInicio);
	$dataTermino = dataSql($dataTermino);	

	/* CADASTRO NA TABELA J17_BOLSAS_ALOC_HISTORICO */
	$sql1 = "UPDATE #__bolsas_aloc SET status = 'Finalizada', dataTermino = '$dataTermino', motivo = '$motivo' , justificativa = '$justificativa' WHERE id = $idBolsaAloc";
	$database->setQuery($sql1);
	$funcionou1 = $database->Query();
	
	if($funcionou1){
		echo '<div class="alert alert-success">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>DESALOCAR BOLSISTA :</b> Operação realizada com sucesso!
			  </div>';
	} else {
		echo '<div class="alert alert-error">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>DESALOCAR BOLSISTA :</b> Não foi possível realizar a operação, tente novamente!
			  </div>';
	}
	
	listarAlocacoes();
}
?>


<?php // HISTÓRICO DE ALOCAÇÃO DE BOLSISTAS
function listarHistorico($agencia, $categoria, $area, $ano) {
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
	
	$where = array();

	if($agencia){ $where[] = "B.agencia LIKE '$agencia'"; }

    if($categoria){ $where[] = "B.categoria = '$categoria'"; }

    if($area){ $where[] = "A.area = '$area'"; }	
	
	if($ano){ $where[] = "YEAR(BA.dataInicio) <= '$ano' AND YEAR(BA.dataTermino) >= '$ano'"; }	
	
	$sql = "SELECT BA.id, BA.idAluno, BA.idBolsa, BA.status, BA.dataInicio, BA.motivo, BA.justificativa, BA.dataTermino, B.agencia, B.categoria, A.nome as nomeAluno, A.area
			FROM j17_bolsas_aloc as BA, j17_bolsas as B, j17_aluno as A
			WHERE BA.idBolsa = B.id AND BA.idAluno = A.id ";
	if (sizeof($where)) {
		$sql .= ' AND '.implode(' AND ',$where);
    }
	$sql = $sql.' ORDER BY A.nome';
	
    $database->setQuery($sql);
    $historicos = $database->loadObjectList(); ?>	
    
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();
		});
		
		function imprimir(form, itemId, agencia, categoria, mes) {
			agencia = form.agencia.value;
			categoria = form.categoria.value;
			mes = form.mes.value;
			
        	window.open("index.php?option=com_bolsas&Itemid="+itemId+"&task=imprimirHistorico&agencia="+agencia+"&categoria="+categoria+"&mes="+mes+"","_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
	    }
	</script>
    
    <form method="post" name="form" action="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>">

        <!-- BARRA DE FERRAMENTAS -->
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <!--<div class="icon" id="toolbar-new">
                    <a href="javascript:imprimir(form, <?php echo $Itemid;?>)">
                    <span class="icon-32-print"></span><?php echo JText::_( 'Imprimir' ); ?></a>
                </div>-->
                
                <div class="icon" id="toolbar-back">
                    <a href="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
    
            <div class="clr"></div>
            </div>
            <div class="pagetitle icon-48-search"><h2>Histórico de Bolsistas</h2></div>
            </div>
        </div>
        
        <!-- FILTRO DA BUSCA -->
        <fieldset>
            <legend>Filtros para consulta</legend>
            
			<div class="line">
                <select name="buscaAgencia">            	
                    <option value="">Financiador</option>
                    <?php 
                        $agencias = listaAgencias(); 					
                        foreach ($agencias as $itemAgencia) { ?>                    
                            <option value="<?php echo $itemAgencia->sigla; ?>" <?php if($agencia == $itemAgencia->sigla) echo "SELECTED";?>><?php echo $itemAgencia->sigla?></option>                    
                    <?php } ?>
                </select>
                
                <select name="buscaCategoria">
                    <option value="">Categoria</option>
                    <option value="Mestrado" <?php if($categoria == "Mestrado") echo "SELECTED";?>>Mestrado</option>
                    <option value="Doutorado" <?php if($categoria == "Doutorado") echo "SELECTED";?>>Doutorado</option>
                </select>
                
                <select name="buscaArea">
                    <option value="">Linha de Pesquisa</option>
					<?php 
                        $linhas = listaLinhasPesquisa(); 					
                        foreach ($linhas as $linha) { ?>                    
                            <option value="<?php echo $linha->id; ?>" <?php if($area == $linha->id) echo "SELECTED";?>><?php echo $linha->nome?></option>                    
                    <?php } ?>
                </select>
                <select name="buscaAno">
                    <option value="">Ano</option>
                    <?php
                        $sql = "SELECT YEAR(dataInicio) as ano FROM #__bolsas_aloc 
								UNION 
								SELECT YEAR(dataTermino) as ano FROM #__bolsas_aloc ORDER BY ano ASC";
                        $database->setQuery($sql);
                        $dados = $database->loadObjectList();
                        
                        foreach ($dados as $data) { ?>
                            <option value="<?php echo $data->ano; ?>" <?php if($ano == $data->ano) echo "SELECTED";?>><?php echo $data->ano; ?></option>
                    <?php } ?>
                </select>
                
                <button type="submit" name="buscar" class="btn btn-primary pull-right" title="Filtra busca">
                    <i class="icone-search icone-white"></i> Consultar
                </button>
            </div>
            
        </fieldset>

		<?php 
		if (!$historicos) 
			echo '<div class="alert alert-info">
			  		<b>Não há registros para esta consulta</b>
			  	  </div>'; 
			else { ?>

            <table class="table table-striped" id="tablesorter-imasters">
                <thead>
                    <tr>
                        <th><i class="icone icone-align-justify"></i></th>
                        <th>Bolsista</th>
	                    <th>Linha de Pesquisa</th>
                        <th>Nível</th>                       
                        <th>Financiador</th>
                        <th>Início</th>
                        <th>Término</th>                        
						<th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $historicos as $historico ) { ?>
                    
                        <tr>
                            <td></td>
                            <td><?php echo $historico->nomeAluno; ?></td>
	                        <td><?php echo linhaPesquisa($historico->area); ?></td>                            
                            <td><?php echo ucfirst($historico->categoria); ?></td>
                            <td><?php echo $historico->agencia; ?></td>
                            <td><?php echo dataBr($historico->dataInicio); ?></td>
                            <td><?php echo dataBr($historico->dataTermino); ?></td>
							<td><?php echo $historico->status; ?></td>
                        </tr>
                    
                    <?php } ?>
            
                </tbody>
            </table>
        
        <?php } // FIM ELSE ?>
        
        <br />
        <span class="label label-inverse upper">Total de Registros: <?php echo sizeof($historicos);?></span>

        <input type="hidden" name="agencia" value="<?php echo $historico->agencia; ?>" />
        <input type="hidden" name="categoria" value="<?php echo $historico->categoria; ?>" />
        <input type="hidden" name="area" value="<?php echo $historico->area; ?>" />
        <input type="hidden" name="mes" value="<?php echo $historico->mes; ?>" />
        <input type="hidden" name="task" value="historico" />
        
    </form>  
        
<?php } ?>


<?php // VISUALIZAR HISTORICO DE ALOCAÇÃO : PDF
function visualizarPDF ($agencia, $categoria, $area, $mes) {  
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); 
	 
    $sqlEstendido = $sqlEstendido2 = $sqlEstendido3 = $sqlEstendido4 = "";

    if($agencia)
		$sqlEstendido = "AND B.agencia LIKE '$agencia'";

    if($categoria) 
		$sqlEstendido2 = "AND B.categoria = '$categoria'";
		
    if($area) 
		$sqlEstendido3 = "AND B.area = '$categoria'";		

    if($mes) 
		$sqlEstendido4 = "AND MONTH(BA.dataTermino) = '$mes'";
	
	$sql = "SELECT BA.id, BA.idAluno, BA.idBolsa, BA.dataRegistro, BA.dataInicio, BA.dataTermino, BA.motivo, BA.justificativa, BA.status, B.agencia, B.categoria, A.nome as nomeAluno
			FROM j17_bolsas_aloc as BA, j17_bolsas as B, j17_aluno as A
			WHERE BAH.idBolsa = B.id
			AND BAH.idAluno = A.id ".$sqlEstendido." ".$sqlEstendido2." ".$sqlEstendido3." ".$sqlEstendido4." ";
    $database->setQuery($sql);
    $historicos = $database->loadObjectList();
    
	$mes = date("m");
    switch ($mes) {
		case 1: $mes = "Janeiro"; break;
		case 2: $mes = "Fevereiro"; break;
		case 3: $mes = "Março"; break;
		case 4: $mes = "Abril"; break;
		case 5: $mes = "Maio"; break;
		case 6: $mes = "Junho"; break;
		case 7: $mes = "Julho"; break;
		case 8: $mes = "Agosto"; break;
		case 9: $mes = "Setembro"; break;
		case 10: $mes = "Outubro"; break;
		case 11: $mes = "Novembro"; break;
		case 12: $mes = "Dezembro"; break;
    }
	
	$file = "components/com_bolsas/historico/teste.pdf";
	$arq = fopen($file, 'w') or die("CREATE ERROR");

    $pdf = new Cezpdf();
    $pdf->selectFont('pdf-php/fonts/Helvetica.afm');
    $optionsText = array('justification'=>'center', 'spacing'=>1.5);	
    $dados = array('justification'=>'justify', 'spacing'=>1.0);
	$optionsTable = array('fontSize'=>10, 'titleFontSize'=>12 ,
					  'xPos'=>'center', 'width'=>560, 'cols'=>array(
					  utf8_decode('Bolsista')=>array('width'=>200, 'justification'=>'left'),
					  utf8_decode('Financiador')=>array('width'=>70, 'justification'=>'left'),
					  utf8_decode('Período')=>array('width'=>140, 'justification'=>'left'),
					  utf8_decode('Término')=>array('width'=>70, 'justification'=>'left'),
					  utf8_decode('Categoria')=>array('width'=>70, 'justification'=>'left')));

    $pdf->addJpegFromFile('components/com_portalaluno/images/ufam.jpg', 490, 720, 75);
    $pdf->ezText(utf8_decode('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>'),15,$optionsText);
    $pdf->ezText(utf8_decode('<b>Instituto de Computação - IComp</b>'),12,$optionsText);
    $pdf->ezText(utf8_decode('<b>Programa de Pós-Graduação em Informática - PPGI</b>'),12,$optionsText);
    //$pdf->addText(480,780,8,"<b>Data:</b> ".date("d/m/Y"),0,0);
    //$pdf->addText(480,790,8,"<b>Hora:</b> ".date("H:i"),0,0);
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->setLineStyle(3);
    $pdf->line(20, 700, 580, 700);
    $pdf->ezText(utf8_decode('<b>HISTÓRICO DE ALOCAÇÃO DE BOLSAS DE PESQUISA</b>'),12,$optionsText);
    $pdf->setLineStyle(3);
    $pdf->line(20, 700, 580, 700);	
    $pdf->ezText('');
    $pdf->ezText('');

	foreach ($historicos as $historico) {
        $listagem[]=array(utf8_decode('Bolsista')=>utf8_decode($historico->nomeAluno),
					  utf8_decode('Financiador')=>utf8_decode($historico->agencia),
					  utf8_decode('Período')=>dataBr($historico->dataInicio).' a '.dataBR($historico->dataTermino),
                      utf8_decode('Categoria')=>utf8_decode(ucfirst($historico->categoria)));
	}

	$pdf->ezTable($listagem,$cols,'',$optionsTable);

    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->addText(235,90,8,"<b>Manaus, ".date("d")." de ".$mes." de ".date("Y")."</b>",0,0);
    $pdf->line(20, 55, 580, 55);
    $pdf->addText(80,40,8,utf8_decode('Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur Virgílio Filho - CEP 69077-000 -  Manaus, AM, Brasil'),0,0);
	$pdf->addJpegFromFile('components/com_controleprojetos/images/icon_telefone.jpg', 140, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_controleprojetos/images/icon_email.jpg', 229, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_controleprojetos/images/icon_casa.jpg', 383, 30, 8, 8);
    $pdf->addText(150,30,8,utf8_decode('Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br'),0,0);

    $pdfcode = $pdf->output();
    fwrite($arq,$pdfcode);
	fclose($arq);

	header("Location: ".$file);

} ?>
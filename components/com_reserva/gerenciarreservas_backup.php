<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css" /> <!-- BARRA DE OPÇÕES -->

<?php // CADASTRAR RESERVA DE SALA : TELA 
function telaCadastrarReserva () { 
	$user =& JFactory::getUser();
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);  ?>
    
    <!-- MÁSCARAS PARA DATAS -->
	<script type="text/javascript" src="components/com_reserva/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
        $(function($){
            $("#dataInicio").mask("99/99/9999");
            $("#dataTermino").mask("99/99/9999");
            $("#horaInicio").mask("99:99");
            $("#horaTermino").mask("99:99");
        });
    </script>
    
    <!-- ENVIAR DATA -->    
    <script type="text/javascript">		
		function enviarData(form, dia, mes, ano) {
			form.dia.value = dia;
			form.mes.value = mes;
			form.ano.value = ano;			
			form.task.value = 'verTabelaHorario';
			form.submit();
		}
    </script>
    
    <!-- CALENDÁRIO -->
    <link rel="stylesheet" type="text/css" href="components/com_reserva/assets/css/calendario/default.css"/>
    <link rel="stylesheet" type="text/css" href="components/com_reserva/assets/css/calendario/jquery.click-calendario-1.0.css"/>
    <script type="text/javascript" src="components/com_reserva/assets/js/calendario/jquery.click-calendario-1.0-min.js"></script>
    <script type="text/javascript" src="components/com_reserva/assets/js/calendario/script.js"></script>

	<!-- JANELA MODAL -->
 	<link rel="stylesheet" href="components/com_reserva/assets/css/calendario-modal.css" />
    
    <link rel="stylesheet" type="text/css" href="components/com_reserva/assets/css/estilo.css" />

    <!-- BARRA DE FERRAMENTAS -->          
	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        <div class="cpanel2">
        	<?php $user = JFactory::getUser(); if ($user->id == '66') { ?>
        	<div class="icon" id="toolbar-save">
                <a href="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>&task=addSala">
                <span class="icon-32-new"></span><?php echo JText::_( 'Nova Sala' ); ?></a>
            </div>
            <?php } ?>
        
            <div class="icon" id="toolbar-save">
                <a href="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>&task=gerenciarReservas">
                <span class="icon-32-module"></span><?php echo JText::_( 'Listagem' ); ?></a>
            </div>
            
            <div class="icon" id="toolbar-cancel">
                <a href="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>&task=reservas">
                <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
            </div>
        </div>
        
        <div class="clr"></div>            
        </div>
    
        <div class="pagetitle icon-48-contact-categories">
            <h2>Reserva de Sala</h2>
        </div>
    </div></div>
    
	<form method="post" name="formData" action="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>" class="form-horizontal">

    <?php
		// NAVEGAÇÃO ENTRE OS MESES
		if(empty($_GET['data'])) {
			$dia = date('d');
			$month = ltrim(date('m'),"0");
			$ano = date('Y');
		} else {
			$data = explode('/',$_GET['data']); // NOVA DATA
			$dia = $data[0];
			$month = $data[1];
			$ano = $data[2];
		}

		if($month==1) { // MES ANTERIOR DE JANEIRO
			$mes_ant = 12;
			$ano_ant = $ano - 1;
		} else {
			$mes_ant = $month - 1;
			$ano_ant = $ano;
		}
		
		if($month==12) { // PROXIMO MES DE DEZEMBRO
			$mes_prox = 1;
			$ano_prox = $ano + 1;
		} else {
			$mes_prox = $month + 1;
			$ano_prox = $ano;
		}

		$hoje = date('j'); // DIA ATUAL
		
		$mesAtual = date('m'); // MES ATUAL
		if ($_GET['data']) {
			$mesCalendar = explode('/',$_GET['data']); // MES DO CALENDARIO
			$mesCalendar = $mesCalendar[1];
		} else {
			$mesCalendar = $mesAtual; // EXIBIÇÃO INICIAL DO CALENDÁRIO
		}
		
		// IDENTIFICAR O MES E A QUANTIDADE DE DIAS
		switch($month.$n){
			case 1: $mes = "JANEIRO";
					$n = 31;
			break;
			case 2: $mes = "FEVEREIRO";
					$bi = $ano % 4; // VERIFICAÇÃO PARA ANO BISSEXTO (MULTIPLO DE 4)
					if($bi == 0){
						$n = 29;
					}else{
						$n = 28;
					}
			break;
			case 3: $mes = "MARÇO";
					$n = 31;
			break;
			case 4: $mes = "ABRIL";
					$n = 30;
			break;
			case 5: $mes = "MAIO";
					$n = 31;
			break;
			case 6: $mes = "JUNHO";
					$n = 30;
			break;
			case 7: $mes = "JULHO";
					$n = 31;
			break;
			case 8: $mes = "AGOSTO";
					$n = 31;
			break;
			case 9: $mes = "SETEMBRO";
					$n = 30;
			break;
			case 10: $mes = "OUTUBRO";
					$n = 31;
			break;
			case 11: $mes = "NOVEMBRO";
					$n = 30;
			break;
			case 12: $mes = "DEZEMBRO";
					$n = 31;
			break;
		}

		$pdianu = mktime(0,0,0,$month,1,$ano); // PRIMEIRO DIA DO MES
		$dialet = date('D', $pdianu); // ESCOLHE PELO DIA DA SEMANA
		
		// VERIFICA O DIA QUE CAI
		switch($dialet){ 
			case "Sun": $branco = 0; break;
			case "Mon": $branco = 1; break;
			case "Tue": $branco = 2; break;
			case "Wed": $branco = 3; break;
			case "Thu": $branco = 4; break;
			case "Fri": $branco = 5; break;
			case "Sat": $branco = 6; break;
		}            
       
		echo '<div style="width:574px; height:auto; margin:auto;">';
		
		// NOME DO MES
		echo '<div id="safe-mes">';
			echo $mes.'/'.$ano;
		echo '</div>';
		
		// MES ANTERIOR
		echo '<a href="index.php?option=com_reserva&Itemid='.$Itemid.'&task=addReserva&data='.$dia.'/'.$mes_ant.'/'.$ano_ant.'"> <div class="nav"> <i class="icone-chevron-left"></i> Anterior </div> </a>';
		
		// PROXIMO MES
		echo '<a href="index.php?option=com_reserva&Itemid='.$Itemid.'&task=addReserva&data='.$dia.'/'.$mes_prox.'/'.$ano_prox.'"> <div class="nav"> Próximo <i class="icone-chevron-right"></i> </div> </a>';
		
		// DIAS DA SEMANA
		echo '
			<div class="diasNome">Domingo</div>
			<div class="diasNome">Segunda</div>
			<div class="diasNome">Terça</div>
			<div class="diasNome">Quarta</div>
			<div class="diasNome">Quinta</div>
			<div class="diasNome">Sexta</div>
			<div class="diasNome">Sábado</div>';
	
		$dt = 1;
	
		if($branco > 0){
			for($x = 0; $x < $branco; $x++){
				print '<div class="dias diabranco">&nbsp;</div>'; // DIAS EM BRANCO
				$dt++;
			}
		}
	
		// DIAS DO MES
		for($i = 1; $i <= $n; $i++ ){ // FUNÇÃO PARA VERIFICAR RESERVAS
		
			$qtdeReservas = verificarReservas($i, $mesCalendar); // QUANTIDADE DE RESERVAS DO DIA
		
			if(($i == $hoje) && ($mesAtual == $mesCalendar)){ // DIA ATUAL ?>
                <a href="javascript:enviarData(document.formData,'<?php echo $i; ?>','<?php echo $mesCalendar; ?>','<?php echo $ano; ?>')">
					<div class="dias diahoje"><?php echo $i; ?></div>
				</a>
                
				<?php $dt++;
				
			}elseif($dt == 1){ // DOMINGOS
				echo '<div id="dom" class="dias" style="background-color:#eaeaea;">'.$i.'</div>';
				$dt++;
	
			}else{ // DIAS NORMAIS	?> 
            	<a href="javascript:enviarData(document.formData,'<?php echo $i; ?>','<?php echo $mesCalendar; ?>','<?php echo $ano; ?>')">                
                
					<?php if ($qtdeReservas) { 
					
						// LISTAGEM DAS INFORMAÇÕES DAS RESERVAS
						$itens = identificarReservas($i, $mesCalendar); ?>
                        
						<div class="dias diasContent">                        
                        	<span class="diasContentQtde">
								<?php echo $qtdeReservas.'<span class="txtreserva">reserva(s)</span>'; ?>
                            </span>
                        
							<p class="hint"> <!-- EXIBE SE HOUVER RESERVA PARA O DIA -->
								<?php foreach ($itens as $itensReserva) 
									echo '<i class="icone-flag icone-white"></i> '
									.horaBr($itensReserva->horaInicio).' às '.horaBr($itensReserva->horaTermino).' '
									.$itensReserva->atividade.' '
									.$itensReserva->sala.' '
									.$itensReserva->name.'<br />'; ?>
                            </p>
                            
							<?php echo $i; ?>
                        </div>
                        
                    <?php } else { ?>
		               <div class="dias"><?php echo $i; ?></div>
                    <?php } ?>
                    
				   </a>
				<?php $dt++;
			}
	
			if($dt > 7){ // QUEBRA NO SÁBADO
				echo '</br>';
				$dt = 1;
			}
		}
	
		echo '</div>';
	?>
    
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="dia" value="" />
    <input type="hidden" name="mes" value="" />
    <input type="hidden" name="ano" value="" />    
    
    </form>
    
<?php } ?>


<?php // LISTAGEM DE RESERVAS
function listarReservas($atividade = '', $tipo = '', $dataInicio = '', $dataTermino = '') {
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0); 
	
	if($atividade){ $where[] = "atividade LIKE '%$atividade%'"; }

	if($tipo){ $where[] = "tipo LIKE '%$tipo%'"; }	
	
	if($dataInicio){ $where[] = "dataInicio = '$dataInicio'"; }

	if($dataTermino){ $where[] = "dataTermino = '$dataTermino'"; }
	
	/*CONDIÇÃO PARA LIMITAR O ACESSO */
	$user = JFactory::getUser();
	if ($user->id == '66')
		$sql = "SELECT * FROM #__reservas WHERE 1";
	else
		$sql = "SELECT * FROM #__reservas WHERE idSolicitante = '$user->id'";
		
	if (sizeof($where)) {
		$sql .= ' AND '.implode(' AND ',$where);
    }
    $database->setQuery($sql);
    $gerenciarReservas = $database->loadObjectList(); ?>
        
	<link rel="stylesheet" type="text/css" href="components/com_reserva/assets/css/estilo.css" />
	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/estilo.css" /> <!-- TABELA -->
    
    <!-- SCRIPTS -->
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
    <script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();
		});
		
		function excluir(id){
			if (confirm("Deseja realmente exluir o registro?")) {
				form.task.value = 'excluirReserva';
				form.idReserva.value = id;
				form.submit();
			} else
				alert("Exclusão cancelada");	
		}
	</script>
    
    <!-- MÁSCARAS -->
    <script type="text/javascript" src="components/com_reserva/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
		$(function($){
			$("#dataInicio").mask("99/99/9999");
			$("#dataTermino").mask("99/99/9999");
		});
    </script>
    
    <!-- CALENDÁRIO -->
    <link rel="stylesheet" type="text/css" href="components/com_reserva/assets/css/calendario/default.css"/>
    <link rel="stylesheet" type="text/css" href="components/com_reserva/assets/css/calendario/jquery.click-calendario-1.0.css"/>
    <script type="text/javascript" src="components/com_reserva/assets/js/calendario/jquery.click-calendario-1.0-min.js"></script>
    <script type="text/javascript" src="components/com_reserva/assets/js/calendario/script.js"></script>
    
	<form method="post" name="form" action="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>">

        <!-- BARRA DE FERRAMENTAS -->
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-new">
                    <a href="javascript:document.form.task.value='addReserva';document.form.submit()">
                    <span class="icon-32-calendar"></span><?php echo JText::_( 'Calendário' ); ?></a>
                </div>
                
                <div class="icon" id="toolbar-back">
                    <a href="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>&task=addReserva">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
    
            <div class="clr"></div>
            </div>
            <div class="pagetitle icon-48-search2"><h2>Reserva de Salas</h2></div>
            </div>
        </div>
        
        <!-- FILTRO DA BUSCA -->
        <fieldset>
            <legend>Filtros para consulta</legend>
            
            <input type="text" name="atividade" placeholder="Busque por atividade..." />
            
            <select name="tipo">
            	<option value="">Selecione o tipo</option>
            	<option value=""></option>
            </select>
            
            <input type="text" name="dataInicio" id="dataInicio" placeholder="Data Inicial" class="imgcalendar" />
            
            <input type="text" name="dataTermino" id="dataTermino" placeholder="Data Final" class="imgcalendar" />
            
            <button type="submit" name="buscar" class="btn btn-primary pull-right" title="Filtra busca">
            	<i class="icone-search icone-white"></i> Consultar
            </button>            
        </fieldset>
        
        <table class="table table-striped" id="tablesorter-imasters">
            <thead>
                <tr>
                	<th>Sala</th>
                    <th>Atividade</th>
                    <th>Solicitante</th>
                    <th>Início</th>
                    <th>Término</th>
                    <th>Horário</th>
                    <td colspan="2" style="background:#E7E3E3; border-bottom:2px solid #D9D2D2; font-weight:bold;">Opções</td>
                </tr>
            </thead>
            
            <tbody>
            
            	<?php 
					foreach ($gerenciarReservas as $reservas) { 
					
					$sql = "SELECT name FROM #__users WHERE id = '$reservas->idSolicitante' ";
					$database->setQuery($sql);
					$dados = $database->loadObjectList(); ?>
                    
            	<tr>
                	<td><?php echo $reservas->sala; ?></td>
                    <td><?php echo $reservas->atividade; ?></td>
                    <td><?php echo $dados[0]->name; ?></td>
                    <td><?php echo dataBr($reservas->dataInicio); ?></td>
                    <td><?php echo dataBr($reservas->dataTermino); ?></td>
                    <td><?php echo horaBr($reservas->horaInicio).' às '.horaBr($reservas->horaTermino); ?></td>
                    <td><a href="javascript:document.form.task.value='editReserva';document.form.idReserva.value='<?php echo $reservas->id; ?>';document.form.submit()" title="Editar Reserva">
                    	<span class="label label-info"><i class="icone-pencil icone-white"></i></span>
                        </a>
					</td>                    
                    <td><a href="javascript:document.form.task.value='confirmarExclusao';document.form.idReserva.value='<?php echo $reservas->id; ?>';document.form.submit()" title="Desfazer Reserva">
                    	<span class="label label-important"><i class="icone-minus icone-white"></i></span>
                        </a>
					</td>
                </tr>
                
                <?php } ?>
                
            </tbody>
        </table>
        
        <br />
        Total de Reservas: <b><?php echo sizeof($gerenciarReservas);?></b>
        
        <input name='task' type='hidden' value='gerenciarReservas' />
        <input name='idReserva' type='hidden' value='' />    
    </form>
        
<?php } ?>


<?php // CADASTRAR RESERVA DE SALA : SQL
function salvarReserva() {
	$database =& JFactory::getDBO();
	
	$sala = JRequest::getVar('sala');
	$idSolicitante = JRequest::getVar('idSolicitante');	
	$atividade = JRequest::getVar('atividade');
	$tipo = JRequest::getVar('tipo');	
	$dataInicio = JRequest::getVar('data');
	$dataTermino = JRequest::getVar('data');
	$horaInicio = JRequest::getVar('horarioInicio');
	$horaTermino = JRequest::getVar('horarioTermino');
	
	$dataInicio = dataSql($dataInicio);
	$dataTermino = dataSql($dataTermino);

	$sql = "INSERT INTO `#__reservas` (`id`, `sala`, `idSolicitante`, `atividade`, tipo, `dataInicio`, `dataTermino`, `horaInicio`, `horaTermino`) VALUES ('', '$sala', '$idSolicitante', '$atividade', '$tipo', '$dataInicio', '$dataTermino', '$horaInicio', '$horaTermino');";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	if($funcionou){
		echo '<div class="alert alert-success">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>CADASTRO :</b> Reserva de sala realizada com sucesso!
			  </div>';
	} else {
		echo '<div class="alert alert-error">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>CADASTRO :</b> Não foi possível reservar esta sala, tente novamente!
			  </div>';
	}
	
	telaCadastrarReserva();
}
?>


<?php // EDITAR RESERVA DE SALA : TELA 
function telaEditarReserva ($idReserva) { 
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);  
	
	$dadosReserva = identificarReservaId($idReserva); ?>

    <!-- SCRIPTS -->    
    <script type="text/javascript">
		function ValidateForm(form) {        
           var erro = false;
		   
		   form.submeter.click();
		}
	</script>
    
    <!-- MÁSCARAS PARA NÚMEROS -->
	<script type="text/javascript" src="components/com_bolsas/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
        $(function($){
            $("#dataInicio").mask("99/99/9999");	
        });
    </script>
    
    <!-- CALENDÁRIO -->
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/default.css"/>
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/jquery.click-calendario-1.0.css"/>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/jquery.click-calendario-1.0-min.js"></script>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/script.js"></script>
    
	<form method="post" name="formEditReserva" action="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>" class="form-horizontal">
                  
        <!-- BARRA DE FERRAMENTAS -->
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-save">
                    <a href="javascript: ValidateForm(document.formEditReserva)" class="toolbar">
                    <span class="icon-32-save"></span><?php echo JText::_( 'Salvar' ); ?></a>
                </div>
                
                <div class="icon" id="toolbar-cancel">
                    <a href="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>&task=gerenciarReservas">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
            
            <div class="clr"></div>            
            </div>
        
            <div class="pagetitle icon-48-cpanel">
                <h2>Edição de Reservas</h2>
            </div>
        </div></div>
    
        <span class="label label-info">Informações</span>
        <ul>
            <li>Os campos com <span class="obrigatory"> * </span> são de preenchimento obrigatório</li>
            <li></li>
        </ul>
        <hr />
        
        <table class="table-form"> 
            <tr>
                <td>Sala <span class="obrigatory">*</span></td>
                <td><select name="sala" required="required">
                    <option value="<?php echo $dadosReserva->sala; ?>"><?php echo $dadosReserva->sala; ?></option>
                    <option value="Reunião">Reunião</option>
                </select>
                </td>
            </tr>
            <tr>
                <td>Atividade <span class="obrigatory">*</span></td>
                <td><textarea name="atividade" id="atividade" required="required" style="width:350px; height:60px; font-size:14px;"><?php echo $dadosReserva->atividade; ?></textarea></td>
            </tr>
            <tr>
                <td>Tipo <span class="obrigatory">*</span></td>
                <td><select name="tipo" required="required">
                    <option value="<?php echo $dadosReserva->tipo; ?>"><?php echo $dadosReserva->tipo; ?></option>
                    <option value=""></option>
                </select></td>
            </tr>
            <tr>
                <td>Data <span class="obrigatory">*</span></td>
                <td><input type="text" name="data" id="dataInicio" value="<?php echo dataBr($dadosReserva->dataInicio); ?>" class="imgcalendar" /></td>
            </tr>
            <tr>
                <td>Início <span class="obrigatory">*</span></td>
                <td><select name="horarioInicio" id="horarioInicio" required="required">
                    <option value="<?php echo $dadosReserva->horaInicio; ?>"><?php echo horaBr($dadosReserva->horaInicio); ?></option>
                    <option value="08:00">08:00</option>
                    <option value="09:00">09:00</option>
                    <option value="10:00">10:00</option>
                    <option value="11:00">11:00</option>
                    <option value="12:00">12:00</option>                                
                    <option value="13:00">13:00</option>
                    <option value="14:00">14:00</option>
                    <option value="15:00">15:00</option>
                    <option value="16:00">16:00</option>
                    <option value="17:00">17:00</option>
                    <option value="18:00">18:00</option>
                </select>
                </td>
            </tr>
            <tr>
                <td>Término <span class="obrigatory">*</span></td>
                <td><select name="horarioTermino" id="horarioTermino" required="required">
                    <option value="<?php echo $dadosReserva->horaTermino; ?>"><?php echo horaBr($dadosReserva->horaTermino); ?></option>
                    <option value="08:00">08:00</option>
                    <option value="09:00">09:00</option>
                    <option value="10:00">10:00</option>
                    <option value="11:00">11:00</option>
                    <option value="12:00">12:00</option>                                
                    <option value="13:00">13:00</option>
                    <option value="14:00">14:00</option>
                    <option value="15:00">15:00</option>
                    <option value="16:00">16:00</option>
                    <option value="17:00">17:00</option>
                    <option value="18:00">18:00</option>
                </select>
                </td>
            </tr>
        </table>       
        
		<input name="idReserva" type="hidden" value="<?php echo $dadosReserva->id; ?>" />        
		<input type="submit" value="Submeter" name="submeter" style="display:none" />
		<input name="task" type="hidden" value="atualizarReserva" />

    </form>
    
<?php } ?>


<?php // EDITAR RESERVA DE SALA : SQL
function atualizarReserva($idReserva) {
	$database =& JFactory::getDBO();
	
	$sala = JRequest::getVar('sala');
	$atividade = JRequest::getVar('atividade');
	$tipo = JRequest::getVar('tipo');	
	$dataInicio = JRequest::getVar('data');
	$dataTermino = JRequest::getVar('data');
	$horaInicio = JRequest::getVar('horarioInicio');
	$horaTermino = JRequest::getVar('horarioTermino');	
	
	$dataInicio = dataSql($dataInicio);
	$dataTermino = dataSql($dataTermino);

	$sql = "UPDATE `#__reservas` SET sala = '$sala', atividade = '$atividade', tipo = '$tipo', dataInicio = '$dataInicio', dataTermino = '$dataTermino', horaInicio = '$horaInicio', horaTermino = '$horaTermino' WHERE id = '$idReserva';";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	if($funcionou){
		echo '<div class="alert alert-success">
			  	<b>EDIÇÃO :</b> Reserva de sala atualizada com sucesso!
			  </div>';
	} else {
		echo '<div class="alert alert-error">
			  	<b>EDIÇÃO :</b> Não foi possível atualizar este registro, tente novamente!
			  </div>';
	}
	
	listarReservas('', '', '', '');
}
?>


<?php // CONFIRMAR EXCLUSÃO DA RESERVA
function confirmarExclusao ($idReserva) { 
    $Itemid = JRequest::getInt('Itemid', 0); ?>

	<form method="post" name="form" action="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>">
    
        <div class="alert alert-error">
            <h4>Advertência!</h4>
            Você realmente deseja desmarcar esta reserva?
    
            <button class="btn btn-small btn-danger" type="submit">Sim, quero excluir</button>        
            <a href="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>&task=gerenciarReservas">
            	<button class="btn btn-small" type="button">Cancelar Exclusão</button>
			</a>
        </div>
    
        <input name='task' type='hidden' value='excluirReserva' />    
	    <input name='idReserva' type='hidden' value='<?php echo $idReserva; ?>' />
    
    </form>

<?php listarReservas('', '', '', ''); } ?>


<?php // EXCLUIR RESERVA
function excluirReserva($idReserva) {
	$database = JFactory::getDBO();
	
	$sql = "DELETE FROM #__reservas WHERE id = '$idReserva'";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	if($funcionou) {
		echo '<div class="alert alert-success">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>EXCLUSÃO :</b> Reserva de sala excluída com sucesso!
			  </div>';
	} else {
		echo '<div class="alert alert-error">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>EXCLUSÃO :</b> Não foi possível desfazer a reserva, tente novamente!
			  </div>';
	}
	
	listarReservas('', '', '', '');
} ?>


<?php // CADASTRAR SALA : TELA 
function telaCadastrarSala () { 
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);  ?>

    <!-- SCRIPTS -->    
    <script type="text/javascript">
		function ValidateForm(form) {        
           var erro = false;
		   
		   form.submeter.click();
		}
	</script>
    
    <!-- MÁSCARAS PARA NÚMEROS -->
	<script type="text/javascript" src="components/com_bolsas/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
        $(function($){
            $("#numero").mask("9999");	
        });
    </script>
    
	<form method="post" name="formCadSala" action="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>" class="form-horizontal">
                  
        <!-- BARRA DE FERRAMENTAS -->
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-save">
                    <a href="javascript: ValidateForm(document.formCadSala)" class="toolbar">
                    <span class="icon-32-save"></span><?php echo JText::_( 'Salvar' ); ?></a>
                </div>
                
                <div class="icon" id="toolbar-cancel">
                    <a href="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>&task=addReserva">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
            
            <div class="clr"></div>            
            </div>
        
            <div class="pagetitle icon-48-cpanel">
                <h2>Cadastro de Salas</h2>
            </div>
        </div></div>
    
        <span class="label label-info">Informações</span>
        <ul>
            <li>Os campos com <span class="obrigatory"> * </span> são de preenchimento obrigatório</li>
            <li></li>
        </ul>
        <hr />
        
        <table class="table-form">            
            <tbody>
            	<tr>
                	<td>Nome da Sala <span class="obrigatory">*</span></td>
                    <td><input type="text" name="nome" required="required" /></td>
                </tr>
                <tr>
                	<td>Número</td>
                    <td><input type="text" name="numero" id="numero" /></td>
                </tr>
                <tr>
                	<td>Localização</td>
                    <td><input type="text" name="localizacao" /></td>
                </tr>
            </tbody>
        </table>        
        
		<input type="submit" value="Submeter" name="submeter" style="display:none" />
		<input name="task" type="hidden" value="cadSala" />
    </form>
    
<?php } ?>


<?php // CADASTRAR SALA : SQL
function salvarSala() {
	$database =& JFactory::getDBO();
	
	$nome = JRequest::getVar('nome');	
	$numero = JRequest::getVar('numero');
	$localizacao = JRequest::getVar('localizacao');

	$sql = "INSERT INTO `#__reservas_salas` (`id`, nome, numero, localizazao) VALUES ('', '$nome', '$numero', '$localizacao');";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	if($funcionou){
		echo '<div class="alert alert-success">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>CADASTRO :</b> Sala cadastrada com sucesso!
			  </div>';
	} else {
		echo '<div class="alert alert-error">
			  	<button type="button" class="close" data-dismiss="alert">×</button>
			  	<b>CADASTRO :</b> Não foi possível cadastrar esta sala, tente novamente!
			  </div>';
	}
	
	telaCadastrarReserva();
}
?>


<?php // TABELA DE HORÁRIO
function tabelaHorario($dia, $mes, $ano) { 
	$user =& JFactory::getUser();
    $database = JFactory::getDBO();
    $Itemid = JRequest::getInt('Itemid', 0);
	
	// IDENTIFICAR O MES
	switch($mes){
		case 1: $nomeMes = "JANEIRO";
		break;
		case 2: $nomeMes = "FEVEREIRO";
		break;
		case 3: $nomeMes = "MARÇO";
		break;
		case 4: $nomeMes = "ABRIL";
		break;
		case 5: $nomeMes = "MAIO";
		break;
		case 6: $nomeMes = "JUNHO";
		break;
		case 7: $nomeMes = "JULHO";
		break;
		case 8: $nomeMes = "AGOSTO";
		break;
		case 9: $nomeMes = "SETEMBRO";
		break;
		case 10: $nomeMes = "OUTUBRO";
		break;
		case 11: $nomeMes = "NOVEMBRO";
		break;
		case 12: $nomeMes = "DEZEMBRO";
		break;
	} 
		
	// IDENTIFICAR O DIA DA SEMANA
	$diaSemana = date('l');		

	switch($diaSemana){ 
		case "Sunday": $nomeDia = 'Domingo'; break;
		case "Monday": $nomeDia = 'Segunda-feira'; break;
		case "Tuesday": $nomeDia = 'Terça-feira'; break;
		case "Wednesday": $nomeDia = 'Quarta-feira'; break;
		case "Thursday": $nomeDia = 'Quinta-feira'; break;
		case "Friday": $nomeDia = 'Sexta-feira'; break;
		case "Saturday": $nomeDia = 'Sábado'; break;
	} ?>
    
    <!-- SCRIPTS -->
    <script type="text/javascript" src="components/com_reserva/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
        $(function($){
            $("#horarioInicio").mask("99:99");
        });
    </script>    
       
    <!-- ENVIAR DATA, HORA E SALA -->
	<script type="text/javascript">		
		function enviarHora(form, horario, data, sala) {
			formCadReserva.horarioInicio.value = horario+':00';
			formCadReserva.data.value = data;
			formCadReserva.salaDisabled.value = sala;
			formCadReserva.sala.value = sala;					
		}
    </script>
        
    <script type="text/javascript" src="components/com_reserva/assets/js/modal.js"></script>
    <link rel="stylesheet" href="components/com_reserva/assets/css/calendario-modal.css" />
    
    <!-- BARRA DE FERRAMENTAS -->          
	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        <div class="cpanel2">
            
            <div class="icon" id="toolbar-cancel">
                <a href="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>&task=addReserva">
                <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
            </div>
        </div>
        
        <div class="clr"></div>            
        </div>
    
        <div class="pagetitle icon-48-contact-categories">
            <h2>Reserva de Sala</h2>
        </div>
    </div></div>    
    
    <span class="label label-warning">LEGENDA</span><br /><br />
    <span class="label label-success">R</span> Sala de Reuniões
    <span class="label label-info">S</span> Sala de Seminários
    <hr />
    
   	<form method="post" name="formCadHora" action="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>" class="form-horizontal">
    
    	<div class="lftitulo">
		   	<div class="line-left">Horário</div>
    	    <div class="line-right"><?php echo $nomeDia.', '.$dia.' de '.$nomeMes; ?></div>
        </div>
        
        <!-- NOME DAS SALAS -->
        <div class="line-left"></div>
  	    <div class="line-right">
        	<?php		
			$salas = identificarSalas();
			foreach ($salas as $sala) {
				echo '<div class="room-title"> <i class="icone icone-th-list"></i> Sala de '.$sala->nome.'</div>';		
			} ?> 
		</div>
         
    	<!-- LINHAS DOS HORÁRIOS -->
		<?php $i = 8; 
        for ($i; $i<=22; $i++) { 
		
			if ($i % 2 == 0) 
				echo '<div class="line" style="background:#eeeff0;">';
			else 
				echo '<div class="line">'; ?>
    
                <div class="line-left"><?php echo $i.':00'; ?></div>
                
				<div class="line-right">
                
                	<?php					
					$salas = identificarSalas();
					foreach ($salas as $sala) { ?>
                    
                    <a href="javascript:enviarHora(document.formCadHora,'<?php echo $i; ?>','<?php echo $dia.'/'.$mes.'/'.$ano; ?>','<?php echo $sala->nome; ?>')" class="button" data-type="zoomin" title="Escolher horário">
                    <div class="column-room">
                
						<?php
                        $reservas = identificarReservasHora($i, $dia, $mes, $sala->nome); 
                        foreach ($reservas as $reservaHora) { 
                            $hInicio = horaBr($reservaHora->horaInicio);
                            $hTermino = horaBr($reservaHora->horaTermino);
                            
                            // A DIFERENÇA DE HORAS DETERMINA O TAMANHO DA DIV
                            $dif = $hTermino - $i; 
                            
                            // DEFINIR A COR DA DIV PELO TIPO DE SALA
                            $tipoSala = $reservaHora->sala; 
                            if ($tipoSala == 'Reunião')
                                $tpSala = 'reunion'; 
                                
                            if ($tipoSala == 'Seminário')
                                $tpSala = 'seminar'; ?>
                            
                            <!-- DIV DA RESERVA -->
                            <a href="#" title="Horário indisponível para esta sala!">
                            <div class="box-item espaco<?php echo $dif.' '.$tpSala; ?>">
                                <?php echo '<b>'.$hInicio.' às '.$hTermino.'</b>
                                            ('.$reservaHora->name.')<br /> '
                                            .$reservaHora->atividade; ?>
                            </div>
                            </a>
                        
                        <?php } ?>           
                		                    
                    </div>	<!-- FIM DIV COLUMN-ROOM -->
                    </a>
                    
					<?php } ?>  
                                  
                </div>	<!-- FIM DIV LINE-RIGHT -->                
            </div>	<!-- FIM DIV LINE -->
            
        <?php } ?>    
    
    </form>    
    
    <!-- JANELA MODAL -->
	<div class="overlay-container">
		<div class="window-container zoomin">
        
        <!-- ALERTA DE HORÁRIO INVÁLIDO -->
		<script language="javascript">			
            $(function(){
                $("#horarioTermino").change(function(){
                var hora_inicio = $("#horarioInicio").val();
                var hora = $("#horarioTermino").val();
                
                if (hora > hora_inicio) {
                    $('#resultado2').html("");
                    $('#resultado2').css("background","#fff");
                } else {
                    $('#resultado2').html("Horário de término inválido!");
                    $('#resultado2').css("background","#f2dede");
                    $('#resultado2').css("border-radius","5px");
                    $('#resultado2').css("color","#b94a48");
                    $('#resultado2').css("padding","10px");						
                }
                
                return false;
                })
            })
        </script>
    
        <form method="post" name="formCadReserva" action="index.php?option=com_reserva&Itemid=<?php echo $Itemid;?>">
			<h3>Reserva de Sala</h3> 
            <hr />
            
            <div id="formularioReserva">
            
            <p><span class="label label-warning">AVISO</span> Todos os campos são <b>obrigatórios</b> para a reserva de salas.</p>
            
            <table class="table-form"> 
                <tbody>
                    <tr>
                        <td>Sala</td>
                        <td><input type="text" name="salaDisabled" required="required" disabled="disabled" />
                        </td>
                    </tr>
                    <tr>
                        <td>Atividade</td>
                        <td><textarea name="atividade" id="atividade" required="required" style="width:350px; height:60px;"></textarea></td>
                    </tr>
                    <tr>
                        <td>Tipo</td>
                        <td><select name="tipo" required="required">
                            <option value="">Selecione...</option>
                            <option value=""></option>
                        </select></td>
                    </tr>
                    <tr>
                        <td>Início</td>
                        <td><input type="text" name="horarioInicio" id="horarioInicio" required="required" /></td>
                    </tr>
                    <tr>
                        <td>Término</td>
                        <td><select name="horarioTermino" id="horarioTermino" required="required">
                            <option value="">Selecione...</option>
                            <option value="08:00">08:00</option>
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                            <option value="11:00">11:00</option>
                            <option value="12:00">12:00</option>                                
                            <option value="13:00">13:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                            <option value="17:00">17:00</option>
                            <option value="18:00">18:00</option>
                            <option value="19:00">19:00</option>
                            <option value="20:00">20:00</option>
                            <option value="21:00">21:00</option>
                            <option value="22:00">22:00</option>
                        </select></td><div id="resultado2" class="resultado"></div>
                    </tr>
                </tbody>
            </table>
            </div>
                
			<hr />
            <div class="pull-right">
				<button type="reset" class="btn fechar">Fechar</button>
				<button type="submit" class="btn btn-primary">Salvar Solicitação</button>
            </div>
	        <br /><br />
            
            <input name="task" type="hidden" value="cadReserva" />
            <input type="hidden" name="data" id="data" />
            <input type="hidden" name="sala" id="sala" />
            <input type="hidden" name="idSolicitante" value="<?php echo $user->id; ?>" />
            <input type="submit" name="submeter" value="Submeter" style="display:none" />

        </form>
	
		</div>
        
	</div>
	
<?php } ?>
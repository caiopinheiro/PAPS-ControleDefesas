<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css">
<link rel="stylesheet" href="components/com_inscricaoppgi/estilo.css" type="text/css" />

<?php //LISTAGEM DAS PRORROGAÇÕES
function mostrarTelaProrrogacao($projeto) {
	
	$Itemid = JRequest::getInt('Itemid', 0);
	$database =& JFactory::getDBO();

	$sql = "SELECT * FROM #__contproj_prorrogacoes WHERE projeto_id =".$projeto->id." ORDER BY dataPedido";
	$database->setQuery($sql);
	$prorrogacoes = $database->loadObjectList(); ?>

	<link type="text/css" rel="stylesheet" href="components/com_controleprojetos/estilo.css" />
    <link type="text/css" rel="Stylesheet" href="components/com_controleprojetos/jquery-ui-1.10.1.custom/css/ui-lightness/jquery-ui-1.10.1.custom.css" />
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.js" type="text/javascript"></script>
    <script src="components/com_controleprojetos/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
    
	<script type="text/javascript">
		$(function() {//Mudanca para PT(BR)
			$.datepicker.regional['pt-BR'] = {
				closeText: 'Fechar',
				prevText: '&#x3c;Anterior',
				nextText: 'Pr&oacute;ximo&#x3e;',
				currentText: 'Hoje',
				monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho',
				'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
				monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
				'Jul','Ago','Set','Out','Nov','Dez'],
				dayNames: ['Domingo','Segunda-feira','Ter&ccedil;a-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
				dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
				dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
				weekHeader: 'Sm',
				dateFormat: 'dd/mm/yy',
				firstDay: 0,
				isRTL: false,
				showMonthAfterYear: false,
				yearSuffix: ''};
			$.datepicker.setDefaults($.datepicker.regional['pt-BR']);
			$("#nova_data").datepicker({dateFormat: 'dd/mm/yy'})
	
		});
	</script>
    
    <script type="text/javascript">    
        function voltarNivelForm(form){
			form.task.value = 'verProjetopd';
			form.submit();
			return true;
		}
          
        function ValidarProrrogacao(form) {                     
			if(form.nova_data.value == 0) {
				alert('O campo Nova Data deve ser preenchido.')
				form.nova_data.focus();
				return false;
			}
    
            if(form.justificativa.value == 0) {
                alert('O campo Justificativa deve ser preenchido.')
                form.justificativa.focus();
                return false;
            }

            return true;
        }    
    </script>

	<form method="post" name="formProrrogacao" action="index.php?option=com_controleprojetos&Itemid=<?php echo $Itemid;?>" enctype="multipart/form-data">

	<!--Barra de Ferramentas Superior-->
	<div id="toolbar-box"><div class="m">
        <div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
				<div class="icon" id="toolbar-save">
                	<a href="javascript:if(ValidarProrrogacao(document.formProrrogacao)) document.formProrrogacao.submit()">
              		<span class="icon-32-save"></span>Salvar</a>
	            </div>
				<div class="icon" id="toolbar-back">
                    <a href="javascript:voltarNivelForm(formProrrogacao)">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
            <div class="clr"></div>
        </div>

        <div class="pagetitle icon-48-inbox">
            <h2>Solicita&#231;&#245;es de Prorrogação de Projetos</h2>
        </div>
		</div>
	</div>
	<?php exibirProjeto($projeto); ?>

	<hr>
	<?php if (sizeof($prorrogacoes) == 0) { ?>
	<?php } else { ?>

	<!--Tabela que lista os pedidos-->
	<table width='100%' cellpadding="0" cellspacing="0" id="tablesorter-imasters" class="tabela">
		<thead>
			<tr bgcolor="#002666" align="center">
				<th width="15%">Data do Pedido</th>
				<th width="15%">Data de Término Prevista</th>
				<th width="15%">Data de Término Alterada</th>
				<th width="55%">Justificativa</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$table_bgcolor_even="#e6e6e6";
			$table_bgcolor_odd="#FFFFFF";

            $i = 0;

            foreach ($prorrogacoes as $prorrogacao) {
                $i = $i + 1;
                if ($i % 2){
                    echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
                } else {
                    echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
                }
                ?>
				<td align="center"><?php echo dataBr($prorrogacao->dataPedido); ?></td>
				<td align="center"><?php echo dataBr($projeto->data_fim); ?></td>
				<td align="center"><?php echo dataBr($prorrogacao->dataTermino); ?></td>
				<td align="center"><?php echo $prorrogacao->justificativa; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<hr>    
	<br />
    
	<?php } ?>

		<table width="100%">
			<tr style="background-color: #7196d8;">
                <td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Solicitação de Novo Pedido de Prorrogação</font></b></font></td>
            </tr>
            <tr>
				<td class="tbItemForm">Nova Data <span> * </span></td>
                <td><input type="text" size="15" id="nova_data" name="data" class="inputbox" /></td>
            </tr>
           	<tr>
				<td class="tbItemForm">Justificativa</td>
                <td><textarea id="justificativa" name="justificativa" cols="80" rows="3" ></textarea></td>
			</tr>
		</table>
        
	<input name='idProjetopd' type='hidden' value='<?php echo $projeto->id; ?>' /> <!-- OPÇÃO PRA VOLTAR -->
	<input name='idProjeto' type='hidden' value='<?php echo $projeto->id; ?>' />
	<input name='task' type='hidden' value='adicionarProrrogacao' />
    
</form>

<?php } 


// SALVAR PRORROGAÇÃO
function adicionarProrrogacao($idProjeto) {
	$database = JFactory::getDBO();
	$projetopd = identificarProjetopdID($idProjeto);

	$id = JRequest::getVar('id');
	$dataTermino = dataSql(JRequest::getVar('data'));
	$justificativa = JRequest::getVar('justificativa');	
	$dataHoje = date("Y-m-d");

	$sql = "INSERT INTO #__contproj_prorrogacoes (projeto_id, dataPedido, dataTermino, justificativa) VALUES ('$idProjeto','$dataHoje', '$dataTermino','$justificativa' )";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	// ATUALIZAR DATA DE TERMINO DO PROJETO
	$sqlUpdate = "UPDATE #__contproj_projetos SET data_fim_alterada = '$dataTermino' WHERE id = '".$idProjeto."'";
	$database->setQuery($sqlUpdate);
	$foi = $database->Query();

	if ($funcionou) {
		JFactory::getApplication()->enqueueMessage(JText::_('Prorrogação salva com sucesso!'));
		mostrarTelaProrrogacao($projetopd);
	} else { 
		JError::raiseWarning( 100, 'ERRO: Solicitação não enviada.' );
		adicionarProrrogacao($idProjeto);
	}

}
?>

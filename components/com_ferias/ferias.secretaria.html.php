<link rel="stylesheet" type="text/css" href="components/com_reserva/assets/css/estilo.css" />
<script type="text/javascript" src="components/com_reserva/assets/js/jquery.js"></script>

<?php
$document = &JFactory::getDocument();

function listFeriasProfessores($professor, $ano) {

	$Itemid = JRequest::getInt('Itemid', 0);
	$database	=& JFactory::getDBO();

	if($ano == "") {
        $ano = (int)date("Y");
    }
    $sql = "SELECT * FROM #__professores WHERE icomp = 1 ORDER BY nomeProfessor";
    $database->setQuery( $sql );
    $professores = $database->loadObjectList();

    $sql = "SELECT * FROM #__funcionarios ORDER BY nome";
    $database->setQuery( $sql );
    $funcionarios = $database->loadObjectList();
	
    $sql = "SELECT YEAR(dataSaida) as ano FROM #__ferias group by (YEAR(dataSaida)) ORDER BY ano DESC";
    $database->setQuery( $sql );
    $anos = $database->loadObjectList();
    ?>
    
	<script type="text/javascript">
		function deletar(form) {
			var idSelecionado = 0;
		    
			for(i = 0;i < form.idPedidoSelec.length;i++)
				if(form.idPedidoSelec[i].checked) idSelecionado = form.idPedidoSelec[i].value;
        
			if(idSelecionado > 0) {
				form.task.value = 'deleteFerias';
				form.idFerias.value = idSelecionado;
				form.submit();
           } else {
				alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o.')
           }
        }
		
		function adicionar(form) {
			form.task.value = 'addFerias';
			form.submit();
        }
    </script>
    
    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
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
				form.task.value = 'removeFerias';
				form.idFerias.value = id;
				form.submit();
			} else
				alert("Exclusão cancelada");	
		}
	</script>

<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_ferias&Itemid=<?php echo $Itemid;?>" method="post">
	
        <!-- FILTRO DA BUSCA -->
        <fieldset>
            <legend>Selecione o ano:</legend>
            <select name="fecha">
				<?php
							$anoAtual = date("Y");
							echo "<option value='$anoAtual'>$anoAtual</option>";

							foreach($anos as $anoCombo){
								$valor = $anoCombo->ano;

               ?>
                    <option value="<?php echo $valor;?>"
                    <?php
                    if($valor == $ano)
                        echo 'SELECTED';
                    ?>>
                        <?php echo $valor;?>
                    </option>
                    <?php } ?>
            </select>
 
            <button type="submit" name="buscar" class="btn btn-primary pull-right" title="Filtra busca">
            	<i class="icone-search icone-white"></i> Consultar
            </button>            
        </fieldset>
		
	<div id="toolbar-box">
		<div class="m">
			<div class="toolbar-list" id="toolbar">
				<div class="cpanel2">
					<!--<div class="icon" id="toolbar-back">
						<a href="index.php?option=com_ferias&Itemid=<?php echo $Itemid;?>"><span class="icon-32-back"></span>Voltar</a>
					</div>-->
				</div>
				<div class="clr"></div>
			</div>
			<div class="pagetitle icon-48-inbox">
				<h2>Solicita&#231;&#245;es de Férias de Professores</h2>
			</div>
		</div>
	</div>
	
	<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters" class="tabela">
		<thead>
			<tr bgcolor="#002666">
				<th align="center">Professor</th>
				<th align="center">Férias Oficiais</th>
				<th align="center">Usufruto de Férias</th>
				<th>Opções</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$table_bgcolor_even="#e6e6e6";
			$table_bgcolor_odd="#FFFFFF";
			$tipos = array(1 => 'Usufruto', 2 => 'Oficial');

			$i = 0;
			
			foreach( $professores as $item ) {
			
				$sql = "SELECT id, nomeusuario, SUM(DATEDIFF(dataretorno, datasaida)+1) as total FROM #__ferias WHERE YEAR(datasaida) = $ano AND tipo = 1 AND idusuario = $item->idUser GROUP BY idusuario";
				$database->setQuery( $sql );
				$usufrutoFerias = $database->loadObjectList();
				
				$sql = "SELECT id, nomeusuario, SUM(DATEDIFF(dataretorno, datasaida)+1) as total FROM #__ferias WHERE YEAR(datasaida) = $ano AND tipo = 2 AND idusuario = $item->idUser GROUP BY idusuario";
				$database->setQuery( $sql );
				$feriasOficiais = $database->loadObjectList();

				$i = $i + 1;
				if ($i % 2) {
					echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
				} else {
					echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
				}
				?>
                <td><?php echo $item->nomeProfessor;?></td>
                <td><?php if($feriasOficiais) echo $feriasOficiais[0]->total; else echo '0';?></td>
				<td><?php if($usufrutoFerias) echo $usufrutoFerias[0]->total; else echo '0';?></td>
				<td><a href="javascript:document.form.task.value='viewFeriasProfessor';document.form.idProfessor.value='<?php echo $item->idUser; ?>';document.form.submit()" title="Detalhar">
                    <span class="label label-info"><i class="icone-search icone-white"></i></span>
                    </a>
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	<div id="toolbar-box">
		<div class="m">
			<div class="toolbar-list" id="toolbar">
				<div class="cpanel2">
					<!--<div class="icon" id="toolbar-back">
						<a href="index.php?option=com_ferias&Itemid=<?php echo $Itemid;?>"><span class="icon-32-back"></span>Voltar</a>
					</div>-->
				</div>
				<div class="clr"></div>
			</div>
			<div class="pagetitle icon-48-inbox">
				<h2>Solicita&#231;&#245;es de Férias de Funcionários</h2>
			</div>
		</div>
	</div>
	
	<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters" class="tabela">
		<thead>
			<tr bgcolor="#002666">
				<th align="center">Funcionário</th>
				<th align="center">Férias Oficiais</th>
				<th align="center">Usufruto de Férias</th>
				<th>Opções</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$table_bgcolor_even="#e6e6e6";
			$table_bgcolor_odd="#FFFFFF";
			$tipos = array(1 => 'Usufruto', 2 => 'Oficial');

			$i = 0;
			
			foreach( $funcionarios as $item ) {
			
				$sql = "SELECT id, nomeusuario, SUM(DATEDIFF(dataretorno, datasaida))+1 as total FROM #__ferias WHERE YEAR(datasaida) = $ano AND tipo = 1 AND idusuario = $item->idUser GROUP BY idusuario";
				$database->setQuery( $sql );
				$usufrutoFerias = $database->loadObjectList();

				$sql = "SELECT id, nomeusuario, SUM(DATEDIFF(dataretorno, datasaida))+1 as total FROM #__ferias WHERE YEAR(datasaida) = $ano AND tipo = 2 AND idusuario = $item->idUser GROUP BY idusuario";
				$database->setQuery( $sql );
				$feriasOficiais = $database->loadObjectList();

				$i = $i + 1;
				if ($i % 2) {
					echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
				} else {
					echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
				}
				?>
                <td><?php echo $item->nome;?></td>
                <td><?php if($feriasOficiais) echo $feriasOficiais[0]->total; else echo '0';?></td>
				<td><?php if($usufrutoFerias) echo $usufrutoFerias[0]->total; else echo '0';?></td>
				<td><a href="javascript:document.form.task.value='viewFeriasProfessor';document.form.idProfessor.value='<?php echo $item->idUser; ?>';document.form.submit()" title="Detalhar">
                    <span class="label label-info"><i class="icone-search icone-white"></i></span>
                    </a>
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>	
	<input name='task' type='hidden' value='listFeriasProfessores' /> 
	<input name='idProfessor' type='hidden' value='' />

</form>

<?php

}

function listFeriasProfessor($user, $ano) {

	$Itemid = JRequest::getInt('Itemid', 0);
	$database	=& JFactory::getDBO();

	if($ano == "") {
        $ano = (int)date("Y");
    }
    $sql = "SELECT id,nomeusuario, DATE_FORMAT(dataPedido,'%d/%m/%Y') as dataPedido, DATE_FORMAT(dataSaida,'%d/%m/%Y') as dataSaida, DATE_FORMAT(dataRetorno,'%d/%m/%Y') as dataRetorno, DATEDIFF(dataretorno, datasaida)+1 AS diferenca, tipo FROM #__ferias WHERE YEAR(datasaida) = $ano AND idusuario = $user->id ORDER BY dataPedido DESC";

    $database->setQuery( $sql );
    $ferias = $database->loadObjectList();

    $sql = "SELECT YEAR(dataSaida) as ano FROM #__ferias WHERE idusuario = $user->id group by (YEAR(dataSaida)) ORDER BY ano DESC";
    $database->setQuery( $sql );
    $anos = $database->loadObjectList();
    ?>
    
	<script type="text/javascript">
		
		function adicionar(form) {
			form.task.value = 'addFeriasSecretaria';
			form.submit();
        }
    </script>
    
    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
		<link rel="stylesheet" type="text/css" href="components/com_reserva/assets/css/estilo.css" />
	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/estilo.css" /> <!-- TABELA -->
    
    <!-- SCRIPTS -->
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
    <script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();
		});
		
		function excluir(id, idProfessor){
			if (confirm("Deseja realmente exluir o registro?")) {
				form.task.value = 'removeFeriasSecretaria';
				form.idFerias.value = id;
				form.submit();
			} else
				alert("Exclusão cancelada");	
		}
	</script>

<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_ferias&Itemid=<?php echo $Itemid;?>" method="post">
	<div id="toolbar-box">
		<div class="m">
			<div class="toolbar-list" id="toolbar">
				<div class="cpanel2">
					<div class="icon" id="toolbar-new">
						<a href="javascript:adicionar(document.form)"> <span class="icon-32-new"></span>Nova</a>
					</div>
					<div class="icon" id="toolbar-back">
						<a href="index.php?option=com_ferias&task=listFeriasProfessores&Itemid=<?php echo $Itemid;?>"><span class="icon-32-back"></span>Voltar</a>
					</div>
				</div>
				<div class="clr"></div>
			</div>
			<div class="pagetitle icon-48-inbox">
				<h2>Férias de <?php echo $user->name;?></h2>
			</div>
		</div>
	</div>
        <!-- FILTRO DA BUSCA -->
        <fieldset>
			<legend>Ano selecionado: <?php echo $ano;?></legend>
        </fieldset>
	
	<table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters" class="tabela">
		<thead>
			<tr bgcolor="#002666">
				<th width="15%" align="center">Data do Pedido</th>
				<th width="30%" align="center">Professor / Funcionário</th>
				<th width="10%" align="center">In&#237;cio</th>
				<th width="10%" align="center">T&#233;rmino</th>
				<th width="10%" align="center">Tipo</th>
				<th width="3%">Opções</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$table_bgcolor_even="#e6e6e6";
			$table_bgcolor_odd="#FFFFFF";
			$tipos = array(1 => 'Usufruto', 2 => 'Oficial');
			$usufruto = 0;
			$oficial = 0;

			$i = 0;
			
			if(!$ferias){ 
				echo "<tr><td colspan='7' align='center'>Não há registro de férias para o ano informado</td></tr>";
			}
			else{

			foreach( $ferias as $item ) {
				$i = $i + 1;
				if ($i % 2) {
					echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");
				} else {
					echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
				}
				if($item->tipo == 1) $usufruto += $item->diferenca;
				if($item->tipo == 2) $oficial += $item->diferenca;
				?>
                <td><?php echo $item->dataPedido;?></td>
                <td><?php echo $item->nomeusuario;?></td>
                <td><?php echo $item->dataSaida;?></td>
                <td><?php echo $item->dataRetorno;?></td>
				<td><?php echo $tipos[$item->tipo];?></td>
				<td><a href="javascript:document.form.task.value='deleteFeriasSecretaria';document.form.idFerias.value='<?php echo $item->id; ?>';document.form.submit()" title="Remover Férias">
                    <span class="label label-important"><i class="icone-minus icone-white"></i></span>
                    </a>
				</td>
			</tr>
			<?php
			}
			}
			?>
		</tbody>
	</table>
	<ul>
	<li><b>Total de dias de férias oficiais:</b> <?php echo $oficial;?></li>
	<li><b>Total de dias de usufruto de férias:</b> <?php echo $usufruto;?></li>
	<li><b>Dias restantes de usufruto de férias:</b> <?php echo $oficial-$usufruto;?></li>
	</ul>
	
	
	<input name='task' type='hidden' value='listFerias' /> 
	<input name='idFerias' type='hidden' value='' />
	<input name='fecha' type='hidden' value='<?php echo $ano;?>' />
	<input name='idProfessor' type='hidden' value='<?php echo $user->id;?>' />

</form>

<?php
}

function addFeriasSecretaria($user, $ano) {
	$Itemid = JRequest::getInt('Itemid', 0); ?>    
    
    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
    <link rel="stylesheet" type="text/css"href="components/com_portalprofessor/calendar-jos.css">
    <link type="text/css" rel="stylesheet" href="components/com_portalaluno/jquery-ui-1.8.20.custom.css" />
    <script src="components/com_ferias/jquery/js/jquery.validate.js" type="text/javascript"></script> 
	<link href="components/com_ferias/jquery/css/validate.css" type="text/css" media="screen" rel="stylesheet" />
	<script src="components/com_ferias/jquery/js/jquery.min.js" type="text/javascript"></script>
    
<!--    <script src="components/com_portalaluno/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="components/com_portalaluno/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>    -->
    
    <script language="JavaScript" charset="ISO-8859-1">
       
		function voltar(form) {        
			form.task.value = 'viewFeriasProfessor';
		   form.submit();
		   
        }	
		function ValidateForm(form) {        
           var erro = false;
		   form.submeter.click();
		   
        }		
    </script>
 <!--   <script>
	$(function() {
	  $( "#datasaida" ).datepicker({ 
	  dateFormat: "dd/mm/yy",
         appendText: ' (mm/dd/yyyy)',
                changeMonth: true,
                changeYear: true,
                selectOtherMonths: true,
                showOtherMonths: true,

	  onClose: function() {
                    this.focus();
                }
  	});
	});
    </script>
    <script>
	$(function() {
	  $( "#dataretorno" ).datepicker({ 
	  dateFormat: "dd/mm/yy",
         appendText: ' (mm/dd/yyyy)',
                changeMonth: true,
                changeYear: true,
                selectOtherMonths: true,
                showOtherMonths: true,

	  onClose: function() {
                    this.focus();
                }
  	});
	});
    </script>    -->
    
    <form method="post" id="f1" class="validate" name="form" action="index.php?option=com_ferias&Itemid=<?php echo $Itemid;?>" autocomplete="on">
    
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
            	<div class="icon" id="toolbar-send">
                    <a href="javascript: ValidateForm(document.form)" class="toolbar">
                    <span class="icon-32-send"></span>Enviar</a>
	            </div>
                
            	<div class="icon" id="toolbar-back">
		            <a href="javascript: voltar(document.form)">
		            <span class="icon-32-back"></span>Voltar</a>
	            </div>
            
    	        </div>
        	    <div class="clr"></div>
            	</div>
                
	            <div class="pagetitle icon-48-writemess">
    	    	    <h2>Registro de Férias</h2>
        	    </div>
            </div>
		</div>
    
        <hr />

		<p>Preencha os campos abaixo para registrar um pedido de férias.</p>
        <p><span class="aviso">Importante</span> Todos os campos devem ser preenchidos</p>

        <hr />
    
        <table border="0" cellpadding="1" cellspacing="2" width="80%" class="table-form">
            <tbody>
                <tr>
                    <td width="20%"><b>Usu&#225;rio Selecionado</b></td>
                    <td width="80%"><?php echo $user->name;?></td>
                </tr>
                <tr>
                    <td><b>E-mail de Origem</b></td>
                    <td><?php echo $user->email;?></td>
                </tr>
                <tr>
                    <td><b>Tipo de Férias</b></td>
                    <td><input name="tipo" value="1" type="radio" required> Usufruto
                        <input name="tipo" value="2" type="radio" required> Oficial
                    </td>
                </tr>
                <tr>
                    <td><b>Data de In&#237;cio (dd/mm/aaaa)</b></td>
                    <td><p><input type="text" name="datasaida" id="datasaida" class="required data" maxlength="10"><span>Campo requerido, informe uma data v&#225;lida</span></p></td>
                </tr>
                <tr>
                    <td><b>Data de T&#233;rmino (dd/mm/aaaa)</b></td>
                    <td><p><input type="text" name="dataretorno" id="dataretorno" class="required data" maxlength="10"><span>Campo requerido, informe uma data v&#225;lida</span></p></td>
                </tr>
            </tbody>
        </table>        
        <br />
        
        <input type="submit" value="Submeter" name="submeter" style="display:none">
        <input name='task' type='hidden' value='saveFeriasSecretaria' />    
		<input name='fecha' type='hidden' value='<?php echo $ano;?>' />    
		<input name='idProfessor' type='hidden' value='<?php echo $user->id;?>' />    
    </form>
    
<?php }

?>
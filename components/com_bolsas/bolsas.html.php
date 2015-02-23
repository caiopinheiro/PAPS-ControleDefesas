<link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/estilo.css">
<script type="text/javascript" src="components/com_bolsas/assets/js/jquery.js"></script>

<!-- MÁSCARA PARA MOEDA -->
<script type="text/javascript" src="components/com_bolsas/assets/js/mascara-moeda.js"></script>

<?php
function listarOpcoesBolsa() {
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
    <script type="text/javascript" src="components/com_bolsas/assets/js/colapsavel/colapsavel2.js"></script>    
    <link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css" />

	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
        <div class="cpanel2">
        </div>
		<div class="clr"></div>
		</div>
        <div class="pagetitle icon-48-search2"><h2>Gestão de Bolsas de Pesquisa</h2></div>
    </div></div>
    
    <!-- AVISO DE EXPIRAÇÃO DE BOLSA (30 DIAS) -->
    <?php 
	$database = JFactory::getDBO();
	
 	$hoje = date("Y-m-d");
	
	$sql = "SELECT *
			FROM #__bolsas_aloc
			WHERE DATEDIFF(dataTermino, '$hoje' ) < 30
			AND DATEDIFF(dataTermino, '$hoje' ) > 0
			ORDER BY dataTermino ASC";
    $database->setQuery($sql);
    $bolsas_aloc = $database->loadObjectList();
	
	if ($bolsas_aloc) { ?>
    
    <dl id="jfaq2" title="Expandir | Esconder">
        <dt>
        <legend><span class="label label-important">AVISO Bolsas de Pesquisa próximas do fim</span></legend>
        </dt>
            
        <dd>
        <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>Agência</th>
                <th>Data Início</th>
                <th>Data Término</th>                        
                <th>Bolsista</th>
                <th>Categoria</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        foreach ($bolsas_aloc as $bolsas) { 
            $idAluno = $bolsas->idAluno;		
            $bolsas_exp = identificarBolsista($idAluno); ?>
                    
                <tr>            
                    <td></td>
                    <td><?php echo $bolsas_exp->agencia; ?></td>
                    <td><?php echo dataBr($bolsas_exp->dataInicio); ?></td>
                    <td><?php echo dataBr($bolsas_exp->dataTermino); ?></td>
                    <td><?php echo $bolsas_exp->nome; ?></td>
                    <td><?php echo ucfirst($bolsas_exp->categoria); ?></td>
                </tr>  
        
        <?php } ?>
        </tbody>
    	</table>
        </dd>
	</dl>		
    
    <?php } ?>

	<div class="cpanel">
		<div class="icon-wrapper">
			<div class="icon">
            	<a href="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>&task=gerenciarBolsas">
           		<img src="components/com_bolsas/assets/img/icon-48-category-add.png">
                <span><b>Gerenciar Bolsas</b></span></a>
			</div>
		</div>
        
		<div class="icon-wrapper">
    		<div class="icon">
        		<a href="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>&task=gerenciarAlocacao">
           		<img src="components/com_bolsas/assets/img/icon-48-contacts-categories.png">
                <span><b>Gerenciar Alocação</b></span></a>
			</div>
		</div>
        
        <div class="icon-wrapper">
			<div class="icon">
            	<a href="index.php?option=com_bolsas&Itemid=<?php echo $Itemid;?>&task=historico">
           		<img src="components/com_bolsas/assets/img/icon-48-stats.png">
                <span><b>Histórico de Alocação</b></span></a>
			</div>
		</div>   
                    
	</div>
    
	
<?php } ?>
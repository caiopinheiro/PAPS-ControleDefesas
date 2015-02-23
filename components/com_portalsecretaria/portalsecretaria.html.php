<?php
$document = &JFactory::getDocument();
$document->addScript("includes/js/joomla.javascript.js");

class HTML_secretariaDCC {

function listarServicos() {    
    $Itemid = JRequest::getInt('Itemid', 0); ?>
    
	<!--<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">-->

    <script type="text/javascript">	
		alerta = function(){
			var $alerta = $("#alerta");
			$alerta.slideDown(500);
		
			window.setTimeout(function() {
				$alerta.slideUp(500);
			}, 10000);
		}
		
		$alerta.click(function(){
			$alerta.slideUp(500);
		});
	</script>

	<h3>Servi&#231;os Oferecidos para a Secretaria do IComp/PPGI</h3>
    <hr />
    <br />
    
	<?php 	
		$database =& JFactory::getDBO();		
		$sqlPro = "SELECT * FROM #__prorrogacoes WHERE status = '1'";
		$database->setQuery($sqlPro);
		$prorrogacoes = $database->loadObjectList();

		$sqlTranc = "SELECT * FROM #__trancamentos WHERE status = '1'";
		$database->setQuery($sqlTranc);
		$trancamentos = $database->loadObjectList();		
		?>
        
		<div id="alerta">
            <?php 
			if ($prorrogacoes) { 
				$qtde1 = count($prorrogacoes);
				echo 'Prorrogação de Prazo: '.$qtde1.'<br>';
            }
            
            if ($trancamentos) {
				$qtde2 = count($trancamentos);
				echo 'Trancamento de Prazo: '.$qtde2.'<br>';
            } ?>
		</div>
        
    	<div class="cpanel">
			<div class="icon-wrapper">
				<div class="icon">
           			<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=professores">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/professor.png"><span><?php echo JText::_( 'Gerenciar Professores' ); ?></span></a>
				</div>
			</div>
            
            <div class="icon-wrapper">
				<div class="icon">
           			<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=funcionarios">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/funcionario.png"><span><?php echo JText::_( 'Gerenciar Funcionários' ); ?></span></a>
				</div>
			</div>
			
            <!-- MENU DE SELEÇÃO DOS CANDIDATOS -->
            <div class="icon-wrapper">
				<div class="icon">
                	<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=avaliarcandidatos">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/aprovarcandidatos.png"><span><?php echo JText::_( 'Sele&#231;&#227;o de Candidatos do PPGI' ); ?></span></a>
				</div>
			</div>

            <div class="icon-wrapper">
				<div class="icon">
                	<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=candidatosacre">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/aprovarcandidatos.png"><span><?php echo JText::_( 'Sele&#231;&#227;o de Candidatos do Curso do Acre' ); ?></span></a>
				</div>
			</div>
			
			<div class="icon-wrapper">
				<div class="icon">
                	<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=listarmatriculas">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/listarmatriculas.jpg"><span><?php echo JText::_( 'Matr&#237;culas de Alunos do PPGI'); ?></span></a>
				</div>
			</div>
            
			<div class="icon-wrapper">
				<div class="icon">
			    	<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=listarnotificacoes">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/notificacaosaida.gif"><span><?php echo JText::_( 'Solicita&#231;&#245;es de Afastamento de Professores' ); ?></span></a>
				</div>
			</div>
            
			<div class="icon-wrapper">
				<div class="icon">
                	<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=listarturmas)">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/aula.png"><span><?php echo JText::_( 'Gerenciar Turmas por Per&#237;odo'); ?></span></a>
				</div>
			</div>
            
			<div class="icon-wrapper">
				<div class="icon">
			    	<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=aproveitamentos">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/historico.gif"><span><?php echo JText::_( 'Aproveitamentos de Disciplina' ); ?></span></a>
				</div>
			</div>
            
			<div class="icon-wrapper">
				<div class="icon">
                	<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=alunos">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/cadastraraluno.png"><span><?php echo JText::_( 'Gerenciar Alunos do PPGI' ); ?></span></a>
				</div>
			</div>
            
			<div class="icon-wrapper">
				<div class="icon">
                	<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=disciplinas">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/cursos.png"><span><?php echo JText::_( 'Gerenciar Disciplinas do PPGI' ); ?></span></a>
				</div>
			</div>
            
			<div class="icon-wrapper">
				<div class="icon">
                <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=periodo">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/periodo.png"><span><?php echo JText::_( 'Gerenciar Per&#237;odos do PPGI' ); ?></span></a>
				</div>
			</div>
            
			<div class="icon-wrapper">
				<div class="icon">
                	<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=bolsas">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/bolsa.png"><span><?php echo JText::_( 'Gerenciar Bolsas de Estudo do PPGI' ); ?></span></a>
				</div>
			</div>
			
            <!-- DIV CONSULTA DE JUBILAMENTO -->
			<div class="icon-wrapper">
				<div class="icon">
                	<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=jubilamento">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/jubilamento.png"><span><?php echo JText::_( 'Consulta para Jubilamento' ); ?></span></a>
				</div>
			</div>
            
            <!-- DIV PRORROGAÇÃO DE PRAZO -->
			<div class="icon-wrapper">
				<div class="icon">
                	<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=prorrogacao">
           			<img width="32" height="32" border="0" src= "components/com_portalsecretaria/images/prorrogacao.png"><span><?php echo JText::_( 'Prorrogação de Prazo' ); ?></span></a>
				</div>
			</div>  
            
            <!-- DIV TRANCAMENTO DE CURSO -->
			<div class="icon-wrapper">
				<div class="icon">
                	<a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=trancamento">
           			<img width="32" height="32" border="0" src= "components/com_portalaluno/images/trancamento.png"><span><?php echo JText::_( 'Trancamento de Curso' ); ?></span></a>
				</div>
			</div> 
            
            <div class="icon-wrapper">
				<div class="icon">
                <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=solicitacao">
           			<img width="32" height="32" border="0" src="components/com_portalaluno/images/autorizacao.png"><span><?php echo JText::_( 'Solicita&#231;&#227;o de Entrada' ); ?></span></a>
				</div>
			</div>      
 
			<div class="icon-wrapper">
				<div class="icon">
                <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=membrosbanca">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/funcionario.png"><span><?php echo JText::_( 'Membros  de Banca' ); ?></span></a>
				</div>
			</div>      
			
			<div class="icon-wrapper">
				<div class="icon">
                <a href="index.php?option=com_controledefesas">
           			<img width="32" height="32" border="0" src="components/com_portalsecretaria/images/controle.png"><span><?php echo JText::_( 'Controle Defesas PPGI' ); ?></span></a>
				</div>
			</div>
			
			
        </div>
     <?php
 }

// SOLICITAÇÕES DE AFASTAMENTOS
function listarNotificacoes($notificacoes, $mes) {

    $Itemid = JRequest::getInt('Itemid', 0);
    
    $database	=& JFactory::getDBO();
    $sql = "SELECT MONTH(dataenvio) as mes, YEAR(dataenvio) as ano FROM #__notificacoes_saida group by CONCAT_WS('/',MONTH(dataenvio),YEAR(dataenvio)) ORDER BY ano DESC, mes DESC";
    $database->setQuery( $sql );
    $meses = $database->loadObjectList(); ?>

	<script type="text/javascript" src="components/com_inscricaoppgi/jquery.js"></script>
    <script type="text/javascript" src="components/com_inscricaoppgi/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();
		});
	</script>
    
	<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />

    
    <form method="post" name="form" action="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
    
    	<div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-back">
                    <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>">
                    	<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?>
                    </a>
                </div>
            </div>
            <div class="clr"></div>
            </div>
        
            <div class="pagetitle icon-48-inbox"><h2>Solicita&#231;&#245;es de Afastamento Enviadas</h2></div>
        </div></div>
    
    	<p>
        Selecione o m&#234;s     
        <select name="fecha" style="width:120px;">
        <?php
             foreach($meses as $mesCombo){
                   $valor = $mesCombo->mes ."/".$mesCombo->ano;
                   if($mesCombo->mes < 10)
                      $valor = "0". $valor;
    
             ?>   <option value="<?php echo $valor;?>" <?php
             if($valor == $mes)
                   echo 'SELECTED';
             ?>
             > <?php echo $valor;?></option>
        <?php } ?>
        </select>
       
        <button type="submit" name="buscar" class="btn btn-primary" title="Filtra busca" style="margin-top:-8px;">
            <i class="icone-search icone-white"></i> Consultar
        </button>
        </p>
                
        
        <table class="table table-striped" id="tablesorter-imasters">
            <thead class="head-one">
              <tr>
                <th></th>
                <th>Pedido</th>
                <th>Data do Envio</th>
                <th>Professor</th>
                <th>Sa&#237;da</th>
                <th>Retorno</th>
                <th>Local</th>
                <th>Tipo da Viagem</th>
              </tr>
            </thead>
             
            <tbody>
         
            <?php        
            $tipoViagem = array (1 => "Nacional",2 => "Internacional");
            
            foreach ($notificacoes as $notificacao) {            
                $anoEnvio = substr($notificacao->dataenvio,2,2);
                $mesEnvio = substr($notificacao->dataenvio,5,2);
                $diaEnvio = substr($notificacao->dataenvio,8,2);
                $horaEnvio = substr($notificacao->dataenvio,11,2);
                $minutoEnvio = substr($notificacao->dataenvio,14,2); ?>
    
            <tr>
                <td>
                    <div align="center">
                        <a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>&task=vernotificacao&idPedido=<?php echo $notificacao->id;?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;"><img border='0' src='images/b_view.png' width='16' height='16' title='visualizar'>
                        </a>
                    </div>
                </td>
                <td><div align="center"><?php echo $notificacao->id;?></div></td>
                <td><?php echo $notificacao->dataenvio;?></td>
                <td><?php echo $notificacao->nomeusuario;?></td>
                <td><?php echo $notificacao->datasaida;?></td>
                <td><?php echo $notificacao->dataretorno;?></td>
                <td><?php echo $notificacao->local;?></td>
                <td><?php echo $tipoViagem[$notificacao->tipo];?></td>
            </tr>    
            <?php } ?>
        </tbody>
        </table>
        
        <input name='task' type='hidden' value='listarnotificacoes' />
        
    </form>

<?php }
	}
?>

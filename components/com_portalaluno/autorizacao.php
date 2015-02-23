<link rel="stylesheet" type="text/css" href="components/com_controleprojetos/template.css" /> <!-- BARRA DE OPÇÕES -->
<link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/estilo.css">

<?php // CADASTRAR SOLICITACAO DE ENTRADA : TELA 
function telaSolicitacao ($aluno) { 
    $Itemid = JRequest::getInt('Itemid', 0);   ?>

	<script type="text/javascript" src="components/com_portalaluno/jquery-1.7.2.min.js"></script>
    
    <!-- SCRIPTS -->    
    <script type="text/javascript">
		function ValidateForm(form) {        
           var erro = false;
		   
		   form.submeter.click();
		}
		
		function abrirSolicitacao(idSolic,idAluno) {
			window.open("index.php?option=com_portalaluno&Itemid=191&task=imprimirSolicitacao&item="+idSolic+"&al="+idAluno,"_blank","toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50");
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
    
    <!-- MÁSCARAS -->
	<script type="text/javascript" src="components/com_reserva/assets/js/maskedInput.js"></script>
    <script type="text/javascript">
        $(function($){
            $(".data").mask("99/99/9999");
        });
    </script>
    
	<!-- CALENDÁRIO -->
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/default.css"/>
    <link rel="stylesheet" type="text/css" href="components/com_bolsas/assets/css/calendario/jquery.click-calendario-1.0.css"/>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/jquery.click-calendario-1.0-min.js"></script>
    <script type="text/javascript" src="components/com_bolsas/assets/js/calendario/script.js"></script>
    
    <style>
		.data{
			width:190px;
		}
	

		label:before {  
			content: "";  
			display: inline-block;  
			
			width: 16px;  
			height: 16px;  
			
			margin-right: 10px;  
			position: absolute;  
			left: 0;
			bottom: 1px;  
			background-color: #aaa;  
			box-shadow: inset 0px 2px 3px 0px rgba(0, 0, 0, .3), 0px 1px 0px 0px rgba(255, 255, 255, .8);  
		}
	</style>
    
	<form method="post" name="formPedido" action="index.php?option=com_portalaluno&Itemid=<?php echo $Itemid;?>" class="form-horizontal">
            
        <!-- BARRA DE FERRAMENTAS -->          
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">        
                <div class="icon" id="toolbar-save">
                    <a href="javascript: ValidateForm(document.formPedido)" class="toolbar">
                    <span class="icon-32-save"></span><?php echo JText::_( 'Salvar' ); ?></a>
                </div>
                
                <div class="icon" id="toolbar-cancel">
                    <a href="index.php?option=com_portalaluno&idAluno=<?php echo $aluno->id; ?>&Itemid=<?php echo $Itemid;?>&task=servicos">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                </div>
            </div>
            
            <div class="clr"></div>            
            </div>
        
            <div class="pagetitle icon-48-contact-categories">
                <h2>Solicitação de Entrada</h2>
            </div>
        </div></div>
    
        <span class="label label-info">Informações</span>
        <ul>
            <li>Os campos com <span class="obrigatory"> * </span> são de preenchimento obrigatório</li>
            <li>A escolha do tipo <span class="label">permanência</span>, permite ao aluno ficar após às 22 horas</li>
        </ul>
        <hr />
    
    	<table class="table-form">            
            <tbody>
            	<tr>
                	<td>Tipo <span class="obrigatory">*</span></td>
                    <td><div class="checkbox">
						<input type="radio" name="tipo" value="Entrada" required="required"/>Entrada<br />
						<input type="radio" name="tipo" value="Permanência" required="required" />Permanência
                    </div></td>
                </tr>
                <tr>
                	<td>Período</td>
                    <td><input type="text" name="dataInicio" id="dataInicio" class="data imgcalendar" required="required" placeholder="Data Inicial" />
	                    <input type="text" name="dataTermino" id="dataTermino" class="data imgcalendar" placeholder="Data Final" />
                        <span class="help-block" style="margin:2px 0 5px 0;">
                        	<span class="label label-warning">Lembrete</span> Apenas a Data Inicial é obrigatória
                        </span>
                    </td>
                </tr>
                <tr>
                	<td>Sala <span class="obrigatory">*</span></td>
					<td><input type="text" name="sala" id="sala" placeholder="Sala" size="100"/></td>					
                </tr>
                <tr>
                	<td>Motivo <span class="obrigatory">*</span></td>
                    <td><textarea name="motivo" rows="4" cols="100" required="required"></textarea></td>
                </tr>
            </tbody>
        </table>        
        
		<input type="submit" value="Submeter" name="submeter" style="display:none" />      
		<input name="task" type="hidden" value="addSolicitacao" />
		<input type="hidden" name="idAluno" value="<?php echo $aluno->id; ?>" />        
    </form>    
    
<?php } ?>


<?php // CADASTRAR SOLICITACAO DE ENTRADA : SQL
function salvarSolicitacao($aluno) {
	$database =& JFactory::getDBO();
	
	$tipo = JRequest::getVar('tipo');
	$data = dataSql(JRequest::getVar('data'));
	$data1 = JRequest::getVar('dataInicio');
	$dataInicio = dataSql($data1);
	$data2 = JRequest::getVar('dataTermino');
	$dataTermino = dataSql($data2);
	$sala = JRequest::getVar('sala');
	$motivo = JRequest::getVar('motivo');
	
	if (empty($data2))
		$dataTermino = $dataInicio;
	
	$sql = "INSERT INTO `#__solic_entrada` (`id`, `idAluno`, `tipo`, `dataInicio`, `dataTermino`, `sala`, `motivo`) VALUES ('', '$aluno->id', '$tipo', '$dataInicio', '$dataTermino', '$sala', '$motivo');";
	$database->setQuery($sql);
	$funcionou = $database->Query();
	
	$query = "SELECT MAX(id) as id FROM #__solic_entrada";
	$database->setQuery($query);
	$dados = $database->loadObjectList();
	$idSolic = $dados[0]->id;
	
	$solic = identificarSolicitacao($idSolic);
	$arquivoHTML = gerarArquivo($solic, $aluno);
	//enviarEmail3($idSolic, $aluno, $tipo, $dataInicio, $dataTermino, $motivo, $arquivoHTML);

	if($funcionou){
		echo '<div class="alert alert-success">
			<b>CADASTRO :</b> Solicitação de entrada enviada com sucesso!<br />
			Imprima sua solicitação e entregue na Secretaria com a assinatura do <b>Diretor do Instituto</b>
			
			<a href="javascript: abrirSolicitacao('.$idSolic.','.$aluno->id.')">
			<button class="btn btn-small" type="button" style="margin-left:60px;" title="Clique para imprimir"><i class="icone-print"></i> Imprimir Solicitação</button>
			</a>
		  </div>';
	} else {
		echo '<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<b>CADASTRO :</b> Não foi possível enviar sua solicitação, tente novamente!
		  </div>';
	}
	
	telaSolicitacao($aluno);
}
?>


<?php // IMPRIMIR SOLICITAÇÃO
function telaImpressaoSolic($solic, $aluno) {
	global $mosConfig_lang;
    $database =& JFactory::getDBO();

	$curso = array (1 => "Mestrado",2 => "Doutorado",3 =>"Especial");
	
	$complemento = '';

	if ($solic->tipo == 'Entrada') {
		$complemento = 'entrada';
		$titulo = 'ENTRADA';
	}
		
	if ($solic->tipo == 'Permanência') {
		$complemento = 'entrada e permanência após às 22 horas';
		$titulo = 'PERMANÊNCIA';
	}
		
	$periodo = '';
	if ($solic->dataTermino <> $solic->dataInicio)
		$periodo = ' periodo  de '.dataBr($solic->dataInicio).' a '.dataBr($solic->dataTermino);
	else
		$periodo = ' dia '.dataBr($solic->dataInicio);

    $mes = date("m");
		switch ($mes){	
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

    $chave = md5($aluno->id.$aluno->nome.date("l jS \of F Y h:i:s A"));
	$comprovanteSolicitacao = "components/com_portalaluno/forms/$chave.pdf";
	$arq = fopen($comprovanteSolicitacao, 'w') or die("CREATE ERROR");

    $pdf = new Cezpdf();
    $pdf->selectFont('pdf-php/fonts/Helvetica.afm');
    $optionsCenter = array('justification'=>'center', 'spacing'=>1.5);	
    $optionsText = array('justification'=>'justify', 'spacing'=>1.5);
    $optionsLeft = array('justification'=>'left', 'spacing'=>1.5);
    $optionsRight = array('justification'=>'right', 'spacing'=>1.5);

    $pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 490, 720, 75);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 720, 100);
    $pdf->ezText('<b>PODER EXECUTIVO</b>',12,$optionsCenter);
    $pdf->ezText("".(utf8_decode("<b>MINISTÉRIO DA EDUCAÇÃO</b>"))."",10,$optionsCenter);
    $pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$optionsCenter);
    $pdf->ezText("".(utf8_decode("<b>INSTITUTO DE COMPUTAÇÃO</b>"))."",10,$optionsCenter);
    $pdf->ezText('',11,$optionsText);
    $pdf->addText(495,665,8,"<b>Data:</b> ".date("d/m/Y"),0,0);
    $pdf->addText(495,675,8,"<b>Hora:</b> ".date("H:i"),0,0);
    $pdf->setLineStyle(1);
    $pdf->line(20, 690, 580, 690);
		
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');	
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText("".(utf8_decode("<b>AUTORIZAÇÃO</b>"))."",18,$optionsCenter);
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText("".(utf8_decode("<b>REF.: LIBERAÇÃO PARA ".$titulo."</b>"))."",16,$optionsLeft);
    $pdf->ezText('');	
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText("".(utf8_decode("Eu, <b>Prof. Ruiter Braga Caldas</b>, Diretor do Instituto de Computação - IComp, autorizo a ".$complemento." do discente <b>".$aluno->nome."</b>, do Curso de ".$curso[$aluno->curso]." em Informática do PPGI/UFAM, na(o) ".$solic->sala." deste Instituto no ".$periodo."."))."",14,$optionsText);
    $pdf->ezText('');	
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');	
    $pdf->ezText("Manaus, ".date("d")." de ".$mes." de ".date("Y")."",14,$optionsRight);
    $pdf->ezText('');
		
    $pdf->line(20, 55, 580, 55);
    $pdf->addText(80,40,8,utf8_decode('Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur Virgílio Filho - CEP 69077-000 - Manaus, AM, Brasil'),0,0);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 140, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 229, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 383, 30, 8, 8);
    $pdf->addText(150,30,8,'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br',0,0);

    $pdfcode = $pdf->output();
    fwrite($arq,$pdfcode);
	fclose($arq);

	header("Location: ".$comprovanteSolicitacao);
} ?>


<?php // GERAR ARQUIVO HTML
function gerarArquivo($solic, $aluno) {
	global $mosConfig_lang;
    $database =& JFactory::getDBO();

	$curso = array (1 => "Mestrado",2 => "Doutorado",3 =>"Especial");
	
	$complemento = "";
	if ($solic->tipo == 'Entrada')
		$complemento = 'a entrada';
	else if ($solic->tipo == 'Permanência')
		$complemento = $complemento.' e permanência, após às 22 horas,';

    $mes = date("m");
		switch ($mes){	
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

    $chave = md5($aluno->id.$aluno->nome.date("l jS \of F Y h:i:s A"));
	$arquivoHTML = "components/com_portalaluno/forms/$chave.pdf";
	$arq = fopen($arquivoHTML, 'w') or die("CREATE ERROR");

    $pdf = new Cezpdf();
    $pdf->selectFont('pdf-php/fonts/Helvetica.afm');
    $optionsCenter = array('justification'=>'center', 'spacing'=>1.5);	
    $optionsText = array('justification'=>'justify', 'spacing'=>1.5);
    $optionsLeft = array('justification'=>'left', 'spacing'=>1.5);
    $optionsRight = array('justification'=>'right', 'spacing'=>1.5);

    $pdf->addJpegFromFile('components/com_portalsecretaria/images/ufam.jpg', 490, 720, 75);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/logo-brasil.jpg', 30, 720, 100);
    $pdf->ezText('<b>PODER EXECUTIVO</b>',12,$optionsCenter);
    $pdf->ezText('<b>MINISTÉRIO DA EDUCAÇÃO</b>',10,$optionsCenter);
    $pdf->ezText('<b>UNIVERSIDADE FEDERAL DO AMAZONAS</b>',10,$optionsCenter);
    $pdf->ezText('<b>INSTITUTO DE COMPUTAÇÃO</b>',10,$optionsCenter);
    $pdf->ezText('',11,$optionsText);
    $pdf->addText(495,665,8,"<b>Data:</b> ".date("d/m/Y"),0,0);
    $pdf->addText(495,675,8,"<b>Hora:</b> ".date("H:i"),0,0);
    $pdf->setLineStyle(1);
    $pdf->line(20, 690, 580, 690);
		
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');	
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('<b>AUTORIZAÇÃO</b>',18,$optionsCenter);
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('<b>REF.: LIBERAÇÃO PARA ENTRADA NO FINAL DE SEMANA</b>',14,$optionsLeft);
    $pdf->ezText('');	
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText("Eu, <b>Prof. Ruiter Braga Caldas,</b> Diretor do Instituto de Computação - IComp, autorizo ".$complemento." do discente <b>".$aluno->nome."</b>, do Curso de ".$curso[$aluno->curso]." em Informática do PPGI/UFAM no dia ".dataBr($solic->dataInicio)." ",14,$optionsText);
    $pdf->ezText('');	
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');
    $pdf->ezText('');	
    $pdf->ezText("Manaus, ".date("d")." de ".$mes." de ".date("Y")."",14,$optionsRight);
    $pdf->ezText('');
		
    $pdf->line(20, 55, 580, 55);
    $pdf->addText(80,40,8,'Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur Virgílio Filho - CEP 69077-000 - Manaus, AM, Brasil',0,0);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_telefone.jpg', 140, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_email.jpg', 229, 30, 8, 8);
    $pdf->addJpegFromFile('components/com_portalsecretaria/images/icon_casa.jpg', 383, 30, 8, 8);
    $pdf->addText(150,30,8,'Tel. (092) 3305 1193       E-mail: secretaria@icomp.ufam.edu.br        www.ppgi.ufam.edu.br',0,0);

    $pdfcode = $pdf->output();
    fwrite($arq,$pdfcode);
	fclose($arq);

	return $arquivoHTML;
} ?>


<?php // 	ENVIO DE EMAIL P/ SECRETARIA
function enviarEmail3($idSolic, $aluno, $tipo, $dataInicio, $dataTermino, $motivo, $arquivoHTML){
	$subject  = "[IComp/UFAM] Solicitacao de Entrada";

	$message .= "O(A) aluno(a) ".$aluno->nome." solicita um Autorização.\r\n\n";
	$message .= "Nome: ".$aluno->nome."\r\n";
	$message .= "E-mail: ".$aluno->email."\r\n";
	$message .= "Data para Entrada: ".$dataInicio."\r\n";
	$message .= "Data para Saï¿½da: ".$dataTermino."\r\n";
	$message .= "Motivo: ".utf8_decode($motivo)."\r\n";
	$message .= "Data e Hora do envio: ".date("d/m/Y H:i:s")."\r\n";

	$secretaria = "secretaria@icomp.ufam.edu.br";

	JUtility::sendMail($aluno->email, "Site do IComp: ".$aluno->nome, $secretaria, $subject, $message, false, $aluno->email, NULL, $arquivoHTML);
} ?>
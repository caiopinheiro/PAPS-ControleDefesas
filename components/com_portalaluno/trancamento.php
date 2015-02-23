<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
<link rel="stylesheet" href="components/com_inscricaoppgi/estilo.css" type="text/css" />    

<?php	
	function telaTrancamento($aluno) { 
		$Itemid = JRequest::getInt('Itemid', 0); ?>
                       
			<script language="JavaScript">                
                function ValidarForm(formTrancamento) {
                    if(formTrancamento.justificativa.value == 0) {
                      alert('O campo Justificativa deve ser preenchido.')
                      formTrancamento.justificativa.focus();
                      return false;
                    }
                
                    return true;
                }
            </script>        
              
                <form method="post" name="formTrancamento" action="index.php?option=com_portalaluno&Itemid=<?php echo $Itemid;?>" onsubmit="javascript:return ValidateForm(this)" enctype="multipart/form-data">
                   
                    <!-- CABEÇALHO DA PÁGINA -->                          
                    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
                        <div class="cpanel2">
                            <div class="icon" id="toolbar-save">
                                <a href="javascript: if(ValidarForm(document.formTrancamento)) document.formTrancamento.submit()">
                                <span class="icon-32-save"></span><?php echo JText::_( 'Enviar' ); ?></a>
                            </div>
                            
                            <div class="icon" id="toolbar-cancel">
                                <a href="index.php?option=com_portalaluno&idAluno=<?php echo $aluno->id;?>&task=servicos&Itemid=<?php echo $Itemid;?>">
                                <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                            </div>
                        </div>
                        
                        <div class="clr"></div>
                        
                        </div>
                    
                        <div class="pagetitle icon-48-cpanel">
                            <h2>Solicitação de Trancamento de Curso</h2>
                        </div>
                    </div></div>
                
                    <b>Como proceder: </b>
                    <ul><li>Preencha o Justificativa explicando o motivo do trancamento do curso <font color="#FF0000">(* Campo Obrigat&#243rio)</font>.</li></ul>
                    <hr style="width: 100%; height: 2px;">
                    
                    <table width="100%" border="0" cellspacing="2" cellpadding="2">
                        <tr style="background-color: #7196d8;">
                            <td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações do Aluno</font></b></font></td>
                        </tr>  
                        <tr>
                            <td bgcolor="#CCCCCC"><strong>Aluno: </strong></td>
                            <td><?php echo $aluno->nome;?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#CCCCCC"><strong>Ano de Ingresso: </strong></td>
                            <td><?php
                                     $data = explode("-", $aluno->anoingresso);
                                     echo $data[2]."/".$data[1]."/".$data[0];
                            ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#CCCCCC"><strong>Previsão de Conclusão: </strong></td>
                            <td><?php
                                     $data = explode("-", $aluno->anoconclusao);
                                     echo $data[2]."/".$data[1]."/".$data[0];
                            ?></td>
                        </tr>      
                        <tr>
                            <td bgcolor="#CCCCCC"><font color="#FF0000">*</font><strong>Justificativa: </strong></td>
                            <td><textarea name="justificativa" cols="70" rows="5"></textarea></td>
                        </tr> 
                        <tr>
                            <td bgcolor="#CCCCCC"><strong>Documento:</strong></td>
                            <td><input type="file" name="documento" />
                        </tr>      
                    </table>
					<hr>
					<?php
						$database =& JFactory::getDBO();	
						$sql = "SELECT DATE_FORMAT(dataSolicitacao, '%d/%m/%Y') AS dataSolicitacao,  documento, DATE_FORMAT(dataInicio, '%d/%m/%Y') AS dataInicio,  DATE_FORMAT(dataTermino, '%d/%m/%Y') AS dataTermino, status, justificativa FROM #__trancamentos WHERE idAluno = $aluno->id ORDER BY dataSolicitacao";
						$database->setQuery($sql);
						$trancamentos = $database->loadObjectList();
						$resultado = array (0 => "Em aprova&#231;&#227;o pelo Orientador",1 => "Em aprova&#231;&#227;o pelo PPGI",2 => "Indeferido pelo Orientador",3 => "Indeferido pelo PPGI",3 => "Deferido");
					?>
					<table width="100%" border="0" cellspacing="2" cellpadding="2">
						<tr style="background-color: #7196d8;">
							<td style="width: 100%;" colspan="8"><font size="2"> <b><font color="#FFFFFF">Pedidos de Trancamentos do Aluno</font></b></font></td>
						</tr>
						<tr>
							<td align="center" class="cellCommom">Solicita&#231;&#227;o</td>
							<td align="center" class="cellCommom">Status</td>							
							<td align="center" class="cellCommom">Início</td>
							<td align="center" class="cellCommom">Término</td>
							<td align="center" class="cellCommom">Justificativa</td>
							<td align="center" class="cellCommom">Documento</td>
						</tr>
        
						<?php
							foreach($trancamentos as $trancamento) { ?>
						<tr>
							<td align="center"><?php echo $trancamento->dataSolicitacao;?></td>
							<td align="center"><?php echo $resultado[$trancamento->status];?></td>
							<td align="center"><?php if($trancamento->dataInicio == "00/00/0000") echo "N&#227;o Julgado"; else echo $trancamento->dataInicio;?></td>
							<td align="center"><?php if($trancamento->dataTermino == "00/00/0000") echo "Em aberto"; else echo $trancamento->dataTermino;?></td>
							<td align="center"><img src="components/com_portalsecretaria/images/justificativa.gif" title="<?php echo $trancamento->justificativa;?>"></td>
							<td align="center"><a href='<?php echo $trancamento->documento;?>' target='_blan'><img src="components/com_portalaluno/images/icon_pdf.gif"></a></td>
						</tr>
						<?php } ?>
					</table>                        
                    <input name='idAluno' type='hidden' value='<?php echo $aluno->id;?>'>
                    <input name='task' type='hidden' value='addTrancamento'>              
            	</form> 
                            
		<?php	
	}
?>

<?php 
	function enviarTrancamento($aluno) {
		$database =& JFactory::getDBO();	
		$justificativa = $_POST['justificativa'];
		$documento = "";
		$status = 0;
		
		if($_FILES["documento"]["tmp_name"]){
			$documento = "components/com_portalaluno/atestados/PPGI-Trancamento-".$aluno->id.".pdf";
			move_uploaded_file($_FILES["documento"]["tmp_name"],$documento);
    	}

		$sql = "INSERT INTO #__trancamentos (idAluno, idOrientador, dataSolicitacao, dataInicio, dataTermino, justificativa, documento, status) VALUES ($aluno->id, $aluno->orientador, '".date("Y-m-d")."', '', '', '$justificativa', '$documento', '$status')";
		$database->setQuery($sql);
		
		$funcionou = $database->Query();		
		if($funcionou){
			 JFactory::getApplication()->enqueueMessage(JText::_('Solicitação enviada para avaliação!'));
			 servicosPortalAlunoItem($aluno);
		}
		else JError::raiseWarning( 100, 'ERRO: Solicitação não enviada.' );
	} 
?>

<?php
	function enviarEmail2 ($aluno, $justificativa) {
		$database =& JFactory::getDBO();
		$sqlEmail = "SELECT email FROM #__professores WHERE id = $aluno->orientador";		
		$database->setQuery($sqlEmail);
		$resultado = $database->loadObjectList();
		
		foreach ($resultado as $email)
			$emailOrientador = $email->email;

		//ENVIO DE EMAIL AO ORIENTADOR		
		$subject  = "[IComp/UFAM] Solicitacao de Trancamento de Curso";
	
		$message .= utf8_decode("Há uma nova Solicitação de Trancamento de Curso para ser avaliada.\r\n\n");
		$message .= "Nome: ".$aluno->nome."\r\n";
		$message .= "E-mail: ".$aluno->email."\r\n";
		$message .= "Justificativa: ".utf8_decode($justificativa)."\r\n";
		$message .= "Data e Hora do envio: ".date("d/m/Y H:i:s")."\r\n";
		$message .= utf8_decode("Acesse o Portal do Professor para tomar as devidas providências.\r\n\n");
					
		JUtility::sendMail($aluno->email, "Site do IComp: ".$aluno->nome, $emailOrientador, $subject, $message, false, $aluno->email, NULL);
	}
?>
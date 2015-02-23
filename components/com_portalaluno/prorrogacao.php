<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">  
<link rel="stylesheet" href="components/com_inscricaoppgi/estilo.css" type="text/css" />    

<?php	
	function telaProrrogacao($aluno) { 
		$Itemid = JRequest::getInt('Itemid', 0);
		
		$database =& JFactory::getDBO();
		$sqlQtde = "SELECT * FROM #__prorrogacoes WHERE idAluno = ".$aluno->id." ORDER BY dataSolicitacao ASC";
		$database->setQuery( $sqlQtde );
		$dados = $database->loadObjectList();

		// CALCULAR A DIFERENÇA ENTRE A DATA ATUAL E A DATA DE CONCLUSÃO (DIAS)
		$dataFinal = $aluno->anoconclusao;
				
		$dataAtual = date("Y-m-d");		
		$timeInicial = strtotime($dataAtual);
		$timeFinal = strtotime($dataFinal);		
		$diff = $timeFinal - $timeInicial;		
		$dias = (int)floor($diff / (60*60*24));
		
			
			// NA PRIMEIRA SOLICITAÇÃO O ALUNO VISUALIZA O FORMULÁRIO
			if (sizeof($dados) == 0) { ?>   
                       
				<script language="JavaScript">
                    function voltarForm(form) {		
                       form.task.value = 'servicos';
                       form.submit();
                       return true;		
                    }
                    
                    function ValidarProrrogacao(formProrrogacao) {
                        if(formProrrogacao.justificativa.value == 0) {
                          alert('O campo Justificativa deve ser preenchido.')
                          formProrrogacao.justificativa.focus();
                          return false;
                        }
                        
                        if(formProrrogacao.previa.value == 0) {
                          alert('Selecione um arquivo para upload.')
                          formProrrogacao.previa.focus();
                          return false;
                        }		
                    
                        return true;
                    }
                </script>        
              
                <form method="post" name="formProrrogacao" action="index.php?option=com_portalaluno&Itemid=<?php echo $Itemid;?>" enctype="multipart/form-data">
                   
                    <!-- CABEÇALHO DA PÁGINA -->                          
                    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
                        <div class="cpanel2">
                            <div class="icon" id="toolbar-save">
                                <a href="javascript: if(ValidarProrrogacao(document.formProrrogacao)) document.formProrrogacao.submit()">
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
                            <h2>Solicitação de Prorrogação de Prazo</h2>
                        </div>
                    </div></div>
                
                    <b>Como proceder: </b>
                    <ul><li>Preencha o formul&#225;rio abaixo justificando o motivo da prorrogação de prazo <font color="#FF0000">(* Campos Obrigat&#243rios)</font>.</li></ul>
                    <hr style="width: 100%; height: 2px;">
                    
                    <table width="100%" border="0" cellspacing="2" cellpadding="2">
                        <tr style="background-color: #7196d8;">
                            <td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
                        </tr>        
                        <tr>
                            <td bgcolor="#CCCCCC"><font color="#FF0000">*</font><strong>Justificativa: </strong></td>
                            <td><textarea name="justificativa" cols="70" rows="5"></textarea></td>
                        </tr>
                        <tr>
                            <td bgcolor="#CCCCCC"><font color="#FF0000">*</font><strong>Prévia da Dissertação:</strong></td>
                            <td><input type="file" name="previa" />
                        </tr>       
                    </table>
                        
                    <input name='idAluno' type='hidden' value='<?php echo $aluno->id;?>'>
                    <input name='task' type='hidden' value='addProrrogacao'>              
            	</form> 
                            
		<?php	
			
			// QUANDO EXISTE AO MENOS UMA SOLICITAÇÃO
			} else { 
				// CALCULAR A DIFERENÇA ENTRE A DATA DE SOLICITAÇÃO E A DATA DE CONCLUSÃO (DIAS2)
			
				$timeInicial = strtotime($dados[sizeof($dados)-1]->dataSolicitacao);
				$timeFinal = strtotime($aluno->anoconclusao);
				$diff = $timeFinal - $timeInicial;
				$dias2 = (int)floor($diff / (60*60*24)); 
			
			
				?>
                
            	<form method="post" name="formListProrrogacao" action="index.php?option=com_portalaluno&Itemid=<?php echo $Itemid;?>" enctype="multipart/form-data">
            
					<script language="JavaScript">			
                        function visualizar(form) {
                            var idSelecionado = 0;
                            
                            for(i = 0; i < form.idSolicSelec.length;i++)
                               if(form.idSolicSelec[i].checked) idSelecionado = form.idSolicSelec[i].value;
                
                            if(idSelecionado > 0){
                                form.task.value = 'mostrarDetalhesProrrogacao';
                                form.idAluno.value = idSelecionado;
                                form.submit();
                            } else {
                               alert('Ao menos 1 item deve ser selecionado para visualiza\xE7\xE3o.')
                            }
                        }
                    </script>
        
                    <!-- CABEÇALHO DA PÁGINA -->
                    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
                        <div class="cpanel2">
                            <?php //if (($dias <= 30) && ($dias2 > 30) && (sizeof($dados) < 3)) { ?> 
							<?php if (($dias <= 30) && (sizeof($dados) < 3)) { ?>
                            <div class="icon" id="toolbar-preview">
                                <a href="index.php?option=com_portalaluno&task=cadProrrogacao&idAluno=<?php echo $aluno->id;?>&Itemid=<?php echo $Itemid;?>">
                                <span class="icon-32-new"></span>Nova</a>
                            </div>
                            <?php } ?>
                            
                            <div class="icon" id="toolbar-cancel">
                                <a href="index.php?option=com_portalaluno&idAluno=<?php echo $aluno->id;?>&task=servicos&Itemid=<?php echo $Itemid;?>">
                                <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
                            </div>
                        </div>
                        
                        <div class="clr"></div>
                        
                        </div>
                    
                        <div class="pagetitle icon-48-cpanel">
                            <h2>Solicitação de Prorrogação de Prazo</h2>
                        </div>
                    </div></div>
        
                    <ul><li>Solicitações de Prorrogação de Prazo Realizadas</li></ul>
                    <hr style="width: 100%; height: 2px;">
            
                    <table width='100%' border='0' cellspacing='1' cellpadding="0" id="tablesorter-imasters" class="tabela">
                        <thead>
                            <tr bgcolor="#002666">
                                <th width="3%" align="center">Status</th>
                                <th width="6%" align="center">Data de Solicitação</th>
                                <th width="6%" align="center">Avaliação Professor</th>
                                <th width="6%" align="center">Avaliação IComp</th>                        
                                <th width="15%" align="center">Orientador</th>				
                                <th width="5%" align="center">Curso</th>
                                <th width="5%" align="center">Ingresso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $table_bgcolor_even="#e6e6e6";
                                $table_bgcolor_odd="#FFFFFF";
                                $curso = array (1 => "mestrado",2 => "doutorado",3 => "especial");
                                $status_pedido = array (0 => "Em aprovação pelo Professor",
                                                        1 => "Em aprovação pelo IComp",
                                                        2 => "Indeferido pelo Professor",
                                                        3 => "Indeferido pelo IComp",
                                                        4 => "Deferido"); 
                                
								$i = 0; 									
								
								foreach ($dados as $d) { 
									$sqlQtde = "SELECT p.nomeProfessor, pr.idOrientador, pr.justificativa, pr.previa, pr.dataSolicitacao, pr.dataAprovOrientador, pr.dataAprovColegiado, pr.status, count(*) as total FROM #__prorrogacoes pr, #__professores p WHERE pr.idAluno = ".$aluno->id." AND p.id = ".$aluno->orientador."";
									$database->setQuery( $sqlQtde );
									$resultado = $database->loadObjectList(); 
									
									//atribui o primeiro objeto do vetor a uma variavel
									foreach ($resultado as $t) {
										$itens = $t;
									}
									
									$i = $i + 1;
									if ($i % 2)
										echo("<tr bgcolor='$table_bgcolor_even' style='text-align: center;'>");						
									else
										echo("<tr bgcolor='$table_bgcolor_odd' style='text-align: center;'>");
									?>
                                    <tr>
                                        <td align="center"><img src="components/com_portalaluno/images/icon_status<?php echo $d->status; ?>.png" title="<?php echo $status_pedido[$d->status]; ?>" /></td>
                                        <td align="center">
                                            <?php
                                                $data = $d->dataSolicitacao;
                                                $data = explode("-", $data);
                                                echo $data[2]."/".$data[1]."/".$data[0]; 
                                            ?>
                                        </td>
                                        <td align="center">
                                            <?php
                                                if ($d->dataAprovOrientador == 0){} 
                                                else {
                                                    $data = $d->dataAprovOrientador;
                                                    $data = explode("-", $data);
                                                    echo $data[2]."/".$data[1]."/".$data[0]; 
                                                }
                                            ?>
                                        </td>                                
                                        <td align="center">
                                            <?php
                                                if ($d->dataAprovColegiado == 0){} 
                                                else {
                                                    $data = $d->dataAprovColegiado;
                                                    $data = explode("-", $data);
                                                    echo $data[2]."/".$data[1]."/".$data[0]; 
                                                }
                                            ?>
                                        </td>                                
                                        <td align="center"><?php echo $itens->nomeProfessor; ?></td>
                                        <td align="center"><img border='0' src='components/com_portalsecretaria/images/<?php echo $curso[$aluno->curso]; ?>.gif' title="<?php echo $curso[$aluno->curso]; ?>" /></td>
                                        <td align="center">
                                            <?php 
                                                $data = $aluno->anoingresso;
                                                $data = explode("-", $data);
                                                echo $data[2]."/".$data[1]."/".$data[0];
                                            ?>
                                        </td>
                                    </tr>
                            <?php } } ?> 
                        </tbody>
                    </table> 
                
                <input name='idAluno' type='hidden' value='<?php echo $aluno->id?>'>                
                <input name='idSolicSelec' type='hidden' value='0'>  
            </form>               
    <?php }  // FIM ELSE ?>
    
    
<?php	// ----------------------------- FUNÇÕES -----------------------------
	function mostrarDetalhesProrrogacao($aluno) {
		$Itemid = JRequest::getInt('Itemid', 0);
		$database =& JFactory::getDBO();
		$sql = "SELECT p.previa, p.justificativa FROM #__prorrogacoes p WHERE p.idAluno=".$aluno->id." ORDER BY p.dataSolicitacao LIMIT 1 ";
		
		$database->setQuery($sql);
		$prorrogacoes = $database->loadObjectList();
		
		foreach($prorrogacoes as $p){
			$prorrogacao=$p;
		} ?>		
    
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-back">
                    <a href="index.php?option=com_portalsecretaria&Itemid=<?php echo $Itemid;?>&task=prorrogacao">
                    <span class="icon-32-back"></span>Voltar</a>
                </div>
            </div>
          
            <div class="clr"></div>
            </div>
              <div class="pagetitle icon-48-contact"><h2>Dados da Solicitação do Aluno</h2></div>
            </div>
        </div>
	
        <table border="0" cellpadding="3" cellspacing="2" width="100%" align="center">
        <tbody>
          <tr>
            <td><font size="2"><b>Justificativa:</b></font></td>
            <td colspan="3"><?php echo $prorrogacao->justificativa;?></td>
          </tr>
          <tr>
            <td><font size="2"><b>Prévia da Dissertação:</b></font></td>
            <td colspan="3"><?php echo $prorrogacao->previa;?></td>
          </tr>
        </tbody>
      </table>
<?php } ?>

<?php
	function telaCadProrrogacao($aluno) { ?>
            
		<script language="JavaScript">                    
            function ValidarProrrogacao(formProrrogacao) {
                if(formProrrogacao.justificativa.value == 0) {
                  alert('O campo Justificativa deve ser preenchido.')
                  formProrrogacao.justificativa.focus();
                  return false;
                }
                
                if(formProrrogacao.previa.value == 0) {
                  alert('Selecione um arquivo para upload.')
                  formProrrogacao.previa.focus();
                  return false;
                }		
                
            
                return true;
            }
        </script>        
      
        <form method="post" name="formProrrogacao" action="index.php?option=com_portalaluno&Itemid=<?php echo $Itemid;?>" enctype="multipart/form-data">
                  
        <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
            <div class="cpanel2">
                <div class="icon" id="toolbar-save">
                    <a href="javascript: if(ValidarProrrogacao(document.formProrrogacao)) document.formProrrogacao.submit()">
                    <span class="icon-32-save"></span><?php echo JText::_( 'Enviar' ); ?></a>
                </div>
                
                <div class="icon" id="toolbar-cancel">
                    <a href="javascript:history.back()">
                    <span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>

                </div>
            </div>
            
            <div class="clr"></div>
            
            </div>
        
            <div class="pagetitle icon-48-cpanel">
                <h2>Solicitação de Prorrogação de Prazo</h2>
            </div>
        </div></div>
    
        <b>Como proceder: </b>
        <ul><li>Preencha o formul&#225;rio abaixo justificando o motivo da prorrogação de prazo <font color="#FF0000">(* Campos Obrigat&#243rios)</font>.</li></ul>
        <hr style="width: 100%; height: 2px;">
        
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr style="background-color: #7196d8;">
                <td style="width: 100%;" colspan="2"><font size="2"> <b><font color="#FFFFFF">Informações</font></b></font></td>
            </tr>        
            <tr>
                <td bgcolor="#CCCCCC"><font color="#FF0000">*</font><strong>Justificativa: </strong></td>
                <td><textarea name="justificativa" cols="70" rows="5"></textarea></td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC"><font color="#FF0000">*</font><strong>Prévia da Dissertação:</strong></td>
                <td><input type="file" name="previa" />
            </tr>       
        </table>
                
      <input name='idAluno' type='hidden' value='<?php echo $aluno->id;?>'>
      <input name='task' type='hidden' value='addProrrogacao'>              
    </form> 
<?php } ?>
	

<?php	
	function enviarProrrogacao($aluno) {
		$database =& JFactory::getDBO();	
		$previa = "";
		$status = 0;
		$justificativa = JRequest::getVar('justificativa');
		
		if($_FILES["previa"]["tmp_name"]){
			$previa = "components/com_portalaluno/previas/PPGI-Previa-".$aluno->id."-".date("Y-m-d").".pdf";
			move_uploaded_file($_FILES["previa"]["tmp_name"],$previa);
    	}
		
		$sql = "INSERT INTO #__prorrogacoes (idAluno, idOrientador, justificativa, previa, dataSolicitacao, status) VALUES ($aluno->id, $aluno->orientador, '$justificativa', '$previa', '".date("Y-m-d")."', $status )";		
		$database->setQuery($sql);
		$funcionou = $database->Query();

		if($funcionou){
			 JFactory::getApplication()->enqueueMessage(JText::_('Solicitação enviada para avaliação do orientador!'));
			 enviarEmail($aluno, $justificativa);
			 servicosPortalAlunoItem($aluno);
		}
		else JError::raiseWarning( 100, 'ERRO: Solicitação não enviada.' );
	}
?>

<?php
	function enviarEmail ($aluno, $justificativa) {
		$database =& JFactory::getDBO();	
		$sqlEmail = "SELECT email FROM #__professores WHERE id = $aluno->orientador";		
		$database->setQuery($sqlEmail);
		$resultado = $database->loadObjectList();
		
		foreach ($resultado as $email)
			$emailOrientador = $email->email;

		//ENVIO DE EMAIL AO ORIENTADOR		
		$subject  = "[IComp/UFAM] Solicitacao de Prorrogação de Prazo";
	
		$message .= utf8_decode("Há uma nova Solicitação de Prorrogação de Prazo para ser avaliada.\r\n\n");
		$message .= "Nome: ".$aluno->nome."\r\n";
		$message .= "E-mail: ".$aluno->email."\r\n";
		$message .= "Justificativa: ".utf8_decode($justificativa)."\r\n";
		$message .= "Data e Hora do envio: ".date("d/m/Y H:i:s")."\r\n";
		$message .= utf8_decode("Acesse o Portal do Professor para tomar as devidas providências.\r\n\n");
					
		JUtility::sendMail($aluno->email, "Site do IComp: ".$aluno->nome, $emailOrientador, $subject, $message, false, $aluno->email, NULL);
	}
?>
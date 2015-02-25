<?php
// No direct access to this file
$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );
defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
//$document->addScript("includes/js/joomla.javascript.js");

function formatarData($data){
    $data = explode("-", $data);
    $aux = $data[2] . "/" . $data[1] . "/" .$data[0] ;
    return $aux;    
}

$idDefesa = $this->idDefesa;
$idAluno = $this->idAluno;
$isUpdate = $this->isUpdate;

$Defesa = $this->defesa;
$Aluno = $this->aluno;
$MembrosBanca = $this->membrosBanca;

$arrayCurso = array (1 => "Mestrado", 2 => "Doutorado", 3 => "Especial");
$arrayTipoDefesa = array('Q1' => "Qualificação 1", 'Q2' => "Qualificação 2", 'D' => "Dissertação", 'T' => "Tese");
$arrayFuncaoMembro = array ('P' => "Presidente",'E' => "Membro Externo", 'I' => "Membro Interno");

$updated = $this->updated;
if($updated == true){
    JFactory :: getApplication()->enqueueMessage(JText :: _('Dados da Defesa atualizados com sucesso!'));
}
else if($updated == false AND $updated !=NULL){
    JError :: raiseWarning(100, 'ERRO: Opera&#231;&#227;o Falhou. Tente Novamente.');
}
?>

<script type="text/javascript" src="/icomp/components/com_defesascoordenador/assets/jquery-ui-1.11.2.custom/jquery-ui.js"></script>

<script language="JavaScript">
       
    function atualizarDefesa(form){        
       var confirmar;
       
       confirmar = window.confirm('Corfirma a ALTERAÇÃO dos dados?');
   
       if(confirmar == true){
            var defesa = <?php echo $idDefesa ?>;
            var aluno = <?php echo $idAluno ?>;
            
            var campoObrigatorioVazio = '';
            var campoRecebeFoco = '';
            var dataValida = true;
            var horaValida = true;
            var msgErro = '';
            var titulo = document.getElementById('titulo').value; // usando Javascript
            var dataDefesa = document.getElementById('dataDefesa').value;
            var horarioDefesa = document.getElementById('horarioDefesa').value;
            var localDefesa = document.getElementById('localDefesa').value;
            var resumo = document.getElementById('resumo').value;
            
            //alert(form.titulo.value);
            //var x=document.forms["myForm"]["fname"].value;
            //alert($('#titulo').val()); // usando Jquery
            //atributos += elem.attributes[i].nodeName;
            if(titulo == null || titulo == "" || titulo.length == 0){
                campoObrigatorioVazio = '- Título\n';
                if (!campoRecebeFoco) campoRecebeFoco = 'titulo';
            }
            if(!dataDefesa){
                campoObrigatorioVazio = campoObrigatorioVazio.concat('- Data\n');
                if (!campoRecebeFoco) campoRecebeFoco = 'dataDefesa';
            }
            if(!horarioDefesa){
                campoObrigatorioVazio = campoObrigatorioVazio.concat('- Hora\n');
                if (!campoRecebeFoco) campoRecebeFoco = 'horarioDefesa';
            }
            if(localDefesa == null || localDefesa == "" || localDefesa.length == 0){
                campoObrigatorioVazio = campoObrigatorioVazio.concat('- Local\n');
                if (!campoRecebeFoco) campoRecebeFoco = 'localDefesa';
            }
            if(resumo == null || resumo == "" || resumo.length == 0){
                campoObrigatorioVazio = campoObrigatorioVazio.concat('- Resumo\n');
                if (!campoRecebeFoco) campoRecebeFoco = 'resumo';
            }
            
            if(campoObrigatorioVazio){
                msgErro = 'Campos obrigatórios não preenchidos:\n'.concat(campoObrigatorioVazio, '\n');
            }
            
            // Verificar se o formato da data é válido
            var patternData = /^(((0[1-9]|[12][0-9]|3[01])([-.\/])(0[13578]|10|12)([-.\/])(\d{4}))|(([0][1-9]|[12][0-9]|30)([-.\/])(0[469]|11)([-.\/])(\d{4}))|((0[1-9]|1[0-9]|2[0-8])([-.\/])(02)([-.\/])(\d{4}))|((29)(\.|-|\/)(02)([-.\/])([02468][048]00))|((29)([-.\/])(02)([-.\/])([13579][26]00))|((29)([-.\/])(02)([-.\/])([0-9][0-9][0][48]))|((29)([-.\/])(02)([-.\/])([0-9][0-9][2468][048]))|((29)([-.\/])(02)([-.\/])([0-9][0-9][13579][26])))$/;
            if(!patternData.test(dataDefesa)){  
                msgErro = msgErro.concat('Digite uma data válida no formato Dia/Mês/Ano.', '\n');
                if (!campoRecebeFoco) campoRecebeFoco = 'dataDefesa';
            }
            
            // Verificar se o formato da hora é válido
            var patternHora = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
            if(!patternHora.test(horarioDefesa)){  
                msgErro = msgErro.concat('Digite a hora no formato de 24 horas HH:Min', '\n');
                if (!campoRecebeFoco) campoRecebeFoco = 'horarioDefesa';
            }
            if (msgErro){
                alert(msgErro);
                document.getElementById(campoRecebeFoco).focus();
                document.getElementById(campoRecebeFoco).select();
                return;
            }
            else
            {
                form.task.value = 'atualizarDefesa';
                form.idDefesa.value = defesa;
                form.idAluno.value = aluno;
                form.submit();
            }
       }
    }

    jQuery(function() {
        jQuery("#dataDefesa").datepicker({
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames: ['Janeiro','Fevereiro','MarÃ§o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        nextText: 'Próximo',
        prevText: 'Anterior'});
    });

</script>

<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
<link rel="stylesheet" type="text/css" href="components/com_defesascoordenador/assets/jquery-ui-1.11.2.custom/jquery-ui.css">

<form method="post" id="formAvaliacao" name="form" enctype="multipart/form-data" action="index.php?option=com_defesasorientador&view=detalhesdefesa" >

<div id="toolbar-box">
    <div class="m">
        <div class="toolbar-list" id="toolbar">
          <div class="cpanel2">

            <div class="icon" id="toolbar-save" <?php if($isUpdate == '0') { ?> style="display: none;"<?php } ?>>
                <a href ="javascript:atualizarDefesa(document.form)" class = 'toolbar' id="toolbar-save">
                <span class="icon-32-save"></span><?php echo JText::_( 'Atualizar' ); ?></a>
            </div>

            <div class="icon" id="toolbar-back">
                <a href="index.php?option=com_defesasorientador&view=listadefesas">
                <span class="icon-32-back"></span>Voltar</a>
            </div>
           
            <input name='task' type='hidden' value='display'>
            <input name='idDefesa' type='hidden' value = <?php echo $idDefesa;?>>
            <input name='idAluno' type='hidden' value = <?php echo $idAluno;?>>

        </div>
        <div class="clr"></div>
        </div>

        <div class="pagetitle icon-48-user"><h2><?php echo $this->msg; ?></h2></div>
    </div>
</div>

<h2>Dados da Defesa</h2>
<hr />

<link rel="stylesheet" type="text/css" href="components/com_portalsecretaria/assets/css/estilo.css" />

<fieldset>
    <table>
        <tbody>
            <tr>
                <td colspan="2">Nome Aluno<span class="obrigatory">*</span></td>
                <td>Número Defesa</td>
            </tr>
            <tr>
                <td colspan="2"><input type="text" name="nomeAluno" size="60" class="obrigatorio" value="<?php echo $Aluno[0]->nome; ?>" disabled /></td>
                <td><input type="text" name="numDefesa" value="<?php echo $Defesa[0]->numDefesa; ?>" disabled /></td>
            </tr>
            <tr>
                <td>Curso<span class="obrigatory">*</span></td>
                <td>Tipo Defesa<span class="obrigatory">*</span></td>
                <td>Conceito</td>
            </tr>
            <tr>
                <td><input type="text" name="curso" id="curso" class="obrigatorio" value="<?php echo $arrayCurso[$Aluno[0]->curso]; ?>" disabled /></td>
                <td><input type="text" name="tipoDefesa" id="tipoDefesa" class="obrigatorio" value="<?php echo $arrayTipoDefesa[$Defesa[0]->tipoDefesa]; ?>" disabled /></td>
                <td><input type="text" name="conceitoDefesa" id="conceitoDefesa" value="<?php echo $Defesa[0]->conceito; ?>" disabled /></td>
            </tr>
            <tr>
                <td>Título<span class="obrigatory">*</span></td>
            </tr>
            <tr>
                <td colspan="4"><input type="text" name="titulo" id="titulo" size="100" maxlength="180" class="obrigatorio" value="<?php echo $Defesa[0]->titulo; ?>" <?php if($isUpdate == '0') echo 'disabled'; ?> /></td>
            </tr>
            <tr>
                <td>Data da Defesa</td>
                <td>Horário</td>
            </tr>
            <tr>
                <td><input type="text" name="dataDefesa" id="dataDefesa" placeholder="dd/mm/aaaa" maxlength="10" value="<?php echo formatarData($Defesa[0]->data); ?>" <?php if($isUpdate == '0') echo 'disabled'; ?> /></td>
                <td><input type="text" name="horarioDefesa" id="horarioDefesa" maxlength="10" value="<?php echo $Defesa[0]->horario; ?>" <?php if($isUpdate == '0') echo 'disabled'; ?> /></td>
            </tr>
            <tr>
                <td colspan="4">Local da Defesa</td>
            </tr>
            <tr>
                <td colspan="4"><input type="text" name="localDefesa" id="localDefesa" size="100" maxlength="100" value="<?php echo $Defesa[0]->local; ?>" <?php if($isUpdate == '0') echo 'disabled'; ?> /></td>
            </tr>
            <tr>
                <td colspan="4">Resumo<span class="obrigatory">*</span></td>
            </tr>
            <tr>
                <td colspan="4"><textarea cols="100%" rows="22" name="resumo" id="resumo" class="obrigatorio" <?php if($isUpdate == '0') echo 'disabled'; ?>><?php echo $Defesa[0]->resumo; ?></textarea></td>
            </tr>
        </tbody>
    </table>
</fieldset>

<h2>Dados da Comissão Examinadora</h2>
<hr />

<fieldset>
    <table style='text-align: left; width: 93%;' border='1' cellpadding='3' cellspacing='0'>
      <tbody>
        <tr bgcolor='#B0B0B0'>
          <td style='text-align: center; font-weight: bold;' width='60%'>MEMBROS DA BANCA</td>
          <td style='text-align: center; font-weight: bold;' width='20%'>FILIAÇÃO</td>
          <td style='text-align: center; font-weight: bold;' width='20%'>FUNÇÃO</td>
        </tr>
    
        <?php
        if(isset ($MembrosBanca)){
            foreach( $MembrosBanca as $membro ){ ?>
            <tr>
              <td align='center'><?php echo $membro->nome;?></td>
              <td align='center'><?php echo $membro->filiacao;?></td>
              <td align='center'><?php echo $arrayFuncaoMembro[$membro->funcao];?></td>   
            </tr>
            <?php
            }
        } ?>
    
      </tbody>
    </table>
</fieldset>

</form>

<?php
JHTML::_('behavior.mootools');
JHTML::_('script','modal.js', 'media/system/js', true);
JHTML::_('stylesheet','modal.css');
JHTML::_('behavior.modal', 'a.modal');
// No direct access to this file
$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );
defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
$document->addScript("includes/js/joomla.javascript.js");

$defesas = $this->defesas;
$data_inicial = $this->data_inicial;
$data_final = $this->data_final;
$nome_professor = $this->nome_professor;

if(($this->data_inicial == NULL) AND 
   ($this->data_final == NULL) AND 
   ($this->nome_professor == NULL)){
    $data_inicial = '';
    $data_final = '';
    $nome_professor = '';
}
?>

<script type="text/javascript" src="/icomp/components/com_defesascoordenador/assets/jquery-ui-1.11.2.custom/jquery-ui.js"></script>

<script language="JavaScript">
    function emitirRelatorio(form){
        var dataInicial = document.getElementById('dataInicial').value;
        var dataFinal = document.getElementById('dataFinal').value;
        var nomeProfessor = document.getElementById('nomeProfessor').value;

        var dataIniAux = dataInicial.split("/");
        dataInicial = dataIniAux[0]+'-'+dataIniAux[1]+'-'+dataIniAux[2];

        var dataFinalAux = dataFinal.split("/");
        dataFinal = dataFinalAux[0]+'-'+dataFinalAux[1]+'-'+dataFinalAux[2];

        form.task.value = 'emitirRelatorioDefesas'; 
        window.open(URL='index.php?option=com_controledefesas&task=emitirRelatorioDefesas&dataInicial='+dataInicial+'&dataFinal='+dataFinal+'&nomeProfessor='+nomeProfessor+'&lang=pt-br');
    }
</script>

<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">
    
<link rel="stylesheet" href="components/com_portalsecretaria/assets/css/estilo.css" type="text/css" />


<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">  
<link rel="stylesheet" type="text/css" href="components/com_defesascoordenador/assets/jquery-ui-1.11.2.custom/jquery-ui.css">

<form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_controledefesas&view=relatoriodefesas">

    <div id="toolbar-box">
        <div class="m">
            <div class="toolbar-list" id="toolbar">
                <div class="cpanel2">
                    <div class="icon" id="toolbar-back">
                        <a href="index.php?option=com_controledefesas">
                        <span class="icon-32-back"></span>Voltar</a>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="pagetitle icon-48-groups"><h2>Relatório de Defesas</h2></div>
        </div>
    </div>

    <!-- FILTRO DA BUSCA -->
    <fieldset>
        <legend>Filtros para consulta</legend>
        </br>
        <table width="100%">
            <tr>
                <td>Data Inicial</td>
                <td>Data Final</td>
                <td>Nome Professor</td>
            </tr>
            <tr>                
                <td><input id="dataInicial" name="dataInicial" type="text" size="14" maxlength="10" value=""/></td>
                <td><input id="dataFinal" name="dataFinal" type="text" size="14" maxlength="10" value=""/></td>
                <td><input id="nomeProfessor" name="nomeProfessor" type="text" size="50" value=""/></td>
            </tr>

            <tr>
                <td><a href="javascript:emitirRelatorio(document.form)" title="Visualizar o Relatório em PDF"><span class="btn btn-primary"><i class="icone-search icone-white"></i>  Emitir Relatório</span></a></td>
            </tr>

        </table>
    </fieldset>

    <br />
     
     <input name='task' type='hidden' value='display' />
     
</form>

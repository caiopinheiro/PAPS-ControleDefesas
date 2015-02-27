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
//$document->addScript('//code.jquery.com/ui/1.11.2/jquery-ui.js');

/*
$defesas = $this->defesas;
$data_inicial = $this->data_inicial;
$data_final = $this->data_final;
$nome_professor = $this->nome_professor;
$id_membro_banca = $this->id_membro_banca;
$id_professor = $this->id_professor;

if(($this->data_inicial == NULL) AND 
   ($this->data_final == NULL) AND 
   ($this->nome_professor == NULL) AND
   ($this->id_membro_banca == NULL) AND
   ($this->id_professor == NULL)){
    $data_inicial = '';
    $data_final = '';
    $nome_professor = '';
    $id_membro_banca = '';
    $id_professor = '';
}
*/
?>

<script type="text/javascript" src="/icomp/components/com_defesascoordenador/assets/jquery-ui-1.11.2.custom/jquery-ui.js"></script>

<script language="JavaScript">
    function emitirRelatorio(form){
		
        var dataInicial = document.getElementById('dataInicial').value;
        var dataFinal = document.getElementById('dataFinal').value;
		var nomeProfessor = document.getElementById('professor').value;
        var idMembroBanca = '';
        var idProfessor = '';

		if (dataInicial == '' && dataFinal == '' && nomeProfessor == ''){
			alert("Informe o filtro da pesquisa.");
			return;
		}

		if (dataInicial != ''){
			dataInicial = formatarData(dataInicial);
		}
		
		if (dataFinal != ''){
            dataFinal = formatarData(dataFinal);
		}
		
        if (nomeProfessor != ''){
            nomeProfessor = removerAcentos(nomeProfessor.toUpperCase().replace("PROF. ", "").replace("PROFA. ", ""));
            idMembroBanca = getIdMembroBanca();
            //idProfessor = getIdProfessor();

            if (idMembroBanca == '' && idProfessor == ''){
                alert("Informe um nome de professor válido.\n\nDicas:\n\t1. Selecione uma opção da lista.\n\t2. Ou verifique a existência dele no cadastro de 'Membros de Banca'.\n\t3. Ou verifique a existência dele no cadastro de 'Professores'.");
                return;
            }
        }

        form.task.value = 'emitirRelatorioDefesas';
        window.open(URL='index.php?option=com_controledefesas&task=emitirRelatorioDefesas&dataInicial='+dataInicial+'&dataFinal='+dataFinal+'&nomeProfessor='+nomeProfessor+'&idMembroBanca='+idMembroBanca+'&idProfessor='+idProfessor+'&lang=pt-br');
        //window.location='index.php?option=com_controledefesas&task=emitirRelatorioDefesas&dataInicial='+dataInicial+'&dataFinal='+dataFinal+'&nomeProfessor='+nomeProfessor+'&lang=pt-br';
    }
    
    function formatarData( data ){
        var dataAux = data.split("/");
        return dataAux[0]+'-'+dataAux[1]+'-'+dataAux[2];
    }

    function removerAcentos( stringComAcento ) {
        var string = stringComAcento;
        var mapaAcentosHex = {
            a : /[\xE0-\xE6]/g,
            A : /[\xC0-\xC6]/g,
            e : /[\xE8-\xEB]/g,
            E : /[\xC8-\xCB]/g,
            i : /[\xEC-\xEF]/g,
            I : /[\xCC-\xCF]/g,
            o : /[\xF2-\xF6]/g,
            O : /[\xD2-\xD6]/g,
            u : /[\xF9-\xFC]/g,
            U : /[\xD9-\xDC]/g,
            c : /\xE7/g,
            C : /\xC7/g,
            n : /\xF1/g,
            N : /\xD1/g,
        };

        for ( var letra in mapaAcentosHex ) {
            var expressaoRegular = mapaAcentosHex[letra];
            string = string.replace( expressaoRegular, letra );
        }
        return string;
    }

	jQuery(function() {
		jQuery("#dataInicial").datepicker({
		dateFormat: 'dd/mm/yy',
		dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
		dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
		dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
		monthNames: ['Janeiro','Fevereiro','MarÃ§o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
		nextText: 'Próximo',
		prevText: 'Anterior'});
	});

	jQuery(function() {
		jQuery("#dataFinal").datepicker({
		dateFormat: 'dd/mm/yy',
		dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
		dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
		dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
		monthNames: ['Janeiro','Fevereiro','MarÃ§o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
		nextText: 'Próximo',
		prevText: 'Anterior'});
	});

	jQuery(function() {		
		var membros = [];

		<?php foreach ($this->membrosBanca as $membro ) {
			echo "membros.push('" .$membro->nome. "');";
		}?>
		
		jQuery("#professor").autocomplete({
			source: membros
		});
	});

    (function($) {
        getIdMembroBanca = function () {
            var membros = [];
            var ids = [];
            var index = -1;
            var idMembroBanca = '';
            var nomeProfessor = document.getElementById('professor').value;

            if (nomeProfessor != '') {
                <?php foreach ($this->membrosBanca as $membro) {
                    echo "membros.push('" .$membro->nome. "');";
                    echo "ids.push('" . $membro->id . "');";
                }?>

                index = membros.indexOf(nomeProfessor.trim());
                if (index >= 0){
                    idMembroBanca = ids[index];
                }
            }
            return idMembroBanca;
    }})(jQuery);

    (function($) {
        getIdProfessor = function () {
            var listaProfessores = [];
            var ids = [];
            var index = -1;
            var idProfessor = '';
            var nomeProfessor = document.getElementById('professor').value;

            if (nomeProfessor != '') {
                <?php foreach ($this->professores as $professor) {
                    
                    // Remover acentos do nome e mudá-lo para maiúsculo
                    $nome = strtoupper(preg_replace('/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $professor->nomeProfessor)));

                    echo "listaProfessores.push('" .$nome. "');";
                    echo "ids.push('" . $professor->id . "');";
                }?>
                
                var nome = removerAcentos(nomeProfessor.toUpperCase().replace("PROF. ", "").replace("PROFA. ", ""));

                index = listaProfessores.indexOf(nome.trim());
                if (index >= 0){
                    idProfessor = ids[index];
                }
            }
            return idProfessor;
    }})(jQuery);

</script>

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
            </tr>
            <tr>
				<td><input type="text" name="dataInicial" id="dataInicial" placeholder="dd/mm/aaaa" size="14" maxlength="10" value=""/></td>
            </tr>
            <tr>
                <td>Data Final</td>
            </tr>
            <tr>
				<td><input type="text" name="dataFinal" id="dataFinal" placeholder="dd/mm/aaaa" size="14" maxlength="10" value=""/></td>
            </tr>
            <tr>
                <td>Nome Professor</td>
            </tr>
            <tr>
				<td><input type="text" id="professor" style="width:60%" placeholder="Informe quando desejar filtrar as defesas de um professor."/></td>
            </tr>
            <tr><td> </td></tr>
            <tr><td> </td></tr>
            <tr><td> </td></tr>
            <tr><td> </td></tr>
            <tr>
                <td><a href="javascript:emitirRelatorio(document.form)" title="Visualizar o Relatório em PDF"><span class="btn btn-primary"><i class="icone-search icone-white"></i>  Emitir Relatório</span></a></td>
            </tr>
        </table>
    </fieldset>

    <br />
    <input name='task' type='hidden' value='display' />
</form>

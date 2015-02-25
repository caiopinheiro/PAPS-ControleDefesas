<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$user =& JFactory::getUser();
if(!$user->username) die( 'Acesso Restrito.' );

$document = &JFactory::getDocument();
$document->addStyleSheet('components/com_defesasorientador/template.css','text/css');
$document->addStyleSheet('components/com_defesasorientador/assets/css/estilo.css','text/css');
$document->addScript('components/com_portalsecretaria/jquery.js', 'text/javascript');
$document->addScript('components/com_portalsecretaria/jquery.tablesorter.js', 'text/javascript');
$document->addScript('//code.jquery.com/jquery-1.10.2.js', 'text/javascript');
$document->addScript('//code.jquery.com/ui/1.11.2/jquery-ui.js');
$document->addStyleSheet('//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css');
?>

<?php 

if (($this->aluno[0]->curso == 2) && ($this->faseDefesa[0] == 'T') && ($this->creditos < 32)) {?>
<script>	

alert ('<?php echo mb_convert_encoding('Aluno não possui os 32 créditos necessários para defesa de Tese', 'UTF-8', 'ISO-8859-1');?>');
location.href = 'index.php?option=com_portalprofessor&task=alunos&Itemid=317';

</script>

<?php }?> 

<?php if (($this->aluno[0]->curso == 1) && ($this->faseDefesa[0] == 'D') && ($this->creditos < 24)) {?>
<script>

alert ('<?php echo mb_convert_encoding('Aluno não possui os 24 créditos necessários para defesa de Dissertação', 'UTF-8', 'ISO-8859-1');?>');
location.href = 'index.php?option=com_portalprofessor&task=alunos&Itemid=317';

</script>

<?php }?>


<?php if ($this->aluno[0]->curso == 3) { ?>

<script>

alert ('<?php echo mb_convert_encoding('Aluno especial não possui defesa.', 'UTF-8', 'ISO-8859-1');?>');
location.href = 'index.php?option=com_portalprofessor&task=alunos&Itemid=317';

</script>

<?php }?>

<?php if ($this->semProeficiencia) {?>

<script>

alert ('Aluno sem exame de proeficiÃªncia cadastrado.');
location.href = 'index.php?option=com_portalprofessor&task=alunos&Itemid=317';

</script>


<?php }?>
<?php 

if ($this->existeSemAprovacao) {	

?>

<script>

alert('Existe <?php echo mb_convert_encoding($this->nomeFase[$this->faseDefesa[0]], 'UTF-8', 'ISO-8859-1');?> cadastrada sem conceito');
location.href = 'index.php?option=com_portalprofessor&task=alunos&Itemid=317';

<?php 

/**
 * TODO verificar pra onde redireciona
 */
		?>

</script>

	
<?php } else if ($this->finalizouCurso) {?>

<script>

alert('Aluno jÃ¡ finalizou o seu curso');
location.href = 'index.php?option=com_portalprofessor&task=alunos&Itemid=317';
		
</script>

<?php } else if ($this->orientadorSemMembroBanca) { ?>

<script>

alert ('<?php echo mb_convert_encoding('Orientador sem correspondente em registros de membros de banca. Favor contatar a secretaria.', 'UTF-8', 'ISO-8859-1');?>');
location.href = 'index.php?option=com_portalprofessor&task=alunos&Itemid=317';

</script>

<?php }?>



<script> 

function enviarForm() {

	var form1 = document.form1;

	//validaForm();
	
	form1.submit();

	//validaBanca();

	
}

function validaForm() {

	// validar data de acordo com horário do server (inserir php)
	
	valido = true;

	var maximo = new Date('<?php $date = date_create(); echo date_format($date, 'Y/m/d');?>');

	// verificar regra de negócio
	maximo.setDate(maximo.getDate() + 30);
	
	var dataDefesa = document.getElementById('datepicker').value;
	var dataSplit = dataDefesa.split('/');
	var dataString = dataSplit[2] + '/' + dataSplit[1] + '/' + dataSplit[0];
	var dataForm = new Date(dataString);
	
	total = diferencaDatas(dataForm, maximo);
	
	if (total < 0) {

		valido = false;

		alert('Data com antecedência menor que o permitido (30 dias)');
		
	}
}


function diferencaDatas(date1, date2) {

var diferenca = date1 - date2; //diferença em milésimos e positivo
var dia = 1000*60*60*24; // milésimos de segundo correspondente a um dia
var total = Math.round(diferenca/dia); //valor total de dias arredondado 

return total;
} 


(function($) {
	  validaBanca = function () {


		  // aplicar regras de negócio
			var table = $ ("#tablebanca");

			var trs = table.find('tr');

			var resultado = false;
			
					
				$(table).find('tr').each(function(indice){
			    var row = $(this).find('td').toArray();

			    var select = document.getElementById('mBanca');
				var option = select.options[select.selectedIndex];
				var professoresInst = option.text.split(' - ');

				if ($(row[1]).text() == professoresInst[0])
					resultado = true;
					
			});
			
			return resultado;
	  };
	})(jQuery);


</script>
<div id="toolbar-box"><div class="m
"><div class="toolbar-list" id="toolbar">
<div class="cpanel2">

<div class="icon" id="toolbar-salvar">
<a href="javascript:enviarForm()">
<span class="icon-32-save-new"></span>Salvar</a>
</div>


<div class="icon" id="toolbar-cancel">
<a href="index.php?option=com_portalprofessor&task=alunos&Itemid=317&lang=pt-br" class="toolbar">
<span class="icon-32-cancel"></span>Cancelar</a>
</div>
</div>
<div class="clr"></div>
</div>

<div class="pagetitle icon-48-groups"><h2>Solicitar Banca - <?php echo mb_convert_encoding($this->nomeFase[$this->faseDefesa[0]], 'UTF-8', 'ISO-8859-1');	?></h2></div>
</div></div>


<form name="form1" method="post" action="index.php?option=com_defesasorientador&task=cadastrarbanca" enctype="multipart/form-data">
<table width="100%">
		<thead>
        	<h2>Aluno</h2>
        </thead>
		<input type="hidden" name="idAluno" value="<?php echo $this->aluno[0]->id?>"/>		
        <input type="hidden" name="tipoDefesa" value="<?php echo $this->faseDefesa[0]?>" />
        <hr />
        <tbody>
        <tr >
        <td>
        <label>Nome</label> <input type="text" name="nomeAluno" value="<?php echo $this->aluno[0]->nome;?>" disabled="disabled"/>
        </td>
        <td>
        <input type="hidden" name="idaluno" value="<?php echo $this->aluno[0]->id?>" />
        
        <?php 
        	switch ($this->aluno[0]->curso)
        	{
        		case 1: $curso = 'Mestrado';
        			break;
        		case 2: $curso = 'Doutorado';
        			break;
        		case 3: $curso = 'Especial';
        			break;
        		default: break;
        	}
        ?>
        
        
        <label>Curso:</label> <input type="text" name="curso" value="<?php echo $curso;?>" disabled="disabled"/>
        </td>
        </tr>
        
        <tr>
        	<td> <label>Linha de pesquisa</label> <input type="text" name="linhapesquisa" value="<?php echo $this->aluno[0]->linhapesquisa;?>" disabled="disabled" style="width:90%"/></td>
        	
  			<td> <label>Ingresso</label> <input type="text" name="ingresso" value="<?php echo  JHTML::_('date',$this->aluno[0]->anoingresso, JText::_('d/m/Y')); ?>" disabled="disabled" /></td>
        </tr>
			</tbody>
			
	</table>
	
	
	<script>
  $(function() {
        	$("#datepicker").datepicker({
    	    dateFormat: 'dd/mm/yy',
    	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    	    monthNames: ['Janeiro','Fevereiro','MarÃ§o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    	    nextText: 'Próximo',
    	    prevText: 'Anterior'});
  });

  function adicionarMembro()
  {

		if (!verificaMembroBanca()) {
			if (!verificaPresidente()) {	
			AddTableRow();
			} else alert('JÃ¡ existe o Presidente da banca.');
		}
		else alert('Professor jÃ¡ estÃ¡ inserido nesta banca.');	
			
  }

  (function($) {
	  verificaMembroBanca = function () {

			var table = $ ("#tablebanca");


			var trs = table.find('tr');

			var resultado = false;
			
			
			$(table).find('tr').each(function(indice){
			    var row = $(this).find('td').toArray();

			    var select = document.getElementById('mBanca');
				var option = select.options[select.selectedIndex];
				var professoresInst = option.text.split(' - ');

				if ($(row[1]).text() == professoresInst[0])
					resultado = true;
					
			});
			
			return resultado;
	  };
	})(jQuery);

	(function($) {
		carregaMembrosInterno = function () {


			var membros = [];
			var ids = [];
			var filiacao = [];

			
			<?php foreach ($this->membrosInternos as $membro ) {
			
				echo "membros.push('" .$membro->nome. "');";
				echo "ids.push('" . $membro->id . "');";
				echo "filiacao.push('" . $membro->filiacao. "');";
				
				}?>

		
		$("#mBanca option").remove();

		var i;


		$("#mBanca").append('<option value="-1">Selecione o membro interno</option>');
		
		
		for (i = 0; i < membros.length; i++)
		{
			$("#mBanca").append('<option value="' + ids[i] + '">' + membros[i] + ' - ' + filiacao[i] +'</option>');
		}
		};
		
	})(jQuery);

	(function($) {
		changeLocal = function () {

		var selected = $('#tLocal option:selected').val();

		if (selected == 'I') {

			$('#divLocal').css('display', 'none');

		} else {
			$('#divLocal').css('display', 'block');
		}
		
	}})(jQuery);
	
	(function($) {
		carregaMembrosExterno = function () {

			var membros = [];
			var ids = [];
			var filiacao = [];

			
			<?php foreach ($this->membrosExternos as $membro ) {
			
				echo "membros.push('" . $membro->nome. "');";
				echo "ids.push('" . $membro->id . "');"; 
				echo "filiacao.push('" . $membro->filiacao. "');";
				
				}?>

					

		$("#mBanca option").remove();

		var i;


		$("#mBanca").append('<option value="-1">Selecione o membro externo</option>');
		
				
		for (i = 0; i < membros.length; i++)
		{
			$("#mBanca").append('<option value="' + ids[i] + '">' + membros[i] + ' - ' + filiacao[i] +'</option>');
		}
			
		};
	})(jQuery);


	  (function($) {
	  verificaPresidente = function () {

			var table = $("#tablebanca");

			var trs = table.find('tr');
			console.debug(trs);	

			var resultado = false;
			
			$(table).find('tr').each(function(indice){
			    var row = $(this).find('td').toArray();

				var tipoMembroBanca;

				var vTipoMembro = $("input[type='radio'][name='tipoMembro']:checked").val();
				
				switch (vTipoMembro){

				case 'P': tipoMembroBanca = 'Presidente';
						break;
				case 'I': tipoMembroBanca = 'Membro Interno';
						break;
				case 'E': tipoMembroBanca = 'Membro Externo';
						break;
					default: break;
				}	
	
				
				if (tipoMembroBanca == 'Presidente' && tipoMembroBanca == $(row[3]).text()) {
					resultado = true;
				}
				
			});
			
			return resultado;
	  };
	})(jQuery);

  (function($) {
	  AddTableRow = function() {
		  
	    var newRow = $("<tr>");
	    var cols = "";

		var select = document.getElementById('mBanca');
		var option = select.options[select.selectedIndex];

		var professoresInst = option.text.split(' - ');
		var vMembroBanca = option.value;
		
		var tipoMembroBanca;

		var vTipoMembro = $("input[type='radio'][name='tipoMembro']:checked").val();
		
		switch (vTipoMembro){

		case 'I': tipoMembroBanca = 'Membro Interno';
				break;
		case 'E': tipoMembroBanca = 'Membro Externo';
				break;
			default: break;
		}	

		var display;
		if (vTipoMembro == 'I') {
			display = 'none';
		} else display = 'block';
		
	    cols += '<td>';
	    cols += '<button onclick="RemoveTableRow(this)" type="button">Remover</button>';
	    cols += '</td>';

		
		cols += '<td>' + professoresInst[0] + '</td>' ; 		
		cols += '<td>' + professoresInst[1] + '</td>' ; 
		cols += '<td>' + tipoMembroBanca + '</td>';
		cols += '<td>';
		cols += '<select name="passagem[]" style="display:' + display + '; width:100%">';
		cols += '<option value="N" selected="selected">' + "<?php echo  mb_convert_encoding('Não', 'UTF-8', 'ISO-8859-1')?>" + '</option>';
		cols += '<option value="S">Sim</option>';
		cols += '</select></td>'; 
		
		cols += '<input type="hidden" name="idMembroBanca[]" value="'+vMembroBanca+'" />';
		cols += '<input type="hidden" name="tipoMembroBanca[]" value="'+vTipoMembro+'" />';	
				
	    newRow.append(cols);

		if (vMembroBanca != -1)
	    $("#tablebanca").append(newRow);


	    return false;
	  };
	})(jQuery);

	(function($){
		RemoveTableRow = function(button) {

			$(button).closest('tr').remove();
			
		};

	})(jQuery);
  
  </script>
<table width="100%">
		<thead>
        	<h2>Dados da defesa</h2>
        </thead>
        
        <hr />
        <tbody>
           <?php 
       	if ($this->mensagens['titulo']) 
			echo '<span class="msgError">' .  mb_convert_encoding('Informar o título da defesa.', 'UTF-8', 'ISO-8859-1') . '</span>';
        ?>
        
        <tr ><td colspan="4">
         
        <label><?php echo mb_convert_encoding('Título', 'UTF-8', 'ISO-8859-1'); ?></label>
         <input name="titulodefesa" style="width:95%" value="<?php if(!is_null($this->titulo)) echo $this->titulo;?>"></td></tr>
        <tr >
        <td>

        <?php 
        
        if ($this->mensagens['dataInvalida'])
        	echo '<span class="msgError">' .  mb_convert_encoding('Informar uma data válida.', 'UTF-8', 'ISO-8859-1') . '</span>';
        if ($this->mensagens['dataAnterior'])
        	echo '<span class="msgError">' .  mb_convert_encoding('Informar uma data com antecedência mínima de 30 dias.', 'UTF-8', 'ISO-8859-1') . '</span>';
        	?>
        <label>Data da defesa</label> <input type="text" name="datadefesa" id="datepicker" value="<?php if (!is_null($this->datadefesa)) echo $this->datadefesa;?>"/>
        </td>
        <td>
        
        <?php 
        	switch ($this->aluno[0]->curso)
        	{
        		case 1: $curso = 'Mestrado';
        			break;
        		case 2: $curso = 'Doutorado';
        			break;
        		case 3: $curso = 'Especial';
        			break;
        		default: break;
        	}
        ?>
        </td>
        </tr>
        <tr>
        	<td>
        	  <?php 
       	if ($this->mensagens['resumo']) 
			echo '<span class="msgError">' .  mb_convert_encoding('Informar o resumo da defesa.', 'UTF-8', 'ISO-8859-1') . '</span>';
        ?>
        
        		<label>Resumo </label>
        		
        		<textarea rows="6" cols="100" name="resumodefesa"><?php if (!is_null($this->resumo)) echo $this->resumo;?></textarea>
        	</td>
        	
        
        </tr>
        
        <tr>
        	<td>
       	<?php 
       	if ($this->mensagens['semArquivo']) 
			echo '<span class="msgError">' .  mb_convert_encoding('Selecionar o arquivo de prévia da defesa (Formato PDF).', 'UTF-8', 'ISO-8859-1') . '</span>';
			
			if ($this->mensagens['arquivoTamanho']) 
			echo '<span class="msgError">' .  mb_convert_encoding('Selecionar arquivo menor que 10 MB.', 'UTF-8', 'ISO-8859-1') . '</span>';
			
			IF ($this->mensagens['arquivoFormato']) 
			echo '<span class="msgError">' .  mb_convert_encoding('Selecionar arquivo em formato PDF.', 'UTF-8', 'ISO-8859-1') . '</span>';
				
        ?>
        
        
        	<label><?php echo mb_convert_encoding('Prévia', 'UTF-8', 'ISO-8859-1'); ?></label><input type="file" id="previa" name="previa" />
        <?php 
        	if (isset($this->urlPrevia)) {
			
				echo '<a target="_blank" href="' . $this->urlPrevia .  '">'.  mb_convert_encoding('Prévia', 'UTF-8', 'ISO-8859-1') . '</a>'; 
				echo '<input type="hidden" name="previaanterior" value="' . $this->previa . '"/>';

			}
        ?>
        	</td>
        		
       	<!-- 
       	<td >
       
       	<label>Local</label>
        		<select name="tipolocal" id="tLocal" onchange="javascript:changeLocal();">
        			<option value="I" <?php 
        			
        			if (isset($this->tipoLocal)) {
        									if ($this->tipoLocal == 'I') echo 'selected="selected"';
        			} else echo 'selected="selected"';
        				
        				?>>Interno</option>
        			<option value="E" <?php if (isset($this->tipoLocal))
        									if ($this->tipoLocal == 'E') echo 'selected="selected"'?>>Externo</option>
        		</select>
        </td>
        	 -->
        
        </tr>
        
        <?php if ($this->faseDefesa[0] == 'Q1' && $this->aluno[0]->curso == 2) { ?>
        <tr>
        	<td>
        	  <?php 
       	if ($this->mensagens['examinador']) 
			echo '<span class="msgError">' .  mb_convert_encoding('Informar o examinador da qualificação.', 'UTF-8', 'ISO-8859-1') . '</span>';
        ?>
        
  	      		<label>Examinador</label>
        		
        		<input type="text" name="examinador" value="<?php if (!is_null($this->examinador)) echo $this->examinador;?>" />
        	</td>
        
        </tr>
        
        <tr>
        	<td>
        	  <?php 
       	if ($this->mensagens['emailexaminador']) 
			echo '<span class="msgError">' .  mb_convert_encoding('Informar o email do examinador da qualificação.', 'UTF-8', 'ISO-8859-1') . '</span>';
        ?>
        
        		<label>E-mail do Examinador</label>
        		
        		<input type="text" name="emailexaminador" value="<?php if (!is_null($this->emailexaminador)) echo $this->emailexaminador;?>" />
        	</td>
        
        </tr>
        
        <?php }?>
			</tbody>
			
	</table>

	<div id="divLocal" style="display:<?php if ($this->faseDefesa[0] == 'Q1' && $this->aluno[0]->curso == 2) echo 'none'; else echo 'block';?>;">   
	<table width="100% <?php if ($this->faseDefesa[0] == 'Q1' && $this->aluno[0]->curso == 2) echo 'style="display:none"'?>">
		<thead>
        	<h2>Local da defesa</h2>
        </thead>
        
        
        <hr />
        
        <tbody>
        <tr>
        	<td >
        	 <?php if ($this->mensagens['local']) 
						echo '<span class="msgError">' . mb_convert_encoding('Informar o local da defesa.', 'UTF-8', 'ISO-8859-1') . '</span>';
        	    	
        	    	?>   	
        		<label>Local</label>
        		<input type="text" name="localDescricao" size="80" value="<?php if (isset($this->localDescricao)) echo $this->localDescricao ?>"/>
        	</td>
        </tr>
        <tr>
        <td>
        	    <?php if ($this->mensagens['horario']) 
						echo '<span class="msgError">' . mb_convert_encoding('Informar o horário da defesa.', 'UTF-8', 'ISO-8859-1') . '</span>';
        	    	
        	    	?>   	
        		<label><?php echo mb_convert_encoding('Horário', 'UTF-8', 'ISO-8859-1')?></label>
        		<input type="text" name="localHorario" value="<?php if (isset($this->localHorario)) echo $this->localHorario ?>"/>
        	</td>
        </tr>		
        </tbody>
        
        </table>
	</div>
	
	
	<table width="100%" <?php if ($this->faseDefesa[0] == 'Q1' && $this->aluno[0]->curso == 2) echo 'style="display:none"'?>>
		<thead>
        	<h2  <?php if ($this->faseDefesa[0] == 'Q1' && $this->aluno[0]->curso == 2) echo 'style="display:none"'?>>Membros da Banca</h2>
        </thead>
        
        
        
        <hr  <?php if ($this->faseDefesa[0] == 'Q1' && $this->aluno[0]->curso == 2) echo 'style="display:none"'?> />
        
          <?php 
       	if ($this->mensagens['semMembros']) 
			echo '<span class="msgError">' .  mb_convert_encoding('Selecionar os membros da banca.', 'UTF-8', 'ISO-8859-1') . '</span>';
			
			if ($this->mensagens['semMembrosInternos']) 
			echo '<span class="msgError">' .  mb_convert_encoding('Selecionar no mínimo um membro interno.', 'UTF-8', 'ISO-8859-1') . '</span><br />';
			
			if ($this->mensagens['semMembrosExternos']) 
			echo '<span class="msgError">' .  mb_convert_encoding('Selecionar no mínimo um membro externo.', 'UTF-8', 'ISO-8859-1') . '</span>';
				
			
        ?>
        
     
	</table>
	
	<table width="100%" id="tablebanca" class="table-bordered" <?php if ($this->faseDefesa[0] == 'Q1' && $this->aluno[0]->curso == 2) echo 'style="display:none"'?>>
		<thead>
			<td style="width:10%;"></td>
        	<td style="width:50%;">Professor</td>
        	<td style="width:15%;">InstituiÃ§Ã£o</td>
        	<td style="width:15%;">FunÃ§Ã£o</td>
        	<td style="width:10%;">Passagem?</td>  
        </thead>
        <tbody>

        	<?php 
        	if (isset($this->membrosBancaTabela['id']))
        	for($count = 0; $count < count($this->membrosBancaTabela['id']); $count++) {


				if ($this->membrosBancaTabela['tipoMembro'][$count] == 'P')  continue;
        	
        		$cols = "<tr>";
        	
        		switch ($this->membrosBancaTabela['tipoMembro'][$count]){
        			case 'I': $tipoMembroBanca = 'Membro Interno';
        			break;
        			case 'E': $tipoMembroBanca = 'Membro Externo';
        			break;
        			default: break;
        		}
        		
        		
        		$selectedNao = '';
        		$selectedSim = '';
        		
        		$display = ($this->membrosBancaTabela['tipoMembro'][$count] == 'I' ? 'none' : 'block');
        		
        		if ($this->membrosBancaTabela['passagem'][$count] == 'N')
        			$selectedNao = 'selected="selected"';
        		else $selectedSim = 'selected="selected"';
        			
        		$cols = $cols . '<td>';
        		$cols = $cols . '<button onclick="RemoveTableRow(this)" type="button">Remover</button>';
        		$cols = $cols . '</td>';
        	
        		$cols = $cols .  '<td>' . mb_convert_encoding($this->membrosBancaTabela['nome'][$count], 'UTF-8', 'ISO-8859-1') . '</td>' ;
        		$cols = $cols .  '<td>' . mb_convert_encoding($this->membrosBancaTabela['filiacao'][$count] , 'UTF-8', 'ISO-8859-1') . '</td>' ;
        		$cols = $cols .  '<td>' . $tipoMembroBanca . '</td>';
        		$cols = $cols . '<td>';
        		$cols = $cols . '<select name="passagem[]" style="display:' . $display . '; width:100%">';
        		$cols = $cols . '<option value="N" ' . $selectedNao . '>' . mb_convert_encoding('Não', 'UTF-8', 'ISO-8859-1') . '</option>';
        		$cols = $cols . '<option value="S" ' . $selectedSim . '>Sim</option>';
        		$cols = $cols . '</select>';
        		$cols = $cols . '</td>';
        		$cols = $cols .  '<input type="hidden" name="idMembroBanca[]" value="'. $this->membrosBancaTabela['id'][$count] . '" />';
        		$cols = $cols .  '<input type="hidden" name="tipoMembroBanca[]" value="' . $this->membrosBancaTabela['tipoMembro'][$count]. '" />';
        		$cols = $cols . '</tr>';
        		
        			
        	//	var_dump($cols);
        	echo $cols;
        		
        	}
        	?>
        	
      
        	
        </tbody>
        
      
     
	</table>	
	
	  <hr />
	<br />
	<br />
	<br />
	<br />
	<br />
	
	
	<!-- TODO membros da banca já adicionados carregar
	 -->
	<table width="100%" id="table-membrosbanca" <?php if ($this->faseDefesa[0] == 'Q1' && $this->aluno[0]->curso == 2) echo 'style="display:none"'?>>
	
		<tr>
		<td style="width:25%"> <legend> Tipo de membro </legend>
  
   <!-- presidente é o orientador -->
 <!-- <label> <input type="radio" id="rdioPresidente" name="tipoMembro" checked="checked" value="P"> Presidente </label> --> 
  <label> <input type="radio" id="rdioMembroInterno" name="tipoMembro" value="I" checked="checked" onchange="javascript:carregaMembrosInterno()"> Membro Interno</label>
   <label> <input type="radio" id="rdioMembroExterno" name="tipoMembro" value="E" onchange="javascript:carregaMembrosExterno()"> Membro Externo</label>
   
		</td>
		<td style="width:60%; padding-left:20px; ">
		<select name="professores" id="mBanca" style="width:80%">
		
		</select>
				
		</td>
		
		<!-- 
		<td colspan="5">
			<input type="radio" name="tipoMembro" value="p" style="display:inline;" id="rdioPresidente"/><label style="display:inline;">Presidente</label>
			<input type="radio" name="tipoMembro" value="mi" style="display:inline;" id="rdioMembroInterno"/><label for="rdioMembroInterno">Membro Interno</label>
			<input type="radio" name="tipoMembro" value="me" id="rdioMembroExterno"/><label for="rdioMembroExterno">Membro Externo</label>
		</td>
		 -->
		 <td style="width:10%"><a href="javascript:adicionarMembro();"> <img alt="Adicionar Membro da Banca" src="components/com_defesasorientador/assets/img/add-icon.png" /></a></td>
		 <td>
		 <fieldset>
   <input type="hidden" id="orientadorNome" value="<?php echo mb_convert_encoding($this->orientador[0]->nomeProfessor, 'UTF-8', 'ISO-8859-1'); ?>" />
  <input type="hidden" id="orientadorFiliacao" value="<?php echo mb_convert_encoding($this->orientador[0]->filiacao, 'UTF-8', 'ISO-8859-1'); ?>" />
  
  
 
 <script>

 	function adicionaPresidente() {

 	   var newRow = $("<tr>");
	   var cols = "";
		var orientadorNome = document.getElementById('orientadorNome').value;
		var orientadorFiliacao = document.getElementById('orientadorFiliacao').value;

	    cols += '<td>';
	    cols += '';
	    cols += '</td>';

	    
	    
		
		cols += '<td>' + orientadorNome + '</td>' ; 		
		cols += '<td>' + orientadorFiliacao+ '</td>' ; 
		cols += '<td>' + 'Presidente' + '</td>';
 	}

 	adicionaPresidente();

 </script>
 
 <script>

 (function($) {
	  carregaPresidente = function() {
		  
	    var newRow = $("<tr>");
	    var cols = "";

		var presidente = '<?php echo $this->presidente->nome;?>';
		var filiacao = '<?php echo $this->presidente->filiacao;?>';
		var tipoMembroBanca = 'Presidente';
		
	    cols += '<td>';
	    cols += '</td>';
	
		cols += '<td>' + presidente+ '</td>' ; 		
		cols += '<td>' + filiacao + '</td>' ; 
		cols += '<td>' + tipoMembroBanca + '</td>';
		cols += '<input type="hidden" name="idMembroBanca[]" value="<?php echo $this->presidente->id?>" />';
		cols += '<input type="hidden" name="tipoMembroBanca[]" value="P" />';
		cols = cols + '<select name="passagem[]" style="display:none; width:100%">';
		cols = cols + '<option value="N" ' +  'selected="selected"' + '>' + '</option>';
		cols = cols + '<option value="S" >Sim</option>';
		cols = cols + '</select>';
		
		cols += '<td>';
		cols += '</td>'; 
		
				
	    newRow.append(cols);

	    $("#tablebanca").append(newRow);

	    return false;
	  };
	})(jQuery);


 </script>
 
   <script>
   		$('#rdioMembroInterno').prop('checked', true);
		carregaMembrosInterno();
		carregaPresidente();
		//changeLocal();
   </script>
  <!-- etc -->
	</fieldset>
</td>
		 </tr>
	</table>
</form>




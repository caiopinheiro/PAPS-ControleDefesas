<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
$document->addStyleSheet('components/com_defesasorientador/template.css','text/css');
$document->addStyleSheet('components/com_defesasorientador/assets/css/estilo.css','text/css');
$document->addScript('components/com_portalsecretaria/jquery.js', 'text/javascript');
$document->addScript('components/com_portalsecretaria/jquery.tablesorter.js', 'text/javascript');
$document->addScript('//code.jquery.com/jquery-1.10.2.js', 'text/javascript');
$document->addScript('//code.jquery.com/ui/1.11.2/jquery-ui.js');
$document->addStyleSheet('//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css');

//echo $document->getCharset(); 
?>

<?php if ($this->semProeficiencia) {?>

<script>

alert ('Aluno sem exame de proeficiência cadastrado.');
location('index.php?option=com_portalprofessor&task=alunos&Itemid=317');

</script>




<?php }?>
<?php 

if ($this->existeSemAprovacao) {	

?>

<script>

alert ('Existe <?php echo $this->nomeFase[$this->faseDefesa[0]]?>cadastrada sem conceito');
location('index.php?option=com_portalprofessor&task=alunos&Itemid=317');

</script>

	
<?php } else if ($this->finalizouCurso) {?>

<script>

alert('Aluno já finalizou o seu curso');
location('index.php?option=com_portalprofessor&task=alunos&Itemid=317'); 

</script>

<?php } ?>
<div id="toolbar-box"><div class="m
"><div class="toolbar-list" id="toolbar">
<div class="cpanel2">

<div class="icon" id="toolbar-listadefesas">
<a href="javascript:visualizar(document.form)" class="toolbar">
<span class="icon-32-back"></span>Lista de defesas</a>
</div>

<div class="icon" id="toolbar-salvar">
<a href="index.php?option=com_portalprofessor&Itemid=<?php echo $Itemid;?>">
<span class="icon-32-save-new"></span>Salvar</a>
</div>


<div class="icon" id="toolbar-cancel">
<a href="javascript:visualizar(document.form)" class="toolbar">
<span class="icon-32-cancel"></span>Cancelar</a>
</div>
</div>
<div class="clr"></div>
</div>

<div class="pagetitle icon-48-groups"><h2>Solicitar Banca - <?php echo $this->nomeFase[$this->faseDefesa[0]]?></h2></div>
</div></div>


<form name="form1" method="post" action="index.php?option=com_defesasorientador&task=cadastrarbanca" enctype="multipart/form-data">
<table width="100%">
		<thead>
        	<h2>Aluno</h2>
        </thead>
        
        <hr />
        <tbody>
        <tr >
        <td>
        <label>Nome:</label> <input type="text" name="nomeAluno" value="<?php echo $this->aluno[0]->nome;?>" disabled="disabled"/>
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
    	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    	    nextText: 'Próximo',
    	    prevText: 'Anterior'});
  });

  function adicionarMembro()
  {

		if (!verificaMembroBanca()) {
			if (!verificaPresidente()) {	
			AddTableRow();
			} else alert('Já existe o Presidente da banca.');
		}
		else alert('Professor já está inserido nesta banca.');	
			
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

				case 'p': tipoMembroBanca = 'Presidente';
						break;
				case 'mi': tipoMembroBanca = 'Membro Interno';
						break;
				case 'me': tipoMembroBanca = 'Membro Externo';
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

		case 'p': tipoMembroBanca = 'Presidente';
				break;
		case 'mi': tipoMembroBanca = 'Membro Interno';
				break;
		case 'me': tipoMembroBanca = 'Membro Externo';
				break;
			default: break;
		}	
		
	    cols += '<td>';
	    cols += '<button onclick="RemoveTableRow(this)" type="button">Remover</button>';
	    cols += '</td>';
		
		cols += '<td>' + professoresInst[0] + '</td>' ; 		
		cols += '<td>' + professoresInst[1] + '</td>' ; 
		cols += '<td>' + tipoMembroBanca + '</td>';
		cols += '<input type="hidden" name="idMembroBanca" value="'+vMembroBanca+'" />';
		cols += '<input type="hidden" name="tipoMembroBanca" value="'+vTipoMembro+'" />';	
				
	    newRow.append(cols);
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
        
        <tr ><td colspan="4"><label><</label>
         <input name="titulodefesa" style="width:95%"></td></tr>
        <tr >
        <td>
        <label>Data da defesa:</label> <input type="text" name="datadefesa" id="datepicker" />
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
        		<label>Resumo: </label>
        		
        		<textarea rows="6" cols="100" name="resumodefesa"></textarea>
        	</td>
        	
        
        </tr>
        
			</tbody>
			
	</table>



	
	<table width="100%">
		<thead>
        	<h2>Membros da Banca</h2>
        </thead>
        
        <hr />
     
	</table>
	
	<table width="100%" id="tablebanca" class="table-bordered">
		<thead>
			<td style="width:10%;"></td>
        	<td style="width:50%;">Professor</td>
        	<td style="width:20%;">Instituição</td>
        	<td style="width:20%;">Função</td>
        </thead>
        <tbody>
        
        </tbody>
        
        <hr />
     
	</table>	
	
	<br />
	<br />
	<br />
	<br />
	<br />
	<table width="100%" id="table-membrosbanca">
		<tr><td style="width:10%"><a href="javascript:adicionarMembro();"> <img alt="Adicionar Membro da Banca" src="components/com_defesasorientador/assets/img/add-icon.png" /></a></td>
		<td style="width:40%">
		<select name="professores" id="mBanca" style="width:80%">
		
		<option value="-1">Selecionar Membro da Banca</option>
		<?php 
		
		foreach ($this->membrosbanca as $membrobanca) 
		{
			echo '<option value="' . $membrobanca->id . '">' . $membrobanca->nome . ' - ' . $membrobanca->filiacao . '</option>';
		}
		?>
		
		</select>
				
		</td>
		
		<!-- 
		<td colspan="5">
			<input type="radio" name="tipoMembro" value="p" style="display:inline;" id="rdioPresidente"/><label style="display:inline;">Presidente</label>
			<input type="radio" name="tipoMembro" value="mi" style="display:inline;" id="rdioMembroInterno"/><label for="rdioMembroInterno">Membro Interno</label>
			<input type="radio" name="tipoMembro" value="me" id="rdioMembroExterno"/><label for="rdioMembroExterno">Membro Externo</label>
		</td>
		 -->
		 <td>
		 <fieldset>
  <legend> Tipo de membro </legend>
  <label> <input type="radio" id="rdioPresidente" name="tipoMembro" checked="checked" value="p"> Presidente </label>
  <label> <input type="radio" id="rdioMembroInterno" name="tipoMembro" value="mi"> Membro Interno</label>
   <label> <input type="radio" id="rdioMembroExterno" name="tipoMembro" value="me"> Membro Externo</label>
  <!-- etc -->
	</fieldset>
</td>
		 </tr>
	</table>
</form>




<script language="JavaScript">
       
        function indeferirBanca(form){        
           var confirmar;
           var indeferir = 0;
            
           confirmar = window.confirm('Você tem certeza que deseja INDEFERIR essa banca?');
			
           if(confirmar == true){
				form.task.value = 'indeferirBanca';
				form.avaliacao.value = indeferir;
				form.justificativa.value = "";
				form.submit();
           }
           
        }
        
</script>


<link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css">

<div id="toolbar-box">
	<div class="m">
		<div class="toolbar-list" id="toolbar">
		  <div class="cpanel2">
				<div class="icon" id="toolbar-back">
					<a href ="javascript:indeferirBanca(document.form)" class = 'toolbar'>
					<span class="icon-32-save"></span>Salvar</a>
				</div>
				
		</div>
		<div class="clr"></div>
		</div>

	<div class="pagetitle icon-48-user"><h2><?php echo Justificativa; ?></h2></div>
	</div>
</div>
 
 <form method="post" name="form" enctype="multipart/form-data" action="index.php?option=com_defesascoordenador&view=avaliarBanca" >
	
	<table width="100%">
		<tr>
			<td>Digite a Justificativa de Deferimento da Banca:</td>
		</tr>
		<tr>
			<td ><input size= 100  id="justificativa" name="justificativa" type="text" value="<?php echo $justificativa;?>"/></td>
		</tr>
	</table>
</form>

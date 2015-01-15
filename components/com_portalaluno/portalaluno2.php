<?php
	$user =& JFactory::getUser();
	if(!$user->username) die( 'Acesso Restrito.' );
	
	include_once("components/com_portalaluno/portalaluno.html.php");
	include_once("components/com_portalaluno/edicao.php");
	include_once("components/com_portalaluno/impressao.php");
	include_once("components/com_portalaluno/prorrogacao.php");
	include_once("components/com_portalaluno/trancamento.php");	
	include_once('pdf-php/class.ezpdf.php');
	//JLoader::register('JSite' , 'components/com_portalaluno/portalaluno.html.php');
	
	$task = JRequest::getCmd('task', false);
	$Itemid = JRequest::getInt('Itemid', 0);
	$database	=& JFactory::getDBO();

	if (isAluno($user->id)) {	
		$idAluno = JRequest::getVar('idAluno');
		
		if ($idAluno) {
			$alunos = identificarAluno($user->id);
			$aluno2 = identificarAlunoID($idAluno);
			$erro = 1;
			foreach($alunos as $aluno1){
   			  if($aluno1->id == $aluno2->id)
			    $erro = 0;
			}
			if($erro)
    			die( 'VocÃª esta tentando acessar a Ã¡rea de outro aluno!!!' );
		}
	}

	switch ($task) {
	
		// FUNCINONALIDADES DE MATRÃCULA
		case "matricula":
		  $idAluno = JRequest::getCmd('idAluno', false);
		  $aluno = identificarAlunoID($idAluno);
	
		  if($aluno){
			$idAluno = ($aluno->id);
			
			$sql = " SELECT * FROM `#__periodos` WHERE status = 1 ";
			$database->setQuery( $sql );
			$periodos = $database->loadObjectList();
			$periodo = $periodos[0];
			
			if($aluno->curso <> 2) $curso = 1;
			else $curso = 2;
			
			$myDisciplinas = getMyDisciplinas($aluno->id , $periodo->id);
			$disciplinas = disciplinasCadastradas($periodo->id, $curso);
			
			$sql = " SELECT * FROM #__matricula WHERE idAluno = $idAluno AND idPeriodo = ".$periodo->id;
			$database->setQuery( $sql );
			$matriculas = $database->loadObjectList();
			$fasePesquisa = NULL;
			if($matriculas) $fasePesquisa = $matriculas[0]->fasePesquisa;
			
			formularioMatricula($aluno, $myDisciplinas, $disciplinas, $periodo, $fasePesquisa);
			
		  }
		  else{
			formLogin(true);
		  }
		break;
		
		case "servicos":
		  $idAluno = JRequest::getCmd('idAluno', false);
		  $aluno = identificarAlunoID($idAluno);
		  servicosPortalAlunoItem($aluno);
		break;
	
    	case "declaracao":
		  $idAluno = JRequest::getCmd('idAluno', false);
		  $aluno = identificarAlunoID($idAluno);
		  imprimirDeclaracao($aluno);
		break;
	
		case "historico":
		  $idAluno = JRequest::getVar('idAluno');
		  $aluno = identificarAlunoID($idAluno);
		  historicoAluno($aluno);
		break;
	
		case "aluno":
		  $idAluno = JRequest::getCmd('idAluno', false);
		  $aluno = identificarAlunoID($idAluno);
		  editarAluno($aluno);
		break;
		
		case "salvarEditar":
		  $idAluno = JRequest::getCmd('idAluno', false);
		  salvarEdicao($idAluno);
		  $aluno = identificarAlunoID($idAluno);
		  servicosPortalAluno($aluno);
		break;
	
		// OPERAÃ‡Ã•ES DE MATRÃCULA
		case "addDisciplina":
			$idDisciplina = JRequest::getVar('novaDisciplina');
			$disciplina = explode("/", $idDisciplina);
			$idAluno = JRequest::getCmd('idAluno', false);
			$idPeriodo = JRequest::getCmd('idPeriodo', false);
			$aluno = identificarAlunoID($idAluno);
			
			$myDisciplinas = getMyDisciplinas($aluno->id, $idPeriodo);
	
			if($aluno->curso <> 2) 
				$curso = 1;
			else 
				$curso = 2;
	
			if (tratamento($idDisciplina,$myDisciplinas)== 0 && $idDisciplina!=0){
				//$turma = ($curso, $idPeriodo, $idDisciplina);
				salvarDisciplina($idAluno,$disciplina[0], $idPeriodo, $disciplina[1]);
		   }
		   
		   $aluno = identificarAlunoID($idAluno);
		   $myDisciplinas = getMyDisciplinas($aluno->id, $idPeriodo);
		
		   $sql = " SELECT * FROM `#__periodos` WHERE id = $idPeriodo";
		   $database->setQuery( $sql );
		   $periodos = $database->loadObjectList();
		
		   $disciplinas = getTabelaDisciplinas($idPeriodo, $curso);
		
		   formularioMatricula($aluno,$myDisciplinas,$disciplinas,$periodos[0], NULL);
		break;

		case "rmDisciplina":
			$idDisciplina = JRequest::getCmd('rmDisc', false);
			$idAluno = JRequest::getCmd('idAluno', false);
			$idPeriodo = JRequest::getCmd('idPeriodo', false);
			
			removerDisciplina($idAluno,$idDisciplina, $idPeriodo);
			
			$aluno = identificarAlunoID($idAluno);
			$myDisciplinas = getMyDisciplinas($aluno->id, $idPeriodo);
			
			$sql = " SELECT * FROM `#__periodos` WHERE id = $idPeriodo";
			$database->setQuery( $sql );
			$periodos = $database->loadObjectList();
	
			if($aluno->curso <> 2) 
				$curso = 1;
			else 
				$curso = 2;
	
			$disciplinas = getTabelaDisciplinas($idPeriodo, $curso);
	
			formularioMatricula($aluno,$myDisciplinas,$disciplinas,$periodos[0], NULL);
		break;
	
		case "salvarMatricula":
			$idAluno = JRequest::getCmd('idAluno', false);
			$idPeriodo = JRequest::getCmd('idPeriodo', false);
			$aluno = identificarAlunoID($idAluno);
			$myDisciplinas = getMyDisciplinas($aluno->id, $idPeriodo);
			
			salvarMatricula($idAluno,$myDisciplinas);	
		break;
	
		case "cancelarMatricula":
			$idAluno = JRequest::getCmd('idAluno', false);
			$aluno = identificarAlunoID($idAluno);
			
			//cancelarMatricula($idAluno);
			servicosPortalAluno($aluno);	
		break;

		case "imprimir":
			$idAluno = JRequest::getCmd('idAluno', false);
			$idPeriodo = JRequest::getCmd('idPeriodo', false);
			$aluno = identificarAlunoID($idAluno);
			
			$sql = "SELECT D.id, D.codigo, D.nomeDisciplina , D.creditos , D.carga, PD.professor, PD.horario, T.turma, PD.curso FROM #__disciplina as D join #__disc_matricula as T join #__periodo_disc as PD on (D.id = T.idDisciplina AND T.idDisciplina = PD.idDisciplina) WHERE T.idAluno = $idAluno AND T.idPeriodo = $idPeriodo AND PD.idPeriodo = $idPeriodo AND T.turma = PD.turma ORDER BY D.codigo";
			$database->setQuery( $sql );
			$disciplinas = $database->loadObjectList();
			
			$sql = " SELECT `semestre`,`fasePesquisa`,`dataPrevista`,`dataMatricula` FROM `#__matricula` WHERE idAluno = $idAluno AND idPeriodo = $idPeriodo";
			$database->setQuery( $sql );
			$matricula = $database->loadObjectList();
			
			imprimirMatricula($aluno,$disciplinas,$matricula);	
		break;

		case "declaracao":
			$idAluno = JRequest::getCmd('idAluno', false);
			$aluno = identificarAlunoID($idAluno);
			
			imprimirDeclaracao($aluno);
		break;

		case "imprimirMatricula":
			$idAluno = JRequest::getCmd('idAluno', false);
			
			$database->setQuery("SELECT id from #__periodos WHERE status = 1 LIMIT 1");
			$idPeriodo = $database->loadObjectList();
	
			if($idPeriodo){
				$aluno = identificarAlunoID($idAluno);
				
				$sql = "SELECT * FROM #__matricula WHERE idAluno = $idAluno AND idPeriodo =". $idPeriodo[0]->id;
				$database->setQuery( $sql );
				$matricula = $database->loadObjectList();
				
				if($matricula){
					$sql = "SELECT D.id, D.codigo, D.nomeDisciplina , D.creditos , D.carga, PD.professor, PD.horario, T.turma, PD.curso FROM #__disciplina as D join #__disc_matricula as T join #__periodo_disc as PD on (D.id = T.idDisciplina AND T.idDisciplina = PD.idDisciplina) WHERE T.idAluno = $idAluno AND T.idPeriodo =". $idPeriodo[0]->id ." AND PD.idPeriodo =". $idPeriodo[0]->id ." AND T.turma = PD.turma ORDER BY D.codigo";
					$database->setQuery( $sql );
					$disciplinas = $database->loadObjectList();
					imprimirMatricula($aluno,$disciplinas,$matricula);
				} else {
					JFactory::getApplication()->enqueueMessage(JText::_('N&#227;o existe matr&#237;cula realizada para o per&#237;odo ativo!!!'));
				}
			} else {
				JFactory::getApplication()->enqueueMessage(JText::_('N&#227;o existe per&#237;odo ativo!!!'));
			}
		break;
	
		case "oferta":
			$idAluno = JRequest::getCmd('idAluno', false);
			$idPeriodo = JRequest::getCmd('idPeriodo', false);
			
			$aluno = identificarAlunoID($idAluno);
			$curso = $aluno->curso;
			if($aluno->curso == 3) $curso = 1;
			
			$database->setQuery("SELECT periodo FROM #__periodos WHERE id = $idPeriodo");
			$periodos = $database->loadObjectList();
			
			$sql = "SELECT D.id, D.codigo, D.nomeDisciplina , D.creditos , D.carga, PD.professor, PD.horario, PD.turma, PD.curso, PD.sala FROM #__disciplina as D join #__periodo_disc as PD on (D.id = PD.idDisciplina) WHERE PD.idPeriodo = $idPeriodo AND curso = $curso ORDER BY D.codigo";
			$database->setQuery( $sql );
			$disciplinas = $database->loadObjectList();
			
			imprimirOferta($disciplinas, $periodos[0]);	
		break;
   
		case "aproveitamento":
			$idAluno = JRequest::getCmd('idAluno', false);
			$aluno = identificarAlunoID($idAluno);
			$sql = " SELECT * FROM `#__periodos` WHERE status = 1 ";
			$database->setQuery( $sql );
			$periodos = $database->loadObjectList();
			$periodo = $periodos[0];
			
			aproveitarDisciplina($aluno, $periodo->id);
		break;
   
		case "addAproveitamento":
			$idDisciplina = JRequest::getVar('novaDisciplina');
			$idAluno = JRequest::getVar('idAluno');
			$idPeriodo = JRequest::getVar('idPeriodo');
			$aluno = identificarAlunoID($idAluno);

			salvarAproveitamentoDisciplina($idAluno,$idDisciplina, $idPeriodo);								
		break;

		case "removerAproveitamentoDisciplina":
			$idAproveitamento = JRequest::getVar('rmAprov');
			$idAluno = JRequest::getVar('idAluno');
			$idPeriodo = JRequest::getVar('idPeriodo');
			removerAproveitamentoDisciplina($idAluno,$idAproveitamento);		
			$aluno = identificarAlunoID($idAluno);
			
			aproveitarDisciplina($aluno, $idPeriodo);
		break;
   
   		//---------------------------- PRORROGAÃ‡ÃƒO DE PRAZO ----------------------------
		case "prorrogacao":
			$idAluno = JRequest::getVar('idAluno', false);
			$aluno = identificarAlunoID($idAluno);		
	
			telaProrrogacao($aluno);
		break;
	
		case "addProrrogacao":
			$justificativa = $_POST['justificativa'];
			$idAluno = JRequest::getVar('idAluno');
			$aluno = identificarAlunoID($idAluno);
	
			enviarProrrogacao($aluno);
			enviarEmail($aluno, $justificativa);
		break;
		
		case "cadProrrogacao":
			$idAluno = JRequest::getVar('idAluno', false);
			$aluno = identificarAlunoID($idAluno);		
	
			telaCadProrrogacao($aluno);
		break;
		
		case "mostrarDetalhesProrrogacao":
			$idAluno = JRequest::getCmd('idAluno', false);
			$aluno = identificarAlunoID($idAluno);
			
			mostrarDetalhesProrrogacao($aluno);
		break;
		
		//---------------------------- TRANCAMENTO DE PERÃODO ----------------------------
		case "trancamento":
			$idAluno = JRequest::getVar('idAluno', false);
			$aluno = identificarAlunoID($idAluno);		

			telaTrancamento($aluno);
		break;
		
		case "addTrancamento":
			$justificativa = $_POST['justificativa'];
			$idAluno = JRequest::getVar('idAluno');
			$aluno = identificarAlunoID($idAluno);
	
			enviarTrancamento($aluno);
			enviarEmail2($aluno, $justificativa);
		break;
		
		default:
			$alunos = quantidadeAluno($user->id);
			if(sizeof($alunos) > 1){
				servicosPortalAluno($alunos);
			}
			else{
  			   $alunos = identificarAluno($user->id);
			   if($alunos){
				   servicosPortalAlunoItem($alunos[0]);
			   } else {
				   JFactory::getApplication()->enqueueMessage(JText::_('Aluno n&#227;o encontrado ou egresso.'));
			   }
			}
		break;
	}


	// ---------------------------- MENU PRINCIPAL ----------------------------

	function quantidadeAluno($idUser) {
		$database	=& JFactory::getDBO();	
		$sql = "SELECT * FROM #__aluno WHERE idUser = $idUser AND status = 0";
		$database->setQuery( $sql );
		$alunos = $database->loadObjectList();
	
		return ($alunos);
	}
	function matriculaAberta() {
		$database	=& JFactory::getDBO();	
		$sql = "SELECT * FROM `#__periodos` WHERE status = 1 AND matricula = 1";
		$database->setQuery( $sql );
		$periodos = $database->loadObjectList();
	
		if($periodos) return $periodos[0]->periodo;
		return NULL;
	}

	function tratamento($idDisciplina,$myDisciplinas) {
    	foreach($myDisciplinas as $disciplina) {
             if ($disciplina->id == $idDisciplina) {
              return 1;
              }
           }
        return 0;
    }

	function identificarAluno($idUser) {
		$database	=& JFactory::getDBO();	
		$sql = "SELECT * FROM #__aluno WHERE idUser = $idUser AND status = 0";
		$database->setQuery( $sql );
		$alunos = $database->loadObjectList();
	
		return ($alunos);
	}

	function identificarDisciplinaID($idDisciplina) {
		$database	=& JFactory::getDBO();	
		$sql = "SELECT * FROM #__disciplina WHERE id = '$idDisciplina' ";
		$database->setQuery( $sql );
		$alunos = $database->loadObjectList();
	
		return ($disciplina[0]);
	}

	function identificarPeriodoID($idPeriodo) {
		$database	=& JFactory::getDBO();	
		$sql = "SELECT * FROM #__periodos WHERE id = '$idPeriodo' ";
		$database->setQuery( $sql );
		$periodo = $database->loadObjectList();
	
		return ($periodo[0]);
	}

	function identificarTurma($curso, $idPeriodo, $idDisciplina) {
		$database	=& JFactory::getDBO();	
		$sql = "SELECT turma FROM #__periodo_disc  WHERE idPeriodo = $idPeriodo AND idDisciplina = $idDisciplina AND curso = $curso";
		$database->setQuery( $sql );
		$turma = $database->loadObjectList();
	
		return ($turma[0]->turma);
	}

	function getMyDisciplinas($idAluno, $idPeriodo) {
		$database	=& JFactory::getDBO();
		$sql = "SELECT D.id, DM.idPeriodo, DM.turma, D.codigo, D.nomeDisciplina, D.creditos, D.carga, PD.professor,PD.horario
		 FROM #__disc_matricula AS DM
		 JOIN #__disciplina AS D ON DM.idDisciplina = D.id
		 JOIN #__periodo_disc AS PD ON (DM.idDisciplina = PD.idDisciplina AND DM.turma = PD.turma)
		 WHERE DM.idPeriodo = $idPeriodo AND PD.idPeriodo = $idPeriodo AND DM.idAluno = $idAluno ORDER BY D.codigo";
	
		$database->setQuery( $sql );
		$myDisciplinas = $database->loadObjectList();
	
		return ($myDisciplinas);
	}

	function disciplinasCadastradas($periodo, $curso) {
		$database	=& JFactory::getDBO();
		$sql = "SELECT D.id, D.codigo, D.nomeDisciplina , D.creditos , PD.professor, PD.horario, PD.turma, PD.curso, D.carga FROM `#__disciplina` as D join #__periodo_disc as PD on D.id = PD.idDisciplina WHERE PD.idPeriodo = '$periodo' AND PD.curso = $curso ORDER BY D.codigo";
		$database->setQuery( $sql );
		$myDisciplinas = $database->loadObjectList();
	
		return ($myDisciplinas);
	}

	function getTabelaDisciplinas($periodo, $curso) {
		$database	=& JFactory::getDBO();
		$sql = "SELECT D.id, D.codigo, D.nomeDisciplina , D.creditos , PD.professor, PD.horario, PD.turma, PD.curso, D.carga FROM `#__disciplina` as D join #__periodo_disc as PD on D.id = PD.idDisciplina WHERE PD.idPeriodo = '$periodo' AND PD.curso = $curso ORDER BY D.codigo";
		$database->setQuery( $sql );
		$disciplinas= $database->loadObjectList();
	
		return ($disciplinas);
	}

	function getTabelaDisciplinas_p() {
		$database	=& JFactory::getDBO();
		$sql = "SELECT * FROM #__disciplina  ORDER BY codigo";
		$database->setQuery( $sql );
		$disciplinas= $database->loadObjectList();
	
		return ($disciplinas);
	}

	function identificarAlunoID($idAluno ){
		$database	=& JFactory::getDBO();
		$sql = "SELECT * FROM #__aluno WHERE id = $idAluno";
		$database->setQuery( $sql );
		$alunos = $database->loadObjectList();
	
		return ($alunos[0]);
	}

	function identificarCadastro($cpf) {
		$database	=& JFactory::getDBO();
		$sql = "SELECT * FROM #__aluno WHERE cpf = $cpf";
		$database->setQuery( $sql );
		$cadastro = $database->loadObjectList();
		
		if ($cadastro[0]==NULL) {
			return 0;
		}
		
		return 1;
	}

	function identificarPeriodo($periodo) {
		$database	=& JFactory::getDBO();
		$sql = "SELECT * FROM #__periodos WHERE periodo LIKE '%$periodo%' ";
		$database->setQuery( $sql );
		$teste = $database->loadObjectList();
	
		if ($teste[0]==NULL) {
			return 0;
		}
		
		return 1;
	}

	function verEmail($email) {
		$database	=& JFactory::getDBO();
		$sql = "SELECT email FROM #__aluno WHERE email = '$email'";
		$database->setQuery( $sql );
		$database->Query();
		$alunos = $database->loadObjectList();
	
		return ($alunos[0]);
	}

	function salvarMatricula($idAluno,$myDisciplinas) {
		$database	=& JFactory::getDBO();
	
		$semestre = $_POST['periodo'];
		$idPeriodo = $_POST['idPeriodo'];
		$fasePesquisa = $_POST['fasePesquisa'];
		$dataPrevista = $_POST['dataPrevista'];
	
		$sql = " SELECT * FROM #__matricula WHERE idAluno = $idAluno AND idPeriodo=$idPeriodo";
		$database->setQuery( $sql );
		$matriculas = $database->loadObjectList();
	
		if($matriculas == NULL)
			$sql = "INSERT INTO #__matricula (idAluno,dataMatricula,semestre,idPeriodo,fasePesquisa,dataPrevista) VALUES ('$idAluno','".date("Y-m-d H:i:s")."','$semestre', $idPeriodo, '$fasePesquisa', '$dataPrevista' )";
		else
			$sql = "UPDATE #__matricula SET dataMatricula = '".date("Y-m-d H:i:s")."', fasePesquisa = '$fasePesquisa', dataPrevista = '$dataPrevista'";
	
		$database->setQuery( $sql );
		$database->Query();
	
		telafinal($idAluno,$idPeriodo, 1);
	}

	function cancelarMatricula($idAluno) {
		$database	=& JFactory::getDBO();
	
		$semestre = $_POST['periodo'];
		$idPeriodo = $_POST['idPeriodo'];
	
		$sql = " DELETE FROM #__matricula WHERE idAluno = $idAluno AND idPeriodo=$idPeriodo";
		$database->setQuery( $sql );
		$database->Query();
	
		$sql = " DELETE FROM #__disc_matricula WHERE idAluno = $idAluno AND idPeriodo=$idPeriodo";
		$database->setQuery( $sql );
		$database->Query();
	
		telafinal($idAluno,$idPeriodo, 2);
	}

	function salvarDisciplina($idAluno,$idDisciplina, $idPeriodo, $turma) {
		$database	=& JFactory::getDBO();
		$sql = "INSERT INTO #__disc_matricula (idAluno, idDisciplina, idPeriodo, turma) VALUES ($idAluno,$idDisciplina, $idPeriodo, '$turma' )";
		$database->setQuery( $sql );
		$database->Query();
	}

	function removerDisciplina($idAluno,$idDisciplina, $idPeriodo) {
		$database	=& JFactory::getDBO();
		$sql ="DELETE FROM #__disc_matricula WHERE idAluno = $idAluno AND idDisciplina = $idDisciplina AND idPeriodo = $idPeriodo LIMIT 1";
		$database->setQuery( $sql );
		$database->Query();
	}

	function finalizarMatricula($idAluno) {
		$database	=& JFactory::getDBO();
		$sql = "UPDATE #__aluno SET fim='".date("Y-m-d H:i:s")."' WHERE id = $idAluno";
		$database->setQuery( $sql );
		$database->Query();
	}

	function verProfessor($idProfessor) {
		$db = & JFactory::getDBO();
		$db->setQuery("SELECT nomeProfessor FROM #__professores WHERE id = $idProfessor LIMIT 1");
		$professor = $db->loadResult();
	
		return($professor);
	}

	function verLinhaPesquisa($idLinha, $inf) {
		 $db = & JFactory::getDBO();
		 $db->setQuery("SELECT nome, sigla FROM #__linhaspesquisa WHERE id = $idLinha LIMIT 1");
		 $linha = $db->loadObjectList();
	
		 if($inf == 2)
			return($linha[0]->sigla);
	
		 return($linha[0]->nome);
	}
	//-----------------------------------------------------------------------------------------------------

function aproveitarDisciplina($aluno, $idPeriodo) {
    $Itemid = JRequest::getInt('Itemid', 0);
	
    $database =& JFactory::getDBO();
	
	$curso = array (1 => "Mestrado",2 => "Doutorado",3 => "Especial");
	?>
    
    <link rel="stylesheet" type="text/css" href="components/com_portalprofessor/template.css" />
    <link rel="stylesheet" href="components/com_portalsecretaria/estilo.css" type="text/css" />
    
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.js"></script>
    <script type="text/javascript" src="components/com_portalsecretaria/jquery.tablesorter.js"></script>
    
	<script language="JavaScript">
		function ConfirmarExclusao(formAproveitamento, idAproveitamento) {
			var confirmar = confirm('Confirmar Exclus\xE3o?');
		   
			if(confirmar == true) {
				formAproveitamento.task.value='removerAproveitamentoDisciplina';
			 	formAproveitamento.rmAprov.value=idAproveitamento;
			 	formAproveitamento.submit();
		   	}
		}
		
		function Limpar(valor, validos) {
			var result = "";
			var aux;
			
			for (var i=0; i < valor.length; i++) {
				aux = validos.indexOf(valor.substring(i, i+1));
				
				if (aux>=0)
					result += aux;
			}
				
			return result;
		}
				
		function Formata(campo,tammax,teclapres,decimal) {
			var tecla = teclapres.keyCode;
			
			vr = Limpar(campo.value,"0123456789");
			tam = vr.length;
			dec=decimal;
		
			if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }
		
			if (tecla == 8 )
			{ tam = tam - 1 ; }
		
			if ( tecla == 8 || (tecla >= 48 && tecla <= 57) ) {
		
				 if ( tam == 0 )
				 {         campo.value = '0.00' ;         }
				 else if ( tam == 1 )
				 {         campo.value = '0.0'+vr ;         }
				 else if ( tam == 2 )
				 {         campo.value = '0.'+vr ;         }
				 else if ( (tam > dec) ){
					var parte1 = vr.substr( 0, tam - 2 );
					if(parte1 == '00') parte1 = '0';
					if(parte1 == '01') parte1 = '1';
					if(parte1 == '02') parte1 = '2';
					if(parte1 == '03') parte1 = '3';
					if(parte1 == '04') parte1 = '4';
					if(parte1 == '05') parte1 = '5';
					if(parte1 == '06') parte1 = '6';
					if(parte1 == '07') parte1 = '7';
					if(parte1 == '08') parte1 = '8';
					if(parte1 == '98') parte1 = '9';
					campo.value = parte1 + "." + vr.substr( tam - dec, tam ) ; }
		
				 }
		
		}
		
		function handleEnter (field, event) {
			var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
			
			if(keyCode == 8 || keyCode == 9 || (keyCode >= 48 && keyCode <= 57)) return true;
			if (keyCode == 13) {
				var i;
			
				for (i = 0; i < field.form.elements.length; i++)
					if (field == field.form.elements[ i ])
					   break;
					   
					i = (i + 1) % field.form.elements.length;
					field.form.elements[ i ].focus();
					return false;
			} else
				return false;
			}
		
		function validarFreq(form) {
			for (i = 0; i < form.elements.length; i++)
				 if (form.elements[i].type == "text")
				 if (form.elements[i].value > 100){
				   alert(unescape('O campo FREQUENCIA deve ser preenchido com valores menores ou iguais a 100.'));
				   form.elements[i].focus();
				   return false;
		
				 }
			return true;
		}
		
		
		function IsEmpty(aTextField) {
		   if ((aTextField.value.length==0) ||
		   (aTextField.value==null)) {
			  return true;
		   }
		   else { return false; }
		}
		
		function validarPeriodo(periodo) {
		
		   if(periodo.length != 7){
			  return false;
		   }
			  
		   var ano = periodo.substr(0,4);
		   var barra = periodo.substr(4,1);
		   var semestre = periodo.substr(5,2);
		
		   if(barra != '/' || isNaN(semestre) || isNaN(ano))
			  return false;
			  
		   return true;
		}
		
		function isPdf(file){
		
		   extArray = new Array(".pdf");
		   allowSubmit = false;
		
		   if (!file) return;
		   while (file.indexOf("\\") != -1)
		   file = file.slice(file.indexOf("\\") + 1);
		   ext = file.slice(file.indexOf(".")).toLowerCase();
		
		   for (var i = 0; i < extArray.length; i++) {
				 if (extArray[i] == ext) { allowSubmit = true; break; }
		   }
		
		   return(allowSubmit);
		}
		
		
		function ValidarAdicao(formAproveitamento) {
			/* if(formAproveitamento.novaDisciplina.value==0) {
			  alert('Deve ser escolhida uma disciplina.')
			  formAproveitamento.novaDisciplina.focus();
			  return false;
			}
		
			if(IsEmpty(formAproveitamento.periodo) || !validarPeriodo(formAproveitamento.periodo.value)) {
			  alert('O campo periodo deve ser preenchido seguindo o formato indicado.')
			  formAproveitamento.periodo.focus();
			  return false;
			}
			
			if(IsEmpty(formAproveitamento.frequencia)) {
			  alert('O campo frequencia deve ser preenchido.')
			  formAproveitamento.frequencia.focus();
			  return false;
			}
		
			if(formAproveitamento.frequencia.value < 75 || formAproveitamento.frequencia.value > 100) {
			  alert(unescape('O campo FREQUENCIA deve ser preenchido com valores entre 75% e 100%.'));
			  formAproveitamento.frequencia.focus();
			  return false;
			}
		
			if(IsEmpty(formAproveitamento.conceito)) {
			  alert('O campo conceito deve ser preenchido.')
			  formAproveitamento.conceito.focus();
			  return false;
			}*/
			
			if(IsEmpty(formAproveitamento.tipo)) {
			  alert('O campo Tipo de Aproveitamento deve ser preenchido.')
			  formAproveitamento.tipo.focus();
			  return false;
			}
		
		   /*	if(IsEmpty(formAproveitamento.comprovante) || !isPdf(formAproveitamento.comprovante.value)) {
			  alert('Este campo \xE9 obrigat\xF3rio e apenas arquivos com extens\xE3o PDF podem ser carregados.')
			  return false;
		   	}*/
		
			return true;
		}
    </script>
    
    <script type="text/javascript">
		$(function() {
			$("#tablesorter-imasters").tablesorter();
		});
    </script>

    <form method="post" id="formAproveitamento" name="formAproveitamento" enctype="multipart/form-data" action="index.php?option=com_portalaluno&Itemid=<?php echo $Itemid;?>">
    <div id="toolbar-box"><div class="m"><div class="toolbar-list" id="toolbar">
          <div class="cpanel2">
				<div class="icon" id="toolbar-new">
           			<a href="javascript:if(ValidarAdicao(document.formAproveitamento)) document.formAproveitamento.submit()">
                   <!--<a href="javascript:document.formAproveitamento.task.value='addAproveitamento';document.formAproveitamento.submit()">-->
           			<span class="icon-32-new"></span><?php echo JText::_( 'Adicionar' ); ?></a>
				</div>
                
				<div class="icon" id="toolbar-back">
           			<a href="index.php?option=com_portalaluno&idAluno=<?php echo $aluno->id;?>&task=servicos&Itemid=<?php echo $Itemid;?>">
           			<span class="icon-32-back"></span><?php echo JText::_( 'Voltar' ); ?></a>
				</div>
			</div>
        
			<div class="clr"></div>
    
			</div>
			<div class="pagetitle icon-48-cpanel"><h2>Aproveitamento de Disciplina</h2></div>
    </div></div>

	<table width="100%" border="0" cellspacing="2" cellpadding="2">
		<tr style="background-color: #7196d8;">
			<td style="width: 100%;" colspan="8"><font size="2"><b><font color="#FFFFFF">Dados do Aluno</font></b></font></td>
		</tr>
        <tr>
			<td class="cellCommom"><b>Nome:</b></b></td>
			<td><?php echo $aluno->nome;?></td>
			<td class="cellCommom"><b>Curso:</b></td>
			<td><?php echo $curso[$aluno->curso];?></td>
        </tr>
        <tr>
			<td class="cellCommom"><b>Linha de Pesquisa:</b></td>
			<td><?php echo verLinhaPesquisa($aluno->area, 1);?></td>
			<td class="cellCommom"><b>Ano Ingresso:</b></td>
			<td><?php $data = explode("-", $aluno->anoingresso);
                      echo $data[2]."/".$data[1]."/".$data[0]; ?> 
			</td>
        </tr>
	</table>
    <hr>
	<?php
		$resultado = array (1 => "deferido",2 => "indeferido");

		$sql = "SELECT D.id, D.codigo, D.nomeDisciplina, D.creditos, D.carga, A.periodoCursado, A.resultado, DATE_FORMAT(A.dataPedido, '%d/%m/%Y') AS dataPedido, DATE_FORMAT(A.dataJulgamento, '%d/%m/%Y')AS dataJulgamento, A.justificativa, A.conceito, A.frequencia FROM #__disciplina AS D JOIN #__aproveitamentos AS A ON D.id = A.idDisciplina WHERE idAluno = $aluno->id AND resultado <> 0 ORDER BY codigo";
		$database->setQuery($sql);
		$myDisciplinas = $database->loadObjectList();

		if ($myDisciplinas) {
	?>
    
	<br />
    
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
		<tr style="background-color: #7196d8;">
			<td style="width: 100%;" colspan="8"><font size="2"> <b><font color="#FFFFFF">Pedidos de Aproveitamento Julgados</font></b></font></td>
		</tr>
        <tr>
          <td align="center" class="cellCommom">Pedido</td>
          <td align="center" class="cellCommom">Julgamento</td>
          <td align="center" class="cellCommom">Resultado</td>
          <td align="center" class="cellCommom">Justif.</td>
          <td align="center" class="cellCommom">Código</td>
          <td align="center" class="cellCommom">Disciplina</td>
          <td align="center" class="cellCommom">Freq (%)</td>
          <td align="center" class="cellCommom">Conceito</td>
        </tr>
        
    <?php
    	foreach($myDisciplinas as $disciplina) { ?>
            <tr>
              <td align="center"><?php echo $disciplina->dataPedido;?></td>
              <td align="center"><?php echo $disciplina->dataJulgamento;?></td>
              <td align="center"><img src="components/com_portalsecretaria/images/<?php echo $resultado[$disciplina->resultado];?>.gif"></td>
              <td align="center"><?php if($disciplina->resultado == 2){ ?><img src="components/com_portalsecretaria/images/justificativa.gif" title="<?php echo $disciplina->justificativa;?>"><?php } ?></td>
              <td align="center"><?php echo $disciplina->codigo;?></td>
              <td align="center"><?php echo $disciplina->nomeDisciplina;?></td>
              <td align="center"><?php echo number_format($disciplina->frequencia,2);?></td>
              <td align="center"><?php echo $disciplina->conceito;?></td>
            </tr>
	<?php } ?>
	</table>
    <?php } ?>
    
	<br />
    
    <?php
       $sql = "SELECT * FROM #__aproveitamentos WHERE idAluno = $aluno->id AND resultado = 0 ORDER BY codigo";
       $database->setQuery($sql);
       $myDisciplinas = $database->loadObjectList();

    	if($myDisciplinas) { 
			$sql = "SELECT A.idAproveitamento, A.tipoAproveitamento, D.id, D.codigo, D.nomeDisciplina, D.creditos, D.carga, DATE_FORMAT(A.dataPedido, '%d/%m/%Y') AS dataPedido, A.comprovante, A.periodoCursado, A.conceito, A.frequencia FROM #__disciplina AS D JOIN #__aproveitamentos AS A ON D.id = A.idDisciplina WHERE idAluno = $aluno->id AND resultado = 0 ORDER BY codigo";
		   $database->setQuery($sql);
		   $myDisciplinas1 = $database->loadObjectList(); 
		   
		   foreach ($myDisciplinas1 as $d1) ?>

			<table width="100%" border="0" cellspacing="2" cellpadding="2">
				<tr style="background-color: #7196d8;">
					<td style="width: 100%;" colspan="9"><font size="2"> <b><font color="#FFFFFF">Disciplinas Solicitadas e N&#227;o Julgadas</font></b></font></td>
				</tr>
                <tr>
                  <td align="center" class="cellCommom"></td>
                  <td align="center" class="cellCommom">Pedido</td>
                  <td align="center" class="cellCommom">Código</td>
                  <td align="center" class="cellCommom">Disciplina</td>
                  <td align="center" class="cellCommom">Período</td>
                  <td align="center" class="cellCommom">Créditos</td>
                  <td align="center" class="cellCommom">C.H.</td>
                  <td align="center" class="cellCommom">Freq (%)</td>
                  <td align="center" class="cellCommom">Conceito</td>
                </tr>

			<?php
                foreach($myDisciplinas as $disciplina) { 
				
					if (($disciplina->tipoAproveitamento == '1') || ($disciplina->tipoAproveitamento == '3')) { ?>
                    
                    <tr>
                      <td align="center"><a href="javascript:ConfirmarExclusao(document.formAproveitamento, <?php echo $disciplina->idAproveitamento;?>)"> <img src="components/com_portalaluno/images/lixeira.gif"  alt="excluir" title="excluir"/> </a></td>
                      <td align="center"><?php echo $d1->dataPedido; ?></td>
                      <td align="center"><?php echo $d1->codigo; ?></td>
                      <td align="center"><?php echo $d1->nomeDisciplina; ?> <a href="<?php echo $d1->comprovante;?>" target="_blank"><img src="components/com_portalaluno/images/icon_pdf.gif" width="16" height="16" border="0"></a></td>
                      <td align="center"><?php echo $d1->periodoCursado; ?></td>
                      <td align="center"><?php echo $d1->creditos; ?></td>
                      <td align="center"><?php echo $d1->carga; ?></td>
                      <td align="center"><?php echo number_format($d1->frequencia,2); ?></td>
                      <td align="center"><?php echo $d1->conceito; ?></td>
                    </tr>
                    
                    <?php } else { 
					
					   $sql = "SELECT idAproveitamento, tipoAproveitamento, codigo, disciplina, carga_horaria, creditos, DATE_FORMAT(dataPedido, '%d/%m/%Y') AS dataPedido, ementa, periodoCursado, conceito, frequencia FROM #__aproveitamentos WHERE idAluno = $aluno->id AND resultado = 0 ORDER BY codigo";
					   $database->setQuery($sql);
					   $myDisciplinas2 = $database->loadObjectList(); 
					   
					   foreach ($myDisciplinas2 as $d2) ?>
                    
                        <tr>
                          <td align="center"><a href="javascript:ConfirmarExclusao(document.formAproveitamento, <?php echo $disciplina->idAproveitamento;?>)"> <img src="components/com_portalaluno/images/lixeira.gif"  alt="excluir" title="excluir"/> </a></td>
                          <td align="center"><?php echo $d2->dataPedido; ?></td>
                          <td align="center"><?php echo $d2->codigo; ?></td>
                          <td align="center"><?php echo $d2->disciplina; ?> <a href="<?php echo $d2->ementa;?>" target="_blank"><img src="components/com_portalaluno/images/icon_pdf.gif" width="16" height="16" border="0"></a></td>
                          <td align="center"><?php echo $d2->periodoCursado; ?></td>
                          <td align="center"><?php echo $d2->creditos; ?></td>
                          <td align="center"><?php echo $d2->carga_horaria; ?></td>
                          <td align="center"><?php echo $d2->frequencia; ?></td>
                          <td align="center"><?php echo $d2->conceito; ?></td>
                        </tr>
                    
                    <?php } ?>
                    
    		<?php } ?>
  			</table>
		<?php } ?>
        
	<?php
       $sql = "SELECT * FROM #__disciplina ORDER BY nomeDisciplina";
       $database->setQuery($sql);
       $disciplinas = $database->loadObjectList();

       $sql = "SELECT * FROM #__periodos ORDER BY periodo";
       $database->setQuery($sql);
       $periodos = $database->loadObjectList();
	?>

	    <script type="text/javascript" src="components/com_portalaluno/jquery-1.7.2.js"></script>    
	<!-- MASCARA DOS CAMPOS -->
    <script type="text/javascript">
		function mascara(src, mask ) {
			var i = src.value.length;
			var saida = mask.substring(1,2);
			var texto = mask.substring(i)
			if (texto.substring(0,1) != saida) {
				src.value += texto.substring(0,1);
			}
		} 
	</script>
	
    <!-- OPÇÃO QUE MOSTRA OUTRA IES -->
   	<script type="text/javascript">
		$(document).ready(function() {
		   var valor;		 
		   $("#tipo").change(function(){			  
			  valor = $("#tipo option:selected").val();
			  
			  if ((valor == '') || (valor == 1) || (valor == 3)) {
			  	$("#tbOutra").css("display", "none");				
			  } else {
			  	$("#tbOutra").css("display", "block");
			  } 
		   });
		});
	</script> 
    
    <br />

	<b>Como proceder: </b>
    <ul>
	<li>Selecione primeiramente a disciplina do PPGI a ser aproveitada em ems eguida escolha o tipo de aproveitamento.</li>
	<li>Caso a disciplina tenha sido cursada em outra IES (tipos 2, 4, 5 e 6), você deverá preencher os dados da disciplina cursada na outra IES (código, nome, carga horária, créditos e ementa).</li>
	</ul>
    
    <table width="100%" border="0" cellspacing="2" cellpadding="2" id="tbAproveitamento">
        <tr style="background-color: #7196d8;">
        	<td style="width: 100%;" colspan="3"><font size="2"> <b><font color="#FFFFFF">Incluir Nova Disciplina</font></b></font></td>
        </tr>
        <tr>
            <td width="18%" bgcolor="#CCCCCC"><strong>Disciplina do PPGI:</strong></td>
            <td width="82%">
                <select name="novaDisciplina" id="novaDisciplina">
                <option value="0">Escolher Disciplina</option>
                <?php for($i=0; $i < count($disciplinas); $i++) { ?>
	                <option value="<?php echo $disciplinas[$i]->id;?>"><?php echo $disciplinas[$i]->codigo." - ".$disciplinas[$i]->nomeDisciplina;?></option>
                <?php } ?>
                </select>
			</td>
        </tr>
        <tr>
            <td width="18%" bgcolor="#CCCCCC"><strong>Tipo de Aproveitamento:</strong></td>
            <td width="82%">
            <select name="tipo" id="tipo" class="inputbox">
            <option value=""></option>
            <option value="1">1 - Cursada neste Curso de P&#243;s-Gradua&#231;&#227;o como aluno regular ou aluno especial</option>
            <option value="2">2 - Cursada em outros Cursos de P&#243;s-Gradua&#231;&#227;o como aluno especial ou regular</option>
            <option value="3">3 - Ministrada no IComp/UFAM por pelo menos um semestre para aproveitamento em Est&#225;gio em Doc&#234;ncia</option>
            <option value="4">4 - Professores de outras IES reconhecidas que tenham exercido suas atividades na &#225;rea de Inform&#225;tica</option>
            <option value="5">5 - Aluno que tenha ministrado aulas em cursos de Inform&#225;tica ou Computa&#231;&#227;o em outras IES reconhecidas</option>
            <option value="6">6 - Cursada em curso de Gradua&#231;&#227;o com equival&#234;ncia &#224;s disciplinas do Mestrado</option>
            </select>
            </td>
        </tr>
	</table>
        
    <!-- TABELA PARA DISCIPLINA DE OUTRA IES -->
	<table width="100%" cellpadding="2" cellspacing="2" id="tbOutra" style="display:none; background-color:#E4E4E4; border:1px solid #999; border-radius:5px;">
        <tr>
            <td class="cellCommom" colspan="2" align="center"><b>Dados da Disciplina na outra IES<b></td>
        </tr>
		<tr>
            <td width="21%" class="cellCommom">Código da Disciplina:</td>
            <td width="79%"><input type="text" id="codigo" name="codigo2" /></td>
        </tr>
        <tr>
            <td width="21%" class="cellCommom">Nome Disciplina:</td>
            <td width="79%"><input type="text" id="disciplina" name="disciplina2" size="100" /></td>
        </tr>
        <tr>
            <td width="21%" class="cellCommom">Carga Horária:</td>
            <td width="79%"><input type="text" id="carga" name="carga2" maxlength="3" onkeypress="mascara(this, '###')" /></td>
        </tr>
        <tr>
            <td width="21%" class="cellCommom">Créditos:</td>
            <td width="79%"><input type="text" id="creditos" name="creditos2" maxlength="2" /></td>
        </tr>        
        <tr>
            <td width="21%" class="cellCommom">Ementa:</td>
            <td width="79%" colspan="2"><input type="file" name="ementa" size="60"></td>
        </tr>
    </table>
    
	<table width="100%">
        <tr>
            <td bgcolor="#CCCCCC"><strong>Período - Ano/Sem:</strong></td>
            <td><input type="text" id="periodo" name="periodo" size="6" maxlength="7" onkeypress="mascara(this, '####/##')" /></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><strong>Frequência xx.xx%:</strong></td>
            <td><input type="text" id="frequencia" name="frequencia" size="6" maxlength="5" onkeypress="mascara(this, '##.##')"/></td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC"><strong>Conceito:</strong></td>
            <td><select name="conceito" id="conceito" class="inputbox">
            <option value=""></option>
            <option value="A">A (&#8805; 9,0: Excelente)</option>
            <option value="B">B (&#8805; 8,0 e < 9,0: &Oacute;timo)</option>
            <option value="C">C (&#8805; 7,0 e < 8,0: Bom)</option>
            </select>
            </td>
        </tr>   
        <tr>
            <td bgcolor="#CCCCCC"><strong>Comprovante<br>(hist&#243;rico em PDF):</strong></td>
            <td colspan="2"><input type="file" name="comprovante" size="60"></td>
        </tr>
    </table>

        <input name='idAluno' type='hidden' value='<?php echo $aluno->id;?>'>
        <input name='idPeriodo' type='hidden' value='<?php echo $idPeriodo;?>'>
        <input name='task' type='hidden' value='addAproveitamento'>
        <input name='rmAprov' type='hidden' value=''>
	</form>
    
<?php } ?>


<?php // FUNÇÕES

function salvarAproveitamentoDisciplina($idAluno, $idDisciplina, $idPeriodo) {
	$database =& JFactory::getDBO();
	
	$aluno = identificarAlunoID($idAluno);
	$tipo = JRequest::getVar('tipo');
	$periodo = JRequest::getVar('periodo');
	$frequencia = JRequest::getVar('frequencia');
	$conceito = JRequest::getVar('conceito');
	
	if (($tipo == '1') || ($tipo == '3')) {
		
		$comprovante = "";
		
		if($_FILES["comprovante"]["tmp_name"]){
			$comprovante = "components/com_portalsecretaria/aproveitamentos/PPGI-Aproveitamento-$idAluno-$idDisciplina.pdf";
			move_uploaded_file($_FILES["comprovante"]["tmp_name"],$comprovante);
		}
	
		$sql = "INSERT INTO #__aproveitamentos (idAluno, idDisciplina, idPeriodo, periodoCursado, frequencia, conceito, comprovante, dataPedido, tipoAproveitamento) VALUES ($idAluno, $idDisciplina, $idPeriodo, '$periodo', '$frequencia', '$conceito' ,'$comprovante','". date("Y/m/d H:m:s")."', $tipo)";
		$database->setQuery($sql);
		$funcionou = $database->Query();
		
	} else {
		
		$comprovante = "";
		
		if($_FILES["comprovante"]["tmp_name"]){
			$comprovante = "components/com_portalsecretaria/aproveitamentos/PPGI-Aproveitamento-$idAluno-$idDisciplina.pdf";
			move_uploaded_file($_FILES["comprovante"]["tmp_name"],$comprovante);
		}
		
		$codigo = JRequest::getVar('codigo2');
		$disciplina = JRequest::getVar('disciplina2');
		$carga_horaria = JRequest::getVar('carga2');
		$creditos = JRequest::getVar('creditos2');
		$ementa = "";
		
		if($_FILES["ementa"]["tmp_name"]){
			$ementa = "components/com_portalsecretaria/aproveitamentos/PPGI-Aproveitamento-$idAluno.pdf";
			move_uploaded_file($_FILES["ementa"]["tmp_name"],$ementa);
		}
	
		$sql = "INSERT INTO #__aproveitamentos (idAluno, idDisciplina, idPeriodo, dataPedido, periodoCursado, frequencia, conceito, tipoAproveitamento, codigo, disciplina, carga_horaria, creditos, ementa, comprovante) VALUES ($idAluno, $idDisciplina, $idPeriodo, '". date("Y/m/d H:m:s")."', '$periodo', '$frequencia', '$conceito', '$tipo', '$codigo', '$disciplina', '$carga_horaria', '$creditos', '$ementa', '$comprovante')";
		$database->setQuery($sql);
		$funcionou = $database->Query();
				
	}
	
	if ($funcionou) {
		aproveitarDisciplina($aluno, $idPeriodo);
		return 1;
	} else {
		JError::raiseWarning( 100, 'Cadastro não realizado!' );
		return 0;
	}
}


// REMOVER APROVEITAMENTO DE DISCIPLINA
function removerAproveitamentoDisciplina($idAluno,$idAproveitamento){
    $database	=& JFactory::getDBO();
    $sql ="DELETE FROM #__aproveitamentos WHERE idAproveitamento = $idAproveitamento";
    $database->setQuery( $sql );
    $database->Query();
}

// IS ALUNO
function isAluno($idUser){
    $database	=& JFactory::getDBO();
    $sql ="SELECT group_id FROM #__user_usergroup_map WHERE user_id = $idUser AND group_id = 10";
    $database->setQuery( $sql );
    $resultado = $database->loadObjectList();
	
    return $resultado;
}

?>


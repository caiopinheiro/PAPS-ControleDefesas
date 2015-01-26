<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * HelloWorld Model
*/
class DefesasOrientadorModelCadastrarBanca extends JModelItem
{
	 

	public function getAluno($idAluno) {

		$database =& JFactory::getDBO();

		$sql = "select matricula, a.nome nome, pesq.nome linhapesquisa, curso, anoingresso from #__aluno a, #__linhaspesquisa pesq where a.id = $idAluno and pesq.id = a.area";

		$database->setQuery($sql);

		$aluno = $database->loadObjectList();


		return $aluno[0];

	}

	/**
	 * fun��o que retorna a �ltima fase cadastrada (aprovada ou sem conceito)
	 *
	 * @return multitype:string boolean NULL
	 */

	public function getFaseDefesa($idAluno) {
	
		$database =& JFactory::getDBO();
	
		$fase = array('', false);
	
		$mapaFases = array(
				1 => array ('P', 'Q1', 'D'),
				2 => array ('P', 'Q1', 'Q2', 'T')
		);
	
		$cursos = array(1 => 'D', 2 => 'T');
	
		$sql = "select curso, nome, id, conceitoExameProf from #__aluno where id = $idAluno";
	
		$database->setQuery($sql);
	
		$aluno = $database->loadObjectList();
	
		$sql = "select tipoDefesa, conceito, idDefesa, banca_id, status_banca from #__defesa d left join #__banca_controledefesas b on d.banca_id = b.id where aluno_id = $idAluno";
	
		$database->setQuery($sql);
	
		$defesas = $database->loadObjectList();
	
		$fase[0] = 'P';
	
		/**
		 * verifica exame de proefici�ncia
		 */
	
	
		if ((!is_null($aluno[0]->conceitoExameProf) || (!strlen($aluno[0]->conceitoExameProf))))
		{
			$fase[1] = true;
		} else {
			$fase[1] = false;
		}
	
		$countFase = 0;
		$achou = 1;
	
		/**
		 *
		 */
		while ($fase[0] != $mapaFases[$aluno[0]->curso][count($mapaFases[$aluno[0]->curso]) - 1] && $achou && ($fase[1]))
		{
	
			$countFase++;
			$achou = 0;
			$defesaEncontrada = '';
				
			foreach ($defesas as $defesa) {
		
				if (!strcmp($defesa->tipoDefesa,$mapaFases[$aluno[0]->curso][$countFase]) && (!($defesa->status_banca === 0))) {
					$achou = 1;
					$defesaEncontrada = $defesa;
				}
			}
	
			if ($achou)
			{
	
				$fase[0] = $defesaEncontrada->tipoDefesa;
	
				if ((is_null($defesaEncontrada->conceito) || (!strlen($defesaEncontrada->conceito))))
					$fase[1] = false;
				else $fase[1] = true;
			}
		}
	
		return $fase;
	}
	
	
	public function getMapaFases() {

		$mapaFases = array(
				1 => array ('P', 'Q1', 'D'),
				2 => array ('P', 'Q1', 'Q2', 'T')
		);

		$cursos = array(1 => 'D', 2 => 'T');

		$database =& JFactory::getDBO();
		$idAluno = JRequest::getVar("idaluno");

		$sql = "select curso, nome, id, conceitoExameProf from #__aluno where id = $idAluno";

		$database->setQuery($sql);

		$aluno = $database->loadObjectList();

		return $mapaFases[$aluno[0]->curso];

	}

	
	/**
	 * funcao que valida a data
	 * - data v�lida 
	 * - diferen�a para data corrente de no m�nimo 30 dias
	 * 
	 * @return boolean
	 */
		
	private function validaData($date) {
		
		$valido = true;
		
		if (!strlen($date)) {
			$valido = false;
		} else { 
			
			$data = explode("/",$date);
			$d = $data[0];
			$m = $data[1];
			$y = $data[2];
			$valido = checkdate($m,$d,$y);
		}	
		
		return $valido;
	}
	
	
	/**
	 * 
	 * funcao que retorna se o arquivo � v�lido
	 * - extens�o PDF
	 * - tamanho m�ximo (10MB)
	 * 
	 * @param file $arquivo
	 * @return boolean
	 */
	
	private function validaArquivo($arquivo) {
			
		$validacao = array();

		$validacao['semArquivo'] = false;
		$validacao['tamanho'] = false;
		$validacao['formato'] = false;

		if (is_null($arquivo) || $arquivo['size'] == 0) {
			
			$validacao['semArquivo'] = true;
			
		} else {

			$extensao = strtolower(end(explode('.', $arquivo['name'])));
			$validacao['formato'] = !(!strcmp($extensao, 'pdf'));
			
			//tamanho m�ximo 10mb
			
			$maximo = 10 * 1024 * 1024;
			
			$validacao['tamanho'] = ($arquivo['size'] > $maximo);
		}
		
		return $validacao;
		
	}

	/**
	 * 
	 * Funcao que retorna o resultado da valida��o
	 * 
	 * - array que o resultado de cada um dos crit�rios da valida��o
	 * 
	 * @param unknown $defesa
	 * @return multitype:boolean
	 */
	private function validaDefesa($defesa) {
		
		$valido = true;
		$mensagens = array();
		
		$mensagens['titulo'] = (!strlen($defesa['titulo']) || is_null($defesa['titulo']));
		$mensagens['resumo'] = (!strlen($defesa['resumo']) || is_null($defesa['resumo']));
	 	$mensagens['dataInvalida'] = !$this->validaData($defesa['data']);
	 	
	 	
	 	$validacaoArquivo = $this->validaArquivo($defesa['previa']);

	 	$mensagens['semArquivo'] = $validacaoArquivo['semArquivo'];
	 	$mensagens['arquivoTamanho'] = $validacaoArquivo['tamanho'];
	 	$mensagens['arquivoFormato'] = $validacaoArquivo['formato'];
		
	 	if (!$mensagens['dataInvalida']) {
	 	
	 	$dateNow = date_create();
	 	$dateDefesa = DateTime::createFromFormat('d/m/Y', $defesa['data']);
	 	$diff = date_diff($dateNow, $dateDefesa);
	 	// solicita��o de banca deve ser feita com no minimo 30 dias de anteced�ncia
	 	$mensagens['dataAnterior'] = !(($diff->days >= 30) && (!$diff->invert));
	 	 
	 	} else $mensagens['dataAnterior'] = false;
	 	
	 	if (count($defesa['membrosBanca']['id'])) {
	 	
	 		$countMembroExterno = 0;
	 		$countMembroInterno = 0;
	 		
		 	for($count = 0; $count < count($defesa['membrosBanca']['id']); $count++) {
		 		
		 		if ($defesa['membrosBanca']['tipoMembro'][$count] == 'I') {
		 			$countMembroInterno++;
		 		}

		 		if ($defesa['membrosBanca']['tipoMembro'][$count] == 'E') {
		 			$countMembroExterno++;
		 		}
		 		
		 	}
		 	
		 	$mensagens['semMembros'] = false;
		 	
		 	// nega duas vezes para se transformar em booleano
		 	
		 	$mensagens['semMembrosInternos'] = !$countMembroInterno;
		 	$mensagens['semMembrosExternos'] = !$countMembroExterno;
			
	 	} else  {
	 		$mensagens['semMembros'] = true;
	 		$mensagens['semMembrosExternos'] = false;
	 		$mensagens['semMembrosInternos'] = false;
	 	}
	 	
	 	
	 	// validar local
	 	
	 	if ($defesa['tipoLocal'] == 'E') {
	 		
	 		$mensagens['local'] = (!strlen($defesa['localDescricao']) || is_null($defesa['localDescricao']));
	 		$mensagens['horario'] = !$this->validaHora($defesa['localHorario']);
	 		$mensagens['sala'] = (!strlen($defesa['localSala']) || is_null($defesa['localSala']));
	 		
	 	} else {
	 		$mensagens['local'] = false;
	 		$mensagens['horario'] = false;
	 		$mensagens['sala'] = false;
	 	}
	 	
	 	
	 	$aluno = $this->getAluno($defesa['aluno']);
	 	$fase = $this->getFaseDefesa($defesa['aluno']);
	 	
	 	//segundo a regra de neg�cio, qualifica��o 1 de doutorado n�o tem membros de banca
	 	if ($aluno->curso == 2 && !strcmp($fase[0], 'Q1')) {
	 		$mensagens['semMembros'] = false;
	 		$mensagens['semMembrosExternos'] = false;
	 		$mensagens['semMembrosInternos'] = false;
	 	}
	 	
	 	if (!$mensagens['semArquivo'] && !$mensagens['arquivoTamanho'] && !$mensagens['arquivoFormato'])
	 		$this->moverArquivoTemp($defesa['previa']);
			 	
	 	return $mensagens;
	 	
	}
	
	
	/**
	 * move arquivo para pasta tempor�ria do sistema
	 * 
	 * @param unknown $arquivo
	 */
	private function moverArquivoTemp($arquivo) {
		
		$target_dir = "tmp/";
		$target_file = $target_dir . basename($arquivo["name"]);
		
		if (move_uploaded_file($arquivo["tmp_name"], $target_file)) {	
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * mover arquivo para destino final
	 * 
	 * @param unknown $arquivoPrevia
	 * @return boolean
	 */
	
	
	private function moverArquivo($arquivo, $tipoDefesa) {
	
		
		$oldLocation = 'tmp/';
		
		$oldName = $oldLocation . basename($arquivo['name']);
		
		$newLocation = 'components/com_defesasorientador/previas/';
		
		$newName = $tipoDefesa . $arquivo['name'];
		
		$newName = md5($newName);
			
		$moveu = rename($oldName, $newLocation . $newName . '.pdf');

		if ($moveu) {
			return $newName . '.pdf';
		} else return false;
	
	}
	
	
	private function validaHora($hora){
	
		$t=explode(":",$hora);
		if ($t=="")
			return false;
		$h=$t[0];
		
		
		if (!isset($t[1])) return false;
		$m=$t[1];
		
		if (!is_numeric($h) || !is_numeric($m))
			return false;
			
		if ($h<0 || $h>24)
			return false;
		if ($m<0 || $m>59)
			return false;
			
		return true;
	}
	
	/**
	 * 
	 *	Fun��o que executa no banco de dados a inser��o
	 * 
	 * @param unknown $defesa
	 * @return unknown|Ambigous <multitype:boolean, multitype:boolean >
	 */
	public function insertDefesa($defesa) {
	
		
		
		$validacao = $this->validaDefesa($defesa);
		
		// usado para opera��o l�gica com array de booleanos
		$resultadoValidacao = false;

		/**
		 * se existir uma mensagem true, o resultado � true e � confirmado que a valida��o n�o foi aceita
		 */	
		
		foreach ($validacao as $msg) {
			$resultadoValidacao = $resultadoValidacao || $msg;
		}
		
		$resultadoValidacao = !$resultadoValidacao;
		
		if ($resultadoValidacao) {
				
			$database =& JFactory::getDBO();

			if (!strcmp($defesa['tipoDefesa'], 'Q1') || !strcmp($defesa['tipoDefesa'], 'Q2')) {
				$statusBanca = 1;
			} else $statusBanca = 'null';
			
			$sql = "insert into #__banca_controledefesas (justificativa, status_banca) values ('', " . $statusBanca . ")" ;
			
			var_dump($sql);
			$database->setQuery($sql);
				
			$database->execute();
				
			$idBanca = $database->insertid();
			
			
			$arquivoPrevia = $this->moverArquivo($defesa['previa'], $defesa['tipoDefesa']);
			$this->inserirMembroBanca($defesa['membrosBanca'], $idBanca);
				
			$sql = "insert into #__defesa (aluno_id, titulo, resumo, tipoDefesa, data, banca_id, previa) values (" . $defesa['aluno'].  ", '" . $defesa["titulo"] . "', '" . $defesa["resumo"] . "', " .
					"'" . $defesa['tipoDefesa'] . "', " . "str_to_date('" . $defesa['data'] .  "', '%d/%m/%Y'), $idBanca, '" .  $arquivoPrevia . "')";
				
			$database->setQuery($sql);
				
			var_dump($sql);
			
			$database->execute();
			
			$result = $database->insertid(); 					
			
			return $result;
			
		} else return $validacao;

	}
	
	/**
	 * 
	 * Move o arquivo da pasta tmp, para a pasta de arquivos de pr�via
	 * 
	 * @param unknown $arquivoPrevia
	 * @return boolean
	 */
	
	/**
	 * 
	 * Fun��o que retorna um �nico membro de banca
	 * 
	 * @param unknown $id
	 * @return Ambigous <unknown, mixed>
	 */
	
	public function getMembroBanca($id) {

		$database =& JFactory::getDBO();
		
		$sql = 'select nome, filiacao, id from #__membrosbanca where id=' . $id;
		
		$database->setQuery($sql);
		
		$membro = $database->loadObjectList();

		return $membro[0];
	}
	
	/**
	 * fun��o utilizada para inserir um membro de banca 
	 * 
	 * @param unknown $membroBanca
	 * @param unknown $idBanca
	 */
	private function inserirMembroBanca($membroBanca, $idBanca) {
		
		$database =& JFactory::getDBO();
		
		for ($count = 0; $count < count($membroBanca['id']); $count++) {

			$sql = "insert into #__banca_has_membrosbanca (banca_id, membrosbanca_id, funcao, passagem) " .
					"values ($idBanca, " . $membroBanca['id'][$count] . ", '" . $membroBanca['tipoMembro'][$count] . "', '" . $membroBanca['passagem'][$count] . "')";
			
			var_dump($sql);
			
			$database->setQuery($sql);
			
			$database->execute();

			// decidir o que fazer com isso
			$result = $database->insertid();
			
		}
		
	}


}

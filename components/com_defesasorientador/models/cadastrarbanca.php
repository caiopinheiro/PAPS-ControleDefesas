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
	 * função que retorna a última fase cadastrada (aprovada ou sem conceito)
	 *
	 * @return multitype:string boolean NULL
	 */

	private function getFaseDefesa($idAluno) {
		
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
		
		$sql = "select tipoDefesa, conceito, idDefesa from #__defesa where aluno_id = $idAluno";

		$database->setQuery($sql);
		
		$defesas = $database->loadObjectList();
		
		$fase[0] = 'P';
		
		/**
		 * verifica exame de proeficiência
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
				
				if (!strcmp($defesa->tipoDefesa,$mapaFases[$aluno[0]->curso][$countFase])) {
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
	 * - data válida 
	 * - diferença para data corrente de no mínimo 30 dias
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
	 * funcao que retorna se o arquivo é válido
	 * - extensão PDF
	 * - tamanho máximo (10MB)
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
			
			//tamanho máximo 10mb
			
			$maximo = 10 * 1024 * 1024;
			
			$validacao['tamanho'] = ($arquivo['size'] > $maximo);
		}
		
		return $validacao;
		
	}

	/**
	 * 
	 * Funcao que retorna o resultado da validação
	 * 
	 * - array que o resultado de cada um dos critérios da validação
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
	 	$dateDefesa = DateTime::createFromFormat('d/m/Y', $defesa['data']);
	 	$dateNow = date_create();
	 	
	 	$validacaoArquivo = $this->validaArquivo($defesa['previa']);

	 	$mensagens['semArquivo'] = $validacaoArquivo['semArquivo'];
	 	$mensagens['arquivoTamanho'] = $validacaoArquivo['tamanho'];
	 	$mensagens['arquivoFormato'] = $validacaoArquivo['formato'];
		 	
	 	$diff = date_diff($dateNow, $dateDefesa);

	 	// solicitação de banca deve ser feita com no minimo 30 dias de antecedência
	 	$mensagens['dataAnterior'] = !(($diff->days >= 30) && (!$diff->invert));
	 	if ($mensagens['dataInvalida']) $mensagens['dataAnterior'] = false;
	 	
	 	if (count($defesa['membrosBanca']['id'])) {
	 	
	 		$countMembroExterno = 0;
	 		$countMembroInterno = 0;
	 		
		 	for($count = 0; $count < count($defesa['membrosBanca']['id']); $count++) {
		 		
		 		if ($defesa['membrosBanca']['tipoMembro'][$count] == 'I') {
		 			$countMembroExterno++;
		 		}

		 		if ($defesa['membrosBanca']['tipoMembro'][$count] == 'E') {
		 			$countMembroInterno++;
		 		}
		 		
		 	}
		 	
		 	$mensagens['semMembros'] = false;
		 	
		 	// nega duas vezes para se transformar em booleano
		 	
		 	$mensagens['semMembrosInternos'] = !(!($countMembroInterno));
		 	$mensagens['semMembrosExternos'] = !(!($countMembroExterno));
			
	 	} else  {
	 		$mensagens['semMembros'] = true;
	 		$mensagens['semMembrosExternos'] = false;
	 		$mensagens['semMembrosInternos'] = false;
	 	}
	 	
	 	
	 	// validar local
	 	
	 	if ($defesa['tipoLocal'] == 'E') {
	 		
	 		$mensagens['semLocal'] = (!strlen($defesa['localDescricao']) || is_null($defesa['localDescricao']));
	 		$mensagens['horarioInvalido'] = !$this->validaHora($defesa['localHorario']);
	 		$mensagens['semSala'] = (!strlen($defesa['localSala']) || is_null($defesa['localSala']));
	 		
	 	} else {
	 		$mensagens['semLocal'] = false;
	 		$mensagens['horarioInvalido'] = false;
	 		$mensagens['semSala'] = false;
	 	}
	 	
	 	
	 	$aluno = $this->getAluno($defesa['aluno']);
	 	$fase = $this->getFaseDefesa($defesa['aluno']);
	 	
	 	//segundo a regra de negócio, qualificação 1 de doutorado não tem membros de banca
	 	if ($aluno->curso == 2 && !strcmp($fase, 'Q1')) {
	 		$mensagens['semMembros'] = false;
	 		$mensagens['semMembrosExternos'] = false;
	 		$mensagens['semMembrosInternos'] = false;
	 	}
	 	
	 	var_dump($mensagens);
	 	return $mensagens;
	 	
	}
	
	private function validaHora($hora){
	
		$t=explode(":",$hora);
		if ($t=="")
			return false;
		$h=$t[0];
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
	 *	Função que executa no banco de dados a inserção
	 * 
	 * @param unknown $defesa
	 * @return unknown|Ambigous <multitype:boolean, multitype:boolean >
	 */
	public function insertDefesa($defesa) {
	
		
		
		$validacao = $this->validaDefesa($defesa);
		
		var_dump($validacao);
		
		//exit(0);
		// usado para operação lógica com array de booleanos
		$resultadoValidacao = false;

		/**
		 * se existir uma mensagem true, o resultado é true e é confirmado que a validação não foi aceita
		 */	
		
		foreach ($validacao as $msg) {
			$resultadoValidacao = $resultadoValidacao || $msg;
		}
		
		$resultadoValidacao = !$resultadoValidacao;
		
		if ($resultadoValidacao) {
				
			$database =& JFactory::getDBO();

			$sql = "insert into #__banca_controledefesas (descricao) values ('Banca do aluno: " . $nomeAluno . "')" ;
				
			$database->setQuery($sql);
				
			$database->execute();
				
			$idBanca = $database->insertid();
			
			$this->inserirMembroBanca($defesa['membrosBanca'], $idBanca);
				
			$sql = "insert into #__defesa (aluno_id, titulo, resumo, tipoDefesa, data, banca_id) values (" . $defesa['aluno'].  ", '" . $defesa["titulo"] . "', '" . $defesa["resumo"] . "', " .
					"'" . $defesa['tipoDefesa'] . "', " . "str_to_date('" . $defesa['data'] .  "', '%d/%m/%Y'), $idBanca)";
				
			$database->setQuery($sql);
				
			//var_dump($sql);
			
		//	$database->execute();
			
			//$result = $database->insertid(); 					
			
			$this->moverArquivo($arquivoPrevia);
			
			return $result;
			
		} else return $validacao;

	}
	
	/**
	 * 
	 * Move o arquivo da pasta tmp, para a pasta de arquivos de prévia
	 * 
	 * @param unknown $arquivoPrevia
	 * @return boolean
	 */
	
	private function moverArquivo($arquivoPrevia) {
		
		$moveu = true;
		
		return $moveu;
		
	}
	
	/**
	 * 
	 * Função que retorna um único membro de banca
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
	 * função utilizada para inserir um membro de banca 
	 * 
	 * @param unknown $membroBanca
	 * @param unknown $idBanca
	 */
	private function inserirMembroBanca($membroBanca, $idBanca) {
		
		$database =& JFactory::getDBO();
		
		for ($count = 0; $count < count($membroBanca['id']); $count++) {

			$sql = "insert into #__banca_has_membrosbanca (banca_id, membrosbanca_id, funcao) " .
					"values ($idBanca, " . $membroBanca['id'][$count] . ", '" . $membroBanca['tipoMembro'][$count] . "')";
			
			var_dump($sql);
			
			$database->setQuery($sql);
			
			$database->execute();

			// decidir o que fazer com isso
			$result = $database->insertid();
			
		}
		
	}


}

<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * HelloWorld Model
*/
class DefesasOrientadorModelSolicitarBanca extends JModelItem
{
	 

	public function getAluno() {

		$database =& JFactory::getDBO();

		$idAluno = JRequest::getVar("idaluno");

		$sql = "select matricula, a.nome nome, pesq.nome linhapesquisa, curso, anoingresso from #__aluno a, #__linhaspesquisa pesq where a.id = $idAluno and pesq.id = a.area";

		$database->setQuery($sql);

		$aluno = $database->loadObjectList();


		return $aluno;

	}

	/**
	 * função que retorna a última fase cadastrada (aprovada ou sem conceito)
	 *
	 * @return multitype:string boolean NULL
	 */

	public function getFaseDefesa() {

		$database =& JFactory::getDBO();
		$idAluno = JRequest::getVar("idaluno");

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

		//var_dump($aluno);

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

		while ($fase[0] != $mapaFases[$aluno[0]->curso][count($mapaFases[$aluno[0]->curso]) - 1] && $achou && ($fase[1]))
		{

			$countFase++;
			$achou = 0;
			$defesaEncontrada = '';
				
			foreach ($defesas as $defesa) {

				if ($defesa->tipoDefesa == $mapaFases[$aluno[0]->curso][$countFase]) {
					$achou = 1;
					$defesaEncontrada = $defesa;
				}
			}

			if ($achou)
			{
				$fase[0] = $defesaEncontrada->tipoDefesa;

				if ((!is_null($defesaEncontrada->conceito) || (!strlen($defesaEncontrada->conceito))))
					$fase[1] = true;
				else $fase[1] = false;
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
	
	private function validaData($date) {
		
		$data = explode("/",$date);
		$d = $data[0];
		$m = $data[1];
		$y = $data[2];
		
		return checkdate($m,$d,$y);
	}
	
	private function validaArquivo($arquivo) {
		
		
		
	}

	
	/**
	 * Função de que valida a defesa 
	 * 
	 * @param unknown $defesa
	 */
	public function validaDefesa($defesa) {
		
		$valido = true;
		$mensagens = array();
							
		$mensagens['titulo'] = (!strlen($defesa['titulo']) || is_null($defesa['titulo']));
		$mensagens['resumo'] = (!strlen($defesa['resumo']) || is_null($defesa['resumo']));
	 	$mensagens['data'] = !$this->validaData($defesa['data']);
	 	
	 	$achouPresidente = false;
	 	for($count = 0; i < count($defesa['membrosBanca']['id']); $count++) {
			if ($defesa['membrosBanca']['tipoMembro'][$count] == 'P'){
				
				$achouPresidente = true;
				
			}		 		
	 	}
	 	
	 	$mensagens['presidente'] = !$achouPresidente;
	 	
	 	$mensagens['previa'] = $this->validaArquivo($defesa['previa']);
		
	 	/**
	 	 * verificar se professor informa o local
	 	 */
	 	
	 	return $mensagens;
	 	
	}

	public function insertDefesa($defesa) {

		$validacao = $this->validaDefesa($defesa);
		
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
				
			$sql = "insert into #__defesa (aluno_id, titulo, resumo, tipoDefesa, data, previa) values ($idAluno, '" . $defesa["titulo"] . "', '" . $defesa["resumo"] . "', " .
					"'" . $defesa['tipoDefesa'] . "', " . "str_to_date('" . $defesa['data'] .  "', '%d/m%/Y'), '" . $defesa["previa"] . "')" ;
				
			$database->setQuery($sql);
				
			$database->execute();

			return  $database->insertid();
			
		} else return $validacao;

	}

	private function inserirMembroBanca($membroBanca, $idBanca) {
		
		$database =& JFactory::getDBO();
		
		for ($count = 0; $count < count($membroBanca['id']); $count++) {

			$sql = "insert into #__banca_has_membrosbanca (banca_id, membrosbanca_id, funcao, situacao) " .
					"values ($idBanca, " . $membroBanca['id'][$count] . ", '" . $membroBanca['tipoMembro'][$count] . "', 0)";
			
			
			$database->setQuery($sql);
			

			// decidir o que fazer com isso
			$result = $database->insertid();
			
		}
		
	}


}

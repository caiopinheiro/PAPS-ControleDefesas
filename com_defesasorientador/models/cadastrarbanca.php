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
	 * Função de que valida os dados do formulário defesa 
	 * 
	 * - true indica que falhou na validação, ou seja deve ser exibido a mensagem
	 * 
	 * @param unknown $defesa
	 */
	private function validaDefesa($defesa) {
		
		// TODO validar defesa repetida
		
		$valido = true;
		$mensagens = array();
		
		$mensagens['titulo'] = (!strlen($defesa['titulo']) || is_null($defesa['titulo']));
		$mensagens['resumo'] = (!strlen($defesa['resumo']) || is_null($defesa['resumo']));
	 	$mensagens['dataInvalida'] = !$this->validaData($defesa['data']);
	 	$dateDefesa = date_create_from_format('d/m/Y', $defesa['data']);
	 	$dateNow = date_create();

	 	
	 	$diff = date_diff($dateNow, $dateDefesa);

	 	// ver regra de negócio - sendo rejeitado apenas datas anteriores a data de hoje
	 	$mensagens['dataAnterior'] = ($diff->invert);  
	 	
	 	$achouPresidente = true;
	 	
	 	if (count($defesa['membrosBanca']['id'])) {
	 	
	 	$achouPresidente = false;
	 	
		 	for($count = 0; $count < count($defesa['membrosBanca']['id']); $count++) {
				if ($defesa['membrosBanca']['tipoMembro'][$count] == 'P'){
					
					$achouPresidente = true;
					
				}		 		
		 	}
		 	$mensagens['membrobanca'] = false;
	 	} else $mensagens['membrobanca'] = true;
	 	
	 	$mensagens['presidente'] = !$achouPresidente;
	 	
	 	//$mensagens['previa'] = $this->validaArquivo($defesa['previa']);
			 	
	 	/**
	 	 * verificar se professor informa o local
	 	 */
	 	
	 	return $mensagens;
	 	
	}

	public function insertDefesa($defesa) {
	
		var_dump($defesa);
		
		
		$validacao = $this->validaDefesa($defesa);
		
		var_dump($validacao);
		
		
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
				
			var_dump($sql);
			
			$database->execute();
			
			$result = $database->insertid(); 					

			return $result;
			
		} else return $validacao;

	}

	public function getMembroBanca($id) {

		$database =& JFactory::getDBO();
		
		$sql = 'select nome, filiacao, id from #__membrosbanca where id=' . $id;
		
		$database->setQuery($sql);
		
		$membro = $database->loadObjectList();

		return $membro[0];
	}
	
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

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
		
		$sql = "select matricula, a.id id,  a.nome nome, pesq.nome linhapesquisa, curso, anoingresso from #__aluno a, #__linhaspesquisa pesq where a.id = $idAluno and pesq.id = a.area";	
		
		$database->setQuery($sql);

		$aluno = $database->loadObjectList();
		
		return $aluno;
		
	}
	
	
	/**
	 * Funcao que retorna um único membro da banca
	 * 
	 * @param unknown $id
	 * @return Ambigous <mixed, NULL, multitype:unknown mixed >
	 */
	
	public function getMembroBanca($id) {

		$database =& JFactory::getDBO();
		
		$sql = "select id, nome, filiacao from #__membrosbanca where id = $id";
		
		$database->setQuery($sql);
		
		$membrosbanca = $database->loadObjectList();
		
		return $membrosbanca[0];
		
	}
	
	private function getBancasTipoDefesa ($idAluno, $faseDefesa) {
		
		$database =& JFactory::getDBO();
		
		$sql = "select d.conceito conceito_defesa, b.status_banca, b.id idbanca, d.tipoDefesa from j17_aluno a join 
		(j17_defesa d LEFT join j17_banca_controledefesas b on d.banca_id = b.id) on 
        a.id = d.aluno_id where a.id = $idAluno and d.tipoDefesa = '$faseDefesa'
		";

		$database->setQuery($sql);
		
		$defesas = $database->loadObjectList();
		
		return $defesas;
		
	}
	
	/**
	 * 
	 * Verifica se existe banca aprovada pelo coordenador. 
	 * 
	 * 1 - se não existe banca aprovada
	 * 2 - se existe banca aprovada
	 * 3 - se não existe banca
	 * 
	 * @param unknown $idAluno
	 * @param unknown $tipoDefesa
	 */
	public function getBancaAlunoAprovada($idAluno, $tipoDefesa) {
		
		$sql = "select b.status_banca from #__defesa d, #__banca_controledefesas b, #__aluno a 
				where a.id = $idAluno and
		 		d.aluno_id = a.id and tipoDefesa = '$tipoDefesa' and 
				d.banca_id = b.id";
		
		$database->setQuery($sql);
		
		$banca = $database->loadObjectList();

		if (count($banca)) {
			$aprovada = 3;
			return $aprovada;
		}
		
		$aprovada = 1;
		
		for ($i = 0; $i < count($banca); $i++) {
			if ($banca[$i]->status_banca == 1 || is_null($banca[$i])) { 
				$aprovada = 2;
			}
		}
	}
	
	/**
	 * funcao que retorna os membros internos, a saber, cuja filiação for 'PPGI/UFAM'
	 * 
	 * @return array
	 */
	
	public function getMembrosInternos() {
	
		$database =& JFactory::getDBO();
	
		$sql = "select id, nome, filiacao from #__membrosbanca where filiacao = 'PPGI/UFAM'";
	
		$database->setQuery($sql);
	
		$membrosbanca = $database->loadObjectList();
	
		return $membrosbanca;
	
	}
	
	
	/**
	 * Função que retorna todos os membros externos para montagem de select
	 * 
	 * @return array
	 * 
	 */
	

	public function getMembrosExternos() {
	
		$database =& JFactory::getDBO();
	
		$sql = "select id, nome, filiacao from #__membrosbanca where filiacao <> 'PPGI/UFAM'";
	
		$database->setQuery($sql);
	
		$membrosbanca = $database->loadObjectList();
	
		return $membrosbanca;
	
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
	
		$sql = "select tipoDefesa, conceito, idDefesa, banca_id, status_banca from #__defesa d left join #__banca_controledefesas b on d.banca_id = b.id where aluno_id = $idAluno";
	
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
		
				if (!strcmp($defesa->tipoDefesa,$mapaFases[$aluno[0]->curso][$countFase]) && (!($defesa->status_banca === '0'))) {
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
	
	
	/**
	 *
	 *	Busca um orientador de um aluno específico
	 * 
	 * @return Ambigous <mixed, NULL, multitype:unknown mixed >
	 */
	
	
	public function getOrientador() {
		
		$database =& JFactory::getDBO();
		$idAluno = JRequest::getVar("idaluno");
		
		$sql = "select , p.nomeProfessor orientador, 'PPGI/UFAM' filiacao from j17_aluno a, j17_professores p
where a.orientador = p.id and a.id = $idAluno";
		
		$database->setQuery($sql);
		
		$orientador = $database->loadObjectList();
		
	
		return $orientador;
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
	
}

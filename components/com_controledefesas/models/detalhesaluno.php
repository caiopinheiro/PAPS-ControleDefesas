<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class ControledefesasModelDetalhesAluno extends JModelItem
{
	public function getAluno($idAluno){
		$database =& JFactory::getDBO();
		$sql = "SELECT a.*, lp.nome as nome_area, lp.sigla as sigla_area FROM #__aluno AS a LEFT JOIN #__linhaspesquisa AS lp ON lp.id = a.area WHERE a.id = '$idAluno' ";
		$database->setQuery($sql);
		return $database->loadObjectList();
	}
	public function getDefesas($idAluno){
		$database =& JFactory::getDBO();
		$sql = "SELECT d.* FROM #__defesa AS d WHERE d.aluno_id = '$idAluno' ";
		$database->setQuery($sql);
		return $database->loadObjectList();
	}
	public function getMembrosBanca($idAluno){
		$database =& JFactory::getDBO();
		
		// setar orientador como Presidente da banca
	/*	$sqlOrientador = "select concat('Prof.(a) ', p.nomeProfessor) nome, 'P' funcao , 'PPGI/UFAM' filiacao, p.email, '' tipoDefesa, d.banca_id 
		from j17_professores p, j17_banca_controledefesas b, j17_defesa d, j17_aluno a where
		d.banca_id = b.id and d.aluno_id = a.id and p.id = a.orientador and a.id = $idAluno";
	*/
		$sql = "SELECT mb.nome, bhmb.funcao, mb.filiacao, mb.email, d.tipoDefesa, d.banca_id FROM #__banca_controledefesas AS b 
		JOIN #__defesa AS d ON d.banca_id = b.id 
		JOIN #__banca_has_membrosbanca AS bhmb ON bhmb.banca_id = b.id 
		JOIN #__membrosbanca AS mb ON mb.id = bhmb.membrosbanca_id 
		WHERE d.aluno_id = '$idAluno'";
		
	//	WHERE d.aluno_id = '$idAluno' AND b.status_banca = '1'";
	//	$sql = $sqlOrientador . ' UNION ' . $sql;
		$database->setQuery($sql);
		return $database->loadObjectList();
	}
}

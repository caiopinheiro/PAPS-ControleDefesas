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
		$sql = "SELECT a.*, lp.nome as nome_area, lp.sigla as sigla_area 
		FROM #__aluno AS a LEFT JOIN #__linhaspesquisa AS lp ON lp.id = a.area WHERE a.id = '$idAluno' ";
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
		
		$sql = "SELECT mb.nome, bhmb.funcao, mb.filiacao, mb.email, d.tipoDefesa, d.banca_id 
		FROM #__defesa AS d 
		JOIN #__banca_controledefesas AS b ON b.id = d.banca_id 
		JOIN #__banca_has_membrosbanca AS bhmb ON bhmb.banca_id = b.id 
		JOIN #__membrosbanca AS mb ON mb.id = bhmb.membrosbanca_id 
		WHERE d.aluno_id = '$idAluno' AND b.status_banca = '1'";
		
		$database->setQuery($sql);
		return $database->loadObjectList();
	}
}

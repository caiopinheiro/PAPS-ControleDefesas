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
		
	//	var_dump($sql);
		
		$database->setQuery($sql);

		$aluno = $database->loadObjectList();
		
		//var_dump($aluno);		
		
		return $aluno;
		
	}

	public function getMembrosBanca() {
		
		$database =& JFactory::getDBO();

		$sql = "select id, nome, filiacao from #__membrosbanca";
		
		$database->setQuery($sql);
		
		$membrosbanca = $database->loadObjectList();
		
		return $membrosbanca;
		
	}
	
	public function getFaseDefesa($aluno) {
		
		$database =& JFactory::getDBO();

		
		
	}
	
	
	
	
}

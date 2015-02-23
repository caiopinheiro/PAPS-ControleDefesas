<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class ControledefesasModelDeclaracao extends JModelItem
{             
	    

	public function visualizarNomeMembro($idMembro){
			
		$database =& JFactory::getDBO();
		$sql = "SELECT nome FROM #__membrosbanca WHERE id =".$idMembro;
		$database->setQuery($sql);
		return $database->loadObjectList();

	}

		public function visualizarDefesa($idDefesa){
			
		$database =& JFactory::getDBO();
		$sql = "SELECT a.nome, titulo,tipoDefesa,data,horario,local,curso, p.nomeProfessor FROM (#__defesa as d JOIN #__aluno as a on a.id = d.aluno_id) JOIN #__professores AS p ON a.orientador = p.id WHERE idDefesa = ".$idDefesa;
		$database->setQuery($sql);
		return $database->loadObjectList();

	}
      
}

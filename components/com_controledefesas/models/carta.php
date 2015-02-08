<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class ControledefesasModelCarta extends JModelItem
{             
	public function visualizarNomeMembro($idMembro){
			
		$database =& JFactory::getDBO();
		$sql = "SELECT nome FROM #__membrosbanca WHERE id =".$idMembro;
		$database->setQuery($sql);
		return $database->loadObjectList();

	}

		public function visualizarDefesa($idDefesa){
			
		$database =& JFactory::getDBO();
		$sql = "SELECT a.nome, titulo,tipoDefesa,data,horario,local,curso FROM #__defesa as d JOIN #__aluno as a on a.id = d.aluno_id WHERE idDefesa = ".$idDefesa;
		$database->setQuery($sql);
		return $database->loadObjectList();

	}

}

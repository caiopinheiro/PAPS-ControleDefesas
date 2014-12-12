<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class DefesasCoordenadorModelAvaliarBanca extends JModelItem
{             
	    
	    public function visualizarBanca($idBanca) {
			$database =& JFactory::getDBO();
			$sql = "SELECT status_banca FROM #__banca_controledefesas WHERE id = ".$idBanca;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}

		public function visualizarAluno($idAluno){
			$database =& JFactory::getDBO();
			$sql = "SELECT nome as nome_aluno,area,anoingresso FROM #__aluno WHERE id= ".$idAluno;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
		
		public function visualizarDefesa($idDefesa){
			$database =& JFactory::getDBO();
			$sql = "SELECT titulo, resumo, tipoDefesa FROM #__defesa WHERE idDefesa = ".$idDefesa;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
		
		public function visualizarMembrosBanca($idBanca){
			$database =& JFactory::getDBO();
			$sql = "SELECT mb.nome, bhmb.funcao, mb.filiacao FROM  #__banca_has_membrosbanca AS bhmb JOIN #__membrosbanca AS mb ON mb.id = bhmb.membrosbanca_id WHERE bhmb.banca_id = ".$idBanca;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
		
		public function updateStatusBanca($idBanca,$avaliacao){
			$database =& JFactory::getDBO();
			$sql = "UPDATE #__banca_controledefesas SET status_banca = ".$avaliacao." WHERE id = ".$idBanca;
			
			$database->setQuery($sql);
			
			$sucesso = $database->Query();
			
			return $sucesso;
				
		}

        
}

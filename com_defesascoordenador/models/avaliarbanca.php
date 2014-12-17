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
			$sql = "SELECT status_banca, justificativa FROM #__banca_controledefesas WHERE id = ".$idBanca;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}

		public function visualizarAluno($idBanca){
			$database =& JFactory::getDBO();
			$sql = "SELECT a.nome as nome_aluno, a.area, a.anoingresso FROM (#__banca_controledefesas AS bcd JOIN #__defesa AS d ON bcd.id = d.banca_id) JOIN #__aluno AS a ON d.aluno_id = a.id WHERE bcd.id= ".$idBanca;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
		
		public function visualizarDefesa($idBanca){
			$database =& JFactory::getDBO();
			$sql = "SELECT titulo, resumo, tipoDefesa FROM (#__banca_controledefesas AS bcd JOIN #__defesa AS d ON bcd.id = d.banca_id)  WHERE bcd.id= ".$idBanca;
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
		
		public function updateJustificativaBanca($idBanca,$justificativa){
			$database =& JFactory::getDBO();
			$sql = "UPDATE #__banca_controledefesas SET justificativa = ".$justificativa." WHERE id = ".$idBanca;
			
			$database->setQuery($sql);
			
			$sucesso = $database->Query();
			
			return $sucesso;
				
		}
        
}

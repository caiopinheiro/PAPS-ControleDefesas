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
			$sql = "SELECT a.nome as nome_aluno, a.area, a.anoingresso, a.curso FROM (#__banca_controledefesas AS bcd JOIN #__defesa AS d ON bcd.id = d.banca_id) JOIN #__aluno AS a ON d.aluno_id = a.id WHERE bcd.id= ".$idBanca;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
			
		public function visualizarDefesa($idBanca){
			$database =& JFactory::getDBO();
			$sql = "SELECT titulo, resumo, tipoDefesa, data, horario, local, previa FROM (#__banca_controledefesas AS bcd JOIN #__defesa AS d ON bcd.id = d.banca_id)  WHERE bcd.id= ".$idBanca;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
		
		public function visualizarMembrosBanca($idBanca){
			$database =& JFactory::getDBO();

		/*	$sql1 = "(select concat('Prof. ', p.nomeProfessor) nome, 'P' funcao, 'PPGI/UFAM' filiacao, p.email, 'N' passagem
			from ((j17_professores p join j17_aluno a on a.orientador = p.id) join j17_defesa d on d.aluno_id = a.id) join j17_banca_controledefesas b on b.id = d.banca_id
			where b.id = $idBanca )";
		*/	
			
			$sql = "(SELECT mb.nome, bhmb.funcao, mb.filiacao, mb.email, bhmb.passagem FROM  #__banca_has_membrosbanca AS bhmb JOIN #__membrosbanca AS mb ON mb.id = bhmb.membrosbanca_id WHERE bhmb.banca_id = ".$idBanca . ')';
			
		//	$sql = $sql1 . 'UNION ' . $sql;
			
			
	//		var_dump(%sql)
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
		
		public function passagemMembrosBanca($idBanca){
			$database =& JFactory::getDBO();			
			
			$sql = "(SELECT mb.nome, bhmb.funcao, mb.filiacao, mb.email, bhmb.passagem FROM  #__banca_has_membrosbanca AS bhmb JOIN #__membrosbanca AS mb ON mb.id = bhmb.membrosbanca_id WHERE bhmb.banca_id = ".$idBanca . ')';			
			
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
		
		public function updateStatusBanca($idBanca,$avaliacao){
			$db =& JFactory::getDbo();
			 
			$query = $db->getQuery(true);
			 
			// Fields to update.
			$fields = array(
				$db->quoteName('status_banca') . ' = ' . $db->quote($db->escape($avaliacao))
			);
			 
			// Conditions for which records should be updated.
			$conditions = array(
				$db->quoteName('id') . ' = '.$idBanca 
			);
			 
			$query->update($db->quoteName('#__banca_controledefesas'))->set($fields)->where($conditions);
			 
			$db->setQuery($query);
			 
			$result = $db->execute();


			
			/*
			$database =& JFactory::getDBO();
			$sql = "UPDATE #__banca_controledefesas SET status_banca = ".$avaliacao." WHERE id = ".$idBanca;
			
			$database->setQuery($sql);
			
			$sucesso = $database->Query();
			*/
			
			//return $sucesso;
			return $result;
				
		}
		
		public function setJustificativa($idBanca,$justificativa){
			$db =& JFactory::getDBO();
			
			$query = $db->getQuery(true);
			 
			// Fields to update.
			$fields = array(
				$db->quoteName('justificativa') . ' = ' . $db->quote($db->escape($justificativa))
			);
			 
			// Conditions for which records should be updated.
			$conditions = array(
				$db->quoteName('id') . ' = '.$idBanca 
			);
			 
			$query->update($db->quoteName('#__banca_controledefesas'))->set($fields)->where($conditions);
			 
			$db->setQuery($query);
			 
			$result = $db->execute();
			
			
			/*$sql = "UPDATE #__banca_controledefesas SET justificativa = '".$justificativa."' WHERE id = ".$idBanca;
			
			$database->setQuery($sql);
			
			$sucesso = $database->Query();
			
			return $sucesso;
			*/	
			return $result;
		}
        
}

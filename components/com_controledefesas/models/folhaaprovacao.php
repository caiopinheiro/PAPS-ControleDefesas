<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class ControledefesasModelFolhaaprovacao extends JModelItem
{             
	    
	    public function visualizarBanca($idBanca) {
			$database =& JFactory::getDBO();
			$sql = "SELECT status_banca FROM #__banca_controledefesas WHERE id = ".$idBanca;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}

		public function visualizarAluno($idAluno){
			$database =& JFactory::getDBO();
			$sql = "SELECT nomeProfessor, a.curso as curso, a.orientador , a.id as id_aluno, a.nome as nome_aluno, a.area, a.anoingresso, d.conceito as conceito 
					FROM ( #__defesa AS d JOIN #__aluno AS a ON d.aluno_id = a.id) JOIN #__professores as prof ON prof.id = a.orientador 
					WHERE aluno_id= ".$idAluno;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
		
		public function visualizarDefesa($idDefesa){
			$database =& JFactory::getDBO();
			$sql = "SELECT conceito, status_banca, titulo, d.data as data, banca_id, resumo, tipoDefesa FROM (#__defesa AS d LEFT JOIN #__banca_controledefesas AS bcd ON bcd.id = d.banca_id)  WHERE idDefesa= ".$idDefesa;
			$database->setQuery($sql); // TIREI O RIGHT DO JOIN
			return $database->loadObjectList();
		}
/*		
		public function visualizarMembrosBanca($idBanca){
			$database =& JFactory::getDBO();
			$sql = "SELECT mb.nome, bhmb.funcao, mb.filiacao, mb.email FROM  #__banca_has_membrosbanca AS bhmb 
							JOIN #__membrosbanca AS mb ON mb.id = bhmb.membrosbanca_id WHERE bhmb.banca_id = ".$idBanca;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
*/


		public function visualizarMembrosBanca($idDefesa){
			$database =& JFactory::getDBO();
			$sql = "SELECT mb.nome, bhmb.funcao, mb.filiacao, mb.email FROM #__defesa as d JOIN #__banca_has_membrosbanca AS bhmb ON d.banca_id = bhmb.banca_id
					JOIN #__membrosbanca AS mb ON mb.id = bhmb.membrosbanca_id 
							WHERE d.idDefesa = ".$idDefesa;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}

		public function updateConceito($idAluno,$idDefesa,$escolha){
			$database =& JFactory::getDBO();
			$sql = "UPDATE #__defesa SET conceito='".$escolha."' WHERE aluno_id=".$idAluno." AND idDefesa = ".$idDefesa;
			$database->setQuery($sql);
		
			$sucesso = $database->Query();
			return $sucesso;
		}


		public function folhaaprovacao($idDefesa){
			$database =& JFactory::getDBO();
			$sql = "SELECT  tipoDefesa, curso , a.nome as nome, a.area, a.orientador, nomeProfessor, d.titulo, d.data, d.local, d.horario
			 		FROM #__defesa as d JOIN #__aluno as a ON a.id = d.aluno_id JOIN #__professores as p ON p.id = a.orientador
			 		WHERE d.idDefesa = ".$idDefesa;
			$database->setQuery($sql);
			return $database->loadObjectList();

		}

		
		
        
}

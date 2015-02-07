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
			$database->setQuery($sql); 
			return $database->loadObjectList();
		}

		public function visualizarMembrosBanca($idDefesa){
			$database =& JFactory::getDBO();
			$sql = "SELECT mb.nome, mb.id, bhmb.funcao, mb.filiacao FROM #__defesa as d JOIN #__banca_has_membrosbanca AS bhmb ON d.banca_id = bhmb.banca_id
					JOIN #__membrosbanca AS mb ON mb.id = bhmb.membrosbanca_id 
							WHERE d.idDefesa = ".$idDefesa;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}

 
}

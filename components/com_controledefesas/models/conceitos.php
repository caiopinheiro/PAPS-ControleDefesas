<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class ControledefesasModelConceitos extends JModelItem
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
			$sql = "SELECT  d.previa as previa, horario, local, d.numDefesa, d.conceito, idDefesa, status_banca, titulo, d.data as data, banca_id, resumo, tipoDefesa, d.examinador, d.emailExaminador FROM (#__defesa AS d LEFT JOIN #__banca_controledefesas AS bcd ON bcd.id = d.banca_id)  WHERE idDefesa= ".$idDefesa;
			$database->setQuery($sql); 
			return $database->loadObjectList();
		}

/*		public function visualizarMembrosBanca($idDefesa){
			$database =& JFactory::getDBO();
			$sql = "SELECT mb.nome, mb.id, bhmb.funcao, mb.filiacao FROM #__defesa as d JOIN #__banca_has_membrosbanca AS bhmb ON d.banca_id = bhmb.banca_id
					JOIN #__membrosbanca AS mb ON mb.id = bhmb.membrosbanca_id 
							WHERE d.idDefesa = ".$idDefesa;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
*/
		public function visualizarMembrosBanca($idDefesa){
			$database =& JFactory::getDBO();

			$sql1 = "(select concat('Prof. ', p.nomeProfessor) nome, 'P' funcao, 'PPGI/UFAM' filiacao, p.email, p.id, 'N' passagem
			from ((#__professores p join #__aluno a on a.orientador = p.id) join #__defesa d on d.aluno_id = a.id) join #__banca_controledefesas b on b.id = d.banca_id
			where d.idDefesa = $idDefesa )";
			
			
			$sql = "(SELECT mb.nome, bhmb.funcao, mb.filiacao, mb.email, mb.id, bhmb.passagem FROM  (#__banca_has_membrosbanca AS bhmb JOIN #__membrosbanca AS mb ON mb.id = bhmb.membrosbanca_id) JOIN #__defesa AS d ON bhmb.banca_id = d.banca_id WHERE d.idDefesa = ".$idDefesa.")";
			
			$sql = $sql1 . 'UNION ' . $sql;
			
			
	//		var_dump(%sql)
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
		
		public function passagemMembrosBanca($idDefesa){
			$database =& JFactory::getDBO();			
			
			$sql = "(SELECT mb.nome, bhmb.funcao, mb.filiacao, mb.email, bhmb.passagem FROM  #__banca_has_membrosbanca AS bhmb JOIN #__membrosbanca AS mb ON mb.id = bhmb.membrosbanca_id WHERE bhmb.banca_id = ".$idDefesa . ')';			
			
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
		
		public function checkNumDefesa($numDefesa,$tipoDefesa){
			$database =& JFactory::getDBO();
			$sql = "SELECT numDefesa FROM #__defesa WHERE numDefesa =".$numDefesa;
			
			$complemento = "";
				
			if($tipoDefesa == 'D'){
				$complemento = " AND tipoDefesa LIKE 'D'";
			}
			else if($tipoDefesa == 'T'){
				$complemento = " AND tipoDefesa LIKE 'T'";
			}
			
			$sql = $sql.$complemento;
			
			
			$database->setQuery($sql);
						
			$result = $database->loadObjectList();
			
			return sizeof($result);
		}
		
		//atualização do numero de defesa
		public function updateNumDefesa($idDefesa,$numDefesa){
			$database =& JFactory::getDBO();
			$sql = "UPDATE #__defesa SET numDefesa='".$numDefesa."' WHERE idDefesa = ".$idDefesa;
			$database->setQuery($sql);
			
			$sucesso = $database->Query();
			return $sucesso;
		}

}

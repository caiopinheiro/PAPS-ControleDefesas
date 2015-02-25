<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class ControledefesasModelGerarata extends JModelItem
{             
	    

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

		public function visualizarMembrosBanca($idDefesa){
			$database =& JFactory::getDBO();

	/*		$sql1 = "(select concat('Prof. ', p.nomeProfessor) nome, 'P' funcao, 'PPGI/UFAM' filiacao, p.email, p.id
	//		from ((#__professores p join #__aluno a on a.orientador = p.id) join #__defesa d on d.aluno_id = a.id) join #__banca_controledefesas b on b.id = d.banca_id
			where d.idDefesa = $idDefesa )";
	*/		
			
			$sql = "(SELECT mb.nome, bhmb.funcao, mb.filiacao, mb.email, mb.id FROM  (#__banca_has_membrosbanca AS bhmb JOIN #__membrosbanca AS mb ON mb.id = bhmb.membrosbanca_id) JOIN #__defesa AS d ON bhmb.banca_id = d.banca_id WHERE d.idDefesa = ".$idDefesa. ')';
			
	//		$sql = $sql1 . 'UNION ' . $sql;
			
			
	//		var_dump(%sql)
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
		
		///Consultas para gerar ata
		public function visualizarAlunoAta($idDefesa){
			$database =& JFactory::getDBO();
			$sql = "SELECT a.nome as nome_aluno, a.curso FROM #__defesa AS d JOIN #__aluno AS a ON d.aluno_id = a.id WHERE d.idDefesa= ".$idDefesa;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
			
		public function visualizarDefesaAta($idDefesa){
			$database =& JFactory::getDBO();
			$sql = "SELECT titulo, resumo, tipoDefesa, data, horario, local, numDefesa FROM j17_defesa  WHERE idDefesa= ".$idDefesa;
			$database->setQuery($sql);
			return $database->loadObjectList();
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

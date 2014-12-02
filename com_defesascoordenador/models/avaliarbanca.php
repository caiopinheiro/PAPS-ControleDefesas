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
			
			
		//	echo '<p>'.$idBanca.' Estou dentro da model</p>';
			$database =& JFactory::getDBO();
			
			//Consulta por nome orientador e status da banca
			$sql1 =  ("SELECT status_banca, nome FROM #__banca_controledefesas AS CD JOIN #__banca_has_membrosbanca AS MB ON CD.id = MB.banca_id JOIN #__membrosbanca AS M ON MB.membrosbanca_id = M.id WHERE MB.funcao LIKE 'presidente' and CD.id = ".$idBanca);		

			//Consulta por status_banca
//			SELECT sql1.id, sql1.status_banca, sql1.nome from (SELECT CD.id, status_banca, nome FROM j17_banca_controledefesas AS CD JOIN j17_banca_has_membrosbanca AS MB ON CD.id = MB.banca_id JOIN j17_membrosbanca AS M ON MB.membrosbanca_id = M.id WHERE MB.funcao LIKE 'presidente') as sql1 where sql1.status_banca = 1


			$database->setQuery($sql1);
			
		//	echo '<p>'.var_dump($database->loadObjectList()).'</p>';
						
			return $database->loadObjectList();
		}

		public function vizualizarAluno($idBanca){
			$database =& JFactory::getDBO();
		//id, nome e linha de pesquisa do aluno e id da banca desse aluno
			$sql1 = "SELECT a.id, a.nome,a.area d.banca_id from j17_defesa as d join j17_aluno as a where a.id = d.aluno_id and d.banca_id = ".$idBanca;
			$database->setQuery($sql1);
			return $database->loadObjectList();
		}

//id banca, titulo e resumo da defesa
//(select b.id, d.titulo, d.resumo from j17_banca_controledefesas as b join j_17defesa as d where b.id = d.banca_id) as sql3

        
}

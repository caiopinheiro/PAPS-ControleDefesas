<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class ControledefesasModelListaBancas extends JModelItem
{
        /**
         * @var string msg
         */
        protected $msg;        
 
        /**
         * Get the message
         * @return string The message to be displayed to the user
         */
        public function getMsg() 
        {
                if (!isset($this->msg)) 
                {
                        $this->msg = 'This message has been brought to you by the hello world model getMsg function.';
                }
                return $this->msg;
        }
        
      	    
	    public function filtroBanca($status_banca, $nome_aluno, $tipo_curso, $nome_orientador, $tipo_banca, $linha_pesquisa) {
			$database =& JFactory::getDBO();
			$sql_standard = "SELECT  distinct d.idDefesa, d.numDefesa, d.data as data , a.curso as curso, prof.nomeProfessor, a.orientador, prof.id ,bcd.id as idBanca, a.id as idAluno, bcd.status_banca, a.nome as nome_aluno, 
					d.tipoDefesa as tipo_banca, a.area as linha_pesquisa, d.conceito as conceito
					FROM ((((( j17_defesa as d JOIN  j17_aluno as a  ON d.aluno_id = a.id) LEFT JOIN j17_banca_controledefesas as bcd 
					ON d.banca_id = bcd.id) LEFT JOIN j17_banca_has_membrosbanca AS MB ON bcd.id = MB.banca_id) LEFT JOIN j17_membrosbanca 
					AS M ON MB.membrosbanca_id = M.id) JOIN #__professores as prof ON prof.id = a.orientador)
					WHERE 1=1";
					//	)";
					//AND (a.status = 0 OR a.status = 1 OR a.status = 5)
					//essa ultima condicao referente ao a.status é para eliminar os alunos jubilados, desligados e desistente

//			 AND (d.tipoDefesa LIKE 'T' OR d.tipoDefesa LIKE 'D')
			
			$sql_status_banca = '';
			$sql_nome_aluno = '';	
			$sql_nome_orientador = '';
			$sql_tipo_banca = '';
			$sql_linha_pesquisa = '';
			$sql_tipo_curso = ''; // falta fazer o tipo CURSO !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			
				
			
			
			if($nome_aluno != '')
				$sql_nome_aluno = " AND a.nome LIKE '%".$nome_aluno.'%\'';		
			
			if($nome_orientador != '')
				$sql_nome_orientador = " AND prof.nomeProfessor LIKE '%".$nome_orientador.'%\'';

			if($tipo_curso == 1)
				$sql_tipo_curso = " AND curso = 1";
			else if ($tipo_curso == 2)
				$sql_tipo_curso = " AND curso = 2";
			else if ($tipo_curso == 3)
				$sql_tipo_curso = " AND curso = 3";
	


			if($status_banca == 2){					
				$sql_status_banca = " AND (bcd.status_banca = '1' OR bcd.status_banca is NULL) AND (conceito = '' OR conceito is NULL)";
				//' AND bcd.status_banca IS NULL';
			}
			else if($status_banca == 1 ){
				$sql_status_banca = " AND ( conceito LIKE 'Aprovado' ) ";
			}
			else if($status_banca == 4 ){
				$sql_status_banca = " AND (bcd.status_banca = '0') ";
			}
			else if($status_banca == 5){
				$sql_status_banca = " AND ( conceito LIKE 'Reprovado' )";
			}
			else if($status_banca == 6){
				$sql_status_banca = " AND (d.banca_id <> '0' AND bcd.status_banca is NULL and (conceito = ''))";//pendente deferimento coordenador
			}
			else if($status_banca == 7){
				$sql_status_banca = " AND (d.banca_id = '0' AND tipoDefesa <> 'Q1')"; //pendente de banca
			}




				if($tipo_banca == 0){
					$sql_tipo_banca = " AND d.tipoDefesa LIKE 'D'";
				}
				else if($tipo_banca ==1){
					$sql_tipo_banca = " AND d.tipoDefesa LIKE 'T'";
				}
				else if ($tipo_banca == 2){
					$sql_tipo_banca = " AND d.tipoDefesa LIKE 'Q1'";
				}
				else if ($tipo_banca == 3){
					$sql_tipo_banca = " AND d.tipoDefesa LIKE 'Q2'";
				}
	




			if($linha_pesquisa > 0)
				$sql_linha_pesquisa = ' AND a.area = '.$linha_pesquisa;
							
			$sql = $sql_standard.$sql_status_banca.$sql_nome_aluno.$sql_nome_orientador.$sql_tipo_curso.$sql_tipo_banca.$sql_linha_pesquisa;
		
			$database->setQuery($sql);		
			return $database->loadObjectList();
	    
		}
		
		///Consultas para gerar ata
		public function visualizarAluno($idDefesa){
			$database =& JFactory::getDBO();
			$sql = "SELECT a.nome as nome_aluno, a.curso FROM #__defesa AS d JOIN #__aluno AS a ON d.aluno_id = a.id WHERE d.idDefesa= ".$idDefesa;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
			
		public function visualizarDefesa($idDefesa){
			$database =& JFactory::getDBO();
			$sql = "SELECT titulo, resumo, tipoDefesa, data, horario, local, numDefesa FROM j17_defesa  WHERE idDefesa= ".$idDefesa;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
		
		public function visualizarMembrosBanca($idDefesa){
			$database =& JFactory::getDBO();

			$sql1 = "(select concat('Prof. ', p.nomeProfessor) nome, 'P' funcao, 'PPGI/UFAM' filiacao, p.email
			from ((#__professores p join #__aluno a on a.orientador = p.id) join #__defesa d on d.aluno_id = a.id) join #__banca_controledefesas b on b.id = d.banca_id
			where d.idDefesa = $idDefesa )";
			
			
			$sql = "(SELECT mb.nome, bhmb.funcao, mb.filiacao, mb.email FROM  (#__banca_has_membrosbanca AS bhmb JOIN #__membrosbanca AS mb ON mb.id = bhmb.membrosbanca_id) JOIN #__defesa AS d ON bhmb.banca_id = d.banca_id WHERE d.idDefesa = ".$idDefesa. ')';
			
			$sql = $sql1 . 'UNION ' . $sql;
			
			
	//		var_dump(%sql)
			$database->setQuery($sql);
			return $database->loadObjectList();
		} 
		
		// para validaçao do convite a ser gerado
		public function visualizarBanca($idDefesa) {
			$database =& JFactory::getDBO();
			$sql = "SELECT status_banca, justificativa FROM #__banca_controledefesas JOIN #__defesa AS d ON id = d.banca_id WHERE d.idDefesa =".$idDefesa;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
		       
}

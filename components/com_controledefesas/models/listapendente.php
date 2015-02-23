<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class ControledefesasModelListaPendente extends JModelItem
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





//			 AND (d.tipoDefesa LIKE 'T' OR d.tipoDefesa LIKE 'D')
			
			$sql_status_banca = '';
			$sql_nome_aluno = '';	
			$sql_nome_orientador = '';
			$sql_tipo_banca = '';
			$sql_linha_pesquisa = '';
			$sql_tipo_curso = ''; 
			


	if ($status_banca == 1) {


			$sql_standard = "select * from (select * from (select nomeProfessor,area,orientador,anoingresso,aluno_id, nome, curso, (0) as QTD_DEFESA from 
            (
              select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, (count(aluno_id)) as INDEFERIDAS from j17_aluno a left join j17_defesa d 
              on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
              where aluno_id in (select a.id FROM j17_aluno as a where curso <> 3 AND status = 0 AND status_banca = 0) 
              GROUP BY aluno_id
            ) as z
              WHERE aluno_id not in 
              
                        (SELECT aluno_id FROM 
                (
                  select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, count(aluno_id) as QTD_DEFESAS from j17_aluno a left join j17_defesa d 
                  on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
                  where aluno_id in (select id FROM j17_aluno where curso = 1 AND status = 0 AND (status_banca is null OR status_banca = 1)) 
                  GROUP BY aluno_id

                  union 
                  select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, count(aluno_id) as QTD_DEFESAS from j17_aluno a left join j17_defesa d 
                  on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
                  where aluno_id in (select id FROM j17_aluno where curso = 2 AND status = 0 AND (status_banca is null OR status_banca = 1)) 
                  GROUP BY aluno_id

                ) AS x 
              GROUP BY aluno_id)
              GROUP BY aluno_id
        union

                  select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, count(aluno_id) as QTD_DEFESAS from j17_aluno a left join j17_defesa d 
                  on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
                  where aluno_id in (select id FROM j17_aluno where curso = 1 AND status = 0 AND (status_banca is null OR status_banca = 1)) 
                  GROUP BY aluno_id

                  union 
                  select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, count(aluno_id) as QTD_DEFESAS from j17_aluno a left join j17_defesa d 
                  on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
                  where aluno_id in (select id FROM j17_aluno where curso = 2 AND status = 0 AND (status_banca is null OR status_banca = 1)) 
                  GROUP BY aluno_id) as geral
where (curso = 1 AND QTD_DEFESA <2) OR (curso = 2 AND QTD_DEFESA <3)
union
select nomeProfessor,area,orientador,anoingresso,a.id,nome,curso, 0 as QTD_DEFESAS from j17_aluno a left join j17_defesa d on (a.id = d.aluno_id) join j17_professores on orientador = j17_professores.id
                  where idDefesa is NULL
)as geral 
where ((curso = 1 AND QTD_DEFESA = 0 and (((DATEDIFF(date_add(anoingresso, interval 1 year), sysdate()) < 0)))) or 
      (curso = 1 AND QTD_DEFESA = 1 and (((DATEDIFF(date_add(anoingresso, interval 2 year), sysdate()) < 0)))) or
      (curso = 2 AND QTD_DEFESA = 0 and (((DATEDIFF(date_add(anoingresso, interval 2 year), sysdate()) < 0)))) or
      (curso = 2 AND QTD_DEFESA = 1 and (((DATEDIFF(date_add(anoingresso, interval 3 year), sysdate()) < 0)))) or
      (curso = 2 AND QTD_DEFESA = 2 and (((DATEDIFF(date_add(anoingresso, interval 4 year), sysdate()) < 0)))))
";

}
else if ($status_banca == 2) {

			$sql_standard = "select * from (select * from (select nomeProfessor,area,orientador,anoingresso,aluno_id, nome, curso, (0) as QTD_DEFESA from 
            (
              select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, (count(aluno_id)) as INDEFERIDAS from j17_aluno a left join j17_defesa d 
              on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
              where aluno_id in (select a.id FROM j17_aluno as a where curso <> 3 AND status = 0 AND status_banca = 0) 
              GROUP BY aluno_id
            ) as z
              WHERE aluno_id not in 
              
                        (SELECT aluno_id FROM 
                (
                  select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, count(aluno_id) as QTD_DEFESAS from j17_aluno a left join j17_defesa d 
                  on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
                  where aluno_id in (select id FROM j17_aluno where curso = 1 AND status = 0 AND (status_banca is null OR status_banca = 1)) 
                  GROUP BY aluno_id

                  union 
                  select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, count(aluno_id) as QTD_DEFESAS from j17_aluno a left join j17_defesa d 
                  on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
                  where aluno_id in (select id FROM j17_aluno where curso = 2 AND status = 0 AND (status_banca is null OR status_banca = 1)) 
                  GROUP BY aluno_id

                ) AS x 
              GROUP BY aluno_id)
              GROUP BY aluno_id
        union

                  select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, count(aluno_id) as QTD_DEFESAS from j17_aluno a left join j17_defesa d 
                  on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
                  where aluno_id in (select id FROM j17_aluno where curso = 1 AND status = 0 AND (status_banca is null OR status_banca = 1)) 
                  GROUP BY aluno_id

                  union 
                  select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, count(aluno_id) as QTD_DEFESAS from j17_aluno a left join j17_defesa d 
                  on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
                  where aluno_id in (select id FROM j17_aluno where curso = 2 AND status = 0 AND (status_banca is null OR status_banca = 1)) 
                  GROUP BY aluno_id) as geral
where (curso = 1 AND QTD_DEFESA <2) OR (curso = 2 AND QTD_DEFESA <3)
union
select nomeProfessor,area,orientador,anoingresso,a.id,nome,curso, 0 as QTD_DEFESAS from j17_aluno a left join j17_defesa d on (a.id = d.aluno_id) join j17_professores on orientador = j17_professores.id
                  where idDefesa is NULL
)as geral 
where ((curso = 1 AND QTD_DEFESA = 0 and (((DATEDIFF(date_add(anoingresso, interval 1 year), sysdate()) >= 0)))) or 
      (curso = 1 AND QTD_DEFESA = 1 and (((DATEDIFF(date_add(anoingresso, interval 2 year), sysdate()) >= 0)))) or
      (curso = 2 AND QTD_DEFESA = 0 and (((DATEDIFF(date_add(anoingresso, interval 2 year), sysdate()) >= 0)))) or
      (curso = 2 AND QTD_DEFESA = 1 and (((DATEDIFF(date_add(anoingresso, interval 3 year), sysdate()) >= 0)))) or
      (curso = 2 AND QTD_DEFESA = 2 and (((DATEDIFF(date_add(anoingresso, interval 4 year), sysdate()) >= 0)))))
";

}
else { 

			$sql_standard = "select * from (select * from (select nomeProfessor,area,orientador,anoingresso,aluno_id, nome, curso, (0) as QTD_DEFESA from 
            (
              select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, (count(aluno_id)) as INDEFERIDAS from j17_aluno a left join j17_defesa d 
              on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
              where aluno_id in (select a.id FROM j17_aluno as a where curso <> 3 AND status = 0 AND status_banca = 0) 
              GROUP BY aluno_id
            ) as z
              WHERE aluno_id not in 
              
                        (SELECT aluno_id FROM 
                (
                  select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, count(aluno_id) as QTD_DEFESAS from j17_aluno a left join j17_defesa d 
                  on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
                  where aluno_id in (select id FROM j17_aluno where curso = 1 AND status = 0 AND (status_banca is null OR status_banca = 1)) 
                  GROUP BY aluno_id

                  union 
                  select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, count(aluno_id) as QTD_DEFESAS from j17_aluno a left join j17_defesa d 
                  on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
                  where aluno_id in (select id FROM j17_aluno where curso = 2 AND status = 0 AND (status_banca is null OR status_banca = 1)) 
                  GROUP BY aluno_id

                ) AS x 
              GROUP BY aluno_id)
              GROUP BY aluno_id
        union

                  select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, count(aluno_id) as QTD_DEFESAS from j17_aluno a left join j17_defesa d 
                  on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
                  where aluno_id in (select id FROM j17_aluno where curso = 1 AND status = 0 AND (status_banca is null OR status_banca = 1)) 
                  GROUP BY aluno_id

                  union 
                  select nomeProfessor,area,orientador,anoingresso,aluno_id,nome,curso, count(aluno_id) as QTD_DEFESAS from j17_aluno a left join j17_defesa d 
                  on (a.id = d.aluno_id) left join j17_banca_controledefesas as b on b.id = d.banca_id join j17_professores on orientador = j17_professores.id
                  where aluno_id in (select id FROM j17_aluno where curso = 2 AND status = 0 AND (status_banca is null OR status_banca = 1)) 
                  GROUP BY aluno_id) as geral
where (curso = 1 AND QTD_DEFESA <2) OR (curso = 2 AND QTD_DEFESA <3)
union
select nomeProfessor,area,orientador,anoingresso,a.id,nome,curso, 0 as QTD_DEFESAS from j17_aluno a left join j17_defesa d on (a.id = d.aluno_id) join j17_professores on orientador = j17_professores.id
                  where idDefesa is NULL
)as geral   where (TRUE)";


}

			if($tipo_curso == 1)
				$sql_tipo_curso = " AND curso = 1 ";
			else if ($tipo_curso == 2)
				$sql_tipo_curso = " AND curso = 2 ";

				

				if ($tipo_banca == 0){
					$sql_tipo_banca = " and (QTD_DEFESA = 1 AND curso = 1) "; //dissertação
				}

				else if ($tipo_banca == 1){
					$sql_tipo_banca = " and (QTD_DEFESA = 2 AND curso = 2) "; //tese
				}
				else if ($tipo_banca == 2){
					$sql_tipo_banca = " and (QTD_DEFESA = 0) "; //q1
				}
				else if ($tipo_banca == 3){
					$sql_tipo_banca = " and (QTD_DEFESA = 1 AND curso = 2) "; // q2
				}






			if($nome_aluno != '')
				$sql_nome_aluno = " AND nome LIKE '%".$nome_aluno.'%\' ';		
			
			if($nome_orientador != '')
				$sql_nome_orientador = " AND nomeProfessor LIKE '%".$nome_orientador.'%\' ';



			if($linha_pesquisa > 0)
				$sql_linha_pesquisa = ' AND area = '.$linha_pesquisa;
							
			$sql = $sql_standard.$sql_status_banca.$sql_nome_aluno.$sql_nome_orientador.$sql_tipo_curso.$sql_tipo_banca.$sql_linha_pesquisa;
			$database->setQuery($sql);		
			return $database->loadObjectList();
	    
		}
		
		///Consultas para gerar ata
		public function visualizarAluno($idAluno){
			$database =& JFactory::getDBO();
			$sql = "SELECT a.nome as nome_aluno, a.curso, anoingresso, nomeProfessor, p.email as profemail, a.email as alunoemail FROM #__aluno as a join #__professores p on a.orientador = p.id
           WHERE a.id= ".$idAluno;
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
		
		
		public function verificar_aluno($idAluno){

			$database =& JFactory::getDBO();
			$sql = "SELECT a.id, a.nome as nome_aluno, a.curso, d.tipoDefesa FROM #__aluno AS a JOIN #__defesa AS d ON d.aluno_id =".$idAluno;
			$database->setQuery($sql);
			return $database->loadObjectList();

		}
		       
}

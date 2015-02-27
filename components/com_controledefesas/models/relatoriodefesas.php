<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class ControledefesasModelRelatorioDefesas extends JModelItem
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
    public function getDefesasPorPeriodo($data_inicial, $data_final) {
		$database =& JFactory::getDBO();
		$sql_standard = "SELECT d.*, date_format(d.data,'%d/%m/%Y') as data_defesa, ifnull(d.conceito, '') as conceito_defesa,
						       a.nome as nome_aluno, a.id as idAluno, a.curso, 
						       (case a.curso when 1 then 'Mestrado' when 2 then 'Doutorado' when 3 then 'Especial' end) as desc_curso, 
						       d.tipoDefesa, 
						       (case d.tipoDefesa 
						     		when upper('Q1') then 'Qualificação 1' 
						     		when upper('Q2') then 'Qualificação 2' 
						     		when upper('D') then 'Dissertação' 
						     		when upper('T') then 'Tese' end) as desc_tipo_defesa 
							FROM ((
							      j17_defesa as d 
							      LEFT JOIN j17_aluno as a ON a.id = d.aluno_id) 
							      LEFT JOIN j17_banca_controledefesas as bcd ON d.banca_id = bcd.id) 
							WHERE ifnull(bcd.status_banca, '') = '1' ";
		
		$sql_data_inicial = '';
		$sql_data_final = '';
		$sql_orderby = " ORDER BY d.data DESC, desc_curso, desc_tipo_defesa";
		
		if ($data_inicial != NULL && $data_inicial != false && $data_inicial != '')
			$sql_data_inicial = " AND d.data >= STR_TO_DATE('".$data_inicial."','%d-%m-%Y')";
		if ($data_final != NULL && $data_final != false && $data_final != '')
			$sql_data_final = " AND d.data <= STR_TO_DATE('".$data_final."','%d-%m-%Y')";
		
		$sql = $sql_standard.$sql_data_inicial.$sql_data_final.$sql_orderby;
		
		$database->setQuery($sql);
		return $database->loadObjectList();
	}
	
    public function getDefesasPorPeriodoProfessor($data_inicial, $data_final, $nome_professor, $id_membro_banca, $id_professor) {
		$database =& JFactory::getDBO();

		
		/*
		// O orientador é o presidente da banca. Não existe registro do presidente na j17_banca_has_membrosbanca.
		$sqlOrientador = "select a.nome as nome_aluno, 
							(case a.curso when 1 then 'Mestrado' when 2 then 'Doutorado' when 3 then 'Especial' end) as desc_curso, 
							(case d.tipoDefesa 
						     		when upper('Q1') then 'Qualificação 1' 
						     		when upper('Q2') then 'Qualificação 2' 
						     		when upper('D') then 'Dissertação' 
						     		when upper('T') then 'Tese' end) as desc_tipo_defesa, 
							ifnull(d.conceito, '') as conceito_defesa, 
							date_format(d.data,'%d/%m/%Y') as data_defesa, 
							'Presidente' funcao_membro 
							from j17_professores p, j17_banca_controledefesas b, j17_defesa d, j17_aluno a 
							where d.banca_id = b.id and d.aluno_id = a.id and a.orientador = p.id and p.id = $id_professor";

*/
		$sql_standard = "SELECT a.nome as nome_aluno, 
								(case a.curso when 1 then 'Mestrado' when 2 then 'Doutorado' when 3 then 'Especial' end) as desc_curso, 
								(case d.tipoDefesa 
						     		when upper('Q1') then 'Qualificação 1' 
						     		when upper('Q2') then 'Qualificação 2' 
						     		when upper('D') then 'Dissertação' 
						     		when upper('T') then 'Tese' end) as desc_tipo_defesa, 
								ifnull(d.conceito, '') as conceito_defesa, 
								date_format(d.data,'%d/%m/%Y') as data_defesa, 
								(case ifnull(bhm.funcao, '') 
									when 'P' then 'Presidente' 
									when 'I' then 'Membro Interno' 
									when 'E' then 'Membro Externo' 
									when  '' then 'Examinador' end) as funcao_membro 
							FROM ((((
							      j17_defesa as d 
							      	LEFT JOIN j17_aluno as a ON a.id = d.aluno_id) 
						        	LEFT JOIN j17_banca_controledefesas as bcd ON bcd.id = d.banca_id AND ifnull(bcd.status_banca, '') = '1') 
						        	LEFT JOIN j17_banca_has_membrosbanca as bhm ON bhm.banca_id = bcd.id)
						        	LEFT JOIN j17_membrosbanca as mb ON mb.id = bhm.membrosbanca_id)
							WHERE d.banca_id is not null ";
		
		$sql = '';
		$sql_data_inicial = '';
		$sql_data_final = '';
		$sql_id_membro_banca = '';
		$sql_nome_professor = '';
		$sql_orderby = " ORDER BY nome_aluno";
		
		if ($data_inicial != NULL && $data_inicial != false && $data_inicial != ''){
			$sql_data_inicial = " AND d.data >= STR_TO_DATE('".$data_inicial."','%d-%m-%Y')";
		}
		
		if ($data_final != NULL && $data_final != false && $data_final != ''){
			$sql_data_final = " AND d.data <= STR_TO_DATE('".$data_final."','%d-%m-%Y')";
		}

		if (($id_membro_banca != NULL && $id_membro_banca != false && $id_membro_banca != '') 
			&& $nome_professor != ''){
			$sql_id_membro_banca = " AND (mb.id = ".$id_membro_banca." OR upper(d.examinador) LIKE upper('%".$nome_professor."%'))";
		}
		/**
		if (($id_membro_banca == NULL || $id_membro_banca == false || $id_membro_banca == '') 
			&& $nome_professor != ''){
			$sql_nome_professor = " AND upper(d.examinador) LIKE upper('%".$nome_professor."%')";
		}
		*/

		/*if ($id_professor != NULL && $id_professor != false && $id_professor != ''){
			$sql = "SELECT * FROM (". $sqlOrientador . $sql_data_inicial.$sql_data_final . ' UNION ' . $sql_standard.$sql_data_inicial.$sql_data_final.$sql_id_membro_banca.$sql_nome_professor.") a ORDER BY nome_aluno";
		}
		else{ */
			$sql = $sql_standard.$sql_data_inicial.$sql_data_final.$sql_id_membro_banca.$sql_nome_professor.$sql_orderby;
	//	}

		$database->setQuery($sql);
		return $database->loadObjectList();
	}

	public function getMembrosBanca() {
	
		$database =& JFactory::getDBO();
		$sql = "select id, trim(nome) as nome from #__membrosbanca";
		$database->setQuery($sql);
		$membrosBanca = $database->loadObjectList();
		return $membrosBanca;
	}

	public function getProfessores() {
	
		$database =& JFactory::getDBO();
		$sql = "select id, trim(nomeProfessor) as nomeProfessor from #__professores";
		$database->setQuery($sql);
		$professores = $database->loadObjectList();
		return $professores;
	}
}

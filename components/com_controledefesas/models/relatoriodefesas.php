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
		$sql_orderby = " ORDER BY DATE_FORMAT(STR_TO_DATE(d.data,'%Y-%m-%d'),'%Y-%m-%d'), desc_curso, desc_tipo_defesa";
		
		if ($data_inicial != NULL && $data_inicial != false && $data_inicial != '')
			$sql_data_inicial = " AND d.data >= STR_TO_DATE('".$data_inicial."','%d-%m-%Y')";
		if ($data_final != NULL && $data_final != false && $data_final != '')
			$sql_data_final = " AND d.data <= STR_TO_DATE('".$data_final."','%d-%m-%Y')";
		
		$sql = $sql_standard.$sql_data_inicial.$sql_data_final.$sql_orderby;
		
		$database->setQuery($sql);
		return $database->loadObjectList();
	}
	
    public function getDefesasPorPeriodoProfessor($data_inicial, $data_final, $id_membro_banca) {
		$database =& JFactory::getDBO();

		$sql_standard = "SELECT a.nome as nome_aluno, mb.nome as nome_professor, 
								(case a.curso when 1 then 'Mestrado' when 2 then 'Doutorado' when 3 then 'Especial' end) as desc_curso, 
								(case d.tipoDefesa 
						     		when upper('Q1') then 'Qualificação 1' 
						     		when upper('Q2') then 'Qualificação 2' 
						     		when upper('D') then 'Dissertação' 
						     		when upper('T') then 'Tese' end) as desc_tipo_defesa, 
								ifnull(d.conceito, '') as conceito_defesa, 
								date_format(d.data,'%d/%m/%Y') as data_defesa, 
								(case ifnull(bhm.funcao, ' ') 
									when 'P' then 'Presidente' 
									when 'I' then 'Membro Interno' 
									when 'E' then 'Membro Externo' 
									when ' ' then '' end) as funcao_membro 
							FROM ((((
							      j17_defesa as d 
							      	JOIN j17_aluno as a ON a.id = d.aluno_id) 
						        	LEFT JOIN j17_banca_controledefesas as bcd ON bcd.id = d.banca_id) 
						        	LEFT JOIN j17_banca_has_membrosbanca as bhm ON bhm.banca_id = bcd.id)
						        	LEFT JOIN j17_membrosbanca as mb ON mb.id = bhm.membrosbanca_id)
							WHERE ifnull(bcd.status_banca, '') = '1' ";
		
		$sql = '';
		$sql_data_inicial = '';
		$sql_data_final = '';
		$sql_id_membro_banca = '';
		$sql_orderby = " ORDER BY DATE_FORMAT(STR_TO_DATE(d.data,'%Y-%m-%d'),'%Y-%m-%d'), desc_curso, desc_tipo_defesa";
		
		if ($data_inicial != NULL && $data_inicial != false && $data_inicial != ''){
			$sql_data_inicial = " AND d.data >= STR_TO_DATE('".$data_inicial."','%d-%m-%Y')";
		}
		
		if ($data_final != NULL && $data_final != false && $data_final != ''){
			$sql_data_final = " AND d.data <= STR_TO_DATE('".$data_final."','%d-%m-%Y')";
		}

		if ($id_membro_banca != NULL && $id_membro_banca != false && $id_membro_banca != '')
			$sql_id_membro_banca = " AND mb.id = ".$id_membro_banca;

		/*
		if (($id_membro_banca != NULL && $id_membro_banca != false && $id_membro_banca != '') 
			&& $nome_professor != ''){
			$sql_id_membro_banca = " AND (mb.id = ".$id_membro_banca." OR upper(d.examinador) LIKE upper('%".$nome_professor."%'))";
		}
		
		if (($id_membro_banca == NULL || $id_membro_banca == false || $id_membro_banca == '') 
			&& $nome_professor != ''){
			$sql_nome_professor = " AND upper(d.examinador) LIKE upper('%".$nome_professor."%')";
		}
		*/

		$sql = $sql_standard.$sql_data_inicial.$sql_data_final.$sql_id_membro_banca.$sql_orderby;
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

<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class DefesasOrientadorModelListaDefesas extends JModelItem
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
        
      	    
	    public function filtroDefesa($userId, $nome_aluno, $curso, $tipo_defesa, $data_defesa, $local_defesa) {
			$database =& JFactory::getDBO();
			$sql_standard = "SELECT a.nome as nome_aluno, a.id as idAluno, d.idDefesa, d.titulo as titulo_defesa, 
									   a.curso, d.tipoDefesa, date_format(d.data,'%d/%m/%Y') as data_defesa, d.local as local_defesa, 
									   ifnull(d.conceito, '') as conceito_defesa, d.banca_id, ifnull(bcd.status_banca, '2') as status_banca 
								FROM (((
								      j17_defesa as d 
								      JOIN j17_aluno as a ON a.id = d.aluno_id) 
								      JOIN j17_professores as p ON p.id = a.orientador) 
									  LEFT JOIN j17_banca_controledefesas as bcd ON d.banca_id = bcd.id) 
								WHERE p.idUser = ".$userId;
			
			$sql_nome_aluno = '';
			$sql_curso = '';
			$sql_tipo_defesa = '';
			$sql_data_defesa = '';
			$sql_local_defesa = '';
			$sql_orderby = " ORDER BY ifnull(d.conceito, ''), DATE_FORMAT(STR_TO_DATE(d.data,'%Y-%m-%d'),'%Y-%m-%d')";

			if($nome_aluno != '')
				$sql_nome_aluno = " AND upper(a.nome) LIKE upper('%".$nome_aluno.'%\')';
			
			if($curso > 0)
				$sql_curso = " AND a.curso = ".$curso;

			if($tipo_defesa != '')
				$sql_tipo_defesa = " AND d.tipoDefesa = '".$tipo_defesa."'";

			if($data_defesa != '')
				$sql_data_defesa = " AND date_format(d.data,'%d/%m/%Y') = '".$data_defesa."'";

			if($local_defesa != '')
				$sql_local_defesa = " AND upper(d.local) LIKE upper('%".$local_defesa.'%\')';

			$sql = $sql_standard.$sql_nome_aluno.$sql_curso.$sql_tipo_defesa.$sql_data_defesa.$sql_local_defesa.$sql_orderby;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
}

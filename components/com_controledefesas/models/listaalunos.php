<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class ControledefesasModelListaAlunos extends JModelItem
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
        
		public function filtroAluno($nome_aluno, $matricula, $curso, $linha_pesquisa, $nome_orientador) {
			$database =& JFactory::getDBO();
			$sql_standard = "SELECT a.nome as nome_aluno, a.id as idAluno, a.matricula, a.idiomaExameProf, a.dataExameProf, a.conceitoExameProf, 
									   a.curso, a.area as linha_pesquisa, p.nomeProfessor as nome_orientador 
								FROM (j17_aluno as a 
								      JOIN j17_professores as p ON p.id = a.orientador)";
			
			$sql_where = '';
			$sql_orderby = " ORDER BY a.nome";

			if(($nome_aluno == NULL) AND 
			   ($matricula == NULL) AND
			   ($curso == NULL) AND
			   ($linha_pesquisa == NULL) AND
			   ($nome_orientador == NULL))
			{
				$sql_where = " WHERE a.id = NULL";
			}
			else
			{
				if($nome_aluno != '')
					$sql_where = " WHERE upper(a.nome) LIKE upper('%".$nome_aluno.'%\')';
				
				if($matricula != ''){
					if($sql_where == '')
						$sql_where = " WHERE a.matricula = '".$matricula."'";
					else
						$sql_where = $sql_where." AND a.matricula = '".$matricula."'";
				}

				if($curso > 0){
					if($sql_where == '')
						$sql_where = " WHERE a.curso = ".$curso;
					else
						$sql_where = $sql_where." AND a.curso = ".$curso;
				}

				if($linha_pesquisa > 0){
					if($sql_where == '')
						$sql_where = " WHERE a.area = ".$linha_pesquisa;
					else
						$sql_where = $sql_where." AND a.area = ".$linha_pesquisa;
				}

				if($nome_orientador != ''){
					if($sql_where == '')
						$sql_where = " WHERE upper(p.nomeProfessor) LIKE upper('%".$nome_orientador.'%\')';
					else
						$sql_where = $sql_where." AND upper(p.nomeProfessor) LIKE upper('%".$nome_orientador.'%\')';
				}
			}
			$sql = $sql_standard.$sql_where.$sql_orderby;
			$database->setQuery($sql);
			return $database->loadObjectList();
		}
}

<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class DefesasCoordenadorModelListaBancas extends JModelItem
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
        
      	    
	    public function filtroBanca($status_banca, $nome_aluno, $nome_orientador, $tipo_banca, $linha_pesquisa) {
			$database =& JFactory::getDBO();
			$sql_standard = "SELECT d.idDefesa, bcd.id as idBanca, a.id as idAluno, bcd.status_banca, a.nome as nome_aluno, M.nome as nome_orientador, d.tipoDefesa as tipo_banca, a.area as linha_pesquisa
					FROM ((((j17_aluno as a JOIN j17_defesa as d ON d.aluno_id = a.id) JOIN j17_banca_controledefesas as bcd ON d.banca_id = bcd.id) JOIN j17_banca_has_membrosbanca AS MB ON bcd.id = MB.banca_id) JOIN j17_membrosbanca AS M ON MB.membrosbanca_id = M.id) 
					WHERE MB.funcao LIKE 'P'";


//			 AND (d.tipoDefesa LIKE 'T' OR d.tipoDefesa LIKE 'D')
			
			$sql_status_banca = '';
			$sql_nome_aluno = '';	
			$sql_nome_orientador = '';
			$sql_tipo_banca = '';
			$sql_linha_pesquisa = '';
			
				
			if($status_banca < 3){
				if($status_banca ==2)
					$sql_status_banca = ' AND bcd.status_banca IS NULL';
				else
					$sql_status_banca = ' AND bcd.status_banca = '.$status_banca;
			}
			
			if($nome_aluno != '')
				$sql_nome_aluno = " AND a.nome LIKE '%".$nome_aluno.'%\'';		
			
			if($nome_orientador != '')
				$sql_nome_orientador = " AND M.nome LIKE '%".$nome_orientador.'%\'';
			
			if($tipo_banca < 2){
				if($tipo_banca == 1)
					$sql_tipo_banca = " AND d.tipoDefesa LIKE 'D'";
				else
					$sql_tipo_banca = " AND d.tipoDefesa LIKE 'T'";
			}else
				$sql_tipo_banca = " AND (d.tipoDefesa LIKE 'T' OR d.tipoDefesa LIKE 'D')";
				
			if($linha_pesquisa > 0)
				$sql_linha_pesquisa = ' AND a.area = '.$linha_pesquisa;
							
			$sql = $sql_standard.$sql_status_banca.$sql_nome_aluno.$sql_nome_orientador.$sql_tipo_banca.$sql_linha_pesquisa;
		
			$database->setQuery($sql);		
			return $database->loadObjectList();
	    
		}        
}

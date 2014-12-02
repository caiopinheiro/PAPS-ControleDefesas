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
        
      	    
	    public function filtroBanca($nome_orientador, $status_bancas) {
			//Consulta por nome		
//			SELECT sql1.id, sql1.status_banca, sql1.nome from (SELECT CD.id, status_banca, nome FROM j17_banca_controledefesas AS CD JOIN j17_banca_has_membrosbanca AS MB ON CD.id = MB.banca_id JOIN j17_membrosbanca AS M ON MB.membrosbanca_id = M.id WHERE MB.funcao LIKE 'presidente') as sql1 where nome LIKE '%bruno%'

			//Consulta por status_banca
//			SELECT sql1.id, sql1.status_banca, sql1.nome from (SELECT CD.id, status_banca, nome FROM j17_banca_controledefesas AS CD JOIN j17_banca_has_membrosbanca AS MB ON CD.id = MB.banca_id JOIN j17_membrosbanca AS M ON MB.membrosbanca_id = M.id WHERE MB.funcao LIKE 'presidente') as sql1 where sql1.status_banca = 1

			$database =& JFactory::getDBO();
			$sql = "(SELECT CD.id, status_banca, nome FROM #__banca_controledefesas AS CD JOIN #__banca_has_membrosbanca AS MB ON CD.id = MB.banca_id JOIN #__membrosbanca AS M ON MB.membrosbanca_id = M.id WHERE MB.funcao LIKE 'presidente')";
				
			if(($status_bancas < 3) AND ($nome_orientador != ''))
				if($status_bancas ==2){
					$database->setQuery("SELECT sql1.id, sql1.status_banca, sql1.nome FROM ".$sql." as sql1 WHERE sql1.status_banca IS NULL and sql1.nome LIKE '%".$nome_orientador.'%\'');
				}else
					$database->setQuery("SELECT sql1.id, sql1.status_banca, sql1.nome FROM ".$sql." as sql1 WHERE sql1.status_banca = ".$status_bancas." and sql1.nome LIKE '%".$nome_orientador.'%\'' );
			if(($status_bancas < 3) AND ($nome_orientador == ''))
				if($status_bancas ==2){
					$database->setQuery("SELECT sql1.id, sql1.status_banca, sql1.nome FROM ".$sql." as sql1 WHERE sql1.status_banca IS NULL");
				}else
					$database->setQuery("SELECT sql1.id, sql1.status_banca, sql1.nome FROM ".$sql." as sql1 WHERE sql1.status_banca = ".$status_bancas);
			if(($status_bancas == 3) AND ($nome_orientador != ''))
				$database->setQuery("SELECT sql1.id, sql1.status_banca, sql1.nome FROM ".$sql." as sql1 WHERE sql1.nome LIKE '%".$nome_orientador.'%\'');
			if(($status_bancas == 3) AND ($nome_orientador == ''))
				$database->setQuery($sql);
						
			return $database->loadObjectList();
	    
		}

        
}

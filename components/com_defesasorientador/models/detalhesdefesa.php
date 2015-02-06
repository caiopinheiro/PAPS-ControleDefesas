<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * HelloWorld Model
 */
class DefesasOrientadorModelDetalhesDefesa extends JModelItem
{

	public function visualizarDefesa($idDefesa, $idAluno){

		$database =& JFactory::getDBO();
		$sql = "SELECT d.* FROM #__defesa AS d WHERE d.idDefesa = '$idDefesa' AND d.aluno_id = '$idAluno' ";
		$database->setQuery($sql);
		return $database->loadObjectList();
	}

	public function visualizarAluno($idAluno){

		$database =& JFactory::getDBO();
		$sql = "SELECT a.* FROM #__aluno AS a WHERE a.id = '$idAluno' ";
		$database->setQuery($sql);
		return $database->loadObjectList();
	}

	public function visualizarMembrosBanca($idBanca){

		$database =& JFactory::getDBO();
		$sql = "SELECT mb.nome, bhmb.funcao, mb.filiacao, mb.email FROM  #__banca_has_membrosbanca AS bhmb JOIN #__membrosbanca AS mb ON mb.id = bhmb.membrosbanca_id WHERE bhmb.banca_id = '$idBanca' ";
		$database->setQuery($sql);
		return $database->loadObjectList();
	}
	
	public function updateDefesa($idDefesa, $idAluno, $titulo, $data, $horario, $local, $resumo){

		$database =& JFactory::getDBO();
		$sql = "UPDATE #__defesa AS d SET titulo = '".$titulo."', data = date_format(STR_TO_DATE('".$data."','%d/%m/%Y'),'%Y-%m-%d'), horario = '".$horario."', local = '".$local."', resumo = '".$resumo."' WHERE d.idDefesa = ".$idDefesa." AND d.aluno_id = ".$idAluno;
		
		$database->setQuery($sql);
		$resultado = $database->Query();
		return $resultado;
	}

	public function deleteDefesa($idDefesa, $idAluno, $idBanca){

		$database =& JFactory::getDBO();
		$resultado = 0;

		try{

			$database->transactionStart();

			$sql = "DELETE FROM #__defesa WHERE idDefesa = ".$idDefesa." AND aluno_id = ".$idAluno;
			$database->setQuery($sql);
			$resultado = $database->execute();

			try{
				$sql = "DELETE FROM #__banca_has_membrosbanca WHERE banca_id = ".$idBanca;
				$database->setQuery($sql);
				$resultado = $database->execute();

				try{
					$sql = "DELETE FROM #__banca_controledefesas WHERE id = ".$idBanca;
					$database->setQuery($sql);
					$resultado = $database->execute();

					$database->transactionCommit();
				}
				catch (Exception $e3)
				{
					throw $e3;
				}
			}
			catch (Exception $e2)
			{
				throw $e2;
			}
		}
		catch (Exception $e1)
		{
			$resultado = 0;
		    $database->transactionRollback();
		    //JErrorPage::render($e);
		}

		return $resultado;
	}
}

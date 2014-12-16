<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class DefesasOrientadorViewSolicitarBanca extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) 
        {
                // Assign data to the view
                $this->msg = '';
                $this->aluno = $this->get('aluno');
                $this->membrosbanca = $this->get('membrosbanca');
                $this->faseDefesa = $this->get('fasedefesa');
                $this->mapaFases = $this->get('mapafases');
                $this->finalizouCurso = false;
                $this->existeSemAprovacao = false;
                $this->semProeficiencia = false;
                
                $this->nomeFase = array("P" => "Proeficiência","Q1" => "Qualificação 1", 'Q2' => "Qualificação 2", 'D' => 'Dissertação', 'T' => 'Tese');
                
                
                if ($this->faseDefesa[0] == $this->mapaFases[0] && !$this->faseDefesa[1]) {
                	$this->semProeficiencia = true;
                }
                
                if (!$this->faseDefesa[1]) {
                	$this->existeSemAprovacao = true;
                } else if ($this->faseDefesa[0] == $this->mapaFases[count($this->mapaFases) - 1]) {
                	$this->finalizouCurso = true;	
                } else {
	                	$count = 0;
						$achou = 0;
	                	while ($count < count($this->mapaFases) && !$achou){
	                		if ($this->faseDefesa[0] == $this->mapaFases[$count])
	                			$achou = 1;
	                		$count++;
	                	}
	                	
	                	$this->faseDefesa[0] = $this->mapaFases[$count];
                	}
                
                
                // Display the view
                parent::display($tpl);
        }
        
        
        
		
		
}

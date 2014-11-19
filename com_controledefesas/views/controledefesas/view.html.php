<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class ControleDefesasViewControleDefesas extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) 
        {
                // Assign data to the view
                $this->msg = 'Controle de Defesas';
                
                // Display the view
                parent::display($tpl);
        }
        
        function solicitarBanca(){
			$this->msg = 'Solicitar Banca';
		}
		
		function aprovarBanca(){
			echo 'Aprovar Banca';
		}
		
		function confirmarBanca(){
			echo 'Confirmar Banca';
		}
		
		function lancarConceito(){
			echo 'Lançar Conceito';
		}
		
		function consultarDefesas(){
			echo 'Consultar Defesas';
		}
		
		function gerenciarMembrosBanca(){
			echo 'Gerenciar Membros Banca';
		}
		
		function enviarEmails(){
			echo 'Enviar Emails';
		}
		
		function gerarRelatorio(){
			echo 'Gerar Relatorio';
		}
		
}

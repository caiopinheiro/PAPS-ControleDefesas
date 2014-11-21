<?php

/**
 * @version     1.0.0
 * @package     com_controledefesas
 * @copyright   Copyright (C) 2014. Todos os direitos reservados.
 * @license     GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 * @author      Caio <pinheiro.caiof@gmail.com> - http://
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class ControledefesasController extends JController {

    /**
     * Method to display a view.
     *
     * @param	boolean			$cachable	If true, the view output will be cached
     * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return	JController		This object to support chaining.
     * @since	1.5
     */
    public function display($cachable = false, $urlparams = false) {
        require_once JPATH_COMPONENT . '/helpers/controledefesas.php';

        parent::display($cachable, $urlparams);

        return $this;
    }
    
    function confirmarbanca(){
		echo "<p>confirmarbanca</p>";
	}
	
	    function consultardefesas(){
		echo "<p>consultardefesas</p>";
	}
	
	    function gerenciarmembros(){
		echo "<p>gerenciarmembros</p>";
	}
	
	    function enviaremail(){
		echo "<p>enviaremail</p>";
	}
	
	    function gerarrelatorios(){
		echo "<p>gerarrelatorios</p>";
	}

}

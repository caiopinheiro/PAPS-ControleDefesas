-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: Nov 25, 2013 as 03:22 
-- Versão do Servidor: 5.1.41
-- Versão do PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `icomp`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_funcionarios`
--

CREATE TABLE IF NOT EXISTS `j17_funcionarios` (
  `id` tinyint(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) NOT NULL,
  `cpf` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `endereco` varchar(100) NOT NULL,
  `bairro` varchar(30) NOT NULL,
  `cidade` varchar(30) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `tel_residencial` varchar(15) NOT NULL,
  `tel_celular` varchar(15) NOT NULL,
  `data_ingresso` date NOT NULL,
  `siape` int(10) DEFAULT NULL,
  `cargo` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `j17_funcionarios`
--

INSERT INTO `j17_funcionarios` (`id`, `nome`, `cpf`, `email`, `endereco`, `bairro`, `cidade`, `uf`, `cep`, `tel_residencial`, `tel_celular`, `data_ingresso`, `siape`, `cargo`) VALUES
(1, 'Elienai Silva', '002.974.510-55', 'elienai@dcc.ufam.edu.br', 'Rua da Ufam', 'Coroado', 'Manaus', 'AM', '69074-351', '(92) 3624-5487', '(92) 8154-2179', '1994-11-10', 10541, 'Secretária'),
(2, 'Helen Silva', '541.975.159-11', 'helen@dcc.ufam.edu.br', 'Rua da Ufam', 'Coroado', 'Manaus', 'AM', '69000-000', '(92) 3624-5987', '(92) 8124-5987', '2012-10-10', NULL, 'Assistente');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

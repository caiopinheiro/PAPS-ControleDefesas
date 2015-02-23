-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tempo de Geração: 
-- Versão do Servidor: 5.5.27
-- Versão do PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `icomp`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_contproj_agencias`
--

CREATE TABLE IF NOT EXISTS `j17_contproj_agencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(70) NOT NULL,
  `sigla` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `j17_contproj_agencias`
--

INSERT INTO `j17_contproj_agencias` (`id`, `nome`, `sigla`) VALUES
(1, 'Fundação de Apoio à Pesquisa do Estado do Amazonas', 'FAPEAM'),
(2, 'Conselho Nacional de Pesquisa e Desenvolvimento', 'CNPq'),
(3, 'Fundaçao Vertuglio Garva', 'FVG'),
(6, 'Fundação Carlos Carlota', 'FCCAR'),
(7, 'Coordenação de Aperfeiçoamento de Pessoal de Nível Superior', 'CAPES');

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_contproj_bancos`
--

CREATE TABLE IF NOT EXISTS `j17_contproj_bancos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(5) DEFAULT NULL,
  `nome` varchar(70) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=133 ;

--
-- Extraindo dados da tabela `j17_contproj_bancos`
--

INSERT INTO `j17_contproj_bancos` (`id`, `codigo`, `nome`) VALUES
(3, '246', 'ABC Brasil S.A.'),
(4, '25', 'Alfa S.A.'),
(5, '641', 'Alvorada S.A.'),
(6, '29', 'Banerj S.A.'),
(7, '0', 'Bankpar S.A.'),
(8, '740', 'Barclays S.A.'),
(9, '107', 'BBM S.A.'),
(10, '31', 'Beg S.A.'),
(11, '739', 'BGN S.A.'),
(12, '96', 'BM&FBOVESPA de Serviços de Liquidação e custódia S.A.'),
(13, '318', 'BGM S.A.'),
(14, '752', 'BNP Paribas Brasil S.A.'),
(15, '248', 'Boavista Interatlântico S.A.'),
(16, '218', 'Bonsucesso S.A.'),
(17, '65', 'Bracce S.A.'),
(18, '36', 'Bradesco BBI S.A.'),
(19, '204', 'Bradesco Cartões S.A.'),
(20, '394', 'Bradesco Financiamentos S.A.'),
(21, '237', 'Bradesco S.A.'),
(22, '225', 'Brascan S.A.'),
(23, '208', 'BTG Pactual S.A.'),
(24, '44', 'BVA S.A.'),
(25, '263', 'Cacique S.A.'),
(26, '473', 'Caixa Geral - Brasil S.A.'),
(27, '40', 'Cargill S.A.'),
(28, '0', 'Caterpillar S.A.'),
(29, '233', 'Cifra S.A.'),
(30, '745', 'Citibank S.A.'),
(31, 'M08', 'Citicard S.A.'),
(32, 'M19', 'CNH Capital S.A.'),
(33, '215', 'Comercial e de Investimento Sudameris S.A.'),
(34, '95', 'Confidence de Câmbio S.A.'),
(35, '756', 'Cooperativo do Brasil S.A. - BANCOOB'),
(36, '748', 'Cooperativo Sicredi S.A.'),
(37, '222', 'Credit Agricole Brasil S.A.'),
(38, '505', 'Credit Suisse (Brasil) S.A.'),
(39, '229', 'Cruzeiro do Sul S.A.'),
(40, '0', 'CSF S.A.'),
(41, '3', 'Banco da Amazônia S.A.'),
(42, '083-3', 'Banco da China Brasil S.A.'),
(43, '707', 'Daycoval S.A.'),
(44, 'M06', 'Banco de Lage Landen Brasil S.A.'),
(45, '24', 'Banco de Pernambuco S.A. - BANDEPE'),
(46, '456', 'Banco de Tokyo-Mitsubishi UFJ Brasil S.A.'),
(47, '214', 'Dibens S.A.'),
(48, '1', 'Banco do Brasil S.A.'),
(49, '47', 'Banco do Estado de Sergipe S.A.'),
(50, '37', 'Banco do Estado do Pará S.A.'),
(51, '41', 'Banco do Estado do Rio Grande do Sul S.A.'),
(52, '4', 'Banco do Nordeste do Brasil S.A.'),
(53, '265', 'Fator S.A.'),
(54, 'M03', 'Fiat S.A.'),
(55, '224', 'Fibra S.A.'),
(56, '626', 'Ficsa S.A.'),
(57, '0', 'Fidis S.A.'),
(58, '394', 'Finasa BMC S.A.'),
(59, 'M18', 'Ford S.A.'),
(60, 'M07', 'GMAC S.A.'),
(61, '612', 'Guanabara S.A.'),
(62, 'M22', 'Honda S.A.'),
(63, '63', 'Ibi S.A. Banco Múltiplo'),
(64, 'M11', 'IBM S.A.'),
(65, '604', 'Industrial do Brasil S.A.'),
(66, '320', 'Industrial e Comercial S.A.'),
(67, '653', 'Indusval S.A.'),
(68, '249', 'Investcred Unibanco S.A.'),
(69, '184', 'Itaú BBA S.A.'),
(70, '479', 'ItaúBank S.A'),
(71, '0', 'Itaucard S.A.'),
(72, 'M09', 'Itaucred Financiamentos S.A.'),
(73, '376', 'J. P. Morgan S.A.'),
(74, '74', 'J. Safra S.A.'),
(75, '217', 'John Deere S.A.'),
(76, '600', 'Luso Brasileiro S.A.'),
(77, '389', 'Mercantil do Brasil S.A.'),
(78, '746', 'Modal S.A.'),
(79, '45', 'Opportunity S.A.'),
(80, '79', 'Original do Agronegócio S.A.'),
(81, '623', 'Panamericano S.A.'),
(82, '611', 'Paulista S.A.'),
(83, '643', 'Pine S.A.'),
(84, '638', 'Prosper S.A.'),
(85, 'M24', 'PSA Finance Brasil S.A.'),
(86, '747', 'Rabobank International Brasil S.A.'),
(87, '356', 'Real S.A.'),
(88, '633', 'Rendimento S.A.'),
(89, 'M16', 'Rodobens S.A.'),
(90, '72', 'Rural Mais S.A.'),
(91, '453', 'Rural S.A.'),
(92, '422', 'Safra S.A.'),
(93, '33', 'Santander (Brasil) S.A.'),
(94, '749', 'Simples S.A.'),
(95, '366', 'Société Générale Brasil S.A.'),
(96, '637', 'Sofisa S.A.'),
(97, '12', 'Standard de Investimentos S.A.'),
(98, '464', 'Sumitomo Mitsui Brasileiro S.A.'),
(99, '082-5', 'Topázio S.A.'),
(100, 'M20', 'Toyota do Brasil S.A.'),
(101, '634', 'Triângulo S.A.'),
(102, 'M14', 'Volkswagen S.A.'),
(103, 'M23', 'Volvo (Brasil) S.A.'),
(104, '655', 'Votorantim S.A.'),
(105, '610', 'VR S.A.'),
(106, '119', 'Western Union do Brasil S.A.'),
(107, '370', 'WestLB do Brasil S.A.'),
(108, '0', 'Yamaha Motor S.A.'),
(109, '21', 'BANESTES S.A. Banco do Estado do Espírito Santo'),
(110, '719', 'Banif-Banco Internacional do Funchal (Brasil)S.A.'),
(111, '755', 'Bank of America Merrill Lynch Banco Múltiplo S.A.'),
(112, '73', 'BB Banco Popular do Brasil S.A.'),
(113, '250', 'BCV - Banco de Crédito e Varejo S.A.'),
(114, '78', 'BES Investimento do Brasil S.A.-Banco de Investimento'),
(115, '69', 'BPN Brasil Banco Múltiplo S.A.'),
(116, '125', 'Brasil Plural S.A. - Banco Múltiplo'),
(117, '70', 'BRB - Banco de Brasília S.A.'),
(118, '104', 'Caixa Econômica Federal'),
(119, '477', 'Citibank S.A.'),
(120, '081-7', 'Concórdia Banco S.A.'),
(121, '487', 'Deutsche Bank S.A. - Banco Alemão'),
(122, '64', 'Goldman Sachs do Brasil Banco Múltiplo S.A.'),
(123, '62', 'Hipercard Banco Múltiplo S.A.'),
(124, '399', 'HSBC Bank Brasil S.A. - Banco Múltiplo'),
(125, '492', 'ING Bank N.V.'),
(126, '652', 'Itaú Unibanco Holding S.A.'),
(127, '341', 'Itaú Unibanco S.A.'),
(128, '488', 'JPMorgan Chase Bank'),
(129, '751', 'Scotiabank Brasil S.A. Banco Múltiplo'),
(130, '0', 'Standard Chartered Bank (Brasil) S/A–Bco Invest.'),
(131, '409', 'UNIBANCO - União de Bancos Brasileiros S.A.'),
(132, '230', 'Unicard Banco Múltiplo S.A.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_contproj_despesas`
--

CREATE TABLE IF NOT EXISTS `j17_contproj_despesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rubricasdeprojetos_id` int(11) NOT NULL,
  `descricao` varchar(90) NOT NULL,
  `valor_despesa` double NOT NULL,
  `tipo_pessoa` varchar(11) NOT NULL,
  `data_emissao` date NOT NULL,
  `ident_nf` varchar(70) NOT NULL,
  `nf` varchar(20) NOT NULL,
  `ident_cheque` varchar(70) NOT NULL,
  `data_emissao_cheque` date NOT NULL,
  `valor_cheque` double NOT NULL,
  `favorecido` varchar(70) NOT NULL,
  `cnpj_cpf` int(14) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `descricao` (`descricao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=75 ;

--
-- Extraindo dados da tabela `j17_contproj_despesas`
--

INSERT INTO `j17_contproj_despesas` (`id`, `rubricasdeprojetos_id`, `descricao`, `valor_despesa`, `tipo_pessoa`, `data_emissao`, `ident_nf`, `nf`, `ident_cheque`, `data_emissao_cheque`, `valor_cheque`, `favorecido`, `cnpj_cpf`) VALUES
(66, 45, 'viagem de alunos', 2000, 'Física', '2013-04-15', '1454io', '12415lkl', 'yu67', '2013-04-22', 1123, 'João sem braço', 2147483647),
(67, 45, 'viagem de professores', 1547.65, 'Física', '2013-04-15', 'kj98', 'mn98', 'ioi890', '2013-04-15', 17872, 'Carlos da Silva', 124157845),
(68, 46, 'limpeza de carro', 1500, 'Física', '2013-04-15', 'ihjiouijhihji', 'joiu87', 'uijkj', '2013-04-15', 345678, 'Pedro Paulo', 85632487),
(69, 47, 'compra de maquinas pro lab1', 1500, 'Jurídica', '2013-04-22', '567890', 'ghj78', 'hj78', '2013-04-30', 74158, 'Marcio Mario da cunha', 2147483647),
(70, 43, 'Compra de Leptop pra aluno chico', 2700, 'Física', '2013-04-22', 'jkjk898', 'jkj898', 'jkjk909', '2013-04-15', 2700, 'Chico da Silva', 154787878),
(71, 42, 'Viagem do time de basquete', 2300, 'Física', '2013-04-15', 'jkjk7878', 'jkjuiu', 'jkjuiu989', '2013-04-23', 2300, 'Laerte Cunha', 2147483647),
(74, 54, 'Transferência de Saldo', 100, 'Jurídica', '2013-04-24', '', '', '', '0000-00-00', 0, '', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_contproj_projetos`
--

CREATE TABLE IF NOT EXISTS `j17_contproj_projetos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomeprojeto` varchar(70) NOT NULL,
  `orcamento` double NOT NULL DEFAULT '0',
  `saldo` double NOT NULL DEFAULT '0',
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `coordenador_id` int(11) NOT NULL,
  `agencia_id` int(11) NOT NULL,
  `banco_id` int(11) NOT NULL,
  `agencia` varchar(11) NOT NULL,
  `conta` varchar(11) NOT NULL,
  `edital` varchar(70) NOT NULL,
  `proposta` varchar(70) NOT NULL,
  `status` varchar(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomeprojeto` (`nomeprojeto`),
  UNIQUE KEY `nomeprojeto_2` (`nomeprojeto`),
  UNIQUE KEY `nomeprojeto_3` (`nomeprojeto`),
  UNIQUE KEY `nomeprojeto_4` (`nomeprojeto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

--
-- Extraindo dados da tabela `j17_contproj_projetos`
--

INSERT INTO `j17_contproj_projetos` (`id`, `nomeprojeto`, `orcamento`, `saldo`, `data_inicio`, `data_fim`, `coordenador_id`, `agencia_id`, `banco_id`, `agencia`, `conta`, `edital`, `proposta`, `status`) VALUES
(42, 'Recuperação de dados', 222323.4, 222323.4, '2013-04-08', '2013-10-10', 11, 2, 7, '388', '23478', '987', '345', 'Cadastrado'),
(43, 'Estudo da subida dos rios e suas consequencias', 367000, 367000, '2013-05-10', '2014-05-15', 4, 1, 41, '6745', '900235', '6789', '688', 'Cadastrado'),
(44, 'Alfabetização a distância', 678000, 678000, '2013-07-04', '2014-06-12', 17, 4, 48, '899', '567233', '1113', '456', 'Prorrogado'),
(45, 'A importância das novas tecnologias na educação', 12223.37, 12223.37, '2013-04-13', '2014-08-15', 11, 5, 21, '6775', '890456', '3455', '566', 'Cadastrado'),
(47, 'A Tecnologia da Informação na atualidade', 67754, 67754, '2013-08-01', '2014-08-07', 31, 4, 118, '867', '590003', '567', '341', 'Cadastrado'),
(48, 'Redes Sociais', 59000, 59000, '2013-11-20', '2014-09-16', 12, 6, 4, '78900', '08731205', '907', '258', 'Cadastrado'),
(49, 'Pesquisa e coleta de dados', 653290, 653290, '2013-06-11', '2014-11-06', 6, 5, 52, '09325', '0675322', '830', '6582', 'Cadastrado'),
(50, 'Captação de material reciclável dos igarapés de Manaus', 73245, 73245, '2013-04-03', '2013-12-20', 5, 1, 127, '385', '0956632', '683', '247', 'Ativo'),
(51, 'A internet como meio de comunicação e informação', 212123.46, 212123.46, '2013-04-24', '2014-07-03', 9, 6, 91, '855', '98650977', '344', '567', 'Encerrado'),
(52, 'Maraká', 62000, 0, '2012-11-01', '2014-11-01', 3, 1, 21, '1197-5', '22558-4', 'PPP', 'pdf', 'Ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_contproj_receitas`
--

CREATE TABLE IF NOT EXISTS `j17_contproj_receitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rubricasdeprojetos_id` int(11) NOT NULL,
  `descricao` varchar(90) NOT NULL,
  `valor_receita` double NOT NULL,
  `data` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Extraindo dados da tabela `j17_contproj_receitas`
--

INSERT INTO `j17_contproj_receitas` (`id`, `rubricasdeprojetos_id`, `descricao`, `valor_receita`, `data`) VALUES
(3, 53, 'Transferência de Saldo', 100, '2013-04-24'),
(6, 68, 'Parcela do Projeto', 2300, '2013-04-24'),
(7, 53, 'Limpeza', 1000, '2013-04-24'),
(8, 53, 'Serviço de limpeza 2', 10000, '2013-04-24'),
(9, 69, 'Parcela do Projeto', 10000, '2013-04-26'),
(10, 70, 'Parcela do Projeto', 15000, '2013-04-26'),
(11, 71, 'Parcela do Projeto', 27000, '2013-04-26'),
(12, 69, '7 estações de trabalho Apple', 10000, '2013-04-26');

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_contproj_registradatas`
--

CREATE TABLE IF NOT EXISTS `j17_contproj_registradatas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evento` varchar(70) NOT NULL,
  `data` date NOT NULL,
  `projeto_id` int(5) NOT NULL,
  `observacao` varchar(140) NOT NULL,
  `tipo` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `j17_contproj_registradatas`
--

INSERT INTO `j17_contproj_registradatas` (`id`, `evento`, `data`, `projeto_id`, `observacao`, `tipo`) VALUES
(1, 'Aviso ao cooordenador', '2013-04-10', 45, 'Tem que avisar ao cooordenador', 'Recado'),
(2, 'ver outra coisa', '2013-04-10', 45, 'Olha logo as coisas que tem que olhar!', 'Recado'),
(3, 'ligar pra loja', '2013-04-09', 45, 'liga logo peao', 'Telefone');

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_contproj_rubricas`
--

CREATE TABLE IF NOT EXISTS `j17_contproj_rubricas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(11) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `nome` (`nome`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Extraindo dados da tabela `j17_contproj_rubricas`
--

INSERT INTO `j17_contproj_rubricas` (`id`, `codigo`, `nome`, `tipo`) VALUES
(1, 'CpDesk', 'Computadores - Desktop', 'Custeio'),
(2, 'CpLep', 'Computadores - Leptop', 'Custeio'),
(3, 'VgEXT', 'Viagens exterior', 'Custeio'),
(4, 'VgNAC', 'Viagens Nacionais', 'Custeio'),
(5, 'BolsaExt', 'Bolsa Estudo Exterior', 'Capital'),
(6, 'BolsaNac', 'Bolsa Estudo Nacional', 'Capital'),
(7, 'ServLP', 'Serviço de limpeza', 'Capital'),
(8, 'VMR', 'Venda material reciclável', 'Capital');

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_contproj_rubricasdeprojetos`
--

CREATE TABLE IF NOT EXISTS `j17_contproj_rubricasdeprojetos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projeto_id` int(11) NOT NULL,
  `rubrica_id` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `valor_total` double NOT NULL,
  `valor_gasto` double NOT NULL,
  `valor_disponivel` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `descricao` (`descricao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=72 ;

--
-- Extraindo dados da tabela `j17_contproj_rubricasdeprojetos`
--

INSERT INTO `j17_contproj_rubricasdeprojetos` (`id`, `projeto_id`, `rubrica_id`, `descricao`, `valor_total`, `valor_gasto`, `valor_disponivel`) VALUES
(38, 42, 2, 'Compra de Leptop', 50000, 48000, 5487),
(39, 42, 1, 'Compra de computadores', 65000, 55000, 1544),
(40, 42, 3, 'Viagens para cursos', 26000, 17000, 1455),
(41, 42, 8, 'Garrafas pets', 45000, 39000, 4578),
(42, 51, 3, 'Viagem de alunos para seminários', 15324, 12654, 4587),
(43, 51, 2, 'Compra de material para estudos', 2345, 1567, 778),
(44, 51, 6, 'Bolsas para alunos', 6789, 6543, 246),
(45, 45, 3, 'Participação em feiras e seminários', 2000, 19582, 4578),
(46, 45, 7, 'Aquisição de material para manutenção', 5000, 2389, 4578),
(47, 45, 1, 'Aquisição de material de informática', 3000, 9502, 4587),
(48, 47, 6, 'Bolsas disponíveis para participantes do projeto', 45285, 41670, 5478),
(49, 47, 4, 'Seminários', 2578, 1945, 1457),
(50, 47, 2, 'Compra de 03 leptops', 4563, 3633, 145),
(51, 44, 8, 'Para captação de recursos', 89478, 73210, 457),
(52, 44, 3, 'Participação da Feira de Educação em Portugal', 732190, 53967, 7848),
(53, 50, 7, 'Material para desenvolvimento do projeto', 15789, 13692, 145),
(54, 50, 1, 'Aquisição de computadores para coleta de dados', 3789, 2675, 1378),
(55, 43, 4, 'Participação em Seminário na cidade de Belém-PA', 7595, 6955, 7845),
(56, 43, 1, 'Compra de 05 computadores', 12378, 1493, 1545),
(57, 49, 3, 'Feira de TI na cidade de Toronto - Canadá', 35622, 34786, 1541),
(58, 49, 6, 'Bolsa de Estudos na UFRJ', 32678, 31677, 1400),
(59, 48, 5, 'Bolsa de Estudos na UCLA - USA', 56987, 48933, 145),
(60, 48, 2, 'Aquisição de computadores', 65231, 54896, 1820),
(61, 43, 6, 'Bolsa de Estudos na Universidade Federal de Rondônia', 37899, 23445, 7845),
(62, 44, 1, 'Manutenção de computadores', 4578, 445.5, 7845),
(63, 47, 7, 'Limpeza de equipamentos', 455455, 45151, 457),
(68, 50, 3, 'Viagem para EUA', 5000, 0, 2300),
(69, 52, 1, '10 estações de trabalho DELL', 20000, 0, 10000),
(70, 52, 3, '5 passagens Manaus-EUA', 15000, 0, 15000),
(71, 52, 7, 'Limpar o LAB', 27000, 0, 27000);

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_contproj_transferenciassaldorubricas`
--

CREATE TABLE IF NOT EXISTS `j17_contproj_transferenciassaldorubricas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projeto_id` int(11) NOT NULL,
  `rubrica_origem` int(11) NOT NULL,
  `rubrica_destino` int(11) NOT NULL,
  `valor` double NOT NULL,
  `data` date NOT NULL,
  `autorizacao` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Extraindo dados da tabela `j17_contproj_transferenciassaldorubricas`
--

INSERT INTO `j17_contproj_transferenciassaldorubricas` (`id`, `projeto_id`, `rubrica_origem`, `rubrica_destino`, `valor`, `data`, `autorizacao`) VALUES
(1, 50, 53, 54, 1211, '2013-04-02', 'manel'),
(2, 50, 54, 53, 83983, '2013-04-30', 'pedro'),
(3, 0, 0, 0, 323, '2013-04-15', 'dawfwe'),
(4, 0, 0, 0, 323, '2013-04-15', 'dawfwe'),
(5, 0, 0, 0, 323, '2013-04-15', 'dawfwe'),
(6, 0, 0, 0, 323, '2013-04-15', 'dawfwe'),
(7, 0, 0, 0, 2434, '2013-04-16', 'drfehf'),
(8, 0, 0, 0, 2434, '2013-04-16', 'drfehf'),
(13, 50, 54, 53, 100, '2013-04-24', 'FAPEAM');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

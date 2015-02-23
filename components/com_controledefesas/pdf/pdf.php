<?php

require('./fpdf/fpdf.php');

class PDF extends FPDF{	
		function Header(){
			$this->Image('components/com_portalsecretaria/images/logo-brasil.jpg', 10, 7, 32.32);
			$this->Image('components/com_portalsecretaria/images/ufam.jpg', 175, 7, 25.25);
	
			//exibe o cabecalho do documento
			$this->SetFont("Helvetica",'B', 12);
			$this->MultiCell(0,5,"PODER EXECUTIVO",0, 'C');
			$this->SetFont("Helvetica",'B', 10);
			$this->MultiCell(0,5,utf8_decode("MINISTÉRIO DA EDUCAÇÃO"),0, 'C');
			$this->MultiCell(0,5,utf8_decode("INSTITUTO DE COMPUTAÇÃO"),0, 'C');
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,5,utf8_decode("PROGRAMA DE PÓS-GRADUAÇÃO EM INFORMÁTICA"),0, 'C');
			$this->SetDrawColor(0,0,0);
			$this->Line(10,42,200,42);
			$this->ln( 7 ); 			
		}

		function Footer(){
			$this->Line(10,285,200,285);
			$this->SetFont('Helvetica','I',8);
			$this->SetXY(10, 281);
			$this->MultiCell(0,5,"",0, 'C');
			$this->MultiCell(0,4,utf8_decode("Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur Virgílio Filho - CEP 69077-000 - Manaus, AM, Brasil"),0, 'C');
			$this->MultiCell(0,4,utf8_decode(" Tel. (092) 3305 1193         E-mail: secretariappgi@icomp.ufam.edu.br          www.ppgi.ufam.edu.br"),0, 'C');
			$this->Image('components/com_portalsecretaria/images/icon_telefone.jpg', '40', '290');
			$this->Image('components/com_portalsecretaria/images/icon_email.jpg', '73', '290');
			$this->Image('components/com_portalsecretaria/images/icon_casa.jpg', '134', '290');
		}
}
?>

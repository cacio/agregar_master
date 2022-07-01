<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class ProdutosAgregarDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	public function VerificaProdutoAgregar($cod){

		$dba = $this->dba;

		$vet = array();

		$sql ='SELECT     
				p.codigo,
				p.descricao,
				p.especie,
				p.tipo,
				p.aliquota,
				p.sexo,
				p.pauta,
				p.relatorioa
			FROM
			   produtos_agregar p
			   WHERE p.codigo = "'.$cod.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod    	= $dba->result($res,$i,'codigo');
			$desc   	= $dba->result($res,$i,'descricao');
			$espec  	= $dba->result($res,$i,'especie');
			$tipo   	= $dba->result($res,$i,'tipo');
			$aliquo 	= $dba->result($res,$i,'aliquota');
			$sexo  		= $dba->result($res,$i,'sexo');
			$pauta  	= $dba->result($res,$i,'pauta');
			$relatorioa = $dba->result($res,$i,'relatorioa');
				
			$prodagreg = new ProdutosAgregar();

			$prodagreg->setCodProd($cod);
			$prodagreg->setDescProd($desc);
			$prodagreg->setEspecie($espec);
			$prodagreg->setTipo($tipo);
			$prodagreg->setAliquota($aliquo);
			$prodagreg->setSexo($sexo);
			$prodagreg->setPauta($pauta);
			$prodagreg->setRelatorioA($relatorioa);
			
			$vet[$i] = $prodagreg;

		}

		return $vet;

	}


	public function BuscaProdutoAgregar($sarch){

		$dba = $this->dba;

		$vet = array();

		$sql ='SELECT     
				p.codigo,
				p.descricao,
				p.especie,
				p.tipo,
				p.aliquota,
				p.sexo,
				p.pauta,
				p.relatorioa
			FROM
			   produtos_agregar p
			   WHERE (p.descricao like "%'.$sarch.'%" or 
			   		  p.codigo like "%'.$sarch.'%") ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod    	= $dba->result($res,$i,'codigo');
			$desc   	= $dba->result($res,$i,'descricao');
			$espec  	= $dba->result($res,$i,'especie');
			$tipo   	= $dba->result($res,$i,'tipo');
			$aliquo 	= $dba->result($res,$i,'aliquota');
			$sexo  		= $dba->result($res,$i,'sexo');
			$pauta  	= $dba->result($res,$i,'pauta');
			$relatorioa = $dba->result($res,$i,'relatorioa');
				
			$prodagreg = new ProdutosAgregar();

			$prodagreg->setCodProd($cod);
			$prodagreg->setDescProd($desc);
			$prodagreg->setEspecie($espec);
			$prodagreg->setTipo($tipo);
			$prodagreg->setAliquota($aliquo);
			$prodagreg->setSexo($sexo);
			$prodagreg->setPauta($pauta);
			$prodagreg->setRelatorioA($relatorioa);
			
			$vet[$i] = $prodagreg;

		}

		return $vet;

	}
	

	public function ListaProdutoAgregar(){

		$dba = $this->dba;

		$vet = array();

		$sql ='SELECT     
				p.codigo,
				p.descricao,
				p.especie,
				p.tipo,
				p.aliquota,
				p.sexo,
				p.pauta,
				p.relatorioa
			FROM
			   produtos_agregar p';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod    	= $dba->result($res,$i,'codigo');
			$desc   	= $dba->result($res,$i,'descricao');
			$espec  	= $dba->result($res,$i,'especie');
			$tipo   	= $dba->result($res,$i,'tipo');
			$aliquo 	= $dba->result($res,$i,'aliquota');
			$sexo  		= $dba->result($res,$i,'sexo');
			$pauta  	= $dba->result($res,$i,'pauta');
			$relatorioa = $dba->result($res,$i,'relatorioa');
				
			$prodagreg = new ProdutosAgregar();

			$prodagreg->setCodProd($cod);
			$prodagreg->setDescProd($desc);
			$prodagreg->setEspecie($espec);
			$prodagreg->setTipo($tipo);
			$prodagreg->setAliquota($aliquo);
			$prodagreg->setSexo($sexo);
			$prodagreg->setPauta($pauta);
			$prodagreg->setRelatorioA($relatorioa);
			
			$vet[$i] = $prodagreg;

		}

		return $vet;

	}
	

	
}

?>
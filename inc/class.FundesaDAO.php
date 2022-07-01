<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class FundesaDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	public function ListaFundesaEmpresas($cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					f.id,
					f.COMPETENC,
					f.NUMERO,
					f.VALORPAGO,
					f.DATAPAGO,
					f.cnpj_emp
				FROM
					fundesa f 
				WHERE f.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod  		= $dba->result($res,$i,'id');
			$competenc  = $dba->result($res,$i,'COMPETENC');
			$numero     = $dba->result($res,$i,'NUMERO');
			$valorpago  = $dba->result($res,$i,'VALORPAGO');
			$datapago   = $dba->result($res,$i,'DATAPAGO');
			$cnpj_emp   = $dba->result($res,$i,'cnpj_emp');
			
			$fun = new Fundesa();

			$fun->setCodigo($cod);
			$fun->setCompetenc($competenc);
			$fun->setNumero($numero);
			$fun->setValorPago($valorpago);
			$fun->setDataPago($datapago);
			$fun->setCnpjEmp($cnpj_emp);
			
			
			$vet[$i] = $fun;

		}

		return $vet;

	}

	public function ListaFundesaEmpresasUm($cnpj,$id){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					f.id,
					f.COMPETENC,
					f.NUMERO,
					f.VALORPAGO,
					f.DATAPAGO,
					f.cnpj_emp
				FROM
					fundesa f 
				WHERE f.cnpj_emp = "'.$cnpj.'" and f.id = "'.$id.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod  		= $dba->result($res,$i,'id');
			$competenc  = $dba->result($res,$i,'COMPETENC');
			$numero     = $dba->result($res,$i,'NUMERO');
			$valorpago  = $dba->result($res,$i,'VALORPAGO');
			$datapago   = $dba->result($res,$i,'DATAPAGO');
			$cnpj_emp   = $dba->result($res,$i,'cnpj_emp');
			
			$fun = new Fundesa();

			$fun->setCodigo($cod);
			$fun->setCompetenc($competenc);
			$fun->setNumero($numero);
			$fun->setValorPago($valorpago);
			$fun->setDataPago($datapago);
			$fun->setCnpjEmp($cnpj_emp);
			
			
			$vet[$i] = $fun;

		}

		return $vet;

	}

	public function ValidaFundesa($cnpj,$dt){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					f.id,
					f.COMPETENC,
					f.NUMERO,
					f.VALORPAGO,
					f.DATAPAGO,
					f.cnpj_emp
				FROM
					fundesa f 
				WHERE f.cnpj_emp = "'.$cnpj.'" and f.COMPETENC = "'.$dt.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod  		= $dba->result($res,$i,'id');
			$competenc  = $dba->result($res,$i,'COMPETENC');
			$numero     = $dba->result($res,$i,'NUMERO');
			$valorpago  = $dba->result($res,$i,'VALORPAGO');
			$datapago   = $dba->result($res,$i,'DATAPAGO');
			$cnpj_emp   = $dba->result($res,$i,'cnpj_emp');
			
			$fun = new Fundesa();

			$fun->setCodigo($cod);
			$fun->setCompetenc($competenc);
			$fun->setNumero($numero);
			$fun->setValorPago($valorpago);
			$fun->setDataPago($datapago);
			$fun->setCnpjEmp($cnpj_emp);
			
			
			$vet[$i] = $fun;

		}

		return $vet;

	}

	public function inserir($fun){

		$dba = $this->dba;		
		
		$competenc = $fun->getCompetenc();
		$numero	   = $fun->getNumero();
		$valorpago = $fun->getValorPago();
		$datapag   = $fun->getDataPago();
		$cnpjemp   = $fun->getCnpjEmp();
		
		$sql = 'INSERT INTO `fundesa`
				(`COMPETENC`,
				`NUMERO`,
				`VALORPAGO`,
				`DATAPAGO`,
				`cnpj_emp`)
				VALUES
				("'.$competenc.'",
				 "'.$numero.'",
				 '.$valorpago.',
				 "'.$datapag.'",
				 "'.$cnpjemp.'")';

		$dba->query($sql);	
							
	}
	
	public function update($fun){

		$dba = $this->dba;		
		
		$id		   = $fun->getCodigo();
		$competenc = $fun->getCompetenc();
		$numero	   = $fun->getNumero();
		$valorpago = $fun->getValorPago();
		$datapag   = $fun->getDataPago();
		$cnpjemp   = $fun->getCnpjEmp();
		
		$sql = 'UPDATE `fundesa`
				SET
				`COMPETENC` = "'.$competenc.'",
				`NUMERO` = "'.$numero.'",
				`VALORPAGO` = '.$valorpago.',
				`DATAPAGO` = "'.$datapag.'",
				`cnpj_emp` = "'.$cnpjemp.'"
				WHERE `id` = '.$id.' ';

		$dba->query($sql);	
							
	}
	

	public function deletar($fun){
	
		$dba = $this->dba;

		$id  = $fun->getCodigo();
		
		$sql = 'DELETE FROM `fundesa`
				WHERE id = '.$id;

		$dba->query($sql);	
				
	}		
}

?>
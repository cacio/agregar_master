<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class FundoviDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	public function ListaFundoviEmpresas($cnpj){

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
					fundovi f 
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
			
			$fund = new Fundovi();

			$fund->setCodigo($cod);
			$fund->setCompetenc($competenc);
			$fund->setNumero($numero);
			$fund->setValorPago($valorpago);
			$fund->setDataPago($datapago);
			$fund->setCnpjEmp($cnpj_emp);
			
			
			$vet[$i] = $fund;

		}

		return $vet;

	}

	public function ListaFundoviEmpresasUm($cnpj,$id){

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
					fundovi f 
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
			
			$fund = new Fundovi();

			$fund->setCodigo($cod);
			$fund->setCompetenc($competenc);
			$fund->setNumero($numero);
			$fund->setValorPago($valorpago);
			$fund->setDataPago($datapago);
			$fund->setCnpjEmp($cnpj_emp);
			
			
			$vet[$i] = $fund;

		}

		return $vet;

	}

	public function ValidaFundovi($cnpj,$comp){

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
					fundovi f 
				WHERE f.cnpj_emp = "'.$cnpj.'" and f.COMPETENC = "'.$comp.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod  		= $dba->result($res,$i,'id');
			$competenc  = $dba->result($res,$i,'COMPETENC');
			$numero     = $dba->result($res,$i,'NUMERO');
			$valorpago  = $dba->result($res,$i,'VALORPAGO');
			$datapago   = $dba->result($res,$i,'DATAPAGO');
			$cnpj_emp   = $dba->result($res,$i,'cnpj_emp');
			
			$fund = new Fundovi();

			$fund->setCodigo($cod);
			$fund->setCompetenc($competenc);
			$fund->setNumero($numero);
			$fund->setValorPago($valorpago);
			$fund->setDataPago($datapago);
			$fund->setCnpjEmp($cnpj_emp);
			
			
			$vet[$i] = $fund;

		}

		return $vet;

	}

	public function inserir($fund){

		$dba = $this->dba;		
		
		$competenc = $fund->getCompetenc();
		$numero	   = $fund->getNumero();
		$valorpago = $fund->getValorPago();
		$datapag   = $fund->getDataPago();
		$cnpjemp   = $fund->getCnpjEmp();
		
		$sql = 'INSERT INTO `fundovi`
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
	
	public function update($fund){

		$dba = $this->dba;		
		
		$id		   = $fund->getCodigo();
		$competenc = $fund->getCompetenc();
		$numero	   = $fund->getNumero();
		$valorpago = $fund->getValorPago();
		$datapag   = $fund->getDataPago();
		$cnpjemp   = $fund->getCnpjEmp();
		
		$sql = 'UPDATE `fundovi`
				SET
				`COMPETENC` = "'.$competenc.'",
				`NUMERO` = "'.$numero.'",
				`VALORPAGO` = '.$valorpago.',
				`DATAPAGO` = "'.$datapag.'",
				`cnpj_emp` = "'.$cnpjemp.'"
				WHERE `id` = '.$id.' ';

		$dba->query($sql);	
							
	}
	

	public function deletar($fund){
	
		$dba = $this->dba;

		$id  = $fund->getCodigo();
		
		$sql = 'DELETE FROM `fundovi`
				WHERE id = '.$id;

		$dba->query($sql);	
				
	}		
}

?>
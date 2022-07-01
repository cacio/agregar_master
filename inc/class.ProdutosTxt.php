<?php

class ProdutosTxt{
		
	private $Codigo;
	private $cod_prod;
    private $desc_prod;
    private $cnpj_emp;
	private $codsecret;
	private $descsecret;
	private $pkid;
	private $id;	
	
	public function __construct(){
		//nada
	}
	
	public function getId(){
		return $this->id;	
	}

	public function setId($id){
		$this->id = $id;
	}
	
	public function getCodigo(){
		return $this->Codigo;	
	}

	public function setCodigo($codigo){
		$this->Codigo = $codigo;
	}

	public function getCodProd(){
		return $this->cod_prod;	
	}

	public function setCodProd($codprod){
		$this->cod_prod = $codprod;
	}
		
	public function getDescProd(){
		return $this->desc_prod;	
	}

	public function setDescProd($descprod){
		$this->desc_prod = $descprod;
	}
	
	
	public function getCnpjEmp(){
		return $this->cnpj_emp;	
	}

	public function setCnpjEmp($cnpjemp){
		$this->cnpj_emp = $cnpjemp;
	}

	public function getCodSecretaria(){
		return $this->codsecret;	
	}

	public function setCodSecretaria($codsecret){
		$this->codsecret = $codsecret;
	}
	
	public function getDescSecretaria(){
		return $this->descsecret;	
	}

	public function setDescSecretaria($descsecret){
		$this->descsecret = $descsecret;
	}

	public function getPkId(){
		return $this->pkid;	
	}

	public function setPkId($pkid){
		$this->pkid = $pkid;
	}
}

?>
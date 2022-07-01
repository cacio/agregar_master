<?php

class ProdFrigTxt{
		

	private $Codigo;
	private $cod_prod;
    private $desc_prod;
    private $cod_secretaria;
    private $cnpj_emp;
	private $tipo;
	private $proxid;
	private $id;
	private $descprodsecretaria;

	public function __construct(){

		//nada

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
	
	public function getCodSecretaria(){
		return $this->cod_secretaria;	
	}

	public function setCodSecretaria($codsecre){
		$this->cod_secretaria = $codsecre;
	}		
	
	public function getCnpjEmp(){
		return $this->cnpj_emp;	
	}

	public function setCnpjEmp($cnpjemp){
		$this->cnpj_emp = $cnpjemp;
	}
	
	public function getTipo(){
		return $this->tipo;	
	}

	public function setTipo($tipo){
		$this->tipo = $tipo;
	}
	
	public function getProximoId(){
		return $this->proxid;	
	}

	public function setProximoId($proxid){
		$this->proxid = $proxid;
	}

	public function getId(){
		return $this->id;	
	}

	public function setId($id){
		$this->id = $id;
	}
	
	public function getDesProdSecretaria(){
		return $this->descprodsecretaria;	
	}

	public function setDesProdSecretaria($descprodsecre){
		$this->descprodsecretaria = $descprodsecre;
	}
}

?>
<?php

class RelacionaProduto{
		

	private $Codigo;
	private $id_rel;
	private $id_prod;
	private $n_nota;	
    private $cnpj_emp;
	private $descprod;
	private $proxid;
	
	public function __construct(){

		//nada

	}
		
	public function getCodigo(){
		return $this->Codigo;	
	}

	public function setCodigo($codigo){
		$this->Codigo = $codigo;
	}
	
	
	public function getIdRelacionado(){
		return $this->id_rel;	
	}

	public function setIdRelacionado($idrel){
		$this->id_rel = $idrel;
	}
	
	
	public function getIdProduto(){
		return $this->id_prod;	
	}

	public function setIdProduto($idprod){
		$this->id_prod = $idprod;
	}
	
	public function getNumeroNota(){
		return $this->n_nota;	
	}

	public function setNumeroNota($nnota){
		$this->n_nota = $nnota;
	}
	
	public function getDescProd(){
		return $this->descprod;	
	}

	public function setDescProd($desc){
		$this->descprod = $desc;
	}
	
	public function getCnpjEmp(){
		return $this->cnpj_emp;	
	}

	public function setCnpjEmp($cnpjemp){
		$this->cnpj_emp = $cnpjemp;
	}
	
	public function getProximoId(){
		return $this->proxid;	
	}

	public function setProximoId($proxid){
		$this->proxid = $proxid;
	}
	
}

?>
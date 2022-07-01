<?php

class CfopEmpresa{
		

	private $Codigo;
	private $id_cfop;
	private $gera_agregar;
    private $cnpj_emp;	
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
	
	
	public function getIdCfop(){
		return $this->id_cfop;	
	}

	public function setIdCfop($idcfop){
		$this->id_cfop = $idcfop;
	}
	
	
	public function getGeraAgregar(){
		return $this->gera_agregar;	
	}

	public function setGeraAgregar($geragrega){
		$this->gera_agregar = $geragrega;
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
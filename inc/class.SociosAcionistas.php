<?php
class SociosAcionistas{

	private $Codigo;
	private $nome;
	private $cpf;
	private $part_cap_social;
	private $id_emp;
	private $idprox;
	
	public function __construct(){
		//nada
	}

	public function getCodigo(){
		return $this->Codigo;	
	}
	public function setCodigo($codigo){
		$this->Codigo = $codigo;
	}
	
	
	public function getNome(){
		return $this->nome;	
	}
	public function setNome($nome){
		$this->nome = $nome;
	}
	
	public function getCpf(){
		return $this->cpf;	
	}
	public function setCpf($cpf){
		$this->cpf = $cpf;
	}
	
	public function getPartCapSocial(){
		return $this->part_cap_social;	
	}
	public function setPartCapSocial($partcapsoc){
		$this->part_cap_social = $partcapsoc;
	}
	
	public function getIdEmpresa(){
		return $this->id_emp;	
	}
	public function setIdEmpresa($idemp){
		$this->id_emp = $idemp;
	}
	
	public function getIdProx(){
		return $this->idprox;	
	}
	public function setIdProx($idprox){
		$this->idprox = $idprox;
	}
	
}
?>
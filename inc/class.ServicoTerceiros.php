<?php
class ServicoTerceiros{

	private $Codigo;
	private $razao;
	private $cgc;
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
	
	
	public function getRazaoSocial(){
		return $this->razao;	
	}
	public function setRazaoSocial($razao){
		$this->razao = $razao;
	}
	
	public function getCgc(){
		return $this->cgc;	
	}
	public function setCgc($cgc){
		$this->cgc = $cgc;
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
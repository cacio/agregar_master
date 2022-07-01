<?php
class InspecaoEstabelecimento{

	private $Codigo;	
	private $tipo;
	private $n_registro;
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
		
	public function getTipo(){
		return $this->tipo;	
	}
	public function setTipo($tipo){
		$this->tipo = $tipo;
	}
	
	public function getNregistro(){
		return $this->n_registro;	
	}
	public function setNregistro($nreg){
		$this->n_registro = $nreg;
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
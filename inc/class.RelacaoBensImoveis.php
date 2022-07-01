<?php
class RelacaoBensImoveis{

	private $Codigo;
	private $descricao;
	private $endereco;
	private $matricula;
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
	
	
	public function getDescricao(){
		return $this->descricao;	
	}
	public function setDescricao($desc){
		$this->descricao = $desc;
	}
	
	public function getEndereco(){
		return $this->endereco;	
	}
	public function setEndereco($ende){
		$this->endereco = $ende;
	}
	
	public function getMatricula(){
		return $this->matricula;	
	}
	public function setMatricula($matri){
		$this->matricula = $matri;
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
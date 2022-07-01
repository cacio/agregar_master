<?php
class EnderecoEscritorio{

	private $Codigo;	
	private $endereco;
	private $municipio;
	private $cep;
	private $fone;
	private $email;
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
		
	public function getEndereco(){
		return $this->endereco;	
	}
	public function setEndereco($ende){
		$this->endereco = $ende;
	}
	
	public function getMunicipio(){
		return $this->municipio;	
	}
	public function setMunicipio($mun){
		$this->municipio = $mun;
	}
	
	public function getCep(){
		return $this->cep;	
	}
	public function setCep($cep){
		$this->cep = $cep;
	}
	
	public function getFone(){
		return $this->fone;	
	}
	public function setFone($fone){
		$this->fone = $fone;
	}
	
	public function getEmail(){
		return $this->email;	
	}
	public function setEmail($email){
		$this->email = $email;
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
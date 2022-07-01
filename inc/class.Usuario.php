<?php

class Usuario{
		

	private $Codigo;
	private $Nome;
	private $email;
	private $login;
	private $senha;
	private $idrota;
	private $idsys;
	private $proximoid;	
	private $id_emp;
	private $cnpj;
	private $insc_emp;

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
		return $this->Nome;
	}

	public function setNome($nome){
		$this->Nome = $nome;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getLogin(){
		return $this->login;
	}	

	public function setLogin($login){
		$this->login = $login;
	}

	public function getSenha(){
		return $this->senha;
	}
	
	public function setSenha($senha){
		$this->senha = $senha;
	}

	public function getIdRota(){
		return $this->idrota;
	}

	public function setIdRota($idrota){
		$this->idrota = $idrota;
	}

	public function getIdsys(){
		return $this->idsys;
	}

	public function setIdsys($idsys){
		$this->idsys = $idsys;
	}

	public function getProxid(){
		return $this->proximoid;
	}

	public function setProxid($proximoid){
		$this->proximoid = $proximoid;
	}
	
	public function getIdEmpresa(){
		return $this->id_emp;
	}

	public function setIdEmpresa($idemp){
		$this->id_emp = $idemp;
	}
	
	public function getCnpj(){
		return $this->cnpj;
	}

	public function setCnpj($cnpj){
		$this->cnpj = $cnpj;
	}
	
	public function getInscEmp(){
		return $this->insc_emp;
	}

	public function setInscEmp($inscemp){
		$this->insc_emp = $inscemp;
	}
}

?>
<?php

class EmpresasTxt{
		

	private $Codigo;
	private $cnpj_cpf;
    private $insc_estadual;
    private $razao;
    private $cidade;
    private $uf;
    private $tipo;
    private $cnpj_emp;
	
	public function __construct(){

		//nada

	}
		
	public function getCodigo(){
		return $this->Codigo;	
	}

	public function setCodigo($codigo){
		$this->Codigo = $codigo;
	}

	public function getCnpjCpf(){
		return $this->cnpj_cpf;	
	}

	public function setCnpjCpf($cnpjcpf){
		$this->cnpj_cpf = $cnpjcpf;
	}
		
	public function getInscEstadual(){
		return $this->insc_estadual;	
	}

	public function setInscEstadual($inscest){
		$this->insc_estadual = $inscest;
	}
	
	public function getRazao(){
		return $this->razao;	
	}

	public function setRazao($razao){
		$this->razao = $razao;
	}
	
	public function getCidade(){
		return $this->cidade;	
	}

	public function setCidade($cidade){
		$this->cidade = $cidade;
	}
	
	public function getUf(){
		return $this->uf;	
	}

	public function setUf($uf){
		$this->uf = $uf;
	}
	
	public function getTipo(){
		return $this->tipo;	
	}

	public function setTipo($tipo){
		$this->tipo = $tipo;
	}
	
	public function getCnpjEmp(){
		return $this->cnpj_emp;	
	}

	public function setCnpjEmp($cnpjemp){
		$this->cnpj_emp = $cnpjemp;
	}
	
}

?>
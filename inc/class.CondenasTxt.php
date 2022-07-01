<?php

class CondenasTxt{
		

	private $Codigo;
	private $numero_nota;
	private $data_emissao;
	private $cnpj_cpf;
	private $codigo_condena;
	private $qtd_condena;
	private $insc_estadual;
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

	public function getNumeroNota(){
		return $this->numero_nota;	
	}

	public function setNumeroNota($numeronota){
		$this->numero_nota = $numeronota;
	}
		
	public function getDataEmissao(){
		return $this->data_emissao;	
	}

	public function setDataEmissao($emissao){
		$this->data_emissao = $emissao;
	}
	
	public function getCnpjCpf(){
		return $this->cnpj_cpf;	
	}

	public function setCnpjCpf($cnpjcpf){
		$this->cnpj_cpf = $cnpjcpf;
	}		
	
	public function getCodigoCondena(){
		return $this->codigo_condena;	
	}

	public function setCodigoCondena($codcondena){
		$this->codigo_condena = $codcondena;
	}
			
	public function getQtdCondena(){
		return $this->qtd_condena;	
	}

	public function setQtdCondena($qtdcondena){
		$this->qtd_condena = $qtdcondena;
	}
	
	public function getInscEstadual(){
		return $this->insc_estadual;	
	}

	public function setInscEstadual($inscestadual){
		$this->insc_estadual = $inscestadual;
	}
	
	public function getCnpjEmp(){
		return $this->cnpj_emp;	
	}

	public function setCnpjEmp($cnpjemp){
		$this->cnpj_emp = $cnpjemp;
	}
	
}

?>
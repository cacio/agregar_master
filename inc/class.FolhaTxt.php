<?php

class FolhaTxt{
		

	private $Codigo;
	private $data_pag;
    private $num_funcionarios;
    private $valor_folha;
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

	public function getDataPag(){
		return $this->data_pag;	
	}

	public function setDataPag($datapag){
		$this->data_pag = $datapag;
	}
		
	public function getNumFuncionario(){
		return $this->num_funcionarios;	
	}

	public function setNumFuncionario($numfuncionario){
		$this->num_funcionarios = $numfuncionario;
	}
	
	public function getValorFolha(){
		return $this->valor_folha;	
	}

	public function setValorFolha($vlfolha){
		$this->valor_folha = $vlfolha;
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
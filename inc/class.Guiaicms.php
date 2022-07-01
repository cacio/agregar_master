<?php

class Guiaicms{
		

	private $Codigo;
	private $COMPETENC;
    private $NUMERO;
    private $VALORPAGO;
	private $DATAPAGO;
    private $cnpj_emp;
	private $codigoguia;
	private $tipo;
	private $id;

	public function __construct(){

		//nada

	}
		
	public function getCodigo(){
		return $this->Codigo;	
	}

	public function setCodigo($codigo){
		$this->Codigo = $codigo;
	}
	
	public function getCompetenc(){
		return $this->COMPETENC;	
	}

	public function setCompetenc($competenc){
		$this->COMPETENC = $competenc;
	}
	
	public function getNumero(){
		return $this->NUMERO;	
	}

	public function setNumero($numero){
		$this->NUMERO = $numero;
	}
	
	public function getValorPago(){
		return $this->VALORPAGO;	
	}

	public function setValorPago($valorpago){
		$this->VALORPAGO = $valorpago;
	}
	
	public function getDataPago(){
		return $this->DATAPAGO;	
	}

	public function setDataPago($datapago){
		$this->DATAPAGO = $datapago;
	}
	
	public function getCodigoGuia(){
		return $this->codigoguia;	
	}

	public function setCodigoGuia($codigoguia){
		$this->codigoguia = $codigoguia;
	}
	
	public function getCnpjEmp(){
		return $this->cnpj_emp;	
	}

	public function setCnpjEmp($cnpjemp){
		$this->cnpj_emp = $cnpjemp;
	}
	
	public function getTipo(){
		return $this->tipo;	
	}

	public function setTipo($tipo){
		$this->tipo = $tipo;
	}

	public function getId(){
		return $this->id;	
	}

	public function setId($id){
		$this->id = $id;
	}
}

?>
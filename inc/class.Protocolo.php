<?php
class Protocolo{

	private $Codigo;
	private $competencia;
	private $selected;
	private $protocolo;
	private $cripty;
	private $status;
	private $cnpj_emp;
	private $numcomp;
	private $tipoarq;

	public function __construct(){
		//nada
	}

	public function getCodigo(){
		return $this->Codigo;	
	}
	public function setCodigo($codigo){
		$this->Codigo = $codigo;
	}
	
	public function getCompetencia(){
		return $this->competencia;	
	}
	public function setCompetencia($competencia){
		$this->competencia = $competencia;
	}
	
	public function getProtocolo(){
		return $this->protocolo;	
	}
	public function setProtocolo($protocolo){
		$this->protocolo = $protocolo;
	}

	public function getCripty(){
		return $this->cripty;	
	}
	public function setCripty($cripty){
		$this->cripty = $cripty;
	}

	public function getStatus(){
		return $this->status;	
	}
	public function setStatus($status){
		$this->status = $status;
	}

	public function getCnpjEmp(){
		return $this->cnpj_emp;	
	}
	public function setCnpjEmp($cnpjemp){
		$this->cnpj_emp = $cnpjemp;
	}

	public function getSelecionado(){
		return $this->selected;	
	}
	public function setSelecionado($selec){
		$this->selected = $selec;
	}

	public function getNumeroComp(){
		return $this->numcomp;	
	}
	public function setNumeroComp($numcomp){
		$this->numcomp = $numcomp;
	}

	public function getTipoArq(){
		return $this->tipoarq;	
	}
	public function setTipoArq($tparq){
		$this->tipoarq = $tparq;
	}
}
?>
<?php
class DetalheDocDigitalizadoEmpresa{

	private $Codigo;
	private $documento;
	private $id_docdigitalizado;
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
		
	public function getDocumento(){
		return $this->documento;	
	}
	public function setDocumento($documento){
		$this->documento = $documento;
	}

	public function getIdocdig(){
		return $this->id_docdigitalizado;	
	}
	public function setIdocdig($iddocdig){
		$this->id_docdigitalizado = $iddocdig;
	}
	
	public function getIdProx(){
		return $this->idprox;	
	}
	public function setIdProx($idprox){
		$this->idprox = $idprox;
	}
}
?>
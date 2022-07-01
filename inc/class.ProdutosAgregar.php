<?php

class ProdutosAgregar{
		
	private $Codigo;
	private $cod_prod;
    private $desc_prod;
    private $especie;
    private $tipo;
	private $aliquota;
	private $sexo;
	private $pauta;
	private $relatorioa;
	
	public function __construct(){
		//nada
	}
		
	public function getCodigo(){
		return $this->Codigo;	
	}

	public function setCodigo($codigo){
		$this->Codigo = $codigo;
	}

	public function getCodProd(){
		return $this->cod_prod;	
	}

	public function setCodProd($codprod){
		$this->cod_prod = $codprod;
	}
		
	public function getDescProd(){
		return $this->desc_prod;	
	}

	public function setDescProd($descprod){
		$this->desc_prod = $descprod;
	}
	
	public function getEspecie(){
		return $this->especie;	
	}

	public function setEspecie($especie){
		$this->especie = $especie;
	}		
	
	public function getTipo(){
		return $this->tipo;	
	}

	public function setTipo($tipo){
		$this->tipo = $tipo;
	}
	
	public function getAliquota(){
		return $this->aliquota;	
	}

	public function setAliquota($aliquota){
		$this->aliquota = $aliquota;
	}
	
	public function getSexo(){
		return $this->sexo;	
	}

	public function setSexo($sexo){
		$this->sexo = $sexo;
	}
	
	public function getPauta(){
		return $this->pauta;	
	}

	public function setPauta($pauta){
		$this->pauta = $pauta;
	}
	
	public function getRelatorioA(){
		return $this->relatorioa;	
	}

	public function setRelatorioA($relatorioa){
		$this->relatorioa = $relatorioa;
	}
	
}

?>
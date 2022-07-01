<?php
class Pais{
	
	private $codigo;
	private $nome;
	private $sigla;
	private $pais_codigo;
	private $selected;
	
	public function __construct(){
		//nada
	}
	
	public function getCodigo(){
		return $this->codigo;
	}
	
	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}
	
	public function getNome(){
		return $this->nome;
	}
	
	public function setNome($nome){
		$this->nome = $nome;
	}
	
	public function getSigla(){
		return $this->sigla;
	}
	
	public function setSigla($sigla){
		$this->sigla = $sigla;
	}
	
	public function getCodigoPais(){
		return $this->pais_codigo;
	}
	
	public function setCodigoPais($codpais){
		$this->pais_codigo = $codpais;
	}
	
	public function getSelected(){
		return $this->selected;
	}
	
	public function setSelected($sel){
		$this->selected = $sel;
	}
	
}
?>
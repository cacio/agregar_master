<?php
class ModalidadeEnv{

	private $Codigo;
	private $nome;
	private $selected;
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
		return $this->nome;	
	}
	public function setNome($nome){
		$this->nome = $nome;
	}
	
	public function getSelecionado(){
		return $this->selected;	
	}
	public function setSelecionado($selec){
		$this->selected = $selec;
	}
}
?>
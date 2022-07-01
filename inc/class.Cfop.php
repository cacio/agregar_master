<?php
class Cfop{
	
	private $id;
	private $codigo;
	private $nome;
	private $dadosadfisc;
	private $gera;
	private $vinculado;
	private $idvinculado;
	private $devolucao;
	
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
	
	public function getId(){
		return $this->id;
	}
	public function setId($id){
		$this->id = $id;
	}
	
	public function getDadosadfisco(){
		return $this->dadosadfisc;
	}
	public function setDadosadfisco($Dadosadfisco){
		$this->dadosadfisc = $Dadosadfisco;
	}
	
	public function getGeraAgregar(){
		return $this->gera;
	}
	public function setGeraAgregar($gera){
		$this->gera = $gera;
	}
	
	public function getVinculado(){
		return $this->vinculado;
	}
	public function setVinculado($vinc){
		$this->vinculado = $vinc;
	}
	
	public function getIdVinculado(){
		return $this->idvinculado;
	}
	public function setIdVinculado($idvinc){
		$this->idvinculado = $idvinc;
	}

	public function getDevolucao(){
		return $this->devolucao;
	}
	public function setDevolucao($devolucao){
		$this->devolucao = $devolucao;
	}
}
?>
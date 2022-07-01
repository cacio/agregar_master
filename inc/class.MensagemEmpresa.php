<?php
class MensagemEmpresa{

	private $Codigo;
	private $titulo;
	private $mensagem;
	private $idmodalidade;
	private $idempresa;
	private $iddocumento;
	private $data;
	private $selected;
	private $timestamp;
	private $now;
	private $nomeemp;
	private $lida;
	private $proxid;
	private $numeros_nao_lida;

	public function __construct(){
		//nada
	}

	public function getCodigo(){
		return $this->Codigo;	
	}
	public function setCodigo($codigo){
		$this->Codigo = $codigo;
	}
	
	
	public function getTitulo(){
		return $this->titulo;	
	}
	public function setTitulo($titulo){
		$this->titulo = $titulo;
	}
	
	public function getMensagem(){
		return $this->mensagem;	
	}
	public function setMensagem($mensagem){
		$this->mensagem = $mensagem;
	}
	
	public function getIdModalidade(){
		return $this->idmodalidade;	
	}
	public function setIdModalidade($idmodalidade){
		$this->idmodalidade = $idmodalidade;
	}
	
	public function getIdEmpresa(){
		return $this->idempresa;	
	}
	public function setIdEmpresa($idempresa){
		$this->idempresa = $idempresa;
	}
	
	public function getIdDocumento(){
		return $this->iddocumento;	
	}
	public function setIdDocumento($iddoc){
		$this->iddocumento = $iddoc;
	}
	
	public function getData(){
		return $this->data;	
	}
	public function setData($data){
		$this->data = $data;
	}
		
	public function getSelecionado(){
		return $this->selected;	
	}
	public function setSelecionado($selec){
		$this->selected = $selec;
	}
	
	public function getTimesTamp(){
		return $this->timestamp;	
	}
	public function setTimesTamp($timesp){
		$this->timestamp = $timesp;
	}
	
	public function getNow(){
		return $this->now;	
	}
	public function setNow($now){
		$this->now = $now;
	}
	
	public function getNomeEmp(){
		return $this->nomeemp;	
	}
	public function setNomeEmp($nomeemp){
		$this->nomeemp = $nomeemp;
	}

	public function getLida(){
		return $this->lida;	
	}
	public function setLida($lida){
		$this->lida = $lida;
	}

	public function getProximoId(){
		return $this->proxid;	
	}
	public function setProximoId($proxid){
		$this->proxid = $proxid;
	}

	public function getNumNaoLidas(){
		return $this->numeros_nao_lida;	
	}
	public function setNumNaoLidas($numlida){
		$this->numeros_nao_lida = $numlida;
	}

}
?>
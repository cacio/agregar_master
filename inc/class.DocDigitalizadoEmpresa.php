<?php
class DocDigitalizadoEmpresa{

	private $Codigo;
	private $id_tpdoc;
	private $dtemissao;
	private $xcaminho;
	private $documento;
	private $xmotivo;
	private $status;
	private $id_empresa;
	private $selected;
	private $idprox;
	private $modalidade_env;
	private $razao_social;
	private $num_arq;

	public function __construct(){
		//nada
	}

	public function getCodigo(){
		return $this->Codigo;	
	}
	public function setCodigo($codigo){
		$this->Codigo = $codigo;
	}
	
	
	public function getIdTpDoc(){
		return $this->id_tpdoc;	
	}
	public function setIdTpDoc($idtpdoc){
		$this->id_tpdoc = $idtpdoc;
	}
	
	public function getDtEmissao(){
		return $this->dtemissao;	
	}
	public function setDtEmissao($dtemissao){
		$this->dtemissao = $dtemissao;
	}
	
	public function getXcaminho(){
		return $this->xcaminho;	
	}
	public function setXcaminho($xcaminho){
		$this->xcaminho = $xcaminho;
	}
	
	public function getDocumento(){
		return $this->documento;	
	}
	public function setDocumento($documento){
		$this->documento = $documento;
	}

	public function getXmotivo(){
		return $this->xmotivo;	
	}
	public function setXmotivo($xmotivo){
		$this->xmotivo = $xmotivo;
	}
	
	public function getStatus(){
		return $this->status;	
	}
	public function setStatus($status){
		$this->status = $status;
	}

	public function getIdEmpresa(){
		return $this->id_empresa;	
	}
	public function setIdEmpresa($idemp){
		$this->id_empresa = $idemp;
	}	
			
	public function getSelecionado(){
		return $this->selected;	
	}
	public function setSelecionado($selec){
		$this->selected = $selec;
	}
	public function getIdProx(){
		return $this->idprox;	
	}
	public function setIdProx($idprox){
		$this->idprox = $idprox;
	}
	
	public function getModalidadeEnvio(){
		return $this->modalidade_env;	
	}
	public function setModalidadeEnvio($modenv){
		$this->modalidade_env = $modenv;
	}
	
	public function getRazaoSocial(){
		return $this->razao_social;	
	}
	public function setRazaoSocial($razaosocial){
		$this->razao_social = $razaosocial;
	}

	public function getNumeroArquivo(){
		return $this->num_arq;	
	}
	public function setNumeroArquivo($numarq){
		$this->num_arq = $numarq;
	}

}
?>
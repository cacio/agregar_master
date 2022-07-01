<?php
class ModalidadeDocumento{

	private $Codigo;
	private $id_modalidade;
	private $id_documento;
	private $nomemodalidade;
	private $nomedocumento;
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
	
	
	public function getIdModalidade(){
		return $this->id_modalidade;	
	}
	public function setIdModalidade($idmod){
		$this->id_modalidade = $idmod;
	}
	
	public function getIdDocumento(){
		return $this->id_documento;	
	}
	public function setIdDocumento($idmod){
		$this->id_documento = $idmod;
	}
	
	public function getNomeModalidade(){
		return $this->nomemodalidade;	
	}
	public function setNomeModalidade($nommodalidade){
		$this->nomemodalidade = $nommodalidade;
	}
	
	public function getNomeDocumento(){
		return $this->nomedocumento;	
	}
	public function setNomeDocumento($nomedoc){
		$this->nomedocumento = $nomedoc;
	}
	
	public function getSelecionado(){
		return $this->selected;	
	}
	public function setSelecionado($selec){
		$this->selected = $selec;
	}
}
?>
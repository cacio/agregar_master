<?php
class ModalidadeEnvModalidadeEmp{

	private $Codigo;
	private $id_modalidade;
	private $id_modalidade_env;
	private $nomemodalidade;
	private $nomemodalidadeenv;
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
	
	public function getIdModalidadeEnv(){
		return $this->id_modalidade_env;	
	}
	public function setIdModalidadeEnv($idmodenv){
		$this->id_modalidade_env = $idmodenv;
	}
	
	public function getNomeModalidade(){
		return $this->nomemodalidade;	
	}
	public function setNomeModalidade($nommodalidade){
		$this->nomemodalidade = $nommodalidade;
	}
	
	public function getNomeModalidadeEnv(){
		return $this->nomemodalidadeenv;	
	}
	public function setNomeModalidadeEnv($nomodenv){
		$this->nomemodalidadeenv = $nomodenv;
	}
	
	public function getSelecionado(){
		return $this->selected;	
	}
	public function setSelecionado($selec){
		$this->selected = $selec;
	}
}
?>
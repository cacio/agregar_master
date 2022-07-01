<?php
class VeterinarioEstabelecimento{

	private $Codigo;	
	private $nome;
	private $n_crmv;
	private $endereco;
	private $email;	
	private $conv_municipio;
	private $org_municipio;		
	private $id_emp;
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
		
	public function getNome(){
		return $this->nome;	
	}
	public function setNome($nome){
		$this->nome = $nome;
	}
	
	public function getCrmv(){
		return $this->n_crmv;	
	}
	public function setCrmv($crmv){
		$this->n_crmv = $crmv;
	}		
	
	public function getEndereco(){
		return $this->endereco;	
	}
	public function setEndereco($end){
		$this->endereco = $end;
	}

	public function getEmail(){
		return $this->email;	
	}
	public function setEmail($email){
		$this->email = $email;
	}

	public function getConvenioMunicipio(){
		return $this->conv_municipio;	
	}
	public function setConvenioMunicipio($convmuni){
		$this->conv_municipio = $convmuni;
	}
	
	public function getOrgMunicipio(){
		return $this->org_municipio;	
	}
	public function setOrgMunicipio($orgmuni){
		$this->org_municipio = $orgmuni;
	}
				
	public function getIdEmpresa(){
		return $this->id_emp;	
	}
	public function setIdEmpresa($idemp){
		$this->id_emp = $idemp;
	}
	
	public function getIdProx(){
		return $this->idprox;	
	}
	public function setIdProx($idprox){
		$this->idprox = $idprox;
	}
	
}
?>
<?php
class Administracao{

	private $Codigo;	
	private $nome_gerente_diretor;	
	private $fone_gerente_diretor;
	private $email_gerente_diretor;
	private $nome_contador;
	private $fone_contador;
	private $email_contador;
	private $nome_tecnico;
	private $fone_tecnico;
	private $email_tecnico;
	private $n_crmv_tecnico;
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
		
	public function getNomeGerenteDiretor(){
		return $this->nome_gerente_diretor;	
	}
	public function setNomeGerenteDiretor($nomegente){
		$this->nome_gerente_diretor = $nomegente;
	}		
	
	public function getFoneGerenteDiretor(){
		return $this->fone_gerente_diretor;	
	}
	public function setFoneGerenteDiretor($fonegerente){
		$this->fone_gerente_diretor = $fonegerente;
	}
	
	public function getEmailGerenteDiretor(){
		return $this->email_gerente_diretor;	
	}
	public function setEmailGerenteDiretor($emailgerente){
		$this->email_gerente_diretor = $emailgerente;
	}
	
	public function getNomeContador(){
		return $this->nome_contador;	
	}
	public function setNomeContador($nomecontador){
		$this->nome_contador = $nomecontador;
	}
	
	public function getFoneContador(){
		return $this->fone_contador;	
	}
	public function setFoneContador($fonecontador){
		$this->fone_contador = $fonecontador;
	}
	
	public function getEmailContador(){
		return $this->email_contador;	
	}
	public function setEmailContador($emailcontador){
		$this->email_contador = $emailcontador;
	}
	
	public function getNomeTecnico(){
		return $this->nome_tecnico;	
	}
	public function setNomeTecnico($nometecnico){
		$this->nome_tecnico = $nometecnico;
	}	
	
	public function getFoneTecnico(){
		return $this->fone_tecnico;	
	}
	public function setFoneTecnico($fonetecnico){
		$this->fone_tecnico = $fonetecnico;
	}	
	
	public function getEmailTecnico(){
		return $this->email_tecnico;	
	}
	public function setEmailTecnico($emailtecnico){
		$this->email_tecnico = $emailtecnico;
	}
	
	public function getCrmvTecnico(){
		return $this->n_crmv_tecnico;	
	}
	public function setCrmvTecnico($crmv){
		$this->n_crmv_tecnico = $crmv;
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
<?php
class CertidoesLicenciamento{

	private $Codigo;	
	private $cert_fiscal_tesouro_estado;	
	private $licenca_amb_fepam;
	private $emitida;
	private $n_protocolo_fepam;
	private $data;
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
		
	public function getCertFiscalTesouroEstado(){
		return $this->cert_fiscal_tesouro_estado;	
	}
	public function setCertFiscalTesouroEstado($cert){
		$this->cert_fiscal_tesouro_estado = $cert;
	}		
	
	public function getLicencaAmbFepam(){
		return $this->licenca_amb_fepam;	
	}
	public function setLicencaAmbFepam($lic){
		$this->licenca_amb_fepam = $lic;
	}
	
	public function getEmitida(){
		return $this->emitida;	
	}
	public function setEmitida($emit){
		$this->emitida = $emit;
	}
	
	public function getNumeroProtocoloFepam(){
		return $this->n_protocolo_fepam;	
	}
	public function setNumeroProtocoloFepam($nprot){
		$this->n_protocolo_fepam = $nprot;
	}
	
	public function getData(){
		return $this->data;	
	}
	public function setData($data){
		$this->data = $data;
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
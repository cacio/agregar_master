<?php
class Empresas{

	private $Codigo;
	private $cnpj;
	private $razao_social;
	private $fantasia;
	private $marca;
	private $ins_estadual;
	private $endereco;
	private $nro;
	private $complemento;
	private $cep;
	private $cidade;
	private $estado;
	private $bairro;
	private $inspecao;
	private $fone1;
	private $fone2;
	private $email;
	private $responsavel;
	private $id_modalidade;
	private $capacidade_bovinos;
	private $capacidade_ovinos;
	private $selected;	
	private $idprox;
	private $dt_num_arq_ult_ato_junt_comerc;	
	private $form_const_juridica;	
	private $cap_social_reg;
	private $ativo;	
	private $capacidade_bubalino;
	private $TIPISEM;	
	private $REGINSP;	
	private $MEDVETE;	
	private $NUMCRMV;
	private $CAPDIAB;
	private $RALIZSN;
	private $PERABTE;
	private $INSTPAA;
	private $length;

	public function __construct(){
		//nada
	}

	public function getCodigo(){
		return $this->Codigo;	
	}
	public function setCodigo($codigo){
		$this->Codigo = $codigo;
	}
	
	public function getCnpj(){
		return $this->cnpj;	
	}
	public function setCnpj($cnpj){
		$this->cnpj = $cnpj;
	}
	
	public function getRazaoSocial(){
		return $this->razao_social;	
	}
	public function setRazaoSocial($razaosocial){
		$this->razao_social = $razaosocial;
	}
	
	public function getFantasia(){
		return $this->fantasia;	
	}
	public function setFantasia($fant){
		$this->fantasia = $fant;
	}	
	
	public function getMarca(){
		return $this->marca;	
	}
	public function setMarca($marca){
		$this->marca = $marca;
	}
	
	public function getInscricaoEstadual(){
		return $this->ins_estadual;	
	}
	public function setInscricaoEstadual($insc){
		$this->ins_estadual = $insc;
	}
	
	public function getEndereco(){
		return $this->endereco;	
	}
	public function setEndereco($end){
		$this->endereco = $end;
	}
	
	public function getNumero(){
		return $this->nro;	
	}
	public function setNumero($nr){
		$this->nro = $nr;
	}
	
	public function getComplemento(){
		return $this->complemento;	
	}
	public function setComplemento($compl){
		$this->complemento = $compl;
	}
	
	public function getCep(){
		return $this->cep;	
	}
	public function setCep($cep){
		$this->cep = $cep;
	}
	
	public function getCidade(){
		return $this->cidade;	
	}
	public function setCidade($cidade){
		$this->cidade = $cidade;
	}
	
	public function getEstado(){
		return $this->estado;	
	}
	public function setEstado($estado){
		$this->estado = $estado;
	}
	
	public function getBairro(){
		return $this->bairro;	
	}
	public function setBairro($bairro){
		$this->bairro = $bairro;
	}
	
	public function getInspecao(){
		return $this->inspecao;	
	}
	public function setInspecao($inspecao){
		$this->inspecao = $inspecao;
	}
	
	public function getFone1(){
		return $this->fone1;	
	}
	public function setFone1($fone1){
		$this->fone1 = $fone1;
	}
	public function getFone2(){
		return $this->fone2;	
	}
	public function setFone2($fone2){
		$this->fone2 = $fone2;
	}
	
	public function getEmail(){
		return $this->email;	
	}
	public function setEmail($email){
		$this->email = $email;
	}
	public function getResponsavel(){
		return $this->responsavel;	
	}
	public function setResponsavel($resp){
		$this->responsavel = $resp;
	}
	public function getIdModalidade(){
		return $this->id_modalidade;	
	}
	public function setIdModalidade($idmod){
		$this->id_modalidade = $idmod;
	}
	public function getCapacidadeBovino(){
		return $this->capacidade_bovinos;	
	}
	public function setCapacidadeBovino($capbov){
		$this->capacidade_bovinos = $capbov;
	}
	public function getCapacidadeOvino(){
		return $this->capacidade_ovinos;	
	}
	public function setCapacidadeOvino($capov){
		$this->capacidade_ovinos = $capov;
	}		
	
	public function getSeleciona(){
		return $this->selected;
	}
	public function setSeleciona($sel){
		$this->selected = $sel;
	}	
	
	public function getIdProx(){
		return $this->idprox;	
	}
	public function setIdProx($idprox){
		$this->idprox = $idprox;
	}
	
	public function getDtAtoJuntaComercial(){
		return $this->dt_num_arq_ult_ato_junt_comerc;	
	}
	public function setDtAtoJuntaComercial($dtjunt){
		$this->dt_num_arq_ult_ato_junt_comerc = $dtjunt;
	}
	
	public function getFormConstituicaoJuridica(){
		return $this->form_const_juridica;	
	}
	public function setFormConstituicaoJuridica($formjuri){
		$this->form_const_juridica = $formjuri;
	}
	
	public function getCapSocialReg(){
		return $this->cap_social_reg;	
	}
	public function setCapSocialReg($capsocireg){
		$this->cap_social_reg = $capsocireg;
	}
	
	public function getAtivo(){
		return $this->ativo;	
	}
	public function setAtivo($ativo){
		$this->ativo = $ativo;
	}
	
	public function getCapacidadeBubalino(){
		return $this->capacidade_bubalino;	
	}
	public function setCapacidadeBubalino($capacidade_bubalino){
		$this->capacidade_bubalino = $capacidade_bubalino;
	}
	
	public function getTipiSem(){
		return $this->TIPISEM;	
	}
	public function setTipiSem($tipisem){
		$this->TIPISEM = $tipisem;
	}
	
	public function getReginSP(){
		return $this->REGINSP;	
	}
	public function setReginSP($reginsp){
		$this->REGINSP = $reginsp;
	}
	
	public function getMedVete(){
		return $this->MEDVETE;	
	}
	public function setMedVete($medvete){
		$this->MEDVETE = $medvete;
	}
	
	public function getNumCrmv(){
		return $this->NUMCRMV;	
	}
	public function setNumCrmv($numcrmv){
		$this->NUMCRMV = $numcrmv;
	}
	
	public function getCapDiab(){
		return $this->CAPDIAB;	
	}
	public function setCapDiab($capdiab){
		$this->CAPDIAB = $capdiab;
	}
	
	public function getRalizSN(){
		return $this->RALIZSN;	
	}
	public function setRalizSN($ralizsn){
		$this->RALIZSN = $ralizsn;
	}
	
	public function getPerabte(){
		return $this->PERABTE;	
	}
	public function setPerabte($perabte){
		$this->PERABTE = $perabte;
	}
	
	public function getInstpaa(){
		return $this->INSTPAA;	
	}
	public function setInstpaa($instpaa){
		$this->INSTPAA = $instpaa;
	}

	public function getLengthUser(){
		return $this->length;	
	}
	public function setLengthUser($length){
		$this->length = $length;
	}
}
?>
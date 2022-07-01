<?php

class Resumo{
		
	private $Codigo;
	private $COMPETENC;
	private $BOVINOS;
	private $BUBALINOS;
	private $OVINOS;
	private $ICMSNOR;
	private $SUBSTIT;
	private $CREDITOENT;
	private $CREDITOSRS;
	private $CREDITOSOE;
	private $BASEENT;
	private $BASESAIRS;
	private $BASESAIOE;
	private $NUMEFUNC;
	private $VALOFOLHA;
	private $DATAPAGT; 
	private $BASESAIRS4;
	private $CRIDITOSR4;
	private $CREDITOSR4;
	private $cnpj_emp;
	private $numero_entrada;
	private $numero_saida;
	private $numero_cabecas;
	private $status;
	private $protocolo;
	private $nome_status;
	private $razaosocialemp;
	private $emp_fantasia;
	private $emp_endereco;
	private $emp_fone1;
	private $emp_fone2;
	private $emp_cidade;
	private $emp_estado;
	private $emp_bairro;
	private $emp_cep;
	private $tipoarq;
	private $CREDITOSOE4;
	private $BASESAIOE4;
	
	public function __construct(){
		//nada
	}
		
	public function getCodigo(){
		return $this->Codigo;	
	}

	public function setCodigo($codigo){
		$this->Codigo = $codigo;
	}

	public function getCompetenc(){
		return $this->COMPETENC;	
	}

	public function setCompetenc($competenc){
		$this->COMPETENC = $competenc;
	}
	
	public function getBovinos(){
		return $this->BOVINOS;	
	}

	public function setBovinos($bovinos){
		$this->BOVINOS = $bovinos;
	}	
	
	public function getBubalinos(){
		return $this->BUBALINOS;	
	}

	public function setBubalinos($bubalinos){
		$this->BUBALINOS = $bubalinos;
	}
	
	public function getOvinos(){
		return $this->OVINOS;	
	}

	public function setOvinos($ovinos){
		$this->OVINOS = $ovinos;
	}
	
	public function getIcmsNor(){
		return $this->ICMSNOR;	
	}

	public function setIcmsNor($icmsnor){
		$this->ICMSNOR = $icmsnor;
	}
	
	public function getSubstit(){
		return $this->SUBSTIT;	
	}

	public function setSubstit($substit){
		$this->SUBSTIT = $substit;
	}
	
	public function getCreditoEnt(){
		return $this->CREDITOENT;	
	}

	public function setCreditoEnt($creditoent){
		$this->CREDITOENT = $creditoent;
	}
	
	public function getCreditosRS(){
		return $this->CREDITOSRS;	
	}

	public function setCreditosRS($creditosrs){
		$this->CREDITOSRS = $creditosrs;
	}
	
	public function getCreditosOE(){
		return $this->CREDITOSOE;	
	}

	public function setCreditosOE($creditosoe){
		$this->CREDITOSOE = $creditosoe;
	}
	
	public function getBaseEnt(){
		return $this->BASEENT;	
	}

	public function setBaseEnt($baseent){
		$this->BASEENT = $baseent;
	}
	
	public function getBaseSaiRS(){
		return $this->BASESAIRS;	
	}

	public function setBaseSaiRS($basesairs){
		$this->BASESAIRS = $basesairs;
	}
	
	public function getBaseSaiOE(){
		return $this->BASESAIOE;	
	}

	public function setBaseSaiOE($basesaioe){
		$this->BASESAIOE = $basesaioe;
	}
	
	public function getNumeroFuncionario(){
		return $this->NUMEFUNC;	
	}

	public function setNumeroFuncionario($numefun){
		$this->NUMEFUNC = $numefun;
	}
	
	public function getValorFolha(){
		return $this->VALOFOLHA;	
	}

	public function setValorFolha($valorfolha){
		$this->VALOFOLHA = $valorfolha;
	}
	
	public function getDataPagto(){
		return $this->DATAPAGT;	
	}

	public function setDataPagto($datapagto){
		$this->DATAPAGT = $datapagto;
	}
	
	public function getBaseSaiRS4(){
		return $this->BASESAIRS4;	
	}

	public function setBaseSaiRS4($basesairs4){
		$this->BASESAIRS4 = $basesairs4;
	}
	
	public function getCriditosR4(){
		return $this->CRIDITOSR4;	
	}

	public function setCriditosR4($criditosr4){
		$this->CRIDITOSR4 = $criditosr4;
	}
	
	public function getCreditosR4(){
		return $this->CREDITOSR4;	
	}

	public function setCreditosR4($creditosr4){
		$this->CREDITOSR4 = $creditosr4;
	}
	
	public function getCnpjEmp(){
		return $this->cnpj_emp;	
	}

	public function setCnpjEmp($cnpjemp){
		$this->cnpj_emp = $cnpjemp;
	}

	public function getNumeroEntrada(){
		return $this->numero_entrada;	
	}

	public function setNumeroEntrada($numetrada){
		$this->numero_entrada = $numetrada;
	}

	public function getNumeroSaida(){
		return $this->numero_saida;	
	}

	public function setNumeroSaida($numsaida){
		$this->numero_saida = $numsaida;
	}

	public function getNumeroCabecas(){
		return $this->numero_cabecas;	
	}

	public function setNumeroCabecas($numcabecas){
		$this->numero_cabecas = $numcabecas;
	}

	public function getStatus(){
		return $this->status;	
	}

	public function setStatus($status){
		$this->status = $status;
	}

	public function getProtocolo(){
		return $this->protocolo;	
	}

	public function setProtocolo($protocolo){
		$this->protocolo = $protocolo;
	}

	public function getNomeStatus(){
		return $this->nome_status;	
	}

	public function setNomeStatus($nomestatus){
		$this->nome_status = $nomestatus;
	}

	public function getRazaoSocialEmp(){
		return $this->razaosocialemp;	
	}

	public function setRazaoSocialEmp($razaosocialemp){
		$this->razaosocialemp = $razaosocialemp;
	}

	public function getEmpFantasia(){
		return $this->emp_fantasia;	
	}

	public function setEmpFantasia($empfant){
		$this->emp_fantasia = $empfant;
	}

	public function getEmpEndereco(){
		return $this->emp_endereco;	
	}

	public function setEmpEndereco($empend){
		$this->emp_endereco = $empend;
	}

	public function getEmpFone1(){
		return $this->emp_fone1;	
	}

	public function setEmpFone1($empfone1){
		$this->emp_fone1 = $empfone1;
	}

	public function getEmpFone2(){
		return $this->emp_fone2;	
	}

	public function setEmpFone2($empfone2){
		$this->emp_fone2 = $empfone2;
	}

	public function getEmpCidade(){
		return $this->emp_cidade;	
	}

	public function setEmpCidade($empcidade){
		$this->emp_cidade = $empcidade;
	}

	public function getEmpEstado(){
		return $this->emp_estado;	
	}

	public function setEmpEstado($empestado){
		$this->emp_estado = $empestado;
	}

	public function getEmpBairro(){
		return $this->emp_bairro;	
	}

	public function setEmpBairro($empbairro){
		$this->emp_bairro = $empbairro;
	}

	public function getEmpCep(){
		return $this->emp_cep;	
	}

	public function setEmpCep($empcep){
		$this->emp_cep = $empcep;
	}

	public function getTipoArq(){
		return $this->tipoarq;	
	}

	public function setTipoArq($tparq){
		$this->tipoarq = $tparq;
	}
	
	public function getCreditosOE4(){
		return $this->CREDITOSOE4;	
	}

	public function setCreditosOE4($creditosoe4){
		$this->CREDITOSOE4 = $creditosoe4;
	}
	
	public function setBaseSaiOE4($basesaioe4){
		$this->BASESAIOE4 = $basesaioe4;
	}
	
	public function getBaseSaiOE4(){
		return $this->BASESAIOE4;	
	}
}	

?>
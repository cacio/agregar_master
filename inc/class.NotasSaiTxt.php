<?php

class NotasSaiTxt{
		

	private $Codigo;
	private $numero_nota;
	private $data_emissao;
	private $cnpj_cpf;
	private $valor_total_nota;
    private $valor_icms;
    private $valor_icms_subs;
    private $ent_sai;
    private $insc_estadual;
    private $cfop;	
    private $cnpj_emp;
	private $entrada;
	private $saida;
	private $numero_saida;
	private $razao;
	private $codsecretaria;
	private $descsecretaria;
	private $peso;
	private $pecas;
	private $precounit;
	private $pkrel;
	private $codigoprod;
	private $chave;
	private $xml;
	private $nomecli;
	private $totalprod;
	private $itemnota;
	private $descprod;
	private $cfopSN;
	private $subtotal;

	public function __construct(){

		//nada

	}
		
	public function getCodigo(){
		return $this->Codigo;	
	}

	public function setCodigo($codigo){
		$this->Codigo = $codigo;
	}

	public function getNumeroNota(){
		return $this->numero_nota;	
	}

	public function setNumeroNota($numeronota){
		$this->numero_nota = $numeronota;
	}
		
	public function getDataEmissao(){
		return $this->data_emissao;	
	}

	public function setDataEmissao($emissao){
		$this->data_emissao = $emissao;
	}
	
	public function getCnpjCpf(){
		return $this->cnpj_cpf;	
	}

	public function setCnpjCpf($cnpjcpf){
		$this->cnpj_cpf = $cnpjcpf;
	}		
	
	public function getValorTotalNota(){
		return $this->valor_total_nota;	
	}

	public function setValorTotalNota($valortotalnota){
		$this->valor_total_nota = $valortotalnota;
	}
	
	public function getValorIcms(){
		return $this->valor_icms;	
	}

	public function setValorIcms($valoricms){
		$this->valor_icms = $valoricms;
	}
	
	public function getValorIcmsSubs(){
		return $this->valor_icms_subs;	
	}

	public function setValorIcmsSubs($valoricmssubs){
		$this->valor_icms_subs = $valoricmssubs;
	}
	
	public function getEntSai(){
		return $this->ent_sai;	
	}

	public function setEntSai($entsai){
		$this->ent_sai = $entsai;
	}
	
	public function getInscEstadual(){
		return $this->insc_estadual;	
	}

	public function setInscEstadual($inscestadual){
		$this->insc_estadual = $inscestadual;
	}
	
	
	public function getCfop(){
		return $this->cfop;	
	}

	public function setCfop($cfop){
		$this->cfop = $cfop;
	}
		
	public function getCnpjEmp(){
		return $this->cnpj_emp;	
	}

	public function setCnpjEmp($cnpjemp){
		$this->cnpj_emp = $cnpjemp;
	}
	
	public function getEntrada(){
		return $this->entrada;	
	}

	public function setEntrada($entrada){
		$this->entrada = $entrada;
	}
	
	public function getSaida(){
		return $this->saida;	
	}

	public function setSaida($saida){
		$this->saida = $saida;
	}
	
	public function getNumeroSaida(){
		return $this->numero_saida;	
	}

	public function setNumeroSaida($numero_saida){
		$this->numero_saida = $numero_saida;
	}

	public function getRazao(){
		return $this->razao;	
	}

	public function setRazao($razao){
		$this->razao = $razao;
	}
	
	public function getCodSecretaria(){
		return $this->codsecretaria;	
	}

	public function setCodSecretaria($codsecret){
		$this->codsecretaria = $codsecret;
	}

	public function getDescSecretaria(){
		return $this->descsecretaria;	
	}

	public function setDescSecretaria($descsecretaria){
		$this->descsecretaria = $descsecretaria;
	}

	public function getPeso(){
		return $this->peso;	
	}

	public function setPeso($peso){
		$this->peso = $peso;
	}

	public function getPecas(){
		return $this->pecas;	
	}

	public function setPecas($pecas){
		$this->pecas = $pecas;
	}

	public function getPrecoUnitario(){
		return $this->precounit;	
	}

	public function setPrecoUnitario($precounit){
		$this->precounit = $precounit;
	}

	public function getPkRelacionamento(){
		return $this->pkrel;	
	}

	public function setPkRelacionamento($pkrel){
		$this->pkrel = $pkrel;
	}

	public function getCodigoProduto(){
		return $this->codigoprod;	
	}

	public function setCodigoProduto($codprod){
		$this->codigoprod = $codprod;
	}
	
	public function getChave(){
		return $this->chave;	
	}

	public function setChave($chave){
		$this->chave = $chave;
	}
	
	public function getXml(){
		return $this->xml;	
	}

	public function setXml($xml){
		$this->xml = $xml;
	}
	
	public function getNomeCli(){
		return $this->nomecli;	
	}

	public function setNomeCli($nomecli){
		$this->nomecli = $nomecli;
	}

	public function getTotalProd(){
		return $this->totalprod;	
	}

	public function setTotalProd($totalprod){
		$this->totalprod = $totalprod;
	}

	public function getItemNota(){
		return $this->itemnota;	
	}

	public function setItemNota($itemnota){
		$this->itemnota = $itemnota;
	}

	public function getDescProduto(){
		return $this->descprod;	
	}

	public function setDescProduto($descprod){
		$this->descprod = $descprod;
	}
	
	public function getCfopSN(){
		return $this->cfopSN;	
	}

	public function setCfopSN($cfopsn){
		$this->cfopSN = $cfopsn;
	}

	public function getSubTotal(){
		return $this->subtotal;	
	}

	public function setSubTotal($subtotal){
		$this->subtotal = $subtotal;
	}
}

?>
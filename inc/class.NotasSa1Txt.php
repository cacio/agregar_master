<?php

class NotasSa1Txt{
		

	private $Codigo;
	private $numero_nota;
	private $data_emissao;
	private $cnpj_cpf;
	private $codigo_produto;
	private	$qtd_pecas;
	private	$peso;
	private	$preco_unitario;
	private	$ent_sai;
	private	$numero_item_nota;
	private	$insc_estadual;
	private	$cfop;
	private	$aliquota_icms;	
    private $cnpj_emp;
	private $numero_saida;
	private $desc_prod;
	private $prod_qtd;
	private $subtotal;
	private $codsecretaria;
	private $nomecfop;
	private $devolucao;
	private $uf;
	private $valordevolucao;
	private $vicms;
	
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
	
	public function getCodigoProduto(){
		return $this->codigo_produto;	
	}

	public function setCodigoProduto($codigoproduto){
		$this->codigo_produto = $codigoproduto;
	}
	
	public function getQtdPecas(){
		return $this->qtd_pecas;	
	}

	public function setQtdPecas($qtdpecas){
		$this->qtd_pecas = $qtdpecas;
	}
	
	public function getPeso(){
		return $this->peso;	
	}

	public function setPeso($peso){
		$this->peso = $peso;
	}
	
	public function getPrecoUnitario(){
		return $this->preco_unitario;	
	}

	public function setPrecoUnitario($precounitario){
		$this->preco_unitario = $precounitario;
	}
	
	public function getEntSai(){
		return $this->ent_sai;	
	}

	public function setEntSai($entasai){
		$this->ent_sai = $entasai;
	}
	
	public function getNumeroItemNota(){
		return $this->numero_item_nota;	
	}

	public function setNumeroItemNota($numeroitemnota){
		$this->numero_item_nota = $numeroitemnota;
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
	
	public function getAliquotaIcms(){
		return $this->aliquota_icms;	
	}

	public function setAliquotaIcms($aliquotaicms){
		$this->aliquota_icms = $aliquotaicms;
	}
	
	public function getCnpjEmp(){
		return $this->cnpj_emp;	
	}

	public function setCnpjEmp($cnpjemp){
		$this->cnpj_emp = $cnpjemp;
	}

	public function getNumeroSaida(){
		return $this->numero_saida;	
	}

	public function setNumeroSaida($numero_saida){
		$this->numero_saida = $numero_saida;
	}

	public function setDescProd($descprod) { 
		$this->desc_prod = $descprod; 
	}
	public function getDescProd() { 
		return $this->desc_prod; 
	}

	public function setProdQtd($prodqtd) { 
		$this->prod_qtd = $prodqtd; 
	}
	public function getProdQtd() { 
		return $this->prod_qtd; 
	}
	
	public function setSubtotal($subtotal) { 
		$this->subtotal = $subtotal; 
	}
	public function getSubtotal() { 
		return $this->subtotal; 
	}

	public function setCodSecretaria($codsecretaria) { 
		$this->codsecretaria = $codsecretaria; 
	}
	public function getCodSecretaria() { 
		return $this->codsecretaria; 
	}

	public function setNomeCfop($nomecfop) { 
		$this->nomecfop = $nomecfop; 
	}
	public function getNomeCfop() { 
		return $this->nomecfop; 
	}

	public function setDevolucao($devolucao) { 
		$this->devolucao = $devolucao; 
	}
	public function getDevolucao() { 
		return $this->devolucao; 
	}

	public function setUf($uf) { 
		$this->uf = $uf; 
	}
	public function getUf() { 
		return $this->uf; 
	}

	public function setValorDevolucao($vldev) { 
		$this->valordevolucao = $vldev; 
	}
	public function getValorDevolucao() { 
		return $this->valordevolucao; 
	}
	
	public function setVicms($vicms) { 
		$this->vicms = $vicms; 
	}
	public function getVicms() { 
		return $this->vicms; 
	}
	
}

?>
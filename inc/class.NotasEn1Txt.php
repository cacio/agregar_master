<?php

class NotasEn1Txt{
		

	private $Codigo;
	private $numero_nota;
	private $data_emissao;
	private $cnpj_cpf;
	private $codigo_produto;
	private $qtd_cabeca;
	private $peso_vivo_cabeca;
	private $peso_carcasa;
	private $preco_quilo;
	private $numero_item_nota;
	private $insc_estadual;
	private $data_abate;
	private $tipo_r_v;
	private $cfop;
	private $aliquota_icms;
	private $cnpj_emp;
	private $bovinos;
	private $bubalinos;
	private $ovinos;
	private $vivo;
	private $rendimento;
	private $macho_bovino;
	private $femea_bovino;
	private $macho_bubalino;
	private $femea_bubalino;
	private $macho_ovinos;
	private $femea_ovinos;
	private $valor_total_rendimento;
	private $valor_total_vivo;
	private $razao_social_empresa;
	private $fantasia_empresa;
	private $cidade_empresa;
	private $cod_secretaria;
	private $desc_secretaria;
	private $valortotalnota;
	private $pkrel;
	private $serie;
	private $desc_prod;
	private $prod_qtd;
	private $especie;
	private $cabeca;
	private $nomecfop;
	private $devolucao;
	private $subtotal;
	private $entsai;

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

	public function setCodigoProduto($codigoprod){
		$this->codigo_produto = $codigoprod;
	}
	
	public function getQtdCabeca(){
		return $this->qtd_cabeca;	
	}

	public function setQtdCabeca($qtdcabeca){
		$this->qtd_cabeca = $qtdcabeca;
	}
	
	public function getPesoVivoCabeca(){
		return $this->peso_vivo_cabeca;	
	}

	public function setPesoVivoCabeca($pesovivocabeca){
		$this->peso_vivo_cabeca = $pesovivocabeca;
	}
	
	public function getPesoCarcasa(){
		return $this->peso_carcasa;	
	}

	public function setPesoCarcasa($pesocarcasa){
		$this->peso_carcasa = $pesocarcasa;
	}
	
	public function getPrecoQuilo(){
		return $this->preco_quilo;	
	}

	public function setPrecoQuilo($precoquilo){
		$this->preco_quilo = $precoquilo;
	}
	
	public function getNumeroItemNota(){
		return $this->numero_item_nota;	
	}

	public function setNumeroItemNota($numeroitemnota){
		$this->numero_item_nota = $numeroitemnota;
	}
	
	public function getInsEstadual(){
		return $this->insc_estadual;	
	}

	public function setInsEstadual($inscestadual){
		$this->insc_estadual = $inscestadual;
	}
	
	public function getDataAbate(){
		return $this->data_abate;	
	}

	public function setDataAbate($dataabate){
		$this->data_abate = $dataabate;
	}
	
	public function getTipo_R_V(){
		return $this->tipo_r_v;	
	}

	public function setTipo_R_V($tiporv){
		$this->tipo_r_v = $tiporv;
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
	
	public function getBovinos(){
		return $this->bovinos;	
	}

	public function setBovinos($bovinos){
		$this->bovinos = $bovinos;
	}
	
	public function getBubalinos(){
		return $this->bubalinos;	
	}

	public function setBubalinos($bubalinos){
		$this->bubalinos = $bubalinos;
	}
	
	public function getOvinos(){
		return $this->ovinos;	
	}

	public function setOvinos($ovinos){
		$this->ovinos = $ovinos;
	}
	
	public function getVivo(){
		return $this->vivo;	
	}

	public function setVivo($vivo){
		$this->vivo = $vivo;
	}
	
	public function getRendimento(){
		return $this->rendimento;	
	}

	public function setRendimento($rendimento){
		$this->rendimento = $rendimento;
	}

	public function getMachoBovino(){
		return $this->macho_bovino;	
	}

	public function setMachoBovino($machobovino){
		$this->macho_bovino = $machobovino;
	}

	public function getFemaBovino(){
		return $this->femea_bovino;	
	}

	public function setFemaBovino($femeabovino){
		$this->femea_bovino = $femeabovino;
	}

	public function getMachoBubalino(){
		return $this->macho_bubalino;	
	}

	public function setMachoBubalino($machobubalino){
		$this->macho_bubalino = $machobubalino;
	}

	public function getFemeaBubalino(){
		return $this->femea_bubalino;	
	}

	public function setFemeaBubalino($femeabubalino){
		$this->femea_bubalino = $femeabubalino;
	}

	public function getMachoOvinos(){
		return $this->macho_ovinos;	
	}

	public function setMachoOvinos($machoovinos){
		$this->macho_ovinos = $machoovinos;
	}

	public function getFemeaOvinos(){
		return $this->femea_ovinos;	
	}

	public function setFemeaOvinos($femeaovinos){
		$this->femea_ovinos = $femeaovinos;
	}

	public function getValorTotalRendimento(){
		return $this->valor_total_rendimento;	
	}

	public function setValorTotalRendimento($valortotrendimento){
		$this->valor_total_rendimento = $valortotrendimento;
	}

	public function getValorTotalVivo(){
		return $this->valor_total_vivo;	
	}

	public function setValorTotalVivo($valortotvivo){
		$this->valor_total_vivo = $valortotvivo;
	}

	public function getRazaoSocialEmpresa(){
		return $this->razao_social_empresa;	
	}

	public function setRazaoSocialEmpresa($razaosocial){
		$this->razao_social_empresa = $razaosocial;
	}

	public function getFantasiaEmpresa(){
		return $this->fantasia_empresa;	
	}

	public function setFantasiaEmpresa($fantasia){
		$this->fantasia_empresa = $fantasia;
	}

	public function getCidadeEmpresa(){
		return $this->cidade_empresa;	
	}

	public function setCidadeEmpresa($cidade){
		$this->cidade_empresa = $cidade;
	}

	public function getCodSecretaria(){
		return $this->cod_secretaria;	
	}

	public function setCodSecretaria($codsecret){
		$this->cod_secretaria = $codsecret;
	}

	public function getDescSecretaria(){
		return $this->desc_secretaria;	
	}

	public function setDescSecretaria($descsecret){
		$this->desc_secretaria = $descsecret;
	}

	public function getValorTotalNota(){
		return $this->valortotalnota;	
	}

	public function setValorTotalNota($vlnota){
		$this->valortotalnota = $vlnota;
	}

	public function getPkRelacionamento(){
		return $this->pkrel;	
	}

	public function setPkRelacionamento($pkrel){
		$this->pkrel = $pkrel;
	}

	public function setSerie($serie) { 
		$this->serie = $serie; 
	}
	public function getSerie() { 
		return $this->serie; 
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

	public function setEspecie($especie) { 
		$this->especie = $especie; 
	}
	public function getEspecie() { 
		return $this->especie; 
	}

	public function setPeso($peso) { 
		$this->peso = $peso; 
	}
	public function getPeso() { 
		return $this->peso; 
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

	public function setSubTotal($subtotal) { 
		$this->subtotal = $subtotal; 
	}
	public function getSubTotal() { 
		return $this->subtotal; 
	}

	public function setEntSai($entsai) { 
		$this->entsai = $entsai; 
	}
	public function getEntSai() { 
		return $this->entsai; 
	}

}

?>
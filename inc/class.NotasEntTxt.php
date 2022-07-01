<?php

class NotasEntTxt{
		

	private $Codigo;
	private $numero_nota;
    private $data_emissao;
    private $cnpj_cpf;
    private $tipo_v_r_a;
    private $valor_total_nota;
    private $gta;
    private $numero_nota_produtor_ini;
    private $numero_nota_produtor_fin;
    private $condenas;
    private $abate;
    private $insc_produtor;
    private $cnpj_emp;
	private $numero_entrada;
	private $chave;
	private $xml;
	private $nomecli;
	private $valortotalproduto;
	private $dtabate;

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
	
	public function getTipoV_R_A(){
		return $this->tipo_v_r_a;	
	}

	public function setTipoV_R_A($tipovra){
		$this->tipo_v_r_a = $tipovra;
	}
	
	public function getValorTotalNota(){
		return $this->valor_total_nota;	
	}

	public function setValorTotalNota($vltotnota){
		$this->valor_total_nota = $vltotnota;
	}
	
	public function getGta(){
		return $this->gta;	
	}

	public function setGta($gta){
		$this->gta = $gta;
	}
	
	public function getNumeroNotaProdutorIni(){
		return $this->numero_nota_produtor_ini;	
	}

	public function setNumeroNotaProdutorIni($numnotaini){
		$this->numero_nota_produtor_ini = $numnotaini;
	}
	
	public function getNumeroNotaProdutorFin(){
		return $this->numero_nota_produtor_fin;	
	}

	public function setNumeroNotaProdutorFin($numnotafin){
		$this->numero_nota_produtor_fin = $numnotafin;
	}
	
	public function getCondenas(){
		return $this->condenas;	
	}

	public function setCondenas($condenas){
		$this->condenas = $condenas;
	}
	
	public function getAbate(){
		return $this->abate;	
	}

	public function setAbate($abate){
		$this->abate = $abate;
	}
	
	public function getInscProdutor(){
		return $this->insc_produtor;	
	}

	public function setInscProdutor($inscprodutor){
		$this->insc_produtor = $inscprodutor;
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

	public function setNumeroEntrada($numero_entrada){
		$this->numero_entrada = $numero_entrada;
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

	public function getValorTotalProduto(){
		return $this->valortotalproduto;	
	}

	public function setValorTotalProduto($vltotprod){
		$this->valortotalproduto = $vltotprod;
	}

	public function getDataAbate(){
		return $this->dtabate;	
	}

	public function setDataAbate($dtabate){
		$this->dtabate = $dtabate;
	}
}

?>
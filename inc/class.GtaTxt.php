<?php

class GtaTxt{
		

	private $Codigo;
	private $numero_nota;
	private $data_emissao;
	private $cnpj_cpf;
	private $numero_gta;
    private $insc_estadual;
    private $especie;
    private $qtd_animais_macho_idade;
    private $qtd_animais_femea_idade;
    private $qtd_animais_macho_4_12;
    private $qtd_animais_femea_4_12;
    private $qtd_animais_macho_12_24;
    private $qtd_animais_femea_12_24;
    private $qtd_animais_macho_24_36;
    private $qtd_animais_femea_24_36;
    private $qtd_animais_macho_36;
    private $qtd_animais_femea_36;
    private $cnpj_emp;
	private $proxid;

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
	
	public function getNumeroGta(){
		return $this->numero_gta;	
	}

	public function setNumeroGta($numerogta){
		$this->numero_gta = $numerogta;
	}
	
	public function getInscEstadual(){
		return $this->insc_estadual;	
	}

	public function setInscEstadual($inscestadual){
		$this->insc_estadual = $inscestadual;
	}
	
	public function getEspecie(){
		return $this->especie;	
	}

	public function setEspecie($especie){
		$this->especie = $especie;
	}
	
	public function getQtdAnimaisMachoIdade(){
		return $this->qtd_animais_macho_idade;	
	}

	public function setQtdAnimaisMachoIdade($qtanimaismachoidade){
		$this->qtd_animais_macho_idade = $qtanimaismachoidade;
	}
	
	public function getQtdAnimaisFemeaIdade(){
		return $this->qtd_animais_femea_idade;	
	}

	public function setQtdAnimaisFemeaIdade($qtanimaisfemeaidade){
		$this->qtd_animais_femea_idade = $qtanimaisfemeaidade;
	}
	
	public function getQtdAnimaisMacho_4_12(){
		return $this->qtd_animais_macho_4_12;	
	}

	public function setQtdAnimaisMacho_4_12($qtanimaismacho4a12){
		$this->qtd_animais_macho_4_12 = $qtanimaismacho4a12;
	}
	
	public function getQtdAnimaisFemea_4_12(){
		return $this->qtd_animais_femea_4_12;	
	}

	public function setQtdAnimaisFemea_4_12($qtanimaisfemea4a12){
		$this->qtd_animais_femea_4_12 = $qtanimaisfemea4a12;
	}	
	
	public function getQtdAnimaisMacho_12_24(){
		return $this->qtd_animais_macho_12_24;	
	}

	public function setQtdAnimaisMacho_12_24($qtanimaismacho12a24){
		$this->qtd_animais_macho_12_24 = $qtanimaismacho12a24;
	}
	
	public function getQtdAnimaisFemea_12_24(){
		return $this->qtd_animais_femea_12_24;	
	}

	public function setQtdAnimaisFemea_12_24($qtanimaisfemea12a24){
		$this->qtd_animais_femea_12_24 = $qtanimaisfemea12a24;
	}
	
	public function getQtdAnimaisMacho_24_36(){
		return $this->qtd_animais_macho_24_36;	
	}

	public function setQtdAnimaisMacho_24_36($qtanimaismacho24a36){
		$this->qtd_animais_macho_24_36 = $qtanimaismacho24a36;
	}
	
	public function getQtdAnimaisFemea_24_36(){
		return $this->qtd_animais_femea_24_36;	
	}

	public function setQtdAnimaisFemea_24_36($qtanimaisfemea24a36){
		$this->qtd_animais_femea_24_36 = $qtanimaisfemea24a36;
	}
	
	
	public function getQtdAnimaisMacho36(){
		return $this->qtd_animais_macho_36;	
	}

	public function setQtdAnimaisMacho36($qtanimaismacho36){
		$this->qtd_animais_macho_36 = $qtanimaismacho36;
	}
	
	
	public function getQtdAnimaisFemea36(){
		return $this->qtd_animais_femea_36;	
	}

	public function setQtdAnimaisFemea36($qtanimaisfemea36){
		$this->qtd_animais_femea_36 = $qtanimaisfemea36;
	}
	
	public function getCnpjEmp(){
		return $this->cnpj_emp;	
	}

	public function setCnpjEmp($cnpjemp){
		$this->cnpj_emp = $cnpjemp;
	}
	
	public function getProxId(){
		return $this->proxid;	
	}

	public function setProxId($proxid){
		$this->proxid = $proxid;
	}
}

?>
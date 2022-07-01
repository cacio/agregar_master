<?php
class Exportacao{

    private $codigo;
    private $competencia;
    private $pais;    
    private $kg_glos;
    private $valor_glos;
    private $kg_vend;
    private $valor_vend;
    private $cnpj_emp;

    public function __construct(){
		//nada
	}

    public function getCodigo(){
        return $this->codigo;
    }

    public function setCodigo($codigo){
        $this->codigo = $codigo;
    }

    public function getCompetencia(){
        return $this->competencia;
    }

    public function setCompetencia($comp){
        $this->competencia = $comp;
    }

    public function getPais(){
        return $this->pais;
    }

    public function setPais($pais){
        $this->pais = $pais;
    }

    public function getKgGlosado(){
        return $this->kg_glos;
    }

    public function setKgGlosado($kgglos){
        $this->kg_glos = $kgglos;
    }

    public function getValorGlosado(){
        return $this->valor_glos;
    }

    public function setValorGlosado($valorglos){
        $this->valor_glos = $valorglos;
    }

    public function getKgVend(){
        return $this->kg_vend;
    }

    public function setKgVend($kgvend){
        $this->kg_vend = $kgvend;
    }

    public function getValorVend(){
        return $this->valor_vend;
    }

    public function setValorVend($valorvend){
        $this->valor_vend = $valorvend;
    }

    public function getCnpjEmp(){
		return $this->cnpj_emp;	
	}

	public function setCnpjEmp($cnpjemp){
		$this->cnpj_emp = $cnpjemp;
	}

}
?>
<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class ProdFrigTxt2DAO{

	private $dba;

	public function __construct(){
		$dba = new MysqliDb(Array (
            'host' => ''.HOST.'',
            'username' => ''.USER.'', 
            'password' => ''.SENHA.'',
            'db'=> ''.BD.'',
            'port' => 3306,
            'prefix' => '',
            'charset' => 'utf8'));

		$this->dba = $dba;
	}

    public function ListaRelacionado($cod,$cnpj){
        $db = $this->dba;
        //$db->setTrace (true);
        $db->join("produtos_agregar a", "(a.codigo = p.cod_secretaria)", "LEFT");
        $db->where ("p.cod_prod","{$cod}");
        $db->where ("p.cnpj_emp","{$cnpj}");
        $cols = Array (
            "p.id",
            "p.cod_prod",
            "p.desc_prod",
            "a.descricao",
            "p.cod_secretaria",
            "p.cnpj_emp",
            "a.tipo"    
        );
        
        $results = $db->get('prodfrigtxt p',null,$cols);

        return $results;
        
    }

    public function ListaRelacionadoTodos($cnpj){
        $db = $this->dba;
        //$db->setTrace (true);
        $db->join("produtos_agregar a", "(a.codigo = p.cod_secretaria)", "LEFT");
        //$db->where ("p.cod_prod","{$cod}");
        $db->where ("p.cnpj_emp","{$cnpj}");
        $cols = Array (
            "p.id",
            "p.cod_prod",
            "p.desc_prod",
            "a.descricao",
            "p.cod_secretaria",
            "p.cnpj_emp",
            "a.tipo"    
        );
        
        $results = $db->get('prodfrigtxt p',null,$cols);

        return $results;
        
    }

}
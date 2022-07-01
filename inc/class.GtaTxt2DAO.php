<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class GtaTxt2DAO{

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

    public function GtaEmpresas($cnpj){
        $db = $this->dba;

        $db->where ("t.cnpj_emp","{$cnpj}");
        $cols = Array (
            "t.id",
            "t.numero_nota", 
			"t.numero_gta"  
        );
        
        $results = $db->get('gtatxt t',null,$cols);

        return $results;
    }

}
?>
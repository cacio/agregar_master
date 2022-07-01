<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class Cfop2DAO{

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


    public function VerificaCfopDevolucaoSN($codigo){
        $db = $this->dba;
        $db->where ('Codigo',$codigo);        
        $results = $db->get ('cfop');
        return $results;
    }

    

    
}
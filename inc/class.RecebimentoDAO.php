<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class RecebimentoDAO{

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

    public function Gravar($data){
        $db = $this->dba;
        $id = $db->insert('tbreceber', $data);
    } 

    public function Excluir($id){
        $db = $this->dba;
        
        $db->where('id', $id);
        $db->delete('tbreceber');
        
        return $id;

    }

    public function ListaRecebimentoPorDup($id){
        $db = $this->dba;
		$db->setTrace (true);
        $db->join("forma_pagto f", "f.id=t.tppagto", "LEFT");
        $db->where ("t.numero", $id);
        $tbreceber = $db->get('tbreceber t');
		
		//print_r ($db->trace);
        return $tbreceber;

    }

    public function ListaRecebimentoUm($id){
        $db = $this->dba;
		$db->setTrace (true);

        $row = array(
            't.id',
            't.numero',
            'CONCAT(t.tppagto, " - ", f.nome) AS id_formarecbto',
            't.valor',
        );

        $db->join("forma_pagto f", "f.id=t.tppagto", "LEFT");
        $db->where ("t.numero", $id);
        $tbreceber = $db->get('tbreceber t',null, $row);
		
		//print_r ($db->trace);
        return $tbreceber;


    }

}
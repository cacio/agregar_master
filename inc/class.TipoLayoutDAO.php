<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class TipoLayoutDAO{
    
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

    public function getTipoLayout($idemp){

        $db = $this->dba;
        //$db->setTrace (true);
        $db->where ('id_emp',$idemp);
        $results = $db->get('apuracao_layout');
        //print_r ($db->trace);

        return $results;

    }

    public function Gravar($data){
        $db = $this->dba;

        $id = $db->insert ('apuracao_layout', $data);

        return $id;
    }

    public function alterar($data,$id){
        $db = $this->dba;
        
        $db->where ('id', $id);
        $db->update ('apuracao_layout', $data);

        return $db->count;

    }

}
?>
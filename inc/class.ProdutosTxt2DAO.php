<?php
    require_once('inc.autoload.php');
    require_once('inc.connect.php');
    class ProdutosTxt2DAO{

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


        public function ListaProdutosProprio($cnpj){
            $db = $this->dba;
            
            $db->where('cnpj_emp',$cnpj);
            $vet = $db->get('produtotxt');

            return $vet;
        }

    }

?>
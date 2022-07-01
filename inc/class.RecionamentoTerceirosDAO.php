<?php
    require_once('inc.autoload.php');
    require_once('inc.connect.php');

    class RecionamentoTerceirosDAO{
        
        private $dba;
        private $table;

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

            $this->table = "relaciona_terceiros";

        }

        public function VerificaRelacionamento($indForn,$cprod,$cnpj){
            $db = $this->dba;     
           // $db->setTrace (true);
            $db->where('cnpj_ie_terceiros',$indForn);
            $db->where('idprodterceiros',$cprod);
            $db->where('cnpj_emp',$cnpj);

            $vet = $db->get($this->table);
            //print_r ($db->trace);
            return $vet;

        }
		
		public function VerificaRelacionamentoEmpresa($indForn,$cprod,$cnpj){
            $db = $this->dba;     
           // $db->setTrace (true);
            $db->where('cnpj_ie_terceiros',$indForn);
            $db->where('idprodterceiros',$cprod);
            $db->where('cnpj_emp',$cnpj);

            $vet = $db->get($this->table);
            //print_r ($db->trace);
            return $vet;

        }
		
        public function HandlerInsert($data){
            $db = $this->dba;

            $id = $db->insert ($this->table, $data);
            
            return $id;
        }
       

    }

?>
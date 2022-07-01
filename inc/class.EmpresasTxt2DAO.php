<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class EmpresasTxt2DAO{

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

    public function VerificaEmpresasCadastradas($cnpj){
        $db = $this->dba;
        //$db->setTrace (true);
                
        $db->where ('e.cnpj_emp',$_SESSION['cnpj']);
       
        $cols = Array (
            "CONCAT(e.cnpj_cpf,e.insc_estadual) as val",            
        );
        
        $results = $db->get ('empresastxt e',null,$cols);

        return $results;
        
    }

    public function InconsistenciaCadastrodeEmpresas($cnpj){
        $db = $this->dba;

        $db->where("cnpj_emp","{$cnpj}");
		$db->where("(uf = ? or insc_estadual = ? or cidade = ?)",array('','',''));					
		$results = $db->get ('empresastxt');

        return $results;
    }

    public function ListViewCli($cnpj){
        $db = $this->dba;

        $db->where("cnpj","{$cnpj}");	
        
        $cols = Array (
            "razao_social",
            "fantasia",
            "cnpj",
            "email"            
        );

		$results = $db->get ('empresas',null,$cols);

        return $results;
    }


   public function formatCnpjCpf($value)
    {
    $CPF_LENGTH = 11;
    $cnpj_cpf = preg_replace("/\D/", '', $value);
    
    if (strlen($cnpj_cpf) === $CPF_LENGTH) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    } 
    
    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }
   
}
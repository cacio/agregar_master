<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class Usuario2DAO{

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

    public function VerificaSenhaAtual($id,$cnpj){
        $db = $this->dba;
        $db->setTrace (true);
        //$db->join("empresas e", "(e.id = u.id_empresa)", "INNER");        
        $db->where ('u.id_empresa',$id);       
        
        $cols = Array (
                "u.id",
                "u.senha",            
        );
        
        $results = $db->get ('usuario u',null,$cols);
       //print_r ($db->trace);
        return $results;
        
    }
    
    public function VerificaSenhaFraca($id){
        $db = $this->dba;
        $db->setTrace (true);
        //$db->join("empresas e", "(e.id = u.id_empresa)", "INNER");        
        $db->where ('u.id_empresa',$id);               
        
        $cols = Array (
                "case 
                when sha1('12345') = senha then
                    'true'
                when sha1('123') = senha then		
                    'true'
                when cast(sha1(concat(Upper(substr(nome, 1,1)),'',  trim(LOWER(substr(nome, 2,2))),'', substr(REPLACE(REPLACE(REPLACE(login,'/',''), '-',''),'.',''),6,3))) as CHAR) = senha then		
                    'true'
                else
                    'false' end as valid",                 
        );
        
        $results = $db->get ('usuario u',null,$cols);
       //print_r ($db->trace);
        return $results;
        
    }

    public function AtualizaPass($id,$pass){
        $db = $this->dba;
        $db->setTrace (true);
        $daofunc = new FuncoesDAO();

        $data = Array (
            'senha' => ''.$pass.'',          
        );

        $db->where ('id', $id);
        
        if ($db->update ('usuario', $data))
            echo $daofunc->ajaxresponse("message",[
                "type"=>"success",
                "message"=>"Senha alterado(a) com sucesso!",								
            ]);
        else            
            echo $daofunc->ajaxresponse("message",[
                "type"=>"error",
                "message"=>"Alteração falhou {$db->getLastError()} ",								
            ]);
    }

   
}
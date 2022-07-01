<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class CfopEmpresa2DAO{

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


    public function ListaCfopGeraAgregarSN($cnpj){
        $db = $this->dba;
        $db->where ('cnpj_emp',$cnpj);
        $cols = Array ("if(gera_agregar = 1 , 'sim','nao') as sn","id_cfop");
        $results = $db->get ('cfop_empresa',null, $cols);
        return $results;
    }

    public function ListaCfopPorEmpresaUm($cnpj,$cfop){
        $db = $this->dba;
        $db->where ('cnpj_emp',$cnpj);
        $db->where ('id_cfop',$cfop);
        $results = $db->get ('cfop_empresa');
        return $results;

    }

    public function ListaCfopEmpresas($cnpj){
        $db = $this->dba;
        $colscfop  = array(
            "c.Codigo",
            "c.Nome",
            "(SELECT 
                        e.gera_agregar
                    FROM
                        cfop_empresa e
                    WHERE
                        e.id_cfop = c.Codigo
                            AND e.cnpj_emp = {$cnpj} limit 1) AS gera",
            "(SELECT 
                    'SIM'
                FROM
                    cfop_empresa e
                WHERE
                    e.id_cfop = c.Codigo
                        AND e.cnpj_emp = {$cnpj} limit 1) AS vinculado",
            "(SELECT 
                    e.id
                FROM
                    cfop_empresa e
                WHERE
                    e.id_cfop = c.Codigo
                        AND e.cnpj_emp = {$cnpj} limit 1) AS idvinculado"							
        );
        
        $resultscfop = $db->get ('cfop c',null, $colscfop);

        return $resultscfop;

    }

    public function RemoveCfopEmpresa($cnpj,$id){
        $db = $this->dba;
        $db->where ('id',$id);
        $db->where ('cnpj_emp',$cnpj);        
        $db->delete('cfop_empresa');
    }
}
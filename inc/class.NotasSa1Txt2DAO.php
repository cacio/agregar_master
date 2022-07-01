<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class NotasSa1Txt2DAO{

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

    public function ListaNotasSa1Detalhe($cnpj,$mesano){
        $db = $this->dba;
        $db->setTrace (true);

        $db->where ("concat(lpad(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao))","{$mesano}");
        $db->where ("s.cnpj_emp","{$cnpj}");
        $cols = Array (
            "s.id",
            "s.numero_nota",
            "s.data_emissao",
            "s.cnpj_cpf",
            's.codigo_produto',
            "s.qtd_pecas",
            "s.peso",
            "s.preco_unitario",
            "s.ent_sai",
            "s.numero_item_nota",
            "s.insc_estadual",
            's.cfop',
            "s.aliquota_icms",
            "s.cnpj_emp"  
        );

        $results = $db->get('notassa1txt s',null,$cols);
        
        return $results;
    }

    public function ListaNotasSa1DetalheNumero($cnpj,$numero){
        $db = $this->dba;
        $db->setTrace (true);

        $db->join("cfop c", "(c.Codigo = s.cfop)", "LEFT");
        $db->join("produtotxt p", "(p.cod_prod = s.codigo_produto) and (p.cnpj_emp = s.cnpj_emp)", "INNER");        
        $db->where ("s.numero_nota","{$numero}");
        $db->where ("s.cnpj_emp","{$cnpj}");        
        $results = $db->get('notassa1txt s');
        //print_r ($db->trace);
        return $results;
    }

    public function pegaIdsParaRemovecao($numero,$cnpj){
        $db = $this->dba;
        $db->setTrace (true);

        $db->where ("s.numero_nota","{$numero}");
        $db->where ("s.cnpj_emp","{$cnpj}");    
        
        $cols = Array (
            "s.id",         
        );

        $results = $db->get('notassa1txt s',null,$cols);
        //print_r ($db->trace);
        return $results;
    }
	
	public function pegaIdItemNota($numero,$nitem,$cnpj){
        $db = $this->dba;
        $db->setTrace (true);

        $db->where ("s.numero_nota","{$numero}");
        $db->where ("s.numero_item_nota","{$nitem}");
        $db->where ("s.cnpj_emp","{$cnpj}");    
        //$db->where ("s.codigo_produto","");
		
        $cols = Array (
            "s.id",         
        );

        $results = $db->get('notassa1txt s',null,$cols);
       // print_r ($db->trace);
        return $results;
    }
	
	public function pegaItemNota($numero,$nitem,$mesano,$cnpj){
        $db = $this->dba;
        //$db->setTrace (true);

        $db->where ("s.numero_nota","{$numero}");
        $db->where ("s.numero_item_nota","{$nitem}");
        $db->where ("s.cnpj_emp","{$cnpj}");    
        $db->where ("concat(lpad(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao))","{$mesano}");

        $cols = Array (
            "s.id",
            "s.codigo_produto",         
        );

        $results = $db->get('notassa1txt s',null,$cols);
        //print_r ($db->trace);
        return $results;
    }
	
    public function UpdateItemNotaProdutoTerceiro($data,$id){
        $db = $this->dba;

        $db->where ('id', $id);    
        $db->update ('notassa1txt', $data);
        return $db->count;

    }

    public function removeItens($id,$cnpj){
        $db = $this->dba;
        $db->where('id', $id);
        $db->where('cnpj_emp', $cnpj);
        $db->delete('notassa1txt');
        return true;
    }

}
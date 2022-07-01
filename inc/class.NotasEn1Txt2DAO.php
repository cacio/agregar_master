<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class NotasEn1Txt2DAO{

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

    public function ListaNotasEntradaDetalhe($cnpj,$mesano){

        $db = $this->dba;
        $db->where ("concat(lpad(EXTRACT(MONTH FROM n.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM n.data_emissao))","{$mesano}");
        $db->where ("n.cnpj_emp","{$cnpj}");
        $cols = Array (
            "n.id",
            "n.numero_nota",
            "n.data_emissao",
            "n.cnpj_cpf",
            "n.codigo_produto",
            "n.qtd_cabeca",
            "n.peso_vivo_cabeca",
            "n.peso_carcasa",
            "n.preco_quilo",
            "n.numero_item_nota",
            "n.insc_estadual",
            "n.data_abate",
            "n.tipo_r_v",
            "n.cfop",
            "n.aliquota_icms",
            "n.cnpj_emp"    
        );

        $results = $db->get('notasen1txt n',null,$cols);

        return $results;        

    }
    
    public function VerificaDataAbateTxt($mesano,$cnpjemp,$numero_nota,$cprod,$numitem){
        $db = $this->dba;
        //$db->setTrace (true);
        $db->join("prodfrigtxt p", "(p.cod_prod = s.codigo_produto and p.cnpj_emp = s.cnpj_emp)", "INNER");
        $db->where ("CONCAT(LPAD(EXTRACT(MONTH FROM s.data_emissao), 2, '0'), '/', EXTRACT(YEAR FROM s.data_emissao))","{$mesano}");
        $db->where ("s.cnpj_emp","{$cnpjemp}");
        $db->where ("s.numero_nota","{$numero_nota}");
        $db->where ("s.codigo_produto","{$cprod}");
        $db->where ("cast(s.numero_item_nota as UNSIGNED)","{$numitem}");
        $cols = Array (
            "s.data_emissao",
            "s.data_abate",
            "p.desc_prod" 
        );

        $results = $db->get('notasen1txt s',1,$cols);

        return $results;
        
    }

    public function VerificaCabecasPreenchidas($nnota,$cod,$cnpj,$nitem){
        $db = $this->dba;
        $db->setTrace (true);
        $db->join("prodfrigtxt p", "(p.cod_prod = n.codigo_produto)", "LEFT");
        $db->join("produtos_agregar a", "(a.codigo = p.cod_secretaria)", "LEFT");
        $db->join("cfop c", "(c.Codigo = n.cfop)", "INNER");
        $db->where ("n.numero_nota","{$nnota}");
        $db->where ("n.codigo_produto","{$cod}");
        $db->where ("n.cnpj_emp","{$cnpj}");
        $db->where ("p.cnpj_emp","{$cnpj}");
        $db->where ("cast(n.numero_item_nota as UNSIGNED)","{$nitem}");
        $db->where ("COALESCE(p.cod_secretaria ,0)","99999","<>");
        $db->where ("COALESCE(p.cod_secretaria ,0)","99998","<>");
        $db->where ("COALESCE(p.cod_secretaria ,0)","99997","<>");
        $db->where ("COALESCE(p.cod_secretaria ,0)","99996","<>");
        $db->where ("c.devolucao","S","<>");

        $cols = Array (
            "n.qtd_cabeca",
            "n.tipo_r_v",
            "n.peso_carcasa"
        );

        $results = $db->get('notasen1txt n',1,$cols);

        /*echo "<pre>";
        print_r($results);
        print_r ($db->trace);
        echo "</pre>";*/

        return $results;
       
    }   

    public function ListaNotasDetalhe($cnpj,$numero){
        
        $db = $this->dba;
        $db->setTrace (true);
        $db->join("cfop c", "(c.Codigo = n.cfop)", "LEFT");
        $db->join("produtotxt p", "(p.cod_prod = n.codigo_produto) and (p.cnpj_emp = n.cnpj_emp)", "INNER"); 
        $db->where ("n.numero_nota","{$numero}");
        $db->where ("n.cnpj_emp","{$cnpj}");  
        
        $cols = Array (
            "n.id",
            "n.numero_nota",
            "n.data_emissao",
            "n.cnpj_cpf",
            "n.codigo_produto",
            "n.qtd_cabeca",
            "n.peso_vivo_cabeca",
            "n.peso_carcasa",
            "n.preco_quilo",
            "n.numero_item_nota",
            "n.insc_estadual",
            "n.data_abate",
            "n.tipo_r_v",
            "n.cfop",
            "n.aliquota_icms",
            "c.Codigo",
            "c.Nome",
            "p.cod_prod",
            "p.desc_prod"   
        );


        $results = $db->get('notasen1txt n',null,$cols);
        //print_r ($db->trace);
        return $results;        

    }

    public function pegaIds($cnpj,$numero){
        $db = $this->dba;
        $db->setTrace (true);

        $db->where ("n.numero_nota","{$numero}");
        $db->where ("n.cnpj_emp","{$cnpj}");        
        $results = $db->get('notasen1txt n');
        //print_r ($db->trace);
        return $results;        

    }

    public function removeitens($id,$cnpj){
        $db = $this->dba;
        $db->setTrace (true);
        $db->where('id', "{$id}");
         $db->where('cnpj_emp', "{$cnpj}");
        $db->delete('notasen1txt');
        //print_r ($db->trace);
        return true;
    }
}
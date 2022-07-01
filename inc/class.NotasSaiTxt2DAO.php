<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class NotasSaiTxt2DAO{

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

    public function ListandoNotasSai($mesano,$cnpj){
        $db = $this->dba;
       // $db->setTrace (true);
        $db->join("empresastxt e", "(e.cnpj_cpf = t.cnpj_cpf and e.insc_estadual = t.insc_estadual)", "LEFT");
        $db->where ("concat(lpad(EXTRACT(MONTH FROM t.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM t.data_emissao)) ","{$mesano}");
        $db->where ("t.cnpj_emp","{$cnpj}");
        $cols = Array (
					"t.id",
					"t.numero_nota",
					"t.data_emissao",
					"t.cnpj_cpf",
					"t.valor_total_nota",
					"(select cast(sum(s.peso * s.preco_unitario) as DECIMAL(10,2))  from notassa1txt s where s.numero_nota = t.numero_nota and s.cnpj_emp = t.cnpj_emp) as totprod",
					"t.valor_icms",
					"t.valor_icms_subs",
					"t.ent_sai",
					"t.insc_estadual",
					't.cfop',
                    "e.razao",
                    "t.xml",
                    "t.chave_acesso"  
        );
        $db->groupBy('t.id');
        $results = $db->get('notassaitxt t',null,$cols);
        
        return $results;
    }


    public function buscaidalteracao($numero,$cnpj){
        $db = $this->dba;
       // $db->setTrace (true);
        $db->join("empresastxt e", "(e.cnpj_cpf = t.cnpj_cpf and e.insc_estadual = t.insc_estadual)", "LEFT");
        $db->where ("t.numero_nota","{$numero}");
        $db->where ("t.cnpj_emp","{$cnpj}");
        $db->where ("e.cnpj_emp","{$cnpj}");

        $cols = Array (
            "t.id",
            "t.numero_nota",
            "t.data_emissao",
            "t.cnpj_cpf",
            "t.valor_total_nota",        
            "t.valor_icms",
            "t.valor_icms_subs",
            "t.ent_sai",
            "t.insc_estadual",
            't.cfop',
            "e.razao",
            "e.id as idemp",            
            );

        $results = $db->get('notassaitxt t',null,$cols);
      //  print_r ($db->trace);
        return $results;
    }

    public function buscaId($numero,$cnpj){
        $db = $this->dba;
       // $db->setTrace (true);        
        $db->where ("t.numero_nota","{$numero}");
        $db->where ("t.cnpj_emp","{$cnpj}");        
        $cols = Array (
            "t.id",
            "t.numero_nota",            
            );

        $results = $db->get('notassaitxt t',null,$cols);
      //  print_r ($db->trace);
        return $results;
    }

    
	
	public function getDevolucaoVendas($cnpj,$mesano,$perc){
        $db = $this->dba;
        $results = $db->rawQuery('select COALESCE(GETDEVOLUCAO("'.$cnpj.'","'.$mesano.'","E","'.$perc.'"), 0) as valor');
        return $results;
    }

}
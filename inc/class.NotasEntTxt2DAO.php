<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class NotasEntTxt2DAO{

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


    public function ListaNotasDeEntradas($cnpj,$mesano){
        $db = $this->dba;
        $db->join("notasen1txt en", "(en.numero_nota = n.numero_nota and en.cnpj_emp = n.cnpj_emp)", "LEFT");
        $db->join("empresastxt e", "(e.cnpj_cpf = n.cnpj_cpf and e.insc_estadual = n.insc_produtor)", "LEFT");
        $db->where("(concat(lpad(EXTRACT(MONTH FROM n.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM n.data_emissao)) = ? or 
        concat(lpad(EXTRACT(MONTH FROM en.data_abate),2,'0'),'/',EXTRACT(YEAR FROM en.data_abate)) = ? )", array(''.$mesano.'',''.$mesano.''));	
        $db->where ("n.cnpj_emp","{$cnpj}");
        $db->where ("e.cnpj_emp","{$cnpj}");
        
        $cols = Array (
                "n.id", 
                "n.numero_nota", 
                "n.data_emissao",
                "en.data_abate",
                "n.cnpj_cpf",
                "n.tipo_v_r_a",
                "n.valor_total_nota",
                "n.gta",
                "n.numero_nota_produtor_ini",
                "n.numero_nota_produtor_fin",
                "n.condenas",
                "n.abate",
                "n.insc_produtor",
                "n.cnpj_emp",
                "e.razao",
                "n.chave_acesso",
                "n.xml",
                "(SELECT 
                SUM(CAST(CASE
                            WHEN en1.tipo_r_v = 'V' THEN en1.preco_quilo * en1.peso_vivo_cabeca
                            ELSE en1.preco_quilo * en1.peso_carcasa
                        END
                        AS DECIMAL (10 , 2 ))) AS valorproduto
            FROM
                notasen1txt en1
            WHERE
                en1.numero_nota = n.numero_nota
                    AND en1.cnpj_emp = n.cnpj_emp) AS valorproduto");
        $db->groupBy ("n.id", "n.numero_nota", "n.data_emissao","en.data_abate","n.cnpj_cpf","n.tipo_v_r_a","n.valor_total_nota","n.gta","n.numero_nota_produtor_ini","n.numero_nota_produtor_fin","n.condenas","n.abate","n.insc_produtor","n.cnpj_emp","e.razao","n.chave_acesso","n.xml");
        $results = $db->get('notasenttxt n',null,$cols);

        return $results;
    }

    public function buscaid($numero,$cnpj){
        $db = $this->dba;
        //$db->setTrace (true);
        $db->where ("n.numero_nota","{$numero}");
        $db->where ("n.cnpj_emp","{$cnpj}");

        $cols = Array (
            "n.id",
            "n.numero_nota",
        );

        $results = $db->get('notasenttxt n',null,$cols);
        //print_r ($db->trace);
        return $results;
    }

    public function buscaidalteracao($numero,$cnpj){
        $db = $this->dba;
        $db->setTrace (true);
        $db->join("empresastxt e", "(e.cnpj_cpf = n.cnpj_cpf and e.insc_estadual = n.insc_produtor) AND (e.cnpj_emp = n.cnpj_emp)", "LEFT");
        $db->where ("n.numero_nota","{$numero}");
        $db->where ("n.cnpj_emp","{$cnpj}");
        $cols = Array (
            "n.id",
            "n.numero_nota",
            "n.data_emissao",
            "n.cnpj_cpf",
            "n.tipo_v_r_a",        
            "n.valor_total_nota",
            "n.gta",
            "n.numero_nota_produtor_ini",
            "n.numero_nota_produtor_fin",
            'n.condenas',
            'n.abate',
            'n.insc_produtor',
            "e.razao",
            "e.id as idemp",            
            );
        $results = $db->get('notasenttxt n',null,$cols);
        //print_r ($db->trace);
        return $results;
    }
}
?>
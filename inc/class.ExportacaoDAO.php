<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class ExportacaoDAO{
    private $dba;
    
    public function __construct(){
        
        $dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
        $this->dba = $dba;
        
    }
    

    public function ListaExportacaoCompetencia($cnpj){

        $dba = $this->dba;
		
		$vet = array();
		
		$sql = 'SELECT 
                    e.id,
                    e.competencia,    
                    e.kg_glos,
                    e.valor_glos,
                    e.kg_vend,
                    e.valor_vend,
                    p.nome_pt
                FROM
                    exportacao e
                inner join pais p on (p.id = e.id_pais) 
                where e.cnpj_emp = "'.$cnpj.'" ';
		
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i < $num; $i++){
			
			$id         = $dba->result($res,$i,'id');
            $comp       = $dba->result($res,$i,'competencia');
            $kg_glos    = $dba->result($res,$i,'kg_glos');
            $valor_glos = $dba->result($res,$i,'valor_glos');
            $kg_vend    = $dba->result($res,$i,'kg_vend');
            $valor_vend = $dba->result($res,$i,'valor_vend');
            $nome_pt    = $dba->result($res,$i,'nome_pt');
			
			$expo = new Exportacao();
            
            $expo->setCodigo($id);
			$expo->setCompetencia($comp);
            $expo->setPais($nome_pt);
            $expo->setKgGlosado($kg_glos);
            $expo->setValorGlosado($valor_glos);
            $expo->setKgVend($kg_vend);
            $expo->setValorVend($valor_vend);
            
			$vet[$i] = $expo;
			
		}
		return $vet;

    }

    public function ListaExportacaoCompetenciaAlteracao($cnpj,$id){

        $dba = $this->dba;
		
		$vet = array();
		
		$sql = 'SELECT 
                    e.id,
                    e.competencia,    
                    e.kg_glos,
                    e.valor_glos,
                    e.kg_vend,
                    e.valor_vend,
                    e.id_pais
                FROM
                    exportacao e                
                where e.cnpj_emp = "'.$cnpj.'" and e.id = "'.$id.'" ';
		
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i < $num; $i++){
			
			$id         = $dba->result($res,$i,'id');
            $comp       = $dba->result($res,$i,'competencia');
            $kg_glos    = $dba->result($res,$i,'kg_glos');
            $valor_glos = $dba->result($res,$i,'valor_glos');
            $kg_vend    = $dba->result($res,$i,'kg_vend');
            $valor_vend = $dba->result($res,$i,'valor_vend');
            $id_pais    = $dba->result($res,$i,'id_pais');
			
			$expo = new Exportacao();
            
            $expo->setCodigo($id);
			$expo->setCompetencia($comp);
            $expo->setPais($id_pais);
            $expo->setKgGlosado($kg_glos);
            $expo->setValorGlosado($valor_glos);
            $expo->setKgVend($kg_vend);
            $expo->setValorVend($valor_vend);
            
			$vet[$i] = $expo;
			
		}
		return $vet;

    }

    public function VerificaCompPais($cnpj,$comp,$idpais){

        $dba = $this->dba;
		
		$vet = array();
		
        $sql = 'SELECT 
                    e.id                    
                FROM
                    exportacao e                
                where 
                    e.cnpj_emp = "'.$cnpj.'" and 
                    e.competencia = "'.$comp.'" and 
                    e.id_pais = "'.$idpais.'" ';
		
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i < $num; $i++){
			
			$id         = $dba->result($res,$i,'id');

			$expo = new Exportacao();
            
            $expo->setCodigo($id);

			$vet[$i] = $expo;
			
		}
		return $vet;

    }

    public function ComputaCompetenciaExportacao($mesano,$cnpj){

        $dba = $this->dba;
		
		$vet = array();
		
		$sql = 'SELECT 
                    SUM(e.valor_glos) AS valor_glos
                FROM
                    exportacao e
                WHERE
                    e.competencia = "'.$mesano.'"
                        AND e.cnpj_emp = "'.$cnpj.'" ';
		
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i < $num; $i++){
						
            $valor_glos = $dba->result($res,$i,'valor_glos');
            			
			$expo = new Exportacao();
            
            $expo->setValorGlosado($valor_glos);
            
			$vet[$i] = $expo;
			
		}
		return $vet;

    }

    public function ListaComputaCompetenciaExportacao($mesano,$cnpj){

        $dba = $this->dba;
		
		$vet = array();
		
		$sql = 'SELECT 
                    p.nome_pt,
                    e.valor_glos    
                FROM
                    exportacao e
                    inner join pais p on (p.id = e.id_pais) 
                WHERE
                    e.competencia = "'.$mesano.'"
                        AND e.cnpj_emp = "'.$cnpj.'" ';
		
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i < $num; $i++){
						
            $valor_glos = $dba->result($res,$i,'valor_glos');
            $nome_pt    = $dba->result($res,$i,'nome_pt');

			$expo = new Exportacao();
            
            $expo->setValorGlosado($valor_glos);
            $expo->setPais($nome_pt);

			$vet[$i] = $expo;
			
		}
		return $vet;

    }

    public function Inserir($expo){
        $dba = $this->dba;

        $comp       = $expo->getCompetencia();
        $id_pais    = $expo->getPais();
        $kg_glos    = $expo->getKgGlosado();
        $valor_glos = $expo->getValorGlosado();
        $kg_vend    = $expo->getKgVend();
        $valor_vend = $expo->getValorVend();
        $cnpj_emp   = $expo->getCnpjEmp();

        $sql = "INSERT INTO `exportacao`
                (`competencia`,
                `id_pais`,
                `kg_glos`,
                `valor_glos`,
                `kg_vend`,
                `valor_vend`,
                `cnpj_emp`)
                VALUES
                ('".$comp."',
                ".$id_pais.",
                ".$kg_glos.",
                ".$valor_glos.",
                ".$kg_vend.",
                ".$valor_vend.",
                '".$cnpj_emp."')";

        $res = $dba->query($sql);

    }
  
    
    public function Update($expo){
        $dba = $this->dba;

        $id         = $expo->getCodigo();
        $comp       = $expo->getCompetencia();
        $id_pais    = $expo->getPais();
        $kg_glos    = $expo->getKgGlosado();
        $valor_glos = $expo->getValorGlosado();
        $kg_vend    = $expo->getKgVend();
        $valor_vend = $expo->getValorVend();
        $cnpj_emp   = $expo->getCnpjEmp();

        $sql = "UPDATE `exportacao`
                SET
                `competencia` = '".$comp."',
                `id_pais` = ".$id_pais.",
                `kg_glos` = ".$kg_glos.",
                `valor_glos` = ".$valor_glos.",
                `kg_vend` = ".$kg_vend.",
                `valor_vend` = ".$valor_vend."
                WHERE `id` = '".$id."' and 
                      `cnpj_emp` = '".$cnpj_emp."' ";
        //echo $sql;
        $res = $dba->query($sql);

    }

    public function Delete($expo){
        $dba = $this->dba;

        $id  = $expo->getCodigo();
        
        $sql = "DELETE FROM `exportacao`
                WHERE `id` = ".$id." ";

        $res = $dba->query($sql);

    }
    
}
?>
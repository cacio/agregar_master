<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class CfopDAO{

	private $dba;

	public function __construct(){
		
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
		
	}
	
	public function listacfop(){
	
		$dba = $this->dba;
		
		$vet = array();
		
		$sql = 'SELECT * 
				FROM cfop';
		
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i < $num; $i++){
			
			$idn = $dba->result($res,$i,'id');
			$cod = $dba->result($res,$i,'Codigo');
			$nom = $dba->result($res,$i,'Nome');
			
			$nat = new Cfop();
			
			$nat->setId($idn);
			$nat->setCodigo($cod);
			$nat->setNome($nom);
			
			$vet[$i] = $nat;
			
		}
		return $vet;
	}
	public function buscacfop($cfop){
		$dba = $this->dba;
		
		$vet = array();
		

		
		$sql = 'SELECT * FROM cfop
				where Codigo like "%'.$cfop.'%" ';
		
				
				
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i < $num; $i++){
			
			$idn         = $dba->result($res,$i,'id');
			$cod         = $dba->result($res,$i,'Codigo');
			$nom         = $dba->result($res,$i,'Nome');
			$dadosadfisc = $dba->result($res,$i,'dados_ad_fisc');
			$devolucao   = $dba->result($res,$i,'devolucao');
			
			
			$nat = new Cfop();
			
			$nat->setId($idn);
			$nat->setCodigo($cod);
			$nat->setNome($nom);
			$nat->setDadosadfisco($dadosadfisc);
			$nat->setDevolucao($devolucao);

			$vet[$i] = $nat;
			
		}
		return $vet;
		
	
	}
	public function listaCfopum($codn){
	
		$dba = $this->dba;
		
		$vet = array();
		

		
		$sql = 'SELECT * 
				FROM cfop 
				WHERE id ='.$codn;
		
				echo $sql;
				
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i < $num; $i++){
			
			$idn = $dba->result($res,$i,'id');
			$cod = $dba->result($res,$i,'Codigo');
			$nom = $dba->result($res,$i,'Nome');
			$dadosadfisc = $dba->result($res,$i,'dados_ad_fisc');
			
			
			
			$nat = new Cfop();
			
			$nat->setId($idn);
			$nat->setCodigo($cod);
			$nat->setNome($nom);
			$nat->setDadosadfisco($dadosadfisc);
			
			$vet[$i] = $nat;
			
		}
		return $vet;
	}
	public function listanaturezacodum($codn){
	
		$dba = $this->dba;
		
		$vet = array();
		

		
		$sql = 'SELECT * 
				FROM cfop 
				WHERE Codigo ='.$codn;
		
				
				
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i < $num; $i++){
			
			$idn = $dba->result($res,$i,'id');
			$cod = $dba->result($res,$i,'Codigo');
			$nom = $dba->result($res,$i,'Nome');
			$dadosadfisc = $dba->result($res,$i,'dados_ad_fisc');
			
			
			
			$nat = new Cfop();
			
			$nat->setId($idn);
			$nat->setCodigo($cod);
			$nat->setNome($nom);
			$nat->setDadosadfisco($dadosadfisc);
			
			$vet[$i] = $nat;
			
		}
		return $vet;
	}
	public function autocompletenatura($src){
		$dba = $this->dba;
		
		$vet = array();
		

		
		$sql = 'SELECT * 
				FROM cfop 
				WHERE 
				Codigo like "%'.$src.'%" or 
				Nome like "%'.$src.'%"';
		
				
	
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i < $num; $i++){
			
			$idn = $dba->result($res,$i,'id');
			$cod = $dba->result($res,$i,'Codigo');
			$nom = $dba->result($res,$i,'Nome');
			$dadosadfisc = $dba->result($res,$i,'dados_ad_fisc');
			
			
			
			$nat = new Cfop();
			
			$nat->setId($idn);
			$nat->setCodigo($cod);
			$nat->setNome(utf8_encode($nom));
			$nat->setDadosadfisco($dadosadfisc);
			
			$vet[$i] = $nat;
			
		}
		return $vet;
		
		
	
	}
	
	public function VerificaRelacionamento($codcfop,$cnpjemp){
		$dba = $this->dba;
		
		$vet = array();
		

		
		$sql = 'SELECT 
					c.Codigo,
					c.Nome,
					(SELECT 
							e.gera_agregar
						FROM
							cfop_empresa e
						WHERE
							e.id_cfop = c.Codigo
								AND e.cnpj_emp = "'.$cnpjemp.'" limit 1) AS gera,
					(SELECT 
							"SIM"
						FROM
							cfop_empresa e
						WHERE
							e.id_cfop = c.Codigo
								AND e.cnpj_emp = "'.$cnpjemp.'" limit 1) AS vinculado,
					(SELECT 
							e.id
						FROM
							cfop_empresa e
						WHERE
							e.id_cfop = c.Codigo
								AND e.cnpj_emp = "'.$cnpjemp.'" limit 1) AS idvinculado			
				FROM
					cfop c where c.Codigo = "'.$codcfop.'" ';
		
				
	
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i < $num; $i++){
						
			$cod  	   = $dba->result($res,$i,'Codigo');
			$nom  	   = $dba->result($res,$i,'Nome');			
			$gera 	   = $dba->result($res,$i,'gera');
			$vinculado = $dba->result($res,$i,'vinculado');
			$idvincula = $dba->result($res,$i,'idvinculado');
			
			$nat = new Cfop();
						
			$nat->setCodigo($cod);
			$nat->setNome(utf8_encode($nom));
			$nat->setGeraAgregar($gera);
			$nat->setVinculado($vinculado);
			$nat->setIdVinculado($idvincula);
			
			$vet[$i] = $nat;
			
		}
		return $vet;
		
		
	
	}
	
	public function ListaCfopVinculado($cnpjemp){
		$dba = $this->dba;
		
		$vet = array();
		

		
		$sql = 'SELECT 
					c.Codigo,
					c.Nome,
					(SELECT 
							e.gera_agregar
						FROM
							cfop_empresa e
						WHERE
							e.id_cfop = c.Codigo
								AND e.cnpj_emp = "'.$cnpjemp.'" limit 1) AS gera,
					(SELECT 
							e.id
						FROM
							cfop_empresa e
						WHERE
							e.id_cfop = c.Codigo
								AND e.cnpj_emp = "'.$cnpjemp.'" limit 1) AS vinculado
				FROM
					cfop c';
		
				
	
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i < $num; $i++){
						
			$cod  	   = $dba->result($res,$i,'Codigo');
			$nom  	   = $dba->result($res,$i,'Nome');			
			$gera 	   = $dba->result($res,$i,'gera');
			$vinculado = $dba->result($res,$i,'vinculado');
			
			$nat = new Cfop();
						
			$nat->setCodigo($cod);
			$nat->setNome(utf8_encode($nom));
			$nat->setGeraAgregar($gera);
			$nat->setVinculado($vinculado);
			
			$vet[$i] = $nat;
			
		}
		return $vet;
		
		
	
	}
	

	public function VerificaDevolucao($codn){
	
		$dba = $this->dba;
		
		$vet = array();
				
		$sql = 'select c.Codigo,c.devolucao from cfop c where  c.Codigo = "'.$codn.'" limit 1 ';				
				
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i < $num; $i++){
						
			$cod 	   = $dba->result($res,$i,'Codigo');
			$devolucao = $dba->result($res,$i,'devolucao');			
			
			$nat = new Cfop();
						
			$nat->setCodigo($cod);			
			$nat->setDevolucao($devolucao);

			$vet[$i] = $nat;
			
		}
		return $vet;
	}
	
	public function inserir($nat){
		
		$dba = $this->dba;
		
		$cod          = $nat->getCodigo();
		$nom          = $nat->getNome();
		$Dadosadfisco = $nat->getDadosadfisco();
		
		$sql = 'INSERT INTO cfop
							(Codigo,
							 Nome,
							 dados_ad_fisc)
							VALUES
							('.$cod.',
							"'.$nom.'",
							"'.$Dadosadfisco.'")';
		
		$dba->query($sql);
		
	}
	public function alterar($nat){
		
		$dba = $this->dba;
		
		$id  = $nat->getId();
		$cod = $nat->getCodigo();
		$nom = $nat->getNome();
		$Dadosadfisco = $nat->getDadosadfisco();
		
		$sql = 'UPDATE cfop
				SET
				Codigo =  '.$cod.',
				Nome = 	  "'.$nom.'",
				dados_ad_fisc = "'.$Dadosadfisco.'"
				WHERE id = '.$id.'';
		
		$dba->query($sql);
		
	}
	public function deletar($nat){
		
		$dba = $this->dba;
		
		$id  = $nat->getId();
		
		$sql = 'DELETE 
				FROM cfop
				where id='.$id;
		
		$dba->query($sql);
	}
}
?>
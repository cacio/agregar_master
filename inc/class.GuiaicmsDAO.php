<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class GuiaicmsDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	public function ListaGuiaicmsEmpresas($cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					g.id,
					g.COMPETENC,
					g.NUMERO,
					g.CODIGO,
					g.VALORPAGO,
					g.DATAPAGO,
					g.cnpj_emp,
					g.TIPO
				FROM
					guiaicms g
				WHERE g.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod  		= $dba->result($res,$i,'id');
			$competenc  = $dba->result($res,$i,'COMPETENC');
			$numero     = $dba->result($res,$i,'NUMERO');
			$codigo	    = $dba->result($res,$i,'CODIGO');
			$valorpago  = $dba->result($res,$i,'VALORPAGO');
			$datapago   = $dba->result($res,$i,'DATAPAGO');
			$cnpj_emp   = $dba->result($res,$i,'cnpj_emp');
			$tipo       = $dba->result($res,$i,'TIPO');


			$guia = new Guiaicms();

			$guia->setCodigo($cod);
			$guia->setCompetenc($competenc);
			$guia->setNumero($numero);
			$guia->setCodigoGuia($codigo);
			$guia->setValorPago($valorpago);
			$guia->setDataPago($datapago);
			$guia->setCnpjEmp($cnpj_emp);
			$guia->setTipo($tipo);
			
			$vet[$i] = $guia;

		}

		return $vet;

	}

	public function ListaGuiaicmsEmpresasUm($cnpj, $id){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					g.id,
					g.COMPETENC,
					g.NUMERO,
					g.CODIGO,
					g.VALORPAGO,
					g.DATAPAGO,
					g.cnpj_emp,
					g.TIPO
				FROM
					guiaicms g
				WHERE g.cnpj_emp = "'.$cnpj.'" and 
					  g.id = "'.$id.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod  		= $dba->result($res,$i,'id');
			$competenc  = $dba->result($res,$i,'COMPETENC');
			$numero     = $dba->result($res,$i,'NUMERO');
			$codigo	    = $dba->result($res,$i,'CODIGO');
			$valorpago  = $dba->result($res,$i,'VALORPAGO');
			$datapago   = $dba->result($res,$i,'DATAPAGO');
			$cnpj_emp   = $dba->result($res,$i,'cnpj_emp');
			$tipo       = $dba->result($res,$i,'TIPO');

			$guia = new Guiaicms();

			$guia->setCodigo($cod);
			$guia->setCompetenc($competenc);
			$guia->setNumero($numero);
			$guia->setCodigoGuia($codigo);
			$guia->setValorPago($valorpago);
			$guia->setDataPago($datapago);
			$guia->setCnpjEmp($cnpj_emp);
			$guia->setTipo($tipo);
			
			$vet[$i] = $guia;

		}

		return $vet;

	}

	public function ListaGuiaicmsCompetencia($cnpj,$dt){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					g.id,
					g.COMPETENC,
					g.NUMERO,
					g.CODIGO,
					g.VALORPAGO,
					g.DATAPAGO,
					g.cnpj_emp
				FROM
					guiaicms g
				WHERE g.cnpj_emp = "'.$cnpj.'" and 
					  g.COMPETENC = "'.$dt.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod  		= $dba->result($res,$i,'id');
			$competenc  = $dba->result($res,$i,'COMPETENC');
			$numero     = $dba->result($res,$i,'NUMERO');
			$codigo	    = $dba->result($res,$i,'CODIGO');
			$valorpago  = $dba->result($res,$i,'VALORPAGO');
			$datapago   = $dba->result($res,$i,'DATAPAGO');
			$cnpj_emp   = $dba->result($res,$i,'cnpj_emp');
			
			$guia = new Guiaicms();

			$guia->setCodigo($cod);
			$guia->setCompetenc($competenc);
			$guia->setNumero($numero);
			$guia->setCodigoGuia($codigo);
			$guia->setValorPago($valorpago);
			$guia->setDataPago($datapago);
			$guia->setCnpjEmp($cnpj_emp);
			
			
			$vet[$i] = $guia;

		}

		return $vet;

	}
	
	public function ValidGuiaicmsNormal($cnpj,$dt){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					g.id,
					g.COMPETENC,
					g.TIPO,    
					g.CODIGO,
					g.VALORPAGO    
				FROM
					guiaicms g
				WHERE
					g.COMPETENC = "'.$dt.'" AND 
							g.cnpj_emp = "'.$cnpj.'" and 
							g.TIPO = "1" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		
				
			$id			= $dba->result($res,$i,'id');
			$competenc  = $dba->result($res,$i,'COMPETENC');			
			$codigo	    = $dba->result($res,$i,'CODIGO');
			$valorpago  = $dba->result($res,$i,'VALORPAGO');						
			
			$guia = new Guiaicms();
			
			$guia->setId($id);
			$guia->setCompetenc($competenc);			
			$guia->setCodigoGuia($codigo);
			$guia->setValorPago($valorpago);			
			
			$vet[$i] = $guia;

		}

		return $vet;

	}
	
	public function ValidGuiaicmsSt($cnpj,$dt){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					g.id,
					g.COMPETENC,
					g.TIPO,    
					g.CODIGO,
					g.VALORPAGO    
				FROM
					guiaicms g
				WHERE
					g.COMPETENC = "'.$dt.'" AND 
							g.cnpj_emp = "'.$cnpj.'" and 
							g.TIPO = "2" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		
				
			$id			= $dba->result($res,$i,'id');
			$competenc  = $dba->result($res,$i,'COMPETENC');			
			$codigo	    = $dba->result($res,$i,'CODIGO');
			$valorpago  = $dba->result($res,$i,'VALORPAGO');						
			
			$guia = new Guiaicms();
			
			$guia->setId($id);
			$guia->setCompetenc($competenc);			
			$guia->setCodigoGuia($codigo);
			$guia->setValorPago($valorpago);			
			
			$vet[$i] = $guia;

		}

		return $vet;

	}

	public function ValidGuia($cnpj,$dt){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					g.id,
					g.COMPETENC,
					g.TIPO,    
					g.CODIGO,
					g.VALORPAGO    
				FROM
					guiaicms g
				WHERE
					g.COMPETENC = "'.$dt.'" AND 
							g.cnpj_emp = "'.$cnpj.'"';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		
				
			$id			= $dba->result($res,$i,'id');
			$competenc  = $dba->result($res,$i,'COMPETENC');			
			$codigo	    = $dba->result($res,$i,'CODIGO');
			$valorpago  = $dba->result($res,$i,'VALORPAGO');						
			
			$guia = new Guiaicms();
			
			$guia->setId($id);
			$guia->setCompetenc($competenc);			
			$guia->setCodigoGuia($codigo);
			$guia->setValorPago($valorpago);			
			
			$vet[$i] = $guia;

		}

		return $vet;

	}
	
	
	public function inserir($guia){

		$dba = $this->dba;		
		
		$competenc = $guia->getCompetenc();		
		$codigo    = $guia->getCodigoGuia();
		$valorpago = $guia->getValorPago();
		$tipo 	   = $guia->getTipo();	
		$cnpjemp   = $guia->getCnpjEmp();
		
		$sql = 'INSERT INTO `guiaicms`
				(`COMPETENC`,				
				`CODIGO`,
				`VALORPAGO`,
				`TIPO`,
				`cnpj_emp`)
				VALUES
				("'.$competenc.'",				 
				 "'.$codigo.'",
				 '.$valorpago.',
				 "'.$tipo.'",
				 "'.$cnpjemp.'")';

		$dba->query($sql);	
							
	}
	
	public function update($guia){

		$dba = $this->dba;		
		
		$id		   = $guia->getCodigo();
		$competenc = $guia->getCompetenc();		
		$codigo    = $guia->getCodigoGuia();
		$valorpago = $guia->getValorPago();
		$tipo 	   = $guia->getTipo();	
				
		$sql = 'UPDATE `guiaicms`
				SET
				`COMPETENC` = "'.$competenc.'",				
				`CODIGO` =  "'.$codigo.'",
				`VALORPAGO` = '.$valorpago.',
				`TIPO` = "'.$tipo.'"
				WHERE `id` = '.$id.' ';

		$dba->query($sql);	
							
	}
	
	public function inserirguia($guia){

		$dba = $this->dba;		
		
		$competenc = $guia->getCompetenc();		
		$codigo    = $guia->getCodigoGuia();
		$valorpago = $guia->getValorPago();
		$tipo      = $guia->getTipo();				
		$cnpjemp   = $guia->getCnpjEmp();
		
		$sql = 'INSERT INTO `guiaicms`
				(`COMPETENC`,				
				`CODIGO`,				
				`TIPO`,
				`VALORPAGO`,
				`cnpj_emp`)
				VALUES
				("'.$competenc.'",				 
				 "'.$codigo.'",
				 "'.$tipo.'",
				 '.$valorpago.',				
				 "'.$cnpjemp.'")';

		$dba->query($sql);	
							
	}

	public function updateguia($guia){

		$dba = $this->dba;		
		
		$id		   = $guia->getid();		
		$codigo    = $guia->getCodigoGuia();
		$valorpago = $guia->getValorPago();
				
		$sql = 'UPDATE `guiaicms`
				SET							
				`CODIGO` =  "'.$codigo.'",
				`VALORPAGO` = '.$valorpago.'				
				WHERE `id` = '.$id.' ';
			
		$dba->query($sql);	
							
	}

	public function deletar($guia){
	
		$dba = $this->dba;

		$id  = $guia->getCodigo();
		
		$sql = 'DELETE FROM `guiaicms`
				WHERE id = '.$id;

		$dba->query($sql);	
				
	}		
}

?>
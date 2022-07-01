<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class ProdFrigTxtDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}


		public function VerificaProduto($cod,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					p.id,
					p.cod_prod,
					p.desc_prod,
					p.cod_secretaria,
					p.cnpj_emp
				FROM
					prodfrigtxt p
				where p.cod_prod = "'.$cod.'" and p.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	    		= $dba->result($res,$i,'id');
			$cod_prod  		= $dba->result($res,$i,'cod_prod');
			$desc_prod 		= $dba->result($res,$i,'desc_prod');
			$cod_secretaria	= $dba->result($res,$i,'cod_secretaria');
			$cnpj_emp  		= $dba->result($res,$i,'cnpj_emp');
				
			$prodfrig = new ProdFrigTxt();

			$prodfrig->setCodigo($id);
			$prodfrig->setCodProd($cod_prod);
			$prodfrig->setDescProd($desc_prod);
			$prodfrig->setCodSecretaria($cod_secretaria);
			$prodfrig->setCnpjEmp($cnpj_emp);
			$prodfrig->setId($id);
			
			$vet[$i] = $prodfrig;

		}

		return $vet;

	}
	
	public function VerificaProdutoProdFrig($cod,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					p.id,
					p.cod_prod,
					p.desc_prod,
					p.cod_secretaria,
					p.cnpj_emp,
					a.tipo
				FROM
					prodfrigtxt p
				inner join produtos_agregar a on (a.codigo = p.cod_secretaria)
				where p.cod_prod = "'.$cod.'" and p.cnpj_emp = "'.$cnpj.'"';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	    		= $dba->result($res,$i,'id');
			$cod_prod  		= $dba->result($res,$i,'cod_prod');
			$desc_prod 		= $dba->result($res,$i,'desc_prod');
			$cod_secretaria	= $dba->result($res,$i,'cod_secretaria');
			$cnpj_emp  		= $dba->result($res,$i,'cnpj_emp');
			$tipo	  		= $dba->result($res,$i,'tipo');
				
			$prodfrig = new ProdFrigTxt();

			$prodfrig->setCodigo($cod);
			$prodfrig->setCodProd($cod_prod);
			$prodfrig->setDescProd($desc_prod);
			$prodfrig->setCodSecretaria($cod_secretaria);
			$prodfrig->setCnpjEmp($cnpj_emp);
			$prodfrig->setTipo($tipo);
			
			$vet[$i] = $prodfrig;

		}

		return $vet;

	}
	
	public function ListaRelacionado($cod,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					p.id,
					p.cod_prod,
					p.desc_prod,
					a.descricao,
					p.cod_secretaria,
					p.cnpj_emp,
					a.tipo
				FROM
					prodfrigtxt p
				left join produtos_agregar a on (a.codigo = p.cod_secretaria)
				where p.cod_prod = "'.$cod.'" and p.cnpj_emp = "'.$cnpj.'"';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	    		= $dba->result($res,$i,'id');
			$cod_prod  		= $dba->result($res,$i,'cod_prod');
			$desc_prod 		= $dba->result($res,$i,'desc_prod');
			$cod_secretaria	= $dba->result($res,$i,'cod_secretaria');
			$cnpj_emp  		= $dba->result($res,$i,'cnpj_emp');
			$tipo	  		= $dba->result($res,$i,'tipo');
			$descricao 		= $dba->result($res,$i,'descricao');

			$prodfrig = new ProdFrigTxt();

			$prodfrig->setCodigo($id);
			$prodfrig->setCodProd($cod_prod);
			$prodfrig->setDescProd($desc_prod);
			$prodfrig->setCodSecretaria($cod_secretaria);
			$prodfrig->setCnpjEmp($cnpj_emp);
			$prodfrig->setTipo($tipo);
			$prodfrig->setDesProdSecretaria($descricao);

			$vet[$i] = $prodfrig;

		}

		return $vet;

	}

	public function ListaCodigosExclusao($cods,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					p.id					
				FROM
					prodfrigtxt p				
				where p.cod_prod in ('.$cods.') and p.cnpj_emp = "'.$cnpj.'"';
		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	      = $dba->result($res,$i,'id');

			$prodfrig = new ProdFrigTxt();

			$prodfrig->setCodigo($id);			

			$vet[$i] = $prodfrig;

		}

		return $vet;

	}

	public function inserir($prodfrigtxt){

		$dba = $this->dba;		
		
		$codprod	= $prodfrigtxt->getCodProd();
		$descprod	= $prodfrigtxt->getDescProd();
		$codsecret  = $prodfrigtxt->getCodSecretaria();
		$cnpjemp    = $prodfrigtxt->getCnpjEmp();
		
		$sql = 'INSERT INTO `prodfrigtxt`
				(`cod_prod`,
				`desc_prod`,
				`cod_secretaria`,
				`cnpj_emp`)
				VALUES
				("'.$codprod.'",
				"'.$descprod.'",
				"'.$codsecret.'",
				"'.$cnpjemp.'")';

		

		$dba->query($sql);	
							
	}
	
	public function deletesecret($prodfrigtxt){

		$dba = $this->dba;		
		
		$codprod	= $prodfrigtxt->getCodigo();
		$codsecret  = $prodfrigtxt->getCodSecretaria();
		$cnpjemp    = $prodfrigtxt->getCnpjEmp();
		
		$sql = 'DELETE FROM `prodfrigtxt`
				WHERE `id` = '.$codprod.' ';

		$dba->query($sql);	
							
	}

	public function delete($prodfrigtxt){

		$dba = $this->dba;		
		
		$codprod	= $prodfrigtxt->getCodigo();		
		$cnpjemp    = $prodfrigtxt->getCnpjEmp();
		
		$sql = 'DELETE FROM `prodfrigtxt`
				WHERE `id` = '.$codprod.' and cnpj_emp = "'.$cnpjemp.'" ';

		$dba->query($sql);	
							
	}

	public function update($prodfrigtxt){
	
		$dba = $this->dba;

		$id 		= $prodfrigtxt->getCodigo();
		$codprod	= $prodfrigtxt->getCodProd();
		$descprod	= $prodfrigtxt->getDescProd();
		$codsecret  = $prodfrigtxt->getCodSecretaria();
		//$cnpjemp    = $prodfrigtxt->getCnpjEmp();

		$sql = 'UPDATE `prodfrigtxt`
				SET
				`cod_prod` = "'.$codprod.'",
				`desc_prod` = "'.$descprod.'",
				`cod_secretaria` = "'.$codsecret.'"				
				WHERE `id` ='.$id;
		
		$dba->query($sql);	
				
	}		

	public function proximoid(){
		
		$dba = $this->dba;

		$vet = array();		

		$sql = 'SHOW TABLE STATUS LIKE "prodfrigtxt"';

		$res = $dba->query($sql);

		$i = 0;

		$prox_id = $dba->result($res,$i,'Auto_increment');	 

		$prodfrig = new ProdFrigTxt();

		$prodfrig->setProximoId($prox_id);

		$vet[$i] = $prodfrig;

		return $vet;	

	}
}

?>
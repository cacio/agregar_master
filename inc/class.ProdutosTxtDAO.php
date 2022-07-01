<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class ProdutosTxtDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

		

	public function VerificaProdutoTxt($cod,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql ='SELECT p.id, p.cod_prod, p.desc_prod, p.cnpj_emp FROM produtotxt p where p.cod_prod = "'.$cod.'" and p.cnpj_emp = "'.$cnpj.'" ';
		 	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$codp 	    = $dba->result($res,$i,'id');
			$cod_prod 	= $dba->result($res,$i,'cod_prod');
			$desc_prod 	= $dba->result($res,$i,'desc_prod');
			$cnpj_emp 	= $dba->result($res,$i,'cnpj_emp');
			
				
			$prodtxt = new ProdutosTxt();
						
			$prodtxt->setCodigo($codp);			
			$prodtxt->setCodProd($cod);
			$prodtxt->setDescProd($desc_prod);
			$prodtxt->setCnpjEmp($cnpj_emp);

			$vet[$i] = $prodtxt;

		}

		return $vet;

	}
	
	public function RelProdutoTxt($cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql =' SELECT 
				    pr.id,
					pr.cod_secretaria,
					a.descricao,
					pr.cod_prod,
					if(p.desc_prod = "" or p.desc_prod  is null,pr.desc_prod,p.desc_prod) as desc_prod
				FROM
				    prodfrigtxt pr
				        left JOIN
				    produtos_agregar a ON (a.codigo = pr.cod_secretaria)
					LEFT JOIN
					produtotxt p ON (p.cod_prod = pr.cod_prod)
						AND (p.cnpj_emp = pr.cnpj_emp)				        
				WHERE
				    pr.cnpj_emp = "'.$cnpj.'" order by pr.cod_secretaria';
		 	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$pkid              = $dba->result($res,$i,'id');
			$cod_secretaria    = $dba->result($res,$i,'cod_secretaria');
			$descricao 	       = $dba->result($res,$i,'descricao');
			$cod_prod 	       = $dba->result($res,$i,'cod_prod');
			$desc_prod         = $dba->result($res,$i,'desc_prod');
			
				
			$prodtxt = new ProdutosTxt();

			$prodtxt->setCodSecretaria($cod_secretaria);			
			$prodtxt->setDescSecretaria($descricao);
			$prodtxt->setCodProd($cod_prod);
			$prodtxt->setDescProd($desc_prod);
			$prodtxt->setPkId($pkid);

			$vet[$i] = $prodtxt;

		}

		return $vet;

	}
	
	public function ListaProdutoTxt($cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql ='SELECT p.id, p.cod_prod, p.desc_prod, p.cnpj_emp FROM produtotxt p where p.cnpj_emp = "'.$cnpj.'" ';
		 	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod 	    = $dba->result($res,$i,'id');
			$cod_prod 	= $dba->result($res,$i,'cod_prod');
			$desc_prod 	= $dba->result($res,$i,'desc_prod');
			$cnpj_emp 	= $dba->result($res,$i,'cnpj_emp');
			
				
			$prodtxt = new ProdutosTxt();

			$prodtxt->setCodigo($cod);			
			$prodtxt->setCodProd($cod_prod);
			$prodtxt->setDescProd($desc_prod);
			$prodtxt->setCnpjEmp($cnpj_emp);

			$vet[$i] = $prodtxt;

		}

		return $vet;

	}

	public function BuscaProdutoTxt($term,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql =' SELECT 
				    pr.id,
					pr.cod_secretaria,					
					pr.cod_prod,
					pr.desc_prod as desc_prod
				FROM
				    prodfrigtxt pr				       		        
				WHERE
				(pr.desc_prod like "%'.$term.'%" or pr.cod_prod like "%'.$term.'%") and
				pr.cnpj_emp = "'.$cnpj.'" order by pr.cod_secretaria';
		 	//echo "{$sql}";  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$pkid              = $dba->result($res,$i,'id');
			$cod_secretaria    = $dba->result($res,$i,'cod_secretaria');			
			$cod_prod 	       = $dba->result($res,$i,'cod_prod');
			$desc_prod         = $dba->result($res,$i,'desc_prod');
			
				
			$prodtxt = new ProdutosTxt();

			$prodtxt->setCodSecretaria($cod_secretaria);			
			$prodtxt->setCodProd($cod_prod);
			$prodtxt->setDescProd($desc_prod);
			$prodtxt->setPkId($pkid);

			$vet[$i] = $prodtxt;

		}

		return $vet;

	}

	public function inserir($prodtxt){

		$dba = $this->dba;
		
		$codprod  = $prodtxt->getCodProd();
		$descprod = $prodtxt->getDescProd(); 
		$cnpjemp  = $prodtxt->getCnpjEmp();

		$sql = 'INSERT INTO `produtotxt`
				(
				`cod_prod`,
				`desc_prod`,
				`cnpj_emp`)
				VALUES
				(
				"'.$codprod.'",
				"'.$descprod.'",
				"'.$cnpjemp.'")';					

		$dba->query($sql);	
	
	}

	public function update($prodtxt){

		$dba = $this->dba;
		$codigo   = $prodtxt->getCodigo();
		$codprod  = $prodtxt->getCodProd();
		$descprod = $prodtxt->getDescProd(); 
		$cnpjemp  = $prodtxt->getCnpjEmp();

		$sql = 'UPDATE `produtotxt`
				SET
				`cod_prod` = "'.$codprod.'",
				`desc_prod` = "'.$descprod.'"				
				WHERE `id` = "'.$codigo .'" ';					

		$dba->query($sql);	
	
	}	


}

?>
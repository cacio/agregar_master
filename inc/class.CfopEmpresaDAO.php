<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class CfopEmpresaDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	public function ListaCfopGeraAgregarSN($cfop,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql ='SELECT 
					if(gera_agregar = 1 , "sim","nao") as sn 
				FROM cfop_empresa where id_cfop = "'.$cfop.'" and cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$sn    = $dba->result($res,$i,'sn');
						
			$rcfop = new CfopEmpresa();

			$rcfop->setGeraAgregar($sn);
		
			
			$vet[$i] = $rcfop;

		}

		return $vet;

	}

	public function inserir($cfopemp){

		$dba = $this->dba;		
		
		$idcfop    = $cfopemp->getIdCfop();
		$geraagreg = $cfopemp->getGeraAgregar();
		$empresa   = $cfopemp->getCnpjEmp();
		
		$sql = 'INSERT INTO `cfop_empresa`
				(`id_cfop`,
				`gera_agregar`,
				`cnpj_emp`)
				VALUES
				('.$idcfop.',
				"'.$geraagreg.'",
				"'.$empresa.'")';

		$dba->query($sql);	
							
	}
	
	public function update($cfopemp){
	
		$dba = $this->dba;

		$id 	   = $cfopemp->getCodigo();
		$idcfop    = $cfopemp->getIdCfop();
		$geraagreg = $cfopemp->getGeraAgregar();
		$empresa   = $cfopemp->getCnpjEmp();
		
		$sql = 'UPDATE `cfop_empresa`
				SET
				`id_cfop` = '.$idcfop.',
				`gera_agregar` = "'.$geraagreg.'",
				`cnpj_emp` = "'.$empresa.'"
				WHERE `id` ='.$id;

		$dba->query($sql);	
				
	}	

	
	
	public function proximoid(){
		
		$dba = $this->dba;

		$vet = array();		

		$sql = 'SHOW TABLE STATUS LIKE "relaciona_produto"';

		$res = $dba->query($sql);

		$i = 0;

		$prox_id = $dba->result($res,$i,'Auto_increment');	 

		$rel = new RelacionaProduto();

		$rel->setProximoId($prox_id);

		$vet[$i] = $rel;

		return $vet;	

	}
	
		
}

?>
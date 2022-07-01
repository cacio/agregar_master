<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class SociosAcionistasDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	
	
		public function ListaSociosAcionistasPorEmpresa($idemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					s.id,
					s.nome,
					s.cpf,
					s.partic_cap_social,
					s.id_emp
				FROM
					tab_socios_acionistas_qa s 
				WHERE s.id_emp = '.$idemp.'';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod   	  			= $dba->result($res,$i,'id');
			$nome 	 		 	= $dba->result($res,$i,'nome');
			$cpf  	 			= $dba->result($res,$i,'cpf');
			$partic_cap_social  = $dba->result($res,$i,'partic_cap_social');
			$id_emp   			= $dba->result($res,$i,'id_emp');
			
			$soc = new SociosAcionistas();

			$soc->setCodigo($cod);
			$soc->setNome($nome);
			$soc->setCpf($cpf);
			$soc->setPartCapSocial($partic_cap_social);
			$soc->setIdEmpresa($id_emp);
			
			$vet[$i] = $soc;

		}

		return $vet;

	}
	

	public function inserir($soc){

		$dba = $this->dba;
		
			$nome 			   = $soc->getNome();
			$cpf  			   = $soc->getCpf();
			$partic_cap_social = $soc->getPartCapSocial();
			$id_emp			   = $soc->getIdEmpresa();
		
		$sql = 'INSERT INTO `tab_socios_acionistas_qa`
				(`nome`,
				`cpf`,
				`partic_cap_social`,
				`id_emp`)
				VALUES
				("'.$nome.'",
				 "'.$cpf.'",
				 "'.$partic_cap_social.'",
				 '.$id_emp.')';					

		$dba->query($sql);	
	
	}

	

	public function deletar($soc){
	
		$dba = $this->dba;

		$id     = $soc->getCodigo();
		$idemp	= $soc->getIdEmpresa();
		
		$sql = 'DELETE FROM tab_socios_acionistas_qa WHERE id_emp = '.$idemp.' and id='.$id;

		$dba->query($sql);	
		
	}
	
	public function proximoid(){
		
		$dba = $this->dba;
		$vet = array();
		
		$sql = 'SHOW TABLE STATUS LIKE "tab_socios_acionistas_qa"';
		$res = $dba->query($sql);
		$i = 0;
		$prox_id = $dba->result($res,$i,'Auto_increment');	 
		$soc = new SociosAcionistas();
		$soc->setIdProx($prox_id);
		$vet[$i] = $soc;
		return $vet;
	
	}

}

?>
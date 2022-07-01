<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class InspecaoEstabelecimentoDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	
	
		public function ListaInspecaoEstabelecimentoPorEmpresa($idemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					i.id,
					i.tipo,
					i.n_registro,
					i.id_emp
				FROM
					tab_inspecao_estabeleciomento_qa i
				WHERE i.id_emp  = '.$idemp.' ';			  
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod   	  			= $dba->result($res,$i,'id');
			$tipo     			= $dba->result($res,$i,'tipo');
			$n_registro		 	= $dba->result($res,$i,'n_registro');			
			
			$insp = new InspecaoEstabelecimento();

			$insp->setCodigo($cod);
			$insp->setTipo($tipo);
			$insp->setNregistro($n_registro);
			
			$vet[$i] = $insp;

		}

		return $vet;

	}
	

	public function inserir($ins){

		$dba = $this->dba;
					
		$tipo   = $ins->getTipo();
		$nreg	= $ins->getNregistro();
		$id_emp = $ins->getIdEmpresa();
	
		$sql = 'INSERT INTO `tab_inspecao_estabeleciomento_qa`
				(
				`tipo`,
				`n_registro`,
				`id_emp`)
				VALUES
				("'.$tipo.'",
				"'.$nreg.'",
				'.$id_emp.')';					
		
		$dba->query($sql);	
	
	}
	


	public function deletar($ins){
	
		$dba = $this->dba;

		$id     = $ins->getCodigo();
		$idemp	= $ins->getIdEmpresa();
		
		$sql = 'DELETE FROM tab_inspecao_estabeleciomento_qa WHERE id_emp = '.$idemp.' and id='.$id;

		$dba->query($sql);	
		
	}
	
	public function proximoid(){
		
		$dba = $this->dba;
		$vet = array();
		
		$sql = 'SHOW TABLE STATUS LIKE "tab_relacao_bens_imoveis_qa"';
		$res = $dba->query($sql);
		$i = 0;
		$prox_id = $dba->result($res,$i,'Auto_increment');	 
		$bens = new RelacaoBensImoveis();
		$bens->setIdProx($prox_id);
		$vet[$i] = $bens;
		return $vet;
	
	}

}

?>
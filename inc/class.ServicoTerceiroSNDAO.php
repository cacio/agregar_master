<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class ServicoTerceiroSNDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	
	
		public function ListaServicoTerceiroSNPorEmpresa($idemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					s.id,
					s.s_n,
					s.id_emp
				FROM
					tab_servico_terceiro_s_n_qa s 
				WHERE s.id_emp = '.$idemp.' ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod   	  		= $dba->result($res,$i,'id');		
			$s_n   			= $dba->result($res,$i,'s_n');
			
			$sn = new ServicoTerceiroSN();

			$sn->setCodigo($cod);			
			$sn->setSn($s_n);	
			
			$vet[$i] = $sn;

		}

		return $vet;

	}
	

	public function inserir($sn){

		$dba = $this->dba;
					
		$s_n	= $sn->getSn();		
		$id_emp	= $sn->getIdEmpresa();
	
		$sql = 'INSERT INTO `tab_servico_terceiro_s_n_qa`
				(`s_n`,
				`id_emp`)
				VALUES
				('.$s_n.',
				 '.$id_emp.')';					
		
		$dba->query($sql);	
	
	}
	


	public function deletar($sn){
	
		$dba = $this->dba;

		$id     = $sn->getCodigo();
		$idemp	= $sn->getIdEmpresa();
		
		$sql = 'DELETE FROM tab_servico_terceiro_s_n_qa WHERE id_emp = '.$idemp.' and id='.$id;

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
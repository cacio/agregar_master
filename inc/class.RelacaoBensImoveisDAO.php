<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class RelacaoBensImoveisDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	
	
		public function ListaRelacaoBensImoveisPorEmpresa($idemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					r.id,
					r.descricao,
					r.endereco,
					r.matricula,
					r.id_emp
				FROM
					tab_relacao_bens_imoveis_qa r
				WHERE r.id_emp = '.$idemp.'';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod   	  			= $dba->result($res,$i,'id');
			$desc 	 		 	= $dba->result($res,$i,'descricao');
			$endereco 			= $dba->result($res,$i,'endereco');
			$matricula			= $dba->result($res,$i,'matricula');
			$id_emp   			= $dba->result($res,$i,'id_emp');
			
			$bens = new RelacaoBensImoveis();

			$bens->setCodigo($cod);
			$bens->setDescricao($desc);
			$bens->setEndereco($endereco);
			$bens->setMatricula($matricula);
			$bens->setIdEmpresa($id_emp);
			
			$vet[$i] = $bens;

		}

		return $vet;

	}
	

	public function inserir($bens){

		$dba = $this->dba;
		
			$desc 			   = $bens->getDescricao();
			$endereco		   = $bens->getEndereco();
			$matricula		   = $bens->getMatricula();
			$id_emp			   = $bens->getIdEmpresa();
		
			$sql = 'INSERT INTO `tab_relacao_bens_imoveis_qa`
					(`descricao`,
					`endereco`,
					`matricula`,
					`id_emp`)
					VALUES
					("'.$desc.'",
					 "'.$endereco.'",
					 "'.$matricula.'",
					  '.$id_emp.')';					

		$dba->query($sql);	
	
	}

	

	public function deletar($bens){
	
		$dba = $this->dba;

		$id     = $bens->getCodigo();
		$idemp	= $bens->getIdEmpresa();
		
		$sql = 'DELETE FROM tab_relacao_bens_imoveis_qa WHERE id_emp = '.$idemp.' and id='.$id;

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
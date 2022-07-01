<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class EnderecoEscritorioDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	
	
		public function ListaEnderecoEscritorioPorEmpresa($idemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					e.id,
					e.endereco,
					e.municipio,
					e.cep,
					e.fone,
					e.email,
					e.id_emp
				FROM
					tab_endereco_escritorio_qa e
				WHERE e.id_emp = '.$idemp.' ';			  
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod   	  			= $dba->result($res,$i,'id');
			$endereco 			= $dba->result($res,$i,'endereco');
			$cep	 		 	= $dba->result($res,$i,'cep');
			$fone				= $dba->result($res,$i,'fone');
			$municipio   	    = $dba->result($res,$i,'municipio');
			$email   			= $dba->result($res,$i,'email');
			
			$esc = new EnderecoEscritorio();

			$esc->setCodigo($cod);
			$esc->setEndereco($endereco);
			$esc->setCep($cep);
			$esc->setFone($fone);
			$esc->setEmail($email);
			$esc->setMunicipio($municipio);
			
			$vet[$i] = $esc;

		}

		return $vet;

	}
	

	public function inserir($esc){

		$dba = $this->dba;
					
		$endereco		   = $esc->getEndereco();
		$municipio		   = $esc->getMunicipio();
		$cep 			   = $esc->getCep();
		$fone			   = $esc->getFone();
		$email			   = $esc->getEmail();
		$id_emp			   = $esc->getIdEmpresa();
	
		$sql = 'INSERT INTO `tab_endereco_escritorio_qa`
				(`endereco`,
				`municipio`,
				`cep`,
				`fone`,
				`email`,
				`id_emp`)
				VALUES
				("'.$endereco.'",
				"'.$municipio.'",
				"'.$cep.'",
				"'.$fone.'",
				"'.$email.'",
				'.$id_emp.')';					
		
		$dba->query($sql);	
	
	}
	
	
	public function update($esc){

		$dba = $this->dba;
					
		$endereco		   = $esc->getEndereco();
		$municipio		   = $esc->getMunicipio();
		$cep 			   = $esc->getCep();
		$fone			   = $esc->getFone();
		$email			   = $esc->getEmail();
		$id_emp			   = $esc->getIdEmpresa();
	
		$sql = '';					

		$dba->query($sql);	
	
	}
	

	public function deletar($esc){
	
		$dba = $this->dba;

		$id     = $esc->getCodigo();
		$idemp	= $esc->getIdEmpresa();
		
		$sql = 'DELETE FROM tab_endereco_escritorio_qa WHERE id_emp = '.$idemp.' and id='.$id;

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
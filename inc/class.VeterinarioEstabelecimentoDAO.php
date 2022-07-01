<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class VeterinarioEstabelecimentoDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	
	
		public function ListaVeterinarioEstabelecimentoPorEmpresa($idemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					v.id,
					v.nome,
					v.n_crmv,
					v.endereco,
					v.email,
					v.conv_municipio,
					v.org_municipio,
					v.id_emp
				FROM
					tab_veterinario_estabelecimento_qa v 
				WHERE v.id_emp = '.$idemp.' ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod   	  			= $dba->result($res,$i,'id');
			$nome  	  			= $dba->result($res,$i,'nome');
			$n_crmv  			= $dba->result($res,$i,'n_crmv');
			$endereco  			= $dba->result($res,$i,'endereco');
			$email 	  			= $dba->result($res,$i,'email');
			$conv_municipio		= $dba->result($res,$i,'conv_municipio');
			$org_municipio		= $dba->result($res,$i,'org_municipio');			
			$id_emp   			= $dba->result($res,$i,'id_emp');
			
			$est = new VeterinarioEstabelecimento();

			$est->setCodigo($cod);
			$est->setNome($nome);
			$est->setCrmv($n_crmv);
			$est->setEndereco($endereco);
			$est->setEmail($email);
			$est->setConvenioMunicipio($conv_municipio);
			$est->setOrgMunicipio($org_municipio);		
			$est->setIdEmpresa($id_emp);
			
			$vet[$i] = $est;

		}

		return $vet;

	}
	

	public function inserir($est){

		$dba = $this->dba;
					
		$nome     = $est->getNome();
		$crmv     = $est->getCrmv();
		$endr	  = $est->getEndereco();
		$email    = $est->getEmail();
		$convmuni = $est->getConvenioMunicipio();
		$org_muni = $est->getOrgMunicipio();
		$id_emp	  = $est->getIdEmpresa();
	
		$sql = 'INSERT INTO `tab_veterinario_estabelecimento_qa`
				(`nome`,
				`n_crmv`,
				`endereco`,
				`email`,
				`conv_municipio`,
				`org_municipio`,
				`id_emp`)
				VALUES
				("'.$nome.'",
				 "'.$crmv.'",
				 "'.$endr.'",
				 "'.$email.'",
				 "'.$convmuni.'",
				 "'.$org_muni.'",
				 '.$id_emp.')';					
						
		$dba->query($sql);	
	
	}
	
	

	public function deletar($est){
	
		$dba = $this->dba;

		$id     = $est->getCodigo();
		$idemp	= $est->getIdEmpresa();
		
		$sql = 'DELETE FROM tab_veterinario_estabelecimento_qa WHERE id_emp = '.$idemp.' and id='.$id;

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
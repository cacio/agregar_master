<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class AdministracaoDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	
	
		public function ListaAdministracaoPorEmpresa($idemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					a.id,
					a.nome_gerente_diretor,
					a.fone_gerente_diretor,
					a.email_gerente_diretor,
					a.nome_contador,
					a.fone_contador,
					a.email_contador,
					a.nome_tecnico,
					a.fone_tecnico,
					a.email_tecnico,
					a.n_crmv_tecnico,
					a.id_emp
				FROM
					tab_administracao_qa a  
				WHERE a.id_emp = '.$idemp.' ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod   	  			   = $dba->result($res,$i,'id');
			$nome_gerente_diretor  = $dba->result($res,$i,'nome_gerente_diretor');
			$fone_gerente_diretor  = $dba->result($res,$i,'fone_gerente_diretor');
			$email_gerente_diretor = $dba->result($res,$i,'email_gerente_diretor');
			$nome_contador 		   = $dba->result($res,$i,'nome_contador');
			$fone_contador 		   = $dba->result($res,$i,'fone_contador');
			$email_contador		   = $dba->result($res,$i,'email_contador');
			$nome_tecnico		   = $dba->result($res,$i,'nome_tecnico');
			$fone_tecnico		   = $dba->result($res,$i,'fone_tecnico');
			$email_tecnico		   = $dba->result($res,$i,'email_tecnico');
			$n_crmv_tecnico		   = $dba->result($res,$i,'n_crmv_tecnico');
			$id_emp   			   = $dba->result($res,$i,'id_emp');
			
			$adm = new Administracao();

			$adm->setCodigo($cod);
			$adm->setNomeGerenteDiretor($nome_gerente_diretor);
			$adm->setFoneGerenteDiretor($fone_gerente_diretor);
			$adm->setEmailGerenteDiretor($email_gerente_diretor);
			$adm->setNomeContador($nome_contador);
			$adm->setFoneContador($fone_contador);
			$adm->setEmailContador($email_contador);
			$adm->setNomeTecnico($nome_tecnico);
			$adm->setFoneTecnico($fone_tecnico);
			$adm->setEmailTecnico($email_tecnico);
			$adm->setCrmvTecnico($n_crmv_tecnico);	
			$adm->setIdEmpresa($id_emp);
			
			$vet[$i] = $adm;

		}

		return $vet;

	}
	

	public function inserir($adm){

		$dba = $this->dba;
					
		$nome_gerente    = $adm->getNomeGerenteDiretor();
		$fone_gerente    = $adm->getFoneGerenteDiretor();
		$email_generente = $adm->getEmailGerenteDiretor();
		$nome_contador   = $adm->getNomeContador();
		$fone_contador   = $adm->getFoneContador();
		$email_contador  = $adm->getEmailContador();
		$nome_tecnico    = $adm->getNomeTecnico();
		$fone_tecnico    = $adm->getFoneTecnico();
		$email_tecnico   = $adm->getEmailTecnico();
		$crmv_tecnico	 = $adm->getCrmvTecnico();							
		$id_emp	         = $adm->getIdEmpresa();
	
		$sql = 'INSERT INTO `tab_administracao_qa`
				(`nome_gerente_diretor`,
				`fone_gerente_diretor`,
				`email_gerente_diretor`,
				`nome_contador`,
				`fone_contador`,
				`email_contador`,
				`nome_tecnico`,
				`fone_tecnico`,
				`email_tecnico`,
				`n_crmv_tecnico`,
				`id_emp`)
				VALUES
				("'.$nome_gerente.'",
				"'.$fone_gerente.'",
				"'.$email_generente.'",
				"'.$nome_contador.'",
				"'.$fone_contador.'",
				"'.$email_contador.'",
				"'.$nome_tecnico.'",
				"'.$fone_tecnico.'",
				"'.$email_tecnico.'",
				"'.$crmv_tecnico.'",
				'.$id_emp.')';					
		
		$dba->query($sql);	
	
	}
	
	
	
	

	public function deletar($adm){
	
		$dba = $this->dba;

		$id     = $adm->getCodigo();
		$idemp	= $adm->getIdEmpresa();
		
		$sql = 'DELETE FROM tab_administracao_qa WHERE id_emp = '.$idemp.' and id='.$id;

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
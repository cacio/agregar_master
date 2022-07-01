<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class ServicoTerceirosDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}

	
	public function ListaServicoTerceirosPorEmpresa($idemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					a.id,
					a.razao_social,
					a.cgc,
					a.id_emp
				FROM
					tab_servico_terceiro_qa a
				WHERE a.id_emp = '.$idemp.'';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod   	  = $dba->result($res,$i,'id');
			$razao 	  = $dba->result($res,$i,'razao_social');
			$cgc  	  = $dba->result($res,$i,'cgc');
			$id_emp   = $dba->result($res,$i,'id_emp');
			
			$serv = new ServicoTerceiros();

			$serv->setCodigo($cod);
			$serv->setRazaoSocial($razao);
			$serv->setCgc($cgc);
			$serv->setIdEmpresa($id_emp);
			
			$vet[$i] = $serv;

		}

		return $vet;

	}
	

	public function inserir($serv){

		$dba = $this->dba;
		
		$razao  = $serv->getRazaoSocial();
		$cgc	= $serv->getCgc();
		$idemp	= $serv->getIdEmpresa();
		
		$sql = 'INSERT INTO `tab_servico_terceiro_qa`
				(`razao_social`,
				`cgc`,
				`id_emp`)
				VALUES
				("'.$razao.'",
				 "'.$cgc.'",
				  '.$idemp.')';					

		$dba->query($sql);	
	
	}

	

	public function update($serv){

		$dba = $this->dba;

		$id     = $serv->getCodigo();
		$razao  = $serv->getRazaoSocial();
		$cgc	= $serv->getCgc();
		$idemp	= $serv->getIdEmpresa();
		
		$sql = '';

		$dba->query($sql);	

	}

	public function deletar($serv){
	
		$dba = $this->dba;

		$id     = $serv->getCodigo();
		$idemp	= $serv->getIdEmpresa();
		
		$sql = 'DELETE FROM tab_servico_terceiro_qa WHERE id_emp = '.$idemp.' and id='.$id;

		$dba->query($sql);	
		
	}
	
	public function proximoid(){
		
		$dba = $this->dba;
		$vet = array();
		
		$sql = 'SHOW TABLE STATUS LIKE "tab_servico_terceiro_qa"';
		$res = $dba->query($sql);
		$i = 0;
		$prox_id = $dba->result($res,$i,'Auto_increment');	 
		$serv = new ServicoTerceiros();
		$serv->setIdProx($prox_id);
		$vet[$i] = $serv;
		return $vet;
	
	}

}

?>
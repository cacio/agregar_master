<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class CertidoesLicenciamentoDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	
	
		public function ListaCertidoesLicenciamentoPorEmpresa($idemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					c.id,
					c.cert_fiscal_tesouro_estado,
					c.licenca_amb_fepam,
					c.emitida,
					c.n_protocolo_fepam,
					c.data,
					c.id_emp
				FROM
					tab_certidoes_licenciamento_qa c 
				WHERE c.id_emp = '.$idemp.' ';			  
		//echo 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod   	  					= $dba->result($res,$i,'id');
			$cert_fiscal_tesouro_estado	= $dba->result($res,$i,'cert_fiscal_tesouro_estado');
			$licenca_amb_fepam 			= $dba->result($res,$i,'licenca_amb_fepam');
			$emitida		 			= $dba->result($res,$i,'emitida');
			$n_protocolo_fepam 			= $dba->result($res,$i,'n_protocolo_fepam');
			$data			 			= $dba->result($res,$i,'data');
			$id_emp   					= $dba->result($res,$i,'id_emp');
			
			$cert = new CertidoesLicenciamento();

			$cert->setCodigo($cod);
			$cert->setCertFiscalTesouroEstado($cert_fiscal_tesouro_estado);	
			$cert->setLicencaAmbFepam($licenca_amb_fepam);
			$cert->setEmitida($emitida);
			$cert->setNumeroProtocoloFepam($n_protocolo_fepam);
			$cert->setData($data);
			$cert->setIdEmpresa($id_emp);
			
			$vet[$i] = $cert;

		}

		return $vet;

	}
	

	public function inserir($cert){

		$dba = $this->dba;
					
		$certfiscal	= $cert->getCertFiscalTesouroEstado();	
		$licenca    = $cert->getLicencaAmbFepam();
		$emitida	= $cert->getEmitida();
		$numprot 	= $cert->getNumeroProtocoloFepam();
		$data		= $cert->getData();			
		$id_emp	    = $cert->getIdEmpresa();
	
		$sql = 'INSERT INTO `tab_certidoes_licenciamento_qa`
				(`cert_fiscal_tesouro_estado`,
				`licenca_amb_fepam`,
				`emitida`,
				`n_protocolo_fepam`,
				`data`,
				`id_emp`)
				VALUES
				("'.$certfiscal.'",
				 "'.$licenca.'",
				 "'.$emitida.'",
				 "'.$numprot.'",
				 "'.$data.'",
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
		
		$sql = 'DELETE FROM tab_certidoes_licenciamento_qa WHERE id_emp = '.$idemp.' and id='.$id;
		 
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
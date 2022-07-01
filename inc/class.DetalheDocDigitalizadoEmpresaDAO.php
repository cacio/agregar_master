<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class DetalheDocDigitalizadoEmpresaDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	public function ListaDetalheDocumentoDiditalizado($id){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					dg.id,
					dg.documento,
					dg.id_detalhe_doc_digitalizado_empresa
				FROM
					detalhe_doc_digitalizado_empresa dg
				where
					dg.id_detalhe_doc_digitalizado_empresa = "'.$id.'"  ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod      = $dba->result($res,$i,'id');
			$docs     = $dba->result($res,$i,'documento');
			$iddocdig = $dba->result($res,$i,'id_detalhe_doc_digitalizado_empresa');
			
			$det = new DetalheDocDigitalizadoEmpresa();

			$det->setCodigo($cod);
			$det->setDocumento($docs);
			$det->setIdocdig($iddocdig);
			
			$vet[$i] = $det;

		}

		return $vet;

	}
		
	public function VerificaNomeDoArquivo($id,$doc){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'select e.documento from detalhe_doc_digitalizado_empresa e where e.id_detalhe_doc_digitalizado_empresa = "'.$id.'" and e.documento like "%'.$doc.'%" ';			  
		//echo $sql."<br/><br/>"; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){
			
			$docs     = $dba->result($res,$i,'documento');
			
			$det = new DetalheDocDigitalizadoEmpresa();

			$det->setDocumento($docs);
			
			$vet[$i] = $det;

		}

		return $vet;

	}	

	public function inserir($det){

		$dba = $this->dba;
	 	
		$id_doc_dig   = $det->getIdocdig();
		$documento    = $det->getDocumento(); 

		
		$sql = 'INSERT INTO `detalhe_doc_digitalizado_empresa`
				(
				`documento`,
				`id_detalhe_doc_digitalizado_empresa`)
				VALUES
				("'.$documento.'",
				 '.$id_doc_dig.')';					

		$dba->query($sql);	
	
	}

	

	public function deletar($det){
	
		$dba = $this->dba;

		$id = $det->getCodigo();
		
		$sql = 'DELETE FROM detalhe_doc_digitalizado_empresa WHERE id='.$id;

		$dba->query($sql);	
		
	}
	
	public	function getExtension($str){
			
		$i = strrpos($str,".");
		if (!$i){
			return "";
		}
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
		
	}
	
	public function proximoid(){
		
		$dba = $this->dba;
		$vet = array();
		
		$sql = 'SHOW TABLE STATUS LIKE "doc_digitalizado_empresa"';
		$res = $dba->query($sql);
		$i = 0;
		$prox_id = $dba->result($res,$i,'Auto_increment');	 
		$dig = new DocDigitalizadoEmpresa();
		$dig->setIdProx($prox_id);
		$vet[$i] = $dig;
		return $vet;
	
	}
	
}

?>
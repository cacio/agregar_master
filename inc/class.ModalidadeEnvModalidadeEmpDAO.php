<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class ModalidadeEnvModalidadeEmpDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	
	
	public function ListaModalidadeEnvModalidade($cod){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					md.id,
					md.id_modalidade,
					md.id_modalidade_env
				FROM
					tab_modalidadeenv_modalidadeemp md
				WHERE md.id_modalidade = '.$cod.' ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  		  		= $dba->result($res,$i,'id');
			$idmodalidade 		= $dba->result($res,$i,'id_modalidade');
			$id_modalidade_env  = $dba->result($res,$i,'id_modalidade_env');
			
			$mod = new ModalidadeEnvModalidadeEmp();

			$mod->setCodigo($cod);
			$mod->setIdModalidade($idmodalidade);
			$mod->setIdModalidadeEnv($id_modalidade_env);
			
			$vet[$i] = $mod;

		}

		return $vet;

	}
	
	public function ListaModalidadeEnvModalidadeSel($cod,$cenv){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					md.id,
					md.id_modalidade,
					md.id_modalidade_env
				FROM
					tab_modalidadeenv_modalidadeemp md
				WHERE md.id_modalidade = '.$cod.' and md.id_modalidade_env = '.$cenv.' ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  		  		= $dba->result($res,$i,'id');
			$idmodalidade		= $dba->result($res,$i,'id_modalidade');
			$id_modalidade_env  = $dba->result($res,$i,'id_modalidade_env');
			
			$mod = new ModalidadeEnvModalidadeEmp();

			$mod->setCodigo($cod);
			$mod->setIdModalidade($idmodalidade);
			$mod->setIdModalidade($id_modalidade_env);
			
			$vet[$i] = $mod;

		}

		return $vet;

	}
	
	public function VerificaModalidadeEnv($cod){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					md.id,
					md.id_modalidade,
					md.id_documentos,
					(select m.nome from modadlidade m where m.id = md.id_modalidade) as modalidade
				FROM
					tab_modalidade_documentos md
				WHERE md.id_documentos = '.$cod.' ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  		  = $dba->result($res,$i,'id');
			$idmodalidade = $dba->result($res,$i,'id_modalidade');
			$iddocumento  = $dba->result($res,$i,'id_documentos');
			$modalidade	  = $dba->result($res,$i,'modalidade');
			
			$mod = new ModalidadeDocumento();

			$mod->setCodigo($cod);
			$mod->setIdModalidade($idmodalidade);
			$mod->setIdDocumento($iddocumento);
			$mod->setNomeModalidade($modalidade);
			
			$vet[$i] = $mod;

		}

		return $vet;

	}

	public function VerificaModalidadeEnv2($cod){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					md.id,
					md.id_modalidade,
					md.id_documentos,
					(select d.nome from documentos d where d.id = md.id_documentos) as documento
				FROM
					tab_modalidade_documentos md
				WHERE md.id_modalidade = '.$cod.' ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  		  = $dba->result($res,$i,'id');
			$idmodalidade = $dba->result($res,$i,'id_modalidade');
			$iddocumento  = $dba->result($res,$i,'id_documentos');
			$documento	  = $dba->result($res,$i,'documento');
			
			$mod = new ModalidadeDocumento();

			$mod->setCodigo($cod);
			$mod->setIdModalidade($idmodalidade);
			$mod->setIdDocumento($iddocumento);
			$mod->setNomeDocumento($documento);
			
			$vet[$i] = $mod;

		}

		return $vet;

	}

	public function inserir($md){

		$dba = $this->dba;
		
		$idmodalidade      = $md->getIdModalidade();
		$idmodalidadeenv   = $md->getIdModalidadeEnv();
				
		$sql = 'INSERT INTO `tab_modalidadeenv_modalidadeemp`
				(`id_modalidade`,
				`id_modalidade_env`)
				VALUES
				('.$idmodalidade.',
				'.$idmodalidadeenv.')';					
		
		$dba->query($sql);	
	
	}
	
	public function deletar($md){
	
		$dba = $this->dba;

		$id 		   = $md->getCodigo();
		$idmodalidade  = $md->getIdModalidade();
		
		$sql = 'DELETE FROM tab_modalidadeenv_modalidadeemp WHERE id_modalidade = '.$idmodalidade.' and id='.$id;

		$dba->query($sql);	
		
	}

	
	public function deletar2($md){
	
		$dba = $this->dba;

		$id = $md->getCodigo();
		
		
		$sql = 'DELETE FROM tab_modalidade_documentos WHERE id='.$id;

		$dba->query($sql);	
		
	}

}

?>
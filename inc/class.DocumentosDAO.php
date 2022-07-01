<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class DocumentosDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	public function ListaDocumentos(){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					d.id, 
					d.nome
				FROM
					documentos d';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');
			
			$doc = new Documentos();

			$doc->setCodigo($cod);
			$doc->setNome($nome);

			$vet[$i] = $doc;

		}

		return $vet;

	}
	
	public function ListaDocumentosUm($cod){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					d.id, 
					d.nome
				FROM
					documentos d where d.id = "'.$cod.'" ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');
			
			$doc = new Documentos();

			$doc->setCodigo($cod);
			$doc->setNome($nome);

			$vet[$i] = $doc;

		}

		return $vet;

	}
	
	public function ListaDocumentosSelecionada(){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'select 
					d.*			
				from documentos d 	';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');

			
			$doc = new Documentos();

			$doc->setCodigo($cod);
			$doc->setNome($nome);

			
			$vet[$i] = $doc;

		}

		return $vet;

	}

	public function ListaDocumentosPorModalidadeEmpresa($cemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'select d.* from documentos d 
				inner join tab_modalidade_documentos md on ( md.id_documentos = d.id)
				where md.id_modalidade = (select m.id from modadlidade m
				inner join empresas e on (e.id_modalidade = m.id)
				where e.id = "'.$cemp.'")';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');

			
			$doc = new Documentos();

			$doc->setCodigo($cod);
			$doc->setNome($nome);

			
			$vet[$i] = $doc;

		}

		return $vet;

	}

	
	public function ListaDocumentosPorModalidadeEnvio($id){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT d.* FROM documentos d
				inner join tab_modalidade_documentos md on (md.id_documentos = d.id)
				where md.id_modalidade = "'.$id.'" ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');

			
			$doc = new Documentos();

			$doc->setCodigo($cod);
			$doc->setNome($nome);

			
			$vet[$i] = $doc;

		}

		return $vet;

	}

	public function inserir($doc){

		$dba = $this->dba;
		
		$nome  = $doc->getNome();
		
		$sql = 'INSERT INTO `documentos`
				(`nome`)
				VALUES
				("'.$nome.'")';					

		$dba->query($sql);	
	
	}

	

	public function update($doc){

		$dba = $this->dba;

		$id    = $doc->getCodigo();
		$nome  = $doc->getNome();
		
		$sql = 'UPDATE `documentos`
				SET
				`nome` = "'.$nome.'"
				WHERE `id` = '.$id;

		$dba->query($sql);	

	}

	public function deletar($doc){
	
		$dba = $this->dba;

		$id = $doc->getCodigo();
		
		$sql = 'DELETE FROM documentos WHERE id='.$id;

		$dba->query($sql);	
		
	}


}

?>
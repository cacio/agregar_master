<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class StatusDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	public function ListaStatus(){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					s.id, 
					s.nome
				FROM
					status s';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');
			
			$stat = new Status();

			$stat->setCodigo($cod);
			$stat->setNome($nome);

			$vet[$i] = $stat;

		}

		return $vet;

	}

	public function ListaStatusUm($id){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					s.id, 
					s.nome
				FROM
					status s
				WHERE s.id = '.$id.' ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');
			
			$stat = new Status();

			$stat->setCodigo($cod);
			$stat->setNome($nome);

			$vet[$i] = $stat;

		}

		return $vet;

	}


	public function inserir($stat){

		$dba = $this->dba;
		
		$nome  = $stat->getNome();
		
		$sql = 'INSERT INTO `status`
				(`nome`)
				VALUES
				("'.$nome.'")';					

		$dba->query($sql);	
	
	}

	

	public function update($stat){

		$dba = $this->dba;

		$id    = $stat->getCodigo();
		$nome  = $stat->getNome();
		
		$sql = 'UPDATE `status`
				SET
				`nome` = "'.$nome.'"
				WHERE `id` = '.$id;

		$dba->query($sql);	

	}

	public function deletar($stat){
	
		$dba = $this->dba;

		$id = $stat->getCodigo();
		
		$sql = 'DELETE FROM status WHERE id='.$id;

		$dba->query($sql);	
		
	}


}

?>
<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class PaisDAO{
	
	private $dba;
		
	public function __construct(){
		
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
		
	}
	
	public function listapais(){
		
		$dba = $this->dba;
		
		$vet = array();
		
		$sql = 'SELECT p.id,p.nome_pt FROM pais p';
		
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i < $num; $i++){
			
			$id      = $dba->result($res,$i,'id');            
			$nome_pt = $dba->result($res,$i,'nome_pt');

			$pais = new Pais();
            
            $pais->setCodigo($id);
			$pais->setNome($nome_pt);
            
			$vet[$i] = $pais;
			
		}
		return $vet;
	}
	
	
	
	
}
?>
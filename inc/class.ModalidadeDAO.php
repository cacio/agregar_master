<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class ModalidadeDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	public function ListaModalidade(){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					m.id, 
					m.nome
				FROM
					modadlidade m';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');
			
			$mod = new Modalidade();

			$mod->setCodigo($cod);
			$mod->setNome($nome);

			$vet[$i] = $mod;

		}

		return $vet;

	}
	
	public function ListaModalidadeUm($cod){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					m.id, 
					m.nome
				FROM
					modadlidade m where m.id = "'.$cod.'" ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');
			
			$mod = new Modalidade();

			$mod->setCodigo($cod);
			$mod->setNome($nome);

			$vet[$i] = $mod;

		}

		return $vet;

	}
	
	public function ListaModalidadeSelecionada($cod){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					m.id, 
					m.nome,
					case  when m.id = "'.$cod.'" then
					"selected" else "" end as selected
				FROM
					modadlidade m';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');
			$sel  = $dba->result($res,$i,'selected');
			
			$mod = new Modalidade();

			$mod->setCodigo($cod);
			$mod->setNome($nome);
			$mod->setSelecionado($sel);
			
			$vet[$i] = $mod;

		}

		return $vet;

	}

	public function inserir($mod){

		$dba = $this->dba;
		
		$nome  = $mod->getNome();
		
		$sql = 'INSERT INTO `modadlidade`
				(`nome`)
				VALUES
				("'.$nome.'")';					

		$dba->query($sql);	
	
	}

	

	public function update($mod){

		$dba = $this->dba;

		$id    = $mod->getCodigo();
		$nome  = $mod->getNome();
		
		$sql = 'UPDATE `modadlidade`
				SET
				`nome` = "'.$nome.'"
				WHERE `id` = '.$id;

		$dba->query($sql);	

	}

	public function deletar($mod){
	
		$dba = $this->dba;

		$id = $mod->getCodigo();
		
		$sql = 'DELETE FROM modadlidade WHERE id='.$id;

		$dba->query($sql);	
		
	}


}

?>
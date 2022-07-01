<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class ModalidadeEnvDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	public function ListaModalidadeEnv(){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					m.id, 
					m.nome
				FROM
					modadlidade_envio m';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');
			
			$env = new ModalidadeEnv();

			$env->setCodigo($cod);
			$env->setNome($nome);

			$vet[$i] = $env;

		}

		return $vet;

	}
	
	public function ListaModalidadeEnvUm($cod){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					m.id, 
					m.nome
				FROM
					modadlidade_envio m where m.id = "'.$cod.'" ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');
			
			$env = new ModalidadeEnv();

			$env->setCodigo($cod);
			$env->setNome($nome);

			$vet[$i] = $env;

		}

		return $vet;

	}
	
	public function ListaModalidadeEnvSelecionada(){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'select 
					m.*			
				from modadlidade_envio m 	';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');

			
			$env = new ModalidadeEnv();

			$env->setCodigo($cod);
			$env->setNome($nome);

			
			$vet[$i] = $env;

		}

		return $vet;

	}

	public function ListaModalidadeEnvioPorModalidadeEmpresa($cemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'select e.* from modadlidade_envio e
				inner join tab_modalidadeenv_modalidadeemp me on (me.id_modalidade_env = e.id)
				where me.id_modalidade = (select m.id from modadlidade m
								inner join empresas e on (e.id_modalidade = m.id)
								where e.id = "'.$cemp.'")';			  
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');

			
			$env = new ModalidadeEnv();

			$env->setCodigo($cod);
			$env->setNome($nome);

			
			$vet[$i] = $env;

		}

		return $vet;

	}
	
	public function ListaModalidadeEnvioPorModalidadeEmpresaUm($cemp,$cmod){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'select e.* from modadlidade_envio e
				inner join tab_modalidadeenv_modalidadeemp me on (me.id_modalidade_env = e.id)
				where me.id_modalidade = (select m.id from modadlidade m
								inner join empresas e on (e.id_modalidade = m.id)
								where e.id = "'.$cemp.'") and e.id = '.$cmod.'';			  
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
			$nome = $dba->result($res,$i,'nome');

			
			$env = new ModalidadeEnv();

			$env->setCodigo($cod);
			$env->setNome($nome);

			
			$vet[$i] = $env;

		}

		return $vet;

	}
	

	public function inserir($env){

		$dba = $this->dba;
		
		$nome  = $env->getNome();
		
		$sql = 'INSERT INTO `modadlidade_envio`
				(`nome`)
				VALUES
				("'.$nome.'")';					

		$dba->query($sql);	
	
	}

	

	public function update($env){

		$dba = $this->dba;

		$id    = $env->getCodigo();
		$nome  = $env->getNome();
		
		$sql = 'UPDATE `modadlidade_envio`
				SET
				`nome` = "'.$nome.'"
				WHERE `id` = '.$id;

		$dba->query($sql);	

	}

	public function deletar($doc){
	
		$dba = $this->dba;

		$id = $doc->getCodigo();
		
		$sql = 'DELETE FROM modadlidade_envio WHERE id='.$id;

		$dba->query($sql);	
		
	}


}

?>
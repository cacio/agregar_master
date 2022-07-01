<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class EmpresasTxtDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	
	public function VerificaEmpresasCadastradas($cnpj,$cpf,$insc,$cnpjemp){

		$dba = $this->dba;

		$vet = array();

		$sql ='select 
				e.id,
				e.cnpj_cpf,
				e.insc_estadual,
				e.razao,
				e.cidade,
				e.uf,
				e.tipo,
				e.cnpj_emp
			from
				empresastxt e
			where
				(SUBSTRING(e.cnpj_cpf from 1 for 14) = "'.$cnpj.'"
					or SUBSTRING(e.cnpj_cpf from 1 for 11) = "'.$cpf.'") and e.insc_estadual = "'.$insc.'" 
					and e.cnpj_emp = "'.$cnpjemp.'" ';
			
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod    		= $dba->result($res,$i,'id');
			$cnpj_cpf    	= $dba->result($res,$i,'cnpj_cpf');
			$insc_estadual 	= $dba->result($res,$i,'insc_estadual');
			$razao    		= $dba->result($res,$i,'razao');
			$cidade    		= $dba->result($res,$i,'cidade');
			$uf   		 	= $dba->result($res,$i,'uf');
			$tipo   	 	= $dba->result($res,$i,'tipo');
			$cnpj_emp	 	= $dba->result($res,$i,'cnpj_emp');	
				
			$emp = new EmpresasTxt();

			$emp->setCodigo($cod);
			$emp->setCnpjCpf($cnpj_cpf);
			$emp->setInscEstadual($insc_estadual);
			$emp->setRazao($razao);
			$emp->setCidade($cidade);
			$emp->setUf($uf);
			$emp->setTipo($tipo);
			$emp->setCnpjEmp($cnpj_emp);
			
			$vet[$i] = $emp;

		}

		return $vet;

	}
	
	public function VerificaSeExisteEmpresas($cnpj,$ie,$cnpjemp){

		$dba = $this->dba;

		$vet = array();

		$sql ='select 
					e.id,
					e.cnpj_cpf,
					e.insc_estadual,
					e.razao,
					e.cidade,
					e.uf,
					e.tipo,
					e.cnpj_emp
				from
					empresastxt e
				where	
					cast(e.cnpj_cpf as unsigned integer) = "'.$cnpj.'" and 
					e.cnpj_emp = "'.$cnpjemp.'" and e.insc_estadual = "'.$ie.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod    		= $dba->result($res,$i,'id');
			$cnpj_cpf    	= $dba->result($res,$i,'cnpj_cpf');
			$insc_estadual 	= $dba->result($res,$i,'insc_estadual');
			$razao    		= $dba->result($res,$i,'razao');
			$cidade    		= $dba->result($res,$i,'cidade');
			$uf   		 	= $dba->result($res,$i,'uf');
			$tipo   	 	= $dba->result($res,$i,'tipo');
			$cnpj_emp	 	= $dba->result($res,$i,'cnpj_emp');	
				
			$emp = new EmpresasTxt();

			$emp->setCodigo($cod);
			$emp->setCnpjCpf($cnpj_cpf);
			$emp->setInscEstadual($insc_estadual);
			$emp->setRazao($razao);
			$emp->setCidade($cidade);
			$emp->setUf($uf);
			$emp->setTipo($tipo);
			$emp->setCnpjEmp($cnpj_emp);
			
			$vet[$i] = $emp;

		}

		return $vet;

	}
	
	public function ListaEmpresas($cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql ='select 
				e.id,
				e.cnpj_cpf,
				e.insc_estadual,
				e.razao,
				e.cidade,
				e.uf,
				e.tipo,
				e.cnpj_emp
			from
				empresastxt e
			where			
			e.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod    		= $dba->result($res,$i,'id');
			$cnpj_cpf    	= $dba->result($res,$i,'cnpj_cpf');
			$insc_estadual 	= $dba->result($res,$i,'insc_estadual');
			$razao    		= $dba->result($res,$i,'razao');
			$cidade    		= $dba->result($res,$i,'cidade');
			$uf   		 	= $dba->result($res,$i,'uf');
			$tipo   	 	= $dba->result($res,$i,'tipo');
			$cnpj_emp	 	= $dba->result($res,$i,'cnpj_emp');	
				
			$emp = new EmpresasTxt();

			$emp->setCodigo($cod);
			$emp->setCnpjCpf($cnpj_cpf);
			$emp->setInscEstadual($insc_estadual);
			$emp->setRazao($razao);
			$emp->setCidade($cidade);
			$emp->setUf($uf);
			$emp->setTipo($tipo);
			$emp->setCnpjEmp($cnpj_emp);
			
			$vet[$i] = $emp;

		}

		return $vet;

	}
	
	
	public function BuscaEmpresas($sarch,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql ='select 
				e.id,
				e.cnpj_cpf,
				e.insc_estadual,
				e.razao,
				e.cidade,
				e.uf,
				e.tipo,
				e.cnpj_emp
			from
				empresastxt e
			where			
			(e.razao like "%'.$sarch.'%" or 
			 e.cnpj_cpf like "%'.$sarch.'%") and 
			 e.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod    		= $dba->result($res,$i,'id');
			$cnpj_cpf    	= $dba->result($res,$i,'cnpj_cpf');
			$insc_estadual 	= $dba->result($res,$i,'insc_estadual');
			$razao    		= $dba->result($res,$i,'razao');
			$cidade    		= $dba->result($res,$i,'cidade');
			$uf   		 	= $dba->result($res,$i,'uf');
			$tipo   	 	= $dba->result($res,$i,'tipo');
			$cnpj_emp	 	= $dba->result($res,$i,'cnpj_emp');	
				
			$emp = new EmpresasTxt();

			$emp->setCodigo($cod);
			$emp->setCnpjCpf($cnpj_cpf);
			$emp->setInscEstadual($insc_estadual);
			$emp->setRazao($razao);
			$emp->setCidade($cidade);
			$emp->setUf($uf);
			$emp->setTipo($tipo);
			$emp->setCnpjEmp($cnpj_emp);
			
			$vet[$i] = $emp;

		}

		return $vet;

	}

	public function BuscaEmpresasUm($id,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql ='select 
				e.id,
				e.cnpj_cpf,
				e.insc_estadual,
				e.razao,
				e.cidade,
				e.uf,
				e.tipo,
				e.cnpj_emp
			from
				empresastxt e
			where			
			 e.id = "'.$id.'" and 
			 e.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod    		= $dba->result($res,$i,'id');
			$cnpj_cpf    	= $dba->result($res,$i,'cnpj_cpf');
			$insc_estadual 	= $dba->result($res,$i,'insc_estadual');
			$razao    		= $dba->result($res,$i,'razao');
			$cidade    		= $dba->result($res,$i,'cidade');
			$uf   		 	= $dba->result($res,$i,'uf');
			$tipo   	 	= $dba->result($res,$i,'tipo');
			$cnpj_emp	 	= $dba->result($res,$i,'cnpj_emp');	
				
			$emp = new EmpresasTxt();

			$emp->setCodigo($cod);
			$emp->setCnpjCpf($cnpj_cpf);
			$emp->setInscEstadual($insc_estadual);
			$emp->setRazao($razao);
			$emp->setCidade($cidade);
			$emp->setUf($uf);
			$emp->setTipo($tipo);
			$emp->setCnpjEmp($cnpj_emp);
			
			$vet[$i] = $emp;

		}

		return $vet;

	}
	
	public function inserir($emptxt){

		$dba = $this->dba;
		
		$cnpj_cpf = $emptxt->getCnpjCpf();
		$insc_est = $emptxt->getInscEstadual();
		$razao	  = $emptxt->getRazao();
		$cidade	  = $emptxt->getCidade();
		$uf		  = $emptxt->getUf();
		$tipo	  = $emptxt->getTipo();
		$cnpjemp  = $emptxt->getCnpjEmp();
		
		$sql = 'INSERT INTO `empresastxt`
			(`cnpj_cpf`,
			`insc_estadual`,
			`razao`,
			`cidade`,
			`uf`,
			`tipo`,
			`cnpj_emp`)
			VALUES
			("'.$cnpj_cpf.'",
			"'.$insc_est.'",
			"'.$razao.'",
			"'.$cidade.'",
			"'.$uf.'",
			"'.$tipo.'",
			"'.$cnpjemp.'")';
		//echo "{$sql}\r";
		$dba->query($sql);	
							
	}
	
	public function update($emptxt){
		$dba = $this->dba;

		$codigo   = $emptxt->getCodigo();
		$cnpj_cpf = $emptxt->getCnpjCpf();
		$insc_est = $emptxt->getInscEstadual();
		$razao	  = $emptxt->getRazao();
		$cidade	  = $emptxt->getCidade();
		$uf		  = $emptxt->getUf();
		$tipo	  = $emptxt->getTipo();
		//$cnpjemp  = $emptxt->getCnpjEmp();

		$sql = 'UPDATE `empresastxt`
				SET
				`cnpj_cpf` = "'.$cnpj_cpf.'",
				`insc_estadual` = "'.$insc_est.'",
				`razao` = "'.$razao.'",
				`cidade` = "'.$cidade.'",
				`uf` = "'.$uf.'",
				`tipo` = "'.$tipo.'"				
				WHERE `id` = '.$codigo.' ';
		//echo "{$sql}\r";		
		$dba->query($sql);
	}

	public function deletar($usu){
	
		$dba = $this->dba;

		$idu = $usu->getCodigo();
		
		$sql = ''.$idu;

		$dba->query($sql);	
				
	}		
}

?>
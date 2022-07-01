<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class FolhaTxtDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	public function ListaFolhaEmpresas($cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					f.id,
					f.data_pag,
					f.num_funcionarios,
					f.valor_folha,
					f.cnpj_emp
				FROM
					folhatxt f
				WHERE f.cnpj_emp = "'.$cnpj.'"	';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod   				= $dba->result($res,$i,'id');
			$data_pag		    = $dba->result($res,$i,'data_pag');
			$num_funcionarios   = $dba->result($res,$i,'num_funcionarios');
			$valor_folha	    = $dba->result($res,$i,'valor_folha');
				
			$folha = new FolhaTxt();

			$folha->setCodigo($cod);
			$folha->setDataPag($data_pag);
			$folha->setNumFuncionario($num_funcionarios);
			$folha->setValorFolha($valor_folha);
			
			$vet[$i] = $folha;

		}

		return $vet;

	}

	public function ListaFolhaEmpresasUm($cnpj,$id){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					f.id,
					f.data_pag,
					f.num_funcionarios,
					f.valor_folha,
					f.cnpj_emp
				FROM
					folhatxt f
				WHERE f.cnpj_emp = "'.$cnpj.'"	and f.id = '.$id.' ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod   				= $dba->result($res,$i,'id');
			$data_pag		    = $dba->result($res,$i,'data_pag');
			$num_funcionarios   = $dba->result($res,$i,'num_funcionarios');
			$valor_folha	    = $dba->result($res,$i,'valor_folha');
				
			$folha = new FolhaTxt();

			$folha->setCodigo($cod);
			$folha->setDataPag($data_pag);
			$folha->setNumFuncionario($num_funcionarios);
			$folha->setValorFolha($valor_folha);
			
			$vet[$i] = $folha;

		}

		return $vet;

	}

	public function ListaFolhaEmpresasApurado($cnpj,$dtp){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					f.id,
					f.data_pag,
					f.num_funcionarios,
					f.valor_folha,
					f.cnpj_emp
				FROM
					folhatxt f
				WHERE f.cnpj_emp = "'.$cnpj.'"	and 
				concat(LPAD(EXTRACT(MONTH FROM f.data_pag),2,"0"),"/",EXTRACT(YEAR FROM f.data_pag)) = "'.$dtp.'" ';
		//echo $sql;	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod   				= $dba->result($res,$i,'id');
			$data_pag		    = $dba->result($res,$i,'data_pag');
			$num_funcionarios   = $dba->result($res,$i,'num_funcionarios');
			$valor_folha	    = $dba->result($res,$i,'valor_folha');
				
			$folha = new FolhaTxt();

			$folha->setCodigo($cod);
			$folha->setDataPag($data_pag);
			$folha->setNumFuncionario($num_funcionarios);
			$folha->setValorFolha($valor_folha);
			
			$vet[$i] = $folha;

		}

		return $vet;

	}

	public function ValidaFolhaMes($cnpj,$dtp){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					f.id,
					f.data_pag,
					f.num_funcionarios,
					f.valor_folha					
				FROM
					folhatxt f
				WHERE f.cnpj_emp = "'.$cnpj.'"	and 
				concat(LPAD(EXTRACT(MONTH FROM f.data_pag),2,"0"),"/",EXTRACT(YEAR FROM f.data_pag)) = "'.$dtp.'" ';
		//echo $sql;	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		
			
			$cod   				= $dba->result($res,$i,'id');
			$data_pag		    = $dba->result($res,$i,'data_pag');
			$num_funcionarios   = $dba->result($res,$i,'num_funcionarios');
			$valor_folha	    = $dba->result($res,$i,'valor_folha');
				
			$folha = new FolhaTxt();
		
			$folha->setCodigo($cod);
			$folha->setDataPag($data_pag);
			$folha->setNumFuncionario($num_funcionarios);
			$folha->setValorFolha($valor_folha);
			
			$vet[$i] = $folha;

		}

		return $vet;

	}
	
	public function BuscaCodigoFolhaDia($cnpj,$dtp){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					f.id							
				FROM
					folhatxt f
				WHERE f.cnpj_emp = "'.$cnpj.'"	and 
				concat(LPAD(EXTRACT(MONTH FROM f.data_pag),2,"0"),"/",EXTRACT(YEAR FROM f.data_pag)) = "'.$dtp.'" /*AND cast(f.data_inserida as date) = current_date*/ ';
		//echo $sql;	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		
			
			$cod   = $dba->result($res,$i,'id');
							
			$folha = new FolhaTxt();
		
			$folha->setCodigo($cod);			
			
			$vet[$i] = $folha;

		}

		return $vet;

	}
	
	public function inserir($folhatxt){

		$dba = $this->dba;		
		
		$datapag    = $folhatxt->getDataPag();
		$numfuncio  = $folhatxt->getNumFuncionario();
		$valorfolha = $folhatxt->getValorFolha();
		$cnpjemp    = $folhatxt->getCnpjEmp();
		
		$sql = 'INSERT INTO `folhatxt`
				(`data_pag`,
				`num_funcionarios`,
				`valor_folha`,
				`cnpj_emp`)
				VALUES
				("'.$datapag.'",
				"'.$numfuncio.'",
				'.$valorfolha.',
				"'.$cnpjemp.'")';

		$dba->query($sql);	
							
	}
	
	public function inserirnumfun($folhatxt){

		$dba = $this->dba;		
		
		$datapag    = $folhatxt->getDataPag();
		$numfuncio  = $folhatxt->getNumFuncionario();		
		$cnpjemp    = $folhatxt->getCnpjEmp();
		
		$sql = 'INSERT INTO `folhatxt`
				(`data_pag`,
				`num_funcionarios`,
				`cnpj_emp`)
				VALUES
				("'.$datapag.'",
				"'.$numfuncio.'",				
				"'.$cnpjemp.'")';

		$dba->query($sql);	
							
	}
	
	public function update($folhatxt){

		$dba = $this->dba;		
		
		$codigo		= $folhatxt->getCodigo();
		$datapag    = $folhatxt->getDataPag();
		$numfuncio  = $folhatxt->getNumFuncionario();
		$valorfolha = $folhatxt->getValorFolha();
		$cnpjemp    = $folhatxt->getCnpjEmp();
		
		$sql = 'UPDATE `folhatxt`
				SET
				`data_pag` = "'.$datapag.'",
				`num_funcionarios` = "'.$numfuncio.'",
				`valor_folha` = '.$valorfolha.'
				WHERE `id` = '.$codigo.' ';
		
		$dba->query($sql);	
							
	}

	public function updatenumfun($folhatxt){

		$dba = $this->dba;		
		
		$codigo		= $folhatxt->getCodigo();		
		$numfuncio  = $folhatxt->getNumFuncionario();				
		
		$sql = 'UPDATE `folhatxt`
				SET				
				`num_funcionarios` = "'.$numfuncio.'"				
				WHERE `id` = '.$codigo.' ';
		
		$dba->query($sql);	
							
	}
	
	public function inserirvlpagto($folhatxt){

		$dba = $this->dba;		
		
		$datapag    = $folhatxt->getDataPag();
		$valorfolha = $folhatxt->getValorFolha();	
		$cnpjemp    = $folhatxt->getCnpjEmp();
		
		$sql = 'INSERT INTO `folhatxt`
				(`data_pag`,
				`valor_folha`,
				`cnpj_emp`)
				VALUES
				("'.$datapag.'",
				 '.$valorfolha.',				
				"'.$cnpjemp.'")';

		$dba->query($sql);	
							
	}

	public function updatevlpagto($folhatxt){

		$dba = $this->dba;		
		
		$codigo		= $folhatxt->getCodigo();		
		$valorfolha = $folhatxt->getValorFolha();				
		
		$sql = 'UPDATE `folhatxt`
				SET				
				`valor_folha` = "'.$valorfolha.'"				
				WHERE `id` = '.$codigo.' ';
		
		$dba->query($sql);	
							
	}

	public function deletar($folhatxt){
	
		$dba = $this->dba;

		$id = $folhatxt->getCodigo();
		
		$sql = 'DELETE FROM folhatxt WHERE id ='.$id;

		$dba->query($sql);	
				
	}	
	
	public function proximoid(){

		$dba = $this->dba;

		$vet = array();		

		$sql = 'SHOW TABLE STATUS LIKE "folhatxt"';

		$res = $dba->query($sql);

		$i = 0;

		$prox_id = $dba->result($res,$i,'Auto_increment');	 

		$folha = new FolhaTxt();

		$folha->setProximoId($prox_id);

		$vet[$i] = $folha;

		return $vet;

	}
	
}

?>
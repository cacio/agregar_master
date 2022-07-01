<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class CondenasTxtDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	public function ListaCondenas($where){

		$dba = $this->dba;

		$vet = array();

		$sql ='SELECT 
					c.id,
					c.numero_nota,
					c.data_emissao,
					c.cnpj_cpf,
					c.codigo_condena,
					c.qtd_condena,
					c.insc_estadual,
					c.cnpj_emp
				FROM
					condenastxt c 
				'.$where.'	';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod  			= $dba->result($res,$i,'id');
			$numero_nota    = $dba->result($res,$i,'numero_nota');
			$data_emissao   = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf		= $dba->result($res,$i,'cnpj_cpf');
			$codigo_condena = $dba->result($res,$i,'codigo_condena');
			$qtd_condena	= $dba->result($res,$i,'qtd_condena');
			$insc_estadual  = $dba->result($res,$i,'insc_estadual');
			$cnpj_emp	    = $dba->result($res,$i,'cnpj_emp');
				
			$cond = new CondenasTxt();

			$cond->setCodigo($cod);
			$cond->setNumeroNota($numero_nota);			
			$cond->setDataEmissao($data_emissao);
			$cond->setCnpjCpf($cnpj_cpf);
			$cond->setCodigoCondena($codigo_condena);
			$cond->setQtdCondena($qtd_condena);
			$cond->setInscEstadual($insc_estadual);
			$cond->setCnpjEmp($cnpj_emp);
			
			$vet[$i] = $cond;

		}

		return $vet;

	}

	public function inserir($condenatxt){

		$dba = $this->dba;		
		
		$numero_nota    	= $condenatxt->getNumeroNota();
		$dataemissao    	= $condenatxt->getDataEmissao();
		$cnpjcpf	    	= $condenatxt->getCnpjCpf();
		$codigo_condena		= $condenatxt->getCodigoCondena();
		$qtdcondena			= $condenatxt->getQtdCondena();
		$inscestadual		= $condenatxt->getInscEstadual();	
		$cnpjemp            = $condenatxt->getCnpjEmp();
		
		$sql = 'INSERT INTO `condenastxt`
				(`numero_nota`,
				`data_emissao`,
				`cnpj_cpf`,
				`codigo_condena`,
				`qtd_condena`,
				`insc_estadual`,
				`cnpj_emp`)
				VALUES
				("'.$numero_nota.'",
				"'.$dataemissao.'",
				"'.$cnpjcpf.'",
				"'.$codigo_condena.'",
				"'.$qtdcondena.'",
				"'.$inscestadual.'",
				"'.$cnpjemp.'")';

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
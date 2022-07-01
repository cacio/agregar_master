<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class GtaTxtDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	public function ListaGtaEmpresas($cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					g.id,
					g.numero_nota,
					g.data_emissao,
					g.cnpj_cpf,
					g.numero_gta,
					g.insc_estadual,
					g.especie,
					g.qtd_animais_macho_idade,
					g.qtd_animais_femea_idade,
					g.qtd_animais_macho_4_12,
					g.qtd_animais_femea_4_12,
					g.qtd_animais_macho_12_24,
					g.qtd_animais_femea_12_24,
					g.qtd_animais_macho_24_36,
					g.qtd_animais_femea_24_36,
					g.qtd_animais_macho_36,
					g.qtd_animais_femea_36,
					g.cnpj_emp
				FROM
					gtatxt g 
				WHERE g.cnpj_emp = "'.$cnpj.'" ';
	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod  		 				 = $dba->result($res,$i,'id');
			$numero_nota 				 = $dba->result($res,$i,'numero_nota');
			$data_emissao 				 = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf  					 = $dba->result($res,$i,'cnpj_cpf');
			$numero_gta   				 = $dba->result($res,$i,'numero_gta');
			$insc_estadual 				 = $dba->result($res,$i,'insc_estadual');
			$especie  		 			 = $dba->result($res,$i,'especie');
			$qtd_animais_macho_idade  	 = $dba->result($res,$i,'qtd_animais_macho_idade');
			$qtd_animais_femea_idade  	 = $dba->result($res,$i,'qtd_animais_femea_idade');
			$qtd_animais_macho_4_12  	 = $dba->result($res,$i,'qtd_animais_macho_4_12');
			$qtd_animais_femea_4_12 	 = $dba->result($res,$i,'qtd_animais_femea_4_12');
			$qtd_animais_macho_12_24	 = $dba->result($res,$i,'qtd_animais_macho_12_24');
			$qtd_animais_femea_12_24	 = $dba->result($res,$i,'qtd_animais_femea_12_24');
			$qtd_animais_macho_24_36	 = $dba->result($res,$i,'qtd_animais_macho_24_36');
			$qtd_animais_femea_24_36	 = $dba->result($res,$i,'qtd_animais_femea_24_36');
			$qtd_animais_macho_36  		 = $dba->result($res,$i,'qtd_animais_macho_36');
			$qtd_animais_femea_36  		 = $dba->result($res,$i,'qtd_animais_femea_36');
			
			$gtatxt = new GtaTxt();

			$gtatxt->setCodigo($cod);
			$gtatxt->setNumeroNota($numero_nota);
			$gtatxt->setDataEmissao($data_emissao);
			$gtatxt->setCnpjCpf($cnpj_cpf);
			$gtatxt->setNumeroGta($numero_gta);
			$gtatxt->setInscEstadual($insc_estadual);
			$gtatxt->setEspecie($especie);
			$gtatxt->setQtdAnimaisMachoIdade($qtd_animais_macho_idade);
			$gtatxt->setQtdAnimaisFemeaIdade($qtd_animais_femea_idade);
			$gtatxt->setQtdAnimaisMacho_4_12($qtd_animais_macho_4_12);
			$gtatxt->setQtdAnimaisFemea_4_12($qtd_animais_femea_4_12);
			$gtatxt->setQtdAnimaisMacho_12_24($qtd_animais_macho_12_24);
			$gtatxt->setQtdAnimaisFemea_12_24($qtd_animais_femea_12_24);
			$gtatxt->setQtdAnimaisMacho_24_36($qtd_animais_macho_24_36);
			$gtatxt->setQtdAnimaisFemea_24_36($qtd_animais_femea_24_36);
			$gtatxt->setQtdAnimaisMacho36($qtd_animais_macho_36);
			$gtatxt->setQtdAnimaisFemea36($qtd_animais_femea_36);
			
			
			$vet[$i] = $gtatxt;

		}

		return $vet;

	}
	
	public function GtaEmpresas($nnota,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT
					t.id,
					t.numero_nota, 
					t.numero_gta
				FROM
					gtatxt t
				WHERE
					t.numero_nota = "'.$nnota.'" AND 
					t.cnpj_emp = "'.$cnpj.'" ';
	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod  		 	= $dba->result($res,$i,'id');
			$numero_nota 	= $dba->result($res,$i,'numero_nota');			
			$numero_gta   	= $dba->result($res,$i,'numero_gta');			
			
			$gtatxt = new GtaTxt();

			$gtatxt->setCodigo($cod);
			$gtatxt->setNumeroNota($numero_nota);			
			$gtatxt->setNumeroGta($numero_gta);
			
			$vet[$i] = $gtatxt;

		}

		return $vet;

	}
	
	public function inserir($gtatxt){

		$dba = $this->dba;		
		
		$numero_nota    	  = $gtatxt->getNumeroNota();
		$dataemissao    	  = $gtatxt->getDataEmissao();
		$cnpjcpf	    	  = $gtatxt->getCnpjCpf();
		$numeriogta			  = $gtatxt->getNumeroGta();				
		$inscestadual	  	  = $gtatxt->getInscEstadual();	
		$especie			  = $gtatxt->getEspecie();
		$qtdanimaismachoidade =	$gtatxt->getQtdAnimaisMachoIdade();
		$qtdanimaisfemeaidade = $gtatxt->getQtdAnimaisFemeaIdade();
		$qtdanimaismacho4a12  = $gtatxt->getQtdAnimaisMacho_4_12();
		$qtdanimaisfemea4a12  = $gtatxt->getQtdAnimaisFemea_4_12();
		$qtdanimaismacho12a24 = $gtatxt->getQtdAnimaisMacho_12_24();
		$qtdanimaisfemea12a24 = $gtatxt->getQtdAnimaisFemea_12_24();
		$qtdanimaismacho24a36 = $gtatxt->getQtdAnimaisMacho_24_36();
		$qtdanimaisfemea24a36 = $gtatxt->getQtdAnimaisFemea_24_36();
		$qtdanimaismacho36	  =	$gtatxt->getQtdAnimaisMacho36();
		$qtdanimaisfemea36    = $gtatxt->getQtdAnimaisFemea36();
		$cnpjemp              = $gtatxt->getCnpjEmp();
		
		$sql = 'INSERT INTO `gtatxt`
				(`numero_nota`,
				`data_emissao`,
				`cnpj_cpf`,
				`numero_gta`,
				`insc_estadual`,
				`especie`,
				`qtd_animais_macho_idade`,
				`qtd_animais_femea_idade`,
				`qtd_animais_macho_4_12`,
				`qtd_animais_femea_4_12`,
				`qtd_animais_macho_12_24`,
				`qtd_animais_femea_12_24`,
				`qtd_animais_macho_24_36`,
				`qtd_animais_femea_24_36`,
				`qtd_animais_macho_36`,
				`qtd_animais_femea_36`,
				`cnpj_emp`)
				VALUES
				("'.$numero_nota.'",
				"'.$dataemissao.'",
				"'.$cnpjcpf.'",
				"'.$numeriogta.'",
				"'.$inscestadual.'",
				"'.$especie.'",
				"'.$qtdanimaismachoidade.'",
				"'.$qtdanimaisfemeaidade.'",
				"'.$qtdanimaismacho4a12.'",
				"'.$qtdanimaisfemea4a12.'",
				"'.$qtdanimaismacho12a24.'",
				"'.$qtdanimaisfemea12a24.'",
				"'.$qtdanimaismacho24a36.'",
				"'.$qtdanimaisfemea24a36.'",
				"'.$qtdanimaismacho36.'",
				"'.$qtdanimaisfemea36.'",
				"'.$cnpjemp.'")';

		$dba->query($sql);	
							
	}
	
	public function inserirgta($gtatxt){

		$dba = $this->dba;		
		
		$numero_nota    	  = $gtatxt->getNumeroNota();
		$dataemissao    	  = $gtatxt->getDataEmissao();		
		$numeriogta			  = $gtatxt->getNumeroGta();						
		$cnpjemp              = $gtatxt->getCnpjEmp();
		
		$sql = 'INSERT INTO `gtatxt`
				(`numero_nota`,
				`data_emissao`,				
				`numero_gta`,				
				`cnpj_emp`)
				VALUES
				("'.$numero_nota.'",
				"'.$dataemissao.'",			
				"'.$numeriogta.'",				
				"'.$cnpjemp.'")';
		//echo $sql."\r";
		$dba->query($sql);	
							
	}

	public function proximoid(){
		
		$dba = $this->dba;

		$vet = array();		

		$sql = 'SHOW TABLE STATUS LIKE "gtatxt"';

		$res = $dba->query($sql);

		$i = 0;

		$prox_id = $dba->result($res,$i,'Auto_increment');	 

		$gtatxt = new GtaTxt();

		$gtatxt->setProxId($prox_id);

		$vet[$i] = $gtatxt;

		return $vet;	

	}

	public function deletar($gtatxt){
	
		$dba = $this->dba;

		$id  = $gtatxt->getCodigo();
		
		$sql = 'DELETE FROM `gtatxt`
				WHERE id ='.$id;
		
		$dba->query($sql);	
				
	}		
}

?>
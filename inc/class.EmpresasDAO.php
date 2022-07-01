<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class EmpresasDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	public function ListaEmpresa(){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					e.id,
					e.cnpj,
					e.razao_social,
					e.fantasia,
					e.marca,
					e.ins_estadual,
					e.endereco,
					e.nro,
					e.complemento,
					e.cep,
					e.cidade,
					e.estado,
					e.bairro,
					e.inspecao,
					e.fone1,
					e.fone2,
					e.email,
					e.responsavel,
					e.id_modalidade,
					e.capacidade_bovinos,
					e.capacidade_ovinos,
					(select count(*) from usuario u where u.id_empresa = e.id) as user
				FROM
					empresas e';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 				= $dba->result($res,$i,'id');
			$cnpj 				= $dba->result($res,$i,'cnpj');
			$razao_social 		= $dba->result($res,$i,'razao_social');
			$fantasia 			= $dba->result($res,$i,'fantasia');
			$marca 				= $dba->result($res,$i,'marca');
			$ins_estadual 		= $dba->result($res,$i,'ins_estadual');
			$endereco 			= $dba->result($res,$i,'endereco');
			$nro 				= $dba->result($res,$i,'nro');
			$complemento 		= $dba->result($res,$i,'complemento');
			$cep 				= $dba->result($res,$i,'cep');
			$cidade 			= $dba->result($res,$i,'cidade');
			$estado 			= $dba->result($res,$i,'estado');
			$bairro 			= $dba->result($res,$i,'bairro');
			$inspecao 			= $dba->result($res,$i,'inspecao');
			$fone1 				= $dba->result($res,$i,'fone1');
			$fone2 				= $dba->result($res,$i,'fone2');
			$email 				= $dba->result($res,$i,'email');
			$responsavel 		= $dba->result($res,$i,'responsavel');
			$id_modalidade 		= $dba->result($res,$i,'id_modalidade');
			$capacidade_bovinos = $dba->result($res,$i,'capacidade_bovinos');
			$capacidade_ovinos  = $dba->result($res,$i,'capacidade_ovinos');
			$user			    = $dba->result($res,$i,'user');
			
			$emp = new Empresas();

			$emp->setCodigo($cod);				
			$emp->setCnpj($cnpj);
			$emp->setRazaoSocial($razao_social);
			$emp->setFantasia($fantasia);
			$emp->setMarca($marca);
			$emp->setInscricaoEstadual($ins_estadual);
			$emp->setEndereco($endereco);
			$emp->setNumero($nro);
			$emp->setComplemento($complemento);
			$emp->setCep($cep);
			$emp->setCidade($cidade);
			$emp->setEstado($estado);
			$emp->setBairro($bairro);
			$emp->setInspecao($inspecao);
			$emp->setFone1($fone1);
			$emp->setFone2($fone2);
			$emp->setEmail($email);
			$emp->setResponsavel($responsavel);
			$emp->setIdModalidade($id_modalidade);
			$emp->setCapacidadeBovino($capacidade_bovinos);
			$emp->setCapacidadeOvino($capacidade_ovinos);
			$emp->setLengthUser($user);
			
			$vet[$i] = $emp;

		}

		return $vet;

	}
	
	public function ListaEmpresaUm($cod){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					e.id,
					e.cnpj,
					e.razao_social,
					e.fantasia,
					e.marca,
					e.ins_estadual,
					e.endereco,
					e.nro,
					e.complemento,
					e.cep,
					e.cidade,
					e.estado,
					e.bairro,
					e.inspecao,
					e.fone1,
					e.fone2,
					e.email,
					e.responsavel,
					e.id_modalidade,
					e.capacidade_bovinos,
					e.capacidade_ovinos,
					e.dt_num_arq_ult_ato_junt_comerc,
					e.form_const_juridica,
					e.cap_social_reg,
					e.ativo,
					e.capacidade_bubalino
				FROM
					empresas e WHERE e.id = "'.$cod.'" ';			  
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 				 = $dba->result($res,$i,'id');
			$cnpj 				 = $dba->result($res,$i,'cnpj');
			$razao_social 		 = $dba->result($res,$i,'razao_social');
			$fantasia 			 = $dba->result($res,$i,'fantasia');
			$marca 				 = $dba->result($res,$i,'marca');
			$ins_estadual 		 = $dba->result($res,$i,'ins_estadual');
			$endereco 			 = $dba->result($res,$i,'endereco');
			$nro 				 = $dba->result($res,$i,'nro');
			$complemento 		 = $dba->result($res,$i,'complemento');
			$cep 				 = $dba->result($res,$i,'cep');
			$cidade 			 = $dba->result($res,$i,'cidade');
			$estado 			 = $dba->result($res,$i,'estado');
			$bairro 			 = $dba->result($res,$i,'bairro');
			$inspecao 			 = $dba->result($res,$i,'inspecao');
			$fone1 				 = $dba->result($res,$i,'fone1');
			$fone2 				 = $dba->result($res,$i,'fone2');
			$email 				 = $dba->result($res,$i,'email');
			$responsavel 		 = $dba->result($res,$i,'responsavel');
			$id_modalidade 		 = $dba->result($res,$i,'id_modalidade');
			$capacidade_bovinos  = $dba->result($res,$i,'capacidade_bovinos');
			$capacidade_ovinos   = $dba->result($res,$i,'capacidade_ovinos');
			$dt_num_arq_ult_ato  = $dba->result($res,$i,'dt_num_arq_ult_ato_junt_comerc');
			$form_const_juridica = $dba->result($res,$i,'form_const_juridica');
			$cap_social_reg      = $dba->result($res,$i,'cap_social_reg');
			$ativo			     = $dba->result($res,$i,'ativo');
			$capacidade_bubalino = $dba->result($res,$i,'capacidade_bubalino');
			
			$emp = new Empresas();

			$emp->setCodigo($cod);				
			$emp->setCnpj($cnpj);
			$emp->setRazaoSocial($razao_social);
			$emp->setFantasia($fantasia);
			$emp->setMarca($marca);
			$emp->setInscricaoEstadual($ins_estadual);
			$emp->setEndereco($endereco);
			$emp->setNumero($nro);
			$emp->setComplemento($complemento);
			$emp->setCep($cep);
			$emp->setCidade($cidade);
			$emp->setEstado($estado);
			$emp->setBairro($bairro);
			$emp->setInspecao($inspecao);
			$emp->setFone1($fone1);
			$emp->setFone2($fone2);
			$emp->setEmail($email);
			$emp->setResponsavel($responsavel);
			$emp->setIdModalidade($id_modalidade);
			$emp->setCapacidadeBovino($capacidade_bovinos);
			$emp->setCapacidadeOvino($capacidade_ovinos);
			$emp->setDtAtoJuntaComercial($dt_num_arq_ult_ato);
			$emp->setFormConstituicaoJuridica($form_const_juridica);
			$emp->setCapSocialReg($cap_social_reg);
			$emp->setAtivo($ativo);
			$emp->setCapacidadeBubalino($capacidade_bubalino);
			
			$vet[$i] = $emp;

		}

		return $vet;

	}
	
	public function ListaEmpresaUmCnpj($cnpj){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					e.id,
					e.cnpj,
					e.razao_social,
					e.fantasia,
					e.marca,
					e.ins_estadual,
					e.endereco,
					e.nro,
					e.complemento,
					e.cep,
					e.cidade,
					e.estado,
					e.bairro,
					e.inspecao,
					e.fone1,
					e.fone2,
					e.email,
					e.responsavel,
					e.id_modalidade,
					e.capacidade_bovinos,
					e.capacidade_ovinos,
					e.dt_num_arq_ult_ato_junt_comerc,
					e.form_const_juridica,
					e.cap_social_reg,
					e.ativo,
					e.capacidade_bubalino
				FROM
					empresas e WHERE e.cnpj = "'.$cnpj.'" ';			  
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 				 = $dba->result($res,$i,'id');
			$cnpj 				 = $dba->result($res,$i,'cnpj');
			$razao_social 		 = $dba->result($res,$i,'razao_social');
			$fantasia 			 = $dba->result($res,$i,'fantasia');
			$marca 				 = $dba->result($res,$i,'marca');
			$ins_estadual 		 = $dba->result($res,$i,'ins_estadual');
			$endereco 			 = $dba->result($res,$i,'endereco');
			$nro 				 = $dba->result($res,$i,'nro');
			$complemento 		 = $dba->result($res,$i,'complemento');
			$cep 				 = $dba->result($res,$i,'cep');
			$cidade 			 = $dba->result($res,$i,'cidade');
			$estado 			 = $dba->result($res,$i,'estado');
			$bairro 			 = $dba->result($res,$i,'bairro');
			$inspecao 			 = $dba->result($res,$i,'inspecao');
			$fone1 				 = $dba->result($res,$i,'fone1');
			$fone2 				 = $dba->result($res,$i,'fone2');
			$email 				 = $dba->result($res,$i,'email');
			$responsavel 		 = $dba->result($res,$i,'responsavel');
			$id_modalidade 		 = $dba->result($res,$i,'id_modalidade');
			$capacidade_bovinos  = $dba->result($res,$i,'capacidade_bovinos');
			$capacidade_ovinos   = $dba->result($res,$i,'capacidade_ovinos');
			$dt_num_arq_ult_ato  = $dba->result($res,$i,'dt_num_arq_ult_ato_junt_comerc');
			$form_const_juridica = $dba->result($res,$i,'form_const_juridica');
			$cap_social_reg      = $dba->result($res,$i,'cap_social_reg');
			$ativo			     = $dba->result($res,$i,'ativo');
			$capacidade_bubalino = $dba->result($res,$i,'capacidade_bubalino');
			
			$emp = new Empresas();

			$emp->setCodigo($cod);				
			$emp->setCnpj($cnpj);
			$emp->setRazaoSocial($razao_social);
			$emp->setFantasia($fantasia);
			$emp->setMarca($marca);
			$emp->setInscricaoEstadual($ins_estadual);
			$emp->setEndereco($endereco);
			$emp->setNumero($nro);
			$emp->setComplemento($complemento);
			$emp->setCep($cep);
			$emp->setCidade($cidade);
			$emp->setEstado($estado);
			$emp->setBairro($bairro);
			$emp->setInspecao($inspecao);
			$emp->setFone1($fone1);
			$emp->setFone2($fone2);
			$emp->setEmail($email);
			$emp->setResponsavel($responsavel);
			$emp->setIdModalidade($id_modalidade);
			$emp->setCapacidadeBovino($capacidade_bovinos);
			$emp->setCapacidadeOvino($capacidade_ovinos);
			$emp->setDtAtoJuntaComercial($dt_num_arq_ult_ato);
			$emp->setFormConstituicaoJuridica($form_const_juridica);
			$emp->setCapSocialReg($cap_social_reg);
			$emp->setAtivo($ativo);
			$emp->setCapacidadeBubalino($capacidade_bubalino);
			
			$vet[$i] = $emp;

		}

		return $vet;

	}
	
	public function ListaEmpresaUmCnpjDbf($cnpj){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					e.cnpj,
					e.ins_estadual,
					e.razao_social,
					e.cidade,
					e.estado,
					"S" AS TIPISEM,
					"1733" AS REGINSP,
					v.nome AS MEDVETE,
					v.n_crmv AS NUMCRMV,
					"400" AS CAPDIAB,
					"N" AS RALIZSN,
					"0,00" AS PERABTE,
					"1" AS INSTPAA
				FROM
					empresas e
						INNER JOIN
					tab_veterinario_estabelecimento_qa v ON (v.id_emp = e.id)
				where e.cnpj = "'.$cnpj.'" ';			  
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cnpj 				 = $dba->result($res,$i,'cnpj');
			$ins_estadual 		 = $dba->result($res,$i,'ins_estadual');
			$razao_social 		 = $dba->result($res,$i,'razao_social');
			$cidade 			 = $dba->result($res,$i,'cidade');
			$estado 			 = $dba->result($res,$i,'estado');
			$tipisem 			 = $dba->result($res,$i,'TIPISEM');	
			$reginsp 			 = $dba->result($res,$i,'REGINSP');
			$medvete 			 = $dba->result($res,$i,'MEDVETE');
			$numcrmv 			 = $dba->result($res,$i,'NUMCRMV');
			$capdiab 			 = $dba->result($res,$i,'CAPDIAB');
			$ralizsn 			 = $dba->result($res,$i,'RALIZSN');
			$perabte 			 = $dba->result($res,$i,'PERABTE');
			$instpaa 			 = $dba->result($res,$i,'INSTPAA');
			
			$emp = new Empresas();
				
			$emp->setCnpj($cnpj);
			$emp->setInscricaoEstadual($ins_estadual);
			$emp->setRazaoSocial($razao_social);
			$emp->setCidade($cidade);
			$emp->setEstado($estado);
			$emp->setTipiSem($tipisem);	
			$emp->setReginSP($reginsp);
			$emp->setMedVete($medvete);
			$emp->setNumCrmv($numcrmv);
			$emp->setCapDiab($capdiab);
			$emp->setRalizSN($ralizsn);
			$emp->setPerabte($perabte);
			$emp->setInstpaa($instpaa);
			
			$vet[$i] = $emp;

		}

		return $vet;

	}
	
	public function VerificaEmpresa($cnpj){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					e.id,
					e.cnpj,
					e.razao_social as fantasia,
					e.email,
					e.ativo
				FROM
					empresas e
				WHERE
					e.cnpj = "'.$cnpj.'"';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 	  = $dba->result($res,$i,'id');
			$cnpj     = $dba->result($res,$i,'cnpj');
			$fantasia = $dba->result($res,$i,'fantasia');
			$email 	  = $dba->result($res,$i,'email');
			$ativo 	  = $dba->result($res,$i,'ativo');
			
			$emp = new Empresas();

			$emp->setCodigo($cod);				
			$emp->setCnpj($cnpj);
			$emp->setFantasia($fantasia);
			$emp->setEmail($email);
			$emp->setAtivo($ativo);
			
			$vet[$i] = $emp;

		}

		return $vet;

	}
		public function ListaEmpresaSelecionada($cod){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					e.id,
					e.razao_social,
					e.fantasia,
					case
						when e.id = "'.$cod.'" then "selected"
						else ""
					end as selected
				FROM
					empresas e';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 				= $dba->result($res,$i,'id');
			$razao_social 		= $dba->result($res,$i,'razao_social');
			$fantasia 			= $dba->result($res,$i,'fantasia');
			$selected 			= $dba->result($res,$i,'selected');
			
			
			$emp = new Empresas();

			$emp->setCodigo($cod);				
			$emp->setRazaoSocial($razao_social);
			$emp->setFantasia($fantasia);
			$emp->setSeleciona($selected);
			
			
			$vet[$i] = $emp;

		}

		return $vet;

	}
	
	
	public function RelatorioEmpresaPorModalidade($where){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					e.id,
					e.cnpj,
					e.razao_social,
					e.fantasia,        
					e.endereco,
					e.nro,
					e.cidade,
					e.estado,    
					e.inspecao    
				FROM
					empresas e 
				'.$where.' ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 				= $dba->result($res,$i,'id');
			$cnpj 				= $dba->result($res,$i,'cnpj');
			$razao_social 		= $dba->result($res,$i,'razao_social');
			$fantasia 			= $dba->result($res,$i,'fantasia');	
			$endereco 			= $dba->result($res,$i,'endereco');
			$nro 				= $dba->result($res,$i,'nro');
			$cidade 			= $dba->result($res,$i,'cidade');
			$estado 			= $dba->result($res,$i,'estado');
			$inspecao 			= $dba->result($res,$i,'inspecao');
			
			
			$emp = new Empresas();

			$emp->setCodigo($cod);				
			$emp->setCnpj($cnpj);
			$emp->setRazaoSocial($razao_social);
			$emp->setFantasia($fantasia);
			$emp->setEndereco($endereco);
			$emp->setNumero($nro);
			$emp->setCidade($cidade);
			$emp->setEstado($estado);
			$emp->setInspecao($inspecao);		
			
			
			$vet[$i] = $emp;

		}

		return $vet;

	}
	
	
	public function ListaEmpresaPorModalidadeUm($where){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					e.id,
					e.cnpj,
					e.razao_social,
					e.fantasia,        
					e.endereco,
					e.nro,
					e.cidade,
					e.estado,    
					e.inspecao    
				FROM
					empresas e 
				'.$where.' ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 				= $dba->result($res,$i,'id');
			$cnpj 				= $dba->result($res,$i,'cnpj');
			$razao_social 		= $dba->result($res,$i,'razao_social');
			$fantasia 			= $dba->result($res,$i,'fantasia');	
			$endereco 			= $dba->result($res,$i,'endereco');
			$nro 				= $dba->result($res,$i,'nro');
			$cidade 			= $dba->result($res,$i,'cidade');
			$estado 			= $dba->result($res,$i,'estado');
			$inspecao 			= $dba->result($res,$i,'inspecao');
			
			
			$emp = new Empresas();

			$emp->setCodigo($cod);				
			$emp->setCnpj($cnpj);
			$emp->setRazaoSocial($razao_social);
			$emp->setFantasia($fantasia);
			$emp->setEndereco($endereco);
			$emp->setNumero($nro);
			$emp->setCidade($cidade);
			$emp->setEstado($estado);
			$emp->setInspecao($inspecao);		
			
			
			$vet[$i] = $emp;

		}

		return $vet;

	}
	
	public function BuscaEmpresa($term){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					e.id,
					e.cnpj,
					e.razao_social,
					e.fantasia,        
					e.endereco,
					e.nro,
					e.cidade,
					e.estado,    
					e.inspecao    
				FROM
					empresas e 
				where (e.razao_social LIKE "%'.$term.'%"
		        OR e.fantasia LIKE "%'.$term.'%"
		        OR e.cnpj LIKE "%'.$term.'%") ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 				= $dba->result($res,$i,'id');
			$cnpj 				= $dba->result($res,$i,'cnpj');
			$razao_social 		= $dba->result($res,$i,'razao_social');
			$fantasia 			= $dba->result($res,$i,'fantasia');	
			$endereco 			= $dba->result($res,$i,'endereco');
			$nro 				= $dba->result($res,$i,'nro');
			$cidade 			= $dba->result($res,$i,'cidade');
			$estado 			= $dba->result($res,$i,'estado');
			$inspecao 			= $dba->result($res,$i,'inspecao');
			
			
			$emp = new Empresas();

			$emp->setCodigo($cod);				
			$emp->setCnpj($cnpj);
			$emp->setRazaoSocial($razao_social);
			$emp->setFantasia($fantasia);
			$emp->setEndereco($endereco);
			$emp->setNumero($nro);
			$emp->setCidade($cidade);
			$emp->setEstado($estado);
			$emp->setInspecao($inspecao);		
			
			
			$vet[$i] = $emp;

		}

		return $vet;

	}

	public function ListaEmpresaParaBenificioArrecadacao($where){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					e.cnpj,
				    e.razao_social,
				    e.cidade  
				FROM
					empresas e 
				'.$where.' ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){
			
			$cnpj 				= $dba->result($res,$i,'cnpj');
			$razao_social 		= $dba->result($res,$i,'razao_social');			
			$cidade 			= $dba->result($res,$i,'cidade');			
			
			$emp = new Empresas();
			
			$emp->setCnpj($cnpj);
			$emp->setRazaoSocial($razao_social);			
			$emp->setCidade($cidade);						
			
			$vet[$i] = $emp;

		}

		return $vet;

	}
	
	public function inserir($emp){

		$dba = $this->dba;
		
		$cnpj 		     = $emp->getCnpj();
		$razao_social    = $emp->getRazaoSocial();
		$fantasia	     = $emp->getFantasia();
		$marca		     = $emp->getMarca();
		$insc_estadual   = $emp->getInscricaoEstadual();
		$endereco	     = $emp->getEndereco();
		$numero		     = $emp->getNumero();
		$complemento     = $emp->getComplemento();
		$cep		     = $emp->getCep();
		$cidade		     = $emp->getCidade();
		$Estado		     = $emp->getEstado();
		$bairro		     = $emp->getBairro();
		$inspecao	     = $emp->getInspecao();
		$fone1		     = $emp->getFone1();
		$fone2		     = $emp->getFone2();
		$email		     = $emp->getEmail();
		$responsavel     = $emp->getResponsavel();
		$idmodalidade    = $emp->getIdModalidade();
		$capacidade_bov  = $emp->getCapacidadeBovino();
		$capacidade_ovi  = $emp->getCapacidadeOvino();
		$dt_ult_junt_com = $emp->getDtAtoJuntaComercial();
		$form_const_juri = $emp->getFormConstituicaoJuridica();
		$capsocialreg	 = $emp->getCapSocialReg();
		$ativo			 = $emp->getAtivo();
		$capacidade_buba = $emp->getCapacidadeBubalino();
		
		$sql = 'INSERT INTO `empresas`
				(`cnpj`,
				`razao_social`,
				`fantasia`,
				`marca`,
				`ins_estadual`,
				`endereco`,
				`nro`,
				`complemento`,
				`cep`,
				`cidade`,
				`estado`,
				`bairro`,
				`inspecao`,
				`fone1`,
				`fone2`,
				`email`,
				`responsavel`,
				`id_modalidade`,
				`capacidade_bovinos`,
				`capacidade_ovinos`,
				`dt_num_arq_ult_ato_junt_comerc`,
				`form_const_juridica`,
				`cap_social_reg`,
				`ativo`,
				`capacidade_bubalino`)
				VALUES
				("'.$cnpj.'",
				"'.$razao_social.'",
				"'.$fantasia.'",
				"'.$marca.'",
				"'.$insc_estadual.'",
				"'.$endereco.'",
				"'.$numero.'",
				"'.$complemento.'",
				"'.$cep.'",
				"'.$cidade.'",
				"'.$Estado.'",
				"'.$bairro.'",
				"'.$inspecao.'",
				"'.$fone1.'",
				"'.$fone2.'",
				"'.$email.'",
				"'.$responsavel.'",
				'.$idmodalidade.',
				"'.$capacidade_bov.'",
				"'.$capacidade_ovi.'",
				"'.$dt_ult_junt_com.'",
				"'.$form_const_juri.'",
				"'.$capsocialreg.'",
				"'.$ativo.'",
				"'.$capacidade_buba.'")';					

		$dba->query($sql);	
	
	}
	
	public function inserir2($emp){

		$dba = $this->dba;
		
		$cnpj 		     = $emp->getCnpj();
		$razao_social    = $emp->getRazaoSocial();
		$fantasia	     = $emp->getFantasia();
		$endereco	     = $emp->getEndereco();
		$numero		     = $emp->getNumero();
		$cep		     = $emp->getCep();
		$cidade		     = $emp->getCidade();
		$Estado		     = $emp->getEstado();
		$bairro		     = $emp->getBairro();
		$fone1		     = $emp->getFone1();
		$email		     = $emp->getEmail();
		$ativo			 = $emp->getAtivo();
		
		$sql = 'INSERT INTO `empresas`
				(`cnpj`,
				`razao_social`,
				`fantasia`,
				`endereco`,
				`nro`,
				`cep`,
				`cidade`,
				`estado`,
				`bairro`,
				`fone1`,
				`email`,
				`ativo`)
				VALUES
				("'.$cnpj.'",
				"'.$razao_social.'",
				"'.$fantasia.'",
				"'.$endereco.'",
				"'.$numero.'",
				"'.$cep.'",
				"'.$cidade.'",
				"'.$Estado.'",
				"'.$bairro.'",
				"'.$fone1.'",
				"'.$email.'",
				"'.$ativo.'")';					

		$dba->query($sql);	
	
	}
	

	public function update($emp){

		$dba = $this->dba;

		$id				= $emp->getCodigo();
		$cnpj 		    = $emp->getCnpj();
		$razao_social   = $emp->getRazaoSocial();
		$fantasia	    = $emp->getFantasia();
		$marca		    = $emp->getMarca();
		$insc_estadual  = $emp->getInscricaoEstadual();
		$endereco	    = $emp->getEndereco();
		$numero		    = $emp->getNumero();
		$complemento    = $emp->getComplemento();
		$cep		    = $emp->getCep();
		$cidade		    = $emp->getCidade();
		$Estado		    = $emp->getEstado();
		$bairro		    = $emp->getBairro();
		$inspecao	    = $emp->getInspecao();
		$fone1		    = $emp->getFone1();
		$fone2		    = $emp->getFone2();
		$email		    = $emp->getEmail();
		$responsavel    = $emp->getResponsavel();
		$idmodalidade   = $emp->getIdModalidade();
		$capacidade_bov = $emp->getCapacidadeBovino();
		$capacidade_ovi = $emp->getCapacidadeOvino();
		$dt_ult_junt_com = $emp->getDtAtoJuntaComercial();
		$form_const_juri = $emp->getFormConstituicaoJuridica();
		$capsocialreg	 = $emp->getCapSocialReg();
		$ativo			 = $emp->getAtivo();
		$capacidade_buba = $emp->getCapacidadeBubalino();
		
		$sql = 'UPDATE `empresas`
				SET
				`cnpj` = "'.$cnpj.'",
				`razao_social` = "'.$razao_social.'",
				`fantasia` = "'.$fantasia.'",
				`marca` = "'.$marca.'",
				`ins_estadual` = "'.$insc_estadual.'",
				`endereco` = "'.$endereco.'",
				`nro` = "'.$numero.'",
				`complemento` = "'.$complemento.'",
				`cep` = "'.$cep.'",
				`cidade` = "'.$cidade.'",
				`estado` = "'.$Estado.'",
				`bairro` = "'.$bairro.'",
				`inspecao` = "'.$inspecao.'",
				`fone1` = "'.$fone1.'",
				`fone2` = "'.$fone2.'",
				`email` = "'.$email.'",
				`responsavel` = "'.$responsavel.'",
				`id_modalidade` = '.$idmodalidade.',
				`capacidade_bovinos` = "'.$capacidade_bov.'",
				`capacidade_ovinos` = "'.$capacidade_ovi.'",
				`dt_num_arq_ult_ato_junt_comerc` = "'.$dt_ult_junt_com.'",
				`form_const_juridica` = "'.$form_const_juri.'",
				`cap_social_reg` = "'.$capsocialreg.'",
				`ativo` = "'.$ativo.'",
				`capacidade_bubalino` = "'.$capacidade_buba.'"
				WHERE `id` ='.$id;
		//echo $sql;	
		$dba->query($sql);	

	}

	public function deletar($emp){
	
		$dba = $this->dba;

		$id = $emp->getCodigo();
		
		$sql = 'DELETE FROM empresas WHERE id='.$id;

		$dba->query($sql);	
		
	}
	
	public function proximoid(){
		
		$dba = $this->dba;
		$vet = array();
		
		$sql = 'SHOW TABLE STATUS LIKE "empresas"';
		$res = $dba->query($sql);
		$i = 0;
		$prox_id = $dba->result($res,$i,'Auto_increment');	 
		$emp = new Empresas();
		$emp->setIdProx($prox_id);
		$vet[$i] = $emp;
		return $vet;
	
	}
	
	public function mask($val, $mask)
	{
		 $maskared = '';
		 $k = 0;
		 for($i = 0; $i<=strlen($mask)-1; $i++){
		 if($mask[$i] == '#'){
		 if(isset($val[$k]))		 
		 	$maskared .= $val[$k++];
		 }else{
			 if(isset($mask[$i]))
			 $maskared .= $mask[$i];
			 }
		 }
		 return $maskared;
	}

}

?>
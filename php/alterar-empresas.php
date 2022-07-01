<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/alterar-empresas.htm');

	$tpl->prepare();

	

	/**************************************************************/

		

		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');
		//require_once('../inc/inc.permissao.php');
		$tpl->assign('log',$_SESSION['login']);

		$codigo =  $_REQUEST['cod_emp'];
		
		$dao = new EmpresasDAO();
		$vet = $dao->ListaEmpresaUm($codigo);
		$num = count($vet);
		
		if($num > 0){
			
		
			$emp = $vet[0];
			
			
			
			$cod 		     = $emp->getCodigo();
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
			
			$sel1 = '';
			$sel2 = '';
			$sel3 = '';
			$sel4 = '';
			$sel5 = '';
			
			if($inspecao == "SIF"){
			$sel1 = 'selected';	
			}else if($inspecao == "CISPOA"){
			$sel2 = 'selected';		
			}else if($inspecao == "SIM"){
			$sel3 = 'selected';		
			}else if($inspecao == "SISB"){
			$sel4 = 'selected';		
			}else if($inspecao == "DIPOA"){
			$sel5 = 'selected';		
			}
			
			$xativo = "";
			if($ativo == '1'){
				$xativo = "checked";
			}
			
			$tpl->assign('cod',$cod);
			$tpl->assign('codigo',$codigo);
			$tpl->assign('cnpj',$cnpj);
			$tpl->assign('razao_social',$razao_social);
			$tpl->assign('fantasia',$fantasia);
			$tpl->assign('marca',$marca);
			$tpl->assign('insc_estadual',$insc_estadual);
			$tpl->assign('endereco',$endereco);
			$tpl->assign('numero',$numero);
			$tpl->assign('complemento',$complemento);
			$tpl->assign('cep',$cep);
			$tpl->assign('cidade',$cidade);
			$tpl->assign('Estado',$Estado);
			$tpl->assign('bairro',$bairro);
			$tpl->assign('inspecao',$inspecao);
			$tpl->assign('fone1',$fone1);
			$tpl->assign('fone2',$fone2);
			$tpl->assign('email',$email);
			$tpl->assign('responsavel',$responsavel);
			$tpl->assign('idmodalidade',$idmodalidade);
			$tpl->assign('capacidade_bov',$capacidade_bov);
			$tpl->assign('capacidade_ovi',$capacidade_ovi);
			$tpl->assign('dt_ult_junt_com',$dt_ult_junt_com);
			$tpl->assign('form_const_juri',$form_const_juri);
			$tpl->assign('capsocialreg',number_format($capsocialreg,2,',','.'));
			$tpl->assign('xativo',$xativo);
			$tpl->assign('ativo',$ativo);
			$tpl->assign('capacidade_buba',$capacidade_buba);
			
			$tpl->assign('sel1',$sel1);
			$tpl->assign('sel2',$sel2);
			$tpl->assign('sel3',$sel3);
			$tpl->assign('sel4',$sel4);
			$tpl->assign('sel5',$sel5);
			$daom = new ModalidadeDAO();
			
			//se for agregar mostra os dados para questionario
			if($idmodalidade == '1'){
				
				$tpl->assign('abilita','agregar');	
				$tpl->assign('selecionadoabt','');
				
				## pegando a modalidade
				
				$vetmd = $daom->ListaModalidadeUm($idmodalidade);
				$nummd = count($vetmd);
				
				if($nummd > 0){
					
					$mods = $vetmd[0];
					$nome_mods = $mods->getNome();	
					$tpl->assign('nome_mods',$nome_mods);
				}else{
					$nome_mods = "";
					$tpl->assign('nome_mods',$nome_mods);
				}
				
				## pegando a modalidade
				
				## PEGANDO DADOS tab_endereco_escritorio_qa
				
				$daoesc = new EnderecoEscritorioDAO();
				$vetesc = $daoesc->ListaEnderecoEscritorioPorEmpresa($cod);
				$numesc = count($vetesc);
				
				if($numesc > 0){
					
					$esc = $vetesc[0];
						
					$endereco_esc	 = $esc->getEndereco();
					$municipio_esc	 = $esc->getMunicipio();
					$cep_esc 		 = $esc->getCep();
					$fone_esc		 = $esc->getFone();
					$email_esc		 = $esc->getEmail();
					
					$tpl->assign('endereco_esc',$endereco_esc);
					$tpl->assign('municipio_esc',$municipio_esc);
					$tpl->assign('cep_esc',$cep_esc);
					$tpl->assign('fone_esc',$fone_esc);
					$tpl->assign('email_esc',$email_esc);
						
				}else{
					
					$tpl->assign('endereco_esc','');
					$tpl->assign('municipio_esc','');
					$tpl->assign('cep_esc','');
					$tpl->assign('fone_esc','');
					$tpl->assign('email_esc','');
					
				}
				## PEGANDO DADOS tab_endereco_escritorio_qa
				
				## PEGANDO DADOS tab_servico_terceiro_s_n_qa
				
				$daosn = new ServicoTerceiroSNDAO();
				$vetsn = $daosn->ListaServicoTerceiroSNPorEmpresa($cod);
				$numsn = count($vetsn);
				
				if($numsn > 0){
					
					$sn = $vetsn[0];
					
					$s_n  = $sn->getSn();
					$ck   = "";
					$ck2  = "";	
					
					if($s_n == '1'){
						
						$tpl->assign('ck','checked');
						$tpl->assign('cla','serv_terceiros');
						$tpl->assign('dis','');
					}else if($s_n == '2'){
						$tpl->assign('ck2','checked');
						$tpl->assign('cla','serv_terceiros serv_terceiros_sn');
						$tpl->assign('dis','disabled');
					}else{
						$tpl->assign('ck2','checked');
						$tpl->assign('cla','serv_terceiros serv_terceiros_sn');
						$tpl->assign('dis','disabled');
					}
					
				}
				
				
				## PEGANDO DADOS tab_certidoes_licenciamento_qa
				$daocert = new CertidoesLicenciamentoDAO();
				$vetcert = $daocert->ListaCertidoesLicenciamentoPorEmpresa($cod);
				$numcert = count($vetcert);
				
				if($numcert > 0){
					
					$cert = $vetcert[0];	
					
					$certfiscal_cert	= $cert->getCertFiscalTesouroEstado();	
					$licenca_cert       = $cert->getLicencaAmbFepam();
					$emitida_cert   	= $cert->getEmitida();
					$numprot_cert   	= $cert->getNumeroProtocoloFepam();
					$data_cert  		= $cert->getData();	
					
					$data_cert_lic      = explode('-',$data_cert);
					
						
					$tpl->assign('certfiscal_cert',$certfiscal_cert);	
					$tpl->assign('licenca_cert',$licenca_cert);
					$tpl->assign('emitida_cert',$emitida_cert);
					$tpl->assign('numprot_cert',$numprot_cert);	
					$tpl->assign('data_cert',$data_cert);
					$tpl->assign('data_cert1',$data_cert_lic[2]);
					$tpl->assign('data_cert2',$data_cert_lic[1]);
					$tpl->assign('data_cert3',$data_cert_lic[0]);
								
				}else{
					
				
				}
				
				## PEGANDO DADOS tab_certidoes_licenciamento_qa
				
				## PEGANDO DADOS tab_administracao_qa
					
				$daoadm = new AdministracaoDAO();
				$vetadm = $daoadm->ListaAdministracaoPorEmpresa($cod);	
				$numadm = count($vetadm);
				
				if($numadm > 0){
					
					$adm = $vetadm[0];	
					
					$nome_gerente    = $adm->getNomeGerenteDiretor();
					$fone_gerente    = $adm->getFoneGerenteDiretor();
					$email_generente = $adm->getEmailGerenteDiretor();
					$nome_contador   = $adm->getNomeContador();
					$fone_contador   = $adm->getFoneContador();
					$email_contador  = $adm->getEmailContador();
					$nome_tecnico    = $adm->getNomeTecnico();
					$fone_tecnico    = $adm->getFoneTecnico();
					$email_tecnico   = $adm->getEmailTecnico();
					$crmv_tecnico	 = $adm->getCrmvTecnico();
					
					$tpl->assign('nome_gerente',$nome_gerente);
					$tpl->assign('fone_gerente',$fone_gerente);
					$tpl->assign('email_generente',$email_generente);
					$tpl->assign('nome_contador',$nome_contador);
					$tpl->assign('fone_contador',$fone_contador);
					$tpl->assign('email_contador',$email_contador);
					$tpl->assign('nome_tecnico',$nome_tecnico);	
					$tpl->assign('fone_tecnico',$fone_tecnico);	
					$tpl->assign('email_tecnico',$email_tecnico);
					$tpl->assign('crmv_tecnico',$crmv_tecnico);					
				}
				
				
				## PEGANDO DADOS tab_administracao_qa
				
				## PEGANDO DADOS tab_inspecao_estabeleciomento_qa
				
				$daoinsp = new InspecaoEstabelecimentoDAO();	
				$vetinsp = $daoinsp->ListaInspecaoEstabelecimentoPorEmpresa($cod); 	
				$numinsp = count($vetinsp);	
				
				if($numinsp > 0){
					
					$insp = $vetinsp[0];	
					
					$tipo_insp   = $insp->getTipo();
					$nreg_insp   = $insp->getNregistro();
					
					if($tipo_insp == '1'){
						$tpl->assign('tipo_insp1','checked');
						
					}else if($tipo_insp == '2'){
						$tpl->assign('tipo_insp2','checked');
						
					}else if($tipo_insp == '3'){
						$tpl->assign('tipo_insp3','checked');
					}
					
					$tpl->assign('nreg_insp',$nreg_insp);
						
				}								
				
				## PEGANDO DADOS tab_inspecao_estabeleciomento_qa
				
				## PEGANDO DADOS tab_veterinario_estabelecimento_qa
				
				$daoest = new VeterinarioEstabelecimentoDAO();
				$vetest = $daoest->ListaVeterinarioEstabelecimentoPorEmpresa($cod);
				$numest = count($vetest);
				
				if($numest > 0){
						
					$est = $vetest[0];		
					
					$nome_est     = $est->getNome();
					$crmv_est     = $est->getCrmv();
					$endr_est	  = $est->getEndereco();
					$email_est    = $est->getEmail();
					$convmuni_est = $est->getConvenioMunicipio();
					$org_muni_est = $est->getOrgMunicipio();
					
					if($convmuni_est == '1'){
						$tpl->assign('convmuni_est1','checked');	
						$tpl->assign('org_muni_est',$org_muni_est);
						$tpl->assign('disa','');
					}else if($convmuni_est == '2'){
						$tpl->assign('convmuni_est2','checked');							
						$tpl->assign('org_muni_est','');
						$tpl->assign('disa','disabled');
					}else{
						$tpl->assign('convmuni_est2','checked');							
						$tpl->assign('org_muni_est','');
						$tpl->assign('disa','disabled');
					}
					
					$tpl->assign('nome_est',$nome_est);
					$tpl->assign('crmv_est',$crmv_est);
					$tpl->assign('endr_est',$endr_est);	
					$tpl->assign('email_est',$email_est);
					
					
					
				}
				
				
				## PEGANDO DADOS tab_veterinario_estabelecimento_qa
				
				
				## PEGANDO DADOS tab_servico_terceiro_s_n_qa
				
				$daoserv = new ServicoTerceirosDAO();
				$vetserv = $daoserv->ListaServicoTerceirosPorEmpresa($cod); 
				$numserv = count($vetserv);
				
				for($i = 0; $i < $numserv; $i++){
					
					$serv = $vetserv[$i];	
					
					$id_serv     = $serv->getCodigo();
					$razao_serv  = $serv->getRazaoSocial();
					$cgc_serv	 = $serv->getCgc();
					$idemp_serv	 = $serv->getIdEmpresa();
					
					$tpl->newBlock('listserv');
		
					$tpl->assign('id_serv',$id_serv);
					$tpl->assign('razao_serv',$razao_serv);
					$tpl->assign('cgc_serv',$cgc_serv);
					
					
				}
				## PEGANDO DADOS tab_servico_terceiro_s_n_qa
				
				
				## PEGANDO DADOS tab_socios_acionistas_qa
				
				$daosoc = new SociosAcionistasDAO();
				$vetsoc = $daosoc->ListaSociosAcionistasPorEmpresa($cod);
				$numsoc	= count($vetsoc);
				
				for($y = 0; $y < $numsoc; $y++){
					
					$soc = $vetsoc[$y];	
					
					$cod_soc		       = $soc->getCodigo();
					$nome_soc 			   = $soc->getNome();
					$cpf_soc  			   = $soc->getCpf();
					$partic_cap_social_soc = $soc->getPartCapSocial();
					
					$tpl->newBlock('listsocia');
		
					$tpl->assign('cod_soc',$cod_soc);
					$tpl->assign('nome_soc',$nome_soc);
					$tpl->assign('cpf_soc',$cpf_soc);
					$tpl->assign('partic_cap_social_soc',$partic_cap_social_soc);
					
					
				}
				## PEGANDO DADOS tab_socios_acionistas_qa
				
				## PEGANDO DADOS tab_relacao_bens_imoveis_qa
				
				$daobens = new RelacaoBensImoveisDAO();
				$vetbens = $daobens->ListaRelacaoBensImoveisPorEmpresa($cod);
				$numbens = count($vetbens);
				
				for($s = 0; $s < $numbens; $s++){
					
					$bens = $vetbens[$s];
					
					$cod_bens			   = $bens->getCodigo();	
					$desc_bens 			   = $bens->getDescricao();
					$endereco_bens		   = $bens->getEndereco();
					$matricula_bens		   = $bens->getMatricula();	
					
					$tpl->newBlock('listbens');
		
					$tpl->assign('cod_bens',$cod_bens);
					$tpl->assign('desc_bens',$desc_bens);
					$tpl->assign('endereco_bens',$endereco_bens);
					$tpl->assign('matricula_bens',$matricula_bens);
						
				}
				
				## PEGANDO DADOS tab_relacao_bens_imoveis_qa
				
			}else{
				
				$tpl->assign('abilita','agreg');	
				$tpl->assign('selecionadoabt','hide');
					
			}
			
			$vetm = $daom->ListaModalidadeSelecionada($idmodalidade);
			$numm = count($vetm);
					
			for($x = 0;$x < $numm; $x++){
				
				$mod  = $vetm[$x];
				
				$cod_mod  =	$mod->getCodigo();
				$nome_mod = $mod->getNome();	
				$sel_mod  = $mod->getSelecionado();
	
				$tpl->newBlock('listmod');
	
				$tpl->assign('cod_mod',$cod_mod);
				$tpl->assign('nome_mod',$nome_mod);
				$tpl->assign('sel_mod',$sel_mod);
			}
			
			
			
			
			
		}		

	/**************************************************************/

	$tpl->printToScreen();



?>
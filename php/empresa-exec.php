<?php

	

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		

		

		$act = $_REQUEST['act'];	

		

		switch($act){

			case 'inserir':
						
				if(empty($_REQUEST['cnpj'])){
					$cnpj = "";
				}else{
					$cnpj = $_REQUEST['cnpj'];
					$cnpj = preg_replace("/\D+/", "", $cnpj);					
				}
				
				if(empty($_REQUEST['nome'])){
					$nome = "";
				}else{
					$nome = $_REQUEST['nome'];
				}
				
				if(empty($_REQUEST['fantasia'])){
					$fantasia = "";
				}else{
					$fantasia = $_REQUEST['fantasia'];
				}
				
				if(empty($_REQUEST['ins'])){
					$ins = "";
				}else{
					$ins = $_REQUEST['ins'];
					$insc = preg_replace("/\D+/", "", $ins);	
				}
				
				if(empty($_REQUEST['email'])){
					$email = "";
				}else{
					$email = $_REQUEST['email'];	
				}
				
				if(empty($_REQUEST['marca'])){
					$marca = "";
				}else{
					$marca = $_REQUEST['marca'];
				}
				
				
				if(empty($_REQUEST['cep'])){
					$cep = "";
				}else{
					$cep = $_REQUEST['cep'];
				}
				
				if(empty($_REQUEST['ende'])){
					$ende = "";
				}else{
					$ende = $_REQUEST['ende'];
				}
				
				if(empty($_REQUEST['bairro'])){
					$bairro = "";
				}else{
					$bairro = $_REQUEST['bairro'];
				}
				
				if(empty($_REQUEST['nro'])){
					$nro = "";
				}else{
					$nro = $_REQUEST['nro'];
				}
				
				if(empty($_REQUEST['cpl'])){
					$cpl = "";
				}else{
					$cpl = $_REQUEST['cpl'];
				}
				
				if(empty($_REQUEST['cidade'])){
					$cidade = "";
				}else{
					$cidade = $_REQUEST['cidade'];
				}
								
				if(empty($_REQUEST['uf'])){
					$uf = "";
				}else{
					$uf = $_REQUEST['uf'];
				}
				
				if(empty($_REQUEST['inspecao'])){
					$inspecao = "0";
				}else{
					$inspecao = $_REQUEST['inspecao'];
				}
				
				if(empty($_REQUEST['fone'])){
					$telefone3 = "";
				}else{
					$fone	   = $_REQUEST['fone'];
					$telefone  = str_replace("(","", $fone);
					$telefone3 = str_replace(")","", $telefone);
					$telefone3 = explode('-',$telefone3);
					$telefone3 = ''.$telefone3[0].''.$telefone3[1].'';	
				}
				
				
				if(empty($_REQUEST['fone2'])){
					$telefone2 = "";
				}else{
					$fone2 = $_REQUEST['fone2'];
					
					$telefone  = str_replace("(","", $fone2);
					$telefone2 = str_replace(")","", $telefone);
					$telefone2 = explode('-',$telefone2);
					$telefone2 = ''.$telefone2[0].''.$telefone2[1].'';	
				
				}
				
				if(empty($_REQUEST['responsavel'])){
					$responsavel = "";
				}else{
					$responsavel = $_REQUEST['responsavel'];
				}
				
				if(empty($_REQUEST['modalidade'])){
					$modalidade = "0";
				}else{
					$modalidade = $_REQUEST['modalidade'];
				}
				
				if(empty($_REQUEST['bovinos'])){
					$bovinos = "";
				}else{
					$bovinos = $_REQUEST['bovinos'];
				}
				
				if(empty($_REQUEST['ovinos'])){
					$ovinos = "";
				}else{
					$ovinos = $_REQUEST['ovinos'];
				}
				
				if(empty($_REQUEST['form_const_juridica'])){
					$form_const_juridica = "";
				}else{
					$form_const_juridica = $_REQUEST['form_const_juridica'];
				}
				
				if(empty($_REQUEST['dt_num_arq_ult_ato_junt_comerc'])){
					$dt_num_arq_ult_ato_junt_comerc = "";
				}else{
					$dt_num_arq_ult_ato_junt_comerc = $_REQUEST['dt_num_arq_ult_ato_junt_comerc'];
				}
				
				if(empty($_REQUEST['cap_social_reg'])){
					$cap_social_reg = "";
				}else{
					$cap_social_reg = $_REQUEST['cap_social_reg'];
				}
				
				if(isset($_REQUEST['ativo'])){
					$ativo = "1";					
				}else{
					$ativo = "2";
				}
				
				if(empty($_REQUEST['bubalinos'])){
					$bubalinos = "";
				}else{
					$bubalinos = $_REQUEST['bubalinos'];
				}
				
				$emp =  new Empresas();
				
				$emp->setCnpj($cnpj);
				$emp->setRazaoSocial($nome);
				$emp->setFantasia($fantasia);
				$emp->setMarca($marca);
				$emp->setInscricaoEstadual($insc);
				$emp->setEndereco($ende);
				$emp->setNumero($nro);
				$emp->setComplemento($cpl);
				$emp->setCep($cep);
				$emp->setCidade($cidade);
				$emp->setEstado($uf);
				$emp->setBairro($bairro);
				$emp->setInspecao($inspecao);
				$emp->setFone1($telefone3);
				$emp->setFone2($telefone2);
				$emp->setEmail($email);
				$emp->setResponsavel($responsavel);
				$emp->setIdModalidade($modalidade);
				$emp->setCapacidadeBovino($bovinos);
				$emp->setCapacidadeOvino($ovinos);
				$emp->setDtAtoJuntaComercial($dt_num_arq_ult_ato_junt_comerc);
				$emp->setFormConstituicaoJuridica($form_const_juridica);
				$emp->setCapSocialReg($cap_social_reg);
				$emp->setAtivo($ativo);
				$emp->setCapacidadeBubalino($bubalinos);
				
				$dao = new EmpresasDAO();
				$dao->inserir($emp);
				
				if(isset($_REQUEST['agregar']) and !empty($_REQUEST['agregar'])){
						
						
					/*echo "<pre>";
					print_r($_REQUEST);*/	
					$idemp = $_REQUEST['idemp']; 
					
					## Endereco Escritorio 
					if(empty($_REQUEST['escritorio_endereco'])){
						$escritorio_endereco = "";
					}else{
						$escritorio_endereco = $_REQUEST['escritorio_endereco'];
					}
					
					if(empty($_REQUEST['escritorio_municipio'])){
						$escritorio_municipio = "";
					}else{
						$escritorio_municipio = $_REQUEST['escritorio_municipio'];
					}				
					
					if(empty($_REQUEST['escritorio_cep'])){
						$escritorio_cep = "";
					}else{
						$escritorio_cep = $_REQUEST['escritorio_cep'];
					}
					
					if(empty($_REQUEST['escritorio_fone'])){
						$escritorio_fone = "";
					}else{
						$escritorio_fone = $_REQUEST['escritorio_fone'];
					}
					
					if(empty($_REQUEST['escritorio_email'])){
						$escritorio_email = "";
					}else{
						$escritorio_email = $_REQUEST['escritorio_email'];
					}
					
					$esc = new EnderecoEscritorio();
					
					$esc->setEndereco($escritorio_endereco);
					$esc->setMunicipio($escritorio_municipio);
					$esc->setCep($escritorio_cep);
					$esc->setFone($escritorio_fone);
					$esc->setEmail($escritorio_email);
					$esc->setIdEmpresa($idemp);
					
					$daoesc = new EnderecoEscritorioDAO();
					$daoesc->inserir($esc);
					## Endereco Escritorio
					
					## Pestação de serviço 1 sim ou 2 não ou 3 não informou
					if(empty($_REQUEST['terceiro_s_n'])){
						$terceiro_s_n = "3";
					}else{
						$terceiro_s_n = $_REQUEST['terceiro_s_n'];
					}
					
					$psn = new ServicoTerceiroSN();
					$psn->setSn($terceiro_s_n);
					$psn->setIdEmpresa($idemp);
					$daopsn = new ServicoTerceiroSNDAO();
					$daopsn->inserir($psn);
					## Pestação de serviço 1 sim ou 2 não ou 3 não informou
					
					## Certidão e licenciamento
					
					if(empty($_REQUEST['certidao_licenciamento_anexo1'])){
						$certidao_licenciamento_anexo1 = "";
					}else{
						$certidao_licenciamento_anexo1 = $_REQUEST['certidao_licenciamento_anexo1'];
					}
					
					if(empty($_REQUEST['certidao_licenciamento_anexo2'])){
						$certidao_licenciamento_anexo2 = "";
					}else{
						$certidao_licenciamento_anexo2 = $_REQUEST['certidao_licenciamento_anexo2'];
					}
					
					if(empty($_REQUEST['certidao_licenciamento_emit'])){
						$certidao_licenciamento_emit = "";
					}else{
						$certidao_licenciamento_emit = $_REQUEST['certidao_licenciamento_emit'];
					}
					
					if(empty($_REQUEST['certidao_licenciamento_nfepam'])){
						$certidao_licenciamento_nfepam = "";
					}else{
						$certidao_licenciamento_nfepam = $_REQUEST['certidao_licenciamento_nfepam'];
					}
					
					if(empty($_REQUEST['certidao_licenciamento_dt1'])){
						$certdtli = "";
					}else{
						$certidao_licenciamento_dt1 = $_REQUEST['certidao_licenciamento_dt1'];
						
						$certdtli = $_REQUEST['certidao_licenciamento_dt1'].'-'.$_REQUEST['certidao_licenciamento_dt2'].'-'.$certidao_licenciamento_dt1;
						
					}
					
					
					$cert = new CertidoesLicenciamento();
												
					$cert->setCertFiscalTesouroEstado($certidao_licenciamento_anexo1);	
					$cert->setLicencaAmbFepam($certidao_licenciamento_anexo2);
					$cert->setEmitida($certidao_licenciamento_emit);
					$cert->setNumeroProtocoloFepam($certidao_licenciamento_nfepam);
					$cert->setData($certdtli);			
					$cert->setIdEmpresa($idemp);
					
					$daocert = new CertidoesLicenciamentoDAO();
					$daocert->inserir($cert);
					## Certidão e licenciamento
					
					
					## Administração
					
					if(empty($_REQUEST['nome_gerente_diretor'])){
						$nome_gerente_diretor = "";
					}else{
						$nome_gerente_diretor = $_REQUEST['nome_gerente_diretor'];
					}
					
					if(empty($_REQUEST['fone_gerente_diretor'])){
						$fone_gerente_diretor = "";
					}else{
						$fone_gerente_diretor = $_REQUEST['fone_gerente_diretor'];
					}
					
					if(empty($_REQUEST['email_gerente_diretor'])){
						$email_gerente_diretor = "";
					}else{
						$email_gerente_diretor = $_REQUEST['email_gerente_diretor'];
					}
					
					if(empty($_REQUEST['nome_contador'])){
						$nome_contador = "";
					}else{
						$nome_contador = $_REQUEST['nome_contador'];
					}
					
					if(empty($_REQUEST['fone_contador'])){
						$fone_contador = "";
					}else{
						$fone_contador = $_REQUEST['fone_contador'];
					}
					
					if(empty($_REQUEST['email_contador'])){
						$email_contador = "";
					}else{
						$email_contador = $_REQUEST['email_contador'];
					}
					
					if(empty($_REQUEST['nome_tecnico'])){
						$nome_tecnico = "";
					}else{
						$nome_tecnico = $_REQUEST['nome_tecnico'];
					}
					
					if(empty($_REQUEST['fone_tecnico'])){
						$fone_tecnico = "";
					}else{
						$fone_tecnico = $_REQUEST['fone_tecnico'];
					}	
					
					if(empty($_REQUEST['email_tecnico'])){
						$email_tecnico = "";
					}else{
						$email_tecnico = $_REQUEST['email_tecnico'];
					}
					
					if(empty($_REQUEST['n_crmv_tecnico'])){
						$n_crmv_tecnico = "";
					}else{
						$n_crmv_tecnico = $_REQUEST['n_crmv_tecnico'];
					}
					
					$adm = new Administracao();
					
					$adm->setNomeGerenteDiretor($nome_gerente_diretor);
					$adm->setFoneGerenteDiretor($fone_gerente_diretor);
					$adm->setEmailGerenteDiretor($email_gerente_diretor);
					$adm->setNomeContador($nome_contador);
					$adm->setFoneContador($fone_contador);
					$adm->setEmailContador($email_contador);
					$adm->setNomeTecnico($nome_tecnico);
					$adm->setFoneTecnico($fone_tecnico);
					$adm->setEmailTecnico($email_tecnico);
					$adm->setCrmvTecnico($n_crmv_tecnico);					
					$adm->setIdEmpresa($idemp);
					
					$daoadm =  new AdministracaoDAO();		
					$daoadm->inserir($adm);
					## Administração
					
					
					## Inspeção estabelecimento
					
					
					if(empty($_REQUEST['inspecao_estabeleciomento'])){
						$inspecao_estabeleciomento = "0";
					}else{
						$inspecao_estabeleciomento = $_REQUEST['inspecao_estabeleciomento'];
					}
					
					if(empty($_REQUEST['nregistro_inspecao_estabeleciomento'])){
						$nregistro_inspecao_estabeleciomento = "";
					}else{
						$nregistro_inspecao_estabeleciomento = $_REQUEST['nregistro_inspecao_estabeleciomento'];
					}
					
					$insp = new InspecaoEstabelecimento();
					
					$insp->setTipo($inspecao_estabeleciomento);
					$insp->setNregistro($nregistro_inspecao_estabeleciomento);
					$insp->setIdEmpresa($idemp);
					
					$daoins = new InspecaoEstabelecimentoDAO();
					$daoins->inserir($insp);
					
					## Inspeção estabelecimento
					
					## Veterinario estabelecimento
					
					if(empty($_REQUEST['nome_veterinario_estabelecimento'])){
						$nome_veterinario_estabelecimento = "";
					}else{
						$nome_veterinario_estabelecimento = $_REQUEST['nome_veterinario_estabelecimento'];
					}
					
					if(empty($_REQUEST['crmv_veterinario_estabelecimento'])){
						$crmv_veterinario_estabelecimento = "";
					}else{
						$crmv_veterinario_estabelecimento = $_REQUEST['crmv_veterinario_estabelecimento'];
					}
					
					
					if(empty($_REQUEST['endereco_veterinario_estabelecimento'])){
						$endereco_veterinario_estabelecimento = "";
					}else{
						$endereco_veterinario_estabelecimento = $_REQUEST['endereco_veterinario_estabelecimento'];
					}
					
					if(empty($_REQUEST['email_veterinario_estabelecimento'])){
						$email_veterinario_estabelecimento = "";
					}else{
						$email_veterinario_estabelecimento = $_REQUEST['email_veterinario_estabelecimento'];
					}
					
					if(empty($_REQUEST['veterinario_estabelecimento_s_n'])){
						$veterinario_estabelecimento_s_n = "3";
					}else{
						$veterinario_estabelecimento_s_n = $_REQUEST['veterinario_estabelecimento_s_n'];
					}
					
					if(empty($_REQUEST['org_municipio_veterinario_estabelecimento'])){
						$org_municipio_veterinario_estabelecimento = "";
					}else{
						$org_municipio_veterinario_estabelecimento = $_REQUEST['org_municipio_veterinario_estabelecimento'];
					}
					
					
					
					$est =  new VeterinarioEstabelecimento();
					
					$est->setNome($nome_veterinario_estabelecimento);
					$est->setCrmv($crmv_veterinario_estabelecimento);
					$est->setEndereco($endereco_veterinario_estabelecimento);
					$est->setEmail($email_veterinario_estabelecimento);
					$est->setConvenioMunicipio($veterinario_estabelecimento_s_n);
					$est->setOrgMunicipio($org_municipio_veterinario_estabelecimento);
					$est->setIdEmpresa($idemp);
					
					$daoest = new VeterinarioEstabelecimentoDAO();
					$daoest->inserir($est);
					
				}
				
				
				$res    = array();

				array_push($res,array(
					'titulo'=>"Mensagem da Alteção da empresa",
					'mensagem'=>"Inserido com sucesso!",
					'url'=>'Listar-Empresas.php',
					'tipo'=>'1'
				));

				$re      = json_encode($res);
				$reponse = urlencode($re);

				$destino = "response.php?mg={$reponse}";
				header("Location:{$destino}");
				
			break;
			case 'alterar':
					
				$id  = $_REQUEST['id'];							
				
				if(empty($_REQUEST['cnpj'])){
					$cnpj = "";
				}else{
					$cnpj = $_REQUEST['cnpj'];
					$cnpj = preg_replace("/\D+/", "", $cnpj);					
				}
				
				if(empty($_REQUEST['nome'])){
					$nome = "";
				}else{
					$nome = $_REQUEST['nome'];
				}
				
				if(empty($_REQUEST['fantasia'])){
					$fantasia = "";
				}else{
					$fantasia = $_REQUEST['fantasia'];
				}
				
				if(empty($_REQUEST['ins'])){
					$ins = "";
				}else{
					$ins = $_REQUEST['ins'];
					$insc = preg_replace("/\D+/", "", $ins);	
				}
				
				if(empty($_REQUEST['email'])){
					$email = "";
				}else{
					$email = $_REQUEST['email'];	
				}
				
				if(empty($_REQUEST['marca'])){
					$marca = "";
				}else{
					$marca = $_REQUEST['marca'];
				}
				
				
				if(empty($_REQUEST['cep'])){
					$cep = "";
				}else{
					$cep = $_REQUEST['cep'];
				}
				
				if(empty($_REQUEST['ende'])){
					$ende = "";
				}else{
					$ende = $_REQUEST['ende'];
				}
				
				if(empty($_REQUEST['bairro'])){
					$bairro = "";
				}else{
					$bairro = $_REQUEST['bairro'];
				}
				
				if(empty($_REQUEST['nro'])){
					$nro = "";
				}else{
					$nro = $_REQUEST['nro'];
				}
				
				if(empty($_REQUEST['cpl'])){
					$cpl = "";
				}else{
					$cpl = $_REQUEST['cpl'];
				}
				
				if(empty($_REQUEST['cidade'])){
					$cidade = "";
				}else{
					$cidade = $_REQUEST['cidade'];
				}
								
				if(empty($_REQUEST['uf'])){
					$uf = "";
				}else{
					$uf = $_REQUEST['uf'];
				}
				
				if(empty($_REQUEST['inspecao'])){
					$inspecao = "0";
				}else{
					$inspecao = $_REQUEST['inspecao'];
				}
				
				if(empty($_REQUEST['fone'])){
					$telefone3 = "";
				}else{
					$fone	   = $_REQUEST['fone'];
					$telefone  = str_replace("(","", $fone);
					$telefone3 = str_replace(")","", $telefone);
					$telefone3 = explode('-',$telefone3);
					$telefone3 = ''.$telefone3[0].''.$telefone3[1].'';	
				}
				
				
				if(empty($_REQUEST['fone2'])){
					$telefone2 = "";
				}else{
					$fone2 = $_REQUEST['fone2'];
					
					$telefone  = str_replace("(","", $fone2);
					$telefone2 = str_replace(")","", $telefone);
					$telefone2 = explode('-',$telefone2);
					$telefone2 = ''.$telefone2[0].''.$telefone2[1].'';	
				
				}
				
				if(empty($_REQUEST['responsavel'])){
					$responsavel = "";
				}else{
					$responsavel = $_REQUEST['responsavel'];
				}
				
				if(empty($_REQUEST['modalidade'])){
					$modalidade = "0";
				}else{
					$modalidade = $_REQUEST['modalidade'];
				}
				
				if(empty($_REQUEST['bovinos'])){
					$bovinos = "";
				}else{
					$bovinos = $_REQUEST['bovinos'];
				}
				
				if(empty($_REQUEST['ovinos'])){
					$ovinos = "";
				}else{
					$ovinos = $_REQUEST['ovinos'];
				}
				
				if(empty($_REQUEST['form_const_juridica'])){
					$form_const_juridica = "";
				}else{
					$form_const_juridica = $_REQUEST['form_const_juridica'];
				}
				
				if(empty($_REQUEST['dt_num_arq_ult_ato_junt_comerc'])){
					$dt_num_arq_ult_ato_junt_comerc = "";
				}else{
					$dt_num_arq_ult_ato_junt_comerc = $_REQUEST['dt_num_arq_ult_ato_junt_comerc'];
				}
				
				if(empty($_REQUEST['cap_social_reg'])){
					$cap_social_reg = "";
				}else{
					$cap_social_reg = $_REQUEST['cap_social_reg'];
				}
				
				if(isset($_REQUEST['ativo'])){
					$ativo = "1";					
				}else{
					$ativo = "2";
				}
				
				$emp =  new Empresas();
				
				$emp->setCodigo($id);
				$emp->setCnpj($cnpj);
				$emp->setRazaoSocial($nome);
				$emp->setFantasia($fantasia);
				$emp->setMarca($marca);
				$emp->setInscricaoEstadual($insc);
				$emp->setEndereco($ende);
				$emp->setNumero($nro);
				$emp->setComplemento($cpl);
				$emp->setCep($cep);
				$emp->setCidade($cidade);
				$emp->setEstado($uf);
				$emp->setBairro($bairro);
				$emp->setInspecao($inspecao);
				$emp->setFone1($telefone3);
				$emp->setFone2($telefone2);
				$emp->setEmail($email);
				$emp->setResponsavel($responsavel);
				$emp->setIdModalidade($modalidade);
				$emp->setCapacidadeBovino($bovinos);
				$emp->setCapacidadeOvino($ovinos);
				$emp->setDtAtoJuntaComercial($dt_num_arq_ult_ato_junt_comerc);
				$emp->setFormConstituicaoJuridica($form_const_juridica);
				$emp->setCapSocialReg($cap_social_reg);
				$emp->setAtivo($ativo);
				
				$dao = new EmpresasDAO();
				$dao->update($emp);
				
				
				if(isset($_REQUEST['agregar']) and !empty($_REQUEST['agregar'])){
						
						
					/*echo "<pre>";
					print_r($_REQUEST);*/	
					$idemp = $_REQUEST['id']; 
					
					## Endereco Escritorio 
					if(empty($_REQUEST['escritorio_endereco'])){
						$escritorio_endereco = "";
					}else{
						$escritorio_endereco = $_REQUEST['escritorio_endereco'];
					}
					
					if(empty($_REQUEST['escritorio_municipio'])){
						$escritorio_municipio = "";
					}else{
						$escritorio_municipio = $_REQUEST['escritorio_municipio'];
					}				
					
					if(empty($_REQUEST['escritorio_cep'])){
						$escritorio_cep = "";
					}else{
						$escritorio_cep = $_REQUEST['escritorio_cep'];
					}
					
					if(empty($_REQUEST['escritorio_fone'])){
						$escritorio_fone = "";
					}else{
						$escritorio_fone = $_REQUEST['escritorio_fone'];
					}
					
					if(empty($_REQUEST['escritorio_email'])){
						$escritorio_email = "";
					}else{
						$escritorio_email = $_REQUEST['escritorio_email'];
					}
					
					
					
					$daoesc = new EnderecoEscritorioDAO();					
					$vetesc = $daoesc->ListaEnderecoEscritorioPorEmpresa($idemp);
					$numesc = count($vetesc);
					
					for($i = 0; $i < $numesc; $i++){
						
						$escr = $vetesc[$i];
														
						$es = new EnderecoEscritorio();
						
						$es->setCodigo($escr->getCodigo());	
						$es->setIdEmpresa($idemp);
							
						$daoesc->deletar($es);	
							
					}
					
					$esc = new EnderecoEscritorio();
					
					$esc->setEndereco($escritorio_endereco);
					$esc->setMunicipio($escritorio_municipio);
					$esc->setCep($escritorio_cep);
					$esc->setFone($escritorio_fone);
					$esc->setEmail($escritorio_email);
					$esc->setIdEmpresa($idemp);										
					
					$daoesc->inserir($esc);
					## Endereco Escritorio
					
					## Pestação de serviço 1 sim ou 2 não ou 3 não informou
					if(empty($_REQUEST['terceiro_s_n'])){
						$terceiro_s_n = "3";
					}else{
						$terceiro_s_n = $_REQUEST['terceiro_s_n'];
					}
					
					$daopsn = new ServicoTerceiroSNDAO();
					
					$vetsn = $daopsn->ListaServicoTerceiroSNPorEmpresa($idemp);
					$numsn = count($vetsn);
					
					for($i = 0; $i < $numsn; $i++){
						
						$sn = $vetsn[$i];	
						
						$psnt = new ServicoTerceiroSN();
						
						$psnt->setCodigo($sn->getCodigo());
						$psnt->setIdEmpresa($idemp);		
						
						$daopsn->deletar($psnt);
							
					}
					
					$psn = new ServicoTerceiroSN();
					$psn->setSn($terceiro_s_n);
					$psn->setIdEmpresa($idemp);
					
					$daopsn->inserir($psn);
					## Pestação de serviço 1 sim ou 2 não ou 3 não informou
					
					## Certidão e licenciamento
					
					if(empty($_REQUEST['certidao_licenciamento_anexo1'])){
						$certidao_licenciamento_anexo1 = "";
					}else{
						$certidao_licenciamento_anexo1 = $_REQUEST['certidao_licenciamento_anexo1'];
					}
					
					if(empty($_REQUEST['certidao_licenciamento_anexo2'])){
						$certidao_licenciamento_anexo2 = "";
					}else{
						$certidao_licenciamento_anexo2 = $_REQUEST['certidao_licenciamento_anexo2'];
					}
					
					if(empty($_REQUEST['certidao_licenciamento_emit'])){
						$certidao_licenciamento_emit = "";
					}else{
						$certidao_licenciamento_emit = $_REQUEST['certidao_licenciamento_emit'];
					}
					
					if(empty($_REQUEST['certidao_licenciamento_nfepam'])){
						$certidao_licenciamento_nfepam = "";
					}else{
						$certidao_licenciamento_nfepam = $_REQUEST['certidao_licenciamento_nfepam'];
					}
					
					if(empty($_REQUEST['certidao_licenciamento_dt1'])){
						$certdtli = "";
					}else{
						$certidao_licenciamento_dt1 = $_REQUEST['certidao_licenciamento_dt1'];
						
						$certdtli = $_REQUEST['certidao_licenciamento_dt1'].'-'.$_REQUEST['certidao_licenciamento_dt2'].'-'.$certidao_licenciamento_dt1;
						
					}
					
					$daocert = new CertidoesLicenciamentoDAO();
					
					$vetcert = $daocert->ListaCertidoesLicenciamentoPorEmpresa($idemp);
					$numcert = count($vetcert);
					
					for($i = 0; $i < $numcert; $i++){
						
						$certs = $vetcert[$i];		
						
						$certl = new CertidoesLicenciamento();	
						$certl->setCodigo($certs->getCodigo());
						$certl->setIdEmpresa($idemp);	
						
						$daocert->deletar($certl);
						
					}
					
					
					$cert = new CertidoesLicenciamento();
												
					$cert->setCertFiscalTesouroEstado($certidao_licenciamento_anexo1);	
					$cert->setLicencaAmbFepam($certidao_licenciamento_anexo2);
					$cert->setEmitida($certidao_licenciamento_emit);
					$cert->setNumeroProtocoloFepam($certidao_licenciamento_nfepam);
					$cert->setData($certdtli);			
					$cert->setIdEmpresa($idemp);
					
					
					$daocert->inserir($cert);
					## Certidão e licenciamento
					
					
					## Administração
					
					if(empty($_REQUEST['nome_gerente_diretor'])){
						$nome_gerente_diretor = "";
					}else{
						$nome_gerente_diretor = $_REQUEST['nome_gerente_diretor'];
					}
					
					if(empty($_REQUEST['fone_gerente_diretor'])){
						$fone_gerente_diretor = "";
					}else{
						$fone_gerente_diretor = $_REQUEST['fone_gerente_diretor'];
					}
					
					if(empty($_REQUEST['email_gerente_diretor'])){
						$email_gerente_diretor = "";
					}else{
						$email_gerente_diretor = $_REQUEST['email_gerente_diretor'];
					}
					
					if(empty($_REQUEST['nome_contador'])){
						$nome_contador = "";
					}else{
						$nome_contador = $_REQUEST['nome_contador'];
					}
					
					if(empty($_REQUEST['fone_contador'])){
						$fone_contador = "";
					}else{
						$fone_contador = $_REQUEST['fone_contador'];
					}
					
					if(empty($_REQUEST['email_contador'])){
						$email_contador = "";
					}else{
						$email_contador = $_REQUEST['email_contador'];
					}
					
					if(empty($_REQUEST['nome_tecnico'])){
						$nome_tecnico = "";
					}else{
						$nome_tecnico = $_REQUEST['nome_tecnico'];
					}
					
					if(empty($_REQUEST['fone_tecnico'])){
						$fone_tecnico = "";
					}else{
						$fone_tecnico = $_REQUEST['fone_tecnico'];
					}	
					
					if(empty($_REQUEST['email_tecnico'])){
						$email_tecnico = "";
					}else{
						$email_tecnico = $_REQUEST['email_tecnico'];
					}
					
					if(empty($_REQUEST['n_crmv_tecnico'])){
						$n_crmv_tecnico = "";
					}else{
						$n_crmv_tecnico = $_REQUEST['n_crmv_tecnico'];
					}
					
					$daoadm =  new AdministracaoDAO();		
					$vetadm = $daoadm->ListaAdministracaoPorEmpresa($idemp);	
					$numadm = count($vetadm);
					
					for($i = 0; $i < $numadm; $i++){
						
						$adms = $vetadm[$i];		
						
						$admst = new Administracao();	
						
						$admst->setCodigo($adms->getCodigo());
						$admst->setIdEmpresa($idemp);
						
						$daoadm->deletar($admst);
						
					}
					
					
					$adm = new Administracao();
					
					$adm->setNomeGerenteDiretor($nome_gerente_diretor);
					$adm->setFoneGerenteDiretor($fone_gerente_diretor);
					$adm->setEmailGerenteDiretor($email_gerente_diretor);
					$adm->setNomeContador($nome_contador);
					$adm->setFoneContador($fone_contador);
					$adm->setEmailContador($email_contador);
					$adm->setNomeTecnico($nome_tecnico);
					$adm->setFoneTecnico($fone_tecnico);
					$adm->setEmailTecnico($email_tecnico);
					$adm->setCrmvTecnico($n_crmv_tecnico);					
					$adm->setIdEmpresa($idemp);					
					
					$daoadm->inserir($adm);
					## Administração
					
					
					## Inspeção estabelecimento
					
					
					if(empty($_REQUEST['inspecao_estabeleciomento'])){
						$inspecao_estabeleciomento = "0";
					}else{
						$inspecao_estabeleciomento = $_REQUEST['inspecao_estabeleciomento'];
					}
					
					if(empty($_REQUEST['nregistro_inspecao_estabeleciomento'])){
						$nregistro_inspecao_estabeleciomento = "";
					}else{
						$nregistro_inspecao_estabeleciomento = $_REQUEST['nregistro_inspecao_estabeleciomento'];
					}
					
					$daoins  = new InspecaoEstabelecimentoDAO();
					$vetinsp = $daoins->ListaInspecaoEstabelecimentoPorEmpresa($idemp); 	
					$numinsp = count($vetinsp);
					
					for($i = 0; $i < $numinsp; $i++){
						
						$inspe = $vetinsp[$i];	
																	
						$inspet = new InspecaoEstabelecimento();	
						
						$inspet->setCodigo($inspe->getCodigo());
						$inspet->setIdEmpresa($idemp);
						$daoins->deletar($inspet);
						
					}
										
					$insp = new InspecaoEstabelecimento();
					
					$insp->setTipo($inspecao_estabeleciomento);
					$insp->setNregistro($nregistro_inspecao_estabeleciomento);
					$insp->setIdEmpresa($idemp);
										
					$daoins->inserir($insp);
					
					## Inspeção estabelecimento
					
					## Veterinario estabelecimento
					
					if(empty($_REQUEST['nome_veterinario_estabelecimento'])){
						$nome_veterinario_estabelecimento = "";
					}else{
						$nome_veterinario_estabelecimento = $_REQUEST['nome_veterinario_estabelecimento'];
					}
					
					if(empty($_REQUEST['crmv_veterinario_estabelecimento'])){
						$crmv_veterinario_estabelecimento = "";
					}else{
						$crmv_veterinario_estabelecimento = $_REQUEST['crmv_veterinario_estabelecimento'];
					}
					
					
					if(empty($_REQUEST['endereco_veterinario_estabelecimento'])){
						$endereco_veterinario_estabelecimento = "";
					}else{
						$endereco_veterinario_estabelecimento = $_REQUEST['endereco_veterinario_estabelecimento'];
					}
					
					if(empty($_REQUEST['email_veterinario_estabelecimento'])){
						$email_veterinario_estabelecimento = "";
					}else{
						$email_veterinario_estabelecimento = $_REQUEST['email_veterinario_estabelecimento'];
					}
					
					if(empty($_REQUEST['veterinario_estabelecimento_s_n'])){
						$veterinario_estabelecimento_s_n = "3";
					}else{
						$veterinario_estabelecimento_s_n = $_REQUEST['veterinario_estabelecimento_s_n'];
					}
					
					if(empty($_REQUEST['org_municipio_veterinario_estabelecimento'])){
						$org_municipio_veterinario_estabelecimento = "";
					}else{
						$org_municipio_veterinario_estabelecimento = $_REQUEST['org_municipio_veterinario_estabelecimento'];
					}
					
					
					$daoest = new VeterinarioEstabelecimentoDAO();
					$vetest = $daoest->ListaVeterinarioEstabelecimentoPorEmpresa($idemp);
					$numest = count($vetest);
					
					for($i = 0; $i < $numest; $i++){
						
						$ests = $vetest[$i];	
						
						$esta =  new VeterinarioEstabelecimento();	
						$esta->setCodigo($ests->getCodigo());
						$esta->setIdEmpresa($idemp);
						
						$daoest->deletar($esta);
						
					}
					
					$est =  new VeterinarioEstabelecimento();
					
					$est->setNome($nome_veterinario_estabelecimento);
					$est->setCrmv($crmv_veterinario_estabelecimento);
					$est->setEndereco($endereco_veterinario_estabelecimento);
					$est->setEmail($email_veterinario_estabelecimento);
					$est->setConvenioMunicipio($veterinario_estabelecimento_s_n);
					$est->setOrgMunicipio($org_municipio_veterinario_estabelecimento);
					$est->setIdEmpresa($idemp);
					
					
					$daoest->inserir($est);
					
				}
				
				$res    = array();

				array_push($res,array(
					'titulo'=>"Mensagem da Alteção da empresa",
					'mensagem'=>"Alterado com sucesso!",
					'url'=>'Listar-Empresas.php',
					'tipo'=>'1'
				));

				$re      = json_encode($res);
				$reponse = urlencode($re);

				$destino = "response.php?mg={$reponse}";
				header("Location:{$destino}");
				
			break;
			case 'alteraruserempresa':
					
				$id  = $_SESSION['id_emp'];							
				
				$cnpj = preg_replace("/\D+/", "", $_SESSION['cnpj']);
				$insc = preg_replace("/\D+/", "",$_SESSION['inscemp']);	
				
				if(empty($_REQUEST['nome'])){
					$nome = "";
				}else{
					$nome = $_REQUEST['nome'];
				}
				
				if(empty($_REQUEST['fantasia'])){
					$fantasia = "";
				}else{
					$fantasia = $_REQUEST['fantasia'];
				}
				
								
				if(empty($_REQUEST['email'])){
					$email = "";
				}else{
					$email = $_REQUEST['email'];	
				}
				
				if(empty($_REQUEST['marca'])){
					$marca = "";
				}else{
					$marca = $_REQUEST['marca'];
				}
				
				
				if(empty($_REQUEST['cep'])){
					$cep = "";
				}else{
					$cep = $_REQUEST['cep'];
				}
				
				if(empty($_REQUEST['ende'])){
					$ende = "";
				}else{
					$ende = $_REQUEST['ende'];
				}
				
				if(empty($_REQUEST['bairro'])){
					$bairro = "";
				}else{
					$bairro = $_REQUEST['bairro'];
				}
				
				if(empty($_REQUEST['nro'])){
					$nro = "";
				}else{
					$nro = $_REQUEST['nro'];
				}
				
				if(empty($_REQUEST['cpl'])){
					$cpl = "";
				}else{
					$cpl = $_REQUEST['cpl'];
				}
				
				if(empty($_REQUEST['cidade'])){
					$cidade = "";
				}else{
					$cidade = $_REQUEST['cidade'];
				}
								
				if(empty($_REQUEST['uf'])){
					$uf = "";
				}else{
					$uf = $_REQUEST['uf'];
				}
				
				if(empty($_REQUEST['inspecao'])){
					$inspecao = "0";
				}else{
					$inspecao = $_REQUEST['inspecao'];
				}
				
				if(empty($_REQUEST['fone'])){
					$telefone3 = "";
				}else{
					$fone	   = $_REQUEST['fone'];
					$telefone  = str_replace("(","", $fone);
					$telefone3 = str_replace(")","", $telefone);
					$telefone3 = explode('-',$telefone3);
					$telefone3 = ''.$telefone3[0].''.$telefone3[1].'';	
				}
				
				
				if(empty($_REQUEST['fone2'])){
					$telefone2 = "";
				}else{
					$fone2 = $_REQUEST['fone2'];
					
					$telefone  = str_replace("(","", $fone2);
					$telefone2 = str_replace(")","", $telefone);
					$telefone2 = explode('-',$telefone2);
					$telefone2 = ''.$telefone2[0].''.$telefone2[1].'';	
				
				}
				
				if(empty($_REQUEST['responsavel'])){
					$responsavel = "";
				}else{
					$responsavel = $_REQUEST['responsavel'];
				}
				
				if(empty($_REQUEST['modalidade'])){
					$modalidade = "0";
				}else{
					$modalidade = $_REQUEST['modalidade'];
				}
				
				if(empty($_REQUEST['bovinos'])){
					$bovinos = "";
				}else{
					$bovinos = $_REQUEST['bovinos'];
				}
				
				if(empty($_REQUEST['ovinos'])){
					$ovinos = "";
				}else{
					$ovinos = $_REQUEST['ovinos'];
				}
				
				if(empty($_REQUEST['form_const_juridica'])){
					$form_const_juridica = "";
				}else{
					$form_const_juridica = $_REQUEST['form_const_juridica'];
				}
				
				if(empty($_REQUEST['dt_num_arq_ult_ato_junt_comerc'])){
					$dt_num_arq_ult_ato_junt_comerc = "";
				}else{
					$dt_num_arq_ult_ato_junt_comerc = $_REQUEST['dt_num_arq_ult_ato_junt_comerc'];
				}
				
				if(empty($_REQUEST['cap_social_reg'])){
					$cap_social_reg = "";
				}else{
					$cap_social_reg = $_REQUEST['cap_social_reg'];
				}
				
				if(isset($_REQUEST['ativo'])){
					$ativo = "1";					
				}else{
					$ativo = "2";
				}
				
				
				$emp =  new Empresas();
				
				$emp->setCodigo($id);
				$emp->setCnpj($cnpj);
				$emp->setRazaoSocial($nome);
				$emp->setFantasia($fantasia);
				$emp->setMarca($marca);
				$emp->setInscricaoEstadual($insc);
				$emp->setEndereco($ende);
				$emp->setNumero($nro);
				$emp->setComplemento($cpl);
				$emp->setCep($cep);
				$emp->setCidade($cidade);
				$emp->setEstado($uf);
				$emp->setBairro($bairro);
				$emp->setInspecao($inspecao);
				$emp->setFone1($telefone3);
				$emp->setFone2($telefone2);
				$emp->setEmail($email);
				$emp->setResponsavel($responsavel);
				$emp->setIdModalidade($modalidade);
				$emp->setCapacidadeBovino($bovinos);
				$emp->setCapacidadeOvino($ovinos);
				$emp->setDtAtoJuntaComercial($dt_num_arq_ult_ato_junt_comerc);
				$emp->setFormConstituicaoJuridica($form_const_juridica);
				$emp->setCapSocialReg($cap_social_reg);
				$emp->setAtivo($ativo);
				
				$dao = new EmpresasDAO();
				$dao->update($emp);
				
				
				if(isset($_REQUEST['agregar']) and !empty($_REQUEST['agregar'])){
						
						
					/*echo "<pre>";
					print_r($_REQUEST);*/	
					$idemp = $_SESSION['id_emp']; 
					
					## Endereco Escritorio 
					if(empty($_REQUEST['escritorio_endereco'])){
						$escritorio_endereco = "";
					}else{
						$escritorio_endereco = $_REQUEST['escritorio_endereco'];
					}
					
					if(empty($_REQUEST['escritorio_municipio'])){
						$escritorio_municipio = "";
					}else{
						$escritorio_municipio = $_REQUEST['escritorio_municipio'];
					}				
					
					if(empty($_REQUEST['escritorio_cep'])){
						$escritorio_cep = "";
					}else{
						$escritorio_cep = $_REQUEST['escritorio_cep'];
					}
					
					if(empty($_REQUEST['escritorio_fone'])){
						$escritorio_fone = "";
					}else{
						$escritorio_fone = $_REQUEST['escritorio_fone'];
					}
					
					if(empty($_REQUEST['escritorio_email'])){
						$escritorio_email = "";
					}else{
						$escritorio_email = $_REQUEST['escritorio_email'];
					}
					
					
					
					$daoesc = new EnderecoEscritorioDAO();					
					$vetesc = $daoesc->ListaEnderecoEscritorioPorEmpresa($idemp);
					$numesc = count($vetesc);
					
					for($i = 0; $i < $numesc; $i++){
						
						$escr = $vetesc[$i];
														
						$es = new EnderecoEscritorio();
						
						$es->setCodigo($escr->getCodigo());	
						$es->setIdEmpresa($idemp);
							
						$daoesc->deletar($es);	
							
					}
					
					$esc = new EnderecoEscritorio();
					
					$esc->setEndereco($escritorio_endereco);
					$esc->setMunicipio($escritorio_municipio);
					$esc->setCep($escritorio_cep);
					$esc->setFone($escritorio_fone);
					$esc->setEmail($escritorio_email);
					$esc->setIdEmpresa($idemp);										
					
					$daoesc->inserir($esc);
					## Endereco Escritorio
					
					## Pestação de serviço 1 sim ou 2 não ou 3 não informou
					if(empty($_REQUEST['terceiro_s_n'])){
						$terceiro_s_n = "3";
					}else{
						$terceiro_s_n = $_REQUEST['terceiro_s_n'];
					}
					
					$daopsn = new ServicoTerceiroSNDAO();
					
					$vetsn = $daopsn->ListaServicoTerceiroSNPorEmpresa($idemp);
					$numsn = count($vetsn);
					
					for($i = 0; $i < $numsn; $i++){
						
						$sn = $vetsn[$i];	
						
						$psnt = new ServicoTerceiroSN();
						
						$psnt->setCodigo($sn->getCodigo());
						$psnt->setIdEmpresa($idemp);		
						
						$daopsn->deletar($psnt);
							
					}
					
					$psn = new ServicoTerceiroSN();
					$psn->setSn($terceiro_s_n);
					$psn->setIdEmpresa($idemp);
					
					$daopsn->inserir($psn);
					## Pestação de serviço 1 sim ou 2 não ou 3 não informou
					
					## Certidão e licenciamento
					
					if(empty($_REQUEST['certidao_licenciamento_anexo1'])){
						$certidao_licenciamento_anexo1 = "";
					}else{
						$certidao_licenciamento_anexo1 = $_REQUEST['certidao_licenciamento_anexo1'];
					}
					
					if(empty($_REQUEST['certidao_licenciamento_anexo2'])){
						$certidao_licenciamento_anexo2 = "";
					}else{
						$certidao_licenciamento_anexo2 = $_REQUEST['certidao_licenciamento_anexo2'];
					}
					
					if(empty($_REQUEST['certidao_licenciamento_emit'])){
						$certidao_licenciamento_emit = "";
					}else{
						$certidao_licenciamento_emit = $_REQUEST['certidao_licenciamento_emit'];
					}
					
					if(empty($_REQUEST['certidao_licenciamento_nfepam'])){
						$certidao_licenciamento_nfepam = "";
					}else{
						$certidao_licenciamento_nfepam = $_REQUEST['certidao_licenciamento_nfepam'];
					}
					
					if(empty($_REQUEST['certidao_licenciamento_dt1'])){
						$certdtli = "";
					}else{
						$certidao_licenciamento_dt1 = $_REQUEST['certidao_licenciamento_dt1'];
						
						$certdtli = $_REQUEST['certidao_licenciamento_dt1'].'-'.$_REQUEST['certidao_licenciamento_dt2'].'-'.$certidao_licenciamento_dt1;
						
					}
					
					$daocert = new CertidoesLicenciamentoDAO();
					
					$vetcert = $daocert->ListaCertidoesLicenciamentoPorEmpresa($idemp);
					$numcert = count($vetcert);
					
					for($i = 0; $i < $numcert; $i++){
						
						$certs = $vetcert[$i];		
						
						$certl = new CertidoesLicenciamento();	
						$certl->setCodigo($certs->getCodigo());
						$certl->setIdEmpresa($idemp);	
						
						$daocert->deletar($certl);
						
					}
					
					
					$cert = new CertidoesLicenciamento();
												
					$cert->setCertFiscalTesouroEstado($certidao_licenciamento_anexo1);	
					$cert->setLicencaAmbFepam($certidao_licenciamento_anexo2);
					$cert->setEmitida($certidao_licenciamento_emit);
					$cert->setNumeroProtocoloFepam($certidao_licenciamento_nfepam);
					$cert->setData($certdtli);			
					$cert->setIdEmpresa($idemp);
					
					
					$daocert->inserir($cert);
					## Certidão e licenciamento
					
					
					## Administração
					
					if(empty($_REQUEST['nome_gerente_diretor'])){
						$nome_gerente_diretor = "";
					}else{
						$nome_gerente_diretor = $_REQUEST['nome_gerente_diretor'];
					}
					
					if(empty($_REQUEST['fone_gerente_diretor'])){
						$fone_gerente_diretor = "";
					}else{
						$fone_gerente_diretor = $_REQUEST['fone_gerente_diretor'];
					}
					
					if(empty($_REQUEST['email_gerente_diretor'])){
						$email_gerente_diretor = "";
					}else{
						$email_gerente_diretor = $_REQUEST['email_gerente_diretor'];
					}
					
					if(empty($_REQUEST['nome_contador'])){
						$nome_contador = "";
					}else{
						$nome_contador = $_REQUEST['nome_contador'];
					}
					
					if(empty($_REQUEST['fone_contador'])){
						$fone_contador = "";
					}else{
						$fone_contador = $_REQUEST['fone_contador'];
					}
					
					if(empty($_REQUEST['email_contador'])){
						$email_contador = "";
					}else{
						$email_contador = $_REQUEST['email_contador'];
					}
					
					if(empty($_REQUEST['nome_tecnico'])){
						$nome_tecnico = "";
					}else{
						$nome_tecnico = $_REQUEST['nome_tecnico'];
					}
					
					if(empty($_REQUEST['fone_tecnico'])){
						$fone_tecnico = "";
					}else{
						$fone_tecnico = $_REQUEST['fone_tecnico'];
					}	
					
					if(empty($_REQUEST['email_tecnico'])){
						$email_tecnico = "";
					}else{
						$email_tecnico = $_REQUEST['email_tecnico'];
					}
					
					if(empty($_REQUEST['n_crmv_tecnico'])){
						$n_crmv_tecnico = "";
					}else{
						$n_crmv_tecnico = $_REQUEST['n_crmv_tecnico'];
					}
					
					$daoadm =  new AdministracaoDAO();		
					$vetadm = $daoadm->ListaAdministracaoPorEmpresa($idemp);	
					$numadm = count($vetadm);
					
					for($i = 0; $i < $numadm; $i++){
						
						$adms = $vetadm[$i];		
						
						$admst = new Administracao();	
						
						$admst->setCodigo($adms->getCodigo());
						$admst->setIdEmpresa($idemp);
						
						$daoadm->deletar($admst);
						
					}
					
					
					$adm = new Administracao();
					
					$adm->setNomeGerenteDiretor($nome_gerente_diretor);
					$adm->setFoneGerenteDiretor($fone_gerente_diretor);
					$adm->setEmailGerenteDiretor($email_gerente_diretor);
					$adm->setNomeContador($nome_contador);
					$adm->setFoneContador($fone_contador);
					$adm->setEmailContador($email_contador);
					$adm->setNomeTecnico($nome_tecnico);
					$adm->setFoneTecnico($fone_tecnico);
					$adm->setEmailTecnico($email_tecnico);
					$adm->setCrmvTecnico($n_crmv_tecnico);					
					$adm->setIdEmpresa($idemp);					
					
					$daoadm->inserir($adm);
					## Administração
					
					
					## Inspeção estabelecimento
					
					
					if(empty($_REQUEST['inspecao_estabeleciomento'])){
						$inspecao_estabeleciomento = "0";
					}else{
						$inspecao_estabeleciomento = $_REQUEST['inspecao_estabeleciomento'];
					}
					
					if(empty($_REQUEST['nregistro_inspecao_estabeleciomento'])){
						$nregistro_inspecao_estabeleciomento = "";
					}else{
						$nregistro_inspecao_estabeleciomento = $_REQUEST['nregistro_inspecao_estabeleciomento'];
					}
					
					$daoins  = new InspecaoEstabelecimentoDAO();
					$vetinsp = $daoins->ListaInspecaoEstabelecimentoPorEmpresa($idemp); 	
					$numinsp = count($vetinsp);
					
					for($i = 0; $i < $numinsp; $i++){
						
						$inspe = $vetinsp[$i];	
																	
						$inspet = new InspecaoEstabelecimento();	
						
						$inspet->setCodigo($inspe->getCodigo());
						$inspet->setIdEmpresa($idemp);
						$daoins->deletar($inspet);
						
					}
										
					$insp = new InspecaoEstabelecimento();
					
					$insp->setTipo($inspecao_estabeleciomento);
					$insp->setNregistro($nregistro_inspecao_estabeleciomento);
					$insp->setIdEmpresa($idemp);
										
					$daoins->inserir($insp);
					
					## Inspeção estabelecimento
					
					## Veterinario estabelecimento
					
					if(empty($_REQUEST['nome_veterinario_estabelecimento'])){
						$nome_veterinario_estabelecimento = "";
					}else{
						$nome_veterinario_estabelecimento = $_REQUEST['nome_veterinario_estabelecimento'];
					}
					
					if(empty($_REQUEST['crmv_veterinario_estabelecimento'])){
						$crmv_veterinario_estabelecimento = "";
					}else{
						$crmv_veterinario_estabelecimento = $_REQUEST['crmv_veterinario_estabelecimento'];
					}
					
					
					if(empty($_REQUEST['endereco_veterinario_estabelecimento'])){
						$endereco_veterinario_estabelecimento = "";
					}else{
						$endereco_veterinario_estabelecimento = $_REQUEST['endereco_veterinario_estabelecimento'];
					}
					
					if(empty($_REQUEST['email_veterinario_estabelecimento'])){
						$email_veterinario_estabelecimento = "";
					}else{
						$email_veterinario_estabelecimento = $_REQUEST['email_veterinario_estabelecimento'];
					}
					
					if(empty($_REQUEST['veterinario_estabelecimento_s_n'])){
						$veterinario_estabelecimento_s_n = "3";
					}else{
						$veterinario_estabelecimento_s_n = $_REQUEST['veterinario_estabelecimento_s_n'];
					}
					
					if(empty($_REQUEST['org_municipio_veterinario_estabelecimento'])){
						$org_municipio_veterinario_estabelecimento = "";
					}else{
						$org_municipio_veterinario_estabelecimento = $_REQUEST['org_municipio_veterinario_estabelecimento'];
					}
					
					
					$daoest = new VeterinarioEstabelecimentoDAO();
					$vetest = $daoest->ListaVeterinarioEstabelecimentoPorEmpresa($idemp);
					$numest = count($vetest);
					
					for($i = 0; $i < $numest; $i++){
						
						$ests = $vetest[$i];	
						
						$esta =  new VeterinarioEstabelecimento();	
						$esta->setCodigo($ests->getCodigo());
						$esta->setIdEmpresa($idemp);
						
						$daoest->deletar($esta);
						
					}
					
					$est =  new VeterinarioEstabelecimento();
					
					$est->setNome($nome_veterinario_estabelecimento);
					$est->setCrmv($crmv_veterinario_estabelecimento);
					$est->setEndereco($endereco_veterinario_estabelecimento);
					$est->setEmail($email_veterinario_estabelecimento);
					$est->setConvenioMunicipio($veterinario_estabelecimento_s_n);
					$est->setOrgMunicipio($org_municipio_veterinario_estabelecimento);
					$est->setIdEmpresa($idemp);
					
					
					$daoest->inserir($est);
					
				}
				
				$res    = array();

				array_push($res,array(
					'titulo'=>"Mensagem da Alteção da empresa",
					'mensagem'=>"Alterado com sucesso!",
					'url'=>'admin.php',
					'tipo'=>'1'
				));

				$re      = json_encode($res);
				$reponse = urlencode($re);

				$destino = "response.php?mg={$reponse}";
				header("Location:{$destino}");
			break;
			case 'delete':
				
				$id  = $_REQUEST['id'];		
				
				$emp =  new Empresas();
				
				$emp->setCodigo($id);
				
				$dao = new EmpresasDAO();
				$dao->deletar($emp);
				
			break;
			case 'inserir_emp_login':
				
				$results = array();
				if(empty($_REQUEST['cnpj'])){
					$cnpj = "";
				}else{
					$cnpj = $_REQUEST['cnpj'];
					$cnpj = preg_replace("/\D+/", "", $cnpj);					
				}
				
				if(empty($_REQUEST['nome_emp'])){
					$nome = "";
				}else{
					$nome = $_REQUEST['nome_emp'];
				}
				
				if(empty($_REQUEST['fantasia_emp'])){
					$fantasia = "";
				}else{
					$fantasia = $_REQUEST['fantasia_emp'];
				}
				
								
				if(empty($_REQUEST['email_emp'])){
					$email = "";
				}else{
					$email = $_REQUEST['email_emp'];	
				}
				
								
				if(empty($_REQUEST['cep_emp'])){
					$cep = "";
				}else{
					$cep = $_REQUEST['cep_emp'];
				}
				
				if(empty($_REQUEST['endereco_emp'])){
					$ende = "";
				}else{
					$ende = $_REQUEST['endereco_emp'];
				}
				
				if(empty($_REQUEST['bairro_emp'])){
					$bairro = "";
				}else{
					$bairro = $_REQUEST['bairro_emp'];
				}
				
				if(empty($_REQUEST['numero_emp'])){
					$nro = "";
				}else{
					$nro = $_REQUEST['numero_emp'];
				}
				
			
				if(empty($_REQUEST['cidade_emp'])){
					$cidade = "";
				}else{
					$cidade = $_REQUEST['cidade_emp'];
				}
								
				if(empty($_REQUEST['estado_emp'])){
					$uf = "";
				}else{
					$uf = $_REQUEST['estado_emp'];
				}
				
				if(empty($_REQUEST['fone_emp'])){
					$fone_emp = "";
				}else{
					$fone_emp = $_REQUEST['fone_emp'];
				}
	
				
				$emp =  new Empresas();
				
				$emp->setCnpj($cnpj);
				$emp->setRazaoSocial($nome);
				$emp->setFantasia($fantasia);
				$emp->setEndereco($ende);
				$emp->setNumero($nro);			
				$emp->setCep($cep);
				$emp->setCidade($cidade);
				$emp->setEstado($uf);
				$emp->setBairro($bairro);
				$emp->setFone1($fone_emp);
				$emp->setEmail($email);
				$emp->setAtivo(2);
					
				$dao = new EmpresasDAO();
				$dao->inserir2($emp);	
					
				 array_push($results, array(
					'msg' => 'Sua empresa foi cadastrado com sucesso, aguarde o contato de liberação para acesso, Obrigado!',									
			));			
				
				
		   echo (json_encode($results));	
					
			break;
			
			case 'empresa_modalidade':
				
				
				$condicao  = array();
				
				
				if(isset($_REQUEST['idmod']) and !empty($_REQUEST['idmod'])){

					$idmod       =  $_REQUEST['idmod'];	
		
					$condicao[]  = " e.id_modalidade = '".$idmod."' ";		
				}
				
				$where = '';

				if(count($condicao) > 0){
				
					$where = ' where'.implode('AND',$condicao);
						
				}	
				
				
				$dao = new EmpresasDAO();
				$vet = $dao->ListaEmpresaPorModalidadeUm($where);
				$num = count($vet);
				$result = array();
				
				for($i = 0; $i < $num; $i++){
										
					$emp = $vet[$i];
					
					$cod 		  = $emp->getCodigo();				
					$razao_social = $emp->getRazaoSocial();
					
					array_push($result, array(
						'cod' => ''.$cod.'',
						'razao_social' => ''.$razao_social.'',									
					));			
				
				
				}
				
				 echo (json_encode($result));
				 
			break;
			case 'buscar':

				$term  = !empty($_REQUEST['term']) ? $_REQUEST['term'] : '';
				
				$dao 	= new EmpresasDAO();
				$vet 	= $dao->BuscaEmpresa($term);
				$num 	= count($vet);
				$result = array();

				for($i = 0; $i < $num; $i++){

					$emp          = $vet[$i];  

					$cod  		  = $emp->getCodigo();				
					$cnpj 		  = $emp->getCnpj();
					$razao_social = $emp->getRazaoSocial();
					$fantasia     = $emp->getFantasia();
					$endereco     = $emp->getEndereco();
					$nro          = $emp->getNumero();
					$cidade       = $emp->getCidade();
					$estado       = $emp->getEstado();
					$inspecao     = $emp->getInspecao();
					$nome         = !empty($fantasia) ? $razao_social.'('.$fantasia.')':$razao_social;

					array_push($result, array(
						'label'=>''.$nome.'',
						'value' => ''.$nome.'',
						'cnpj' => ''.$cnpj.'',
						'cod' => ''.$cod.'',							
					));	

				}

				echo json_encode($result);
					
			break;
			case 'remove':
				$id  = $_REQUEST['id'];	

				$emp = new Empresas();

				$emp->setCodigo($id);				

				$dao = new EmpresasDAO();

				$dao->deletar($emp);


				$daoesc = new EnderecoEscritorioDAO();					
				$vetesc = $daoesc->ListaEnderecoEscritorioPorEmpresa($id);
				$numesc = count($vetesc);
				
				for($i = 0; $i < $numesc; $i++){
					
					$escr = $vetesc[$i];
													
					$es = new EnderecoEscritorio();
					
					$es->setCodigo($escr->getCodigo());	
					$es->setIdEmpresa($id);
						
					$daoesc->deletar($es);	
						
				}

				$daopsn = new ServicoTerceiroSNDAO();					
				$vetsn = $daopsn->ListaServicoTerceiroSNPorEmpresa($id);
				$numsn = count($vetsn);
				
				for($i = 0; $i < $numsn; $i++){
					
					$sn = $vetsn[$i];	
					
					$psnt = new ServicoTerceiroSN();
					
					$psnt->setCodigo($sn->getCodigo());
					$psnt->setIdEmpresa($id);		
					
					$daopsn->deletar($psnt);
						
				}


				$daocert = new CertidoesLicenciamentoDAO();					
				$vetcert = $daocert->ListaCertidoesLicenciamentoPorEmpresa($id);
				$numcert = count($vetcert);
				
				for($i = 0; $i < $numcert; $i++){
					
					$certs = $vetcert[$i];		
					
					$certl = new CertidoesLicenciamento();	
					$certl->setCodigo($certs->getCodigo());
					$certl->setIdEmpresa($id);	
					
					$daocert->deletar($certl);
					
				}

				$daoadm =  new AdministracaoDAO();		
				$vetadm = $daoadm->ListaAdministracaoPorEmpresa($id);	
				$numadm = count($vetadm);
				
				for($i = 0; $i < $numadm; $i++){
					
					$adms = $vetadm[$i];		
					
					$admst = new Administracao();	
					
					$admst->setCodigo($adms->getCodigo());
					$admst->setIdEmpresa($id);
					
					$daoadm->deletar($admst);
					
				}

				$daoins  = new InspecaoEstabelecimentoDAO();
				$vetinsp = $daoins->ListaInspecaoEstabelecimentoPorEmpresa($id); 	
				$numinsp = count($vetinsp);
				
				for($i = 0; $i < $numinsp; $i++){
					
					$inspe = $vetinsp[$i];	
																
					$inspet = new InspecaoEstabelecimento();	
					
					$inspet->setCodigo($inspe->getCodigo());
					$inspet->setIdEmpresa($id);
					$daoins->deletar($inspet);
					
				}

				$daoest = new VeterinarioEstabelecimentoDAO();
				$vetest = $daoest->ListaVeterinarioEstabelecimentoPorEmpresa($id);
				$numest = count($vetest);
				
				for($i = 0; $i < $numest; $i++){
					
					$ests = $vetest[$i];	
					
					$esta =  new VeterinarioEstabelecimento();	
					$esta->setCodigo($ests->getCodigo());
					$esta->setIdEmpresa($id);
					
					$daoest->deletar($esta);
					
				}

				$daou =  new UsuarioDAO();
				$vetu = $daou->ListaUsuarioPorEmpresa($id);
				$numu = count($vetu);

				for ($i=0; $i < $numu; $i++) { 
					
					$usu = $vetu[$i];

					$cod = $usu->getCodigo();

					$users = new Usuario();

					$users->setCodigo($cod);


					$daou->deletar($users);

				}

				$res    = array();

				array_push($res,array(
					'titulo'=>"Mensagem de exclução da empresa",
					'mensagem'=>"Excluido com sucesso!",
					'url'=>'Listar-Empresas.php',
					'tipo'=>'1'
				));

				$re      = json_encode($res);
				$reponse = urlencode($re);

				$destino = "response.php?mg={$reponse}";
				header("Location:{$destino}");

			break;
			case 'alterPass':
				
				$daofunc = new FuncoesDAO();

				if(!empty($_REQUEST['atusenha']) AND !empty($_REQUEST['nsenha'])){
					
					$atusenha  = $_REQUEST['atusenha'];
					$novasenha = $_REQUEST['nsenha'];

					

					$daou =  new Usuario2DAO();
					$vetu = $daou->VerificaSenhaAtual($_SESSION['id_emp'],$_SESSION['cnpj']);	
					
					if(!empty($vetu)){

						$senhaatual = $vetu[0]['senha'];	

						if($senhaatual == sha1($atusenha)){
							//atualizar senha
							$pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z\d](?=.*[@#$%^&+=]).\S{8,36}$/';
							
							if(preg_match($pattern, $novasenha) == false){
								echo $daofunc->ajaxresponse("message",[
									"type"=>"alert",
									"message"=>"Ops, Senha muito fraca !<br/><br/> Regra 1 – A senha deve ter no mínimo 8 e no máximo 36 caracteres.<br/>Regra 2 – A senha deve ser composta de ao menos 1 número, ao menos uma letra maiúscula, e ao menos uma letra minúscula.<br/>Regra 3 – A senha deve ser composta de apenas letras e números e não deve possuir nenhum tipo de caracteres especiais. Regra 4 - Possua pelo menos 1 caractere especial; ",								
								]);	
							}else{
								$daou->AtualizaPass($vetu[0]['id'],sha1($novasenha));
							}
						}else{
							echo $daofunc->ajaxresponse("message",[
								"type"=>"alert",
								"message"=>"Senha atual incorreta, tente novamente!",								
							]);	
						}
						
					}else{
						echo $daofunc->ajaxresponse("message",[
							"type"=>"error",
							"message"=>"Ops, tentativa errada tente com acesso correto!",								
						]);	
					}


				}else{
					echo $daofunc->ajaxresponse("message",[
						"type"=>"alert",
						"message"=>"Ops, Informar os dados para alteração da senha!",								
					]);	
				}


			break;
			case 'buscarum':

				$term  = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';
				
				$dao 	= new EmpresasDAO();
				$vet 	= $dao->ListaEmpresaUm($term);
				$num 	= count($vet);
				$result = array();

				for($i = 0; $i < $num; $i++){

					$emp          = $vet[$i];  

					$cod  		  = $emp->getCodigo();				
					$cnpj 		  = $emp->getCnpj();
					$razao_social = $emp->getRazaoSocial();
					$fantasia     = $emp->getFantasia();
					$endereco     = $emp->getEndereco();
					$nro          = $emp->getNumero();
					$cidade       = $emp->getCidade();
					$estado       = $emp->getEstado();
					$inspecao     = $emp->getInspecao();
					$email        = $emp->getEmail();

					array_push($result, array(
						'label'=>''.$razao_social.'',
						'value' => ''.$razao_social.'',
						'cnpj' => ''.$cnpj.'',
						'email' => ''.$email.'',
						'cod' => ''.$cod.'',							
					));	

				}

				echo json_encode($result);
					
			break;

		}

	}
	

?>
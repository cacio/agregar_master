<?php
	require_once('../inc/inc.autoload.php');
	session_start();
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){
		
		$act = $_REQUEST['act'];
		
		switch($act){
			
		case 'box':
			
			/*echo '<pre>';
			print_r($_FILES);*/
			$funcoes = new FuncoesDAO();
			
			$erro   = array();	
			$result = array(); 
						
			$dir  = "../arquivos/";
			
			if(!is_dir("".$dir."".$_SESSION['cnpj']."")){				
				mkdir("".$dir."".$_SESSION['cnpj']."", 0777, true);
			}
			
			$arquivos 	   = $_FILES;			
			$countarquivo  = count($_FILES);
				
			if($countarquivo >= 6){
				
				$_SESSION['apura']['mesano'] = $_REQUEST['dtmesano'];

				for($i = 0; $i < $countarquivo; $i++){										
						
						$value = $arquivos[$i];
						
						$ext     	 = $funcoes->getExtension($value['name']);									
						$tmp     	 = $value['tmp_name'];
						$novodir 	 = "".$dir."".$_SESSION['cnpj']."/";
						$actual_name = strtoupper($value['name']);
						
						if($ext == 'txt' or $ext == 'TXT'){
												
							
							if(move_uploaded_file($tmp, $novodir.$actual_name)){
							
								array_push($result, array(
										'msg' => 'Upload do arquivo '.$actual_name.' efetuado com sucesso! ',					
								));			
							
							}else{
								
								array_push($erro, array(
										'msg' => 'Falhou upload Arquivo: '.$novodir.$actual_name.'',					
								));
							
							}
							
								
							
						}else{
						
							array_push($erro, array(
										'msg' => 'Ops, ['.$value.'] não é um arquivo texto, tente novamente!',					
							));
						
						}	
						
				}
						
			}else{
				
				array_push($erro, array(
							'msg' => 'Falta Arquivos para importação',					
				));
			
			}
			
			$data = array('result'=>$result,'erro'=>$erro);
				
			echo json_encode($data);
			
		break;
		case 'box2':

			$funcoes = new FuncoesDAO();
			
			$erro   = array();	
			$result = array(); 
						
			$dir    = "../arquivos/";
			
			if(!is_dir("".$dir."".$_SESSION['cnpj']."")){				
				mkdir("".$dir."".$_SESSION['cnpj']."", 0777, true);
			}

			 foreach($_FILES['file']['tmp_name'] as $key => $value) {

		       /* $tmp         = $_FILES['file']['tmp_name'][$key];
		        $actual_name =  $storeFolder. $_FILES['file']['name'][$key];*/

		        $ext     	 = $funcoes->getExtension($_FILES['file']['name'][$key]);									
				$tmp     	 = $_FILES['file']['tmp_name'][$key];
				$novodir 	 = "".$dir."".$_SESSION['cnpj']."/";
				$actual_name = strtoupper($_FILES['file']['name'][$key]);

				if($ext == 'txt' or $ext == 'TXT'){
												
							
					if(move_uploaded_file($tmp, $novodir.$actual_name)){
					
						array_push($result, array(
								'msg' => 'Upload do arquivo '.$actual_name.' efetuado com sucesso! ',					
						));			
					
					}else{
						
						array_push($erro, array(
								'msg' => 'Falhou upload Arquivo: '.$novodir.$actual_name.'',					
						));
					
					}
					
						
					
				}else{
				
					array_push($erro, array(
								'msg' => 'Ops, ['.$value.'] não é um arquivo texto, tente novamente!',					
					));
				
				}	

		    }

		    $data = array('result'=>$result,'erro'=>$erro);
				
			echo json_encode($data);


		break;

		case 'analizar':
			
			$_SESSION['apura']['mesano'] = date('m/Y',strtotime(implode("-", array_reverse(explode("/", "".$_REQUEST['dtini']."")))));
			$alert_nfunc    = array();
			$alert_vlfolha  = array();	
			$alert_icmsnorm = array();	
			$alert_icmsst   = array();	
			$alert_gta      = array();
			
			$numnotasenttxt = 0;
			$numnotasaitxt  = 0;

			$path_empresa = "../arquivos/".$_SESSION['cnpj']."/EMPRESAS.TXT"; // pegando caminho do txt empresas
			$xerro_emp	  = array(); 
			
			$valid		  = new FuncoesDAO();
			$daop		  = new ProdutosAgregarDAO();
			$daoemp	      = new EmpresasTxtDAO();
			$daoprodfrig  = new ProdFrigTxtDAO(); 
			
			if(file_exists($path_empresa)){
				
				$lines = file ('../arquivos/'.$_SESSION['cnpj'].'/EMPRESAS.TXT');
				
				foreach ($lines as $line_num => $line) {
					
					$line = $line;
					
					$cnpj_cpf 	 = substr($line,0,14);
					$cpf 		 = substr($line,3,11);
					$indcnpjcpfemp = substr($line,0,3); 					
					$ie		  	 = substr($line,14,13);
					$razaosocial = substr($line,27,39);				
					$cidade		 = substr($line,67,35);
					$uf			 = substr($line,103,2);
					$tipo		 = substr($line,105,1);														
					$indcpfcnpj  = "";
					
					## VALIDANDO CNPJ E CPF
					if(empty($cpf)){
						array_push($xerro_emp, array(
							'msg' => 'FALTA INFORMAR O CPF',					
						));	
						
					}else{
					
						 $valid_cpf = $valid->Valida_Cnpj_Cpf($cpf);
						
						 if($valid_cpf == false){
							$indcpfcnpj = 0;		
							 $valid_cnpj_cpf = $valid->Valida_Cnpj_Cpf($cnpj_cpf);
							
							 if($valid_cnpj_cpf == false){
								$indcpfcnpj = 0;
															
							}else{
								$indcpfcnpj = 1;	
							}	
							
						}else{
							$indcpfcnpj = 1;
						}
							
							
						if($indcpfcnpj == 0){
							array_push($xerro_emp, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - CNPJ OU CPF INVALIDO!',					
							));
						
						}	
							
					}
					
					## FIM DA VALIDAÇÃO CNPJ E CPF			
					
					##VALIDANDO A INSCRIÇÃO ESTADUAL
						
					$valid_ie = $valid->inscricao_estadual(trim($ie),trim($uf));
							
					if($valid_ie == 0 and trim($ie) != 'ISENTO'){
					
							array_push($xerro_emp, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - INSCRIÇÃO ESTADUAL INVALIDA!',					
							));
						
					}
					
					## FIM DA VALIDAÇÃO DSA INSCRIÇÃO ESTADUAL
					
					## VALIDADANDO RAZAO SOCIAL
					if(strlen(trim($razaosocial)) == 0){
					
						array_push($xerro_emp, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - SEM RAZAO SOCIAL!',					
						));	
					
					}
					## FIM DA VALIDAÇÃO DA  RAZAO SOCIAL
					
					## VALIDADANDO CIDADE
					if(strlen(trim($cidade)) == 0){
						
						array_push($xerro_emp, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - SEM CIDADE!',					
						));
					
					}
					## FIM DA VALIDAÇÃO DA CIDADE
					
					## VALIDADANDO UF
					if(strlen(trim($uf)) == 0){
						array_push($xerro_emp, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - SEM UF!',					
						));
					
					}
					## FIM DA VALIDAÇÃO DA UF
					
					## VALIDADANDO TIPO INDICADOR [V]AREJO [A]TACADO [G]ERAL [P]RODUTOR [M]ARCHANTE
					if($tipo != "V" and $tipo != "A" and $tipo != "G" and $tipo != "P" and $tipo != "M"){
						
						array_push($xerro_emp, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - INDICADOR [V]AREJO [A]TACADO [G]ERAL [P]RODUTOR [M]ARCHANTE!',					
						));
						
					} 
					
					## FIM DA VALIDAÇÃO TIPO INDICADOR [V]AREJO [A]TACADO [G]ERAL [P]RODUTOR [M]ARCHANTE
					
					$vetemp = $daoemp->VerificaEmpresasCadastradas(trim($cnpj_cpf),trim($cpf),trim($ie),$_SESSION['cnpj']);
					$numemp = count($vetemp);
					
					if($numemp == 0){
						
						if($indcnpjcpfemp == '000' or $indcnpjcpfemp == '   '){
							$cnpj_cpf = $cpf;
						}

						$emp = new EmpresasTxt();	
						
						$emp->setCnpjCpf(trim($cnpj_cpf));
						$emp->setInscEstadual(trim($ie));
						$emp->setRazao($razaosocial);
						$emp->setCidade($cidade);
						$emp->setUf($uf);
						$emp->setTipo($tipo);
						$emp->setCnpjEmp($_SESSION['cnpj']);
						$daoemp->inserir($emp);	
					}						
					
				}
				
			}else{
									
				array_push($xerro_emp, array(
							'msg' => 'Falta o arquivo empresas para importação!',					
				));		
			}
			
			## FIM DA VALIDAÇÃO DAS EMPRESAS
			
			## COMEÇO DA VALIDAÇÃO DO ARQUIVO 	PRODFRIG.TXT
			$path_prodfrig   = "../arquivos/".$_SESSION['cnpj']."/PRODFRIG.TXT"; // pegando caminho do txt PRODFRIG
			$xerro_prodfrig  = array();
			
			if(file_exists($path_prodfrig)){
				
				$lines = file ('../arquivos/'.$_SESSION['cnpj'].'/PRODFRIG.TXT');
				
				foreach ($lines as $line_num => $line) {
					
					$line = $line;
					
					$codigo_produto     = substr($line,0,14);
					$descricao_produto  = substr($line,14,40);
					$codprodsecreataria = substr($line,54,68);
					
					## VALIDAÇÃO CODIGO PRODUTO EXISTENTE NA TABELA (FALTA VALIDAR NA TABELA)
					if(strlen(trim($codigo_produto)) == 0){
					
						array_push($xerro_prodfrig, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - CODIGO DO PRODUTO INEXISTE!',					
						));
					}
					##  FIM DA VALIDAÇÃO CODIGO PRODUTO EXISTENTE NA TABELA
					
					## VALIDAÇÃO DA DESCRIÇÃO DO PRODUTO
					if(strlen(trim($descricao_produto)) == 0){
						
						array_push($xerro_prodfrig, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - DESCRICAO DO PRODUTO NO FRIGORIFICO!',					
						));	
							
					}
					## FIM DA VALIDAÇÃO DA DESCRIÇÃO DO PRODUTO
					
					## VALIDAÇÃO CODIGO DO PRODUTO NA SECRETARIA
					if(strlen(trim($codprodsecreataria)) == 0){
						
						array_push($xerro_prodfrig, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - CODIGO DO PRODUTO NA SECRETARIA INEXISTE!',					
						));	
					
					}
					
					$vetp = $daop->VerificaProdutoAgregar(trim($codprodsecreataria));
					$nump = count($vetp);
					
					if($nump == 0){
						
						array_push($xerro_prodfrig, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - CODIGO DO PRODUTO '.$codprodsecreataria.' NA SECRETARIA INEXISTE!',					
						));
					
					}
					
					## FIM DA VALIDAÇÃO CODIGO DO PRODUTO NA SECRETARIA											
					
					$vetprodfrig =  $daoprodfrig->VerificaProduto(trim($codigo_produto),$_SESSION['cnpj']);
					$numprodfrig = count($vetprodfrig);
					
					if($numprodfrig == 0){
						
						$prodfrig = new ProdFrigTxt();
						
						$prodfrig->setCodProd(trim($codigo_produto));
						$prodfrig->setDescProd(trim($descricao_produto));
						$prodfrig->setCodSecretaria(trim($codprodsecreataria));
						$prodfrig->setCnpjEmp($_SESSION['cnpj']);
						
						$daoprodfrig->inserir($prodfrig);
						
					}
					
				
				}
				
			
			}else{
			
				array_push($xerro_prodfrig, array(
							'msg' => 'Falta o arquivo PRODFRIG para importação!',					
				));	
				
			}	
			## FIM DA VALIDAÇÃO DO ARQUIVO 	PRODFRIG.TXT	
			
			## COMEÇO DA VALIDAÇÃO DO ARQUIVO NOTASENT.TXT
			$path_notasent   = "../arquivos/".$_SESSION['cnpj']."/NOTASENT.TXT"; // pegando caminho do txt NOTASENT
			$xerro_notasent  = array();
			
			if(file_exists($path_notasent)){
				
				$lines = file ('../arquivos/'.$_SESSION['cnpj'].'/NOTASENT.TXT');
				
				foreach ($lines as $line_num => $line) {
					
					$line =  $line;
					
					$numero_nota   			  = substr($line,0,6);
					$emissao	   			  = substr($line,6,8);
					$cnpj_cpf      			  = substr($line,14,14);
					$cpf		  			  = substr($line,17,11);
					$tipo_v_t_a    			  = substr($line,28,1);
					$valortotanota 			  = substr($line,29,16);
					$gta		  			  = substr($line,44,6);
					$numero_nota_produtor_ini = substr($line,50,6);
					$numero_nota_produtor_fim = substr($line,56,6);
					$tipo_s_n				  = substr($line,62,1);
					$tipo_p_t				  = substr($line,63,1);
					$ie_produtor			  =	substr($line,64,14);
					$indcpfcnpj  			  = "";
					
					//echo date('d/m/Y',strtotime($emissao));
					## VALIDANDO CNPJ E CPF
					if(empty($cpf)){
						array_push($xerro_notasent, array(
							'msg' => 'FALTA INFORMAR O CPF',					
						));	
						
					}else{
					
						 $valid_cpf = $valid->Valida_Cnpj_Cpf($cpf);
						
						 if($valid_cpf == false){
							$indcpfcnpj = 0;		
							 $valid_cnpj_cpf = $valid->Valida_Cnpj_Cpf($cnpj_cpf);
							
							 if($valid_cnpj_cpf == false){
								$indcpfcnpj = 0;
															
							}else{
								$indcpfcnpj = 1;	
							}	
							
						}else{
							$indcpfcnpj = 1;
						}
							
							
						if($indcpfcnpj == 0){
							array_push($xerro_notasent, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - CNPJ OU CPF INVALIDO!',					
							));
						
						}	
							
					}
					
					$vetemp = $daoemp->VerificaEmpresasCadastradas(trim($cnpj_cpf),trim($cpf),trim($ie_produtor),$_SESSION['cnpj']);
					$numemp = count($vetemp);
					
					if($numemp == 0){
						
						array_push($xerro_notasent, array(
							'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - CNPJ/CPF/INSC [PRODUTOR] INEXISTE/CADASTRO EMPRESAS-> CNPJ/CPF-> ('.$cnpj_cpf.' - '.$cpf.') INSC-> '.trim($ie_produtor).' !',					
						));
							
					}
										
					## FIM DA VALIDAÇÃO CNPJ E CPF	
					
					## VALIDAÇÃO DO NUMERO DA NOTA 
					if(strlen(trim($numero_nota)) < 6){
							
						array_push($xerro_notasent, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - NUMERO DA NOTA-> '.$numero_nota.' !',					
						));	
					
					}
					## FIM DA VALIDAÇÃO DO NUMERO DA NOTA
				
					## VALIDAÇÃO DA DATA DE EMISSÃO
					if(strlen(trim($emissao)) == 0 and $valid->ValidaData(date('d/m/Y',strtotime($emissao))) == false){
						array_push($xerro_notasent, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - DATA DE EMISSAO INVALIDA!',					
						));	
					}
					## FIM DA VALIDAÇÃO DA DATA DE EMISSÃO
					
					## VALIDAÇÃO VIVO OU REDIMENTO
					if($tipo_v_t_a != "V" and $tipo_v_t_a != "R" and $tipo_v_t_a != "A"){
						
						array_push($xerro_notasent, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - INDICADOR [V]IVO OU [R]ENDIMENTO->'.$tipo_v_t_a.'!',					
						));
						
					}
					## FIM DA VALIDAÇÃO VIVO OU REDIMENTO
					
					## VALIDAÇÃO VALOR TOTAL DA NOTA
					if(trim($valortotanota) < '0.01'){
						
						array_push($xerro_notasent, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - VALOR TOTAL DA NOTA FISCAL ->'.number_format(trim($valortotanota),2,',','.').'!',					
						));
						
					}
					## FIM DA VALIDAÇÃO VALOR TOTAL DA NOTA
					
					## VALIDAÇÃO DE CONDENAS SE SIM OU NÃO
					if(trim($tipo_s_n) != 'S' and trim($tipo_s_n) != 'N'){
					
						array_push($xerro_notasent, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - INDICADOR DE CONDENAS [S]IM [N]AO->'.$tipo_s_n.'!',					
						));
					}
					## FIM DA VALIDAÇÃO DE CONDENAS SE SIM OU NÃO
					
					## VALIDAÇÃO SE ABATE PROPRIO OU TERCEIRO
					if(trim($tipo_p_t) != 'P' and trim($tipo_p_t) != 'T'){
						array_push($xerro_notasent, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - INDICADOR DE ABATE [P]ROPRIO OU [T]ERCEIROS->'.$tipo_p_t.'!',					
						));
					
					}
					## FIM DA VALIDAÇÃO SE ABATE PROPRIO OU TERCEIRO
					

					$daogta = new GtaTxtDAO();
					$vetgta = $daogta->GtaEmpresas($numero_nota,$_SESSION['cnpj']);
					$numgta = count($vetgta);
						
					if($numgta == 0){
						
						array_push($alert_gta,array(
								'codigo'=>''.$numero_nota.'',
								'msg'=>' Número da nota ('.$numero_nota.') Não existem numeros de GTA informados!',
						));
						
					}

					//echo "Linha #<b>{($line_num + 1)}</b> : ".$line."<br>\n";
					$numnotasenttxt++;
				}
				
			
			}else{
				
				array_push($xerro_notasent, array(
							'msg' => 'Falta o arquivo NOTASENT para importação!',					
				));	
						
			}				
			## FIM DA VALIDAÇÃO DO ARQUIVO NOTASENT.TXT
			
			## COMEÇO DA VALIDAÇÃO DO ARQUIVO NOTASEN1.TXT
			$path_notasen1   = "../arquivos/".$_SESSION['cnpj']."/NOTASEN1.TXT"; // pegando caminho do txt NOTASEN1
			$xerro_notasen1  = array();
			
			if(file_exists($path_notasen1)){
				
				$lines = file ('../arquivos/'.$_SESSION['cnpj'].'/NOTASEN1.TXT');
				
				foreach ($lines as $line_num => $line) {
					
					$line =  $line;
					
					$numero_nota1 = substr($line,0,6);
					$emissao1	  = substr($line,6,8);
					$cnpj_cpf1	  = substr($line,14,14);
					$cpf1		  = substr($line,17,11);
					$cod_produto  = substr($line,28,14);
					$qtdcabecas	  = substr($line,42,6);
					$pesovivo	  = substr($line,47,16);
					$pesocarcasa  = substr($line,62,15);
					$precoquilo   = substr($line,77,16);
					$numero_item  = substr($line,92,3);	
					$ie1		  = substr($line,95,14);		
					$dataabate	  =	substr($line,109,8);
					$tipo_r_v	  = substr($line,117,1);
					$cfop		  = substr($line,118,4);	
					$aliquotaicms = substr($line,122,5);
					$indcpfcnpj   = "";
				
					## VALIDANDO CNPJ E CPF
					if(empty($cpf1)){
						array_push($xerro_notasen1, array(
							'msg' => 'FALTA INFORMAR O CPF',					
						));	
						
					}else{
					
						 $valid_cpf = $valid->Valida_Cnpj_Cpf($cpf1);
						
						 if($valid_cpf == false){
							$indcpfcnpj = 0;		
							 $valid_cnpj_cpf = $valid->Valida_Cnpj_Cpf($cnpj_cpf1);
							
							 if($valid_cnpj_cpf == false){
								$indcpfcnpj = 0;
															
							}else{
								$indcpfcnpj = 1;	
							}	
							
						}else{
							$indcpfcnpj = 1;
						}
							
							
						if($indcpfcnpj == 0){
							array_push($xerro_notasen1, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - CNPJ OU CPF INVALIDO!',					
							));
						
						}	
							
					}	
					
					/*$vetemp = $daoemp->VerificaEmpresasCadastradas(trim($cnpj_cpf1),trim($cpf1),trim($ie1),$_SESSION['cnpj']);
					$numemp = count($vetemp);
					
					if($numemp == 0){
						
						array_push($xerro_notasen1, array(
							'msg' => 'Linha #<b>{'.$line_num.'}</b> - CNPJ/CPF/INSC [PRODUTOR] INEXISTE/CADASTRO DE EMPRESAS-> ('.$cnpj_cpf1.' - '.$cpf1.') INSC-> '.trim($ie1).' !',					
						));
							
					}*/				
					## FIM DA VALIDAÇÃO CNPJ E CPF
					
					## VALIDAÇÃO DO NUMERO DA NOTA
					if(strlen(trim($numero_nota1)) < 6){
							
						array_push($xerro_notasen1, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - NUMERO DA NOTA-> '.$numero_nota1.' !',					
						));	
					
					}	
					## FIM DA VALIDAÇÃO DO NUMERO DA NOTA
						
					## VALIDAÇÃO DA DATA DE EMISSÃO	
					if(strlen(trim($emissao1)) == 0 and $valid->ValidaData(date('d/m/Y',strtotime($emissao1))) == false){
						array_push($xerro_notasen1, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - DATA DE EMISSAO INVALIDA!',					
						));	
					}	
					## FIM DA VALIDAÇÃO DA DATA DE EMISSÃO
					
					## VALIDA DATA INICIAL E DATA FINAL SE A DATA ESTA ENTRE AS INFORMADA
					if(isset($_REQUEST['dtini']) and !empty($_REQUEST['dtini'])){
						
						if(isset($_REQUEST['dtfim']) and !empty($_REQUEST['dtfim'])){	
							
							if(strtotime(date('Y/m/d',strtotime($dataabate))) < strtotime(implode("/", array_reverse(explode("/", "".$_REQUEST['dtini']."")))) or strtotime(date('Y/m/d',strtotime($dataabate))) > strtotime(implode("/", array_reverse(explode("/", "".$_REQUEST['dtfim'].""))))){
							
								array_push($xerro_notasen1, array(
										'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - NUMERO NOTA: '.$numero_nota1.' DATA DE ABATE FORA DO PERIODO '.date('d/m/Y',strtotime($dataabate)).'!',					
								));	
					
							}
							
						}else{
							
								array_push($xerro_notasen1, array(
										'msg' => 'DATA FINAL NÃO INFORMADA!',					
								));	
							
						}
					}else{
						
						array_push($xerro_notasen1, array(
										'msg' => 'DATA INICIAL NÃO INFORMADA!',					
						));
						
					}
					## FIM DA VALIDACAO DATA INICIAL E DATA FINAL SE A DATA ESTA ENTRE AS INFORMADA
					
					## VALIDAÇÃO DAS PECAS	
					if(trim($qtdcabecas) < 1){
						
						array_push($xerro_notasen1, array(
										'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - QUANTIDADE DE CABECAS INFORMADAS INCONSISTENTE!',					
								));	
					
					}	
					## FIM DA VALIDAÇÃO DAS PECAS
					
					## VALIDAÇÃO PESO VIVO
					if(trim($pesovivo) < 0){
						array_push($xerro_notasen1, array(
							'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - PESO VIVO INFORMADO INCONSISTENTE!',					
						));	
					}					
					## FIM DA  VALIDAÇÃO PESO VIVO	
					
					## VALIDAÇÃO PESO CARCAÇA
					if(trim($pesocarcasa) < 001 and $tipo_r_v == 'R'){
					
						array_push($xerro_notasen1, array(
							'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - PESO DAS CARCACAS-> '.number_format(trim($pesocarcasa),3,',','.').' !',					
						));
					}
					## FIM DA  VALIDAÇÃO PESO CARCAÇA
					
					## VALIDAÇÃO PREÇO QUILO
					if(trim($precoquilo) < 0.01){
					
						array_push($xerro_notasen1, array(
							'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - '.$numero_nota1.' '.$precoquilo.' PRE€O POR QUILO INFORMADO INCONSISTENTE !',					
						));
					}
					## FIM DA VALIDAÇÃO PREÇO QUILO
					
					## VALIDAÇÃO RENDIMENTO OU VIVO
					if($tipo_r_v != 'R' and $tipo_r_v != 'V'){
						
						array_push($xerro_notasen1, array(
							'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - INDICADOR [V]IVO [R]ENDIMENTO INCONSISTENTE !',					
						));
					
					}
					## FIM DA VALIDAÇÃO RENDIMENTO OU VIVO	
					
					## VALIDAÇÃO DO PRODUTO NA SECRETARIA
					$vetpf = $daoprodfrig->VerificaProdutoProdFrig(trim($cod_produto),$_SESSION['cnpj']);
					$numpf = count($vetpf);
					
					if($numpf == 0){
						
						array_push($xerro_notasen1, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - PRODUTO/FRIGORIFICO->'.trim($cod_produto).' SEM RELACAO COM PRODUTO/SECRETARIA !',					
						));
					
					}else{
					
						$prodpf = $vetpf[0];
						
						$codprod	= $prodpf->getCodProd();
						$descprod	= $prodpf->getDescProd();
						$codsecret  = $prodpf->getCodSecretaria();
						$tipo		= $prodpf->getTipo();
						
						if($tipo == 'S'){
							
							array_push($xerro_notasen1, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> -CODIGO DO PRODUTO NO FRIGORIFICO '.trim($cod_produto).' '.$descprod.' RELACIONADO COM '.$codprod.' - '.$descprod.' DA SECRETARIA E SE REFERE A ENTRADAS/SAIDAS! NOTA => '.$numero_nota1.'',					
							));
						
						}
							
					}
					
						
					## FIM DA VALIDAÇÃO DO PRODUTO NA SECRETARIA	
						
						
					//echo "Linha #<b>{($line_num + 1)}</b> : ".$line."<br>\n";
				}
				
			}else{
			
				array_push($xerro_notasen1, array(
							'msg' => 'Falta o arquivo NOTASEN1 para importação!',					
				));
				
			}
			
			
			## COMEÇO DA VALIDAÇÃO DO ARQUIVO NOTASSAI.TXT
			$path_notassai   = "../arquivos/".$_SESSION['cnpj']."/NOTASSAI.TXT"; // pegando caminho do txt NOTASSAI
			$xerro_notassai  = array();
			
			if(file_exists($path_notassai)){
				
				$lines = file ('../arquivos/'.$_SESSION['cnpj'].'/NOTASSAI.TXT');
				
				foreach ($lines as $line_num => $line) {
					
					$line =  $line;
					
					$numero_notafisc = substr($line,0,6);
					$dtemissao		 = substr($line,6,8);
					$cnpj_cpf	     = substr($line,14,14);
					$cpf		     = substr($line,17,11);
					$valortotalnota  = substr($line,28,15);
					$valoricmsnormal = substr($line,43,15);
					$valoricmssubs	 = substr($line,58,15);
					$tipo_e_s		 = substr($line,73,1);
					$insc_est        = substr($line,74,14);
					$cfop		     = substr($line,88,5);					
					$indcpfcnpj      = "";
				
					## VALIDANDO CNPJ E CPF
					if(empty($cpf)){
						array_push($xerro_notassai, array(
							'msg' => 'FALTA INFORMAR O CPF',					
						));	
						
					}else{
					
						 $valid_cpf = $valid->Valida_Cnpj_Cpf($cpf);
						
						 if($valid_cpf == false){
							 
							 $indcpfcnpj = 0;		
							 $valid_cnpj_cpf = $valid->Valida_Cnpj_Cpf($cnpj_cpf);
							
							 if($valid_cnpj_cpf == false){
								$indcpfcnpj = 0;
															
							}else{
								$indcpfcnpj = 1;	
							}	
							
						}else{
							$indcpfcnpj = 1;
						}
							
							
						if($indcpfcnpj == 0){
							array_push($xerro_notassai, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - CNPJ OU CPF INVALIDO!',					
							));
						
						}	
							
					}
					
					$vetemp = $daoemp->VerificaEmpresasCadastradas(trim($cnpj_cpf),trim($cpf),trim($insc_est),$_SESSION['cnpj']);
					$numemp = count($vetemp);
					
					if($numemp == 0){
						
						array_push($xerro_notassai, array(
							'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - CNPJ/CPF/INSC [PRODUTOR] INEXISTE/CADASTRO DE EMPRESAS-> ('.$cnpj_cpf.' - '.$cpf.') INSC-> '.trim($insc_est).' !',					
						));
							
					}
										
					## FIM DA VALIDAÇÃO CNPJ E CPF
					
					## VALIDAÇÃO DO NUMERO DA NOTA 
					if(strlen(trim($numero_notafisc)) < 6){
							
						array_push($xerro_notassai, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - NUMERO DA NOTA-> '.$numero_notafisc.' !',					
						));	
					
					}	
					## FIM VALIDAÇÃO DO NUMERO DA NOTA 
					
					## VALIDAÇÃO DA DATA DE EMISSÃO 
					if(strlen(trim($dtemissao)) == 0 and $valid->ValidaData(date('d/m/Y',strtotime($dtemissao))) == false){
						array_push($xerro_notassai, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - DATA DE EMISSAO INVALIDA!',					
						));	
					}	
					
					## FIM VALIDAÇÃO DA DATA DE EMISSÃO
					
					## VALIDAÇÃO DO TOTA DA NOTA 
					if(trim($valortotalnota) < 0.01){
						
						array_push($xerro_notassai, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - VALOR TOTAL DA NOTA FISCAL-> '.number_format(trim($valortotalnota),2,',','.').' !',					
						));
					
					}
					## FIM DO TOTA DA NOTA 
					
					## VALIDAÇÃO DO VALOR ICMS NORMAL
					if(trim($valoricmsnormal) < 0.00){
							
						array_push($xerro_notassai, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - VALOR DO ICMS NORMAL-> '.number_format(trim($valoricmsnormal),2,',','.').' !',					
						));	
						
					}
					## FIM VALIDAÇÃO DO VALOR ICMS NORMAL
					
					## VALIDAÇÃO DO VALOR ICMS SUBSTUIÇÃO
					if(trim($valoricmssubs) < 0.00){
						
						array_push($xerro_notassai, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - VALOR DO ICMS SUBSTIT-> '.number_format(trim($valoricmssubs),2,',','.').' !',					
						));	
						
					}
					## FIM VALIDAÇÃO DO VALOR ICMS SUBSTUIÇÃO
					
					## VALIDAÇÃO ENTRADA E SAIDA
					if(trim($tipo_e_s) != "E" and trim($tipo_e_s) != "S"){
						array_push($xerro_notassai, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - INDICADOR DE [E]NTRADA [S]AIDA -> '.$tipo_e_s.' !',					
						));
						
					}
					## FIM VALIDAÇÃO ENTRADA E SAIDA
					
					
					$numnotasaitxt++;
					
				}
			}else{
				array_push($xerro_notassai, array(
							'msg' => 'Falta o arquivo NOTASSAI para importação!',					
				));
			}
			
						
			## COMEÇO DA VALIDAÇÃO DO ARQUIVO NOTASSA1.TXT
			$path_notassa1   = "../arquivos/".$_SESSION['cnpj']."/NOTASSA1.TXT"; // pegando caminho do txt NOTASSAI
			$xerro_notassa1  = array();
			
			if(file_exists($path_notassa1)){
				
				$lines = file ('../arquivos/'.$_SESSION['cnpj'].'/NOTASSA1.TXT');
				
				foreach ($lines as $line_num => $line) {
						
					$line =  $line;
					
					$numero_notasa1  = substr($line,0,6);
					$dtemissao		 = substr($line,6,8);
					$cnpj_cpf	     = substr($line,14,14);
					$cpf		     = substr($line,17,11);	
					$cod_produto     = substr($line,28,14);	
					$qtdpecas        = substr($line,42,16);
					$peso            = substr($line,57,16);
					$precounitario   = substr($line,72,15);
					$entsai			 = substr($line,87,1);
					$numitem		 = substr($line,88,3);
					$insc_est		 = substr($line,91,14);
					$cfop			 = substr($line,105,5);
					$aliguotaicms	 = substr($line,109,5);
					$indcpfcnpj      = "";
					
					## VALIDANDO CNPJ E CPF
					if(empty($cpf)){
						array_push($xerro_notassa1, array(
							'msg' => 'FALTA INFORMAR O CPF',					
						));	
						
					}else{
					
						 $valid_cpf = $valid->Valida_Cnpj_Cpf($cpf);
						
						 if($valid_cpf == false){
							 
							 $indcpfcnpj = 0;		
							 $valid_cnpj_cpf = $valid->Valida_Cnpj_Cpf($cnpj_cpf);
							
							 if($valid_cnpj_cpf == false){
								$indcpfcnpj = 0;
															
							}else{
								$indcpfcnpj = 1;	
							}	
							
						}else{
							$indcpfcnpj = 1;
						}
							
							
						if($indcpfcnpj == 0){
							array_push($xerro_notassa1, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - CNPJ OU CPF INVALIDO!',					
							));
						
						}	
							
					}	
					
					$vetemp = $daoemp->VerificaEmpresasCadastradas(trim($cnpj_cpf),trim($cpf),trim($insc_est),$_SESSION['cnpj']);
					$numemp = count($vetemp);
					
					if($numemp == 0){
						
						array_push($xerro_notassa1, array(
							'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - CNPJ/CPF/INSC [PRODUTOR] INEXISTE/CADASTRO DE EMPRESAS-> ('.$cnpj_cpf.' - '.$cpf.') INSC-> '.trim($insc_est).' !',					
						));
							
					}
									
					## FIM DA VALIDAÇÃO CNPJ E CPF
					
					## VALIDAÇÃO DO NUMERO DA NOTA 
					if(strlen(trim($numero_notasa1)) < 6){
							
						array_push($xerro_notassa1, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - NUMERO DA NOTA-> '.$numero_notasa1.' !',					
						));	
					
					}	
					## FIM VALIDAÇÃO DO NUMERO DA NOTA 
					
					## VALIDAÇÃO DA DATA DE EMISSÃO 
					if(strlen(trim($dtemissao)) == 0 and $valid->ValidaData(date('d/m/Y',strtotime($dtemissao))) == false){
						array_push($xerro_notassa1, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - DATA DE EMISSAO INVALIDA!',					
						));	
					}						
					## FIM VALIDAÇÃO DA DATA DE EMISSÃO
					
					## VALIDAÇÃO DE QUANTIDADE DE PEÇAS
					if(trim($qtdpecas) < 0){
						array_push($xerro_notassa1, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - QUANTIDADE DE PECAS INFORMADAS INCONSISTENTE!',					
						));
					
					}
					## FIM DA VALIDAÇÃO DE QUANTIDADE DE PEÇAS
					
					## VALIDAÇÃO DO PESO
					//echo trim($peso)."\n";
					if(floatval(trim($peso)) <= 0){
						array_push($xerro_notassa1, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - PESO INFORMADO INCONSISTENTE !',					
						));
						
					}
					## FIM DA VALIDAÇÃO DO PESO
					
					## VALIDAÇÃO DO PRECO UNITARIO
					if(trim($precounitario) < 00){
						
						array_push($xerro_notassa1, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - PRE€O POR QUILO INFORMADO INCONSISTENTE !',					
						));
					}
					## FIM DA VALIDAÇÃO DO PRECO UNITARIO
					
					## VALIDAÇÃO ENTRADA E SAIDA
					if(trim($entsai) != "E" and trim($entsai) != "S"){
						array_push($xerro_notassa1, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - INDICADOR DE [E]NTRADA [S]AIDA -> '.$entsai.' !',					
						));
						
					}
					## FIM VALIDAÇÃO ENTRADA E SAIDA
					
					
					## VALIDAÇÃO DO PRODUTO NA SECRETARIA
					$vetpf = $daoprodfrig->VerificaProdutoProdFrig(trim($cod_produto),$_SESSION['cnpj']);
					$numpf = count($vetpf);
					
					if($numpf == 0){
						
						array_push($xerro_notassa1, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> - PRODUTO/FRIGORIFICO->'.trim($cod_produto).' SEM RELACAO COM PRODUTO/SECRETARIA !',					
						));
					
					}else{
					
						$prodpf = $vetpf[0];
						
						$codprod	= $prodpf->getCodProd();
						$descprod	= $prodpf->getDescProd();
						$codsecret  = $prodpf->getCodSecretaria();
						$tipo		= $prodpf->getTipo();
						
						if($tipo == 'E'){
							
							array_push($xerro_notassa1, array(
								'msg' => 'Linha #<b>{'.($line_num + 1).'}</b> -CODIGO DO PRODUTO NO FRIGORIFICO '.trim($cod_produto).' '.$descprod.' RELACIONADO COM '.$codprod.' - '.$descprod.' DA SECRETARIA E SE REFERE A ENTRADAS/SAIDAS!',					
							));
						
						}
							
					}
					
						
					## FIM DA VALIDAÇÃO DO PRODUTO NA SECRETARIA
					
					
					
					//echo $aliguotaicms."<br/>";	
					//echo "Linha #<b>{($line_num + 1)}</b> : ".$line."<br>\n";	
				}
				
			}else{
				array_push($xerro_notassa1, array(
							'msg' => 'Falta o arquivo NOTASSA1 para importação!',					
				));
			}
			
			$daoprot = new ProtocoloDAO();
			$vetprot = $daoprot->ListaProtocoloPorEmmpresaCompetencia(date('m/Y',strtotime(implode("-", array_reverse(explode("/", "".$_REQUEST['dtini'].""))))),$_SESSION['cnpj']);
			$numprot = count($vetprot);

			if($numprot > 0){

					$prot   = $vetprot[0];
					
					$idprot = $prot->getCodigo();	

					$prots  = new Protocolo();

					$prots->setCodigo($idprot);
					$prots->setStatus(7);	

					$daoprot->updateStatus($prots);	

			}else{

				$prot = new Protocolo();
						
				$prot->setCompetencia(date('m/Y',strtotime(implode("-", array_reverse(explode("/", "".$_REQUEST['dtini'].""))))));
				$prot->setProtocolo('');
				$prot->setCripty('');
				$prot->setStatus(5);
				$prot->setCnpjEmp($_SESSION['cnpj']);	
				$prot->setTipoArq(2);

				$daoprot->inserir($prot);

			}

			$daofolha = new FolhaTxtDAO();
			$vetfolha = $daofolha->ValidaFolhaMes($_SESSION['cnpj'],$_SESSION['apura']['mesano']);	
			$numfolha = count($vetfolha);
				
			if($numfolha > 0){				
				
				$folha = $vetfolha[0];
				
				$codfolha         = $folha->getCodigo();
				$num_funcionarios = $folha->getNumFuncionario();
				$valor_folha 	  = $folha->getValorFolha();
				
				if(empty($num_funcionarios)){
					array_push($alert_nfunc,array(
						'id'=>''.$codfolha.'',
						'msg'=>'Falta informar o numero de funcionários!',
					));											
				}
				
				if(empty($valor_folha)){
					array_push($alert_vlfolha,array(
						'id'=>''.$codfolha.'',
						'msg'=>'Falta informar valor da folha!',
					));										
				}								
			}else{
				
				array_push($alert_nfunc,array(
						'id'=>'',
						'msg'=>'Falta informar o numero de funcionários!',
				));
				
				array_push($alert_vlfolha,array(
						'id'=>'',
						'msg'=>'Falta informar valor da folha!',
				));
			}

			$daoguia 	 =  new GuiaicmsDAO();
			$vetguianorm = $daoguia->ValidGuiaicmsNormal($_SESSION['cnpj'],$_SESSION['apura']['mesano']);	
			$numguianorm = count($vetguianorm);	
				
			if($numguianorm == 0){
				
				array_push($alert_icmsnorm,array(
					'msg'=>'Valor do ICMS normal não informado !',							
				));
			}	
				
			$vetguiast = $daoguia->ValidGuiaicmsSt($_SESSION['cnpj'],$_SESSION['apura']['mesano']);
			$numguiast = count($vetguiast);	
			
			if($numguiast == 0){
				
				array_push($alert_icmsst,array(
					'msg'=>'Valor do ICMS ST não informado !',
				));
			}
			/*echo "<pre>";
			print_r($xerro_notasent);	
			print_r($xerro_prodfrig);	
			print_r($xerro_emp);
			print_r($xerro_notasen1);
			print_r($xerro_notassai);
			print_r($xerro_notassa1);*/
			

			$num_tota_erros = count($xerro_notasent) + count($xerro_prodfrig) + count($xerro_emp) + count($xerro_notasen1) + count($xerro_notassai) + count($xerro_notassa1);
			
			$data = array('xerro_notasent'=>$xerro_notasent,
						  'xerro_prodfrig'=>$xerro_prodfrig,
						  'xerro_emp'=>$xerro_emp,
						  'xerro_notasen1'=>$xerro_notasen1,
						  'xerro_notassai'=>$xerro_notassai,
						  'xerro_notassa1'=>$xerro_notassa1, 	
						  'num_tota_erros'=>$num_tota_erros,
						  'dtini'=>''.$_REQUEST['dtini'].'',
						  'dtfim'=>''.$_REQUEST['dtfim'].'',
						  'info'=>array(
							'funcionario'=>$alert_nfunc,
							'folha'=>$alert_vlfolha,
							'icmsnormal'=>$alert_icmsnorm,
							'icmsst'=>$alert_icmsst,
							'gta'=>$alert_gta,
							'num_entrada'=>$numnotasenttxt,
							'num_saida'=>$numnotasaitxt
						),);
						
			//print_r($data);	
			echo json_encode($data);
			
		break;
		case 'gravadados':
			//ini_set('MAX_EXECUTION_TIME', '-1');
			error_reporting(E_ALL);
			ini_set('display_errors', 'On');
			$dtini     = implode("-", array_reverse(explode("/", "".$_REQUEST['dtini']."")));
			$dtfim     = implode("-", array_reverse(explode("/", "".$_REQUEST['dtfim']."")));
			$indicador = empty($_REQUEST['indicador']) ? '0' : '1';
			$result    = array();
			
			$condicao     = array();
			$condicao[]   = " n.data_abate between '".$dtini."' ";
			$condicao[]   = " '".$dtfim."' ";
			$condicao[]   = " n.cnpj_emp = '".$_SESSION['cnpj']."' ";
						
			$where = '';
			if(count($condicao) > 0){			
				$where = ' where'.implode('AND',$condicao);					
			}
				
			
			$daonotasen1 = new NotasEn1TxtDAO();
			$vetnotasen1 = $daonotasen1->VerificaSeExisteAbate($where);
			$numnotasen1 = count($vetnotasen1); 			
			
			if($numnotasen1 > 0){
										
				// EXISTEM REGITROS ENTRE ESSAS DATAS DE ABATE		
			
				if($indicador == 0){					
					
					array_push($result, array(
							'msg'  => 'Existem Registros ja populados entre essas datas deseja gerar novamente ?',
							'tipo' => '1',					
					));
				
				}else{
					
					// AQUI EXCLUIR OS DADOS ENTRE ESSAS DATAS E INSERIR NOVAMENTE
					
					##DELETANDO REGISTRO NOTASENT
					$daonotasent = new NotasEntTxtDAO();
					$vetnotasent = $daonotasent->PegaRestristroParaExclusao($dtini,$dtfim,$_SESSION['cnpj']); 
					$numnotasent = count($vetnotasent);
					
					for($i = 0; $i < $numnotasent; $i++){
					
						$notasent = $vetnotasent[$i];
						
						$id_notasent = $notasent->getCodigo();
						
						
						$notasents = new NotasEntTxt();
						
						$notasents->setCodigo($id_notasent);
						$notasents->setCnpjEmp($_SESSION['cnpj']);
						
						$daonotasent->deletar($notasents);
						
					
					}
					##FIM DE DELETANDO REGISTRO NOTASENT
					//echo "passo";
					##DELETANDO REGISTRO NOTASEN1
					$daonotasen1  = new NotasEn1TxtDAO();
					$vetnotasen1  = $daonotasen1->PegaRegistroParaExclusao($dtini,$dtfim,$_SESSION['cnpj']);
					$numtnotasen1 = count($vetnotasen1);
					
					for($x = 0; $x < $numtnotasen1; $x++){
					
						$notasen1 = $vetnotasen1[$x];
						
						$id_notasen1  = $notasen1->getCodigo();
						
						$notasen1s = new NotasEn1Txt();
						
						$notasen1s->setCodigo($id_notasen1);
						$notasen1s->setCnpjEmp($_SESSION['cnpj']);						
						
						$daonotasen1->deletar($notasen1s);
						
					}
					##FIM DE DELETANDO REGISTRO NOTASEN1										
					//echo "passo2";
					##DELETANDO REGISTRO NOTASSAI
					$daonotasai = new NotasSaiTxtDAO();
					$vetnotasai = $daonotasai->PegaRestristroParaExclusao($dtini,$dtfim,$_SESSION['cnpj']);
					$numnotasai = count($vetnotasai);
					
					for($y = 0; $y < $numnotasai; $y++){
					
						$notasai = $vetnotasai[$y];	
						
						$id_notasai = $notasai->getCodigo();
						
						$notasais = new NotasSaiTxt();
						
						$notasais->setCodigo($id_notasai);
						$notasais->setCnpjEmp($_SESSION['cnpj']);
						
						$daonotasai->deletar($notasais); 
												
					}
					##FIM DE DELETANDO REGISTRO NOTASSAI										
					//echo "passo3";
					##DELETANDO REGISTRO NOTASSA1
					$daonotassa1 = new NotasSa1TxtDAO();
					$vetnotassa1 = $daonotassa1->PegaRestristroParaExclusao($dtini,$dtfim,$_SESSION['cnpj']); 
					$numnotassa1 = count($vetnotassa1); 
					
					for($s = 0; $s < $numnotassa1; $s++){
					
						$notassa1 = $vetnotassa1[$s];
						
						$id_notasa1 = $notassa1->getCodigo();
						
						$notasa1s = new NotasSa1Txt();
						
						$notasa1s->setCodigo($id_notasa1);
						$notasa1s->setCnpjEmp($_SESSION['cnpj']);
						
						$daonotassa1->deletar($notasa1s);
						
					}
					
					##FIM DE DELETANDO REGISTRO NOTASSA1
					
					//echo "passo4";
					
					array_push($result, array(
							'msg'  => 'Registros Alterados com sucesso !',
							'tipo' => '2',					
					));
					
				}
				
			
			}else{

				$pathFile      = '../arquivos/'.$_SESSION['cnpj'].'/config.json';
				$configJson    = file_get_contents($pathFile);
				$installConfig = json_decode($configJson);
				
				/*$db = new MysqliDb(Array (
					'host' => '162.214.92.178',
					'username' => 'agregarc_agregar', 
					'password' => 'Pr0d@5Iq',
					'db'=> 'agregarc_agregar',
					'port' => 3306,
					'prefix' => '',
					'charset' => 'utf8'));*/

					$db = new MysqliDb(Array (
						'host' => 'localhost',
						'username' => 'root', 
						'password' => '',
						'db'=> 'agregar',
						'port' => 3306,
						'prefix' => '',
						'charset' => 'utf8'));
				$db->setTrace (true);	
				$daofunc   = new FuncoesDAO();
				##ARQUIVO EMPRESAS.TXT
				$path_empresa = "../arquivos/".$_SESSION['cnpj']."/EMPRESAS.TXT"; // pegando caminho do txt empresas
				if(file_exists($path_empresa)){
										

					$lines   = file ('../arquivos/'.$_SESSION['cnpj'].'/EMPRESAS.TXT');					
					$daoemp	 = new EmpresasTxtDAO();

					foreach ($lines as $line_num => $line) {

						$line 		   = $line;
						$cnpj_cpf 	   = substr($line,0,14);
						$cpf 		   = substr($line,3,11);
						$indcnpjcpfemp2= substr($line,11,3);
						$indcnpjcpfemp = substr($line,0,3); 					
						$ie		  	   = substr($line,14,13);
						$razaosocial   = str_replace("'", "", str_replace('"', "", substr($line,27,39)));				
						$cidade		   = substr($line,67,35);
						$uf			   = substr($line,103,2);
						$tipo		   = substr($line,105,1);
						
						$vetemp = $daoemp->VerificaEmpresasCadastradas(trim($cnpj_cpf),trim($cpf),trim($ie),$_SESSION['cnpj']);
						$numemp = count($vetemp);
						
						if($numemp == 0){
														
							if(trim($cnpj_cpf) != ''){
								if(!$daofunc->valida_cnpj(trim($cnpj_cpf))){
									if($daofunc->ValidaCPF(trim($cnpj_cpf))){
										$cnpj_cpf = $cnpj_cpf;									
									}else{
										
										if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
											$cnpj_cpf = substr($cnpj_cpf,3,11);		
											
										}else{
											$cnpj_cpf = substr($cnpj_cpf,3,11);
											
										}
										
									}
									
								}else{
									
									if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
										$cnpj_cpf = substr($cnpj_cpf,3,11);									
									}else{
										$cnpj_cpf = $cnpj_cpf;
									}
									
								}								
							}

							

							$emp = new EmpresasTxt();	
							
							$emp->setCnpjCpf(trim($cnpj_cpf));
							$emp->setInscEstadual(trim($ie));
							$emp->setRazao(trim($razaosocial));
							$emp->setCidade(trim($cidade));
							$emp->setUf($uf);
							$emp->setTipo($tipo);
							$emp->setCnpjEmp($_SESSION['cnpj']);							
							$daoemp->inserir($emp);

						}else{
							$xemp = $vetemp[0];
							
							$cod  = $xemp->getCodigo();
							
							if(trim($cnpj_cpf) != ''){
								if(!$daofunc->valida_cnpj(trim($cnpj_cpf))){
									if($daofunc->ValidaCPF(trim($cnpj_cpf))){
										$cnpj_cpf = $cnpj_cpf;									
									}else{
										
										if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
											$cnpj_cpf = substr($cnpj_cpf,3,11);		
											
										}else{
											$cnpj_cpf = substr($cnpj_cpf,3,11);
											
										}
										
									}
									
								}else{
									
									if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
										$cnpj_cpf = substr($cnpj_cpf,3,11);									
									}else{
										$cnpj_cpf = $cnpj_cpf;
									}
									
								}								
							}

							$emp  = new EmpresasTxt();	
							
							$emp->setCodigo($cod);
							$emp->setCnpjCpf(trim($cnpj_cpf));
							$emp->setInscEstadual(trim($ie));
							$emp->setRazao($razaosocial);
							$emp->setCidade($cidade);
							$emp->setUf($uf);
							$emp->setTipo($tipo);
							$emp->setCnpjEmp($_SESSION['cnpj']);							
							$daoemp->update($emp);	

						}
						
						
					}

				}
				
				$db->where ('cnpj_emp', ''.$_SESSION['cnpj'].'');	
				$empresastxt = $db->get('empresastxt');
				
				##ARQUIVO PRODFRIG.TXT
				$path_prodfrig   = "../arquivos/".$_SESSION['cnpj']."/PRODFRIG.TXT"; // pegando caminho do txt PRODFRIG
				$daoprodfrig     = new ProdFrigTxtDAO();
				$daopr 			 = new ProdutosTxtDAO();
				if(file_exists($path_prodfrig)){

					$lines = file ('../arquivos/'.$_SESSION['cnpj'].'/PRODFRIG.TXT');
					foreach ($lines as $line_num => $line) {
						$line = $line;
					
						$codigo_produto     = substr($line,0,14);
						$descricao_produto  = substr($line,14,40);
						$codprodsecreataria = substr($line,54,68);


						$vetprodfrig =  $daoprodfrig->VerificaProduto(trim($codigo_produto),$_SESSION['cnpj']);
						$numprodfrig = count($vetprodfrig);
						
						if($numprodfrig == 0){
							
							$prodfrig = new ProdFrigTxt();

							$prodfrig->setCodProd(trim($codigo_produto));
							$prodfrig->setDescProd(str_replace("'", "", str_replace('"', "", trim($descricao_produto))));
							$prodfrig->setCodSecretaria(trim($codprodsecreataria));
							$prodfrig->setCnpjEmp($_SESSION['cnpj']);							
							$daoprodfrig->inserir($prodfrig);
							
						}else{
							$prodfrigs = $vetprodfrig[0];

							$codps = $prodfrigs->getCodigo();

							$prodfrig = new ProdFrigTxt();


							$prodfrig->setCodigo($codps);
							$prodfrig->setCodProd(trim($codigo_produto));
							$prodfrig->setDescProd(str_replace("'", "", str_replace('"', "", trim($descricao_produto))));
							$prodfrig->setCodSecretaria(trim($codprodsecreataria));
							$prodfrig->setCnpjEmp($_SESSION['cnpj']);

							$daoprodfrig->update($prodfrig);
							
						}


						$vetpr = $daopr->VerificaProdutoTxt(trim($codigo_produto),$_SESSION['cnpj']);
						$numpr = count($vetpr);

						if($numpr == 0){
							
							$prodtxt = new ProdutosTxt();
								
							$prodtxt->setCodProd(trim($codigo_produto));
							$prodtxt->setDescProd(str_replace("'", "", str_replace('"', "", trim($descricao_produto))));
							$prodtxt->setCnpjEmp($_SESSION['cnpj']);

							$daopr->inserir($prodtxt);

						}else{
							$codptxt = $vetpr[0]->getCodigo();
							$prodtxt = new ProdutosTxt();

							$prodtxt->setCodigo($codptxt);								
							$prodtxt->setCodProd(trim($codigo_produto));
							$prodtxt->setDescProd(str_replace("'", "", str_replace('"', "", trim($descricao_produto))));
							$prodtxt->setCnpjEmp($_SESSION['cnpj']);

							$daopr->update($prodtxt);

						}

					}

				}
				
				

				
				##ARQUIVO NOTASENT.TXT
				$path_notasent   = "../arquivos/".$_SESSION['cnpj']."/NOTASENT.TXT"; // pegando caminho do txt NOTASENT			
				$lines 			 = file('../arquivos/'.$_SESSION['cnpj'].'/NOTASENT.TXT');
				$datanotasent    = array();
				$datagta		 = array();
				foreach ($lines as $line_num => $line) {
					
					$line =  $line;
					
					if($_SESSION['cnpj'] != '88728027000146'){

						$numero_nota   			  = substr($line,0,6);
						$emissao	   			  = substr($line,6,8);
						$cnpj_cpf      			  = substr($line,14,14);								
						$tipo_v_t_a    			  = substr($line,28,1);
						$valortotanota 			  = substr($line,29,15);
						$gta		  			  = substr($line,44,6);
						$numero_nota_produtor_ini = substr($line,50,6);
						$numero_nota_produtor_fim = substr($line,56,6);
						$tipo_s_n				  = substr($line,62,1);
						$tipo_p_t				  = substr($line,63,1);
						$ie_produtor			  =	substr($line,64,14);

					}else{
						
						$numero_nota   			  = substr($line,0,9);
						$emissao	   			  = substr($line,9,8);
						$cnpj_cpf      			  = substr($line,17,14);					
						$tipo_v_t_a    			  = substr($line,31,1);

						if(preg_match("/^(\d+)?\.\d+$/", substr($line,32,15))) {
							$valortotanota 			  = substr($line,32,15);
						}else{
							$valortotanota 			  = str_replace('.','',(substr($line,32,15)/1000));
						}
						
						$gta		  			  = substr($line,47,6);
						$numero_nota_produtor_ini = substr($line,53,6);
						$numero_nota_produtor_fim = substr($line,59,6);
						$tipo_s_n				  = substr($line,65,1);
						$tipo_p_t				  = substr($line,66,1);
						$ie_produtor			  =	substr($line,67,14);

					}	

					if(trim($cnpj_cpf) != ''){
						if(!$daofunc->valida_cnpj(trim($cnpj_cpf))){
							if($daofunc->ValidaCPF(trim($cnpj_cpf))){
								$cnpj_cpf = $cnpj_cpf;									
							}else{
								
								if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
									$cnpj_cpf = substr($cnpj_cpf,3,11);		
									
								}else{
									$cnpj_cpf = substr($cnpj_cpf,3,11);
									
								}
								
							}
							
						}else{
							
							if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
								$cnpj_cpf = substr($cnpj_cpf,3,11);									
							}else{
								$cnpj_cpf = $cnpj_cpf;
							}
							
						}								
					}

					if(!empty($empresastxt)){
						$key = array_search(trim($cnpj_cpf),array_column($empresastxt, 'cnpj_cpf'));
						if(trim($key) != ''){
							$ie_produtor = $empresastxt[$key]['insc_estadual'];		
						}
					}

					array_push($datanotasent,array(
						trim($numero_nota),
						date('Y-m-d',strtotime($emissao)),
						trim($cnpj_cpf),
						trim($tipo_v_t_a),
						trim($valortotanota),
						trim($gta),
						trim($numero_nota_produtor_ini),
						trim($numero_nota_produtor_fim),
						trim($tipo_s_n),
						trim($tipo_p_t),
						trim($ie_produtor),
						$_SESSION['cnpj']
					));
					
					

					if(!empty(trim($gta))){

						$daogta = new GtaTxtDAO();
						$vetgta = $daogta->GtaEmpresas($numero_nota,$_SESSION['cnpj']);
						$numgta = count($vetgta);
							
						if($numgta == 0){
							
							$setgta = new GtaTxt();
						
							$setgta->setNumeroNota(trim($numero_nota));
							$setgta->setDataEmissao(date('Y-m-d',strtotime($emissao)));		
							$setgta->setNumeroGta(trim($gta));						
							$setgta->setCnpjEmp($_SESSION['cnpj']);

							
							$daogta->inserirgta($setgta);
						}
						
					}
					
				}			
							
				$datanotasent_flip = array_map('json_encode', $datanotasent);
				$datanotasent_flip = array_unique($datanotasent_flip);
				$datanotasent_flip = array_map('json_decode', $datanotasent_flip);

				$keysnotasent = Array(
					"numero_nota", 
					"data_emissao", 
					"cnpj_cpf",
					"tipo_v_r_a",
					"valor_total_nota",
					"gta",
					"numero_nota_produtor_ini",
					"numero_nota_produtor_fin",
					"condenas",
					"abate",
					"insc_produtor",
					"cnpj_emp"
					);

				$db->insertMulti('notasenttxt', $datanotasent_flip, $keysnotasent);
				
				##FIM DE ARQUIVO NOTASENT.TXT	
				
				##ARQUIVO NOTASEN1.TXT
				$path_notasen1   = "../arquivos/".$_SESSION['cnpj']."/NOTASEN1.TXT"; // pegando caminho do txt NOTASEN1			
				$lines 			 = file('../arquivos/'.$_SESSION['cnpj'].'/NOTASEN1.TXT');
				$datanotasen1    = array();	
				foreach ($lines as $line_num => $line) {
					
					$line =  $line;
					if($_SESSION['cnpj'] != '88728027000146'){
						$numero_nota1 = substr($line,0,6);
						$emissao1	  = substr($line,6,8);
						$cnpj_cpf1	  = substr($line,14,14);					
						$cod_produto  = substr($line,28,13);
						$qtdcabecas	  = substr($line,42,5);
						$pesovivo	  = substr($line,47,15);
						$pesocarcasa  = substr($line,62,15);
						$precoquilo   = substr($line,77,15);
						$numero_item  = substr($line,92,3);	
						$ie1		  = substr($line,95,14);		
						$dataabate	  =	substr($line,109,8);
						$tipo_r_v	  = substr($line,117,1);
						$cfop		  = !empty(trim(substr($line,118,4))) ? trim(substr($line,118,4)) : '1101';	
						$aliquotaicms = !empty(substr($line,122,6)) ? substr($line,122,6) : 0;
					}else{
						$numero_nota1 = substr($line,0,9);
						$emissao1	  = substr($line,9,8);
						$cnpj_cpf1	  = substr($line,17,14);					
						$cod_produto  = substr($line,31,14);
						$qtdcabecas	  = substr($line,45,5);
						$pesovivo	  = substr($line,50,15);
						$pesocarcasa  = substr($line,65,15);
						$precoquilo   = substr($line,80,15);
						$numero_item  = substr($line,95,3);	
						$ie1		  = substr($line,98,14);	
						
						if(!empty($installConfig->abatetxt)){							
							if($installConfig->abatetxt == '1'){								
								$dataabate	  =	!empty(trim(substr($line,112,8))) ? date('Y-m-d',strtotime(trim(substr($line,112,8)))) : date('Y-m-d');
							}else{
								$dataabate	  =	!empty(trim(substr($line,112,8))) ? date('Y-m-d',strtotime(trim(substr($line,112,8)))) : 'null';	
							}							
						}else{
							$dataabate	  =	!empty(trim(substr($line,112,8))) ? date('Y-m-d',strtotime(trim(substr($line,112,8)))) : 'null';
						}
						
						if(empty($installConfig->notas->vivorendtxt)){
							$tipo_r_v	  = !empty(trim(substr($line,120,1))) ? trim(substr($line,120,1)) : '';
						}else{
							$tipo_r_v	  = !empty(trim(substr($line,120,1))) ? trim(substr($line,120,1)) : ''.$installConfig->notas->vivorendtxt.'';
						}
						
						
						$cfop		  = !empty(trim(substr($line,121,4))) ? trim(substr($line,121,4)) : '1101';	
						$aliquotaicms = !empty(substr($line,125,6)) ? substr($line,125,6) : 0;
					}

					if(trim($cnpj_cpf1) != ''){
						if(!$daofunc->valida_cnpj(trim($cnpj_cpf1))){
							if($daofunc->ValidaCPF(trim($cnpj_cpf1))){
								$cnpj_cpf1 = $cnpj_cpf1;									
							}else{
								
								if($daofunc->ValidaCPF(trim(substr($cnpj_cpf1,3,11)))){
									$cnpj_cpf1 = substr($cnpj_cpf1,3,11);		
									
								}else{
									$cnpj_cpf1 = substr($cnpj_cpf1,3,11);
									
								}
								
							}
							
						}else{
							
							if($daofunc->ValidaCPF(trim(substr($cnpj_cpf1,3,11)))){
								$cnpj_cpf1 = substr($cnpj_cpf1,3,11);									
							}else{
								$cnpj_cpf1 = $cnpj_cpf1;
							}
							
						}								
					}

					if(!empty($empresastxt)){
						$key = array_search(trim($cnpj_cpf1),array_column($empresastxt, 'cnpj_cpf'));
						if(trim($key) != ''){
							$ie1 = $empresastxt[$key]['insc_estadual'];		
						}
					}

					array_push($datanotasen1,array(
						trim($numero_nota1),
						date('Y-m-d',strtotime($emissao1)),
						trim($cnpj_cpf1),
						trim($cod_produto),
						trim(str_replace(',', '.', $qtdcabecas)),
						trim($pesovivo),
						trim($pesocarcasa),
						trim($precoquilo),
						trim($numero_item),
						trim($ie1),
						$dataabate,
						trim($tipo_r_v),
						trim($cfop),
						trim($aliquotaicms),
						$_SESSION['cnpj'],
						trim($pesovivo)
					));

					
					
				}

				$datanotasen1_flip = array_map('json_encode', $datanotasen1);
				$datanotasen1_flip = array_unique($datanotasen1_flip);
				$datanotasen1_flip = array_map('json_decode', $datanotasen1_flip);	


				$keysnotasen1 = Array(
					"numero_nota",
					"data_emissao",
					"cnpj_cpf",
					"codigo_produto",
					"qtd_cabeca",
					"peso_vivo_cabeca",
					"peso_carcasa",
					"preco_quilo",
					"numero_item_nota",
					"insc_estadual",
					"data_abate",
					"tipo_r_v",
					"cfop",
					"aliquota_icms",
					"cnpj_emp",
					"qtd_prod");
				
				$db->insertMulti('notasen1txt', $datanotasen1_flip, $keysnotasen1);


				##FIM DE ARQUIVO NOTASEN1.TXT	
			
				
				##ARQUIVO NOTASSAI.TXT
				$path_notassai   = "../arquivos/".$_SESSION['cnpj']."/NOTASSAI.TXT"; // pegando caminho do txt NOTASSAI			
				$lines 			 = file('../arquivos/'.$_SESSION['cnpj'].'/NOTASSAI.TXT');
				$datanotassai    = array();
				foreach ($lines as $line_num => $line) {
					
					$line =  $line;

					if($_SESSION['cnpj'] != '88728027000146'){
						$numero_notafisc = substr($line,0,6);
						$dtemissao		 = substr($line,6,8);
						$cnpj_cpf	     = substr($line,14,14);					
						$valortotalnota  = substr($line,28,15);
						$valoricmsnormal = substr($line,43,15);
						$valoricmssubs	 = substr($line,58,15);
						$tipo_e_s		 = substr($line,73,1);
						$insc_est        = substr($line,74,14);
						$cfop		     = substr($line,88,4);					
					}else{
						$numero_notafisc = substr($line,0,9);
						$dtemissao		 = substr($line,9,8);
						$cnpj_cpf	     = substr($line,17,14);					
						$valortotalnota  = substr($line,31,15);
						$valoricmsnormal = substr($line,46,15);
						$valoricmssubs	 = substr($line,61,15);
						$tipo_e_s		 = substr($line,76,1);
						$insc_est        = substr($line,77,14);
						$cfop		     = substr($line,90,4);
					}

					if($cfop == '1101'){
						continue;
					}
							
							
						
						
					if(trim($cnpj_cpf) != ''){
						if(!$daofunc->valida_cnpj(trim($cnpj_cpf))){
							if($daofunc->ValidaCPF(trim($cnpj_cpf))){
								$cnpj_cpf = $cnpj_cpf;									
							}else{
								
								if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
									$cnpj_cpf = substr($cnpj_cpf,3,11);		
									
								}else{
									$cnpj_cpf = substr($cnpj_cpf,3,11);
									
								}
								
							}
							
						}else{
							
							if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
								$cnpj_cpf = substr($cnpj_cpf,3,11);									
							}else{
								$cnpj_cpf = $cnpj_cpf;
							}
							
						}								
					}

					if(!empty($empresastxt)){
						$key = array_search(trim($cnpj_cpf),array_column($empresastxt, 'cnpj_cpf'));
						if(trim($key) != ''){
							$insc_est = $empresastxt[$key]['insc_estadual'];
							$ufcli    = $empresastxt[$key]['uf'];	
						}
					}
						
					if(!empty($ufcli)){
						if(trim(strtoupper($ufcli)) == 'RS'){
							if(empty(trim($cfop))){
								$cfop = '5401';
							}
						}else if(trim(strtoupper($ufcli)) != 'RS'){
							if(empty(trim($cfop))){
								$cfop = '6101';
							}
						}	
					}
					
					array_push($datanotassai,array(
						trim($numero_notafisc),
						date('Y-m-d',strtotime($dtemissao)),
						trim($cnpj_cpf),
						trim($valortotalnota),
						trim($valoricmsnormal),
						trim($valoricmssubs),
						trim($tipo_e_s),
						trim($insc_est),
						trim($cfop),
						$_SESSION['cnpj']
					));

				
					
				}	
				
				$datasai_flip 	   = array_map('json_encode', $datanotassai);
				$datanotassai_flip = array_unique($datasai_flip);
				$datanotassai_flip = array_map('json_decode', $datanotassai_flip);				
								
				$keysnotassai = Array(
					"numero_nota",
					"data_emissao",
					"cnpj_cpf",
					"valor_total_nota",
					"valor_icms",
					"valor_icms_subs",
					"ent_sai",
					"insc_estadual",
					"cfop",
					"cnpj_emp");

				$db->insertMulti('notassaitxt', $datanotassai_flip, $keysnotassai);	
				//print_r ($db->trace);
				##FIM DE ARQUIVO NOTASSAI.TXT
					
				## ARQUIVO NOTASSA1.TXT									
				$path_notassa1   = "../arquivos/".$_SESSION['cnpj']."/NOTASSA1.TXT"; // pegando caminho do txt NOTASSAI		
				$lines 			 = file ('../arquivos/'.$_SESSION['cnpj'].'/NOTASSA1.TXT');
				$data  			 = array();
				foreach ($lines as $line_num => $line) {
						
					$line =  $line;
					if($_SESSION['cnpj'] != '88728027000146'){
						$numero_notasa1  = substr($line,0,6);
						$dtemissao		 = substr($line,6,8);
						$cnpj_cpf	     = substr($line,14,14);
						$cod_produto     = substr($line,28,14);	
						$qtdpecas        = substr($line,42,15);
						$peso            = substr($line,57,15);
						$precounitario   = substr($line,72,15);
						$entsai			 = substr($line,87,1);
						$numitem		 = substr($line,88,3);
						$insc_est		 = substr($line,91,14);
						$cfop			 = substr($line,105,4);
						$aliguotaicms	 = substr($line,109,6);
					}else{

						$numero_notasa1  = substr($line,0,9);
						$dtemissao		 = substr($line,9,8);
						$cnpj_cpf	     = substr($line,17,14);					
						$cod_produto     = substr($line,31,14);	
						$qtdpecas        = substr($line,45,15);
						$peso            = substr($line,60,15);
						$precounitario   = substr($line,75,15);
						$entsai			 = substr($line,90,1);
						$numitem		 = substr($line,91,3);
						$insc_est		 = substr($line,94,14);
						$cfop			 = substr($line,108,4);
						$aliguotaicms	 = substr($line,112,6);
					}
					if($cfop == '1101'){
						continue;
					}

					if(trim($cnpj_cpf) != ''){
						if(!$daofunc->valida_cnpj(trim($cnpj_cpf))){
							if($daofunc->ValidaCPF(trim($cnpj_cpf))){
								$cnpj_cpf = $cnpj_cpf;									
							}else{
								
								if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
									$cnpj_cpf = substr($cnpj_cpf,3,11);		
									
								}else{
									$cnpj_cpf = substr($cnpj_cpf,3,11);
									
								}
								
							}
							
						}else{
							
							if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
								$cnpj_cpf = substr($cnpj_cpf,3,11);									
							}else{
								$cnpj_cpf = $cnpj_cpf;
							}
							
						}								
					}

					if(!empty($empresastxt)){
						$key = array_search(trim($cnpj_cpf),array_column($empresastxt, 'cnpj_cpf'));
						if(trim($key) !=''){
							$insc_est = $empresastxt[$key]['insc_estadual'];
							$ufcli    = $empresastxt[$key]['uf'];							
						}
					}

					if(!empty($ufcli)){
						if(trim(strtoupper($ufcli)) == 'RS'){
							if(empty(trim($cfop))){
								$cfop = '5401';
							}
						}else if(trim(strtoupper($ufcli)) != 'RS'){
							if(empty(trim($cfop))){
								$cfop = '6101';
							}
						}	
					}
	
					array_push($data,array(
						trim($numero_notasa1),
						date('Y-m-d',strtotime($dtemissao)),
						trim($cnpj_cpf),
						trim($cod_produto),
						trim(str_replace(',', '.', $qtdpecas)),
						trim($peso),
						trim($precounitario),
						trim($peso),
						trim($entsai),
						trim($numitem),
						trim($insc_est),
						trim($cfop),
						trim($aliguotaicms),
						trim($_SESSION['cnpj'])
					));
					
															
				}

				$data_flip = array_map('json_encode', $data);
				$data_flip = array_unique($data_flip);
				$data_flip = array_map('json_decode', $data_flip);

				$keys = Array(
					"numero_nota", 
					"data_emissao", 
					"cnpj_cpf",
					"codigo_produto",
					"qtd_pecas",
					"peso",
					"preco_unitario",
					"qtd_prod",
					"ent_sai",
					"numero_item_nota",
					"insc_estadual",
					"cfop",
					"aliquota_icms",
					"cnpj_emp");

				$ids = $db->insertMulti('notassa1txt', $data_flip, $keys);

				##FIM DO ARQUIVO NOTASEN1.TXT
				
				array_push($result, array(
							'msg'  => 'Registros Inseridos com sucesso!',
							'tipo' => '3',					
					));
				
			}//FIM DO IF SE EXSTEM ARQUIVOS ENTRE DATAS ABATEIDOS
			
			
			
			echo json_encode($result);
			
		break;
		case 'valida_novamente':

			$mesano         = !empty($_REQUEST['mesano']) ? $_REQUEST['mesano'] : $_SESSION['apura']['mesano'];
			$_SESSION['apura']['mesano'] = $mesano;
			$alert_nfunc    = array();
			$alert_vlfolha  = array();	
			$alert_icmsnorm = array();	
			$alert_icmsst   = array();	
			$alert_gta      = array();			
			$alert_dataabate= array();
			$alert_emp		= array();
			
			$erro           = array();
			$dados          = array();
			$err_cfop       = array();
			$err_produto    = array();	
			$err_nota       = array();
			$err_v_r	    = array();

			$xerro_notasent = array();
			$xerro_notassa1 = array();
			$xerro_notasen1 = array();
			$xerro_notassai = array();
			$err_dataabate 	= array();

			$numnotasenttxt = 0;
			$numnotasaitxt  = 0;
			
			$xerro_emp	  = array(); 
			
			$pathFile      = '../arquivos/'.$_SESSION['cnpj'].'/config.json';
			$configJson    = file_get_contents($pathFile);
			$installConfig = json_decode($configJson);

			$valid		  = new FuncoesDAO();
			$daop		  = new ProdutosAgregarDAO();
			$daoemp	      = new EmpresasTxtDAO();
			$daoprodfrig  = new ProdFrigTxt2DAO(); 
			$daocf  	  = new CfopDAO();
			$daocfopemp   = new CfopEmpresa2DAO();
			$daogta		  = new GtaTxt2DAO();
			$daoemp2      = new EmpresasTxt2DAO();
			
			$resuprod    = $daoprodfrig->ListaRelacionadoTodos($_SESSION['cnpj']);				
			$results     = $daocfopemp->ListaCfopGeraAgregarSN($_SESSION['cnpj']);				
			$resultscfop = $daocfopemp->ListaCfopEmpresas($_SESSION['cnpj']);
			$resultgta   = $daogta->GtaEmpresas($_SESSION['cnpj']);
			$resultsemp  = $daoemp2->InconsistenciaCadastrodeEmpresas($_SESSION['cnpj']);
			
			## COMEÇO DA VALIDAÇÃO DE EMPRESAS			
			if($resultsemp){				
				for ($i=0; $i < count($resultsemp); $i++) { 

					$idemp 		   = $resultsemp[$i]['id'];
					$cnpj_cpf_emp	   = $resultsemp[$i]['cnpj_cpf'];
					$insc_estadual_emp = $resultsemp[$i]['insc_estadual'];
					$razao_emp  	   = !empty(trim($resultsemp[$i]['razao'])) ? trim($resultsemp[$i]['razao']):'Sem informação!';
					$cidade_emp 	   = !empty(trim($resultsemp[$i]['cidade'])) ? trim($resultsemp[$i]['cidade']) : 'Sem informação!';
					$uf_emp     	   = !empty(trim($resultsemp[$i]['uf'])) ? $resultsemp[$i]['uf'] : 'Sem informação!';					

					array_push($alert_emp,
						array(
							'id'=>$idemp,
							'msg'=>"Inconsistência no cadastro de empressas => Nome: {$razao_emp} Cidade: {$cidade_emp} UF: {$uf_emp}"			
						)
					);


				}

			}

			## TERMINO DA VALIDAÇÃO DE EMPRESAS
			
			## COMEÇO DA VALIDAÇÃO DO ARQUIVO NOTASENT

			$daonotasent = new NotasEntTxt2DAO();
			$vetnotasent = $daonotasent->ListaNotasDeEntradas($_SESSION['cnpj'],$mesano);
			$numnotasent = count($vetnotasent);

			for($i = 0; $i < $numnotasent; $i++){

				$notasent		   = $vetnotasent[$i];

				$id 		  	   = $notasent['id'];
				$numero_nota       = $notasent['numero_nota'];
				$data_emissao 	   = $notasent['data_emissao'];
				$cnpj_cpf	  	   = $notasent['cnpj_cpf'];
				$tipo_v_r_a	  	   = $notasent['tipo_v_r_a'];
				$valor_total_nota  = $notasent['valor_total_nota'];
				$gta			   = $notasent['gta'];
				$nota_produtor_ini = $notasent['numero_nota_produtor_ini'];
				$nota_produtor_fin = $notasent['numero_nota_produtor_fin'];
				$condenas		   = $notasent['condenas'];
				$abate			   = $notasent['abate'];
				$insc_produtor	   = $notasent['insc_produtor'];
				$cnpj_emp		   = $notasent['cnpj_emp'];
				$xml			   = $notasent['xml'];
				$chave_acesso	   = $notasent['chave_acesso'];
				$razao			   = $notasent['razao'];
				$indcpfcnpj		   = "";	
				$cpf			   = "";
				$cnpj              = "";  
				$xcnpj_cpf         = "";
				if(strlen($cnpj_cpf) == 11){
					$cpf = $cnpj_cpf;
					$xcnpj_cpf = $cpf;
				}else if(strlen($cnpj_cpf) == 14){
					$cnpj = $cnpj_cpf;
					$xcnpj_cpf = $cnpj;
				}
				
				## VALIDANDO CNPJ E CPF
				if(empty($cpf) AND empty($cnpj)){

					array_push($xerro_notasent, array(
						'id'=>$id,
						'msg' => 'Linha da nota: {'.$numero_nota .'} </b> - FALTA INFORMAR O CPF OU CNPJ',
						'CNPJCPF'=>''.$xcnpj_cpf.'',
						'tipo'=>'CPF'					
					));	
					
				}else{
				
					 $valid_cpf = $valid->Valida_Cnpj_Cpf($cpf);
					
					 if($valid_cpf == false){
						$indcpfcnpj = 0;		
						 $valid_cnpj_cpf = $valid->Valida_Cnpj_Cpf($cnpj);
						
						 if($valid_cnpj_cpf == false){
							$indcpfcnpj = 0;
														
						}else{
							$indcpfcnpj = 1;	
						}	
						
					}else{
						$indcpfcnpj = 1;
					}
						
						
					if($indcpfcnpj == 0){
						array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Linha da nota: {'.$numero_nota .'}</b> - CNPJ OU CPF INVALIDO!',
							'CNPJCPF'=>''.$xcnpj_cpf.'',
							'tipo'=>'CPF'					
						));
					
					}	
						
				}

				$vetemp = $daoemp->VerificaEmpresasCadastradas(trim($cnpj),trim($cpf),trim($insc_produtor),$_SESSION['cnpj']);
				$numemp = count($vetemp);
				
				if($numemp == 0){
					
					array_push($xerro_notasent, array(
						'msg' => 'Linha #<b>{'.$id.'}</b> - CNPJ/CPF/INSC [PRODUTOR] INEXISTE/CADASTRO EMPRESAS-> CNPJ/CPF-> ('.$xcnpj_cpf.') INSC-> '.trim($insc_produtor).' !',
						'tipo'=>'empresa'					
					));
						
				}
									
				## FIM DA VALIDAÇÃO CNPJ E CPF	
				
				## VALIDAÇÃO DO NUMERO DA NOTA 
				if(strlen(trim($numero_nota)) == 0){
						
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Linha #<b>{'.$id.'}</b> - NUMERO DA NOTA-> '.$numero_nota.' INVALIDO!',					
					));	
				
				}
				## FIM DA VALIDAÇÃO DO NUMERO DA NOTA
			
				## VALIDAÇÃO DA DATA DE EMISSÃO
				if(strlen(trim($data_emissao)) == 0 and $valid->ValidaData(date('d/m/Y',strtotime($data_emissao))) == false){
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Linha #<b>{'.($i + 1).'}</b> - DATA DE EMISSAO INVALIDA!',					
					));	
				}
				## FIM DA VALIDAÇÃO DA DATA DE EMISSÃO
				
				## VALIDAÇÃO VIVO OU REDIMENTO
				/*if($tipo_v_r_a != "V" and $tipo_v_r_a != "R" and $tipo_v_r_a != "A"){
					
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Número da nota ('.$numero_nota.') </b> - INDICADOR [V]IVO OU [R]ENDIMENTO->'.$tipo_v_r_a.'!',					
					));					
					
				}*/
				## FIM DA VALIDAÇÃO VIVO OU REDIMENTO
				
				## VALIDAÇÃO VALOR TOTAL DA NOTA
				if(trim($valor_total_nota) < '0.01'){
					
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Número da nota ('.$numero_nota.') </b> - VALOR TOTAL DA NOTA FISCAL ->'.number_format(floatval(trim($valor_total_nota)),2,',','.').'!',					
					));
					
				}
				## FIM DA VALIDAÇÃO VALOR TOTAL DA NOTA
				
				## VALIDAÇÃO DE CONDENAS SE SIM OU NÃO
				if(trim($condenas) != 'S' and trim($condenas) != 'N'){
				
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Número da nota ('.$numero_nota.') </b> - INDICADOR DE CONDENAS [S]IM [N]AO->'.$condenas.'!',					
					));
				}
				## FIM DA VALIDAÇÃO DE CONDENAS SE SIM OU NÃO
				
				## VALIDAÇÃO SE ABATE PROPRIO OU TERCEIRO
				if(trim($abate) != 'P' and trim($abate) != 'T'){
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Número da nota ('.$numero_nota.') </b> - INDICADOR DE ABATE [P]ROPRIO OU [T]ERCEIROS->'.$abate.'!',					
					));
				
				}
				## FIM DA VALIDAÇÃO SE ABATE PROPRIO OU TERCEIRO
				
				if(!empty($resultgta)){
					$keyshta = array_search($numero_nota,array_column($resultgta, 'numero_nota'));
					if(trim($keyshta) == ""){
						array_push($alert_gta,array(
							'codigo'=>''.$numero_nota.'',
							'msg'=>' Número da nota ('.$numero_nota.') Não existem numeros de GTA informados!',
					));
					}
				}

				/*$daogta = new GtaTxtDAO();
				$vetgta = $daogta->GtaEmpresas($numero_nota,$_SESSION['cnpj']);
				$numgta = count($vetgta);
					
				if($numgta == 0){
					
					array_push($alert_gta,array(
							'codigo'=>''.$numero_nota.'',
							'msg'=>' Número da nota ('.$numero_nota.') Não existem numeros de GTA informados!',
					));
					
				}*/

			
				array_push($dados,array(
						
					'Numero'=>''.$numero_nota.'',
					'chave'=>'',
					'entsai'=>'ENTRADA',
					'cliente'=>'',
					'valor'=>''.number_format(doubleval($valor_total_nota),2,',','.').'',
					'caminho'=>'',
					'dEmi'=>''.date('d/m/Y',strtotime($data_emissao)).'',
					'msg'=>''									
				));

			}
			## FIM DA VALIDAÇÃO DO ARQUIVO NOTASENT

			## COMEÇO DA VALIDAÇÃO DO ARQUIVO NOTASEN1
			
			$daonotasen1 = new NotasEn1Txt2DAO();
			$vetnotasen1 = $daonotasen1->ListaNotasEntradaDetalhe($_SESSION['cnpj'],$mesano);
			$numnotasen1 = count($vetnotasen1);

			for($x = 0; $x < $numnotasen1; $x++){

				$notasen1		  = $vetnotasen1[$x];

				$id_det 		  	  = $notasen1['id'];
				$numero_nota_det  	  = $notasen1['numero_nota'];
				$data_emissao_det 	  = $notasen1['data_emissao'];
				$cnpj_cpf_det	  	  = $notasen1['cnpj_cpf'];
				$codigo_produto_det   = $notasen1['codigo_produto'];
				$qtd_cabeca_det    	  = $notasen1['qtd_cabeca'];
				$peso_vivo_cabeca_det = $notasen1['peso_vivo_cabeca'];
				$peso_carcasa_det	  = $notasen1['peso_carcasa'];
				$preco_quilo_det	  = $notasen1['preco_quilo'];
				$numero_item_nota_det = $notasen1['numero_item_nota'];
				$insc_estadual_det	  = $notasen1['insc_estadual'];
				$data_abate_det		  = $notasen1['data_abate'];
				$tipo_r_v_det		  = $notasen1['tipo_r_v'];
				$cfop_det			  = $notasen1['cfop'];
				$aliquota_icms_det	  = $notasen1['aliquota_icms'];
				$cnpj_emp_det		  = $notasen1['cnpj_emp'];
				$indcpfcnpj			  = "";
				$cpf			      = "";
				$cnpj                 = "";

				if(strlen($cnpj_cpf_det) == 11){
					$cpf = $cnpj_cpf_det;
				}else if(strlen($cnpj_cpf_det) == 14){
					$cnpj = $cnpj_cpf_det;
				}

				if(empty($cpf) and empty($cnpj)){
					array_push($xerro_notasen1, array(
						'msg' => 'Linha da nota: {'.$numero_nota_det .'} </b> - FALTA INFORMAR O CPF OU CNPJ',					
					));	
					
				}else{
				
					 $valid_cpf = $valid->Valida_Cnpj_Cpf($cpf);
					
					 if($valid_cpf == false){
						$indcpfcnpj = 0;		
						 $valid_cnpj_cpf = $valid->Valida_Cnpj_Cpf($cnpj);
						
						 if($valid_cnpj_cpf == false){
							$indcpfcnpj = 0;
														
						}else{
							$indcpfcnpj = 1;	
						}	
						
					}else{
						$indcpfcnpj = 1;
					}
						
						
					if($indcpfcnpj == 0){
						array_push($xerro_notasen1, array(
							'msg' => 'Linha da nota: {'.$numero_nota_det .'} </b> - CNPJ OU CPF INVALIDO!',					
						));
					
					}	
						
				}

				## VALIDAÇÃO DO NUMERO DA NOTA
				/*if(strlen(trim($numero_nota_det)) < 6){
							
					array_push($xerro_notasen1, array(
							'id'=>$id_det,
							'msg' => 'Linha #<b>{'.$id_det.'}</b> - NUMERO DA NOTA-> '.$numero_nota_det.' !',					
					));	
				
				}	*/
				## FIM DA VALIDAÇÃO DO NUMERO DA NOTA

				## VALIDAÇÃO DA DATA DE EMISSÃO	
				if(strlen(trim($data_emissao_det)) == 0 and $valid->ValidaData(date('d/m/Y',strtotime($data_emissao_det))) == false){
					array_push($xerro_notasen1, array(
							'id'=>$id_det,	
							'msg' => 'Linha #<b>{'.$id.'}</b> - DATA DE EMISSAO INVALIDA!',					
					));	
				}	
				## FIM DA VALIDAÇÃO DA DATA DE EMISSÃO				

				if(!empty($data_abate_det) and $data_abate_det != '0000-00-00'){
					if(date('m/Y',strtotime($data_abate_det)) != $_SESSION['apura']['mesano']){
						if(!empty($resuprod)){
							$keysp = array_search(trim($codigo_produto_det),array_column($resuprod, 'cod_prod'));
							if(trim($keysp) != ""){
								$desc_prod = $resuprod[$keysp]['desc_prod'];
							}else{
								$desc_prod = "";
							}
						}
						//alerta de diferente da data da competencia
						array_push($alert_dataabate,array(
							'codigo'=>''.$numero_nota_det.'',
							'cProd'=>''.trim($codigo_produto_det).'',
							'dProd'=>'('.trim($codigo_produto_det).') - '.$desc_prod.'',											
							'data_emissao'=>date('m/Y',strtotime($data_emissao_det)),
							'data_abate'=>date('d/m/Y',strtotime($data_abate_det)),
							'numero_nota'=>''.$numero_nota_det.'',
							'entsai'=>'Entrada',
							'idseq'=>$numero_item_nota_det,
							'msg'=>' Data do abate diferente da data da competência => Data Competência: '.date('m/Y',strtotime($data_emissao_det)).' Data Abate informada: '.date('d/m/Y',strtotime($data_abate_det)).' Numero Nota: '.$numero_nota_det.' Produto: ('.trim($codigo_produto_det).' '.$desc_prod.') ',									
						));
					}
				}else{
					if(!empty($resuprod)){
						$keysp = array_search(trim($codigo_produto_det),array_column($resuprod, 'cod_prod'));
						if(trim($keysp) != ""){
							$desc_prod = $resuprod[$keysp]['desc_prod'];
						}else{
							$desc_prod = "";
						}
					}
					array_push($err_dataabate,array(
						'codigo'=>''.$numero_nota_det.'',
						'cProd'=>''.trim($codigo_produto_det).'',
						'dProd'=>'('.trim($codigo_produto_det).') - '.$desc_prod.'',											
						'data_emissao'=>date('m/Y',strtotime($data_emissao_det)),
						'data_abate'=>date('d/m/Y',strtotime($data_abate_det)),
						'numero_nota'=>''.$numero_nota_det.'',
						'entsai'=>'Entrada',
						'idseq'=>$numero_item_nota_det,
						'msg'=>'Data do abate não informado numero nota: '.$numero_nota_det.' Produto: ('.trim($codigo_produto_det).' '.$desc_prod.') ',									
					));
				}
				

				## VALIDAÇÃO PREÇO QUILO
				if(trim($preco_quilo_det) < 0.01){
					
					array_push($xerro_notasen1, array(
						'id'=>$id_det,
						'codigo'=>''.$numero_nota_det.'',
						'entsai'=>'Entrada',
						'msg' => 'Linha #<b>{'.$id_det.'}</b> - NOTA: '.$numero_nota_det.' VALOR: '.$preco_quilo_det.' PRE€O POR QUILO INFORMADO INCONSISTENTE !',					
					));
				}
				## FIM DA VALIDAÇÃO PREÇO QUILO
				
				if(!empty($resuprod)){
					$keysp = array_search(trim($codigo_produto_det),array_column($resuprod, 'cod_prod'));
					if(trim($keysp) == ""){
						array_push($err_produto,array(
							'codigo'=>''.trim($codigo_produto_det).'',
							'cnota'=>''.trim($numero_nota_det).'',
							'msg'=>' Código do produto ('.trim($codigo_produto_det).') :: Produto não relacionado com o produto do agregar ::',
							"dados"=> array(
								'nNF'=>''.$numero_nota_det.'',
								'entsai'=>'Entrada',
								'cliente'=>'',
								'valor'=>'',
								'demi'=>''.date('d/m/Y',strtotime($data_emissao_det)).''
							),
						));
					}else{
						$cod_secretaria = $resuprod[$keysp]['cod_secretaria'];
						$desc_prod      = $resuprod[$keysp]['desc_prod'];		

						if($cod_secretaria == ""){
							array_push($err_produto,array(
								'codigo'=>''.trim($codigo_produto_det).'',
								'cnota'=>''.trim($numero_nota_det).'',
								'msg'=>' Código do produto ('.trim($codigo_produto_det).' '.$desc_prod.') :: Produto não relacionado com o produto do agregar ::',
								"dados"=> array(
									'nNF'=>''.$numero_nota_det.'',
									'entsai'=>'Saida',
									'cliente'=>'',
									'valor'=>'',
									'demi'=>''.date('d/m/Y',strtotime($data_emissao_det)).''
								),
							));
						}
					}
				}
				
				if(!empty($resultscfop)){
					$keys = array_search($cfop_det,array_column($resultscfop, 'Codigo'));
					if(trim($keys) != ""){
						$codcfop   = $resultscfop[$keys]['Codigo'];
						$nomcfop   = $resultscfop[$keys]['Nome'];
						$geraagreg = $resultscfop[$keys]['gera'];
						$vinculado = $resultscfop[$keys]['vinculado'];
						$idvinculo = $resultscfop[$keys]['idvinculado'];

						if(empty($geraagreg)){
							$err_cfop[] = array(
								'idvinc'=>''.$idvinculo.'',
								'codigo'=>''.trim($cfop_det).'',
								'nota'=>''.trim($numero_nota_det).'',
								'arquivo'=>'',
								'msg'=>' CFOP ('.trim($cfop_det).') sem indicação se irá "Considerar" ou "Desconsiderar" na apuração de crédito.',
							);
						}
					}else{
						$err_cfop[] = array(
							'codigo'=>''.trim($cfop_det).'',
							'msg'=>' CFOP ('.trim($cfop_det).') não Existe no sistema ou não esta gravado!  '
						);	
					}
				}
				

				if(!empty($resuprod)){
					$keycab = array_search(trim($codigo_produto_det),array_column($resuprod, 'cod_prod'));
					if(trim($keycab) != ""){
						$cod_secretaria = $resuprod[$keycab]['cod_secretaria'];
					}else{
						$cod_secretaria = "";
					}
				}else{
					$cod_secretaria = "";
				}
				
				if($cod_secretaria != '99999' and $cod_secretaria != '99998' and $cod_secretaria != '99997' and $cod_secretaria != '99996'){
					
					if(empty($qtd_cabeca_det) || (int)$qtd_cabeca_det <= 0){
						
						array_push($err_nota,array(
							'codigo'=>''.$numero_nota_det.'',
							'cProd'=>''.trim($codigo_produto_det).'',
							'idseq'=>$numero_item_nota_det,
							'entsai'=>'Entrada',
							'msg'=>' Número da nota ('.$numero_nota_det.') # Linha do produto ('.trim($codigo_produto_det).') esta faltando numero de cabeças a ser preenchido!',										
						));
					
					}

					if($qtd_cabeca_det > 1000){
						array_push($err_nota,array(
							'codigo'=>''.$numero_nota_det.'',
							'cProd'=>''.trim($codigo_produto_det).'',
							'idseq'=>$numero_item_nota_det,
							'entsai'=>'Entrada',
							'msg'=>' Número da nota ('.$numero_nota_det.') # Linha do produto ('.trim($codigo_produto_det).') quantidade de cabeças incorreta!',										
						));
					}

					if(empty($tipo_r_v_det)){
						array_push($err_v_r,array(
							'codigo'=>''.$numero_nota_det.'',
							'cProd'=>''.trim($codigo_produto_det).'',
							'qCom'=>''.number_format(doubleval($qtd_cabeca_det),2,',','.').'',
							'entsai'=>'Entrada',
							'idseq'=>$numero_item_nota_det,
							'msg'=>' Número da nota ('.$numero_nota_det.') # Linha do produto ('.trim($codigo_produto_det).') <br/> Esta faltando Informar se é Vivo ou Rendimento!',									
						));
						
					}

					if(trim($peso_carcasa_det) < 001 and $tipo_r_v_det == 'R'){
					
						array_push($xerro_notasen1, array(
							'codigo'=>''.$numero_nota_det.'',
							'cProd'=>''.trim($codigo_produto_det).'',
							'idseq'=>$numero_item_nota_det,
							'entsai'=>'Entrada',
							'msg' => 'Número da nota ('.$numero_nota_det.') # Linha do produto ('.trim($codigo_produto_det).') Esta faltando Informar peso das carcaça '.number_format($peso_carcasa_det,3,',','.').' !',					
						));

					}

				}

			}

			## FIM DA VALIDAÇÃO DO ARQUIVO NOTASEN1
			
			## COMEÇO DA VALIDAÇÃO DO ARQUIVO NOTASSAI

			$daonotassaitxt = new NotasSaiTxt2DAO();
			$vetnotassaitxt = $daonotassaitxt->ListandoNotasSai($mesano,$_SESSION['cnpj']);
			$numnotassaitxt = count($vetnotassaitxt);

			for($y = 0; $y < $numnotassaitxt; $y++){

				$notasai		  = $vetnotassaitxt[$y];

				$id 		  	  =	$notasai['id'];
				$numero_nota  	  = $notasai['numero_nota'];
				$data_emissao 	  = $notasai['data_emissao'];
				$cnpj_cpf	  	  = $notasai['cnpj_cpf'];
				$valor_total_nota = $notasai['valor_total_nota'];	
				$valor_icms		  = $notasai['valor_icms'];
				$valor_icms_subs  = $notasai['valor_icms_subs'];
				$ent_sai		  = $notasai['ent_sai'];
				$insc_estadual	  = $notasai['insc_estadual'];
				$cfop			  = $notasai['cfop'];
				$razao			  = $notasai['razao'];
				$indcpfcnpj       = "";
				$cpf			  = "";
				$cnpj             = "";

				if(strlen($cnpj_cpf) == 11){
					$cpf = $cnpj_cpf;
				}else if(strlen($cnpj_cpf) == 14){
					$cnpj = $cnpj_cpf;
				}

				## VALIDANDO CNPJ E CPF
				if(empty($cnpj_cpf)){
					array_push($xerro_notassai, array(
						'msg' => 'Linha da nota: {'.$numero_nota .'} </b> - CNPJ OU CPF INVALIDO!',					
					));	
					
				}else{
				
					 $valid_cpf = $valid->Valida_Cnpj_Cpf($cpf);
					
					 if($valid_cpf == false){
						 
						 $indcpfcnpj = 0;		
						 $valid_cnpj_cpf = $valid->Valida_Cnpj_Cpf($cnpj);
						
						 if($valid_cnpj_cpf == false){
							$indcpfcnpj = 0;
														
						}else{
							$indcpfcnpj = 1;	
						}	
						
					}else{
						$indcpfcnpj = 1;
					}
						
						
					if($indcpfcnpj == 0){
						array_push($xerro_notassai, array(
							'msg' => 'Linha #<b>{'.($y + 1).'}</b> - CNPJ OU CPF INVALIDO!',					
						));
					
					}	
						
				}
				
				$vetemp = $daoemp->VerificaEmpresasCadastradas(trim($cnpj),trim($cpf),trim($insc_estadual),$_SESSION['cnpj']);
				$numemp = count($vetemp);
				
				if($numemp == 0){
					
					array_push($xerro_notassai, array(
						'id'=>$id,
						'msg' => 'Linha #<b>{'.($y + 1).'}</b> - CNPJ/CPF/INSC [PRODUTOR] INEXISTE/CADASTRO DE EMPRESAS-> ('.$cnpj_cpf.') INSC-> '.trim($insc_estadual).' !',					
					));
						
				}
									
				## FIM DA VALIDAÇÃO CNPJ E CPF
				
				## VALIDAÇÃO DO NUMERO DA NOTA 
				/*if(strlen(trim($numero_nota)) < 6){
						
					array_push($xerro_notassai, array(
							'id'=>$id,
							'msg' => 'Linha #<b>{'.$id.'}</b> - NUMERO DA NOTA-> '.$numero_nota.' !',					
					));	
				
				}	*/
				## FIM VALIDAÇÃO DO NUMERO DA NOTA 
				
				## VALIDAÇÃO DA DATA DE EMISSÃO 
				if(strlen(trim($data_emissao)) == 0 and $valid->ValidaData(date('d/m/Y',strtotime($data_emissao))) == false){
					array_push($xerro_notassai, array(
							'id'=>$id,
							'msg' => 'Linha #<b>{'.($y + 1).'}</b> - DATA DE EMISSAO INVALIDA!',					
					));	
				}	
				
				## FIM VALIDAÇÃO DA DATA DE EMISSÃO
				
				## VALIDAÇÃO DO TOTA DA NOTA 
				if(trim($valor_total_nota) < 0.01){
					
					array_push($xerro_notassai, array(
							'id'=>$id,
							'msg' => 'Número da nota:'.$numero_nota.' </b> - VALOR TOTAL DA NOTA FISCAL-> '.number_format(trim($valor_total_nota),2,',','.').' INVÁLIDO !',					
					));
				
				}
				## FIM DO TOTA DA NOTA 
				
				## VALIDAÇÃO DO VALOR ICMS NORMAL
				if(trim($valor_icms) < 0.00){
						
					array_push($xerro_notassai, array(
							'id'=>$id,
							'msg' => 'Número da nota:'.$numero_nota.' </b> - VALOR DO ICMS NORMAL-> '.number_format(trim($valor_icms),2,',','.').' INVÁLIDO !',					
					));	
					
				}
				## FIM VALIDAÇÃO DO VALOR ICMS NORMAL
				
				## VALIDAÇÃO DO VALOR ICMS SUBSTUIÇÃO
				if(trim($valor_icms_subs) < 0.00){
					
					array_push($xerro_notassai, array(
							'id'=>$id,
							'msg' => 'Número da nota:'.$numero_nota.' </b> - VALOR DO ICMS SUBSTIT-> '.number_format(trim($valor_icms_subs),2,',','.').' INVÁLIDO !',					
					));	
					
				}
				## FIM VALIDAÇÃO DO VALOR ICMS SUBSTUIÇÃO
				
				## VALIDAÇÃO ENTRADA E SAIDA
				if(trim($ent_sai) != "E" and trim($ent_sai) != "S"){
					array_push($xerro_notassai, array(
							'id'=>$id,
							'msg' => 'Número da nota:'.$numero_nota.' </b> - INDICADOR DE [E]NTRADA [S]AIDA -> '.$ent_sai.' !',					
					));
					
				}

				array_push($dados,array(
					'Numero'=>''.$numero_nota.'',
					'chave'=>'',
					'entsai'=>'SAIDA',
					'cliente'=>'',
					'valor'=>''.number_format(doubleval($valor_total_nota),2,',','.').'',
					'caminho'=>'',
					'dEmi'=>''.date('d/m/Y',strtotime($data_emissao)).'',	
					'msg'=>''								
				));

			}
			## FIM DA VALIDAÇÃO DO ARQUIVO NOTASSAI

			## COMEÇO DA VALIDAÇÃO DO ARQUIVO NOTASSA1

			$daonotassai1 = new NotasSa1Txt2DAO();
			$vetnotassai1 = $daonotassai1->ListaNotasSa1Detalhe($_SESSION['cnpj'],$mesano);
			$numnotassai1 = count($vetnotassai1);

			for($z = 0; $z < $numnotassai1; $z++){

				$notasa1		  = $vetnotassai1[$z];

				$id 		  	  = $notasa1['id'];
				$numero_nota      = $notasa1['numero_nota'];
				$data_emissao 	  = $notasa1['data_emissao'];
				$cnpj_cpf	      = $notasa1['cnpj_cpf'];
				$codigo_produto   = $notasa1['codigo_produto'];
				$qtd_pecas		  = $notasa1['qtd_pecas'];
				$peso			  = $notasa1['peso'];
				$preco_unitario   = $notasa1['preco_unitario'];
				$ent_sai		  = $notasa1['ent_sai'];
				$numero_item_nota = $notasa1['numero_item_nota'];		
				$insc_estadual    = $notasa1['insc_estadual'];
				$cfop			  = $notasa1['cfop'];
				$aliquota_icms	  = $notasa1['aliquota_icms'];
				$indcpfcnpj       = "";
				$cnpj			  = "";
				$cpf			  = "";

				if(strlen($cnpj_cpf) == '11'){
					$cpf  = $cnpj_cpf; 
				}else{
					$cnpj = $cnpj_cpf;
				}

					## VALIDANDO CNPJ E CPF
					if(empty($cnpj_cpf)){
						array_push($xerro_notassa1, array(
							'msg' => 'Linha da nota: {'.$numero_nota .'} </b> - CNPJ OU CPF INVALIDO!',					
						));	
						
					}else{
					
						$valid_cpf = $valid->Valida_Cnpj_Cpf($cpf);
						
						if($valid_cpf == false){
							
							$indcpfcnpj = 0;		
							$valid_cnpj_cpf = $valid->Valida_Cnpj_Cpf($cnpj);
							
							if($valid_cnpj_cpf == false){
								$indcpfcnpj = 0;
															
							}else{
								$indcpfcnpj = 1;	
							}	
							
						}else{
							$indcpfcnpj = 1;
						}
							
							
						if($indcpfcnpj == 0){
							array_push($xerro_notassa1, array(
								'msg' => 'Linha #<b>{'.($z + 1).'}</b> - CNPJ OU CPF INVALIDO!',					
							));
						
						}	
							
					}	

														
					
					$vetemp = $daoemp->VerificaEmpresasCadastradas(trim($cnpj),trim($cpf),trim($insc_estadual),$_SESSION['cnpj']);
					$numemp = count($vetemp);
					
					if($numemp == 0){
						
						array_push($xerro_notassa1, array(
							'msg' => 'Linha #<b>{'.($z + 1).'}</b> - CNPJ/CPF/INSC [PRODUTOR] INEXISTE/CADASTRO DE EMPRESAS-> ('.$cnpj_cpf.') INSC-> '.trim($insc_estadual).' !',					
						));
							
					}
					/*if(!empty($resultemp)){
						$val = trim($cnpj_cpf).trim($insc_estadual);
						$keyemp = array_search($val,array_column($resultemp, 'val'));
						if($keyemp == ''){
							array_push($xerro_notassa1, array(
								'msg' => 'Linha #<b>{'.($z + 1).'}</b> - CNPJ/CPF/INSC [PRODUTOR] INEXISTE/CADASTRO DE EMPRESAS-> ('.$cnpj_cpf.') INSC-> '.trim($insc_estadual).' !',					
							));
						}
					}*/
									
					## FIM DA VALIDAÇÃO CNPJ E CPF
					
					## VALIDAÇÃO DO NUMERO DA NOTA 
					/*if(strlen(trim($numero_nota)) < 6){
							
						array_push($xerro_notassa1, array(
								'id'  => $id,
								'msg' => 'Linha #<b>{'.$id.'}</b> - NUMERO DA NOTA-> '.$numero_nota.' !',					
						));	
					
					}	*/
					## FIM VALIDAÇÃO DO NUMERO DA NOTA 
					
					## VALIDAÇÃO DA DATA DE EMISSÃO 
					if(strlen(trim($data_emissao)) == 0 and $valid->ValidaData(date('d/m/Y',strtotime($data_emissao))) == false){
						array_push($xerro_notassa1, array(
								'id'  => $id,
								'msg' => 'Número da nota: '.$numero_nota.' </b> - DATA DE EMISSAO INVALIDA!',					
						));	
					}						
					## FIM VALIDAÇÃO DA DATA DE EMISSÃO
					
					## VALIDAÇÃO DE QUANTIDADE DE PEÇAS
					if(trim($qtd_pecas) < 0){
						array_push($xerro_notassa1, array(
								'id'  => $id,
								'msg' => 'Número da nota: '.$numero_nota.' </b> - QUANTIDADE DE PECAS INFORMADAS INCONSISTENTE!',					
						));
					
					}
					## FIM DA VALIDAÇÃO DE QUANTIDADE DE PEÇAS
					
					## VALIDAÇÃO DO PESO
					if(!empty($results)){
						$key = array_search($cfop,array_column($results, 'id_cfop'));
						if(trim($key) != ""){
							$sn = $results[$key]['sn'];		
							if($sn == 'sim'){
								if(floatval(trim($peso)) <= 0){
									array_push($xerro_notassa1, array(
											'id'  => $id,
											'msg' => 'Número da nota: '.$numero_nota.' </b> - PESO INFORMADO INCONSISTENTE !',					
									));
									
								}	
							}
						}else{
							if(floatval(trim($peso)) <= 0){
								array_push($xerro_notassa1, array(
										'id'  => $id,
										'msg' => 'Número da nota: '.$numero_nota.' </b> - PESO INFORMADO INCONSISTENTE !',					
								));
								
							}
						}
					}

					
					
					## FIM DA VALIDAÇÃO DO PESO
					
					## VALIDAÇÃO DO PRECO UNITARIO
					if(trim($preco_unitario) < 0){
						
						array_push($xerro_notassa1, array(
								'id'  => $id,
								'msg' => 'Número da nota: '.$numero_nota.' </b> - PRE€O POR QUILO INFORMADO INCONSISTENTE !',					
						));
					}
					## FIM DA VALIDAÇÃO DO PRECO UNITARIO
					
					## VALIDAÇÃO ENTRADA E SAIDA
					if(trim($ent_sai) != "E" and trim($ent_sai) != "S"){
						array_push($xerro_notassa1, array(
								'id'  => $id,
								'msg' => 'Número da nota: '.$numero_nota.' </b> - INDICADOR DE [E]NTRADA [S]AIDA -> '.$ent_sai.' !',					
						));
						
					}
					## FIM VALIDAÇÃO ENTRADA E SAIDA

					if(!empty($resultscfop)){

						$keys = array_search($cfop,array_column($resultscfop, 'Codigo'));
						if(trim($keys) != ""){
							$codcfops   = $resultscfop[$keys]['Codigo'];
							$nomcfops   = $resultscfop[$keys]['Nome'];
							$geraagregs = $resultscfop[$keys]['gera'];
							$vinculados = $resultscfop[$keys]['vinculado'];
							$idvinculos = $resultscfop[$keys]['idvinculado'];
							
							if($geraagregs != 2){

								//$resuprod
								if(!empty($resuprod)){
									$keysp = array_search(trim($codigo_produto),array_column($resuprod, 'cod_prod'));
									if(trim($keysp) == ""){

										array_push($err_produto,array(
											'codigo'=>''.trim($codigo_produto).'',
											'cnota'=>''.trim($numero_nota).'',
											'msg'=>' Código do produto ('.trim($codigo_produto).') :: Produto não relacionado com o produto do agregar ::',
											"dados"=> array(
												'nNF'=>''.$numero_nota.'',
												'entsai'=>'Saida',
												'cliente'=>'',
												'valor'=>'',
												'demi'=>''.date('d/m/Y',strtotime($data_emissao)).''
											),
										));
									}else{

										$cod_secretaria = $resuprod[$keysp]['cod_secretaria'];
										$desc_prod      = $resuprod[$keysp]['desc_prod'];		

										if($cod_secretaria == ""){
											array_push($err_produto,array(
												'codigo'=>''.trim($codigo_produto).'',
												'cnota'=>''.trim($numero_nota).'',
												'msg'=>' Código do produto ('.trim($codigo_produto).' '.$desc_prod.') :: Produto não relacionado com o produto do agregar ::',
												"dados"=> array(
													'nNF'=>''.$numero_nota.'',
													'entsai'=>'Saida',
													'cliente'=>'',
													'valor'=>'',
													'demi'=>''.date('d/m/Y',strtotime($data_emissao)).''
												),
											));
										}

									}
								}

							}
							
							if(empty($geraagregs)){
								$err_cfop[] =array(
									'idvinc'=>''.$idvinculos.'',
									'codigo'=>''.trim($cfop).'',
									'nota'=>''.trim($numero_nota).'',
									'arquivo'=>'',
									'msg'=>' CFOP ('.trim($cfop).') sem indicação se irá "Considerar" ou "Desconsiderar" na apuração de crédito.',
								);
							}	

						}else{
							$err_cfop[] =array(
								'codigo'=>''.trim($cfop).'',
								'msg'=>' CFOP ('.trim($cfop).') não Existe no sistema ou não esta gravado!  '
							);	
						}

					}
					

			}


			$daofolha = new FolhaTxtDAO();
			$vetfolha = $daofolha->ValidaFolhaMes($_SESSION['cnpj'],$_SESSION['apura']['mesano']);	
			$numfolha = count($vetfolha);
				
			if($numfolha > 0){				
				
				$folha = $vetfolha[0];
				
				$codfolha         = $folha->getCodigo();
				$num_funcionarios = $folha->getNumFuncionario();
				$valor_folha 	  = $folha->getValorFolha();
				
				if(empty($num_funcionarios)){
					array_push($alert_nfunc,array(
						'id'=>''.$codfolha.'',
						'msg'=>'Falta informar o numero de funcionários!',
					));											
				}
				
				if(empty($valor_folha)){
					array_push($alert_vlfolha,array(
						'id'=>''.$codfolha.'',
						'msg'=>'Falta informar valor da folha!',
					));										
				}								
			}else{
				
				array_push($alert_nfunc,array(
						'id'=>'',
						'msg'=>'Falta informar o numero de funcionários!',
				));
				
				array_push($alert_vlfolha,array(
						'id'=>'',
						'msg'=>'Falta informar valor da folha!',
				));
			}					
				
			

			$daoprot = new ProtocoloDAO();
			$vetprot = $daoprot->ListaProtocoloPorEmmpresaCompetencia($_SESSION['apura']['mesano'],$_SESSION['cnpj']);
			$numprot = count($vetprot);

			if($numprot > 0){

					$prot   = $vetprot[0];
					
					$idprot = $prot->getCodigo();	

					$prots  = new Protocolo();

					$prots->setCodigo($idprot);
					$prots->setStatus(7);	

					$daoprot->updateStatus($prots);	

			}else{

				$prot = new Protocolo();
						
				$prot->setCompetencia($_SESSION['apura']['mesano']);
				$prot->setProtocolo('');
				$prot->setCripty('');
				$prot->setStatus(7);
				$prot->setCnpjEmp($_SESSION['cnpj']);	
				$prot->setTipoArq(2);

				$daoprot->inserir($prot);

			}


			$daoguia 	 =  new GuiaicmsDAO();
			$vetguianorm = $daoguia->ValidGuiaicmsNormal($_SESSION['cnpj'],$_SESSION['apura']['mesano']);	
			$numguianorm = count($vetguianorm);	
				
			if($numguianorm == 0){
				
				array_push($alert_icmsnorm,array(
					'msg'=>'Valor do ICMS normal não informado !',							
				));
			}	
				
			$vetguiast = $daoguia->ValidGuiaicmsSt($_SESSION['cnpj'],$_SESSION['apura']['mesano']);
			$numguiast = count($vetguiast);	
			
			if($numguiast == 0){
				
				array_push($alert_icmsst,array(
					'msg'=>'Valor do ICMS ST não informado !',
				));
			}	
				
			$erro_cfop = $valid->array_sort($err_cfop,'codigo',SORT_ASC);
			$cfop_erro = array();	
			foreach($erro_cfop as $key=>$value){				
				array_push($cfop_erro,$value);					
			}
				
			$erro_produto = $valid->array_sort($err_produto,'codigo',SORT_ASC);	
			$produto_erro = array();	
			foreach($erro_produto as $keys=>$values){
				array_push($produto_erro,$values);
				
			}
				
			$num_tota_erros = count($xerro_notasent)  + count($xerro_emp) + count($xerro_notasen1) + count($xerro_notassai) + count($xerro_notassa1) + count($cfop_erro) + count($produto_erro) + count($err_nota) + count($err_v_r) + count($err_dataabate);	
			
			
			$data = array(
					'erro'=>array(
						'cfop'=>$cfop_erro,
						'produto'=>$produto_erro,						
						'vivorendmento'=>$err_v_r,
						'abate'=>$err_dataabate,
					),
					'info'=>array(
						'funcionario'=>$alert_nfunc,
						'folha'=>$alert_vlfolha,
						'icmsnormal'=>$alert_icmsnorm,
						'icmsst'=>$alert_icmsst,
						'gta'=>$alert_gta,
						'infabate'=>$alert_dataabate,
						'empresa'=>$alert_emp,
						'nota'=>$err_nota,
						'num_entrada'=>$numnotasent,
						'num_saida'=>$numnotassaitxt
					),
					'dados_grid'=>$dados,
					'xerro_notasent'=>$xerro_notasent,					
					'xerro_emp'=>$xerro_emp,
					'xerro_notasen1'=>$xerro_notasen1,
					'xerro_notassai'=>$xerro_notassai,
					'xerro_notassa1'=>$xerro_notassa1, 	
					'num_tota_erros'=>$num_tota_erros,);		
					
					$_SESSION[$mesano]['validador'] = $data;

			echo json_encode($data);

		break;	
		case 'testearq':
			
				
				
				echo 'CPNJ EMPRESA: '.$_SESSION['cnpj'].'<BR><BR>';
				$daofunc   = new FuncoesDAO();					
				echo "<table>";
				##ARQUIVO NOTASSAI.TXT
				$path_notassa1   = "../arquivos/".$_SESSION['cnpj']."/NOTASSA1.TXT"; // pegando caminho do txt NOTASSAI		
				$lines = file ('../arquivos/'.$_SESSION['cnpj'].'/NOTASSA1.TXT');
				
				foreach ($lines as $line_num => $line) {
						
					$line =  $line;
					
					$numero_notasa1  = substr($line,0,9);
					$dtemissao		 = substr($line,9,8);
					$cnpj_cpf	     = substr($line,17,14);					
					$cod_produto     = substr($line,31,14);	
					$qtdpecas        = substr($line,45,15);
					$peso            = substr($line,60,15);
					$precounitario   = substr($line,75,15);
					$entsai			 = substr($line,90,1);
					$numitem		 = substr($line,91,3);
					$insc_est		 = substr($line,94,14);
					$cfop			 = substr($line,108,4);
					$aliguotaicms	 = substr($line,112,6);

					echo "<tr>";
						echo "<td>{$numero_notasa1}<td>";
						echo "<td>{$dtemissao}<td>";
						echo "<td>{$cnpj_cpf}<td>";
						echo "<td>{$cod_produto}<td>";
						echo "<td>{$qtdpecas}<td>";
						echo "<td>{$peso}<td>";
						echo "<td>{$precounitario}<td>";
						echo "<td>{$entsai}<td>";
						echo "<td>{$numitem}<td>";
						echo "<td>{$insc_est}<td>";
						echo "<td>{$cfop}<td>";
						echo "<td>{$aliguotaicms}<td>";
						echo "<td>{$aliguotaicms}<td>";
						echo "<td><td>";
					echo "</tr>";
					//echo "V/T/A: {$tipo_v_t_a}     - VALOR NOTA: {$valortotanota}<br>";
				}

				echo "<table>";
					
		break;
		case 'testes':
			
			$daofunc   = new FuncoesDAO();

			$db = new MysqliDb(Array (
                'host' => 'localhost',
                'username' => 'root', 
                'password' => '',
                'db'=> 'agregar',
                'port' => 3306,
                'prefix' => '',
                'charset' => 'utf8'));

			$cnpj = $_REQUEST['cnpj'];	

			echo 'CPNJ EMPRESA: '.$cnpj.'<BR><BR>';

		//	echo "<table>";
				##ARQUIVO NOTASSAI.TXT
				$path_notassa1   = "../arquivos/".$cnpj."/NOTASSA1.TXT"; // pegando caminho do txt NOTASSAI		
				$lines = file ('../arquivos/'.$cnpj.'/NOTASSA1.TXT');
				$data  = array();

				foreach ($lines as $line_num => $line) {
						
					$line =  $line;
					
					if($cnpj != '88728027000146'){
						$numero_notasa1  = substr($line,0,6);
						$dtemissao		 = substr($line,6,8);
						$cnpj_cpf	     = substr($line,14,14);
						$cod_produto     = substr($line,28,14);	
						$qtdpecas        = substr($line,42,15);
						$peso            = substr($line,57,15);
						$precounitario   = substr($line,72,15);
						$entsai			 = substr($line,87,1);
						$numitem		 = substr($line,88,3);
						$insc_est		 = substr($line,91,14);
						$cfop			 = substr($line,105,4);
						$aliguotaicms	 = substr($line,109,6);
					}else{

						$numero_notasa1  = substr($line,0,9);
						$dtemissao		 = substr($line,9,8);
						$cnpj_cpf	     = substr($line,17,14);					
						$cod_produto     = substr($line,31,14);	
						$qtdpecas        = substr($line,45,15);
						$peso            = substr($line,60,15);
						$precounitario   = substr($line,75,15);
						$entsai			 = substr($line,90,1);
						$numitem		 = substr($line,91,3);
						$insc_est		 = substr($line,94,14);
						$cfop			 = substr($line,108,4);
						$aliguotaicms	 = substr($line,112,6);
					}

					if($cfop == '1101'){
						continue;
					}

					if(trim($cnpj_cpf) != ''){
						if(!$daofunc->valida_cnpj(trim($cnpj_cpf))){
							if($daofunc->ValidaCPF(trim($cnpj_cpf))){
								$cnpj_cpf = $cnpj_cpf;									
							}else{
								
								if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
									$cnpj_cpf = substr($cnpj_cpf,3,11);		
									
								}else{
									$cnpj_cpf = substr($cnpj_cpf,3,11);
									
								}
								
							}
							
						}else{
							
							if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
								$cnpj_cpf = substr($cnpj_cpf,3,11);									
							}else{
								$cnpj_cpf = $cnpj_cpf;
							}
							
						}								
					}
					array_push($data,array(
						trim($numero_notasa1),
						date('Y-m-d',strtotime($dtemissao)),
						trim($cnpj_cpf),
						trim($cod_produto),
						trim(str_replace(',', '.', $qtdpecas)),
						trim($peso),
						trim($precounitario),
						trim(str_replace(',', '.', $qtdpecas)),
						trim($entsai),
						trim($numitem),
						trim($insc_est),
						trim($cfop),
						trim($aliguotaicms),
						trim($cnpj)
					));
					/*$data = array(array(
						trim($numero_notasa1),
						date('Y-m-d',strtotime($dtemissao)),
						trim($cnpj_cpf),
						trim($cod_produto),
						trim(str_replace(',', '.', $qtdpecas)),
						trim($peso),
						trim($precounitario),
						trim(str_replace(',', '.', $qtdpecas)),
						trim($entsai),
						trim($numitem),
						trim($insc_est),
						trim($cfop),
						trim($aliguotaicms),
						trim($cnpj)
					));*/

				/*	echo "<tr>";
						echo "<td>{$numero_notasa1}<td>";
						echo "<td>{$dtemissao}<td>";
						echo "<td>{$cnpj_cpf}<td>";
						echo "<td>{$cod_produto}<td>";
						echo "<td>{$qtdpecas}<td>";
						echo "<td>{$peso}<td>";
						echo "<td>{$precounitario}<td>";
						echo "<td>{$entsai}<td>";
						echo "<td>{$numitem}<td>";
						echo "<td>{$insc_est}<td>";
						echo "<td>{$cfop}<td>";
						echo "<td>{$aliguotaicms}<td>";
						echo "<td>{$aliguotaicms}<td>";
						echo "<td><td>";
					echo "</tr>";*/
					
				}

			//	echo "<table>";
				//print_r($data);

				$keys = Array(
						"numero_nota", 
						"data_emissao", 
						"cnpj_cpf",
						"codigo_produto",
						"qtd_pecas",
						"peso",
						"preco_unitario",
						"qtd_prod",
						"ent_sai",
						"numero_item_nota",
						"insc_estadual",
						"cfop",
						"aliquota_icms",
						"cnpj_emp");
				$ids = $db->insertMulti('notassa1txt', $data, $keys);
				if(!$ids) {
					echo 'insert failed: ' . $db->getLastError();
				} else {
					echo 'novos notassa1txt inseridos com o seguinte id\'s: ' . implode(', ', $ids);
				}



		break;
		case 'notassaitxt':
			$daofunc   = new FuncoesDAO();
			##ARQUIVO NOTASSAI.TXT
			$db = new MysqliDb(Array (
                'host' => 'localhost',
                'username' => 'root', 
                'password' => '',
                'db'=> 'agregar',
                'port' => 3306,
                'prefix' => '',
                'charset' => 'utf8'));

			$cnpj = $_REQUEST['cnpj'];
			$path_notassai   = "../arquivos/".$cnpj."/NOTASSAI.TXT"; // pegando caminho do txt NOTASSAI			
			$lines 			 = file('../arquivos/'.$cnpj.'/NOTASSAI.TXT');
			$datanotassai    = array();
			echo "<table>";
			foreach ($lines as $line_num => $line) {
				
				$line =  $line;

				if($cnpj != '88728027000146'){
					$numero_notafisc = substr($line,0,6);
					$dtemissao		 = substr($line,6,8);
					$cnpj_cpf	     = substr($line,14,14);					
					$valortotalnota  = substr($line,28,15);
					$valoricmsnormal = substr($line,43,15);
					$valoricmssubs	 = substr($line,58,15);
					$tipo_e_s		 = substr($line,73,1);
					$insc_est        = substr($line,74,14);
					$cfop		     = substr($line,88,4);					
				}else{
					$numero_notafisc = substr($line,0,9);
					$dtemissao		 = substr($line,9,8);
					$cnpj_cpf	     = substr($line,17,14);					
					$valortotalnota  = substr($line,31,15);
					$valoricmsnormal = substr($line,46,15);
					$valoricmssubs	 = substr($line,61,15);
					$tipo_e_s		 = substr($line,76,1);
					$insc_est        = substr($line,77,14);
					$cfop		     = substr($line,90,4);
				}

				if($cfop == '1101'){
					continue;
				}

				if(trim($cnpj_cpf) != ''){
					if(!$daofunc->valida_cnpj(trim($cnpj_cpf))){
						if($daofunc->ValidaCPF(trim($cnpj_cpf))){
							$cnpj_cpf = $cnpj_cpf;									
						}else{
							
							if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
								$cnpj_cpf = substr($cnpj_cpf,3,11);		
								
							}else{
								$cnpj_cpf = substr($cnpj_cpf,3,11);
								
							}
							
						}
						
					}else{
						
						if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
							$cnpj_cpf = substr($cnpj_cpf,3,11);									
						}else{
							$cnpj_cpf = $cnpj_cpf;
						}
						
					}								
				}

				array_push($datanotassai,array(
					trim($numero_notafisc),
					date('Y-m-d',strtotime($dtemissao)),
					trim($cnpj_cpf),
					trim($valortotalnota),
					trim($valoricmsnormal),
					trim($valoricmssubs),
					trim($tipo_e_s),
					trim($insc_est),
					trim($cfop),
					$cnpj
				));

				echo "<tr>";
						echo "<td>".trim($numero_notafisc)."<td>";
						echo "<td>".date('Y-m-d',strtotime($dtemissao))."<td>";
						echo "<td>".trim($cnpj_cpf)."<td>";
						echo "<td>".trim($valortotalnota)."<td>";
						echo "<td>".trim($valoricmsnormal)."<td>";
						echo "<td>".trim($valoricmssubs)."<td>";
						echo "<td>".trim($tipo_e_s)."<td>";
						echo "<td>".trim($insc_est)."<td>";
						echo "<td>".trim($cfop)."<td>";
						echo "<td>{$cnpj}<td>";		
						echo "<td><td>";
					echo "</tr>";
				
			}	
			echo "<table>";
			$keysnotassai = Array(
				"numero_nota",
				"data_emissao",
				"cnpj_cpf",
				"valor_total_nota",
				"valor_icms",
				"valor_icms_subs",
				"ent_sai",
				"insc_estadual",
				"cfop",
				"cnpj_emp");

			//$db->insertMulti('notassaitxt', $datanotassai, $keysnotassai);


		break;
		case 'notasent':

			$db = new MysqliDb(Array (
				'host' => 'localhost',
				'username' => 'root', 
				'password' => '',
				'db'=> 'agregar2',
				'port' => 3306,
				'prefix' => '',
				'charset' => 'utf8'));
				$db->setTrace (true);	
			$daofunc   = new FuncoesDAO();
			$cnpj 	   = $_REQUEST['cnpj'];
			
			##ARQUIVO NOTASENT.TXT
			$path_notasent   = "../arquivos/".$cnpj."/NOTASENT.TXT"; // pegando caminho do txt NOTASENT			
			$lines 			 = file('../arquivos/'.$cnpj.'/NOTASENT.TXT');
			$datanotasent    = array();			
			echo "<table>";
			foreach ($lines as $line_num => $line) {
				
				$line =  $line;
				
				if($_SESSION['cnpj'] != '88728027000146'){

					$numero_nota   			  = substr($line,0,6);
					$emissao	   			  = substr($line,6,8);
					$cnpj_cpf      			  = substr($line,14,14);								
					$tipo_v_t_a    			  = substr($line,28,1);
					$valortotanota 			  = substr($line,29,15);
					$gta		  			  = substr($line,44,6);
					$numero_nota_produtor_ini = substr($line,50,6);
					$numero_nota_produtor_fim = substr($line,56,6);
					$tipo_s_n				  = substr($line,62,1);
					$tipo_p_t				  = substr($line,63,1);
					$ie_produtor			  =	substr($line,64,14);

				}else{

					$numero_nota   			  = substr($line,0,9);
					$emissao	   			  = substr($line,9,8);
					$cnpj_cpf      			  = substr($line,17,14);					
					$tipo_v_t_a    			  = substr($line,31,1);
					$valortotanota 			  = substr($line,32,15);
					$gta		  			  = substr($line,47,6);
					$numero_nota_produtor_ini = substr($line,53,6);
					$numero_nota_produtor_fim = substr($line,59,6);
					$tipo_s_n				  = substr($line,65,1);
					$tipo_p_t				  = substr($line,66,1);
					$ie_produtor			  =	substr($line,67,14);

				}	

				if(trim($cnpj_cpf) != ''){
					if(!$daofunc->valida_cnpj(trim($cnpj_cpf))){
						if($daofunc->ValidaCPF(trim($cnpj_cpf))){
							$cnpj_cpf = $cnpj_cpf;									
						}else{
							
							if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
								$cnpj_cpf = substr($cnpj_cpf,3,11);		
								
							}else{
								$cnpj_cpf = substr($cnpj_cpf,3,11);
								
							}
							
						}
						
					}else{
						
						if($daofunc->ValidaCPF(trim(substr($cnpj_cpf,3,11)))){
							$cnpj_cpf = substr($cnpj_cpf,3,11);									
						}else{
							$cnpj_cpf = $cnpj_cpf;
						}
						
					}								
				}

				array_push($datanotasent,array(
					trim($numero_nota),
					date('Y-m-d',strtotime($emissao)),
					trim($cnpj_cpf),
					trim($tipo_v_t_a),
					str_replace('.','',($valortotanota/1000)),
					trim($gta),
					trim($numero_nota_produtor_ini),
					trim($numero_nota_produtor_fim),
					trim($tipo_s_n),
					trim($tipo_p_t),
					trim($ie_produtor),
					$_SESSION['cnpj']
				));

				echo "<tr>";
					echo "<td>{$numero_nota}<td>";
					echo "<td>{$emissao}<td>";
					echo "<td>{$cnpj_cpf}<td>";
					echo "<td>{$tipo_v_t_a}<td>";
					echo "<td>valor nota: ".str_replace('.','',($valortotanota/1000))."<td>";
					echo "<td>{$gta}<td>";
					echo "<td>{$numero_nota_produtor_ini}<td>";
					echo "<td>{$numero_nota_produtor_fim}<td>";
					echo "<td>{$tipo_s_n}<td>";
					echo "<td>{$tipo_p_t}<td>";
					echo "<td>{$ie_produtor}<td>";
				echo "</tr>";
				
			}
			echo "<table>";
			$keysnotasent = Array(
					"numero_nota", 
					"data_emissao", 
					"cnpj_cpf",
					"tipo_v_r_a",
					"valor_total_nota",
					"gta",
					"numero_nota_produtor_ini",
					"numero_nota_produtor_fin",
					"condenas",
					"abate",
					"insc_produtor",
					"cnpj_emp"
					);

				//$db->insertMulti('notasenttxt', $datanotasent, $keysnotasent);
				echo "<pre>";
				print_r ($db->trace);
				echo "</pre>";
		break;
		case 'search':
			
			$db = new MysqliDb(Array (
                'host' => 'localhost',
                'username' => 'root', 
                'password' => '',
                'db'=> 'agregar',
                'port' => 3306,
                'prefix' => '',
                'charset' => 'utf8'));

			$db->where ('cnpj_emp', '88728027000146');	
			$empresastxt = $db->get('empresastxt');
				

			$key = array_search('56527062003550',array_column($empresastxt, 'cnpj_cpf')); 					

			echo "<pre>";
			//print_r($key);
			print_r($empresastxt[$key]['insc_estadual']);
			echo "</pre>";	


		break;
		case 'testes2':

			/*$daonotasen1 = new NotasEn1Txt2DAO();
			$vetnotasen1 = $daonotasen1->VerificaCabecasPreenchidas('001171838','000230','88728027000146','006');
			$numnotasen1 = count($vetnotasen1);*/

			/*$daoprodfrig = new ProdFrigTxt2DAO();
			$daoprodfrig->ListaRelacionado('000230','88728027000146');

			echo "<pre>";
			print_r($vetnotasen1);
			echo "</pre>";*/

			$dao = new NotasSa1Txt2DAO();
			$dao->ListaNotasSa1Detalhe('88728027000146','11/2020');
		
		break;
		case 'te':
			$mesano         = !empty($_REQUEST['mesano']) ? $_REQUEST['mesano'] : $_SESSION['apura']['mesano'];
			$_SESSION['apura']['mesano'] = $mesano;
			$alert_nfunc    = array();
			$alert_vlfolha  = array();	
			$alert_icmsnorm = array();	
			$alert_icmsst   = array();	
			$alert_gta      = array();			
			$alert_dataabate= array();

			$erro           = array();
			$dados          = array();
			$err_cfop       = array();
			$err_produto    = array();	
			$err_nota       = array();
			$err_v_r	    = array();

			$xerro_notasent = array();
			$xerro_notassa1 = array();
			$xerro_notasen1 = array();
			$xerro_notassai = array();
			$err_dataabate 	= array();

			$numnotasenttxt = 0;
			$numnotasaitxt  = 0;

			$xerro_emp	  = array(); 

			$pathFile      = '../arquivos/'.$_SESSION['cnpj'].'/config.json';
			$configJson    = file_get_contents($pathFile);
			$installConfig = json_decode($configJson);

			$valid		  = new FuncoesDAO();
			$daop		  = new ProdutosAgregarDAO();
			$daoemp	      = new EmpresasTxtDAO();
			//$daoemp2      = new EmpresasTxt2DAO();
			$daocfopemp   = new CfopEmpresa2DAO();
			$daoprodfrig  = new ProdFrigTxt2DAO(); 
			$daocf  	  = new CfopDAO();
			$db = new MysqliDb(Array (
				'host' => ''.HOST.'',
				'username' => ''.USER.'', 
				'password' => ''.SENHA.'',
				'db'=> ''.BD.'',
				'port' => 3306,
				'prefix' => '',
				'charset' => 'utf8'));

			//$resultemp = $daoemp2->VerificaEmpresasCadastradas($_SESSION['cnpj']);	

			$resuprod    = $daoprodfrig->ListaRelacionadoTodos($_SESSION['cnpj']);				
			$results     = $daocfopemp->ListaCfopGeraAgregarSN($_SESSION['cnpj']);				
			$resultscfop = $daocfopemp->ListaCfopEmpresas($_SESSION['cnpj']);
			
			//echo "<pre>";
			//print_r($resultemp);
			//print_r ($db->trace);
			//echo "</pre>";

			//die();	
			

			$daonotasent = new NotasEntTxt2DAO();
			$vetnotasent = $daonotasent->ListaNotasDeEntradas($_SESSION['cnpj'],$mesano);
			$numnotasent = count($vetnotasent);

			for($i = 0; $i < $numnotasent; $i++){

				$notasent		   = $vetnotasent[$i];

				$id 		  	   = $notasent['id'];
				$numero_nota       = $notasent['numero_nota'];
				$data_emissao 	   = $notasent['data_emissao'];
				$cnpj_cpf	  	   = $notasent['cnpj_cpf'];
				$tipo_v_r_a	  	   = $notasent['tipo_v_r_a'];
				$valor_total_nota  = $notasent['valor_total_nota'];
				$gta			   = $notasent['gta'];
				$nota_produtor_ini = $notasent['numero_nota_produtor_ini'];
				$nota_produtor_fin = $notasent['numero_nota_produtor_fin'];
				$condenas		   = $notasent['condenas'];
				$abate			   = $notasent['abate'];
				$insc_produtor	   = $notasent['insc_produtor'];
				$cnpj_emp		   = $notasent['cnpj_emp'];
				$xml			   = $notasent['xml'];
				$chave_acesso	   = $notasent['chave_acesso'];
				$razao			   = $notasent['razao'];
				$indcpfcnpj		   = "";	
				$cpf			   = "";
				$cnpj              = "";  
				$xcnpj_cpf         = "";
				if(strlen($cnpj_cpf) == 11){
					$cpf = $cnpj_cpf;
					$xcnpj_cpf = $cpf;
				}else if(strlen($cnpj_cpf) == 14){
					$cnpj = $cnpj_cpf;
					$xcnpj_cpf = $cnpj;
				}
				
				## VALIDANDO CNPJ E CPF
				if(empty($cpf) AND empty($cnpj)){

					array_push($xerro_notasent, array(
						'id'=>$id,
						'msg' => 'Linha da nota: {'.$numero_nota .'} </b> - FALTA INFORMAR O CPF OU CNPJ',
						'CNPJCPF'=>''.$xcnpj_cpf.'',
						'tipo'=>'CPF'					
					));	
					
				}else{
				
					 $valid_cpf = $valid->Valida_Cnpj_Cpf($cpf);
					
					 if($valid_cpf == false){
						$indcpfcnpj = 0;		
						 $valid_cnpj_cpf = $valid->Valida_Cnpj_Cpf($cnpj);
						
						 if($valid_cnpj_cpf == false){
							$indcpfcnpj = 0;
														
						}else{
							$indcpfcnpj = 1;	
						}	
						
					}else{
						$indcpfcnpj = 1;
					}
						
						
					if($indcpfcnpj == 0){
						array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Linha da nota: {'.$numero_nota .'}</b> - CNPJ OU CPF INVALIDO!',
							'CNPJCPF'=>''.$xcnpj_cpf.'',
							'tipo'=>'CPF'					
						));
					
					}	
						
				}

				$vetemp = $daoemp->VerificaEmpresasCadastradas(trim($cnpj),trim($cpf),trim($insc_produtor),$_SESSION['cnpj']);
				$numemp = count($vetemp);
				
				if($numemp == 0){
					
					array_push($xerro_notasent, array(
						'msg' => 'Linha #<b>{'.$id.'}</b> - CNPJ/CPF/INSC [PRODUTOR] INEXISTE/CADASTRO EMPRESAS-> CNPJ/CPF-> ('.$xcnpj_cpf.') INSC-> '.trim($insc_produtor).' !',
						'tipo'=>'empresa'					
					));
						
				}
									
				## FIM DA VALIDAÇÃO CNPJ E CPF	
				
				## VALIDAÇÃO DO NUMERO DA NOTA 
				if(strlen(trim($numero_nota)) == 0){
						
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Linha #<b>{'.$id.'}</b> - NUMERO DA NOTA-> '.$numero_nota.' INVALIDO!',					
					));	
				
				}
				## FIM DA VALIDAÇÃO DO NUMERO DA NOTA
			
				## VALIDAÇÃO DA DATA DE EMISSÃO
				if(strlen(trim($data_emissao)) == 0 and $valid->ValidaData(date('d/m/Y',strtotime($data_emissao))) == false){
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Linha #<b>{'.($i + 1).'}</b> - DATA DE EMISSAO INVALIDA!',					
					));	
				}
				## FIM DA VALIDAÇÃO DA DATA DE EMISSÃO
				
				## VALIDAÇÃO VIVO OU REDIMENTO
				/*if($tipo_v_r_a != "V" and $tipo_v_r_a != "R" and $tipo_v_r_a != "A"){
					
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Número da nota ('.$numero_nota.') </b> - INDICADOR [V]IVO OU [R]ENDIMENTO->'.$tipo_v_r_a.'!',					
					));					
					
				}*/
				## FIM DA VALIDAÇÃO VIVO OU REDIMENTO
				
				## VALIDAÇÃO VALOR TOTAL DA NOTA
				if(trim($valor_total_nota) < '0.01'){
					
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Número da nota ('.$numero_nota.') </b> - VALOR TOTAL DA NOTA FISCAL ->'.number_format(floatval(trim($valortotanota)),2,',','.').'!',					
					));
					
				}
				## FIM DA VALIDAÇÃO VALOR TOTAL DA NOTA
				
				## VALIDAÇÃO DE CONDENAS SE SIM OU NÃO
				if(trim($condenas) != 'S' and trim($condenas) != 'N'){
				
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Número da nota ('.$numero_nota.') </b> - INDICADOR DE CONDENAS [S]IM [N]AO->'.$condenas.'!',					
					));
				}
				## FIM DA VALIDAÇÃO DE CONDENAS SE SIM OU NÃO
				
				## VALIDAÇÃO SE ABATE PROPRIO OU TERCEIRO
				if(trim($abate) != 'P' and trim($abate) != 'T'){
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Número da nota ('.$numero_nota.') </b> - INDICADOR DE ABATE [P]ROPRIO OU [T]ERCEIROS->'.$abate.'!',					
					));
				
				}
				## FIM DA VALIDAÇÃO SE ABATE PROPRIO OU TERCEIRO
				

				$daogta = new GtaTxtDAO();
				$vetgta = $daogta->GtaEmpresas($numero_nota,$_SESSION['cnpj']);
				$numgta = count($vetgta);
					
				if($numgta == 0){
					
					array_push($alert_gta,array(
							'codigo'=>''.$numero_nota.'',
							'msg'=>' Número da nota ('.$numero_nota.') Não existem numeros de GTA informados!',
					));
					
				}

			
				array_push($dados,array(
						
					'Numero'=>''.$numero_nota.'',
					'chave'=>'',
					'entsai'=>'ENTRADA',
					'cliente'=>'',
					'valor'=>''.number_format(doubleval($valor_total_nota),2,',','.').'',
					'caminho'=>'',
					'dEmi'=>''.date('d/m/Y',strtotime($data_emissao)).'',
					'msg'=>''									
				));

			}
			
			//print_r($err_nota);
		break;
		case 'validteste':

			$mesano         = !empty($_REQUEST['mesano']) ? $_REQUEST['mesano'] : $_SESSION['apura']['mesano'];
			$_SESSION['apura']['mesano'] = $mesano;
			$alert_nfunc    = array();
			$alert_vlfolha  = array();	
			$alert_icmsnorm = array();	
			$alert_icmsst   = array();	
			$alert_gta      = array();			
			$alert_dataabate= array();
			
			$erro           = array();
			$dados          = array();
			$err_cfop       = array();
			$err_produto    = array();	
			$err_nota       = array();
			$err_v_r	    = array();

			$xerro_notasent = array();
			$xerro_notassa1 = array();
			$xerro_notasen1 = array();
			$xerro_notassai = array();
			$err_dataabate 	= array();

			$numnotasenttxt = 0;
			$numnotasaitxt  = 0;
			
			$xerro_emp	  = array(); 
			
			$pathFile      = '../arquivos/'.$_SESSION['cnpj'].'/config.json';
			$configJson    = file_get_contents($pathFile);
			$installConfig = json_decode($configJson);

			$valid		  = new FuncoesDAO();
			$daop		  = new ProdutosAgregarDAO();
			$daoemp	      = new EmpresasTxtDAO();
			$daoprodfrig  = new ProdFrigTxt2DAO(); 
			$daocf  	  = new CfopDAO();
			$daocfopemp   = new CfopEmpresa2DAO();
			$daogta		  = new GtaTxt2DAO();

			$resuprod    = $daoprodfrig->ListaRelacionadoTodos($_SESSION['cnpj']);				
			$results     = $daocfopemp->ListaCfopGeraAgregarSN($_SESSION['cnpj']);				
			$resultscfop = $daocfopemp->ListaCfopEmpresas($_SESSION['cnpj']);
			$resultgta   = $daogta->GtaEmpresas($_SESSION['cnpj']);

			## COMEÇO DA VALIDAÇÃO DO ARQUIVO NOTASENT

			$daonotasent = new NotasEntTxt2DAO();
			$vetnotasent = $daonotasent->ListaNotasDeEntradas($_SESSION['cnpj'],$mesano);
			$numnotasent = count($vetnotasent);

			for($i = 0; $i < $numnotasent; $i++){

				$notasent		   = $vetnotasent[$i];

				$id 		  	   = $notasent['id'];
				$numero_nota       = $notasent['numero_nota'];
				$data_emissao 	   = $notasent['data_emissao'];
				$cnpj_cpf	  	   = $notasent['cnpj_cpf'];
				$tipo_v_r_a	  	   = $notasent['tipo_v_r_a'];
				$valor_total_nota  = $notasent['valor_total_nota'];
				$gta			   = $notasent['gta'];
				$nota_produtor_ini = $notasent['numero_nota_produtor_ini'];
				$nota_produtor_fin = $notasent['numero_nota_produtor_fin'];
				$condenas		   = $notasent['condenas'];
				$abate			   = $notasent['abate'];
				$insc_produtor	   = $notasent['insc_produtor'];
				$cnpj_emp		   = $notasent['cnpj_emp'];
				$xml			   = $notasent['xml'];
				$chave_acesso	   = $notasent['chave_acesso'];
				$razao			   = $notasent['razao'];
				$indcpfcnpj		   = "";	
				$cpf			   = "";
				$cnpj              = "";  
				$xcnpj_cpf         = "";
				if(strlen($cnpj_cpf) == 11){
					$cpf = $cnpj_cpf;
					$xcnpj_cpf = $cpf;
				}else if(strlen($cnpj_cpf) == 14){
					$cnpj = $cnpj_cpf;
					$xcnpj_cpf = $cnpj;
				}
				
				## VALIDANDO CNPJ E CPF
				if(empty($cpf) AND empty($cnpj)){

					array_push($xerro_notasent, array(
						'id'=>$id,
						'msg' => 'Linha da nota: {'.$numero_nota .'} </b> - FALTA INFORMAR O CPF OU CNPJ',
						'CNPJCPF'=>''.$xcnpj_cpf.'',
						'tipo'=>'CPF'					
					));	
					
				}else{
				
					 $valid_cpf = $valid->Valida_Cnpj_Cpf($cpf);
					
					 if($valid_cpf == false){
						$indcpfcnpj = 0;		
						 $valid_cnpj_cpf = $valid->Valida_Cnpj_Cpf($cnpj);
						
						 if($valid_cnpj_cpf == false){
							$indcpfcnpj = 0;
														
						}else{
							$indcpfcnpj = 1;	
						}	
						
					}else{
						$indcpfcnpj = 1;
					}
						
						
					if($indcpfcnpj == 0){
						array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Linha da nota: {'.$numero_nota .'}</b> - CNPJ OU CPF INVALIDO!',
							'CNPJCPF'=>''.$xcnpj_cpf.'',
							'tipo'=>'CPF'					
						));
					
					}	
						
				}

				$vetemp = $daoemp->VerificaEmpresasCadastradas(trim($cnpj),trim($cpf),trim($insc_produtor),$_SESSION['cnpj']);
				$numemp = count($vetemp);
				
				if($numemp == 0){
					
					array_push($xerro_notasent, array(
						'msg' => 'Linha #<b>{'.$id.'}</b> - CNPJ/CPF/INSC [PRODUTOR] INEXISTE/CADASTRO EMPRESAS-> CNPJ/CPF-> ('.$xcnpj_cpf.') INSC-> '.trim($insc_produtor).' !',
						'tipo'=>'empresa'					
					));
						
				}
									
				## FIM DA VALIDAÇÃO CNPJ E CPF	
				
				## VALIDAÇÃO DO NUMERO DA NOTA 
				if(strlen(trim($numero_nota)) == 0){
						
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Linha #<b>{'.$id.'}</b> - NUMERO DA NOTA-> '.$numero_nota.' INVALIDO!',					
					));	
				
				}
				## FIM DA VALIDAÇÃO DO NUMERO DA NOTA
			
				## VALIDAÇÃO DA DATA DE EMISSÃO
				if(strlen(trim($data_emissao)) == 0 and $valid->ValidaData(date('d/m/Y',strtotime($data_emissao))) == false){
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Linha #<b>{'.($i + 1).'}</b> - DATA DE EMISSAO INVALIDA!',					
					));	
				}
				## FIM DA VALIDAÇÃO DA DATA DE EMISSÃO
				
				## VALIDAÇÃO VIVO OU REDIMENTO
				/*if($tipo_v_r_a != "V" and $tipo_v_r_a != "R" and $tipo_v_r_a != "A"){
					
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Número da nota ('.$numero_nota.') </b> - INDICADOR [V]IVO OU [R]ENDIMENTO->'.$tipo_v_r_a.'!',					
					));					
					
				}*/
				## FIM DA VALIDAÇÃO VIVO OU REDIMENTO
				
				## VALIDAÇÃO VALOR TOTAL DA NOTA
				if(trim($valor_total_nota) < '0.01'){
					
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Número da nota ('.$numero_nota.') </b> - VALOR TOTAL DA NOTA FISCAL ->'.number_format(floatval(trim($valortotanota)),2,',','.').'!',					
					));
					
				}
				## FIM DA VALIDAÇÃO VALOR TOTAL DA NOTA
				
				## VALIDAÇÃO DE CONDENAS SE SIM OU NÃO
				if(trim($condenas) != 'S' and trim($condenas) != 'N'){
				
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Número da nota ('.$numero_nota.') </b> - INDICADOR DE CONDENAS [S]IM [N]AO->'.$condenas.'!',					
					));
				}
				## FIM DA VALIDAÇÃO DE CONDENAS SE SIM OU NÃO
				
				## VALIDAÇÃO SE ABATE PROPRIO OU TERCEIRO
				if(trim($abate) != 'P' and trim($abate) != 'T'){
					array_push($xerro_notasent, array(
							'id'=>$id,
							'msg' => 'Número da nota ('.$numero_nota.') </b> - INDICADOR DE ABATE [P]ROPRIO OU [T]ERCEIROS->'.$abate.'!',					
					));
				
				}
				## FIM DA VALIDAÇÃO SE ABATE PROPRIO OU TERCEIRO
				
				if(!empty($resultgta)){
					$keyshta = array_search($numero_nota,array_column($resultgta, 'numero_nota'));
					if(trim($keyshta) == ""){
						array_push($alert_gta,array(
							'codigo'=>''.$numero_nota.'',
							'msg'=>' Número da nota ('.$numero_nota.') Não existem numeros de GTA informados!',
					));
					}
				}

				/*$daogta = new GtaTxtDAO();
				$vetgta = $daogta->GtaEmpresas($numero_nota,$_SESSION['cnpj']);
				$numgta = count($vetgta);
					
				if($numgta == 0){
					
					array_push($alert_gta,array(
							'codigo'=>''.$numero_nota.'',
							'msg'=>' Número da nota ('.$numero_nota.') Não existem numeros de GTA informados!',
					));
					
				}*/

			
				array_push($dados,array(
						
					'Numero'=>''.$numero_nota.'',
					'chave'=>'',
					'entsai'=>'ENTRADA',
					'cliente'=>'',
					'valor'=>''.number_format(doubleval($valor_total_nota),2,',','.').'',
					'caminho'=>'',
					'dEmi'=>''.date('d/m/Y',strtotime($data_emissao)).'',
					'msg'=>''									
				));

			}
			## FIM DA VALIDAÇÃO DO ARQUIVO NOTASENT

			## COMEÇO DA VALIDAÇÃO DO ARQUIVO NOTASEN1
			
			$daonotasen1 = new NotasEn1Txt2DAO();
			$vetnotasen1 = $daonotasen1->ListaNotasEntradaDetalhe($_SESSION['cnpj'],$mesano);
			$numnotasen1 = count($vetnotasen1);

			for($x = 0; $x < $numnotasen1; $x++){

				$notasen1		  = $vetnotasen1[$x];

				$id_det 		  	  = $notasen1['id'];
				$numero_nota_det  	  = $notasen1['numero_nota'];
				$data_emissao_det 	  = $notasen1['data_emissao'];
				$cnpj_cpf_det	  	  = $notasen1['cnpj_cpf'];
				$codigo_produto_det   = $notasen1['codigo_produto'];
				$qtd_cabeca_det    	  = $notasen1['qtd_cabeca'];
				$peso_vivo_cabeca_det = $notasen1['peso_vivo_cabeca'];
				$peso_carcasa_det	  = $notasen1['peso_carcasa'];
				$preco_quilo_det	  = $notasen1['preco_quilo'];
				$numero_item_nota_det = $notasen1['numero_item_nota'];
				$insc_estadual_det	  = $notasen1['insc_estadual'];
				$data_abate_det		  = $notasen1['data_abate'];
				$tipo_r_v_det		  = $notasen1['tipo_r_v'];
				$cfop_det			  = $notasen1['cfop'];
				$aliquota_icms_det	  = $notasen1['aliquota_icms'];
				$cnpj_emp_det		  = $notasen1['cnpj_emp'];
				$indcpfcnpj			  = "";
				$cpf			      = "";
				$cnpj                 = "";

				if(strlen($cnpj_cpf_det) == 11){
					$cpf = $cnpj_cpf_det;
				}else if(strlen($cnpj_cpf_det) == 14){
					$cnpj = $cnpj_cpf_det;
				}

				if(empty($cpf) and empty($cnpj)){
					array_push($xerro_notasen1, array(
						'msg' => 'Linha da nota: {'.$numero_nota_det .'} </b> - FALTA INFORMAR O CPF OU CNPJ',					
					));	
					
				}else{
				
					 $valid_cpf = $valid->Valida_Cnpj_Cpf($cpf);
					
					 if($valid_cpf == false){
						$indcpfcnpj = 0;		
						 $valid_cnpj_cpf = $valid->Valida_Cnpj_Cpf($cnpj);
						
						 if($valid_cnpj_cpf == false){
							$indcpfcnpj = 0;
														
						}else{
							$indcpfcnpj = 1;	
						}	
						
					}else{
						$indcpfcnpj = 1;
					}
						
						
					if($indcpfcnpj == 0){
						array_push($xerro_notasen1, array(
							'msg' => 'Linha da nota: {'.$numero_nota_det .'} </b> - CNPJ OU CPF INVALIDO!',					
						));
					
					}	
						
				}

				## VALIDAÇÃO DO NUMERO DA NOTA
				if(strlen(trim($numero_nota_det)) < 6){
							
					array_push($xerro_notasen1, array(
							'id'=>$id_det,
							'msg' => 'Linha #<b>{'.$id_det.'}</b> - NUMERO DA NOTA-> '.$numero_nota_det.' !',					
					));	
				
				}	
				## FIM DA VALIDAÇÃO DO NUMERO DA NOTA

				## VALIDAÇÃO DA DATA DE EMISSÃO	
				if(strlen(trim($data_emissao_det)) == 0 and $valid->ValidaData(date('d/m/Y',strtotime($data_emissao_det))) == false){
					array_push($xerro_notasen1, array(
							'id'=>$id_det,	
							'msg' => 'Linha #<b>{'.$id.'}</b> - DATA DE EMISSAO INVALIDA!',					
					));	
				}	
				## FIM DA VALIDAÇÃO DA DATA DE EMISSÃO				

				if(!empty($data_abate_det) and $data_abate_det != '0000-00-00'){
					if(date('m/Y',strtotime($data_abate_det)) != $_SESSION['apura']['mesano']){
						if(!empty($resuprod)){
							$keysp = array_search(trim($codigo_produto_det),array_column($resuprod, 'cod_prod'));
							if(trim($keysp) != ""){
								$desc_prod = $resuprod[$keysp]['desc_prod'];
							}else{
								$desc_prod = "";
							}
						}
						//alerta de diferente da data da competencia
						array_push($alert_dataabate,array(
							'codigo'=>''.$numero_nota_det.'',
							'cProd'=>''.trim($codigo_produto_det).'',
							'dProd'=>'('.trim($codigo_produto_det).') - '.$desc_prod.'',											
							'data_emissao'=>date('m/Y',strtotime($data_emissao_det)),
							'data_abate'=>date('d/m/Y',strtotime($data_abate_det)),
							'numero_nota'=>''.$numero_nota_det.'',
							'entsai'=>'Entrada',
							'idseq'=>$numero_item_nota_det,
							'msg'=>' Data do abate diferente da data da competência => Data Competência: '.date('m/Y',strtotime($data_emissao_det)).' Data Abate informada: '.date('d/m/Y',strtotime($data_abate_det)).' Numero Nota: '.$numero_nota_det.' Produto: ('.trim($codigo_produto_det).' '.$desc_prod.') ',									
						));
					}
				}else{
					if(!empty($resuprod)){
						$keysp = array_search(trim($codigo_produto_det),array_column($resuprod, 'cod_prod'));
						if(trim($keysp) != ""){
							$desc_prod = $resuprod[$keysp]['desc_prod'];
						}else{
							$desc_prod = "";
						}
					}
					array_push($err_dataabate,array(
						'codigo'=>''.$numero_nota_det.'',
						'cProd'=>''.trim($codigo_produto_det).'',
						'dProd'=>'('.trim($codigo_produto_det).') - '.$desc_prod.'',											
						'data_emissao'=>date('m/Y',strtotime($data_emissao_det)),
						'data_abate'=>date('d/m/Y',strtotime($data_abate_det)),
						'numero_nota'=>''.$numero_nota_det.'',
						'entsai'=>'Entrada',
						'idseq'=>$numero_item_nota_det,
						'msg'=>'Data do abate não informado numero nota: '.$numero_nota_det.' Produto: ('.trim($codigo_produto_det).' '.$desc_prod.') ',									
					));
				}
				

				## VALIDAÇÃO PREÇO QUILO
				if(trim($preco_quilo_det) < 0.01){
					
					array_push($xerro_notasen1, array(
						'id'=>$id_det,
						'msg' => 'Linha #<b>{'.$id_det.'}</b> - NOTA: '.$numero_nota1.' VALOR: '.$preco_quilo_det.' PRE€O POR QUILO INFORMADO INCONSISTENTE !',					
					));
				}
				## FIM DA VALIDAÇÃO PREÇO QUILO
				
				if(!empty($resuprod)){
					$keysp = array_search(trim($codigo_produto_det),array_column($resuprod, 'cod_prod'));
					if(trim($keysp) == ""){
						array_push($err_produto,array(
							'codigo'=>''.trim($codigo_produto_det).'',
							'cnota'=>''.trim($numero_nota_det).'',
							'msg'=>' Código do produto ('.trim($codigo_produto_det).') :: Produto não relacionado com o produto do agregar ::',
							"dados"=> array(
								'nNF'=>''.$numero_nota_det.'',
								'entsai'=>'Entrada',
								'cliente'=>'',
								'valor'=>'',
								'demi'=>''.date('d/m/Y',strtotime($data_emissao_det)).''
							),
						));
					}else{
						$cod_secretaria = $resuprod[$keysp]['cod_secretaria'];
						$desc_prod      = $resuprod[$keysp]['desc_prod'];		

						if($cod_secretaria == ""){
							array_push($err_produto,array(
								'codigo'=>''.trim($codigo_produto_det).'',
								'cnota'=>''.trim($numero_nota_det).'',
								'msg'=>' Código do produto ('.trim($codigo_produto_det).' '.$desc_prod.') :: Produto não relacionado com o produto do agregar ::',
								"dados"=> array(
									'nNF'=>''.$numero_nota_det.'',
									'entsai'=>'Saida',
									'cliente'=>'',
									'valor'=>'',
									'demi'=>''.date('d/m/Y',strtotime($data_emissao_det)).''
								),
							));
						}
					}
				}
				
				if(!empty($resultscfop)){
					$keys = array_search($cfop_det,array_column($resultscfop, 'Codigo'));
					if(trim($keys) != ""){
						$codcfop   = $resultscfop[$keys]['Codigo'];
						$nomcfop   = $resultscfop[$keys]['Nome'];
						$geraagreg = $resultscfop[$keys]['gera'];
						$vinculado = $resultscfop[$keys]['vinculado'];
						$idvinculo = $resultscfop[$keys]['idvinculado'];

						if(empty($geraagreg)){
							$err_cfop[] = array(
								'idvinc'=>''.$idvinculo.'',
								'codigo'=>''.trim($cfop_det).'',
								'nota'=>''.trim($numero_nota_det).'',
								'arquivo'=>'',
								'msg'=>' CFOP ('.trim($cfop_det).') sem indicação se irá "Considerar" ou "Desconsiderar" na apuração de crédito.',
							);
						}
					}else{
						$err_cfop[] = array(
							'codigo'=>''.trim($cfop_det).'',
							'msg'=>' CFOP ('.trim($cfop_det).') não Existe no sistema ou não esta gravado!  '
						);	
					}
				}
				

				if(!empty($resuprod)){
					$keycab = array_search(trim($codigo_produto_det),array_column($resuprod, 'cod_prod'));
					if(trim($keycab) != ""){
						$cod_secretaria = $resuprod[$keycab]['cod_secretaria'];
					}else{
						$cod_secretaria = "";
					}
				}
				
				if($cod_secretaria != '99999' and $cod_secretaria != '99998' and $cod_secretaria != '99997' and $cod_secretaria != '99996'){
					
					if(empty($qtd_cabeca_det) || (int)$qtd_cabeca_det <= 0){
						
						array_push($err_nota,array(
							'codigo'=>''.$numero_nota_det.'',
							'cProd'=>''.trim($codigo_produto_det).'',
							'idseq'=>$numero_item_nota_det,
							'entsai'=>'Entrada',
							'msg'=>' Número da nota ('.$numero_nota_det.') # Linha do produto ('.trim($codigo_produto_det).') esta faltando numero de cabeças a ser preenchido!',										
						));
					
					}

					if(empty($tipo_r_v_det)){
						array_push($err_v_r,array(
							'codigo'=>''.$numero_nota_det.'',
							'cProd'=>''.trim($codigo_produto_det).'',
							'qCom'=>''.number_format(doubleval($qtd_cabeca_det),2,',','.').'',
							'entsai'=>'Entrada',
							'idseq'=>$numero_item_nota_det,
							'msg'=>' Número da nota ('.$numero_nota_det.') # Linha do produto ('.trim($codigo_produto_det).') <br/> Esta faltando Informar se é Vivo ou Rendimento!',									
						));
						
					}

					if(trim($peso_carcasa_det) < 001 and $tipo_r_v_det == 'R'){
					
						array_push($xerro_notasen1, array(
							'codigo'=>''.$numero_nota_det.'',
							'cProd'=>''.trim($codigo_produto_det).'',
							'idseq'=>$numero_item_nota_det,
							'entsai'=>'Entrada',
							'msg' => 'Número da nota ('.$numero_nota_det.') # Linha do produto ('.trim($codigo_produto_det).') Esta faltando Informar peso das carcaça '.number_format($peso_carcasa_det,3,',','.').' !',					
						));

					}

				}

			}

			## FIM DA VALIDAÇÃO DO ARQUIVO NOTASEN1
			
			## COMEÇO DA VALIDAÇÃO DO ARQUIVO NOTASSAI

			$daonotassaitxt = new NotasSaiTxt2DAO();
			$vetnotassaitxt = $daonotassaitxt->ListandoNotasSai($mesano,$_SESSION['cnpj']);
			$numnotassaitxt = count($vetnotassaitxt);

			for($y = 0; $y < $numnotassaitxt; $y++){

				$notasai		  = $vetnotassaitxt[$y];

				$id 		  	  =	$notasai['id'];
				$numero_nota  	  = $notasai['numero_nota'];
				$data_emissao 	  = $notasai['data_emissao'];
				$cnpj_cpf	  	  = $notasai['cnpj_cpf'];
				$valor_total_nota = $notasai['valor_total_nota'];	
				$valor_icms		  = $notasai['valor_icms'];
				$valor_icms_subs  = $notasai['valor_icms_subs'];
				$ent_sai		  = $notasai['ent_sai'];
				$insc_estadual	  = $notasai['insc_estadual'];
				$cfop			  = $notasai['cfop'];
				$razao			  = $notasai['razao'];
				$indcpfcnpj       = "";
				$cpf			  = "";
				$cnpj             = "";

				if(strlen($cnpj_cpf) == 11){
					$cpf = $cnpj_cpf;
				}else if(strlen($cnpj_cpf) == 14){
					$cnpj = $cnpj_cpf;
				}

				## VALIDANDO CNPJ E CPF
				if(empty($cnpj_cpf)){
					array_push($xerro_notassai, array(
						'msg' => 'Linha da nota: {'.$numero_nota .'} </b> - CNPJ OU CPF INVALIDO!',					
					));	
					
				}else{
				
					 $valid_cpf = $valid->Valida_Cnpj_Cpf($cpf);
					
					 if($valid_cpf == false){
						 
						 $indcpfcnpj = 0;		
						 $valid_cnpj_cpf = $valid->Valida_Cnpj_Cpf($cnpj);
						
						 if($valid_cnpj_cpf == false){
							$indcpfcnpj = 0;
														
						}else{
							$indcpfcnpj = 1;	
						}	
						
					}else{
						$indcpfcnpj = 1;
					}
						
						
					if($indcpfcnpj == 0){
						array_push($xerro_notassai, array(
							'msg' => 'Linha #<b>{'.($y + 1).'}</b> - CNPJ OU CPF INVALIDO!',					
						));
					
					}	
						
				}
				
				$vetemp = $daoemp->VerificaEmpresasCadastradas(trim($cnpj),trim($cpf),trim($insc_estadual),$_SESSION['cnpj']);
				$numemp = count($vetemp);
				
				if($numemp == 0){
					
					array_push($xerro_notassai, array(
						'id'=>$id,
						'msg' => 'Linha #<b>{'.($y + 1).'}</b> - CNPJ/CPF/INSC [PRODUTOR] INEXISTE/CADASTRO DE EMPRESAS-> ('.$cnpj_cpf.') INSC-> '.trim($insc_estadual).' !',					
					));
						
				}
									
				## FIM DA VALIDAÇÃO CNPJ E CPF
				
				## VALIDAÇÃO DO NUMERO DA NOTA 
				if(strlen(trim($numero_nota)) < 6){
						
					array_push($xerro_notassai, array(
							'id'=>$id,
							'msg' => 'Linha #<b>{'.$id.'}</b> - NUMERO DA NOTA-> '.$numero_nota.' !',					
					));	
				
				}	
				## FIM VALIDAÇÃO DO NUMERO DA NOTA 
				
				## VALIDAÇÃO DA DATA DE EMISSÃO 
				if(strlen(trim($data_emissao)) == 0 and $valid->ValidaData(date('d/m/Y',strtotime($data_emissao))) == false){
					array_push($xerro_notassai, array(
							'id'=>$id,
							'msg' => 'Linha #<b>{'.($y + 1).'}</b> - DATA DE EMISSAO INVALIDA!',					
					));	
				}	
				
				## FIM VALIDAÇÃO DA DATA DE EMISSÃO
				
				## VALIDAÇÃO DO TOTA DA NOTA 
				if(trim($valor_total_nota) < 0.01){
					
					array_push($xerro_notassai, array(
							'id'=>$id,
							'msg' => 'Número da nota:'.$numero_nota.' </b> - VALOR TOTAL DA NOTA FISCAL-> '.number_format(trim($valor_total_nota),2,',','.').' INVÁLIDO !',					
					));
				
				}
				## FIM DO TOTA DA NOTA 
				
				## VALIDAÇÃO DO VALOR ICMS NORMAL
				if(trim($valor_icms) < 0.00){
						
					array_push($xerro_notassai, array(
							'id'=>$id,
							'msg' => 'Número da nota:'.$numero_nota.' </b> - VALOR DO ICMS NORMAL-> '.number_format(trim($valor_icms),2,',','.').' INVÁLIDO !',					
					));	
					
				}
				## FIM VALIDAÇÃO DO VALOR ICMS NORMAL
				
				## VALIDAÇÃO DO VALOR ICMS SUBSTUIÇÃO
				if(trim($valor_icms_subs) < 0.00){
					
					array_push($xerro_notassai, array(
							'id'=>$id,
							'msg' => 'Número da nota:'.$numero_nota.' </b> - VALOR DO ICMS SUBSTIT-> '.number_format(trim($valor_icms_subs),2,',','.').' INVÁLIDO !',					
					));	
					
				}
				## FIM VALIDAÇÃO DO VALOR ICMS SUBSTUIÇÃO
				
				## VALIDAÇÃO ENTRADA E SAIDA
				if(trim($ent_sai) != "E" and trim($ent_sai) != "S"){
					array_push($xerro_notassai, array(
							'id'=>$id,
							'msg' => 'Número da nota:'.$numero_nota.' </b> - INDICADOR DE [E]NTRADA [S]AIDA -> '.$ent_sai.' !',					
					));
					
				}

				array_push($dados,array(
					'Numero'=>''.$numero_nota.'',
					'chave'=>'',
					'entsai'=>'SAIDA',
					'cliente'=>'',
					'valor'=>''.number_format(doubleval($valor_total_nota),2,',','.').'',
					'caminho'=>'',
					'dEmi'=>''.date('d/m/Y',strtotime($data_emissao)).'',	
					'msg'=>''								
				));

			}
			## FIM DA VALIDAÇÃO DO ARQUIVO NOTASSAI
			
			## COMEÇO DA VALIDAÇÃO DO ARQUIVO NOTASSA1

			$daonotassai1 = new NotasSa1Txt2DAO();
			$vetnotassai1 = $daonotassai1->ListaNotasSa1Detalhe($_SESSION['cnpj'],$mesano);
			$numnotassai1 = count($vetnotassai1);

			for($z = 0; $z < $numnotassai1; $z++){

				$notasa1		  = $vetnotassai1[$z];

				$id 		  	  = $notasa1['id'];
				$numero_nota      = $notasa1['numero_nota'];
				$data_emissao 	  = $notasa1['data_emissao'];
				$cnpj_cpf	      = $notasa1['cnpj_cpf'];
				$codigo_produto   = $notasa1['codigo_produto'];
				$qtd_pecas		  = $notasa1['qtd_pecas'];
				$peso			  = $notasa1['peso'];
				$preco_unitario   = $notasa1['preco_unitario'];
				$ent_sai		  = $notasa1['ent_sai'];
				$numero_item_nota = $notasa1['numero_item_nota'];		
				$insc_estadual    = $notasa1['insc_estadual'];
				$cfop			  = $notasa1['cfop'];
				$aliquota_icms	  = $notasa1['aliquota_icms'];
				$indcpfcnpj       = "";
				$cnpj			  = "";
				$cpf			  = "";

				if(strlen($cnpj_cpf) == '11'){
					$cpf  = $cnpj_cpf; 
				}else{
					$cnpj = $cnpj_cpf;
				}

					## VALIDANDO CNPJ E CPF
					if(empty($cnpj_cpf)){
						array_push($xerro_notassa1, array(
							'msg' => 'Linha da nota: {'.$numero_nota .'} </b> - CNPJ OU CPF INVALIDO!',					
						));	
						
					}else{
					
						$valid_cpf = $valid->Valida_Cnpj_Cpf($cpf);
						
						if($valid_cpf == false){
							
							$indcpfcnpj = 0;		
							$valid_cnpj_cpf = $valid->Valida_Cnpj_Cpf($cnpj);
							
							if($valid_cnpj_cpf == false){
								$indcpfcnpj = 0;
															
							}else{
								$indcpfcnpj = 1;	
							}	
							
						}else{
							$indcpfcnpj = 1;
						}
							
							
						if($indcpfcnpj == 0){
							array_push($xerro_notassa1, array(
								'msg' => 'Linha #<b>{'.($z + 1).'}</b> - CNPJ OU CPF INVALIDO!',					
							));
						
						}	
							
					}	

														
					
					$vetemp = $daoemp->VerificaEmpresasCadastradas(trim($cnpj),trim($cpf),trim($insc_estadual),$_SESSION['cnpj']);
					$numemp = count($vetemp);
					
					if($numemp == 0){
						
						array_push($xerro_notassa1, array(
							'msg' => 'Linha #<b>{'.($z + 1).'}</b> - CNPJ/CPF/INSC [PRODUTOR] INEXISTE/CADASTRO DE EMPRESAS-> ('.$cnpj_cpf.') INSC-> '.trim($insc_estadual).' !',					
						));
							
					}
					
									
					## FIM DA VALIDAÇÃO CNPJ E CPF
					
					## VALIDAÇÃO DO NUMERO DA NOTA 
					if(strlen(trim($numero_nota)) < 6){
							
						array_push($xerro_notassa1, array(
								'id'  => $id,
								'msg' => 'Linha #<b>{'.$id.'}</b> - NUMERO DA NOTA-> '.$numero_nota.' !',					
						));	
					
					}	
					## FIM VALIDAÇÃO DO NUMERO DA NOTA 
					
					## VALIDAÇÃO DA DATA DE EMISSÃO 
					if(strlen(trim($data_emissao)) == 0 and $valid->ValidaData(date('d/m/Y',strtotime($data_emissao))) == false){
						array_push($xerro_notassa1, array(
								'id'  => $id,
								'msg' => 'Número da nota: '.$numero_nota.' </b> - DATA DE EMISSAO INVALIDA!',					
						));	
					}						
					## FIM VALIDAÇÃO DA DATA DE EMISSÃO
					
					## VALIDAÇÃO DE QUANTIDADE DE PEÇAS
					if(trim($qtd_pecas) < 0){
						array_push($xerro_notassa1, array(
								'id'  => $id,
								'msg' => 'Número da nota: '.$numero_nota.' </b> - QUANTIDADE DE PECAS INFORMADAS INCONSISTENTE!',					
						));
					
					}
					## FIM DA VALIDAÇÃO DE QUANTIDADE DE PEÇAS
					
					## VALIDAÇÃO DO PESO
					if(!empty($results)){
						$key = array_search($cfop,array_column($results, 'id_cfop'));
						if(trim($key) != ""){
							$sn = $results[$key]['sn'];		
							if($sn == 'sim'){
								if(floatval(trim($peso)) <= 0){
									array_push($xerro_notassa1, array(
											'id'  => $id,
											'msg' => 'Número da nota: '.$numero_nota.' </b> - PESO INFORMADO INCONSISTENTE !',					
									));
									
								}	
							}
						}else{
							if(floatval(trim($peso)) <= 0){
								array_push($xerro_notassa1, array(
										'id'  => $id,
										'msg' => 'Número da nota: '.$numero_nota.' </b> - PESO INFORMADO INCONSISTENTE !',					
								));
								
							}
						}
					}

					
					
					## FIM DA VALIDAÇÃO DO PESO
					
					## VALIDAÇÃO DO PRECO UNITARIO
					if(trim($preco_unitario) < 0){
						
						array_push($xerro_notassa1, array(
								'id'  => $id,
								'msg' => 'Número da nota: '.$numero_nota.' </b> - PRE€O POR QUILO INFORMADO INCONSISTENTE !',					
						));
					}
					## FIM DA VALIDAÇÃO DO PRECO UNITARIO
					
					## VALIDAÇÃO ENTRADA E SAIDA
					if(trim($ent_sai) != "E" and trim($ent_sai) != "S"){
						array_push($xerro_notassa1, array(
								'id'  => $id,
								'msg' => 'Número da nota: '.$numero_nota.' </b> - INDICADOR DE [E]NTRADA [S]AIDA -> '.$ent_sai.' !',					
						));
						
					}
					## FIM VALIDAÇÃO ENTRADA E SAIDA

					if(!empty($resultscfop)){

						$keys = array_search($cfop,array_column($resultscfop, 'Codigo'));
						if(trim($keys) != ""){
							$codcfops   = $resultscfop[$keys]['Codigo'];
							$nomcfops   = $resultscfop[$keys]['Nome'];
							$geraagregs = $resultscfop[$keys]['gera'];
							$vinculados = $resultscfop[$keys]['vinculado'];
							$idvinculos = $resultscfop[$keys]['idvinculado'];
							
							if($geraagregs != 2){

								//$resuprod
								if(!empty($resuprod)){
									$keysp = array_search(trim($codigo_produto),array_column($resuprod, 'cod_prod'));
									if(trim($keysp) == ""){

										array_push($err_produto,array(
											'codigo'=>''.trim($codigo_produto).'',
											'cnota'=>''.trim($numero_nota).'',
											'msg'=>' Código do produto ('.trim($codigo_produto).') :: Produto não relacionado com o produto do agregar ::',
											"dados"=> array(
												'nNF'=>''.$numero_nota.'',
												'entsai'=>'Saida',
												'cliente'=>'',
												'valor'=>'',
												'demi'=>''.date('d/m/Y',strtotime($data_emissao)).''
											),
										));
									}else{

										$cod_secretaria = $resuprod[$keysp]['cod_secretaria'];
										$desc_prod      = $resuprod[$keysp]['desc_prod'];		

										if($cod_secretaria == ""){
											array_push($err_produto,array(
												'codigo'=>''.trim($codigo_produto).'',
												'cnota'=>''.trim($numero_nota).'',
												'msg'=>' Código do produto ('.trim($codigo_produto).' '.$desc_prod.') :: Produto não relacionado com o produto do agregar ::',
												"dados"=> array(
													'nNF'=>''.$numero_nota.'',
													'entsai'=>'Saida',
													'cliente'=>'',
													'valor'=>'',
													'demi'=>''.date('d/m/Y',strtotime($data_emissao)).''
												),
											));
										}

									}
								}

							}
							
							if(empty($geraagregs)){
								$err_cfop[] =array(
									'idvinc'=>''.$idvinculos.'',
									'codigo'=>''.trim($cfop).'',
									'nota'=>''.trim($numero_nota).'',
									'arquivo'=>'',
									'msg'=>' CFOP ('.trim($cfop).') sem indicação se irá "Considerar" ou "Desconsiderar" na apuração de crédito.',
								);
							}	

						}else{
							$err_cfop[] =array(
								'codigo'=>''.trim($cfop).'',
								'msg'=>' CFOP ('.trim($cfop).') não Existe no sistema ou não esta gravado!  '
							);	
						}

					}
					

			}


			$daofolha = new FolhaTxtDAO();
			$vetfolha = $daofolha->ValidaFolhaMes($_SESSION['cnpj'],$_SESSION['apura']['mesano']);	
			$numfolha = count($vetfolha);
				
			if($numfolha > 0){				
				
				$folha = $vetfolha[0];
				
				$codfolha         = $folha->getCodigo();
				$num_funcionarios = $folha->getNumFuncionario();
				$valor_folha 	  = $folha->getValorFolha();
				
				if(empty($num_funcionarios)){
					array_push($alert_nfunc,array(
						'id'=>''.$codfolha.'',
						'msg'=>'Falta informar o numero de funcionários!',
					));											
				}
				
				if(empty($valor_folha)){
					array_push($alert_vlfolha,array(
						'id'=>''.$codfolha.'',
						'msg'=>'Falta informar valor da folha!',
					));										
				}								
			}else{
				
				array_push($alert_nfunc,array(
						'id'=>'',
						'msg'=>'Falta informar o numero de funcionários!',
				));
				
				array_push($alert_vlfolha,array(
						'id'=>'',
						'msg'=>'Falta informar valor da folha!',
				));
			}					
				
			

			$daoprot = new ProtocoloDAO();
			$vetprot = $daoprot->ListaProtocoloPorEmmpresaCompetencia($_SESSION['apura']['mesano'],$_SESSION['cnpj']);
			$numprot = count($vetprot);

			if($numprot > 0){

					$prot   = $vetprot[0];
					
					$idprot = $prot->getCodigo();	

					$prots  = new Protocolo();

					$prots->setCodigo($idprot);
					$prots->setStatus(7);	

					$daoprot->updateStatus($prots);	

			}else{

				$prot = new Protocolo();
						
				$prot->setCompetencia($_SESSION['apura']['mesano']);
				$prot->setProtocolo('');
				$prot->setCripty('');
				$prot->setStatus(7);
				$prot->setCnpjEmp($_SESSION['cnpj']);	
				$prot->setTipoArq(2);

				$daoprot->inserir($prot);

			}


			$daoguia 	 =  new GuiaicmsDAO();
			$vetguianorm = $daoguia->ValidGuiaicmsNormal($_SESSION['cnpj'],$_SESSION['apura']['mesano']);	
			$numguianorm = count($vetguianorm);	
				
			if($numguianorm == 0){
				
				array_push($alert_icmsnorm,array(
					'msg'=>'Valor do ICMS normal não informado !',							
				));
			}	
				
			$vetguiast = $daoguia->ValidGuiaicmsSt($_SESSION['cnpj'],$_SESSION['apura']['mesano']);
			$numguiast = count($vetguiast);	
			
			if($numguiast == 0){
				
				array_push($alert_icmsst,array(
					'msg'=>'Valor do ICMS ST não informado !',
				));
			}	
				
			$erro_cfop = $valid->array_sort($err_cfop,'codigo',SORT_ASC);
			$cfop_erro = array();	
			foreach($erro_cfop as $key=>$value){				
				array_push($cfop_erro,$value);					
			}
				
			$erro_produto = $valid->array_sort($err_produto,'codigo',SORT_ASC);	
			$produto_erro = array();	
			foreach($erro_produto as $keys=>$values){
				array_push($produto_erro,$values);
				
			}
				
			$num_tota_erros = count($xerro_notasent)  + count($xerro_emp) + count($xerro_notasen1) + count($xerro_notassai) + count($xerro_notassa1) + count($cfop_erro) + count($produto_erro) + count($err_nota) + count($err_v_r) + count($err_dataabate);	
							
			$data = array(
					'erro'=>array(
						'cfop'=>$cfop_erro,
						'produto'=>$produto_erro,
						'nota'=>$err_nota,
						'vivorendmento'=>$err_v_r,
						'abate'=>$err_dataabate,
					),
					'info'=>array(
						'funcionario'=>$alert_nfunc,
						'folha'=>$alert_vlfolha,
						'icmsnormal'=>$alert_icmsnorm,
						'icmsst'=>$alert_icmsst,
						'gta'=>$alert_gta,
						'infabate'=>$alert_dataabate,
						'num_entrada'=>$numnotasent,
						'num_saida'=>$numnotassaitxt
					),
					'dados_grid'=>$dados,
					'xerro_notasent'=>$xerro_notasent,					
					'xerro_emp'=>$xerro_emp,
					'xerro_notasen1'=>$xerro_notasen1,
					'xerro_notassai'=>$xerro_notassai,
					'xerro_notassa1'=>$xerro_notassa1, 	
					'num_tota_erros'=>$num_tota_erros,);		
				
			echo json_encode($data);

		break;	
		case 'tttt':

			/*$tags = '000000143616000';
			$termo = '.';

			$pattern = '/' . $termo . '/';
			if (preg_match("/^(\d+)?\.\d+$/", $tags)) {
			echo 'Tag encontrada';
			} else {
			echo 'Tag não encontrada';
			}*/

			echo "<pre>";
			print_r($_SESSION[$_SESSION['apura']['mesano']]['validador']);			
			echo "</pre>";

			##ARQUIVO PRODFRIG.TXT
			/*$path_prodfrig   = "../arquivos/".$_SESSION['cnpj']."/PRODFRIG.TXT"; // pegando caminho do txt PRODFRIG
			$daoprodfrig     = new ProdFrigTxtDAO();
			$daopr 			 = new ProdutosTxtDAO();
			if(file_exists($path_prodfrig)){

				$lines = file ('../arquivos/'.$_SESSION['cnpj'].'/PRODFRIG.TXT');
				foreach ($lines as $line_num => $line) {
					$line = $line;
				
					$codigo_produto     = substr($line,0,14);
					$descricao_produto  = substr($line,14,40);
					$codprodsecreataria = substr($line,54,68);


					
					
					$prodfrig = new ProdFrigTxt();

					$prodfrig->setCodProd(trim($codigo_produto));
					$prodfrig->setDescProd(str_replace("'", "", str_replace('"', "", trim($descricao_produto))));
					$prodfrig->setCodSecretaria(trim($codprodsecreataria));
					$prodfrig->setCnpjEmp($_SESSION['cnpj']);							
					$daoprodfrig->inserir($prodfrig);
						
					


					

				}

			}*/

		break;
		case 'valida_novamente_session':
			$mesano         = !empty($_REQUEST['mesano']) ? $_REQUEST['mesano'] : $_SESSION['apura']['mesano'];
			echo json_encode($_SESSION[$mesano]['validador']);			
		break;

		}
	
	
	}
	
?>
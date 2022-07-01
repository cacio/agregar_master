<?php
	require_once('../inc/inc.autoload.php');
	session_start();
	//error_reporting(E_ALL);
	//ini_set('display_errors', 'On');
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){

			case 'inserir':
				
				/*print_r($_REQUEST);						
				print_r($_FILES);	*/
				
				$erro = "";				
				$dir = "../arquivos/";
				
				$results = array();
				
				if(!is_dir("".$dir."".$_REQUEST['cnpj']."")){
					mkdir("".$dir."".$_REQUEST['cnpj']."", 0777, true);
					mkdir("".$dir."".$_REQUEST['cnpj']."/".date('Ym')."", 0777, true);
				}
				
				if(!is_dir("".$dir."".$_REQUEST['cnpj']."/".date('Ym')."")){				
					mkdir("".$dir."".$_REQUEST['cnpj']."/".date('Ym')."", 0777, true);
				}
				
				$dao = new DocDigitalizadoEmpresaDAO();
				$vet = $dao->VerificaDocumentoDiditalizado($_REQUEST['tpdoc'],$_REQUEST['idemp']);
				$num = count($vet);
				
				if($num == 0){
				//$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
				$arquivos 	   = $_FILES;			
				$countarquivo  = count($_FILES['arq']['name']);
				$novodir 	   = "".$dir."".$_REQUEST['cnpj']."/".date('Ym')."/";
				
				for($i = 0; $i < $countarquivo; $i++){										
					foreach($arquivos as $key => $value){
						if(strlen($value['name'][$i])){
							
							$ext		 = $dao->getExtension($value['name'][$i]);						
							$actual_name = "".$_REQUEST['tpdoc'].'_'.str_replace(' ', '_', $_REQUEST['arq']['titulo'][$i]).".".$ext."";												
							$tmp 		 = $value['tmp_name'][$i];
							
							if(move_uploaded_file($tmp, $novodir.$actual_name)){							
							
								$prox   = $dao->proximoid();			
								$vetpr  = $prox[0];	
								$proxid = $vetpr->getIdProx();
								
								$dig = new DetalheDocDigitalizadoEmpresa();
								$dig->setDocumento($actual_name);
								$dig->setIdocdig($proxid);
								
								$daodig = new DetalheDocDigitalizadoEmpresaDAO();
								$daodig->inserir($dig);	
									
							}else{
								$erro = 1;
								array_push($results, array(
										'msg' => 'Falhou upload Arquivo: '.$novodir.$actual_name.'',
										'tipo' => '1',							
								));	
								
							}
						}else{
								/*$erro = 1;	
								array_push($results, array(
										'msg' => 'Ops: selecione um arquivo para envio',
										'tipo' => '1',							
								));	*/						
						}
					}	
					
				}
				
				if($erro == ""){
					$digdoc =  new DocDigitalizadoEmpresa();
					
					$digdoc->setIdTpDoc($_REQUEST['tpdoc']);
					$digdoc->setDtEmissao(date('Y-m-d'));
					$digdoc->setXcaminho($novodir);
					$digdoc->setDocumento(addslashes($_REQUEST['xdocumento'])); 
					$digdoc->setXmotivo($_REQUEST['xtitulo']);
					$digdoc->setStatus(1);
					$digdoc->setIdEmpresa($_REQUEST['idemp']);
					$dao->inserir($digdoc);
					
					array_push($results, array(
							'msg' => 'Arquivos enviados com sucesso!',
							'tipo' => '2',							
					));	
					
					echo (json_encode($results));
					
				}else{
					echo (json_encode($results));
				}				
				
				}else{
					array_push($results, array(
							'msg' => 'Verifica sua lista de arquivos e reenvia por meio dele(a)!',
							'tipo' => '2',							
					));	
					
					echo (json_encode($results));
				}
			break;
			case 'alterar':
					
				$erro = "";				
				$dir = "../arquivos/";
				
				/*print_r($_REQUEST);						
				print_r($_FILES);*/	

				$results = array();
				
				if(!is_dir("".$dir."".$_REQUEST['cnpj']."")){
					mkdir("".$dir."".$_REQUEST['cnpj']."", 0777, true);
					mkdir("".$dir."".$_REQUEST['cnpj']."/".date('Ym')."", 0777, true);
				}
				
				if(!is_dir("".$dir."".$_REQUEST['cnpj']."/".date('Ym')."")){				
					mkdir("".$dir."".$_REQUEST['cnpj']."/".date('Ym')."", 0777, true);
				}
				
				$dao = new DocDigitalizadoEmpresaDAO();
				
				$daodig = new DetalheDocDigitalizadoEmpresaDAO();
				/*$vetdig = $daodig->ListaDetalheDocumentoDiditalizado($_REQUEST['id']);
				$numdig = count($vetdig);
				
				for($x = 0; $x < $numdig; $x++){
					
					$digs = $vetdig[$x];	
					
					$coddigs  = $digs->getCodigo();
					$docsdigs = $digs->getDocumento();
					
					unlink("".$dir."".$_REQUEST['cnpj']."/".date('Ym')."/".$docsdigs."");
										
					$digd = new DetalheDocDigitalizadoEmpresa();
					
					$digd->setCodigo($coddigs);
					
					$daodig->deletar($digd);
					
				}*/
				
				
				//$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
				$arquivos 	   = $_FILES;			
				$countarquivo  = count($_FILES['arq']['name']);
				$novodir 	   = "".$dir."".$_REQUEST['cnpj']."/".date('Ym')."/";
				
				for($i = 0; $i < $countarquivo; $i++){										
					foreach($arquivos as $key => $value){
						if(strlen($value['name'][$i])){
							
							$ext		 = $dao->getExtension($value['name'][$i]);						
							$actual_name = $_REQUEST['tpdoc'].'_'.str_replace(' ', '_', $_REQUEST['arq']['titulo'][$i]).".".$ext;												
							$tmp 		 = $value['tmp_name'][$i];
							
							if(move_uploaded_file($tmp, $novodir.$actual_name)){							
							
																
								$dig = new DetalheDocDigitalizadoEmpresa();
								$dig->setDocumento($actual_name);
								$dig->setIdocdig($_REQUEST['id']);
								
								$daodig->inserir($dig);	
									
							}else{
								$erro = 1;
								array_push($results, array(
										'msg' => 'Falhou upload Arquivo: '.$novodir.$actual_name.'',
										'tipo' => '1',							
								));	
								
							}
						}else{
								/*$erro = 1;	
								array_push($results, array(
										'msg' => 'Ops: selecione um arquivo para envio',
										'tipo' => '1',							
								));*/							
						}
					}	
					
				}
				
				if($erro == ""){
					$digdoc =  new DocDigitalizadoEmpresa();
					
					$digdoc->setCodigo($_REQUEST['id']);
					$digdoc->setIdTpDoc($_REQUEST['tpdoc']);
					$digdoc->setDtEmissao(date('Y-m-d'));
					$digdoc->setXcaminho($novodir);
					$digdoc->setDocumento(addslashes($_REQUEST['xdocumento'])); 
					$digdoc->setXmotivo($_REQUEST['xtitulo']);
					$digdoc->setStatus(4);
					$digdoc->setIdEmpresa($_REQUEST['idemp']);
					$dao->update($digdoc);
					
					array_push($results, array(
							'msg' => 'Arquivos reenviado com sucesso!',
							'tipo' => '2',							
					));	
					
					echo (json_encode($results));
					
				}else{
					echo (json_encode($results));
				}				
			
			break;
			case 'updstatus':
				
				$results = array();
					
				$id = $_REQUEST['id'];			
				
				if(empty($_REQUEST['status'])){
					$status = '1';
				}else{
					$status = $_REQUEST['status'];	
				}
				
				if(empty($_REQUEST['xmotivo'])){
					$xmotivo = "";	
				}else{
					$xmotivo = $_REQUEST['xmotivo'];
				}
				
				$digdoc =  new DocDigitalizadoEmpresa();
				
				$digdoc->setCodigo($id);
				$digdoc->setDocumento(addslashes($xmotivo)); 
				$digdoc->setStatus($status);
				
				$dao = new DocDigitalizadoEmpresaDAO();
				$dao->updtstatus($digdoc);
				
				array_push($results, array(
							'msg' => 'Status Atualizado!',				
					));	
					
				echo (json_encode($results));
			break;
			case 'consultaarchive':

				$cod = $_REQUEST['cod'];

				$dao    = new DocDigitalizadoEmpresaDAO();
				$daodet = new DetalheDocDigitalizadoEmpresaDAO();
				$vetdet = $daodet->ListaDetalheDocumentoDiditalizado($cod);
				$numdet = count($vetdet);
				$result = array();

				for($x = 0; $x < $numdet; $x++){
						
					$det = $vetdet[$x];		
					
					$cod  	  = $det->getCodigo();
					$docs	  = $det->getDocumento();
					$iddocdig = $det->getIdocdig();				
					$ext 	  = $dao->getExtension($docs);
					$nomedocs = explode('.', str_replace('_', ' ', $docs));

					array_push($result, array(
						'docs'=>''.$docs.'',
						'nomedocs'=>''.$nomedocs[0].'',
						'ext'=>''.$ext.'',						
					));

				}

				echo json_encode($result);

			break;
		}

	}
	

?>
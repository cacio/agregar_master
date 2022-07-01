<?php
	require_once('../inc/inc.autoload.php');
	session_start();
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){
			
		$act = $_REQUEST['act'];
		
		switch($act){
			
			case 'inserir':
						
				$codcfop   = $_REQUEST['cfop'];
				$agregarsn = $_REQUEST['agregarsn'];		
				$xml       = $_REQUEST['caminho'];

				$daocf =  new CfopEmpresa2DAO();
				$vet   = $daocf->ListaCfopPorEmpresaUm($_SESSION['cnpj'],$codcfop); 	

				if(count($vet) > 0){

					if(count($vet) > 1){

						foreach ($vet as $key => $value) {
							$id = $value['id'];
							$daocf->RemoveCfopEmpresa($_SESSION['cnpj'],$id);
						}	

						$cfopemp = new CfopEmpresa();
					
						$cfopemp->setIdCfop($codcfop);
						$cfopemp->setGeraAgregar($agregarsn);
						$cfopemp->setCnpjEmp($_SESSION['cnpj']);
						
						$dao = new CfopEmpresaDAO();
						$dao->inserir($cfopemp);

					}else{

						$cod = $vet[0]['id'];

						$cfopemp = new CfopEmpresa();
				
						$cfopemp->setCodigo($cod);
						$cfopemp->setIdCfop($codcfop);
						$cfopemp->setGeraAgregar($agregarsn);
						$cfopemp->setCnpjEmp($_SESSION['cnpj']);
						
						$dao = new CfopEmpresaDAO();
						$dao->update($cfopemp);
					}

				}else{

					$cfopemp = new CfopEmpresa();
					
					$cfopemp->setIdCfop($codcfop);
					$cfopemp->setGeraAgregar($agregarsn);
					$cfopemp->setCnpjEmp($_SESSION['cnpj']);
					
					$dao = new CfopEmpresaDAO();
					$dao->inserir($cfopemp);
				}
				$result = array();
				
				
				array_push($result,array(
					'msg'=>'Sucesso!',
					'cfop'=>''.$codcfop.'',
					'agregarsn'=>''.$agregarsn.''					
				));
				
				echo json_encode($result);				
			break;
			case 'alterar':
						
				$codcfop   = $_REQUEST['cfop'];
				$agregarsn = $_REQUEST['agregarsn'];
				$cod 	   = $_REQUEST['cod'];	
				$xml       = $_REQUEST['caminho'];

				$destino   = "../arquivos/{$_SESSION['cnpj']}/removexml/{$xml}";
				$arquivo   = "../arquivos/{$_SESSION['cnpj']}/importado/{$xml}";

				$cfopemp = new CfopEmpresa();
				
				$cfopemp->setCodigo($cod);
				$cfopemp->setIdCfop($codcfop);
				$cfopemp->setGeraAgregar($agregarsn);
				$cfopemp->setCnpjEmp($_SESSION['cnpj']);
				
				$dao = new CfopEmpresaDAO();
				$dao->update($cfopemp);
								
				$result = array();
				
				if($agregarsn == 2){
					copy($arquivo, $destino);
					unlink($arquivo);
				}

				array_push($result,array(
					'msg'=>'Sucesso!',
					'cfop'=>''.$codcfop.'',
					'agregarsn'=>''.$agregarsn.''
				));
				
				echo json_encode($result);
				
			break;

			case 'delete':


			break;
				
		}
		
	}
	
?>
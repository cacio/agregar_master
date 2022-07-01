<?php
	session_start();
	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

		case 'insrt':
				
				$idprod    = $_REQUEST['idprod'];
				$relprod   = $_REQUEST['relprod'];
				$idprodrel = $_REQUEST['idprodrel'];	
				$iddet     = $_REQUEST['iddet'];
					
				$rel = new RelacionaProduto();		

				$rel->setIdRelacionado($idprodrel);			
				$rel->setIdProduto($idprod);
				$rel->setNumeroNota('');				
				$rel->setCnpjEmp($_SESSION['cnpj']);	
					
				$dao = new RelacionaProdutoDAO();
				
				$vet    = $dao->proximoid();
				$prox   = $vet[0];
				$codigo = $prox->getProximoId();
				
				$dao->inserir($rel);	

				$result = array();
				
				array_push($result, array(
						'codigo'=>''.$codigo.'',
						'iddet' => ''.$iddet.'',
						'relprod' => ''.$relprod.'',
						'idprod' => ''.$idprod.'',
						'idprodrel' => ''.$idprodrel.'',							
				));	
					
				
				echo (json_encode($result));		
						
		break;
		case 'inserir':

			$idprod    = $_REQUEST['idprod'];
			$relprod   = $_REQUEST['relprod'];
			$idprodrel = $_REQUEST['idprodrel'];	
			$iddet     = $_REQUEST['iddet'];

			$rel = new ProdFrigTxt();	

			$rel->setCodProd($idprod);
			$rel->setDescProd($relprod);
			$rel->setCodSecretaria($idprodrel);
			$rel->setCnpjEmp($_SESSION['cnpj']);

			$dao    = new ProdFrigTxtDAO();	
			$vet    = $dao->proximoid();
			$prox   = $vet[0];
			$codigo = $prox->getProximoId();

			$dao->inserir($rel);

			$result = array();
				
			array_push($result, array(
					'codigo'=>''.$codigo.'',
					'iddet' => ''.$iddet.'',
					'relprod' => ''.$relprod.'',
					'idprod' => ''.$idprod.'',
					'idprodrel' => ''.$idprodrel.'',							
			));	
				
			
			echo (json_encode($result));

		break;
		case 'alterar':
			
			$id        = $_REQUEST['id'];
			$idprod    = $_REQUEST['idprod'];
			$relprod   = $_REQUEST['relprod'];
			$idprodrel = $_REQUEST['idprodrel'];	
			$iddet     = $_REQUEST['iddet'];			

			$prodfrigtxt = new ProdFrigTxt();

			$prodfrigtxt->setCodigo($id);
			$prodfrigtxt->setCodProd($idprod);
			$prodfrigtxt->setDescProd($relprod);
			$prodfrigtxt->setCodSecretaria($idprodrel);

			$dao    = new ProdFrigTxtDAO();	

			$dao->update($prodfrigtxt);

			$result = array();
				
			array_push($result, array(
					'codigo'=>''.$id.'',
					'iddet' => ''.$iddet.'',
					'relprod' => ''.$relprod.'',
					'idprod' => ''.$idprod.'',
					'idprodrel' => ''.$idprodrel.'',							
			));	
				
			
			echo (json_encode($result));

		break;
		case 'alter':
				
				$id        = $_REQUEST['id'];
				$idprod    = $_REQUEST['idprod'];
				$relprod   = $_REQUEST['relprod'];
				$idprodrel = $_REQUEST['idprodrel'];	
				$iddet     = $_REQUEST['iddet'];
					
				$rel = new RelacionaProduto();		
				
				$rel->setCodigo($id);
				$rel->setIdRelacionado($idprodrel);			
				$rel->setIdProduto($idprod);
				$rel->setNumeroNota('');				
				$rel->setCnpjEmp($_SESSION['cnpj']);	
					
				$dao = new RelacionaProdutoDAO();								
				
				$dao->update($rel);	

				$result = array();
				
				array_push($result, array(
						'codigo'=>''.$id.'',
						'iddet' => ''.$iddet.'',
						'relprod' => ''.$relprod.'',
						'idprod' => ''.$idprod.'',
						'idprodrel' => ''.$idprodrel.'',							
				));	
					
				
				echo (json_encode($result));		
						
		break;
		case 'upinsert':

			$pk        = explode('|', $_REQUEST['pk']);
			$value     = explode('|',$_REQUEST['value']);
			$idprodrel = $value[0];				
			$relprod   = $value[1];

			$dao = new ProdFrigTxtDAO();
			$vet = $dao->VerificaProduto($pk[0],$_SESSION['cnpj']);
			$num = count($vet);

			if($num > 0){

				$prodfrig = $vet[0];
				$codigo   = $prodfrig->getId();
				
				$prodfrigtxt = new ProdFrigTxt();

				$prodfrigtxt->setCodigo($codigo);
				$prodfrigtxt->setCodProd($pk[0]);
				$prodfrigtxt->setDescProd($relprod);
				$prodfrigtxt->setCodSecretaria($idprodrel);

				$dao    = new ProdFrigTxtDAO();	

				$dao->update($prodfrigtxt);



			}else{

				$rel = new ProdFrigTxt();	

				$rel->setCodProd($pk[0]);
				$rel->setDescProd($relprod);
				$rel->setCodSecretaria($idprodrel);
				$rel->setCnpjEmp($_SESSION['cnpj']);

				$dao    = new ProdFrigTxtDAO();	
				$vet    = $dao->proximoid();
				$prox   = $vet[0];
				$codigo = $prox->getProximoId();

				$dao->inserir($rel);

			}

			$result = array();
				
			array_push($result, array(
					'codigo'=>''.$codigo.'',
					'iddet' => ''.$codigo.'',
					'relprod' => ''.$relprod.'',
					'idprod' => ''.$pk[0].'',
					'idprodrel' => ''.$idprodrel.'',							
			));	
				
			
			echo (json_encode($result));	

		break;
		case 'updateadm':	

			$pk        = explode('|', $_REQUEST['pk']);
			$id        = $pk[0];
			$idprod    = $pk[1];
			$value     = explode('|',$_REQUEST['value']);			
			$idprodrel = $value[0];				
			$relprod   = $value[1];	

			$prodfrigtxt = new ProdFrigTxt();

			$prodfrigtxt->setCodigo($id);
			$prodfrigtxt->setCodProd($idprod);
			$prodfrigtxt->setDescProd($relprod);
			$prodfrigtxt->setCodSecretaria($idprodrel);

			$dao    = new ProdFrigTxtDAO();	

			$dao->update($prodfrigtxt);

			$result = array();
			
			array_push($result, array(
					'codigo'=>''.$id.'',									
					'idprod' => ''.$idprod.'',
					'idprodrel' => ''.$idprodrel.'',							
			));	
				
			
			echo (json_encode($result));


		break;
		case 'upinsetselected':
			$pk        = explode('|', $_REQUEST['pk']);
			$prod	   = $_REQUEST['prod'];
			$idprodrel = $pk[0];
			$relprod   = $pk[1];
			$result    = array();

			foreach ($prod as $key => $value) {
				
				$dao = new ProdFrigTxtDAO();
				$vet = $dao->VerificaProduto($value,$_SESSION['cnpj']);
				$num = count($vet);

				if($num > 0){

					$prodfrig = $vet[0];
					$codigo   = $prodfrig->getId();
					
					$prodfrigtxt = new ProdFrigTxt();

					$prodfrigtxt->setCodigo($codigo);
					$prodfrigtxt->setCodProd($value);
					$prodfrigtxt->setDescProd($relprod);
					$prodfrigtxt->setCodSecretaria($idprodrel);

					$dao    = new ProdFrigTxtDAO();	

					$dao->update($prodfrigtxt);



				}else{

					$rel = new ProdFrigTxt();	

					$rel->setCodProd($value);
					$rel->setDescProd($relprod);
					$rel->setCodSecretaria($idprodrel);
					$rel->setCnpjEmp($_SESSION['cnpj']);

					$dao    = new ProdFrigTxtDAO();	
					$vet    = $dao->proximoid();
					$prox   = $vet[0];
					$codigo = $prox->getProximoId();

					$dao->inserir($rel);

				}

				array_push($result, array(
					'codigo'=>''.$codigo.'',
					'iddet' => ''.$codigo.'',
					'relprod' => ''.$relprod.'',
					'idprod' => ''.$value.'',
					'idprodrel' => ''.$idprodrel.'',							
				));	
			}
			
			echo (json_encode($result));
		break;
		case 'upinsertterceiro':
				
			$dao = new RecionamentoTerceirosDAO();
			$result    		  = array();
			$pk        		  = explode('|', $_REQUEST['pk']);
			$value    		  = explode('|',$_REQUEST['value']);
			$idprodrel 		  = $value[0];
			$relprod   		  = $value[1];							  
			$obj       		  = json_decode (json_encode (str_replace("'",'"',$pk[0])), FALSE);		
			$res       		  = json_decode($obj,TRUE);
			$indForn   		  = $res[0]['clicnpj'].''.$res[0]['cliie'];		
			$cprod     		  = $res[0]['cprod'];
			$nnota    		  = $res[0]['nnota'];
			$entsai    		  = $res[0]['entsai'];
			$numero_item_nota = $res[0]['numero_item_nota'];
						
			$data = array(
				"cnpj_ie_terceiros"=>$indForn,
				"idprodproprio"=>$idprodrel,
				"idprodterceiros"=>$cprod,
				"cnpj_emp"=>$_SESSION['cnpj']
			);
			
			$vetrt = $dao->VerificaRelacionamentoEmpresa($indForn,$idprodrel,$_SESSION['cnpj']);
				
			if(count($vetrt) == 0){
				$codigo = $dao->HandlerInsert($data);
			}else{
				$codigo = $vetrt[0]['id'];
			}

			$daosai1 = new NotasSa1Txt2DAO();
			$vetsai1 = $daosai1->pegaIdItemNota($nnota,$numero_item_nota,$_SESSION['cnpj']);

			if(count($vetsai1) > 0){
				$id = $vetsai1[0]['id'];

				$datan = array(
					'codigo_produto'=>''.$idprodrel.''
				);

				$daosai1->UpdateItemNotaProdutoTerceiro($datan,$id);
				
			}

			array_push($result, array(
				'codigo'=>''.$codigo.'',
				'iddet' => ''.$codigo.'',
				'relprod' => ''.$relprod.'',
				'idprod' => ''.$cprod.'',
				'idprodrel' => ''.$idprodrel.'',
				'indForn'=>$indForn,
				'nnota'=>$nnota,
				'obj'=>$pk[0]							
			));	
				
			
			echo (json_encode($result));	

		break;
		case 'upinsertterceiromulti':
			
			$result    		  = array();
			$pk        		  = explode('|', $_REQUEST['pk']);
			$idprodrel 		  = $pk[0];
			$relprod   		  = $pk[1];		
			$prod			  = $_REQUEST['prod'];

			for ($i=0; $i < count($prod); $i++) { 
				
				$res 			  = $prod[$i];
				$obj       		  = json_decode (json_encode (str_replace("'",'"',$res['obj'])), FALSE);		
				$resobj       	  = json_decode($obj,TRUE);
				$indForn   		  = $resobj[0]['clicnpj'].''.$resobj[0]['cliie'];		
				$cprod     		  = $resobj[0]['cprod'];
				$nnota    		  = $resobj[0]['nnota'];
				$entsai    		  = $resobj[0]['entsai'];
				$numero_item_nota = $resobj[0]['numero_item_nota'];

				$dao = new RecionamentoTerceirosDAO();

				$data = array(
					"cnpj_ie_terceiros"=>$indForn,
					"idprodproprio"=>$idprodrel,
					"idprodterceiros"=>$cprod,
					"cnpj_emp"=>$_SESSION['cnpj']
				);

				$vetrt = $dao->VerificaRelacionamentoEmpresa($indForn,$idprodrel,$_SESSION['cnpj']);
				
				if(count($vetrt) == 0){
					$codigo = $dao->HandlerInsert($data);
				}else{
					$codigo = $vetrt[0]['id'];
				}

				$daosai1 = new NotasSa1Txt2DAO();
				$vetsai1 = $daosai1->pegaIdItemNota($nnota,$numero_item_nota,$_SESSION['cnpj']);

				if(count($vetsai1) > 0){
					$id = $vetsai1[0]['id'];

					$datan = array(
						'codigo_produto'=>''.$idprodrel.''
					);

					$daosai1->UpdateItemNotaProdutoTerceiro($datan,$id);
					
				}

				array_push($result, array(
					'codigo'=>''.$codigo.'',
					'iddet' => ''.$codigo.'',
					'relprod' => ''.$relprod.'',
					'idprod' => ''.$cprod.'',
					'idprodrel' => ''.$idprodrel.'',
					'indForn'=>$indForn,
					'nnota'=>$nnota,
					'obj'=>$res['obj']							
				));	

			}	
			echo (json_encode($result));
		break;
		}
	}

?>
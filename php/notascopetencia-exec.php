<?php
	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){

			case 'entrada':
				$mesano  = $_REQUEST['mesano'];
				//$_SESSION['cnpj'],$mesano
				$dao = new NotasEntTxtDAO();
				$vet = $dao->ListaNotasEntrada($_SESSION['cnpj'],$mesano);
				$num = count($vet);
				$res = array();
				
				for($i = 0; $i < $num; $i++){
					
					$notasent		  = $vet[$i]; 
					$id 		  	  = $notasent->getCodigo();
					$numero_nota  	  = $notasent->getNumeroNota();
					$data_emissao 	  = $notasent->getDataEmissao();
					$cnpj_cpf     	  = $notasent->getCnpjCpf();
					$tipo_v_r_a	  	  = $notasent->getTipoV_R_A();
					$valor_total_nota = $notasent->getValorTotalNota();
					$xml    		  = $notasent->getXml();
					$chave_acesso     = $notasent->getChave();	
					$razao			  = $notasent->getNomeCli();
					$valorproduto     = $notasent->getValorTotalProduto();
					$abate			  = $notasent->getAbate();
					$data_abate       = $notasent->getDataAbate();	
					$cor 			  = "";	

					if(date('Y/m',strtotime($data_emissao))  != date('Y/m',strtotime($data_abate))){
						$cor = "#ffbc34";
					}

					array_push($res,array(
						'id'=>$id,
						'numero'=>$numero_nota,
						'dataemiss'=>date('d/m/Y',strtotime($data_emissao)),
						'tipo'=>'ENTRADA',
						'chave'=>$chave_acesso,
						'cnpjcpf'=>$cnpj_cpf,
						'razao'=>$razao,
						'valor'=>$valor_total_nota,
						'totprod'=>$valorproduto,
						'cor'=>$cor,
						'abate'=>$abate,
					));
					
				}		
				
				echo json_encode($res);
				
			break;
			case 'saida':
				$mesano  = $_REQUEST['mesano'];
								
				$dao = new NotasSaiTxtDAO();
				$vet = $dao->ListandoNotasSai($mesano,$_SESSION['cnpj']);
				$num = count($vet);
				$res = array();
				for($i = 0; $i < $num; $i++){
					
					$notasai		  = $vet[$i];
					
					$id    			  = $notasai->getCodigo();
					$numero_nota 	  = $notasai->getNumeroNota();
					$data_emissao 	  = $notasai->getDataEmissao();
					$cnpj_cpf	  	  = $notasai->getCnpjCpf();
					$valor_total_nota = $notasai->getValorTotalNota();						
					$razao			  = $notasai->getNomeCli();
					$xml			  = $notasai->getXml();
					$chave_acesso	  = $notasai->getChave();
					$totprod		  = $notasai->getTotalProd();

										
					$dao->setProgress(($i * 100) / $num);
					
					array_push($res,array(
						'id'=>$id,
						'numero'=>$numero_nota,
						'dataemiss'=>date('d/m/Y',strtotime($data_emissao)),
						'tipo'=>'SAIDA',
						'chave'=>$chave_acesso,
						'cnpjcpf'=>$cnpj_cpf,
						'razao'=>$razao,
						'valor'=>$valor_total_nota,
						'totprod'=>$totprod,
					));
					
				}
				
				echo json_encode($res);
				
			break;
			case 'saidateste':
				$mesano  = $_REQUEST['mesano'];
								
				$requested_page = $_POST['page_num'];
				$set_limit = (($requested_page - 1) * 100) . ",100";

				$dao = new NotasSaiTxtDAO();
				$vet = $dao->ListandoNotasSaiScroll($mesano,$_SESSION['cnpj'],$set_limit);
				$num = count($vet);
				$res = array();
				for($i = 0; $i < $num; $i++){
					
					$notasai		  = $vet[$i];
					
					$id    			  = $notasai->getCodigo();
					$numero_nota 	  = $notasai->getNumeroNota();
					$data_emissao 	  = $notasai->getDataEmissao();
					$cnpj_cpf	  	  = $notasai->getCnpjCpf();
					$valor_total_nota = $notasai->getValorTotalNota();						
					$razao			  = $notasai->getNomeCli();
					$xml			  = $notasai->getXml();
					$chave_acesso	  = $notasai->getChave();
					$totprod		  = $notasai->getTotalProd();

					array_push($res,array(
						'id'=>$id,
						'numero'=>$numero_nota,
						'dataemiss'=>date('d/m/Y',strtotime($data_emissao)),
						'tipo'=>'SAIDA',
						'chave'=>$chave_acesso,
						'cnpjcpf'=>$cnpj_cpf,
						'razao'=>$razao,
						'valor'=>$valor_total_nota,
						'totprod'=>$totprod,
					));
					
				}
				
				echo json_encode($res);
				
			break;
			
			case 'removeitementrada':
				
				$id 		= $_REQUEST['id'];
				$res        = array();
				$notasen1txt =  new NotasEn1Txt();

				$notasen1txt->setCodigo($id);
				$notasen1txt->setCnpjEmp($_SESSION['cnpj']);

				$dao = new NotasEn1TxtDAO();
				$dao->deletar($notasen1txt);

				array_push($res,array(
					'msg'=>"Deletado com sucesso!",
					'id'=>$id
				));

				echo json_encode($res);

			break;

			case 'removeitemsaida':

				$id 		= $_REQUEST['id'];
				$res        = array();
				$notasa1txt =  new NotasSa1Txt();

				$notasa1txt->setCodigo($id);
				$notasa1txt->setCnpjEmp($_SESSION['cnpj']);

				$dao = new NotasSa1TxtDAO();
				$dao->deletar($notasa1txt);

				array_push($res,array(
					'msg'=>"Deletado com sucesso!",
					'id'=>$id
				));

				echo json_encode($res);


			break;
			
			
		}

	}
	

?>
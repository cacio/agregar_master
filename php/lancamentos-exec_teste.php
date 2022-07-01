<?php
	session_start();
	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

		case 'box':
			
			$data 		= array();
			
			if(isset($_GET['files'])){
			
				
					$error = false;
					$files = array();
					$uploaddir = '../arquivos/'.$_SESSION['cnpj'].'/xml/entrada/';
					
					
					foreach($_FILES as $file){
												
						if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name']))){
							
							$files[] = $uploaddir .$file['name'];
							
						}else{
							
							$error = true;
						}
												
					}
					
					$data = ($error) ? array('error' => 'Houve um erro ao carregar seus arquivos') : array('files' => $files);
				
				
				}else{
			
				
				foreach($_REQUEST['filenames'] as $arquivo){
					
					if($xml =  simplexml_load_file($arquivo)){
						
						
						//$xml->NFe->infNFe->ide->nNF.
						
						if($xml->NFe->infNFe->det->prod->CFOP < 5000){
							//NOTA DE ENTRADA
							
							$daonotasenttxt = new NotasEntTxtDAO();
							$vetnotasenttxt = $daonotasenttxt->VerificaNotas($xml->NFe->infNFe->ide->nNF,$_SESSION['cnpj']);
							$numnotasenttxt = count($vetnotasenttxt);
							
							if($numnotasenttxt == 0){
							
								$dEmi         = explode('T',$xml->NFe->infNFe->ide->dhEmi);
								
								$notasenttxt = new NotasEntTxt();
						
								$notasenttxt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
								$notasenttxt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
								$notasenttxt->setCnpjCpf(trim($xml->NFe->infNFe->dest->CNPJ));
								$notasenttxt->setTipoV_R_A('');
								$notasenttxt->setValorTotalNota(trim($xml->NFe->infNFe->total->ICMSTot->vNF));
								$notasenttxt->setGta('');
								$notasenttxt->setNumeroNotaProdutorIni('');
								$notasenttxt->setNumeroNotaProdutorFin('');
								$notasenttxt->setCondenas('');
								$notasenttxt->setAbate('');
								$notasenttxt->setInscProdutor(trim($xml->NFe->infNFe->dest->IE));
								$notasenttxt->setCnpjEmp($_SESSION['cnpj']);
															
								$daonotasenttxt->inserir($notasenttxt);
								
								$i = 0; 
								foreach($xml->NFe->infNFe->det as $prod){
									
									
									
									$notasen1 = new NotasEn1Txt();
	
									$notasen1->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
									$notasen1->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
									$notasen1->setCnpjCpf(trim($xml->NFe->infNFe->dest->CNPJ));
									$notasen1->setCodigoProduto(trim($prod->prod->cProd));
									$notasen1->setQtdCabeca(0);
									$notasen1->setPesoVivoCabeca(trim($prod->prod->qCom));
									$notasen1->setPesoCarcasa(0);
									$notasen1->setPrecoQuilo(trim($prod->prod->vUnCom));
									$notasen1->setNumeroItemNota(($i+1));
									$notasen1->setInsEstadual(trim($xml->NFe->infNFe->dest->IE));
									$notasen1->setDataAbate(NULL);
									$notasen1->setTipo_R_V('');
									$notasen1->setCfop(trim($prod->prod->CFOP));
									$notasen1->setAliquotaIcms(0);
									$notasen1->setCnpjEmp($_SESSION['cnpj']);
									
									$daonotasen1 = new NotasEn1TxtDAO();
									$daonotasen1->inserir($notasen1);
									
									$i++;
								}
								
								array_push($data,array(
										
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'chave'=>''.$xml->protNFe->infProt->chNFe.'',
									'entsai'=>'E',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'caminho'=>''.$arquivo.'',
									'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',
									'msg'=>''									
								));	
								
							}else{
								$dEmi         = explode('T',$xml->NFe->infNFe->ide->dhEmi);
								
								array_push($data,array(
										
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'chave'=>''.$xml->protNFe->infProt->chNFe.'',
									'entsai'=>'E',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'caminho'=>''.$arquivo.'',
									'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',
									'msg'=>'Nota já Existe!'									
								));	
							
							}
						}else{
							
							$dEmi       = explode('T',$xml->NFe->infNFe->ide->dhEmi);
						
							$daonotasaitxt = new NotasSaiTxtDAO();
							$vetnotasaitxt = $daonotasaitxt->VerificaNota(trim($xml->NFe->infNFe->ide->nNF),$_SESSION['cnpj']); 
							$numnotasaitxt = count($vetnotasaitxt);
							
							if($numnotasaitxt == 0){
							
								$notasaitxt = new NotasSaiTxt();
						
								$notasaitxt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
								$notasaitxt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
								$notasaitxt->setCnpjCpf(trim($xml->NFe->infNFe->dest->CNPJ));
								$notasaitxt->setValorTotalNota(trim($xml->NFe->infNFe->total->ICMSTot->vNF));
								$notasaitxt->setValorIcms(trim($xml->NFe->infNFe->total->ICMSTot->vICMS));
								$notasaitxt->setValorIcmsSubs(trim($xml->NFe->infNFe->total->ICMSTot->vST));
								$notasaitxt->setEntSai('S');
								$notasaitxt->setInscEstadual(trim($xml->NFe->infNFe->dest->IE));
								$notasaitxt->setCfop(trim($xml->NFe->infNFe->det->prod->CFOP));
								$notasaitxt->setCnpjEmp($_SESSION['cnpj']);
															
								$daonotasaitxt->inserir($notasaitxt);
								
								$i = 0; 
								foreach($xml->NFe->infNFe->det as $prod){
									
									
									$notasa1txt = new NotasSa1Txt();
						
									$notasa1txt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
									$notasa1txt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
									$notasa1txt->setCnpjCpf(trim($xml->NFe->infNFe->dest->CNPJ));
									$notasa1txt->setCodigoProduto(trim($prod->prod->cProd));
									$notasa1txt->setQtdPecas(trim(0));
									$notasa1txt->setPeso(trim($prod->prod->qCom));
									$notasa1txt->setPrecoUnitario(trim($prod->prod->vUnCom));
									$notasa1txt->setEntSai('S');
									$notasa1txt->setNumeroItemNota(($i+1));		
									$notasa1txt->setInscEstadual(trim($xml->NFe->infNFe->dest->IE));
									$notasa1txt->setCfop(trim($xml->NFe->infNFe->det->prod->CFOP));
									$notasa1txt->setAliquotaIcms(0);
									$notasa1txt->setCnpjEmp($_SESSION['cnpj']);
									
									$daonotasa1txt = new NotasSa1TxtDAO();
									
									$daonotasa1txt->inserir($notasa1txt);
									$i++;
								}
							
								array_push($data,array(
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'chave'=>''.$xml->protNFe->infProt->chNFe.'',
									'entsai'=>'S',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'caminho'=>'',
									'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',	
									'msg'=>''								
								));	
							
							}else{
								
								$dEmi         = explode('T',$xml->NFe->infNFe->ide->dhEmi);
								
								array_push($data,array(
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'chave'=>''.$xml->protNFe->infProt->chNFe.'',
									'entsai'=>'S',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'caminho'=>''.$arquivo.'',
									'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',
									'msg'=>'Nota já Existe!'									
								));	
								
							}
							
						}
													
						
							
					}else{
						
						$error = true;
						
					}
					
				}
			
			
			
			}
				
			echo json_encode($data);	
						
		break;
		case 'dadosprod':
			
			$funcao = new FuncoesDAO();
			//select das entradas
			$numero  = $_REQUEST['cod'];
			$arquivo = $_REQUEST['arquivo'];
			$entsai  = $_REQUEST['entsai'];
			
			$destino = "../arquivos/{$_SESSION['cnpj']}/importado/";	
			
			if($entsai == 'SAIDA'){
				//ListaNotasSaiEmpUm => notas saida				
				$daosaida = new NotasSaiTxtDAO();
				$vetsaida = $daosaida->ListaNotasSaiEmpUm($numero);
				$numsaida = count($vetsaida);
				
				if($numsaida > 0){
					$notasai = $vetsaida[0];
					$xmlstr  = $notasai->getXml();						
				}
				
			}else{
				//ListaNotasEntCompUm => notas entradas	
				$daoentrada = new NotasEntTxtDAO();
				$vetentrada = $daoentrada->ListaNotasEntCompUm($numero);
				$numentrada = count($vetentrada);
				
				if($numentrada > 0){
					$notasent = $vetentrada[0];
					$xmlstr   = $notasent->getXml();
				}
				
			}
			
			$dom = new domDocument();
			$dom->loadXML($xmlstr);
			$xml = simplexml_import_dom($dom);
			$xml = $funcao->verificatagxml($xml);			
			//$xml	 =  simplexml_load_file($destino.$arquivo);
			$result  = array();			
			$i = 0; 
			foreach($xml->NFe->infNFe->det as $prod){
			
				if($xml->NFe->infNFe->ide->tpNF == 0){	
				 $where  = "  where n.numero_nota = '".$numero."'
							AND n.codigo_produto = '".$prod->prod->cProd."'
							AND n.numero_item_nota = '".($i+1)."'
							AND n.cnpj_emp = '".$_SESSION['cnpj']."' ";	
				 
				 $dao = new NotasEn1TxtDAO();
				 $vet = $dao->DetalheProdutoNota($where); 
				 $num = count($vet);
				 	
				 if($num > 0){
					
						$notasen1txt  	  = $vet[0];

						$id				  = $notasen1txt->getCodigo();
						$numero_nota  	  = $notasen1txt->getNumeroNota();
						$data_emissao 	  = $notasen1txt->getDataEmissao();
						$cnpj_cpf	  	  = $notasen1txt->getCnpjCpf();
						$codigo_produto   = $notasen1txt->getCodigoProduto();
						$qtd_cabeca		  = $notasen1txt->getQtdCabeca();
						$peso_vivo_cabeca = $notasen1txt->getPesoVivoCabeca();
						$peso_carcasa	  = $notasen1txt->getPesoCarcasa();
						$preco_quilo	  = $notasen1txt->getPrecoQuilo();
						$numero_item_nota = $notasen1txt->getNumeroItemNota();
						$insc_estadual	  = $notasen1txt->getInsEstadual();
						$data_abate		  = $notasen1txt->getDataAbate();
						$tipo_r_v		  = $notasen1txt->getTipo_R_V();
						$cfop			  = $notasen1txt->getCfop();
						$aliquota_icms	  = $notasen1txt->getAliquotaIcms();		
						$subtotal		  = ($qtd_cabeca * $preco_quilo);	

						if($data_abate == '0000-00-00'){
							$data_abate = "";
						}else{
							$data_abate = implode("/", array_reverse(explode("-", "".$data_abate."")));
						}
					 
					}
				}else{
					
					$where  = "  where s.numero_nota = '".$numero."'
							AND s.codigo_produto = '".$prod->prod->cProd."'
							AND s.numero_item_nota = '".($i+1)."'
							AND s.cnpj_emp = '".$_SESSION['cnpj']."' ";	
					
					$dao = new NotasSa1TxtDAO();
					$vet = $dao->DetalheProdutoNotaSaida($where);
					$num = count($vet);
					
					if($num > 0){
						
						$notasa1 = $vet[0];
						
						$id 		      = $notasa1->getCodigo();
						$numero_nota      = $notasa1->getNumeroNota();
						$data_emissao     = $notasa1->getDataEmissao();
						$cnpj_cpf	      = $notasa1->getCnpjCpf();
						$codigo_produto   = $notasa1->getCodigoProduto();
						$qtd_pecas	      = $notasa1->getQtdPecas();
						$peso			  = $notasa1->getPeso();
						$preco_unitario   = $notasa1->getPrecoUnitario();
						$preco_quilo      = $notasa1->getPrecoUnitario();
						$ent_sai		  = $notasa1->getEntSai();
						$numero_item_nota = $notasa1->getNumeroItemNota();		
						$insc_estadual    = $notasa1->getInscEstadual();
						$cfop			  = $notasa1->getCfop();
						$aliquota_icms    = $notasa1->getAliquotaIcms();
						$subtotal         = $prod->prod->vUnCom * trim($prod->prod->qCom);
						$data_abate 	  = "";
						$qtd_cabeca       = 0;
						$tipo_r_v         = 'R';
						$peso_carcasa	  = 0;
						$peso_vivo_cabeca = 0;
					}										
					
				}
				
				$daop = new ProdFrigTxtDAO();		
				$vetp =	$daop->ListaRelacionado($codigo_produto,$_SESSION['cnpj']);
				$nump = count($vetp);	

				if($nump > 0){

					$rel = $vetp[0];

					$idre 	   = $rel->getCodProd();
					$id_rel    = $rel->getCodSecretaria();
					$descricao = $rel->getDescProd();

				}else{
					$id_rel    = "";
					$descricao = "";
					$idre	   = ""; 
				}	

				array_push($result,array(
					'id'=>''.$id.'',
					'codigo_produto'=>''.$codigo_produto.'',
					'qtd_cabeca'=>''.$qtd_cabeca.'',
					'qCom'=>''.number_format(doubleval($prod->prod->qCom),2,',','.').'',
					'preco_quilo'=>''.number_format($preco_quilo,2,',','.').'',
					'subtotal'=>''.number_format(doubleval($subtotal),2,',','.').'',
					'desc_produto'=>''.$prod->prod->xProd.'',
					'id_rel'=>''.$id_rel.'',
					'descricao'=>''.$descricao.'',
					'idre'=>''.$idre.'',
					'tipo_r_v'=>''.$tipo_r_v.'',
					'data_abate'=>''.$data_abate.'',
					'peso_carcasa'=>''.number_format(doubleval($peso_carcasa),2,',','.').'',
					'pesovivo'=>''.number_format(doubleval($peso_vivo_cabeca),2,',','.').'',
				));			

				 	
					
			
				$i++;
			}			
						
			echo json_encode($result);
		break;
		case 'detalhenota':

			$tipo     = substr($_REQUEST['tipo'],0,1);
			$numero   = $_REQUEST['numero'];
			$cnpj_emp = $_SESSION['cnpj'];
			$comptenc = !empty($_SESSION['competencia']) ? $_REQUEST['competencia']  : $_SESSION['apura']['mesano'];
			
			$result   = array();
			
			if($tipo == 'E'){
				//detalhe da nota de entrada
				//	
				$daoen1  = new NotasEn1TxtDAO();
				$veten1  = $daoen1->DetalheNotaEntrada($numero,$comptenc,$cnpj_emp);
				$numen1  = count($veten1);

				for($i = 0; $i < $numen1; $i++){

					$notasen1txt  	  = $veten1[$i];

					$id				  = $notasen1txt->getCodigo();
					$numero_nota  	  = $notasen1txt->getNumeroNota();
					$data_emissao 	  = $notasen1txt->getDataEmissao();
					$cnpj_cpf	  	  = $notasen1txt->getCnpjCpf();
					$codigo_produto   = $notasen1txt->getCodigoProduto();
					$desc_prod 		  = $notasen1txt->getDescProd();
					$qtd_cabeca		  = $notasen1txt->getQtdCabeca();
					$qtd_prod         = $notasen1txt->getProdQtd();
					$peso_vivo_cabeca = $notasen1txt->getPesoVivoCabeca();
					$peso_carcasa	  = $notasen1txt->getPesoCarcasa();
					$preco_quilo	  = $notasen1txt->getPrecoQuilo();
					$numero_item_nota = $notasen1txt->getNumeroItemNota();
					$insc_estadual	  = $notasen1txt->getInsEstadual();
					$data_abate		  = $notasen1txt->getDataAbate();
					$tipo_r_v		  = $notasen1txt->getTipo_R_V();
					$cfop			  = $notasen1txt->getCfop();
					$aliquota_icms	  = $notasen1txt->getAliquotaIcms();
					
					if(empty($qtd_prod)){
						$qtd_prod = $qtd_cabeca;
					}

					$subtotal		  = ($qtd_prod * $preco_quilo);	

					if($data_abate == '0000-00-00'){
						$data_abate = "";
					}else{
						$data_abate = implode("/", array_reverse(explode("-", "".$data_abate."")));
					}

					
					$daop = new ProdFrigTxtDAO();		
					$vetp =	$daop->ListaRelacionado($codigo_produto,$cnpj_emp);
					$nump = count($vetp);	

					if($nump > 0){

						$rel = $vetp[0];

						$idre 	           = $rel->getCodProd();
						$id_rel            = $rel->getCodSecretaria();
						$descricao 		   = $desc_prod;
						$descricaoprosecre = $rel->getDesProdSecretaria();

					}else{
						$id_rel    = "";
						$descricao = $desc_prod;
						$descricaoprosecre = "";
						$idre	   = ""; 
					}	

					array_push($result,array(
						'id'=>''.$id.'',
						'codigo_produto'=>''.$codigo_produto.'',
						'qtd_cabeca'=>''.$qtd_cabeca.'',
						'qtd_prod'=>''.$qtd_prod.'',
						'qCom'=>''.$qtd_cabeca.'',
						'preco_quilo'=>''.number_format($preco_quilo,2,',','.').'',
						'subtotal'=>''.number_format(doubleval($subtotal),2,',','.').'',
						'desc_produto'=>''.$descricao.'',
						'id_rel'=>''.$id_rel.'',
						'descricao'=>''.$descricaoprosecre.'',
						'idre'=>''.$idre.'',
						'tipo_r_v'=>''.$tipo_r_v.'',
						'data_abate'=>''.$data_abate.'',
						'peso_carcasa'=>''.number_format(doubleval($peso_carcasa),2,',','.').'',
						'pesovivo'=>''.number_format(doubleval($peso_vivo_cabeca),2,',','.').'',
						'tipo'=>$tipo
					));	



				}

			}else if($tipo == 'S'){
				// detalhe de nota de saida

				$daosaida = new NotasSaiTxtDAO();
				$vetsaida = $daosaida->ListaNotasSaiEmpUm($numero);
				$numsaida = count($vetsaida);
				$xmlstr	  ="";
				if($numsaida > 0){
					$notasai = $vetsaida[0];
					$xmlstr  = $notasai->getXml();						
				}

				$daosa1 = new NotasSa1TxtDAO();
				$vetsa1 = $daosa1->DetalheNotaSaida($numero,$comptenc,$cnpj_emp);
				$numsa1 = count($vetsa1);
				
				for($i = 0; $i < $numsa1; $i++){
					
					$notasa1 		 = $vetsa1[$i];
						
					$id 		      = $notasa1->getCodigo();
					$numero_nota      = $notasa1->getNumeroNota();
					$data_emissao     = $notasa1->getDataEmissao();
					$cnpj_cpf	      = $notasa1->getCnpjCpf();
					$codigo_produto   = $notasa1->getCodigoProduto();
					$desc_prod		  = $notasa1->getDescProd();
					$qtd_pecas	      = $notasa1->getQtdPecas();
					$qtd_prod		  = $notasa1->getProdQtd();
					$peso			  = $notasa1->getPeso();
					$preco_unitario   = $notasa1->getPrecoUnitario();
					$preco_quilo      = $notasa1->getPrecoUnitario();
					$ent_sai		  = $notasa1->getEntSai();
					$numero_item_nota = $notasa1->getNumeroItemNota();		
					$insc_estadual    = $notasa1->getInscEstadual();
					$cfop			  = $notasa1->getCfop();
					$aliquota_icms    = $notasa1->getAliquotaIcms();

					if($xmlstr != ''){
						$dom = new domDocument();
						$dom->loadXML($xmlstr);
						$xml = simplexml_import_dom($dom);
					}
					
					if(empty($qtd_prod)){
						$qtd_prod = $peso;
					}

					$subtotal         = $preco_unitario * trim($qtd_prod);
					$data_abate 	  = "";
					$qtd_cabeca       = 0;
					$tipo_r_v         = 'R';
					$peso_carcasa	  = 0;
					$peso_vivo_cabeca = 0;
					
					$daop = new ProdFrigTxtDAO();		
					$vetp =	$daop->ListaRelacionado($codigo_produto,$cnpj_emp);
					$nump = count($vetp);	

					if($nump > 0){

						$rel = $vetp[0];

						$idre 	   = $rel->getCodProd();
						$id_rel    = $rel->getCodSecretaria();
						$descricao = $desc_prod;
						$descricaoprosecre = $rel->getDesProdSecretaria();
					}else{
						$id_rel    = "";
						$descricao = $desc_prod;
						$idre	   = ""; 
						$descricaoprosecre = "";
					}	

					array_push($result,array(
						'id'=>''.$id.'',
						'codigo_produto'=>''.$codigo_produto.'',
						'qtd_cabeca'=>''.$qtd_cabeca.'',
						'qtd_prod'=>''.$qtd_prod.'',
						'qCom'=>''.number_format($peso,2,',','.').'',
						'preco_quilo'=>''.number_format($preco_quilo,2,',','.').'',
						'subtotal'=>''.number_format(doubleval($subtotal),2,',','.').'',
						'desc_produto'=>''.$descricao.'',
						'id_rel'=>''.$id_rel.'',
						'descricao'=>''.$descricaoprosecre.'',
						'idre'=>''.$idre.'',
						'tipo_r_v'=>''.$tipo_r_v.'',
						'data_abate'=>''.$data_abate.'',
						'peso_carcasa'=>''.number_format(doubleval($peso_carcasa),2,',','.').'',
						'pesovivo'=>''.number_format(doubleval($peso_vivo_cabeca),2,',','.').'',
						'tipo'=>$tipo
					));


				}

			}

			echo json_encode($result);


		break;


		case 'excluir': 
			
			$numero  = $_REQUEST['cod'];
			$entsai  = $_REQUEST['entsai'];
			$result  = array();	
				
			if($entsai[0] == 'S'){
			
				$daosai = new NotasSaiTxtDAO();
				$vetsai = $daosai->PegaExclusao($numero,$_SESSION['cnpj']);
				$numsai = count($vetsai);
					
				
				for($i = 0; $i < $numsai; $i++){
				
					$notasaitxt = $vetsai[$i];
				
					$id 	 = $notasaitxt->getCodigo();
					
					$notasai = new NotasSaiTxt();	
						
					$notasai->setCodigo($id);	
					$notasai->setCnpjEmp($_SESSION['cnpj']);
					
					$daosai->deletar($notasai);
					
				}
				
				
				$daosa = new NotasSa1TxtDAO();
				$vetsa = $daosa->PegaExclusao($numero,$_SESSION['cnpj']);
				$numsa = count($vetsa);
			
				for($x = 0; $x < $numsa; $x++){
					
					$notasa1txt = $vetsa[$x];
						
					$idsai 	    = $notasa1txt->getCodigo();	
						
					$sa1 = new NotasSa1Txt();	
					
					$sa1->setCodigo($idsai);
					$sa1->setCnpjEmp($_SESSION['cnpj']);
										
					$daosa->deletar($sa1);
						
				}	
			
				array_push($result,array(
						'id'=>''.$numero.'',
						'msg'=>'Deletado com sucesso !'
				));	
				
				
			}else if($entsai[0] == 'E'){
				
				$daoent = new NotasEntTxtDAO();
				$vetent = $daoent->PegaExclusao($numero,$_SESSION['cnpj']);
				$nument = count($vetent);
				
				for($i = 0; $i < $nument; $i++){
				
					$notasenttxt = $vetent[$i];
					
					$ident = $notasenttxt->getCodigo();
					
					$notasent = new NotasEntTxt();
					
					$notasent->setCodigo($ident);
					$notasent->setCnpjEmp($_SESSION['cnpj']);
					
					$daoent->deletar($notasent);
						
				} 
				
				$daoen = new NotasEn1TxtDAO();
				$veten = $daoen->PegaExclusao($numero,$_SESSION['cnpj']);
				$numen = count($veten);					
				
				
				for($x = 0; $x < $numen; $x++){
					
					$notasen1txt = $veten[$x];	
					
					$iden = $notasen1txt->getCodigo();
					
					$notasen1 = new NotasEn1Txt();
					
					$notasen1->setCodigo($iden);
					$notasen1->setCnpjEmp($_SESSION['cnpj']);
					
					$daoen->deletar($notasen1);
					
					
				}
				
				array_push($result,array(
						'id'=>''.$numero.'',
						'msg'=>'Deletado com sucesso !'
				));	
				
			}	
			
			echo json_encode($result);
			
		break;
		case 'updatenotas':
			
			/*echo "<pre>";			
			print_r($_REQUEST);*/
			
			$numeronota = $_REQUEST['numeronota'];
			$item       = $_REQUEST['item'];
			$tpnota     = $_REQUEST['tpnota'];
			$numitem    = count($item); 	
			$result		= array();
			if($tpnota != 'SAIDA'){
				for($i = 0; $i < $numitem; $i++){
				
					$items = $item[$i];
					
					$ncabeca     = str_replace(',', '.', str_replace('.', '', $items['ncabeca']));
					$dtabate     = implode("-", array_reverse(explode("/", "".$items['dtabate']."")));
					$id		     = $items['id'];
					$vivorend    = $items['vivorend'];
					$peso        = str_replace(',', '.', str_replace('.', '', $items['qCom']));
					$qtd_prod    = str_replace(',', '.', str_replace('.', '', $items['qtd_prod']));
					$preco_quilo = str_replace(',', '.', str_replace('.', '', $items['preco_quilo']));
					//$pesocarcasa = $vivorend == "R" ? str_replace(',', '.', str_replace('.', '', $items['npesocarcaca'])) : 0;
					//$pesovivo    = $vivorend == "V" ? str_replace(',', '.', str_replace('.', '', $items['npesovivo']))    : 0;

					/*if($vivorend == 'R'){
						$pesocarcasa = $peso;
						$pesovivo    = !empty($items['npesovivo']) ? str_replace(',', '.', str_replace('.', '', $items['npesovivo'])) : 0;
					}else if($vivorend == 'V'){
						$pesovivo    = $peso;	
						$pesocarcasa = !empty($items['npesocarcaca']) ? str_replace(',', '.', str_replace('.', '', $items['npesocarcaca'])) : 0;
					}*/

					if($vivorend == 'R'){
						//tirado o $peso rem
						$pesocarcasa = !empty($items['npesocarcaca']) ? str_replace(',', '.', str_replace('.', '', $items['npesocarcaca'])) : 0;
						$pesovivo    = !empty($items['npesovivo']) ? str_replace(',', '.', str_replace('.', '', $items['npesovivo'])) : 0;
					}else if($vivorend == 'V'){
						$pesovivo    = !empty($items['npesovivo']) ? str_replace(',', '.', str_replace('.', '', $items['npesovivo'])) : 0;	
						$pesocarcasa = !empty($items['npesocarcaca']) ? str_replace(',', '.', str_replace('.', '', $items['npesocarcaca'])) : 0;
					}

					if($items['ncabeca'] == "" or $items['dtabate'] == "" or $items['id'] == "" or $items['vivorend'] == ""){										
						
						array_push($result,array(
							'id'=>''.$id.'',
							'cprod'=>''.$items['cprod'].'',
							'numnota'=>''.$numeronota.'',
							'ncabeca'=>''.$items['ncabeca'].'',
							'vivorend'=>''.$items['vivorend'].'',
							'dtabate'=>''.$items['dtabate'].'',
							'msg'=>'algo não foi informado!',													
						));	
						
					}else{
						
													
						$notasen1txt = new NotasEn1Txt();
						
						$notasen1txt->setCodigo($id);
						$notasen1txt->setQtdCabeca($ncabeca);
						$notasen1txt->setDataAbate($dtabate);
						$notasen1txt->setTipo_R_V($vivorend);
						$notasen1txt->setPesoCarcasa($pesocarcasa);
						$notasen1txt->setPesoVivoCabeca($pesovivo);
						$notasen1txt->setProdQtd($qtd_prod);
						$notasen1txt->setPrecoQuilo($preco_quilo);

						$daonotasen1 = new NotasEn1TxtDAO();	
							
						$daonotasen1->update($notasen1txt);
							
						array_push($result,array(
							'id'=>''.$id.'',
							'cprod'=>''.$items['cprod'].'',
							'numnota'=>''.$numeronota.'',
							'ncabeca'=>''.$items['ncabeca'].'',
							'vivorend'=>''.$items['vivorend'].'',
							'pesocarcasa'=>''.$pesocarcasa.'',
							'pesovivo'=>''.$pesovivo.'',
							'dtabate'=>''.$dtabate.'',
							'msg'=>'',													
						));		
							
					}
					
					
				}
			}else{
				
				for($i = 0; $i < $numitem; $i++){
					$items = $item[$i];

					$id   		 = $items['id']; 
					$cprod 	     = $items['cprod']; 
					$qtd_prod    = str_replace(',', '.', str_replace('.', '', $items['qtd_prod'])); 
					$preco_quilo = str_replace(',', '.', str_replace('.', '', $items['preco_quilo'])); 
					$qCom        = str_replace(',', '.', str_replace('.', '', $items['qCom'])); 

					$notasa1txt = new NotasSa1Txt();

					$notasa1txt->setCodigo($id);
					$notasa1txt->setQtdPecas($qtd_prod);
					$notasa1txt->setPeso($qtd_prod);
					$notasa1txt->setPrecoUnitario($preco_quilo);		
					$notasa1txt->setProdQtd($qtd_prod);

					$dao = new NotasSa1TxtDAO();
					$dao->update($notasa1txt);
						
					array_push($result,array(
						'id'=>'',
						'cprod'=>''.$cprod.'',
						'numnota'=>''.$numeronota.'',
						'ncabeca'=>'',
						'vivorend'=>'',
						'pesocarcasa'=>'',
						'pesovivo'=>'',
						'dtabate'=>'',
						'msg'=>'',													
					));	

				}

			}
			$data = array('result'=>$result,'numeronota'=>''.$numeronota.'');
			
			echo json_encode($data);
			
		break;
		case 'updatecabecas':
					
			$ncabecas = str_replace(',', '.', str_replace('.', '', $_REQUEST['ncabecas']));	
			$cprod    = $_REQUEST['cprod'];	
			$nnota    = $_REQUEST['nnota'];	
			$iditem   = $_REQUEST['idseq'];	
			$result   = array();
				
			$daonotasen1 = new NotasEn1TxtDAO();
			$vetnotasen1 = $daonotasen1->VerificaIdCabecas($nnota,$cprod,$_SESSION['cnpj'],$iditem);	
			$numnotasen1 = count($vetnotasen1);	
				
			if($numnotasen1 > 0){
				
				$notasen1 = $vetnotasen1[0];
				
				$id   = $notasen1->getCodigo();
				
				$notasen1txt = new NotasEn1Txt();
					
				$notasen1txt->setCodigo($id);
				$notasen1txt->setQtdCabeca($ncabecas);
				
				$daonotasen1->updatecabecas($notasen1txt);
				
				array_push($result,array(
					'id'=>''.$id.'',
					'ncabecas'=>''.$ncabecas.'',
					'cprod'=>''.$cprod.'',
					'nnota'=>''.$nnota.'',
					'idseq'=>$iditem,
				));
			}	
				
			echo json_encode($result);	
				
		break;
		case 'pegaqtdcabeca':

			$cprod    = $_REQUEST['cprod'];	
			$nnota    = $_REQUEST['nnota'];	
			$iditem	  = $_REQUEST['iditem'];	
			$result   = array();

			$daonotasen1 = new NotasEn1TxtDAO();
			$vetnotasen1 = $daonotasen1->PegaQtdCabecas($nnota,$cprod,$_SESSION['cnpj'],$iditem);	
			$numnotasen1 = count($vetnotasen1);	

			if($numnotasen1 > 0){
				
				$notasen1   = $vetnotasen1[0];

				$qtd_cabeca = $notasen1->getQtdCabeca();

				array_push($result,array(					
					'ncabecas'=>''.$qtd_cabeca.'',					
				));

			}else{
				array_push($result,array(					
					'ncabecas'=>'',					
				));
			}

			echo json_encode($result);
		break;
		case 'updatevivorend':
					
			$vivorend 	  = $_REQUEST['vivorend'];	
			$cprod    	  = $_REQUEST['cprod'];	
			$nnota   	  = $_REQUEST['nnota'];	
			$npeso        = str_replace(',', '.', str_replace('.', '', $_REQUEST['qcom']));
			$idseq		  = $_REQUEST['idseq'];	
			$passa		  = $_REQUEST['passa'];
			$mesano 	  = $_REQUEST['mesano'];

			if($vivorend == 'R'){
				$pesocarcasa = $npeso;
				$pesovivo    = !empty($items['npesovivo']) ? str_replace(',', '.', str_replace('.', '', $items['npesovivo'])) : 0;
			}else if($vivorend == 'V'){
				$pesovivo    = $npeso;	
				$pesocarcasa = !empty($items['npesocarcaca']) ? str_replace(',', '.', str_replace('.', '', $items['npesocarcaca'])) : 0;
			}

			$result   = array();
				
			$daonotasen1 = new NotasEn1TxtDAO();
			$vetnotasen1 = $daonotasen1->VerificaIdCabecas($nnota,$cprod,$_SESSION['cnpj'],$idseq);	
			$numnotasen1 = count($vetnotasen1);	
				
			if($numnotasen1 > 0){
				
				$notasen1 = $vetnotasen1[0];
				
				$id   = $notasen1->getCodigo();
				
				$notasen1txt = new NotasEn1Txt();
					
				$notasen1txt->setCodigo($id);
				$notasen1txt->setTipo_R_V($vivorend);
				$notasen1txt->setPesoCarcasa($pesocarcasa);
				$notasen1txt->setPesoVivoCabeca($pesovivo);

				$daonotasen1->updatevivorend($notasen1txt);
				
				array_push($result,array(
					'id'=>''.$id.'',
					'vivorend'=>''.$vivorend.'',
					'cprod'=>''.$cprod.'',
					'nnota'=>''.$nnota.'',
					'npesocarcaca'=>''.$pesocarcasa.'',
					'npesovivo'=>''.$pesovivo.''
				));
			}	


			if($passa == 'true'){
				//echo "aqui: {$passa}";
				$vetpassavr = $daonotasen1->getVivoRendimentoVazio($mesano,$_SESSION['cnpj']);
				$numpassavr = count($vetpassavr);
				for($i = 0; $i < $numpassavr; $i++){
					
					$notasen1 = $vetpassavr[$i];

					$idn 			  = $notasen1->getCodigo();			
					$peso_vivo_cabeca = $notasen1->getPesoVivoCabeca();
					$peso_carcasa     = $notasen1->getPesoCarcasa();	
					$codprod          = $notasen1->getCodigoProduto();		
					$numnota	      = $notasen1->getNumeroNota();
					

					if($vivorend == 'R'){
						if(empty($peso_carcasa)){
							$peso_carcasa = $peso_vivo_cabeca;
						}else{
							$peso_carcasa = $peso_carcasa;
						}

						$peso_vivo_cabeca = 0;	
										
					}else if($vivorend == 'V'){
						
						if(empty($peso_vivo_cabeca)){
							$peso_vivo_cabeca = $peso_carcasa;
						}else{
							$peso_vivo_cabeca = $peso_vivo_cabeca;
						}

						$peso_carcasa = 0;	
					}

					$notasen1txt2 = new NotasEn1Txt();
					
					$notasen1txt2->setCodigo($idn);
					$notasen1txt2->setTipo_R_V($vivorend);
					$notasen1txt2->setPesoCarcasa($peso_carcasa);
					$notasen1txt2->setPesoVivoCabeca($peso_vivo_cabeca);

					$daonotasen1->updatevivorend($notasen1txt2);

					array_push($result,array(
						'id'=>''.$idn.'',
						'vivorend'=>''.$vivorend.'',
						'cprod'=>''.$codprod.'',
						'nnota'=>''.$numnota.'',
						'npesocarcaca'=>''.$peso_carcasa.'',
						'npesovivo'=>''.$peso_vivo_cabeca.''
					));

				}

			}


				
			echo json_encode($result);	
				
		break;
				
		case 'excluirnotas':
		
			$result   = array();
			$destino  = "../arquivos/{$_SESSION['cnpj']}/importado/";
			$pasta    = opendir($destino);	
				
			while ($arquivo = readdir($pasta)){

				if ($arquivo != "." && $arquivo != ".."){
					
					$xml =  simplexml_load_file($destino.$arquivo);
					
					if($xml->NFe->infNFe->ide->tpNF == 0){
						
						$numero = $xml->NFe->infNFe->ide->nNF;
							
						$daoent = new NotasEntTxtDAO();
						$vetent = $daoent->PegaExclusao($numero,$_SESSION['cnpj']);
						$nument = count($vetent);
						
						for($i = 0; $i < $nument; $i++){
						
							$notasenttxt = $vetent[$i];
							
							$ident = $notasenttxt->getCodigo();
							
							$notasent = new NotasEntTxt();
							
							$notasent->setCodigo($ident);
							$notasent->setCnpjEmp($_SESSION['cnpj']);
							
							$daoent->deletar($notasent);
								
						} 
						
						$daoen = new NotasEn1TxtDAO();
						$veten = $daoen->PegaExclusao($numero,$_SESSION['cnpj']);
						$numen = count($veten);					
						
						
						for($x = 0; $x < $numen; $x++){
							
							$notasen1txt = $veten[$x];	
							
							$iden = $notasen1txt->getCodigo();
							
							$notasen1 = new NotasEn1Txt();
							
							$notasen1->setCodigo($iden);
							$notasen1->setCnpjEmp($_SESSION['cnpj']);
							
							$daoen->deletar($notasen1);
														
						}
						
						array_push($result,array(
							'caminho'=>''.$arquivo.'',
							'nfecod'=>''.$numero.'',													
						));		
						
					}else{
						
						$numero = $xml->NFe->infNFe->ide->nNF;
						
						$daosai = new NotasSaiTxtDAO();
						$vetsai = $daosai->PegaExclusao($numero,$_SESSION['cnpj']);
						$numsai = count($vetsai);
							
						
						for($i = 0; $i < $numsai; $i++){
						
							$notasaitxt = $vetsai[$i];
						
							$id 	 = $notasaitxt->getCodigo();
							
							$notasai = new NotasSaiTxt();	
								
							$notasai->setCodigo($id);	
							$notasai->setCnpjEmp($_SESSION['cnpj']);
							
							$daosai->deletar($notasai);
							
						}
						
						
						$daosa = new NotasSa1TxtDAO();
						$vetsa = $daosa->PegaExclusao($numero,$_SESSION['cnpj']);
						$numsa = count($vetsa);
					
						for($x = 0; $x < $numsa; $x++){
							
							$notasa1txt = $vetsa[$x];
								
							$idsai 	    = $notasa1txt->getCodigo();	
								
							$sa1 = new NotasSa1Txt();	
							
							$sa1->setCodigo($idsai);
							$sa1->setCnpjEmp($_SESSION['cnpj']);
												
							$daosa->deletar($sa1);
								
						}
						
						array_push($result,array(
							'caminho'=>''.$arquivo.'',
							'nfecod'=>''.$numero.'',													
						));	
					
					}
				}
				
			}	
			
			echo json_encode($result);
			
		break;
		case 'excluirnotasdesconsiderada':
		
			$result   = array();
			$destino  = "../arquivos/{$_SESSION['cnpj']}/removexml/";
			$pasta    = opendir($destino);	
				
			while ($arquivo = readdir($pasta)){

				if ($arquivo != "." && $arquivo != ".."){
					
					$xml =  simplexml_load_file($destino.$arquivo);
					
					if($xml->NFe->infNFe->ide->tpNF == 0){
						
						$numero = $xml->NFe->infNFe->ide->nNF;
							
						$daoent = new NotasEntTxtDAO();
						$vetent = $daoent->PegaExclusao($numero,$_SESSION['cnpj']);
						$nument = count($vetent);
						
						for($i = 0; $i < $nument; $i++){
						
							$notasenttxt = $vetent[$i];
							
							$ident = $notasenttxt->getCodigo();
							
							$notasent = new NotasEntTxt();
							
							$notasent->setCodigo($ident);
							$notasent->setCnpjEmp($_SESSION['cnpj']);
							
							$daoent->deletar($notasent);
								
						} 
						
						$daoen = new NotasEn1TxtDAO();
						$veten = $daoen->PegaExclusao($numero,$_SESSION['cnpj']);
						$numen = count($veten);					
						
						
						for($x = 0; $x < $numen; $x++){
							
							$notasen1txt = $veten[$x];	
							
							$iden = $notasen1txt->getCodigo();
							
							$notasen1 = new NotasEn1Txt();
							
							$notasen1->setCodigo($iden);
							$notasen1->setCnpjEmp($_SESSION['cnpj']);
							
							$daoen->deletar($notasen1);
														
						}
						
						array_push($result,array(
							'caminho'=>''.$arquivo.'',
							'nfecod'=>''.$numero.'',													
						));		
						
					}else{
						
						$numero = $xml->NFe->infNFe->ide->nNF;
						
						$daosai = new NotasSaiTxtDAO();
						$vetsai = $daosai->PegaExclusao($numero,$_SESSION['cnpj']);
						$numsai = count($vetsai);
							
						
						for($i = 0; $i < $numsai; $i++){
						
							$notasaitxt = $vetsai[$i];
						
							$id 	 = $notasaitxt->getCodigo();
							
							$notasai = new NotasSaiTxt();	
								
							$notasai->setCodigo($id);	
							$notasai->setCnpjEmp($_SESSION['cnpj']);
							
							$daosai->deletar($notasai);
							
						}
						
						
						$daosa = new NotasSa1TxtDAO();
						$vetsa = $daosa->PegaExclusao($numero,$_SESSION['cnpj']);
						$numsa = count($vetsa);
					
						for($x = 0; $x < $numsa; $x++){
							
							$notasa1txt = $vetsa[$x];
								
							$idsai 	    = $notasa1txt->getCodigo();	
								
							$sa1 = new NotasSa1Txt();	
							
							$sa1->setCodigo($idsai);
							$sa1->setCnpjEmp($_SESSION['cnpj']);
												
							$daosa->deletar($sa1);
								
						}
						
						array_push($result,array(
							'caminho'=>''.$arquivo.'',
							'nfecod'=>''.$numero.'',													
						));	
					
					}
				}
				
			}	
			
			echo json_encode($result);
			
		break;	

		case 'excluirnotasdesconsiderada2':

			$cfop   = $_POST['cfop'];
			$cnpj   = $_SESSION['cnpj'];
			$mesano = $_POST['mesano']; 

			
			/* NOTAS DE SAIDA DETALHE*/

			$daosai1 = new NotasSa1TxtDAO();
			$vetsai1 = $daosai1->PegaExclusaoCfopDesconsiderar($cfop,$cnpj,$mesano);
			$numsai1 = count($vetsai1);
			$notassai= array();

			for($i = 0; $i < $numsai1; $i++){

				$notasa1txt = $vetsai1[$i];

				$idsai1     = $notasa1txt->getCodigo();
				$numeronota = $notasa1txt->getNumeroNota();

				$sa1 = new NotasSa1Txt();	
							
				$sa1->setCodigo($idsai1);
				$sa1->setCnpjEmp($cnpj);
									
				$daosai1->deletar($sa1);								

				array_push($notassai,array(
					'nota'=>''.$numeronota.''
				));

			}
			
			/* FINAL NOTAS DE SAIDA DETALHE*/
			/* NOTAS DE SAIDA */
			$dados_arrsai   = array_unique($notassai, SORT_REGULAR); 
			$integrantes2   = "";
			$xx			    = 0;
			$sinals		    = ", ";
			$sizes 		    = count($dados_arrsai);

			if($sizes > 0){
				
				foreach($dados_arrsai as $keys=>$values){

					if($xx == $sizes-1) 
						$sinals = "  ";
					elseif($xx == $sizes) 
						$sinals = ".";

					$integrantes2 .= "'".$values['nota']."'".$sinals;
					$xx++;

				}

				$daonotasai = new NotasSaiTxtDAO();
				$vetnotasai = $daonotasai->PegaNotasDesconcideradaExcluir($integrantes2,$cnpj,$mesano); 
				$numnotasai = count($vetnotasai);

				for($i = 0; $i <  $numnotasai; $i++){
					$notasaitxt = $vetnotasai[$i];

					$idsai = $notasaitxt->getCodigo();

					$notasai = new NotasSaiTxt();	
									
					$notasai->setCodigo($idsai);	
					$notasai->setCnpjEmp($cnpj);
					
					$daonotasai->deletar($notasai);

				}
			}
			/* FINAL NOTAS DE SAIDA */

			/* NOTAS DE ENTRADA DETALHE*/
			$daoent1  = new NotasEn1TxtDAO();
			$vetent1  = $daoent1->PegaExclusaoCfopDesconsiderar($cfop,$cnpj,$mesano);
			$nument1  = count($vetent1);
			$notasent = array();

			for($i = 0; $i < $nument1; $i++){

				$notasen1txt = $vetent1[$i];
				$ident1 	 = $notasen1txt->getCodigo();
				$numero_nota = $notasen1txt->getNumeroNota();

				$notasen1 = new NotasEn1Txt();
							
				$notasen1->setCodigo($ident1);
				$notasen1->setCnpjEmp($cnpj);
				
				$daoen->deletar($notasen1);

				array_push($notasent,array(
					'nota'=>''.$numero_nota.''
				));

			}

			/*FINAL NOTAS DE ENTRADA DETALHE*/

			/* NOTAS DE ENTRADA */

			$dados_arr   = array_unique($notasent, SORT_REGULAR); 
			$integrantes = "";
			$x 			 = 0;
			$sinal 		 = ", ";
			$size 		 = count($dados_arr);	

			if($size > 0){
				foreach($dados_arr as $key=>$value){

					if($x == $size-1) 
						$sinal = "  ";
					elseif($x == $size) 
						$sinal = ".";

					$integrantes .= "'".$value['nota']."'".$sinal;
					$x++;

				}

				$daoent = new NotasEntTxtDAO();
				$vetent = $daoent->PegaExclusaoCfopDesconciderar($integrantes,$cnpj,$mesano);
				$nument = count($vetent); 

				for($i = 0; $i < $nument; $i++){

					$notasenttxt = $vetent[$i];
					$ident 		 = $notasenttxt->getCodigo();

					$notasent = new NotasEntTxt();
								
					$notasent->setCodigo($ident);
					$notasent->setCnpjEmp($cnpj);
					
					$daoent->deletar($notasent);

				}
			}
			/*FINAL NOTAS DE ENTRADA */
			$result = array();
			array_push($result,array(
				'msg'=>'Deletado com sucesso'
			));	

			echo json_encode($result);

		break;
		case 'regrava':
			
			$data = array();
			
			foreach($_REQUEST['filenames'] as $arquivo){
	
				if($xml =  simplexml_load_file($arquivo)){
					
					
					//$xml->NFe->infNFe->ide->nNF.
					
					if($xml->NFe->infNFe->det->prod->CFOP < 5000){
						//NOTA DE ENTRADA
						
						
							$dEmi         = explode('T',$xml->NFe->infNFe->ide->dhEmi);
							
							$notasenttxt = new NotasEntTxt();
					
							$notasenttxt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
							$notasenttxt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
							$notasenttxt->setCnpjCpf(trim($xml->NFe->infNFe->dest->CNPJ));
							$notasenttxt->setTipoV_R_A('');
							$notasenttxt->setValorTotalNota(trim($xml->NFe->infNFe->total->ICMSTot->vNF));
							$notasenttxt->setGta('');
							$notasenttxt->setNumeroNotaProdutorIni('');
							$notasenttxt->setNumeroNotaProdutorFin('');
							$notasenttxt->setCondenas('');
							$notasenttxt->setAbate('');
							$notasenttxt->setInscProdutor(trim($xml->NFe->infNFe->dest->IE));
							$notasenttxt->setCnpjEmp($_SESSION['cnpj']);
							
							$daonotasenttxt = new NotasEntTxtDAO();							
							$daonotasenttxt->inserir($notasenttxt);
							
							$i = 0; 
							foreach($xml->NFe->infNFe->det as $prod){
								
								
								
								$notasen1 = new NotasEn1Txt();
			
								$notasen1->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
								$notasen1->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
								$notasen1->setCnpjCpf(trim($xml->NFe->infNFe->dest->CNPJ));
								$notasen1->setCodigoProduto(trim($prod->prod->cProd));
								$notasen1->setQtdCabeca(0);
								$notasen1->setPesoVivoCabeca(trim($prod->prod->qCom));
								$notasen1->setPesoCarcasa(0);
								$notasen1->setPrecoQuilo(trim($prod->prod->vUnCom));
								$notasen1->setNumeroItemNota(($i+1));
								$notasen1->setInsEstadual(trim($xml->NFe->infNFe->dest->IE));
								$notasen1->setDataAbate(NULL);
								$notasen1->setTipo_R_V('');
								$notasen1->setCfop(trim($prod->prod->CFOP));
								$notasen1->setAliquotaIcms(0);
								$notasen1->setCnpjEmp($_SESSION['cnpj']);
								
								$daonotasen1 = new NotasEn1TxtDAO();
								$daonotasen1->inserir($notasen1);
								
								$i++;
							}
							
							array_push($data,array(
									
								'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
								'chave'=>''.$xml->protNFe->infProt->chNFe.'',
								'entsai'=>'E',
								'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
								'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
								'caminho'=>''.$arquivo.'',
								'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',
								'msg'=>''									
							));	
							
						
					}else{
						
						   $dEmi       = explode('T',$xml->NFe->infNFe->ide->dhEmi);
					
						
						
							$notasaitxt = new NotasSaiTxt();
					
							$notasaitxt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
							$notasaitxt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
							$notasaitxt->setCnpjCpf(trim($xml->NFe->infNFe->dest->CNPJ));
							$notasaitxt->setValorTotalNota(trim($xml->NFe->infNFe->total->ICMSTot->vNF));
							$notasaitxt->setValorIcms(trim($xml->NFe->infNFe->total->ICMSTot->vICMS));
							$notasaitxt->setValorIcmsSubs(trim($xml->NFe->infNFe->total->ICMSTot->vST));
							$notasaitxt->setEntSai('S');
							$notasaitxt->setInscEstadual(trim($xml->NFe->infNFe->dest->IE));
							$notasaitxt->setCfop(trim($xml->NFe->infNFe->det->prod->CFOP));
							$notasaitxt->setCnpjEmp($_SESSION['cnpj']);
							
							$daonotasaitxt = new NotasSaiTxtDAO();							
							$daonotasaitxt->inserir($notasaitxt);
							
							$i = 0; 
							foreach($xml->NFe->infNFe->det as $prod){
								
								
								$notasa1txt = new NotasSa1Txt();
					
								$notasa1txt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
								$notasa1txt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
								$notasa1txt->setCnpjCpf(trim($xml->NFe->infNFe->dest->CNPJ));
								$notasa1txt->setCodigoProduto(trim($prod->prod->cProd));
								$notasa1txt->setQtdPecas(trim(0));
								$notasa1txt->setPeso(trim($prod->prod->qCom));
								$notasa1txt->setPrecoUnitario(trim($prod->prod->vUnCom));
								$notasa1txt->setEntSai('S');
								$notasa1txt->setNumeroItemNota(($i+1));		
								$notasa1txt->setInscEstadual(trim($xml->NFe->infNFe->dest->IE));
								$notasa1txt->setCfop(trim($xml->NFe->infNFe->det->prod->CFOP));
								$notasa1txt->setAliquotaIcms(0);
								$notasa1txt->setCnpjEmp($_SESSION['cnpj']);
								
								$daonotasa1txt = new NotasSa1TxtDAO();
								
								$daonotasa1txt->inserir($notasa1txt);
								$i++;
							}
						
							array_push($data,array(
								'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
								'chave'=>''.$xml->protNFe->infProt->chNFe.'',
								'entsai'=>'S',
								'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
								'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
								'caminho'=>'',
								'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',	
								'msg'=>''								
							));	
						
									
					}
												
					
						
				}else{
					
					$error = true;
					
				}
				
			}
			echo json_encode($data);
		
		break;	
		case 'calcula':
		
			/*print_r($_FILES);*/
			$func		   = new FuncoesDAO();
			$arquivos 	   = $_FILES;			
			$num 	  	   = count($_FILES);
			$num_entrada   = 0;
			$num_saida     = 0;
			$total_entrada = 0;
			$total_saida   = 0;
			$data		   = array();
			$erros		   = "";
			$erro          = "";
			$data_menor    = date('Y-m-d');
			$data_maior    = '1900-01-01';	
			
			for($i = 0; $i < $num; $i++){
			
				$arq = $_FILES[$i];
				
					
				$arquivo = $arq['tmp_name'];
				
				//$string = simplexml_load_string($arquivo);
				$erros  = $func->is_valid_xml($arquivo,$arq['name']);
				
				
				if($erros == ""){
					
					if($xml =  simplexml_load_file($arquivo)){
						
						$dEmi    = explode('T',$xml->NFe->infNFe->ide->dhEmi);
						//$dEmi[0]		
						
						if(strtotime($dEmi[0]) < strtotime($data_menor)){
							$data_menor = $dEmi[0];
						}
						
						if(strtotime($dEmi[0]) > strtotime($data_maior)){
							$data_maior = $dEmi[0];
						}
										
						if($xml->NFe->infNFe->ide->tpNF == 0){
						
						//entrada
							
						$total_entrada = $total_entrada + $xml->NFe->infNFe->total->ICMSTot->vNF;				
											
						$num_entrada++;
						}else{
							
						//saida	
						
						$total_saida = $total_saida  + $xml->NFe->infNFe->total->ICMSTot->vProd;
						
						$num_saida++;					
						}	
					
					}
				}else{
				
					$erro .= $erros;
				}
			}
			
			array_push($data,array(
				'total_entrada'=>''.number_format($total_entrada,2,',','.').'',
				'total_saida'=>''.number_format($total_saida,2,',','.').'',
				'num_entrada'=>''.$num_entrada.'',
				'num_saida'=>''.$num_saida.'',
				'data_menor'=>''.date('d/m/Y',strtotime($data_menor)).'',
				'data_maior'=>''.date('d/m/Y',strtotime($data_maior)).'',
				'msg'=>''.$erro.'',								
			));	
			
			echo json_encode($data);
		break;	
		case 'calculadados':
				error_reporting(E_ALL);
				ini_set('display_errors', 'On');
				$unzipper 	   = new Unzipper();
				$func		   = new FuncoesDAO();
				$arquivos 	   = $_FILES;
				$num 	  	   = count($_FILES);
				$destino	   = "../arquivos/{$_SESSION['cnpj']}/importado/";
				$num_entrada   = 0;
				$num_saida     = 0;
				$total_entrada = 0;
				$total_saida   = 0;
				$erro		   = array();
				$result		   = array();
				$dados		   = array();
				$dadosn		   = array();
				$mesano 	   = $_POST['dtmesano'];	

				for($i = 0; $i < $num; $i++){
					
					$arq 		  = $_FILES[$i];
					
					$nome_arquivo = $arq['name'];
					$arquivo 	  = $arq['tmp_name'];
					$ext          = $func->getExtension($nome_arquivo);
					
					if($ext == 'rar' or $ext == 'zip'){
							
						if($ext == 'zip'){
							
							//aqui desconpacta o arquivo .zip
							//$zip = new ZipArchive;
							//$zip->open($arquivo);	

							try{
								$func->zip_flatten($arquivo,$destino);
							}catch(Exception $e){
								array_push($erro,array(
									'msg'=>'O arquivo não pode ser descompactado , arquivo zip com defeito!<br/>Exceção capturada: '.$e->getMessage().'',									
								));	
							}	
							/*if($zip->extractTo($destino) == TRUE){
								
							   }else{
								array_push($erro,array(
									'msg'=>'O arquivo não pode ser descompactado , arquivo zip com defeito!'									
								));
							}*/
														
						}else{
							
							//aqui desconpacta o arquivo .rar
							
							//$rar = new RarArchiver($arquivo);
							//$rar->extractTo($destino);	
							
							array_push($erro,array(
									'msg'=>'O arquivo não pode ser descompactado , arquivo RAR estamos em desenvolvimento para esse especifico arquivo!'									
								));
						}						
						
					}else{						
						
						if(move_uploaded_file($arquivo, $destino.basename($nome_arquivo))){
							
						}else{

							array_push($erro,array(
									'msg'=>'O arquivo não pode ser Movido para pasta, arquivo com defeito!'									
							));
						}
																		
					}
																									
				}												
				
				$daocf  = new CfopDAO();

				if(empty($erro)){
					//aqui abrindo a pasta para passa o arquivo
					$pasta 				= opendir($destino);
					
					$_SESSION['apura']['mesano'] = $mesano;

					while ($arquivo = readdir($pasta)){

						if ($arquivo != "." && $arquivo != ".."){

							$xml 	 =  simplexml_load_file($destino.$arquivo);
							$xml     = $func->verificatagxml($xml);

							if(!empty($xml->protNFe->infProt->cStat)){
								if($xml->protNFe->infProt->cStat == 100){	

									$ent_sai = $xml->NFe->infNFe->ide->tpNF == 0 ? 'ENTRADA': 'SAIDA';
									
									if($xml->NFe->infNFe->dest->CNPJ == $_SESSION['cnpj'] or $xml->NFe->infNFe->emit->CNPJ == $_SESSION['cnpj']){

										$vetcf   = $daocf->VerificaRelacionamento(trim($xml->NFe->infNFe->det->prod->CFOP),$_SESSION['cnpj']);
										$numcf   = count($vetcf);
										
										if($numcf > 0){
											$vercfop = $vetcf[0];
											
											$codcfop   = $vercfop->getCodigo();
											$nomcfop   = $vercfop->getNome();
											$geraagreg = $vercfop->getGeraAgregar();
											$vinculado = $vercfop->getVinculado();
											$idvinculo = $vercfop->getIdVinculado();

											if($geraagreg != 2){
												$dtem        = explode('T',$xml->NFe->infNFe->ide->dhEmi);
												$dataemissao = date('m/Y',strtotime($dtem[0]));	
												if($mesano == $dataemissao){
													
													array_push($dados,array(
														'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
														'dhEmi'=>''.$dataemissao.'',
														'chave'=>''.$xml->protNFe->infProt->chNFe.'',
														'ent_sai'=>''.$ent_sai.'',
														'cli_for'=>''.$xml->NFe->infNFe->dest->xNome.'',
														'valor_nota'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
														'caminho'=>''.$arquivo.'',
														'tipo'=>0
													));
													
													if($xml->NFe->infNFe->ide->tpNF == 0){						
														//entrada
														$total_entrada = $total_entrada + $xml->NFe->infNFe->total->ICMSTot->vNF;															
														$num_entrada++;							
													}else{							
														//saida								
														$total_saida 	= $total_saida  + $xml->NFe->infNFe->total->ICMSTot->vNF;						
														$num_saida++;					
													}


													//Verificar se existe clientes na tabela empresastxt	
													$cnpj_cpf  = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
													$ie        = !empty($xml->NFe->infNFe->dest->IE) ? $xml->NFe->infNFe->dest->IE : '';
													
													if(empty($cnpj_cpf)){
														$cnpj_cpf = strlen(trim($xml->NFe->infNFe->emit->CNPJ)) == 14 ? trim($xml->NFe->infNFe->emit->CNPJ) : trim($xml->NFe->infNFe->emit->CPF);
													}
													
													if(empty($ie)){
														$ie        = !empty($xml->NFe->infNFe->emit->IE) ? $xml->NFe->infNFe->emit->IE : '';
													}
													
													$xNome = str_replace("'", "", str_replace('"', "", $xml->NFe->infNFe->dest->xNome));

													if(empty($xNome)){
														$xNome = str_replace("'", "", str_replace('"', "", $xml->NFe->infNFe->emit->xNome));														
													}


													$xMun = empty($xml->NFe->infNFe->dest->enderDest->xMun) ? $xml->NFe->infNFe->emit->enderEmit->xMun : $xml->NFe->infNFe->dest->enderDest->xMun;
													
													$UF   = empty($xml->NFe->infNFe->dest->enderDest->UF) ? $xml->NFe->infNFe->emit->enderEmit->UF : $xml->NFe->infNFe->dest->enderDest->UF;
													
													
													$daoemp    = new EmpresasTxtDAO();
													$vetemp    = $daoemp->VerificaSeExisteEmpresas($cnpj_cpf,$ie,$_SESSION['cnpj']);	
													$numemp    = count($vetemp);	

													if($numemp > 0){
														//se existe update	
														$emp 	= $vetemp[0];

														$codigo = $emp->getCodigo();

														$emptxt = new EmpresasTxt();
																
														$emptxt->setCodigo($codigo);	
														$emptxt->setCnpjCpf($cnpj_cpf);
														$emptxt->setInscEstadual($ie);
														$emptxt->setRazao($xNome);
														$emptxt->setCidade($xMun);
														$emptxt->setUf($UF);
														$emptxt->setTipo('G');								

														$daoemp ->update($emptxt);		

													}else{
														//se não existe insere	
														$emptxt = new EmpresasTxt();

														$emptxt->setCnpjCpf($cnpj_cpf);
														$emptxt->setInscEstadual($ie);
														$emptxt->setRazao($xNome);
														$emptxt->setCidade($xMun);
														$emptxt->setUf($UF);
														$emptxt->setTipo('G');
														$emptxt->setCnpjEmp($_SESSION['cnpj']);	

														$daoemp->inserir($emptxt);	

													}

													foreach($xml->NFe->infNFe->det as $prod){

															//$prod->prod->cProd
															//$prod->prod->xProd

															$daopr = new ProdutosTxtDAO();
															$vetpr = $daopr->VerificaProdutoTxt($prod->prod->cProd,$_SESSION['cnpj']);
															$numpr = count($vetpr);

															if($numpr == 0){
																
																$prodtxt = new ProdutosTxt();
																	
																$prodtxt->setCodProd($prod->prod->cProd);
																$prodtxt->setDescProd(str_replace("'", "", str_replace('"', "", $prod->prod->xProd)));
																$prodtxt->setCnpjEmp($_SESSION['cnpj']);

																$daopr->inserir($prodtxt);

															}

													}

												
													//VERIFICAR QUAL O STATUS, SE O STATUS RECIBO ENTREGUE PELO PAULO PERGUNTA  

												}else{

													if(!empty($_REQUEST['dif'])){

														array_push($dados,array(
															'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
															'dhEmi'=>''.$dataemissao.'',
															'chave'=>''.$xml->protNFe->infProt->chNFe.'',
															'ent_sai'=>''.$ent_sai.'',
															'cli_for'=>''.$xml->NFe->infNFe->dest->xNome.'',
															'valor_nota'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
															'caminho'=>''.$arquivo.'',
															'tipo'=>0
														));
														
														if($xml->NFe->infNFe->ide->tpNF == 0){						
															//entrada
															$total_entrada = $total_entrada + $xml->NFe->infNFe->total->ICMSTot->vNF;															
															$num_entrada++;							
														}else{							
															//saida								
															$total_saida 	= $total_saida  + $xml->NFe->infNFe->total->ICMSTot->vNF;						
															$num_saida++;					
														}
	
	
														//Verificar se existe clientes na tabela empresastxt	
														$cnpj_cpf  = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
														$ie        = !empty($xml->NFe->infNFe->dest->IE) ? $xml->NFe->infNFe->dest->IE : '';
														
														if(empty($cnpj_cpf)){
														$cnpj_cpf = strlen(trim($xml->NFe->infNFe->emit->CNPJ)) == 14 ? trim($xml->NFe->infNFe->emit->CNPJ) : trim($xml->NFe->infNFe->emit->CPF);
														}
														
														if(empty($ie)){
															$ie        = !empty($xml->NFe->infNFe->emit->IE) ? $xml->NFe->infNFe->emit->IE : '';
														}
														
														$xNome = str_replace("'", "", str_replace('"', "", $xml->NFe->infNFe->dest->xNome));

														if(empty($xNome)){
															$xNome = str_replace("'", "", str_replace('"', "", $xml->NFe->infNFe->emit->xNome));														
														}


														$xMun = empty($xml->NFe->infNFe->dest->enderDest->xMun) ? $xml->NFe->infNFe->emit->enderEmit->xMun : $xml->NFe->infNFe->dest->enderDest->xMun;
														
														$UF   = empty($xml->NFe->infNFe->dest->enderDest->UF) ? $xml->NFe->infNFe->emit->enderEmit->UF : $xml->NFe->infNFe->dest->enderDest->UF;
														
														$daoemp    = new EmpresasTxtDAO();
														$vetemp    = $daoemp->VerificaSeExisteEmpresas($cnpj_cpf,$ie,$_SESSION['cnpj']);	
														$numemp    = count($vetemp);	
	
														if($numemp > 0){
															//se existe update	
															$emp 	= $vetemp[0];
	
															$codigo = $emp->getCodigo();
	
															$emptxt = new EmpresasTxt();
																	
															$emptxt->setCodigo($codigo);	
															$emptxt->setCnpjCpf($cnpj_cpf);
															$emptxt->setInscEstadual($ie);
															$emptxt->setRazao($xNome);
															$emptxt->setCidade($xMun);
															$emptxt->setUf($$UF);
															$emptxt->setTipo('G');								
	
															$daoemp ->update($emptxt);		
	
														}else{
															//se não existe insere	
															$emptxt = new EmpresasTxt();
	
															$emptxt->setCnpjCpf($cnpj_cpf);
															$emptxt->setInscEstadual($ie);
															$emptxt->setRazao($xNome);
															$emptxt->setCidade($xMun);
															$emptxt->setUf($UF);
															$emptxt->setTipo('G');
															$emptxt->setCnpjEmp($_SESSION['cnpj']);	
	
															$daoemp->inserir($emptxt);	
	
														}
	
														foreach($xml->NFe->infNFe->det as $prod){
	
																//$prod->prod->cProd
																//$prod->prod->xProd
	
																$daopr = new ProdutosTxtDAO();
																$vetpr = $daopr->VerificaProdutoTxt($prod->prod->cProd,$_SESSION['cnpj']);
																$numpr = count($vetpr);
	
																if($numpr == 0){
																	
																	$prodtxt = new ProdutosTxt();
																		
																	$prodtxt->setCodProd($prod->prod->cProd);
																	$prodtxt->setDescProd(str_replace("'", "", str_replace('"', "", $prod->prod->xProd)));
																	$prodtxt->setCnpjEmp($_SESSION['cnpj']);
	
																	$daopr->inserir($prodtxt);
	
																}
	
														}


													}else{


														array_push($dadosn,array(
															'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
															'dhEmi'=>''.$dataemissao.'',
															'chave'=>''.$xml->protNFe->infProt->chNFe.'',
															'ent_sai'=>''.$ent_sai.'',
															'cli_for'=>''.$xml->NFe->infNFe->dest->xNome.'',
															'valor_nota'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
															'caminho'=>'',
															'msg'=>'XML NÃO SÃO DESSA COMPETENCIA!',
															'tipo'=>1
														));		
	
														unlink($destino.$arquivo);	

													}

												}	

											}else{
												$dtem        = explode('T',$xml->NFe->infNFe->ide->dhEmi);
												$dataemissao = date('m/Y',strtotime($dtem[0]));	
												array_push($dadosn,array(
													'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
													'dhEmi'=>''.$dataemissao.'',
													'chave'=>''.$xml->protNFe->infProt->chNFe.'',
													'ent_sai'=>''.$ent_sai.'',
													'cli_for'=>''.$xml->NFe->infNFe->dest->xNome.'',
													'valor_nota'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
													'caminho'=>'',
													'msg'=>'CFOP '.trim($xml->NFe->infNFe->det->prod->CFOP).' PREVISTO PARA NÃO CONSIDERAR!',
													'tipo'=>2
												));	


												unlink($destino.$arquivo);
											}	

										}else{
											$dtem        = explode('T',$xml->NFe->infNFe->ide->dhEmi);
											$dataemissao = date('m/Y',strtotime($dtem[0]));
											array_push($dadosn,array(
												'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'dhEmi'=>''.$dataemissao.'',
												'chave'=>''.$xml->protNFe->infProt->chNFe.'',
												'ent_sai'=>''.$ent_sai.'',
												'cli_for'=>''.$xml->NFe->infNFe->dest->xNome.'',
												'valor_nota'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
												'caminho'=>'',
												'msg'=>'CFOP NÃO CADASTRADA!',
												'tipo'=>2
											));	


											unlink($destino.$arquivo);

										}//fim validade cfop

									}else{
										$dtem        = explode('T',$xml->NFe->infNFe->ide->dhEmi);
										$dataemissao = date('m/Y',strtotime($dtem[0]));
										$ent_sai = $xml->NFe->infNFe->ide->tpNF == 0 ? 'ENTRADA': 'SAIDA';
										array_push($dadosn,array(
												'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'dhEmi'=>''.$dataemissao.'',
												'chave'=>''.$xml->protNFe->infProt->chNFe.'',
												'ent_sai'=>''.$ent_sai.'',
												'cli_for'=>''.$xml->NFe->infNFe->dest->xNome.'',
												'valor_nota'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
												'caminho'=>'',
												'msg'=>'NOTA FISCAL NÃO É DESTINADA A ESSA EMPRESA!',
												'tipo'=>2
											));	
										unlink($destino.$arquivo);
										
									}

								}else{
									$xmlstr = file_get_contents($destino.$arquivo);		
									$doc = new DOMDocument();
									$doc->formatOutput = false;
									$doc->preserveWhiteSpace = false;
									$doc->loadXML($xmlstr, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

									$chave 		=  !empty($doc->getElementsByTagName('chNFe')->item(0)->nodeValue) ? $doc->getElementsByTagName('chNFe')->item(0)->nodeValue :'Sem Informção da chave';
									$descEvento =  !empty($doc->getElementsByTagName('descEvento')->item(0)->nodeValue) ? $doc->getElementsByTagName('descEvento')->item(0)->nodeValue : '';
									$nNF        =  !empty($doc->getElementsByTagName('nNF')->item(0)->nodeValue) ? $doc->getElementsByTagName('nNF')->item(0)->nodeValue : 'Sem Informação';
									$vNF        =  !empty($doc->getElementsByTagName('vNF')->item(0)->nodeValue) ? $doc->getElementsByTagName('vNF')->item(0)->nodeValue : '0.00';
									$ent_sai    =  !empty($doc->getElementsByTagName('tpNF')->item(0)->nodeValue) ? $doc->getElementsByTagName('tpNF')->item(0)->nodeValue : '';
									
									if(!empty($doc->getElementsByTagName('dhEmi')->item(0)->nodeValue)){
										$dtem = explode('T',$doc->getElementsByTagName('dhEmi')->item(0)->nodeValue);	
										$dataemissao = date('m/Y',strtotime($dtem[0]));
									}else{
										$dtem = explode('T',$doc->getElementsByTagName('dhEvento')->item(0)->nodeValue);	
										$dataemissao = date('m/Y',strtotime($dtem[0]));
									}

									if(!empty($doc->getElementsByTagName("dest")->item(0))){
										$det        = $doc->getElementsByTagName("dest")->item(0);
										$nomeDest   = $det->getElementsByTagName('xNome')->item(0)->nodeValue;
									}else{
										$nomeDest   = "Sem informação";
									}
									
									if(!empty($doc->getElementsByTagName("emit")->item(0))){
										$emit       = $doc->getElementsByTagName("emit")->item(0);			
										$nomeEmit   = $emit->getElementsByTagName('xNome')->item(0)->nodeValue;
									}else{
										$nomeEmit = "Sem Informação";
									}

									array_push($dadosn,array(
											'nNF'=>''.$nNF.'',
											'dhEmi'=>''.$dataemissao.'',
											'chave'=>''.$chave.'',
											'ent_sai'=>''.$ent_sai.'',
											'cli_for'=>''.$nomeDest.'',
											'valor_nota'=>''.number_format(doubleval($vNF),2,',','.').'',
											'caminho'=>'',
											'msg'=>'Não foi localizado a autorização da NF-e',
											'tipo'=>2
										));	
									unlink($destino.$arquivo);								
								}
							}else{
								//echo $arquivo;
								$xmlstr = file_get_contents($destino.$arquivo);
								$doc = new DOMDocument();
								$doc->formatOutput = false;
								$doc->preserveWhiteSpace = false;
								$doc->loadXML($xmlstr, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

								$chave 		=  !empty($doc->getElementsByTagName('chNFe')->item(0)->nodeValue) ? $doc->getElementsByTagName('chNFe')->item(0)->nodeValue :'Sem Informção da chave';
								$descEvento =  !empty($doc->getElementsByTagName('descEvento')->item(0)->nodeValue) ? $doc->getElementsByTagName('descEvento')->item(0)->nodeValue : '';
								$nNF        =  !empty($doc->getElementsByTagName('nNF')->item(0)->nodeValue) ? $doc->getElementsByTagName('nNF')->item(0)->nodeValue : 'Sem Informação';
								$vNF        =  !empty($doc->getElementsByTagName('vNF')->item(0)->nodeValue) ? $doc->getElementsByTagName('vNF')->item(0)->nodeValue : '0.00';
								$ent_sai    =  !empty($doc->getElementsByTagName('tpNF')->item(0)->nodeValue) ? $doc->getElementsByTagName('tpNF')->item(0)->nodeValue : $descEvento;
								
								if(!empty($doc->getElementsByTagName('dhEmi')->item(0)->nodeValue)){
									$dtem = explode('T',$doc->getElementsByTagName('dhEmi')->item(0)->nodeValue);	
									$dataemissao = date('m/Y',strtotime($dtem[0]));
								}else{
									$dtem = explode('T',$doc->getElementsByTagName('dhEvento')->item(0)->nodeValue);	
									$dataemissao = date('m/Y',strtotime($dtem[0]));
								}

								if(!empty($doc->getElementsByTagName("dest")->item(0))){
									$det        = $doc->getElementsByTagName("dest")->item(0);
									$nomeDest   = $det->getElementsByTagName('xNome')->item(0)->nodeValue;
								}else{
									$nomeDest   = "Sem informação";
								}
								
								if(!empty($doc->getElementsByTagName("emit")->item(0))){
									$emit       = $doc->getElementsByTagName("emit")->item(0);			
									$nomeEmit   = $emit->getElementsByTagName('xNome')->item(0)->nodeValue;
								}else{
									$nomeEmit = "Sem Informação";
								}

								array_push($dadosn,array(
										'nNF'=>''.$nNF.'',
										'dhEmi'=>''.$dataemissao.'',
										'chave'=>''.$chave.'',
										'ent_sai'=>''.$ent_sai.'',
										'cli_for'=>''.$nomeDest.'',
										'valor_nota'=>''.number_format(doubleval($vNF),2,',','.').'',
										'caminho'=>'',
										'msg'=>'Não foi localizado a autorização da NF-e',
										'tipo'=>2
									));	
								unlink($destino.$arquivo);

							}

						}

					}

					//$_SESSION['apura']['numero_entradas'] = $num_entrada;
					//$_SESSION['apura']['numero_saida']    = $num_saida;

					array_push($result,array(
						'total_entradas'=>''.number_format($total_entrada,2,',','.').'',
						'numero_entradas'=>''.$num_entrada.'',
						'total_saida'=>''.number_format($total_saida,2,',','.').'',
						'numero_saida'=>''.$num_saida.''
					));

					
				}
				
				if(!empty($dados)){
					
					$prot = new Protocolo();
					
					$prot->setCompetencia($mesano);
					$prot->setProtocolo('');
					$prot->setCripty('');
					$prot->setStatus(5);
					$prot->setCnpjEmp($_SESSION['cnpj']);
					$prot->setTipoArq(1);	
					//1 = xml 2 = txt 3 = manual	
					$daoprot = new ProtocoloDAO();
					$daoprot->inserir($prot);	

				}

				$data = array('dados'=>$result,'erro'=>$erro,'dados_grid'=>$dados,'dadosn'=>$dadosn);												
				echo json_encode($data);		
				
		break;
		case 'grava_valida2':
			
			$erro           = array();
			$dados          = array();
			$err_cfop       = array();
			$err_produto    = array();	
			$err_nota       = array();
			$err_v_r	    = array();
			
			$alert_nfunc    = array();
			$alert_vlfolha  = array();	
			$alert_icmsnorm = array();	
			$alert_icmsst   = array();	
			$alert_gta      = array();
			$mesano 		= $_REQUEST['mesano'];	
			$_SESSION['apura']['mesano'] = $mesano;
			$funcao   = new FuncoesDAO();	
			$daor     = new ProdFrigTxtDAO();	
			$daocf    = new CfopDAO();	

			$pathFile      = '../arquivos/'.$_SESSION['cnpj'].'/config.json';
			$configJson    = file_get_contents($pathFile);
			$installConfig = json_decode($configJson);
			
			//notas de entrada
			$daonotasenttxt = new NotasEntTxtDAO();
			$vetnotasenttxt = $daonotasenttxt->ListaNotasEntComp($mesano,$_SESSION['cnpj']);
			$numnotasenttxt = count($vetnotasenttxt);
			
			if($numnotasenttxt > 0){		
				for($i = 0; $i < $numnotasenttxt;$i++){
					
					$notasent = $vetnotasenttxt[$i];
					$id  	  = $notasent->getCodigo();
					$xmlstr	  = $notasent->getXml();
					
					$dom = new domDocument();
					$dom->loadXML($xmlstr);
					$xml = simplexml_import_dom($dom);
					$xml = $funcao->verificatagxml($xml);

					$dEmi         = explode('T',$xml->NFe->infNFe->ide->dhEmi);
					$cnpj_cpf     = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);				
					$ii = 0;
					foreach($xml->NFe->infNFe->det as $prod){
						
						$vetr = $daor->ListaRelacionado(trim($prod->prod->cProd),$_SESSION['cnpj']);
						$numr = count($vetr);
						
						if($numr == 0){
							
							array_push($err_produto,array(
								'codigo'=>''.trim($prod->prod->cProd).'',
								'cnota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
								'msg'=>' Código do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') :: Produto não relacionado com o produto do agregar ::',
								"dados"=> array(
									'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'entsai'=>'Entrada',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
								),
							));
						}
						
						$vetcf = $daocf->VerificaRelacionamento(trim($prod->prod->CFOP),$_SESSION['cnpj']);
						$numcf = count($vetcf);
						
						if($numcf > 0){
							$vercfop = $vetcf[0];
							
							$codcfop   = $vercfop->getCodigo();
							$nomcfop   = $vercfop->getNome();
							$geraagreg = $vercfop->getGeraAgregar();
							$vinculado = $vercfop->getVinculado();
							$idvinculo = $vercfop->getIdVinculado();
							
							if(empty($geraagreg)){
								$err_cfop[] = array(
									'idvinc'=>''.$idvinculo.'',
									'codigo'=>''.trim($prod->prod->CFOP).'',
									'nota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
									'arquivo'=>'',
									'msg'=>' CFOP ('.trim($prod->prod->CFOP).') sem indicação se irá "Considerar" ou "Desconsiderar" na apuração de crédito. ',
								);
							}																				
							
						}else{
							//não existe essa cfop no sistema
							$err_cfop[] = array(
								'codigo'=>''.trim($prod->prod->CFOP).'',
								'msg'=>' CFOP ('.trim($prod->prod->CFOP).') não Existe no sistema ou não esta gravado!  '
							);									
						}
						
						$daonotasen1 = new NotasEn1TxtDAO();
						$vetnotasen1 = $daonotasen1->VerificaCabecasPreenchidas($xml->NFe->infNFe->ide->nNF,trim($prod->prod->cProd),$_SESSION['cnpj'],($ii+1));
						$numnotasen1 = count($vetnotasen1);
						
						if($numnotasen1 > 0){
							
							$notasen1 = $vetnotasen1[0];
							
							$qtd_cabeca   = $notasen1->getQtdCabeca();
							$tipo_r_v     = $notasen1->getTipo_R_V();
							$peso_carcasa = $notasen1->getPesoCarcasa();

							if(empty($qtd_cabeca)){
								
									array_push($err_nota,array(
										'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'cProd'=>''.trim($prod->prod->cProd).'',
										'idseq'=>($ii+1),
										'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') esta faltando numero de cabeças a ser preenchido!',
									));
								
							}/*else if($peso_carcasa <= 0){
								array_push($err_nota,array(
										'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'cProd'=>''.trim($prod->prod->cProd).'',
										'msg'=>' Numero da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') esta faltando numero de cabeças a ser preenchido!',
								));	
							}*/
							
							
							if(empty($tipo_r_v)){
								array_push($err_v_r,array(
									'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'cProd'=>''.trim($prod->prod->cProd).'',
									'qCom'=>''.number_format(doubleval($prod->prod->qCom),2,',','.').'',
									'idseq'=>($ii+1),
									'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') <br/> Esta faltando Informar se é Vivo ou Rendimento!',
								));
								
							}
							
						}
						$ii++;
					}
					
					$daogta = new GtaTxtDAO();
					$vetgta = $daogta->GtaEmpresas($xml->NFe->infNFe->ide->nNF,$_SESSION['cnpj']);
					$numgta = count($vetgta);
						
					if($numgta == 0){
						
						array_push($alert_gta,array(
								'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
								'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') Não existem numeros de GTA informados!',
						));
						
					}


					array_push($dados,array(
							
						'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
						'chave'=>''.$xml->protNFe->infProt->chNFe.'',
						'entsai'=>'ENTRADA',
						'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
						'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
						'caminho'=>'',
						'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',
						'msg'=>''									
					));		
								
				}
			}
			
			//nota de saida
			$daonotasaitxt = new NotasSaiTxtDAO();
			$vetnotasaitxt = $daonotasaitxt->ListaNotasSaiEmp($mesano,$_SESSION['cnpj']); 
			$numnotasaitxt = count($vetnotasaitxt);
			
			if($numnotasaitxt > 0){
				
				for($x = 0; $x < $numnotasaitxt; $x++){
						
						$notasai = $vetnotasaitxt[$x];
						$id 	 = $notasai->getCodigo();
						$xmlstr2 = $notasai->getXml();
					
						$dom2 = new domDocument();
						$dom2->loadXML($xmlstr2);
						$xml2 = simplexml_import_dom($dom2);
						$xml2 = $funcao->verificatagxml($xml2);

						$dEmi       = explode('T',$xml2->NFe->infNFe->ide->dhEmi);
						$cnpj_cpf2   = strlen(trim($xml2->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml2->NFe->infNFe->dest->CNPJ) : trim($xml2->NFe->infNFe->dest->CPF);
						
						foreach($xml2->NFe->infNFe->det as $prod2){
							
							$vetrl = $daor->ListaRelacionado(trim($prod2->prod->cProd),$_SESSION['cnpj']);
							$numrl = count($vetrl);
							
							if($numrl == 0){
								
								array_push($err_produto,array(
									'codigo'=>''.trim($prod2->prod->cProd).'',
									'cnota'=>''.trim($xml2->NFe->infNFe->ide->nNF).'',
									'msg'=>' Código do produto ('.trim($prod2->prod->cProd).' '.$prod2->prod->xProd.') :: Produto não relacionado com o produto do agregar ::',
									"dados"=> array(
										'nNF'=>''.$xml2->NFe->infNFe->ide->nNF.'',
										'entsai'=>'Saida',
										'cliente'=>''.$xml2->NFe->infNFe->dest->xNome.'',
										'valor'=>''.number_format(doubleval($xml2->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
										'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
									),
								));
							}
							
							$vetcfs = $daocf->VerificaRelacionamento(trim($prod2->prod->CFOP),$_SESSION['cnpj']);
							$numcfs = count($vetcfs);
							
							if($numcfs > 0){
								$vercfops = $vetcfs[0];
								
								$codcfops   = $vercfops->getCodigo();
								$nomcfops   = $vercfops->getNome();
								$geraagregs = $vercfops->getGeraAgregar();
								$vinculados = $vercfops->getVinculado();
								$idvinculos = $vercfops->getIdVinculado();
								
								if(empty($geraagregs)){
									$err_cfop[] =array(
										'idvinc'=>''.$idvinculos.'',
										'codigo'=>''.trim($prod2->prod->CFOP).'',
										'nota'=>''.trim($xml2->NFe->infNFe->ide->nNF).'',
										'arquivo'=>'',
										'msg'=>' CFOP ('.trim($prod2->prod->CFOP).') sem indicação se irá "Considerar" ou "Desconsiderar" na apuração de crédito.',
									);
								}																														
								
								
							}else{
								//não existe essa cfop no sistema
								$err_cfop[] =array(
									'codigo'=>''.trim($prod2->prod->CFOP).'',
									'msg'=>' CFOP ('.trim($prod2->prod->CFOP).') não Existe no sistema ou não esta gravado!  '
								);									
							}
							
						}
						
						array_push($dados,array(
							'Numero'=>''.$xml2->NFe->infNFe->ide->nNF.'',
							'chave'=>''.$xml2->protNFe->infProt->chNFe.'',
							'entsai'=>'SAIDA',
							'cliente'=>''.$xml2->NFe->infNFe->dest->xNome.'',
							'valor'=>''.number_format(doubleval($xml2->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
							'caminho'=>'',
							'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',	
							'msg'=>''								
						));
						
				}
				
				
			}
				
			$daoprot = new ProtocoloDAO();
			$vetprot = $daoprot->ListaProtocoloPorEmmpresaCompetencia($mesano,$_SESSION['cnpj']);
			$numprot = count($vetprot);

			if($numprot > 0){

					$prot   = $vetprot[0];
					
					$idprot = $prot->getCodigo();	

					$prots  = new Protocolo();

					$prots->setCodigo($idprot);
					$prots->setStatus(7);	

					$daoprot->updateStatus($prots);	

			}

			$daofolha = new FolhaTxtDAO();
			$vetfolha = $daofolha->ValidaFolhaMes($_SESSION['cnpj'],$mesano);	
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
			$vetguianorm = $daoguia->ValidGuiaicmsNormal($_SESSION['cnpj'],$mesano);	
			$numguianorm = count($vetguianorm);	
				
			if($numguianorm == 0){
				
				array_push($alert_icmsnorm,array(
					'msg'=>'Valor do ICMS normal não informado !',							
				));
			}	
				
			$vetguiast = $daoguia->ValidGuiaicmsSt($_SESSION['cnpj'],$mesano);
			$numguiast = count($vetguiast);	
			
			if($numguiast == 0){
				
				array_push($alert_icmsst,array(
					'msg'=>'Valor do ICMS ST não informado !',
				));
			}	
				
				
			$erro_cfop = $funcao->array_sort($err_cfop,'codigo',SORT_ASC);
			$cfop_erro = array();	
			foreach($erro_cfop as $key=>$value){				
				array_push($cfop_erro,$value);					
			}
				
			$erro_produto = $funcao->array_sort($err_produto,'codigo',SORT_ASC);	
			$produto_erro = array();	
			foreach($erro_produto as $keys=>$values){
				array_push($produto_erro,$values);
				
			}	
				
				
			$data = array(
					'erro'=>array(
						'cfop'=>$cfop_erro,
						'produto'=>$produto_erro,
						'nota'=>$err_nota,
						'vivorendmento'=>$err_v_r,
					),
					'info'=>array(
						'funcionario'=>$alert_nfunc,
						'folha'=>$alert_vlfolha,
						'icmsnormal'=>$alert_icmsnorm,
						'icmsst'=>$alert_icmsst,
						'gta'=>$alert_gta,
						'num_entrada'=>$numnotasenttxt,
						'num_saida'=>$numnotasaitxt
					),
					'dados_grid'=>$dados);												
			echo json_encode($data);
			
		break;
		case 'grava_valida_teste':

				
			$destino        = "../arquivos/{$_SESSION['cnpj']}/importado/";	
			$erro           = array();
			$dados          = array();
			$err_cfop       = array();
			$err_produto    = array();	
			$err_nota       = array();
			$err_v_r	    = array();
			
			$alert_nfunc    = array();
			$alert_vlfolha  = array();	
			$alert_icmsnorm = array();	
			$alert_icmsst   = array();	
			$alert_gta      = array();
			$difcompmesano  = !empty($_REQUEST['difcompmesano']) ? $_REQUEST['difcompmesano'] : 3; /* 1 = sim e 2 = não and 3 = da mesma compatencia não passou pelo xml diferente da competencia */

			$numnotasenttxt = 0;
			$numnotasaitxt  = 0;
			
			$funcao   = new FuncoesDAO();	
			$daor     = new ProdFrigTxtDAO();	
			$daocf    = new CfopDAO();	

			$pathFile      = '../arquivos/'.$_SESSION['cnpj'].'/config.json';
			$configJson    = file_get_contents($pathFile);
			$installConfig = json_decode($configJson);
			//aqui abrindo a pasta para passa o arquivo
			$pasta = opendir($destino);
				
			while ($arquivo = readdir($pasta)){

				if ($arquivo != "." && $arquivo != ".."){

						$xml 	= simplexml_load_file($destino.$arquivo);	
						$xmlstr = file_get_contents($destino.$arquivo);	
						
						$doc = new DOMDocument();
						$doc->loadXML($xmlstr);
						$xmlstr = $doc->saveXML();
						
						$xml    = $funcao->verificatagxml($xml);

						if($xml->NFe->infNFe->ide->tpNF == 0){
							//NOTA DE ENTRADA
							
							$daonotasenttxt = new NotasEntTxtDAO();
							$vetnotasenttxt = $daonotasenttxt->VerificaNotas($xml->NFe->infNFe->ide->nNF,$_SESSION['cnpj']);
							$numnotasenttxt = count($vetnotasenttxt);
							
							if($numnotasenttxt == 0){
							
								$dEmi         = explode('T',$xml->NFe->infNFe->ide->dhEmi);
								$dhSaiEnt     = explode('T',$xml->NFe->infNFe->ide->dhSaiEnt);
								$cnpj_cpf     = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
								

								if(!empty($installConfig->tppt)){
									$abatept = $installConfig->tppt;
								}else{
									$abatept = 'P';
								}

								if($difcompmesano == 1){
									if(date('m/Y',strtotime($dEmi[0])) != $_SESSION['apura']['mesano']){
										$dEmi = date('d',strtotime($dEmi[0])).'/'.$_SESSION['apura']['mesano'];	
										$dEmi = explode(' ',implode("-", array_reverse(explode("/",$dEmi))).' '.date('h:m:s'));
									}
								}

								$notasenttxt = new NotasEntTxt();
						
								$notasenttxt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
								$notasenttxt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
								$notasenttxt->setCnpjCpf($cnpj_cpf);
								$notasenttxt->setTipoV_R_A('');
								$notasenttxt->setValorTotalNota(trim($xml->NFe->infNFe->total->ICMSTot->vNF));
								$notasenttxt->setGta('');
								$notasenttxt->setNumeroNotaProdutorIni('');
								$notasenttxt->setNumeroNotaProdutorFin('');
								$notasenttxt->setCondenas('');
								$notasenttxt->setAbate($abatept);
								$notasenttxt->setInscProdutor(trim($xml->NFe->infNFe->dest->IE));
								$notasenttxt->setCnpjEmp($_SESSION['cnpj']);
								$notasenttxt->setChave($xml->protNFe->infProt->chNFe);
								$notasenttxt->setXml($xmlstr);
								
								$daonotasenttxt->inserir($notasenttxt);
								
								$i = 0; 
								foreach($xml->NFe->infNFe->det as $prod){
									
								
									$vetr = $daor->ListaRelacionado(trim($prod->prod->cProd),$_SESSION['cnpj']);
									$numr = count($vetr);
									
									if($numr == 0){
										
										array_push($err_produto,array(
											'codigo'=>''.trim($prod->prod->cProd).'',
											'cnota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
											'msg'=>' Código do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') :: Produto não relacionado com o produto do agregar ::',
											"dados"=> array(
												'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'entsai'=>'Entrada',
												'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
												'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
												'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
											),
										));
									}
									
									$vetcf = $daocf->VerificaRelacionamento(trim($prod->prod->CFOP),$_SESSION['cnpj']);
									$numcf = count($vetcf);
									
									if($numcf > 0){
										$vercfop = $vetcf[0];
										
										$codcfop   = $vercfop->getCodigo();
										$nomcfop   = $vercfop->getNome();
										$geraagreg = $vercfop->getGeraAgregar();
										$vinculado = $vercfop->getVinculado();
										$idvinculo = $vercfop->getIdVinculado();
										
										if(empty($geraagreg)){
											$err_cfop[] = array(
												'idvinc'=>''.$idvinculo.'',
												'codigo'=>''.trim($prod->prod->CFOP).'',
												'nota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
												'arquivo'=>''.$arquivo.'',
												'entsai'=>'Entrada',
												'msg'=>' CFOP ('.trim($prod->prod->CFOP).') sem indicação se irá "Considerar" ou "Desconsiderar" na apuração de crédito.',
											);
										}																				
										
									}else{
										//não existe essa cfop no sistema
										$err_cfop[] = array(
											'codigo'=>''.trim($prod->prod->CFOP).'',
											'msg'=>' CFOP ('.trim($prod->prod->CFOP).') não Existe no sistema ou não esta gravado!  '
										);									
									}
									
									//$prod->infAdProd
									
									if(empty($prod->infAdProd)){
										array_push($err_nota,array(
											'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
											'cProd'=>''.trim($prod->prod->cProd).'',
											'idseq'=>($i+1),
											'entsai'=>'Entrada',
											'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') esta faltando numero de cabeças a ser preenchido!',
										));
										$ncabeca = 0;
										
									}else{
										
										$posicaopc    = explode('PC:',$prod->infAdProd);
										$posicaoqtde  = explode('Qtde:',$prod->infAdProd);
										$posicaoqtde2 = explode('CBC',$prod->infAdProd);
										
										$ncabecapc    = !empty($posicaopc[1])  ? preg_replace("/[^0-9]/", "", $posicaopc[1])   : "";
										$ncabecaqtd   = !empty($posicaoqtde[1])? preg_replace("/[^0-9]/", "", $posicaoqtde[1]) : "";	
										$posicaoqtde2 = !empty($posicaoqtde[0])? preg_replace("/[^0-9]/", "", $posicaoqtde[0]) : "";

										if($ncabecapc != ""){
											$ncabeca = $ncabecapc;
											if(preg_match("/^(\d+)?\.\d+$/", $ncabeca)) {
												$ncabeca = preg_replace("/[^0-9]/", "", substr($ncabeca, 0, strpos($ncabeca, ".")));
											}
										}else if($ncabecaqtd != ""){
											$ncabeca = $ncabecaqtd;
											if(preg_match("/^(\d+)?\.\d+$/", $ncabeca)) {
												$ncabeca = preg_replace("/[^0-9]/", "", substr($ncabeca, 0, strpos($ncabeca, ".")));
											}
										}else if($posicaoqtde2 != ""){
											$ncabeca = $posicaoqtde2;
											if(preg_match("/^(\d+)?\.\d+$/", $ncabeca)) {
												$ncabeca = preg_replace("/[^0-9]/", "", substr($ncabeca, 0, strpos($ncabeca, ".")));
											}
										}else{
											$ncabeca = 0;
											array_push($err_nota,array(
												'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'cProd'=>''.trim($prod->prod->cProd).'',
												'idseq'=>($i+1),
												'entsai'=>'Entrada',
												'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') esta faltando numero de cabeças a ser preenchido!',
											));
										}
																														
									}
									
									
									
									$peso_vivo_cabeca = 0;
									$peso_carcasa	  = 0;
									if(!empty($installConfig->notas->vivorend)){	
										if($installConfig->notas->vivorend == "V"){
											$peso_vivo_cabeca = $prod->prod->qCom;		
										}else if($installConfig->notas->vivorend == "R"){
											$peso_carcasa = $prod->prod->qCom;
										}else{
											array_push($err_v_r,array(
										 		'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'cProd'=>''.trim($prod->prod->cProd).'',
												'qCom'=>''.number_format(doubleval($prod->prod->qCom),2,',','.').'',
												'entsai'=>'Entrada',
												'idseq'=>($i+1),
												'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') <br/> Esta faltando Informar se é Vivo ou Rendimento!',
											));
										}
									}else{
										array_push($err_v_r,array(
										 		'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'cProd'=>''.trim($prod->prod->cProd).'',
												'qCom'=>''.number_format(doubleval($prod->prod->qCom),2,',','.').'',
												'entsai'=>'Entrada',
												'idseq'=>($i+1),
												'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') <br/> Esta faltando Informar se é Vivo ou Rendimento!',
											));
									}

									//$dataabate = date('Y-m-d',strtotime($dEmi[0]));
									if($_SESSION['apura']['mesano'] == date('m/Y',strtotime($dEmi[0]))){
										if(!empty($installConfig->abate)){
											if($installConfig->abate == '1'){
												$dataabate = date('Y-m-d',strtotime($dEmi[0]));	
											}else if($installConfig->abate == '2'){
												$dataabate = date('Y-m-d',strtotime($dhSaiEnt[0]));	
											}else{
												$dataabate = "";
											}
										}else{
											$dataabate = date('Y-m-d',strtotime($dEmi[0]));
										}
									
									}else{
										$dataabate = '';
										if($difcompmesano == 1){
											if(date('m/Y',strtotime($dEmi[0])) != $_SESSION['apura']['mesano']){
												$dataabate = date('d',strtotime($dEmi[0])).'/'.$_SESSION['apura']['mesano'];	
												$dataabate = explode(' ',implode("-", array_reverse(explode("/",$dEmi))).' '.date('h:m:s'));
											}
										}
									}
									
									$notasen1 = new NotasEn1Txt();
	
									$notasen1->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
									$notasen1->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
									$notasen1->setCnpjCpf($cnpj_cpf);
									$notasen1->setCodigoProduto(trim($prod->prod->cProd));
									$notasen1->setQtdCabeca($ncabeca);
									$notasen1->setPesoVivoCabeca($peso_vivo_cabeca);
									$notasen1->setPesoCarcasa($peso_carcasa);
									$notasen1->setPrecoQuilo(trim($prod->prod->vUnCom));
									$notasen1->setNumeroItemNota(($i+1));
									$notasen1->setInsEstadual(trim($xml->NFe->infNFe->dest->IE));
									$notasen1->setDataAbate($dataabate);
									$notasen1->setTipo_R_V($installConfig->notas->vivorend);
									$notasen1->setCfop(trim($prod->prod->CFOP));
									$notasen1->setAliquotaIcms(0);
									$notasen1->setCnpjEmp($_SESSION['cnpj']);
									$notasen1->setProdQtd(trim($prod->prod->qCom));
									$daonotasen1 = new NotasEn1TxtDAO();
									$daonotasen1->inserir($notasen1);
									
									$i++;
								}
								

								$daogta = new GtaTxtDAO();
								$vetgta = $daogta->GtaEmpresas($xml->NFe->infNFe->ide->nNF,$_SESSION['cnpj']);
								$numgta = count($vetgta);
									
								if($numgta == 0){
									
									array_push($alert_gta,array(
											'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
											'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') Não existem numeros de GTA informados!',
									));
									
								}


								array_push($dados,array(
										
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'chave'=>''.$xml->protNFe->infProt->chNFe.'',
									'entsai'=>'ENTRADA',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'caminho'=>''.$arquivo.'',
									'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',
									'msg'=>''									
								));	
								
							}else{

								// se ja existe vamos validar a competencia que ainda não foi populada	

								$dEmi         = explode('T',$xml->NFe->infNFe->ide->dhEmi);
								$cnpj_cpf     = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
									
								if(date('m/Y',strtotime($dEmi[0])) != $_SESSION['apura']['mesano']){
									$dEmi = date('d',strtotime($dEmi[0])).'/'.$_SESSION['apura']['mesano'];	
									$dEmi = explode(' ',implode("-", array_reverse(explode("/",$dEmi))).' '.date('h:m:s'));
								}
								
								$i = 0; 
								foreach($xml->NFe->infNFe->det as $prod){
									
									
									$vetr = $daor->ListaRelacionado(trim($prod->prod->cProd),$_SESSION['cnpj']);
									$numr = count($vetr);
									
									if($numr == 0){
										
										array_push($err_produto,array(
											'codigo'=>''.trim($prod->prod->cProd).'',
											'cnota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
											'msg'=>' Código do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') :: Produto não relacionado com o produto do agregar ::',
											"dados"=> array(
												'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'entsai'=>'Entrada',
												'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
												'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
												'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
											),
										));
									}
									
									$vetcf = $daocf->VerificaRelacionamento(trim($prod->prod->CFOP),$_SESSION['cnpj']);
									$numcf = count($vetcf);
									
									if($numcf > 0){
										$vercfop = $vetcf[0];
										
										$codcfop   = $vercfop->getCodigo();
										$nomcfop   = $vercfop->getNome();
										$geraagreg = $vercfop->getGeraAgregar();
										$vinculado = $vercfop->getVinculado();
										$idvinculo = $vercfop->getIdVinculado();
										
										if(empty($geraagreg)){
											$err_cfop[] = array(
												'idvinc'=>''.$idvinculo.'',
												'codigo'=>''.trim($prod->prod->CFOP).'',
												'nota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
												'arquivo'=>''.$arquivo.'',
												'entsai'=>'Entrada',
												'msg'=>' CFOP ('.trim($prod->prod->CFOP).') sem indicação se irá "Considerar" ou "Desconsiderar" na apuração de crédito.',
											);
										}																				
										
									}else{
										//não existe essa cfop no sistema
										$err_cfop[] = array(
											'codigo'=>''.trim($prod->prod->CFOP).'',
											'msg'=>' CFOP ('.trim($prod->prod->CFOP).') não Existe no sistema ou não esta gravado!  '
										);									
									}
									
									//$prod->infAdProd
									$daonotasen1 = new NotasEn1TxtDAO();
									$vetnotasen1 = $daonotasen1->VerificaCabecasPreenchidas($xml->NFe->infNFe->ide->nNF,trim($prod->prod->cProd),$_SESSION['cnpj'],($i+1));
									$numnotasen1 = count($vetnotasen1);
									
									if($numnotasen1 > 0){
										
										$notasen1 = $vetnotasen1[0];
										
										$qtd_cabeca   = $notasen1->getQtdCabeca();
										$tipo_r_v     = $notasen1->getTipo_R_V();
										$peso_carcasa = $notasen1->getPesoCarcasa();

										if(empty($qtd_cabeca)){
											
												array_push($err_nota,array(
													'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
													'cProd'=>''.trim($prod->prod->cProd).'',
													'idseq'=>($i+1),
													'entsai'=>'Entrada',
													'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') esta faltando numero de cabeças a ser preenchido!',
												));
											
										}/*else if($peso_carcasa <= 0){
											array_push($err_nota,array(
													'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
													'cProd'=>''.trim($prod->prod->cProd).'',
													'msg'=>' Numero da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') esta faltando numero de cabeças a ser preenchido!',
											));	
										}*/
										
										
										if(empty($tipo_r_v)){
											array_push($err_v_r,array(
												'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'cProd'=>''.trim($prod->prod->cProd).'',
												'qCom'=>''.number_format(doubleval($prod->prod->qCom),2,',','.').'',
												'entsai'=>'Entrada',
												'idseq'=>($i+1),
												'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') <br/> Esta faltando Informar se é Vivo ou Rendimento!',
											));
											
										}
										
									}
																																		
									
									$i++;
								}
								
								$daogta = new GtaTxtDAO();
								$vetgta = $daogta->GtaEmpresas($xml->NFe->infNFe->ide->nNF,$_SESSION['cnpj']);
								$numgta = count($vetgta);
									
								if($numgta == 0){
									
									array_push($alert_gta,array(
											'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
											'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') Não existem numeros de GTA informados!',
									));
									
								}


								array_push($dados,array(
										
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'chave'=>''.$xml->protNFe->infProt->chNFe.'',
									'entsai'=>'ENTRADA',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'caminho'=>''.$arquivo.'',
									'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',
									'msg'=>''									
								));		

								
							
							}
						}else{
							
							$dEmi       = explode('T',$xml->NFe->infNFe->ide->dhEmi);
							$cnpj_cpf2   = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
							
							$daonotasaitxt = new NotasSaiTxtDAO();
							$vetnotasaitxt = $daonotasaitxt->VerificaNota(trim($xml->NFe->infNFe->ide->nNF),$_SESSION['cnpj']); 
							$numnotasaitxt = count($vetnotasaitxt);

							if(trim($xml->NFe->infNFe->det->prod->CFOP) == '1101'){
								continue;
							}
							
							if($difcompmesano == 1){
								if(date('m/Y',strtotime($dEmi[0])) != $_SESSION['apura']['mesano']){
									$dEmi = date('d',strtotime($dEmi[0])).'/'.$_SESSION['apura']['mesano'];	
									$dEmi = explode(' ',implode("-", array_reverse(explode("/",$dEmi))).' '.date('h:m:s'));
								}
							}

							if($numnotasaitxt == 0){

								if(trim($xml->NFe->infNFe->det->prod->CFOP) > 5000){
									$entsai_sai_mestre = "S";
								}else if(trim($xml->NFe->infNFe->det->prod->CFOP) < 5000){
									$entsai_sai_mestre = "E";
								}

								$notasaitxt = new NotasSaiTxt();
						
								$notasaitxt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
								$notasaitxt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
								$notasaitxt->setCnpjCpf($cnpj_cpf2);
								$notasaitxt->setValorTotalNota(trim($xml->NFe->infNFe->total->ICMSTot->vNF));
								$notasaitxt->setValorIcms(trim($xml->NFe->infNFe->total->ICMSTot->vICMS));
								$notasaitxt->setValorIcmsSubs(trim($xml->NFe->infNFe->total->ICMSTot->vST));
								$notasaitxt->setEntSai($entsai_sai_mestre);
								$notasaitxt->setInscEstadual(trim($xml->NFe->infNFe->dest->IE));
								$notasaitxt->setCfop(trim($xml->NFe->infNFe->det->prod->CFOP));
								$notasaitxt->setCnpjEmp($_SESSION['cnpj']);
								$notasaitxt->setChave($xml->protNFe->infProt->chNFe);
								$notasaitxt->setXml($xmlstr);
								
								$daonotasaitxt->inserir($notasaitxt);
								
								$i = 0; 
								foreach($xml->NFe->infNFe->det as $prod){
									
									if(trim($prod->prod->CFOP) == '1101'){
										continue;
									}
									
									$vetrl = $daor->ListaRelacionado(trim($prod->prod->cProd),$_SESSION['cnpj']);
									$numrl = count($vetrl);
									
									if($numrl == 0){
										
										array_push($err_produto,array(
											'codigo'=>''.trim($prod->prod->cProd).'',
											'cnota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
											'msg'=>' Código do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') :: Produto não relacionado com o produto do agregar ::',
											"dados"=> array(
												'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'entsai'=>'Saida',
												'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
												'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
												'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
											),
										));
									}
									
									$vetcfs = $daocf->VerificaRelacionamento(trim($prod->prod->CFOP),$_SESSION['cnpj']);
									$numcfs = count($vetcfs);
									
									if($numcfs > 0){
										$vercfops = $vetcfs[0];
										
										$codcfops   = $vercfops->getCodigo();
										$nomcfops   = $vercfops->getNome();
										$geraagregs = $vercfops->getGeraAgregar();
										$vinculados = $vercfops->getVinculado();
										$idvinculos = $vercfops->getIdVinculado();
										
										if(empty($geraagregs)){
											$err_cfop[] =array(
												'idvinc'=>''.$idvinculos.'',
												'codigo'=>''.trim($prod->prod->CFOP).'',
												'nota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
												'arquivo'=>''.$arquivo.'',
												'entsai'=>'Saida',
												'msg'=>' CFOP ('.trim($prod->prod->CFOP).') sem indicação se irá "Considerar" ou "Desconsiderar" na apuração de crédito.',
											);
										}																														
										
										
									}else{
										//não existe essa cfop no sistema
										$err_cfop[] =array(
											'codigo'=>''.trim($prod->prod->CFOP).'',
											'msg'=>' CFOP ('.trim($prod->prod->CFOP).') não Existe no sistema ou não esta gravado!  '
										);									
									}
									
									if(trim($xml->NFe->infNFe->det->prod->CFOP) > 5000){
										$entsai_sai = "S";
									}else if(trim($xml->NFe->infNFe->det->prod->CFOP) < 5000){
										$entsai_sai = "E";
									}
									
									$notasa1txt = new NotasSa1Txt();
						
									$notasa1txt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
									$notasa1txt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
									$notasa1txt->setCnpjCpf($cnpj_cpf2);
									$notasa1txt->setCodigoProduto(trim($prod->prod->cProd));
									$notasa1txt->setQtdPecas(trim(0));
									$notasa1txt->setPeso(trim($prod->prod->qCom));
									$notasa1txt->setPrecoUnitario(trim($prod->prod->vUnCom));
									$notasa1txt->setEntSai($entsai_sai);
									$notasa1txt->setNumeroItemNota(($i+1));		
									$notasa1txt->setInscEstadual(trim($xml->NFe->infNFe->dest->IE));
									$notasa1txt->setCfop(trim($xml->NFe->infNFe->det->prod->CFOP));
									$notasa1txt->setAliquotaIcms(0);
									$notasa1txt->setCnpjEmp($_SESSION['cnpj']);
									$notasa1txt->setProdQtd(trim($prod->prod->qCom));

									$daonotasa1txt = new NotasSa1TxtDAO();
									
									$daonotasa1txt->inserir($notasa1txt);
									$i++;
								}
							
								array_push($dados,array(
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'chave'=>''.$xml->protNFe->infProt->chNFe.'',
									'entsai'=>'SAIDA',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'caminho'=>''.$arquivo.'',
									'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',	
									'msg'=>''								
								));	
							
							}else{
								
								$dEmi       = explode('T',$xml->NFe->infNFe->ide->dhEmi);
								$cnpj_cpf2   = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
								
								if(date('m/Y',strtotime($dEmi[0])) != $_SESSION['apura']['mesano']){
									$dEmi = date('d',strtotime($dEmi[0])).'/'.$_SESSION['apura']['mesano'];	
									$dEmi = explode(' ',implode("-", array_reverse(explode("/",$dEmi))).' '.date('h:m:s'));
								}

								$i = 0; 
								foreach($xml->NFe->infNFe->det as $prod){
									
									
									$vetrl = $daor->ListaRelacionado(trim($prod->prod->cProd),$_SESSION['cnpj']);
									$numrl = count($vetrl);
									
									if($numrl == 0){
										
										array_push($err_produto,array(
											'codigo'=>''.trim($prod->prod->cProd).'',
											'cnota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
											'msg'=>' Código do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') :: Produto não relacionado com o produto do agregar ::',											
											"dados"=> array(
												'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'entsai'=>'Saida',
												'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
												'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
												'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
											),
										));
									}
									
									$vetcfs = $daocf->VerificaRelacionamento(trim($prod->prod->CFOP),$_SESSION['cnpj']);
									$numcfs = count($vetcfs);
									
									if($numcfs > 0){
										$vercfops = $vetcfs[0];
										
										$codcfops   = $vercfops->getCodigo();
										$nomcfops   = $vercfops->getNome();
										$geraagregs = $vercfops->getGeraAgregar();
										$vinculados = $vercfops->getVinculado();
										$idvinculos = $vercfops->getIdVinculado();
										
										if(empty($geraagregs)){
											$err_cfop[] =array(
												'idvinc'=>''.$idvinculos.'',
												'codigo'=>''.trim($prod->prod->CFOP).'',
												'nota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
												'arquivo'=>''.$arquivo.'',
												'entsai'=>'Saida',
												'msg'=>' CFOP ('.trim($prod->prod->CFOP).') sem indicação se irá "Considerar" ou "Desconsiderar" na apuração de crédito.',
											);
										}																														
										
										
									}else{
										//não existe essa cfop no sistema
										$err_cfop[] =array(
											'codigo'=>''.trim($prod->prod->CFOP).'',
											'msg'=>' CFOP ('.trim($prod->prod->CFOP).') não Existe no sistema ou não esta gravado!  '
										);									
									}
																											
									$i++;
								}
							
								array_push($dados,array(
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'chave'=>''.$xml->protNFe->infProt->chNFe.'',
									'entsai'=>'SAIDA',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'caminho'=>''.$arquivo.'',
									'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',	
									'msg'=>''								
								));
								
							}
							
						}
										
				}
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
				
				
			$erro_cfop = $funcao->array_sort($err_cfop,'codigo',SORT_ASC);
			$cfop_erro = array();	
			foreach($erro_cfop as $key=>$value){				
				array_push($cfop_erro,$value);					
			}
				
			$erro_produto = $funcao->array_sort($err_produto,'codigo',SORT_ASC);	
			$produto_erro = array();	
			foreach($erro_produto as $keys=>$values){
				array_push($produto_erro,$values);
				
			}
				
				
			$data = array(
					'erro'=>array(
						'cfop'=>$cfop_erro,
						'produto'=>$produto_erro,
						'nota'=>$err_nota,
						'vivorendmento'=>$err_v_r,
					),
					'info'=>array(
						'funcionario'=>$alert_nfunc,
						'folha'=>$alert_vlfolha,
						'icmsnormal'=>$alert_icmsnorm,
						'icmsst'=>$alert_icmsst,
						'gta'=>$alert_gta,
						'num_entrada'=>$numnotasenttxt,
						'num_saida'=>$numnotasaitxt
					),
					'dados_grid'=>$dados);												
			echo json_encode($data);
				
				
		break;
		case 'valida_processo':
			
			$destino     	= "../arquivos/{$_SESSION['cnpj']}/importado/";	
			$erro        	= array();
			$dados       	= array();
			$err_cfop    	= array();
			$err_produto 	= array();	
			$err_nota    	= array();
			$err_v_r	 	= array();
			$err_dataabate 	= array();
			$err_produtoter = array();

			$alert_nfunc    = array();
			$alert_vlfolha  = array();	
			$alert_icmsnorm = array();	
			$alert_icmsst   = array();	
			$alert_gta      = array();	
			$alert_dataabate= array();
			$alert_valoricms= array();
			$alerta_sessao  = !empty($_SESSION['alerta_sessao'][$_SESSION['apura']['mesano']]) ?  $_SESSION['alerta_sessao'][$_SESSION['apura']['mesano']] : array();
			
			$funcao        = new FuncoesDAO();	
			$daor          = new ProdFrigTxtDAO();	
			$daoprodfrig   = new ProdutosTxt2DAO(); 
			$daocf         = new CfopDAO();
			
			$pathFile      = '../arquivos/'.$_SESSION['cnpj'].'/config.json';
			$configJson    = file_get_contents($pathFile);
			$installConfig = json_decode($configJson);

			$resuprod    = $daoprodfrig->ListaProdutosProprio($_SESSION['cnpj']);

			//notas de entrada
			$daonotasenttxt = new NotasEntTxtDAO();
			$vetnotasenttxt = $daonotasenttxt->ListaNotasEntComp($_SESSION['apura']['mesano'],$_SESSION['cnpj']);
			$numnotasenttxt = count($vetnotasenttxt);
			
			if($numnotasenttxt > 0){		
				for($i = 0; $i < $numnotasenttxt;$i++){
					
					$notasent   = $vetnotasenttxt[$i];
					$id  	    = $notasent->getCodigo();
					$xmlstr	    = $notasent->getXml();
					$abateap    = $notasent->getAbate();

					$dom = new domDocument();
					$dom->loadXML($xmlstr);
					$xml = simplexml_import_dom($dom);
					$xml = $funcao->verificatagxml($xml);

					$dEmi         = explode('T',$xml->NFe->infNFe->ide->dhEmi);
					$cnpj_cpf     = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
					if(empty(trim($cnpj_cpf)))	{
						$cnpj_cpf   = strlen(trim($xml->NFe->infNFe->emit->CNPJ)) == 14 ? trim($xml->NFe->infNFe->emit->CNPJ) : trim($xml->NFe->infNFe->emit->CPF);
					}			
					
					if(date('m/Y',strtotime($dEmi[0])) != $_SESSION['apura']['mesano']){
						$dEmi = date('d',strtotime($dEmi[0])).'/'.$_SESSION['apura']['mesano'];	
						$dEmi = explode(' ',implode("-", array_reverse(explode("/",$dEmi))).' '.date('h:m:s'));
					}
					
					
					$ii = 0;
					foreach($xml->NFe->infNFe->det as $prod){
						
						
						$vetr = $daor->ListaRelacionado(trim($prod->prod->cProd),$_SESSION['cnpj']);
						$numr = count($vetr);
						
						if($numr == 0){
							
							array_push($err_produto,array(
								'codigo'=>''.trim($prod->prod->cProd).'',
								'cnota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
								'msg'=>' Código do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') :: Produto não relacionado com o produto do agregar ::',
								"dados"=> array(
									'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'entsai'=>'Entrada',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
								),
							));
						}
						
						$vetcf = $daocf->VerificaRelacionamento(trim($prod->prod->CFOP),$_SESSION['cnpj']);
						$numcf = count($vetcf);
						
						if($numcf > 0){
							$vercfop = $vetcf[0];
							
							$codcfop   = $vercfop->getCodigo();
							$nomcfop   = $vercfop->getNome();
							$geraagreg = $vercfop->getGeraAgregar();
							$vinculado = $vercfop->getVinculado();
							$idvinculo = $vercfop->getIdVinculado();
							
							if(empty($geraagreg)){
								$err_cfop[] = array(
									'idvinc'=>''.$idvinculo.'',
									'codigo'=>''.trim($prod->prod->CFOP).'',
									'nota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
									'arquivo'=>'',
									'entsai'=>'Entrada',
									'msg'=>' CFOP ('.trim($prod->prod->CFOP).') sem indicação se irá "Considerar" ou "Desconsiderar" na apuração de crédito.',
								);
							}																				
							
						}else{
							//não existe essa cfop no sistema
							$err_cfop[] = array(
								'codigo'=>''.trim($prod->prod->CFOP).'',
								'msg'=>' CFOP ('.trim($prod->prod->CFOP).') não Existe no sistema ou não esta gravado!  '
							);									
						}
						
						//$prod->infAdProd
						$daonotasen1 = new NotasEn1TxtDAO();
						$vetnotasen1 = $daonotasen1->VerificaCabecasPreenchidas($xml->NFe->infNFe->ide->nNF,trim($prod->prod->cProd),$_SESSION['cnpj'],($ii+1));
						$numnotasen1 = count($vetnotasen1);
						
						if($numnotasen1 > 0){
							
							$notasen1 = $vetnotasen1[0];
							
							$qtd_cabeca   = $notasen1->getQtdCabeca();
							$tipo_r_v     = $notasen1->getTipo_R_V();
							$peso_carcasa = $notasen1->getPesoCarcasa();

							if(empty($qtd_cabeca)){
								
									array_push($err_nota,array(
										'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'cProd'=>''.trim($prod->prod->cProd).'',
										'idseq'=>($ii+1),
										'entsai'=>'Entrada',
										'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') esta faltando numero de cabeças a ser preenchido!',										
									));
								
							}/*else if($peso_carcasa <= 0){
								array_push($err_nota,array(
										'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'cProd'=>''.trim($prod->prod->cProd).'',
										'msg'=>' Numero da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') esta faltando numero de cabeças a ser preenchido!',
									));	
							}*/
							
							if($qtd_cabeca > 300){
								array_push($err_nota,array(
									'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'cProd'=>''.trim($prod->prod->cProd).'',
									'idseq'=>($ii+1),
									'entsai'=>'Entrada',
									'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') quantidade de cabeças incorreta !',										
								));
							}
							
							if(empty($tipo_r_v)){
								array_push($err_v_r,array(
									'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'cProd'=>''.trim($prod->prod->cProd).'',
									'qCom'=>''.number_format(doubleval($prod->prod->qCom),2,',','.').'',
									'entsai'=>'Entrada',
									'idseq'=>($ii+1),
									'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') <br/> Esta faltando Informar se é Vivo ou Rendimento!',									
								));
								
							}
							
						}

						/*valida data do abate*/

						if(!empty($installConfig->abate)){
							if($installConfig->abate == '1'){
								//emissao
								//verificar se a data emissao for diferente da competencia informada
								$vetdatabte = $daonotasen1->VerificaDataAbate($_SESSION['apura']['mesano'],$_SESSION['cnpj'],$xml->NFe->infNFe->ide->nNF,trim($prod->prod->cProd),($ii+1));
								$numdatabte = count($vetdatabte);
								
								if($numdatabte > 0){
									$notasen1abt  = $vetdatabte[0];

									$data_emissao = $notasen1abt->getDataEmissao();
									$data_abate   = $notasen1abt->getDataAbate();
									if(!empty($data_abate) and $data_abate != '0000-00-00'){
										if(date('m/Y',strtotime($data_abate)) != $_SESSION['apura']['mesano']){
											//alerta de diferente da data da competencia
											array_push($alert_dataabate,array(
												'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'cProd'=>''.trim($prod->prod->cProd).'',
												'dProd'=>'('.trim($prod->prod->cProd).') - '.$prod->prod->xProd.'',											
												'data_emissao'=>date('m/Y',strtotime($data_emissao)),
												'data_abate'=>date('d/m/Y',strtotime($data_abate)),
												'numero_nota'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'entsai'=>'Entrada',
												'idseq'=>($ii+1),
												'msg'=>' Data do abate diferente da data da competência => Data Competência: '.date('m/Y',strtotime($data_emissao)).' Data Abate informada: '.date('d/m/Y',strtotime($data_abate)).' Numero Nota: '.$xml->NFe->infNFe->ide->nNF.' Produto: ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') ',									
											));
										}
									}else{
										array_push($alert_dataabate,array(
											'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
											'cProd'=>''.trim($prod->prod->cProd).'',
											'dProd'=>'('.trim($prod->prod->cProd).') - '.$prod->prod->xProd.'',											
											'data_emissao'=>date('m/Y',strtotime($data_emissao)),
											'data_abate'=>date('d/m/Y',strtotime($data_abate)),
											'numero_nota'=>''.$xml->NFe->infNFe->ide->nNF.'',
											'entsai'=>'Entrada',
											'idseq'=>($ii+1),
											'msg'=>'Data do abate não informado numero nota: '.$xml->NFe->infNFe->ide->nNF.' Produto: ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') ',									
										));
									}
								}else{
									// não ta informado

									array_push($alert_dataabate,array(
										'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'cProd'=>''.trim($prod->prod->cProd).'',
										'dProd'=>'('.trim($prod->prod->cProd).') - '.$prod->prod->xProd.'',											
										'data_emissao'=>date('m/Y',strtotime($data_emissao)),
										'data_abate'=>date('d/m/Y',strtotime($data_abate)),
										'numero_nota'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'entsai'=>'Entrada',
										'idseq'=>($ii+1),
										'msg'=>'Data do abate não informado numero nota: '.$xml->NFe->infNFe->ide->nNF.' Produto: ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') ',									
									));

								}
								
							}else if($installConfig->abate == '2'){
								//data entrada
								// verificar se a data da entrada é difirente da data da emissão
								
								$vetdatabte = $daonotasen1->VerificaDataAbate($_SESSION['apura']['mesano'],$_SESSION['cnpj'],$xml->NFe->infNFe->ide->nNF,trim($prod->prod->cProd),($ii+1));
								$numdatabte = count($vetdatabte);
								
								if($numdatabte > 0){
									$notasen1abt  = $vetdatabte[0];

									$data_emissao = $notasen1abt->getDataEmissao();
									$data_abate   = $notasen1abt->getDataAbate();
									if(!empty($data_abate) and $data_abate != '0000-00-00'){
										if(date('m/Y',strtotime($data_abate)) != date('m/Y',strtotime($data_emissao))){
											//alerta de diferente da data da competencia
											array_push($alert_dataabate,array(
												'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'cProd'=>''.trim($prod->prod->cProd).'',	
												'dProd'=>'('.trim($prod->prod->cProd).') - '.$prod->prod->xProd.'',										
												'data_emissao'=>date('m/Y',strtotime($data_emissao)),
												'data_abate'=>date('d/m/Y',strtotime($data_abate)),
												'numero_nota'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'entsai'=>'Entrada',
												'idseq'=>($ii+1),
												'msg'=>' Data do abate diferente da data da competência => Data Competência: '.date('m/Y',strtotime($data_emissao)).' Data Abate informada: '.date('d/m/Y',strtotime($data_abate)).' Numero Nota: '.$xml->NFe->infNFe->ide->nNF.' Produto: ('.trim($prod->prod->cProd).' - '.$prod->prod->xProd.') ',									
											));
										}
									}else{
										array_push($alert_dataabate,array(
											'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
											'cProd'=>''.trim($prod->prod->cProd).'',
											'dProd'=>'('.trim($prod->prod->cProd).') - '.$prod->prod->xProd.'',											
											'data_emissao'=>date('m/Y',strtotime($data_emissao)),
											'data_abate'=>date('d/m/Y',strtotime($data_abate)),
											'numero_nota'=>''.$xml->NFe->infNFe->ide->nNF.'',
											'entsai'=>'Entrada',
											'idseq'=>($ii+1),
											'msg'=>'Data do abate não informado numero nota: '.$xml->NFe->infNFe->ide->nNF.' Produto: ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') ',									
										));
									}
								}else{
									// não ta informado

									array_push($alert_dataabate,array(
										'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'cProd'=>''.trim($prod->prod->cProd).'',
										'dProd'=>'('.trim($prod->prod->cProd).') - '.$prod->prod->xProd.'',											
										'data_emissao'=>date('m/Y',strtotime($data_emissao)),
										'data_abate'=>date('d/m/Y',strtotime($data_abate)),
										'numero_nota'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'entsai'=>'Entrada',
										'idseq'=>($ii+1),
										'msg'=>'Data do abate não informado numero nota: '.$xml->NFe->infNFe->ide->nNF.' Produto: ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') ',									
									));

								}	


							}else{
								//sem informação

								$vetdatabte = $daonotasen1->VerificaDataAbate($_SESSION['apura']['mesano'],$_SESSION['cnpj'],$xml->NFe->infNFe->ide->nNF,trim($prod->prod->cProd),($ii+1));
								$numdatabte = count($vetdatabte);
								
								if($numdatabte > 0){
									$notasen1abt  = $vetdatabte[0];

									$data_emissao = $notasen1abt->getDataEmissao();
									$data_abate   = $notasen1abt->getDataAbate();
									if(!empty($data_abate) and $data_abate != '0000-00-00'){		
										if(date('m/Y',strtotime($data_emissao)) != date('m/Y',strtotime($data_abate))){
											//alerta de diferente da data da competencia
											array_push($alert_dataabate,array(
												'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'cProd'=>''.trim($prod->prod->cProd).'',
												'dProd'=>'('.trim($prod->prod->cProd).') - '.$prod->prod->xProd.'',											
												'data_emissao'=>date('m/Y',strtotime($data_emissao)),
												'data_abate'=>date('d/m/Y',strtotime($data_abate)),
												'numero_nota'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'entsai'=>'Entrada',
												'idseq'=>($ii+1),
												'msg'=>' Data do abate diferente da data da competência => Data Competência: '.date('m/Y',strtotime($data_emissao)).' Data Abate informada: '.date('d/m/Y',strtotime($data_abate)).' Numero Nota: '.$xml->NFe->infNFe->ide->nNF.' Produto: ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') ',									
											));
										}
									}else{
										array_push($alert_dataabate,array(
											'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
											'cProd'=>''.trim($prod->prod->cProd).'',
											'dProd'=>'('.trim($prod->prod->cProd).') - '.$prod->prod->xProd.'',											
											'data_emissao'=>date('m/Y',strtotime($data_emissao)),
											'data_abate'=>date('d/m/Y',strtotime($data_abate)),
											'numero_nota'=>''.$xml->NFe->infNFe->ide->nNF.'',
											'entsai'=>'Entrada',
											'idseq'=>($ii+1),
											'msg'=>'Data do abate não informado numero nota: '.$xml->NFe->infNFe->ide->nNF.' Produto: ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') ',									
										));
									}
								}else{
									// não ta informado

									array_push($alert_dataabate,array(
										'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'cProd'=>''.trim($prod->prod->cProd).'',
										'dProd'=>'('.trim($prod->prod->cProd).') - '.$prod->prod->xProd.'',											
										'data_emissao'=>date('m/Y',strtotime($data_emissao)),
										'data_abate'=>date('d/m/Y',strtotime($data_abate)),
										'numero_nota'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'entsai'=>'Entrada',
										'idseq'=>($ii+1),
										'msg'=>'Data do abate não informado numero nota: '.$xml->NFe->infNFe->ide->nNF.' Produto: ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') ',									
									));

								}

							}
						}else{
							//padrao emissão
								$vetdatabte = $daonotasen1->VerificaDataAbate($_SESSION['apura']['mesano'],$_SESSION['cnpj'],$xml->NFe->infNFe->ide->nNF,trim($prod->prod->cProd),($ii+1));
								$numdatabte = count($vetdatabte);
								
								if($numdatabte > 0){
									$notasen1abt  = $vetdatabte[0];

									$data_emissao = $notasen1abt->getDataEmissao();
									$data_abate   = $notasen1abt->getDataAbate();

									if(date('m/Y',strtotime($data_emissao)) != date('m/Y',strtotime($data_abate))){
										//alerta de diferente da data da competencia
										array_push($alert_dataabate,array(
											'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
											'cProd'=>''.trim($prod->prod->cProd).'',											
											'dProd'=>'('.trim($prod->prod->cProd).') - '.$prod->prod->xProd.'',
											'data_emissao'=>date('m/Y',strtotime($data_emissao)),
											'data_abate'=>date('d/m/Y',strtotime($data_abate)),
											'numero_nota'=>''.$xml->NFe->infNFe->ide->nNF.'',
											'entsai'=>'Entrada',
											'idseq'=>($ii+1),
											'msg'=>' Data do abate diferente da data da competência => Data Competência: '.date('m/Y',strtotime($data_emissao)).' Data Abate informada: '.$data_abate.' Numero Nota: '.$xml->NFe->infNFe->ide->nNF.' Produto: ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') ',									
										));
									}

								}else{
									// não ta informado

									array_push($alert_dataabate,array(
										'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'cProd'=>''.trim($prod->prod->cProd).'',
										'dProd'=>'('.trim($prod->prod->cProd).') - '.$prod->prod->xProd.'',											
										'data_emissao'=>date('m/Y',strtotime($data_emissao)),
										'data_abate'=>date('d/m/Y',strtotime($data_abate)),
										'numero_nota'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'entsai'=>'Entrada',
										'idseq'=>($ii+1),
										'msg'=>'Data do abate não informado numero nota: '.$xml->NFe->infNFe->ide->nNF.' Produto: ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') ',									
									));

								}
						}


						$ii++;																																				
					}
					
					$daogta = new GtaTxtDAO();
					$vetgta = $daogta->GtaEmpresas($xml->NFe->infNFe->ide->nNF,$_SESSION['cnpj']);
					$numgta = count($vetgta);
						
					if($numgta == 0){
						
						array_push($alert_gta,array(
								'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
								'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') Não existem numeros de GTA informados!',
						));
						
					}


					array_push($dados,array(
							
						'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
						'chave'=>''.$xml->protNFe->infProt->chNFe.'',
						'entsai'=>'ENTRADA',
						'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
						'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
						'caminho'=>'',
						'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',
						'abate'=>''.$abateap.'',
						'msg'=>''									
					));
						
								
				}
			}
			
			//nota de saida
			$daonotasaitxt = new NotasSaiTxtDAO();
			$vetnotasaitxt = $daonotasaitxt->ListaNotasSaiEmp($_SESSION['apura']['mesano'],$_SESSION['cnpj']); 
			$numnotasaitxt = count($vetnotasaitxt);
			
			if($numnotasaitxt > 0){
				
				for($x = 0; $x < $numnotasaitxt; $x++){
						
						$notasai = $vetnotasaitxt[$x];
						$id 	 = $notasai->getCodigo();
						$xmlstr2 = $notasai->getXml();
					
						$dom2 = new domDocument();
						$dom2->loadXML($xmlstr2);
						$xml2 = simplexml_import_dom($dom2);
						$xml2 = $funcao->verificatagxml($xml2);

						$dEmi         = explode('T',$xml2->NFe->infNFe->ide->dhEmi);
						$cnpj_cpf2    = strlen(trim($xml2->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml2->NFe->infNFe->dest->CNPJ) : trim($xml2->NFe->infNFe->dest->CPF);
						
						if(empty(trim($cnpj_cpf2)))	{
							$cnpj_cpf2   = strlen(trim($xml2->NFe->infNFe->emit->CNPJ)) == 14 ? trim($xml2->NFe->infNFe->emit->CNPJ) : trim($xml2->NFe->infNFe->emit->CPF);
						}
					
						if(date('m/Y',strtotime($dEmi[0])) != $_SESSION['apura']['mesano']){
							$dEmi = date('d',strtotime($dEmi[0])).'/'.$_SESSION['apura']['mesano'];	
							$dEmi = explode(' ',implode("-", array_reverse(explode("/",$dEmi))).' '.date('h:m:s'));
						}
						
						//ListaaNotasSa1Detalhe
						$daonotassa1 = new  NotasSa1Txt2DAO();
						$vetnotassa1 = $daonotassa1->ListaNotasSa1Detalhe($_SESSION['cnpj'],$_SESSION['apura']['mesano']);
						$numnotassa1 = count($vetnotassa1); 
						
						for($y = 0; $y < $numnotassa1; $y++){

							$notassa1 = $vetnotassa1[$y];

							$numero_nota      = $notassa1['numero_nota'];
							$data_emissao     = $notassa1['data_emissao'];
							$cnpj_cpf         = $notassa1['cnpj_cpf'];
							$codigo_produto   = $notassa1['codigo_produto'];
							$qtd_pecas        = $notassa1['qtd_pecas'];
							$peso			  = $notassa1['peso'];
							$preco_unitario   = $notassa1['preco_unitario'];
							$ent_sai          = $notassa1['ent_sai'];
							$numero_item_nota = $notassa1['numero_item_nota'];
							$insc_estadual    = $notassa1['insc_estadual'];
							$cfop             = $notassa1['cfop'];
							$aliquota_icms    = $notassa1['aliquota_icms'];
							$cnpj_emp         = $notassa1['cnpj_emp'];
							$vICMS			  = $notassa1['vICMS'];

							

							$vetrl = $daor->ListaRelacionado(trim($codigo_produto),$_SESSION['cnpj']);
							$numrl = count($vetrl);
							if(!empty($codigo_produto)){
								if(!empty($resuprod)){
									$keysp = array_search(trim($codigo_produto),array_column($resuprod, 'cod_prod'));
									if(trim($keysp) != ""){
										$desc_prod = $resuprod[$keysp]['desc_prod'];
									}else{
										$desc_prod = "";
									}
								}
							}else{
								$desc_prod = "";
							}
							
							if($numrl == 0){
								
								if(!empty($codigo_produto)){
									array_push($err_produto,array(
										'codigo'=>''.trim($codigo_produto).'',
										'cnota'=>''.trim($numero_nota).'',
										'msg'=>' Código do produto ('.trim($codigo_produto).' '.$desc_prod.') :: Produto não relacionado com o produto do agregar ::',
										"dados"=> array(
											'nNF'=>''.$numero_nota.'',
											'entsai'=>'Saida',
											'cliente'=>''.$xml2->NFe->infNFe->dest->xNome.'',
											'valor'=>''.number_format(doubleval($xml2->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
											'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
										),
									));
								}else{
											//$err_produtoter
									$prod		= $xml2->NFe->infNFe->det[$y];			
									$prodx 		= json_encode($prod);
									$arrayprod  = json_decode($prodx,TRUE);

									array_push($err_produtoter,array(
										'codigo'=>''.trim($arrayprod['prod']['cProd']).'',
										'cnota'=>''.trim($numero_nota).'',
										'msg'=>' Relacione o produto ('.trim($arrayprod['prod']['cProd']).' '.$arrayprod['prod']['xProd'].') de terceiros, com o código do seu cadastro. ',
										"dados"=> array(
											'nNF'=>''.$numero_nota.'',
											'numero_item_nota'=>''.$numero_item_nota.'',
											'entsai'=>'Saida',
											'cnpjcli'=>''.$xml2->NFe->infNFe->emit->CNPJ.'',
											'iecli'=>''.$xml2->NFe->infNFe->emit->IE.'',
											'cliente'=>''.$xml2->NFe->infNFe->emit->xNome.'',
											'valor'=>''.number_format(doubleval($xml2->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
											'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
										),
									));

								}
							}


							if($numrl > 0){
								$prodfrig		= $vetrl[0];
								$cod_secretaria = trim($prodfrig->getCodSecretaria());
								$tipo           = $prodfrig->getTipo();
								if(empty($cod_secretaria)){
									array_push($err_produto,array(
									'codigo'=>''.trim($codigo_produto).'',
									'cnota'=>''.trim($numero_nota).'',
									'msg'=>' Código do produto ('.trim($codigo_produto).' '.$desc_prod.') :: Produto não relacionado com o produto do agregar ::',
									"dados"=> array(
										'nNF'=>''.$numero_nota.'',
										'entsai'=>'Saida',
										'cliente'=>''.$xml2->NFe->infNFe->dest->xNome.'',
										'valor'=>''.number_format(doubleval($xml2->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
										'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
									),
								));
									
								}
								
								if($tipo == 'E'){
									
										array_push($err_produto,array(
										'codigo'=>''.trim($codigo_produto).'',
										'cnota'=>''.trim($numero_nota).'',
										'msg'=>' Código do produto ('.trim($codigo_produto).' '.$desc_prod.') ::  Produto relacionado com código do AGREGAR de entradas, em documento de saída. Verifique o relacionamento!::',
										"dados"=> array(
												'nNF'=>''.$numero_nota.'',
												'entsai'=>'Saida',
												'cliente'=>''.$xml2->NFe->infNFe->dest->xNome.'',
												'valor'=>''.number_format(doubleval($xml2->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
												'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
											),
										));									
									
								}
								
							}


							$vetcfs = $daocf->VerificaRelacionamento(trim($cfop),$_SESSION['cnpj']);
							$numcfs = count($vetcfs);
							
							if($numcfs > 0){
								$vercfops = $vetcfs[0];
								
								$codcfops   = $vercfops->getCodigo();
								$nomcfops   = $vercfops->getNome();
								$geraagregs = $vercfops->getGeraAgregar();
								$vinculados = $vercfops->getVinculado();
								$idvinculos = $vercfops->getIdVinculado();
								
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
								//não existe essa cfop no sistema
								$err_cfop[] =array(
									'codigo'=>''.trim($cfop).'',
									'msg'=>' CFOP ('.trim($cfop).') não Existe no sistema ou não esta gravado!  '
								);									
							}
							

							
							$vicms	    = !empty($vICMS) ? $vICMS : 0.00;

							if ($vicms <= 0){
								array_push($alert_valoricms,array(
									'codigo'=>''.trim($codigo_produto).'',
									'cnota'=>''.trim($numero_nota).'',
									'msg'=>' Código do produto ('.trim($codigo_produto).' '.$desc_prod.') :: Produto não possui débito de ICMS ::',
									"dados"=> array(
										'nNF'=>''.$numero_nota.'',
										'entsai'=>'Saida',
										'cliente'=>''.$xml2->NFe->infNFe->dest->xNome.'',
										'valor'=>''.number_format(doubleval($xml2->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
										'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
									),
								));
							}


						}


						array_push($dados,array(
							'Numero'=>''.$xml2->NFe->infNFe->ide->nNF.'',
							'chave'=>''.$xml2->protNFe->infProt->chNFe.'',
							'entsai'=>'SAIDA',
							'cliente'=>''.$xml2->NFe->infNFe->dest->xNome.'',
							'valor'=>''.number_format(doubleval($xml2->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
							'caminho'=>'',
							'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',	
							'msg'=>''								
						));
						
						
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
				
			$erro_cfop = $funcao->array_sort($err_cfop,'codigo',SORT_ASC);
			$cfop_erro = array();	
			foreach($erro_cfop as $key=>$value){				
				array_push($cfop_erro,$value);					
			}
				
			$erro_produto = $funcao->array_sort($err_produto,'codigo',SORT_ASC);	
			$produto_erro = array();	
			foreach($erro_produto as $keys=>$values){
				array_push($produto_erro,$values);
				
			}
				
				
							
			$data = array(
					'erro'=>array(
						'cfop'=>$cfop_erro,
						'produto'=>$produto_erro,
						'nota'=>$err_nota,
						'vivorendmento'=>$err_v_r,
						'abate'=>$err_dataabate,
						'produtoterceiro'=>$err_produtoter
					),
					'info'=>array(
						'funcionario'=>$alert_nfunc,
						'folha'=>$alert_vlfolha,
						'icmsnormal'=>$alert_icmsnorm,
						'icmsst'=>$alert_icmsst,
						'gta'=>$alert_gta,
						'infabate'=>$alert_dataabate,
						'valoricms'=>$alert_valoricms,
						'sessao'=>$alerta_sessao,
						'num_entrada'=>$numnotasenttxt,
						'num_saida'=>$numnotasaitxt
					),
					'dados_grid'=>$dados);		
				
			echo json_encode($data);
		
		break;	
		case 'valida_processo2':

				
			$destino     	= "../arquivos/{$_SESSION['cnpj']}/importado/";	
			$erro        	= array();
			$dados       	= array();
			$err_cfop    	= array();
			$err_produto 	= array();	
			$err_nota    	= array();
			$err_v_r	 	= array();
			
			$alert_nfunc    = array();
			$alert_vlfolha  = array();	
			$alert_icmsnorm = array();	
			$alert_icmsst   = array();	
			$alert_gta      = array();	
				
			$funcao   = new FuncoesDAO();	
			$daor     = new ProdFrigTxtDAO();	
			$daocf    = new CfopDAO();	
			//aqui abrindo a pasta para passa o arquivo
			$pasta = opendir($destino);
				
			while ($arquivo = readdir($pasta)){

				if ($arquivo != "." && $arquivo != ".."){

						$xml =  simplexml_load_file($destino.$arquivo);	
							
						
						if($xml->NFe->infNFe->ide->tpNF == 0){
							//NOTA DE ENTRADA																					
													
								$dEmi         = str_replace('T','',$xml->NFe->infNFe->ide->dhEmi);
								$cnpj_cpf     = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
																
								
								$i = 0; 
								foreach($xml->NFe->infNFe->det as $prod){
									
									
									$vetr = $daor->ListaRelacionado(trim($prod->prod->cProd),$_SESSION['cnpj']);
									$numr = count($vetr);
									
									if($numr == 0){
										
										array_push($err_produto,array(
											'codigo'=>''.trim($prod->prod->cProd).'',
											'cnota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
											'msg'=>' Código do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') :: Produto não relacionado com o produto do agregar ::',
											"dados"=> array(
												'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'entsai'=>'Entrada',
												'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
												'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
												'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
											),
										));
									}
									
									$vetcf = $daocf->VerificaRelacionamento(trim($prod->prod->CFOP),$_SESSION['cnpj']);
									$numcf = count($vetcf);
									
									if($numcf > 0){
										$vercfop = $vetcf[0];
										
										$codcfop   = $vercfop->getCodigo();
										$nomcfop   = $vercfop->getNome();
										$geraagreg = $vercfop->getGeraAgregar();
										$vinculado = $vercfop->getVinculado();
										$idvinculo = $vercfop->getIdVinculado();
										
										if(empty($geraagreg)){
											$err_cfop[] = array(
												'idvinc'=>''.$idvinculo.'',
												'codigo'=>''.trim($prod->prod->CFOP).'',
												'nota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
												'arquivo'=>''.$arquivo.'',
												'msg'=>' CFOP ('.trim($prod->prod->CFOP).') sem indicação se irá "Considerar" ou "Desconsiderar" na apuração de crédito.',
											);
										}																				
										
									}else{
										//não existe essa cfop no sistema
										$err_cfop[] = array(
											'codigo'=>''.trim($prod->prod->CFOP).'',
											'msg'=>' CFOP ('.trim($prod->prod->CFOP).') não Existe no sistema ou não esta gravado!  '
										);									
									}
									
									//$prod->infAdProd
									$daonotasen1 = new NotasEn1TxtDAO();
									$vetnotasen1 = $daonotasen1->VerificaCabecasPreenchidas($xml->NFe->infNFe->ide->nNF,trim($prod->prod->cProd),$_SESSION['cnpj'],($i+1));
									$numnotasen1 = count($vetnotasen1);
									
									if($numnotasen1 > 0){
										
										$notasen1 = $vetnotasen1[0];
										
										$qtd_cabeca   = $notasen1->getQtdCabeca();
										$tipo_r_v     = $notasen1->getTipo_R_V();
										$peso_carcasa = $notasen1->getPesoCarcasa();

										if(empty($qtd_cabeca)){
											
												array_push($err_nota,array(
													'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
													'cProd'=>''.trim($prod->prod->cProd).'',
													'idseq'=>($i+1),
													'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') esta faltando numero de cabeças a ser preenchido!',
												));
											
										}/*else if($peso_carcasa <= 0){
											array_push($err_nota,array(
													'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
													'cProd'=>''.trim($prod->prod->cProd).'',
													'msg'=>' Numero da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') esta faltando numero de cabeças a ser preenchido!',
												));	
										}*/
										
										
										if(empty($tipo_r_v)){
											array_push($err_v_r,array(
												'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'cProd'=>''.trim($prod->prod->cProd).'',
												'qCom'=>''.number_format(doubleval($prod->prod->qCom),2,',','.').'',
												'idseq'=>($i+1),
												'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') # Linha do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') <br/> Esta faltando Informar se é Vivo ou Rendimento!',
											));
											
										}
										
									}
																																		
									
									$i++;
								}
								
								$daogta = new GtaTxtDAO();
								$vetgta = $daogta->GtaEmpresas($xml->NFe->infNFe->ide->nNF,$_SESSION['cnpj']);
								$numgta = count($vetgta);
									
								if($numgta == 0){
									
									array_push($alert_gta,array(
											'codigo'=>''.$xml->NFe->infNFe->ide->nNF.'',
											'msg'=>' Número da nota ('.$xml->NFe->infNFe->ide->nNF.') Não existem numeros de GTA informados!',
									));
									
								}


								array_push($dados,array(
										
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'chave'=>''.$xml->protNFe->infProt->chNFe.'',
									'entsai'=>'ENTRADA',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'caminho'=>''.$arquivo.'',
									'dEmi'=>''.date('d/m/Y',strtotime($dEmi)).'',
									'msg'=>''									
								));	
								
							
						}else{
							
								$dEmi       = str_replace('T','',$xml->NFe->infNFe->ide->dhEmi);
								$cnpj_cpf2   = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
															
								$i = 0; 
								foreach($xml->NFe->infNFe->det as $prod){
									
									
									$vetrl = $daor->ListaRelacionado(trim($prod->prod->cProd),$_SESSION['cnpj']);
									$numrl = count($vetrl);
									
									if($numrl == 0){
										
										array_push($err_produto,array(
											'codigo'=>''.trim($prod->prod->cProd).'',
											'cnota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
											'msg'=>' Código do produto ('.trim($prod->prod->cProd).' '.$prod->prod->xProd.') :: Produto não relacionado com o produto do agregar ::',
											"dados"=> array(
												'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
												'entsai'=>'Saida',
												'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
												'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
												'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
											),
										));
									}
									
									$vetcfs = $daocf->VerificaRelacionamento(trim($prod->prod->CFOP),$_SESSION['cnpj']);
									$numcfs = count($vetcfs);
									
									if($numcfs > 0){
										$vercfops = $vetcfs[0];
										
										$codcfops   = $vercfops->getCodigo();
										$nomcfops   = $vercfops->getNome();
										$geraagregs = $vercfops->getGeraAgregar();
										$vinculados = $vercfops->getVinculado();
										$idvinculos = $vercfops->getIdVinculado();
										
										if(empty($geraagregs)){
											$err_cfop[] =array(
												'idvinc'=>''.$idvinculos.'',
												'codigo'=>''.trim($prod->prod->CFOP).'',
												'nota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
												'arquivo'=>''.$arquivo.'',
												'msg'=>' CFOP ('.trim($prod->prod->CFOP).') sem indicação se irá "Considerar" ou "Desconsiderar" na apuração de crédito.',
											);
										}																														
										
										
									}else{
										//não existe essa cfop no sistema
										$err_cfop[] =array(
											'codigo'=>''.trim($prod->prod->CFOP).'',
											'msg'=>' CFOP ('.trim($prod->prod->CFOP).') não Existe no sistema ou não esta gravado!  '
										);									
									}
																											
									$i++;
								}
							
								array_push($dados,array(
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'chave'=>''.$xml->protNFe->infProt->chNFe.'',
									'entsai'=>'SAIDA',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'caminho'=>''.$arquivo.'',
									'dEmi'=>''.date('d/m/Y',strtotime($dEmi)).'',	
									'msg'=>''								
								));	
							
							
							
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
				
			$erro_cfop = $funcao->array_sort($err_cfop,'codigo',SORT_ASC);
			$cfop_erro = array();	
			foreach($erro_cfop as $key=>$value){				
				array_push($cfop_erro,$value);					
			}
				
			$erro_produto = $funcao->array_sort($err_produto,'codigo',SORT_ASC);	
			$produto_erro = array();	
			foreach($erro_produto as $keys=>$values){
				array_push($produto_erro,$values);
				
			}
				
				
							
			$data = array(
					'erro'=>array(
						'cfop'=>$cfop_erro,
						'produto'=>$produto_erro,
						'nota'=>$err_nota,
						'vivorendmento'=>$err_v_r,
					),
					'info'=>array(
						'funcionario'=>$alert_nfunc,
						'folha'=>$alert_vlfolha,
						'icmsnormal'=>$alert_icmsnorm,
						'icmsst'=>$alert_icmsst,
						'gta'=>$alert_gta
					),
					'dados_grid'=>$dados);		
				
			echo json_encode($data);
				
				
		break;
				
		case 'validacaocfop':
				$funcao    = new FuncoesDAO();
				$erro_cfop = $funcao->array_sort($_REQUEST['arr'],'codigo',SORT_ASC);
				$erro	   = array();
				
				foreach($erro_cfop as $key=>$value){				
					array_push($erro,$value);					
				}
				
				echo "<pre>";
				print_r($erro);	

		break;	
		case 'removexml':		
			
			$arquivo  = $_REQUEST['caminho']; 	
			$destino  = "../arquivos/{$_SESSION['cnpj']}/importado/";	
			$num_entrada   = 0;
			$num_saida     = 0;
			$total_entrada = 0;
			$total_saida   = 0;
			$erro		   = array();
			$result		   = array();
			$dados		   = array();	
			$dadosn        = array();

				

			$xmls 	 =  simplexml_load_file($destino.$arquivo);

			if($xmls->NFe->infNFe->ide->tpNF == 0){

				$numero = $xmls->NFe->infNFe->ide->nNF;

				$daoent = new NotasEntTxtDAO();
				$vetent = $daoent->PegaExclusao($numero,$_SESSION['cnpj']);
				$nument = count($vetent);
				
				for($i = 0; $i < $nument; $i++){
				
					$notasenttxt = $vetent[$i];
					
					$ident = $notasenttxt->getCodigo();
					
					$notasent = new NotasEntTxt();
					
					$notasent->setCodigo($ident);
					$notasent->setCnpjEmp($_SESSION['cnpj']);
					
					$daoent->deletar($notasent);
						
				} 
				
				$daoen = new NotasEn1TxtDAO();
				$veten = $daoen->PegaExclusao($numero,$_SESSION['cnpj']);
				$numen = count($veten);					
				
				
				for($x = 0; $x < $numen; $x++){
					
					$notasen1txt = $veten[$x];	
					
					$iden = $notasen1txt->getCodigo();
					
					$notasen1 = new NotasEn1Txt();
					
					$notasen1->setCodigo($iden);
					$notasen1->setCnpjEmp($_SESSION['cnpj']);
					
					$daoen->deletar($notasen1);
												
				}


			}else{
				$numero = $xmls->NFe->infNFe->ide->nNF;

				$daosai = new NotasSaiTxtDAO();
				$vetsai = $daosai->PegaExclusao($numero,$_SESSION['cnpj']);
				$numsai = count($vetsai);
					
				
				for($i = 0; $i < $numsai; $i++){
				
					$notasaitxt = $vetsai[$i];
				
					$id 	 = $notasaitxt->getCodigo();
					
					$notasai = new NotasSaiTxt();	
						
					$notasai->setCodigo($id);	
					$notasai->setCnpjEmp($_SESSION['cnpj']);
					
					$daosai->deletar($notasai);
					
				}				
				
				$daosa = new NotasSa1TxtDAO();
				$vetsa = $daosa->PegaExclusao($numero,$_SESSION['cnpj']);
				$numsa = count($vetsa);
			
				for($x = 0; $x < $numsa; $x++){
					
					$notasa1txt = $vetsa[$x];
						
					$idsai 	    = $notasa1txt->getCodigo();	
						
					$sa1 = new NotasSa1Txt();	
					
					$sa1->setCodigo($idsai);
					$sa1->setCnpjEmp($_SESSION['cnpj']);
										
					$daosa->deletar($sa1);
						
				}
			}
				
			unlink($destino.$arquivo);
				
			if(empty($erro)){
				//aqui abrindo a pasta para passa o arquivo
				$pasta = opendir($destino);
				while ($arquivo = readdir($pasta)){

					if ($arquivo != "." && $arquivo != ".."){

						$xml 	 =  simplexml_load_file($destino.$arquivo);
							
							$ent_sai = $xml->NFe->infNFe->ide->tpNF == 0 ? 'ENTRADA': 'SAIDA';


							array_push($dados,array(
								'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
								'dhEmi'=>''.date('d/m/Y',strtotime(str_replace('T','',$xml->NFe->infNFe->ide->dhEmi))).'',
								'chave'=>''.$xml->protNFe->infProt->chNFe.'',
								'ent_sai'=>''.$ent_sai.'',
								'cli_for'=>''.$xml->NFe->infNFe->dest->xNome.'',
								'valor_nota'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
								'caminho'=>''.$arquivo.'',
							));
							
							if($xml->NFe->infNFe->ide->tpNF == 0){						
								//entrada
								$total_entrada = $total_entrada + $xml->NFe->infNFe->total->ICMSTot->vNF;															
								$num_entrada++;							
							}else{							
								//saida								
								$total_saida 	= $total_saida  + $xml->NFe->infNFe->total->ICMSTot->vNF;						
								$num_saida++;					
							}


					}

				}

				array_push($result,array(
					'total_entradas'=>''.number_format($total_entrada,2,',','.').'',
					'numero_entradas'=>''.$num_entrada.'',
					'total_saida'=>''.number_format($total_saida,2,',','.').'',
					'numero_saida'=>''.$num_saida.''
				));


			}

		$data = array('dados'=>$result,'erro'=>$erro,'dados_grid'=>$dados,'dadosn'=>$dadosn);												
		echo json_encode($data);	
				
				
		break;
		case 'pegadados':
			/*echo "<pre>";
			print_r($_REQUEST);	
			echo "</pre>";	*/
			if(empty($_REQUEST['produto'])){
				$produto = "";
			}else{
				$produto = $_REQUEST['produto'];
			}	
			
			if(empty($_REQUEST['idproduto'])){
				$idproduto = "";
			}else{
				$idproduto = $_REQUEST['idproduto'];
			}	
			
			if(empty($_REQUEST['qtd'])){
				$quantidade = "0";				
			}else{				
				$quantidade = str_replace(',', '.', str_replace('.', '', $_REQUEST['qtd']));
			}

			if(empty($_REQUEST['cabeca'])){
				$cabeca = "0";				
			}else{				
				$cabeca = str_replace(',', '.', str_replace('.', '', $_REQUEST['cabeca']));
			}
			
			if(empty($_REQUEST['precoquilo'])){
				$precoquilo = "0";
			}else{
				$precoquilo = str_replace(',', '.', str_replace('.', '', $_REQUEST['precoquilo']));
			}	
			
				
			if(empty($_REQUEST['pecas'])){
				$valor = "0";
			}else{
				$valor = str_replace(',', '.', str_replace('.', '', $_REQUEST['pecas']));										
			}
				
			$total = $quantidade * $precoquilo;
				
			
			$result = array();
				
			array_push($result,array(										
				'idprox'=>''.mt_rand(0,1000).'',
				'produto'=>''.$idproduto.' - '.$produto.'',
				'idproduto'=>''.$idproduto.'',
				'quantidade'=>''.$quantidade.'',
				'cabeca'=>''.$cabeca.'',
				'precoquilo'=>''.number_format($precoquilo,2,',','.').'',
				'valor'=>''.number_format($valor,2,',','.').'',				
				'total'=>''.number_format($total,2,',','.').'',	
				'tipo'=>'inserir',				
			));

			echo(json_encode($result));		
			
				
		break;
		
		case 'alterdados':
			/*echo "<pre>";
			print_r($_REQUEST);	
			echo "</pre>";	*/
			if(empty($_REQUEST['id'])){
				$id = 0;
			}else{
				$id = $_REQUEST['id'];
			}
			if(empty($_REQUEST['produto'])){
				$produto = "";
			}else{
				$produto = $_REQUEST['produto'];
			}	
			
			if(empty($_REQUEST['idproduto'])){
				$idproduto = "";
			}else{
				$idproduto = $_REQUEST['idproduto'];
			}	
			
			if(empty($_REQUEST['qtd'])){
				$quantidade = "0";				
			}else{				
				$quantidade = str_replace(',', '.', str_replace('.', '', $_REQUEST['qtd']));
			}

			if(empty($_REQUEST['cabeca'])){
				$cabeca = "0";				
			}else{				
				$cabeca = str_replace(',', '.', str_replace('.', '', $_REQUEST['cabeca']));
			}
			
			if(empty($_REQUEST['precoquilo'])){
				$precoquilo = "0";
			}else{
				$precoquilo = str_replace(',', '.', str_replace('.', '', $_REQUEST['precoquilo']));
			}	
			
				
			if(empty($_REQUEST['pecas'])){
				$valor = "0";
			}else{
				$valor = str_replace(',', '.', str_replace('.', '', $_REQUEST['pecas']));										
			}
				
			$total = $quantidade * $precoquilo;
				
			
			$result = array();
				
			array_push($result,array(										
				'idprox'=>''.$id.'',
				'produto'=>''.$idproduto.' - '.$produto.'',
				'idproduto'=>''.$idproduto.'',
				'quantidade'=>''.$quantidade.'',
				'cabeca'=>''.$cabeca.'',
				'precoquilo'=>''.number_format($precoquilo,2,',','.').'',
				'valor'=>''.number_format($valor,2,',','.').'',				
				'total'=>''.number_format($total,2,',','.').'',	
				'tipo'=>'alteracao',				
			));

			echo(json_encode($result));		
			
				
		break;
		
		case 'inserirnotamanual':

			$result          = array();		
			$entsai 		 = $_REQUEST['es'];	
			$dataemiss 	 	 = implode("-", array_reverse(explode("/", "".$_REQUEST['dataemiss']."")));
			
			$numero_nota 	 = str_pad($_REQUEST['numero_nota'], 9, "0", STR_PAD_LEFT);
			$idclifor	 	 = $_REQUEST['idclifor'];	
			$tipo		 	 = $_REQUEST['tipo'];	
			$gta         	 = $_REQUEST['gta'];	
			$condenas	 	 = $_REQUEST['condenas'];	
			$abate       	 = $_REQUEST['abate'];	 	
			$idcfop     	 = $_REQUEST['idcfop'];
			$subtotalprodnfe = str_replace(',', '.', str_replace('.', '', $_REQUEST['subtotalprodnfe']));
		    $valoricms       = str_replace(',', '.', str_replace('.', '', $_REQUEST['valoricms']));
			$valoricmssubs   = str_replace(',', '.', str_replace('.', '', $_REQUEST['valoricmssubs']));
			$data_abate	     = implode("-", array_reverse(explode("/", "".$_REQUEST['dataabate']."")));	
				
			$daoe = new EmpresasTxtDAO();
			$vete = $daoe->BuscaEmpresasUm($idclifor,$_SESSION['cnpj']);	
			$nume = count($vete);
			
			if($nume > 0){
				$emptxt   = $vete[0];
				
				$cnpj_cpf = $emptxt->getCnpjCpf();
				$insc_est = $emptxt->getInscEstadual();
			} 	
				
			if($entsai == "E"){
				
				$notasenttxt = new NotasEntTxt();
						
				$notasenttxt->setNumeroNota($numero_nota);
				$notasenttxt->setDataEmissao($dataemiss);
				$notasenttxt->setCnpjCpf($cnpj_cpf);
				$notasenttxt->setTipoV_R_A($tipo);
				$notasenttxt->setValorTotalNota($subtotalprodnfe);
				$notasenttxt->setGta($gta);
				$notasenttxt->setNumeroNotaProdutorIni('');
				$notasenttxt->setNumeroNotaProdutorFin('');
				$notasenttxt->setCondenas($condenas);
				$notasenttxt->setAbate($abate);
				$notasenttxt->setInscProdutor($insc_est);
				$notasenttxt->setCnpjEmp($_SESSION['cnpj']);
				$notasenttxt->setXml('');

				$daonotasenttxt = new NotasEntTxtDAO();		
				$daonotasenttxt->inserir($notasenttxt);

				$i = 0; 

				foreach($_REQUEST['item'] as $key=>$value){


					$notasen1 = new NotasEn1Txt();

					$notasen1->setNumeroNota($numero_nota);
					$notasen1->setDataEmissao($dataemiss);
					$notasen1->setCnpjCpf($cnpj_cpf);
					$notasen1->setCodigoProduto($value['idproduto']);
					$notasen1->setQtdCabeca(str_replace(',', '.', str_replace('.', '', $value['valor'])));
					
					if($tipo == 'V'){
						$notasen1->setPesoVivoCabeca(str_replace(',', '.', str_replace('.', '', $value['quantidade'])));
						$notasen1->setPesoCarcasa(0.00);
					}else{
						$notasen1->setPesoVivoCabeca(0.00);
						$notasen1->setPesoCarcasa(str_replace(',', '.', str_replace('.', '', $value['quantidade'])));
					}
					
					$notasen1->setPrecoQuilo(str_replace(',', '.', str_replace('.', '', $value['precoquilo'])));
					$notasen1->setNumeroItemNota(($i+1));
					$notasen1->setInsEstadual($insc_est);
					$notasen1->setDataAbate($data_abate);
					$notasen1->setTipo_R_V($tipo);
					$notasen1->setCfop($idcfop);
					$notasen1->setAliquotaIcms(0);
					$notasen1->setCnpjEmp($_SESSION['cnpj']);
					$notasen1->setProdQtd(str_replace(',', '.', str_replace('.', '', $value['quantidade'])));

					$daonotasen1 = new NotasEn1TxtDAO();
					$daonotasen1->inserir($notasen1);

					$i++;				

					//echo $key." idproduto:".$value['idproduto']." qtd:".$value['quantidade']." valor:".$value['valor']."<br/>";

				}	
				
				array_push($result,array(															
					'msg'=>'Inserido com sucesso , vamos para a proxima!',					
				));

			
				
			}else if($entsai == "S"){
				//notas de saidas
				$daocfop = new Cfop2DAO();
				$vetcfop = $daocfop->VerificaCfopDevolucaoSN(trim($idcfop));

				if(trim($idcfop) > 5000 and $vetcfop[0]['devolucao'] == 'N'){
					$entsai_sai_mestre = "S";
				}else if(trim($idcfop) < 5000 and $vetcfop[0]['devolucao'] == 'S'){
					$entsai_sai_mestre = "E";
				}else if(trim($idcfop) < 5000 and $vetcfop[0]['devolucao'] == 'N'){
					$entsai_sai_mestre = "E";
				}else if(trim($idcfop) > 5000 and $vetcfop[0]['devolucao'] == 'S'){
					$entsai_sai_mestre = "S";
				}

				$notasaitxt = new NotasSaiTxt();
						
				$notasaitxt->setNumeroNota($numero_nota);
				$notasaitxt->setDataEmissao($dataemiss);
				$notasaitxt->setCnpjCpf($cnpj_cpf);
				$notasaitxt->setValorTotalNota($subtotalprodnfe);
				$notasaitxt->setValorIcms($valoricms);
				$notasaitxt->setValorIcmsSubs($valoricmssubs);
				$notasaitxt->setEntSai($entsai_sai_mestre);
				$notasaitxt->setInscEstadual($insc_est);
				$notasaitxt->setCfop($idcfop);
				$notasaitxt->setCnpjEmp($_SESSION['cnpj']);
				$notasaitxt->setXml('');

				$daonotasaitxt = new NotasSaiTxtDAO();
				$daonotasaitxt->inserir($notasaitxt);

				$i = 0; 
				foreach($_REQUEST['item'] as $key=>$value){

					$notasa1txt = new NotasSa1Txt();

					$notasa1txt->setNumeroNota($numero_nota);
					$notasa1txt->setDataEmissao($dataemiss);
					$notasa1txt->setCnpjCpf($cnpj_cpf);
					$notasa1txt->setCodigoProduto($value['idproduto']);
					$notasa1txt->setQtdPecas(str_replace(',', '.', str_replace('.', '', $value['valor'])));
					$notasa1txt->setPeso(str_replace(',', '.', str_replace('.', '', $value['quantidade'])));
					$notasa1txt->setPrecoUnitario(str_replace(',', '.', str_replace('.', '', $value['precoquilo'])));
					$notasa1txt->setEntSai($entsai_sai_mestre);
					$notasa1txt->setNumeroItemNota(($i+1));		
					$notasa1txt->setInscEstadual($insc_est);
					$notasa1txt->setCfop($idcfop);
					$notasa1txt->setAliquotaIcms(0);
					$notasa1txt->setCnpjEmp($_SESSION['cnpj']);
					$notasa1txt->setProdQtd(str_replace(',', '.', str_replace('.', '', $value['quantidade'])));
					$notasa1txt->setVicms($valoricms);
					
					$daonotasa1txt = new NotasSa1TxtDAO();

					$daonotasa1txt->inserir($notasa1txt);
					$i++;
				
					//echo $key." idproduto:".$value['idproduto']." qtd:".$value['quantidade']." valor:".$value['valor']."<br/>";

				}
				array_push($result,array(															
					'msg'=>'Inserido com sucesso , vamos para a proxima!',					
				));
			}
				

			$daoprot = new ProtocoloDAO();
			$vetprot = $daoprot->ListaProtocoloPorEmmpresaCompetencia(date('m/Y',strtotime($dataemiss)),$_SESSION['cnpj']);
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
						
				$prot->setCompetencia(date('m/Y',strtotime($dataemiss)));
				$prot->setProtocolo('');
				$prot->setCripty('');
				$prot->setStatus(7);
				$prot->setCnpjEmp($_SESSION['cnpj']);	
				$prot->setTipoArq(2);

				$daoprot->inserir($prot);

			}


			echo(json_encode($result));		
		break;
		
		case 'alteracanotamanual':
			$result          = array();		
			$entsai 		 = $_REQUEST['es'];	
			$dataemiss 	 	 = implode("-", array_reverse(explode("/", "".$_REQUEST['dataemiss']."")));
			
			$numero_nota 	 = $_REQUEST['numero_nota'];
			$idclifor	 	 = $_REQUEST['idclifor'];	
			$tipo		 	 = $_REQUEST['tipo'];	
			$gta         	 = $_REQUEST['gta'];	
			$condenas	 	 = $_REQUEST['condenas'];	
			$abate       	 = $_REQUEST['abate'];	 	
			$idcfop     	 = $_REQUEST['idcfop'];
			$subtotalprodnfe = str_replace(',', '.', str_replace('.', '', $_REQUEST['subtotalprodnfe']));
		    $valoricms       = str_replace(',', '.', str_replace('.', '', $_REQUEST['valoricms']));
			$valoricmssubs   = str_replace(',', '.', str_replace('.', '', $_REQUEST['valoricmssubs']));
			$data_abate	     = implode("-", array_reverse(explode("/", "".$_REQUEST['dataabate']."")));	
				
			$daoe = new EmpresasTxtDAO();
			$vete = $daoe->BuscaEmpresasUm($idclifor,$_SESSION['cnpj']);	
			$nume = count($vete);
			
			if($nume > 0){
				$emptxt   = $vete[0];
				
				$cnpj_cpf = $emptxt->getCnpjCpf();
				$insc_est = $emptxt->getInscEstadual();
			} 

			if($entsai == "E"){

				$daoent =  new NotasEntTxt2DAO();
				$vetent = $daoent->buscaid($numero_nota,$_SESSION['cnpj']);
				
				if($vetent){
						
						$notasenttxt = new NotasEntTxt();

						$notasenttxt->setCodigo($vetent[0]['id']);		
						$notasenttxt->setDataEmissao($dataemiss);
						$notasenttxt->setCnpjCpf($cnpj_cpf);
						$notasenttxt->setTipoV_R_A($tipo);
						$notasenttxt->setValorTotalNota($subtotalprodnfe);
						$notasenttxt->setGta($gta);		
						$notasenttxt->setCondenas($condenas);
						$notasenttxt->setAbate($abate);
						$notasenttxt->setInscProdutor($insc_est);
						
						$daonotasenttxt = new NotasEntTxtDAO();		
						$daonotasenttxt->update($notasenttxt);

						$daodetent = new NotasEn1Txt2DAO();
						$vetdetent = $daodetent->pegaIds($_SESSION['cnpj'],$numero_nota);
						if($vetdetent){
							for($x =0; $x < count($vetdetent); $x++){
								$id  = $vetdetent[$x]['id'];
								$daodetent->removeitens($id,$_SESSION['cnpj']);
							}
						}

					$i = 0;
					foreach($_REQUEST['item'] as $key=>$value){


						$notasen1 = new NotasEn1Txt();

						$notasen1->setNumeroNota($numero_nota);
						$notasen1->setDataEmissao($dataemiss);
						$notasen1->setCnpjCpf($cnpj_cpf);
						$notasen1->setCodigoProduto($value['idproduto']);
						$notasen1->setQtdCabeca(str_replace(',', '.', str_replace('.', '', $value['valor'])));
						
						if($tipo == 'V'){
							$notasen1->setPesoVivoCabeca(str_replace(',', '.', str_replace('.', '', $value['quantidade'])));
							$notasen1->setPesoCarcasa(0.00);
						}else{
							$notasen1->setPesoVivoCabeca(0.00);
							$notasen1->setPesoCarcasa(str_replace(',', '.', str_replace('.', '', $value['quantidade'])));
						}
						
						$notasen1->setPrecoQuilo(str_replace(',', '.', str_replace('.', '', $value['precoquilo'])));
						$notasen1->setNumeroItemNota(($i+1));
						$notasen1->setInsEstadual($insc_est);
						$notasen1->setDataAbate($data_abate);
						$notasen1->setTipo_R_V($tipo);
						$notasen1->setCfop($idcfop);
						$notasen1->setAliquotaIcms(0);
						$notasen1->setCnpjEmp($_SESSION['cnpj']);
						$notasen1->setProdQtd(str_replace(',', '.', str_replace('.', '', $value['quantidade'])));
				
						$daonotasen1 = new NotasEn1TxtDAO();
						$daonotasen1->inserir($notasen1);

						$i++;										

					}	
					
					array_push($result,array(															
						'msg'=>'Alterado com sucesso , vamos para a proxima!',					
					));

				}
				

			}else if($entsai == "S"){

				$daos = new NotasSaiTxt2DAO();
				$vets = $daos->buscaId($numero_nota,$_SESSION['cnpj']);
				
				if($vets){


					$daocfop = new Cfop2DAO();
					$vetcfop = $daocfop->VerificaCfopDevolucaoSN(trim($idcfop));
	
					if(trim($idcfop) > 5000 and $vetcfop[0]['devolucao'] == 'N'){
						$entsai_sai_mestre = "S";
					}else if(trim($idcfop) < 5000 and $vetcfop[0]['devolucao'] == 'S'){
						$entsai_sai_mestre = "E";
					}else if(trim($idcfop) < 5000 and $vetcfop[0]['devolucao'] == 'N'){
						$entsai_sai_mestre = "E";
					}else if(trim($idcfop) > 5000 and $vetcfop[0]['devolucao'] == 'S'){
						$entsai_sai_mestre = "S";
					}else{
						$entsai_sai_mestre = "S";
					}
	
					$notasaitxt = new NotasSaiTxt();
							
					$notasaitxt->setCodigo($vets[0]['id']);
					$notasaitxt->setDataEmissao($dataemiss);
					$notasaitxt->setCnpjCpf($cnpj_cpf);
					$notasaitxt->setValorTotalNota($subtotalprodnfe);
					$notasaitxt->setValorIcms($valoricms);
					$notasaitxt->setValorIcmsSubs($valoricmssubs);
					$notasaitxt->setEntSai($entsai_sai_mestre);
					$notasaitxt->setInscEstadual($insc_est);
					$notasaitxt->setCfop($idcfop);
	
					$daonotasaitxt = new NotasSaiTxtDAO();
					$daonotasaitxt->update($notasaitxt);

					$daosa1 = new NotasSa1Txt2DAO();
					$vetsa1 = $daosa1->pegaIdsParaRemovecao($numero_nota,$_SESSION['cnpj']);
					if($vetsa1){
						for ($x=0; $x < count($vetsa1); $x++) { 							
							$daosa1->removeItens($vetsa1[$x]['id'],$_SESSION['cnpj']);							
						}
						
					}

					$i = 0; 
					foreach($_REQUEST['item'] as $key=>$value){

						$notasa1txt = new NotasSa1Txt();

						$notasa1txt->setNumeroNota($numero_nota);
						$notasa1txt->setDataEmissao($dataemiss);
						$notasa1txt->setCnpjCpf($cnpj_cpf);
						$notasa1txt->setCodigoProduto($value['idproduto']);
						$notasa1txt->setQtdPecas(str_replace(',', '.', str_replace('.', '', $value['valor'])));
						$notasa1txt->setPeso(str_replace(',', '.', str_replace('.', '', $value['quantidade'])));
						$notasa1txt->setPrecoUnitario(str_replace(',', '.', str_replace('.', '', $value['precoquilo'])));
						$notasa1txt->setEntSai($entsai_sai_mestre);
						$notasa1txt->setNumeroItemNota(($i+1));		
						$notasa1txt->setInscEstadual($insc_est);
						$notasa1txt->setCfop($idcfop);
						$notasa1txt->setAliquotaIcms(0);
						$notasa1txt->setCnpjEmp($_SESSION['cnpj']);
						$notasa1txt->setProdQtd(str_replace(',', '.', str_replace('.', '', $value['quantidade'])));
						$notasa1txt->setVicms($valoricms);
						
						$daonotasa1txt = new NotasSa1TxtDAO();

						$daonotasa1txt->inserir($notasa1txt);
						$i++;									

					}

					array_push($result,array(															
						'msg'=>'Alterado com sucesso , vamos para a proxima!',					
					));


				}

			}

			$daoprot = new ProtocoloDAO();
			$vetprot = $daoprot->ListaProtocoloPorEmmpresaCompetencia(date('m/Y',strtotime($dataemiss)),$_SESSION['cnpj']);
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
						
				$prot->setCompetencia(date('m/Y',strtotime($dataemiss)));
				$prot->setProtocolo('');
				$prot->setCripty('');
				$prot->setStatus(7);
				$prot->setCnpjEmp($_SESSION['cnpj']);	
				$prot->setTipoArq(2);

				$daoprot->inserir($prot);

			}

			echo(json_encode($result));
		break;	

		case 'validmesano':
			
			$mesano = $_POST['mesano'];

				//VALIDAR COM STATUS SE O STATUS ENTREGUE PARA O PAULO DAR UM MENSAGEM QUE JA FOI ENTREGUE E MOSTRAR O NUMERO DO RECIBO PARA O PAULO LIBERAR

			$dao 	= new ResumoDAO();
			$vet 	= $dao->ValidaMesAnoCompetencia($mesano,$_SESSION['cnpj']);
			$num 	= count($vet);
			$result = array();

			if($num > 0){

				$resu = $vet[0];	

				$status    = $resu->getStatus();	
				$protocolo = $resu->getProtocolo();

				if(empty($status)){

					//inserir o status para importando	
					array_push($result, array(
						'msg'=>'Tudo certo!',
						'tipo'=>'2'
					));					

				}else{

					if($status == 8){

						array_push($result, array(
							'msg'=>'Competência já Recebida!, Recibo de numero '.$protocolo.' já esta em processo, caso queira regerar entrar em contato com Agregar, Obrigado! ',
							'tipo'=>'3'
						));	

					}else{

						array_push($result, array(
							'msg'=>'Já Existe Competência gerada nesse mês e ano!, deseja gerar novamente ? ',
							'tipo'=>'1'
						));		

					}	


				}

				
			}else{
				//inserir o status para importando		
				array_push($result, array(
					'msg'=>'Tudo certo!',
					'tipo'=>'2'
				));

			}

			echo json_encode($result);

		break;

		case 'validmesanoinicio':
			
			$mesano = $_POST['mesano'];
			$_SESSION['apura']['mesano'] = $mesano;
				//VALIDAR COM STATUS SE O STATUS ENTREGUE PARA O PAULO DAR UM MENSAGEM QUE JA FOI ENTREGUE E MOSTRAR O NUMERO DO RECIBO PARA O PAULO LIBERAR

			$dao 	= new ProtocoloDAO();
			$vet 	= $dao->VerificaCompetencia($mesano,$_SESSION['cnpj']);
			$num 	= count($vet);
			$result = array();
			$status = "";
			$protocolo = "";
			if($num > 0){

				$resu = $vet[0];	

				$status    = $resu->getStatus();	
				$protocolo = $resu->getProtocolo();

				array_push($result, array(
					'status'=>$status,
					'protocolo'=>$protocolo,
					'msg'=>'',
					'tipo'=>'1'
				));	

				
			}else{
				//inserir o status para importando		
				array_push($result, array(
					'status'=>$status,
					'protocolo'=>$protocolo,
					'msg'=>'Tudo certo!',
					'tipo'=>'2'
				));

			}

			echo json_encode($result);

		break;

		case 'excluircopetencias':


			$mesano = $_POST['mesano'];


			## deletando notas de entradas
			$daoen = new NotasEntTxtDAO();
			$veten = $daoen->PegaNumerosNotaEntradas($mesano,$_SESSION['cnpj']);
			$numen = count($veten);


			for($i = 0; $i < $numen; $i++){

				$notasent    = $veten[$i];

				$ident       = $notasent->getCodigo();
				$numero_nota = $notasent->getNumeroNota();		

				$daoen1 = new NotasEn1TxtDAO();
				$veten1 = $daoen1->PegaExclusaoCompetencia($mesano,$numero_nota,$_SESSION['cnpj']);	
				$numen1 = count($veten1);


				for($x = 0; $x < $numen1; $x++){

						$notasen1 = $veten1[$x];

						$iden1 = $notasen1->getCodigo();

						$notasen1txt = new NotasEn1Txt();

						$notasen1txt->setCodigo($iden1);
						$notasen1txt->setCnpjEmp($_SESSION['cnpj']);

						$daoen1->deletar($notasen1txt);	
				}

				$notasenttxt = new NotasEntTxt();
				
				$notasenttxt->setCodigo($ident);
				$notasenttxt->setCnpjEmp($_SESSION['cnpj']);					

				$notasenttxt->deletar($notasenttxt);		
			}

			## termino do deletando notas de entradas
				
			## deletando as notas de saida
			$daosai =  NotasSaiTxtDAO();
			$vetsai = $daosai->NotasSaiCompetenciaExclui($mesano,$_SESSION['cnpj']);
			$numsai = count($vetsai);

			for($y = 0; $y < $numsai; $y++){

					$notasai = $vetsai[$y];

					$idsai 		 	= $notasai->getCodigo();
					$numero_notasai = $notasai->getNumeroNota();

					$daosa1 = new NotasSa1TxtDAO();
					$vetsa1 = $daosa1->PegaExclusaoCompetencia($mesano,$numero_notasai,$_SESSION['cnpj']);	
					$numsa1 = count($vetsa1);

					for($z = 0; $z < $numsa1; $z++){

						$notasa1 = $vetsa1[$z];

						$idsa1   = $notasa1->getCodigo();

						$notasa1txt = new NotasSa1Txt();		

						$notasa1txt->setCodigo($idsa1);
						$notasa1txt->setCnpjEmp($_SESSION['cnpj']);	

						$daosa1->deletar($notasa1txt);

					}


					$notasaitxt = new NotasSaiTxt();
					
					$notasaitxt->setCodigo($idsai);
					$notasaitxt->setCnpjEmp($_SESSION['cnpj']);

					$daosai->deletar($notasaitxt);

			}

			## termino do deletando as notas de saida

			$result = array();

			array_push($result, array(
				'msg'=>'Arquivos da competencia '.$mesano.' foram deletados!'	
			));	

			echo json_encode($result);

		break;	
		case 'eliminaarquivos':
			
			$destino = "../arquivos/{$_SESSION['cnpj']}/importado/";
			$pasta   = opendir($destino);
			$result  = array();

			while ($arquivo = readdir($pasta)){

				if ($arquivo != "." && $arquivo != ".."){
					unlink($destino.$arquivo);
				}
			}

			array_push($result, array(
				'msg'=>'arquivos removidos',
			));
			echo json_encode($result);
		break;	

		case 'updatecabecasadm':
					
			$ncabecas = str_replace(',', '.', str_replace('.', '', $_REQUEST['value']));	
			$pk       = explode('|', $_REQUEST['pk']);
			$cprod    = $pk[0];
			$nnota    = $pk[1];	
			$codnota  = $pk[2];	
			$result   = array();
				
			$daonotasen1 = new NotasEn1TxtDAO();
			$vetnotasen1 = $daonotasen1->VerificaIdCabecas($nnota,$cprod,$_SESSION['cnpj'],$codnota);	
			$numnotasen1 = count($vetnotasen1);	
				
			if($numnotasen1 > 0){
				
				$notasen1 = $vetnotasen1[0];
				
				$id   = $notasen1->getCodigo();
				
				$notasen1txt = new NotasEn1Txt();
					
				$notasen1txt->setCodigo($id);
				$notasen1txt->setQtdCabeca($ncabecas);
				
				$daonotasen1->updatecabecas($notasen1txt);
				
				array_push($result,array(
					'id'=>''.$id.'',
					'ncabecas'=>''.$ncabecas.'',
					'cprod'=>''.$cprod.'',
					'nnota'=>''.$nnota.'',
				));
			}	
				
			echo json_encode($result);	
				
		break;
		case 'updatevivorendadm':
				
			$pk           = explode('|', $_REQUEST['pk']);		
			$vivorend 	  = $pk[2];	
			$cprod    	  = $pk[0];	
			$nnota   	  = $pk[1];	
			$npeso        = str_replace(',', '.', str_replace('.', '', $_REQUEST['value']));

			if($vivorend == 'R'){
				$pesocarcasa = $npeso;
				$pesovivo    = !empty($pk[3]) ? str_replace(',', '.', str_replace('.', '', $pk[3])) : 0;
				
				if($pk[4] == '0.00'){
					$vivorend = 'V';
				}
			}else if($vivorend == 'V'){
				$pesovivo    = $npeso;	
				$pesocarcasa = !empty($pk[4]) ? str_replace(',', '.', str_replace('.', '', $pk[4])) : 0;
				
				if($pk[3] == '0.00'){
					$vivorend = 'R';
				}
			}

			$result   = array();
				
			$daonotasen1 = new NotasEn1TxtDAO();
			$vetnotasen1 = $daonotasen1->VerificaIdCabecas($nnota,$cprod,$_SESSION['cnpj']);	
			$numnotasen1 = count($vetnotasen1);	
				
			if($numnotasen1 > 0){
				
				$notasen1 = $vetnotasen1[0];
				
				$id   = $notasen1->getCodigo();
				
				$notasen1txt = new NotasEn1Txt();
					
				$notasen1txt->setCodigo($id);
				$notasen1txt->setTipo_R_V($vivorend);
				$notasen1txt->setPesoCarcasa($pesocarcasa);
				$notasen1txt->setPesoVivoCabeca($pesovivo);

				$daonotasen1->updatevivorend($notasen1txt);
				
				array_push($result,array(
					'id'=>''.$id.'',
					'vivorend'=>''.$vivorend.'',
					'cprod'=>''.$cprod.'',
					'nnota'=>''.$nnota.'',
					'npesocarcaca'=>''.$pesocarcasa.'',
					'npesovivo'=>''.$pesovivo.''
				));
			}	
				
			echo json_encode($result);	
				
		break;
		case 'arqxmlcabecas':

			$file    = $_FILES[0]['tmp_name'];
			$fun     = new FuncoesDAO();
			$mycript = new MyCripty();
			$xls     = new SimpleXLSX();
			$ch      = sha1($_SESSION['id_emp'].''.$_SESSION['cnpj'].''.$_SESSION['inscemp']);			 			
			$result  = array();
			$erro  	 = array();
											
			if ($xlsx = $xls->parse($file)) {
				//print_r($xlsx->rows());
				$validemp = $xlsx->rows()[0][1];
				$topo     = $fun->limpar_string($xlsx->rows()[1]);	
				if($validemp === $ch){	
					$header_values = $rows = [];
					foreach ( $xlsx->rows() as $k => $r ) {
						if ( $k === 0 or $k === 1 ) {
							if($k === 1){
								//echo $fun->limpar_string($r);
								$header_values = $fun->limpar_string($r);
								//print_r($r);
							}
							continue;
						}
						//print_r($r);
						$rows[] = array_combine( $header_values, $r );
					}
					

					if($topo[0] == 'NUMERONOTA' and $topo[1] == 'SERIE' and $topo[2] == 'CODIGOPRODUTO' and $topo[3] == 'ITEM' and $topo[4] == 'NCABECAS'){
						
						for($i = 0; $i < count($rows); $i++){
							$dados = $rows[$i];	
							
							$NUMERONOTA    = trim($dados['NUMERONOTA']);
							$SERIE		   = trim($dados['SERIE']);
							$CODIGOPRODUTO = trim($dados['CODIGOPRODUTO']);
							$ITEM          = trim($dados['ITEM']);  
							$NCABECAS	   = str_replace(',', '.', str_replace('.', '', $dados['NCABECAS']));
							if (is_numeric($NCABECAS)) {
								$daonotasen1 = new NotasEn1TxtDAO();
								$vetnotasen1 = $daonotasen1->VerificaIdCabecas2($NUMERONOTA,$CODIGOPRODUTO,$_SESSION['cnpj'],$ITEM);	
								$numnotasen1 = count($vetnotasen1);
								
								if($numnotasen1 > 0){
									
									$notasen1 	 = $vetnotasen1[0];
					
									$id  		 = $notasen1->getCodigo();
									
									$notasen1txt = new NotasEn1Txt();
										
									$notasen1txt->setCodigo($id);
									$notasen1txt->setQtdCabeca($NCABECAS);
									
									$daonotasen1->updatecabecas($notasen1txt);
									
									array_push($result,array(
										'id'=>''.$id.'',
										'ncabecas'=>''.$NCABECAS.'',
										'cprod'=>''.$CODIGOPRODUTO.'',
										'nnota'=>''.$NUMERONOTA.'',
										'item'=>''.$ITEM.'',
										'msg'=>"Número Nota: {$NUMERONOTA} Série: {$SERIE} Código Produto: {$CODIGOPRODUTO} N° Cabeças: {$NCABECAS} => Atualizado com sucesso!",
									));

								}else{
									array_push($result,array(
										'id'=>''.$id.'',
										'ncabecas'=>''.$NCABECAS.'',
										'cprod'=>''.$CODIGOPRODUTO.'',
										'nnota'=>''.$NUMERONOTA.'',
										'item'=>''.$ITEM.'',
										'msg'=>"Número Nota: {$NUMERONOTA} Série: {$SERIE} Código Produto: {$CODIGOPRODUTO} N° Cabeças: {$NCABECAS} => Não estão vinculadas a essa competencia!",
									));
									
								}		

							}else{
								array_push($erro,array(
									'id'=>'',
									'ncabecas'=>'',
									'cprod'=>'',
									'nnota'=>'',
									'item'=>'',
									'msg'=>"Número Nota: {$NUMERONOTA} Série: {$SERIE} Código Produto: {$CODIGOPRODUTO} N° Cabeças: {$NCABECAS} => Número de cabeças incorreto!",
								));
							}

						}		

					}else{
						array_push($erro,array(
							'id'=>'',
							'ncabecas'=>'',
							'cprod'=>'',
							'nnota'=>'',
							'item'=>'',
							'msg'=>"Arquivo incorreto, arquivo tem que ficar igual ao exemplo disponibilizado!",
						));
												
					}
					
			 }else{
				array_push($erro,array(
					'id'=>'',
					'ncabecas'=>'',
					'cprod'=>'',
					'nnota'=>'',
					'item'=>'',
					'msg'=>"Chave não confere com os dados da empresa!",
				));
				
			 }		

			}else{
				//echo $xls->parseError();
				array_push($erro,array(
					'id'=>'',
					'ncabecas'=>'',
					'cprod'=>'',
					'nnota'=>'',
					'msg'=>"{$xls->parseError()}",
				));
			}
							
			$data = ($erro) ? array('error' => $erro) : array('result' => $result);			
			echo json_encode($data);

		break;
		case 'teste':
			
			echo preg_replace("/[^0-9]/", "", substr('25', 0, strpos('25', ".")));
			if(preg_match("/^(\d+)?\.\d+$/", '25')) {
				echo preg_replace("/[^0-9]/", "", substr('25.00', 0, strpos('25.00', ".")));
			}

			/*$valor = "123.00";
			if (is_numeric($valor)) {
				echo 'Números';		
			}
			else {
				echo 'Agora sim são letras';
				
			}*/

			/*$txt = "1 CBC";	
			$posicaoqtde = strripos(trim($txt), "Qtde:");
			$posicaoqtde = explode('Qtde:',$txt);
			$posicaoqtde2 = explode('CBC',$txt);

			echo "<pre>";	
			print_r($posicaoqtde2);
			echo preg_replace("/[^0-9]/", "", $posicaoqtde2[0]);	*/
			//$ncabecaqtd = preg_replace("/[^0-9]/", "", $posicaoqtde);	
			

			//echo $ncabecaqtd;	


			/*$mes = '02';
			$ano = '2018';
			echo cal_days_in_month(CAL_GREGORIAN, $mes , $ano);*/
			

			//Instanciando classe

			
			/*$encodeTXT = '06/2018|750|88888888888888';	

			$cript =  sha1($encodeTXT);	

			echo preg_replace("/[^0-9]/", "", $cript)."<br/>";	*/


		break;
		
		case 'removecompetencia':
			
			$daoprot = new ProtocoloDAO();
			$vetprot = $daoprot->ListaProtocoloPorEmmpresaCompetencia($_SESSION['apura']['mesano'],$_SESSION['cnpj']);
			$numprot = count($vetprot);

			if($numprot > 0){

					$prot   = $vetprot[0];
					
				    $idprot = $prot->getCodigo();
									
					$prots = new Protocolo();
					
					$prots->setCodigo($idprot);
					
					$daoprot->removeProtocolo($prots);						
					
			}
			$res = array();
			array_push($res,array(
				'msg'=>'Removido com sucesso!'
			));
			echo json_encode($res);
		break;
		
		case 'removercompentencia':

			$mesano = $_POST['mesano'];
			$cnpj   = $_POST['cnpjemp'];
			$tipo   = $_POST['tipo'];
			$prod   = array();
			## pegar notas ent para remover

			$daonotasent = new NotasEntTxtDAO();
			$vetnotasent = $daonotasent->ListaNotasEntrada($cnpj,$mesano); 
			$numnotasent = count($vetnotasent);

			for($i = 0; $i < $numnotasent; $i++){

				$notasent    = $vetnotasent[$i];
				
				$id = $notasent->getCodigo();

				$setnotasent = new NotasEntTxt();

				$setnotasent->setCodigo($id);
				$setnotasent->setCnpjEmp($cnpj);

				$daonotasent->deletar($setnotasent);				

			}

			## fim notas ent para remover

			## pegar notas ent 1 para remover

			$daonotasent1 = new NotasEn1TxtDAO();
			$vetnotasent1 = $daonotasent1->ListaNotasEntradaDetalheParaExclusao($cnpj,$mesano);
			$numnotasent1 = count($vetnotasent1);

			for($i = 0; $i <  $numnotasent1; $i++){

				$notasen1 = $vetnotasent1[$i];

				$id1 			 = $notasen1->getCodigo();
				$codigo_produto1 = $notasen1->getCodigoProduto();
				array_push($prod,array(
					'idpro'=>''.$codigo_produto1.'',
				));

				$setnotasent1 = new NotasEn1Txt();

				$setnotasent1->setCodigo($id1);
				$setnotasent1->setCnpjEmp($cnpj);

				$daonotasent1->deletar($setnotasent1);

			}
			## Fim notas ent 1 para remover


			## pegar notas sai para remover

			$daonotassai = new NotasSaiTxtDAO();
			$vetnotassai = $daonotassai->ListandoNotasSai($mesano,$cnpj);
			$numnotassai = count($vetnotassai);

			for($i = 0; $i < $numnotassai; $i++){

				$notasai = $vetnotassai[$i]; 

				$idsai   = $notasai->getCodigo();

				$setnotassai = new NotasSaiTxt();

				$setnotassai->setCodigo($idsai);
				$setnotassai->setCnpjEmp($cnpj);

				$daonotassai->deletar($setnotassai);

			}

			## Fim notas sai para remover

			## pegar notas sai1 para remover
			$daonotassai1 = new NotasSa1TxtDAO();
			$vetnotassai1 = $daonotassai1->ListaNotasSa1Detalhe($cnpj,$mesano);
			$numnotassai1 = count($vetnotassai1);

			for($i = 0; $i < $numnotassai1; $i++){
				$notasa1 = $vetnotassai1[$i];

				$idsai1  = $notasa1->getCodigo();
				$codprod = $notasa1->getCodigoProduto();

				array_push($prod,array(
					'idpro'=>''.$codprod.'',
				));

				$setnotassai1 = new NotasSa1Txt();

				$setnotassai1->setCodigo($idsai1);
				$setnotassai1->setCnpjEmp($cnpj);

				$daonotassai1->deletar($setnotassai1);

			}

			## Fim notas sai1 para remover

			$daoprot = new ProtocoloDAO();
			$vetprot = $daoprot->ListaProtocoloExclusao($mesano,$cnpj);
			$numprot = count($vetprot);

			for($i = 0; $i < $numprot; $i++){

				$prot    = $vetprot[$i]; 
				$codprot = $prot->getCodigo();	

				$setprot = new Protocolo();

				$setprot->setCodigo($codprot);
				$setprot->setCnpjEmp($cnpj);

				$daoprot->removeProtocoloEmp($setprot);

			}

			
			if($tipo == 2){

				$dados_arr   = array_unique($prod, SORT_REGULAR); 
				$integrantes = "";
				$x 			 = 0;
				$sinal 		 = ", ";
				$size 		 = count($dados_arr);	

				foreach($dados_arr as $key=>$value){

					if($x == $size-1) 
						$sinal = "  ";
					elseif($x == $size) 
						$sinal = ".";

					$integrantes .= "'".$value['idpro']."'".$sinal;
					$x++;

				}

				$daoprodfrig = new ProdFrigTxtDAO();
				$vetprodfrig = $daoprodfrig->ListaCodigosExclusao($integrantes,$cnpj);
				$numprodfrig = count($vetprodfrig);


				for($i = 0; $i < $numprodfrig; $i++){

					$prodfrig    = $vetprodfrig[$i]; 
					$codprodfrig = $prodfrig->getCodigo();	

					$setprodfrig = new ProdFrigTxt();
					
					$setprodfrig->setCodigo($codprodfrig);
					$setprodfrig->setCnpjEmp($cnpj);

					$daoprodfrig->delete($setprodfrig);


				}
				
			}

			echo "Competência <strong>{$mesano}</strong> removido(a) com sucesso!";

		break;
		case 'semmovi':
		
			$mesano = $_REQUEST['mesano'];
			$obs	= $_REQUEST['obs'];
			$cnpj_emp = $_SESSION['cnpj'];

			$msgs = new MensagemEmpresa();
					
			$msgs->setTitulo('Geração sem movimentação do agregar competencia '.$mesano.' ');
			$msgs->setMensagem(utf8_decode("Nome: {$_SESSION['login']}<br/> Cnpj:{$cnpj_emp}<br> Geração do agregar sem movimentação Motivo: {$obs} "));
			$msgs->setIdModalidade(1);
			$msgs->setIdEmpresa($_SESSION['id_emp']);
			$msgs->setData(date('Y-m-d'));
			$msgs->setTimesTamp(time());
			
			$daoms  = new MensagemEmpresaDAO();
						
			$daoms->inserir($msgs);


			$prot = new Protocolo();
					
			$prot->setCompetencia($mesano);
			$prot->setProtocolo('');
			$prot->setCripty('');
			$prot->setStatus(7);
			$prot->setCnpjEmp($_SESSION['cnpj']);
			$prot->setTipoArq(1);	
			//1 = xml 2 = txt 3 = manual	
			$daoprot = new ProtocoloDAO();
			$daoprot->inserir($prot);

			$result = array();
			array_push($result,array(
				'msg'=>'proximo'
			));

			echo json_encode($result);

		break;
		case 'atupt':
			$cod 	= $_REQUEST['cod'];
			$mesano = $_REQUEST['mesano'];
			$tipo   = $_REQUEST['tipo'];

			$daoent = new NotasEntTxt2DAO();
			$vetent = $daoent->buscaid($cod,$_SESSION['cnpj']);
			if($vetent){
				$notasenttxt = new NotasEntTxt();
				$notasenttxt->setCodigo($vetent[0]['id']);
				$notasenttxt->setCnpjEmp($_SESSION['cnpj']);
				$notasenttxt->setAbate($tipo);
				$notasenttxt->setDataEmissao($mesano);
				
				$dao = new NotasEntTxtDAO();
				$dao->updatepropterc($notasenttxt);
			}	
			$res = array();
			array_push($res,array(
				'msg'=>"Atualizado com sucesso!"
			));

			echo json_encode($res);

		break;
		case 'teste2':
			$destino	   = "../arquivos/{$_SESSION['cnpj']}/importado/";
			
			//$xmlstr = file_get_contents($destino.'43200702591772000766550010000041821700082388.xml');	
			$xmlstr = file_get_contents($destino.'43200687755799000376550000000039131004516462_578965f3ebee5685fb4216709424fba7ebff24a3.xml');
			//echo date('m/Y',strtotime(explode('T',$xml->NFe->infNFe->ide->dhEmi)[0]));

			$doc = new DOMDocument();
			$doc->formatOutput = false;
			$doc->preserveWhiteSpace = false;
			$doc->loadXML($xmlstr, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);	
			$chave 		=  !empty($doc->getElementsByTagName('chNFe')->item(0)->nodeValue) ? $doc->getElementsByTagName('chNFe')->item(0)->nodeValue :'Sem Informção da chave';
			$descEvento =  !empty($doc->getElementsByTagName('descEvento')->item(0)->nodeValue) ? $doc->getElementsByTagName('descEvento')->item(0)->nodeValue : '';
			$nNF        =  !empty($doc->getElementsByTagName('nNF')->item(0)->nodeValue) ? $doc->getElementsByTagName('nNF')->item(0)->nodeValue : 'Sem Informação';
			$vNF        =  !empty($doc->getElementsByTagName('vNF')->item(0)->nodeValue) ? $doc->getElementsByTagName('vNF')->item(0)->nodeValue : 'Sem Informação';

			if(!empty($doc->getElementsByTagName("dest")->item(0))){
				$det        = $doc->getElementsByTagName("dest")->item(0);
				$nomeDest   = $det->getElementsByTagName('xNome')->item(0)->nodeValue;
			}else{
				$nomeDest   = "Sem informação";
			}
			
			if(!empty($doc->getElementsByTagName("emit")->item(0))){
				$emit       = $doc->getElementsByTagName("emit")->item(0);			
				$nomeEmit   = $emit->getElementsByTagName('xNome')->item(0)->nodeValue;
			}else{
				$nomeEmit = "Sem Informação";
			}
			echo " {$chave} - {$descEvento} - {$nNF} - {$vNF} - {$nomeDest} -  {$nomeEmit} ";

			/*
			'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'dhEmi'=>'',
										'chave'=>''.$xml->protNFe->infProt->chNFe.'',
										'ent_sai'=>''.$ent_sai.'',
										'cli_for'=>''.$xml->NFe->infNFe->dest->xNome.'',
										'valor_nota'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
										'caminho'=>'',
										'msg'=>'NOTA FISCAL NÃO É AUTORIZADA!',
										'tipo'=>2
			
			*/ 


		break;

		case 'grava_valida':
			//error_reporting(E_ALL);
			//ini_set('display_errors', 'On');
			$destino        = "../arquivos/{$_SESSION['cnpj']}/importado/";	
			$erro           = array();
			$dados          = array();
			$err_cfop       = array();
			$err_produto    = array();	
			$err_nota       = array();
			$err_v_r	    = array();
			
			$alert_nfunc    = array();
			$alert_vlfolha  = array();	
			$alert_icmsnorm = array();	
			$alert_icmsst   = array();	
			$alert_gta      = array();
			$difcompmesano  = !empty($_REQUEST['difcompmesano']) ? $_REQUEST['difcompmesano'] : 3; /* 1 = sim e 2 = não and 3 = da mesma compatencia não passou pelo xml diferente da competencia */
			
			$_SESSION['alerta_sessao'][$_SESSION['apura']['mesano']] = !empty($_SESSION['alerta_sessao'][$_SESSION['apura']['mesano']]) ? $_SESSION['alerta_sessao'][$_SESSION['apura']['mesano']] : array();
			
			$numnotasenttxt = 0;
			$numnotasaitxt  = 0;
			
			$funcao   = new FuncoesDAO();	
			$daor     = new ProdFrigTxtDAO();	
			$daocf    = new CfopDAO();	

			$pathFile      = '../arquivos/'.$_SESSION['cnpj'].'/config.json';
			$configJson    = file_get_contents($pathFile);
			$installConfig = json_decode($configJson);

			//$_SESSION['apura']['mesano'] = $_REQUEST['mesano'];

			//aqui abrindo a pasta para passa o arquivo
			$pasta = opendir($destino);
				
			while ($arquivo = readdir($pasta)){

				if ($arquivo != "." && $arquivo != ".."){

						$xml 	= simplexml_load_file($destino.$arquivo);	
						$xmlstr = file_get_contents($destino.$arquivo);	
						
						$doc = new DOMDocument();
						$doc->loadXML($xmlstr);
						$xmlstr = $doc->saveXML();
						
						$xml    = $funcao->verificatagxml($xml);
						
						$daocfop = new Cfop2DAO();
						$vetcfop = $daocfop->VerificaCfopDevolucaoSN(trim($xml->NFe->infNFe->det->prod->CFOP));
						
						if(trim($xml->NFe->infNFe->det->prod->CFOP) < 5000 and $vetcfop[0]['devolucao'] == 'N'){
							//entrada
							//echo "{$xml->NFe->infNFe->ide->nNF}<br/>";
							$daonotasenttxt = new NotasEntTxtDAO();
							$vetnotasenttxt = $daonotasenttxt->VerificaNotas($xml->NFe->infNFe->ide->nNF,$_SESSION['cnpj'],$_SESSION['apura']['mesano']);
							$numnotasenttxt = count($vetnotasenttxt);
							$itemserie = 0;
							if($numnotasenttxt > 0){
								$xmlnota= $vetnotasenttxt[0]->getXml();

								$doc2 = new DOMDocument();
								$doc2->loadXML($xmlnota);								
								$xml2 = simplexml_import_dom($doc2);
								$xml2 = $funcao->verificatagxml($xml2);
								$xserie = intval($xml2->NFe->infNFe->ide->serie);										
								$serienota = intval($xml->NFe->infNFe->ide->serie);
								
								if($xserie != $serienota){
									$itemserie = 0;
								}else{
									$itemserie = 1;
								}
								
							}	
							
							if($numnotasenttxt == 0 or $itemserie == 0){
								//echo "entro";
								$dEmi         = explode('T',$xml->NFe->infNFe->ide->dhEmi);
								$dhSaiEnt     = explode('T',$xml->NFe->infNFe->ide->dhSaiEnt);
								$cnpj_cpf     = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
								$ie           = trim($xml->NFe->infNFe->dest->IE);
								if(empty(trim($cnpj_cpf))){
									$cnpj_cpf   = strlen(trim($xml->NFe->infNFe->emit->CNPJ)) == 14 ? trim($xml->NFe->infNFe->emit->CNPJ) : trim($xml->NFe->infNFe->emit->CPF);
									$ie         = trim($xml->NFe->infNFe->emit->IE);
								}
								
								if(!empty($installConfig->tppt)){
									$abatept = $installConfig->tppt;
								}else{
									$abatept = 'P';
								}

								if($difcompmesano == 1){
									//echo "entro".date('m/Y',strtotime($dEmi[0]))." ".$_SESSION['apura']['mesano']."\n";
									if(date('m/Y',strtotime($dEmi[0])) != $_SESSION['apura']['mesano']){
										
										$dEmi = date('d',strtotime($dEmi[0])).'/'.$_SESSION['apura']['mesano'];	
										$dEmi = explode(' ',implode("-", array_reverse(explode("/",$dEmi))).' '.date('h:m:s'));
										
									}
								}

								$notasenttxt = new NotasEntTxt();
						
								$notasenttxt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
								$notasenttxt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
								$notasenttxt->setCnpjCpf($cnpj_cpf);
								$notasenttxt->setTipoV_R_A('');
								$notasenttxt->setValorTotalNota(trim($xml->NFe->infNFe->total->ICMSTot->vNF));
								$notasenttxt->setGta('');
								$notasenttxt->setNumeroNotaProdutorIni('');
								$notasenttxt->setNumeroNotaProdutorFin('');
								$notasenttxt->setCondenas('');
								$notasenttxt->setAbate($abatept);
								$notasenttxt->setInscProdutor(trim($ie));
								$notasenttxt->setCnpjEmp($_SESSION['cnpj']);
								$notasenttxt->setChave($xml->protNFe->infProt->chNFe);
								$notasenttxt->setXml($xmlstr);
								
								$daonotasenttxt->inserir($notasenttxt);

								$i = 0; 
								foreach($xml->NFe->infNFe->det as $prod){

									if(empty($prod->infAdProd)){									
										$ncabeca = 0;										
									}else{
										
										$posicaopc    = explode('PC:',$prod->infAdProd);
										$posicaoqtde  = explode('Qtde:',$prod->infAdProd);
										$posicaoqtde2 = explode('CBC:',$prod->infAdProd);
										
										$ncabecapc    = !empty($posicaopc[1])  ? preg_replace("/[^0-9]/", "", $posicaopc[1])   : "";
										$ncabecaqtd   = !empty($posicaoqtde[1])? preg_replace("/[^0-9]/", "", $posicaoqtde[1]) : "";	
										$posicaoqtde2 = !empty($posicaoqtde[1])? preg_replace("/[^0-9]/", "", $posicaoqtde[1]) : "";

										if($ncabecapc != ""){
											$ncabeca = $ncabecapc;
											if(preg_match("/^(\d+)?\.\d+$/", $ncabeca)) {
												$ncabeca = preg_replace("/[^0-9]/", "", substr($ncabeca, 0, strpos($ncabeca, ".")));
											}
										}else if($ncabecaqtd != ""){
											$ncabeca = $ncabecaqtd;
											if(preg_match("/^(\d+)?\.\d+$/", $ncabeca)) {
												$ncabeca = preg_replace("/[^0-9]/", "", substr($ncabeca, 0, strpos($ncabeca, ".")));
											}
										}else if($posicaoqtde2 != ""){
											$ncabeca = $posicaoqtde2;
											if(preg_match("/^(\d+)?\.\d+$/", $ncabeca)) {
												$ncabeca = preg_replace("/[^0-9]/", "", substr($ncabeca, 0, strpos($ncabeca, ".")));
											}
										}else{
											$ncabeca = 0;											
										}

										if($ncabeca == 0){
											if(trim($prod->prod->uTrib) == 'KG'){
												$ncabeca = $prod->prod->qCom;
											}else{
												$ncabeca = $prod->prod->qTrib;
											}
										}
										
										if(strpos($prod->infAdProd, 'CABECA(S):') !== false) {	
											$posicaoqtde3 = $prod->infAdProd; 							
											$ncabeca =  preg_replace("/[^0-9]/", "",explode('CABECA(S):',$posicaoqtde3)[1]);
										}
										
										if(strpos($prod->infAdProd, 'cab') !== false) {
											$posicaoqtde4 = $prod->infAdProd;		
											if(!empty(preg_replace("/[^0-9]/", "",explode('cab',$posicaoqtde4)[0]))){												
												$ncabeca = preg_replace("/[^0-9]/", "",explode('cab',$posicaoqtde4)[0]);								
											}else if(!empty(preg_replace("/[^0-9]/", "",explode('cab',$posicaoqtde4)[1]))){						
												$ncabeca = preg_replace("/[^0-9]/", "",explode('cab',$posicaoqtde4)[1]);
											}																								
										}
										if(strpos($prod->infAdProd, 'Quantidade:') !== false) {	
											$posicaoqtde5 = $prod->infAdProd; 							
											$ncabeca =  intval(explode('Quantidade:',$posicaoqtde5)[1]);
										}
										
										
									}

									$peso_vivo_cabeca = 0;
									$peso_carcasa	  = 0;

									if(!empty($installConfig->notas->vivorend)){	
										if($installConfig->notas->vivorend == "V"){
											$peso_vivo_cabeca = $prod->prod->qCom;		
										}else if($installConfig->notas->vivorend == "R"){
											$peso_carcasa = $prod->prod->qCom;
										}
									}

									if($_SESSION['apura']['mesano'] == date('m/Y',strtotime($dEmi[0]))){
										if(!empty($installConfig->abate)){
											if($installConfig->abate == '1'){
												$dataabate = date('Y-m-d',strtotime($dEmi[0]));	
											}else if($installConfig->abate == '2'){
												$dataabate = date('Y-m-d',strtotime($dhSaiEnt[0]));	
											}else{
												$dataabate = "";
											}
										}else{
											$dataabate = date('Y-m-d',strtotime($dEmi[0]));
										}
									
									}else{
										$dataabate = '';
										if($difcompmesano == 1){
											if(date('m/Y',strtotime($dEmi[0])) != $_SESSION['apura']['mesano']){
												$dataabate = date('d',strtotime($dEmi[0])).'/'.$_SESSION['apura']['mesano'];	
												$dataabate = explode(' ',implode("-", array_reverse(explode("/",$dEmi))).' '.date('h:m:s'));
											}
										}
									}


									$notasen1 = new NotasEn1Txt();
	
									$notasen1->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
									$notasen1->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
									$notasen1->setCnpjCpf($cnpj_cpf);
									$notasen1->setCodigoProduto(trim($prod->prod->cProd));
									$notasen1->setQtdCabeca($ncabeca);
									$notasen1->setPesoVivoCabeca($peso_vivo_cabeca);
									$notasen1->setPesoCarcasa($peso_carcasa);
									$notasen1->setPrecoQuilo(trim($prod->prod->vUnCom));
									$notasen1->setNumeroItemNota(($i+1));
									$notasen1->setInsEstadual(trim($ie));
									$notasen1->setDataAbate($dataabate);
									$notasen1->setTipo_R_V($installConfig->notas->vivorend);
									$notasen1->setCfop(trim($prod->prod->CFOP));
									$notasen1->setAliquotaIcms(0);
									$notasen1->setCnpjEmp($_SESSION['cnpj']);
									$notasen1->setProdQtd(trim($prod->prod->qCom));
									$daonotasen1 = new NotasEn1TxtDAO();
									$daonotasen1->inserir($notasen1);

									$i++;
								}


								array_push($dados,array(
										
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'chave'=>''.$xml->protNFe->infProt->chNFe.'',
									'entsai'=>'ENTRADA',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'caminho'=>''.$arquivo.'',
									'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',
									'msg'=>''									
								));


							}
							

						}else if(trim($xml->NFe->infNFe->det->prod->CFOP) > 5000 and $vetcfop[0]['devolucao'] == 'S' AND trim($xml->NFe->infNFe->emit->CNPJ) == $_SESSION['cnpj']){
							//entrada devolvendo

							$daonotasenttxt = new NotasEntTxtDAO();
							$vetnotasenttxt = $daonotasenttxt->VerificaNotas($xml->NFe->infNFe->ide->nNF,$_SESSION['cnpj'],$_SESSION['apura']['mesano']);
							$numnotasenttxt = count($vetnotasenttxt);

							$itemserie = 0;
							if($numnotasenttxt > 0){
								$xmlnota= $vetnotasenttxt[0]->getXml();

								$doc2 = new DOMDocument();
								$doc2->loadXML($xmlnota);								
								$xml2 = simplexml_import_dom($doc2);
								$xml2 = $funcao->verificatagxml($xml2);
								$xserie = intval($xml2->NFe->infNFe->ide->serie);										
								$serienota = intval($xml->NFe->infNFe->ide->serie);
								
								if($xserie != $serienota){
									$itemserie = 0;
								}else{
									$itemserie = 1;
								}
								
							}

							if($numnotasenttxt == 0 or $itemserie == 0){

								$dEmi         = explode('T',$xml->NFe->infNFe->ide->dhEmi);
								$dhSaiEnt     = explode('T',$xml->NFe->infNFe->ide->dhSaiEnt);
								$cnpj_cpf     = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
								$ie           = trim($xml->NFe->infNFe->dest->IE);
								if(empty(trim($cnpj_cpf))){
									$cnpj_cpf   = strlen(trim($xml->NFe->infNFe->emit->CNPJ)) == 14 ? trim($xml->NFe->infNFe->emit->CNPJ) : trim($xml->NFe->infNFe->emit->CPF);
									$ie         = trim($xml->NFe->infNFe->emit->IE);
								}
								
								if(!empty($installConfig->tppt)){
									$abatept = $installConfig->tppt;
								}else{
									$abatept = 'P';
								}

								if($difcompmesano == 1){
									if(date('m/Y',strtotime($dEmi[0])) != $_SESSION['apura']['mesano']){
										$dEmi = date('d',strtotime($dEmi[0])).'/'.$_SESSION['apura']['mesano'];	
										$dEmi = explode(' ',implode("-", array_reverse(explode("/",$dEmi))).' '.date('h:m:s'));
									}
								}

								$notasenttxt = new NotasEntTxt();
						
								$notasenttxt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
								$notasenttxt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
								$notasenttxt->setCnpjCpf($cnpj_cpf);
								$notasenttxt->setTipoV_R_A('');
								$notasenttxt->setValorTotalNota(trim($xml->NFe->infNFe->total->ICMSTot->vNF));
								$notasenttxt->setGta('');
								$notasenttxt->setNumeroNotaProdutorIni('');
								$notasenttxt->setNumeroNotaProdutorFin('');
								$notasenttxt->setCondenas('');
								$notasenttxt->setAbate($abatept);
								$notasenttxt->setInscProdutor(trim($ie));
								$notasenttxt->setCnpjEmp($_SESSION['cnpj']);
								$notasenttxt->setChave($xml->protNFe->infProt->chNFe);
								$notasenttxt->setXml($xmlstr);
								
								$daonotasenttxt->inserir($notasenttxt);

								$i = 0; 
								foreach($xml->NFe->infNFe->det as $prod){

									if(empty($prod->infAdProd)){									
										$ncabeca = 0;										
									}else{
										
										$posicaopc    = explode('PC:',$prod->infAdProd);
										$posicaoqtde  = explode('Qtde:',$prod->infAdProd);
										$posicaoqtde2 = explode('CBC:',$prod->infAdProd);
										
										$ncabecapc    = !empty($posicaopc[1])  ? preg_replace("/[^0-9]/", "", $posicaopc[1])   : "";
										$ncabecaqtd   = !empty($posicaoqtde[1])? preg_replace("/[^0-9]/", "", $posicaoqtde[1]) : "";	
										$posicaoqtde2 = !empty($posicaoqtde[1])? preg_replace("/[^0-9]/", "", $posicaoqtde[1]) : "";

										if($ncabecapc != ""){
											$ncabeca = $ncabecapc;
											if(preg_match("/^(\d+)?\.\d+$/", $ncabeca)) {
												$ncabeca = preg_replace("/[^0-9]/", "", substr($ncabeca, 0, strpos($ncabeca, ".")));
											}
										}else if($ncabecaqtd != ""){
											$ncabeca = $ncabecaqtd;
											if(preg_match("/^(\d+)?\.\d+$/", $ncabeca)) {
												$ncabeca = preg_replace("/[^0-9]/", "", substr($ncabeca, 0, strpos($ncabeca, ".")));
											}
										}else if($posicaoqtde2 != ""){
											$ncabeca = $posicaoqtde2;
											if(preg_match("/^(\d+)?\.\d+$/", $ncabeca)) {
												$ncabeca = preg_replace("/[^0-9]/", "", substr($ncabeca, 0, strpos($ncabeca, ".")));
											}
										}else{
											$ncabeca = 0;											
										}

										if($ncabeca == 0){
											if(trim($prod->prod->uTrib) == 'KG'){
												$ncabeca = $prod->prod->vUnTrib;
											}else{
												$ncabeca = $prod->prod->qTrib;
											}
										}

										if(strpos($prod->infAdProd, 'CABECA(S):') !== false) {	
											$posicaoqtde3 = $prod->infAdProd; 							
											$ncabeca =  preg_replace("/[^0-9]/", "",explode('CABECA(S):',$posicaoqtde3)[1]);
										}
										
										if(strpos($prod->infAdProd, 'cab') !== false) {
											$posicaoqtde4 = $prod->infAdProd;		
											if(!empty(preg_replace("/[^0-9]/", "",explode('cab',$posicaoqtde4)[0]))){												
												$ncabeca = preg_replace("/[^0-9]/", "",explode('cab',$posicaoqtde4)[0]);								
											}else if(!empty(preg_replace("/[^0-9]/", "",explode('cab',$posicaoqtde4)[1]))){						
												$ncabeca = preg_replace("/[^0-9]/", "",explode('cab',$posicaoqtde4)[1]);
											}																								
										}
										
										if(strpos($prod->infAdProd, 'Quantidade:') !== false) {	
											$posicaoqtde5 = $prod->infAdProd; 							
											$ncabeca =  intval(explode('Quantidade:',$posicaoqtde5)[1]);
										}
											
									}

									$peso_vivo_cabeca = 0;
									$peso_carcasa	  = 0;

									if(!empty($installConfig->notas->vivorend)){	
										if($installConfig->notas->vivorend == "V"){
											$peso_vivo_cabeca = $prod->prod->qCom;		
										}else if($installConfig->notas->vivorend == "R"){
											$peso_carcasa = $prod->prod->qCom;
										}
									}

									if($_SESSION['apura']['mesano'] == date('m/Y',strtotime($dEmi[0]))){
										if(!empty($installConfig->abate)){
											if($installConfig->abate == '1'){
												$dataabate = date('Y-m-d',strtotime($dEmi[0]));	
											}else if($installConfig->abate == '2'){
												$dataabate = date('Y-m-d',strtotime($dhSaiEnt[0]));	
											}else{
												$dataabate = "";
											}
										}else{
											$dataabate = date('Y-m-d',strtotime($dEmi[0]));
										}
									
									}else{
										$dataabate = '';
										if($difcompmesano == 1){
											if(date('m/Y',strtotime($dEmi[0])) != $_SESSION['apura']['mesano']){
												$dataabate = date('d',strtotime($dEmi[0])).'/'.$_SESSION['apura']['mesano'];	
												$dataabate = explode(' ',implode("-", array_reverse(explode("/",$dEmi))).' '.date('h:m:s'));
											}
										}
									}


									$notasen1 = new NotasEn1Txt();
	
									$notasen1->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
									$notasen1->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
									$notasen1->setCnpjCpf($cnpj_cpf);
									$notasen1->setCodigoProduto(trim($prod->prod->cProd));
									$notasen1->setQtdCabeca($ncabeca);
									$notasen1->setPesoVivoCabeca($peso_vivo_cabeca);
									$notasen1->setPesoCarcasa($peso_carcasa);
									$notasen1->setPrecoQuilo(trim($prod->prod->vUnCom));
									$notasen1->setNumeroItemNota(($i+1));
									$notasen1->setInsEstadual(trim($ie));
									$notasen1->setDataAbate($dataabate);
									$notasen1->setTipo_R_V($installConfig->notas->vivorend);
									$notasen1->setCfop(trim($prod->prod->CFOP));
									$notasen1->setAliquotaIcms(0);
									$notasen1->setCnpjEmp($_SESSION['cnpj']);
									$notasen1->setProdQtd(trim($prod->prod->qCom));
									$daonotasen1 = new NotasEn1TxtDAO();
									$daonotasen1->inserir($notasen1);

									$i++;
								}


								array_push($dados,array(
										
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'chave'=>''.$xml->protNFe->infProt->chNFe.'',
									'entsai'=>'ENTRADA',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'caminho'=>''.$arquivo.'',
									'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',
									'msg'=>''									
								));


							}

							

						}else if(trim($xml->NFe->infNFe->det->prod->CFOP) > 5000 and $vetcfop[0]['devolucao'] == 'S' AND trim($xml->NFe->infNFe->emit->CNPJ) != $_SESSION['cnpj']){

							$dEmi       = explode('T',$xml->NFe->infNFe->ide->dhEmi);
							$cnpj_cpf2   = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
							$ie           = trim($xml->NFe->infNFe->dest->IE);

							if(empty(trim($cnpj_cpf2))){
								$cnpj_cpf2   = strlen(trim($xml->NFe->infNFe->emit->CNPJ)) == 14 ? trim($xml->NFe->infNFe->emit->CNPJ) : trim($xml->NFe->infNFe->emit->CPF);
								$ie          = trim($xml->NFe->infNFe->emit->IE);
							}
							$daonotasaitxt = new NotasSaiTxtDAO();
							$vetnotasaitxt = $daonotasaitxt->VerificaNota(trim($xml->NFe->infNFe->ide->nNF),$_SESSION['cnpj'],$_SESSION['apura']['mesano']); 
							$numnotasaitxt = count($vetnotasaitxt);

							$itemserie = 0;
							if($numnotasaitxt > 0){
								$xmlnota= $vetnotasaitxt[0]->getXml();

								$doc2 = new DOMDocument();
								$doc2->loadXML($xmlnota);								
								$xml2 = simplexml_import_dom($doc2);
								$xml2 = $funcao->verificatagxml($xml2);
								$xserie = intval($xml2->NFe->infNFe->ide->serie);										
								$serienota = intval($xml->NFe->infNFe->ide->serie);
								
								if($xserie != $serienota){
									$itemserie = 0;
								}else{
									$itemserie = 1;
								}
								
							}

							if($numnotasaitxt == 0 or $itemserie == 0){
								
								$entsai_sai_mestre = "E";

								$notasaitxt = new NotasSaiTxt();
						
								$notasaitxt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
								$notasaitxt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
								$notasaitxt->setCnpjCpf($cnpj_cpf2);
								$notasaitxt->setValorTotalNota(trim($xml->NFe->infNFe->total->ICMSTot->vNF));
								$notasaitxt->setValorIcms(trim($xml->NFe->infNFe->total->ICMSTot->vICMS));
								$notasaitxt->setValorIcmsSubs(trim($xml->NFe->infNFe->total->ICMSTot->vST));
								$notasaitxt->setEntSai($entsai_sai_mestre);
								$notasaitxt->setInscEstadual(trim($ie));
								$notasaitxt->setCfop(trim($xml->NFe->infNFe->det->prod->CFOP));
								$notasaitxt->setCnpjEmp($_SESSION['cnpj']);
								$notasaitxt->setChave($xml->protNFe->infProt->chNFe);
								$notasaitxt->setXml($xmlstr);
								
								$daonotasaitxt->inserir($notasaitxt);

								$i = 0; 
								foreach($xml->NFe->infNFe->det as $prod){
									
									$entsai_sai = "E";
									
									$imposto    = json_encode($prod->imposto->ICMS);
									$arrayimp   = json_decode($imposto,TRUE);
									$icms       = array_pop($arrayimp);
									$vicms	    = !empty($icms['vICMS']) ? $icms['vICMS'] : 0.00;

									$indForn    = trim($xml->NFe->infNFe->emit->CNPJ).''.trim($xml->NFe->infNFe->emit->IE);

									$daotrc = new RecionamentoTerceirosDAO();
									$vettrc =  $daotrc->VerificaRelacionamento($indForn,trim($prod->prod->cProd));
									if(count($vettrc) > 0){
										$cprod = $vettrc[0]['idprodproprio'];
									}else{
										$cprod = "";
									}


									$notasa1txt = new NotasSa1Txt();
						
									$notasa1txt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
									$notasa1txt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
									$notasa1txt->setCnpjCpf($cnpj_cpf2);
									$notasa1txt->setCodigoProduto($cprod);
									$notasa1txt->setQtdPecas(trim(0));
									$notasa1txt->setPeso(trim($prod->prod->qCom));
									$notasa1txt->setPrecoUnitario(trim($prod->prod->vUnCom));
									$notasa1txt->setEntSai($entsai_sai);
									$notasa1txt->setNumeroItemNota(($i+1));		
									$notasa1txt->setInscEstadual(trim($ie));
									$notasa1txt->setCfop(trim($xml->NFe->infNFe->det->prod->CFOP));
									$notasa1txt->setAliquotaIcms(0);
									$notasa1txt->setCnpjEmp($_SESSION['cnpj']);
									$notasa1txt->setProdQtd(trim($prod->prod->qCom));
									$notasa1txt->setVicms($vicms);

									$daonotasa1txt = new NotasSa1TxtDAO();
									
									$daonotasa1txt->inserir($notasa1txt);
									$i++;
									}
								}

								array_push($dados,array(
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'chave'=>''.$xml->protNFe->infProt->chNFe.'',
									'entsai'=>'SAIDA',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'caminho'=>''.$arquivo.'',
									'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',	
									'msg'=>''								
								));


						}else if(trim($xml->NFe->infNFe->det->prod->CFOP) > 5000 and $vetcfop[0]['devolucao'] == 'N' AND trim($xml->NFe->infNFe->emit->CNPJ) == $_SESSION['cnpj']){
							//saida
							$dEmi         = explode('T',$xml->NFe->infNFe->ide->dhEmi);
							$cnpj_cpf2    = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
							$ie           = trim($xml->NFe->infNFe->dest->IE);
							
							if(empty(trim($cnpj_cpf2))){
								$cnpj_cpf2   = strlen(trim($xml->NFe->infNFe->emit->CNPJ)) == 14 ? trim($xml->NFe->infNFe->emit->CNPJ) : trim($xml->NFe->infNFe->emit->CPF);
								$ie          = trim($xml->NFe->infNFe->emit->IE);
							}
							
							$daonotasaitxt = new NotasSaiTxtDAO();
							$vetnotasaitxt = $daonotasaitxt->VerificaNota(trim($xml->NFe->infNFe->ide->nNF),$_SESSION['cnpj'],$_SESSION['cnpj'],$_SESSION['apura']['mesano']); 
							$numnotasaitxt = count($vetnotasaitxt);

							$itemserie = 0;
							if($numnotasaitxt > 0){
								$xmlnota= $vetnotasaitxt[0]->getXml();

								$doc2 = new DOMDocument();
								$doc2->loadXML($xmlnota);								
								$xml2 = simplexml_import_dom($doc2);
								$xml2 = $funcao->verificatagxml($xml2);
								$xserie = intval($xml2->NFe->infNFe->ide->serie);										
								$serienota = intval($xml->NFe->infNFe->ide->serie);
								
								if($xserie != $serienota){
									$itemserie = 0;
								}else{
									$itemserie = 1;
								}
								
							}
							
							if($numnotasaitxt == 0 or $itemserie == 0){

								$entsai_sai_mestre = "S";

								$notasaitxt = new NotasSaiTxt();
						
								$notasaitxt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
								$notasaitxt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
								$notasaitxt->setCnpjCpf($cnpj_cpf2);
								$notasaitxt->setValorTotalNota(trim($xml->NFe->infNFe->total->ICMSTot->vNF));
								$notasaitxt->setValorIcms(trim($xml->NFe->infNFe->total->ICMSTot->vICMS));
								$notasaitxt->setValorIcmsSubs(trim($xml->NFe->infNFe->total->ICMSTot->vST));
								$notasaitxt->setEntSai($entsai_sai_mestre);
								$notasaitxt->setInscEstadual(trim($ie));
								$notasaitxt->setCfop(trim($xml->NFe->infNFe->det->prod->CFOP));
								$notasaitxt->setCnpjEmp($_SESSION['cnpj']);
								$notasaitxt->setChave($xml->protNFe->infProt->chNFe);
								$notasaitxt->setXml($xmlstr);
								
								$daonotasaitxt->inserir($notasaitxt);

								$i = 0; 
								foreach($xml->NFe->infNFe->det as $prod){
									
									$entsai_sai = "S";
									
									$imposto    = json_encode($prod->imposto->ICMS);
									$arrayimp   = json_decode($imposto,TRUE);
									$icms       = array_pop($arrayimp);
									$vicms	    = !empty($icms['vICMS']) ? $icms['vICMS'] : 0.00;
									
									$notasa1txt = new NotasSa1Txt();
						
									$notasa1txt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
									$notasa1txt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
									$notasa1txt->setCnpjCpf($cnpj_cpf2);
									$notasa1txt->setCodigoProduto(trim($prod->prod->cProd));
									$notasa1txt->setQtdPecas(trim(0));
									$notasa1txt->setPeso(trim($prod->prod->qCom));
									$notasa1txt->setPrecoUnitario(trim($prod->prod->vUnCom));
									$notasa1txt->setEntSai($entsai_sai);
									$notasa1txt->setNumeroItemNota(($i+1));		
									$notasa1txt->setInscEstadual(trim($ie));
									$notasa1txt->setCfop(trim($xml->NFe->infNFe->det->prod->CFOP));
									$notasa1txt->setAliquotaIcms(0);
									$notasa1txt->setCnpjEmp($_SESSION['cnpj']);
									$notasa1txt->setProdQtd(trim($prod->prod->qCom));
									$notasa1txt->setVicms($vicms);
									
									$daonotasa1txt = new NotasSa1TxtDAO();
									
									$daonotasa1txt->inserir($notasa1txt);
									$i++;

								}

								array_push($dados,array(
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'chave'=>''.$xml->protNFe->infProt->chNFe.'',
									'entsai'=>'SAIDA',
									'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
									'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
									'caminho'=>''.$arquivo.'',
									'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',	
									'msg'=>''								
								));

							}




						}else if(trim($xml->NFe->infNFe->det->prod->CFOP) < 5000 and $vetcfop[0]['devolucao'] == 'S'){
							//saida
								$dEmi       = explode('T',$xml->NFe->infNFe->ide->dhEmi);
								$cnpj_cpf2   = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
								$ie          = trim($xml->NFe->infNFe->dest->IE);

								if(empty(trim($cnpj_cpf2))){
									$cnpj_cpf2   = strlen(trim($xml->NFe->infNFe->emit->CNPJ)) == 14 ? trim($xml->NFe->infNFe->emit->CNPJ) : trim($xml->NFe->infNFe->emit->CPF);
									$ie			 = trim($xml->NFe->infNFe->emit->IE);
								}
								
								$daonotasaitxt = new NotasSaiTxtDAO();
								$vetnotasaitxt = $daonotasaitxt->VerificaNota(trim($xml->NFe->infNFe->ide->nNF),$_SESSION['cnpj'],$_SESSION['cnpj'],$_SESSION['apura']['mesano']); 
								$numnotasaitxt = count($vetnotasaitxt);

								$itemserie = 0;
								if($numnotasaitxt > 0){
									$xmlnota= $vetnotasaitxt[0]->getXml();

									$doc2 = new DOMDocument();
									$doc2->loadXML($xmlnota);								
									$xml2 = simplexml_import_dom($doc2);
									$xml2 = $funcao->verificatagxml($xml2);
									$xserie = intval($xml2->NFe->infNFe->ide->serie);										
									$serienota = intval($xml->NFe->infNFe->ide->serie);
									
									if($xserie != $serienota){
										$itemserie = 0;
									}else{
										$itemserie = 1;
									}
									
								}

								if($numnotasaitxt == 0 or $itemserie == 0){
									
									$entsai_sai_mestre = "E";

									$notasaitxt = new NotasSaiTxt();
							
									$notasaitxt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
									$notasaitxt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
									$notasaitxt->setCnpjCpf($cnpj_cpf2);
									$notasaitxt->setValorTotalNota(trim($xml->NFe->infNFe->total->ICMSTot->vNF));
									$notasaitxt->setValorIcms(trim($xml->NFe->infNFe->total->ICMSTot->vICMS));
									$notasaitxt->setValorIcmsSubs(trim($xml->NFe->infNFe->total->ICMSTot->vST));
									$notasaitxt->setEntSai($entsai_sai_mestre);
									$notasaitxt->setInscEstadual(trim($ie));
									$notasaitxt->setCfop(trim($xml->NFe->infNFe->det->prod->CFOP));
									$notasaitxt->setCnpjEmp($_SESSION['cnpj']);
									$notasaitxt->setChave($xml->protNFe->infProt->chNFe);
									$notasaitxt->setXml($xmlstr);
									
									$daonotasaitxt->inserir($notasaitxt);

									$i = 0; 
									foreach($xml->NFe->infNFe->det as $prod){
										
										$entsai_sai = "E";
										
										$imposto    = json_encode($prod->imposto->ICMS);
										$arrayimp   = json_decode($imposto,TRUE);
										$icms       = array_pop($arrayimp);
										$vicms	    = !empty($icms['vICMS']) ? $icms['vICMS'] : 0.00;
										
										$notasa1txt = new NotasSa1Txt();
							
										$notasa1txt->setNumeroNota(trim($xml->NFe->infNFe->ide->nNF));
										$notasa1txt->setDataEmissao(date('Y-m-d',strtotime($dEmi[0])));
										$notasa1txt->setCnpjCpf($cnpj_cpf2);
										$notasa1txt->setCodigoProduto(trim($prod->prod->cProd));
										$notasa1txt->setQtdPecas(trim(0));
										$notasa1txt->setPeso(trim($prod->prod->qCom));
										$notasa1txt->setPrecoUnitario(trim($prod->prod->vUnCom));
										$notasa1txt->setEntSai($entsai_sai);
										$notasa1txt->setNumeroItemNota(($i+1));		
										$notasa1txt->setInscEstadual(trim($ie));
										$notasa1txt->setCfop(trim($xml->NFe->infNFe->det->prod->CFOP));
										$notasa1txt->setAliquotaIcms(0);
										$notasa1txt->setCnpjEmp($_SESSION['cnpj']);
										$notasa1txt->setProdQtd(trim($prod->prod->qCom));
										$notasa1txt->setVicms($vicms);
										
										$daonotasa1txt = new NotasSa1TxtDAO();
										
										$daonotasa1txt->inserir($notasa1txt);
										$i++;
										}
									}

									array_push($dados,array(
										'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'chave'=>''.$xml->protNFe->infProt->chNFe.'',
										'entsai'=>'SAIDA',
										'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
										'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
										'caminho'=>''.$arquivo.'',
										'dEmi'=>''.date('d/m/Y',strtotime($dEmi[0])).'',	
										'msg'=>''								
									));


						}

						if(trim($xml->NFe->infNFe->det->prod->CFOP) > 5000 and $vetcfop[0]['devolucao'] == 'N' AND trim($xml->NFe->infNFe->emit->CNPJ) != $_SESSION['cnpj']){
							//saida
							$dEmi         = explode('T',$xml->NFe->infNFe->ide->dhEmi);
							$cnpj_cpf2    = strlen(trim($xml->NFe->infNFe->dest->CNPJ)) == 14 ? trim($xml->NFe->infNFe->dest->CNPJ) : trim($xml->NFe->infNFe->dest->CPF);
							
							array_push($_SESSION['alerta_sessao'][$_SESSION['apura']['mesano']],array(								
								'cnota'=>''.trim($xml->NFe->infNFe->ide->nNF).'',
								'msg'=>' Documento emitido por terceiros em operação diferente de devolução. Nota: '.$xml->NFe->infNFe->ide->nNF.' CHAVE: '.$xml->protNFe->infProt->chNFe.' ',
								"dados"=> array(
										'nNF'=>''.$xml->NFe->infNFe->ide->nNF.'',
										'entsai'=>'Saida',
										'cliente'=>''.$xml->NFe->infNFe->dest->xNome.'',
										'valor'=>''.number_format(doubleval($xml->NFe->infNFe->total->ICMSTot->vNF),2,',','.').'',
										'demi'=>''.date('d/m/Y',strtotime($dEmi[0])).''
									),
								));	

						}						
					
				}
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
			}
			
			
			$data = array('msg'=>'Gravado com sucesso!');												
			echo json_encode($data);
				
				
		break;

		
		case 'verificanotaalteracao':

			$tipo   = $_REQUEST['tipo'];
			$numero = $_REQUEST['numero'];
			$cnpj   = $_SESSION['cnpj'];
			$res    = array();
			if($tipo == 'S'){
				$dao = new NotasSaiTxt2DAO();
				$vet = $dao->buscaidalteracao($numero,$cnpj);
				if(!empty($vet)){
					$daos = new NotasSa1Txt2DAO();
					$vets =	$daos->ListaNotasSa1DetalheNumero($cnpj,$vet[0]['numero_nota']);
					array_push($res,array(
						"id"=>"", 
						"numero_nota"=>"{$vet[0]['numero_nota']}",
						"data_emissao"=>"".date('d/m/Y',strtotime($vet[0]['data_emissao']))."",
						"cnpj_cpf"=>"{$vet[0]['cnpj_cpf']}",
						"valor_total_nota"=>"{$vet[0]['valor_total_nota']}",
						"valor_icms"=>"{$vet[0]['valor_icms']}",
						"valor_icms_subs"=>"{$vet[0]['valor_icms_subs']}",
						"ent_sai"=>"{$vet[0]['ent_sai']}", 
						"insc_estadual"=>"{$vet[0]['insc_estadual']}", 
						"cfop"=>"{$vets[0]['cfop']}",
						"nomecfop"=>"{$vets[0]['Nome']}",
						"razao"=>"{$vet[0]['razao']}",
						"idemp"=>"{$vet[0]['idemp']}",
						"tipo"=>"S",
						"det"=>$vets,
					));
				}

			}else if($tipo == 'E'){
				$dao = new NotasEntTxt2DAO();
				$vet = $dao->buscaidalteracao($numero,$cnpj);

				if(!empty($vet)){
					$daos = new NotasEn1Txt2DAO();
					$vets = $daos->ListaNotasDetalhe($cnpj,$numero);

					array_push($res,array(
						"id"=>"", 
						"numero_nota"=>"{$vet[0]['numero_nota']}",
						"data_emissao"=>"".date('d/m/Y',strtotime($vet[0]['data_emissao']))."",
						"data_abate"=>"".date('d/m/Y',strtotime($vets[0]['data_abate']))."",
						"cnpj_cpf"=>"{$vet[0]['cnpj_cpf']}",
						"tipo_v_r_a"=>"{$vets[0]['tipo_r_v']}",
						"valor_total_nota"=>"{$vet[0]['valor_total_nota']}",
						"gta"=>"{$vet[0]['gta']}",
						"numero_nota_produtor_ini"=>"{$vet[0]['numero_nota_produtor_ini']}",
						"numero_nota_produtor_fin"=>"{$vet[0]['numero_nota_produtor_fin']}", 
						"condenas"=>"{$vet[0]['condenas']}", 
						"abate"=>"{$vet[0]['abate']}",
						"insc_produtor"=>"{$vet[0]['insc_produtor']}",
						"cfop"=>"{$vets[0]['cfop']}",
						"nomecfop"=>"{$vets[0]['Nome']}",
						"razao"=>"{$vet[0]['razao']}",
						"idemp"=>"{$vet[0]['idemp']}",
						"tipo"=>"E",
						"det"=>$vets,																	
					));
				}

			}

			echo json_encode($res);

		break;
		case 'converte':
			/*$xmls 	 =  simplexml_load_file("../arquivos/43200315084124000170550010000062791891081729.xml");
		
			echo "<pre>";
			$prod		= $xmls->NFe->infNFe->det[0];			
			$prodx 		= json_encode($prod);
			$arrayprod  = json_decode($prodx,TRUE);
						
			print_r($arrayprod['prod']);*/

			$daoprodfrig   = new ProdutosTxt2DAO(); 
			$resuprod    = $daoprodfrig->ListaProdutosProprio($_SESSION['cnpj']);

	//		echo "<pre>";
		//	print_r($resuprod);

			$codigo_produto = "82";
			if(!empty($codigo_produto)){
				if(!empty($resuprod)){
					$keysp = array_search(trim($codigo_produto),array_column($resuprod, 'cod_prod'));
					if(trim($keysp) != ""){
						$desc_prod = $resuprod[$keysp]['desc_prod'];
					}else{
						$desc_prod = "";
					}
				}
			}else{
				$desc_prod = "";
			}

			echo $desc_prod;
		break;

		}
	}

?>
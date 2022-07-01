<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/relatoriorelacaobeneficioarecadacao.htm');
	//$tpl->assignInclude('conteudo','../tpl/relacaoabates.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		//require_once('../inc/inc.permissao.php');
		//require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$condicao  = array();
		$condicao2 = array();		
		$condicao3 = array();
		$condicao4 = array();
		$condicao5 = array();

		if(isset($_REQUEST['dataini']) and !empty($_REQUEST['dataini'])){

			$dataini       =  implode("-", array_reverse(explode("/", $_REQUEST['dataini'])));					
			$condicao[]    = " n.data_emissao between '".$dataini."' ";
			$condicao3[]   = " m.data_emissao between '".$dataini."' ";
			$condicao4[]   = " m.data_emissao between '".$dataini."' ";
			$condicao5[]   = " m.data_emissao between '".$dataini."' ";								
		}
	
		if(isset($_REQUEST['datafin']) and !empty($_REQUEST['datafin'])){

			$datafin      =  implode("-", array_reverse(explode("/", $_REQUEST['datafin'])));	
			$condicao[]   = " '".$datafin."' ";
			$condicao3[]  = " '".$datafin."' ";
			$condicao4[]  = " '".$datafin."' ";
			$condicao5[]  = " '".$datafin."' ";            			
		}
		
		if(isset($_REQUEST['cnpjemp']) and !empty($_REQUEST['cnpjemp'])){

			$cnpjemp      =  $_REQUEST['cnpjemp'];	
			$condicao2[]   = " e.cnpj = '".$cnpjemp."' ";            		
		}

		$condicao3[]  = " e.uf = 'RS' ";
		$condicao3[]  = " p.cod_secretaria < 10000 ";
		
		$condicao4[]  = " e.uf = 'RS' ";
		$condicao4[]  = " p.cod_secretaria >= 10000 ";

		$condicao5[]  = " e.uf <> 'RS' ";
		$condicao5[]  = " p.cod_secretaria < 10000 ";

		$where = '';
		if(count($condicao) > 0){		
			$where = ' where'.implode('AND',$condicao);				
		}

		$where2 = '';
		if(count($condicao2) > 0){		
			$where2 = ' where'.implode('AND',$condicao2);				
		}

		$where3 = '';
		if(count($condicao3) > 0){		
			$where3 = ' where'.implode('AND',$condicao3);				
		}

		$where4 = '';
		if(count($condicao4) > 0){		
			$where4 = ' where'.implode('AND',$condicao4);				
		}

		$where5 = '';
		if(count($condicao5) > 0){		
			$where5 = ' where'.implode('AND',$condicao5);				
		}

		$dao = new EmpresasDAO();
		$vet = $dao->ListaEmpresaParaBenificioArrecadacao($where2);
		$num = count($vet);

		for($i = 0; $i <  $num; $i++){

			$emp          = $vet[$i];
			$cnpj 		  = $emp->getCnpj();
			$razao_social = $emp->getRazaoSocial();			
			$cidade       = $emp->getCidade();

			$daonotaen1 = new NotasEn1TxtDAO();
			$vetnotaen1 = $daonotaen1->RelatorioRelacaoBeneficioArrecadacao($where,$cnpj);
			$numnotaen1 = count($vetnotaen1);
			$base		= 0;
			$credito	= 0;

			if($numnotaen1 > 0){

				$notasen1  = $vetnotaen1[0];
				$valor_total_rendimento = $notasen1->getValorTotalRendimento();
				$valor_total_vivo		= $notasen1->getValorTotalVivo();

				$base    = $valor_total_rendimento + $valor_total_vivo;
				$credito = (($base * 3.6) / 100);
			}

			$daonotasai = new NotasSaiTxtDAO();
			$vetnotasai = $daonotasai->RelatorioTotalVendasbeneficioArrecadacao($where3,$cnpj);
			$numnotasai = count($vetnotasai);
			$basers     = 0;
			$creditors  = 0;

			if($numnotasai > 0){
			
				$notasai = $vetnotasai[0];	
				
				$entrada = $notasai->getEntrada();
				$saida   = $notasai->getSaida();
				
				$basers    = $saida - $entrada;
				$creditors = (($basers * 3) / 100);
							
				
			}


			$vetnotasai2 = $daonotasai->RelatorioTotalVendasbeneficioArrecadacao($where4,$cnpj);
			$numnotasai2 = count($vetnotasai2);			
			$basers2     = 0;
			$creditors2  = 0;

			if($numnotasai2 > 0){
			
				$notasai2   = $vetnotasai2[0];	
				
				$entrada2   = $notasai2->getEntrada();
				$saida2     = $notasai2->getSaida();
				
				$basers2    = $saida2 - $entrada2;
				$creditors2 = (($basers2 * 4) / 100);
												
			}

			$vetnotasaioutro = $daonotasai->RelatorioTotalVendasbeneficioArrecadacao($where5,$cnpj);
			$numnotasaioutro = count($vetnotasaioutro);
			$basedifrs 		 = 0;
			$creditodifrs    = 0;
			$creditodifrs2   = 0;

			if($numnotasaioutro > 0){
			
				$notasaioutro   = $vetnotasaioutro[0];	
				
				$entrada_outros = $notasaioutro->getEntrada();
				$saida_outros   = $notasaioutro->getSaida();
				
				$basedifrs      = $saida_outros - $entrada_outros;
				$creditodifrs   = (($basedifrs * 3) / 100);
												
				$creditodifrs2 = (($basedifrs * 4) / 100);


			}


			$daog = new GuiaicmsDAO();
			$vetg = $daog->ListaGuiaicmsCompetencia($cnpj,date('m/Y',strtotime($datafin)));
			$numg = count($vetg);
			$valorimcs   = 0;
			$valorimcsst = 0;

			if($numg > 0){
				
				$guia 	   = $vetg[0];				
				$datapag   = implode("/", array_reverse(explode("-", "".$guia->getDataPago()."")));
				$tipo 	   = $guia->getTipo();
				$valorpago = $guia->getValorPago();

				if($tipo == 1){
					$valorimcs  = $valorpago;
				}else{
					$valorimcsst = $valorpago;
				}

			}


				$daof  		= new FolhaTxtDAO();
				$vetf  		= $daof->ListaFolhaEmpresasApurado($cnpj,date('m/Y',strtotime($datafin)));
				$numf  		= count($vetf);				
				$numfuncio  = 0;
				$valorfolha = 0;
				if($numf > 0){
				
					$folhatxt = $vetf[0];
					
					$numfuncio  = $folhatxt->getNumFuncionario();
					$valorfolha = $folhatxt->getValorFolha();
									
				}


			$tpl->newBlock('lista');

			$tpl->assign('cnpj',$cnpj);
			$tpl->assign('razao_social',$razao_social);
			$tpl->assign('cidade',$cidade);
			$tpl->assign('base',number_format($base,2,',','.'));
			$tpl->assign('credito',number_format($credito,2,',','.'));

			$tpl->assign('basers',number_format($basers,2,',','.'));
			$tpl->assign('creditors',number_format($creditors,2,',','.'));

			$tpl->assign('basers2',number_format($basers2,2,',','.'));
			$tpl->assign('creditors2',number_format($creditors2,2,',','.'));

			$tpl->assign('creditodifrs',number_format($creditodifrs,2,',','.'));
			$tpl->assign('creditodifrs2',number_format($creditodifrs2,2,',','.'));

			$tpl->assign('valorimcs',number_format($valorimcs,2,',','.'));
			$tpl->assign('valorimcsst',number_format($valorimcsst,2,',','.'));

			$tpl->assign('numfuncio',$numfuncio);
			$tpl->assign('valorfolha',number_format($valorfolha,2,',','.'));
			
		}



			



	/**************************************************************/
	$tpl->printToScreen();
		
?>
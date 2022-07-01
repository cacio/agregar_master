<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/competenciaenviadas.htm');
	$tpl->prepare();

	/**************************************************************/
		error_reporting(E_ALL);
		ini_set('display_errors', 'On');
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		//require_once('../inc/inc.permissao.php');
		$tpl->assign('log',$_SESSION['login']);
		
		if(!empty($_REQUEST['mesano'])){
		
			$mesano 	  = $_REQUEST['mesano'];  
			$cnpj         = $_SESSION['cnpj']; 
			
			$pathFile      = '../arquivos/'.$_SESSION['cnpj'].'/config.json';
			$configJson    = file_get_contents($pathFile);
			$installConfig = json_decode($configJson);

			$tpl->assign('mesano',$mesano);
			$tpl->assign('cnpj',$cnpj);
				
			$condicao     = array();
			$condicao3    = array();
			$condicao4    = array();
			$condicao5    = array();
			$condicao6    = array();

			$condicao[]   = " concat(LPAD(EXTRACT(MONTH FROM n.data_abate),2,'0'),'/',EXTRACT(YEAR FROM n.data_emissao)) between '".$mesano."' ";	
			$condicao[]   = " '".$mesano."' ";				
			$condicao[]   = " n.cnpj_emp = '".$cnpj."' ";
			$condicao[]   = " p.cnpj_emp = '".$cnpj."' ";
			$condicao[]   = " a.codigo in ('1','2','3','1001','1002','1003') ";
			$condicao[]   = " p.cod_secretaria < 10000 ";
			$condicao[]   = " p.cod_secretaria <> '99999' ";
			$condicao[]   = " n.cfop < 5000 ";
			
			$condicao7[]   = " concat(LPAD(EXTRACT(MONTH FROM n.data_abate),2,'0'),'/',EXTRACT(YEAR FROM n.data_abate)) between '".$mesano."' ";	
			$condicao7[]   = " '".$mesano."' ";	
			$condicao7[]   = " n.cnpj_emp = '".$cnpj."' ";
			$condicao7[]   = " p.cnpj_emp = '".$cnpj."' ";
			$condicao7[]   = " a.codigo in ('1','2','3','1001','1002','1003') ";
			$condicao7[]   = " p.cod_secretaria < 10000 ";
			$condicao7[]   = " p.cod_secretaria <> '99999' ";
			$condicao7[]   = " n.cfop > 5000 ";
				
			$condicao3[]  = " concat(LPAD(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao)) between '".$mesano."' ";
			$condicao3[]  = " '".$mesano."' ";	
			$condicao3[]  = " s.cnpj_emp = '".$cnpj."' ";		
			$condicao3[]  = " c.cnpj_emp = '".$cnpj."' ";
			$condicao3[]  = " e.cnpj_emp = '".$cnpj."' ";
			$condicao3[]  = " p.cnpj_emp = '".$cnpj."' ";
			$condicao3[]  = " e.uf = 'RS' ";
			$condicao3[]  = " p.cod_secretaria < 10000 ";
			$condicao3[]  = " p.cod_secretaria <> '99999' ";
			$condicao3[]  = " c.gera_agregar = '1' ";

			$condicao4[]  = " concat(LPAD(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao)) between '".$mesano."' ";
			$condicao4[]  = " '".$mesano."' ";
			$condicao4[]  = " s.cnpj_emp = '".$cnpj."' ";		
			$condicao4[]  = " c.cnpj_emp = '".$cnpj."' ";
			$condicao4[]  = " e.cnpj_emp = '".$cnpj."' ";
			$condicao4[]  = " p.cnpj_emp = '".$cnpj."' ";
			$condicao4[]  = " e.uf = 'RS' ";
			$condicao4[]  = " p.cod_secretaria >= 10000 ";
			$condicao4[]  = " p.cod_secretaria <> '99999' ";
			$condicao4[]  = " c.gera_agregar = '1' ";

			$condicao5[]  = " concat(LPAD(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao)) between '".$mesano."' ";
			$condicao5[]  = " '".$mesano."' ";
			$condicao5[]  = " s.cnpj_emp = '".$cnpj."' ";		
			$condicao5[]  = " c.cnpj_emp = '".$cnpj."' ";
			$condicao5[]  = " e.cnpj_emp = '".$cnpj."' ";
			$condicao5[]  = " p.cnpj_emp = '".$cnpj."' ";
			$condicao5[]  = " e.uf <> 'RS' ";
			$condicao5[]  = " p.cod_secretaria < 10000 ";
			$condicao5[]  = " p.cod_secretaria <> '99999' ";
			$condicao5[]  = " c.gera_agregar = '1' ";

			$condicao6[]  = " concat(LPAD(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao)) between '".$mesano."' ";
			$condicao6[]  = " '".$mesano."' ";
			$condicao6[]  = " s.cnpj_emp = '".$_SESSION['cnpj']."' ";		
			$condicao6[]  = " c.cnpj_emp = '".$_SESSION['cnpj']."' ";
			$condicao6[]  = " e.cnpj_emp = '".$_SESSION['cnpj']."' ";
			$condicao6[]  = " p.cnpj_emp = '".$_SESSION['cnpj']."' ";
			$condicao6[]  = " e.uf <> 'RS' ";
			$condicao6[]  = " p.cod_secretaria > 10000 ";
			$condicao6[]  = " p.cod_secretaria <> '99999' ";
			$condicao6[]  = " c.gera_agregar = '1' ";

			$where 		  = '';
			if(count($condicao) > 0){		
				$where = ' where'.implode('AND',$condicao);				
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

			$where6 = '';
			if(count($condicao6) > 0){		
				$where6 = ' where'.implode('AND',$condicao6);				
			}
			
			$where7 = '';
			if(count($condicao7) > 0){		
				$where7 = ' where'.implode('AND',$condicao7);				
			}
			
			$daoresm = new ResumoDAO();
			$vetresm = $daoresm->ValidaMesAnoCompetencia($mesano,$cnpj);
			$numresm = count($vetresm);	
			
			if($numresm > 0){
				
				$tpl->assign('hide','');
				## Contando quantos animais abatidos	
				$daonotasen1 = new NotasEn1TxtDAO();
				$vetnotasen1 = $daonotasen1->ListagemTotalDeAnimaisAbatidos($where); 
				$numnotasen1 = count($vetnotasen1);
				$animais     = array();

				if($numnotasen1 > 0){
					
					$notasen1 = $vetnotasen1[0];
					
					$bovinos   = $notasen1->getBovinos();
					$bubalinos = $notasen1->getBubalinos();
					$ovinos	   = $notasen1->getOvinos();									
					
					$tpl->assign('bovinos',$bovinos);
					$tpl->assign('bubalinos',$bubalinos);
					$tpl->assign('ovinos',$ovinos);				
					
				}else{

					$bovinos   = 0;
					$bubalinos = 0;
					$ovinos	   = 0;
					
					$tpl->assign('bovinos',$bovinos);
					$tpl->assign('bubalinos',$bubalinos);
					$tpl->assign('ovinos',$ovinos);
					
				}
				## termino da contagem de animais abatidos
				
				## Total de produtores	
				if(!empty($installConfig->apuracao)){
				if($installConfig->apuracao == '2'){
					$vetprodutor = $daonotasen1->TotalDeProdutoresPorNota($where);
					$numprodutor = count($vetprodutor);
					$basecredito = array();

					if($numprodutor > 0){
					
						$notasen12 = $vetprodutor[0];
						
						$vivo 		= $notasen12->getValorTotalNota();
												
						$export = new ExportacaoDAO();
						$vetexp = $export->ComputaCompetenciaExportacao($mesano,$_SESSION['cnpj']);
						$numexp = count($vetexp);

						if($numexp > 0){
							$expo		= $vetexp[0];
							$valor_glos = $expo->getValorGlosado();
						}else{
							$valor_glos = 0;
						}

						$base       = ($vivo - $valor_glos);
						$credito    = (($base * 3.6) / 100);
						
						$tpl->assign('base',number_format($base,2,',','.'));
						$tpl->assign('credito',number_format($credito,2,',','.'));
						
					}

				}else{
					$vetprodutor = $daonotasen1->TotalDeProdutores($where);
					$numprodutor = count($vetprodutor);
					$basecredito = array();

					if($numprodutor > 0){
					
						$notasen12 = $vetprodutor[0];
						
						$vivo 		= $notasen12->getVivo();
						$rendimento = $notasen12->getRendimento();
						
						$export = new ExportacaoDAO();
						$vetexp = $export->ComputaCompetenciaExportacao($mesano,$_SESSION['cnpj']);
						$numexp = count($vetexp);

						if($numexp > 0){
							$expo		= $vetexp[0];
							$valor_glos = $expo->getValorGlosado();
						}else{
							$valor_glos = 0;
						}
						
						// dedução das compras
						$vetprodutordeducao = $daonotasen1->TotalDeProdutores($where7);
						$numprodutordeducao = count($vetprodutor);	
						if($numprodutordeducao > 0){
							
							$notasen12d	   = $vetprodutordeducao[0];
						
							$vivodef 	   = $notasen12d->getVivo();
							$rendimentodef = $notasen12d->getRendimento();	

							$basedef       = (($vivodef + $rendimentodef));

						}else{
							$basedef       = 0;
						}

						//$base       = (($vivo + $rendimento) - $valor_glos);
						$base       = ((($vivo + $rendimento) - $basedef) - $valor_glos);
						$credito    = (($base * 3.6) / 100);
								
						$tpl->assign('base',number_format($base,2,',','.'));
						$tpl->assign('credito',number_format($credito,2,',','.'));
					}	
				}
			}else{

					$vetprodutor = $daonotasen1->TotalDeProdutores($where);
					$numprodutor = count($vetprodutor);
					$basecredito = array();

					if($numprodutor > 0){
					
						$notasen12 = $vetprodutor[0];
						
						$vivo 		= $notasen12->getVivo();
						$rendimento = $notasen12->getRendimento();
						
						$export = new ExportacaoDAO();
						$vetexp = $export->ComputaCompetenciaExportacao($mesano,$_SESSION['cnpj']);
						$numexp = count($vetexp);

						if($numexp > 0){
							$expo		= $vetexp[0];
							$valor_glos = $expo->getValorGlosado();
						}else{
							$valor_glos = 0;
						}

						$base       = (($vivo + $rendimento) - $valor_glos);
						$credito    = (($base * 3.6) / 100);
								
						$tpl->assign('base',number_format($base,2,',','.'));
						$tpl->assign('credito',number_format($credito,2,',','.'));
					}

			}
				## Termino de total de produtores
				
				
				## vendas RS
				$daonotasai = new NotasSaiTxtDAO();
				$vetnotasai = $daonotasai->TotalVendasRS($where3,$_SESSION['cnpj'],$mesano,'3');
				$numnotasai = count($vetnotasai);
				$vendars    = array();

				if($numnotasai > 0){
				
					$notasai = $vetnotasai[0];	
					
					$entrada = $notasai->getEntrada();
					$saida   = $notasai->getSaida();
					
					$basers    = $saida - $entrada;
					$creditors = (($basers * 3) / 100);
							
					$tpl->assign('basers',number_format($basers,2,',','.'));
					$tpl->assign('creditors',number_format($creditors,2,',','.'));
				}
				
				$vetnotasai2 = $daonotasai->TotalVendasRS($where4,$_SESSION['cnpj'],$mesano,'4');
				$numnotasai2 = count($vetnotasai2);
				$vendars2    = array();

				if($numnotasai2 > 0){
				
					$notasai2   = $vetnotasai2[0];	
					
					$entrada2   = $notasai2->getEntrada();
					$saida2     = $notasai2->getSaida();
					
					$basers2    = $saida2 - $entrada2;
					$creditors2 = (($basers2 * 4) / 100);
							
					$tpl->assign('basers2',number_format($basers2,2,',','.'));
					$tpl->assign('creditors2',number_format($creditors2,2,',','.'));
				}
				
				$vetnotasaioutro = $daonotasai->TotalVendasRS($where5,$_SESSION['cnpj'],$mesano,'outro3');
				$numnotasaioutro = count($vetnotasaioutro);
				$vendasdifrs     = array();

				if($numnotasaioutro > 0){
				
					$notasaioutro   = $vetnotasaioutro[0];	
					
					$entrada_outros = $notasaioutro->getEntrada();
					$saida_outros   = $notasaioutro->getSaida();
					
					$basedifrs      = $saida_outros - $entrada_outros;
					$creditodifrs   = (($basedifrs * 3) / 100);
							
					$tpl->assign('basedifrs',number_format($basedifrs,2,',','.'));
					$tpl->assign('creditodifrs',number_format($creditodifrs,2,',','.'));
				}
				
				$vetnotasaioutro2 = $daonotasai->TotalVendasRS($where6,$_SESSION['cnpj'],$mesano,'outro4');
				$numnotasaioutro2 = count($vetnotasaioutro2);
				$vendasdifrs2     = array();

				if($numnotasaioutro2 > 0){
				
					$notasaioutro2 = $vetnotasaioutro2[0];	
					
					$entrada_outros2 = $notasaioutro2->getEntrada();
					$saida_outros2   = $notasaioutro2->getSaida();
					
					$basedifrs2    = $saida_outros2 - $entrada_outros2;
					$creditodifrs2 = (($basedifrs2 * 4) / 100);
								
					$tpl->assign('basedifrs2',number_format($basedifrs2,2,',','.'));
					$tpl->assign('creditodifrs2',number_format($creditodifrs2,2,',','.'));
				}

				$total_geral_base    = $base + $basers + $basers2 + $basedifrs + $basedifrs2;
				$total_geral_credito = $credito + $creditors + $creditors2 + $creditodifrs + $creditodifrs2;
				$tpl->assign('total_geral_base',number_format($total_geral_base,2,',','.'));
				$tpl->assign('total_geral_credito',number_format($total_geral_credito,2,',','.'));
				## Fim vendas RS
				
				## folha
				$daof  = new FolhaTxtDAO();
				$vetf  = $daof->ListaFolhaEmpresasApurado($cnpj,$mesano);
				$numf  = count($vetf);
				$folha = array();

				if($numf > 0){
				
					$folhatxt = $vetf[0];
					
					$numfuncio  = $folhatxt->getNumFuncionario();
					$valorfolha = $folhatxt->getValorFolha();
					
					array_push($folha, array(
						'numfuncio'=>''.$numfuncio.'',
						'valorfolha'=>''.$valorfolha.''
					));
					$tpl->assign('numfuncio',$numfuncio);
					$tpl->assign('valorfolha',$valorfolha);
				}else{
					$numfuncio  = 0;
					$valorfolha = 0;
					$tpl->assign('numfuncio',$numfuncio);
					$tpl->assign('valorfolha',$valorfolha);
				}
				## fim folha
				
				## GUIA
				$daog = new GuiaicmsDAO();
				$vetg = $daog->ListaGuiaicmsCompetencia($cnpj,$mesano);
				$numg = count($vetg);
				
				if($numg > 0){
					
					$guia 	   = $vetg[0];				
					$datapag   = implode("/", array_reverse(explode("-", "".$guia->getDataPago()."")));
					$tpl->assign('datapag',$datapag);
				}else{
					$datapag   = "";
					$tpl->assign('datapag',$datapag);
				}
				## FIM GUIA
				
				## Notas Saida Competencia
				$vets = $daonotasai->ListandoNotasSaiCompetancia($cnpj,$mesano);
				$nums = count($vets);
				
				if($nums > 0 ){
					$notasaitxt = $vets[0];
					
					$valoricms	    = $notasaitxt->getValorIcms();
					$valoricmssubs	= $notasaitxt->getValorIcmsSubs();
					$tpl->assign('valoricms',$valoricms);
					$tpl->assign('valoricmssubs',$valoricmssubs);			
				}else{
					$valoricms	    = 0;
					$valoricmssubs	= 0;
					$tpl->assign('valoricms',$valoricms);
					$tpl->assign('valoricmssubs',$valoricmssubs);
				}
				## Fim Notas Saida Competencia
				
				## pegando numero de entradas inseridas
				$daoent        = new NotasEntTxtDAO();
				$vetnumentrada = $daoent->NumeroDeEntradas($mesano,$cnpj);	
				$numentrada    = count($vetnumentrada);

				if($numentrada > 0){

					$notasentrada = $vetnumentrada[0];					
					$num_entrada  = $notasentrada->getNumeroEntrada();
					$tpl->assign('num_entrada',$num_entrada);
				}else{
					$num_entrada  = 0;
					$tpl->assign('num_entrada',$num_entrada);	
				}
				## FIM pegando numero de entradas inseridas
				
				## pegando numero de saidas inseridas
				$vetnumsai = $daonotasai->NumeroDeSaidas($mesano,$cnpj);
				$numsai    = count($vetnumsai);

				if($numsai > 0){

					$notassai  = $vetnumsai[0];
					$num_Saida = $notassai->getNumeroSaida();
					$tpl->assign('num_Saida',$num_Saida);
				}else{
					$num_Saida = 0;
					$tpl->assign('num_Saida',$num_Saida);
				}
				
				$daores = new ResumoDAO();
				$vetres = $daores->ValidaMesAnoCompetencia($mesano,$cnpj);	
				$numres = count($vetres);
				$prot   = array();

				if($numres >0){
					$resu = $vetres[0];

					$codres    = $resu->getCodigo();			
					$xstatus   = $resu->getStatus();
					$protocolo = $resu->getProtocolo();

					$tpl->assign('codres',$codres);
					$tpl->assign('xstatus',$xstatus);
					$tpl->assign('protocolo',$protocolo);
				}
				
				$daostatus = new StatusDAO();
				$vetstatus = $daostatus->ListaStatus();
				$numstatus = count($vetstatus);
				$status    = array();
				for($st = 0; $st < $numstatus; $st++){
					$stat = $vetstatus[$st];

					$codstatus  = $stat->getCodigo();
					$nomestatus = $stat->getNome();
					$sel        = "";
					if($codstatus == $xstatus){
						$sel = "selected";
					}
					
					$tpl->newBlock('listar');
					
					$tpl->assign('codstatus',$codstatus);
					$tpl->assign('nomestatus',$nomestatus);
					$tpl->assign('sel',$sel);					
				}

				## Pegando dados de exportação
				$exports   = new ExportacaoDAO();
				$vetexps   = $exports->ListaComputaCompetenciaExportacao($mesano,$_SESSION['cnpj']);
				$numexps   = count($vetexps);					

				if($numexps > 0){

					for ($i=0; $i < $numexps; $i++) { 
						$expos = $vetexps[$i];

						$valor_glos = $expos->getValorGlosado();
						$nome_pt    = $expos->getPais();
												
						$tpl->newBlock('listexp');

						$tpl->assign('nome_pt',$nome_pt);
						$tpl->assign('valor',number_format($valor_glos,2,',','.'));

					}

				}
				## Termino de dados de exportação
				
			}else{
				$tpl->assign('hide','hide');
				$tpl->newBlock('nada');
				$tpl->assign('msg','Competencia não computada, rever suas competencias em andamento!');
			}
		}else{
			header('Location:../php/admin.php');
		}
	/**************************************************************/

	$tpl->printToScreen();

?>
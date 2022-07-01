<?php
	use Dompdf\Dompdf;
	use Dompdf\Options;
	session_start();
	require_once('../inc/inc.autoload.php');
	
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

			case 'apurar':
				//error_reporting(E_ALL);
				//ini_set('display_errors', 'On');
				$mesano 	  = !empty($_SESSION['apura']['mesano']) ? $_SESSION['apura']['mesano'] :  date('m/Y');  

				$pathFile      = '../arquivos/'.$_SESSION['cnpj'].'/config.json';
				$configJson    = file_get_contents($pathFile);
				$installConfig = json_decode($configJson);

				$condicao     = array();
				$condicao3    = array();
				$condicao4    = array();
				$condicao5    = array();
				$condicao6    = array();
				$condicao7    = array();
				$condicao8    = array();

				$condicao[]   = " concat(LPAD(EXTRACT(MONTH FROM n.data_abate),2,'0'),'/',EXTRACT(YEAR FROM n.data_abate)) between '".$mesano."' ";	
				$condicao[]   = " '".$mesano."' ";	
				$condicao[]   = " n.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao[]   = " p.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao[]   = " a.codigo in ('1','2','3','1001','1002','1003') ";
				$condicao[]   = " COALESCE(p.cod_secretaria ,0) < 10000 ";
				$condicao[]   = " COALESCE(p.cod_secretaria ,0) <> '99999' ";
				$condicao[]   = " n.cfop < 5000 ";
				
				$condicao8[]   = " concat(LPAD(EXTRACT(MONTH FROM n.data_abate),2,'0'),'/',EXTRACT(YEAR FROM n.data_abate)) between '".$mesano."' ";	
				$condicao8[]   = " '".$mesano."' ";	
				$condicao8[]   = " n.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao8[]   = " p.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao8[]   = " a.codigo in ('1','2','3','1001','1002','1003') ";
				//$condicao8[]   = " p.cod_secretaria < 10000 ";
				//$condicao8[]   = " p.cod_secretaria <> '99999' ";
				$condicao8[]   = " (SELECT c.devolucao from cfop c WHERE c.codigo =n.cfop)  = 'S' ";
				
				$condicao3[]  = " concat(LPAD(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao)) between '".$mesano."' ";
				$condicao3[]  = " '".$mesano."' ";	
				$condicao3[]  = " s.cnpj_emp = '".$_SESSION['cnpj']."' ";		
				$condicao3[]  = " c.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao3[]  = " e.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao3[]  = " p.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao3[]  = " e.uf = 'RS' ";
				$condicao3[]  = " COALESCE(p.cod_secretaria ,0) < 10000 ";
				$condicao3[]  = " COALESCE(p.cod_secretaria ,0) <> '99999' ";
				$condicao3[]  = " c.gera_agregar = '1' ";
				$condicao3[]  = " cc.devolucao <> 'S' ";
				$condicao3[]  = " (SELECT n.valor_icms FROM notassaitxt n WHERE   n.cnpj_emp = s.cnpj_emp AND n.numero_nota = s.numero_nota and concat(LPAD(EXTRACT(MONTH FROM n.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM n.data_emissao)) = '".$mesano."' limit 1)  IS NOT NULL ";

				$condicao4[]  = " concat(LPAD(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao)) between '".$mesano."' ";
				$condicao4[]  = " '".$mesano."' ";
				$condicao4[]  = " s.cnpj_emp = '".$_SESSION['cnpj']."' ";		
				$condicao4[]  = " c.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao4[]  = " e.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao4[]  = " p.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao4[]  = " e.uf = 'RS' ";
				$condicao4[]  = " COALESCE(p.cod_secretaria ,0) >= 10000 ";
				$condicao4[]  = " COALESCE(p.cod_secretaria ,0) <> '99999' ";
				$condicao4[]  = " c.gera_agregar = '1' ";
				$condicao4[]  = " cc.devolucao <> 'S' ";
				$condicao4[]  = " (SELECT n.valor_icms FROM notassaitxt n WHERE   n.cnpj_emp = s.cnpj_emp AND n.numero_nota = s.numero_nota and concat(LPAD(EXTRACT(MONTH FROM n.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM n.data_emissao)) = '".$mesano."' limit 1)  IS NOT NULL ";
				
				$condicao5[]  = " concat(LPAD(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao)) between '".$mesano."' ";
				$condicao5[]  = " '".$mesano."' ";
				$condicao5[]  = " s.cnpj_emp = '".$_SESSION['cnpj']."' ";		
				$condicao5[]  = " c.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao5[]  = " e.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao5[]  = " p.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao5[]  = " e.uf <> 'RS' ";
				$condicao5[]  = " COALESCE(p.cod_secretaria ,0) < 10000 ";
				$condicao5[]  = " COALESCE(p.cod_secretaria ,0) <> '99999' ";
				$condicao5[]  = " c.gera_agregar = '1' ";
				$condicao5[]  = " cc.devolucao <> 'S' ";
				$condicao5[]  = " (SELECT n.valor_icms FROM notassaitxt n WHERE   n.cnpj_emp = s.cnpj_emp AND n.numero_nota = s.numero_nota and concat(LPAD(EXTRACT(MONTH FROM n.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM n.data_emissao)) = '".$mesano."' limit 1)  IS NOT NULL ";
				
				$condicao6[]  = " concat(LPAD(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao)) between '".$mesano."' ";
				$condicao6[]  = " '".$mesano."' ";
				$condicao6[]  = " s.cnpj_emp = '".$_SESSION['cnpj']."' ";		
				$condicao6[]  = " c.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao6[]  = " e.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao6[]  = " p.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao6[]  = " e.uf <> 'RS' ";
				$condicao6[]  = " COALESCE(p.cod_secretaria ,0) > 10000 ";
				$condicao6[]  = " COALESCE(p.cod_secretaria ,0) <> '99999' ";
				$condicao6[]  = " c.gera_agregar = '1' ";
				$condicao6[]  = " cc.devolucao <> 'S' ";
				$condicao6[]  = " (SELECT n.valor_icms FROM notassaitxt n WHERE   n.cnpj_emp = s.cnpj_emp AND n.numero_nota = s.numero_nota and concat(LPAD(EXTRACT(MONTH FROM n.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM n.data_emissao)) = '".$mesano."' limit 1)  IS NOT NULL ";
				
				$condicao7[]  = " CONCAT(LPAD(EXTRACT(MONTH FROM s.data_emissao), 2, '0'), '/', EXTRACT(YEAR FROM s.data_emissao)) = '".$mesano."' ";
				$condicao7[]  = " s.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao7[]  = " COALESCE(p.cod_secretaria ,0) <> '99999' ";
				$condicao7[]  = " (s.insc_estadual = e.insc_estadual) ";
				$condicao7[]  = "  cf.devolucao = 'S' ";

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
				
				$where8 = '';
				if(count($condicao8) > 0){		
					$where8 = ' where'.implode('AND',$condicao8);				
				}

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
					
					array_push($animais, array(
						'bovinos'=>''.$bovinos.'',
						'bubalinos'=>''.$bubalinos.'',
						'ovinos'=>''.$ovinos.'',	
					));					
					
				}else{

					$bovinos   = 0;
					$bubalinos = 0;
					$ovinos	   = 0;
					
					array_push($animais, array(
						'bovinos'=>''.$bovinos.'',
						'bubalinos'=>''.$bubalinos.'',
						'ovinos'=>''.$ovinos.'',	
					));
					
				}
				## termino da contagem de animais abatidos
				
    			
				## Total de produtores	

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

						$base         = (($vivo - $valor_glos));
						$credito      = (($base * 3.6) / 100);
						$valorentrada = $vivo;

						array_push($basecredito, array(
							'base'=>$base,
							'credito'=>$credito,
							'devolucao'=>0,
							'valorentrada'=>$valorentrada,
						));	
						
					}else{
						$base = 0;
						$credito = 0;
						$valorentrada = 0;
						array_push($basecredito, array(
							'base'=>$base,
							'credito'=>$credito,
							'devolucao'=>0,
							'valorentrada'=>$valorentrada,
						));
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
						$vetprodutordeducao = $daonotasen1->TotalDeProdutores($where8);
						$numprodutordeducao = count($vetprodutor);	
						if($numprodutordeducao > 0){
							
							$notasen12d	   = $vetprodutordeducao[0];
						
							$vivodef 	   = !empty($notasen12d->getVivo()) ? $notasen12d->getVivo() : 0;
							$rendimentodef = !empty($notasen12d->getRendimento()) ? $notasen12d->getRendimento() : 0;	

							$basedef       = (($vivodef + $rendimentodef));
							if(empty($basedef)){
								$basedef = 0;
							}

						}else{
							$basedef       = 0;
						}
						
						$base         = (((($vivo + $rendimento) - $basedef) - $valor_glos));
						$credito      = (($base * 3.6) / 100);
						$valorentrada = ($vivo + $rendimento);

						array_push($basecredito, array(
							'base'=>$base,
							'credito'=>$credito,
							'devolucao'=>$basedef,
							'valorentrada'=>$valorentrada,	
						));	
						
					}else{
						$base = 0;
						$credito = 0;
						$basedef = 0;
						$valorentrada = 0;
						
						array_push($basecredito, array(
							'base'=>$base,
							'credito'=>$credito,
							'devolucao'=>$basedef,
							'valorentrada'=>$valorentrada,	
						));	
					}	
				}
				## Termino de total de produtores

				## Pegando dados de exportação
				$exports   = new ExportacaoDAO();
				$vetexps   = $exports->ListaComputaCompetenciaExportacao($mesano,$_SESSION['cnpj']);
				$numexps   = count($vetexps);	
				$resutexpo = array();

				if($numexps > 0){

					for ($i=0; $i < $numexps; $i++) { 
						$expos = $vetexps[$i];

						$valor_glos = $expos->getValorGlosado();
						$nome_pt    = $expos->getPais();
						
						array_push($resutexpo,array(
							'nome'=>"{$nome_pt}",
							'valor'=>number_format($valor_glos,2,',','.'),
						));

					}

				}
				## Termino de dados de exportação
				
				## vendas RS
				$daonotasai = new NotasSaiTxtDAO();
				$vetnotasai = $daonotasai->TotalVendasRSXml($where3,$_SESSION['cnpj'],$mesano,'3');
				$numnotasai = count($vetnotasai);
				$vendars    = array();

				if($numnotasai > 0){
				
					$notasai = $vetnotasai[0];	
					
					$entrada = $notasai->getEntrada();
					$saida   = $notasai->getSaida();
					
					$basers    = $saida - $entrada;
					$creditors = (($basers * 3) / 100);
					
					$daonotassa1 = new NotasSaiTxt2DAO();
					$vetnotassa1 = $daonotassa1->getDevolucaoVendas($_SESSION['cnpj'],$mesano,'3');
					
					if($vetnotassa1){
						$valordev = $vetnotassa1[0]['valor'];					
					}else{
						$valordev = 0;
					}
					
					array_push($vendars, array(
						'basers'=>$basers,
						'creditors'=>$creditors,
						'saida'=>$saida,
						'devolucao'=>($entrada + $valordev),
						'devolucao2'=>$valordev
					));
					
				}else{
					$basers = 0;
					$creditors = 0;
					$saida = 0;
					$valordev = 0;
					array_push($vendars, array(
						'basers'=>$basers,
						'creditors'=>$creditors,
						'saida'=>$saida,
						'devolucao'=>0,
						'devolucao2'=>$valordev
					));
				}
				
				$vetnotasai2 = $daonotasai->TotalVendasRSXml($where4,$_SESSION['cnpj'],$mesano,'4');
				$numnotasai2 = count($vetnotasai2);
				$vendars2    = array();

				if($numnotasai2 > 0){
				
					$notasai2   = $vetnotasai2[0];	
					
					$entrada2   = $notasai2->getEntrada();
					$saida2     = $notasai2->getSaida();
					
					$basers2    = $saida2 - $entrada2;
					$creditors2 = (($basers2 * 4) / 100);
					
					$daonotassa2 = new NotasSaiTxt2DAO();
					$vetnotassa2 = $daonotassa2->getDevolucaoVendas($_SESSION['cnpj'],$mesano,'4');
					
					if($vetnotassa2){
						$valordev2 = $vetnotassa2[0]['valor'];					
					}else{
						$valordev2 = 0;
					}
					
					array_push($vendars2, array(
						'basers2'=>$basers2,
						'creditors2'=>$creditors2,
						'saida'=>$saida2,
						'devolucao'=>($entrada2 + $valordev2),
						'devolucao2'=>$valordev2
					));
					
				}else{
					$basers2 = 0;
					$creditors2 = 0;
					$saida2 = 0;
					$valordev2 = 0;
					array_push($vendars2, array(
						'basers2'=>$basers2,
						'creditors2'=>$creditors2,
						'saida'=>$saida2,
						'devolucao'=>0,
						'devolucao2'=>$valordev2
					));
				}

				$vetnotasaioutro = $daonotasai->TotalVendasRSXml($where5,$_SESSION['cnpj'],$mesano,'outro3');
				$numnotasaioutro = count($vetnotasaioutro);
				$vendasdifrs     = array();

				if($numnotasaioutro > 0){
				
					$notasaioutro   = $vetnotasaioutro[0];	
					
					$entrada_outros = $notasaioutro->getEntrada();
					$saida_outros   = $notasaioutro->getSaida();
					
					$basedifrs      = $saida_outros - $entrada_outros;
					$creditodifrs   = (($basedifrs * 3) / 100);
					
					$daonotassa3 = new NotasSaiTxt2DAO();
					$vetnotassa3 = $daonotassa3->getDevolucaoVendas($_SESSION['cnpj'],$mesano,'outro3');
					
					if($vetnotassa3){
						$valordev3 = $vetnotassa3[0]['valor'];					
					}else{
						$valordev3 = 0;
					}
					
					array_push($vendasdifrs, array(
						'basedifrs'=>$basedifrs,
						'creditodifrs'=>$creditodifrs,
						'saida'=>$saida_outros,
						'devolucao'=>($entrada_outros + $valordev3),
						'devolucao2'=>$valordev3
					));
					
				}else{
					$basedifrs = 0;
					$creditodifrs = 0;
					$saida_outros = 0;
					$valordev3 = 0;
					array_push($vendasdifrs, array(
						'basedifrs'=>$basedifrs,
						'creditodifrs'=>$creditodifrs,
						'saida'=>$saida_outros,
						'devolucao'=>0,
						'devolucao2'=>$valordev3
					));
				}

				$vetnotasaioutro2 = $daonotasai->TotalVendasRSXml($where6,$_SESSION['cnpj'],$mesano,'outro4');
				$numnotasaioutro2 = count($vetnotasaioutro2);
				$vendasdifrs2     = array();

				if($numnotasaioutro2 > 0){
				
					$notasaioutro2 = $vetnotasaioutro2[0];	
					
					$entrada_outros2 = $notasaioutro2->getEntrada();
					$saida_outros2   = $notasaioutro2->getSaida();
					
					$basedifrs2    = $saida_outros2 - $entrada_outros2;
					$creditodifrs2 = (($basedifrs2 * 4) / 100);
					
					$daonotassa4 = new NotasSaiTxt2DAO();
					$vetnotassa4 = $daonotassa4->getDevolucaoVendas($_SESSION['cnpj'],$mesano,'outro4');
					
					if($vetnotassa4){
						$valordev4 = $vetnotassa4[0]['valor'];					
					}else{
						$valordev4 = 0;
					}
					
					array_push($vendasdifrs2, array(
						'basedifrs2'=>$basedifrs2,
						'creditodifrs2'=>$creditodifrs2,
						'saida'=>$saida_outros2,
						'devolucao'=>($entrada_outros2 + $valordev4),
						'devolucao2'=>$valordev4
					));
					
				}else{
					$basedifrs2 = 0;
					$creditodifrs2 = 0;
					$saida_outros2 = 0;
					$valordev4 = 0;
					array_push($vendasdifrs2, array(
						'basedifrs2'=>$basedifrs2,
						'creditodifrs2'=>$creditodifrs2,
						'saida'=>$saida_outros2,
						'devolucao'=>0,
						'devolucao2'=>$valordev4
					));
				}

				$total_geral_base    = $base + $basers + $basers2 + $basedifrs + $basedifrs2;
				$total_geral_credito = $credito + $creditors + $creditors2 + $creditodifrs + $creditodifrs2;

				## Fim vendas RS


				## folha
				$daof  = new FolhaTxtDAO();
				$vetf  = $daof->ListaFolhaEmpresasApurado($_SESSION['cnpj'],$mesano);
				$numf  = count($vetf);
				$folha = array();

				if($numf > 0){
				
					$folhatxt = $vetf[0];
					
					$numfuncio  = $folhatxt->getNumFuncionario();
					$valorfolha = $folhatxt->getValorFolha();
					
					if(empty($numfuncio)){
						$numfuncio = 0;
					}
					
					if(empty($valorfolha)){
						$valorfolha = 0;
					}
					array_push($folha, array(
						'numfuncio'=>''.$numfuncio.'',
						'valorfolha'=>''.$valorfolha.''
					));

				}else{
					$numfuncio  = 0;
					$valorfolha = 0;
					array_push($folha, array(
						'numfuncio'=>''.$numfuncio.'',
						'valorfolha'=>''.$valorfolha.''
					));
				}
				## fim folha
 				
 				## GUIA
				$daog = new GuiaicmsDAO();
				$vetg = $daog->ListaGuiaicmsCompetencia($_SESSION['cnpj'],$mesano);
				$numg = count($vetg);
				
				if($numg > 0){
					
					$guia 	   = $vetg[0];				
					$datapag   = implode("/", array_reverse(explode("-", "".$guia->getDataPago()."")));
					
				}else{
					$datapag   = "";
				}

				## FIM GUIA

				## Notas Saida Competencia
				$vets = $daonotasai->ListandoNotasSaiCompetancia($_SESSION['cnpj'],$mesano);
				$nums = count($vets);
				
				if($nums > 0 ){
					$notasaitxt = $vets[0];
					
					$valoricms	    = !empty($notasaitxt->getValorIcms()) ? $notasaitxt->getValorIcms() :0;
					$valoricmssubs	= !empty($notasaitxt->getValorIcmsSubs()) ? $notasaitxt->getValorIcmsSubs() :0;				
				}else{
					$valoricms	    = 0;
					$valoricmssubs	= 0;
				}
				## Fim Notas Saida Competencia

				## pegando numero de entradas inseridas
				$daoent        = new NotasEntTxtDAO();
				$vetnumentrada = $daoent->NumeroDeEntradas($mesano,$_SESSION['cnpj']);	
				$numentrada    = count($vetnumentrada);

				if($numentrada > 0){

					$notasentrada = $vetnumentrada[0];					
					$num_entrada  = $notasentrada->getNumeroEntrada();
				}else{
					$num_entrada  = 0;	
				}
				## FIM pegando numero de entradas inseridas

				## pegando numero de saidas inseridas
				$vetnumsai = $daonotasai->NumeroDeSaidas($mesano,$_SESSION['cnpj']);
				$numsai    = count($vetnumsai);

				if($numsai > 0){

					$notassai  = $vetnumsai[0];
					$num_Saida = $notassai->getNumeroSaida();
				}else{
					$num_Saida = 0;
				}


				$daoprot = new ProtocoloDAO();
				$vetprot = $daoprot->ListaProtocoloPorEmmpresaCompetencia($mesano,$_SESSION['cnpj']);
				$numprot = count($vetprot);

				if($numprot > 0){

						$prot   = $vetprot[0];
						
						$idprot = $prot->getCodigo();	

						$prots  = new Protocolo();

						$prots->setCodigo($idprot);
						$prots->setStatus(10);	

						$daoprot->updateStatus($prots);	
						
				}

				## Dados da empresa
				$daoemp = new EmpresasTxt2DAO();
				$vetemp = $daoemp->ListViewCli($_SESSION['cnpj']);

				$_SESSION['apura']['somanimais'] = ($bovinos + $bubalinos + $ovinos);
				$_SESSION['apura']['idprot']     = $idprot;

				## Fim pegando numero de saidas inseridas

				# Dados lauyout apauração
				$daoapura   = new TipoLayoutDAO();
				$vetapura   = $daoapura->getTipoLayout($_SESSION['id_emp']);	
				$tipolayout = 1;
				if(!empty($vetapura)){
					if($vetapura[0]['tipo_layout'] == 1){
						$tipolayout = 1;
					}else{
						$tipolayout = 2;
					}
				}
				
				$daores = new ResumoDAO();
				$vetres = $daores->ValidaMesAnoCompetencia($mesano,$_SESSION['cnpj']);
				$numres = count($vetres);

				if($numres > 0){

					$resumos   = $vetres[0];

					$codresumo = $resumos->getCodigo();

					$resu =  new Resumo();

					$resu->setCodigo($codresumo);	
					$resu->setCompetenc($mesano);
					$resu->setBovinos($bovinos);
					$resu->setBubalinos($bubalinos);
					$resu->setOvinos($ovinos);
					$resu->setIcmsNor($valoricms);
					$resu->setSubstit($valoricmssubs);
					$resu->setCreditoEnt($credito);
					$resu->setCreditosRS($creditors);
					$resu->setCreditosOE($creditodifrs);
					$resu->setBaseEnt($base);
					$resu->setBaseSaiRS($basers);
					$resu->setBaseSaiOE($basedifrs);
					$resu->setNumeroFuncionario($numfuncio);
					$resu->setValorFolha($valorfolha);
					$resu->setDataPagto($datapag);
					$resu->setBaseSaiRS4($basers2);
					$resu->setCriditosR4($creditors2);
					$resu->setCreditosR4($creditors2);
					$resu->setCnpjEmp($_SESSION['cnpj']);
					$resu->setNumeroEntrada($num_entrada);
					$resu->setNumeroSaida($num_Saida);
					$resu->setCreditosOE4($creditodifrs2);
					$resu->setBaseSaiOE4($basedifrs2);
					
					$daores->update($resu);


				}else{
					
					$resu =  new Resumo();

					$resu->setCompetenc($mesano);
					$resu->setBovinos($bovinos);
					$resu->setBubalinos($bubalinos);
					$resu->setOvinos($ovinos);
					$resu->setIcmsNor($valoricms);
					$resu->setSubstit($valoricmssubs);
					$resu->setCreditoEnt($credito);
					$resu->setCreditosRS($creditors);
					$resu->setCreditosOE($creditodifrs);
					$resu->setBaseEnt($base);
					$resu->setBaseSaiRS($basers);
					$resu->setBaseSaiOE($basedifrs);
					$resu->setNumeroFuncionario($numfuncio);
					$resu->setValorFolha($valorfolha);
					$resu->setDataPagto($datapag);
					$resu->setBaseSaiRS4($basers2);
					$resu->setCriditosR4($creditors2);
					$resu->setCreditosR4($creditors2);
					$resu->setCnpjEmp($_SESSION['cnpj']);
					$resu->setNumeroEntrada($num_entrada);
					$resu->setNumeroSaida($num_Saida);
					$resu->setCreditosOE4($creditodifrs2);
					$resu->setBaseSaiOE4($basedifrs2);
					
					$daores->inserir($resu);

				}

				$data = array('animais'=>$animais,
							  'basecredito'=>$basecredito,
							  'vendars'=>$vendars,
							  'vendars2'=>$vendars2,
							  'vendasdifrs'=>$vendasdifrs,
							  'vendasdifrs2'=>$vendasdifrs2,
							  'total_geral_base'=>number_format($total_geral_base,2,',','.'),
							  'total_geral_credito'=>number_format($total_geral_credito,2,',','.'),
							  'folha'=>$folha,
							  'mesano'=>$mesano,
							  'exportacao'=>$resutexpo,
							  'empresa'=>$vetemp,
							  'tipolayout'=>$tipolayout,
							  'tipo'=>'xml');	

				echo json_encode($data);	
				
				//destruir session $_SESSION['apura']['mesano']	
			break;	
			
			case 'apurar2':
				error_reporting(E_ALL);
				ini_set('display_errors', 'On');		 
				
				$pathFile      = '../arquivos/'.$_SESSION['cnpj'].'/config.json';
				$configJson    = file_get_contents($pathFile);
				$installConfig = json_decode($configJson);

				$mesanoini 	  = !empty($_REQUEST['mesanoini']) ? date('m/Y',strtotime(implode("-", array_reverse(explode("/", "".$_REQUEST['mesanoini'].""))))) :  date('m/Y');  
				$mesanofim 	  = !empty($_REQUEST['mesanofim']) ? date('m/Y',strtotime(implode("-", array_reverse(explode("/", "".$_REQUEST['mesanofim'].""))))) :  date('m/Y');

				$condicao     = array();
				$condicao3    = array();
				$condicao4    = array();
				$condicao5    = array();
				$condicao6    = array();

				$condicao[]   = " concat(LPAD(EXTRACT(MONTH FROM n.data_abate),2,'0'),'/',EXTRACT(YEAR FROM n.data_abate)) between '".$mesanoini."' ";	
				$condicao[]   = " '".$mesanofim."' ";	
				$condicao[]   = " n.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao[]   = " p.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao[]   = " a.codigo in ('1','2','3','1001','1002','1003') ";
				$condicao[]   = " p.cod_secretaria < 10000 ";
				$condicao[]   = " p.cod_secretaria <> '99999' ";
				$condicao[]   = " n.cfop < 5000 ";
				
				$condicao7[]   = " concat(LPAD(EXTRACT(MONTH FROM n.data_abate),2,'0'),'/',EXTRACT(YEAR FROM n.data_abate)) between '".$mesanoini."' ";	
				$condicao7[]   = " '".$mesanofim."' ";	
				$condicao7[]   = " n.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao7[]   = " p.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao7[]   = " a.codigo in ('1','2','3','1001','1002','1003') ";
				$condicao7[]   = " p.cod_secretaria < 10000 ";
				$condicao7[]   = " p.cod_secretaria <> '99999' ";
				$condicao7[]   = " n.cfop > 5000 ";
				
				$condicao3[]  = " concat(LPAD(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao)) between '".$mesanoini."' ";
				$condicao3[]  = " '".$mesanofim."' ";	
				$condicao3[]  = " s.cnpj_emp = '".$_SESSION['cnpj']."' ";		
				$condicao3[]  = " c.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao3[]  = " e.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao3[]  = " p.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao3[]  = " e.uf = 'RS' ";
				$condicao3[]  = " p.cod_secretaria < 10000 ";
				$condicao3[]  = " p.cod_secretaria <> '99999' ";
				$condicao3[]  = " c.gera_agregar = '1' ";

				$condicao4[]  = " concat(LPAD(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao)) between '".$mesanoini."' ";
				$condicao4[]  = " '".$mesanofim."' ";
				$condicao4[]  = " s.cnpj_emp = '".$_SESSION['cnpj']."' ";		
				$condicao4[]  = " c.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao4[]  = " e.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao4[]  = " p.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao4[]  = " e.uf = 'RS' ";
				$condicao4[]  = " p.cod_secretaria >= 10000 ";
				$condicao4[]   = " p.cod_secretaria <> '99999' ";
				$condicao4[]  = " c.gera_agregar = '1' ";

				$condicao5[]  = " concat(LPAD(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao)) between '".$mesanoini."' ";
				$condicao5[]  = " '".$mesanofim."' ";
				$condicao5[]  = " s.cnpj_emp = '".$_SESSION['cnpj']."' ";		
				$condicao5[]  = " c.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao5[]  = " e.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao5[]  = " p.cnpj_emp = '".$_SESSION['cnpj']."' ";
				$condicao5[]  = " e.uf <> 'RS' ";
				$condicao5[]  = " p.cod_secretaria < 10000 ";
				$condicao5[]   = " p.cod_secretaria <> '99999' ";
				$condicao5[]  = " c.gera_agregar = '1' ";

				$condicao6[]  = " concat(LPAD(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao)) between '".$mesanoini."' ";
				$condicao6[]  = " '".$mesanofim."' ";
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
				
				## Contando quantos animais abatidos	
				$daonotasen1 = new NotasEn1TxtDAO();
				$vetnotasen1 = $daonotasen1->ListagemTotalDeAnimaisAbatidos($where); 
				$numnotasen1 = count($vetnotasen1);
				$animais     = array();

				if($numnotasen1 > 0){
					
					$notasen1 = $vetnotasen1[0];
					
					
					$daonotasen1d = new NotasEn1TxtDAO();
					$vetnotasen1d = $daonotasen1d->ListagemTotalDeAnimaisAbatidosDev($where7); 
					$numnotasen1d = count($vetnotasen1d);
					
					if($numnotasen1d > 0){
						$notasen1dv = $vetnotasen1d[0];
						$bovinos_dv   = $notasen1dv->getBovinos();
						$bubalinos_dv = $notasen1dv->getBubalinos();
						$ovinos_dv	  = $notasen1dv->getOvinos();	
						
					}else{
						$bovinos_dv   = 0;
						$bubalinos_dv = 0;
						$ovinos_dv	  = 0;	
					}
					
					$bovinos   = $notasen1->getBovinos() - $bovinos_dv;
					$bubalinos = $notasen1->getBubalinos() - $bubalinos_dv;
					$ovinos	   = $notasen1->getOvinos() - $ovinos_dv;									
					
					array_push($animais, array(
						'bovinos'=>''.$bovinos.'',
						'bubalinos'=>''.$bubalinos.'',
						'ovinos'=>''.$ovinos.'',	
					));					
					
				}else{

					$bovinos   = 0;
					$bubalinos = 0;
					$ovinos	   = 0;
					
					array_push($animais, array(
						'bovinos'=>''.$bovinos.'',
						'bubalinos'=>''.$bubalinos.'',
						'ovinos'=>''.$ovinos.'',	
					));
					
				}
				## termino da contagem de animais abatidos
				
    			
				## Total de produtores	

				if($installConfig->apuracao == '2'){

					$vetprodutor = $daonotasen1->TotalDeProdutoresPorNota($where);
					$numprodutor = count($vetprodutor);
					$basecredito = array();

					if($numprodutor > 0){
					
						$notasen12 = $vetprodutor[0];
						
						$vivo 		= $notasen12->getValorTotalNota();
												
						$export = new ExportacaoDAO();
						$vetexp = $export->ComputaCompetenciaExportacao($mesanoini,$_SESSION['cnpj']);
						$numexp = count($vetexp);

						if($numexp > 0){
							$expo		= $vetexp[0];
							$valor_glos = $expo->getValorGlosado();
						}else{
							$valor_glos = 0;
						}

						$base       = ($vivo - $valor_glos);
						$credito    = (($base * 3.6) / 100);
						
						array_push($basecredito, array(
							'base'=>$base,
							'credito'=>$credito,
							'devolucao'=>0,
							'valorentrada'=>$vivo,
						));	
						
					}else{
						$base = 0;
						$credito = 0;
						$vivo = 0;
						array_push($basecredito, array(
							'base'=>$base,
							'credito'=>$credito,
							'devolucao'=>0,
							'valorentrada'=>$vivo,
						));
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
						$vetexp = $export->ComputaCompetenciaExportacao($mesanoini,$_SESSION['cnpj']);
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
						
						$base         = ((($vivo + $rendimento) - $basedef) - $valor_glos);
						$credito      = (($base * 3.6) / 100);
						$valorentrada = ($vivo + $rendimento);

						array_push($basecredito, array(
							'base'=>$base,
							'credito'=>$credito,
							'devolucao'=>$basedef,
							'valorentrada'=>$valorentrada,							
						));	
						
					}else{
						$base = 0;
						$credito = 0;
						$basedef = 0;
						$valorentrada = 0;
						array_push($basecredito, array(
							'base'=>$base,
							'credito'=>$credito,
							'devolucao'=>$basedef,
							'valorentrada'=>$valorentrada,							
						));
					}	
				}
				## Termino de total de produtores
				
				## Pegando dados de exportação
				$exports   = new ExportacaoDAO();
				$vetexps   = $exports->ListaComputaCompetenciaExportacao($mesanoini,$_SESSION['cnpj']);
				$numexps   = count($vetexps);	
				$resutexpo = array();

				if($numexps > 0){

					for ($i=0; $i < $numexps; $i++) { 
						$expos = $vetexps[$i];

						$valor_glos = $expos->getValorGlosado();
						$nome_pt    = $expos->getPais();
						
						array_push($resutexpo,array(
							'nome'=>"{$nome_pt}",
							'valor'=>number_format($valor_glos,2,',','.'),
						));

					}

				}
				## Termino de dados de exportação

				## vendas RS
				$daonotasai = new NotasSaiTxtDAO();
				$vetnotasai = $daonotasai->TotalVendasRS($where3,$_SESSION['cnpj'],$mesanoini,'3');
				$numnotasai = count($vetnotasai);
				$vendars    = array();

				if($numnotasai > 0){
				
					$notasai = $vetnotasai[0];	
					
					$entrada = $notasai->getEntrada();
					$saida   = $notasai->getSaida();
					
					$basers    = $saida - $entrada;
					$creditors = (($basers * 3) / 100);
					
					array_push($vendars, array(
						'basers'=>$basers,
						'creditors'=>$creditors,
						'saida'=>$saida,
						'devolucao'=>$entrada
					));
					
				}else{
					$basers = 0;
					$creditors = 0;
					$saida = 0;
					$entrada = 0;
					array_push($vendars, array(
						'basers'=>$basers,
						'creditors'=>$creditors,
						'saida'=>$saida,
						'devolucao'=>$entrada
					));
				}
		
				$vetnotasai2 = $daonotasai->TotalVendasRS($where4,$_SESSION['cnpj'],$mesanoini,'4');
				$numnotasai2 = count($vetnotasai2);
				$vendars2    = array();

				if($numnotasai2 > 0){
				
					$notasai2   = $vetnotasai2[0];	
					
					$entrada2   = $notasai2->getEntrada();
					$saida2     = $notasai2->getSaida();
					
					$basers2    = $saida2 - $entrada2;
					$creditors2 = (($basers2 * 4) / 100);
					
					array_push($vendars2, array(
						'basers2'=>$basers2,
						'creditors2'=>$creditors2,
						'saida'=>$saida2,
						'devolucao'=>$entrada2
					));
					
				}else{
					$basers2 = 0;
					$creditors2 = 0;
					$saida2 = 0;
					$entrada2 = 0;
					array_push($vendars2, array(
						'basers2'=>$basers2,
						'creditors2'=>$creditors2,
						'saida'=>$saida2,
						'devolucao'=>$entrada2
					));
				}

				$vetnotasaioutro = $daonotasai->TotalVendasRS($where5,$_SESSION['cnpj'],$mesanoini,'outro3');
				$numnotasaioutro = count($vetnotasaioutro);
				$vendasdifrs     = array();

				if($numnotasaioutro > 0){
				
					$notasaioutro   = $vetnotasaioutro[0];	
					
					$entrada_outros = $notasaioutro->getEntrada();
					$saida_outros   = $notasaioutro->getSaida();
					
					$basedifrs      = $saida_outros - $entrada_outros;
					$creditodifrs   = (($basedifrs * 3) / 100);
					
					array_push($vendasdifrs, array(
						'basedifrs'=>$basedifrs,
						'creditodifrs'=>$creditodifrs,
						'saida'=>$saida_outros,
						'devolucao'=>$entrada_outros
					));
					
				}else{
					$basedifrs = 0;
					$creditodifrs = 0;
					$saida_outros = 0;
					$entrada_outros = 0;
					array_push($vendasdifrs, array(
						'basedifrs'=>$basedifrs,
						'creditodifrs'=>$creditodifrs,
						'saida'=>$saida_outros,
						'devolucao'=>$entrada_outros
					));
				}

				$vetnotasaioutro2 = $daonotasai->TotalVendasRS($where6,$_SESSION['cnpj'],$mesanoini,'outro4');
				$numnotasaioutro2 = count($vetnotasaioutro2);
				$vendasdifrs2     = array();

				if($numnotasaioutro2 > 0){
				
					$notasaioutro2 = $vetnotasaioutro2[0];	
					
					$entrada_outros2 = $notasaioutro2->getEntrada();
					$saida_outros2   = $notasaioutro2->getSaida();
					
					$basedifrs2    = $saida_outros2 - $entrada_outros2;
					$creditodifrs2 = (($basedifrs2 * 4) / 100);
					
					array_push($vendasdifrs2, array(
						'basedifrs2'=>$basedifrs2,
						'creditodifrs2'=>$creditodifrs2,
						'saida'=>$saida_outros2,
						'devolucao'=>$entrada_outros2
					));
					
				}else{
					$basedifrs2 = 0;
					$creditodifrs2 = 0;
					$saida_outros2 = 0;
					$entrada_outros2 = 0;
					array_push($vendasdifrs2, array(
						'basedifrs2'=>$basedifrs2,
						'creditodifrs2'=>$creditodifrs2,
						'saida'=>$saida_outros2,
						'devolucao'=>$entrada_outros2
					));
				}

				$total_geral_base    = $base + $basers + $basers2 + $basedifrs + $basedifrs2;
				$total_geral_credito = $credito + $creditors + $creditors2 + $creditodifrs + $creditodifrs2;

				## Fim vendas RS


				## folha
				$daof  = new FolhaTxtDAO();
				$vetf  = $daof->ListaFolhaEmpresasApurado($_SESSION['cnpj'],$mesanoini);
				$numf  = count($vetf);
				$folha = array();

				if($numf > 0){
				
					$folhatxt = $vetf[0];
					
					$numfuncio  = $folhatxt->getNumFuncionario();
					$valorfolha = $folhatxt->getValorFolha();
					
					if(empty($numfuncio)){
						$numfuncio = 0;
					}
					
					if(empty($valorfolha)){
						$valorfolha = 0;
					}
					
					array_push($folha, array(
						'numfuncio'=>''.$numfuncio.'',
						'valorfolha'=>''.$valorfolha.''
					));

				}else{
					$numfuncio  = 0;
					$valorfolha = 0;
					array_push($folha, array(
						'numfuncio'=>''.$numfuncio.'',
						'valorfolha'=>''.$valorfolha.''
					));
				}
				## fim folha
 				
 				## GUIA
				$daog = new GuiaicmsDAO();
				$vetg = $daog->ListaGuiaicmsCompetencia($_SESSION['cnpj'],$mesanoini);
				$numg = count($vetg);
				
				if($numg > 0){
					
					$guia 	   = $vetg[0];				
					$datapag   = implode("/", array_reverse(explode("-", "".$guia->getDataPago()."")));
					
				}else{
					$datapag   = "";
				}

				## FIM GUIA

				## Notas Saida Competencia
				$vets = $daonotasai->ListandoNotasSaiCompetancia($_SESSION['cnpj'],$mesanoini);
				$nums = count($vets);
				
				if($nums > 0 ){
					$notasaitxt = $vets[0];
					
					$valoricms	    = !empty($notasaitxt->getValorIcms()) ? $notasaitxt->getValorIcms() : 0;
					$valoricmssubs	= !empty($notasaitxt->getValorIcmsSubs()) ? $notasaitxt->getValorIcmsSubs() : 0;				
				}else{
					$valoricms	    = 0;
					$valoricmssubs	= 0;
				}
				## Fim Notas Saida Competencia

				## pegando numero de entradas inseridas
				$daoent        = new NotasEntTxtDAO();
				$vetnumentrada = $daoent->NumeroDeEntradas($mesanoini,$_SESSION['cnpj']);	
				$numentrada    = count($vetnumentrada);

				if($numentrada > 0){

					$notasentrada = $vetnumentrada[0];					
					$num_entrada  = $notasentrada->getNumeroEntrada();
				}else{
					$num_entrada  = 0;	
				}
				## FIM pegando numero de entradas inseridas

				## pegando numero de saidas inseridas
				$vetnumsai = $daonotasai->NumeroDeSaidas($mesanoini,$_SESSION['cnpj']);
				$numsai    = count($vetnumsai);

				if($numsai > 0){

					$notassai  = $vetnumsai[0];
					$num_Saida = $notassai->getNumeroSaida();
				}else{
					$num_Saida = 0;
				}

				## Fim pegando numero de saidas inseridas

				$daoprot = new ProtocoloDAO();
				$vetprot = $daoprot->ListaProtocoloPorEmmpresaCompetencia($mesanoini,$_SESSION['cnpj']);
				$numprot = count($vetprot);

				if($numprot > 0){

						$prot   = $vetprot[0];
						
						$idprot = $prot->getCodigo();	

						$prots  = new Protocolo();

						$prots->setCodigo($idprot);
						$prots->setStatus(10);	

						$daoprot->updateStatus($prots);	
						
				}

				## Dados da empresa
				$daoemp = new EmpresasTxt2DAO();
				$vetemp = $daoemp->ListViewCli($_SESSION['cnpj']);

				# Dados lauyout apauração
				$daoapura   = new TipoLayoutDAO();
				$vetapura   = $daoapura->getTipoLayout($_SESSION['id_emp']);	
				$tipolayout = 1;
				if(!empty($vetapura)){
					if($vetapura[0]['tipo_layout'] == 1){
						$tipolayout = 1;
					}else{
						$tipolayout = 2;
					}
				}

				$_SESSION['apura']['somanimais'] = ($bovinos + $bubalinos + $ovinos);
				$_SESSION['apura']['idprot']     = $idprot;
				$_SESSION['apura']['mesano']     = $mesanoini;
				
				$daores = new ResumoDAO();
				$vetres = $daores->ValidaMesAnoCompetencia($mesanoini,$_SESSION['cnpj']);
				$numres = count($vetres);

				if($numres > 0){

					$resumos   = $vetres[0];

					$codresumo = $resumos->getCodigo();

					$resu =  new Resumo();

					$resu->setCodigo($codresumo);	
					$resu->setCompetenc($mesanoini);
					$resu->setBovinos($bovinos);
					$resu->setBubalinos($bubalinos);
					$resu->setOvinos($ovinos);
					$resu->setIcmsNor($valoricms);
					$resu->setSubstit($valoricmssubs);
					$resu->setCreditoEnt($credito);
					$resu->setCreditosRS($creditors);
					$resu->setCreditosOE($creditodifrs);
					$resu->setBaseEnt($base);
					$resu->setBaseSaiRS($basers);
					$resu->setBaseSaiOE($basedifrs);
					$resu->setNumeroFuncionario($numfuncio);
					$resu->setValorFolha($valorfolha);
					$resu->setDataPagto($datapag);
					$resu->setBaseSaiRS4($basers2);
					$resu->setCriditosR4($creditors2);
					$resu->setCreditosR4($creditors2);
					$resu->setCnpjEmp($_SESSION['cnpj']);
					$resu->setNumeroEntrada($num_entrada);
					$resu->setNumeroSaida($num_Saida);
					$resu->setCreditosOE4($creditodifrs2);
					$resu->setBaseSaiOE4($basedifrs2);			
					
					$daores->update($resu);


				}else{


					$resu =  new Resumo();

					$resu->setCompetenc($mesanoini);
					$resu->setBovinos($bovinos);
					$resu->setBubalinos($bubalinos);
					$resu->setOvinos($ovinos);
					$resu->setIcmsNor($valoricms);
					$resu->setSubstit($valoricmssubs);
					$resu->setCreditoEnt($credito);
					$resu->setCreditosRS($creditors);
					$resu->setCreditosOE($creditodifrs);
					$resu->setBaseEnt($base);
					$resu->setBaseSaiRS($basers);
					$resu->setBaseSaiOE($basedifrs);
					$resu->setNumeroFuncionario($numfuncio);
					$resu->setValorFolha($valorfolha);
					$resu->setDataPagto($datapag);
					$resu->setBaseSaiRS4($basers2);
					$resu->setCriditosR4($creditors2);
					$resu->setCreditosR4($creditors2);
					$resu->setCnpjEmp($_SESSION['cnpj']);
					$resu->setNumeroEntrada($num_entrada);
					$resu->setNumeroSaida($num_Saida);
					$resu->setCreditosOE4($creditodifrs2);
					$resu->setBaseSaiOE4($basedifrs2);
					$daores->inserir($resu);

				}

				$data = array('animais'=>$animais,
							  'basecredito'=>$basecredito,
							  'vendars'=>$vendars,
							  'vendars2'=>$vendars2,
							  'vendasdifrs'=>$vendasdifrs,
							  'vendasdifrs2'=>$vendasdifrs2,
							  'total_geral_base'=>number_format($total_geral_base,2,',','.'),
							  'total_geral_credito'=>number_format($total_geral_credito,2,',','.'),
							  'folha'=>$folha,
							  'mesano'=>$mesanoini,
							  'exportacao'=>$resutexpo,
							  'empresa'=>$vetemp,
							  'tipolayout'=>$tipolayout,
							  'tipo'=>'txt');	

				echo json_encode($data);	
				
				//destruir session $_SESSION['apura']['mesano']
					
			break;
			case 'protocolo':

				$somaanimais = $_SESSION['apura']['somanimais'];
				$cnpj_emp    = $_SESSION['cnpj'];
				$mesano      = $_SESSION['apura']['mesano'];
				$idprot      = $_SESSION['apura']['idprot'];
				$tipo		 = $_REQUEST['tipo'];

				$encodeTXT   = ''.$mesano.'|'.$somaanimais.'|'.$cnpj_emp.'';	
				$cript		 =  sha1($encodeTXT);				

				$daoprot = new ProtocoloDAO();	
				$vetprot = $daoprot->VerificaCriptografia($mesano,$cnpj_emp);
				$numprot = count($vetprot);


				if($vetprot > 0){

					$prots  = $vetprot[0];

					$cod     = $prots->getCodigo();
					$criptys = $prots->getCripty();


					if($criptys != $cript){

						//inserir novo protocolo
						$prot = new Protocolo();

						$prot->setCompetencia($mesano);
						$prot->setProtocolo(preg_replace("/[^0-9]/", "", $cript));
						$prot->setCripty($cript);
						$prot->setStatus(9);
						$prot->setCnpjEmp($cnpj_emp);
						$prot->setTipoArq($tipo);
						$daoprot->inserir($prot);	
						

					}else{

						$prot = new Protocolo();
				
						$prot->setCodigo($idprot);
						$prot->setStatus(9);
						$prot->setProtocolo(preg_replace("/[^0-9]/", "", $cript));
						$prot->setCripty($cript);

						
						$daoprot->updateStatusProtocolo($prot);

					}


				}
				

				$result = array();

				array_push($result, array(
					'protocolo'=>''.preg_replace("/[^0-9]/", "", $cript).'',
				));

				echo json_encode($result);
			break;
			case 'enviaremail':

				$pathFile      = '../arquivos/config.json';
				$configJson    = file_get_contents($pathFile);
				$installConfig = json_decode($configJson);

				$cnpj_emp       = $_SESSION['cnpj'];
				$mesano         = $_SESSION['apura']['mesano'];
				$pastamesano    = str_replace('/','',$mesano);
				$caminhopadrao  = "../arquivos/{$cnpj_emp}/dbf/{$pastamesano}/";
				$caminhopadrao2 = "../arquivos/{$cnpj_emp}/dbf/";

				$dados = array(
					'mesano'=>"{$mesano}",
					'cnpjemp'=>"{$cnpj_emp}",
					'caminho'=>"{$caminhopadrao}",
					'caminho2'=>"{$caminhopadrao2}",
					'pastamesano'=>"{$pastamesano}" 
				 );

				 $dbf = new Dbf($dados);

                 $arqdbf = $dbf->Save();

                 if(count($arqdbf) > 0){
                    foreach ($arqdbf as $key => $value) {
                      unlink($caminhopadrao.$value);
                    }                    
                 }

				$daoprot = new ProtocoloDAO();
				$vetprot = $daoprot->ListaProtocoloPorEmmpresaCompetencia($mesano,$cnpj_emp);
				$numprot = count($vetprot);

				if($numprot > 0){

						$prot   = $vetprot[0];
						
						$idprot = $prot->getCodigo();	

						$prots  = new Protocolo();

						$prots->setCodigo($idprot);
						$prots->setStatus(8);	

						$daoprot->updateStatus($prots);	
						
				}


				$dao = new ResumoDAO();
				$vet = $dao->MesAnoCompetenciaFinalizadaParaEnvio($mesano,$cnpj_emp);
				$num = count($vet);	

				if($num > 0){

					$resu         = $vet[0];
							
					$cod 	      = $resu->getCodigo();			
					$status       = $resu->getStatus();
					$protocolo    = $resu->getProtocolo();
					$competencia  = $resu->getCompetenc();
					$nome 		  = $resu->getNomeStatus();
					$razao_social = $resu->getRazaoSocialEmp();	

					$msg          = utf8_decode("Razão Social: {$razao_social}<br/> Cnpj:{$cnpj_emp}<br>Recibo de protocolo de numero <mark>{$protocolo}</mark> gerado com sucesso ");	


					$msgs = new MensagemEmpresa();
					
					$msgs->setTitulo('Apuração de arquivos agregar competencia '.$competencia.' ');
					$msgs->setMensagem(utf8_decode("Razão Social: {$razao_social}<br/> Cnpj:{$cnpj_emp}<br>Recibo de protocolo de numero <mark>{$protocolo}</mark> gerado com sucesso;  "));
					$msgs->setIdModalidade(1);
					$msgs->setIdEmpresa($_SESSION['id_emp']);
					$msgs->setData(date('Y-m-d'));
					$msgs->setTimesTamp(time());
					
					$daoms  = new MensagemEmpresaDAO();
					
					$vetms  = $daoms->proximoid();
					$prox   = $vetms[0];
					$idprox = $prox->getProximoId();

					$daoms->inserir($msgs);

					
					$dados = array(
						'SMTPAuth'=>true,
						'SMTPSecure'=>''.$installConfig->emails->smtpsegure.'',
						'Host'=>''.$installConfig->emails->host.'',
						'Port'=>$installConfig->emails->port,
						'Username'=>''.$installConfig->emails->username.'',
						'Password'=>''.$installConfig->emails->senhaem.'',		
					);


					$daoe = new EmailDAO($dados);

					$std = new stdClass();

					$std->mensagem    = "{$msg}";
					$std->titulo      = utf8_decode("Apuração de arquivos agregar competencia {$competencia}");
					$std->nome 		  = "Agregar";
					$std->assinatura  = "agregar";
					$std->data        = date('d/m/Y');
					$std->url         = "http://agregarcarnesrs.com.br/";
					$std->email       = "{$installConfig->emails->username}";
					$std->msgretorno  = "COMPETÊNCIA ENVIADO COM SUCESSO!";
					$std->txt_btn     = utf8_decode("VER COMPETÊNCIA");
					$std->Attachment  = array(
						'arquivo'=>'AGREGARS.ZIP',
						'caminho'=>''.$caminhopadrao.'',
					);

				 	$results =  $daoe->mandaEmail($std);

										
					/*require '../pusher/vendor/autoload.php';
					$options = array(
				    'cluster' => 'us2',
				    'encrypted' => true
				  );
				  $pusher = new Pusher\Pusher(
				    'd660df1b434be12934a5',
				    '9807355fbaffff4b0b95',
				    '563425',
				    $options
				  );

				  $data['message'] = "Apuração de arquivos agregar competencia {$competencia}|{$msg}|Apuração de arquivos agregar competencia{$competencia}|{$_SESSION['id_emp']}|{$idprox}";
				  
				  $pusher->trigger('my-channel', 'my-event', $data);*/


					echo (json_encode($results));
				}

			break;

			case 'buscaapuracao':

				$mesano 	  = $_REQUEST['mesano'];  
				$cnpj         = $_REQUEST['cnpj']; 

				$pathFile      = '../arquivos/'.$cnpj.'/config.json';
				$configJson    = file_get_contents($pathFile);
				$installConfig = json_decode($configJson);

				$condicao     = array();
				$condicao3    = array();
				$condicao4    = array();
				$condicao5    = array();
				$condicao6    = array();

				$condicao[]   = " concat(LPAD(EXTRACT(MONTH FROM n.data_abate),2,'0'),'/',EXTRACT(YEAR FROM n.data_abate)) between '".$mesano."' ";	
				$condicao[]   = " '".$mesano."' ";	
				$condicao[]   = " n.cnpj_emp = '".$cnpj."' ";
				$condicao[]   = " a.codigo in ('1','2','3','1001','1002','1003') ";
				$condicao[]   = " p.cod_secretaria < 10000 ";
				$condicao[]   = " p.cod_secretaria <> '99999' ";

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
				$condicao6[]  = " s.cnpj_emp = '".$cnpj."' ";		
				$condicao6[]  = " c.cnpj_emp = '".$cnpj."' ";
				$condicao6[]  = " e.cnpj_emp = '".$cnpj."' ";
				$condicao6[]  = " p.cnpj_emp = '".$cnpj."' ";
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
					
					array_push($animais, array(
						'bovinos'=>''.$bovinos.'',
						'bubalinos'=>''.$bubalinos.'',
						'ovinos'=>''.$ovinos.'',	
					));					
					
				}else{

					$bovinos   = 0;
					$bubalinos = 0;
					$ovinos	   = 0;
					
					array_push($animais, array(
						'bovinos'=>''.$bovinos.'',
						'bubalinos'=>''.$bubalinos.'',
						'ovinos'=>''.$ovinos.'',	
					));
					
				}
				## termino da contagem de animais abatidos
				
    			
				## Total de produtores	

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
						
						array_push($basecredito, array(
							'base'=>''.number_format($base,2,',','.').'',
							'credito'=>''.number_format($credito,2,',','.').''
						));	
						
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
						
						array_push($basecredito, array(
							'base'=>''.number_format($base,2,',','.').'',
							'credito'=>''.number_format($credito,2,',','.').''
						));	
						
					}	
				}
				## Termino de total de produtores

				## Pegando dados de exportação
				$exports   = new ExportacaoDAO();
				$vetexps   = $exports->ListaComputaCompetenciaExportacao($mesano,$_SESSION['cnpj']);
				$numexps   = count($vetexps);	
				$resutexpo = array();

				if($numexps > 0){

					for ($i=0; $i < $numexps; $i++) { 
						$expos = $vetexps[$i];

						$valor_glos = $expos->getValorGlosado();
						$nome_pt    = $expos->getPais();
						
						array_push($resutexpo,array(
							'nome'=>"{$nome_pt}",
							'valor'=>number_format($valor_glos,2,',','.'),
						));

					}

				}
				## Termino de dados de exportação

				## vendas RS
				$daonotasai = new NotasSaiTxtDAO();
				$vetnotasai = $daonotasai->TotalVendasRS($where3,$cnpj,$mesano,'3');
				$numnotasai = count($vetnotasai);
				$vendars    = array();

				if($numnotasai > 0){
				
					$notasai = $vetnotasai[0];	
					
					$entrada = $notasai->getEntrada();
					$saida   = $notasai->getSaida();
					
					$basers    = $saida - $entrada;
					$creditors = (($basers * 3) / 100);
					
					array_push($vendars, array(
						'basers'=>''.number_format($basers,2,',','.').'',
						'creditors'=>''.number_format($creditors,2,',','.').''
					));
					
				}

				$vetnotasai2 = $daonotasai->TotalVendasRS($where4,$cnpj,$mesano,'4');
				$numnotasai2 = count($vetnotasai2);
				$vendars2    = array();

				if($numnotasai2 > 0){
				
					$notasai2   = $vetnotasai2[0];	
					
					$entrada2   = $notasai2->getEntrada();
					$saida2     = $notasai2->getSaida();
					
					$basers2    = $saida2 - $entrada2;
					$creditors2 = (($basers2 * 4) / 100);
					
					array_push($vendars2, array(
						'basers2'=>''.number_format($basers2,2,',','.').'',
						'creditors2'=>''.number_format($creditors2,2,',','.').''
					));
					
				}

				$vetnotasaioutro = $daonotasai->TotalVendasRS($where5,$cnpj,$mesano,'outro3');
				$numnotasaioutro = count($vetnotasaioutro);
				$vendasdifrs     = array();

				if($numnotasaioutro > 0){
				
					$notasaioutro   = $vetnotasaioutro[0];	
					
					$entrada_outros = $notasaioutro->getEntrada();
					$saida_outros   = $notasaioutro->getSaida();
					
					$basedifrs      = $saida_outros - $entrada_outros;
					$creditodifrs   = (($basedifrs * 3) / 100);
					
					array_push($vendasdifrs, array(
						'basedifrs'=>''.number_format($basedifrs,2,',','.').'',
						'creditodifrs'=>''.number_format($creditodifrs,2,',','.').''
					));
					
				}

				$vetnotasaioutro2 = $daonotasai->TotalVendasRS($where6,$cnpj,$mesano,'outro4');
				$numnotasaioutro2 = count($vetnotasaioutro2);
				$vendasdifrs2     = array();

				if($numnotasaioutro2 > 0){
				
					$notasaioutro2 = $vetnotasaioutro2[0];	
					
					$entrada_outros2 = $notasaioutro2->getEntrada();
					$saida_outros2   = $notasaioutro2->getSaida();
					
					$basedifrs2    = $saida_outros2 - $entrada_outros2;
					$creditodifrs2 = (($basedifrs2 * 4) / 100);
					
					array_push($vendasdifrs2, array(
						'basedifrs2'=>''.number_format($basedifrs2,2,',','.').'',
						'creditodifrs2'=>''.number_format($creditodifrs2,2,',','.').''
					));
					
				}

				$total_geral_base    = $base + $basers + $basers2 + $basedifrs + $basedifrs2;
				$total_geral_credito = $credito + $creditors + $creditors2 + $creditodifrs + $creditodifrs2;

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

				}else{
					$numfuncio  = 0;
					$valorfolha = 0;
					array_push($folha, array(
						'numfuncio'=>''.$numfuncio.'',
						'valorfolha'=>''.$valorfolha.''
					));
				}
				## fim folha
 				
 				## GUIA
				$daog = new GuiaicmsDAO();
				$vetg = $daog->ListaGuiaicmsCompetencia($cnpj,$mesano);
				$numg = count($vetg);
				
				if($numg > 0){
					
					$guia 	   = $vetg[0];				
					$datapag   = implode("/", array_reverse(explode("-", "".$guia->getDataPago()."")));
					
				}else{
					$datapag   = "";
				}

				## FIM GUIA

				## Notas Saida Competencia
				$vets = $daonotasai->ListandoNotasSaiCompetancia($cnpj,$mesano);
				$nums = count($vets);
				
				if($nums > 0 ){
					$notasaitxt = $vets[0];
					
					$valoricms	    = $notasaitxt->getValorIcms();
					$valoricmssubs	= $notasaitxt->getValorIcmsSubs();				
				}else{
					$valoricms	    = 0;
					$valoricmssubs	= 0;
				}
				## Fim Notas Saida Competencia

				## pegando numero de entradas inseridas
				$daoent        = new NotasEntTxtDAO();
				$vetnumentrada = $daoent->NumeroDeEntradas($mesano,$cnpj);	
				$numentrada    = count($vetnumentrada);

				if($numentrada > 0){

					$notasentrada = $vetnumentrada[0];					
					$num_entrada  = $notasentrada->getNumeroEntrada();
				}else{
					$num_entrada  = 0;	
				}
				## FIM pegando numero de entradas inseridas

				## pegando numero de saidas inseridas
				$vetnumsai = $daonotasai->NumeroDeSaidas($mesano,$cnpj);
				$numsai    = count($vetnumsai);

				if($numsai > 0){

					$notassai  = $vetnumsai[0];
					$num_Saida = $notassai->getNumeroSaida();
				}else{
					$num_Saida = 0;
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

					array_push($prot, array(
						'codres'=>''.$codres.'',
						'xstatus'=>''.$xstatus.'',
						'protocolo'=>''.$protocolo.''
					));

				}

				$daostatus = new StatusDAO();
				$vetstatus = $daostatus->ListaStatus();
				$numstatus = count($vetstatus);
				$status    = array();
				for($st = 0; $st < $numstatus; $st++){
					$stat = $vetstatus[$st];

					$codstatus  = $stat->getCodigo();
					$nomestatus = $stat->getNome();

					array_push($status, array(
						'codstatus'=>''.$codstatus.'',
						'nomestatus'=>''.$nomestatus.'',
					));
				}


				$data = array('animais'=>$animais,
							  'basecredito'=>$basecredito,
							  'vendars'=>$vendars,
							  'vendars2'=>$vendars2,
							  'vendasdifrs'=>$vendasdifrs,
							  'vendasdifrs2'=>$vendasdifrs2,
							  'total_geral_base'=>number_format($total_geral_base,2,',','.'),
							  'total_geral_credito'=>number_format($total_geral_credito,2,',','.'),
							  'folha'=>$folha,
							  'mesano'=>$mesano,
							  'prot'=>$prot,
							  'status'=>$status,
							  'exportacao'=>$resutexpo);	

				echo json_encode($data);	

			break;

			case 'uplayoutapuracao':
				$id  = $_REQUEST['id'];

				# Dados lauyout apauração
				$daoapura   = new TipoLayoutDAO();
				$vetapura   = $daoapura->getTipoLayout($_SESSION['id_emp']);	
				$tipolayout = 1;
				//print_r($vetapura);
				if($vetapura){
					$cod = $vetapura[0]['id'];
					$data = Array (
						"tipo_layout" =>$id,												
					);

					$daoapura->alterar($data,$cod);

				}else{

					$data = Array (
								"tipo_layout" =>$id,
								"id_emp" => $_SESSION['id_emp'],							
							);

					$daoapura->Gravar($data);			
				}
				$res = array();
				array_push($res,array(
					'msg'=>'Alterado com sucesso'
				));
				echo json_encode($res);

			break;
			case 'gravapdf':
				
				$cnpj_emp       = $_SESSION['cnpj'];
				$mesano         = $_SESSION['apura']['mesano'];
				$pastamesano    = str_replace('/','',$mesano);
				$nomearquivo    = "apuracao_{$pastamesano}";				
				$param 			= json_decode($_REQUEST['dados']);
				$reponse 		= urlencode(json_encode($param));
				$tipo			= $_REQUEST['tipo'];
				$layout			= $_REQUEST['layout'];
				$param2  		= json_decode('{"tipo":"'.$tipo.'","layout":"'.$layout.'"}');
				$outros			= urlencode(json_encode($param2));
				## Dados da empresa
				$daoemp = new EmpresasTxt2DAO();
				$vetemp = $daoemp->ListViewCli($_SESSION['cnpj']);

				$daosnap = new Capture();

				$snap = $daosnap->snap('http://agregarcarnesrs.com.br/php/apuracaotopdf.php?dados='.$reponse.'&outros='.$outros.'');
				$resp = $daosnap->imagesaver("../arquivos/{$cnpj_emp}/{$nomearquivo}",$snap);
				
				require '../vendor/autoload.php';

				$options = new Options();
				$options->set('isRemoteEnabled', TRUE);
				$options->setIsHtml5ParserEnabled(true);

				$dompdf = new Dompdf($options);

				$htm = ' <img class="img-fluid" src="'.$snap.'" alt="snap">';	
				
				$html = '<!DOCTYPE html>
							<html lang="en">
								<head>
									<meta charset="utf-8">
									<title>PRODASIQ</title>
									<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
									<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
									<meta name="viewport" content="width=device-width, initial-scale=1.0">     
									<!-- Le styles -->       
										
									<style type="text/css">
										html,  body {
											height: 100%;
											margin:5px;
											padding:0px;								
										}  
										
										
										#wrap {
											
											margin: 0 auto -60px; 
											height: 25cm;
											background: #dcdcdc;*/
										}
									
									</style>             
								</head>
							<body>
				
						
					<div id="wrap">  
						'.$htm.'	 
					</div>
				</body>
				</html>
				';

				$dompdf->loadHtml($html);

				$dompdf->setPaper('A4', 'portrait');

				$dompdf->render();
				$output = $dompdf->output();
				$res = array();
				
				

			   if(! file_put_contents("../arquivos/{$cnpj_emp}/{$nomearquivo}.pdf",$output)){
				   array_push($res,array(
					   'titulo'=>"Mensagem da gravação",
					   'mensagem'=>"erro ao gravar aquivo",
					   'url'=>'',
					   'tipo'=>'2'
				   ));
			   }else{
				   array_push($res,array(
					   'titulo'=>"Mensagem da gravação",
					   'mensagem'=>"Sucesso!",
					   'email'=>$vetemp[0]['email'],
					   'corpo'=>"Ola Sr(a). {$vetemp[0]['razao_social']},\nSegue o anexo da apuração mensal AGREGAR Mês/Ano: {$mesano}",
					   'url'=>"../arquivos/{$cnpj_emp}/{$nomearquivo}.pdf",
					   'assunto'=>"Apuração Mensal AGREGAR Mês/Ano: {$mesano}",						
					   'tipo'=>'1'
				   ));
			   }
			   
			   echo json_encode($res);

			break;

			case 'enviarpdfapuracao';
			    $envpara 	= $_REQUEST['env-para'];
			    $envassunto	= $_REQUEST['env-assunto'];
			    $obs		= $_REQUEST['obs'];

	 		    $pathFile      = '../arquivos/config.json';
				$configJson    = file_get_contents($pathFile);
				$installConfig = json_decode($configJson);

				$cnpj_emp       = $_SESSION['cnpj'];
				$mesano         = $_SESSION['apura']['mesano'];
				$pastamesano    = str_replace('/','',$mesano);
				$caminhopadrao  = "../arquivos/{$cnpj_emp}/";

				$dados = array(
					'SMTPAuth'=>true,
					'SMTPSecure'=>''.$installConfig->emails->smtpsegure.'',
					'Host'=>''.$installConfig->emails->host.'',
					'Port'=>$installConfig->emails->port,
					'Username'=>''.$installConfig->emails->username.'',
					'Password'=>''.$installConfig->emails->senhaem.'',		
				);

				$daoe = new EmailDAO($dados);

				$std = new stdClass();

				$std->mensagem    = "{$obs}";
				$std->titulo      = utf8_decode("{$envassunto}");
				$std->nome 		  = "Agregar";
				$std->assinatura  = "agregar";
				$std->data        = date('d/m/Y');
				$std->url         = "http://agregarcarnesrs.com.br/";
				$std->email       = "{$installConfig->emails->username}";
				$std->para        = "{$envpara}";
				$std->msgretorno  = "E-MAIL ENVIADO COM SUCESSO!";
				$std->txt_btn     = utf8_decode("VER COMPETÊNCIA");
				$std->Attachment  = array(
					'arquivo'=>"apuracao_{$pastamesano}.pdf",
					'caminho'=>''.$caminhopadrao.'',
				);
				
				$results =  $daoe->mandaEmail($std);									
				
				echo (json_encode($results));

			break;
			
		}
	}

?>
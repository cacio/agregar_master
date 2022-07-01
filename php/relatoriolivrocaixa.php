<?php
	
	require_once('../inc/inc.autoload.php');
	require_once('../php/geral_config.php');

	$tpl = new TemplatePower('../tpl/relatoriolivrocaixa.htm');
	//$tpl->assignInclude('conteudo','../tpl/relatorioficha.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');		

		$tpl->assign('log',$_SESSION['login']);
		$tpl->assign('empresa',$empresa);
		$tpl->assign('msgmensalidade',$msgmensalidade);

		$condicao  = array();
		$condicao2 = array();		
		$condicao3 = array();
		$condicao4 = array();

		if(isset($_REQUEST['dtadmissaoini']) and !empty($_REQUEST['dtadmissaoini'])){

			$dtadmissaoini    =  $_REQUEST['dtadmissaoini'];	

			$condicao[]   = " l.data between '".implode("-", array_reverse(explode("-", "".$dtadmissaoini."")))."' ";
			$condicao2[]  = " l.data <= '".implode("-", array_reverse(explode("-", "".$dtadmissaoini."")))."' ";		
			$condicao4[]   = " ll.data between '".implode("-", array_reverse(explode("-", "".$dtadmissaoini."")))."' ";
			
		}
				
		if(isset($_REQUEST['dtadmissaofin']) and !empty($_REQUEST['dtadmissaofin'])){

			$dtadmissaofin     =  $_REQUEST['dtadmissaofin'];	

			$condicao[]   = " '".implode("-", array_reverse(explode("-", "".$dtadmissaofin."")))."' ";
			$condicao4[]  = " '".implode("-", array_reverse(explode("-", "".$dtadmissaofin."")))."' ";
		}						
		
		if(isset($_REQUEST['tpdep']) and !empty($_REQUEST['tpdep'])){

			$tpdep	      =  $_REQUEST['tpdep'];	

			$condicao[]   = " l.id_dep =  '".$tpdep."' ";
			//$condicao4[]  = " '".implode("-", array_reverse(explode("-", "".$dtadmissaofin."")))."' ";
		}
		
		$condicao[]   = " l.id_dep <> '1' ";
		$condicao2[]  = "  l.id_dep = '1' ";
		$condicao4[]   = " ll.id_dep <> '1' ";

		$where = '';

		if(count($condicao) > 0){
		
			$where = ' where'.implode('AND',$condicao);
				
		}	
		
		$where2 = '';

		if(count($condicao2) > 0){
			
			$where2 = ' where'.implode('AND',$condicao2);				
						
		}
		
		$where4 = '';

		if(count($condicao4) > 0){
			
			$where4 = ' where'.implode('AND',$condicao4);				
						
		}
		
		
		$dao = new CaixaDAO();
		
		$veti = $dao->SaldoInicial($where2);
		$numi = count($veti);	
		
		if($numi > 0){			
			$cx = $veti[0];
			
			$sadoini = $cx->getValor();
			$dataini = $cx->getData();								
		}else{
			$sadoini = 0;			
			$dataini = '';
		}

		$condicao3[]  = " l.data > '".$dataini."' ";
		$condicao3[]  = " l.data < '".implode("-", array_reverse(explode("-", "".$dtadmissaoini."")))."' ";
		$condicao3[]  = " l.id_dep <> '1' ";

		$where3 = '';

		if(count($condicao3) > 0){
			
			$where3 = ' where'.implode('AND',$condicao3);				
						
		}

		$valortotal=0;

	if($sadoini > 0){
		$vetc = $dao->CompoeSaldo($where3);
		$numc = count($vetc);
		
		for($x = 0; $x < $numc; $x++){
			
			$caix = $vetc[$x];
			
			$tipox    = $caix->getTipo();
			$valorx   = $caix->getValor();
			
			if($tipox == "E"){
				$xentrada = $valorx;
				$xsaida   = 0;
			}else if($tipox == "S"){
				$xsaida   = $valorx;
				$xentrada = 0;
			}	
			
			$valortotal = $valortotal +  $xentrada - $xsaida;
			
		}
		}
		//echo $valortotal;
		$sadoini = $sadoini + $valortotal;

		
		
		if($_REQUEST['analisint'] == 'A'){
			$tpl->newBlock('analitico');
			
			$tpl->assign('xnome','Saldo Inicial');
			$tpl->assign('sadoini',number_format($sadoini,2,',','.'));
			
			$vet = $dao->RelatorioLivroCaixa($where);
			$num = count($vet);
			$saldo = $sadoini;
			$tot_entrada = 0;
			$tot_saida   = 0;



			for($i = 0; $i < $num; $i++){

				$caixa = $vet[$i];

				$data    = $caixa->getData();
				$doc     = $caixa->getDocumento();
				$hist    = $caixa->getHistorico();
				$tipo    = $caixa->getTipo();
				$valor   = $caixa->getValor();
				$iddep   = $caixa->getIdDep();
				$nomadep = $caixa->getNomeDep();

				if($tipo == "E"){
					$entrada = $valor;
					$saida   = 0;
				}else if($tipo == "S"){
					$saida   = $valor;
					$entrada = 0;
				}			

				$tot_entrada = $tot_entrada + $entrada;
				$tot_saida   = $tot_saida + $saida;
				$saldo 		 = $saldo +  $entrada - $saida;

				/*if($iddep == 1){
					$saldo = $valor;	
				}*/

				$tpl->newBlock('caixa');	

				$tpl->assign('data',implode("/", array_reverse(explode("-", "".$data.""))));
				$tpl->assign('doc',$doc);
				$tpl->assign('hist',$hist);
				$tpl->assign('nomadep',$nomadep);
				if($entrada == 0){
					$tpl->assign('entrada','');
				}else{
					$tpl->assign('entrada',number_format($entrada,2,',','.'));	
				}

				if($saida == 0){
					$tpl->assign('saida','');
				}else{
					$tpl->assign('saida',number_format($saida,2,',','.') );
				}

				$tpl->assign('saldo',number_format($saldo,2,',','.'));
			}	

				$tpl->newBlock('total');
				$tpl->assign('tot_entrada',number_format($tot_entrada,2,',','.'));
				$tpl->assign('tot_saida',number_format($tot_saida,2,',','.') );
				$tpl->assign('saldo',number_format($saldo,2,',','.'));
		}else if($_REQUEST['analisint'] == 'S'){
			
			$tpl->newBlock('sintetico');
			
			$tpl->assign('xnome','Saldo Inicial');
			$tpl->assign('sadoini',number_format($sadoini,2,',','.'));
			
			$vet2 = $dao->RelatorioLivroCaixaSintetico($where,$where4);
			$num2 = count($vet2);	
			$saldo2 = $sadoini;
			$xid_dep = "";
			$tot_entrada2 = 0;
			$tot_saida2   = 0;
			
			for($x = 0; $x < $num2; $x++){
				
				$cx = $vet2[$x];
				
				$entradas = $cx->getEntrada();
				$saidas   = $cx->getSaida();
				$id_dep   = $cx->getIdDep();
				$nome	  = $cx->getNomeDep();
				
				
				/*if($id_dep != $xid_dep){
					
					$xid_dep = $id_dep;
					
					
					
					$tpl->newBlock('listacaixa');
					
					$tpl->assign('id_dep',$id_dep);
					$tpl->assign('nome',$nome);
					$tpl->assign('entradas',number_format($entradas,2,',','.'));
					$tpl->assign('saidas',number_format($saidas,2,',','.'));
					$tpl->assign('saldo2',number_format($saldo2,2,',','.'));
					
				}*/
				
				$tot_entrada2 = $tot_entrada2 + $entradas;
				$tot_saida2   = $tot_saida2 + $saidas;
				$saldo2  = $saldo2 + $entradas - $saidas;
				
				$tpl->newBlock('listacaixa');
					
				$tpl->assign('id_dep',$id_dep);
				$tpl->assign('nome',$nome);
				$tpl->assign('entradas',number_format($entradas,2,',','.'));
				$tpl->assign('saidas',number_format($saidas,2,',','.'));
				$tpl->assign('saldo2',number_format($saldo2,2,',','.'));
				
			}
			
			$tpl->newBlock('total2');
			$tpl->assign('tot_entrada2',number_format($tot_entrada2,2,',','.'));
			$tpl->assign('tot_saida2',number_format($tot_saida2,2,',','.') );
			$tpl->assign('saldo2',number_format($saldo2,2,',','.'));
			
		}
	/**************************************************************/

	$tpl->printToScreen();
?>
<?php

	error_reporting(E_ALL);
	ini_set('display_errors', 'On');

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/relatoriocontasreceberporrecebimento.htm');

	//$tpl->assignInclude('conteudo','../tpl/relatorioficha.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		//require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		$condicao  = array();
		$condicao2 = array();		
			
				
		if(isset($_REQUEST['dtadmissaoini']) and !empty($_REQUEST['dtadmissaoini'])){

			$dtadmissaoini    =  $_REQUEST['dtadmissaoini'];	

			$condicao[]  = " datapag between '".implode("-", array_reverse(explode("-", "".$dtadmissaoini."")))."' ";		
			$condicao2[]  = " dr.datapag between '".implode("-", array_reverse(explode("-", "".$dtadmissaoini."")))."' ";	
		}
				
		
		if(isset($_REQUEST['dtadmissaofin']) and !empty($_REQUEST['dtadmissaofin'])){

			$dtadmissaofin       =  $_REQUEST['dtadmissaofin'];	

			$condicao[]   = " '".implode("-", array_reverse(explode("-", "".$dtadmissaofin."")))."' ";
			$condicao2[]   = " '".implode("-", array_reverse(explode("-", "".$dtadmissaofin."")))."' ";
					
		}	
		
		if(isset($_REQUEST['cliente']) and !empty($_REQUEST['cliente'])){

			$cliente       =  $_REQUEST['cliente'];	

			$condicao[]   = " cod_cliente = '".$cliente."' ";
			$condicao2[]   = " dr.cod_cliente = '".$cliente."' ";
					
		}	
		$condicao2[]   = " dr.cod_cliente = d.cod_cliente ";	
		//$condicao2[]   = " SUBSTRING(dr.numero FROM 1 FOR 1) = '#' ";
		$where = '';

		if(count($condicao) > 0){
		
			$where = ' where'.implode('AND',$condicao);
				
		}	
		$where2 = '';

		if(count($condicao2) > 0){
		
			$where2 = ' where'.implode('AND',$condicao2);
				
		}	
		
		if(empty($_REQUEST['analisint'])){
			
			echo "<div align='center'>
					<h1>Selecione se Analitico ou Sintetico</h1>
					</div>";
								
		}else{
		
			
			if($_REQUEST['analisint'] == 'A'){
			
			//analitico
				$tpl->newBlock('analitico');
				
				$dao = new DuplicReceberDAO();
				$vet = $dao->RelatorioDuplicReceberPorRecebimento($where); 
				$num = count($vet);
					
				$valortotal    = 0;
				$vlcontritotal = 0;
				$vlpromototal  = 0;
				$vlpromo       = 0;
				$vldoca        = 0;
				$vlrpagtodin   = 0;
				$vlrpagtocart  = 0;
				for($i = 0; $i < $num; $i++){
					
					$duplic = $vet[$i];
					
					$id 		= $duplic->getCodigo();
					$datapag    = $duplic->getDataPag();
					$vencimento = $duplic->getVencimento();
					$numero     = $duplic->getNumero();
					$nomecli    = $duplic->getNomeCliente();			
					$valordoc   = $duplic->getValorDoc();
					$formpagto  = $duplic->getFormPagto();
					
					$daor = new RecebimentoDAO();
					$vetr = $daor->ListaRecebimentoPorDup($id);
					
					if(!empty($vetr)){
						/*echo "<pre>";
						print_r($vetr);
						echo "</pre>";*/
						
						foreach ($vetr as $recb) {
							if(strtoupper($recb['nome']) == 'DINHEIRO'){
								$_SESSION['FORMA'][$i]['DINHEIRO']['VALOR'] = $recb['valor'];
								$_SESSION['FORMA'][$i]['DINHEIRO']['NOME']  = "DINHEIRO";
							}else if($recb['nome'] == 'Cartão'){ 
								$_SESSION['FORMA'][$i]['CARTAO']['VALOR'] = $recb['valor'];
								$_SESSION['FORMA'][$i]['CARTAO']['NOME'] = "CARTAO";
							}else{
								$_SESSION['FORMA'][$i][$recb['nome']]['VALOR'] = $recb['valor'];
								$_SESSION['FORMA'][$i][$recb['nome']]['NOME']  = $recb['nome'];
								
							}
						}
					}

					if($formpagto == 1){						
						$vlrpagtodin = $vlrpagtodin + $valordoc;	
						$_SESSION['FORMA'][$i]['DINHEIRO']['VALOR'] = $valordoc;	
						$_SESSION['FORMA'][$i]['DINHEIRO']['NOME']  = "DINHEIRO";				
					}else if($formpagto == 2){
						$vlrpagtocart = $vlrpagtocart + $valordoc;
						$_SESSION['FORMA'][$i]['CARTAO']['VALOR'] =  $valordoc;
						$_SESSION['FORMA'][$i]['CARTAO']['NOME'] = "CARTAO";
					}
	
					$rest = $numero{0};
					$vldoca = 0;
					if($rest == '#'){
				
						$vldoca = $valordoc;
						$valordoc  = 0;
					}
					$vlpromo = 0;
					if($rest == 'P'){
				
						$vlpromo = $valordoc;
						$valordoc  = 0;
					}
					
								
					
					$valortotal = $valortotal + $valordoc;
					$vlcontritotal = $vlcontritotal + $vldoca;
					$vlpromototal  = $vlpromototal + $vlpromo;
					$tpl->newBlock('relrecebimento');	
					
					$tpl->assign('datapag',implode("/", array_reverse(explode("-", "".$datapag.""))));
					$tpl->assign('vencimento',implode("/", array_reverse(explode("-", "".$vencimento.""))));
					$tpl->assign('numero',$numero);
					$tpl->assign('nomecli',$nomecli);
					$tpl->assign('valordoc',number_format($valordoc,2,',','.'));
					$tpl->assign('vldoca',number_format($vldoca,2,',','.'));
					$tpl->assign('vlpromo',number_format($vlpromo,2,',','.'));
				}

				$valorestot = $valortotal + $vlcontritotal + $vlpromototal;	
				$totalgeral = $valortotal + $vlcontritotal + $vlpromototal;
				$tpl->newBlock('totals');
				$tpl->assign('valortotal',number_format($valortotal,2,',','.'));
				$tpl->assign('vlcontritotal',number_format($vlcontritotal,2,',','.'));
				$tpl->assign('valorestot',number_format($valorestot,2,',','.'));
				$tpl->assign('vlpromototal',number_format($vlpromototal,2,',','.'));
				$tpl->assign('totalgeral',number_format($totalgeral,2,',','.'));
				
				$tpl->assign('vlrpagtodin',number_format($vlrpagtodin,2,',','.'));
				$tpl->assign('vlrpagtocart',number_format($vlrpagtocart,2,',','.'));


				$arfr = array();
				for ($i=0; $i < count($_SESSION['FORMA']); $i++) { 
					$form = $_SESSION['FORMA'][$i];
					$arraydata = array_values(array_filter($form));					
					$xfr = "";				
					foreach ($form as $key => $value) {						
						$arfr[] = $dao->array_sort($value, 'NOME', SORT_DESC); 			
					}					
				}

				$novo     = $dao->array_sort($arfr, 'NOME', SORT_DESC);
				$xnome 	  = "";
				$xnome2   = "";
				$contador = 0;
				
				foreach ($novo as $keys => $values) {
					
					if($values['NOME'] != $xnome){
						$xnome = $values['NOME'];
						$tpl->newBlock('vtlist');
						$tpl->assign('NOME',$values['NOME']);
						if($contador > 0){
							$tpl->newBlock('vt');
							$tpl->assign('valort',number_format($valort,2,',','.'));						

						}
						$valort = 0;
					}	
					/*if($values['NOME'] != $xnome2){
						$xnome2 = $values['NOME'];						
						$tpl->newBlock('vtlist');
						$tpl->assign('NOME',$values['NOME']);

					}*/
					$valort = $valort + $values['VALOR'];

					//$tpl->newBlock('list');

					$contador++;
				}

				
				$tpl->newBlock('vt');
				$tpl->assign('valort',number_format($valort,2,',','.'));
			

				/*echo "<pre>";
				print_r($novo);
				echo "</pre>";*/

				unset($_SESSION['FORMA']);
			
				
			}else{
				
			//sintetico		
				$tpl->newBlock('sintetico');
				
				$dao = new DuplicReceberDAO();
				$vet = $dao->RelatorioDuplicReceberPorRecebimentoSintetico($where,$where2); 
				$num = count($vet);
				$valortotal 	 = 0;
				$vlcontritotal   = 0;
				$vlpromos 		 = 0;
				$vlpromostotal   = 0;
				$vlrpagtodin     = 0;
				$vlrpagtocart    = 0;
				
				for($i = 0; $i < $num; $i++){
					
					$duplic = $vet[$i];
					
				
					$nomecli     = $duplic->getNomeCliente();			
					$valordoc    = $duplic->getValorDoc();
					$valorcontri = $duplic->getValorContribuicao();		
					$dinheiro	 = $duplic->getDinheiro();
					$cheque		 = $duplic->getCheque();
					
					$vlpromos  = 0;
					if($nomecli == "Promocoes"){
						$vlpromos = $valordoc;
						$valordoc = 0;
					}
					
					$vlrpagtodin  = $vlrpagtodin + $dinheiro;
					$vlrpagtocart = $vlrpagtocart + $cheque;
					
					$valortotal    = $valortotal + $valordoc;
					$vlcontritotal = $vlcontritotal + $valorcontri;
					$vlpromostotal = $vlpromostotal + $vlpromos;
					$tpl->newBlock('relrecebimentosintetico');	
					
					$tpl->assign('nomecli',$nomecli);
					$tpl->assign('valordoc',number_format($valordoc,2,',','.'));
					$tpl->assign('valorcontri',number_format($valorcontri,2,',','.'));
					$tpl->assign('vlpromos',number_format($vlpromos,2,',','.'));
				}
				$valortotal = $valortotal - $vlcontritotal;				
				$tpl->newBlock('total');
				$tpl->assign('valortotal',number_format($valortotal,2,',','.'));
				$tpl->assign('vlcontritotal',number_format($vlcontritotal,2,',','.'));
				$tpl->assign('vlpromostotal',number_format($vlpromostotal,2,',','.'));
				
				$tpl->assign('vlrpagtodin',number_format($vlrpagtodin,2,',','.'));
				$tpl->assign('vlrpagtocart',number_format($vlrpagtocart,2,',','.'));
				
			}
							
		
		}
		

	/**************************************************************/

	$tpl->printToScreen();



?>
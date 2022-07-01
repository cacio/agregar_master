<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/relatoriobazar.htm');

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

			$condicao[]  = " m.data between '".implode("-", array_reverse(explode("-", "".$dtadmissaoini."")))."' ";		
		}
				
		if(isset($_REQUEST['dtadmissaofin']) and !empty($_REQUEST['dtadmissaofin'])){

			$dtadmissaofin     =  $_REQUEST['dtadmissaofin'];	

			$condicao[]  = " '".implode("-", array_reverse(explode("-", "".$dtadmissaofin."")))."' ";		
		}						
		$condicao[]  = " m.formpagto is not null ";
							
		$where = '';

		if(count($condicao) > 0){
		
			$where = ' where'.implode('AND',$condicao);
				
		}	
			
		
			
		$dao = new MovimentacaoDAO();
			
		if($_REQUEST['analisint'] == 'A'){
			
			$tpl->newBlock('analitico');				
			
			$vet = $dao->RelatorioBazar($where);
			$num = count($vet);
			
			$tot_valorunit  = 0;
			$tot_quantidade = 0;
			$tot_total	    = 0;
			$tot_dinheiro   = 0;
			$tot_cartao     = 0;
			$tot_misto      = 0;
			$tot_outros     = 0;
			$media			= 0;
			$tot_media		= 0;
			$dinheiro		= 0;
			$cartão			= 0;
			$misto			= 0;
			$outros			= 0;
			for($i = 0; $i < $num; $i++){
			
				$movim = $vet[$i];
				
				$numero = $movim->getNumeroControl();
				$data   = $movim->getData();
				$vlunit = $movim->getValorUnitario();
				$quanti = $movim->getQuantidade();
				$total  = $movim->getTotal();
				$tipo   = $movim->getTipo();
				$pagto	= $movim->getPagto();
				
				if($pagto == 1){
					$pagto = "DINHEIRO";
					$tot_dinheiro = $tot_dinheiro + $total;
					$dinheiro     = $total;
					$vlunit		  = 0;
				}else if($pagto == 2){
					$pagto = "CARTÃO";
					$tot_cartao = $tot_cartao + $total;
					$cartão	    = $total;
					$vlunit		  = 0;
					
				}else if($pagto == 3){
					$pagto = "MISTO";
					$tot_misto = $tot_misto + $total;
					$misto	    = $total;
					$vlunit		  = 0;
					
					$daom = new FormaMistaMovimentacoesDAO();
					$vetm = $daom->ListaFormaPorControleDinheiro($numero);
					$numm = count($vetm);
					if($numm > 0){
						$formist = $vetm[0];						
						$tipo  = $formist->getTipo();
						$valor = $formist->getValor();
						
						$dinheiro     = $valor;
						$tot_dinheiro = $tot_dinheiro + $valor;
					}
					
					$vetm2 = $daom->ListaFormaPorControleCartao($numero);
					$numm2 = count($vetm2);
					if($numm2 > 0){
					
						$formist2 = $vetm2[0];						
						$tipo2  = $formist2->getTipo();
						$valor2 = $formist2->getValor();
						
						$cartão	    = $valor2;
						$tot_cartao = $tot_cartao + $valor2;
					}
							
					
				}else if($pagto == 4){
					$pagto = "OUTROS";
					$tot_outros = $tot_outros + $total;
					$outros	    = $total;
					$vlunit		  = 0;
				}
				
				$tot_valorunit  = $tot_valorunit  + $vlunit;
				$tot_quantidade = $tot_quantidade + $quanti;
				$tot_total		= $tot_total + $total;
				$media			= $total / $quanti;
				$tot_media		= $tot_media + $media;
				
				$tpl->newBlock('listar');
				
				$tpl->assign('numero',$numero);
				$tpl->assign('data',$data);
				$tpl->assign('vlunit',number_format($vlunit,2,',','.'));
				$tpl->assign('quanti',$quanti);
				$tpl->assign('total',number_format($total,2,',','.'));	
				$tpl->assign('pagto',$pagto);
				$tpl->assign('media',number_format($media,2,',','.'));
				$tpl->assign('dinheiro',number_format($dinheiro,2,',','.'));
				$tpl->assign('cartão',number_format($cartão,2,',','.'));
				$tpl->assign('misto',number_format($misto,2,',','.'));
				$tpl->assign('outros',number_format($outros,2,',','.'));
					
				$dinheiro = 0;
				$cartão	  = 0;
				$misto	  = 0;
				$outros   = 0;
			}
			$tpl->newBlock('total');
			@$mediafinal = $tot_total / $tot_quantidade;
			$tpl->assign('tot_valorunit',number_format($tot_valorunit,2,',','.'));
			$tpl->assign('tot_quantidade',$tot_quantidade);
			$tpl->assign('tot_total',number_format($tot_total,2,',','.'));
			$tpl->assign('tot_media',number_format($mediafinal,2,',','.'));
			$tpl->assign('tot_dinheiro',number_format($tot_dinheiro,2,',','.'));
			$tpl->assign('tot_cartao',number_format($tot_cartao,2,',','.'));
			$tpl->assign('tot_misto',number_format($tot_misto,2,',','.'));
			$tpl->assign('tot_outros',number_format($tot_outros,2,',','.'));
			
			$tpl->newBlock('totaltipo');
			$total_dincart = $tot_dinheiro + $tot_cartao + $tot_outros;
			$tpl->assign('tot_dinheiro',number_format($tot_dinheiro,2,',','.'));
			$tpl->assign('tot_cartao',number_format($tot_cartao,2,',','.'));
			$tpl->assign('tot_misto',number_format($tot_misto,2,',','.'));
			$tpl->assign('tot_outros',number_format($tot_outros,2,',','.'));
			$tpl->assign('total_dincart',number_format($total_dincart,2,',','.'));
			
		}else if($_REQUEST['analisint'] == 'S'){
		
			$tpl->newBlock('sintetico');
				
			$vet = $dao->RelatorioBazarSintetico($where);
			$num = count($vet);
			
			$tot_valorunit  = 0;
			$tot_quantidade = 0;
			$tot_total	    = 0;
			$tot_dinheiro   = 0;
			$tot_cartao     = 0;
			$tot_misto      = 0;
			$tot_outros     = 0;
			$media			= 0;
			$tot_media		= 0;
			$dinheiro		= 0;
			$cartão			= 0;
			$misto			= 0;
			$outros			= 0;
			for($i = 0; $i < $num; $i++){
			
				$movim = $vet[$i];
				
				$data   = $movim->getData();
				$quanti = $movim->getQuantidade();
				$total  = $movim->getTotal();
				$pagto	= $movim->getPagto();
				$numero = $movim->getNumeroControl();
				
				if($pagto == 1){
				
					$tot_dinheiro = $tot_dinheiro + $total;
					$dinheiro     = $total; 		
				}else if($pagto == 2){
			
					$tot_cartao = $tot_cartao + $total;
					$cartão	    = $total;

					
				}else if($pagto == 3){
										
					$tot_misto = $tot_misto + $total;					
					$misto	    = $total;
										
					$daom = new FormaMistaMovimentacoesDAO();
					$vetm = $daom->ListaFormaPorControleDinheiro($numero);
					$numm = count($vetm);
					if($numm > 0){
						$formist = $vetm[0];						
						$tipo  = $formist->getTipo();
						$valor = $formist->getValor();
						
						$dinheiro     = $valor;
						$tot_dinheiro = $tot_dinheiro + $valor;
					}
					
					$vetm2 = $daom->ListaFormaPorControleCartao($numero);
					$numm2 = count($vetm2);
					if($numm2 > 0){
					
						$formist2 = $vetm2[0];						
						$tipo2  = $formist2->getTipo();
						$valor2 = $formist2->getValor();
						
						$cartão	    = $valor2;
						$tot_cartao = $tot_cartao + $valor2;
					}
					
					
				}else if($pagto == 4){
			
					$tot_outros = $tot_outros + $total;
					$outros	    = $total;

					
				}
				
				$tot_quantidade = $tot_quantidade + $quanti;
				$tot_total		= $tot_total + $total;
				$media			= $total / $quanti;
				$tot_media		= $tot_media + $media;
				
				$tpl->newBlock('listars');
							
				$tpl->assign('data',$data);				
				$tpl->assign('quanti',$quanti);
				$tpl->assign('total',number_format($total,2,',','.'));	
				$tpl->assign('pagto',$pagto);
				$tpl->assign('media',number_format($media,2,',','.'));
				$tpl->assign('dinheiro',number_format($dinheiro,2,',','.'));
				$tpl->assign('cartão',number_format($cartão,2,',','.'));
				$tpl->assign('misto',number_format($misto,2,',','.'));
				$tpl->assign('outros',number_format($outros,2,',','.'));

				$dinheiro = 0;
				$cartão	  = 0;
				$misto	  = 0;
				$outros	  = 0;
			}
			
			@$mediafinal = $tot_total / $tot_quantidade;
			$tpl->newBlock('totals');		
			$tpl->assign('tot_quantidade',$tot_quantidade);
			$tpl->assign('tot_total',number_format($tot_total,2,',','.'));
			$tpl->assign('tot_media',number_format($mediafinal,2,',','.'));
			$tpl->assign('tot_dinheiro',number_format($tot_dinheiro,2,',','.'));
			$tpl->assign('tot_cartao',number_format($tot_cartao,2,',','.'));
			$tpl->assign('tot_misto',number_format($tot_misto,2,',','.'));
			$tpl->assign('tot_outros',number_format($tot_outros,2,',','.'));

			$tpl->newBlock('totaltipos');
			$total_dincart = $tot_dinheiro + $tot_cartao + $tot_outros;
			$tpl->assign('tot_dinheiro',number_format($tot_dinheiro,2,',','.'));
			$tpl->assign('tot_cartaos',number_format($tot_cartao,2,',','.'));
			$tpl->assign('tot_misto',number_format($tot_misto,2,',','.'));
			$tpl->assign('tot_outros',number_format($tot_outros,2,',','.'));
			$tpl->assign('total_dincart',number_format($total_dincart,2,',','.'));
		}
	/**************************************************************/

	$tpl->printToScreen();



?>
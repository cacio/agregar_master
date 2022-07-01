<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/relatoriorelacaoabates.htm');
	//$tpl->assignInclude('conteudo','../tpl/relacaoabates.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		//require_once('../inc/inc.permissao.php');
		//require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$condicao = array();		

		if(isset($_REQUEST['dataini']) and !empty($_REQUEST['dataini'])){

			$dataini       =  implode("-", array_reverse(explode("/", $_REQUEST['dataini'])));					
			$condicao[]    = " n.data_emissao between '".$dataini."' ";								
		}
	
		if(isset($_REQUEST['datafin']) and !empty($_REQUEST['datafin'])){

			$datafin      =  implode("-", array_reverse(explode("/", $_REQUEST['datafin'])));	
			$condicao[]   = " '".$datafin."' ";            			
		}
		
		if(isset($_REQUEST['cnpjemp']) and !empty($_REQUEST['cnpjemp'])){

			$cnpjemp      =  $_REQUEST['cnpjemp'];	
			$condicao[]   = " e.cnpj = '".$cnpjemp."' ";            		
		}

		$where = '';
		if(count($condicao) > 0){		
			$where = ' where'.implode('AND',$condicao);				
		}


		$dao = new NotasEn1TxtDAO();
		$vet = $dao->RelatorioRelacaoAbate($where);
		$num = count($vet);
		$vl_tot_bovinos 		 = 0;
		$vl_tot_bubalino 		 = 0;
		$vl_tot_ovino    		 = 0;
		$vl_tot_compras  		 = 0;
		$vl_geral_bovinos 		 = 0;
		$vl_geral_macho_bovinos  = 0;
		$vl_geral_femea_bovinos  = 0;
		$vl_geral_macho_bubalino = 0;
		$vl_geral_femea_bubalino = 0;
		$vl_geral_bubalino       = 0;
		$vl_geral_macho_ovinos   = 0;
		$vl_geral_femea_ovinos   = 0;
		$vl_geral_ovinos         = 0;
		$vl_geral_compras        = 0;

		for($i =0; $i < $num; $i++){

			$notasen1 			    = $vet[$i];

			$cnpj 		     		= $notasen1->getCnpjEmp();
			$razao_social    		= $notasen1->getRazaoSocialEmpresa();
			$cidade          		= $notasen1->getCidadeEmpresa();
			$macho_bovinos  		= $notasen1->getMachoBovino();
			$femea_bovinos  		= $notasen1->getFemaBovino();
			$macho_bubalinos 		= $notasen1->getMachoBubalino();
			$femea_bubalinos 		= $notasen1->getFemeaBubalino();
			$macho_ovinos    		= $notasen1->getMachoOvinos();
			$femea_ovinos    		= $notasen1->getFemeaOvinos();
			$valor_total_rendimento = $notasen1->getValorTotalRendimento();
			$valor_total_vivo       = $notasen1->getValorTotalVivo();

			$vl_tot_bovinos  = $macho_bovinos + $femea_bovinos;
			$vl_tot_bubalino = $macho_bubalinos + $femea_bubalinos;
			$vl_tot_ovino    = $macho_ovinos + $femea_ovinos;
			$vl_tot_compras  = $valor_total_rendimento + $valor_total_vivo;

			$vl_geral_macho_bovinos = $vl_geral_macho_bovinos + $macho_bovinos;
			$vl_geral_femea_bovinos = $vl_geral_femea_bovinos + $femea_bovinos;
			$vl_geral_bovinos 		= $vl_geral_bovinos + $vl_tot_bovinos; 

			$vl_geral_macho_bubalino= $vl_geral_macho_bubalino + $macho_bubalinos;
			$vl_geral_femea_bubalino= $vl_geral_femea_bubalino + $femea_bubalinos;
			$vl_geral_bubalino      = $vl_geral_bubalino + $vl_tot_bubalino;

			$vl_geral_macho_ovinos  = $vl_geral_macho_ovinos + $macho_ovinos;
			$vl_geral_femea_ovinos  = $vl_geral_femea_ovinos + $femea_ovinos;
			$vl_geral_ovinos 		= $vl_geral_ovinos + $vl_tot_ovino;

			$vl_geral_compras       = $vl_geral_compras + $vl_tot_compras;


			$tpl->newBlock('lista');

			$tpl->assign('cnpj',$cnpj);
			$tpl->assign('razao_social',$razao_social);
			$tpl->assign('cidade',$cidade);
			$tpl->assign('macho_bovinos',$macho_bovinos);
			$tpl->assign('femea_bovinos',$femea_bovinos);
			$tpl->assign('vl_tot_bovinos',$vl_tot_bovinos);
			$tpl->assign('macho_bubalinos',$macho_bubalinos);
			$tpl->assign('femea_bubalinos',$femea_bubalinos);
			$tpl->assign('vl_tot_bubalino',$vl_tot_bubalino);
			$tpl->assign('macho_ovinos',$macho_ovinos);
			$tpl->assign('femea_ovinos',$femea_ovinos);
			$tpl->assign('vl_tot_ovino',$vl_tot_ovino);
			$tpl->assign('valor_total_rendimento',number_format($valor_total_rendimento,2,',','.'));
			$tpl->assign('valor_total_vivo',number_format($valor_total_vivo,2,',','.'));
			$tpl->assign('vl_tot_compras',number_format($vl_tot_compras,2,',','.'));

		}

			$tpl->newBlock('total');

			$tpl->assign('vl_geral_macho_bovinos',$vl_geral_macho_bovinos);
			$tpl->assign('vl_geral_femea_bovinos',$vl_geral_femea_bovinos);
			$tpl->assign('vl_geral_bovinos',$vl_geral_bovinos);
			
			$tpl->assign('vl_geral_macho_bubalino',$vl_geral_macho_bubalino);
			$tpl->assign('vl_geral_femea_bubalino',$vl_geral_femea_bubalino);
			$tpl->assign('vl_geral_bubalino',$vl_geral_bubalino);

			$tpl->assign('vl_geral_macho_ovinos',$vl_geral_macho_ovinos);
			$tpl->assign('vl_geral_femea_ovinos',$vl_geral_femea_ovinos);
			$tpl->assign('vl_geral_ovinos',$vl_geral_ovinos);

			$tpl->assign('vl_geral_compras',number_format($vl_geral_compras,2,',','.'));

	/**************************************************************/
	$tpl->printToScreen();
		
?>
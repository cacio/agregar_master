<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/relatoriosocioemdia.htm');

	//$tpl->assignInclude('conteudo','../tpl/relatorioficha.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		//require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		$condicao  = array();
		$condicao2 = array();		
						
				
		if(isset($_REQUEST['dtini']) and !empty($_REQUEST['dtini'])){

			$dtini    =  $_REQUEST['dtini'];	

			$condicao[]  = " d.vencimento >= '".implode("-", array_reverse(explode("-", "".$dtini."")))."' ";		
		}
				
		if(isset($_REQUEST['dtfim']) and !empty($_REQUEST['dtfim'])){

			$dtfim     =  $_REQUEST['dtfim'];	

			$condicao[]  = " d.vencimento <= '".implode("-", array_reverse(explode("-", "".$dtfim."")))."' ";		
		}						
		
			
		if(isset($_REQUEST['categoria']) and !empty($_REQUEST['categoria'])){

			$categoria       =  $_REQUEST['categoria'];	

			$condicao[]   = " f.codcategoria = '".$categoria."' ";
					
		}
		
		$condicao[]   = " d.datapag <> '0000-00-00' ";
			
		$where = '';

		if(count($condicao) > 0){
		
			$where = ' where'.implode('AND',$condicao);
				
		}	
			
		
		$dao = new DuplicReceberDAO();
		$vet = $dao->RelatorioSociosemdia($where);
		$num = count($vet);

		for($i = 0; $i < $num; $i++){
			
			
			$duplic = $vet[$i];
			
			$nome 		= $duplic->getNome();
			$datapag	= $duplic->getDataPag();
			$valordoc   = $duplic->getValorPago();
			
			$tpl->newBlock('lista');	
					
			$tpl->assign('nome',$nome);
			$tpl->assign('datapag',implode("/", array_reverse(explode("-", "".$datapag.""))));
			$tpl->assign('valordoc',number_format($valordoc,2,',','.'));
		}


	/**************************************************************/

	$tpl->printToScreen();



?>
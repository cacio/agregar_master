<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/relatoriovencidos.htm');

	//$tpl->assignInclude('conteudo','../tpl/relatoriovencidos.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		//require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);
		$tpl->assign('empresa','ESPIRITISMO');
		
		$condicao    = array();	
		
		if(isset($_REQUEST['dtadmissaoini']) and !empty($_REQUEST['dtadmissaoini'])){

			$dtadmissaoini =  $_REQUEST['dtadmissaoini'];			
			$condicao[]    = " d.vencimento <= '".implode("-", array_reverse(explode("-", "".$dtadmissaoini."")))."' ";
				
		}	
		
			$condicao[]    = " (d.datapag is null or d.datapag = '0000-00-00' or d.datapag = '') ";	
		
		$where = '';

		if(count($condicao) > 0){
		
			$where = ' where'.implode('AND',$condicao);
				
		}
		
		$dao = new DuplicReceberDAO();
		$vet = $dao->RelatorioVencidos($where);
		$num = count($vet);
		
		
		for($i = 0; $i < $num; $i++){
			
			$duplic = $vet[$i];
			
			$vencimento = $duplic->getVencimento();
			$id			= $duplic->getCodigoCliente();
			$nome		= $duplic->getNomeCliente();	
			$categoria	= $duplic->getCategoria();	
			$email		= $duplic->getEmail();
			$telefone	= $duplic->getTelefone();
			
			$tpl->newBlock('listar');	
					
			$tpl->assign('vencimento',implode("/", array_reverse(explode("-", "".$vencimento.""))));
			$tpl->assign('id',$id);
			$tpl->assign('nome',$nome);	
			$tpl->assign('categoria',$categoria);		
			$tpl->assign('email',$email);
			$tpl->assign('telefone',$telefone);
		}
		
		
		
			

	/**************************************************************/

	$tpl->printToScreen();



?>
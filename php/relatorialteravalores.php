<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/relatorialteravalores.htm');

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

			$condicao[]  = " d.vencimento >= '".implode("-", array_reverse(explode("-", "".$dtadmissaoini."")))."' ";		
		}
				
		if(isset($_REQUEST['valoratu']) and !empty($_REQUEST['valoratu'])){

			$valoratu    =  $_REQUEST['valoratu'];	

			$condicao[]  = " d.valordoc = '".str_replace(',', '.', str_replace('.', '', $valoratu))."' ";		
		}						
		
		if(isset($_REQUEST['cliente']) and !empty($_REQUEST['cliente'])){

			$cliente    =  $_REQUEST['cliente'];	

			$condicao[]  = " d.cod_cliente = '".$cliente."' ";		
		}
		
		$condicao[]  = " d.datapag = '0000-00-00' ";
			
		$where = '';

		if(count($condicao) > 0){
		
			$where = ' where'.implode('AND',$condicao);
				
		}	
			

		$dao = new DuplicReceberDAO();
		$vet = $dao->RelatoAlteracaoValores($where);
		$num = count($vet);

		for($i = 0; $i < $num; $i++){
			
			$duplic		= $vet[$i];	

			$id   		 = $duplic->getCodigo();
			$nome 		 = $duplic->getNome();
			$numero 	 = $duplic->getNumero();
			$vencimento  = $duplic->getVencimento();
			$valordoc	 = $duplic->getValorPago();
			$valorcorrig = str_replace(',', '.', str_replace('.', '', $_REQUEST['valorcorigido']));
			
			$tpl->newBlock('listar');	
					
			$tpl->assign('id',$id);
			$tpl->assign('nome',$nome);
			$tpl->assign('numero',$numero);
			$tpl->assign('vencimento',implode("/", array_reverse(explode("-", "".$vencimento.""))));
			$tpl->assign('valordoc',number_format($valordoc,2,',','.'));
			$tpl->assign('valorcorrig',number_format($valorcorrig,2,',','.'));
			
			$dup = new DuplicReceber();
			
			$dup->setCodigo($id);
			$dup->setValorDoc($valorcorrig);
			
			$dao->alterarValores($dup);
			
			$tpl->assign('ok','fas fa-check');
		}
		
		

	/**************************************************************/

	$tpl->printToScreen();



?>
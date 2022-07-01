<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/Listar-movimentacao.htm');

	$tpl->prepare();

	

	/**************************************************************/	
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

		$daom 	 = new MovimentacaoDAO();
		$vetm 	 = $daom->proximoid();
		$prox 	 = $vetm[0];		
		$proxcod = $prox->getProxid();
		$tpl->assign('proxcod',$proxcod);
		
		$dao =  new MovimentacaoDAO();
		$vet = $dao->ListaMovimentacao2();		
		$num = count($vet);
		
		
		for($i = 0; $i < $num; $i++){
			
			$movim = $vet[$i];
			
			
			$numero = $movim->getNumeroControl();
			$data 	= $movim->getData();
			$vlunit = $movim->getValorUnitario();
			$quanti = $movim->getQuantidade();
			$total	= $movim->getTotal();
			$tipo   = $movim->getTipo();
			
			if($tipo == 'E'){
				$tipo = "ENTRADA";
			}else if($tipo == 'S'){
				$tipo = "SAIDA";
			}
			
			$tpl->newBlock('listar');

			$tpl->assign('numero',$numero);
			$tpl->assign('data',implode("/", array_reverse(explode("-", "".$data.""))));
			$tpl->assign('vlunit',number_format($vlunit,2,',','.'));
			$tpl->assign('quanti',$quanti);
			$tpl->assign('total',number_format($total,2,',','.'));
			$tpl->assign('tipo',$tipo);
			
			
		}
		

	/**************************************************************/

	$tpl->printToScreen();



?>
<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/cadastro-movimentacao.htm');

	$tpl->prepare();

	

	/**************************************************************/	
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

		$daom 	 = new MovimentacaoDAO();
		$vetm 	 = $daom->proximoid();
		$prox 	 = $vetm[0];		
		$proxcod = $prox->getProxid();
		
		$vetm2 	  = $daom->proximoid2();
		$prox2 	  = $vetm2[0];		
		$proxcod2 = $prox2->getProxid();
		
		
		$tpl->assign('proxcod2',$proxcod2);
		$tpl->assign('proxcod',$proxcod);
		$tpl->assign('data',date('d/m/Y'));		
				
		$dao = new ProdutosDAO();
		$vet = $dao->ListaProdutos();
		$num = count($vet);		
		 
		for($i = 0; $i < $num; $i++){
		
			$prod = $vet[$i];
				
				
			$codigo = $prod->getCodigo();
			$nome   = $prod->getNome();				
			
			$tpl->newBlock('listarprod');

			$tpl->assign('codigo',$codigo);
			$tpl->assign('nome',$nome);

			
		}
		
		

	/**************************************************************/

	$tpl->printToScreen();



?>
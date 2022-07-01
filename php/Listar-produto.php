<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/Listar-produto.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		
		$dao = new ProdutosDAO();
		$vet = $dao->ListaProdutos();
		$num = count($vet);
		
		 
		for($i = 0; $i < $num; $i++){
		
				$prod = $vet[$i];
					
					
				$codigo = $prod->getCodigo();
				$nome   = $prod->getNome();				
				
				$tpl->newBlock('listar');

				$tpl->assign('codigo',$codigo);
				$tpl->assign('nome',$nome);

			if($_SESSION['idsys'] == 1){
				$tpl->newBlock('per');
				$tpl->assign('codigo',$codigo);
			}	
		}	
		

	/**************************************************************/

	$tpl->printToScreen();



?>
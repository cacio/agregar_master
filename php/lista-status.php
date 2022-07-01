<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/lista-status.htm');

	$tpl->prepare();

	

	/**************************************************************/

		

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.permissao.php');
		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		

		$daos = new StatusDAO();
		$vets = $daos->ListaStatus();
		$nums = count($vets);
		
		for($i = 0; $i < $nums; $i++){
			
			$stat = $vets[$i];		
			
			$codigo = $stat->getCodigo();
			$nome   = $stat->getNome();
			
			$tpl->newBlock('liststatus');
			
			
			$tpl->assign('cod',$codigo);
			$tpl->assign('nome',$nome);
			
		}
		
	/**************************************************************/

	$tpl->printToScreen();



?>
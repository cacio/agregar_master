<?php

	

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/postolivros.htm');
	$tpl->prepare();

	

	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

		$dao    = new PostoLivroDAO();
		$vet  	=  $dao->proximoid();
		$prox 	=  $vet[0];
		$codigo =  $prox->getProximoId();
		$tpl->assign('codigo',$codigo);
		
		

	/**************************************************************/

	$tpl->printToScreen();

?>
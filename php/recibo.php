<?php
	require_once('../inc/inc.autoload.php');	

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/recibo.htm');
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
		
		

	/**************************************************************/

	$tpl->printToScreen();

?>
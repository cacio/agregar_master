<?php
	
	require_once('../inc/inc.autoload.php');
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/cadnotasmanuais.htm');
	$tpl->prepare();
	
	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		$tpl->assign('dthj',date('d/m/Y H:m:s'));

		

	/**************************************************************/

	$tpl->printToScreen();

?>
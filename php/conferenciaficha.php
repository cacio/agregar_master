<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/conferenciaficha.htm');
	$tpl->prepare();

	

	/**************************************************************/
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		$tpl->assign('empresa','ESPIRITISMO');
		

		
		

	/**************************************************************/

	$tpl->printToScreen();



?>
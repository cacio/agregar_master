<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/cadastro-status.htm');

	$tpl->prepare();

	

	/**************************************************************/

		

		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');
		//require_once('../inc/inc.permissao.php');
		$tpl->assign('log',$_SESSION['login']);

	

	

	/**************************************************************/

	$tpl->printToScreen();



?>
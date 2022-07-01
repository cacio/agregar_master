<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/cad-receber.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		//require_once('../inc/inc.permissao.php');
		
		$tpl->assign('log',$_SESSION['login']);

		$tpl->assign('dtemis',date('d-m-Y'));		
		
			
		
	/**************************************************************/

	$tpl->printToScreen();



?>
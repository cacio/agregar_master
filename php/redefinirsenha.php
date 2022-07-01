<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/redefinirsenha.htm');	
	$tpl->prepare();
	
	/**************************************************************/
		
		
		if(!empty($_REQUEST['token'])){

			$token  = $_REQUEST['token'];	

			$tpl->assign('token',$token);						

		}else{

			$destino = 'login.php';
			header('Location:'.$destino);

		}		
			
	
	/**************************************************************/
	$tpl->printToScreen();
?>
<?php
	session_start();
	include_once('../inc/inc.autoload.php');
	$tpl = new TemplatePower('../tpl/loginrestrito.htm');
	$tpl->prepare();
	
	
	/**************************************************************/

	$pathFile      = '../arquivos/config.json';
	if(file_exists($pathFile)){
		$configJson    = file_get_contents($pathFile);
		$installConfig = json_decode($configJson);
								
		$titulo        = isset($installConfig->tela->titulo) ? $installConfig->tela->titulo : '';        
		$corpo         = isset($installConfig->tela->corpo) ? $installConfig->tela->corpo : '';
		
		$tpl->assign('titulo',$titulo);
		$tpl->assign('corpo',html_entity_decode($corpo));
	}
		
	if(!empty($_SESSION['inf'])){
		$tpl->newBlock('info');
		$tpl->assign('alerta',$_SESSION['inf']);
		unset($_SESSION['inf']);
	}
		
		
	
	
	/**************************************************************/
	
	$tpl->printToScreen();
?>
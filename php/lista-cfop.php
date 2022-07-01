<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/lista-cfop.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$dao = new CfopDAO();
		$vet = $dao->ListaCfopVinculado($_SESSION['cnpj']);		
		$num = count($vet);
		
		for($i = 0; $i < $num; $i++){
			
			$nat	   = $vet[$i];
			
			$cod 	   = $nat->getCodigo();
			$nom 	   = $nat->getNome();
			$gera	   = $nat->getGeraAgregar();
			$vinculado = $nat->getVinculado();						
			$selecione = "";
			$selecione2 = "";
			$tpl->newBlock('lista');
			
			if($gera == 1){
				$selecione = "selected";
			}else if($gera == 2){
				$selecione2 = "selected";
			}
			
			$tpl->assign('cod',$cod);
			$tpl->assign('nom',$nom);
			$tpl->assign('gera',$gera);
			$tpl->assign('vinculado',$vinculado);
			$tpl->assign('selecione',$selecione);
			$tpl->assign('selecione2',$selecione2);
		}
		

	/**************************************************************/
	$tpl->printToScreen();
?>
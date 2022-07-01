<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/alterar-status.htm');

	$tpl->prepare();

	

	/**************************************************************/

		

		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');
		//require_once('../inc/inc.permissao.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$cod_stat = $_REQUEST['cod_stat'];
		
		if(!isset($_REQUEST['cod_stat']) and empty($_REQUEST['cod_stat'])){
			header('Location:admin.php');
		}
		
		$dao = new StatusDAO();
		$vet = $dao->ListaStatusUm($cod_stat);
		$num = count($vet);
					
		$stat = $vet[0];	
		
		$cod  = $stat->getCodigo();
		$nome = $stat->getNome();
						
		$tpl->assign('cod',$cod);
		$tpl->assign('nome',htmlentities($nome,ENT_QUOTES));
			
		

	

	/**************************************************************/

	$tpl->printToScreen();



?>
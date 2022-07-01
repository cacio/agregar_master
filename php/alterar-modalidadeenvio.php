<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/alterar-modalidadeenvio.htm');

	$tpl->prepare();

	

	/**************************************************************/

		

		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');
		//require_once('../inc/inc.permissao.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$cod_modenvio = $_REQUEST['cod_modenvio'];
		
		if(!isset($_REQUEST['cod_modenvio']) and empty($_REQUEST['cod_modenvio'])){
			header('Location:admin.php');
		}
		
		$dao = new ModalidadeEnvDAO();
		$vet = $dao->ListaModalidadeEnvUm($cod_modenvio);
		$num = count($vet);
					
		$modenv = $vet[0];	
		
		$cod  = $modenv->getCodigo();
		$nome = $modenv->getNome();
						
		$tpl->assign('cod',$cod);
		$tpl->assign('nome',htmlentities($nome,ENT_QUOTES));
			
		

	

	/**************************************************************/

	$tpl->printToScreen();



?>
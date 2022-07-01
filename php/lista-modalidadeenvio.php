<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/lista-modalidadeenvio.htm');

	$tpl->prepare();

	

	/**************************************************************/

		

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.permissao.php');
		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		

		$dao = new ModalidadeEnvDAO();
		$vet = $dao->ListaModalidadeEnv();
		$num = count($vet);
		
		for($i = 0; $i < $num; $i++){
			
			$modenv = $vet[$i];	
			
			$cod  = $modenv->getCodigo();
			$nome = $modenv->getNome();
			
			$tpl->newBlock('listamodenv');
			
			
			$tpl->assign('cod',$cod);
			$tpl->assign('nome',$nome);
			
		}

	/**************************************************************/

	$tpl->printToScreen();



?>
<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/lista-modalidade.htm');

	$tpl->prepare();

	

	/**************************************************************/

		

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.permissao.php');
		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		

		$dao = new ModalidadeDAO();
		$vet = $dao->ListaModalidade();
		$num = count($vet);
		
		for($i = 0; $i < $num; $i++){
			
			$mod = $vet[$i];	
			
			$cod  = $mod->getCodigo();
			$nome = $mod->getNome();
			
			$tpl->newBlock('listamod');
			
			
			$tpl->assign('cod',$cod);
			$tpl->assign('nome',$nome);
			
		}

	/**************************************************************/

	$tpl->printToScreen();



?>
<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/atualizar-espe.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		($_REQUEST['id']) ? $id  = $_REQUEST['id']   :false;

		$dao = new EspeDAO();
		$vet = $dao->ListaEspUm($id);
		$num = count($vet);
				
		$esp = $vet[0];
			
			
		$codigo = $esp->getCodigo();
		$nome   = $esp->getNome();				
		
		
		$tpl->assign('codigo',$codigo);
		$tpl->assign('nome',$nome);

				
	

		
		

	/**************************************************************/

	$tpl->printToScreen();



?>
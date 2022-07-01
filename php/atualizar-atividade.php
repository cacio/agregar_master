<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/atualizar-atividade.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		($_REQUEST['id']) ? $id  = $_REQUEST['id']   :false;

		$dao = new AtividadesDAO();
		$vet = $dao->ListaAtividadeUm($id);
		$num = count($vet);
				
		$cat = $vet[0];
			
			
		$codigo = $cat->getCodigo();
		$nome   = $cat->getNome();				
		
		
		$tpl->assign('codigo',$codigo);
		$tpl->assign('nome',$nome);

				
	

		
		

	/**************************************************************/

	$tpl->printToScreen();



?>
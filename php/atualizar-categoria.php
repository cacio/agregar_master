<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/atualizar-categoria.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		($_REQUEST['id']) ? $id  = $_REQUEST['id']   :false;

		$dao = new CategoriaDAO();
		$vet = $dao->ListaCategoriaUm($id);
		$num = count($vet);
				
		$cat = $vet[0];
			
			
		$codigo = $cat->getCodigo();
		$nome   = $cat->getNome();				
		
		
		$tpl->assign('codigo',$codigo);
		$tpl->assign('nome',$nome);

				
	

		
		

	/**************************************************************/

	$tpl->printToScreen();



?>
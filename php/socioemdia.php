<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/socioemdia.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);
		$tpl->assign('empresa','ESPIRITISMO');
		

		$dao = new CategoriaDAO();
		$vet = $dao->ListaCategoria();
		$num = count($vet);
		
		 
		for($i = 0; $i < $num; $i++){
		
				$cat = $vet[$i];
					
					
				$codigo = $cat->getCodigo();
				$nome   = $cat->getNome();				
				
				$tpl->newBlock('categoria');

				$tpl->assign('codigo',$codigo);
				$tpl->assign('nome',$nome);

				
		}
		

	/**************************************************************/

	$tpl->printToScreen();



?>
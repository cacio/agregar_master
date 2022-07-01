<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/Listar-espe.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		
		$dao = new EspeDAO();
		$vet = $dao->ListaEspe();
		$num = count($vet);
		
		 
		for($i = 0; $i < $num; $i++){
		
				$esp = $vet[$i];
					
					
				$codigo = $esp->getCodigo();
				$nome   = $esp->getNome();				
				
				$tpl->newBlock('espe');

				$tpl->assign('codigo',$codigo);
				$tpl->assign('nome',$nome);

				if($_SESSION['idsys'] == 1){
					$tpl->newBlock('per');
				}
		}	
		

	/**************************************************************/

	$tpl->printToScreen();



?>
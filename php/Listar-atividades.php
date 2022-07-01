<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/Listar-atividades.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		
		$dao = new AtividadesDAO();
		$vet = $dao->ListaAtividade();
		$num = count($vet);
		
		 
		for($i = 0; $i < $num; $i++){
		
				$atv = $vet[$i];
					
					
				$codigo = $atv->getCodigo();
				$nome   = $atv->getNome();				
				
				$tpl->newBlock('atividade');

				$tpl->assign('codigo',$codigo);
				$tpl->assign('nome',$nome);

			if($_SESSION['idsys'] == 1){
				$tpl->newBlock('per');
			}	
		}	
		

	/**************************************************************/

	$tpl->printToScreen();



?>
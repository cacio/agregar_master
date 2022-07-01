<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/lista-grupo.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		
		$dao = new GrupoDAO();
		$vet = $dao->ListaGrupo();
		$num = count($vet);		
		 
		for($i = 0; $i < $num; $i++){
		
				$grup = $vet[$i];
					
					
				$codigo = $grup->getCodigo();
				$nome   = $grup->getNome();				
				
				$tpl->newBlock('listagrupo');

				$tpl->assign('codigo',$codigo);
				$tpl->assign('nome',$nome);

			if($_SESSION['idsys'] == 1){
				$tpl->newBlock('per');
				$tpl->assign('codigo',$codigo);
			}	
		}	
		

	/**************************************************************/

	$tpl->printToScreen();



?>
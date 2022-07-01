<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/cadastro-caixa.htm');
	$tpl->prepare();

	

	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

		
		$dao = new DepartamentoDAO();
		$vet = $dao->ListaDepartamento();
		$num = count($vet);		
		 
		for($i = 0; $i < $num; $i++){
		
				$dep = $vet[$i];
					
					
				$codigo = $dep->getCodigo();
				$nome   = $dep->getNome();				
				
				$tpl->newBlock('listadepartamento');

				$tpl->assign('codigo',$codigo);
				$tpl->assign('nome',$nome);
			
		}		

	/**************************************************************/

	$tpl->printToScreen();
?>
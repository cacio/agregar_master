<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/atualizar-grupo.htm');
	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);
		
		if(!empty($_REQUEST['id'])){
			
			$id     = $_REQUEST['id'];
			$dao 	= new GrupoDAO();
			$vet 	= $dao->ListaGrupoUm($id);
			$num	= count($vet);

			if($num > 0){

				$group = $vet[0];
				
				$nome   = $group->getNome();
				$codigo = $group->getCodigo();
				
				$tpl->assign('codigo',$codigo);
				$tpl->assign('nome',$nome);
				
			}else{
				header('Location:lista-grupo.php');
			}	
		}else{
			header('Location:lista-grupo.php');
		}

	/**************************************************************/

	$tpl->printToScreen();



?>
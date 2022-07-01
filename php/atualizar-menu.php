<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/atualizar-menu.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		($_REQUEST['id'])  ? $id = $_REQUEST['id']     :false;
		
		$dao = new MenuDAO();
	
		$vet = $dao->listamenuUm($id);

		$num = count($vet);
		
		
			
		$user = $vet[0];
		
		$cod    = $user->getCodigo();
		$nom    = $user->getNome();
		$iduser = $user->getIdusuario();
		$link   = $user->getLink();
		$icone  = $user->getIcone();
		
		if($icone == 'glyphicon-th'){
			$icone = 'selected';
			$tpl->assign('icone',$icone);
		}else if($icone == 'glyphicon-plus-sign'){
			$icone2 = 'selected';
			$tpl->assign('icone2',$icone2);
		}if($icone == 'glyphicon-th-list'){
			$icone3 = 'selected';
			$tpl->assign('icone3',$icone3);
		}if($icone == 'glyphicon-list-alt'){
			$icone4 = 'selected';
			$tpl->assign('icone4',$icone4);
		}
		

		$tpl->assign('cod',$cod);
		$tpl->assign('menu',$nom);
		$tpl->assign('link',$link);
		$tpl->assign('icone',$icone);
			
			

	
	/**************************************************************/
	$tpl->printToScreen();

?>
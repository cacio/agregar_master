<?php
	//require_once('../php/geral_config.php');
	$tpl->assign('log',$_SESSION['login']);
	$tpl->assign('idemp',$_SESSION['id_emp']);

	if($_SESSION['idsys'] == 1){
		$tpl->assign('adm','adm');
	}

	$dao = new MenuDAO();
	$vet = $dao->listamemupousuario($_SESSION['coduser'],$_SESSION['idsys']);
	$num = count($vet);

	for($i = 0 ; $i < $num; $i++){

		$user = $vet[$i];

		$cod    = $user->getCodigo();
		$nom    = $user->getNome();
		$iduser = $user->getIdusuario();
		$link   = $user->getLink();
		$icone  = $user->getIcone();
		$idtipo = $user->getNumTp();
		if($idtipo > 0){
			$tpl->newBlock('menu');

			$tpl->assign('menu',$nom);
			$tpl->assign('id',$cod);
			$tpl->assign('link',$link);
			$tpl->assign('icone',$icone);


			$daosub = new SubmenuDAO();
			$vetsub = $daosub->listasubmenuporusuario($_SESSION['coduser'],$cod);
			$numsub = count($vetsub);

			for($y=0; $y < $numsub; $y++){

				$sub = $vetsub[$y];

				$cods    = $sub->getCodigo(); 	
				$noms    = $sub->getNome();
				$idusers = $sub->getIdusuario();
				$idmenus = $sub->getIdmenu();		
				$links   = $sub->getLink();
				$icones  = $sub->getIcone();					

				$tpl->newBlock('submenu');			

				$tpl->assign('submenu',$noms);
				$tpl->assign('idsub',$cods);
				$tpl->assign('links',$links);
				$tpl->assign('icones',$icones);
			}

					
		}

	$tpl->gotoBlock('_ROOT');

}

?>
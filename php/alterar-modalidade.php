<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/alterar-modalidade.htm');

	$tpl->prepare();

	

	/**************************************************************/

		

		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');
		//require_once('../inc/inc.permissao.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$cod_mod = $_REQUEST['cod_mod'];
		
		if(!isset($_REQUEST['cod_mod']) and empty($_REQUEST['cod_mod'])){
			header('Location:admin.php');
		}
		
		$dao = new ModalidadeDAO();
		$vet = $dao->ListaModalidadeUm($cod_mod);
		$num = count($vet);
					
		$mod = $vet[0];	
		
		$cod  = $mod->getCodigo();
		$nome = $mod->getNome();
						
		$tpl->assign('cod',$cod);
		$tpl->assign('nome',htmlentities($nome,ENT_QUOTES));
			
		

	

	/**************************************************************/

	$tpl->printToScreen();



?>
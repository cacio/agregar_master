<?php
	
	require_once('../inc/inc.autoload.php');
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/notasmanuais.htm');
	$tpl->prepare();
	
	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		$tpl->assign('dthj',date('d/m/Y H:m:s'));
		$tpl->assign('cnpjemp',$_SESSION['cnpj']);
	
		if(!empty($_REQUEST['act'])){
			$act = $_REQUEST['act'];
			if($act == 'alteracao'){
				$tpl->assign('act','alteracanotamanual');
				$tpl->assign('tipo','Alteração');
				$tpl->newBlock('cfopalteracao');
			}else if($act == 'lancamento'){
				$tpl->assign('act','inserirnotamanual');
				$tpl->assign('tipo','Lançamento');
				$tpl->newBlock('cfopamanual');
			}else{
				header('Location:admin.php');
			}

		}else{
			
		}
		


	/**************************************************************/

	$tpl->printToScreen();

?>
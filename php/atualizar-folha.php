<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/atualizar-folha.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$id = $_REQUEST['id'];
			
		$dao = new 	FolhaTxtDAO();
		$vet = $dao->ListaFolhaEmpresasUm($_SESSION["cnpj"],$id);
			
		$folhatxt = $vet[0];	
			
		$codigo		= $folhatxt->getCodigo();
		$datapag    = $folhatxt->getDataPag();
		$numfuncio  = $folhatxt->getNumFuncionario();
		$valorfolha = $folhatxt->getValorFolha();
		
		$tpl->assign('cod',$id);
		$tpl->assign('numfuncio',$numfuncio);
		$tpl->assign('valorfolha',number_format($valorfolha,2,',','.'));
		$tpl->assign('datapag',date('m/Y',strtotime($datapag)));
		
					
	/**************************************************************/
	$tpl->printToScreen();

?>
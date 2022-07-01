<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/listar-folha.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
		
		$dao = new FolhaTxtDAO();
		$vet = $dao->ListaFolhaEmpresas($_SESSION["cnpj"]);
		$num = count($vet);		
		
		for($i = 0; $i <  $num; $i++){
			
			$folhatxt  = $vet[$i];
			
			$cod	    = $folhatxt->getCodigo();
			$datapag    = $folhatxt->getDataPag();
			$numfuncio  = $folhatxt->getNumFuncionario();
			$valorfolha = $folhatxt->getValorFolha();
			
			
			$tpl->newBlock('listar');
			
			$tpl->assign('cod',$cod);
			$tpl->assign('numfuncio',$numfuncio);
			$tpl->assign('valorfolha',number_format($valorfolha,2,',','.'));
			$tpl->assign('datapag',date('m/Y',strtotime($datapag)));
		}
		
		
	/**************************************************************/
	$tpl->printToScreen();

?>
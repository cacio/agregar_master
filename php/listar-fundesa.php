<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/listar-fundesa.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
		
		$dao = new FundesaDAO();
		$vet = $dao->ListaFundesaEmpresas($_SESSION["cnpj"]);
		$num = count($vet);		
		
		for($i = 0; $i <  $num; $i++){
			
			$fun 	   = $vet[$i];
			
			$cod	   = $fun->getCodigo();
			$competenc = $fun->getCompetenc();
			$numero	   = $fun->getNumero();
			$valorpago = $fun->getValorPago();
			$datapag   = $fun->getDataPago();
			
			
			$tpl->newBlock('listar');
			
			$tpl->assign('cod',$cod);
			$tpl->assign('competenc',$competenc);
			$tpl->assign('numero',$numero);
			$tpl->assign('valorpago',number_format($valorpago,2,',','.'));
			$tpl->assign('datapag',date('d/m/Y',strtotime($datapag)));
		}
		
		
	/**************************************************************/
	$tpl->printToScreen();

?>
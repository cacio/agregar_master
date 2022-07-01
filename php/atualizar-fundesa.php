<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/atualizar-fundesa.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$id = $_REQUEST['id'];
		
		$dao = new FundesaDAO();
		$vet = $dao->ListaFundesaEmpresasUm($_SESSION["cnpj"],$id);
		$num = count($vet);		
					
		$fun 	   = $vet[0];
		
		$cod	   = $fun->getCodigo();
		$competenc = $fun->getCompetenc();
		$numero	   = $fun->getNumero();
		$valorpago = $fun->getValorPago();
		$datapag   = $fun->getDataPago();
		
		
		$tpl->assign('cod',$cod);
		$tpl->assign('competenc',$competenc);
		$tpl->assign('numero',$numero);
		$tpl->assign('valorpago',number_format($valorpago,2,',','.'));
		$tpl->assign('datapag',date('d/m/Y',strtotime($datapag)));
	
				
	
	/**************************************************************/
	$tpl->printToScreen();

?>
<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/atualizar-guiaicms.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$id  = $_REQUEST['id'];	
		
		$dao = new GuiaicmsDAO();
		$vet = $dao->ListaGuiaicmsEmpresasUm($_SESSION["cnpj"],$id);
		$num = count($vet);
		
		$guia = $vet[0];
		
		$cod	   = $guia->getCodigo();
		$competenc = $guia->getCompetenc();
		$numero	   = $guia->getNumero();
		$codigo    = $guia->getCodigoGuia();
		$valorpago = $guia->getValorPago();
		$datapag   = $guia->getDataPago();
		$tipo      = $guia->getTipo();				
		$sel	   = "";
		$sel2	   = "";

		if($tipo == 1){
			$sel	   = "selected";
		}else if($tipo == 2){
			$sel2	   = "selected";
		}


		$tpl->assign('cod',$cod);
		$tpl->assign('competenc',$competenc);
		$tpl->assign('numero',$numero);
		$tpl->assign('codigo',$codigo);
		$tpl->assign('valorpago',number_format($valorpago,2,',','.'));
		$tpl->assign('datapag',date('d/m/Y',strtotime($datapag)));
		$tpl->assign('sel',$sel);
		$tpl->assign('sel2',$sel2);
		
	/**************************************************************/
	$tpl->printToScreen();

?>
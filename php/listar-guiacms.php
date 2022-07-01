<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/listar-guiacms.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
		
		$dao = new GuiaicmsDAO();
		$vet = $dao->ListaGuiaicmsEmpresas($_SESSION["cnpj"]);
		$num = count($vet);		
		
		for($i = 0; $i <  $num; $i++){
			
			$guia 	   = $vet[$i];
			
			$cod	   = $guia->getCodigo();
			$competenc = $guia->getCompetenc();
			$numero	   = $guia->getNumero();
			$codigo    = $guia->getCodigoGuia();
			$valorpago = $guia->getValorPago();
			$datapag   = $guia->getDataPago();
			$tipo      = $guia->getTipo();				
			$sel	   = "";	

			if($tipo == 1){
				$sel	   = "ICMS NORMAL";
			}else if($tipo == 2){
				$sel	   = "ICMS ST";
			}
			

			$tpl->newBlock('listar');
			
			$tpl->assign('cod',$cod);
			$tpl->assign('competenc',$competenc);
			$tpl->assign('numero',$numero);
			$tpl->assign('codigo',$codigo);
			$tpl->assign('valorpago',number_format($valorpago,2,',','.'));
			$tpl->assign('datapag',date('d/m/Y',strtotime($datapag)));
			$tpl->assign('sel',$sel);
		}
		
		
	/**************************************************************/
	$tpl->printToScreen();

?>
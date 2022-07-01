<?php

	require_once('../php/geral_config.php');

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/relatoriomensalcliente.htm');

	//$tpl->assignInclude('conteudo','../tpl/relatoriovencidos.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		//require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		$tpl->assign('empresa',$empresa);
		$tpl->assign('msgmensalidade',$msgmensalidade);
		
		$condicao  = array();
		
			
		$condicao[]   = " d.cod_cliente = '".$_REQUEST['codcli']."' ";
		$condicao[]   = " YEAR(d.vencimento) = '".$_REQUEST['ano']."' ";
		$condicao[]   = " (d.datapag <> '0000-00-00' or d.datapag is null) or d.datapag = '' ";
		
		$where = '';

		if(count($condicao) > 0){
		
			$where = ' where'.implode('AND',$condicao);
				
		}	
		
		$daof =  new FichaDAO();
		$vetf = $daof->ListaFichaUm($_REQUEST['codcli']);
		$numf = count($vetf);
		
		$ficha = $vetf[0];
		
		$nome = $ficha->getNome();
		
		$tpl->assign('nome',$nome);
		
		$dao = new DuplicReceberDAO();
		$vet = $dao->RelatorioMensalCliente($where);
		$num = count($vet);
		
		$vl_total =0;
		for($i = 0; $i < $num; $i++){
			
			$duplic = $vet[$i];
			
			$emissao    = $duplic->getEmissao();
			$vencimento = $duplic->getVencimento();
			$numero		= $duplic->getNumero();
			$valordoc	= $duplic->getValorDoc();
			$datapag	= $duplic->GetDataPag();
			
			$vl_total   = $vl_total + $valordoc;
			
			$tpl->newBlock('listar');	
					
			$tpl->assign('vencimento',implode("/", array_reverse(explode("-", "".$vencimento.""))));
			$tpl->assign('datapag',implode("/", array_reverse(explode("-", "".$datapag.""))));
			$tpl->assign('numero',$numero);
			$tpl->assign('valordoc',number_format($valordoc,2,',','.'));		
	
		}
		
		$tpl->newBlock('total');	
		
		$tpl->assign('vl_total',number_format($vl_total,2,',','.'));

	/**************************************************************/

	$tpl->printToScreen();



?>
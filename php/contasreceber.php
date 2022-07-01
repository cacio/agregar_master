<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/contasreceber.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		require_once('../inc/inc.permissao.php');
		
		$tpl->assign('log',$_SESSION['login']);

		if($_SESSION['idsys'] != 3){
				$tpl->newBlock('permis');
		}
				
		$condicao = array();
		
		if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){
		
		if(isset($_REQUEST['dtini']) and !empty($_REQUEST['dtini'])){

			$dtini    =  $_REQUEST['dtini'];	

			$condicao[]  = " d.vencimento between '".implode("-", array_reverse(explode("-", "".$dtini."")))."' ";		
		}
				
		if(isset($_REQUEST['dtfim']) and !empty($_REQUEST['dtfim'])){

			$dtfim     =  $_REQUEST['dtfim'];	

			$condicao[]  = " '".implode("-", array_reverse(explode("-", "".$dtfim."")))."' ";		
		}
		
		if(isset($_REQUEST['ndoc']) and !empty($_REQUEST['ndoc'])){

			$ndoc     =  $_REQUEST['ndoc'];	

			$condicao[]  = " d.id = '".$ndoc."' ";		
		}
		
		if(isset($_REQUEST['cliente']) and !empty($_REQUEST['cliente'])){

			$cliente     =  $_REQUEST['cliente'];	

			$condicao[]  = " d.cod_cliente = '".$cliente."' ";		
		}
		
		if(isset($_REQUEST['optionsRadios']) and !empty($_REQUEST['optionsRadios'])){

			$optionsRadios     =  $_REQUEST['optionsRadios'];	
						
			if($optionsRadios == 'option1'){
				$condicao[]  = " d.datapag  IS not NULL and d.datapag <> '0000-00-00' ";		
			}else{
				$condicao[]  = " (d.datapag  IS NULL OR d.datapag = '0000-00-00') ";		
			}
		}
			
		}else{
			$condicao[]  = " d.vencimento = curdate() ";
		}
				
		$where = '';

		if(count($condicao) > 0){
		
			$where = ' where'.implode('AND',$condicao);
				
		}
		
		$dao = new DuplicReceberDAO();
		$vet = $dao->ListaDuplicReceber($where);
		$num = count($vet);
		
		$vldoca = 0;
		$valortotal = 0;
		$vlcontritotal = 0;
		for($i = 0; $i < $num; $i++){
		
			$duplic = $vet[$i];	
			
			$codigo	  = $duplic->getCodigo();
			$codcli   = $duplic->getCodigoCliente();
			$emissao  = $duplic->getEmissao();
			$numero	  = $duplic->getNumero();
			$vencim   = $duplic->getVencimento();
			$vldoc	  = $duplic->getValorDoc();		
			$historic = $duplic->getHistorico();
			$datapag  = $duplic->getDataPag();
			$saldo	  = $duplic->getSaldo();
			$valorpag = $duplic->getValorPago();
			$tipo     = $duplic->getTipo();
			$banco	  = $duplic->getBanco();			
			$nomecli  = $duplic->getNomeCliente(); 
			$nome	  = $duplic->getNome();	
			
			if($nomecli != ""){
				$nomecli = $nome;
			}
			
			if($datapag == '0000-00-00'){
				$datapag = '';
			}else{
				$datapag = implode("-", array_reverse(explode("-", "".$datapag."")));
			}
			
			$rest = $numero{0};
			$vldoca = 0;
			if($rest == '#'){
		
				$vldoca = $vldoc;
				$vldoc  = 0;
			}
			$valortotal = $valortotal + $vldoc;
			$vlcontritotal = $vlcontritotal + $vldoca;
			
			$tpl->newBlock('listacontasreceber');		

			$tpl->assign('codigo',$codigo);
			$tpl->assign('codcli',$codcli);
			$tpl->assign('emissao',implode("/", array_reverse(explode("-", "".$emissao.""))));
			$tpl->assign('numero',$numero);
			$tpl->assign('vencim',implode("/", array_reverse(explode("-", "".$vencim.""))));
			$tpl->assign('vldoc',number_format($vldoc,2,',','.'));
			$tpl->assign('vldoca',number_format($vldoca,2,',','.'));
			$tpl->assign('historic',utf8_decode($historic));
			$tpl->assign('datapag',$datapag);
			$tpl->assign('saldo',$saldo);
			$tpl->assign('valorpag',number_format($valorpag,2,',','.'));
			$tpl->assign('tipo',$tipo);
			$tpl->assign('banco',$banco);
			$tpl->assign('nomecli',$nomecli);
			$tpl->assign('nome',$nome);

			if($_SESSION['idsys'] == 1){
				$tpl->newBlock('per');
				$tpl->assign('codigo',$codigo);
			}
		} 
		$valorestot = $valortotal + $vlcontritotal;	
		$tpl->newBlock('total');
		$tpl->assign('valortotal',number_format($valortotal,2,',','.'));
		$tpl->assign('vlcontritotal',number_format($vlcontritotal,2,',','.'));
		$tpl->assign('valorestot',number_format($valorestot,2,',','.'));
	/**************************************************************/

	$tpl->printToScreen();



?>
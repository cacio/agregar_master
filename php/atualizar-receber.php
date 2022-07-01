<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/atualizar-receber.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		//require_once('../inc/inc.permissao.php');
		
		$tpl->assign('log',$_SESSION['login']);

		$id     = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '0';
		
		$dao = new DuplicReceberDAO();
		$vet = $dao->ListaDuplicReceberUm($id);
		$num = count($vet);
		
				
		$duplic = $vet[0];	
		
		$codigo	   = $duplic->getCodigo();
		$codcli    = $duplic->getCodigoCliente();
		$emissao   = $duplic->getEmissao();
		$numero	   = $duplic->getNumero();
		$vencim    = $duplic->getVencimento();
		$vldoc	   = $duplic->getValorDoc();		
		$historic  = $duplic->getHistorico();
		$datapag   = $duplic->getDataPag();
		$saldo	   = $duplic->getSaldo();
		$valorpag  = $duplic->getValorPago();
		$tipo      = $duplic->getTipo();
		$banco	   = $duplic->getBanco();			
		$nomecli   = $duplic->getNomeCliente(); 
		$formpagto = $duplic->getFormPagto();
		
		$sel1 = "";		
		$sel2 = "";	
		
		if($formpagto == 1){
			$sel1 = "selected";	
		}else if($formpagto == 2){
			$sel2 = "selected";
		}
		
		$tpl->assign('codigo',$codigo);
		$tpl->assign('codcli',$codcli);
		$tpl->assign('emissao',implode("-", array_reverse(explode("-", "".$emissao.""))));
		$tpl->assign('numero',$numero);
		$tpl->assign('vencim',implode("-", array_reverse(explode("-", "".$vencim.""))));
		$tpl->assign('vldoc',number_format($vldoc,2,',','.'));
		$tpl->assign('historic',utf8_decode($historic));
		$tpl->assign('datapag',implode("-", array_reverse(explode("-", "".$datapag.""))));
		$tpl->assign('saldo',$saldo);
		$tpl->assign('valorpag',number_format($valorpag,2,',','.'));
		$tpl->assign('tipo',$tipo);
		$tpl->assign('banco',$banco);
		$tpl->assign('nomecli',$nomecli);
		$tpl->assign('sel1',$sel1);
		$tpl->assign('sel2',$sel2);
		
		$daoF = new FormaPagtoDAO();
		$vetF = $daoF->ListaFormaPagamento();
		$numF = count($vetF);

		for ($i=0; $i < $numF; $i++) { 
			
			$forma = $vetF[$i];

			$codigof = $forma->getCodigo();
			$nome    = $forma->getNome();
			$sel     = "";
			
			if($formpagto == $codigof){
				$sel = "selected";	
			}
			
			$tpl->newBlock('listar');
			$tpl->assign('codigof',$codigof);
			$tpl->assign('nome',$nome);
			$tpl->assign('sel',$sel);
		}            


		$daor = new RecebimentoDAO();
		$vetr = $daor->ListaRecebimentoUm($id);
		
		if(!empty($vetr)){
			$total = 0;
			foreach ($vetr as $receb) {

				$idreb 	  = $receb['id']; 
				$valor 		  = $receb['valor'];
				$xformarecbto = $receb['id_formarecbto'];
				$total		  = $total + $valor;

				$tpl->newBlock('listapagtos');

				$tpl->assign('idreb',$idreb);
				$tpl->assign('idreceber',$id);
				$tpl->assign('xformarecbto',$xformarecbto);
				$tpl->assign('dtrecbto',implode("/", array_reverse(explode("-", "".$datapag.""))));
				$tpl->assign('valor',number_format($valor,2,',','.'));
				
			}

			$tpl->newBlock('totalpagtos');
			
			if($total > 0){
				$saldo = $vldoc - $total;
			}else{
				$saldo = $vldoc;
			}

			$tpl->assign('total',number_format($total,2,',','.'));
			$tpl->assign('saldo',number_format($saldo,2,',','.'));

		}

	/**************************************************************/

	$tpl->printToScreen();



?>
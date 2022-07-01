<?php

	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/relatorioestoqueporgrupo.htm');
	//$tpl->assignInclude('conteudo','../tpl/relatorioficha.htm');
	$tpl->prepare();

	/**************************************************************/
		require_once('../inc/inc.session.php');		

		$tpl->assign('log',$_SESSION['login']);

		$condicao  = array();
		$condicao2 = array();		
			
				
		if(isset($_REQUEST['dtadmissaoini']) and !empty($_REQUEST['dtadmissaoini'])){

			$dtadmissaoini    =  $_REQUEST['dtadmissaoini'];	
			$condicao[]  	  = " m.data between '".implode("-", array_reverse(explode("-", "".$dtadmissaoini."")))."' ";		
		}
				
		if(isset($_REQUEST['dtadmissaofin']) and !empty($_REQUEST['dtadmissaofin'])){

			$dtadmissaofin  =  $_REQUEST['dtadmissaofin'];	
			$condicao[]  	= " '".implode("-", array_reverse(explode("-", "".$dtadmissaofin."")))."' ";		
		}						
		
		$condicao[]  	= " m.formpagto IS NOT NULL ";	
									
		$where = '';

		if(count($condicao) > 0){		
			$where = ' where'.implode('AND',$condicao);				
		}	
			
			
		$dao = new MovimentacaoDAO();
		$vet = $dao->RelatorioEstoquePorGrupo($where);
		$num = count($vet);
			
		$contador = 1;
		$xidgrupo = "";
		
		for($i = 0; $i < $num; $i++){
			
			$movim	   = $vet[$i];
			
			$idgrupo    = $movim->getIdGrupo();
			$grupo      = $movim->getGrupo();
			$descricao  = $movim->getDescProd();
			$entrada    = $movim->getEntrada();
			$saida	    = $movim->getSaida();	
			$total	    = $movim->getTotal();	
			$quantidade = $movim->getQuantidade();
			
			if($idgrupo != $xidgrupo){
				$xidgrupo = $idgrupo;
				
				if($contador > 0){
					
					
					$tpl->newBlock('lista');
			
					$tpl->assign('idgrupo',$idgrupo);
					$tpl->assign('grupo',$grupo);
					
				}
				
			}
			
			$tpl->newBlock('listaest');
			
			$tpl->assign('quantidade',$quantidade);
			$tpl->assign('descricao',$descricao);
			$tpl->assign('entrada',number_format($entrada,2,',','.'));
			$tpl->assign('saida',number_format($saida,2,',','.'));
			$tpl->assign('total',number_format($total,2,',','.'));
			
			$contador++;
		}
		

	/**************************************************************/

	$tpl->printToScreen();

?>
<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/atualizar-movimentacao.htm');

	$tpl->prepare();

	

	/**************************************************************/	
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

		$ncontrole = $_REQUEST['id'];
		
		$dao 	 = new ProdutosDAO();
		$daom 	 = new MovimentacaoDAO();
		
		$vetd  	 = $daom->ListaMovimentacaoDetalhado($ncontrole);
		$numd	 = count($vetd);
		
		if($numd > 0){
			
			$mov = $vetd[0];
				
			$numero = $mov->getNumeroControl();
			$data   = $mov->getData();
			$tipo	= $mov->getTipo();	
			$sel1   = "";
			$sel2   = "";
			
			if($tipo == 'E'){
				$sel1 = "selected";	
			}else if($tipo == 'S'){
				$sel2   = "selected";
			}
					
			$tpl->assign('data',date('d/m/Y',strtotime($data)));		
			$tpl->assign('sel1',$sel1);	
			$tpl->assign('sel2',$sel2);	
			$tpl->assign('numero',$numero);		
				
		}
		
		
	
				
		$vetm	 =  $daom->ListaMovimentacaoUm($ncontrole);
		$numm 	 = count($vetm);
		$valortotal = 0;
		for($x = 0; $x < $numm; $x++){
		
			$movim = $vetm[$x];	

			$codigo 	=  	$movim->getCodigo();
			$numero		=   $movim->getNumeroControl();
			$id_produto = 	$movim->getIdProduto();
			$data 		= 	$movim->getData();
			$tipo 		=	$movim->getTipo();
			$vlunit 	= 	$movim->getValorUnitario();
			$quantidade = 	$movim->getQuantidade();
			$total 		=	$movim->getTotal();		
			$valortotal =   $valortotal + $total;
					
			$vetp = $dao->ListaProdutosUm($id_produto);
			$nump = count($vetp);
			
			if($nump > 0){										
				$prod   = $vetp[0];					
				$nome   = $prod->getNome();
				$id		= $prod->getCodigo();																
			}else{
				$nome   = "";
				$id		= "";
			}	
				
			$tpl->newBlock('listar');

			$tpl->assign('codigo',$codigo);
			$tpl->assign('numero',$numero);
			$tpl->assign('produto',$id_produto.'-'.$nome);
			$tpl->assign('data',implode("/", array_reverse(explode("-", "".$data.""))));
			$tpl->assign('vlunit',number_format($vlunit,2,',','.'));
			$tpl->assign('quantidade',$quantidade);
			$tpl->assign('total',number_format($total,2,',','.'));
			$tpl->assign('tipo',$tipo);	
				
				
		}
		
		$tpl->newBlock('total');
		$tpl->assign('valortotal',number_format($valortotal,2,',','.'));
				
					
		$vet = $dao->ListaProdutos();
		$num = count($vet);		
		 
		for($i = 0; $i < $num; $i++){
		
			$prod = $vet[$i];
				
				
			$codigo = $prod->getCodigo();
			$nome   = $prod->getNome();				
			
			$tpl->newBlock('listarprod');

			$tpl->assign('codigo',$codigo);
			$tpl->assign('nome',$nome);

			
		}
		

	/**************************************************************/

	$tpl->printToScreen();



?>
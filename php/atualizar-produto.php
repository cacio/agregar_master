<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/atualizar-produto.htm');

	$tpl->prepare();

	

	/**************************************************************/		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

		$codigo = $_REQUEST['id'];
				
		$dao = new ProdutosDAO();	
		$vet = $dao->ListaProdutosUm($codigo);
		$num = count($vet);
		
		if($num > 0){
			
			
			$prod = $vet[0];
			
			$nome    = $prod->getNome();
			$id		 = $prod->getCodigo();
			$preco   = $prod->getPreco();
			$idgrupo = $prod->getGrupo();
			
			$tpl->assign('id',$id);
			$tpl->assign('nome',$nome);
			$tpl->assign('preco',number_format($preco,2,',','.'));
			
			
			$daog = new GrupoDAO();
			$vetg = $daog->ListaGrupoUmSel($idgrupo);
			$numg = count($vetg);		

			for($x = 0; $x < $numg; $x++){

				$grup = $vetg[$x];


				$codigog = $grup->getCodigo();
				$nomeg   = $grup->getNome();				
				$sel     = $grup->getSelecionado();
				
				$tpl->newBlock('listagrupo');

				$tpl->assign('codigog',$codigog);
				$tpl->assign('nomeg',$nomeg);
				$tpl->assign('sel',$sel);

			}	
					
		}
			
	/**************************************************************/

	$tpl->printToScreen();



?>
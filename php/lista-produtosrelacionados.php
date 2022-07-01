<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/lista-produtosrelacionados.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$cnpjemp  = $_SESSION['cnpj'];
		
		$dao = new ProdutosTxtDAO();
		$vet = $dao->RelProdutoTxt($cnpjemp);
		$num = count($vet);		
			
		for ($i=0; $i < $num; $i++) { 
			
			$prodtxt        = $vet[$i];

			$cod_secretaria = $prodtxt->getCodSecretaria();			
			$descricao      = $prodtxt->getDescSecretaria();
			$cod_prod       = $prodtxt->getCodProd();
			$desc_prod      = $prodtxt->getDescProd();
			$pkid           = $prodtxt->getPkId();

			$tpl->newBlock('lista');

			$tpl->assign('cod_secretaria',$cod_secretaria);
			$tpl->assign('descricao',$descricao);
			$tpl->assign('cod_prod',$cod_prod);
			$tpl->assign('desc_prod',$desc_prod);
			$tpl->assign('pkid',$pkid);

		}

	/**************************************************************/
	$tpl->printToScreen();
?>
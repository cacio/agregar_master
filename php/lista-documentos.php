<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/lista-documentos.htm');

	$tpl->prepare();

	

	/**************************************************************/

		

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.permissao.php');
		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		

		$dao = new DocumentosDAO();
		$vet = $dao->ListaDocumentos();
		$num = count($vet);
		
		for($i = 0; $i < $num; $i++){
			
			$doc = $vet[$i];	
			
			$cod  = $doc->getCodigo();
			$nome = $doc->getNome();
			
			$tpl->newBlock('listdocs');
			
			
			$tpl->assign('cod',$cod);
			$tpl->assign('nome',$nome);
			
		}

	/**************************************************************/

	$tpl->printToScreen();



?>
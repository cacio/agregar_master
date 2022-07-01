<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/alterar-documentos.htm');

	$tpl->prepare();

	

	/**************************************************************/

		

		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');
		//require_once('../inc/inc.permissao.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$cod_doc = $_REQUEST['cod_doc'];
		
		if(!isset($_REQUEST['cod_doc']) and empty($_REQUEST['cod_doc'])){
			header('Location:admin.php');
		}
		
		$dao = new DocumentosDAO();
		$vet = $dao->ListaDocumentosUm($cod_doc);
		$num = count($vet);
					
		$doc = $vet[0];	
		
		$cod  = $doc->getCodigo();
		$nome = $doc->getNome();
						
		$tpl->assign('cod',$cod);
		$tpl->assign('nome',htmlentities($nome,ENT_QUOTES));
			
		

	

	/**************************************************************/

	$tpl->printToScreen();



?>
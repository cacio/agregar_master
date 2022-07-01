<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/atualizar-forma.htm');
	$tpl->prepare();

	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);
		
		if(!empty($_REQUEST['id'])){
			
			$id     = $_REQUEST['id'];
			$dao 	= new FormaPagtoDAO();
			$vet 	= $dao->ListaFormaPagamentoUm($id);
			$num	= count($vet);

			if($num > 0){

				$forma = $vet[0];
				
				$nome   = $forma->getNome();
				$codigo = $forma->getCodigo();
				
				$tpl->assign('codigo',$codigo);
				$tpl->assign('nome',$nome);
				
			}else{
				header('Location:formapagto.php');
			}	
		}else{
			header('Location:formapagto.php');
		}

	/**************************************************************/

	$tpl->printToScreen();



?>
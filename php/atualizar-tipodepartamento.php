<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/atualizar-tipodepartamento.htm');
	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);
		
		if(!empty($_REQUEST['id'])){
			
			$id     = $_REQUEST['id'];
			$dao 	= new TipoDepartamentoDAO();
			$vet 	= $dao->ListaTipoDepartamentoUm($id);
			$num	= count($vet);

			if($num > 0){

				$td = $vet[0];
				
				$nome   = $td->getNome();
				$codigo = $td->getCodigo();
				
				$tpl->assign('codigo',$codigo);
				$tpl->assign('nome',$nome);
				
			}else{
				header('Location:lista-tipodepartamento.php');
			}	
		}else{
			header('Location:lista-tipodepartamento.php');
		}

	/**************************************************************/

	$tpl->printToScreen();



?>
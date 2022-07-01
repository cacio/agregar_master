<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/formapagto.htm');
	$tpl->prepare();

	/**************************************************************/
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

		$dao = new FormaPagtoDAO();
		$vet = $dao->ListaFormaPagamento();
		$num = count($vet);		
		 
		for($i = 0; $i < $num; $i++){
		
            $forma  = $vet[$i];
                
            $codigo = $forma->getCodigo();
            $nome   = $forma->getNome();				
            
            $tpl->newBlock('listar');

            $tpl->assign('codigo',$codigo);
            $tpl->assign('nome',$nome);

			if($_SESSION['idsys'] == 1){
				$tpl->newBlock('per');
				$tpl->assign('codigo',$codigo);
			}	
		}	
		

	/**************************************************************/

	$tpl->printToScreen();

?>
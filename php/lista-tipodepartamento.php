<?php
	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/lista-tipodepartamento.htm');
	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		
		$dao = new TipoDepartamentoDAO();
		$vet = $dao->ListaTipoDepartamento();
		$num = count($vet);		
		 
		for($i = 0; $i < $num; $i++){
		
				$tpd = $vet[$i];
					
					
				$codigo = $tpd->getCodigo();
				$nome   = $tpd->getNome();				
				
				$tpl->newBlock('listatdp');

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
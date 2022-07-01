<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/env-dados.htm');

	$tpl->prepare();

	

	/**************************************************************/
		require_once('../inc/inc.session.php');		
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$daoe = new EmpresasDAO();
		$vete = $daoe->ListaEmpresaUm($_SESSION['id_emp']);
		$nume = count($vete);
				
		$emp  = $vete[0];		
		$cnpj = $emp->getCnpj();
		$tpl->assign('cnpj',$cnpj);
		$tpl->assign('idemp',$_SESSION['id_emp']);
		
		$dao = new ModalidadeEnvDAO();
		$vet = $dao->ListaModalidadeEnvioPorModalidadeEmpresa($_SESSION['id_emp']);	
		$num = count($vet);	
		
		for($i = 0; $i < $num; $i++){
			
			$modenv   = $vet[$i];
			
			$id    = $modenv->getCodigo();
			$nome  = $modenv->getNome();
		
				
			$tpl->newBlock('listamodalidadeenvio');
			
			
			$tpl->assign('id',$id);
			$tpl->assign('nome',$nome);	
				
		} 		
		

				

	

	/**************************************************************/

	$tpl->printToScreen();



?>
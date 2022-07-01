<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/cadastro-empresas.htm');

	$tpl->prepare();

	

	/**************************************************************/

		

		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');
		/*require_once('../inc/inc.permissao.php');*/
		$tpl->assign('log',$_SESSION['login']);

		$daoe   = new EmpresasDAO();
		$vete   = $daoe->proximoid();
		$prox   = $vete[0];
		$idprox = $prox->getIdProx(); 
		
		$tpl->assign('idprox',$idprox);
		
		$dao = new ModalidadeDAO();
		$vet = $dao->ListaModalidade();
		$num = count($vet);
				
		for($i = 0;$i < $num; $i++){
			
			$mod  = $vet[$i];
			
			$cod  =	$mod->getCodigo();
			$nome = $mod->getNome();	
			

			$tpl->newBlock('listmod');

			$tpl->assign('cod',$cod);
			$tpl->assign('nome',$nome);
		}		
				

	

	/**************************************************************/

	$tpl->printToScreen();



?>
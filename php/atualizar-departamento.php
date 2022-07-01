<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/atualizar-departamento.htm');
	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);
		
		if(!empty($_REQUEST['id'])){
			
			$id     = $_REQUEST['id'];
			$dao 	= new DepartamentoDAO();
			$vet 	= $dao->ListaDepartamentoUm($id);
			$num	= count($vet);

			if($num > 0){

				$dep = $vet[0];
				
				$nome     = $dep->getNome();
				$codigo   = $dep->getCodigo();
				$tipo_dep = $dep->getTipoDepartamento();
				
				$tpl->assign('codigo',$codigo);
				$tpl->assign('nome',$nome);
				
				
				$daot = new TipoDepartamentoDAO();
				$vett = $daot->ListaTipoDepartamentoUmSel($tipo_dep);
				$numt = count($vett);		

				for($i = 0; $i < $numt; $i++){

						$tpd = $vett[$i];

						$cod = $tpd->getCodigo();
						$nom = $tpd->getNome();
						$sel = $tpd->getSelecionado();

						$tpl->newBlock('listatdp');

						$tpl->assign('cod',$cod);
						$tpl->assign('nom',$nom);
						$tpl->assign('sel',$sel);
						
				}
				
				
			}else{
				header('Location:lista-departamento.php');
			}	
		}else{
			header('Location:lista-departamento.php');
		}

	/**************************************************************/

	$tpl->printToScreen();



?>
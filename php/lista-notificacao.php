<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/lista-notificacao.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		require_once('../inc/inc.pushmensagem.php');
		
		//require_once('../inc/inc.permissao.php');
		$tpl->assign('log',$_SESSION['login']);

	
		if($_SESSION['idsys'] == 2){
			$daomens = new MensagemEmpresaDAO();
			$Vetmens = $daomens->ListaMensagemEmpresa($_SESSION['id_emp']);
			$numens  = count($Vetmens);
			$tpl->assign('numens',$numens);

			for($ms = 0; $ms < $numens; $ms++){

				$msgs       = $Vetmens[$ms];

				$cods       = $msgs->getCodigo();
				$datas 	    = $msgs->getData();		
				$titulos    = $msgs->getTitulo();
				$mensagems  = $msgs->getMensagem();						
				$timestamps = date('H:m',strtotime($msgs->getTimesTamp()));
				$ampm       = date('a',strtotime($timestamps));
				$lida       = $msgs->getLida();

				if($lida == 1){

					$lida = "text-warning";	
				}else{
					$lida = "text-success";
				}

				$tpl->newBlock('listars');

				$tpl->assign('cods',$cods);	
				$tpl->assign('datas',$datas);
				$tpl->assign('titulos',substr($titulos, 0, 10));
				$tpl->assign('mensagems',$mensagems);
				$tpl->assign('timestamps',$timestamps);
				$tpl->assign('ampm',$ampm);
				$tpl->assign('lida',$lida);

			}		

		}else if($_SESSION['idsys'] == 1){

			$daomens = new MensagemEmpresaDAO();
			$Vetmens = $daomens->ListaMensagemDiaAdmTodos();
			$numens  = count($Vetmens);

			$tpl->assign('numens',$numens);

			for($ms = 0; $ms < $numens; $ms++){

				$msgs       = $Vetmens[$ms];

				$cods       = $msgs->getCodigo();
				$datas 	    = $msgs->getData();		
				$titulos    = $msgs->getTitulo();
				$mensagems  = $msgs->getMensagem();						
				$timestamps = date('H:m',strtotime($msgs->getTimesTamp()));
				$ampm       = date('a',strtotime($timestamps));
				$lida       = $msgs->getLida();

				if($lida == 1){

					$lida = "text-warning";	
				}else{
					$lida = "text-success";
				}
				$tpl->newBlock('listars');

				$tpl->assign('cods',$cods);
				$tpl->assign('titulos',substr($titulos, 0, 10));
				$tpl->assign('mensagems',$mensagems);
				$tpl->assign('timestamps',$timestamps);
				$tpl->assign('ampm',$ampm);
				$tpl->assign('lida',$lida);
			}	

		}
	

	/**************************************************************/

	$tpl->printToScreen();

?>
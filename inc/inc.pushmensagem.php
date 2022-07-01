<?php
	
	if($_SESSION['idsys'] == 2){
			$daomen = new MensagemEmpresaDAO();
			$Vetmen = $daomen->ListaMensagemDia($_SESSION['id_emp']);
			$numen  = count($Vetmen);

			if($numen > 0){
				for($m = 0; $m < $numen; $m++){

					$msg       = $Vetmen[$m];

					$codigo    = $msg->getCodigo();
					$data 	   = $msg->getData();		
					$titulo    = $msg->getTitulo();
					$mensagem  = $msg->getMensagem();						
					$timestamp = $msg->getTimesTamp();
					$timestamps = date('H:m',strtotime($msg->getTimesTamp()));
					$ampm       = date('a',strtotime($timestamps));

					$tpl->newBlock('mensagems');

					$tpl->assign('codigo',$codigo);
					$tpl->assign('data',$data);
					$tpl->assign('titulo',$titulo);
					$tpl->assign('mensagem',$mensagem);
					$tpl->assign('timestamp',$timestamps);
					$tpl->assign('ampm',$ampm);
				}		
			}else{
				$tpl->newBlock('notmensagems');
			}
		}else if($_SESSION['idsys'] == 1){

			$daomen = new MensagemEmpresaDAO();
			$Vetmen = $daomen->ListaMensagemDiaAdm();
			$numen  = count($Vetmen);
			if($numen > 0){
				for($m = 0; $m < $numen; $m++){

					$msg       = $Vetmen[$m];

					$codigo    = $msg->getCodigo();	
					$data 	   = $msg->getData();		
					$titulo    = $msg->getTitulo();
					$mensagem  = $msg->getMensagem();						
					$timestamp = $msg->getTimesTamp();
					$timestamps = date('H:m',strtotime($msg->getTimesTamp()));
					$ampm       = date('a',strtotime($timestamps));

					$tpl->newBlock('mensagems');

					$tpl->assign('codigo',$codigo);
					$tpl->assign('data',$data);
					$tpl->assign('titulo',$titulo);
					$tpl->assign('mensagem',$mensagem);
					$tpl->assign('timestamp',$timestamps);
					$tpl->assign('ampm',$ampm);
				}
			}else{
				$tpl->newBlock('notmensagems');
			}
		}
		$tpl->gotoBlock('_ROOT');
?>
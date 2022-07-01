<?php 
	
	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/detalhe-notificacao.htm');
	$tpl->prepare();

	/**************************************************************/

	require_once('../inc/inc.session.php');
	require_once('../inc/inc.menu.php');
	require_once('../inc/inc.pushmensagem.php');
	
	//require_once('../inc/inc.permissao.php');
	$tpl->assign('log',$_SESSION['login']);

	$id = $_REQUEST['id'];
	$dao = new MensagemEmpresaDAO();
	$vet = $dao->ListaMensagemDetalhe($id);
	$num = count($vet);	

	if($num > 0){
		$msg = $vet[0];

		$cod          = $msg->getCodigo();
		$data         = $msg->getData();		
		$titulo       = $msg->getTitulo();
		$mensagem     = $msg->getMensagem();					
		$timestamp    = $msg->getTimesTamp();
		$id_empresa   = $msg->getIdEmpresa();
		$nome_empresa = explode('|', $msg->getNomeEmp());
		$lida         = $msg->getLida();
		
		if($id_empresa == $_SESSION['id_emp'] or $_SESSION['idsys'] == 1){

			if($lida == 1){

				$msgs = new MensagemEmpresa();
				$msgs->setCodigo($cod);
				$msgs->setLida(2);	
				$dao->updatelida($msgs);

			}
			

			$tpl->assign('titulo',$titulo);
			$tpl->assign('data',date('d/m/Y',strtotime($data)));
			$tpl->assign('mensagem',$mensagem);
			$tpl->assign('nome',$nome_empresa[0]);
			$tpl->assign('email',$nome_empresa[1]);

		}else{

			$destino = 'admin.php';
			header('Location:'.$destino);
		}
		
	}else{
			$destino = 'admin.php';
			header('Location:'.$destino);	
	}
	/**************************************************************/

	$tpl->printToScreen();

?>
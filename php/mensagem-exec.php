<?php
	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){

			case 'enviar':													
					
					if(empty($_REQUEST['cvtitulo'])){
						$cvtitulo = "";
					}else{
						$cvtitulo = $_REQUEST['cvtitulo'];
					}
					
					if(empty($_REQUEST['cvmensagem'])){
						$cvmensagem = "";
					}else{
						$cvmensagem = $_REQUEST['cvmensagem'];
					}
					
					if(empty($_REQUEST['cmodalid'])){
						$cmodalid = 0;
					}else{
						$cmodalid = $_REQUEST['cmodalid'];
					}
					
					if(empty($_REQUEST['cvempresa'])){
						$cvempresa = 0;
					}else{
						$cvempresa = $_REQUEST['cvempresa'];
					}
					
					$msg = new MensagemEmpresa();
					
					$msg->setTitulo($cvtitulo);
					$msg->setMensagem($cvmensagem);
					$msg->setIdModalidade($cmodalid);
					$msg->setIdEmpresa($cvempresa);
					$msg->setData(date('Y-m-d'));
					$msg->setTimesTamp(time());
					
					$dao = new MensagemEmpresaDAO();
					$dao->inserir($msg);
					
					$results = array();
					
					array_push($results, array(							
							'msg' => 'Mensagem Enviada com Sucesso!',								
					));	
					
					echo (json_encode($results));
					
			break;
			
			case 'buscamsg':
								
				$timestart = time();
				
				$dao = new MensagemEmpresaDAO();
				
				if(isset($_POST['timestamp'])){					
					$timestamp = $_POST['timestamp'];				
				}else{
					
					$pega_time = $dao->TempoAual();
					$row = $pega_time[0];
					
					$timestamp = $row->getNow();										
				}
				
				
								
				$newData = false;
				
				$notificacoes = array();
				
				
				while(!$newData && (time()-$timestart)<20){
					
					$vet = $dao->ListaMensagemTempoReal($_SESSION['id_emp'],$timestamp);	
					$num = count($vet);
				
					for($i = 0; $i < $num; $i++){
						
						$msg = $vet[$i];
												
						$data      = $msg->getData();		
						$titulo    = $msg->getTitulo();
						$mensagem  = $msg->getMensagem();
						$timestamp = $msg->getTimesTamp();
						
						
						array_push($notificacoes, array(							
							'data' => ''.$data.'',
							'titulo' => ''.$titulo.'',
							'mensagem' => ''.$mensagem.'',
							'timestamp' => ''.$timestamp.'',								
						));
						
						$newData = true;
						
					}
					usleep(200000);	
									
				}
				
				$pega_time = $dao->TempoAual();
				$row 	   = $pega_time[0];				
				$timestamp = $row->getNow();
				
				$data = array('notificacoes'=>$notificacoes,'timestamp'=>$timestamp);
				echo (json_encode($data));
				exit;				
			break;
			
			case 'verificar':
				
				$dao = new MensagemEmpresaDAO();
				$vet = $dao->CountMesagemDia($_SESSION['id_emp']);
				$num = count($vet);
				
				echo $num;
				
			break;
			
		}

	}
	

?>
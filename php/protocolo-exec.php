<?php
	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){
			
			case 'alterar':
				
				$codstatus = !empty($_REQUEST['codstatus']) ? $_REQUEST['codstatus'] : "";				
				$codprot   = !empty($_REQUEST['codprot']) ? $_REQUEST['codprot']     : "";
							
				$dao 	= new ProtocoloDAO();
				$vet 	= $dao->BuscaDadosProtocoloUm($codprot);
				$num 	= count($vet);	
				$result = array();
				
				if($num > 0){
										
					$prot = $vet[0];					
					$cod  = $prot->getCodigo();
					
					$proto = new Protocolo();
					
					$proto->setCodigo($cod);
					$proto->setStatus($codstatus);
					
					$dao->updateStatus($proto);
					
					array_push($result,array(
						'obs'=>'Alterado com sucesso!'
					));
				}else{
					array_push($result,array(
						'obs'=>'Não achei esse protocolo!'
					));
				}
				
				echo json_encode($result);
			break;
			case 'alterarstatus':
				error_reporting(E_ALL);
				ini_set('display_errors', 'On');
				if(!empty($_REQUEST['prot'])){

					$prots   = $_REQUEST['prot'];
					$tipo   = $_REQUEST['tipo'];
					$mesano = $_REQUEST['mesano'];

					$dao 	= new ProtocoloDAO();
					$vet 	= $dao->BuscaDadosProtocoloEnviados($prots);
					$num 	= count($vet);	
					$result = array();
					
					if($num > 0){
											
						$prot = $vet[0];					
						$cod  = $prot->getCodigo();
						
						$proto = new Protocolo();
						
						$proto->setCodigo($cod);
						$proto->setStatus(7);
						
						$dao->updateStatus($proto);
						
						if($tipo == 'xml'){							
							$url	     = "importa_agregar_xml.php";	
						}else if($tipo == 'txt'){
							$url	     = "importa_agregar_txt.php";	
						}
						
						header("Location:{$url}?act=Validando&anomes={$mesano}");

					}else{
						header("Location:admin.php");
					}

				}else{
					header("Location:admin.php");
				}
				echo "ok";

			break;
		
		}

	}
	

?>
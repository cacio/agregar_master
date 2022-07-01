<?php
	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){
			case 'inserir':
				
				if(empty($_REQUEST['nome'])){
					$nome = "";
				}else{
					$nome = $_REQUEST['nome'];
				}
				
				
				$stat = new Status();
				
				$stat->setNome($nome);
				
				$dao = new StatusDAO();
				$dao->inserir($stat);
				
				header('Location:../php/lista-status.php');
				
			break;
			case 'alterar':
				
				$id = $_REQUEST['id'];
				
				if(empty($_REQUEST['nome'])){
					$nome = "";
				}else{
					$nome = $_REQUEST['nome'];
				}
				
				
				$stat = new Status();
				
				$stat->setCodigo($id);
				$stat->setNome($nome);
				
				$dao = new StatusDAO();
				$dao->update($stat);
				
				header('Location:../php/lista-status.php');
			
			break;
			case 'deletar':
			
				$id = $_REQUEST['id'];
				
				$stat = new Status();
				
				$stat->setCodigo($id);
				
				$dao = new StatusDAO();
				
				$dao->deletar($stat);
			
			break;
			case 'lista':
																			
				$results = array();
				
				$daos = new StatusDAO();
				$vets = $daos->ListaStatus();
				$nums = count($vets);
				
				for($y = 0; $y < $nums; $y++){
					
					$stat = $vets[$y];	
					
					$codigo = $stat->getCodigo();
					$nome   = $stat->getNome();
										
					array_push($results, array(
							'codigo' => ''.$codigo.'',									
							'nome' => ''.utf8_encode($nome).'',		
					));		
										
					
				}
				
				echo (json_encode($results));
					
			break;
		}

	}
	

?>
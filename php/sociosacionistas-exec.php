<?php

	

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		

		

		$act = $_REQUEST['act'];	

		

		switch($act){

			case 'inserir':
			
				$results = array();	
				$id_emp = $_REQUEST['idemp'];
										
				if(empty($_REQUEST['socio_acionista_nome'])){
					$socio_acionista_nome = "";
				}else{
					$socio_acionista_nome = $_REQUEST['socio_acionista_nome'];
				}
				
				if(empty($_REQUEST['socio_acionista_cpf'])){
					$socio_acionista_cpf = "";
				}else{
					$socio_acionista_cpf = $_REQUEST['socio_acionista_cpf'];
				}
				
				if(empty($_REQUEST['socio_acionista_partcapsocial'])){
					$socio_acionista_partcapsocial = "";
				}else{
					$socio_acionista_partcapsocial = $_REQUEST['socio_acionista_partcapsocial'];
				}
				
				$soc = new SociosAcionistas();
			
				$soc->setNome($socio_acionista_nome);
				$soc->setCpf($socio_acionista_cpf);
				$soc->setPartCapSocial($socio_acionista_partcapsocial);
				$soc->setIdEmpresa($id_emp);
				
				$dao = new SociosAcionistasDAO();
				
				$vet    = $dao->proximoid();
				$prox   = $vet[0];				
				$codigo = $prox->getIdProx();
				
				$dao->inserir($soc);
				
				array_push($results, array(
							'codigo' => ''.$codigo.'',									
							'nome' => ''.$socio_acionista_nome.'',	
							'cpf' => ''.$socio_acionista_cpf.'',
							'partcapsocial' => ''.$socio_acionista_partcapsocial.'',
				));			
				
				
				echo (json_encode($results));
				
				
			break;
			
			case 'delete':
				
				$id      = $_REQUEST['id'];	
				$id_emp  = $_REQUEST['idemp'];
				$results = array();
				
				foreach($id as $key=>$value){
				
					$soc = new SociosAcionistas();
					
					$soc->setCodigo($value);
					$soc->setIdEmpresa($id_emp);
					
					$dao = new SociosAcionistasDAO();
					$dao->deletar($soc);
					
					array_push($results, array(
									'codigo' => ''.$value.'',									
								));		
								
				}
				
				echo (json_encode($results));
			break;
		}

	}
	

?>
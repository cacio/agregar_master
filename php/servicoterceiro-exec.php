<?php

	

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		

		

		$act = $_REQUEST['act'];	

		

		switch($act){

			case 'inserir':
			
				$results = array();	
				$id_emp = $_REQUEST['idemp'];
										
				if(empty($_REQUEST['serv_razao'])){
					$serv_razao = "";
				}else{
					$serv_razao = $_REQUEST['serv_razao'];
				}
				
				if(empty($_REQUEST['serv_cgc'])){
					$serv_cgc = "";
				}else{
					$serv_cgc = $_REQUEST['serv_cgc'];
				}
				
				$serv  = new ServicoTerceiros();
			
				$serv->setRazaoSocial($serv_razao);
				$serv->setCgc($serv_cgc);
				$serv->setIdEmpresa($id_emp);
				
				$dao = new ServicoTerceirosDAO();
				
				$vet    = $dao->proximoid();
				$prox   = $vet[0];				
				$codigo = $prox->getIdProx();
				
				$dao->inserir($serv);
				
				array_push($results, array(
							'codigo' => ''.$codigo.'',									
							'serv_razao' => ''.$serv_razao.'',	
							'serv_cgc' => ''.$serv_cgc.'',
				));			
				
				
				echo (json_encode($results));
				
				
			break;
			
			case 'delete':
				
				$id      = $_REQUEST['id'];	
				$id_emp  = $_REQUEST['idemp'];
				$results = array();
				
				foreach($id as $key=>$value){
				
					$serv  = new ServicoTerceiros();
					
					$serv->setCodigo($value);
					$serv->setIdEmpresa($id_emp);
					
					$dao = new ServicoTerceirosDAO();
					$dao->deletar($serv);
					
					array_push($results, array(
									'codigo' => ''.$value.'',									
								));		
								
				}
				
				echo (json_encode($results));
			break;
		}

	}
	

?>
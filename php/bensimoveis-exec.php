<?php
	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){

			case 'inserir':
			
				$results = array();	
				$id_emp = $_REQUEST['idemp'];
										
				if(empty($_REQUEST['descricao'])){
					$descricao = "";
				}else{
					$descricao = $_REQUEST['descricao'];
				}
				
				if(empty($_REQUEST['endereco'])){
					$endereco = "";
				}else{
					$endereco = $_REQUEST['endereco'];
				}
				
				if(empty($_REQUEST['matricula'])){
					$matricula = "";
				}else{
					$matricula = $_REQUEST['matricula'];
				}
				
				$bens = new RelacaoBensImoveis();
			
				$bens->setDescricao($descricao);
				$bens->setEndereco($endereco);
				$bens->setMatricula($matricula);
				$bens->setIdEmpresa($id_emp);
				
				$dao = new RelacaoBensImoveisDAO();
				
				$vet    = $dao->proximoid();
				$prox   = $vet[0];				
				$codigo = $prox->getIdProx();
				
				$dao->inserir($bens);
				
				array_push($results, array(
							'codigo' => ''.$codigo.'',									
							'descricao' => ''.$descricao.'',	
							'endereco' => ''.$endereco.'',
							'matricula' => ''.$matricula.'',
				));			
				
				
				echo (json_encode($results));
				
				
			break;
			
			case 'delete':
				
				$id      = $_REQUEST['id'];	
				$id_emp  = $_REQUEST['idemp'];
				$results = array();
				
				foreach($id as $key=>$value){
				
					$bens = new RelacaoBensImoveis();
					
					$bens->setCodigo($value);
					$bens->setIdEmpresa($id_emp);
					
					$dao = new RelacaoBensImoveisDAO();
					$dao->deletar($bens);
					
					array_push($results, array(
									'codigo' => ''.$value.'',									
								));		
								
				}
				
				echo (json_encode($results));
			break;
		}

	}
	

?>
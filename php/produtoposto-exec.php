<?php

	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

			case 'inserir':




			break;

			case 'alterar':




			break;



			case 'delete':




			break;
			case 'busca':
				
				$search = $_REQUEST['searchData'];
				
				$dao    = new ProdutoPostoDAO();
				$vet    = $dao->Busca($search);
				$num    = count($vet);	
				$result = array();
				
				for($i = 0; $i < $num; $i++){
					
					$prodp  = $vet[$i];
					
					$id 	= $prodp->getId();
					$cod	= $prodp->getCodigo();
					$nome	= $prodp->getDescricao();
					$unid	= $prodp->getUnid();
					$vlunit = $prodp->getVlUnit();
					
					
					array_push($result,array(						
						'id'=>''.$id.'',
						'cod'=>''.$cod.'',
						'nome'=>''.$nome.'',
						'unid'=>''.$unid.'',
						'valor'=>''.number_format($vlunit,2,',','.').'',
					));
					
				}
				
				echo json_encode($result);
				
			break;	
		

		}

	

	

	}

	

?>
<?php
	require_once('../inc/inc.autoload.php');
	session_start();
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){

			case 'busca':
				
				$q   	= $_REQUEST['term']; 
				$dao 	= new CfopDAO();
				$vet 	= $dao->buscacfop($q);
				$num 	= count($vet);	
				$result = array();
				
				for($i = 0; $i < $num; $i++){
					
					$nat   = $vet[$i];
					
					$cod       = $nat->getCodigo();
					$nom   	   = $nat->getNome();
					$devolucao = $nat->getDevolucao();	
				
					array_push($result, array(
							'label'=>''.$cod.' - '.$nom.'',
							'value' => ''.$cod.' - '.$nom.'',
							'nome' => ''.$nom.'',
							'cod' => ''.$cod.'',							
							'dev'=>''.$devolucao.''
					));	
						
						
				}
				
				echo (json_encode($result));

			break;
			
		}

	}	

?>